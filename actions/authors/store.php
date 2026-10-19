<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_POST['store']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $name = trim($_POST['name'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        $stmt = $pdo->prepare("INSERT INTO authors (name, bio) VALUES (:name, :bio)");
        $stmt->execute([
            ':name' => $name,
            ':bio' => $bio
        ]);

        header("Location: ../../pages/authors/index.php");
        exit;
    } catch (PDOException $e) {
        throw $e;
    }
}

