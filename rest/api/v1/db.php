<?php

$servername = "localhost";
$username = "root";
$password = "root";


function loadItems($resource, $conn)
{
    $objs = array();
    if ($resource == null) {
        $sql = "SELECT * FROM item";
    } else {
        $sql = "SELECT * FROM item where name = '$resource->name'";
    }
    if ($result = $conn->query($sql)) {
        $row_cnt = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            array_push($objs, createItem($row));
        }
        $result->close();
    } else {
        echo("Error description: " . mysqli_error($conn));
    }
    return $objs;
}


function createItem($row)
{
    return new Item($row["name"], $row["description"], $row["total"], $row["recovery_time"], $row["hire_cost"]);
}


function saveOrUpdateItem($resource, $conn)
{
    $item = loadItem($resource);

    if ($item == null) {
        $sql = "INSERT INTO item VALUES ('$resource->name','$resource->description',$resource->total,$resource->recovery_time,$resource->hire_cost)";

        if ($conn->query($sql) === TRUE) {
            return "New record created successfully";
        } else {
            return "Error: ";
        }
    } else {
        $sql = "UPDATE item set description = '$resource->description', total=$resource->total, recovery_time=$resource->recovery_time, hire_cost=$resource->hire_cost WHERE name='$resource->name'";

        if ($conn->query($sql) === TRUE) {
            return "Update successfully";
        } else {
            return "Error: ";
        }
    }
}

function loadAllLabour($conn)
{
    $labour = array();
    if ($result = $conn->query('SELECT * FROM labour')) {
        while ($row = $result->fetch_assoc()) {
            array_push($labour, new Labour($row["type"], $row["rate"]));
        }
        $result->close();
    } else {
        throw new Exception("Error");
    }
    return $labour;
}

function loadAllEmployees($conn)
{
    $flowers = array();
    if ($result = $conn->query('SELECT * FROM employee')) {

        while ($row = $result->fetch_assoc()) {
            array_push($flowers, new Employee($row["name"], $row["salary"]));
        }

        $result->close();
    } else {
        throw new Exception("Error");
    }

    return $flowers;
}

function loadAllFreshFlowers($conn)
{
    $flowers = array();
    if ($result = $conn->query('SELECT * FROM fresh_flower')) {

        while ($row = $result->fetch_assoc()) {
            array_push($flowers, new Flower($row["name"], $row["buy_rate"], $row["comm_rate"], $row["sell_rate"]));
        }
        $result->close();
    } else {
        throw new Exception("Error");
    }
    return $flowers;
}

function saveOrUpdateQuotation($quotation, $quotation_data, $conn)
{
    if ($quotation->id == null) {
        $insertQuote = "INSERT INTO quotation (client_name,description,quote_date,wedding_date,event_type,location,event_time)
                        VALUES (" . strItem($quotation->clientName) . "," . strItem($quotation->comments) . "," . "STR_TO_DATE(" . strItem($quotation->quotationDate) . ",'%m/%d/%Y')" . ","
            . "STR_TO_DATE(" . strItem($quotation->weddingDate) . ",'%m/%d/%Y')" . "," . strItem($quotation->eventType) . "," . strItem($quotation->location) . "," . strItem($quotation->eventTime) . ")";
        if ($conn->query($insertQuote) === TRUE) {
            $quote_id = mysqli_insert_id($conn);
            $insertQuoteData = "INSERT INTO quotation_data (quotation_id,data) VALUES (" . strval($quote_id) . " ,'" . $quotation_data . "')";
            if ($conn->query($insertQuoteData) === TRUE) {
                echo("created");
            } else {
                echo("Error description: " . mysqli_error($conn));
            }
        } else {
            echo("Error description: " . mysqli_error($conn));
        }
    } else {
        $updateQuote = "UPDATE quotation set client_name='" . $quotation->clientName . "',description='" . $quotation->comments . "',
        quote_date=" . "STR_TO_DATE(" . strItem($quotation->quotationDate) . ", '%m/%d/%Y')" . ",
        wedding_date=" . "STR_TO_DATE(" . strItem($quotation->weddingDate) . ", '%m/%d/%Y') " . ",
        event_type='" . $quotation->eventType . "',location='" . $quotation->location . "',event_time='" . $quotation->eventTime . "' 
        WHERE id = " . strval($quotation->id);

        if ($conn->query($updateQuote) === TRUE) {
            if ($conn->query("UPDATE quotation_data set data ='" . $quotation_data . "' where quotation_id= ".strval($quotation->id)) === TRUE) {
                echo "updated";
            } else {
                echo("Error description: " . mysqli_error($conn));
            }
        } else {
            echo("Error description: " . mysqli_error($conn));
        }
    }
}

function strItem($value)
{
    return "'" . $value . "'";

}

function loadAllOtherCost($conn)
{
    if ($result = $conn->query('SELECT * FROM other_cost_master')) {
        $misc = 0;
        $tp = 0;
        $paint = 0;

        while ($row = $result->fetch_assoc()) {
            $name = $row["name"];
            $total = $row["total"];
            if ($name === "Transport hidden") {
                $tp = $total;
            } else if ($name === "Misc") {
                $misc = $total;
            }
        }
        $otherCosts = new OtherCosts();
        $otherCosts->hiddenTransport = $tp;
        $otherCosts->misc = $misc;
        $otherCosts->paint = $paint;
        //TODO: Add others

        $result->close();
        return $otherCosts;
    } else {
        throw new Exception("Error");
    }

}


function findQuotationDataById($id, $conn)
{
    $sql = "SELECT * FROM quotation_data where quotation_id=" . $id;
    if ($result = $conn->query($sql)) {
        $row_cnt = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $quotationData = $row['data'];
            $result->close();
            return $quotationData;
        }
    } else {
        echo("Error description: " . mysqli_error($conn) . $sql);
    }
}

function findQuotations($name, $from, $to, $conn)
{
    if ($name == null) {
        echo "load all";
        return "Hello";
    } else {
        $quotations = array();
        $sql = "SELECT * FROM quotation where 1 = 1 ";
        if ($name != null) {
            $sql = $sql . " and  client_name like '%" . $name . "%' ";
        }
        if ($from != null) {
            $sql = $sql . " and wedding_date >= '" . $from . "'";
        }
        if ($to != null) {
            $sql = $sql . " and wedding_date<= '" . $to . "'";
        }

        if ($result = $conn->query($sql)) {
            $row_cnt = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                array_push($quotations, loadQuotation($row));
            }
            $result->close();
        } else {
            echo("Error description: " . mysqli_error($conn) . $sql);
        }
        return $quotations;
    }
}

function loadQuotation($row)
{
    $quotation = new Quotation($row['client_name'], $row['wedding_date'], $row['quote_date'], $row['event_type'], $row['event_time'], $row['location'], $row['description']);
    $quotation->id = $row['id'];

    return $quotation;
}

function createComponent($name, $description, $qtty)
{
    $minorItems = array();
    $items = array();
    $flowers = array();

    array_push($minorItems, createMinorItem("Lhts", null, 1000));
    array_push($minorItems, createMinorItem("Bd", null, 2000));
    array_push($minorItems, createMinorItem("form", null, 200));

    array_push($flowers, createFlowers("hydra", 0, 10, 20, 40));
    array_push($flowers, createFlowers("lithi", 0, 10, 20, 40));
    array_push($flowers, createFlowers("crysh", 0, 10, 20, 40));

    array_push($items, createQotedItem("X", 10, 20), createQotedItem("Y", 1, 300), createQotedItem("4443", 1, 312));
    $c = new Component();

    $c->name = $name;
    $c->minorItem = $minorItems;
    $c->description = $description;
    $c->silkFlowers = 1000;
    $c->freshFlowers = $flowers;
    $c->floristPerc = 10;
    $c->qtty = $qtty;
    $c->labourPerc = 20 / $qtty;
    $c->otherCostPec = 40 / $qtty;
    $c->timeRiskPerc = 2 / $qtty;
    $c->profitPerc = 25 / $qtty;
    $c->items = $items;

    return $c;
}

function quoteLabour($type, $rate)
{
    return new Labour($type, $rate);
}


function quoteFlorist($qtty, $rate)
{
    return new QuotedFlorist($qtty, $rate);
}

function quoteShopRunningCost($type, $qtty, $rate)
{
}

function quoteFlowerRate($name, $br, $cr, $sellRate)
{
    return new QuotedFlower($name, $br, $cr, $sellRate, 0);
}

function createFlowers($name, $qtty, $br, $cr, $sr)
{
    return new QuotedFlower($name, $br, $cr, $sr, $qtty);
}

function createQotedItem($name, $qtty, $cost)
{
    return new QuotedItem(new Item($name, "", 0, 0, $cost), $qtty, $cost);
}

function createMinorItem($name, $description, $cost)
{
    return new MinorItem($name, $description, $cost);
}


function createDate($year, $month, $date)
{
    return "2018-03-30";
}

function createEmployee($name, $salery)
{
    return new Employee($name, $salery);
}

?>
