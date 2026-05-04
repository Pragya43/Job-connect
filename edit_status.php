<?php 

include 'config.php';
session_start();

if(!isset($_SESSION['employer_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['application_id'])){
    echo "Application ID not specified.";
    exit();
}

$application_id = intval($_GET['application_id']);

//fetch curreb=nt status to pre-fill the form
$query = "SELECT a.status, u.name, j.job_title
        From applications a
        JOIN job_seekers js ON a.seeker_id = js.seeker_id
        JOIN users u ON js.user_id = u.user_id
        JOIN jobs j ON a.job_id = j.job_id
        WHERE a.application_id = $application_id";
    
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if(!$data){
        echo "Invalid application.";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Application Status</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Link  to the external css file -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<header class ="header">

    <div class="flex">
    <img src="logo.png" class="logo" width="125px">

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

<section class="edit-status-form">
    <div class="from-wrapper">
    <h2 class="edit-heading">Update Status for: <?php echo htmlspecialchars($data['name']); ?> (<?php echo htmlspecialchars($data['job_title']); ?>)</h2>
    <form action="update_application.php" method="POST">
        <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
        <label for="status">Status:</label>
        <select name="status">
            <option value="pending" <?php if($data['status'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="reviewed" <?php if($data['status'] == 'reviewed') echo 'selected'; ?>>Reviewed</option>
            <option value="proceed to interview" <?php if($row['status'] == 'proceed to interview') echo 'selected'; ?>>Proceed to Interview</option>
            <option value="selected" <?php if($data['status'] == 'selected') echo 'selected'; ?>>Selected</option>
            <option value="rejected" <?php if($data['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
        </select>
        <div class="btn-group">
        <button type="submit" class="update-btn">Update Status</button>
        <a href ="javascript:history.back();" class="back-btn">Cancel</a>
        </div>
    </form>
    </div>
</section>
    














<!-- custom js file link -->
 <script src="js/script.js"></script>
    
</body>
</html>