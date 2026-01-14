<?php 
/**
 * Uživatelský profil (profile.php)
 *
 * Zobrazuje informace o uživateli, jeho vlastní inzeráty a koupené položky.
 * Obsahuje také zpracování mazání inzerátu přes POST `delete_offer_id`.
 *
 * @package ZWA
 */
require_once '../config/init.php'; 
    
if (empty($_SESSION['user_id'])) {
    header("Location: /pages/login.php");
    exit;
}
    
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_offer_id'])) {
    $offerIdToDelete = $_POST['delete_offer_id'];

    try {
        $stmtDelete = $pdo->prepare("DELETE FROM offers WHERE id = ? AND seller_id = ?");
        $stmtDelete->execute([$offerIdToDelete, $userId]);
        
        header("Location: profile.php");
        exit;
    } catch (PDOException $e) {
        $error = "Chyba při mazání inzerátu.";
    }
}

if (isset($pdo)) {
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1;");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM `offers` WHERE `seller_id` = ?;");
    $stmt->execute([$userId]);
    $offers = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $stmt_bought = $pdo->prepare("
        SELECT o.*, bo.bought_at 
        FROM bought_offers bo
        JOIN offers o ON bo.offer_id = o.id
        WHERE bo.user_id = ?
        ORDER BY bo.bought_at DESC
    ");
    $stmt_bought->execute([$userId]);
    $bought_offers = $stmt_bought->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil uživatele | <?php echo htmlspecialchars($user['username'] ?? 'Uživatel'); ?></title>
    <link rel="stylesheet" href="/public/styles/navbar.css">
    <link rel="stylesheet" href="/public/styles/profile.css">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <main class="profile-container">
        <section class="profile-header-card">
            <div class="pfp-container">
            </div>
            
            <div class="user-main-info">
                <h1><?php echo htmlspecialchars(($user['jmeno'] ?? '') . ' ' . ($user['prijmeni'] ?? '')); ?></h1>
                <p class="username-tag">@<?php echo htmlspecialchars($user['username'] ?? ''); ?></p>
            </div>

            <div class="user-details-grid">
                <div class="detail-item">
                    <span><?php echo htmlspecialchars($user['email'] ?? ''); ?></span>
                </div>
            </div>
            <div class="profile-actions">
                <a href="profile_edit.php?id=<?php echo $user['id']; ?>" class="edit-profile-btn">Upravit profil</a>
                <a href="offer_create.php" class="create-offer-btn">Vytvořit inzerát</a>
            </div>
        </section>

        <div class="offers-grid">
            <section class="offer-section">
                <div class="section-header">
                    <h2>Koupené nabídky</h2>
                </div>
                <div class="offers-list">
                    <?php if (!empty($bought_offers)): ?>
                        <?php foreach ($bought_offers as $item): ?>
                            <div class="profile-offer-item">
                                <div class="info">
                                    <span class="offer-title"><?php echo htmlspecialchars($item['title']); ?></span>
                                    <span class="offer-price"><?php echo htmlspecialchars($item['price']); ?> Kč</span>
                                    <span class="offer-date">Koupeno: <?php echo date('d.m.Y', strtotime($item['bought_at'])); ?></span>
                                </div>
                                <div class="actions">
                                    <a href="offer_detail.php?id=<?php echo $item['id']; ?>" class="btn-view">Zobrazit</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-msg">Zatím jste nic nekoupili.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="offer-section">
                <div class="section-header">
                    <h2>Vaše inzeráty</h2>
                </div>
                <div class="offers-list">
                    <?php if (!empty($offers)): ?>
                        <?php foreach ($offers as $offer): ?>
                            <div class="profile-offer-item">
                                <div class="info">
                                    <span class="offer-title"><?php echo htmlspecialchars($offer['title']); ?></span>
                                    <span class="offer-price"><?php echo htmlspecialchars($offer['price']); ?> Kč</span>
                                </div>
                                <div class="actions">
                                    <a href="offer_detail.php?id=<?php echo $offer['id']; ?>" class="btn-view">Zobrazit</a>
                                    <a href="offer_update.php?id=<?php echo $offer['id']; ?>" class="btn-edit">Upravit</a>
            
                                    <form method="POST" action="#" class="delete-form">
                                        <input type="hidden" name="delete_offer_id" value="<?php echo $offer['id']; ?>">
                                        <button type="submit" class="btn-delete">Smazat</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-msg">Zatím nic neprodáváte.</p>
                    <?php endif; ?>
                </div>
            </section>

            <?php if (isset($user['role_id']) && $user['role_id'] === 1): ?> 
            <section class="offer-section">
                <div class="section-header">
                    <h2>Admin</h2>
                </div>
                
                <div class="admin-controls">
                    <a href="/pages/admin_offer_list.php" class="btn-view admin-btn">Nabídky</a>
                    <a href="/pages/admin_user_list.php" class="btn-edit admin-btn">Uživatelé</a>
                </div>
            </section>
            <?php endif; ?>

        </div>
    </main>
    <script src='/public/js/profile.js'></script>
</body>
</html>