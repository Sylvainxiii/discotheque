<?php
include("includes/header.php");

if (isset($_SESSION['email'])) {
    header('Location: liste.php');
}
?>


<body>
    <?php
    include("includes/navbar.php");

    // je vérifie que email et password ne soient pas vide
    // je vérifie que l'utilisateur n'existe pas déjà
    // je chiffre le password avec bcrypt

    if (isset($_POST["password"])) {
        $crypted_password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        addUser($_POST["email"], $crypted_password, $pdo);
        header('Location: login.php');
    }

    ?>
    <div class="container">
        <h1>Création d'un Compte</h1>

        <form action="utilisateur_add.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input required type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input required type="password" class="form-control" name="password" id="password">
            </div>
            <button type="submit" class="btn btn-primary">Créer le compte</button>
        </form>
    </div>
</body>

</html>