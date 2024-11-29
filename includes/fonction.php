<?php
// 1-FUNCTION MENU 
// 2-FUNCTION USER
// 3-FUNCTION LISTE UTILISATEUR
// 4-FUNCTION VERSION D'ALBUM
// 5-FUNCTION CHANSON
// 6-FUNCTION TITRE ALBUM
// 7-FUNCTION ARTISTE
// 8-FUNCTION LABEL
// 9-FUNCTION UTILITAIRES

// 1-FUNCTION MENU ---------------------------------------------------------------------------------------------------------------------------------------------------------

// Selecteur dynamique avec taille auto, peut être déployé n'importe ou
function menuSelect($label, $postvalue, $table, $pdo, $def = 0)
{
    $sql = "SELECT * FROM $table";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datamenu = $stmt->fetchAll(PDO::FETCH_NUM);

    if ($label !== "") {
        echo "<label for=" . $postvalue . " class='form-label'>" . $label . ":</label>";
    }

    echo "<select class='form-select' name = " . $postvalue . " id = " . $postvalue . " aria-label='Default select example'>";
    for ($i = 0; $i < count($datamenu); $i++) {
        if ($datamenu[$i][0] == $def) {
            echo "<option selected value=" . $datamenu[$i][0] . ">" . $datamenu[$i][1] . "</option>";
        } else {
            echo "<option value=" . $datamenu[$i][0] . ">" . $datamenu[$i][1] . "</option>";
        }
    }
    echo  "</select>";
    return;
}

/**
 * Recupère les données d'une table pour definir les options d'un select
 * 
 * @param array $parametres
 * @param PDO $pdo
 * @return void
 */
function getSelect($parametres, $pdo)
{
    $table = $parametres["table"];
    $sql = "SELECT * FROM $table";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datamenu = $stmt->fetchAll(PDO::FETCH_NUM);

    header("Content-Type: application/json");
    echo json_encode($datamenu, JSON_PRETTY_PRINT);
}

// 2-FUNCTION USER ---------------------------------------------------------------------------------------------------------------------------------------------------------

// Vérifie la connection (password et email)
function isValid($email, $password, $pdo)
{
    $sql = "SELECT uti_email, uti_mdp FROM d_utilisateur_uti WHERE uti_email = :email";
    $stmt = $pdo->prepare($sql);
    $params = ["email" => $email];
    $stmt->execute($params);
    $result = $stmt->fetchAll();

    // si le compte de $result est supérieur à zéro, ça veut dire 
    // qu'il y a des données dans le tableau
    if (count($result) > 0) {

        // Dans une second temps on vérifie si le mot de pass est bon en 
        // le comparant avec celui de la base de données
        // la fonction password_verify s'occupe  de comparer $password qui est en claire
        // et $result[0]['password'] (originaire de la table utilisateur) qui est chiffré
        if (password_verify($password, $result[0]['uti_mdp'])) {
            return true;
        }
    }
    return false;
}

// Ajout d'un utilisateur
function addUser($email, $password, $pdo)
{
    $sql = "INSERT INTO d_utilisateur_uti(uti_email, uti_mdp, uti_date_add) VALUES (:email,:password,:utilisateurdateadd)";
    $stmt = $pdo->prepare($sql);
    $params = ["email" => $email, "password" => $password, "utilisateurdateadd" => date('Y-m-d H:i:s')];
    $stmt->execute($params);
    $utilisateurid = $pdo->LastInsertId();
    return $utilisateurid;
}

// récupère L'ID de l'utilisateur en fonction de l'email (pllus utilisée actuellement)
function userId($email, $pdo)
{
    $sql = "SELECT uti_id, uti_email FROM d_utilisateur_uti 
    WHERE uti_email = :email";
    $stmt = $pdo->prepare($sql);
    $params = ["email" => $email];
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Récupère les infos de l'utilisateur
function userInfo($email, $pdo)
{
    $sql = "SELECT uti_id, uti_email, uti_nom, uti_prenom, uti_naissance_date, uti_avatar FROM d_utilisateur_uti
    WHERE uti_email = :email";
    $stmt = $pdo->prepare($sql);
    $params = ["email" => $email];
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Edition du compte utilisateur
function editUser($pdo)
{
    $sql = "UPDATE d_utilisateur_uti SET uti_nom = :utilisateurNom, uti_prenom = :utilisateurPrenom, uti_naissance_date = :utilisateurNaissance, uti_date_edit = :utilisateurdateedit
    WHERE uti_email = :email";
    $stmt = $pdo->prepare($sql);
    $params = ["utilisateurNom" => $_POST["utilisateurNom"], "utilisateurPrenom" => $_POST["utilisateurPrenom"], "utilisateurNaissance" => $_POST["utilisateurNaissance"], "email" => $_SESSION['email'], "utilisateurdateedit" => date('Y-m-d H:i:s')];
    $stmt->execute($params);
    return;
}

function addImage64($pdo)
{
    if ($_FILES['utilisateurImg']['name'] !== "") {
        // Récupérer le contenu de l'image
        $file = $_FILES['utilisateurImg']['tmp_name'];
        $image = base64_encode(file_get_contents($file));

        // Insérer l'image dans la base de données
        $sql = "UPDATE d_utilisateur_uti SET uti_avatar = :utiAvatar
            WHERE uti_id = 1";
        $stmt = $pdo->prepare($sql);
        $params = ["utiAvatar" => $image];
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}

// 3-FUNCTION LISTE UTILISATEUR ---------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Récupère la liste des albums de l'utilisateur
 * 
 * @param array $parametres (id de l'utilisateur)
 * @param PDO $pdo
 */
function getListe($parametres, $pdo)
{
    $sql = "SELECT lis_id, ver_id, alb_titre, ver_ref, gen_nom, for_nom, art_nom, lis_fk_media_eta_id, lis_fk_pochette_eta_id, ver_image FROM d_utilisateur_uti
    INNER JOIN d_liste_lis ON uti_id = lis_fk_uti_id 
    INNER JOIN d_version_ver ON lis_fk_ver_id = ver_id 
    INNER JOIN d_album_alb ON ver_fk_alb_id = alb_id 
    INNER JOIN d_j_art_alb_jaa ON alb_id = jaa_fk_alb_id	 
    INNER JOIN d_artiste_art  ON jaa_fk_art_id = art_id 
    INNER JOIN d_genre_gen  ON alb_fk_gen_id = gen_id
    INNER JOIN d_format_for  ON ver_fk_for_id = for_id
    WHERE uti_id = :userid";
    $stmt = $pdo->prepare($sql);
    $params = ["userid" => $parametres["id"]];
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($result, JSON_PRETTY_PRINT);
    return $result;
}

/**
 * Ajout d'un album à la collection de l'utilisateur
 * 
 * @param PDO $pdo
 * @return void
 */
function addToList($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "INSERT INTO d_liste_lis (lis_fk_uti_id, lis_fk_ver_id) VALUES (:utilisateurid, :versionid)";
    $stmt = $pdo->prepare($sql);
    $params = ["utilisateurid" => $data["user"], "versionid" => $data["version"]];
    $stmt->execute($params);
    header("Content-Type: application/json");
    echo json_encode(["ajout" => true], JSON_PRETTY_PRINT);
    return;
}

/**
 * Edition de l'état de l'exemplaire d'un album dans la collection de l'utilisateur
 * 
 * @param PDO $pdo
 * @return void
 */
function editEtat($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $colonneAEditer = $data["type"] === "media" ? "lis_fk_media_eta_id" : "lis_fk_pochette_eta_id";

    $sql = "UPDATE d_liste_lis SET $colonneAEditer = :valeurEtat
    WHERE lis_id = :listeId";
    $stmt = $pdo->prepare($sql);
    $params = ["listeId" => $data["id"], "valeurEtat" => $data["etat"]];
    $stmt->execute($params);
    header("Content-Type: application/json");
    echo json_encode(["modification" => true], JSON_PRETTY_PRINT);
    return;
}

// Suppression d'une ligne de la collection de l'Utilisateur
function deleteListe($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "DELETE FROM d_liste_lis WHERE 	lis_id = :listeId";
    $stmt = $pdo->prepare($sql);
    $params = ["listeId" => $data["id"]];
    $stmt->execute($params);
    header("Content-Type: application/json");
    echo json_encode(["id" => $data["id"]], JSON_PRETTY_PRINT);
    return;
}

// 4-FUNCTION VERSION D'ALBUM ---------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Fonction pour l'affichage des details d'un album
 * 
 * @param array $parametres - contient l'id de l'album
 * @param PDO $pdo
 */
function versionDetail($parametres, $pdo)
{
    $sql = "SELECT ver_id, 	ver_fk_alb_id, 	alb_titre, alb_sortie_annee, art_nom, gen_nom, ver_ref as reference, ver_press_pays as pays, ver_press_annee as pressageAnnee,
    ver_fk_edi_id, edi_type as type, ver_fk_for_id, for_nom as format, ver_fk_lab_id, lab_nom as label, ver_image as image
    FROM d_version_ver INNER JOIN d_album_alb ON ver_fk_alb_id = alb_id 
    INNER JOIN d_j_art_alb_jaa ON alb_id = jaa_fk_alb_id	 
    INNER JOIN d_artiste_art ART ON jaa_fk_art_id = art_id 
    INNER JOIN d_genre_gen  ON alb_fk_gen_id = gen_id 
    INNER JOIN d_format_for  ON ver_fk_for_id = for_id 
    INNER JOIN d_label_lab  ON ver_fk_lab_id = lab_id 
    INNER JOIN d_edition_edi  ON ver_fk_edi_id = edi_id
    WHERE ver_id = :versionid";
    $stmt = $pdo->prepare($sql);
    $params = ["versionid" => $parametres["id"]];
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($result, JSON_PRETTY_PRINT);
    return;
}

/**
 * Fonction qui permet de rechercher les versions correzspondantes aux cirtères de recherche
 * 
 */
function getVersion($criteres, $pdo)
{
    // Construction de la requête de base
    $sql = "SELECT ver_id, ver_ref, alb_titre, art_nom, ver_press_annee, ver_press_pays 
            FROM d_version_ver 
            INNER JOIN d_album_alb ON ver_fk_alb_id = alb_id  
            INNER JOIN d_j_art_alb_jaa ON alb_id = jaa_fk_alb_id     
            INNER JOIN d_artiste_art ON jaa_fk_art_id = art_id";

    // Construction dynamique de la clause WHERE
    $clausesWhere = [];
    $params = [];

    if (!empty($criteres)) {
        // Mappage des critères avec leurs colonnes correspondantes
        $columnMapping = [
            'chercherreference' => 'ver_ref',
            'cherchertitre' => 'alb_titre',
            'chercherartiste' => 'art_nom'
        ];

        // Construction des conditions WHERE
        foreach ($criteres as $cle => $valeur) {
            if (!empty($valeur)) {  // Ignore les critères vides
                $clausesWhere[] = "{$columnMapping[$cle]} LIKE :$cle";
                $params[$cle] = "%" . $valeur . "%";
            }
        }

        // Ajout de la clause WHERE si des conditions existent
        if (!empty($clausesWhere)) {
            $sql .= " WHERE " . implode(' AND ', $clausesWhere);
        }
    }

    // Exécution de la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($result, JSON_PRETTY_PRINT);
}

/**
 * Fonction qui permet d'ajouter une version d'un album
 * Dans ce cas, les données étant envoyées en formData via la méthode POST, les données sont récupérées dans $_POST et $_FILES
 * Dans le cas où l'image est présente, elle est déplacée dans le dossier uploads
 * 
 * @param PDO $pdo
 * @return void
 */
function addVersion($pdo)
{
    if (isset($_FILES['image'])) {
        $tmpName = $_FILES['image']['tmp_name'];
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $name = securiserNomFichier("img-" . $_POST["reference"] . "." . $extension);

        // Créer le chemin absolu
        $uploadDir = realpath(__DIR__ . '/../uploads');
        $destination = $uploadDir . DIRECTORY_SEPARATOR . $name;

        // S'assurer que le dossier existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        move_uploaded_file($tmpName, $destination);
    }

    $sql = "INSERT INTO d_version_ver(ver_fk_alb_id, ver_ref,ver_fk_for_id, ver_fk_lab_id,ver_press_annee,ver_press_pays,ver_fk_edi_id, ver_image, ver_date_add ) 
    VALUES (:albumid, :reference, :formatId, :labelId, :versionPressYear, :versionPressPays, :versionEditId, :versionimg,:versiondateajout)";
    $stmt = $pdo->prepare($sql);
    $params = [
        "albumid" => getId($_POST["titre"], "d_album_alb", "alb_titre", $pdo),
        "reference" => $_POST["reference"],
        "formatId" => getId($_POST["format"], "d_format_for", "for_nom", $pdo),
        "labelId" => getId($_POST["label"], "d_label_lab", "lab_nom", $pdo),
        "versionPressYear" => $_POST["pressageAnnee"],
        "versionPressPays" => $_POST["pays"],
        "versionEditId" => getId($_POST["type"], "d_edition_edi", "edi_type", $pdo),
        "versionimg" => "uploads/" . $name,
        "versiondateajout" => date('Y-m-d H:i:s'),
    ];
    $stmt->execute($params);
    $newversionId = $pdo->LastInsertId();

    echo json_encode(["id" => $newversionId], JSON_PRETTY_PRINT);
    return;
}

/**
 * Fonction pour modifier une version d'un album
 * Dans ce cas, les données étant envoyées en formData via la méthode PUT, les données sont récupérées dans php://input
 * 
 * @param PDO $pdo
 * @return array
 */
function editVersion($pdo)
{
    $rawData = file_get_contents("php://input");

    // Récupérer le boundary depuis le Content-Type
    $contentType = $_SERVER['CONTENT_TYPE'];
    // $boundary est le séparateur de chaque partie dans le contenu de la requête, il est défini dans la première ligne juste après le Content-Type
    $boundary = substr($contentType, strpos($contentType, "boundary=") + 9);

    // Séparer les parties du multipart
    $parts = array_slice(explode("--" . $boundary, $rawData), 1, -1);
    $data = [];

    // Parser chaque partie
    foreach ($parts as $part) {
        // Si c'est un fichier
        if (strpos($part, 'filename=') !== false) {
            preg_match('/name="([^"]+)"/i', $part, $matches);
            if ($matches[1] === 'image') {
                // Extraire le nom du fichier et le contenu
                preg_match('/filename="([^"]+)"/i', $part, $fileMatch);
                $originalFileName = $fileMatch[1] ?? 'unknown';
                list(, $content) = explode("\r\n\r\n", $part, 2);

                // Stocker les informations de l'image pour un traitement ultérieur
                $imageInfo = [
                    'content' => trim($content),
                    'originalFileName' => $originalFileName
                ];
            }
        }
        // Si c'est un champ normal
        else {
            preg_match('/name="([^"]+)"/i', $part, $matches);
            if ($matches[1]) {
                list(, $value) = explode("\r\n\r\n", $part, 2);
                $data[$matches[1]] = trim($value);
            }
        }
    }

    // Mise à jour de la base de données
    $sql = "UPDATE d_version_ver 
        SET  	ver_ref = :reference, ver_fk_for_id = :formatId, ver_fk_lab_id = :labelId, ver_press_annee = :versionPressYear, 
        ver_press_pays = :versionPressPays, ver_fk_edi_id = :versionEditId, ver_date_edit = :versiondateedit
        WHERE ver_id = :versionId";
    $stmt = $pdo->prepare($sql);
    $params = [
        "versionId" => $data["versionId"],
        "reference" => $data["reference"],
        "formatId" => getId($data["format"], "d_format_for", "for_nom", $pdo),
        "labelId" => getId($data["label"], "d_label_lab", "lab_nom", $pdo),
        "versionPressYear" => $data["pressageAnnee"],
        "versionPressPays" => $data["pays"],
        "versionEditId" => getId($data["type"], "d_edition_edi", "edi_type", $pdo),
        "versiondateedit" => date('Y-m-d H:i:s')
    ];
    $stmt->execute($params);

    if ($imageInfo) {
        // Écrire directement dans le dossier uploads
        $uploadDir = realpath(__DIR__ . '/../uploads');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Obtenir l'extension du fichier original
        $extension = pathinfo($imageInfo['originalFileName'], PATHINFO_EXTENSION);

        // Nouveau nom de fichier
        $newFileName = securiserNomFichier('img-' . $data['reference'] . '.' . $extension);

        $destination = $uploadDir . DIRECTORY_SEPARATOR . $newFileName;
        if (file_put_contents($destination, $imageInfo['content'])) {
            // Mise à jour du chemin du fichier dans la base de données
            $sql = "UPDATE d_version_ver SET ver_image = :newfilename WHERE ver_id = :versionId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "newfilename" => 'uploads/' . $newFileName,
                "versionId" => $data["versionId"]
            ]);
        }
    }

    echo json_encode(["id" => $data["versionId"]], JSON_PRETTY_PRINT);
    return;
}

// 5-FUNCTION CHANSON ---------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Fonction pour récupérer la liste des chanson relative à un album.
 *
 * @param int $idVersion l'ID de la version de l'album dont les chansons sont recherchées.
 * @param PDO $pdo l'objet de connection pdo.
 * @return array tableau associatif contenant les données des chansons.
 */
function getChansons($parametres, $pdo)
{
    $sql = "SELECT * FROM d_chanson_cha WHERE cha_fk_ver_id = :idversion ORDER BY cha_track ASC";
    $stmt = $pdo->prepare($sql);
    $params = ["idversion" => $parametres["id"]];
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($result, JSON_PRETTY_PRINT);
}

/**
 * Création d'une nouvelle chanson.
 *
 *
 * @param PDO $pdo l'objet de connection pdo.
 * @return void
 */
function addChanson($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "INSERT INTO d_chanson_cha(cha_titre, cha_duree, cha_fk_ver_id, cha_track) 
    VALUES (:chansontitre, :chansonduree, :idversion, :chansontracknr)";
    $stmt = $pdo->prepare($sql);
    $params = [
        "chansontitre" => $data["titre"],
        "chansonduree" => $data["duree"],
        "idversion" => $data["idVersion"],
        "chansontracknr" => $data["track"]
    ];
    $stmt->execute($params);
    $lastInsertId = $pdo->lastInsertId();
    echo json_encode(["id" => $lastInsertId], JSON_PRETTY_PRINT);
    return;
}

/**
 * Edition d'une chanson.
 *
 *
 * @param PDO $pdo l'objet de connection pdo.
 * @return void
 */
function editchanson($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "UPDATE d_chanson_cha SET cha_titre = :chansonTitre, cha_fk_ver_id = :idVersion, cha_duree = :chansonDuree, cha_track = :chansonTrackNr
    WHERE cha_id = :chansonId";
    $stmt = $pdo->prepare($sql);
    $params = [
        "chansonId" => $data["idChanson"],
        "chansonTitre" => $data["titre"],
        "idVersion" => $data["idVersion"],
        "chansonDuree" => $data["duree"],
        "chansonTrackNr" => $data["track"]
    ];
    $stmt->execute($params);

    echo json_encode(["id" => $data["idChanson"]], JSON_PRETTY_PRINT);
    return;
}

/**
 * Suppression d'une chanson.
 *
 *
 * @param PDO $pdo l'objet de connection pdo.
 * @return void
 */
function deleteChanson($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "DELETE FROM d_chanson_cha WHERE cha_id = :idchanson";
    $stmt = $pdo->prepare($sql);
    $params = ["idchanson" => $data["idChanson"]];
    $stmt->execute($params);

    echo json_encode(["id" => $data["idChanson"]], JSON_PRETTY_PRINT);
    return;
}

// 6-FUNCTION TITRE ALBUM ---------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Création d'un nouveau titre d'album (un titre peut être identique à plusieurs versions du même album)
 * 
 * @param PDO $pdo l'objet de connection pdo.
 * @return void
 */
function createAlbum($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "INSERT INTO d_album_alb(alb_titre, alb_fk_gen_id,alb_sortie_annee) 
    VALUES (:albumtitre, :genreid, :albumansortie);";
    $stmt = $pdo->prepare($sql);
    $params = ["albumtitre" => $data["titre"], "genreid" => $data["genre"], "albumansortie" => $data["annee"]];
    $stmt->execute($params);
    $newAlbumId = $pdo->LastInsertId();

    $sql = "INSERT INTO d_j_art_alb_jaa (jaa_fk_alb_id, jaa_fk_art_id) VALUES (:albumid, :artisteid)";
    $stmt = $pdo->prepare($sql);
    $params = ["albumid" => $newAlbumId, "artisteid" => $data["artiste"]];
    $stmt->execute($params);

    echo json_encode(["id" => $newAlbumId], JSON_PRETTY_PRINT);
    return;
}

// 7-FUNCTION ARTISTE ---------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Création d'un nouvel artiste
 * 
 * @param PDO $pdo l'objet de connection pdo.
 * @return void
 */
function createArtiste($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "INSERT INTO d_artiste_art(art_nom, art_pays) 
    VALUES (:artistenom, :artistepays)";
    $stmt = $pdo->prepare($sql);
    $params = ["artistenom" => $data["nom"], "artistepays" => $data["pays"]];
    $stmt->execute($params);
    $lastInsertId = $pdo->lastInsertId();
    echo json_encode(["id" => $lastInsertId], JSON_PRETTY_PRINT);
    return;
}

// 8-FUNCTION LABEL ---------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Création d'un nouveau label
 * 
 * @param PDO $pdo l'objet de connection pdo.
 * @return void
 */
function createLabel($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "INSERT INTO d_label_lab(lab_nom) 
    VALUES (:labelnom)";
    $stmt = $pdo->prepare($sql);
    $params = ["labelnom" => $data["nom"]];
    $stmt->execute($params);
    $lastInsertId = $pdo->lastInsertId();
    echo json_encode(["id" => $lastInsertId], JSON_PRETTY_PRINT);
    return;
}

// 9-FUNCTION UTILITAIRE ---------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Récupère l'id d'une valeur dans une table
 * 
 * @param string $valeur
 * @param string $table
 * @param string $champs
 * @param PDO $pdo
 * @return int
 */
function getId($valeur, $table, $champs, $pdo)
{
    $sql = "SELECT * FROM $table WHERE $champs = :value";
    if ($valeur == "") {
        $sql .= " OR $champs is null";
    }
    $stmt = $pdo->prepare($sql);
    $params = ["value" => $valeur];
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_NUM);
    return $result[0];
}

/**
 * Fonction pour sécuriser un nom de fichier à partir de son nom original.
 * 
 * @param string $nom Le nom du fichier à sécuriser.
 * @return string Le nom du fichier sécurisé.
 */
function securiserNomFichier($nom)
{
    // Translittération des caractères
    $nom = iconv('UTF-8', 'ASCII//TRANSLIT', $nom);

    // Supprime les caractères potentiellement dangereux
    $nom = preg_replace([
        '/[^a-zA-Z0-9-_.]/',
        '/\.{2,}/', // Bloque les tentatives de traversée de répertoire
        '/^\./', // Bloque les fichiers cachés
    ], '', $nom);

    // Limite la longueur
    $nom = substr($nom, 0, 255);

    // Convertit en minuscules
    $nom = strtolower($nom);

    return $nom;
}
