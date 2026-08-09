<?php
include('DB.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/*
|--------------------------------------------------------------------------
| HANDLE UPDATE
|--------------------------------------------------------------------------
*/
$msg = '';

if (isset($_POST["submit"])) {

    // Check if all required fields are present
    if (
        !isset($_POST['ID']) || empty($_POST['ID']) ||
        !isset($_POST['userName']) || empty($_POST['userName']) ||
        !isset($_POST['userType']) || $_POST['userType'] == 'Select User Type'
    ) {

        $msg = "<div class='alert alert-danger'>You must fill all fields.</div>";

    } else {

        $ID = trim($_POST["ID"]);
        $UN = trim($_POST["userName"]);
        $UT = trim($_POST["userType"]);
        $PS = trim($_POST["Password"]);
        
        // Check if password is being updated or keeping old one
        if (!empty($PS) && $PS != '********') {
            // Hash the new password
            $hashedPassword = password_hash($PS, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET userName=?, password=?, userType=? WHERE ID=?");
            $stmt->bind_param("ssss", $UN, $hashedPassword, $UT, $ID);
        } else {
            // Update without changing password
            $stmt = $conn->prepare("UPDATE users SET userName=?, userType=? WHERE ID=?");
            $stmt->bind_param("sss", $UN, $UT, $ID);
        }

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>
                        <i class='fa fa-check-circle'></i> User updated successfully.
                    </div>";
            // Refresh the row data after update
            $row['userName'] = $UN;
            $row['userType'] = $UT;
        } else {
            $msg = "<div class='alert alert-danger'>
                        <i class='fa fa-exclamation-triangle'></i> Update failed: {$stmt->error}
                    </div>";
        }
        $stmt->close();
    }
}

/*
|--------------------------------------------------------------------------
| LOAD SELECTED USER
|--------------------------------------------------------------------------
*/
$row = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT ID, userName, password, userType FROM users WHERE ID=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $msg = "<div class='alert alert-warning'>User not found.</div>";
    }
    $stmt->close();
}

/*
|--------------------------------------------------------------------------
| LOAD USER LIST
|--------------------------------------------------------------------------
*/
$users = [];
$res = $conn->query("SELECT ID, userName, userType FROM users ORDER BY userName");
if ($res) {
    while ($u = $res->fetch_assoc()) {
        $users[] = $u;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Prison Management System - Edit User Account</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/modern-business.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
body {
    background: #f5f7fa;
}

.page-header {
    background: linear-gradient(135deg, #0f1722 0%, #1e2a3a 100%);
    color: white;
    padding: 20px 30px;
    border-radius: 10px;
    margin-top: 20px;
    margin-bottom: 30px;
    border: none;
}

.page-header h1 {
    margin: 0;
    font-size: 24px;
}

.page-header h1 i {
    margin-right: 10px;
}

.panel {
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.panel-heading {
    border-radius: 12px 12px 0 0;
    font-weight: 600;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.btn-edit {
    margin: 0 2px;
}

.alert {
    border-radius: 10px;
    margin-bottom: 20px;
}

/* Password hint */
.password-hint {
    font-size: 11px;
    color: #6c757d;
    margin-top: 5px;
}

.password-info {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    margin-top: 8px;
    font-size: 12px;
    border-left: 3px solid #17a2b8;
}

.password-info ul {
    margin: 5px 0 0 20px;
    padding: 0;
}

.password-info li {
    margin: 3px 0;
}

/* Modern form styling */
.form-control {
    border-radius: 8px;
    border: 1.5px solid #e2e8f0;
    transition: all 0.2s;
}

.form-control:focus {
    border-color: #3b71ca;
    box-shadow: 0 0 0 3px rgba(59,113,202,0.1);
}

.btn-success {
    background: linear-gradient(95deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
}

.btn-success:hover {
    background: linear-gradient(95deg, #0f2a4f 0%, #1f4280 100%);
    transform: translateY(-1px);
}

.btn-default {
    border-radius: 8px;
    padding: 8px 20px;
}
</style>

<script>
function formValidation() {
    let UserName = document.getElementById('userName');
    let Password = document.getElementById('Password');
    let UserType = document.getElementById('userType');
    
    // Username validation (lowercase only)
    let usernameRegex = /^[a-z]+$/;
    if (!usernameRegex.test(UserName.value)) {
        alert("❌ Username must contain only lowercase letters (a-z).");
        UserName.focus();
        return false;
    }
    
    // Username length validation
    if (UserName.value.length < 3 || UserName.value.length > 30) {
        alert("❌ Username must be between 3 and 30 characters.");
        UserName.focus();
        return false;
    }
    
    // Password validation (only if password field is not empty and not placeholder)
    let passwordVal = Password.value;
    if (passwordVal && passwordVal !== '********') {
        if (passwordVal.length < 8) {
            alert("❌ Password must be at least 8 characters long.");
            Password.focus();
            return false;
        }
        if (!/[A-Z]/.test(passwordVal)) {
            alert("❌ Password must contain at least one uppercase letter.");
            Password.focus();
            return false;
        }
        if (!/[a-z]/.test(passwordVal)) {
            alert("❌ Password must contain at least one lowercase letter.");
            Password.focus();
            return false;
        }
        if (!/[0-9]/.test(passwordVal)) {
            alert("❌ Password must contain at least one number.");
            Password.focus();
            return false;
        }
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(passwordVal)) {
            alert("❌ Password must contain at least one special character.");
            Password.focus();
            return false;
        }
    }
    
    // User type validation
    if (UserType.value === "Select User Type") {
        alert("❌ Please select a valid user type.");
        UserType.focus();
        return false;
    }
    
    return true;
}

// Convert username to lowercase as user types
function enforceLowercase(input) {
    input.value = input.value.toLowerCase().replace(/[^a-z]/g, '');
}
</script>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-inverse navbar-fixed-top">
<div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index3.php">
            <i class="fa fa-shield"></i> Prison System
        </a>
    </div>
    
    <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav navbar-left">
            <li><a href="index3.php"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="user_account.php"><i class="fa fa-user-plus"></i> Add User</a></li>
        </ul>
        
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php" style="color:white;"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
    </div>
</div>
</nav>

<div class="container-fluid" style="margin-top:70px;">

<div class="container">
    <div class="page-header">
        <h1>
            <i class="fa fa-users"></i> User Account Management
        </h1>
        <p class="text-white-50" style="margin-top:5px; opacity:0.8;">
            <i class="fa fa-info-circle"></i> Edit user accounts, reset passwords, and manage permissions
        </p>
    </div>

    <?php if (!empty($msg)) echo $msg; ?>

    <!-- ================= USER SELECT LIST ================= -->
    <?php if (!$row): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-list"></i> Select a User to Edit
            <span class="pull-right text-muted">Total Users: <?= count($users) ?></span>
        </div>
        <div class="panel-body">
            
            <?php if (count($users) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="35%">User Name</th>
                            <th width="35%">User Type</th>
                            <th width="25%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($u['ID']) ?></td>
                            <td><i class="fa fa-user"></i> <?= htmlspecialchars($u['userName']) ?></td>
                            <td>
                                <?php 
                                $badgeClass = '';
                                if($u['userType'] == 'Admin') $badgeClass = 'label-danger';
                                elseif($u['userType'] == 'Police Commissioner') $badgeClass = 'label-warning';
                                elseif($u['userType'] == 'Inspector') $bandageClass = 'label-info';
                                else $badgeClass = 'label-default';
                                ?>
                                <span class="label <?= $badgeClass ?>"><?= htmlspecialchars($u['userType']) ?></span>
                            </td>
                            <td class="text-center">
                                <a href="?id=<?= $u['ID'] ?>" class="btn btn-primary btn-sm btn-edit">
                                    <i class="fa fa-edit"></i> Edit User
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fa fa-info-circle"></i> No users found. 
                    <a href="user_account.php" class="alert-link">Create a new user</a>
                </div>
            <?php endif; ?>
            
            <div class="text-right" style="margin-top: 15px;">
                <a href="user_account.php" class="btn btn-success">
                    <i class="fa fa-plus-circle"></i> Create New User
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ================= EDIT FORM ================= -->
    <?php if ($row && isset($row['ID'])): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-pencil-square-o"></i> Edit User Account
            <span class="pull-right text-muted">User ID: <?= htmlspecialchars($row['ID']) ?></span>
        </div>
        <div class="panel-body">
            
            <form method="post" class="form-horizontal" onsubmit="return formValidation()">
                
                <!-- ID (Readonly) -->
                <div class="form-group">
                    <label class="col-md-2 control-label">User ID</label>
                    <div class="col-md-3">
                        <input type="text" name="ID" value="<?= htmlspecialchars($row['ID'] ?? '') ?>" class="form-control" readonly>
                    </div>
                </div>
                
                <!-- User Name -->
                <div class="form-group">
                    <label class="col-md-2 control-label">User Name <span class="text-danger">*</span></label>
                    <div class="col-md-4">
                        <input type="text" 
                               id="userName" 
                               name="userName" 
                               value="<?= htmlspecialchars($row['userName'] ?? '') ?>" 
                               class="form-control" 
                               oninput="enforceLowercase(this)"
                               placeholder="username (lowercase only)"
                               required>
                        <div class="password-hint">
                            <i class="fa fa-info-circle"></i> Only lowercase letters (a-z), 3-30 characters
                        </div>
                    </div>
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <label class="col-md-2 control-label">Password</label>
                    <div class="col-md-4">
                        <input type="password" 
                               id="Password" 
                               name="Password" 
                               value="" 
                               class="form-control" 
                               placeholder="Leave blank to keep current password">
                        <div class="password-info">
                            <strong><i class="fa fa-lock"></i> Password Requirements:</strong>
                            <ul>
                                <li>Minimum 8 characters</li>
                                <li>At least one uppercase letter</li>
                                <li>At least one lowercase letter</li>
                                <li>At least one number</li>
                                <li>At least one special character (!@#$%^&*)</li>
                            </ul>
                            <small class="text-muted"><i class="fa fa-info-circle"></i> Leave empty to keep existing password</small>
                        </div>
                    </div>
                </div>
                
                <!-- User Type -->
                <div class="form-group">
                    <label class="col-md-2 control-label">User Type <span class="text-danger">*</span></label>
                    <div class="col-md-3">
                        <select id="userType" name="userType" class="form-control" required>
                            <option value="">Select User Type</option>
                            <?php
                            $types = ["Prisoner", "Inspector", "Police Commissioner", "Police Officer", "Admin"];
                            foreach ($types as $t) {
                                $sel = (isset($row['userType']) && $t == $row['userType']) ? "selected" : "";
                                echo "<option $sel>$t</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <button type="submit" name="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Update User
                        </button>
                        <a href="edit_account.php" class="btn btn-default">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                        <a href="user_account.php" class="btn btn-info">
                            <i class="fa fa-plus"></i> Add New User
                        </a>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
    
    <!-- Danger Zone for Admin users -->
    <div class="panel panel-danger">
        <div class="panel-heading">
            <i class="fa fa-exclamation-triangle"></i> Danger Zone
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <strong>Reset Password</strong>
                    <p class="text-muted small">Force a password reset on next login</p>
                    <button class="btn btn-warning btn-sm" onclick="alert('Password reset feature coming soon')">
                        <i class="fa fa-refresh"></i> Force Password Reset
                    </button>
                </div>
                <div class="col-md-6">
                    <strong>Delete Account</strong>
                    <p class="text-muted small">Permanently remove this user account</p>
                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['ID'] ?>)">
                        <i class="fa fa-trash"></i> Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
</div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
function confirmDelete(userId) {
    if (confirm("⚠️ Are you sure you want to delete this user account?\n\nThis action cannot be undone!")) {
        window.location.href = "delete_user.php?id=" + userId;
    }
}

$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>

</body>
</html>