<?php
include('DB.php'); // Assuming this returns a mysqli connection object, e.g., $conn

if (!isset($conn)) {
    die("Database connection error.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Prison Management System</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="index3.php"><i class="fa fa-fw fa-home"></i>Home</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-compass"></i>Attendance<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Take_Attendance.php"><i class="fa fa-fw fa-save"></i>Take Attendance</a></li>
                            <li><a href="attendance.php"><i class="fa fa-fw fa-save"></i>View Attendance</a></li>
                            <li><a href="delete_all_attend.php"><i class="fa fa-fw fa-trash"></i>Delete Attendance</a></li>
                        </ul>
                    </li>
                    <li><a href="profilepo.php"><i class="fa fa-fw fa-home"></i>Prisoner Info</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-save"></i>Schedule<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Add_Schedule.php"><i class="fa fa-fw fa-plus"></i>Add Job Schedule</a></li>
                            <li><a href="edit_schedule.php"><i class="fa fa-fw fa-eye"></i>Edit Job Schedule</a></li>
                            <li><a href="visting_time3.php"><i class="fa fa-fw fa-plus"></i>Add Visiting Time</a></li>
                            <li><a href="viewandedit_visitng.php"><i class="fa fa-fw fa-eye"></i>Edit Visiting Time</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-file"></i>Report<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Report1.php"><i class="fa fa-fw fa-envelope-o"></i>Report</a></li>
                            <li><a href="delete_report1.php"><i class="fa fa-fw fa-trash"></i>Delete Report</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-file"></i>Job<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Jobpo.php"><i class="fa fa-fw fa-envelope-o"></i>Announce</a></li>
                            <li><a href="delete_job.php"><i class="fa fa-fw fa-trash"></i>Delete</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="?logout"><font color="white">Logout</font></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid" style="margin-top:80px;">
        <?php
        if (isset($_POST['search'])) {
            $searchP = $_POST['search'];
            $searchP = preg_replace("#[^0-9a-z]#i", "", $searchP); // Sanitize input

            if (empty($searchP)) {
                echo "<div style='font-size:24px; color:red; margin-left:250px;'>Please Input ID or Name To Search</div>";
            } else {
                $stmt = $conn->prepare("SELECT * FROM prisoner WHERE prison_ID LIKE ? OR prison_fname LIKE ? OR prison_mname LIKE ? OR prison_lname LIKE ?");
                $likeSearch = "%" . $searchP . "%";
                $stmt->bind_param("ssss", $likeSearch, $likeSearch, $likeSearch, $likeSearch);
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->num_rows;

                if ($count == 0) {
                    echo "<div style='font-size:24px; color:red; margin-left:250px;'>There are no search results</div>";
                } else {
                    // Paging setup
                    if (isset($_GET["page"])) {
                        $page = intval($_GET["page"]);
                    } else {
                        $page = 1;
                    }
                    $endlimit = 10;
                    $start_from = ($page - 1) * $endlimit;

                    $stmt_paged = $conn->prepare("SELECT * FROM prisoner WHERE prison_ID LIKE ? OR prison_fname LIKE ? OR prison_mname LIKE ? OR prison_lname LIKE ? LIMIT ? OFFSET ?");
                    $stmt_paged->bind_param("ssssi", $likeSearch, $likeSearch, $likeSearch, $likeSearch, $endlimit, $start_from);
                    $stmt_paged->execute();
                    $result_paged = $stmt_paged->get_result();
                    $num_rows = $result_paged->num_rows;
                ?>
                    <div class="row">
                        <div class="col-md-8">
                            <h2><?php echo $num_rows; ?> Prisoner<?php echo ($num_rows > 1 ? 's' : ''); ?></h2>
                        </div>
                        <div class="col-md-4">
                            <form method="post" action="search2.php" class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-md-9">
                                        <input type="text" name="search" placeholder="Search by FName, MName, LName" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-search"></i> Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                    <th>Previous Record</th>
                                    <th>Start Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result_paged->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['prison_ID']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prison_fname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prison_lname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prison_age']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prison_gen']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prison_add']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prison_cont']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prison_stat']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prison_recored']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Prison_Date']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Paging Links -->
                    <?php
                    $total_pages = ceil($count / $endlimit);
                    echo '<ul class="pagination">';
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li><a href="search2.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                    echo '</ul>';
                    $stmt->close();
                    $stmt_paged->close();
                }
            }
        }
        ?>
    </div>
    <?php include('footer.php'); ?>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>
