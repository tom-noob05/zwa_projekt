<?php 
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
            throw new Exception("Inzerát již není k dispozici.");
        }

        $insertStmt = $pdo->prepare("INSERT INTO bought_offers (user_id, offer_id, bought_at) VALUES (?, ?, NOW())");
        $insertStmt->execute([$buyerId, $offerId]);

        $pdo->commit();
        echo json_encode(['success' => true]);
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
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
            <img id="offer-img" src="../misc/default_placeholder.jpeg" alt="obrazek inzeratu">
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

        <button id="buy-btn">BUY</button>
    </div>

    <script>
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if (id) {
        fetch('offers.php?id=' + id)
            .then(res => {
                if (!res.ok) throw new Error("Inzerát nebyl nalezen");
                return res.json();
            })
            .then(offer => {
                document.getElementById('offer-name').textContent = offer.title;
                document.getElementById('offer-price').textContent = offer.price;
                document.getElementById('offer-condition').textContent = offer.condition;
                document.getElementById('offer-description').textContent = offer.description;
                document.getElementById('offer-category').textContent = offer.category_name || 'Bez kategorie';
                
                if (offer.image_path) {
                    document.getElementById('offer-img').src = offer.image_path;
                }
                document.title = offer.title + " | Detail inzerátu";

                if(offer.status == ""){
                    document.getElementById('buy-btn').hidden = true;
                }
            })
            .catch(err => {
                console.error("Chyba:", err);
                document.querySelector('.content').innerHTML = "<h2>Chyba: Inzerát se nepodařilo načíst.</h2>";
            });
    }

    document.getElementById('buy-btn').addEventListener('click', function() {
        if (!id) return;

        if (!confirm('Opravdu chcete tento předmět koupit?')) return;

        const formData = new FormData();
        formData.append('buy_offer_id', id);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Úspěšně koupeno!');
                window.location.href = 'profile.php';
            } else {
                alert('Chyba: ' + data.message);
            }
        })
        .catch(err => {
            console.error("Chyba:", err);
            alert('Nastala chyba při nákupu.');
        });
    });
    </script>
</body>
</html>