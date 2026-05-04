<?php

include 'config.php';

session_start();

if(!isset($_SESSION['employer_id'])){
    header("Location: login.php");
    exit();
}

$employer_id = $_SESSION['employer_id'];

//fetch job applicationsrelated to employer's jobs

$query = "
    SELECT a.application_id, a.status,
    j.job_title, j.job_id,
    js.seeker_id, js.skills, js.experience, js.bio, js.phone_number, js.location,
    u.name, u.email,
    cv.file_path
    FROM applications a
    JOIN jobs j ON a.job_id = j.job_id
    JOIN job_seekers js ON a.seeker_id = js.seeker_id
    JOIN users u ON js.user_id = u.user_id
    LEFT JOIN cv ON u.user_id = cv.user_id
    WHERE j.employer_id = $employer_id
    ";

    $result = mysqli_query($conn, $query);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>

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
    <img src="logo.png" class="logo" width="125px">

    <nav class="navbar">
        <a href="employer_page.php">Home</a>
        <a href="added_job.php">Add Job</a>
        <a href="my_job.php">My Jobs</a>
        <a href="applied_job.php">View Applicants</a>
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

<section class="applications">

    <h1 class="title">Job Applications</h1>

    <div class="applications-container">
    <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="application-box">
                <h3><p><strong>Job Title:</strong> <?php echo$row['job_title']; ?></p></h3>
                <p><strong>Username:</strong> <?php echo $row['name']; ?></p>
                <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $row['phone_number']; ?></p>
                <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                <p><strong>Skills:</strong> <?php echo $row['skills']; ?></p>
                <p><strong>Experience:</strong> <?php echo $row['experience']; ?></p>
                <p><strong>Bio:</strong> <?php echo $row['bio']; ?></p>
                <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
                <form action="update_application.php" method="POST" class="status-form">
                    <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                    <select name ="status">
                        <option value="pending" <?php if($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                        <option value="reviewed" <?php if($row['status'] == 'reviewed') echo 'selected'; ?>>Reviewed</option>
                        <option value="selected" <?php if($row['status'] == 'selected') echo 'selected'; ?>>Selected</option>
                        <option value="rejected" <?php if($row['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>                    
                    </select>

                <?php if ($row['file_path']): ?>
                <a href="<?php echo $row['file_path']; ?>" target="_blank" class="view-cv-btn">View CV </a>
                <?php endif; ?>

                <button type="submit">Update Status</button>
                </form>
                
                
                <form action="delete_application.php" method="POST" onsubmit="return confirm('Delete this application?');">
                    <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                    <button type="submmit" class="delete-btn">Delete Application</button>
                </form>

                <!--
                <form action="send_email.php" method="POST">
                    <input type="hidden" name="email" value="<?php echo $row['job_title']; ?>">
                    <input type="hidden" name="job_title" vlaue="<?php echo $row['job_title']; ?>">
                    <textarea name="message" placeholder="Enter Your message here...." required></textarea>
                    <button type="submit" class="email-btn">Send Interview Email</button>
                </form>
                -->

                </div>
          
            <?php endwhile; ?>
            <?php else: ?>
                <p class="no-jobs">No applications Yet.</p>
                <?php endif; ?>
        </div>
</section>




<!-- custom js file link -->
 <script src="js/script.js"></script>
    
</body>
</html>