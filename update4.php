<?php
include('DB.php'); // $conn = mysqli connection

// Pagination setup
$endlimit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start_from = ($page - 1) * $endlimit;

// Count total prisoners
$result_total = $conn->query("SELECT COUNT(*) AS total FROM prisoner");
$total_rows = $result_total->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $endlimit);

// Fetch prisoners
$sql = "SELECT * FROM prisoner ORDER BY Prison_Date DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $start_from, $endlimit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Woliso Prison Management System</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
    /* Criminal record column styling */
    .criminal-record-cell {
        max-width: 250px;
        word-wrap: break-word;
        white-space: normal;
        font-size: 12px;
        background-color: #fef9e6;
        cursor: pointer;
    }
    
    .criminal-record-cell:hover {
        background-color: #fef0d8;
    }
    
    .criminal-record-preview {
        color: #337ab7;
        text-decoration: underline;
    }
    
    .criminal-record-preview:hover {
        color: #23527c;
    }
    
    /* Severity badges */
    .severity-badge {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 12px;
        font-size: 9px;
        font-weight: bold;
        margin-top: 5px;
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
    
    .no-record {
        color: #999;
        font-style: italic;
    }
    
    /* Modal styles */
    .modal-content {
        border-radius: 8px;
    }
    
    .modal-header {
        background-color: #f5f5f5;
        border-bottom: 2px solid #337ab7;
    }
    
    .modal-header.severity-high {
        background-color: #d9534f;
        color: white;
        border-bottom-color: #c12e2a;
    }
    
    .modal-header.severity-medium {
        background-color: #f0ad4e;
        color: white;
        border-bottom-color: #ec971f;
    }
    
    .modal-header.severity-low {
        background-color: #5cb85c;
        color: white;
        border-bottom-color: #449d44;
    }
    
    .criminal-record-full {
        max-height: 400px;
        overflow-y: auto;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 5px;
        font-family: monospace;
        font-size: 13px;
        line-height: 1.6;
    }
    
    .prisoner-details-card {
        background-color: #f5f5f5;
        padding: 10px;
        border-radius: 5px;
        margin-top: 15px;
    }
    
    .criminal-icon {
        margin-right: 5px;
        color: #d9534f;
    }
    
    /* Action buttons */
    .action-icons a {
        margin: 0 5px;
        text-decoration: none;
    }
    
    .action-icons a:hover {
        opacity: 0.8;
    }
    
    /* Table responsive fix */
    .table-responsive {
        overflow-x: auto;
    }
    
    /* Badge for record indicator */
    .record-badge {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 5px;
    }
    
    .record-high {
        background-color: #d9534f;
        box-shadow: 0 0 3px #d9534f;
    }
    
    .record-medium {
        background-color: #f0ad4e;
        box-shadow: 0 0 3px #f0ad4e;
    }
    
    .record-low {
        background-color: #5cb85c;
        box-shadow: 0 0 3px #5cb85c;
    }
    
    .record-none {
        background-color: #999;
    }
</style>
</head>

<body>

<div class="container" style="margin-top:60px;">

    <!-- Return to Home Button -->
    <a href="index3.php" class="btn btn-success" style="margin-bottom:15px;">
        ← Return to Home Page
    </a>

    <div class="row">
        <div class="col-md-8">
            <h2>
                <i class="fa fa-users"></i> Total Prisoners: <?= $total_rows ?>
            </h2>
        </div>
        <div class="col-md-4">
            <div class="alert alert-info" style="padding: 8px; margin-top: 20px;">
                <i class="fa fa-gavel"></i> <strong>Criminal Records:</strong> 
                Click on any criminal record to view full details
            </div>
        </div>
    </div>

    <!-- Search form -->
    <div class="row">
        <div class="col-md-4">
            <form method="post" action="search.php" class="form-inline">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by ID or Name">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="input-group">
                <input type="text" id="criminalSearch" class="form-control" placeholder="Filter by criminal record...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="filterByCriminalRecord()">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </span>
            </div>
        </div>
    </div>

    <br>

    <!-- Prisoner Table -->
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="prisonerTable">
                <thead>
                    <tr class="active">
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Criminal Record</th>
                        <th>Prison Date</th>
                        <th>End Date</th>
                        <th colspan="2">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php 
                // Function to determine severity of criminal record
                function getSeverityClassForList($record) {
                    if (empty($record)) return '';
                    $record_lower = strtolower($record);
                    if (strpos($record_lower, 'murder') !== false || 
                        strpos($record_lower, 'homicide') !== false || 
                        strpos($record_lower, 'rape') !== false || 
                        strpos($record_lower, 'kidnapping') !== false ||
                        strpos($record_lower, 'terrorism') !== false) {
                        return 'severity-high';
                    } elseif (strpos($record_lower, 'assault') !== false || 
                              strpos($record_lower, 'robbery') !== false || 
                              strpos($record_lower, 'theft') !== false || 
                              strpos($record_lower, 'burglary') !== false ||
                              strpos($record_lower, 'fraud') !== false ||
                              strpos($record_lower, 'drug') !== false) {
                        return 'severity-medium';
                    } elseif (!empty($record)) {
                        return 'severity-low';
                    }
                    return '';
                }
                
                // Function to truncate text
                function truncateTextForList($text, $length = 50) {
                    if (empty($text)) return '';
                    if (strlen($text) <= $length) {
                        return $text;
                    }
                    return substr($text, 0, $length) . '...';
                }
                
                while($row = $result->fetch_assoc()):
                    $criminal_record = isset($row['criminal_record']) ? $row['criminal_record'] : '';
                    $severity_class = getSeverityClassForList($criminal_record);
                    $truncated_record = truncateTextForList($criminal_record, 50);
                    $record_badge_class = $severity_class ? str_replace('severity-', 'record-', $severity_class) : 'record-none';
                ?>
                    <tr class="prisoner-row" data-criminal="<?= strtolower(htmlspecialchars($criminal_record)) ?>">
                        <td><?= htmlspecialchars($row['prison_ID']) ?></td>
                        <td><?= htmlspecialchars($row['prison_fname']) ?></td>
                        <td><?= htmlspecialchars($row['prison_mname']) ?></td>
                        <td><?= htmlspecialchars($row['prison_age']) ?></td>
                        <td><?= htmlspecialchars($row['prison_gen']) ?></td>
                        <td><?= htmlspecialchars($row['prison_add']) ?></td>
                        <td><?= htmlspecialchars($row['prison_cont']) ?></td>
                        <td><?= htmlspecialchars($row['prison_stat']) ?></td>
                        
                        <!-- Criminal Record Column -->
                        <td class="criminal-record-cell" onclick="showCriminalRecordModal(
                            '<?= htmlspecialchars($row['prison_ID']) ?>',
                            '<?= htmlspecialchars(addslashes($row['prison_fname'] . ' ' . $row['prison_lname'])) ?>',
                            '<?= htmlspecialchars(addslashes($criminal_record)) ?>',
                            '<?= $severity_class ?>',
                            '<?= htmlspecialchars($row['Prison_Date']) ?>',
                            '<?= htmlspecialchars($row['prison_gen']) ?>',
                            '<?= htmlspecialchars($row['prison_stat']) ?>'
                        )">
                            <?php if (!empty($criminal_record)): ?>
                                <span class="record-badge <?= $record_badge_class ?>"></span>
                                <span class="criminal-record-preview">
                                    <?= htmlspecialchars($truncated_record) ?>
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
                        
                        <td class="action-icons">
                            <a href="profile.php?id=<?= $row['prison_ID'] ?>" title="View Profile">
                                <span class="glyphicon glyphicon-list-alt" style="font-size:18px; color:#337ab7;"></span>
                            </a>
                        </td>
                        
                        <td class="action-icons">
                            <a href="edit_prisoner.php?id=<?= $row['prison_ID'] ?>" title="Edit">
                                <span class="glyphicon glyphicon-edit" style="font-size:18px; color:#f0ad4e;"></span>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <li class="<?= ($i==$page)?'active':'' ?>">
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            </ul>
        </nav>
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
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-6">
                            <strong><i class="fa fa-venus-mars"></i> Gender:</strong> 
                            <span id="modalGender"></span>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fa fa-heart"></i> Marital Status:</strong> 
                            <span id="modalStatus"></span>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <strong><i class="fa fa-tag"></i> Severity Level:</strong>
                            <span id="modalSeverity" class="severity-badge"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
                <a href="#" id="editPrisonerLink" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit Prisoner
                </a>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
// Function to show criminal record modal
function showCriminalRecordModal(id, name, record, severity, prisonDate, gender, status) {
    // Set modal content
    document.getElementById('modalCriminalRecord').innerHTML = record ? record.replace(/\n/g, '<br>') : '<em>No criminal record available</em>';
    document.getElementById('modalPrisonerName').innerHTML = name + ' (ID: ' + id + ')';
    document.getElementById('modalPrisonDate').innerHTML = prisonDate;
    document.getElementById('modalGender').innerHTML = gender;
    document.getElementById('modalStatus').innerHTML = status;
    
    // Set severity badge
    var severityBadge = document.getElementById('modalSeverity');
    var modalHeader = document.getElementById('modalHeader');
    
    // Reset classes
    severityBadge.className = 'severity-badge';
    modalHeader.className = 'modal-header';
    
    if (severity === 'severity-high') {
        severityBadge.classList.add('severity-high');
        modalHeader.classList.add('severity-high');
        severityBadge.innerHTML = '<i class="fa fa-exclamation-triangle"></i> HIGH SEVERITY';
    } else if (severity === 'severity-medium') {
        severityBadge.classList.add('severity-medium');
        modalHeader.classList.add('severity-medium');
        severityBadge.innerHTML = '<i class="fa fa-exclamation-triangle"></i> MEDIUM SEVERITY';
    } else if (severity === 'severity-low') {
        severityBadge.classList.add('severity-low');
        modalHeader.classList.add('severity-low');
        severityBadge.innerHTML = '<i class="fa fa-info-circle"></i> LOW SEVERITY';
    } else {
        severityBadge.innerHTML = '<i class="fa fa-check-circle"></i> NO RECORD';
    }
    
    // Set edit link
    document.getElementById('editPrisonerLink').href = 'edit_prisoner.php?id=' + id;
    
    // Show modal
    $('#criminalRecordModal').modal('show');
}

// Filter function for criminal records
function filterByCriminalRecord() {
    var input = document.getElementById('criminalSearch');
    var filter = input.value.toLowerCase();
    var table = document.getElementById('prisonerTable');
    var rows = table.getElementsByClassName('prisoner-row');
    
    for (var i = 0; i < rows.length; i++) {
        var criminalData = rows[i].getAttribute('data-criminal');
        if (criminalData && criminalData.indexOf(filter) > -1) {
            rows[i].style.display = '';
        } else if (!filter) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

// Add enter key support for filter
document.getElementById('criminalSearch').addEventListener('keyup', function(event) {
    if (event.key === 'Enter') {
        filterByCriminalRecord();
    }
});

// Highlight rows with criminal records on page load
$(document).ready(function() {
    $('.criminal-record-cell').each(function() {
        if ($(this).find('.severity-high').length) {
            $(this).closest('tr').css('border-left', '3px solid #d9534f');
        } else if ($(this).find('.severity-medium').length) {
            $(this).closest('tr').css('border-left', '3px solid #f0ad4e');
        } else if ($(this).find('.severity-low').length) {
            $(this).closest('tr').css('border-left', '3px solid #5cb85c');
        }
    });
});
</script>

<?php include('footer.php'); ?>

</body>
</html>