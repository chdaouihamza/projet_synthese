<?php
// ============================================
// LOGOUT PAGE
// Destroys user session and redirects to login
// ============================================

session_start();  // Start the session
session_destroy(); // Destroy all session data (logs user out)
header("Location: login.php"); // Redirect to login page
exit();
?>