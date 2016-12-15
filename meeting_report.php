<?php
// return meeting_id,meeting_name, group_id, group_name, organiser_id, organiser_name IN meeting_info
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
$query_str = "SELECT MeetingReport.meeting_id, User.user_id, User.user_name, MeetingReport.message, MeetingReport.time, MeetingReport.meeting_image FROM MeetingReport JOIN User ON MeetingReport.user_id = User.user_id WHERE MeetingReport.meeting_id = ?";
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







<!-- FE test -->




<!-- FE -->
<!-- Navbar -->
<?php require "./include/partials/navHeader.php" ?>

<?php require "./include/partials/navRight.php" ?>

<?php require "./include/partials/navFooter.php" ?>


<!-- meeting name -->
<div class = "container">
    <div class = "jumbotron meeting-div">
        <?php 
            echo "<h1>" . $meeting_info["meeting_name"] . "</h1>";
            echo "<p>" . $meeting_info["group_name"] . " group</p>";
            echo "<p> Host by " . $meeting_info["organiser_name"] . "</p>";
        ?>
    </div>
</div>


<div class = "container">
    <div class = "jumbotron report-div">
        <div class = "meeting-header">
            <h2>Meeting Reports</h2>

            <!-- create meeting button -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-meeting">
              New Comment
            </button>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="create-meeting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">New Comment</h4>
              </div>
              <div class="modal-body">
                <form method="POST" action="./create_meeting_report.php">
                    <input type="hidden" name="meeting_id" value="<?php echo $meeting_info["meeting_id"] ?>">
                    <textarea class="form-control" placeholder="Leave your comment here" name = "message" rows = "2" required></textarea>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>

        <?php 
            if (!empty($meeting_reports)){
                foreach ($meeting_reports as $value) {
                    echo "<div class = 'report-tuple'>";
                    echo "<p class = 'report-message'>" . $value["message"] . "</p>";
                    if(!empty($value["meeting_image"])){
                        echo "<img src='" . $value["meeting_image"] . "' class = 'thumbnail meeting-image'></p>";
                    }
                    echo "<p class = 'report-name-n-time'>By " . $value["user_name"] . " on " . $value["time"] . "</p>";
                    echo "</div>";
                }
            }
        ?>
    </div>
</div>



<link rel="stylesheet" type="text/css" href="./include/css/meeting_report.css">
<script type="text/javascript" src = "./include/framework/jquery-3.1.1.min.js"></script>
<?php require "./include/partials/footer.php" ?>
