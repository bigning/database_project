<?php
// return recipe_id, recipe_title, recipe_name, user_id , user_icon, num_servings, user_name IN recipe
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
$query_str = "SELECT Recipe.recipe_id, Recipe.recipe_title, Recipe.user_id, Recipe.num_servings, User.user_icon, User.user_name FROM Recipe JOIN User ON Recipe.user_id = User.user_id  where recipe_id = ?";
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
// echo "<br/><br/>Recipe: <br/>";
// print_r($recipe);
// echo "<br/><br/>Ingredients: <br/>";
// print_r($ingredients);
// echo "<br/><br/>Steps: <br/>";
// print_r($steps);
// echo "<br/><br/>Tags: <br/>";
// print_r($tags);
// echo "<br/><br/>relate to: <br/>";
// print_r($relate_recipes);
// echo "<br/><br/>reviews: <br/>";
// print_r($reviews);

?>





<!-- FE test -->

<!-- FE -->
<!-- Navbar -->
<?php require "./include/partials/navHeader.php" ?>

<?php require "./include/partials/navRight.php" ?>

<?php require "./include/partials/navFooter.php" ?>

<!-- Recipe title -->
<div class = "container">
    <div class="jumbotron">
        <h1 id = "recipe-title"><?php echo $recipe["recipe_title"] ?></h1>
        <p>Number of Servings:
            <?php 
                for ($x = 0; $x < $recipe["num_servings"]; $x++){
                    echo "<span class='glyphicon glyphicon-glass' aria-hidden='true' ></span>";
                }
            ?>
        </p>
        <p>
            <?php 
                if (!empty($tags)){
                    echo "Tags: ";
                    foreach ($tags as $value) {
                        echo "<span>" . $value["tag"] . "</span> ";
                    }
                }
            ?>
        </p>
        <div id = "recipe-user">
            <span>Provide by: </span>
            <?php 
                if ($recipe["user_icon"] != null){
                    echo '<img src="' . $recipe["user_icon"] . '" class = "thumbnail user_icon" >';
                }
                echo "<span>" . $recipe["user_name"] . "</span>";
            ?>
        </div>
    </div>
</div>


<!-- Ingredients -->
<div class = "container">
    <div class = "jumbotron">
     <h2>Ingredients </h2>
        <?php 
            if (!empty($ingredients)){
                foreach ($ingredients as $value) {
                    echo "<div class = 'ingredient-tuple'>";
                    echo "<span>" . $value["ingredients"] . "</span>";
                    echo "<span>" . $value["quantity"] . "</span>";
                    echo "<span>" . $value["unit"] . "</span>";
                    echo "</div>";
                }
            }
        ?>
    </div>
</div>


<!-- Steps -->
<div class = "container">
    <div class = "jumbotron">
    <h2>Step </h2>
        <?php 
            if (!empty($steps)){
                foreach ($steps as $value) {
                    echo "<div class = 'step-tuple'>";
                    echo "<p>" . $value["step_description"];
                    if(!empty($value["step_image"])){
                        echo "<img src='" . $value["step_image"] . "' class = 'thumbnail step-image'></p>";
                    }
                    echo "</div>";
                }
            }
        ?>
    </div>
</div>


<!-- Review -->
<div class = "container">
    <div class = "jumbotron">
    <h2>Review </h2>
        <?php 
            if (!empty($reviews)){
                
                foreach ($reviews as $value) {
                    echo "<div class = 'review-tuple'>";
                    echo "<h3>" . $value["review_title"] . "<div id='stars-existing' class='starrr rating-star' data-rating=" . $value["ratings"] . "></div>" . "</h3>";
                    echo "<p class = 'review-text'>" . $value["text"] . "</p>";
                    echo "<p class = 'review-text'> Suggestion: " . $value["suggestions"] . "</p>";
                    echo "<span>By " . $value["user_name"] . "</span>";
                    echo "</div>";
                }
            }
        ?>
    </div>
</div>

<a href=""></a>
<!-- Relate to -->
<div class = "container">
    <div class = "jumbotron">
        <h3>Related Recipes</h3>
        <?php 
            if (!empty($relate_recipes)){
                foreach ($relate_recipes as $value) {
                    echo "<div class = 'related-tuple'>";
                    echo "<a href='recipe_detail.php?recipe_id=" . $value["relate_to"] . "'>" . $value["recipe_title"]. "</a>";
                    echo "</div>";
                }
            }
        ?>
    </div>
</div>


<link rel="stylesheet" type="text/css" href="./include/css/recipe_detail.css">
<script type="text/javascript" src = "./include/framework/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src = "./include/js/recipe_detail.js"></script>
<?php require "./include/partials/footer.php" ?>
