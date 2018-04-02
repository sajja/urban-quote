<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet()
{
    $parms = $_SERVER['QUERY_STRING'];
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    echo json_encode(loadAllFreshFlowers($conn));
    $conn->close();
}

function handlePost()
{
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    $payload = file_get_contents('php://input');
    $flowersJson = json_decode($payload, false);

    foreach ($flowersJson as $fJ) {
        $flower = Flower::parse($fJ);
        saveOrUpdateFlower($flower, $conn);
    }
    $conn->close();
}

function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
