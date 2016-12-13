<ul class="navbar-text navbar-right">

    <!-- User icon -->
    <?php 
        if ($user_icon != null){
            echo '<img src="' . $user_icon . '" class = "thumbnail" id = "user_icon">';
        }
    ?>

    <!-- Drop Down -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <?php echo $user_name ?> <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="./homepage.php">My HomePage</a></li>
        <li><a href="./logout.php">Log Out</a></li>
    </ul>

</ul>

<!-- Search for group -->
<form class="navbar-form navbar-right navbar-search" role="search" method="GET" action="./groups.php">
  <div class="form-group">
    <input type="text" class="form-control" placeholder="Search Group" name = "query">
  </div>
  <button type="submit" class="btn btn-default">  <span class="glyphicon glyphicon-user" aria-hidden="true">Go!</span> </button>
</form>

<!-- Search for recipe -->
<form class="navbar-form navbar-right navbar-search" role="search" action="./recipes.php">
  <div class="form-group">
    <input type="text" class="form-control" placeholder="Search Recipe" name = "query">
  </div>
  <button type="submit" class="btn btn-default"><span class = "glyphicon glyphicon-grain" aria-hidden="true">Go!</span></button>
</form>