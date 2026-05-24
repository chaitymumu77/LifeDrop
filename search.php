<?php
session_start();
require_once 'db_connect.php';

// Fetch all donors from database to allow live filtering
$donors_query = $conn->query("
    SELECT d.id as donor_id, u.name, d.blood_group, d.location, d.phone, d.last_donation_date 
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
    <title>LifeDrop - Search Donor</title>
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
                    <li class="nav-item"><a class="nav-link" href="donor-list.php">Find Donor</a></li>
                    <li class="nav-item"><a class="nav-link active" href="search.php">Search</a></li>
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
        <div class="card shadow-sm border-0 mb-4 p-4">
            <h3 class="fw-bold text-primary-custom mb-3">Live Search & Filter</h3>
            <p class="text-muted">Search through our database by name, location, or blood group instantly.</p>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <input type="text" id="searchInput" class="form-control form-control-lg"
                        placeholder="Type name or location (e.g. Dhaka or Salim)...">
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
        </div>

        <div class="row" id="publicDonorList">
            <?php if ($donors_query && $donors_query->num_rows > 0): ?>
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
                    <div class="col-md-4 mb-3 donor-card" data-bg="<?php echo htmlspecialchars($row['blood_group']); ?>" data-loc="<?php echo htmlspecialchars(strtolower($row['location'])); ?>">
                        <div class="card h-100 shadow-sm border-top border-danger border-4" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'" onclick="window.location.href='donor-profile.php?id=<?php echo $row['donor_id']; ?>'">
                            <div class="card-body text-center">
                                <div class="mb-2"><?php echo $status_html; ?></div>
                                <h2 class="text-danger fw-bold mb-1"><?php echo htmlspecialchars($row['blood_group']); ?></h2>
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text text-muted mb-2">
                                    <i class="text-secondary">📍 <?php echo htmlspecialchars($row['location']); ?></i><br>
                                    <small>Last Donated: <?php echo $row['last_donation_date'] ? date('d M, Y', strtotime($row['last_donation_date'])) : 'N/A'; ?></small>
                                </p>
                                <button class="btn btn-outline-danger btn-sm w-100">View Details</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    <p>No donors found in the database.</p>
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
            // Search Input Filter
            $('#searchInput').on('keyup', function () {
                filterDonors();
            });

            // Blood Group Filter
            $('#bgFilter').on('change', function () {
                filterDonors();
            });

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
        });
    </script>
</body>

</html>

