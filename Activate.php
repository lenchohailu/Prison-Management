<?php
session_start();
include('DB.php');

// Redirect if not logged in
if (!isset($_SESSION["ID"])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION["ID"];

// Fetch requests safely
$stmt = $conn->prepare("SELECT * FROM request WHERE ID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>View Requests | Prison Management System</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
<style>
body {
    padding-top: 70px;
    background: #f8f9fa;
}
.navbar-nav .nav-link {
    color: white !important;
}
.table th, .table td {
    vertical-align: middle !important;
}
</style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index2.php">Prison Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index2.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fa fa-question"></i> Request
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="request_form.php"><i class="fa fa-send"></i> Send Request</a></li>
                        <li><a class="dropdown-item" href="view_request.php"><i class="fa fa-eye"></i> View Request</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="visiting_time2.php"><i class="fa fa-envelope-o"></i> Visiting Time</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="fa fa-user"></i> Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="job.php"><i class="fa fa-briefcase"></i> Job</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="container mt-4">
    <h2 class="text-center mb-4">Your Requests</h2>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Request ID</th>
                    <th>Place</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ID']) ?></td>
                            <td><?= htmlspecialchars($row['place']) ?></td>
                            <td><?= htmlspecialchars($row['reason']) ?></td>
                            <td>
                                <?php
                                $status = htmlspecialchars($row['status']);
                                $badgeClass = match($status) {
                                    'Approved' => 'success',
                                    'Pending' => 'warning',
                                    'Rejected' => 'danger',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $badgeClass ?>"><?= $status ?></span>
                            </td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 fixed-bottom">
    &copy; <?= date('Y') ?> Prison Management System
</footer>

<!-- JS -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
