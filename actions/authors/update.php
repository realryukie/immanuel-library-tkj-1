<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        $stmt = $pdo->prepare("UPDATE authors SET name = :name, bio = :bio WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':bio' => $bio,
            ':id' => $id
        ]);

        header("Location: ../../pages/authors/index.php");
        exit;
    } catch (PDOException $e) {
        throw $e;
    }
}

