<?php
$servername = "localhost";
$username = "root"; // Default XAMPP/WAMP username
$password = ""; // Default XAMPP/WAMP password
$dbname = "lifedrop_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally set the charset to utf8mb4 for proper rendering of special characters
$conn->set_charset("utf8mb4");
?>
