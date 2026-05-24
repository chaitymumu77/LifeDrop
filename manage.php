<?php
session_start();
require_once 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$donors_query = $conn->query("
    SELECT d.id as donor_id, u.name, d.blood_group, d.location, d.phone 
    FROM users u 
    JOIN donors d ON u.id = d.user_id 
    ORDER BY d.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - Manage Donors</title>
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
                    <li class="nav-item"><a class="nav-link active" href="manage.php">Manage Donors</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_requests.php">Manage Requests</a></li>
                    <li class="nav-item"><a class="nav-link" href="notice.php">Manage Notices</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content container py-4 fade-in-on-load">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Donor Management (CRUD)</h2>
            <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#addModal">+ Add
                Donor</button>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="adminDonorTable">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Blood Group</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($donors_query->num_rows > 0): ?>
                                <?php $count = 1; while($row = $donors_query->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><span class="badge bg-danger"><?php echo htmlspecialchars($row['blood_group']); ?></span></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary edit-donor-btn" 
                                                data-id="<?php echo $row['donor_id']; ?>"
                                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                data-phone="<?php echo htmlspecialchars($row['phone']); ?>"
                                                data-bg="<?php echo htmlspecialchars($row['blood_group']); ?>"
                                                data-loc="<?php echo htmlspecialchars($row['location']); ?>"
                                                data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                            <a href="manage_donor_actions.php?action=delete&id=<?php echo $row['donor_id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this donor?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No donors found in database.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="manage_donor_actions.php" method="POST" class="modal-content">
                <input type="hidden" name="action" value="add">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Create New Donor Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Salim Mahmud">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email (for Login)</label>
                        <input type="email" name="email" class="form-control" required placeholder="donor@email.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Account Password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Blood Group</label>
                        <select name="blood_group" class="form-select" required>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Location</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Age</label>
                            <input type="number" name="age" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Weight (kg)</label>
                            <input type="number" name="weight" step="0.1" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Smoker?</label>
                        <select name="smoking_status" class="form-select" required>
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Disease Details</label>
                        <input type="text" name="disease_history" class="form-control" placeholder="Optional">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Save Donor to Database</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="manage_donor_actions.php" method="POST" class="modal-content">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editId">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Donor Record</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" id="editPhone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Blood Group</label>
                        <select name="blood_group" id="editBG" class="form-select" required>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Location</label>
                        <input type="text" name="location" id="editLoc" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Update Database Records</button>
                </div>
            </form>
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
    <script src="js/app.js"></script>
    <script>
        $(document).ready(function() {
            // Fill edit modal with data from button
            $('.edit-donor-btn').on('click', function() {
                $('#editId').val($(this).data('id'));
                $('#editName').val($(this).data('name'));
                $('#editPhone').val($(this).data('phone'));
                $('#editBG').val($(this).data('bg'));
                $('#editLoc').val($(this).data('loc'));
            });
        });
    </script>
</body>

</html>
