<?php
require_once 'db_connect.php';

$donor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$donor = null;

if ($donor_id > 0) {
    $stmt = $conn->prepare("
        SELECT u.name, d.blood_group, d.phone, d.location, d.age, d.weight, d.smoking_status, d.disease_history, d.last_donation_date 
        FROM users u 
        JOIN donors d ON u.id = d.user_id 
        WHERE d.id = ?
    ");
    if($stmt) {
        $stmt->bind_param("i", $donor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $donor = $result->fetch_assoc();
        }
    }
}

// Calculate eligibility if donor exists
$eligible = true;
$eligibility_msg = "Eligible to donate! This donor is ready to help.";
$status_badge = '<span class="badge bg-success px-3 py-2">Available</span>';

if ($donor && $donor['last_donation_date']) {
    $last_date = new DateTime($donor['last_donation_date']);
    $today = new DateTime();
    $diff = $today->diff($last_date);
    $months = ($diff->format('%y') * 12) + $diff->format('%m');
    
    if ($months < 3) {
        $eligible = false;
        $wait_months = 3 - $months;
        $eligibility_msg = "Currently on wait. Last donated " . $months . " months ago. Will be eligible in about " . $wait_months . " month(s).";
        $status_badge = '<span class="badge bg-warning text-dark px-3 py-2">On Wait</span>';
    } else {
        $eligibility_msg = "Eligible to donate! Last donation was " . $months . " months ago.";
    }
} elseif ($donor && !$donor['last_donation_date']) {
    $eligibility_msg = "No previous donation record found. Donor is ready for their first donation!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - Donor Profile</title>
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
                    <li class="nav-item"><a class="nav-link" href="search.php">Search</a></li>
                    <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content container py-5 fade-in-on-load">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-secondary">Donor Information</h2>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">← Back</a>
                </div>

                <?php if ($donor): ?>
                <div class="card shadow-lg border-0 overflow-hidden">
                    <div class="card-header bg-danger text-white text-center py-5">
                        <h1 class="display-1 fw-bold mb-0"><?php echo htmlspecialchars($donor['blood_group']); ?></h1>
                        <p class="fs-4">Blood Group</p>
                        <div class="mt-3"><?php echo $status_badge; ?></div>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold text-primary-custom border-bottom pb-3 d-inline-block"><?php echo htmlspecialchars($donor['name']); ?></h2>
                        </div>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded shadow-sm">
                                    <small class="text-muted d-block mb-1">📞 Phone Number</small>
                                    <span class="fs-5 fw-bold"><?php echo htmlspecialchars($donor['phone']); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded shadow-sm">
                                    <small class="text-muted d-block mb-1">📍 Location</small>
                                    <span class="fs-5 fw-bold"><?php echo htmlspecialchars($donor['location']); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">Age</small>
                                    <span class="fw-bold"><?php echo $donor['age'] ? $donor['age'] . ' Years' : 'N/A'; ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">Weight</small>
                                    <span class="fw-bold"><?php echo $donor['weight'] ? $donor['weight'] . ' Kg' : 'N/A'; ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">Smoker</small>
                                    <span class="fw-bold <?php echo $donor['smoking_status'] ? 'text-warning' : 'text-success'; ?>">
                                        <?php echo $donor['smoking_status'] ? 'Yes' : 'No'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Alert -->
                        <div class="alert <?php echo $eligible ? 'alert-success' : 'alert-warning'; ?> border-0 shadow-sm py-4 mb-5">
                            <div class="d-flex align-items-center">
                                <div class="display-6 me-4"><?php echo $eligible ? '✅' : '⏳'; ?></div>
                                <div>
                                    <h5 class="fw-bold mb-1">Availability Status</h5>
                                    <p class="mb-0"><?php echo $eligibility_msg; ?></p>
                                    <small class="text-muted">Last Donation Date: <?php echo $donor['last_donation_date'] ? date('d M, Y', strtotime($donor['last_donation_date'])) : 'No record'; ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 text-center py-4">
                            <h6 class="fw-bold mb-2">Medical Note</h6>
                            <p class="mb-0 small">Disease History: <?php echo $donor['disease_history'] ? htmlspecialchars($donor['disease_history']) : 'No major diseases reported.'; ?></p>
                            <hr>
                            <small class="text-muted">Please verify all health records before blood collection.</small>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-danger text-center py-5 shadow">
                    <h1 class="display-1">🚫</h1>
                    <h3 class="fw-bold">Donor Not Found</h3>
                    <p>The donor you are looking for does not exist in our database.</p>
                    <a href="donor-list.php" class="btn btn-danger mt-3">Back to List</a>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container text-center">
            <p class="mb-0">© 2026 LifeDrop Blood Management System.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
