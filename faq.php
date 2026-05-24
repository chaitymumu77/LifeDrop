
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - FAQ</title>
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
        <h2 class="fw-bold text-center mb-5 text-primary-custom">Frequently Asked Questions</h2>

        <!-- Tabs Implementation (JS/jQuery) -->
        <ul class="nav nav-tabs mb-4 justify-content-center" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general"
                    type="button">General</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="donor-tab" data-bs-toggle="tab" data-bs-target="#donor"
                    type="button">For Donors</button>
            </li>
        </ul>

        <div class="tab-content row justify-content-center" id="myTabContent">
            <!-- Tab 1 -->
            <div class="tab-pane fade show active col-md-8" id="general" role="tabpanel">
                <div class="accordion" id="accordionGeneral">
                    <div class="accordion-item shadow-sm mb-2 border-0 rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#col1">
                                What is LifeDrop?
                            </button>
                        </h2>
                        <div id="col1" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                            <div class="accordion-body text-muted">
                                LifeDrop is a modern blood management system that uses JSON, AJAX, and LocalStorage to
                                securely manage and connect potential donors to the people in need.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item shadow-sm mb-2 border-0 rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#col2">
                                Is it free to use?
                            </button>
                        </h2>
                        <div id="col2" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                            <div class="accordion-body text-muted">
                                Yes! LifeDrop is fully free. Our goal is to save lives without any cost barrier.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2 -->
            <div class="tab-pane fade col-md-8" id="donor" role="tabpanel">
                <div class="accordion" id="accordionDonor">
                    <div class="accordion-item shadow-sm mb-2 border-0 rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-danger" type="button"
                                data-bs-toggle="collapse" data-bs-target="#col3">
                                How often can I donate blood?
                            </button>
                        </h2>
                        <div id="col3" class="accordion-collapse collapse" data-bs-parent="#accordionDonor">
                            <div class="accordion-body text-muted">
                                Generally, you can safely donate blood every 3 to 4 months. Please consult a doctor for
                                personalized advice.
                            </div>
                        </div>
                    </div>
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
    <script src="js/app.js"></script>
</body>

</html>
