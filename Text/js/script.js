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
  p1.innerHTML = S - S * r / 100;
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
      // document.getElementById('error').innerHTML = xhr.responseText;
      i = xhr.responseText.split('@');
      document.getElementById('cPrestation'+ligne).innerHTML = i[1];
      prixT = document.getElementById('prix'+ligne);

      p1 = document.createElement('P');
      p2 = document.createElement('P');
      prix = document.createTextNode(i[3]);
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
      // document.getElementById('error').innerHTML = xhr.responseText;
      var i1=xhr.responseText.split('#');

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
            while (i1[cmpt]) {
              var i2 = i1[cmpt].split('@');
              var value = i2[0];
              var nom = i2[1];
              var option = document.createElement('option'); option.setAttribute("value",value);
              var title = document.createTextNode(nom); option.appendChild(title); ttd2.appendChild(option);
              cmpt++;
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

function infoClient()
{	
	var codeClients	= document.getElementById('inputIdClient').value;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
	if(xhr.readyState == 4 && xhr.status == 200){
	  	// ----info client----
      document.getElementById('info').innerHTML = "";
      // document.getElementById('error').innerHTML = xhr.responseText;
      var i = xhr.responseText.split(",");

      var p = document.createElement('P');
      var t1 = document.createTextNode(i[0]+" "+i[1]+" "+i[2]);
      var t2 = document.createTextNode(i[3]);
      var t3 = document.createTextNode(i[4]+" "+i[5]);
      
      p.appendChild(t1);
      p.appendChild(document.createElement('br'));
      p.appendChild(t2);
      p.appendChild(document.createElement('br'));
      p.appendChild(t3);
      document.getElementById('info').appendChild(p);

      document.getElementById('tableau').style.visibility="visible";
      document.getElementById('tdCarte').innerHTML="Votre carte est "+i[6]+".";
      document.getElementById('tdRemise').innerHTML=i[7];
      cmptLigne = 0;


      // // ---- Tableau ----
      // var divTableau = document.createElement('DIV');
      // var btnNewLine = document.createElement("INPUT");
      // btnNewLine.setAttribute("type", "button");
      // btnNewLine.setAttribute('value', 'Ajouter une ligne');
      // btnNewLine.setAttribute('onclick', 'AjoutLigne()');

      // // ---- bouton ----
      // var btnSuprLine = document.createElement("INPUT");
      // btnSuprLine.setAttribute("type", "button");
      // btnSuprLine.setAttribute('value', 'Supprimer une ligne');
      // //btnSuprLine.setAttribute('onclick', '');

      // divTableau.appendChild(btnNewLine);
      // divTableau.appendChild(btnSuprLine);
      // document.getElementById('info').appendChild(divTableau);
      
      // //----table----
      // var table = document.createElement('TABLE');
      // table.setAttribute('id','table');

      // //----tbody----
      // var row = table.insertRow();
      // var tbody = document.getElementsByTagName('tbody');
      // //tbody.setAttribute('id','tbodyId');


      // //----thead----
      // var thead = table.createTHead();
      // var tr = thead.insertRow();
      // var th1 = tr.insertCell(); var tth1 = document.createTextNode("Code prestation"); th1.appendChild(tth1);
      // var th2 = tr.insertCell(); var tth2 = document.createTextNode("Designation");     th2.appendChild(tth2);
      // var th3 = tr.insertCell(); var tth3 = document.createTextNode("Prix");            th3.appendChild(tth3);
      // var th4 = tr.insertCell(); var tth4 = document.createTextNode("Quantite");        th4.appendChild(tth4);
      // var th5 = tr.insertCell(); var tth5 = document.createTextNode("Montant");         th5.appendChild(tth5);
      // document.getElementById('info').appendChild(table);
      // //AjoutLigne()
      // //----tFoot----
      // var tfoot = table.createTFoot();
      // var rf1 = tfoot.insertRow();
      // var rf2 = tfoot.insertRow();
      // var rf3 = tfoot.insertRow();

      // var rf1Cell0 = rf1.insertCell(); var trf1Cell0 = document.createTextNode("Votre carte est "+i[6]+"."); rf1Cell0.appendChild(trf1Cell0); 
      // rf1Cell0.setAttribute('colspan','3'); rf1Cell0.setAttribute('rowspan','3');
      // var rf1Cell1 = rf1.insertCell(); var rf1Cell2 = rf1.insertCell(); var trf1Cell1 = document.createTextNode("Somme");    rf1Cell1.appendChild(trf1Cell1);
      // var rf2Cell1 = rf2.insertCell(); var rf1Cell2 = rf2.insertCell(); var trf2Cell1 = document.createTextNode("Remise");    rf2Cell1.appendChild(trf2Cell1);
      // var rf3Cell1 = rf3.insertCell(); var rf1Cell2 = rf3.insertCell(); var trf3Cell1 = document.createTextNode("A regler");  rf3Cell1.appendChild(trf3Cell1);

      
	  }
	}
	xhr.open("POST","script.php?action=infoClient",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("codeClients="+codeClients);


}





