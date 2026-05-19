<?php
include 'support/db.php'; // Include your database connection file

// Retrieve complaints based on NRIC from GET parameter
$nric = isset($_GET['nric']) ? $_GET['nric'] : '';

if ($nric) {
    $query = "SELECT id, name, time_created, status FROM complaints WHERE nric = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nric);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = null; // Set to null to prevent error before NRIC input
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Complaints</title>
        <link rel="stylesheet" href="css/style3.css">

</head>
<body style="background-image: url('images/citybg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;" >
    <div>

    <h1>Track Complaints</h1>

    <!-- Form to enter NRIC number -->
    <form method="get" action="track_complaint.php">
        <label for="nric">Enter NRIC:</label>
        <input type="text" id="nric" name="nric" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($result): ?>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Time Created</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['time_created']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a href="view_complaint.php?id=<?php echo $row['id']; ?>">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php elseif ($nric): ?>
        <p>No complaints found for the provided NRIC.</p>
    <?php endif; ?>

    <?php
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
    ?>
    </div>
</body>
</html>
