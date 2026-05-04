<?php 
include 'config.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id'])){
    $app_id = intval($_POST['application_id']);

    //new added
    //get job_id defore the application
    $get_job = mysqli_query($conn, "SELECT job_id FROM applications WHERE application_id = $app_id");
    $job = mysqli_fetch_assoc($get_job);
    $job_id = $job['job_id'];

    $delete = mysqli_query($conn, "DELETE FROM applications WHERE application_id = $app_id");

    $_SESSION['message'] = $delete ? "Application deleted!" : "Delete Failed";

     header("Location: view_applicants.php?job_id=$job_id");
exit();
}else{
    $_SESSION['error'] = "Invalid request.";
    header("Location: my_job.php");
    exit();
}
 




?>