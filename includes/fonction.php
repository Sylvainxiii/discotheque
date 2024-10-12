<?php

// FUNCTION MENU ---------------------------------------------------------------------------------------------------------------------------------------------------------

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
    $stmt = $pdo->prepare($sql);
    $params = ["value" => $valeur];
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_NUM);
    return $result[0];
}

// FUNCTION USER ---------------------------------------------------------------------------------------------------------------------------------------------------------

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

// FUNCTION LISTE UTILISATEUR ---------------------------------------------------------------------------------------------------------------------------------------------------------

// Fonctions pour l'affichage de la collection de l'utilisateur
function getListe($email, $pdo)
{
    $sql = "SELECT lis_id, ver_id,  uti_email, alb_titre, ver_ref, gen_nom, for_nom, art_nom, lis_fk_media_eta_id, lis_fk_pochette_eta_id, ver_image FROM d_utilisateur_uti
    INNER JOIN d_liste_lis ON uti_id = lis_fk_uti_id 
    INNER JOIN d_version_ver ON lis_fk_ver_id = ver_id 
    INNER JOIN d_album_alb ON ver_fk_alb_id = alb_id 
    INNER JOIN d_j_art_alb_jaa ON alb_id = jaa_fk_alb_id	 
    INNER JOIN d_artiste_art  ON jaa_fk_art_id = art_id 
    INNER JOIN d_genre_gen  ON alb_fk_gen_id = gen_id
    INNER JOIN d_format_for  ON ver_fk_for_id = for_id
    WHERE uti_email = :email";
    $stmt = $pdo->prepare($sql);
    $params = ["email" => $email];
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}


// Ajout de la version de l'album choisie dans la collection de l'utilisateur
function addToList($utilisateurid, $versionid, $pdo)
{
    $sql = "INSERT INTO d_liste_lis (lis_fk_uti_id, lis_fk_ver_id) VALUES (:utilisateurid, :versionid)";
    $stmt = $pdo->prepare($sql);
    $params = ["utilisateurid" => $utilisateurid, "versionid" => $versionid];
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

// Edition de l'état de l'exemplaire d'un album dans la collection de l'utilisateur
function editetat($pdo)
{
    $sql = "UPDATE d_liste_lis SET lis_fk_media_eta_id = :etatMedia, lis_fk_pochette_eta_id = :etatPochette
    WHERE lis_id = :listeId";
    $stmt = $pdo->prepare($sql);
    $params = ["listeId" => $_GET["listeId"], "etatMedia" => $_GET["etatMedia"], "etatPochette" => $_GET["etatPochette"]];
    $stmt->execute($params);
    return;
}

// Suppression d'une ligne de la collection de l'Utilisateur
function supListe($listeId, $pdo)
{
    $sql = "DELETE FROM d_liste_lis WHERE 	lis_id = :listeId";
    $stmt = $pdo->prepare($sql);
    $params = ["listeId" => $listeId];
    $stmt->execute($params);
    return;
}

// FUNCTION VERSION D'ALBUM ---------------------------------------------------------------------------------------------------------------------------------------------------------

// Fonctions pour l'affichage des details d'un album
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
}

// Fonction pour recherche d'une version d'un album en vue de l'ajouter dans la collection utilisateur la recherche s'effectue soit via la ref (plus précise) soit via le titre de l'album
function getVersionByRef($versionref, $pdo)
{
    $sql = "SELECT 	ver_id, ver_ref, alb_titre, art_nom, ver_press_annee, ver_press_pays FROM d_version_ver 
    INNER JOIN d_album_alb ON ver_fk_alb_id = alb_id  
    INNER JOIN d_j_art_alb_jaa ON alb_id  = jaa_fk_alb_id	 
    INNER JOIN d_artiste_art  ON jaa_fk_art_id = art_id 
    WHERE ver_ref LIKE :versionref";
    $stmt = $pdo->prepare($sql);
    $params = ["versionref" => $versionref];
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function getVersionByName($albumtitre, $pdo)
{
    $sql = "SELECT ver_id, 	ver_ref, alb_titre, art_nom, ver_press_annee, ver_press_pays FROM d_version_ver 
    INNER JOIN d_album_alb ON ver_fk_alb_id = alb_id  
    INNER JOIN d_j_art_alb_jaa ON alb_id  = jaa_fk_alb_id	 
    INNER JOIN d_artiste_art  ON jaa_fk_art_id = art_id 
    WHERE alb_titre LIKE :albumtitre";
    $stmt = $pdo->prepare($sql);
    $params = ["albumtitre" => $albumtitre];
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function getVersionByArtist($artistenom, $pdo)
{
    $sql = "SELECT ver_id, ver_ref, alb_titre, art_nom, ver_press_annee, ver_press_pays FROM d_version_ver 
    INNER JOIN d_album_alb ON ver_fk_alb_id = alb_id 
    INNER JOIN d_j_art_alb_jaa ON alb_id = jaa_fk_alb_id 
    INNER JOIN d_artiste_art ON jaa_fk_art_id = art_id
    WHERE art_nom LIKE :artistenom";
    $stmt = $pdo->prepare($sql);
    $params = ["artistenom" => $artistenom];
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// Création Album
function createVersion($pdo)
{
    $sql = "INSERT INTO d_version_ver(ver_fk_alb_id, ver_ref,ver_fk_for_id, ver_fk_lab_id,ver_press_annee,ver_press_pays,ver_fk_edi_id, ver_image, ver_date_add ) 
    VALUES (:albumid, :versionref, :formatid, :labelid, :versionpressyear, :versionpresspays, :versioneditid, :versionimg,:versiondateajout)";
    $stmt = $pdo->prepare($sql);
    $params = [
        "albumid" => $_POST["albumId"],
        "versionref" => $_POST["versionRef"],
        "formatid" => $_POST["formatId"],
        "labelid" => $_POST["labelId"],
        "versionpressyear" => $_POST["versionPressYear"],
        "versionpresspays" => $_POST["versionPressPays"],
        "versioneditid" => $_POST["versionEditId"],
        "versionimg" => "img/" . $_POST["versionImg"],
        "versiondateajout" => date('Y-m-d H:i:s')
    ];
    $stmt->execute($params);
    $newversionId = $pdo->LastInsertId();


    if ($_FILES["versionImg"]['name'] !== "") {
        uploadImage($newversionId, $pdo);
    }
    return $newversionId;
}

/**
 * Fonction pour uploader une image d'une version d'un album
 * 
 * @param int $versionId l'id de la version d'un album
 * @param PDO $pdo l'objet de connection pdo
 * @return bool
 */
function uploadImage($versionId, $pdo)
{
    if (isset($_FILES['image'])) {
        $tmpName = $_FILES['image']['tmp_name'];
        $name = $_FILES['image']['name'];

        // Créer le chemin absolu
        $uploadDir = realpath(__DIR__ . '/../uploads');
        $destination = $uploadDir . DIRECTORY_SEPARATOR . $name;

        // S'assurer que le dossier existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($tmpName, $destination)) {
            $sql = "UPDATE d_version_ver SET ver_image = :newfilename WHERE ver_id = :versionId";
            $stmt = $pdo->prepare($sql);
            $params = [
                "newfilename" => 'uploads/' . $name,
                "versionId" => $versionId
            ];
            return $stmt->execute($params);
        }

        return false;
    }
    return false;
}

/**
 * Fonction pour modifier une version d'un album
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
                $fileName = $fileMatch[1] ?? 'unknown';
                list(, $content) = explode("\r\n\r\n", $part, 2);

                // Écrire directement dans le dossier uploads
                $uploadDir = realpath(__DIR__ . '/../uploads');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $destination = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
                if (file_put_contents($destination, trim($content))) {
                    // Mise à jour du chemin du fichier dans la base de données
                    $sql = "UPDATE d_version_ver SET ver_image = :newfilename WHERE ver_id = :versionId";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        "newfilename" => 'uploads/' . $fileName,
                        "versionId" => $data["versionId"]
                    ]);
                }
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

    echo json_encode(["id" => $data["versionId"]], JSON_PRETTY_PRINT);
    return;
}

// Suppression d'une version d'un album existante
function supVersion($versionid, $pdo)
{
    $sql = "DELETE FROM d_version_ver WHERE ver_id = :versionid";
    $stmt = $pdo->prepare($sql);
    $params = ["versionId" => $versionid];
    $stmt->execute($params);
    return;
}

// FUNCTION CHANSON ---------------------------------------------------------------------------------------------------------------------------------------------------------

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

// FUNCTION TITRE ALBUM ---------------------------------------------------------------------------------------------------------------------------------------------------------

// Création d'un nouveau titre d'album (un titre peut être identique à plusieurs versions du même album)
function createAlbum($pdo)
{
    $sql = "INSERT INTO d_album_alb(alb_titre, alb_fk_gen_id,alb_sortie_annee) 
    VALUES (:albumtitre, :genreid, :albumansortie);";
    $stmt = $pdo->prepare($sql);
    $params = ["albumtitre" => $_POST["albumTitre"], "genreid" => $_POST["genreId"], "albumansortie" => $_POST["albumanSortie"]];
    $stmt->execute($params);
    $newAlbumId = $pdo->LastInsertId();
    return $newAlbumId;
}

// FUNCTION ARTISTE ---------------------------------------------------------------------------------------------------------------------------------------------------------

// Création d'un nouvel artiste
function createArtiste($pdo)
{
    $sql = "INSERT INTO d_artiste_art(art_nom, art_pays) 
    VALUES (:artistenom, :artistepays)";
    $stmt = $pdo->prepare($sql);
    $params = ["artistenom" => $_POST["artisteNom"], "artistepays" => $_POST["artistePays"]];
    $stmt->execute($params);
    return;
}

// Permet d'appairer un artiste avec un titre d'album
function addArtiste($artisteId, $idnomalbum, $pdo)
{
    $sql = "INSERT INTO d_j_art_alb_jaa (jaa_fk_alb_id, jaa_fk_art_id) VALUES (:albumid, :artisteid)";
    $stmt = $pdo->prepare($sql);
    $params = ["albumid" => $idnomalbum, "artisteid" => $artisteId];
    $stmt->execute($params);
    return;
}
