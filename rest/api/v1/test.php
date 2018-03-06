<?php
require_once('objects.php');
require_once('db.php');
require('abstract_rest.php');
function handleGet()
{
    $conn = new mysqli('localhost', 'root', 'root', 'urbanste_master');

    $objs = array();

    if ($result = $conn->query('select data from quotation_data where id=3')) {
        $row_cnt = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            echo $row['data'];
        }
        $result->close();
    } else {
        echo("Error description: " . mysqli_error($conn));
    }
    return $objs;


    echo json_encode(loadItems($r, $conn));
    $conn->close();
}
?>
