<?php

// If BASE_URL not defined yet, define default
if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}

// Redirect helper
function redirect(string $to = null): void
{
    if ($to === null) $to = BASE_URL . 'index.php';
    header('Location: ' . $to);
    exit;
}

// XSS-safe output helper
function h(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
