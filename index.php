<?php 
	include 'header.php';
?>
	
	<div id="content">
		Votre code client 
		<input type="text" value="" id="inputIdClient">
		<input type="button" value="OK" onclick="infoClient()">
	</div>
	<div id="info" ></div>
	<div id="tableau" style="visibility:hidden">
		<input type="button" value="Ajouter une ligne" onclick="AjoutLigne()">
		<input type="button" value="Supprimer une ligne" onclick="SupprimerLigne()">
		<table id="table">
			<thead><tr><td>Code prestation</td><td>Designation</td><td>Prix</td><td>Quantite</td><td>Montant</td></tr></thead>
			<tbody id="tbodyId"></tbody>
			<tfoot>
				<tr>
					<td colspan="3" rowspan="3" id="tdCarte">Votre carte est .</td>
					<td>Somme</td><td id="Somme"></td>
				</tr>
				<tr>
					<td>Remise</td><td id="tdRemise"></td>
				</tr>
				<tr>
					<td>A regler</td><td></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div id="error"></div>
<?php 
	include 'footer.php';
?>



