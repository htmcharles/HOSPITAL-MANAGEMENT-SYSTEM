<?php

	if(isset($_POST['addNursery']))
	{
		// echo $_POST['soins'];
		
		$idconsuNext = $_GET['idconsuNext'];
		$dateconsu = $_GET['dateconsu'];
		$numero = $_GET['num'];
		$id_uM = $_GET['idMed'];
		$soinsfait=0;
		
		if($_POST['soins'] != 'autresoins')
		{
			$idPresta=$_POST['soins'];
			
			if($idPresta != 0)
			{
				// echo $idPresta.','.$soinsfait.','.$dateconsu.','.$id_uM.','.$numero;
				
				$resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,soinsfait,numero,id_uM,id_consuInf) VALUES(:dateconsu,:idPresta,:soinsfait,:numero,:id_uM,:idconsuNext)');
				$resultat->execute(array(
				'dateconsu'=>nl2br($dateconsu),
				'idPresta'=>nl2br($idPresta),
				'soinsfait'=>nl2br($soinsfait),					
				'numero'=>nl2br($numero),
				'id_uM'=>nl2br($id_uM),
				'idconsuNext'=>nl2br($idconsuNext)
				)) or die( print_r($connexion->errorInfo()));
				
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsuNext='.$idconsuNext.'";</script>';
			
			}else{
				echo 'ok';
			
			}
					
		}else{
		
			if(isset($_POST['areaAutresoins']))
			{
				$idAutrePresta=$_POST['areaAutresoins'];
			

				if($idAutrePresta != "")
				{
					// echo $idAutrePresta.','.$soinsfait.','.$dateconsu.','.$id_uM.','.$numero;	
					
					$resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,soinsfait,autrePrestaM,numero,id_uM,id_consuInf) VALUES(:dateconsu,:soinsfait,:autrePrestaM,:numero,:id_uM,:idconsuNext)');
					$resultat->execute(array(
					'dateconsu'=>nl2br($dateconsu),
					'soinsfait'=>nl2br($soinsfait),
					'autrePrestaM'=>nl2br($idAutrePresta),
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuNext'=>nl2br($idconsuNext)
					)) or die( print_r($connexion->errorInfo()));
					
					echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsuNext='.$idconsuNext.'";</script>';
				}
			}
		}
		
	}

?>