<?php
// Database connection
require_once 'support/db.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $nric = $_POST['nric'];
    $subject = $_POST['subject'];
    $details = $_POST['details'];
  
    // Initialize variables for storing figures
    $figure1 = $_FILES['figure1']['tmp_name'];
    $figure2 = $_FILES['figure2']['tmp_name'];
    
    // Read the file contents into variables
    $figure1_data = file_get_contents($figure1);
    $figure2_data = file_get_contents($figure2);
    
    // Set target directory for uploads
    $target_dir = "upload/"; // Ensure this directory exists

    // Save the files to the uploads directory
    $figure1_name = basename($_FILES['figure1']['name']);
    $figure2_name = basename($_FILES['figure2']['name']);
    move_uploaded_file($figure1, $target_dir . $figure1_name);
    move_uploaded_file($figure2, $target_dir . $figure2_name);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO complaints (name, email, phone_number, nric, subject, details, figure1, figure2, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'open')");
    $stmt->bind_param("ssssssss", $name, $email, $phone_number, $nric, $subject, $details, $figure1_data, $figure2_data);

    if ($stmt->execute()) {
        echo "Complaint submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style2.css">
    <title>Submit Complaint</title>
</head>
<body style="background-image: url('images/citybg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">


<div class="container">
    <h2>Submit Your Complaint</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" class="form-input" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" class="form-input" required><br><br>

        <label for="phone_number">Phone Number:</label><br>
        <input type="text" id="phone_number" name="phone_number" class="form-input" required><br><br>

        <label for="nric">NRIC:</label><br>
        <input type="text" id="nric" name="nric" class="form-input" required><br><br>

        <label for="subject">Address:</label><br>
        <input type="text" id="subject" name="subject" class="form-input" required><br><br>

        <label for="department">Department:</label><br>
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


