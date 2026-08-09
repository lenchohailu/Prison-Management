<?php
include('session.php');
include('DB.php');

if (!isset($conn)) {
    die("Database connection error.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Prison Management System</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
<div class="container">
<ul class="nav navbar-nav navbar-left">
<li><a href="index3.php">Home</a></li>
<li><a href="attendance.php">Attendance</a></li>
<li><a href="profilepo.php">Prisoner Info</a></li>
</ul>
<ul class="nav navbar-nav navbar-right">
<li><a href="logout.php">Logout</a></li>
</ul>
</div>
</nav>

<div class="container-fluid" style="margin-top:80px;">

<?php
if (isset($_POST['search'])) {

    $search = trim($_POST['search']);
    $search = preg_replace("#[^0-9a-zA-Z ]#i", "", $search);

    if ($search == "") {
        echo "<h3 style='color:red;text-align:center'>Please enter ID or Name</h3>";
    } else {

        $like = "%$search%";

        /* COUNT RESULTS */
        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total 
            FROM prisoner 
            WHERE prison_ID LIKE ?
               OR prison_fname LIKE ?
               OR prison_mname LIKE ?
               OR prison_lname LIKE ?
               OR prison_age LIKE ?
               OR prison_gen LIKE ?
        ");
        $stmt->bind_param("ssssss", $like,$like,$like,$like,$like,$like);
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        if ($total == 0) {
            echo "<h3 style='color:red;text-align:center'>No results found</h3>";
        } else {

            /* PAGINATION */
            $limit = 10;
            $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $start = ($page - 1) * $limit;

            /* FETCH DATA */
            $stmt = $conn->prepare("
                SELECT * FROM prisoner
                WHERE prison_ID LIKE ?
                   OR prison_fname LIKE ?
                   OR prison_mname LIKE ?
                   OR prison_lname LIKE ?
                   OR prison_age LIKE ?
                   OR prison_gen LIKE ?
                LIMIT ? OFFSET ?
            ");
            $stmt->bind_param(
                "ssssssii",
                $like,$like,$like,$like,$like,$like,$limit,$start
            );
            $stmt->execute();
            $result = $stmt->get_result();
?>

<h3><?php echo $total; ?> Prisoner(s) Found</h3>

<div class="table-responsive">
<table class="table table-bordered table-hover">
<thead>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Age</th>
<th>Gender</th>
<th>Address</th>
<th>Contact</th>
<th>Status</th>
<th>Start Date</th>
<th>End Date</th>
<th colspan="3">Attendance</th>
</tr>
</thead>
<tbody>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
<td><?= htmlspecialchars($row['prison_ID']) ?></td>
<td><?= htmlspecialchars($row['prison_fname']) ?></td>
<td><?= htmlspecialchars($row['prison_lname']) ?></td>
<td><?= htmlspecialchars($row['prison_age']) ?></td>
<td><?= htmlspecialchars($row['prison_gen']) ?></td>
<td><?= htmlspecialchars($row['prison_add']) ?></td>
<td><?= htmlspecialchars($row['prison_cont']) ?></td>
<td><?= htmlspecialchars($row['prison_stat']) ?></td>
<td><?= htmlspecialchars($row['Prison_Date']) ?></td>
<td><?= htmlspecialchars($row['end_date']) ?></td>

<td>
<a href="Take_Attendance.php?idd=<?= $row['prison_ID'] ?>">
<span class="glyphicon glyphicon-check" style="color:green"></span>
</a>
</td>

<td>
<a href="editAttend.php?idd=<?= $row['prison_ID'] ?>">
<span class="glyphicon glyphicon-edit"></span>
</a>
</td>

<td>
<a href="count.php?idd=<?= $row['prison_ID'] ?>">
<span class="glyphicon glyphicon-time" style="color:gold"></span>
</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

<!-- PAGINATION -->
<ul class="pagination">
<?php
$pages = ceil($total / $limit);
for ($i = 1; $i <= $pages; $i++) {
    echo "<li><a href='?page=$i'>$i</a></li>";
}
?>
</ul>

<?php
        }
    }
}
?>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
