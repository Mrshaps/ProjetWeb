<?php 
include 'config.php';

$nbRowResult = mysqli_query($link,'SELECT COUNT(numMan) AS nbLigne FROM contenu') or die ('Unable to execute query. '. mysqli_error($link));
$nbRow = mysqli_fetch_array($nbRowResult);
$nbPage = ceil($nbRow['nbLigne'] / 10);

//Récupération des informations dans la base de données
$query = "SELECT ma.numMan ,ma.nomMan,ep.numEpreuve, ep.intitule 
            FROM contenu c
            INNER JOIN epreuve ep ON c.numEpreuve = ep.numEpreuve
            INNER JOIN manifestation ma ON c.numMan = ma.numMan ";

if (isset($_GET['order']) && $_GET['order'] == 'nom') {
    $query = $query."ORDER BY nomMan "; $order="nom";
}
if (isset($_GET['order']) && $_GET['order'] == 'nomD') {
    $query = $query."ORDER BY nomMan DESC "; $order="nomD";
}
if (isset($_GET['order']) && $_GET['order'] == 'ep') {
    $query = $query."ORDER BY intitule "; $order="ep";
}
if (isset($_GET['order']) && $_GET['order'] == 'epD') {
    $query = $query."ORDER BY intitule DESC "; $order="epD";
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
if (isset($_GET['page']) && $_GET['page'] == 'contenu') {
    
        if(isset($_GET['action']) && $_GET['action'] == 'modifier' ){
            /*print('<pre>');
            print_r($_POST);
            print('</pre>');*/
            

            ?>
            <form id="formModif" name="FormModif" action="contenu.php?valider=valider" method="POST">

            <input type="hidden" id="ID1" value="<?php echo $_POST['numMan']; ?>">
            <input type="hidden" id="ID2" value="<?php echo $_POST['numEpreuve']; ?>">

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
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:ValiderCont()"><img src="img/Icon_valider.png"></a>
                <a href="contenu.php"><img src="img/Icon_annuler.png"></a>
            </td>
            </form>
        
        <?php 
        }
        //-----modifier-----
        if(isset($_GET['valider']) && $_GET['valider'] == 'valider'){
            //Vérification de doublon
            $verif = "SELECT count(*) AS nbl FROM contenu WHERE numMan=".$_POST['numMan']."AND numEpreuve=".$_POST['numEpreuve'].";";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {
                $rqt = "UPDATE contenu SET numMan=".$_POST['numMan'].", numEpreuve=".$_POST['numEpreuve']." 
                WHERE numMan=".$_POST['old_numMan']." AND numEpreuve=".$_POST['old_numEpreuve'].";";
                echo $rqt;
                $update_result = mysqli_query( $link , $rqt );
                header('location:contenu.php');
            }
            else{
                header('location:contenu.php?erreur=Modification impossible : doublons.');
            }
        } 
        //------supprimer------
        if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
            /*Si la valeur de conf == vrai alors on supprime tout*/
            $rqt1 = 'DELETE FROM contenu WHERE numMan='.$_POST['numMan'].' AND numEpreuve='.$_POST['numEpreuve'].'; ';
            //echo $rqt1;
            $delete_result = mysqli_query( $link , $rqt1 ) or die ('Unable to execute query. '. mysqli_error($link));
            header('location:contenu.php');
        }
        //------ajouter------
        if (isset($_GET['action']) && $_GET['action'] == 'ajouter') {
            //Vérification de doublon
            $verif = "SELECT count(*) AS nbl FROM contenu WHERE numMan=".$_POST['numMan']." AND numEpreuve=".$_POST['numEpreuve'].";";
            $res_verif = mysqli_query($link , $verif);
            $row_verif = mysqli_fetch_array($res_verif);
            echo $row_verif['nbl'];
            if($row_verif['nbl'] == 0)
            {
                $rqt_ajout='INSERT INTO contenu VALUES ('.$_POST['numMan'].', '.$_POST['numEpreuve'].')';
                echo $rqt_ajout;
                $res_ajout = mysqli_query($link , $rqt_ajout);
                header('location:contenu.php');
            }
            else{
                header('location:contenu.php?erreur=Ajout impossible : doublons.');
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
      <input type="hidden" id="ouSuisJe" value="contenu">
    	<?php if(mysqli_num_rows($result) > 0){ ?>
        <form id="form_suppression" action="" method="POST"  >
    		<table class="tableau">
    		<caption id="bj">Tableau des Contenances</caption>
    		<thead>
     		 <tr>
       			<th onclick='javascript:location.href = "contenu.php?order=nom<?php if (isset($_GET['order']) && $_GET['order'] == 'nom') {echo "D";} ?>" ' class="thOrder">Manifestation <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'nom') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'nomD') {echo "<img src='img/haut.png'>";} } ?></th>
        		<th onclick='javascript:location.href = "contenu.php?order=ep<?php if (isset($_GET['order']) && $_GET['order'] == 'ep') {echo "D";} ?>" ' class="thOrder">Epreuve <?php if (isset($_GET['order'])){ if ($_GET['order'] == 'ep') {echo "<img src='img/bas.png'>";} if ($_GET['order'] == 'epD') {echo "<img src='img/haut.png'>";} } ?></th>
                <th>Action</th>
     		 </tr>
    		</thead>
        <tbody>
        
          <?php while( $row = mysqli_fetch_array($result)){ 
            /*print('<pre>');
            print_r($row);
            print('</pre>');*/?>
         <tr id="<?php echo "ligne".$row["numMan"].$row["numEpreuve"]; ?>">
            <td><?php echo $row["nomMan"];?></td> 
            <td><?php echo $row["intitule"];?></td> 
            <td class="imgAction">
                
                
                <a href="javascript:modifierCont(<?php echo $row['numMan'] ;?>,<?php echo $row['numEpreuve'];?>)" title="Modifier"><img src="img/Icon_Update.png"></a>
                <a href="javascript:supprimerCont(<?php echo $row['numMan']; ?>,<?php echo $row['numEpreuve'];?>)" title="Suprimer"><img src="img/Icon_Delete.png"></a>
            </td>
          </tr>   
          
        <?php
        }
        ?>
        <tr>
            <td>
                <select id="numManAjout">
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
                <select id="numEpreuveAjout">
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
            <td class="imgAction">
                <?php $post=implode(",",$_POST) ?>
                <a href="javascript:AjouterCont()"><img src="img/Icon_valider_ajout.png"></a>
            </td>
        </tr>
        </tbody>
        </form>
        </table>
        <span id="erreur">
            <?php if (isset($_GET['erreur'])) {
                echo $_GET['erreur']."<br/><br/>";
            } ?>
        </span>
        <span class="tablePage">
            <?php if (isset($_GET['startIndex']) && $_GET['startIndex'] != 0) {?>
                <a href='contenu.php?startIndex=<?php echo $startIndexPrew; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Precedent</a>
            <?php }
                else{?>
                <span class="disable">Precedent</span>

               <?php }

            for ($i=0; $i < $nbPage; $i++) {?>
                <a href='contenu.php?startIndex=<?php echo (int)($i*10) ?>
                <?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'><?php echo $i+1; ?></a>
            <?php } 

            if ($startIndexNext < $nbRow['nbLigne']) {?>
                <a href='contenu.php?startIndex=<?php echo $startIndexNext; ?><?php if (isset($_GET['order'])){echo "&order=".$order;} ?>'>Suivant</a>           
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