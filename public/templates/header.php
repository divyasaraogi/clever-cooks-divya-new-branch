<header class="header toolbar bg-6 sticky">
    <div class="container logo-grp">
        <a href="home.php"><img src="assets/logo.png"></a>
        <span>CLEVER</span>
        <span>COOKS</span>
    </div>
    <nav class="links-grp">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="finder.php">Recipe Finder</a></li>
        </ul>
    </nav>
    <?php if (isset($user)) { ?>
        <div class="profile-grp dropdown">
            <button class="dropbtn" onclick="showMenu()">Your Profile</button>
            <div class="dropdown-content">
                <ul>
                    <li><a href="#">View Profile</a></li>
                    <?php if ($user['user_type'] == 'admin') { ?>
                        <li><a href="manage-ingredients.php">Manage Ingredients</a></li>
                        <li><a href="manage-recipes.php">Manage Recipes</a></li>
                        <li><a href="#">Manage Users</a></li>
                    <?php } ?>
                    <li><a href="api.php?req=logout">Logout</a></li>
                </ul>
            </div>
        </div>
    <?php } else { ?>
        <div class="signing-grp">
            <a class="btn" href="signup.php">Sign Up</a>
            <a class="btn" href="login.php">Log In</a>
        </div>
    <?php } ?>
</header>