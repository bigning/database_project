<?php 
// return user_id, recipe_id, recipe_title IN recipes;
// For each recipe_id in recipes, return 
// pictures, tags, avg_ratings. Key is recipe_id
// E.G. pictures[1] = array {"pic_1", "pic_2"}
// tags[1] = array ("chinese", "spicy")
// avg_ratings[1] = 5.0

require "./check_login_status.php";
require "./db_util.php";

$search_word = '%%';
if (array_key_exists("query", $_GET)) {
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
$avg_ratings = array();
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

    // avg_rating
    $avg_ratings[$recipe["recipe_id"]] = 2.5;
    $query_str = "SELECT avg(ratings) AS avg_rating FROM Review where recipe_id = ?";
    if ($query = $conn->prepare($query_str)) {
        $query->bind_param('i', $recipe["recipe_id"]);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $avg_ratings[$recipe["recipe_id"]] = (float)($row["avg_rating"]);
        $query->close();
    } else {
        echo "query recipe picture error";
    }
}

// insert search_log
$pure_search_word = substr($search_word, 1, strlen($search_word) - 2);
$query_str = "INSERT INTO user_log (user_id, event_name, event_time, params) VALUES (?, 'search_recipe', '".date("Y-m-d H:i:s") . "', ?)";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('is', $user_id, $pure_search_word);
    $query->execute();
    $query->close();
} else {
    echo $conn->error;
    echo "insert log error";
}

// test
// echo '<br/><br/>recipes: <br/>';
// print_r($recipes);
// echo '<br/><br/>pictures: <br/>';
// print_r($pictures);
// echo '<br/><br/>tags: <br/>';
// print_r($tags);
// echo '<br/><br/>avg_ratings: <br/>';
// print_r($avg_ratings);

?>






<!-- FE test -->



<!-- FE -->
<!-- Navbar -->
<?php require "./include/partials/navHeader.php" ?>

<?php require "./include/partials/navRight.php" ?>

<?php require "./include/partials/navFooter.php" ?>

<div class = "container">
    <div class = "jumbotron">
        <?php 
            if (!empty($recipes)){
                foreach ($recipes as $value) {
                    $curr_id = $value["recipe_id"];
                    echo "<div class = 'recipe-tuple'>";

                    // show the recipe image or default if no images provide
                    echo "<img class = 'thumbnail recipe-image' src='";
                    $image_count = count($pictures[$curr_id]);
                    if(!empty($pictures[$curr_id][$image_count - 1])){
                        echo $pictures[$curr_id][$image_count - 1];
                    } else {
                        echo "./include/images/not_found_image.jpeg";
                    }
                    echo "'>";

                    //show recipe title
                    echo "<a class = 'recipe-title' href='./recipe_detail.php?recipe_id=";
                    echo $value["recipe_id"];
                    echo "'>";
                    echo $value["recipe_title"];
                    echo "</a>";

                    // show rating
                    echo "<div id='stars-existing' class='starrr' data-rating='";
                    echo $avg_ratings[$curr_id];
                    echo "'></div>";

                    // show tags
                    echo "<div class = 'tags'>";
                    if(!empty($tags[$curr_id])){
                        foreach ($tags[$curr_id] as $value) {
                            echo "<a class = 'label label-danger' href='./recipes.php?query=" . $value . "'>" . $value . "</a>";
                        }
                    }
                    echo "</div>";

                    echo "</div>";
                }
            }
        ?>
    </div>
</div>


<link rel="stylesheet" type="text/css" href="./include/css/recipes.css">
<script type="text/javascript" src = "./include/framework/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src = "./include/js/recipes.js"></script>
<?php require "./include/partials/footer.php" ?>
