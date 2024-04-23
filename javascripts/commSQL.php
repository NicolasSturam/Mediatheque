<?php 
	if(isset($_POST['genre'])){
		$dbh = new PDO(
	            "mysql:dbname=mediatheque;host=localhost;port=3308",
	            "root",
	            "",
	            array(
	                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
	                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	            )
	        );
		$sql="INSERT INTO genres VALUES (NULL,:genre);";
		$stmt = $dbh -> prepare($sql); 

		$stmt->bindValue('genre', $_POST['genre'], PDO::PARAM_STR);

		$result=$stmt->execute();

		echo "$result";
	}
	else if(isset($_POST['real'])){
		$dbh = new PDO(
	            "mysql:dbname=mediatheque;host=localhost;port=3308",
	            "root",
	            "",
	            array(
	                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
	                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	            )
	        );
		$sql="INSERT INTO realisateurs VALUES (NULL,:real);";
		$stmt = $dbh -> prepare($sql); 

		$stmt->bindValue('real', $_POST['real'], PDO::PARAM_STR);

		$result=$stmt->execute();

		echo "$result";
	}
	else if(isset($_POST['acteur'])){
		$dbh = new PDO(
	            "mysql:dbname=mediatheque;host=localhost;port=3308",
	            "root",
	            "",
	            array(
	                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
	                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	            )
	        );
		$sql="INSERT INTO acteurs VALUES (NULL,:acteur);";
		$stmt = $dbh -> prepare($sql); 

		$stmt->bindValue('acteur', $_POST['acteur'], PDO::PARAM_STR);

		$result=$stmt->execute();

		echo "$result";
	}


 ?>