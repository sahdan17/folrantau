<?php
function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371000;

    $latDifference = deg2rad($lat2 - $lat1);
    $lonDifference = deg2rad($lon2 - $lon1);

    $a = sin($latDifference / 2) * sin($latDifference / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($lonDifference / 2) * sin($lonDifference / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}

function destinationPoint($lat, $lon, $distance, $bearing) {
    $earthRadius = 6371000;

    $lat = deg2rad($lat);
    $lon = deg2rad($lon);
    $bearing = deg2rad($bearing);

    $destLat = asin(sin($lat) * cos($distance / $earthRadius) +
                    cos($lat) * sin($distance / $earthRadius) * cos($bearing));
    $destLon = $lon + atan2(sin($bearing) * sin($distance / $earthRadius) * cos($lat),
                            cos($distance / $earthRadius) - sin($lat) * sin($destLat));

    return [rad2deg($destLat), rad2deg($destLon)];
}

function calculateBearing($lat1, $lon1, $lat2, $lon2) {
    $lat1 = deg2rad($lat1);
    $lat2 = deg2rad($lat2);
    $lonDifference = deg2rad($lon2 - $lon1);

    $y = sin($lonDifference) * cos($lat2);
    $x = cos($lat1) * sin($lat2) -
         sin($lat1) * cos($lat2) * cos($lonDifference);

    $bearing = atan2($y, $x);

    return fmod(rad2deg($bearing) + 360, 360);
}

function findNewPointOnRoute($points, $distance) {
    $numPoints = count($points);

    for ($i = 0; $i < $numPoints - 1; $i++) {
        $segmentDistance = haversineDistance(
            $points[$i][1], $points[$i][0],
            $points[$i + 1][1], $points[$i + 1][0]
        );

        if ($distance <= $segmentDistance) {
            $bearing = calculateBearing(
                $points[$i][1], $points[$i][0],
                $points[$i + 1][1], $points[$i + 1][0]
            );

            return destinationPoint(
                $points[$i][1], $points[$i][0],
                $distance, $bearing
            );
        } else {
            $distance -= $segmentDistance;
        }
    }

    return $points[$numPoints - 1];
}

$route = isset($_GET['route']) ? intval($_GET['route']) : 1;
$dis = isset($_GET['dis']) ? floatval($_GET['dis']) : 15000;

if ($route == 1){
    $jsonData = file_get_contents('bjgtpn.json');
}

$points = json_decode($jsonData, true);
$desiredDistance = $dis;
$newPoint = findNewPointOnRoute($points, $desiredDistance);

return "https://www.google.com/maps?q=" . $newPoint[0] . "," . $newPoint[1];
?>