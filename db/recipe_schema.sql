DROP TABLE MeetingReport;
DROP TABLE MeetingRSVP;
DROP TABLE GroupMeeting;
DROP TABLE GroupMember;
DROP TABLE Groups;
DROP TABLE ReviewImage;
DROP TABLE Review;
DROP TABLE RecipeRelation;
DROP TABLE RecipeTag;
DROP TABLE RecipeIngredient;
DROP TABLE RecipeStep;
DROP TABLE Recipe;
DROP TABLE Tag;
DROP TABLE User;

CREATE TABLE User (
    user_id int(10),
    user_name varchar(40),
    user_profile varchar(200),
    password char(32),
    user_icon varchar(255),
    primary key (user_id)
);

CREATE TABLE Tag (
    tag varchar(255),
    primary key (tag)
);

CREATE TABLE Recipe (
    recipe_id int (10),
    user_id int(10),
    recipe_title varchar(255),
    num_servings int(10),
    primary key (recipe_id, user_id),
    foreign key (user_id) references User(user_id)
);

CREATE TABLE RecipeStep (
    recipe_id int(10),
    step_id int(10),
    step_description varchar(255),
    step_image varchar(255),
    primary key (recipe_id, step_id),
    foreign key (recipe_id) references Recipe(recipe_id)
);

CREATE TABLE RecipeIngredient (
    recipe_id int(10),
    ingredients varchar(255),
    quantity int(10),
    unit varchar(255),
    primary key (recipe_id, ingredients),
    foreign key (recipe_id) references Recipe(recipe_id)
);

CREATE TABLE RecipeTag (
    recipe_id int(10),
    tag varchar(255),
    primary key (recipe_id, tag),
    foreign key (recipe_id) references Recipe(recipe_id),
    foreign key (tag) references Tag(tag)
);

CREATE TABLE RecipeRelation (
    recipe_id int(10),
    relate_to int(10),
    primary key (recipe_id, relate_to),
    foreign key (recipe_id) references Recipe(recipe_id),
    foreign key (relate_to) references Recipe(recipe_id)
);

CREATE TABLE Review (
    review_id int(10),
    recipe_id int(10),
    user_id int(10),
    review_title varchar(255),
    text varchar(255),
    suggestions varchar(255),
    ratings int(10),
    primary key (review_id),
    foreign key (recipe_id) references Recipe(recipe_id),
    foreign key (user_id) references User(user_id)
);

CREATE TABLE ReviewImage (
    review_id int(10),
    image_path varchar(255),
    primary key (review_id, image_path),
    foreign key (review_id) references Review(review_id)
);

CREATE TABLE Groups (
    group_id int(10),
    group_name varchar(255),
    group_owner int(10),
    primary key (group_id),
    foreign key (group_owner) references User(user_id)
);

CREATE TABLE GroupMember (
    group_id int(10),
    user_id int(10),
    primary key (group_id, user_id),
    foreign key (group_id) references Groups(group_id),
    foreign key (user_id) references User(user_id)
);

CREATE TABLE GroupMeeting (
    group_id int(10),
    meeting_id int(10),
    meeting_name varchar(255),
    organiser_id int(10),
    primary key (meeting_id),
    foreign key (group_id) references Groups(group_id),
    foreign key (organiser_id) references User(user_id)
);

CREATE TABLE MeetingRSVP (
    meeting_id int(10),
    user_id int(10),
    primary key (meeting_id, user_id),
    foreign key (meeting_id) references GroupMeeting(meeting_id),
    foreign key (user_id) references User(user_id)
    
);

CREATE TABLE MeetingReport (
    meeting_id int(10),
    user_id int(10),
    message varchar(255),
    time datetime,
    meeting_report_image varchar(255),
    primary key (meeting_id, user_id, time),
    foreign key (meeting_id) references GroupMeeting(meeting_id),
    foreign key (user_id) references User(user_id)
);

/* insert sample data */
INSERT INTO User VALUES (1, 'bigning', 'hello world', 'wangning', NULL);
INSERT INTO User VALUES (2, 'jie', 'hello world', 'wangjie', NULL);

INSERT INTO Tag VALUES ('italian');
INSERT INTO Tag VALUES ('chinese');
INSERT INTO Tag VALUES ('vegan');
INSERT INTO Tag VALUES ('soup');
INSERT INTO Tag VALUES ('spicy');

INSERT INTO Recipe VALUES (1, 1, 'stir-fried shredded potato', 1);

INSERT INTO RecipeStep VALUES (1, 1, 'wash potato', NULL);
INSERT INTO RecipeStep VALUES (1, 2, 'chop to slices', NULL);
INSERT INTO RecipeStep VALUES (1, 3, 'fry', NULL);

INSERT INTO RecipeIngredient VALUES (1, 'potato', 300, 'g');
INSERT INTO RecipeIngredient VALUES (1, 'salt', 10, 'g');

INSERT INTO RecipeTag VALUES (1, 'chinese');
INSERT INTO RecipeTag VALUES (1, 'spicy');

INSERT INTO Review VALUES (1, 1, 1, 'great', 'it is great', 'no suggestoin', 5);

INSERT INTO Groups VALUES (1, "chinese food", 1);
INSERT INTO GroupMember VALUES (1, 1);
INSERT INTO GroupMember VALUES (1, 2);

INSERT INTO GroupMeeting VALUES (1, 1, 'fired rice', 1);

INSERT INTO MeetingRSVP VALUES (1, 1);

INSERT INTO MeetingReport VALUES (1, 1, 'good', '2016-11-18 13:00:00', NULL);
