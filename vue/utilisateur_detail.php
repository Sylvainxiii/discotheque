<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once("../includes/header.php");

?>

<body>
    <?php
    // Inclusion de la navbar
    include_once("../includes/navbar.php")
    ?>

    <div class="container container-etroit">
        <h1><?= $user['uti_prenom'] ?> <?= $user['uti_nom'] ?></h1>
        <div class="text-center">
            <img src="data:image/jpeg;base64,<?= $user['uti_avatar'] ?>" alt="photo de profil" class="img-profil">
            <div class="table-responsive">
                <table>
                    <tbody>
                        <tr>
                            <th colspan="2" class="th-left">Email: </th>
                            <td colspan="2"><?= $user['uti_email'] ?></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="th-left">Date de Naissance: </th>
                            <td colspan="2"><?= $user['uti_naissance_date'] ?></td>
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