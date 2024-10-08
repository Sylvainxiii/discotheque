<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once("../includes/header.php");

// Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
}

// Récupère les données de la version d'album
// TODO: passer en AJAX
if (isset($_GET['versionId'])) {
    $data = versionDetail($_GET['versionId'], $pdo);
}

$infopage = pathinfo(__FILE__);
?>

<body>
    <?php
    // Inclusion de la navbar
    include_once("../includes/navbar.php")
    ?>

    <div class="container">
        <h1>Album: <?= $data['alb_titre'] ?> par <?= $data['art_nom'] ?></h1>
        <div class="text-center">
            <input type="hidden" id="idVersion" value=<?= $_GET['versionId'] ?>>
            <img src="../<?= $data['ver_image'] ?>" alt="pochette de l'album" class="img-detail">
            <table class="table">
                <tbody>
                    <tr>
                        <th colspan="2" class="th-left">Année: </th>
                        <td colspan="2"><?= $data['alb_sortie_annee'] ?></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Genre: </th>
                        <td colspan="2"><?= $data['gen_nom'] ?></td>
                    </tr>
                    <tr>
                        <th class="th-left">Label: </th>
                        <td><?= $data['lab_nom'] ?></td>
                        <th class="th-left">Label Ref: </th>
                        <td><?= $data['ver_ref'] ?></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Format: </th>
                        <td colspan="2"><?= $data['for_nom'] ?></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Pays: </th>
                        <td colspan="2"><?= $data['ver_press_pays'] ?></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Sortie: </th>
                        <td colspan="2"><?= $data['ver_press_annee'] ?></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Edition: </th>
                        <td colspan="2"><?= $data['edi_type'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div class="btn btn-primary"><a href="version_edit.php?versionId=<?= $_GET['versionId'] ?>">Editer l'Album</a></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="th-left">Track</th>
                    <th scope="col" class="th-left">Titre</th>
                    <th scope="col" class="th-left">Durée</th>
                    <th scope="col" class="th-left"></th>
                    <th scope="col" class="th-left"></th>
                </tr>
            </thead>
            <tbody id="liste-chansons">
            </tbody>
        </table>
        <div>
            <div class="btn btn-primary" id="add-chanson-btn">Ajouter des chansons</a></div>
        </div>
    </div>

</body>
<script type="module" src="../assets/js/fonctions_rest.js"></script>
<script type="module" src="../assets/js/composants.js"></script>
<script type="module" src="../assets/js/<?= $infopage['filename'] ?>.js"></script>

</html>