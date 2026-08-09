<?php
include('DB.php'); // Must contain $conn = new mysqli(...);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$endlimit = 10;
$start_from = ($page - 1) * $endlimit;

// Count total rows
$count_sql = "SELECT COUNT(*) AS total FROM prisoner";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $endlimit);

// Fetch records
$stmt = $conn->prepare("SELECT * FROM prisoner ORDER BY Prison_Date DESC LIMIT ?, ?");
$stmt->bind_param("ii", $start_from, $endlimit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Prison Management System - Woliso</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

</head>
<body>

<div class="container-fluid" style="margin-top:20px;">

    <!-- Return Home Button -->
    <div class="col-md-12" style="margin-bottom:20px;">
        <a href="index3.php" class="btn btn-success">
            <i class="fa fa-home"></i> Return to Home
        </a>
    </div>

<div class="col-md-8">
    <h2><?php echo $total_rows; ?> Prisoner(s)</h2>
</div>

<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="POST" action="search2.php" class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-9">
                        <input id="search" name="search" placeholder="Search by FName, MName, LName"
                               class="form-control" type="text">
                    </div>
                    <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Previous Record</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['prison_ID'] ?></td>
                            <td><?= $row['prison_fname'] ?></td>
                            <td><?= $row['prison_mname'] ?></td>
                            <td><?= $row['prison_lname'] ?></td>
                            <td><?= $row['prison_age'] ?></td>
                            <td><?= $row['prison_gen'] ?></td>
                            <td><?= $row['prison_add'] ?></td>
                            <td><?= $row['prison_cont'] ?></td>
                            <td><?= $row['prison_stat'] ?></td>
                            <td><?= $row['previews_record'] ?></td>
                            <td><?= $row['Prison_Date'] ?></td>
                            <td><?= $row['end_date'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="text-center">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li <?php if ($i == $page) echo 'class="active"'; ?>>
                <a href="update.php?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</div>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php include('footer.php'); ?>
</body>
</html>