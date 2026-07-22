<?php
/* File: auth.php - checks who may open private pages. */
function requireLogin()
{
    if (!isLoggedIn()) {
        setFlash('error', 'Please log in first.');
        redirect('login.php');
    }
}

function requireAdmin()
{
    if (!isAdmin()) {
        setFlash('error', 'This page is for the administrator.');
        redirect('login.php');
    }
}
?>
