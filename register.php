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
    $password = $_POST['password']; // Use the plain password as is

    // Role is automatically assigned as 'citizen'
    $role = 'citizen';

    // Basic validation
    if (empty($name) || empty($nric) || empty($email) || empty($phone_number) || empty($password)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check if email already exists
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email is already registered. Please choose a different email.";
        } else {
            // Insert the new user into the database with department set to NULL
            $query = "INSERT INTO user (name, nric, email, phone_number, password, department, role) VALUES (?, ?, ?, ?, ?, NULL, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssss", $name, $nric, $email, $phone_number, $password, $role);

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
            setTimeout(function() {
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
                    <div class="panel-heading" style="background:#00796B;color:white; border-radius: 5px 5px 0 0; padding: 15px;">
                        <div class="panel-title" text-align="center">Citizen - Register</div>
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

                        <form method="POST">
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
                                <input type="text" name="phone_number" required placeholder="Phone Number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" required placeholder="Password" class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Register</button>
                            </div>
                        </form>
                        <p class="text-center">If you are City Officer <a href="register2.php">Register here</a></p>
                        <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
