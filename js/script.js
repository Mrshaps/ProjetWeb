function writeInDiv(text,element){
    var objet = document.getElementById(element);
    objet.innerHTML = text;
}

function getXhr(){
 var xhr = null; 
 if(window.XMLHttpRequest) // Firefox et autres
   xhr = new XMLHttpRequest(); 
 else if(window.ActiveXObject){ // Internet Explorer 
   try {
     xhr = new ActiveXObject("Msxml2.XMLHTTP"); } 
   catch (e) {
     xhr = new ActiveXObject("Microsoft.XMLHTTP"); }
 }
 else { // XMLHttpRequest non support par le navigateur 
   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
   xhr = false; 
 } 
  return xhr;
}




//test chaine de caractere
function erreur(chaine){
	document.getElementById('erreur').innerHTML = chaine;

}

//test nombre entier
function testInt(value){
		var a = document.getElementById(value).value;
		var b =	document.getElementById(value);
		var patt = /-*/;
		if (isNaN(a)==true || a == "" || a.match(patt) == "-"){
			b.style.background="#F25A5A";
			//alert("La Masse est incorect");
		}
		else{
			b.style.background="rgb(137, 255, 213)";
			//alert("La Masse est incorect");
		}
}
//fonction qui coche tout le checkbox
var checkflag = "false"; 
function checkAll(field) { 
    if(checkflag == "false") { 
        for(i = 0; i < field.length; i++) { 
        field[i].checked = true;
        } 
        checkflag = "true"; 
        return "Tout décocher"; 
    } 
    else{ 
        for(i = 0; i < field.length; i++) { 
            field[i].checked = false;
        } 
        checkflag = "false"; 
        return "Tout cocher";
    } 
} 


/*----------------Modifier-----------------*/

function modifierEtu(ID,jnom,jprenom,jage,jsexe,jnoIut)
{	
	idLigne="ligne"+ID;
	var ouSuisJe = document.getElementById('ouSuisJe').value ;
	//document.getElementById('test').innerHTML = idLigne;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById(idLigne).innerHTML = xhr.responseText;
	  }
	}
	xhr.open("POST","etudiant.php?action=modifier&page="+ouSuisJe,true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("noEtudiant="+ID+"&nom="+jnom+"&prenom="+jprenom+"&age="+jage+"&sexe="+jsexe+"&noIut="+jnoIut);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}

function modifierCont(ID1, ID2)
{	
	idLigne="ligne"+ID1+ID2;
	var ouSuisJe = document.getElementById('ouSuisJe').value ;
	//document.getElementById('test').innerHTML = idLigne;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById(idLigne).innerHTML = xhr.responseText;
	  }
	}
	xhr.open("POST","contenu.php?action=modifier&page="+ouSuisJe,true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+ID1+"&numEpreuve="+ID2);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}

function modifierPart(ID1,ID2,ID3,resultat)
{	
	idLigne="ligne"+ID1+ID2+ID3;
	var ouSuisJe = document.getElementById('ouSuisJe').value ;
	//document.getElementById('test').innerHTML = idLigne;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById(idLigne).innerHTML = xhr.responseText;
	  }
	}
	xhr.open("POST","participe.php?action=modifier&page="+ouSuisJe,true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+ID1+"&numEpreuve="+ID2+"&noEtudiant="+ID3+"&resultat="+resultat);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}


function modifierMan(ID,nom,date,noIut)
{	
	idLigne="ligne"+ID;
	var ouSuisJe = document.getElementById('ouSuisJe').value ;
	//document.getElementById('test').innerHTML = idLigne;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById(idLigne).innerHTML = xhr.responseText;
	  }
	}
	xhr.open("POST","manifestation.php?action=modifier&page="+ouSuisJe,true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+ID+"&nomMan="+nom+"&dateMan="+date+"&noIut="+noIut);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}

function modifierIut(ID,nom,adresse,nbEtudiants)
{	
	idLigne="ligne"+ID;
	var ouSuisJe = document.getElementById('ouSuisJe').value ;
	//document.getElementById('test').innerHTML = idLigne;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById(idLigne).innerHTML = xhr.responseText;
	  }
	}
	xhr.open("POST","iut.php?action=modifier&page="+ouSuisJe,true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("noIut="+ID+"&nomIut="+nom+"&adresse="+adresse+"&nbEtudiants="+nbEtudiants);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}

function modifierEpr(ID,intitule)
{	
	idLigne="ligne"+ID;
	var ouSuisJe = document.getElementById('ouSuisJe').value ;
	//document.getElementById('test').innerHTML = idLigne;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById(idLigne).innerHTML = xhr.responseText;
	  }
	}
	xhr.open("POST","epreuve.php?action=modifier&page=epreuve",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numEpreuve="+ID+"&intitule="+intitule);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}

function ValiderEtu()
{	
	var ID = document.getElementById('ID').value;
	var nom = document.getElementById('nom').value;
	var prenom = document.getElementById('prenom').value;
	var age = document.getElementById('age').value;
	var sexe = document.getElementById('sexe').value;
	var iut = document.getElementById('noIut').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	xhr.open("POST","etudiant.php?page=etudiant&valider=valider",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("noEtudiant="+ID+"&nom="+nom+"&prenom="+prenom+"&age="+age+"&sexe="+sexe+"&noIut="+iut);


}

function ValiderCont()
{	
	var numMan = document.getElementById('numMan').value;
	var numEpreuve = document.getElementById('numEpreuve').value;

	var old_numMan = document.getElementById('ID1').value;
	var old_numEpreuve = document.getElementById('ID2').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	xhr.open("POST","contenu.php?page=contenu&valider=valider",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+numMan+"&numEpreuve="+numEpreuve+"&old_numMan="+old_numMan+"&old_numEpreuve="+old_numEpreuve);


}

function ValiderPart()
{	
	var numMan = document.getElementById('numMan').value;
	var numEpreuve = document.getElementById('numEpreuve').value;
	var noEtudiant = document.getElementById('noEtudiant').value;
	var resultat = document.getElementById('resultat').value;

	var old_numMan = document.getElementById('ID1').value;
	var old_numEpreuve = document.getElementById('ID2').value;
	var old_noEtudiant = document.getElementById('ID3').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	xhr.open("POST","participe.php?page=participe&valider=valider",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+numMan+"&numEpreuve="+numEpreuve+"&noEtudiant="+noEtudiant+"&resultat="+resultat+"&old_numMan="+old_numMan+"&old_numEpreuve="+old_numEpreuve+"&old_noEtudiant="+old_noEtudiant);


}

function ValiderMan()
{	
	var ID = document.getElementById('ID').value;
	var nom = document.getElementById('nomMan').value;
	var date = document.getElementById('dateMan').value;
	var iut = document.getElementById('noIut').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	xhr.open("POST","manifestation.php?page=manifestation&valider=valider",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+ID+"&nomMan="+nom+"&dateMan="+date+"&noIut="+iut);


}

function ValiderIut()
{	
	var ID = document.getElementById('ID').value;
	var nomIut = document.getElementById('nomIut').value;
	var adresse = document.getElementById('adresse').value;
	var nbEtudiants = document.getElementById('nbEtudiants').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	xhr.open("POST","iut.php?page=iut&valider=valider",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("noIut="+ID+"&nomIut="+nomIut+"&adresse="+adresse+"&nbEtudiants="+nbEtudiants);


}

function ValiderEpr()
{	
	var ID = document.getElementById('ID').value;
	var intitule = document.getElementById('intitule').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	xhr.open("POST","epreuve.php?page=epreuve&valider=valider",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numEpreuve="+ID+"&intitule="+intitule);


}


/*-----------------ajout-----------------*/

function AjouterEtu()
{	
	var nom = document.getElementById('nomAjout').value;
	var prenom = document.getElementById('prenomAjout').value;
	var age = document.getElementById('ageAjout').value;
	var sexe = document.getElementById('sexeAjout').value;
	var iut = document.getElementById('noIutAjout').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
		document.getElementById('html').innerHTML = xhr.responseText;
	  	drawGrid();
	  }
	}
	xhr.open("POST","etudiant.php?page=etudiant&action=ajouter",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("nom="+nom+"&prenom="+prenom+"&age="+age+"&sexe="+sexe+"&noIut="+iut);


}

function AjouterCont()
{	
	var numMan = document.getElementById('numManAjout').value;
	var numEpreuve = document.getElementById('numEpreuveAjout').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
		document.getElementById('html').innerHTML = xhr.responseText;
	  	drawGrid();
	  }
	}
	xhr.open("POST","contenu.php?page=contenu&action=ajouter",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+numMan+"&numEpreuve="+numEpreuve);


}

function AjouterPart()
{	
	var numMan = document.getElementById('numMan').value;
	var numEpreuve = document.getElementById('numEpreuve').value;
	var noEtudiant = document.getElementById('noEtudiant').value;
	var resultat = document.getElementById('resultat').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	xhr.open("POST","participe.php?page=participe&action=ajouter",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+numMan+"&numEpreuve="+numEpreuve+"&noEtudiant="+noEtudiant+"&resultat="+resultat);


}



function AjouterMan()
{	
	var nom = document.getElementById('nomManAjout').value;
	var date = document.getElementById('dateManAjout').value;
	var iut = document.getElementById('noIutAjout').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
		document.getElementById('html').innerHTML = xhr.responseText;
	  	drawGrid();
	  }
	}
	xhr.open("POST","manifestation.php?page=manifestation&action=ajouter",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("nomMan="+nom+"&dateMan="+date+"&noIut="+iut);


}

function AjouterIut()
{	
	var nomIut = document.getElementById('nomIutAjout').value;
	var adresse = document.getElementById('adresseAjout').value;
	var nbEtudiants = document.getElementById('nbEtudiantsAjout').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
		document.getElementById('html').innerHTML = xhr.responseText;
	  	drawGrid();
	  }
	}
	xhr.open("POST","iut.php?page=iut&action=ajouter",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("nomIut="+nomIut+"&adresse="+adresse+"&nbEtudiants="+nbEtudiants);


}


function AjouterEpr()
{	
	var intitule = document.getElementById('intituleAjout').value;
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
		document.getElementById('html').innerHTML = xhr.responseText;
	  	drawGrid();
	  }
	}
	xhr.open("POST","epreuve.php?page=epreuve&action=ajouter",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("intitule="+intitule);


}

/*------------------Supression----------------------*/

function supprimerEtu(noEtudiant)
{	
	idLigne="ligne"+noEtudiant;
	var ouSuisJe = document.getElementById('ouSuisJe').value ;
	//document.getElementById('test').innerHTML = idLigne;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	//Fentre demandant confirmation à l'utilisateur de la supression totale
	var resultat = confirm("Vous allez egalement supprimer les manifestations auxquelles cet etudiant participe ?");
	if(resultat == true)
		xhr.open("POST","etudiant.php?action=supprimer&conf=vrai&page="+ouSuisJe,true);
	else
		xhr.open("POST","etudiant.php?action=supprimer&conf=faux&page="+ouSuisJe,true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("noEtudiant="+noEtudiant);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}

function supprimerCont(numMan, numEpreuve)
{	
	idLigne="ligne"+numMan+numEpreuve;
	var ouSuisJe = document.getElementById('ouSuisJe').value ;
	//document.getElementById('test').innerHTML = idLigne;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	//Fentre demandant confirmation à l'utilisateur de la supression totale
	var resultat = confirm("Etes-vous sur de vouloir supprimer ?");
	if(resultat == true)
		xhr.open("POST","contenu.php?action=supprimer&conf=vrai&page="+ouSuisJe,true);
	else
		xhr.open("POST","contenu.php?action=supprimer&conf=faux&page="+ouSuisJe,true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+numMan+"&numEpreuve="+numEpreuve);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}

function supprimerMan(numMan)
{	
	idLigne="ligne"+numMan;
	var ouSuisJe = document.getElementById('ouSuisJe').value ;
	//document.getElementById('test').innerHTML = idLigne;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	//Fentre demandant confirmation à l'utilisateur de la supression totale
	var resultat = confirm("Vous allez egalement supprimer toutes les associations auxquelles cet cette manifestation est reliée ?");
	if(resultat == true)
		xhr.open("POST","manifestation.php?action=supprimer&conf=vrai&page="+ouSuisJe,true);
	else
		xhr.open("POST","manifestation.php?action=supprimer&conf=faux&page="+ouSuisJe,true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+numMan);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}
		
function supprimerPart(numMan,numEpreuve,noEtudiant){	
	//document.getElementById('test').innerHTML = nom+'-'+prenom+'-'+age+'-'+sexe+'-'+iut;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	xhr.open("POST","participe.php?page=participe&action=supprimer",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("numMan="+numMan+"&numEpreuve="+numEpreuve+"&noEtudiant="+noEtudiant);

}


function supprimerIut(noIut){	
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	var resultat = confirm("La supression de l'iut supprimera les etudiants qui appartiennent a cet iut ainsi que les manifestation auxquelles l'iut participe ?");
	if(resultat == true)
		xhr.open("POST","iut.php?action=supprimer&conf=vrai&page=iut",true);
	else
		xhr.open("POST","iut.php?action=supprimer&conf=faux&page=iut",true);

	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("noIut="+noIut);

}

/*----------------Fonction suppersion multiple------------------*/
function supprMultipleEtudiant(){
	var string="";
	for (var i = 0; i < 10; i++) {
		checkbox = document.getElementById('check'+i);
		if (checkbox.checked == true) {
			string = string+checkbox.value+'#';
		};
	};
	string = string + 'NULL';
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	var resultat = confirm("Vous allez egalement supprimer les manifestation auxquelles ces etudiants participent ?");
	if(resultat == true)
		xhr.open("POST","etudiant.php?action=supprimerMultiple&conf=vrai&page=etudiant",true);
	else
		xhr.open("POST","etudiant.php?action=supprimerMultiple&conf=faux&page=etudiant",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("ID="+string);

}

function supprMultipleManifestation(){
	var string="";
	for (var i = 0; i < 10; i++) {
		checkbox = document.getElementById('check'+i);
		if (checkbox.checked == true) {
			string = string+checkbox.value+'#';
		}
	}
	string = string + 'NULL';
	document.getElementById('erreur').innerHTML = string;
	
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	var resultat = confirm("?");
	if(resultat == true)
		xhr.open("POST","manifestation.php?action=supprimerMultiple&conf=vrai&page=manifestation",true);
	else
		xhr.open("POST","manifestation.php?action=supprimerMultiple&conf=faux&page=manifestation",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("ID="+string);

}

function supprMultipleIut(){
	var string="";
	for (var i = 0; i < 10; i++) {
		checkbox = document.getElementById('check'+i);
		if (checkbox.checked == true) {
			string = string+checkbox.value+'#';
		};
	};
	string = string + 'NULL';
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  document.getElementById('html').innerHTML = xhr.responseText;
	  drawGrid();
	  }
	}
	var resultat = confirm("?");
	if(resultat == true)
		xhr.open("POST","iut.php?action=supprimerMultiple&conf=vrai&page=iut",true);
	else
		xhr.open("POST","iut.php?action=supprimerMultiple&conf=faux&page=iut",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("ID="+string);

}


/*------------------Fonction permettant d'afficher la requete -------------------*/
function requete()
{
    var xhr=null;
    var lr=document.getElementById('liste_requete').value;
   
    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhr.open("POST", "traitement_requete.php", false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("liste_requete="+lr);
    writeInDiv(xhr.responseText,'resultat');
}



