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
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  	// document.getElementById(info).innerHTML = xhr.responseText;
	  	alert(xhr.responseText);
	  }
	}
	xhr.open("POST","script.php",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("noEtudiant="+ID+"&nom="+jnom+"&prenom="+jprenom+"&age="+jage+"&sexe="+jsexe+"&noIut="+jnoIut);


	//$('#'+idLigne).load("traitement.php?action=modifier&page="+ouSuisJe);
}


