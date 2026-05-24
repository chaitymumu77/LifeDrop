
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
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
    
    <div class="main-content">
        <!-- Hero Section -->
        <section class="hero-section fade-in-on-load">
            <div class="container">
                <h1>Donate Blood, Save a Life ❤️</h1>
                <p>Your one drop of blood can give someone a second chance at life.</p>
                <div class="mt-4">
                    <a href="register.php" class="btn btn-custom-light mx-2">Become a Donor</a>
                    <a href="donor-list.php" class="btn btn-outline-light btn-lg mx-2" style="border-radius:30px;">Find
                        Donor</a>
                </div>
            </div>
        </section>

        <!-- Dynamic Data Section via Bootstrap Grid & Cards -->
        <section class="container py-5 my-4 fade-in-on-load">
            <h2 class="text-center mb-5 text-primary-custom fw-bold">Why Choose LifeDrop?</h2>
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <h1 class="text-danger mb-3">🩸</h1>
                        <h4 class="card-title">Quick Search</h4>
                        <p class="card-text text-muted">Find matching blood donors efficiently based on your location
                            and requirements.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <h1 class="text-danger mb-3">🛡️</h1>
                        <h4 class="card-title">Verified Data</h4>
                        <p class="card-text text-muted">Our admin strictly manages and verifies donor data ensuring
                            reliability.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <h1 class="text-danger mb-3">📢</h1>
                        <h4 class="card-title">Stay Updated</h4>
                        <p class="card-text text-muted">Get real-time emergency alerts and donor updates through our integrated notice system.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-0">© 2026 LifeDrop Blood Management System.</p>
            <small class="text-muted">Save Life. Donate Blood.</small>
        </div>
    </footer>

    <!-- Scripts (jQuery + Bootstrap + Custom) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js?v=1.2"></script>
</body>

</html>
