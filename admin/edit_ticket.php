<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an officer
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Initialize variables for storing error/success messages
$error = '';
$success = '';

// Check if ticket ID is provided
if (!isset($_GET['id'])) {
    die("Ticket ID is required.");
}

$ticket_id = $_GET['id'];

// Fetch ticket details
$sql = "SELECT * FROM complaints WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    die("Ticket not found.");
}

// Handle form submission for updating ticket
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $nric = $_POST['nric'];
    $subject = $_POST['subject'];
    $details = $_POST['details'];
    $department = $_POST['department'];
    $status = $_POST['status'];
    $answer = $_POST['answer'];
    $time_resolve = $_POST['time_resolve'];

    // Update images only if new files are uploaded
    $figure1_data = !empty($_FILES['figure1']['tmp_name']) ? file_get_contents($_FILES['figure1']['tmp_name']) : $ticket['figure1'];
    $figure2_data = !empty($_FILES['figure2']['tmp_name']) ? file_get_contents($_FILES['figure2']['tmp_name']) : $ticket['figure2'];
    $figure3_data = !empty($_FILES['figure3']['tmp_name']) ? file_get_contents($_FILES['figure3']['tmp_name']) : $ticket['figure3'];
    $figure4_data = !empty($_FILES['figure4']['tmp_name']) ? file_get_contents($_FILES['figure4']['tmp_name']) : $ticket['figure4'];

    // Update ticket in the database
    $update_sql = "UPDATE complaints SET name=?, email=?, phone_number=?, nric=?, subject=?, details=?, department=?, status=?, answer=?, time_resolve=?, figure1=?, figure2=?, figure3=?, figure4=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param(
        "ssssssssssssssi",
        $name,
        $email,
        $phone_number,
        $nric,
        $subject,
        $details,
        $department,
        $status,
        $answer,
        $time_resolve,
        $figure1_data,
        $figure2_data,
        $figure3_data,
        $figure4_data,
        $ticket_id
    );

    if ($update_stmt->execute()) {
        $success = "Ticket updated successfully!";
        // Refresh the ticket data after update
        $stmt->execute();
        $ticket = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "Error updating ticket: " . $conn->error;
    }

    $update_stmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .container {
            margin-top: 20px;
            max-width: 600px;
        }

        .image-preview {
            margin-bottom: 10px;
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Ticket</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="<?php echo htmlspecialchars($ticket['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="<?php echo htmlspecialchars($ticket['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control"
                    value="<?php echo htmlspecialchars($ticket['phone_number']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="nric" class="form-label">NRIC</label>
                <input type="text" id="nric" name="nric" class="form-control"
                    value="<?php echo htmlspecialchars($ticket['nric']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" id="subject" name="subject" class="form-control"
                    value="<?php echo htmlspecialchars($ticket['subject']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="details" class="form-label">Details</label>
                <textarea id="details" name="details" class="form-control"
                    required><?php echo htmlspecialchars($ticket['details']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="department" class="form-label">Category</label>
                <select id="department" name="department" class="form-control" required>
                    <option value="Building / Houses" <?php echo $ticket['department'] == 'Building / Houses' ? 'selected' : ''; ?>>Building / Houses</option>
                    <option value="Slope" <?php echo $ticket['department'] == 'Slope' ? 'selected' : ''; ?>>Slope
                    </option>
                    <option value="Animal" <?php echo $ticket['department'] == 'Animal' ? 'selected' : ''; ?>>Animal
                    </option>
                    <option value="Road" <?php echo $ticket['department'] == 'Road' ? 'selected' : ''; ?>>Road
                    </option>
                    <option value="Drain / Flood" <?php echo $ticket['department'] == 'Drain / Flood' ? 'selected' : ''; ?>>Drain / Flood</option>
                    <option value="OKU" <?php echo $ticket['department'] == 'OKU' ? 'selected' : ''; ?>>OKU</option>
                    <option value="Ads board" <?php echo $ticket['department'] == 'Ads board' ? 'selected' : ''; ?>>
                        Ads board</option>
                    <option value="Parking" <?php echo $ticket['department'] == 'Parking' ? 'selected' : ''; ?>>
                        Parking</option>
                    <option value="Open burning" <?php echo $ticket['department'] == 'Open burning' ? 'selected' : ''; ?>>
                        Open burning</option>
                    <option value="Landscape" <?php echo $ticket['department'] == 'Landscape' ? 'selected' : ''; ?>>
                        Landscape</option>
                    <option value="Food stall" <?php echo $ticket['department'] == 'Food stall' ? 'selected' : ''; ?>>
                        Food stall</option>
                    <option value="Rubbish" <?php echo $ticket['department'] == 'Rubbish' ? 'selected' : ''; ?>>
                        Rubbish</option>
                </select>
            </div>


            <div class="mb-3">
                <label for="State" class="form-label">State</label>
                <textarea id="State" name="State"
                    class="form-control"><?php echo htmlspecialchars($ticket['state']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="Municipality" class="form-label">Municipality</label>
                <textarea id="Municipality" name="Municipality"
                    class="form-control"><?php echo htmlspecialchars($ticket['municipality']); ?></textarea>
            </div>

           

            <div class="mb-3">
                <label for="answer" class="form-label">Answer</label>
                <textarea id="answer" name="answer"
                    class="form-control"><?php echo htmlspecialchars($ticket['answer']); ?></textarea>
            </div>


            <div class="mb-3">
                <label for="time_resolve" class="form-label">Time Resolve</label>
                <input type="datetime-local" id="time_resolve" name="time_resolve" class="form-control"
                    value="<?php echo htmlspecialchars($ticket['time_resolve']); ?>">
            </div>

            <!-- Figures Display and Upload Section -->
            <div class="mb-3">
                <label for="figure1" class="form-label">Citizen Figure 1</label>
                <?php if (!empty($ticket['figure1'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($ticket['figure1']); ?>"
                        class="image-preview">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
                <input type="file" id="figure1" name="figure1" class="form-control">
            </div>

            <div class="mb-3">
                <label for="figure2" class="form-label">Citizen Figure 2</label>
                <?php if (!empty($ticket['figure2'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($ticket['figure2']); ?>"
                        class="image-preview">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
                <input type="file" id="figure2" name="figure2" class="form-control">
            </div>
            <div class="mb-3">
                <label for="Location" class="form-label">Location</label>
                <?php
                $location = $ticket['location'];
                // Extract the latitude and longitude
                preg_match('/Lat: ([\d\.\-]+), Long: ([\d\.\-]+)/', $location, $matches);
                if (count($matches) === 3) {
                    $latitude = $matches[1];
                    $longitude = $matches[2];
                    $mapLink = "https://www.google.com/maps?q={$latitude},{$longitude}";
                    echo '<textarea id="Location" name="Location" class="form-control" readonly>' . htmlspecialchars($location) . '</textarea>';
                    echo '<a href="' . htmlspecialchars($mapLink) . '" target="_blank" class="btn btn-link mt-2">View on Google Maps</a>';
                } else {
                    echo '<textarea id="Location" name="Location" class="form-control" readonly>' . htmlspecialchars($location) . '</textarea>';
                }
                ?>
            </div>

            <div class="mb-3">
                <label for="figure3" class="form-label">City Figure 1</label>
                <?php if (!empty($ticket['figure3'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($ticket['figure3']); ?>"
                        class="image-preview">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
                <input type="file" id="figure3" name="figure3" class="form-control">
            </div>

            <div class="mb-3">
                <label for="figure4" class="form-label">City Figure 2</label>
                <?php if (!empty($ticket['figure4'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($ticket['figure4']); ?>"
                        class="image-preview">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
                <input type="file" id="figure4" name="figure4" class="form-control">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="open" <?php echo $ticket['status'] == 'open' ? 'selected' : ''; ?>>Open</option>
                    <option value="pending" <?php echo $ticket['status'] == 'pending' ? 'selected' : ''; ?>>Pending
                    </option>
                    <option value="close" <?php echo $ticket['status'] == 'close' ? 'selected' : ''; ?>>Close</option>
                </select>
            </div>




            <button type="submit" class="btn btn-primary">Update Ticket</button>
            <a href="complaint_ticket.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    </div>
</body>

</html>