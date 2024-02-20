<?php
include('includes/header.php');

//Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header("location: login.php");
}

$data = userInfo($_SESSION['email'], $pdo);

if (isset($_FILES['utilisateurImg'])) {
    addImage64($pdo);
}

if (isset($_POST['utilisateurPrenom'])) {
    editUser($pdo);
    header("location: utilisateur_detail.php");
}

?>

<body>
    <?php
    include("includes/navbar.php");
    ?>
    <div class="container">
        <h1>Mes Informations Personnelles</h1>

        <form enctype="multipart/form-data" action="utilisateur_edit.php" method="post">
            <fieldset>
                <?php
                echo  "<div class='form-control'>" . $data['uti_email'] . "</div>";
                ?>
            </fieldset>
            <fieldset>
                <div class="mb-3">
                    <label for="utilisateurPrenom" class="form-label">Prenom</label>
                    <input type="text" class="form-control" name="utilisateurPrenom" id="utilisateurPrenom" value=<?= $data['uti_prenom'] ?>>
                </div>
                <div class="mb-3">
                    <label for="utilisateurNom" class="form-label">Nom</label>
                    <input type="text" class="form-control" name="utilisateurNom" id="utilisateurNom" value=<?= $data['uti_nom'] ?>>
                </div>
                <div class="mb-3">
                    <label for="utilisateurNaissance" class="form-label">Date de Naissance</label>
                    <input type="date" class="form-control" name="utilisateurNaissance" id="utilisateurNaissance" value=<?= $data['uti_naissance_date'] ?>>
                </div>
                <div class="mb-3">
                    <label for="utilisateurImg" class="form-label">Image</label>
                    <input type="file" class="form-control" name="utilisateurImg" id="utilisateurImg">
                </div>
            </fieldset>
            <button type="submit" class="btn btn-primary">Mettre mes données de compte</button>
        </form>
    </div>
</body>

</html>