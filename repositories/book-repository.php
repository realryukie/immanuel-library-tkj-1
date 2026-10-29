<?php
require_once __DIR__ . '/../config/database.php';

function getBooks($search, $categoryId, $currentPage, $perPage)
{
  global $pdo;

  $query = "select b.id, b.title, b.stock, c.name as category from books b 
            left join categories c on b.category_id = c.id 
            where b.title like :search 
            and (:category_filter = '' or b.category_id = :category_id) 
            limit :limit offset :offset
            ";

  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'search' => "%$search%",
    'category_filter' => $categoryId,
    'category_id' => $categoryId, 
    'limit' => $perPage,
    'offset' => ($currentPage - 1) * $perPage
  ]);

  $books = $stmt->fetchAll();

  $query = "select ba.book_id, a.name from book_author ba 
            left join authors a on ba.author_id = a.id";

  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $bookAuthors = $stmt->fetchAll();

  $bookByAuthors = [];
  foreach ($bookAuthors as $bookAuthor) {
    $bookByAuthors[$bookAuthor['book_id']][] = $bookAuthor['name'];
  }

  foreach ($books as $index => $book) {
    $books[$index]['authors'] = $bookByAuthors[$book['id']] ?? [];
  }

  return $books;
}

function getBook($id = null)
{
  global $pdo;

  if ($id === null || $id === '') {
    $books = getBooks();
    return $books[0] ?? null;
  }

  $query = "select b.id, b.title, b.isbn, b.year, b.stock, b.category_id, b.description, c.name as category from books b left join categories c on b.category_id = c.id where b.id = :id";

  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'id' => $id
  ]);

  $book = $stmt->fetch();

  if (!$book) {
    return null;
  }

  $query = "select a.id, a.name from book_author ba 
            left join authors a on ba.author_id = a.id
            where ba.book_id = :book_id";

  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'book_id' => $book['id']
  ]);

  $authors = $stmt->fetchAll();
  $book['author_ids'] = array_column($authors, 'id');
  $book['authors'] = array_column($authors, 'name');

  return $book;

function isIsbnTaken($isbn, $ignoreBookId){
  global $pdo;

  $bookId = intval($ignoreBookId);
    
  $query = "select count(id) from books where isbn = :isbn && id != :book_id";

  $stmt = $pdo->prepare($query);
  $stmt->execute(['isbn' => $isbn, 'book_id' => $bookId]);

  $isIsbnTaken = $stmt->fetchColumn() > 0;

  return $isIsbnTaken;
}

function isCategoryExist($categoryId){
  global $pdo;

  $query = "select count(id) from categories where id = :id";

  $stmt = $pdo->prepare($query);
  $stmt->execute(['category_id' => $categoryId]);

  $isCategoryExist = $stmt->fetchColumn() > 0;

  return $isCategoryExist;
}

function countOfBooks($search, $categoryId)
{
  global $pdo;

  $query = "select count(id) from books
            where title like :title and 
            (:category_filter = '' or category_id = :category_id)";
  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'title' => "%search%",
    'category_filter' => $categoryId,
    'category_id' => $categoryId,
  ]);
}

}