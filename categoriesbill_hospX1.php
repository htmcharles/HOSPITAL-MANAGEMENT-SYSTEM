<?php 
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


	$annee = date('Y').'-'.date('m').'-'.date('d');
	

if(!isset($_GET['doneBill']) AND isset($_GET['deletebill']))
{
	
	$idBill=$_GET['deletebill'];

	$deleteBilling=$connexion->prepare('DELETE FROM bills WHERE id_bill=:idBill');
		
	$deleteBilling->execute(array(
	'idBill'=>$idBill
	
	))or die($deleteBilling->errorInfo());
	
}

if(isset($_GET['previewprint']))
{
	$previewback='previewprint='.$_GET['previewprint'];
}else{
	$previewback="";
}


if(isset($_GET['deleteMedConsu']))
{

	$id_medC = $_GET['deleteMedConsu'];
	
	$deleteConsu=$connexion->prepare('DELETE FROM med_consult_hosp WHERE id_medconsu=:id_medC');
	
	$deleteConsu->execute(array(
	'id_medC'=>$id_medC
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un service");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedInf']))
{

	$id_medI = $_GET['deleteMedInf'];
	
	$deleteInf=$connexion->prepare('DELETE FROM med_inf_hosp WHERE id_medinf=:id_medI');
	
	$deleteInf->execute(array(
	'id_medI'=>$id_medI
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un soins");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}


if(isset($_GET['deleteMedSurge']))
{

	$id_medI = $_GET['deleteMedSurge'];
	
	$deleteSurge=$connexion->prepare('DELETE FROM med_surge_hosp WHERE id_medsurge=:id_medI');
	
	$deleteSurge->execute(array(
	'id_medI'=>$id_medI
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un acte chirurgical");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedLabo']))
{

	$id_medL= $_GET['deleteMedLabo'];
	
	$deleteLabo=$connexion->prepare('DELETE FROM med_labo_hosp WHERE id_medlabo=:id_medL');
	
	$deleteLabo->execute(array(
	'id_medL'=>$id_medL
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un examen");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedRadio']))
{

	$id_medX= $_GET['deleteMedRadio'];
	
	$deleteRadio=$connexion->prepare('DELETE FROM med_radio_hosp WHERE id_medradio=:id_medX');
	
	$deleteRadio->execute(array(
	'id_medX'=>$id_medX
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une radio");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedKine']))
{
	$id_medX= $_GET['deleteMedKine'];
	
	$deleteKine=$connexion->prepare('DELETE FROM med_kine_hosp WHERE id_medkine=:id_medX');
	
	$deleteKine->execute(array(
	'id_medX'=>$id_medX
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une kiné");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedConsom']))
{

	$id_medCo= $_GET['deleteMedConsom'];
	
	$deleteConsom=$connexion->prepare('DELETE FROM med_consom_hosp WHERE id_medconsom=:id_medCo');
	
	$deleteConsom->execute(array(
	'id_medCo'=>$id_medCo
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un consommable");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedMedoc']))
{

	$id_medMe= $_GET['deleteMedMedoc'];
	
	$deleteMedoc=$connexion->prepare('DELETE FROM med_medoc_hosp WHERE id_medmedoc=:id_medMe');
	
	$deleteMedoc->execute(array(
	'id_medMe'=>$id_medMe
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un medicament");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedOrtho']))
{

	$id_medMe= $_GET['deleteMedOrtho'];
	
	$deleteOrtho=$connexion->prepare('DELETE FROM med_ortho_hosp WHERE id_medortho=:id_medMe');
	
	$deleteOrtho->execute(array(
	'id_medMe'=>$id_medMe
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une orthopedie");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

/*
if(isset($_GET['cancelbtn']))
{
	
	$idhosp=$_GET['idhosp'];
	
	/*----------Update Hosp (Delete serial number)----------------*/
/*
	$updateIdFactureHosp=$connexion->prepare('UPDATE patients_hosp ph SET ph.id_factureHosp=NULL WHERE ph.id_hosp=:idhosp AND ph.numero=:num');

	$updateIdFactureHosp->execute(array(
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));
	
}
*/

if(isset($_GET['finishbtn']))
{
	if($_GET['createBill']==1)
	{
		createON('H');
	}
	
	$idBilling=$_GET['idbill'];
	$idhosp=$_GET['idhosp'];
	
	/*----------Update Hosp----------------*/
	
	$updateIdFactureHosp=$connexion->prepare('UPDATE patients_hosp ph SET ph.id_factureHosp=:idbill, ph.codecashierHosp=:codecashier WHERE ph.id_hosp=:idhosp AND ph.id_factureHosp IS NULL AND ph.numero=:num');

	$updateIdFactureHosp->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Consult----------------*/
	
	$updateIdFactureMedConsult=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.id_factureMedConsu=:idbill, mc.codecashier=:codecashier WHERE mc.id_hospMed=:idhosp AND mc.numero=:num AND mc.id_factureMedConsu=""');

	$updateIdFactureMedConsult->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Inf----------------*/
	
	$updateIdFactureMedInf=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.id_factureMedInf=:idbill, mi.codecashier=:codecashier WHERE mi.id_hospInf=:idhosp AND mi.numero=:num AND mi.id_factureMedInf=""');

	$updateIdFactureMedInf->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash'] 
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Surge----------------*/
	
	$updateIdFactureMedSurge=$connexion->prepare('UPDATE med_surge_hosp ms SET ms.id_factureMedSurge=:idbill, ms.codecashier=:codecashier WHERE ms.id_hospSurge=:idhosp AND ms.numero=:num AND ms.id_factureMedSurge=""');

	$updateIdFactureMedSurge->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash'] 
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Labo----------------*/
	
	$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.id_factureMedLabo=:idbill, ml.codecashier=:codecashier WHERE ml.id_hospLabo=:idhosp AND ml.numero=:num AND ml.id_factureMedLabo=""');

	$updateIdFactureMedLabo->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Radio----------------*/
	
	$updateIdFactureMedRadio=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.id_factureMedRadio=:idbill, mr.codecashier=:codecashier WHERE mr.id_hospRadio=:idhosp AND mr.numero=:num AND mr.id_factureMedRadio=""');

	$updateIdFactureMedRadio->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Kine----------------*/
	
	$updateIdFactureMedKine=$connexion->prepare('UPDATE med_kine_hosp mk SET mk.id_factureMedKine=:idbill, mk.codecashier=:codecashier WHERE mk.id_hospKine=:idhosp AND mk.numero=:num AND mk.id_factureMedKine=""');

	$updateIdFactureMedKine->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Consom----------------*/
	
	$updateIdFactureMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.id_factureMedConsom=:idbill, mco.codecashier=:codecashier WHERE mco.id_hospConsom=:idhosp AND mco.numero=:num AND mco.id_factureMedConsom=""');

	$updateIdFactureMedConsom->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Medoc----------------*/
	
	$updateIdFactureMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.id_factureMedMedoc=:idbill, mdo.codecashier=:codecashier WHERE mdo.id_hospMedoc=:idhosp AND mdo.numero=:num AND mdo.id_factureMedMedoc=""');

	$updateIdFactureMedMedoc->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	/*----------Update Med_Ortho----------------*/
	
	$updateIdFactureMedOrtho=$connexion->prepare('UPDATE med_Ortho_hosp mdo SET mdo.id_factureMedOrtho=:idbill, mdo.codecashier=:codecashier WHERE mdo.id_hospOrtho=:idhosp AND mdo.numero=:num AND mdo.id_factureMedOrtho=""');

	$updateIdFactureMedOrtho->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
}

?>

<!doctype html>
<html>
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<meta charset="utf-8"/>
	<title><?php echo getString(2); ?></title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />

		<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	
	<script src="script.js"></script>
			
			<!------------------------------------>
	
	<link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />
	
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"/><!--Header-->

		<!-------------------calendrier------------------->
	
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>	
	<link href="css/form-signin.css" rel="stylesheet" type="text/css">

			<!---------------Pagination--------------------->
			
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">		
	
	<script src="myQuery.js"></script>

	<script type="text/javascript">

function controlFormCategoBill(theForm){

	<?php
	if(isset($_GET['sortie']))
	{
	?>
	var prixprestaHosp=document.getElementById('prixprestaHosp');
	var percentHosp=document.getElementById('percentHosp');
	<?php
	}
	?>
	
	function controlMedecinSelected(fld){
		
		erreur="error";
		fld.style.background="rgba(0,255,0,0.3)";
		
		return erreur;
	}

	var prixprestaConsu=document.getElementsByClassName("prixprestaConsu");
	var quantityConsu=document.getElementsByClassName("quantityConsu");
	var percentConsu=document.getElementsByClassName("percentConsu");
	
	
	var prixprestaInf=document.getElementsByClassName("prixprestaInf");
	var quantityInf=document.getElementsByClassName("quantityInf");
	var percentInf=document.getElementsByClassName("percentInf");
	
	
	var prixprestaSurge=document.getElementsByClassName("prixprestaSurge");
	var quantitySurge=document.getElementsByClassName("quantitySurge");
	var percentSurge=document.getElementsByClassName("percentSurge");
	
	
	var prixprestaLab=document.getElementsByClassName("prixprestaLab");
	var quantityLab=document.getElementsByClassName("quantityLab");
	var percentLab=document.getElementsByClassName("percentLab");
	
	
	var prixprestaRad=document.getElementsByClassName("prixprestaRad");
	var quantityRad=document.getElementsByClassName("quantityRad");
	var percentRad=document.getElementsByClassName("percentRad");
	
	
	var prixprestaKine=document.getElementsByClassName("prixprestaKine");
	var quantityKine=document.getElementsByClassName("quantityKine");
	var percentKine=document.getElementsByClassName("percentKine");
	
	
	var prixprestaConsom=document.getElementsByClassName("prixprestaConsom");
	var quantityConsom=document.getElementsByClassName("quantityConsom");
	var percentConsom=document.getElementsByClassName("percentConsom");
	
	
	var prixprestaMedoc=document.getElementsByClassName("prixprestaMedoc");
	var quantityMedoc=document.getElementsByClassName("quantityMedoc");
	var percentMedoc=document.getElementsByClassName("percentMedoc");
	
	
	var prixprestaOrtho=document.getElementsByClassName("prixprestaOrtho");
	var quantityOrtho=document.getElementsByClassName("quantityOrtho");
	var percentOrtho=document.getElementsByClassName("percentOrtho");
	
	
	
	var rapportPrixHosp="";
	var rapportPercentHosp="";
	
	var i;
	var rapportPrixConsu = [];
	var rapportQuantityConsu = [];
	var rapportPercentConsu = [];
	
	var rapportPrixInf = [];
	var rapportQuantityInf = [];
	var rapportPercentInf = [];
	
	var rapportPrixSurge = [];
	var rapportQuantitySurge = [];
	var rapportPercentSurge = [];
	
	var rapportPrixLab = [];
	var rapportQuantityLab = [];
	var rapportPercentLab = [];
	
	var rapportPrixRad = [];
	var rapportQuantityRad = [];
	var rapportPercentRad = [];
	
	var rapportPrixKine = [];
	var rapportQuantityKine = [];
	var rapportPercentKine = [];
	
	var rapportPrixConsom = [];
	var rapportQuantityConsom = [];
	var rapportPercentConsom = [];
	
	var rapportPrixMedoc = [];
	var rapportQuantityMedoc = [];
	var rapportPercentMedoc = [];
	
	var rapportPrixOrtho = [];
	var rapportQuantityOrtho = [];
	var rapportPercentOrtho = [];
	
	
	<?php
	if(isset($_GET['sortie']))
	{
	?>
	if(prixprestaHosp.value > 0)
	{
		prixprestaHosp.style.background="white";
	}else{			
		rapportPrixHosp=controlPrixprestaHosp(prixprestaHosp);	
	}
	
		function controlPrixprestaHosp(fld){
			
			erreur="error";
			fld.style.background="rgba(0,255,0,0.3)";

			return erreur;	
		}
			
				
	if(percentHosp.value >= 0 && percentHosp.value !="")
	{
		percentHosp.style.background="white";
	}else{			
		rapportPercentHosp=controlPercentHosp(percentHosp);	
	}
		
		function controlPercentHosp(fld){
			
			erreur="error";
			fld.style.background="rgba(0,255,0,0.3)";

			return erreur;	
		}		
	
	<?php
	}
	?>
	
		for(i=0; i<prixprestaConsu.length; ++i){
			
			if(prixprestaConsu[i].value > 0){
				prixprestaConsu[i].style.background="white";
			}else{			
				rapportPrixConsu[i]=controlPrixprestaConsu(prixprestaConsu[i]);	
			}	
		}			
			function controlPrixprestaConsu(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
				
		for(i=0; i<quantityConsu.length; ++i){
			
			if(quantityConsu[i].value > 0){
				quantityConsu[i].style.background="white";
			}else{			
				rapportQuantityConsu[i]=controlQuantityConsu(quantityConsu[i]);	
			}
		}
			function controlQuantityConsu(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentConsu.length; ++i){	
						
			if(percentConsu[i].value >= 0 && percentConsu[i].value !=""){
				percentConsu[i].style.background="white";
			}else{			
				rapportPercentConsu[i]=controlPercentConsu(percentConsu[i]);	
			}			
		}			
			function controlPercentConsu(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
		
		
		for(i=0; i<prixprestaInf.length; ++i){
			
			if(prixprestaInf[i].value > 0){
				prixprestaInf[i].style.background="white";
			}else{			
				rapportPrixInf[i]=controlPrixprestaInf(prixprestaInf[i]);	
			}
		}				
			function controlPrixprestaInf(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<quantityInf.length; ++i){
			
			if(quantityInf[i].value > 0){
				quantityInf[i].style.background="white";
			}else{			
				rapportQuantityInf[i]=controlQuantityInf(quantityInf[i]);	
			}
		}
			function controlQuantityInf(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentInf.length; ++i){	
									
			if(percentInf[i].value >= 0 && percentInf[i].value !=""){
				percentInf[i].style.background="white";
			}else{			
				rapportPercentInf[i]=controlPercentInf(percentInf[i]);	
			}
		}
			function controlPercentInf(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
		
		
		for(i=0; i<prixprestaSurge.length; ++i){
			
			if(prixprestaSurge[i].value > 0){
				prixprestaSurge[i].style.background="white";
			}else{			
				rapportPrixSurge[i]=controlPrixprestaSurge(prixprestaSurge[i]);	
			}
		}				
			function controlPrixprestaSurge(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<quantitySurge.length; ++i){
			
			if(quantitySurge[i].value > 0){
				quantitySurge[i].style.background="white";
			}else{			
				rapportQuantitySurge[i]=controlQuantitySurge(quantitySurge[i]);	
			}
		}
			function controlQuantitySurge(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentSurge.length; ++i){	
									
			if(percentSurge[i].value >= 0 && percentSurge[i].value !=""){
				percentSurge[i].style.background="white";
			}else{			
				rapportPercentSurge[i]=controlPercentSurge(percentSurge[i]);	
			}
		}
			function controlPercentSurge(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
		
		
		for(i=0; i<prixprestaLab.length; ++i){
		
			if(prixprestaLab[i].value > 0 && prixprestaLab[i].value !=""){
				prixprestaLab[i].style.background="white";
			}else{
				rapportPrixLab[i]=controlPrixprestaLab(prixprestaLab[i]);
			}

		}		
			function controlPrixprestaLab(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<quantityLab.length; ++i){
			
			if(quantityLab[i].value > 0){
				quantityLab[i].style.background="white";
			}else{			
				rapportQuantityLab[i]=controlQuantityLab(quantityLab[i]);	
			}
		}
			function controlQuantityLab(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentLab.length; ++i){
		
			if(percentLab[i].value >= 0 && percentLab[i].value !=""){
				percentLab[i].style.background="white";
			}else{			
				rapportPercentLab[i]=controlPercentLab(percentLab[i]);	
			}
		}
			function controlPercentLab(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
		
		
		for(i=0; i<prixprestaRad.length; ++i){
		
			if(prixprestaRad[i].value > 0 && prixprestaRad[i].value !=""){
				prixprestaRad[i].style.background="white";
			}else{
				rapportPrixRad[i]=controlPrixprestaRad(prixprestaRad[i]);
			}

		}		
			function controlPrixprestaRad(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<quantityRad.length; ++i){
			
			if(quantityRad[i].value > 0){
				quantityRad[i].style.background="white";
			}else{			
				rapportQuantityRad[i]=controlQuantityRad(quantityRad[i]);	
			}
		}
			function controlQuantityRad(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentRad.length; ++i){
		
			if(percentRad[i].value >= 0 && percentRad[i].value !=""){
				percentRad[i].style.background="white";
			}else{			
				rapportPercentRad[i]=controlPercentRad(percentRad[i]);	
			}
		}
			function controlPercentRad(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
		
		
		for(i=0; i<prixprestaKine.length; ++i){
		
			if(prixprestaKine[i].value > 0 && prixprestaKine[i].value !=""){
				prixprestaKine[i].style.background="white";
			}else{
				rapportPrixKine[i]=controlPrixprestaKine(prixprestaKine[i]);
			}

		}		
			function controlPrixprestaKine(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<quantityKine.length; ++i){
			
			if(quantityKine[i].value > 0){
				quantityKine[i].style.background="white";
			}else{			
				rapportQuantityKine[i]=controlQuantityKine(quantityKine[i]);	
			}
		}
			function controlQuantityKine(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentKine.length; ++i){
		
			if(percentKine[i].value >= 0 && percentKine[i].value !=""){
				percentKine[i].style.background="white";
			}else{			
				rapportPercentKine[i]=controlPercentKine(percentKine[i]);	
			}
		}
			function controlPercentKine(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
				
		
		for(i=0; i<prixprestaConsom.length; ++i){
		
			if(prixprestaConsom[i].value > 0){
				prixprestaConsom[i].style.background="white";
			}else{			
				rapportPrixConsom[i]=controlPrixprestaConsom(prixprestaConsom[i]);	
			}
		}
			function controlPrixprestaConsom(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<quantityConsom.length; ++i){
			
			if(quantityConsom[i].value > 0){
				quantityConsom[i].style.background="white";
			}else{			
				rapportQuantityConsom[i]=controlQuantityConsom(quantityConsom[i]);	
			}
		}
			function controlQuantityConsom(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentConsom.length; ++i){
			
			if(percentConsom[i].value >= 0 && percentConsom[i].value !=""){
				percentConsom[i].style.background="white";
			}else{			
				rapportPercentConsom[i]=controlPercentConsom(percentConsom[i]);	
			}
		}
			function controlPercentConsom(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
		
		
		for(i=0; i<prixprestaMedoc.length; ++i){
			
			if(prixprestaMedoc[i].value > 0){
				prixprestaMedoc[i].style.background="white";
			}else{			
				rapportPrixMedoc[i]=controlPrixprestaMedoc(prixprestaMedoc[i]);	
			}
		}	
			function controlPrixprestaMedoc(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<quantityMedoc.length; ++i){
			
			if(quantityMedoc[i].value > 0){
				quantityMedoc[i].style.background="white";
			}else{			
				rapportQuantityMedoc[i]=controlQuantityMedoc(quantityMedoc[i]);	
			}
		}	
			function controlQuantityMedoc(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentMedoc.length; ++i){
		
			if(percentMedoc[i].value >= 0 && percentMedoc[i].value !=""){
				percentMedoc[i].style.background="white";
			}else{			
				rapportPercentMedoc[i]=controlPercentMedoc(percentMedoc[i]);	
			}
		}
			function controlPercentMedoc(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
		
		
		for(i=0; i<prixprestaOrtho.length; ++i){
			
			if(prixprestaOrtho[i].value > 0){
				prixprestaOrtho[i].style.background="white";
			}else{			
				rapportPrixOrtho[i]=controlPrixprestaOrtho(prixprestaOrtho[i]);	
			}
		}	
			function controlPrixprestaOrtho(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<quantityOrtho.length; ++i){
			
			if(quantityOrtho[i].value > 0){
				quantityOrtho[i].style.background="white";
			}else{			
				rapportQuantityOrtho[i]=controlQuantityOrtho(quantityOrtho[i]);	
			}
		}	
			function controlQuantityOrtho(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentOrtho.length; ++i){
		
			if(percentOrtho[i].value >= 0 && percentOrtho[i].value !=""){
				percentOrtho[i].style.background="white";
			}else{			
				rapportPercentOrtho[i]=controlPercentOrtho(percentOrtho[i]);	
			}
		}
			function controlPercentOrtho(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
		
		
	if (rapportPrixHosp != "" || rapportPercentHosp != "" || rapportPrixConsu != "" || rapportQuantityConsu != "" || rapportPercentConsu != "" || rapportPrixInf != "" || rapportQuantityInf != "" || rapportPercentInf != "" || rapportPrixSurge != "" || rapportQuantitySurge != "" || rapportPercentSurge != "" || rapportPrixLab != "" || rapportQuantityLab != "" || rapportPercentLab != "" || rapportPrixRad != "" || rapportQuantityRad != "" || rapportPercentRad != "" || rapportPrixKine != "" || rapportQuantityKine != "" || rapportPercentKine != "" || rapportPrixConsom != "" || rapportQuantityConsom != "" || rapportPercentConsom != "" || rapportPrixMedoc != "" || rapportQuantityMedoc != "" || rapportPercentMedoc != "" || rapportPrixOrtho != "" || rapportQuantityOrtho != "" || rapportPercentOrtho != "") {
	
		alert("Veuillez corriger les erreurs._");
		
				return false;		
	 }
		
	
}


</script>

	<script type="text/javascript">

function controlFormCategoBillHosp(theForm){

	var rapportMedecin="";
	
	var medecin=document.getElementById('medecins');
	
	if(medecin.value != 0)
	{
		medecin.style.background="white";
	}else{			
		rapportMedecin=controlMedecinSelected(medecin);	
	}
	
	function controlMedecinSelected(fld){
		
		erreur="error";
		fld.style.background="rgba(0,255,0,0.3)";
		
		return erreur;
	}

		
	if (rapportMedecin != "") {
	
		alert("Veuillez selectionner un medecin.");
		
				return false;		
	 }
		
	
}


</script>

</head>

<body onload="myScriptMois()">
<?php

$id=$_SESSION['id'];

$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");
$sqlO=$connexion->query("SELECT *FROM orthopedistes o WHERE o.id_u='$id'");
$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");

$comptidI=$sqlI->rowCount();
$comptidO=$sqlO->rowCount();
$comptidC=$sqlC->rowCount();


$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND ($comptidI!=0 OR $comptidO!=0 OR $comptidC!=0) AND isset($_GET['num']))
{
	if($status==1)
	{

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
			$nom_uti=$ligne->nom_u;
			$prenom_uti=$ligne->prenom_u;
			$sexe=$ligne->sexe;
			$dateN=$ligne->date_naissance;
			$province=$ligne->province;
			$district=$ligne->district;
			$secteur=$ligne->secteur;
			$phone=$ligne->telephone;
			$mail=$ligne->e_mail;
			$profession=$ligne->profession;
			$idassu=$ligne->id_assurance;
			$bill=$ligne->bill;
			$password=$ligne->password;
			$idP=$ligne->id_u;
			}
			$resultats->closeCursor();

			$old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].'	';//reçoit l'année de naissance
			$month=$dateN[5].''.$dateN[6].'	';//reçoit le mois de naissance

			$an= date('Y')-$old.'	';//recupere l'âge en année
			$mois= date('m')-$month.'	';//recupere l'âge en mois

			if($mois<0)
			{
				$an= ($an-1).' ans	'.(12+$mois).' mois';
				// echo $an= $an-1;

			}else{

				$an= $an.' ans';
				//$an= $an.' ans	'.(date('m')-$month).' mois';// X ans Y mois
				// echo $mois= date('m')-$month;
			}
			
							
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

			
		}

?>

<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<img src="images/logo_large.png" class="brand" />	
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
				<li class="">			
					<form method="post" action="categoriesbill_hosp.php?num=<?php echo $_GET['num'];?>&inf=<?php echo $_GET['inf'];?>&facture=ok<?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];} if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="categoriesbill_hosp.php?english=english<?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['previewprint'])){ echo '&previewprint='.$_GET['previewprint'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['inf'])){ echo '&inf='.$_GET['inf'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="categoriesbill_hosp.php?francais=francais<?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['previewprint'])){ echo '&previewprint='.$_GET['previewprint'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['inf'])){ echo '&inf='.$_GET['inf'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?>" class="btn"><?php echo getString(29);?></a>
					<?php
					}
					?>
						<br/>						
					
						<input type="text" name="Pass" id="Pass" placeholder="<?php echo getString(26);?>" style="margin-top:10px;margin-bottom:0;height:20px;"/>
						
						<input type="submit" name="confirmPass" id="confirmPass" class="btn"  value="<?php echo getString(27);?>"/>
						
					
					</form>
				</li>	
				</ul>
			</div><!--/.nav-collapse -->
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div><br><br><br><br><br>

<?php
if(isset($_SESSION['infhosp']))
{
	$infhosp=$_SESSION['infhosp'];
	
	if($infhosp!=0)
	{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
	}
}
?>

<?php
if(isset($_SESSION['codeO']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
}
?>

<?php
if($comptidC!=0)
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
}
?>

<div class="account-container" style="width:90%">

<?php

if($comptidI!=0 OR $comptidO!=0 OR $comptidC!=0)
{
?>
<div id='cssmenu' style="text-align:center;">

<ul style="margin-top:20px;background:none;border:none;">

	<li style="width:50%;"><a href="patients1_hosp.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#listPatientHosp" style="margin-right:5px;" data-title="Patients hospitalisés"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients hospitalisés</a></li>
	
	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49); ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49); ?></a></li>
	
</ul>

<ul style="margin-top:20px; background:none;border:none;">
	
	<div style="display:none;" id="divMenuMsg">

		<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57); ?></a>
		
		<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
		
		<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

	</div>

</ul>
	
</div>
<?php
}
?>

	<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:10px auto auto auto; padding: 10px; width:80%;">
		<tr>
			<td style="text-align:left; width:33.333%;">
				
				<p class="patientId"><span>S/N:</span> <?php echo $num; ?>

				<p class="patientId"><span> <?php echo getString(89);?>:</span> <?php echo $nom_uti.' '.$prenom_uti; ?>
				
			</td>
		<?php
		if($idassu!=NULL)
		{
		?>
			<td style="text-align:center; width:33.333%;">
				
				<p class="patientId"><span>Insurance type:</span>
				<?php
				
				$resultAssu=$connexion->prepare('SELECT * FROM assurances a WHERE id_assurance=:assu');
				$resultAssu->execute(array(
				'assu'=>$idassu
				)); 
				
				if($ligneAssu=$resultAssu->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
				{
					echo $ligneAssu->nomassurance.' ('.$bill.'%)';
				}
				?>

			</td>
		<?php
		}else{
		?>
			<td style="text-align:center; width:33.333%;">
				
				<p class="patientId"><span>Insurance type:</span> <?php echo "Privé"; ?>
				
			</td>
		<?php
		}
		?>

			<td style="font-size:18px; padding-right:20px; text-align:right; width:33.333%;">
				<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y', strtotime($_GET['datehosp']));?>
				
				<input size="25px" type="hidden" id="today" name="today" value="<?php echo $annee;?>"/>
			</td>
		</tr>

	</table>
	
	<form method="post" action="addpresta_hosp.php?inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idassu=<?php echo $_GET['idassu'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idhosp=<?php echo $_GET['idhosp'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>" onsubmit="return controlFormCategoBillHosp(this)" enctype="multipart/form-data">
		
		<table align="center">
		   
			<tr style="<?php if(isset($_GET['previewprint'])){ echo 'display:none;';} ?>">
				<td><label for="medecins"><?php echo 'Nom du medecin'; ?></label></td>
							
				<td style="padding:10px;" align="center">										
					<select name="medecins" id="medecins" style="background:white; border:1px solid #ddd; height:40px; width:500px;">

						<option value='0'><?php echo 'Selectionner ici...'; ?></option>
					<?php
					
					$resultatsMedecins=$connexion->query('SELECT *FROM utilisateurs u, medecins m, categopresta_ins cp, servicemed sm WHERE u.id_u=m.id_u AND sm.id_categopresta=cp.id_categopresta AND sm.codemedecin=m.codemedecin ORDER BY u.nom_u');
					
					$resultatsMedecins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				
					while($ligneMedecins=$resultatsMedecins->fetch())//on recupere la liste des éléments
					{
					?>
						<option value="<?php echo $ligneMedecins->id_u;?>" <?php if(isset($_GET['id_uM'])){if($_GET['id_uM'] == $ligneMedecins->id_u){ echo 'selected="selected"';}}?>>
							<?php
							echo $ligneMedecins->nom_u.' '.$ligneMedecins->prenom_u;
							
							if($ligneMedecins->nomcategopresta == 'Consultations')
							{
								echo ' (Generalist)';
							}else{
								echo ' ('.$ligneMedecins->nomcategopresta.')';
							}
							?>
						</option>
					<?php
					}
					?>
					</select>
				</td>
				
			</tr>	   

			<tr>
				<td></td>
				
				<td style="padding:10px;" align="center">
					
					<table cellpadding=3>
						<tr>
							<td style="padding:0 20px; text-align:center"><label for="roomhosp"><?php echo 'N° Salle'; ?></label></td>
								
							<td style="padding:0 20px; text-align:center"><label for="lithosp"><?php echo 'N° du lit'; ?></label></td>
								
						</tr>	   

						<tr>	
							<td style="text-align:center">
								
									<input type="text" name="roomhosp" id="roomhosp" value="<?php echo $_GET['numroom'];?>" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:150px;" readonly="readonly"/>
									
									<input type="hidden" name="idhosp" id="idhosp" value="<?php echo $_GET['idhosp'];?>"/>
								
								
							</td>
								
							<td style="text-align:center">
								<input type="text" name="lithosp" id="lithosp" value="<?php echo $_GET['numlit'];?>" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:50px;" readonly="readonly"/>
								
							</td>
						</tr>
					</table>
				</td>				
			</tr>	   

		</table>
		
		<?php
		if(!isset($_SESSION['codeCash']))
		{
		?>
		
		<table align="center">
			<tr>
			<?php
			
			$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.numero=:num AND mc.id_factureMedConsu="" AND mc.id_hospMed=:idhosp ORDER BY mc.id_medconsu');		
			$resultMedConsult->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsult=$resultMedConsult->rowCount();



			$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.numero=:num AND mi.id_factureMedInf="" AND mi.id_hospInf=:idhosp ORDER BY mi.id_medinf');		
			$resultMedInf->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

			$comptMedInf=$resultMedInf->rowCount();



			$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.numero=:num AND ms.id_factureMedSurge="" AND ms.id_hospSurge=:idhosp ORDER BY ms.id_medsurge');		
			$resultMedSurge->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

			$comptMedSurge=$resultMedSurge->rowCount();



			$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.numero=:num AND ml.id_factureMedLabo="" AND ml.id_hospLabo=:idhosp ORDER BY ml.id_medlabo');		
			$resultMedLabo->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

			$comptMedLabo=$resultMedLabo->rowCount();

			

			$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.numero=:num AND mr.id_factureMedRadio="" AND mr.id_hospRadio=:idhosp ORDER BY mr.id_medradio');		
			$resultMedRadio->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

			$comptMedRadio=$resultMedRadio->rowCount();

			

			$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk WHERE mk.numero=:num AND mk.id_factureMedKine="" AND mk.id_hospKine=:idhosp ORDER BY mk.id_medkine');		
			$resultMedKine->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

			$comptMedKine=$resultMedKine->rowCount();

			
			
			$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.numero=:num AND mco.id_factureMedConsom="" AND mco.id_hospConsom=:idhosp ORDER BY mco.id_medconsom');		
			$resultMedConsom->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsom=$resultMedConsom->rowCount();

			
			
			$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedMedoc="" AND mdo.id_hospMedoc=:idhosp ORDER BY mdo.id_medmedoc');		
			$resultMedMedoc->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

			$comptMedMedoc=$resultMedMedoc->rowCount();

			
			
			$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo WHERE mo.numero=:num AND mo.id_factureMedOrtho="" AND mo.id_hospOrtho=:idhosp ORDER BY mo.id_medortho');		
			$resultMedOrtho->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

			$comptMedOrtho=$resultMedOrtho->rowCount();


			if($comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedSurge!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedKine!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedOrtho!=0)
			{
			?>
				
				<td>
				<?php
				if(!isset($_GET['previewprint']))
				{
					if(isset($_GET['id_uM'])!=0)
					{
						$id_uM=$_GET['id_uM'];
					}else{
						$id_uM=0;
					}
					
					if($comptidI!=0)
					{
						$infShow='&infShow=ok';
					}else{
						$infShow='';
					}
				?>
					<a style="padding:10px 40px;" href="<?php echo 'categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$id_uM.'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&previewprint=ok&today=ok'.$infShow.''?>" class="btn-large-inversed" name="showpreviewbtn" id="showpreviewbtn"><?php echo 'Edit Profile'; ?></a>
				<?php
				}else{
					if(isset($_GET['previewprint']))
					{
				?>
					<a style="padding:10px 40px;" href="<?php echo 'categoriesbill_hosp.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&back=ok'?>" class="btn-large-inversed" name="showbackbtn" id="showbackbtn"><?php echo getString(221) ?></a>
				<?php
					}
				}
				?>
					<a style="padding:10px 40px;" href="<?php echo 'formPatient_hosp.php?num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_SESSION['id'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&showmore=ok'?>" class="btn-large" name="showbackbtn" id="showbackbtn"><?php echo getString(100) ?></a>
				</td>
			<?php
			}
			?>
			</tr>
		</table>
		<?php
		}
		?>
		
		<?php
		if(!isset($_GET['previewprint']))
		{
		?>
		<div id="divCatego" style="margin:40px auto 0; text-align:center">

			<table align="center" style="display:inline;" id="catego">
				<tr>
					
					<?php

					$resultatsPrestaConsultation=$connexion->query('SELECT *FROM categopresta_ins cp, '.$presta_assu.' p  WHERE p.id_categopresta!=2 AND (cp.id_categopresta=4 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=4) OR (cp.id_categopresta=3 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=3) OR (cp.id_categopresta=12 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=12) OR (cp.id_categopresta=13 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=13) OR (cp.id_categopresta=14 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=14) OR (cp.id_categopresta=21 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=21) OR (cp.id_categopresta=22 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=22) OR (cp.id_categopresta=23 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=23) OR (cp.id_categopresta=24 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=24) GROUP BY cp.id_categopresta ORDER BY cp.nomcategopresta');

					$resultatsPrestaConsultation->setFetchMode(PDO::FETCH_OBJ);
					
					while($ligneCatPrestaConsultation=$resultatsPrestaConsultation->fetch())
					{
					?>									
						<td style="padding:5px;<?php if($ligneCatPrestaConsultation->id_categopresta!=23 AND $comptidO!=0){ echo "display:none;";}?>">
							<span id="<?php echo $ligneCatPrestaConsultation->id_categopresta;?>" class="btn-large" onclick="ShowMore('<?php echo $ligneCatPrestaConsultation->id_categopresta;?>')"><?php echo getCatego($ligneCatPrestaConsultation->id_categopresta);?></span>								
						</td>
					<?php
					}
					
					?>
					<td>
						<span style="<?php if($comptidO!=0){ echo "display:none;";}?>" id="services" class="btn-large" onclick="ShowMore('services')"><?php echo "Autres Services";?></span>
					</td>	
				</tr>
				
			</table>
			
			<table id="showService" align="center" style="margin:20px auto; display:none;width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1>Services</h1>
						
						<select style="margin:auto" multiple="multiple"name="checkprestaServ[]" id="checkprestaServ" class="chosen-select">
							<!--
							<option value='0'><?php echo getString(119) ?></option>
							-->
						<?php
	
						$resultatsCategoPrestaConsu=$connexion->query('SELECT *FROM categopresta_ins cp WHERE cp.id_categopresta=5 OR cp.id_categopresta=6 OR cp.id_categopresta=7 OR cp.id_categopresta=8 OR cp.id_categopresta=19 OR cp.id_categopresta=20 ORDER BY cp.nomcategopresta');

						$resultatsCategoPrestaConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptCategoMedConsu=$resultatsCategoPrestaConsu->rowCount();
						
						while($ligneCategoPrestaConsu=$resultatsCategoPrestaConsu->fetch())
						{
							echo '<optgroup label="'.$ligneCategoPrestaConsu->nomcategopresta.'">';
							
							$resultatsPrestaConsu=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND (cp.id_categopresta=5 OR cp.id_categopresta=6 OR cp.id_categopresta=7 OR cp.id_categopresta=8 OR cp.id_categopresta=19 OR cp.id_categopresta=20) AND p.id_categopresta='.$ligneCategoPrestaConsu->id_categopresta.' ORDER BY p.nompresta');

							$resultatsPrestaConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptMedConsu=$resultatsPrestaConsu->rowCount();
							
							while($lignePrestaConsu=$resultatsPrestaConsu->fetch())
							{
						?>
								<option value='<?php echo $lignePrestaConsu->id_prestation;?>'>
								<?php
								if($lignePrestaConsu->nompresta!="")
								{
									echo $lignePrestaConsu->nompresta;
								}else{
									if($lignePrestaConsu->namepresta!="")
									{
										echo $lignePrestaConsu->namepresta;
									}
								}
								?>
								
								</option>
						<?php
							}
						echo '</optgroup>';
						}
						?>							
							<!--
							<option value='autreconsult' id="autreconsult"> <?php echo getString(120); ?></option>
							-->
							
						</select>

					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouveau service</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprestaServ[]' id='autreprestaServ' style='width:150px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style='text-align:center;background:#ddd;'>	
									<td>Prix Unitaire</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaServ[]' id='autreprixprestaServ' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
								
								<tr style='text-align:center;background:#ddd;'>	
									<td>Quantité</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='qteprestaServ[]' id='qteprestaServ' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		
		</div>
		
		<?php
		}
		?>
		<div id="divViewServ" style="overflow:auto;height:600px;display:none;">
		
		
		</div>
	
		<div id="divViewInf" style="margin:40px auto 0; text-align:center;display:none;">
		
			<table id="inf" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1>Nursing care</h1>
					
					<select style="margin:auto;" multiple="multiple" name="checkprestaInf[]" class="chosen-select" id="checkprestaInf">						
						<!--
						<option value='0'><?php echo getString(121) ?></option>
						-->							
					<?php

					$resultatsPrestaSoins=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=3 ORDER BY p.nompresta ASC');
					
					$resultatsPrestaSoins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					if($ligneCatPrestaSoins=$resultatsPrestaSoins->fetch())
					{
						echo '<optgroup label="'.$ligneCatPrestaSoins->nomcategopresta.'">';

						echo '<option value='.$ligneCatPrestaSoins->id_prestation.' onclick="ShowOthersSoins(\'soins\')">'.$ligneCatPrestaSoins->nompresta.'</option>';							
						
						while($lignePrestaSoins=$resultatsPrestaSoins->fetch())//on recupere la liste des éléments
						{
					?>
							<option value='<?php echo $lignePrestaSoins->id_prestation;?>'><?php echo $lignePrestaSoins->nompresta;?></option>
					<?php
						}$resultatsPrestaSoins->closeCursor();
					
						echo '</optgroup>';
					}
					?>
						<!--
						<option value="autresoins" id="autresoins"><?php echo getString(122) ?></option>
						-->
						
					</select>
					
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouveau soins</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprestaInf[]' id='autreprestaInf' style='width:150px;'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Prix Unitaire</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaInf[]' id='autreprixprestaInf' style='width:70px;'/>
									</td>
									<?php
									}
									?>	
								</tr>
								
								<tr style="background:#ddd;">	
									<td>Quantité</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='qteprestaInf[]' id='qteprestaInf' style='width:70px;'/>
									</td>
									<?php
									}
									?>	
								</tr>
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
			
		</div>
		
		<div id="divViewSurge" style="<?php if(isset($_GET['surge'])){ echo 'display:inline;';}else{ echo 'display:none;';}?>">
		
			<table id="surge" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1><?php echo getString(274);?></h1>
					
						<select style="margin:auto" multiple="multiple" name="checkprestaSurge[]" class="chosen-select" id="checkprestaSurge">

						<!--
						<option value='0'><?php echo 'Selectionner le type de chirurgie...' ?></option>
						-->
						<?php
						
						$resultatsPrestaSurge=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=4 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaSurge->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneCatPrestaSurge=$resultatsPrestaSurge->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaSurge->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaSurge->id_prestation.' onclick="ShowSurge(\'surge\')">'.$ligneCatPrestaSurge->nompresta.'</option>';
							
							while($lignePrestaSurge=$resultatsPrestaSurge->fetch())
							{
						?>
								<option value='<?php echo $lignePrestaSurge->id_prestation;?>'><?php echo $lignePrestaSurge->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}
						?>
					
						<!--
						<option value="autresurge" id="autresurge"><?php echo 'Autre type chirurgie...'; ?></option>
						-->
							
						</select>
								
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouvelle Chirurgie</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='autreprestaSurge[]' id='autreprestaSurge' style='width:150px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Prix unitaire</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaSurge[]' id='autreprixprestaSurge' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Quantité</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='qteprestaSurge[]' id='qteprestaSurge' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
			
			<!--
			<table class="tablesorter tablesorter2" style='margin-top:20px; width:50%;background:#ddd;'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nom de la Chirurgie</td>
						<td>Prix Unitaire</td>
						<td>Quantité</td>
					</tr>
					
					<?php
					for($q=0;$q<1;$q++)
					{
					?>
					<tr style='text-align:center'>
						<td>
							<input type='text' name='autreprestaSurge[]' id='autreprestaSurge' value=''/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaSurge[]' id='autreprixprestaSurge' style='width:70px; text-align:center'/>
						</td>
						
						<td>
							<input type='text' name='qteprestaSurge[]' id='qteprestaSurge' value='1' style='width:70px; text-align:center'/>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			-->
			
		</div>
		
		<div id="divViewLab" style="margin:40px auto 0; display:none;">
			
			<table id="lab" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1>Laboratory</h1>
						
						<select style="margin:auto" multiple="multiple" name="checkprestaLab[]" class="chosen-select" id="checkprestaLab">

						<?php
						
						$resultatsPrestaExamen=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=12 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaExamen->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaExamen=$resultatsPrestaExamen->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaExamen->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaExamen->id_prestation.' onclick="ShowExam(\'exam\')">'.$ligneCatPrestaExamen->nompresta.'</option>';
							
							while($lignePrestaExamen=$resultatsPrestaExamen->fetch())//on recupere la liste des éléments
							{
						?>
								<option value='<?php echo $lignePrestaExamen->id_prestation;?>'><?php echo $lignePrestaExamen->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}
						?>
						
							<!--
							<option value="autreexamen" id="autreexamen"><?php echo getString(124) ?></option>
							-->
							
						</select>
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouveau examen</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprestaLab[]' id='autreprestaLab' style='width:150px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Prix Unitaire</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaLab[]' id='autreprixprestaLab' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
								
								<tr style="background:#ddd;">	
									<td>Quantité</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='qteprestaLab[]' id='qteprestaLab' style='width:70px;'/>
									</td>
									<?php
									}
									?>	
								</tr>
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
		</div>
		
		<div id="divViewRad" style="margin:40px auto 0; display:none;">
			
			<table id="rad" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1>Radiology</h1>
					
						<select style="margin:auto" multiple="multiple" name="checkprestaRad[]" class="chosen-select" id="checkprestaRad">

						<!--
						<option value='0'><?php echo 'Selectionner le type de radio...' ?></option>
						-->
						<?php
						
						$resultatsPrestaRadio=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=13 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaRadio=$resultatsPrestaRadio->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaRadio->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaRadio->id_prestation.' onclick="ShowRadio(\'radio\')">'.$ligneCatPrestaRadio->nompresta.'</option>';
							
							while($lignePrestaRadio=$resultatsPrestaRadio->fetch())
							{
						?>
								<option value='<?php echo $lignePrestaRadio->id_prestation;?>'><?php echo $lignePrestaRadio->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}
						?>
					
						<!--
						<option value="autreradio" id="autreradio"><?php echo 'Autre type radio...'; ?></option>
						-->
							
						</select>
								
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouvelle radio</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprestaRad[]' id='autreprestaRad' style='width:150px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Prix Unitaire</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaRad[]' id='autreprixprestaRad' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
								
								<tr style="background:#ddd;">	
									<td>Quantité</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='qteprestaRad[]' id='qteprestaRad' style='width:70px;'/>
									</td>
									<?php
									}
									?>	
								</tr>
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
			
		</div>
		
		<div id="divViewKine" style="margin:40px auto 0; display:none;">
			
			<table id="rad" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1><?php echo getString(272);?></h1>
					
						<select style="margin:auto" multiple="multiple" name="checkprestaKine[]" class="chosen-select" id="checkprestaKine">

						<!--
						<option value='0'><?php echo 'Selectionner le type de kiné...' ?></option>
						-->
						<?php
						
						$resultatsPrestaKine=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=14 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaKine->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaRadio=$resultatsPrestaKine->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaRadio->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaRadio->id_prestation.' onclick="ShowKine\'kine\')">'.$ligneCatPrestaRadio->nompresta.'</option>';
							
							while($lignePrestaKine=$resultatsPrestaKine->fetch())
							{
						?>
								<option value='<?php echo $lignePrestaKine->id_prestation;?>'><?php echo $lignePrestaKine->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}
						?>
					
						<!--
						<option value="autrekine" id="autrekine"><?php echo 'Autre type kiné...'; ?></option>
						-->
							
						</select>
								
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouvelle Kiné</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprestaKine[]' id='autreprestaKine' style='width:150px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Prix Unitaire</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaKine[]' id='autreprixprestaKine' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
								
								<tr style="background:#ddd;">	
									<td>Quantité</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='qteprestaKine[]' id='qteprestaKine' style='width:70px;'/>
									</td>
									<?php
									}
									?>	
								</tr>
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
			
		</div>

		<div id="divViewConsom" style="<?php if(isset($_GET['consom'])){ echo 'display:inline;';}else{ echo 'display:none;';}?>">
			
			<table id="consom" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1>Matériels</h1>
					
						<select style="margin:auto" multiple="multiple" name="checkprestaConsom[]" class="chosen-select" id="checkprestaConsom">

						<!--
						<option value='0'><?php echo 'Selectionner le type de matériel...' ?></option>
						-->
						<?php
						
						$resultatsPrestaConsom=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=21 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaConsom->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneCatPrestaConsom=$resultatsPrestaConsom->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaConsom->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaConsom->id_prestation.' onclick="ShowConsom(\'consom\')">'.$ligneCatPrestaConsom->nompresta.'</option>';
							
							while($lignePrestaConsom=$resultatsPrestaConsom->fetch())
							{
						?>
								<option value='<?php echo $lignePrestaConsom->id_prestation;?>'><?php echo $lignePrestaConsom->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}
						?>
					
						<!--
						<option value="autreconsom" id="autreconsom"><?php echo 'Autre type matériel...'; ?></option>
						-->
							
						</select>
								
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouveau Matériel</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='autreprestaConsom[]' id='autreprestaConsom' style='width:150px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Prix unitaire</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaConsom[]' id='autreprixprestaConsom' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Quantité</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='qteprestaConsom[]' id='qteprestaConsom' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
					
			<!--
			<table class="tablesorter tablesorter2" style='margin-top:20px; width:50%;background:#ddd;'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nom du consommable</td>
						<td>Prix Unitaire</td>
						<td>Quantité</td>
					</tr>
					
					<?php
					for($q=0;$q<1;$q++)
					{
					?>					
					<tr style='text-align:center'>
						<td>
							<input type='text' name='autreprestaConsom[]' id='autreprestaConsom' value=''/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaConsom[]' id='autreprixprestaConsom' style='width:70px; text-align:center'/>
						</td>
						
						<td>
							<input type='text' name='qteprestaConsom[]' id='qteprestaConsom' value='1' style='width:70px; text-align:center'/>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			-->
			
		</div>
		
		<div id="divViewMedoc" style="<?php if(isset($_GET['medoc'])){ echo 'display:inline;';}else{ echo 'display:none;';}?>">
		
			<table id="medoc" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1>Médicaments</h1>
					
						<select style="margin:auto" multiple="multiple" name="checkprestaMedoc[]" class="chosen-select" id="checkprestaMedoc">

						<!--
						<option value='0'><?php echo 'Selectionner le type de médicament...' ?></option>
						-->
						<?php
						
						$resultatsPrestaMedoc=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=22 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaMedoc->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneCatPrestaMedoc=$resultatsPrestaMedoc->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaMedoc->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaMedoc->id_prestation.' onclick="ShowMedoc(\'medoc\')">'.$ligneCatPrestaMedoc->nompresta.'</option>';
							
							while($lignePrestaMedoc=$resultatsPrestaMedoc->fetch())
							{
						?>
								<option value='<?php echo $lignePrestaMedoc->id_prestation;?>'><?php echo $lignePrestaMedoc->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}
						?>
					
						<!--
						<option value="autremedoc" id="autremedoc"><?php echo 'Autre type médicament...'; ?></option>
						-->
							
						</select>
								
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouveau Médicament</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='autreprestaMedoc[]' id='autreprestaMedoc' style='width:150px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Prix unitaire</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaMedoc[]' id='autreprixprestaMedoc' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Quantité</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='qteprestaMedoc[]' id='qteprestaMedoc' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
			
			<!--
			<table class="tablesorter tablesorter2" style='margin-top:20px; width:50%;background:#ddd;'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nom du medicament</td>
						<td>Prix Unitaire</td>
						<td>Quantité</td>
					</tr>
					
					<?php
					for($q=0;$q<1;$q++)
					{
					?>
					<tr style='text-align:center'>
						<td>
							<input type='text' name='autreprestaMedoc[]' id='autreprestaMedoc' value=''/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaMedoc[]' id='autreprixprestaMedoc' style='width:70px; text-align:center'/>
						</td>
						
						<td>
							<input type='text' name='qteprestaMedoc[]' id='qteprestaMedoc' value='1' style='width:70px; text-align:center'/>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			-->
			
		</div>
		
		<div id="divViewOrtho" style="<?php if(isset($_GET['ortho'])){ echo 'display:inline;';}else{ echo 'display:none;';}?>">
		
			<table id="ortho" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1><?php echo 'P&O';?></h1>
					
						<select style="margin:auto" multiple="multiple" name="checkprestaOrtho[]" class="chosen-select" id="checkprestaOrtho">

							<!--
							<option value='0'><?php echo 'Selectionner le type de matériel...' ?></option>
							-->
						<?php
						
						$resultatsPrestaOrtho=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=23 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaOrtho->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneCatPrestaOrtho=$resultatsPrestaOrtho->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaOrtho->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaOrtho->id_prestation.' onclick="ShowOrtho(\'ortho\')">'.$ligneCatPrestaOrtho->nompresta.'</option>';
							
							while($lignePrestaOrtho=$resultatsPrestaOrtho->fetch())
							{
						?>
								<option value='<?php echo $lignePrestaOrtho->id_prestation;?>'><?php echo $lignePrestaOrtho->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}
						?>
					
						<!--
						<option value="autreortho" id="autreortho"><?php echo 'Autre type médicament...'; ?></option>
						-->
							
						</select>
								
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouveau Matériel</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='autreprestaOrtho[]' id='autreprestaOrtho' style='width:150px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Prix unitaire</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaOrtho[]' id='autreprixprestaOrtho' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
				
								<tr style="background:#ddd;">	
									<td>Quantité</td>
									<?php
									for($r=0;$r<5;$r++)
									{
									?>	
									<td>
										<input type='text' name='qteprestaOrtho[]' id='qteprestaOrtho' style='width:70px'/>
									</td>
									<?php
									}
									?>	
								</tr>
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
			
			<!--
			<table class="tablesorter tablesorter2" style='margin-top:20px; width:50%;background:#ddd;'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nom de l'Orthopedie</td>
						<td>Prix Unitaire</td>
						<td>Quantité</td>
					</tr>
					
					<?php
					for($q=0;$q<1;$q++)
					{
					?>
					<tr style='text-align:center'>
						<td>
							<input type='text' name='autreprestaOrtho[]' id='autreprestaOrtho' value=''/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaOrtho[]' id='autreprixprestaOrtho' style='width:70px; text-align:center'/>
						</td>
						
						<td>
							<input type='text' name='qteprestaOrtho[]' id='qteprestaOrtho' value='1' style='width:70px; text-align:center'/>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			-->
			
		</div>
		
		
		<div style="margin-top:20px; text-align:center;">
			<button type="submit" class="btn-large" name="addbtn" id="addbtn" style="<?php if(isset($_GET['consom']) OR isset($_GET['medoc'])){ echo 'display:inline;';}else{ echo 'display:none;';}?>;">Ajouter</button>
		</div>
		
	</form>

	
	<?php
	if(isset($_GET['previewprint']))
	{
	?>
		<div id="previewprint">
				
		<?php
		
		/* if(!isset($_GET['today']))
		{ */
			$resultatHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.numero=:num AND ph.id_hosp=:idhosp AND ph.statusPaHosp=1 ORDER BY ph.id_hosp');		
			$resultatHosp->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultatHosp->setFetchMode(PDO::FETCH_OBJ);

			$comptHosp=$resultatHosp->rowCount();

			// echo $comptHosp;

			$ligneHosp=$resultatHosp->fetch();
			
			// $idhospFact = $ligneHosp->id_factureHosp;
			
			
			$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.numero=:num AND mc.id_factureMedConsu="" AND mc.id_hospMed=:idhosp ORDER BY mc.id_medconsu');		
			$resultMedConsult->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsult=$resultMedConsult->rowCount();



			$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.numero=:num AND mi.id_factureMedInf="" AND mi.id_hospInf=:idhosp ORDER BY mi.id_medinf');		
			$resultMedInf->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

			$comptMedInf=$resultMedInf->rowCount();

			

			$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.numero=:num AND ms.id_factureMedSurge="" AND ms.id_hospSurge=:idhosp ORDER BY ms.id_medsurge');		
			$resultMedSurge->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

			$comptMedSurge=$resultMedSurge->rowCount();



			$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.numero=:num AND ml.id_factureMedLabo="" AND ml.id_hospLabo=:idhosp ORDER BY ml.id_medlabo');		
			$resultMedLabo->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

			$comptMedLabo=$resultMedLabo->rowCount();



			$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.numero=:num AND mr.id_factureMedRadio="" AND mr.id_hospRadio=:idhosp ORDER BY mr.id_medradio');		
			$resultMedRadio->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

			$comptMedRadio=$resultMedRadio->rowCount();



			$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk WHERE mk.numero=:num AND mk.id_factureMedKine="" AND mk.id_hospKine=:idhosp ORDER BY mk.id_medkine');		
			$resultMedKine->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

			$comptMedKine=$resultMedKine->rowCount();



			$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.numero=:num AND mco.id_factureMedConsom="" AND mco.id_hospConsom=:idhosp ORDER BY mco.id_medconsom');		
			$resultMedConsom->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsom=$resultMedConsom->rowCount();



			$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedMedoc="" AND mdo.id_hospMedoc=:idhosp ORDER BY mdo.id_medmedoc');		
			$resultMedMedoc->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

			$comptMedMedoc=$resultMedMedoc->rowCount();



			$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo WHERE mo.numero=:num AND mo.id_factureMedOrtho="" AND mo.id_hospOrtho=:idhosp ORDER BY mo.id_medortho');		
			$resultMedOrtho->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

			$comptMedOrtho=$resultMedOrtho->rowCount();
		
		/* }elseif(isset($_GET['today'])){
		
			$resultatHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.numero=:num AND ph.id_hosp=:idhosp AND ph.statusPaHosp=1 AND ph.datehosp=:datehosp ORDER BY ph.id_hosp');		
			$resultatHosp->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultatHosp->setFetchMode(PDO::FETCH_OBJ);

			$comptHosp=$resultatHosp->rowCount();

			// echo $comptHosp;

			$ligneHosp=$resultatHosp->fetch();
			
			// $idhospFact = $ligneHosp->id_factureHosp;
			
			
			$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.numero=:num AND mc.id_factureMedConsu="" AND mc.id_hospMed=:idhosp AND mc.datehosp=:datehosp ORDER BY mc.id_medconsu');		
			$resultMedConsult->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsult=$resultMedConsult->rowCount();



			$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.numero=:num AND mi.id_factureMedInf="" AND mi.id_hospInf=:idhosp AND mi.datehosp=:datehosp ORDER BY mi.id_medinf');		
			$resultMedInf->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

			$comptMedInf=$resultMedInf->rowCount();

			

			$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.numero=:num AND ms.id_factureMedSurge="" AND ms.id_hospSurge=:idhosp AND ms.datehosp=:datehosp ORDER BY ms.id_medsurge');		
			$resultMedSurge->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

			$comptMedSurge=$resultMedSurge->rowCount();



			$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.numero=:num AND ml.id_factureMedLabo="" AND ml.id_hospLabo=:idhosp AND ml.datehosp=:datehosp ORDER BY ml.id_medlabo');		
			$resultMedLabo->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

			$comptMedLabo=$resultMedLabo->rowCount();



			$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.numero=:num AND mr.id_factureMedRadio="" AND mr.id_hospRadio=:idhosp AND mr.datehosp=:datehosp ORDER BY mr.id_medradio');		
			$resultMedRadio->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

			$comptMedRadio=$resultMedRadio->rowCount();



			$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk WHERE mk.numero=:num AND mk.id_factureMedKine="" AND mk.id_hospKine=:idhosp AND mk.datehosp=:datehosp ORDER BY mk.id_medkine');		
			$resultMedKine->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

			$comptMedKine=$resultMedKine->rowCount();



			$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.numero=:num AND mco.id_factureMedConsom="" AND mco.id_hospConsom=:idhosp AND mco.datehosp=:datehosp ORDER BY mco.id_medconsom');		
			$resultMedConsom->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsom=$resultMedConsom->rowCount();



			$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedMedoc="" AND mdo.id_hospMedoc=:idhosp AND mdo.datehosp=:datehosp ORDER BY mdo.id_medmedoc');		
			$resultMedMedoc->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

			$comptMedMedoc=$resultMedMedoc->rowCount();



			$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo WHERE mo.numero=:num AND mo.id_factureMedOrtho="" AND mo.id_hospOrtho=:idhosp AND mo.datehosp=:datehosp ORDER BY mo.id_medortho');		
			$resultMedOrtho->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp'],
			'datehosp'=>$annee
			));
			
			$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

			$comptMedOrtho=$resultMedOrtho->rowCount();
		
		} */
		
		
		
		
		if($comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedSurge!=0 OR $comptMedLabo!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedRadio!=0 OR $comptMedKine!=0 OR $comptMedOrtho!=0 OR isset($_GET['sortie']))
		{
		?>
			<form method="post" action="<?php if(isset($_GET['sortie'])){ echo 'printBill_hospReport.php?';}else{ if(isset($_GET['infShow']) OR $comptidO!=0){ echo 'newTest.php?';}else{ echo 'printBill_hosp.php?';}}?>inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idassu=<?php echo $_GET['idassu'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['id_consuHosp'])){ echo '&id_consuHosp='.$_GET['id_consuHosp'];}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" <?php if(!isset($_GET['infShow']) AND $comptidO==0){ echo 'onsubmit="return controlFormCategoBill(this)"';}?> enctype="multipart/form-data">
			
			<?php
			if(isset($_SESSION['codeCash']))
			{
				if(isset($_GET['sortie']))
				{

					$resultHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:hospId AND ph.numero=:num AND ph.dateEntree=:datehosp AND ph.numero=:num AND ph.id_assuHosp=:idassu ORDER BY ph.id_hosp');		
					$resultHosp->execute(array(
					'hospId'=>$_GET['idhosp'],
					'num'=>$_GET['num'],
					'idassu'=>$_GET['idassu'],
					'datehosp'=>$_GET['datehosp']
					));

					$resultHosp->setFetchMode(PDO::FETCH_OBJ);

					$comptHosp=$resultHosp->rowCount();
					


					if($comptHosp!=0)
					{
					?>
					<table class="tablesorter tablesorter1" cellspacing="0" style="margin:25px auto 25px;"> 
						<thead> 
							<tr>
								<th style="width:5%;text-align:center;">Room</th>
								<th style="width:10%;text-align:center;">Date In</th>
								<th style="width:20%;text-align:center;">Date Out</th>
								<th style="width:10%;text-align:center;">Price/day</th>
								<th style="width:10%;text-align:center;">Percent</th>
								
							</tr> 
						</thead>

						<tbody>
						<?php
						while($ligneHosp=$resultHosp->fetch())
						{
							
							$billpercent=$ligneHosp->insupercent_hosp;
							
							$idassu=$ligneHosp->id_assuHosp;						
							?>
							<tr style="font-weight:bold;">
								<td style="text-align:center;"><?php echo $ligneHosp->numroomPa;?></td>
								
								<?php						
															
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

							
							$resultPresta=$connexion->prepare('SELECT *FROM rooms r,'.$presta_assu.' p WHERE r.numroom=:numroomPa AND r.id_prestationHosp=p.id_prestation');

							$resultPresta->execute(array(
							'numroomPa'=>$ligneHosp->numroomPa
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($comptPresta!=0)
							{
								if($lignePresta=$resultPresta->fetch())
								{
									if(isset($_POST['pourcentage']))
									{
										$resultats=$connexion->prepare('UPDATE patients_hosp SET insupercent_hosp=:percent WHERE id_hosp=:idHosp');
							
										$resultats->execute(array(
										'percent'=>$_POST['pourcentage'],
										'idHosp'=>$_GET['idhosp']
										
										))or die( print_r($connexion->errorInfo()));
									}
									
									if($lignePresta->namepresta!='')
									{
										$nameprestaHosp=$lignePresta->namepresta;		
										// echo '<td style="text-align:center;">'.$lignePresta->namepresta.'</td>';
									}else{	
									
										if($lignePresta->nompresta!='')
										{
											$nameprestaHosp=$lignePresta->nompresta;
											// echo '<td style="text-align:center;">'.$lignePresta->nompresta.'</td>';
						
										}
									}
								
								if($ligneHosp->prixroom==0)
								{
									$prixPresta = $lignePresta->prixpresta;
								}else{
									$prixPresta = $ligneHosp->prixroom;
								}
									
									if($prixPresta==-1)
									{
										$prixPresta=0;
									}
								?>
									
									<td style="text-align:center;">
									<?php
										echo date('d-M-Y', strtotime($ligneHosp->dateEntree));
									?>
									
										<input size="25px" type="hidden" id="datein" name="datein" onclick="ds_sh(this);" value="<?php echo $ligneHosp->dateEntree;?>" style="width:150px"/>
									</td>
									
									<td style="text-align:center;">
									<?php
									if($ligneHosp->dateSortie>='0000-00-00')
									{
										// echo $annee;
									?>
										<select name="annee" id="annee" style="width:100px;" onchange="myScriptAnnee()">
											<?php
											for($a=2000;$a<=2020;$a++)
											{
											?>
												<option value="<?php echo $a;?>" <?php if(date('Y')==$a) echo 'selected="selected"';?>>
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
											?>
												<option value="<?php echo $m;?>" <?php if(date('F')==$moisString) echo 'selected="selected"';?>>
												<?php 
													echo $moisString;
												?>
												</option>
											<?php
											}
											?>
										</select>
										
										
										<select name="jours" id="jours" style="width:80px;">
											<option value=""></option>
										</select>
										
										<br/>
										<?php
										
										$heureSortie= date('H', strtotime($ligneHosp->heureSortie));
										
										$minuteSortie= date('i', strtotime($ligneHosp->heureSortie));
										
										
										?>
										Heure <input type="text" id="heureout" name="heureout" value="<?php if($heureSortie!=0) echo $heureSortie;?>" style="width:50px" required/>
										:<input type="text" id="minuteout" name="minuteout" value="<?php if($minuteSortie!=0) echo $minuteSortie;?>" style="width:50px" required/>
										
										<input size="25px" type="hidden" id="jourN" name="jourN" value="<?php echo date("d");?>"/>
						
									<?php
									}
									?>

									</td>
									
									<td style="text-align:center;">
									
										<input type="text" name="prixprestaHosp" style="width:100px;" id="prixprestaHosp" class="prixprestaHosp" value="<?php echo $prixPresta;?>" required/><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td style="text-align:center;">
									
										<input type="text" name="percentHosp" class="percentHosp" id="percentHosp" style="width:30px; text-align:center" value="<?php echo $billpercent;?>"/> %
							
										<input type="hidden" name="idHosp" class="idHosp"  id="idHosp"style="width:50px; text-align:center" value="<?php echo $ligneHosp->id_hosp;?>"/>
									</td>								
									
								</tr>
						<?php
								}
								
							}
							
						}
							
						?>
						</tbody>
					</table>
			<?php
					}
				
				}
			}

			if($comptMedSurge != 0)
			{
			?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; margin-top:10px;<?php if($comptidO!=0){ echo "display:none;";}?>" id="tableSurge"> 
					<thead> 
						<tr>
							<th style="width:35%">Date</th>
							<th style="width:25%"><?php echo getString(275);?></th>
							<th style="display:none;"><?php echo getString(145);?></th>
							<th style="width:25%"><?php echo getString(215);?></th>
							<th style="width:15%;display:none;"><?php echo getString(38);?></th>
							<th style="width:auto"><?php echo getString(21);?></th>
							<th style="width:auto"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
					<?php
					$l=0;
					
					while($ligneMedSurge=$resultMedSurge->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td>
								<select name="anneeMedSurge[]" id="anneeMedSurge" style="width:80px;" onchange="ShowSaveSurge(<?php echo $l;?>)">
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
								
								<select name="moisMedSurge[]" id="moisMedSurge" style="width:120px;" onchange="ShowSaveSurge(<?php echo $l;?>)">
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
								
								
								<select name="joursMedSurge[]" id="joursMedSurge" style="width:70px;" onchange="ShowSaveSurge(<?php echo $l;?>)">
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
						
							<td>
								<?php
								
								$idassuSurge=$ligneMedSurge->id_assuSurge;
																	
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuSurge
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuSurge='prestations_'.$ligneNomAssu->nomassurance;
								}
							}
															
								
								$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assuSurge.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=4 AND p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneMedSurge->id_prestationSurge
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($comptPresta==0)
								{
									$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=4 AND p.id_prestation=:prestaId');
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedSurge->id_prestationSurge
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
								}
												
								if($lignePresta=$resultPresta->fetch())
								{
									echo '<input type="text" name="idprestaSurge[]" style="width:100px;display:none; text-align:center" id="idprestaSurge" value="'.$lignePresta->id_prestation.'"/>';
				
									echo '<input type="text" name="autreSurge[]" style="width:100px;display:none; text-align:center" id="autreSurge" value=""/>';
									
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td style="display:none;">					
								<input type="text" name="prixprestaSurge[]" style="width:100px; text-align:center" id="prixprestaSurge" class="prixprestaSurge" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveSurge(<?php echo $l;?>)"/>
								
							</td>
								<?php
								}else{
									echo $lignePresta->nompresta;
									?>
							</td>
							
							<td style="display:none;">					
								<input type="text" name="prixprestaSurge[]" style="width:100px; text-align:center" id="prixprestaSurge" class="prixprestaSurge" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveSurge(<?php echo $l;?>)"/>
								
							</td>
								<?php
								}
								
							}
							
							if($ligneMedSurge->id_prestationSurge==0 AND $ligneMedSurge->prixautreSurge==0)
							{
								echo '<input type="text" name="autreSurge[]" style="width:100px;display:none; text-align:center" id="autreSurge" value="'.$ligneMedSurge->autreSurge.'"/>';

								echo $ligneMedSurge->autreSurge.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td style="display:none;">
										<input type="text" name="prixprestaSurge[]" style="width:100px; text-align:center" id="prixprestaSurge" class="prixprestaSurge" value="" placeholder="Tarrif ici..." onchange="ShowSaveSurge('.$l.')"/>
										
										<input type="text" name="idprestaSurge[]" style="width:100px;display:none; text-align:center" id="idprestaSurge" value="0"/>
									</td>';

							}else{

								if($ligneMedSurge->id_prestationSurge==0 AND $ligneMedSurge->prixautreSurge!=0)
								{
									echo $ligneMedSurge->autreSurge.'<input type="text" name="idprestaSurge[]" style="width:100px;display:none;" id="idprestaSurge" value="0"/>
									
									</td>';
									
									echo '<input type="text" name="autreSurge[]" style="width:100px;display:none;" id="autreSurge" value="'.$ligneMedSurge->autreSurge.'"/>';	
									
									echo '<td style="display:none;">
									<input type="text" name="prixprestaSurge[]" style="width:100px;" id="prixprestaSurge" class="prixprestaSurge" value="'.$ligneMedSurge->prixautreSurge.'" onchange="ShowSaveSurge('.$l.')"/>';
								}
								
							}
							?>
							</td>
							
							<td>						
								<span type="submit" id="qteSurgeMoins<?php echo $l;?>" name="qteSurgeMoins<?php echo $l;?>" class="qteSurgeMoins btn" style="display:<?php if($ligneMedSurge->qteSurge ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteSurge(<?php echo $l;?>)"/>-</span>	
								<input type="text" id="quantitySurge<?php echo $l;?>" name="quantitySurge[]" class="quantitySurge" style="width:30px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedSurge->qteSurge;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteSurge<?php echo $l;?>" name="qteSurge[]" class="qteSurge" style="width:50px;margin-left:0px;" value="<?php echo $l;?>"/>
							
								<span type="submit" id="qteSurgePlus<?php echo $l;?>" name="qteSurgePlus<?php echo $l;?>" class="qteSurgePlus btn" onclick="PlusQteSurge(<?php echo $l;?>)"/>+</span>
							
							</td>
							
							<td style="display:none;">
								<input type="text" id="percentSurge" name="percentSurge[]" class="percentSurge" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								
								<input type="text" id="idmedSurge" name="idmedSurge[]" style="width:30px;display:none; text-align:center" value="<?php echo $ligneMedSurge->id_medsurge;?>"/>
							</td>
							
							<td>
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedSurge->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedSurge->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td>
								<a href="categoriesbill_hosp.php?deleteMedSurge=<?php echo $ligneMedSurge->id_medsurge;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td colspan=5 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteSurgeBtn<?php echo $ligneMedSurge->id_medsurge;?>" id="addQteSurgeBtn<?php echo $l;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$l++;
					}
					?>		
					</tbody>
				</table>
			<?php
			}
			
			if($comptMedInf != 0)
			{
			?>		
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; margin-top:10px;<?php if($comptidO!=0){ echo "display:none;";}?>" id="tableInf"> 
					<thead> 
						<tr>							
							<th style="width:35%">Date</th>
							<th style="width:25%"><?php echo getString(98);?></th>
							<th style="display:none;"><?php echo getString(145);?></th>
							<th style="width:25%"><?php echo getString(215);?></th>
							<th style="width:15%;display:none;"><?php echo getString(38);?></th>
							<th style="width:auto"><?php echo getString(21);?></th>
							<th style="width:auto"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
					<?php
					$f=0;
					
					while($ligneMedInf=$resultMedInf->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td>
								<select name="anneeMedInf[]" id="anneeMedInf" style="width:80px;" onchange="ShowSaveInf(<?php echo $f;?>)">
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
								
								<select name="moisMedInf[]" id="moisMedInf" style="width:120px;" onchange="ShowSaveInf(<?php echo $f;?>)">
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
								
								
								<select name="joursMedInf[]" id="joursMedInf" style="width:70px;" onchange="ShowSaveInf(<?php echo $f;?>)">
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
						
							<td>
							<?php
							
							$idassuInf=$ligneMedInf->id_assuInf;							
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuInf
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuInf='prestations_'.$ligneNomAssu->nomassurance;
								}
							}


							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedInf->id_prestation
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								echo '<input type="text" name="idprestaInf[]" style="width:100px;display:none; text-align:center" id="idprestaInf" value="'.$lignePresta->id_prestation.'"/>';
							
								echo '<input type="text" name="autreInf[]" style="width:100px;display:none; text-align:center" id="autreInf" value=""/>';
									
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td style="display:none;">					
								<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveInf(<?php echo $f;?>)"/>
								
							</td>
								<?php
								}else{								
									echo $lignePresta->nompresta;	
								?>
							</td>
							
							<td style="display:none;">					
								<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveInf(<?php echo $f;?>)"/>
								
							</td>
								<?php
								}
							}
							
							if($ligneMedInf->id_prestation==NULL AND $ligneMedInf->prixautrePrestaM==0)
							{
								echo '<input type="text" name="autreInf[]" style="width:100px;display:none; text-align:center" id="autreInf" value="0"/>';
									
								echo $ligneMedInf->autrePrestaM.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="" placeholder="Tarrif ici..." onchange="ShowSaveInf('.$f.')"/>
										
										<input type="text" name="'.$ligneMedInf->id_medinf.'" style="width:100px;display:none; text-align:center" id="id_medinf" value="'.$ligneMedInf->id_medinf.'"/>
										
										<input type="hidden" name="idprestaInf[]" style="width:100px; text-align:center" id="idprestaInf" value="0"/>
									</td>';
							}else{
							
								if($ligneMedInf->id_prestation==NULL AND $ligneMedInf->prixautrePrestaM!=0)
								{
									echo $ligneMedInf->autrePrestaM.'
<input type="hidden" name="idprestaInf[]" style="width:100px;display:none;" id="idprestaInf" value="0"/>
									</td>';
									
									echo '<input type="text" name="autreInf[]" style="width:100px;display:none; text-align:center" id="autreInf" value="'.$ligneMedInf->autrePrestaM.'"/>';
									
									echo '<td style="display:none;">
									<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="'.$ligneMedInf->prixautrePrestaM.'" onchange="ShowSaveInf('.$f.')"/>
										
									</td>';
								}
							}
							?>
							</td>
							
							<td>
								<span type="submit" id="qteInfMoins<?php echo $f;?>" name="qteInfMoins<?php echo $f;?>" class="qteInfMoins btn" style="display:<?php if($ligneMedInf->qteInf ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteInf(<?php echo $f;?>)"/>-</span>
								
								<input type="text" id="quantityInf<?php echo $f;?>" name="quantityInf[]" class="quantityInf" style="width:30px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedInf->qteInf;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteInf<?php echo $f;?>" name="qteInf[]" class="qteInf" style="width:50px;margin-left:0px;" value="<?php echo $f;?>"/>
							
								<span type="submit" id="qteInfPlus<?php echo $f;?>" name="qteInfPlus<?php echo $f;?>" class="qteInfPlus btn" onclick="PlusQteInf(<?php echo $f;?>)"/>+</span>

							</td>
							
							<td style="display:none;">
								<input type="text" id="percentInf" name="percentInf[]" class="percentInf" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								<input type="hidden" id="idmedInf" name="idmedInf[]" style="width:50px; text-align:center" value="<?php echo $ligneMedInf->id_medinf;?>"/>
							
							</td>
							
							<td>
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedInf->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedInf->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td>
								<a href="categoriesbill_hosp.php?deleteMedInf=<?php echo $ligneMedInf->id_medinf;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td colspan=5 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteInfBtn<?php echo $ligneMedInf->id_medinf;?>" id="addQteInfBtn<?php echo $f;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							
							</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$f++;
					}
					?>		
					</tbody>
				</table>
			<?php
			}
			
			if($comptMedLabo != 0)
			{
			?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; margin-top:10px;<?php if($comptidO!=0){ echo "display:none;";}?>" id="tableLab"> 
					<thead> 
						<tr>
							<th style="width:35%">Date</th>
							<th style="width:25%"><?php echo getString(99);?></th>
							<th style="display:none;"><?php echo getString(145);?></th>
							<th style="width:25%"><?php echo getString(215);?></th>
							<th style="width:15%;display:none;"><?php echo getString(38);?></th>
							<th style="width:auto"><?php echo getString(21);?></th>
							<th style="width:auto"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
					<?php
					$g=0;
					
					while($ligneMedLabo=$resultMedLabo->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td>
								<select name="anneeMedLabo[]" id="anneeMedLabo" style="width:80px;" onchange="ShowSaveLab(<?php echo $g;?>)">
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
								
								<select name="moisMedLabo[]" id="moisMedLabo" style="width:120px;" onchange="ShowSaveLab(<?php echo $g;?>)">
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
								
								
								<select name="joursMedLabo[]" id="joursMedLabo" style="width:70px;" onchange="ShowSaveLab(<?php echo $g;?>)">
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
						
							<td>
							<?php
							
							$idassuLab=$ligneMedLabo->id_assuLab;
				
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuLab
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
								}
							}
		
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedLabo->id_prestationExa
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								echo '<input type="text" name="idprestaLab[]" style="width:100px;display:none; text-align:center" id="idprestaLab" value="'.$lignePresta->id_prestation.'"/>';
								
								echo '<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab" value=""/>';
								
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td style="display:none;">								
								<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }?>" onchange="ShowSaveLab(<?php echo $g;?>)"/>
								
							</td>
								<?php
								
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td style="display:none;">								
								<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }?>" onchange="ShowSaveLab(<?php echo $g;?>)"/>
								
							</td>
								<?php
								}
							}
							
							if($ligneMedLabo->id_prestationExa==NULL AND $ligneMedLabo->prixautreExamen==0)
							{
								echo '<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab" value="'.$ligneMedLabo->autreExamen.'"/>';
								
								echo $ligneMedLabo->autreExamen.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td style="display:none;">
										<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="" placeholder="Tarrif ici..." onchange="ShowSaveLab('.$g.')"/>
										
										<input type="text" name="'.$ligneMedLabo->id_medlabo.'" style="width:100px;display:none; text-align:center" id="id_medlabo" value="'.$ligneMedLabo->id_medlabo.'"/>
										
										<input type="hidden" name="idprestaLab[]" style="width:100px; text-align:center" id="idprestaLab" value="0"/>
									</td>';
							}else{

								if($ligneMedLabo->id_prestationExa==NULL AND $ligneMedLabo->prixautreExamen!=0)
								{
									echo $ligneMedLabo->autreExamen.'<input type="hidden" name="idprestaLab[]" style="width:100px;display:none; text-align:center" id="idprestaLab" value="0"/>
									</td>';
									echo '<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab" value="'.$ligneMedLabo->autreExamen.'"/>';
								
									echo '<td style="display:none;">
									<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="'.$ligneMedLabo->prixautreExamen.'" onchange="ShowSaveLab('.$g.')"/>';
								}
							}
							?>
							</td>
							
							<td>
								<span type="submit" id="qteLabMoins<?php echo $g;?>" name="qteLabMoins<?php echo $g;?>" class="qteLabMoins btn" style="display:<?php if($ligneMedLabo->qteLab ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteLab(<?php echo $g;?>)"/>-</span>
								
								<input type="text" id="quantityLab<?php echo $g;?>" name="quantityLab[]" class="quantityLab" style="width:30px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedLabo->qteLab;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteLab<?php echo $g;?>" name="qteLab[]" class="qteLab" style="width:50px;margin-left:0px;" value="<?php echo $g;?>"/>
							
								<span type="submit" id="qteLabPlus<?php echo $g;?>" name="qteLabPlus<?php echo $g;?>" class="qteLabPlus btn" onclick="PlusQteLab(<?php echo $g;?>)"/>+</span>

							</td>
							
							<td style="display:none;">
								<input type="text" id="percentLab" name="percentLab[]" class="percentLab" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								<input type="hidden" id="idmedLab" name="idmedLab[]" style="width:50px; text-align:center" value="<?php echo $ligneMedLabo->id_medlabo;?>"/>
							</td>
							
							<td>
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedLabo->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedLabo->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td>
								<a href="categoriesbill_hosp.php?deleteMedLabo=<?php echo $ligneMedLabo->id_medlabo;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
							
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td colspan=5 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteLabBtn<?php echo $ligneMedLabo->id_medlabo;?>" id="addQteLabBtn<?php echo $g;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							
							</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$g++;
					}
					?>		
					</tbody>
				</table>
			<?php
			}
				
			if($comptMedRadio != 0)
			{
			?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; margin-top:10px;<?php if($comptidO!=0){ echo "display:none;";}?>" id="tableRad"> 
					<thead> 
						<tr>
							<th style="width:35%">Date</th>
							<th style="width:25%"><?php echo 'Radiology test';?></th>
							<th style="display:none;"><?php echo getString(145);?></th>
							<th style="width:25%"><?php echo getString(215);?></th>
							<th style="width:15%;display:none;"><?php echo getString(38);?></th>
							<th style="width:auto"><?php echo getString(21);?></th>
							<th style="width:auto"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
					<?php
					$h=0;
					
					while($ligneMedRadio=$resultMedRadio->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td>
								<select name="anneeMedRadio[]" id="anneeMedRadio" style="width:80px;" onchange="ShowSaveRad(<?php echo $h;?>)">
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
								
								<select name="moisMedRadio[]" id="moisMedRadio" style="width:120px;" onchange="ShowSaveRad(<?php echo $h;?>)">
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
								
								
								<select name="joursMedRadio[]" id="joursMedRadio" style="width:70px;" onchange="ShowSaveRad(<?php echo $h;?>)">
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
						
							<td>
							<?php
							
							$idassuRad=$ligneMedRadio->id_assuRad;
				
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuRad
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuRad='prestations_'.$ligneNomAssu->nomassurance;
								}
							}
	
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedRadio->id_prestationRadio
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								echo '<input type="text" name="idprestaRad[]" style="width:100px;display:none; text-align:center" id="idprestaRad" value="'.$lignePresta->id_prestation.'"/>';
								
								echo '<input type="text" name="autreRad[]" style="width:100px;display:none; text-align:center" id="autreRad" value=""/>';
								
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td style="display:none;">								
								<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }?>" onchange="ShowSaveRad(<?php echo $h;?>)"/>
								
							</td>
								<?php
								
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td style="display:none;">								
								<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }?>" onchange="ShowSaveRad(<?php echo $h;?>)"/>
								
							</td>
								<?php
								}
							}
							
							if($ligneMedRadio->id_prestationRadio==NULL AND $ligneMedRadio->prixautreRadio==0)
							{
								echo '<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab" value="'.$ligneMedRadio->autreRadio.'"/>';
								
								echo $ligneMedRadio->autreRadio.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td style="display:none;">
										<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="" placeholder="Tarrif ici..." onchange="ShowSaveRad('.$h.')"/>
										
										<input type="text" name="'.$ligneMedRadio->id_medradio.'" style="width:100px;display:none; text-align:center" id="id_medradio" value="'.$ligneMedRadio->id_medradio.'"/>
										
										<input type="hidden" name="idprestaRad[]" style="width:100px; text-align:center" id="idprestaRad" value="0"/>
									</td>';
							}else{

								if($ligneMedRadio->id_prestationRadio==NULL AND $ligneMedRadio->prixautreRadio!=0)
								{
									echo $ligneMedRadio->autreRadio.'<input type="hidden" name="idprestaRad[]" style="width:100px;display:none; text-align:center" id="idprestaRad" value="0"/>
									</td>';
									echo '<input type="text" name="autreRad[]" style="width:100px;display:none; text-align:center" id="autreRad" value="'.$ligneMedRadio->autreRadio.'"/>';
								
									echo '<td style="display:none;">
									<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="'.$ligneMedRadio->prixautreRadio.'" onchange="ShowSaveRad('.$h.')"/>';
								}
							}
							?>
							</td>
							
							<td>
								<span type="submit" id="qteRadMoins<?php echo $h;?>" name="qteRadMoins<?php echo $h;?>" class="qteRadMoins btn" style="display:<?php if($ligneMedRadio->qteRad ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteRad(<?php echo $h;?>)"/>-</span>
								
								<input type="text" id="quantityRad<?php echo $h;?>" name="quantityRad[]" class="quantityRad" style="width:30px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedRadio->qteRad;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteRad<?php echo $h;?>" name="qteRad[]" class="qteRad" style="width:50px;margin-left:0px;" value="<?php echo $h;?>"/>
							
								<span type="submit" id="qteRadPlus<?php echo $h;?>" name="qteRadPlus<?php echo $h;?>" class="qteRadPlus btn" onclick="PlusQteRad(<?php echo $h;?>)"/>+</span>

							</td>
							
							<td style="display:none;">
								<input type="text" id="percentRad" name="percentRad[]" class="percentRad" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								<input type="hidden" id="idmedRad" name="idmedRad[]" style="width:50px; text-align:center" value="<?php echo $ligneMedRadio->id_medradio;?>"/>
							</td>
							
							<td>
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedRadio->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedRadio->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td>
								<a href="categoriesbill_hosp.php?deleteMedRadio=<?php echo $ligneMedRadio->id_medradio;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>							
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td colspan=5 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteRadBtn<?php echo $ligneMedRadio->id_medradio;?>" id="addQteRadBtn<?php echo $h;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							
							</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$h++;
					}
					?>		
					</tbody>
				</table>
			<?php
			}
				
			if($comptMedConsom != 0)
			{
			?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; margin-top:10px;<?php if($comptidO!=0){ echo "display:none;";}?>" id="tableConsom"> 
					<thead> 
						<tr>
							<th style="width:35%">Date</th>
							<th style="width:25%"><?php echo getString(214);?></th>
							<th style="display:none;"><?php echo getString(145);?></th>
							<th style="width:25%"><?php echo getString(215);?></th>
							<th style="width:15%;display:none;"><?php echo getString(38);?></th>
							<th style="width:auto"><?php echo getString(21);?></th>
							<th style="width:auto"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
					<?php
					$j=0;
					
					while($ligneMedConsom=$resultMedConsom->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td>
								<select name="anneeMedConsom[]" id="anneeMedConsom" style="width:80px;" onchange="ShowSaveConsom(<?php echo $j;?>)">
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
								
								<select name="moisMedConsom[]" id="moisMedConsom" style="width:120px;" onchange="ShowSaveConsom(<?php echo $j;?>)">
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
								
								<select name="joursMedConsom[]" id="joursMedConsom" style="width:70px;" onchange="ShowSaveConsom(<?php echo $j;?>)">
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
						
							<td>
							<?php
							
							$idassuConsom=$ligneMedConsom->id_assuConsom;
							
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuConsom
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuConsom='prestations_'.$ligneNomAssu->nomassurance;
								}
							}

							
							
							$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assuConsom.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedConsom->id_prestationConsom
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

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
								echo '<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom" value="'.$lignePresta->id_prestation.'"/>';
			
								echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom" value=""/>';
			
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td style="display:none;">								
								<input type="text" name="prixprestaConsom[]" style="width:100px;" id="prixprestaConsom"  class="prixprestaConsom"  value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo 0;}?>" onchange="ShowSaveConsom(<?php echo $j;?>)"/>
								
							</td>
								<?php
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td style="display:none;">								
								<input type="text" name="prixprestaConsom[]" style="width:100px; text-align:center" id="prixprestaConsom"  class="prixprestaConsom"  value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo 0;}?>" onchange="ShowSaveConsom(<?php echo $j;?>)"/>
							</td>
								<?php
								}
								
							}
							
							if($ligneMedConsom->id_prestationConsom==NULL AND $ligneMedConsom->prixautreConsom==0)
							{
								echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom" value="'.$ligneMedConsom->autreConsom.'"/>';

								echo $ligneMedConsom->autreConsom.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/>
								</td>
								<td style="display:none;">
									<input type="text" name="prixprestaConsom[]" style="width:100px; text-align:center" id="prixprestaConsom" class="prixprestaConsom"  value="" placeholder="Tarrif ici..." onchange="ShowSaveConsom('.$j.')"/>
									
									<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom" value="0"/>
								</td>';
							}else{

								if($ligneMedConsom->id_prestationConsom==NULL AND $ligneMedConsom->prixautreConsom!=0)
								{
									echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom" value="'.$ligneMedConsom->autreConsom.'"/>';
									
									echo $ligneMedConsom->autreConsom.'<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom" value="0"/>
									</td>';
									echo '
									<td style="display:none;">
										<input type="text" name="prixprestaConsom[]" style="width:100px; text-align:center" id="prixprestaConsom"  class="prixprestaConsom"  value="'.$ligneMedConsom->prixautreConsom.'" onchange="ShowSaveConsom('.$j.')"/>
										
									</td>';
								}
								
							}
							?>
							
							<td>
								<span type="submit" id="qteConsomMoins<?php echo $j;?>" name="qteConsomMoins<?php echo $j;?>" class="qteConsomMoins btn" style="display:<?php if($ligneMedConsom->qteConsom ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteConsom(<?php echo $j;?>)"/>-</span>
								
								<input type="text" id="quantityConsom<?php echo $j;?>" name="quantityConsom[]" class="quantityConsom" style="width:30px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedConsom->qteConsom;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteConsom<?php echo $j;?>" name="qteConsom[]" class="qteConsom" style="width:50px;margin-left:0px;" value="<?php echo $j;?>"/>
							
								<span type="submit" id="qteConsomPlus<?php echo $j;?>" name="qteConsomPlus<?php echo $j;?>" class="qteConsomPlus btn" onclick="PlusQteConsom(<?php echo $j;?>)"/>+</span>

							</td>
							
							<td style="display:none;">
								<input type="text" id="percentConsom" name="percentConsom[]" class="percentConsom" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								<input type="text" id="idmedConsom" name="idmedConsom[]" style="width:30px;display:none; text-align:center" value="<?php echo $ligneMedConsom->id_medconsom;?>"/>
							</td>
							
							<td>
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedConsom->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedConsom->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td>
								<a href="categoriesbill_hosp.php?deleteMedConsom=<?php echo $ligneMedConsom->id_medconsom;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td colspan=5 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteConsomBtn<?php echo $ligneMedConsom->id_medconsom;?>" id="addQteConsomBtn<?php echo $j;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							
							</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$j++;
					}
					?>							
					</tbody>
				</table>
			<?php
			}

			if($comptMedMedoc != 0)
			{
			?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; margin-top:10px;<?php if($comptidO!=0){ echo "display:none;";}?>" id="tableMedoc"> 
					<thead> 
						<tr>
							<th style="width:35%">Date</th>
							<th style="width:25%"><?php echo getString(216);?></th>
							<th style="display:none;"><?php echo getString(145);?></th>
							<th style="width:25%"><?php echo getString(215);?></th>
							<th style="width:15%;display:none;"><?php echo getString(38);?></th>
							<th style="width:auto"><?php echo getString(21);?></th>
							<th style="width:auto"><?php echo 'Action';?></th>
						</tr>
					</thead> 


					<tbody>
					<?php
					$k=0;
					
					while($ligneMedMedoc=$resultMedMedoc->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td>
								<select name="anneeMedMedoc[]" id="anneeMedMedoc" style="width:80px;" onchange="ShowSaveMedoc(<?php echo $k;?>)">
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
								
								<select name="moisMedMedoc[]" id="moisMedMedoc" style="width:120px;" onchange="ShowSaveMedoc(<?php echo $k;?>)">
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
								
								<select name="joursMedMedoc[]" id="joursMedMedoc" style="width:70px;" onchange="ShowSaveMedoc(<?php echo $k;?>)">
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
						
							<td>
								<?php
								
								$idassuMedoc=$ligneMedMedoc->id_assuMedoc;
																	
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuMedoc
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuMedoc='prestations_'.$ligneNomAssu->nomassurance;
								}
							}
															
								
							$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assuMedoc.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedMedoc->id_prestationMedoc
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

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
								echo '<input type="text" name="idprestaMedoc[]" style="width:100px;display:none; text-align:center" id="idprestaMedoc" value="'.$lignePresta->id_prestation.'"/>';
			
								echo '<input type="text" name="autreMedoc[]" style="width:100px;display:none; text-align:center" id="autreMedoc" value=""/>';
								if($lignePresta->namepresta!='')
								{
										echo $lignePresta->namepresta;
								?>
							</td>
							
							<td style="display:none;">								
								<input type="text" name="prixprestaMedoc[]" style="width:100px; text-align:center;" id="prixprestaMedoc" class="prixprestaMedoc" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveMedoc(<?php echo $k;?>)"/>
								
							</td>
								<?php
								}else{
									echo $lignePresta->nompresta;
									?>
							</td>
							
							<td style="display:none;">
								
								<input type="text" name="prixprestaMedoc[]" style="width:100px; text-align:center;" id="prixprestaMedoc" class="prixprestaMedoc" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveMedoc(<?php echo $k;?>)"/>
								
							</td>
								<?php
								}
								
							}
							
							if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc==0)
							{
								echo '<input type="text" name="autreMedoc[]" style="width:100px;display:none; text-align:center" id="autreMedoc" value="'.$ligneMedMedoc->autreMedoc.'"/>';

								echo $ligneMedMedoc->autreMedoc.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td style="display:none;">
										<input type="text" name="prixprestaMedoc[]" style="width:100px; text-align:center;" id="prixprestaMedoc" class="prixprestaMedoc" value="" placeholder="Tarrif ici..." onchange="ShowSaveMedoc('.$k.')"/>
										
										<input type="text" name="idprestaMedoc[]" style="width:100px;display:none; text-align:center" id="idprestaMedoc" value="0"/>
									</td>';

							}else{

								if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc!=0)
								{
									echo $ligneMedMedoc->autreMedoc.'<input type="text" name="idprestaMedoc[]" style="width:100px;display:none;" id="idprestaMedoc" value="0"/>
									
									</td>';
									
									echo '<input type="text" name="autreMedoc[]" style="width:100px;display:none;" id="autreMedoc" value="'.$ligneMedMedoc->autreMedoc.'"/>';	
									
									echo '<td style="display:none;">
									<input type="text" name="prixprestaMedoc[]" style="width:100px; text-align:center;" id="prixprestaMedoc" class="prixprestaMedoc" value="'.$ligneMedMedoc->prixautreMedoc.'" onchange="ShowSaveMedoc('.$k.')"/>';
								}
								
							}
							?>
							</td>
							
							<td>						
								<span type="submit" id="qteMedocMoins<?php echo $k;?>" name="qteMedocMoins<?php echo $k;?>" class="qteMedocMoins btn" style="display:<?php if($ligneMedMedoc->qteMedoc ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteMedoc(<?php echo $k;?>)"/>-</span>	
								<input type="text" id="quantityMedoc<?php echo $k;?>" name="quantityMedoc[]" class="quantityMedoc" style="width:30px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedMedoc->qteMedoc;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteMedoc<?php echo $k;?>" name="qteMedoc[]" class="qteMedoc" style="width:50px;margin-left:0px;" value="<?php echo $k;?>"/>
							
								<span type="submit" id="qteMedocPlus<?php echo $k;?>" name="qteMedocPlus<?php echo $k;?>" class="qteMedocPlus btn" onclick="PlusQteMedoc(<?php echo $k;?>)"/>+</span>
							
							</td>
							
							<td style="display:none;">
								<input type="text" id="percentMedoc" name="percentMedoc[]" class="percentMedoc" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								
								<input type="text" id="idmedMedoc" name="idmedMedoc[]" style="width:30px;display:none; text-align:center" value="<?php echo $ligneMedMedoc->id_medmedoc;?>"/>
							</td>
							
							<td>
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedMedoc->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedMedoc->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td>
								<a href="categoriesbill_hosp.php?deleteMedMedoc=<?php echo $ligneMedMedoc->id_medmedoc;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td colspan=5 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteMedocBtn<?php echo $ligneMedMedoc->id_medmedoc;?>" id="addQteMedocBtn<?php echo $k;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$k++;
					}
					?>		
					</tbody>
				</table>
			<?php
			}

			if($comptMedKine !=0)
			{
			?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; margin-top:10px;<?php if($comptidO!=0){ echo "display:none;";}?>" id="tableKine"> 
					<thead> 
						<tr>
							<th style="width:35%">Date</th>
							<th style="width:25%"><?php echo getString(272);?></th>
							<th style="display:none;"><?php echo getString(145);?></th>
							<th style="width:25%"><?php echo getString(215);?></th>
							<th style="width:15%;display:none;"><?php echo getString(38);?></th>
							<th style="width:auto"><?php echo getString(21);?></th>
							<th style="width:auto"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
					<?php
					$o=0;
					
					while($ligneMedKine=$resultMedKine->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td>
								<select name="anneeMedKine[]" id="anneeMedKine" style="width:80px;" onchange="ShowSaveKine(<?php echo $o;?>)">
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
								
								<select name="moisMedKine[]" id="moisMedKine" style="width:120px;" onchange="ShowSaveKine(<?php echo $o;?>)">
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
								
								
								<select name="joursMedKine[]" id="joursMedKine" style="width:70px;" onchange="ShowSaveKine(<?php echo $o;?>)">
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
						
							<td>
								<?php
								
								$idassuKine=$ligneMedKine->id_assuKine;
																	
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuKine
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuKine='prestations_'.$ligneNomAssu->nomassurance;
								}
							}
															
								
								$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assuKine.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=14 AND p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneMedKine->id_prestationKine
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($comptPresta==0)
								{
									$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=14 AND p.id_prestation=:prestaId');
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedKine->id_prestationKine
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
								}
												
								if($lignePresta=$resultPresta->fetch())
								{
									echo '<input type="text" name="idprestaKine[]" style="width:100px;display:none; text-align:center" id="idprestaKine" value="'.$lignePresta->id_prestation.'"/>';
				
									echo '<input type="text" name="autreKine[]" style="width:100px;display:none; text-align:center" id="autreKine" value=""/>';
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta;
								?>
							</td>
							
							<td style="display:none;">								
								<input type="text" name="prixprestaKine[]" style="width:100px; text-align:center;" id="prixprestaKine" class="prixprestaKine" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveKine(<?php echo $o;?>)"/>
								
							</td>
								<?php
								}else{
									echo $lignePresta->nompresta;
									?>
							</td>
							
							<td style="display:none;">
								
								<input type="text" name="prixprestaKine[]" style="width:100px; text-align:center;" id="prixprestaKine" class="prixprestaKine" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveKine(<?php echo $o;?>)"/>
								
							</td>
								<?php
								}
								
							}
							
							if($ligneMedKine->id_prestationKine==0 AND $ligneMedKine->prixautreKine==0)
							{
								echo '<input type="text" name="autreKine[]" style="width:100px;display:none; text-align:center" id="autreKine" value="'.$ligneMedKine->autreKine.'"/>';

								echo $ligneMedKine->autreKine.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td style="display:none;">
										<input type="text" name="prixprestaKine[]" style="width:100px; text-align:center;" id="prixprestaKine" class="prixprestaKine" value="" placeholder="Tarrif ici..." onchange="ShowSaveKine('.$o.')"/>
										
										<input type="text" name="idprestaKine[]" style="width:100px;display:none; text-align:center" id="idprestaKine" value="0"/>
									</td>';

							}else{

								if($ligneMedKine->id_prestationKine==0 AND $ligneMedKine->prixautreKine!=0)
								{
									echo $ligneMedKine->autreKine.'<input type="text" name="idprestaKine[]" style="width:100px;display:none;" id="idprestaKine" value="0"/>
									
									</td>';
									
									echo '<input type="text" name="autreKine[]" style="width:100px;display:none;" id="autreKine" value="'.$ligneMedKine->autreKine.'"/>';	
									
									echo '<td style="display:none;">
									<input type="text" name="prixprestaKine[]" style="width:100px;" id="prixprestaKine" class="prixprestaKine" value="'.$ligneMedKine->prixautreKine.'" onchange="ShowSaveKine('.$o.')"/>';
								}
								
							}
							?>
							</td>
							
							<td>						
								<span type="submit" id="qteKineMoins<?php echo $o;?>" name="qteKineMoins<?php echo $o;?>" class="qteKineMoins btn" style="display:<?php if($ligneMedKine->qteKine ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteKine(<?php echo $o;?>)"/>-</span>	
								<input type="text" id="quantityKine<?php echo $o;?>" name="quantityKine[]" class="quantityKine" style="width:30px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedKine->qteKine;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteKine<?php echo $o;?>" name="qteKine[]" class="qteKine" style="width:50px;margin-left:0px;" value="<?php echo $o;?>"/>
							
								<span type="submit" id="qteKinePlus<?php echo $o;?>" name="qteKinePlus<?php echo $o;?>" class="qteKinePlus btn" onclick="PlusQteKine(<?php echo $o;?>)"/>+</span>
							
							</td>
							
							<td style="display:none;">
								<input type="text" id="percentKine" name="percentKine[]" class="percentKine" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								
								<input type="text" id="idmedKine" name="idmedKine[]" style="width:30px;display:none; text-align:center" value="<?php echo $ligneMedKine->id_medkine;?>"/>
							</td>

							<td>
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedKine->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedKine->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td>
								<a href="categoriesbill_hosp.php?deleteMedKine=<?php echo $ligneMedKine->id_medkine;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td colspan=5 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteKineBtn<?php echo $ligneMedKine->id_medkine;?>" id="addQteKineBtn<?php echo $o;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$o++;
					}
					?>		
					</tbody>
				</table>
			<?php
			}

			if($comptMedOrtho != 0)
			{
			?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; margin-top:10px;" id="tableOrtho"> 
					<thead> 
						<tr>
							<th style="width:35%">Date</th>
							<th style="width:25%"><?php echo 'P&O';?></th>
							<th style="display:none;"><?php echo getString(145);?></th>
							<th style="width:25%"><?php echo getString(215);?></th>
							<th style="width:15%;display:none;"><?php echo getString(38);?></th>
							<th style="width:auto"><?php echo getString(21);?></th>
							<th style="width:auto"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
					<?php
					$n=0;
					
					while($ligneMedOrtho=$resultMedOrtho->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td>
								<select name="anneeMedOrtho[]" id="anneeMedOrtho" style="width:80px;" onchange="ShowSaveOrtho(<?php echo $n;?>)">
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
								
								<select name="moisMedOrtho[]" id="moisMedOrtho" style="width:120px;" onchange="ShowSaveOrtho(<?php echo $n;?>)">
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
								
								
								<select name="joursMedOrtho[]" id="joursMedOrtho" style="width:70px;" onchange="ShowSaveOrtho(<?php echo $n;?>)">
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
						
							<td>
								<?php
								
								$idassuOrtho=$ligneMedOrtho->id_assuOrtho;
																	
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuOrtho
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuOrtho='prestations_'.$ligneNomAssu->nomassurance;
								}
							}
															
								
								$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assuOrtho.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneMedOrtho->id_prestationOrtho
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($comptPresta==0)
								{
									$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation=:prestaId');
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedOrtho->id_prestationOrtho
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
								}
												
								if($lignePresta=$resultPresta->fetch())
								{
									echo '<input type="text" name="idprestaOrtho[]" style="width:100px;display:none; text-align:center" id="idprestaOrtho" value="'.$lignePresta->id_prestation.'"/>';
				
									echo '<input type="text" name="autreOrtho[]" style="width:100px;display:none; text-align:center" id="autreOrtho" value=""/>';
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta;
								?>
							</td>
							
							<td style="display:none;">
								<input type="text" name="prixprestaOrtho[]" style="width:100px; text-align:center;display:none;" id="prixprestaOrtho" class="prixprestaOrtho" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveOrtho(<?php echo $n;?>)"/>
								
							</td>
								<?php
								}else{
									echo $lignePresta->nompresta;
									?>
							</td>
							
							<td style="display:none;">				
								<input type="text" name="prixprestaOrtho[]" style="width:100px; text-align:center;display:none;" id="prixprestaOrtho" class="prixprestaOrtho" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveOrtho(<?php echo $n;?>)"/>
								
							</td>
								<?php
								}
								
							}
							
							if($ligneMedOrtho->id_prestationOrtho==0 AND $ligneMedOrtho->prixautreOrtho==0)
							{
								echo '<input type="text" name="autreOrtho[]" style="width:100px;display:none; text-align:center" id="autreOrtho" value="'.$ligneMedOrtho->autreOrtho.'"/>';

								echo $ligneMedOrtho->autreOrtho.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaOrtho[]" style="width:100px; text-align:center;display:none;" id="prixprestaOrtho" class="prixprestaOrtho" value="" placeholder="Tarrif ici..." onchange="ShowSaveOrtho('.$n.')"/>
										
										<input type="text" name="idprestaOrtho[]" style="width:100px;display:none; text-align:center" id="idprestaOrtho" value="0"/>
									</td>';

							}else{

								if($ligneMedOrtho->id_prestationOrtho==0 AND $ligneMedOrtho->prixautreOrtho!=0)
								{
									echo $ligneMedOrtho->autreOrtho.'<input type="text" name="idprestaOrtho[]" style="width:100px;display:none;" id="idprestaOrtho" value="0"/>
									
									</td>';
									
									echo '<input type="text" name="autreOrtho[]" style="width:100px;display:none;" id="autreOrtho" value="'.$ligneMedOrtho->autreOrtho.'"/>';	
									
									echo '<td>
									<input type="text" name="prixprestaOrtho[]" style="width:100px;display:none;" id="prixprestaOrtho" class="prixprestaOrtho" value="'.$ligneMedOrtho->prixautreOrtho.'" onchange="ShowSaveOrtho('.$n.')"/>';
								}
								
							}
							?>
							</td>
							
							<td>						
								<span type="submit" id="qteOrthoMoins<?php echo $n;?>" name="qteOrthoMoins<?php echo $n;?>" class="qteOrthoMoins btn" style="display:<?php if($ligneMedOrtho->qteOrtho ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteOrtho(<?php echo $n;?>)"/>-</span>	
								<input type="text" id="quantityOrtho<?php echo $n;?>" name="quantityOrtho[]" class="quantityOrtho" style="width:30px;margin-left:0px;<?php if($comptidI!=0 OR $comptidO!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedOrtho->qteOrtho;?>" <?php if($comptidI!=0 OR $comptidO!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteOrtho<?php echo $n;?>" name="qteOrtho[]" class="qteOrtho" style="width:50px;margin-left:0px;" value="<?php echo $n;?>"/>
							
								<span type="submit" id="qteOrthoPlus<?php echo $n;?>" name="qteOrthoPlus<?php echo $n;?>" class="qteOrthoPlus btn" onclick="PlusQteOrtho(<?php echo $n;?>)"/>+</span>
							
							</td>
							
							<td style="display:none;">
								<input type="text" id="percentOrtho" name="percentOrtho[]" class="percentOrtho" style="width:30px; text-align:center;<?php if($comptidI!=0 OR $comptidO!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0 OR $comptidO!=0){ echo 'readonly="readonly"';}?>/> %
								
								<input type="text" id="idmedOrtho" name="idmedOrtho[]" style="width:30px;display:none; text-align:center" value="<?php echo $ligneMedOrtho->id_medortho;?>"/>
							</td>
							
							<td>
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedOrtho->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedOrtho->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td>
								<a href="categoriesbill_hosp.php?deleteMedOrtho=<?php echo $ligneMedOrtho->id_medortho;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
						if($comptidI!=0 OR $comptidO!=0)
						{
						?>
						<tr>
							<td colspan=5 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteOrthoBtn<?php echo $ligneMedOrtho->id_medortho;?>" id="addQteOrthoBtn<?php echo $n;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$n++;
					}
					?>		
					</tbody>
				</table>
			<?php
			}
			
			if($comptMedConsult!=0)
			{
			?>
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; margin-top:10px;<?php if($comptidO!=0){ echo "display:none;";}?>" id="tableServ"> 
					<thead> 
						<tr>
							<th style="width:35%">Date</th>
							<th style="width:25%"><?php echo getString(39);?></th>
							<th style="display:none;"><?php echo getString(145);?></th>
							<th style="width:25%"><?php echo getString(215);?></th>
							<th style="width:15%;display:none;"><?php echo getString(38);?></th>
							<th style="width:auto"><?php echo getString(21);?></th>
							<th style="width:auto"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						$e=0;
						
						while($ligneMedConsult=$resultMedConsult->fetch())
						{
							
						?>
						<tr style="text-align:center;">
							<td>
								<select name="anneeMedConsu[]" id="anneeMedConsu" style="width:80px;" onchange="ShowSaveConsu(<?php echo $e;?>)">
									<?php
									for($a=2016;$a<=2050;$a++)
									{
										$anneeConsult=date('Y', strtotime($ligneMedConsult->datehosp));
									?>
										<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeConsult==$a) echo 'selected="selected"';?>>
										<?php echo $a;?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="moisMedConsu[]" id="moisMedConsu" style="width:120px;" onchange="ShowSaveConsu(<?php echo $e;?>)">
									<?php
									for($m=1;$m<=12;$m++)
									{
										$moisString=date("F",mktime(0,0,0,$m,10));
										$moisConsult=date("F",mktime(0,0,0,date('m', strtotime($ligneMedConsult->datehosp)),10));
									?>
										<option value="<?php echo $m;?>" style="font-weight:bold" <?php if($moisConsult==$moisString) echo 'selected="selected"'; ?>>
										<?php 
											echo $moisString;
										?>
										</option>
									<?php
									}
									?>
								</select>
								
								
								<select name="joursMedConsu[]" id="joursMedConsu" style="width:70px;" onchange="ShowSaveConsu(<?php echo $e;?>)">
									<?php
									for($d=1;$d<=31;$d++)
									{
										$joursConsult=date('d', strtotime($ligneMedConsult->datehosp));
									?>
										<option value="<?php echo $d;?>" style="font-weight:bold" <?php if($joursConsult==$d) echo 'selected="selected"'; ?>>
										<?php 
											echo $d;
										?>
										</option>
									<?php
									}
									?>
								</select>
							</td>
						
							<td>
							<?php
							
							$idassuServ=$ligneMedConsult->id_assuServ;
							
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuServ
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
								}
							}


							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedConsult->id_prestationConsu
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
							
								echo '<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta" class="idpresta" value="'.$lignePresta->id_prestation.'"/>';
								
								echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu" value=""/>';
										
								if($lignePresta->namepresta!='')
								{

									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td style="display:none;">
								
								<input type="text" name="prixprestaConsu[]" style="width:100px;" id="prixprestaConsu" class="prixprestaConsu" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveConsu(<?php echo $e;?>)"/>
								
							</td>
								<?php
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td style="display:none;">
								
								<input type="text" name="prixprestaConsu[]" style="width:100px; text-align:center" id="prixprestaConsu" class="prixprestaConsu" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveConsu(<?php echo $e;?>)"/>
								
							</td>
								<?php
								}
							}
							
							if($ligneMedConsult->id_prestationConsu==NULL AND $ligneMedConsult->prixautreConsu==0)
							{
								echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu" value="'.$ligneMedConsult->autreConsu.'"/>';
								
								echo $ligneMedConsult->autreConsu.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td style="display:none;">
										<input type="text" name="prixprestaConsu[]" style="width:100px; text-align:center;" id="prixprestaConsu" class="prixprestaConsu" value="" placeholder="Tarrif ici..." onchange="ShowSaveConsu('.$e.')"/>
										
										<input type="text" name="'.$ligneMedConsult->id_medconsu.'" style="width:100px;display:none; text-align:center" id="id_medconsu" value="'.$ligneMedConsult->id_medconsu.'"/>
									
										<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta" value="0"/>
									</td>';

							}else{
							
								if($ligneMedConsult->id_prestationConsu==NULL AND $ligneMedConsult->prixautreConsu!=0)
								{
									
									echo '<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta" value="0"/>';
								
									echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu" value="'.$ligneMedConsult->autreConsu.'"/>';
								
									echo $ligneMedConsult->autreConsu.'</td>
									<td style="display:none;">
										<input type="text" name="prixprestaConsu[]" style="width:100px; text-align:center;" id="prixprestaConsu" class="prixprestaConsu" value="'.$ligneMedConsult->prixautreConsu.'" onchange="ShowSaveConsu('.$e.')"/>
									
									</td>';
								}
							}
							?>
							
							<td>
								<span type="submit" id="qteConsuMoins<?php echo $e;?>" name="qteConsuMoins<?php echo $e;?>" class="qteConsuMoins btn" style="display:<?php if($ligneMedConsult->qteConsu ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteConsu(<?php echo $e;?>)"/>-</span>
								
								<input type="text" id="quantityConsu<?php echo $e;?>" name="quantityConsu[]" class="quantityConsu" style="width:30px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedConsult->qteConsu;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteConsu<?php echo $e;?>" name="qteConsu[]" class="qteConsu" style="width:50px;margin-left:0px;" value="<?php echo $e;?>"/>
							
								<span type="submit" id="qteConsuPlus<?php echo $e;?>" name="qteConsuPlus<?php echo $e;?>" class="qteConsuPlus btn" onclick="PlusQteConsu(<?php echo $e;?>)"/>+</span>

							</td>
							
							<td style="display:none;">
								<input type="text" name="percentConsu[]" class="percentConsu" id="percentConsu<?php echo $e;?>" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>;" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
							
								<input type="hidden" name="idmedConsu[]" class="idmedConsu"  id="idmedConsu<?php echo $e;?>"style="width:50px; text-align:center" value="<?php echo $ligneMedConsult->id_medconsu;?>"/>
							
							</td>
							
							<td>
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedConsult->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedConsult->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td>
								<a href="categoriesbill_hosp.php?deleteMedConsu=<?php echo $ligneMedConsult->id_medconsu;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
					
							</td>
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td colspan=5 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteConsuBtn<?php echo $ligneMedConsult->id_medconsu;?>" id="addQteConsuBtn<?php echo $e;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							
							</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$e++;
						}
						?>		
					</tbody>
				</table>
			<?php
			}
			?>
		
				<?php
				if(isset($_SESSION['codeCash']))
				{
					if(isset($_GET['sortie']))
					{
				?>
					<table class="tablesorter tablesorter1" cellspacing="0" style="background:none;border:none; width:70%; margin-top:10px;">
						<tr>
							<td>
								<input type="submit" id="previewbtn" name="previewbtn" class="btn-large" value="Preview Hospitalisation Report"/>
							</td>
						</tr>
					</table>
				<?php
					}else{
				?>
					<table class="tablesorter tablesorter1" cellspacing="0" style="background:none;border:none; width:70%; margin-top:10px;">
						<tr>
							<td>
								<input type="submit" id="previewbtn" name="previewbtn" class="btn-large" value="Preview Print"/>
							</td>
						</tr>
					</table>
				<?php
					}
				}
				?>
			</form>
		<?php
		}
		?>
		</div>
			
	<?php
	}
	?>
	
	
	<script>

	function getXMLHttpRequest() {
	var xhr = null;

	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest(); 
		}
	}else {
		alert("Your Browser does not support   XMLHTTPRequest object...");
		return null;
	}

	return xhr;
	}

	
	function ShowAddbtn(categorie)
	{	
		var catego =document.getElementById('categorie').value;
			
		if(catego!="0")
		{
			document.getElementById('addbtn').style.display='inline';
	
		}else{
			document.getElementById('addbtn').style.display='none';
		}
	}
	
	function ShowMore(more)
	{
		if(more=="services")
		{
			document.getElementById('showService').style.display='table';
			document.getElementById('divViewServ').style.display='inline';
							
		}else{
		
			document.getElementById('showService').style.display='none';
			document.getElementById('divViewServ').style.display='none';
		}
		
		if(more==3)
		{
			
			document.getElementById('divViewInf').style.display='block';	
		}else{
		
			document.getElementById('divViewInf').style.display='none';
			
		}
		
		
		if(more==4)
		{
			document.getElementById('divViewSurge').style.display='inline';
				
		}else{		
			document.getElementById('divViewSurge').style.display='none';
		}	
			
		
		if(more==12)
		{
			document.getElementById('divViewLab').style.display='block';	
		}else{
		
			document.getElementById('divViewLab').style.display='none';
			
		}
				
		
		if(more==13)
		{
			document.getElementById('divViewRad').style.display='block';	
		}else{
		
			document.getElementById('divViewRad').style.display='none';
			
		}		
		
		
		if(more==14)
		{
			document.getElementById('divViewKine').style.display='block';	
		}else{
		
			document.getElementById('divViewKine').style.display='none';
			
		}

		
		if(more==21)
		{
			document.getElementById('divViewConsom').style.display='inline';
			
		}else{
		
			document.getElementById('divViewConsom').style.display='none';
		}
		
		
		if(more==22)
		{
			document.getElementById('divViewMedoc').style.display='inline';
				
		}else{		
			document.getElementById('divViewMedoc').style.display='none';
		}	
		
		
		if(more==23)
		{
			document.getElementById('divViewOrtho').style.display='inline';
				
		}else{		
			document.getElementById('divViewOrtho').style.display='none';
		}
		
		/* 
		if(more==24)
		{
			document.getElementById('divViewMngr').style.display='inline';
				
		}else{		
			document.getElementById('divViewMngr').style.display='none';
		}
		 */
		
		
		if(more="services" || more==3 || more==4 || more==12 || more==13 || more==14 || more==21 || more==22 || more==23 || more==24)
		{
			document.getElementById('addbtn').style.display='inline';
				
		}else{		
			document.getElementById('addbtn').style.display='none';
		}		
	}
	
	function ShowList(list)
	{
		if( list =='Users')
		{
			document.getElementById('divMenuUser').style.display='inline';
			document.getElementById('divMenuMsg').style.display='none';
		
		}
		
		if( list =='Msg')
		{
			document.getElementById('divMenuMsg').style.display='inline';
			document.getElementById('divMenuUser').style.display='none';
			document.getElementById('divListe').style.display='none';
		}
		
		if( list =='Liste')
		{
			document.getElementById('divListe').style.display='inline';
			document.getElementById('listOff').style.display='inline';
			document.getElementById('listOn').style.display='none';
		}
		
		if( list =='ListeNon')
		{
			document.getElementById('divListe').style.display='none';
			document.getElementById('listOn').style.display='inline';
			document.getElementById('listOff').style.display='none';
		}
		
		if( list =='MsgRecu')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='inline';
			document.getElementById('EnvoiMsg').style.display='inline';
			document.getElementById('MsgEnvoye').style.display='inline';
			document.getElementById('MsgRecu').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
		if( list =='MsgEnvoye')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('MsgEnvoye').style.display='none';
			document.getElementById('EnvoiMsg').style.display='inline';
			document.getElementById('MsgRecu').style.display='inline';
			document.getElementById('envoye').style.display='inline';
		}
		
		if( list =='EnvoiMsg')
		{
			document.getElementById('formMsg').style.display='inline';
			document.getElementById('MsgEnvoye').style.display='inline';
			document.getElementById('MsgRecu').style.display='inline';
			document.getElementById('EnvoiMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
	}

	function controlFormPassword(theForm){
		var rapport="";
		
		rapport +=controlPass(theForm.Pass);

			if (rapport != "") {
			alert("Veuillez corrigez les erreurs suivantes:\n" + rapport);
						return false;
			 }
	}


	function controlPass(fld){
		var erreur="";
		
		if(fld.value=="")
		{
			erreur="Saisir nouveau mot de pass\n";
			fld.style.background="cyan";
		}
		
		return erreur;
	}

	</script>
	
	
	<script>
	function PlusQteConsu(i)
	{	
		var plus=parseInt($('#quantityConsu'+i).val()) + 1;		
		$('#quantityConsu'+i).val(plus);
		
		if($('#quantityConsu'+i).val()<2)
		{
			document.getElementById('qteConsuMoins'+i).style.display="none";
		}else{
			document.getElementById('qteConsuMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteConsuBtn'+i).style.display="inline";
	}
	
	function MoinsQteConsu(i)
	{
		var moins=parseInt($('#quantityConsu'+i).val()) - 1;		
		$('#quantityConsu'+i).val(moins);
		
		if($('#quantityConsu'+i).val()<2)
		{
			document.getElementById('qteConsuMoins'+i).style.display="none";
		}else{
			document.getElementById('qteConsuMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteConsuBtn'+i).style.display="inline";
	}
	
	function ShowSaveConsu(i)
	{
		document.getElementById('addQteConsuBtn'+i).style.display="inline";
	}
	
	
	
	
	function PlusQteInf(i)
	{	
		var plus=parseInt($('#quantityInf'+i).val()) + 1;		
		$('#quantityInf'+i).val(plus);
		
		if($('#quantityInf'+i).val()<2)
		{
			document.getElementById('qteInfMoins'+i).style.display="none";
		}else{
			document.getElementById('qteInfMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteInfBtn'+i).style.display="inline";
	}
	
	function MoinsQteInf(i)
	{
		var moins=parseInt($('#quantityInf'+i).val()) - 1;		
		$('#quantityInf'+i).val(moins);
		
		if($('#quantityInf'+i).val()<2)
		{
			document.getElementById('qteInfMoins'+i).style.display="none";
		}else{
			document.getElementById('qteInfMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteInfBtn'+i).style.display="inline";
	}
	
	function ShowSaveInf(i)
	{
		document.getElementById('addQteInfBtn'+i).style.display="inline";
	}
	
	
	
	
	function PlusQteSurge(i)
	{	
		var plus=parseInt($('#quantitySurge'+i).val()) + 1;		
		$('#quantitySurge'+i).val(plus);
		
		if($('#quantitySurge'+i).val()<2)
		{
			document.getElementById('qteSurgeMoins'+i).style.display="none";
		}else{
			document.getElementById('qteSurgeMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteSurgeBtn'+i).style.display="inline";
	}
	
	function MoinsQteSurge(i)
	{
		var moins=parseInt($('#quantitySurge'+i).val()) - 1;		
		$('#quantitySurge'+i).val(moins);
		
		if($('#quantitySurge'+i).val()<2)
		{
			document.getElementById('qteSurgeMoins'+i).style.display="none";
		}else{
			document.getElementById('qteSurgeMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteSurgeBtn'+i).style.display="inline";
	}
	
	function ShowSaveSurge(i)
	{
		document.getElementById('addQteSurgeBtn'+i).style.display="inline";
	}
	
	
	
	
	function PlusQteLab(i)
	{	
		var plus=parseInt($('#quantityLab'+i).val()) + 1;		
		$('#quantityLab'+i).val(plus);
		
		if($('#quantityLab'+i).val()<2)
		{
			document.getElementById('qteLabMoins'+i).style.display="none";
		}else{
			document.getElementById('qteLabMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteLabBtn'+i).style.display="inline";
	}
	
	function MoinsQteLab(i)
	{
		var moins=parseInt($('#quantityLab'+i).val()) - 1;		
		$('#quantityLab'+i).val(moins);
		
		if($('#quantityLab'+i).val()<2)
		{
			document.getElementById('qteLabMoins'+i).style.display="none";
		}else{
			document.getElementById('qteLabMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteLabBtn'+i).style.display="inline";
	}
	
	function ShowSaveLab(i)
	{
		document.getElementById('addQteLabBtn'+i).style.display="inline";
	}
	
	
	
	
	function PlusQteRad(i)
	{	
		var plus=parseInt($('#quantityRad'+i).val()) + 1;		
		$('#quantityRad'+i).val(plus);
		
		if($('#quantityRad'+i).val()<2)
		{
			document.getElementById('qteRadMoins'+i).style.display="none";
		}else{
			document.getElementById('qteRadMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteRadBtn'+i).style.display="inline";
	}
	
	function MoinsQteRad(i)
	{
		var moins=parseInt($('#quantityRad'+i).val()) - 1;		
		$('#quantityRad'+i).val(moins);
		
		if($('#quantityRad'+i).val()<2)
		{
			document.getElementById('qteRadMoins'+i).style.display="none";
		}else{
			document.getElementById('qteRadMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteRadBtn'+i).style.display="inline";
	}
	
	function ShowSaveRad(i)
	{
		document.getElementById('addQteRadBtn'+i).style.display="inline";
	}
	
	
	
	
	function PlusQteKine(i)
	{	
		var plus=parseInt($('#quantityKine'+i).val()) + 1;		
		$('#quantityKine'+i).val(plus);
		
		if($('#quantityKine'+i).val()<2)
		{
			document.getElementById('qteKineMoins'+i).style.display="none";
		}else{
			document.getElementById('qteKineMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteKineBtn'+i).style.display="inline";
	}
	
	function MoinsQteKine(i)
	{
		var moins=parseInt($('#quantityKine'+i).val()) - 1;		
		$('#quantityKine'+i).val(moins);
		
		if($('#quantityKine'+i).val()<2)
		{
			document.getElementById('qteKineMoins'+i).style.display="none";
		}else{
			document.getElementById('qteKineMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteKineBtn'+i).style.display="inline";
	}
	
	function ShowSaveKine(i)
	{
		document.getElementById('addQteKineBtn'+i).style.display="inline";
	}
	
	
	
	
	function PlusQteConsom(i)
	{	
		var plus=parseInt($('#quantityConsom'+i).val()) + 1;		
		$('#quantityConsom'+i).val(plus);
		
		if($('#quantityConsom'+i).val()<2)
		{
			document.getElementById('qteConsomMoins'+i).style.display="none";
		}else{
			document.getElementById('qteConsomMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteConsomBtn'+i).style.display="inline";
	}
	
	function MoinsQteConsom(i)
	{
		var moins=parseInt($('#quantityConsom'+i).val()) - 1;		
		$('#quantityConsom'+i).val(moins);
		
		if($('#quantityConsom'+i).val()<2)
		{
			document.getElementById('qteConsomMoins'+i).style.display="none";
		}else{
			document.getElementById('qteConsomMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteConsomBtn'+i).style.display="inline";
	}
	
	function ShowSaveConsom(i)
	{
		document.getElementById('addQteConsomBtn'+i).style.display="inline";
	}
	
	
	
	
	function PlusQteMedoc(j)
	{		
		var plus=parseInt($('#quantityMedoc'+j).val()) + 1;		
		$('#quantityMedoc'+j).val(plus);	
		
		if($('#quantityMedoc'+j).val()<2)
		{
			document.getElementById('qteMedocMoins'+j).style.display="none";
		}else{
			document.getElementById('qteMedocMoins'+j).style.display="inline";
		}
		
		document.getElementById('addQteMedocBtn'+j).style.display="inline";
	}
	
	function MoinsQteMedoc(j)
	{
		var moins=parseInt($('#quantityMedoc'+j).val()) - 1;		
		$('#quantityMedoc'+j).val(moins);
		
		// alert ($('#quantityMedoc'+j).val());
		
		if($('#quantityMedoc'+j).val()<2)
		{
			document.getElementById('qteMedocMoins'+j).style.display="none";
		}else{
			document.getElementById('qteMedocMoins'+j).style.display="inline";
		}
		
		document.getElementById('addQteMedocBtn'+j).style.display="inline";
	}
	
	function ShowSaveMedoc(j)
	{
		document.getElementById('addQteMedocBtn'+j).style.display="inline";
	}
	
	
	
	
	function PlusQteOrtho(j)
	{		
		var plus=parseInt($('#quantityOrtho'+j).val()) + 1;		
		$('#quantityOrtho'+j).val(plus);	
		
		if($('#quantityOrtho'+j).val()<2)
		{
			document.getElementById('qteOrthoMoins'+j).style.display="none";
		}else{
			document.getElementById('qteOrthoMoins'+j).style.display="inline";
		}
		
		document.getElementById('addQteOrthoBtn'+j).style.display="inline";
	}
	
	function MoinsQteOrtho(j)
	{
		var moins=parseInt($('#quantityOrtho'+j).val()) - 1;		
		$('#quantityOrtho'+j).val(moins);
		
		// alert ($('#quantityOrtho'+j).val());
		
		if($('#quantityOrtho'+j).val()<2)
		{
			document.getElementById('qteOrthoMoins'+j).style.display="none";
		}else{
			document.getElementById('qteOrthoMoins'+j).style.display="inline";
		}
		
		document.getElementById('addQteOrthoBtn'+j).style.display="inline";
	}
	
	function ShowSaveOrtho(j)
	{
		document.getElementById('addQteOrthoBtn'+j).style.display="inline";
	}
	
	
	
	
	function PlusQteMngr(j)
	{		
		var plus=parseInt($('#quantityMngr'+j).val()) + 1;		
		$('#quantityMngr'+j).val(plus);	
		
		if($('#quantityMngr'+j).val()<2)
		{
			document.getElementById('qteMngrMoins'+j).style.display="none";
		}else{
			document.getElementById('qteMngrMoins'+j).style.display="inline";
		}
		
		document.getElementById('addQteMngrBtn'+j).style.display="inline";
	}
	
	function MoinsQteMngr(j)
	{
		var moins=parseInt($('#quantityMngr'+j).val()) - 1;		
		$('#quantityMngr'+j).val(moins);
		
		// alert ($('#quantityMngr'+j).val());
		
		if($('#quantityMngr'+j).val()<2)
		{
			document.getElementById('qteMngrMoins'+j).style.display="none";
		}else{
			document.getElementById('qteMngrMoins'+j).style.display="inline";
		}
		
		document.getElementById('addQteMngrBtn'+j).style.display="inline";
	}
	
	function ShowSaveMngr(j)
	{
		document.getElementById('addQteMngrBtn'+j).style.display="inline";
	}
	
	</script>
	

</div>

<?php
	
	}else{
		echo '<script language="javascript"> alert("Vous avez été désactivé\n Demander à l\'administrateur de vous activer");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}

}else{
	echo '<script language="javascript">document.location.href="index.php"</script>';
}



	if(isset($_POST['confirmPass']))
	{
	
		$pass = $_POST['Pass'];
		$iduti = $_SESSION['id'];
				
		$resultats=$connexion->prepare('UPDATE utilisateurs SET password=:pass WHERE id_u=:modifierIduti');
						
		$resultats->execute(array(
		'pass'=>$pass,
		'modifierIduti'=>$iduti
		))or die( print_r($connexion->errorInfo()));
			
		echo '<script type="text/javascript"> alert("Your password have been changed\nYour new password is : '.$pass.'");</script>';
		
	}


?>

<div>
	<footer style="bottom: 0px; width: 100%; text-align: center; background: #fff; border-top: 1px solid #eee; padding: 10px 0px; vertical-align: middle;">
		<p style="margin:0"><span style="color:#a00000">Medical FIle®</span> is a product of <span style="font-style:bold;">Innovate Solutions Ltd</span>. ©2022-<?php echo date('Y');?> All rights reserved.</p>
	</footer>
</div>

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
	    
	</script>
	

	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	
		$('#checkprestaServ').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaInf').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaSurge').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaLab').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaRad').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaKine').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaConsom').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaMedoc').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaOrtho').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaMngr').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
	</script>
	
</body>
</html>