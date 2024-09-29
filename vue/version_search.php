<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once("../includes/header.php");

// Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
}

// TODO: Passer la recherche en AJAX
// Ici nous récupérons les listes d'album suivant les critères renseignés, les variables définies avant l'appel des fonctions permettent d'effectuer une recherche avec
// seulement quelques caractères.
if (isset($_POST['versionRef'])) {
    $versionref = ($_POST['versionRef'] !== "") ? ('%' . $_POST['versionRef'] . '%') : ($_POST['versionRef']);
    $dataref = getVersionByRef($versionref, $pdo);
} else {
    $dataref = [];
}

if (isset($_POST['albumTitre'])) {
    $albumtitre = ($_POST['albumTitre'] !== "") ? ('%' . $_POST['albumTitre'] . '%') : ($_POST['albumTitre']);
    $datanom = getVersionByName($albumtitre, $pdo);
} else {
    $datanom = [];
}

if (isset($_POST['artisteNom'])) {
    $artiste = ($_POST['artisteNom'] !== "") ? ('%' . $_POST['artisteNom'] . '%') : ($_POST['artisteNom']);
    $datartist = getVersionByArtist($artiste, $pdo);
} else {
    $datartist = [];
}

// $data sera la liste retournée d'album peu importe si la recherche s'effectue via la ref, le titre de l'album ou l'artiste
// si les trois champs sont renseignés , la priorité se fera sur la recherche via ref qui est plus précise puis via le titre de l'album et enfin par l'artiste
if (count($dataref) > 0) {
    $data = $dataref;
} else {
    if (count($datanom) > 0) {
        $data = $datanom;
    } else {
        if (count($datartist) > 0) {
            $data = $datartist;
        } else {
            $data = [];
        }
    }
}

// Ajoute le disque à la liste de l'utilisateur une fois que l'id de l'album a été inclus dans la variable $_GET, puis redirection vers la liste utilisateur.
if (isset($_GET["versionId"])) {
    $email = $_SESSION['email'];
    $user =  userId($email, $pdo);

    addToList($user["uti_id"], $_GET['versionId'], $pdo);
    header('Location: ../index.php');
}
?>

<body>
    <?php
    // Inclusion de la navbar
    include_once("../includes/navbar.php");
    ?>
    <div class="container">
        <h1>Recherche d'Album</h1>
        <form class="form-control-line" action="version_search.php" method="post">

            <div class="input-group">
                <label class="label-hidden" for="versionRef">Ref</label>
                <div class="input-group-text">Ref</div>
                <input type="text" class="input-group-control" name="versionRef" id="versionRef" placeholder="Ref">
            </div>


            <div class="input-group">
                <label class="label-hidden" for="albumTitre">Titre</label>
                <div class="input-group-text">Titre de l'album</div>
                <input type="text" class="input-group-control" name="albumTitre" id="albumTitre" placeholder="Titre">
            </div>


            <div class="input-group">
                <label class="label-hidden" for="artisteNom">Artiste</label>
                <div class="input-group-text">Artiste</div>
                <input type="text" class="input-group-control" name="artisteNom" id="artisteNom" placeholder="Artiste">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <div class="text-center">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ref</th>
                        <th scope="col">Album</th>
                        <th scope="col">Artiste</th>
                        <th scope="col">Sortie</th>
                        <th scope="col">Pays</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($data); $i++) {
                    ?>
                        <tr>
                            <th scope="row"><?= ($i + 1) ?></th>
                            <td><a href="version_album.php?versionId=<?= $data[$i]['ver_id'] ?>"><?= $data[$i]['ver_ref'] ?></a></td>
                            <td><?= $data[$i]['alb_titre'] ?></td>
                            <td><?= $data[$i]['art_nom'] ?></td>
                            <td><?= $data[$i]['ver_press_annee'] ?></td>
                            <td><?= $data[$i]['ver_press_pays'] ?></td>
                            <td class="btn-td"><a href="version_search.php?versionId=<?= $data[$i]['ver_id'] ?>" class='btn btn-primary'>Ajouter à ma liste</a></td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>

        <div class="btn btn-primary"><a href="version_creation.php">Créer un Album</a></div>
    </div>
</body>

</html>