<?php
session_start();
include '../support/db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
    header("Location: ../login.php");
    exit();
}

// Get NRIC from the session
$nric = $_SESSION['nric'];

// Retrieve complaints based on NRIC
$query = "SELECT id, name, time_created, status FROM complaints WHERE nric = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nric);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Complaints</title>
    <link rel="stylesheet" href="../css/style3.css">
</head>
<body style="background-image: url('../images/citybg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container">
        <h1>Track Your Complaints</h1>

        <?php if ($result->num_rows > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Time Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['time_created']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <a href="view_complaint.php?id=<?php echo $row['id']; ?>">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No complaints found for your NRIC.</p>
        <?php endif; ?>

        <?php
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
