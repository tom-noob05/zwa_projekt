let currentPage = 1;
let totalPages = 1;

function loadOffers(page = 1) {
    const contentArea = document.getElementById('main-offers-container');
    currentPage = page;

    fetch(`/pages/offers.php?page=${page}`)
        .then(response => response.json())
        .then(data => {
            const offers = data.offers;
            totalPages = data.totalPages;

            contentArea.innerHTML = '';

            offers.forEach(offer => {
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

            renderPagination();
        })
        .catch(err => {
            console.error("Chyba při načítání:", err);
            if (contentArea) contentArea.innerHTML = "<p>Nepodařilo se načíst inzeráty.</p>";
        });
}

function renderPagination() {
    const contentBox = document.querySelector('.content');
    let paginationContainer = document.getElementById('pagination-controls');

    if (!paginationContainer) {
        paginationContainer = document.createElement('div');
        paginationContainer.id = 'pagination-controls';
        contentBox.appendChild(paginationContainer);
    }

    paginationContainer.innerHTML = `
        <div class="pagination-wrapper">
            <button ${currentPage === 1 ? 'disabled' : ''} id="prevPage">← Předchozí</button>
            <span class="page-info">Strana ${currentPage} z ${totalPages}</span>
            <button ${currentPage === totalPages ? 'disabled' : ''} id="nextPage">Další →</button>
        </div>
    `;

    document.getElementById('prevPage').onclick = () => {
        if (currentPage > 1) loadOffers(currentPage - 1);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    document.getElementById('nextPage').onclick = () => {
        if (currentPage < totalPages) loadOffers(currentPage + 1);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', () => loadOffers(1));