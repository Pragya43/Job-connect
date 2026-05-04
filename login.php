<?php

session_start();

?>

<!DOCTYPE html>  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style2.css">

</head>
<body>


<header>
        <img src="Logo.jpg" alt="Logo" class="logo">
        <div id="menu-bar" class="fas fa-bars"></div>
        <nav class="navbar">
            <a href="home.php">home</a>
            <a href="home.php">about us</a>
            <a href="home.php">service</a>
            <a href="home.php">contact</a>
            <a href="login.php"><button class="btn" id="loginBtn">Log In</button></a>
            <a href="register.php"><button class="btn" id="signupBtn">Sign Up</button></a>
        </nav>
    </header>

    <!-- Header 
    <div class="header">
        <div class="container">
            <div class="navbar">
                <div class="right">
                    <a href="login.php"><button class="btn" id="loginBtn">Log In</button></a>
                    <a href="register.php"><button class="btn" id="signupBtn">Sign Up</button></a>
                </div>
            </div>
        </div>
    </div>
-->

    <div class="login-container">

    
    <div class="alert">
        <?php
        if(isset($_SESSION['status'])){
            echo"<h4>".$_SESSION['status']."</h4>";
            unset($_SESSION['status']);
        }

        ?>
    </div>


        <h2>Login</h2>
        <form action="logincode.php" method="post">
            <div class="form-group">
                <label for="name">Username</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Log In</button>
                <p> don't have a account? <a href = "register.php"> signup now</a></p>
            </div>
        </form>
    </div>
</body>
</html>

