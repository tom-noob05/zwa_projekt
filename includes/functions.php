<?php
/**
 * Utility functions used across the application.
 *
 * Contains small helper functions for session management and
 * other common tasks used by page controllers.
 *
 * @package ZWA
 */

/**
 * Simple debug helper that prints a message to the page.
 * Use only for quick debugging; remove or replace with proper
 * logging in production.
 *
 * @param string $string Message to print
 * @return void
 */
function testFunction($string)
{
    echo("<br>functions.php funguje! {$string} \n");
}

/**
 * Destroys the current session and clears session cookie data.
 * Ensures session cookie is removed when cookies are in use.
 *
 * @return void
 */
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

/**
 * Redirects the current request to the homepage when an authenticated
 * user is already logged in. Useful to prevent showing login/register
 * pages to authenticated users.
 *
 * @return void
 */
function redirectIfLoggedIn()
{
    if (!empty($_SESSION['user_id']))
    {
        header('Location: /index.php');
        exit;
    }
}