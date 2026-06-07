<?php
// =============================
// Database configuration file
// =============================
//
// Change these values to match your MySQL setup.
// In XAMPP, default host is usually 'localhost' and default user is 'root'.
// Password is often empty for local installs.

return [
    // The hostname of your MySQL server.
    'DB_HOST' => 'localhost',

    // The MySQL username.
    'DB_USER' => 'root',

    // The MySQL password.
    // For many XAMPP setups, this is an empty string.
    'DB_PASS' => '',

    // The database name that will store our users table.
    // Create the database (or adjust the name) before running the project.
    'DB_NAME' => 'simple_project',

    // Optional: connection charset.
    'DB_CHARSET' => 'utf8mb4',
];

