<?php
require 'config.php';
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$student_number=''; $name=''; $age=''; $course=''; $contact=''; $gender='';

if ($id) {
  $r = $mysqli->query("SELECT * FROM students WHERE id=$id");
  if ($r && $r->num_rows) {
    $d = $r->fetch_assoc();
    $student_number = $d['student_number'];
    $name = $d['name'];
    $age = $d['age'];
    $course = $d['course'];
    $contact = $d['contact'];
    $gender = $d['gender'];
  }
}

$msg='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $student_number = $mysqli->real_escape_string($_POST['student_number']);
  $name = $mysqli->real_escape_string($_POST['name']);
  $age = intval($_POST['age']);
  $course = $mysqli->real_escape_string($_POST['course']);
  $contact = $mysqli->real_escape_string($_POST['contact']);
  $gender = $mysqli->real_escape_string($_POST['gender']);

  // Validate contact length
  if(strlen($contact) != 11 || !ctype_digit($contact)){
      $msg = 'Contact must be exactly 11 digits.';
  } else {
      if ($id) {
        $mysqli->query("UPDATE students SET student_number='$student_number', name='$name', gender='$gender', age=$age, course='$course', contact='$contact' WHERE id=$id");
        $msg = 'Updated successfully';
      } else {
        $mysqli->query("INSERT INTO students (student_number,name,gender,age,course,contact) VALUES ('$student_number','$name','$gender',$age,'$course','$contact')");
        $msg = 'Added successfully';
      }
      header('Location: dashboard.php');
      exit;
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $id ? 'Edit Student' : 'Add Student'; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-theme-light">
<div class="container mt-4">
  <a class="btn btn-link mb-3" href="dashboard.php">&larr; Back to Students</a>
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="mb-3"><?php echo $id ? 'Edit Student' : 'Add Student'; ?></h4>
      <?php if($msg): ?>
        <div class="alert alert-warning"><?php echo $msg; ?></div>
      <?php endif; ?>
      <form method="post" onsubmit="return validateForm()">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Student ID</label>
            <input name="student_number" id="student_number" class="form-control" value="<?php echo htmlspecialchars($student_number); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Full Name</label>
            <input name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-select">
              <option value="">Select Gender</option>
              <option value="Male" <?php if($gender=='Male') echo 'selected'; ?>>Male</option>
              <option value="Female" <?php if($gender=='Female') echo 'selected'; ?>>Female</option>
              <option value="Other" <?php if($gender=='Other') echo 'selected'; ?>>Other</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Age</label>
            <input name="age" id="age" type="number" class="form-control" value="<?php echo htmlspecialchars($age); ?>">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Course</label>
            <select name="course" id="course" class="form-select" required>
              <option value="">Select Course</option>
              <?php 
                $courses_list = ['BSIS', 'BSIT', 'BSEMC', 'BLIS', 'BSBIO', 'ABELS', 'BSF','Others'];
                foreach($courses_list as $c):
              ?>
                <option value="<?php echo $c; ?>" <?php if($course==$c) echo 'selected'; ?>><?php echo $c; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-12 mb-3">
            <label class="form-label">Contact No.</label>
            <input
              name="contact"
              id="contact"
              class="form-control"
              value="<?php echo htmlspecialchars($contact); ?>"
              pattern="\d{11}"
              maxlength="11"
              minlength="11"
              title="Contact number must be exactly 11 digits"
              required
            >
          </div>
        </div>

        <div class="d-flex gap-2">
          <button class="btn btn-success"><?php echo $id ? 'Update' : 'Add'; ?></button>
          <a class="btn btn-secondary" href="dashboard.php">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function validateForm(){
  if(!document.getElementById('student_number').value.trim() || !document.getElementById('name').value.trim()){
    alert('Please fill required fields');
    return false;
  }
  var contact = document.getElementById('contact').value.trim();
  if(!/^\d{11}$/.test(contact)){
    alert('Contact must be exactly 11 digits.');
    return false;
  }
  return true;
}

// Optional: restrict input to digits while typing
document.getElementById('contact').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g,'').slice(0,11);
});
</script>
</body>
</html>
