<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Get Total Donors
$total_donors_query = $conn->query("SELECT COUNT(*) as count FROM donors");
$total_donors = $total_donors_query->fetch_assoc()['count'];

// Get Active Blood Groups
$active_groups_query = $conn->query("SELECT COUNT(DISTINCT blood_group) as count FROM donors");
$active_groups = $active_groups_query->fetch_assoc()['count'];

// Get Most Common Group
$most_common_query = $conn->query("SELECT blood_group, COUNT(*) as count FROM donors GROUP BY blood_group ORDER BY count DESC LIMIT 1");
$most_common_group = "N/A";
if ($most_common_query->num_rows > 0) {
    $most_common_group = $most_common_query->fetch_assoc()['blood_group'];
}

// Get Pending Blood Requests
$pending_req_query = $conn->query("SELECT COUNT(*) as count FROM blood_requests WHERE status = 'pending'");
$pending_requests = $pending_req_query->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container">
            <a class="navbar-brand text-danger" href="index.php">🩸 Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Live Site</a></li>
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage.php">Manage Donors</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_requests.php">Manage Requests</a></li>
                    <li class="nav-item"><a class="nav-link" href="notice.php">Manage Notices</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content container py-5 fade-in-on-load">
        <h2 class="fw-bold text-dark mb-4 border-bottom pb-2">Admin Overview Dashboard</h2>

        <div class="row" id="dashboardStats">
            <div class="col-md-4 mb-4">
                <div class="card bg-danger text-white shadow h-100 border-0 p-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Donors</h5>
                        <h1 class="display-3 fw-bold"><?php echo $total_donors; ?></h1>
                        <p class="mb-0 small">Registered in system</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card bg-success text-white shadow h-100 border-0 p-3">
                    <div class="card-body">
                        <h5 class="card-title">Active Blood Groups</h5>
                        <h1 class="display-3 fw-bold"><?php echo $active_groups; ?></h1>
                        <p class="mb-0 small">Distinct types available</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card bg-primary text-white shadow h-100 border-0 p-3">
                    <div class="card-body">
                        <h5 class="card-title">Pending Blood Requests</h5>
                        <h1 class="display-3 fw-bold"><?php echo $pending_requests; ?></h1>
                        <p class="mb-0 small">Emergency cases needing attention</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">Recent Admin Actions</div>
                    <ul class="list-group list-group-flush text-muted">
                        <li class="list-group-item">Admin logged in. Session Active.</li>
                        <li class="list-group-item">System metrics successfully loaded via PHP/MySQL</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-0">© 2026 Admin Portal - LifeDrop.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
