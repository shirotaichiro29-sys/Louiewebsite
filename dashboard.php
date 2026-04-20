<?php
require 'config.php';
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

$course_filter = isset($_GET['course']) ? $mysqli->real_escape_string($_GET['course']) : '';
$gender_filter = isset($_GET['gender']) ? $mysqli->real_escape_string($_GET['gender']) : '';

$q = "SELECT * FROM students WHERE 1";
if ($course_filter) $q .= " AND course='$course_filter'";
if ($gender_filter) $q .= " AND gender='$gender_filter'";

$res = $mysqli->query($q);
$courses = $mysqli->query("SELECT DISTINCT course FROM students");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard - SUNN</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="dashboard-bg">

<!-- NAVBAR WITH LOGO -->
<nav class="navbar navbar-expand-lg shadow-sm">
  <div class="container-fluid">

    <a class="navbar-brand d-flex align-items-center fw-bold" href="#">
        <img src="assets/img/logo.png" style="height:50px;" class="me-2">
        <span>STATE UNIVERSITY OF NORTHERN NEGROS</span>
    </a>

    <div class="d-flex gap-2">
        <a class="btn btn-success" href="student_form.php">
            <i class="bi bi-plus-lg"></i> Add Student
        </a>
        <a class="btn btn-danger" href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

  </div>
</nav>

<div class="container mt-4">

  <div class="card p-3 shadow-sm mb-3">
    <div class="row g-2">

        <div class="col-md-4">
            <input id="searchBox" class="form-control" placeholder="Search student...">
        </div>

        <div class="col-md-3">
            <select id="courseFilter" class="form-select">
              <option value="">All Courses</option>
              <?php while($c = $courses->fetch_assoc()): ?>
                <option value="<?php echo $c['course']; ?>">
                    <?php echo $c['course']; ?>
                </option>
              <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-3">
            <select id="genderFilter" class="form-select">
              <option value="">All Genders</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
        </div>

    </div>
  </div>

  <!-- STUDENT TABLE -->
  <div class="card shadow-sm">
    <div class="card-body p-0">

      <table id="studentsTable" class="display table table-bordered table-hover mb-0">
        <thead>
          <tr>
            <th>No.</th>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Course</th>
            <th>Contact</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
        <?php while($row = $res->fetch_assoc()): ?>

          <?php
            $g = strtolower($row['gender']);
            $icon = "bi-gender-ambiguous";
            $color = "text-secondary";

            if ($g === "male") { $icon="bi-gender-male"; $color="text-primary"; }
            if ($g === "female") { $icon="bi-gender-female"; $color="text-danger"; }
          ?>

          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['student_number']); ?></td>

            <td>
              <span class="icon-circle <?php echo $color; ?>">
                <i class="bi <?php echo $icon; ?>"></i>
              </span>
              <?php echo htmlspecialchars($row['name']); ?>
            </td>

            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['age']); ?></td>
            <td><?php echo htmlspecialchars($row['course']); ?></td>
            <td><?php echo htmlspecialchars($row['contact']); ?></td>

            <td>
              <a class="btn btn-primary btn-sm" href="student_form.php?id=<?php echo $row['id']; ?>">
                <i class="bi bi-pencil"></i>
              </a>
              <a class="btn btn-danger btn-sm delete-link" href="delete_student.php?id=<?php echo $row['id']; ?>">
                <i class="bi bi-trash"></i>
              </a>
              <a class="btn btn-info btn-sm" href="medical_records.php?student_id=<?php echo $row['id']; ?>">
                <i class="bi bi-file-medical"></i>
              </a>
            </td>
          </tr>

        <?php endwhile; ?>
        </tbody>

      </table>

    </div>
  </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
// Initialize DataTable
$(document).ready(function(){
  var table = $('#studentsTable').DataTable({
    pageLength: 10
  });

  // Search box
  $('#searchBox').on('keyup', function(){
    table.search(this.value).draw();
  });

  // Course filter
  $('#courseFilter').on('change', function(){
    table.column(5).search(this.value).draw();
  });

  // Gender filter
  $('#genderFilter').on('change', function(){
    table.column(3).search(this.value).draw();
  });

  // Confirm delete globally for dynamic table
  $('#studentsTable').on('click', 'a.delete-link', function(e){
    if(!confirm('Are you sure? This action cannot be undone.')) {
      e.preventDefault(); // stop deletion if canceled
    }
  });
});
</script>

</body>
</html>
