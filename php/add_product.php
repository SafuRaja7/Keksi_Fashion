<?php
session_start();
include 'db_connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];

    $imagePaths = [];
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] === UPLOAD_ERR_OK) {
        $uploadsDir = __DIR__ . '/../uploads/products/';
        if (!file_exists($uploadsDir)) {
            if (!mkdir($uploadsDir, 0777, true)) {
                die('Failed to create upload directory');
            }
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $filePath = $uploadsDir . $fileName;

            if (move_uploaded_file($tmpName, $filePath)) {
                $imagePaths[] = $filePath;
            } else {
                echo "Failed to move file: $fileName";
            }
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, stock) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $category, $stock]);
        $productId = $pdo->lastInsertId();

        foreach ($imagePaths as $imagePath) {
            $stmt = $pdo->prepare("INSERT INTO productImages (product_id, image_path) VALUES (?, ?)");
            $stmt->execute([$productId, $imagePath]);
        }

        header('Location: ../admin_dashboard.html');
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Unauthorized access.";
}
?>
