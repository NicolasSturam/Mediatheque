<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<script type="text/javascript">
		function loadListe(id) {
			if(id!='0'){
	  		const xhttp = new XMLHttpRequest();
	  		xhttp.onload = function() {
	  			console.log(this.responseText);
   		 	good= this.responseText; //json
   		 	obj = JSON.parse(good);
   		 	select=document.getElementById("sscat");
   		 	select.innerHTML="";
    		for(let i in obj.sscat){
      		opt = document.createElement("option");
      		opt.value = obj.sscat[i].id;// PK du genre ajouté
      		opt.text = obj.sscat[i].nom;

      		select.add(opt, null);
      			}
  			}
		  	xhttp.open("POST", "catrep.php");
		  	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		  	xhttp.send("id="+id);
		  }
		}
	</script>
</head>
<body>
	<form>
	<select	name="cat" onchange="loadListe(this.value);">
		<option value='0'>Choisir</option>
		<option value='1'>Félins</option>
		<option value='2'>Canidés</option>
		<option value='3'>Equidés</option>
	</select>
	<select name="sscat" id="sscat">
	</select>
	</form>
</body>
</html>