<?php 
include 'config.php';
$nbRowResult = mysqli_query($link,'SELECT COUNT(noEtudiant) AS nbLigne FROM etudiant') or die ('Unable to execute query. '. mysqli_error($link));
$nbRow = mysqli_fetch_array($nbRowResult);
$nbPage = ceil($nbRow['nbLigne'] / 10);
//echo $nbRow['nbLigne'];
//echo " ".$nbPage;

//Récupération des informations dans la base de données
$query = "SELECT *,iut.noIut FROM etudiant e
            INNER JOIN iut ON iut.noIut = e.noIut 
            ";
//Modifie la requete pour ordonner en fonction des diffrente collone.          
if (isset($_GET['order']) && $_GET['order'] == 'nom') {
    $query = $query."ORDER BY nom "; $order='nom';
}
if (isset($_GET['order']) && $_GET['order'] == 'nomD') {
    $query = $query."ORDER BY nom DESC "; $order='nomD';
}
if (isset($_GET['order']) && $_GET['order'] == 'pre') {
    $query = $query."ORDER BY prenom "; $order='pre';
}
if (isset($_GET['order']) && $_GET['order'] == 'preD') {
    $query = $query."ORDER BY prenom DESC "; $order='preD';
}
if (isset($_GET['order']) && $_GET['order'] == 'age') {
    $query = $query."ORDER BY age "; $order='age';
}
if (isset($_GET['order']) && $_GET['order'] == 'ageD') {
    $query = $query."ORDER BY age DESC "; $order='ageD';
}
if (isset($_GET['order']) && $_GET['order'] == 'sexe') {
    $query = $query."ORDER BY sexe "; $order='sexe';
}
if (isset($_GET['order']) && $_GET['order'] == 'sexeD') {
    $query = $query."ORDER BY sexe DESC "; $order='sexeD';
}
if (isset($_GET['order']) && $_GET['order'] == 'iut') {
    $query = $query."ORDER BY iut.nomIut "; $order='iut';
}
if (isset($_GET['order']) && $_GET['order'] == 'iutD') {
    $query = $query."ORDER BY iut.nomIut DESC "; $order='iutD';
}
//Modifie la requete pour afficher 10 linge
$startIndex =0;
$startIndexNext = 10;
if (isset($_GET['startIndex'])) {
    $startIndex =$_GET['startIndex'];
    $startIndexPrew = $_GET['startIndex'] - 10;
    $startIndexNext = $_GET['startIndex'] + 10;
}
$query=$query."limit ".$startIndex.",10 ";

$result = mysqli_query($link,$query) or die ('Unable to execute query. '. mysqli_error($link));
//appelé par ajax
if (isset($_GET['page']) && $_GET['page'] == 'etudiant') {
        //ligne pour afficher dans le tableau pour modifier
        if(isset($_GET['action']) && $_GET['action'] == 'modifier' ){
            /*print('<pre>');
            print_r($_POST);
            print('</pre>');*/
            ?>
            <form id="formModif" name="FormModif" action="etudiant.php?valider=valider" method="POST">
            <input type="hidden" id="ID" value="<?php echo $_POST['noEtudiant']; ?>">
            <td></td>
            <td><input type="text" class="inputForm" id="nom"value='<?php echo $_POST["nom"];?>'></td> 
            <td><input type="text" class="inputForm" id="prenom" value='<?php echo  $_POST["prenom"];?>'></td> 
            <td><input type="text" class="inputForm" onkeyup="testInt(this.id)" id="age" value='<?php echo $_POST["age"];?>'></td>
            <td>
                <select id="sexe">
                    <option value="<?php echo $_POST["sexe"];?>">M</option>
                    <option value="<?php echo $_POST["sexe"];?>">F</option>
                </select>
            </td>
            <td>
                <select id="noIut">
                    <?php 
                    $rqtIut = "SELECT noIut, nomIut From iut";
                    $resultRqtIut = mysqli_query($link,$rqtIut) or die ('Unable to execute query. '. mysqli_error($link));
                    
                    while ($rowIut = mysqli_fetch_array($resultRqtIut)) {?>
                        <option value="<?php echo $rowIut['noIut']; ?> "
                        <?php if ($rowIut['noIut'] == $_POST['noIut']){echo "selected='selected'";}?>
                        ><?php echo $rowIut['nomIut']; ?></option>
                    <?php   
                    } 
                    ?>
                </select>
           
            </td>
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:ValiderEtu()"><img src="img/Icon_valider.png"></a>
                <a href="etudiant.php"><img src="img/Icon_annuler.png"></a>
            </td>
            </form>
            <script type="text/javascript">
             $(document).ready(function(){
                  $(document).keydown(function(event){
                    if (event.which == 13) {
                        ValiderEtu();
                    }
                    if (event.which == 27) {
                        document.location.href="etudiant.php";
                    }
                    //$("#test").html("Key: " + event.which);
                  });
                });
            </script>
        
        <?php 
        }
        //-----modifier-----
        if(isset($_GET['valider']) && $_GET['valider'] == 'valider'){
            //Vérification de doublon
            $verif = "SELECT count(*) AS nbl FROM etudiant WHERE nom='".$_POST['nom']."'AND prenom='".$_POST['prenom']."';";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0 && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['age']) && !empty($_POST['sege']) && !empty($_POST['noIut']) )
            {
                $rqt = 'UPDATE etudiant set nom="'.$_POST['nom'].'", prenom="'.$_POST['prenom'].'", age='.$_POST['age'].', sexe="'.$_POST['sexe'].'", noIut='.$_POST['noIut'].' WHERE noEtudiant='.$_POST['noEtudiant'].'; ';
                echo $rqt;
                $update_result = mysqli_query( $link , $rqt );
                header('location:etudiant.php');
            }
            else{
                header('location:etudiant.php?erreur=Modification impossible : doublons.');
            }
        }
        //------suprimer------
        if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
            /*Si la valeur de conf == vrai alors on supprime tout*/
            if(isset($_GET['conf']) && $_GET['conf'] == 'vrai'){
                $rqt1 = 'DELETE FROM participe WHERE noEtudiant='.$_POST['noEtudiant'].'; ';
                echo $rqt1;
                $delete_result = mysqli_query( $link , $rqt1 ) or die ('Unable to execute query. '. mysqli_error($link));
                $rqt2 = 'DELETE FROM etudiant WHERE noEtudiant='.$_POST['noEtudiant'].'; ';
                echo $rqt2;
                $delete_result = mysqli_query( $link , $rqt2 ) or die ('Unable to execute query. '. mysqli_error($link));
            }
            header('location:etudiant.php');
            
        }
        //-------suppression multiple------
        if(isset($_GET['action']) && $_GET['action'] == 'supprimerMultiple'){
            /*Si la valeur de conf == vrai alors on supprime tout*/
            if(isset($_GET['conf']) && $_GET['conf'] == 'vrai'){
                $id=explode('#', $_POST['ID']);
                /*print('<pre>');
                print_r($_POST);
                print('</pre>');*/
                foreach ($id as $key ) {
                    $rqt1 = 'DELETE FROM participe WHERE noEtudiant='.$key.'; ';
                    echo $rqt1;
                    $delete_result = mysqli_query( $link , $rqt1 ) or die ('Unable to execute query. '. mysqli_error($link));
                    $rqt2 = 'DELETE FROM etudiant WHERE noEtudiant='.$key.'; ';
                    echo $rqt2;
                    $delete_result = mysqli_query( $link , $rqt2 ) or die ('Unable to execute query. '. mysqli_error($link));
                } 
                
            }
            header('location:etudiant.php');
            
        }
        //-------ajouter------
        if (isset($_GET['action']) && $_GET['action'] == 'ajouter') {

            $_POST['nom'] = wd_remove_accents($_POST['nom'], $charset='utf-8');
            $_POST['prenom'] = wd_remove_accents($_POST['prenom'], $charset='utf-8');
            //Vérification de doublon
            $verif = "SELECT count(*) AS nbl FROM etudiant WHERE nom='".$_POST['nom']."'AND prenom='".$_POST['prenom']."';";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0 && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['age']) && !empty($_POST['sege']) && !empty($_POST['noIut']))
            {
                $rqt_ajout='INSERT INTO etudiant VALUES (NULL,"'.$_POST['nom'].'", "'.$_POST['prenom'].'",'.$_POST['age'].',"'.$_POST['sexe'].'",'.$_POST['noIut'].' )';
                $res_ajout = mysqli_query($link , $rqt_ajout);
                header('location:etudiant.php');
            }
            else{
                header('location:etudiant.php?erreur=Ajout impossible : doublons.');
            }
        }



    }

//page normal
if (!isset($_GET['page'])) {
   include 'header.php';
    //Début du rendu HTML
    ?>
    <div id="content">
    <center>
      <input type="hidden" id="ouSuisJe" value="etudiant">
    	<?php if(mysqli_num_rows($result) > 0){ ?>
        <form id="form_suppression" action="" method="POST"  >
    		<table class="tableau">
    		<caption id="bj">Tableau des Etudiants</caption>
    		<thead>
     		 <tr>
                <th class="suppr" ><a href="javascript:supprMultipleEtudiant()" title="Suppression multiple"><img src="img/Icon_Delete.png"></a><input type="checkbox" id="superCheck" class="checkbox" onclick="this.value=checkAll(this.form);"></th>
       			<th onclick='javascript:location.href = "etudiant.php?order=nom<?php if (isset($_GET['order']) && $_GET['order'] == 'nom') {echo "D";} ?>" ' class="thOrder">Nom <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'nom') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'nomD') {echo "<img src='img/haut.png'>";} } ?></th>
        		<th onclick='javascript:location.href = "etudiant.php?order=pre<?php if (isset($_GET['order']) && $_GET['order'] == 'pre') {echo "D";} ?>" ' class="thOrder">Prenom <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'pre') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'preD') {echo "<img src='img/haut.png'>";} } ?></th>
        		<th onclick='javascript:location.href = "etudiant.php?order=age<?php if (isset($_GET['order']) && $_GET['order'] == 'age') {echo "D";} ?>" ' class="thOrder">Age <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'age') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'ageD') {echo "<img src='img/haut.png'>";} } ?></th>
    	        <th onclick='javascript:location.href = "etudiant.php?order=sexe<?php if (isset($_GET['order']) && $_GET['order'] == 'sexe') {echo "D";} ?>" ' class="thOrder">Sexe <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'sexe') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'sexeD') {echo "<img src='img/haut.png'>";} } ?></th>
    	        <th onclick='javascript:location.href = "etudiant.php?order=iut<?php if (isset($_GET['order']) && $_GET['order'] == 'iut') {echo "D";} ?>" ' class="thOrder">IUT <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'iut') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'iutD') {echo "<img src='img/haut.png'>";} } ?></th>
    	        <th>Action</th>
     		 </tr>
    		</thead>
        <tbody>
            
          <?php $j=0;
          while( $row = mysqli_fetch_array($result)){ 
            /*print('<pre>');
            print_r($row);
            print('</pre>');*/?>
           <tr id="<?php echo "ligne".$row["noEtudiant"] ?>">
            <td><input type="checkbox" id="check<?php echo $j; $j++; ?>" value="<?php echo $row["noEtudiant"]; ?>"></td>
            <td><?php echo $row["nom"];?></td> 
            <td><?php echo $row["prenom"];?></td> 
            <td><?php echo $row["age"];?></td>
            <td><?php echo $row["sexe"];?></td>
            <td><?php echo $row["nomIut"];?></td>
            <td class="imgAction">                
                <a href="javascript:modifierEtu(<?php echo $row['noEtudiant'] ;?>,'<?php echo $row['nom'];?>','<?php echo $row['prenom'] ;?>','<?php echo $row['age'] ;?>','<?php echo $row['sexe'] ;?>','<?php echo $row['noIut'] ;?>')" title="Modifier"><img src="img/Icon_Update.png"></a>
                <a href="javascript:supprimerEtu(<?php echo $row['noEtudiant'] ;?>)" title="Supprimer" onclick="confirmer()" ><img src="img/Icon_Delete.png"></a>
            </td>
          </tr>    
          
        <?php
        }
        ?>
        <tr>
            <form id="formModif" name="FormModif" action="etudiant.php?valider=valider" method="POST">
            <td></td>
            <td><input type="text" class="inputForm" id="nomAjout"value='' onkeyup="testString(this.id)" placeholder="Nom"></td> 
            <td><input type="text" class="inputForm" id="prenomAjout" value='' onkeyup="testString(this.id)" placeholder="Prenom"></td> 
            <td><input type="text" class="inputForm" id="ageAjout" value='' onkeyup="testInt(this.id)" placeholder="Age"></td>
            <td>
                <select id="sexeAjout">
                    <option value="M">M</option>
                    <option value="F">F</option>
                </select>
            </td>
            <td>
                <select id="noIutAjout">
                    <?php 
                    $rqtIut = "SELECT noIut, nomIut From iut";
                    $resultRqtIut = mysqli_query($link,$rqtIut) or die ('Unable to execute query. '. mysqli_error($link));
                    
                    while ($rowIut = mysqli_fetch_array($resultRqtIut)) {?>
                        <option value="<?php echo $rowIut['noIut']; ?> "><?php echo $rowIut['nomIut']; ?></option>
                    <?php   
                    } 
                    ?>
                </select>
           
            </td>
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:AjouterEtu()"><img src="img/Icon_valider_ajout.png"></a>
            </td>
            </form>
        </tr>
        </tbody>
        </form>
        </table>
        <span id="erreur"><?php if (isset($_GET['erreur'])) {
                echo $_GET['erreur'];
            } ?>
            <br><br>
        </span>
        <span class="tablePage">
            <?php if (isset($_GET['startIndex']) && $_GET['startIndex'] != 0) {?>
                <a href='etudiant.php?startIndex=<?php echo $startIndexPrew; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Precedent</a>
            <?php }
                else{?>
                <span class="disable">Precedent</span>

               <?php }

            for ($i=0; $i < $nbPage; $i++) {?>
                <a href='etudiant.php?startIndex=<?php echo (int)($i*10) ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'><?php echo $i+1; ?></a>
            <?php } 

            if ($startIndexNext < $nbRow['nbLigne']) {?>
                <a href='etudiant.php?startIndex=<?php echo $startIndexNext; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Suivant</a>           
            <?php } 
            else {?>
                <span class="disable">Suivant</span>
            <?php }

            ?>


        </span>
        
        </center>
        
    <?php
    }

}
print('</div>');


include 'footer.php';
?>