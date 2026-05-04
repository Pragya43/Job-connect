<?php

include 'config.php';

session_start();

if(!isset($_SESSION['employer_id'])){
    header("Location: login.php");
    exit();
}

$employer_id = $_SESSION['employer_id'];

//get the job_id from URL to filter applicants for taht specificjob
if(!isset($_GET['job_id'])){
    echo "NO job selected.";
    exit();
}

$job_id = (int)$_GET['job_id'];

//Fetch job title for display
$job_query  = mysqli_query($conn, "SELECT job_title FROM jobs WHERE job_id = $job_id AND employer_id = $employer_id");
$job_data = mysqli_fetch_assoc($job_query);

if(!$job_data){
    echo"Invalid job .";
    exit();
}

$job_title = $job_data['job_title'];

// Employer info
$emp_sql = "SELECT u.email AS employer_email, j.company_name 
            FROM employers e 
            JOIN users u ON e.user_id = u.user_id 
            LEFT JOIN jobs j ON j.employer_id = e.employer_id 
            WHERE e.employer_id = $employer_id LIMIT 1";
$emp_result = mysqli_query($conn, $emp_sql);
$emp_info = mysqli_fetch_assoc($emp_result);
$employer_email = $emp_info['employer_email'];
$company_name = $emp_info['company_name'];


//fetch job applicationsrelated to employer's jobs
$query = "
    SELECT a.application_id, a.status,
    j.job_title, 
    js.seeker_id, js.skills, js.experience, js.bio, js.phone_number, js.location,
    u.name, u.email,
    cv.file_path
    FROM applications a
    JOIN jobs j ON a.job_id = j.job_id
    JOIN job_seekers js ON a.seeker_id = js.seeker_id
    JOIN users u ON js.user_id = u.user_id
    LEFT JOIN cv ON u.user_id = cv.user_id
    WHERE j.employer_id = $employer_id AND a.job_id = $job_id
    ";

    $result = mysqli_query($conn, $query);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Link  to the external css file -->
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #fff;
            margin: 8% auto;
            padding: 20px;
            width: 50%;
            border-radius: 10px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 15px;
            top: 10px;
            font-size: 20px;
            cursor: pointer;
        }

        input, textarea {
            width: 100%;
            margin: 8px 0;
            padding: 6px;
        }

        button {
            margin-top: 10px;
        }
    </style>

</head>
<body>


<?php if (isset($_SESSION['message'])): ?>
    <div style="background-color: #dff0d8; color: #3c763d; padding: 10px; text-align: center; margin: 10px; border-radius: 5px;">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

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

<?php if(isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
    <div class="message <?php echo isset($_SESSION['success']) ? 'success' : 'error'; ?>">
        <span>
            <?php 
                echo isset($_SESSION['success']) ? $_SESSION['success'] : $_SESSION['error']; 
                unset($_SESSION['success'], $_SESSION['error']);
            ?>
        </span>
        <i class="fas fa-times close-btn" onclick="this.parentElement.style.display='none';"></i>
    </div>
<?php endif; ?>

<section class="applications">
    <h1 class="title">Applicants for: <?php echo htmlspecialchars($job_title); ?></h1>
    <a href="my_job.php" class="back-btn">Back to My Jobs</a>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <div class="table-container">
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Location</th>
                    <th>Skills</th>
                    <th>Experience</th>
                    <th>Bio</th>
                    <th>Status</th>
                    <th>CV</th>
                    <th>Update Status</th>
                    <th>Contact</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['job_title']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['skills']; ?></td>
                    <td><?php echo $row['experience']; ?></td>
                    <td><?php echo $row['bio']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['file_path']): ?>
                            <a href="<?php echo $row['file_path']; ?>" target="_blank">View CV</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="update_application.php" method="POST">
                            <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                            <select name="status">
                                <option value="pending" <?php if($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                <option value="reviewed" <?php if($row['status'] == 'reviewed') echo 'selected'; ?>>Reviewed</option>
                                <option value="proceed to interview" <?php if($row['status'] == 'proceed to interview') echo 'selected'; ?>>Proceed to Interview</option>
                                <option value="selected" <?php if($row['status'] == 'selected') echo 'selected'; ?>>Selected</option>
                                <option value="rejected" <?php if($row['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>
                        <button onclick="openModal('<?php echo $row['email']; ?>', '<?php echo addslashes($row['name']); ?>', '<?php echo addslashes($row['job_title']); ?>')">Contact</button>

                        </td>
                    <td>

                    <!-- Update button redirects to edit_status.php 
            <form action="edit_status.php" method="GET" style="display:inline;">
                <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                <button type="submit" class="update-btn">Update</button>
                        -->
                        <form action="delete_application.php" method="POST" onsubmit="return confirm('Delete this application?');">
                            <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    <?php else: ?>
        <p class="no-jobs">No applications yet.</p>
    <?php endif; ?>
</section>

<!-- Contact Modal -->
<div id="emailModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Contact <span id="modalName"></span></h3>
        <form action="jobseekeremail.php" method="POST">
            <input type="hidden" name="to_email" id="modalToEmail">
            <input type="hidden" name="job_title" id="modalJobTitle">
            <input type="hidden" name="from_email" value="<?php echo $employer_email; ?>">
            <input type="hidden" name="company_name" value="<?php echo $company_name; ?>">
            <input type="hidden" name="job_id" value="<?php echo $job_id; ?>"> 

            <label>Subject:</label>
            <input type="text" name="subject" required>

            <label>Message:</label>
            <textarea name="message" rows="4" required></textarea>

            <label>Interview Date:</label>
            <input type="date" name="interview_date" required>

            <button type="submit">Send Email</button>
        </form>
    </div>
</div>

<script>
function openModal(email, name, jobTitle) {
    document.getElementById('modalToEmail').value = email;
    document.getElementById('modalJobTitle').value = jobTitle;
    document.getElementById('modalName').textContent = name + ' <' + email + '>';
    document.getElementById('emailModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('emailModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('emailModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>





<!-- custom js file link -->
 <script src="js/script.js"></script>
    
</body>
</html>