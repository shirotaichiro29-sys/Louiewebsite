<?php
include 'db.php';

// Get the patient ID from the URL
$id = $_GET['id'];

// Get the patient data from the database
$result = $conn->query("SELECT * FROM patients WHERE id = $id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        input, select, textarea { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { background: #ac3ef1ff; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        a { text-decoration: none; color: #ac3ef1ff; }
    </style>
</head>
<body>

<h2>Edit Patient Record</h2>

<form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <input type="text" name="full_name" value="<?php echo $row['full_name']; ?>" required>
    <input type="number" name="age" value="<?php echo $row['age']; ?>" required>

    <select name="gender" required>
        <option value="Male" <?php if($row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if($row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
    </select>

    <input type="text" name="contact" value="<?php echo $row['contact']; ?>" required>
    <textarea name="address" required><?php echo $row['address']; ?></textarea>

    <button type="submit">Update Patient</button>
    <a href="index.php">Cancel</a>
</form>

</body>
</html>
