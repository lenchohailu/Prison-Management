<?php
define("db_server", "localhost");
define("db_user", "root");
define("db_pass", "");
define("db_name", "prisons");

$conn = mysqli_connect(db_server, db_user, db_pass, db_name);

if (!$conn) {
    die("Error connecting to database: " . mysqli_connect_error());
}
?>
