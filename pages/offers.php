<?php 
require_once '../config/init.php'; 

header('Content-Type: application/json; charset=utf-8');

try {

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id) {
        $sql = "SELECT o.`id`, o.`title`, o.`description`, o.`price`, o.`status`, o.`condition`, 
                       o.seller_id, c.name as category_name, o.created_at, o.img_path 
                FROM offers o
                LEFT JOIN categories c ON o.category_id = c.id
                WHERE o.`id` = ? 
                LIMIT 1;";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
  
        if (!$data) {
            http_response_code(404);
            echo json_encode(['error' => 'Inzerát nenalezen']);
            exit;
        }
    } else {
        $sql = "SELECT `id`, `title`, `price`, `condition`, `img_path`
        FROM offers 
        WHERE `status` = 'active';";
        
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll();
    }

    echo json_encode($data);

} catch(\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'database_error: ' . $e->getMessage()]);
}
?>