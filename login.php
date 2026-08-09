<?php
session_start();

// Database connection
$connection = mysqli_connect("localhost", "root", "", "prisons");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$message = "";

if (isset($_POST["submit"])) {

    $error = false;

    $userName = strtolower(trim($_POST["userName"]));
    $password = trim($_POST["password"]);
    $userType = trim($_POST["userType"]);

    // Validation
    if (empty($userName)) {
        $message .= "Please enter User Name.<br>";
        $error = true;
    }

    if (empty($password)) {
        $message .= "Please enter Password.<br>";
        $error = true;
    }

    if ($userType == "Select User Type") {
        $message .= "Please select User Type.<br>";
        $error = true;
    }

    if (!$error) {

        // STEP 1: GET USER (NO PASSWORD IN SQL)
        $stmt = $connection->prepare("
            SELECT id, userName, password, userType
            FROM users
            WHERE userName = ? AND userType = ?
        ");

        $stmt->bind_param("ss", $userName, $userType);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $row = $result->fetch_assoc();

            // STEP 2: VERIFY PASSWORD (IMPORTANT FIX)
            if (password_verify($password, $row["password"])) {

                $_SESSION["userName"] = $row["userName"];
                $_SESSION["userType"] = $row["userType"];
                $_SESSION["userID"] = $row["id"];

                // Redirect based on user type
                switch ($row["userType"]) {

                    case "Prisoner":
                        header("Location: index2.php");
                        break;

                    case "Inspector":
                        header("Location: index5.php");
                        break;

                    case "Police Officer":
                        header("Location: index3.php");
                        break;

                    case "Police Commissioner":
                        header("Location: index4.php");
                        break;

                    case "Admin":
                        header("Location: index1.php");
                        break;

                    default:
                        header("Location: index.php");
                        break;
                }

                exit;

            } else {
                $message = "Incorrect Username, Password, or User Type.";
            }

        } else {
            $message = "Incorrect Username, Password, or User Type.";
        }

        $stmt->close();
    }
}

mysqli_close($connection);
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

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Prison Management</a>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="index.php">Home</a></li>
                <li class="active"><a href="visiting_time.php">Visiting Time</a></li>
                <li><a href="About.php">About Us</a></li>
                <li><a href="HelpDesk.php">Help</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container" style="margin-top:80px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3"
             style="background-color:#f9f9f9; padding:30px; border-radius:10px;">

            <h2>Login Form</h2>

            <?php if ($message != "") { ?>
                <div class="alert alert-danger">
                    <?php echo $message; ?>
                </div>
            <?php } ?>

            <form action="login.php" method="POST">

                <div class="form-group">
                    <label>User Name</label>
                    <input type="text" name="userName" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>User Type</label>
                    <select name="userType" class="form-control" required>
                        <option>Select User Type</option>
                        <option>Prisoner</option>
                        <option>Inspector</option>
                        <option>Police Commissioner</option>
                        <option>Police Officer</option>
                        <option>Admin</option>
                    </select>
                </div>

                <button type="submit" name="submit"
                        class="btn btn-primary btn-block">
                    Login
                </button>

            </form>

        </div>
    </div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>