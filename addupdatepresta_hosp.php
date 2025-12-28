<?php
session_start();
include("connect.php");
include("connectLangues.php");


	$datefacture = $_GET['datefacture'];

	$annee = date('Y').'-'.date('m').'-'.date('d');
	date('Y-m-d', strtotime($annee));

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
	$datefacture=$_GET['datefacture'];
	$numero=$_GET['num'];
	$id_uM=$_POST['medecins'];
	$id_uI=NULL;
	$id_uCoor=$_SESSION['id'];
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


if(isset($_POST['checkprestaSurge']))
{
	$addSurge = array();
	$surgefait=0;
	
	foreach($_POST['checkprestaSurge'] as $valeurSurge)
	{
		$addSurge[] = $valeurSurge;
	}
	
	for($i=0;$i<sizeof($addSurge);$i++)
	{		
		$resSurge=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=4 AND p.id_prestation="'.$addSurge[$i].'" ORDER BY p.nompresta');
		
		$comptSurge=$resSurge->rowCount();
		
		if($ligneprestaSurge=$resSurge->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaSurge=$ligneprestaSurge->prixpresta;
			$prixprestaSurgeCCO=$ligneprestaSurge->prixprestaCCO;
			
			// echo $addSurge[$i].' ('.$ligneprestaSurge->prixpresta.')<br/>';
		}
		
		
		$resultatSurge=$connexion->prepare('INSERT INTO med_surge_hosp (datehosp,id_prestationSurge,prixprestationSurge,prixprestationSurgeCCO,surgefait,id_assuSurge,insupercentSurge,numero,id_uM,id_hospSurge) VALUES(:datehosp,:idPrestaSurge,:prixPresta,:prixPrestaCCO,:surgefait,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
		
		$resultatSurge->execute(array(
			'datehosp'=>$annee,
			'idPrestaSurge'=>$addSurge[$i],
			'prixPresta'=>$prixprestaSurge,
			'prixPrestaCCO'=>$prixprestaSurgeCCO,
			'surgefait'=>$surgefait,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idhospAdd'=>$idhospAdd
		
		)) or die( print_r($connexion->errorInfo()));
		
		
		
	}
	
}


if(isset($_POST['autreprestaSurge']))
{
	$surgefait=0;
	$idAutrePresta=$_POST['autreprestaSurge'];
	
	if($idAutrePresta != "")
	{
		$id_categopresta=4;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		
		$addSurgeautre = array();
		$addSurgeautreprix = array();
		
		foreach($_POST['autreprestaSurge'] as $valeurSurge)
		{
			$addSurgeautre[] = $valeurSurge;
			
		}
		foreach($_POST['autreprixprestaSurge'] as $valeurSurgeprix)
		{
			$addSurgeautreprix[] = $valeurSurgeprix;
			
		}
		foreach($_POST['autreprixprestaSurgeCCO'] as $valeurSurgeprixCCO)
		{
			$addSurgeautreprixCCO[] = $valeurSurgeprixCCO;
			
		}
		
		for($i=0;$i<sizeof($addSurgeautre);$i++)
		{
			if($addSurgeautre[$i]!="")
			{
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=4 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
					'idAutrePresta'=>$addSurgeautre[$i]
				));
				
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
				
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
						'nompresta'=>nl2br($addSurgeautre[$i]),
						'namepresta'=>nl2br($addSurgeautre[$i]),
						'prixpresta'=>$addSurgeautreprix[$i],
						'prixprestaCCO'=>$addSurgeautreprixCCO[$i],
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
				
				/*-------Put in med_surge_hosp---------*/
				
				$searchLastId->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneLastId=$searchLastId->fetch())
				{
					
					$lastIdPresta=$ligneLastId->id_prestation;
					
					$prixprestasurge=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$prixprestasurge->execute(array(
						'idPresta'=>$lastIdPresta
					));
					
					$prixprestasurge->setFetchMode(PDO::FETCH_OBJ);
					
					$comptPrixprestaconsu=$prixprestasurge->rowCount();
					
					if($lignePrixprestasurge=$prixprestasurge->fetch())
					{
						$prixSurge=$lignePrixprestasurge->prixpresta;
						$prixSurgeCCO=$lignePrixprestasurge->prixprestaCCO;
					}
					
					$resultat=$connexion->prepare('INSERT INTO med_surge_hosp (datehosp,id_prestationSurge,prixprestationSurge,prixprestationSurgeCCO,surgefait,id_assuSurge,insupercentSurge,numero,id_uM,id_hospSurge) VALUES(:datehosp,:idPresta,:prixPresta,:prixPrestaCCO,:surgefait,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
					$resultat->execute(array(
						'datehosp'=>$annee,
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixSurge,
						'prixPrestaCCO'=>$prixSurgeCCO,
						'surgefait'=>$surgefait,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
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
	$soinsfait=0;

	foreach($_POST['checkprestaInf'] as $valeurInf)
	{
		$addInf[] = $valeurInf;
	}
	
	for($i=0;$i<sizeof($addInf);$i++)
	{
		
		$resInf=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=3 AND p.id_prestation="'.$addInf[$i].'" ORDER BY p.nompresta');
		
		$comptInf=$resInf->rowCount();
		
		if($ligneprestaInf=$resInf->fetch(PDO::FETCH_OBJ))//on recupere la liste des �l�ments
		{
			$prixprestaInf=$ligneprestaInf->prixpresta;
			$prixprestaInfCCO=$ligneprestaInf->prixprestaCCO;
			
			// echo $addInf[$i].' ('.$ligneprestaInf->prixpresta.')<br/>';
		}
		
		
		$resultatInf=$connexion->prepare('INSERT INTO med_inf_hosp (datehosp,id_prestation,prixprestation,prixprestationCCO,soinsfait,id_assuInf,insupercentInf,numero,id_uM,id_hospInf) VALUES(:datehosp,:idPrestaInf,:prixPresta,:prixPrestaCCO,:soinsfait,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
		
		$resultatInf->execute(array(
		'datehosp'=>$annee,
		'idPrestaInf'=>$addInf[$i],
		'prixPresta'=>$prixprestaInf,
		'prixPrestaCCO'=>$prixprestaInfCCO,
		'soinsfait'=>$soinsfait,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idhospAdd'=>$idhospAdd
		
		)) or die( print_r($connexion->errorInfo()));

	}
	
}


if(isset($_POST['autreprestaInf']))
{
	$soinsfait=0;
	$idAutrePresta=$_POST['autreprestaInf'];
	
	if($idAutrePresta != "")
	{
		$id_categopresta=3;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		
		$addInfautre = array();
		$addInfautreprix = array();
		
		foreach($_POST['autreprestaInf'] as $valeurInf)
		{
			$addInfautre[] = $valeurInf;
			
		}
		foreach($_POST['autreprixprestaInf'] as $valeurInfprix)
		{
			$addInfautreprix[] = $valeurInfprix;
			
		}
		foreach($_POST['autreprixprestaInfCCO'] as $valeurInfprixCCO)
		{
			$addInfautreprixCCO[] = $valeurInfprixCCO;
			
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
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
						'nompresta'=>nl2br($addInfautre[$i]),
						'namepresta'=>nl2br($addInfautre[$i]),
						'prixpresta'=>$addInfautreprix[$i],
						'prixprestaCCO'=>$addInfautreprixCCO[$i],
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
					
					$lastIdPresta = $ligneLastId->id_prestation;
					
					$prixprestainf = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$prixprestainf->execute(array(
						'idPresta'=>$lastIdPresta
					));
					
					$prixprestainf->setFetchMode(PDO::FETCH_OBJ);
					
					$comptPrixprestaconsu = $prixprestainf->rowCount();
					
					if($lignePrixprestainf = $prixprestainf->fetch())
					{
						$prixInf = $lignePrixprestainf->prixpresta;
						$prixInfCCO = $lignePrixprestainf->prixprestaCCO;
					}
					
					$resultatInf = $connexion->prepare('INSERT INTO med_inf_hosp (datehosp,id_prestation,prixprestation,prixprestationCCO,soinsfait,datesoins,id_assuInf,insupercentInf,numero,id_uM,id_uI,id_uCoor,id_hospInf) VALUES(:datehosp,:idPresta,:prixPresta,:prixPrestaCCO,:soinsfait,:datesoins,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
					
					$resultatInf->execute(array(
						'datehosp'      => $annee,
						'idPresta'      => $lastIdPresta,
						'prixPresta'    => $prixInf,
						'prixPrestaCCO' => $prixInfCCO,
						'soinsfait'     => $soinsfait,
						'datesoins'     => $annee,
						'idassu'        => $idassu,
						'bill'          => $bill,
						'numero'        => $numero,
						'id_uM'         => $id_uM,
						'id_uI'         => $id_uI,
						'id_uCoor'      => $id_uCoor,
						'idhospAdd'     => $idhospAdd
					
					)) or die(print_r($connexion->errorInfo()));
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
	$dateresultat="0000-00-00";
	$idfacture=0;

	foreach($_POST['checkprestaLab'] as $valeurLab)
	{
		$addLab[] = $valeurLab;
	}
	
	
	for($i=0;$i<sizeof($addLab);$i++)
	{

		$resLab=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=12 AND p.id_prestation="'.$addLab[$i].'" ORDER BY p.nompresta');
		
		$comptLab=$resLab->rowCount();
		
		if($ligneprestaLab=$resLab->fetch(PDO::FETCH_OBJ))//on recupere la liste des �l�ments
		{
			$prixprestaLab=$ligneprestaLab->prixpresta;
			$prixprestaLabCCO=$ligneprestaLab->prixprestaCCO;

			// echo $addLab[$i].' ('.$prixprestaLab.')<br/><br/>';
		}


		$resultatLabo=$connexion->prepare('INSERT INTO med_labo_hosp (datehosp,id_prestationExa,prixprestationExa,prixprestationExaCCO,prixautreExamen,id_assuLab,insupercentLab,examenfait,dateresultats,numero,id_uM,id_hospLabo) VALUES(:datehosp,:idPrestaLab,:prixPresta,:prixPrestaCCO,:prixautreExa,:idassu,:bill,:examenfait,:dateresultat,:numero,:id_uM,:idhospAdd)');
		$resultatLabo->execute(array(
		'datehosp'=>$annee,
		'idPrestaLab'=>$addLab[$i],
		'prixPresta'=>$prixprestaLab,
		'prixPrestaCCO'=>$prixprestaLabCCO,
		'prixautreExa'=>$prixautreExa,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'examenfait'=>$examenfait,
		'dateresultat'=>$dateresultat,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idhospAdd'=>$idhospAdd
		)) or die( print_r($connexion->errorInfo()));

	}
}


if(isset($_POST['autreprestaLab']))
{
	$examenfait=0;
	$idfacture=0;
	$idAutreExam=$_POST['autreprestaLab'];
	
	if($idAutreExam != "")
	{
		$prixpresta=$_POST['autreprixprestaLab'];
		$id_categopresta=12;
		$id_souscategopresta=7;
		
		$mesure=NULL;
		$statupresta=0;
		
		$addLabautre = array();
		$addLabautreprix = array();
		
		foreach($_POST['autreprestaLab'] as $valeurLab)
		{
			$addLabautre[] = $valeurLab;
			
		}
		foreach($_POST['autreprixprestaLab'] as $valeurLabprix)
		{
			$addLabautreprix[] = $valeurLabprix;
			
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
					
					$lastIdPresta = $ligneLastId->id_prestation;
					
					$prixprestalabo = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$prixprestalabo->execute(array(
						'idPresta'=>$lastIdPresta
					));
					
					$prixprestalabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$comptPrixprestalabo = $prixprestalabo->rowCount();
					
					if($lignePrixprestalabo = $prixprestalabo->fetch())
					{
						$prixLabo = $lignePrixprestalabo->prixpresta;
						$prixLaboCCO = $lignePrixprestalabo->prixprestaCCO;
					}
					
					$resultatLab = $connexion->prepare('INSERT INTO med_labo_hosp (datehosp,id_prestationExa,prixprestationExa,prixprestationExaCCO,examenfait,id_assuLab,insupercentLab,numero,id_uM,id_uI,id_uCoor,id_hospLabo) VALUES(:datehosp,:idPresta,:prixPresta,:prixPrestaCCO,:examenfait,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
					
					$resultatLab->execute(array(
						'datehosp'      => $annee,
						'idPresta'      => $lastIdPresta,
						'prixPresta'    => $prixLabo,
						'prixPrestaCCO' => $prixLaboCCO,
						'examenfait'    => $examenfait,
						'idassu'        => $idassu,
						'bill'          => $bill,
						'numero'        => $numero,
						'id_uM'         => $id_uM,
						'id_uI'         => $id_uI,
						'id_uCoor'      => $id_uCoor,
						'idhospAdd'     => $idhospAdd
					)) or die(print_r($connexion->errorInfo()));
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
		
		if($ligneprestaRad=$resRad->fetch(PDO::FETCH_OBJ))
		{
			$prixprestaRad=$ligneprestaRad->prixpresta;
			$prixprestaRadCCO=$ligneprestaRad->prixprestaCCO;

			// echo $addRad[$i].' ('.$prixprestaRad.')<br/><br/>';
		}


		$resultatRadio=$connexion->prepare('INSERT INTO med_radio_hosp (datehosp,id_prestationRadio,prixprestationRadio,prixprestationRadioCCO,prixautreRadio,id_assuRad,insupercentRad,radiofait,dateradio,numero,id_uM,id_hospRadio) VALUES(:datehosp,:idPrestaRad,:prixPresta,:prixPrestaCCO,:prixautreRadio,:idassu,:bill,:radiofait,:dateradio,:numero,:id_uM,:idhospAdd)');
		$resultatRadio->execute(array(
		'datehosp'=>$annee,
		'idPrestaRad'=>$addRad[$i],
		'prixPresta'=>$prixprestaRad,
		'prixPrestaCCO'=>$prixprestaRadCCO,
		'prixautreRadio'=>$prixautreRadio,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'radiofait'=>$radiofait,
		'dateradio'=>$dateradio,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idhospAdd'=>$idhospAdd
		)) or die( print_r($connexion->errorInfo()));

	}
}


if(isset($_POST['autreprestaRad']))
{
	$radiofait=0;
	$idfacture=0;
	$idAutreRadio=$_POST['autreprestaRad'];
	
	if($idAutreRadio != "")
	{
		$prixpresta=$_POST['autreprixprestaRad'];
		$id_categopresta=13;
		$id_souscategopresta=10;
		
		$mesure=NULL;
		$statupresta=0;
		
		$addRadautre = array();
		$addRadautreprix = array();
		$addRadautreprixCCO = array();
		
		foreach($_POST['autreprestaRad'] as $valeurRad)
		{
			$addRadautre[] = $valeurRad;
			
		}
		foreach($_POST['autreprixprestaRad'] as $valeurRadprix)
		{
			$addRadautreprix[] = $valeurRadprix;
			
		}
		foreach($_POST['autreprixprestaRadCCO'] as $valeurRadprixCCO)
		{
			$addRadautreprixCCO[] = $valeurRadprixCCO;
			
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
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
						'nompresta'=>nl2br($addRadautre[$i]),
						'namepresta'=>nl2br($addRadautre[$i]),
						'prixpresta'=>$addRadautreprix[$i],
						'prixprestaCCO'=>$addRadautreprixCCO[$i],
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
					
					$lastIdPresta = $ligneLastId->id_prestation;
					
					$prixprestaradio = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$prixprestaradio->execute(array(
						'idPresta'=>$lastIdPresta
					));
					
					$prixprestaradio->setFetchMode(PDO::FETCH_OBJ);
					
					$comptPrixprestaradio = $prixprestaradio->rowCount();
					
					if($lignePrixprestaradio = $prixprestaradio->fetch())
					{
						$prixRadio = $lignePrixprestaradio->prixpresta;
						$prixRadioCCO = $lignePrixprestaradio->prixprestaCCO;
					}
					
					$resultatRad = $connexion->prepare('INSERT INTO med_radio_hosp (datehosp,id_prestationRadio,prixprestationRadio,prixprestationRadioCCO,radiofait,id_assuRad,insupercentRad,numero,id_uM,id_uI,id_uCoor,id_hospRadio) VALUES(:datehosp,:idPresta,:prixPresta,:prixPrestaCCO,:radiofait,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
					
					$resultatRad->execute(array(
						'datehosp'      => $annee,
						'idPresta'      => $lastIdPresta,
						'prixPresta'    => $prixRadio,
						'prixPrestaCCO' => $prixRadioCCO,
						'radiofait'     => $radiofait,
						'idassu'        => $idassu,
						'bill'          => $bill,
						'numero'        => $numero,
						'id_uM'         => $id_uM,
						'id_uI'         => $id_uI,
						'id_uCoor'      => $id_uCoor,
						'idhospAdd'     => $idhospAdd
					)) or die(print_r($connexion->errorInfo()));
				}
			}
		}
	}
}


if(isset($_POST['checkprestaKine']))
{
	$addKine = array();
	$kinefait=0;
	
	foreach($_POST['checkprestaKine'] as $valeurKine)
	{
		$addKine[] = $valeurKine;
	}
	
	for($i=0;$i<sizeof($addKine);$i++)
	{
		
		$resKine=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=14 AND p.id_prestation="'.$addKine[$i].'" ORDER BY p.nompresta');
		
		$comptKine=$resKine->rowCount();
		
		if($ligneprestaKine=$resKine->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaKine=$ligneprestaKine->prixpresta;
			$prixprestaKineCCO=$ligneprestaKine->prixprestaCCO;
			
			// echo $addKine[$i].' ('.$ligneprestaKine->prixpresta.')<br/>';
		}

//        echo 'INSERT INTO med_kine_hosp (datehosp,id_prestationKine,prixprestationKine,prixprestationKineCCO,kinefait,id_assuKine,insupercentKine,numero,id_uM,id_hospKine) VALUES('.$annee.','.$addKine[$i].','.$prixprestaKine.','.$prixprestaKineCCO.','.$kinefait.','.$idassu.','.$bill.','.$numero.','.$id_uM.','.$idhospAdd.') <br/>';
		
		
		$resultatKine=$connexion->prepare('INSERT INTO med_kine_hosp (datehosp,id_prestationKine,prixprestationKine,prixprestationKineCCO,kinefait,id_assuKine,insupercentKine,numero,id_uM,id_hospKine) VALUES(:datehosp,:idPrestaKine,:prixPresta,:prixPrestaCCO,:kinefait,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
		
		$resultatKine->execute(array(
			'datehosp'=>$annee,
			'idPrestaKine'=>$addKine[$i],
			'prixPresta'=>$prixprestaKine,
			'prixPrestaCCO'=>$prixprestaKineCCO,
			'kinefait'=>$kinefait,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idhospAdd'=>$idhospAdd
		
		)) or die( print_r($connexion->errorInfo()));
		
		
	}
	
}


if(isset($_POST['autreprestaKine']))
{
	$kinefait=0;
	$idAutrePresta=$_POST['autreprestaKine'];
	
	if($idAutrePresta != "")
	{
		$id_categopresta=14;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		
		$addKineautre = array();
		$addKineautreprix = array();
		
		foreach($_POST['autreprestaKine'] as $valeurKine)
		{
			$addKineautre[] = $valeurKine;
			
		}
		foreach($_POST['autreprixprestaKine'] as $valeurKineprix)
		{
			$addKineautreprix[] = $valeurKineprix;
			
		}
		foreach($_POST['autreprixprestaKineCCO'] as $valeurKineprixCCO)
		{
			$addKineautreprixCCO[] = $valeurKineprixCCO;
			
		}
		
		for($i=0;$i<sizeof($addKineautre);$i++)
		{
			if($addKineautre[$i]!="")
			{
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=14 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
					'idAutrePresta'=>$addKineautre[$i]
				));
				
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
				
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
						'nompresta'=>nl2br($addKineautre[$i]),
						'namepresta'=>nl2br($addKineautre[$i]),
						'prixpresta'=>$addKineautreprix[$i],
						'prixprestaCCO'=>$addKineautreprixCCO[$i],
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
				
				/*-------Put in med_kine_hosp---------*/
				
				$searchLastId->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneLastId=$searchLastId->fetch())
				{
					
					$lastIdPresta=$ligneLastId->id_prestation;
					
					$prixprestakine=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$prixprestakine->execute(array(
						'idPresta'=>$lastIdPresta
					));
					
					$prixprestakine->setFetchMode(PDO::FETCH_OBJ);
					
					$comptPrixprestaconsu=$prixprestakine->rowCount();
					
					if($lignePrixprestakine=$prixprestakine->fetch())
					{
						$prixKine=$lignePrixprestakine->prixpresta;
						$prixKineCCO=$lignePrixprestakine->prixprestaCCO;
					}
					
					$resultat=$connexion->prepare('INSERT INTO med_kine_hosp (datehosp,id_prestationKine,prixprestationKine,prixprestationKineCCO,kinefait,id_assuKine,insupercentKine,numero,id_uM,id_hospKine) VALUES(:datehosp,:idPresta,:prixPresta,:prixPrestaCCO,:kinefait,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
					$resultat->execute(array(
						'datehosp'=>date('Y-m-d', strtotime($annee)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixKine,
						'prixPrestaCCO'=>$prixKineCCO,
						'kinefait'=>$kinefait,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idhospAdd'=>$idhospAdd
					
					)) or die( print_r($connexion->errorInfo()));
					
				}
			}
		}
	}
}


if(isset($_POST['checkprestaOrtho']))
{
	$addOrtho = array();
	$orthofait=0;
	
	foreach($_POST['checkprestaOrtho'] as $valeurOrtho)
	{
		$addOrtho[] = $valeurOrtho;
	}
	
	for($i=0;$i<sizeof($addOrtho);$i++)
	{
		
		$resOrtho=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation="'.$addOrtho[$i].'" ORDER BY p.nompresta');
		
		$comptOrtho=$resOrtho->rowCount();
		
		if($ligneprestaOrtho=$resOrtho->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaOrtho=$ligneprestaOrtho->prixpresta;
			$prixprestaOrthoCCO=$ligneprestaOrtho->prixprestaCCO;
			
			// echo $addOrtho[$i].' ('.$ligneprestaOrtho->prixpresta.')<br/>';
		}
		
		
		$resultatOrtho=$connexion->prepare('INSERT INTO med_ortho_hosp (datehosp,id_prestationOrtho,prixprestationOrtho,prixprestationOrthoCCO,orthofait,id_assuOrtho,insupercentOrtho,numero,id_uM,id_hospOrtho) VALUES(:datehosp,:idPrestaOrtho,:prixPresta,:prixPrestaCCO,:orthofait,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
		
		$resultatOrtho->execute(array(
			'datehosp'=>$annee,
			'idPrestaOrtho'=>$addOrtho[$i],
			'prixPresta'=>$prixprestaOrtho,
			'prixPrestaCCO'=>$prixprestaOrthoCCO,
			'orthofait'=>$orthofait,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idhospAdd'=>$idhospAdd
		
		)) or die( print_r($connexion->errorInfo()));
		
		
		
	}
	
}


if(isset($_POST['autreprestaOrtho']))
{
	$orthofait=0;
	$idAutrePresta=$_POST['autreprestaOrtho'];
	
	if($idAutrePresta != "")
	{
		$id_categopresta=23;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		
		$addOrthoautre = array();
		$addOrthoautreprix = array();
		
		foreach($_POST['autreprestaOrtho'] as $valeurOrtho)
		{
			$addOrthoautre[] = $valeurOrtho;
			
		}
		foreach($_POST['autreprixprestaOrtho'] as $valeurOrthoprix)
		{
			$addOrthoautreprix[] = $valeurOrthoprix;
			
		}
		foreach($_POST['autreprixprestaOrthoCCO'] as $valeurOrthoprixCCO)
		{
			$addOrthoautreprixCCO[] = $valeurOrthoprixCCO;
			
		}
		
		for($i=0;$i<sizeof($addOrthoautre);$i++)
		{
			if($addOrthoautre[$i]!="")
			{
				$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=14 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
					'idAutrePresta'=>$addOrthoautre[$i]
				));
				
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta=$searchNomPresta->rowCount();
				
				if($comptNomPresta==0)
				{
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
						'nompresta'=>nl2br($addOrthoautre[$i]),
						'namepresta'=>nl2br($addOrthoautre[$i]),
						'prixpresta'=>$addOrthoautreprix[$i],
						'prixprestaCCO'=>$addOrthoautreprixCCO[$i],
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
				
				/*-------Put in med_ortho_hosp---------*/
				
				$searchLastId->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneLastId=$searchLastId->fetch())
				{
					
					$lastIdPresta=$ligneLastId->id_prestation;
					
					$prixprestaortho=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$prixprestaortho->execute(array(
						'idPresta'=>$lastIdPresta
					));
					
					$prixprestaortho->setFetchMode(PDO::FETCH_OBJ);
					
					$comptPrixprestaconsu=$prixprestaortho->rowCount();
					
					if($lignePrixprestaortho=$prixprestaortho->fetch())
					{
						$prixOrtho=$lignePrixprestaortho->prixpresta;
						$prixOrthoCCO=$lignePrixprestaortho->prixprestaCCO;
					}
					
					$resultat=$connexion->prepare('INSERT INTO med_ortho_hosp (datehosp,id_prestationOrtho,prixprestationOrtho,prixprestationOrthoCCO,orthofait,id_assuOrtho,insupercentOrtho,numero,id_uM,id_hospOrtho) VALUES(:datehosp,:idPresta,:prixPresta,:prixPrestaCCO,:orthofait,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
					$resultat->execute(array(
						'datehosp'=>date('Y-m-d', strtotime($annee)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixOrtho,
						'prixPrestaCCO'=>$prixOrthoCCO,
						'orthofait'=>$orthofait,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idhospAdd'=>$idhospAdd
					
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
	$prixautreConsomCCO=0;
	$qteConsom=1;
	$idfacture=0;
	
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
			$prixprestaConsomCCO=$ligneprestaConsom->prixprestaCCO;
			
			// echo $comptConsom.'_'.$addConsom[$i].' ('.$prixprestaConsom.')<br/>';
		}
		
		
		$resultatConsom=$connexion->prepare('INSERT INTO med_consom_hosp (datehosp,id_prestationConsom,prixprestationConsom,prixprestationConsomCCO,prixautreConsom,prixautreConsomCCO,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_hospConsom) VALUES(:datehosp,:idPrestaConsom,:prixPresta,:prixPrestaCCO,:prixautreConsom,:prixautreConsomCCO,:qteConsom,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
		$resultatConsom->execute(array(
			'datehosp'=>$annee,
			'idPrestaConsom'=>$addConsom[$i],
			'prixPresta'=>$prixprestaConsom,
			'prixPrestaCCO'=>$prixprestaConsomCCO,
			'prixautreConsom'=>$prixautreConsom,
			'prixautreConsomCCO'=>$prixautreConsomCCO,
			'qteConsom'=>$qteConsom,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idhospAdd'=>$idhospAdd
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
		
		$addConsomautre = array();
		$addConsomautreprix = array();
		$addConsomautreprixCCO = array();
		$qteConsom = array();
		
		foreach($_POST['autreprestaConsom'] as $valeurConsom)
		{
			$addConsomautre[] = $valeurConsom;
			
		}
		foreach($_POST['autreprixprestaConsom'] as $valeurConsomprix)
		{
			$addConsomautreprix[] = $valeurConsomprix;
			
		}
		foreach($_POST['autreprixprestaConsomCCO'] as $valeurConsomprixCCO)
		{
			$addConsomautreprixCCO[] = $valeurConsomprixCCO;
			
		}
		foreach($_POST['qteprestaConsom'] as $valeurConsomqte)
		{
			$qteConsom[] = $valeurConsomqte;
			
		}
		
		for($i=0;$i<sizeof($addConsomautre);$i++)
		{
			
			if($addConsomautre[$i] != "")
			{
				if($qteConsom[$i] == 0)
				{
					$qteConsom[$i] = 1;
				}
				
				$searchNomPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' WHERE id_categopresta=21 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
				$searchNomPresta->execute(array(
					'idAutrePresta' => $addConsomautre[$i]
				));
				
				$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
				
				$comptNomPresta = $searchNomPresta->rowCount();
				
				if($comptNomPresta == 0)
				{
					$insertNewPresta = $connexion->prepare('INSERT INTO ' . $presta_assu . ' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
						'nompresta'           => nl2br($addConsomautre[$i]),
						'namepresta'          => nl2br($addConsomautre[$i]),
						'prixpresta'          => $addConsomautreprix[$i],
						'prixprestaCCO'       => $addConsomautreprixCCO[$i],
						'id_categopresta'     => $id_categopresta,
						'id_souscategopresta' => $id_souscategopresta,
						'mesure'              => $mesure,
						'statupresta'         => $statupresta
					)) or die(print_r($connexion->errorInfo()));
					
					$searchLastId = $connexion->query('SELECT *FROM ' . $presta_assu . ' ORDER BY id_prestation DESC LIMIT 1');
					
				}else{
					$ligneNomPresta = $searchNomPresta->fetch();
					
					$searchLastId = $connexion->query('SELECT *FROM ' . $presta_assu . ' WHERE id_prestation=' . $ligneNomPresta->id_prestation . '');
					
				}
				
				/*-------Put in med_consom---------*/
				
				$searchLastId->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneLastId = $searchLastId->fetch())
				{
					
					$lastIdPresta = $ligneLastId->id_prestation;
					
					$prixprestaconsom = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$prixprestaconsom->execute(array(
						'idPresta' => $lastIdPresta
					));
					
					$prixprestaconsom->setFetchMode(PDO::FETCH_OBJ);
					
					$comptPrixprestaradio = $prixprestaconsom->rowCount();
					
					if($addConsomautreprix[$i] == "")
					{
						if($lignePrixprestaconsom = $prixprestaconsom->fetch())
						{
							$addConsomautreprix[$i] = $lignePrixprestaconsom->prixpresta;
							$addConsomautreprixCCO[$i] = $lignePrixprestaconsom->prixprestaCCO;
						}
					}

// echo 'INSERT INTO med_consom (datehosp,id_prestationConsom,prixprestationConsom,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_hospConsom) VALUES('.date('Y-m-d', strtotime($annee)).','.$lastIdPresta.','.$addConsomautreprix[$i].','.$qteConsom[$i].','.$idassu.','.$bill.','.nl2br($numero).','.nl2br($id_uM).','.nl2br($idhospAdd).')<br/>';
					
					$resultat = $connexion->prepare('INSERT INTO med_consom_hosp (datehosp,id_prestationConsom,prixprestationConsom,prixprestationConsomCCO,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_hospConsom) VALUES(:datehosp,:idPresta,:prixPresta,:prixPrestaCCO,:qteConsom,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
					$resultat->execute(array(
						'datehosp'      => $annee,
						'idPresta'      => $lastIdPresta,
						'prixPresta'    => $addConsomautreprix[$i],
						'prixPrestaCCO' => $addConsomautreprixCCO[$i],
						'qteConsom'     => $qteConsom[$i],
						'idassu'        => $idassu,
						'bill'          => $bill,
						'numero'        => nl2br($numero),
						'id_uM'         => nl2br($id_uM),
						'idhospAdd'    => nl2br($idhospAdd)
					)) or die(print_r($connexion->errorInfo()));
				}
			}
		}
	}
}


if(isset($_POST['checkprestaMedoc']))
{
	$addMedoc = array();
	
	$prixautreMedoc=0;
	$prixautreMedocCCO=0;
	$qteMedoc=1;
	$idfacture=0;

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
			$resMedoc=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation="'.$addMedoc[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaMedoc=$resMedoc->fetch(PDO::FETCH_OBJ))
			{
				$prixprestaMedoc=$ligneprestaMedoc->prixpresta;
				$prixprestaMedocCCO=$ligneprestaMedoc->prixprestaCCO;
				
				// echo $comptMedoc.'_'.$addMedoc[$i].' ('.$prixprestaMedoc.')<br/>';
			}
		
		
		$resultatMedoc=$connexion->prepare('INSERT INTO med_medoc_hosp (datehosp,id_prestationMedoc,prixprestationMedoc,prixprestationMedocCCO,prixautreMedoc,prixautreMedocCCO,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_hospMedoc) VALUES(:datehosp,:idPrestaMedoc,:prixPresta,:prixPrestaCCO,:prixautreMedoc,:prixautreMedocCCO,:qteMedoc,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
			$resultatMedoc->execute(array(
			'datehosp'=>$annee,
			'idPrestaMedoc'=>$addMedoc[$i],
			'prixPresta'=>$prixprestaMedoc,
			'prixPrestaCCO'=>$prixprestaMedocCCO,
			'prixautreMedoc'=>$prixautreMedoc,
			'prixautreMedocCCO'=>$prixautreMedocCCO,
			'qteMedoc'=>$qteMedoc,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idhospAdd'=>$idhospAdd
			)) or die( print_r($connexion->errorInfo()));
		
	}
	
}

if(isset($_POST['autreprestaMedoc']))
{
	if($_POST['autreprestaMedoc']!="")
	{
		$id_categopresta=22;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		
		$addMedocautre = array();
		$addMedocautreprix = array();
		$addMedocautreprixCCO = array();
		$qteMedoc = array();
		
		foreach($_POST['autreprestaMedoc'] as $valeurMedoc)
		{
			$addMedocautre[] = $valeurMedoc;
			
		}
		foreach($_POST['autreprixprestaMedoc'] as $valeurMedocprix)
		{
			$addMedocautreprix[] = $valeurMedocprix;
			
		}
		foreach($_POST['autreprixprestaMedocCCO'] as $valeurMedocprixCCO)
		{
			$addMedocautreprixCCO[] = $valeurMedocprixCCO;
			
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
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
						'nompresta'=>nl2br($addMedocautre[$i]),
						'namepresta'=>nl2br($addMedocautre[$i]),
						'prixpresta'=>$addMedocautreprix[$i],
						'prixprestaCCO'=>$addMedocautreprixCCO[$i],
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
					
					$comptPrixprestaradio=$prixprestamedoc->rowCount();
					
					if($addMedocautreprix[$i]=="")
					{
						if($lignePrixprestamedoc=$prixprestamedoc->fetch())
						{
							$addMedocautreprix[$i]=$lignePrixprestamedoc->prixpresta;
							$addMedocautreprixCCO[$i]=$lignePrixprestamedoc->prixprestaCCO;
						}
					}
					
					$resultat=$connexion->prepare('INSERT INTO med_medoc_hosp (datehosp,id_prestationMedoc,prixprestationMedoc,prixprestationMedocCCO,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_hospMedoc) VALUES(:datehosp,:idPresta,:prixPresta,:prixPrestaCCO,:qteMedoc,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
					$resultat->execute(array(
						'datehosp'=>$annee,
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$addMedocautreprix[$i],
						'prixPrestaCCO'=>$addMedocautreprixCCO[$i],
						'qteMedoc'=>$qteMedoc[$i],
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>nl2br($numero),
						'id_uM'=>nl2br($id_uM),
						'idhospAdd'=>nl2br($idhospAdd)
					)) or die( print_r($connexion->errorInfo()));
				}
			}
		}
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
		
		$resServ=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_prestation="'.$add[$i].'" ORDER BY p.nompresta');
		
		$comptServ=$resServ->rowCount();
		
		if($ligneprestaServ=$resServ->fetch(PDO::FETCH_OBJ))//on recupere la liste des �l�ments
		{
			$prixprestaServ=$ligneprestaServ->prixpresta;
			$prixprestaServCCO=$ligneprestaServ->prixprestaCCO;
			
			// echo $add[$i].' ('.$ligneprestaServ->prixpresta.')<br/>';
		}
		
		$resultatServ=$connexion->prepare('INSERT INTO med_consult_hosp (datehosp,id_prestationConsu,prixprestationConsu,prixprestationConsuCCO,id_assuServ,insupercentServ,numero,id_uM,id_hospMed) VALUES(:datehosp,:idPrestaConsu,:prixPrestaConsu,:prixPrestaConsuCCO,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
		$resultatServ->execute(array(
			'datehosp'=>$annee,
			'idPrestaConsu'=>$add[$i],
			'prixPrestaConsu'=>$prixprestaServ,
			'prixPrestaConsuCCO'=>$prixprestaServCCO,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
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
		$addautreprixCCO = array();
		
		foreach($_POST['autreprestaServ'] as $valeur)
		{
			$addautre[] = $valeur;
			
		}
		foreach($_POST['autreprixprestaServ'] as $valeurprix)
		{
			$addautreprix[] = $valeurprix;
			
		}
		foreach($_POST['autreprixprestaServCCO'] as $valeurprixCCO)
		{
			$addautreprixCCO[] = $valeurprixCCO;
			
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
					$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
					$insertNewPresta->execute(array(
						'nompresta'=>nl2br($addautre[$i]),
						'namepresta'=>nl2br($addautre[$i]),
						'prixpresta'=>$addautreprix[$i],
						'prixprestaCCO'=>$addautreprixCCO[$i],
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
					
					$lastIdPresta = $ligneLastId->id_prestation;
					
					$prixprestaconsu = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$prixprestaconsu->execute(array(
						'idPresta'=>$lastIdPresta
					));
					
					$prixprestaconsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$comptPrixprestaconsu = $prixprestaconsu->rowCount();
					
					if($lignePrixprestaconsu = $prixprestaconsu->fetch())
					{
						$prixConsu = $lignePrixprestaconsu->prixpresta;
						$prixConsuCCO = $lignePrixprestaconsu->prixprestaCCO;
					}
					
					$resultatServ = $connexion->prepare('INSERT INTO med_consult_hosp (datehosp,id_prestationConsu,prixprestationConsu,prixprestationConsuCCO,id_assuServ,insupercentServ,numero,id_uM,id_uI,id_uCoor,id_hospMed) VALUES(:datehosp,:idPrestaConsu,:prixPrestaConsu,:prixPrestaConsuCCO,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
					$resultatServ->execute(array(
						'datehosp'           => $annee,
						'idPrestaConsu'      => $lastIdPresta,
						'prixPrestaConsu'    => $prixConsu,
						'prixPrestaConsuCCO' => $prixConsuCCO,
						'idassu'             => $idassu,
						'bill'               => $bill,
						'numero'             => $numero,
						'id_uM'              => $id_uM,
						'id_uI'              => $id_uI,
						'id_uCoor'           => $id_uCoor,
						'idhospAdd'          => $idhospAdd
					)) or die(print_r($connexion->errorInfo()));
					
				}
			}
		}
	}
}

	
	
	echo '<script text="text/javascript">document.location.href="categoriesbill_hospmodifier.php?num='.$_GET['num'].'&manager='.$_GET['manager'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$id_uM.'&datehosp='.$_GET['datehosp'].'&datefacture='.$_GET['datefacture'].'&idassu='.$_GET['idassu'].'&numbill='.$_GET['numbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&finishbtn=ok"</script>';


/* 	echo $_POST['autreprestaServ'];
	echo $_POST['autreprestaInf'];
	echo $_POST['autreprestaLab'];
	echo $_POST['autreprestaConsom'];
	echo $_POST['autreprestaMedoc'];
	echo $_POST['typeconsu'].'<br/>';
	echo $_POST['medecin'].'<br/>';
 */
	
?>