<?php
require_once '../config/init.php';

$alertMessage = null;
$redirectUrl = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];


    // natahne se zaznam z 'users'
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1;");
    $stmt->execute([$username, $email]);
    $existingUser = $stmt->fetch();

    if (!empty($existingUser))
    {
        if ($existingUser['username'] === $username)
        {
            $alertMessage = "Username already exists!";
        }else if ($existingUser['email'] === $email){
            $alertMessage = "Email already in use!";
        }
        

    }else
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insert = $pdo->prepare("INSERT INTO users (`jmeno`, `prijmeni`, `email`, `username`, `password` ) VALUES (?, ?, ?, ?, ?);");
        $insert->execute([$jmeno, $prijmeni, $email, $username, $hashedPassword]);

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
    <title>Register </title>
    <link rel = "stylesheet" href = "/public/styles/navbar.css">
    <link rel = "stylesheet" href = "/public/styles/register.css">
</head>
<body>
    <?php include '../includes/navbar.php';?>

    <form method="POST" action="" onsubmit="return validatePassword()">
        <label for="jmeno">Jmeno: </label>
        <input type="text" name="jmeno" required><br>
        <label for="prijmeni">Prijmeni: </label>
        <input type="text" name="prijmeni" required><br>
        <label for="email">Email: </label>
        <input type="email" name="email" required><br>
        <label for="username">Username: </label>
        <input type="text" name="username" required><br>
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

    <script src='/public/js/register.js'></script>

</body>
</html>