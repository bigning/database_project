<?php
require "./db_util.php";
require "./check_login_status.php";

if (!array_key_exists("group_id", $_GET)) {
    header("Location: error_page.php?err_msg=please input group_id to join or quit");
}
if (!array_key_exists("is_member", $_GET)) {
    header("Location: error_page.php?err_msg=please indicate if current user is mebmer of this group");
}
$group_id = $_GET["group_id"];
$is_member = $_GET["is_member"];
$query_str = "";
if ($is_member == 1) {
    $query_str = "DELETE FROM GroupMember WHERE group_id = ? and user_id = ?";
} else {
    $query_str = "INSERT INTO GroupMember (group_id, user_id) VALUES (?, ?)";
}
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('ii', $group_id, $user_id);
    $query->execute();

    $query->close();
} else {
    echo $conn->error;
}
header("Location: ./group_detail.php?group_id=$group_id");
?>
