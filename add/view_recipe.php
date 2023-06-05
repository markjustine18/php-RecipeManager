<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>Recipe Manager</title>
</head>

<body>
    <header>
        <h1><img src="../image/logos.png" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../categories.php">Categories</a></li>
                <li><a href="../add_recipe.php">Recipe</a></li>
                <li><a href="../favorites.php" class="active">Favorites</a></li>
                <li><a href="../profile.php">Profile</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="recipe-container">
            <?php
            require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
            require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';

            if (isset($_GET['recipe_id'])) {
                $recipe_id = $_GET['recipe_id'];
                $sql = "SELECT * FROM recipes WHERE recipe_id = $recipe_id";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $reci_ingred = $row['ingredients'];
                    $recisteps = $row['instructions'];
                    echo '<div class="recipe">';
                    echo '<h3>' . $row['recipe_name'] . '</h3>';
                    echo '<img src="http://localhost/php-RecipeManager/image/' .
                        $row['image'] .
                        '" alt="' .
                        $row['recipe_name'] .
                        '">';
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
                    echo '<div class="rate">';
                    if (isset($_SESSION['user_id'])) {
                        $recipe_id = $row['recipe_id'];
                        $user_id = $_SESSION['user_id'];
                        $query_rating = "SELECT rating FROM recipe_reviews WHERE recipe_id = '$recipe_id' AND user_id = '$user_id'";
                        $result_rating = mysqli_query($conn, $query_rating);
                        if (mysqli_num_rows($result_rating) > 0) {
                            $row_rating = mysqli_fetch_assoc($result_rating);
                            $user_rating = $row_rating['rating'];
                            echo '<p>Your rating:</p>';
                            echo '<div class="stars">';
                            for ($i = 0; $i < $user_rating; $i++) {
                                echo '<i class="fas fa-star"></i>';
                            }
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                    echo '<div class="ratings">';
                    $recipe_id = $row['recipe_id'];
                    $query_avg = "SELECT AVG(rating) AS avg_rating FROM recipe_reviews WHERE recipe_id = '$recipe_id'";
                    $result_avg = mysqli_query($conn, $query_avg);
                    $row_avg = mysqli_fetch_assoc($result_avg);
                    $avg_rating = round($row_avg['avg_rating'], 1);
                    echo '<p>Average rating:</p>';
                    echo '<div class="stars">';
                    for ($i = 0; $i < $avg_rating; $i++) {
                        echo '<i class="fas fa-star"></i>';
                    }
                    echo '</div>';
                    echo '</div>';
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
                    echo '</div>';
                } else {
                    echo '<p>No recipe found.</p>';
                }
            } else {
                header('http://localhost/php-RecipeManager/index.php');
                exit();
            }
            ?>
        </div>
    </main>
</body>

</html>