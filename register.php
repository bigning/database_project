<?php
error_reporting(-1);
$conn = new mysqli("52.203.6.76", "db", "database_project", "recipes");
if (mysqli_connect_errno()) {
    die("Connection failed: " . $conn->connect_error);
} 
$conn->close();
?>
