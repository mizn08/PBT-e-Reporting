<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}


// Check if the 'id' parameter is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid ticket ID.'); window.location.href = 'index.php';</script>";
    exit();
}

$ticket_id = intval($_GET['id']);

// Check if the ticket exists
$sql_check = "SELECT * FROM complaints WHERE id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Ticket not found.'); window.location.href = 'index.php';</script>";
    exit();
}

// Delete the ticket
$sql_delete = "DELETE FROM complaints WHERE id = ?";
$stmt = $conn->prepare($sql_delete);
$stmt->bind_param("i", $ticket_id);

if ($stmt->execute()) {
    echo "<script>alert('Ticket deleted successfully.'); window.location.href = 'index.php';</script>";
} else {
    echo "<script>alert('Error deleting ticket.'); window.location.href = 'index.php';</script>";
}

$stmt->close();
$conn->close();
?>
