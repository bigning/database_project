<?php
require 'db_util.php';

$user_name = $_POST["username"];
$pw = $_POST["password"];
$profile = $_POST["description"];
$icon_size = $_FILES["icon"]["size"];
if ($icon_size > 0) {
    $icon_save_name = "./user_icons/" . date('Ymdhms') . "_" . rand() . ".jpg";
    move_uploaded_file($_FILES["icon"]["tmp_name"], $icon_save_name);
} else {
    $icon_save_name = null;
}

$query = $conn->prepare("INSERT INTO User(user_name, user_profile, password, user_icon) VALUES (?, ?, ?, ?)");
if ($icon_size > 0) {
    $query->bind_param('ssss', $user_name, $profile, $pw, $icon_save_name);
} else {
    $query->bind_param('ssss', $user_name, $profile, $pw, $icon_save_name);
}
$query->execute();

$query_str = "SELECT user_id, user_name, user_profile, user_icon FROM User Where user_name = ? ORDER BY user_id desc";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('s', $user_name);
    $query->execute();

    $result = $query->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);
    session_start();
    $_SESSION["user_id"] = $row["user_id"];
    $_SESSION["user_name"] = $row["user_name"];
    $_SESSION["user_profile"] = $row["user_profile"];
    $_SESSION["user_icon"] = $row["user_icon"];
} else {
}
header("Location: ./homepage.php");

$conn->close();
?>
Done!
