<?php
require_once('authenticate.php');
?>
<html lang="en-US">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/angular.min.js"></script>
<script src="js/angular-animate.min.js"></script>
<script src="js/angular-aria.min.js"></script>
<script src="js/angular-messages.min.js"></script>
<script src="js/angular-material.min.js"></script>
<script src="js/notify.js"></script>
<script src="js/jquery.validate.js"></script>
<!-- Angular Material Library -->
<link rel="stylesheet" href="css/angular-material.min.css">

<style>
    .ng-hide {
        opacity: 0;
        background-color: transparent;
    }

    .calculated-field {
        background-color: lightslategrey;
    }

    .approved {
        background-color: blue;
    }

    .calculated-field-row {
        background-color: lightgray;
    }

    .sub-component-header {
        background-color: darkgrey;
    }

    td {
        padding-right: 10px;
        padding-left: 10px;
    }

    input {
        background-color: lightcyan;
        width: 100%;
    }

    #container {
        height: 100%;
        font-size: 0;
    }

    .componentHeaderRow {
        margin-top: 3px;
        background-color: #c7ddef;
    }

    .headerRow {
        margin-top: 3px;
        background-color: #5e5e5e;
    }

    .dataRow {
        margin-top: 5px;
        padding-bottom: 5px;
        background-color: #cccccc;
    }

    .horiz-divs {
        display: inline-block;
        *display: inline;
        zoom: 1;
        vertical-align: top;
        font-size: 12px;
        padding-right: 10px;
        padding-top: 10px;
    }

    .total {
        background-color: #23527c;
    }

    .subtotal {
        background-color: #1b6d85;
    }

    .error {
        background-color: red;
    }

    #notifications {
        cursor: pointer;
        position: fixed;
        right: 0px;
        z-index: 9999;
        bottom: 0px;
        margin-bottom: 22px;
        margin-right: 15px;
        max-width: 300px;
    }
</style>
<body>
<div ng-controller="DemoCtrl as ctrl" class="autocompletedemoFloatingLabel" ng-app="MyApp">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12" style="background-color:aliceblue">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-9">Urban stem quotation calculator</div>
                        <div class="col-md-3">
                            <table width="100%">
                                <tr>
                                    <td><img src="images/approve.png" height="30" width="30"
                                             ng-click="approveAndSave()"/></td>
                                    <td><img src="images/new.png" height="30" width="30" ng-click="newQuote()"/></td>
                                    <td><img src="images/save.jpg" height="30" width="30" ng-click="save()"/></td>
                                    <td><img src="images/clone.png" height="30" width="30" ng-click="clone()"/></td>
                                    <td><img src="images/delete.png" height="30" width="30"
                                             ng-click="confirmDelete()"/></td>
                                    <td><img src="images/items.png" height="30" width="30" ng-click="goToInventory()"/>
                                    </td>
                                    <td><a href="shop.php"> <img src="images/shop.png" height="30" width="30"/></a>
                                    </td>
                                    <td><img src="images/refresh.png" height="30" width="30" ng-click="refreshMasterData()" title="Refresh flower/item data"/> </td>
                                    <td><img src="images/refresh-all.png" height="30" width="30" ng-click="refreshAll()" title="Warning: This will change the quotation value. Never do this on a already sent quotation"/> </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <table border="1" width="100%">
                    <tr>
                        <td>Search</td>
                        <td><input ng-model="name" id="searchField"></td>
                        <td align="right">
                            <button ng-click="search()">&#128269;</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            Search results
                        </td>
                    </tr>
                    <tr ng-repeat="quotation in searchResult">
                        <td colspan="2"><input ng-model="quotation.id" hidden/> <a href=""
                                                                                   ng-click="showQuotation(quotation)">{{quotation.clientName}}</a>
                        </td>
                        <td> {{quotation.weddingDate}} &nbsp;
                            <img src="images/approve.png" height="30" width="30" style="padding-top:5px;"
                                 ng-show="quotation.isApproved"/>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="notifications"></div>
            <form id="quotationForm" ng-show="isQuotationLoaded">
                <!--<form id="quotationForm" ng-show="showQuotation">-->
                <div class="col-md-10">
                    <div class="container-fluid">
                        <div class="row headerRow">
                            <div class="col-lg-11"><h4>Client Info</h4></div>
                            <div class="col-lg-1">
                                <img src="images/approve.png" height="50" width="50" style="padding-top:5px;"
                                     ng-show="quotation.isApproved"/>
                                <img src="images/waiting.png" height="50" width="50" style="padding-top:5px;"
                                     ng-show="!quotation.isApproved"/>
                            </div>
                        </div>
                        <div class="row dataRow">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-1">Name</div>
                                    <div class="col-md-3 ">
                                        <input class="required" style="width:100%" ng-model="quotation.clientName"
                                               name="clientName"/>
                                    </div>
                                    <div class="col-md-2">Event type</div>
                                    <div class="col-md-2"><select ng-model="quotation.eventType">
                                            <option value="">Event type</option> <!-- not selected / blank option -->
                                            <option value="Wedding">Wedding</option>
                                            <!-- not selected / blank option -->
                                            <option value="Homecoming">Homecoming</option>
                                            <!-- not selected / blank option -->
                                            <option value="Bday">B'day</option> <!-- not selected / blank option -->
                                            <option value="Party">Party</option> <!-- not selected / blank option -->
                                        </select></div>
                                    <div class="col-md-1">Location</div>
                                    <div class="col-md-3"><input class="required" style="width: 100%;"
                                                                 ng-model="quotation.location" name="location"></div>
                                </div>
                                <div class="row" style="padding-top:5px;">
                                    <div class="col-md-1">
                                        Time
                                    </div>
                                    <div class="col-md-2">
                                        <select ng-model="quotation.eventTime">
                                            <option value="">Time</option> <!-- not selected / blank option -->
                                            <option value="Evening">Evening</option>
                                            <!-- not selected / blank option -->
                                            <option value="Morning">Morning</option>
                                            <!-- not selected / blank option -->

                                        </select>
                                    </div>
                                    <div class="col-md-2">Quote date</div>
                                    <div class="col-md-2" style="padding:0;margin:0">
                                        <md-datepicker ng-model="quotationDate"
                                                       ng-change="{{quotation.quotationDate=quotationDate.toLocaleDateString()}}"
                                                       md-placeholder="Quotation date"
                                                       style="padding:0;margin:0"></md-datepicker>
                                    </div>
                                    <div class="col-md-2">Wedding date</div>
                                    <div class="col-md-2" style="padding:0;margin:0">
                                        <md-datepicker ng-model="weddingDate" md-placeholder="Wedding date"
                                                       ng-change="{{quotation.weddingDate=weddingDate.toLocaleDateString()}}"
                                                       style="padding:0;margin:0"></md-datepicker>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <textarea style="width: 100%;height: 100px;"
                                          ng-model="quotation.comments"></textarea>
                            </div>
                        </div>
                        <div class="row headerRow">
                            <div class="col-lg-12">
                                <h4>Summary of quotation</h4>
                            </div>
                        </div>
                        <div class="row dataRow">
                            <div class="col-md-12">
                                <table border="1" width="100%">
                                    <tr>
                                        <th>Component</th>
                                        <th>Mandetory</th>
                                        <th width="4%">Qtty</th>
                                        <th>Rate</th>
                                        <th width="4%">Labour %</th>
                                        <th width="4%"> Florist %</th>
                                        <th width="4%">Other %</th>
                                        <th width="4%">Profit %</th>
                                        <th width="4%">Risk factor %</th>
                                        <th>Total profit</th>
                                        <th>Quote</th>
                                        <th><span style="color: #5cb85c;text-align: center"
                                                  ng-click="newComponent()"><h2>➕</h2></span></th>
                                    </tr>
                                    <tr ng-repeat="c in quoteData.components">
                                        <td><input ng-model="c.name" style="width: 90%" name="component"
                                                   class="required"/> &nbsp;<span
                                                    ng-click="scrollTo('c-'+c.id)">▼</span></td>
                                        <td><input type="checkbox" ng-model="c.mandetory"/></td>
                                        <td><input ng-model="c.qtty" name="qtty" class="required digit"/></td>
                                        <td>{{c.total |number:0}}</td>
                                        <td><input ng-model="c.labourPerc" class="required digit" name="labour"/></td>
                                        <td><input ng-model="c.floristPerc" class="required digit" name="florist"/></td>
                                        <td><input ng-model="c.otherCostPerc" name="othercost" class="required digit"/>
                                        </td>
                                        <td><input ng-model="c.profitPerc" class="required digit" name="profit"/></td>
                                        <td>{{quotation.riskFactor}}</td>
                                        <td>{{c.mandetory ?(c.totalProfit*c.qtty | number:0) :0}}</td>
                                        <td colspan="2">{{c.mandetory ? ( (c.total * c.qtty)| number:0): 0}}</td>
                                    </tr>
                                    <tr>
                                        <td>Sub totals</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>{{sumAllLabourPerc(quoteData.components)}}</td>
                                        <td>{{sumAllFloristPerc(quoteData.components)}}</td>
                                        <td>{{sumAllOtherCostPerc(quoteData.components)}}</td>
                                        <td colspan="2"></td>
                                        <td>{{sumAllProfit(quoteData.components)}}</td>
                                        <td colspan="2">
                                            {{sumAllComponents(quoteData.components)}}
                                            {{quoteData.componentsCost | number:0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Transport</td>
                                        <td colspan="9">&nbsp;</td>
                                        <td colspan="2"><input ng-model="quoteData.transport" name="transport"
                                                               class="digit"/></td>
                                    </tr>
                                    <tr>
                                        <td>Final quote</td>
                                        <td colspan="9">&nbsp;</td>
                                        <td colspan="2">{{createFinalQuote()|number:0}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row headerRow" style="margin-top: 4px;">
                            <div class="col-lg-11"><h5>Master data applied to quotation</h5></div>
                            <div class="col-lg-1" style="text-align: right"><h4><span
                                            ng-click="expandCollaspeMasterData()">↓</span></h4></div>
                        </div>
                        <div class="row dataRow" ng-show="toggleMasterData">
                            <div class="row">
                                <div class="col-lg-4">
                                    <table border="1">
                                        <tr>
                                            <td colspan="7">Flower rates</td>
                                        </tr>
                                        <tr>
                                            <td>name</td>
                                            <td>Buy rate</td>
                                            <td>Avg buy rate</td>
                                            <td>Sell</td>
                                            <td>Qtty </td>
                                            <td>Quoted</td>
                                            <td>Actual spent</td>
                                        </tr>
                                        <tr ng-repeat="flower in quoteData.quotedFreshFlowerRates">
                                            <td>{{flower.name}}</td>
                                            <td>{{flower.buyRate}}</td>
                                            <td>{{flower.commRate}}</td>
                                            <td><input ng-model="flower.sellRate"/></td>
                                            <td>{{flower.qtty}}</td>
                                            <td>{{flower.qtty * flower.sellRate}}</td>
                                            <td><input ng-model="flower.actual"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6">Other flower costs</td>
                                            <td><input ng-model="quoteData.actualFlowerCost"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-3">
                                    <table border="1" width="100%">
                                        <tr>
                                            <td colspan="5">Labour rates</td>
                                        </tr>
                                        <tr>
                                            <td>Type</td>
                                            <td>Rate</td>
                                            <td>Qtty</td>
                                            <td>Cost</td>
                                            <td>Actual</td>
                                        </tr>
                                        <tr ng-repeat="labour in quoteData.quotedLabourRates.labour">
                                            <td>{{labour.type}}</td>
                                            <td><input ng-model="labour.rate" name="labourRate"
                                                       class="digit required"/></td>
                                            <td><input ng-model="labour.qtty" name="labourQtty" class="digit required"/>
                                            </td>
                                            <td>{{labour.rate * labour.qtty}}</td>
                                            <td><input ng-model="labour.actual" name="labourActual" class="digit"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Total labour</td>
                                            <td>
                                                {{calculateTotalLabour(quoteData.quotedLabourRates)}}
                                                {{quoteData.quotedLabourRates.cost}}
                                            </td>
                                            <td>
                                                {{quoteData.quotedLabourRates.actualCost}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Florist</td>
                                            <td><input ng-model="quoteData.quotedFloristRates.rate"
                                                       name="floristRate" class="digit"/></td>
                                            <td><input ng-model="quoteData.quotedFloristRates.qtty"
                                                       name="floristQtty" class="digit"/></td>
                                            <td>
                                                {{calculateFloristCost(quoteData.quotedFloristRates)}}
                                                {{quoteData.quotedFloristRates.cost}}
                                            </td>
                                            <td><input ng-model="quoteData.quotedFloristRates.actual"
                                                       name="floristActual" class="digit"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-2">
                                    <table border="1">
                                        <tr>
                                            <td colspan="3">Other costs</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Transport hidden</td>
                                            <td><input ng-model="quoteData.quotedOtherCosts.hiddenTransport"
                                                       name="hiddenTransport" class="digit"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Food</td>
                                            <td><input ng-model="quoteData.quotedOtherCosts.food" name="food"
                                                       class="digit"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Paint</td>
                                            <td><input ng-model="quoteData.quotedOtherCosts.paint" class="digit"
                                                       name="paint"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Cleaning</td>
                                            <td><input ng-model="quoteData.quotedOtherCosts.cleaning"
                                                       name="cleaning" class="digit"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Misc</td>
                                            <td><input ng-model="quoteData.quotedOtherCosts.misc" name="misc"
                                                       class="digit"/></td>
                                        </tr>
                                        <tr>
                                            <td>Shop %</td>
                                            <td><input ng-model="quoteData.quotedOtherCosts.shopRunningCostPerc"
                                                       name="shopRunningPerc" class="required digit"/>
                                            </td>
                                            <td>
                                                {{calculateShopRunningCostApplied(quoteData.quotedOtherCosts)}}
                                                {{quoteData.quotedOtherCosts.totalShopRunningCostApplied}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Flower waste%</td>
                                            <td><input ng-model="quoteData.quotedOtherCosts.flowerWastagePerc"
                                                       name="floristPerc" class="digit"/>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Total other costs</td>
                                            <td>
                                                {{calculateTotalOtherCosts(quoteData.quotedOtherCosts)}}
                                                {{quoteData.quotedOtherCosts.total}}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-3">
                                    <table border="1">
                                        <tr>
                                            <th colspan="3">Shop running cost</th>
                                        </tr>
                                        <tr ng-repeat="src in quoteData.shopRunningCost.utilities">
                                            <td colspan="3">{{src.name}}</td>
                                            <td>{{src.cost}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Labour</td>
                                        </tr>
                                        <tr ng-repeat="emp in quoteData.shopRunningCost.employees">
                                            <td>&nbsp;</td>
                                            <td>{{emp.name}}</td>
                                            <td colspan="2"><input ng-model="emp.salary" class="required digit"/>
                                            <td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Total</td>
                                            <td>
                                                {{calculateShopRunningCost(quoteData.shopRunningCost)}}
                                                {{quoteData.shopRunningCost.total}}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-3">
                                    <table border="1">
                                        <tr>
                                            <th>Months</th>
                                            <th>Risk factor per month</th>
                                        </tr>
                                        <tr>
                                            <td>0-6</td>
                                            <td>0</td>
                                        </tr>
                                        <tr>
                                            <td>7-12</td>
                                            <td>0.5 for each month</td>
                                        </tr>
                                        <tr>
                                            <td>13-18</td>
                                            <td>0.8</td>
                                        </tr>
                                        <tr>
                                            <td>19-24</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>Months to wedding</td>
                                            <td> {{diffDate(quotation.quotationDate,quotation.weddingDate)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Calculated risk</td>
                                            <td>
                                                {{calculateRiskFactor(diffDate(quotation.quotationDate,quotation.weddingDate))}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Applied risk</td>
                                            <td><input ng-model="quotation.riskFactor"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-3">
                                    <table border="1">
                                        <tr>
                                            <th>Cost</th>
                                            <th>Value</th>
                                        </tr>
                                        <tr>
                                            <td>Artificial flowers</td>
                                            <td>
                                                {{calculateArtificialFlowerCost()}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fresh flower best case</td>
                                            <td>{{bestCaseFlowerProfit()}}</td>
                                        </tr>
                                        <tr>
                                            <td>Fresh flower worse case</td>
                                            <td>{{avgCaseFlowerProfit()}}</td>
                                        </tr>
                                        <tr>
                                            <td>Structures</td>
                                            <td>{{calculateStructureCost()}}</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="row headerRow" style="margin-top: 3px">
                            <div class="col-lg-11"><h5>Quoted costs vs actual costs</h5></div>
                            <div class="col-lg-1" style="text-align: right"><h4><span
                                            ng-click="expandCollaspeActual()">↓</span></h4></div>
                        </div>
                        <div class="row dataRow" ng-show="toggleActual">
                            <div class="col-lg-4">
                                <table border="1">
                                    <tr>
                                        <th colspan="4">Costs</th>
                                    </tr>
                                    <tr>
                                        <th> Flower type</th>
                                        <th> Cost</th>
                                        <th> Sale</th>
                                        <th> Diff</th>
                                    </tr>
                                    <tr ng-repeat="flower in quoteData.quotedFreshFlowerRates">
                                        <td>{{flower.name}}</td>
                                        <td><input ng-model="flower.actual"/></td>
                                        <td>{{flower.qtty * flower.sellRate}}</td>
                                        <td>{{(flower.qtty * flower.sellRate)-flower.actual}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total fresh flower profit/loss</td>
                                        <td>{{calculateFreshFlowerProfit()}}</td>
                                    </tr>
                                    <tr>
                                        <td>New structure cost</td>
                                        <td><input ng-model="quoteData.newStructuresCost"/></td>
                                    </tr>
                                    <tr>
                                        <td>Other cost</td>
                                        <td><input ng-model="quoteData.actualOtherCosts"/></td>
                                    </tr>
                                    <tr>
                                        <td>Labour cost</td>
                                        <td><input ng-model="quoteData.actualLabourCosts"/></td>
                                    </tr>
                                    <tr>
                                        <td>Florist cost</td>
                                        <td><input ng-model="quoteData.actualFloristCosts"/></td>
                                    </tr>
                                    <tr>
                                        <td>Transport cost</td>
                                        <td><input ng-model="quoteData.actualTransportCosts"/></td>
                                    </tr>
                                    <tr>
                                        <td>Damages</td>
                                        <td><input ng-model="quoteData.damages"/></td>
                                    </tr>
                                    <tr>
                                        <td>Total costs</td>
                                        <td>{{calculateTotalExpenditure()}}</td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-lg-4">
                                <table border="1">
                                    <tr>
                                        <th colspan="3">Earnings</th>
                                    </tr>
                                    <tr>
                                        <td>Bill value</td>
                                        <td><input ng-model="quoteData.totalPaid"/></td>
                                    </tr>
                                    <tr>
                                        <td>Profit/Loss</td>
                                        <td>{{quoteData.totalPaid - quoteData.actualCost}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <table border="1">
                                    <tr>
                                        <td>Structures (Old ones)</td>
                                        <td>Cost</td>
                                        <td>95% recovery</td>
                                    </tr>
                                    <tr>
                                        <td>Artificial flowers</td>
                                        <td>Sell price</td>
                                        <td>90% recovery</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                </table>

                            </div>

                        </div>
                        <div class="row dataRow" ng-repeat="component in quoteData.components" style="margin-top: 4px;">
                            <div class="col-lg-12 componentHeaderRow">
                                <table width="100%">
                                    <tr>
                                        <td>
                                            {{component.name}} - {{component.qtty}}
                                        </td>
                                        <td>
                                            {{component.type}}
                                        </td>
                                        <td>Mandetory</td>
                                        <td>
                                            <input type="checkbox" ng-model="component.mandetory"/>
                                        </td>
                                        <td width="1%" align="right">
                                        <span style="text-align: right"
                                              ng-click="expandCollaspeComponent(component)">↓</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-12">

                            </div>
                            <div class="col-lg-12" style="padding-top: 5px;">
                                <div id="c-{{component.id}}" ng-show="component.visible && component.qtty > 0 ">
                                    <table border="1" width="100%">
                                        <tr>
                                            <td colspan="5">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <input ng-model="component.description" style="width: 100%;"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Item</th>
                                            <th>Desription</th>
                                            <th>Rate</th>
                                            <th>Qtty</th>
                                            <th width="10%">Cost</th>
                                        </tr>
                                        <tr class="sub-component-header">
                                            <td colspan="4" style="padding-top: 1em;">Configured Items</td>
                                            <td style="padding-top: 1em;">
                                                <h4><span style="color: #761c19;text-align: center"
                                                          ng-click="newItem(component,'configured')"><h4>➕</h4></span>
                                                </h4>
                                            </td>
                                        </tr>
                                        <tr ng-repeat="item in component.items">
                                            <td width="20%">
                                                <md-autocomplete flex="" required="" md-input-name="autocompleteField"
                                                                 md-input-minlength="2" md-input-maxlength="20"
                                                                 md-no-cache="ctrl.noCache"
                                                                 md-selected-item="item.name"
                                                                 md-search-text="item.searchText"
                                                                 md-items="item in ctrl.querySearch(item.searchText)"
                                                                 md-item-text="item.display"
                                                                 md-require-match=""
                                                                 md-floating-label="Configured item" name="confItemName"
                                                                 class="required">
                                                    <md-item-template>
                                                        <span md-highlight-text="ctrl.searchText">{{item.display}}</span>
                                                    </md-item-template>
                                                </md-autocomplete>
                                            </td>
                                            <td width="50%">
                                                <textarea ng-model="item.description" style="width: 100%"></textarea>
                                            </td>
                                            <td width="15%">
                                                {{isCustomComponent(item)}}
                                                {{showConfiguredItemRate(item)}}
                                                <span ng-show="!item.customComponent">{{item.rate}} </span>
                                                <input ng-show="item.customComponent" ng-model="item.rate"/>
                                            </td>
                                            <td width="5%"><input ng-model="item.qtty" class="required digit"/></td>
                                            <td width="10%">
                                                {{calculateItemHireRate(item)}}
                                                <span>{{item.hire_rate}} </span>
                                            </td>
                                        </tr>
                                        <tr class="sub-component-header">
                                            <td colspan="4" style="padding-top: 1em;">Basic Items</td>
                                            <td style="padding-top: 1em;">
                                                <h4><span style="color: #761c19;text-align: center"
                                                          ng-click="newItem(component, 'minor')"><h4>➕</h4></span></h4>
                                            </td>
                                        </tr>
                                        <tr ng-repeat="mi in component.minorItem">
                                            <td><input ng-model="mi.name" name="miName" class="required"/></td>
                                            <td><input ng-model="mi.description"/></td>
                                            <td>NA</td>
                                            <td>NA</td>
                                            <td><input ng-model="mi.cost" name="miCost" class="digit"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">C</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">&nbsp;</td>
                                        </tr>
                                        <tr class="sub-component-header">
                                            <td colspan="4" style="padding-top: 1em;">Fresh flowers</td>
                                            <td style="padding-top: 1em;"><h4><span
                                                            ng-click="newFlower(component)">➕</span></h4></td>
                                        </tr>
                                        <tr ng-repeat="flower in component.freshFlowers">
                                            <td>
                                                <select ng-options="f as f.name for f in freshFlowers track by f.name"
                                                        ng-model="flower.name"
                                                        ng-change="{{calculateSellRate(flower)}}" name="ff"
                                                        class="required"></select>
                                            </td>
                                            <td><input ng-model="flower.description"/></td>
                                            <td>
                                                {{flower.sellRate}}
                                            </td>
                                            <td><input ng-model="flower.qtty" name="ffQtty" class="required digit"/>
                                            </td>
                                            <td>{{flower.qtty * flower.sellRate}}</td>
                                        </tr>
                                        <tr class="calculated-field-row">
                                            <td>Total fresh flower cost</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{calculateFreshFlowerTotal(component)}}
                                                {{component.totalFreshFlowerCost}}
                                            </td>
                                        </tr>
                                        <tr class="sub-component-header">
                                            <td colspan="5" style="padding-top: 1em;">Artificial flowers</td>
                                        </tr>
                                        <tr>
                                            <td>Silk</td>
                                            <td>Description</td>
                                            <td>{{component.silkFlowerRate}}</td>
                                            <td><input ng-model="component.silkFlowers"></td>
                                            <td>{{component.silkFlowerRate * component.silkFlowers}}</td>
                                        </tr>
                                        <tr>
                                            <td>Non-Silk</td>
                                            <td>Description</td>
                                            <td>{{component.otherFlowerRate}}</td>
                                            <td><input ng-model="component.otherFlowers" name="of" class="digit"></td>
                                            <td>{{component.otherFlowers * component.otherFlowerRate}}</td>
                                        </tr>
                                        <tr>
                                            <td>Artificial leaves</td>
                                            <td colspan="2">description</td>
                                            <td><input ng-model="component.artificialLeaves" name="al" class="digit">
                                            <td>
                                        </tr>
                                        <tr class="calculated-field-row">
                                            <td>Total artificial</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{calculateArtificialFlowerTotal(component)}}
                                                {{component.totalArtificialFlowerCost}}
                                            </td>
                                        </tr>
                                        <tr class="calculated-field-row subtotal">
                                            <td>Total flower cost</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{component.totalFlowerCost}}
                                            </td>
                                        </tr>
                                        <tr class="calculated-field-row">
                                            <td>Total material cost</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{calculateTotalMaterialCost(component)}}
                                            </td>
                                        </tr>
                                        <tr class="calculated-field-row">
                                            <td>Total base cost</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{calculateBaseCost(component)}}
                                            </td>
                                        </tr>
                                        <tr class="calculated-field-row">
                                            <td>Labour</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{calculateLabourCostForComponent(component) }}
                                                {{component.totalLabourCost}}
                                            </td>
                                        </tr>
                                        <tr class="calculated-field-row">
                                            <td>Florist charge</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{calculateFloristCostForComponent(component) }}
                                                {{component.totalFloristCost}}
                                            </td>
                                        </tr>
                                        <tr class="calculated-field-row">
                                            <td>Other costs</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{calculateOtherCostsForComponent(component) }}
                                                {{component.totalOtherCost}}
                                            </td>
                                        </tr>
                                        <tr class="calculated-field-row subtotal">
                                            <td>Total before profit</td>
                                            <td>Total before profit</td>
                                            <td colspan="2"></td>
                                            <td>
                                                {{calculateTotalBeforeProfit(component) }}
                                                {{component.totalBeforeProfit}}
                                            </td>
                                        </tr>
                                        <tr class="calculated-field-row">
                                            <td>Profit</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{calculateProfitForComponent(component) }}
                                                {{component.totalProfit|number}}
                                            </td>
                                        </tr>
                                        <tr class="calculated-field-row total">
                                            <td>Component final cost</td>
                                            <td colspan="3"></td>
                                            <td>
                                                {{calculateComponentTotal(component)}}
                                                {{component.total|number}}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!--</div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>


    jQuery.validator.addClassRules({
        required: {
            required: true
        },
        digit: {
            digits: true
        }
    });

    function deleteQuote() {
        var $scope = angular.element($('[ng-controller]')).scope();
        $scope.delete();
    }

    function approveAndSave() {
        var $scope = angular.element($('[ng-controller]')).scope();
        $scope.approveAndSave();
    }

    function newQuote() {
        var $scope = angular.element($('[ng-controller]')).scope();
        $scope.createNewQuote();
    }

    $("#quotationForm").validate();


    $(':input').change(function (event) {
        if (event.target.id !== 'searchField') {
            var $scope = angular.element($('[ng-controller]')).scope();
            $scope.isDirty = true;
        }
    });


    (function () {
            'use strict';
            angular
                .module('MyApp', ['ngMaterial'])
                .controller('DemoCtrl', DemoCtrl);


            function DemoCtrl($scope, $http, $timeout, $q, $location) {
                var self = this;
                $scope.configuredItems = [];
                $scope.isQuotationLoaded = false;

                // list of `state` value/display objects
                // self.selectedItem = null;
                self.searchText = null;
                self.querySearch = querySearch;

                // ******************************
                // Internal methods
                // ******************************

                function querySearch(query) {
                    var results = query ? self.allConfiguredItems.filter(createFilterFor(query)) : self.allConfiguredItems;
                    var deferred = $q.defer();
                    $timeout(function () {
                        deferred.resolve(results);
                    }, 0, false);
                    return deferred.promise;
                }

                /**
                 * Build `states` list of key/value pairs
                 */
                function loadAll() {
                    return $scope.configuredItems.map(function (item) {
                        return {
                            value: item.name,
                            display: item.name
                        }
                    });
                }

                /**
                 * Create filter function for a query string
                 */
                function createFilterFor(query) {
                    var lowercaseQuery = angular.lowercase(query);

                    return function filterFn(item) {
                        // console.log("Query term: " + lowercaseQuery+" Current item search : " + item.value);
                        return (angular.lowercase(item.value).includes(lowercaseQuery));
                    };

                }

                $http.get('rest/api/v1/flower.php').then(function (response) {
                    $scope.freshFlowers = response.data;
                });


                $http.get('rest/api/v1/item.php').then(function (response) {
                    $scope.configuredItems = response.data;
                    self.allConfiguredItems = loadAll();
                });

                $scope.calculateTotalItemsCost = function (c) {
                    var total = 0;
                    angular.forEach(c.items, function (i) {
                        total = total + parseInt(i.qtty) * parseInt(i.rate);
                    });
                    return total;
                };


                $scope.calculateTotalMinorItemsCost = function (c) {
                    var total = 0;
                    angular.forEach(c.minorItem, function (i) {
                        total = total + parseInt(i.cost);
                    });
                    return total;
                };

                $scope.calculateBaseCost = function (c) {
                    return $scope.calculateTotalMaterialCost(c) + c.totalFlowerCost;
                };

                $scope.refreshAll = function (c) {
                    if (!$scope.quotation.isApproved) {
                        $http.get('rest/api/v1/flower.php').then(function (response) {
                            $scope.freshFlowers = response.data;

                            angular.forEach($scope.freshFlowers, function (f) {
                                var found = false;

                                angular.forEach($scope.quoteData.quotedFreshFlowerRates, function (quotedFF) {
                                    if (quotedFF.name === f.name) {
                                        found = true;
                                    }
                                });


                                if (!found) {
                                    $scope.quoteData.quotedFreshFlowerRates.push({
                                        'name': f.name,
                                        'buyRate': f.buyRate,
                                        'commRate': f.commRate,
                                        'qtty': 0,
                                        'sellRate': f.sellRate
                                    })
                                }
                            });
                        });

                        $http.get('rest/api/v1/item.php').then(function (response) {
                            $scope.configuredItems = response.data;
                            self.allConfiguredItems = loadAll();
                        });

                        $http.get('rest/api/v1/utility.php').then(function (response) {
                            $scope.quoteData.shopRunningCost.utilities = response.data;
                        });


                        $http.get('rest/api/v1/employee.php').then(function (response) {
                            $scope.quoteData.shopRunningCost.employees = response.data;
                        });
                    } else {
                        Notify('You cannot refresh master data on an approved quotation', null, null, 'danger');
                    }
                };


                $scope.refreshMasterData = function (c) {
                    $http.get('rest/api/v1/flower.php').then(function (response) {
                        $scope.freshFlowers = response.data;

                        angular.forEach($scope.freshFlowers, function (f) {
                            var found = false;

                            angular.forEach($scope.quoteData.quotedFreshFlowerRates, function (quotedFF) {
                                if (quotedFF.name === f.name) {
                                    found = true;
                                }
                            });


                            if (!found) {
                                $scope.quoteData.quotedFreshFlowerRates.push({
                                    'name': f.name,
                                    'buyRate': f.buyRate,
                                    'commRate': f.commRate,
                                    'qtty': 0,
                                    'sellRate': f.sellRate
                                })
                            }
                        });
                    });

                    $http.get('rest/api/v1/item.php').then(function (response) {
                        $scope.configuredItems = response.data;
                        self.allConfiguredItems = loadAll();
                    });
                };

                $scope.calculateTotalMaterialCost = function (c) {
                    var total = 0;
                    total = $scope.calculateTotalItemsCost(c) + $scope.calculateTotalMinorItemsCost(c);
                    c.totalMeterialCost = total;
                    return total;
                };


                $scope.calculateTotalFlowerCost = function (c) {
                    var total = 0;
                    total = $scope.calculateArtificialFlowerTotal(c) + $scope.calculateFreshFlowerTotal(c);
                    c.totalFlowerCost = total;
                    return total;
                };

                $scope.calculateArtificialFlowerTotal = function (c) {
                    var total = 0;
                    total = c.silkFlowerRate * c.silkFlowers + c.otherFlowerRate * c.otherFlowers + c.artificialLeaves * 1;
                    c.totalArtificialFlowerCost = total;
                    c.totalFlowerCost = c.totalFreshFlowerCost + c.totalArtificialFlowerCost;
                };

                $scope.calculateFreshFlowerTotal = function (c) {
                    var total = 0;
                    angular.forEach(c.freshFlowers, function (x) {
                        total = total + x.qtty * x.sellRate;
                    });
                    c.totalFreshFlowerCost = total;
                };


                $scope.calculateTotalLabour = function (ql) {
                    if ($scope.quoteData != null && ql != null) {
                        var total = 0;
                        $scope.quoteData.quotedLabourRates.actualCost = 0;
                        angular.forEach(ql.labour, function (l) {
                            total = total + (l.rate * l.qtty);
                            $scope.quoteData.quotedLabourRates.actualCost = parseInt($scope.quoteData.quotedLabourRates.actualCost) + parseInt(l.actual);
                        });
                        ql.cost = total;
                    }
                };

                $scope.calculateProfitForComponent = function (component) {
                    component.totalProfit = (component.totalBeforeProfit / 100) * component.profitPerc / component.qtty;
                };

                $scope.calculateFloristCostForComponent = function (component) {
                    if ($scope.quoteData != null) {
                        component.totalFloristCost = ($scope.quoteData.quotedFloristRates.cost / 100) * component.floristPerc / component.qtty;
                    }
                };

                $scope.calculateOtherCostsForComponent = function (component) {
                    component.totalOtherCost = ($scope.quoteData.quotedOtherCosts.total / 100) * component.otherCostPerc / component.qtty;
                };

                $scope.calculateLabourCostForComponent = function (component) {
                    component.totalLabourCost = ($scope.quoteData.quotedLabourRates.cost / 100) * component.labourPerc / component.qtty;
                };

                $scope.calculateFloristCost = function (florist) {
                    if ($scope.quoteData != null && florist != null) {
                        florist.cost = florist.rate * florist.qtty;
                    }
                };

                $scope.calculateTotalOtherCosts = function (quotedOtherCosts) {
                    if ($scope.quoteData != null && quotedOtherCosts != null) {
                        quotedOtherCosts.total = parseInt(quotedOtherCosts.totalShopRunningCostApplied) + parseInt(quotedOtherCosts.food) +
                            parseInt(quotedOtherCosts.paint) + parseInt(quotedOtherCosts.cleaning) + parseInt(quotedOtherCosts.misc) +
                            parseInt(quotedOtherCosts.hiddenTransport) + parseInt(quotedOtherCosts.flowerWastageApplied);
                    }
                };

                $scope.calculateTotalBeforeProfit = function (component) {
                    component.totalBeforeProfit = component.totalLabourCost + component.totalOtherCost + component.totalFloristCost + $scope.calculateBaseCost(component)
                };

                $scope.calculateShopRunningCost = function (shop) {
                    if ($scope.quoteData != null && shop != null) {
                        var total = 0;
                        angular.forEach(shop.utilities, function (utility) {
                            total = total + parseInt(utility.cost);
                        });

                        angular.forEach(shop.employees, function (emp) {
                            total = total + parseInt(emp.salary);
                        });
                        shop.total = total;
                    }
                };

                $scope.calculateComponentTotal = function (component) {
                    if ($scope.quotation.riskFactor == 0 || $scope.quotation.riskFactor == null) {
                        $scope.quotation.riskFactor = 1;
                    }
                    component.total = component.totalBeforeProfit * $scope.quotation.riskFactor + component.totalProfit;
                };


                $scope.calculateShopRunningCostApplied = function (quotedOtherCosts) {
                    if ($scope.quoteData != null && quotedOtherCosts != null) {
                        quotedOtherCosts.totalShopRunningCostApplied = parseInt($scope.quoteData.shopRunningCost.total) / 100 * parseInt(quotedOtherCosts.shopRunningCostPerc);
                    }
                };


                $scope.sumAllLabourPerc = function (components) {
                    var total = 0;
                    angular.forEach(components, function (c) {
                        total = total + parseInt(c.labourPerc);
                    });
                    return total;
                };

                $scope.sumAllProfit = function (components) {
                    var total = 0;
                    angular.forEach(components, function (c) {
                        if (c.mandetory) {
                            total = total + (parseInt(c.totalProfit) * parseInt(c.qtty));
                        }
                    });
                    return total;
                };

                $scope.createFinalQuote = function () {

                    if ($scope.quoteData != null) {
                        $scope.quoteData.quotationValue = $scope.quoteData.componentsCost + parseInt($scope.quoteData.transport);
                        return $scope.quoteData.quotationValue;
                    } else {
                        return 0;
                    }
                };

                $scope.sumAllComponents = function (components) {
                    var total = 0;
                    if ($scope.quoteData != null) {
                        angular.forEach(components, function (c) {
                            if (c.mandetory) {
                                total = total + (c.total * c.qtty);
                            }
                        });
                        $scope.quoteData.componentsCost = total;
                    }
                };

                $scope.sumAllFloristPerc = function (components) {
                    var total = 0;
                    angular.forEach(components, function (c) {
                        total = total + parseInt(c.floristPerc);
                    });
                    return total;
                };

                $scope.sumAllOtherCostPerc = function (components) {
                    var total = 0;
                    angular.forEach(components, function (c) {
                        total = total + parseInt(c.otherCostPerc);
                    });
                    return total;
                };

                $scope.calculateSellRate = function (flower) {
                    angular.forEach($scope.quoteData.quotedFreshFlowerRates, function (flowerRate) {
                        if (flowerRate.name === flower.name.name) {
                            flower.sellRate = flowerRate.sellRate;
                        }
                    });
                    $scope.calculateTotalFlowersQuoted();

                };

                $scope.calculateRiskFactor = function (months) {
                    if (months <= 6) {
                        return 0;
                    } else if (months > 6 && months <= 12) {
                        return months * 0.8;
                    } else if (months > 12 && months <= 18) {
                        return months * 0.9;
                    } else {
                        return months;
                    }
                };

                $scope.avgCaseFlowerProfit = function () {
                    var total = 0;
                    if ($scope.quoteData != null) {
                        angular.forEach($scope.quoteData.quotedFreshFlowerRates, function (flower) {
                            total = total + (flower.qtty * flower.sellRate) - (flower.qtty * flower.commRate);
                        });
                    }
                    return total;
                };

                $scope.bestCaseFlowerProfit = function () {
                    var total = 0;
                    if ($scope.quoteData != null) {
                        angular.forEach($scope.quoteData.quotedFreshFlowerRates, function (flower) {
                            total = total + (flower.qtty * flower.sellRate) - (flower.qtty * flower.buyRate);
                        });
                    }
                    return total;
                };

                $scope.calculateTotalFlowersQuoted = function () {
                    var total = 0;

                    angular.forEach($scope.quoteData.quotedFreshFlowerRates, function (qff) {
                        var found = false;
                        angular.forEach($scope.quoteData.components, function (component) {
                            if (component.mandetory) {

                                angular.forEach(component.freshFlowers, function (flower) {
                                    if (flower.name.name === qff.name) {
                                        found = true;
                                        total = total + parseInt(flower.qtty);
                                        qff.qtty = total;
                                    }
                                })
                            }
                        });
                        if (!found) {
                            qff.qtty = 0;
                        }
                    });
                };

                $scope.approveAndSave = function () {
                    if ($scope.quotation == null || $scope.quoteData == null) {
                        Notify('Cannot approve empty quotation', null, null, 'danger');
                    } else {
                        $scope.quotation.approved = 1;
                        $scope.isDirty = true;
                        Notify('Quotation approved. Please save to confirm', null, null, 'success');
                    }
                };

                $scope.createNewQuote = function () {
                    $http.put('rest/api/v1/quotation.php', $scope.data).then(function (response) {
                        $scope.quoteData = response.data;
                        $scope.quotationDate = new Date();
                        $scope.weddingDate = new Date();
                        $scope.quotation = {
                            'clientName': '',
                            'approved': 0
                        };
                        $scope.isDirty = false;
                        $scope.isQuotationLoaded = true;
                    });
                };


                $scope.newQuote = function () {
                    if ($scope.isDirty) {
                        Notify('<h4>You will loose unsaved data <button onclick="newQuote()">Ok</button></h4>', null, null, 'warning');
                    } else {
                        $scope.createNewQuote();
                    }
                };


                $scope.save = function () {
                    var isFormValid = $("#quotationForm").valid();
                    //1st we validate
                    if (isFormValid) {
                        $scope.quotation.data = $scope.quoteData;
                        var res = $http.post('rest/api/v1/quotation.php', $scope.quotation);
                        res.success(function (data, status, headers, config) {
                            console.log(JSON.stringify(data));
                            if (data.code === 200) {
                                Notify("Save success", null, null, 'success');
                                $scope.quotation.data = $scope.quoteData;
                                $scope.isDirty = false;
                            } else {
                                Notify("Save failed" + JSON.stringify(data.data), null, null, 'danger');
                            }
                        });

                        res.error(function (data, status, headers, config) {
                            Notify("Save failed: " + JSON.stringify({data: data}), null, null, 'danger');
                        });
                    } else {
                        Notify("Validation failed", null, null, 'warning');

                    }
                };

                $scope.search = function () {
                    $http.get('rest/api/v1/quotation.php/?name=' + $scope.name).then(function (response) {
                        $scope.searchResult = response.data;
                    });
                };

                $scope.toDate = function (dateStr) {
                    return new Date(dateStr);
                };

                $scope.diffDate = function (date1, date2) {
                    var a = new Date(date1);
                    var b = new Date(date2);

                    var d1Y = a.getFullYear();
                    var d2Y = b.getFullYear();
                    var d1M = a.getMonth();
                    var d2M = b.getMonth();

                    return (d2M + 12 * d2Y) - (d1M + 12 * d1Y);
                };


                $scope.showQuotation = function (quotation) {
                    if (!$scope.isDirty) {
                        $http.get('rest/api/v1/quotation.php/' + quotation.id).then(function (response) {
                            $scope.quotation = quotation;
                            $scope.quoteData = response.data;
                            $scope.quotationDate = new Date(quotation.quotationDate);
                            $scope.weddingDate = new Date(quotation.weddingDate);
                            $scope.isQuotationLoaded = true;
                        });
                    } else {
                        Notify('Quotation is not saved. Please save it first.', null, null, 'danger');
                    }
                };

                $scope.expandCollaspeComponent = function (component) {
                    component.visible = !component.visible;
                };

                $scope.expandCollaspeMasterData = function () {
                    $scope.toggleMasterData = !$scope.toggleMasterData;
                };

                $scope.expandCollaspeActual = function () {
                    $scope.toggleActual = !$scope.toggleActual;
                };

                $scope.newFlower = function (component) {
                    component.freshFlowers.push({
                        'name': '',
                        'sellRate': 0
                    })
                };


                $scope.scrollTo = function (id) {
                    $('html, body').animate({
                        scrollTop: $("#" + id).offset().top
                    }, 1000);
                };

                $scope.newItem = function (component, type) {
                    //todo no need to call server. locally create the json
                    $http.put('rest/api/v1/item.php?type=' + type).then(function (response) {
                        if (type === 'minor') {
                            component.minorItem.push(response.data);
                        } else {
                            component.items.push(response.data);
                        }
                        $scope.isDirty = true;
                    });
                };

                $scope.showConfiguredItemRate = function (item) {
                    if (!item.customComponent) {
                        angular.forEach($scope.configuredItems, function (ci) {
                            if (item.name === '' || item.name == null) {
                                item.rate = 0;
                            } else if (ci.name === item.name.value) {
                                item.rate = ci.hire_cost;
                            }
                        });
                    }
                };

                $scope.calculateArtificialFlowerCost = function () {
                    var total = 0;
                    if ($scope.quoteData != null) {
                        angular.forEach($scope.quoteData.components, function (component) {
                            if (component.mandetory) {
                                total = total + (component.silkFlowerRate * component.silkFlowers) + (component.otherFlowers * component.otherFlowerRate) + component.artificialLeaves;
                            }
                        });

                        $scope.quoteData.artificialFlowerCost = total;
                    }
                    return total;
                };

                $scope.calculateStructureCost = function () {
                    var total = 0;
                    if ($scope.quoteData != null) {
                        angular.forEach($scope.quoteData.components, function (component) {
                            if (component.mandetory) {
                                angular.forEach(component.items, function (item) {
                                    total = total + item.rate * item.qtty;
                                });
                            }
                        });

                        $scope.quoteData.structureCost = total;
                    }
                    return total;
                };

                $scope.isCustomComponent = function (item) {
                    item.customComponent = false;
                    if (item.name != null && item.name.value != null) {
                        var n = item.name.value.toUpperCase();
                        if (n === 'NEW STRUCTURE' || n === 'NEW DECORE' || n === 'CUSTOMIZATION') {
                            item.customComponent = true;
                        }
                    }
                };


                $scope.clone = function () {
                    if ($scope.quotation == null || $scope.quoteData == null) {
                        Notify('Cannot clone empty quotation', null, null, 'danger');
                    } else {
                        $scope.quotation.id = null;
                        Notify('Quotation cloned. Please change client name to distinguish this from original', null, null, 'success');
                    }
                };

                $scope.delete = function () {
                    alert('delete confirmed');
                };

                $scope.confirmDelete = function () {
                    if ($scope.quotation.id == null) {
                        Notify('Cannot delete unsaved quotation.', null, null, 'danger');
                    } else {
                        Notify('Are you sure you want to delete <button onclick="deleteQuote()">Ok</button>', null, null, 'warning');
                    }

                };

                $scope.calculateFreshFlowerProfit = function () {
                    var cost = 0;
                    var sale = 0;
                    if ($scope.quoteData != null) {
                        angular.forEach($scope.quoteData.quotedFreshFlowerRates, function (quotedFF) {
                            cost = cost + parseInt(quotedFF.actual);
                            sale = sale + ((quotedFF.qtty * quotedFF.sellRate));
                        });
                        console.log("Sale : " + sale + " Cost : "  + cost);

                        $scope.quoteData.freshFlowerProfit = sale - cost;
                        $scope.quoteData.freshFlowerCost = cost;
                        return $scope.quoteData.freshFlowerProfit;
                    } else {
                        return 0;
                    }
                };


                $scope.calculateTotalExpenditure = function () {
                    var total = 0;
                    if ($scope.quoteData != null) {
                        total = parseInt($scope.quoteData.newStructuresCost) + parseInt($scope.quoteData.actualOtherCosts)
                            + parseInt($scope.quoteData.actualLabourCosts) + parseInt($scope.quoteData.actualFloristCosts)
                            + parseInt($scope.quoteData.actualTransportCosts) + parseInt($scope.quoteData.freshFlowerCost)
                            + parseInt($scope.quoteData.damages) + parseInt($scope.quoteData.artificialFlowerCost) / 100 * 10;

                        $scope.quoteData.actualCost = total;
                    }
                    return total;

                };


                $scope.goToInventory = function () {
                    if (!$scope.isDirty) {
                        window.location = "inventory.php";
                    } else {
                        Notify("Document is not saved. Please save it ")
                    }
                };

                $scope.calculateItemHireRate = function (item) {
                    item.hire_rate = item.rate * item.qtty;
                };

                $scope.newComponent = function () {
                    $http.put('rest/api/v1/component.php').then(function (response) {
                        var component = response.data;
                        component.id = Math.round((Math.random() * 100) + 1);
                        $scope.quoteData.components.push(component);
                        $scope.isDirty = true;
                    });
                };

            }

        }
    )
    ();


</script>
</body>
</html>