<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an officer
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

// Get the logged-in officer's email from the session
$email = $_SESSION['email'];

// Fetch the municipality associated with the logged-in officer
$sql_officer = "SELECT municipal FROM officer WHERE email = ?";
$stmt_officer = $conn->prepare($sql_officer);
$stmt_officer->bind_param("s", $email);
$stmt_officer->execute();
$result_officer = $stmt_officer->get_result();

if ($result_officer->num_rows > 0) {
    // Get the municipality for the logged-in officer
    $officer = $result_officer->fetch_assoc();
    $officer_municipality = $officer['municipal'];  // Officer's municipality
} else {
    die("Officer not found.");
}

// Get today's date in YYYY-MM-DD format
$today = date('Y-m-d');

// Fetch complaints that match the officer's municipality and today's date
$sql_complaints = "SELECT * FROM complaints WHERE DATE(time_created) = ? AND municipality = ?";
$stmt_complaints = $conn->prepare($sql_complaints);
$stmt_complaints->bind_param("ss", $today, $officer_municipality);  // Bind today's date and officer's municipality
$stmt_complaints->execute();
$result_complaints = $stmt_complaints->get_result();
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
            <h2>New Tickets (Today)</h2>
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
                        if ($result_complaints->num_rows > 0) {
                            while ($row = $result_complaints->fetch_assoc()) {
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
$stmt_complaints->close();
$stmt_officer->close();
$conn->close();
?>
