<?php
include('session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Prison Management System</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>

/* GENERAL PAGE BACKGROUND */
body {
    background: #e6e6e6;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* NAVBAR ENHANCEMENT */
.navbar-inverse {
    background: linear-gradient(90deg, #000428, #004e92);
    border: none;
}
.navbar-inverse .navbar-brand,
.navbar-inverse .navbar-nav > li > a {
    color: white !important;
    font-weight: 500;
}
.navbar-inverse .navbar-nav > li > a:hover {
    color: #ffd700 !important;
}

/* LEFT CONTACT PANEL */
.left-panel {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* CENTER CONTENT CARD */
.center-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    margin-top: 20px;
    box-shadow: 0 6px 14px rgba(0,0,0,0.18);
    border-left: 5px solid #2e8b57;
}

/* RIGHT PANEL */
.right-panel {
    padding: 0;
    margin-top: 20px;
}

/* WELCOME TEXT */
@keyframes fadeSlide {
    0% { opacity: 0; transform: translateY(-20px); }
    100% { opacity: 1; transform: translateY(0); }
}
.welcome-text {
    font-size: 26px;
    color: #2e8b57;
    text-align: center;
    font-style: italic;
    animation: fadeSlide 1.5s ease-out;
}

/* SECTION TITLE */
.section-title {
    color: #444;
    font-weight: 600;
    border-left: 4px solid #2e8b57;
    padding-left: 10px;
}

/* FOOTER */
footer {
    background: #1a1a1a;
    color: white;
    padding: 15px;
    text-align: center;
    margin-top: 30px;
}
footer p { margin: 0; }

</style>

</head>

<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navMenu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="index1.php">
                Prison Management System
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navMenu">

            <ul class="nav navbar-nav navbar-left">

                <li class="active"><a href="index1.php">Home</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        User <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="user_account.php">Create Account</a></li>
                        <li><a href="edit_account.php">Edit Account</a></li>
                        <li><a href="update2.php">Delete Account</a></li>
                    </ul>
                </li>

                <li><a href="indexE.php"><i class="fa fa-download"></i> Backup</a></li>
                <li><a href="View_report.php">View Report</a></li>
                <li><a href="search.php">Search</a></li>

                <!-- ✅ HELP REQUEST ADDED -->
                <li>
                    <a href="help_requested.php">
                        <i class="fa fa-question-circle"></i> Help Request
                    </a>
                </li>

            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php">Logout</a></li>
            </ul>

        </div>
    </div>
</nav>

<br><br><br>

<div class="container">

<div class="row">

    <!-- LEFT PANEL -->
    <div class="col-md-3">
        <div class="left-panel">
            <h3 class="section-title">Contact Details</h3>
            <p>Woliso Prison Management System<br>Address: Kebele 02</p>

            <p><i class="fa fa-phone"></i> <b>Phone:</b> (+251) 25 666 0541</p>
            <p><i class="fa fa-envelope-o"></i> <b>Email:</b>
                <a href="mailto:wolisoprison@gmail.com">wolisoprison@gmail.com</a>
            </p>
            <p><i class="fa fa-clock-o"></i> <b>Hours:</b> Mon–Fri: 9:00 AM – 5:00 PM</p>

            <hr>

            <h4 class="section-title">Follow Us</h4>
            <p><a href="https://facebook.com" target="_blank">Facebook</a></p>
            <p><a href="https://gmail.com" target="_blank">Gmail</a></p>
            <p><a href="https://twitter.com" target="_blank">Twitter</a></p>
            <p><a href="https://google.com" target="_blank">Google</a></p>
        </div>
    </div>

    <!-- CENTER PANEL -->
    <div class="col-md-6">
        <div class="center-card">

            <h2 class="welcome-text">Welcome to Administrator Page</h2>

            <h3 class="section-title">Vision</h3>
            <p style="color:#2e8b57;">
                To become an institution with good governance that respects prisoner rights and produces rehabilitated citizens.
            </p>

            <h3 class="section-title">Mission</h3>
            <p style="color:#2e8b57;">
                To ensure public safety by rehabilitating detainees and providing secure correctional services.
            </p>

        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="col-md-3">
        <div class="right-panel">

            <div id="myCarousel" class="carousel slide">

                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                </ol>

                <div class="carousel-inner">
                    <div class="item active">
                        <img src="image1/pr4.jpg" style="width:100%; border-radius:8px;">
                    </div>
                </div>

                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="icon-prev"></span>
                </a>

                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="icon-next"></span>
                </a>

            </div>

        </div>
    </div>

</div>

</div>

<!-- FOOTER -->
<footer>
    <p>© Woliso Prison Management System</p>
</footer>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
$('.carousel').carousel({
    interval: 3000
});
</script>

</body>
</html>