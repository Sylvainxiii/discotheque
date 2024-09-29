<?php

// Fonction pour charger les variables depuis le fichier .env
function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception("Le fichier .env n'existe pas.");
    }

    $lignes = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lignes as $ligne) {
        if (strpos(trim($ligne), '#') === 0) {
            continue; // Ignorer les commentaires
        }

        // Assigne le nom de la donnée d'environnement ainsi que sa valeur aux variables $name et $value
        list($name, $value) = explode('=', $ligne, 2);

        // Nettoyer les espaces autour du nom et de la valeur
        $name = trim($name);
        $value = trim($value);

        // Définir la variable d'environnement
        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}
