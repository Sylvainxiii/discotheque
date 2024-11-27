<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once("../includes/header.php");

// Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
}
?>

<body>
    <?php
    // Inclusion de la navbar
    include_once("../includes/navbar.php")
    ?>

    <div class="container">
        <h1></h1>
        <div class="text-center">
            <input type="hidden" id="idVersion" value=<?= $_GET['id'] ?>>

            <img class="img-detail" id="version-edit-image">

            <table class="table">
                <tbody>
                    <tr>
                        <th colspan="2" class="th-left">Année: </th>
                        <td colspan="2" class="td-left">
                            <div id="version-edit-sortie-annee"></div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Genre: </th>
                        <td colspan="2" class="td-left">
                            <div class="version-edit-input" id="version-edit-genre"></div>
                        </td>
                    </tr>
                    <tr>
                        <th class="th-left">Label: </th>
                        <td class="td-left">
                            <div class="version-edit-input" id="version-edit-label"></div>
                        </td>
                        <th class="th-left">Label Ref: </th>
                        <td class="td-left">
                            <div class="version-edit-input" id="version-edit-reference"></div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Format: </th>
                        <td colspan="2" class="td-left">
                            <div class="version-edit-input" id="version-edit-format"></div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Pays: </th>
                        <td colspan="2" class="td-left">
                            <div class="version-edit-input" id="version-edit-pays"></div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Sortie: </th>
                        <td colspan="2" class="td-left">
                            <div class="version-edit-input" id="version-edit-pressage-annee"></div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" class="th-left">Edition: </th>
                        <td colspan="2" class="td-left">
                            <div class="version-edit-input" id="version-edit-type"></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" id="edit-commandes" class="flex-row">
                            <div class="btn btn-primary" id="edit-version-btn">Editer l'Album</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="th-center">Track</th>
                    <th scope="col" class="th-center">Titre</th>
                    <th scope="col" class="th-center">Durée</th>
                    <th scope="col" class="th-center"></th>
                    <th scope="col" class="th-center"></th>
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
<script type="module" src="../assets/js/version_album.js"></script>

</html>