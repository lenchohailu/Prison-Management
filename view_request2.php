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
	<?php
	//include("../modules/session.php"); 
	//include ('../modules/header.php');
	include('DB.php');
	?>
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
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-home"></i>Home</a>
                    </li>
					<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-question"></i>Request <b class="caret"></b></a>
                        <ul class="dropdown-menu">
						
                            
                            <li>
                                <a href="request_form.php"><i class="fa fa-fw fa-send"></i>Send Request</a>
                            </li>
							 <li>
                                <a href="view_request2.php"><i class="fa fa-fw fa-eye"></i>View Request</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="visiting_time2.php"><i class="fa fa-fw fa-envelope-o"></i>visiting Time</a>
                    </li>
					<li>
                       <a href="profile.php"><i class="fa fa-fw fa-user"></i>profile</a>
                    </li>
					<li>
                       <a href="job.php"><i class="fa fa-fw fa-user"></i>Job</a>
                    </li>
                </ul>
				<ul class="nav navbar-nav navbar-right">
				     <li>
                             
							 <a href="?logout" name=""><font color="white">logout</font></a>
                            </li>
				 </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

<div class="container-fluid">
<div class = "row" style ="margin-top:10px">	
					
					
				</div>
				
	<div class="col-md-12">
			<div class = "col-md-10">
					
			</div>
		
	</div>
<div class="container-fluid" style="margin-top:80px;">
	<div class = "row">
		<div class="panel panel-default">
			<div class="panel-body">
			
				<div class="table-responsive">
					
					<table class="table table-hover">
						<thead>
							<tr>
								         
									    <th>Day</th> 
										<th>Morning visiting Time</th>
	
										<th>Afternoon visiting Time</th>
                                       
								
							</tr>
						</thead>
						
						<tbody>
						
							<tr>
                                    <td>Monday-Friday</td>
                                    <td>6:00-7:30 Local Time</td>
									
                                    <td>10:00-11:00 Local Time</td> 
                                    
					
							</tr>
							<tr>
                                    <td>Saturday </td>
                                    <td>5:00-7:30 Local Time</td>
									
                                    <td>10:00-11:00 Local Time </td> 
                                    
									 
									
					
							</tr>
							<tr>
                                    <td>Sunday</td>
                                    <td>2:30-7:30 Local Time</td>
									
                                    <td>7:30-11:30 Local Time</td> 
                                    
									 
									
					
							</tr>
							
						</tbody>
					</table>
					
			
				</div>
			</div>
		</div>
	</div>
</div>






</div>

	<?php include('footer.php'); ?>
	