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
	
	if(isset($_GET['idassuInf']))
	{
		$idassu=$_GET['idassuInf'];
										
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
	$idmedInf=$_GET['idmedInf'];
	$idconsuAdd=$_GET['idconsuInf'];
	$id_uI=$_SESSION['id'];
	$id_uM=$_GET['iduM'];
	$presta=$_GET['presta'];
	$soinsPa=$_GET['soinsPa'];
	$dateconsu=$_GET['dateconsu'];
	
 	if(isset($_POST['savebtn']))
	{
		if(isset($_POST['qteConsom']))
		{
			$idmedconsom = array();
			$qteConsom = array();

			foreach($_POST['id_medconsom'] as $valueidMedConsom)
			{
				$idmedconsom[] = $valueidMedConsom;			   
			}
			
			foreach($_POST['qteConsom'] as $valueQteConsom)
			{
				$qteConsom[] = $valueQteConsom;			   
			}
			
			for($x=0;$x<sizeof($idmedconsom);$x++)
			{
				if($idmedconsom[$x] != "")
				{					
					$resultats=$connexion->prepare('UPDATE med_consom SET qteConsom=:qteConsom WHERE id_medconsom=:idmedconsom');
							
					$resultats->execute(array(
					'qteConsom'=>$qteConsom[$x],
					'idmedconsom'=>$idmedconsom[$x]
					
					));
		
				}
			}
		}
	
		if(isset($_POST['qteMedoc']))
		{
			$idmedmedoc = array();
			$qteMedoc = array();

			foreach($_POST['id_medmedoc'] as $valueidMedMedoc)
			{
				$idmedmedoc[] = $valueidMedMedoc;			   
			}
			
			foreach($_POST['qteMedoc'] as $valueQteMedoc)
			{
				$qteMedoc[] = $valueQteMedoc;			   
			}
			
			for($x=0;$x<sizeof($idmedmedoc);$x++)
			{
				if($idmedmedoc[$x] != 0)
				{					
					$resultats=$connexion->prepare('UPDATE med_medoc SET qteMedoc=:qteMedoc WHERE id_medmedoc=:idmedmedoc');
							
					$resultats->execute(array(
					'qteMedoc'=>$qteMedoc[$x],
					'idmedmedoc'=>(int)$idmedmedoc[$x]
					
					));
		
				}
			}
		}		

		if(isset($_POST['qteInf']))
		{
			$idmedInf = array();
			$qteInf = array();

			foreach($_POST['id_medinf'] as $valueid_medinf)
			{
				$idmedInf[] = $valueid_medinf;			   
			}
			
			foreach($_POST['qteInf'] as $valueqteInf)
			{
				$qteInf[] = $valueqteInf;			   
			}
			
			for($x=0;$x<sizeof($idmedInf);$x++)
			{
				if($idmedInf[$x] != 0)
				{					
					// $resultats=$connexion->prepare('UPDATE med_medoc SET qteMedoc=:qteMedoc WHERE id_medmedoc=:idmedInf');
							
					// $resultats->execute(array(
					// 'qteMedoc'=>$qteMedoc[$x],
					// 'idmedInf'=>(int)$idmedInf[$x]
					
					// ));

					$updateMedInf=$connexion->prepare("UPDATE med_inf SET qteInf=:qteInf,id_uInfNurse=:id_uInfNurse,soinsfait = '1', id_uI=:id_uI, datesoins=:annee WHERE id_medinf =:id_medinf");			
					$updateMedInf->execute(array(
					'qteInf'=>$qteInf[$x],
					'id_medinf'=>(int)$idmedInf[$x],
					'id_uInfNurse'=>$id_uI,
					'id_uI'=>$id_uI,
					'annee'=>$annee
					));
		
				}
			}
		}

		// $updateMedInf=$connexion->prepare("UPDATE med_inf SET soinsfait = '1', id_uI=:id_uI, datesoins=:annee WHERE id_consuInf =:idconsuAdd AND soinsfait=0");			
		// $updateMedInf->execute(array(
		// 'idconsuAdd'=>$idconsuAdd,
		// 'id_uI'=>$id_uI,
		// 'annee'=>$annee
		// ));
		
		// echo "UPDATE med_inf SET soinsfait = '1', id_uI=".$id_uI.", datesoins=".$annee." WHERE id_consuInf=".$idconsuAdd."";
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
			}
		}
	}
	
 	
	if(isset($_POST['updatebtn']))
	{
		if(isset($_POST['qteConsom']))
		{
			$idmedconsom = array();
			$qteConsom = array();

			foreach($_POST['id_medconsom'] as $valueidMedConsom)
			{
				$idmedconsom[] = $valueidMedConsom;			   
			}
			
			foreach($_POST['qteConsom'] as $valueQteConsom)
			{
				$qteConsom[] = $valueQteConsom;			   
			}
			
			for($x=0;$x<sizeof($idmedconsom);$x++)
			{
				if($idmedconsom[$x] != 0)
				{					
					$resultats=$connexion->prepare('UPDATE med_consom SET qteConsom=:qteConsom WHERE id_medconsom=:idmedconsom');
							
					$resultats->execute(array(
					'qteConsom'=>$qteConsom[$x],
					'idmedconsom'=>(int)$idmedconsom[$x]
					
					));
		
				}
			}
		}
	
		if(isset($_POST['qteMedoc']))
		{
			$idmedmedoc = array();
			$qteMedoc = array();

			foreach($_POST['id_medmedoc'] as $valueidMedMedoc)
			{
				$idmedmedoc[] = $valueidMedMedoc;			   
			}
			
			foreach($_POST['qteMedoc'] as $valueQteMedoc)
			{
				$qteMedoc[] = $valueQteMedoc;			   
			}
			
			for($x=0;$x<sizeof($idmedmedoc);$x++)
			{
				if($idmedmedoc[$x] != 0)
				{					
					$resultats=$connexion->prepare('UPDATE med_medoc SET qteMedoc=:qteMedoc WHERE id_medmedoc=:idmedmedoc');
							
					$resultats->execute(array(
					'qteMedoc'=>$qteMedoc[$x],
					'idmedmedoc'=>(int)$idmedmedoc[$x]
					
					));
		
				}
			}
		}

		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
			}
		}
	}

	
	if(isset($_POST['addConsult']) OR isset($_POST['addAutreConsult']))
	{
			
		if(isset($_POST['addConsult']))
		{
			$idPrestaConsu = array();

			foreach($_POST['consult'] as $valueConsu)
			{
				$idPrestaConsu[] = $valueConsu;			   
			}

			// print_r($idPrestaConsu);
			
			for($x=0;$x<sizeof($idPrestaConsu);$x++)
			{
				// echo $idPrestaConsu.','.$autreConsu.','.$dateconsu.','.$id_uM.','.$numero;
				
				$prixprestaconsu=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestaconsu->execute(array(
				'idPresta'=>$idPrestaConsu[$x]
				));
				
				$prixprestaconsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestaconsu=$prixprestaconsu->rowCount();
				
				if($lignePrixprestaconsu=$prixprestaconsu->fetch())
				{
					$prixConsu=$lignePrixprestaconsu->prixpresta;
				}
				
				if($idPrestaConsu[$x] != "")
				{
					// echo"ss";
					$resultat=$connexion->prepare('INSERT INTO med_consult (dateconsu,id_prestationConsu,prixprestationConsu,id_uConsult,id_assuServ,insupercentServ,numero,id_uM,id_consuMed) 
					VALUES(:dateconsu,:idPrestaConsu,:prixPrestaConsu,:id_uI,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($annee)),
					'idPrestaConsu'=>$idPrestaConsu[$x],
					'prixPrestaConsu'=>$prixConsu,
					'id_uI'=>$id_uI,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					)) ;
					
				}
			}
		}
		
		if(isset($_POST['addAutreConsult']))
		{
			if(isset($_POST['areaAutreconsult']))
			{
				$idAutreConsult=$_POST['areaAutreconsult'];

				if($idAutreConsult != "")
				{
					$prixpresta=-1;
					$id_categopresta=20;
					$id_souscategopresta=0;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=20 AND (nompresta=:idAutreConsult OR namepresta=:idAutreConsult) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutreConsult'=>$idAutreConsult
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutreConsult),
						'namepresta'=>nl2br($idAutreConsult),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_consult---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestaconsu=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestaconsu->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestaconsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPrixprestaconsu=$prixprestaconsu->rowCount();
						
						if($lignePrixprestaconsu=$prixprestaconsu->fetch())
						{
							$prixConsu=$lignePrixprestaconsu->prixpresta;
						}
						
						$resultat=$connexion->prepare('INSERT INTO med_consult (dateconsu,id_prestationConsu,prixprestationConsu,id_assuServ,insupercentServ,numero,id_uM,id_consuMed) VALUES(:dateconsu,:idPrestaConsu,:prixPrestaConsu,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPrestaConsu'=>$lastIdPresta,
						'prixPrestaConsu'=>$prixConsu,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>nl2br($numero),
						'id_uM'=>nl2br($id_uM),
						'idconsuAdd'=>nl2br($idconsuAdd)
						)) ;
						
					}
					
				}
			}
		}
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
			}
		}
	}
	

	if(isset($_POST['addNursery']) or isset($_POST['addAutreNursery']))
	{
		$soinsfait=1;
			
		if(isset($_POST['addNursery']))
		{
			$idPresta = array();

			foreach($_POST['soins'] as $valueSoins)
			{
				$idPresta[] = $valueSoins;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$soinsfait.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestainf=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestainf->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestainf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestainf=$prixprestainf->rowCount();
				
				if($lignePrixprestainf=$prixprestainf->fetch())
				{
					$prixInf=$lignePrixprestainf->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,prixprestation,id_uI,soinsfait,datesoins,id_assuInf,insupercentInf,numero,id_uM,id_consuInf) VALUES(:dateconsu,:idPresta,:prixPresta,:id_uI,:soinsfait,:datesoins,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($annee)),
					'idPresta'=>$idPresta[$x],
					'prixPresta'=>$prixInf,
					'id_uI'=>$id_uI,
					'soinsfait'=>$soinsfait,
					'datesoins'=>date('Y-m-d', strtotime($annee)),
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutreNursery']))
		{
			if(isset($_POST['areaAutresoins']))
			{
				$idAutrePresta=$_POST['areaAutresoins'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=3;
					$id_souscategopresta=0;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=3 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_inf---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestainf=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestainf->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestainf->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestainf=$prixprestainf->rowCount();
						
						if($lignePrixprestainf=$prixprestainf->fetch())
						{
							$prixInf=$lignePrixprestainf->prixpresta;
						}
						
						$resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,prixprestation,id_uI,soinsfait,datesoins,id_assuInf,insupercentInf,numero,id_uM,id_consuInf) VALUES(:dateconsu,:idPresta,:prixPresta,:id_uI,:soinsfait,:datesoins,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($annee)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixInf,
						'id_uI'=>$id_uI,
						'soinsfait'=>$soinsfait,						
						'datesoins'=>date('Y-m-d', strtotime($annee)),
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
			}
		}
	}
	
	
	
	if(isset($_POST['addConsom']) or isset($_POST['addAutreConsom']))
	{
		if(isset($_POST['addConsom']))
		{
			$idPresta = array();

			foreach($_POST['consom'] as $valueConsom)
			{
				$idPresta[] = $valueConsom;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestaconsom=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestaconsom->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestaconsom->setFetchMode(PDO::FETCH_OBJ);

				$comptPrixprestaconsom=$prixprestaconsom->rowCount();
				
				if($lignePrixprestaconsom=$prixprestaconsom->fetch())
				{
					$prixConsom=$lignePrixprestaconsom->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
			
					$resultat=$connexion->prepare('INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,id_uInfConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom) VALUES(:dateconsu,:idPresta,:prixPresta,:id_uI,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($annee)),
					'idPresta'=>$idPresta[$x],
					'prixPresta'=>$prixConsom,
					'id_uI'=>$id_uI,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutreConsom']))
		{
			if(isset($_POST['areaAutreconsom']))
			{
				$idAutrePresta=$_POST['areaAutreconsom'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=21;
					$id_souscategopresta=NULL;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=21 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_consom---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestaconsom=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestaconsom->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestaconsom->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestaconsom=$prixprestaconsom->rowCount();
						
						if($lignePrixprestaconsom=$prixprestaconsom->fetch())
						{
							$prixConsom=$lignePrixprestaconsom->prixpresta;
						}
						
						$resultat=$connexion->prepare('INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,id_uInfConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom) VALUES(:dateconsu,:idPresta,:prixPresta,:id_uI,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($annee)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixConsom,
						'id_uI'=>$id_uI,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
			}
		}
	}
	

	if(isset($_POST['addMedoc']) or isset($_POST['addAutreMedoc']))
	{
		if(isset($_POST['addMedoc']))
		{
			$idPresta = array();

			foreach($_POST['medoc'] as $valueMedoc)
			{
				$idPresta[] = $valueMedoc;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestamedoc=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestamedoc->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestamedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestamedoc=$prixprestamedoc->rowCount();
				
				if($lignePrixprestamedoc=$prixprestamedoc->fetch())
				{
					$prixMedoc=$lignePrixprestamedoc->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_medoc (dateconsu,id_prestationMedoc,prixprestationMedoc,id_uInfMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc) VALUES(:dateconsu,:idPresta,:prixPresta,:id_uI,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($annee)),
					'idPresta'=>$idPresta[$x],
					'prixPresta'=>$prixMedoc,
					'id_uI'=>$id_uI,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutreMedoc']))
		{
			if(isset($_POST['areaAutremedoc']))
			{
				$idAutrePresta=$_POST['areaAutremedoc'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=22;
					$id_souscategopresta=NULL;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=22 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_inf---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestamedoc=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestamedoc->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestamedoc->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestamedoc=$prixprestamedoc->rowCount();
						
						if($lignePrixprestamedoc=$prixprestamedoc->fetch())
						{
							$prixMedoc=$lignePrixprestamedoc->prixpresta;
						}
					
						$resultat=$connexion->prepare('INSERT INTO med_medoc (dateconsu,id_prestationMedoc,prixprestationMedoc,id_uInfMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc) VALUES(:dateconsu,:idPresta,:prixPresta,:id_uI,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($annee)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixMedoc,
						'id_uI'=>$id_uI,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
			}
		}
	}
	
	
	// echo $_POST['deleteMedconsu'.$_POST['deleteIdMedconsu'].''];
		
	if(isset($_POST['deleteMedconsu']))
	{
		$deleteIdMedConsu = array();

		foreach($_POST['deleteMedconsu'] as $valeur)
		{
			$deleteIdMedConsu[] = $valeur;		
		}
		
		
		for($i=0;$i<sizeof($deleteIdMedConsu);$i++)
		{
			// echo $deleteIdMedConsu[$i];
	
			$resultats=$connexion->prepare('DELETE FROM med_consult WHERE id_medconsu=:id_medC');
			
			$resultats->execute(array(
			'id_medC'=>$deleteIdMedConsu[$i]
			
			))or die($resultats->errorInfo());
		
			// echo '<script type="text/javascript"> alert("Le service'.$deleteIdMedConsu[$i].' a bien été supprimé");</script>';
		
		}
		
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
			}
		}
	}
	
		
	if(isset($_POST['deleteMedinf']))
	{	
		$deleteIdMedinf = array();

		foreach($_POST['deleteMedinf'] as $valeur)
		{
			$deleteIdMedinf[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedinf);$i++)
		{
			// echo $deleteIdMedinf[$i];
	
			$resultats=$connexion->prepare('DELETE FROM med_inf WHERE id_medinf=:id_medI');
		
			$resultats->execute(array(
			'id_medI'=>$deleteIdMedinf[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le soin '.$deleteIdMedinf[$i].' a bien été supprimé");</script>';
		
		}
		
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
			}
		}
	}
	
		
	if(isset($_POST['deleteMedconsom']))
	{	
		$deleteIdMedconsom = array();

		foreach($_POST['deleteMedconsom'] as $valeur)
		{
			$deleteIdMedconsom[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedconsom);$i++)
		{
			// echo $deleteIdMedconsom[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_consom WHERE id_medconsom=:id_medCo');
		
			$resultats->execute(array(
			'id_medCo'=>$deleteIdMedconsom[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le matériel '.$deleteIdMedconsom[$i].' a bien été supprimé");</script>';
		
		}
		
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
			}
		}
	}

	
		
	if(isset($_POST['deleteMedmedoc']))
	{			
		$deleteIdMedmedoc = array();

		foreach($_POST['deleteMedmedoc'] as $valeur)
		{
			$deleteIdMedmedoc[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedmedoc);$i++)
		{
			// echo $deleteIdMedmedoc[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_medoc WHERE id_medmedoc=:id_medMo');
		
			$resultats->execute(array(
			'id_medMo'=>$deleteIdMedmedoc[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le médicament '.$deleteIdMedmedoc[$i].' a bien été supprimé");</script>';
		
		}
		
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuAdd.'&idassuInf='.$idassu.'&iduM='.$id_uM.'&presta='.$presta.'&dateconsu='.$dateconsu.'&soinsPa='.$soinsPa.'";</script>';
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