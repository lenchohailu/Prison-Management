<?php
include('session.php');
include('DB.php'); // Must contain MySQLi connection: $conn
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Job Post</title>

<link href="css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
}
.container-box{
    margin-top:40px;
    background:#fff;
    padding:25px;
    border-radius:8px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}
.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}
</style>

</head>
<body>

<div class="container">

    <div class="container-box">

        <!-- RETURN TO HOME -->
        <div class="top-bar">
            <a href="index3.php" class="btn btn-success">
                ← Return to Home Page
            </a>

            <h3 style="margin:0;">Job Announcement</h3>
        </div>

        <?php
        if (isset($_POST["postB"])) {

            $title = trim($_POST["title"]);
            $post = trim($_POST["post"]);
            $postedBy = $_SESSION["userName"];

            if ($title == "" || $post == "") {

                echo "<div class='alert alert-danger'>Please fill in all fields.</div>";

            } else {

                $stmt = $conn->prepare("
                    INSERT INTO job (title, post, postby, date)
                    VALUES (?, ?, ?, NOW())
                ");

                $stmt->bind_param("sss", $title, $post, $postedBy);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Successfully Submitted.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                }

                $stmt->close();
            }
        }
        ?>

        <form method="post" action="" class="form-horizontal">

            <div class="form-group">
                <label class="col-md-2 control-label">Title</label>
                <div class="col-md-6">
                    <input type="text" 
                           name="title" 
                           class="form-control" 
                           placeholder="Enter Title">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">Job</label>
                <div class="col-md-6">
                    <textarea name="post" 
                              rows="6" 
                              class="form-control" 
                              placeholder="Enter Job Description"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2"></label>
                <div class="col-md-6">
                    <button type="submit" 
                            name="postB" 
                            class="btn btn-primary">
                        Submit
                    </button>

                    <a href="index3.php" class="btn btn-default">
                        Cancel
                    </a>
                </div>
            </div>

        </form>

    </div>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>