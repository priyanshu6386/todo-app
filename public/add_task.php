<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/functions.php';

$title = trim($_POST['title'] ?? '');

if ($title !== '') {
    $stmt = $pdo->prepare('INSERT INTO tasks (title) VALUES (:title)');
    $stmt->execute(['title' => $title]);
}

redirect();
