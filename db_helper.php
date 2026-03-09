<?php
session_start();
$userFile = 'data/users.json';
$bookFile = 'data/books.json';
$warningFile = 'data/warnings.json';

function loadData($file) {
    if (!file_exists($file)) file_put_contents($file, '[]');
    return json_decode(file_get_contents($file), true) ?: [];
}

function saveData($file, $data) {
    file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT));
}

function checkAuth($role = null) {
    if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }
    if ($role && $_SESSION['user']['role'] !== $role) { header("Location: index.php"); exit; }
}
?>