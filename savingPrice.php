<?php
	include("connect.php");

	$selectpriceRef = $connexion->query('SELECT * FROM prestations_assuref');
	$selectpriceRef->setFetchMode(PDO::FETCH_OBJ);
	$row = $selectpriceRef->rowCount();
	/*echo "row = ".$row;

	$selectpriceRef1 = $connexion->query('SELECT * FROM prestations_assuref WHERE statupresta=0');
	$selectpriceRef1->setFetchMode(PDO::FETCH_OBJ);
	$row1 = $selectpriceRef1->rowCount();
	echo "<br>row = ".$row1;*/
	while ($lignepriceRef = $selectpriceRef->fetch()) {

		$nompresta = $lignepriceRef->nompresta;
		$namepresta = $lignepriceRef->namepresta;
		$id_categopresta = $lignepriceRef->id_categopresta;

		$newprix = $lignepriceRef->prixpresta;
		$newstatus = $lignepriceRef->statupresta;

		/*echo "<br>----------------------------------------<br>";
		echo "<br>nompresta ref = ".$nompresta;
		echo "<br>namepresta ref = ".$namepresta;
		echo "<br>id_categopresta ref = ".$id_categopresta;
		echo "----------------------------------------<br><br>";*/


		$comptAssuConsu=$connexion->query('SELECT *FROM assurances a WHERE id_assurance!=1 AND id_assurance!=5 ORDER BY a.id_assurance');
			
		$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
				
		$assuCount = $comptAssuConsu->rowCount();
		
		for($i=1;$i<=$assuCount;$i++)
		{
			$ligneNomAssu = $comptAssuConsu->fetch();
			$presta_assuConsu='prestations_'.$ligneNomAssu->nomassurance;

			$replaceprice = $connexion->query('SELECT * FROM '.$presta_assuConsu.' WHERE nompresta IN ("'.$nompresta.'") AND id_categopresta='.$id_categopresta.'');
			$replaceprice->setFetchMode(PDO::FETCH_OBJ);
			$rowrep = $replaceprice->rowCount();
			//echo '<br>'.$ligneNomAssu->nomassurance."-------------<br>";
			while ($lignereplace = $replaceprice->fetch()) {
				/*echo "<br>nompresta ref = ".$lignereplace->nompresta;
				echo "<br>namepresta ref = ".$lignereplace->namepresta;
				echo "<br>id_categopresta ref = ".$lignereplace->id_categopresta;
				echo "<br>----------------<br>";*/
				$id_prestationUpdate =$lignereplace->id_prestation;

				$update = $connexion->query('UPDATE '.$presta_assuConsu.' SET prixpresta='.$newprix.', statupresta='.$newstatus.' WHERE id_prestation='.$id_prestationUpdate.'');
			}
			//echo "rowrep = ".$rowrep."<br>";
		}
	}
?>