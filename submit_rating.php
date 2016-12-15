<?php
require "./db_util.php";
require "./check_login_status.php";

if (array_key_exists("recipe_id", $_GET)) {
    $recipe_id = $_GET["recipe_id"];
}
if (array_key_exists("review_title", $_GET)) {
    $review_title = strip_tags($_GET["review_title"]);
}
if (array_key_exists("text", $_GET)) {
    $text = strip_tags($_GET["text"]);
}
if (array_key_exists("suggestions", $_GET)) {
    $suggestions = strip_tags($_GET["suggestions"]);
}
if (array_key_exists("rating", $_GET)) {
    $ratings = strip_tags($_GET["rating"]);
}

if (array_key_exists("recipe_id", $GLOBALS) && array_key_exists("review_title", $GLOBALS) && array_key_exists("ratings", $GLOBALS)) {
    // insert log 
    $query_str = "INSERT INTO Review (recipe_id, user_id, review_title, text, suggestions, ratings) VALUES (?, ?, ?, ?, ?, ?)";
    if ($query = $conn->prepare($query_str)) {
        $query->bind_param('iisssi',$recipe_id, $user_id, $review_title, $text, $suggestions,  $ratings);
        $query->execute();
        $query->close();
    } else {
        echo $conn->error;
        echo "insert log error";
    }
} else {
    header("Location: error_page.php?err_msg=please fill recipe_id, review_title, ratings");
}

header("Location: ./recipe_detail.php?recipe_id=$recipe_id");

?>
