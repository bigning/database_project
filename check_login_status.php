<?php
session_start();
if (!$_SESSION["user_id"]) {
    ob_start();
    header("Location: welcomePage.php");
}
?>
