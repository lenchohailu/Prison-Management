<?php
include('session.php');
include('DB.php'); // Use mysqli connection

// Delete report if requested
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM post WHERE postby = ? AND id = ?");
    mysqli_stmt_bind_param($stmt, "si", $_SESSION['userName'], $id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "<span style='color:green;'>Deleted successfully.</span>";
    } else {
        $message = "<span style='color:red;'>Error: " . mysqli_error($conn) . "</span>";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reports</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6f9;
}

.container-box {
    margin-top: 60px;
}

.home-btn {
    margin-bottom: 20px;
}
</style>
</head>

<body>

<div class="container container-box">

    <div class="panel panel-default">
        <div class="panel-body">

            <?php if (isset($message)) echo "<p>$message</p>"; ?>

            <!-- ✅ RETURN TO HOME PAGE -->
            <a href="index4.php" class="btn btn-primary home-btn">
                <i class="fa fa-home"></i> Return to Home Page
            </a>

            <?php
            // Fetch user's reports
            $stmt = mysqli_prepare($conn, "SELECT id, title FROM post WHERE postby = ? ORDER BY id DESC");
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['userName']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="panel panel-info" style="padding:10px; margin-bottom:15px;">
                    <div><strong><?php echo htmlspecialchars($row['title']); ?></strong></div>

                    <div style="margin-top:10px;">
                        <a href="javascript:confirmDelete(<?php echo $row['id']; ?>);"
                           class="btn btn-danger btn-sm">
                            Delete
                        </a>
                    </div>
                </div>
            <?php } ?>

            <?php mysqli_stmt_close($stmt); ?>

        </div>
    </div>

</div>

<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this post?")) {
        window.location = "delete_Report3.php?delete=1&id=" + id;
    }
}
</script>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>

<?php mysqli_close($conn); ?>