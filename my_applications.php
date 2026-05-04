<?php
include 'config.php';
include 'jobseekernav.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || $_SESSION['user_type'] !== 'job_seeker') {
    header("Location: login.php");
    exit();
}

// Get the seeker's ID from job_seekers table
$seeker_sql = "SELECT seeker_id FROM job_seekers WHERE user_id = ?";
$seeker_stmt = mysqli_prepare($conn, $seeker_sql);
mysqli_stmt_bind_param($seeker_stmt, "i", $user_id);
mysqli_stmt_execute($seeker_stmt);
$seeker_result = mysqli_stmt_get_result($seeker_stmt);

if ($row = mysqli_fetch_assoc($seeker_result)) {
    $seeker_id = $row['seeker_id'];
} else {
    echo "Seeker profile not found.";
    exit();
}

// Fetch jobs the user has applied to along with application status
$sql = "SELECT j.job_id, j.job_title, j.image, j.location, j.company_name, a.status
        FROM applications a
        JOIN jobs j ON a.job_id = j.job_id
        WHERE a.seeker_id = ?
        ORDER BY a.application_id DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $seeker_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <h1 class="centered-blue">Jobs You Have Applied To</h1>
    <div class="application-container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($job = mysqli_fetch_assoc($result)) {
                $statusClass = strtolower($job['status']); // pending, accepted, rejected

                /* Secure and verify image path old one
                $imageFileName = basename($job['image']);
                $imagePath = "uploads/" . $imageFileName;
                */
                
                //new one for image
                $imagePath = $job['image'];
                
                echo '<div class="job-card">';
                //old one if (!empty($imageFileName) && file_exists($imagePath)) {
                //new one for image
                if(!empty($imagePath) && file_exists($imagePath)){
                    echo '<img src="' . htmlspecialchars($imagePath) . '" alt="Job Image">';
                } else {
                    echo '<p><em>No image available.</em></p>';
                }
                echo '<h3>' . htmlspecialchars($job['job_title']) . '</h3>';
                echo '<p><strong>Company:</strong> ' . htmlspecialchars($job['company_name']) . '</p>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($job['location']) . '</p>';
                echo '<p class="status ' . $statusClass . '">' . ucfirst($statusClass) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p class="no-applications">You have not applied for any jobs yet.</p>';
        }
        ?>
    </div>
</body>
</html>
S