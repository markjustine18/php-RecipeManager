<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=2">
    <title>Recipe Manager</title>
    <style>
    h2 {
        text-align: center;
        font-size: 2em;
    }
    </style>
</head>

<body>
    <header>
        <h1><img src="image/logos.png" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="add_recipe.php">Recipe</a></li>
                <li><a href="favorites.php" class="active">Favorites</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div id="recipeContainer" class="recipe-container">

            <?php
            require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
            require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';
            // Check if user is logged in
            if (!isset($_SESSION['user_id'])) {
                echo 'You need to be logged in first to view your favorite recipes';
                exit();
            }
            $user_id = $_SESSION['user_id'];

            $sql = "SELECT r.recipe_id, r.recipe_name, r.description, r.ingredients, r.instructions, r.image, r.servings, r.prep_time, r.category_id, r.user_id,
        IFNULL(rv.rating, 0) AS rating
        FROM favorites f
        JOIN recipes r ON f.recipe_id = r.recipe_id
        LEFT JOIN recipe_reviews rv ON r.recipe_id = rv.recipe_id AND rv.user_id = $user_id
        WHERE f.user_id = $user_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
            <div class="recipe">
                <h3><?php echo $row['recipe_name']; ?></h3>
                <img src="http://localhost/php-RecipeManager/image/<?php echo $row[
                    'image'
                ]; ?>" alt="<?php echo $row['recipe_name']; ?>">
                <?php
                echo '<p class="description">Description: <br>' .
                    $row['description'] .
                    '</p>';
                echo '<p>Preparation Time: ' .
                    $row['prep_time'] .
                    ' minutes</p>';
                echo '<p>Servings: ' . $row['servings'] . '</p>';
                echo '<p>Ingredients:</p>';
                echo '<ul>';
                $ingredients = explode("\n", $row['ingredients']);
                foreach ($ingredients as $ingredient) {
                    echo '<li>' . trim($ingredient) . '</li>';
                }
                echo '</ul>';

                echo '<p>Instructions:</p>';
                echo '<ol>';
                $steps = explode("\n", $row['instructions']);
                foreach ($steps as $step) {
                    echo '<li>' . trim($step) . '</li>';
                }
                echo '</ol>';
                echo '<p class="created-by">Created by: ';
                if ($row['user_id'] == null) {
                    echo 'Anonymous';
                } else {
                    $user_id = $row['user_id'];
                    $query_user = "SELECT user_name FROM users WHERE user_id = '$user_id'";
                    $result_user = mysqli_query($conn, $query_user);
                    if (mysqli_num_rows($result_user) > 0) {
                        $user = mysqli_fetch_assoc($result_user);
                        echo $user['user_name'];
                    } else {
                        echo 'Anonymous';
                    }
                }
                echo '</p>';
                if ($row['rating'] == 0) {
                    $add_review_link =
                        '<a href="add/add_review.php?recipe_id=' .
                        $row['recipe_id'] .
                        '">Add Review</a>';
                    echo $add_review_link;
                }
                ?>
            </div>
            <?php }
            } else {
                echo 'No favorite recipes found for this user.';
            }
            $conn->close();
            ?>
        </div>
    </main>
    <link rel="stylesheet" href="/add/add_review.php">
    <footer>
        <!-- Add the footer content here -->
    </footer>
</body>

</html>