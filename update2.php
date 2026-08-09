<?php
include('DB.php'); // $conn = mysqli connection

// Handle Delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Pagination setup
$endlimit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start_from = ($page - 1) * $endlimit;

// Count total users
$result_total = $conn->query("SELECT COUNT(*) AS total FROM users");
$total_rows = $result_total->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $endlimit);

// Fetch users
$stmt = $conn->prepare("SELECT * FROM users ORDER BY ID DESC LIMIT ?, ?");
$stmt->bind_param("ii", $start_from, $endlimit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Woliso Prison Management System</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index1.php">Woliso Prison</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="index1.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="dropdown">
                    
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="?logout" style="color:white;"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container" style="margin-top:80px;">
    <h2>Total Users: <?= $total_rows ?></h2>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>User Type</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['ID']) ?></td>
                    <td><?= htmlspecialchars($row['userName']) ?></td>
                    <td><?= htmlspecialchars($row['userType']) ?></td>
                    <td>
                        <a href="edit_acount.php?id=<?= $row['ID'] ?>" title="Edit">
                            <span class="glyphicon glyphicon-edit" style="font-size:18px;"></span>
                        </a>
                    </td>
                    <td>
                        <a href="javascript:void(0);" onclick="confirmDelete(<?= $row['ID'] ?>)" title="Delete">
                            <span class="glyphicon glyphicon-trash" style="font-size:18px; color:red;"></span>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
        <?php for($i=1; $i<=$total_pages; $i++): ?>
            <li class="<?= ($i==$page)?'active':'' ?>">
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
function confirmDelete(id){
    if(confirm("Are you sure you want to delete this user?")){
        window.location.href = "?delete=1&id=" + id;
    }
}
</script>


</body>
</html>
