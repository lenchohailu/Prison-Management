<?php
include("DB.php"); // must create $connection = new mysqli(...);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Prison Management System</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="index4.php">Home</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Request<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="view_request3.php">Approve</a></li>
                        <li><a href="approved.php">View Approved</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Report<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="Report3.php">Report</a></li>
                        <li><a href="delete_Report3.php">Delete Report</a></li>
                    </ul>
                </li>

                <li><a href="prisonprofile1.php">Prisoner Info</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><font color="white">Logout</font></a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid" style="margin-top:80px;">
    <div class="row">
        <div class="col-md-10">

            <?php
            error_reporting(E_ALL & ~E_NOTICE);

            // PAGINATION SETUP
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $start_from = ($page - 1) * $limit;

            // COUNT APPROVED REQUESTS
            $count_sql = "SELECT COUNT(*) AS total FROM request WHERE status='Approved'";
            $count_result = $conn->query($count_sql);
            $total_rows = $count_result->fetch_assoc()['total'];

            echo "<h2>$total_rows Requests Approved</h2>";
            ?>

        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">

                    <table class="table table-hover">
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

                        <?php
                        // FETCH APPROVED REQUESTS
                        $sql = "SELECT * FROM request WHERE status='Approved' ORDER BY date LIMIT $start_from, $limit";
                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?= $row['ID']; ?></td>
                                <td><?= $row['place']; ?></td>
                                <td><?= $row['reason']; ?></td>
                                <td><?= $row['status']; ?></td>
                                <td><?= $row['date']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>

                        </tbody>
                    </table>

                    <!-- PAGINATION LINKS -->
                    <?php
                    $total_pages = ceil($total_rows / $limit);

                    echo "<ul class='pagination'>";
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li><a href='update.php?page=$i'>$i</a></li>";
                    }
                    echo "</ul>";
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

</body>
</html>
