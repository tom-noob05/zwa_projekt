<?php 
/**
 * Editace uživatelského profilu.
 * * - Běžný uživatel: Může upravovat pouze své vlastní údaje.
 * - Admin (role_id=1): Může upravovat libovolného uživatele (přes GET id) a měnit jeho roli.
 * - Obsahuje logiku pro načtení dat, validaci oprávnění a zpracování formuláře (POST).
 */
require_once '../config/init.php';

if (empty($_SESSION['user_id'])) {
    header("Location: /pages/login.php");
    exit;
}

$loggedInUserId = $_SESSION['user_id'];
$loggedInUser = null;

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$loggedInUserId]);
    $loggedInUser = $stmt->fetch();
} catch (PDOException $e) {
    die("Chyba při ověřování uživatele.");
}

$targetId = $_GET['id'] ?? $loggedInUserId;

if ($targetId != $loggedInUserId && $loggedInUser['role_id'] != 1) {
    die("Nemáte oprávnění upravovat tento profil.");
}

$targetUser = null;
$error = null;
$success = false;

try {
    $stmtTarget = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmtTarget->execute([$targetId]);
    $targetUser = $stmtTarget->fetch();
    
    if (!$targetUser) {
        die("Uživatel k úpravě nebyl nalezen.");
    }
} catch (PDOException $e) {
    $error = "Chyba při načítání dat: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jmeno = $_POST['jmeno'] ?? '';
    $prijmeni = $_POST['prijmeni'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($loggedInUser['role_id'] == 1) {
        $role_id = $_POST['role_id'] ?? $targetUser['role_id'];
    } else {
        $role_id = $targetUser['role_id'];
    }

    if (empty($jmeno) || empty($prijmeni) || empty($username) || empty($email)) {
        $error = "Všechna pole jsou povinná.";
    } else {
        try {
            $sql = "UPDATE users SET jmeno = ?, prijmeni = ?, username = ?, email = ?, role_id = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$jmeno, $prijmeni, $username, $email, $role_id, $targetId]);
            
            $success = true;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Uživatelské jméno nebo e-mail již používá někdo jiný.";
            } else {
                $error = "Chyba databáze: " . $e->getMessage();
            }
        }
    }

    if ($success) {
        if ($loggedInUser['role_id'] == 1 && $targetId != $loggedInUserId) {
            header('Location: /pages/admin_user_list.php');
        } else {
            header('Location: /pages/profile.php');
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Upravit profil | <?php echo htmlspecialchars($targetUser['username']); ?></title>
    <link rel="stylesheet" type="text/css" href="/public/styles/profile_edit.css">
</head>
<body>
<?php include '../includes/navbar.php';?>

<div class="container">
    <div class="form-box">
        <center><h2>Upravit profil</h2></center>
        
        <?php if ($error): ?>
            <p style="color: #ff6b6b; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <div class="wrapper">
            <div class="card">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($targetUser['id']); ?>">

                    <div class="form-group">
                        <label for="jmeno">Jméno:</label>
                        <input type="text" id="jmeno" name="jmeno" value="<?php echo htmlspecialchars($targetUser['jmeno']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="prijmeni">Příjmení:</label>
                        <input type="text" id="prijmeni" name="prijmeni" value="<?php echo htmlspecialchars($targetUser['prijmeni']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Uživatelské jméno:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($targetUser['username']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($targetUser['email']); ?>" required>
                    </div>

                    <?php if ($loggedInUser['role_id'] == 1): ?>
                    <div class="form-group">
                        <label for="role_id">Role (1=Admin, 2=User):</label>
                        <select id="role_id" name="role_id" required>
                            <option value="1" <?php echo ($targetUser['role_id'] == 1) ? 'selected' : ''; ?>>Admin (1)</option>
                            <option value="2" <?php echo ($targetUser['role_id'] == 2) ? 'selected' : ''; ?>>User (2)</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <br>

                    <div class="form-footer">
                        <button type="submit" class="btn-submit">Uložit změny</button>
                        
                        <?php if ($loggedInUser['role_id'] == 1 && $targetId != $loggedInUserId): ?>
                            <a href="/pages/admin_user_list.php">Zrušit</a>
                        <?php else: ?>
                            <a href="/pages/profile.php">Zrušit</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>