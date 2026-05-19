<?php
session_start();
include 'support/db.php'; // Include the database connection file

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve input values
    $email = $_POST['email'];
    $password = $_POST['password'];

    // First, check the user table
    $query = "SELECT * FROM user WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists in the user table
        $user = $result->fetch_assoc();

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone_number'] = $user['phone_number'];
        $_SESSION['nric'] = $user['nric'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        switch ($user['role']) {
            case 'admin':
                header("Location: admin/index.php");
                break;
            case 'citizen':
                header("Location: citizen/index.php");
                break;
            default:
                $error = "Invalid role.";
                break;
        }
        exit();
    } else {
        // If not found in user table, check officer table
        $officer_query = "SELECT * FROM officer WHERE email = ? AND password = ?";
        $officer_stmt = $conn->prepare($officer_query);
        $officer_stmt->bind_param("ss", $email, $password);
        $officer_stmt->execute();
        $officer_result = $officer_stmt->get_result();

        if ($officer_result->num_rows > 0) {
            // User exists in the officer table
            $officer = $officer_result->fetch_assoc();

            if ($officer['status'] !== 'active') {
                $error = "Your account is inactive. Please contact the admin.";
            } elseif ($officer['permit'] !== 'allow') {
                $error = "Your account requires admin approval. Please wait.";
            } else {
                // Set session variables for officer
                $_SESSION['officer_id'] = $officer['id'];
                $_SESSION['username'] = $officer['name'];
                $_SESSION['email'] = $officer['email'];
                $_SESSION['phone_number'] = $officer['phone_number'];
                $_SESSION['nric'] = $officer['nric'];

                // Redirect to officer dashboard
                header("Location: city/index.php");
                exit();
            }
        } else {
            $error = "Invalid email or password.";
        }

        $officer_stmt->close();
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-control {
            width: 100%;
            box-sizing: border-box;
        }

        .panel-heading {
            background: #00796B;
            color: white;
            border-radius: 5px 5px 0 0;
            padding: 15px;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="panel-heading">eComplaint - Login</div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if (isset($error) && $error): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" id="email" required placeholder="Email"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="password" required placeholder="Password"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Login</button>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-secondary btn-block" type="button"
                                    onclick="window.location.href='index.php';">Go to Home</button>
                            </div>

                        </form>
                        <p class="text-center">Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>