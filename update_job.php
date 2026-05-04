<?php 

include 'config.php';
session_start();

// redirect if employer not logged in
if(!isset($_SESSION['employer_id'])){
    header("Location: login.php");
    exit();
}

$employer_id = $_SESSION['employer_id'];

if(!isset($_GET['update'])){
    header("Location: my_job.php");
    exit();
}

$job_id = intval($_GET['update']);

//fetch job data
$job_query = mysqli_query($conn, "SELECT * FROM jobs WHERE job_id = $job_id AND employer_id = $employer_id");

if(!$job_query || mysqli_num_rows($job_query) == 0){
    echo"Job not found or acess denied.";
    exit();
}

$job = mysqli_fetch_assoc($job_query);

//update logic
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $job_title = mysqli_real_escape_string($conn, $_POST['job_title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $required_skills = mysqli_real_escape_string($conn, $_POST['required_skills']);
    $salary = floatval($_POST['salary']);
    $job_type = $_POST['job_type'];
    $status = $_POST['status'];
    $experience_required = mysqli_real_escape_string($conn, $_POST['experience_required']);
    $job_level = mysqli_real_escape_string($conn, $_POST['job_level']);
    $education_required = mysqli_real_escape_string($conn, $_POST['education_required']);
    $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
    $expired_date = $_POST['expired_date'];

    //optional image update
    if($_FILES['image']['name']){
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }
    else{
        $image = $job['image']; //keep old image if not change
    }

    $update = mysqli_query($conn,"UPDATE jobs SET
    job_title = '$job_title',
    location = '$location',
    required_skills = '$required_skills',
    salary = '$salary',
    job_type = '$job_type',
    status = '$status',
    experience_required = '$experience_required',
    job_level = '$job_level',
    education_required = '$education_required',
    job_description = '$job_description',
    expired_date = '$expired_date',
    image = '$image'
    WHERE job_id = $job_id AND employer_id = $employer_id
    ");

    if($update){
        $_SESSION['success'] = "Job update sucessfully!";
        header("Location: my_job.php");
        exit();
    }
    else{
        $_SESSION['error'] = "Update failed: " . mysqli_error($conn);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Job</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Link  to the external css file -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

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

<section class="form-container">
    <h1 class="title">Update Job posted</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="job_title">Job title</label>
        <input type="text" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" placeholder="Job title">

        <label for="location">Location</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required placeholder="Location">

        <label for="required_skills">Required skills</label>
        <input type="text" name="required_skills" value="<?php echo htmlspecialchars($job['required_skills']); ?>" placeholder="Required Skills">

        <label for="salary">Salary</label>
        <input type="number" step="0.01" name="salary" value="<?php echo $job['salary']; ?>" placeholder="Salary">

        <label for="education_required">Qualification required</label>
        <input type="text" name="education_required" value="<?php echo htmlspecialchars($job['education_required']); ?>" placeholder="Education Required">

        <label>Job Type</label>
        <select name="job_type">
            <option value="full-time" <?php if ($job['job_type'] == 'full-time'); ?>>Full time</option>
            <option value="part-time" <?php if ($job['job_type'] == 'part-time'); ?>>Part Time</option>
            <option value="remort" <?php if ($job['job_type'] == 'remote'); ?>>Remote</option>
        </select>

        <label>Experience Required</label>
        <select name="experience_required">
            <option value="fresher" <?php if($job['experience_required'] == 'fresher'); ?>>Fresher</option>
            <option value="1-3 years" <?php if($job['experience_required']  == '1-3 years'); ?>>1-3 years</option>
            <option value="3-5 years" <?php if($job['experience_required'] == '3-5 years'); ?>>3-5 years</option>
            <option value="5+ years" <?php if($job['experience_required'] == '5+ years'); ?>>5+ years</option>
        </select>
        
        <label>Job Type</label>
        <select name="job_level">
            <option value="entry" <?php if($job['job_level'] == 'entry'); ?>>Entry Level</option>
            <option value="mid" <?php if($job['job_level'] =='mid'); ?>>Mid Level</option>
            <option value="senior" <?php if($job['job_level'] == 'senior'); ?>>Senior Level</option>
            <option value="executive" <?php if($job['job_level'] == 'executive'); ?>>Executive</option>
        </select> 

        <label>Status</label>
        <select name="status" >
            <option value="active" <?php if($job['status'] == 'active') echo "selected"; ?>>Active</option>
            <option value="closed" <?php if($job['status'] == 'closed') echo "selected"; ?>>Closed</option>
        </select> 

        <label>Expired Date</label>
        <input type="date" name="expired_date" value="<?php echo $job['expired_date']; ?>">
        
        <label for="job_description">Job Description</label>
        <textarea name="job_description" palceholder="Job Description" rows="4"><?php echo htmlspecialchars($job['job_description']); ?></textarea>

        <!--
        <p>Current Image:</p>
        <img src="<?php echo $job['image']; ?>" alt="" style="max-width: 150px"><br><br>
        <input type="file" name="image">
-->
        <input type="submit" value="Update Job" class="btn">

<!-- Cancle Button added new -->
 <div style= "display:flex; gap: 10px; margin-top:10px;">
 <button type="button" class="btn" style="width:120px;" onclick="window.location.href='my_job.php'">Cancel</button>
</div>
    </form>
</section>
















<!-- custom js file link -->
<script src="js/script.js"></script>
    
</body>
</html>