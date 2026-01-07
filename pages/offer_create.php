<?php
require_once '../config/init.php'; 

//prihlaseni
if (!isset($_SESSION['user_id'])) {
    // $_SESSION['user_id'] = 1; // Pro testování
    die("Chyba: Uživatel není přihlášen.");
}

//nacteni kategorii
try {
    // Načteme ID a jméno, seřazené podle abecedy
    $stmt_cat = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Chyba při načítání kategorií: " . $e->getMessage());
}

//zpracovani formulare
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category_id = $_POST['category_id'] ?? null;
    $condition = $_POST['condition'] ?? '';
    $description = $_POST['description'] ?? '';
    
    $seller_id = $_SESSION['user_id'];
    $status = 'active';

    //vlozeni do db
    $sql = "INSERT INTO offers (`title`, `description`, `price`, `status`, `condition`, `seller_id`, `category_id`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $pdo->prepare($sql);
        
        $result = $stmt->execute([
            $title, 
            $description, 
            $price, 
            $status, 
            $condition, 
            $seller_id, 
            $category_id
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
            
            <form action="" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label for="title">Název:</label>
                    <input type="text" id="title" name="title" required placeholder="Název">
                </div>

                <div class="form-group">
                    <label for="image">Přidejte obrázek:</label>
                    <input type="file" id="image" name="image">
                </div>

                <div class="form-group">
                    <label for="price">Cena:</label>
                    <input type="number" id="price" name="price" step="10" required placeholder="1000">
                </div>

                <div class="form-group">
                    <label for="category">Kategorie:</label>
                    <select id="category" name="category_id" required>
                        <option value="" disabled selected>Vyberte kategorii</option>
                        
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Žádné kategorie nenalezeny</option>
                        <?php endif; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="condition">Kondice:</label>
                    <select id="condition" name="condition" required>
                        <option value="" disabled selected>Vyberte stav</option>
                        <option value="new">Nové</option>
                        <option value="used">Použité</option>
                        <option value="damaged">Poškozené</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Popis:</label>
                    <textarea id="description" name="description" rows="4" required placeholder="Popište prodávaný předmět..."></textarea>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">Uložit</button>
                </div>

            </form>
        </div>
    </div>
</body>
</html>