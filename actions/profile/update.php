<?php
require_once __DIR__ . '/../../config/database.php';

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $id = (int) ($_POST['user_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':id' => $id
            ]);
        }

        $tableCheck = $pdo->query("SHOW TABLES LIKE 'profiles'");
        if ($tableCheck && $tableCheck->rowCount() > 0 && $id > 0) {
            $stmt = $pdo->prepare("INSERT INTO profiles (user_id, phone, address, bio)
                VALUES (:user_id, :phone, :address, :bio)
                ON DUPLICATE KEY UPDATE phone = VALUES(phone), address = VALUES(address), bio = VALUES(bio)");
            $stmt->execute([
                ':user_id' => $id,
                ':phone' => $phone,
                ':address' => $address,
                ':bio' => $bio
            ]);
        }

        header("Location: ../../pages/profile/edit.php");
        exit;
    } catch (PDOException $e) {
        throw $e;
    }
}

