<?php 
	function construireHeader($nbrPages){
		$numeroPage=$_SESSION['numeroPage']+1;
		$str="<header class='fixe'>
		 		<h1>Cyber Médiathèque</h1>
			 	<form action='Controller.php' method='POST'>
				 	<input type='text' name='recherche' value='{$_SESSION['mot']}' autofocus>
				 	<button name='action' value='Rechercher'><i class='fa-solid fa-magnifying-glass'></i></button>
			 	</form>
			 	<form action='Controller.php' method='POST' id='id_form_nav'></form>
			 	<form method='POST' action='Controller.php' id='formModeAffichage'></form>
			 	<form method='POST' action='Controller.php' id='loginForm'></form>
			 	<div>"
			 		.construireNav($nbrPages).
			 	"</div>
 			</header>";
 		return $str;
	}
	function construireNav($nbrPages){
		$display="";//pour cacher les boutons
		$numeroPage=$_SESSION['numeroPage']+1; //on compte à partir de 1 pour l'affichage
		$str="";

		$str.="<p class='modifAffichage'></p>";
		if($_SESSION['admin']){
			$str.="<p class='modifAffichage'></p>";
		}

		//navigation
		$str.="<nav class='morceauNav'>";
		if($_SESSION['numeroPage'] == 0){
			$display="class='cacher'";
		}
		$str.="<button $display name='action' value='Precedent' form='id_form_nav' id='boutonPrecedent'><i class='fa-solid fa-circle-left icon'></i></button>";
		$str.="<p><B> $numeroPage / $nbrPages </B></p>";
		$display="";
		if($_SESSION['numeroPage'] == $nbrPages-1){
			$display="class='cacher'";
		}
		$str.="<button $display name='action' value='Suivant' form='id_form_nav'><i class='fa-solid fa-circle-right icon'></i></button>";
 		$str.="</nav>";

 		//menu
 		if(!$_SESSION['admin']){
			$str.="<button class='modifAffichage' name='action' value='login' form='loginForm'>Connexion</button>";
		}
		else{
			$str.="<button class='modifAffichage' name='action' value='ajouter' form='loginForm'>Ajouter</button>";
			$str.="<button class='modifAffichage' name='action' value='logout' form='loginForm'>Deconnexion</button>";
		}

 		return $str;
	}
	function construireListeFilms($films){
		$str="<div class='mediatheque'>";
		foreach ($films as $film) {
			$str.=construireFilm($film);
		}
		$str.="</div>";
		return $str;
	}
	function construireFilm($film){
		$str="<div class='film'>
				<figure>
					<img src='images/imagesFilms/{$film['Affiche']}' alt='Plage'>
					<figcaption><B>{$film['Annee']}</B></figcaption>
				</figure>
				<article>
					<h2>{$film['Titre']}</h2>
					<p>{$film['Genres']}</p>
					<p><B>Réalisateur :</B> {$film['Realisateur']}</p>
					<p><B>Acteurs :</B> {$film['Acteurs']}</p>
					<p><B>Durée :</B> {$film['Duree']} minutes</p>
					<p>{$film['Resume']}</p>";
					if($_SESSION['admin']){
						$str.="<form method='POST' action='Controller.php'>
								<button name='action' value='supprimer'><i class='fa-solid fa-trash'></i></button>
								<button name='action' value='modifier'><i class='fa-solid fa-gear'></i></i></button>
								<input type='hidden' name='filmId' value='{$film['films_id']}'>
								</form>";
					}
				$str.="</article>
			</div>";
		return $str;
	}
	function afficherGenres($genres){
		$str="";
		$iMax = count($genres);
		for($i; $i < $iMax;$i++){
			$str.=$genres[$i];
			if($i < $iMax-1){
				$str.=", ";
			}
		}
		return $str;
	}
	function loginPage($erreurLogin){
		$str="<div class='contour'>";
		if($erreurLogin){
			$str.="<p>Mot de passe ou email incorrect</p>";
		}
		$str.="<form method='POST' action='Controller.php'>
				<fieldset>
				<legend>Connexion</legend>
 				<div>
 					<label for='email'>E-mail</label>
					<input type='email' name='email' id='email' placeholder='Votre email: nom@domaine.be' required>
				</div>
 				<div>
 					<label for='mdp'>Mot de passe :</label>
 					<input type='password' name='mdp' value='' placeholder='Votre mot de passe' required></div>
 				<div>
 					<input type='submit' name='action' value='Connexion'>
 				</div>
 				</fieldset>
			</form>
			<form method='POST' action='Controller.php'>
				<input type='submit' name='action' value='Retour'>
			</form>
		</div>";

		
		return $str;

		/*
		<div>
			<label for='nom'>Nom : </label>
			<input type='text' name='nom' value='' placeholder='Votre nom' autofocus required>
		</div>
		<div>
			<label for='prenom'>Prenom : </label>
			<input type='text' name='prenom' value='' placeholder='Votre prénom' required>
		</div>
		*/
	}

	function pageAjouter($genres, $acteurs, $realisateurs){
		$numeroOption=0;
		$idOption="";
		$nbrGenres;


		$str="<div class='contour'>
				<form method='POST' action='Controller.php' enctype='multipart/form-data'>
				<fieldset>
				<legend>Ajout d'un film</legend>
 				<div>
 					<label for='titre'>Titre : </label>
					<input type='text' name='titre' placeholder='Titre du film' autofocus required>
				</div>
				<div>
 					<label for='annee'>Année : </label>
					<input type='text' name='annee' placeholder='Année de sortie'>
				</div>
				<div>
 					<label for='duree'>Durée : </label>
					<input type='text' name='duree' placeholder='Durée en minutes'>
				</div>
				<div>
 					<label for='resume'>Résumé : </label>
 				</div>
 				<div>
					<textarea name='resume' rows='5' placeholder='Résumé du film'></textarea>
				</div>
				<div>
					<label for='fichier'>Affiche du film :</label>
				</div>
				<div>
	 				<input type='file' name='fichier' accept='image/jpeg,image/png'>
	 			</div>
	 			<div>
	 				<label>Genres :</label>
	 			</div>
	 			<div class='genres' id='idGenres'>";
 					foreach($genres as $key => $genre){
 						foreach($genre as $value){
 							$numeroOption="'g$key"."'";
 							$idOption="'id_$key"."'";
 							$str.="<div class='genre'>
						 		<label for=$idOption>$value:</label>
						 		<input type='checkbox' name='$value' value='genre' id=$idOption>
						 	</div>";
 						}
 					}
	 			$str.="</div>
	 			<div>
	 				<input type='text' name='nouveauGenre' placeholder='Ajouter un genre' id='nouveauGenre'>
	 				<input type='button' name='action' value='Ajouter' onclick='loadGenre();'>
	 			</div>
	 			<div>
	 				<label>Réalisateur :</label>
	 			</div>
	 			<div>
	 				<select name='realisateur' id='idRealisateur'>
	 					<option value='vide' hidden selected> -- Sélectionnez une option --  </option>";
		 				foreach($realisateurs as $key => $real){
		 					foreach($real as $value){
		 						$numeroOption="'r$key"."'";
		 						$str.="<option name=$numeroOption>$value </option>";
		 					}
		 				}
	 				$str.="</select></div>";
	 			$str.="<div>
	 				<input type='text' name='nouveauReal' placeholder='Ajouter un réalisateur' id='nouveauReal'>
	 				<input type='button' name='action' value='Ajouter' onclick='loadReal();'>
	 			</div>";
	 			$str.="<div>
	 				<label>Acteurs :</label>
	 			</div>
	 			<div class='genres' id='idActeurs'>";
 					foreach($acteurs as $key => $acteur){
 						foreach($acteur as $value){
 							$numeroOption="'a$key"."'";
 							$idOption="'id_$key"."'";
 							$str.="<div class='genre'>
						 		<label for=$idOption>$value:</label>
						 		<input type='checkbox' name='$value' value='acteur' id=$idOption>
						 	</div>";
 						}
 					}
	 			$str.="</div>";
	 			$str.="<div>
	 				<input type='text' name='nouvelActeur' placeholder='Ajouter un acteur' id='nouvelActeur'>
	 				<input type='button' name='action' value='Ajouter' onclick='loadActeur();'>
	 			</div>";
	 			$str.="<hr>
				<div>
 					<button name='action' value='ajouterFilm'>Ajouter</button>
 				</div>
 				</fieldset>
				</form>
				<form method='POST' action='Controller.php'>
					<input type='submit' name='action' value='Annuler'>
				</form>
			</div>";
		return $str;

	}
	function pageModifier($filmAModifier, $genres, $acteurs, $realisateurs){
		var_dump($filmAModifier);

	}
	// function modeAffichage(){
	// 	$str="";
	// 	$str.="<div class='boutonAffichage'><p class='morceauNav modifAffichage'><B>Affichage</B></p>
	// 			<button class='sous' name='action' value='Simple'><B>Simple</B></button>
	// 			<button class='sous' name='action' value='Details'><B>Détails</B></button>
	// 			</div>";
	// 	return $str;
	// }
 ?>