<?php
include('DB.php');

// Pagination setup
$limit = 10;
$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0)
    ? intval($_GET['page'])
    : 1;

$offset = ($page - 1) * $limit;

// Check existing columns in prisoner table
$columns = [];
$colCheck = $conn->query("SHOW COLUMNS FROM prisoner");

while ($col = $colCheck->fetch_assoc()) {
    $columns[] = $col['Field'];
}

// Available fields mapping
$availableFields = [
    'prison_ID' => 'prison_ID',
    'prison_fname' => 'prison_fname',
    'prison_mname' => 'prison_mname',
    'prison_lname' => 'prison_lname',
    'prison_age' => 'prison_age',
    'prison_gen' => 'prison_gen',
    'prison_add' => 'prison_add',
    'prison_cont' => 'prison_cont',
    'prison_stat' => 'prison_stat',
    'prison_recored' => 'prison_time',
    'previews_record' => 'previews_record',
    'criminal_record' => 'criminal_record',
    'Prison_Date' => 'start_date',
    'end_date' => 'end_date',
    'released_date' => 'released_date'
];

// Build SELECT query
$selectedColumns = [];

foreach ($availableFields as $requested => $alternative) {
    if (in_array($requested, $columns)) {
        $selectedColumns[] = $requested;
    } elseif (in_array($alternative, $columns)) {
        $selectedColumns[] = $alternative;
    }
}

if (empty($selectedColumns)) {
    $selectedColumns = ['prison_ID', 'prison_fname', 'prison_mname', 'prison_lname'];
}

$selectSQL = "SELECT " . implode(", ", $selectedColumns) . " 
              FROM prisoner 
              ORDER BY prison_ID DESC 
              LIMIT ?, ?";

// Count total rows
$stmtCount = $conn->prepare("SELECT COUNT(*) FROM prisoner");
$stmtCount->execute();
$stmtCount->bind_result($total_rows);
$stmtCount->fetch();
$stmtCount->close();

$total_pages = ($total_rows > 0) ? ceil($total_rows / $limit) : 1;
$page = max(1, min($page, $total_pages));
$offset = ($page - 1) * $limit;

// Fetch data
$records = [];

$stmt = $conn->prepare($selectSQL);
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

$stmt->close();

// Function to determine severity of criminal record
function getSeverityClass($record) {
    if (empty($record)) return '';
    $record_lower = strtolower($record);
    if (strpos($record_lower, 'murder') !== false || 
        strpos($record_lower, 'homicide') !== false || 
        strpos($record_lower, 'rape') !== false || 
        strpos($record_lower, 'kidnapping') !== false ||
        strpos($record_lower, 'terrorism') !== false ||
        strpos($record_lower, 'manslaughter') !== false) {
        return 'severity-high';
    } elseif (strpos($record_lower, 'assault') !== false || 
              strpos($record_lower, 'robbery') !== false || 
              strpos($record_lower, 'theft') !== false || 
              strpos($record_lower, 'burglary') !== false ||
              strpos($record_lower, 'fraud') !== false ||
              strpos($record_lower, 'drug') !== false ||
              strpos($record_lower, 'cheating') !== false) {
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
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Prisoner Archive - Criminal Records Management</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
* {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Return Home Button */
.return-home-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 12px 25px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 25px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.return-home-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(40,167,69,0.3);
    color: white;
    text-decoration: none;
}

.return-home-btn i {
    font-size: 18px;
}

/* Header Section */
.header-section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.header-section h1 {
    margin: 0 0 10px 0;
    color: #2c3e50;
    font-size: 28px;
    font-weight: 600;
}

.header-section h1 i {
    color: #667eea;
    margin-right: 10px;
}

.header-section p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

/* Stats Cards */
.stats-container {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.stat-box {
    background: white;
    border-radius: 12px;
    padding: 20px;
    flex: 1;
    min-width: 150px;
    text-align: center;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-5px);
}

.stat-box i {
    font-size: 35px;
    color: #667eea;
    margin-bottom: 10px;
}

.stat-box .number {
    font-size: 28px;
    font-weight: bold;
    color: #2c3e50;
}

.stat-box .label {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
}

/* Search Section */
.search-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.search-box {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.search-box input {
    flex: 1;
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #667eea;
}

.search-box button {
    padding: 10px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-box button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.3);
}

/* Table Styles */
.table-container {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow-x: auto;
}

.prisoner-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.prisoner-table th {
    background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%);
    color: white;
    padding: 12px 10px;
    text-align: left;
    font-weight: 600;
    position: sticky;
    top: 0;
}

.prisoner-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
}

.prisoner-table tr:hover {
    background-color: #f8f9fa;
}

/* Criminal Record Cell */
.criminal-cell {
    max-width: 200px;
    background-color: #fff9e6;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.criminal-cell:hover {
    background-color: #ffefb9;
    transform: scale(1.02);
}

.criminal-preview {
    color: #337ab7;
    text-decoration: underline;
    font-size: 12px;
}

.severity-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: bold;
    margin-top: 5px;
}

.severity-high {
    background-color: #dc3545;
    color: white;
}

.severity-medium {
    background-color: #fd7e14;
    color: white;
}

.severity-low {
    background-color: #28a745;
    color: white;
}

.record-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 6px;
}

.dot-high { background-color: #dc3545; }
.dot-medium { background-color: #fd7e14; }
.dot-low { background-color: #28a745; }
.dot-none { background-color: #999; }

.no-record {
    color: #999;
    font-style: italic;
}

/* Action Button */
.btn-view {
    background: #1e4b43;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.btn-view:hover {
    background: #0b2b26;
    color: white;
    text-decoration: none;
    transform: translateX(2px);
}

/* Pagination */
.pagination-container {
    margin-top: 25px;
    text-align: center;
}

.pagination {
    display: inline-flex;
    gap: 5px;
    list-style: none;
    padding: 0;
}

.pagination li a {
    padding: 8px 15px;
    background: white;
    color: #667eea;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.pagination li.active a {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.pagination li a:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

/* Modal */
.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border: none;
}

.modal-header.severity-high {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.modal-header.severity-medium {
    background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
}

.modal-header.severity-low {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.criminal-record-full {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    font-family: monospace;
    font-size: 13px;
    line-height: 1.6;
    max-height: 350px;
    overflow-y: auto;
}

.details-card {
    background: #e9ecef;
    padding: 15px;
    border-radius: 10px;
    margin-top: 15px;
}

.details-card p {
    margin: 8px 0;
}

/* Footer */
.footer {
    text-align: center;
    margin-top: 30px;
    color: white;
    font-size: 12px;
}

/* Responsive */
@media (max-width: 768px) {
    .prisoner-table th, .prisoner-table td {
        padding: 8px 5px;
        font-size: 11px;
    }
    .stat-box .number {
        font-size: 20px;
    }
    .criminal-cell {
        max-width: 120px;
    }
    .return-home-btn {
        padding: 8px 20px;
        font-size: 14px;
        width: 100%;
        justify-content: center;
    }
}
</style>
</head>

<body>

<div class="container">
    
    <!-- Return to Home Page Button -->
    <a href="index5.php" class="return-home-btn">
        <i class="fa fa-home"></i> Return to Home Page
    </a>
    
    <!-- Header Section -->
    <div class="header-section">
        <h1>
            <i class="fa fa-archive"></i> Prisoner Archive
        </h1>
        <p>View and manage all prisoner records including criminal history and sentencing information</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-box">
            <i class="fa fa-users"></i>
            <div class="number"><?= $total_rows ?></div>
            <div class="label">Total Prisoners</div>
        </div>
        <div class="stat-box">
            <i class="fa fa-gavel"></i>
            <div class="number">
                <?php 
                $crime_count = 0;
                foreach ($records as $rec) {
                    if (!empty($rec['criminal_record'] ?? '')) $crime_count++;
                }
                echo $crime_count;
                ?>
            </div>
            <div class="label">With Criminal Records</div>
        </div>
        <div class="stat-box">
            <i class="fa fa-check-circle"></i>
            <div class="number">
                <?php 
                $clean_count = 0;
                foreach ($records as $rec) {
                    if (empty($rec['criminal_record'] ?? '')) $clean_count++;
                }
                echo $clean_count;
                ?>
            </div>
            <div class="label">Clean Records</div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by ID, Name, or Criminal Record..." onkeyup="searchTable()">
            <button onclick="searchTable()">
                <i class="fa fa-search"></i> Search
            </button>
            <button onclick="clearSearch()" style="background: #6c757d;">
                <i class="fa fa-refresh"></i> Reset
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <table class="prisoner-table" id="prisonerTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Criminal Record</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr>
                        <td colspan="11" style="text-align: center; padding: 50px;">
                            <i class="fa fa-inbox fa-3x" style="color: #ccc;"></i>
                            <p style="margin-top: 10px;">No records found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($records as $row): 
                        $full_name = trim(($row['prison_fname'] ?? '') . ' ' . ($row['prison_mname'] ?? '') . ' ' . ($row['prison_lname'] ?? ''));
                        $criminal_record = $row['criminal_record'] ?? '';
                        $severity_class = getSeverityClass($criminal_record);
                        $truncated_record = truncateText($criminal_record, 45);
                        $dot_class = $severity_class ? str_replace('severity-', 'dot-', $severity_class) : 'dot-none';
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['prison_ID'] ?? '') ?></td>
                            <td><?= htmlspecialchars($full_name ?: 'N/A') ?></td>
                            <td><?= $row['prison_age'] ?? 'N/A' ?></td>
                            <td><?= $row['prison_gen'] ?? 'N/A' ?></td>
                            <td><?= htmlspecialchars(substr($row['prison_add'] ?? '', 0, 30)) ?></td>
                            <td><?= $row['prison_cont'] ?? 'N/A' ?></td>
                            <td><?= $row['prison_stat'] ?? 'N/A' ?></td>
                            
                            <td class="criminal-cell" onclick="showCriminalModal(
                                '<?= $row['prison_ID'] ?? '' ?>',
                                '<?= htmlspecialchars(addslashes($full_name)) ?>',
                                '<?= htmlspecialchars(addslashes($criminal_record)) ?>',
                                '<?= $severity_class ?>',
                                '<?= $row['Prison_Date'] ?? '' ?>',
                                '<?= $row['prison_gen'] ?? '' ?>',
                                '<?= $row['prison_stat'] ?? '' ?>',
                                '<?= $row['prison_age'] ?? '' ?>',
                                '<?= $row['end_date'] ?? '' ?>'
                            )">
                                <?php if (!empty($criminal_record)): ?>
                                    <span class="record-dot <?= $dot_class ?>"></span>
                                    <span class="criminal-preview">
                                        <i class="fa fa-gavel"></i> <?= htmlspecialchars($truncated_record) ?>
                                    </span>
                                    <br>
                                    <span class="severity-badge <?= $severity_class ?>">
                                        <?= strtoupper(str_replace('severity-', '', $severity_class)) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="record-dot dot-none"></span>
                                    <span class="no-record">
                                        <i class="fa fa-check-circle"></i> No criminal record
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <td><?= date('Y-m-d', strtotime($row['Prison_Date'] ?? 'now')) ?></td>
                            <td><?= $row['end_date'] ? date('Y-m-d', strtotime($row['end_date'])) : 'N/A' ?></td>
                            
                            <td>
                                <a href="profile4.php?id=<?= $row['prison_ID'] ?>" class="btn-view">
                                    <i class="fa fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination-container">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="<?= ($i == $page) ? 'active' : '' ?>">
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?= date('Y') ?> Woliso Prison Management System. All rights reserved.</p>
    </div>
</div>

<!-- Modal for Criminal Record Details -->
<div class="modal fade" id="criminalModal" tabindex="-1" role="dialog">
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
                    Loading...
                </div>
                <div class="details-card">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fa fa-user"></i> Prisoner:</strong> <span id="modalPrisonerName"></span></p>
                            <p><strong><i class="fa fa-venus-mars"></i> Gender:</strong> <span id="modalGender"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fa fa-calendar"></i> Start Date:</strong> <span id="modalStartDate"></span></p>
                            <p><strong><i class="fa fa-heart"></i> Status:</strong> <span id="modalStatus"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fa fa-calendar-alt"></i> Age:</strong> <span id="modalAge"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fa fa-calendar-minus"></i> End Date:</strong> <span id="modalEndDate"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong><i class="fa fa-tag"></i> Severity:</strong> <span id="modalSeverity" class="severity-badge"></span></p>
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
// Search function
function searchTable() {
    var input = document.getElementById('searchInput');
    var filter = input.value.toLowerCase();
    var table = document.getElementById('prisonerTable');
    var rows = table.getElementsByTagName('tr');
    
    for (var i = 1; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName('td');
        var found = false;
        
        for (var j = 0; j < cells.length; j++) {
            var cellText = cells[j].innerText || cells[j].textContent;
            if (cellText.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        rows[i].style.display = found ? '' : 'none';
    }
}

// Clear search
function clearSearch() {
    document.getElementById('searchInput').value = '';
    searchTable();
}

// Enter key search
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchTable();
    }
});

// Show criminal record modal
function showCriminalModal(id, name, record, severity, startDate, gender, status, age, endDate) {
    var modalContent = document.getElementById('modalCriminalRecord');
    var modalHeader = document.getElementById('modalHeader');
    var severityBadge = document.getElementById('modalSeverity');
    
    // Set content
    if (record && record.trim() !== '') {
        modalContent.innerHTML = record.replace(/\n/g, '<br>');
    } else {
        modalContent.innerHTML = '<em>No criminal record available for this prisoner.</em>';
    }
    
    document.getElementById('modalPrisonerName').innerHTML = name + ' (ID: ' + id + ')';
    document.getElementById('modalStartDate').innerHTML = startDate || 'N/A';
    document.getElementById('modalGender').innerHTML = gender || 'N/A';
    document.getElementById('modalStatus').innerHTML = status || 'N/A';
    document.getElementById('modalAge').innerHTML = age || 'N/A';
    document.getElementById('modalEndDate').innerHTML = endDate || 'N/A';
    
    // Reset classes
    severityBadge.className = 'severity-badge';
    modalHeader.className = 'modal-header';
    
    if (severity === 'severity-high') {
        severityBadge.classList.add('severity-high');
        modalHeader.classList.add('severity-high');
        severityBadge.innerHTML = 'HIGH SEVERITY - Serious violent offense';
    } else if (severity === 'severity-medium') {
        severityBadge.classList.add('severity-medium');
        modalHeader.classList.add('severity-medium');
        severityBadge.innerHTML = 'MEDIUM SEVERITY - Moderate offense';
    } else if (severity === 'severity-low') {
        severityBadge.classList.add('severity-low');
        modalHeader.classList.add('severity-low');
        severityBadge.innerHTML = 'LOW SEVERITY - Minor offense';
    } else {
        severityBadge.classList.add('severity-low');
        severityBadge.innerHTML = 'NO CRIMINAL RECORD';
    }
    
    $('#criminalModal').modal('show');
}
</script>

</body>
</html>