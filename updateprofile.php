<?php
include 'config.php';
include 'jobseekernav.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || $_SESSION['user_type'] !== 'job_seeker') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number'] ?? '');
    $location = mysqli_real_escape_string($conn, $_POST['location'] ?? '');
    $skills = mysqli_real_escape_string($conn, $_POST['skills'] ?? '');
    $experience = mysqli_real_escape_string($conn, $_POST['experience'] ?? '');
    $bio = mysqli_real_escape_string($conn, $_POST['bio'] ?? '');

    // Ensure job_seeker row exists
    $checkSQL = "SELECT * FROM job_seekers WHERE user_id = $user_id";
    $checkResult = mysqli_query($conn, $checkSQL);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        $updateSQL = "UPDATE job_seekers SET 
            phone_number = '$phone_number',
            location = '$location',
            skills = '$skills',
            experience = '$experience',
            bio = '$bio'
            WHERE user_id = $user_id";
        mysqli_query($conn, $updateSQL);
    } else {
        $insertSQL = "INSERT INTO job_seekers (user_id, phone_number, location, skills, experience, bio)
                      VALUES ($user_id, '$phone_number', '$location', '$skills', '$experience', '$bio')";
        mysqli_query($conn, $insertSQL);
    }

    // Handle CV upload
    if (!empty($_FILES['cv_file']['name'])) {
        $uploadDir = 'uploads/cv/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['cv_file']['name']);
        $targetFilePath = $uploadDir . $fileName;

        $allowedExtensions = ['pdf', 'doc', 'docx'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['upload_error'] = "Invalid file type. Only PDF, DOC, DOCX allowed.";
            header("Location: updateprofile.php");
            exit();
        }

        if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $targetFilePath)) {
            $cvCheckSQL = "SELECT * FROM cv WHERE user_id = $user_id";
            $cvResult = mysqli_query($conn, $cvCheckSQL);

            if ($cvResult && mysqli_num_rows($cvResult) > 0) {
                $updateCVSQL = "UPDATE cv SET file_path = '$targetFilePath' WHERE user_id = $user_id";
                mysqli_query($conn, $updateCVSQL);
            } else {
                $insertCVSQL = "INSERT INTO cv (user_id, file_path) VALUES ($user_id, '$targetFilePath')";
                mysqli_query($conn, $insertCVSQL);
            }
        } else {
            $_SESSION['upload_error'] = "Failed to upload CV.";
            header("Location: updateprofile.php");
            exit();
        }
    }

    $_SESSION['success_message'] = "Your profile has been updated.";
    header("Location: profile.php");
    exit();
}

// Fetch info to pre-fill
$userSQL = "SELECT name, email FROM users WHERE user_id = $user_id";
$userResult = mysqli_query($conn, $userSQL);
$user = mysqli_fetch_assoc($userResult);

$seekerSQL = "SELECT phone_number, location, skills, experience, bio FROM job_seekers WHERE user_id = $user_id";
$seekerResult = mysqli_query($conn, $seekerSQL);
$seeker = mysqli_fetch_assoc($seekerResult) ?? ['phone_number' => '', 'location' => '', 'skills' => '', 'experience' => '', 'bio' => ''];

$cvSQL = "SELECT file_path FROM cv WHERE user_id = $user_id";
$cvResult = mysqli_query($conn, $cvSQL);
$cv = mysqli_fetch_assoc($cvResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Update Profile</title>  
    <link rel="stylesheet" href="style1.css" />
</head>
<body>
<div class="container">
<div class="profile-box">
<h2>Update Profile, <?= htmlspecialchars($user['name']) ?></h2>

<?php
if (isset($_SESSION['upload_error'])) {
    echo '<div class="error-message">' . htmlspecialchars($_SESSION['upload_error']) . '</div>';
    unset($_SESSION['upload_error']);
}
?>

<form method="post" enctype="multipart/form-data">
   <p><label><strong>Phone Number:</strong></label><br />
    <input type="text" name="phone_number" value="<?= htmlspecialchars($seeker['phone_number']) ?>" /></p>

   <p><label><strong>Location:</strong></label><br />
    <input type="text" name="location" value="<?= htmlspecialchars($seeker['location']) ?>" /></p>

   <p><label><strong>Skills:</strong></label><br />
    <textarea name="skills" rows="4"><?= htmlspecialchars($seeker['skills']) ?></textarea></p>

   <p><label><strong>Experience:</strong></label><br />
    <textarea name="experience" rows="4"><?= htmlspecialchars($seeker['experience']) ?></textarea></p>

    <p><label><strong>Bio:</strong></label><br />
    <textarea name="bio" rows="4"><?= htmlspecialchars($seeker['bio']) ?></textarea></p>

   <p><label><strong>Upload / Update CV (PDF/DOC/DOCX):</strong></label><br />
    <input type="file" name="cv_file" accept=".pdf,.doc,.docx" /></p>

    <?php if (!empty($cv['file_path'])): ?>
        <p>Current CV: <a href="<?= htmlspecialchars($cv['file_path']) ?>" target="_blank">View CV</a></p>
    <?php else: ?>
        <p>No CV uploaded yet.</p>
    <?php endif; ?>

    <button type="submit" class="update-btn">Update</button>
</form>

<a href="profile.php" class="back-btn">Back to Profile</a>
</div>
</div>
</body>
</html>
