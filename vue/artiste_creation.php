<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once('../includes/header.php');

// Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

// Ajout dans la BDD de l'artiste
// TODO: Passer en AJAX
if (isset($_POST['artisteNom'])) {
    createArtiste($pdo);
    header("location: album_nom_creation.php");
}
?>

<body>
    <?php
    // Inclusion de la navbar
    include_once("../includes/navbar.php");
    ?>
    <div class="container">
        <h1>Nouvel Artiste</h1>

        <form action="artiste_creation.php" method="post">

            <div class="mb-3">
                <label for="artisteNom" class="form-label">Nom de l'artiste</label>
                <input required type="text" class="form-control" name="artisteNom" id="artisteNom">
            </div>
            <div class="mb-3">
                <label for="artistePays" class="form-label">Pays</label>
                <input type="text" class="form-control" name="artistePays" id="artistePays">
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
</body>

</html>