<?php
include('session.php'); // Optional session check

// Database credentials
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'prisons';

try {
    $filename = backup_tables($host, $user, $pass, $dbname);
    $successMessage = "Backup completed successfully! File saved as: <strong>" . htmlspecialchars($filename) . "</strong>";
} catch (Exception $e) {
    $successMessage = "Backup failed: " . htmlspecialchars($e->getMessage());
}

/**
 * Backup MySQL tables to SQL file
 */
function backup_tables($host, $user, $pass, $dbname, $tables = '*') {
    // Improved: Use Exception handling
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');
    $return = '';

    // Get all tables
    if ($tables == '*') {
        $tables = [];
        $result = $conn->query('SHOW TABLES');
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    // Backup each table
    foreach ($tables as $table) {
        $result         = $conn->query("SELECT * FROM `$table`");
        $num_fields     = $result->field_count;
        $row2           = $conn->query("SHOW CREATE TABLE `$table`")->fetch_assoc();
        $return        .= "\n\n" . $row2['Create Table'] . ";\n\n";

        // Insert data
        while ($row = $result->fetch_assoc()) {
            $vals = array_map(function($v) use ($conn) {
                if ($v === null) return 'NULL';
                return '"' . $conn->real_escape_string($v) . '"';
            }, array_values($row));
            $return .= "INSERT INTO `$table` VALUES(" . implode(',', $vals) . ");\n";
        }
        $return .= "\n";
    }

    // Save to file with more robust error checking
    $data = date("Y-m-d_H-i-s");
    $folder = 'DB_backup';
    if (!is_dir($folder) && !mkdir($folder, 0777, true)) {
        throw new Exception("Failed to create backup folder.");
    }
    $filename = $folder . '/db-backup-' . $data . '-' . $dbname . '.sql';

    if (file_put_contents($filename, $return) === false) {
        throw new Exception("Failed to write backup file.");
    }

    $conn->close();
    return $filename;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Database Backup</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container" style="margin-top:100px;">
    <a href="index1.php" class="btn btn-primary mb-3">Back to Home</a>
    <div class="alert alert-success"><?php echo $successMessage; ?></div>
  </div>
</body>
</html>
