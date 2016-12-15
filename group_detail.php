<?php
// return group_id, group_name, group_owner, user_icon(owner), owner_name IN group
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
$query_str = "SELECT Groups.group_id, Groups.group_name, Groups.group_owner,User.user_name AS owner_name, User.user_icon AS owner_icon FROM Groups JOIN User ON Groups.group_owner = User.user_id WHERE Groups.group_id = ?";
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
// echo "<br/><br/>group: <br/>";
// print_r($group);
// echo "<br/><br/>is_member: <br/>";
// print_r($is_member);
// echo "<br/><br/>meetings: <br/>";
// print_r($meetings);
// echo "<br/><br/>is_rsvps: <br/>";
// print_r($is_rsvp);
// echo "<br/><br/>curr_user: <br/>";
// print($user_id);

?>






<!-- FE test -->




<!-- FE -->
<!-- Navbar -->
<?php require "./include/partials/navHeader.php" ?>

<?php require "./include/partials/navRight.php" ?>

<?php require "./include/partials/navFooter.php" ?>


<!-- group title -->
<div class = "container">
    <div class = "jumbotron">

        <!-- join-quit-form -->
        <form method= "GET" action="./join_quit.php" id = "join-quit-form">
            <input type = "hidden" name = "group_id" value = "<?php echo $group_id ?>" />
            <input type = "hidden" name = "is_member" value = "<?php echo $is_member ?>" />
        </form>


        <?php 
            // title
            echo "<h1 id = 'group-title'>" . $group["group_name"] . "</h1>";



            // join or quit button
            if ($is_member){
                if ($user_id === $group["group_owner"]){
                    echo "<button type='button' class='btn btn-lg btn-danger join-quit-button' disabled>Quit Group</button>";
                } else {
                    echo "<button type='submit' form = 'join-quit-form' class='btn btn-lg btn-danger join-quit-button' >Quit Group</button>";
                }
            } else {
                echo "<button type='submit' form = 'join-quit-form' class='btn btn-lg btn-success join-quit-button' >Join Group</button>";
            }

            // group owner
            echo "<div class = 'host-div'>";
            echo "<span>" . "Owner: " . "</span>";
            if ($group["owner_icon"] != null){
                echo '<img src="' . $group["owner_icon"] . '" class = "thumbnail user_icon" >';
            }
            echo "<span>" . $group["owner_name"] . "</span>";
            echo "</div>";
        ?>
    </div>
</div>



<!-- Meetings -->
<div class = "container">
    <div class = "jumbotron">
        <div>
            <h2 id = "meeting-header">Meetings</h2>

            <!-- creating meeting button -->
            <?php 
                if($is_member){
                    echo "<button type='button' class='btn btn-primary meeting-button' data-toggle='modal' data-target='#create-meeting'>New Meeting</button>";
                }
            ?>
        </div>
        
        

        <!-- Modal -->
        <div class="modal fade" id="create-meeting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Create Meeting</h4>
                    </div>
                    <div class="modal-body">
                        
                        <!-- Form -->
                        <form class="form-inline" method="GET" action="./create_meeting.php" id = "meeting-form">
                            <div class="form-group">
                                <label for = "meeting-name">Meeting Name</label>
                                <input type="text" class = "form-control" name="meeting_name" id = "meeting-name" placeholder="Meeting Name" required>
                            </div>
                            <input type="hidden" name="group_id" value="<?php echo $group['group_id'] ?>">
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="meeting-form">Save changes</button>
                    </div>
                </div>
            </div>
        </div>


        <?php  

            //list all meetings
            if (!empty($meetings)){
                foreach ($meetings as $value) {
                    echo "<div class = 'meeting-tuple'>";

                    // Meeting title
                    echo "<a class = 'meeting-title' href='./meeting_report.php?meeting_id=" . $value["meeting_id"] . "'>" . $value["meeting_name"] . "</a>";

                    // Meeting organizer 
                    echo "<div class = 'organizer'>";
                    echo "<span>" . "Host by: " . "</span>";
                    if ($value["user_icon"] != null){
                        echo '<img src="' . $value["user_icon"] . '" class = "thumbnail user_icon" >';
                    }
                    echo "<span>" . $value["user_name"] . "</span>";
                    echo "</div>";

                    // RSVP
                    if($is_member){
                        if ($is_rsvp[$value["meeting_id"]]){
                            echo "<button type='button' class='btn btn-sm btn-success rsvp-button' disabled>RSVPed</button>";
                        } else {
                            echo "<a class = 'btn btn-sm btn-warning rsvp-button' href='./rsvp.php?meeting_id=" . $value["meeting_id"] . "&is_rsvp=". $is_rsvp[$value["meeting_id"]] . "'>" . "RSVP" . "</a>";
                        }
                    }
                
                    echo "</div>";
                }
            }
        ?>
    </div>
</div>



<link rel="stylesheet" type="text/css" href="./include/css/group_detail.css">
<script type="text/javascript" src = "./include/framework/jquery-3.1.1.min.js"></script>
<?php require "./include/partials/footer.php" ?>
