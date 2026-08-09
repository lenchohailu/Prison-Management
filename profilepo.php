<?php
include('DB.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Prison Management System - Prisoner List</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>

body{
    background:#f5f5f5;
}

/* Criminal Record Styles */
.criminal-record-cell {
    max-width: 250px;
    word-wrap: break-word;
    white-space: normal;
    font-size: 12px;
}

.criminal-record-preview {
    cursor: pointer;
    color: #337ab7;
    text-decoration: underline;
}

.criminal-record-preview:hover {
    color: #23527c;
}

/* Severity badges - Updated to match add_prison.php */
.severity-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 5px;
}

.severity-high {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.severity-medium {
    background: #fed7aa;
    color: #9a3412;
    border: 1px solid #fdba74;
}

.severity-low {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

/* Modal */
.modal-content {
    border-radius: 8px;
}

.modal-header {
    background: linear-gradient(135deg, #0f1722 0%, #1e2a3a 100%);
    color: white;
    border-bottom: none;
}

.modal-header .close {
    color: white;
    opacity: 0.8;
}

.modal-header .close:hover {
    color: white;
    opacity: 1;
}

.modal-title {
    font-weight: 600;
}

.modal-title small {
    color: #aac3e0;
}

.criminal-record-full {
    max-height: 400px;
    overflow-y: auto;
    padding: 20px;
    background-color: #f8fafc;
    border-radius: 12px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.6;
    border-left: 4px solid #3b71ca;
}

/* Prisoner photo */
.prisoner-photo:hover {
    transform: scale(1.08);
    transition: 0.3s;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
}

/* Stat Cards */
.stats-row {
    margin: 20px 0 30px 0;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 32px;
    font-weight: bold;
    color: #1e293b;
}

.stat-label {
    font-size: 13px;
    color: #64748b;
    margin-top: 5px;
}

.stat-icon {
    font-size: 28px;
    margin-bottom: 10px;
}

/* Table improvements */
.table > thead > tr > th {
    background: #1e293b;
    color: white;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
}

.table > tbody > tr > td {
    vertical-align: middle;
    font-size: 13px;
}

/* Filter section */
.filter-section {
    background: white;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Export button */
.export-btn {
    margin-left: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 11px;
    }
    .stat-number {
        font-size: 24px;
    }
}
</style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index3.php">
                <i class="fa fa-shield"></i> Prison System
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="add_prison.php" style="color:white;">
                        <i class="fa fa-plus-circle"></i> Add Prisoner
                    </a>
                </li>
                <li>
                    <a href="logout.php" style="color:white;">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>

<div class="container" style="margin-top:80px;">

<!-- ================= RETURN BUTTON ================= -->
<div class="row">
    <div class="col-md-12">
        <a href="index3.php" class="btn btn-primary" style="margin-bottom:15px;">
            <i class="fa fa-arrow-left"></i> Return to Home Page
        </a>
    </div>
</div>

<?php
// Pagination
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$limit = 10;
$start = ($page - 1) * $limit;

// Get filter values
$severity_filter = isset($_GET['severity']) ? $_GET['severity'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build WHERE clause for filters
$where_clauses = [];
$params = [];
$types = "";

if($severity_filter && $severity_filter != 'all') {
    $where_clauses[] = "criminal_severity = ?";
    $params[] = $severity_filter;
    $types .= "s";
}

if($status_filter && $status_filter != 'all') {
    $where_clauses[] = "prison_stat = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$where_sql = "";
if(count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

// Count total prisoners with filters
$countQuery = $conn->prepare("SELECT COUNT(*) AS total FROM prisoner $where_sql");
if(count($params) > 0) {
    $countQuery->bind_param($types, ...$params);
}
$countQuery->execute();
$total_result = $countQuery->get_result();
$total = $total_result->fetch_assoc()["total"];

// Get statistics by severity
$severity_stats = $conn->query("
    SELECT 
        criminal_severity,
        COUNT(*) as count
    FROM prisoner
    WHERE criminal_severity IS NOT NULL AND criminal_severity != ''
    GROUP BY criminal_severity
");

$severity_counts = ['Low' => 0, 'Medium' => 0, 'High' => 0];
while($stat = $severity_stats->fetch_assoc()) {
    if(isset($severity_counts[$stat['criminal_severity']])) {
        $severity_counts[$stat['criminal_severity']] = $stat['count'];
    }
}
?>

<!-- Statistics Cards -->
<div class="stats-row">
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa fa-users" style="color: #3b71ca;"></i>
                </div>
                <div class="stat-number"><?php echo $total; ?></div>
                <div class="stat-label">Total Prisoners</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa fa-exclamation-triangle" style="color: #dc2626;"></i>
                </div>
                <div class="stat-number"><?php echo $severity_counts['High']; ?></div>
                <div class="stat-label">High Severity</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa fa-balance-scale" style="color: #f59e0b;"></i>
                </div>
                <div class="stat-number"><?php echo $severity_counts['Medium']; ?></div>
                <div class="stat-label">Medium Severity</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa fa-leaf" style="color: #10b981;"></i>
                </div>
                <div class="stat-number"><?php echo $severity_counts['Low']; ?></div>
                <div class="stat-label">Low Severity</div>
            </div>
        </div>
    </div>
</div>

<!-- ================= FILTERS & SEARCH ================= -->
<div class="filter-section">
    <div class="row">
        <div class="col-md-4">
            <form method="GET" action="" class="form-inline" style="width:100%;">
                <div class="form-group" style="width:100%;">
                    <label><i class="fa fa-filter"></i> Severity:</label>
                    <select name="severity" class="form-control" style="width:70%; margin-left:10px;" onchange="this.form.submit()">
                        <option value="all">All Severities</option>
                        <option value="Low" <?php echo $severity_filter == 'Low' ? 'selected' : ''; ?>>🟢 Low</option>
                        <option value="Medium" <?php echo $severity_filter == 'Medium' ? 'selected' : ''; ?>>🟠 Medium</option>
                        <option value="High" <?php echo $severity_filter == 'High' ? 'selected' : ''; ?>>🔴 High</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET" action="" class="form-inline" style="width:100%;">
                <div class="form-group" style="width:100%;">
                    <label><i class="fa fa-heart"></i> Status:</label>
                    <select name="status" class="form-control" style="width:70%; margin-left:10px;" onchange="this.form.submit()">
                        <option value="all">All Status</option>
                        <option value="Single" <?php echo $status_filter == 'Single' ? 'selected' : ''; ?>>Single</option>
                        <option value="Married" <?php echo $status_filter == 'Married' ? 'selected' : ''; ?>>Married</option>
                        <option value="Divorced" <?php echo $status_filter == 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                        <option value="Widowed" <?php echo $status_filter == 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form method="POST" action="search2.php" class="form-inline" style="width:100%;">
                <div class="form-group" style="width:100%;">
                    <label><i class="fa fa-search"></i> Search:</label>
                    <input type="text" name="search" class="form-control" style="width:60%; margin-left:10px;" placeholder="Name or ID...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= TABLE ================= -->
<div class="panel panel-default">
<div class="panel-body">

<div class="table-responsive">

<table class="table table-hover table-bordered">

<thead>
<tr>
    <th>Photo</th>
    <th>ID</th>
    <th>First Name</th>
    <th>Middle Name</th>
    <th>Last Name</th>
    <th>Age</th>
    <th>Gender</th>
    <th>Address</th>
    <th>Contact</th>
    <th>Status</th>
    <th>Previous Record</th>
    <th>Criminal Record & Severity</th>
    <th>Start Date</th>
    <th>End Date</th>
</tr>
</thead>

<tbody>

<?php
// Truncate function
function truncateText($text, $length = 60) {
    if(strlen($text) <= $length){
        return $text;
    }
    return substr($text, 0, $length) . "...";
}

// Get severity badge class and label
function getSeverityBadge($severity) {
    $severity_upper = ucfirst(strtolower($severity));
    
    switch($severity_upper) {
        case 'High':
            return '<span class="severity-badge severity-high"><i class="fa fa-bolt"></i> High Severity</span>';
        case 'Medium':
            return '<span class="severity-badge severity-medium"><i class="fa fa-chart-line"></i> Medium Severity</span>';
        case 'Low':
            return '<span class="severity-badge severity-low"><i class="fa fa-check-circle"></i> Low Severity</span>';
        default:
            return '<span class="severity-badge" style="background:#e2e8f0; color:#475569;">Not Classified</span>';
    }
}

// Main query with stored severity from database
$query_sql = "
    SELECT 
        prisoner.*,
        prisoner_images.path AS prisoner_photo
    FROM prisoner
    LEFT JOIN prisoner_images 
        ON prisoner.prison_ID = prisoner_images.prisoner_id
    $where_sql
    GROUP BY prisoner.prison_ID
    ORDER BY Prison_Date DESC
    LIMIT $start, $limit
";

$query = $conn->prepare($query_sql);
if(count($params) > 0) {
    $query->bind_param($types, ...$params);
}
$query->execute();
$result = $query->get_result();

if($result->num_rows > 0):

while($row = $result->fetch_assoc()):

    // Get severity from database (stored from add_prison.php)
    $stored_severity = isset($row['criminal_severity']) ? $row['criminal_severity'] : '';
    $severity_badge_html = getSeverityBadge($stored_severity);
    
    $truncated_record = truncateText($row['criminal_record'], 80);
?>

<tr>

<!-- PHOTO -->
<td style="text-align:center; vertical-align:middle;">

<?php if(!empty($row['prisoner_photo']) && file_exists($row['prisoner_photo'])): ?>

    <img 
        src="<?= htmlspecialchars($row['prisoner_photo']) ?>"
        class="img-thumbnail prisoner-photo"
        data-toggle="modal"
        data-target="#photoModal<?= $row['prison_ID'] ?>"
        style="
            width:60px;
            height:60px;
            border-radius:50%;
            object-fit:cover;
            cursor:pointer;
        "
    >

<?php else: ?>

    <div style="
        width:60px;
        height:60px;
        border-radius:50%;
        background:#e2e8f0;
        line-height:60px;
        text-align:center;
        margin:auto;
        color:#64748b;
    ">
        <i class="fa fa-user"></i>
    </div>

<?php endif; ?>

</td>

<td class="text-center"><strong><?= $row['prison_ID'] ?></strong></td>
<td><?= htmlspecialchars($row['prison_fname']) ?></td>
<td><?= htmlspecialchars($row['prison_mname']) ?></td>
<td><?= htmlspecialchars($row['prison_lname']) ?></td>
<td class="text-center"><?= $row['prison_age'] ?></td>
<td class="text-center"><?= $row['prison_gen'] ?></td>
<td><?= htmlspecialchars(substr($row['prison_add'], 0, 30)) . (strlen($row['prison_add']) > 30 ? '...' : '') ?></td>
<td><?= htmlspecialchars($row['prison_cont']) ?></td>
<td class="text-center">
    <span class="label label-info"><?= $row['prison_stat'] ?></span>
</td>
<td><?= htmlspecialchars(truncateText($row['previews_record'], 40)) ?></td>

<!-- CRIMINAL RECORD & SEVERITY (Combined) -->
<td class="criminal-record-cell">

<?php if(!empty($row['criminal_record'])): ?>

    <span
        class="criminal-record-preview"
        data-toggle="modal"
        data-target="#criminalModal<?= $row['prison_ID'] ?>"
        title="Click to view full criminal record"
    >
        <i class="fa fa-file-text-o"></i>
        <?= htmlspecialchars($truncated_record) ?>
    </span>

    <br>

    <?= $severity_badge_html ?>

<?php else: ?>

    <em class="text-muted">No criminal record</em>
    <br>
    <?= $severity_badge_html ?>

<?php endif; ?>

</td>

<td class="text-center"><?= date('Y-m-d', strtotime($row['Prison_Date'])) ?></td>
<td class="text-center"><?= date('Y-m-d', strtotime($row['end_date'])) ?></td>

</tr>

<!-- PHOTO MODAL -->
<div class="modal fade" id="photoModal<?= $row['prison_ID'] ?>" tabindex="-1">
<div class="modal-dialog modal-md">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">
    <i class="fa fa-user"></i> Prisoner Photo
</h4>
</div>
<div class="modal-body text-center">
<?php if(!empty($row['prisoner_photo']) && file_exists($row['prisoner_photo'])): ?>
    <img 
        src="<?= htmlspecialchars($row['prisoner_photo']) ?>"
        class="img-responsive img-thumbnail"
        style="margin:auto; max-height:450px; border-radius:10px;"
    >
    <h4 style="margin-top:15px;">
        <?= htmlspecialchars($row['prison_fname'] . ' ' . $row['prison_lname']) ?>
    </h4>
    <p><strong>Prisoner ID:</strong> <?= $row['prison_ID'] ?></p>
<?php else: ?>
    <div class="alert alert-warning">
        <i class="fa fa-exclamation-triangle"></i> No photo uploaded for this prisoner.
    </div>
<?php endif; ?>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<!-- CRIMINAL RECORD MODAL (Enhanced with all details) -->
<div class="modal fade" id="criminalModal<?= $row['prison_ID'] ?>" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">
    <i class="fa fa-gavel"></i> Complete Criminal Record
    <small>Prisoner ID: <?= $row['prison_ID'] ?> | <?= htmlspecialchars($row['prison_fname'] . ' ' . $row['prison_lname']) ?></small>
</h4>
</div>
<div class="modal-body">

<!-- Criminal Record Full Text -->
<div class="criminal-record-full">
    <strong><i class="fa fa-file-text"></i> Criminal Record Details:</strong>
    <hr>
    <?= nl2br(htmlspecialchars($row['criminal_record'])) ?>
</div>

<hr>

<!-- Additional Information from add_prison.php -->
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-info-circle"></i> Prisoner Information</strong>
            </div>
            <div class="panel-body">
                <p><strong>Full Name:</strong> <?= htmlspecialchars($row['prison_fname'] . ' ' . $row['prison_mname'] . ' ' . $row['prison_lname']) ?></p>
                <p><strong>Age:</strong> <?= $row['prison_age'] ?> years</p>
                <p><strong>Gender:</strong> <?= $row['prison_gen'] ?></p>
                <p><strong>Marital Status:</strong> <?= $row['prison_stat'] ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($row['prison_cont']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($row['prison_add']) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-calendar"></i> Sentence Information</strong>
            </div>
            <div class="panel-body">
                <p><strong>Start Date:</strong> <?= date('F d, Y', strtotime($row['Prison_Date'])) ?></p>
                <p><strong>End Date:</strong> <?= date('F d, Y', strtotime($row['end_date'])) ?></p>
                <p><strong>Previous Record:</strong> <?= htmlspecialchars($row['previews_record'] ?: 'None') ?></p>
                <p><strong>Criminal Severity:</strong> <?= $severity_badge_html ?></p>
                <?php 
                // Calculate remaining time
                $end = new DateTime($row['end_date']);
                $now = new DateTime();
                $interval = $now->diff($end);
                if($end > $now) {
                    echo '<p class="text-success"><strong>Remaining:</strong> ' . $interval->y . ' years, ' . $interval->m . ' months, ' . $interval->d . ' days</p>';
                } else {
                    echo '<p class="text-danger"><strong>Status:</strong> Release date passed</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">
    <i class="fa fa-times"></i> Close
</button>
<button type="button" class="btn btn-primary" onclick="window.print();">
    <i class="fa fa-print"></i> Print
</button>
</div>
</div>
</div>
</div>

<?php endwhile; else: ?>

<tr>
    <td colspan="14" class="text-center">
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> No prisoners found matching the criteria.
        </div>
    </td>
</tr>

<?php endif; ?>

</tbody>
</table>

</div>
</div>
</div>

<!-- ================= PAGINATION ================= -->
<?php if($total > $limit): ?>
<ul class="pagination">
    <?php
    $total_pages = ceil($total / $limit);
    $query_params = [];
    if($severity_filter && $severity_filter != 'all') $query_params['severity'] = $severity_filter;
    if($status_filter && $status_filter != 'all') $query_params['status'] = $status_filter;
    
    for($i = 1; $i <= $total_pages; $i++):
        $query_params['page'] = $i;
        $url = '?' . http_build_query($query_params);
    ?>
    <li class="<?= ($page == $i) ? 'active' : '' ?>">
        <a href="<?= $url ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
</ul>
<?php endif; ?>

</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
$(document).ready(function(){
    $('.criminal-record-preview').tooltip({
        placement: 'top',
        title: 'Click to view full details'
    });
    
    // Add tooltip to severity badges
    $('.severity-badge').tooltip({
        placement: 'top',
        title: 'Criminal severity level from prisoner record'
    });
});
</script>

</body>
</html>