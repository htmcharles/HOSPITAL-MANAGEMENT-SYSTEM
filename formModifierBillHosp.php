<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


if(isset($_GET['deleteHosp']))
{

	$id_Hosp = $_GET['deleteHosp'];
	$id_Bill = $_GET['idbill'];
	
	$deleteHosp=$connexion->prepare('DELETE FROM patients_hosp WHERE id_hosp=:idHosp');
	
	$deleteHosp->execute(array(
	'idHosp'=>$id_Hosp
	
	))or die( print_r($connexion->errorInfo()));
	
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer l\'hôspitalisation");</script>';
	
	echo '<script type="text/javascript">document.location.href="facturesedit.php?manager='.$_GET['manager'].'";</script>';
	
}


if(isset($_GET['deleteMedConsu']))
{

	$id_medC = $_GET['deleteMedConsu'];
	
	$deleteHosp=$connexion->prepare('DELETE FROM med_consult_hosp WHERE id_medconsu=:id_medC');
	
	$deleteHosp->execute(array(
	'id_medC'=>$id_medC
	
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un service");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBillHosp.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&numbill='.$_GET['numbill'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok#tableServ";</script>';
	
}


if(isset($_GET['deleteMedSurge']))
{

	$id_medS = $_GET['deleteMedSurge'];
	
	$deleteSurge=$connexion->prepare('DELETE FROM med_surge_hosp WHERE id_medsurge=:id_medS');
	
	$deleteSurge->execute(array(
	'id_medS'=>$id_medS
	
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un acte");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBillHosp.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&numbill='.$_GET['numbill'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok#tableSurge";</script>';
	
}


if(isset($_GET['deleteMedInf']))
{

	$id_medI = $_GET['deleteMedInf'];
	
	$deleteInf=$connexion->prepare('DELETE FROM med_inf_hosp WHERE id_medinf=:id_medI');
	
	$deleteInf->execute(array(
	'id_medI'=>$id_medI
	
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un soins");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBillHosp.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&numbill='.$_GET['numbill'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok#tableInf";</script>';
	
}

if(isset($_GET['deleteMedLabo']))
{

	$id_medL= $_GET['deleteMedLabo'];
	
	$deleteLabo=$connexion->prepare('DELETE FROM med_labo_hosp WHERE id_medlabo=:id_medL');
	
	$deleteLabo->execute(array(
	'id_medL'=>$id_medL
	
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un examen");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBillHosp.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&numbill='.$_GET['numbill'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok#tableLabo";</script>';
	
}

if(isset($_GET['deleteMedRadio']))
{

	$id_medX= $_GET['deleteMedRadio'];
	
	$deleteRadio=$connexion->prepare('DELETE FROM med_radio_hosp WHERE id_medradio=:id_medX');
	
	$deleteRadio->execute(array(
	'id_medX'=>$id_medX
	
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une radio");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBillHosp.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&numbill='.$_GET['numbill'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok#tableRadio";</script>';
	
}


if(isset($_GET['deleteMedKine']))
{
	
	$id_medK = $_GET['deleteMedKine'];
	
	$deleteKine=$connexion->prepare('DELETE FROM med_kine_hosp WHERE id_medkine=:id_medK');
	
	$deleteKine->execute(array(
		'id_medK'=>$id_medK
	
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une kiné");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBillHosp.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&numbill='.$_GET['numbill'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok#tableKine";</script>';
	
}


if(isset($_GET['deleteMedOrtho']))
{
	
	$id_medO = $_GET['deleteMedOrtho'];
	
	$deleteOrtho=$connexion->prepare('DELETE FROM med_ortho_hosp WHERE id_medortho=:id_medO');
	
	$deleteOrtho->execute(array(
		'id_medO'=>$id_medO
	
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un appareil");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBillHosp.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&numbill='.$_GET['numbill'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok#tableOrtho";</script>';
	
}

if(isset($_GET['deleteMedConsom']))
{

	$id_medCo= $_GET['deleteMedConsom'];
	
	$deleteConsom=$connexion->prepare('DELETE FROM med_consom_hosp WHERE id_medconsom=:id_medCo');
	
	$deleteConsom->execute(array(
	'id_medCo'=>$id_medCo
	
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un consommable");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBillHosp.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&numbill='.$_GET['numbill'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok#tableConsom";</script>';
	
}

if(isset($_GET['deleteMedMedoc']))
{

	$id_medMe= $_GET['deleteMedMedoc'];
	
	$deleteMedoc=$connexion->prepare('DELETE FROM med_medoc_hosp WHERE id_medmedoc=:id_medMe');
	
	$deleteMedoc->execute(array(
	'id_medMe'=>$id_medMe
	
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un medicament");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBillHosp.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&numbill='.$_GET['numbill'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok#tableMedoc";</script>';
	
}

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
	$idhosp=$_GET['idhosp'];
	
	
	$checkIdBill=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:idhosp ORDER BY ph.id_factureHosp LIMIT 1');

	$checkIdBill->execute(array(
	'idhosp'=>$_GET['idhosp']
	));
	
	$comptidBill=$checkIdBill->rowCount();

	// echo $comptidBill;
	
	if($comptidBill != 0)
	{
		$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBill->fetch();
		
		$numbill = $ligne->id_factureHosp;
		$idorgBillHosp=$ligne->idorgBillHosp;
		$idassubill=$ligne->id_assuHosp;
		$percentIdbill=$ligne->insupercent_hosp;
		$nomassurancebill=$ligne->nomassuranceHosp;
		$idcardbill=$ligne->idcardbillHosp;
		$numpolicebill=$ligne->numpolicebillHosp;
		$codecashierHosp=$ligne->codecashierHosp;
		$adherentbill=$ligne->adherentbillHosp;
		$vouchernum=$ligne->vouchernumHosp;
		
		$datehosp = $ligne->dateEntree.' '.$ligne->heureEntree;
		$datebill = $ligne->dateSortie.' '.$ligne->heureSortie;
		$createBill = 0;
		
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



<body onload="myScriptMois()">

<?php
$connected=$_SESSION['connect'];
$idCoordi=$_SESSION['id'];

if($connected==true AND isset($_SESSION['codeC']))
{
	
	// echo 'New '.$idBilling;
	
	$resultatsManager=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.id_u=:operation');
	$resultatsManager->execute(array(
	'operation'=>$idCoordi
	));

	$resultatsManager->setFetchMode(PDO::FETCH_OBJ);
	if($ligneManager=$resultatsManager->fetch())
	{
		$doneby = $ligneManager->nom_u.'  '.$ligneManager->prenom_u;
		$codecoordi = $ligneManager->codecoordi;
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
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecoordi.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	
	<div class="account-container" style="margin: 10px auto auto; width:auto; border: 1px solid #ccc; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
<?php
$barcode = '

	<table style="width:100%">
		
		<tr>
			<td colspan=2 style="text-align:center;">
				<span style="text-align:center;background:#333;border-radius:40px;color:#eee;font-weight:400;padding:5px 50px">Powered by <font>Medical File</font> , a product of Innovate Solutions Ltd. ©2022-'.date('Y').', All Rights Reserved.</span>
			</td>
		</tr>
	 
		<tr>
			<td style="text-align:left; width:60%">
			  <table>
				<tbody>
					<tr>
						<td style="text-align:right;padding:5px;border-top:none;">
							<img src="images/Logo.jpg">
						</td>

						<td style="text-align:left;width:80%">
							<span style="border-top:none;border-bottom:2px solid #ccc; font-size:110%; font-weight:900"></span>
							<span style="font-size:90%;">
                                Phone: (+250) 788404430<br/>
                                E-mail: clinicumurage@gmail.com<br/>
                                Muhanga - Nyamabuye - Gahogo
                            </span>
						</td>
					</tr>
				</tbody>
			  </table>
			</td>
			
			<td style="text-align:right;">
				<img src="barcode/png/barcode'.$codecoordi.'.png" style="height:auto;"/>
			</td>
			
		</tr>
		
	</table>';

echo $barcode;
?>

<?php
		
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		$resultHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:idhosp AND ph.numero=:num ORDER BY ph.id_hosp');
		$resultHosp->execute(array(
		'idhosp'=>$_GET['idhosp'],
		'num'=>$numPa
		));

		$resultHosp->setFetchMode(PDO::FETCH_OBJ);

		$comptHosp=$resultHosp->rowCount();
		
		$TotalHosp = 0;
		$TotalHospCCO = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_surge-----------*/
	
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_hospSurge=:idhosp AND ms.id_factureMedSurge!="" ORDER BY ms.datehosp');
		$resultMedSurge->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

		$comptMedSurge=$resultMedSurge->rowCount();
	
		$TotalMedSurge = 0;
		$TotalMedSurgeCCO = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_inf-----------*/
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_hospInf=:idhosp AND mi.id_factureMedInf!="" ORDER BY mi.datehosp');
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
		$TotalMedInfCCO = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_labo-----------*/
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_hospLabo=:idhosp AND ml.id_factureMedLabo!="" ORDER BY ml.datehosp');
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();
		
		$TotalMedLabo = 0;
		$TotalMedLaboCCO = 0;
	
	
	
	
		/*-------Requête pour AFFICHER Med_radio-----------*/
	
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_hospRadio=:idhosp AND mr.id_factureMedRadio!="" ORDER BY mr.datehosp');
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
		$TotalMedRadioCCO = 0;

		
		
		/*-------Requête pour AFFICHER Med_kine-----------*/
		
		$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_hospKine=:idhosp AND mk.id_factureMedKine!="" ORDER BY mk.datehosp');
		$resultMedKine->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedKine->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedKine=$resultMedKine->rowCount();
		
		$TotalMedKine = 0;
		$TotalMedKineCCO = 0;

		
		
		/*-------Requête pour AFFICHER Med_ortho-----------*/
		
		$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_hospOrtho=:idhosp AND mo.id_factureMedOrtho!="" ORDER BY mo.datehosp');
		$resultMedOrtho->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedOrtho=$resultMedOrtho->rowCount();
		
		$TotalMedOrtho = 0;
		$TotalMedOrthoCCO = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_consom-----------*/
		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_hospConsom=:idhosp AND mco.id_factureMedConsom!="" ORDER BY mco.datehosp');
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		$TotalMedConsomCCO = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_medoc-----------*/
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_hospMedoc=:idhosp AND mdo.id_factureMedMedoc!="" ORDER BY mdo.datehosp');
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
		$TotalMedMedocCCO = 0;
		
		
		
		/*-------Requête pour AFFICHER Med_consult-----------*/
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_hospMed=:idhosp AND mc.id_factureMedConsu!="" ORDER BY mc.datehosp');
		$resultMedConsult->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedConsult=$resultMedConsult->rowCount();
		
		$TotalMedConsult = 0;
		$TotalMedConsultCCO = 0;
		
		
	?>
	
		<form method="post" action="printBill_hospReport_modifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['numbill'])){ echo '&numbill='.$numbill;}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?>" onsubmit="return controlFormRembou(this)" enctype="multipart/form-data">

		<table style="width:100%; margin:20px auto auto;">
			<tr>
				<td style="text-align:left; width:13%;">
				
				</td>
				
				<td style="text-align:center; width:66%;">
					<h2 style="font-size:150%; font-weight:600;">Formulaire Modification Facture Hospitalisation n° <?php echo $numbill;?></h2>
				</td>
				
				<td>
					<a href="categoriesbill_hospmodifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}else{ echo '&datehosp='.$datehosp;}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}else{ echo '&idassu='.$idassubill;}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}else{ echo '&nomassurance='.$nomassurance;}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}else{ echo '&billpercent='.$percentIdbill;}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&finishbtn=ok" id="addmorebtn" style="margin:5px;">
						<span class="btn-large" style="width:150px;"><i class="fa fa-plus fa-lg fa-fw"></i> <?php echo getString(221);?></span>
					</a>
					
				</td>
				
				<td>
					<a href="facturesedit.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" id="cancelbtn" class="btn-large-inversed">
					<i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?>
					</a>
					
				</td>
				
			</tr>
		</table>
		
		<?php
		
			/*--------------Billing Info Patient-----------------*/
		
		$resultatsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultatsPatient->execute(array(
		'operation'=>$numPa
		));
		
		$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
		
		if($lignePatient=$resultatsPatient->fetch())
		{
			
			$fullname= $lignePatient->full_name;
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

			?>
			
		<table style="width:100%; margin-top:20px; margin-bottom:20px;">
			
			<tr>
				<td style="text-align:left;">
					Full name: <span style="font-weight:bold"><?php echo $fullname;?></span><br/>
					Gender: <span style="font-weight:bold"><?php echo $sexe;?></span><br/>
					Adress: <span style="font-weight:bold"><?php echo $adresse;?></span>
				</td>
			
				<td style="text-align:center;">
					Organisation:
					<select name="org" id="org" style="width:40%;" onchange="ShowOrg('org')">
						
						<?php
						$resultats = $connexion->query('SELECT *FROM organisations ORDER BY nomOrg');
						
						while ($ligne = $resultats->fetch(PDO::FETCH_OBJ)) {
							?>
							<option value="<?php echo $ligne->id_org; ?>" id="organisation"
									style="font-weight:bold" <?php if ($idorgBillHosp == $ligne->id_org) {
								echo "selected='selected'";
							} ?>>
								<?php
								echo $ligne->nomOrg;
								if($ligne->lieuOrg != NULL){
									echo ' _ ' . $ligne->lieuOrg;
								}
								?>
							</option>
							<?php
						}
						?>
					</select><br/>
				<?php
				$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
				
				$resultAssurance->execute(array(
				'assuId'=>$idassubill
				));
				
				$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

				if($ligneAssu=$resultAssurance->fetch())
				{
					if($ligneAssu->id_assurance == $idassubill)
					{
						$idassu=$ligneAssu->id_assurance;
						$nomassurance=$ligneAssu->nomassurance;
						$numpolice=$lignePatient->numeropolice;
						$adherent=$lignePatient->adherent;
						
				?>
						Insurance type:
						<select name="assurance" id="assurance" style="width:20%;" onchange="ShowAssurance('assurance')">
						
							<option value="1" id="noinsurance" style="font-weight:bold" <?php if($idassurance == 1){echo "selected='selected'";}?>>
							<?php echo getString(78);?>
							</option>
							<?php
							$resultats=$connexion->query('SELECT *FROM assurances WHERE id_assurance!=1 ORDER BY nomassurance');
							
							while($ligne=$resultats->fetch(PDO::FETCH_OBJ))
							{
							?>
							<option value="<?php echo $ligne->id_assurance;?>" id="insurance" style="font-weight:bold" <?php if($idassubill == $ligne->id_assurance){echo "selected='selected'";}?>>
							<?php echo $ligne->nomassurance;?>
							</option>
							<?php
							}
							?>
						</select>
						
						<input type="text" name="percentIdbill" id="percentIdbill" value="<?php echo $percentIdbill;?>" style="font-weight:bold;width:5%;"/>%<br/>
						
							N° insurance card:
							<input type="text" name="idcardbill" id="idcardbill" value="<?php echo $idcardbill;?>" style="font-weight:bold;width:20%;"/>
							
							<br/>
							N° police:
							<input type="text" name="numpolicebill" id="numpolicebill" value="<?php echo $numpolicebill;?>" style="font-weight:bold;width:30%;"/>
						
							<br/>
							
							Principal member:
							<input type="text" name="adherentbill" id="adherentbill" value="<?php echo $adherentbill;?>" style="font-weight:bold;width:45%;"/>
						
				<?php
					}
				}
				?>
				</td>
				
				<td style="text-align:right;">
					Patient ID: <span style="font-weight:bold"><?php echo $lignePatient->numero;?></span>
					<br/>
					
					Date of birth: <span style="font-weight:bold"><?php echo date('d-M-Y', strtotime($lignePatient->date_naissance));?></span>
					<br/>
					
					Voucher number:
					<input type="text" name="vouchernum" id="vouchernum" value="<?php echo $vouchernum;?>" style="font-weight:bold;width:45%;"/>

				</td>
				
			</tr>
		</table>
		<?php
		}
		
		
		try
		{
			$TotalGnlPrice=0;
			$TotalGnlPatientPrice=0;
			$TotalGnlInsurancePrice=0;
			
			$x=0;
			$y=0;
			$z=0;
			
			if($comptHosp != 0)
			{
			?>
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th style="display:none;text-align:center;">Room</th>
						<th style="width:30%;text-align:center;">Date In</th>
						<th style="width:30%;text-align:center;">Date Out</th>
						<th style="width:5%;text-align:center;">Price/day</th>
						<th style="display:none;width:5%;text-align:center;">Percent</th>
						<th style="width:10%;text-align:center;">Nouveau prix</th>
						<th style="width:8%;text-align:center;">Nouveau %</th>
						<th style="width:5%;text-align:center;">Action</th>
					</tr>
				</thead>

				<tbody>
			<?php
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
			
				$i=1;
				
				while($ligneHosp=$resultHosp->fetch())
				{
				
					$billpercent=$ligneHosp->insupercent_hosp;
					
					$idassu=$ligneHosp->id_assuHosp;
					?>
					<tr style="font-weight:bold;">
						<td style="display:none;text-align:center;">
							<select name="numroom" id="numroom" style="width:100px;">
								<?php

								$resultats=$connexion->query('SELECT *FROM rooms ORDER BY id_room');
								
								while($ligne=$resultats->fetch(PDO::FETCH_OBJ))
								{
								?>
								<option value='<?php echo $ligne->numroom;?>' id="roomNum" name="roomNum"  <?php if($ligneHosp->numroomPa == $ligne->numroom){echo "selected='selected'";}?>>
								<?php
									echo $ligne->numroom;
								?>
								</option>
								<?php
								}
								?>
							</select>
						</td>
						
						<td style="text-align:center;">
							<br/>
							
							<select name="annee" id="annee" style="width:90px;" onchange="myScriptAnnee()">
								<?php
								for($a=2016;$a<=2050;$a++)
								{
									$anneeHosp=date('Y', strtotime($datehosp));
								?>
									<option value="<?php echo $a;?>" <?php if($anneeHosp==$a) echo 'selected="selected"';?>>
									<?php echo $a;?>
									</option>
								<?php
								}
								?>
							</select>
							
							<select name="mois" id="mois" style="width:120px;" onchange="myScriptMois()">
								<?php
								for($m=1;$m<=12;$m++)
								{
									$moisString=date("F",mktime(0,0,0,$m,10));
									$moisHosp=date("F",mktime(0,0,0,date('m', strtotime($datehosp)),10));
								?>
									<option value="<?php echo $m;?>" <?php if($moisHosp==$moisString) echo 'selected="selected"'; ?>>
									<?php
										echo $moisString;
									?>
									</option>
								<?php
								}
								?>
							</select>
							
							<select name="jours" id="jours" style="width:70px;">
								<option value=""></option>
							</select>
							
							<input size="25px" type="hidden" id="jourN" name="jourN" value="<?php echo $joursHosp=date('d', strtotime($datehosp));?>"/>
							<br/>
							
							<select name="heurein" id="heurein" style="width:70px;">
								<?php
								for($h=0;$h<=23;$h++)
								{
									$heureHosp=date('H', strtotime($datehosp));
								?>
									<option value="<?php echo $h;?>" <?php if($heureHosp==$h) echo 'selected="selected"';?>>
									<?php echo $h;?>
									</option>
								<?php
								}
								?>
							</select> H
							
							<select name="minutein" id="minutein" style="width:70px;">
								<?php
								for($min=0;$min<=59;$min++)
								{
									$minuteHosp=date('i', strtotime($datehosp));
								?>
									<option value="<?php echo $min;?>" <?php if($minuteHosp==$min) echo 'selected="selected"';?>>
									<?php echo $min;?>
									</option>
								<?php
								}
								?>
							</select> min
							
							<select name="secondein" id="secondein" style="width:70px;">
								<?php
								for($sec=0;$sec<=59;$sec++)
								{
									$secondeHosp=date('s', strtotime($datehosp));
								?>
									<option value="<?php echo $sec;?>" <?php if($secondeHosp==$sec) echo 'selected="selected"';?>>
									<?php echo $sec;?>
									</option>
								<?php
								}
								?>
							</select> sec
							
						</td>
						
						<td style="text-align:center;">
							<br/>
							
							<select name="anneeC" id="anneeC" style="width:90px;" onchange="myScriptAnneeC()">
								<?php
								for($a=2016;$a<=2050;$a++)
								{
									$anneeBill=date('Y', strtotime($datebill));
								?>
									<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeBill==$a) echo 'selected="selected"';?>>
									<?php echo $a;?>
									</option>
								<?php
								}
								?>
							</select>
							
							<select name="moisC" id="moisC" style="width:120px;" onchange="myScriptMoisC()">
								<?php
								for($m=1;$m<=12;$m++)
								{
									$moisString=date("F",mktime(0,0,0,$m,10));
									$moisBill=date("F",mktime(0,0,0,date('m', strtotime($datebill)),10));
								?>
									<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisBill==$moisString) echo 'selected="selected"'; ?>>
									<?php
										echo $moisString;
									?>
									</option>
								<?php
								}
								?>
							</select>
							
							
							<select name="joursC" id="joursC" style="width:70px;">
								<?php
								for($d=1;$d<=31;$d++)
								{
									$joursBill=date('d', strtotime($datebill));
								?>
									<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursBill==$d) echo 'selected="selected"'; ?>>
									<?php
										echo $d;
									?>
									</option>
								<?php
								}
								?>
							</select>
							
							<input size="25px" type="hidden" id="jourC" name="jourC" value="<?php echo $joursBill=date('d', strtotime($datebill));?>"/>
							<br/>
							
							<select name="heureout" id="heureout" style="width:70px;">
								<?php
								for($h=0;$h<=23;$h++)
								{
									$heureBill=date('H', strtotime($datehosp));
								?>
									<option value="<?php echo $h;?>" <?php if($heureBill==$h) echo 'selected="selected"';?>>
									<?php echo $h;?>
									</option>
								<?php
								}
								?>
							</select> H
							
							<select name="minuteout" id="minuteout" style="width:70px;">
								<?php
								for($min=0;$min<=59;$min++)
								{
									$minuteBill=date('i', strtotime($datebill));
								?>
									<option value="<?php echo $min;?>" <?php if($minuteBill==$min) echo 'selected="selected"';?>>
									<?php echo $min;?>
									</option>
								<?php
								}
								?>
							</select> min
							
							<select name="secondeout" id="secondeout" style="width:70px;">
								<?php
								for($sec=0;$sec<=59;$sec++)
								{
									$secondeBill=date('s', strtotime($datebill));
								?>
									<option value="<?php echo $sec;?>" <?php if($secondeBill==$sec) echo 'selected="selected"';?>>
									<?php echo $sec;?>
									</option>
								<?php
								}
								?>
							</select> sec
							
						</td>
						
						<?php

						$dateIn=strtotime($ligneHosp->dateEntree);
						$dateOut=strtotime($ligneHosp->dateSortie);
						
						$datediff= abs($dateOut - $dateIn);
						
						$nbrejrs= floor($datediff /(60*60*24));
						
						if($nbrejrs==0)
						{
							$nbrejrs=1;
						}
						
						$prixPrestaHosp=$ligneHosp->prixroom;
						?>
						
						<td style="text-align:center;"><?php echo $ligneHosp->prixroom;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
						
						<?php
						$balanceHosp=$prixPrestaHosp * $nbrejrs;
						$patientPrice=($balanceHosp * $billpercent)/100;
						$uapPrice= $balanceHosp - $patientPrice;
						
						$TotalHosp=$TotalHosp + $balanceHosp;
						$TotalPatientPrice=$TotalpatientPrice + $patientPrice;
						$TotaluapPrice = $TotaluapPrice + $uapPrice;
						
						
						?>
						
						<td style="display:none;text-align:center;"><?php echo $billpercent;?>%</td>
						
						
						<td style="text-align:left;">
							<input type="text" name="prixprestaHosp" id="prixprestaHosp" class="prixprestaHosp" value="<?php echo $prixPrestaHosp;?>" style="width:60%; font-weight:normal;"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="text-align:left;">
							<input type="text" name="percentHosp" id="percentHosp" class="percentHosp" value="<?php echo $percentIdbill;?>" style="width:40%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
							
						</td>
						
						<td>
						<?php
							if($comptMedSurge==0 AND $comptMedInf==0 AND $comptMedLabo==0 AND $comptMedRadio==0 AND $comptMedKine==0 AND $comptMedOrtho==0 AND $comptMedConsom==0 AND $comptMedMedoc==0 AND $comptMedConsult==0)
							{
						?>
								<a href="formModifierBillHosp.php?deleteHosp=<?php echo $ligneHosp->id_hosp; ?>&manager=<?php echo $_GET['manager']; ?>&num=<?php echo $_GET['num']; ?>&idhosp=<?php echo $_GET['idhosp']; ?>&numbill=<?php echo $_GET['numbill']; ?>&datehosp=<?php echo $_GET['datehosp']; ?>&numroom=<?php echo $_GET['numroom']; ?>&numlit=<?php echo $_GET['numlit']; ?>&idassu=<?php echo $_GET['idassu']; ?>&nomassurance=<?php echo $_GET['nomassurance']; ?>&billpercent=<?php echo $_GET['billpercent']; ?>&datefacture=<?php echo $_GET['datefacture']; ?>&finishbtn=ok"
								   class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
						<?php
							}
						?>
						</td>
					</tr>
				<?php

				}
				
				$TotalGnlPrice=$TotalGnlPrice + $TotalHosp;
				
				$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
				
				$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
				
		 	
				?>
				</tbody>
			</table>
			<?php
			
			}
		
			
			if($comptMedSurge != 0)
			{
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;" id="tableSurge">
				<thead>
					<tr>
						<th style="text-align:left;width:30%;">Date</th>
						<th style="text-align:left;">Surgery</th>
						<th style="text-align:left;width:10%">Qté</th>
						<th style="text-align:left;width:7%">P/U</th>
						<th style="display:none;text-align:left;width:7%;">Pourcentage</th>
						<th style="text-align:left;width:8%;">Nouvelle Qté</th>
						<th style="text-align:left;width:11%">Nouveau P/U</th>
						<th style="text-align:left;width:12%;">Nouveau %</th>
						<th style="text-align:left;width:2%;">Action</th>
					</tr>
				</thead>

				<tbody>
				<?php
			
				$TotalpatientPrice=0;
				
				$TotaluapPrice=0;
		
				$i=1;
				
				while($ligneMedSurge=$resultMedSurge->fetch())
				{
				
					$billpercent=$ligneMedSurge->insupercentSurge;
					
					$idassu=$ligneMedSurge->id_assuSurge;
				
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($assu=1;$assu<=$assuCount;$assu++)
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
						<td style="text-align:left;">
							<select name="anneeMedSurge[]" id="anneeMedSurge" style="width:90px;">
								<?php
								for($a=2016;$a<=2050;$a++)
								{
									$anneeSurge=date('Y', strtotime($ligneMedSurge->datehosp));
								?>
									<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeSurge==$a) echo 'selected="selected"';?>>
									<?php echo $a;?>
									</option>
								<?php
								}
								?>
							</select>
							
							<select name="moisMedSurge[]" id="moisMedSurge" style="width:120px;">
								<?php
								for($m=1;$m<=12;$m++)
								{
									$moisString=date("F",mktime(0,0,0,$m,10));
									$moisSurge=date("F",mktime(0,0,0,date('m', strtotime($ligneMedSurge->datehosp)),10));
								?>
									<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisSurge==$moisString) echo 'selected="selected"'; ?>>
									<?php
										echo $moisString;
									?>
									</option>
								<?php
								}
								?>
							</select>
							
							
							<select name="joursMedSurge[]" id="joursMedSurge" style="width:70px;">
								<?php
								for($d=1;$d<=31;$d++)
								{
									$joursSurge=date('d', strtotime($ligneMedSurge->datehosp));
								?>
									<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursSurge==$d) echo 'selected="selected"'; ?>>
										<?php
										echo $d;
										?>
									</option>
								<?php
								}
								?>
							</select>
						</td>
						
						<td style="text-align:left;">

							<input type="hidden" name="idassuSurge[]" style="width:20px;text-align:center" id="idassuSurge<?php echo $i;?>" value="<?php echo $idassu;?>"/>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
						$resultPresta->execute(array(
							'prestaId'=>$ligneMedSurge->id_prestationSurge
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							echo '<input type="text" name="idprestaSurge[]" style="width:100px;display:none; text-align:center" id="idprestaSurge'.$i.'" class="idprestaSurge" value="'.$lignePresta->id_prestation.'"/>';
							
							echo '<input type="text" name="autreSurge[]" style="width:100px;display:none; text-align:center" id="autreSurge'.$i.'" value=""/>';
							
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedSurge=$lignePresta->namepresta;
								echo $lignePresta->namepresta;
							
							}else{
							
								$nameprestaMedSurge=$lignePresta->nompresta;
								echo $lignePresta->nompresta;
							}
						?>
						</td>

						<td style="text-align:left;">
						<?php
							$qteSurge=$ligneMedSurge->qteSurge;
							
							echo $qteSurge;
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
							$prixPresta = $ligneMedSurge->prixprestationSurge;

							echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td style="display:none;text-align:left;">
						<?php
							echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
						?>
						</td>
					
						<?php
						$balance=$prixPresta*$qteSurge;
						
						$TotalMedSurge=$TotalMedSurge + $balance;
						?>
						
						<td style="text-align:left;">
							<input type="text" name="quantitySurge[]" id="quantitySurge<?php echo $i;?>" class="quantitySurge" value="<?php echo $qteSurge;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>

						
						<td style="text-align:left;">
							<input type="text" name="prixprestaSurge[]" id="prixprestaSurge<?php echo $i;?>" class="prixprestaSurge" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
						</td>
						
						<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
					
						}
						
						if($ligneMedSurge->id_prestationSurge==NULL AND $ligneMedSurge->autrePrestaS!="")
						{
							echo '<input type="hidden" name="idprestaSurge[]" style="width:100px;display:none;" id="idprestaSurge'.$i.'" value="0"/>';
							
							echo '<input type="text" name="autreSurge[]" style="width:100px;display:none; text-align:center" id="autreSurge'.$i.'" value="'.$ligneMedSurge->autrePrestaS.'"/>';
							
							$nameprestaMedSurge=$ligneMedSurge->autrePrestaS;
							echo $ligneMedSurge->autrePrestaS;
							
						?>
						</td>

						<td style="text-align:left;">
						<?php
							$qteSurge=$ligneMedSurge->qteSurge;
							
							echo $qteSurge;
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
							$prixPresta = $ligneMedSurge->prixautrePrestaS;

							echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td style="display:none;text-align:left;">
						<?php
							echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
						?>
						</td>
					
						<?php
						$balance=$prixPresta*$qteSurge;
						
						$TotalMedSurge=$TotalMedSurge + $balance;
						?>
						
						<td style="text-align:left;">
							<input type="text" name="quantitySurge[]" id="quantitySurge<?php echo $i;?>" class="quantitySurge" value="<?php echo $qteSurge;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
						
						
						<td style="text-align:left;">
							<input type="text" name="prixprestaSurge[]" id="prixprestaSurge<?php echo $i;?>" class="prixprestaSurge" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
						</td>
						
						<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							$uapPrice= $prixPresta - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;

						}
						?>
						
						<td style="text-align:left;">
							<input type="hidden" name="idmedSurge[]" id="idmedSurge<?php echo $i;?>" class="idmedSurge" value="<?php echo $ligneMedSurge->id_medsurge;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
					
							<input type="text" name="percentSurge[]" id="percentSurge<?php echo $i;?>" class="percentSurge" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
						</td>
						
						<td>
							<a href="formModifierBillHosp.php?deleteMedSurge=<?php echo $ligneMedSurge->id_medsurge;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							
						</td>
					</tr>
				<?php
					$i++;
				}
				?>
					<tr style="text-align:center;">
						
						<td colspan=4></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedSurge;
						?>
						
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
							// echo $TotalMedSurge.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
							?>
						</td>
						
						<td colspan=4></td>
					</tr>
				</tbody>
			</table>
			<?php
			}
		
			
			if($comptMedInf != 0)
			{
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;" id="tableInf">
				<thead>
					<tr>
						<th style="text-align:left;width:30%;">Date</th>
						<th style="text-align:left;">Nursing Care</th>
						<th style="text-align:left;width:10%">Qté</th>
						<th style="text-align:left;width:7%">P/U</th>
						<th style="display:none;text-align:left;width:7%;">Pourcentage</th>
						<th style="text-align:left;width:8%;">Nouvelle Qté</th>
						<th style="text-align:left;width:11%">Nouveau P/U</th>
						<th style="text-align:left;width:12%;">Nouveau %</th>
						<th style="text-align:left;width:2%;">Action</th>
					</tr>
				</thead>

				<tbody>
				<?php
			
				$TotalpatientPrice=0;
				
				$TotaluapPrice=0;
		
				$i=1;
				
				while($ligneMedInf=$resultMedInf->fetch())
				{
				
					$billpercent=$ligneMedInf->insupercentInf;
					
					$idassu=$ligneMedInf->id_assuInf;
				
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($assu=1;$assu<=$assuCount;$assu++)
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
						<td style="text-align:left;">
							<select name="anneeMedInf[]" id="anneeMedInf<?php echo $i;?>" style="width:90px;">
								<?php
								for($a=2016;$a<=2050;$a++)
								{
									$anneeInf=date('Y', strtotime($ligneMedInf->datehosp));
								?>
									<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeInf==$a) echo 'selected="selected"';?>>
									<?php echo $a;?>
									</option>
								<?php
								}
								?>
							</select>
							
							<select name="moisMedInf[]" id="moisMedInf<?php echo $i;?>" style="width:120px;">
								<?php
								for($m=1;$m<=12;$m++)
								{
									$moisString=date("F",mktime(0,0,0,$m,10));
									$moisInf=date("F",mktime(0,0,0,date('m', strtotime($ligneMedInf->datehosp)),10));
								?>
									<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisInf==$moisString) echo 'selected="selected"'; ?>>
									<?php
										echo $moisString;
									?>
									</option>
								<?php
								}
								?>
							</select>
							
							
							<select name="joursMedInf[]" id="joursMedInf<?php echo $i;?>" style="width:70px;">
								<?php
								for($d=1;$d<=31;$d++)
								{
									$joursInf=date('d', strtotime($ligneMedInf->datehosp));
								?>
									<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursInf==$d) echo 'selected="selected"'; ?>>
										<?php
										echo $d;
										?>
									</option>
								<?php
								}
								?>
							</select>
						</td>
						
						<td style="text-align:left;">

							<input type="hidden" name="idassuInf[]" style="width:20px;text-align:center" id="idassuInf<?php echo $i;?>" value="<?php echo $idassu;?>"/>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
						$resultPresta->execute(array(
							'prestaId'=>$ligneMedInf->id_prestation
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							echo '<input type="text" name="idprestaInf[]" style="width:100px;display:none; text-align:center" id="idprestaInf'.$i.'" class="idprestaInf" value="'.$lignePresta->id_prestation.'"/>';
							
							echo '<input type="text" name="autreInf[]" style="width:100px;display:none; text-align:center" id="autreInf'.$i.'" value=""/>';
							
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedInf=$lignePresta->namepresta;
								echo $lignePresta->namepresta;
							
							}else{
							
								$nameprestaMedInf=$lignePresta->nompresta;
								echo $lignePresta->nompresta;
							}
						?>
						</td>

						<td style="text-align:left;">
						<?php
							$qteInf=$ligneMedInf->qteInf;
							
							echo $qteInf;
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
							$prixPresta = $ligneMedInf->prixprestation;

							echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td style="display:none;text-align:left;">
						<?php
							echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
						?>
						</td>
					
						<?php
						$balance=$prixPresta*$qteInf;
						
						$TotalMedInf=$TotalMedInf + $balance;
						?>
						
						<td style="text-align:left;">
							<input type="text" name="quantityInf[]" id="quantityInf<?php echo $i;?>" class="quantityInf" value="<?php echo $qteInf;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>

						
						<td style="text-align:left;">
							<input type="text" name="prixprestaInf[]" id="prixprestaInf<?php echo $i;?>" class="prixprestaInf" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
						</td>
						
						<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
					
						}
						
						if($ligneMedInf->id_prestation==NULL AND $ligneMedInf->autrePrestaM!="")
						{
							echo '<input type="hidden" name="idprestaInf[]" style="width:100px;display:none;" id="idprestaInf'.$i.'" value="0"/>';
							
							echo '<input type="text" name="autreInf[]" style="width:100px;display:none; text-align:center" id="autreInf'.$i.'" value="'.$ligneMedInf->autrePrestaM.'"/>';
							
							$nameprestaMedInf=$ligneMedInf->autrePrestaM;
							echo $ligneMedInf->autrePrestaM;
							
						?>
						</td>

						<td style="text-align:left;">
						<?php
							$qteInf=$ligneMedInf->qteInf;
							
							echo $qteInf;
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
							$prixPresta = $ligneMedInf->prixautrePrestaM;

							echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td style="display:none;text-align:left;">
						<?php
							echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
						?>
						</td>
					
						<?php
						$balance=$prixPresta*$qteInf;
						
						$TotalMedInf=$TotalMedInf + $balance;
						?>
						
						<td style="text-align:left;">
							<input type="text" name="quantityInf[]" id="quantityInf<?php echo $i;?>" class="quantityInf" value="<?php echo $qteInf;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
												
						<td style="text-align:left;">
							<input type="text" name="prixprestaInf[]" id="prixprestaInf<?php echo $i;?>" class="prixprestaInf" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
						</td>
						
						<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							$uapPrice= $prixPresta - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;

						}
						?>
						
						<td style="text-align:left;">
							<input type="hidden" name="idmedInf[]" id="idmedInf<?php echo $i;?>" class="idmedInf" value="<?php echo $ligneMedInf->id_medinf;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
					
							<input type="text" name="percentInf[]" id="percentInf<?php echo $i;?>" class="percentInf" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
						</td>
						
						<td>
							<a href="formModifierBillHosp.php?deleteMedInf=<?php echo $ligneMedInf->id_medinf;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							
						</td>
					</tr>
				<?php
					$i++;
				}
				?>
					<tr style="text-align:center;">
						
						<td colspan=4></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
						?>
						
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
							// echo $TotalMedInf.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
							?>
						</td>
						
						<td colspan=4></td>
					</tr>
				</tbody>
			</table>
			<?php
			}
			
			
			if($comptMedLabo != 0)
			{
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;" id="tableLabo">
				<thead>
					<tr>
						<th style="text-align:left;width:30%;">Date</th>
						<th style="text-align:left;">Labs</th>
						<th style="text-align:left;width:10%">Qté</th>
						<th style="text-align:left;width:7%">P/U</th>
						<th style="display:none;text-align:left;width:7%;">Pourcentage</th>
						<th style="text-align:left;width:8%;">Nouvelle Qté</th>
						<th style="text-align:left;width:11%">Nouveau P/U</th>
						<th style="text-align:left;width:12%;">Nouveau %</th>
						<th style="text-align:left;width:2%;">Action</th>
					</tr>
				</thead>

				<tbody>
				<?php
			
				$TotalpatientPrice=0;
				$TotaluapPrice=0;
				
				$i=1;
				
				while($ligneMedLabo=$resultMedLabo->fetch())
				{
					$billpercent=$ligneMedLabo->insupercentLab;
					
					$idassu=$ligneMedLabo->id_assuLab;
					
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($assu=1;$assu<=$assuCount;$assu++)
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
						<td style="text-align:left;">
							<select name="anneeMedLabo[]" id="anneeMedLabo" style="width:90px;">
								<?php
								for($a=2016;$a<=2050;$a++)
								{
									$anneeLabo=date('Y', strtotime($ligneMedLabo->datehosp));
								?>
									<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeLabo==$a) echo 'selected="selected"';?>>
									<?php echo $a;?>
									</option>
								<?php
								}
								?>
							</select>
							
							<select name="moisMedLabo[]" id="moisMedLabo" style="width:120px;">
								<?php
								for($m=1;$m<=12;$m++)
								{
									$moisString=date("F",mktime(0,0,0,$m,10));
									$moisLabo=date("F",mktime(0,0,0,date('m', strtotime($ligneMedLabo->datehosp)),10));
								?>
									<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisLabo==$moisString) echo 'selected="selected"'; ?>>
									<?php
										echo $moisString;
									?>
									</option>
								<?php
								}
								?>
							</select>
							
							
							<select name="joursMedLabo[]" id="joursMedLabo" style="width:70px;">
								<?php
								for($d=1;$d<=31;$d++)
								{
									$joursLabo=date('d', strtotime($ligneMedLabo->datehosp));
								?>
									<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursLabo==$d) echo 'selected="selected"'; ?>>
										<?php
										echo $d;
										?>
									</option>
								<?php
								}
								?>
							</select>
						</td>
						
						<td style="text-align:left;">

							<input type="hidden" name="idassuLab[]" style="width:20px;text-align:center" id="idassuLab<?php echo $i;?>" value="<?php echo $idassu;?>"/>
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedLabo->id_prestationExa
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							echo '<input type="text" name="idprestaLab[]" style="width:100px;display:none; text-align:center" id="idprestaLab'.$i.'" value="'.$lignePresta->id_prestation.'"/>';
							echo '<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab'.$i.'" value=""/>';
							
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedLabo=$lignePresta->namepresta;
								echo $lignePresta->namepresta;
							
							}else{
							
								$nameprestaMedLabo=$lignePresta->nompresta;
								echo $lignePresta->nompresta;
							}
							
						?>
						</td>

						<td style="text-align:left;">
						<?php
							$qteLab=$ligneMedLabo->qteLab;
							
							echo $qteLab;
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
							$prixPresta = $ligneMedLabo->prixprestationExa;

							echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td style="display:none;text-align:left;">
						<?php
							echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
						?>
						</td>
					
						<?php
						$balance=$prixPresta*$qteLab;
						
						$TotalMedLabo=$TotalMedLabo + $balance;
						?>
						
						<td style="text-align:left;">
							<input type="text" name="quantityLab[]" id="quantityLab<?php echo $i;?>" class="quantityLab" value="<?php echo $qteLab;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
												
						<td style="text-align:left;">
							<input type="text" name="prixprestaLab[]" id="prixprestaLab<?php echo $i;?>" class="prixprestaLab" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
						</td>
						
						<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						
						}
						
						if($ligneMedLabo->id_prestationExa==NULL AND $ligneMedLabo->autreExamen!="")
						{
							echo $ligneMedLabo->autreExamen.'
							<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab'.$i.'" value="'.$ligneMedLabo->autreExamen.'"/>
							
							<input type="hidden" name="idprestaLab[]" style="width:100px;display:none; text-align:center" id="idprestaLab'.$i.'" value="0"/>';
						
							$nameprestaMedLabo=$ligneMedLabo->autreExamen;
						?>
						</td>

						<td style="text-align:left;">
						<?php
							$qteLab=$ligneMedLabo->qteLab;
							
							echo $qteLab;
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
							$prixPresta = $ligneMedLabo->prixautreExamen;

							echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
						?>
						</td>
					
						<td style="display:none;text-align:left;">
						<?php
							echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
						?>
						</td>
					
						<?php
						$balance=$prixPresta*$qteLab;
						
						$TotalMedLabo=$TotalMedLabo + $balance;
						?>
						
						<td style="text-align:left;">
							<input type="text" name="quantityLab[]" id="quantityLab<?php echo $i;?>" class="quantityLab" value="<?php echo $qteLab;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>

						
						<td style="text-align:left;">
							<input type="text" name="prixprestaLab[]" id="prixprestaLab<?php echo $i;?>" class="prixprestaLab" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
						</td>
						
						<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						
						}
						?>
						
						<td style="text-align:left;">
							<input type="hidden" name="idmedLab[]" id="idmedLab<?php echo $i;?>" class="idmedLab" value="<?php echo $ligneMedLabo->id_medlabo;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						
							<input type="text" name="percentLab[]" id="percentLab<?php echo $i;?>" class="percentLab" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
							
						</td>
						
						<td>
							<a href="formModifierBillHosp.php?deleteMedLabo=<?php echo $ligneMedLabo->id_medlabo;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							
						</td>
					</tr>
				<?php
					$i++;
				}
				?>
					<tr style="text-align:center;">
						
						<td colspan=4></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
						?>
						
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
							// echo $TotalMedLabo.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
							?>
						</td>
						<td colspan=4></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			}
			
			
			if($comptMedRadio != 0)
			{
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;" id="tableRadio">
				<thead>
					<tr>
						<th style="text-align:left;width:30%;">Date</th>
						<th style="text-align:left;">Radiologie</th>
						<th style="text-align:left;width:10%">Qté</th>
						<th style="text-align:left;width:7%">P/U</th>
						<th style="display:none;text-align:left;width:7%;">Pourcentage</th>
						<th style="text-align:left;width:8%;">Nouvelle Qté</th>
						<th style="text-align:left;width:11%">Nouveau P/U</th>
						<th style="text-align:left;width:12%;">Nouveau %</th>
						<th style="text-align:left;width:2%;">Action</th>
					</tr>
				</thead>

				<tbody>
				<?php
			
				$TotalpatientPrice=0;
				$TotaluapPrice=0;
			
				$i=1;
			
				while($ligneMedRadio=$resultMedRadio->fetch())
				{
				
					$billpercent=$ligneMedRadio->insupercentRad;
					
					$idassu=$ligneMedRadio->id_assuRad;
					
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($assu=1;$assu<=$assuCount;$assu++)
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
						<td style="text-align:left;">
							<select name="anneeMedRadio[]" id="anneeMedRadio" style="width:90px;">
								<?php
								for($a=2016;$a<=2050;$a++)
								{
									$anneeRadio=date('Y', strtotime($ligneMedRadio->datehosp));
								?>
									<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeRadio==$a) echo 'selected="selected"';?>>
									<?php echo $a;?>
									</option>
								<?php
								}
								?>
							</select>
							
							<select name="moisMedRadio[]" id="moisMedRadio" style="width:120px;">
								<?php
								for($m=1;$m<=12;$m++)
								{
									$moisString=date("F",mktime(0,0,0,$m,10));
									$moisRadio=date("F",mktime(0,0,0,date('m', strtotime($ligneMedRadio->datehosp)),10));
								?>
									<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisRadio==$moisString) echo 'selected="selected"'; ?>>
									<?php
										echo $moisString;
									?>
									</option>
								<?php
								}
								?>
							</select>
							
							
							<select name="joursMedRadio[]" id="joursMedRadio" style="width:70px;">
								<?php
								for($d=1;$d<=31;$d++)
								{
									$joursRadio=date('d', strtotime($ligneMedRadio->datehosp));
								?>
									<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursRadio==$d) echo 'selected="selected"'; ?>>
										<?php
										echo $d;
										?>
									</option>
								<?php
								}
								?>
							</select>
						</td>
						
						<td style="text-align:left;">

							<input type="hidden" name="idassuRad[]" style="width:20px;text-align:center" id="idassuRad<?php echo $i;?>" value="<?php echo $idassu;?>"/>
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedRadio->id_prestationRadio
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							echo '<input type="text" name="idprestaRad[]" style="width:100px;display:none; text-align:center" id="idprestaRad'.$i.'" value="'.$lignePresta->id_prestation.'"/>';
							
							echo '<input type="text" name="autreRad[]" style="width:100px;display:none; text-align:center" id="autreRad'.$i.'" value=""/>';
							
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedRadio=$lignePresta->namepresta;
								echo $lignePresta->namepresta;
							
							}else{
							
								$nameprestaMedRadio=$lignePresta->nompresta;
								echo $lignePresta->nompresta;
							}
							
						?>
						</td>

						<td style="text-align:left;">
						<?php
							$qteRad=$ligneMedRadio->qteRad;
							
							echo $qteRad;
							
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
							$prixPresta = $ligneMedRadio->prixprestationRadio;

							echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td style="display:none;text-align:left;">
						<?php
							echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
						?>
						</td>
					
						<?php
						$balance=$prixPresta*$qteRad;
						
						$TotalMedRadio=$TotalMedRadio + $balance;
						?>
						
						<td style="text-align:left;">
							<input type="text" name="quantityRad[]" id="quantityRad<?php echo $i;?>" class="quantityRad" value="<?php echo $qteRad;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
												
						<td style="text-align:left;">
							<input type="text" name="prixprestaRad[]" id="prixprestaRad<?php echo $i;?>" class="prixprestaRad" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
						</td>
						
						<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
						}
						
						if($ligneMedRadio->id_prestationRadio==NULL AND $ligneMedRadio->autreRadio!="")
						{
							echo $ligneMedRadio->autreRadio.'<input type="text" name="autreRad[]" style="width:100px;display:none; text-align:center" id="autreRad'.$i.'" value="'.$ligneMedRadio->autreRadio.'"/>
							<input type="text" name="idprestaRad[]" style="width:100px;display:none; text-align:center" id="idprestaRad'.$i.'" value="0"/>';
							
							$nameprestaMedRadio=$ligneMedRadio->autreRadio;
						?>
						</td>

						<td style="text-align:left;">
						<?php
							$qteRad=$ligneMedRadio->qteRad;
							
							echo $qteRad;
							
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
							$prixPresta = $ligneMedRadio->prixautreRadio;

							echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td style="display:none;text-align:left;">
						<?php
							echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
						?>
						</td>
					
						<?php
						
						$TotalMedRadio=$TotalMedRadio + $balance;
						?>
						
						<td style="text-align:left;">
							<input type="text" name="quantityRad[]" id="quantityRad<?php echo $i;?>" class="quantityRad" value="<?php echo $qteRad;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						</td>
						
						<td style="text-align:left;">
							<input type="text" name="prixprestaRad[]" id="prixprestaRad<?php echo $i;?>" class="prixprestaRad" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
							
						</td>
						
						<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
						}
						?>
						
						<td style="text-align:left;">
							<input type="hidden" name="idmedRad[]" id="idmedRad<?php echo $i;?>" class="idmedRad" value="<?php echo $ligneMedRadio->id_medradio;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						
							<input type="text" name="percentRad[]" id="percentRad<?php echo $i;?>" class="percentRad" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
							
						</td>
						
						<td>
							<a href="formModifierBillHosp.php?deleteMedRadio=<?php echo $ligneMedRadio->id_medradio;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							
						</td>
					</tr>
				<?php
					$i++;
				}
				?>
					<tr style="text-align:center;">
						<td colspan=3></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
						?>
						
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
							// echo $TotalMedRadio.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?>
						</td>
						
						<td>
						<?php
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
						?>
						</td>
						
						<td colspan=4></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			}
			
			
			if($comptMedKine != 0)
			{
				?>

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;" id="tableKine">
					<thead>
						<tr>
							<th style="text-align:left;width:30%;">Date</th>
							<th style="text-align:left;">Physiotherapy</th>
							<th style="text-align:left;width:10%">Qté</th>
							<th style="text-align:left;width:7%">P/U</th>
							<th style="display:none;text-align:left;width:7%;">Pourcentage</th>
							<th style="text-align:left;width:8%;">Nouvelle Qté</th>
							<th style="text-align:left;width:11%">Nouveau P/U</th>
							<th style="text-align:left;width:12%;">Nouveau %</th>
							<th style="text-align:left;width:2%;">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotalpatientPrice=0;
						
						$TotaluapPrice=0;
						
						$i=1;
						
						while($ligneMedKine=$resultMedKine->fetch())
						{
							
							$billpercent=$ligneMedKine->insupercentKine;
							
							$idassu=$ligneMedKine->id_assuKine;
							
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
							$assuCount = $comptAssuConsu->rowCount();
							
							for($assu=1;$assu<=$assuCount;$assu++)
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
								<td style="text-align:left;">
									<select name="anneeMedKine[]" id="anneeMedKine" style="width:90px;">
										<?php
										for($a=2016;$a<=2050;$a++)
										{
											$anneeKine=date('Y', strtotime($ligneMedKine->datehosp));
											?>
											<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeKine==$a) echo 'selected="selected"';?>>
												<?php echo $a;?>
											</option>
											<?php
										}
										?>
									</select>

									<select name="moisMedKine[]" id="moisMedKine" style="width:120px;">
										<?php
										for($m=1;$m<=12;$m++)
										{
											$moisString=date("F",mktime(0,0,0,$m,10));
											$moisKine=date("F",mktime(0,0,0,date('m', strtotime($ligneMedKine->datehosp)),10));
											?>
											<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisKine==$moisString) echo 'selected="selected"'; ?>>
												<?php
												echo $moisString;
												?>
											</option>
											<?php
										}
										?>
									</select>


									<select name="joursMedKine[]" id="joursMedKine" style="width:70px;">
										<?php
										for($d=1;$d<=31;$d++)
										{
											$joursKine=date('d', strtotime($ligneMedKine->datehosp));
											?>
											<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursKine==$d) echo 'selected="selected"'; ?>>
												<?php
												echo $d;
												?>
											</option>
											<?php
										}
										?>
									</select>
								</td>

								<td style="text-align:left;">

									<input type="hidden" name="idassuKine[]" style="width:20px;text-align:center" id="idassuKine<?php echo $i;?>" value="<?php echo $idassu;?>"/>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedKine->id_prestationKine
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
									
									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())
									{
									echo '<input type="text" name="idprestaKine[]" style="width:100px;display:none; text-align:center" id="idprestaKine'.$i.'" class="idprestaKine" value="'.$lignePresta->id_prestation.'"/>';
									
									echo '<input type="text" name="autreKine[]" style="width:100px;display:none; text-align:center" id="autreKine'.$i.'" value=""/>';
									
									if($lignePresta->namepresta!='')
									{
										$nameprestaMedKine=$lignePresta->namepresta;
										echo $lignePresta->namepresta;
										
									}else{
										
										$nameprestaMedKine=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
									}
									?>
								</td>

								<td style="text-align:left;">
									<?php
									$qteKine=$ligneMedKine->qteKine;
									
									echo $qteKine;
									?>
								</td>

								<td style="text-align:left;">
									<?php
									$prixPresta = $ligneMedKine->prixprestationKine;
									
									echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
									?>
								</td>

								<td style="display:none;text-align:left;">
									<?php
									echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
									?>
								</td>
								
								<?php
								$balance=$prixPresta*$qteKine;
								
								$TotalMedKine=$TotalMedKine + $balance;
								?>

								<td style="text-align:left;">
									<input type="text" name="quantityKine[]" id="quantityKine<?php echo $i;?>" class="quantityKine" value="<?php echo $qteKine;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
								</td>

								<td style="text-align:left;">
									<input type="text" name="prixprestaKine[]" id="prixprestaKine<?php echo $i;?>" class="prixprestaKine" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

								</td>
								
								<?php
								$patientPrice=($balance * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								$uapPrice= $prixPresta - $patientPrice;
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								}
								
								if($ligneMedKine->id_prestationKine==NULL AND $ligneMedKine->autrePrestaK!="")
								{
									echo '<input type="hidden" name="idprestaKine[]" style="width:100px;display:none;" id="idprestaKine'.$i.'" value="0"/>';
									
									echo '<input type="text" name="autreKine[]" style="width:100px;display:none; text-align:center" id="autreKine'.$i.'" value="'.$ligneMedKine->autrePrestaK.'"/>';
									
									$nameprestaMedKine=$ligneMedKine->autrePrestaK;
									echo $ligneMedKine->autrePrestaK;
									
									?>
									</td>

									<td style="text-align:left;">
										<?php
										$qteKine=$ligneMedKine->qteKine;
										
										echo $qteKine;
										?>
									</td>

									<td style="text-align:left;">
										<?php
										$prixPresta = $ligneMedKine->prixautrePrestaK;
										
										echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<td style="display:none;text-align:left;">
										<?php
										echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
										?>
									</td>
									
									<?php
									$balance=$prixPresta*$qteKine;
									
									$TotalMedKine=$TotalMedKine + $balance;
									?>

									<td style="text-align:left;">
										<input type="text" name="quantityKine[]" id="quantityKine<?php echo $i;?>" class="quantityKine" value="<?php echo $qteKine;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
									</td>

									<td style="text-align:left;">
										<input type="text" name="prixprestaKine[]" id="prixprestaKine<?php echo $i;?>" class="prixprestaKine" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

									</td>
									
									<?php
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									$uapPrice= $prixPresta - $patientPrice;
									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
								}
								?>

								<td style="text-align:left;">
									<input type="hidden" name="idmedKine[]" id="idmedKine<?php echo $i;?>" class="idmedKine" value="<?php echo $ligneMedKine->id_medkine;?>" style="width:30%; font-weight:normal;margin-top:5px"/>

									<input type="text" name="percentKine[]" id="percentKine<?php echo $i;?>" class="percentKine" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
								</td>

								<td>
									<a href="formModifierBillHosp.php?deleteMedKine=<?php echo $ligneMedKine->id_medkine;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

								</td>
							</tr>
							<?php
							$i++;
						}
						?>
						<tr style="text-align:center;">

							<td colspan=4></td>
							<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedKine;
							?>

							<td style="text-align:left; font-size: 13px; font-weight: bold;">
								<?php
								// echo $TotalMedKine.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								?>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								?>
							</td>

							<td colspan=4></td>
						</tr>
					</tbody>
				</table>
				<?php
			}
			
			
			if($comptMedOrtho != 0)
			{
				?>

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;" id="tableOrtho">
					<thead>
						<tr>
							<th style="text-align:left;width:30%;">Date</th>
							<th style="text-align:left;">P&O</th>
							<th style="text-align:left;width:10%">Qté</th>
							<th style="text-align:left;width:7%">P/U</th>
							<th style="display:none;text-align:left;width:7%;">Pourcentage</th>
							<th style="text-align:left;width:8%;">Nouvelle Qté</th>
							<th style="text-align:left;width:11%">Nouveau P/U</th>
							<th style="text-align:left;width:12%;">Nouveau %</th>
							<th style="text-align:left;width:2%;">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotalpatientPrice=0;
						
						$TotaluapPrice=0;
						
						$i=1;
						
						while($ligneMedOrtho=$resultMedOrtho->fetch())
						{
							
							$billpercent=$ligneMedOrtho->insupercentOrtho;
							
							$idassu=$ligneMedOrtho->id_assuOrtho;
							
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
							$assuCount = $comptAssuConsu->rowCount();
							
							for($assu=1;$assu<=$assuCount;$assu++)
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
								<td style="text-align:left;">
									<select name="anneeMedOrtho[]" id="anneeMedOrtho" style="width:90px;">
										<?php
										for($a=2016;$a<=2050;$a++)
										{
											$anneeOrtho=date('Y', strtotime($ligneMedOrtho->datehosp));
											?>
											<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeOrtho==$a) echo 'selected="selected"';?>>
												<?php echo $a;?>
											</option>
											<?php
										}
										?>
									</select>

									<select name="moisMedOrtho[]" id="moisMedOrtho" style="width:120px;">
										<?php
										for($m=1;$m<=12;$m++)
										{
											$moisString=date("F",mktime(0,0,0,$m,10));
											$moisOrtho=date("F",mktime(0,0,0,date('m', strtotime($ligneMedOrtho->datehosp)),10));
											?>
											<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisOrtho==$moisString) echo 'selected="selected"'; ?>>
												<?php
												echo $moisString;
												?>
											</option>
											<?php
										}
										?>
									</select>


									<select name="joursMedOrtho[]" id="joursMedOrtho" style="width:70px;">
										<?php
										for($d=1;$d<=31;$d++)
										{
											$joursOrtho=date('d', strtotime($ligneMedOrtho->datehosp));
											?>
											<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursOrtho==$d) echo 'selected="selected"'; ?>>
												<?php
												echo $d;
												?>
											</option>
											<?php
										}
										?>
									</select>
								</td>

								<td style="text-align:left;">

									<input type="hidden" name="idassuOrtho[]" style="width:20px;text-align:center" id="idassuOrtho<?php echo $i;?>" value="<?php echo $idassu;?>"/>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedOrtho->id_prestationOrtho
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
									
									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())
									{
									echo '<input type="text" name="idprestaOrtho[]" style="width:100px;display:none; text-align:center" id="idprestaOrtho'.$i.'" class="idprestaOrtho" value="'.$lignePresta->id_prestation.'"/>';
									
									echo '<input type="text" name="autreOrtho[]" style="width:100px;display:none; text-align:center" id="autreOrtho'.$i.'" value=""/>';
									
									if($lignePresta->namepresta!='')
									{
										$nameprestaMedOrtho=$lignePresta->namepresta;
										echo $lignePresta->namepresta;
										
									}else{
										
										$nameprestaMedOrtho=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
									}
									?>
								</td>

								<td style="text-align:left;">
									<?php
									$qteOrtho=$ligneMedOrtho->qteOrtho;
									
									echo $qteOrtho;
									?>
								</td>

								<td style="text-align:left;">
									<?php
									$prixPresta = $ligneMedOrtho->prixprestationOrtho;
									
									echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
									?>
								</td>

								<td style="display:none;text-align:left;">
									<?php
									echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
									?>
								</td>
								
								<?php
								$balance=$prixPresta*$qteOrtho;
								
								$TotalMedOrtho=$TotalMedOrtho + $balance;
								?>

								<td style="text-align:left;">
									<input type="text" name="quantityOrtho[]" id="quantityOrtho<?php echo $i;?>" class="quantityOrtho" value="<?php echo $qteOrtho;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
								</td>


								<td style="text-align:left;">
									<input type="text" name="prixprestaOrtho[]" id="prixprestaOrtho<?php echo $i;?>" class="prixprestaOrtho" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

								</td>
								
								<?php
								$patientPrice=($balance * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								$uapPrice= $prixPresta - $patientPrice;
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								}
								
								if($ligneMedOrtho->id_prestationOrtho==NULL AND $ligneMedOrtho->autrePrestaO!="")
								{
									echo '<input type="hidden" name="idprestaOrtho[]" style="width:100px;display:none;" id="idprestaOrtho'.$i.'" value="0"/>';
									
									echo '<input type="text" name="autreOrtho[]" style="width:100px;display:none; text-align:center" id="autreOrtho'.$i.'" value="'.$ligneMedOrtho->autrePrestaO.'"/>';
									
									$nameprestaMedOrtho=$ligneMedOrtho->autrePrestaO;
									echo $ligneMedOrtho->autrePrestaO;
									
									?>
									</td>

									<td style="text-align:left;">
										<?php
										$qteOrtho=$ligneMedOrtho->qteOrtho;
										
										echo $qteOrtho;
										?>
									</td>

									<td style="text-align:left;">
										<?php
										$prixPresta = $ligneMedOrtho->prixautrePrestaO;
										
										echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<td style="display:none;text-align:left;">
										<?php
										echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
										?>
									</td>
									
									<?php
									$balance=$prixPresta*$qteOrtho;
									
									$TotalMedOrtho=$TotalMedOrtho + $balance;
									?>

									<td style="text-align:left;">
										<input type="text" name="quantityOrtho[]" id="quantityOrtho<?php echo $i;?>" class="quantityOrtho" value="<?php echo $qteOrtho;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
									</td>


									<td style="text-align:left;">
										<input type="text" name="prixprestaOrtho[]" id="prixprestaOrtho<?php echo $i;?>" class="prixprestaOrtho" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

									</td>
									
									<?php
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									$uapPrice= $prixPresta - $patientPrice;
									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
								}
								?>

								<td style="text-align:left;">
									<input type="hidden" name="idmedOrtho[]" id="idmedOrtho<?php echo $i;?>" class="idmedOrtho" value="<?php echo $ligneMedOrtho->id_medortho;?>" style="width:30%; font-weight:normal;margin-top:5px"/>

									<input type="text" name="percentOrtho[]" id="percentOrtho<?php echo $i;?>" class="percentOrtho" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
								</td>

								<td>
									<a href="formModifierBillHosp.php?deleteMedOrtho=<?php echo $ligneMedOrtho->id_medortho;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

								</td>
							</tr>
							<?php
							$i++;
						}
						?>
						<tr style="text-align:center;">

							<td colspan=4></td>
							<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedOrtho;
							?>

							<td style="text-align:left; font-size: 13px; font-weight: bold;">
								<?php
								// echo $TotalMedOrtho.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								?>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								?>
							</td>

							<td colspan=4></td>
						</tr>
					</tbody>
				</table>
				<?php
			}
			
			
			if($comptMedConsom != 0)
			{
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;" id="tableConsom">
				<thead>
					<tr>
						<th style="text-align:left;width:30%;">Date</th>
						<th style="text-align:left;">Consommables</th>
						<th style="text-align:left;width:10%">Qté</th>
						<th style="text-align:left;width:7%">P/U</th>
						<th style="display:none;text-align:left;width:7%;">Pourcentage</th>
						<th style="text-align:left;width:8%;">Nouvelle Qté</th>
						<th style="text-align:left;width:11%">Nouveau P/U</th>
						<th style="text-align:left;width:12%;">Nouveau %</th>
						<th style="text-align:left;width:2%;">Action</th>
					</tr>
				</thead>

				<tbody>
				<?php
			
				$TotalpatientPrice=0;
				$TotaluapPrice=0;
			
				$i=1;
				
				while($ligneMedConsom=$resultMedConsom->fetch())
				{
				
					$billpercent=$ligneMedConsom->insupercentConsom;
					
					$idassu=$ligneMedConsom->id_assuConsom;
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($assu=1;$assu<=$assuCount;$assu++)
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


					$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedConsom->id_prestationConsom
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
				
					if($comptPresta==0)
					{
						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedConsom->id_prestationConsom
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);
					}
					
					if($lignePresta=$resultPresta->fetch())
					{
					?>
						<tr>
							<td style="text-align:left;">
								<select name="anneeMedConsom[]" id="anneeMedConsom" style="width:90px;">
									<?php
									for($a=2016;$a<=2050;$a++)
									{
										$anneeConsom=date('Y', strtotime($ligneMedConsom->datehosp));
									?>
										<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeConsom==$a) echo 'selected="selected"';?>>
										<?php echo $a;?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="moisMedConsom[]" id="moisMedConsom" style="width:120px;">
									<?php
									for($m=1;$m<=12;$m++)
									{
										$moisString=date("F",mktime(0,0,0,$m,10));
										$moisConsom=date("F",mktime(0,0,0,date('m', strtotime($ligneMedConsom->datehosp)),10));
									?>
										<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisConsom==$moisString) echo 'selected="selected"'; ?>>
										<?php
											echo $moisString;
										?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="joursMedConsom[]" id="joursMedConsom" style="width:70px;">
									<?php
									for($d=1;$d<=31;$d++)
									{
										$joursConsom=date('d', strtotime($ligneMedConsom->datehosp));
									?>
										<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursConsom==$d) echo 'selected="selected"'; ?>>
											<?php
											echo $d;
											?>
										</option>
									<?php
									}
									?>
								</select>
							</td>
							
							<td style="text-align:left;">

								<input type="hidden" name="idassuConsom[]" style="width:20px;text-align:center" id="idassuConsom<?php echo $i;?>" value="<?php echo $idassu;?>"/>
								<?php
								echo '<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom'.$i.'" value="'.$lignePresta->id_prestation.'"/>';
			
								echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom'.$i.'" value=""/>';
			
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
							
							<td style="text-align:left;">
							<?php
								$qteConsom=$ligneMedConsom->qteConsom;
								
								echo $qteConsom;
								
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
								$prixPresta = $ligneMedConsom->prixprestationConsom;
								
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>
							
							<td style="display:none;text-align:left;">
							<?php
								echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
							?>
							</td>
							
							<?php
							$balance=$prixPresta*$qteConsom;
							
							$TotalMedConsom=$TotalMedConsom + $balance;
							$patientPrice=($balance * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							?>
							
							<td style="text-align:left;">
								<input type="text" name="quantityConsom[]" id="quantityConsom<?php echo $i;?>" class="quantityConsom" value="<?php echo $qteConsom;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
							
							
							<td style="text-align:left;">
								<input type="text" name="prixprestaConsom[]" id="prixprestaConsom<?php echo $i;?>" class="prixprestaConsom" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
								
								<input type="hidden" name="idmedConsom[]" id="idmedConsom<?php echo $i;?>" class="idmedConsom" value="<?php echo $ligneMedConsom->id_medconsom;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
							
							<td style="text-align:left;">
								<input type="text" name="percentConsom[]" id="percentConsom<?php echo $i;?>" class="percentConsom" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
							</td>
							
							<?php
							$uapPrice= $balance - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							?>
							
							<td>
								<a href="formModifierBillHosp.php?deleteMedConsom=<?php echo $ligneMedConsom->id_medconsom;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
								
							</td>
						</tr>
					<?php
					}
					
					if($ligneMedConsom->id_prestationConsom==0 AND $ligneMedConsom->autreConsom!="")
					{
					?>
						<tr>
							<td style="text-align:left;">
								<select name="anneeMedConsom[]" id="anneeMedConsom" style="width:90px;">
									<?php
									for($a=2016;$a<=2050;$a++)
									{
										$anneeConsom=date('Y', strtotime($ligneMedConsom->datehosp));
									?>
										<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeConsom==$a) echo 'selected="selected"';?>>
										<?php echo $a;?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="moisMedConsom[]" id="moisMedConsom" style="width:120px;">
									<?php
									for($m=1;$m<=12;$m++)
									{
										$moisString=date("F",mktime(0,0,0,$m,10));
										$moisConsom=date("F",mktime(0,0,0,date('m', strtotime($ligneMedConsom->datehosp)),10));
									?>
										<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisConsom==$moisString) echo 'selected="selected"'; ?>>
										<?php
											echo $moisString;
										?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="joursMedConsom[]" id="joursMedConsom" style="width:70px;">
									<?php
									for($d=1;$d<=31;$d++)
									{
										$joursConsom=date('d', strtotime($ligneMedConsom->datehosp));
									?>
										<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursConsom==$d) echo 'selected="selected"'; ?>>
											<?php
											echo $d;
											?>
										</option>
									<?php
									}
									?>
								</select>
							</td>
							
							<td style="text-align:left;">

								<input type="hidden" name="idassuConsom[]" style="width:20px;text-align:center" id="idassuConsom<?php echo $i;?>" value="<?php echo $idassu;?>"/>
							<?php
								
								echo '<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom'.$i.'" value="0"/>';

								echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom'.$i.'" value="'.$ligneMedConsom->autreConsom.'"/>';
			
								$nameprestaMedConsom=$ligneMedConsom->autreConsom;
								echo $nameprestaMedConsom;
							?>
							</td>
						
							<td style="text-align:left;">
							<?php
								$qteConsom=$ligneMedConsom->qteConsom;
								
								echo $qteConsom;
								
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
								$prixPrestaCCO = $ligneMedConsom->prixautreConsomCCO;
								$prixPresta = $ligneMedConsom->prixautreConsom;
							
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>
							
							<td style="display:none;text-align:left;">
							<?php
								echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
							?>
							</td>
							
							<?php
							$balanceCCO=$prixPrestaCCO*$qteConsom;
							$balance=$prixPresta*$qteConsom;
							
							$TotalMedConsom=$TotalMedConsom + $balance;
							$patientPrice=($balance * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							?>
							
							<td style="text-align:left;">
								<input type="text" name="quantityConsom[]" id="quantityConsom<?php echo $i;?>" class="quantityConsom" value="<?php echo $qteConsom;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
							
							<td style="text-align:left;">
								<input type="text" name="prixprestaConsom[]" id="prixprestaConsom<?php echo $i;?>" class="prixprestaConsom" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
								
								<input type="hidden" name="idmedConsom[]" id="idmedConsom<?php echo $i;?>" class="idmedConsom" value="<?php echo $ligneMedConsom->id_medconsom;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
							
							<td style="text-align:left;">
								<input type="text" name="percentConsom[]" id="percentConsom<?php echo $i;?>" class="percentConsom" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
							</td>
								
								<?php
								$uapPrice= $balance - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								?>
								
							<td>
								<a href="formModifierBillHosp.php?deleteMedConsom=<?php echo $ligneMedConsom->id_medconsom;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
								
							</td>
						</tr>
				<?php
					}
					$i++;
				}
				?>
					<tr style="text-align:center;">
						
						<td colspan=4></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
						?>
						
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
							// echo $TotalMedConsom.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							
			// float round(float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP)
							
							?>
						</td>
						
						<td>
							<?php
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
							?>
						</td>
						
						<td colspan=4></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			}
			
			
			if($comptMedMedoc != 0)
			{
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;" id="tableMedoc">
				<thead>
					<tr>
						<th style="text-align:left;width:30%;">Date</th>
						<th style="text-align:left;">Medicaments</th>
						<th style="text-align:left;width:10%">Qté</th>
						<th style="text-align:left;width:7%">P/U</th>
						<th style="display:none;text-align:left;width:7%;">Pourcentage</th>
						<th style="text-align:left;width:8%;">Nouvelle Qté</th>
						<th style="text-align:left;width:11%">Nouveau P/U</th>
						<th style="text-align:left;width:12%;">Nouveau %</th>
						<th style="text-align:left;width:2%;">Action</th>
					</tr>
				</thead>

				<tbody>
				<?php
			
				$TotalpatientPrice=0;
				$TotaluapPrice=0;
				
				$i=1;
				
				while($ligneMedMedoc=$resultMedMedoc->fetch())
				{
				
					$billpercent=$ligneMedMedoc->insupercentMedoc;
					
					$idassu=$ligneMedMedoc->id_assuMedoc;
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($assu=1;$assu<=$assuCount;$assu++)
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
					'prestaId'=>$ligneMedMedoc->id_prestationMedoc
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
				
					if($comptPresta==0)
					{
						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedMedoc->id_prestationMedoc
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);
					}
					
					if($lignePresta=$resultPresta->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td style="text-align:left;">
								<select name="anneeMedMedoc[]" id="anneeMedMedoc" style="width:90px;">
									<?php
									for($a=2016;$a<=2050;$a++)
									{
										$anneeMedoc=date('Y', strtotime($ligneMedMedoc->datehosp));
									?>
										<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeMedoc==$a) echo 'selected="selected"';?>>
										<?php echo $a;?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="moisMedMedoc[]" id="moisMedMedoc" style="width:120px;">
									<?php
									for($m=1;$m<=12;$m++)
									{
										$moisString=date("F",mktime(0,0,0,$m,10));
										$moisMedoc=date("F",mktime(0,0,0,date('m', strtotime($ligneMedMedoc->datehosp)),10));
									?>
										<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisMedoc==$moisString) echo 'selected="selected"'; ?>>
										<?php
											echo $moisString;
										?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="joursMedMedoc[]" id="joursMedMedoc" style="width:70px;">
									<?php
									for($d=1;$d<=31;$d++)
									{
										$joursMedoc=date('d', strtotime($ligneMedMedoc->datehosp));
									?>
										<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursMedoc==$d) echo 'selected="selected"'; ?>>
											<?php
											echo $d;
											?>
										</option>
									<?php
									}
									?>
								</select>
							</td>
							
							<td style="text-align:left;">

								<input type="hidden" name="idassuMedoc[]" style="width:20px;text-align:center" id="idassuMedoc<?php echo $i;?>" value="<?php echo $idassu;?>"/>
							<?php
							
								echo '<input type="text" name="idprestaMedoc[]" style="width:100px;display:none; text-align:center" id="idprestaMedoc'.$i.'" value="'.$lignePresta->id_prestation.'"/>';
			
								echo '<input type="text" name="autreMedoc[]" style="width:100px;display:none; text-align:center" id="autreMedoc'.$i.'" value=""/>';
								
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
							</td>
							
							<td style="text-align:left;">
							<?php
								$balance=$prixPresta*$qteMedoc;
								
								echo $qteMedoc;
								
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							?>
							</td>
						
							<td style="text-align:left;">
							<?php
								echo $prixPresta;
							?>
							</td>
						
							<td style="display:none;text-align:left;">
							<?php
								echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
							?>
							</td>
							
							<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							?>
							
							<td style="text-align:left;">
								<input type="text" name="quantityMedoc[]" id="quantityMedoc<?php echo $i;?>" class="quantityMedoc" value="<?php echo $qteMedoc;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
							
							<td style="text-align:left;">
								<input type="text" name="prixprestaMedoc[]" id="prixprestaMedoc<?php echo $i;?>" class="prixprestaMedoc" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
								
								<input type="hidden" name="idmedMedoc[]" id="idmedMedoc<?php echo $i;?>" class="idmedMedoc" value="<?php echo $ligneMedMedoc->id_medmedoc;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
							
							<td style="text-align:left;">
								<input type="text" name="percentMedoc[]" id="percentMedoc<?php echo $i;?>" class="percentMedoc" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
							</td>
							
							<?php
								$uapPrice= $balance - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							?>
							
							<td>
								<a href="formModifierBillHosp.php?deleteMedMedoc=<?php echo $ligneMedMedoc->id_medmedoc;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
								
							</td>
						</tr>
					
					<?php
					}
					
					if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->autreMedoc!="")
					{
					?>
						<tr style="text-align:center;">
							<td style="text-align:left;">
								<select name="anneeMedMedoc[]" id="anneeMedMedoc" style="width:90px;">
									<?php
									for($a=2016;$a<=2050;$a++)
									{
										$anneeMedoc=date('Y', strtotime($ligneMedMedoc->datehosp));
									?>
										<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeMedoc==$a) echo 'selected="selected"';?>>
										<?php echo $a;?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="moisMedMedoc[]" id="moisMedMedoc" style="width:120px;">
									<?php
									for($m=1;$m<=12;$m++)
									{
										$moisString=date("F",mktime(0,0,0,$m,10));
										$moisMedoc=date("F",mktime(0,0,0,date('m', strtotime($ligneMedMedoc->datehosp)),10));
									?>
										<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisMedoc==$moisString) echo 'selected="selected"'; ?>>
										<?php
											echo $moisString;
										?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="joursMedMedoc[]" id="joursMedMedoc" style="width:70px;">
									<?php
									for($d=1;$d<=31;$d++)
									{
										$joursMedoc=date('d', strtotime($ligneMedMedoc->datehosp));
									?>
										<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursMedoc==$d) echo 'selected="selected"'; ?>>
											<?php
											echo $d;
											?>
										</option>
									<?php
									}
									?>
								</select>
							</td>
							
							<td style="text-align:left;">

								<input type="hidden" name="idassuMedoc[]" style="width:20px;text-align:center" id="idassuMedoc<?php echo $i;?>" value="<?php echo $idassu;?>"/>
							<?php
								echo $ligneMedMedoc->autreMedoc.'
								<input type="text" name="autreMedoc[]" style="width:100px;display:none;" id="autreMedoc'.$i.'" value="'.$ligneMedMedoc->autreMedoc.'"/>
								<input type="text" name="idprestaMedoc[]" style="width:100px;display:none;" id="idprestaMedoc'.$i.'" value="0"/>';
								
								$nameprestaMedMedoc=$ligneMedMedoc->autreMedoc;
								
								$qteMedoc=$ligneMedMedoc->qteMedoc;
								
								$prixPresta = $ligneMedMedoc->prixautreMedoc;
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
								$balance=$prixPresta*$qteMedoc;
								
								echo $qteMedoc;
								
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							?>
							</td>
						
							<td style="text-align:left;">
							<?php
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>
						
							<td style="display:none;text-align:left;">
							<?php
								echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
							?>
							</td>
						
							<?php
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							?>
							
							<td style="text-align:left;">
								<input type="text" name="quantityMedoc[]" id="quantityMedoc<?php echo $i;?>" class="quantityMedoc" value="<?php echo $qteMedoc;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>

							
							<td style="text-align:left;">
								<input type="text" name="prixprestaMedoc[]" id="prixprestaMedoc<?php echo $i;?>" class="prixprestaMedoc" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
								
								<input type="hidden" name="idmedMedoc[]" id="idmedMedoc<?php echo $i;?>" class="idmedMedoc" value="<?php echo $ligneMedMedoc->id_medmedoc;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
							
							<td style="text-align:left;">
								<input type="text" name="percentMedoc[]" id="percentMedoc<?php echo $i;?>" class="percentMedoc" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
							</td>
							
							<?php
								$uapPrice= $balance - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							?>
							
							<td>
								<a href="formModifierBillHosp.php?deleteMedMedoc=<?php echo $ligneMedMedoc->id_medmedoc;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
								
							</td>
						</tr>
				<?php
					}
					$i++;
				}
				?>
					<tr style="text-align:center;">
						<td colspan=4></td>
							<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
							?>
						
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
							// echo $TotalMedMedoc.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?>
						</td>
						<td>
						<?php
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
						?>
						</td>
						<td colspan=4></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			}
			
			
			if($comptMedConsult != 0)
			{
				?>

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;" id="tableServ">
					<thead>
						<tr>
							<th style="text-align:left;width:30%;">Date</th>
							<th style="text-align:left;">Services</th>
							<th style="text-align:left;width:10%">Qté</th>
							<th style="text-align:left;width:7%">P/U</th>
							<th style="display:none;text-align:left;width:7%;">Pourcentage</th>
							<th style="text-align:left;width:8%;">Nouvelle Qté</th>
							<th style="text-align:left;width:11%">Nouveau P/U</th>
							<th style="text-align:left;width:12%;">Nouveau %</th>
							<th style="text-align:left;width:2%;">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotalpatientPrice=0;
						
						$TotaluapPrice=0;
						
						
						$i=1;
						
						while($ligneMedConsult=$resultMedConsult->fetch())
						{
							
							$billpercent=$ligneMedConsult->insupercentServ;
							
							$idassu=$ligneMedConsult->id_assuServ;
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
							$assuCount = $comptAssuConsu->rowCount();
							
							for($assu=1;$assu<=$assuCount;$assu++)
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
								<td style="text-align:left;">
									<select name="anneeMedConsu[]" id="anneeMedConsu<?php echo $i;?>" style="width:90px;">
										<?php
										for($a=2016;$a<=2050;$a++)
										{
											$anneeConsu=date('Y', strtotime($ligneMedConsult->datehosp));
											?>
											<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeConsu==$a) echo 'selected="selected"';?>>
												<?php echo $a;?>
											</option>
											<?php
										}
										?>
									</select>

									<select name="moisMedConsu[]" id="moisMedConsu<?php echo $i;?>" style="width:120px;">
										<?php
										for($m=1;$m<=12;$m++)
										{
											$moisString=date("F",mktime(0,0,0,$m,10));
											$moisConsu=date("F",mktime(0,0,0,date('m', strtotime($ligneMedConsult->datehosp)),10));
											?>
											<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisConsu==$moisString) echo 'selected="selected"'; ?>>
												<?php
												echo $moisString;
												?>
											</option>
											<?php
										}
										?>
									</select>


									<select name="joursMedConsu[]" id="joursMedConsu" style="width:70px;">
										<?php
										for($d=1;$d<=31;$d++)
										{
											$joursConsu=date('d', strtotime($ligneMedConsult->datehosp));
											?>
											<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursConsu==$d) echo 'selected="selected"'; ?>>
												<?php
												echo $d;
												?>
											</option>
											<?php
										}
										?>
									</select>
								</td>

								<td style="text-align:left;">

									<input type="hidden" name="idassuServ[]" style="width:20px;text-align:center" id="idassuServ<?php echo $i;?>" value="<?php echo $idassu;?>"/>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
									
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsult->id_prestationConsu
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
									
									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())
									{
									echo '<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta'.$i.'" class="idpresta" value="'.$lignePresta->id_prestation.'"/>';
									echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu'.$i.'" value=""/>';
									
									if($lignePresta->namepresta!='')
									{
										$nameprestaMedConsult=$lignePresta->namepresta;
										echo $lignePresta->namepresta;
										
									}else{
										
										$nameprestaMedConsult=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
									}
									?>
								</td>

								<td style="text-align:left;">
									<?php
									$qteConsu=$ligneMedConsult->qteConsu;
									
									echo $qteConsu;
									?>
								</td>

								<td style="text-align:left;">
									<?php
									$prixPresta = $ligneMedConsult->prixprestationConsu;
									
									echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
									?>
								</td>

								<td style="display:none;text-align:left;">
									<?php
									echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
									?>
								</td>
								
								<?php
								$balance=$prixPresta*$qteConsu;
								
								$TotalMedConsult=$TotalMedConsult + $balance;
								?>

								<td style="text-align:left;">
									<input type="text" name="quantityConsu[]" id="quantityConsu<?php echo $i;?>" class="quantityConsu" value="<?php echo $qteConsu;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
								</td>

								<td style="text-align:left;">
									<input type="text" name="prixprestaConsu[]" id="prixprestaConsu<?php echo $i;?>" class="prixprestaConsu" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

								</td>
								
								<?php
								$patientPrice=($balance * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								$uapPrice= $prixPresta - $patientPrice;
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								
								if($ligneMedConsult->id_prestationConsu==NULL AND $ligneMedConsult->autreConsu!="")
								{
									
									echo '<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta'.$i.'" value="0"/>';
									
									echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu'.$i.'" value="'.$ligneMedConsult->autreConsu.'"/>';
									
									$nameprestaMedConsult=$ligneMedConsult->autreConsu;
									echo $ligneMedConsult->autreConsu;
									
									?>
									</td>

									<td style="text-align:left;">
										<?php
										$qteConsu=$ligneMedConsult->qteConsu;
										
										echo $qteConsu;
										?>
									</td>

									<td style="text-align:left;">
										<?php
										$prixPresta = $ligneMedConsult->prixautreConsu;
										
										echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<td style="display:none;text-align:left;">
										<?php
										echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
										?>
									</td>
									
									<?php
									$balance=$prixPresta*$qteConsu;
									
									$TotalMedConsult=$TotalMedConsult + $balance;
									?>

									<td style="text-align:left;">
										<input type="text" name="quantityConsu[]" id="quantityConsu<?php echo $i;?>" class="quantityConsu" value="<?php echo $qteConsu;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
									</td>


									<td style="text-align:left;">
										<input type="text" name="prixprestaConsu[]" id="prixprestaConsu<?php echo $i;?>" class="prixprestaConsu" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

									</td>
									
									<?php
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									$uapPrice= $prixPresta - $patientPrice;
									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								?>

								<td style="text-align:left;">
									<input type="hidden" name="idmedConsu[]" id="idmedConsu<?php echo $i;?>" class="idmedConsu" value="<?php echo $ligneMedConsult->id_medconsu;?>" style="width:30%; font-weight:normal;margin-top:5px"/>

									<input type="text" name="percentConsu[]" id="percentConsu<?php echo $i;?>" class="percentConsu" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>

								</td>

								<td>
									<a href="formModifierBillHosp.php?deleteMedConsu=<?php echo $ligneMedConsult->id_medconsu;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&datehosp=<?php echo $_GET['datehosp'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&idassu=<?php echo $_GET['idassu'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

								</td>
							</tr>
							<?php
							$i++;
						}
						?>
						<tr style="text-align:center;">

							<td colspan=4></td>
							<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
							?>

							<td style="text-align:left; font-size: 13px; font-weight: bold;">
								<?php
								// echo $TotalMedConsult.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								?>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								?>
							</td>

							<td colspan=4></td>
						</tr>
					</tbody>
				</table>
				<?php
			}
			
			
		}

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
		}

		// Get All Cashier List

		$GetCachier = $connexion->query('SELECT * FROM utilisateurs u,cashiers c WHERE c.id_u=u.id_u');
		$GetCachier->setFetchMode(PDO::FETCH_OBJ);
		$countCashier = $GetCachier->rowCount();
		//echo $countCashier;

		?>
			<table class="tablesorter tablesorter1" cellspacing="0" style="background:none;border:none; width:70%;">

				<tr style="text-align:center;">
					<td style="font-size: 110%; font-weight: bold;text-align:center;" class="buttonBill">
						Change Cashier
					</td>
				</tr>

				<tr style="text-align:center;">
					<td>
						<label style="color: red;">Done By:</label>
						<select name="Change_cashier">
							<option value="">Select Cashier...</option>
							<?php 
								while($FetchCashier = $GetCachier->fetch()){
							?>
								<option value="<?php echo $FetchCashier->codecashier; ?>" <?php if($FetchCashier->codecashier == $codecashierHosp){echo "selected='selected'";} ?>><?php echo $FetchCashier->full_name; ?></option>
							<?php
								}
							?>
						</select>
					</td>

				</tr>

				<tr>
					<td>
						<button type="submit" id="previewbtn" name="previewbtn" class="btn-large"/>
							<i class="fa fa-desktop fa-lg fa-fw"></i> <?php echo 'Save';?>
						</button>
					</td>
				</tr>
			</table>
		
	</form>
	</div>

<?php

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	

}
?>

	<script type="text/javascript">
	
		function myScriptAnnee()
	   {
		   var i;
		   var test = [];
		   var annee = $('#annee').val();
		   var mois = $('#mois').val();
		   var jours = new Date(annee, mois , 0).getDate();
		   $('#jours').empty();
		   for(i = 1; i<= jours; i++)
		   {
				test[i-1] = i;
				$('#jours').append('<option value="' + i + '">'
						+ i + '</option>');
		   }
	   }
		
		function myScriptMois()
	   {
		   var i;
		   var test = [];
		   var annee = $('#annee').val();
		   var mois = $('#mois').val();
		   var jours = new Date(annee, mois , 0).getDate();
		   $('#jours').empty();
		   for(i = 1; i<= jours; i++)
		   {
				test[i-1] = i;
				var j = $('#jourN').val();
				var h = '';
				if(j==i)
				{
					h = 'selected = "selected"';
				}
				
				$('#jours').append('<option value="' + i + '"' + h +'>'
						+ i + '</option>');
		   }
	   }
	   
	   
	   function myScriptAnneeC()
	   {
		   var i;
		   var test = [];
		   var anneeC = $('#anneeC').val();
		   var moisC = $('#moisC').val();
		   var joursC = new Date(anneeC, moisC , 0).getDate();
		   $('#joursC').empty();
		   for(i = 1; i<= joursC; i++)
		   {
				test[i-1] = i;
				$('#joursC').append('<option value="' + i + '">'
						+ i + '</option>');
		   }
	   }
		
		function myScriptMoisC()
	   {
		   var i;
		   var test = [];
		   var anneeC = $('#anneeC').val();
		   var moisC = $('#moisC').val();
		   var joursC = new Date(anneeC, moisC , 0).getDate();
		   $('#joursC').empty();
		   for(i = 1; i<= joursC; i++)
		   {
				test[i-1] = i;
				var j = $('#jourC').val();
				var h = '';
				if(j==i)
				{
					h = 'selected = "selected"';
				}
				
				$('#joursC').append('<option value="' + i + '"' + h +'>'
						+ i + '</option>');
		   }
	   }
	   
	</script>
	
</body>

	<script type="text/javascript">
	
	
	function ShowAssurance(assure)
	{
		var assurance=document.getElementById('assurance').value;
		
		if( assurance == 1){
			document.getElementById('insuranceIDbill').style.display='none';
		}else{
			document.getElementById('insuranceIDbill').style.display='inline-block';
		}
	}

	</script>
	
</html>