<?php 
include 'config.php';

$nbRowResult = mysqli_query($link,'SELECT COUNT(numMan) AS nbLigne FROM manifestation') or die ('Unable to execute query. '. mysqli_error($link));
$nbRow = mysqli_fetch_array($nbRowResult);
$nbPage = ceil($nbRow['nbLigne'] / 10);

//Récupération des informations dans la base de données
$query = "SELECT *,iut.noIut FROM manifestation 
            INNER JOIN iut ON iut.noIut = manifestation.noIut ";

if (isset($_GET['order']) && $_GET['order'] == 'nom') {
    $query = $query."ORDER BY nomMan "; $order="nom";
}
if (isset($_GET['order']) && $_GET['order'] == 'nomD') {
    $query = $query."ORDER BY nomMan DESC "; $order="nomD";
}
if (isset($_GET['order']) && $_GET['order'] == 'dat') {
    $query = $query."ORDER BY dateMan "; $order="dat";
}
if (isset($_GET['order']) && $_GET['order'] == 'datD') {
    $query = $query."ORDER BY dateMan DESC "; $order="datD";
}
if (isset($_GET['order']) && $_GET['order'] == 'iut') {
    $query = $query."ORDER BY iut.noIut "; $order="iut";
}
if (isset($_GET['order']) && $_GET['order'] == 'iutD') {
    $query = $query."ORDER BY iut.noIut DESC "; $order="iutD";
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


$result = mysqli_query($link,$query);
//appelé par ajax
if (isset($_GET['page']) && $_GET['page'] == 'manifestation') {
    
        if(isset($_GET['action']) && $_GET['action'] == 'modifier' ){
            /*print('<pre>');
            print_r($_POST);
            print('</pre>');*/
            //header('Content-Type: text/html; charset=utf8_unicode_ci');
            ?>
            <form id="formModif" name="FormModif" action="manifestation.php?valider=valider" method="POST">
            <input type="hidden" id="ID" value="<?php echo $_POST['numMan']; ?>">
            <td><input type="text" class="inputForm" id="nomMan"value='<?php echo $_POST["nomMan"];?>'></td> 
            <td><input type="date" class="inputForm" id="dateMan" value='<?php echo  $_POST["dateMan"];?>'></td> 
            <td>
                <select id="noIut">
                    <?php 
                    $rqtIut = "SELECT noIut, nomIut From iut";
                    $resultRqtIut = mysqli_query($link,$rqtIut) or die ('Unable to execute query. '. mysqli_error($link));
                    
                    while ($rowIut = mysqli_fetch_array($resultRqtIut)) {?>
                        <option value="<?php echo $rowIut['noIut'];?>" 
                        <?php if ($rowIut['noIut'] == $_POST['noIut']){echo "selected='selected'";}?>>
                        <?php echo $rowIut['nomIut']; ?>
                        </option>
                    <?php   
                    } 
                    ?>
                </select>
           
            </td>
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:ValiderMan()"><img src="img/Icon_valider.png"></a>
                <a href="manifestation.php"><img src="img/Icon_annuler.png"></a>
            </td>
            </form>
            <script type="text/javascript">
             $(document).ready(function(){
                  $(document).keydown(function(event){
                    if (event.which == 13) {
                        ValiderEtu();
                    }
                    if (event.which == 27) {
                        document.location.href="manifestation.php";
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
            $verif = "SELECT count(*) AS nbl FROM manifestation WHERE nomMan='".$_POST['nomMan']."'AND dateMan='".$_POST['dateMan']."' AND noIut=".$_POST['noIut'].";";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {
                $rqt = 'UPDATE manifestation set nomMan="'.$_POST['nomMan'].'", dateMan="'.$_POST['dateMan'].'", noIut='.$_POST['noIut'].' WHERE numMan='.$_POST['numMan'].'; ';
                echo $rqt;
                $update_result = mysqli_query( $link , $rqt );
                header('location:manifestation.php');
            }
            else{
                header('location:manifestation.php?erreur=Modification impossible : doublons.');
            }
        }
        //------suprimer------
        if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
            /*Si la valeur de conf == vrai alors on supprime tout*/
            if(isset($_GET['conf']) && $_GET['conf'] == 'vrai'){
                $rqt1 = 'DELETE FROM participe WHERE numMan='.$_POST['numMan'].'; ';
                echo $rqt1;
                $delete_result = mysqli_query( $link , $rqt1 ) or die ('Unable to execute query. '. mysqli_error($link));
                $rqt3 = 'DELETE FROM contenu WHERE numMan='.$_POST['numMan'].'; ';
                echo $rqt3;
                $delete_result = mysqli_query( $link , $rqt3 ) or die ('Unable to execute query. '. mysqli_error($link));
                $rqt2 = 'DELETE FROM manifestation WHERE numMan='.$_POST['numMan'].'; ';
                echo $rqt2;
                $delete_result = mysqli_query( $link , $rqt2 ) or die ('Unable to execute query. '. mysqli_error($link));
            }
            header('location:manifestation.php');
            
        }
        //-------ajouter------
        if (isset($_GET['action']) && $_GET['action'] == 'ajouter') {
            //Vérification de doublon
            $verif = "SELECT count(*) AS nbl FROM manifestation WHERE nomMan='".$_POST['nomMan']."'AND dateMan='".$_POST['dateMan']."' AND noIut=".$_POST['noIut'].";";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {
                $rqt_ajout='INSERT INTO manifestation VALUES (NULL,"'.$_POST['nomMan'].'","'.$_POST['dateMan'].'",'.$_POST['noIut'].' )';
                $res_ajout = mysqli_query($link , $rqt_ajout);
                header('location:manifestation.php');
            }
            else{
                header('location:manifestation.php?erreur=Ajout impossible : doublons.');
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
      <input type="hidden" id="ouSuisJe" value="manifestation">
        <?php if(mysqli_num_rows($result) > 0){ ?>
        <form id="form_suppression" action="" method="POST"  >
            <table class="tableau">
            <caption id="bj">Tableau des Manifestations</caption>
            <thead>
             <tr>
                <th onclick='javascript:location.href = "manifestation.php?order=nom<?php if (isset($_GET['order']) && $_GET['order'] == 'nom') {echo "D";} ?>" ' class="thOrder">Nom <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'nom') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'nomD') {echo "<img src='img/haut.png'>";} } ?></th>
                <th onclick='javascript:location.href = "manifestation.php?order=dat<?php if (isset($_GET['order']) && $_GET['order'] == 'dat') {echo "D";} ?>" ' class="thOrder">Date <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'dat') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'datD') {echo "<img src='img/haut.png'>";} } ?></th>
                <th onclick='javascript:location.href = "manifestation.php?order=iut<?php if (isset($_GET['order']) && $_GET['order'] == 'iut') {echo "D";} ?>" ' class="thOrder">IUT <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'iut') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'iutD') {echo "<img src='img/haut.png'>";} } ?></th>
                <th>Action</th>
             </tr>
            </thead>
        <tbody>
        
          <?php $j=0;
          while( $row = mysqli_fetch_array($result)){ 
            /*print('<pre>');
            print_r($row);
            print('</pre>');*/?>
           <tr id="<?php echo "ligne".$row["numMan"] ?>">
            <td><?php echo $row["nomMan"];?></td> 
            <td><?php echo $row["dateMan"];?></td> 
            <td><?php echo $row["nomIut"];?></td>
            <td class="imgAction">                
                <a href="javascript:modifierMan(<?php echo $row['numMan'] ;?>,'<?php echo $row['nomMan'];?>','<?php echo $row['dateMan'] ;?>','<?php echo $row['noIut'] ;?>')" title="Modifier"><img src="img/Icon_Update.png"></a>
                <a href="javascript:supprimerMan(<?php echo $row['numMan'] ;?>)" title="Supprimer" onclick="confirmer()" ><img src="img/Icon_Delete.png"></a>
            </td>
          </tr>    
          
        <?php
        }
        ?>
        <tr>
            <form id="formModif" name="FormModif" action="manifestation.php?valider=valider" method="POST">
            <td><input type="text" class="inputForm" id="nomManAjout"value='' onkeyup="testString(this.id)" placeholder="Nom"></td> 
            <td><input type="date" class="inputForm" id="dateManAjout" value='' onkeyup="testString(this.id)" placeholder="Date"></td> 
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
                <a href="javascript:AjouterMan()"><img src="img/Icon_valider_ajout.png"></a>
            </td>
            </form>
        </tr>
        </tbody>
        </form>
        </table>
        <span id="erreur"></span>
        <span class="tablePage">
            <?php if (isset($_GET['startIndex']) && $_GET['startIndex'] != 0) {?>
                <a href='manifestation.php?startIndex=<?php echo $startIndexPrew; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Precedent</a>
            <?php }
                else{?>
                <span class="disable">Precedent</span>

               <?php }

            for ($i=0; $i < $nbPage; $i++) {?>
                <a href='manifestation.php?startIndex=<?php echo (int)($i*10) ?>
                <?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'><?php echo $i+1; ?></a>
            <?php } 

            if ($startIndexNext < $nbRow['nbLigne']) {?>
                <a href='manifestation.php?startIndex=<?php echo $startIndexNext; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Suivant</a>           
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
