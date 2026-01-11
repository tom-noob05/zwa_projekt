<?php
/**
 * Handles user login form submission and authentication.
 *
 * Expected POST params:
 *  - username: user's username
 *  - password: user's password
 *
 * On successful authentication the user's id is stored in
 * $_SESSION['user_id'] and the user is redirected to the homepage.
 *
 * @package ZWA
 */
require_once '../config/init.php';

// zalozeni promennych k pozdejsimu hlaseni chyb / vraceni hodnoty do formulare
$usernameValue = '';
$errorMsg = '';

redirectIfLoggedIn();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    // kouknout, jestli prislo neco v POST
    if(!empty($_POST))
    {
        // ulozti hodnoty z formulare
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($username) && !empty($password))
        {
            // natahne se zaznam z 'users'
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?;");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // verifikace hesla
            if ($user && password_verify($password, $user['password']))
            {
                $_SESSION['user_id'] = $user['id'];
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
    <title>Přihlášení</title>
    <link rel="stylesheet" href="../public/styles/login.css">
    <link rel = "stylesheet" href = "/public/styles/navbar.css">
</head>
<body>

    <?php include '../includes/navbar.php';?>
<div class = "wrapper">
    <div class="card">

        <h2>Přihlášení</h2>

        <div class = "error-div"> <?php echo $errorMsg ? $errorMsg : '&nbsp;'; ?> </div>

        <form method = "post" action="#">

            <div class="form-group">
                <label for="username">Uživatelské jméno</label>
                <input type="text" id="username" name="username" placeholder="Uživatelské jméno" value="<?php echo $usernameValue; ?>" required <?php echo empty($usernameValue) ? 'autofocus' : ''; ?> >
            </div>

            <div class="form-group">
                <label for="password">Heslo</label>
                <input type="password" id="password" name="password" placeholder="Heslo" required <?php echo !empty($usernameValue) ? 'autofocus' : ''; ?> >
            </div>

            <button type="submit">Přihlásit se</button>

        </form>
        <hr>
        <a href="/pages/register.php">Nemáte účet? Zaregistrujte se.</a>
        
    </div>
</div>

    <script src = "/public/js/login.js"></script>

</body>
</html>