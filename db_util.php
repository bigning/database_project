<?php
error_reporting(-1);
$conn = new mysqli("35.164.15.98", "db", "database_project", "recipes");
if (mysqli_connect_errno()) {
    die("Connection failed: " . $conn->connect_error);
} 
?>
