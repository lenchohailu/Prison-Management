<?php
include('session.php');
include('DB.php'); // must define $conn (mysqli)

/* ================= DELETE PRISONER ================= */
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("DELETE FROM prisoner WHERE prison_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* ================= TOTAL PRISONERS ================= */
$countQuery = $conn->query("SELECT COUNT(*) AS total FROM prisoner");
$countData  = $countQuery->fetch_assoc();
$total_prisoners = $countData['total'];

/* ================= GET ATTENDANCE RECORDS ================= */
// Fetch all attendance records with prisoner details
$attendance_stmt = $conn->prepare("
    SELECT a.*, p.criminal_severity, p.prison_lname 
    FROM attendance a
    LEFT JOIN prisoner p ON a.prison_ID = p.prison_ID
    ORDER BY a.Date DESC
");
$attendance_stmt->execute();
$attendance_records = $attendance_stmt->get_result();

/* ================= PAGINATION ================= */
$limit = 10;
$page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start = ($page - 1) * $limit;

$stmt = $conn->prepare("
    SELECT * FROM prisoner
    ORDER BY Prison_Date DESC
    LIMIT ?, ?
");
$stmt->bind_param("ii", $start, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Prison Management System - Attendance Records</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
body {
    background: #f5f6f7;
}

.top-bar {
    margin-top: 20px;
    margin-bottom: 20px;
}

.home-btn {
    background: #1e4b43;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
}

.home-btn:hover {
    background: #0b2b26;
    color: #fff;
}

.panel-heading {
    font-weight: bold;
    font-size: 16px;
}

/* Attendance badge styling */
.attendance-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.badge-present {
    background-color: #5cb85c;
    color: white;
}

.badge-absent {
    background-color: #d9534f;
    color: white;
}

.badge-permission {
    background-color: #f0ad4e;
    color: white;
}

/* Severity badge */
.severity-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: bold;
}

.severity-high {
    background-color: #d9534f;
    color: white;
}

.severity-medium {
    background-color: #f0ad4e;
    color: white;
}

.severity-low {
    background-color: #5cb85c;
    color: white;
}

/* Stats cards */
.stat-card {
    text-align: center;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 15px;
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-present {
    background: linear-gradient(135deg, #5cb85c 0%, #4cae4c 100%);
    color: white;
}

.stat-absent {
    background: linear-gradient(135deg, #d9534f 0%, #c9302c 100%);
    color: white;
}

.stat-permission {
    background: linear-gradient(135deg, #f0ad4e 0%, #eea236 100%);
    color: white;
}

.stat-total {
    background: linear-gradient(135deg, #337ab7 0%, #286090 100%);
    color: white;
}

.stat-number {
    font-size: 36px;
    font-weight: bold;
}

.stat-label {
    font-size: 14px;
    margin-top: 8px;
}

.table-attendance {
    font-size: 13px;
}

.table-attendance th {
    background-color: #f8f9fa;
    text-align: center;
    vertical-align: middle;
}

.table-attendance td {
    vertical-align: middle;
}

.filter-box {
    margin-bottom: 20px;
    padding: 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.export-btn {
    margin-top: 10px;
}
</style>
</head>
<body>

<div class="container">

    <!-- ================= RETURN HOME BUTTON ================= -->
    <div class="top-bar clearfix">
        <div class="pull-left">
            <a href="index3.php" class="home-btn">
                <i class="fa fa-home"></i> Return to Home
            </a>
        </div>
        <div class="pull-right">
            <span class="label label-info" style="padding: 8px 12px;">
                <i class="fa fa-calendar"></i> <?php echo date('l, F j, Y'); ?>
            </span>
        </div>
    </div>

    <!-- ================= ATTENDANCE STATISTICS ================= -->
    <?php
    // Calculate statistics
    $present_total = 0;
    $absent_total = 0;
    $permission_total = 0;
    
    $stats_stmt = $conn->query("
        SELECT 
            SUM(CASE WHEN Attendance = 'Present' THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN Attendance = 'Absent' THEN 1 ELSE 0 END) as absent,
            SUM(CASE WHEN Attendance = 'Permission' THEN 1 ELSE 0 END) as permission,
            COUNT(*) as total
        FROM attendance
    ");
    $stats = $stats_stmt->fetch_assoc();
    $present_total = $stats['present'];
    $absent_total = $stats['absent'];
    $permission_total = $stats['permission'];
    $total_records = $stats['total'];
    ?>

    <div class="row">
        <div class="col-md-3">
            <div class="stat-card stat-present">
                <div class="stat-number"><?php echo $present_total; ?></div>
                <div class="stat-label">
                    <i class="fa fa-check-circle"></i> Present
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-absent">
                <div class="stat-number"><?php echo $absent_total; ?></div>
                <div class="stat-label">
                    <i class="fa fa-times-circle"></i> Absent
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-permission">
                <div class="stat-number"><?php echo $permission_total; ?></div>
                <div class="stat-label">
                    <i class="fa fa-clock-o"></i> Permission
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-total">
                <div class="stat-number"><?php echo $total_records; ?></div>
                <div class="stat-label">
                    <i class="fa fa-database"></i> Total Records
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TOTAL PRISONERS ================= -->
    <div class="clearfix" style="margin: 20px 0 15px 0;">
        <div class="pull-left">
            <h4>
                <i class="fa fa-users"></i> 
                Total Prisoners: <?php echo $total_prisoners; ?>
            </h4>
        </div>
        <div class="pull-right">
            <button onclick="window.print();" class="btn btn-default btn-sm">
                <i class="fa fa-print"></i> Print Report
            </button>
        </div>
    </div>

    <!-- ================= ATTENDANCE RECORDS TABLE ================= -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-list-alt"></i> Attendance Records
            <span class="pull-right">
                <i class="fa fa-clock-o"></i> All Records
            </span>
        </div>
        <div class="panel-body table-responsive">
            
            <?php if($attendance_records->num_rows > 0): ?>
            <table class="table table-bordered table-hover table-striped table-attendance">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date & Time</th>
                        <th>Prisoner ID</th>
                        <th>Full Name</th>
                        <th>Attendance Status</th>
                        <th>Criminal Severity</th>
                        <th>Recorded By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1;
                    while ($record = $attendance_records->fetch_assoc()): 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $counter++; ?></td>
                        <td><?php echo date('M d, Y h:i A', strtotime($record['Date'])); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($record['prison_ID']); ?></td>
                        <td>
                            <?php 
                            echo htmlspecialchars($record['prison_fname'] . ' ' . 
                                                $record['prison_mname'] . ' ' . 
                                                $record['prison_lname']);
                            ?>
                        </td>
                        <td class="text-center">
                            <?php 
                            $badge_class = '';
                            $icon = '';
                            if($record['Attendance'] == 'Present') {
                                $badge_class = 'badge-present';
                                $icon = 'fa-check-circle';
                            } elseif($record['Attendance'] == 'Absent') {
                                $badge_class = 'badge-absent';
                                $icon = 'fa-times-circle';
                            } else {
                                $badge_class = 'badge-permission';
                                $icon = 'fa-clock-o';
                            }
                            ?>
                            <span class="attendance-badge <?php echo $badge_class; ?>">
                                <i class="fa <?php echo $icon; ?>"></i> <?php echo $record['Attendance']; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if(isset($record['criminal_severity']) && $record['criminal_severity']): ?>
                                <?php 
                                $sev_class = '';
                                if($record['criminal_severity'] == 'High') $sev_class = 'severity-high';
                                elseif($record['criminal_severity'] == 'Medium') $sev_class = 'severity-medium';
                                elseif($record['criminal_severity'] == 'Low') $sev_class = 'severity-low';
                                else $sev_class = '';
                                ?>
                                <span class="severity-badge <?php echo $sev_class; ?>">
                                    <?php echo htmlspecialchars($record['criminal_severity']); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="label label-default">
                                <i class="fa fa-user"></i> Staff
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <!-- Export options -->
            <div class="export-btn">
                <a href="export_attendance.php" class="btn btn-success btn-sm">
                    <i class="fa fa-file-excel-o"></i> Export to Excel
                </a>
                <button onclick="window.print();" class="btn btn-info btn-sm">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
            
            <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fa fa-info-circle"></i> 
                No attendance records found. Please take attendance from the Take Attendance page.
            </div>
            <?php endif; ?>
            
        </div>
    </div>
    
    <!-- Attendance Summary by Severity -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-bar-chart"></i> Attendance Summary by Criminal Severity
        </div>
        <div class="panel-body">
            <?php
            $severity_summary = $conn->query("
                SELECT 
                    p.criminal_severity,
                    COUNT(CASE WHEN a.Attendance = 'Present' THEN 1 END) as present,
                    COUNT(CASE WHEN a.Attendance = 'Absent' THEN 1 END) as absent,
                    COUNT(CASE WHEN a.Attendance = 'Permission' THEN 1 END) as permission,
                    COUNT(*) as total
                FROM attendance a
                LEFT JOIN prisoner p ON a.prison_ID = p.prison_ID
                WHERE p.criminal_severity IS NOT NULL
                GROUP BY p.criminal_severity
            ");
            
            if($severity_summary->num_rows > 0):
            ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th>Severity Level</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Permission</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($summary = $severity_summary->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php 
                                $sev_class = '';
                                if($summary['criminal_severity'] == 'High') $sev_class = 'severity-high';
                                elseif($summary['criminal_severity'] == 'Medium') $sev_class = 'severity-medium';
                                else $sev_class = 'severity-low';
                                ?>
                                <span class="severity-badge <?php echo $sev_class; ?>">
                                    <?php echo $summary['criminal_severity']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="label label-success"><?php echo $summary['present']; ?></span>
                            </td>
                            <td class="text-center">
                                <span class="label label-danger"><?php echo $summary['absent']; ?></span>
                            </td>
                            <td class="text-center">
                                <span class="label label-warning"><?php echo $summary['permission']; ?></span>
                            </td>
                            <td class="text-center">
                                <strong><?php echo $summary['total']; ?></strong>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted text-center">No severity data available for analysis.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
// Print styling
@media print {
    .top-bar, .stat-card, .export-btn, .panel-heading .pull-right, .btn {
        display: none !important;
    }
    .panel {
        border: none !important;
    }
    .table {
        font-size: 12px !important;
    }
}
</script>

</body>
</html>