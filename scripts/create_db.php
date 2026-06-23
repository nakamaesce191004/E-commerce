<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $pdo->exec('CREATE DATABASE IF NOT EXISTS ecomers CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo "DB created\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
