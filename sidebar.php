<style>
  /* Change text color to white for the sidebar */
  .sb-sidenav a {
    color: white !important;
    transition: color 0.3s ease;
    /* Smooth transition for color change */
  }

  /* Hover effect for sidebar links */
  .sb-sidenav a:hover {
    color: #ffc107 !important;
    /* Change color on hover */
    text-decoration: none;
    /* Remove underline */
  }

  .sb-sidenav a.active {
    color: #ffc107 !important;
  }
</style>

<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <div class="sb-sidenav-menu-heading">Menu</div>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'user-dashboard.php') ? 'active' : ''; ?>" href="user-dashboard.php">
          <div class="sb-nav-link-icon"></div>
          Dashboard
        </a>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'user-create-ticket.php') ? 'active' : ''; ?>" href="user-create-ticket.php">
          <div class="sb-nav-link-icon"></div>
          Create New Tickets
        </a>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'user-check-ticket.php') ? 'active' : ''; ?>" href="user-check-ticket.php">
          <div class="sb-nav-link-icon"></div>
          Check Tickets
        </a>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'user-knowledgebase.php') ? 'active' : ''; ?>" href="user-knowledgebase.php">
          <div class="sb-nav-link-icon"></div>
          Knowledgebase
        </a>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'user-send-feedback.php') ? 'active' : ''; ?>" href="user-send-feedback.php">
          <div class="sb-nav-link-icon"></div>
          Send Feedback
        </a>
        <!-- <a class="nav-link" href="logout.php">
                <div class="sb-nav-link-icon"></div>
                Logout
            </a> -->
      </div>
    </div>
  </nav>

</div>