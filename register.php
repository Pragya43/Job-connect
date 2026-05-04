<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!--  css file link  -->
<link rel="stylesheet" href="style2.css">

    </head>

    <!-- Header 
    <div class="header">
        <div class="container">
            <div class="navbar">
                <div class="right">
                    <a href="login.php"> <button class="btn" id="loginBtn">Log In</button></a>
                     <a href="register.php" ><button class="btn" id="signupBtn">Sign Up</button></a>
                </div>
            </div>
        </div>
    </div>
-->

<header>
          <img src="Logo.jpg" alt="Logo" class="logo">

           <div id="menu-bar" class="fas fa-bars"></div>
          
           <nav class="navbar">
            <a href="home.php">home</a>
            <a href="home.php">about us</a>
            <a href="home.php">service</a>
            <a href="home.php">contact</a>
           <a href="login.php"> <button class="btn" id="loginBtn">Log In</button></a>
            <a href="register.php" ><button class="btn" id="signupBtn">Sign Up</button></a>
           </nav>
           

        </header>

    <div class="signup-container">

    <div class="alert">
        <?php
        if(isset($_SESSION['status'])){
            echo"<h4>".$_SESSION['status']."</h4>";
            unset($_SESSION['status']);
        }

        ?>
    </div>
    

        <h2>Sign-up</h2>
        <form action="code.php" method="POST">
            <div class="form-group">
                <label for="new-name">Username</label>
                <input type="text" id="new-name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="new-password">Password</label>
                <input type="password" id="new-password" name="password" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" required>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>
            </div>

            <div class="form-group">
                <label for="user-type">User Type</label>
                <select id="user-type" name="user_type" required>
                    <option value="admin">Admin</option>
                    <option value="employer">Employer</option>
                    <option value="job_seeker">Job Seeker</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Sign Up</button>
                <p> already signup. <a href = "login.php" >login now</a></p>
            </div>
        </form>
        </div>

        </body>
</html>
