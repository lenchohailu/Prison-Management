<?php
include('session.php');
include('DB.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$message = "";

/* ================= FETCH SCHEDULE ================= */
$query = "SELECT * FROM schedule ORDER BY date ASC";
$result = $conn->query($query);

$today = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Schedule</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
body {
    background:#f4f6f9;
}

.card-box {
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

.highlight {
    background-color: #dff0d8 !important;
}
</style>

</head>

<body>

<div class="container" style="margin-top:50px; max-width:900px;">

    <!-- Return Button -->
    <a href="index2.php" class="btn btn-success">
        ← Return to Home
    </a>

    <div class="card-box" style="margin-top:20px;">

        <h3 class="text-center">
            <i class="fa fa-calendar"></i> Scheduled Job
        </h3>
        <hr>

        <?php if ($result->num_rows > 0) { ?>

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Morning</th>
                    <th>Afternoon</th>
                </tr>
            </thead>

            <tbody>

            <?php while($row = $result->fetch_assoc()) { ?>

                <tr class="<?php echo ($row['date'] == $today) ? 'highlight' : ''; ?>">
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['days']; ?></td>
                    <td><?php echo $row['morning']; ?></td>
                    <td><?php echo $row['afternoon']; ?></td>
                </tr>

            <?php } ?>

            </tbody>

        </table>

        <?php } else { ?>

            <div class="alert alert-warning text-center">
                No schedule available.
            </div>

        <?php } ?>

    </div>

</div>

</body>
</html>