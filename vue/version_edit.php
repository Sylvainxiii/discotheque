<?php
include_once('../includes/header.php');

//Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

$data = versionDetail($_GET['versionId'], $pdo);

if (isset($_POST["formatId"])) {
    editVersion($_GET['versionId'], $pdo);
    header("location: ../index.php");
}

?>

<body>
    <?php
    include_once("../includes/navbar.php");
    ?>
    <div class="container">
        <h1>Edition d'un Album</h1>

        <form enctype="multipart/form-data" action="version_edit.php?versionId=<?= $_GET['versionId'] ?>" method="post">
            <fieldset>
                <?php
                echo  "<div class='form-control'>" . $data['alb_titre'] . "</div>";
                ?>
            </fieldset>
            <fieldset>
                <div class="mb-3">
                    <label for="versionRef" class="form-label">Version</label>
                    <?= "<div class='form-control'>" . $data['ver_ref'] . "</div>" ?>
                </div>
                <?php
                menuSelect("Format", "formatId", "d_format_for", $pdo, $data['ver_fk_for_id']);
                menuSelect("Label", "labelId", "d_label_lab", $pdo, $data['ver_fk_lab_id']);
                ?>
                <div class="mb-3">
                    <label for="versionPressYear" class="form-label">Sortie</label>
                    <input type="text" class="form-control" name="versionPressYear" id="versionPressYear" value=<?= $data['ver_press_annee'] ?>>
                </div>
                <div class="mb-3">
                    <label for="versionPressPays" class="form-label">Pays</label>
                    <input type="text" class="form-control" name="versionPressPays" id="versionPressPays" value=<?= $data['ver_press_pays'] ?>>
                </div>
                <?php
                menuSelect("Edition", "versionEditId", "d_edition_edi", $pdo, $data['ver_fk_edi_id']);
                ?>
                <div class="mb-3">
                    <label for="versionImg" class="form-label">Image</label>
                    <input type="file" class="form-control" name="versionImg" id="versionImg">
                </div>
            </fieldset>
            <button type="submit" class="btn btn-primary">Mettre à jour l'album</button>
        </form>
    </div>
</body>

</html>