/*
 * navbar.js — obslužné funkce pro navigaci (logout/login tlačítka)
 */function confirmLogout() {
    logout = confirm('Opravdu se chcete odhlásit?');
    if (logout) {
        window.location.href = '/pages/logout.php';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutbtn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            confirmLogout();
        });
    }

    const loginBtn = document.getElementById('loginbtn');

    if (loginBtn) {
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = "/pages/login.php";
        });
    }
});

//pridat eventlistenery na logoutbtn a loginbtn, odebrat onclick() z html