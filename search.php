<?php
include('DB.php'); // must define $conn = mysqli_connect(...)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Prison Management System</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>

<!-- NAVIGATION BAR -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" data-toggle="collapse" data-target="#nav">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="index1.php"><i class="fa fa-home"></i> Home</a></li>
               </li>
                <li><a href="search.php"><i class="fa fa-search"></i> Search Prisoner</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="login.php"><i class="fa fa-user"></i> Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<br><br><br>

<div class="container">

    <h3>Search Prisoner</h3>
    <hr>

    <!-- SEARCH FORM -->
    <form action="search.php" method="post" class="form-inline">
        <div class="form-group">
            <label>Prisoner ID:</label>
            <input type="text" name="prison_ID" class="form-control" required>
        </div>

        <button type="submit" name="display" class="btn btn-primary">Search</button>
    </form>

    <hr>

<?php
if (isset($_POST['display'])) {

    $id = trim($_POST['prison_ID']);

    if ($id == "") {
        echo "<div class='alert alert-danger'>Please enter a prisoner ID.</div>";
    } else {

        $stmt = mysqli_prepare($conn, "SELECT * FROM prisoner WHERE prison_ID = ?");
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            echo "<div class='alert alert-warning'>No prisoner found with ID <b>$id</b>.</div>";
        } else {
            $row = mysqli_fetch_assoc($result);

            echo '<h4>Prisoner Information</h4>';
            echo '<table class="table table-bordered table-striped">';
            foreach ($row as $key => $value) {
                echo "<tr><th>" . htmlspecialchars($key) . "</th><td>" . htmlspecialchars($value) . "</td></tr>";
            }
            echo '</table>';
        }
    }
}
?>

    <hr>

    <footer>
        <div class="row">
            <div class="col-lg-12 text-right">
                <p>© Woliso Prison</p>
            </div>
        </div>
    </footer>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
