<?php 
include 'header.php';

//Début du rendu HTML
?>
<div id="content">
<center>
    <div id="selection">
    <form method="POST" action="" >
    <label style="  font-family: PoetsenOne-Regular; color: #3F5677; font-size: 35px;"> Liste des requetes : </label>
    <select type="text" name="liste_requete" id="liste_requete" onclick="javascript:requete()" class="selectR" >
      <option value="r1">Requete 1</option>
      <option value="r2">Requete 2</option>
      <option value="r3">Requete 3</option>
      <option value="r4">Requete 4</option>
      <option value="r5">Requete 5</option>
      <option value="r6">Requete 6</option>
      <option value="r7">Requete 7</option>
      <option value="r8">Requete 8</option>
      <option value="r9">Requete 9</option>
    </select>
    </form>
    </div>

	<div id="resultat"></div>
</center>
</div>

