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
        <p>Kategorie: KOCICKY</p>
    </div>
    <div class='content'>
        <div class="offer" id="0">
            <img src="/misc/1092132_polstarek-kocicka-30x45-cm.jpeg" alt="obrazek inzeratu">
            <p class="offer_name">Kocicka 0</p>
            <a href="pages\offer_detail.php">see more</a>
        </div>
    </div>

    <!-- 
    <footer class='footer'>
        <p>Footer</p>
    </footer> -->
</body>
<script>
    // 1. Získáme referenci na kontejner, kam budeme prvky vkládat
    const contentContainer = document.querySelector('.content');

    // 2. Získáme referenci na stávající "offer" element, který použijeme jako šablonu
    // Všimněte si, že ho nemusíme kopírovat, stačí ho získat a pak klonovat.
    const originalOffer = document.getElementById('0');

    // Zabráníme nekonečnému cyklu a definujeme, kolikrát chceme "offer" zopakovat
    const countToGenerate = 203;

    /**
     * Funkce pro generování nových offer elementů
     * @param {number} startId - ID, od kterého začneme počítat (pokračujeme po posledním stávajícím)
     * @param {number} count - Kolik elementů vygenerovat
     */
    function generateOffers(startId, count) {
        if (!originalOffer || !contentContainer) {
            console.error("Chyba: Nepodařilo se najít originální offer element nebo kontejner.");
            return;
        }

        // Vytvoříme fragment pro efektivní přidávání (vložíme všechny prvky najednou)
        const fragment = document.createDocumentFragment();

        for (let i = 0; i < count; i++) {
            // Použijeme .cloneNode(true) pro vytvoření hluboké kopie (včetně všech vnitřních elementů)
            const newOffer = originalOffer.cloneNode(true);

            // Nastavíme nové, unikátní ID. (Používáme startId + i)
            newOffer.id = (startId + i).toString();

            // Volitelně změníme text pro ověření, že jde o novou kopii
            const nameParagraph = newOffer.querySelector('.offer_name');
            if (nameParagraph) {
                nameParagraph.textContent = `Kocicka ${newOffer.id}`;
            }

            fragment.appendChild(newOffer);
        }

        // Vložíme všechny nové elementy do DOM najednou
        contentContainer.appendChild(fragment);
    }

    // Spočítáme stávající počet offer elementů, abychom mohli navázat v ID (momentálně je jich 9, s ID 0-8)
    const existingOffersCount = contentContainer.querySelectorAll('.offer').length;

    // Zavoláme funkci pro generování elementů
    generateOffers(existingOffersCount, countToGenerate);
</script>

</html>