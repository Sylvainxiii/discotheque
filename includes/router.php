<?php

include_once("fonction.php");
include_once("dbconnect.php");

/**
 * Routeur
 * 
 * Suivant la méthode de requête et l'entité,
 * récupère dans la variable $route, la fonction correspondante.
 * Pour la méthode GET, vérifie si les paramètrees requis sont présent et les récupère pour les passer en paramètre de la fonction.
 */

$pdo = dbconnect();
$request_method = $_SERVER["REQUEST_METHOD"];
$entite = $_GET["entite"];

// Chaque paramètre peut être requis ou optionnel
$routes = [
    'GET' => [
        'chanson' => [
            'fonction' => 'getChansons',
            'parametres' => [
                'id' => 'requis'
            ]
        ],
        'select' => [
            'fonction' => 'getSelect',
            'parametres' => [
                'table' => 'requis'
            ]
        ],
        'version' => [
            'fonction' => 'versionDetail',
            'parametres' => [
                'id' => 'requis'
            ]
        ],
        'listeVersion' => [
            'fonction' => 'getVersion',
            'parametres' => [
                'chercherreference' => 'optionnel',
                'cherchertitre' => 'optionnel',
                'chercherartiste' => 'optionnel'
            ]
        ],
        'liste' => [
            'fonction' => 'getListe',
            'parametres' => [
                'id' => 'requis'
            ]
        ]
    ],
    'DELETE' => [
        'chanson' => ['fonction' => 'deleteChanson'],
        'liste' => ['fonction' => 'deleteListe'],
    ],
    'POST' => [
        'chanson' => ['fonction' => 'addChanson'],
        'liste' => ['fonction' => 'addToList'],
        'version' => ['fonction' => 'addVersion'],
        'artiste' => ['fonction' => 'createArtiste'],
        'album' => ['fonction' => 'createAlbum'],
        'label' => ['fonction' => 'createLabel']
    ],
    'PUT' => [
        'chanson' => ['fonction' => 'editchanson'],
        'version' => ['fonction' => 'editVersion'],
        'liste' => ['fonction' => 'editEtat']
    ]
];

if (isset($routes[$request_method][$entite])) {
    $route = $routes[$request_method][$entite];
    $function = $route['fonction'];

    if ($request_method === 'GET') {
        $parametres = valideEtGetParametres($route);
        $function($parametres, $pdo);
    } else {
        $function($pdo);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}

/**
 * Vérifie si les paramètres requis sont présents dans la requête et retourne un tableau associatif contenant les paramètres requis.
 * 
 * @param array $route Les paramètres de la route.
 * @return array Un tableau associatif contenant les paramètres requis.
 */
function valideEtGetParametres($route)
{
    $parametre = [];
    $parametresRequis = $route['parametres'] ?? [];

    // Vérification si les parametres requis sont présents dans la requête
    foreach ($parametresRequis as $param => $requisition) {
        if ($requisition === 'requis' && !isset($_GET[$param])) {
            http_response_code(400);
            echo json_encode(['error' => "Missing required parameter: $param"]);
            exit;
        } elseif (!empty($_GET[$param])) {
            $parametre[$param] = $_GET[$param];
        }
    }
    return $parametre;
}
