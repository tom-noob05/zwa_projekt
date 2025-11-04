<?php


if (session_status() == PHP_SESSION_NONE)
{
    session_start();
    // echo "zapnula by se session<br>";
}

require_once 'config.php';

require_once APP_ROOT . '/includes/functions.php';

// print_r(APP_ROOT);

// $info = [
//     "dbhost" => DB_HOST,
//     "dbname" => DB_NAME,
//     "dbuser" => DB_USER,
//     "dbpassword" => DB_PASS
// ];

try
{
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4"; 

    $options = 
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

}catch (\PDOException $e){
    die("Chyba pri pokusu pripojeni se k databazi: <br><br>" . $e->getMessage());
}