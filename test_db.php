<?php

echo "<h1>Test pripojeni k DB</h1>";
echo "<p>pokousim se nacist config/init.php...</p>";

// 1. Pokusíme se načíst init.php
// Pokud je v config.php chyba (špatné heslo, host...), 
// tak se init.php sám ukončí (pomocí die()) a vypíše chybu.
require_once 'config/init.php';

echo "<p style='color:green; font-weight:bold;'>config/init.php nacten uspesne!</p>";
echo "<p style='color:green; font-weight:bold;'>pripojeni k DB probehlo v pohode.</p>";

echo "<hr>";
echo "<p>zkousim testovaci dotaz 'SELECT 1 + 1 AS result'...</p>";

try {
    // Použijeme proměnnou $pdo, kterou vytvořil init.php
    $stmt = $pdo->query("SELECT 1 + 1 AS result");
    $row = $stmt->fetch();
    
    echo "<p>Výsledek: " . $row['result'] . "</p>";
    if ($row['result'] == 2) {
        echo "<h2 style='color:blue;'>super, vse funguje</h2>";
        echo "<br>";
        print_r($row);
    }

} catch (PDOException $e) {
    echo "<p style='color:red; font-weight:bold;'>CHYBA pri provadeni dotazu: " . $e->getMessage() . "</p>";
}

try {
    $stmt = $pdo->query("select * from users;");
    $row = $stmt->fetchAll();
    echo "<pre>";
    print_r($row);
    echo "</pre>";
} catch (PDOException $e) {
    echo "<p style='color:red; font-weight:bold;'>CHYBA pri provadeni dotazu: " . $e->getMessage() . "</p>";
}
?>
