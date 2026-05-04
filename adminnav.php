<?php
session_start();
include 'config.php';

// Default username fallback
$username_display = 'Admin';

// Check if admin_name session exists and is not empty
if (isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name'])) {
    $username_display = $_SESSION['admin_name'];
} elseif (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
    // fallback to generic name if admin_name not set
   // $username_display = $_SESSION['name'];
}
?>

<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<header class="header">
    <div class="flex">
        <a href="admin_dashboard.php">
            <img src="Logo.jpg" class="logo" alt="Shakriya Logo">
        </a>

        <nav class="navbar">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_users.php">Manage Users</a>
            <a href="message.php">Feedback</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars" style="display: none;"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="user-box">
            <p>Username: <span><?php echo htmlspecialchars($username_display); ?></span></p>
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
