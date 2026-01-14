/*
 * navbar.js — obslužné funkce pro navigaci (logout/login tlačítka)
 */function confirmLogout() {
    logout = confirm('Opravdu se chcete odhlásit?');
    if (logout) {
        window.location.href = '/pages/logout.php';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutdiv');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            confirmLogout();
        });
    }

    const loginBtn = document.getElementById('logindiv');

    if (loginBtn) {
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = "/pages/login.php";
        });
    }

    const profileBtn = document.getElementById('profilediv');

    if(profileBtn) {
        profileBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = "/pages/profile.php";
        });
    }

});