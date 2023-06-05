<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <link rel="stylesheet" href="../css/add_rev.css?v=2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

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


    <?php
    session_start();
    require 'C:\xampp\htdocs\php-RecipeManager\config\config.php';
    require 'C:\xampp\htdocs\php-RecipeManager\config\db.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: http://localhost/php-RecipeManager/index.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];

    if (isset($_POST['submit'])) {
        $recipe_id = $_GET['recipe_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        $sql =
            'INSERT INTO recipe_reviews (user_id, recipe_id, rating, comment) VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiis', $user_id, $recipe_id, $rating, $comment);
        $stmt->execute();

        header('Location: http://localhost/php-RecipeManager/index.php');
        exit();
    }
    ?>
    <div class="review">
        <form method="post" action="">
            <label for="rating">Rating:</label>
            <div class="rating">
                <input type="radio" id="star5" name="rating" value="5" />
                <label for="star5" title="5 stars">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4" />
                <label for="star4" title="4 stars">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3" />
                <label for="star3" title="3 stars">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2" />
                <label for="star2" title="2 stars">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="1" />
                <label for="star1" title="1 star">&#9733;</label>
            </div>
            <div class="form-group">
                <label for="comment">Comment:</label>
                <textarea class="form-control" name="comment" rows="3"></textarea>
            </div>
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>

</html>