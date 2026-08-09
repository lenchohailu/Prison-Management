<?php
include('session.php');
include('DB.php');   // provides $conn
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Prison Management System</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f4f7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container-fluid {
            margin-top: 30px;
        }
        .postTitle {
            border-bottom: 1px solid #ccc;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }
        .innerTitle {
            color: #337ab7; 
            font-size: 22px; 
            font-weight: bold;
        }
        .postBasicInfo {
            font-size: 16px;
            color: #555;
        }
        .postPost {
            font-size: 18px; 
            color: #333;
            padding: 10px 0;
        }
        .return-home {
            margin: 20px 0;
            text-align: center;
        }
        .btn-home {
            background-color: #2e8b57; 
            color: white; 
            padding: 10px 20px; 
            font-size: 16px; 
            border: none; 
            border-radius: 5px;
        }
        .btn-home:hover {
            background-color: #234f38;
        }
    </style>
</head>

<body>

<!-- Navigation (unchanged) -->

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">

            <!-- Return to Home Button -->
            <div class="return-home">
                <a href="index1.php" class="btn btn-home">Return to Home</a>
            </div>

            <?php
            // SAFETY CHECK
            if (!isset($conn)) {
                die("Database connection not available.");
            }

            if (isset($_GET["users"])) {
                $sql = "SELECT * FROM post WHERE postby = ? ORDER BY ID DESC";
                $stmt = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($stmt, "s", $_SESSION["userName"]);
            } else {
                $sql = "SELECT * FROM post ORDER BY ID DESC";
                $stmt = mysqli_prepare($conn, $sql);
            }

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="postTitle">
                    <div class="innerTitle">
                        <strong><?php echo htmlspecialchars($row["title"]); ?></strong>
                    </div>

                    <div class="postBasicInfo">
                        <i>
                            Post by <b style="color:red;"><?php echo htmlspecialchars($row["postby"]); ?></b> on <?php echo $row["date"]; ?>
                        </i>
                    </div>
                </div>

                <div class="postPost">
                    <?php echo nl2br(htmlspecialchars($row["post"])); ?>
                </div>
                <hr>
            <?php
            }
            mysqli_stmt_close($stmt);
            ?>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>