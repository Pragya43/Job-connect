<?php
include 'config.php';
include 'adminnav.php';

// Get total users
$result_users = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$row_users = mysqli_fetch_assoc($result_users);
$total_users = $row_users['total_users'];

// Get total employers from users table
$result_employers = mysqli_query($conn, "SELECT COUNT(*) AS total_employers FROM users WHERE user_type = 'employer'");
$row_employers = mysqli_fetch_assoc($result_employers);
$total_employers = $row_employers['total_employers'];

// Get total job seekers from users table
$result_seekers = mysqli_query($conn, "SELECT COUNT(*) AS total_seekers FROM users WHERE user_type = 'job_seeker'");
$row_seekers = mysqli_fetch_assoc($result_seekers);
$total_seekers = $row_seekers['total_seekers'];

 //Get total blacklisted users
$result_blacklist = mysqli_query($conn, "SELECT COUNT(*) AS total_blacklisted FROM users WHERE blacklist = 1");
$row_blacklist = mysqli_fetch_assoc($result_blacklist);
$total_blacklisted = $row_blacklist['total_blacklisted'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard - Shakriya</title>
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel =" stylesheet" href =" admin.css">    
    
</head>
<body>

<div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <div class="stats">
        <div class="stat-box">
            <h2><?php echo $total_users; ?></h2>
            <p>Total Users</p>
        </div>
        <div class="stat-box">
            <h2><?php echo $total_employers; ?></h2>
            <p>Total Employers</p>
        </div>
        <div class="stat-box">
            <h2><?php echo $total_seekers; ?></h2>
            <p>Total Job Seekers</p>
        </div>
        <div class="stat-box blacklisted">
            <h2><?php echo $total_blacklisted; ?></h2>
            <p>Blacklisted Users</p>
        </div>
    </div>
</div>

</body>
</html>
