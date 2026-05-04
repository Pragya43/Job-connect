<?php
session_start();
include('config.php');

if(isset($_POST['submit'])){
    if(!empty(trim($_POST['name'])) && !empty(trim($_POST['password']))){
        $name= mysqli_real_escape_string($conn,$_POST['name']);
        $password = mysqli_real_escape_string($conn,$_POST['password']);

        $login_query = "SELECT * FROM users WHERE  name='$name' AND password = '$password' LIMIT 1";
        $login_query_run = mysqli_query($conn, $login_query);

        if(mysqli_num_rows($login_query_run) > 0){
            $row = mysqli_fetch_array($login_query_run);
            //echo $row['verify_status'];

            //check if blacklisted
            if($row['blacklist'] == 1){
                $_SESSION['status'] = "You can't login because you are backlisted. ";
                header("Location: login.php");
                exit(0);
            }

        if($row['verify_status'] == "1"){
            $_SESSION['authenticated'] = TRUE;
            $_SESSION['auth_user'] = [
                'name' => $row['name'],
                'user_type' => $row['user_type'],
            ];

            if($row['user_type'] == 'admin'){
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['admin_name'] = $row['name'];
                header("Location: admin_dashboard.php");
                exit(0);
                
                //employer redirection
            }elseif($row['user_type'] == 'employer'){
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['employer_id'] = $row['user_id'];
                $_SESSION['employer_name'] = $row['name'];
                header("Location: employer_page.php");
                exit(0);

                //job seeker redirection
            }elseif($row['user_type'] == 'job_seeker'){
                $user = $row;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['name'] = $user['name'];
                echo "<p style='color:green;'>Login successful! Welcome, " . $user['name'] . "</p>";
                header("Location: jobseeker_dashboard.php");
                exit(0);
            }else{
                $_SESSION['status'] = "Unknown user type.";
                header("Location: login.php");
                exit(0);
            }
        
        }
        else{
            $_SESSION['status'] = "Please verify your email Address to login";
            header("Location: login.php");
            exit(0);
        }
    }
        else{
            $_SESSION['status'] = "Invalid Username or password";
            header("Location: login.php");
            exit(0);

        }
}
    else{
        $_SESSION['status'] = "All fields are Mandetory";
        header("Location: login.php");
        exit(0);
    }
}

    
?>