<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet()
{
    $parms = $_SERVER['QUERY_STRING'];
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    $exploded = array();
    parse_str($parms, $exploded);
    $filter = null;
    if (isset($exploded['filter'])) {
        $filter = $exploded['filter'];
        echo json_encode(loadFilteredItems($filter, $conn));
    } else {
        echo json_encode(loadAllItems( $conn));
    }
    $conn->close();
}

function handlePut()
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

function handlePost()
{
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    $payload = file_get_contents('php://input');
    $itemsJson = json_decode($payload, false);

    foreach ($itemsJson as $itemJson) {
        $item = Item::parse($itemJson);
        saveOrUpdateItem($item, $conn);
    }
//
//    $json = json_encode(saveOrUpdateItem($res, $conn));
//    $conn->close();
//    echo $payload;
}

function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
