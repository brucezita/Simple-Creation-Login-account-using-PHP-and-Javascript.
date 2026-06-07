<?php
// =============================
// Login API endpoint
// =============================
//
// Accepts JSON request body:
// {"email": "...", "password": "..."}
// and returns JSON response.

declare(strict_types=1);

// Only accept POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => 'Method not allowed.']);
    exit;
}

// Read raw request body.
$raw = file_get_contents('php://input');

// Decode JSON.
$data = json_decode($raw, true);

// If JSON is invalid, reject.
if (!is_array($data)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON.']);
    exit;
}

// Extract input fields.
$email = (string)($data['email'] ?? '');
$password = (string)($data['password'] ?? '');

// Load login logic.
require __DIR__ . '/../src/auth.php';

// Call login.
login($email, $password);

