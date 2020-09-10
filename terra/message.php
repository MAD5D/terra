<?php
        ini_set('display_errors','on');
        error_reporting(E_ALL);

require "/var/www/html/terra/Classe/Utilisateur.php";
require "/var/www/html/terra/Classe/Personnage.php";
require "/var/www/html/terra/Classe/App.php";
require "/var/www/html/terra/Classe/Base_de_donnees.php";
require "/var/www/html/terra/Classe/Plateau.php";
require "/var/www/html/terra/Classe/Message.php";
require "/var/www/html/terra/Classe/Serveur.php";

session_start();

if(isset($_SESSION['Utilisateur']) AND isset($_POST['rafraichir'])){
	$personnage = $_SESSION['Utilisateur']->personnage(true);
	$dernier_message = $personnage->obt_dernier_message();
	$json_a_envoyer = '{"message":{
		"numero_tour" : "' . $dernier_message->obt_numero_tour() . '",
		"date_message" : "' . $dernier_message->obt_date_message() . '",
		"message" : "' . $dernier_message->obt_message() . '"
		}}';
	echo $json_a_envoyer;
}
else{
	echo "Perdu :)";
}