<?php
include 'db_connect.php';  

header('Content-Type: application/json');

try {
    $sql = "
        SELECT p.product_id, p.name, p.description, p.price, p.category, p.stock, 
               GROUP_CONCAT(pi.image_path) as images
        FROM products p
        LEFT JOIN productImages pi ON p.product_id = pi.product_id
        GROUP BY p.product_id
    ";
    $stmt = $pdo->query($sql);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$product) {
        $product['images'] = array_map(function($image_url) {
            return 'uploads/products/' . basename($image_url);
        }, explode(',', $product['images']));
    }
    
    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
