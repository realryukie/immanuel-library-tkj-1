<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_POST['store']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);

        header("Location: ../../pages/categories/index.php");
        exit;
    } catch (PDOException $e) {
        throw $e;
    }
}

