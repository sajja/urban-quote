<?php
require_once('authenticate.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
    <!-- Angular Material Library -->
    <link rel="stylesheet" href="css/angular-material.min.css">
    <meta charset="UTF-8">
    <title>Inventory master data</title>
</head>
<body>

<div ng-controller="shopController" class="autocompletedemoFloatingLabel" ng-app="firstApplication">
    <div>Header</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <div>Utilities</div>
                <table border="1" width="100%">
                    <tr>
                        <th colspan="2">Type</th>
                        <th>Cost</th>
                        <td><img src="images/new.png" height="30" width="30" ng-click="newUtility()"/></td>
                    </tr>
                    <tr ng-repeat="utility in utilities">
                        <td colspan="2"><input type="hidden" ng-model="utility.id"/> <input ng-model="utility.name"/>
                        </td>
                        <td><input ng-model="utility.cost"/></td>
                        <td><img ng-show="!utility.new_item" src="images/delete.png" height="30" width="30"
                                 ng-click="confirmDelete()"/></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right">
                            <img src="images/save.jpg" height="30" width="30" ng-click="saveUtility()"/>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-3">
                <div>Employees</div>
                <table border="1" width="100%">
                    <tr>
                        <th colspan="2">Name</th>
                        <th>Salary</th>
                        <td><img src="images/new.png" height="30" width="30" ng-click="newEmployee()"/></td>
                    </tr>
                    <tr ng-repeat="employee in employees">
                        <td colspan="2"><input type="hidden" ng-model="employee.id"/> <input ng-model="employee.name"/>
                        </td>
                        <td><input ng-model="employee.salary"/></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right">
                            <img src="images/save.jpg" height="30" width="30" ng-click="saveEmployee()"/>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6">
                <div>Flower costs</div>
                <table border="1">
                    <tr>
                        <th>Name</th>
                        <th>Buy rate</th>
                        <th>Comm rate</th>
                        <th>Sell rate</th>
                        <th><img src="images/new.png" height="30" width="30" ng-click="newFlower()"/></th>
                    </tr>
                    <tr ng-repeat="flower in freshFlowers">
                        <td><input type="hidden" ng-model="flower.id"/> <input ng-model="flower.name"/>
                        <td><input ng-model="flower.buyRate"/></td>
                        <td><input ng-model="flower.comBuyRate"/></td>
                        <td><input ng-model="flower.sellRate"/></td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">
                            <img src="images/save.jpg" height="30" width="30" ng-click="saveFlowers()"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div>Labour rates</div>
                <table border="1" width="100%">
                    <tr>
                        <th colspan="2">Type</th>
                        <th>Cost</th>
                        <td><img src="images/new.png" height="30" width="30" ng-click="newLabour()"/></td>
                    </tr>
                    <tr ng-repeat="lab in labourer">
                        <td colspan="2"><input type="hidden" ng-model="lab.id"/> <input ng-model="lab.type"/>
                        </td>
                        <td><input ng-model="lab.rate"/></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right">
                            <img src="images/save.jpg" height="30" width="30" ng-click="saveLabour()"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
<script language="javascript">
    angular.module('firstApplication', ['ngMaterial', 'ngMessages']).controller('shopController', shopController);

    function shopController($scope, $http) {
        $http.get('rest/api/v1/utility.php').then(function (response) {
            $scope.utilities = response.data;
        });

        $http.get('rest/api/v1/employee.php').then(function (response) {
            $scope.employees = response.data;
        });

        $http.get('rest/api/v1/labour.php').then(function (response) {
            $scope.labourer = response.data;
        });

        $http.get('rest/api/v1/flower.php').then(function (response) {
            $scope.freshFlowers = response.data;
        });

        $scope.saveUtility = function () {
            $http.post('rest/api/v1/utility.php', $scope.utilities).then(function (response) {
                $scope.utilities = response.data;
            });
        };

        $scope.saveEmployee = function () {
            $http.post('rest/api/v1/employee.php', $scope.employees).then(function (response) {
                $scope.employees = response.data;
                Notify("Save success", null, null, 'success');
            });
        };
        $scope.saveLabour = function () {
            $http.post('rest/api/v1/labour.php', $scope.labourer).then(function (response) {
                $scope.labourer = response.data;
                Notify("Save success", null, null, 'success');
                Notify('You cannot refresh master data on an approved quotation', null, null, 'danger');
            });
        };

        $scope.saveFlowers = function () {
            $http.post('rest/api/v1/flower.php', $scope.freshFlowers).then(function (response) {
            });
        };

        $scope.newUtility = function () {
            $scope.utilities.push({
                'name': '',
                'cost': '',
                'new_item': true
            });
        };

        $scope.newEmployee = function () {
            $scope.employees.push({
                'name': '',
                'salary': '',
                'new_item': true
            });
        };

        $scope.newLabour = function () {
            $scope.labourer.push({
                'type': '',
                'rate': '',
                'new_item': true
            });
        };

        $scope.newFlower = function () {
            $scope.freshFlowers.push({
                'name': '',
                'buyRate': 0,
                'comBuyRate': 0,
                'sellRate': 0,
                'new_item': true
            });
        };
    }
</script>
</html>
