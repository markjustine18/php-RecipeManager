<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="./css/add_reci.css">
    <title>Recipe Manager</title>
</head>

<?php
require_once 'config/config.php';
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $recipe_name = $_POST['recipe_name'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $servings = $_POST['servings'];
    $prep_time = $_POST['prep_time'];
    $category_id = $_POST['category_id'];

    session_start();
    $user_id = null;
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }

    $image = $_FILES['image']['name'];
    $target_dir = 'C:/xampp/htdocs/php-RecipeManager/image/';
    $target_file = $target_dir . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Prepare the query with placeholders
    $query = "INSERT INTO recipes (recipe_name, description, ingredients, instructions, image, servings, prep_time, category_id, user_id)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        // Handle the error, e.g., output it or redirect to an error page
        echo 'Error: Unable to prepare the statement.';
        exit();
    }

    // Bind the parameters
    $stmt->bind_param(
        'sssssiisi',
        $recipe_name,
        $description,
        $ingredients,
        $instructions,
        $image,
        $servings,
        $prep_time,
        $category_id,
        $user_id
    );

    // Execute the statement
    $result = $stmt->execute();

    if ($result) {
        header('Location: http://localhost/php-RecipeManager/index.php');
        exit();
    } else {
        echo 'Error: ' . $stmt->error;
    }
}
?>


<body>
    <header>
        <h1><img src="image/logos.png" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="add_recipe.php" class="active">Recipe</a></li>
                <li><a href="favorites.php">Favorites</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Add Recipe</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="recipe_name">Recipe Name:</label>
            <input type="text" id="recipe_name" name="recipe_name" required>
            <br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
            <br>
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" rows="4" required></textarea>
            <br>
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" rows="4" required></textarea>
            <br>
            <label for="servings">Servings:</label>
            <input type="number" id="servings" name="servings" required min="0">
            <br>
            <label for="prep_time">Preparation Time (in minutes):</label>
            <input type="number" id="prep_time" name="prep_time" required min="0">
            <br>
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <?php
                $categories = mysqli_query(
                    $conn,
                    'SELECT category_id, category_name FROM categories'
                );
                while ($row = mysqli_fetch_assoc($categories)) {
                    echo "<option value='" .
                        $row['category_id'] .
                        "'>" .
                        $row['category_name'] .
                        '</option>';
                }
                ?>
            </select>
            <br>
            <input type="submit" value="Add Recipe">
        </form>
    </main>
    <footer>
        <!-- Add the footer content here -->
    </footer>
</body>

</html>