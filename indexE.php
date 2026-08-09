
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
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<style type="text/css">
body{
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
}
#sss{
background-color:#FF0000;
}
a{
text-decoration:none;
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
}
table { 
border-collapse: collapse;
text-decoration:none;
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
 }
td { padding: .3em; border: 1px #ccc solid; }
#head { background: #fc9; float:left; }
#eee { background: #fff;}
</style>
</head>
<body>
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
                    <li class="active">
                        <a href="index1.php"><i class="fa fa-fw fa-home"></i>Home</a>
                    </li>
					<li class="dropdown">
                        
                        <ul class="dropdown-menu">
						
                           
                        </ul>
                    </li>
					
					 <li>
                        <a href="indexE.php"><i class="fa fa-fw fa-download"></i>Backup</a>
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
	<div class = "row">
		<div class="panel panel-default">
			<div class="panel-body">
			<div class="col-md-12">

<a href="backup.php" id="sss">
<img src="backup.png" alt="backup" />
</a>
<br />
<br />
Backup Archives
<br />
<table
<tr id="head">
<td>
File Name
</td>
<td>
Action
</td>
</tr>
<?php
// List the files
$dir = opendir ("./DB_backup"); 
while (false !== ($file = readdir($dir))) { 

	// Print the filenames that have .sql extension
	if (strpos($file,'.sql',1)) { 

	// Remove the sql extension part in the filename
	$filenameboth = str_replace('.sql', '', $file);
                        
	// Print the cells

		echo "<tr id='eee'>";
		echo '<td>'.$filenameboth.".sql".'</td>';
		echo "<td>"."<a href='DB_backup/" . $filenameboth . ".sql' class='view'>Download SQL</a>"."</td>";
		echo "</tr>";
		
	} 
} 
?>
</table>
</div>
</div>
</div>
</div>
</div>
 <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
	<?php include('footer.php'); ?>
</body>
</html>