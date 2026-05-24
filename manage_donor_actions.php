<?php
session_start();
require_once 'db_connect.php';

//////////////////////////////////////////////////
// ✅ SESSION CHECK (FIRST)
//////////////////////////////////////////////////
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

//////////////////////////////////////////////////
// ✅ DELETE (GET REQUEST)
//////////////////////////////////////////////////
if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM donors WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage.php?msg=Donor deleted successfully");
        exit(); // 🔥 VERY IMPORTANT
    } else {
        echo "Error: " . $stmt->error;
        exit();
    }
}

//////////////////////////////////////////////////
// ✅ POST REQUEST (ADD / EDIT)
//////////////////////////////////////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];

    //////////////////////////////////////////////////
    // ✅ ADD DONOR
    //////////////////////////////////////////////////
    if ($action == 'add') {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'];
        $blood_group = $_POST['blood_group'];
        $location = $_POST['location'];
        $age = $_POST['age'];
        $weight = $_POST['weight'];
        $smoking_status = ($_POST['smoking_status'] == 'Yes') ? 1 : 0;
        $disease_history = $_POST['disease_history'];

        // Insert into users
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Insert into donors
            $stmt2 = $conn->prepare("INSERT INTO donors (user_id, blood_group, age, weight, gender, phone, location, smoking_status, disease_history) VALUES (?, ?, ?, ?, 'male', ?, ?, ?, ?)");
            $stmt2->bind_param("isidssis", $user_id, $blood_group, $age, $weight, $phone, $location, $smoking_status, $disease_history);

            if ($stmt2->execute()) {
                header("Location: manage.php?msg=Donor added successfully");
                exit();
            } else {
                header("Location: manage.php?msg=Error adding donor");
                exit();
            }

            $stmt2->close();
        } else {
            header("Location: manage.php?msg=Error creating user");
            exit();
        }

        $stmt->close();
    }

    //////////////////////////////////////////////////
    // ✅ EDIT DONOR
    //////////////////////////////////////////////////
    elseif ($action == 'edit') {

        $id = $_POST['id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $blood_group = $_POST['blood_group'];
        $location = $_POST['location'];

        // Update donors
        $stmt = $conn->prepare("UPDATE donors SET blood_group = ?, phone = ?, location = ? WHERE id = ?");
        $stmt->bind_param("sssi", $blood_group, $phone, $location, $id);

        if ($stmt->execute()) {

            // Update user name
            $stmt2 = $conn->prepare("UPDATE users u JOIN donors d ON u.id = d.user_id SET u.name = ? WHERE d.id = ?");
            $stmt2->bind_param("si", $name, $id);
            $stmt2->execute();
            $stmt2->close();

            header("Location: manage.php?msg=Donor updated successfully");
            exit();

        } else {
            header("Location: manage.php?msg=Error updating donor");
            exit();
        }

        $stmt->close();
    }
}
?>