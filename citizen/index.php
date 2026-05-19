<?php
session_start();
include '../support/db.php'; // Include database connection

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'citizen') {
    header("Location: ../login.php");
    exit();
}

// Fetch logged-in user information
$user_id = $_SESSION['user_id'];
$query = "SELECT id, name, nric, email, phone_number FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container-main {
            display: flex;
            flex-direction: row;
            gap: 20px;
            margin-top: 20px;
        }
        .right-section {
            flex: 0.3;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .left-section {
            flex: 0.7;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .features {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .card {
            flex: 1;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            background-color: #ffffff;
        }
        .btn-submit {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #00796B;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .btn-submit:hover {
            background-color: #005b4a;
        }
    </style>
</head>
<body style="background-image: url('../images/citybg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

    <div class="container mt-4">
        <h2 class="text-center">Citizen Dashboard</h2>
        <div class="text-right mb-3">
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="container-main">
            <!-- Right Section: Logged-In User Information Table -->
            <div class="right-section">
                <h4>Your Information</h4>
                <?php if ($user): ?>
                    <table class="table table-sm table-striped">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                            </tr>
                            <tr>
                                <th>NRIC</th>
                                <td><?php echo htmlspecialchars($user['nric']); ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No user information found.</p>
                <?php endif; ?>
            </div>

            <!-- Left Section: Complaint Cards -->
            <div class="left-section">
                <section class="container">
                    <h2 class="header">Complaint</h2>
                    <div class="features">
                        <div class="card left">
                            <span><i class="ri-handshake-line"></i></span>
                            <h4>Submit Your Complaint</h4>
                            <p>
                                If you have an issue that needs addressing, we’re here to help! Click the button below to submit your complaint easily and quickly.
                            </p>
                            <a href="submit_complaint.php" class="btn-submit">Submit Complaint</a>
                        </div>
                        
                        <div class="card right">
                            <span><i class="ri-handshake-line"></i></span>
                            <h4>Track Your Complaint</h4>
                            <p>
                                Stay informed about the status of your complaint. Click below to view your submission and its progress.
                            </p>
                            <a href="track_complaint.php" class="btn-submit">Track Complaint</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
