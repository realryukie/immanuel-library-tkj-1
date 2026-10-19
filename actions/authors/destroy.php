<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === "GET") {
    try {
        $id = (int) $_GET['id'];
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("DELETE FROM book_author WHERE author_id = :id");
        $stmt->execute([':id' => $id]);

        $stmt = $pdo->prepare("DELETE FROM authors WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $pdo->commit();
        header("Location: ../../pages/authors/index.php");
        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}
