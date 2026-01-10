<?php 
require_once '../config/init.php';

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
        
        <img id="offer-img" src="../misc/1092132_polstarek-kocicka-30x45-cm.jpeg" alt="obrazek inzeratu">
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

        <button>BUY</button>
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
                console.log("Hurá, data dorazila:", offer);

                document.getElementById('offer-name').textContent = offer.title;
                document.getElementById('offer-price').textContent = offer.price;
                document.getElementById('offer-condition').textContent = offer.condition;
                document.getElementById('offer-description').textContent = offer.description;
                
                document.getElementById('offer-category').textContent = offer.category_id;

                document.title = offer.title + " | Detail inzerátu";
            })
            .catch(err => {
                console.error("Chyba:", err);
                document.querySelector('.content').innerHTML = "<h2>Chyba: Inzerát se nepodařilo načíst.</h2>";
            });
    } else {
        console.error("V URL chybí ID inzerátu.");
    }
    </script>
</body>
</html>