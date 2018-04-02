<?php

$servername = "localhost";
$username = "root";
$password = "root";


function loadAllItems($conn)
{
    $sql = "SELECT * FROM item";
    return loadItems($sql, $conn);
}


function loadFilteredItems($resource, $conn)
{
    $sql = "SELECT * FROM item where name like( '%" . $resource . "%')";
    return loadItems($sql, $conn);
}


function loadItems($sql, $conn)
{
    $objs = array();

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
    $item = new Item($row["name"], $row["description"], $row["total"], $row["recovery_time"], $row["hire_cost"]);
    $item->id = $row["id"];
    return $item;
}

function saveOrUpdateUtility($util, $conn)
{
    if ($util->id == null) {
        $sql = "INSERT INTO utility (name,total) values('" . $util->name . "'," . $util->cost . ")";
    } else {
        $sql = "UPDATE utility set total = " . $util->cost . " WHERE name='" . $util->name . "'";
    }

    if ($conn->query($sql) === TRUE) {
        return "Update successfully";
    } else {
        echo("Error description: " . mysqli_error($conn));
    }
}

function saveOrUpdateFlower($flower, $conn)
{
    if ($flower->id == null) {
        $sql = "INSERT INTO fresh_flower (name,buy_rate,comm_rate,sell_rate) values('" . $flower->name . "'," . $flower->buyRate . "," . $flower->comBuyRate . "," . $flower->sellRate . ")";
    } else {
        $sql = "UPDATE fresh_flower set name = '" . $flower->name . "'" . ", buy_rate = " . $flower->buyRate . ", comm_rate = " . $flower->comBuyRate . ", sell_rate = " . $flower->sellRate . " WHERE id=" . $flower->id;
    }


    if ($conn->query($sql) === TRUE) {
        return "Update successfully";
    } else {
        echo("Error description: " . mysqli_error($conn));
    }
}

function saveOrUpdateItem($resource, $conn)
{
    if ($resource->id == null) {
        $sql = "INSERT INTO item (name,description,total,recovery_time,hire_cost) VALUES ('$resource->name','$resource->description',$resource->total,$resource->recovery_time,$resource->hire_cost)";
        echo $sql;
    } else {
        $sql = "UPDATE item  set name='$resource->name', description = '$resource->description', total=$resource->total, recovery_time=$resource->recovery_time, hire_cost=$resource->hire_cost WHERE id=$resource->id";
    }

    if ($conn->query($sql) === TRUE) {
        return "Update successfully";
    } else {
        echo("Error description: " . mysqli_error($conn));
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
            $emp = new Employee($row["name"], $row["salary"]);
            $emp->id = $row["id"];
            array_push($flowers, $emp);
        }
        $result->close();
    } else {
        echo("Error description: " . mysqli_error($conn));
    }
    return $flowers;
}

function saveOrUpdateEmployee($emp, $conn)
{
    if ($emp->id == null) {
        $sql = "INSERT INTO employee (name,salary) VALUES ('$emp->name',$emp->salary)";
        echo $sql;
    } else {
        $sql = "UPDATE employee  set name='$emp->name', salary = $emp->salary WHERE id=$emp->id";
    }

    if ($conn->query($sql) === TRUE) {
        return "Update successfully";
    } else {
        echo("Error description: " . mysqli_error($conn));
    }
}

function loadAllFreshFlowers($conn)
{
    $flowers = array();
    if ($result = $conn->query('SELECT * FROM fresh_flower')) {

        while ($row = $result->fetch_assoc()) {
            $flower = new Flower($row["name"], $row["buy_rate"], $row["comm_rate"], $row["sell_rate"]);
            $flower->id = $row["id"];
            array_push($flowers, $flower);
        }
        $result->close();
    } else {
        echo("Error description: " . mysqli_error($conn));
    }
    return $flowers;
}

function saveOrUpdateQuotation($quotation, $quotation_data, $conn)
{
    if ($quotation->id == null) {

        $insertQuote = "INSERT INTO quotation (client_name,description,quote_date,wedding_date,event_type,location,event_time,approved)
                        VALUES (" . strItem($quotation->clientName) . "," . strItem($quotation->comments) . "," . "STR_TO_DATE(" . strItem($quotation->quotationDate) . ",'%m/%d/%Y')" . ","
            . "STR_TO_DATE(" . strItem($quotation->weddingDate) . ",'%m/%d/%Y')" . "," . strItem($quotation->eventType) . "," . strItem($quotation->location) . "," . strItem($quotation->eventTime) .
            "," . $quotation->approved . ")";

        if ($conn->query($insertQuote) === TRUE) {
            $quote_id = mysqli_insert_id($conn);
            $insertQuoteData = "INSERT INTO quotation_data (quotation_id,data) VALUES (" . strval($quote_id) . " ,'" . $quotation_data . "')";
            if ($conn->query($insertQuoteData) === TRUE) {
                $q = findQuotationById($quote_id, $conn);
                echo json_encode($q);
            } else {
                echo("Error description: " . mysqli_error($conn));
            }
        } else {
            echo $insertQuote;
            echo("Error description: " . mysqli_error($conn));
        }
    } else {
        $updateQuote = "UPDATE quotation set client_name='" . $quotation->clientName . "',description='" . $quotation->comments .
            "', quote_date=" . "STR_TO_DATE(" . strItem($quotation->quotationDate) . ", '%m/%d/%Y')" . ", wedding_date=" .
            "STR_TO_DATE(" . strItem($quotation->weddingDate) . ", '%m/%d/%Y') " . ", event_type='" . $quotation->eventType .
            "',location='" . $quotation->location . "',event_time='" . $quotation->eventTime . "',approved=" . $quotation->approved .
            " WHERE id = " . strval($quotation->id);

        if ($conn->query($updateQuote) === TRUE) {
            $updateQuoteData = "UPDATE quotation_data set data ='" . $quotation_data . "' where quotation_id= " . strval($quotation->id);
            if ($conn->query($updateQuoteData) === TRUE) {
                $q = findQuotationById($quotation->id, $conn);
                echo json_encode($q);
            } else {
                echo $updateQuoteData;
                echo("Error description: " . mysqli_error($conn));
            }
        } else {
            echo $updateQuote;
            echo("Error description: " . mysqli_error($conn));
        }
    }
}

function strItem($value)
{
    return "'" . $value . "'";

}

function loadUtilities($conn)
{
    $utilities = array();
    $sql = "select * from utility";

    if ($result = $conn->query($sql)) {
        $row_cnt = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            array_push($utilities, new Utility($row["name"], $row["total"], $row["id"]));
        }
        $result->close();
    } else {
        echo("Error description: " . mysqli_error($conn));
    }

    return $utilities;
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

function findQuotationById($id, $conn)
{
    $sql = "SELECT * FROM quotation where id = " . $id;
    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $quotation = loadQuotation($row);
            $result->close();
            return $quotation;
        }
    } else {
        echo $sql;
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
    $quotation->approved = $row['approved'];

    if ($quotation->approved == 1) {
        $quotation->isApproved = true;
    }
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
