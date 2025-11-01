<?php


if (session_status() == PHP_SESSION_NONE)
{
    // session_start();
    // echo "zapnula by se session<br>";
}

require_once 'config.php';

require_once APP_ROOT . '/includes/functions.php';

print_r(APP_ROOT);