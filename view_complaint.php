<?php
session_start();
include 'support/db.php'; // Include your database connection file

// Retrieve complaint details based on the ID from GET parameter
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = "SELECT * FROM complaints WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();

// Update phone number if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_phone'])) {
    $new_phone_number = $_POST['phone_number'];
    $update_query = "UPDATE complaints SET phone_number = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_phone_number, $id);
    $update_stmt->execute();
    $update_stmt->close();

    // Refresh the complaint data after update
    $stmt->execute();
    $complaint = $stmt->get_result()->fetch_assoc();
}

// Insert feedback if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $feedback = $_POST['feedback'];
    $feedback_query = "UPDATE complaints SET feedback = ? WHERE id = ?";
    $feedback_stmt = $conn->prepare($feedback_query);
    $feedback_stmt->bind_param("si", $feedback, $id);
    $feedback_stmt->execute();
    $feedback_stmt->close();

    // Refresh the complaint data after update
    $stmt->execute();
    $complaint = $stmt->get_result()->fetch_assoc();
}

// Insert feedback if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $feedback = $_POST['feedback'];
    $feedback_query = "UPDATE complaints SET feedback = ? WHERE id = ?";
    $feedback_stmt = $conn->prepare($feedback_query);
    $feedback_stmt->bind_param("si", $feedback, $id);
    $feedback_stmt->execute();
    $feedback_stmt->close();

    // Refresh the complaint data after update
    $query = "SELECT * FROM complaints WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $complaint = $result->fetch_assoc();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Complaint Details</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="css/style1.css">
</head>

<body style="background-image: url('images/citybg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

    <h1>Complaint Details</h1>

    <?php if ($complaint): ?>
        <!-- First Table: Basic Information -->
        <h5>Personal Information</h5>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($complaint['name']); ?></td>
                <td><?php echo htmlspecialchars($complaint['email']); ?></td>
                <td><?php echo htmlspecialchars($complaint['phone_number']); ?></td>
            </tr>
        </table>


        <br> <!-- Space between tables -->

        <!-- Second Table: Detailed Information -->
        <table border="1">
            <tr>
                <th>NRIC</th>
                <td><?php echo htmlspecialchars($complaint['nric']); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($complaint['subject']); ?></td>
            </tr>
            <tr>
                <th>Details</th>
                <td><?php echo htmlspecialchars($complaint['details']); ?></td>
            </tr>
            <tr>
                <th>Time Created</th>
                <td><?php echo htmlspecialchars($complaint['time_created']); ?></td>
            </tr>
            <tr>
                <th>Department</th>
                <td><?php echo htmlspecialchars($complaint['department']); ?></td>
            </tr>
            <tr>
                <th>Time Process</th>
                <td><?php echo htmlspecialchars($complaint['time_process']); ?></td>
            </tr>
            <tr>
                <th>Answer</th>
                <td><?php echo htmlspecialchars($complaint['answer']); ?></td>
            </tr>
            <tr>
                <th>Time Resolved</th>
                <td><?php echo htmlspecialchars($complaint['time_resolve']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($complaint['status']); ?></td>
            </tr>
            <tr>
                <th>Figures</th>
                <td>
                    <div style="display: flex; gap: 10px;">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($complaint['figure1']); ?>" width="150"
                            height="150" style="object-fit: cover;" alt="Figure 1" />
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($complaint['figure2']); ?>" width="150"
                            height="150" style="object-fit: cover;" alt="Figure 2" />
                    </div>
                </td>
            </tr>
        </table>

        <h5>Submit Feedback</h5>
        <form method="POST" action="">
            <table border="1">
                <tr>
                    <td>Feedback:</td>
                    <td><textarea name="feedback" rows="4" cols="50" required></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit" name="submit_feedback">Submit Feedback</button>
                    </td>
                </tr>
            </table>
        </form>
        <button onclick="window.location.href='index.php'">Go to Dashboard</button>


    <?php else: ?>
        <p>No complaint details found.</p>
    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>