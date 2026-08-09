<?php
include('session.php');
include('DB.php');

$attendanceData = [];
$num_rows = 0;
$error = '';
$delete_msg = '';

/* ================= DELETE ATTENDANCE ================= */
if (isset($_GET['delete'])) {

    $prison_ID = $_GET['prison_ID'];
    $Date = $_GET['Date'];
    $Attendance = $_GET['Attendance'];

    $stmt = $conn->prepare("
        DELETE FROM attendance
        WHERE prison_ID = ? AND Date = ? AND Attendance = ?
        LIMIT 1
    ");
    $stmt->bind_param("iss", $prison_ID, $Date, $Attendance);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index3.php"); // ✅ redirect to home page
        exit();
    }

    $stmt->close();
}

/* ================= UPDATE ATTENDANCE ================= */
if (isset($_POST['action']) && $_POST['action'] == 'update_attendance') {

    $prison_ID = $_POST['prison_ID'];
    $oldDate   = $_POST['oldDate'];
    $field     = $_POST['field'];
    $value     = $_POST['value'];

    $allowed = ['prison_fname','prison_mname','Attendance','Date'];

    if (in_array($field, $allowed)) {

        $stmt = $conn->prepare("
            UPDATE attendance
            SET $field = ?
            WHERE prison_ID = ? AND Date = ?
            LIMIT 1
        ");

        $stmt->bind_param("sis", $value, $prison_ID, $oldDate);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['success'=>true]);
        exit;
    }

    echo json_encode(['success'=>false]);
    exit;
}

/* ================= SEARCH ATTENDANCE ================= */
if (isset($_POST['submit'])) {

    $prisonId = trim($_POST['prison_ID']);

    if (!is_numeric($prisonId) || $prisonId <= 0) {
        $error = "Invalid Prisoner ID.";
    } else {

        $stmtCount = $conn->prepare("
            SELECT COUNT(*)
            FROM attendance
            WHERE prison_ID = ?
        ");

        $stmtCount->bind_param("i", $prisonId);
        $stmtCount->execute();
        $stmtCount->bind_result($num_rows);
        $stmtCount->fetch();
        $stmtCount->close();

        if ($num_rows > 0) {

            $stmt = $conn->prepare("
                SELECT prison_ID, prison_fname, prison_mname, Date, Attendance
                FROM attendance
                WHERE prison_ID = ?
                ORDER BY Date DESC
            ");

            $stmt->bind_param("i", $prisonId);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $attendanceData[] = $row;
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Attendance Records</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container" style="margin-top:50px;">

<!-- ✅ RETURN HOME BUTTON -->
<a href="index3.php" class="btn btn-primary" style="margin-bottom:15px;">
    ← Return to Home Page
</a>

<h2>Attendance Records</h2>

<?php if($delete_msg) echo $delete_msg; ?>
<?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="post">
    <div class="form-group">
        <label>Prisoner ID</label>
        <input type="number" name="prison_ID" class="form-control" required>
    </div>
    <button name="submit" class="btn btn-primary">Search</button>
</form>

<?php if($num_rows > 0): ?>

<hr>

<table class="table table-bordered">
    <tr>
        <th>Prison ID</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Date</th>
        <th>Attendance</th>
        <th>Delete</th>
    </tr>

    <?php foreach($attendanceData as $row): ?>

    <tr>
        <td><?= htmlspecialchars($row['prison_ID']) ?></td>
        <td><?= htmlspecialchars($row['prison_fname']) ?></td>
        <td><?= htmlspecialchars($row['prison_mname']) ?></td>
        <td><?= htmlspecialchars($row['Date']) ?></td>
        <td><?= htmlspecialchars($row['Attendance']) ?></td>
        <td>
            <a href="?delete=1
                &prison_ID=<?= urlencode($row['prison_ID']) ?>
                &Date=<?= urlencode($row['Date']) ?>
                &Attendance=<?= urlencode($row['Attendance']) ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Delete attendance record?')">
               Delete
            </a>
        </td>
    </tr>

    <?php endforeach; ?>

</table>

<?php endif; ?>

</div>

</body>
</html>