<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'job_seeker') {
    header("Location: login.php");
    exit();
}
?>

<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<header class="header">
    <div class="flex">
        <a href="jobseeker_dashboard.php">
            <img src="logo.jpg" class="logo" alt="Shakriya Logo">
        </a>

        <nav class="navbar">
            <a href="jobseeker_dashboard.php">Dashboard</a>
            <a href="view_jobs.php">Browse Jobs</a>
            <a href="profile.php">My Profile</a>
            <a href="my_applications.php">My Applications</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars" display ="none"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="user-box">
            <p>Username: <span><?php echo htmlspecialchars($_SESSION['name']); ?></span></p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.getElementById('menu-btn');
    const userBtn = document.getElementById('user-btn');
    const navbar = document.querySelector('.header .navbar');
    const userBox = document.querySelector('.header .user-box');

    menuBtn.addEventListener('click', () => {
        navbar.classList.toggle('active');
        userBox.classList.remove('active');
    });

    userBtn.addEventListener('click', () => {
        userBox.classList.toggle('active');
        navbar.classList.remove('active');
    });

    window.addEventListener('scroll', () => {
        navbar.classList.remove('active');
        userBox.classList.remove('active');
    });
});

</script>
