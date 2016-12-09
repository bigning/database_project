<?php
// return uesr_id, user_name, user_profile, user_icon
// return user's group_id, group_name, group_owner in group_rows
// return user's meeting_name, in rsvp 
// return recipe_id, recipe_title in recipes
// return recipe the user recetly looked: recipe_title,recipe_id in recent_look
error_reporting(-1);
require 'db_util.php';
require './check_login_status.php';


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
if ($query = $conn->prepare("SELECT Recipe.recipe_id, Recipe.recipe_title FROM Recipe WHERE Recipe.user_id = ?;")) {
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
if ($query = $conn->prepare("SELECT Recipe.recipe_title, Recipe.recipe_id FROM Recipe JOIN user_log ON Recipe.recipe_id = user_log.params WHERE user_log.user_id = ? AND user_log.event_name='look_recipe' ORDER BY user_log.event_time DESC limit 5;")) {
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





	

<!-- FE test -->








<!-- FE -->
<?php require "./include/partials/navHeader.php" ?>
<ul class="navbar-text navbar-right">

    <!-- User icon -->
    <img src= <?php echo $user_icon ?> class = "thumbnail" id = "user_icon">

    <!-- Drop Down -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <?php echo $user_name ?> <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="./homepage.php">My HomePage</a></li>
        <li><a href="./logout.php">Log Out</a></li>
    </ul>

</ul>

<?php require "./include/partials/navFooter.php" ?>

<div class = "page-right-div">
    <!-- Group Membership -->
    <div class = "container">
        <table class = "table">
            <thead class = "thead-default">
                <th>Group Membership</th>
            </thead>
            <tbody>
                <?php
                    if (!empty($group_rows)) {
                        foreach ($group_rows as $value) {
                            echo "<tr><td><a href='./group_detail?group_id=" .  $value["group_id"] . "'>" . $value["group_name"] . "</a></td></tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>


    <!-- RSVPs -->
    <div class = "container">
        <table class = "table">
            <thead class = "thead-default">
                <th>Upcoming Reservation</th>
            </thead>
            <tbody>
                <?php 
                if (!empty($rsvp)){
                    foreach ($rsvp as $value) {
                        echo "<tr><td>" . $value["meeting_name"] . "</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Recent looked recipe -->
    <div class = "container recentlook-div">
        <table class = "table">
            <thead class = "thead-default">
                <th>Recent Looked Recipes</th>
            </thead>
            <tbody>
                <?php 
                    if (!empty($recent_look)){
                        foreach ($recent_look as $value) {
                        echo "<tr><td><a href='./recipe_detail?recipe_id=" .  $value["recipe_id"] . "'>" . $value["recipe_title"] . "</a></td></tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>




<!-- Recipes -->
<div class = "container recipe-div">
    <table class = "table">
        <thead class = "thead-default">
            <th>My Recipes</th>
        </thead>
        <tbody>
            <?php 
                if (!empty($recipes)){
                    foreach ($recipes as $value) {
                        echo "<tr><td><a href='./recipe_detail?recipe_id=" .  $value["recipe_id"] . "'>" . $value["recipe_title"] . "</a></td></tr>";
                    }
                }
            ?>
        </tbody>
    </table>
</div>






<link rel="stylesheet" type="text/css" href="./include/css/homePage.css">
<?php require "./include/partials/footer.php" ?>
