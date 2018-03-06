<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet()
{
    $parms = $_SERVER['QUERY_STRING'];
    $r = createResource($_SERVER['PATH_INFO'], $parms);
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    echo json_encode(loadItems($r, $conn));
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
    $parms = $_SERVER['QUERY_STRING'];
    $r = createResource($_SERVER['PATH_INFO'], $parms);
    $res = new Item($r->name, $_POST["description"], $_POST["total"], $_POST["recoverytime"], $_POST["hirecost"]);
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    $json = json_encode(saveOrUpdateItem($res, $conn));
    $conn->close();
    echo $json;
}

function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
