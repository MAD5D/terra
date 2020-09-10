<?php
        ini_set('display_errors','on');
        error_reporting(E_ALL);

require "/var/www/html/terra/Classe/Utilisateur.php";
require "/var/www/html/terra/Classe/Personnage.php";
require "/var/www/html/terra/Classe/App.php";
require "/var/www/html/terra/Classe/Base_de_donnees.php";
require "/var/www/html/terra/Classe/Plateau.php";

session_start();

if(isset($_SESSION['Utilisateur']) AND isset($_POST['deplacement'])){
	if($_POST['deplacement'] == "hd"){
		if($_SESSION['Utilisateur']->personnage(true)){
			if($_SESSION['Utilisateur']->personnage()->obt_point_deplacement() > 0 AND $_SESSION['Utilisateur']->personnage()->obt_position_y() > -10){
				$_SESSION['Utilisateur']->personnage()->def_point_deplacement($_SESSION['Utilisateur']->personnage()->obt_point_deplacement() - 1);
				$_SESSION['Utilisateur']->personnage()->def_position_y($_SESSION['Utilisateur']->personnage()->obt_position_y() - 1);
				$_SESSION['Utilisateur']->personnage()->sauvegarde();

				//Création des nouvelles case.
				$nouvelle_case = Plateau::nouvelle_case_hd($_SESSION['Utilisateur']->personnage()->obt_position_x(),$_SESSION['Utilisateur']->personnage()->obt_position_y());

				$i_nouvelle_case = 0;
				$i_type = 0;

				$reponse = '[{"autorisation":1,
							  "case": {';
				while($i_type < 9){
					if(isset($nouvelle_case[$i_nouvelle_case]) AND $nouvelle_case[$i_nouvelle_case]->obt_position_x() == $_SESSION['Utilisateur']->personnage()->obt_position_x()-4+$i_type){
						$reponse = $reponse . '"' . ($i_type+1) . '":{
													"type": ' . $nouvelle_case[$i_nouvelle_case]->obt_type() . ',
													"position_x": ' . $nouvelle_case[$i_nouvelle_case]->obt_position_x() . ',
													"position_y": ' . $nouvelle_case[$i_nouvelle_case]->obt_position_y() . '
												},';
						$i_nouvelle_case++;
					}
					else{
						$reponse = $reponse . '"' . ($i_type+1) . '":{
													"type": 0,
													"position_x": 999,
													"position_y": 999
												},';
					}
					$i_type++;
				}
				$reponse = substr($reponse,0,-1);

				//Recherche des nouveaux élément de jeu
				$liste_personnage = $_SESSION['Utilisateur']->personnage()->obt_personnage_en_bordure("hd");

				$i = 0;
				$reponse .= '
						},"element_du_jeu": [';
				while($i < count($liste_personnage)){
					$reponse .= '{
						"type": 1,
						"position_x": ' . $liste_personnage[$i]->obt_position_x() . ',
						"position_y": ' . $liste_personnage[$i]->obt_position_y() . '
					},';

					$i++;
				}
				if($i != 0){
					$reponse = substr($reponse,0,-1);
				}
				$reponse .= '],';

				//Nouvelle position du personnage
				$reponse .= '
					"position": {
						"x": ' . $_SESSION['Utilisateur']->personnage()->obt_position_x() . ',
						"y": ' . $_SESSION['Utilisateur']->personnage()->obt_position_y() . '
					}
				}]';

				//Envoie de la réponse.
				echo $reponse;
			}
			else{
				echo '[{"autorisation":0}]';
			}
		}
		else{
			echo "il ne peut pas";
		}
	}
	else if($_POST['deplacement'] == "hg"){
		if($_SESSION['Utilisateur']->personnage(true)){
			if($_SESSION['Utilisateur']->personnage()->obt_point_deplacement() > 0 AND $_SESSION['Utilisateur']->personnage()->obt_position_x() > -10){
				$_SESSION['Utilisateur']->personnage()->def_point_deplacement($_SESSION['Utilisateur']->personnage()->obt_point_deplacement() - 1);
				$_SESSION['Utilisateur']->personnage()->def_position_x($_SESSION['Utilisateur']->personnage()->obt_position_x() - 1);
				$_SESSION['Utilisateur']->personnage()->sauvegarde();

				//Création des nouvelles case.
				$nouvelle_case = Plateau::nouvelle_case_hg($_SESSION['Utilisateur']->personnage()->obt_position_x(),$_SESSION['Utilisateur']->personnage()->obt_position_y());

				$i_nouvelle_case = 0;
				$i_type = 0;
				$reponse = '[{"autorisation":1,
							  "case": {';
				while($i_type < 9){
					if(isset($nouvelle_case[$i_nouvelle_case]) AND $nouvelle_case[$i_nouvelle_case]->obt_position_y() == $_SESSION['Utilisateur']->personnage()->obt_position_y()-4+$i_type){
						$reponse = $reponse . '"' . ($i_type+1) . '":{
													"type": ' . $nouvelle_case[$i_nouvelle_case]->obt_type() . ',
													"position_x": ' . $nouvelle_case[$i_nouvelle_case]->obt_position_x() . ',
													"position_y": ' . $nouvelle_case[$i_nouvelle_case]->obt_position_y() . '
												},';
						$i_nouvelle_case++;
					}
					else{
						$reponse = $reponse . '"' . ($i_type+1) . '":{
													"type": 0,
													"position_x": 999,
													"position_y": 999
												},';
					}
					$i_type++;
				}
				$reponse = substr($reponse,0,-1);

				//Recherche des nouveaux élément de jeu
				$liste_personnage = $_SESSION['Utilisateur']->personnage()->obt_personnage_en_bordure("hg");

				$i = 0;
				$reponse .= '
						},"element_du_jeu": [';
				while($i < count($liste_personnage)){
					$reponse .= '{
						"type": 1,
						"position_x": ' . $liste_personnage[$i]->obt_position_x() . ',
						"position_y": ' . $liste_personnage[$i]->obt_position_y() . '
					},';

					$i++;
				}
				if($i != 0){
					$reponse = substr($reponse,0,-1);
				}
				$reponse .= '],';

				//Nouvelle position du personnage
				$reponse .= '
					"position": {
						"x": ' . $_SESSION['Utilisateur']->personnage()->obt_position_x() . ',
						"y": ' . $_SESSION['Utilisateur']->personnage()->obt_position_y() . '
					}
				}]';

				//Envoie de la réponse.
				echo $reponse;
			}
			else{
				echo '[{"autorisation":0}]';
			}
		}
		else{
			echo "il ne peut pas";
		}
	}
	else if($_POST['deplacement'] == "bd"){
		if($_SESSION['Utilisateur']->personnage(true)){
			if($_SESSION['Utilisateur']->personnage()->obt_point_deplacement() > 0 AND $_SESSION['Utilisateur']->personnage()->obt_position_x() < 10){
				$_SESSION['Utilisateur']->personnage()->def_point_deplacement($_SESSION['Utilisateur']->personnage()->obt_point_deplacement() - 1);
				$_SESSION['Utilisateur']->personnage()->def_position_x($_SESSION['Utilisateur']->personnage()->obt_position_x() + 1);
				$_SESSION['Utilisateur']->personnage()->sauvegarde();

				//Création des nouvelles case.
				$nouvelle_case = Plateau::nouvelle_case_bd($_SESSION['Utilisateur']->personnage()->obt_position_x(),$_SESSION['Utilisateur']->personnage()->obt_position_y());

				$i_nouvelle_case = 0;
				$i_type = 0;
				$reponse = '[{"autorisation":1,
							  "case": {';
				while($i_type < 9){
					if(isset($nouvelle_case[$i_nouvelle_case]) AND $nouvelle_case[$i_nouvelle_case]->obt_position_y() == $_SESSION['Utilisateur']->personnage()->obt_position_y()-4+$i_type){
						$reponse = $reponse . '"' . ($i_type+1) . '":{
													"type": ' . $nouvelle_case[$i_nouvelle_case]->obt_type() . ',
													"position_x": ' . $nouvelle_case[$i_nouvelle_case]->obt_position_x() . ',
													"position_y": ' . $nouvelle_case[$i_nouvelle_case]->obt_position_y() . '
												},';
						$i_nouvelle_case++;
					}
					else{
						$reponse = $reponse . '"' . ($i_type+1) . '":{
													"type": 0,
													"position_x": 999,
													"position_y": 999
												},';
					}
					$i_type++;
				}
				$reponse = substr($reponse,0,-1);

				//Recherche des nouveaux élément de jeu
				$liste_personnage = $_SESSION['Utilisateur']->personnage()->obt_personnage_en_bordure("bd");

				$i = 0;
				$reponse .= '
						},"element_du_jeu": [';
				while($i < count($liste_personnage)){
					$reponse .= '{
						"type": 1,
						"position_x": ' . $liste_personnage[$i]->obt_position_x() . ',
						"position_y": ' . $liste_personnage[$i]->obt_position_y() . '
					},';

					$i++;
				}
				if($i != 0){
					$reponse = substr($reponse,0,-1);
				}
				$reponse .= '],';

				//Nouvelle position du personnage
				$reponse .= '
					"position": {
						"x": ' . $_SESSION['Utilisateur']->personnage()->obt_position_x() . ',
						"y": ' . $_SESSION['Utilisateur']->personnage()->obt_position_y() . '
					}
				}]';

				//Envoie de la réponse.
				echo $reponse;
			}
			else{
				echo '[{"autorisation":0}]';
			}
		}
		else{
			echo "il ne peut pas";
		}
	}
	else if($_POST['deplacement'] == "bg"){
		if($_SESSION['Utilisateur']->personnage(true)){
			if($_SESSION['Utilisateur']->personnage()->obt_point_deplacement() > 0 AND $_SESSION['Utilisateur']->personnage()->obt_position_y() < 10){
				$_SESSION['Utilisateur']->personnage()->def_point_deplacement($_SESSION['Utilisateur']->personnage()->obt_point_deplacement() - 1);
				$_SESSION['Utilisateur']->personnage()->def_position_y($_SESSION['Utilisateur']->personnage()->obt_position_y() + 1);
				$_SESSION['Utilisateur']->personnage()->sauvegarde();

				//Création des nouvelles case.
				$nouvelle_case = Plateau::nouvelle_case_bg($_SESSION['Utilisateur']->personnage()->obt_position_x(),$_SESSION['Utilisateur']->personnage()->obt_position_y());

				$i_nouvelle_case = 0;
				$i_type = 0;

				$reponse = '[{"autorisation":1,
							  "case": {';

				while($i_type < 9){
					if(isset($nouvelle_case[$i_nouvelle_case]) AND $nouvelle_case[$i_nouvelle_case]->obt_position_x() == $_SESSION['Utilisateur']->personnage()->obt_position_x()-4+$i_type){
						$reponse = $reponse . '"' . ($i_type+1) . '":{
													"type": ' . $nouvelle_case[$i_nouvelle_case]->obt_type() . ',
													"position_x": ' . $nouvelle_case[$i_nouvelle_case]->obt_position_x() . ',
													"position_y": ' . $nouvelle_case[$i_nouvelle_case]->obt_position_y() . '
												},';
						$i_nouvelle_case++;
					}
					else{
						$reponse = $reponse . '"' . ($i_type+1) . '":{
													"type": 0,
													"position_x": 999,
													"position_y": 999
												},';
					}
					$i_type++;
				}
				$reponse = substr($reponse,0,-1);

				//Recherche des nouveaux élément de jeu
				$liste_personnage = $_SESSION['Utilisateur']->personnage()->obt_personnage_en_bordure("bg");

				$i = 0;
				$reponse .= '
						},"element_du_jeu": [';
				while($i < count($liste_personnage)){
					$reponse .= '{
						"type": 1,
						"position_x": ' . $liste_personnage[$i]->obt_position_x() . ',
						"position_y": ' . $liste_personnage[$i]->obt_position_y() . '
					},';

					$i++;
				}
				if($i != 0){
					$reponse = substr($reponse,0,-1);
				}
				$reponse .= '],';

				//Nouvelle position du personnage
				$reponse .= '
					"position": {
						"x": ' . $_SESSION['Utilisateur']->personnage()->obt_position_x() . ',
						"y": ' . $_SESSION['Utilisateur']->personnage()->obt_position_y() . '
					}
				}]';

				//Envoie de la réponse.
				echo $reponse;
			}
			else{
				echo '[{"autorisation":0}]';
			}
		}
		else{
			echo "il ne peut pas";
		}
	}
}
else if(isset($_SESSION['Utilisateur']) AND isset($_POST['information_case']) AND isset($_POST['position_x']) AND isset($_POST['position_y'])){
	if($_POST['position_x'] >= ($_SESSION['Utilisateur']->personnage(true)->obt_position_x()-4) AND 
		$_POST['position_x'] <= ($_SESSION['Utilisateur']->personnage(true)->obt_position_x()+4) AND 
		$_POST['position_y'] >= ($_SESSION['Utilisateur']->personnage(true)->obt_position_y()-4) AND 
		$_POST['position_y'] <= ($_SESSION['Utilisateur']->personnage(true)->obt_position_y()+4)){
		$liste_personnage = Personnage::obt_personnage_depuis_coordonnees($_POST['position_x'],$_POST['position_y']);
		$reponse = '{"Personnage": [';
		if(is_array($liste_personnage) AND count($liste_personnage)){
			foreach ($liste_personnage as $personnage) {
				$reponse .= '{"id":' . $personnage->obt_id() . '},';
			}
			$reponse = substr($reponse, 0,-1);
		}
		$reponse .= ']}';

		echo $reponse;
	}
	else{
		echo "ok va chier";
	}
}
else{
	echo "Perdu :)";	
}
?>