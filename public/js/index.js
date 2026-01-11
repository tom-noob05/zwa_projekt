const urlParams = new URLSearchParams(window.location.search);
const categoryId = urlParams.get('category_id');

function loadOffers(page = 1) {
    const contentArea = document.getElementById('main-offers-container');

    let fetchUrl = `/pages/offers.php?page=${page}`;
    if (categoryId) {
        fetchUrl += `&category_id=${categoryId}`;
    }

    fetch(fetchUrl)
        .then(res => res.json())
        .then(data => {
            const offers = data.offers;
            const totalPages = data.totalPages;

            contentArea.innerHTML = '';

            if (offers.length === 0) {
                contentArea.innerHTML = "<p style='grid-column: 1/-1; text-align: center;'>V této kategorii zatím nejsou žádné inzeráty.</p>";
            }

            offers.forEach(offer => {
                const img = offer.img_path ? offer.img_path : "/misc/1092132_polstarek-kocicka-30x45-cm.jpeg";
                const card = `
                    <div class="offer" id="${offer.id}">
                        <img src="${img}" alt="${escapeHtml(offer.title)}">
                        <p class="offer_name">${escapeHtml(offer.title)}</p>
                        <p class="condition">${offer.condition}</p>
                        <p class="offer_price">${offer.price} Kč</p>
                        <a href="pages/offer_detail.php?id=${offer.id}">see more</a>
                    </div>
                `;
                contentArea.insertAdjacentHTML('beforeend', card);
            });

            renderPagination(totalPages, page);
        })
        .catch(err => {
            console.error("Fetch error:", err);
            contentArea.innerHTML = "Chyba při načítání inzerátů.";
        });
}

function renderPagination(totalPages, currentPage) {
    const contentBox = document.querySelector('.content');
    let nav = document.getElementById('pagination-controls');

    if (!nav) {
        nav = document.createElement('div');
        nav.id = 'pagination-controls';
        contentBox.appendChild(nav);
    }

    if (totalPages <= 1) {
        nav.innerHTML = '';
        return;
    }

    nav.innerHTML = `
        <div class="pagination-wrapper">
            <button ${currentPage === 1 ? 'disabled' : ''} id="prevPage">← Předchozí</button>
            <span class="page-info">Strana ${currentPage} z ${totalPages}</span>
            <button ${currentPage === totalPages ? 'disabled' : ''} id="nextPage">Další →</button>
        </div>
    `;

    document.getElementById('prevPage').onclick = () => loadOffers(currentPage - 1);
    document.getElementById('nextPage').onclick = () => loadOffers(currentPage + 1);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', () => loadOffers(1));