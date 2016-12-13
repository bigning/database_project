<?php
// return group_id, group_name, group_owner, user_icon(owner) IN group
// return is_member (1 or 0)
// return meeting_id, meeting_name, organiser_id, user_name(organiser), user_icon(organiser) in meetings
// return is_rsvp for each meeting, e.g. is_rsvp[1] = 1 means current user is rsvp for meeting 1
require "./db_util.php";
require "./check_login_status.php";

if (!array_key_exists("group_id", $_GET)) {
    header("Location: error_page.php?err_msg=please input recipe_id");
}
$group_id = $_GET["group_id"];
$group = array();
$query_str = "SELECT Groups.group_id, Groups.group_name, Groups.group_owner, User.user_icon AS owner_icon FROM Groups JOIN User ON Groups.group_owner = User.user_id WHERE Groups.group_id = ?";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $group_id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $group = $row;

    $query->close();
} else {
    echo $conn->error;
}

if (!isset($group)) {
    header("Location: error_page.php?err_msg=wrong group id");
}

// get is_member
$query_str = "SELECT Groups.group_id FROM Groups JOIN GroupMember ON Groups.group_id = GroupMember.group_id WHERE Groups.group_id = ? AND GroupMember.user_id = ?";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('ii', $group_id, $user_id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
        $is_member = 1;
    } else {
        $is_member = 0;
    }

    $query->close();
} else {
    echo $conn->error;
}

//get meeting_id, meeting_name, organiser_id, user_name(organiser), user_icon(organiser) 
$query_str = "SELECT GroupMeeting.meeting_id, GroupMeeting.meeting_name, GroupMeeting.organiser_id, User.user_name, User.user_icon FROM GroupMeeting JOIN User ON GroupMeeting.organiser_id = User.user_id WHERE GroupMeeting.group_id = ?";
$meetings = array();
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $group_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($meetings, $row);
    }
    $query->close();
} else {
    echo $conn->error;
    echo "query recipe picture error";
}

// is_rsvp
$is_rsvp = array();
foreach ($meetings as $meeting) {
    $is_rsvp[$meeting["meeting_id"]] = 0;
    $query_str = "SELECT meeting_id FROM MeetingRSVP WHERE meeting_id = ? and user_id = ?";
    if ($query = $conn->prepare($query_str)) {
        $query->bind_param('ii', $meeting["meeting_id"], $user_id);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            $is_rsvp[$meeting["meeting_id"]] = 1;
        }
        $query->close();
    } else {
        echo $conn->error;
        echo "query recipe picture error";
    }
}

// test
echo "<br/><br/>group: <br/>";
print_r($group);
echo "<br/><br/>is_member: <br/>";
print_r($is_member);
echo "<br/><br/>meetings: <br/>";
print_r($meetings);
echo "<br/><br/>is_rsvps: <br/>";
print_r($is_rsvp);

?>






<!-- FE test -->



<!-- FE -->
<!-- Navbar -->
<?php require "./include/partials/navHeader.php" ?>

<?php require "./include/partials/navRight.php" ?>

<?php require "./include/partials/navFooter.php" ?>


<script type="text/javascript" src = "./include/framework/jquery-3.1.1.min.js"></script>
<?php require "./include/partials/footer.php" ?>
