<?php

// Inclusion du fichier de fonctions et de la config de connexion BDD
include_once("function.php");
include_once("dbconnect.php");

$request_method = $_SERVER["REQUEST_METHOD"];
$pdo = dbconnect();

// TODO: supprimer les id via GET dans l'url du routeur, à remplacer par données dans le body de la requête. Remplacer SWITCH() par MATCH() pour une syntaxe plus courte et une comparaison plus précise et solide
switch ($request_method) {
    case 'GET':
        $id = intval($_GET["idVersion"]);
        getChansons($id, $pdo);
        break;
    case 'DELETE':
        $id = intval($_GET["idChanson"]);
        deleteChanson($id, $pdo);
        break;
    case 'POST':
        addChanson($pdo);
        break;
    case 'PUT':
        editchanson($pdo);
        break;
}
