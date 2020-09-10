<?php

class Plateau{
	private $id;
	private $type;
	private $position_x;
	private $position_y;

	public function obt_type(){
		return $this->type;
	}

	public function obt_position_x(){
		return $this->position_x;
	}

	public function obt_position_y(){
		return $this->position_y;
	}

	public static function nouvelle_case_hg($x,$y){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$x);
		array_push($tableau_req,$y);
		array_push($tableau_req,$y);

		return $db->prepare("SELECT * FROM Terra__Plateau WHERE position_x = (?-4) AND position_y >= (?-4) AND position_y <= (?+4) ORDER BY position_y",$tableau_req,__CLASS__);
	}

	public static function nouvelle_case_hd($x,$y){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$y);
		array_push($tableau_req,$x);
		array_push($tableau_req,$x);

		return $db->prepare("SELECT * FROM Terra__Plateau WHERE position_y = (?-4) AND position_x >= (?-4) AND position_x <= (?+4) ORDER BY position_x",$tableau_req,__CLASS__);
	}

	public static function nouvelle_case_bg($x,$y){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$y);
		array_push($tableau_req,$x);
		array_push($tableau_req,$x);

		return ($db->prepare("SELECT * FROM Terra__Plateau WHERE position_y = (?+4) AND position_x >= (?-4) AND position_x <= (?+4) ORDER BY position_x",$tableau_req,__CLASS__));
	}

	public static function nouvelle_case_bd($x,$y){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$x);
		array_push($tableau_req,$y);
		array_push($tableau_req,$y);

		return ($db->prepare("SELECT * FROM Terra__Plateau WHERE position_x = (?+4) AND position_y >= (?-4) AND position_y <= (?+4) ORDER BY position_y",$tableau_req,__CLASS__));
	}

	public static function obtenir_plateau_de_jeu_depuis_coordonnees($position_x,$position_y){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$position_x);
		array_push($tableau_req,$position_x);
		array_push($tableau_req,$position_y);
		array_push($tableau_req,$position_y);

		return $db->prepare("SELECT * FROM Terra__Plateau WHERE position_x >= (?-4) AND position_x <= (?+4) AND position_y >= (?-4) AND position_y <= (?+4) ORDER BY position_x,position_y",$tableau_req,__CLASS__);
	}
}