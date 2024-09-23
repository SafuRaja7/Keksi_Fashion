<?php
header('Content-Type: application/json');

$product_id = $_GET['product_id'];

if (empty($product_id)) {
    echo json_encode(['error' => 'Product ID is missing']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=keksi_fashion', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $sql_images = "SELECT image_path FROM productImages WHERE product_id = ?";
        $stmt_images = $pdo->prepare($sql_images);
        $stmt_images->execute([$product_id]);
        $images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

        $baseUrl = 'uploads/products/';
        foreach ($images as &$image) {
            $image['image_path'] = $baseUrl . basename($image['image_path']);
        }

        $product['images'] = $images;

        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
