<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === "GET") {
    try {
        $pdo->beginTransaction();
        $id = (int) $_GET['id'];

        $query = "DELETE FROM book_author WHERE book_id = :book_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':book_id' => $id]);

        $query = "DELETE FROM books WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);

        $pdo->commit();
        header("Location: ../../pages/books/index.php");
        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}
