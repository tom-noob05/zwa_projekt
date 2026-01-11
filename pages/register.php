<?php
require_once '../config/init.php';

redirectIfLoggedIn();

$alertMessage = null;
$redirectUrl = null;

$val_jmeno = "";
$val_prijmeni = "";
$val_email = "";
$val_username = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $val_jmeno = $_POST['jmeno'] ?? '';
    $val_prijmeni = $_POST['prijmeni'] ?? '';
    $val_email = $_POST['email'] ?? '';
    $val_username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1;");
    $stmt->execute([$val_username, $val_email]);
    $existingUser = $stmt->fetch();

    if (!empty($existingUser))
    {
        if ($existingUser['username'] === $val_username)
        {
            $alertMessage = "Username already exists!";
        } else if ($existingUser['email'] === $val_email) {
            $alertMessage = "Email already in use!";
        }
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insert = $pdo->prepare("INSERT INTO users (`jmeno`, `prijmeni`, `email`, `username`, `password` ) VALUES (?, ?, ?, ?, ?);");
        $insert->execute([$val_jmeno, $val_prijmeni, $val_email, $val_username, $hashedPassword]);

        $alertMessage = "Registered successfully!";
        $redirectUrl = "/pages/login.php";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/public/styles/navbar.css">
    <link rel="stylesheet" href="/public/styles/register.css">
</head>
<body>
    <?php include '../includes/navbar.php';?>

    <div class="wrapper">
        <div class="card">
            <h2>Registrace</h2>
            <form method="POST" action="" onsubmit="return validatePassword()">
                <label for="jmeno">Jmeno: </label>
                <input type="text" name="jmeno" value="<?php echo htmlspecialchars($val_jmeno); ?>" required><br>
                
                <label for="prijmeni">Prijmeni: </label>
                <input type="text" name="prijmeni" value="<?php echo htmlspecialchars($val_prijmeni); ?>" required><br>
                
                <label for="email">Email: </label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($val_email); ?>" required><br>
                
                <label for="username">Username: </label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($val_username); ?>" required><br>
                
                <label for="password">Password: </label>
                <input type="password" name="password" id="password" required><br>
                
                <label for="confirmPassword">Confirm password: </label>
                <input type="password" name="confirmPassword" id="confirmPassword" required><br>
                
                <input type="submit" value="Register">
            </form>

            <?php
                if (!empty($alertMessage))
                {
                    echo '<script>alert(' . json_encode($alertMessage) . ');</script>';
                }

                if (!empty($redirectUrl))
                {
                    echo '<script>window.location.href = ' . json_encode($redirectUrl) . ';</script>';
                }
            ?>
        </div>
    </div>

    <script src='/public/js/register.js'></script>
</body>
</html>