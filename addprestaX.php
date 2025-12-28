<?php
include("connect.php");
include("connectLangues.php");

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


	$idassu=$_GET['idassu'];
	
	$dateconsu=$_GET['dateconsu'];
	$numero=$_GET['num'];
	$id_uM=$_GET['idmed'];
	$idconsuAdd=$_GET['idconsu'];
	
											
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
		
		
			$resultatServ=$connexion->prepare('INSERT INTO med_consult (dateconsu,id_prestationConsu,prixprestationConsu,id_assuServ,insupercentServ,numero,id_uM,id_consuMed) VALUES(:dateconsu,:idPrestaConsu,:prixPrestaConsu,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
			$resultatServ->execute(array(
			'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
			'idPrestaConsu'=>$add[$i],
			'prixPrestaConsu'=>$prixprestaServ,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>nl2br($numero),
			'id_uM'=>nl2br($id_uM),
			'idconsuAdd'=>nl2br($idconsuAdd)
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

		foreach($_POST['autreprestaServ'] as $valeur)
		{
			$addautre[] = $valeur;
			   
		}
		foreach($_POST['autreprixprestaServ'] as $valeurprix)
		{
			$addautreprix[] = $valeurprix;
			   
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
			
		if($ligneprestaInf=$resInf->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaInf=$ligneprestaInf->prixpresta;
			
			// echo $addInf[$i].' ('.$ligneprestaInf->prixpresta.')<br/>';
		}
		
		
		$resultatInf=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,prixprestation,soinsfait,id_assuInf,insupercentInf,numero,id_uM,id_consuInf) VALUES(:dateconsu,:idPrestaInf,:prixPresta,:soinsfait,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
		
		$resultatInf->execute(array(
		'dateconsu'=>$dateconsu,
		'idPrestaInf'=>$addInf[$i],
		'prixPresta'=>$prixprestaInf,
		'soinsfait'=>$soinsfait,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idconsuAdd'=>$idconsuAdd
		
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
					
					$resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,prixprestation,soinsfait,id_assuInf,insupercentInf,numero,id_uM,id_consuInf) VALUES(:dateconsu,:idPresta,:prixPresta,:soinsfait,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$lastIdPresta,
					'prixPresta'=>$prixInf,
					'soinsfait'=>$soinsfait,
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
			
		if($ligneprestaLab=$resLab->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaLab=$ligneprestaLab->prixpresta;

			// echo $addLab[$i].' ('.$prixprestaLab.')<br/><br/>';
		}


		$resultatLabo=$connexion->prepare('INSERT INTO med_labo (dateconsu,id_prestationExa,prixprestationExa,prixautreExamen,id_assuLab,insupercentLab,examenfait,dateresultats,numero,id_uM,id_consuLabo,id_factureMedLabo) VALUES(:dateconsu,:idPrestaLab,:prixPresta,:prixautreExa,:idassu,:bill,:examenfait,:dateresultat,:numero,:id_uM,:idconsuAdd,:idfacture)');
		$resultatLabo->execute(array(
		'dateconsu'=>$dateconsu,
		'idPrestaLab'=>$addLab[$i],
		'prixPresta'=>$prixprestaLab,
		'prixautreExa'=>$prixautreExa,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'examenfait'=>$examenfait,
		'dateresultat'=>$dateresultat,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idconsuAdd'=>$idconsuAdd,
		'idfacture'=>$idfacture
		)) or die( print_r($connexion->errorInfo()));

			/* echo 'Date : '.$dateconsu.'<br/>';
			echo 'Id presta : '.$addLab[$i].'<br/>';
			echo 'Prix presta : '.$prixprestaLab.'<br/>';
			echo '% : '.$bill.'<br/>';
			echo 'Examenfait : '.$examenfait.'<br/>';
			echo 'Numero Patient : '.$numero.'<br/>';
			echo 'Id Medecin : '.$id_uM.'<br/>';
			echo 'Id Consu : '.$idconsuAdd.'<br/><br/>'; */
	}
}


if($_POST['autreprestaLab']!="")
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
					
					$resultat=$connexion->prepare('INSERT INTO med_labo (dateconsu,id_prestationExa,prixprestationExa,id_assuLab,insupercentLab,examenfait,numero,id_uM,id_consuLabo) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:examenfait,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$lastIdPresta,
					'prixPresta'=>$prixLabo,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'examenfait'=>nl2br($examenfait),
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
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
	$idfacture=0;

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
		}


		$resultatRadio=$connexion->prepare('INSERT INTO med_radio (dateconsu,id_prestationRadio,prixprestationRadio,prixautreRadio,id_assuRad,insupercentRad,radiofait,dateradio,numero,id_uM,id_consuRadio,id_factureMedRadio) VALUES(:dateconsu,:idPrestaRad,:prixPresta,:prixautreRadio,:idassu,:bill,:radiofait,:dateradio,:numero,:id_uM,:idconsuAdd,:idfacture)');
		$resultatRadio->execute(array(
		'dateconsu'=>$dateconsu,
		'idPrestaRad'=>$addRad[$i],
		'prixPresta'=>$prixprestaRad,
		'prixautreRadio'=>$prixautreRadio,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'radiofait'=>$radiofait,
		'dateradio'=>$dateradio,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idconsuAdd'=>$idconsuAdd,
		'idfacture'=>$idfacture
		)) or die( print_r($connexion->errorInfo()));

			/* echo 'Date : '.$dateconsu.'<br/>';
			echo 'Id presta : '.$addRad[$i].'<br/>';
			echo 'Prix presta : '.$prixprestaRad.'<br/>';
			echo '% : '.$bill.'<br/>';
			echo 'Radiofait : '.$radiofait.'<br/>';
			echo 'Numero Patient : '.$numero.'<br/>';
			echo 'Id Medecin : '.$id_uM.'<br/>';
			echo 'Id Consu : '.$idconsuAdd.'<br/><br/>'; */
	}
}


if($_POST['autreprestaRad']!="")
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

		foreach($_POST['autreprestaRad'] as $valeurRad)
		{
			$addRadautre[] = $valeurRad;
			   
		}
		foreach($_POST['autreprixprestaRad'] as $valeurRadprix)
		{
			$addRadautreprix[] = $valeurRadprix;
			   
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

					
					$resultat=$connexion->prepare('INSERT INTO med_radio (dateconsu,id_prestationRadio,prixprestationRadio,id_assuRad,insupercentRad,radiofait,numero,id_uM,id_consuRadio) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:radiofait,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$lastIdPresta,
					'prixPresta'=>$prixRadio,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'radiofait'=>nl2br($radiofait),
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) or die( print_r($connexion->errorInfo()));
						
				}
			}
		}
	}
}


if(isset($_POST['checkprestaConsom']))
{
	$addConsom = array();

	$qteConsom=1;
	$prixautreConsom=0;
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
				
				// echo $comptConsom.'_'.$addConsom[$i].' ('.$prixprestaConsom.')<br/>';
			}
			
				
			$resultatConsom=$connexion->prepare('INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,prixautreConsom,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom,id_factureMedConsom) VALUES(:dateconsu,:idPrestaConsom,:prixPresta,:prixautreConsom,:qteConsom,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
			$resultatConsom->execute(array(
			'dateconsu'=>$dateconsu,
			'idPrestaConsom'=>$addConsom[$i],
			'prixPresta'=>$prixprestaConsom,
			'prixautreConsom'=>$prixautreConsom,
			'qteConsom'=>$qteConsom,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idconsuAdd'=>$idconsuAdd,
			'idfacture'=>$idfacture
			)) or die( print_r($connexion->errorInfo()));

	}
	
}
		

if($_POST['autreprestaConsom']!="")
{
	if($_POST['autreprestaConsom']!="")
	{		
		$id_categopresta=21;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		
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
					
// echo 'INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom) VALUES('.date('Y-m-d', strtotime($dateconsu)).','.$lastIdPresta.','.$addConsomautreprix[$i].','.$qteConsom[$i].','.$idassu.','.$bill.','.nl2br($numero).','.nl2br($id_uM).','.nl2br($idconsuAdd).')<br/>';	

					$resultat=$connexion->prepare('INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom) VALUES(:dateconsu,:idPresta,:prixPresta,:qteConsom,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$lastIdPresta,
					'prixPresta'=>$addConsomautreprix[$i],
					'qteConsom'=>$qteConsom[$i],
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) or die( print_r($connexion->errorInfo()));
									
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
			$resMedoc=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation="'.$addMedoc[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaMedoc=$resMedoc->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaMedoc=$ligneprestaMedoc->prixpresta;
				
				// echo $comptMedoc.'_'.$addMedoc[$i].' ('.$prixprestaMedoc.')<br/>';
			}
			
				
			$resultatMedoc=$connexion->prepare('INSERT INTO med_medoc (dateconsu,id_prestationMedoc,prixprestationMedoc,prixautreMedoc,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc,id_factureMedMedoc) VALUES(:dateconsu,:idPrestaMedoc,:prixPresta,:prixautreMedoc,:qteMedoc,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
			$resultatMedoc->execute(array(
			'dateconsu'=>$dateconsu,
			'idPrestaMedoc'=>$addMedoc[$i],
			'prixPresta'=>$prixprestaMedoc,
			'prixautreMedoc'=>$prixautreMedoc,
			'qteMedoc'=>$qteMedoc,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idconsuAdd'=>$idconsuAdd,
			'idfacture'=>$idfacture
			)) or die( print_r($connexion->errorInfo()));
		
		
			/* 
			echo 'Date : '.$dateconsu.'<br/>';
			echo 'Id presta : '.$addMedoc[$i].'<br/>';
			echo 'Prix presta : '.$prixprestaMedoc.'<br/>';
			echo '% : '.$bill.'<br/>';
			echo 'Numero Patient : '.$numero.'<br/>';
			echo 'Id Medecin : '.$id_uM.'<br/>';
			echo 'Id Consu : '.$idconsuAdd.'<br/><br/>';
		 */
	}
	
}		


if($_POST['autreprestaMedoc']!='')
{
	if($_POST['autreprestaMedoc']!="")
	{	
		$id_categopresta=22;
		$id_souscategopresta=0;
		
		$mesure=NULL;
		$statupresta=0;
		
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
						}
					}
				
					$resultat=$connexion->prepare('INSERT INTO med_medoc (dateconsu,id_prestationMedoc,prixprestationMedoc,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc) VALUES(:dateconsu,:idPresta,:prixPresta,:qteMedoc,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$lastIdPresta,
					'prixPresta'=>$addMedocautreprix[$i],
					'qteMedoc'=>$qteMedoc[$i],
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) or die( print_r($connexion->errorInfo()));
									
				}
			}
		}
	}
}

/* 	echo $_POST['autreprestaServ'];
	echo $_POST['autreprestaInf'];
	echo $_POST['autreprestaLab'];
	echo $_POST['autreprestaConsom'];
	echo $_POST['autreprestaMedoc']; 
	echo $_POST['typeconsu'].'<br/>';
	echo $_POST['medecin'].'<br/>';
 */
	echo '<script text="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&back=ok"</script>';


?>