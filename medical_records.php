<?php
require 'config.php';
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
if (!$student_id) { header('Location: dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $checkup = $mysqli->real_escape_string($_POST['checkup_date']);
  $symptoms = $mysqli->real_escape_string($_POST['symptoms']);
  $diagnosis = $mysqli->real_escape_string($_POST['diagnosis']);
  $treatment = $mysqli->real_escape_string($_POST['treatment']);
  $med = $mysqli->real_escape_string($_POST['medication']);
  $file_path = null;
  if (!empty($_FILES['file']['name'])) {
    $updir = 'uploads/';
    if (!is_dir($updir)) mkdir($updir, 0777, true);
    $fname = time().'_'.basename($_FILES['file']['name']);
    $target = $updir.$fname;
    if (move_uploaded_file($_FILES['file']['tmp_name'],$target)) {
      $file_path = $mysqli->real_escape_string($target);
    }
  }
  $mysqli->query("INSERT INTO medical_records (student_id, checkup_date, symptoms, diagnosis, treatment, medication, file_path) VALUES ($student_id,'$checkup','$symptoms','$diagnosis','$treatment','$med',".($file_path?"'{$file_path}'":"NULL").")");
  header('Location: medical_records.php?student_id='.$student_id);
  exit;
}

$student = $mysqli->query("SELECT * FROM students WHERE id=$student_id")->fetch_assoc();
$records = $mysqli->query("SELECT * FROM medical_records WHERE student_id=$student_id ORDER BY checkup_date DESC");
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Medical Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head><body class="bg-theme-light">
<div class="container mt-4">
  <a href="dashboard.php" class="btn btn-link">&larr; Back to Students</a>
  <h4>Medical Records for: <?php echo htmlspecialchars($student['name']); ?> <small class="text-muted">(<?php echo htmlspecialchars($student['gender']); ?>)</small></h4>

  <div class="card mb-3">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data" onsubmit="return validateMed()">
        <div class="row">
          <div class="col-md-3 mb-2">
            <label>Date</label>
            <input type="date" name="checkup_date" class="form-control" required>
          </div>
          <div class="col-md-3 mb-2">
            <label>Symptoms</label>
            <input name="symptoms" class="form-control">
          </div>
          <div class="col-md-3 mb-2">
            <label>Diagnosis</label>
            <input name="diagnosis" class="form-control">
          </div>
          <div class="col-md-3 mb-2">
            <label>Treatment</label>
            <input name="treatment" class="form-control">
          </div>
        </div>
        <div class="mb-2">
          <label>Medication</label>
          <input name="medication" class="form-control">
        </div>
        <div class="mb-2">
          <label>Attach file (certificate, prescription)</label>
          <input type="file" name="file" class="form-control">
        </div>
        <button class="btn btn-primary">Add Record</button>
      </form>
    </div>
  </div>

  <h5>Previous Records</h5>
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
        <thead><tr><th>Date</th><th>Symptoms</th><th>Diagnosis</th><th>Treatment</th><th>Medication</th><th>File</th></tr></thead>
        <tbody>
        <?php while($r = $records->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($r['checkup_date']); ?></td>
            <td><?php echo htmlspecialchars($r['symptoms']); ?></td>
            <td><?php echo htmlspecialchars($r['diagnosis']); ?></td>
            <td><?php echo htmlspecialchars($r['treatment']); ?></td>
            <td><?php echo htmlspecialchars($r['medication']); ?></td>
            <td><?php if($r['file_path']): ?><a target="_blank" href="<?php echo $r['file_path']; ?>">View</a><?php else: echo '-'; endif; ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
function validateMed(){ return true; }
</script>
</body></html>
