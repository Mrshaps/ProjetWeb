<?php 
	include 'config.php';

	// print('<pre>');
	// print_r($_POST);
	// print('</pre>');

	if (isset($_GET['action']) && $_GET['action'] == 'infoClient') {
		$rqt = "SELECT C.civilite, C.nom, C.prenom, C.adresse, C.codePostal, C.ville, Ca.carte, Ca.remise FROM clients C 
				INNER JOIN carte Ca ON Ca.codeCarte = C.codeCarte
				WHERE codeClient=".$_POST['codeClients'];
		//echo $rqt;
		$retrqt = mysqli_query($link, $rqt) or die ('Unable to execute query. '. mysqli_error($link));
		$info = mysqli_fetch_assoc($retrqt);
		echo json_encode($info);
		//echo $info['civilite'].",".$info['nom'].",".$info['prenom'].",".$info['adresse'].",".$info['codePostal'].",".$info['ville'].",".$info['carte'].",".$info['remise'];
	}

	if (isset($_GET['action']) && $_GET['action'] == 'newLigne') {
		$rqt = "SELECT codeService, prestation, cout FROM service";
		//echo $rqt;
		$retrqt = mysqli_query($link, $rqt) or die ('Unable to execute query. '. mysqli_error($link));
		
		while ($info = mysqli_fetch_assoc($retrqt)) {
			$liste[] = array(
				'codeService' => $info['codeService'], 
				'prestation' => $info['prestation'], 
				'cout' => $info['cout'], 
				);
		}
		echo json_encode($liste);
	}

	if (isset($_GET['action']) && $_GET['action'] == 'InfoLigne') {
		/*print('<pre>');
		print_r($_POST);
		print('</pre>');*/
		$rqt = "SELECT codeService, prestation, cout FROM service WHERE codeService =".$_POST['codeServices'];
		//echo $rqt;
		$retrqt = mysqli_query($link, $rqt) or die ('Unable to execute query. '. mysqli_error($link));
		$info = mysqli_fetch_assoc($retrqt);
		echo json_encode($info);
		
	}

 ?>