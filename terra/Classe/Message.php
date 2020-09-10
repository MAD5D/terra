<?php

class Message{

	private $id;
	private $id_personnage;
	private $date_message;
	private $numero_tour;
	private $message;

	public function obt_date_message(){
		return $this->date_message;
	}

	public function obt_numero_tour(){
		return $this->numero_tour;
	}

	public function obt_message(){
		return $this->message;
	}

	public static function creer_message($id_personnage,$numero_tour,$message){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$id_personnage);
		array_push($tableau_req,$numero_tour);
		array_push($tableau_req,$message);

		return $db->prepare("INSERT INTO `Terra__Message`(
			`id`, 
			`id_personnage`, 
			`date_message`, 
			`numero_tour`, 
			`message`
		) VALUES ('',?,NOW(),?,?)",$tableau_req,__CLASS__);
	}

	public static function obt_tout_les_messages_depuis_id_personnage($id_personnage){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$id_personnage);

		return $db->prepare("SELECT * FROM Terra__Message WHERE id_personnage = ? ORDER BY ID DESC",$tableau_req,__CLASS__);
	}

	public static function purger_ancien_message($numero_tour){

		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$numero_tour-2);

		return $db->prepare("DELETE FROM Terra__Message WHERE numero_tour <= ? ORDER BY ID DESC",$tableau_req,__CLASS__);
	}

	public static function obt_dernier_message_depuis_id_personnage($id_personnage){
		$db = App::obt_base_de_donnees();

		$tableau_req = array();
		array_push($tableau_req,$id_personnage);

		return $db->prepare("SELECT * FROM Terra__Message WHERE id_personnage = ? ORDER BY id DESC LIMIT 1",$tableau_req,__CLASS__)[0];
	}
}
?>

