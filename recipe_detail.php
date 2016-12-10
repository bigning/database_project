<?php
// return recipe_id, recipe_title, recipe_name, user_id , user_icon, num_servings IN recipe
// return recipe_id, ingredients, quantity, unit IN ingredients 
// return recipe_id, step_id, step_description, step_image IN steps
// return tags in tags
// return review_id, recipe_id, user_id, user_name, review_title, text, suggestions, ratings
// return relate_to, recipe_title, user_id, num_servings IN relate_recipes
require "./db_util.php";
require "./check_login_status.php";

if (!array_key_exists("recipe_id", $_GET)) {
    header("Location: error_page.php?err_msg=please input recipe_id");
}
$recipe_id = $_GET["recipe_id"];

// get recipe_name, owner_name, owner_icon, num_servings.
$query_str = "SELECT Recipe.recipe_id, Recipe.recipe_title, Recipe.user_id, Recipe.num_servings, User.user_icon FROM Recipe JOIN User ON Recipe.user_id = User.user_id  where recipe_id = ?";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $recipe_id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $recipe = $row;

    $query->close();
} else {
    echo $conn->error;
    echo "query recipe picture error";
}

if (!isset($recipe)) {
    header("Location: error_page.php?err_msg=can't find this recipe");
}

// get recipe_id, ingredients, quantity, unit.
$query_str = "SELECT recipe_id, ingredients, quantity, unit FROM RecipeIngredient where recipe_id = ?";
$ingredients = array();
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $recipe_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($ingredients, $row);
    }
    $query->close();
} else {
    echo $conn->error;
    echo "query recipe picture error";
}

// get recipe_id, step_id, step_description, step_image,
$query_str = "SELECT recipe_id, step_id, step_description, step_image FROM RecipeStep where recipe_id = ?";
$steps = array();
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $recipe_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($steps, $row);
    }
    $query->close();
} else {
    echo $conn->error;
    echo "query recipe picture error";
}

// get tags
$query_str = "SELECT recipe_id, tag FROM RecipeTag where recipe_id = ?";
$tags = array();
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $recipe_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($tags, $row);
    }
    $query->close();
} else {
    echo $conn->error;
    echo "query recipe picture error";
}

// get relate_to, recipe_title, user_id, num_servings 
$query_str = "SELECT RecipeRelation.relate_to, Recipe.recipe_title, Recipe.user_id, Recipe.num_servings FROM RecipeRelation JOIN Recipe ON RecipeRelation.relate_to = Recipe.recipe_id where RecipeRelation.recipe_id = ?";
$relate_recipes = array();
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $recipe_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($relate_recipes, $row);
    }
    $query->close();
} else {
    echo $conn->error;
    echo "query recipe picture error";
}

// get review_id, recipe_id, user_id, user_name, review_title, text, suggestions, ratings
$query_str = "SELECT Review.review_id, Review.recipe_id, Review.user_id, User.user_name, Review.review_title, Review.text, Review.suggestions, Review.ratings FROM Review JOIN Recipe ON Review.recipe_id= Recipe.recipe_id JOIN User ON Review.user_id = User.user_id where Review.recipe_id = ?";
$reviews = array();
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('i', $recipe_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($reviews, $row);
    }
    $query->close();
} else {
    echo $conn->error;
    echo "query recipe picture error";
}

// insert log 
$query_str = "INSERT INTO user_log (user_id, event_name, event_time, params) VALUES (?, 'look_recipe', '".date("Y-m-d H:i:s") . "', ?)";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('is', $user_id, $recipe_id);
    $query->execute();
    $query->close();
} else {
    echo $conn->error;
    echo "insert log error";
}



// test
echo "<br/><br/>Recipe: <br/>";
print_r($recipe);
echo "<br/><br/>Ingredients: <br/>";
print_r($ingredients);
echo "<br/><br/>Steps: <br/>";
print_r($steps);
echo "<br/><br/>Tags: <br/>";
print_r($tags);
echo "<br/><br/>relate to: <br/>";
print_r($relate_recipes);
echo "<br/><br/>reviews: <br/>";
print_r($reviews);

?>
