<?php 
require_once '../config/init.php';

if (empty($_SESSION['user_id'])) {
    header("Location: /pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$user = null;
$error = null;
$success = false;

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        die("Uživatel nenalezen.");
    }
} catch (PDOException $e) {
    $error = "Chyba při načítání dat: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jmeno = $_POST['jmeno'] ?? '';
    $prijmeni = $_POST['prijmeni'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($jmeno) || empty($prijmeni) || empty($username) || empty($email)) {
        $error = "Všechna pole jsou povinná.";
    } else {
        try {
            $sql = "UPDATE users SET jmeno = ?, prijmeni = ?, username = ?, email = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$jmeno, $prijmeni, $username, $email, $userId]);
            
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
        header('Location: /pages/profile.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Upravit profil</title>
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
            <div class="form-group">
                <label for="jmeno">Jméno:</label>
                <input type="text" id="jmeno" name="jmeno" value="<?php echo htmlspecialchars($user['jmeno']); ?>" required>
            </div>

            <div class="form-group">
                <label for="prijmeni">Příjmení:</label>
                <input type="text" id="prijmeni" name="prijmeni" value="<?php echo htmlspecialchars($user['prijmeni']); ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Uživatelské jméno:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <br>

            <div class="form-footer">
                <button type="submit" class="btn-submit">Uložit změny</button>
                <a href="/pages/profile.php">Zrušit</a>
            </div>
        </form>
        </div>
        </div>
    </div>
</div>

</body>
</html>