<?php
session_start();
include("connect.php");
include("connectLangues.php");


	$annee = date('Y').'-'.date('m').'-'.date('d');
	$idfacture="";


	if(isset($_GET['num']))
	{
		$resultats=$connexion->prepare('SELECT *FROM utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp and ph.numero=:operation');
		$resultats->execute(array(
		'operation'=>$_GET['num']	
		));
		
		$resultats->setFetchMode(PDO::FETCH_OBJ);
		while($ligne=$resultats->fetch())
		{
			$num=$ligne->numero;
			$bill=$ligne->insupercent_hosp;
			$idP=$ligne->id_uHosp;
		}
		$resultats->closeCursor();

	}


	$idassu=$_GET['idassu'];
	
	$datehosp=$_GET['datehosp'];
	$numero=$_GET['num'];
	$id_uM=$_SESSION['id'];
	$id_uI=NULL;
	$idhospAdd=$_GET['idhosp'];
	

	$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
	
	$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
			
	$assuCount = $comptAssuConsu->rowCount();
	
	for($i=1;$i<=$assuCount;$i++)
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
		

if(isset($_POST['checkprestaServ']))
{
	$add = array();

	foreach($_POST['checkprestaServ'] as $valeur)
	{
		$add[] = $valeur;
		   
	}
	
	for($i=0;$i<sizeof($add);$i++)
	{
		
		$resServ=$connexion->query('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation="'.$add[$i].'" ORDER BY p.nompresta');
			
		$comptServ=$resServ->rowCount();
			
		if($ligneprestaServ=$resServ->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaServ=$ligneprestaServ->prixpresta;
			
			// echo $add[$i].' ('.$ligneprestaServ->prixpresta.')<br/>';
		}
		
		$resultatServ=$connexion->prepare('INSERT INTO med_consult_hosp (datehosp,id_prestationConsu,prixprestationConsu,id_assuServ,insupercentServ,numero,id_uM,id_uI,id_hospMed) VALUES(:datehosp,:idPrestaConsu,:prixPrestaConsu,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd)');
		$resultatServ->execute(array(
		'datehosp'=>$annee,
		'idPrestaConsu'=>$add[$i],
		'prixPrestaConsu'=>$prixprestaServ,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'id_uI'=>$id_uI,
		'idhospAdd'=>$idhospAdd
		)) or die( print_r($connexion->errorInfo()));
					
	}
}

if(isset($_POST['autreprestaServ']))
{
	$idAutreConsult=$_POST['autreprestaServ'];

	if($idAutreConsult != "")
	{
		$prixpresta=$_POST['autreprixprestaServ'];
		$id_categopresta=20;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		
		$addautre = array();
		$addautreprix = array();
		$qteadd = array();

		foreach($_POST['autreprestaServ'] as $valeur)
		{
			$addautre[] = $valeur;
			   
		}
		foreach($_POST['autreprixprestaServ'] as $valeurprix)
		{
			$addautreprix[] = $valeurprix;
			   
		}
		foreach($_POST['qteprestaServ'] as $valeurqte)
		{
			if($valeurqte==0)
			{
				$qteadd[] = 1;
			   
			}else{
				$qteadd[] = $valeurqte;
			}
		
		}
		
		for($i=0;$i<sizeof($addautre);$i++)
		{			
			if($addautre[$i]!="")
			{
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=20 AND (nompresta=:idAutreConsult OR namepresta=:idAutreConsult) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
				'idAutreConsult'=>$addautre[$i]
				));
								
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
							
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
					'nompresta'=>nl2br($addautre[$i]),
					'namepresta'=>nl2br($addautre[$i]),
					'prixpresta'=>$addautreprix[$i],
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
					
					$resultatServ=$connexion->prepare('INSERT INTO med_consult_hosp (datehosp,id_prestationConsu,prixprestationConsu,qteConsu,id_assuServ,insupercentServ,numero,id_uM,id_uI,id_hospMed) VALUES(:datehosp,:idPrestaConsu,:prixPrestaConsu,:qteConsu,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd)');
					$resultatServ->execute(array(
					'datehosp'=>$annee,
					'idPrestaConsu'=>$lastIdPresta,
					'prixPrestaConsu'=>$prixConsu,
					'qteConsu'=>$qteadd[$i],
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'id_uI'=>$id_uI,
					'idhospAdd'=>$idhospAdd
					)) or die( print_r($connexion->errorInfo()));
				}
			}
		}
	}
}


if(isset($_POST['checkprestaInf']))
{
	$addInf = array();
	$soinsfait=1;

	foreach($_POST['checkprestaInf'] as $valeurInf)
	{
		$addInf[] = $valeurInf;  
	}
		
	for($i=0;$i<sizeof($addInf);$i++)
	{
		
		$resInf=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=3 AND p.id_prestation="'.$addInf[$i].'" ORDER BY p.nompresta');
					
		$comptInf=$resInf->rowCount();
			
		if($ligneprestaInf=$resInf->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaInf=$ligneprestaInf->prixpresta;
			
			// echo $addInf[$i].' ('.$ligneprestaInf->prixpresta.')<br/>';
		}
		
		
		$resultatInf=$connexion->prepare('INSERT INTO med_inf_hosp (datehosp,id_prestation,prixprestation,soinsfait,datesoins,id_assuInf,insupercentInf,numero,id_uM,id_uI,id_hospInf) VALUES(:datehosp,:idPrestaInf,:prixPresta,:soinsfait,:datesoins,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd)');
		
		$resultatInf->execute(array(
		'datehosp'=>$annee,
		'idPrestaInf'=>$addInf[$i],
		'prixPresta'=>$prixprestaInf,
		'soinsfait'=>$soinsfait,
		'datesoins'=>$annee,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'id_uI'=>$id_uI,
		'idhospAdd'=>$idhospAdd
		
		)) or die( print_r($connexion->errorInfo()));

	}
	
}


if(isset($_POST['autreprestaInf']))
{
	$idAutrePresta=$_POST['autreprestaInf'];

	if($idAutrePresta != "")
	{
		$id_categopresta=3;
		$id_souscategopresta=0;		
		$mesure=NULL;
		$statupresta=0;
		
		$soinsfait=1;	
		
		$addInfautre = array();
		$addInfautreprix = array();
		$qteaddInf= array();

		foreach($_POST['autreprestaInf'] as $valeurInf)
		{
			$addInfautre[] = $valeurInf;
			   
		}
		foreach($_POST['qteprestaInf'] as $valeurqteInf)
		{
			if($valeurqteInf==0)
			{
				$qteaddInf[] = 1;
			   
			}else{
				$qteaddInf[] = $valeurqteInf;
			}
		
		}
		foreach($_POST['autreprixprestaInf'] as $valeurprixInf)
		{
			$addInfautreprix[] = $valeurprixInf;
			   
		}
		
		for($i=0;$i<sizeof($addInfautre);$i++)
		{
			if($addInfautre[$i]!="")
			{
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=3 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
				'idAutrePresta'=>$addInfautre[$i]
				));
								
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
							
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
					'nompresta'=>nl2br($addInfautre[$i]),
					'namepresta'=>nl2br($addInfautre[$i]),
					'prixpresta'=>$addInfautreprix[$i],
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

					$comptPrixprestaconsu=$prixprestainf->rowCount();
					
					if($lignePrixprestainf=$prixprestainf->fetch())
					{
						$prixInf=$lignePrixprestainf->prixpresta;
					}
					
					$resultatInf=$connexion->prepare('INSERT INTO med_inf_hosp (datehosp,id_prestation,prixprestation,qteInf,soinsfait,datesoins,id_assuInf,insupercentInf,numero,id_uM,id_uI,id_hospInf) VALUES(:datehosp,:idPresta,:prixPresta,:qteInf,:soinsfait,:datesoins,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd)');
					
					$resultatInf->execute(array(
					'datehosp'=>$annee,
					'idPresta'=>$lastIdPresta,
					'prixPresta'=>$prixInf,
					'qteInf'=>$qteaddInf[$i],
					'soinsfait'=>$soinsfait,
					'datesoins'=>$annee,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'id_uI'=>$id_uI,
					'idhospAdd'=>$idhospAdd
					
					)) or die( print_r($connexion->errorInfo()));
				}
			}
		}
	}
}


if(isset($_POST['checkprestaSurge']))
{
	$addSurge = array();

	$surgefait=1;
	$qteSurge=1;
	$prixautreSurge=0;
	$idfacture="";

	foreach($_POST['checkprestaSurge'] as $valeurSurge)
	{
		$addSurge[] = $valeurSurge;
	}
		
	for($i=0;$i<sizeof($addSurge);$i++)
	{
		
		$resSurge=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=4 AND p.id_prestation="'.$addSurge[$i].'" ORDER BY p.nompresta');
					
		$comptSurge=$resSurge->rowCount();
		
		if($comptSurge==0)
		{
			$resSurge=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=4 AND p.id_prestation="'.$addSurge[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaSurge=$resSurge->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaSurge=$ligneprestaSurge->prixpresta;
				
				// echo $comptSurge.'_'.$addSurge[$i].' ('.$prixprestaSurge.')<br/>';
			}
			
			
			$resultatSurge=$connexion->prepare('INSERT INTO med_surge_hosp (datehosp,id_prestationSurge,prixprestationSurge,surgefait,prixautrePrestaS,qteSurge,id_assuSurge,insupercentSurge,numero,id_uM,id_uI,id_hospSurge) VALUES(:datehosp,:idPrestaSurge,:prixPresta,:surgefait,:prixautreSurge,:qteSurge,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd)');
			$resultatSurge->execute(array(
			'datehosp'=>$annee,
			'idPrestaSurge'=>$addSurge[$i],
			'prixPresta'=>$prixprestaSurge,
			'surgefait'=>$surgefait,
			'prixautreSurge'=>$prixautreSurge,
			'qteSurge'=>$qteSurge,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'id_uI'=>$id_uI,
			'idhospAdd'=>$idhospAdd
			)) or die( print_r($connexion->errorInfo()));
		
	}
	
}		


if(isset($_POST['autreprestaSurge']))
{
	if($_POST['autreprestaSurge']!='')
	{	
		$id_categopresta=4;
		$id_souscategopresta=0;		
		$mesure=NULL;
		$statupresta=0;
		
		$idfacture="";
		$surgefait=1;
		
		$addSurgeautre = array();
		$addSurgeautreprix = array();
		$qteSurge = array();

		foreach($_POST['autreprestaSurge'] as $valeurSurge)
		{
			$addSurgeautre[] = $valeurSurge;
			   
		}
		foreach($_POST['autreprixprestaSurge'] as $valeurSurgeprix)
		{
			$addSurgeautreprix[] = $valeurSurgeprix;
			   
		}
		foreach($_POST['qteprestaSurge'] as $valeurSurgeqte)
		{
			$qteSurge[] = $valeurSurgeqte;
			   
		}
		
		for($i=0;$i<sizeof($addSurgeautre);$i++)
		{			
			if($addSurgeautre[$i]!="")
			{
				if($qteSurge[$i]==0)
				{
					$qteSurge[$i]=1;
				}
				
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=4 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
				'idAutrePresta'=>$addSurgeautre[$i]
				));
								
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
							
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
					'nompresta'=>nl2br($addSurgeautre[$i]),
					'namepresta'=>nl2br($addSurgeautre[$i]),
					'prixpresta'=>$addSurgeautreprix[$i],
					'id_categopresta'=>$id_categopresta,
					'id_souscategopresta'=>$id_souscategopresta,
					'mesure'=>$mesure,
					'statupresta'=>$statupresta
					)) or die( print_r($connexion->errorInfo()));
					
					$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
					
				}else{
					$ligneNomPresta=$searchNomPresta->fetch();
					
						if($addSurgeautreprix[$i]!="")
						{	
							$updatePrixPrestaSurge=$connexion->prepare('UPDATE '.$presta_assu.' SET prixpresta=:newprice WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
											
							$updatePrixPrestaSurge->execute(array(
							'newprice'=>$addSurgeautreprix[$i]
							))or die( print_r($connexion->errorInfo()));
								
						}
					
					$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
					
				}
				
				/*-------Put in med_surge---------*/
			 
				$searchLastId->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneLastId=$searchLastId->fetch())
				{
					
					$lastIdPresta=$ligneLastId->id_prestation;
				
					$prixprestasurge=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
					$prixprestasurge->execute(array(
					'idPresta'=>$lastIdPresta
					));
					
					$prixprestasurge->setFetchMode(PDO::FETCH_OBJ);

					$comptPrixprestasurge=$prixprestasurge->rowCount();
					
					if($addSurgeautreprix[$i]=="")
					{
						if($lignePrixprestasurge=$prixprestasurge->fetch())
						{
							$addSurgeautreprix[$i]=$lignePrixprestasurge->prixpresta;
						}
					}
				
					$searchSurge=$connexion->prepare('SELECT *FROM med_surge_hosp WHERE datehosp=:datehosp AND (id_factureMedSurge="" OR id_factureMedSurge=0) AND numero=:num AND id_prestationSurge LIKE \''.$lastIdPresta.'\' ');
					$searchSurge->execute(array(
					'datehosp'=>$annee,
					'num'=>$numero
					)) or die( print_r($connexion->errorInfo()));
					
					$searchSurge->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchSurge=$searchSurge->rowCount();
					
					if($comptSearchSurge!=0)				
					{
						if($ligneSurge=$searchSurge->fetch())
						{
							
							$updateQteMedSurge=$connexion->prepare('UPDATE med_surge_hosp mdo SET mdo.qteSurge=:qteSurge, mdo.prixprestationSurge=:prixPresta WHERE mdo.datehosp=:datehosp AND mdo.numero=:num AND mdo.id_prestationSurge LIKE \''.$lastIdPresta.'\'');
							
							$updateQteMedSurge->execute(array(
							'qteSurge'=>$ligneSurge->qteSurge+$qteSurge[$i],
							'num'=>$numero,
							'prixPresta'=>$addSurgeautreprix[$i],
							'datehosp'=>$annee
							
							))or die( print_r($connexion->errorInfo()));
		
						}
						
					}else{
					
						$resultatSurge=$connexion->prepare('INSERT INTO med_surge_hosp (datehosp,id_prestationSurge,prixprestationSurge,surgefait,qteSurge,id_assuSurge,insupercentSurge,numero,id_uM,id_uI,id_hospSurge,id_factureMedSurge) VALUES(:datehosp,:idPresta,:prixPresta,:surgefait,:qteSurge,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
						$resultatSurge->execute(array(
						'datehosp'=>$annee,
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$addSurgeautreprix[$i],
						'surgefait'=>$surgefait,
						'qteSurge'=>$qteSurge[$i],
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'id_uI'=>$id_uI,
						'idhospAdd'=>$idhospAdd,
						'idfacture'=>$idfacture
						)) or die( print_r($connexion->errorInfo()));
					}
				}
			}
		}
	}
}


if(isset($_POST['checkprestaLab']))
{
	$addLab = array();
	
	$prixautreExa=0;
	$examenfait=0;
	$dateresultats="0000-00-00";
	$idfacture="";

	foreach($_POST['checkprestaLab'] as $valeurLab)
	{
		$addLab[] = $valeurLab; 
	}
	
	
	for($i=0;$i<sizeof($addLab);$i++)
	{

		$resLab=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=12 AND p.id_prestation="'.$addLab[$i].'" ORDER BY p.nompresta');
					
		$comptLab=$resLab->rowCount();
			
		if($ligneprestaLab=$resLab->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaLab=$ligneprestaLab->prixpresta;

			// echo $addLab[$i].' ('.$prixprestaLab.')<br/><br/>';
		}


		$resultatLabo=$connexion->prepare('INSERT INTO med_labo_hosp (datehosp,id_prestationExa,prixprestationExa,prixautreExamen,id_assuLab,insupercentLab,examenfait,dateresultats,numero,id_uM,id_uI,id_hospLabo,id_factureMedLabo) VALUES(:datehosp,:idPrestaLab,:prixPresta,:prixautreExa,:idassu,:bill,:examenfait,:dateresultat,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
		$resultatLabo->execute(array(
		'datehosp'=>$annee,
		'idPrestaLab'=>$addLab[$i],
		'prixPresta'=>$prixprestaLab,
		'prixautreExa'=>$prixautreExa,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'examenfait'=>$examenfait,
		'dateresultat'=>$dateresultats,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'id_uI'=>$id_uI,
		'idhospAdd'=>$idhospAdd,
		'idfacture'=>$idfacture
		)) or die( print_r($connexion->errorInfo()));

	}
}


if(isset($_POST['autreprestaLab']))
{
	$idAutreExam=$_POST['autreprestaLab'];

	if($idAutreExam != "")
	{
		$id_categopresta=12;
		$id_souscategopresta=7;
		$mesure=NULL;
		$statupresta=0;
		
		$examenfait=0;
		$idfacture="";
		$dateresultats="0000-00-00";		
		
		$addLabautre = array();
		$addLabautreprix = array();
		$qteaddLabo = array();

		foreach($_POST['autreprestaLab'] as $valeurLab)
		{
			$addLabautre[] = $valeurLab;
			   
		}
		foreach($_POST['autreprixprestaLab'] as $valeurLabprix)
		{
			$addLabautreprix[] = $valeurLabprix;
			   
		}
		foreach($_POST['qteprestaLab'] as $valeurqteLabo)
		{
			if($valeurqteLabo==0)
			{
				$qteaddLabo[] = 1;
			   
			}else{
				$qteaddLabo[] = $valeurqteLabo;
			}
		
		}
		
		for($i=0;$i<sizeof($addLabautre);$i++)
		{
			
			if($addLabautre[$i]!="")
			{	
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=12 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
				'idAutrePresta'=>$addLabautre[$i]
				));
								
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
							
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
					'nompresta'=>nl2br($addLabautre[$i]),
					'namepresta'=>nl2br($addLabautre[$i]),
					'prixpresta'=>$addLabautreprix[$i],
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
				
				/*-------Put in med_labo---------*/
			 
				$searchLastId->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneLastId=$searchLastId->fetch())
				{
					
					$lastIdPresta=$ligneLastId->id_prestation;
				
					$prixprestalabo=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
					$prixprestalabo->execute(array(
					'idPresta'=>$lastIdPresta
					));
					
					$prixprestalabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPrixprestalabo=$prixprestalabo->rowCount();
					
					if($lignePrixprestalabo=$prixprestalabo->fetch())
					{
						$prixLabo=$lignePrixprestalabo->prixpresta;
					}
					
					$resultatLab=$connexion->prepare('INSERT INTO med_labo_hosp (datehosp,id_prestationExa,prixprestationExa,qteLab,examenfait,dateresultats,id_assuLab,insupercentLab,numero,id_uM,id_uI,id_hospLabo,id_factureMedLabo) VALUES(:datehosp,:idPresta,:prixPresta,:qteLab,:examenfait,:dateresultat,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
					
					$resultatLab->execute(array(
					'datehosp'=>$annee,
					'idPresta'=>$lastIdPresta,
					'prixPresta'=>$prixLabo,
					'qteLab'=>$qteaddLabo[$i],
					'examenfait'=>$examenfait,
					'dateresultat'=>$dateresultats,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'id_uI'=>$id_uI,
					'idhospAdd'=>$idhospAdd,
					'idfacture'=>$idfacture	
					)) or die( print_r($connexion->errorInfo()));
				}
			}
		}
	}
}


if(isset($_POST['checkprestaRad']))
{
	$addRad = array();
	
	$prixautreRadio=0;
	$radiofait=0;
	$dateradio="0000-00-00";

	foreach($_POST['checkprestaRad'] as $valeurRad)
	{
		$addRad[] = $valeurRad; 
	}
	
	
	for($i=0;$i<sizeof($addRad);$i++)
	{

		$resRad=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=13 AND p.id_prestation="'.$addRad[$i].'" ORDER BY p.nompresta');
					
		$comptRad=$resRad->rowCount();
			
		if($ligneprestaRad=$resRad->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaRad=$ligneprestaRad->prixpresta;

			// echo $addRad[$i].' ('.$prixprestaRad.')<br/><br/>';
		}


		$resultatRadio=$connexion->prepare('INSERT INTO med_radio_hosp (datehosp,id_prestationRadio,prixprestationRadio,prixautreRadio,id_assuRad,insupercentRad,radiofait,dateradio,numero,id_uM,id_uI,id_hospRadio,id_factureMedRadio) VALUES(:datehosp,:idPrestaRad,:prixPresta,:prixautreRadio,:idassu,:bill,:radiofait,:dateradio,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
		$resultatRadio->execute(array(
		'datehosp'=>$annee,
		'idPrestaRad'=>$addRad[$i],
		'prixPresta'=>$prixprestaRad,
		'prixautreRadio'=>$prixautreRadio,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'radiofait'=>$radiofait,
		'dateradio'=>$dateradio,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'id_uI'=>$id_uI,
		'idhospAdd'=>$idhospAdd,
		'idfacture'=>$idfacture
		)) or die( print_r($connexion->errorInfo()));

	}
}


if(isset($_POST['autreprestaRad']))
{
	$idAutreRadio=$_POST['autreprestaRad'];

	if($idAutreRadio != "")
	{
		$prixpresta=$_POST['autreprixprestaRad'];
		$id_categopresta=13;
		$id_souscategopresta=10;		
		$mesure=NULL;
		$statupresta=0;
		
		$radiofait=0;
		$idfacture="";
		$dateradio="0000-00-00";
		
		$addRadautre = array();
		$addRadautreprix = array();
		$qteaddRad = array();

		foreach($_POST['autreprestaRad'] as $valeurRad)
		{
			$addRadautre[] = $valeurRad;
			   
		}
		foreach($_POST['autreprixprestaRad'] as $valeurRadprix)
		{
			$addRadautreprix[] = $valeurRadprix;
			   
		}
		foreach($_POST['qteprestaRad'] as $valeurqteRad)
		{
			if($valeurqteRad==0)
			{
				$qteaddRad[] = 1;
			   
			}else{
				$qteaddRad[] = $valeurqteRad;
			}
		
		}
		
		for($i=0;$i<sizeof($addRadautre);$i++)
		{			
			if($addRadautre[$i]!="")
			{
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=13 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
				'idAutrePresta'=>$addRadautre[$i]
				));
								
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
							
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
					'nompresta'=>nl2br($addRadautre[$i]),
					'namepresta'=>nl2br($addRadautre[$i]),
					'prixpresta'=>$addRadautreprix[$i],
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
				
				/*-------Put in med_labo---------*/
			 
				$searchLastId->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneLastId=$searchLastId->fetch())
				{
					
					$lastIdPresta=$ligneLastId->id_prestation;
				
					$prixprestaradio=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
					$prixprestaradio->execute(array(
					'idPresta'=>$lastIdPresta
					));
					
					$prixprestaradio->setFetchMode(PDO::FETCH_OBJ);

					$comptPrixprestaradio=$prixprestaradio->rowCount();
					
					if($lignePrixprestaradio=$prixprestaradio->fetch())
					{
						$prixRadio=$lignePrixprestaradio->prixpresta;
					}
					
						
					$resultatRad=$connexion->prepare('INSERT INTO med_Radio_hosp (datehosp,id_prestationRadio,prixprestationRadio,qteRad,radiofait,dateradio,id_assuRad,insupercentRad,numero,id_uM,id_uI,id_hospRadio,id_factureMedRadio) VALUES(:datehosp,:idPresta,:prixPresta,:qteRad,:radiofait,:dateradio,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
					
					$resultatRad->execute(array(
					'datehosp'=>$annee,
					'idPresta'=>$lastIdPresta,
					'prixPresta'=>$prixRadio,
					'qteRad'=>$qteaddRad[$i],
					'radiofait'=>$radiofait,
					'dateradio'=>$dateradio,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'id_uI'=>$id_uI,
					'idhospAdd'=>$idhospAdd,
					'idfacture'=>$idfacture	
					)) or die( print_r($connexion->errorInfo()));
				}
			}
		}
	}
}


if(isset($_POST['checkprestaConsom']))
{
	$addConsom = array();

	$prixautreConsom=0;
	$qteConsom=1;
	$idfacture="";

	foreach($_POST['checkprestaConsom'] as $valeurConsom)
	{
		$addConsom[] = $valeurConsom;
	}
		
	for($i=0;$i<sizeof($addConsom);$i++)
	{
		
		$resConsom=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation="'.$addConsom[$i].'" ORDER BY p.nompresta');
					
		$comptConsom=$resConsom->rowCount();
		
		if($comptConsom==0)
		{
			$resConsom=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation="'.$addConsom[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaConsom=$resConsom->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaConsom=$ligneprestaConsom->prixpresta;
				
				// echo $comptConsom.'_'.$addConsom[$i].' ('.$prixprestaConsom.')<br/>';
			}
			
				
			$resultatConsom=$connexion->prepare('INSERT INTO med_consom_hosp (datehosp,id_prestationConsom,prixprestationConsom,prixautreConsom,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_uI,id_hospConsom,id_factureMedConsom) VALUES(:datehosp,:idPrestaConsom,:prixPresta,:prixautreConsom,:qteConsom,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
			$resultatConsom->execute(array(
			'datehosp'=>$annee,
			'idPrestaConsom'=>$addConsom[$i],
			'prixPresta'=>$prixprestaConsom,
			'prixautreConsom'=>$prixautreConsom,
			'qteConsom'=>$qteConsom,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'id_uI'=>$id_uI,
			'idhospAdd'=>$idhospAdd,
			'idfacture'=>$idfacture
			)) or die( print_r($connexion->errorInfo()));

	}
	
}


if(isset($_POST['autreprestaConsom']))
{
	if($_POST['autreprestaConsom']!="")
	{		
		$id_categopresta=21;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		$idfacture="";
		
		$addConsomautre = array();
		$addConsomautreprix = array();
		$qteConsom = array();

		foreach($_POST['autreprestaConsom'] as $valeurConsom)
		{
			$addConsomautre[] = $valeurConsom;
			   
		}
		foreach($_POST['autreprixprestaConsom'] as $valeurConsomprix)
		{
			$addConsomautreprix[] = $valeurConsomprix;
			   
		}
		foreach($_POST['qteprestaConsom'] as $valeurConsomqte)
		{
			$qteConsom[] = $valeurConsomqte;
			   
		}
		
		for($i=0;$i<sizeof($addConsomautre);$i++)
		{
			
			if($addConsomautre[$i]!="")
			{
				if($qteConsom[$i]==0)
				{
					$qteConsom[$i]=1;
				}
				
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=21 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
				'idAutrePresta'=>$addConsomautre[$i]
				));
								
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
							
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
					'nompresta'=>nl2br($addConsomautre[$i]),
					'namepresta'=>nl2br($addConsomautre[$i]),
					'prixpresta'=>$addConsomautreprix[$i],
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

					$comptPrixprestaradio=$prixprestaconsom->rowCount();
					
					if($addConsomautreprix[$i]=="")
					{
						if($lignePrixprestaconsom=$prixprestaconsom->fetch())
						{
							$addConsomautreprix[$i]=$lignePrixprestaconsom->prixpresta;
						}
					}
					
					$searchConsom=$connexion->prepare('SELECT *FROM med_consom_hosp WHERE datehosp=:datehosp AND numero=:num AND (id_factureMedConsom="" OR id_factureMedConsom=0) AND id_prestationConsom LIKE \''.$lastIdPresta.'\' ');
					$searchConsom->execute(array(
					'datehosp'=>$annee,
					'num'=>$numero
					)) or die( print_r($connexion->errorInfo()));
					
					$searchConsom->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchConsom=$searchConsom->rowCount();
					
					if($comptSearchConsom!=0)				
					{
						if($ligneConsom=$searchConsom->fetch())
						{
							$updateQteMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.qteConsom=:qteConsom, mco.prixprestationConsom=:prixPresta WHERE mco.datehosp=:datehosp AND mco.numero=:num AND mco.id_prestationConsom LIKE \''.$lastIdPresta.'\'');
							
							$updateQteMedConsom->execute(array(
							'qteConsom'=>$ligneConsom->qteConsom+$qteConsom[$i],
							'num'=>$numero,
							'prixPresta'=>$addConsomautreprix[$i],
							'datehosp'=>$annee
							
							))or die( print_r($connexion->errorInfo()));
		
						}
						
					}else{
						$resultatConsom=$connexion->prepare('INSERT INTO med_consom_hosp (datehosp,id_prestationConsom,prixprestationConsom,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_uI,id_hospConsom,id_factureMedConsom) VALUES(:datehosp,:idPresta,:prixPresta,:qteConsom,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
						$resultatConsom->execute(array(
						'datehosp'=>$annee,
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$addConsomautreprix[$i],
						'qteConsom'=>$qteConsom[$i],
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'id_uI'=>$id_uI,
						'idhospAdd'=>$idhospAdd,
						'idfacture'=>$idfacture
						)) or die( print_r($connexion->errorInfo()));
					}
				}
			}
		}
	}
}


if(isset($_POST['checkprestaMedoc']))
{
	$addMedoc = array();

	$qteMedoc=1;
	$prixautreMedoc=0;
	$idfacture="";

	foreach($_POST['checkprestaMedoc'] as $valeurMedoc)
	{
		$addMedoc[] = $valeurMedoc;
	}
		
	for($i=0;$i<sizeof($addMedoc);$i++)
	{
		
		$resMedoc=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation="'.$addMedoc[$i].'" ORDER BY p.nompresta');
					
		$comptMedoc=$resMedoc->rowCount();
		
		if($comptMedoc==0)
		{
			$resMedoc=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation="'.$addMedoc[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaMedoc=$resMedoc->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaMedoc=$ligneprestaMedoc->prixpresta;
				
				// echo $comptMedoc.'_'.$addMedoc[$i].' ('.$prixprestaMedoc.')<br/>';
			}
			
				
			$resultatMedoc=$connexion->prepare('INSERT INTO med_medoc_hosp (datehosp,id_prestationMedoc,prixprestationMedoc,prixautreMedoc,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_uI,id_hospMedoc,id_factureMedMedoc) VALUES(:datehosp,:idPrestaMedoc,:prixPresta,:prixautreMedoc,:qteMedoc,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
			$resultatMedoc->execute(array(
			'datehosp'=>$annee,
			'idPrestaMedoc'=>$addMedoc[$i],
			'prixPresta'=>$prixprestaMedoc,
			'prixautreMedoc'=>$prixautreMedoc,
			'qteMedoc'=>$qteMedoc,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'id_uI'=>$id_uI,
			'idhospAdd'=>$idhospAdd,
			'idfacture'=>$idfacture
			)) or die( print_r($connexion->errorInfo()));
		
	}
	
}		


if(isset($_POST['autreprestaMedoc']))
{
	if($_POST['autreprestaMedoc']!='')
	{	
		$id_categopresta=22;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		$idfacture="";
		
		$addMedocautre = array();
		$addMedocautreprix = array();
		$qteMedoc = array();

		foreach($_POST['autreprestaMedoc'] as $valeurMedoc)
		{
			$addMedocautre[] = $valeurMedoc;
			   
		}
		foreach($_POST['autreprixprestaMedoc'] as $valeurMedocprix)
		{
			$addMedocautreprix[] = $valeurMedocprix;
			   
		}
		foreach($_POST['qteprestaMedoc'] as $valeurMedocqte)
		{
			$qteMedoc[] = $valeurMedocqte;
			   
		}
		
		for($i=0;$i<sizeof($addMedocautre);$i++)
		{			
			if($addMedocautre[$i]!="")
			{
				if($qteMedoc[$i]==0)
				{
					$qteMedoc[$i]=1;
				}
				
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=22 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
				'idAutrePresta'=>$addMedocautre[$i]
				));
								
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
							
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
					'nompresta'=>nl2br($addMedocautre[$i]),
					'namepresta'=>nl2br($addMedocautre[$i]),
					'prixpresta'=>$addMedocautreprix[$i],
					'id_categopresta'=>$id_categopresta,
					'id_souscategopresta'=>$id_souscategopresta,
					'mesure'=>$mesure,
					'statupresta'=>$statupresta
					)) or die( print_r($connexion->errorInfo()));
					
					$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
					
				}else{
					$ligneNomPresta=$searchNomPresta->fetch();
					
						if($addMedocautreprix[$i]!="")
						{	
							$updatePrixPrestaMedoc=$connexion->prepare('UPDATE '.$presta_assu.' SET prixpresta=:newprice WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
											
							$updatePrixPrestaMedoc->execute(array(
							'newprice'=>$addMedocautreprix[$i]
							))or die( print_r($connexion->errorInfo()));
								
						}
					
					$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
					
				}
				
				/*-------Put in med_medoc---------*/
			 
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
					
					if($addMedocautreprix[$i]=="")
					{
						if($lignePrixprestamedoc=$prixprestamedoc->fetch())
						{
							$addMedocautreprix[$i]=$lignePrixprestamedoc->prixpresta;
						}
					}
				
					$searchMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp WHERE datehosp=:datehosp AND (id_factureMedMedoc="" OR id_factureMedMedoc=0) AND numero=:num AND id_prestationMedoc LIKE \''.$lastIdPresta.'\' ');
					$searchMedoc->execute(array(
					'datehosp'=>$annee,
					'num'=>$numero
					)) or die( print_r($connexion->errorInfo()));
					
					$searchMedoc->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchMedoc=$searchMedoc->rowCount();
					
					if($comptSearchMedoc!=0)				
					{
						if($ligneMedoc=$searchMedoc->fetch())
						{
							
							$updateQteMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.qteMedoc=:qteMedoc, mdo.prixprestationMedoc=:prixPresta WHERE mdo.datehosp=:datehosp AND mdo.numero=:num AND mdo.id_prestationMedoc LIKE \''.$lastIdPresta.'\'');
							
							$updateQteMedMedoc->execute(array(
							'qteMedoc'=>$ligneMedoc->qteMedoc+$qteMedoc[$i],
							'num'=>$numero,
							'prixPresta'=>$addMedocautreprix[$i],
							'datehosp'=>$annee
							
							))or die( print_r($connexion->errorInfo()));
		
						}
						
					}else{
					
						$resultatMedoc=$connexion->prepare('INSERT INTO med_medoc_hosp (datehosp,id_prestationMedoc,prixprestationMedoc,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_uI,id_hospMedoc,id_factureMedMedoc) VALUES(:datehosp,:idPresta,:prixPresta,:qteMedoc,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
						$resultatMedoc->execute(array(
						'datehosp'=>$annee,
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$addMedocautreprix[$i],
						'qteMedoc'=>$qteMedoc[$i],
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'id_uI'=>$id_uI,
						'idhospAdd'=>$idhospAdd,
						'idfacture'=>$idfacture
						)) or die( print_r($connexion->errorInfo()));
					}
				}
			}
		}
	}
}


if(isset($_POST['checkprestaKine']))
{
	$addKine = array();

	$qteKine=1;
	$prixautreKine=0;
	$idfacture="";

	foreach($_POST['checkprestaKine'] as $valeurKine)
	{
		$addKine[] = $valeurKine;
	}
		
	for($i=0;$i<sizeof($addKine);$i++)
	{
		
		$resKine=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=14 AND p.id_prestation="'.$addKine[$i].'" ORDER BY p.nompresta');
					
		$comptKine=$resKine->rowCount();
		
		if($comptKine==0)
		{
			$resKine=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=14 AND p.id_prestation="'.$addKine[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaKine=$resKine->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaKine=$ligneprestaKine->prixpresta;
				
				// echo $comptKine.'_'.$addKine[$i].' ('.$prixprestaKine.')<br/>';
			}
				
			// echo 'INSERT INTO med_kine_hosp (datehosp,id_prestationKine,prixprestationKine,prixautrePrestaK,qteKine,id_assuKine,insupercentKine,numero,id_uM,id_uI,id_hospKine) VALUES('.$datehosp.','.$addKine[$i].','.$prixprestaKine.','.$prixautreKine.','.$qteKine.','.$idassu.','.$bill.','.$numero.','.$id_uM.','.$id_uI.','.$idhospAdd.')';
				
				
				
			$resultatKine=$connexion->prepare('INSERT INTO med_kine_hosp (datehosp,id_prestationKine,prixprestationKine,prixautrePrestaK,qteKine,id_assuKine,insupercentKine,numero,id_uM,id_uI,id_hospKine) VALUES(:datehosp,:idPrestaKine,:prixPresta,:prixautreKine,:qteKine,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd)');
			$resultatKine->execute(array(
			'datehosp'=>$annee,
			'idPrestaKine'=>$addKine[$i],
			'prixPresta'=>$prixprestaKine,
			'prixautreKine'=>$prixautreKine,
			'qteKine'=>$qteKine,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'id_uI'=>$id_uI,
			'idhospAdd'=>$idhospAdd
			)) or die( print_r($connexion->errorInfo()));
		
	}
	
}		


if(isset($_POST['autreprestaKine']))
{
	if($_POST['autreprestaKine']!='')
	{	
		$id_categopresta=14;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		$idfacture="";
		
		$addKineautre = array();
		$addKineautreprix = array();
		$qteKine = array();

		foreach($_POST['autreprestaKine'] as $valeurKine)
		{
			$addKineautre[] = $valeurKine;
			   
		}
		foreach($_POST['autreprixprestaKine'] as $valeurKineprix)
		{
			$addKineautreprix[] = $valeurKineprix;
			   
		}
		foreach($_POST['qteprestaKine'] as $valeurKineqte)
		{
			$qteKine[] = $valeurKineqte;
			   
		}
		
		for($i=0;$i<sizeof($addKineautre);$i++)
		{			
			if($addKineautre[$i]!="")
			{
				if($qteKine[$i]==0)
				{
					$qteKine[$i]=1;
				}
				
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=14 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
				'idAutrePresta'=>$addKineautre[$i]
				));
								
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
							
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
					'nompresta'=>nl2br($addKineautre[$i]),
					'namepresta'=>nl2br($addKineautre[$i]),
					'prixpresta'=>$addKineautreprix[$i],
					'id_categopresta'=>$id_categopresta,
					'id_souscategopresta'=>$id_souscategopresta,
					'mesure'=>$mesure,
					'statupresta'=>$statupresta
					)) or die( print_r($connexion->errorInfo()));
					
					$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
					
				}else{
					$ligneNomPresta=$searchNomPresta->fetch();
					
						if($addKineautreprix[$i]!="")
						{	
							$updatePrixPrestaKine=$connexion->prepare('UPDATE '.$presta_assu.' SET prixpresta=:newprice WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
											
							$updatePrixPrestaKine->execute(array(
							'newprice'=>$addKineautreprix[$i]
							))or die( print_r($connexion->errorInfo()));
								
						}
					
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
					
					if($addKineautreprix[$i]=="")
					{
						if($lignePrixprestakine=$prixprestakine->fetch())
						{
							$addKineautreprix[$i]=$lignePrixprestakine->prixpresta;
						}
					}
				
					$searchKine=$connexion->prepare('SELECT *FROM med_kine_hosp WHERE datehosp=:datehosp AND (id_factureMedKine="" OR id_factureMedKine=0) AND numero=:num AND id_prestationKine LIKE \''.$lastIdPresta.'\' ');
					$searchKine->execute(array(
					'datehosp'=>$annee,
					'num'=>$numero
					)) or die( print_r($connexion->errorInfo()));
					
					$searchKine->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchKine=$searchKine->rowCount();
					
					if($comptSearchKine!=0)				
					{
						if($ligneKine=$searchKine->fetch())
						{
							
							$updateQteMedKine=$connexion->prepare('UPDATE med_kine_hosp mdo SET mdo.qteKine=:qteKine, mdo.prixprestationKine=:prixPresta WHERE mdo.datehosp=:datehosp AND mdo.numero=:num AND mdo.id_prestationKine LIKE \''.$lastIdPresta.'\'');
							
							$updateQteMedKine->execute(array(
							'qteKine'=>$ligneKine->qteKine+$qteKine[$i],
							'num'=>$numero,
							'prixPresta'=>$addKineautreprix[$i],
							'datehosp'=>$annee
							
							))or die( print_r($connexion->errorInfo()));
		
						}
						
					}else{
					
						$resultatKine=$connexion->prepare('INSERT INTO med_kine_hosp (datehosp,id_prestationKine,prixprestationKine,qteKine,id_assuKine,insupercentKine,numero,id_uM,id_uI,id_hospKine,id_factureMedKine) VALUES(:datehosp,:idPresta,:prixPresta,:qteKine,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
						$resultatKine->execute(array(
						'datehosp'=>$annee,
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$addKineautreprix[$i],
						'qteKine'=>$qteKine[$i],
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'id_uI'=>$id_uI,
						'idhospAdd'=>$idhospAdd,
						'idfacture'=>$idfacture
						)) or die( print_r($connexion->errorInfo()));
					}
				}
			}
		}
	}
}


if(isset($_POST['checkprestaOrtho']))
{
	$addOrtho = array();

	$qteOrtho=1;
	$prixautreOrtho=0;

	foreach($_POST['checkprestaOrtho'] as $valeurOrtho)
	{
		$addOrtho[] = $valeurOrtho;
	}
		
	for($i=0;$i<sizeof($addOrtho);$i++)
	{
		
		$resOrtho=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation="'.$addOrtho[$i].'" ORDER BY p.nompresta');
					
		$comptOrtho=$resOrtho->rowCount();
		
		if($comptOrtho==0)
		{
			$resOrtho=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation="'.$addOrtho[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaOrtho=$resOrtho->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaOrtho=$ligneprestaOrtho->prixpresta;
				
				// echo $comptOrtho.'_'.$addOrtho[$i].' ('.$prixprestaOrtho.')<br/>';
			}
			
				
			$resultatOrtho=$connexion->prepare('INSERT INTO med_ortho_hosp (datehosp,id_prestationOrtho,prixprestationOrtho,prixautrePrestaO,qteOrtho,id_assuOrtho,insupercentOrtho,numero,id_uM,id_uI,id_hospOrtho) VALUES(:datehosp,:idPrestaOrtho,:prixPresta,:prixautreOrtho,:qteOrtho,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd)');
			$resultatOrtho->execute(array(
			'datehosp'=>$annee,
			'idPrestaOrtho'=>$addOrtho[$i],
			'prixPresta'=>$prixprestaOrtho,
			'prixautreOrtho'=>$prixautreOrtho,
			'qteOrtho'=>$qteOrtho,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'id_uI'=>$id_uI,
			'idhospAdd'=>$idhospAdd
			)) or die( print_r($connexion->errorInfo()));
		
	}
	
}		


if(isset($_POST['autreprestaOrtho']))
{
	if($_POST['autreprestaOrtho']!='')
	{	
		$id_categopresta=23;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		$idfacture="";
		
		$addOrthoautre = array();
		$addOrthoautreprix = array();
		$qteOrtho = array();

		foreach($_POST['autreprestaOrtho'] as $valeurOrtho)
		{
			$addOrthoautre[] = $valeurOrtho;
			   
		}
		foreach($_POST['autreprixprestaOrtho'] as $valeurOrthoprix)
		{
			$addOrthoautreprix[] = $valeurOrthoprix;
			   
		}
		foreach($_POST['qteprestaOrtho'] as $valeurOrthoqte)
		{
			$qteOrtho[] = $valeurOrthoqte;
			   
		}
		
		for($i=0;$i<sizeof($addOrthoautre);$i++)
		{			
			if($addOrthoautre[$i]!="")
			{
				if($qteOrtho[$i]==0)
				{
					$qteOrtho[$i]=1;
				}
				
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=23 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
				'idAutrePresta'=>$addOrthoautre[$i]
				));
								
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
							
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
					'nompresta'=>nl2br($addOrthoautre[$i]),
					'namepresta'=>nl2br($addOrthoautre[$i]),
					'prixpresta'=>$addOrthoautreprix[$i],
					'id_categopresta'=>$id_categopresta,
					'id_souscategopresta'=>$id_souscategopresta,
					'mesure'=>$mesure,
					'statupresta'=>$statupresta
					)) or die( print_r($connexion->errorInfo()));
					
					$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
					
				}else{
					$ligneNomPresta=$searchNomPresta->fetch();
					
						if($addOrthoautreprix[$i]!="")
						{	
							$updatePrixPrestaOrtho=$connexion->prepare('UPDATE '.$presta_assu.' SET prixpresta=:newprice WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
											
							$updatePrixPrestaOrtho->execute(array(
							'newprice'=>$addOrthoautreprix[$i]
							))or die( print_r($connexion->errorInfo()));
								
						}
					
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
					
					if($addOrthoautreprix[$i]=="")
					{
						if($lignePrixprestaortho=$prixprestaortho->fetch())
						{
							$addOrthoautreprix[$i]=$lignePrixprestaortho->prixpresta;
						}
					}
				
					$searchOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp WHERE datehosp=:datehosp AND (id_factureMedOrtho="" OR id_factureMedOrtho=0) AND numero=:num AND id_prestationOrtho LIKE \''.$lastIdPresta.'\' ');
					$searchOrtho->execute(array(
					'datehosp'=>$annee,
					'num'=>$numero
					)) or die( print_r($connexion->errorInfo()));
					
					$searchOrtho->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchOrtho=$searchOrtho->rowCount();
					
					if($comptSearchOrtho!=0)				
					{
						if($ligneOrtho=$searchOrtho->fetch())
						{
							
							$updateQteMedOrtho=$connexion->prepare('UPDATE med_ortho_hosp mdo SET mdo.qteOrtho=:qteOrtho, mdo.prixprestationOrtho=:prixPresta WHERE mdo.datehosp=:datehosp AND mdo.numero=:num AND mdo.id_prestationOrtho LIKE \''.$lastIdPresta.'\'');
							
							$updateQteMedOrtho->execute(array(
							'qteOrtho'=>$ligneOrtho->qteOrtho+$qteOrtho[$i],
							'num'=>$numero,
							'prixPresta'=>$addOrthoautreprix[$i],
							'datehosp'=>$annee
							
							))or die( print_r($connexion->errorInfo()));
		
						}
						
					}else{
					
						$resultatOrtho=$connexion->prepare('INSERT INTO med_ortho_hosp (datehosp,id_prestationOrtho,prixprestationOrtho,qteOrtho,id_assuOrtho,insupercentOrtho,numero,id_uM,id_uI,id_hospOrtho,id_factureMedOrtho) VALUES(:datehosp,:idPresta,:prixPresta,:qteOrtho,:idassu,:bill,:numero,:id_uM,:id_uI,:idhospAdd,:idfacture)');
						$resultatOrtho->execute(array(
						'datehosp'=>$annee,
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$addOrthoautreprix[$i],
						'qteOrtho'=>$qteOrtho[$i],
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'id_uI'=>$id_uI,
						'idhospAdd'=>$idhospAdd,
						'idfacture'=>$idfacture
						)) or die( print_r($connexion->errorInfo()));
					}
				}
			}
		}
	}
}


	echo '<script text="text/javascript">document.location.href="categoriesbill_hosp_medecin.php?num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$id_uM.'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&back=ok"</script>';

	
/* 	echo $_POST['autreprestaServ'];
	echo $_POST['autreprestaInf'];
	echo $_POST['autreprestaSurge'];
	echo $_POST['autreprestaLab'];
	echo $_POST['autreprestaRad'];
	echo $_POST['autreprestaKine'];
	echo $_POST['autreprestaConsom'];
	echo $_POST['autreprestaMedoc']; 
	echo $_POST['autreprestaOrtho']; 
	echo $_POST['typeconsu'].'<br/>';
	echo $_POST['medecin'].'<br/>';
 */
	
?>