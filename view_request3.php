<?php
include('DB.php');

// =======================
// HANDLE APPROVE ACTION
// =======================
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);

    $stmt = $conn->prepare("UPDATE request SET status='Approved' WHERE ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: view_request3.php");
    exit();
}

// =======================
// HANDLE NOT APPROVE ACTION
// =======================
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);

    $stmt = $conn->prepare("UPDATE request SET status='Not Approved' WHERE ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: view_request3.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Request Approval System</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }

.table {
    background:white;
}

.btn-approve {
    background:green;
    color:white;
    padding:5px 10px;
    border-radius:5px;
}

.btn-reject {
    background:red;
    color:white;
    padding:5px 10px;
    border-radius:5px;
}

.status-approved {
    color: green;
    font-weight: bold;
}

.status-rejected {
    color: red;
    font-weight: bold;
}

.status-pending {
    color: orange;
    font-weight: bold;
}

/* ✅ NEW HOME BUTTON STYLE */
.home-btn {
    margin-bottom: 15px;
    display: inline-block;
}
</style>
</head>

<body>

<div class="container" style="margin-top:30px;">

<h3><i class="fa fa-check-circle"></i> Request Approval Panel</h3>

<!-- ✅ NEW: RETURN TO HOME BUTTON -->
<a href="index4.php" class="btn btn-primary home-btn">
    <i class="fa fa-home"></i> Return to Home
</a>

<div class="panel panel-default">
<div class="panel-body">

<div class="table-responsive">
<table class="table table-bordered table-hover">

<thead>
<tr>
    <th>ID</th>
    <th>Place</th>
    <th>Reason</th>
    <th>Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php
$result = $conn->query("SELECT * FROM request ORDER BY date DESC");

while ($row = $result->fetch_assoc()) {
?>

<tr>
    <td><?= $row['ID'] ?></td>
    <td><?= $row['place'] ?></td>
    <td><?= $row['reason'] ?></td>
    <td><?= $row['date'] ?></td>

    <td>
        <?php
        if ($row['status'] == 'Approved') {
            echo "<span class='status-approved'>Approved</span>";
        } elseif ($row['status'] == 'Not Approved') {
            echo "<span class='status-rejected'>Not Approved</span>";
        } else {
            echo "<span class='status-pending'>Pending</span>";
        }
        ?>
    </td>

    <td>
        <a href="?approve=<?= $row['ID'] ?>" class="btn-approve">
            Approve
        </a>

        <a href="?reject=<?= $row['ID'] ?>" class="btn-reject">
            Not Approve
        </a>
    </td>
</tr>

<?php } ?>

</tbody>
</table>
</div>

</div>
</div>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>