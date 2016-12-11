<?php
// return meeting_name, group_id, group_name, organiser_id, organiser_name IN meeting_info
// 
require "./db_util.php";
require "./check_login_status.php";

if (!array_key_exists("meeting_id", $_GET)) {
    header("Location: error_page.php?err_msg=please input meeting_id");
}
$meeting_id = $_GET["meeting_id"];
$query_str = "SELECT GroupMeeting.meeting_name, GroupMeeting.group_id, Groups.group_name, User.user_id AS organiser_id, User.user_name as organiser_name FROM GroupMeeting JOIN User ON GroupMeeting.organiser_id = User.user_id JOIN Groups ON GroupMeeting.group_id = Groups.group_id WHERE GroupMeeting.meeting_id = ?";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $meeting_id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);

    if ($result->num_rows <= 0) {
        header("Location: ./error_page.php?err_msg=incorrect meeting_id");
    }
    $meeting_info = $row;

    $query->close();
} else {
    echo $conn->error;
}

//get user_id, message, time, meeting_image 
$meeting_reports = array();
$query_str = "SELECT User.user_id, User.user_name, MeetingReport.message, MeetingReport.time, MeetingReport.meeting_image FROM MeetingReport JOIN User ON MeetingReport.user_id = User.user_id WHERE MeetingReport.meeting_id = ?";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $meeting_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($meeting_reports, $row);
    }
    $query->close();
} else {
    echo $conn->error;
}


// test
echo "<br/><br/>meeting_info: <br/>";
print_r($meeting_info);
echo "<br/><br/>meeting_reports: <br/>";
print_r($meeting_reports);
?>
