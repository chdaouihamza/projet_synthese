<?php
$host = 'localhost';     
$dbname = 'agence_voyage';
$username = 'root';       
$password = 'XVCX';            
try {
    // PDO is a secure way to interact with databases from sql injection 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set PDO to throw exceptions ila w9e3 moxkil f query
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // If connection fails, show error message and stop execution
    die("Connection failed: " . $e->getMessage());
}
// Start or resume a session
// Sessions allow us to store user data (like login info) across multiple pages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// test test
?>
