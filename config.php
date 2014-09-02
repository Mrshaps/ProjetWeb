<?php 

function wd_remove_accents($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
}


define("DBTYPE", "MySQL"); 
define("HOST", "localhost"); 
define("USER", "root"); 
define("PASS", "root"); 
define("BASE", "ProjetWeb");

$link = mysqli_connect(HOST,USER,PASS,BASE);
	if(!$link){
		die('Problème de connexion au serveur. \n');
	}

?>