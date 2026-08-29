<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'portfolio_db'; // Replace with your actual local database name


    // ADD THIS BLOCK TO CREATE THE CONNECTION
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
        // This catches initialization errors before your page runs
        die("Database connection failed: " . $e->getMessage());
    }
?>
