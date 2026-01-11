<?php
/**
 * Stránka pro správu uživatelů (pouze pro Admina).
 * - Ověřuje oprávnění (přístup pouze pro role_id = 1).
 * - Zobrazuje seznam všech uživatelů v tabulce se stránkováním.
 * - Umožňuje přechod na detailní editaci profilu každého uživatele.
 */

require_once '../config/init.php';

// kontrola prihlaseni
if (empty($_SESSION['user_id'])) {
    header("Location: /pages/login.php");
    exit;
} else {
    $currentUserId = $_SESSION['user_id'];
}

// kontrola role
if (!empty($currentUserId) && !empty($pdo)){
    $stmt = $pdo->prepare("SELECT role_id FROM `users` WHERE `id` = ? LIMIT 1;");
    $stmt->execute([$currentUserId]);
    $currentUser = $stmt->fetch();

    if (!$currentUser || $currentUser['role_id'] != 1){
        header("Location: /index.php");
        exit;
    }
} else {
    header("Location: /index.php");
    exit;
}

// strankovani
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// nacteni uzivatelu
try {

    $stmtCount = $pdo->query("SELECT COUNT(*) FROM users");
    $totalUsers = $stmtCount->fetchColumn();
    $totalPages = ceil($totalUsers / $limit);

    $stmtUsers = $pdo->prepare("SELECT * FROM users ORDER BY id ASC LIMIT ? OFFSET ?");
    $stmtUsers->bindValue(1, $limit, PDO::PARAM_INT);
    $stmtUsers->bindValue(2, $offset, PDO::PARAM_INT);
    $stmtUsers->execute();
    
    $allUsers = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $allUsers = [];
    $error = "Chyba při načítání uživatelů: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Seznam uživatelů</title>
    <link rel="stylesheet" href="/public/styles/navbar.css">
    <link rel="stylesheet" href="/public/styles/admin_user_list.css">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <main class="admin-container">
        <div class="admin-header">
            <h1>Administrace uživatelů</h1>
            <a href="profile.php" class="back-link">Zpět na profil</a>
        </div>

        <div class="table-wrapper">
            <?php if (isset($error)): ?>
                <p style="color: red; text-align: center;"><?php echo $error; ?></p>
            <?php endif; ?>

            <?php if (!empty($allUsers)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Jméno a příjmení</th>
                            <th>Email</th>
                            <th>Role ID</th>
                            <th class="actions-col">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsers as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['id']); ?></td>
                                <td class="username-cell"><?php echo htmlspecialchars($u['username']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($u['jmeno']) . ' (' . htmlspecialchars($u['prijmeni']) . ')'; ?>
                                </td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['role_id']); ?></td>
                                <td class="actions-cell">
                                    <a href="profile_edit.php?id=<?php echo $u['id']; ?>" class="btn-edit">Upravit</a>
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
                        <a href="?page=<?php echo $i; ?>" class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="page-link">Další &raquo;</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <p class="empty-msg">V databázi nejsou žádní uživatelé.</p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>