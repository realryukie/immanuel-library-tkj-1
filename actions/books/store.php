<?php
require_once __DIR__ . '/../config/database.php';
if (isset($_POST['store']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo->beginTransaction();

        $title = htmlspecialchars($_POST['title']);
        $isbn = htmlspecialchars($_POST['isbn']);
        $year = htmlspecialchars($_POST['year']);
        $stock = htmlspecialchars($_POST['stock']);
        $description = htmlspecialchars($_POST['description']);
        $category_id = htmlspecialchars($_POST['category_id']);

        $query = "insert into books (title, isbn, year, stock, description, category_id) 
                VALUES (:title, :isbn, :year, :stock, :description, :category_id)";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':isbn' => $isbn,
            ':year' => $year,
            ':stock' => $stock,
            ':description' => $description,
            ':category_id' => $category_id
        ]);

        $bookId = $pdo->lastInsertId();

        $query = "insert into book_author (book_id, author_id) VALUES (:book_id, :author_id)";
        $sstmt = $pdo->prepare($query);

        $authorIds = $_POST['author_ids'];
        foreach ($authorIds as $authorId) {
            $sstmt->execute([
                ':book_id' => $bookId,
                ':author_id' => htmlspecialchars($authorId)
            ]);
        }

        $pdo->commit();
        header("Location: ../../pages/books/index.php");
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }   
}

?>