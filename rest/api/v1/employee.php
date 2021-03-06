<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet($conn)
{
    echo json_encode(loadAllEmployees($conn));
}

function handlePut($conn)
{
    $parms = $_SERVER['QUERY_STRING'];
    $exploded = array();
    parse_str($parms, $exploded);
    $type = "configured";
    if (isset($exploded['type'])) {
        $type = $exploded['type'];
    }
    if ($type === 'minor') {
        echo json_encode(new MinorItem('', '', 0));
    } else {
        echo json_encode(new Item('', '', 0, 0, 0));
    }
}

function handlePost($conn)
{
    $payload = file_get_contents('php://input');
    $empsJson = json_decode($payload, false);

    foreach ($empsJson as $empJson) {
        $emp = Employee::parse($empJson);
        saveOrUpdateEmployee($emp, $conn);
    }
    echo $payload;
}

function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
