<?php
// Fichier header.php contenant les inclusion de function  et initialisation de la page
include_once("../includes/header.php");

// Si l'utilisateur est connecté via $_SESSION, redirection vers la page index, si password non reconnu redirection création de compte
// TODO: Amélioration du comportement pour indiquer une erreur de pssword sans redirection, ajout bouton création compte, validation js des champs
if (count($_POST) > 0) {
    if (isValid($_POST['email'], $_POST['password'], $pdo)) {
        $_SESSION['email'] = $_POST['email'];
        header('Location: ../index.php');
    } else {
        header('Location: ./utilisateur_add.php');
    }
} else {

?>

    <body>
        <div class="container">
            <h1>Login</h1>

            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <button type="submit" class="btn btn-primary">Soumettre</button>
            </form>
        </div>
    </body>

    </html>
<?php
}
?>