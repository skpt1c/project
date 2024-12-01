<?php
header('Content-Type: application/json');

if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
    echo json_encode(["error" => "Latitude and longitude are required"]);
    exit;
}

$lat = $_GET['lat'];
$lon = $_GET['lon'];
$accessToken = "MLY|27991921137088279|484eff14a649e828465754896578c0e8";

$url = "https://graph.mapillary.com/images?access_token={$accessToken}&fields=thumb_1024_url&closeto={$lon},{$lat}&limit=1";

$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['data'][0]['thumb_1024_url'])) {
    echo json_encode(["imageUrl" => $data['data'][0]['thumb_1024_url']]);
} else {
    echo json_encode(["error" => "No street view image found nearby"]);
}
?>