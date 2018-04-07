<?php
require_once('authenticate.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory master data</title>
</head>
<body>
<div>
    Urban setm inventory
</div>
<div>
   <table border="1">
       <tr>
           <td>Add new</td>
       </tr>
   </table>
</div>
<div>
    <TABLE border="1">
        <tr>
            <td>Filter</td>
            <td></td>
        </tr>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Purchase cost</th>
            <th>Est.Rec. time</th>
            <th>Hire cost</th>
        </tr>
        <tr></tr>
    </TABLE>
</div>
</body>
<script language="javascript">
    angular.module('firstApplication+-`', ['ngMaterial', 'ngMessages']).controller('inventoryController', inventoryController);

    function inventoryController($scope) {
    }
</script>
</html>
