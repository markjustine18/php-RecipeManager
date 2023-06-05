<?php
session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

<body>
    <header>
        <h1><img src="image/logos.png" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="recipe-container" id="recipeContainer">
            <?php
            require 'config/config.php';
            require 'config/db.php';

            $query = 'SELECT * FROM recipes';
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
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
                    $ingredients = explode("\n", $reci_ingred);
                    foreach ($ingredients as $ingredient) {
                        echo '<li>' . trim($ingredient) . '</li>';
                    }
                    echo '</ul>';

                    echo '<p>Instructions:</p>';
                    echo '<ol>';
                    $steps = explode("\n", $recisteps);
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
                            echo '<p>Your Rating:</p>';
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
                    echo '<p>Average Rating:</p>';
                    echo '<div class="stars">';
                    for ($i = 0; $i < $avg_rating; $i++) {
                        echo '<i class="fas fa-star"></i>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="com">';
                    echo '<p>Comments</p>';
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $query_comment = "SELECT * FROM recipe_reviews WHERE recipe_id = '{$row['recipe_id']}' AND user_id = '$user_id'";
                        $result_comment = mysqli_query($conn, $query_comment);
                        if (mysqli_num_rows($result_comment) > 0) {
                            $row_comment = mysqli_fetch_assoc($result_comment);
                            echo '<div class="comment">';
                            echo '<p class="username">You:</p>';
                            echo '<p class="comment-text">' .
                                $row_comment['comment'] .
                                '</p>';
                            echo '</div>';
                        }
                    }
                    $query_comments = "SELECT r.*, u.user_name FROM recipe_reviews r INNER JOIN users u ON r.user_id = u.user_id WHERE r.recipe_id = '{$row['recipe_id']}' ORDER BY r.created_at DESC LIMIT 3";
                    $result_comments = mysqli_query($conn, $query_comments);
                    if (mysqli_num_rows($result_comments) > 0) {
                        while (
                            $row_comment = mysqli_fetch_assoc($result_comments)
                        ) {
                            if (
                                !isset($user_id) ||
                                $row_comment['user_id'] != $user_id
                            ) {
                                echo '<div class="comment">';
                                echo '<p class="username">' .
                                    $row_comment['user_name'] .
                                    ':</p>';
                                echo '<p class="comment-text">' .
                                    $row_comment['comment'] .
                                    '</p>';
                                echo '</div>';
                            }
                        }
                    }
                    echo '</div>';
                    echo '<p class="created-by">Created by: ';
                    if ($row['user_id'] == null) {
                        echo 'Anonymous';
                    } else {
                        // Fetch username from the "users" table based on user_id
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
                    echo '<a href="add\edit_recipe.php?id=' .
                        $row['recipe_id'] .
                        '">Edit</a>';
                    echo '<a href="add\delete_recipe.php?id=' .
                        $row['recipe_id'] .
                        '">Delete</a>';
                    echo '<a href="#" class="favorite" data-recipe-id="' .
                        $row['recipe_id'] .
                        '"><i class="fas fa-heart"></i></a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No recipes found.</p>';
            }
            mysqli_close($conn);
            ?>
        </div>
    </main>
    <footer>
        <!-- Add the footer content here -->
    </footer>
</body>

</html>