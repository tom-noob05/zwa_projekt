<?php 
// require_once '../config/init.php';
?>

<link rel="stylesheet" href="public/styles/navbar.css">
<nav class="navbar">
    <section class="links">
    <a href="/" class="nav-link">Market place</a>
    
        <div class="dropdown">
            <a href="#" class="nav-link">Kategorie</a>
            <div class="dropdown-content">
                <a>Kategorie 0</a>
                <a>Kategorie 1</a>
                <a>Kategorie 2</a>
            </div>
        </div>
    </section>
    <?php 
        if (!empty($_SESSION['user']))
        {
        ?>
            <div class='account-button'>
                <a href='/pages/profile.php' id='username'><?php echo($_SESSION['user']['username']);?></a>
                <button id='logoutbtn' class='navbar-button' onclick="confirmLogout()">Log Out</button>
            </div>

        <?php
        }else
        { ?>
            <div class='account-button'>
                <button id="loginbtn" class='navbar-button' onclick="location.href='/pages/login.php'">Log In</button>
            </div>
        <?php
        }
    
    ?>
    
</nav>

<script src='public/js/navbar.js'></script>