<?php
require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';

if (isset($_GET['id'])) {
    $recipe_id = $_GET['id'];

    $query = "DELETE FROM recipes WHERE recipe_id = $recipe_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header('Location: http://localhost/php-RecipeManager/index.php');
        exit();
    } else {
        echo 'Error deleting recipe: ' . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
