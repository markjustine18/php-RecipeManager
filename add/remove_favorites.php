<?php
session_start();

require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';

if (!isset($_SESSION['user_id'])) {
    echo 'Not logged in';
    exit();
}

$user_id = $_SESSION['user_id'];
$recipe_id = $_POST['recipe_id'];

$query = 'SELECT * FROM favorites WHERE user_id = ? AND recipe_id = ?';
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $user_id, $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $query = 'DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $user_id, $recipe_id);

    if ($stmt->execute()) {
        echo 'Recipe removed from favorites';
    } else {
        echo 'Failed to remove recipe from favorites';
    }
} else {
    echo 'Recipe is not a favorite';
}

$stmt->close();
$conn->close();
?>
