<?php
/**
 * Admin - seznam nabídek
 *
 * Tento skript renderuje stránku pro administrátora s paginovaným výpisem
 * všech inzerátů. Obsahuje jednoduchou kontrolu přihlášení a role, server‑side
 * stránkování (LIMIT/OFFSET) a bezpečné vypisování dat pomocí `htmlspecialchars()`.
 *
 * Bezpečnostní poznámky (stručně):
 *  - Pouze uživatelé s `role_id == 1` mají přístup
 *  - Výstup je escapován pro prevenci XSS
 *  - SQL používá připravené dotazy tam, kde je to nutné (i když zde nejsou vstupní řetězce)
 *
 * @package ZWA
 */
require_once '../config/init.php';

// --- Kontrola přihlášení a role ---
if (empty($_SESSION['user_id'])) {
    header("Location: /pages/login.php");
    exit;
} else {
    $userId = $_SESSION['user_id'];
}

// Načtení aktuálního uživatele z DB (použito pro kontrolu role)
if (!empty($userId) && !empty($pdo)){
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1;");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
} else {
    header("Location: /index.php");
    exit;
}

// Pokud uživatel není admin, přesměruj ho pryč
if ($user['role_id'] != 1){
    header("Location: /pages/login.php");
    exit;
}

// Stránkování: počet položek na stránku (konfigurovatelné)
$itemsPerPage = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $itemsPerPage;

try {
    $stmtCount = $pdo->query("SELECT COUNT(*) FROM offers");
    $totalOffers = $stmtCount->fetchColumn();
    
    $totalPages = ceil($totalOffers / $itemsPerPage);

    $stmtOffers = $pdo->prepare("SELECT * FROM offers ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmtOffers->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmtOffers->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmtOffers->execute();
    
    $paginatedOffers = $stmtOffers->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $paginatedOffers = [];
    $error = "Chyba při načítání inzerátů.";
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Seznam nabídek</title>
    <link rel="stylesheet" href="/public/styles/navbar.css">
    <link rel="stylesheet" href="/public/styles/admin_offer_list.css">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <main class="admin-container">
        <div class="admin-header">
            <h1>Administrace nabídek</h1>
            <a href="profile.php" class="back-link">Zpět na profil</a>
        </div>

        <div class="table-wrapper">
            <?php if (!empty($paginatedOffers)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Název (Title)</th>
                            <th>Seller ID</th>
                            <th>Cena</th>
                            <th class="actions-col">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paginatedOffers as $offer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($offer['id']); ?></td>
                                <td class="title-cell"><?php echo htmlspecialchars($offer['title']); ?></td>
                                <td><?php echo htmlspecialchars($offer['seller_id']); ?></td>
                                <td><?php echo htmlspecialchars($offer['price']); ?> Kč</td>
                                <td class="actions-cell">
                                    <a href="offer_update.php?id=<?php echo $offer['id']; ?>" class="btn-edit">Upravit</a>
                                    <a href="offer_detail.php?id=<?php echo $offer['id']; ?>" class="btn-view">Zobrazit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="page-link">&laquo; Předchozí</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="page-link <?php echo ($i === $page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="page-link">Další &raquo;</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <p class="empty-msg">V databázi nejsou žádné inzeráty.</p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>