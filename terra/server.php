<?php
        ini_set('display_errors','on');
        error_reporting(E_ALL);

require "/var/www/html/terra/Classe/Utilisateur.php";
require "/var/www/html/terra/Classe/Personnage.php";
require "/var/www/html/terra/Classe/App.php";
require "/var/www/html/terra/Classe/Base_de_donnees.php";


Class Server{
	private $_actualClient;
	private $_socket;
	private $_clients;
	private $_SSID_clients;

	public function __construct(){
		$this->_clients = array();
		$this->_SSID_clients = array();


		$this->_socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
		socket_set_option($this->_socket, SOL_SOCKET, SO_REUSEADDR, 1); // A VERIFIER
		socket_bind($this->_socket,'51.77.194.229',9820);
		socket_listen($this->_socket);


		$this->_actualClient = socket_accept($this->_socket);
		$request = socket_read($this->_actualClient,5000);
		//echo $request;

		///// Creation de l'entête \\\\\
		preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
		preg_match('#Cookie: PHPSESSID=(.*)\r\n#', $request, $matches2);
		$key = base64_encode(pack(
		    'H*',
		    sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
		));


		$headers = "HTTP/1.1 101 Switching Protocols\r\n";
		$headers .= "Upgrade: websocket\r\n";
		$headers .= "Connection: Upgrade\r\n";
		$headers .= "Sec-WebSocket-Version: 13\r\n";
		$headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
		socket_write($this->_actualClient, $headers, strlen($headers));

		$utilisateur = Utilisateur::obt_utilisateur_depuis_SESSID($matches2[1]);

		$json_a_envoyer = "[";

		foreach ($utilisateur->personnage()->obt_personnage_autour() as $personnage){
			$json_a_envoyer .= '{"id":"' . $personnage->obt_id() . '",
				"x": "' . $personnage->obt_position_x() . '",
				"y": "' . $personnage->obt_position_y() . '"
			},';
		}
		$json_a_envoyer = substr($json_a_envoyer,0,-1);
		$json_a_envoyer .= ']';

		sleep(1);		

		    $content = $json_a_envoyer;
		    $response = chr(129) . chr(strlen($content)) . $content;

    		$b1 = 0x80 | (0x1 & 0x0f);
    		$length = strlen($content);
		    $response = pack('CC', $b1, $length) . $content;

		    socket_write($this->_actualClient, $response);
			array_push($this->_clients,$this->_actualClient);
			array_push($this->_SSID_clients,$matches2[1]);

		sleep(5);


		echo $this->_actualClient;
		socket_set_nonblock($this->_actualClient);

//session_id($matches2[1]);
//session_start();



		print_r($matches2[1]);
		socket_set_nonblock($this->_socket);


		$this->work();
	}




	public function work(){
		$i = 0;
		$rupture = false;
		while(true AND !($rupture)){
			sleep(1);
			echo $i;
			$i++;

			$this->_actualClient = socket_accept($this->_socket);
			$request = @socket_read($this->_actualClient,5000);
			if($request){
				echo "JE ME CONNECTE";
				///// Creation de l'entête \\\\\
				preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
				preg_match('#Cookie: PHPSESSID=(.*)\r\n#', $request, $matches2);
				$key = base64_encode(pack(
				    'H*',
				    sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
				));


				$headers = "HTTP/1.1 101 Switching Protocols\r\n";
				$headers .= "Upgrade: websocket\r\n";
				$headers .= "Connection: Upgrade\r\n";
				$headers .= "Sec-WebSocket-Version: 13\r\n";
				$headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
				socket_write($this->_actualClient, $headers, strlen($headers));

				array_push($this->_clients,$this->_actualClient);
				array_push($this->_SSID_clients,$matches2[1]);

				echo "AAAAAAAAA";
				echo $matches2[1];
				echo "BBBBBBBBB";
			}
			else{
				echo "personne";
			}

			/*$content = Utilisateur::obt_utilisateur_depuis_pseudo_mot_de_passe('123456','123456')->personnage()->obt_position_x();


		    $content .= "abcdefghijklmnopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqopqrstuvmnopqrstuvwx FFF";
		    $response = chr(129) . chr(strlen($content)) . $content;

    		$b1 = 0x80 | (0x1 & 0x0f);
    		$length = strlen($content);
		    $response = pack('CSC', $b1, 126, $length) . $content;*/

echo "\n\n" . count($this->_clients) . "\n\n";

		    $j = 0;
		    while($j < count($this->_clients)){

		    	$utilisateur = Utilisateur::obt_utilisateur_depuis_SESSID($this->_SSID_clients[$j]);
				$json_a_envoyer = "[";

				foreach ($utilisateur->personnage()->obt_personnage_autour() as $personnage){
					$json_a_envoyer .= '{"id":"' . $personnage->obt_id() . '",
						"x": "' . $personnage->obt_position_x() . '",
						"y": "' . $personnage->obt_position_y() . '"
					},';
				}
				$json_a_envoyer = substr($json_a_envoyer,0,-1);
				$json_a_envoyer .= ']';
				$content = $json_a_envoyer;
				$response = chr(129) . chr(strlen($content)) . $content;

				$b1 = 0x80 | (0x1 & 0x0f);
				$length = strlen($content);
				$response = pack('CC', $b1, $length) . $content;




		    	if(socket_write($this->_clients[$j], $response)){
		    		$j++;
			    }
			    else{
			    	array_splice($this->_clients,$j,1);
			    	array_splice($this->_SSID_clients,$j,1);
			    	//$rupture = true;
			    }
		    }
		    /*if(socket_write($this->_actualClient, $response)){
		    	//echo "AAAAAAAAAAAAAAAAA";
		    }
		    else{
		    	//echo "MMMMMMMMMMMMMMMMM";
		    	//$rupture = true;
		    }*/
		}
	}
}

new Server;