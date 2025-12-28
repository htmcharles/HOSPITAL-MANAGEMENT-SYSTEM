<?php 
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


	$annee = date('d-M-Y', strtotime($_GET['datefacture']));
	

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
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hospmodifier.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&numbill='.$_GET['numbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedInf']))
{

	$id_medI = $_GET['deleteMedInf'];
	
	$deleteInf=$connexion->prepare('DELETE FROM med_inf_hosp WHERE id_medinf=:id_medI');
	
	$deleteInf->execute(array(
	'id_medI'=>$id_medI
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un soins");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hospmodifier.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&numbill='.$_GET['numbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedLabo']))
{

	$id_medL= $_GET['deleteMedLabo'];
	
	$deleteLabo=$connexion->prepare('DELETE FROM med_labo_hosp WHERE id_medlabo=:id_medL');
	
	$deleteLabo->execute(array(
	'id_medL'=>$id_medL
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un examen");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hospmodifier.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&numbill='.$_GET['numbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedRadio']))
{

	$id_medX= $_GET['deleteMedRadio'];
	
	$deleteRadio=$connexion->prepare('DELETE FROM med_radio_hosp WHERE id_medradio=:id_medX');
	
	$deleteRadio->execute(array(
	'id_medX'=>$id_medX
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une radio");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hospmodifier.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&numbill='.$_GET['numbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedConsom']))
{

	$id_medCo= $_GET['deleteMedConsom'];
	
	$deleteConsom=$connexion->prepare('DELETE FROM med_consom_hosp WHERE id_medconsom=:id_medCo');
	
	$deleteConsom->execute(array(
	'id_medCo'=>$id_medCo
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un consommable");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hospmodifier.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&numbill='.$_GET['numbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
}

if(isset($_GET['deleteMedMedoc']))
{

	$id_medMe= $_GET['deleteMedMedoc'];
	
	$deleteMedoc=$connexion->prepare('DELETE FROM med_medoc_hosp WHERE id_medmedoc=:id_medMe');
	
	$deleteMedoc->execute(array(
	'id_medMe'=>$id_medMe
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un medicament");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill_hospmodifier.php?inf='.$_GET['inf'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&numbill='.$_GET['numbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&'.$previewback.'";</script>';
	
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
	
	$idBilling=$_GET['numbill'];
	$idhosp=$_GET['idhosp'];
	$codecashier="";
	
	/*----------Update Hosp----------------*/
	
	$updateIdFactureHosp=$connexion->prepare('UPDATE patients_hosp ph SET ph.id_factureHosp=:numbill, ph.codecashierHosp=:codecashier, ph.codecoordiHosp=:codecoordi WHERE ph.id_hosp=:idhosp AND ph.id_factureHosp IS NULL AND ph.numero=:num');

	$updateIdFactureHosp->execute(array(
	'numbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$codecashier,
	'codecoordi'=>$_SESSION['codeC']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Consult----------------*/
	
	$updateIdFactureMedConsult=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.id_factureMedConsu=:numbill, mc.codecashier=:codecashier, mc.codecoordi=:codecoordi WHERE mc.id_hospMed=:idhosp AND mc.numero=:num AND mc.id_factureMedConsu=""');

	$updateIdFactureMedConsult->execute(array(
	'numbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$codecashier,
	'codecoordi'=>$_SESSION['codeC']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Inf----------------*/
	
	$updateIdFactureMedInf=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.id_factureMedInf=:numbill, mi.codecashier=:codecashier, mi.codecoordi=:codecoordi WHERE mi.id_hospInf=:idhosp AND mi.numero=:num AND mi.id_factureMedInf=""');

	$updateIdFactureMedInf->execute(array(
	'numbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$codecashier,
	'codecoordi'=>$_SESSION['codeC']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Labo----------------*/
	
	$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.id_factureMedLabo=:numbill, ml.codecashier=:codecashier, ml.codecoordi=:codecoordi WHERE ml.id_hospLabo=:idhosp AND ml.numero=:num AND ml.id_factureMedLabo=""');

	$updateIdFactureMedLabo->execute(array(
	'numbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$codecashier,
	'codecoordi'=>$_SESSION['codeC']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Radio----------------*/
	
	$updateIdFactureMedRadio=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.id_factureMedRadio=:numbill, mr.codecashier=:codecashier, mr.codecoordi=:codecoordi WHERE mr.id_hospRadio=:idhosp AND mr.numero=:num AND mr.id_factureMedRadio=""');

	$updateIdFactureMedRadio->execute(array(
	'numbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$codecashier,
	'codecoordi'=>$_SESSION['codeC']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Consom----------------*/
	
	$updateIdFactureMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.id_factureMedConsom=:numbill, mco.codecashier=:codecashier, mco.codecoordi=:codecoordi WHERE mco.id_hospConsom=:idhosp AND mco.numero=:num AND mco.id_factureMedConsom=""');

	$updateIdFactureMedConsom->execute(array(
	'numbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$codecashier,
	'codecoordi'=>$_SESSION['codeC']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Medoc----------------*/
	
	$updateIdFactureMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.id_factureMedMedoc=:numbill, mdo.codecashier=:codecashier, mdo.codecoordi=:codecoordi WHERE mdo.id_hospMedoc=:idhosp AND mdo.numero=:num AND mdo.id_factureMedMedoc=""');

	$updateIdFactureMedMedoc->execute(array(
	'numbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idhosp'=>$idhosp,
	'codecashier'=>$codecashier,
	'codecoordi'=>$_SESSION['codeC']
	
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
	var percentConsu=document.getElementsByClassName("percentConsu");
	
	
	var prixprestaInf=document.getElementsByClassName("prixprestaInf");
	var percentInf=document.getElementsByClassName("percentInf");
	
	
	var prixprestaLab=document.getElementsByClassName("prixprestaLab");
	var percentLab=document.getElementsByClassName("percentLab");
	
	
	var prixprestaRad=document.getElementsByClassName("prixprestaRad");
	var percentRad=document.getElementsByClassName("percentRad");
	
	
	var prixprestaConsom=document.getElementsByClassName("prixprestaConsom");
	var quantityConsom=document.getElementsByClassName("quantityConsom");
	var percentConsom=document.getElementsByClassName("percentConsom");
	
	
	var prixprestaMedoc=document.getElementsByClassName("prixprestaMedoc");
	var quantityMedoc=document.getElementsByClassName("quantityMedoc");
	var percentMedoc=document.getElementsByClassName("percentMedoc");
	
	
	
	var rapportPrixHosp="";
	var rapportPercentHosp="";
	
	var i;
	var rapportPrixConsu = [];
	var rapportPercentConsu = [];
	var rapportPrixInf = [];
	var rapportPercentInf = [];
	var rapportPrixLab = [];
	var rapportPercentLab = [];
	var rapportPrixRad = [];
	var rapportPercentRad = [];
	var rapportPrixConsom = [];
	var rapportQuantityConsom = [];
	var rapportPercentConsom = [];
	var rapportPrixMedoc = [];
	var rapportQuantityMedoc = [];
	var rapportPercentMedoc = [];
	
	
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
		
		
		
	if (rapportPrixHosp != "" || rapportPercentHosp != "" || rapportPrixConsu != "" || rapportPercentConsu != "" || rapportPrixInf != "" || rapportPercentInf != "" || rapportPrixLab != "" || rapportPercentLab != "" || rapportPrixRad != "" || rapportPercentRad != "" || rapportPrixConsom != "" || rapportQuantityConsom != "" || rapportPercentConsom != "" || rapportPrixMedoc != "" || rapportQuantityMedoc != "" || rapportPercentMedoc != "") {
	
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
$sqlC=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");

$comptidI=$sqlI->rowCount();
$comptidC=$sqlC->rowCount();


$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $comptidC!=0 AND isset($_GET['num']))
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
					<form method="post" action="categoriesbill_hospmodifier.php?num=<?php echo $_GET['num'];?>&facture=ok<?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];} if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
					
						<a href="categoriesbill_hospmodifier.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?><?php if(isset($_GET['manager'])){ echo '&manager='.$_GET['manager'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['numbill'])){ echo '&numbill='.$_GET['numbill'];}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="categoriesbill_hospmodifier.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?><?php if(isset($_GET['manager'])){ echo '&manager='.$_GET['manager'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['numbill'])){ echo '&numbill='.$_GET['numbill'];}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}?>" class="btn"><?php echo getString(29);?></a>
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
if(isset($_SESSION['codeC']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="report.php?coordi=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Reports';?>
		</a>

		<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Factures';?>
		</a>
	
	</div>
	
<?php
}
?>

<div class="account-container" style="width:90%">

	<div id='cssmenu' style="text-align:center;">

		<ul>
			<li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="<?php echo getString(48);?>"><i class="fa fa-users fa-1x fa-fw"></i> <?php echo getString(48);?></a></li>
			
			<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-1x fa-fw"></i> <?php echo getString(49);?></a></li>
			
		</ul>

		<ul style="margin-top:20px;background:none;border:none;">

			<div id="divMenuUser" style="display:inline-block;">
			
				<a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" id="newUser" style="display:none;"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(87);?></a>
			
				<a onclick="ShowList('Liste')" id="listOn" class="btn-large" style="display:none;"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(55);?></a>
				
				<span onclick="ShowList('ListeNon')" id="listOff" class="btn-large" style="display:none;"><i class="fa fa-eye-slash fa-lg fa-fw"></i> <?php echo getString(56);?></span>
			
			</div>

		</ul>
		
			<div style="display:none;" id="divMenuMsg">

				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57); ?></a>
				
				<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
				
				<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

			</div>

			
			<div style="display:none;margin-top:50px;" id="divListe" align="center">

				<br/>

				<a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&listPa=1<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(20);?></a>
				
				<a href="medecins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(19);?></a>
				
				<a href="infirmiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(21);?></a>
				
				<a href="laborantins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(22);?></a>
				
				<a href="radiologues1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Radiologue';?></a>
				
				<a href="receptionistes1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(40);?></a>
				
				<a href="caissiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(23);?></a>
				
				<a href="coordinateurs1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Manager';?></a>
				
			</div>
		
	</div>


	<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:10px auto auto auto; padding: 10px; width:80%;">
		<tr>
			<td style="text-align:left; width:33.333%;">
				
				<p class="patientId"><span> <?php echo getString(89);?>:</span> <?php echo $nom_uti.' '.$prenom_uti; ?>
				
				<p class="patientId"><span>S/N:</span> <?php echo $num; ?>

			</td>
			
			<td style="text-align:center; width:33.333%;">
							
			<?php
			if($idassu!=NULL)
			{
			?>
				<p class="patientId"><span>Insurance type:</span>
				<?php
				
				$resultAssu=$connexion->prepare('SELECT * FROM assurances a WHERE id_assurance=:assu');
				$resultAssu->execute(array(
				'assu'=>$_GET['idassu']
				)); 
				
				if($ligneAssu=$resultAssu->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
				{
					echo $ligneAssu->nomassurance.' ('.$_GET['billpercent'].'%)';
				}
		
			}else{
			?>
				<p class="patientId"><span>Insurance type:</span> <?php echo "Privé"; ?>
					
			<?php
			}
			?>
				<p class="patientId"><span>Bill number:</span> <?php echo $_GET['numbill']; ?>
				
			</td>
			
			<td style="font-size:18px; padding-right:20px; text-align:right; width:33.333%;">
				<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71).' Facturation' ?>: </span><?php echo $annee;?>
				
				<input size="25px" type="hidden" id="today" name="today" value="<?php echo $annee;?>"/>
			</td>
		</tr>

	</table>

	<form method="post" action="addupdatepresta_hosp.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_GET['manager'];?>&idassu=<?php echo $_GET['idassu'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idhosp=<?php echo $_GET['idhosp'];?>&numbill=<?php echo $_GET['numbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&nomassurance=<?php echo $_GET['nomassurance'];?>&billpercent=<?php echo $_GET['billpercent'];?>&datefacture=<?php echo $_GET['datefacture'];?>" onsubmit="return controlFormCategoBillHosp(this)" enctype="multipart/form-data">
		
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
							<td style="padding:0 20px; text-align:center"><label for="roomhosp"><?php echo 'Numero chambre'; ?></label></td>
								
							<td style="padding:0 20px; text-align:center"><label for="lithosp"><?php echo 'Numero du lit'; ?></label></td>
								
						</tr>	   

						<tr>	
							<td style="text-align:center">
								
									<input type="text" name="roomhosp" id="roomhosp" value="<?php echo $_GET['numroom'];?>" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:50px;" readonly="readonly"/>
									
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
			
			
			$resultHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:idhosp AND ph.id_factureHosp IS NOT NULL AND ph.numero=:num');

			$resultHosp->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			
			))or die( print_r($connexion->errorInfo()));
			
			$resultHosp->setFetchMode(PDO::FETCH_OBJ);

			$comptHosp=$resultHosp->rowCount();



	
			$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.numero=:num AND mc.id_factureMedConsu!="" AND mc.id_hospMed!=:idhosp ORDER BY mc.id_medconsu');		
			$resultMedConsult->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsult=$resultMedConsult->rowCount();



			$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.soinsfait=0 AND mi.numero=:num AND mi.id_factureMedInf!="" AND mi.id_hospInf=:idhosp ORDER BY mi.id_medinf');		
			$resultMedInf->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

			$comptMedInf=$resultMedInf->rowCount();



			$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.examenfait=0 AND ml.numero=:num AND ml.id_factureMedLabo!="" AND ml.id_hospLabo=:idhosp ORDER BY ml.id_medlabo');		
			$resultMedLabo->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

			$comptMedLabo=$resultMedLabo->rowCount();

			

			$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.radiofait=0 AND mr.numero=:num AND mr.id_factureMedRadio!="" AND mr.id_hospRadio=:idhosp ORDER BY mr.id_medradio');		
			$resultMedRadio->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

			$comptMedRadio=$resultMedRadio->rowCount();

			
			
			$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.numero=:num AND mco.id_factureMedConsom!="" AND mco.id_hospConsom=:idhosp ORDER BY mco.id_medconsom');		
			$resultMedConsom->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsom=$resultMedConsom->rowCount();

			
			
			$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedMedoc!="" AND mdo.id_hospMedoc=:idhosp ORDER BY mdo.id_medmedoc');		
			$resultMedMedoc->execute(array(
			'num'=>$_GET['num'],
			'idhosp'=>$_GET['idhosp']
			));
			
			$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

			$comptMedMedoc=$resultMedMedoc->rowCount();


			if($comptHosp!=0 OR $comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0)
			{
			?>
				
				<td>
				<?php
				if(!isset($_GET['previewprint']))
				{
				?>
					<a style="padding:10px 40px;" href="<?php echo 'formModifierBillHosp.php?num='.$_GET['num'].'&manager='.$_GET['manager'].'&idhosp='.$_GET['idhosp'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&nomassurance='.$_GET['nomassurance'].'&billpercent='.$_GET['billpercent'].'&numbill='.$_GET['numbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&datefacture='.$_GET['datefacture'].''?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="showpreviewbtn" id="showpreviewbtn"><?php echo getString(220) ?></a>
				<?php
				}
				
				?>
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
		<div id="divCatego" style="margin:20px auto 0; text-align:center">

			<table align="center" style="display:inline;" id="catego">
				<tr>
					<td style="padding:5px;">
						<span id="services" class="btn-large" onclick="ShowMore('services')"><?php echo "Add Services";?></span>
					</td>
					<?php

					$resultatsPrestaConsultation=$connexion->query('SELECT *FROM categopresta_ins cp, '.$presta_assu.' p  WHERE p.id_categopresta!=2 AND (cp.id_categopresta=3 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=3) OR (cp.id_categopresta=12 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=12) OR (cp.id_categopresta=13 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=13) GROUP BY cp.id_categopresta ORDER BY cp.id_categopresta');

					$resultatsPrestaConsultation->setFetchMode(PDO::FETCH_OBJ);
					
					while($ligneCatPrestaConsultation=$resultatsPrestaConsultation->fetch())
					{
					?>									
						<td style="padding:5px;">
							<span id="<?php echo $ligneCatPrestaConsultation->id_categopresta;?>" class="btn-large" onclick="ShowMore('<?php echo $ligneCatPrestaConsultation->id_categopresta;?>')"><?php echo getCatego($ligneCatPrestaConsultation->id_categopresta);?></span>								
						</td>
					<?php
					}
					
					?>									
						<td style="padding:5px;">
							<span id="22" class="btn-large" onclick="ShowMore('22')"><?php echo 'Consommables';?></span>								
						</td>
													
						<td style="padding:5px;">
							<span id="23" class="btn-large" onclick="ShowMore('23')"><?php echo 'Medicaments';?></span>								
						</td>
					
						
				</tr>
				
			</table>
			
			<table id="showService" align="center" style="margin:20px auto; display:none;">
				<tr>
					<td style="padding:5px;text-align:center;">
					
						<select name="categorie" id="categorie" onchange="ShowAddbtn('categorie')">

							<option value='0'><?php echo 'Select services here...'; ?></option>
					<?php

					$resultatsPrestaConsultation=$connexion->query('SELECT *FROM categopresta_ins cp, '.$presta_assu.' p WHERE cp.id_categopresta!=1 AND cp.id_categopresta!=2 AND cp.id_categopresta!=3 AND cp.id_categopresta!=12 AND cp.id_categopresta!=13 AND p.id_categopresta=cp.id_categopresta GROUP BY p.id_categopresta ORDER BY cp.nomcategopresta');
						
					$resultatsPrestaConsultation->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
						while($ligneCatPrestaConsultation=$resultatsPrestaConsultation->fetch())//on recupere la liste des éléments
						{
						?>
							<option value="<?php echo $ligneCatPrestaConsultation->id_categopresta;?>">
								<?php echo $ligneCatPrestaConsultation->nomcategopresta?>
							</option>
						<?php
						}
						?>
						</select>
						
			
						<script src="jQuery.js"></script>
						<script>
						$(function(){
							$("#categorie").change(function(){
						var serviceChoisi='categorie='+$(this).val();
							//alert(serviceChoisi);
							$.ajax({
								url:"tablepresta_hosp.php?<?php echo 'num='.$num;?>",
								type:"POST",
								data:serviceChoisi,
								
								success:function(resultat)
								{
									
									// alert(resultat);
									$('#divViewServ').html(resultat);
								}
								});
							});
						});
						</script>
					

					</td>
				</tr>
			</table>
		
		</div>
		
		<?php
		}
		?>
		<div id="divViewServ" style="margin:40px auto 0; width:50%; display:none;">


		</div>
	
		<div id="divViewInf" style="margin:40px auto 0; width:50%; display:none;">
			
			<h2 style='margin-top:20px;'>Nursing care</h2>
		<!--
		<?php
		
		$resInf=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=3 ORDER BY p.id_prestation DESC');
		
		$resInf->setFetchMode(PDO::FETCH_OBJ);
	
		$comptInf=$resInf->rowCount();
		
		?>
		<div style="overflow:auto; height:600px;">
			<table class='tablesorter' id='inf'>
				<thead> 
					<tr>
						<th>Actions</th>
						<th>Prestations</th>
						<th>Prix Unitaire</th>
					</tr> 
				</thead>
				
				<tbody>
				<?php
				while($ligneInf=$resInf->fetch(PDO::FETCH_OBJ))
				{
					if($ligneInf->nompresta!="")
					{
						$prestaInf=$ligneInf->nompresta;
					}else{
						$prestaInf=$ligneInf->namepresta;
					}
				?>
					<tr style='text-align:center'> 
						<td><input type='checkbox' name='checkprestaInf[]' id='checkprestaInf' value='<?php echo $ligneInf->id_prestation;?>'/></td>
						<td><?php echo $prestaInf;?></td>
						<td>
						<?php
						
							if($ligneInf->prixpresta==-1)
							{
								echo "";							
							}else{					
								echo $ligneInf->prixpresta;		
							}
						?>
						</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
		</div>
		-->					
			<table class="tablesorter tablesorter2" style='margin-top:10px;'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nouvelle prestation</td>
						<td>Prix unitaire</td>
					</tr>
					<?php
					for($i=0;$i<=4;$i++)
					{
					?>
					<tr style='text-align:center'> 
						<td>
							<input type='text' name='autreprestaInf[]' id='autreprestaInf' class='autreprestaInf'/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaInf[]' id='autreprixprestaInf' class='autreprixprestaInf' style='width:70px'/>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>

		</div>
		
		<div id="divViewLab" style="margin:40px auto 0; width:50%; display:none;">
		
			<h2 style='margin-top:20px;'>Laboratory</h2>
		<!--
		<?php
		
		$resLab=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=12 ORDER BY p.nompresta');
			
		$resLab->setFetchMode(PDO::FETCH_OBJ);
		
		$comptLab=$resLab->rowCount();
		
		?>
		<div style="overflow:auto; height:600px;">
			<table class='tablesorter' id='lab'>
				<thead> 
					<tr>
						<th>Actions</th>
						<th>Prestations</th>
						<th>Prix Unitaire</th>
					</tr> 
				</thead>
				
				<tbody>
				<?php
				while($ligneLab=$resLab->fetch(PDO::FETCH_OBJ))
				{
				?>
					<tr style='text-align:center'> 
						<td><input type='checkbox' name='checkprestaLab[]' id='checkprestaLab' value='<?php echo $ligneLab->id_prestation;?>'/></td>
						<td>
						<?php
							if($ligneLab->nompresta!="")
							{
								echo $ligneLab->nompresta;
							}else{
								echo $ligneLab->namepresta;
							}
						?>
						</td>
						<td>
						<?php
						
							if($ligneLab->prixpresta==-1)
							{
								echo "";							
							}else{					
								echo $ligneLab->prixpresta;		
							}
						?>
						</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
		</div>
		-->
			
			<table class="tablesorter tablesorter2" style='margin-top:10px;'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nouvelle prestation</td>
						<td>Prix unitaire</td>
					</tr>
					<?php
					for($i=0;$i<=4;$i++)
					{
					?>
					<tr style='text-align:center'> 
						<td>
							<input type='text' name='autreprestaLab[]' id='autreprestaLab' class='autreprestaLab' value=''/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaLab[]' id='autreprixprestaLab' class='autreprixprestaLab' style='width:70px; text-align:center'/>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			
		</div>
		
		<div id="divViewRad" style="margin:40px auto 0; width:50%; display:none;">
			
			<h2 style='margin-top:20px;'>Radiologie</h2>
		<!--
		<?php
		
		$resRad=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=13 ORDER BY p.nompresta');
			
		$resRad->setFetchMode(PDO::FETCH_OBJ);
		
		$comptRad=$resRad->rowCount();
		
		?>
		<div style="overflow:auto; height:600px;">
			<table class='tablesorter' id='lab'>
				<thead> 
					<tr>
						<th>Actions</th>
						<th>Prestations</th>
						<th>Prix Unitaire</th>
					</tr> 
				</thead>
				
				<tbody>
				<?php
				while($ligneRad=$resRad->fetch(PDO::FETCH_OBJ))
				{
				?>
					<tr style='text-align:center'> 
						<td><input type='checkbox' name='checkprestaRad[]' id='checkprestaRad' value='<?php echo $ligneRad->id_prestation;?>'/></td>
						<td>
						<?php
							if($ligneRad->nompresta!="")
							{
								echo $ligneRad->nompresta;
							}else{
								echo $ligneRad->namepresta;
							}
						?>
						</td>
						<td>
						<?php
						
							if($ligneRad->prixpresta==-1)
							{
								echo "";							
							}else{					
								echo $ligneRad->prixpresta;		
							}
						?>
						</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
		</div>
		-->
			
			<table class="tablesorter tablesorter2" style='margin-top:10px;'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nouvelle prestation</td>
						<td>Prix unitaire</td>
					</tr>
					<?php
					for($i=0;$i<=4;$i++)
					{
					?>
					<tr style='text-align:center'> 
						<td>
							<input type='text' name='autreprestaRad[]' id='autreprestaRad' class='autreprestaRad' value=''/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaRad[]' id='autreprixprestaRad' class='autreprixprestaRad' style='width:70px; text-align:center'/>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			
		</div>

		<div id="divViewConsom" style="margin:40px auto 0; width:50%; display:none;">
			
			<h2 style='margin-top:20px;'>Consommables</h2>
			
			<table class="tablesorter tablesorter2" style='margin-top:20px; width:50%;'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nom du consommable</td>
						<td>Prix Unitaire</td>
						<td>Quantité</td>
					</tr>
					<?php
					for($i=0;$i<=4;$i++)
					{
					?>
					<tr style='text-align:center'> 
						<td>
							<input type='text' name='autreprestaConsom[]' id='autreprestaConsom' class='autreprestaConsom' value=''/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaConsom[]' id='autreprixprestaConsom' class='autreprixprestaConsom' style='width:70px; text-align:center'/>
						</td>
						
						<td>
							<input type='text' name='qteprestaConsom[]' id='qteprestaConsom' class='qteprestaConsom' value='1' style='width:70px; text-align:center'/>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			
		</div>
		
		<div id="divViewMedoc" style="margin:40px auto 0; width:50%; display:none;">
		
			<h2 style='margin-top:20px;'>Medicaments</h2>
			
			<table class="tablesorter tablesorter2" style='margin-top:10px; width:50%;'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nom du medicament</td>
						<td>Prix Unitaire</td>
						<td>Quantité</td>
					</tr>
					<?php
					for($i=0;$i<=4;$i++)
					{
					?>
					<tr style='text-align:center'> 
						<td>
							<input type='text' name='autreprestaMedoc[]' id='autreprestaMedoc' class='autreprestaMedoc' value=''/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaMedoc[]' id='autreprixprestaMedoc' class='autreprixprestaMedoc' style='width:70px; text-align:center'/>
						</td>
						
						<td>
							<input type='text' name='qteprestaMedoc[]' id='qteprestaMedoc' class='qteprestaMedoc' value='1' style='width:70px; text-align:center'/>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			
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



		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.soinsfait=1 AND mi.numero=:num AND mi.id_factureMedInf="" AND mi.id_hospInf=:idhosp ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'num'=>$_GET['num'],
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();



		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.examenfait=0 AND ml.numero=:num AND ml.id_factureMedLabo="" AND ml.id_hospLabo=:idhosp ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'num'=>$_GET['num'],
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();



		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.radiofait=0 AND mr.numero=:num AND mr.id_factureMedRadio="" AND mr.id_hospRadio=:idhosp ORDER BY mr.id_medradio');		
		$resultMedRadio->execute(array(
		'num'=>$_GET['num'],
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();



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

		
		
		
		
		if($comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedRadio!=0 OR isset($_GET['sortie']))
		{
		?>
		
			<form method="post" action="<?php if(isset($_GET['sortie'])){ echo 'printBill_hospReport.php?';}else{ if(isset($_GET['infShow'])){ echo 'newTest.php?';}else{ echo 'printBill_hosp.php?';}}?>inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idassu=<?php echo $_GET['idassu'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&numbill=<?php echo $_GET['numbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" <?php if(!isset($_GET['infShow'])){ echo 'onsubmit="return controlFormCategoBill(this)"';}?> enctype="multipart/form-data">
			
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
			
			if($comptMedConsult!=0)
			{
		?>
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:50%"><?php echo getString(39);?></th>
							<th style="width:20%"><?php echo getString(145);?></th>
							<th style="width:20%"><?php echo getString(38);?></th>
							<th style="width:50%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						$i=1;
						
						while($ligneMedConsult=$resultMedConsult->fetch())
						{
							
						?>
						<tr style="text-align:center;">
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
							
								echo '<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta'.$i.'" class="idpresta" value="'.$lignePresta->id_prestation.'"/>';
								
								echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu'.$i.'" value=""/>';
										
								if($lignePresta->namepresta!='')
								{

									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td>
								
								<input type="text" name="prixprestaConsu[]" style="width:100px;" id="prixprestaConsu<?php echo $i;?>" class="prixprestaConsu" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>"/>
								
							</td>
								<?php
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td>
								
								<input type="text" name="prixprestaConsu[]" style="width:100px; text-align:center" id="prixprestaConsu<?php echo $i;?>" class="prixprestaConsu" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>"/>
								
							</td>
								<?php
								}
							}
							
							if($ligneMedConsult->id_prestationConsu==NULL AND $ligneMedConsult->prixautreConsu==0)
							{
								echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu'.$i.'" value="'.$ligneMedConsult->autreConsu.'"/>';
								
								echo $ligneMedConsult->autreConsu.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaConsu[]" style="width:100px; text-align:center" id="prixprestaConsu'.$i.'" class="prixprestaConsu" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedConsult->id_medconsu.'" style="width:100px;display:none; text-align:center" id="id_medconsu" value="'.$ligneMedConsult->id_medconsu.'"/>
									
										<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta'.$i.'" value="0"/>
									</td>';

							}else{
							
								if($ligneMedConsult->id_prestationConsu==NULL AND $ligneMedConsult->prixautreConsu!=0)
								{
									
									echo '<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta'.$i.'" value="0"/>';
								
									echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu'.$i.'" value="'.$ligneMedConsult->autreConsu.'"/>';
								
									echo $ligneMedConsult->autreConsu.'</td>
									<td>
										<input type="text" name="prixprestaConsu[]" style="width:100px; text-align:center" id="prixprestaConsu'.$i.'" class="prixprestaConsu" value="'.$ligneMedConsult->prixautreConsu.'"/>
									
									</td>';
								}
							}
							?>
							
							<td>
								<input type="text" name="percentConsu[]" class="percentConsu" id="percentConsu<?php echo $i;?>" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
							
								<input type="hidden" name="idmedConsu[]" class="idmedConsu"  id="idmedConsu<?php echo $i;?>"style="width:50px; text-align:center" value="<?php echo $ligneMedConsult->id_medconsu;?>"/>
							
							</td>
							
							<td>
								<a href="categoriesbill_hospmodifier.php?deleteMedConsu=<?php echo $ligneMedConsult->id_medconsu;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&numbill=<?php echo $_GET['numbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
					
							</td>
						</tr>
						<?php
							$i++;
						}
						?>		
					</tbody>
				</table>
			<?php
			}
			
			
			if($comptMedInf != 0)
			{
			?>		
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:50%"><?php echo getString(98);?></th>
							<th style="width:20%"><?php echo getString(145);?></th>
							<th style="width:20%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						while($ligneMedInf=$resultMedInf->fetch())
						{
						?>
						<tr style="text-align:center;">
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
							
							<td>
								
								<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>"/>
								
							</td>
								<?php
								}else{								
									echo $lignePresta->nompresta;	
								?>
							</td>
							
							<td>
								
								<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>"/>
								
							</td>
								<?php
								}
							}
							
							if($ligneMedInf->id_prestation==NULL AND $ligneMedInf->prixautrePrestaM==0)
							{
								echo '<input type="text" name="autreInf[]" style="width:100px;display:none; text-align:center" id="autreInf" value="0"/>';
									
								echo $ligneMedInf->autrePrestaM.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="" placeholder="Tarrif ici..."/>
										
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
									
									echo '<td>
									<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="'.$ligneMedInf->prixautrePrestaM.'"/>
										
									</td>';
								}
							}
							?>
							</td>
							
							<td>
								<input type="text" id="percentInf" name="percentInf[]" class="percentInf" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								<input type="hidden" id="idmedInf" name="idmedInf[]" style="width:50px; text-align:center" value="<?php echo $ligneMedInf->id_medinf;?>"/>
							
							</td>
							
							<td>
								<a href="categoriesbill_hospmodifier.php?deleteMedInf=<?php echo $ligneMedInf->id_medinf;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&numbill=<?php echo $_GET['numbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
						}
						?>		
					</tbody>
				</table>
		<?php
			}
			
			
			if($comptMedLabo != 0)
			{
		?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:50%"><?php echo getString(99);?></th>
							<th style="width:20%"><?php echo getString(145);?></th>
							<th style="width:20%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						while($ligneMedLabo=$resultMedLabo->fetch())
						{
						?>
						<tr style="text-align:center;">
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
							
							<td>
								
								<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }?>"/>
								
							</td>
								<?php
								
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td>
								
								<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }?>"/>
								
							</td>
								<?php
								}
							}
							
							if($ligneMedLabo->id_prestationExa==NULL AND $ligneMedLabo->prixautreExamen==0)
							{
								echo '<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab" value="'.$ligneMedLabo->autreExamen.'"/>';
								
								echo $ligneMedLabo->autreExamen.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedLabo->id_medlabo.'" style="width:100px;display:none; text-align:center" id="id_medlabo" value="'.$ligneMedLabo->id_medlabo.'"/>
										
										<input type="hidden" name="idprestaLab[]" style="width:100px; text-align:center" id="idprestaLab" value="0"/>
									</td>';
							}else{

								if($ligneMedLabo->id_prestationExa==NULL AND $ligneMedLabo->prixautreExamen!=0)
								{
									echo $ligneMedLabo->autreExamen.'<input type="hidden" name="idprestaLab[]" style="width:100px;display:none; text-align:center" id="idprestaLab" value="0"/>
									</td>';
									echo '<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab" value="'.$ligneMedLabo->autreExamen.'"/>';
								
									echo '<td>
									<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="'.$ligneMedLabo->prixautreExamen.'"/>';
								}
							}
							?>
							</td>
							
							<td>
								<input type="text" id="percentLab" name="percentLab[]" class="percentLab" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								<input type="hidden" id="idmedLab" name="idmedLab[]" style="width:50px; text-align:center" value="<?php echo $ligneMedLabo->id_medlabo;?>"/>
							</td>
							
							<td>
								<a href="categoriesbill_hospmodifier.php?deleteMedLabo=<?php echo $ligneMedLabo->id_medlabo;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&numbill=<?php echo $_GET['numbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
							
						</tr>
					<?php
					}
					?>		
					</tbody>
				</table>
		<?php
			}
				
			
			if($comptMedRadio != 0)
			{
		?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:50%"><?php echo 'Radiologie tests';?></th>
							<th style="width:20%"><?php echo getString(145);?></th>
							<th style="width:20%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						while($ligneMedRadio=$resultMedRadio->fetch())
						{
						?>
						<tr style="text-align:center;">
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
							
							<td>
								
								<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }?>"/>
								
							</td>
								<?php
								
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td>
								
								<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }?>"/>
								
							</td>
								<?php
								}
							}
							
							if($ligneMedRadio->id_prestationRadio==NULL AND $ligneMedRadio->prixautreRadio==0)
							{
								echo '<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab" value="'.$ligneMedRadio->autreRadio.'"/>';
								
								echo $ligneMedRadio->autreRadio.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedRadio->id_medradio.'" style="width:100px;display:none; text-align:center" id="id_medradio" value="'.$ligneMedRadio->id_medradio.'"/>
										
										<input type="hidden" name="idprestaRad[]" style="width:100px; text-align:center" id="idprestaRad" value="0"/>
									</td>';
							}else{

								if($ligneMedRadio->id_prestationRadio==NULL AND $ligneMedRadio->prixautreRadio!=0)
								{
									echo $ligneMedRadio->autreRadio.'<input type="hidden" name="idprestaRad[]" style="width:100px;display:none; text-align:center" id="idprestaRad" value="0"/>
									</td>';
									echo '<input type="text" name="autreRad[]" style="width:100px;display:none; text-align:center" id="autreRad" value="'.$ligneMedRadio->autreRadio.'"/>';
								
									echo '<td>
									<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="'.$ligneMedRadio->prixautreRadio.'"/>';
								}
							}
							?>
							</td>
							
							<td>
								<input type="text" id="percentRad" name="percentRad[]" class="percentRad" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								<input type="hidden" id="idmedRad" name="idmedRad[]" style="width:50px; text-align:center" value="<?php echo $ligneMedRadio->id_medradio;?>"/>
							</td>
							
							<td>
								<a href="categoriesbill_hospmodifier.php?deleteMedRadio=<?php echo $ligneMedRadio->id_medradio;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&numbill=<?php echo $_GET['numbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>							
						</tr>
						<?php
						}
						?>		
					</tbody>
				</table>
		<?php
			}
				
			if($comptMedConsom != 0)
			{
		?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:80%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:10%">Date</th>
							<th style="width:<?php if($comptidI!=0){ echo '35%';}else{ echo '40%';}?>"><?php echo getString(214);?></th>
							<th style="width:10%"><?php echo getString(145);?></th>
							<th style="width:<?php if($comptidI!=0){ echo '25%';}else{ echo '20%';}?>"><?php echo getString(215);?></th>
							<th style="width:10%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						$i=0;
						
						while($ligneMedConsom=$resultMedConsom->fetch())
						{
						?>
						<tr style="text-align:center;">
							<td>
								<?php echo $ligneMedConsom->datehosp;?>
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

							
							
							$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assuConsom.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedConsom->id_prestationConsom
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($comptPresta==0)
							{
								$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
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
							
							<td>								
								<input type="text" name="prixprestaConsom[]" style="width:100px;" id="prixprestaConsom"  class="prixprestaConsom"  value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo 0;}?>" onchange="ShowSaveConsom(<?php echo $i;?>)"/>
								
							</td>
								<?php
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaConsom[]" style="width:100px; text-align:center" id="prixprestaConsom"  class="prixprestaConsom"  value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo 0;}?>" onchange="ShowSaveConsom(<?php echo $i;?>)"/>
							</td>
								<?php
								}
								
							}
							
							if($ligneMedConsom->id_prestationConsom==NULL AND $ligneMedConsom->prixautreConsom==0)
							{
								echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom" value="'.$ligneMedConsom->autreConsom.'"/>';

								echo $ligneMedConsom->autreConsom.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/>
								</td>
								<td>
									<input type="text" name="prixprestaConsom[]" style="width:100px; text-align:center" id="prixprestaConsom" class="prixprestaConsom"  value="" placeholder="Tarrif ici..." onchange="ShowSaveConsom('.$i.')"/>
									
									<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom" value="0"/>
								</td>';
							}else{

								if($ligneMedConsom->id_prestationConsom==NULL AND $ligneMedConsom->prixautreConsom!=0)
								{
									echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom" value="'.$ligneMedConsom->autreConsom.'"/>';
									
									echo $ligneMedConsom->autreConsom.'<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom" value="0"/>
									</td>';
									echo '
									<td>
										<input type="text" name="prixprestaConsom[]" style="width:100px; text-align:center" id="prixprestaConsom"  class="prixprestaConsom"  value="'.$ligneMedConsom->prixautreConsom.'" onchange="ShowSaveConsom('.$i.')"/>
										
									</td>';
								}
								
							}
							?>
							
							<td>
								<span type="submit" id="qteConsomMoins<?php echo $i;?>" name="qteConsomMoins<?php echo $i;?>" class="qteConsomMoins btn" style="display:<?php if($ligneMedConsom->qteConsom ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteConsom(<?php echo $i;?>)"/>-</span>
								
								<input type="text" id="quantityConsom<?php echo $i;?>" name="quantityConsom[]" class="quantityConsom" style="width:50px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedConsom->qteConsom;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteConsom<?php echo $i;?>" name="qteConsom[]" class="qteConsom" style="width:50px;margin-left:0px;" value="<?php echo $i;?>"/>
							
								<span type="submit" id="qteConsomPlus<?php echo $i;?>" name="qteConsomPlus<?php echo $i;?>" class="qteConsomPlus btn" onclick="PlusQteConsom(<?php echo $i;?>)"/>+</span>

							</td>
							
							<td>
								<input type="text" id="percentConsom" name="percentConsom[]" class="percentConsom" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								<input type="text" id="idmedConsom" name="idmedConsom[]" style="width:30px;display:none; text-align:center" value="<?php echo $ligneMedConsom->id_medconsom;?>"/>
							</td>
							
							<td>
								<a href="categoriesbill_hospmodifier.php?deleteMedConsom=<?php echo $ligneMedConsom->id_medconsom;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&numbill=<?php echo $_GET['numbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td></td>
							<td></td>
							<td colspan=2 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteConsomBtn<?php echo $ligneMedConsom->id_medconsom;?>" id="addQteConsomBtn<?php echo $i;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							
							</td>
							<td></td>
							<td></td>
						</tr>
						
						<tr>
							<td colspan=6 style="background:#eee;">
							
							</td>
						</tr>
						<?php
						}
							$i++;
						}
						?>							
				</tbody>
				</table>
		<?php
			}

			if($comptMedMedoc != 0)
			{
		?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:80%; margin-top:10px;"> 
				<thead> 
					<tr>
						<th style="width:10%">Date</th>
						<th style="width:<?php if($comptidI!=0){ echo '35%';}else{ echo '40%';}?>"><?php echo getString(216);?></th>
						<th style="width:10%"><?php echo getString(145);?></th>
						<th style="width:<?php if($comptidI!=0){ echo '25%';}else{ echo '20%';}?>"><?php echo getString(215);?></th>
						<th style="width:10%"><?php echo getString(38);?></th>
						<th style="width:10%"><?php echo 'Action';?></th>
					</tr> 
				</thead> 


				<tbody>
						<?php
						$j=0;
						
						while($ligneMedMedoc=$resultMedMedoc->fetch())
						{
						?>
						<tr style="text-align:center;">
							<td>
								<?php echo $ligneMedMedoc->datehosp;?>
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
								
								
								$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assuMedoc.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneMedMedoc->id_prestationMedoc
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($comptPresta==0)
								{
									$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation=:prestaId');
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
							
							<td>								
								<input type="text" name="prixprestaMedoc[]" style="width:100px; text-align:center" id="prixprestaMedoc" class="prixprestaMedoc" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveMedoc(<?php echo $j;?>)"/>
								
							</td>
								<?php
								}else{
									echo $lignePresta->nompresta;
									?>
							</td>
							
							<td>
								
								<input type="text" name="prixprestaMedoc[]" style="width:100px; text-align:center" id="prixprestaMedoc" class="prixprestaMedoc" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>" onchange="ShowSaveMedoc(<?php echo $j;?>)"/>
								
							</td>
								<?php
								}
								
							}
							
							if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc==0)
							{
								echo '<input type="text" name="autreMedoc[]" style="width:100px;display:none; text-align:center" id="autreMedoc" value="'.$ligneMedMedoc->autreMedoc.'"/>';

								echo $ligneMedMedoc->autreMedoc.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaMedoc[]" style="width:100px; text-align:center" id="prixprestaMedoc" class="prixprestaMedoc" value="" placeholder="Tarrif ici..." onchange="ShowSaveMedoc('.$j.')"/>
										
										<input type="text" name="idprestaMedoc[]" style="width:100px;display:none; text-align:center" id="idprestaMedoc" value="0"/>
									</td>';

							}else{

								if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc!=0)
								{
									echo $ligneMedMedoc->autreMedoc.'<input type="text" name="idprestaMedoc[]" style="width:100px;display:none;" id="idprestaMedoc" value="0"/>
									
									</td>';
									
									echo '<input type="text" name="autreMedoc[]" style="width:100px;display:none;" id="autreMedoc" value="'.$ligneMedMedoc->autreMedoc.'"/>';	
									
									echo '<td>
									<input type="text" name="prixprestaMedoc[]" style="width:100px;" id="prixprestaMedoc" class="prixprestaMedoc" value="'.$ligneMedMedoc->prixautreMedoc.'" onchange="ShowSaveMedoc('.$j.')"/>';
								}
								
							}
							?>
							</td>
							
							<td>						
								<span type="submit" id="qteMedocMoins<?php echo $j;?>" name="qteMedocMoins<?php echo $j;?>" class="qteMedocMoins btn" style="display:<?php if($ligneMedMedoc->qteMedoc ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteMedoc(<?php echo $j;?>)"/>-</span>	
								<input type="text" id="quantityMedoc<?php echo $j;?>" name="quantityMedoc[]" class="quantityMedoc" style="width:30px;margin-left:0px;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $ligneMedMedoc->qteMedoc;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/>
							
								<input type="hidden" id="qteMedoc<?php echo $j;?>" name="qteMedoc[]" class="qteMedoc" style="width:50px;margin-left:0px;" value="<?php echo $j;?>"/>
							
								<span type="submit" id="qteMedocPlus<?php echo $j;?>" name="qteMedocPlus<?php echo $j;?>" class="qteMedocPlus btn" onclick="PlusQteMedoc(<?php echo $j;?>)"/>+</span>
							
							</td>
							
							<td>
								<input type="text" id="percentMedoc" name="percentMedoc[]" class="percentMedoc" style="width:30px; text-align:center;<?php if($comptidI!=0){ echo 'background:#F8F8F8;';}?>" value="<?php echo $bill;?>" <?php if($comptidI!=0){ echo 'readonly="readonly"';}?>/> %
								
								<input type="text" id="idmedMedoc" name="idmedMedoc[]" style="width:30px;display:none; text-align:center" value="<?php echo $ligneMedMedoc->id_medmedoc;?>"/>
							</td>
							
							<td>
								<a href="categoriesbill_hospmodifier.php?deleteMedMedoc=<?php echo $ligneMedMedoc->id_medmedoc;?>&inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&numbill=<?php echo $_GET['numbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
						if($comptidI!=0)
						{
						?>
						<tr>
							<td></td>
							<td></td>
							<td colspan=2 style="border:0 20px 0 0">
							
								<input type="submit" name="addQteMedocBtn<?php echo $ligneMedMedoc->id_medmedoc;?>" id="addQteMedocBtn<?php echo $j;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
							</td>
							<td></td>
							<td></td>
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
		var catego =document.getElementById('categorie').value;
		
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
		
		
		if(more==22)
		{
			document.getElementById('divViewConsom').style.display='inline';
			
		}else{
		
			document.getElementById('divViewConsom').style.display='none';
		}
		
		
		if(more==23)
		{
			document.getElementById('divViewMedoc').style.display='inline';
				
		}else{		
			document.getElementById('divViewMedoc').style.display='none';
		}	
		
		if(catego!="0" || more==3 || more==12 || more==13 || more==22 || more==23)
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
			document.getElementById('divMenuUser').style.display='inline-block';
			
			document.getElementById('listOn').style.display='inline';
			document.getElementById('newUser').style.display='inline';
			
			document.getElementById('listOff').style.display='none';
			
			document.getElementById('divMenuMsg').style.display='none';
			document.getElementById('divListe').style.display='none';
		
		}
		
		if( list =='Msg')
		{
			document.getElementById('divListe').style.display='none';
			document.getElementById('divMenuMsg').style.display='inline-block';
			document.getElementById('divMenuUser').style.display='none';
			
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
			document.getElementById('reception').style.display='inline-block';
			document.getElementById('EnvoiMsg').style.display='inline-block';
			document.getElementById('MsgEnvoye').style.display='inline-block';
			document.getElementById('MsgRecu').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
		if( list =='MsgEnvoye')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('MsgEnvoye').style.display='none';
			document.getElementById('EnvoiMsg').style.display='inline-block';
			document.getElementById('MsgRecu').style.display='inline-block';
			document.getElementById('envoye').style.display='inline-block';
		}
		
		if( list =='EnvoiMsg')
		{
			document.getElementById('formMsg').style.display='inline-block';
			document.getElementById('MsgEnvoye').style.display='inline-block';
			document.getElementById('MsgRecu').style.display='inline-block';
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
		<p style="margin:0"><span style="color:#a00000">Medical FIle®</span> is a product of <span style="font-style:bold;">Innovate Solutions Ltd</span>. ©2022-2016 All rights reserved.</p>
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
	
</body>
</html>