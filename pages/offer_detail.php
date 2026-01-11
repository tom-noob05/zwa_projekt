<?php 
/**
 * Detail inzerátu + nákup (offer_detail.php)
 *
 * Zobrazuje detail inzerátu (HTML) a přijímá AJAX POST requesty pro nákup
 * (`buy_offer_id`). POST vrací JSON: { success: bool, message?: string }.
 * Transakce jsou použity pro atomické provedení UPDATE + INSERT do `bought_offers`.
 *
 * @package ZWA
 */
require_once '../config/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_offer_id'])) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Musíte se přihlásit.']);
        exit;
    }

    $offerId = $_POST['buy_offer_id'];
    $buyerId = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        $updateStmt = $pdo->prepare("UPDATE offers SET status = 'sold' WHERE id = ? AND status = 'active'");
        $updateStmt->execute([$offerId]);

        if ($updateStmt->rowCount() === 0) {
            throw new Exception("Inzerát již byl prodán nebo neexistuje.");
        }

        $insertStmt = $pdo->prepare("INSERT INTO bought_offers (user_id, offer_id, bought_at) VALUES (?, ?, NOW())");
        $insertStmt->execute([$buyerId, $offerId]);

        $pdo->commit();
        echo json_encode(['success' => true]);
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail inzerátu</title>
    <link rel="stylesheet" href="../public/styles/offer_detail.css">
    <link rel="stylesheet" href="../public/styles/navbar.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="content">
        <h1 id="offer-name" class="name">Načítám...</h1>
        
        <div class="img-container">
            <img id="offer-img" src="" alt="obrazek inzeratu" style="max-width: 100%; height: auto;">
        </div>
        
        <hr>
        <p class="price">Cena: <span id="offer-price">--</span> Kč</p>
        <hr>
        <p class="category">Kategorie: <span id="offer-category">--</span></p>
        <hr>
        <p class="condition">Kondice: <span id="offer-condition">--</span></p>
        <hr>
        <div class="description-box">
            <h3>Popis:</h3>
            <p id="offer-description" class="popis">--</p>
        </div>
        <hr>

        <div class="action-container">
    <button id="buy-btn">Koupit</button>
    
    <div id="sold-info" class="sold-info hidden">
        <span class="sold-badge">Již prodáno</span>
    </div>
</div>

    </div>

    <script src = "/public/js/offer_details.js"></script>
</body>
</html>