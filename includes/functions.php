<?php

function testFunction($string)
{
    echo("<br>functions.php funguje! {$string} \n");
}

function destroySession()
{
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
}

function redirectIfLoggedIn()
{
    if (!empty($_SESSION['user_id']))
    {
        header('Location: /index.php');
        exit;
    }
}