<?php
include("session.php");
include("DB.php");
error_reporting(E_ALL & ~E_NOTICE);

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$start_from = ($page - 1) * $limit;

// Count total prisoners
$count_sql = "SELECT COUNT(*) AS total FROM prisoner";
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$num_rows = $count_row['total'];
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
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<body>

<div class="container-fluid" style="margin-top:20px;">
<div class="row">

    <!-- Return Home Button -->
    <div class="col-md-12" style="margin-bottom:20px;">
        <a href="index3.php" class="btn btn-success">
            <i class="fa fa-home"></i> Return to Home
        </a>
    </div>

    <!-- Prisoner Count -->
    <div class="col-md-8">
        <h2><?php echo $num_rows; ?> Prisoner<?php echo ($num_rows != 1 ? "s" : ""); ?></h2>
    </div>

    <!-- Search Form -->
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <form method="post" action="search2.php" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-9">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Search by FName, MName, LName" 
                                   class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Prisoner Table -->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
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
                        <?php
                        $sql = "SELECT * FROM prisoner ORDER BY Prison_Date DESC LIMIT ?, ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ii", $start_from, $limit);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $prev_record = isset($row['prison_recored']) ? $row['prison_recored'] : '';
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['prison_ID']); ?></td>
                                <td><?php echo htmlspecialchars($row['prison_fname']); ?></td>
                                <td><?php echo htmlspecialchars($row['prison_mname']); ?></td>
                                <td><?php echo htmlspecialchars($row['prison_lname']); ?></td>
                                <td><?php echo htmlspecialchars($row['prison_age']); ?></td>
                                <td><?php echo htmlspecialchars($row['prison_gen']); ?></td>
                                <td><?php echo htmlspecialchars($row['prison_add']); ?></td>
                                <td><?php echo htmlspecialchars($row['prison_cont']); ?></td>
                                <td><?php echo htmlspecialchars($row['prison_stat']); ?></td>
                                <td><?php echo htmlspecialchars($prev_record); ?></td>
                                <td><?php echo htmlspecialchars($row['Prison_Date']); ?></td>
                                <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination">
                        <?php
                        $total_pages = ceil($num_rows / $limit);
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo "<li><a href='?page=$i'>$i</a></li>";
                        }
                        ?>
                    </ul>
                </nav>

            </div>
        </div>
    </div>

</div>
</div>

<?php include("footer.php"); ?>

</body>
</html>