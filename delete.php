<?php
include('session.php');
include('DB.php'); // make sure this file contains your mysqli connection in $conn

$message = '';

if (isset($_POST['submit'])) {
    $prison_ID = trim($_POST['prison_ID']);

    if (!empty($prison_ID) && is_numeric($prison_ID)) {
        // Prepare statement
        $stmt = mysqli_prepare($conn, "DELETE FROM prisoner WHERE prison_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $prison_ID);

        if (mysqli_stmt_execute($stmt)) {
            $message = "<div class='alert alert-success'>Deleted Successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }

        mysqli_stmt_close($stmt);
    } else {
        $message = "<div class='alert alert-warning'>Please enter a valid numeric ID.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Prison Management System - Delete Prisoner</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

</head>
<body>

<div class="container" style="margin-top:30px;">

    <!-- Return Home Button -->
    <a href="index3.php" class="btn btn-primary" style="margin-bottom:20px;">
        <i class="fa fa-home"></i> Return to Home
    </a>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Delete Prisoner Information</h3>
        </div>
        <div class="panel-body">
            <?php if ($message) echo $message; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="prison_ID">Insert Prisoner ID:</label>
                    <input type="text" 
                           class="form-control" 
                           name="prison_ID" 
                           id="prison_ID" 
                           placeholder="Enter Prisoner ID" 
                           required>
                </div>

                <button type="submit" name="submit" class="btn btn-danger">
                    Delete
                </button>
            </form>
        </div>
    </div>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php include('footer.php'); ?>

</body>
</html>

<?php
mysqli_close($conn);
?>