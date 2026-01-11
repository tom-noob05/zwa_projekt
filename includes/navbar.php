<?php
/**
 * Navigační panel (navbar.php)
 *
 * Načítá (pokud je přihlášen) uživatele z DB pro zobrazení přihlašovacího
 * stavu a načítá seznam kategorií z DB. Tento soubor je includován do hlavních
 * layoutů a obsahuje také odkaz na tiskové styly (`print.css`).
 *
 * @package ZWA
 */
if (!empty($_SESSION['user_id']) && isset($pdo)) {
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1;");
    $stmt->execute([$_SESSION['user_id']]);
    $fetched_data = $stmt->fetch();
    if (!empty($fetched_data)) $user = $fetched_data;
}
if (isset($pdo)) {
    try {
        $sql = "SELECT * from `categories`;";
        $stmt = $pdo->query($sql);
        $categories = $stmt->fetchAll();
    } catch (\PDOException $e) {
    }
}
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>

<link rel="stylesheet" href="/public/styles/navbar.css">
<link rel="stylesheet" href="/public/styles/print.css" media="print">
<nav class="navbar">
    <section class="links">
        <div class="nav-item">
            <a href="/" class="nav-link"><b>Domů</b></a>
        </div>
        <?php if ($currentPage !== 'login.php' && $currentPage !== 'register.php'): ?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link">Kategorie</a>
                <div class="dropdown-content">
                    <a href="/">Všechny kategorie</a>
                    <?php foreach($categories as $category): ?> 
                        <a href="/index.php?category_id=<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <section class="account-section">
        <?php if (!empty($user)): ?>
            <?php if ($currentPage !== 'profile.php'): ?> 
                <div class="nav-item clickable" onclick="location.href='/pages/profile.php'">
                    <span id="username"><?php echo htmlspecialchars($user['username']); ?></span>
                </div>
            <?php endif; ?>
            
            <div class="nav-item clickable" onclick="confirmLogout()">
                <button id="logoutbtn" class="navbar-button">Odhlásit se</button>
            </div>
        <?php else: ?>
            <div class="nav-item clickable" onclick="location.href='/pages/login.php'">
                <button id="loginbtn" class="navbar-button">Přihlásit se</button>
            </div>
        <?php endif; ?>
    </section>
</nav>
<script src='/public/js/navbar.js'></script>