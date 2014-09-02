<?php
include 'config.php';

if(isset($_POST['liste_requete']) && $_POST['liste_requete'] == 'r1'){

	$res = mysqli_query($link,"SELECT intitule FROM epreuve ");
	?>
	<br/>
	<p>Liste des épreuves (affichage : intitulé)</p>
	<br/>
	<table class="tableau">
		<tr>
			<td>Intitule</td>
		</tr>
		<?php $row = mysqli_fetch_array($res);
		 while($row){?>
		<tr>
	  		<td><?php echo $row['intitule'];?></td>
	  	</tr>
	  	<?php
		}
print('</table>');
//mysql_close();
}



if(isset($_POST['liste_requete']) && $_POST['liste_requete'] == 'r2'){

	$res = mysqli_query($link,"SELECT nom, age, sexe FROM etudiant ");
	?>
	<br/>
	<p>Liste des étudiants (affichage : nom, âge, sexe)</p>
	<br/>
	<table class="tableau">
		<tr>
			<td>Nom</td>
			<td>Age</td>
			<td>Sexe</td>
		</tr>
		<?php while($row = mysqli_fetch_array($res)){?>
		<tr>
	  		<td><?php echo $row['nom'];?></td>
	  		<td><?php echo $row['age'];?></td>
	  		<td><?php echo $row['sexe'];?></td>
	  	</tr>
	  	<?php
		}
print('</table>');
printf('<span><br/></span>');
//mysql_close();
}


if(isset($_POST['liste_requete']) && $_POST['liste_requete'] == 'r3'){

	$res = mysqli_query($link,"SELECT numMan, nomMan,dateMan, noIut FROM manifestation WHERE dateMAn > '1999-04-12' ");
	?>
	<br/>
	<p>Liste des manifestations ayant lieu apres le 12/04/99</p>
	<br/>
	<table class="tableau">
		<tr>
			<td>N°</td>
			<td>Nom</td>
			<td>Date</td>
			<td>N° IUT</td>
		</tr>
		<?php while($row = mysqli_fetch_array($res)){?>
		<tr>
	  		<td><?php echo $row['numMan'];?></td>
	  		<td><?php echo $row['nomMan'];?></td>
	  		<td><?php echo $row['dateMan'];?></td>
	  		<td><?php echo $row['noIut'];?></td>
	  	</tr>
	  	<?php
		}
print('</table>');
//mysql_close();
}



if(isset($_POST['liste_requete']) && $_POST['liste_requete'] == 'r4'){

	$res = mysqli_query($link,"SELECT count(*) FROM etudiant e 
								INNER JOIN iut i ON e.noIut = i.noIut WHERE  i.nomIut='Belfort'");
	?>
	<br/>
	<p>Nombre d'étudiants de l'iut de Belfort</p>
	<br/>
	<table class="tableau">
		<tr>
			<td>Nombre d'etudiant</td>
		</tr>
		<?php while($row = mysqli_fetch_array($res)){?>
		<tr>
	  		<td><?php echo $row[0];?></td>
	  	</tr>
	  	<?php
		}
print('</table>');
//mysql_close();
}


if(isset($_POST['liste_requete']) && $_POST['liste_requete'] == 'r5'){

	$res = mysqli_query($link,"SELECT e.noEtudiant, e.nom, e.prenom,e.age, e.sexe FROM etudiant e 
								INNER JOIN iut i ON e.noIut = i.noIut WHERE  i.nomIut='Belfort'");
	?>
	<br/>
	<p>Liste des étudiant de l'iut de "Belfort"</p>
	<br/>
	<table class="tableau">
		<tr>
			<td>N°</td>
			<td>Nom</td>
			<td>Prenom</td>
			<td>Age</td>
			<td>Sexe</td>
		</tr>
		<?php while($row = mysqli_fetch_array($res)){?>
		<tr>
	  		<td><?php echo $row['noEtudiant'];?></td>
	  		<td><?php echo $row['nom'];?></td>
	  		<td><?php echo $row['prenom'];?></td>
	  		<td><?php echo $row['age'];?></td>
	  		<td><?php echo $row['sexe'];?></td>
	  	</tr>
	  	<?php
		}
print('</table>');
//mysql_close();
}


if(isset($_POST['liste_requete']) && $_POST['liste_requete'] == 'r6'){

	$res = mysqli_query($link,"SELECT nom, prenom FROM etudiant WHERE age in (SELECT age FROM etudiant WHERE nom = 'toto')");
	?>
	<br/>
	<p>Liste des étudiant ayant le même âge que toto</p>
	<br/>
	<table class="tableau">
		<tr>
			<td>Nom</td>
			<td>Prenom</td>
		</tr>
		<?php while($row = mysqli_fetch_array($res)){?>
		<tr>
	  		<td><?php echo $row['nom'];?></td>
	  		<td><?php echo $row['prenom'];?></td>
	  	</tr>
	  	<?php
		}
print('</table>');
//mysql_close();
}

if(isset($_POST['liste_requete']) && $_POST['liste_requete'] == 'r7'){

	$res = mysqli_query($link,"SELECT m.nomMan, count(*) FROM contenu c, manifestation m
           WHERE m.numMan=c.numMan GROUP BY m.nomMan");
	?>
	<br/>
	<p>Nombre d'épreuves par manifestations</p>
	<br/>
	<table class="tableau">
		<tr>
			<td>Nom</td>
			<td>Nb epreuves</td>
		</tr>
		<?php while($row = mysqli_fetch_array($res)){?>
		<tr>
	  		<td><?php echo $row['nomMan'];?></td>
	  		<td><?php echo $row[1];?></td>
	  	</tr>
	  	<?php
		}
print('</table>');
//mysql_close();
}

if(isset($_POST['liste_requete']) && $_POST['liste_requete'] == 'r8'){

	$res = mysqli_query($link,"SELECT m.nomMan, i.nomIut, count(DISTINCT e.noEtudiant) AS nbEtudiants FROM participe p, etudiant e, manifestation m, iut i 
         where e.noEtudiant = p.noEtudiant 
         and p.numMan = m.numMan 
         and e.noIut = i.noIut 
         group by p.numMan, i.noIut;");
	?>
	<br/>
	<p>Nombre d'étudiants par IUT ayant participé à une manifestation</p>
	<br/>
	<table class="tableau">
		<tr>
			<td>Manifestation</td>
			<td>IUT</td>
			<td>Nb etudiants</td>
		</tr>
		<?php while($row = mysqli_fetch_array($res)){?>
		<tr>
	  		<td><?php echo $row['nomMan'];?></td>
	  		<td><?php echo $row['nomIut'];?></td>
	  		<td><?php echo $row['nbEtudiants'];?></td>
	  	</tr>
	  	<?php
		}
print('</table>');
//mysql_close();
}

if(isset($_POST['liste_requete']) && $_POST['liste_requete'] == 'r9'){

	$res = mysqli_query($link,"SELECT m.nomMan FROM manifestation m, participe p, etudiant e
        						WHERE e.nom='toto'
        						AND e.noEtudiant = p.noEtudiant
        						AND m.numMan = p.numMan") or die ('Unable to execute query. '. mysqli_error($link));
	?> 
	<br/>
	<p>Liste des manifestations auxquelles l'étudiant "toto" à participé.</p>
	<br/>
	<table class="tableau">
		<tr>
			<td>Nom</td>
		</tr>
		<?php while($row = mysqli_fetch_array($res)){?>
		<tr>
	  		<td><?php echo $row['nomMan'];?></td>
	  	</tr>
	  	<?php
		}
print('</table>');
//mysql_close();
}
?>