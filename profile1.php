<?php
// Include database connection (mysqli)
include('DB.php');

// Validate ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h3>Invalid Request</h3>");
}

$prisonID = $_GET['id'];

// Prepare & execute secure query
$stmt = $conn->prepare("SELECT * FROM prisoner WHERE prison_ID = ?");
$stmt->bind_param("s", $prisonID);
$stmt->execute();
$result = $stmt->get_result();

// Check if record exists
if ($result->num_rows === 0) {
    die("<h3>No prisoner found with this ID.</h3>");
}

$row = $result->fetch_assoc();
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
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="nav navbar-nav navbar-left">
                <li class="active"><a href="index4.php"><i class="fa fa-home"></i> Home</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question"></i> Request <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="view_request3.php"><i class="fa fa-check"></i> Approve</a></li>
                        <li><a href="approved.php"><i class="fa fa-eye"></i> View Approved</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file"></i> Report <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="Report3.php"><i class="fa fa-envelope-o"></i> Report</a></li>
                        <li><a href="delete_report3.php"><i class="fa fa-trash"></i> Delete Report</a></li>
                    </ul>
                </li>

                <li><a href="prisonprofile1.php"><i class="fa fa-user"></i> Prisoner Info</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="?logout"><font color="white">Logout</font></a></li>
            </ul>
        </div>

    </div>
</nav>

<div class="container" style="margin-top: 80px;">
    <div class="page-header">
        <h1>Prison Profile</h1>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">

            <form class="form-horizontal">
                <fieldset>
                    <legend>Prison Information</legend>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Prison ID</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="<?php echo $row['prison_ID']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Full Name</label>
                        <div class="col-md-3"><input type="text" class="form-control" value="<?php echo $row['prison_fname']; ?>" readonly></div>
                        <div class="col-md-3"><input type="text" class="form-control" value="<?php echo $row['prison_mname']; ?>" readonly></div>
                        <div class="col-md-3"><input type="text" class="form-control" value="<?php echo $row['prison_lname']; ?>" readonly></div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Age</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="<?php echo $row['prison_age']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Gender</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="<?php echo $row['prison_gen']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Address</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="<?php echo $row['prison_add']; ?>" readonly>
                        </div>
                    </div>

                    <legend>Contact Information</legend>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Contact Number</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="<?php echo $row['prison_cont']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Email</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="<?php echo $row['email']; ?>" readonly>
                        </div>
                    </div>

                    <legend>Prison Status</legend>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Status</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="<?php echo $row['prison_stat']; ?>" readonly>
                        </div>

                        <label class="col-md-2 control-label">Previous Record No.</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="<?php echo $row['prison_recored']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-2">
                            <a href="view_request3.php" class="btn btn-default">Back</a>
                        </div>
                    </div>

                </fieldset>
            </form>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>

</body>
</html>
