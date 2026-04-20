<?php
include 'db.php';

$id = $_POST['id'];
$full_name = $_POST['full_name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$contact = $_POST['contact'];
$address = $_POST['address'];

$sql = "UPDATE patients 
        SET full_name='$full_name', age='$age', gender='$gender', contact='$contact', address='$address' 
        WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error updating record: " . $conn->error;
}
?>
