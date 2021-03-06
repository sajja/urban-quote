<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet($conn)
{
    echo json_encode(loadUtilities($conn));
}

function handlePost($conn)
{
    $payload = file_get_contents('php://input');
    $utilityJson = json_decode($payload, false);

    foreach ($utilityJson as $uj) {
        $utill = Utility::parse($uj);
        saveOrUpdateUtility($utill, $conn);
    }
    echo $payload;
}

function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
