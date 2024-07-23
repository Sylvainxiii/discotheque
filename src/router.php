<?php

include("__function.php");
include_once("dbconnect.php");
$request_method = $_SERVER["REQUEST_METHOD"];
$pdo = dbconnect();

switch ($request_method) {
    case 'GET':
        $id = intval($_GET["id"]);
        getChansons($id, $pdo);
        break;
    case 'DELETE':
        $id = intval($_GET["id"]);
        supChanson($pdo, $id);
        break;
    case 'POST':
        createChanson($pdo);
        break;
    case 'PUT':
        editchanson($id, $pdo);
        break;
}
