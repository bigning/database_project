<?php
// if success login, return is_success(1) user_name, user_profile, user_icon_path
// else return is_success(0)
error_reporting(-1);
$conn = new mysqli("52.203.6.76", "db", "database_project", "recipes");
if (mysqli_connect_errno()) {
    die("Connection failed: " . $conn->connect_error);
} 

$user_name = $_GET["username"];
$pw = $_GET["password"];

$query = $conn->prepare("SELECT user_id, user_name, user_profile, user_icon FROM User WHERE user_name = ? AND password = ?");
$query->bind_param('ss', $user_name, $pw);
$query->execute();
$result = $query->get_result();
if ($result->num_rows == 0) {
    $is_success = 0;
}elseif ($result->num_rows == 1) {
    $is_success = 1;
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $user_profile = $row["user_profile"];
    $user_icon_path = $row["user_icon"];
} else {
    $is_success = 0;
    echo "don't try to inject!!";
    return;
}
$conn->close();

//test
echo $is_success;
<<<<<<< HEAD
?>
=======
?>


<!-- front end -->
>>>>>>> 1d61ba192751f6d1c910319e064b0c094f72fa00
