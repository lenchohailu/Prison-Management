<?php
include('session.php');
include('DB.php'); // MUST define $conn
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Attendance Management</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<style>
.top-bar{
    margin-bottom:20px;
}
.home-btn{
    background:#1e4b43;
    color:white;
    padding:10px 15px;
    border-radius:6px;
    text-decoration:none;
    display:inline-block;
}
.home-btn:hover{
    background:#0b2b26;
    color:white;
}
</style>

</head>

<body>

<div class="container" style="margin-top:80px;">

<!-- ✅ RETURN TO HOME BUTTON -->
<div class="top-bar">
    <a href="index3.php" class="home-btn">
        <i class="fa fa-home"></i> Return to Home Page
    </a>
</div>

<?php
/* ================= DELETE ATTENDANCE ================= */
if(isset($_GET['delete'])){

    $prison_ID  = $_GET['prison_ID'];
    $Date       = $_GET['Date'];
    $Attendance = $_GET['Attendance'];

    $stmt = $conn->prepare("
        DELETE FROM attendance
        WHERE prison_ID = ? AND Date = ? AND Attendance = ?
        LIMIT 1
    ");

    $stmt->bind_param("iss", $prison_ID, $Date, $Attendance);
    $stmt->execute();
    $stmt->close();

    echo '<div class="alert alert-success">Attendance record deleted successfully.</div>';
}

/* ================= PAGINATION ================= */
$limit = 10;
$page = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;
$start_from = ($page - 1) * $limit;

/* ================= COUNT ================= */
$count = $conn->query("SELECT COUNT(*) as total FROM attendance");
$total_records = $count->fetch_assoc()['total'] ?? 0;

echo "<h2>{$total_records} Attendance Records</h2>";

/* ================= FETCH ================= */
$stmt = $conn->prepare("
    SELECT prison_ID, prison_fname, prison_mname, Date, Attendance
    FROM attendance
    ORDER BY Date DESC
    LIMIT ?, ?
");

$stmt->bind_param("ii", $start_from, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="panel panel-default">
<div class="panel-body">

<table class="table table-hover table-bordered">

<thead>
<tr>
    <th>ID</th>
    <th>First Name</th>
    <th>Middle Name</th>
    <th>Date</th>
    <th>Attendance</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): ?>
<tr>

    <td><?= htmlspecialchars($row['prison_ID']) ?></td>
    <td><?= htmlspecialchars($row['prison_fname']) ?></td>
    <td><?= htmlspecialchars($row['prison_mname']) ?></td>
    <td><?= htmlspecialchars($row['Date']) ?></td>
    <td><?= htmlspecialchars($row['Attendance']) ?></td>

    <td class="text-center">
        <a href="javascript:void(0);"
           onclick="confirmDelete(
                '<?= $row['prison_ID'] ?>',
                '<?= $row['Date'] ?>',
                '<?= $row['Attendance'] ?>'
           )">
            <i class="fa fa-trash text-danger" style="font-size:18px;"></i>
        </a>
    </td>

</tr>
<?php endwhile; ?>

</tbody>
</table>

<!-- ================= PAGINATION ================= -->
<ul class="pagination">
<?php
$total_pages = ceil($total_records / $limit);

for($i = 1; $i <= $total_pages; $i++):
?>
    <li class="<?= ($i == $page) ? 'active' : '' ?>">
        <a href="?page=<?= $i ?>"><?= $i ?></a>
    </li>
<?php endfor; ?>
</ul>

</div>
</div>

</div>

<script>
function confirmDelete(prison_ID, Date, Attendance){

    if(confirm("Are you sure you want to delete this record?")){

        window.location.href =
            "?delete=1"
            + "&prison_ID=" + encodeURIComponent(prison_ID)
            + "&Date=" + encodeURIComponent(Date)
            + "&Attendance=" + encodeURIComponent(Attendance);
    }
}
</script>

</body>
</html>