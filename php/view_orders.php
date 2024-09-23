<?php
include 'db_connect.php';  
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM orders");

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($orders);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
