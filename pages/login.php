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

    <form method="POST" action="/index.php">
        <label for="username">Username: </label>
        <input type="text" name="username"><br>
        <label for="password">Password: </label>
        <input type="password" name="password"><br>
        <input type="submit" value="Log In">
    </form>


</body>
</html>