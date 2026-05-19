<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get the start of the current week (Monday) and the end of the current week (Sunday)
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

// Fetch tickets from the complaints table for the current week
$sql = "SELECT * FROM complaints WHERE DATE(time_created) BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $startOfWeek, $endOfWeek);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Tickets</title>
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
            <h2>New Tickets (This Week)</h2>
            <div class="table-container">
                <table class="table table-striped" id="newTicketsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>NRIC</th>
                            <th>Subject</th>
                            <th>Details</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Time Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nric']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['details']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['time_created']) . "</td>";
                                echo "<td>";
                                echo "<a href='edit_ticket.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a> ";
                                echo "<a href='delete_ticket.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No tickets found for today</td></tr>";
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
        // Initialize DataTable for newTicketsTable
        const newTicketsTable = new simpleDatatables.DataTable("#newTicketsTable");
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
