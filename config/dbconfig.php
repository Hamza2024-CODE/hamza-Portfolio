<?php
// Automatic Environment Detection (XAMPP Localhost vs InfinityFree Hosting)
$is_local = (in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || ($_SERVER['SERVER_NAME'] ?? '') === 'localhost');

if ($is_local) {
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'portfolio_db';
} else {
    // InfinityFree Production Database Configuration
    $db_host = 'sql306.infinityfree.com';
    $db_user = 'if0_41712671';
    $db_pass = 'mx9cnfa7YhJ4W';
    $db_name = 'if0_41712671_portfolio';
}

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", 
        $db_user, 
        $db_pass, 
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
?>
