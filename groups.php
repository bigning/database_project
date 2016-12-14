<?php 
// return group_id, group_name, group_owner, user_icon, member_number IN groups.

require "./check_login_status.php";
require "./db_util.php";

$search_word = '%%';
if (array_key_exists("query", $_GET)) {
    $search_word = '%' . $_GET['query'] . '%';
}
$query_str = "SELECT Groups.group_id, Groups.group_name, Groups.group_owner, User.user_name, User.user_icon, count(*) AS member_numer FROM Groups INNER JOIN User ON Groups.group_owner = User.user_id INNER JOIN GroupMember ON Groups.group_id = GroupMember.group_id WHERE Groups.group_name LIKE ? GROUP BY Groups.group_id, Groups.group_name, Groups.group_owner, User.user_id, User.user_name, User.user_icon";
$groups = array();
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('s', $search_word);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $recipes[] = $row;
        //echo $row['recipe_title'] . '\n ';
        array_push($groups, $row);
    }
    $query->close();
} else {
    echo $conn->error;
    echo "query groups error";
}

// test
// echo '<br/><br/>groups: <br/>';
// print_r($groups);
?>





<!-- FE test -->



<!-- FE -->
<!-- Navbar -->
<?php require "./include/partials/navHeader.php" ?>

<?php require "./include/partials/navRight.php" ?>

<?php require "./include/partials/navFooter.php" ?>


<div class = "container">
    <div class = "jumbotron">
        <h2>Group Search Result</h2>
        <?php 
            if (!empty($groups)){
                foreach ($groups as $value) {
                    echo "<div class = 'group-tuple'>";
                    echo "<a href='./group_detail.php?group_id=" . $value["group_id"] . "'>" . $value["group_name"]. "</a>";
                    echo "</div>";
                }
            }
        ?>
    </div>
</div>


<link rel="stylesheet" type="text/css" href="./include/css/groups.css">
<script type="text/javascript" src = "./include/framework/jquery-3.1.1.min.js"></script>
<?php require "./include/partials/footer.php" ?>



