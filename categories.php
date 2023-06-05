<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="./css/categ.css">
    <title>Recipe Manager</title>
</head>

<?php
require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';

$sql = 'SELECT * FROM categories';
$result = mysqli_query($conn, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    $sql = "SELECT recipes.*, users.user_name 
    FROM recipes 
    LEFT JOIN users ON recipes.user_id = users.user_id
    WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<body>
    <header>
        <h1><img src="image/logos.png" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="categories.php" class="active">Categories</a></li>
                <li><a href="add_recipe.php">Recipe</a></li>
                <li><a href="favorites.php">Favorites</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Recipe Categories</h2>
        <form method="post">
            <select id="categorySelect" name="category_id">
                <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category[
                    'category_id'
                ]; ?>"><?php echo $category['category_name']; ?>
                </option>
                <?php } ?>
            </select>
            <input type="submit" value="Submit">
        </form>
        <?php if (isset($recipes) && !empty($recipes)) { ?>
        <div class="recipe-container">
            <?php foreach ($recipes as $recipe) { ?>
            <div class="recipe">
                <h3><?php echo $recipe['recipe_name']; ?></h3>
                <img src="http://localhost/php-RecipeManager/image/<?php echo $recipe[
                    'image'
                ]; ?>" alt="<?php echo $recipe['recipe_name']; ?>">
                <?php
                echo '<p class="description">Description: <br>' .
                    $recipe['description'] .
                    '</p>';
                echo '<p>Preparation Time: ' .
                    $recipe['prep_time'] .
                    ' minutes</p>';
                echo '<p>Servings: ' . $recipe['servings'] . '</p>';
                echo '<p>Ingredients:</p>';
                echo '<ul>';
                $ingredients = explode("\n", $recipe['ingredients']);
                foreach ($ingredients as $ingredient) {
                    echo '<li>' . trim($ingredient) . '</li>';
                }
                echo '</ul>';

                echo '<p>Instructions:</p>';
                echo '<ol>';
                $steps = explode("\n", $recipe['instructions']);
                foreach ($steps as $step) {
                    echo '<li>' . trim($step) . '</li>';
                }
                echo '</ol>';
                ?>
                <div class="com">
                    <?php
                    echo '<p>Comments</p>';
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $query_comment = "SELECT * FROM recipe_reviews WHERE recipe_id = '{$recipe['recipe_id']}' AND user_id = '$user_id'";
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
                    $query_comments = "SELECT r.*, u.user_name FROM recipe_reviews r INNER JOIN users u ON r.user_id = u.user_id WHERE r.recipe_id = '{$recipe['recipe_id']}' ORDER BY r.created_at DESC LIMIT 3";
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
                    ?>
                </div>
                <?php
                echo '<p class="created-by">Created by: ';
                if ($recipe['user_id'] == null) {
                    echo 'Anonymous';
                } else {
                    $user_id = $recipe['user_id'];
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
                ?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </main>
    <footer>
        <!-- Add your footer content here -->
    </footer>
</body>

</html>