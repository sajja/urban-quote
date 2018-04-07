<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet($conn)
{
    echo json_encode(loadAllLabour($conn));
}

function handlePost($conn)
{
    $payload = file_get_contents('php://input');
    $labourersJson = json_decode($payload, false);

    foreach ($labourersJson as $labJson) {
        $lab = Labour::parse($labJson);
        saveOrUpdateLabourer($lab, $conn);
    }
    echo $payload;
}

function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
