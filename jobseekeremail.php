<?php
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to_email = $_POST['to_email'];
    $from_email = $_POST['from_email']; // actual employer email
    $company_name = $_POST['company_name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $interview_date = $_POST['interview_date']; //added new for interview
    $job_title = $_POST['job_title']; // NEW: job title from form

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration (system Gmail)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // System email credentials
        $mail->Username = 'gp175443@gmail.com';
        $mail->Password = 'nphd zpxu ptow qvzf';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Always send from your system email
        $mail->setFrom('gp175443@gmail.com', 'JobConnect Platform');

        // ✅ Set reply-to to the employer’s actual email
        $mail->addReplyTo($from_email, $company_name);

        // Send to job seeker
        $mail->addAddress($to_email);
        $mail->Subject = $subject;

        $mail->isHTML(true);
        $mail->Body = nl2br("
            <p><strong>Message from {$company_name}</strong></p>
            <p><strong>Position:</strong> {$job_title}</p>
            <p>{$message}</p>
             <p><strong>Interview Date:</strong> {$interview_date}</p>
            <hr>
            <p>You can reply directly to this email, for any kind of help: <a href='mailto:{$from_email}'>{$from_email}</a></p>
        ");
        $mail->AltBody = strip_tags("Message from {$company_name}\n\nPosition: {$job_title}\n\n{$message}\n\nInterview Date: {$interview_date}\n\nReply to: {$from_email}");

        $mail->send();

        $_SESSION['message'] = "✅ Email successfully sent to job seeker!";
        header("Location: view_applicants.php?job_id=" . $_POST['job_id']);
        exit;
    } catch (Exception $e) {
        $_SESSION['message'] = "❌ Email failed. Mailer Error: {$mail->ErrorInfo}";
        header("Location: view_applicants.php?job_id=" . $_POST['job_id']);
        exit;
    }
} else {
    $_SESSION['message'] = "Invalid access.";
    header("Location: view_applicants.php?job_id=" . $_POST['job_id']);
    exit;
}
