<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch officers with permit = 'allow'
$sql_allow = "SELECT * FROM officer WHERE permit = 'allow'";
$result_allow = $conn->query($sql_allow);

// Fetch officers with permit = 'restrict'
$sql_restrict = "SELECT * FROM officer WHERE permit = 'access'";
$result_restrict = $conn->query($sql_restrict);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'] === 'active' ? 'active' : 'inactive';

    $update_sql = "UPDATE officer SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: city_officials.php");
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Officials</title>
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
            <h2>City Officials</h2>

            <!-- Table for Permit = Allow -->
            <h3>Permit: Allow</h3>
            <div class="table-container">
                <table class="table table-striped" id="allowTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>NRIC</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_allow->num_rows > 0) {
                            while ($row = $result_allow->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nric']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['department'] ? $row['department'] : 'N/A') . "</td>";
                                echo "<td>";
                                echo "<form method='POST' style='display:inline;'>";
                                echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>";
                                echo "<label><input type='radio' name='status' value='active' " . ($row['status'] == 'active' ? 'checked' : '') . " onchange='this.form.submit();'> Active</label>";
                                echo "<label><input type='radio' name='status' value='inactive' " . ($row['status'] == 'inactive' ? 'checked' : '') . " onchange='this.form.submit();'> Inactive</label>";
                                echo "</form>";
                                echo "</td>";
                                echo "<td>";
                                echo "<a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a> ";
                                echo "<a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No city officials with permit 'allow' found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Table for Permit = Restrict -->
            <h3>Permit: Restrict</h3>
            <div class="table-container">
                <table class="table table-striped" id="restrictTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>NRIC</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_restrict->num_rows > 0) {
                            while ($row = $result_restrict->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nric']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['department'] ? $row['department'] : 'N/A') . "</td>";
                                echo "<td>";
                                echo "<form method='POST' style='display:inline;'>";
                                echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>";
                                echo "<label><input type='radio' name='status' value='active' " . ($row['status'] == 'active' ? 'checked' : '') . " onchange='this.form.submit();'> Active</label>";
                                echo "<label><input type='radio' name='status' value='inactive' " . ($row['status'] == 'inactive' ? 'checked' : '') . " onchange='this.form.submit();'> Inactive</label>";
                                echo "</form>";
                                echo "</td>";
                                echo "<td>";
                                echo "<a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a> ";
                                echo "<a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No city officials with permit 'restrict' found</td></tr>";
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
        // Initialize DataTables
        const allowTable = new simpleDatatables.DataTable("#allowTable");
        const restrictTable = new simpleDatatables.DataTable("#restrictTable");
    </script>
</body>
</html>

<?php
$conn->close();
?>
