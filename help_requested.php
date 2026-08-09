<?php
include('session.php');
include('DB.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Help Requests - Admin Panel</title>

<link href="css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6f9;
    font-family: Arial, sans-serif;
}

.container {
    margin-top: 50px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #2c3e50;
}

.table-box {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.status {
    padding: 5px 10px;
    border-radius: 5px;
    color: white;
    font-size: 12px;
}

.pending {
    background: orange;
}

.solved {
    background: green;
}
</style>

</head>

<body>

<div class="container">

<h2>Help Requests (Admin Panel)</h2>

<div class="table-box">

<table class="table table-bordered table-striped">

<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Issue</th>
    <th>Status</th>
    <th>Date</th>
</tr>
</thead>

<tbody>

<?php
$result = $conn->query("SELECT * FROM helpdesk ORDER BY id DESC");

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
?>

<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['issue']) ?></td>

    <td>
        <?php if ($row['status'] == "Solved") { ?>
            <span class="status solved">Solved</span>
        <?php } else { ?>
            <span class="status pending">Pending</span>
        <?php } ?>
    </td>

    <td><?= $row['created_at'] ?></td>
</tr>

<?php
    }

} else {
    echo "<tr><td colspan='6' style='text-align:center;'>No help requests found</td></tr>";
}
?>

</tbody>

</table>

</div>

<br>

<a href="index1.php" class="btn btn-primary">Back to Dashboard</a>

</div>

</body>
</html>