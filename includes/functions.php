<?php
// Very small helper to redirect & quit
function redirect(string $to = BASE_URL): void
{
    header('Location: '.$to);
    exit;
}

// Basic XSS‑safe output
function h(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
