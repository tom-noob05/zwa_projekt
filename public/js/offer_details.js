const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

if (id) {
    fetch('offers.php?id=' + id)
        .then(res => {
            if (!res.ok) throw new Error("Inzerát nebyl nalezen v databázi.");
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
            }

            document.title = (offer.title || "Detail") + " | Detail inzerátu";

            if (offer.status == "") {
                document.getElementById('buy-btn').hidden = true;
            }
        })
        .catch(err => {
            console.error("Chyba při načítání:", err);
            document.querySelector('.content').innerHTML = "<h2>Chyba: Inzerát se nepodařilo načíst. Zkontrolujte konzoli.</h2>";
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
                location.reload();
            }
        })
        .catch(err => {
            console.error("Chyba při nákupu:", err);
            alert('Nastala chyba při komunikaci se serverem.');
        });
});