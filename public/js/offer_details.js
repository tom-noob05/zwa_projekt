/*
 * offer_details.js — načítá detail inzerátu a implementuje AJAX nákup (POST)
 * - Očekává JSON z `pages/offers.php?id=...`
 * - Provádí POST s `buy_offer_id` a ošetřuje JSON odpověď
 */
const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

if (id) {
    fetch('offers.php?id=' + id)
        .then(res => {
            if (!res.ok) throw new Error("Inzerát nebyl nalezen.");
            return res.json();
        })
        .then(offer => {
            document.getElementById('offer-name').textContent = offer.title || 'Bez názvu';
            document.getElementById('offer-price').textContent = offer.price || '0';
            document.getElementById('offer-condition').textContent = offer.condition || '--';
            document.getElementById('offer-description').textContent = offer.description || '--';
            document.getElementById('offer-category').textContent = offer.category_name || 'Bez kategorie';

            if (offer.img_path) {
                document.getElementById('offer-img').src = ".." + offer.img_path;
            } else {
                document.getElementById('offer-img').src = "/misc/1092132_polstarek-kocicka-30x45-cm.jpeg";
            }

            if (offer.status == "") {
                document.getElementById('buy-btn').classList.add('hidden');
                document.getElementById('sold-info').classList.remove('hidden');
            }

            document.title = (offer.title || "Detail") + " | Detail inzerátu";
        })
        .catch(err => {
            console.error("Chyba při načítání:", err);
            document.querySelector('.content').innerHTML = "<h2>Inzerát se nepodařilo načíst.</h2>";
        });
}

document.getElementById('buy-btn').addEventListener('click', function () {
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
                if (data.message.includes("přihlásit")) {
                    window.location.href = 'login.php';
                } else {
                    location.reload();
                }
            }
        })
        .catch(err => {
            console.error("Chyba při nákupu:", err);
            alert('Nastala chyba při komunikaci se serverem.');
        });
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}