<?php

                                        /*      TODO        */
    // opravit animaci :hover prvku .account-button. Je potreba, aby se animace zobrazovala samostatne
    // pro tlacitka Log Out / Log In a vypsane username
    // problem se objevil po pridani animace tride .account-button

if (!empty($_SESSION['user_id']) && isset($pdo))
{
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1;");
    $stmt->execute([$_SESSION['user_id']]);
    $fetched_data = $stmt->fetch();
    if (!empty($fetched_data)) $user = $fetched_data;
}

$currentPage = basename($_SERVER['SCRIPT_NAME']);

?>

<link rel="stylesheet" href="/public/styles/navbar.css">
<nav class="navbar">
    <section class="links">
        <a href="/" class="nav-link"><b>Home</b></a>
        <?php if ($currentPage !== 'login.php' && $currentPage !== 'register.php'){?>
            <div class="dropdown">
                <a href="#" class="nav-link">Kategorie</a>
                <div class="dropdown-content">
                    <a>Kategorie 0</a>
                    <a>Kategorie 1</a>
                    <a>Kategorie 2</a>
                </div>
            </div>
    </section>
    <?php if (!empty($user)) { ?>
        <div class='account-button'>
            <?php if ($currentPage !== 'profile.php') { ?> 
                <a href='/pages/profile.php' id='username'><?php echo(htmlspecialchars($user['username'])); ?></a> 
            <?php } ?>
            <button id='logoutbtn' class='navbar-button' onclick="confirmLogout()">Log Out</button>
        </div>

    <?php
    } else { ?>
        <div class='account-button' onclick="location.href='/pages/login.php'">
            <button id="loginbtn" class='navbar-button'>Log In</button>
        </div>
    <?php
    }
    }
    ?>

</nav>

<script src='/public/js/navbar.js'></script>