<?php 
// return user_id, recipe_id, recipe_title IN recipes;
// For each recipe_id in recipes, return 

require "./check_login_status.php";
require "./db_util.php";

$search_word = '%%';
if ($_GET["query"]) {
    $search_word = '%' . $_GET['query'] . '%';
}

$query_str = "SELECT DISTINCT Recipe.user_id, Recipe.recipe_id, Recipe.recipe_title FROM Recipe LEFT OUTER JOIN RecipeStep ON Recipe.recipe_id = RecipeStep.recipe_id LEFT OUTER JOIN RecipeTag ON Recipe.recipe_id = RecipeTag.recipe_id Where Recipe.recipe_title like ? OR RecipeTag.tag like ? OR RecipeStep.step_description like ?";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('sss', $search_word, $search_word, $search_word);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $recipes[] = $row;
        //echo $row['recipe_title'] . '\n ';
    }
    $query->close();
} else {
    echo "query groups error";
}

$pictures = array();
$tags = array();
foreach ($recipes as $recipe) {

    // query recipe pictures
    $pictures[$recipe["recipe_id"]] = array();
    $query_str = "SELECT RecipeStep.step_image FROM RecipeStep where recipe_id = ?";
    if ($query = $conn->prepare($query_str)) {
        $query->bind_param('i', $recipe["recipe_id"]);
        $query->execute();
        $result = $query->get_result();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($pictures[$recipe["recipe_id"]], $row['step_image']);
            //echo $row['recipe_title'] . '\n ';
        }
        $query->close();
    } else {
        echo "query recipe picture error";
    }

    // query recipe pictures
    $tags[$recipe["recipe_id"]] = array();
    $query_str = "SELECT RecipeTag.tag FROM RecipeTag where recipe_id = ?";
    if ($query = $conn->prepare($query_str)) {
        $query->bind_param('i', $recipe["recipe_id"]);
        $query->execute();
        $result = $query->get_result();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($tags[$recipe["recipe_id"]], $row['tag']);
            //echo $row['recipe_title'] . '\n ';
        }
        $query->close();
    } else {
        echo "query recipe picture error";
    }
}

// test
print_r($recipes);
echo '<br/>';
print_r($pictures);
echo '<br/>';
print_r($tags);

?>
