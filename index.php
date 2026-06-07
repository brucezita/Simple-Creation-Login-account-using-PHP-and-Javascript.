<?php
// =============================
// Project entry point
// =============================
//
// Redirect to the real UI in /public.
//
// Note:
// - When you open http://localhost/PHP/, this file runs.
// - All other files live under /public, /api, /src, /config.

header('Location: /PHP/public/index.php');
exit;

