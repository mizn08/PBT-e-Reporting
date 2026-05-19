<?php
session_start();
require_once '../support/db.php'; // Database connection

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}



// Query the database for ticket counts by status
$open_tickets = 0;
$pending_tickets = 0;
$close_tickets = 0;

$sql = "SELECT status, COUNT(*) AS count FROM complaints GROUP BY status";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        switch ($row['status']) {
            case 'open':
                $open_tickets = $row['count'];
                break;
            case 'pending':
                $pending_tickets = $row['count'];
                break;
            case 'close':
                $close_tickets = $row['count'];
                break;
        }
    }
}

$total_tickets = $open_tickets + $pending_tickets + $close_tickets;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Main content styling */
        .main-content {
            margin-left: 250px;
            /* Width of sidebar */
            padding: 20px;
            width: 100%;
        }

        .status-chart-container {
            display: flex;
            justify-content: space-evenly;
            align-items: flex-start;
            margin-top: 20px;
        }

        .status-cards {
            width: 30%;
            display: flex;
            flex-direction: column;
            gap: 15px;
            /* Reduced gap between cards */
        }

        .chart-container {
            width: 30%;
            /* Adjusted width to make the chart smaller */
        }

        .chart-container canvas {
            max-width: 100%;
            /* Ensure the chart fits within its container */
        }
    </style>
</head>

<body>
    <!-- Include Header -->
    <?php include 'head.php'; ?>

    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <h2>Admin Dashboard</h2>
            <div class="status-chart-container">
                <!-- Ticket Status Cards -->
                <div class="status-cards">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Tickets</h5>
                            <p class="card-text"><?php echo $total_tickets; ?></p>
                        </div>
                    </div>
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Open Tickets</h5>
                            <p class="card-text"><?php echo $open_tickets; ?></p>
                        </div>
                    </div>
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Pending Tickets</h5>
                            <p class="card-text"><?php echo $pending_tickets; ?></p>
                        </div>
                    </div>
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Closed Tickets</h5>
                            <p class="card-text"><?php echo $close_tickets; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Ticket Status Chart -->
                <div class="chart-container">
                    <canvas id="ticketStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('ticketStatusChart').getContext('2d');
        const ticketStatusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Open', 'Pending', 'Closed'],
                datasets: [{
                    label: 'Ticket Status',
                    data: [
                        <?php echo $open_tickets; ?>,
                        <?php echo $pending_tickets; ?>,
                        <?php echo $close_tickets; ?>
                    ],
                    backgroundColor: ['#42A5F5', '#FFA726', '#66BB6A'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>