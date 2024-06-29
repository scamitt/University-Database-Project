<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StudentLMS";

// Create connection
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
