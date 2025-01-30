<?php

namespace App\Helpers;

class Predictions
{
    public static function calculateValue($inputValue, $defaultValue)
    {
        return is_numeric($inputValue) ? $defaultValue - floatval($inputValue) : null;
    }
    
    public static function predictLoc($x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9, $type)
    {
        if ($type == 'dynamic'){
            $yDinamis = 63.30 + (-0.16 * $x1) + (-0.52 * $x2) + (-1.57 * $x3) + (-0.11 * $x4) + (0.11 * $x5) + (-0.37 * $x6) + (0.51 * $x7) + (0.90 * $x8) + (-267.44 * $x9);
            $y = number_format($yDinamis, 2);
        } elseif ($type = 'static'){
            $yStatic = 63.30 + (-33.72 * $x1) + (-2.04 * $x2) + (-3.27 * $x3) + (-1.07 * $x4) + (-3.88 * $x5) + (0.46 * $x6) + (-0.43 * $x7) + (-0.34 * $x8) + (30.94 * $x9);
            $y = number_format($yStatic, 2);
        }
        
        // dd($y, $x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9, $type);
        // dd($y);
        
        if ($y <= 0 || $y > 64) {
            $tap = false;
            $suspect_loct = 'Probabilitas yang terjadi
- Tidak Terdapat Kebocoran
- Start/Stop Pompa
- Switch Tangki';
        } else {
            $tap = true;
            // $mapsLink = self::processMap(1, $y*1000);
//             $suspect_loct = '- Indikasi kebocoran pada titik ' . $y . ' KM dari MGS BJG (KM ' . $pu . ' Jalan PU).
// - Google Maps Link: ' . $mapsLink;
            $suspect_loct = '- Indikasi kebocoran pada titik ' . $y . ' KM dari PPP RTU';
        }
        
        return [$suspect_loct, $tap];
    }
    
    private static function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371000;
    
        $latDifference = deg2rad($lat2 - $lat1);
        $lonDifference = deg2rad($lon2 - $lon1);
    
        $a = sin($latDifference / 2) * sin($latDifference / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDifference / 2) * sin($lonDifference / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        return $earthRadius * $c;
    }
    
    private static function destinationPoint($lat, $lon, $distance, $bearing) {
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
    
    private static function calculateBearing($lat1, $lon1, $lat2, $lon2) {
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $lonDifference = deg2rad($lon2 - $lon1);
    
        $y = sin($lonDifference) * cos($lat2);
        $x = cos($lat1) * sin($lat2) -
             sin($lat1) * cos($lat2) * cos($lonDifference);
    
        $bearing = atan2($y, $x);
    
        return fmod(rad2deg($bearing) + 360, 360);
    }
    
    private static function findNewPointOnRoute($points, $distance) {
        $numPoints = count($points);
    
        for ($i = 0; $i < $numPoints - 1; $i++) {
            $segmentDistance = self::haversineDistance(
                $points[$i][1], $points[$i][0],
                $points[$i + 1][1], $points[$i + 1][0]
            );
    
            if ($distance <= $segmentDistance) {
                $bearing = self::calculateBearing(
                    $points[$i][1], $points[$i][0],
                    $points[$i + 1][1], $points[$i + 1][0]
                );
    
                return self::destinationPoint(
                    $points[$i][1], $points[$i][0],
                    $distance, $bearing
                );
            } else {
                $distance -= $segmentDistance;
            }
        }
    
        return $points[$numPoints - 1];
    }
    
    public static function processMap($route, $dis) {
        // dd('masuk');
        if ($route == 1){
            $jsonData = file_get_contents(__DIR__ . '/rantau.json');
        } else {
            // Load data lain jika diperlukan
            $jsonData = '[]'; // Default data atau tangani jika rute tidak ditemukan
        }
    
        $points = json_decode($jsonData, true);
        $desiredDistance = $dis;
        $newPoint = self::findNewPointOnRoute($points, $desiredDistance);
    
        return "https://www.google.com/maps?q=" . $newPoint[0] . "," . $newPoint[1];
    }
}
