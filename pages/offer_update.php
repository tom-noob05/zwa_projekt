<?php 
/**
 * Úprava inzerátu (offer_update.php)
 *
 * Zajišťuje autorizaci: pouze vlastníci inzerátu nebo admin mohou upravovat.
 * Obsluha POST provádí UPDATE hodnot s použitím prepared statements.
 *
 * Očekává POST: title, description, price, category_id, condition, status (admin only)
 *
 * @package ZWA
 */
require_once '../config/init.php';

if (empty($_SESSION['user_id'])) {
    header("Location: /pages/login.php");
    exit;
}

$offer = null;
$offerId = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'];
$success = null;
$errors = [];

try {
    $stmt_cat = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $stmt_cat->fetchAll();
} catch (PDOException $e) {
    die("Chyba při načítání kategorií: " . $e->getMessage());
}

try {
    $stmt_user = $pdo->prepare("SELECT * FROM users WHERE `id` = ? LIMIT 1;");
    $stmt_user->execute([$userId]);
    $user = $stmt_user->fetch();
} catch (PDOException $e) {
    die("Chyba při načítání user: " . $e->getMessage());
}

if (!empty($offerId)) {
    try {
        if ($user['role_id'] == 1) {
            $stmt = $pdo->prepare("SELECT * FROM offers WHERE id = ?;");
            $stmt->execute([$offerId]);
        }else {
            $stmt = $pdo->prepare("SELECT * FROM offers WHERE id = ? AND seller_id = ?;");
            $stmt->execute([$offerId, $userId]);
        }
        
        $offer = $stmt->fetch();

        if (!$offer) {
            $errors[] = "Inzerát nebyl nalezen nebo nemáte oprávnění k jeho úpravě.";
        }
    } catch (\PDOException $e) {
        $errors[] = "Chyba databáze: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $offer) {
    try {
        $title = trim($_POST['title'] ?? '');
        $price = trim($_POST['price'] ?? 0);
        $category_id = trim($_POST['category_id'] ?? null);
        $condition = trim($_POST['condition'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
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

        if ($user['role_id'] == 1) {
            $status = $_POST['status'] ?? 'active';
        } else {
            $status = $offer['status'];
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("UPDATE offers SET title = ?, `description` = ?, `price` = ?, `category_id` = ?, `condition` = ?, `status` = ? WHERE `id` = ?;");
                $stmt->execute([$title, $description, $price, $category_id, $condition, $status, $offerId]);
                $success = true;
            } catch (\PDOException $e) {
                $errors[] = "Nepodařilo se uložit změny: " . $e->getMessage();
            }
        }

    } catch (\PDOException $e) {
        $errors[] = "Nepodařilo se uložit změny: " . $e->getMessage();
    }

    if ($success) {
        header('Location: /pages/profile.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Úprava nabídky | <?= htmlspecialchars($offer['title'] ?? 'Chyba') ?></title>
    <link rel="stylesheet" type="text/css" href="/public/styles/offer_update.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <div class="form-box">
        <?php if (!empty($errors)): ?>
            <div id="error-div">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if (!$offer): ?>
                <a href="/pages/profile.php" id="backtoprofile">Zpět na profil</a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($offer): ?>
            <h2>Úprava nabídky</h2>
            <form method="POST" action="#">
                <input type="hidden" name="id" value="<?= htmlspecialchars($offer['id']); ?>">

                <div class="form-group">
                    <label for="title">*Název:</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($offer['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">*Popis:</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($offer['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">*Cena (Kč):</label>
                    <input type="number" id="price" name="price" value="<?= htmlspecialchars($offer['price']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">*Kategorie:</label>
                    <select id="category" name="category_id" required>
                        <option value="" disabled>Vyberte kategorii</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($offer['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="condition">*Kondice:</label>
                    <select id="condition" name="condition" required>
                        <option value="" disabled>Vyberte stav</option>
                        <option value="new" <?= ($offer['condition'] == 'new') ? 'selected' : '' ?>>Nové</option>
                        <option value="used" <?= ($offer['condition'] == 'used') ? 'selected' : '' ?>>Použité</option>
                        <option value="damaged" <?= ($offer['condition'] == 'damaged') ? 'selected' : '' ?>>Poškozené</option>
                    </select>
                </div>
                <?php if ($user['role_id'] == 1): ?>
                <div class="form-group">
                    <label for="status">*Stav:</label>
                    <select id="status" name="status">
                        <option value="active" <?= ($offer['status'] == 'active') ? 'selected' : '' ?>>Aktivní</option>
                        <option value="bought" <?= ($offer['status'] == 'bought') ? 'selected' : '' ?>>Koupené</option>
                    </select>
                </div>
                <?php endif; ?>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">Uložit změny</button>
                    <a href="/pages/profile.php">Zrušit a vrátit se</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>