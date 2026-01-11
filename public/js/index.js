const urlParams = new URLSearchParams(window.location.search);
const categoryId = urlParams.get('category_id');

function safeImgPath(path) {
    if (!path) return null;
    // disallow path traversal or javascript schemes
    if (path.indexOf('..') !== -1) return null;
    const lower = path.toLowerCase();
    if (lower.startsWith('javascript:')) return null;

    // allow absolute paths within the app (e.g. /public/uploads/ or /misc/)
    const allowedPrefixes = ['/public/uploads/', '/misc/'];
    if (path.startsWith('/')) {
        for (const p of allowedPrefixes) {
            if (path.indexOf(p) === 0) return path;
        }
        // allow if it's a safe http(s) URL
        if (lower.startsWith('http://') || lower.startsWith('https://')) return path;
        return null;
    }

    // do not allow non-absolute paths
    return null;
}

function loadOffers(page = 1) {
    const contentArea = document.getElementById('main-offers-container');

    let fetchUrl = `/pages/offers.php?page=${page}`;
    if (categoryId) {
        fetchUrl += `&category_id=${categoryId}`;
    }

    fetch(fetchUrl)
        .then(res => res.json())
        .then(data => {
            const offers = data.offers || [];
            const totalPages = data.totalPages || 1;

            contentArea.innerHTML = '';

            if (offers.length === 0) {
                const p = document.createElement('p');
                p.style.gridColumn = '1 / -1';
                p.style.textAlign = 'center';
                p.textContent = 'V této kategorii zatím nejsou žádné inzeráty.';
                contentArea.appendChild(p);
            }

            offers.forEach(offer => {
                // create card elements safely using textContent/attributes
                const card = document.createElement('div');
                card.className = 'offer';
                card.id = String(offer.id);

                const imgElem = document.createElement('img');
                const safePath = safeImgPath(offer.img_path);
                imgElem.src = safePath || '/misc/1092132_polstarek-kocicka-30x45-cm.jpeg';
                imgElem.alt = offer.title ? String(offer.title) : '';
                card.appendChild(imgElem);

                const titleP = document.createElement('p');
                titleP.className = 'offer_name';
                titleP.textContent = offer.title || '';
                card.appendChild(titleP);

                const condP = document.createElement('p');
                condP.className = 'condition';
                condP.textContent = offer.condition || '';
                card.appendChild(condP);

                const priceP = document.createElement('p');
                priceP.className = 'offer_price';
                priceP.textContent = (offer.price !== undefined && offer.price !== null) ? (offer.price + ' Kč') : '';
                card.appendChild(priceP);

                const link = document.createElement('a');
                link.href = `pages/offer_detail.php?id=${encodeURIComponent(offer.id)}`;
                link.textContent = 'Zobrazit';
                card.appendChild(link);

                contentArea.appendChild(card);
            });

            renderPagination(totalPages, page);
        })
        .catch(err => {
            console.error('Fetch error:', err);
            contentArea.innerHTML = 'Chyba při načítání inzerátů.';
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