<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if employer already submitted info
$check = $conn->prepare("SELECT * FROM employers WHERE user_id =?");
$check->bind_param("i", $user_id);
$check->execute();
$result = $check->get_result();

$alreadySubmitted = false;
$logoPath = "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['employer_id'] = $row['employer_id'];
    $alreadySubmitted = true;
    $logoPath = $row['company_logo'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($alreadySubmitted) {
        $error = "<span style='font-size:15px;'>You have already submitted your company info. Please proceed to <a href='added_job.php'>Add Job</a></span>";
    } else {
        $company_name = trim($_POST['company_name']);
        $company_description = trim($_POST['company_description']);

        if (!empty($company_name) && !empty($company_description) && isset($_FILES['company_logo'])) {
            $targetDir = "uploads";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $logoName = basename($_FILES["company_logo"]["name"]);
            $targetFile = $targetDir . time() . "_" . $logoName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES["company_logo"]["tmp_name"], $targetFile)) {
                    // Insert into database with logo
                    $stmt = $conn->prepare("INSERT INTO employers(user_id, company_name, company_description, company_logo) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isss", $user_id, $company_name, $company_description, $targetFile);

                    if ($stmt->execute()) {
                        $_SESSION['employer_id'] = $stmt->insert_id;
                        header("Location: added_job.php");
                        exit;
                    } else {
                        $error = "Failed to save company details.";
                    }
                } else {
                    $error = "Failed to upload logo.";
                }
            } else {
                $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        } else {
            $error = "All fields including logo are required.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<header class="header">
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
            <p> Username : <span><?php echo $_SESSION['employer_name']; ?></span></p>
            <a href="logout.php" class="delete-btn">Logout</a>
        </div>
    </div>
</header>

<section class="add-detail">
    <h2 class="title">Enter Company Information</h2>

    <form action="employer_page.php" method="POST" enctype="multipart/form-data">
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <label for="company_name">Company Name</label>
        <input type="text" name="company_name" id="company_name" required>

        <label for="company_description">Company Description</label>
        <textarea name="company_description" id="company_description" rows="5" required></textarea>

        <label for="company_logo">Company Logo</label>
        <input type="file" name="company_logo" id="company_logo" accept="image/*" required>

        <?php if ($alreadySubmitted): ?>
    <div style="margin-top: 20px;">
        <?php if (!empty($logoPath)): ?>
            <div style="margin-bottom: 10px;">
                <p><strong>Uploaded Logo:</strong></p>
                <img src="<?php echo $logoPath; ?>" alt="Company Logo" style="max-width: 150px;">
            </div>
        <?php endif; ?>
        <p style="color: green;">Your company information is already submitted. Go to <a href="added_job.php">Add Job</a></p>
    </div>
<?php endif; ?>

        <input type="submit" class="btn" value="Submit and Continue">
    </form>

    

</section>

<script src="js/script.js"></script>
</body>
</html>
