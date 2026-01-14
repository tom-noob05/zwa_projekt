<?php
/**
 * Vytvoření inzerátu (offer_create.php)
 *
 * Tento skript zobrazuje formulář pro vytvoření nového inzerátu a
 * zpracovává POST request pro uložení do databáze. Ověřuje, že uživatel
 * je přihlášen a bezpečně zpracovává upload obrázku (ukládá cestu do DB).
 *
 * POST parametry očekávané:
 * - title, price, category_id, condition, description, image (file)
 *
 * Bezpečnostní poznámky:
 * - Kontrolovat MIME/obsah nahraného souboru (getimagesize), velikost
 * - Používat prepared statements pro DB
 *
 * @package ZWA
 */
require_once '../config/init.php'; 

if (!isset($_SESSION['user_id'])) {
    die("Chyba: Uživatel není přihlášen.");
}

$title = '';
$price = '';
$category_id = '';
$condition = '';
$description = '';
$errors = [];

try {
    $stmt_cat = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Chyba při načítání kategorií: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $condition = trim($_POST['condition'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    $seller_id = $_SESSION['user_id'];
    $status = 'active';

    if (empty($title)) {
        $errors[] = "Název inzerátu je povinný.";
    } elseif (mb_strlen($title) > 45) {
        $errors[] = "Název inzerátu je příliš dlouhý (max 45 znaků).";
    }

    if (empty($price)) {
        $errors[] = "Cena je povinná.";
    } elseif (!filter_var($price, FILTER_VALIDATE_FLOAT) || $price < 0) {
        $errors[] = "Cena musí být kladné číslo.";
    }

    $allowedConditions = ['new', 'used', 'damaged'];
    if (empty($condition)) {
        $errors[] = "Stav zboží je povinný.";
    } elseif (!in_array($condition, $allowedConditions)) {
        $errors[] = "Neplatná hodnota pro stav zboží.";
    }

    if (empty($category_id) || !filter_var($category_id, FILTER_VALIDATE_INT)) {
        $errors[] = "Vyberte prosím platnou kategorii.";
    }

    if (empty($description)) {
        $errors[] = "Popis inzerátu je povinný.";
    }

    $imagePath = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../public/uploads/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileSize = $_FILES['image']['size'];
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $maxFileSize = 5*1024*1024; // 5 MB

        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors[] = "Povolené formáty obrázku jsou: jpg, jpeg, png, webp.";
        } elseif ($fileSize > $maxFileSize) {
            $errors[] = "Obrázek je příliš velký (max 5 MB).";
        } elseif (getimagesize($fileTmpPath) === false) {
            $errors[] = "Soubor není platný obrázek.";
        } else {
            $newFileName = uniqid('img_', true) . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = '/public/uploads/' . $newFileName;
            }
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors[] = "Chyba při nahrávání souboru (kód: " . $_FILES['image']['error'] . ").";
    }


    if (empty($errors)){
        $sql = "INSERT INTO offers (`title`, `description`, `price`, `status`, `condition`, `seller_id`, `category_id`, `img_path`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $pdo->prepare($sql);

            $result = $stmt->execute([
                $title, 
                $description, 
                $price, 
                $status, 
                $condition, 
                $seller_id, 
                $category_id,
                $imagePath
            ]);

            if ($result) {
                header("Location: ../index.php");
                exit();
            } else {
                echo "Chyba při vkládání do databáze.";
            }

        } catch (PDOException $e) {
            echo "Chyba v SQL dotazu: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/styles/offer_create.css">
    <title>Vytvořit nabídku</title>
</head>
<body>
    <?php include '../includes/navbar.php';?>
    <div class="container">
        <div class="form-box">
            <h2>Vytvořit nabídku</h2>

            <?php if (!empty($errors)): ?>
                <div class="error-div">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="#" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label for="title">*Název:</label>
                    <input type="text" id="title" name="title" required placeholder="Název" value="<?= htmlspecialchars($title) ?>">
                </div>

                <div class="form-group">
                    <label for="image">Přidejte obrázek:</label>
                    <input type="file" id="image" name="image" required accept="image/*">
                </div>

                <div class="form-group">
                    <label for="price">*Cena:</label>
                    <input type="number" id="price" name="price" required placeholder="1000" value="<?= htmlspecialchars($price) ?>">
                </div>

                <div class="form-group">
                    <label for="category">*Kategorie:</label>
                    <select id="category" name="category_id" required>
                        <option value="" disabled <?= empty($category_id) ? 'selected' : '' ?>>Vyberte kategorii</option>
                        
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>" <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Žádné kategorie nenalezeny</option>
                        <?php endif; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="condition">*Kondice:</label>
                    <select id="condition" name="condition" required>
                        <option value="" disabled <?= empty($condition) ? 'selected' : '' ?>>Vyberte stav</option>
                        <option value="new" <?= ($condition === 'new') ? 'selected' : '' ?>>Nové</option>
                        <option value="used" <?= ($condition === 'used') ? 'selected' : '' ?>>Použité</option>
                        <option value="damaged" <?= ($condition === 'damaged') ? 'selected' : '' ?>>Poškozené</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">*Popis:</label>
                    <textarea id="description" name="description" rows="4" required placeholder="Popište prodávaný předmět..."><?= htmlspecialchars($description) ?></textarea>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">Uložit</button>
                </div>

            </form>
        </div>
    </div>
</body>
</html>