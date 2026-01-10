<?php



if (!empty($_SESSION['user_id']) && isset($pdo))
{
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1;");
    $stmt->execute([$_SESSION['user_id']]);
    $fetched_data = $stmt->fetch();
    if (!empty($fetched_data)) $user = $fetched_data;


}

if (isset($pdo)){
    try{
    $sql = "SELECT * from `categories`;";
    $stmt = $pdo->query($sql);
    $categories = $stmt->fetchAll();
    // echo "KATEGORIE JDOU";
    }catch (\PDOException $e){
        echo json_encode(['error' => $e.getMessage()]);
    }
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

                    <?php foreach($categories as $category){ ?> 
                        <a><?php echo(htmlspecialchars($category['name'])); ?></a>
                    <?php } ?>

                </div>
            </div>
    </section>
    <?php if (!empty($user)) { ?>
        <div class='account-button'>
            <?php if ($currentPage !== 'profile.php') { ?> 
                <a href='/pages/profile.php' id='username'><?php echo(htmlspecialchars($user['username'])); ?></a> 
            <?php } ?>
            <button id='logoutbtn' class='navbar-button' >Log Out</button>
        </div>

    <?php
    } else { ?>
        <div class='account-button'>
            <button id="loginbtn" class='navbar-button'>Log In</button>
        </div>
    <?php
    }
    }
    ?>

</nav>

<script src='/public/js/navbar.js'></script>