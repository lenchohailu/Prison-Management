<?php
include('DB.php');
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

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active"><a href="visiting_time.php"><i class="fa fa-eye"></i> Visiting Time</a></li>
                <li><a href="update.php"><i class="fa fa-search"></i> Search Prisoner</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="login.php"><i class="fa fa-user"></i> Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid" style="margin-top:70px">

<?php
// ================= PAGINATION =================
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;

$limit = 10;
$start = ($page - 1) * $limit;

// Count prisoners
$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM prisoner");
$countRow = mysqli_fetch_assoc($countResult);
$total_records = $countRow['total'];
$total_pages = ceil($total_records / $limit);

echo "<h3>$total_records Prisoners</h3>";
?>

<!-- Search Box -->
<div class="row">
    <div class="col-md-4 col-md-offset-8">
        <form method="post" action="search.php">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by First, Middle, Last Name">
                <span class="input-group-btn">
                    <button class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
    </div>
</div>

<br>

<!-- Prisoner Table -->
<div class="panel panel-default">
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>

<?php
$sql = "SELECT * FROM prisoner 
        ORDER BY Prison_Date DESC 
        LIMIT ?, ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $start, $limit);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
?>
    <tr>
        <td><?= htmlspecialchars($row['prison_ID']) ?></td>
        <td><?= htmlspecialchars($row['prison_fname']) ?></td>
        <td><?= htmlspecialchars($row['prison_mname']) ?></td>
        <td><?= htmlspecialchars($row['prison_lname']) ?></td>
        <td><?= htmlspecialchars($row['prison_gen']) ?></td>
        <td><?= htmlspecialchars($row['prison_add']) ?></td>
        <td><?= htmlspecialchars($row['prison_cont']) ?></td>
    </tr>
<?php } ?>

            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <li class="<?= ($i == $page) ? 'active' : '' ?>">
                <a href="update.php?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php } ?>
    </ul>
</nav>

</div>

<?php include('footer.php'); ?>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
