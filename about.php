
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - About Us</title>
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
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <img src="https://images.unsplash.com/photo-1579154204601-01588f351e67?q=80&w=800&auto=format&fit=crop"
                    class="img-fluid rounded shadow" alt="About LifeDrop">
            </div>
            <div class="col-md-6">
                <h1 class="text-primary-custom fw-bold mb-4">Who We Are</h1>
                <p class="lead text-secondary">
                    LifeDrop is a non-profit, student-led open source project aimed at connecting blood donors to
                    patients effortlessly.
                </p>
                <p>
                    Our system leverages dynamic Web Technologies (HTML, Custom CSS, Bootstrap 5, Javascript, jQuery,
                    AJAX) to ensure everyone has access to critical blood resources without delays.
                </p>

                <hr>

                <h3 class="mt-4 text-dark">Our Mission</h3>
                <ul>
                    <li>Create an accessible list of ready donors.</li>
                    <li>Spread awareness regarding the shortage of specific blood groups.</li>
                    <li>Utilize data and modern technology to save lives.</li>
                </ul>
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
    <script src="js/crud.js"></script>
</body>

</html>
