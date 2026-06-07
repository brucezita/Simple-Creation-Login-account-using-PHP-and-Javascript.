<?php
// =============================
// Authentication (signup/login) logic
// =============================
//
// This file centralizes auth logic so it can be reused by API endpoints.

declare(strict_types=1);

// Start a session for logged-in users.
// Sessions allow us to persist login state across requests.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get PDO connection.
$pdo = require __DIR__ . '/db.php';

// Helper function: send JSON response and exit.
function json_response(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Helper function: validate email format.
function validate_email(string $email): bool {
    // FILTER_VALIDATE_EMAIL checks if email has a valid format.
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Signup: create a new user.
function signup(string $email, string $password): void {
    global $pdo; // Use the PDO connection created in db.php.

    // Trim spaces from input.
    $email = trim($email);

    // Basic validation.
    if ($email === '' || $password === '') {
        json_response(['ok' => false, 'error' => 'Email and password are required.'], 400);
    }

    if (!validate_email($email)) {
        json_response(['ok' => false, 'error' => 'Invalid email format.'], 400);
    }

    // Password policy (simple but helpful for learning):
    // You can change these later for future projects.
    if (strlen($password) < 8) {
        json_response(['ok' => false, 'error' => 'Password must be at least 8 characters.'], 400);
    }

    // Hash the password.
    // password_hash() creates a secure one-way hash.
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists.
    // Prepared statement prevents SQL injection.
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $existing = $stmt->fetch();

    if ($existing) {
        json_response(['ok' => false, 'error' => 'Email already registered.'], 409);
    }

    // Insert new user.
    $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, created_at) VALUES (?, ?, NOW())');
    $stmt->execute([$email, $passwordHash]);

    json_response(['ok' => true, 'message' => 'Account created successfully. Please login.'], 201);
}

// Login: verify user credentials.
function login(string $email, string $password): void {
    global $pdo; // Use PDO.

    $email = trim($email);

    // Validation.
    if ($email === '' || $password === '') {
        json_response(['ok' => false, 'error' => 'Email and password are required.'], 400);
    }

    if (!validate_email($email)) {
        json_response(['ok' => false, 'error' => 'Invalid email format.'], 400);
    }

    // Fetch user by email.
    $stmt = $pdo->prepare('SELECT id, email, password_hash FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // If user not found, reject.
    if (!$user) {
        json_response(['ok' => false, 'error' => 'Invalid email or password.'], 401);
    }

    // Verify provided password against stored hash.
    $isValid = password_verify($password, $user['password_hash']);

    if (!$isValid) {
        json_response(['ok' => false, 'error' => 'Invalid email or password.'], 401);
    }

    // Set session values after successful login.
    // Store a user identifier, not the password.
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user_email'] = $user['email'];

    json_response([
        'ok' => true,
        'message' => 'Login successful.',
        'user' => [
            'id' => (int)$user['id'],
            'email' => $user['email'],
        ],
    ], 200);
}

// Logout (optional but useful for future projects).
function logout(): void {
    // Destroy session data.
    $_SESSION = [];

    // Destroy the session cookie.
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    // End session.
    session_destroy();

    json_response(['ok' => true, 'message' => 'Logged out.'], 200);
}

