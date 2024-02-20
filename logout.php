<!-- ajout d'un commentaire pour github -->

<?php
session_start();

session_destroy();
header("location: login.php");
