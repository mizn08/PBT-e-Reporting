<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an officer
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}


// Initialize filters
$state = isset($_GET['state']) ? $_GET['state'] : '';
$municipality = isset($_GET['municipality']) ? $_GET['municipality'] : '';

// Fetch states for the dropdown
$states_query = "SELECT DISTINCT state FROM municipalities ORDER BY state ASC";
$states_result = $conn->query($states_query);

// Fetch filtered complaints
$sql = "SELECT * FROM complaints WHERE 1";
$params = [];
$types = "";

if ($state) {
    $sql .= " AND state = ?";
    $params[] = $state;
    $types .= "s";
}

if ($municipality) {
    $sql .= " AND municipality = ?";
    $params[] = $municipality;
    $types .= "s";
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Complaints</title>
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
    <script>
        // Fetch municipalities dynamically based on selected state
        function fetchMunicipalities(state) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `get_municipalities.php?state=${state}`, true);
            xhr.onload = function () {
                if (this.status === 200) {
                    document.getElementById('municipality').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <!-- Include Header -->
    <?php include 'head.php'; ?>

    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2>Filter Complaints</h2>

            <!-- Filter Form -->
            <form method="get" action="filter.php">
                <div class="mb-3">
                    <label for="state" class="form-label">State:</label>
                    <select id="state" name="state" class="form-select" onchange="fetchMunicipalities(this.value)" required>
                        <option value="">Select State</option>
                        <?php while ($row = $states_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['state']); ?>" <?php echo $state == $row['state'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['state']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="municipality" class="form-label">Municipality:</label>
                    <select id="municipality" name="municipality" class="form-select" required>
                        <option value="">Select Municipality</option>
                        <!-- Populate dynamically based on selected state -->
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>

            <!-- Complaints Table -->
            <div class="table-container">
                <table class="table table-striped" id="complaintsTable">
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
                            <th>State</th>
                            <th>Municipality</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['nric']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td><?php echo htmlspecialchars($row['details']); ?></td>
                                <td><?php echo htmlspecialchars($row['department']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['state']); ?></td>
                                <td><?php echo htmlspecialchars($row['municipality']); ?></td>
                                <td>
                                    <a href="edit_ticket.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script>
        const complaintsTable = new simpleDatatables.DataTable("#complaintsTable");
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
