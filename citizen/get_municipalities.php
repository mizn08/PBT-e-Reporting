<?php
include '../support/db.php';

$state = $_GET['state'];
$query = "SELECT mb FROM municipalities WHERE state = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $state);
$stmt->execute();
$result = $stmt->get_result();

$options = "<option value=''>Select Municipality</option>";
while ($row = $result->fetch_assoc()) {
    $options .= "<option value='" . htmlspecialchars($row['mb']) . "'>" . htmlspecialchars($row['mb']) . "</option>";
}
echo $options;

$stmt->close();
$conn->close();
?>
