<?php
// MySQL Parameters
$conf_mysqlHost = "";
$conf_mysqlUser = "";
$conf_mysqlPass = "";
$conf_mysqlDb = "";

// MySQL Connection
$mysql_resource = mysqli_connect( $conf_mysqlHost, $conf_mysqlUser, $conf_mysqlPass, $conf_mysqlDb) or die (error_mysql());
?>