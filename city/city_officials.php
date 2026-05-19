<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch logged-in officer's details
$email = $_SESSION['email'];
$sql = "SELECT * FROM officer WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$officer = $result->fetch_assoc();
$stmt->close();

// Check if the officer's record exists
if (!$officer) {
    echo "No officer data found. Please contact the administrator.";
    exit();
}

// Fetch the officer's profile picture
$profile_pic_sql = "SELECT picture FROM profile_pictures WHERE off_id = ?";
$stmt = $conn->prepare($profile_pic_sql);
$stmt->bind_param("i", $officer['id']);
$stmt->execute();
$stmt->bind_result($profile_picture_path);
$stmt->fetch();
$stmt->close();

// Use the stored profile picture if available; otherwise, use the default image
$profile_picture_src = $profile_picture_path ? htmlspecialchars($profile_picture_path) : '../pic/default.webp';

// Handle profile picture upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_picture'])) {
    if (is_uploaded_file($_FILES['profile_picture']['tmp_name'])) {
        $upload_dir = '../pic/';
        $file_name = uniqid('profile_', true) . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $file_path = $upload_dir . $file_name;

        // Move the uploaded file to the destination folder
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
            // Check if the officer already has a profile picture
            $check_pic_sql = "SELECT id FROM profile_pictures WHERE off_id = ?";
            $stmt = $conn->prepare($check_pic_sql);
            $stmt->bind_param("i", $officer['id']);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Update the existing profile picture path
                $update_pic_sql = "UPDATE profile_pictures SET picture = ? WHERE off_id = ?";
                $stmt = $conn->prepare($update_pic_sql);
                $stmt->bind_param("si", $file_path, $officer['id']);
            } else {
                // Insert a new profile picture path
                $insert_pic_sql = "INSERT INTO profile_pictures (off_id, picture) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_pic_sql);
                $stmt->bind_param("is", $officer['id'], $file_path);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Profile picture updated successfully!'); window.location.href = 'city_officials.php';</script>";
            } else {
                echo "<script>alert('Error updating profile picture.');</script>";
            }
            
            $stmt->close();
        } else {
            echo "<script>alert('Error uploading file. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .container {
            margin-top: 20px;
            max-width: 600px;
        }
        .profile-pic {
            display: block;
            margin: 0 auto 20px;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <?php include 'head.php'; ?>

    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2>Your Profile</h2>

            <!-- Profile Picture -->
            <img src="<?php echo $profile_picture_src; ?>" alt="Profile Picture" class="profile-pic">

            <!-- Profile Picture Upload Form -->
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Update Profile Picture</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>

            <!-- Officer Details -->
            <table class="table table-bordered mt-4">
                <tr>
                    <th>ID</th>
                    <td><?php echo htmlspecialchars($officer['id']); ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($officer['name']); ?></td>
                </tr>
                <tr>
                    <th>NRIC</th>
                    <td><?php echo htmlspecialchars($officer['nric']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($officer['email']); ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?php echo htmlspecialchars($officer['phone_number']); ?></td>
                </tr>
                <tr>
                    <th>Department ID</th>
                    <td><?php echo htmlspecialchars($officer['department_id'] ? $officer['department_id'] : 'N/A'); ?></td>
                </tr>
                <tr>
                    <th>Municiple</th>
                    <td><?php echo htmlspecialchars($officer['municipal'] ? $officer['municipal'] : 'N/A'); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?php echo htmlspecialchars($officer['status'] ? $officer['status'] : 'N/A'); ?></td>
                </tr>
                <tr>
                    <th>Actions</th>
                    <td>
                        <a href='edit_user.php?id=<?php echo $officer['id']; ?>' class='btn btn-primary'>Edit</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
