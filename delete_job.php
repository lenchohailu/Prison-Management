<?php
include('session.php');
include('DB.php');

// DELETE JOB (fixed without id column)
if (isset($_GET['delete']) && isset($_GET['title'])) {

    $title = $_GET['title'];
    $postby = $_SESSION['userName'];

    $stmt = $conn->prepare("DELETE FROM job WHERE title = ? AND postby = ?");
    $stmt->bind_param("ss", $title, $postby);

    if ($stmt->execute()) {
        $message = "<span style='color:green;'>Deleted successfully.</span>";
    } else {
        $message = "<span style='color:red;'>Error deleting job.</span>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Job Posts</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container" style="margin-top:80px;">

<?php if(isset($message)) echo $message; ?>

<!-- ✅ RETURN TO HOME BUTTON ADDED -->
<a href="index3.php" class="btn btn-primary" style="margin-bottom:15px;">
    Return to Home Page
</a>

<?php
$stmt = $conn->prepare("SELECT title, post, date FROM job WHERE postby = ? ORDER BY date DESC");
$stmt->bind_param("s", $_SESSION['userName']);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
?>

<div class="panel panel-info" style="padding:10px; margin-bottom:10px;">

    <h4><?php echo htmlspecialchars($row['title']); ?></h4>
    <p><?php echo htmlspecialchars($row['post']); ?></p>

    <small><?php echo $row['date']; ?></small>

    <br><br>

    <a href="delete_job.php?delete=1&title=<?php echo urlencode($row['title']); ?>"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Delete this job?')">
        Delete
    </a>

</div>

<?php } ?>

<?php $stmt->close(); ?>

</div>

</body>
</html>