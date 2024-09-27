<?php

function dbconnect()
{

    $dsn = 'mysql:host=localhost;dbname=bd_discotheque_d';
    $user = 'root';
    $pass = '';

    // Création de l'objet de connexion qui va nous permettre de faire des requêtes SQL
    $pdo = new \PDO($dsn, $user, $pass);
    return $pdo;
}
