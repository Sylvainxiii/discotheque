<?php
session_start();

// Après déconnection, redirection vers la page login
session_destroy();
header("location: login.php");
