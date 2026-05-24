<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle request deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM blood_requests WHERE id = $id");
    header("Location: manage_requests.php?msg=Deleted");
    exit();
}

// Handle status update
if (isset($_GET['complete'])) {
    $id = intval($_GET['complete']);
    $conn->query("UPDATE blood_requests SET status = 'completed' WHERE id = $id");
    header("Location: manage_requests.php?msg=Updated");
    exit();
}

// Fetch all requests
$requests = $conn->query("SELECT * FROM blood_requests ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - Blood Requests</title>
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
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage.php">Manage Donors</a></li>
                    <li class="nav-item"><a class="nav-link active" href="manage_requests.php">Manage Requests</a></li>
                    <li class="nav-item"><a class="nav-link" href="notice.php">Manage Notices</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content container py-4 fade-in-on-load">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Emergency Blood Requests</h2>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Action Successful: <?php echo htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Blood Group</th>
                                <th>Hospital</th>
                                <th>Units</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($requests->num_rows > 0): ?>
                                <?php while($row = $requests->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                        <td><span class="badge bg-danger"><?php echo htmlspecialchars($row['blood_group']); ?></span></td>
                                        <td><?php echo htmlspecialchars($row['hospital']); ?></td>
                                        <td><?php echo $row['units']; ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td>
                                            <?php if ($row['status'] == 'pending'): ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="small text-muted"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <?php if ($row['status'] == 'pending'): ?>
                                                <a href="manage_requests.php?complete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success">Done</a>
                                            <?php endif; ?>
                                            <a href="manage_requests.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">No blood requests found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
