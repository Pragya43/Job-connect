<?php
session_start();
include 'config.php';

if (!isset($_SESSION['employer_id'])) {
    header("Location: login.php");
    exit();
}

$employer_id = $_SESSION['employer_id'];

// Fetch employer details
$emp_sql = "SELECT e.*, u.email, j.company_name, j.location
             FROM employers e 
            JOIN users u ON e.user_id = u.user_id 
            LEFT JOIN jobs j ON e.employer_id = j.employer_id
            WHERE e.employer_id = ?";
$emp_stmt = mysqli_prepare($conn, $emp_sql);
mysqli_stmt_bind_param($emp_stmt, "i", $employer_id);
mysqli_stmt_execute($emp_stmt);
$emp_result = mysqli_stmt_get_result($emp_stmt);
$employer = mysqli_fetch_assoc($emp_result);

// Fetch all job seekers
$sql = "SELECT u.user_id, u.name, u.email, js.seeker_id, js.phone_number, js.location, js.skills, js.experience, js.bio, cv.file_path
        FROM users u
        JOIN job_seekers js ON u.user_id = js.user_id
        LEFT JOIN cv ON u.user_id = cv.user_id
        WHERE u.user_type = 'job_seeker'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>ALL Job Seekers</title>
    
    <!-- font awesome cdn link -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Link  to the external css file -->
<link rel="stylesheet" href="./css/style.css">

    <style>
        .card { border: 1px solid #ccc; padding: 15px; margin: 15px; border-radius: 8px; }
        .card h3 { margin-top: 0; }
        #emailModal { display: none; position: fixed; top: 10%; left: 30%; width: 40%; background: white; padding: 20px; box-shadow: 0 0 10px gray; z-index: 10; }
        #emailModal input, #emailModal textarea { width: 100%; margin-bottom: 10px; }
    </style>
</head>
<body>

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



<?php if (isset($_SESSION['message'])): ?>
    <div style="background-color: #dff0d8; color: #3c763d; padding: 10px; text-align: center; margin: 10px; border-radius: 5px;">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>


    <h1 class="title">All Job Seekers</h1>

    <div class="card-container">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="card">
            <h3><p><strong>Username:</strong> <?php echo$row['name']; ?></p></h3>
            <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone_number']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
            <p><strong>Skills:</strong> <?= htmlspecialchars($row['skills']) ?></p>
            <p><strong>Experience:</strong> <?= htmlspecialchars($row['experience']) ?></p>
            <p><strong>Bio:</strong> <?= htmlspecialchars($row['bio']) ?></p>
            <?php if (!empty($row['file_path'])): ?>
                <p><a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank">View CV</a></p>
            <?php endif; ?>
            <button onclick="openMailForm('<?= $row['email'] ?>', '<?= addslashes($row['name']) ?>')">Contact Me</button>
        </div>
    <?php endwhile; ?>
    </div>

    <!-- Email Modal -->
    <div id="emailModal">
        <form method="post" action="email.php">
            <input type="hidden" name="to_email" id="to_email">
            <label>To:</label>
            <input type="text" id="to_display" disabled>

            <label>FROM:</label>
            <input type="email" name="from_email" value="<?= htmlspecialchars($employer['email']) ?>" required readonly>

            <label>Company Name:</label>
            <input type="text" name="company_name" value="<?= htmlspecialchars($employer['company_name']) ?>" readonly>

            <label>Subject:</label>
            <input type="text" name="subject" required>

            <label>Message:</label>
            <textarea name="message" rows="1" required></textarea>

            <!-- ✅ New Field -->
        <label>Interview Date:</label>
        <input type="date" name="interview_date" >

            <button type="submit">Send Email</button>
            <button type="button" onclick="closeMailForm()">Cancel</button>
        </form>
    </div>

    <script>
    function openMailForm(email, name) {
        document.getElementById('emailModal').style.display = 'block';
        document.getElementById('to_email').value = email;
        document.getElementById('to_display').value = name + ' <' + email + '>';
    }

    function closeMailForm() {
        document.getElementById('emailModal').style.display = 'none';
    }
    </script>


<!-- custom js file link -->
 <script src="js/script.js"></script>
</body>
</html>
