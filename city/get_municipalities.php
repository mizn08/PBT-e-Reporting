<?php
include '../support/db.php'; // Include database connection

$state = isset($_GET['state']) ? $_GET['state'] : '';

if ($state) {
    $query = "SELECT mb FROM municipalities WHERE state = ? ORDER BY mb ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $state);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Municipality</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($row['mb']) . '">' . htmlspecialchars($row['mb']) . '</option>';
    }

    $stmt->close();
}
$conn->close();
?>
