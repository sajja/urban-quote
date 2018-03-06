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

function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
