<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <link rel="stylesheet" href="../css/edit_reci.css">
    <title>Edit Recipe</title>
</head>

<?php
require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipe_id = $_POST['recipe_id'];

    $recipe_name = $_POST['recipe_name'];
    $description = $_POST['description'];
    $prep_time = $_POST['prep_time'];
    $servings = $_POST['servings'];
    $category_id = $_POST['category_id'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];

    if ($_FILES['new_image']['name'] != '') {
        $newImage = $_FILES['new_image']['name'];
        $newImageTemp = $_FILES['new_image']['tmp_name'];

        unlink('C:/xampp/htdocs/php-RecipeManager/image/' . $image);
        move_uploaded_file(
            $newImageTemp,
            'C:/xampp/htdocs/php-RecipeManager/image/' . $newImage
        );

        $query = "UPDATE recipes SET image = '$newImage' WHERE recipe_id = $recipe_id";
        mysqli_query($conn, $query);
    }

    $query = "UPDATE recipes SET
                recipe_name = '$recipe_name',
                description = '$description',
                prep_time = '$prep_time',
                servings = '$servings',
                category_id = '$category_id',
                ingredients = '$ingredients',
                instructions = '$instructions'
            WHERE recipe_id = $recipe_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header('Location: http://localhost/php-RecipeManager/index.php');
        exit();
    } else {
        echo 'Error updating recipe: ' . mysqli_error($conn);
    }
}
?>

<body>
    <header>
        <h1><img src="../image/logos.png" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../categories.php">Categories</a></li>
                <li><a href="../add_recipe.php" class="active">Recipe</a></li>
                <li><a href="../favorites.php">Favorites</a></li>
                <?php
                session_start();
                if (isset($_SESSION['user_id'])) {
                    echo '<li><a href="../profile.php">Profile</a></li>';
                    echo '<li><a href="../logout.php">Logout</a></li>';
                } else {
                    echo '<li><a href="../register.php">Register</a></li>';
                    echo '<li><a href="../login.php">Login</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Edit Recipe</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <?php
            $recipeId = $_GET['id'];
            $query = "SELECT * FROM recipes WHERE recipe_id = $recipeId";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);

            $recipe_id = $row['recipe_id'];
            $recipeName = $row['recipe_name'];
            $image = $row['image'];
            $description = $row['description'];
            $prepTime = $row['prep_time'];
            $servings = $row['servings'];
            $ingredients = $row['ingredients'];
            $instructions = $row['instructions'];
            $categoryId = $row['category_id'];

            $query = 'SELECT * FROM categories';
            $result = mysqli_query($conn, $query);

            mysqli_close($conn);
            ?>
            <label for="recipeName">Recipe Name:</label>
            <input type="text" id="recipeName" name="recipe_name" value="<?php echo $recipeName; ?>" required>
            <br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="new_image" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo $description; ?></textarea>
            <br>
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" rows="4" required><?php echo $ingredients; ?></textarea>
            <br>
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" rows="4" required><?php echo $instructions; ?></textarea>
            <br>
            <label for="prepTime">Preparation Time (minutes):</label>
            <input type="number" id="prepTime" name="prep_time" value="<?php echo $prepTime; ?>" required min="0">
            <br>
            <label for="servings">Servings:</label>
            <input type="number" id="servings" name="servings" value="<?php echo $servings; ?>" required min="0">
            <br>
            <label for="categoryId">Category:</label>
            <select id="categoryId" name="category_id" required>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo $row['category_id']; ?>" <?php if (
    $row['category_id'] == $categoryId
) {
    echo 'selected';
} ?>>
                    <?php echo $row['category_name']; ?></option>
                <?php } ?>
            </select>
            <br>
            <input type="hidden" id="recipe_id" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
            <input type="submit" value="Update Recipe">
            <input type="button" value="Back" onclick="window.location.href='../index.php';">
        </form>
    </main>
    <footer>
        <!-- Add the footer content here -->
    </footer>
</body>

</html>