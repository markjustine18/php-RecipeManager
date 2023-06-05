<?php
session_start();

require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['email'] = $email;
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['user_id'];

    header('Location: http://localhost/php-RecipeManager/index.php');
    exit();
} else {
    echo 'Invalid email or password.';
}

$conn->close();
?>