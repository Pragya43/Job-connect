<?php 

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'], $_POST['message'], $_POST['job_title'])){
    $to = $_POST['email'];
    $subject = "Interview Invitation for " . $_POST['job_title'];
    $message = $_POST['message'];
    $headers = "FROM: hr@yourcompany.com";

    if(mail($to, $subject, $message, $headers)){
        $_SESSION['message'] = "Email sent successfully!";
    }else{
        $_SESSION['message'] = "Failed to send email.";
    }
}

header("Location: applied_job.php");
exit();

?>