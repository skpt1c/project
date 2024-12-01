<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['lat1'], $data['lon1'], $data['lat2'], $data['lon2'])) {
    echo json_encode(["error" => "Missing required parameters"]);
    exit;
}

$lat1 = $data['lat1'];
$lon1 = $data['lon1'];
$lat2 = $data['lat2'];
$lon2 = $data['lon2'];

function haversine($lat1, $lon1, $lat2, $lon2) {
    $R = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c;
}

$distance = haversine($lat1, $lon1, $lat2, $lon2);

echo json_encode(["distance" => $distance]);
?>