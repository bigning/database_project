/*  Create a record for a new user account, with a name, a login name, and a password */

INSERT INTO User(user_name, user_profile, password,user_icon) VALUES ( 'wer', 'hello us', 'badpassword', NULL);

/*List all recipes with tag “italian” that contain the keyword 'broccoli' */
SELECT Recipe.recipe_title
FROM Recipe
JOIN RecipeStep
ON Recipe.recipe_id = RecipeStep.recipe_id
JOIN RecipeTag
ON Recipe.recipe_id = RecipeTag.recipe_id
WHERE RecipeStep.step_description like '%broccoli%'
AND RecipeTag.tag = 'italian'
GROUP BY Recipe.recipe_id, Recipe.recipe_title;

/* List all members of the group “Park Slope Cake Club” that have given a positive RSVP to more that three events of the group*/
SELECT User.user_name
FROM Groups
JOIN GroupMeeting
ON Groups.group_id = GroupMeeting.group_id
JOIN MeetingRSVP
ON GroupMeeting.meeting_id = MeetingRSVP.meeting_id
JOIN User
ON MeetingRSVP.user_id = User.user_id
WHERE Groups.group_name = 'Park Slope Cake Club'
GROUP BY User.user_id, User.user_name
HAVING COUNT(GroupMeeting.meeting_id) >= 3;

/*List all recipes with tag “cake” that contain more than 50 grams of sugar per serving */
SELECT Recipe.recipe_title
FROM Recipe
JOIN RecipeTag 
ON Recipe.recipe_id = RecipeTag.recipe_id
JOIN RecipeIngredient
ON Recipe.recipe_id = RecipeIngredient.recipe_id
WHERE RecipeTag.tag = 'cake'
AND RecipeIngredient.ingredients = 'sugar'
AND RecipeIngredient.quantity/Recipe.num_servings > 50;

/*Add a review with title “Yummy!”, text “Really, really, tasty!”, and a rating of 5 stars to the recipe for “Grandma’s Fettuccini Alfredo”*/
INSERT INTO Review VALUES (5, 4, 1, 'Yummy!', 'Really, really, tasty!', NULL, 5);

/*List all recipes containing the word “tuna”, sorted from highest to lowest average rating */
SELECT SelectedRecipe.recipe_id, SelectedRecipe.recipe_title, AVG(Review.ratings) AS rating
FROM (
    SELECT Recipe.recipe_id, Recipe.recipe_title
    FROM Recipe
    JOIN RecipeStep
    ON Recipe.recipe_id = RecipeStep.recipe_id
    WHERE RecipeStep.step_description LIKE '%tuna%'
) SelectedRecipe
JOIN Review
ON SelectedRecipe.recipe_id = Review.recipe_id
GROUP BY SelectedRecipe.recipe_id, SelectedRecipe.recipe_title
ORDER BY rating DESC;

/*List all recipes that are related to a recipe that contains the word “tuna” */
SELECT Recipe.recipe_title
FROM Recipe
JOIN RecipeRelation
ON Recipe.recipe_id = RecipeRelation.recipe_id
JOIN RecipeStep
ON RecipeRelation.relate_to = RecipeStep.recipe_id
WHERE RecipeStep.step_description LIKE "%tuna%"
GROUP BY Recipe.recipe_id, Recipe.recipe_title;
