<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet($conn)
{
    $parms = $_SERVER['QUERY_STRING'];

    $resource = createResource($_SERVER['PATH_INFO'], $parms);
    if ($resource === null) {
        $exploded = array();
        parse_str($parms, $exploded);
        $name = null;
        $from = null;
        $to = null;
        if (isset($exploded['name'])) {
            $name = $exploded['name'];
        } else if ($exploded['from']) {
            $from = $exploded['from'];
        } else if ($exploded['to']) {
            $to = $exploded['to'];
        }

        echo json_encode(findQuotations($name, $from, $to, $conn));
    } else {
        echo findQuotationDataById($resource->name, $conn);
    }
}

function handlePost($conn)
{
    $payload = file_get_contents('php://input');
    $jsonQuote = json_decode($payload, false);

    $quotation = Quotation::parse($jsonQuote);
    try {
        $res = saveOrUpdateQuotation($quotation, json_encode($jsonQuote->data), $conn);
        echo json_encode(new Response(200, '', $res));
    } catch (Exception $e) {
        echo json_encode(new Response(500, 'Error', $e->getMessage()));
    }
}

function handlePut($conn)
{
    echo json_encode(newQuotation($conn));
}

function handleDelete($conn)
{
    $parms = $_SERVER['QUERY_STRING'];
    $resource = createResource($_SERVER['PATH_INFO'], $parms);

    if ($resource !== null && is_numeric($resource->name)) {
        try {
            softDeleteQuotation($resource->name, $conn);
            echo json_encode(new Response(200, '', "Successfully deleted"));
        } catch (Exception $e) {
            echo json_encode(new Response(500, 'Error', $e->getMessage()));
        }
    } else {
        echo json_encode(new Response(500, 'Error', ' Invalid quotation id'));
    }
}

function quoteFlower($flower)
{

    return new QuotedFlower($flower->name, $flower->buyRate, $flower->comBuyRate, $flower->sellRate, 0);
}

function newQuotation($conn)
{
    $components = array();
    $flowers = loadAllFreshFlowers($conn);
    $labourers = loadAllLabour($conn);
    $employees = loadAllEmployees($conn);
//    $otherCosts = loadAllOtherCost($conn);

    $quotedFreshFlowerRates = array();

    for ($x = 0; $x < count($flowers); $x++) {
        array_push($quotedFreshFlowerRates, quoteFlower($flowers[$x]));
    }


    $quotationData = new QuotationData();

    $quotationData->components = $components;
    $quotationData->quotedFreshFlowerRates = $quotedFreshFlowerRates;
    $quotationData->quotedLabourRates = new QuotedLabour($labourers);
    $quotationData->quotedFloristRates = quoteFlorist(1, 7000);

    $utilities = loadUtilities($conn);

    $shopRunningCost = new QuotedShopRunningCost();
    $shopRunningCost->employees = $employees;
    $shopRunningCost->rent = 40000;
    $shopRunningCost->electricity = 13000;
    $shopRunningCost->water = 500;
    $shopRunningCost->telephone = 4500;
    $shopRunningCost->misc = 5000;
    $shopRunningCost->stationary = 1000;
    $shopRunningCost->transport = 1000;
    $shopRunningCost->utilities = $utilities;

    $quotationData->shopRunningCost = $shopRunningCost;
    $quotationData->quotedOtherCosts = new QuotedOtherCosts();

    return $quotationData;
}


function createQuote($jsonQuote)
{
    return QuotationData::parse($jsonQuote);
}


function createResource($uri, $params)
{
    return Resource::parse($uri, $params);
}

?>
