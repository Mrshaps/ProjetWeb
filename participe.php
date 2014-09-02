<?php 
include 'config.php';

$nbRowResult = mysqli_query($link,'SELECT COUNT(numMan) AS nbLigne FROM participe') or die ('Unable to execute query. '. mysqli_error($link));
$nbRow = mysqli_fetch_array($nbRowResult);
$nbPage = ceil($nbRow['nbLigne'] / 10);

//Récupération des informations dans la base de données
$query = "SELECT ma.nomMan, ep.intitule, et.nom, et.prenom, p.numMan, p.numEpreuve, p.noEtudiant, p.resultat 
            FROM participe p
            INNER JOIN etudiant et ON p.noEtudiant = et.noEtudiant
            INNER JOIN epreuve ep ON p.numEpreuve = ep.numEpreuve
            INNER JOIN manifestation ma ON p.numMan = ma.numMan
            ";
if (isset($_GET['order']) && $_GET['order'] == 'man') {
    $query = $query."ORDER BY ma.nomMan "; $order="man";
}
if (isset($_GET['order']) && $_GET['order'] == 'manD') {
    $query = $query."ORDER BY ma.nomMan DESC "; $order="manD";
}
if (isset($_GET['order']) && $_GET['order'] == 'ep') {
    $query = $query."ORDER BY ep.intitule "; $order="ep";
}
if (isset($_GET['order']) && $_GET['order'] == 'epD') {
    $query = $query."ORDER BY ep.intitule DESC "; $order="epD";
}
if (isset($_GET['order']) && $_GET['order'] == 'etu') {
    $query = $query."ORDER BY et.nom "; $order="etu";
}
if (isset($_GET['order']) && $_GET['order'] == 'etuD') {
    $query = $query."ORDER BY et.nom DESC "; $order="etuD";
}
if (isset($_GET['order']) && $_GET['order'] == 'res') {
    $query = $query."ORDER BY p.resultat "; $order="res";
}
if (isset($_GET['order']) && $_GET['order'] == 'resD') {
    $query = $query."ORDER BY p.resultat DESC "; $order="resD";
}

$startIndex =0;
$startIndexNext = 10;
if (isset($_GET['startIndex'])) {
    $startIndex =$_GET['startIndex'];
    $startIndexPrew = $_GET['startIndex'] - 10;
    $startIndexNext = $_GET['startIndex'] + 10;
}
$query=$query."limit ".$startIndex.",10 ";

$result = mysqli_query($link,$query);
//appelé par ajax
if (isset($_GET['page']) && $_GET['page'] == 'participe') {
    
        if(isset($_GET['action']) && $_GET['action'] == 'modifier' ){
            /*print('<pre>');
            print_r($_POST);
            print('</pre>');*/
            

            ?>
            <form id="formModif" name="FormModif" action="participe.php?valider=valider" method="POST">

            <input type="hidden" id="ID1" value="<?php echo $_POST['numMan']; ?>">
            <input type="hidden" id="ID2" value="<?php echo $_POST['numEpreuve']; ?>">
            <input type="hidden" id="ID3" value="<?php echo $_POST['noEtudiant']; ?>">

            <td>
                <select id="numMan">
                    <?php 
                    $rqtIut = "SELECT numMan, nomMan From manifestation";
                    $resultRqtIut = mysqli_query($link,$rqtIut) or die ('Unable to execute query. '. mysqli_error($link));
                    
                    while ($row = mysqli_fetch_array($resultRqtIut)) {?>
                        <option value="<?php echo $row['numMan'];?>" 
                        <?php if ($row['numMan'] == $_POST['numMan']){echo "selected='selected'";}?>>
                        <?php echo $row['nomMan']; ?>
                        </option>
                    <?php   
                    } 
                    ?>
                </select>
            </td>
            <td>
                <select id="numEpreuve">
                    <?php 
                    $rqtIut = "SELECT numEpreuve, intitule From epreuve";
                    $resultRqtIut = mysqli_query($link,$rqtIut) or die ('Unable to execute query. '. mysqli_error($link));
                    
                    while ($row = mysqli_fetch_array($resultRqtIut)) {?>
                        <option value="<?php echo $row['numEpreuve']; ?> "
                        <?php if ($row['numEpreuve'] == $_POST['numEpreuve']){echo "selected='selected'";}?>>
                        <?php echo $row['intitule']; ?></option>
                    <?php   
                    } 
                    ?>
                </select>
            </td>
            <td>
                <select id="noEtudiant">
                    <?php 
                    $rqtIut = "SELECT noEtudiant, nom , prenom From etudiant";
                    $resultRqtIut = mysqli_query($link,$rqtIut) or die ('Unable to execute query. '. mysqli_error($link));
                    
                    while ($row = mysqli_fetch_array($resultRqtIut)) {?>
                        <option value="<?php echo $row['noEtudiant']; ?> "
                        <?php if ($row['noEtudiant'] == $_POST['noEtudiant']){echo "selected='selected'";}?>><?php echo $row['prenom'].' '.$row['nom'] ; ?></option>
                    <?php   
                    } 
                    ?>
                </select>
            </td>
            <td><input type="text" id="resultat"value='<?php echo $_POST["resultat"];?>' class="inputForm" ></td>
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:ValiderPart()"><img src="img/Icon_valider.png"></a>
                <a href="participe.php"><img src="img/Icon_annuler.png"></a>
            </td>
            </form>
        
        <?php 
        }
        //-----modifier-----
        if(isset($_GET['valider']) && $_GET['valider'] == 'valider'){
            //Vérification de doublon
            $verif = "SELECT count(*) AS nbl FROM participe WHERE numMan=".$_POST['numMan']." AND numEpreuve=".$_POST['numEpreuve']." AND noEtudiant=".$_POST['noEtudiant'].";";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {
                $rqt = "UPDATE participe SET numMan=".$_POST['numMan'].", numEpreuve=".$_POST['numEpreuve'].", noEtudiant=".$_POST['noEtudiant'].", resultat=".$_POST['resultat']." 
                WHERE numMan=".$_POST['old_numMan']." AND numEpreuve=".$_POST['old_numEpreuve']." AND noEtudiant=".$_POST['old_noEtudiant'].";";
                echo $rqt;
                $update_result = mysqli_query( $link , $rqt );
                header('location:participe.php');
            }
            else{
                header('location:participe.php?erreur=Modification impossible : doublons.');
            }
        } 
        //------supprimer------
        if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
            /*Si la valeur de conf == vrai alors on supprime tout*/
            $rqt1 = 'DELETE FROM participe WHERE numMan='.$_POST['numMan'].' AND numEpreuve='.$_POST['numEpreuve'].' AND noEtudiant='.$_POST['noEtudiant'].'; ';
            //echo $rqt1;
            $delete_result = mysqli_query( $link , $rqt1 );
            header('location:participe.php');
        }
        //------ajouter------
        if (isset($_GET['action']) && $_GET['action'] == 'ajouter') {
            //Vérification de doublon
            $verif = "SELECT count(*) AS nbl FROM participe WHERE numMan=".$_POST['numMan']." AND numEpreuve=".$_POST['numEpreuve']." AND noEtudiant=".$_POST['noEtudiant'].";";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {
                $rqt_ajout='INSERT INTO participe VALUES ('.$_POST['numMan'].', '.$_POST['numEpreuve'].','.$_POST['noEtudiant'].','.$_POST['resultat'].' )';
                echo $rqt_ajout;
                $res_ajout = mysqli_query($link , $rqt_ajout);
                header('location:participe.php');
            }
            else{
                header('location:participe.php?erreur=Ajout impossible : doublons.');
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
      <input type="hidden" id="ouSuisJe" value="participe">
    	<?php if(mysqli_num_rows($result) > 0){ ?>
        <form id="form_suppression" action="" method="POST"  >
    		<table class="tableau">
    		<caption id="bj">Tableau des participations</caption>
    		<thead>
     		 <tr>
       			<th onclick='javascript:location.href = "participe.php?order=man<?php if (isset($_GET['order']) && $_GET['order'] == 'man') {echo "D";} ?>" ' class="thOrder">Manifestation <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'man') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'manD') {echo "<img src='img/haut.png'>";} } ?></th>
        		<th onclick='javascript:location.href = "participe.php?order=ep<?php if (isset($_GET['order']) && $_GET['order'] == 'ep') {echo "D";} ?>" ' class="thOrder">Epreuve <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'ep') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'epD') {echo "<img src='img/haut.png'>";} } ?></th>
        		<th onclick='javascript:location.href = "participe.php?order=etu<?php if (isset($_GET['order']) && $_GET['order'] == 'etu') {echo "D";} ?>" ' class="thOrder">Etudiant <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'etu') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'etuD') {echo "<img src='img/haut.png'>";} } ?></th>
    	        <th onclick='javascript:location.href = "participe.php?order=res<?php if (isset($_GET['order']) && $_GET['order'] == 'res') {echo "D";} ?>" ' class="thOrder">Resultat <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'res') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'resD') {echo "<img src='img/haut.png'>";} } ?></th>
                <th>Action</th>
     		 </tr>
    		</thead>
        <tbody>
        
          <?php while( $row = mysqli_fetch_array($result)){ 
            /*print('<pre>');
            print_r($row);
            print('</pre>');*/?>
         <tr id="<?php echo "ligne".$row["numMan"].$row["numEpreuve"].$row["noEtudiant"]; ?>">
            <td><?php echo $row["nomMan"];?></td> 
            <td><?php echo $row["intitule"];?></td> 
            <td><?php echo $row['prenom'].' '.$row["nom"];?></td>
            <td><?php echo $row["resultat"];?></td>
            <td class="imgAction">
                
                
                <a href="javascript:modifierPart(<?php echo $row['numMan'] ;?>,<?php echo $row['numEpreuve'];?>,<?php echo $row['noEtudiant'];?>,'<?php echo $row['resultat'] ;?>')" title="Modifier"><img src="img/Icon_Update.png"></a>
                <a href="javascript:supprimerPart(<?php echo $row['numMan']; ?>,<?php echo $row['numEpreuve'];?>,<?php echo $row['noEtudiant'];?>)" title="Suprimer"><img src="img/Icon_Delete.png"></a>
            </td>
          </tr>   
          
        <?php
        }
        ?>
        <tr>
            <td>
                <select id="numMan">
                    <?php 
                    $rqtIut = "SELECT numMan, nomMan From manifestation";
                    $resultRqtIut = mysqli_query($link,$rqtIut) or die ('Unable to execute query. '. mysqli_error($link));
                    
                    while ($row = mysqli_fetch_array($resultRqtIut)) {?>
                        <option value="<?php echo $row['numMan'];?>" >
                        <?php echo $row['nomMan']; ?>
                        </option>
                    <?php   
                    } 
                    ?>
                </select>
            </td>
            <td>
                <select id="numEpreuve">
                    <?php 
                    $rqtIut = "SELECT numEpreuve, intitule From epreuve";
                    $resultRqtIut = mysqli_query($link,$rqtIut) or die ('Unable to execute query. '. mysqli_error($link));
                    
                    while ($row = mysqli_fetch_array($resultRqtIut)) {?>
                        <option value="<?php echo $row['numEpreuve']; ?> ">
                        <?php echo $row['intitule']; ?></option>
                    <?php   
                    } 
                    ?>
                </select>
            </td>
            <td>
                <select id="noEtudiant">
                    <?php 
                    $rqtIut = "SELECT noEtudiant, nom , prenom From etudiant";
                    $resultRqtIut = mysqli_query($link,$rqtIut) or die ('Unable to execute query. '. mysqli_error($link));
                    
                    while ($row = mysqli_fetch_array($resultRqtIut)) {?>
                        <option value="<?php echo $row['noEtudiant']; ?> ">
                        <?php echo $row['prenom'].' '.$row['nom'] ; ?></option>
                    <?php   
                    } 
                    ?>
                </select>
            </td>
            <td><input type="text" id="resultat" value="" class="inputForm" placeholder="Resultat" onkeyup="testInt(this.id)"></td>
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:AjouterPart()"><img src="img/Icon_valider_ajout.png"></a>
            </td>
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
                <a href='participe.php?startIndex=<?php echo $startIndexPrew; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Precedent</a>
            <?php }
                else{?>
                <span class="disable">Precedent</span>

               <?php }

            for ($i=0; $i < $nbPage; $i++) {?>
                <a href='participe.php?startIndex=<?php echo (int)($i*10) ?>
                <?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'><?php echo $i+1; ?></a>
            <?php } 

            if ($startIndexNext < $nbRow['nbLigne']) {?>
                <a href='participe.php?startIndex=<?php echo $startIndexNext; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Suivant</a>           
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