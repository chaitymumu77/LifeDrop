<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = strip_tags(trim($_POST["patient_name"]));
    $blood_group = strip_tags(trim($_POST["blood_group"]));
    $hospital = strip_tags(trim($_POST["hospital"]));
    $units = intval($_POST["units"]);
    $phone = strip_tags(trim($_POST["phone"]));

    if (empty($patient_name) || empty($blood_group) || empty($phone)) {
        http_response_code(400);
        echo "Please fill in all required fields.";
        exit;
    }

    // Insert into blood_requests table
    $stmt = $conn->prepare("INSERT INTO blood_requests (patient_name, blood_group, hospital, units, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $patient_name, $blood_group, $hospital, $units, $phone);
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo "Success! Your blood request has been submitted and saved.";
    } else {
        http_response_code(500);
        echo "Error: Could not save your request. Please try again.";
    }
    $stmt->close();
} else {
    http_response_code(403);
    echo "Direct access not allowed.";
}
?>
