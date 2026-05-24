<?php
session_start();
require_once 'db_connect.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
} elseif (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (isset($_POST['login_user'])) {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = 'user'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashed_password, $role);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['role'] = $role;
                header("Location: profile.php");
                exit();
            } else {
                $error_msg = "Invalid password.";
            }
        } else {
            $error_msg = "No user found with this email.";
        }
        $stmt->close();
    } elseif (isset($_POST['login_admin'])) {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = 'admin'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashed_password, $role);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_name'] = $name;
                $_SESSION['role'] = $role;
                header("Location: dashboard.php"); // Will need to convert dashboard.php to .php later
                exit();
            } else {
                $error_msg = "Invalid password.";
            }
        } else {
            $error_msg = "No admin found with this email.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - Login</title>
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
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <div class="text-center mb-4">
                        <h2 class="text-primary-custom fw-bold">Welcome Back!</h2>
                        <p class="text-muted">Login to manage your profile</p>
                    </div>

                    <?php if ($error_msg != ""): ?>
                        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                    <?php endif; ?>

                    <!-- Let's add tabs for User and Admin Login -->
                    <ul class="nav nav-pills nav-justified mb-4" id="loginTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold" id="user-tab" data-bs-toggle="pill" data-bs-target="#user-login" type="button" role="tab" aria-controls="user-login" aria-selected="true">User Login</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="admin-tab" data-bs-toggle="pill" data-bs-target="#admin-login" type="button" role="tab" aria-controls="admin-login" aria-selected="false">Admin Login</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="loginTabsContent">
                        <!-- User Login Form -->
                        <div class="tab-pane fade show active" id="user-login" role="tabpanel" aria-labelledby="user-tab">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control" required placeholder="Enter email">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Password</label>
                                    <input type="password" name="password" class="form-control" required placeholder="Password">
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <div>
                                        <input type="checkbox" id="userRememberMe"> <label for="userRememberMe">Remember me</label>
                                    </div>
                                    <a href="#" class="text-decoration-none">Forgot Password?</a>
                                </div>
                                <button type="submit" name="login_user" class="btn btn-primary bg-primary-custom w-100">Login as User</button>
                            </form>
                        </div>

                        <!-- Admin Login Form -->
                        <div class="tab-pane fade" id="admin-login" role="tabpanel" aria-labelledby="admin-tab">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Admin Email</label>
                                    <input type="email" name="email" class="form-control" required placeholder="Enter Admin Email">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Password</label>
                                    <input type="password" name="password" class="form-control" required placeholder="Password">
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <div>
                                        <input type="checkbox" id="adminRememberMe"> <label for="adminRememberMe">Remember me</label>
                                    </div>
                                    <a href="#" class="text-decoration-none">Forgot Password?</a>
                                </div>
                                <button type="submit" name="login_admin" class="btn btn-danger w-100">Login as Admin</button>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-4 border-top pt-3">
                        Not registered? <a href="register.php" class="fw-bold text-decoration-none">Create an
                            Account</a>
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
</body>
</html>
