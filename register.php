<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/register.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <title>Recipe Manager</title>
</head>

<body>
    <header>
    </header>
    <div class="container-fluid ps-md-0">
        <div class="row g-0">
            <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
            <div class="col-md-8 col-lg-6">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9 col-lg-8 mx-auto">
                                <img src="image/logos.png" alt="Logo">
                                <h3 class="login-heading mb-4">Create Account!</h3>

                                <!-- Sign In Form -->
                                <form action="add\register_process.php" method="POST">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInputUsername"
                                            name="username" placeholder="myusername" required autofocus>
                                        <label for="floatingInputUsername">Username</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="floatingInputEmail" name="email"
                                            placeholder="name@example.com">
                                        <label for="floatingInputEmail">Email address</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="floatingPassword"
                                            name="password" placeholder="Password">
                                        <label for="floatingPassword">Password</label>
                                    </div>

                                    <div class="d-grid mb-2">
                                        <button class="btn btn-lg btn-warning btn-login text-uppercase fw-bold mb-2"
                                            type="submit">Sign Up</button>
                                    </div>

                                    <div class="text-center">
                                        <p>Have an account? <a href="login.php">Sign In</a></p>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <!-- Add the footer content here -->
    </footer>
</body>

</html>