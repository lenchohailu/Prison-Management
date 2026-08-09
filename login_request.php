<?php
session_start();
include('DB.php'); // must create $conn

$message = "";

if (isset($_POST['login'])) {

    $id = trim($_POST['ID']);

    if ($id == "" || !is_numeric($id)) {
        $message = "<div class='alert alert-danger'>Please enter a valid ID.</div>";
    } else {

        // Check if prisoner/user exists
        $stmt = $conn->prepare("SELECT prison_ID, prison_fname, prison_lname FROM prisoner WHERE prison_ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $user = $result->fetch_assoc();

            // Store session ID for request_form.php
            $_SESSION["ID"] = $user["prison_ID"];
            $_SESSION["userName"] = $user["prison_fname"] . " " . $user["prison_lname"];

            header("Location: request_form.php");
            exit();

        } else {
            $message = "<div class='alert alert-danger'>ID not found.</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f6f9;
        }
        .login-box{
            max-width:450px;
            margin:80px auto;
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container">

    <div class="login-box">

        <h2 class="text-center">Login With Prisoner ID</h2>
        <hr>

        <?= $message ?>

        <form method="POST">

            <div class="form-group">
                <label>Enter Your Prisoner ID</label>
                <input type="number" name="ID" class="form-control" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary btn-block">
                Continue to Request Form
            </button>

        </form>

    </div>

</div>

</body>
</html>