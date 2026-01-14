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
$errors = [];

// nacteni prihlaseneho uzivatele
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$loggedInUserId]);
    $loggedInUser = $stmt->fetch();
} catch (PDOException $e) {
    die("Chyba při ověřování uživatele.");
}

$targetId = $_GET['id'] ?? $loggedInUserId;

// kontrola opravneni
if ($targetId != $loggedInUserId && $loggedInUser['role_id'] != 1) {
    die("Nemáte oprávnění upravovat tento profil.");
}

$targetUser = null;

// nacteni ciloveho uzivatele
try {
    $stmtTarget = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmtTarget->execute([$targetId]);
    $targetUser = $stmtTarget->fetch();
    
    if (!$targetUser) {
        die("Uživatel k úpravě nebyl nalezen.");
    }
} catch (PDOException $e) {
    die("Chyba při načítání dat: " . $e->getMessage());
}

// priprava promennych pro formular (defaultne z DB)
$val_jmeno = $targetUser['jmeno'];
$val_prijmeni = $targetUser['prijmeni'];
$val_username = $targetUser['username'];
$val_email = $targetUser['email'];
$val_role_id = $targetUser['role_id'];

// zpracovani formulare
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $val_jmeno = trim($_POST['jmeno'] ?? '');
    $val_prijmeni = trim($_POST['prijmeni'] ?? '');
    $val_username = trim($_POST['username'] ?? '');
    $val_email = trim($_POST['email'] ?? '');
    
    if ($loggedInUser['role_id'] == 1) {
        $val_role_id = $_POST['role_id'] ?? $targetUser['role_id'];
    } else {
        $val_role_id = $targetUser['role_id'];
    }

    if (empty($val_jmeno) || empty($val_prijmeni) || empty($val_email) || empty($val_username)) {
        $errors[]= "Všechna pole jsou povinná.";
    }
    
    if (!filter_var($val_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Zadaný email nemá platný formát.";
    }

    if (mb_strlen($val_jmeno) > 45) {
        $errors[] = "Jméno je příliš dlouhé (max 45 znaků).";
    }
    
    if (mb_strlen($val_prijmeni) > 45) {
        $errors[] = "Příjmení je příliš dlouhé (max 45 znaků).";
    }
    
    if (mb_strlen($val_username) > 45) {
        $errors[] = "Uživatelské jméno je příliš dlouhé (max 45 znaků).";
    }
    
    if (mb_strlen($val_email) > 100) {
        $errors[] = "Email je příliš dlouhý (max 100 znaků).";
    }

    if (empty($errors)) {
        try {
            $sql = "UPDATE users SET jmeno = ?, prijmeni = ?, username = ?, email = ?, role_id = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$val_jmeno, $val_prijmeni, $val_username, $val_email, $val_role_id, $targetId]);
            
            if ($loggedInUser['role_id'] == 1 && $targetId != $loggedInUserId) {
                header('Location: /pages/admin_user_list.php');
            } else {
                header('Location: /pages/profile.php');
            }
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Uživatelské jméno nebo e-mail již používá někdo jiný.";
            } else {
                $errors[] = "Chyba databáze: " . $e->getMessage();
            }
        }
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
        
        <?php if (!empty($errors)): ?>
            <div id="error-div">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="wrapper">
            <div class="card">
                <h2>Upravit profil</h2>
                <?php if($targetUser): ?>
                    <form method="POST" action="#">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($targetUser['id']); ?>">

                        <div class="form-group">
                            <label for="jmeno">*Jméno:</label>
                            <input type="text" id="jmeno" name="jmeno" value="<?php echo htmlspecialchars($val_jmeno); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="prijmeni">*Příjmení:</label>
                            <input type="text" id="prijmeni" name="prijmeni" value="<?php echo htmlspecialchars($val_prijmeni); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="usernameInput">*Uživatelské jméno:</label>
                            <input type="text" id="usernameInput" name="username" value="<?php echo htmlspecialchars($val_username); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">*E-mail:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($val_email); ?>" required>
                        </div>

                        <?php if ($loggedInUser['role_id'] == 1): ?>
                        <div class="form-group">
                            <label for="role_id">*Role (1=Admin, 2=User):</label>
                            <select id="role_id" name="role_id">
                                <option value="1" <?php echo ($val_role_id == 1) ? 'selected' : ''; ?>>Admin (1)</option>
                                <option value="2" <?php echo ($val_role_id == 2) ? 'selected' : ''; ?>>User (2)</option>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>