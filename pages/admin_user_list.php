<?php
require_once '../config/init.php';

if (empty($_SESSION['user_id'])) {
    header("Location: /pages/login.php");
    exit;
} else {
    $userId = $_SESSION['user_id'];
}

if (!empty($userId) and !empty($pdo)){
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1;");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
} else {
    header("Location: /index.php");
    exit;
}

if ($user['role_id'] != 1){
    header("Location: /pages/login.php");
    exit;
}

?>