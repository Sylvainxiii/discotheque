<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once 'includes/header.php';

?>

<body>
    <?php
    // Inclusion de la navbar
    include_once "includes/navbar.php";
    ?>

    <div class="container">
        <h1>Collection de: <?= $user['uti_prenom'] ?></h1>
        <div class="btn btn-primary"><a href="vue/version_search.php">Ajouter un Album</a></div>
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

                </tbody>
            </table>
        </div>
    </div>
</body>

<script type="module" src="../assets/js/fonctions_rest.js"></script>
<script type="module" src="../assets/js/composants.js"></script>
<script type="module" src="../assets/js/index.js"></script>
<script type="text/javascript">
    sessionStorage.setItem("userId", "<?php echo $idUser["uti_id"]; ?>")
</script>

</html>