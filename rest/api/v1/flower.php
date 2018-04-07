<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet($conn)
{
    $parms = $_SERVER['QUERY_STRING'];
    echo json_encode(loadAllFreshFlowers($conn));
}

function handlePost($conn)
{
    $payload = file_get_contents('php://input');
    $flowersJson = json_decode($payload, false);

    foreach ($flowersJson as $fJ) {
        $flower = Flower::parse($fJ);
        saveOrUpdateFlower($flower, $conn);
    }
}

function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
