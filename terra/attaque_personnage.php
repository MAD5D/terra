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

if(isset($_SESSION['Utilisateur']) AND isset($_POST['attaque']) AND isset($_POST['id_cible'])){
	$personnage_attaquant = $_SESSION['Utilisateur']->personnage(true);
	$personnage_cible = Personnage::obt_personnage_depuis_id_personnage($_POST['id_cible']);
	if($personnage_attaquant->obt_position_x() == $personnage_cible->obt_position_x() AND $personnage_attaquant->obt_position_y() == $personnage_cible->obt_position_y() AND $personnage_attaquant->obt_point_action() > 0){
		$personnage_attaquant->attaque($personnage_cible);
		echo 'Frape';
	}
	else{
		echo "trop loin ou trop faible gégé";
	}
}
else{
	echo "Perdu :)";
}