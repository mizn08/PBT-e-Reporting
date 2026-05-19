<?php
if (!isset($_SESSION['user_logged']) && $_SESSION['user_logged'] != true) {
    header('Location: index.php');
}

$db = new DB();
$query = "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "'";
$result = $db->conn->query($query);
$row = $result->fetch_assoc();
$name = $row['name'];
$email = $row['email'];


?>

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="user-dashboard.php"><img src="logo.jpg" alt="Resolve Rocket Logo" width="50" height="50"> Resolve Rocket</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <img src="profile.jpg" alt="Profile Photo" width="40" class="profile-photo rounded-circle">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $name ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>