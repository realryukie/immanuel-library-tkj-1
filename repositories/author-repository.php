<?php
require_once __DIR__ . '/../config/database.php';

function getAuthors()
{
  global $pdo;

  $query = "select a.id, a.name, count(distinct ba.book_id) as total_books
            from authors a
            left join book_author ba on ba.author_id = a.id
            group by a.id, a.name
            order by a.name";
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $authors = $stmt->fetchAll();

  return $authors;
}

function getAuthor($id = null)
{
  if ($id === null || $id === '') {
    return null;
  }

  global $pdo;

  $stmt = $pdo->prepare("SELECT id, name, bio FROM authors WHERE id = :id");
  $stmt->execute([':id' => $id]);

  return $stmt->fetch() ?: null;
}
