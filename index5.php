<?php
include('session.php');
include('DB.php'); // Add database connection

// Initialize variables with default values
$total_prisoners = 0;

// Try to get real data from database if connection exists
if (isset($conn) && $conn) {
    // Get total prisoners
    $stats_query = $conn->query("SELECT COUNT(*) as total FROM prisoner");
    if ($stats_query) {
        $total_prisoners = $stats_query->fetch_assoc()['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Woliso Prison Management System | Inspector Dashboard</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Modern Navbar */
        .navbar {
            background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%);
            border: none;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .navbar-brand i {
            margin-right: 8px;
            color: #f1c40f;
        }

        .navbar-nav > li > a {
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-nav > li > a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Main Container */
        .main-container {
            margin-top: 80px;
            padding: 30px 15px;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 40px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.3; }
        }

        .welcome-banner h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .welcome-banner p {
            font-size: 1.1rem;
            margin-top: 10px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }

        /* Sidebar Cards */
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .info-card h4 {
            color: #667eea;
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .info-card h4 i {
            margin-right: 8px;
        }

        .info-card p {
            margin: 10px 0;
            color: #555;
        }

        .info-card i {
            width: 25px;
            color: #667eea;
        }

        .social-links {
            margin-top: 15px;
        }

        .social-links a {
            display: inline-block;
            margin: 5px 10px 5px 0;
            color: #667eea;
            transition: all 0.3s ease;
            font-size: 1.5rem;
        }

        .social-links a:hover {
            color: #764ba2;
            transform: translateY(-3px);
        }

        /* Main Content Card */
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .content-card h2 {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
            border-left: 4px solid #667eea;
            padding-left: 15px;
        }

        .content-card h3 {
            color: #667eea;
            margin-top: 25px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .content-card p {
            color: #555;
            line-height: 1.8;
            font-size: 1rem;
        }

        .content-card ul {
            color: #555;
            line-height: 1.8;
        }

        .content-card ul li {
            margin-bottom: 8px;
        }

        .badge-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            margin: 10px 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .stat-card p {
            margin: 0;
            color: #666;
            font-weight: 500;
        }

        /* Image Carousel */
        .carousel-container {
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .carousel-inner {
            border-radius: 10px;
            overflow: hidden;
        }

        .carousel-caption {
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);
            border-radius: 10px;
        }

        /* Modern Footer */
        footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            padding: 40px 0 20px;
            margin-top: 40px;
        }

        footer a {
            color: #f1c40f;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: #fff;
            text-decoration: none;
        }

        .footer-links {
            margin-bottom: 20px;
        }

        .footer-links a {
            margin: 0 15px;
        }

        /* Alert styling */
        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-banner h1 {
                font-size: 1.5rem;
            }
            .stat-card h3 {
                font-size: 1.3rem;
            }
            .content-card {
                padding: 20px;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>

</head>

<body>

<!-- Modern Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button"
                    class="navbar-toggle"
                    data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index5.php">
                <i class="fa fa-shield"></i> Woliso Prison
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left">
                <li class="active">
                    <a href="index5.php"><i class="fa fa-home"></i> Home</a>
                </li>

                <li>
                    <a href="Archive.php">
                        <i class="fa fa-archive"></i> Archive
                    </a>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-file"></i> Report <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="Report4.php">
                                <i class="fa fa-envelope-o"></i> Generate Report
                            </a>
                        </li>
                        <li>
                            <a href="delete_report4.php">
                                <i class="fa fa-trash"></i> Delete Report
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="calculate_release.php">
                        <i class="fa fa-calendar"></i> Calculate Release
                    </a>
                </li>

                <li>
                    <a href="export.php">
                        <i class="fa fa-download"></i> Export Data
                    </a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Container -->
<div class="main-container">
    <div class="container">

        <!-- Welcome Banner -->
        <div class="welcome-banner animate-fadeInUp">
            <h1><i class="fa fa-gavel"></i> Welcome Inspector</h1>
            <p>Oversee prison operations, conduct inspections, and ensure compliance with standards.</p>
        </div>

        <div class="row">
            <!-- LEFT SIDEBAR -->
            <div class="col-md-3">
                <!-- Contact Card -->
                <div class="info-card animate-fadeInUp" style="animation-delay: 0.1s;">
                    <h4><i class="fa fa-address-card"></i> Contact Details</h4>
                    <p><i class="fa fa-building"></i> Woliso Prison Management System</p>
                    <p><i class="fa fa-map-marker"></i> Kebele 02, Woliso, Oromia, Ethiopia</p>
                    <p><i class="fa fa-phone"></i> (+251) 25 666 0541</p>
                    <p><i class="fa fa-envelope"></i> <a href="mailto:wolisoprison@gmail.com">wolisoprison@gmail.com</a></p>
                    <p><i class="fa fa-clock-o"></i> Mon–Fri: 9:00 AM – 5:00 PM</p>
                </div>

                <!-- Quick Stats Card -->
                <div class="info-card animate-fadeInUp" style="animation-delay: 0.2s;">
                    <h4><i class="fa fa-bar-chart"></i> Quick Statistics</h4>
                    <p><i class="fa fa-users"></i> Total Prisoners: <strong><?= number_format($total_prisoners) ?></strong></p>
                    <p><i class="fa fa-calendar"></i> Year Established: <strong>1985</strong></p>
                    <p><i class="fa fa-building"></i> Capacity: <strong>500</strong></p>
                    <p><i class="fa fa-percent"></i> Occupancy: <strong><?= $total_prisoners > 0 ? round(($total_prisoners / 500) * 100) : 0 ?>%</strong></p>
                </div>

                <!-- Social Media Card -->
                <div class="info-card animate-fadeInUp" style="animation-delay: 0.3s;">
                    <h4><i class="fa fa-share-alt"></i> Connect With Us</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a href="https://www.twitter.com" target="_blank"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.linkedin.com" target="_blank"><i class="fa fa-linkedin"></i></a>
                        <a href="https://www.instagram.com" target="_blank"><i class="fa fa-instagram"></i></a>
                        <a href="https://www.gmail.com" target="_blank"><i class="fa fa-google"></i></a>
                    </div>
                </div>
            </div>

            <!-- CENTER CONTENT -->
            <div class="col-md-6">
                <!-- Stats Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="stat-card animate-fadeInUp" style="animation-delay: 0.1s;">
                            <i class="fa fa-users"></i>
                            <h3><?= number_format($total_prisoners) ?></h3>
                            <p>Total Prisoners</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card animate-fadeInUp" style="animation-delay: 0.2s;">
                            <i class="fa fa-check-circle"></i>
                            <h3>124</h3>
                            <p>Released This Year</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content Card -->
                <div class="content-card animate-fadeInUp" style="animation-delay: 0.3s;">
                    <h2><i class="fa fa-info-circle"></i> About Inspector's Office</h2>
                    <p>Side by side with announced visits, the Office of the Inspector of Prisons have made and will make ad hoc unannounced visits to all prisons not alone during business hours but also during off peak hours including night visits and at weekends.</p>
                    
                    <h3><i class="fa fa-tasks"></i> Inspection Duties</h3>
                    <p>Initially the Inspection Team will carry out an unannounced inspection of a prison. The Team will arrive at approximately 9 am and the inspection will last a minimum of two days. In depth analysis of all areas of the prison will be carried out. The Inspection Team will talk to prisoners and members of staff and examine records.</p>
                    
                    <h3><i class="fa fa-gavel"></i> Key Responsibilities</h3>
                    <ul>
                        <li><i class="fa fa-check-circle text-success"></i> Conduct unannounced inspections of prison facilities</li>
                        <li><i class="fa fa-check-circle text-success"></i> Interview prisoners and staff members</li>
                        <li><i class="fa fa-check-circle text-success"></i> Review prison records and documentation</li>
                        <li><i class="fa fa-check-circle text-success"></i> Ensure compliance with prison standards and regulations</li>
                        <li><i class="fa fa-check-circle text-success"></i> Submit detailed inspection reports</li>
                    </ul>
                    
                    <div class="alert alert-info" style="margin-top: 20px;">
                        <i class="fa fa-info-circle"></i> <strong>Note:</strong> All inspections are conducted with the highest level of professionalism and integrity.
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDEBAR - Image Carousel -->
            <div class="col-md-3">
                <div class="carousel-container animate-fadeInUp" style="animation-delay: 0.2s;">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#myCarousel" data-slide-to="1"></li>
                            <li data-target="#myCarousel" data-slide-to="2"></li>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="img/prison.jpg" class="img-responsive" alt="Prison Facility">
                                <div class="carousel-caption">
                                    <p>Modern Correctional Facility</p>
                                </div>
                            </div>
                            <div class="item">
                                <img src="img/prison2.jpg" class="img-responsive" alt="Rehabilitation">
                                <div class="carousel-caption">
                                    <p>Rehabilitation Programs</p>
                                </div>
                            </div>
                            <div class="item">
                                <img src="img/prison3.jpg" class="img-responsive" alt="Staff Training">
                                <div class="carousel-caption">
                                    <p>Professional Staff Training</p>
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                            <span class="icon-prev"></span>
                        </a>
                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                            <span class="icon-next"></span>
                        </a>
                    </div>
                </div>

                <!-- Quick Tip Card -->
                <div class="info-card animate-fadeInUp" style="margin-top: 20px; animation-delay: 0.3s;">
                    <h4><i class="fa fa-lightbulb-o"></i> Inspector's Tip</h4>
                    <p>Always conduct unannounced visits to ensure accurate assessment of prison conditions and staff performance.</p>
                    <hr>
                    <p class="text-muted small"><i class="fa fa-clock-o"></i> Last login: <?= date('F j, Y g:i A') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h4><i class="fa fa-shield"></i> Woliso Prison</h4>
                <p>Committed to justice, rehabilitation, and public safety since 1985.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="footer-links">
                    <a href="#">Privacy Policy</a> | 
                    <a href="#">Terms of Service</a> | 
                    <a href="#">Contact Us</a>
                </div>
                <p>&copy; <?php echo date('Y'); ?> Woliso Prison Management System. All rights reserved.</p>
            </div>
            <div class="col-md-4 text-right">
                <p><i class="fa fa-phone"></i> Emergency: 911</p>
                <p><i class="fa fa-envelope"></i> info@wolisoprison.gov.et</p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
    $('.carousel').carousel({
        interval: 5000
    });
    
    // Add animation on scroll
    $(window).scroll(function() {
        $('.animate-fadeInUp').each(function() {
            var elementTop = $(this).offset().top;
            var viewportTop = $(window).scrollTop();
            if (elementTop < viewportTop + 500) {
                $(this).css('opacity', '1');
            }
        });
    });
</script>

</body>
</html>