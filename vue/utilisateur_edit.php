<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once('../includes/header.php');

// Stockage an base64 de l'image d'avatar dans la bdd si ce dernier est envoyé dans le formulaire
// TODO: passer en AJAX
if (isset($_FILES['utilisateurImg'])) {
    addImage64($pdo);
}

// Edite les informations de l'utilisateur
// TODO: passer en AJAX
if (isset($_POST['utilisateurPrenom'])) {
    editUser($pdo);
    header("location: utilisateur_detail.php");
}
?>

<body>
    <?php
    // Inclusion de la navbar
    include_once("../includes/navbar.php");
    ?>
    <div class="container">
        <h1>Mes Informations Personnelles</h1>

        <form enctype="multipart/form-data" action="utilisateur_edit.php" method="post">
            <fieldset>
                <?php
                echo  "<div class='form-control'>" . $user['uti_email'] . "</div>";
                ?>
            </fieldset>
            <fieldset>
                <div class="mb-3">
                    <label for="utilisateurPrenom" class="form-label">Prenom</label>
                    <input type="text" class="form-control" name="utilisateurPrenom" id="utilisateurPrenom" value=<?= $user['uti_prenom'] ?>>
                </div>
                <div class="mb-3">
                    <label for="utilisateurNom" class="form-label">Nom</label>
                    <input type="text" class="form-control" name="utilisateurNom" id="utilisateurNom" value=<?= $user['uti_nom'] ?>>
                </div>
                <div class="mb-3">
                    <label for="utilisateurNaissance" class="form-label">Date de Naissance</label>
                    <input type="date" class="form-control" name="utilisateurNaissance" id="utilisateurNaissance" value=<?= $user['uti_naissance_date'] ?>>
                </div>
                <div class="mb-3">
                    <label for="utilisateurImg" class="form-label">Image</label>
                    <input type="file" class="form-control" name="utilisateurImg" id="utilisateurImg">
                </div>
            </fieldset>
            <button type="submit" class="btn btn-primary">Mettre mes données de compte</button>
        </form>
    </div>
</body>

</html>