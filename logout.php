<?php
/* File: logout.php - removes the user from the session. */
require_once __DIR__ . '/includes/bootstrap.php';
unset($_SESSION['user']);
setFlash('success', 'You have been logged out.');
redirect('index.php');
?>
