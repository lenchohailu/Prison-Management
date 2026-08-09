<?php
    // Include database connection
    include("DB.php");
    // Uncomment if session management is needed
    // include("../modules/session.php");

    // Handle logout if requested
    if (isset($_GET['logout'])) {
        // Assuming session.php has a logout function or destroy session
        session_destroy();
        header("Location: index4.php"); // Redirect to home after logout
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Contact Ambo Prison Management System for inquiries, support, and information.">
    <meta name="author" content="Ambo Prison Management System">

    <title>Contact - Ambo Prison Management System</title>

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
        /* Custom styles for better layout */
        .contact-form {
            margin-top: 30px;
        }
        .map-container {
            margin-bottom: 20px;
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
                <a class="navbar-brand" href="index4.php">Ambo Prison Management System</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <a href="index4.php"><i class="fa fa-fw fa-home"></i> Home</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-compass"></i> Prisoner <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="inspector/add_prison.php"><i class="fa fa-fw fa-save"></i> Register</a>
                            </li>
                            <li>
                                <a href="search/update.php"><i class="fa fa-fw fa-pencil"></i> Update</a>
                            </li>
                            <li>
                                <a href="search/releasing.php"><i class="fa fa-fw fa-pencil"></i> Releasing Day</a>
                            </li>
                            <li>
                                <a href="search/report.php"><i class="fa fa-fw fa-search"></i> Report</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="archive.php"><i class="fa fa-fw fa-archive"></i> Archive</a>
                    </li>
                    <li class="active">
                        <a href="contact3.php"><i class="fa fa-fw fa-envelope-o"></i> Contact</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="?logout"><i class="fa fa-fw fa-sign-out"></i> Logout</a>
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
                    <li><a href="index4.php">Home</a></li>
                    <li class="active">Contact</li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <!-- Content Row -->
        <div class="row">
            <!-- Map Column -->
            <div class="col-md-8">
                <div class="map-container">
                    <h3>Our Location</h3>
                    <!-- Embedded Google Map - Update coordinates for actual prison location -->
                    <iframe width="100%" height="400px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
                        src="https://maps.google.com/maps?hl=en&amp;ie=UTF8&amp;ll=37.0625,-95.677068&amp;spn=56.506174,79.013672&amp;t=m&amp;z=4&amp;output=embed" 
                        title="Ambo Prison Management System Location">
                    </iframe>
                </div>
            </div>
            <!-- Contact Details Column -->
            <div class="col-md-4">
                <div class="contact-details">
                    <h3>Contact Details</h3>
                    <address>
                        <strong>Ambo Prison Management System</strong><br>
                        Ambo Buro Office, CA 90210<br>
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

        <!-- Contact Form -->
        <div class="row contact-form">
            <div class="col-md-8">
                <h3>Send us a Message</h3>
                <form name="sentMessage" id="contactForm" method="post" action="contact_process.php" novalidate>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="name">Full Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required data-validation-required-message="Please enter your name.">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="phone">Phone Number:</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required data-validation-required-message="Please enter your phone number.">
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="email">Email Address:</label>
                            <input type="email" class="form-control" id="email" name="email" required data-validation-required-message="Please enter your email address.">
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label for="message">Message:</label>
                            <textarea rows="10" cols="100" class="form-control" id="message" name="message" required data-validation-required-message="Please enter your message" maxlength="999" style="resize:none"></textarea>
                        </div>
                    </div>
                    <div id="success"></div>
                    <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>&copy; <?php echo date('Y'); ?> Ambo Prison Management System. All rights reserved.</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <!-- Do not edit these files! In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

</body>

</html>
