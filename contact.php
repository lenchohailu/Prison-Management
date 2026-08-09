<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="HU Prison Management System - Efficient and secure management of prison operations, including visiting times, prisoner search, and more.">
    <meta name="author" content="HU Prison Management System">

    <title>HU Prison Management System</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        /* Custom styles for better readability */
        .vision-mission {
            margin-bottom: 30px;
        }
        .contact-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">HU Prison Management System</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-home"></i> Home</a>
                    </li>
                    <li>
                        <a href="modules/visiting_time.php"><i class="fa fa-fw fa-eye"></i> Visiting Time</a>
                    </li>
                    <li>
                        <a href="search.php"><i class="fa fa-fw fa-search"></i> Search Prisoner</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="modules/login.php"><i class="fa fa-fw fa-user"></i> Login</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container" style="margin-top: 80px;">

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="active"><h1>HU Prison Management System</h1></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <!-- Content Row -->
        <div class="row">
            <!-- Vision and Mission Column -->
            <div class="col-md-8">
                <section class="vision-mission">
                    <h2>Vision</h2>
                    <p>The HU Prison Management System aims to revolutionize prison administration by integrating advanced technology for secure, efficient, and humane management of inmates. We envision a system that promotes rehabilitation, ensures safety, and fosters transparency in correctional facilities.</p>
                </section>

                <section class="vision-mission">
                    <h2>Mission</h2>
                    <p>Our mission is to provide a comprehensive platform for managing prison operations, including inmate records, attendance tracking, visiting schedules, and search functionalities. We are committed to upholding justice, enhancing operational efficiency, and supporting the rehabilitation process through innovative digital solutions.</p>
                </section>
            </div>

            <!-- Contact Details Column -->
            <div class="col-md-4">
                <div class="contact-details">
                    <h3>Contact Details</h3>
                    <address>
                        <strong>HU Prison Management System</strong><br>
                        Hu Buro Office, CA 90210<br>
                    </address>
                    <p><i class="fa fa-phone" aria-hidden="true"></i> 
                        <abbr title="Phone">P</abbr>: (+125) 09170</p>
                    <p><i class="fa fa-envelope-o" aria-hidden="true"></i> 
                        <abbr title="Email">E</abbr>: <a href="mailto:name@example.com">name@example.com</a>
                    </p>
                    <p><i class="fa fa-clock-o" aria-hidden="true"></i> 
                        <abbr title="Hours">H</abbr>: Monday - Friday: 9:00 AM to 5:00 PM</p>
                    <ul class="list-unstyled list-inline list-social-icons">
                        <li>
                            <a href="#" aria-label="Facebook"><i class="fa fa-facebook-square fa-2x" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#" aria-label="LinkedIn"><i class="fa fa-linkedin-square fa-2x" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#" aria-label="Twitter"><i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#" aria-label="Google Plus"><i class="fa fa-google-plus-square fa-2x" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>&copy; <?php echo date('Y'); ?> HU Prison Management System. All rights reserved.</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript (if needed in future) -->
    <!-- Do not edit these files! In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

</body>

</html>
