<?php
include('src/__header.php');

//Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

if (isset($_POST["versionRef"])) {
    createVersion($pdo);
    header("location: version_search.php");
}

?>

<body>
    <?php
    include("src/__navbar.php");
    ?>
    <div class="container">
        <h1>Création d'un Album</h1>

        <form enctype="multipart/form-data" action="version_creation.php" method="post">
            <fieldset>
                <?php
                menuSelect("Titre de l'album", "albumId", "d_album_alb", $pdo);
                ?>
                <a href='album_nom_creation.php' class='btn btn-primary'>Le titre de l'album n'existe pas?</a><br>
            </fieldset>
            <fieldset>
                <div class="mb-3">
                    <label for="versionRef" class="form-label">Référence de la Version</label>
                    <input required type="text" class="form-control" name="versionRef" id="versionRef">
                </div>
                <?php
                menuSelect("Format", "formatId", "d_format_for", $pdo);
                menuSelect("Label", "labelId", "d_label_lab", $pdo);
                ?>
                <div class="mb-3">
                    <label for="versionPressYear" class="form-label">Sortie</label>
                    <input type="text" class="form-control" name="versionPressYear" id="versionPressYear">
                </div>
                <div class="mb-3">
                    <label for="versionPressPays" class="form-label">Pays</label>
                    <input type="text" class="form-control" name="versionPressPays" id="versionPressPays">
                </div>
                <?php
                menuSelect("Edition", "versionEditId", "d_edition_edi", $pdo);
                ?>
                <div class="mb-3">
                    <label for="versionImg" class="form-label">Image</label>
                    <input type="file" class="form-control" name="versionImg" id="versionImg">
                </div>
            </fieldset>
            <button type="submit" class="btn btn-primary">Créer l'album</button>
        </form>
    </div>
</body>

</html>