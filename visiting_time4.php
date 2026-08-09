		
	<?php
	//include ('header.php');
	include('DB.php');
	?>
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
					 <li>
                              
								<a href="visiting_time.php"><i class="fa fa-fw fa-eye"></i>Visiting Time</a>
                      </li>
					 <li>
                                <a href="update.php"><i class="fa fa-fw fa-search"></i>Search Prisoner</a>
                      </li>
          
                </ul>
				<ul class="nav navbar-nav navbar-right">
				     <li>
                             <a href="login.php"><i class="fa fa-fw fa-user"></i>Login</a>
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
							
							<tr>
                                    <td>Manday</td>
                                    <td></td>
									
                                    <td> </td> 
                                    
					
							</tr>
							<tr>
                                    <td>Tusday</td>
                                    <td></td>
									
                                    <td> </td> 
                                    
									 
									
					
							</tr>
							<tr>
                                    <td>Wednesday</td>
                                    <td></td>
									
                                    <td> </td> 
                                    
									 
									
					
							</tr>
							<tr>
                                    <td>Thursday</td>
                                    <td></td>
									
                                    <td> </td> 
                                    
									 
									
					
							</tr>
							<tr>
                                    <td>Friday</td>
                                    <td></td>
									
                                    <td> </td> 
                                    
									 
									
					
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
	