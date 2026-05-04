<?php
include 'config.php'; // DB connection
include 'jobseekernav.php'; // include navbar

// Ensure user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'job_seeker') {
    header("Location: login.php");
    exit();
}

// Fetch jobs from the database
$sql = "SELECT job_id, job_title, company_name, experience_required, required_skills, image FROM jobs ORDER BY job_title ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Job Seeker Dashboard</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>

<h1 style="text-align: center; color: #1a0f41;">Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h1>

<div class="job-grid">
<?php if ($result && mysqli_num_rows($result) > 0): ?>
    <?php while($job = mysqli_fetch_assoc($result)): ?>
        <div class="job-card">
            <?php if (!empty($job['image']) && file_exists($job['image'])): ?>
                <img src="<?= htmlspecialchars($job['image']) ?>" alt="Job Image" class="job-image" />
            <?php else: ?>
                <div class="job-image" style="display:flex; align-items:center; justify-content:center; background:#eee; color:#aaa;">
                    No Image
                </div>
            <?php endif; ?>
            <div class="job-title"><strong>Job:</strong> <?= htmlspecialchars($job['job_title']) ?></div>
            <div class="job-info"><strong>Company:</strong> <?= htmlspecialchars($job['company_name']) ?></div>
            <div class="job-info"><strong>Experience:</strong> <?= htmlspecialchars($job['experience_required']) ?></div>
            <div class="job-info"><strong>Skills:</strong> <?= htmlspecialchars($job['required_skills']) ?></div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center; color:#888;">No jobs available at the moment.</p>
<?php endif; ?>
</div>

</body>
</html>
