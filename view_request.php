<?php
include('session.php');
include('DB.php');

$message = "";
$requests = [];
$user_id = null;

if (isset($_POST['fetch'])) {

    $user_id = trim($_POST['ID']);

    if ($user_id == "" || !is_numeric($user_id)) {
        $message = "<div class='alert alert-danger'>Please enter a valid ID.</div>";
    } else {

        // ONLY THIS USER'S DATA
        $stmt = $conn->prepare("SELECT * FROM request WHERE ID = ? ORDER BY date DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $requests[] = $row;
            }
        } else {
            $message = "<div class='alert alert-warning'>No requests found for this ID.</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>View My Requests</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .box {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<div class="container" style="margin-top:30px;">

    <!-- Return Home -->
    <a href="index2.php" class="btn btn-primary" style="margin-bottom:15px;">
        <i class="fa fa-home"></i> Return to Home
    </a>

    <div class="box">

        <h2 class="text-center">View My Requests</h2>
        <hr>

        <?= $message ?>

        <!-- ID INPUT -->
        <form method="POST" class="form-inline" style="margin-bottom:20px;">
            <div class="form-group">
                <label>Enter Your ID:</label>
                <input type="number" name="ID" class="form-control" required>
            </div>

            <button type="submit" name="fetch" class="btn btn-info">
                <i class="fa fa-search"></i> View My Data
            </button>
        </form>

        <!-- ONLY USER DATA SHOWN -->
        <?php if (!empty($requests)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Place</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($requests as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ID']) ?></td>
                        <td><?= htmlspecialchars($row['place']) ?></td>
                        <td><?= htmlspecialchars($row['reason']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php include('footer.php'); ?>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>