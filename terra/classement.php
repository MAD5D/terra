<?php

require "./Classe/Chargement_auto.php";
spl_autoload_register(array('Chargement_auto','charge'));

session_start();

if(!(isset($_SESSION['Utilisateur']) AND $_SESSION['Utilisateur']->personnage())){
	header('Location: creation_personnage.php');
	exit();
}
else{
	//echo "!";
}

$classement_personnage = Personnage::classement_personnage();

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
		classement_personnage : <br />
		<?php
		//print_r($classement_personnage);
			//////////////////////!!!!!!!!!\\\\\\\\\\\\\\\\\\\\\
			// A OPTIMISER !
			foreach($classement_personnage as $personnage)
			{
			?>
				<span><?php echo $personnage->obt_nom(); ?></span> <span><?php echo $personnage->obt_nombre_meutre(); ?></span><br />
			<?php
			}
		?>
	</body>
</html>
