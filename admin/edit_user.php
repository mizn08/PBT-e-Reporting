<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an admin
// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
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
    $department = $_POST['department'] ?? '';
    $permit = $_POST['permit'] ?? '';
    $department_id = $_POST['department_id'] ?? null; // Set to null if not provided

    if ($department_id === null) {
        $error = "Department ID is required.";
    } else {
        // Ensure department_id is an integer
        $department_id = intval($department_id);

        // Update officer in the database
        $update_sql = "UPDATE officer SET name=?, nric=?, email=?, phone_number=?, password=?, department=?, department_id=?, permit=? WHERE id=?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssssisi", $name, $nric, $email, $phone_number, $password, $department, $department_id, $permit, $officer_id);

        if ($update_stmt->execute()) {
            header("Location: city_officials.php");
            exit();
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
                    <label for="department" class="form-label">Department</label>
                    <select id="department" name="department" class="form-control" required>
                        <option value="Building / Houses" <?php echo $officer['department'] == 'Building / Houses' ? 'selected' : ''; ?>>Building / Houses</option>
                        <option value="Slope" <?php echo $officer['department'] == 'Slope' ? 'selected' : ''; ?>>Slope
                        </option>
                        <option value="Animal" <?php echo $officer['department'] == 'Animal' ? 'selected' : ''; ?>>Animal
                        </option>
                        <option value="Road" <?php echo $officer['department'] == 'Road' ? 'selected' : ''; ?>>Road
                        </option>
                        <option value="Drain / Flood" <?php echo $officer['department'] == 'Drain / Flood' ? 'selected' : ''; ?>>Drain / Flood</option>
                        <option value="OKU" <?php echo $officer['department'] == 'OKU' ? 'selected' : ''; ?>>OKU</option>
                        <option value="Ads board" <?php echo $officer['department'] == 'Ads board' ? 'selected' : ''; ?>>
                            Ads board</option>
                        <option value="Parking" <?php echo $officer['department'] == 'Parking' ? 'selected' : ''; ?>>
                            Parking</option>
                        <option value="Open burning" <?php echo $officer['department'] == 'Open burning' ? 'selected' : ''; ?>>Open burning</option>
                        <option value="Landscape" <?php echo $officer['department'] == 'Landscape' ? 'selected' : ''; ?>>
                            Landscape</option>
                        <option value="Food stall" <?php echo $officer['department'] == 'Food stall' ? 'selected' : ''; ?>>Food stall</option>
                        <option value="Rubbish" <?php echo $officer['department'] == 'Rubbish' ? 'selected' : ''; ?>>
                            Rubbish</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="permit" class="form-label">Permit</label>
                    <select id="permit" name="permit" class="form-control" required>
                        <option value="allow" <?php echo $officer['permit'] == 'allow' ? 'selected' : ''; ?>>Allow
                        </option>
                        <option value="access" <?php echo $officer['permit'] == 'access' ? 'selected' : ''; ?>>
                            Restrict</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="clarify" class="form-label">Clarify Document</label>
                    <?php if (!empty($officer['clarify'])): ?>
                        <?php
                        // Correctly handle the file path by prepending "../" to exit the "admin" folder
                        $file_path = '../' . htmlspecialchars($officer['clarify']);
                        $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
                        ?>
                        <?php if (strtolower($file_extension) === 'pdf'): ?>
                            <embed src="<?php echo $file_path; ?>" type="application/pdf" width="100%" height="400px" />
                        <?php else: ?>
                            <img src="<?php echo $file_path; ?>" alt="Clarify Document"
                                style="max-width: 100%; height: auto;" />
                        <?php endif; ?>
                    <?php else: ?>
                        <p>No clarification document uploaded.</p>
                    <?php endif; ?>
                </div>










                <button type="submit" class="btn btn-primary">Update Officer</button>
                <a href="city_officials.php" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>

</body>

</html>