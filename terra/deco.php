<?php
require "./Classe/Chargement_auto.php";
spl_autoload_register(array('Chargement_auto','charge'));

session_start();

$_SESSION['Utilisateur']->def_SESSID("");

session_destroy();
echo "ok";
?>