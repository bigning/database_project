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
    title varchar(255),
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
