<?php
require_once('api-bootstrap.php');

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

?>
