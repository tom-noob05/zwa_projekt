<?php 
require_once '../config/init.php'; 

// Nastavení headeru pro JSON
header('Content-Type: application/json; charset=utf-8');

try {

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id) {
        $sql = "SELECT `id`, `title`, `description`, `price`, `status`, `condition`, seller_id, category_id, created_at 
                FROM offers 
                WHERE `status` = 'active' AND `id` = ? 
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
        $sql = "SELECT `id`, `title`, `price`, `condition` 
                FROM offers 
                WHERE `status` = 'active';";
        
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll();
    }

    // Odeslání dat
    echo json_encode($data);

} catch(\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'database_error: ' . $e->getMessage()]);
}
?>