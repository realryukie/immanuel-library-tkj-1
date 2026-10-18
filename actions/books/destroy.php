<?php
require_once __DIR__ . '/../../config.php';

if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === "GET") {
    try{
        $pdo->beginTransaction();
        $id = $_GET['id'];    

        $id = $_GET['id'];
        //Delete relation data (book author)
        $query = "delete from book_author where book_id = :book_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'book_id' => $id
        ]);

        $id = $_GET['id'];
        //Delete relation data (book author)
        $query = "delete from books where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);

        $pdo->commit();
        header("Location: ../../pages/books/index.php");
        exit;

      //Delete book data
    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }
}

?>
