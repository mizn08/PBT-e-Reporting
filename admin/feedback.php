<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$sql = "
    SELECT f.ticket_id, f.feedback1, f.feedback2, c.id AS complaint_id, c.name, c.email, c.phone_number, 
           c.nric, c.subject, c.details, c.department, c.status, c.time_created, c.answer 
    FROM feedback f
    JOIN complaints c ON f.ticket_id = c.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedbacks</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .container {
            margin-top: 20px;
        }
        .table-container {
            margin-top: 20px;
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
            <h2>Feedback and Answered Complaints</h2>
            <div class="table-container">
                <table class="table table-striped" id="feedbackTable">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Feedback 1</th>
                            <th>Feedback 2</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['ticket_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['feedback1']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['feedback2']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>";
                                echo "<a href='edit_ticket.php?id=" . $row['ticket_id'] . "' class='btn btn-sm btn-success'>View</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No feedback or answered complaints found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script>
        // Initialize DataTable for feedbackTable
        const feedbackTable = new simpleDatatables.DataTable("#feedbackTable");
    </script>
</body>
</html>

<?php
$conn->close();
?>
