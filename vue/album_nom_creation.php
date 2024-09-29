<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page

include_once('../includes/header.php');

// Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

// Ajout dans la BDD du itre de l'album et lie l'artiste correspondant
// TODO: PAsser en AJAX
if (isset($_POST['albumTitre'])) {
    addArtiste($_POST["artisteId"], createAlbum($pdo), $pdo);
    header("location: version_creation.php");
}
?>

<body>
    <?php
    // Inclusion de la navbar
    include_once("../includes/navbar.php");
    ?>
    <div class="container">
        <h1>Nouveau Titre</h1>

        <form action="album_nom_creation.php" method="post">

            <div class="mb-3">
                <label for="albumTitre" class="form-label">Titre de l'album</label>
                <input required type="text" class="form-control" name="albumTitre" id="albumTitre">
            </div>
            <?php
            menuSelect("Artiste", "artisteId", "d_artiste_art", $pdo);
            ?>
            <a href='artiste_creation.php' class='btn btn-primary'>L'artiste n'existe pas?</a><br>
            <div class="mb-3">
                <label for="albumanSortie" class="form-label">Année</label>
                <input type="text" class="form-control" name="albumanSortie" id="albumanSortie">
            </div>
            <?php
            menuSelect("Genre", "genreId", "d_genre_gen", $pdo);
            ?>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
</body>

</html>