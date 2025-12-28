<?php
session_start();
include("connect.php");
include("connectLangues.php");

	
	$annee = date('Y').'-'.date('m').'-'.date('d');
	
	if(isset($_SESSION['codeI']))
	{
		$id_uI=$_SESSION['id'];
	}else{
		$id_uI=NULL;
	}
	
	if(isset($_SESSION['codeO']))
	{
		$id_uO=$_SESSION['id'];
	}else{
		$id_uO=NULL;
	}
	$idTable = "";
	
	
	if(isset($_POST['idmedConsu']))
	{
		$idConsu= array();
		$qteConsu= array();
		$percentConsu= array();
		$prixConsu= array();
			$anneemc = array();
			$moismc = array();
			$jourmc = array();

		foreach($_POST['prixprestaConsu'] as $valeurPrixConsu)
		{
			if($valeurPrixConsu !="")
			{
				$prixConsu[] = $valeurPrixConsu;
			}else{
				$prixConsu[] = -1;
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
			
			foreach($_POST['anneeMedConsu'] as $anmc)
			{
				$anneemc[] = $anmc;
			}
			
			foreach($_POST['moisMedConsu'] as $moimc)
			{
				$moismc[] = $moimc;
			}
			
			foreach($_POST['joursMedConsu'] as $jrmc)
			{
				$jourmc[] = $jrmc;
			}
		
		for($i=0;$i<sizeof($idConsu);$i++)
		{
			if($moismc[$i]<10)
			{
				$moismc[$i]='0'.$moismc[$i];
			}
			
			if($jourmc[$i]<10)
			{
				$jourmc[$i]='0'.$jourmc[$i];
			}
			
			$datehospMedConsu=$anneemc[$i].'-'.$moismc[$i].'-'.$jourmc[$i];
				
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
							$updateQteMedConsu=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.datehosp=:datehosp,mc.qteConsu=:qteConsu, mc.insupercentServ=:insupercentConsu, mc.prixprestationConsu=:prixConsu, mc.id_uI=:id_uI WHERE mc.id_medconsu=:idmedconsu');
							
							$updateQteMedConsu->execute(array(
							'datehosp'=>$datehospMedConsu,
							'qteConsu'=>$qteConsu[$i],
							'insupercentConsu'=>$percentConsu[$i],
							'prixConsu'=>$prixConsu[$i],
							'id_uI'=>$id_uI,
							'idmedconsu'=>$idConsu[$i]
							
							))or die( print_r($connexion->errorInfo()));
		
						// echo $idConsu[$i].' : '.$qteConsu[$i].'_'.$percentConsu[$i].'_'.$prixConsu[$i].'<br/>';
						}
						
					}
				}
		
				$idTable="#tableServ";
			}
		}
	}
	
	if(isset($_POST['idmedInf']))
	{
		$idInf= array();
		$qteInf= array();
		$percentInf= array();
		$prixInf= array();
			$anneemi = array();
			$moismi = array();
			$jourmi = array();

		foreach($_POST['prixprestaInf'] as $valeurPrixInf)
		{
			if($valeurPrixInf !="")
			{
				$prixInf[] = $valeurPrixInf;
			}else{
				$prixInf[] = -1;
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
			
			foreach($_POST['anneeMedInf'] as $anmi)
			{
				$anneemi[] = $anmi;
			}
			
			foreach($_POST['moisMedInf'] as $moimi)
			{
				$moismi[] = $moimi;
			}
			
			foreach($_POST['joursMedInf'] as $jrmi)
			{
				$jourmi[] = $jrmi;
			}
		
		for($i=0;$i<sizeof($idInf);$i++)
		{
			if($moismi[$i]<10)
			{
				$moismi[$i]='0'.$moismi[$i];
			}
			
			if($jourmi[$i]<10)
			{
				$jourmi[$i]='0'.$jourmi[$i];
			}
				
				$datehospMedInf=$anneemi[$i].'-'.$moismi[$i].'-'.$jourmi[$i];
			
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
							$updateQteMedInf=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.datehosp=:datehosp, mi.qteInf=:qteInf, mi.insupercentInf=:insupercentInf, mi.prixprestation=:prixInf, mi.id_uI=:id_uI WHERE mi.id_medinf=:idmedinf');
							
							$updateQteMedInf->execute(array(
							'datehosp'=>$datehospMedInf,
							'qteInf'=>$qteInf[$i],
							'insupercentInf'=>$percentInf[$i],
							'prixInf'=>$prixInf[$i],
							'idmedinf'=>$idInf[$i],
							'id_uI'=>$id_uI
							
							))or die( print_r($connexion->errorInfo()));
		
						// echo $idInf[$i].' : '.$qteInf[$i].'_'.$percentInf[$i].'_'.$prixInf[$i].'<br/>';
						}
						
					}
				}
		
				$idTable="#tableInf";
			}
		}
	}
	
	if(isset($_POST['idmedSurge']))
	{
		$idSurge= array();
		$qteSurge= array();
		$percentSurge= array();
		$prixSurge= array();
			$anneems = array();
			$moisms = array();
			$jourms = array();

		foreach($_POST['prixprestaSurge'] as $valeurPrixSurge)
		{
			if($valeurPrixSurge !="")
			{
				$prixSurge[] = $valeurPrixSurge;
			}else{
				$prixSurge[] = -1;
			}
		}

		foreach($_POST['quantitySurge'] as $valeurQteSurge)
		{
			$qteSurge[] = $valeurQteSurge;
		}

		foreach($_POST['percentSurge'] as $valeurPercentSurge)
		{
			$percentSurge[] = $valeurPercentSurge;
		}

		foreach($_POST['idmedSurge'] as $valeurIdSurge)
		{
			$idSurge[] = $valeurIdSurge;
		}
			
			foreach($_POST['anneeMedSurge'] as $anms)
			{
				$anneems[] = $anms;
			}
			
			foreach($_POST['moisMedSurge'] as $moims)
			{
				$moisms[] = $moims;
			}
			
			foreach($_POST['joursMedSurge'] as $jrms)
			{
				$jourms[] = $jrms;
			}
		
		for($i=0;$i<sizeof($idSurge);$i++)
		{
			if($moisms[$i]<10)
			{
				$moisms[$i]='0'.$moisms[$i];
			}
			
			if($jourms[$i]<10)
			{
				$jourms[$i]='0'.$jourms[$i];
			}
				
			$datehospMedSurge=$anneems[$i].'-'.$moisms[$i].'-'.$jourms[$i];
			
			if(isset($_POST['addQteSurgeBtn'.$idSurge[$i]]))
			{
				if($qteSurge[$i]>0)
				{
					$searchSurge=$connexion->prepare('SELECT *FROM med_surge_hosp WHERE id_medsurge=:idmedSurge');
					$searchSurge->execute(array(
					'idmedSurge'=>$idSurge[$i]
					)) or die( print_r($connexion->errorInfo()));
					
					$searchSurge->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchSurge=$searchSurge->rowCount();
					
					if($comptSearchSurge!=0)				
					{
						if($ligneSurge=$searchSurge->fetch())
						{							
							$updateQteMedSurge=$connexion->prepare('UPDATE med_surge_hosp ms SET ms.datehosp=:datehosp, ms.qteSurge=:qteSurge, ms.insupercentSurge=:insupercentSurge, ms.prixprestationSurge=:prixSurge, ms.id_uI=:id_uI WHERE ms.id_medsurge=:idmedsurge');
							
							$updateQteMedSurge->execute(array(
							'datehosp'=>$datehospMedSurge,
							'qteSurge'=>$qteSurge[$i],
							'insupercentSurge'=>$percentSurge[$i],
							'prixSurge'=>$prixSurge[$i],
							'idmedsurge'=>$idSurge[$i],
							'id_uI'=>$id_uI
							
							))or die( print_r($connexion->errorInfo()));
						}						
					}
				}
		
				$idTable="#tableSurge";
			}
		}
	}
	
	if(isset($_POST['idmedLab']))
	{
		$idLab= array();
		$qteLab= array();
		$percentLab= array();
		$prixLab= array();
			$anneeml = array();
			$moisml = array();
			$jourml = array();

		foreach($_POST['prixprestaLab'] as $valeurPrixLab)
		{
			if($valeurPrixLab !="")
			{
				$prixLab[] = $valeurPrixLab;
			}else{
				$prixLab[] = -1;
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
			
			foreach($_POST['anneeMedLabo'] as $anml)
			{
				$anneeml[] = $anml;
			}
			
			foreach($_POST['moisMedLabo'] as $moiml)
			{
				$moisml[] = $moiml;
			}
			
			foreach($_POST['joursMedLabo'] as $jrml)
			{
				$jourml[] = $jrml;
			}
		
		for($i=0;$i<sizeof($idLab);$i++)
		{
			if($moisml[$i]<10)
			{
				$moisml[$i]='0'.$moisml[$i];
			}
			
			if($jourml[$i]<10)
			{
				$jourml[$i]='0'.$jourml[$i];
			}
				
			$datehospMedLabo=$anneeml[$i].'-'.$moisml[$i].'-'.$jourml[$i];
			
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
							$updateQteMedLab=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.datehosp=:datehosp, ml.qteLab=:qteLab, ml.insupercentLab=:insupercentLab, ml.prixprestationExa=:prixLab, ml.id_uI=:id_uI WHERE ml.id_medlabo=:idmedlabo');
							
							$updateQteMedLab->execute(array(
							'datehosp'=>$datehospMedLabo,
							'qteLab'=>$qteLab[$i],
							'insupercentLab'=>$percentLab[$i],
							'prixLab'=>$prixLab[$i],
							'idmedlabo'=>$idLab[$i],
							'id_uI'=>$id_uI
							
							))or die( print_r($connexion->errorInfo()));
		
						// echo $idLab[$i].' : '.$qteLab[$i].'_'.$percentLab[$i].'_'.$prixLab[$i].'<br/>';
						}
						
					}
				}
		
				$idTable="#tableLab";
			}
		}
	}
	
	if(isset($_POST['idmedRad']))
	{
		$idRad= array();
		$qteRad= array();
		$percentRad= array();
		$prixRad= array();
			$anneeemr = array();
			$moismr = array();
			$jourmr = array();

		foreach($_POST['prixprestaRad'] as $valeurPrixRad)
		{
			if($valeurPrixRad !="")
			{
				$prixRad[] = $valeurPrixRad;
			}else{
				$prixRad[] = -1;
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
			
			foreach($_POST['anneeMedRadio'] as $anmr)
			{
				$anneemr[] = $anmr;
			}
			
			foreach($_POST['moisMedRadio'] as $moimr)
			{
				$moismr[] = $moimr;
			}
			
			foreach($_POST['joursMedRadio'] as $jrmr)
			{
				$jourmr[] = $jrmr;
			}
		
		for($i=0;$i<sizeof($idRad);$i++)
		{
			if($moismr[$i]<10)
			{
				$moismr[$i]='0'.$moismr[$i];
			}
			
			if($jourmr[$i]<10)
			{
				$jourmr[$i]='0'.$jourmr[$i];
			}
				
			$datehospMedRadio=$anneemr[$i].'-'.$moismr[$i].'-'.$jourmr[$i];
				
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
							$updateQteMedRad=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.datehosp=:datehosp, mr.qteRad=:qteRad, mr.insupercentRad=:insupercentRad, mr.prixprestationRadio=:prixRad, mr.id_uI=:id_uI WHERE mr.id_medradio=:idmedradio');
							
							$updateQteMedRad->execute(array(
							'datehosp'=>$datehospMedRadio,
							'qteRad'=>$qteRad[$i],
							'insupercentRad'=>$percentRad[$i],
							'prixRad'=>$prixRad[$i],
							'idmedradio'=>$idRad[$i],
							'id_uI'=>$id_uI
							
							))or die( print_r($connexion->errorInfo()));
		
						// echo $idRad[$i].' : '.$qteRad[$i].'_'.$percentRad[$i].'_'.$prixRad[$i].'<br/>';
						}
						
					}
				}
		
				$idTable="#tableRad";
			}
		}
	}
	
	if(isset($_POST['idmedKine']))
	{
		$idKine= array();
		$qteKine= array();
		$percentKine= array();
		$prixKine= array();
			$anneeemk = array();
			$moismk = array();
			$jourmk = array();

		foreach($_POST['prixprestaKine'] as $valeurPrixKine)
		{
			if($valeurPrixKine !="")
			{
				$prixKine[] = $valeurPrixKine;
			}else{
				$prixKine[] = -1;
			}
		}

		foreach($_POST['quantityKine'] as $valeurQteKine)
		{
			$qteKine[] = $valeurQteKine;
		}

		foreach($_POST['percentKine'] as $valeurPercentKine)
		{
			$percentKine[] = $valeurPercentKine;
		}

		foreach($_POST['idmedKine'] as $valeurIdKine)
		{
			$idKine[] = $valeurIdKine;
		}
			
			foreach($_POST['anneeMedKine'] as $anmr)
			{
				$anneemk[] = $anmr;
			}
			
			foreach($_POST['moisMedKine'] as $moimk)
			{
				$moismk[] = $moimk;
			}
			
			foreach($_POST['joursMedKine'] as $jrmk)
			{
				$jourmk[] = $jrmk;
			}
		
		for($i=0;$i<sizeof($idKine);$i++)
		{
			if($moismk[$i]<10)
			{
				$moismk[$i]='0'.$moismk[$i];
			}
			
			if($jourmk[$i]<10)
			{
				$jourmk[$i]='0'.$jourmk[$i];
			}
				
				$datehospMedKine=$anneemk[$i].'-'.$moismk[$i].'-'.$jourmk[$i];
				
			if(isset($_POST['addQteKineBtn'.$idKine[$i]]))
			{
				if($qteKine[$i]>0)
				{
					$searchKine=$connexion->prepare('SELECT *FROM med_kine_hosp WHERE id_medkine=:idmedkine');
					$searchKine->execute(array(
					'idmedkine'=>$idKine[$i]
					)) or die( print_r($connexion->errorInfo()));
					
					$searchKine->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchKine=$searchKine->rowCount();
					
					if($comptSearchKine!=0)				
					{
						if($ligneKine=$searchKine->fetch())
						{
							$updateQteMedKine=$connexion->prepare('UPDATE med_kine_hosp mk SET mk.datehosp=:datehosp, mk.qteKine=:qteKine, mk.insupercentKine=:insupercentKine, mk.prixprestationKine=:prixKine, mk.id_uI=:id_uI WHERE mk.id_medkine=:idmedkine');
							
							$updateQteMedKine->execute(array(
							'datehosp'=>$datehospMedKine,
							'qteKine'=>$qteKine[$i],
							'insupercentKine'=>$percentKine[$i],
							'prixKine'=>$prixKine[$i],
							'idmedkine'=>$idKine[$i],
							'id_uI'=>$id_uI
							
							))or die( print_r($connexion->errorInfo()));
						}						
					}
				}
		
				$idTable="#tableKine";
			}
		}
	}
	
	if(isset($_POST['idmedConsom']))
	{	
		$idConsom= array();
		$qteConsom= array();
		$percentConsom= array();
		$prixConsom= array();
			$anneeeConsom = array();
			$moisConsom = array();
			$jourConsom = array();

		foreach($_POST['prixprestaConsom'] as $valeurPrixConsom)
		{
			if($valeurPrixConsom !="")
			{
				$prixConsom[] = $valeurPrixConsom;
			}else{
				$prixConsom[] = -1;
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
			
			foreach($_POST['anneeMedConsom'] as $anConsom)
			{
				$anneeConsom[] = $anConsom;
			}
			
			foreach($_POST['moisMedConsom'] as $moiConsom)
			{
				$moisConsom[] = $moiConsom;
			}
			
			foreach($_POST['joursMedConsom'] as $jrConsom)
			{
				$jourConsom[] = $jrConsom;
			}
		
		for($i=0;$i<sizeof($idConsom);$i++)
		{
			if($moisConsom[$i]<10)
			{
				$moisConsom[$i]='0'.$moisConsom[$i];
			}
			
			if($jourConsom[$i]<10)
			{
				$jourConsom[$i]='0'.$jourConsom[$i];
			}
				
				$datehospMedConsom=$anneeConsom[$i].'-'.$moisConsom[$i].'-'.$jourConsom[$i];
				
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
							$updateQteMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.datehosp=:datehosp, mco.qteConsom=:qteConsom, mco.insupercentConsom=:insupercentConsom, mco.prixprestationConsom=:prixConsom, mco.id_uI=:id_uI WHERE mco.id_medconsom=:idmedconsom');
							
							$updateQteMedConsom->execute(array(
							'datehosp'=>$datehospMedConsom,
							'qteConsom'=>$qteConsom[$i],
							'insupercentConsom'=>$percentConsom[$i],
							'prixConsom'=>$prixConsom[$i],
							'idmedconsom'=>$idConsom[$i],
							'id_uI'=>$id_uI
							
							))or die( print_r($connexion->errorInfo()));
		
						// echo $idConsom[$i].' : '.$qteConsom[$i].'_'.$percentConsom[$i].'_'.$prixConsom[$i].'<br/>';
						}
						
					}
				}
		
				$idTable="#tableConsom";
			}
		}
		
		$idTable="#tableConsom";
	}	
	
	if(isset($_POST['idmedMedoc']))
	{
		$idMedoc= array();
		$qteMedoc= array();
		$percentMedoc= array();
		$prixMedoc= array();
			$anneeeMedoc = array();
			$moisMedoc = array();
			$jourMedoc = array();

		foreach($_POST['prixprestaMedoc'] as $valeurPrixMedoc)
		{
			if($valeurPrixMedoc !="")
			{
				$prixMedoc[] = $valeurPrixMedoc;
			}else{
				$prixMedoc[] = -1;
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
			
			foreach($_POST['anneeMedMedoc'] as $anMedoc)
			{
				$anneeMedoc[] = $anMedoc;
			}
			
			foreach($_POST['moisMedMedoc'] as $moiMedoc)
			{
				$moisMedoc[] = $moiMedoc;
			}
			
			foreach($_POST['joursMedMedoc'] as $jrMedoc)
			{
				$jourMedoc[] = $jrMedoc;
			}
		
		
		for($i=0;$i<sizeof($idMedoc);$i++)
		{
			if($moisMedoc[$i]<10)
			{
				$moisMedoc[$i]='0'.$moisMedoc[$i];
			}
			
			if($jourMedoc[$i]<10)
			{
				$jourMedoc[$i]='0'.$jourMedoc[$i];
			}
			
			$datehospMedMedoc=$anneeMedoc[$i].'-'.$moisMedoc[$i].'-'.$jourMedoc[$i];
				
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
							$updateQteMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mco SET mco.datehosp=:datehosp, mco.qteMedoc=:qteMedoc, mco.insupercentMedoc=:insupercentMedoc, mco.prixprestationMedoc=:prixMedoc, mco.id_uI=:id_uI WHERE mco.id_medmedoc=:idmedmedoc');
							
							$updateQteMedMedoc->execute(array(
							'datehosp'=>$datehospMedMedoc,
							'qteMedoc'=>$qteMedoc[$i],
							'insupercentMedoc'=>$percentMedoc[$i],
							'prixMedoc'=>$prixMedoc[$i],
							'idmedmedoc'=>$idMedoc[$i],
							'id_uI'=>$id_uI
							
							))or die( print_r($connexion->errorInfo()));
		
							// echo $idMedoc[$i].' : '.$qteMedoc[$i].'_'.$percentMedoc[$i].'_'.$prixMedoc[$i].'<br/>';
						}
						
					}
				}
		
				$idTable="#tableMedoc";
			}
		}
	}
	
	if(isset($_POST['idmedOrtho']))
	{
		$idOrtho= array();
		$qteOrtho= array();
		$percentOrtho= array();
		$prixOrtho= array();
			$anneemo = array();
			$moismo = array();
			$jourmo = array();

		foreach($_POST['prixprestaOrtho'] as $valeurPrixOrtho)
		{
			if($valeurPrixOrtho !="")
			{
				$prixOrtho[] = $valeurPrixOrtho;
			}else{
				$prixOrtho[] = -1;
			}
		}

		foreach($_POST['quantityOrtho'] as $valeurQteOrtho)
		{
			$qteOrtho[] = $valeurQteOrtho;
		}

		foreach($_POST['percentOrtho'] as $valeurPercentOrtho)
		{
			$percentOrtho[] = $valeurPercentOrtho;
		}

		foreach($_POST['idmedOrtho'] as $valeurIdOrtho)
		{
			$idOrtho[] = $valeurIdOrtho;
		}
			
			foreach($_POST['anneeMedOrtho'] as $anmo)
			{
				$anneemo[] = $anmo;
			}
			
			foreach($_POST['moisMedOrtho'] as $moimo)
			{
				$moismo[] = $moimo;
			}
			
			foreach($_POST['joursMedOrtho'] as $jrmo)
			{
				$jourmo[] = $jrmo;
			}
		
		for($i=0;$i<sizeof($idOrtho);$i++)
		{
			if($moismo[$i]<10)
			{
				$moismo[$i]='0'.$moismo[$i];
			}
			
			if($jourmo[$i]<10)
			{
				$jourmo[$i]='0'.$jourmo[$i];
			}
			
			$datehospMedOrtho=$anneemo[$i].'-'.$moismo[$i].'-'.$jourmo[$i];
				
			if(isset($_POST['addQteOrthoBtn'.$idOrtho[$i]]))
			{
				if($qteOrtho[$i]>0)
				{
					$searchOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp WHERE id_medortho=:idmedortho');
					$searchOrtho->execute(array(
					'idmedortho'=>$idOrtho[$i]
					)) or die( print_r($connexion->errorInfo()));
					
					$searchOrtho->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchOrtho=$searchOrtho->rowCount();
					
					if($comptSearchOrtho!=0)				
					{
						if($ligneOrtho=$searchOrtho->fetch())
						{
							$updateQteMedOrtho=$connexion->prepare('UPDATE med_ortho_hosp mo SET mo.datehosp=:datehosp, mo.qteOrtho=:qteOrtho, mo.insupercentOrtho=:insupercentOrtho, mo.prixprestationOrtho=:prixOrtho, mo.id_uI=:id_uI, mo.id_uO=:id_uO, mo.dateortho=:dateortho WHERE mo.id_medortho=:idmedortho');
							
							$updateQteMedOrtho->execute(array(
							'datehosp'=>$datehospMedOrtho,
							'qteOrtho'=>$qteOrtho[$i],
							'insupercentOrtho'=>$percentOrtho[$i],
							'prixOrtho'=>$prixOrtho[$i],
							'idmedortho'=>$idOrtho[$i],
							'id_uI'=>$id_uI,
							'id_uO'=>$id_uO,
							'dateortho'=>$annee
							
							))or die( print_r($connexion->errorInfo()));
		
						
						}
						
					}
				}
				
				$idTable="#tableOrtho";
			}
		}
		
	}
	
	
	echo '<script text="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&previewprint=ok&infShow=ok'.$idTable.'"</script>';

	
?>