<?php
// return uesr_id, user_name, user_profile, user_icon
// return user's group_id, group_name, group_owner in group_rows
// return user's meeting_name, in rsvp 
// return recipe_title in recipes
// return recipe the user recetly looked: recipe_title,recipe_id in recent_look
error_reporting(-1);
require 'db_util.php';
echo $conn->info;

session_start();
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];
$user_profile = $_SESSION["user_profile"];
$user_icon = $_SESSION["user_icon"];

// query groups

if ($query = $conn->prepare("SELECT Groups.group_id, Groups.group_name, Groups.group_owner FROM Groups JOIN GroupMember ON Groups.group_id=GroupMember.group_id WHERE GroupMember.user_id = ?;")) {
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $group_rows[] = $row;
    }
    $query->close();
} else {
    echo "query groups error";
}

// query recipes created by this user
if ($query = $conn->prepare("SELECT Recipe.recipe_title FROM Recipe WHERE Recipe.user_id = ?;")) {
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();
    while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
        $recipes[] = $row;
    }
    $query->close();
} else {
    echo mysqli_error($conn);
    echo "query recipes wrong";
}

// query GroupMeetingRSVP
if ($query = $conn->prepare("SELECT Groups.group_id, Groups.group_name, Groups.group_owner, GroupMeeting.meeting_id, GroupMeeting.meeting_name, GroupMeeting.organiser_id FROM Groups JOIN GroupMember ON Groups.group_id=GroupMember.group_id JOIN GroupMeeting ON Groups.group_id=GroupMeeting.group_id JOIN MeetingRSVP ON GroupMeeting.meeting_id=MeetingRSVP.meeting_id WHERE GroupMember.user_id = ?;")) {
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();
    while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
        $rsvp[] = $row;
    }
    $query->close();
} else {
    echo "rsvp query wrong";
}

// query recipes user recently looked at
if ($query = $conn->prepare("SELECT Recipe.recipe_title, Recipe.recipe_id FROM Recipe JOIN user_log ON Recipe.recipe_id = user_log.params WHERE user_log.user_id = ?;")) {
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();
    while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
        $recent_look[] = $row;
    }
    $query->close();
} else {
    echo mysqli_error($conn);
    echo "query recipes wrong";
}

?>





	
<!-- homepage FE -->

<!-- test -->
<?php 
if ($user_icon === null){
    echo "no picture";
}
?>






<?php require "./include/partials/navHeader.php" ?>
<p class="navbar-text navbar-right">
	<?php 
		echo '<span>' . $user_name . '</span>';
		echo '<img class = "thumbnail" id = "user_icon" src="' . $user_icon . '">';
	?>
</p>


<?php require "./include/partials/navFooter.php" ?>
<?php 
    echo("<h1> group information</h1>");
    foreach ($group_rows as $value) {
        echo '<h1>' . $value["group_name"] . '</h1>';
    }
?>


<link rel="stylesheet" type="text/css" href="./include/css/homePage.css">
<?php require "./include/partials/footer.php" ?>
