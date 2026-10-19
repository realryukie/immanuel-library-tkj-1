<?php
require_once __DIR__ . '/../config/database.php';

function getCategories()
{
  global $pdo;

  $query = "select c.id, c.name, c.description, count(b.id) as total_books
            from categories c
            left join books b on b.category_id = c.id
            group by c.id, c.name, c.description
            order by c.name";
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $categories = $stmt->fetchAll();

  return $categories;
}

function getCategory($id = null)
{
  if ($id === null || $id === '') {
    return null;
  }

  global $pdo;

  $stmt = $pdo->prepare("SELECT id, name, description FROM categories WHERE id = :id");
  $stmt->execute([':id' => $id]);

  return $stmt->fetch() ?: null;
}
