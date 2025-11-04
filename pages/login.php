<?php
require_once '../config/init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    // natahne se zaznam z 'users'
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?;");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password']))
    {
        
        $_SESSION['user'] = $user;
        
        ?><script>
            alert("Logged In!");
        </script><?php

        header("Location: /index.php");
    }else{
        // $_SESSION['user_id'] = $user['id'];
        
        ?><script>
            alert("User doesn't exist.");
        </script><?php
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel = "stylesheet" href = "/public/styles/navbar.css">
    <link rel = "stylesheet" href = "/public/styles/login.css">
</head>
<body>
    <?php include '../includes/navbar.php';?>

    <form method="POST" action="login.php">
        <label for="username">Username: </label>
        <input type="text" name="username"><br>
        <label for="password">Password: </label>
        <input type="password" name="password"><br>
        <input type="submit" value="Log In">
        <p>Don't have an account? <a href='/pages/register.php'>Make a new one.</a>
    </form>


</body>
</html>