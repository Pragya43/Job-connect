<?php 

include 'config.php';
session_start();

if(!isset($_SESSION['employer_id'])){
    header("Location:login.php");
    exit();

}

$employer_id = $_SESSION['employer_id'];

//handle jod deletion
if(isset($_GET['delete'])){
    $job_id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM jobs WHERE job_id = $job_id AND employer_id = $employer_id");
    header("Location: my_job.php");
    exit();
}

//fetch jobs for current employer
$jobs = mysqli_query($conn, "SELECT * FROM jobs WHERE employer_id = $employer_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Jobs</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Link  to the external css file -->
<link rel="stylesheet" href="./css/style.css">

</head>
<body>

<?php

if(isset($message)){
    foreach($message as $message){
        echo '
        <div class="message">
        <span>' .$message.'</span>
        <i class="fas fa-times" onclick ="this.parentElement.remove();" style="cursor:pointer; margin-left:10px;"></i>
        </div>
        ';
    }
}
?>

<header class ="header">

    <div class="flex">
    <img src="logo.jpg" class="logo" width="125px">

    <nav class="navbar">
        <a href="employer_page.php">Home</a>
        <a href="added_job.php">Add Job</a>
        <a href="my_job.php">My Jobs</a>
        <a href="user_applied.php">Job Seeker</a>
    </nav>
    <div class="icons">
        <div id="menu-btn" class="fas fa-bars"></div>
        <div id="user-btn" class="fas fa-user"></div>
    </div>
    <div class="user-box">
    <p> Username : <span>
            <?php 
            echo $_SESSION['employer_name'];
            ?>
            </span>

                </p>
                <a href="logout.php" class ="delete-btn">Logout</a>
               
    </div>
    </div>
</header>

<?php if(isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
    <div class="message <?php echo isset($_SESSION['success']) ? 'success' : 'error'; ?>">
        <span>
            <?php 
                echo isset($_SESSION['success']) ? $_SESSION['success'] : $_SESSION['error']; 
                unset($_SESSION['success'], $_SESSION['error']);
            ?>
        </span>
        <i class="fas fa-times close-btn" onclick="this.parentElement.style.display='none';"></i>
    </div>
<?php endif; ?>



<section class="my-jobs">
    <h1 class="title"> Posted Jobs</h1>

    <div class="job-container">
        <?php if (mysqli_num_rows($jobs) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($jobs)): ?>

        <div class="job-box">
            <div class="job-left">
            <img src="<?php echo $row['image']; ?>" alt="Job Image" style="max-width: 150px;">
            <h3><?php echo $row['company_name']; ?></h3>
            </div>
            <div class="job-right">
            <p><strong>Job Title:</strong> <?php echo $row['job_title']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Required skills:</strong> <?php echo $row['required_skills']; ?></p>
            <p><strong>Qualification Required:</strong> <?php echo $row['education_required']; ?></p>
            <p><strong>Job Type:</strong> <?php echo $row['job_type']; ?></p>
            <p><strong>Salary:</strong> Rs<?php echo $row['salary']; ?></p>
            <p><strong>Experience Required:</strong> <?php echo $row['experience_required']; ?></p>
            <p><strong>Job Level:</strong> <?php echo $row['job_level']; ?></p>
            <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
            <p><strong>Expired Date:</strong> <?php echo date("F j, Y", strtotime($row['expired_date'])) ; ?></p>
            <!-- <p><strong>Job Description:</strong> <?php echo $row['job_description']; ?></p>
        -->
            <p><strong>Job Description:</strong></p>
<ul class="job-description">
<?php
    $lines = explode("\n", $row['job_description']);
    foreach ($lines as $line) {
        $clean_line = htmlspecialchars(trim($line));
        if (!empty($clean_line)) {
            echo "<li>$clean_line</li>";
        }
    }
?>
</ul>
            <div class="btn-group">
           <a href="update_job.php?update=<?php echo $row['job_id']; ?>" class="update-btn">Update</a>
           <a href="view_applicants.php?job_id=<?php echo $row['job_id']; ?>" class="view-btn">View Applicants</a>
           <a href="my_job.php?delete=<?php echo $row['job_id']; ?>" class="delete-btn" onclick="return confirm('delete this job?');">Delete</a>
           </div>
           </div>
        </div>
        <?php endwhile; ?>

        <?php else: ?>
            <p class="no-jobs">No jobs Posted Yet.</p>
            <?php endif; ?>
        </div>
</section>


    

<!-- custom js file link -->
<script src="js/script.js"></script>
 

</body>
</html>