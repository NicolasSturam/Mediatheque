<?php 
	require_once("Template/template.func.php");
 ?>
 <!DOCTYPE html>
 <html lang="fr">
 <head>
 	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<title>Cyber Mediathèque</title>
 	<meta name="description" content="Super médiathèque !" />
 	
 	<?php 
 		if($loginPage || $pageAjouter || $pageModifier){
 			echo "<link href='styles/loginPage.css' rel='stylesheet'>";
 		}
 		else{
 			echo "<link href='styles/normalize.css' rel='stylesheet'>";
 			echo "<link href='styles/style.css' rel='stylesheet'>";
 		}
 	 ?>
 	
 	<script src="https://kit.fontawesome.com/7d96b0180d.js" crossorigin="anonymous"></script>
 	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

	<script src="javascripts/monscript.js"></script>

 </head>
 <body>
 	<?php 
 		if($loginPage){
 			echo loginPage($erreurLogin);
 		}
 		else if($pageAjouter){
 			echo pageAjouter($genres, $acteurs, $realisateurs);
 		}
 		else if($pageModifier){
 			echo pageModifier($filmAModifier, $genres, $acteurs, $realisateurs);
 		}
 		else{
 			//Page principale du site
 			echo construireHeader($_SESSION['nbrPages']);
 			echo construireListeFilms($tab);
 		}
 	 ?>
 	
 	<footer>
 		
 	</footer>
 </body>
 </html>