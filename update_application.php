<?php

include 'config.php';

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $application_id = intval($_POST['application_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE applications SET status = '$status' WHERE application_id =$application_id";
    if(mysqli_query($conn, $sql)){
        //new added 
        //fetch the job_id from the updated application
        $get_job = mysqli_query($conn, "SELECT job_id FROM applications WHERE application_id = $application_id");
        $job = mysqli_fetch_assoc($get_job);
        $job_id = $job['job_id'];
        
        $_SESSION['success'] = "Status updated successfully.";
        header("Location: view_applicants.php?job_id=$job_id");
        exit();
    }
    else{
        echo "Error updating status:" . mysqli_error($conn);
    }
}

?>