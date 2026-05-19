<?php
session_start();
include '../support/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
    header("Location: ../login.php");
    exit();
}

// Get user information from session
$name = $_SESSION['username'];
$email = $_SESSION['email'];
$phone_number = $_SESSION['phone_number'];
$nric = $_SESSION['nric'];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $department = $_POST['department'];
    $details = $_POST['details'];
    $location = $_POST['location']; // Get location from user input
    $municipality = $_POST['municipality'];

    // Handle file uploads
    $figure1 = $_FILES['figure1']['tmp_name'] ? file_get_contents($_FILES['figure1']['tmp_name']) : null;
    $figure2 = $_FILES['figure2']['tmp_name'] ? file_get_contents($_FILES['figure2']['tmp_name']) : null;

    // Validate file uploads
    if (!$figure1 || !$figure2) {
        echo "<script>alert('Both figures are required. Please upload them.');</script>";
    } else {
        // Insert complaint into database
        $stmt = $conn->prepare("INSERT INTO complaints (name, email, phone_number, nric, subject, department, details, location, municipality, figure1, figure2, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'open')");
        $stmt->bind_param("sssssssssss", $name, $email, $phone_number, $nric, $subject, $department, $details, $location, $municipality, $figure1, $figure2);

        if ($stmt->execute()) {
            echo "<script>alert('Complaint submitted successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style2.css">
    <title>Submit Complaint</title>
    <script>
        // Auto-detect user location
        function detectLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    document.getElementById('location').value = `Lat: ${latitude}, Long: ${longitude}`;
                }, function (error) {
                    alert('Unable to retrieve location. Please enter manually.');
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
    </script>
</head>
<body style="background-image: url('../images/citybg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

<div class="container">
    <h2>Submit Your Complaint</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($name); ?>" readonly><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($email); ?>" readonly><br><br>

        <label for="phone_number">Phone Number:</label><br>
        <input type="text" id="phone_number" name="phone_number" class="form-input" value="<?php echo htmlspecialchars($phone_number); ?>" readonly><br><br>

        <label for="nric">NRIC:</label><br>
        <input type="text" id="nric" name="nric" class="form-input" value="<?php echo htmlspecialchars($nric); ?>" readonly><br><br>

        <label for="subject">Subject:</label><br>
        <input type="text" id="subject" name="subject" class="form-input" required><br><br>

        <label for="department">Category:</label><br>
        <select id="department" name="department" class="form-input" required>
            <option value="Building / Houses">Building / Houses</option>
            <option value="Slope">Slope</option>
            <option value="Animal">Animal</option>
            <option value="Road">Road</option>
            <option value="Drain / Flood">Drain / Flood</option>
            <option value="OKU">OKU</option>
            <option value="Ads board">Ads board</option>
            <option value="Parking">Parking</option>
            <option value="Open fire">Open Burning</option>
            <option value="Landscape">Landscape</option>
            <option value="Food stall">Food stall</option>
            <option value="Rubbish">Rubbish</option>
        </select><br><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location" class="form-input" placeholder="Enter manually or use auto-detect" required><br>
        <button type="button" onclick="detectLocation()">Auto Detect Location</button><br><br>

        <label for="municipality">Municipality:</label><br>
        <select id="municipality" name="municipality" class="form-input" required>
            <option value="MBSA">MBSA</option>
            <option value="MBPJ">MBPJ</option>
            <option value="MBAJ">MBAJ</option>
        </select><br><br>

        <label for="details">Details:</label><br>
        <textarea id="details" name="details" class="form-textarea" required></textarea><br><br>

        <label for="figure1">Figure 1:</label><br>
        <input type="file" id="figure1" name="figure1" accept="image/*" class="form-input" required><br><br>

        <label for="figure2">Figure 2:</label><br>
        <input type="file" id="figure2" name="figure2" accept="image/*" class="form-input" required><br><br>

        <input type="submit" value="Submit Complaint">
    </form>

    <button onclick="window.location.href='index.php'">Go to Dashboard</button>
</div>

</body>
</html>

