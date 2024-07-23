<?php
include('src/__header.php');

//Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

$data = chansonDetail($_GET['chansonId'], $pdo);

if (isset($_POST["chansonTitre"])) {
    editchanson($_GET['chansonId'], $pdo);
    header("location: version_detail.php?versionId=" . $_POST['versionId']);
}

?>

<body>
    <?php
    include("src/__navbar.php");
    ?>
    <div class="container">
        <h1>Edition d'une Chanson</h1>

        <form action="chanson_edit.php?chansonId=<?= $_GET['chansonId'] ?>" method="post">

            <div class="mb-3">
                <label for="chansonTitre" class="form-label">Titre de la chanson</label>
                <input type="text" class="form-control" name="chansonTitre" id="chansonTitre" value=<?= $data['cha_titre'] ?>>
            </div>
            <div class="mb-3">
                <label for="chansonDuree" class="form-label">Durée</label>
                <input type="text" class="form-control" name="chansonDuree" id="chansonDuree" value=<?= $data['cha_duree'] ?>>
            </div>
            <div class="mb-3">
                <label for="chansonTrackNr" class="form-label">Track Nr</label>
                <input type="text" class="form-control" name="chansonTrackNr" id="chansonTrackNr" value=<?= $data['cha_track'] ?>>
            </div>
            <?php
            menuSelect("Album", "versionId", "(SELECT ver_id, alb_titre FROM d_version_ver INNER JOIN d_album_alb ON ver_fk_alb_id = alb_id WHERE ver_id =" . $data['cha_fk_ver_id'] . ") AS nom", $pdo, $data['cha_fk_ver_id']);
            ?>

            <button type="submit" class="btn btn-primary">Mettre à jour l'album</button>
        </form>
    </div>
</body>

</html>