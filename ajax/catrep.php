<?php
if(isset($_POST["id"])){
	if($_POST["id"]==1) // format JSON
		echo '{"sscat": [{"nom":"Chat", "id":"11"}, {"nom":"Tigre", "id":"12"},{"nom":"Panthère", "id":"13"},{"nom":"Lion", "id":"14"}]}';
	if($_POST["id"]==2)
		echo '{"sscat": [{"nom":"Chien", "id":"21"}, {"nom":"Loup", "id":"22"}]}';
	if($_POST["id"]==3)
		echo '{"sscat": [{"nom":"Cheval", "id":"31"}, {"nom":"Ane", "id":"32"}]}';
}

?>