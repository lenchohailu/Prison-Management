<?php
include('DB.php');
include('session.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* ================= CSRF TOKEN ================= */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";

/* ================= INSERT SCHEDULE ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "<div class='alert alert-danger'>Invalid request.</div>";
    } else {

        $date = trim($_POST['date'] ?? '');
        $day  = trim($_POST['day'] ?? '');

        $MWTFh = (int)($_POST['MWTFh'] ?? -1);
        $MWTFm = (int)($_POST['MWTFm'] ?? -1);
        $MWTTh = (int)($_POST['MWTTh'] ?? -1);
        $MWTTm = (int)($_POST['MWTTm'] ?? -1);

        $AWTFh = (int)($_POST['AWTFh'] ?? -1);
        $AWTFm = (int)($_POST['AWTFm'] ?? -1);
        $AWTTh = (int)($_POST['AWTTh'] ?? -1);
        $AWTTm = (int)($_POST['AWTTm'] ?? -1);

        $errors = [];

        if (empty($date)) {
            $errors[] = "Date is required.";
        }

        $validDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        if (!in_array($day, $validDays)) {
            $errors[] = "Invalid day selected.";
        }

        if (empty($errors)) {

            $morning   = sprintf("%02d:%02d - %02d:%02d", $MWTFh, $MWTFm, $MWTTh, $MWTTm);
            $afternoon = sprintf("%02d:%02d - %02d:%02d", $AWTFh, $AWTFm, $AWTTh, $AWTTm);

            /* ================= FIXED DUPLICATE CHECK ================= */
            $check = $conn->prepare("SELECT COUNT(*) FROM schedule WHERE DATE(date) = ?");
            $check->bind_param("s", $date);
            $check->execute();
            $check->bind_result($count);
            $check->fetch();
            $check->close();

            if ($count > 0) {
                $message = "<div class='alert alert-warning'>Schedule already exists for this date.</div>";
            } else {

                /* ================= INSERT ================= */
                $stmt = $conn->prepare("
                    INSERT INTO schedule (date, days, morning, `afternoon`)
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->bind_param("ssss", $date, $day, $morning, $afternoon);

                if ($stmt->execute()) {

                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    // ✅ STAY ON PAGE (NO REDIRECT)
                    $message = "<div class='alert alert-success'>Schedule added successfully!</div>";

                } else {
                    $message = "<div class='alert alert-danger'>Database error: " . $stmt->error . "</div>";
                }

                $stmt->close();
            }
        }

        if (!empty($errors)) {
            $message = "<div class='alert alert-danger'><ul>";
            foreach ($errors as $e) {
                $message .= "<li>$e</li>";
            }
            $message .= "</ul></div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Schedule</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container" style="margin-top:50px;">

<!-- ================= RETURN TO HOME ================= -->
<a href="index3.php" class="btn btn-success">
    ← Return to Home Page
</a>

<h2 style="margin-top:20px;">Add Schedule</h2>

<?= $message ?>

<form method="post">

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

<div class="form-group">
    <label>Date</label>
    <input type="date" name="date" class="form-control" required>
</div>

<div class="form-group">
    <label>Day</label>
    <select name="day" class="form-control" required>
        <option value="">Select</option>
        <option>Monday</option>
        <option>Tuesday</option>
        <option>Wednesday</option>
        <option>Thursday</option>
        <option>Friday</option>
        <option>Saturday</option>
        <option>Sunday</option>
    </select>
</div>

<h4>Morning</h4>
<div class="row">
    <div class="col-md-3"><input type="number" name="MWTFh" class="form-control" placeholder="From Hour"></div>
    <div class="col-md-3"><input type="number" name="MWTFm" class="form-control" placeholder="From Min"></div>
    <div class="col-md-3"><input type="number" name="MWTTh" class="form-control" placeholder="To Hour"></div>
    <div class="col-md-3"><input type="number" name="MWTTm" class="form-control" placeholder="To Min"></div>
</div>

<h4 style="margin-top:20px;">Afternoon</h4>
<div class="row">
    <div class="col-md-3"><input type="number" name="AWTFh" class="form-control" placeholder="From Hour"></div>
    <div class="col-md-3"><input type="number" name="AWTFm" class="form-control" placeholder="From Min"></div>
    <div class="col-md-3"><input type="number" name="AWTTh" class="form-control" placeholder="To Hour"></div>
    <div class="col-md-3"><input type="number" name="AWTTm" class="form-control" placeholder="To Min"></div>
</div>

<br>

<button type="submit" name="submit" class="btn btn-primary">
    Save Schedule
</button>

<a href="index3.php" class="btn btn-default">Cancel</a>

</form>

</div>

</body>
</html>