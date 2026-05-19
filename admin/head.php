<!-- head.php -->
<?php

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
?>

<div class="header-bar">
    <div class="header-left">
        <!-- Logo image -->
        <img src="../images/citylogo.png" alt="City Logo" class="logo" height="100" width="100">
    </div>
    <div class="header-center">
        <h2>ADMIN</h2>
    </div>
    <div class="header-right">
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
    </div>
</div>

<style>
    /* Header bar styling */
    .header-bar {
        position: fixed; /* Fixes the header at the top */
        top: 0;          /* Aligns it to the top of the page */
        left: 0;
        width: 100%;     /* Takes full width of the page */
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background-color: grey;
        color: white;
        font-family: Arial, sans-serif;
        z-index: 1000;   /* Makes sure the header is above other elements */
    }

    /* Add padding to the body to prevent content from hiding under the fixed header */
    body {
        padding-top: 120px; /* Should be slightly larger than the header height */
    }

    .header-left, .header-center, .header-right {
        flex: 1;
        text-align: center;
    }

    .header-left {
        text-align: left;
    }

    .header-right {
        text-align: right;
        font-size: 16px;
    }

    .header-center h2 {
        margin: 0;
        font-size: 24px;
    }

    /* Logo styling */
    .logo {
        height: 100px; /* Increased size */
        width: 100px;  /* Increased size */
        vertical-align: middle;
    }
</style>
