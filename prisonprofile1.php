<?php
include('DB.php'); // Must contain $connection = new mysqli(...)

// Function to determine severity of criminal record
function getSeverityClass($record) {
    if (empty($record)) return '';
    $record_lower = strtolower($record);
    if (strpos($record_lower, 'murder') !== false || 
        strpos($record_lower, 'homicide') !== false || 
        strpos($record_lower, 'rape') !== false || 
        strpos($record_lower, 'kidnapping') !== false ||
        strpos($record_lower, 'terrorism') !== false ||
        strpos($record_lower, 'manslaughter') !== false ||
        strpos($record_lower, 'attempted murder') !== false) {
        return 'severity-high';
    } elseif (strpos($record_lower, 'assault') !== false || 
              strpos($record_lower, 'robbery') !== false || 
              strpos($record_lower, 'theft') !== false || 
              strpos($record_lower, 'burglary') !== false ||
              strpos($record_lower, 'fraud') !== false ||
              strpos($record_lower, 'drug') !== false ||
              strpos($record_lower, 'embezzlement') !== false ||
              strpos($record_lower, 'forgery') !== false) {
        return 'severity-medium';
    } elseif (!empty($record)) {
        return 'severity-low';
    }
    return '';
}

// Function to truncate text
function truncateText($text, $length = 50) {
    if (empty($text)) return '';
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Prison Management System - Prisoner Records</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
    /* Page background */
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    
    /* Main container styling */
    .main-container {
        background: transparent;
    }
    
    /* Criminal record column styling */
    .criminal-record-cell {
        max-width: 220px;
        word-wrap: break-word;
        white-space: normal;
        font-size: 12px;
        background-color: #fff9e6;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .criminal-record-cell:hover {
        background-color: #ffefb9;
        transform: scale(1.02);
    }
    
    .criminal-record-preview {
        color: #337ab7;
        text-decoration: underline;
        font-weight: 500;
    }
    
    .criminal-record-preview:hover {
        color: #23527c;
    }
    
    /* Severity badges */
    .severity-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: bold;
        margin-top: 5px;
    }
    
    .severity-high {
        background-color: #d9534f;
        color: white;
        box-shadow: 0 1px 3px rgba(217,83,79,0.3);
    }
    
    .severity-medium {
        background-color: #f0ad4e;
        color: white;
        box-shadow: 0 1px 3px rgba(240,173,78,0.3);
    }
    
    .severity-low {
        background-color: #5cb85c;
        color: white;
        box-shadow: 0 1px 3px rgba(92,184,92,0.3);
    }
    
    .no-record {
        color: #999;
        font-style: italic;
    }
    
    /* Modal styles */
    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
        padding: 20px;
    }
    
    .modal-header.severity-high {
        background: linear-gradient(135deg, #d9534f 0%, #c9302c 100%);
    }
    
    .modal-header.severity-medium {
        background: linear-gradient(135deg, #f0ad4e 0%, #ec971f 100%);
    }
    
    .modal-header.severity-low {
        background: linear-gradient(135deg, #5cb85c 0%, #449d44 100%);
    }
    
    .modal-header .close {
        color: white;
        opacity: 0.8;
    }
    
    .modal-header .close:hover {
        opacity: 1;
    }
    
    .criminal-record-full {
        max-height: 400px;
        overflow-y: auto;
        padding: 20px;
        background-color: #fafafa;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 13px;
        line-height: 1.6;
        white-space: pre-wrap;
        border: 1px solid #e0e0e0;
    }
    
    .prisoner-details-card {
        background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    
    .prisoner-details-card .row {
        margin-bottom: 10px;
    }
    
    .criminal-icon {
        margin-right: 5px;
        color: #d9534f;
    }
    
    .record-badge {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 6px;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 0.5; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.2); }
        100% { opacity: 0.5; transform: scale(1); }
    }
    
    .record-high {
        background-color: #d9534f;
        box-shadow: 0 0 5px #d9534f;
    }
    
    .record-medium {
        background-color: #f0ad4e;
        box-shadow: 0 0 5px #f0ad4e;
    }
    
    .record-low {
        background-color: #5cb85c;
        box-shadow: 0 0 5px #5cb85c;
    }
    
    .record-none {
        background-color: #999;
        animation: none;
    }
    
    /* Table row hover effect */
    .table tbody tr:hover {
        background-color: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    /* Panel styling */
    .panel {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .panel-heading {
        background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%);
        color: white;
        border: none;
    }
    
    /* Return button styling */
    .return-home-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 50px;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }
    
    .return-home-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40,167,69,0.3);
        color: white;
        text-decoration: none;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .criminal-record-cell {
            max-width: 150px;
        }
        .severity-badge {
            font-size: 8px;
            padding: 2px 5px;
        }
    }
    
    /* Welcome header */
    .welcome-header {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
</head>

<body>

<div class="container-fluid main-container" style="margin-top:0; padding:20px;">
    
    <!-- Return to Home Button -->
    <div class="container">
        <a href="index4.php" class="return-home-btn">
            <i class="fa fa-home"></i> Return to Home Page
        </a>
    </div>

    <div class="container">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1><i class="fa fa-gavel"></i> Prisoner Records Management</h1>
                    <p class="text-muted">View and manage all prisoner information including criminal records</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                  // Pagination
                  $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
                  $limit = 10;
                  $start = ($page - 1) * $limit;

                  $queryCount = $conn->query("SELECT COUNT(*) AS total FROM prisoner");
                  $countRow = $queryCount->fetch_assoc();
                  $totalRows = $countRow['total'];

                  echo "<h2><i class='fa fa-users'></i> Total Prisoners: <span class='label label-primary'>$totalRows</span></h2>";
                ?>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-search"></i> Search Prisoner</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" action="search3.php">
                            <div class="form-group">
                                <input name="search" type="text" class="form-control" placeholder="Search by FName, MName, LName">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-fw fa-search"></i> Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prisoner Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> Prisoner Records</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="active">
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
                                <th>Criminal Record</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                          $sql = "SELECT * FROM prisoner ORDER BY Prison_Date DESC LIMIT $start, $limit";
                          $result = $conn->query($sql);

                          while ($row = $result->fetch_assoc()) {
                              $criminal_record = isset($row['criminal_record']) ? $row['criminal_record'] : '';
                              $severity_class = getSeverityClass($criminal_record);
                              $truncated_record = truncateText($criminal_record, 45);
                              $record_badge_class = $severity_class ? str_replace('severity-', 'record-', $severity_class) : 'record-none';
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['prison_ID']) ?></td>
                                <td><?= htmlspecialchars($row['prison_fname']) ?></td>
                                <td><?= htmlspecialchars($row['prison_mname']) ?></td>
                                <td><?= htmlspecialchars($row['prison_lname']) ?></td>
                                <td><?= htmlspecialchars($row['prison_age']) ?></td>
                                <td><?= htmlspecialchars($row['prison_gen']) ?></td>
                                <td><?= htmlspecialchars($row['prison_add']) ?></td>
                                <td><?= htmlspecialchars($row['prison_cont']) ?></td>
                                <td><?= htmlspecialchars($row['prison_stat']) ?></td>
                                <td><?= htmlspecialchars($row['previews_record']) ?></td>
                                
                                <!-- Criminal Record Column -->
                                <td class="criminal-record-cell" onclick="showCriminalRecordModal(
                                    '<?= htmlspecialchars($row['prison_ID']) ?>',
                                    '<?= htmlspecialchars(addslashes($row['prison_fname'] . ' ' . $row['prison_mname'] . ' ' . $row['prison_lname'])) ?>',
                                    '<?= htmlspecialchars(addslashes($criminal_record)) ?>',
                                    '<?= $severity_class ?>',
                                    '<?= htmlspecialchars($row['Prison_Date']) ?>',
                                    '<?= htmlspecialchars($row['prison_gen']) ?>',
                                    '<?= htmlspecialchars($row['prison_stat']) ?>',
                                    '<?= htmlspecialchars($row['prison_age']) ?>',
                                    '<?= htmlspecialchars($row['end_date']) ?>'
                                )">
                                    <?php if (!empty($criminal_record)): ?>
                                        <span class="record-badge <?= $record_badge_class ?>"></span>
                                        <span class="criminal-record-preview">
                                            <i class="fa fa-gavel"></i> <?= htmlspecialchars($truncated_record) ?>
                                        </span>
                                        <br>
                                        <span class="severity-badge <?= $severity_class ?>">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            <?= strtoupper(str_replace('severity-', '', $severity_class)) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="record-badge record-none"></span>
                                        <span class="no-record">
                                            <i class="fa fa-check-circle"></i> No criminal record
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td><?= htmlspecialchars($row['Prison_Date']) ?></td>
                                <td><?= htmlspecialchars($row['end_date']) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="text-center">
            <ul class="pagination">
            <?php
              $totalPages = ceil($totalRows / $limit);
              for ($i = 1; $i <= $totalPages; $i++) {
                $active = ($i == $page) ? 'class="active"' : '';
                echo "<li $active><a href='?page=$i'>$i</a></li>";
              }
            ?>
            </ul>
        </div>
    </div>
</div>

<!-- Modal for Criminal Record Details -->
<div class="modal fade" id="criminalRecordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-gavel"></i> Criminal Record Details
                </h4>
            </div>
            <div class="modal-body">
                <div class="criminal-record-full" id="modalCriminalRecord">
                    <!-- Criminal record content will be inserted here -->
                </div>
                
                <div class="prisoner-details-card">
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fa fa-user"></i> Prisoner:</strong> 
                            <span id="modalPrisonerName"></span>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fa fa-calendar"></i> Prison Date:</strong> 
                            <span id="modalPrisonDate"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fa fa-venus-mars"></i> Gender:</strong> 
                            <span id="modalGender"></span>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fa fa-heart"></i> Marital Status:</strong> 
                            <span id="modalStatus"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fa fa-calendar-alt"></i> Age:</strong> 
                            <span id="modalAge"></span>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fa fa-calendar-minus"></i> Release Date:</strong> 
                            <span id="modalReleaseDate"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong><i class="fa fa-tag"></i> Severity Level:</strong>
                            <span id="modalSeverity" class="severity-badge" style="margin-left: 10px;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
// Function to show criminal record modal
function showCriminalRecordModal(id, name, record, severity, prisonDate, gender, status, age, releaseDate) {
    // Set modal content
    var modalContent = document.getElementById('modalCriminalRecord');
    if (record && record.trim() !== '') {
        modalContent.innerHTML = record.replace(/\n/g, '<br>');
    } else {
        modalContent.innerHTML = '<em><i class="fa fa-info-circle"></i> No criminal record available for this prisoner.</em>';
    }
    
    document.getElementById('modalPrisonerName').innerHTML = name + ' <span class="label label-default">ID: ' + id + '</span>';
    document.getElementById('modalPrisonDate').innerHTML = prisonDate;
    document.getElementById('modalGender').innerHTML = gender;
    document.getElementById('modalStatus').innerHTML = status;
    document.getElementById('modalAge').innerHTML = age;
    document.getElementById('modalReleaseDate').innerHTML = releaseDate;
    
    // Set severity badge
    var severityBadge = document.getElementById('modalSeverity');
    var modalHeader = document.getElementById('modalHeader');
    
    // Reset classes
    severityBadge.className = 'severity-badge';
    modalHeader.className = 'modal-header';
    
    if (severity === 'severity-high') {
        severityBadge.classList.add('severity-high');
        modalHeader.classList.add('severity-high');
        severityBadge.innerHTML = '<i class="fa fa-exclamation-triangle"></i> HIGH SEVERITY - Serious violent offense';
    } else if (severity === 'severity-medium') {
        severityBadge.classList.add('severity-medium');
        modalHeader.classList.add('severity-medium');
        severityBadge.innerHTML = '<i class="fa fa-exclamation-triangle"></i> MEDIUM SEVERITY - Moderate offense';
    } else if (severity === 'severity-low') {
        severityBadge.classList.add('severity-low');
        modalHeader.classList.add('severity-low');
        severityBadge.innerHTML = '<i class="fa fa-info-circle"></i> LOW SEVERITY - Minor offense';
    } else {
        severityBadge.classList.add('severity-low');
        severityBadge.innerHTML = '<i class="fa fa-check-circle"></i> NO RECORD';
    }
    
    // Show modal with animation
    $('#criminalRecordModal').modal({
        backdrop: 'static',
        keyboard: true
    });
}

// Add tooltip to criminal record cells
$(document).ready(function() {
    $('.criminal-record-cell').attr('title', 'Click to view full criminal record details');
    
    // Add animation on modal show
    $('#criminalRecordModal').on('show.bs.modal', function() {
        $('.modal-content').css('transform', 'scale(0.8)');
        setTimeout(function() {
            $('.modal-content').css('transform', 'scale(1)');
        }, 50);
    });
});
</script>


</body>
</html>