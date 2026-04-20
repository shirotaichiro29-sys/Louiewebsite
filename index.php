<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Clinic Patient Record System</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #e67af6ff; text-align: left; }
        th { background: #ac3ef1ff; color: white; }
        input, select, textarea { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { background: #ac3ef1ff; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        button:hover { background: #ac3ef1ff; }
    </style>
</head>
<body>

<h2>Clinic Patient Record System</h2>

<form action="add.php" method="POST">
    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="number" name="age" placeholder="Age" required>
    <select name="gender" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>
    <!-- Contact field with 11-digit validation -->
    <input type="tel" name="contact" placeholder="Contact Number" 
           pattern="[0-9]{11}" maxlength="11" required 
           title="Contact number must be exactly 11 digits">
    <textarea name="address" placeholder="Address" required></textarea>
    <button type="submit">Add Patient</button>
</form>

<table>
    <tr>
        <th>No.</th>
        <th>Full Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Contact</th>
        <th>Address</th>
        <th>Date Registered</th>
        <th>Action</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM patients ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>".$row['id']."</td>
            <td>".$row['full_name']."</td>
            <td>".$row['age']."</td>
            <td>".$row['gender']."</td>
            <td>".$row['contact']."</td>
            <td>".$row['address']."</td>
            <td>".$row['date_registered']."</td>
            <td>
                <a href='edit.php?id=".$row['id']."'>Edit</a> |
                <a href='delete.php?id=".$row['id']."' onclick='return confirm(\"Are you sure you want to delete this patient record?\")'>Delete</a>
            </td>
        </tr>";
    }
    ?>
</table>

</body>
</html>
