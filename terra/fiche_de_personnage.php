<?php
        ini_set('display_errors','on');
        error_reporting(E_ALL);

require "./Classe/Chargement_auto.php";
spl_autoload_register(array('Chargement_auto','charge'));

session_start();

if(!(isset($_SESSION['Utilisateur']))){
	header('index.php');
	exit();
}


if(!($personnage = $_SESSION['Utilisateur']->personnage())){
	header('Location: creation_personnage.php');
	exit();
}

if(isset($_POST['ajout_point_action'])){
	if($_POST['ajout_point_action'] <= $personnage->obt_point_experience()){
		echo $personnage->augmenter_point_action($_POST['ajout_point_action']);
	}
	else{
		$erreur['pas_assez_exp'] = true;
	}
}

if(isset($_POST['ajout_point_defense'])){
	if($_POST['ajout_point_defense'] <= $personnage->obt_point_experience()){
		echo $personnage->augmenter_point_defense($_POST['ajout_point_defense']);
	}
	else{
		$erreur['pas_assez_exp'] = true;
	}
}
/*
if(isset($_POST['formulaire']) AND $_POST['formulaire'] == "connection"){
	if(isset($_POST['Pseudo']) AND isset($_POST['Mot_de_passe'])){
		//print_r(Utilisateur::obt_utilisateur_depuis_pseudo_mot_de_passe($_POST['Pseudo'],$_POST['Mot_de_passe']));
		if($Utilisateur = Utilisateur::obt_utilisateur_depuis_pseudo_mot_de_passe($_POST['Pseudo'],$_POST['Mot_de_passe'])){
			session_start();
			$_SESSION['Utilisateur'] = $Utilisateur;
			header('Location: en_suspend.php');
			exit();
		}
		else{
			$error['connection'] = true;
		}
	}
}

if(isset($_POST['formulaire']) AND $_POST['formulaire'] == "inscription"){
	if(isset($_POST['Pseudo_inscription']) AND isset($_POST['Mot_de_passe_inscription']) AND isset($_POST['Mot_de_passe_inscription_confirmation']) AND isset($_POST['Mail_inscription'])){
		//
		if(!($_POST['Mot_de_passe_inscription'] != $_POST['Mot_de_passe_inscription_confirmation'])){
			if(!(Utilisateur::pseudo_existe($_POST['Pseudo_inscription']))){
				if(!(Utilisateur::mail_existe($_POST['Mail_inscription']))){
					Utilisateur::creer_utilisateur($_POST['Pseudo_inscription'],$_POST['Mot_de_passe_inscription'],$_POST['Mail_inscription']);
					echo "inscription validé";
				}else{
					echo "NANNNNN";
				}
			}
			else{
				$error['pseudo_deja_prit'] = true;
			}
			
		}
		else{
			$error['mot_de_passe_different'] = true;
		}
	}
}*/

?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="default.css">
	</head>
	<body>
		<header>
			<?php include "./menu.php"; ?>
		</header>
		<h1>Personnage</h1>
		Nom : <?php echo $personnage->obt_nom(); ?> <br />
		Point d'action :  <?php echo $personnage->obt_point_action() . '/' . $personnage->obt_point_action_max(); ?><br />
		Point de deplacement :  <?php echo $personnage->obt_point_deplacement() . '/' . $personnage->obt_point_deplacement_max(); ?><br />
		Point d'experience : <?php echo $personnage->obt_point_experience(); ?><br />
		Point de vie : <?php echo $personnage->obt_point_vie() . "/" . $personnage->obt_point_vie_max(); ?><br />
		<?php
		if(isset($erreur['pas_assez_exp'])){
			echo "Pas assez de points d'expérience <br />";
		}
		?>
		Point d'attaque : <?php echo $personnage->obt_point_attaque(); ?>
<form method="post" action="fiche_de_personnage.php">
	<input type="hidden" name="ajout_point_action" value="1">
	<input type="submit" value="+1">
</form>
<form method="post" action="fiche_de_personnage.php">
	<input type="hidden" name="ajout_point_action" value="5">
	<input type="submit" value="+5">
</form>
<form method="post" action="fiche_de_personnage.php">
	<input type="hidden" name="ajout_point_action" value="10">
	<input type="submit" value="+10">
</form>

		<br />
		Point de défense : <?php echo $personnage->obt_point_defense(); ?><br />

<form method="post" action="fiche_de_personnage.php">
	<input type="hidden" name="ajout_point_defense" value="1">
	<input type="submit" value="+1">
</form>
<form method="post" action="fiche_de_personnage.php">
	<input type="hidden" name="ajout_point_defense" value="5">
	<input type="submit" value="+5">
</form>
<form method="post" action="fiche_de_personnage.php">
	<input type="hidden" name="ajout_point_defense" value="10">
	<input type="submit" value="+10">
</form>


	</body>
</html>