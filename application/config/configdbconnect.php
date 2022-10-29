<?php
require('configuration_db.php');
$globleConnectDB = array();
try {
    $username = $activedbusername;
    $password = $activedbpassword;
    $dbname = $activedb;

    $conn = new PDO("mysql:host=localhost;dbname=$activedb", $username, $password);


    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare('SELECT * FROM configuration_site');
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $globleConnectDB = $row;
    }

  
    
    $stmt = $conn->prepare('SELECT * FROM theme_css');
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $globleConnectTheme = $row;
    }
    
} catch (PDOException $e) {
    
}
