<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Woliso Prisoner Management System">
    <title>Woliso Prisoner Management System</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/modern-business.css" rel="stylesheet">
    <link href="../css/commonStyles.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <style>
        body {
            background-image: url(../image/bg.png);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            padding-top: 70px; /* offset for navbar */
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 60px;
            background-color: black;
            color: white;
            line-height: 60px;
            text-align: center;
        }

        .navbar-nav > li > a {
            color: white !important;
        }

        .navbar-inverse {
            background-color: #222;
            border-color: #080808;
        }

        .dropdown-menu > li > a {
            color: #333 !important;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Navbar header -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index3.php">Woliso PMS</a>
            </div>
            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index3.php"><i class="fa fa-home"></i> Home</a></li>

                    <!-- Attendance Dropdown -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-check-square-o"></i> Attendance <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Take_Attendance.php">Take Attendance</a></li>
                            <li><a href="attendance.php">View Attendance</a></li>
                            <li><a href="editAttend.php">Edit Attendance</a></li>
                            <li><a href="count.php">Count Attendance</a></li>
                            <li><a href="delete_all_attend.php">Delete Attendance</a></li>
                        </ul>
                    </li>

                    <li><a href="profilepo.php"><i class="fa fa-user"></i> Prisoner Info</a></li>

                    <!-- Schedule Dropdown -->
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-calendar"></i> Schedule <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Add_Schedule.php">Add Job Schedule</a></li>
                            <li><a href="edit_schedule.php">Edit Job Schedule</a></li>
                            <li><a href="visting_time3.php">Add Visiting Time</a></li>
                            <li><a href="viewandedit_visitng.php">Edit Visiting Time</a></li>
                        </ul>
                    </li>

                    <!-- Report Dropdown -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text"></i> Report <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Report1.php">Report</a></li>
                            <li><a href="delete_report1.php">Delete Report</a></li>
                        </ul>
                    </li>

                    <!-- Job Dropdown -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-briefcase"></i> Job <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Jobpo.php">Announce</a></li>
                            <li><a href="delete_job.php">Delete</a></li>
                        </ul>
                    </li>

                    <!-- Prisoner Dropdown -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> Prisoner <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="add_prison.php">Register</a></li>
                            <li><a href="update4.php">Update</a></li>
                            <li><a href="updt.php">Update2</a></li>
                            <li><a href="Releasing_day.php">Releasing Day</a></li>
                            <li><a href="delete.php">Delete</a></li>
                            <li><a href="upld.php">Upload Photo</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Logout -->
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header text-center text-white">Welcome to Woliso Prisoner Management System</h2>
                <p class="text-center text-white">Use the navigation above to manage prisoners, attendance, schedules, reports, and jobs.</p>
            </div>
        </div>

        <!-- Example content area -->
        <div class="row">
            <div class="col-md-12">
                <!-- Add page content here -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date('Y'); ?> Woliso Prisoner Management System
    </footer>

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
