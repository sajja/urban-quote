<?php
$dbSettings = parse_ini_file("db.ini");

$host = $dbSettings["host"];
$db = $dbSettings["db"];
$user = $dbSettings["user"];
$pass = $dbSettings["password"];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $conn = new mysqli($host, $user, $pass, $db);
    handleGet($conn);
    $conn->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli($host, $user, $pass, $db);
    handlePost($conn);
    $conn->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $conn = new mysqli($host, $user, $pass, $db);
    handlePut($conn);
    $conn->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $conn = new mysqli($host, $user, $pass, $db);
    handleDelete($conn);
    $conn->close();
}
?>
