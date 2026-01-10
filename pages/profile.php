<?php require_once '../config/init.php'; 
    
    if (empty($_SESSION['user_id'])) {
        header("Location: /pages/login.php");
        exit;
    }
    
    if (!empty($_SESSION['user_id']) && isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1;");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
if (!empty($_SESSION['user_id']) && isset($pdo)) {
    $stmt = $pdo->prepare("SELECT * FROM `offers` WHERE `seller_id` = ?;");
    $stmt->execute([$_SESSION['user_id']]);
    $offers = $stmt->fetchAll(PDO::FETCH_ASSOC); 
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil uživatele | <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="/public/styles/navbar.css">
    <link rel="stylesheet" href="/public/styles/profile.css">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <main class="profile-container">
        <section class="profile-header-card">
            <div class="pfp-container">
                <div id="pfp-placeholder">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
            
            <div class="user-main-info">
                <h1><?php echo htmlspecialchars($user['jmeno'] . ' ' . $user['prijmeni']); ?></h1>
                <p class="username-tag">@<?php echo htmlspecialchars($user['username']); ?></p>
            </div>

            <div class="user-details-grid">
                <div class="detail-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
            </div>
            
            <button class="edit-profile-btn">Upravit profil</button>
        </section>

        <div class="offers-grid">
            <section class="offer-section">
                <div class="section-header">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h2>Koupené nabídky</h2>
                </div>
                <div class="offers-list">
                    <p class="empty-msg">Zatím jste nic nekoupili.</p>
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
                        <a href="edit_offer.php?id=<?php echo $offer['id']; ?>" class="btn-edit">Upravit</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-msg">Zatím nic neprodáváte.</p>
        <?php endif; ?>
    </div>
</section>
        </div>
    </main>
</body>
</html>