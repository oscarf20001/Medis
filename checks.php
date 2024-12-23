<?php

require_once 'db_connection.php';

### CHECK FOR TIMESTAMP
$time = time();
$endSignInTs = 1735513199;
$state = 0;
$service = 'ANMELDUNG';

// WENN AKTUELLE ZEIT UNTER DEM CHECK TIMESTAMP LIEGT, DANN ERLAUBE DEN EINLASS
if($time < $endSignInTs){
    $state = 1;
    
    $sqlSetSignInOff = "UPDATE controlls SET status = 'on' WHERE service = ?";
    $stmt = $conn->prepare($sqlSetSignInOff);
    $stmt->bind_param("s", $service);
    $stmt->execute();

    return $state;
}

$sqlSetSignInOff = "UPDATE controlls SET status = 'off' WHERE service = ?";
$stmt = $conn->prepare($sqlSetSignInOff);
$stmt->bind_param("s", $service);
$stmt->execute();
return $state;

?>