<?php
session_start();

// Appel du fichier de fonctions
require_once("fonction.php");
include_once("dbconnect.php");

$pdo = dbconnect();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discothèque</title>

    <link rel="stylesheet" href="../assets/css/style_disco.css">

</head>