<?php 
require_once '../config/init.php'; 

// nastavit header pro content type
header('Content-Type: application.json; charset=utf-8');

try {

    $sql = "SELECT `id`, `title`, `description`, `price`, `status`, `condition`, seller_id, category_id, created_at FROM offers WHERE `status` = 'active';";

    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll();

    echo json_encode($data);


}catch(\PDOException $e) {
    // hodit http error a json s errorem
    http_response_code(500);
    echo json_encode(['error' => 'database_error: ' . $e->getMessage()]);
}

?>