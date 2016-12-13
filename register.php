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

$conn->close();
?>
Done!
