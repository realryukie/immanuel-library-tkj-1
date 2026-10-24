<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../validations/book-validation.php';

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo->beginTransaction();

        $request = getBookRequest($_POST);
        $errors = validateBook($request, $request['id']);

        if (count($errors) > 0) {   
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $request;

            header("Location: ../../pages/books/edit.php?id=" . urlencode($request['id']));
            exit;
        }

        $id = htmlspecialchars($_POST['id'] ?? 0);
        $title = htmlspecialchars($_POST['title'] ?? '');
        $isbn = htmlspecialchars($_POST['isbn'] ?? '');
        $year = htmlspecialchars($_POST['year'] ?? 0);
        $stock = htmlspecialchars($_POST['stock'] ?? 0);
        $categoryId = htmlspecialchars($_POST['category_id'] ?? 0);
        $description = htmlspecialchars($_POST['description'] ?? '');

        $query = "UPDATE books
                  SET title = :title, isbn = :isbn, year = :year, stock = :stock, category_id = :category_id, description = :description
                  WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':id' => $request['id'],
            ':title' => $request['title'],
            ':isbn' => $request['isbn'],
            ':year' => $request['year'],
            ':stock' => $request['stock'],
            ':category_id' => $request['category_id'],
            ':description' => $request['description']
        ]);

        $query = "delete from book_author where book_id = :book_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':book_id' => $request['id']]);

        $query = 'insert into book_author (book_id, author_id) values (:book_id, :author_id)';
        $stmt = $pdo->prepare($query);

        $authorIds = isset($request['author_ids']) ? $request['author_ids'] : [];
        foreach ($authorIds as $authorId) {
            $stmt->execute([
                ':book_id' => $request['id'],
                ':author_id' => htmlspecialchars($authorId)
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
