<!-- Un commenteire quelconque -->
<?php
include("includes/header.php");


if (count($_POST) > 0) {
    if (isValid($_POST['email'], $_POST['password'], $pdo)) {
        $_SESSION['email'] = $_POST['email'];
        header('Location: index.php');
    } else {
        header('Location: utilisateur_add.php');
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