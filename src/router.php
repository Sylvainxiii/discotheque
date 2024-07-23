<?php

include("src/__function.php");

switch ($request_method) {
    case 'GET':
        $id = intval($_GET["id"]);
        getProduct($pdo, $id);
        break;
    case 'DELETE':
        $id = intval($_GET["id"]);
        deleteProduct($pdo, $id);
        break;
    case 'POST':
        addProduct($pdo);
        break;
    case 'PUT':
        editProduct($pdo);
        break;
}
