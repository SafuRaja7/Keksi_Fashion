<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ob_start();

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $emailCount = $stmt->fetchColumn();

        if ($emailCount > 0) {
            header('Location: ../register.html?error=' . urlencode('Email already exists.'));
            exit();
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $password, $role])) {
                header('Location: ../login.html');
                exit();
            } else {
                header('Location: ../register.html?error=' . urlencode('Error during registration.'));
                exit();
            }
        }
    } catch (PDOException $e) {
        header('Location: ../register.html?error=' . urlencode('Error during registration: ' . $e->getMessage()));
        exit();
    }

    ob_end_flush();
}
?>
