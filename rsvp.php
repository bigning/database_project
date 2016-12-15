<?php
require "./db_util.php";
require "./check_login_status.php";

if (!array_key_exists("meeting_id", $_GET)) {
    header("Location: error_page.php?err_msg=please input meeting_id to RSVP");
}
$meeting_id = $_GET["meeting_id"];

if (!array_key_exists("is_rsvp", $_GET)) {
    header("Location: error_page.php?err_msg=please input is_rsvp to RSVP");
}
$is_rsvp= $_GET["is_rsvp"];

if (!array_key_exists("group_id", $_GET)) {
    header("Location: error_page.php?err_msg=please input group_id to RSVP");
}
$group_id = $_GET["group_id"];


if ($is_rsvp == 0) {
    $query_str = "insert into MeetingRSVP (meeting_id, user_id) values (?, ?)";
    if ($query = $conn->prepare($query_str)) {
        $query->bind_param('ii', $meeting_id, $user_id);
        $query->execute();

        $query->close();
    } else {
        echo $conn->error;
    }
} else {
    $query_str = "delete from MeetingRSVP where meeting_id = ? and user_id = ?";
    if ($query = $conn->prepare($query_str)) {
        $query->bind_param('ii', $meeting_id, $user_id);
        $query->execute();

        $query->close();
    } else {
        echo $conn->error;
    }
}
header("Location: ./group_detail.php?group_id=$group_id");
?>
