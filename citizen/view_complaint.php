<?php
session_start();
include '../support/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
    header("Location: ../login.php");
    exit();
}

// Retrieve complaint details based on the ID from GET parameter
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = "SELECT * FROM complaints WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $feedback = trim($_POST['feedback']); // Get feedback input and sanitize it

    if (!empty($feedback)) {
        // Check if there's already feedback for this ticket_id
        $stmt = $conn->prepare("SELECT * FROM feedback WHERE ticket_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingFeedback = $result->fetch_assoc();

        if ($existingFeedback) {
            // Update feedback2 if feedback1 is already filled
            if (!empty($existingFeedback['feedback1']) && empty($existingFeedback['feedback2'])) {
                $stmt = $conn->prepare("UPDATE feedback SET feedback2 = ?, timedate = NOW() WHERE ticket_id = ?");
                $stmt->bind_param("si", $feedback, $id);
            } else {
                echo "<script>alert('You have already provided two feedbacks for this complaint.');</script>";
                $stmt->close();
                $conn->close();
                exit();
            }
        } else {
            // Insert new feedback in feedback1
            $stmt = $conn->prepare("INSERT INTO feedback (ticket_id, feedback1) VALUES (?, ?)");
            $stmt->bind_param("is", $id, $feedback);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Feedback submitted successfully!'); window.location.href='view_complaint.php?id=$id';</script>";
        } else {
            echo "<script>alert('Error submitting feedback: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Feedback cannot be empty.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Complaint Details</title>
    <link rel="stylesheet" href="../css/style1.css">
</head>

<body
    style="background-image: url('../images/citybg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

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
        <h5>Details Complaint</h5>
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
                <th>Category</th>
                <td><?php echo htmlspecialchars($complaint['department']); ?></td>
            </tr>
            <tr>
                <th>Location</th>
                <td>
                    <?php
                    $location = $complaint['location'];
                    // Extract the latitude and longitude
                    preg_match('/Lat: ([\d\.\-]+), Long: ([\d\.\-]+)/', $location, $matches);
                    if (count($matches) === 3) {
                        $latitude = $matches[1];
                        $longitude = $matches[2];
                        $mapLink = "https://www.google.com/maps?q={$latitude},{$longitude}";
                        echo htmlspecialchars($location) . " - <a href=\"" . htmlspecialchars($mapLink) . "\" target=\"_blank\">View on Google Maps</a>";
                    } else {
                        echo htmlspecialchars($location);
                    }
                    ?>

                </td>
            </tr>
            <tr>
                <th>State</th>
                <td><?php echo htmlspecialchars($complaint['state']); ?></td>
            </tr>
            <tr>
                <th>Municipality</th>
                <td><?php echo htmlspecialchars($complaint['municipality']); ?></td>
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
                <th>Figure 1</th>
                <td>
                    <?php if (!empty($complaint['figure1'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($complaint['figure1']); ?>" width="200"
                            alt="Figure 1">
                    <?php else: ?>
                        <p>No figure uploaded.</p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Figure 2</th>
                <td>
                    <?php if (!empty($complaint['figure2'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($complaint['figure2']); ?>" width="200"
                            alt="Figure 2">
                    <?php else: ?>
                        <p>No figure uploaded.</p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Figure 3</th>
                <td>
                    <?php if (!empty($complaint['figure3'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($complaint['figure3']); ?>" width="200"
                            alt="Figure 2">
                    <?php else: ?>
                        <p>No figure uploaded.</p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Figure 4</th>
                <td>
                    <?php if (!empty($complaint['figure4'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($complaint['figure4']); ?>" width="200"
                            alt="Figure 2">
                    <?php else: ?>
                        <p>No figure uploaded.</p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <table border="1">
            <?php $feedbackQuery = "SELECT * FROM feedback WHERE ticket_id = ?";
            $stmt = $conn->prepare($feedbackQuery);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $feedbackResult = $stmt->get_result();
            $feedback = $feedbackResult->fetch_assoc();

            if ($feedback) {
                echo "<h5>Feedback</h5>";
                echo "<table border='1'>
            <tr><th>Feedback 1</th><td>" . htmlspecialchars($feedback['feedback1']) . "</td></tr>
            <tr><th>Feedback 2</th><td>" . htmlspecialchars($feedback['feedback2']) . "</td></tr>
            <tr><th>Time Submitted</th><td>" . htmlspecialchars($feedback['timedate']) . "</td></tr>
          </table>";
            } else {
                echo "<p>No feedback submitted yet.</p>";
            }
            $stmt->close();
            ?>
        </table>

        <!-- Feedback Form -->
        <h5>Submit Feedback</h5>
        <form method="POST" action="">
            <table border="1">
                <tr>
                    <td>Feedback:</td>
                    <td>
                        <textarea name="feedback" rows="4" cols="50" <?php echo ($complaint['status'] === 'open' || $complaint['status'] === 'pending') ? 'disabled' : ''; ?>></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit" name="submit_feedback" <?php echo ($complaint['status'] === 'open' || $complaint['status'] === 'pending') ? 'disabled' : ''; ?>>
                            Submit Feedback
                        </button>
                    </td>
                </tr>
            </table>
        </form>
        <button onclick="window.location.href='index.php'">Go to Dashboard</button>
    <?php else: ?>
        <p>No complaint details found.</p>
    <?php endif; ?>

    <?php

    $conn->close();
    ?>
</body>

</html>