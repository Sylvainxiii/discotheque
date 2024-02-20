<?php
include("includes/header.php");
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
}

if (isset($_GET['versionId'])) {
    $data = versionDetail($_GET['versionId'], $pdo);
    $tracklist = getChanson($_GET['versionId'], $pdo);
}

if (isset($_GET['delete'])) {
    supChanson($_GET['delete'], $pdo);
    header("location: version_detail.php?versionId=" . $_GET['versionId']);
}
?>

<body>
    <?php
    include("includes/navbar.php")
    ?>

    <div class="container">
        <h1>Album: <?= $data['alb_titre'] ?> par <?= $data['art_nom'] ?></h1>
        <div class="text-center">
            <img src="<?= $data['ver_image'] ?>" alt="pochette de l'album" class="img-detail">
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
            <tbody>
                <?php
                for ($i = 0; $i < count($tracklist); $i++) {
                ?>
                    <tr>
                        <th scope="row"><?= $tracklist[$i]['cha_track'] ?></th>
                        <td><?= $tracklist[$i]['cha_titre'] ?></td>
                        <td><?= $tracklist[$i]['cha_duree'] ?></td>
                        <td class="btn-td">
                            <div class="btn btn-primary"><a href="chanson_edit.php?chansonId=<?= $tracklist[$i]['cha_id'] ?>">Editer la chanson</a></div>
                        </td>
                        <td class="btn-td">
                            <div class="btn btn-danger"><a href="version_detail.php?versionId=<?= $_GET['versionId'] ?>&delete=<?= $tracklist[$i]['cha_id'] ?>">Supprimer la chanson</a></div>
                        </td>
                    </tr>
                <?php
                }
                ?>

                <tr>
                    <td colspan="5">
                        <div class="btn btn-primary"><a href="chanson_creation.php?versionId=<?= $_GET['versionId'] ?>">Ajouter des chansons</a></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>