<?php

session_start();

include('config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendemail_verify($name, $email, $verify_token){
    $mail = new PHPMailer(true);

    try{
        //$mail ->SMTPDebug = 2; 
        $mail->isSMTP();
        $mail->SMTPAuth = true;
    
        $mail->Host = "smtp.gmail.com";
        $mail->Username ="gp175443@gmail.com";
        $mail->Password = "nphd zpxu ptow qvzf";
    
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;
    
        $mail->setFrom("gp175443@gmail.com",$name);
        $mail->addAddress($email);
    
        $mail->isHTML(true);
        $mail->Subject = "Email Verification from JobConnect Website";
    
        $email_template ="
        <h2>You have Registered with JobConnect</h2>
        <h5>Verify your email address to Login with the below given link</h5>
        <br/><br/>
        <a href='http://localhost/project/verify-email.php?token=$verify_token'>Click Me</a>
        ";
    
        $mail->Body = $email_template;
        $mail->send();
        //echo'Message has been sent';
        }catch(Exception$e){
             // echo"message could no be sent.Mailer Error:{$mail->ErrorInfo}";
        }
    }
    

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = ($_POST['password']);
    $phone_number = $_POST['phone_number'];
    $location = $_POST['location'];
    $verify_token = md5(rand());
    $user_type =$_POST['user_type'];

    //check username exists or not 
    $check_name_query = "SELECT name FROM users WHERE name='$name' LIMIT 1 ";
    $check_name_query_run = mysqli_query($conn, $check_name_query);

    if(mysqli_num_rows($check_name_query_run) > 0){
        $_SESSION['status'] =" User already exists";
        header("Location: register.php");

    }
    else{
        //Insert user/ register user data
        $query = "INSERT INTO users(name, email, password, phone_number, location, user_type, verify_token, blacklist) VALUES ('$name', '$email', '$password', '$phone_number', '$location', '$user_type' ,'$verify_token', 0)";
        $query_run =mysqli_query($conn, $query);

        if($query_run){
            sendemail_verify("$name", "$email", "$verify_token");
            $_SESSION['status'] = "Registration successfull! Verify your Email address";
            header("Location: register.php");

        }
        else{
            $_SESSION['status'] = 'Registration Failed';
            header("Location:register.php");
        }

    }
}

?>