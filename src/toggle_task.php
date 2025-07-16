<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/functions.php';

$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $pdo->prepare('UPDATE tasks SET is_done = NOT is_done WHERE id = :id')
        ->execute(['id' => $id]);
}

redirect();
