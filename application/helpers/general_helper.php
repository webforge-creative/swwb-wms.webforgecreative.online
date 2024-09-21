<?php
function distance($lat1, $lon1, $lat2, $lon2, $unit = "K")
{
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    } else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}
function getInitials($string = null)
{
    return array_reduce(
        explode(' ', $string),
        function ($initials, $word) {
            return sprintf('%s%s', $initials, substr($word, 0, 1));
        },
        ''
    );
}
function cleanInput($input)
{
    $CI = &get_instance();

    return $CI->db->escape_str($CI->security->xss_clean($input));
}

function generate_header_action($url, $name)
{
    return '<a href="' . $url . '" class="dropdown-item">' . $name . '</a>';
}