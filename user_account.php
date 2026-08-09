<?php
include('session.php');
include('DB.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Prison Management System - User Registration</title>

<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>

body{
	background:#f5f5f5;
}

.panel-body{
	background:white;
}

.page-title{
	font-weight:bold;
	margin-bottom:20px;
}

.help-text{
	font-size:12px;
	color:gray;
	margin-top:5px;
}

.password-rules{
	background:#f9f9f9;
	padding:10px;
	border-radius:5px;
	border-left:4px solid #337ab7;
	margin-top:10px;
}

</style>

<script>

// ================= FORM VALIDATION =================
function formValidation(){

	let UserName = document.getElementById('userName');
	let Password = document.getElementById('Password');
	let UserType = document.getElementById('userType');

	// USERNAME VALIDATION
	if(!isLowercase(UserName,
		"Username must contain lowercase letters only (a-z).")){
		return false;
	}

	// USERNAME LENGTH
	if(!lengthRestriction(UserName, 3, 30)){
		return false;
	}

	// PASSWORD VALIDATION
	if(!strongPassword(Password)){
		return false;
	}

	// USER TYPE
	if(!madeSelection(UserType,
		"Please choose a user type.")){
		return false;
	}

	return true;
}


// ================= LOWERCASE VALIDATION =================
function isLowercase(elem, msg){

	var exp = /^[a-z]+$/;

	if(elem.value.match(exp)){
		return true;
	}

	alert(msg);

	elem.focus();

	return false;
}


// ================= PASSWORD VALIDATION =================
function strongPassword(elem){

	var password = elem.value;

	// MIN LENGTH
	if(password.length < 8){

		alert("Password must be at least 8 characters long.");

		elem.focus();

		return false;
	}

	// UPPERCASE
	if(!/[A-Z]/.test(password)){

		alert("Password must contain at least one uppercase letter.");

		elem.focus();

		return false;
	}

	// LOWERCASE
	if(!/[a-z]/.test(password)){

		alert("Password must contain at least one lowercase letter.");

		elem.focus();

		return false;
	}

	// NUMBER
	if(!/[0-9]/.test(password)){

		alert("Password must contain at least one number.");

		elem.focus();

		return false;
	}

	// SPECIAL CHARACTER
	if(!/[!@#$%^&*(),.?\":{}|<>]/.test(password)){

		alert("Password must contain at least one special character.");

		elem.focus();

		return false;
	}

	return true;
}


// ================= LENGTH RESTRICTION =================
function lengthRestriction(elem, min, max){

	var l = elem.value.length;

	if(l >= min && l <= max){
		return true;
	}

	alert("Input must be between " + min + " and " + max + " characters.");

	elem.focus();

	return false;
}


// ================= SELECT VALIDATION =================
function madeSelection(elem, msg){

	if(elem.value === "Select User Type"){

		alert(msg);

		elem.focus();

		return false;
	}

	return true;
}

</script>

</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-inverse navbar-fixed-top">

<div class="container">

<div class="navbar-header">

<button type="button"
		class="navbar-toggle"
		data-toggle="collapse"
		data-target="#menu">

	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>

</button>

<a class="navbar-brand" href="index1.php">
	<i class="fa fa-shield"></i> Prison System
</a>

</div>

<div class="collapse navbar-collapse" id="menu">

<ul class="nav navbar-nav navbar-left">

<li>
	<a href="index1.php">
		<i class="fa fa-home"></i> Home
	</a>
</li>

</ul>

<ul class="nav navbar-nav navbar-right">

<li>
	<a href="logout.php" style="color:white;">
		Logout
	</a>
</li>

</ul>

</div>

</div>

</nav>


<!-- ================= CONTENT ================= -->
<div class="container" style="margin-top:90px;">

<div class="row">

<div class="col-md-10 col-md-offset-1">

<div class="panel panel-default">

<div class="panel-heading">

<h3 class="page-title">
	<i class="fa fa-user-plus"></i> User Account Registration
</h3>

</div>

<div class="panel-body">

<?php

// ================= HANDLE FORM =================
if(isset($_POST["submit"])){

	// GET DATA
	$UN = trim($_POST["userName"]);
	$UT = trim($_POST["userType"]);
	$PS = trim($_POST["Password"]);

	// CONVERT USERNAME TO LOWERCASE
	$UN = strtolower($UN);

	// CHECK EMPTY
	if($UN == '' || $UT == 'Select User Type' || $PS == ''){

		echo "
		<div class='alert alert-danger'>
			All fields are required.
		</div>
		";

	}else{

		// CHECK IF USERNAME EXISTS
		$check = $conn->prepare("
			SELECT id FROM users WHERE userName = ?
		");

		$check->bind_param("s", $UN);

		$check->execute();

		$result = $check->get_result();

		if($result->num_rows > 0){

			echo "
			<div class='alert alert-warning'>
				Username already exists. Please choose another username.
			</div>
			";

		}else{

			// HASH PASSWORD
			$hashedPassword = password_hash($PS, PASSWORD_DEFAULT);

			// INSERT USER
			$stmt = $conn->prepare("
				INSERT INTO users(userName, password, userType)
				VALUES (?, ?, ?)
			");

			$stmt->bind_param("sss",
				$UN,
				$hashedPassword,
				$UT
			);

			if($stmt->execute()){

				echo "
				<div class='alert alert-success'>

					<strong>Success!</strong>

					User account created successfully.

					<br><br>

					<a href='user_account.php'
					   class='btn btn-success'>

						Add Another User

					</a>

				</div>
				";

			}else{

				echo "
				<div class='alert alert-danger'>

					<strong>Error!</strong>

					Failed to create user account.

					<br><br>

					".$conn->error."

				</div>
				";
			}
		}
	}
}

?>


<!-- ================= FORM ================= -->
<form method="post"
	  action="user_account.php"
	  onsubmit="return formValidation();"
	  class="form-horizontal">

<!-- USERNAME -->
<div class="form-group">

<label class="col-md-2 control-label">
	User Name
</label>

<div class="col-md-4">

<input
	id="userName"
	name="userName"
	type="text"
	class="form-control"
	placeholder="lowercase username"
	oninput="this.value = this.value.toLowerCase();"
	required
>

<div class="help-text">
	Only lowercase letters allowed (a-z)
</div>

</div>

</div>


<!-- PASSWORD -->
<div class="form-group">

<label class="col-md-2 control-label">
	Password
</label>

<div class="col-md-4">

<input
	id="Password"
	name="Password"
	type="password"
	class="form-control"
	placeholder="Example: Abcd@123"
	required
>

<div class="password-rules">

<strong>Password Rules:</strong>

<ul style="margin-top:10px;">

	<li>Minimum 8 characters</li>

	<li>At least one uppercase letter</li>

	<li>At least one lowercase letter</li>

	<li>At least one number</li>

	<li>At least one special character</li>

</ul>

</div>

</div>


<!-- USER TYPE -->
<label class="col-md-2 control-label">
	User Type
</label>

<div class="col-md-3">

<select
	id="userType"
	name="userType"
	class="form-control"
	required
>

<option>Select User Type</option>

<option>Prisoner</option>

<option>Inspector</option>

<option>Police Commissioner</option>

<option>Police Officer</option>

<option>Admin</option>

</select>

</div>

</div>


<!-- BUTTONS -->
<div class="form-group">

<label class="col-md-2"></label>

<div class="col-md-8">

<button name="submit" class="btn btn-primary">

	<i class="fa fa-save"></i> Create User

</button>

<a href="index1.php" class="btn btn-default">

	Cancel

</a>

</div>

</div>

</form>

</div>
</div>
</div>
</div>
</div>


<!-- ================= SCRIPTS ================= -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>