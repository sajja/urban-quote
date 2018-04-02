<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet()
{
}

function handlePut()
{
    $component = new Component();
    $basicItems = array();
    $freshFlowers = array();
    $configureItems = array();
    array_push($basicItems, createBasicItem('lights', 200), createBasicItem('backdrop', 500),
        createBasicItem('drapings', 500), createBasicItem('forms', 500));
    $component->minorItem = $basicItems;
    $component->items = $configureItems;
    $component->freshFlowers=$freshFlowers;
    $component->mandetory=true;
    echo json_encode($component);
}

function handlePost()
{
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    $r = createResource($_SERVER['PATH_INFO']);
    $res = new Item($r->name, $_POST["description"], $_POST["total"], $_POST["recoverytime"], $_POST["hirecost"]);
    $json =  json_encode(saveOrUpdateItem($res,$conn));
    $conn->close();
    echo $json;
}


function createBasicItem($name, $cost)
{
    return new MinorItem($name, '', $cost);
}

function createResource($uri)
{
    return Resource::parse($uri);
}

?>
