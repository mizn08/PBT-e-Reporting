<?php
include 'support/db.php'; // Include your database connection file

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $nric = $_POST['nric'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $municipal = $_POST['municipal'];

    $clarify_path = null; // Default clarify path is null

    // Handle file upload
    if (isset($_FILES['clarify']) && $_FILES['clarify']['error'] == 0) {
        $upload_dir = 'uploads/clarify/';
        $file_name = uniqid() . '_' . basename($_FILES['clarify']['name']);
        $clarify_path = $upload_dir . $file_name;

        // Ensure the uploads/clarify directory exists
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create directory if it doesn't exist
        }

        // Move the uploaded file to the uploads/clarify directory
        if (!move_uploaded_file($_FILES['clarify']['tmp_name'], $clarify_path)) {
            $error = "Failed to upload file.";
        }
    } else {
        $error = "File upload is required.";
    }

    // Basic validation
    if (empty($name) || empty($nric) || empty($email) || empty($phone_number) || empty($password) || empty($municipal) || !$clarify_path) {
        $error = "Please fill in all required fields.";
    } else {
        // Check if email already exists
        $query = "SELECT * FROM officer WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email is already registered. Please choose a different email.";
        } else {
            // Insert the new officer into the database
            $query = "INSERT INTO officer (name, nric, email, phone_number, password, department, clarify, status, permit, municipal) VALUES (?, ?, ?, ?, ?, NULL, ?, 'active', 'access', ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssss", $name, $nric, $email, $phone_number, $password, $clarify_path, $municipal);

            if ($stmt->execute()) {
                $success = "Registration successful! Redirecting to login page...";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Ensure all form controls have consistent width */
        form .form-control {
            width: 100%;
            box-sizing: border-box;
        }

        .message-box {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .success-box {
            background-color: #d4edda;
            color: #155724;
        }

        .error-box {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
    <script>
        function redirectToLogin() {
            setTimeout(function () {
                window.location.href = 'login.php';
            }, 3000); // Redirect after 3 seconds
        }
    </script>
</head>

<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="panel-heading"
                        style="background:#00796B;color:white; border-radius: 5px 5px 0 0; padding: 15px;">
                        <div class="panel-title" text-align="center">City Officer - Register</div>
                    </div>
                    <div class="card-body">
                        <?php if ($success): ?>
                            <div class="message-box success-box">
                                <?php echo $success; ?>
                            </div>
                            <script>redirectToLogin();</script>
                        <?php elseif ($error): ?>
                            <div class="message-box error-box">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" required placeholder="Name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>NRIC</label>
                                <input type="text" name="nric" required placeholder="NRIC" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" required placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone_number" required placeholder="Phone Number"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" required placeholder="Password"
                                    class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Municipal</label>
                                <select name="municipal" required class="form-control">
                                    <option value="">Select Municipal</option>
                                    <option value="MBPJ">MBPJ</option>
                                    <option value="MBAJ">MBAJ</option>
                                    <option value="MBSA">MBSA</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Upload Clarification Document (Image or PDF)</label>
                                <input type="file" name="clarify" accept="image/*,application/pdf" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Register</button>
                            </div>
                        </form>


                        <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>