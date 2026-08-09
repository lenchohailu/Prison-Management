<?php
include('DB.php'); // mysqli connection in $conn

// Delete prisoner if requested
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM prisoner WHERE prison_ID = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Prisoner released successfully'); window.location='update4.php';</script>";
    } else {
        echo "<p style='color:red;'>Error deleting prisoner: ".$conn->error."</p>";
    }
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
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="index5.php">Home</a>
</div>
<div class="collapse navbar-collapse" id="navbar">
<ul class="nav navbar-nav navbar-left">
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Prisoner<b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="add_prison.php">Register</a></li>
<li><a href="update4.php">Update</a></li>
<li><a href="Releasing_day.php">Releasing Day</a></li>
</ul>
</li>
<li><a href="Archive.php">Archive</a></li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Report<b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="Report4.php">Report</a></li>
<li><a href="delete_report4.php">Delete Report</a></li>
</ul>
</li>
<li><a href="export.php">Export</a></li>
</ul>
<ul class="nav navbar-nav navbar-right">
<li><a href="?logout" style="color:white;">Logout</a></li>
</ul>
</div>
</div>
</nav>

<div class="container" style="margin-top:80px;">
<div class="panel panel-default">
<div class="panel-body">
<table class="table table-bordered table-hover">
<thead>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Birth Day</th>
<th>Age</th>
<th>Gender</th>
<th>Address</th>
<th>Contact</th>
<th>Status</th>
<th>Record</th>
<th>Prison Date</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php
$result = $conn->query("SELECT * FROM prisoner ORDER BY Prison_Date DESC");
while ($row = $result->fetch_assoc()) {
    $id = $row['prison_ID'];
    echo "<tr>";
    echo "<td>{$row['prison_ID']}</td>";
    echo "<td>{$row['prison_fname']}</td>";
    echo "<td>{$row['prison_lname']}</td>";
    echo "<td>{$row['Brith_Day']}</td>";
    echo "<td>{$row['prison_age']}</td>";
    echo "<td>{$row['prison_gen']}</td>";
    echo "<td>{$row['prison_add']}</td>";
    echo "<td>{$row['prison_cont']}</td>";
    echo "<td>{$row['prison_stat']}</td>";
    echo "<td>{$row['prison_recored']}</td>";
    echo "<td>{$row['Prison_Date']}</td>";
    echo "<td>
            <a href='edit_emp.php?id={$id}' title='Edit'><span class='glyphicon glyphicon-edit' style='font-size:20px'></span></a>
            <a href='javascript:confirmRelease({$id});' title='Release'><span class='glyphicon glyphicon-trash' style='font-size:20px; color:red'></span></a>
          </td>";
    echo "</tr>";
}
?>
</tbody>
</table>
</div>
</div>
</div>

<script>
function confirmRelease(id) {
    if(confirm("Are you sure to release this prisoner?")) {
        window.location = "update4.php?delete=1&id=" + id;
    }
}
</script>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
