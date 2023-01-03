<?php
require_once('api-bootstrap.php');

function filterCoordinates($coordinates, $center, $radius) {
    $filteredCoordinates = array_filter($coordinates, function($coordinate) use ($center, $radius) {
        $distance = getDistance($center, $coordinate);
        return $distance <= $radius;
    });
    // restores array indices
    return array_values($filteredCoordinates);
}

/**
 * Calculates the distance between two points on the 
 * Earth's surface using the Haversine formula.
 *
 * @return distance in kilometers.
 */
function getDistance($point1, $point2) {
    $lat1 = $point1['lat'];
    $lon1 = $point1['lon'];
    $lat2 = $point2['lat'];
    $lon2 = $point2['lon'];
    $rad = M_PI / 180;
    $lat1 *= $rad;
    $lon1 *= $rad;
    $lat2 *= $rad;
    $lon2 *= $rad;
    $a = sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon1 - $lon2);
    $dist = acos($a);
    return $dist * 6371; // 6371 is the radius of the Earth in kilometers
}


if (!isset($_GET['action'])) {
    header("HTTP/1.1 404 Not Found");
}

// action = 0: insert and get the location
if ($_GET['action'] == 0 ) {
    if (isset($_POST["name"], $_POST["lon"], $_POST["lat"])) {
        $response["ok"] = false;

        $location = [
            "name" => $_POST["name"],
            "lon" => $_POST["lon"],
            "lat" => $_POST["lat"],
        ];

        $locationId = registerLocation($location, $mysqli);

        if ($locationId != -1) {
            $location["locationId"] = $locationId;

            $response["location"] = $location;
            $response["ok"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($response);

    } else {
        header("HTTP/1.1 204 No Content");
    }

// action = 1: get locations join posts, inside circle
} elseif ($_GET['action'] == 1) {
    if (isset($_POST["center"], $_POST["radius"])) {
        $center = json_decode($_POST["center"], true);
        $radius = $_POST["radius"];

        $locations = getLocations($mysqli);

        if ($locations != null) {
            $filteredLocations = filterCoordinates($locations, $center, $radius);
        } else {
            $filteredLocations = array();
        }

        header("Content-Type: application/json");
        echo json_encode($filteredLocations);

    } else {
        echo "POST NON SETTATA";
        header("HTTP/1.1 204 No Content");
    }
}

?>
