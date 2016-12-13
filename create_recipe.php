<?php
require "./db_util.php";
require "./check_login_status.php";

// insert to Recipe
$recipe_title = $_POST["recipe_title"];
$num_servings = $_POST["num_servings"];

$query = $conn->prepare("INSERT INTO Recipe(user_id, recipe_title, num_servings) VALUES (?, ?, ?)");
$query->bind_param('isi', $user_id, $recipe_title, $num_servings);
$query->execute();

// get recipe_id
$query_str = "SELECT max(Recipe.recipe_id) AS recipe_id FROM Recipe WHERE recipe_title = ?";
if ($query = $conn->prepare($query_str)) {
    $query->bind_param('s', $recipe_title);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $recipe_id = (int)$row["recipe_id"];

    $query->close();
} else {
    echo $conn->error;
    echo "query recipe picture error";
}

$post_variables = array_keys($_POST);
// insert into RecipeIngredient
$ind = 1;
while (true) {
    $ingredient_name_var = "ingredient_name_" . $ind;
    echo $ingredient_name_var;
    if (!array_key_exists($ingredient_name_var, $_POST)) {
        break;
    }
    $ingredient_name = $_POST[$ingredient_name_var];
    $unit = $_POST["ingredient_unit_" . $ind];
    $quantity = $_POST["ingredient_quantity_" . $ind];

    $query = $conn->prepare("INSERT INTO RecipeIngredient(recipe_id, ingredients, quantity, unit) VALUES (?, ?, ?, ?)");
    $query->bind_param('isis', $recipe_id, $ingredient_name, $quantity, $unit);
    $query->execute();

    $ind = $ind + 1;
}

// insert into RecipeIngredient
$ind = 1;
while (true) {
    $step_name = "step_" . $ind;
    if (!array_key_exists($step_name, $_POST)) {
        break;
    }
    $step_id = $ind;
    $step_desc = $_POST[$step_name];

    $step_img_save_name = null;
    $step_img_size = $_FILES["step_file_" . $ind]["size"];
    if ($step_img_size > 0) {
        $step_img_save_name = "./step_imgs/" . date('Ymdhms') . "_" .rand() . ".jpg";
        move_uploaded_file($_FILES["step_file_" . $ind]["tmp_name"], $step_img_save_name);
    }

    $query = $conn->prepare("INSERT INTO RecipeStep(recipe_id, step_id, step_description, step_image) VALUES (?, ?, ?, ?)");
    $query->bind_param('iiss', $recipe_id, $step_id, $step_desc, $step_img_save_name);
    $query->execute();

    $ind = $ind + 1;
}

// insert into tag
if (strlen($_POST["tags"]) > 0) {
    $tags = $_POST["tags"];
    $tag_arr = explode(",", $tags);
        print_r($tag_arr);
    foreach ($tag_arr as $tag) {
        $query = $conn->prepare("INSERT INTO Tag(tag) VALUES (?)");
        $query->bind_param('s',  $tag);
        $query->execute();

        $query = $conn->prepare("INSERT INTO RecipeTag(recipe_id, tag) VALUES (?, ?)");
        $query->bind_param('is', $recipe_id, $tag);
        $query->execute();
    }
}

header("Location: ./recipe_detail.php?recipe_id=$recipe_id");

//test
echo $recipe_title;
print_r(array_keys($_FILES));
$img_size = $_FILES["step_file_1"]["size"];
echo $img_size;
echo $recipe_id;

?>
