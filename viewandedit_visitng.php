<?php
include('session.php');
include('DB.php');

$message = "";

/* ================= UPDATE VISITING TIME ================= */
if (isset($_POST['update_visiting'])) {

    $id      = intval($_POST['id']);
    $date    = trim($_POST['date']);
    $day     = trim($_POST['day']);
    $morning = trim($_POST['morning']);
    $after   = trim($_POST['after']);

    $stmt = $conn->prepare("
        UPDATE visiting_time
        SET date=?, days=?, morning=?, `after`=?
        WHERE ID=?
    ");
    $stmt->bind_param("ssssi", $date, $day, $morning, $after, $id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Visiting Time updated successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Update failed: {$stmt->error}</div>";
    }

    $stmt->close();
}

/* ================= DELETE ================= */
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {

    $delete_id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("DELETE FROM visiting_time WHERE ID=?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Deleted successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Delete failed.</div>";
    }

    $stmt->close();
}

/* ================= PAGINATION ================= */
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

/* ================= COUNT ================= */
$total_result = $conn->query("SELECT COUNT(*) as total FROM visiting_time");
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);

/* ================= FETCH ================= */
$stmt = $conn->prepare("
    SELECT * FROM visiting_time
    ORDER BY ID DESC
    LIMIT ?, ?
");
$stmt->bind_param("ii", $start, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Visiting Time</title>
<link href="css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f5f6fa;
}
.container-box{
    margin-top:40px;
    background:#fff;
    padding:25px;
    border-radius:8px;
    box-shadow:0 0 10px rgba(0,0,0,0.08);
}
</style>
</head>
<body>

<div class="container container-box">

    <a href="index3.php" class="btn btn-success">
        ← Return to Home Page
    </a>

    <h3 style="margin-top:20px;">Edit Visiting Time</h3>

    <?= $message ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">

            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Morning</th>
                    <th>Afternoon</th>
                    <th width="220">Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <form method="POST">

                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">

                    <td>
                        <input type="date" name="date"
                               value="<?= htmlspecialchars($row['date']) ?>"
                               class="form-control" required>
                    </td>

                    <td>
                        <input type="text" name="day"
                               value="<?= htmlspecialchars($row['days']) ?>"
                               class="form-control" required>
                    </td>

                    <td>
                        <input type="text" name="morning"
                               value="<?= htmlspecialchars($row['morning']) ?>"
                               class="form-control" required>
                    </td>

                    <td>
                        <input type="text" name="after"
                               value="<?= htmlspecialchars($row['after']) ?>"
                               class="form-control" required>
                    </td>

                    <td>
                        <button type="submit"
                                name="update_visiting"
                                class="btn btn-primary btn-sm">
                            Update
                        </button>

                        <a href="?delete_id=<?= $row['ID'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this visiting time?')">
                           Delete
                        </a>
                    </td>

                </form>
            </tr>
            <?php endwhile; ?>

            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <ul class="pagination">
        <?php for($i=1;$i<=$total_pages;$i++): ?>
            <li class="<?= ($page==$i)?'active':'' ?>">
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>

</div>

</body>
</html>