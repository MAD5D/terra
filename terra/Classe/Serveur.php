<?php

class Serveur{
	private $id;
	private $nom;
	private $tour;

	public function obt_id(){
		return $this->id;
	}

	public function obt_nom(){
		return $this->nom;
	}

	public function obt_tour(){
		return $this->tour;
	}

	public function def_nom($nom){
		$this->nom = $nom;
	}

	public function def_tour($tour){
		$this->tour = $tour;
	}

	public function nouveau_tour(){
		$this->tour++;
		$this->sauvegarde();
		return $this->tour;
	}

	public function sauvegarde(){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$this->nom);
		array_push($tableau_req,$this->tour);
		array_push($tableau_req,$this->id);

		return $db->prepare("UPDATE Terra__Serveur SET nom=?, tour=? WHERE id=?",$tableau_req,__CLASS__);
	}

	public static function obtenir_serveur_depuis_id($id_serveur){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$id_serveur);

		return $db->prepare("SELECT * FROM Terra__Serveur WHERE id=?",$tableau_req,__CLASS__)[0];
	}
}