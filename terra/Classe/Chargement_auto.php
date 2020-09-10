<?php

class Chargement_auto{

	static function charge($class_name){
		require './Classe/' . $class_name . '.php';
	}
}

?>