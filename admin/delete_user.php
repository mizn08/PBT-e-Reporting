<?php
session_start();
require_once '../support/db.php'; // Database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Check if the 'id' parameter is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid user ID.'); window.location.href = 'city_officials.php';</script>";
    exit();
}

$user_id = intval($_GET['id']);

// Prevent deleting the currently logged-in admin
if ($user_id == $_SESSION['user_id']) {
    echo "<script>alert('You cannot delete your own account.'); window.location.href = 'city_officials.php';</script>";
    exit();
}

// Check if the user exists
$sql_check = "SELECT * FROM user WHERE id = ? AND role = 'officer'";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('User not found.'); window.location.href = 'city_officials.php';</script>";
    exit();
}

// Delete the user
$sql_delete = "DELETE FROM user WHERE id = ?";
$stmt = $conn->prepare($sql_delete);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo "<script>alert('User deleted successfully.'); window.location.href = 'city_officials.php';</script>";
} else {
    echo "<script>alert('Error deleting user.'); window.location.href = 'city_officials.php';</script>";
}

$stmt->close();
$conn->close();
?>
