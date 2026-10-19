<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_POST['store']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo->beginTransaction();

        $title = trim($_POST['title'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $year = (int) ($_POST['year'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);

        $query = "INSERT INTO books (title, isbn, year, stock, description, category_id)
                  VALUES (:title, :isbn, :year, :stock, :description, :category_id)";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':isbn' => $isbn,
            ':year' => $year,
            ':stock' => $stock,
            ':description' => $description,
            ':category_id' => $categoryId
        ]);

        $bookId = (int) $pdo->lastInsertId();

        $authorIds = $_POST['author_ids'] ?? [];
        if (!empty($authorIds) && is_array($authorIds)) {
            $query = "INSERT INTO book_author (book_id, author_id) VALUES (:book_id, :author_id)";
            $sstmt = $pdo->prepare($query);

            foreach ($authorIds as $authorId) {
                $sstmt->execute([
                    ':book_id' => $bookId,
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
