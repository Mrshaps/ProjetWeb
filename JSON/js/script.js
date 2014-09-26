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


var cmptLigne;

function CalculSomme(){
  var S = 0;
  for (var i = 0; i < cmptLigne; i++) {
    x = parseFloat(document.getElementById('m'+i).innerHTML);
    S=parseFloat(S)+x;
  };

  var std = document.getElementById('Somme');
  std.innerHTML = "";
  p1 = document.createElement('P');
  p2 = document.createElement('P');
  e = document.createTextNode(' \u20AC');
  p1.innerHTML = S;
  p2.appendChild(e);
  std.appendChild(p1);
  std.appendChild(p2);


  var Total = document.getElementById('Total');
  Total.innerHTML ="";
  r = document.getElementById('tdRemise').innerHTML;
  p1 = document.createElement('P');
  p2 = document.createElement('P');
  e = document.createTextNode(' \u20AC');
  sortie = S - S * r / 100;
  p1.innerHTML = sortie.toFixed(2);
  p2.appendChild(e);
  Total.appendChild(p1);
  Total.appendChild(p2);


}

function CalculMontant (ligne) {
  
  Quantite = document.getElementById('qte'+ligne).value;

  var prix = document.getElementById('prixU'+ligne).innerHTML;
  m = document.getElementById('montant'+ligne);
  m.innerHTML = "";
  p1 = document.createElement('P');
  p2 = document.createElement('P');
  prix = document.createTextNode(prix * Quantite);
  e = document.createTextNode(' \u20AC');
  m.appendChild(p1)
  p1.appendChild(prix);
  p1.setAttribute('id','m'+ligne);
  p2.appendChild(e);
  m.appendChild(p2);


}

function InfoLigne(service, ligne) {
  //alert(service);
  var xhr = getXhr();
  xhr.onreadystatechange = function(){
    if(xhr.readyState == 4 && xhr.status == 200){
      //document.getElementById('error').innerHTML = xhr.responseText;
      var rep = JSON.parse(xhr.responseText);

      document.getElementById('cPrestation'+ligne).innerHTML = rep.codeService;
      prixT = document.getElementById('prix'+ligne);

      p1 = document.createElement('P');
      p2 = document.createElement('P');
      prix = document.createTextNode(rep.cout);
      e = document.createTextNode(' \u20AC');
      p1.appendChild(prix);
      p1.setAttribute('id','prixU'+ligne);
      p2.appendChild(e);
      prixT.innerHTML = "";
      prixT.appendChild(p1);
      prixT.appendChild(p2);
    }
  }
  xhr.open("POST","script.php?action=InfoLigne",true);
  xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  xhr.send("codeServices="+service);

}

function SupprimerLigne() {
  var table = document.getElementById('tbodyId');
  table.deleteRow(cmptLigne-1);
  cmptLigne--;
}

function AjoutLigne() {

  var xhr = getXhr();
  xhr.onreadystatechange = function(){
    if(xhr.readyState == 4 && xhr.status == 200){
      //document.getElementById('error').innerHTML = xhr.responseText;
      var rep = JSON.parse(xhr.responseText);
      var table = document.getElementById('tbodyId');
      //alert(table);
      var row = table.insertRow(); 
      var td1 = row.insertCell(); td1.setAttribute('id','cPrestation'+cmptLigne);
      var td2 = row.insertCell(); var ttd2 = document.createElement('SELECT'); td2.appendChild(ttd2);
            ttd2.setAttribute('id','listeServices');
            ttd2.setAttribute('name',cmptLigne);
            ttd2.setAttribute('onchange','InfoLigne(this.value,this.name); CalculMontant(this.name); CalculSomme()');
            var value = 0;
              var nom = "Vide";
              var option = document.createElement('option'); option.setAttribute("value",value);
              var title = document.createTextNode(nom); option.appendChild(title); ttd2.appendChild(option);
            var cmpt = 1;
            

            for (var i = 0; i < rep.length; i++) {
              var value = rep[i].codeService;
              var nom = rep[i].prestation;
              var option = document.createElement('option'); option.setAttribute("value",value);
              var title = document.createTextNode(nom); option.appendChild(title); ttd2.appendChild(option);
            }

      pPrix = document.createElement('P');
      pPrix.setAttribute('id', 'prixU'+cmptLigne);
      pE = document.createElement('P');
      var td3 = row.insertCell(); var ttd3_1 = document.createTextNode('0'); var ttd3_2 = document.createTextNode(' \u20AC'); 
      td3.appendChild(pPrix); td3.appendChild(pE); pPrix.appendChild(ttd3_1); pE.appendChild(ttd3_2); 
      td3.setAttribute('style','display: inline-flex;'); td3.setAttribute('id','prix'+cmptLigne);
      

      var td4 = row.insertCell(); var ttd4 = document.createElement('INPUT'); td4.appendChild(ttd4); ttd4.setAttribute('name',cmptLigne); ttd4.setAttribute('id','qte'+cmptLigne); 
      ttd4.setAttribute('type','text'); ttd4.setAttribute('placeholder','0'); ttd4.setAttribute('onkeyup','CalculMontant(this.name); CalculSomme()');
      
      pMontant = document.createElement('P');
      pMontant.setAttribute('id', 'm'+cmptLigne);
      pE = document.createElement('P');
      var td5 = row.insertCell(); var ttd5_1 = document.createTextNode('0'); var ttd5_2 = document.createTextNode(' \u20AC'); 
      td5.setAttribute('id','montant'+cmptLigne); td5.setAttribute('style','display: inline-flex;');
      td5.appendChild(pMontant); td5.appendChild(pE); pMontant.appendChild(ttd5_1); pE.appendChild(ttd5_2);

      cmptLigne++;

    }
  }
  xhr.open("POST","script.php?action=newLigne",true);
  xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  xhr.send();
  
}

function infoClient() {	
	var codeClients	= document.getElementById('inputIdClient').value;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  	// ----info client----
      document.getElementById('info').innerHTML = "";

      var rep = JSON.parse(xhr.responseText);


      //document.getElementById('error').innerHTML = xhr.responseText;
      var i = xhr.responseText.split(",");

      var p = document.createElement('P');
      var t1 = document.createTextNode(rep.civilite+" "+rep.nom+" "+rep.prenom);
      var t2 = document.createTextNode(rep.adresse);
      var t3 = document.createTextNode(rep.codePostal+" "+rep.ville);
      
      p.appendChild(t1);
      p.appendChild(document.createElement('br'));
      p.appendChild(t2);
      p.appendChild(document.createElement('br'));
      p.appendChild(t3);
      document.getElementById('info').appendChild(p);

      document.getElementById('tableau').style.visibility="visible";
      document.getElementById('tdCarte').innerHTML="Votre carte est "+rep.carte+".";
      document.getElementById('tdRemise').innerHTML=rep.remise;
      cmptLigne = 0;

      
	  }
	}
	xhr.open("POST","script.php?action=infoClient",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("codeClients="+codeClients);
}




