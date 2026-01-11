<?php
/**
 * Hlavní stránka (index.php)
 *
 * Zobrazí přehled inzerátů (frontend využívá `public/js/index.js` pro načítání přes AJAX)
 * a základní navigaci. Důraz na bezpečný rendering obsahu (escape textů) v JS i PHP.
 *
 * @package ZWA
 */
require_once 'config/init.php';?>


<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domov</title>
    <link rel="stylesheet" href="/public/styles/home.css">
</head>

<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="category">
    </div>
    <div class='content'>
        <div class='content' id="main-offers-container">
        </div>
    </div>

</body>

<script src = "/public/js/index.js"></script>
</html>