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
	
	if(isset($_GET['idassuKine']))
	{
		$idassu=$_GET['idassuKine'];
										
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
	$idmedKine=$_GET['idmedKine'];
	$idconsuAdd=$_GET['idconsuKine'];
	$id_uK=$_SESSION['id'];
	$id_uM=$_GET['iduM'];
	$presta=$_GET['presta'];
	$kinePa=$_GET['kinePa'];
	$dateconsu=$_GET['dateconsu'];
	
 	if(isset($_POST['savebtn']))
	{
		$updateConsultations=$connexion->prepare("UPDATE consultations SET physio=2, id_uM=:id_uM WHERE id_consu =:idconsuAdd");			
		$updateConsultations->execute(array(
		'idconsuAdd'=>$idconsuAdd,
		'id_uM'=>$id_uM
		))or die( print_r($connexion->errorInfo()));
		
		// echo "UPDATE med_kine SET kinefait = '2', id_uK=".$id_uK.", datekine=".$annee." WHERE id_consuKine=".$idconsuAdd."";
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?iduser='.$id_uK.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?iduser='.$id_uK.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?iduser='.$id_uK.'";</script>';
			}
		}
	}
	
	
	if(isset($_POST['addKine']) or isset($_POST['addAutreKine']))
	{
		$kinefait=1;
			
		if(isset($_POST['addKine']))
		{
			$idPresta = array();

			foreach($_POST['kine'] as $valueKine)
			{
				$idPresta[] = $valueKine;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$kinefait.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestakine=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestakine->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestakine->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestakine=$prixprestakine->rowCount();
				
				if($lignePrixprestakine=$prixprestakine->fetch())
				{
					$prixKine=$lignePrixprestakine->prixpresta;
					$prixKineCCO=$lignePrixprestakine->prixprestaCCO;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_kine (dateconsu,id_prestationKine,prixprestationKine,prixprestationKineCCO,id_uK,kinefait,datekine,id_assuKine,insupercentKine,numero,id_uM,id_consuKine) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:id_uK,:kinefait,:datekine,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($annee)),
					'idPresta'=>$idPresta[$x],
					'prixPresta'=>$prixKine,
					'prixPrestaCCO'=>$prixKineCCO,
					'id_uK'=>$id_uK,
					'kinefait'=>$kinefait,
					'datekine'=>date('Y-m-d', strtotime($annee)),
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) or die( print_r($connexion->errorInfo()));
					
				}
			}			
		}
	
		if(isset($_POST['addAutreKine']))
		{
			if(isset($_POST['areaAutrekine']))
			{
				$idAutrePresta=$_POST['areaAutrekine'];

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
					
					/*-------Put in med_kine---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestakine=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestakine->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestakine->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestakine=$prixprestakine->rowCount();
						
						if($lignePrixprestakine=$prixprestakine->fetch())
						{
							$prixKine=$lignePrixprestakine->prixpresta;
							$prixKineCCO=$lignePrixprestakine->prixprestaCCO;
						}
						
						$resultat=$connexion->prepare('INSERT INTO med_kine (dateconsu,id_prestationKine,prixprestationKine,prixprestationKineCCO,id_uK,kinefait,datekine,id_assuKine,insupercentKine,numero,id_uM,id_consuKine) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:id_uK,:kinefait,:datekine,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($annee)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixKine,
						'prixPrestaCCO'=>$prixKineCCO,
						'id_uK'=>$id_uK,
						'kinefait'=>$kinefait,						
						'datekine'=>date('Y-m-d', strtotime($annee)),
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
			echo '<script type="text/javascript">document.location.href="consultToPhysio.php?num='.$num.'&idmedKine='.$idmedKine.'&idconsuKine='.$idconsuAdd.'&idassuKine='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&kinePa='.$kinePa.'&english='.$_GET['english'].'#kineTable";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consultToPhysio.php?num='.$num.'&idmedKine='.$idmedKine.'&idconsuKine='.$idconsuAdd.'&idassuKine='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&kinePa='.$kinePa.'&francais='.$_GET['francais'].'#kineTable";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="consultToPhysio.php?num='.$num.'&idmedKine='.$idmedKine.'&idconsuKine='.$idconsuAdd.'&idassuKine='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&kinePa='.$kinePa.'#kineTable";</script>';
			}
		}
	}
	
		
	if(isset($_POST['deleteMedkine']))
	{	
		$deleteidmedKine = array();

		foreach($_POST['deleteMedkine'] as $valeur)
		{
			$deleteidmedKine[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteidmedKine);$i++)
		{
			// echo $deleteidmedKine[$i];
	
			$resultats=$connexion->prepare('DELETE FROM med_kine WHERE id_medkine=:id_medK');
		
			$resultats->execute(array(
			'id_medK'=>$deleteidmedKine[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le soin '.$deleteidmedKine[$i].' a bien été supprimé");</script>';
		
		}
		
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consultToPhysio.php?num='.$num.'&idmedKine='.$idmedKine.'&idconsuKine='.$idconsuAdd.'&idassuKine='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&kinePa='.$kinePa.'&english='.$_GET['english'].'#kineTable";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consultToPhysio.php?num='.$num.'&idmedKine='.$idmedKine.'&idconsuKine='.$idconsuAdd.'&idassuKine='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&kinePa='.$kinePa.'&francais='.$_GET['francais'].'#kineTable";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="consultToPhysio.php?num='.$num.'&idmedKine='.$idmedKine.'&idconsuKine='.$idconsuAdd.'&idassuKine='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&kinePa='.$kinePa.'#kineTable";</script>';
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