<?php
include 'config.php';
include 'jobseekernav.php';

// Check if user logged in and is job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'job_seeker') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch correct job seeker data
$sql = "SELECT u.name, u.email, js.phone_number, js.location, js.skills, js.experience, js.bio, cv.file_path
        FROM users u
        LEFT JOIN job_seekers js ON u.user_id = js.user_id
        LEFT JOIN cv ON u.user_id = cv.user_id
        WHERE u.user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("SQL prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "❌ Job seeker profile not found.";
    exit();
}

$seeker = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Job Seeker Profile</title>
    <link rel="stylesheet" href="style1.css" />
</head>
<body>

<?php
// Display success message if set
if (isset($_SESSION['success_message'])) {
    echo '<div class="success-message">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}
?>

<h2>My Profile</h2>

<div class="job-card">
    <div class="job-info">
        <strong>Name:</strong> <?php echo htmlspecialchars($seeker['name']); ?><br>
        <strong>Email:</strong> <?php echo htmlspecialchars($seeker['email']); ?><br>
        <strong>Phone Number:</strong> <?php echo htmlspecialchars($seeker['phone_number'] ?? 'Not provided'); ?><br>
        <strong>Location:</strong> <?php echo htmlspecialchars($seeker['location'] ?? 'Not provided'); ?><br>
        <strong>Skills:</strong><br>
        <?php echo nl2br(htmlspecialchars($seeker['skills'] ?? 'Not provided')); ?><br>
        <strong>Experience:</strong><br>
        <?php echo nl2br(htmlspecialchars($seeker['experience'] ?? 'Not provided')); ?><br>
        <strong>Bio:</strong><br>
        <?php echo nl2br(htmlspecialchars($seeker['bio'] ?? 'Not provided')); ?><br><br>

        <?php if (!empty($seeker['file_path'])): ?>
            <strong>CV:</strong> <a href="<?php echo htmlspecialchars($seeker['file_path']); ?>" target="_blank">View CV</a>
        <?php else: ?>
            <strong>CV:</strong> Not uploaded yet.
        <?php endif; ?>
    </div>

    <div style="margin-top: 15px;">
        <a href="updateprofile.php" class="apply-btn">Update Profile</a>
    </div>
</div>

</body>
</html>
