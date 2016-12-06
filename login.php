<?php
// if success login, return is_success(1) user_name, user_profile, user_icon_path
// else return is_success(0)
error_reporting(-1);
/*
$conn = new mysqli("52.203.6.76", "db", "database_project", "recipes");
if (mysqli_connect_errno()) {
    die("Connection failed: " . $conn->connect_error);
} 
 */
require 'db_util.php';

$user_name = $_GET["username"];
$pw = $_GET["password"];

$query = $conn->prepare("SELECT user_id, user_name, user_profile, user_icon FROM User WHERE user_name = ? AND password = ?");
$query->bind_param('ss', $user_name, $pw);
$query->execute();
$result = $query->get_result();
if ($result->num_rows == 0) {
    $is_success = 0;
    header("Location: welcomePage.php?source=login_error");
}elseif ($result->num_rows == 1) {
    $is_success = 1;
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $user_profile = $row["user_profile"];
    $user_icon_path = $row["user_icon"];

    // start seesion
    session_start();
    $_SESSION["user_name"] = $user_name;
    $_SESSION["user_id"] = $row["user_id"];
    $_SESSION["user_profile"] = $user_profile;
    $_SESSION["user_icon"] = $user_icon_path;

    header("Location: homepage.php");
} else {
    $is_success = 0;
    echo "don't try to inject!!";
    return;
}
$conn->close();

//test
echo $is_success;
