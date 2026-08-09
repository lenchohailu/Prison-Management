<?php
include('session.php');
include('DB.php');

$message = "";

/* ================= DELETE ================= */
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {

    $delete_id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("DELETE FROM schedule WHERE ID = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Schedule deleted successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error deleting schedule: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

/* ================= UPDATE (AJAX) ================= */
if (isset($_POST['action']) && $_POST['action'] == 'update_schedule') {

    $id    = (int)$_POST['id'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $allowed = ['date','days','morning','after'];

    if (in_array($field, $allowed)) {

        $stmt = $conn->prepare("UPDATE schedule SET $field = ? WHERE ID = ?");
        $stmt->bind_param("si", $value, $id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['success'=>true]);
        exit;
    }

    echo json_encode(['success'=>false]);
    exit;
}

/* ================= PAGINATION ================= */
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start_from = ($page - 1) * $limit;

/* ================= COUNT ================= */
$total_result = $conn->query("SELECT COUNT(*) AS total FROM schedule");
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);

/* ================= FETCH ================= */
$stmt = $conn->prepare("SELECT * FROM schedule ORDER BY date DESC LIMIT ?, ?");
$stmt->bind_param("ii", $start_from, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Schedule</title>
<link href="css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }
.box {
    margin-top:50px;
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}
.editable:hover{
    background:#fff8dc;
    cursor:pointer;
}
</style>

<script src="js/jquery.js"></script>
</head>

<body>

<div class="container box">

    <!-- RETURN HOME -->
    <div class="row">
        <div class="col-md-6">
            <a href="index3.php" class="btn btn-success">← Return to Home Page</a>
        </div>
        <div class="col-md-6 text-right">
            <h3>Schedule Management</h3>
        </div>
    </div>

    <?php if($message) echo $message; ?>

    <h4>Total: <?php echo $total_row['total']; ?></h4>

    <table class="table table-bordered table-hover">

        <thead>
        <tr>
            <th>Date</th>
            <th>Day</th>
            <th>Morning</th>
            <th>Afternoon</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>

            <!-- DATE -->
            <td class="editable" data-id="<?= $row['ID'] ?>" data-field="date">
                <?= htmlspecialchars($row['date']) ?>
            </td>

            <!-- DAY -->
            <td class="editable" data-id="<?= $row['ID'] ?>" data-field="days">
                <?= htmlspecialchars($row['days']) ?>
            </td>

            <!-- MORNING -->
            <td class="editable" data-id="<?= $row['ID'] ?>" data-field="morning">
                <?= htmlspecialchars($row['morning']) ?>
            </td>

            <!-- AFTERNOON -->
            <td class="editable" data-id="<?= $row['ID'] ?>" data-field="after">
                <?= htmlspecialchars($row['after']) ?>
            </td>

            <!-- DELETE -->
            <td>
                <button class="btn btn-danger btn-sm"
                        onclick="confirmDelete(<?= $row['ID'] ?>)">
                    Delete
                </button>
            </td>

        </tr>
        <?php endwhile; ?>

        </tbody>
    </table>

    <!-- PAGINATION -->
    <ul class="pagination">
        <?php for($i=1; $i<=$total_pages; $i++): ?>
            <li class="<?= ($i==$page)?'active':'' ?>">
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>

</div>

<script>

/* ================= DELETE ================= */
function confirmDelete(id){
    if(confirm("Delete this schedule?")){
        window.location.href = "?delete_id=" + id;
    }
}

/* ================= INLINE EDIT ================= */
$('.editable').click(function(){

    if($(this).find('input').length) return;

    let cell = $(this);
    let id = cell.data('id');
    let field = cell.data('field');
    let oldValue = cell.text().trim();

    let input = $('<input type="text" class="form-control" />');
    input.val(oldValue);

    cell.html(input);
    input.focus();

    input.blur(function(){

        let newValue = $(this).val();

        $.post('', {
            action: 'update_schedule',
            id: id,
            field: field,
            value: newValue
        }, function(res){

            let r = JSON.parse(res);

            if(r.success){
                cell.html(newValue);
            } else {
                cell.html(oldValue);
                alert("Update failed");
            }
        });

    });

});

</script>

</body>
</html>