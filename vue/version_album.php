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

    <!-- Modales pour les actions sur les chansons -->
    <!-- TODO: génération en full js via un composant -->
    <div class="modale hidden" id="modale-chanson">
        <div class="close-btn" id="close-modale">X</div>
        <input type="hidden" id="modale-id-chanson">
        <div id="modale-action-chanson">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"> <label class="form-label">Track Nr</label>
                        </th>
                        <th scope="col"> <label class="form-label">Titre de la chanson</label>
                        </th>
                        <th scope="col"> <label class="form-label">Durée</label>
                        </th>
                    </tr>
                </thead>
                <tbody id="modale-liste-chanson">
                </tbody>
            </table>
            <div class="btn btn-primary" id="modale-add-chanson">Créer</div>

            <div id='nombre-chansons'>
                <label for="nChanson" class="form-label">Nr de Chansons</label>
                <input type="number" class="form-control form-control-color" id="nChanson" value="1">
            </div>
        </div>
        <div class="modal-flex-column hidden" id="modale-delete-chanson">
            <div class="texte-modale">Voulez-vous vraiment supprimer cette chanson?</div>
            <div class="modal-flex-row">
                <div class="btn btn-danger" id="modale-confirm-delete-chanson">Confirmer</div>
                <div class="btn btn-primary" id="modale-confirm-cancel-chanson">Annuler</div>
            </div>
        </div>

    </div>

</body>
<script src="../assets/js/album_version.js"></script>

</html>