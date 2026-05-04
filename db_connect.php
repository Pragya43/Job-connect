<?php

//connecting to the database
$servername="localhost";
$username ="root";
$password ="";
$database ="project";

//create connection 
$conn = mysqli_connect($servername, $username, $password, $database);

//die if connection was not successful
if(!$conn){
    die("sorry, we failedto connect:" . mysqli_connect_error());
}



// create tables
$sql = "CREATE TABLE `users`(
    `user_id` INT AUTO_INCREMENT PRIMARY KEY ,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone_number` VARCHAR(20),
    `location`VARCHAR(100),
    `user_type` VARCHAR(20) NOT NULL DEFAULT 'user',
    `verify_token` VARCHAR(255),
    `verify_status` TINYINT(1)
    ALTER TABLE users ADD COLUMN blacklist TINYINT(1) NOT NULL DEFAULT 0;

)";

$sql = "CREATE TABLE `job_seekers`(
    `seeker_id` INT AUTO_INCREMENT PRIMARY KEY, 
    `user_id` INT NOT NULL,
    `skills` TEXT,
    `experience` TEXT,
    `bio` TEXT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_user` (`user_id`) 
)";

$sql = "CREATE TABLE `employers`(
    `employer_id` INT AUTO_INCREMENT PRIMARY KEY, 
    `user_id` INT NOT NULL,
    `company_name` VARCHAR(150),
    `company_description` TEXT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_user` (`user_id`)
)";

$sql = "CREATE TABLE `cv`(
    `cv_id` INT AUTO_INCREMENT PRIMARY KEY, 
    `user_id` INT NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
)";

$sql ="CREATE TABLE `jobs`(
    `job_id` INT AUTO_INCREMENT PRIMARY KEY, 
    `employer_id` INT NOT NULL,
    `job_title` VARCHAR(150) NOT NULL,
    `company_name` VARCHAR(150) NOT NULL,
    `location` VARCHAR(100),
    `job_description` TEXT NOT NULL,
    `required_skills` TEXT,
    `salary` DECIMAL(10,2),
    `job_type`VARCHAR(20) NOT NULL DEFAULT 'full_time',
    `status` VARCHAR(20)NOT NULL DEFAULT 'active',
    `experience_required` VARCHAR(20) NOT NULL DEFAULT 'fresher',
    `job_level` VARCHAR(20)NOT NULL DEFAULT 'entry',
    `image` VARCHAR(100) NOT NULL,
    `education_required` VARCHAR(100),
    FOREIGN KEY (`employer_id`) REFERENCES `employers`(`employer_id`) ON DELETE CASCADE
)";

$sql ="CREATE TABLE `applications`(
    `application_id` INT AUTO_INCREMENT PRIMARY KEY, 
    `job_id` INT NOT NULL,
    `seeker_id` INT NOT NULL,
    `status`VARCHAR(20) NOT NULL DEFAULT 'pending',
    FOREIGN KEY(`job_id`) REFERENCES `jobs`(`job_id`) ON DELETE CASCADE ,
    FOREIGN KEY(`seeker_id`) REFERENCES `job_seekers`(`seeker_id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_application` (`job_id`, `seeker_id`)
)";
 $SQL="CREATE TABLE message (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL
)";


// $sql ="CREATE TABLE `message`(
//     `message_id` INT AUTO_INCREMENT PRIMARY KEY, 
//     `user_id` INT,
//     `name` VARCHAR (100) NOT NULL,
//     `email` VARCHAR(100) NOT NULL,
//     `phone` VARCHAR (20),
//     `message` TEXT NOT NULL,
//     FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
// )";
/* i altered the job seeker job table 
ALTER TABLE job_seekers
ADD COLUMN phone_number VARCHAR(20),
ADD COLUMN location VARCHAR(100);

*/
/* added company name to the job table */

?>