<?php
// =============================
// Frontend UI page (Signup + Login)
// =============================
//
// This is a simple single-page UI.
// It calls API endpoints (api/signup.php and api/login.php) using JavaScript.

// If the user is already logged in (session exists), show a welcome message.
// Note: This UI is minimal; future projects can add more features.
session_start();

$userEmail = $_SESSION['user_email'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PHP Auth Demo</title>
  <link rel="stylesheet" href="/PHP/public/styles.css" />
</head>
<body>
  <main class="container">
    <h1>PHP Login / Signup (Demo)</h1>

    <?php if ($userEmail): ?>
      <!-- If logged in, show info -->
      <div class="card">
        <p class="success">✅ Logged in as: <strong><?= htmlspecialchars($userEmail) ?></strong></p>
        <p class="muted">You can extend this project later (logout, profile, password reset, etc.).</p>
      </div>
    <?php else: ?>
      <!-- If not logged in, show signup + login forms -->
      <div class="grid">
        <section class="card">
          <h2>Create Account</h2>

          <form id="signupForm">
            <label>
              Email
              <input type="email" name="email" autocomplete="email" required />
            </label>

            <label>
              Password
              <input type="password" name="password" autocomplete="new-password" required />
            </label>

            <button type="submit">Sign Up</button>
          </form>
        </section>

        <section class="card">
          <h2>Login</h2>

          <form id="loginForm">
            <label>
              Email
              <input type="email" name="email" autocomplete="email" required />
            </label>

            <label>
              Password
              <input type="password" name="password" autocomplete="current-password" required />
            </label>

            <button type="submit">Login</button>
          </form>
        </section>
      </div>
    <?php endif; ?>

    <!-- Message area for success/error feedback -->
    <div id="message" class="message" aria-live="polite"></div>
  </main>

  <script src="/PHP/public/app.js"></script>
</body>
</html>

