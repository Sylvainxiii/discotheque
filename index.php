<?php
include('src/__header.php');

//Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

$user =  userInfo($_SESSION["email"], $pdo);

$list = [];
$list = getListe($_SESSION["email"], $pdo);

if (isset($_GET['listeId'])) {
    editetat($pdo);
    header("location: index.php");
}

if (isset($_GET['delete'])) {
    supListe($_GET['delete'], $pdo);
    header("location: index.php");
}

?>

<body>
    <?php
    include("src/__navbar.php");
    ?>

    <div class="container">
        <h1>Collection de: <?= $user['uti_prenom'] ?></h1>
        <div class="btn btn-primary"><a href="version_search.php">Ajouter un Album</a></div>
        <div class="text-center">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="th-center">#</th>
                        <th scope="col" class="hide th-center">image</th>
                        <th scope="col" class="th-center">Ref</th>
                        <th scope="col" class="th-center">Album</th>
                        <th scope="col" class="th-center">Artiste</th>
                        <th scope="col" class="hide th-center">Format</th>
                        <th scope="col" class="hide th-center">Genre</th>
                        <th scope="col" class="th-center form-etat-pc">Etat</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($list); $i++) {
                    ?>

                        <tr>
                            <th scope="row" class="th-center th-row"><?= ($i + 1) ?></th>
                            <td class="hide">
                                <img src="<?= $list[$i]['ver_image'] ?>" alt="pochette de l'album" class="img-liste">
                            </td>
                            <td><?= $list[$i]['ver_ref'] ?></td>
                            <td><a href="version_detail.php?versionId=<?= $list[$i]['ver_id'] ?>"><?= $list[$i]['alb_titre'] ?></a></td>
                            <td><?= $list[$i]['art_nom'] ?></td>
                            <td class="hide"><?= $list[$i]['for_nom'] ?></td>
                            <td class="hide"><?= $list[$i]['gen_nom'] ?></td>
                            <form action="index.php" method="get">
                                <input type="hidden" name="listeId" id="listeId" value=<?= $list[$i]['lis_id'] ?>>
                                <td class="etat-td form-etat-pc">
                                    <div class="select-etat"><?= menuSelect("Média", "etatMedia", "d_etat_eta", $pdo, $list[$i]['lis_fk_media_eta_id']) ?></div>
                                    <div class="select-etat"><?= menuSelect("Pochette", "etatPochette", "d_etat_eta", $pdo, $list[$i]['lis_fk_pochette_eta_id']) ?></div>
                                </td>
                                <td class="btn-td form-etat-pc"><button type="submit" class="btn btn-primary">Modifier l'état</button></td>
                            </form>
                            <td class="btn-td form-etat-pc">
                                <div class="btn btn-danger"><a href="index.php?delete=<?= $list[$i]['lis_id'] ?>">Supprimer</a></div>
                            </td>
                        </tr>
                        <tr class="form-etat-mobile">

                            <th scope="row" class="th-center th-row"></th>
                            <form action="index.php" method="get">
                                <input type="hidden" name="listeId" id="listeId" value=<?= $list[$i]['lis_id'] ?>>
                                <td colspan="2">
                                    <div class="flex-row">
                                        <div>
                                            <div class="select-etat-mobile"><?= menuSelect("Média", "etatMedia", "d_etat_eta", $pdo, $list[$i]['lis_fk_media_eta_id']) ?></div>
                                            <div class="select-etat-mobile"><?= menuSelect("Pochette", "etatPochette", "d_etat_eta", $pdo, $list[$i]['lis_fk_pochette_eta_id']) ?></div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Modifier l'état</button>
                                    </div>
                                </td>
                            </form>
                            <td class="btn-td">
                                <div class="btn btn-danger"><a href="index.php?delete=<?= $list[$i]['lis_id'] ?>">Supprimer</a></div>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>