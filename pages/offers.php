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
        echo json_encode($data);

    } else {
        $limit = 20;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $countSql = "SELECT COUNT(*) FROM offers WHERE `status` = 'active'";
        $totalItems = $pdo->query($countSql)->fetchColumn();
        $totalPages = ceil($totalItems / $limit);

        $sql = "SELECT `id`, `title`, `price`, `condition`, `img_path`
                FROM offers 
                WHERE `status` = 'active'
                ORDER BY created_at DESC
                LIMIT $limit OFFSET $offset;";
        
        $stmt = $pdo->query($sql);
        $offers = $stmt->fetchAll();

        echo json_encode([
            'offers' => $offers,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

} catch(\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'database_error: ' . $e->getMessage()]);
}
?>