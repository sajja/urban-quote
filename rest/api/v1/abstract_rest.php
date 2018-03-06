<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    handleGet();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handlePost();
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    handlePut();
}
?>
