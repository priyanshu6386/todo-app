<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/functions.php';

$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $pdo->prepare('DELETE FROM tasks WHERE id = :id')->execute(['id' => $id]);
}

redirect();
