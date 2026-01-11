<?php require_once 'config/init.php';
// testFunction("yayy");
// echo "user: <br>";
// print_r($_SESSION['user']);

// destroySession();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/public/styles/home.css">
</head>

<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="category">
    </div>
    <div class='content'>
        <div class='content' id="main-offers-container">
        </div>
    </div>

</body>

<script>
    function loadOffers() {
    const contentArea = document.getElementById('main-offers-container');

    fetch('/pages/offers.php')
        .then(response => response.json())
        .then(offers => {
            contentArea.innerHTML = '';

            offers.forEach(offer => {
                console.log(offer.img_path)
                const imageSource = offer.img_path ? offer.img_path : "/misc/1092132_polstarek-kocicka-30x45-cm.jpeg";
                const offerDiv = `
                    <div class="offer" id="${offer.id}">
                        <img src="${imageSource}" alt="${escapeHtml(offer.title)}">
                        <p class="offer_name">${escapeHtml(offer.title)}</p>
                        <p class="condition">${offer.condition}</p>
                        <p class="offer_price">${offer.price} Kč</p>
                        <a href="pages/offer_detail.php?id=${offer.id}">see more</a>
                    </div>
                `;
                contentArea.insertAdjacentHTML('beforeend', offerDiv);
            });
        })
        .catch(err => {
            console.error("Chyba při načítání:", err);
            contentArea.innerHTML = "<p>Nepodařilo se načíst inzeráty.</p>";
        });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', loadOffers);
</script>
</html>