<?php
require "./db_util.php";
require "./check_login_status.php";

$meeting_id = $_POST["meeting_id"];
$message = $_POST["message"];
$cur_time = date("Y-m-d h:i:s");

$img_save_name = null;
$img_size = $_FILES["meeting_image"]["size"];
if ($img_size > 0) {
    $img_save_name = "./meeting_img/" . date(Ymdhms) . "_" . rand() . ".jpg";
    move_uploaded_file($_FILES["meeting_image"]["tmp_name"], $img_save_name);
}
$query = $conn->prepare("INSERT INTO MeetingReport(meeting_id, user_id, message, time, meeting_image) VALUES (?, ?, ?, ?,?)");
$query->bind_param('iisss', $meeting_id, $user_id, $message, $cur_time, $img_save_name);
$query->execute();

header("Location: ./meeting_report.php?meeting_id=$meeting_id");
?>
