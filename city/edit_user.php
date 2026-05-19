<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

// Initialize variables for storing error/success messages
$error = '';
$success = '';

// Check if officer ID is provided
if (!isset($_GET['id'])) {
    die("Officer ID is required.");
}

$officer_id = $_GET['id'];

// Fetch officer details
$sql = "SELECT * FROM officer WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $officer_id);
$stmt->execute();
$result = $stmt->get_result();
$officer = $result->fetch_assoc();

if (!$officer) {
    die("Officer not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $nric = $_POST['nric'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $password = $_POST['password'] ?? '';
    $status = $_POST['status'] ?? '';
    $department_id = $_POST['department_id'] ?? null; // Set to null if not provided
    $municipal = $_POST['municipal'] ?? '';

    if ($department_id === null) {
        $error = "Department ID is required.";
    } else {
        // Ensure department_id is an integer
        $department_id = intval($department_id);

        // Update officer in the database
        $update_sql = "UPDATE officer SET name=?, nric=?, email=?, phone_number=?, password=?, department_id=?, status=?, municipal=? WHERE id=?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param(
            "sssssissi",
            $name,
            $nric,
            $email,
            $phone_number,
            $password,
            $department_id,
            $status,
            $municipal,
            $officer_id
        );

        if ($update_stmt->execute()) {
            $success = "Officer profile updated successfully!";
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "city_officials.php";
                    }, 3000); // Redirect after 3 seconds
                  </script>';


        } else {
            $error = "Error updating officer: " . $conn->error;
        }

        $update_stmt->close();
    }
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Officer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .container {
            margin-top: 20px;
            max-width: 600px;
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
            <h2>Edit Officer Profile</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="<?php echo htmlspecialchars($officer['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="nric" class="form-label">NRIC</label>
                    <input type="text" id="nric" name="nric" class="form-control"
                        value="<?php echo htmlspecialchars($officer['nric']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="<?php echo htmlspecialchars($officer['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" class="form-control"
                        value="<?php echo htmlspecialchars($officer['phone_number']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" id="password" name="password" class="form-control"
                        value="<?php echo htmlspecialchars($officer['password']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="department_id" class="form-label">Department ID</label>
                    <input type="text" id="department_id" name="department_id" class="form-control"
                        value="<?php echo isset($officer['department_id']) ? htmlspecialchars($officer['department_id']) : ''; ?>"
                        required>
                </div>



                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="active" <?php echo $officer['status'] == 'active' ? 'selected' : ''; ?>>Active
                        </option>
                        <option value="inactive" <?php echo $officer['status'] == 'inactive' ? 'selected' : ''; ?>>
                            Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="municipal" class="form-label">Municipal</label>
                    <input type="text" id="municipal" name="municipal" class="form-control"
                        value="<?php echo htmlspecialchars($officer['municipal']); ?>" readonly>
                </div>


                <button type="submit" class="btn btn-primary">Update Officer</button>
                <a href="city_officials.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>

</html>