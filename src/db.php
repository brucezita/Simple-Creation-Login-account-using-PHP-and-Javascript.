<?php
// =============================
// PDO database connection helper
// =============================
//
// We use PDO for secure database access (prepared statements)
// and to keep this connection logic reusable.

declare(strict_types=1);

// Load configuration array.
$config = require __DIR__ . '/../config/config.php';

// Create a PDO connection.
// - 'mysql:host=...;dbname=...;charset=...' tells PDO how to connect.
// - ERRMODE_EXCEPTION makes PDO throw exceptions on errors.
// - DEFAULT_FETCH_MODE controls how results are returned.
// - EMULATE_PREPARES=false uses real prepared statements where possible.
try {
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['DB_HOST'],
        $config['DB_NAME'],
        $config['DB_CHARSET']
    );

    $pdo = new PDO(
        $dsn,
        $config['DB_USER'],
        $config['DB_PASS'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // If connection fails, stop execution.
    // In real projects, you might log $e->getMessage() to a file.
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'ok' => false,
        'error' => 'Database connection failed.',
    ]);
    exit;
}

// Return the PDO instance so other files can reuse it.
return $pdo;

