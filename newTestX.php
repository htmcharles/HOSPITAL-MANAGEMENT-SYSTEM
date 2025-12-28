<?php
session_start();
include("connect.php");
include("connectLangues.php");

	$id_uI=$_GET['inf'];

	$idConsu= array();
	$qteConsu= array();
	$percentConsu= array();
	$prixConsu= array();

	foreach($_POST['prixprestaConsu'] as $valeurPrixConsu)
	{
		if($valeurPrixConsu)
		{
			$prixConsu[] = $valeurPrixConsu;
		}
	}

	foreach($_POST['quantityConsu'] as $valeurQteConsu)
	{
		$qteConsu[] = $valeurQteConsu;
	}

	foreach($_POST['percentConsu'] as $valeurPercentConsu)
	{
		$percentConsu[] = $valeurPercentConsu;
	}

	foreach($_POST['idmedConsu'] as $valeurIdConsu)
	{
		$idConsu[] = $valeurIdConsu;
	}
	
	for($i=0;$i<sizeof($idConsu);$i++)
	{
		if(isset($_POST['addQteConsuBtn'.$idConsu[$i]]))
		{
			if($qteConsu[$i]>0)
			{
				$searchConsu=$connexion->prepare('SELECT *FROM med_consult_hosp WHERE id_medconsu=:idmedconsu');
				$searchConsu->execute(array(
				'idmedconsu'=>$idConsu[$i]
				)) or die( print_r($connexion->errorInfo()));
				
				$searchConsu->setFetchMode(PDO::FETCH_OBJ);
				
				$comptSearchConsu=$searchConsu->rowCount();
				
				if($comptSearchConsu!=0)				
				{
					if($ligneConsu=$searchConsu->fetch())
					{
						$updateQteMedConsu=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.qteConsu=:qteConsu, mc.insupercentServ=:insupercentConsu, mc.prixautreConsu=:prixautreConsu, mc.id_uI=:id_uI WHERE mc.id_medconsu=:idmedconsu');
						
						$updateQteMedConsu->execute(array(
						'qteConsu'=>$qteConsu[$i],
						'insupercentConsu'=>$percentConsu[$i],
						'prixautreConsu'=>$prixConsu[$i],
						'id_uI'=>$id_uI,
						'idmedconsu'=>$idConsu[$i]
						
						))or die( print_r($connexion->errorInfo()));
	
					// echo $idConsu[$i].' : '.$qteConsu[$i].'_'.$percentConsu[$i].'_'.$prixConsu[$i].'<br/>';
					}
					
				}
			}
		}
	}
	
	
	$idInf= array();
	$qteInf= array();
	$percentInf= array();
	$prixInf= array();

	foreach($_POST['prixprestaInf'] as $valeurPrixInf)
	{
		if($valeurPrixInf)
		{
			$prixInf[] = $valeurPrixInf;
		}
	}

	foreach($_POST['quantityInf'] as $valeurQteInf)
	{
		$qteInf[] = $valeurQteInf;
	}

	foreach($_POST['percentInf'] as $valeurPercentInf)
	{
		$percentInf[] = $valeurPercentInf;
	}

	foreach($_POST['idmedInf'] as $valeurIdInf)
	{
		$idInf[] = $valeurIdInf;
	}
	
	for($i=0;$i<sizeof($idInf);$i++)
	{
		if(isset($_POST['addQteInfBtn'.$idInf[$i]]))
		{
			if($qteInf[$i]>0)
			{
				$searchInf=$connexion->prepare('SELECT *FROM med_inf_hosp WHERE id_medinf=:idmedinf');
				$searchInf->execute(array(
				'idmedinf'=>$idInf[$i]
				)) or die( print_r($connexion->errorInfo()));
				
				$searchInf->setFetchMode(PDO::FETCH_OBJ);
				
				$comptSearchInf=$searchInf->rowCount();
				
				if($comptSearchInf!=0)				
				{
					if($ligneInf=$searchInf->fetch())
					{
						$updateQteMedInf=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.qteInf=:qteInf, mi.insupercentInf=:insupercentInf, mi.prixautrePrestaM=:prixautreInf, mi.id_uI=:id_uI WHERE mi.id_medinf=:idmedinf');
						
						$updateQteMedInf->execute(array(
						'qteInf'=>$qteInf[$i],
						'insupercentInf'=>$percentInf[$i],
						'prixautreInf'=>$prixInf[$i],
						'idmedinf'=>$idInf[$i],
						'id_uI'=>$id_uI
						
						))or die( print_r($connexion->errorInfo()));
	
					// echo $idInf[$i].' : '.$qteInf[$i].'_'.$percentInf[$i].'_'.$prixInf[$i].'<br/>';
					}
					
				}
			}
		}
	}
	
	
	$idLab= array();
	$qteLab= array();
	$percentLab= array();
	$prixLab= array();

	foreach($_POST['prixprestaLab'] as $valeurPrixLab)
	{
		if($valeurPrixLab)
		{
			$prixLab[] = $valeurPrixLab;
		}
	}

	foreach($_POST['quantityLab'] as $valeurQteLab)
	{
		$qteLab[] = $valeurQteLab;
	}

	foreach($_POST['percentLab'] as $valeurPercentLab)
	{
		$percentLab[] = $valeurPercentLab;
	}

	foreach($_POST['idmedLab'] as $valeurIdLab)
	{
		$idLab[] = $valeurIdLab;
	}
	
	for($i=0;$i<sizeof($idLab);$i++)
	{
		if(isset($_POST['addQteLabBtn'.$idLab[$i]]))
		{
			if($qteLab[$i]>0)
			{
				$searchLab=$connexion->prepare('SELECT *FROM med_labo_hosp WHERE id_medlabo=:idmedlabo');
				$searchLab->execute(array(
				'idmedlabo'=>$idLab[$i]
				)) or die( print_r($connexion->errorInfo()));
				
				$searchLab->setFetchMode(PDO::FETCH_OBJ);
				
				$comptSearchLab=$searchLab->rowCount();
				
				if($comptSearchLab!=0)				
				{
					if($ligneLab=$searchLab->fetch())
					{
						$updateQteMedLab=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.qteLab=:qteLab, ml.insupercentLab=:insupercentLab, ml.prixautreExamen=:prixautreLab, ml.id_uI=:id_uI WHERE ml.id_medlabo=:idmedlabo');
						
						$updateQteMedLab->execute(array(
						'qteLab'=>$qteLab[$i],
						'insupercentLab'=>$percentLab[$i],
						'prixautreLab'=>$prixLab[$i],
						'idmedlabo'=>$idLab[$i],
						'id_uI'=>$id_uI
						
						))or die( print_r($connexion->errorInfo()));
	
					// echo $idLab[$i].' : '.$qteLab[$i].'_'.$percentLab[$i].'_'.$prixLab[$i].'<br/>';
					}
					
				}
			}
		}
	}
	
	
	$idRad= array();
	$qteRad= array();
	$percentRad= array();
	$prixRad= array();

	foreach($_POST['prixprestaRad'] as $valeurPrixRad)
	{
		if($valeurPrixRad)
		{
			$prixRad[] = $valeurPrixRad;
		}
	}

	foreach($_POST['quantityRad'] as $valeurQteRad)
	{
		$qteRad[] = $valeurQteRad;
	}

	foreach($_POST['percentRad'] as $valeurPercentRad)
	{
		$percentRad[] = $valeurPercentRad;
	}

	foreach($_POST['idmedRad'] as $valeurIdRad)
	{
		$idRad[] = $valeurIdRad;
	}
	
	for($i=0;$i<sizeof($idRad);$i++)
	{
		if(isset($_POST['addQteRadBtn'.$idRad[$i]]))
		{
			if($qteRad[$i]>0)
			{
				$searchRad=$connexion->prepare('SELECT *FROM med_radio_hosp WHERE id_medradio=:idmedradio');
				$searchRad->execute(array(
				'idmedradio'=>$idRad[$i]
				)) or die( print_r($connexion->errorInfo()));
				
				$searchRad->setFetchMode(PDO::FETCH_OBJ);
				
				$comptSearchRad=$searchRad->rowCount();
				
				if($comptSearchRad!=0)				
				{
					if($ligneRad=$searchRad->fetch())
					{
						$updateQteMedRad=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.qteRad=:qteRad, mr.insupercentRad=:insupercentRad, mr.prixautreRadio=:prixautreRad, mr.id_uI=:id_uI WHERE mr.id_medradio=:idmedradio');
						
						$updateQteMedRad->execute(array(
						'qteRad'=>$qteRad[$i],
						'insupercentRad'=>$percentRad[$i],
						'prixautreRad'=>$prixRad[$i],
						'idmedradio'=>$idRad[$i],
						'id_uI'=>$id_uI
						
						))or die( print_r($connexion->errorInfo()));
	
					// echo $idRad[$i].' : '.$qteRad[$i].'_'.$percentRad[$i].'_'.$prixRad[$i].'<br/>';
					}
					
				}
			}
		}
	}
	
	
	$idConsom= array();
	$qteConsom= array();
	$percentConsom= array();
	$prixConsom= array();

	foreach($_POST['prixprestaConsom'] as $valeurPrixConsom)
	{
		if($valeurPrixConsom)
		{
			$prixConsom[] = $valeurPrixConsom;
		}
	}

	foreach($_POST['quantityConsom'] as $valeurQteConsom)
	{
		$qteConsom[] = $valeurQteConsom;
	}

	foreach($_POST['percentConsom'] as $valeurPercentConsom)
	{
		$percentConsom[] = $valeurPercentConsom;
	}

	foreach($_POST['idmedConsom'] as $valeurIdConsom)
	{
		$idConsom[] = $valeurIdConsom;
	}
	
	for($i=0;$i<sizeof($idConsom);$i++)
	{
		if(isset($_POST['addQteConsomBtn'.$idConsom[$i]]))
		{
			if($qteConsom[$i]>0)
			{
				$searchConsom=$connexion->prepare('SELECT *FROM med_consom_hosp WHERE id_medconsom=:idmedconsom');
				$searchConsom->execute(array(
				'idmedconsom'=>$idConsom[$i]
				)) or die( print_r($connexion->errorInfo()));
				
				$searchConsom->setFetchMode(PDO::FETCH_OBJ);
				
				$comptSearchConsom=$searchConsom->rowCount();
				
				if($comptSearchConsom!=0)				
				{
					if($ligneConsom=$searchConsom->fetch())
					{
						$updateQteMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.qteConsom=:qteConsom, mco.insupercentConsom=:insupercentConsom, mco.prixautreConsom=:prixautreConsom, mco.id_uI=:id_uI WHERE mco.id_medconsom=:idmedconsom');
						
						$updateQteMedConsom->execute(array(
						'qteConsom'=>$qteConsom[$i],
						'insupercentConsom'=>$percentConsom[$i],
						'prixautreConsom'=>$prixConsom[$i],
						'idmedconsom'=>$idConsom[$i],
						'id_uI'=>$id_uI
						
						))or die( print_r($connexion->errorInfo()));
	
					// echo $idConsom[$i].' : '.$qteConsom[$i].'_'.$percentConsom[$i].'_'.$prixConsom[$i].'<br/>';
					}
					
				}
			}
		}
	}
	
	
	$idMedoc= array();
	$qteMedoc= array();
	$percentMedoc= array();
	$prixMedoc= array();

	foreach($_POST['prixprestaMedoc'] as $valeurPrixMedoc)
	{
		if($valeurPrixMedoc)
		{
			$prixMedoc[] = $valeurPrixMedoc;
		}
	}

	foreach($_POST['quantityMedoc'] as $valeurQteMedoc)
	{
		$qteMedoc[] = $valeurQteMedoc;
	}

	foreach($_POST['percentMedoc'] as $valeurPercentMedoc)
	{
		$percentMedoc[] = $valeurPercentMedoc;
	}

	foreach($_POST['idmedMedoc'] as $valeurIdMedoc)
	{
		$idMedoc[] = $valeurIdMedoc;
	}
	
	for($i=0;$i<sizeof($idMedoc);$i++)
	{
		if(isset($_POST['addQteMedocBtn'.$idMedoc[$i]]))
		{
			if($qteMedoc[$i]>0)
			{
				$searchMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp WHERE id_medmedoc=:idmedmedoc');
				$searchMedoc->execute(array(
				'idmedmedoc'=>$idMedoc[$i]
				)) or die( print_r($connexion->errorInfo()));
				
				$searchMedoc->setFetchMode(PDO::FETCH_OBJ);
				
				$comptSearchMedoc=$searchMedoc->rowCount();
				
				if($comptSearchMedoc!=0)				
				{
					if($ligneMedoc=$searchMedoc->fetch())
					{
						$updateQteMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mco SET mco.qteMedoc=:qteMedoc, mco.insupercentMedoc=:insupercentMedoc, mco.prixautreMedoc=:prixautreMedoc, mco.id_uI=:id_uI WHERE mco.id_medmedoc=:idmedmedoc');
						
						$updateQteMedMedoc->execute(array(
						'qteMedoc'=>$qteMedoc[$i],
						'insupercentMedoc'=>$percentMedoc[$i],
						'prixautreMedoc'=>$prixMedoc[$i],
						'idmedmedoc'=>$idMedoc[$i],
						'id_uI'=>$id_uI
						
						))or die( print_r($connexion->errorInfo()));
	
						// echo $idMedoc[$i].' : '.$qteMedoc[$i].'_'.$percentMedoc[$i].'_'.$prixMedoc[$i].'<br/>';
					}
					
				}
			}
		}
	}
	
	
	echo '<script text="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&previewprint=ok&infShow=ok"</script>';

	
?>