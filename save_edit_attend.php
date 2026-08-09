<?php
include('session.php');

// Connect to database (MySQLi)
$conn = new mysqli("localhost", "root", "", "prisons");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get prison ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle form submission
if (isset($_POST["submit"])) {
    $attendance = $_POST["Attendance"];

    if ($attendance === '') {
        echo "<div class='alert alert-danger'>You must select attendance</div>";
    } else {
        $stmt = $conn->prepare("UPDATE attendance SET Attendance=?, Date=NOW() WHERE prison_ID=?");
        $stmt->bind_param("si", $attendance, $id);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">
                    <strong>Success!</strong> Attendance updated.
                    <a href="attendance.php" class="btn btn-success">Continue</a>
                  </div>';
            exit();
        } else {
            echo '<div class="alert alert-danger">
                    <strong>Error!</strong> ' . htmlspecialchars($stmt->error) . '
                    <a href="attendance.php" class="btn btn-danger">Continue</a>
                  </div>';
            exit();
        }
        $stmt->close();
    }
}

// Fetch attendance record
$stmt = $conn->prepare("SELECT * FROM attendance WHERE prison_ID=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Prison Management System</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container-fluid" style="margin-top:70px;">
    <h1>Update Attendance</h1>

    <?php if ($row): ?>
    <form method="post" class="form-horizontal">
        <div class="form-group">
            <label class="col-md-2 control-label">Prison ID</label>
            <div class="col-md-2">
                <input type="text" class="form-control" value="<?= htmlspecialchars($row['prison_ID']) ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">Name</label>
            <div class="col-md-3">
                <input type="text" class="form-control" value="<?= htmlspecialchars($row['prison_fname']) ?>" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" value="<?= htmlspecialchars($row['prison_mname']) ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">Attendance</label>
            <div class="col-md-2">
                <select name="Attendance" class="form-control" required>
                    <option value="Absent" <?= $row['Attendance']=='Absent'?'selected':'' ?>>Absent</option>
                    <option value="Present" <?= $row['Attendance']=='Present'?'selected':'' ?>>Present</option>
                    <option value="Permission" <?= $row['Attendance']=='Permission'?'selected':'' ?>>Permission</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                <a href="attendance.php" class="btn btn-default">Cancel</a>
            </div>
        </div>
    </form>
    <?php else: ?>
        <div class="alert alert-warning">Attendance record not found.</div>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>
</body>
</html>
