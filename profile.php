<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $last_donation = $_POST['last_donation_date'];
    
    $stmt1 = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
    $stmt1->bind_param("si", $name, $user_id);
    $stmt1->execute();
    
    $stmt2 = $conn->prepare("UPDATE donors SET phone = ?, location = ?, last_donation_date = ? WHERE user_id = ?");
    $stmt2->bind_param("sssi", $phone, $location, $last_donation, $user_id);
    
    if ($stmt2->execute()) {
        $success_msg = "Profile Updated Successfully!";
    } else {
        $error_msg = "Error updating profile.";
    }
}

// Fetch user data
$stmt = $conn->prepare("
    SELECT u.name, u.email, d.blood_group, d.phone, d.location, d.last_donation_date 
    FROM users u 
    LEFT JOIN donors d ON u.id = d.user_id 
    WHERE u.id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Calculate eligibility
$eligible = true;
$last_donation_msg = "No donation record found. You are eligible to donate!";
if ($user_data['last_donation_date']) {
    $last_date = new DateTime($user_data['last_donation_date']);
    $today = new DateTime();
    $diff = $today->diff($last_date);
    $months = ($diff->format('%y') * 12) + $diff->format('%m');
    if ($months >= 3) {
        $last_donation_msg = "Your last blood donation was recorded " . $months . " months ago. You are eligible to donate again!";
    } else {
        $eligible = false;
        $last_donation_msg = "Your last blood donation was recorded recently. You need to wait " . (3 - $months) . " more months to donate.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary-custom">
        <div class="container">
            <a class="navbar-brand" href="index.php">🩸 LifeDrop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="donor-list.php">Find Donor</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Admin Dash</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            My Account
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content container py-5 fade-in-on-load">
        <?php if ($success_msg != ""): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <?php if ($error_msg != ""): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-sm p-4">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user_data['name']); ?>&background=random&size=128"
                        class="rounded-circle mx-auto mb-3" alt="Avatar">
                    <h4 class="fw-bold"><?php echo htmlspecialchars($user_data['name']); ?></h4>
                    <p class="text-muted mb-3"><span class="badge badge-op"><?php echo htmlspecialchars($user_data['blood_group']); ?> Donor</span> Active</p>
                    <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                        data-bs-target="#editProfileModal">Edit Profile</button>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-sm-3 text-muted">Full Name</div>
                            <div class="col-sm-9"><?php echo htmlspecialchars($user_data['name']); ?></div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-sm-3 text-muted">Email</div>
                            <div class="col-sm-9"><?php echo htmlspecialchars($user_data['email']); ?></div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-sm-3 text-muted">Phone</div>
                            <div class="col-sm-9"><?php echo htmlspecialchars($user_data['phone']); ?></div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-sm-3 text-muted">Address/Location</div>
                            <div class="col-sm-9"><?php echo htmlspecialchars($user_data['location']); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Last Donation</div>
                            <div class="col-sm-9 text-danger fw-bold">
                                <?php echo $user_data['last_donation_date'] ? date('d M, Y', strtotime($user_data['last_donation_date'])) : 'Never Donated'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert <?php echo $eligible ? 'alert-info' : 'alert-warning'; ?> shadow-sm">
                    <strong><?php echo $eligible ? 'Great!' : 'Notice:'; ?></strong> <?php echo $last_donation_msg; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="update_profile" value="1">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Location</label>
                            <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($user_data['location']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Donation Date</label>
                            <input type="date" name="last_donation_date" class="form-control" value="<?php echo htmlspecialchars($user_data['last_donation_date']); ?>">
                            <small class="text-muted">Leave empty if you haven't donated yet.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-0">© 2026 LifeDrop Blood Management System.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
