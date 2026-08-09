<?php
include('session.php');
include('DB.php'); // $conn is your mysqli connection

/* ===============================
   HANDLE FORM SUBMISSION
================================ */
$prison_data = null;
if (isset($_POST['fetch_prisoner'])) {
    // User submitted Prison ID to fetch prisoner
    $prison_ID_input = (int) $_POST['prison_ID_input'];

    $stmt = $conn->prepare("SELECT * FROM prisoner WHERE prison_ID = ?");
    $stmt->bind_param("i", $prison_ID_input);
    $stmt->execute();
    $result = $stmt->get_result();
    $prison_data = $result->fetch_assoc();

    if (!$prison_data) {
        $error = "No prisoner found with ID $prison_ID_input";
    }
}

/* ===============================
   HANDLE ATTENDANCE SUBMISSION
================================ */
if (isset($_POST['submit_attendance'])) {

    $prison_ID    = $_POST['prison_ID'];
    $prison_fname = $_POST['prison_fname'];
    $prison_mname = $_POST['prison_mname'];
    $attendance   = $_POST['Attendance'];

    if (empty($prison_ID) || empty($prison_fname) || empty($prison_mname) || empty($attendance)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO attendance (prison_ID, prison_fname, prison_mname, Date, Attendance)
             VALUES (?, ?, ?, NOW(), ?)"
        );
        $stmt->bind_param("isss", $prison_ID, $prison_fname, $prison_mname, $attendance);

        if ($stmt->execute()) {
            $success = "Attendance recorded successfully.";
            $prison_data = null; // Reset after submission
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Prison Management System</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index3.php">Home</a>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top:80px;">
    <div class="page-header">
        <h1>Take Attendance</h1>
    </div>

    <!-- Display messages -->
    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)) : ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- STEP 1: Ask user for Prison ID -->
    <?php if (!$prison_data) : ?>
        <form method="post" class="form-horizontal">
            <div class="form-group">
                <label class="col-md-2 control-label">Prison ID</label>
                <div class="col-md-3">
                    <input type="number" name="prison_ID_input" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="fetch_prisoner" class="btn btn-primary">Fetch Prisoner</button>
                </div>
            </div>
        </form>
    <?php endif; ?>

    <!-- STEP 2: Show prisoner info and attendance form -->
    <?php if ($prison_data) : ?>
        <form method="post" class="form-horizontal">
            <input type="hidden" name="prison_ID" value="<?= htmlspecialchars($prison_data['prison_ID']) ?>">
            <input type="hidden" name="prison_fname" value="<?= htmlspecialchars($prison_data['prison_fname']) ?>">
            <input type="hidden" name="prison_mname" value="<?= htmlspecialchars($prison_data['prison_mname']) ?>">

            <div class="form-group">
                <label class="col-md-2 control-label">Prison ID</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="<?= htmlspecialchars($prison_data['prison_ID']) ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-md-offset-2">
                    First Name
                    <input type="text" class="form-control" value="<?= htmlspecialchars($prison_data['prison_fname']) ?>" readonly>
                </div>

                <div class="col-md-3">
                    Middle Name
                    <input type="text" class="form-control" value="<?= htmlspecialchars($prison_data['prison_mname']) ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">Attendance</label>
                <div class="col-md-3">
                    <select name="Attendance" class="form-control" required>
                        <option value="">-- Select --</option>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Permission">Permission</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-8 col-md-offset-2">
                    <button name="submit_attendance" class="btn btn-primary">Submit Attendance</button>
                    <a href="attendance.php" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
