<?php
	session_start();
	include("connect.php");
	include("connectLangues.php");
	include("serialNumber.php");

	$excel = $connexion->query('SELECT * FROM Sheet1');
	$excel->setFetchMode(PDO::FETCH_OBJ);

	while ($LigneExcel = $excel->fetch()) {
		$insertExcel = $connexion->prepare("INSERT INTO prestations_itm(nompresta, namepresta, prixpresta, prixprestaCCO, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES(:nompresta, :namepresta, :prixpresta, :prixprestaCCO, :id_categopresta, :id_souscategopresta, :mesure, :statupresta) ");
		$insertExcel->execute(array(
			'nompresta'=>$LigneExcel->nompresta,
			'namepresta'=>$LigneExcel->nompresta,
			'prixpresta'=>$LigneExcel->prixpresta,
			'prixprestaCCO'=>$LigneExcel->prixpresta,
			'id_categopresta'=>$LigneExcel->id_categopresta,
			'id_souscategopresta'=>$LigneExcel->id_souscategopresta,
			'mesure'=>$LigneExcel->mesure,
			'statupresta'=>$LigneExcel->statupresta,
		));
	}

?>

