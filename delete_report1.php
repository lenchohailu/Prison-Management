<?php
include('session.php');
include('DB.php'); // mysqli connection

$message = "";

/* ================= DELETE REPORT ================= */
if (isset($_GET['delete']) && isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn, "DELETE FROM post WHERE postby = ? AND id = ?");
    mysqli_stmt_bind_param($stmt, "si", $_SESSION['userName'], $id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "<div class='alert alert-success'>Deleted successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error deleting report.</div>";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Delete Reports</title>

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
.report-box{
    padding:15px;
    border:1px solid #ddd;
    border-radius:6px;
    margin-bottom:15px;
    background:#fafafa;
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

            <h3 style="margin:0;">Delete Reports</h3>
        </div>

        <?= $message ?>

        <?php
        /* ================= FETCH USER REPORTS ================= */
        $stmt = mysqli_prepare($conn, "
            SELECT id, title 
            FROM post 
            WHERE postby = ? 
            ORDER BY id DESC
        ");

        mysqli_stmt_bind_param($stmt, "s", $_SESSION['userName']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0):
            while($row = mysqli_fetch_assoc($result)):
        ?>

            <div class="report-box">

                <h4><?= htmlspecialchars($row['title']) ?></h4>

                <button class="btn btn-danger btn-sm"
                        onclick="confirmDelete(<?= $row['id'] ?>)">
                    Delete
                </button>

            </div>

        <?php
            endwhile;
        else:
        ?>

            <div class="alert alert-info">
                No reports found.
            </div>

        <?php endif; ?>

        <?php mysqli_stmt_close($stmt); ?>

    </div>

</div>

<script>
function confirmDelete(id){
    if(confirm("Are you sure you want to delete this report?")){
        window.location.href = "delete_Report1.php?delete=1&id=" + id;
    }
}
</script>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>

<?php mysqli_close($conn); ?>