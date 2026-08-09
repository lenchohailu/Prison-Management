<?php
include('session.php');
include('DB.php'); // must contain $conn = new mysqli(...)
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Report Page</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f6f9;
        }
        .page-box{
            margin-top:50px;
            background:#fff;
            padding:25px;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
        }
    </style>
</head>

<body>

<div class="container">

    <div class="page-box">

        <!-- RETURN TO HOME -->
        <a href="index3.php" class="btn btn-success">
            ← Return to Home Page
        </a>

        <h2 class="text-center" style="margin-top:20px;">
            Submit Report
        </h2>

        <?php
        /* ================= HANDLE SUBMISSION ================= */
        if (isset($_POST["postB"])) {

            $title = trim($_POST["title"]);
            $post  = trim($_POST["post"]);

            $errors = [];

            if ($title == "") {
                $errors[] = "Please enter a title.";
            }

            if ($post == "") {
                $errors[] = "Please enter your report.";
            }

            if (empty($errors)) {

                $stmt = $conn->prepare("
                    INSERT INTO post (title, post, postby, date)
                    VALUES (?, ?, ?, NOW())
                ");

                $stmt->bind_param(
                    "sss",
                    $title,
                    $post,
                    $_SESSION["userName"]
                );

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Successfully Submitted.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save report.</div>";
                }

                $stmt->close();

            } else {

                echo "<div class='alert alert-danger'>";
                foreach($errors as $error){
                    echo $error . "<br>";
                }
                echo "</div>";
            }
        }
        ?>

        <form method="post" action="">

            <div class="form-group">
                <label>Title</label>
                <input type="text"
                       name="title"
                       class="form-control"
                       placeholder="Enter Report Title">
            </div>

            <div class="form-group">
                <label>Report</label>
                <textarea name="post"
                          rows="8"
                          class="form-control"
                          placeholder="Write report here..."></textarea>
            </div>

            <button type="submit" name="postB" class="btn btn-primary">
                Submit Report
            </button>

            <a href="index3.php" class="btn btn-default">
                Cancel
            </a>

        </form>

    </div>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>