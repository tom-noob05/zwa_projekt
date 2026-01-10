<?php 
require_once '../config/init.php';


$offer = null;
$offerId = $_GET['id'] ?? null;
$success = null;

try {
    $stmt_cat = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Chyba při načítání kategorií: " . $e->getMessage());
}

if (!empty($offerId)){
    try{
        $stmt = $pdo->prepare("SELECT * FROM offers WHERE id = ?;");
        $stmt->execute([$offerId]);
        $offer = $stmt->fetch();
    } catch(\PDOException $e){
        print_r($e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    try{
        $title = $_POST['title'] ?? '';
        $price = $_POST['price'] ?? 0;
        $category_id = $_POST['category_id'] ?? null;
        $condition = $_POST['condition'] ?? '';
        $description = $_POST['description'] ?? '';
        
        $seller_id = $_SESSION['user_id'];
        // $status = 'active';

        $stmt = $pdo->prepare("UPDATE offers SET title = ?, `description` = ?, `price` = ?, `category_id` = ?, `condition` = ? WHERE `id` = ?;");
        $stmt->execute([$title, $description, $price, $category_id, $condition, $offerId]);
        $success = TRUE;
    } catch(\PDOException $e) {
        print_r(json_encode(['error' => $e->getMessage()]));
    }

    if ($success) {
        header('Location: /pages/profile.php');
    }
}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Úprava nabídky</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/offer_update.css">
</head>
<body>
<?php include '../includes/navbar.php';?>

<?php if ($offer): ?>
    <div class="container">
        <div class="form-box">
            <center><h2>Úprava nabídky</h2></center>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($offer['id']); ?>">

                <div class="form-group">
                    <label for="title">Název:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($offer['title']); ?>">
                </div>

                <div class="form-group">
                    <label for="description">Popis:</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($offer['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Cena:</label>
                    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($offer['price']); ?>">
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

                <br>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">Uložit</button>
                </div>

            </form>

        </div>
    </div>
<?php else: ?>
    <p>Nabídka nebyla nalezena.</p>
<?php endif; ?>

</body>
</html>