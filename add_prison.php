<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection with error checking
if (!file_exists('DB.php')) {
    die("Error: DB.php file not found. Please create the database configuration file.");
}

include('DB.php');

// Check if connection exists
if (!isset($conn) || !$conn) {
    die("Error: Database connection failed. Please check your DB.php configuration.");
}

// Include session file if exists
if (file_exists('session.php')) {
    include('session.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Prison Management System | Add Prisoner</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- International Phone -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">

<style>
* {
    font-family: 'Inter', sans-serif;
}

body {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    min-height: 100vh;
    padding: 2rem 0;
    position: relative;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.05"><path fill="white" d="M20,20 L30,20 L25,28 Z M60,60 L70,60 L65,68 Z M40,80 L50,80 L45,88 Z M80,30 L90,30 L85,38 Z"/></svg>') repeat;
    pointer-events: none;
}

.container-custom {
    max-width: 1100px;
    margin: auto;
    padding: 0 1.5rem;
}

.form-card {
    background: rgba(255,255,255,0.98);
    border-radius: 32px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    overflow: hidden;
    backdrop-filter: blur(2px);
    transition: transform 0.3s ease;
}

.form-card:hover {
    transform: translateY(-5px);
}

.card-header-custom {
    background: linear-gradient(135deg, #0f1722 0%, #1e2a3a 100%);
    padding: 1.8rem 2.2rem;
    position: relative;
    overflow: hidden;
}

.card-header-custom::after {
    content: '⚖️';
    position: absolute;
    right: 20px;
    bottom: -10px;
    font-size: 80px;
    opacity: 0.08;
    pointer-events: none;
}

.card-header-custom h2 {
    color: #fff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 700;
    letter-spacing: -0.3px;
}

.card-header-custom h2 i {
    background: rgba(255,255,255,0.15);
    padding: 10px;
    border-radius: 18px;
}

.form-body {
    padding: 2.5rem;
}

/* Required field star */
.required-star {
    color: #dc2626;
    font-size: 1.1rem;
    margin-left: 4px;
    font-weight: 700;
}

.modern-group {
    margin-bottom: 1.6rem;
}

.modern-group label {
    font-weight: 600;
    font-size: 0.85rem;
    color: #1e293b;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modern-group label i {
    color: #3b71ca;
    width: 20px;
}

.form-control,
.form-select,
textarea.form-control {
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
    background: #fff;
}

.form-control:focus,
.form-select:focus,
textarea.form-control:focus {
    border-color: #3b71ca;
    box-shadow: 0 0 0 4px rgba(59,113,202,0.12);
    outline: none;
}

textarea.form-control {
    resize: vertical;
    min-height: 90px;
}

.btn-submit {
    background: linear-gradient(95deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    padding: 1rem 1.8rem;
    border-radius: 60px;
    color: #fff;
    width: 100%;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.btn-submit:hover {
    background: linear-gradient(95deg, #0f2a4f 0%, #1f4280 100%);
    transform: scale(1.01);
    box-shadow: 0 10px 20px -8px rgba(0,0,0,0.3);
}

.return-home-btn {
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 60px;
    padding: 0.7rem 1.5rem;
    text-decoration: none;
    color: white;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.return-home-btn:hover {
    background: rgba(255,255,255,0.25);
    color: white;
    transform: translateX(-3px);
}

.alert-modern {
    border-radius: 20px;
    padding: 1.2rem 1.5rem;
    margin-bottom: 1.8rem;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.alert-success-custom {
    background: #ecfdf5;
    border-left: 5px solid #059669;
    color: #065f46;
}

.alert-danger-custom {
    background: #fef2f2;
    border-left: 5px solid #dc2626;
    color: #991b1b;
}

.form-divider {
    height: 2px;
    background: linear-gradient(90deg, transparent, #cbd5e1, transparent);
    margin: 1.5rem 0 2rem;
}

.iti {
    width: 100%;
}

#prison_cont {
    padding-left: 90px !important;
}

.severity-badge {
    font-size: 0.7rem;
    padding: 4px 12px;
    border-radius: 30px;
    margin-left: 10px;
    font-weight: 600;
    display: inline-block;
}

.severity-low {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.severity-medium {
    background: #fed7aa;
    color: #9a3412;
    border: 1px solid #fdba74;
}

.severity-high {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.hint-text,
.phone-hint {
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 6px;
    margin-left: 28px;
}

.severity-note {
    background: #f8fafc;
    border-radius: 12px;
    padding: 8px 12px;
    margin-top: 10px;
    font-size: 0.72rem;
    border-left: 3px solid #3b71ca;
    color: #334155;
}

.req-badge {
    background: #fee2e2;
    color: #dc2626;
    font-size: 0.65rem;
    padding: 2px 8px;
    border-radius: 30px;
    margin-left: 8px;
    font-weight: 500;
}

.generated-creds {
    background: #1e293b;
    color: #e2e8f0;
    padding: 12px 16px;
    border-radius: 16px;
    font-family: monospace;
    font-size: 0.85rem;
    margin-top: 12px;
}

.cred-line {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #334155;
    padding: 8px 0;
}

.cred-line:last-child {
    border-bottom: none;
}

.cred-label {
    font-weight: 600;
    color: #94a3b8;
}

.cred-value {
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .form-body {
        padding: 1.5rem;
    }
    .card-header-custom h2 {
        font-size: 1.4rem;
    }
}

.iti--allow-dropdown .iti__flag-container:hover .iti__selected-flag {
    background: #f1f5f9;
}

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button {
    opacity: 0.5;
}

.field-error {
    border-color: #dc2626 !important;
    background-color: #fef2f2 !important;
}
</style>

<script>
function formValidation(){
    // Age validation 18-120
    var Age = document.getElementById('prison_age');
    var ageVal = parseInt(Age.value);
    if(isNaN(ageVal) || ageVal < 18 || ageVal > 120){
        alert("❌ Age must be between 18 and 120 years.");
        Age.focus();
        Age.classList.add('field-error');
        return false;
    }
    Age.classList.remove('field-error');

    // Gender validation
    var Gender = document.getElementById('prison_gen');
    if(Gender.value === "Gender" || Gender.value === ""){
        alert("❌ Please select a valid gender.");
        Gender.focus();
        Gender.classList.add('field-error');
        return false;
    }
    Gender.classList.remove('field-error');

    // Marital Status validation
    var status = document.getElementById('prison_stat');
    if(status.value === "Status" || status.value === ""){
        alert("❌ Please select marital status.");
        status.focus();
        status.classList.add('field-error');
        return false;
    }
    status.classList.remove('field-error');

    // Date validation
    var pdate = document.getElementById('da');
    var enddate = document.getElementById('end');
    
    if(!pdate.value || !enddate.value){
        alert("❌ Please fill both entry date and release date.");
        if(!pdate.value) pdate.focus();
        else enddate.focus();
        return false;
    }
    
    if(new Date(pdate.value) >= new Date(enddate.value)){
        alert("❌ Release date must be greater than entry date.");
        enddate.focus();
        enddate.classList.add('field-error');
        return false;
    }
    enddate.classList.remove('field-error');

    // Phone number validation (international format)
    var phoneVal = document.getElementById('prison_cont').value.trim();
    if(!phoneVal.match(/^\+\d{8,15}$/)){
        alert("❌ Phone number must be in international format starting with '+' and country code (e.g., +251912345678).");
        document.getElementById('prison_cont').focus();
        return false;
    }
    
    // Criminal Record text required
    var crimRecord = document.getElementById('criminal_record');
    if(!crimRecord.value.trim()){
        alert("❌ Please enter criminal record details.");
        crimRecord.focus();
        crimRecord.classList.add('field-error');
        return false;
    }
    crimRecord.classList.remove('field-error');
    
    // Severity selection required
    var severity = document.getElementById('criminal_severity');
    if(!severity.value || severity.value === ""){
        alert("❌ Please select criminal record severity.");
        severity.focus();
        severity.classList.add('field-error');
        return false;
    }
    severity.classList.remove('field-error');
    
    return true;
}

function analyzeCriminalRecord(){
    var record = document.getElementById('criminal_record').value.toLowerCase();
    var severitySelect = document.getElementById('criminal_severity');
    var severitySpan = document.getElementById('severityIndicator');
    
    if(record.includes('murder') || record.includes('rape') || record.includes('homicide') || record.includes('terrorism')){
        severitySpan.innerHTML = '<span class="severity-badge severity-high"><i class="fas fa-skull"></i> High Severity Recommended</span>';
        if(severitySelect.value === "")
            severitySelect.value = "High";
    } else if(record.includes('assault') || record.includes('theft') || record.includes('robbery') || record.includes('burglary')){
        severitySpan.innerHTML = '<span class="severity-badge severity-medium"><i class="fas fa-balance-scale"></i> Medium Severity Recommended</span>';
        if(severitySelect.value === "")
            severitySelect.value = "Medium";
    } else if(record.length > 8){
        severitySpan.innerHTML = '<span class="severity-badge severity-low"><i class="fas fa-leaf"></i> Low Severity Suggested</span>';
        if(severitySelect.value === "")
            severitySelect.value = "Low";
    } else if(record.length === 0){
        severitySpan.innerHTML = '';
    }
}

function validatePhoneRealtime() {
    var phoneInput = document.querySelector("#prison_cont");
    if(phoneInput && phoneInput.value.trim() && !phoneInput.value.trim().startsWith('+')) {
        phoneInput.style.borderColor = '#f59e0b';
    } else if(phoneInput && phoneInput.value.trim().match(/^\+\d{8,15}$/)) {
        phoneInput.style.borderColor = '#10b981';
    } else if(phoneInput && phoneInput.value.trim()) {
        phoneInput.style.borderColor = '#f97316';
    } else {
        if(phoneInput) phoneInput.style.borderColor = '#e2e8f0';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Add required star indicators dynamically
    const labels = document.querySelectorAll('.modern-group label');
    labels.forEach(label => {
        if(!label.querySelector('.required-star')) {
            const starSpan = document.createElement('span');
            starSpan.className = 'required-star';
            starSpan.innerHTML = ' *';
            label.appendChild(starSpan);
        }
    });
    
    // attach phone realtime validation
    var phoneField = document.getElementById('prison_cont');
    if(phoneField){
        phoneField.addEventListener('input', validatePhoneRealtime);
        phoneField.addEventListener('blur', validatePhoneRealtime);
    }
    
    // set min age attribute
    var ageInput = document.getElementById('prison_age');
    if(ageInput){
        ageInput.min = 18;
        ageInput.max = 120;
    }
    
    // Remove field-error class on focus
    var formFields = document.querySelectorAll('input, select, textarea');
    formFields.forEach(field => {
        field.addEventListener('focus', function() {
            this.classList.remove('field-error');
        });
    });
});
</script>
</head>
<body>

<div class="container-custom">
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <a href="index3.php" class="return-home-btn">
        <i class="fas fa-arrow-left"></i> Return to Dashboard
    </a>
    <div class="text-white-50 small">
        <i class="fas fa-asterisk text-danger me-1"></i> All fields are required
    </div>
</div>

<div class="form-card">
    <div class="card-header-custom">
        <h2>
            <i class="fas fa-user-shield"></i>
            Register New Prisoner
        </h2>
        <p style="color:#aac3e0; margin-top:8px; font-size:0.9rem;">
            <i class="fas fa-info-circle"></i> Complete all details marked with <span class="text-danger">*</span>
        </p>
    </div>

    <div class="form-body">
        <?php
        // Check if form was submitted
        if(isset($_POST["submit"]) && isset($conn) && $conn){
            
            // Sanitize input data
            $fname = mysqli_real_escape_string($conn, $_POST['prison_fname'] ?? '');
            $mname = mysqli_real_escape_string($conn, $_POST['prison_mname'] ?? '');
            $lname = mysqli_real_escape_string($conn, $_POST['prison_lname'] ?? '');
            $age = intval($_POST['prison_age'] ?? 0);
            $gen = mysqli_real_escape_string($conn, $_POST['prison_gen'] ?? '');
            $add = mysqli_real_escape_string($conn, $_POST['prison_add'] ?? '');
            $phone = mysqli_real_escape_string($conn, $_POST['prison_cont'] ?? '');
            $record = mysqli_real_escape_string($conn, $_POST['previews_record'] ?? '');
            $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record'] ?? '');
            $criminal_severity = mysqli_real_escape_string($conn, $_POST['criminal_severity'] ?? '');
            $stat = mysqli_real_escape_string($conn, $_POST['prison_stat'] ?? '');
            $da = $_POST['da'] ?? '';
            $end = $_POST['end'] ?? '';
            
            // Validate age
            if($age < 18 || $age > 120){
                echo "<div class='alert-modern alert-danger-custom'><i class='fas fa-exclamation-triangle'></i><div><strong>Invalid Age</strong><br>Age must be between 18 and 120 years.</div></div>";
            } 
            // Validate phone format
            elseif(!preg_match('/^\+\d{8,15}$/', $phone)){
                echo "<div class='alert-modern alert-danger-custom'><i class='fas fa-phone-alt'></i><div><strong>Invalid Phone Number</strong><br>Phone number must be in international format (e.g., +251912345678).</div></div>";
            }
            elseif(empty($da) || empty($end)){
                echo "<div class='alert-modern alert-danger-custom'><i class='fas fa-calendar-times'></i><div><strong>Missing Dates</strong><br>Please provide both entry and release dates.</div></div>";
            }
            elseif($da >= $end){
                echo "<div class='alert-modern alert-danger-custom'><i class='fas fa-calendar-times'></i><div><strong>Date Conflict</strong><br>Release date must be greater than entry date.</div></div>";
            } 
            else {
                // Prepare SQL statement for prisoner
                $sql = "INSERT INTO prisoner(prison_fname, prison_mname, prison_lname, prison_age, prison_gen, prison_add, prison_cont, previews_record, criminal_record, criminal_severity, prison_stat, Prison_Date, end_date) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
                
                $stmt = mysqli_prepare($conn, $sql);
                
                if($stmt){
                    mysqli_stmt_bind_param($stmt, "sssssssssssss", $fname, $mname, $lname, $age, $gen, $add, $phone, $record, $criminal_record, $criminal_severity, $stat, $da, $end);
                    
                    if(mysqli_stmt_execute($stmt)){
                        $pid = mysqli_insert_id($conn);
                        $username = strtolower($fname . "." . $lname);
                        $raw_password = strtoupper(substr($fname,0,1)) . strtolower(substr($lname,0,3)) . rand(1000,9999) . "@P";
                        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
                        
                        // Insert into users table
                        $sql_user = "INSERT INTO users(userName, password, userType, prisoner_id) VALUES(?,?,?,?)";
                        $stmt_user = mysqli_prepare($conn, $sql_user);
                        
                        if($stmt_user){
                            $userType = "Prisoner";
                            mysqli_stmt_bind_param($stmt_user, "sssi", $username, $hashed_password, $userType, $pid);
                            
                            if(mysqli_stmt_execute($stmt_user)){
                                $severityBadgeHtml = '';
                                if($criminal_severity == 'Low') $severityBadgeHtml = '<span class="severity-badge severity-low"><i class="fas fa-check-circle"></i> Low Severity</span>';
                                elseif($criminal_severity == 'Medium') $severityBadgeHtml = '<span class="severity-badge severity-medium"><i class="fas fa-chart-line"></i> Medium Severity</span>';
                                elseif($criminal_severity == 'High') $severityBadgeHtml = '<span class="severity-badge severity-high"><i class="fas fa-bolt"></i> High Severity</span>';
                                
                                echo "
                                <div class='alert-modern alert-success-custom'>
                                    <i class='fas fa-check-circle fa-2x'></i>
                                    <div style='flex:1'>
                                        <strong style='font-size:1rem'>✓ Prisoner Registered Successfully</strong>
                                        <div class='generated-creds mt-2'>
                                            <div class='cred-line'><span class='cred-label'><i class='fas fa-user'></i> Username:</span> <span class='cred-value'>" . htmlspecialchars($username) . "</span></div>
                                            <div class='cred-line'><span class='cred-label'><i class='fas fa-key'></i> Password:</span> <span class='cred-value'>" . htmlspecialchars($raw_password) . "</span></div>
                                            <div class='cred-line'><span class='cred-label'><i class='fas fa-phone'></i> Phone:</span> <span class='cred-value'>" . htmlspecialchars($phone) . "</span></div>
                                            <div class='cred-line'><span class='cred-label'><i class='fas fa-gavel'></i> Severity:</span> <span class='cred-value'>" . htmlspecialchars($criminal_severity) . " $severityBadgeHtml</span></div>
                                        </div>
                                        <div class='mt-2 small'>📋 Criminal Record: " . htmlspecialchars(substr($criminal_record,0,80)) . "...</div>
                                    </div>
                                </div>";
                            } else {
                                echo "<div class='alert-modern alert-danger-custom'><i class='fas fa-database'></i><div>User creation error: " . mysqli_error($conn) . "</div></div>";
                            }
                            mysqli_stmt_close($stmt_user);
                        } else {
                            echo "<div class='alert-modern alert-danger-custom'><i class='fas fa-database'></i><div>User statement preparation failed: " . mysqli_error($conn) . "</div></div>";
                        }
                    } else {
                        echo "<div class='alert-modern alert-danger-custom'><i class='fas fa-exclamation-circle'></i><div>Database error: " . mysqli_error($conn) . "</div></div>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<div class='alert-modern alert-danger-custom'><i class='fas fa-database'></i><div>Statement preparation failed: " . mysqli_error($conn) . "</div></div>";
                }
            }
        } elseif(isset($_POST["submit"]) && (!isset($conn) || !$conn)) {
            echo "<div class='alert-modern alert-danger-custom'><i class='fas fa-database'></i><div>Database connection error. Please check your configuration.</div></div>";
        }
        ?>

        <form method="POST" onsubmit="return formValidation()">
            <div class="row g-3">
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-user-circle"></i> First Name</label>
                    <input type="text" name="prison_fname" class="form-control" placeholder="Enter first name" required>
                </div>
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-user-friends"></i> Middle Name</label>
                    <input type="text" name="prison_mname" class="form-control" placeholder="Middle name" required>
                </div>
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-signature"></i> Last Name</label>
                    <input type="text" name="prison_lname" class="form-control" placeholder="Surname" required>
                </div>
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-calendar-alt"></i> Age (18-120)</label>
                    <input type="number" id="prison_age" name="prison_age" class="form-control" placeholder="Years" min="18" max="120" required>
                </div>
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-venus-mars"></i> Gender</label>
                    <select id="prison_gen" name="prison_gen" class="form-select" required>
                        <option value="">Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-globe-asia"></i> Phone (International)</label>
                    <input type="tel" id="prison_cont" name="prison_cont" class="form-control" placeholder="+251912345678" required>
                    <div class="phone-hint">
                        <i class="fas fa-info-circle"></i> Must start with country code e.g., +251, +1, +44
                    </div>
                </div>
                <div class="col-12 modern-group">
                    <label><i class="fas fa-map-pin"></i> Residential Address</label>
                    <textarea name="prison_add" class="form-control" rows="2" placeholder="Full address, city, region" required></textarea>
                </div>
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-archive"></i> Previous Convictions</label>
                    <input name="previews_record" class="form-control" placeholder="Summarize past record (if any)" required>
                </div>
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-heart"></i> Marital Status</label>
                    <select id="prison_stat" name="prison_stat" class="form-select" required>
                        <option value="">Select Status</option>
                        <option>Single</option>
                        <option>Married</option>
                        <option>Divorced</option>
                        <option>Widowed</option>
                    </select>
                </div>
                <div class="col-12 modern-group">
                    <label><i class="fas fa-gavel"></i> Criminal Record Details <span id="severityIndicator"></span></label>
                    <textarea id="criminal_record" name="criminal_record" class="form-control" rows="3" onkeyup="analyzeCriminalRecord()" placeholder="Describe crime, charges, conviction details..." required></textarea>
                    <div class="hint-text"><i class="fas fa-microphone-alt"></i> Provide detailed description for severity analysis</div>
                </div>
                <div class="col-12 modern-group">
                    <label><i class="fas fa-exclamation-triangle"></i> Criminal Severity Level</label>
                    <select id="criminal_severity" name="criminal_severity" class="form-select" required>
                        <option value="">-- Select Severity --</option>
                        <option value="Low">🟢 Low - Minor offenses / Misdemeanor</option>
                        <option value="Medium">🟠 Medium - Moderate / Felony class</option>
                        <option value="High">🔴 High - Severe / Violent crime</option>
                    </select>
                    <div class="severity-note"><i class="fas fa-chart-simple"></i> Severity influences custody level & parole eligibility.</div>
                </div>
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-calendar-plus"></i> Entry / Admission Date</label>
                    <input type="date" id="da" name="da" class="form-control" required>
                </div>
                <div class="col-md-6 modern-group">
                    <label><i class="fas fa-calendar-times"></i> Projected Release Date</label>
                    <input type="date" id="end" name="end" class="form-control" required>
                </div>
            </div>

            <div class="form-divider"></div>
            <button class="btn-submit" name="submit" type="submit">
                <i class="fas fa-save"></i> Register Prisoner & Generate Credentials
            </button>
            <p class="text-center text-muted mt-3 small"><i class="fas fa-lock"></i> Secure system — Credentials will be shown after successful registration</p>
        </form>
    </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>