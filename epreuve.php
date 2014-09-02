<?php 
include 'config.php';
//Récupération des informations dans la base de données

$nbRowResult = mysqli_query($link,'SELECT COUNT(numEpreuve) AS nbLigne FROM epreuve') or die ('Unable to execute query. '. mysqli_error($link));
$nbRow = mysqli_fetch_array($nbRowResult);
$nbPage = ceil($nbRow['nbLigne'] / 10);

//Récupération des informations dans la base de données
$query = "SELECT numEpreuve, intitule FROM epreuve ";

if (isset($_GET['order']) && $_GET['order'] == 'nom') {
    $query = $query."ORDER BY intitule "; $order="nom";
}
if (isset($_GET['order']) && $_GET['order'] == 'nomD') {
    $query = $query."ORDER BY intitule DESC "; $order="nomD";
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
//echo $query;

$result = mysqli_query($link,$query) or die ('Unable to execute query. '. mysqli_error($link));
//appelé par ajax
if (isset($_GET['page']) && $_GET['page'] == 'epreuve') {
    
        if(isset($_GET['action']) && $_GET['action'] == 'modifier' ){
            /*print('<pre>');
            print_r($_POST);
            print('</pre>');*/
            //header('Content-Type: text/html; charset=utf8_unicode_ci');
            ?>
            <form id="formModif" name="FormModif" action="epreuve.php?valider=valider" method="POST">
            <input type="hidden" id="ID" value="<?php echo $_POST['numEpreuve']; ?>">
            <td><input type="text" class="inputForm" id="intitule"value='<?php echo $_POST["intitule"];?>'></td> 
            
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:ValiderEpr()"><img src="img/Icon_valider.png"></a>
                <a href="epreuve.php"><img src="img/Icon_annuler.png"></a>
            </td>
            </form>
            <script type="text/javascript">
             $(document).ready(function(){
                  $(document).keydown(function(event){
                    if (event.which == 13) {
                        ValiderEpr();
                    }
                    if (event.which == 27) {
                        document.location.href="epreuve.php";
                    }
                    //$("#test").html("Key: " + event.which);
                  });
                });
            </script>
        
        <?php 
        }
        //-----modifier-----
        if(isset($_GET['valider']) && $_GET['valider'] == 'valider'){
            $verif = "SELECT count(*) AS nbl FROM epreuve WHERE intitule='".$_POST['intitule']."';";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {
                $rqt = 'UPDATE epreuve set intitule="'.$_POST['intitule'].'" WHERE numEpreuve='.$_POST['numEpreuve'].'; ';
                echo $rqt;
                $update_result = mysqli_query( $link , $rqt );
                header('location:epreuve.php');
            }
            else{
                header('location:epreuve.php?erreur=Modification impossible : doublons.');
            }
        }
        //------suprimer------
        if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
            /*Si la valeur de conf == vrai alors on supprime tout*/
            if(isset($_GET['conf']) && $_GET['conf'] == 'vrai'){
                $rqt2 = 'DELETE FROM epreuve WHERE numEpreuve='.$_POST['numEpreuve'].'; ';
                echo $rqt2;
                $delete_result = mysqli_query( $link , $rqt2 ) or die ('Unable to execute query. '. mysqli_error($link));
            }
            header('location:epreuve.php');
            
        }
        //------suprimer multiple------
        if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
            /*Si la valeur de conf == vrai alors on supprime tout*/
            if(isset($_GET['conf']) && $_GET['conf'] == 'vrai'){
                $id=explode('#', $_POST['ID']);
                foreach ($id as $key) {
                    $rqt2 = 'DELETE FROM epreuve WHERE numEpreuve='.$key.'; ';
                    echo $rqt2;
                    $delete_result = mysqli_query( $link , $rqt2 ) or die ('Unable to execute query. '. mysqli_error($link));                    
                }
            }
            header('location:epreuve.php');
            
        }
        //-------ajouter------
        if (isset($_GET['action']) && $_GET['action'] == 'ajouter') {
            $verif = "SELECT count(*) AS nbl FROM epreuve WHERE intitule='".$_POST['intitule']."';";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {
                $rqt_ajout='INSERT INTO epreuve VALUES (NULL,"'.$_POST['intitule'].'")';
                $res_ajout = mysqli_query($link , $rqt_ajout);
                header('location:epreuve.php');
            }
            else{
                header('location:epreuve.php?erreur=Ajout impossible : doublons.');
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
      <input type="hidden" id="ouSuisJe" value="epreuve">
      <?php if(mysqli_num_rows($result) > 0){ ?>
        <form id="form_suppression" action="" method="POST"  >
        <table class="tableau">
        <caption id="bj">Tableau des Epreuves</caption>
        <thead>
         <tr>
            <th onclick='javascript:location.href = "epreuve.php?order=nom<?php if (isset($_GET['order']) && $_GET['order'] == 'nom') {echo "D";} ?>" ' class="thOrder">Intitule <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'nom') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'nomD') {echo "<img src='img/haut.png'>";} } ?></th>
            <th>Action</th>
         </tr>
        </thead>
        <tbody>
        
          <?php $j=0;
          while( $row = mysqli_fetch_array($result)){ 
            /*print('<pre>');
            print_r($row);
            print('</pre>');*/?>
           <tr id="<?php echo "ligne".$row["numEpreuve"] ?>">
            <td><?php echo $row["intitule"];?></td> 
            <td class="imgAction">                
                <a href="javascript:modifierEpr(<?php echo $row['numEpreuve'] ;?>,'<?php echo $row['intitule'];?>')" title="Modifier"><img src="img/Icon_Update.png"></a>
                <a href="javascript:supprimerEpr(<?php echo $row['numEpreuve'] ;?>)" title="Supprimer" onclick="confirmer()" ><img src="img/Icon_Delete.png"></a>
            </td>
          </tr>    
          
        <?php
        }
        ?>
        <tr>
            <form id="formModif" name="FormModif" action="epreuve.php?valider=valider" method="POST">
            <td><input type="text" class="inputForm" id="intituleAjout"value='' onkeyup="testString(this.id)" placeholder="Intitule"></td> 
    
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:AjouterEpr()"><img src="img/Icon_valider_ajout.png"></a>
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
                <a href='epreuve.php?startIndex=<?php echo $startIndexPrew; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Precedent</a>
            <?php }
                else{?>
                <span class="disable">Precedent</span>

               <?php }

            for ($i=0; $i < $nbPage; $i++) {?>
                <a href='epreuve.php?startIndex=<?php echo (int)($i*10) ?>
                <?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'><?php echo $i+1; ?></a>
            <?php } 

            if ($startIndexNext < $nbRow['nbLigne']) {?>
                <a href='epreuve.php?startIndex=<?php echo $startIndexNext; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Suivant</a>           
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




