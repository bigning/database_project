<?php
session_start();
if (!$_SESSION["user_id"]) {
    ob_start();
    header("Location: welcomePage.php");
}
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];
$user_profile = $_SESSION["user_profile"];
$user_icon = $_SESSION["user_icon"];
?>
