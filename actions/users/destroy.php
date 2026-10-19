<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === "GET") {
    try {
        $id = (int) $_GET['id'];

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header("Location: ../../pages/users/index.php");
        exit;
    } catch (PDOException $e) {
        throw $e;
    }
}

