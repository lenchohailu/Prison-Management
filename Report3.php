<?php
include('session.php');
include('DB.php'); // Make sure DB.php uses mysqli $conn
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Woliso Prison Management System</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="menu">

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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-fw fa-file"></i>Report<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="Report3.php"><i class="fa fa-fw fa-envelope-o"></i>Report</a></li>
                        <li><a href="delete_Report3.php"><i class="fa fa-fw fa-trash"></i>Delete Report</a></li>
                    </ul>
                </li>

                <li><a href="prisonprofile1.php"><i class="fa fa-fw fa-home"></i>Prisoner Info</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><font color="white">Logout</font></a></li>
            </ul>

        </div>

    </div>
</nav>


<div class="container-fluid" style="margin-top: 70px;">

    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Report</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">

                <?php
                if (isset($_POST["postB"])) {

                    $title = trim($_POST["title"]);
                    $post = trim($_POST["post"]);

                    if ($title === "" || $post === "") {
                        echo "<div class='alert alert-danger'>Please fill in all fields.</div>";
                    } else {
                        $stmt = $conn->prepare("
                            INSERT INTO post (title, post, postby, date)
                            VALUES (?, ?, ?, NOW())
                        ");

                        $stmt->bind_param("sss", $title, $post, $_SESSION["userName"]);

                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>
                                    Report successfully submitted.
                                  </div>";

                            // ✅ RETURN TO HOME BUTTON ADDED
                            echo "<a href='index4.php' class='btn btn-primary'>
                                    <i class='fa fa-home'></i> Return to Home
                                  </a>";

                        } else {
                            echo "<div class='alert alert-danger'>Error: ".$stmt->error."</div>";
                        }

                        $stmt->close();
                    }
                }
                ?>

                <form method="post" action="Report3.php" class="form-horizontal">
                    <fieldset>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Title</label>
                            <div class="col-md-3">
                                <input type="text" name="title" class="form-control" placeholder="Title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Report</label>
                            <div class="col-md-3">
                                <textarea name="post" rows="6" class="form-control" placeholder="Your message..."></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-8">
                                <button type="submit" name="postB" class="btn btn-primary">Submit</button>
                                <a href="index4.php" class="btn btn-default">Cancel</a>
                            </div>
                        </div>

                    </fieldset>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>