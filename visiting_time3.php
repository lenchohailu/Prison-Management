<?php
include("DB.php");
include("session.php");

$message = "";

/* ================= ADD VISITING TIME ================= */
if(isset($_POST['add_visiting'])){

    $day      = trim($_POST['day']);
    $morning  = trim($_POST['morning']);
    $after    = trim($_POST['after']);

    if($day == "" || $morning == "" || $after == ""){
        $message = "<div class='alert alert-danger'>All fields are required.</div>";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO visiting_time (days, morning, `after`)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sss", $day, $morning, $after);

        if($stmt->execute()){
            $message = "<div class='alert alert-success'>Visiting Time Added Successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: ".$stmt->error."</div>";
        }

        $stmt->close();
    }
}

/* ================= DELETE VISITING TIME ================= */
if(isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])){

    $id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("DELETE FROM visiting_time WHERE ID=?");
    $stmt->bind_param("i",$id);

    if($stmt->execute()){
        $message = "<div class='alert alert-success'>Deleted Successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Delete Failed.</div>";
    }

    $stmt->close();
}

/* ================= FETCH VISITING TIMES ================= */
$result = $conn->query("
    SELECT * FROM visiting_time
    ORDER BY ID DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Manage Visiting Time</title>

<link href="css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
}
.page-box{
    margin-top:50px;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}
</style>

</head>
<body>

<div class="container">

    <div class="page-box">

        <a href="index3.php" class="btn btn-success">
            ← Return to Home Page
        </a>

        <h2 class="text-center" style="margin-top:20px;">
            Manage Visiting Time
        </h2>

        <?= $message ?>

        <!-- ================= ADD FORM ================= -->
        <form method="POST" style="margin-top:25px;">

            <div class="row">

                <div class="col-md-4">
                    <label>Day</label>
                    <input type="text" name="day" class="form-control" placeholder="Example: Monday-Friday" required>
                </div>

                <div class="col-md-4">
                    <label>Morning Visiting Time</label>
                    <input type="text" name="morning" class="form-control" placeholder="Example: 6:00-7:30" required>
                </div>

                <div class="col-md-4">
                    <label>Afternoon Visiting Time</label>
                    <input type="text" name="after" class="form-control" placeholder="Example: 10:00-11:00" required>
                </div>

            </div>

            <br>

            <button type="submit" name="add_visiting" class="btn btn-primary">
                Add Visiting Time
            </button>

        </form>

        <hr>

        <!-- ================= TABLE ================= -->
        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead>
                    <tr class="info">
                        <th>Day</th>
                        <th>Morning Visiting Time</th>
                        <th>Afternoon Visiting Time</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>

                        <td><?= htmlspecialchars($row['days']) ?></td>
                        <td><?= htmlspecialchars($row['morning']) ?></td>
                        <td><?= htmlspecialchars($row['after']) ?></td>

                        <td>
                            <a href="?delete_id=<?= $row['ID'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Delete this visiting time?')">
                               Delete
                            </a>
                        </td>

                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            No Visiting Time Found
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>