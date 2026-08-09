<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prison Management System</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>

<?php
// Secure Database Connection
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "prisons");

$connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if ($connection->connect_error) {
    die("Database connection failed: " . $connection->connect_error);
}
?>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-menu">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="nav-menu">
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <a href="index.php">
                        <i class="fa fa-fw fa-home"></i> Return to Home
                    </a>
                </li>

                <li>
                    <a href="visiting_time.php">
                        <i class="fa fa-fw fa-envelope-o"></i> Visiting Time
                    </a>
                </li>
            </ul>

        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container" style="margin-top:90px;">
    <div class="panel panel-default">
        <div class="panel-heading text-center">
            <h3>Visiting Time Schedule</h3>
        </div>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Morning Visiting Time</th>
                            <th>Afternoon Visiting Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Monday - Friday</td>
                            <td>6:00 - 7:30 Local Time</td>
                            <td>10:00 - 11:00 Local Time</td>
                        </tr>

                        <tr>
                            <td>Saturday</td>
                            <td>5:00 - 7:30 Local Time</td>
                            <td>10:00 - 11:00 Local Time</td>
                        </tr>

                        <tr>
                            <td>Sunday</td>
                            <td>2:30 - 7:30 Local Time</td>
                            <td>7:30 - 11:30 Local Time</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- jQuery -->
<script src="js/jquery.js"></script>
<!-- Bootstrap JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>
</html>
