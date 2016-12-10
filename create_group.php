<?php
require "./db_util.php";
require "./check_login_status.php";
if (!array_key_exists("group_name", $_GET)) {
    header("Location: error_page.php?err_msg=please input group_name");
}
$group_name = $_GET["group_name"];
$query_str = "INSERT INTO Groups ( group_name, group_owner) VALUES (?, ?)";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('si', $group_name, $user_id);
    $query->execute();

    $query->close();
} else {
    echo $conn->error;
}

// insert into groupmember
// step 1: get gropu_id 
$query_str = "SELECT group_id FROM Groups WHERE group_name = ?";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('s', $group_name);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $group = $row;

    $query->close();
} else {
    echo $conn->error;
}

$query_str = "INSERT INTO GroupMember ( group_id, user_id ) VALUES (?, $user_id)";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $group['group_id']);
    $query->execute();

    $query->close();
} else {
    echo $conn->error;
}

header("Location: ./groups.php");
?>
