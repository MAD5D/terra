<?php
        ini_set('display_errors','on');
        error_reporting(E_ALL);

require "./Classe/Chargement_auto.php";
spl_autoload_register(array('Chargement_auto','charge'));

session_start();

if(!(isset($_SESSION['Utilisateur']))){
	header('Location: index.php');
}

if($_SESSION['Utilisateur']->personnage()){
	header('Location: accueil.php');
}

if(isset($_POST['formulaire']) AND $_POST['formulaire'] == "creation_personnage"){
	if(isset($_POST['Nom']) AND isset($_POST['sexe'])){
		if(!(Personnage::pseudo_existe($_POST['Nom']))){
			print_r(Personnage::creer_personnage($_SESSION['Utilisateur']->obt_id(),$_POST['Nom'],$_POST['sexe']));
			//header('Location: accueil.php');
		}
		else{
			$erreur['nom_deja_prit'] = true;
		}
		//
		/*if(!($_POST['Mot_de_passe_inscription'] != $_POST['Mot_de_passe_inscription_confirmation'])){
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
		}*/
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="default.css">
	</head>
	<body>
		<div style="margin-bottom:20px">
			<form action="creation_personnage.php" method="POST">
				<?php if(isset($erreur['nom_deja_prit'])) echo "Le nom est déjà prit. <br />"; ?>
				<label for="Nom"> Nom : </label>
				<input type="text" name="Nom" id="Nom" <?php if(isset($erreur['nom_deja_prit']) AND isset($_POST['Nom'])) echo "value=\"" . $_POST['Nom'] . "\""; ?> pattern="[A-Za-z]+" title="Uniquement des lettres." required>
				<div>Sexe :</div>
				<input type="radio" id="homme" name="sexe" value="1" required >
				<label for="homme">Male</label>
				<input type="radio" id="femme" name="sexe" value="0" required>
				<label for="femme">Female</label>
				<input type="hidden" name="formulaire" value="creation_personnage"><br />
				<input type="submit" value="Creation">
			</form>
		</div>
	</body>
</html>



