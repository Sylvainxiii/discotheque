<?php
include('src/__header.php');

//Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

if (isset($_POST['artisteNom'])) {
    createArtiste($pdo);
    header("location: album_nom_creation.php");
}


?>

<body>
    <?php
    include("src/__navbar.php");
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