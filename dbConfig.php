<?php
//db informācija
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'dbusers';

//Pieslēgties un izvēlēties db
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>