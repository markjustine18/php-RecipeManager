<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="./css/profile.css">
    <title>Recipe Manager</title>
</head>

<body>
    <header>
        <h1><img src="image/logos.png" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="add_recipe.php">Recipe</a></li>
                <li><a href="favorites.php">Favorites</a></li>
                <li><a href="profile.php" class="active">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="profile">
            <?php if (isset($_SESSION['user_id'])) {
                require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
                require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT * FROM users WHERE user_id = $user_id";
                $result = mysqli_query($conn, $sql);
                $user = mysqli_fetch_assoc($result);
                echo "<h2>Welcome, {$user['user_name']}!</h2>";
                echo "<p>Email: {$user['email']}</p>";
                echo '<h3>My Recipes</h3>';
                $sql = "SELECT * FROM recipes WHERE user_id = $user_id";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    echo '<ul>';
                    while ($recipe = mysqli_fetch_assoc($result)) {
                        echo "<li><a href=\"./add/view_recipe.php?recipe_id={$recipe['recipe_id']}\">{$recipe['recipe_name']}</a></li>";
                    }
                    echo '</ul>';
                } else {
                    echo "<p>You haven't added any recipes yet.</p>";
                }
                echo '<h3>My Favorites</h3>';
                $sql = "SELECT recipes.* FROM favorites JOIN recipes ON favorites.recipe_id = recipes.recipe_id WHERE favorites.user_id = $user_id";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    echo '<ul>';
                    while ($recipe = mysqli_fetch_assoc($result)) {
                        echo "<li><a href=\"./add/view_recipe.php?recipe_id={$recipe['recipe_id']}\">{$recipe['recipe_name']}</a></li>";
                    }
                    echo '</ul>';
                } else {
                    echo "<p>You haven't added any favorites yet.</p>";
                }
            } else {
                header('Location: http://localhost/php-RecipeManager/index.php');
                exit();
            } ?>
        </div>
    </main>
</body>

</html>