<?php
// Include database connection
include('DB.php');

// Validate and sanitize ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    die("<h3 style='color:red; text-align:center;'>Invalid Prisoner ID</h3>");
}

// Modern MySQLi Query
$stmt = $conn->prepare("SELECT * FROM prisoner WHERE prison_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("<h3 style='color:red; text-align:center;'>Prisoner Record Not Found</h3>");
}

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

$criminal_record = isset($row['criminal_record']) ? $row['criminal_record'] : '';
$severity_class = getSeverityClass($criminal_record);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prisoner Profile - Criminal Record</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .btn-nav {
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40,167,69,0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-list {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .btn-list:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23,162,184,0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #333;
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,193,7,0.3);
            color: #333;
            text-decoration: none;
        }
        
        /* Profile Card */
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%);
            color: white;
            padding: 25px 30px;
            border-bottom: 3px solid #667eea;
        }
        
        .profile-header h2 {
            margin: 0;
            font-size: 28px;
        }
        
        .profile-header h2 i {
            margin-right: 10px;
        }
        
        .profile-header .badge-id {
            background: rgba(255,255,255,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            margin-left: 15px;
        }
        
        .profile-body {
            padding: 30px;
        }
        
        .info-section {
            margin-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 20px;
        }
        
        .info-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .info-section h3 {
            color: #667eea;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .info-section h3 i {
            margin-right: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
        }
        
        .info-label {
            font-weight: 600;
            width: 130px;
            color: #555;
            flex-shrink: 0;
        }
        
        .info-value {
            flex: 1;
            color: #333;
            word-break: break-word;
        }
        
        /* Criminal Record Section */
        .criminal-record-section {
            background: #fff9e6;
            border-left: 4px solid #d9534f;
            padding: 20px;
            border-radius: 10px;
            margin-top: 10px;
        }
        
        .severity-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
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
        
        .criminal-record-content {
            background: white;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
            margin-top: 15px;
            white-space: pre-wrap;
            border: 1px solid #e0e0e0;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .record-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .dot-high { background-color: #dc3545; }
        .dot-medium { background-color: #fd7e14; }
        .dot-low { background-color: #28a745; }
        .dot-none { background-color: #999; }
        
        .no-record {
            color: #999;
            font-style: italic;
        }
        
        /* Sentence Timeline */
        .timeline {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
        }
        
        .progress-bar-custom {
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 4px;
            transition: width 0.5s ease;
        }
        
        @media (max-width: 768px) {
            .info-item {
                flex-direction: column;
            }
            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }
            .profile-body {
                padding: 20px;
            }
            .nav-buttons {
                justify-content: center;
            }
        }
    </style>
</head>

<body>

<div class="profile-container">
    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="index5.php" class="btn-nav btn-home">
            <i class="fa fa-home"></i> Return to Home Page
        </a>
        <a href="Archive.php" class="btn-nav btn-list">
            <i class="fa fa-arrow-left"></i> Back to Archive List
        </a>
      
    </div>
    
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-header">
            <h2>
                <i class="fa fa-user"></i> Prisoner Profile
                <span class="badge-id">ID: <?php echo $row['prison_ID']; ?></span>
            </h2>
        </div>
        
        <div class="profile-body">
            <!-- Personal Information Section -->
            <div class="info-section">
                <h3><i class="fa fa-info-circle"></i> Personal Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fa fa-user"></i> Full Name:</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($row['prison_fname'] . ' ' . $row['prison_mname'] . ' ' . $row['prison_lname']); ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fa fa-calendar-alt"></i> Age:</div>
                        <div class="info-value"><?php echo $row['prison_age']; ?> years</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fa fa-venus-mars"></i> Gender:</div>
                        <div class="info-value"><?php echo $row['prison_gen']; ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fa fa-map-marker"></i> Address:</div>
                        <div class="info-value"><?php echo htmlspecialchars($row['prison_add']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fa fa-phone"></i> Contact Number:</div>
                        <div class="info-value"><?php echo $row['prison_cont']; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Legal Information Section -->
            <div class="info-section">
                <h3><i class="fa fa-gavel"></i> Legal Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fa fa-history"></i> Previous Record:</div>
                        <div class="info-value"><?php echo htmlspecialchars($row['previews_record']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fa fa-heart"></i> Marital Status:</div>
                        <div class="info-value"><?php echo $row['prison_stat']; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Criminal Record Section -->
            <div class="info-section">
                <h3><i class="fa fa-gavel"></i> Criminal Record</h3>
                <div class="criminal-record-section">
                    <?php if (!empty($criminal_record)): ?>
                        <div>
                            <span class="record-dot <?php echo str_replace('severity-', 'dot-', $severity_class); ?>"></span>
                            <strong>Criminal Record Details:</strong>
                            <span class="severity-badge <?php echo $severity_class; ?>">
                                <i class="fa fa-exclamation-triangle"></i>
                                <?php echo strtoupper(str_replace('severity-', '', $severity_class)); ?> SEVERITY
                            </span>
                        </div>
                        <div class="criminal-record-content">
                            <?php echo nl2br(htmlspecialchars($criminal_record)); ?>
                        </div>
                    <?php else: ?>
                        <div class="no-record">
                            <i class="fa fa-check-circle"></i> No criminal record found for this prisoner.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sentence Information Section -->
            <div class="info-section">
                <h3><i class="fa fa-calendar"></i> Sentence Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fa fa-calendar-plus"></i> Start Date:</div>
                        <div class="info-value"><?php echo date('F j, Y', strtotime($row['Prison_Date'])); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fa fa-calendar-minus"></i> End Date:</div>
                        <div class="info-value"><?php echo date('F j, Y', strtotime($row['end_date'])); ?></div>
                    </div>
                </div>
                
                <?php
                $start = new DateTime($row['Prison_Date']);
                $end = new DateTime($row['end_date']);
                $today = new DateTime();
                $total_days = $start->diff($end)->days;
                $elapsed_days = $start->diff($today)->days;
                $progress_percent = ($elapsed_days > 0 && $total_days > 0) ? min(100, ($elapsed_days / $total_days) * 100) : 0;
                $remaining_days = $today->diff($end)->days;
                ?>
                
                <div class="timeline">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="fa fa-hourglass-half"></i> Sentence Length:</div>
                            <div class="info-value">
                                <?php echo $start->diff($end)->y . ' years, ' . $start->diff($end)->m . ' months, ' . $start->diff($end)->d . ' days'; ?>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label"><i class="fa fa-clock-o"></i> Time Served:</div>
                            <div class="info-value">
                                <?php echo $start->diff($today)->y . ' years, ' . $start->diff($today)->m . ' months, ' . $start->diff($today)->d . ' days'; ?>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label"><i class="fa fa-hourglass-end"></i> Time Remaining:</div>
                            <div class="info-value">
                                <?php if ($today < $end): ?>
                                    <span class="text-warning">
                                        <?php echo $remaining_days . ' days (' . $today->diff($end)->m . ' months, ' . $today->diff($end)->d . ' days)'; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-success">Sentence Completed</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: <?php echo $progress_percent; ?>%"></div>
                    </div>
                    <div class="text-center small text-muted">
                        <?php echo round($progress_percent); ?>% of sentence completed
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>