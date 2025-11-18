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
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../public/styles/login.css">
</head>
<body>

    <div class="card">
        <h2>Login</h2>
        <form>
            <input type="email" placeholder="Email:" required>
            <input type="password" placeholder="Heslo:" required>
            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>