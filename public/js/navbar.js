function confirmLogout(){
    logout = confirm('Do you want to Log Out?');
    if (logout){
        window.location.href='/pages/logout.php';
    }
}