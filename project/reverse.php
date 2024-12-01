<?php
header('Content-Type: application/json');

function generateRandomCoordinates() {
    $lat = mt_rand(-90000000, 90000000) / 1000000;
    $lon = mt_rand(-180000000, 180000000) / 1000000;

    return [$lat, $lon];
}

list($lat, $lon) = generateRandomCoordinates();

$hereApiKey = "Buew3zqq9sJoSDq5-DQ34WyGpN7yQXqGNxjd1AD3fls";

$url = "https://streetview.hereapi.com/v1/streetview?location={$lat},{$lon}&key={$hereApiKey}";

$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['image_url'])) {
    $openCageApiKey = "26cd66bff2974cb78d69b5b7521d6bf6";
    $openCageUrl = "https://api.opencagedata.com/geocode/v1/json?q={$lat}+{$lon}&key={$openCageApiKey}";
    $openCageResponse = file_get_contents($openCageUrl);
    $openCageData = json_decode($openCageResponse, true);
    $locationName = isset($openCageData['results'][0]['formatted']) ? $openCageData['results'][0]['formatted'] : "Unknown Location";

    echo json_encode([
        "latitude" => $lat,
        "longitude" => $lon,
        "imageUrl" => $data['image_url'],
        "locationName" => $locationName
    ]);
} else {
    echo json_encode([
        "latitude" => $lat,
        "longitude" => $lon,
        "error" => "No street view images found nearby"
    ]);
}
?>