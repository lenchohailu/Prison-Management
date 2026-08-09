<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Woliso Prison</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: #333;
        }

        .navbar {
            background-color: #2d3436 !important;
            border: none;
        }

        .navbar-brand,
        .navbar-nav > li > a {
            color: #fff !important;
        }

        .container-fluid {
            margin-top: 70px;
            padding: 20px;
        }

        .row {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 20px;
        }

        .footer {
            background-color: #2d3436;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Brand text removed -->
            <a class="navbar-brand" href="index.php">
                <i class="fa fa-home"></i> Home
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navbar-main">
            <ul class="nav navbar-nav navbar-left">
                <li class="active">
                    <a href="visiting_time.php">
                        <i class="fa fa-clock-o"></i> Visiting Time
                    </a>
                </li>
                <li>
                    <a href="About.php">
                        <i class="fa fa-info-circle"></i> About Us
                    </a>
                </li>
                <li>
                    <a href="HelpDesk.php">
                        <i class="fa fa-question-circle"></i> Help
                    </a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="login.php">
                        <i class="fa fa-sign-in"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">

        <!-- Left Column -->
        <div class="col-md-2">
            <h4>Contact Details</h4>
            <p><i class="fa fa-map-marker"></i> Woliso Prison, Kebele 02</p>
            <p><i class="fa fa-phone"></i> (+251) 25 666 0541</p>
            <p><i class="fa fa-envelope"></i> wolisoprison@gmail.com</p>
        </div>

        <!-- Center Content -->
        <div class="col-md-7">
            <h2 class="text-center">Welcome to Woliso Prison</h2>

            <img src="image1/log.jpg" class="img-responsive" alt="Woliso Prison">

            <h3>Background</h3>
            <p>
                Woliso Prison is a governmental institution responsible for public security,
                rehabilitation of prisoners, and community awareness. The prison currently
                serves more than 1,500 inmates and operates under regulated legal standards.
            </p>

            <h3>Vision</h3>
            <p>
                To ensure good governance, respect human rights, and rehabilitate prisoners
                into productive citizens.
            </p>

            <h3>Mission</h3>
            <p>
                To maintain peace and security through lawful detention, rehabilitation,
                and reintegration programs.
            </p>
        </div>

        <!-- Right Column -->
        <div class="col-md-3">
            <div id="myCarousel" class="carousel slide">
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="image1/slide1.jpg" class="img-responsive">
                    </div>
                    <div class="item">
                        <img src="image1/slide2.jpg" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2026 Woliso Prison. All Rights Reserved.</p>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
