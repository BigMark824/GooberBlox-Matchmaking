<?php

namespace StupidSimple\Math;

class DistanceCalculator
{
    public static function CalculateDistance(float $lat1, float $lon1, float $lat2, float $lon2)
    {
        /*
            Mark (update): this code measures two points in miles
            https://en.wikipedia.org/wiki/Haversine_formula
        */

        $earthsRadius = 6371;
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        
        $deltaLat = ($lat2 - $lat1) * pi() / 180;
        $deltaLon = ($lon2 - $lon1) * pi() / 180;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2( sqrt($a), sqrt(1 - $a) );

        return $earthsRadius * $c;
    }

}

