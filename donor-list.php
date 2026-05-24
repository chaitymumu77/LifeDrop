<?php
session_start();
require_once 'db_connect.php';

$donors_query = $conn->query("
    SELECT d.id as donor_id, u.name, d.blood_group, d.location, d.gender, d.phone, d.last_donation_date 
    FROM users u 
    JOIN donors d ON u.id = d.user_id 
    ORDER BY u.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - Donor List</title>
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
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link active" href="donor-list.php">Find Donor</a></li>
                    <li class="nav-item"><a class="nav-link" href="search.php">Search</a></li>
                    <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="notice.php">Notice</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="profile.php">My Account</a></li>
                    <?php elseif(isset($_SESSION['admin_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Admin Dash</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content container py-5 fade-in-on-load">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h2 class="fw-bold text-secondary">Available Donors</h2>
            <div>
                <a href="search.php" class="btn btn-outline-danger">Advanced Search</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8 mb-3">
                <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Quick Search (Name or Location)...">
            </div>
            <div class="col-md-4 mb-3">
                <select id="bgFilter" class="form-select form-select-lg">
                    <option value="All">All Blood Groups</option>
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
        </div>

        <p class="text-muted mb-4 border-start border-4 border-danger ps-3 py-1">
            Browse through our verified directory of donors. Use the Advanced Search page for more options.
        </p>

        <!-- Container for dynamically rendering donors -->
        <div class="row" id="publicDonorList">
            <?php if ($donors_query->num_rows > 0): ?>
                <?php while($row = $donors_query->fetch_assoc()): ?>
                    <?php
                        // Eligibility Logic
                        $eligible = true;
                        $status_html = '<span class="badge bg-success">Available</span>';
                        if ($row['last_donation_date']) {
                            $last_date = new DateTime($row['last_donation_date']);
                            $today = new DateTime();
                            $diff = $today->diff($last_date);
                            $months = ($diff->format('%y') * 12) + $diff->format('%m');
                            if ($months < 3) {
                                $eligible = false;
                                $status_html = '<span class="badge bg-warning text-dark">On Wait</span>';
                            }
                        }
                    ?>
                    <div class="col-md-4 mb-4 donor-card" data-bg="<?php echo htmlspecialchars($row['blood_group']); ?>">
                        <div class="card shadow-sm h-100 border-start border-4 border-danger" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'" onclick="window.location.href='donor-profile.php?id=<?php echo htmlspecialchars($row['donor_id']); ?>'">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($row['name']); ?></h5>
                                    <?php echo $status_html; ?>
                                </div>
                                <h6 class="card-subtitle mb-3 text-danger fw-bold">Blood Group: <?php echo htmlspecialchars($row['blood_group']); ?></h6>
                                <p class="card-text mb-1"><small class="text-muted">Location:</small> <?php echo htmlspecialchars($row['location']); ?></p>
                                <p class="card-text mb-1"><small class="text-muted">Last Donated:</small> 
                                    <span class="text-primary fw-bold"><?php echo $row['last_donation_date'] ? date('d M, Y', strtotime($row['last_donation_date'])) : 'N/A'; ?></span>
                                </p>
                                <p class="card-text mb-1"><small class="text-muted">Contact:</small> <?php echo htmlspecialchars($row['phone']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    <p>No donors available.</p>
                </div>
            <?php endif; ?>
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
    <script src="js/app.js"></script>
    <script>
        $(document).ready(function () {
            function filterDonors() {
                let query = $('#searchInput').val().toLowerCase();
                let bgFilter = $('#bgFilter').val();

                $('.donor-card').each(function () {
                    let cardText = $(this).text().toLowerCase();
                    let cardBG = $(this).data('bg');
                    
                    let matchesSearch = cardText.includes(query);
                    let matchesBG = (bgFilter === 'All' || cardBG === bgFilter);

                    if (matchesSearch && matchesBG) {
                        $(this).fadeIn();
                    } else {
                        $(this).fadeOut();
                    }
                });
            }

            $('#searchInput').on('keyup', filterDonors);
            $('#bgFilter').on('change', filterDonors);
        });
    </script>
</body>
</html>
