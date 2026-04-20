<?php
include 'db.php';

$full_name = $_POST['full_name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$contact = $_POST['contact'];
$address = $_POST['address'];

// Validate contact number (must be 11 digits)
if (!preg_match('/^[0-9]{11}$/', $contact)) {
    die("Invalid contact number. It must be exactly 11 digits.");
}

// Insert data
$stmt = $conn->prepare("INSERT INTO patients (full_name, age, gender, contact, address, date_registered) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sisss", $full_name, $age, $gender, $contact, $address);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
