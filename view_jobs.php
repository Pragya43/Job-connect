<?php
include 'config.php';
include 'jobseekernav.php';

// Always start the session at the top
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- 1. AUTHENTICATION & INITIAL SETUP ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'job_seeker') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$seeker_id = null;
$hasCV = false;

// --- 2. GET SEEKER DETAILS (SECURELY) ---
// We must first check if the user has a job_seeker profile.
$stmt = $conn->prepare("SELECT seeker_id FROM job_seekers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $seeker = $result->fetch_assoc();
    $seeker_id = $seeker['seeker_id'];
}
$stmt->close();


// --- 3. CHECK FOR CV (SECURELY) ---
// Only proceed to check for a CV if we found a seeker profile
if ($seeker_id) {
    $stmt = $conn->prepare("SELECT cv_id FROM cv WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cvResult = $stmt->get_result();
    if ($cvResult->num_rows > 0) {
        $hasCV = true;
    }
    $stmt->close();
}

// --- 4. PREPARE THE JOB SEARCH QUERY (SECURELY) ---
$searchTerm = $_GET['search'] ?? '';
$sql = "SELECT * FROM jobs WHERE status = 'active'";
$params = [];
$types = '';

if (!empty(trim($searchTerm))) {
    $sql .= " AND (job_title LIKE ? OR company_name LIKE ? OR location LIKE ?)";
    $likeSearchTerm = "%" . $searchTerm . "%";
    // Add the search term three times to the parameters array
    array_push($params, $likeSearchTerm, $likeSearchTerm, $likeSearchTerm);
    $types .= 'sss';
}
$sql .= " ORDER BY job_id DESC";

$jobsStmt = $conn->prepare($sql);
if (!empty($params)) {
    // Use the splat operator (...) to bind a variable number of parameters
    $jobsStmt->bind_param($types, ...$params);
}
$jobsStmt->execute();
$jobsResult = $jobsStmt->get_result();

// --- 5. PREPARE THE APPLICATION CHECK QUERY (FOR EFFICIENCY) ---
// We prepare this once, before the loop, to reuse it.
if ($seeker_id) {
    $appCheckStmt = $conn->prepare("SELECT application_id FROM applications WHERE seeker_id = ? AND job_id = ?");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Available Jobs</title>
    <link rel="stylesheet" href="style1.css" />
</head>
<body>

<h2>All Available Jobs</h2>

<!-- Search Form -->
<form method="GET" action="" style="text-align: center; margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Search jobs by title, company, or location"
           value="<?php echo htmlspecialchars($searchTerm); ?>" 
           style="width: 60%; padding: 8px;" />
    <button type="submit" style="padding: 8px 16px;">Search</button>
</form>

<div class="job-container">

<?php
if ($jobsResult->num_rows === 0) {
    echo "<p>No active jobs found matching your criteria.</p>";
} else {
    while ($row = $jobsResult->fetch_assoc()) {
        $expiredDate = $row['expired_date'];
        $isExpired = !empty($expiredDate) && (strtotime($expiredDate) < strtotime(date('Y-m-d')));
?>
    <div class="job-card">
        <div class="job-title"><?php echo htmlspecialchars($row['job_title']); ?></div>

        <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Company Logo" class="job-image">
        <?php endif; ?>

        <div class="job-info">
            <strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?><br>
            <strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?><br>
            <!-- Add other job details you want to show here -->
            <strong>Required Skills:</strong> <?php echo nl2br(htmlspecialchars($row['required_skills'])); ?><br>
            <strong>Description:</strong>
            <div class="job-description-box">
                <?php echo nl2br(htmlspecialchars($row['job_description'])); ?>
            </div>
        </div>

        <!-- This is the button logic block -->
        <div class="apply-section" style="margin-top:15px;">
            <?php
            if ($isExpired) {
                echo '<button class="apply-btn disabled" disabled>Job Expired</button>';
            } elseif (!$seeker_id) {
                echo '<a href="updateprofile.php" class="apply-btn disabled" style="text-decoration:none; display:inline-block; text-align:center;">Complete Profile to Apply</a>';
            } elseif (!$hasCV) {
                echo '<a href="updateprofile.php" class="apply-btn disabled" style="text-decoration:none; display:inline-block; text-align:center;">Upload CV to Apply</a>';
            } else {
                // Check if already applied
                $jobId = (int)$row['job_id'];
                $appCheckStmt->bind_param("ii", $seeker_id, $jobId);
                $appCheckStmt->execute();
                $appResult = $appCheckStmt->get_result();

                if ($appResult->num_rows > 0) {
                    echo '<button class="apply-btn disabled" disabled>Already Applied</button>';
                } else {
                    // This is the correct "Apply with AI" button
            ?>
                    <form method="POST" action="process_application.php">
                        <input type="hidden" name="job_id" value="<?php echo $jobId; ?>">
                        <button class="apply-btn" type="submit">Apply & Calculate Match</button>
                    </form>
            <?php
                }
            }
            ?>
        </div>
    </div>
<?php
    }
}
$jobsStmt->close();
if (isset($appCheckStmt)) {
    $appCheckStmt->close();
}
$conn->close();
?>
</div>

</body>
</html>