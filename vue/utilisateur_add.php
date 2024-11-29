<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once("../includes/header.php");

?>

<body>
    <?php

    // je vérifie que email et password ne soient pas vide
    // je vérifie que l'utilisateur n'existe pas déjà, pas sur que ça fonctionne
    // je chiffre le password avec bcrypt
    // TODO: La vérification de compte existant ne fonctionne pas. Validité de champs et message d'erreur en js. Passer en AJAX 
    if (isset($_POST["password"])) {
        $crypted_password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        addUser($_POST["email"], $crypted_password, $pdo);
        header('Location: login.php');
    }
    ?>

    <div class="container container-etroit">
        <h1>Création d'un Compte</h1>

        <form action="utilisateur_add.php" method="post">
            <div class="champ-container">
                <label for="email" class="form-label">Email</label>
                <input required type="email" name="email" id="email" aria-describedby="emailHelp">
            </div>
            <div class="champ-container">
                <label for="password" class="form-label">Password</label>
                <input required type="password" name="password" id="password">
            </div>
            <button type="submit" class="btn btn-primary">Créer le compte</button>
            <div class="btn btn-primary"><a href="login.php">Se Connecter</a></div>
        </form>
    </div>
</body>

</html>