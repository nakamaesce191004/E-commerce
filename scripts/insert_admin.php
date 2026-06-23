<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=ecomers', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('INSERT INTO users (name,email,password,role,created_at,updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
    $stmt->execute(['Admin', 'admin@local', $hash, 'admin']);
    echo "Inserted admin\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
