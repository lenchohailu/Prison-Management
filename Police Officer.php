
<?php
include('session.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Prison Management System  </title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    

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
                
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li >
                        <a href="index3.php"></i>Home</a>
                    </li>
					 <li  class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Attendance</b></a>
                        <ul class="dropdown-menu">
						<li>
                                <a href="Take_Attendance.php"></i>Take Attendance</a>
                            </li>
                            <li>
                                <a href="attendance.php"></i>View Attendance</a>
                            </li>
							<li>
                                <a href="delete_all_attend.php"></i>delete Attendance</a>
                            </li>    
                        </ul>
                    </li>
					
					 <li>
                        <a href="profilepo.php">Prisoner info</a>
                    </li>
					<li  class="active" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Schedule</a>
                        <ul class="dropdown-menu">
						
                            <li>
                                <a href="Add_Schedule.php">Add Job Schedule</a>
                            </li>
							<li>
                                <a href="edit_schedule.php">Edit Job schedule</a>
                            </li>
							<li>
                                <a href="visting_time3.php">Add Visiting Time</a>
                            </li>
							<li>
                                <a href="viewandedit_visitng.php">Edit Visiting Time</a>
                            </li>   							
                        </ul>
                    </li>
                  
					<li  class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Report</a>
                        <ul class="dropdown-menu">
						
                            <li>
                                <a href="Report1.php">Report</a>
                            </li>
							<li>
                                <a href="delete_report1.php">delete Report</a>
                            </li>    
                        </ul>
                    </li>
					<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Job</a>
                        <ul class="dropdown-menu">
						
                            <li>
                                <a href="Jobpo.php">Announce</a>
                            </li>
							<li>
                                <a href="delete_job.php">Delete</a>
                            </li>    
                        </ul>
                    </li>
					 <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-compass"></i>Prisoner<b class="caret"></b></a>
                        <ul class="dropdown-menu">
						
                            <li>
                                <a href="add_prison.php">Register</a>
                            </li>
							<li>
                                <a href="update4.php">Update</a>
                            </li>
							<li>
                                <a href="updt.php">Update2</a>
                            </li>
							<li>
                                <a href="Releasing_day.php">Releasing Day</a>
                            </li>
							<li>
                                <a href="delete.php">Delete</a>
                            </li>
							<li>
                        <a href="upld.php">Upload Photo</a>
                    </li>
                            
                        </ul>
                    </li>
                </ul>
				<ul class="nav navbar-nav navbar-right">
				     <li>
                             
							 <a href="login.php" name=""><font color="white">logout</font></a>
                            </li>
				 </ul>
				
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Header Carousel -->
	<div class="container-fluid" style="background-color:gray;">
	<div class="row">

            <!-- Contact Details Column -->
         <div class="col-md-2" >
                           
			
                <h3>Contact Details</h3>
                <p>
                   Ambo prisoner Management System<br>Address:kebele 01<br>
                </p>
                <p><i class="fa fa-phone"></i> 
                    <abbr title="Phone">P</abbr>: (+251) 25 666 0541</p>
                <p><i class="fa fa-envelope-o"></i> 
                    <abbr title="Email">E</abbr>: <a href="mailto:hararprison@gmail.com"><font color='black'>amboprison@gmail.com</font></a>
                </p>
                <p><i class="fa fa-clock-o"></i> 
                    <abbr title="Hours">H</abbr>: Monday - Friday: 9:00 AM to 5:00 PM</p>
                <hr>
                        <a href= "www.facebook.com" ><font color='black'>facebook</font></a><br />
                    
                   
                        <a href="www.gmail.com" width="80" height="100"><font color='black'>Gmail</font></a><br />
                    
                    
                        <a href="www.twitter.com" width="60" height="60"><font color='black'>twitter</font></a><br />
                    
                    
                        <a href="www.google.com" width="60" height="60"><font color='black'>Google</font></a>
                    
                </hr>
            
            </div>
			<div class="col-md-7" style="background-color:white;">
                <!-- Embedded Google Map -->
				<p> <ol class="breadcrumb" style="text-align: centre">
				
				
				<h2 class="page-header">
                     <font color='green'><i><marque direction='left' speed='10s'> Welcome to Police officer Page</marque></i> </font>
                </h2>
				
				 
				<li class="active"><h3>Role of Officer</h3></li>
				 
               </ol></p>
			<font color='green'>Prison officers must maintain order and daily operations of the facility and are responsible for the care, custody, and control of inmates. A correction officer has a responsibility to control inmates who may be dangerous, and that society themselves do not wish to accommodate. An officer must always prevent disturbances, assaults, and escapes by supervising activities and work assignments of inmates. Officers have a responsibility to protect the public from incarcerated criminals, protect fellow officers from inmates and protect inmates from other inmates at all times. An officer must be alert and aware of any and all movement taking place inside the facility. Prevention is one of the key components to an officer's duties. Officers can utilize prevention by routinely searching inmates and their living quarters for potential threats such as weapons or drugs. An officer must make their presence known at all times and remain assertive and refuse to back down. An officer must be a disciplinarian and enforce the rules and punish when rules are violated. Correction officers also must take full concern for the health and safety of the facility. Officers check for unsanitary conditions, fire hazards, and/or any evidence of tampering or damage to locks, bars, grilles, doors, and gates. Officers must screen all incoming and outgoing mail as well as all visitors as a prevention method for future issues that could cause risk to safety and security of the facilities, inmates and staff. Correction officers also must assist in transportation responsibilities that may include transfers to other facilities, medical appointments, court appearances and other approved locations. Correction officers may assist police officer's on/off duty depending on their peace officer status and jurisdiction</font>
			    <ol class="breadcrumb">
				
				
               </ol>
<font color='green'>Corrections officers' training will vary from jurisdiction to jurisdiction as well as facility to facility depending on the legislated power given, the nature of the facilities, or even the socioeconomics of the region. Training may be provided by external agencies or at the facility with a peer-group or supervisor instructor. </font>
            </div>
            <div class="col-md-3">
    <header id="myCarousel" class="carousel slide">
        <!-- Indicators -->


        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
              
				 
                <!--<div class="carousel-caption">
                    <h2>Caption 1</h2>
                </div>-->
            </div>
         
		 
		 
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>
    </header>
	
	</div>
	</div>

    </div>
        
        

        

        <!-- Footer -->
         <?php include('footer.php'); ?>

    
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
	


    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>

</body>

</html> 
