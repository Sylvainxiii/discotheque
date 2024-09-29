<?php
require_once "dotEnv.php";
function dbconnect()
{
    // Charge les variables d'environnement
    loadEnv($_SERVER['DOCUMENT_ROOT'] . '/.env');

    $dsn = 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');

    // Création de l'objet de connexion qui va nous permettre de faire des requêtes SQL
    $pdo = new \PDO($dsn, $user, $pass);
    return $pdo;
}
