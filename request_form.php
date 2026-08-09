<?php
include('session.php');

/* -----------------------------
   DATABASE CONNECTION
------------------------------ */
$conn = new mysqli("localhost", "root", "", "prisons");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

/* -----------------------------
   CHECK SESSION ID
------------------------------ */
if (!isset($_SESSION["ID"])) {
    die("<div style='padding:20px; color:red; font-weight:bold;'>
            Session ID not found. Please login first.
         </div>");
}

$ID = $_SESSION["ID"];

/* -----------------------------
   HANDLE FORM SUBMISSION
------------------------------ */
$message = "";

if (isset($_POST["submit"])) {

    $place  = trim($_POST["place"]);
    $reason = trim($_POST["reason"]);

    if ($place == "" || $reason == "") {

        $message = "<div class='alert alert-danger'>
                        Please fill all fields.
                    </div>";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO request (ID, place, reason, status, date)
            VALUES (?, ?, ?, 'Not Approved', NOW())
        ");

        $stmt->bind_param("iss", $ID, $place, $reason);

        if ($stmt->execute()) {

            $message = "<div class='alert alert-success'>
                            Request sent successfully!
                        </div>";

        } else {

            $message = "<div class='alert alert-danger'>
                            Error: " . htmlspecialchars($stmt->error) . "
                        </div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Request Form</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
}
.request-box{
    max-width:800px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,0.1);
}
</style>

</head>
<body>

<div class="container" style="margin-top:30px;">

    <a href="index2.php" class="btn btn-primary" style="margin-bottom:20px;">
        <i class="fa fa-home"></i> Return to Home
    </a>

    <div class="request-box">

        <h2 class="text-center">Transfer Request Form</h2>
        <hr>

        <?= $message ?>

        <form method="POST" action="">

            <div class="form-group">
                <label>Place</label>
                <input type="text" name="place" class="form-control" placeholder="Enter Place" required>
            </div>

            <div class="form-group">
                <label>Explanation / Reason</label>
                <textarea name="reason" rows="6" class="form-control" placeholder="Type your reason here..." required></textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-success">
                <i class="fa fa-send"></i> Submit Request
            </button>

            <a href="index2.php" class="btn btn-default">
                Cancel
            </a>

        </form>

    </div>
</div>

<?php include('footer.php'); ?>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>