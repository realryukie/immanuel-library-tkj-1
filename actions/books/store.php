<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../validations/book-validation.php';

if (isset($_POST['store']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo->beginTransaction();

        $request = getBookRequest($_POST);
        $errors = validateBook($request);

        if (count($errors) > 0) {   
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $request;

            header("Location: ../../pages/books/create.php");
            exit;
        }

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
            ':title' => $request['title'],
            ':isbn' => $request['isbn'],
            ':year' => $request['year'],
            ':stock' => $request['stock'],
            ':description' => $request['description'],
            ':category_id' => $request['category_id']
        ]);

        $bookId = (int) $pdo->lastInsertId();

        $authorIds = $_POST['author_ids'] ?? [];
        
        $query = "INSERT INTO book_author (book_id, author_id) VALUES (:book_id, :author_id)";
        $sstmt = $pdo->prepare($query);

        $authorIds = $request['author_ids'];
        foreach ($authorIds as $authorId) {
            $sstmt->execute([
                ':book_id' => $bookId,
                ':author_id' => (int) $authorId
            ]);
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
