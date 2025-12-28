<?php
session_start();
include("connect.php");

try
{
	$heure=date("H");
	$min=date("i");


	$heureToday=$heure.':'.$min;

	$annee = date('Y').'-'.date('m').'-'.date('d');

	//-------Creation fiche par le medecin
	
	if(isset($_GET['idassuOrtho']))
	{
		$idassu=$_GET['idassuOrtho'];
										
		$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
		
		$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
				
		$assuCount = $comptAssuConsu->rowCount();
		
		for($a=1;$a<=$assuCount;$a++)
		{
			
			$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
			$getAssuConsu->execute(array(
			'idassu'=>$idassu
			));
			
			$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

			if($ligneNomAssu=$getAssuConsu->fetch())
			{
				$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
			}
		}

		
	}
	
	
	if(isset($_GET['num']))
	{
		$resultats=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultats->execute(array(
		'operation'=>$_GET['num']	
		));
		
		$resultats->setFetchMode(PDO::FETCH_OBJ);
		while($ligne=$resultats->fetch())
		{
			$num=$ligne->numero;
			$bill=$ligne->bill;
			$idP=$ligne->id_u;
		}
		$resultats->closeCursor();

	}				
				
	$numero=$_GET['num'];
	$idmedOrtho=$_GET['idmedOrtho'];
	$idconsuAdd=$_GET['idconsuOrtho'];
	$id_uO=$_SESSION['id'];
	$id_uM=$_GET['iduM'];
	$presta=$_GET['presta'];
	$orthoPa=$_GET['orthoPa'];
	$dateconsu=$_GET['dateconsu'];
	
 	if(isset($_POST['savebtn']))
	{
		$updateOrthoFait=$connexion->prepare("UPDATE med_ortho SET orthofait=2, id_uM=:id_uM WHERE id_consuOrtho =:idconsuAdd");			
		$updateOrthoFait->execute(array(
		'idconsuAdd'=>$idconsuAdd,
		'id_uM'=>$id_uM
		))or die( print_r($connexion->errorInfo()));
		
		// echo "UPDATE med_ortho SET orthofait = '2', id_uM=".$id_uM." WHERE id_consuOrtho=".$idconsuAdd."";
		
		$updateDateOrtho=$connexion->prepare("UPDATE med_ortho SET dateortho=:dateortho, id_uO=:id_uO WHERE id_consuOrtho =:idconsuAdd AND dateortho ='0000-00-00'");			
		$updateDateOrtho->execute(array(
		'dateortho'=>$annee,
		'id_uO'=>$id_uO,
		'idconsuAdd'=>$idconsuAdd
		))or die( print_r($connexion->errorInfo()));
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?iduser='.$id_uO.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?iduser='.$id_uO.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?iduser='.$id_uO.'";</script>';
			}
		}
	}
	
	
	if(isset($_POST['addOrtho']) or isset($_POST['addAutreOrtho']))
	{
		$orthofait=1;
			
		if(isset($_POST['addOrtho']))
		{
			$idPresta = array();

			foreach($_POST['ortho'] as $valueOrtho)
			{
				$idPresta[] = $valueOrtho;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$orthofait.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestaortho=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestaortho->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestaortho->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestaortho=$prixprestaortho->rowCount();
				
				if($lignePrixprestaortho=$prixprestaortho->fetch())
				{
					$prixOrtho=$lignePrixprestaortho->prixpresta;
					$prixOrthoCCO=$lignePrixprestaortho->prixprestaCCO;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_ortho (dateconsu,id_prestationOrtho,prixprestationOrtho,prixprestationOrthoCCO,id_uO,orthofait,dateortho,id_assuOrtho,insupercentOrtho,numero,id_uM,id_consuOrtho) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:id_uO,:orthofait,:dateortho,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($annee)),
					'idPresta'=>$idPresta[$x],
					'prixPresta'=>$prixOrtho,
					'prixPrestaCCO'=>$prixOrthoCCO,
					'id_uO'=>$id_uO,
					'orthofait'=>$orthofait,
					'dateortho'=>date('Y-m-d', strtotime($annee)),
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) or die( print_r($connexion->errorInfo()));
					
				}
			}			
		}
	
		if(isset($_POST['addAutreOrtho']))
		{
			if(isset($_POST['areaAutreortho']))
			{
				$idAutrePresta=$_POST['areaAutreortho'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$prixprestaCCO=-1;
					$id_categopresta=14;
					$id_souscategopresta=NULL;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=14 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'prixprestaCCO'=>$prixprestaCCO,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) or die( print_r($connexion->errorInfo()));
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_ortho---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestaortho=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestaortho->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestaortho->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestaortho=$prixprestaortho->rowCount();
						
						if($lignePrixprestaortho=$prixprestaortho->fetch())
						{
							$prixOrtho=$lignePrixprestaortho->prixpresta;
							$prixOrthoCCO=$lignePrixprestaortho->prixprestaCCO;
						}
						
						$resultat=$connexion->prepare('INSERT INTO med_ortho (dateconsu,id_prestationOrtho,prixprestationOrtho,prixprestationOrthoCCO,id_uO,orthofait,dateortho,id_assuOrtho,insupercentOrtho,numero,id_uM,id_consuOrtho) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:id_uO,:orthofait,:dateortho,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($annee)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixOrtho,
						'prixPrestaCCO'=>$prixOrthoCCO,
						'id_uO'=>$id_uO,
						'orthofait'=>$orthofait,						
						'dateortho'=>date('Y-m-d', strtotime($annee)),
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) or die( print_r($connexion->errorInfo()));
					
					}
				}
			}
		}
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedOrtho='.$idmedOrtho.'&idconsuOrtho='.$idconsuAdd.'&idassuOrtho='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&orthoPa='.$orthoPa.'&english='.$_GET['english'].'#orthoTable";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedOrtho='.$idmedOrtho.'&idconsuOrtho='.$idconsuAdd.'&idassuOrtho='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&orthoPa='.$orthoPa.'&francais='.$_GET['francais'].'#orthoTable";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedOrtho='.$idmedOrtho.'&idconsuOrtho='.$idconsuAdd.'&idassuOrtho='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&orthoPa='.$orthoPa.'#orthoTable";</script>';
			}
		}
	}
	
		
	if(isset($_POST['deleteMedortho']))
	{	
		$deleteidmedOrtho = array();

		foreach($_POST['deleteMedortho'] as $valeur)
		{
			$deleteidmedOrtho[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteidmedOrtho);$i++)
		{
			// echo $deleteidmedOrtho[$i];
	
			$resultats=$connexion->prepare('DELETE FROM med_ortho WHERE id_medortho=:id_medK');
		
			$resultats->execute(array(
			'id_medK'=>$deleteidmedOrtho[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le soin '.$deleteidmedOrtho[$i].' a bien été supprimé");</script>';
		
		}
		
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedOrtho='.$idmedOrtho.'&idconsuOrtho='.$idconsuAdd.'&idassuOrtho='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&orthoPa='.$orthoPa.'&english='.$_GET['english'].'#orthoTable";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedOrtho='.$idmedOrtho.'&idconsuOrtho='.$idconsuAdd.'&idassuOrtho='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&orthoPa='.$orthoPa.'&francais='.$_GET['francais'].'#orthoTable";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedOrtho='.$idmedOrtho.'&idconsuOrtho='.$idconsuAdd.'&idassuOrtho='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&orthoPa='.$orthoPa.'#orthoTable";</script>';
			}
		}
	}
		
}

catch(Excepton $e)
{
	echo 'Erreur:'.$e->getMessage().'<br/>';
	echo'Numero:'.$e->getCode();
}



?>