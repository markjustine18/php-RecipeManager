<?php
session_start();

require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$sql = 'INSERT INTO users (user_name, email, password) VALUES (?, ?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $username, $email, $password);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['logged_in'] = true;

    header('Location: http://localhost/php-RecipeManager/login.php');
    exit();
} else {
    echo 'Error: ' . $stmt->error;
}

$stmt->close();

$conn->close();
?>