<?php
include('DB.php'); // $conn = mysqli connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ---------------------------------------
   Sanitize Input
--------------------------------------- */
function clean($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

$message = "";

/* ---------------------------------------
   UPDATE PRISONER
--------------------------------------- */
if (isset($_POST['update'])) {

    $prison_ID        = clean($_POST['prison_ID']);
    $prison_fname     = clean($_POST['prison_fname']);
    $prison_mname     = clean($_POST['prison_mname']);
    $prison_lname     = clean($_POST['prison_lname']);
    $prison_age       = clean($_POST['prison_age']);
    $prison_gen       = clean($_POST['prison_gen']);
    $prison_add       = clean($_POST['prison_add']);
    $prison_cont      = clean($_POST['prison_cont']);
    $previews_record  = clean($_POST['previews_record']);
    $criminal_record  = clean($_POST['criminal_record']);
    $criminal_severity = clean($_POST['criminal_severity']);
    $prison_stat      = clean($_POST['prison_stat']);
    $Prison_Date      = clean($_POST['Prison_Date']);
    $end_date         = clean($_POST['end_date']);

    $stmt = $conn->prepare("
        UPDATE prisoner SET
            prison_fname=?,
            prison_mname=?,
            prison_lname=?,
            prison_age=?,
            prison_gen=?,
            prison_add=?,
            prison_cont=?,
            previews_record=?,
            criminal_record=?,
            criminal_severity=?,
            prison_stat=?,
            Prison_Date=?,
            end_date=?
        WHERE prison_ID=?
    ");

    $stmt->bind_param(
        "sssisssssssssi",
        $prison_fname,
        $prison_mname,
        $prison_lname,
        $prison_age,
        $prison_gen,
        $prison_add,
        $prison_cont,
        $previews_record,
        $criminal_record,
        $criminal_severity,
        $prison_stat,
        $Prison_Date,
        $end_date,
        $prison_ID
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('Prisoner updated successfully!');
                window.location='updt.php';
              </script>";
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Update Failed: " . htmlspecialchars($stmt->error) . "</div>";
    }

    $stmt->close();
}

/* ---------------------------------------
   FETCH PRISONER FOR DISPLAY
--------------------------------------- */
$row = null;

if (isset($_POST['display'])) {

    $prison_ID = clean($_POST['prison_ID']);

    $stmt = $conn->prepare("SELECT * FROM prisoner WHERE prison_ID = ?");
    $stmt->bind_param("i", $prison_ID);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>Prisoner not found.</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Prisoner - Criminal Record Management</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        .criminal-record-section {
            background-color: #fef9e6;
            border-left: 4px solid #d9534f;
            padding: 15px;
            border-radius: 5px;
        }
        
        .severity-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
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
        
        .character-count {
            font-size: 11px;
            text-align: right;
            margin-top: 5px;
            color: #999;
        }
        
        .character-count.warning {
            color: #f0ad4e;
        }
        
        .character-count.danger {
            color: #d9534f;
        }
        
        .help-text {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        
        .criminal-icon {
            color: #d9534f;
            margin-right: 5px;
        }
        
        textarea {
            resize: vertical;
            font-family: monospace;
        }
        
        .label-icon {
            margin-right: 5px;
        }
        
        .form-group label {
            font-weight: 600;
        }
        
        .severity-select {
            margin-top: 10px;
        }
        
        .severity-select label {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container" style="margin-top:30px; max-width:900px;">

    <a href="index3.php" class="btn btn-primary" style="margin-bottom:20px;">
        <i class="fa fa-home"></i> Return Home
    </a>

    <h2>
        <i class="fa fa-edit"></i> Update Prisoner Information
    </h2>
    <hr>

    <?= $message ?>

    <!-- Search Form -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-search"></i> Find Prisoner</h3>
        </div>
        <div class="panel-body">
            <form method="POST" class="form-inline">
                <div class="form-group">
                    <label>Enter Prison ID:</label>
                    <input type="number" name="prison_ID" class="form-control" required>
                </div>
                <button type="submit" name="display" class="btn btn-info">
                    <i class="fa fa-search"></i> Fetch Prisoner
                </button>
            </form>
        </div>
    </div>

    <?php if ($row): ?>
    <!-- Update Form -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="fa fa-user"></i> Edit Prisoner Details
                <span class="label label-primary" style="margin-left: 10px;">ID: <?= htmlspecialchars($row['prison_ID']) ?></span>
            </h3>
        </div>
        <div class="panel-body">
            <form method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="prison_ID" value="<?= htmlspecialchars($row['prison_ID']) ?>">
                
                <div class="form-group">
                    <label><i class="fa fa-user label-icon"></i> First Name</label>
                    <input type="text" name="prison_fname" class="form-control" value="<?= htmlspecialchars($row['prison_fname']) ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-user label-icon"></i> Middle Name</label>
                    <input type="text" name="prison_mname" class="form-control" value="<?= htmlspecialchars($row['prison_mname']) ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-user label-icon"></i> Last Name</label>
                    <input type="text" name="prison_lname" class="form-control" value="<?= htmlspecialchars($row['prison_lname']) ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-calendar label-icon"></i> Age</label>
                    <input type="number" name="prison_age" class="form-control" value="<?= htmlspecialchars($row['prison_age']) ?>" required min="18" max="120">
                </div>

                <div class="form-group">
                    <label><i class="fa fa-venus-mars label-icon"></i> Gender</label>
                    <select name="prison_gen" class="form-control" required>
                        <option value="<?= htmlspecialchars($row['prison_gen']) ?>" selected><?= htmlspecialchars($row['prison_gen']) ?></option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-map-marker label-icon"></i> Address</label>
                    <input type="text" name="prison_add" class="form-control" value="<?= htmlspecialchars($row['prison_add']) ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-phone label-icon"></i> Contact</label>
                    <input type="text" name="prison_cont" class="form-control" value="<?= htmlspecialchars($row['prison_cont']) ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-history label-icon"></i> Previous Record</label>
                    <input type="text" name="previews_record" class="form-control" value="<?= htmlspecialchars($row['previews_record']) ?>" required>
                </div>

                <!-- Criminal Record Section -->
                <div class="form-group">
                    <div class="criminal-record-section">
                        <label><i class="fa fa-gavel criminal-icon"></i> Detailed Criminal Record <span class="text-danger">*</span></label>
                        <textarea name="criminal_record" id="criminal_record" class="form-control" rows="6" 
                            placeholder="Enter detailed criminal record information including:
- Type of offense(s)
- Conviction date(s)
- Sentence length
- Parole eligibility
- Prior convictions
- Case numbers
- Court decisions
- Any additional relevant details" 
                            required onkeyup="updateCharacterCount(); analyzeSeverity();"><?= htmlspecialchars(isset($row['criminal_record']) ? $row['criminal_record'] : '') ?></textarea>
                        
                        <div class="character-count" id="charCount">
                            <i class="fa fa-keyboard-o"></i> <span id="charCountValue">0</span> characters
                        </div>
                        
                        <!-- Severity Selection Dropdown -->
                        <div class="severity-select">
                            <label><i class="fa fa-exclamation-triangle"></i> Severity Level <span class="text-danger">*</span></label>
                            <select name="criminal_severity" id="criminal_severity" class="form-control" required onchange="updateSeverityDisplay()">
                                <option value="">Select Severity</option>
                                <option value="Low" <?php echo (isset($row['criminal_severity']) && $row['criminal_severity'] == 'Low') ? 'selected' : ''; ?>>🔵 Low - Minor offenses</option>
                                <option value="Medium" <?php echo (isset($row['criminal_severity']) && $row['criminal_severity'] == 'Medium') ? 'selected' : ''; ?>>🟡 Medium - Moderate offenses</option>
                                <option value="High" <?php echo (isset($row['criminal_severity']) && $row['criminal_severity'] == 'High') ? 'selected' : ''; ?>>🔴 High - Severe offenses</option>
                            </select>
                        </div>
                        
                        <div id="severityIndicator" class="severity-badge" style="display: none;"></div>
                        
                        <div class="help-text">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Note:</strong> Include as much detail as possible. This information is critical for legal records and parole considerations.
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-heart label-icon"></i> Marital Status</label>
                    <select name="prison_stat" class="form-control" required>
                        <option value="<?= htmlspecialchars($row['prison_stat']) ?>" selected><?= htmlspecialchars($row['prison_stat']) ?></option>
                        <option>Single</option>
                        <option>Married</option>
                        <option>Divorced</option>
                        <option>Widowed</option>
                        <option>Separated</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-calendar-plus label-icon"></i> Prison Date</label>
                    <input type="date" name="Prison_Date" class="form-control" value="<?= htmlspecialchars($row['Prison_Date']) ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-calendar-minus label-icon"></i> End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($row['end_date']) ?>" required>
                </div>

                <button type="submit" name="update" class="btn btn-success btn-block">
                    <i class="fa fa-save"></i> Update Prisoner
                </button>
                <a href="updt.php" class="btn btn-default btn-block" style="margin-top: 10px;">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </form>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
// Function to analyze criminal record severity based on content
function analyzeSeverity() {
    var record = document.getElementById('criminal_record').value.toLowerCase();
    var severityDiv = document.getElementById('severityIndicator');
    var severitySelect = document.getElementById('criminal_severity');
    
    if (record.length === 0) {
        severityDiv.style.display = 'none';
        return;
    }
    
    // Check for high severity crimes
    if (record.includes('murder') || record.includes('homicide') || 
        record.includes('rape') || record.includes('kidnapping') || 
        record.includes('terrorism') || record.includes('manslaughter') ||
        record.includes('genocide') || record.includes('torture') ||
        record.includes('attempted murder') || record.includes('armed robbery') ||
        record.includes('aggravated assault')) {
        severityDiv.innerHTML = '<i class="fa fa-exclamation-triangle"></i> HIGH SEVERITY DETECTED - This appears to be a serious violent offense';
        severityDiv.className = 'severity-badge severity-high';
        severityDiv.style.display = 'inline-block';
        if (severitySelect.value === "") severitySelect.value = "High";
    }
    // Check for medium severity crimes
    else if (record.includes('assault') || record.includes('robbery') || 
             record.includes('theft') || record.includes('burglary') || 
             record.includes('fraud') || record.includes('drug') || 
             record.includes('embezzlement') || record.includes('forgery') ||
             record.includes('battery') || record.includes('larceny') ||
             record.includes('grand theft')) {
        severityDiv.innerHTML = '<i class="fa fa-exclamation-triangle"></i> MEDIUM SEVERITY DETECTED - This appears to be a moderate offense';
        severityDiv.className = 'severity-badge severity-medium';
        severityDiv.style.display = 'inline-block';
        if (severitySelect.value === "") severitySelect.value = "Medium";
    }
    // Low severity crimes
    else if (record.length > 10) {
        severityDiv.innerHTML = '<i class="fa fa-info-circle"></i> LOW SEVERITY DETECTED - This appears to be a minor offense';
        severityDiv.className = 'severity-badge severity-low';
        severityDiv.style.display = 'inline-block';
        if (severitySelect.value === "") severitySelect.value = "Low";
    }
}

// Update severity display based on dropdown selection
function updateSeverityDisplay() {
    var severitySelect = document.getElementById('criminal_severity');
    var severityDiv = document.getElementById('severityIndicator');
    var val = severitySelect.value;
    
    if (val === "Low") {
        severityDiv.innerHTML = '<i class="fa fa-info-circle"></i> LOW SEVERITY - Minor offense';
        severityDiv.className = 'severity-badge severity-low';
        severityDiv.style.display = 'inline-block';
    } else if (val === "Medium") {
        severityDiv.innerHTML = '<i class="fa fa-exclamation-triangle"></i> MEDIUM SEVERITY - Moderate offense';
        severityDiv.className = 'severity-badge severity-medium';
        severityDiv.style.display = 'inline-block';
    } else if (val === "High") {
        severityDiv.innerHTML = '<i class="fa fa-exclamation-triangle"></i> HIGH SEVERITY - Serious violent offense';
        severityDiv.className = 'severity-badge severity-high';
        severityDiv.style.display = 'inline-block';
    } else {
        severityDiv.style.display = 'none';
    }
}

// Update character count
function updateCharacterCount() {
    var textarea = document.getElementById('criminal_record');
    var count = textarea.value.length;
    var countSpan = document.getElementById('charCountValue');
    var charDiv = document.getElementById('charCount');
    
    countSpan.innerHTML = count;
    
    if (count > 500) {
        charDiv.className = 'character-count danger';
    } else if (count > 300) {
        charDiv.className = 'character-count warning';
    } else {
        charDiv.className = 'character-count';
    }
}

// Form validation function
function validateForm() {
    var criminalRecord = document.getElementById('criminal_record');
    var severitySelect = document.getElementById('criminal_severity');
    
    if (criminalRecord.value.trim() === '') {
        alert('Please enter the criminal record information. This field is required.');
        criminalRecord.focus();
        return false;
    }
    
    if (criminalRecord.value.trim().length < 10) {
        alert('Please provide more detailed criminal record information (at least 10 characters).');
        criminalRecord.focus();
        return false;
    }
    
    if (severitySelect.value === '') {
        alert('Please select the criminal record severity level (Low, Medium, or High).');
        severitySelect.focus();
        return false;
    }
    
    return true;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCharacterCount();
    analyzeSeverity();
    updateSeverityDisplay();
    
    // Add event listener for severity dropdown
    var severitySelect = document.getElementById('criminal_severity');
    if (severitySelect) {
        severitySelect.addEventListener('change', updateSeverityDisplay);
    }
});
</script>

</body>
</html>