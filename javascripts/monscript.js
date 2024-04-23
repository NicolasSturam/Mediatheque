function genererGenre(genre){
	
	let input = document.createElement('input');
	let div = document.createElement('div');
	let genres = document.getElementById('idGenres');
	let label = document.createElement('label');

	let id = genres.childElementCount;

	label.textContent=genre+" : ";
	label.for='id_'+id;
	input.type='checkbox';
	input.name=genre;
	input.value='genre';
	input.id='id_'+id;

	div.classList.add('genre');
	div.appendChild(label);
	div.appendChild(input);
	genres.appendChild(div);
}	

function loadGenre(){
	const xhttp = new XMLHttpRequest();
	let genre = document.getElementById('nouveauGenre').value;
	xhttp.onload = function() {
		//console.log(this.responseText);
		if(this.responseText){
			genererGenre(genre);
		}
		else{
			console.log("Erreur");
		}
	}
	xhttp.open("POST", "javascripts/commSQL.php");
  	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  	xhttp.send("genre="+genre);
}

function genererReal(real){
	let realisateurs = document.getElementById('idRealisateur');
	let option = document.createElement('option');

	let id = realisateurs.childElementCount-1; //-1 a cause du hidden

	option.name="r"+id;
	option.textContent=real;

	realisateurs.appendChild(option);

}

function loadReal(){
	const xhttp = new XMLHttpRequest();
	let real = document.getElementById('nouveauReal').value;
	xhttp.onload = function() {
		//console.log(this.responseText);
		if(this.responseText){
			genererReal(real);
		}
		else{
			console.log("Erreur");
		}
	}
	xhttp.open("POST", "javascripts/commSQL.php");
  	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  	xhttp.send("real="+real);
}

function genererActeur(acteur){
	
	let input = document.createElement('input');
	let div = document.createElement('div');
	let acteurs = document.getElementById('idActeurs');
	let label = document.createElement('label');

	let id = acteurs.childElementCount;

	label.textContent=acteur+" : ";
	label.for='id_'+id;
	input.type='checkbox';
	input.name=acteur;
	input.value='acteur';
	input.id='id_'+id;

	div.classList.add('genre');
	div.appendChild(label);
	div.appendChild(input);
	acteurs.appendChild(div);
}	

function loadActeur(){
	const xhttp = new XMLHttpRequest();
	let acteur = document.getElementById('nouvelActeur').value;
	xhttp.onload = function() {
		//console.log(this.responseText);
		if(this.responseText){
			genererActeur(acteur);
		}
		else{
			console.log("Erreur");
		}
	}
	xhttp.open("POST", "javascripts/commSQL.php");
  	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  	xhttp.send("acteur="+acteur);
}