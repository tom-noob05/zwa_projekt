<?php
require_once '../config/init.php';

$usernameValue = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(!empty($_POST))
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($username) && !empty($password))
        {
            // natahne se zaznam z 'users'
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?;");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password']))
            {
                $_SESSION['user'] = $user;
                header("Location: /index.php");
                exit;
            } else {        
                $errorMsg = "Wrong username or password.";
            }
        }
        $usernameValue = htmlspecialchars($username);
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

        <div class = "error-div"> <?php echo $errorMsg ? $errorMsg : '&nbsp'; ?> </div>

        <form method = "post" action="">

            <input type="text" placeholder="Username:" name="username" value="<?php echo $usernameValue; ?>" 
            required <?php echo empty($usernameValue) ? 'autofocus' : ''; ?> >

            <input type="password" placeholder="Password:" name="password" required <?php echo !empty($usernameValue) ? 'autofocus' : ''; ?> >
            <button type="submit">Login</button>

        </form>

        <a href="/pages/register.php">Don't have an account? Register a new one.</a>
        
    </div>

    <script src = "/public/js/login.js"></script>

</body>
</html>