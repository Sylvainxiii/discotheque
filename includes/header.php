<?php
session_start();

// Appel du fichier de fonctions
require_once("fonction.php");
include_once("dbconnect.php");

$pdo = dbconnect();

// Vérifiez le nom de la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);

if (($current_page !== 'login.php' && $current_page !== 'utilisateur_add.php') && !isset($_SESSION['email'])) {
    header('Location: ../vue/login.php');
    exit(); // Ajoutez exit() pour arrêter l'exécution du script
} else {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $idUser =  userId($email, $pdo);
        $user =  userInfo($_SESSION["email"], $pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discothèque</title>

    <link rel="stylesheet" href="../assets/css/style_disco.css">

</head>