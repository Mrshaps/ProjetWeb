<?php
  include 'config.php';

$nbRowResult = mysqli_query($link,'SELECT COUNT(noIut) AS nbLigne FROM iut') or die ('Unable to execute query. '. mysqli_error($link));
$nbRow = mysqli_fetch_array($nbRowResult);
$nbPage = ceil($nbRow['nbLigne'] / 10);

//Récupération des informations dans la base de données
$query = "SELECT * FROM iut ";

if (isset($_GET['order']) && $_GET['order'] == 'nom') {
    $query = $query."ORDER BY nomIut "; $order="nom";
}
if (isset($_GET['order']) && $_GET['order'] == 'nomD') {
    $query = $query."ORDER BY nomIut DESC "; $order="nomD";
}
if (isset($_GET['order']) && $_GET['order'] == 'ad') {
    $query = $query."ORDER BY adresse "; $order="ad";
}
if (isset($_GET['order']) && $_GET['order'] == 'adD') {
    $query = $query."ORDER BY adresse DESC "; $order="adD";
}
if (isset($_GET['order']) && $_GET['order'] == 'nb') {
    $query = $query."ORDER BY nbEtudiants "; $order="nb";
}
if (isset($_GET['order']) && $_GET['order'] == 'nbD') {
    $query = $query."ORDER BY nbEtudiants DESC "; $order="nbD";
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

if (isset($_GET['page']) && $_GET['page'] == 'iut') {
        //--------affiche la ligne avec les inputs------
        if(isset($_GET['action']) && $_GET['action'] == 'modifier' ){
            /*print('<pre>');
            print_r($_POST);
            print('</pre>');*/
            //header('Content-Type: text/html; charset=utf8_unicode_ci');
            ?>
            <form id="formModif" name="FormModif" action="etudiant.php?valider=valider" method="POST">
            <input type="hidden" id="ID" value="<?php echo $_POST['noIut']; ?>">
            <td><input type="text" class="inputForm" id="nomIut"value='<?php echo $_POST["nomIut"];?>'></td> 
            <td><input type="text" class="inputForm" id="adresse" value='<?php echo  $_POST["adresse"];?>'></td> 
            <td><input type="text" class="inputForm" onkeyup="testInt(this.id)" id="nbEtudiants" value='<?php echo $_POST["nbEtudiants"];?>'></td>
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:ValiderIut()"><img src="img/Icon_valider.png"></a>
                <a href="iut.php"><img src="img/Icon_annuler.png"></a>
            </td>
            </form>
            <script type="text/javascript">
             $(document).ready(function(){
                $("#test").html("bonjour");
                  $(document).keydown(function(event){
                    if (event.which == 13) {
                        ValiderIut();
                    }
                    if (event.which == 27) {
                        document.location.href="iut.php";
                    }
                    $("#test").html("Key: " + event.which);
                  });
                });
            </script>
        
        <?php 
        }
        //-----modifier-----
        if(isset($_GET['valider']) && $_GET['valider'] == 'valider'){
            //Vérification de doublon
            $verif = "SELECT count(*) AS nbl FROM iut WHERE nomIut='".$_POST['nomIut']."';";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {           
                $rqt = 'UPDATE iut set nomIut="'.$_POST['nomIut'].'", adresse="'.$_POST['adresse'].'", nbEtudiants='.$_POST['nbEtudiants'].' WHERE noIut='.$_POST['noIut'].'; ';
                echo $rqt;
                $update_result = mysqli_query( $link , $rqt ) or die ('Unable to execute query. '. mysqli_error($link));
                header('location:iut.php');
            }
            else{
                header('location:iut.php?erreur=Modification impossible : doublons.');
            }

        }
        //------suprimer------
        if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
            /*Si la valeur de conf == vrai alors on supprime tout*/
            if(isset($_GET['conf']) && $_GET['conf'] == 'vrai'){
                $rqt1 = 'DELETE FROM etudiant WHERE noIut='.$_POST['noIut'].'; ';
                $rqt2 = 'DELETE FROM manifestation WHERE noIut='.$_POST['noIut'].'; ';
                $rqt3 = 'DELETE FROM iut WHERE noIut='.$_POST['noIut'].'; ';
                echo $rqt1.'<br/>'.$rqt2.'<br/>'.$rqt3.'<br/>';
                $delete_result = mysqli_query( $link , $rqt1 ) or die ('Unable to execute query. '. mysqli_error($link));
                $delete_result = mysqli_query( $link , $rqt2 ) or die ('Unable to execute query. '. mysqli_error($link));
                $delete_result = mysqli_query( $link , $rqt3 ) or die ('Unable to execute query. '. mysqli_error($link));
            }
            header('location:iut.php');
            
        }
        //-------ajouter------
        if (isset($_GET['action']) && $_GET['action'] == 'ajouter') {
            //Vérification de doublon
            $verif = "SELECT count(*) AS nbl FROM iut WHERE nomIut='".$_POST['nomIut']."';";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {
                $_POST['nomIut'] = wd_remove_accents($_POST['nomIut'], $charset='utf-8');
                $_POST['adresse'] = wd_remove_accents($_POST['adresse'], $charset='utf-8');
                $rqt_ajout='INSERT INTO iut VALUES (NULL,"'.$_POST['nomIut'].'", "'.$_POST['adresse'].'",'.$_POST['nbEtudiants'].',0)';
                $res_ajout = mysqli_query($link , $rqt_ajout);
                header('location:iut.php');
            }
            else{
                header('location:iut.php?erreur=Ajout impossible : doublons.');
            }

        }



    }



if (!isset($_GET['page'])) {
  include 'header.php';
    //Début du rendu HTML
    ?>
    <div id="content">
    <center>
      <?php if(mysqli_num_rows($result) > 0){ ?>
      <input type="hidden" id="ouSuisJe" value="iut">
      <form id="form_suppression" action="" method="POST"  >
        <table class="tableau">
        <caption id="bj">Tableau des IUT</caption>
        <thead>
         <tr>
            <th onclick='javascript:location.href = "iut.php?order=nom<?php if (isset($_GET['order']) && $_GET['order'] == 'nom') {echo "D";} ?>" ' class="thOrder">Nom <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'nom') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'nomD') {echo "<img src='img/haut.png'>";} } ?></th>
            <th onclick='javascript:location.href = "iut.php?order=ad<?php if (isset($_GET['order']) && $_GET['order'] == 'ad') {echo "D";} ?>" ' class="thOrder">Adresse <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'ad') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'adD') {echo "<img src='img/haut.png'>";} } ?> </th>
            <th onclick='javascript:location.href = "iut.php?order=nb<?php if (isset($_GET['order']) && $_GET['order'] == 'nb') {echo "D";} ?>" ' class="thOrder">NB Etudiant <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'nb') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'nbD') {echo "<img src='img/haut.png'>";} } ?></th>
            <th>Action</th>
         </tr>
        </thead>
        <tbody>
          <?php $j=0;
          while( $row = mysqli_fetch_array($result)){ ?>
         <tr id="<?php echo "ligne".$row["noIut"] ?>">
            <td><?php echo $row["nomIut"];?></td> 
            <td><?php echo $row["adresse"];?></td> 
            <td><?php echo $row["nbEtudiants"];?></td>
            <td class="imgAction">
                  <a href="javascript:modifierIut(<?php echo $row["noIut"]; ?>,'<?php echo $row["nomIut"]; ?>','<?php echo $row["adresse"]; ?>',<?php echo $row["nbEtudiants"]; ?>)" title="Modifier"><img src="img/Icon_Update.png"></a>
                  <a href="javascript:supprimerIut(<?php echo $row["noIut"]; ?>)" title="Suprimer"><img src="img/Icon_Delete.png"></a>
                  <input type="hidden" name="noIut" value="<?php echo $row["noIut"];?>" />
              </td>
          </tr>       
        <?php
        }
        ?>
        <tr>
            <td><input type="text" class="inputForm" id="nomIutAjout" value='' placeholder="Nom"></td> 
            <td><input type="text" class="inputForm" id="adresseAjout" value='' placeholder="Adresse"></td> 
            <td><input type="text" class="inputForm" onkeyup="testInt(this.id)" id="nbEtudiantsAjout" value='' placeholder="Nombre d'etudiant"></td>
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:AjouterIut()"><img src="img/Icon_valider_ajout.png"></a>
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
                <a href='iut.php?startIndex=<?php echo $startIndexPrew; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Precedent</a>
            <?php }
                else{?>
                <span class="disable">Precedent</span>

               <?php }

            for ($i=0; $i < $nbPage; $i++) {?>
                <a href='iut.php?startIndex=<?php echo (int)($i*10) ?>
                <?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'><?php echo $i+1; ?></a>
            <?php } 

            if ($startIndexNext < $nbRow['nbLigne']) {?>
                <a href='iut.php?startIndex=<?php echo $startIndexNext; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Suivant</a>           
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