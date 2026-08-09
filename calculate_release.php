<?php
include('session.php');
include('DB.php');

$message = "";
$resultBox = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $prison_ID = intval($_POST['prison_ID']);
    $years  = intval($_POST['years']);
    $months = intval($_POST['months']);
    $days   = intval($_POST['days']);

    if ($prison_ID > 0 && ($years > 0 || $months > 0 || $days > 0)) {

        $query = "SELECT * FROM prisoner WHERE prison_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $prison_ID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            $prisonDate = $row['Prison_Date'];

            // ✅ Accurate calculation
            $date = new DateTime($prisonDate);
            $date->modify("+$years years +$months months +$days days");
            $releaseDate = $date->format('Y-m-d');

            // Update database
            $update = "UPDATE prisoner SET end_date = ? WHERE prison_ID = ?";
            $stmt2 = $conn->prepare($update);
            $stmt2->bind_param("si", $releaseDate, $prison_ID);
            $stmt2->execute();

            $resultBox = "
            <div class='alert alert-success'>
                <h4>Release Date Calculated Successfully</h4>
                <p><strong>Prisoner Name:</strong> {$row['prison_fname']} {$row['prison_mname']} {$row['prison_lname']}</p>
                <p><strong>Prison Date:</strong> {$prisonDate}</p>
                <p><strong>Sentence:</strong> {$years} Year(s), {$months} Month(s), {$days} Day(s)</p>
                <p><strong>Release Date:</strong> {$releaseDate}</p>
            </div>
            ";

        } else {
            $message = "<div class='alert alert-danger'>Prisoner Not Found.</div>";
        }

    } else {
        $message = "<div class='alert alert-danger'>Please enter valid input.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Calculate Release Date</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>

<body style="background:#f4f6f9;">

<div class="container" style="margin-top:50px; max-width:700px;">

    <div class="panel panel-primary">

        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="fa fa-calendar"></i>
                Calculate Prisoner Release Date
            </h3>
        </div>

        <div class="panel-body">

            <?php echo $message; ?>
            <?php echo $resultBox; ?>

            <form method="POST">

                <!-- Prisoner ID -->
                <div class="form-group">
                    <label>Prisoner ID</label>
                    <input type="number" name="prison_ID" class="form-control" required>
                </div>

                <!-- Sentence Duration -->
                <div class="form-group">
                    <label>Sentence Duration</label>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="number" name="years" class="form-control" placeholder="Years" min="0">
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="months" class="form-control" placeholder="Months" min="0">
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="days" class="form-control" placeholder="Days" min="0">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <button type="submit" class="btn btn-success">
                    Calculate Release Date
                </button>

                <a href="index5.php" class="btn btn-primary">
                    Back
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>