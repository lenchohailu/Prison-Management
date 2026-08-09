<?php
$DB_Server = "localhost";
$DB_Username = "root";
$DB_Password = "";
$DB_DBName = "prisons";
$DB_TBLName = "prisoner";
$xls_filename = 'export_'.date('Y-m-d').'.xls';

// Create mysqli connection
$connection = new mysqli($DB_Server, $DB_Username, $DB_Password, $DB_DBName);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Set charset for UTF-8
$connection->set_charset("utf8");

// Execute query
$sql = "SELECT * FROM $DB_TBLName";
$result = $connection->query($sql);
if(!$result) {
    die("Query failed: " . $connection->error);
}

// Header info for Excel
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache");
header("Expires: 0");

// Separator
$sep = "\t";

// Print column names
$fields = $result->fetch_fields();
foreach ($fields as $field) {
    echo $field->name . $sep;
}
echo "\n";

// Print rows
while($row = $result->fetch_assoc()) {
    $schema_insert = "";
    foreach ($row as $value) {
        if(!isset($value)) $schema_insert .= "NULL".$sep;
        elseif ($value !== "") $schema_insert .= $value.$sep;
        else $schema_insert .= "".$sep;
    }
    // Remove trailing tab and replace newlines
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", trim($schema_insert));
    echo $schema_insert . "\n";
}

$connection->close();
?>
