<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo->beginTransaction();

        $id = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $year = (int) ($_POST['year'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        $query = "UPDATE books
                  SET title = :title, isbn = :isbn, year = :year, stock = :stock, category_id = :category_id, description = :description
                  WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':isbn' => $isbn,
            ':year' => $year,
            ':stock' => $stock,
            ':category_id' => $categoryId,
            ':description' => $description,
            ':id' => $id
        ]);

        $query = "DELETE FROM book_author WHERE book_id = :book_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':book_id' => $id]);

        $authorIds = $_POST['author_ids'] ?? [];
        if (!empty($authorIds) && is_array($authorIds)) {
            $query = "INSERT INTO book_author (book_id, author_id) VALUES (:book_id, :author_id)";
            $stmt = $pdo->prepare($query);

            foreach ($authorIds as $authorId) {
                $stmt->execute([
                    ':book_id' => $id,
                    ':author_id' => (int) $authorId
                ]);
            }
        }

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
