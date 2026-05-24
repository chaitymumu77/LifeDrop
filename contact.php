
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - Contact Us</title>
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
                    <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="notice.php">Notice</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content container py-5 fade-in-on-load">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary-custom">Contact Our Team</h2>
            <p class="text-muted">We are here to help you 24/7. Feel free to reach out.</p>
        </div>

        <div class="row g-5">
            <!-- Contact Info Cards -->
            <div class="col-md-5">
                <div class="d-flex flex-column gap-4">
                    <div class="card p-4 shadow-sm border-0 bg-white hover-up">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary-custom text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                📞
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Call Us</h6>
                                <p class="mb-0 text-muted">+880 1581230398</p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-4 shadow-sm border-0 bg-white hover-up">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary-custom text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                📧
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Email Address</h6>
                                <p class="mb-0 text-muted">admin@gmail.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-4 shadow-sm border-0 bg-white hover-up">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary-custom text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                📍
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Our Location</h6>
                                <p class="mb-0 text-muted">Chattogram, Bangladesh</p>
                            </div>
                        </div>
                    </div>

                    <!-- AJAX Partner Hospitals Mini List -->
                    <div class="mt-2">
                        <h6 class="fw-bold mb-3">Partner Hospitals (JSON)</h6>
                        <ul class="list-group list-group-flush small" id="hospitalList">
                            <div class="spinner-border spinner-border-sm text-danger" role="status"></div>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Blood Request Form -->
            <div class="col-md-7">
                <div class="card p-5 shadow-lg border-0 bg-white" style="border-radius: 20px;">
                    <h4 class="fw-bold mb-4">Request Blood Urgently 🩸</h4>
                    <div id="requestAlert" class="alert" style="display:none;"></div>

                    <form id="bloodRequestForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Patient Name</label>
                                <input type="text" class="form-control bg-light border-0 shadow-none" id="patientName" placeholder="Enter patient name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Blood Group Needed</label>
                                <select class="form-select bg-light border-0 shadow-none" id="bloodGroup" required>
                                    <option value="">Select Group</option>
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Hospital Name</label>
                                <input type="text" class="form-control bg-light border-0 shadow-none" id="hospitalName" placeholder="Hospital/Clinic name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Units/Bags Needed</label>
                                <input type="number" class="form-control bg-light border-0 shadow-none" id="bloodUnits" placeholder="e.g. 2" min="1" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Contact Phone Number</label>
                            <input type="text" class="form-control bg-light border-0 shadow-none" id="contactPhone" placeholder="Mobile number" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-3 fw-bold shadow-sm">
                            Submit Emergency Request 🚨
                        </button>
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
    <script src="js/app.js?v=1.2"></script>
    <script src="js/crud.js?v=1.2"></script>
</body>

</html>
