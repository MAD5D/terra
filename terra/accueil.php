<?php
        ini_set('display_errors','on');
        error_reporting(E_ALL);

require "./Classe/Chargement_auto.php";
spl_autoload_register(array('Chargement_auto','charge'));

session_start();

if(!(isset($_SESSION['Utilisateur']))){
	header('Location: index.php');
	exit();
}
else{
	$_SESSION['Utilisateur']->rafraichir_session();
}


/**** Réception des cases pour l'initialisation de la carte ****/
$case_de_jeu = Plateau::obtenir_plateau_de_jeu_depuis_coordonnees($_SESSION['Utilisateur']->personnage()->obt_position_x(),$_SESSION['Utilisateur']->personnage()->obt_position_y());

/**** Transformation des cases en JSON pour une exploitation JS ****/
$case_de_jeu_JSON = json_encode($case_de_jeu);
$case_de_jeu_JSON = "{ \"case_de_jeu\": [";

foreach ($case_de_jeu as $case) {
  $case_de_jeu_JSON = $case_de_jeu_JSON . "{\"type\": " . $case->obt_type() . "," . "\"position_x\": " . $case->obt_position_x() . "," . "\"position_y\": " . $case->obt_position_y() . "},";
}
$case_de_jeu_JSON = substr($case_de_jeu_JSON,0,-1);
$case_de_jeu_JSON = $case_de_jeu_JSON . "]}";
/**** Fin transformation des cases en JSON pour une exploitation JS ****/

?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="default.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pixi.js/5.3.3/pixi.min.js"></script>
	</head>
	<body>
		<header>
			<?php include "./menu.php"; ?>
		</header>
		  <script type="text/javascript">

    /**** Transfert des informations PHP vers JS ****/
    var case_chargement = <?php echo $case_de_jeu_JSON; ?>;
    var id_personnage  = <?php echo $_SESSION['Utilisateur']->personnage()->obt_id(); ?>;
    var position_personnage_x  = <?php echo $_SESSION['Utilisateur']->personnage()->obt_position_x(); ?>;
    var position_personnage_y = <?php echo $_SESSION['Utilisateur']->personnage()->obt_position_y(); ?>;
    var point_action =  <?php echo $_SESSION['Utilisateur']->personnage()->obt_point_action(); ?>;
    var point_deplacement = <?php echo $_SESSION['Utilisateur']->personnage()->obt_point_deplacement(); ?>;



    let type = "WebGL"
    if(!PIXI.utils.isWebGLSupported()){
      type = "canvas"
    }

    /**** Initialisation de PixiJS ****/
    let app = new PIXI.Application({
      width: 512, 
      height: 350,
      resolution: 1.75
    });
    document.body.appendChild(app.view);

    /**** Chargement des images ****/
    PIXI.loader
    .add("images/texture_sable.png")
    .add("images/case2.png")
    .add("images/vampir11.png")
    .add("images/fleche.png")
    .load(setup);

    /**** Variable général ****/
    let sprite;
    let case_de_jeu = new Array();
    let element_du_jeu = new Array();


    /**** fonction général ****/
    function Attaquer_personnage(id_personnage){
      // A RETRAVAILLER
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
          console.log(xhr.responseText);
          if(xhr.responseText == "Frape"){
          	Ajouter_dernier_message();
          	point_action--;
          	affichage_point_action_deplacement();
          }
        }
        else{
        }
      };

      xhr.open("POST", "attaque_personnage.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("attaque=true&id_cible=" + id_personnage + "");
    }

    function Ajouter_dernier_message(){
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
				console.log(xhr.responseText);
				var reponse_serveur = JSON.parse(xhr.responseText);
				console.log(reponse_serveur['message']);
				document.getElementById('message').innerHTML = "<div>Tour : " + reponse_serveur['message']['numero_tour'] + " date : " + reponse_serveur['message']['date_message'] + " " + reponse_serveur['message']['message'] + "</div>" + document.getElementById('message').innerHTML;
			}
			else{
			}
		};

		xhr.open("POST", "message.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send("rafraichir=true");
    }

    // A REFAIRE
    function Afficher_information_visuel(case_plateau){
      //document.getElementById('affichage').innerHTML = "x : " + case_plateau.position_damier_x + " y : " + case_plateau.position_damier_y + "";
      Affichier_information_bis(position_personnage_x,position_personnage_y);
    }

    function affichage_point_action_deplacement(){
    	document.getElementById('affichage_point_action_deplacement').innerHTML = "action : " + point_action + " deplacement : " + point_deplacement + "";
    }

    function Affichier_information_bis(position_x,position_y){

      document.getElementById('affichage').innerHTML = "x : " + position_x + " y : " + position_y + "";
      var position_damier_x = position_x;
      var position_damier_y = position_y;

    	// A RETRAVAILLER
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
        	console.log(xhr.responseText);
          //document.getElementById('affichage').innerHTML = document.getElementById('affichage').innerHTML + xhr.responseText;
          reponse_serveur = JSON.parse(xhr.responseText);
console.log("??????????");
          var i = 0;
          while(i < reponse_serveur['Personnage'].length){
          	console.log(id_personnage + "ID PERSO"); 
            if(reponse_serveur['Personnage'][i]['id'] != id_personnage){
              document.getElementById('affichage').innerHTML = document.getElementById('affichage').innerHTML + "Personnage : " + reponse_serveur['Personnage'][i]['id'];
              console.log('perso_x : ' + position_personnage_x + " damier_x : " + position_damier_x)
              if(position_personnage_x == position_damier_x){
                document.getElementById('affichage').innerHTML = document.getElementById('affichage').innerHTML + " <button onclick=\"Attaquer_personnage(" + reponse_serveur['Personnage'][i]['id'] + ");\" id_personnage=\"LOULOU\" class=\"favorite styled\" type=\"button\">Attaquer</button> ";                
              }

            }
            else{
            }
            i++;
          }
        }
        else{
          console.log("POK");
        }
      };

      xhr.open("POST", "deplacement.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("information_case=true&position_x=" + position_x + "&position_y=" + position_y + "");
    }

    function Afficher_Information(eventData){
      document.getElementById('affichage').innerHTML = "x : " + this.position_damier_x + " y : " + this.position_damier_y + "";
      var position_damier_x = this.position_damier_x;
      var position_damier_y = this.position_damier_y;


      // A RETRAVAILLER
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
          //document.getElementById('affichage').innerHTML = document.getElementById('affichage').innerHTML + xhr.responseText;
          reponse_serveur = JSON.parse(xhr.responseText);

          var i = 0;
          while(i < reponse_serveur['Personnage'].length){
            if(reponse_serveur['Personnage'][i]['id'] != id_personnage){
              document.getElementById('affichage').innerHTML = document.getElementById('affichage').innerHTML + "Personnage : " + reponse_serveur['Personnage'][i]['id'];
              if(position_personnage_x == position_damier_x){
                document.getElementById('affichage').innerHTML = document.getElementById('affichage').innerHTML + " <button onclick=\"Attaquer_personnage(" + reponse_serveur['Personnage'][i]['id'] + ");\" id_personnage=\"LOULOU\" class=\"favorite styled\" type=\"button\">Attaquer</button> ";                
              }

            }
            else{
            }
            i++;
          }
        }
        else{
          console.log("POK");
        }
      };

      xhr.open("POST", "deplacement.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("information_case=true&position_x=" + this.position_damier_x + "&position_y=" + this.position_damier_y + "");
    }

function setup() {

  ////////Génération du terrain\\\\\\\
  let texture = PIXI.utils.TextureCache["images/texture_sable.png"];
  sprite = new PIXI.Sprite(texture);

  var i = 0;
  var j = 0;
  var x = 241;
  var y = 32;
  var i_case_chargement = 0;

  var decalage_x = 27;
  var decalage_y = 14.2;

  while(j < 9){
    while(i < 9){
      if(i_case_chargement < case_chargement["case_de_jeu"].length){
        if(case_chargement["case_de_jeu"][i_case_chargement]['position_x'] == position_personnage_x+j-4 && case_chargement["case_de_jeu"][i_case_chargement]['position_y'] == (position_personnage_y+i-4)){

          let temp = new PIXI.Sprite(texture);
          temp.interactive = true;
          temp.anchor.set(0.5,0.5);
          temp.scale.set(0.27,0.27);
          temp.on('mousedown', Afficher_Information);
          temp.on('touchstart', Afficher_Information);
          app.stage.addChild(temp);
          temp.position.set(x + j*decalage_x - (i*decalage_x),y+(j*decalage_y)+i*decalage_y);
          case_de_jeu.push(temp);
          temp.position_damier_x = case_chargement["case_de_jeu"][i_case_chargement]['position_x'];
          temp.position_damier_y = case_chargement["case_de_jeu"][i_case_chargement]['position_y'];
          if(case_chargement["case_de_jeu"][i_case_chargement]['position_x'] == position_personnage_x && case_chargement["case_de_jeu"][i_case_chargement]['position_y'] == position_personnage_y){
            Afficher_information_visuel(temp);
          }
          i_case_chargement++;
        }
        else{
        }
      }

      i++;
    }
    i=0;
    j++;
  }

  ////////Génération et placement du personnage\\\\\\\\\
  let texture_personnage = PIXI.utils.TextureCache["images/vampir11.png"];
  sprite_personnage = new PIXI.Sprite(texture_personnage);
  app.stage.addChild(sprite_personnage);
  sprite_personnage.anchor.set(0.5,0.5);
  sprite_personnage.position.set(239,132);

  //Génération et placement des flèches directionnelles\\
  let texture_fleche = PIXI.utils.TextureCache["images/fleche.png"];

  sprite_haut_droite = new PIXI.Sprite(texture_fleche);
  sprite_haut_droite.position.set(380,40);
  sprite_haut_droite.anchor.set(0.5,0.5);
  sprite_haut_droite.on('mousedown', Deplacement_haut_droite);
  sprite_haut_droite.on('touchstart', Deplacement_haut_droite);
  sprite_haut_droite.interactive = true;

  if(position_personnage_y == -10){
    sprite_haut_droite.visible = false;
  }

  sprite_haut_gauche = new PIXI.Sprite(texture_fleche);
  sprite_haut_gauche.position.set(80,40);
  sprite_haut_gauche.anchor.set(0.5,0.5);
  sprite_haut_gauche.rotation = 3.14*1.5;
  sprite_haut_gauche.on('mousedown', Deplacement_haut_gauche);
  sprite_haut_gauche.on('touchstart', Deplacement_haut_gauche);
  sprite_haut_gauche.interactive = true;

  if(position_personnage_x == -10){
    sprite_haut_gauche.visible = false;
  }

  sprite_bas_droite = new PIXI.Sprite(texture_fleche);
  sprite_bas_droite.position.set(380,240);
  sprite_bas_droite.anchor.set(0.5,0.5);
  sprite_bas_droite.rotation = 3.14*0.5;
  sprite_bas_droite.on('mousedown', Deplacement_bas_droite);
  sprite_bas_droite.on('touchstart', Deplacement_bas_droite);
  sprite_bas_droite.interactive = true;

  if(position_personnage_x == 10){
    sprite_bas_droite.visible = false;
  }

  sprite_bas_gauche = new PIXI.Sprite(texture_fleche);
  sprite_bas_gauche.position.set(80,240);
  sprite_bas_gauche.anchor.set(0.5,0.5);
  sprite_bas_gauche.rotation = 3.14;
  sprite_bas_gauche.on('mousedown', Deplacement_bas_gauche);
  sprite_bas_gauche.on('touchstart', Deplacement_bas_gauche);
  sprite_bas_gauche.interactive = true;

  if(position_personnage_y == 10){
    sprite_bas_gauche.visible = false;
  }

  app.stage.addChild(sprite_haut_droite);
  app.stage.addChild(sprite_haut_gauche);
  app.stage.addChild(sprite_bas_droite);
  app.stage.addChild(sprite_bas_gauche);

  function Deplacement_haut_droite(eventData){
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
      	console.log(xhr.responseText);
        reponse_serveur = JSON.parse(xhr.responseText);
        if(reponse_serveur[0]['autorisation']){
        	///////\\\\\\\
			Deplacement_tuile('hd');
			Deplacement_element_du_jeu('hd');
			point_deplacement--;
			affichage_point_action_deplacement();
			position_personnage_y--;
			Affichier_information_bis(position_personnage_x,position_personnage_y);
			///////\\\\\\\
          var numero_type = 0;
          while(numero_type < 9){
            if(reponse_serveur[0]['case'][(numero_type+1)]['type']==1){
              let texture = PIXI.utils.TextureCache["images/texture_sable.png"];
              sprite = new PIXI.Sprite(texture);
              sprite.interactive = true;
              sprite.on('mousedown', Afficher_Information);
              app.stage.addChild(sprite);
              sprite.anchor.set(0.5,0.5);
              sprite.scale.set(0.27,0.27);
              sprite.position.set(241+(decalage_x*numero_type),32+(decalage_y*numero_type));
              sprite.position_damier_x = reponse_serveur[0]['case'][(numero_type+1)]['position_x'];
              sprite.position_damier_y = reponse_serveur[0]['case'][(numero_type+1)]['position_y'];
              case_de_jeu.push(sprite);
            }
            numero_type++;
          }

          if(reponse_serveur[0]['element_du_jeu'].length != 0){
          	placement_element_du_jeu(reponse_serveur[0]['element_du_jeu']);	
          }

			rafraichir_element_du_jeu();
			app.stage.removeChild(sprite_personnage);
			app.stage.addChild(sprite_personnage);

          if(reponse_serveur[0]['position']['y'] == -10){
            sprite_haut_droite.visible = false;
          }
          else if(reponse_serveur[0]['position']['y'] == 9){
            sprite_bas_gauche.visible = true;
          }
        }
      }
      else{
      }
    };

    xhr.open("POST", "deplacement.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("deplacement=hd");
  }

  function Deplacement_haut_gauche(eventData){
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
      	console.log(xhr.responseText);
        reponse_serveur = JSON.parse(xhr.responseText);
        if(reponse_serveur[0]['autorisation']){
          Deplacement_tuile('hg');
            Deplacement_element_du_jeu('hg');
			point_deplacement--;
			affichage_point_action_deplacement();
          position_personnage_x--;
			Affichier_information_bis(position_personnage_x,position_personnage_y);
          var numero_type = 0;
          while(numero_type < 9){
            if(reponse_serveur[0]['case'][(numero_type+1)]['type']==1){
              let texture = PIXI.utils.TextureCache["images/texture_sable.png"];
              sprite = new PIXI.Sprite(texture);
              sprite.interactive = true;
              sprite.on('mousedown', Afficher_Information);
              app.stage.addChild(sprite);
              sprite.anchor.set(0.5,0.5);
              sprite.scale.set(0.27,0.27);
              sprite.position.set(241-(decalage_x*numero_type),32+(decalage_y*numero_type));
              sprite.position_damier_x = reponse_serveur[0]['case'][(numero_type+1)]['position_x'];
              sprite.position_damier_y = reponse_serveur[0]['case'][(numero_type+1)]['position_y'];
              case_de_jeu.push(sprite);
            }
            numero_type++;
          }

			if(reponse_serveur[0]['element_du_jeu'].length != 0){
          		placement_element_du_jeu(reponse_serveur[0]['element_du_jeu']);	
          	}

			rafraichir_element_du_jeu();
			app.stage.removeChild(sprite_personnage);
			app.stage.addChild(sprite_personnage);

          if(reponse_serveur[0]['position']['x'] == -10){
            sprite_haut_gauche.visible = false;
          }
          else if(reponse_serveur[0]['position']['x'] == 9){
            sprite_bas_droite.visible = true;
          }

        }
      }
      else{
      }
    };

    xhr.open("POST", "deplacement.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("deplacement=hg");
  }

  function Deplacement_bas_droite(eventData){
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
        reponse_serveur = JSON.parse(xhr.responseText);
        if(reponse_serveur[0]['autorisation']){

          Deplacement_tuile('bd');
            Deplacement_element_du_jeu('bd');

			point_deplacement--;
			affichage_point_action_deplacement();
          position_personnage_x++;
			Affichier_information_bis(position_personnage_x,position_personnage_y);

          var numero_type = 0;
          while(numero_type < 9){
            if(reponse_serveur[0]['case'][(numero_type+1)]['type']==1){
              let texture = PIXI.utils.TextureCache["images/texture_sable.png"];
              sprite = new PIXI.Sprite(texture);
              sprite.interactive = true;
              sprite.on('mousedown', Afficher_Information);
              app.stage.addChild(sprite);
              sprite.anchor.set(0.5,0.5);
              sprite.scale.set(0.27,0.27);
              sprite.position.set(457-(decalage_x*numero_type),145.6+(decalage_y*numero_type));
              sprite.position_damier_x = reponse_serveur[0]['case'][(numero_type+1)]['position_x'];
              sprite.position_damier_y = reponse_serveur[0]['case'][(numero_type+1)]['position_y'];
              case_de_jeu.push(sprite);
            }
            numero_type++;
          }

			if(reponse_serveur[0]['element_du_jeu'].length != 0){
          		placement_element_du_jeu(reponse_serveur[0]['element_du_jeu']);	
          	}

			rafraichir_element_du_jeu();
			app.stage.removeChild(sprite_personnage);
			app.stage.addChild(sprite_personnage);

          if(reponse_serveur[0]['position']['x'] == 10){
            sprite_bas_droite.visible = false;
          }
          else if(reponse_serveur[0]['position']['x'] == -9){
            sprite_haut_gauche.visible = true;
          }
        }
      }
      else{
      }
    };

    xhr.open("POST", "deplacement.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("deplacement=bd");
  }

  function Deplacement_bas_gauche(eventData){
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
        reponse_serveur = JSON.parse(xhr.responseText);
        if(reponse_serveur[0]['autorisation']){
          reponse_serveur = JSON.parse(xhr.responseText);
          if(reponse_serveur[0]['autorisation']){
            Deplacement_tuile('bg');
            Deplacement_element_du_jeu('bg');
			point_deplacement--;
			affichage_point_action_deplacement();
            position_personnage_y++;
			Affichier_information_bis(position_personnage_x,position_personnage_y);

            var numero_type = 0;
            while(numero_type < 9){
              if(reponse_serveur[0]['case'][(numero_type+1)]['type']==1){
                let texture = PIXI.utils.TextureCache["images/texture_sable.png"];
                sprite = new PIXI.Sprite(texture);
                sprite.interactive = true;
                sprite.on('mousedown', Afficher_Information);
                app.stage.addChild(sprite);
                sprite.anchor.set(0.5,0.5);
                sprite.scale.set(0.27,0.27);
                sprite.position.set(25+(decalage_x*numero_type),145.6+(decalage_y*numero_type));
                sprite.position_damier_x = reponse_serveur[0]['case'][(numero_type+1)]['position_x'];
                sprite.position_damier_y = reponse_serveur[0]['case'][(numero_type+1)]['position_y'];
                case_de_jeu.push(sprite);
              }
              numero_type++;
            }

			if(reponse_serveur[0]['element_du_jeu'].length != 0){
          		placement_element_du_jeu(reponse_serveur[0]['element_du_jeu']);	
          	}

			rafraichir_element_du_jeu();
			app.stage.removeChild(sprite_personnage);
			app.stage.addChild(sprite_personnage);


            if(reponse_serveur[0]['position']['y'] == -9){
              sprite_haut_droite.visible = true;
            }
            else if(reponse_serveur[0]['position']['y'] == 10){
              sprite_bas_gauche.visible = false;
            }

          }
        }
      }
      else{
      }
    };

    xhr.open("POST", "deplacement.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("deplacement=bg");
  }

  function Deplacement_tuile(direction){
    if(direction == 'bg'){
      var i = 0;
      while(i < case_de_jeu.length){
        case_de_jeu[i].position.set(case_de_jeu[i].position.x+decalage_x,case_de_jeu[i].position.y-decalage_y);
        if((case_de_jeu[i].position.y-(+(decalage_y/decalage_x)*case_de_jeu[i].position.x-100)) < 0){
          app.stage.removeChild(case_de_jeu[i]);
          case_de_jeu.splice(i, 1);
        }
        else{
          i++;
        }
      }
    }
    else if(direction == 'hd'){
      var i = 0;
      while(i < case_de_jeu.length){
        case_de_jeu[i].position.set(case_de_jeu[i].position.x-decalage_x,case_de_jeu[i].position.y+decalage_y);
        if((case_de_jeu[i].position.y-(+(decalage_y/decalage_x)*case_de_jeu[i].position.x+140)) > 0){
          app.stage.removeChild(case_de_jeu[i]);
          case_de_jeu.splice(i, 1);
        }
        else{
          i++;
        }
      }
    }
    else if(direction == 'bd'){
      var i = 0;
      while(i < case_de_jeu.length){
        case_de_jeu[i].position.set(case_de_jeu[i].position.x-decalage_x,case_de_jeu[i].position.y-decalage_y);
        if((case_de_jeu[i].position.y-(-(decalage_y/decalage_x)*case_de_jeu[i].position.x+150)) < 0){
          app.stage.removeChild(case_de_jeu[i]);
          case_de_jeu.splice(i, 1);
        }
        else{
          i++;
        }
      }
    }
    else if(direction == 'hg'){
      var i = 0;
      while(i < case_de_jeu.length){
        case_de_jeu[i].position.set(case_de_jeu[i].position.x+decalage_x,case_de_jeu[i].position.y+decalage_y);
        if((case_de_jeu[i].position.y-(-(decalage_y/decalage_x)*case_de_jeu[i].position.x+400)) > 0){
          app.stage.removeChild(case_de_jeu[i]);
          case_de_jeu.splice(i, 1);
        }
        else{
          i++;
        }
      }
    }
  }

  function Deplacement_element_du_jeu(direction){
  	if(direction == 'bg'){
      var i = 0;
      while(i < element_du_jeu.length){
        element_du_jeu[i].position.set(element_du_jeu[i].position.x+25,element_du_jeu[i].position.y-15);
        if((element_du_jeu[i].position.y-(+(15/25)*element_du_jeu[i].position.x-160)) < 0){
          app.stage.removeChild(element_du_jeu[i]);
          element_du_jeu.splice(i, 1);
        }
        else{
          i++;
        }
      }
    }
    else if(direction == 'hd'){
      var i = 0;
      while(i < element_du_jeu.length){
        element_du_jeu[i].position.set(element_du_jeu[i].position.x-25,element_du_jeu[i].position.y+15);
        if((element_du_jeu[i].position.y-(+(15/25)*element_du_jeu[i].position.x+120)) > 0){
          app.stage.removeChild(element_du_jeu[i]);
          element_du_jeu.splice(i, 1);
        }
        else{
          i++;
        }
      }
    }
    else if(direction == 'bd'){
      var i = 0;
      while(i < element_du_jeu.length){
        element_du_jeu[i].position.set(element_du_jeu[i].position.x-25,element_du_jeu[i].position.y-15);
        if((element_du_jeu[i].position.y-(-(15/25)*element_du_jeu[i].position.x+150)) < 0){
          app.stage.removeChild(element_du_jeu[i]);
          element_du_jeu.splice(i, 1);
        }
        else{
          i++;
        }
      }
    }
    else if(direction == 'hg'){
      var i = 0;
      while(i < element_du_jeu.length){
        element_du_jeu[i].position.set(element_du_jeu[i].position.x+25,element_du_jeu[i].position.y+15);
        if((element_du_jeu[i].position.y-(-(15/25)*element_du_jeu[i].position.x+420)) > 0){
          app.stage.removeChild(element_du_jeu[i]);
          element_du_jeu.splice(i, 1);
        }
        else{
          i++;
        }
      }
    }
  }

  function placement_element_du_jeu(liste_element){
  	liste_element.forEach(function(element){
  		var position_x_insersion = element.position_x - (position_personnage_x - 4);
  		var position_y_insersion = element.position_y - (position_personnage_y - 4);

		////////Génération et placement du personnage\\\\\\\\\
		let texture_personnage = PIXI.utils.TextureCache["images/vampir11.png"];
		var sprite_personnage_en_cours = new PIXI.Sprite(texture_personnage);
		app.stage.addChild(sprite_personnage_en_cours);
		sprite_personnage_en_cours.anchor.set(0.5,0.5);
		//sprite_personnage.position.set(239+position_x_insersion*25,132+position_x_insersion*15);
		sprite_personnage_en_cours.position.set(239+position_x_insersion*25-position_y_insersion*25,12+position_x_insersion*15+position_y_insersion*15);
		element_du_jeu.push(sprite_personnage_en_cours);

		console.log("??????????");
		console.log(element);
  	});
  	
  	app.stage.removeChild(sprite_personnage);
    app.stage.addChild(sprite_personnage);

	console.log(liste_element.length);
  }

  function placement_personnage(liste_personnage_json){

  	element_du_jeu.forEach(function(element_du_jeu_seul,index){
  		element_du_jeu.splice(index,1);
  		app.stage.removeChild(element_du_jeu_seul);
  	});


  	var liste_personnage = JSON.parse(liste_personnage_json);

  	liste_personnage.forEach(function(personnage){
  		var position_x_insersion = personnage.x - (position_personnage_x - 4);
  		var position_y_insersion = personnage.y - (position_personnage_y - 4);

		////////Génération et placement du personnage\\\\\\\\\
		let texture_personnage = PIXI.utils.TextureCache["images/vampir11.png"];
		var sprite_personnage_en_cours = new PIXI.Sprite(texture_personnage);
		app.stage.addChild(sprite_personnage_en_cours);
		sprite_personnage_en_cours.anchor.set(0.5,0.5);
		//sprite_personnage.position.set(239+position_x_insersion*25,132+position_x_insersion*15);
		sprite_personnage_en_cours.position.set(239+position_x_insersion*25-position_y_insersion*25,12+position_x_insersion*15+position_y_insersion*15);
		element_du_jeu.push(sprite_personnage_en_cours);
  	});
  	
  	app.stage.removeChild(sprite_personnage);
    app.stage.addChild(sprite_personnage);
  }

  function rafraichir_element_du_jeu(){
  	var i = 0;
  	while(i<element_du_jeu.length){
		app.stage.removeChild(element_du_jeu[i]);
		app.stage.addChild(element_du_jeu[i]);
  		i++;
  	}
  }

  var ws = new WebSocket("ws://51.77.194.229:9820");
ws.onmessage = function(e){
	console.log(e.data);
	placement_personnage(e.data);
};
ws.onopen = function (event){
				console.log("FETE DU SLIIIIIIIIIIP");
			};
}

  </script>
  <!-- partie WEBSOCKET -->
  <script type="text/javascript">
  	/*var ws = new WebSocket("ws://51.77.194.229:9820");
ws.onmessage = function(e){
	console.log(e.data);
	placement_personnage();
};
ws.onopen = function (event){
				console.log("FETE DU SLIIIIIIIIIIP");
			};



		/*setInterval(function(){
				ws.send("test");
				
		}, 5000);*/
  </script>
		<div id="affichage_tour_en_cours">
			Tour en cours : <?php echo Serveur::obtenir_serveur_depuis_id(1)->obt_tour();?>
  		</div>
		<div id="affichage">
  		</div>
		<div id="affichage_point_action_deplacement">
  		</div>

  		<script type="text/javascript">
  			affichage_point_action_deplacement();
  		</script>
<div id="message">
		<?php
			foreach($_SESSION['Utilisateur']->personnage()->obt_message() as $message)
			{
			?>
				<div><?php echo "Tour : " . $message->obt_numero_tour() . " date : " . $message->obt_date_message() . " " . $message->obt_message(); ?></div>
			<?php
			}
		?>
			</div>
	</body>
</html>
