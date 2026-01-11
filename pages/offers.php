<?php 
require_once '../config/init.php'; 
header('Content-Type: application/json; charset=utf-8');

function sanitize_img_path($path) {
    if (empty($path)) return null;
    // disallow directory traversal
    if (strpos($path, '..') !== false) return null;
    $lower = strtolower($path);
    if (strpos($lower, 'javascript:') !== false) return null;

    // Allow absolute app-local paths that start with known folders
    $allowedPrefixes = ['/public/uploads/', '/misc/'];
    if (strpos($path, '/') === 0) {
        foreach ($allowedPrefixes as $p) {
            if (strpos($path, $p) === 0) return $path;
        }
        // allow external http(s) urls
        if (strpos($lower, 'http://') === 0 || strpos($lower, 'https://') === 0) return $path;
        return null;
    }

    return null;
}

try {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $categoryId = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? (int)$_GET['category_id'] : null;

    if ($id) {
        $sql = "SELECT o.`id`, o.`title`, o.`description`, o.`price`, o.`status`, o.`condition`, 
                       o.seller_id, c.name as category_name, o.created_at, o.img_path 
                FROM offers o
                LEFT JOIN categories c ON o.category_id = c.id
                WHERE o.`id` = ? LIMIT 1;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if ($data) {
            $data['img_path'] = sanitize_img_path($data['img_path']);
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Inzerát nenalezen']);
        }
    } else {
        $limit = 20;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $where = "WHERE `status` = 'active'";
        $params = [];
        if ($categoryId) {
            $where .= " AND category_id = ?";
            $params[] = $categoryId;
        }

        $countSql = "SELECT COUNT(*) FROM offers $where";
        $stmtCount = $pdo->prepare($countSql);
        $stmtCount->execute($params);
        $totalItems = $stmtCount->fetchColumn();
        $totalPages = ceil($totalItems / $limit);

        $sql = "SELECT `id`, `title`, `price`, `condition`, `img_path` 
                FROM offers $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $offers = $stmt->fetchAll();

        // sanitize image paths
        foreach ($offers as &$off) {
            $off['img_path'] = sanitize_img_path($off['img_path']);
        }

        echo json_encode([
            'offers' => $offers,
            'currentPage' => $page,
            'totalPages' => $totalPages ?: 1
        ]);
    }
} catch(\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'database_error: ' . $e->getMessage()]);
}