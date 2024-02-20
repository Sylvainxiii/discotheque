<?php
include("includes/header.php");
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
}


if (isset($_SESSION['email'])) {
    $data = userInfo($_SESSION['email'], $pdo);
}

// if (isset($_GET['delete'])) {
//     supChanson($_GET['delete'], $pdo);
//     header("location: version_detail.php?versionId=" . $_GET['versionId']);
// }
?>

<body>
    <?php
    include("includes/navbar.php")
    ?>

    <div class="container">
        <h1><?= $data['uti_prenom'] ?> <?= $data['uti_nom'] ?></h1>
        <div class="text-center">
            <img src="data:image/jpeg;base64,<?= $data['uti_avatar'] ?>" alt="photo de profil" class="img-profil">
            <div class="table-responsive">
                <table>
                    <tbody>
                        <tr>
                            <th colspan="2" class="th-left">Email: </th>
                            <td colspan="2"><?= $data['uti_email'] ?></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="th-left">Date de Naissance: </th>
                            <td colspan="2"><?= $data['uti_naissance_date'] ?></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <div class="btn btn-primary"><a href="utilisateur_edit.php">Modifier mes information Personnelles</a></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>