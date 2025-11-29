<?php require_once '../config/init.php'; 
    
    if (empty($_SESSION['user_id']))
    {
        header("Location: /pages/login.php");
    }
    
    if (!empty($_SESSION['user_id']) && isset($pdo))
    {
        $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1;");
        $stmt->execute([$_SESSION['user_id']]);
        $fetched_data = $stmt->fetch();
        if (!empty($fetched_data)) $user = $fetched_data;
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User profile</title>
    <link rel = "stylesheet" href = "/public/styles/profile.css">
    <link rel = "stylesheet" href = "/public/styles/navbar.css">
</head>
<body>

    <?php include '../includes/navbar.php';?>

    <section id="user-info">
        <div id="pfp">
            <!-- pridat image -->
        </div>
        <div id="details">

            <table class="details-table">
                <tr>
                    <td><strong>Jmeno</strong></td>
                    <td><?php echo htmlspecialchars($user['jmeno']); ?></td>
                </tr>

                <tr>
                    <td><strong>Prijmeni</strong></td>
                    <td><?php echo htmlspecialchars($user['prijmeni']); ?></td>
                </tr>

                <tr>
                    <td><strong>Username</strong></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>

                <tr>
                    <td><strong>Email</strong></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
            </table>

        </div>
    </section>

    <section id="bought-offers">
        <h2>Bought offers</h2>
    </section>

    <section id="listed-offers">
        <h2>Listed offers</h2>
    </section>

    
</body>
</html>