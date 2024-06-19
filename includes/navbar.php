<?php
if (isset($_SESSION["email"])) {
    $home = "index.php";
    $log = "Logout";
    $logref = "logout.php";
    $avatar = userInfo($_SESSION['email'], $pdo);
} else {
    $home = "login.php";
    $log = "Login";
    $logref = "login.php";
}

?>

<div class="navbar-pc">

    <div class="main-pages-pc-droite">
        <a class="nav-link" aria-current="page" href=<?= $home ?>>Home</a>
        <a class="nav-link" href="version_search.php">Rechercher un Album</a>
        <a class="nav-link" href="utilisateur_detail.php">Mon Compte</a>
    </div>
    <div class="main-pages-pc-gauche">
        <a class="nav-link" href=<?= $logref ?>><?= $log ?></a>
        <?php
        if (isset($avatar)) {
            echo "<a class='nav-link avatar' href='utilisateur_detail.php'><img src='data:image/jpeg;base64," . $avatar['uti_avatar'] . "' alt='photo de profil' class='img-avatar'></a>";
        }
        ?>
    </div>

</div>


<input class="navbar-bouton" id='navbar-bouton' type="checkbox">
<label for="navbar-bouton" class="hamburger hamburger2">
    <span class="bar bar1"></span>
    <span class="bar bar2"></span>
    <span class="bar bar3"></span>
    <span class="bar bar4"></span>
</label>
<div class="navbar-mobile">
    <div class="navbar-mobile-bandeau"></div>
    <ul class="main-pages-mobile">
        <li><a class="nav-link" aria-current="page" href=<?= $home ?>>Home</a></li>
        <li><a class="nav-link" href="version_search.php">Rechercher un Album</a></li>
        <li><a class="nav-link" href="utilisateur_detail.php">Mon Compte</a></li>
        <li><a class="nav-link" href=<?= $logref ?>><?= $log ?></a></li>
    </ul>

</div>