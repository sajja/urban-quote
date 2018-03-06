<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet()
{
    $parms = $_SERVER['QUERY_STRING'];

    $resource = createResource($_SERVER['PATH_INFO'], $parms);
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');

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
    $conn->close();
}

function handlePost()
{
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');
    $payload = file_get_contents('php://input');
    $jsonQuote = json_decode($payload, false);

    $quotation = Quotation::parse($jsonQuote);
//    $quote = createQuote($jsonQuote->data);
//    var_dump($quote);
//    echo json_encode($jsonQuote->data);

//      var_dump($payload);
    saveOrUpdateQuotation($quotation, json_encode($jsonQuote->data), $conn);
    $conn->close();
}

function handlePut()
{
    echo json_encode(newQuotation());
}


function quoteFlower($flower)
{

    return new QuotedFlower($flower->name, $flower->buyRate, $flower->comBuyRate, $flower->sellRate, 0);
}

function newQuotation()
{
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');

    $components = array();

    $flowers = loadAllFreshFlowers($conn);
    $labourers = loadAllLabour($conn);
    $employees = loadAllEmployees($conn);
    $otherCosts = loadAllOtherCost($conn);

    $quotedFreshFlowerRates = array();

    for ($x = 0; $x < count($flowers); $x++) {
        array_push($quotedFreshFlowerRates, quoteFlower($flowers[$x]));
    }


    $quotationData = new QuotationData();

    $quotationData->components = $components;
    $quotationData->quotedFreshFlowerRates = $quotedFreshFlowerRates;
    $quotationData->quotedLabourRates = new QuotedLabour($labourers);
    $quotationData->quotedFloristRates = quoteFlorist(1, 7000);
    $shopRunningCost = new QuotedShopRunningCost();
    $shopRunningCost->rent = 40000;
    $shopRunningCost->electricity = 13000;
    $shopRunningCost->water = 500;
    $shopRunningCost->telephone = 4500;
    $shopRunningCost->misc = 5000;
    $shopRunningCost->stationary = 1000;
    $shopRunningCost->transport = 1000;
    $shopRunningCost->employees = $employees;
    $quotationData->shopRunningCost = $shopRunningCost;
    $quotationData->quotedOtherCosts = new QuotedOtherCosts();
    $quotationData->quotedOtherCosts->paint = $otherCosts->paint;
    $quotationData->quotedOtherCosts->misc = $otherCosts->misc;
    $quotationData->quotedOtherCosts->cleaning = $otherCosts->cleaning;
    $quotationData->quotedOtherCosts->food = $otherCosts->food;
    $quotationData->quotedOtherCosts->hiddenTransport = $otherCosts->hiddenTransport;

    $conn->close();
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
