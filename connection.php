<?php
// Database credentials
$host = "localhost"; 
$dbname = "facturegest"; 
$username = "root"; 
$password = ""; // your database password

try {
    // PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If connection fails, print error message
    echo "Connection failed: " . $e->getMessage();
}
?>
