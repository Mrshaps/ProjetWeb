<?php 
$xml = new SimpleXMLElement('<xml/>');

	include 'config.php';

	// print('<pre>');
	// print_r($_POST);
	// print('</pre>');
	$root = $xml->addChild('root');

	if (isset($_GET['action']) && $_GET['action'] == 'infoClient') {
		$rqt = "SELECT C.civilite, C.nom, C.prenom, C.adresse, C.codePostal, C.ville, Ca.carte, Ca.remise FROM clients C 
				INNER JOIN carte Ca ON Ca.codeCarte = C.codeCarte
				WHERE codeClient=".$_POST['codeClients'];
		//echo $rqt;
		$retrqt = mysqli_query($link, $rqt) or die ('Unable to execute query. '. mysqli_error($link));
		$info = mysqli_fetch_assoc($retrqt);
    	$root->addChild('civilite', $info['civilite']);
    	$root->addChild('nom', $info['nom']);
    	$root->addChild('prenom', $info['prenom']);
    	$root->addChild('adresse', $info['adresse']);
    	$root->addChild('codePostal', $info['codePostal']);
    	$root->addChild('ville', $info['ville']);
    	$root->addChild('carte', $info['carte']);
    	$root->addChild('remise', $info['remise']);
	}

	if (isset($_GET['action']) && $_GET['action'] == 'newLigne') {
		$rqt = "SELECT codeService, prestation, cout FROM service";
		//echo $rqt;
		$retrqt = mysqli_query($link, $rqt) or die ('Unable to execute query. '. mysqli_error($link));
		

		while ($info = mysqli_fetch_assoc($retrqt)) {
			$donne = $root->addChild('donne');
			$donne->addChild('codeService',$info['codeService']);
			$donne->addChild('prestation',$info['prestation']);
			$donne->addChild('cout',$info['cout']);
			//echo "#".$info['codeService']."@".$info['prestation']."@".$info['cout'];
		}
	}

	if (isset($_GET['action']) && $_GET['action'] == 'InfoLigne') {
		// print('<pre>');
		// print_r($_POST);
		// print('</pre>');
		$rqt = "SELECT codeService, prestation, cout FROM service WHERE codeService =".$_POST['codeServices'];
		//echo $rqt;
		$retrqt = mysqli_query($link, $rqt) or die ('Unable to execute query. '. mysqli_error($link));
		

		$info = mysqli_fetch_assoc($retrqt);
			$donne = $root->addChild('donne');
			$donne->addChild('codeService',$info['codeService']);
			$donne->addChild('prestation',$info['prestation']);
			$donne->addChild('cout',$info['cout']);
			//echo "@".$info['codeService']."@".$info['prestation']."@".$info['cout'];
	}
	

	header("Content-type: text/xml");
	print($xml->asXML());


 ?>


