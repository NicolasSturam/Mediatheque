<?php 
	function connexion(){
	    $dbh = new PDO(
	            "mysql:dbname=mediatheque;host=localhost;port=3308",
	            "root",
	            "",
	            array(
	                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
	                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	            )
	        );
	    return $dbh;
	}
	function calculNbrPages($nbrFilms, $nbrFilmsParPage){
		$nbrPages=floor($nbrFilms/$nbrFilmsParPage);
		if($nbrPages < $nbrFilms/$nbrFilmsParPage){
			$nbrPages++;
		}
		if($nbrPages==0)
			$nbrPages=1;
		return $nbrPages;
	}
	function getNombreFilms($dbh){
		$sql="SELECT count(films_id) AS 'NbrFilms'
				FROM films;";
		$stmt = $dbh -> prepare($sql); 
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$nbr=$stmt->fetchAll();
		return intval($nbr[0]["NbrFilms"]);
	}
	function recup($dbh, $indice, $nbr){
		$sql="SELECT films_id, films_titre AS 'Titre', films_resume AS 'Resume', films_annee AS 'Annee', films_affiche AS 'Affiche', films_duree AS 'Duree', real_nom AS 'Realisateur', GROUP_CONCAT(DISTINCT acteurs_nom) AS 'Acteurs', GROUP_CONCAT(DISTINCT genres_nom) AS 'Genres'
		    FROM films
			LEFT JOIN realisateurs ON films_real_id=real_id
		    LEFT JOIN films_acteurs ON films_id=fa_films_id
		    LEFT JOIN acteurs ON fa_acteurs_id=acteurs_id
		    LEFT JOIN films_genres ON films_id=fg_films_id
		    LEFT JOIN genres ON genres_id=fg_genres_id
			GROUP BY films_titre, films_resume, films_annee, films_affiche, films_duree, real_nom
		    LIMIT :indice, :nbr;";

		$stmt = $dbh -> prepare($sql); 

		$stmt->bindValue('indice', $indice, PDO::PARAM_INT);
		$stmt->bindValue('nbr', $nbr, PDO::PARAM_INT);

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();

		return $tab;
	}
	function suivant($nbrPages){
		if($_SESSION['numeroPage'] < $nbrPages){
			$_SESSION['numeroPage']++;
		}
	}
	function precedent(){
		if($_SESSION['numeroPage'] > 0){
			$_SESSION['numeroPage']--;
		}
	}
	function recupFiltre($dbh){
		$mot='%'.$_POST['recherche'].'%';
		$sql = "SELECT films_id, films_titre AS Titre, films_resume AS Resume, films_annee AS Annee, films_affiche AS Affiche, films_duree AS Duree, real_nom AS Realisateur, GROUP_CONCAT(DISTINCT acteurs_nom) AS Acteurs, GROUP_CONCAT(DISTINCT genres_nom) AS Genres
		    FROM films
			LEFT JOIN realisateurs ON films_real_id=real_id
		    LEFT JOIN films_acteurs ON films_id=fa_films_id
		    LEFT JOIN acteurs ON fa_acteurs_id=acteurs_id
		    LEFT JOIN films_genres ON films_id=fg_films_id
		    LEFT JOIN genres ON genres_id=fg_genres_id
			GROUP BY films_titre, films_resume, films_annee, films_affiche, films_duree, real_nom
		    HAVING Titre LIKE :mot
				OR Resume LIKE :mot
		        OR Annee LIKE :mot
		        OR Affiche LIKE :mot
		        OR Duree LIKE :mot
		        OR Realisateur LIKE :mot
		        OR Acteurs LIKE :mot
		        OR Genres LIKE :mot;";
		$stmt = $dbh -> prepare($sql); 

		$stmt->bindValue('mot', $mot, PDO::PARAM_STR);

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();

		return $tab;

	}
	function identification($dbh){
		$sql= "SELECT EXISTS(SELECT * FROM admin WHERE (adm_mail=:mail AND adm_mdp=MD5(:mdp) ) ) AS Correct;";
		$stmt = $dbh -> prepare($sql); 

		$stmt->bindValue('mail', $_POST['email'], PDO::PARAM_STR);
		$stmt->bindValue('mdp', $_POST['mdp'], PDO::PARAM_STR);

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();

		return boolval($tab[0]['Correct']);

	}
	function getGenres($dbh){
		$sql="SELECT genres_nom AS Genres
		FROM genres
		ORDER BY genres_nom;";

		$stmt = $dbh -> prepare($sql); 

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();

		//var_dump($tab);
		return $tab;
	}
	function getActeurs($dbh){
		$sql="SELECT acteurs_nom AS Acteurs
		FROM acteurs
		ORDER BY acteurs_nom;";

		$stmt = $dbh -> prepare($sql); 

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();
		//var_dump($tab);
		return $tab;
	}
	function getRealisateurs($dbh){
		$sql="SELECT real_nom AS Realisateurs
		FROM realisateurs
    	ORDER BY real_nom;";

		$stmt = $dbh -> prepare($sql); 

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();
		//var_dump($tab);
		return $tab;
	}
	function ajouterFilm($dbh){
		//var_dump($_POST);

		$titre=$_POST['titre'];
		$annee=$_POST['annee'];
		$duree=$_POST['duree'];
		$resume=$_POST['resume'];
		$realisateur=getRealId($dbh);
		$imageName= $_FILES['fichier']['name'];
		$imageTmp=$_FILES['fichier']['tmp_name'];
		$genres=[];
		$acteurs=[];
		$filmId=0;
		$genreId=0;
		$acteurId=0;

		foreach ($_POST as $key => $value) {
			if($value=='genre'){
				$genres[]=$key;
			}
			else if($value=='acteur'){
				$acteurs[]=$key;
			}
		}

		//Le formulaire remplace les espaces par des _
		//Il faut donc remettre les espaces

		$acteurs=correctListeActeurs($acteurs);

		$sql="INSERT INTO films VALUES (
			NULL,
			:titre,
			:resume,
    		:annee,
    		:image,
    		:duree,
    		:realisateur);";

		//var_dump($realisateur);
		$stmt = $dbh -> prepare($sql); 
    	$stmt->bindValue('titre', $titre, PDO::PARAM_STR);
    	$stmt->bindValue('resume', $resume, PDO::PARAM_STR);
    	$stmt->bindValue('annee', $annee, PDO::PARAM_INT);
    	$stmt->bindValue('image', $imageName, PDO::PARAM_STR);
    	$stmt->bindValue('duree', $duree, PDO::PARAM_INT);
    	$stmt->bindValue('realisateur', $realisateur, PDO::PARAM_STR);

		$stmt->execute();

		move_uploaded_file($imageTmp, 'images/imagesFilms/'.$imageName); 

		$filmId = getFilmId($dbh);
		//var_dump($filmId);
		//Tables intermédiaires
		foreach ($genres as $key => $value) {
			$genreId = getGenreId($dbh, $value);
			$sql = "INSERT INTO films_genres VALUES (
			:filmId,
			:genreId)";
			$stmt = $dbh -> prepare($sql); 
	    	$stmt->bindValue('filmId', $filmId, PDO::PARAM_INT);
	    	$stmt->bindValue('genreId', $genreId, PDO::PARAM_INT);
			$stmt->execute();
		}
		//var_dump($acteurs);
		foreach ($acteurs as $key => $value) {
			$acteurId = getActeurId($dbh, $value);
			$sql = "INSERT INTO films_acteurs VALUES (
			:filmId,
			:acteurId)";
			$stmt = $dbh -> prepare($sql); 
	    	$stmt->bindValue('filmId', $filmId, PDO::PARAM_INT);
	    	$stmt->bindValue('acteurId', $acteurId, PDO::PARAM_INT);
			$stmt->execute();
		}
		
		
	}
	function correctListeActeurs($acteurs){
		$newActeurs=[];
		$acteur=[];
		$newActeur="";

		foreach($acteurs as $key => $acteur){
			$nomPrenom=explode('_', $acteur);
			$newActeur="";
			foreach($nomPrenom as $mot){
				$newActeur.=$mot.' ';
			}
			$newActeurs[]=$newActeur;
		}
		return $newActeurs;
	}
	function getFilmId($dbh){
		$sql="SELECT films_id
			FROM films
    		WHERE films_titre=:titre";

    	$stmt = $dbh -> prepare($sql); 
    	$stmt->bindValue('titre', $_POST['titre'], PDO::PARAM_STR);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();

		if($tab!=null){
			$tab=$tab[0]['films_id'];
		}
		return $tab;

	}
	function getGenreId($dbh, $genre){
		$sql="SELECT genres_id
		FROM genres
    	WHERE genres_nom=:genre";

    	$stmt = $dbh -> prepare($sql); 
    	$stmt->bindValue('genre', $genre, PDO::PARAM_STR);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();

		if($tab!=null){
			$tab=$tab[0]['genres_id'];
		}
		return $tab;
	}
	function getRealId($dbh){
		$sql="SELECT real_id 
		FROM realisateurs
    	WHERE real_nom=:nom";

    	$stmt = $dbh -> prepare($sql); 
    	$stmt->bindValue('nom', $_POST['realisateur'], PDO::PARAM_STR);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();

		var_dump($tab);
		if($tab!=null){
			$tab=$tab[0]['real_id'];
		}
		var_dump($tab);
		return $tab;
	}
	function getActeurId($dbh, $acteur){
		$sql="SELECT acteurs_id
		FROM acteurs
    	WHERE acteurs_nom=:acteur";

    	$stmt = $dbh -> prepare($sql); 
    	$stmt->bindValue('acteur', $acteur, PDO::PARAM_STR);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();

		if($tab!=null){
			$tab=$tab[0]['acteurs_id'];
		}
		return $tab;
	}
	function supprimerFilm($dbh){
		$id=$_POST['filmId'];
	
		//suppression dans films acteurs
		$sql="DELETE FROM films_acteurs WHERE fa_films_id = :id";
		$stmt = $dbh -> prepare($sql); 
    	$stmt->bindValue('id', $id, PDO::PARAM_INT);
		$stmt->execute();

		//suppression dans films genres
		$sql="DELETE FROM films_genres WHERE fg_films_id = :id";
		$stmt = $dbh -> prepare($sql); 
    	$stmt->bindValue('id', $id, PDO::PARAM_INT);
		$stmt->execute();

		//suppression du film
		$sql="DELETE FROM films WHERE films_id = :id";
		$stmt = $dbh -> prepare($sql); 
    	$stmt->bindValue('id', $id, PDO::PARAM_INT);
		$stmt->execute();

	}
	function getFilmAModifier($dbh){
		$idFilmAModifier=$_POST['filmId'];
		$sql="SELECT films_titre AS Titre, films_resume AS Resumé, films_annee AS Annee, films_affiche AS Affiche, films_duree AS Durée, real_nom AS Realisateur, GROUP_CONCAT(DISTINCT acteurs_nom) AS Acteurs, GROUP_CONCAT(DISTINCT genres_nom) AS Genres
			FROM films
    		LEFT JOIN realisateurs ON films_real_id=real_id
    		LEFT JOIN films_acteurs ON films_id=fa_films_id
    		LEFT JOIN acteurs ON fa_acteurs_id=acteurs_id
    		LEFT JOIN films_genres ON films_id=fg_films_id
    		LEFT JOIN genres ON genres_id=fg_genres_id
    		WHERE films_id=:id
    		GROUP BY films_titre, films_resume, films_annee, films_affiche, films_duree, real_nom";

    	$stmt = $dbh -> prepare($sql); 
    	$stmt->bindValue('id', $idFilmAModifier, PDO::PARAM_INT);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$tab=$stmt->fetchAll();

		if($tab!=null){
			$tab=$tab[0];
		}

		return $tab;
	}
	// function rechercher($dbh){
	// 	$tab=recupAll($dbh);
	// 	$newTab=[];
	// 	foreach ($tab as $film) {
	// 		if(rechercheDansFilm($film)){
	// 			$newTab[]=$film;
	// 		}
	// 	}

	// 	return $newTab;
	// }
	// function rechercheDansFilm($film){
	// 	foreach($film as $element){
	// 		if(strpos($element, $_POST['recherche'])!==false){
	// 			return true;
	// 		}
	// 	}
	// 	return false;
	// }
	//function recupAll($dbh){
	// 	$sql="SELECT films_titre AS 'Titre', films_resume AS 'Resume', films_annee AS 'Annee', films_affiche AS 'Affiche', films_duree AS 'Duree', real_nom AS 'Realisateur', GROUP_CONCAT(DISTINCT acteurs_nom) AS 'Acteurs', GROUP_CONCAT(DISTINCT genres_nom) AS 'Genres'
	// 	    FROM films
	// 		LEFT JOIN realisateurs ON films_real_id=real_id
	// 	    LEFT JOIN films_acteurs ON films_id=fa_films_id
	// 	    LEFT JOIN acteurs ON fa_acteurs_id=acteurs_id
	// 	    LEFT JOIN films_genres ON films_id=fg_films_id
	// 	    LEFT JOIN genres ON genres_id=fg_genres_id
	// 		GROUP BY films_titre, films_resume, films_annee, films_affiche, films_duree, real_nom;";

	// 	$stmt = $dbh -> prepare($sql); 
	// 	$stmt->execute();
	// 	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	// 	$tab=$stmt->fetchAll();

	// 	return $tab;
	// }

 ?>