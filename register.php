<?php
session_start();
require_once 'db_connect.php';

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $location = $_POST['location'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $smoking_status = ($_POST['smoking_status'] == 'Yes') ? 1 : 0;
    $disease_history = $_POST['disease_history'];

    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error_msg = "An account with this email is already registered!";
    } else {
        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $name, $email, $password);
        
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Insert into donors table
            $stmt2 = $conn->prepare("INSERT INTO donors (user_id, blood_group, age, weight, gender, phone, location, smoking_status, disease_history) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("isidsssis", $user_id, $blood_group, $age, $weight, $gender, $phone, $location, $smoking_status, $disease_history);
            
            if ($stmt2->execute()) {
                $success_msg = "Successfully registered as a donor! You can now <a href='login.php'>Login here</a>.";
            } else {
                $error_msg = "Error registering donor details: " . $stmt2->error;
            }
            $stmt2->close();
        } else {
            $error_msg = "Error creating account: " . $stmt->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeDrop - Register Donor</title>
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
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary-custom text-white py-3">
                        <h4 class="mb-0 text-center">Register As A Blood Donor</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if ($success_msg != ""): ?>
                            <div class="alert alert-success"><?php echo $success_msg; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error_msg != ""): ?>
                            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <h5 class="mb-3 border-bottom pb-2">Account Information</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. Salim Mahmud">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control" required placeholder="Enter email">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Password</label>
                                    <input type="password" name="password" class="form-control" required placeholder="Create a password">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" required placeholder="+880...">
                                </div>
                            </div>

                            <h5 class="mb-3 border-bottom pb-2">Donor Information</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Blood Group</label>
                                    <select name="blood_group" class="form-select" required>
                                        <option value="" disabled selected>Select</option>
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
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Gender</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="" disabled selected>Select</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Location (City)</label>
                                    <input type="text" name="location" class="form-control" required placeholder="e.g. Dhaka">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Age</label>
                                    <input type="number" name="age" class="form-control" required min="18" max="65" placeholder="Your age">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Weight (kg)</label>
                                    <input type="number" name="weight" step="0.1" class="form-control" required min="45" placeholder="e.g. 60">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Do you smoke?</label>
                                    <select name="smoking_status" class="form-select" required>
                                        <option value="" disabled selected>Select</option>
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Disease/Medication Details (Optional)</label>
                                    <input type="text" name="disease_history" class="form-control" placeholder="e.g. Asthma, None">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 py-2 fs-5 fw-bold" style="border-radius: 8px;">Register Now</button>
                        </form>
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
