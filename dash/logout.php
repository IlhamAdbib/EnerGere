<?php

session_start();

require_once '../connection.php';

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page after logout
header("Location: ../login.php");
exit;
?>
