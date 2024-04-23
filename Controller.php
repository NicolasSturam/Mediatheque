<?php 
	require_once("Modele/modele.func.php");
	session_start();

	$nbrFilmsParPage=10;
	$tab=[];
	$numeroPage=0;
	$nbrPages=0;

	$erreurLogin=false;
	$loginPage=false;

	$pageAjouter=false;
	$genres=[];
	$acteurs=[];
	$realisateurs=[];

	$pageModifier=false;
	$idFilmAModifier=0;
	$filmAModifier;

	try{
		$dbh = connexion();
		
		if(isset($_POST['action'])){
			//Traitement du formulaire
			switch($_POST['action']){
				case 'Suivant':
					suivant($_SESSION['nbrPages']);
				break;
				case 'Precedent':
					precedent();
				break;
				case 'Rechercher':
					if($_SESSION['rechercher']==false && $_POST['recherche']!=""){
						//Passage en mode recherche
						//$_SESSION['tab']=rechercher($dbh);

						$_SESSION['tab']=recupFiltre($dbh);
						$_SESSION['rechercher']=true;
						$_SESSION['mot']=$_POST['recherche'];
						$nbrFilms=count($_SESSION['tab']);
						$_SESSION['nbrPages']=calculNbrPages($nbrFilms, $nbrFilmsParPage);
					}
					else if($_SESSION['rechercher']){
						//Mode recherche
						if($_POST['recherche']==""){
							//Sortie du mode recherche
							$_SESSION['rechercher']=false;
							$_SESSION['mot']="";
						}
						else{
							//On reste en mode recherche mais on regenere un nouveau tableau
							//$_SESSION['tab']=rechercher($dbh);
							$_SESSION['mot']=$_POST['recherche'];
							$_SESSION['tab']=recupFiltre($dbh);
							$nbrFilms=count($_SESSION['tab']);
							$_SESSION['nbrPages']=calculNbrPages($nbrFilms, $nbrFilmsParPage);
						}
					}
					//Si $_SESSION['rechercher']==false && $_POST['recherche']=="" Alors
					//on ne fait rien

					//Peu importe, le bouton recherche permet de revenir au debut
					$_SESSION['numeroPage']=0;
				break;
				case 'login':
					$loginPage=true;
				break;
				case 'Connexion':
					if(identification($dbh)){
						$_SESSION['admin']=true;
					}
					else{
						$erreurLogin=true;
						$loginPage=true;
					}
				break;
				case 'logout':
					$_SESSION['admin']=false;
				break;
				case 'ajouter':
					$pageAjouter=true;
					$genres=getGenres($dbh);
					$acteurs=getActeurs($dbh);
					$realisateurs=getRealisateurs($dbh);
				break;
				case 'ajouterFilm':
					ajouterFilm($dbh);

				break;
				case 'supprimer':
					supprimerFilm($dbh);
				break;
				case 'modifier':
					$pageModifier=true;
					$genres=getGenres($dbh);
					$acteurs=getActeurs($dbh);
					$realisateurs=getRealisateurs($dbh);
					$filmAModifier=getFilmAModifier($dbh);

				break;
			}
		}
		else{
			//initialisation
			$_SESSION['numeroPage']=0;
			$_SESSION['rechercher']=false;
			$_SESSION['nbrPages']=0;
			$_SESSION['tab']=[];
			$_SESSION['mot']="";

			$_SESSION['admin']=false;
		}
		//On agit differemment si on est en recherche ou non
		if($_SESSION['rechercher']==false){
			//Mode normal
			$indice=$_SESSION['numeroPage']*$nbrFilmsParPage;
			$tab = recup($dbh, $indice, $nbrFilmsParPage);
			$nbrFilms=getNombreFilms($dbh);
			$_SESSION['nbrPages']=calculNbrPages($nbrFilms, $nbrFilmsParPage);
		}
		else{
			//Mode recherche
			$tab=$_SESSION['tab'];
			//Decoupe du tableau pour afficher que $nbrFilmsParPage films
			$tab=array_slice($tab, $_SESSION['numeroPage']*$nbrFilmsParPage, $nbrFilmsParPage);
			//var_dump($tab);
		}
		
	}
	catch(Exception $ex){
		die("ERREUR FATALE : ". $ex->getMessage().'<form><input type="button" value="Retour" onclick="history.go(-1)"></form>');
	}

	require_once("Template/template.php");
 ?>