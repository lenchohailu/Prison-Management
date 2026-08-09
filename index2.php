<?php
include('session.php');
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

<style>
body {
    background: #f4f6f9;
}

.hero-title {
    background: linear-gradient(90deg, #2c3e50, #3498db);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 20px;
}

.card-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.sidebar-box {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="nav navbar-nav">
                
                <li class="active">
                    <a href="index2.php"><i class="fa fa-home"></i> Home</a>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Request <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="login_request.php">Send Request</a></li>
                        <li><a href="view_request.php">View Request</a></li>
                    </ul>
                </li>

                <li><a href="visiting_time2.php">Visiting Time</a></li>

                <li><a href="job.php">Job</a></li>

                <!-- ✅ USER CAN ONLY VIEW SCHEDULE -->
                <li>
                    <a href="view_schedule.php">
                        <i class="fa fa-calendar"></i> Scheduled Job
                    </a>
                </li>

            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="logout.php" style="color:white;">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>

        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container" style="margin-top:90px;">

    <div class="hero-title">
        <h2>Welcome to Prisoner Management System</h2>
    </div>

    <div class="row">

        <!-- LEFT SIDEBAR -->
        <div class="col-md-3">
            <div class="sidebar-box">
                <h4>Contact Details</h4>
                <p><b>Woliso Prison Management System</b><br>Kebele 02</p>
                <p><i class="fa fa-phone"></i> (+251) 25 666 0541</p>
                <p><i class="fa fa-envelope"></i> wolisoprison@gmail.com</p>

                <hr>

                <a href="#">Facebook</a><br>
                <a href="#">Gmail</a><br>
                <a href="#">Twitter</a><br>
                <a href="#">Google</a>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-6">
            <div class="card-box">

                <h3>About Prisoner</h3>
                <p style="color:green;">
                    Prisoners often experience psychological and social effects due to long-term confinement...
                </p>

                <hr>

                <h3>Mission</h3>
                <p style="color:green;">
                    To ensure rehabilitation and reintegration into society.
                </p>

            </div>
        </div>

        <!-- RIGHT IMAGE -->
        <div class="col-md-3">
            <div class="card-box text-center">
                <h4>System View</h4>
                <img src="image1/f.PNG" class="img-responsive" style="border-radius:10px;">
            </div>
        </div>

    </div>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>