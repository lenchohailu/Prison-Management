<?php
include('session.php');
include('DB.php');

/* DELETE ATTENDANCE */
if(isset($_GET['delete'])){
    $prison_ID  = $_GET['prison_ID'];
    $Date       = $_GET['Date'];
    $Attendance = $_GET['Attendance'];

    $stmt = $conn->prepare("
        DELETE FROM attendance
        WHERE prison_ID = ? AND Date = ? AND Attendance = ?
        LIMIT 1
    ");
    $stmt->bind_param("iss", $prison_ID, $Date, $Attendance);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success'=>true]);
    exit;
}

/* UPDATE ATTENDANCE */
if(isset($_POST['action']) && $_POST['action']=='update_attendance'){

    $prison_ID = $_POST['prison_ID'];
    $oldDate   = $_POST['oldDate'];
    $field     = $_POST['field'];
    $value     = $_POST['value'];

    $allowed = ['prison_fname','prison_mname','Attendance','Date'];

    if(in_array($field,$allowed)){

        $stmt = $conn->prepare("
            UPDATE attendance
            SET $field = ?
            WHERE prison_ID = ? AND Date = ?
            LIMIT 1
        ");

        $stmt->bind_param("sis",$value,$prison_ID,$oldDate);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['success'=>true]);
        exit;
    }

    echo json_encode(['success'=>false]);
    exit;
}

/* PAGINATION */
$limit = 10;
$page  = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
$start = ($page-1)*$limit;

/* COUNT */
$count = $conn->query("SELECT COUNT(*) as total FROM attendance");
$total_records = $count->fetch_assoc()['total'];

/* FETCH with additional prisoner info */
$stmt = $conn->prepare("
    SELECT a.prison_ID, a.prison_fname, a.prison_mname, a.Date, a.Attendance,
           p.criminal_severity, p.prison_lname
    FROM attendance a
    LEFT JOIN prisoner p ON a.prison_ID = p.prison_ID
    ORDER BY a.Date DESC
    LIMIT ?, ?
");
$stmt->bind_param("ii",$start,$limit);
$stmt->execute();
$result = $stmt->get_result();

$total_pages = ceil($total_records / $limit);

/* Get statistics for dashboard */
$stats_query = $conn->query("
    SELECT 
        SUM(CASE WHEN Attendance = 'Present' THEN 1 ELSE 0 END) as present,
        SUM(CASE WHEN Attendance = 'Absent' THEN 1 ELSE 0 END) as absent,
        SUM(CASE WHEN Attendance = 'Permission' THEN 1 ELSE 0 END) as permission,
        COUNT(*) as total
    FROM attendance
");
$stats = $stats_query->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Attendance Management | Prison System</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
<script src="js/jquery.js"></script>

<style>
body {
    background: #f0f2f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.page-box {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-top: 30px;
    margin-bottom: 30px;
}

.top-bar {
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.editable:hover {
    background: #fff3cd !important;
    cursor: pointer;
    transition: all 0.2s ease;
}

/* Attendance Badge Styles */
.attendance-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    min-width: 85px;
    text-align: center;
}

.badge-present {
    background-color: #28a745;
    color: white;
}

.badge-absent {
    background-color: #dc3545;
    color: white;
}

.badge-permission {
    background-color: #ffc107;
    color: #333;
}

/* Severity Badge */
.severity-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: bold;
    margin-left: 8px;
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

/* Stats Cards */
.stats-container {
    margin-bottom: 30px;
}

.stat-card {
    text-align: center;
    padding: 20px;
    border-radius: 12px;
    transition: transform 0.2s;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card.present {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.stat-card.absent {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.stat-card.permission {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: #333;
}

.stat-card.total {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.stat-number {
    font-size: 32px;
    font-weight: bold;
}

.stat-label {
    font-size: 14px;
    margin-top: 8px;
    opacity: 0.9;
}

.stat-icon {
    font-size: 28px;
    margin-bottom: 10px;
}

/* Table Styling */
.table-attendance {
    font-size: 14px;
}

.table-attendance th {
    background-color: #343a40;
    color: white;
    text-align: center;
    vertical-align: middle;
    padding: 12px;
}

.table-attendance td {
    vertical-align: middle;
    text-align: center;
}

.table-attendance tbody tr:hover {
    background-color: #f8f9fa;
}

/* Dropdown styling */
.attendance-select {
    padding: 5px 10px;
    border-radius: 8px;
    border: 2px solid #dee2e6;
    background: white;
    cursor: pointer;
}

.attendance-select:focus {
    outline: none;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

/* Delete button */
.delete-btn {
    transition: all 0.2s;
}

.delete-btn:hover {
    transform: scale(1.05);
}

/* Pagination */
.pagination {
    margin-top: 20px;
    justify-content: center;
}

.page-link {
    color: #343a40;
}

.page-item.active .page-link {
    background-color: #343a40;
    border-color: #343a40;
}

/* Filter section */
.filter-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .page-box {
        padding: 15px;
    }
    .stat-number {
        font-size: 24px;
    }
    .table-attendance {
        font-size: 12px;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="page-box">

        <!-- Header with Return Button -->
        <div class="top-bar clearfix">
            <a href="index3.php" class="btn btn-primary pull-left">
                <i class="fa fa-arrow-left"></i> Return to Dashboard
            </a>
            <h3 class="text-center" style="margin:0;">
                <i class="fa fa-calendar-check-o"></i> 
                Attendance Management
            </h3>
            <div class="text-center text-muted small mt-2">
                <i class="fa fa-pencil-square-o"></i> Click on any value to edit | 
                <i class="fa fa-trash-o"></i> Delete to remove record
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card present">
                        <div class="stat-icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['present'] ?? 0; ?></div>
                        <div class="stat-label">Present</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card absent">
                        <div class="stat-icon">
                            <i class="fa fa-times-circle"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['absent'] ?? 0; ?></div>
                        <div class="stat-label">Absent</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card permission">
                        <div class="stat-icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['permission'] ?? 0; ?></div>
                        <div class="stat-label">Permission</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card total">
                        <div class="stat-icon">
                            <i class="fa fa-database"></i>
                        </div>
                        <div class="stat-number"><?php echo $total_records; ?></div>
                        <div class="stat-label">Total Records</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row">
                <div class="col-md-4">
                    <label><i class="fa fa-filter"></i> Filter by Status:</label>
                    <select id="statusFilter" class="form-control">
                        <option value="all">All Records</option>
                        <option value="Present">Present Only</option>
                        <option value="Absent">Absent Only</option>
                        <option value="Permission">Permission Only</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label><i class="fa fa-search"></i> Search Prisoner:</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Name or ID...">
                </div>
                <div class="col-md-4">
                    <label><i class="fa fa-print"></i> Actions:</label>
                    <button onclick="window.print();" class="btn btn-info form-control">
                        <i class="fa fa-print"></i> Print Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Total Records Info -->
        <div class="alert alert-info" style="border-radius: 10px;">
            <i class="fa fa-info-circle"></i> 
            <strong>Total Records:</strong> <?php echo $total_records; ?> attendance entries
            <span class="pull-right">
                <i class="fa fa-clock-o"></i> Last updated: <?php echo date('Y-m-d H:i:s'); ?>
            </span>
        </div>

        <!-- Attendance Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-attendance" id="attendanceTable">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Full Name</th>
                        <th width="15%">Attendance Status</th>
                        <th width="15%">Date</th>
                        <th width="10%">Severity</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row=$result->fetch_assoc()): ?>
                    <tr data-status="<?= htmlspecialchars($row['Attendance']) ?>" 
                        data-name="<?= strtolower(htmlspecialchars($row['prison_fname'] . ' ' . $row['prison_lname'])) ?>"
                        data-id="<?= htmlspecialchars($row['prison_ID']) ?>">
                        
                        <td class="text-center">
                            <strong><?= htmlspecialchars($row['prison_ID']) ?></strong>
                        </td>
                        
                        <td class="editable"
                            data-field="prison_fname"
                            data-id="<?= htmlspecialchars($row['prison_ID']) ?>"
                            data-date="<?= htmlspecialchars($row['Date']) ?>">
                            <?= htmlspecialchars($row['prison_fname'] . ' ' . $row['prison_lname']) ?>
                            <small class="text-muted">(<?= htmlspecialchars($row['prison_mname']) ?>)</small>
                        </td>
                        
                        <td class="editable attendance-cell"
                            data-field="Attendance"
                            data-id="<?= htmlspecialchars($row['prison_ID']) ?>"
                            data-date="<?= htmlspecialchars($row['Date']) ?>">
                            <?php 
                            $badge_class = '';
                            $icon = '';
                            if($row['Attendance'] == 'Present') {
                                $badge_class = 'badge-present';
                                $icon = 'fa-check-circle';
                            } elseif($row['Attendance'] == 'Absent') {
                                $badge_class = 'badge-absent';
                                $icon = 'fa-times-circle';
                            } else {
                                $badge_class = 'badge-permission';
                                $icon = 'fa-clock-o';
                            }
                            ?>
                            <span class="attendance-badge <?php echo $badge_class; ?>">
                                <i class="fa <?php echo $icon; ?>"></i> <?= htmlspecialchars($row['Attendance']) ?>
                            </span>
                        </td>
                        
                        <td class="editable"
                            data-field="Date"
                            data-id="<?= htmlspecialchars($row['prison_ID']) ?>"
                            data-date="<?= htmlspecialchars($row['Date']) ?>">
                            <i class="fa fa-calendar"></i> <?= date('M d, Y', strtotime($row['Date'])) ?>
                        </td>
                        
                        <td class="text-center">
                            <?php if(isset($row['criminal_severity']) && $row['criminal_severity']): ?>
                                <?php 
                                $sev_class = '';
                                if($row['criminal_severity'] == 'High') $sev_class = 'severity-high';
                                elseif($row['criminal_severity'] == 'Medium') $sev_class = 'severity-medium';
                                elseif($row['criminal_severity'] == 'Low') $sev_class = 'severity-low';
                                ?>
                                <span class="severity-badge <?php echo $sev_class; ?>">
                                    <?= htmlspecialchars($row['criminal_severity']); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm delete-btn"
                                data-id="<?= htmlspecialchars($row['prison_ID']) ?>"
                                data-date="<?= htmlspecialchars($row['Date']) ?>"
                                data-attendance="<?= htmlspecialchars($row['Attendance']) ?>">
                                <i class="fa fa-trash-o"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <nav>
            <ul class="pagination">
                <li class="page-item <?= ($page==1)?'disabled':'' ?>">
                    <a class="page-link" href="?page=<?= $page-1 ?>">Previous</a>
                </li>
                <?php for($i=1;$i<=$total_pages;$i++): ?>
                    <li class="page-item <?= ($page==$i)?'active':'' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page==$total_pages)?'disabled':'' ?>">
                    <a class="page-link" href="?page=<?= $page+1 ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

        <!-- Legend -->
        <div class="alert alert-light" style="margin-top: 20px;">
            <strong><i class="fa fa-info-circle"></i> Legend:</strong>
            <span class="attendance-badge badge-present" style="margin-left: 10px;">
                <i class="fa fa-check-circle"></i> Present
            </span>
            <span class="attendance-badge badge-absent" style="margin-left: 5px;">
                <i class="fa fa-times-circle"></i> Absent
            </span>
            <span class="attendance-badge badge-permission" style="margin-left: 5px;">
                <i class="fa fa-clock-o"></i> Permission
            </span>
            <span class="pull-right text-muted">
                <i class="fa fa-pencil"></i> Click on Attendance status to change
            </span>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    
    // Editable fields handler with Permission option
    $('.editable').click(function() {
        if($(this).find('input,select').length) return;

        let cell = $(this);
        let oldVal = cell.text().trim();
        let field = cell.data('field');
        let prison_ID = cell.data('id');
        let oldDate = cell.data('date');

        // Handle Attendance field with 3 options
        if(field === 'Attendance'){
            let dropdown = `
                <select class="form-control attendance-select">
                    <option value="Present" ${oldVal.includes('Present') ? 'selected' : ''}>
                        <i class="fa fa-check-circle"></i> Present
                    </option>
                    <option value="Absent" ${oldVal.includes('Absent') ? 'selected' : ''}>
                        <i class="fa fa-times-circle"></i> Absent
                    </option>
                    <option value="Permission" ${oldVal.includes('Permission') ? 'selected' : ''}>
                        <i class="fa fa-clock-o"></i> Permission
                    </option>
                </select>
            `;
            cell.html(dropdown);
            cell.find('select').focus().change(saveData).blur(saveData);
        } 
        else if(field === 'Date'){
            cell.html('<input type="date" value="' + oldVal + '" class="form-control">');
            cell.find('input').focus().blur(saveData);
        }
        else {
            cell.html('<input type="text" value="' + oldVal.replace(/\([^)]*\)/g, '').trim() + '" class="form-control">');
            cell.find('input').focus().blur(saveData);
        }

        function saveData() {
            let newVal = cell.find('input,select').val();
            
            // Show loading indicator
            let originalHtml = cell.html();
            cell.html('<em style="color:#666;">Saving...</em>');
            
            $.post('', {
                action: 'update_attendance',
                prison_ID: prison_ID,
                oldDate: oldDate,
                field: field,
                value: newVal
            }, function(res) {
                let r = JSON.parse(res);
                if(r.success) {
                    // Update cell with badge for attendance field
                    if(field === 'Attendance') {
                        let badgeClass = '';
                        let icon = '';
                        if(newVal === 'Present') {
                            badgeClass = 'badge-present';
                            icon = 'fa-check-circle';
                        } else if(newVal === 'Absent') {
                            badgeClass = 'badge-absent';
                            icon = 'fa-times-circle';
                        } else {
                            badgeClass = 'badge-permission';
                            icon = 'fa-clock-o';
                        }
                        cell.html(`
                            <span class="attendance-badge ${badgeClass}">
                                <i class="fa ${icon}"></i> ${newVal}
                            </span>
                        `);
                        // Update row data-status for filtering
                        cell.closest('tr').attr('data-status', newVal);
                    } 
                    else if(field === 'Date') {
                        let formattedDate = new Date(newVal);
                        let options = { year: 'numeric', month: 'short', day: 'numeric' };
                        cell.html('<i class="fa fa-calendar"></i> ' + formattedDate.toLocaleDateString('en-US', options));
                    }
                    else {
                        cell.html(newVal);
                    }
                    
                    // Show success feedback
                    cell.css('background-color', '#d4edda');
                    setTimeout(() => cell.css('background-color', ''), 500);
                } else {
                    alert('❌ Update Failed. Please try again.');
                    cell.html(originalHtml);
                }
            }).fail(function() {
                alert('❌ Error connecting to server.');
                cell.html(originalHtml);
            });
        }
    });

    // Delete attendance record
    $('.delete-btn').click(function() {
        if(!confirm('⚠️ Are you sure you want to delete this attendance record?')) return;

        let btn = $(this);
        let row = btn.closest('tr');
        
        $.get('', {
            delete: 1,
            prison_ID: btn.data('id'),
            Date: btn.data('date'),
            Attendance: btn.data('attendance')
        }, function() {
            row.fadeOut(300, function() {
                $(this).remove();
                // Update statistics (simple page reload or dynamic update)
                location.reload();
            });
        }).fail(function() {
            alert('❌ Delete failed. Please try again.');
        });
    });

    // Filter by status
    $('#statusFilter').change(function() {
        let filter = $(this).val();
        $('#attendanceTable tbody tr').each(function() {
            if(filter === 'all') {
                $(this).show();
            } else {
                let status = $(this).data('status');
                if(status === filter) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        let searchTerm = $(this).val().toLowerCase();
        $('#attendanceTable tbody tr').each(function() {
            let name = $(this).data('name') || '';
            let id = $(this).data('id') || '';
            if(name.includes(searchTerm) || id.toString().includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>