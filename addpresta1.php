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
		
		$resServ=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_prestation="'.$add[$i].'" ORDER BY p.nompresta');
					
		$comptServ=$resServ->rowCount();
			
		if($ligneprestaServ=$resServ->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaServ=$ligneprestaServ->prixpresta;
			
			// echo $add[$i].' ('.$ligneprestaServ->prixpresta.')<br/>';
		}
		
		$resultatServ=$connexion->prepare('INSERT INTO med_consult (dateconsu,id_prestationConsu,prixprestationConsu,id_assuServ,insupercentServ,numero,id_uM,id_consuMed) VALUES(:dateconsu,:idPrestaConsu,:prixPrestaConsu,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
		$resultatServ->execute(array(
		'dateconsu'=>$dateconsu,
		'idPrestaConsu'=>$add[$i],
		'prixPrestaConsu'=>$prixprestaServ,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idconsuAdd'=>$idconsuAdd
		)) or die( print_r($connexion->errorInfo()));
					
	}
}


if(isset($_POST['autreprestaServ']))
{
	
	if($_POST['autreprestaServ']!="")
	{
	
		$resultatServ=$connexion->prepare('INSERT INTO med_consult (dateconsu,autreConsu,prixautreConsu,id_assuServ,insupercentServ,numero,id_uM,id_consuMed) VALUES(:dateconsu,:autrePrestaConsu,:prixautrePrestaConsu,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
		$resultatServ->execute(array(
		'dateconsu'=>$dateconsu,
		'autrePrestaConsu'=>$_POST['autreprestaServ'],
		'prixautrePrestaConsu'=>$_POST['autreprixprestaServ'],
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idconsuAdd'=>$idconsuAdd
		)) or die( print_r($connexion->errorInfo()));

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


if($_POST['autreprestaInf']!="")
{

	$soinsfait=0;
	
	$resultatInf=$connexion->prepare('INSERT INTO med_inf (dateconsu,autrePrestaM,prixautrePrestaM,soinsfait,id_assuInf,insupercentInf,numero,id_uM,id_consuInf) VALUES(:dateconsu,:autrePrestaInf,:prixautrePrestaInf,:soinsfait,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
	
	$resultatInf->execute(array(
	'dateconsu'=>$dateconsu,
	'autrePrestaInf'=>$_POST['autreprestaInf'],
	'prixautrePrestaInf'=>$_POST['autreprixprestaInf'],
	'soinsfait'=>$soinsfait,
	'idassu'=>$idassu,
	'bill'=>$bill,
	'numero'=>$numero,
	'id_uM'=>$id_uM,
	'idconsuAdd'=>$idconsuAdd
	
	)) or die( print_r($connexion->errorInfo()));
	
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
	
	$resultatLab=$connexion->prepare('INSERT INTO med_labo (dateconsu,autreExamen,prixautreExamen,examenfait,id_assuLab,insupercentLab,numero,id_uM,id_consuLabo,id_factureMedLabo) VALUES(:dateconsu,:autrePrestaLab,:prixautreExa,:examenfait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
	
	$resultatLab->execute(array(
	'dateconsu'=>$dateconsu,
	'autrePrestaLab'=>$_POST['autreprestaLab'],
	'prixautreExa'=>$_POST['autreprixprestaLab'],
	'examenfait'=>$examenfait,
	'idassu'=>$idassu,
	'bill'=>$bill,
	'numero'=>$numero,
	'id_uM'=>$id_uM,
	'idconsuAdd'=>$idconsuAdd,
	'idfacture'=>$idfacture	
	)) or die( print_r($connexion->errorInfo()));
	
	
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

			// echo $addRad[$i].' ('.$prixprestaRad.')<br/><br/>';
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
	
	$resultatRad=$connexion->prepare('INSERT INTO med_Radio (dateconsu,autreRadio,prixautreRadio,radiofait,id_assuRad,insupercentRad,numero,id_uM,id_consuRadio,id_factureMedRadio) VALUES(:dateconsu,:autreprestaRad,:prixautreRad,:radiofait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
	
	$resultatRad->execute(array(
	'dateconsu'=>$dateconsu,
	'autreprestaRad'=>$_POST['autreprestaRad'],
	'prixautreRad'=>$_POST['autreprixprestaRad'],
	'radiofait'=>$radiofait,
	'idassu'=>$idassu,
	'bill'=>$bill,
	'numero'=>$numero,
	'id_uM'=>$id_uM,
	'idconsuAdd'=>$idconsuAdd,
	'idfacture'=>$idfacture	
	)) or die( print_r($connexion->errorInfo()));
	
	
}


if(isset($_POST['checkprestaConsom']))
{
	$addConsom = array();

	$prixautreConsom=0;
	$idfacture=0;

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
			if($ligneprestaConsom=$resConsom->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaConsom=$ligneprestaConsom->prixpresta;
				
				// echo $comptConsom.'_'.$addConsom[$i].' ('.$prixprestaConsom.')<br/>';
			}
			
				
			$resultatConsom=$connexion->prepare('INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,prixautreConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom,id_factureMedConsom) VALUES(:dateconsu,:idPrestaConsom,:prixPresta,:prixautreConsom,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
			$resultatConsom->execute(array(
			'dateconsu'=>$dateconsu,
			'idPrestaConsom'=>$addConsom[$i],
			'prixPresta'=>$prixprestaConsom,
			'prixautreConsom'=>$prixautreConsom,
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
	
	$idfacture=0;
	$prixprestaConsom=0;

	if($_POST['qteprestaConsom']==0)
	{
		$qteConsom=1;
	}else{
		$qteConsom=$_POST['qteprestaConsom'];
	}
	
	
	$resultatConsom=$connexion->prepare('INSERT INTO med_consom (dateconsu,prixprestationConsom,autreConsom,prixautreConsom,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom,id_factureMedConsom) VALUES(:dateconsu,:prixprestaConsom,:autreConsom,:prixautreConsom,:qteConsom,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
	$resultatConsom->execute(array(
	'dateconsu'=>$dateconsu,
	'prixprestaConsom'=>$prixprestaConsom,
	'autreConsom'=>$_POST['autreprestaConsom'],
	'prixautreConsom'=>$_POST['autreprixprestaConsom'],
	'qteConsom'=>$qteConsom,
	'idassu'=>$idassu,
	'bill'=>$bill,
	'numero'=>$numero,
	'id_uM'=>$id_uM,
	'idconsuAdd'=>$idconsuAdd,
	'idfacture'=>$idfacture
	)) or die( print_r($connexion->errorInfo()));
	
	
	echo '<script text="text/javascript">document.location.href="categoriesbill.php?consom=ok&cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&back=ok"</script>';



}
		

if(isset($_POST['checkprestaMedoc']))
{
	$addMedoc = array();

	$prixautreMedoc=0;
	$idfacture=0;

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
			if($ligneprestaMedoc=$resMedoc->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaMedoc=$ligneprestaMedoc->prixpresta;
				
				// echo $comptMedoc.'_'.$addMedoc[$i].' ('.$prixprestaMedoc.')<br/>';
			}
			
				
			$resultatMedoc=$connexion->prepare('INSERT INTO med_medoc (dateconsu,id_prestationMedoc,prixprestationMedoc,prixautreMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc,id_factureMedMedoc) VALUES(:dateconsu,:idPrestaMedoc,:prixPresta,:prixautreMedoc,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
			$resultatMedoc->execute(array(
			'dateconsu'=>$dateconsu,
			'idPrestaMedoc'=>$addMedoc[$i],
			'prixPresta'=>$prixprestaMedoc,
			'prixautreMedoc'=>$prixautreMedoc,
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
	$idfacture=0;
	$prixprestaMedoc=0;

	if($_POST['qteprestaMedoc']==0)
	{
		$qteMedoc=1;
	}else{
		$qteMedoc=$_POST['qteprestaMedoc'];
	}
	
	$resultatMedoc=$connexion->prepare('INSERT INTO med_medoc (dateconsu,prixprestationMedoc,autreMedoc,prixautreMedoc,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc,id_factureMedMedoc) VALUES(:dateconsu,:prixprestaMedoc,:autreMedoc,:prixautreMedoc,:qteMedoc,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
	$resultatMedoc->execute(array(
	'dateconsu'=>$dateconsu,
	'prixprestaMedoc'=>$prixprestaMedoc,
	'autreMedoc'=>$_POST['autreprestaMedoc'],
	'prixautreMedoc'=>$_POST['autreprixprestaMedoc'],
	'qteMedoc'=>$qteMedoc,
	'idassu'=>$idassu,
	'bill'=>$bill,
	'numero'=>$numero,
	'id_uM'=>$id_uM,
	'idconsuAdd'=>$idconsuAdd,
	'idfacture'=>$idfacture
	)) or die( print_r($connexion->errorInfo()));

	
	echo '<script text="text/javascript">document.location.href="categoriesbill.php?medoc=ok&cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&back=ok"</script>';


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