<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'food_charity_db');

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure users table exists and has reset_token_hash and reset_token_expiry columns
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100),
        last_name VARCHAR(100),
        email VARCHAR(100) UNIQUE,
        password VARCHAR(255),
        role VARCHAR(50),
        is_approved TINYINT DEFAULT 0,
        registration_date DATETIME,
        reset_token_hash VARCHAR(255) DEFAULT NULL,
        reset_token_expiry DATETIME DEFAULT NULL
    )");
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("ERROR: Could not connect to the database. Please try again later.");
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>