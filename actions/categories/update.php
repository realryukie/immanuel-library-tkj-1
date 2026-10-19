<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $stmt = $pdo->prepare("UPDATE categories SET name = :name, description = :description WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':id' => $id
        ]);

        header("Location: ../../pages/categories/index.php");
        exit;
    } catch (PDOException $e) {
        throw $e;
    }
}

