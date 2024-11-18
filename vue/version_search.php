<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once("../includes/header.php");

// Vérification si l'utilisateur est connecté en utilisant la variable $_SESSION
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
} else {
    $email = $_SESSION['email'];
    $idUser =  userId($email, $pdo);
}
?>

<body>
    <?php
    // Inclusion de la navbar
    include_once("../includes/navbar.php");
    ?>

    <div class="container">
        <h1>Recherche d'Album</h1>
        <div class="form-control-line" action="version_search.php" method="post">

            <div class="input-group">
                <label class="label-hidden" for="chercher-reference">Ref</label>
                <div class="input-group-text">Ref</div>
                <input type="text" class="input-group-control" name="chercherreference" id="chercher-reference" placeholder="Ref">
            </div>

            <div class="input-group">
                <label class="label-hidden" for="chercher-titre">Titre</label>
                <div class="input-group-text">Titre de l'album</div>
                <input type="text" class="input-group-control" name="cherchertitre" id="chercher-titre" placeholder="Titre">
            </div>

            <div class="input-group">
                <label class="label-hidden" for="chercher-artiste">Artiste</label>
                <div class="input-group-text">Artiste</div>
                <input type="text" class="input-group-control" name="chercherartiste" id="chercher-artiste" placeholder="Artiste">
            </div>
        </div>
        <div class="text-center">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ref</th>
                        <th scope="col">Album</th>
                        <th scope="col">Artiste</th>
                        <th scope="col">Année du pressage</th>
                        <th scope="col">Pays de distribution</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="table-liste">
                </tbody>
            </table>
        </div>

        <div class="btn btn-primary" id="ajout-version">Créer un Album</div>
    </div>
</body>
<script type="module" src="../assets/js/fonctions_rest.js"></script>
<script type="module" src="../assets/js/composants.js"></script>
<script type="module" src="../assets/js/version_search.js"></script>
<script type="text/javascript">
    sessionStorage.setItem("userId", "<?php echo $idUser["uti_id"]; ?>")
</script>

</html>