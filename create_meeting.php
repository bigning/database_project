
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
?>
