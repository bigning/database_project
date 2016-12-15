
<?php
require "./db_util.php";
require "./check_login_status.php";
if (!array_key_exists("group_id", $_GET)) {
    header("Location: error_page.php?err_msg=please input group_id");
}
if (!array_key_exists("meeting_name", $_GET)) {
    header("Location: error_page.php?err_msg=please input meeting_name");
}
$group_id = $_GET["group_id"];
$meeting_name = $_GET["meeting_name"];
$query_str = "INSERT INTO GroupMeeting  (group_id, meeting_name, organiser_id) VALUES (?, ?, ?)";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('isi', $group_id, $meeting_name, $user_id);
    $query->execute();

    $query->close();
    header("Location: ./group_detail.php?group_id=$group_id");
} else {
    echo $conn->error;
}
ob_start();
header("Location: ./group_detail.php?group_id=$group_id");

?>
