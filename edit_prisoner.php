<?php
include('DB.php'); // Your mysqli connection in $conn

// Fetch prisoner info
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $prison_ID = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM prisoner WHERE prison_ID = ?");
    $stmt->bind_param("i", $prison_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Invalid prisoner ID");
}

$message = '';

if (isset($_POST['submit'])) {
    // Validate fields
    $required = ['prison_ID', 'prison_fname', 'prison_lname', 'prison_mname', 'prison_age', 'prison_gen', 'prison_add', 'prison_cont', 'previews_record', 'criminal_record', 'criminal_severity', 'prison_stat', 'da', 'end'];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $message = "<div class='alert alert-danger'>All fields are required. Please complete the form including Criminal Record Severity.</div>";
            break;
        }
    }

    if (empty($message)) {
        $prison_ID        = $_POST['prison_ID'];
        $prison_fname     = $_POST['prison_fname'];
        $prison_mname     = $_POST['prison_mname'];
        $prison_lname     = $_POST['prison_lname'];
        $prison_age       = $_POST['prison_age'];
        $prison_gen       = $_POST['prison_gen'];
        $prison_add       = $_POST['prison_add'];
        $prison_cont      = $_POST['prison_cont'];
        $previews_record  = $_POST['previews_record'];
        $criminal_record  = $_POST['criminal_record'];
        $criminal_severity = $_POST['criminal_severity'];
        $prison_stat      = $_POST['prison_stat'];
        $da               = $_POST['da'];
        $end              = $_POST['end'];

        // Prepared statement to update prisoner info with criminal record and severity
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
            $da,
            $end,
            $prison_ID
        );

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Prisoner information updated successfully!</div>";
            // Refresh the data
            $stmt2 = $conn->prepare("SELECT * FROM prisoner WHERE prison_ID = ?");
            $stmt2->bind_param("i", $prison_ID);
            $stmt2->execute();
            $result = $stmt2->get_result();
            $row = $result->fetch_assoc();
            $stmt2->close();
        } else {
            $message = "<div class='alert alert-danger'>Error updating prisoner: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Prisoner - Woliso Prison Management System</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
    /* Criminal record styling */
    .criminal-record-section {
        background-color: #fef9e6;
        border-left: 4px solid #d9534f;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    
    .criminal-record-section label {
        font-weight: bold;
        color: #d9534f;
    }
    
    .criminal-record-section textarea {
        font-family: monospace;
        background-color: #fffef7;
    }
    
    .severity-indicator {
        margin-top: 10px;
        padding: 8px;
        border-radius: 4px;
        display: none;
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
    
    .badge-criminal {
        background-color: #d9534f;
        margin-left: 10px;
    }
    
    .form-group label i {
        margin-right: 5px;
        color: #337ab7;
    }
    
    .help-text {
        font-size: 11px;
        color: #666;
        margin-top: 5px;
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
    
    .criminal-icon {
        margin-right: 8px;
    }
    
    .severity-select {
        margin-top: 10px;
    }
</style>
</head>
<body>

<div class="container" style="margin-top:30px;">

    <!-- Return Home Button -->
    <a href="index3.php" class="btn btn-primary" style="margin-bottom:20px;">
        <i class="fa fa-home"></i> Return to Home
    </a>
    
    <a href="update4.php" class="btn btn-default" style="margin-bottom:20px;">
        <i class="fa fa-arrow-left"></i> Back to Prisoner List
    </a>

    <?php if(!empty($message)) echo $message; ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="fa fa-edit"></i> Edit Prisoner Information
                <span class="badge badge-criminal">ID: <?php echo $row['prison_ID']; ?></span>
            </h3>
        </div>
        <div class="panel-body">
            <form method="post" class="form-horizontal" onsubmit="return validateForm()">

                <div class="form-group">
                    <label class="col-md-2 control-label">
                        <i class="fa fa-hashtag"></i> Prisoner ID
                    </label>
                    <div class="col-md-2">
                        <input type="number" name="prison_ID" class="form-control" value="<?php echo htmlspecialchars($row['prison_ID']); ?>" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">
                        <i class="fa fa-user"></i> Full Name
                    </label>
                    <div class="col-md-2">
                        <input type="text" name="prison_fname" class="form-control" value="<?php echo htmlspecialchars($row['prison_fname']); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="prison_mname" class="form-control" value="<?php echo htmlspecialchars($row['prison_mname']); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="prison_lname" class="form-control" value="<?php echo htmlspecialchars($row['prison_lname']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">
                        <i class="fa fa-calendar"></i> Age
                    </label>
                    <div class="col-md-2">
                        <input type="number" name="prison_age" class="form-control" value="<?php echo $row['prison_age']; ?>" required min="18" max="120">
                    </div>
                    <label class="col-md-2 control-label">
                        <i class="fa fa-venus-mars"></i> Gender
                    </label>
                    <div class="col-md-2">
                        <select name="prison_gen" class="form-control" required>
                            <option value="<?php echo htmlspecialchars($row['prison_gen']); ?>" selected><?php echo htmlspecialchars($row['prison_gen']); ?></option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">
                        <i class="fa fa-map-marker"></i> Address
                    </label>
                    <div class="col-md-6">
                        <input type="text" name="prison_add" class="form-control" value="<?php echo htmlspecialchars($row['prison_add']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">
                        <i class="fa fa-phone"></i> Contact
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="prison_cont" class="form-control" value="<?php echo $row['prison_cont']; ?>" required>
                    </div>

                    <label class="col-md-2 control-label">
                        <i class="fa fa-history"></i> Previous Record
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="previews_record" class="form-control" value="<?php echo htmlspecialchars($row['previews_record']); ?>" required>
                    </div>
                </div>

                <!-- Criminal Record Field -->
                <div class="form-group">
                    <label class="col-md-2 control-label">
                        <i class="fa fa-gavel criminal-icon"></i> Criminal Record
                    </label>
                    <div class="col-md-8">
                        <div class="criminal-record-section">
                            
                            <!-- Criminal Record Textarea -->
                            <label><i class="fa fa-file-text"></i> Detailed Criminal Record:</label>
                            <textarea name="criminal_record" id="criminal_record" class="form-control" rows="6" 
                                placeholder="Enter detailed criminal record information including:
- Type of offense(s)
- Conviction date(s)
- Sentence length
- Parole eligibility
- Prior convictions
- Case numbers
- Any additional relevant details" 
                                required onkeyup="updateCharacterCount(); analyzeSeverity();"><?php echo htmlspecialchars(isset($row['criminal_record']) ? $row['criminal_record'] : ''); ?></textarea>
                            
                            <!-- Severity Selection Dropdown -->
                            <div class="severity-select">
                                <label><i class="fa fa-exclamation-triangle"></i> Severity Level:</label>
                                <select name="criminal_severity" id="criminal_severity" class="form-control" required onchange="updateSeverityDisplay()">
                                    <option value="">Select Severity</option>
                                    <option value="Low" <?php echo (isset($row['criminal_severity']) && $row['criminal_severity'] == 'Low') ? 'selected' : ''; ?>>🔵 Low - Minor offenses</option>
                                    <option value="Medium" <?php echo (isset($row['criminal_severity']) && $row['criminal_severity'] == 'Medium') ? 'selected' : ''; ?>>🟡 Medium - Moderate offenses</option>
                                    <option value="High" <?php echo (isset($row['criminal_severity']) && $row['criminal_severity'] == 'High') ? 'selected' : ''; ?>>🔴 High - Severe offenses</option>
                                </select>
                            </div>
                            
                            <div class="character-count" id="charCount">
                                <i class="fa fa-keyboard-o"></i> <span id="charCountValue">0</span> characters
                            </div>
                            
                            <div class="severity-indicator" id="severityIndicator"></div>
                            
                            <div class="help-text">
                                <i class="fa fa-info-circle"></i> 
                                <strong>Note:</strong> Include as much detail as possible. This information is critical for legal records and parole considerations.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">
                        <i class="fa fa-heart"></i> Marital Status
                    </label>
                    <div class="col-md-3">
                        <select name="prison_stat" class="form-control" required>
                            <option value="<?php echo htmlspecialchars($row['prison_stat']); ?>" selected><?php echo htmlspecialchars($row['prison_stat']); ?></option>
                            <option>Single</option>
                            <option>Married</option>
                            <option>Divorced</option>
                            <option>Widowed</option>
                            <option>Separated</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">
                        <i class="fa fa-calendar-plus"></i> Entering Date
                    </label>
                    <div class="col-md-2">
                        <input type="date" name="da" class="form-control" value="<?php echo $row['Prison_Date']; ?>" required>
                    </div>

                    <label class="col-md-2 control-label">
                        <i class="fa fa-calendar-minus"></i> Releasing Date
                    </label>
                    <div class="col-md-2">
                        <input type="date" name="end" class="form-control" value="<?php echo $row['end_date']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Prisoner
                        </button>
                        <a href="update4.php" class="btn btn-default">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
// Function to analyze criminal record severity
function analyzeSeverity() {
    var record = document.getElementById('criminal_record').value.toLowerCase();
    var severityDiv = document.getElementById('severityIndicator');
    var severitySelect = document.getElementById('criminal_severity');
    
    if (record.length === 0) {
        severityDiv.style.display = 'none';
        return;
    }
    
    severityDiv.style.display = 'block';
    
    // Check for high severity crimes
    if (record.includes('murder') || record.includes('homicide') || 
        record.includes('rape') || record.includes('kidnapping') || 
        record.includes('terrorism') || record.includes('manslaughter') ||
        record.includes('genocide') || record.includes('torture') ||
        record.includes('armed robbery') || record.includes('aggravated assault')) {
        severityDiv.innerHTML = '<i class="fa fa-exclamation-triangle"></i> <strong>HIGH SEVERITY</strong> - This crime is classified as a serious violent offense.';
        severityDiv.className = 'severity-indicator severity-high';
        if (severitySelect.value === "") severitySelect.value = "High";
    }
    // Check for medium severity crimes
    else if (record.includes('assault') || record.includes('robbery') || 
             record.includes('theft') || record.includes('burglary') || 
             record.includes('fraud') || record.includes('drug') || 
             record.includes('embezzlement') || record.includes('forgery') ||
             record.includes('battery') || record.includes('grand theft')) {
        severityDiv.innerHTML = '<i class="fa fa-exclamation-triangle"></i> <strong>MEDIUM SEVERITY</strong> - This crime is classified as a moderate offense.';
        severityDiv.className = 'severity-indicator severity-medium';
        if (severitySelect.value === "") severitySelect.value = "Medium";
    }
    // Low severity crimes
    else if (record.length > 10) {
        severityDiv.innerHTML = '<i class="fa fa-info-circle"></i> <strong>LOW SEVERITY</strong> - Minor offense.';
        severityDiv.className = 'severity-indicator severity-low';
        if (severitySelect.value === "") severitySelect.value = "Low";
    }
}

// Update severity display based on dropdown
function updateSeverityDisplay() {
    var severitySelect = document.getElementById('criminal_severity');
    var severityDiv = document.getElementById('severityIndicator');
    var val = severitySelect.value;
    
    if (val === "Low") {
        severityDiv.style.display = 'block';
        severityDiv.innerHTML = '<i class="fa fa-info-circle"></i> <strong>LOW SEVERITY</strong> - Minor offense.';
        severityDiv.className = 'severity-indicator severity-low';
    } else if (val === "Medium") {
        severityDiv.style.display = 'block';
        severityDiv.innerHTML = '<i class="fa fa-exclamation-triangle"></i> <strong>MEDIUM SEVERITY</strong> - Moderate offense.';
        severityDiv.className = 'severity-indicator severity-medium';
    } else if (val === "High") {
        severityDiv.style.display = 'block';
        severityDiv.innerHTML = '<i class="fa fa-exclamation-triangle"></i> <strong>HIGH SEVERITY</strong> - Serious violent offense.';
        severityDiv.className = 'severity-indicator severity-high';
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

// Form validation
function validateForm() {
    var criminalRecord = document.getElementById('criminal_record').value.trim();
    var severity = document.getElementById('criminal_severity').value;
    
    if (criminalRecord === '') {
        alert('Please enter the criminal record information. This field is required.');
        document.getElementById('criminal_record').focus();
        return false;
    }
    
    if (criminalRecord.length < 10) {
        alert('Please provide more detailed criminal record information (at least 10 characters).');
        document.getElementById('criminal_record').focus();
        return false;
    }
    
    if (severity === '') {
        alert('Please select the criminal record severity level.');
        document.getElementById('criminal_severity').focus();
        return false;
    }
    
    return true;
}

// Initialize on page load
$(document).ready(function() {
    updateCharacterCount();
    analyzeSeverity();
    updateSeverityDisplay();
    
    // Add tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Warn before leaving if changes are unsaved
    var formChanged = false;
    $('form input, form select, form textarea').change(function() {
        formChanged = true;
    });
    
    $('a').click(function(e) {
        if (formChanged && !confirm('You have unsaved changes. Are you sure you want to leave?')) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>