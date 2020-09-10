<?php
        ini_set('display_errors','on');
        error_reporting(E_ALL);

require "./Classe/Chargement_auto.php";
spl_autoload_register(array('Chargement_auto','charge'));

session_start();

if(isset($_SESSION['Utilisateur'])){
	header('Location: accueil.php');
}

if(isset($_POST['formulaire']) AND $_POST['formulaire'] == "connection"){
	if(isset($_POST['Pseudo']) AND isset($_POST['Mot_de_passe'])){
		//print_r(Utilisateur::obt_utilisateur_depuis_pseudo_mot_de_passe($_POST['Pseudo'],$_POST['Mot_de_passe']));
		if($Utilisateur = Utilisateur::obt_utilisateur_depuis_pseudo_mot_de_passe($_POST['Pseudo'],$_POST['Mot_de_passe'])){
			$_SESSION['Utilisateur'] = $Utilisateur;
			$_SESSION['Utilisateur']->def_SESSID(session_id());
			
			header('Location: accueil.php');
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
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="default.css">
	</head>
	<body>


		<?php
		/*
		$i = -10;
		$j = -10;
		while($i <= 10){
			while($j <= 10){
				echo "INSERT INTO `Terra__Plateau`(`id`, `type`, `position_x`, `position_y`) VALUES ('',1," . $i . "," . $j . "); <br />";
				$j++;
			}
			$j=-10;
			$i++;
		}
*/
		?>

		<div style="margin-bottom:20px">
			<?php if(isset($error['connection']) AND $error['connection']) echo "Identifiant incorrecte"; ?>
			<form action="index.php" method="POST">
				<label for="Pseudo"> Pseudo : </label>
				<input type="text" name="Pseudo" id="Pseudo" <?php if(isset($error['connection']) AND $error['connection'] AND isset($_POST['Pseudo'])) echo "value=\"" . $_POST['Pseudo'] . "\""; ?> required><br />
				<label for="Mot_de_passe"> Mot de passe : </label>
				<input type="password" name="Mot_de_passe" id="Mot_de_passe" requied>
				<input type="hidden" name="formulaire" value="connection"><br />
				<input type="submit" value="Connection">
			</form>
		</div>
		<div>
			<?php if(isset($error['mot_de_passe_different']) AND $error['mot_de_passe_different']) echo "Les mot de passe doivent être identique."; ?>
			<?php if(isset($error['pseudo_deja_prit']) AND $error['pseudo_deja_prit']) echo "Le pseudo est déjà prit"; ?>
			<form action="index.php" method="POST">
				<label for="Pseudo_inscription"> Pseudo : </label>
				<input type="text" name="Pseudo_inscription" id="Pseudo_inscription" minlength="3" maxlength="15" 
				<?php if(isset($error) AND $error AND isset($_POST['Pseudo_inscription'])) echo "value=\"" . $_POST['Pseudo_inscription'] . "\""; ?>				required><br />
				<label for="Mot_de_passe_inscription"> Mot de passe : </label>
				<input type="password" name="Mot_de_passe_inscription" id="mot_de_passe_inscription" required><br />
				<lavel for="Mot_de_passe_inscription_confirmation">Mot de passe (confirmation) : </lavel>
				<input type="password" name="Mot_de_passe_inscription_confirmation" id="Mot_de_passe_inscription_confirmation" required><br />
				<lavel for="Mail_insciption">Courriel : </lavel>
				<input type="email" name="Mail_inscription" <?php if(isset($error)AND $error AND isset($_POST['Mail_inscription'])) echo "value=\"" . $_POST['Mail_inscription'] . "\""; ?>	id="Mail_inscription" required>
				<input type="hidden" name="formulaire" value="inscription"><br />
				<input type="submit" value="Inscription">
		</div>
	</body>
</html>


