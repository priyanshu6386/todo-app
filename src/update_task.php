<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/functions.php';

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');

if ($id && $title !== '') {
    $stmt = $pdo->prepare("UPDATE tasks SET title = :title WHERE id = :id");
    $stmt->execute([
        'title' => $title,
        'id' => $id
    ]);
}

redirect();
