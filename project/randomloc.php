<?php
header('Content-Type: application/json');

$lat = mt_rand(-900000, 900000) / 10000;
$lon = mt_rand(-1800000, 1800000) / 10000;

echo json_encode(["lat" => $lat, "lon" => $lon]);
?>
