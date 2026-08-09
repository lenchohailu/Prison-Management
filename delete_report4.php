<?php
include('session.php');
include('DB.php'); // Use mysqli connection

// Handle deletion
$message = '';

if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn, "DELETE FROM post WHERE postBy = ? AND id = ?");
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
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Delete Reports</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>

<div class="container" style="margin-top:30px;">

    <!-- Return Home Button -->
    <div style="margin-bottom:20px;">
        <a href="index5.php" class="btn btn-primary">
            <i class="fa fa-home"></i> Return to Home Page
        </a>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Delete Reports</h3>
        </div>

        <div class="panel-body">

            <?php if($message) echo "<p>$message</p>"; ?>

            <?php
            $stmt = mysqli_prepare(
                $conn,
                "SELECT id, title FROM post WHERE postBy = ? ORDER BY id DESC"
            );

            mysqli_stmt_bind_param($stmt, "s", $_SESSION['userName']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="panel panel-info" style="padding:10px; margin-bottom:15px;">

                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>

                    <div style="margin-top:10px;">
                        <a href="javascript:confirmDelete(<?php echo $row['id']; ?>);"
                           class="btn btn-danger btn-sm">
                            Delete
                        </a>
                    </div>

                </div>
            <?php
            }

            mysqli_stmt_close($stmt);
            ?>

        </div>
    </div>

</div>

<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this report?")) {
        window.location = "delete_report4.php?delete=1&id=" + id;
    }
}
</script>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>

<?php mysqli_close($conn); ?>