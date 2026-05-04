<?php
include 'config.php';
include 'adminnav.php';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
   // $delete_query = "DELETE FROM users WHERE user_id = $delete_id";
    //mysqli_query($conn, $delete_query);
     $blacklist_query = "UPDATE users SET blacklist = 1 WHERE user_id = $delete_id";
    mysqli_query($conn, $blacklist_query);
    header("Location: manage_users.php");
    exit();
}

// Fetch all users (excluding already blacklisted users, optional)
// $result = mysqli_query($conn, "SELECT * FROM users WHERE blacklist = 0 ORDER BY user_id DESC");
// Fetch all users
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY user_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin Panel</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel ="stylesheet"href ="admin.css" >
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white; /* Full white background */
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 20px;
            color: #004080;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ccc;
        }
        th {
            background-color: #e6f0ff;
        }
        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Users</h1>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo $row['user_type']; ?></td>
                <td><?php echo $row['phone_number']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td><?php echo $row['blacklist'] == 1 ? 'Blacklisted' : 'Active' ; ?></td>
                <td>
                    <?php if($row['blacklist'] == 0): ?>
                    <a href="manage_users.php?delete_id=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                        <button class="delete-btn">Delete</button>
                    </a>
                    <?php else: ?>
                        <span style="color: red;">Blacklisted</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
