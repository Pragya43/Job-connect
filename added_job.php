<?php

include 'config.php';

session_start();

if(!isset($_SESSION['employer_id'])){
    header("Location: login.php");
}

$employer_id = $_SESSION['employer_id'];

//fetch employer details
$fetch_employer = mysqli_query($conn, "SELECT company_name, company_logo FROM employers WHERE employer_id = '$employer_id'");
$employer_data = mysqli_fetch_assoc($fetch_employer);

//check if data was fetched 
if($employer_data){
$company_name = $employer_data['company_name'];
$company_logo = $employer_data['company_logo']; //This will be stored in `image`
}
else{
    $company_name = "";
    $company_logo = "";
}
if (isset($_POST['submit'])) {
    $job_title = $_POST['job_title'];
    $company_name = $_POST['company_name'];
    //$job_description = $_POST['job_description'];
    $job_description = trim($_POST['job_description']);
    $location = $_POST['location'];
    $required_skills = $_POST['required_skills'];
    $salary = $_POST['salary'];
    $job_type = $_POST['job_type'];
    $experience_required = $_POST['experience_required'];
    $category = $_POST['category'];// new added
    $job_level = $_POST['job_level'];
    $education_required = $_POST['education_required'];
    $status = $_POST['status'];
    $expired_date = $_POST['expired_date'];

    // use the employer's stored logo instead of uploading
    $image = ($company_logo);

    // ✅ Use placeholders (?) in the SQL statement
    $sql = "INSERT INTO jobs (
        job_title, company_name, employer_id, job_description, location,  required_skills, 
        salary, job_type, experience_required, category, job_level, image, education_required, status, expired_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // ✅ All fields are strings except salary (float or double), and employer_id (assume int)
        mysqli_stmt_bind_param($stmt, "ssissssssssssss",
            $job_title, $company_name, $employer_id, $job_description, $location, 
            $required_skills, $salary, $job_type, $experience_required,$category,
            $job_level, $image, $education_required, $status, $expired_date
        );

        if (mysqli_stmt_execute($stmt)) {
            $message[] ="<span style='font-size: 18px;'>Job posted successfully!</span>";
        } else {
            $message[] = "Execute Error: " . mysqli_stmt_error($stmt);
        }
    } else {
        echo "Prepare Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Jobs</title>

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

<!-- add job form create section start -->
<section class="add-jobs">
    <h1 class="title">Add Job</h1>



    <form action="added_job.php" method="POST" enctype="multipart/form-data">
       
    <?php if (!empty($company_logo)): ?>
        <label for="company_logo" style="display: block; text-align:center; font-weight: bold; margin-bottom:10px;">Company Logo</label>
            <div style=" text-align: center; margin-bottom: 20px;">
                <img src="<?php echo htmlspecialchars($company_logo); ?>" alt="Company Logo" style="max-width: 150px; max-height: 150px;">
            </div>
            <?php endif; ?>

        <label for="job_title">Job title</label>
        <input type="text" id="job_title" name="job_title" required>

        <label for="company_name">Company Name</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company_name);?>" readonly>

        <label for="location">Location</label>
        <input type="text" id="location" name="location" required>

        <label for="required_skills">Required skills</label>
        <textarea id="required_skills" name="required_skills" row="3" required></textarea>

        <label for="salary">Salary</label>
        <input type="number" id="salary" name="salary" step="0.01" required>

        <label for="education_required">Qualification required</label>
        <input type="text" id="education_required" name="education_required" required>

        <select name="job_type" class="box" required>
            <option value="" disable selected>Select Job type</option>
            <option value="full-time">Full Time</option>
            <option value="part-time">Part Time</option>
            <option value="remote">Remote</option>
        </select>

        <select name="experience_required" class="box" required>
            <option value="" disable selected>Experience Required</option>
            <option value="fresher">Fresher</option>
            <option value="1-3 years">1-3 years</option>
            <option value="3-5 years">3-5 years</option>
            <option value="5+ years">5+ years</option>
        </select>

        <select name="job_level" class="box" required>
            <option value="" disable selected>Job Level</option>
            <option value="entry">Entry Level</option>
            <option value="mid">Mid Level</option>
            <option value="senior">Senior Level</option>
            <option value="executive">Executive</option>
        </select>

        <!--NEW: Job Category dropdown -->
        <select name="category" class="box" required>
            <option value="" disable selected>Select Job Category</option>
            <option value="IT">IT</option>
            <option value="Marketing">Marketing</option>
            <option value="Finance">Finance</option>
            <option value="Engineering">Engineering</option>
            <option value="Healthcare">Healthcare</option>
            <option value="Education">Education</option>
            <option value="Others">Others</option>
        </select>

        <select name="status" class="box" required>
            <option value="" disable selected>Select status</option>
            <option value="active">Active</option>
            <option value="closed">Closed</option>
        </select>

        <label for="expired_date">Expired Date</label>
        <input type="date" id="expired_date" name="expired_date" required>
    

        <label for="job_description">Job Description</label>
        <textarea id="job_description" name ="job_description" rows="4" required></textarea>

        <button type="submit" name="submit">Submit</button>

    </form>
</section>





<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>