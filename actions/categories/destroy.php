<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === "GET") {
    try {
        $id = (int) $_GET['id'];

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM books WHERE category_id = :id");
        $stmt->execute([':id' => $id]);
        if ((int) $stmt->fetchColumn() > 0) {
            http_response_code(409);
            exit('Kategori masih digunakan oleh buku dan tidak dapat dihapus.');
        }

        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header("Location: ../../pages/categories/index.php");
        exit;
    } catch (PDOException $e) {
        throw $e;
    }
}
