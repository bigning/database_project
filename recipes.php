<?php 
require "./check_login_status.php";
require "./db_util.php";

$search_word = '%%';
if ($_GET["query"]) {
    $search_word = '%' . $_GET['query'] . '%';
}

if ($query = $conn->prepare("SELECT DISTINCT Recipe.recipd_id, Recipe.recipe_title FROM Recipe LEFT OUTER JOIN ")) {
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

?>
