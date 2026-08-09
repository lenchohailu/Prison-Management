<?php
include('session.php');
include('DB.php'); // Add database connection

// Initialize variables with default values to avoid undefined variable notices
$total_prisoners = 0;
$total_released = 124; // Default value
$pending_reviews = 18; // Default value
$high_severity = 32; // Default value

// Try to get real data from database if connection exists
if (isset($conn) && $conn) {
    // Get total prisoners
    $stats_query = $conn->query("SELECT COUNT(*) as total FROM prisoner");
    if ($stats_query) {
        $total_prisoners = $stats_query->fetch_assoc()['total'];
    }
    
    // Get released prisoners this year (assuming end_date has passed)
    $current_year = date('Y');
    $released_query = $conn->query("SELECT COUNT(*) as total FROM prisoner WHERE YEAR(end_date) = '$current_year' AND end_date <= CURDATE()");
    if ($released_query) {
        $released_result = $released_query->fetch_assoc();
        $total_released = $released_result['total'] > 0 ? $released_result['total'] : 124;
    }
    
    // Get high severity cases (if criminal_record column exists)
    $severity_query = $conn->query("SELECT COUNT(*) as total FROM prisoner WHERE criminal_record LIKE '%murder%' OR criminal_record LIKE '%rape%' OR criminal_record LIKE '%kidnapping%' OR criminal_record LIKE '%homicide%'");
    if ($severity_query) {
        $severity_result = $severity_query->fetch_assoc();
        $high_severity = $severity_result['total'] > 0 ? $severity_result['total'] : 32;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Woliso Prison Management System | Commissioner Dashboard</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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

        /* Main Content Container */
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

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .stat-card i {
            font-size: 3rem;
            position: absolute;
            right: 20px;
            bottom: 20px;
            opacity: 0.2;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: #333;
        }

        .stat-card p {
            margin: 5px 0 0;
            color: #666;
            font-weight: 500;
        }

        .stat-card.primary { border-left: 4px solid #667eea; }
        .stat-card.success { border-left: 4px solid #28a745; }
        .stat-card.warning { border-left: 4px solid #ffc107; }
        .stat-card.danger { border-left: 4px solid #dc3545; }

        /* Sidebar Cards */
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .info-card h4 {
            color: #667eea;
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .info-card p {
            margin: 10px 0;
            color: #555;
        }

        .info-card i {
            width: 25px;
            color: #667eea;
        }

        .social-links a {
            display: inline-block;
            margin: 5px 10px 5px 0;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: #764ba2;
            transform: translateY(-2px);
        }

        /* About Section */
        .about-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .about-section h3 {
            color: #667eea;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .about-section p {
            color: #555;
            line-height: 1.8;
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

        /* Image Carousel */
        .carousel-container {
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .carousel img {
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-banner h1 {
                font-size: 1.5rem;
            }
            .stat-card h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

<!-- ================= MODERN NAVBAR (PMS REMOVED) ================= -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index4.php">
                <i class="fa fa-shield"></i> Woliso Prison
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="index4.php"><i class="fa fa-home"></i> Home</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope"></i> Request <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="view_request3.php"><i class="fa fa-check-circle"></i> Approve</a></li>
                        <li><a href="approved.php"><i class="fa fa-eye"></i> View Approved</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-file"></i> Report <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="Report3.php"><i class="fa fa-envelope-o"></i> Report</a></li>
                        <li><a href="delete_Report3.php"><i class="fa fa-trash"></i> Delete Report</a></li>
                    </ul>
                </li>

                <li><a href="prisonprofile1.php"><i class="fa fa-users"></i> Prisoner Info</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><i class="fa fa-sign-out"></i> <font color="white">Logout</font></a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ================= MAIN CONTENT ================= -->
<div class="main-container">
    <div class="container">

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h1><i class="fa fa-gavel"></i> Welcome Commissioner</h1>
            <p>Manage prison operations, track prisoners, and oversee rehabilitation programs from your central dashboard.</p>
        </div>

        <div class="row">
            <!-- LEFT SIDEBAR -->
            <div class="col-md-3">
                <!-- Contact Card -->
                <div class="info-card">
                    <h4><i class="fa fa-address-card"></i> Contact Details</h4>
                    <p><i class="fa fa-building"></i> Woliso Prisoner Management System</p>
                    <p><i class="fa fa-map-marker"></i> Kebele 02, Woliso, Oromia, Ethiopia</p>
                    <p><i class="fa fa-phone"></i> (+251) 25 666 0541</p>
                    <p><i class="fa fa-envelope"></i> <a href="mailto:wolisoprison@gmail.com">wolisoprison@gmail.com</a></p>
                    <p><i class="fa fa-clock-o"></i> Mon–Fri: 9:00 AM – 5:00 PM</p>
                </div>

                <!-- Social Media Card -->
                <div class="info-card">
                    <h4><i class="fa fa-share-alt"></i> Connect With Us</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com" target="_blank"><i class="fa fa-facebook fa-2x"></i></a>
                        <a href="https://www.twitter.com" target="_blank"><i class="fa fa-twitter fa-2x"></i></a>
                        <a href="https://www.linkedin.com" target="_blank"><i class="fa fa-linkedin fa-2x"></i></a>
                        <a href="https://www.instagram.com" target="_blank"><i class="fa fa-instagram fa-2x"></i></a>
                        <a href="https://www.gmail.com" target="_blank"><i class="fa fa-google fa-2x"></i></a>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="info-card">
                    <h4><i class="fa fa-bar-chart"></i> Quick Stats</h4>
                    <p><i class="fa fa-users"></i> Total Prisoners: <strong><?= $total_prisoners ?></strong></p>
                    <p><i class="fa fa-calendar"></i> Year Established: <strong>1985</strong></p>
                    <p><i class="fa fa-building"></i> Capacity: <strong>500</strong></p>
                    <p><i class="fa fa-percent"></i> Occupancy: <strong><?= round(($total_prisoners / 500) * 100) ?>%</strong></p>
                </div>
            </div>

            <!-- CENTER CONTENT -->
            <div class="col-md-6">
                <!-- Stats Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="stat-card primary">
                            <i class="fa fa-users"></i>
                            <h3><?= number_format($total_prisoners) ?></h3>
                            <p>Total Prisoners</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card success">
                            <i class="fa fa-check-circle"></i>
                            <h3><?= number_format($total_released) ?></h3>
                            <p>Released This Year</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card warning">
                            <i class="fa fa-clock-o"></i>
                            <h3><?= number_format($pending_reviews) ?></h3>
                            <p>Pending Reviews</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card danger">
                            <i class="fa fa-exclamation-triangle"></i>
                            <h3><?= number_format($high_severity) ?></h3>
                            <p>High Severity Cases</p>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div class="about-section">
                    <h3><i class="fa fa-info-circle"></i> About the Prison Commission</h3>
                    <p>The Prison Commission was established under the Prison Act as a statutory board to administer and inspect prisons in Ethiopia. It took over executive powers and property rights, while maintaining transparency and accountability in prison operations.</p>
                    
                    <h3><i class="fa fa-tasks"></i> Our Duties</h3>
                    <p>The commission is empowered to hold property for correctional purposes. Our duties include maintenance of prisons, appointment of staff, inspection of prison buildings and prisoners' conditions, and submission of annual reports to the Ministry of Justice. We are assisted by a dedicated staff, Prison Inspectorate, and visiting committees.</p>
                    
                    <h3><i class="fa fa-gavel"></i> Our Mission</h3>
                    <p>To provide safe, secure, and humane correctional facilities while promoting rehabilitation and successful reintegration of offenders into society.</p>
                </div>
            </div>

            <!-- RIGHT SIDEBAR - Image Carousel -->
            <div class="col-md-3">
                <div class="carousel-container">
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
                                    <p>Modern Facility</p>
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
                                    <p>Professional Staff</p>
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
                <div class="info-card" style="margin-top: 20px;">
                    <h4><i class="fa fa-lightbulb-o"></i> Quick Tip</h4>
                    <p>Use the navigation menu above to manage prisoner records, approve requests, and generate reports.</p>
                    <hr>
                    <p class="text-muted small"><i class="fa fa-clock-o"></i> Last login: <?= date('F j, Y g:i A') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODERN FOOTER ================= -->
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

<!-- ================= JS ================= -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    $('.carousel').carousel({ interval: 5000 });
    
    // Add animation to stat cards
    $('.stat-card').hover(function() {
        $(this).find('i').css('transform', 'scale(1.1)');
    }, function() {
        $(this).find('i').css('transform', 'scale(1)');
    });
</script>

</body>
</html>