<?php
include('session.php');
include('DB.php'); // Must contain a mysqli connection variable: $conn
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prison Management System</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>

<!-- Jobs Display Section -->
<div class="container-fluid" style="margin-top: 20px;">
    <div class="row">

        <!-- Return Home Button -->
        <div class="col-lg-12" style="margin-bottom:20px;">
            <a href="index2.php" class="btn btn-primary">
                <i class="fa fa-home"></i> Return to Home
            </a>
        </div>

        <div class="col-lg-12">

            <?php
            // If URL contains ?users, filter by logged-in user
            if (isset($_GET["users"])) {
                $stmt = $conn->prepare("SELECT * FROM job WHERE postby = ? ORDER BY prison_ID DESC");
                $stmt->bind_param("s", $_SESSION["userName"]);
            } else {
                $stmt = $conn->prepare("SELECT * FROM job ORDER BY prison_ID DESC");
            }

            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
            ?>

                <div class="postTitle">
                    <div class="innerTitle" style="color:#337ab7; font-size:20px; font-weight:bold;">
                        <strong><?= htmlspecialchars($row["title"]); ?></strong>
                    </div>

                    <div class="postBasicInfo" style="font-size:18px;">
                        <strong>
                            <i>Post by <b><font color='red'><?= htmlspecialchars($row["postby"]); ?></font></b>
                            on <?= htmlspecialchars($row["date"]); ?></i>
                        </strong>
                    </div>
                </div>

                <div class="postPost" style="margin-top:10px; margin-bottom:20px; font-size:16px;">
                    <?= nl2br(htmlspecialchars($row["post"])); ?>
                </div>

            <?php
            }
            ?>

        </div>

    </div>
</div>

<!-- Footer -->


<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<script>
$('.carousel').carousel({
    interval: 5000
});
</script>

</body>
</html>