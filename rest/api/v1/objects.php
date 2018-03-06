<?php


class Quotation
{
    public $id;
    public $clientName;
    public $weddingDate;
    public $quotationDate;
    public $eventType;
    public $eventTime;
    public $location;
    public $description;
    public $quotation;
    public $data;

    function __construct($clientName, $weddingDate, $quotationDate, $eventType, $eventTime, $location, $description)
    {
        $this->clientName = $clientName;
        $this->weddingDate = $weddingDate;
        $this->quotationDate = $quotationDate;
        $this->eventType = $eventType;
        $this->eventTime = $eventTime;
        $this->location = $location;
        $this->description = $description;
    }

    static function parse($json)
    {
        $quotation = new Quotation(
            $json->clientName,
            $json->weddingDate,
            $json->quotationDate,
            $json->eventType,
            $json->eventTime,
            $json->location,
            $json->description
        );
        $quotation->id = $json->id;

        $quotationData = QuotationData::parse($json->data);
        $quotation->data = $quotationData;

        return $quotation;
    }
}

class QuotationData
{
    public $components;
    public $transport = 0;
    public $componentsCost = 0;
    public $quotationValue;
    public $riskFactor = 100;
    public $comments;

    //Master data applied to quotation with customizatoins
    public $quotedFreshFlowerRates;
    public $quotedLabourRates;
    public $quotedFloristRates;
    public $quotedOtherCosts;
    public $bestCaseFlowerProfit = 0;
    public $avgCaseFlowerProfit = 0;

    static function parse($json)
    {
        $quote = new QuotationData();

        $quote->componentsCost = $json->componentsCost;
        $quote->quotationValue = $json->quotationValue;
        $quote->riskFactor = $json->riskFactor;
        $quote->comments = $json->comments;
        $quote->bestCaseFlowerProfit = $json->bestCaseFlowerProfit;
        $quote->avgCaseFlowerProfit = $json->avgCaseFlowerProfit;

        $components = array();
        for ($x = 0; $x < count($json->components); $x++) {
            array_push($components, Component::parse($json->components[$x]));
        }
        $quote->components = $components;

        $quotedFreshFlowerRates = array();
        for ($x = 0; $x < count($json->quotedFreshFlowerRates); $x++) {
            array_push($quotedFreshFlowerRates, QuotedFlower::parse($json->quotedFreshFlowerRates[$x]));
        }
        $quote->quotedFreshFlowerRates = $quotedFreshFlowerRates;

        $quotedLabour = array();
        for ($x = 0; $x < count($json->quotedLabourRates->labour); $x++) {
            array_push($quotedLabour, QuotedLabour::parse($json->quotedLabourRates->labour[$x]));
        }

        $quote->quotedLabourRates = $quotedLabour;
        $quote->quotedFloristRates = QuotedFlorist::parse($json->quotedFloristRates);
        $quote->quotedOtherCosts = QuotedOtherCosts::parse($json->quotedOtherCosts);

        return $quote;
    }
}

class Component
{
    public $name;
    public $description;
    public $qtty = 1;

    public $labourRates;
    public $floristRates;
    public $otherCostRates;

    public $items;
    public $freshFlowers;
    public $silkFlowers = 0;
    public $silkFlowerRate = 10;
    public $otherFlowers = 0;
    public $otherFlowerRate = 5;
    public $artificialLeaves = 0;

    public $minorItem;

    public $labourPerc = 10;
    public $floristPerc = 10;
    public $otherCostPerc = 10;
    public $profitPerc = 10;
    public $timeRiskPerc = 10;

    public $totalLabourCost = 0;
    public $totalFloristCost = 0;
    public $totalOtherCost = 0;
    public $totalProfit = 0;

    public $totalMaterialCost;
    public $totalFreshFlowerCost;
    public $totalArtificialFlowerCost;
    public $totalFlowerCost;
    public $totalItemCost;
    public $totalMinorItemCost;

    public $totalBeforeProfit;
    public $total;


    static function parse($json)
    {
        $component = new Component();

        $minorItems = array();
        $freshFlowers = array();

        $component->name = $json->name;
        $component->description = $json->description;
        $component->qtty = $json->qtty;
        $component->labourRates = $json->labourRates;
        $component->floristRates = $json->floristRates;
        $component->otherCostRates = $json->otherCostRates;
        $component->silkFlowers = $json->silkFlowers;
        $component->otherFlowers = $json->otherFlowers;
        $component->silkFlowerRate = $json->silkFlowerRate;
        $component->otherFlowerRate = $json->otherFlowerRate;
        $component->artificialLeaves = $json->artificialLeaves;
        $component->labourPerc = $json->labourPerc;
        $component->floristPerc = $json->floristPerc;
        $component->otherCostPerc = $json->otherCostPerc;
        $component->profitPerc = $json->profitPerc;
        $component->timeRiskPerc = $json->timeRiskPerc;
        $component->totalLabourCost = $json->totalLabourCost;
        $component->totalFloristCost = $json->totalFloristCost;
        $component->totalOtherCost = $json->totalOtherCost;
        $component->totalProfit = $json->totalProfit;
        $component->totalMaterialCost = $json->totalMaterialCost;
        $component->totalFreshFlowerCost = $json->totalFreshFlowerCost;
        $component->totalArtificialFlowerCost = $json->totalArtificialFlowerCost;
        $component->totalItemCost = $json->totalItemCost;
        $component->totalBeforeProfit = $json->totalBeforeProfit;
        $component->total = $json->total;

        for ($x = 0; $x < count($json->minorItem); $x++) {
            array_push($minorItems, MinorItem::parse($json->minorItem[$x]));
        }

        for ($x = 0; $x < count($json->freshFlowers); $x++) {
            array_push($freshFlowers, QuotedFlower::parse($json->freshFlowers[$x]));
        }


        $component->minorItem = $minorItems;
        $component->freshFlowers=$freshFlowers;
        return $component;
    }
}

class MinorItem
{
    public $name;
    public $description;
    public $cost;
    public $order;

    function __construct($name, $description, $cost)
    {
        $this->name = $name;
        $this->description = $description;
        $this->cost = $cost;
    }

    static function parse($json)
    {
        $item = new MinorItem($json->name, $json->description, $json->cost);
        $item->order = $json->order;

        return $item;
    }
}

class QuotedItem
{
    public $item;
    public $qtty;
    public $rate;

    function __construct($item, $qtty, $rate)
    {
        $this->item = $item;
        $this->qtty = $qtty;
        $this->rate = $rate;
    }

    static function parse($json)
    {
        $item = new QuotedItem(Item::parse($json->item), $json->qtty, $json->rate);
        return $item;
    }
}

class Item
{
    public $name;
    public $description;
    public $total;
    public $recovery_time;
    public $hire_cost;

    function __construct($name, $description, $total, $rt, $hire)
    {
        $this->name = $name;
        $this->description = $description;
        $this->total = $total;
        $this->recovery_time = $rt;
        $this->hire_cost = $hire;
    }

    static function parse($json)
    {
        $item = new Item($json->name, $json->description, $json->total, $json->recovery_time, $json->hire_cost);
        return $item;
    }
}


class Flower
{
    public $name;
    public $buyRate;
    public $comBuyRate;
    public $sellRate;

    function __construct($name, $buyRate, $comBuyRate, $sellRate)
    {
        $this->name = $name;
        $this->buyRate = $buyRate;
        $this->comBuyRate = $comBuyRate;
        $this->sellRate = $sellRate;
    }

    static function parse($json)
    {
        $flower = new Flower($json->name, $json->buyRate, $json->comBuyRate, $json->sellRate);
        return $flower;
    }
}

class QuotedFlower
{
    public $name;
    public $buyRate;
    public $commRate = 0;
    public $sellRate;
    public $qtty = 0;
    public $bestCaseCost;
    public $avgCaseCost;
    public $bestCaseProfit;
    public $avgCaseProfit;

    function __construct($name, $buyRate, $commRate, $sellRate, $qtty)
    {
        $this->name = $name;
        $this->buyRate = $buyRate;
        $this->commRate = $commRate;
        $this->sellRate = $sellRate;
        $this->qtty = $qtty;
    }

    static function parse($json)
    {
        $flower = new QuotedFlower($json->name, $json->buyRate, $json->commRate, $json->sellRate, $json->qtty);
        $flower->bestCaseCost = $json->bestCaseCost;
        $flower->avgCaseCost = $json->avgCaseCost;
        $flower->bestCaseProfit = $json->bestCaseProfit;
        $flower->avgCaseProfit = $json->avgCaseProfit;
        return $flower;
    }
}


class QuotedLabour
{
    public $labour;
    public $cost;

    function __construct($labour)
    {
        $this->labour = $labour;
    }

    static function parse($json)
    {
        $labour = new QuotedLabour(new Labour($json->type, $json->rate));
        return $labour;
    }
}

class Labour
{
    public $type;
    public $rate;
    public $qtty = 1;
    public $cost;

    function __construct($type, $rate)
    {
        $this->type = $type;
        $this->rate = $rate;
    }

    static function parse($json)
    {
        $labour = new Labour($json->type, $json->rate);
        $labour->qtty = $json->qtty;
        $labour->cost = $json->cost;
        return $labour;
    }
}

class QuotedFlorist
{
    public $rate;
    public $qtty;
    public $cost;

    function __construct($qtty, $rate)
    {
        $this->rate = $rate;
        $this->qtty = $qtty;
    }


    static function parse($json)
    {
        $florist = new QuotedFlorist($json->qtty, $json->rate);
        $florist->cost = $json->cost;
        return $florist;
    }
}

class QuotedOtherCosts
{
    public $shopRunningCostPerc = 0;
    public $totalShopRunningCostApplied = 0;
    public $food = 1000;
    public $paint = 0;
    public $cleaning = 0;
    public $misc = 0;
    public $hiddenTransport = 0;
    public $flowerWastagePerc = 0;
    public $flowerWastageApplied = 0;
    public $total = 0;

    static function parse($json)
    {
        $otherCosts = new QuotedOtherCosts();
        $otherCosts->shopRunningCostPerc = $json->shopRunningCostPerc;
        $otherCosts->totalShopRunningCostApplied = $json->totalShopRunningCostApplied;
        $otherCosts->food = $json->food;
        $otherCosts->paint = $json->paint;
        $otherCosts->cleaning = $json->cleaning;
        $otherCosts->misc = $json->misc;
        $otherCosts->hiddenTransport = $json->hiddenTransport;
        $otherCosts->flowerWastagePerc = $json->flowerWastagePerc;
        $otherCosts->flowerWastageApplied = $json->flowerWastageApplied;
        $otherCosts->total = $json->total;

        return $otherCosts;
    }
}

class OtherCosts
{
    public $shopRunningCostPerc = 0;
    public $totalShopRunningCostApplied = 0;
    public $food = 0;
    public $paint = 0;
    public $cleaning = 0;
    public $misc = 0;
    public $hiddenTransport = 0;
    public $flowerWastagePerc = 0;
    public $flowerWastageApplied = 0;
    public $total = 0;

    static function parse($json)
    {
        $otherCosts = new OtherCosts();
        $otherCosts->shopRunningCostPerc = $json->shopRunningCostPerc;
        $otherCosts->totalShopRunningCostApplied = $json->totalShopRunningCostApplied;
        $otherCosts->food = $json->food;
        $otherCosts->paint = $json->paint;
        $otherCosts->cleaning = $json->cleaning;
        $otherCosts->misc = $json->misc;
        $otherCosts->hiddenTransport = $json->hiddenTransport;
        $otherCosts->flowerWastagePerc = $json->flowerWastagePerc;
        $otherCosts->flowerWastageApplied = $json->flowerWastageApplied;
        $otherCosts->total = $json->total;

        return $otherCosts;
    }
}

class ShopRunningCost
{
    public $electricity;
    public $water;
    public $telephone;
    public $stationary;
    public $transport;
    public $misc;
    public $rent;

    static function parse($json)
    {
        $shop = new ShopRunningCost();
        $shop->electricity = $json->electricity;
        $shop->water = $json->water;
        $shop->telephone = $json->telephone;
        $shop->stationary = $json->stationary;
        $shop->transport = $json->transport;
        $shop->misc = $json->misc;
        $shop->rent = $json->rent;
        $shop->total = $json->total;
        return $shop;
    }
}


class QuotedShopRunningCost
{
    public $employees;
    public $electricity;
    public $water;
    public $telephone;
    public $stationary;
    public $transport;
    public $misc;
    public $rent;
    public $total = 0;

    static function parse($json)
    {
        $shop = new ShopRunningCost();
        $shop->employees = $json->employees;
        $shop->electricity = $json->electricity;
        $shop->water = $json->water;
        $shop->telephone = $json->telephone;
        $shop->stationary = $json->stationary;
        $shop->transport = $json->transport;
        $shop->misc = $json->misc;
        $shop->rent = $json->rent;
        $shop->total = $json->total;
        return $shop;
    }
}

class QuotedEmployee
{
    public $name;
    public $salary;

    function __construct($name, $salary)
    {
        $this->name = $name;
        $this->salary = $salary;
    }

    static function parse($json)
    {
        return new QuotedEmployee($json->name, $json->salary);
    }
}

class Employee
{
    public $name;
    public $salary;

    static function parse($json)
    {
        return new Employee($json->name, $json->salary);
    }

    function __construct($name, $salary)
    {
        $this->name = $name;
        $this->salary = $salary;
    }
}

class Florist
{
    public $cost;

    static function parse($json)
    {
        $f = new Florist();
        $f->cost = $json->cost;
        return $f;
    }
}

class Resource
{
    public $name;
    public $resource;
    public $params;

    function __construct($name, $resource)
    {
        $this->name = $name;
        $this->resource = $resource;
    }

    static function parse($uri, $params)
    {
        if (strpos($uri, '/') === 0) {
            $uri = substr($uri, 1);//trim the first /
        }

        $s = explode('/', $uri, 2);

        if ($s[0] == '') {
            return null;
        } else if (sizeof($s) == 1) {
            $res = new Resource($s[0], null);
            $res->params = $params;
            return $res;
        } else {
            return new Resource($s[0], self::parse($s[1], $params));
        }
    }
}

?>
