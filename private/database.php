<?php

require_once('db_credentials.php');

/* connect to oracle db */
function db_connect() {
    $connection = OCILogon(DB_USER, DB_PASS, DB_SERVER);
    confirm_db_connect($connection);
    return $connection;
}

/* disconnect from oracle db */
function db_disconnect($connection) {
    if(isset($connection)) {
        OCILogoff($connection);
    }
}

/* check connection state */
function confirm_db_connect($connection) {
    if(!$connection) {
        $msg = "Database connection failed: ";
        exit($msg);
    }
}

/* check if query worked */
function confirm_result_set($result_set) {
    if (!$result_set) {
        exit("Database query failed.");
    }
}

?>
