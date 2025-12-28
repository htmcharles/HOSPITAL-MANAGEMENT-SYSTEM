<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


/** Include PHPExcel */
require_once 'PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

require_once('barcode/class/BCGFontFile.php');
require_once('barcode/class/BCGColor.php');
require_once('barcode/class/BCGDrawing.php');
require_once('barcode/class/BCGcode93.barcode.php');

$annee = date('d').'-'.date('M').'-'.date('Y');


$heure = date('H').' : '.date('i').' : '.date('s');

// echo $heure;
// echo showBN();


	$numPa=$_GET['num'];
	$consuId=$_GET['idconsu'];	
	
	
	$checkIdBill=$connexion->prepare('SELECT *FROM bills b, consultations c WHERE b.id_bill=c.id_factureConsult AND c.id_factureConsult=:idbill ORDER BY b.id_bill LIMIT 1');

	$checkIdBill->execute(array(
	'idbill'=>$_GET['idbill']
	));

	$comptidBill=$checkIdBill->rowCount();

	// echo $comptidBill;
	
	if($comptidBill != 0)
	{
		$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBill->fetch();
		
		$idBilling = $ligne->id_bill;
		
		$numbill = $ligne->numbill;
		$createBill = 0;
		
		// echo $idBilling;
		
	}
	
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo 'Bill#'.$numbill; ?></title>

	<link href="cssBourbonCoffee/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	
	<!--<meta HTTP-EQUIV="Refresh" CONTENT="30; URL=http://www.tonSite.com/page.html"> --> 
	
		
			<!------------------------------------>
	
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<style type="text/css">

		@media print {
		  
			.az
			{
				display:none;
			}

			.account-container
			{ 
				display:block;
				
			}
			
			.buttonBill
			{ 
				display:none;
				
			}
		}
	
	</style>
	
	<script src="script.js"></script>
		
	<script src="myQuery.js"></script>

	<script type="text/javascript">

function controlFormRembou(theForm){
		
	var rapport="";
	
	rapport +=controlPrixConsu(theForm.prixrembou,theForm.patientprice);
		
	function controlPrixConsu(fld1,fld2){
		var erreur="";
		
		if(fld1.value > fld2.value){
		
			fld1.style.background="rgba(0,255,0,0.3)";			
			erreur = "error";
		}
		
		return erreur;
	}

	
	var prixrembouServ=document.getElementsByClassName("prixrembouServ");
	var balanceServ=document.getElementsByClassName("balanceServ");
	
	
	var prixrembouInf=document.getElementsByClassName("prixrembouInf");
	var balanceInf=document.getElementsByClassName("balanceInf");
	
	
	var prixrembouLabo=document.getElementsByClassName("prixrembouLabo");
	var balanceLabo=document.getElementsByClassName("balanceLabo");
	
	
	var prixrembouRadio=document.getElementsByClassName("prixrembouRadio");
	var balanceRadio=document.getElementsByClassName("balanceRadio");
	
	
	var prixrembouConsom=document.getElementsByClassName("prixrembouConsom");
	var balanceConsom=document.getElementsByClassName("balanceConsom");
	
	
	var prixrembouMedoc=document.getElementsByClassName("prixrembouMedoc");
	var balanceMedoc=document.getElementsByClassName("balanceMedoc");
	
	
	
	var i;
	var rapportPrixServ = [];
	var rapportPrixInf = [];
	var rapportPrixLabo = [];
	var rapportPrixRadio = [];
	var rapportPrixConsom = [];
	var rapportPrixMedoc = [];
	
	
	
		for(i=0; i<prixrembouServ.length; ++i){
			
			if(prixrembouServ[i].value > balanceServ[i].value){	
				rapportPrixServ[i]=controlPrixrembouServ(prixrembouServ[i]);		}else{	
				prixrembouServ[i].style.background="white";				
			}	
		}			
			function controlPrixrembouServ(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
				
		
		for(i=0; i<prixrembouInf.length; ++i){
			
			if(prixrembouInf[i].value > balanceInf[i].value){
				rapportPrixInf[i]=controlPrixrembouInf(prixrembouInf[i]);	
			}else{
				prixrembouInf[i].style.background="white";
			}
		}				
			function controlPrixrembouInf(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
				
		
		for(i=0; i<prixrembouLabo.length; ++i){
		
			if(prixrembouLabo[i].value > balanceLabo[i].value){
				rapportPrixLabo[i]=controlPrixrembouLabo(prixrembouLabo[i]);
			}else{
				prixrembouLabo[i].style.background="white";
			}

		}		
			function controlPrixrembouLabo(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
				
		for(i=0; i<prixrembouRadio.length; ++i){
		
			if(prixrembouRadio[i].value > balanceRadio[i].value){
				rapportPrixRadio[i]=controlPrixrembouRadio(prixrembouRadio[i]);
			}else{
				prixrembouRadio[i].style.background="white";
			}

		}		
			function controlPrixrembouRadio(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
				
		for(i=0; i<prixrembouConsom.length; ++i){
		
			if(prixrembouConsom[i].value > balanceConsom[i].value){
				rapportPrixConsom[i]=controlPrixrembouConsom(prixrembouConsom[i]);
			}else{	
				prixrembouConsom[i].style.background="white";
			}
		}
			function controlPrixrembouConsom(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
			
		for(i=0; i<prixrembouMedoc.length; ++i){
			
			if(prixrembouMedoc[i].value > balanceMedoc[i].value){
				rapportPrixMedoc[i]=controlPrixrembouMedoc(prixrembouMedoc[i]);
			}else{	
				prixrembouMedoc[i].style.background="white";
			}
		}	
			function controlPrixrembouMedoc(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
	
	
	if (rapport != "" || rapportPrixServ != "" || rapportPrixInf != "" || rapportPrixLabo != "" || rapportPrixRadio != "" || rapportPrixConsom != "" || rapportPrixMedoc != ""){
	
		alert("Veuillez corriger les erreurs.");
				
				return false;		
	 }
		
	
}


</script>
</head>



<body>


	<?php
	if(isset($_GET['cancel']))
	{
		$results=$connexion->query('SELECT *FROM consultations c WHERE c.id_consu='.$_GET['idconsu'].'');
			
		$results->setFetchMode(PDO::FETCH_OBJ);
			
		if($ligne=$results->fetch())
		{
			$updateprix=$connexion->query('UPDATE consultations c SET c.id_assuConsu='.$_GET['idassu'].',c.prixrembou=0 WHERE c.id_consu='.$_GET['idconsu'].'');					
		}
	}
	
	if(isset($_GET['previewprint']))
	{
		$updateprixServ=$connexion->query('UPDATE med_consult mc SET mc.id_assuServ='.$_GET['idassu'].',mc.prixrembouConsu=0 WHERE mc.id_consuMed='.$_GET['idconsu'].'');

		$updateprixInf=$connexion->query('UPDATE med_inf mi SET mi.id_assuInf='.$_GET['idassu'].',mi.prixrembouInf=0 WHERE mi.id_consuInf='.$_GET['idconsu'].'');
						
		$updateprixLabo=$connexion->query('UPDATE med_labo ml SET ml.id_assuLab='.$_GET['idassu'].',ml.prixrembouLabo=0 WHERE ml.id_consuLabo='.$_GET['idconsu'].'');
		
		$updateprixRadio=$connexion->query('UPDATE med_radio mr SET mr.id_assuRad='.$_GET['idassu'].',mr.prixrembouRadio=0 WHERE mr.id_consuRadio='.$_GET['idconsu'].'');
		
		$updateprixConsom=$connexion->query('UPDATE med_consom mco SET mco.id_assuConsom='.$_GET['idassu'].',mco.prixrembouConsom=0 WHERE mco.id_consuConsom='.$_GET['idconsu'].'');
		
		$updateprixMedoc=$connexion->query('UPDATE med_medoc mdo SET mdo.id_assuMedoc='.$_GET['idassu'].',mdo.prixrembouMedoc=0 WHERE mdo.id_consuMedoc='.$_GET['idconsu'].'');
	}
	?>
	
<?php
$connected=$_SESSION['connect'];
$idCashier=$_SESSION['id'];

if($connected==true AND isset($_SESSION['codeCash']))
{
	
	// echo 'New '.$idBilling;
		
	$resultatsCashier=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u and c.id_u=:operation');
	$resultatsCashier->execute(array(
	'operation'=>$idCashier	
	));

	$resultatsCashier->setFetchMode(PDO::FETCH_OBJ);
	if($ligneCashier=$resultatsCashier->fetch())
	{
		$doneby = $ligneCashier->nom_u.'  '.$ligneCashier->prenom_u;
		$codecashier = $ligneCashier->codecashier;
	}	

		$font = new BCGFontFile('barcode/font/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		
		// Barcode Part
		$code = new BCGcode93();
		$code->setScale(2);
		$code->setThickness(30);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setLabel('# '.$numbill.' #');
		$code->parse(''.$numbill.'');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecashier.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
<?php
$barcode = '

	<table style="width:100%">
		
		<tr>
			<td style="text-align:left; width:60%">
			  <table>
				<tbody>
				  <tr>
					<td>
					  <img src="images/pc_logo.jpg">
					</td>
					<td style="text-align:left">
					  <span style="border-bottom:2px solid #ccc; font-size:120%; font-weight:900">POLYCLINIC DE L\'ETOILE</span> <br>
					</td>
				  </tr>
				</tbody>
			  </table>
			</td>
			
			<td style="text-align:right;">
				<img src="barcode/png/barcode'.$codecashier.'.png" style="height:auto;"/>	
			</td>
							
		</tr>
		
	</table>';

echo $barcode;
?>
			
<?php

		
			/*--------------Billing Info Patient-----------------*/
		
		$resultatsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultatsPatient->execute(array(
		'operation'=>$numPa	
		));
		
		$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
		
		if($lignePatient=$resultatsPatient->fetch())
		{
			
			$bill= $lignePatient->bill;
			$idassurance=$lignePatient->id_assurance;
			$numpolice=$lignePatient->numeropolice;
			$adherent=$lignePatient->adherent;
			
			if($lignePatient->carteassuranceid != "")
			{
				$idcard = $lignePatient->carteassuranceid;
			}else{
				$idcard = "";
			}
			
			$resultAssu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu');		
			$resultAssu->execute(array(
			'idassu'=>$lignePatient->id_assurance
			));
			
			$resultAssu->setFetchMode(PDO::FETCH_OBJ);

			$comptAssu=$resultAssu->rowCount();
			
			if($ligneAssu=$resultAssu->fetch())
			{
				$nomassurance = $ligneAssu->nomassurance;
			}else{
				$nomassurance = "";
			}
			
			$uappercent= 100 - $lignePatient->bill;
			
			$percentpartient= 100 - $uappercent;

			if($lignePatient->sexe=="M")
			{
				$sexe = "Male";
			}elseif($lignePatient->sexe=="F"){			
				$sexe = "Female";			
			}else{				
				$sexe="";
			}
	
			$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
			$resultAdresse->execute(array(
			'idProv'=>$lignePatient->province,
			'idDist'=>$lignePatient->district,
			'idSect'=>$lignePatient->secteur
			));
					
			$resultAdresse->setFetchMode(PDO::FETCH_OBJ);

			$comptAdress=$resultAdresse->rowCount();
			
			if($ligneAdresse=$resultAdresse->fetch())
			{
				if($ligneAdresse->id_province == $lignePatient->province)
				{
					$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
					
				}
			}elseif($lignePatient->autreadresse!=""){
					$adresse=$lignePatient->autreadresse;
			}else{
				$adresse="";
			}

			
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_consu=:consuId AND p.numero=:num AND p.numero=c.numero AND c.numero=:num ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'consuId'=>$consuId,
		'num'=>$numPa
		));

		$resultConsult->setFetchMode(PDO::FETCH_OBJ);

		if($ligneConsu=$resultConsult->fetch())
		{
			$dateconsu=$ligneConsu->dateconsu;
		}else{
			$dateconsu='';
		}

			
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		$resultatConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_consu=:consuId AND p.numero=:num AND p.numero=c.numero AND c.numero=:num ORDER BY c.id_consu');		
		$resultatConsult->execute(array(
		'consuId'=>$consuId,
		'num'=>$numPa
		));

		$resultatConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptConsult=$resultatConsult->rowCount();
		
		$TotalConsult = 0;
		

		
		
		$TotalGnl = 0;
		

		$userinfo = '<table style="width:100%; margin-top:20px;">
			
			<tr>
				<td style="text-align:left;">
					Full name: 
					<span style="font-weight:bold">'.$lignePatient->nom_u.' '.$lignePatient->prenom_u.'</span><br/>
					Gender: <span style="font-weight:bold">'.$sexe.'</span><br/>
					Adress: <span style="font-weight:bold">'.$adresse.'</span>
				</td>
				
				<td style="text-align:center;">
					Insurance type: <span style="font-weight:bold">';
			
			$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
			
			$resultAssurance->execute(array(
			'assuId'=>$lignePatient->id_assurance
			));
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

			if($ligneAssu=$resultAssurance->fetch())
			{
				if($ligneAssu->id_assurance == $lignePatient->id_assurance)
				{
					$idassu=$ligneAssu->id_assurance;
					$insurance=$ligneAssu->nomassurance;
					$numpolice=$lignePatient->numeropolice;
					$adherent=$lignePatient->adherent;
					
					$userinfo .= ''.$ligneAssu->nomassurance.'</span><br/>';
					
					if($idassurance!=1)
					{
						$userinfo .= 'N° insurance card: 
						<span style="font-weight:bold">'.$idcard;
						
						if($numpolice!="")
						{
							$userinfo .= '</span><br/>
							
							N° police: 
							<span style="font-weight:bold">'.$numpolice;
						}
							
						$userinfo .= '</span><br/>
						
						Principal member: 
						<span style="font-weight:bold">'.$adherent;
					}
				}
			}

				$userinfo .='</span>
				</td>
				
				<td style="text-align:right;">
					Patient ID: <span style="font-weight:bold">'.$lignePatient->numero.'</span><br/>
					Date of birth: <span style="font-weight:bold">'.date('d-M-Y', strtotime($lignePatient->date_naissance)).'</span><br/>
					Date of Consultation: <span style="font-weight:bold">'.date('d-M-Y', strtotime($_GET['dateconsu'])).'</span>
					
				</td>
								
			</tr>		
		</table>';

		echo $userinfo;
		
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Bill #'.$numbill.'')
					 ->setSubject("Billing information")
					 ->setDescription('Billing information for patient : '.$lignePatient->numero.', '.$lignePatient->nom_u.' '.$lignePatient->prenom_u.'')
					 ->setKeywords("Bill Excel")
					 ->setCategory("Bill");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
		
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'S/N')
						->setCellValue('B1', ''.$lignePatient->numero.'')
						->setCellValue('A2', 'Full name')
						->setCellValue('B2', ''.$lignePatient->nom_u.'  '.$lignePatient->prenom_u.'')
						
						->setCellValue('A3', 'Adresse')
						->setCellValue('B3', ''.$adresse.'')
						
						->setCellValue('A4', 'Insurance')
						->setCellValue('B4', ''.$insurance.' '.$percentpartient.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.$numbill.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$annee.'');
			
		}
		
			
	
		/*-------Requête pour AFFICHER Med_consult-----------*/
	
			
		if(isset($_POST['idpresta']))
		{
			
			$idassuServ=$idassu;

			$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

			$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuServ->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuServ=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuServ->execute(array(
				'idassu'=>$idassuServ
				));
				
				$getAssuServ->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssuServ=$getAssuServ->fetch())
				{
					$presta_assuServ='prestations_'.$ligneNomAssuServ->nomassurance;
				}
			}
			


			$idprestamc = array();
			$prixmc = array();
			$add = array();
			$idmc = array();
			$autremc = array();

			foreach($_POST['idpresta'] as $mc)
			{
				$idprestamc[] = $mc;
			}
			
			foreach($_POST['prixprestaConsu'] as $valmc)
			{
				$prixmc[] = $valmc;
			}
			
			foreach($_POST['percentConsu'] as $valuemc)
			{
				$add[] = $valuemc;
			}
			
			foreach($_POST['idmedConsu'] as $valeurmc)
			{
				$idmc[] = $valeurmc;
			}
			
			foreach($_POST['autreConsu'] as $autrevaluemc)
			{
				$autremc[] = $autrevaluemc;
			}
			
			for($i=0;$i<sizeof($add);$i++)
			{
				// echo $add[$i].'_'.$idmc[$i].'_('.$prixmc[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation='.$idprestamc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_consult mc SET mc.id_assuServ='.$_GET['idassu'].',mc.insupercentServ='.$add[$i].',mc.prixprestationConsu='.$prixmc[$i].',mc.prixautreConsu=0 WHERE mc.id_medconsu='.$idmc[$i].'');
						
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_consult mc WHERE mc.id_medconsu='.$idmc[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consult mc SET mc.id_assuServ='.$_GET['idassu'].',mc.insupercentServ='.$add[$i].',mc.prixprestationConsu=0,mc.prixautreConsu='.$prixmc[$i].' WHERE mc.id_medconsu='.$idmc[$i].'');
						
					}
				}

			}
		}
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_consuMed=:idconsu AND mc.dateconsu!="0000-00-00" AND mc.id_assuServ=:idassu AND mc.id_factureMedConsu!=0 ORDER BY mc.id_medconsu');		
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_inf-----------*/
	
					
		if(isset($_POST['idprestaInf']))
		{
			
			$idassuInf=$idassu;

			$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

			$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuInf->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuInf=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuInf->execute(array(
				'idassu'=>$idassuInf
				));
				
				$getAssuInf->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssuInf=$getAssuInf->fetch())
				{
					$presta_assuInf='prestations_'.$ligneNomAssuInf->nomassurance;
				}
			}



			$idprestami = array();
			$prixmi = array();
			$addInf = array();
			$idmi = array();
			$autremi = array();

			foreach($_POST['idprestaInf'] as $mi)
			{
				$idprestami[] = $mi;
			}
			
			foreach($_POST['prixprestaInf'] as $valmi)
			{
				$prixmi[] = $valmi;
			}
			
			foreach($_POST['percentInf'] as $valeurInf)
			{
				$addInf[] = $valeurInf;
			}
			
			foreach($_POST['idmedInf'] as $valeurmi)
			{
				$idmi[] = $valeurmi;
			}
			
			foreach($_POST['autreInf'] as $autrevaluemi)
			{
				$autremi[] = $autrevaluemi;
			}
			
			
			for($i=0;$i<sizeof($addInf);$i++)
			{
				
				// echo $addInf[$i].'_'.$idmi[$i].'_('.$prixmi[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation='.$idprestami[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_inf mi SET mi.id_assuInf='.$_GET['idassu'].',mi.insupercentInf='.$addInf[$i].',mi.prixprestation='.$prixmi[$i].',mi.prixautrePrestaM=0 WHERE mi.id_medinf='.$idmi[$i].'');
						
				}else{
				
					$results=$connexion->query('SELECT *FROM med_inf mi WHERE mi.id_medinf='.$idmi[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_inf mi SET mi.id_assuInf='.$_GET['idassu'].',mi.insupercentInf='.$addInf[$i].',mi.prixprestation=0,mi.prixautrePrestaM='.$prixmi[$i].' WHERE mi.id_medinf='.$idmi[$i].'');
						
					}
				}
				
			}
		}
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_consuInf=:idconsu AND mi.id_assuInf=:idassu AND mi.id_factureMedInf!=0 ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_labo-----------*/
	
					
		if(isset($_POST['idprestaLab']))
		{
			$idassuLab=$idassu;

			$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

			$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuLab->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuLab->execute(array(
				'idassu'=>$idassuLab
				));
				
				$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssuLab=$getAssuLab->fetch())
				{
					$presta_assuLab='prestations_'.$ligneNomAssuLab->nomassurance;
				}
			}

			

			$idprestaml = array();
			$prixml = array();
			$addLab = array();
			$idml = array();
			$autreml = array();


			foreach($_POST['idprestaLab'] as $ml)
			{
				$idprestaml[] = $ml;
			}
			
			foreach($_POST['prixprestaLab'] as $valml)
			{
				$prixml[] = $valml;
			}
			
			foreach($_POST['percentLab'] as $valeurLab)
			{
				$addLab[] = $valeurLab;
			}
			
			foreach($_POST['idmedLab'] as $valeurml)
			{
				$idml[] = $valeurml;
			}
			
			foreach($_POST['autreLab'] as $autrevalueml)
			{
				$autreml[] = $autrevalueml;
			}
			
			for($i=0;$i<sizeof($addLab);$i++)
			{
				// echo $addLab[$i].'_'.$idml[$i].'_('.$prixml[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation='.$idprestaml[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_labo ml SET ml.id_assuLab='.$_GET['idassu'].',ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa='.$prixml[$i].',ml.prixautreExamen=0 WHERE ml.id_medlabo='.$idml[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_labo ml WHERE ml.id_medlabo='.$idml[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_labo ml SET ml.id_assuLab='.$_GET['idassu'].',ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa=0,ml.prixautreExamen='.$prixml[$i].' WHERE ml.id_medlabo='.$idml[$i].'');
						
					}
				}
			}
		}
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_consuLabo=:idconsu AND ml.id_factureMedLabo!=0 ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();			
		
		$TotalMedLabo = 0;
	
	
	
	
		/*-------Requête pour AFFICHER Med_radio-----------*/
	
					
		if(isset($_POST['idprestaRad']))
		{
			$idassuRad=$idassu;

			$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

			$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuRad->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuRad=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuRad->execute(array(
				'idassu'=>$idassuRad
				));
				
				$getAssuRad->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssuRad=$getAssuRad->fetch())
				{
					$presta_assuRad='prestations_'.$ligneNomAssuRad->nomassurance;
				}
			}

										
			$idprestamr = array();
			$prixmr = array();
			$addRad = array();
			$idmr = array();
			$autremr = array();


			foreach($_POST['idprestaRad'] as $mr)
			{
				$idprestamr[] = $mr;
			}
			
			foreach($_POST['prixprestaRad'] as $valmr)
			{
				$prixmr[] = $valmr;
			}
			
			foreach($_POST['percentRad'] as $valeurRad)
			{
				$addRad[] = $valeurRad;
			}
			
			foreach($_POST['idmedRad'] as $valeurmr)
			{
				$idmr[] = $valeurmr;
			}
			
			foreach($_POST['autreRad'] as $autrevaluemr)
			{
				$autremr[] = $autrevaluemr;
			}
			
			for($i=0;$i<sizeof($addRad);$i++)
			{
				
				// echo $addRad[$i].'_'.$idmr[$i].'_('.$prixmr[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation='.$idprestamr[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_radio mr SET mr.id_assuRad='.$_GET['idassu'].',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio='.$prixmr[$i].',mr.prixautreRadio=0 WHERE mr.id_medradio='.$idmr[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_radio mr WHERE mr.id_medradio='.$idmr[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_radio mr SET mr.id_assuRad='.$_GET['idassu'].',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio=0,mr.prixautreRadio='.$prixmr[$i].' WHERE mr.id_medradio='.$idmr[$i].'');
						
					}
				}
			}
		}
		
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_consuRadio=:idconsu AND mr.id_factureMedRadio!=0 ORDER BY mr.id_medradio');		
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();			
		
		$TotalMedRadio = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_consom-----------*/
					
		if(isset($_POST['idprestaConsom']))
		{
			$idassuConsom=$idassu;
			

			$comptAssuConsom=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

			$comptAssuConsom->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuConsom->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsom=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuConsom->execute(array(
				'idassu'=>$idassuConsom
				));
				
				$getAssuConsom->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssuConsom=$getAssuConsom->fetch())
				{
					$presta_assuConsom='prestations_'.$ligneNomAssuConsom->nomassurance;
				}
			}



			$idprestaconsom = array();
			$prixmco = array();
			$addConsom = array();
			$idmco = array();
			$autreconsom = array();
			$qteConsom = array();


			foreach($_POST['idprestaConsom'] as $consom)
			{
				$idprestaconsom[] = $consom;
			}
			
			foreach($_POST['prixprestaConsom'] as $valConsom)
			{
				$prixmco[] = $valConsom;
			}
			
			foreach($_POST['percentConsom'] as $valeurConsom)
			{
				$addConsom[] = $valeurConsom;
			}
			
			foreach($_POST['idmedConsom'] as $valeurmco)
			{
				$idmco[] = $valeurmco;
			}
			
			foreach($_POST['autreConsom'] as $autrevalueconsom)
			{
				$autreconsom[] = $autrevalueconsom;
			}

			foreach($_POST['quantityConsom'] as $valueConsom)
			{
				$qteConsom[] = $valueConsom;
			}
			
			for($i=0;$i<sizeof($addConsom);$i++)
			{
			
				// echo $addConsom[$i].'_'.$idmco[$i].'_('.$prixmco[$i].' : '.$qteConsom[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuConsom.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation='.$idprestaconsom[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_consom mco SET mco.id_assuConsom='.$_GET['idassu'].',mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom='.$prixmco[$i].',mco.prixautreConsom=0,mco.qteConsom='.$qteConsom[$i].' WHERE mco.id_medconsom='.$idmco[$i].'');
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_consom mco WHERE mco.id_medconsom='.$idmco[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consom mco SET mco.id_assuConsom='.$_GET['idassu'].',mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom=0,mco.prixautreConsom='.$prixmco[$i].', mco.qteConsom='.$qteConsom[$i].' WHERE mco.id_medconsom='.$idmco[$i].'');
					}
				}
			}
		}
		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_consuConsom=:idconsu AND mco.id_factureMedConsom!=0 ORDER BY mco.id_medconsom');		
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_medoc-----------*/
	
							
		if(isset($_POST['idprestaMedoc']))
		{
			$idassuMedoc=$idassu;
	
			
			$comptAssuMedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

			$comptAssuMedoc->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuMedoc->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuMedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuMedoc->execute(array(
				'idassu'=>$idassuMedoc
				));
				
				$getAssuMedoc->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssuMedoc=$getAssuMedoc->fetch())
				{
					$presta_assuMedoc='prestations_'.$ligneNomAssuMedoc->nomassurance;
				}
			}

			

			$idprestamedoc = array();
			$prixmdo = array();
			$addMedoc = array();
			$idmdo = array();
			$autremedoc = array();
			$qteMedoc = array();


			foreach($_POST['idprestaMedoc'] as $medoc)
			{
				$idprestamedoc[] = $medoc;
			}
			
			foreach($_POST['prixprestaMedoc'] as $valMedoc)
			{
				$prixmdo[] = $valMedoc;
			}
			
			foreach($_POST['percentMedoc'] as $valeurMedoc)
			{
				$addMedoc[] = $valeurMedoc;
			}
			
			foreach($_POST['idmedMedoc'] as $valeurmdo)
			{
				$idmdo[] = $valeurmdo;
			}
			
			foreach($_POST['autreMedoc'] as $autrevaluemedoc)
			{
				$autremedoc[] = $autrevaluemedoc;
			}

			foreach($_POST['quantityMedoc'] as $valueMedoc)
			{
				$qteMedoc[] = $valueMedoc;
			}
			
			for($i=0;$i<sizeof($addMedoc);$i++)
			{
			
				// echo $addMedoc[$i].'_'.$idmdo[$i].'_('.$prixmdo[$i].' : '.$qteMedoc[$i].')<br/>';
				
				
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuMedoc.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation='.$idprestamedoc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_medoc mdo SET mdo.id_assuMedoc='.$_GET['idassu'].',mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc='.$prixmdo[$i].',mdo.prixautreMedoc=0,mdo.qteMedoc='.$qteMedoc[$i].' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_medoc mdo WHERE mdo.id_medmedoc='.$idmdo[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_medoc mdo SET mdo.id_assuMedoc='.$_GET['idassu'].',mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc=0,mdo.prixautreMedoc='.$prixmdo[$i].', mdo.qteMedoc='.$qteMedoc[$i].' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
					
					}
				}				
			}
		}
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_consuMedoc=:idconsu AND mdo.id_factureMedMedoc!=0 ORDER BY mdo.id_medmedoc');		
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();			
		
		$TotalMedMedoc = 0;
	
	?>
	
		<table style="width:100%; margin:20px auto auto;"> 
			<tr> 
				<td style="text-align:left; width:33%;">
					<h4><?php echo $annee;?></h4>
				</td>
				
				<td style="text-align:center; width:66%;">
					<h2 style="font-size:150%; font-weight:600;">Formulaire de remboursement de la facture n° <?php echo $numbill;?></h2>
				</td>
				
				<td class="buttonBill">
					<a href="listfacture.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>">
						<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
					</a>
					
					<a href="listfacture.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>">
						<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(141);?></button>
					</a>
				</td>
			</tr>
		</table>
		
	
		<form method="post" action="<?php if($comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedRadio!=0){ echo 'printBill_rembou.php';}else{ echo 'printConsuBill_rembou.php';}?>?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&dateconsu=<?php echo $dateconsu;?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}?>&back=ok" onsubmit="return controlFormRembou(this)" enctype="multipart/form-data">

	<?php
		try
		{
			$TotalGnlPrice=0;
			$TotalGnlPatientPrice=0;
			$TotalGnlInsurancePrice=0;
			$i=0;
			$x=0;
			$y=0;
			$z=0;
			
			if($comptConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B8', 'Type of consultation')
							->setCellValue('C8', 'Price')
							->setCellValue('D8', 'Patient Price')
							->setCellValue('E8', 'Insurance Price');
				

		$typeconsult = '<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;"> 
				<thead> 
					<tr>
						<th style="width:60%;">Type of Consultation</th>
						<th style="width:20%;">Balance</th>';
						
				
					if($comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0)
					{	
						$typeconsult .= '<th style="width:20%;"></th>';
					}else{
						$typeconsult .= '<th style="width:20%;">Prix à rembourser</th>';			
					}
						
		$typeconsult .= '</tr> 
				</thead>

				<tbody>';
				
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneConsult=$resultatConsult->fetch())
					{
					
						$billpercent=$ligneConsult->insupercent;
						
						$idassu=$ligneConsult->id_assuConsu;					
		$typeconsult .= '<tr style="text-align:center;">
						<td>';
						
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

						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');

						$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($comptPresta!=0)
						{
							
							if($lignePresta=$resultPresta->fetch())
							{
								
								if($lignePresta->namepresta!='')
								{
									$nameprestaConsult=$lignePresta->namepresta;
									
			$typeconsult .= $lignePresta->namepresta.'</td>';
								}else{	
								
									if($lignePresta->nompresta!='')
									{
										$nameprestaConsult=$lignePresta->nompresta;
			$typeconsult .= $lignePresta->nompresta.'</td>';
									}
								}
				
							$prixpresta=$ligneConsult->prixtypeconsult;
						
						$TotalConsult=$TotalConsult + $prixpresta;
						
		$typeconsult .= '<td style="font-weight:bold">';

							$patientPrice=($prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								

		if($comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0)
		{
			$display='display:none';
			$prixRembou=0;

		}else{
			$display='';
			$prixRembou=$prixpresta;
			
		}
						
			$typeconsult .= $prixpresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
									
						<td style="'.$display.'">
							<input type="text" name="prixrembou" id="prixrembou" class="prixrembou" value="'.$prixRembou.'" style="width:30%; font-weight:normal;margin-top:5px;"/><span style="font-size:70%;font-weight:normal;">Rwf</span>
							
							<input type="hidden" name="patientprice" id="patientprice" class="patientprice" value="'.$patientPrice.'" style="width:30%; font-weight:normal;margin-top:5px;"/>
						</td>';
						
							$uapPrice= $prixpresta - $patientPrice;
							$TotaluapPrice = $TotaluapPrice + $uapPrice;
							
							}
							
						}else{
						
							if(isset($_POST['newprixtypeconsult']))
							{
								if(isset($_POST['pourcentage']))
								{
									$resultats=$connexion->prepare('UPDATE consultations SET prixautretypeconsult=:prixautretypeconsu,insupercent=:percent WHERE id_consu=:idConsult');
						
									$resultats->execute(array(
									'prixautretypeconsu'=>$_POST['newprixtypeconsult'],
									'percent'=>$_POST['pourcentage'],
									'idConsult'=>$_GET['idconsu']
									
									))or die( print_r($connexion->errorInfo()));
								}
							}
							
							$resultNewPresta=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idconsu');	
							
							$resultNewPresta->execute(array(
							'idconsu'=>$_GET['idconsu']
							));
							
							$resultNewPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptNewPresta=$resultNewPresta->rowCount();
							
							if($lignePresta=$resultNewPresta->fetch())
							{
								$nameprestaConsult=$lignePresta->autretypeconsult;
									
			$typeconsult .= $lignePresta->autretypeconsult.'</td>';
			
								$prixpresta = $lignePresta->prixautretypeconsult;
					
				
			$TotalConsult=$TotalConsult + $lignePresta->prixautretypeconsult;
						
		$typeconsult .= '<td style="font-weight:bold>';

				$patientPrice=($prixpresta * $billpercent)/100;
				$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								

		if($comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0)
		{
			$display='display:none';
			$prixRembou=0;
				
		}else{
			$display='';
			$prixRembou=$prixpresta;
		}
								
			$typeconsult .= $prixpresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
									
						<td style="'.$display.'">
							<input type="text" name="prixrembou" id="prixrembou" class="prixrembou" value="'.$prixRembou.'" style="width:30%; font-weight:normal;margin-top:5px;"/><span style="font-size:70%;font-weight:normal;">Rwf</span>
							
							<input type="hidden" name="patientprice" id="patientprice" class="patientprice" value="'.$patientPrice.'" style="width:30%; font-weight:normal;margin-top:5px;"/>
						</td>';
				
			
				$uapPrice= $lignePresta->prixautretypeconsult - $patientPrice;
				$TotaluapPrice= $TotaluapPrice + $uapPrice;
				
							}
						}
					
		$typeconsult .= '</tr>';
						
						$arrayConsult[$i][0]=$nameprestaConsult;
						$arrayConsult[$i][1]=$prixpresta;
						$arrayConsult[$i][2]=$patientPrice;
						$arrayConsult[$i][3]=$uapPrice;
						
						$i++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayConsult,'','B9');
		
					}
					
				$TotalGnlPrice=$TotalGnlPrice + $TotalConsult;
								
				$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;			
				
				$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
				
		 
	$typeconsult .= '</tbody>
			</table>';

					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(9+$i).'', ''.$TotalConsult.'')
								->setCellValue('D'.(9+$i).'', ''.$TotalpatientPrice.'')
								->setCellValue('E'.(9+$i).'', ''.$TotaluapPrice.'');

				echo $typeconsult;
			
			}
		
			
			if($comptMedConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(11+$i).'', 'Services')
							->setCellValue('C'.(11+$i).'', 'Price')
							->setCellValue('D'.(11+$i).'', 'Patient Price')
							->setCellValue('E'.(11+$i).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead> 
					<tr>
						<th style="width:60%">Services</th>
						<th style="width:20%;">Balance</th>
						<th style="width:20%;">Prix à rembourser</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;			

			
					while($ligneMedConsult=$resultMedConsult->fetch())
					{						
							
						$billpercent=$ligneMedConsult->insupercentServ;
						
						$idassu=$ligneMedConsult->id_assuServ;													
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

			?>
					<tr style="text-align:center;">
						<td>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedConsult->id_prestationConsu
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
						
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedConsult=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedConsult=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}

							$prixPresta = $ligneMedConsult->prixprestationConsu;
							
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
						
							$TotalMedConsult=$TotalMedConsult + $prixPresta;
						?>
						
						<!--
						<td>
							<?php 
								$patientPrice=($prixPresta * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								// echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						-->
						
						<td>
							<input type="text" name="prixrembouServ[]" id="prixrembouServ" class="prixrembouServ" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
							<input type="hidden" name="balanceServ[]" id="balanceServ" class="balanceServ" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
							<input type="hidden" name="idrembouServ[]" id="idrembouServ" class="idrembouServ" value="<?php echo $ligneMedConsult->id_medconsu;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
							
						<?php 
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}
						
						if($ligneMedConsult->id_prestationConsu=="" AND $ligneMedConsult->prixautreConsu!=0)
						{
							$nameprestaMedConsult=$ligneMedConsult->autreConsu;
							echo $ligneMedConsult->autreConsu.'</td>';
							
							$prixPresta = $ligneMedConsult->prixautreConsu;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedConsult=$TotalMedConsult + $prixPresta;
						?>
						
								<?php 
								$patientPrice=($prixPresta * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								?>		
							<td>
								<input type="text" name="prixrembouServ[]" id="prixrembouServ" class="prixrembouServ" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
								
								<input type="hidden" name="balanceServ[]" id="balanceServ" class="balanceServ" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
								<input type="hidden" name="idrembouServ[]" id="idrembouServ" class="idrembouServ" value="<?php echo $ligneMedConsult->id_medconsu;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
						
								<?php 
								$uapPrice= $prixPresta - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								?>
							
			<?php
						}
							
						$arrayMedConsult[$x][0]=$nameprestaMedConsult;
						$arrayMedConsult[$x][1]=$prixPresta;
						$arrayMedConsult[$x][2]=$patientPrice;
						$arrayMedConsult[$x][3]=$uapPrice;
						
						$x++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedConsult,'','B'.(12+$i).'');
		
					}
			?>		
					</tr>	
					<tr style="text-align:center;">
											
						<td></td>
						<?php						
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
						?>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedConsult.'';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
											
						<td style="font-size: 13px; font-weight: bold;">
							<?php							
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;								
							?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(12+$i+$x).'', ''.$TotalMedConsult.'')
								->setCellValue('D'.(12+$i+$x).'', ''.$TotalpatientPrice.'')
								->setCellValue('E'.(12+$i+$x).'', ''.$TotaluapPrice.'');
			
			}
			
			
			if($comptMedInf != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(14+$i+$x).'', 'Nursing Care')
							->setCellValue('C'.(14+$i+$x).'', 'Price')
							->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
							->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead> 
					<tr>
						<th style="width:40%">Nursing Care</th>
						<th style="width:15%;">Balance</th>
						<th style="width:15%;">Prix à rembourser</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedInf=$resultMedInf->fetch())
					{
					
						$billpercent=$ligneMedInf->insupercentInf;
						
						$idassu=$ligneMedInf->id_assuInf;													
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

			?>
					<tr style="text-align:center;">
						<td>
						<?php 
							
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
							'prestaId'=>$ligneMedInf->id_prestation
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedInf=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedInf=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedInf->prixprestation;
									
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
											
							$TotalMedInf = $TotalMedInf + $prixPresta;
						?>
						</td>
						
						
								<?php 
								$patientPrice=($prixPresta * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								?>
						
						<td>
							<input type="text" name="prixrembouInf[]" id="prixrembouInf" class="prixrembouInf" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
							<input type="hidden" name="balanceInf[]" id="balanceInf" class="balanceInf" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
							<input type="hidden" name="idrembouInf[]" id="idrembouInf" class="idrembouInf" value="<?php echo $ligneMedInf->id_medinf;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
							
						<?php 
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
					
						}
						
						if($ligneMedInf->id_prestation=="" AND $ligneMedInf->prixautrePrestaM!=0)
						{
							$nameprestaMedInf=$ligneMedInf->autrePrestaM;
							echo $ligneMedInf->autrePrestaM.'aa'.$ligneMedInf->id_prestation.'</td>';
							
							
							$prixPresta = $ligneMedInf->prixautrePrestaM;
										
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
											
							$TotalMedInf = $TotalMedInf + $prixPresta;
			?>
			
							<?php 
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							?>
						
						<td>
							<input type="text" name="prixrembouInf[]" id="prixrembouInf" class="prixrembouInf" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
							<input type="hidden" name="balanceInf[]" id="balanceInf" class="balanceInf" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
							<input type="hidden" name="idrembouInf[]" id="idrembouInf" class="idrembouInf" value="<?php echo $ligneMedInf->id_medinf;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
							
						<?php 
							$uapPrice= $prixPresta - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;

						}
						
							
						$arrayMedInf[$y][0]=$nameprestaMedInf;
						$arrayMedInf[$y][1]=$prixPresta;
						$arrayMedInf[$y][2]=$patientPrice;
						$arrayMedInf[$y][3]=$uapPrice;
						
						$y++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedInf,'','B'.(15+$i+$x).'');
					}
			?>
					</tr>
					<tr style="text-align:center;">
											
						<td></td>
						<?php						
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
						?>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedInf.'';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
											
						<td style="font-size: 13px; font-weight: bold;">
							<?php							
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;								
							?>
						</td>

					</tr>
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedInf.'')
							->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');
			
			}
			
			
			if($comptMedLabo != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(17+$i+$x+$y).'', 'Labs')
							->setCellValue('C'.(17+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(17+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(17+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead> 
					<tr>
						<th style="width:40%">Labs</th>
						<th style="width:15%;">Balance</th>
						<th style="width:15%;">Prix à rembourser</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedLabo=$resultMedLabo->fetch())
					{					
						$billpercent=$ligneMedLabo->insupercentLab;
						
						$idassu=$ligneMedLabo->id_assuLab;													
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

			?>
					<tr style="text-align:center;">
						<td>
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedLabo->id_prestationExa
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
							
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedLabo=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedLabo=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedLabo->prixprestationExa;
						
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedLabo = $TotalMedLabo + $prixPresta;
							?>
						
								<?php 
								$patientPrice=($prixPresta * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								?>
					
						<td>
							<input type="text" name="prixrembouLabo[]" id="prixrembouLabo" class="prixrembouLabo" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
							<input type="hidden" name="balanceLabo[]" id="balanceLabo" class="balanceLabo" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
							<input type="hidden" name="idrembouLabo[]" id="idrembouLabo" class="idrembouLabo" value="<?php echo $ligneMedLabo->id_medlabo;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
							
						<?php 
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						
						}
						
						if($ligneMedLabo->id_prestationExa=="" AND $ligneMedLabo->prixautreExamen!=0)
						{
							$nameprestaMedLabo=$ligneMedLabo->autreExamen;
							echo $ligneMedLabo->autreExamen.'</td>';
							
							$prixPresta = $ligneMedLabo->prixautreExamen;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedLabo = $TotalMedLabo + $prixPresta;
						?>
						
							<?php 
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							?>
						
						<td>
							<input type="text" name="prixrembouLabo[]" id="prixrembouLabo" class="prixrembouLabo" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
							<input type="hidden" name="balanceLabo[]" id="balanceLabo" class="balanceLabo" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
							<input type="hidden" name="idrembouLabo[]" id="idrembouLabo" class="idrembouLabo" value="<?php echo $ligneMedLabo->id_medlabo;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
						
						<?php 
							$uapPrice= $prixPresta - $patientPrice;							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						
						}
							
						$arrayMedLabo[$z][0]=$nameprestaMedLabo;
						$arrayMedLabo[$z][1]=$prixPresta;
						$arrayMedLabo[$z][2]=$patientPrice;
						$arrayMedLabo[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedLabo,'','B'.(18+$i+$x+$y).'');
					}
			?>
					</tr>
					<tr style="text-align:center;">
											
						<td></td>
						<?php						
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
						?>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedLabo.'';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
											
						<td style="font-size: 13px; font-weight: bold;">
							<?php							
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;								
							?>
						</td>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(18+$i+$x+$y+$z).'', ''.$TotalMedLabo.'')
							->setCellValue('D'.(18+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(18+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}
			
						
			if($comptMedRadio != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(21+$i+$x+$y).'', 'Radio')
							->setCellValue('C'.(21+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(21+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(21+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead> 
					<tr>
						<th style="width:40%">Radiologie</th>
						<th style="width:15%;">Balance</th>
						<th style="width:15%;">Prix à rembourser</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedRadio=$resultMedRadio->fetch())
					{
					
						$billpercent=$ligneMedRadio->insupercentRad;
						
						$idassu=$ligneMedRadio->id_assuRad;													
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

			?>
					<tr style="text-align:center;">
						<td>
							<?php
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
							
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedRadio->id_prestationRadio
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptPresta=$resultPresta->rowCount();
							
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedRadio=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedRadio=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedRadio->prixprestationRadio;
						
						
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedRadio = $TotalMedRadio + $prixPresta;
							?>
						</td>

								<?php 
								$patientPrice=($prixPresta * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								?>
						
						<td>
							<input type="text" name="prixrembouRadio[]" id="prixrembouRadio" class="prixrembouRadio" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
							<input type="hidden" name="balanceRadio[]" id="balanceRadio" class="balanceRadio" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
							<input type="hidden" name="idrembouRadio[]" id="idrembouRadio" class="idrembouRadio" value="<?php echo $ligneMedRadio->id_medradio;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
							
						<?php 
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
						}
						
						if($ligneMedRadio->id_prestationRadio=="" AND $ligneMedRadio->prixautreRadio!=0)
						{
							$nameprestaMedRadio=$ligneMedRadio->autreRadio;
							echo $ligneMedRadio->autreRadio.'</td>';
							
							$prixPresta = $ligneMedRadio->prixautreRadio;
							
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedRadio = $TotalMedRadio + $prixPresta;
						?>
						
							<?php 
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							?>
						
						<td>
							<input type="text" name="prixrembouRadio[]" id="prixrembouRadio" class="prixrembouRadio" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
							<input type="hidden" name="balanceRadio[]" id="balanceRadio" class="balanceRadio" value="<?php echo $prixPresta;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
							<input type="hidden" name="idrembouRadio[]" id="idrembouRadio" class="idrembouRadio" value="<?php echo $ligneMedRadio->id_medradio;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
							
						<?php 
							$uapPrice= $prixPresta - $patientPrice;							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
						}
							
						$arrayMedRadio[$z][0]=$nameprestaMedRadio;
						$arrayMedRadio[$z][1]=$prixPresta;
						$arrayMedRadio[$z][2]=$patientPrice;
						$arrayMedRadio[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedRadio,'','B'.(18+$i+$x+$y).'');
					}
			?>
					</tr>
					<tr style="text-align:center;">						
						<td></td>
						<?php						
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
						?>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedRadio.'';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						<?php						
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;																	
						?>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(18+$i+$x+$y+$z).'', ''.$TotalMedRadio.'')
							->setCellValue('D'.(18+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(18+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}
			
			
			if($comptMedConsom != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(20+$i+$x+$y).'', 'Consommables')
							->setCellValue('C'.(20+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(20+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(20+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead> 
					<tr>
						<th style="width:40%">Consommables</th>
						<th style="width:15%;">Balance</th>
						<th style="width:15%;">Prix à rembourser</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedConsom=$resultMedConsom->fetch())
					{
					
						$billpercent=$ligneMedConsom->insupercentConsom;
						
						$idassu=$ligneMedConsom->id_assuConsom;													
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


						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');	
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedConsom->id_prestationConsom
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
					
						if($comptPresta==0)
						{
							$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedConsom->id_prestationConsom
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
						}
							
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
						?>
							<tr style="text-align:center;">
								<td>
									<?php
									if($lignePresta->namepresta!='')
									{
										$nameprestaMedConsom=$lignePresta->namepresta;
										echo $lignePresta->namepresta;
									
									}else{
									
										$nameprestaMedConsom=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
								
									}
									?>
								</td>
								<?php									
									$qteConsom=$ligneMedConsom->qteConsom;	
									$prixPresta = $ligneMedConsom->prixprestationConsom;
								?>
								
								<td>
								<?php
									$balance=$prixPresta*$qteConsom;
									
									echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
									
									$TotalMedConsom=$TotalMedConsom + $balance;
								?>				
								</td>
								
									<?php 
									$patientPrice=($balance * $billpercent)/100;
									
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									?>
										
								<td>
									<input type="text" name="prixrembouConsom[]" id="prixrembouConsom" class="prixrembouConsom" value="<?php echo $balance;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
									
									<input type="hidden" name="balanceConsom[]" id="balanceConsom" class="balanceConsom" value="<?php echo $balance;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
									<input type="hidden" name="idrembouConsom[]" id="idrembouConsom" class="idrembouConsom" value="<?php echo $ligneMedConsom->id_medconsom;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
								</td>
									
								<?php
								$uapPrice= $balance - $patientPrice;									
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								?>
								
							</tr>						
						<?php
						}
						
						if($ligneMedConsom->id_prestationConsom==0 AND $ligneMedConsom->prixautreConsom!=0)
						{
						?>
							<tr>
								<td>
								<?php
									$nameprestaMedConsom=$ligneMedConsom->autreConsom;
									echo $nameprestaMedConsom;
								?>
								</td>
							
									<?php									
									$qteConsom=$ligneMedConsom->qteConsom;	
									$prixPresta = $ligneMedConsom->prixautreConsom;
								
									?>
							
								<td>
								<?php
									$balance=$prixPresta*$qteConsom;
									
									echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
									
									$TotalMedConsom=$TotalMedConsom + $balance;
								?>				
								</td>
								
									<?php 
									$patientPrice=($balance * $billpercent)/100;
									
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;									
									?>
									
								<td>
									<input type="text" name="prixrembouConsom[]" id="prixrembouConsom" class="prixrembouConsom" value="<?php echo $balance;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
									
									<input type="hidden" name="balanceConsom[]" id="balanceConsom" class="balanceConsom" value="<?php echo $balance;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
									<input type="hidden" name="idrembouConsom[]" id="idrembouConsom" class="idrembouConsom" value="<?php echo $ligneMedConsom->id_medconsom;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
								</td>
								
									<?php
									$uapPrice= $balance - $patientPrice;
									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;									
									?>
							</tr>
			<?php
						}
							
						$arrayMedConsom[$z][0]=$nameprestaMedConsom;
						$arrayMedConsom[$z][1]=$prixPresta;
						$arrayMedConsom[$z][2]=$patientPrice;
						$arrayMedConsom[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedConsom,'','B'.(21+$i+$x+$y).'');
					}
			?>
					<tr style="text-align:center;">
						
						<td></td>
						<?php						
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
						?>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedConsom.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
			// float round(float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP)
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						<?php						
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;																	
						?>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(21+$i+$x+$y+$z).'', ''.$TotalMedConsom.'')
							->setCellValue('D'.(21+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(21+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}
			
			if($comptMedMedoc != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(23+$i+$x+$y).'', 'Medocs')
							->setCellValue('C'.(23+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(23+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(23+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead> 
					<tr>
						<th style="width:40%">Medicaments</th>
						<th style="width:15%;">Balance</th>
						<th style="width:15%;">Prix à rembourser</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedMedoc=$resultMedMedoc->fetch())
					{
					
						$billpercent=$ligneMedMedoc->insupercentMedoc;
						
						$idassu=$ligneMedMedoc->id_assuMedoc;													
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

					
					$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation=:prestaId');	
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedMedoc->id_prestationMedoc
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
				
					if($comptPresta==0)
					{
						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation=:prestaId');
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedMedoc->id_prestationMedoc
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);
					}
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
					?>
						<tr style="text-align:center;">
							<td>
							<?php
								if($lignePresta->namepresta!='')
								{
									$nameprestaMedMedoc=$lignePresta->namepresta;
									echo $lignePresta->namepresta;
								
								}else{
								
									$nameprestaMedMedoc=$lignePresta->nompresta;
									echo $lignePresta->nompresta;
							
								}
														
								$qteMedoc=$ligneMedMedoc->qteMedoc;
								
								$prixPresta = $ligneMedMedoc->prixprestationMedoc;
							?>
							
							<td>
							<?php
								$balance=$prixPresta*$qteMedoc;
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							?>				
							</td>
							
								<?php 
								$patientPrice=($balance * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								?>
								
							<td>
								<input type="text" name="prixrembouMedoc[]" id="prixrembouMedoc" class="prixrembouMedoc" value="<?php echo $balance;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
								
								<input type="hidden" name="balanceMedoc[]" id="balanceMedoc" class="balanceMedoc" value="<?php echo $balance;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
								<input type="hidden" name="idrembouMedoc[]" id="idrembouMedoc" class="idrembouMedoc" value="<?php echo $ligneMedMedoc->id_medmedoc;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
						
							<?php
								$uapPrice= $balance - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							?>
							
						</tr>
					
					<?php
					}
					
					if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc!=0)
					{
					?>
						<tr style="text-align:center;">
							<td>
							<?php
								$nameprestaMedMedoc=$ligneMedMedoc->autreMedoc;
								echo $nameprestaMedMedoc;
															
								$qteMedoc=$ligneMedMedoc->qteMedoc;
									
								$prixPresta = $ligneMedMedoc->prixautreMedoc;
							?>
							
							<td>
							<?php
								$balance=$prixPresta*$qteMedoc;
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							?>				
							</td>
						
								<?php 
								$patientPrice=($balance * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								?>
							
							<td>
								<input type="text" name="prixrembouMedoc[]" id="prixrembouMedoc" class="prixrembouMedoc" value="<?php echo $balance;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
								
								<input type="hidden" name="balanceMedoc[]" id="balanceMedoc" class="balanceMedoc" value="<?php echo $balance;?>" style="width:30%; font-weight:normal;margin-top:5px;"/>
							
								<input type="hidden" name="idrembouMedoc[]" id="idrembouMedoc" class="idrembouMedoc" value="<?php echo $ligneMedMedoc->id_medmedoc;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>

							<?php
								$uapPrice= $balance - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;								
							?>
						</tr>
			<?php
					}
							
						$arrayMedMedoc[$z][0]=$nameprestaMedMedoc;
						$arrayMedMedoc[$z][1]=$prixPresta;
						$arrayMedMedoc[$z][2]=$patientPrice;
						$arrayMedMedoc[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedMedoc,'','B'.(21+$i+$x+$y).'');
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<?php						
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
						?>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedMedoc.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td></td>
						<?php						
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;																	
						?>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(24+$i+$x+$y+$z).'', ''.$TotalMedMedoc.'')
							->setCellValue('D'.(24+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(24+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}
			
			
		}	

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
		}

		?>
			<table class="tablesorter tablesorter1" cellspacing="0" style="background:none;border:none; width:70%;">
				<tr>
					<td>
						<button type="submit" id="previewbtn" name="previewbtn" class="btn-large"/>
							<i class="fa fa-desktop fa-lg fa-fw"></i> <?php echo getString(148);?>
						</button>
					</td>
				</tr>
			</table>
		
	</form>
	</div>

<?php
		if(isset($_GET['updatebill']))
		{
			
			/*----------Update Bills----------------*/
						
			$updateIdBill=$connexion->prepare('UPDATE bills b SET  b.numero=:num, b.nomassurance=:nomassu WHERE b.id_bill=:idbill');

			$updateIdBill->execute(array(
			'idbill'=>$idBilling,
			'num'=>$_GET['num'],
			'nomassu'=>$nomassurance
			
			))or die( print_r($connexion->errorInfo()));
			
			
			$callStartTime = microtime(true);

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			
			$IdBill=str_replace('/', '_', showBN());
			
			// $objWriter->save('C:/wamp/www/stjean/BillFiles/Bill#'.$IdBill.'.xlsx');
			$callEndTime = microtime(true);
			$callTime = $callEndTime - $callStartTime;
			
			// createBN();
			
			echo '<script text="text/javascript">document.location.href="printBill_rembou.php?num='.$_GET['num'].'&cashier='.$_SESSION['codeCash'].'&idconsu='.$_GET['idconsu'].'&dateconsu='.$_GET['dateconsu'].'&idbill='.$idBilling.'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idmed='.$_GET['idmed'].'&back=ok"</script>';
			
		
		}

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
	/* $file = file_get_contents("http://192.168.135.50/uap/printConsuBill.php?num=P9&cashier=CSC15A01&dateconsu=2015-09-19");
	file_put_contents("toPDF.html", $file); */

}
?>
</body>

</html>