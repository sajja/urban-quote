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
    <title>Inventory master data</title>
</head>
<style>
    input {
        background-color: lightcyan;
        width: 100%;
    }

</style>
<body>
<div ng-controller="inventoryController" class="autocompletedemoFloatingLabel" ng-app="firstApplication">
    <div>
        <TABLE width="100%">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>Filter</td>
                            <td width="50%"><input ng-model="searchTerm" style="width:100%"/></td>
                            <td align="center">
                                <button ng-click="search()">search</button>
                            </td>
                            <td colspan="4" align="right"><img src="images/new.png" height="30" width="30" ng-click="addNew()"/></td>
                        </tr>
                    </table>

                </td>
            </tr>
        </TABLE>
    </div>
    <div style="padding:10px">
        <table border="1" width="100%">
            <tr>

                <th>Name</th>
                <th>Description</th>
                <th>Purchase cost</th>
                <th>Est.Rec. time</th>
                <th>Hire cost</th>
                <th>Operation</th>
            </tr>
            <tr ng-repeat="i in filteredInventory">
                <input type="hidden" ng-model="i.id"/>
                <td><input ng-model="i.name"/></td>
                <td><input ng-model="i.description"/></td>
                <td><input ng-model="i.total"/></td>
                <td><input ng-model="i.recovery_time"/></td>
                <td><input ng-model="i.hire_cost"/></td>
                <td> <img ng-show="!i.new_item" src="images/delete.png" height="30" width="30" ng-click="confirmDelete()"/> </td>
            </tr>
            <tr>
                <td align="right" colspan="6">
                    <img src="images/save.jpg" height="30" width="30" ng-click="save()"/>
                </td>
            </tr>
        </table>

    </div>
</div>
</body>
<script language="javascript">
    angular.module('firstApplication', ['ngMaterial', 'ngMessages']).controller('inventoryController', inventoryController);

    function inventoryController($scope, $http) {
        $scope.filteredInventory = [];

        $scope.save = function () {
            $http.post('rest/api/v1/item.php/', $scope.filteredInventory).then(function (response) {
                var res = $http.post('/urban/rest/api/v1/quotation.php', $scope.quotation);
                res.success(function (data, status, headers, config) {
                    Notify("Save success", null, null, 'success');
                });

                res.error(function (data, status, headers, config) {
                    Notify("Save failed: " + JSON.stringify({data: data}), null, null, 'danger');
                });
            });
        };

        $scope.addNew = function () {
            $scope.filteredInventory.push({
                'name': '',
                'description': '',
                'total': '',
                'recovery_time': '',
                'new_item': true,
                'hire_cost': ''
            });
        };

        $scope.search = function () {
            $http.get('rest/api/v1/item.php/?filter=' + $scope.searchTerm).then(function (response) {
                $scope.filteredInventory = response.data;
            });
        }
    }
</script>
</html>
