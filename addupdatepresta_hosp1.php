<?php
session_start();
include("connect.php");
include("connectLangues.php");


	$annee = $_GET['datefacture'];

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
		

/* 

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
			
			// echo $add[$i].' ('.$ligneprestaServ->prixpresta.')<br/>';
		}
		
		$resultatServ=$connexion->prepare('INSERT INTO med_consult (datehosp,id_prestationConsu,prixprestationConsu,id_assuServ,insupercentServ,numero,id_uM,id_hospMed) VALUES(:datehosp,:idPrestaConsu,:prixPrestaConsu,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
		$resultatServ->execute(array(
		'datehosp'=>$datehosp,
		'idPrestaConsu'=>$add[$i],
		'prixPrestaConsu'=>$prixprestaServ,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idhospAdd'=>$idhospAdd
		)) or die( print_r($connexion->errorInfo()));
					
	}
}
 */

if(isset($_POST['autreprestaServ']))
{
	
	if($_POST['autreprestaServ']!="")
	{
		$add = array();
		$prixadd = array();


		foreach($_POST['autreprestaServ'] as $valeur)
		{
			$add[] = $valeur;
			   
		}
		foreach($_POST['autreprixprestaServ'] as $valeurprix)
		{
			$prixadd[] = $valeurprix;
			   
		}
		
		for($i=0;$i<sizeof($add);$i++)
		{
			if($add[$i]!="")
			{
				$resultatServ=$connexion->prepare('INSERT INTO med_consult_hosp (datehosp,autreConsu,prixautreConsu,id_assuServ,insupercentServ,numero,id_uM,id_uI,id_uCoor,id_hospMed) VALUES(:datehosp,:autrePrestaConsu,:prixautrePrestaConsu,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
				$resultatServ->execute(array(
				'datehosp'=>$annee,
				'autrePrestaConsu'=>$add[$i],
				'prixautrePrestaConsu'=>$prixadd[$i],
				'idassu'=>$idassu,
				'bill'=>$bill,
				'numero'=>$numero,
				'id_uM'=>$id_uM,
				'id_uI'=>$id_uI,
				'id_uCoor'=>$id_uCoor,
				'idhospAdd'=>$idhospAdd
				)) or die( print_r($connexion->errorInfo()));
			}
		}

	}
}

/* 

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
			
			// echo $addInf[$i].' ('.$ligneprestaInf->prixpresta.')<br/>';
		}
		
		
		$resultatInf=$connexion->prepare('INSERT INTO med_inf (datehosp,id_prestation,prixprestation,soinsfait,id_assuInf,insupercentInf,numero,id_uM,id_hospInf) VALUES(:datehosp,:idPrestaInf,:prixPresta,:soinsfait,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
		
		$resultatInf->execute(array(
		'datehosp'=>$datehosp,
		'idPrestaInf'=>$addInf[$i],
		'prixPresta'=>$prixprestaInf,
		'soinsfait'=>$soinsfait,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idhospAdd'=>$idhospAdd
		
		)) or die( print_r($connexion->errorInfo()));

	}
	
}

*/
 
if(isset($_POST['autreprestaInf']))
{
	if($_POST['autreprestaInf']!="")
	{

		$soinsfait=1;
		$addInf = array();
		$prixaddInf= array();


		foreach($_POST['autreprestaInf'] as $valeurInf)
		{
			$addInf[] = $valeurInf;
			   
		}
		foreach($_POST['autreprixprestaInf'] as $valeurprixInf)
		{
			$prixaddInf[] = $valeurprixInf;
			   
		}
		
		for($i=0;$i<sizeof($addInf);$i++)
		{
			if($addInf[$i]!="")
			{
				$resultatInf=$connexion->prepare('INSERT INTO med_inf_hosp (datehosp,autrePrestaM,prixautrePrestaM,soinsfait,datesoins,id_assuInf,insupercentInf,numero,id_uM,id_uI,id_uCoor,id_hospInf) VALUES(:datehosp,:autrePrestaInf,:prixautrePrestaInf,:soinsfait,:datesoins,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
				
				$resultatInf->execute(array(
				'datehosp'=>$datehosp,
				'autrePrestaInf'=>$addInf[$i],
				'prixautrePrestaInf'=>$prixaddInf[$i],
				'soinsfait'=>$soinsfait,
				'datesoins'=>$annee,
				'idassu'=>$idassu,
				'bill'=>$bill,
				'numero'=>$numero,
				'id_uM'=>$id_uM,
				'id_uI'=>$id_uI,
				'id_uCoor'=>$id_uCoor,
				'idhospAdd'=>$idhospAdd
				
				)) or die( print_r($connexion->errorInfo()));
			}
		}
	}
}
/* 
if(isset($_POST['checkprestaLab']))
{
	$addLab = array();
	
	$prixautreExa=0;
	$examenfait=0;
	$dateresultat="0000-00-00";

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

			// echo $addLab[$i].' ('.$prixprestaLab.')<br/><br/>';
		}


		$resultatLabo=$connexion->prepare('INSERT INTO med_labo (datehosp,id_prestationExa,prixprestationExa,prixautreExamen,id_assuLab,insupercentLab,examenfait,dateresultats,numero,id_uM,id_hospLabo) VALUES(:datehosp,:idPrestaLab,:prixPresta,:prixautreExa,:idassu,:bill,:examenfait,:dateresultat,:numero,:id_uM,:idhospAdd)');
		$resultatLabo->execute(array(
		'datehosp'=>$datehosp,
		'idPrestaLab'=>$addLab[$i],
		'prixPresta'=>$prixprestaLab,
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
 */

if(isset($_POST['autreprestaLab']))
{
	if($_POST['autreprestaLab']!="")
	{
		$examenfait=0;
		$addLabo = array();
		$prixaddLabo= array();


		foreach($_POST['autreprestaLab'] as $valeurLabo)
		{
			$addLabo[] = $valeurLabo;
			   
		}
		foreach($_POST['autreprixprestaLab'] as $valeurprixLabo)
		{
			$prixaddLabo[] = $valeurprixLabo;
			   
		}
		
		for($i=0;$i<sizeof($addLabo);$i++)
		{
			if($addLabo[$i]!="")
			{
				$resultatLab=$connexion->prepare('INSERT INTO med_labo_hosp (datehosp,autreExamen,prixautreExamen,examenfait,id_assuLab,insupercentLab,numero,id_uM,id_uI,id_uCoor,id_hospLabo) VALUES(:datehosp,:autrePrestaLab,:prixautreExa,:examenfait,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
				
				$resultatLab->execute(array(
				'datehosp'=>$annee,
				'autrePrestaLab'=>$addLabo[$i],
				'prixautreExa'=>$prixaddLabo[$i],
				'examenfait'=>$examenfait,
				'idassu'=>$idassu,
				'bill'=>$bill,
				'numero'=>$numero,
				'id_uM'=>$id_uM,
				'id_uI'=>$id_uI,
				'id_uCoor'=>$id_uCoor,
				'idhospAdd'=>$idhospAdd
				)) or die( print_r($connexion->errorInfo()));
			}
		}
	}
}

/* 
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
			
		if($ligneprestaRad=$resRad->fetch(PDO::FETCH_OBJ))//on recupere la liste des �l�ments
		{
			$prixprestaRad=$ligneprestaRad->prixpresta;

			// echo $addRad[$i].' ('.$prixprestaRad.')<br/><br/>';
		}


		$resultatRadio=$connexion->prepare('INSERT INTO med_radio (datehosp,id_prestationRadio,prixprestationRadio,prixautreRadio,id_assuRad,insupercentRad,radiofait,dateradio,numero,id_uM,id_hospRadio) VALUES(:datehosp,:idPrestaRad,:prixPresta,:prixautreRadio,:idassu,:bill,:radiofait,:dateradio,:numero,:id_uM,:idhospAdd)');
		$resultatRadio->execute(array(
		'datehosp'=>$datehosp,
		'idPrestaRad'=>$addRad[$i],
		'prixPresta'=>$prixprestaRad,
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
 */

if(isset($_POST['autreprestaRad']))
{
	if($_POST['autreprestaRad']!="")
	{

		$radiofait=0;
		$addRad = array();
		$prixaddRad= array();


		foreach($_POST['autreprestaRad'] as $valeurRad)
		{
			$addRad[] = $valeurRad;
			   
		}
		foreach($_POST['autreprixprestaRad'] as $valeurprixRad)
		{
			$prixaddRad[] = $valeurprixRad;
			   
		}
		
		for($i=0;$i<sizeof($addRad);$i++)
		{
			if($addRad[$i]!="")
			{		
				$resultatRad=$connexion->prepare('INSERT INTO med_Radio_hosp (datehosp,autreRadio,prixautreRadio,radiofait,id_assuRad,insupercentRad,numero,id_uM,id_uI,id_uCoor,id_hospRadio) VALUES(:datehosp,:autreprestaRad,:prixautreRad,:radiofait,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
				
				$resultatRad->execute(array(
				'datehosp'=>$annee,
				'autreprestaRad'=>$addRad[$i],
				'prixautreRad'=>$prixaddRad[$i],
				'radiofait'=>$radiofait,
				'idassu'=>$idassu,
				'bill'=>$bill,
				'numero'=>$numero,
				'id_uM'=>$id_uM,
				'id_uI'=>$id_uI,
				'id_uCoor'=>$id_uCoor,
				'idhospAdd'=>$idhospAdd
				)) or die( print_r($connexion->errorInfo()));
			}
		}
	}
}

/* 
if(isset($_POST['checkprestaConsom']))
{
	$addConsom = array();

	$prixautreConsom=0;

	foreach($_POST['checkprestaConsom'] as $valeurConsom)
	{
		$addConsom[] = $valeurConsom;
	}
		
	for($i=0;$i<sizeof($addConsom);$i++)
	{
		
		$resConsom=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation="'.$addConsom[$i].'" ORDER BY p.nompresta');
					
		$comptConsom=$resConsom->rowCount();
		
		if($comptConsom==0)
		{
			$resConsom=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation="'.$addConsom[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaConsom=$resConsom->fetch(PDO::FETCH_OBJ))//on recupere la liste des �l�ments
			{
				$prixprestaConsom=$ligneprestaConsom->prixpresta;
				
				// echo $comptConsom.'_'.$addConsom[$i].' ('.$prixprestaConsom.')<br/>';
			}
			
				
			$resultatConsom=$connexion->prepare('INSERT INTO med_consom_hosp (datehosp,id_prestationConsom,prixprestationConsom,prixautreConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_hospConsom) VALUES(:datehosp,:idPrestaConsom,:prixPresta,:prixautreConsom,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
			$resultatConsom->execute(array(
			'datehosp'=>$datehosp,
			'idPrestaConsom'=>$addConsom[$i],
			'prixPresta'=>$prixprestaConsom,
			'prixautreConsom'=>$prixautreConsom,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idhospAdd'=>$idhospAdd
			)) or die( print_r($connexion->errorInfo()));

	}
	
}
		
*/

if(isset($_POST['autreprestaConsom']))
{
	if($_POST['autreprestaConsom']!="")
	{
		$addConsom = array();
		$qteaddConsom= array();
		$prixaddConsom= array();


		foreach($_POST['autreprestaConsom'] as $valeurConsom)
		{
			$addConsom[] = $valeurConsom;
			   
		}
		foreach($_POST['qteprestaConsom'] as $valeurqteConsom)
		{
			if($valeurqteConsom==0)
			{
				$qteaddConsom[] = 1;
			   
			}else{
				$qteaddConsom[] = $valeurqteConsom;
			}
		
		}
		foreach($_POST['autreprixprestaConsom'] as $valeurprixConsom)
		{
			$prixaddConsom[] = $valeurprixConsom;
			   
		}
		
		for($i=0;$i<sizeof($addConsom);$i++)
		{	
			if($addConsom[$i]!="")
			{
				$searchConsom=$connexion->prepare('SELECT *FROM med_consom_hosp WHERE datehosp=:datehosp AND numero=:num AND id_factureMedConsom="" AND autreConsom LIKE \''.$addConsom[$i].'\' ');
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
						$updateQteMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.qteConsom=:qteConsom, mco.prixautreConsom=:prixautreConsom WHERE mco.datehosp=:datehosp AND mco.numero=:num AND mco.autreConsom LIKE \''.$addConsom[$i].'\'');
						
						$updateQteMedConsom->execute(array(
						'qteConsom'=>$ligneConsom->qteConsom+$qteaddConsom[$i],
						'num'=>$numero,
						'prixautreConsom'=>$prixaddConsom[$i],
						'datehosp'=>$annee
						
						))or die( print_r($connexion->errorInfo()));
	
					}
					
				}else{
					$resultatConsom=$connexion->prepare('INSERT INTO med_consom_hosp (datehosp,autreConsom,prixautreConsom,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_uI,id_uCoor,id_hospConsom) VALUES(:datehosp,:autreConsom,:prixautreConsom,:qteConsom,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
					$resultatConsom->execute(array(
					'datehosp'=>$annee,
					'autreConsom'=>$addConsom[$i],
					'prixautreConsom'=>$prixaddConsom[$i],
					'qteConsom'=>$qteaddConsom[$i],
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'id_uI'=>$id_uI,
					'id_uCoor'=>$id_uCoor,
					'idhospAdd'=>$idhospAdd
					)) or die( print_r($connexion->errorInfo()));
				}
			}
		}
	}
}
		
/* 
if(isset($_POST['checkprestaMedoc']))
{
	$addMedoc = array();

	$prixautreMedoc=0;

	foreach($_POST['checkprestaMedoc'] as $valeurMedoc)
	{
		$addMedoc[] = $valeurMedoc;
	}
		
	for($i=0;$i<sizeof($addMedoc);$i++)
	{
		
		$resMedoc=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation="'.$addMedoc[$i].'" ORDER BY p.nompresta');
					
		$comptMedoc=$resMedoc->rowCount();
		
		if($comptMedoc==0)
		{
			$resMedoc=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation="'.$addMedoc[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaMedoc=$resMedoc->fetch(PDO::FETCH_OBJ))//on recupere la liste des �l�ments
			{
				$prixprestaMedoc=$ligneprestaMedoc->prixpresta;
				
				// echo $comptMedoc.'_'.$addMedoc[$i].' ('.$prixprestaMedoc.')<br/>';
			}
			
				
			$resultatMedoc=$connexion->prepare('INSERT INTO med_medoc_hosp (datehosp,id_prestationMedoc,prixprestationMedoc,prixautreMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_hospMedoc) VALUES(:datehosp,:idPrestaMedoc,:prixPresta,:prixautreMedoc,:idassu,:bill,:numero,:id_uM,:idhospAdd)');
			$resultatMedoc->execute(array(
			'datehosp'=>$datehosp,
			'idPrestaMedoc'=>$addMedoc[$i],
			'prixPresta'=>$prixprestaMedoc,
			'prixautreMedoc'=>$prixautreMedoc,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idhospAdd'=>$idhospAdd
			)) or die( print_r($connexion->errorInfo()));
		
	}
	
}		
 */

if(isset($_POST['autreprestaMedoc']))
{
	if($_POST['autreprestaMedoc']!='')
	{
		$addMedoc = array();
		$qteaddMedoc= array();
		$prixaddMedoc= array();


		foreach($_POST['autreprestaMedoc'] as $valeurMedoc)
		{
			$addMedoc[] = $valeurMedoc;
			   
		}
		foreach($_POST['qteprestaMedoc'] as $valeurqteMedoc)
		{
			if($valeurqteMedoc==0)
			{
				$qteaddMedoc[] = 1;
			   
			}else{
				$qteaddMedoc[] = $valeurqteMedoc;
			}
		
		}
		foreach($_POST['autreprixprestaMedoc'] as $valeurprixMedoc)
		{
			$prixaddMedoc[] = $valeurprixMedoc;
			   
		}
		
		for($i=0;$i<sizeof($addMedoc);$i++)
		{
			if($addMedoc[$i]!="")
			{				
				
				$searchMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp WHERE datehosp=:datehosp AND id_factureMedMedoc="" AND numero=:num AND autreMedoc LIKE \''.$addMedoc[$i].'\' ');
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
						
						$updateQteMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.qteMedoc=:qteMedoc, mdo.prixautreMedoc=:prixautreMedoc WHERE mdo.datehosp=:datehosp AND mdo.numero=:num AND mdo.autreMedoc LIKE \''.$addMedoc[$i].'\'');
						
						$updateQteMedMedoc->execute(array(
						'qteMedoc'=>$ligneMedoc->qteMedoc+$qteaddMedoc[$i],
						'num'=>$numero,
						'prixautreMedoc'=>$prixaddMedoc[$i],
						'datehosp'=>$annee
						
						))or die( print_r($connexion->errorInfo()));
	
					}
					
				}else{
				
					$resultatMedoc=$connexion->prepare('INSERT INTO med_medoc_hosp (datehosp,autreMedoc,prixautreMedoc,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_uI,id_uCoor,id_hospMedoc) VALUES(:datehosp,:autreMedoc,:prixautreMedoc,:qteMedoc,:idassu,:bill,:numero,:id_uM,:id_uI,:id_uCoor,:idhospAdd)');
					$resultatMedoc->execute(array(
					'datehosp'=>$annee,
					'autreMedoc'=>$addMedoc[$i],
					'prixautreMedoc'=>$prixaddMedoc[$i],
					'qteMedoc'=>$qteaddMedoc[$i],
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'id_uI'=>$id_uI,
					'id_uCoor'=>$id_uCoor,
					'idhospAdd'=>$idhospAdd
					)) or die( print_r($connexion->errorInfo()));
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