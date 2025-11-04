<?php 
// require_once '../config/init.php';
?>


<nav class="navbar">
    <a href="/">Community Marketplace</a>
    <select id="categories-dropdown">
        <option>Kategorie 0<option>
        <option>Kategorie 1<option>
        <option>Kategorie 2<option>    
    </select>
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

<script src='/public/js/navbar.js'></script>