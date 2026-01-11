<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlásit se</title>
    <link rel="stylesheet" href="../public/styles/sign_in.css">
</head>
<body>

    <div class="card">
        <h2>Přihlásit se</h2>
        <form>
            <label for="sign_name">Jméno:</label>
            <input id="sign_name" type="text" placeholder="Jméno:" required>

            <label for="sign_email">Email:</label>
            <input id="sign_email" type="email" placeholder="Email:" required>

            <label for="sign_password">Heslo:</label>
            <input id="sign_password" type="password" placeholder="Heslo:" required>

            <button type="submit">Přihlásit se</button>
        </form>
    </div>

</body>
</html>