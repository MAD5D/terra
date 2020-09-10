<?php
        ini_set('display_errors','on');
        error_reporting(E_ALL);

require "/var/www/html/terra/Classe/Utilisateur.php";
require "/var/www/html/terra/Classe/Personnage.php";
require "/var/www/html/terra/Classe/App.php";
require "/var/www/html/terra/Classe/Base_de_donnees.php";
require "/var/www/html/terra/Classe/Message.php";
require "/var/www/html/terra/Classe/Serveur.php";

	$liste_personnage = Personnage::classement_personnage();

	$serveur = Serveur::obtenir_serveur_depuis_id(1);
	$tour_en_cours = $serveur->nouveau_tour();

	foreach($liste_personnage as $personnage)
	{
		echo $personnage->obt_nom() . "\n";

		$nb_point_action_gagner = $personnage->obt_point_action_max() - $personnage->obt_point_action();
		$nb_point_deplacement_gagner = $personnage->obt_point_deplacement_max() - $personnage->obt_point_deplacement();

		$personnage->def_point_action($personnage->obt_point_action_max());
		$personnage->def_point_deplacement($personnage->obt_point_deplacement_max());
		$personnage->def_point_experience($personnage->obt_point_experience() + 1);

		$personnage->sauvegarde();

		$message = "Le tour " . $tour_en_cours . " commence, vous avez récupéré " . $nb_point_action_gagner . " jetons action et " . $nb_point_deplacement_gagner . " jetons  déplacements. Vous gagnez 1 point d'xp";

		Message::creer_message($personnage->obt_id(),$tour_en_cours,$message);

		//echo "Le tour XXXX commence, vous avez récupéré " . $nb_point_action_gagner . " jetons action et " . $nb_point_deplacement_gagner . " jetons  déplacements. Vous gagnez 1 point d'xp";
	}

	Message::purger_ancien_message($tour_en_cours);
?>