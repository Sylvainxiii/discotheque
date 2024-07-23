<?php
include('src/__header.php');

//Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

if (isset($_POST['0titre'])) {
    createChanson($pdo);
    header("location: version_detail.php?versionId=" . $_POST['versionId']);
}

?>

<body>
    <?php
    include("src/__navbar.php");
    ?>
    <div class="container">
        <h1>Nouvelles Chansons</h1>

        <form action="chanson_creation.php" method="post">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"> <label for="track" class="form-label">Track Nr</label>
                        </th>
                        <th scope="col"> <label for="titre" class="form-label">Titre de la chanson</label>
                        </th>
                        <th scope="col"> <label for="duree" class="form-label">Durée</label>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <input type='hidden' name='versionId' id='versionId' value=<?= $_GET['versionId'] ?>>
                        <td> <input required type='text' class='form-control' name='0track' id='0track'>
                        </td>
                        <td> <input required type='text' class='form-control' name='0titre' id='0titre'>
                        </td>
                        <td> <input type='text' class='form-control' name='0duree' id='0duree'>
                        </td>
                    </tr>
                    </tr>
                    <?php
                    if (isset($_GET["chansonNr"])) {
                        for ($i = 1; $i < ($_GET["chansonNr"]); $i++) {
                            echo "<tr><td> <input required type='text' class='form-control' name='" . $i . "track' id='" . $i . "track'>
                        </td>
                        <td> <input required type='text' class='form-control' name='" . $i . "titre' id='" . $i . "titre'>
                        </td>
                        <td> <input type='text' class='form-control' name='" . $i . "duree' id='" . $i . "duree'>
                        </td></tr>";
                        }
                    }
                    ?>

                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
        <form action="chanson_creation.php" method="get">
            <input type='hidden' name='versionId' id='versionId0' value=<?= $_GET['versionId'] ?>>
            <label for="chansonNr" class="form-label">Nr de Chansons</label>
            <div>
                <input type="text" class="form-control form-control-color" id="chansonNr" value="1" name="chansonNr">
                <button type="submit" class="btn btn-primary">Go</button>
            </div>
        </form>
    </div>
</body>

</html>