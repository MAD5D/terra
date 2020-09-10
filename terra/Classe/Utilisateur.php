<?php
        ini_set('display_errors','on');
        error_reporting(E_ALL);

class Utilisateur{

	private $id;
	private $pseudo;
	private $mot_de_passe;
	private $mail;
	private $rang;
	private $SESSID;

	public function obt_id(){
		return $this->id;
	}

	public function obt_Nom(){
		return $this->Nom;
	}

	private function obt_Mot_de_passe(){
		return $this->mot_de_passe;
	}

	public function def_SESSID($SESSID){
		$this->SESSID = $SESSID;

		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$SESSID);
		array_push($tableau_req,$this->id);

		return $utilisateur = $db->prepare("UPDATE Terra__Utilisateur SET SESSID=?, date_SESSID=NOW() WHERE id=?",$tableau_req,__CLASS__);
	}

	public function rafraichir_session(){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$this->id);

		return $utilisateur = $db->prepare("UPDATE Terra__Utilisateur SET date_SESSID=NOW() WHERE id=?",$tableau_req,__CLASS__);		
	}

	public function def_Nom($Nom){
		$this->Nom = $Nom;
	}

	public function personnage($forcer_verification = false){
		if(isset($this->Personnage) AND is_a ($this->Personnage,'Personnage') AND !($forcer_verification)){
			return $this->Personnage;
		}
		else{
			$this->Personnage = Personnage::obt_personnage_depuis_id_utilisateur($this->id);
			return $this->Personnage;
		}
		
	}

	public static function obt_utilisateur_depuis_pseudo_mot_de_passe($pseudo,$mot_de_passe){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$pseudo);

		$utilisateur = $db->prepare("SELECT * FROM Terra__Utilisateur WHERE pseudo = ?",$tableau_req,__CLASS__)[0];
		if(password_verify($mot_de_passe, $utilisateur->obt_Mot_de_passe())){
			return $utilisateur;
		}
		else{
			return 0;
		}
	}

	public static function obt_utilisateur_depuis_SESSID($SESSID){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$SESSID);

		return $db->prepare("SELECT * FROM Terra__Utilisateur WHERE SESSID = ? AND date_SESSID > NOW() - INTERVAL 23 MINUTE AND date_SESSID < NOW() + INTERVAL 1 MINUTE ",$tableau_req,__CLASS__)[0];
	}

	public static function creer_utilisateur($pseudo,$mot_de_passe,$mail){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$pseudo);
		array_push($tableau_req,password_hash($mot_de_passe, PASSWORD_BCRYPT));
		array_push($tableau_req,$mail);

		return $db->prepare("INSERT INTO `Terra__Utilisateur`(`id`, `pseudo`, `mot_de_passe`, `mail`, `rang`) VALUES ('',?,?,?,0)",$tableau_req,__CLASS__);
	}

	public static function pseudo_existe($pseudo){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$pseudo);

		return $db->prepare("SELECT * FROM Terra__Utilisateur WHERE pseudo = ?",$tableau_req,__CLASS__);
	}

	public static function mail_existe($mail){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$mail);

		return $db->prepare("SELECT * FROM Terra__Utilisateur WHERE mail = ?",$tableau_req,__CLASS__);
	}
}
?>

