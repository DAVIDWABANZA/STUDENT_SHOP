<?php
session_start();
// Vider les données de la session
$_SESSION = [];
// Détruire la session
session_destroy();
// Retourner à la page de connexion
header('Location: login.php');
exit;