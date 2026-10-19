<?php
require_once __DIR__ . '/../config/database.php';

function getUsers()
{
  global $pdo;

  $stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY name");

  return $stmt->fetchAll();
}

function getUser($id = null)
{
  global $pdo;

  if ($id === null || $id === '') {
    $stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id LIMIT 1");
    return $stmt->fetch() ?: null;
  }

  $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = :id");
  $stmt->execute([':id' => $id]);

  return $stmt->fetch() ?: null;
}

function getProfile()
{
  $profile = [
    "user_id" => 2,
    "phone"   => "0812-3456-7890",
    "address" => "Jl. Merdeka No. 21, Pontianak, Kalimantan Barat",
    "bio"     => "Murid kelas XI TKJ yang gemar membaca novel fiksi dan buku pengembangan diri.",
  ];

  return $profile;
}
