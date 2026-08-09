<?php
include('DB.php'); // Should create $conn as MySQLi connection
session_start();

// Configuration
$MIN_PASSWORD_LENGTH = 8; // You can adjust this

// CSRF token generation/check (basic)
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];

$message = "";

$UN = $_SESSION['username'] ?? '';

if (!$UN) {
    die("You must be logged in to change password.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitchange'])) {
    // CSRF token check
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        $message = "<div class='alert alert-danger'>Invalid session/token. Please reload and try again.</div>";
    } else {
        $old = trim($_POST['txt_old']);
        $new_password = trim($_POST['txt_password']);
        $rpassword = trim($_POST['txt_rpassword']);

        if ($new_password !== $rpassword) {
            $message = "<div class='alert alert-warning'>New passwords do not match!</div>";
        } elseif (strlen($new_password) < $MIN_PASSWORD_LENGTH) {
            $message = "<div class='alert alert-warning'>New password must be at least " . $MIN_PASSWORD_LENGTH . " characters long.</div>";
        } else {
            // Fetch old password hash
            $stmt = $conn->prepare("SELECT password FROM users WHERE userName = ?");
            if ($stmt) {
                $stmt->bind_param("s", $UN);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows === 0) {
                    $message = "<div class='alert alert-danger'>User not found!</div>";
                } else {
                    $stmt->bind_result($old_hash);
                    $stmt->fetch();
                    if (!password_verify($old, $old_hash)) {
                        $message = "<div class='alert alert-danger'>Old password is incorrect!</div>";
                    } elseif ($old === $new_password) {
                        $message = "<div class='alert alert-warning'>Old and new password cannot be the same!</div>";
                    } else {
                        // Update password
                        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        $update = $conn->prepare("UPDATE users SET password = ? WHERE userName = ?");
                        if ($update) {
                            $update->bind_param("ss", $new_hash, $UN);
                            if ($update->execute()) {
                                $message = "<div class='alert alert-success'>Password changed successfully!</div>";
                            } else {
                                $message = "<div class='alert alert-danger'>Error updating password.</div>";
                            }
                            $update->close();
                        } else {
                            $message = "<div class='alert alert-danger'>Database error (update failed).</div>";
                        }
                    }
                }
                $stmt->close();
            } else {
                $message = "<div class='alert alert-danger'>Database error (could not prepare statement).</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Change Password</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        #login { background-color: silver; padding: 20px; max-width: 400px; margin: 50px auto; }
    </style>
</head>
<body>
<div id="login">
    <?php echo $message; ?>
    <fieldset>
        <form action="" method="post" name="frm_change" id="frm_change">
            <!-- CSRF token -->
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />
            <table class="table">
                <tr>
                    <td align="right"><strong>Old Password:</strong></td>
                    <td><input type="password" name="txt_old" required class="form-control" /></td>
                </tr>
                <tr>
                    <td align="right"><strong>New Password:</strong></td>
                    <td><input type="password" name="txt_password" required class="form-control" minlength="8" /></td>
                </tr>
                <tr>
                    <td align="right"><strong>Re-Enter New Password:</strong></td>
                    <td><input type="password" name="txt_rpassword" required class="form-control" minlength="8" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" name="submitchange" value="Change" class="btn btn-success" />
                        <input type="reset" value="Reset" class="btn btn-warning" />
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
</div>
</body>
</html>
