<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet()
{
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    echo json_encode(loadAllLabour($conn));
    $conn->close();
}

function handlePost()
{
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    $payload = file_get_contents('php://input');
    $labourersJson = json_decode($payload, false);

    foreach ($labourersJson as $labJson) {
        $lab = Labour::parse($labJson);
        saveOrUpdateLabourer($lab, $conn);
    }
    echo $payload;
    $conn->close();
}

function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
