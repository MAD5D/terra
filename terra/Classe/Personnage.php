<?php

class Personnage{

	private $id;
	private $id_utilisateur;
	private $nom;
	private $homme;
	private $point_vie;
	private $point_vie_max;
	private $point_action;
	private $point_action_max;
	private $point_deplacement;
	private $point_deplacement_max;
	private $point_experience;
	private $point_attaque;
	private $point_defense;
	private $nombre_meutre;
	private $position_x;
	private $position_y;

	public function obt_id(){
		return $this->id;
	}

	public function obt_nom(){
		return $this->nom;
	}

	public function obt_point_action(){
		return $this->point_action;
	}

	public function obt_point_action_max(){
		return $this->point_action_max;		
	}

	public function obt_point_deplacement(){
		return $this->point_deplacement;
	}

	public function obt_point_deplacement_max(){
		return $this->point_deplacement_max;		
	}

	public function obt_point_experience(){
		return $this->point_experience;		
	}

	public function obt_point_vie(){
		return $this->point_vie;
	}

	public function obt_point_vie_max(){
		return $this->point_vie_max;		
	}

	public function obt_point_attaque(){
		return $this->point_attaque;		
	}

	public function obt_point_defense(){
		return $this->point_defense;		
	}

	public function obt_nombre_meutre(){
		return $this->nombre_meutre;
	}

	public function obt_position_x(){
		return $this->position_x;
	}

	public function obt_position_y(){
		return $this->position_y;
	}

	public function def_point_experience($point_experience){
		$this->point_experience = $point_experience;
	}

	public function def_point_action($point_action){
		$this->point_action = $point_action;
	}

	public function def_point_deplacement($point_deplacement){
		$this->point_deplacement = $point_deplacement;
	}

	public function def_position_x($position_x){
		$this->position_x = $position_x;
	}

	public function def_position_y($position_y){
		$this->position_y = $position_y;
	}

	public function def_nombre_meutre($nombre_de_meurtre){
		$this->nombre_meutre = $nombre_de_meurtre;
	}

	public function obt_personnage_autour(){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$this->position_x);
		array_push($tableau_req,$this->position_x);
		array_push($tableau_req,$this->position_y);
		array_push($tableau_req,$this->position_y);
		array_push($tableau_req,$this->id);

		return $db->prepare("SELECT * FROM Terra__Personnage WHERE position_x >= ?-4 AND position_x <= ?+4 AND position_y >= ?-4 AND position_y <= ?+4 AND id != ?",$tableau_req,__CLASS__);
	}

	public function obt_personnage_en_bordure($direction){
		if($direction == "hd"){
			$db = App::obt_base_de_donnees();

			$tableau_req = array();
			array_push($tableau_req,$this->position_x);
			array_push($tableau_req,$this->position_x);
			array_push($tableau_req,$this->position_y);

			return $db->prepare("SELECT * FROM Terra__Personnage WHERE position_x >= ?-4 AND position_x <= ?+4 AND position_y = ?-4",$tableau_req,__CLASS__);
		}
		else if($direction == "hg"){
			$db = App::obt_base_de_donnees();

			$tableau_req = array();
			array_push($tableau_req,$this->position_y);
			array_push($tableau_req,$this->position_y);
			array_push($tableau_req,$this->position_x);

			return $db->prepare("SELECT * FROM Terra__Personnage WHERE position_y >= ?-4 AND position_y <= ?+4 AND position_x = ?-4",$tableau_req,__CLASS__);
		}
		else if($direction == "bd"){
			$db = App::obt_base_de_donnees();

			$tableau_req = array();
			array_push($tableau_req,$this->position_y);
			array_push($tableau_req,$this->position_y);
			array_push($tableau_req,$this->position_x);

			return $db->prepare("SELECT * FROM Terra__Personnage WHERE position_y >= ?-4 AND position_y <= ?+4 AND position_x = ?+4",$tableau_req,__CLASS__);
		}
		else if($direction == "bg"){
			$db = App::obt_base_de_donnees();

			$tableau_req = array();
			array_push($tableau_req,$this->position_x);
			array_push($tableau_req,$this->position_x);
			array_push($tableau_req,$this->position_y);

			return $db->prepare("SELECT * FROM Terra__Personnage WHERE position_x >= ?-4 AND position_x <= ?+4 AND position_y = ?+4",$tableau_req,__CLASS__);
		}
	}

	public function augmenter_point_action($nombre_de_point){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$this->id);

		$personnage_verification = $db->prepare("SELECT * FROM Terra__Personnage WHERE id=? ",$tableau_req,__CLASS__)[0];
		if($personnage_verification->point_experience >= $nombre_de_point){
			$tableau_req = array();
			array_push($tableau_req,$nombre_de_point);
			array_push($tableau_req,$nombre_de_point);
			array_push($tableau_req,$this->id);

			$resultat = $db->prepare("UPDATE `Terra__Personnage` SET point_experience=point_experience-?,point_attaque=point_attaque+? WHERE id=?",$tableau_req,__CLASS__);
			$this->point_attaque += $nombre_de_point;
			$this->point_experience -= $nombre_de_point;

			return true;
		}
		else{
			return false;
		}
	}

	public function augmenter_point_defense($nombre_de_point){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$this->id);

		$personnage_verification = $db->prepare("SELECT * FROM Terra__Personnage WHERE id=? ",$tableau_req,__CLASS__)[0];
		if($personnage_verification->point_experience >= $nombre_de_point){
			$tableau_req = array();
			array_push($tableau_req,$nombre_de_point);
			array_push($tableau_req,$nombre_de_point);
			array_push($tableau_req,$this->id);

			$resultat = $db->prepare("UPDATE `Terra__Personnage` SET point_experience=point_experience-?,point_defense=point_defense+? WHERE id=?",$tableau_req,__CLASS__);
			$this->point_defense += $nombre_de_point;
			$this->point_experience -= $nombre_de_point;

			return true;
		}
		else{
			return false;
		}
	}

	public function sauvegarde(){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$this->point_vie);
		array_push($tableau_req,$this->point_action);
		array_push($tableau_req,$this->point_deplacement);
		array_push($tableau_req,$this->point_experience);
		array_push($tableau_req,$this->point_attaque);
		array_push($tableau_req,$this->point_defense);
		array_push($tableau_req,$this->point_vie_max);
		array_push($tableau_req,$this->point_action_max);
		array_push($tableau_req,$this->point_deplacement_max);
		array_push($tableau_req,$this->nombre_meutre);
		array_push($tableau_req,$this->position_x);
		array_push($tableau_req,$this->position_y);
		array_push($tableau_req,$this->id);

		$resultat = $db->prepare("UPDATE Terra__Personnage SET 
			`point_vie`=?,
			`point_action`=?,
			`point_deplacement`=?,
			`point_experience`=?,
			`point_attaque`=?,
			`point_defense`=?,
			`point_vie_max`=?,
			`point_action_max`=?,
			`point_deplacement_max`=?,
			`nombre_meutre`=?,
			`position_x`=?,
			`position_y`=?
			 WHERE id=?",$tableau_req,__CLASS__);

		return "OK";

	}

	public function obt_message(){
		return Message::obt_tout_les_messages_depuis_id_personnage($this->id);
	}

	public function def_message($message){
		Message::creer_message($this->id,Serveur::obtenir_serveur_depuis_id(1)->obt_tour(),$message);
	}

	public function verification_deplacement(){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$this->id);

		return $db->prepare("SELECT * FROM Terra__Personnage WHERE id=?",$tableau_req,__CLASS__)[0];
	}

	public function attaque($personnage_cible){
		if($this->point_action <= 0){
			return "";
		}
		$this->def_message("Vous avez infliger " . $this->point_attaque . " point(s) de dégat à " . $personnage_cible->id . ".");
		$personnage_cible->def_message($this->nom . " vous à infliger " . $this->point_attaque . " point(s) de dégat, vengez vous !!");
		if($personnage_cible->degat($this->point_attaque) == 0){
			$this->def_nombre_meutre($this->obt_nombre_meutre()+1);
			$reponse =  "TU AS FAIT UN MEUTRE";
		}
		else{
			$reponse =  "LOOOOOOOOOOOOOOOOPETTE";
		}

		$this->point_action--;
		$this->sauvegarde();

		return $reponse;
	}

	public function degat($degat){
		$this->point_vie = $this->point_vie - $degat;
		$etat_de_sante = $this->verif_etat_de_sante();
		$this->sauvegarde();
		return $etat_de_sante;
	}

	private function verif_etat_de_sante(){
		if($this->point_vie <= 0){
			$this->point_vie = $this->point_vie_max;
			$this->position_x = rand(-10,10);
			$this->position_y = rand(-10,10);
			return 0;
		}
		else{
			return 1;
		}
	}

	public function obt_dernier_message(){
		return Message::obt_dernier_message_depuis_id_personnage($this->id);
	}

	public static function classement_personnage(){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();

		return $db->prepare("SELECT * FROM Terra__Personnage WHERE 1 ORDER BY nombre_meutre DESC",$tableau_req,__CLASS__);
	}

	public static function obt_personnage_depuis_id_utilisateur($id_utilisateur){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$id_utilisateur);

		if($personnage = $db->prepare("SELECT * FROM Terra__Personnage WHERE id_utilisateur = ?",$tableau_req,__CLASS__)){
			return $personnage[0];
		}
		else{
			return 0;
		}
	}

	public static function obt_personnage_depuis_id_personnage($id_personnage){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$id_personnage);

		if($personnage = $db->prepare("SELECT * FROM Terra__Personnage WHERE id = ?",$tableau_req,__CLASS__)){
			return $personnage[0];
		}
		else{
			return 0;
		}
	}

	public static function obt_personnage_depuis_coordonnees($position_x,$position_y){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$position_x);
		array_push($tableau_req,$position_y);

		if($personnage = $db->prepare("SELECT * FROM Terra__Personnage WHERE position_x = ? AND position_y = ?",$tableau_req,__CLASS__)){
			return $personnage;
		}
		else{
			return 0;
		}
	}

	public static function creer_personnage($id_utilisateur,$nom,$sexe){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$id_utilisateur);
		array_push($tableau_req,$nom);
		array_push($tableau_req,$sexe);

		return $db->prepare("INSERT INTO `Terra__Personnage`(
			`id`, 
			`id_utilisateur`, 
			`nom`, 
			`homme`, 
			`point_vie`, 
			`point_action`, 
			`point_deplacement`, 
			`point_experience`, 
			`point_attaque`, 
			`point_defense`, 
			`point_vie_max`, 
			`point_action_max`, 
			`point_deplacement_max`,
			`nombre_meutre`
		) VALUES ('',?,?,?,'100','15','24','50','10','10','100','15','24','0')",$tableau_req,__CLASS__);
	}

	public static function pseudo_existe($nom){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$nom);

		return $db->prepare("SELECT * FROM Terra__Personnage WHERE nom = ?",$tableau_req,__CLASS__);
	}
}
?>

