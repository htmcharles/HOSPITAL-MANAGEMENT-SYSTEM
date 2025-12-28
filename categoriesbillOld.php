<?php 
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


	$annee = date('d').'-'.date('M').'-'.date('Y');
	

if(!isset($_GET['doneBill']) AND isset($_GET['deletebill']))
{
	
	$idBill=$_GET['deletebill'];

	$deleteBilling=$connexion->prepare('DELETE FROM bills WHERE id_bill=:idBill');
		
	$deleteBilling->execute(array(
	'idBill'=>$idBill
	
	))or die($deleteBilling->errorInfo());
	
} 

if(isset($_GET['deleteMedConsu']))
{

	$id_medC = $_GET['deleteMedConsu'];
	
	$deleteConsu=$connexion->prepare('DELETE FROM med_consult WHERE id_medconsu=:id_medC');
	
	$deleteConsu->execute(array(
	'id_medC'=>$id_medC
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un service");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok";</script>';
	
}

if(isset($_GET['deleteMedSurge']))
{

	$id_medS = $_GET['deleteMedSurge'];
	
	$deleteSurge=$connexion->prepare('DELETE FROM med_surge WHERE id_medsurge=:id_medS');
	
	$deleteSurge->execute(array(
	'id_medS'=>$id_medS
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un acte");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok";</script>';
	
}

if(isset($_GET['deleteMedInf']))
{

	$id_medI = $_GET['deleteMedInf'];
	
	$deleteInf=$connexion->prepare('DELETE FROM med_inf WHERE id_medinf=:id_medI');
	
	$deleteInf->execute(array(
	'id_medI'=>$id_medI
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un soins");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok";</script>';
	
}

if(isset($_GET['deleteMedLabo']))
{

	$id_medL= $_GET['deleteMedLabo'];
	
	$deleteLabo=$connexion->prepare('DELETE FROM med_labo WHERE id_medlabo=:id_medL');
	
	$deleteLabo->execute(array(
	'id_medL'=>$id_medL
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un examen");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok";</script>';
	
}

if(isset($_GET['deleteMedRadio']))
{

	$id_medX= $_GET['deleteMedRadio'];
	
	$deleteRadio=$connexion->prepare('DELETE FROM med_radio WHERE id_medradio=:id_medX');
	
	$deleteRadio->execute(array(
	'id_medX'=>$id_medX
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une radio");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok";</script>';
	
}

if(isset($_GET['deleteMedConsom']))
{

	$id_medCo= $_GET['deleteMedConsom'];
	
	$deleteConsom=$connexion->prepare('DELETE FROM med_consom WHERE id_medconsom=:id_medCo');
	
	$deleteConsom->execute(array(
	'id_medCo'=>$id_medCo
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un consommable");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok";</script>';
	
}

if(isset($_GET['deleteMedMedoc']))
{

	$id_medMe= $_GET['deleteMedMedoc'];
	
	$deleteMedoc=$connexion->prepare('DELETE FROM med_medoc WHERE id_medmedoc=:id_medMe');
	
	$deleteMedoc->execute(array(
	'id_medMe'=>$id_medMe
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un medicament");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok";</script>';
	
}

if(isset($_GET['deleteMedKine']))
{

	$id_medK = $_GET['deleteMedKine'];
	
	$deleteKine=$connexion->prepare('DELETE FROM med_kine WHERE id_medkine=:id_medK');
	
	$deleteKine->execute(array(
	'id_medK'=>$id_medK
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un actes (Physio)");</script>';
	
	echo '<script type="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok";</script>';
	
}

if(isset($_GET['deleteMedOrtho']))
{

	$id_medO = $_GET['deleteMedOrtho'];

	$deleteOrtho=$connexion->prepare('DELETE FROM med_ortho WHERE id_medortho=:id_medO');

	$deleteOrtho->execute(array(
	'id_medO'=>$id_medO

	))or die( print_r($connexion->errorInfo()));

	echo '<script type="text/javascript"> alert("Vous venez de supprimer un matériel");</script>';

	echo '<script type="text/javascript">document.location.href="categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok";</script>';

}

if(isset($_GET['finishbtn']))
{
	/* if($_GET['createBill']==1)
	{
		createBN();
	}
	*/
	$idBilling=$_GET['idbill'];
	$idconsu=$_GET['idconsu'];
	$vouchernum=$_GET['vouchernum'];
	$codecash=$_SESSION['codeCash'];
	
	/*----------Update Bill----------------*/
	
	$updateIdBill=$connexion->prepare('UPDATE bills b SET b.vouchernum=:vouchernum, b.codecashier=:codecash WHERE b.id_bill=:idbill');

	$updateIdBill->execute(array(
	'idbill'=>$idBilling,
	'codecash'=>$codecash,
	'vouchernum'=>$vouchernum
	
	))or die( print_r($connexion->errorInfo()));
	
	/*----------Update Consult----------------*/
	
	$updateIdFactureConsult=$connexion->prepare('UPDATE consultations c SET c.id_factureConsult=:idbill, c.codecashier=:codecashier, c.done=1 WHERE c.id_consu=:idconsu AND c.id_factureConsult IS NULL AND c.numero=:num');

	$updateIdFactureConsult->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Consult----------------*/
	
	$updateIdFactureMedConsult=$connexion->prepare('UPDATE med_consult mc SET mc.id_factureMedConsu=:idbill, mc.codecashier=:codecashier WHERE mc.id_consuMed=:idconsu AND mc.numero=:num AND (mc.id_factureMedConsu=0 OR mc.id_factureMedConsu IS NULL)');

	$updateIdFactureMedConsult->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Surge----------------*/
	
	$updateIdFactureMedSurge=$connexion->prepare('UPDATE med_surge ms SET ms.id_factureMedSurge=:idbill, ms.codecashier=:codecashier WHERE ms.id_consuSurge=:idconsu AND ms.numero=:num AND (ms.id_factureMedSurge=0 OR ms.id_factureMedSurge IS NULL)');

	$updateIdFactureMedSurge->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash'] 
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Kine----------------*/
	
	$updateIdFactureMedKine=$connexion->prepare('UPDATE med_kine mk SET mk.id_factureMedKine=:idbill, mk.codecashier=:codecashier WHERE mk.id_consuKine=:idconsu AND mk.numero=:num AND (mk.id_factureMedKine=0 OR mk.id_factureMedKine IS NULL)');

	$updateIdFactureMedKine->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash'] 
	
	))or die( print_r($connexion->errorInfo()));



	/*----------Update Med_Ortho----------------*/

	$updateIdFactureMedOrtho=$connexion->prepare('UPDATE med_ortho mo SET mo.id_factureMedOrtho=:idbill, mo.codecashier=:codecashier WHERE mo.id_consuOrtho=:idconsu AND mo.numero=:num AND (mo.id_factureMedOrtho=0 OR mo.id_factureMedOrtho IS NULL)');

	$updateIdFactureMedOrtho->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']

	))or die( print_r($connexion->errorInfo()));
	

	/*----------Update Med_psy----------------*/

	$updateIdFactureMedPsycho=$connexion->prepare('UPDATE med_psy mp SET mp.id_factureMedPsy=:idbill, mp.codecashier=:codecashier WHERE mp.id_consuPSy=:idconsu AND mp.numero=:num AND (mp.id_factureMedPsy=0 OR mp.id_factureMedPsy IS NULL)');

	$updateIdFactureMedPsycho->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']

	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Inf----------------*/
	
	$updateIdFactureMedInf=$connexion->prepare('UPDATE med_inf mi SET mi.id_factureMedInf=:idbill, mi.codecashier=:codecashier WHERE mi.id_consuInf=:idconsu AND mi.numero=:num AND (mi.id_factureMedInf=0 OR mi.id_factureMedInf IS NULL)');

	$updateIdFactureMedInf->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash'] 
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Labo----------------*/
	
	$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_labo ml SET ml.id_factureMedLabo=:idbill, ml.codecashier=:codecashier WHERE ml.id_consuLabo=:idconsu AND ml.numero=:num AND (ml.id_factureMedLabo=0 OR ml.id_factureMedLabo IS NULL)');

	$updateIdFactureMedLabo->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Radio----------------*/
	
	$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_radio mr SET mr.id_factureMedRadio=:idbill, mr.codecashier=:codecashier WHERE mr.id_consuRadio=:idconsu AND mr.numero=:num AND (mr.id_factureMedRadio=0 OR mr.id_factureMedRadio IS NULL)');

	$updateIdFactureMedLabo->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Consom----------------*/
	
	$updateIdFactureMedConsom=$connexion->prepare('UPDATE med_consom mco SET mco.id_factureMedConsom=:idbill, mco.codecashier=:codecashier WHERE mco.id_consuConsom=:idconsu AND mco.numero=:num AND (mco.id_factureMedConsom=0 OR mco.id_factureMedConsom IS NULL)');

	$updateIdFactureMedConsom->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
	
	/*----------Update Med_Medoc----------------*/
	
	$updateIdFactureMedMedoc=$connexion->prepare('UPDATE med_medoc mdo SET mdo.id_factureMedMedoc=:idbill, mdo.codecashier=:codecashier WHERE mdo.id_consuMedoc=:idconsu AND mdo.numero=:num AND (mdo.id_factureMedMedoc=0 OR mdo.id_factureMedMedoc IS NULL)');

	$updateIdFactureMedMedoc->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
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

	
	var prixtypeconsult=document.getElementsByClassName("prixtypeconsult");
	var percentTypeConsu=document.getElementsByClassName("percentTypeConsu");
	
	
	var prixprestaConsu=document.getElementsByClassName("prixprestaConsu");
	var percentConsu=document.getElementsByClassName("percentConsu");
	
	
	var prixprestaSurge=document.getElementsByClassName("prixprestaSurge");
	var percentSurge=document.getElementsByClassName("percentSurge");
	
	
	var prixprestaKine=document.getElementsByClassName("prixprestaKine");
	var percentKine=document.getElementsByClassName("percentKine");


	var prixprestaOrtho=document.getElementsByClassName("prixprestaOrtho");
	var percentOrtho=document.getElementsByClassName("percentOrtho");
	
	
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
	
	
	
	var i;
	var rapportPrixTypeConsu = [];
	var rapportPercentTypeConsu = [];
	var rapportPrixConsu = [];
	var rapportPercentConsu = [];
	var rapportPrixSurge = [];
	var rapportPercentSurge = [];
	var rapportPrixKine = [];
	var rapportPercentKine = [];
	var rapportPrixOrtho = [];
	var rapportPercentOrtho = [];
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
	
	
	
		for(i=0; i<prixtypeconsult.length; ++i){
			
			if(prixtypeconsult[i].value >= 0){
				prixtypeconsult[i].style.background="white";
			}else{			
				rapportPrixTypeConsu[i]=controlPrixprestaTypeConsu(prixtypeconsult[i]);	
			}	
		}			
			function controlPrixprestaTypeConsu(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
			
		for(i=0; i<percentTypeConsu.length; ++i){	
						
			if(percentTypeConsu[i].value >= 0 && percentTypeConsu[i].value !=""){
				percentTypeConsu[i].style.background="white";
			}else{			
				rapportPercentTypeConsu[i]=controlPercentTypeConsu(percentTypeConsu[i]);	
			}			
		}			
			function controlPercentTypeConsu(fld){
				
				erreur="error";
				fld.style.background="rgba(0,255,0,0.3)";

				return erreur;	
			}
		
		
		
		for(i=0; i<prixprestaConsu.length; ++i){
			
			if(prixprestaConsu[i].value >= 0){
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
		
		
		
		for(i=0; i<prixprestaSurge.length; ++i){
			
			if(prixprestaSurge[i].value >= 0){
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
		
		
		
		for(i=0; i<prixprestaKine.length; ++i){
			
			if(prixprestaKine[i].value >= 0){
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



		for(i=0; i<prixprestaOrtho.length; ++i){

			if(prixprestaOrtho[i].value >= 0){
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
		
		
		
		for(i=0; i<prixprestaInf.length; ++i){
			
			if(prixprestaInf[i].value >= 0){
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
		
			if(prixprestaLab[i].value >= 0 && prixprestaLab[i].value !=""){
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
		
			if(prixprestaRad[i].value >= 0 && prixprestaRad[i].value !=""){
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
		
			if(prixprestaConsom[i].value >= 0){
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
			
			if(prixprestaMedoc[i].value >= 0){
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
		
		
		
	if (rapportPrixTypeConsu != "" || rapportPercentTypeConsu != "" || rapportPrixConsu != "" || rapportPercentConsu != "" || rapportPrixSurge != "" || rapportPercentSurge != "" || rapportPrixKine != "" || rapportPercentKine != "" || rapportPrixOrtho != "" || rapportPercentOrtho != "" || rapportPrixInf != "" || rapportPercentInf != "" || rapportPrixLab != "" || rapportPercentLab != "" || rapportPrixRad != "" || rapportPercentRad != "" || rapportPrixConsom != "" || rapportQuantityConsom != "" || rapportPercentConsom != "" || rapportPrixMedoc != "" || rapportQuantityMedoc != "" || rapportPercentMedoc != "") {
	
		alert("Veuillez corriger les erreurs.");
		
				return false;		
	 }
		
	
}


</script>

</head>

<body>
<?php

$id=$_SESSION['id'];

$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");

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
					<form method="post" action="categoriesbill.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&facture=ok<?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];} if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="categoriesbill.php?english=english<?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="categoriesbill.php?francais=francais<?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?>" class="btn"><?php echo getString(29);?></a>
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
if(isset($_SESSION['codeR']) AND isset($_SESSION['codeCash']))
{
	if(isset($_GET['cashier']))
	{
?>
		<div style="text-align:center;margin-top:20px;">
			
			<a href="patients1.php?receptioniste=ok" class="btn-large" name="savebtn" style="font-size:20px;height:40px;paddin:10px 40px;">
				<?php echo 'Reception';?>
			</a>

		</div>
<?php
	}
}
?>

<div class="account-container" style="width:90%">

<?php

if($comptidC!=0)
{
?>
<div id='cssmenu' style="text-align:center">
<ul>
	<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?>&caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
	
		<?php
		$lu=0;
        $selectmsg=$connexion->prepare("SELECT * FROM messages WHERE lu=:lu AND receiverId=:receiverId");
        $selectmsg->execute(array("lu"=>$lu,"receiverId"=>$_SESSION['id']));
        $selectmsg->setFetchMode(PDO::FETCH_OBJ);
        $lignecount=$selectmsg->rowCount();
        /*echo $_SESSION['id'];*/
        /*echo $lignecount;*/
		?>
		<style type="text/css">.badge{background: black;}.badge2{background: rgb(160, 0, 0);}</style>
		<?php if($lignecount!=0){?>
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?> || Unread messages: <?php echo $lignecount; ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?> <i class="badge flashing"><?php echo $lignecount; ?></i> </a></li>
        <?php }else{?>
        	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
        <?php }?>
	
</ul>
	
		<div style="display:none;" id="divMenuMsg">

					<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
					
					<?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
					
					<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>

				</div>
	
	
</div>
<?php
}
?>

	<table style="width:100%;margin:20px;">
	   
		<tr>
			<td style="text-align:left;width:50%;">
				
				<p class="patientId"><span>S/N:</span> <?php echo $num; ?>

				<p class="patientId"><span> <?php echo getString(89);?>:</span> <?php echo $nom_uti.' '.$prenom_uti; ?>
				
			</td>
		<?php
		if($idassu!=NULL)
		{
		?>
			<td style="text-align:left;width:50%;">
				
				<p class="patientId"><span>Insurance type:</span>
				<?php
				
				$nomassu= '';
					
				$resultAssu=$connexion->prepare('SELECT * FROM assurances a WHERE id_assurance=:assu');
				$resultAssu->execute(array(
				'assu'=>$idassu
				)); 
				
				if($ligneAssu=$resultAssu->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
				{
					echo $ligneAssu->nomassurance.' ('.$bill.'%)';
					
					$nomassu= $ligneAssu->nomassurance;
				}
				?>

			</td>
		<?php
		}else{
		?>
			<td style="text-align:left;width:50%;">
				
				<p class="patientId"><span>Insurance type:</span> <?php echo "Privé"; ?>
				
			</td>
		<?php
		}
		?>

			<td style="text-align:center"><?php echo 'Today date';?>
				<input size="25px" type="text" id="anneeAd" name="anAd" onclick="ds_sh(this);" value="<?php if(isset($_GET['iduti'])){ echo $anAff;}else{ echo $annee;}?>" readonly="readonly"/>
				
				<input size="25px" type="hidden" id="today" name="today" value="<?php echo $annee;?>"/>
			</td>
		</tr>

	</table>

	<form method="post" action="addpresta.php?cashier=<?php echo $_GET['cashier'];?>&idassu=<?php echo $_GET['idassu'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&num=<?php echo $_GET['num'];?>&idmed=<?php echo $_GET['idmed'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idbill=<?php echo $_GET['idbill'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>" enctype="multipart/form-data">
		
		<table align="center">
		   
			<tr>
				<td><label for="medecin"><?php echo 'Nom du medecin'; ?></label></td>
							
				<td>
					<?php
					
					$resultatsMedecins=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m, grades g, servicemed sm WHERE u.id_u=m.id_u AND sm.codemedecin=m.codemedecin AND m.id_u=:medId ORDER BY u.nom_u');
					$resultatsMedecins->execute(array(
						'medId'=>$_GET['idmed']
					));
					
					$resultatsMedecins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				
					if($ligneMedecins=$resultatsMedecins->fetch())//on recupere la liste des éléments
					{
					?>
						<input type="text" name="medecin" id="medecin" value="<?php echo $ligneMedecins->nom_u.' '.$ligneMedecins->prenom_u;?>" readonly="readonly"/>
						
						<input type="hidden" name="idmedecin" id="idmedecin" value="<?php echo $ligneMedecins->id_u;?>"/>
					<?php
					}
					?>
				</td>
				
			</tr>	   

			<tr>
				<td><label for="typeconsu"><?php echo 'Type of consultation'; ?></label></td>
							
				<td>
					<?php
					
					$idassuConsu=$_GET['idassu'];
											
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
					$assuCount = $comptAssuConsu->rowCount();
					
					for($i=1;$i<=$assuCount;$i++)
					{
						
						$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
						$getAssuConsu->execute(array(
						'idassu'=>$idassuConsu
						));
						
						$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

						if($ligneNomAssu=$getAssuConsu->fetch())
						{
							$presta_assuConsu='prestations_'.$ligneNomAssu->nomassurance;
						}
					}

					$resultatsTypeConsu=$connexion->prepare('SELECT *FROM '.$presta_assuConsu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$resultatsTypeConsu->execute(array(
						'idPresta'=>$_GET['idtypeconsu']
					));
					
					if($ligneTypeConsu=$resultatsTypeConsu->fetch(PDO::FETCH_OBJ))
					{
					?>
						<input type="text" name="typeconsu" id="typeconsu" value="<?php echo $ligneTypeConsu->nompresta;?>" readonly="readonly"/>
						
						<input type="hidden" name="idtypeconsu" id="idtypeconsu" value="<?php echo $ligneTypeConsu->id_prestation;?>"/>
					<?php
					}
					?>
					
				</td>
			
			</tr>	   

		</table>
		
		<table style="margin:20px auto;">
			<tr>
			<?php

			$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE  c.id_consu=:idconsu AND c.numero=:num AND c.dateconsu!="0000-00-00" AND c.id_factureConsult IS NULL ORDER BY c.id_consu');		
			$resultConsult->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();




			$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.numero=:num AND (mc.id_factureMedConsu=0 OR mc.id_factureMedConsu IS NULL) AND mc.id_consuMed=:idconsu ORDER BY mc.id_medconsu');		
			$resultMedConsult->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsult=$resultMedConsult->rowCount();



			$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.numero=:num AND (mi.id_factureMedInf=0 OR mi.id_factureMedInf IS NULL) AND mi.id_consuInf=:idconsu ORDER BY mi.id_medinf');		
			$resultMedInf->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

			$comptMedInf=$resultMedInf->rowCount();



			$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.numero=:num AND (ml.id_factureMedLabo=0 OR ml.id_factureMedLabo IS NULL) AND ml.id_consuLabo=:idconsu ORDER BY ml.id_medlabo');		
			$resultMedLabo->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

			$comptMedLabo=$resultMedLabo->rowCount();

			

			$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.numero=:num AND (mr.id_factureMedRadio=0 OR mr.id_factureMedRadio IS NULL) AND mr.id_consuRadio=:idconsu ORDER BY mr.id_medradio');		
			$resultMedRadio->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

			$comptMedRadio=$resultMedRadio->rowCount();

			
			
			$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.numero=:num AND (mco.id_factureMedConsom=0 OR mco.id_factureMedConsom IS NULL) AND mco.id_consuConsom=:idconsu ORDER BY mco.id_medconsom');		
			$resultMedConsom->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsom=$resultMedConsom->rowCount();

			
			
			$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.numero=:num AND (mdo.id_factureMedMedoc=0 OR mdo.id_factureMedMedoc IS NULL) AND mdo.id_consuMedoc=:idconsu ORDER BY mdo.id_medmedoc');		
			$resultMedMedoc->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

			$comptMedMedoc=$resultMedMedoc->rowCount();

			
			
			$resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk WHERE mk.numero=:num AND (mk.id_factureMedKine=0 OR mk.id_factureMedKine IS NULL) AND mk.id_consuKine=:idconsu ORDER BY mk.id_medkine');
			$resultMedKine->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

			$comptMedKine=$resultMedKine->rowCount();



			$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo WHERE mo.numero=:num AND (mo.id_factureMedOrtho=0 OR mo.id_factureMedOrtho IS NULL) AND mo.id_consuOrtho=:idconsu ORDER BY mo.id_medortho');
			$resultMedOrtho->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));

			$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

			$comptMedOrtho=$resultMedOrtho->rowCount();

			
			
			$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.numero=:num AND (ms.id_factureMedSurge=0 OR ms.id_factureMedSurge IS NULL) AND ms.id_consuSurge=:idconsu ORDER BY ms.id_medsurge');		
			$resultMedSurge->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

			$comptMedSurge=$resultMedSurge->rowCount();


			if($comptConsult!=0 OR $comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedKine!=0 OR $comptMedOrtho!=0 OR $comptMedSurge!=0)
			{
			?>
				
				<td>
				<?php
				if(!isset($_GET['previewprint']))
				{
				?>
					<a style="padding:10px 40px;" href="<?php echo 'categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&previewprint=ok'?>" class="btn-large-inversed" name="showpreviewbtn" id="showpreviewbtn"><?php echo getString(220) ?></a>
				<?php
				}else{
					if(isset($_GET['previewprint']))
					{
				?>
					<a style="padding:10px 40px;" href="<?php echo 'categoriesbill.php?cashier='.$_GET['cashier'].'&num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&back=ok'?>" class="btn-large-inversed" name="showbackbtn" id="showbackbtn"><?php echo getString(221) ?></a>
				<?php
					}
				}
				?>
				</td>
			<?php
			}
			?>
			</tr>
		</table>
		
		<?php
		if(!isset($_GET['previewprint']))
		{
		?>
		<div id="divCatego" style="margin:40px auto 0; text-align:center">

			<table align="center" style="display:inline;" id="catego">
				<tr>
					<td style="padding:5px;">
						<span id="services" class="btn-large" onclick="ShowMore('services')"><?php echo "Others Services";?></span>
					</td>
					<?php

					$resultatsPrestaConsultation=$connexion->query('SELECT *FROM categopresta_ins cp, '.$presta_assu.' p  WHERE p.id_categopresta!=2 AND (cp.id_categopresta=3 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=3) OR (cp.id_categopresta=12 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=12) OR (cp.id_categopresta=13 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=13) OR (cp.id_categopresta=21 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=21) OR (cp.id_categopresta=22 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=22) OR (cp.id_categopresta=14 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=14) OR (cp.id_categopresta=23 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=23) OR (cp.id_categopresta=4 AND p.id_categopresta=cp.id_categopresta AND p.id_categopresta=4) GROUP BY cp.id_categopresta ORDER BY cp.id_categopresta');

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
						<!--
						<td style="padding:5px;">
							<span id="21" class="btn-large" onclick="ShowMore('21')"><?php echo 'Consommables';?></span>								
						</td>
													
						<td style="padding:5px;">
							<span id="22" class="btn-large" onclick="ShowMore('22')"><?php echo 'Medicaments';?></span>								
						</td>
						-->
					
						
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
	
						$resultatsCategoPrestaConsu=$connexion->query('SELECT *FROM categopresta_ins cp WHERE cp.id_categopresta!=1 AND cp.id_categopresta!=2 AND cp.id_categopresta!=3 AND cp.id_categopresta!=12 AND cp.id_categopresta!=13 AND cp.id_categopresta!=21 AND cp.id_categopresta!=22 AND cp.id_categopresta!=14 AND cp.id_categopresta!=23 AND cp.id_categopresta!=4 ORDER BY cp.nomcategopresta');

						$resultatsCategoPrestaConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptCategoMedConsu=$resultatsCategoPrestaConsu->rowCount();
						
						while($ligneCategoPrestaConsu=$resultatsCategoPrestaConsu->fetch())
						{
							echo '<optgroup label="'.$ligneCategoPrestaConsu->nomcategopresta.'">';
							
							$resultatsPrestaConsu=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta!=1 AND p.id_categopresta!=2 AND p.id_categopresta!=3 AND p.id_categopresta!=12 AND p.id_categopresta!=13 AND p.id_categopresta!=21 AND p.id_categopresta!=22 AND p.id_categopresta!=4 AND p.id_categopresta!=14 AND p.id_categopresta!=23 AND p.id_categopresta='.$ligneCategoPrestaConsu->id_categopresta.' ORDER BY p.nompresta');

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
									<td>Nouveau prix</td>
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
									<td>Nouveau prix</td>
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
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
			
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
									<td>Nouveau prix</td>
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
									<td>Nouveau prix</td>
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
							
							echo '<option value='.$ligneCatPrestaConsom->id_prestation.' onclick="ShowRadio(\'radio\')">'.$ligneCatPrestaConsom->nompresta.'</option>';
							
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
						<option value='0'><?php echo 'Selectionner le type de matériel...' ?></option>
						-->
						<?php
						
						$resultatsPrestaMedoc=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=22 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaMedoc->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneCatPrestaMedoc=$resultatsPrestaMedoc->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaMedoc->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaMedoc->id_prestation.' onclick="ShowRadio(\'radio\')">'.$ligneCatPrestaMedoc->nompresta.'</option>';
							
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
		
		<div id="divViewKine" style="margin:40px auto 0; display:none;">
			
			<table id="lab" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1><?php echo 'Physiotherapy';?></h1>
						
						<select style="margin:auto" multiple="multiple" name="checkprestaKine[]" class="chosen-select" id="checkprestaKine">

						<?php
						
						$resultatsPrestaKine=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=14 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaKine->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaKine=$resultatsPrestaKine->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaKine->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaKine->id_prestation.' onclick="ShowKine(\'kine\')">'.$ligneCatPrestaKine->nompresta.'</option>';
							
							while($lignePrestaKine=$resultatsPrestaKine->fetch())//on recupere la liste des éléments
							{
						?>
								<option value='<?php echo $lignePrestaKine->id_prestation;?>'><?php echo $lignePrestaKine->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}
						?>
						
							<!--
							<option value="autrekine" id="autrekine"><?php echo getString(124) ?></option>
							-->
							
						</select>
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouveau acte</td>
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
									<td>Nouveau prix</td>
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
							</tbody>
						</table>
					</td>
				</tr>	
			</table>
		</div>

		<div id="divViewOrtho" style="margin:40px auto 0; display:none;">

			<table id="lab" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1><?php echo 'P&O';?></h1>

						<select style="margin:auto" multiple="multiple" name="checkprestaOrtho[]" class="chosen-select" id="checkprestaOrtho">

						<?php

						$resultatsPrestaOrtho=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=23 ORDER BY p.nompresta ASC');

						$resultatsPrestaOrtho->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						if($ligneCatPrestaOrtho=$resultatsPrestaOrtho->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaOrtho->nomcategopresta.'">';

							echo '<option value='.$ligneCatPrestaOrtho->id_prestation.' onclick="ShowOrtho(\'ortho\')">'.$ligneCatPrestaOrtho->nompresta.'</option>';

							while($lignePrestaOrtho=$resultatsPrestaOrtho->fetch())//on recupere la liste des éléments
							{
						?>
								<option value='<?php echo $lignePrestaOrtho->id_prestation;?>'><?php echo $lignePrestaOrtho->nompresta;?></option>
						<?php
							}
							echo '</optgroup>';
						}
						?>

							<!--
							<option value="autreortho" id="autreortho"><?php echo getString(124) ?></option>
							-->

						</select>
					</td>

				</tr>

				<tr>
					<td style="text-align:left;">

						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'>
									<td>Nouveau matériel</td>
									<?php
									for($q=0;$q<5;$q++)
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
									<td>Nouveau prix</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>
									<td>
										<input type='text' name='autreprixprestaOrtho[]' id='autreprixprestaOrtho' style='width:70px'/>
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
		
		<div id="divViewSurge" style="margin:40px auto 0; display:none;">
			
			<table id="rad" align="center" style="margin:20px auto; width:100%;background:#ddd;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<h1>Surgery</h1>
					
						<select style="margin:auto" multiple="multiple" name="checkprestaSurge[]" class="chosen-select" id="checkprestaSurge">

						<!--
						<option value='0'><?php echo 'Selectionner le type de radio...' ?></option>
						-->
						<?php
						
						$resultatsPrestaSurge=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=4 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaSurge=$resultatsPrestaSurge->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaSurge->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaSurge->id_prestation.' onclick="ShowRadio(\'radio\')">'.$ligneCatPrestaSurge->nompresta.'</option>';
							
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
						<option value="autresurge" id="autresurge"><?php echo 'Autre type acte...'; ?></option>
						-->
							
						</select>
								
					</td>
				
				</tr>
				
				<tr>	
					<td style="text-align:left;">
					
						<table class='tablesorter' cellpadding=0>
							<tbody>
								<tr style='text-align:center;background:#ddd;'> 
									<td>Nouveau acte</td>
									<?php
									for($q=0;$q<5;$q++)
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
									<td>Nouveau prix</td>
									<?php
									for($q=0;$q<5;$q++)
									{
									?>	
									<td>
										<input type='text' name='autreprixprestaSurge[]' id='autreprixprestaSurge' style='width:70px'/>
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

		$resultConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.id_consu=:idconsu ORDER BY c.id_consu');		
		$resultConsu->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultConsu->setFetchMode(PDO::FETCH_OBJ);

		$comptConsu=$resultConsu->rowCount();

		// echo $comptConsu;

		$ligneConsu=$resultConsu->fetch();
		
		$idconsuFact = $ligneConsu->id_factureConsult;
		
		
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.id_consu=:idconsu ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptConsult=$resultConsult->rowCount();

		
		
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.numero=:num AND (mc.id_factureMedConsu=0 OR mc.id_factureMedConsu IS NULL) AND mc.id_consuMed=:idconsu ORDER BY mc.id_medconsu');		
		$resultMedConsult->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();



		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.numero=:num AND (mi.id_factureMedInf=0 OR mi.id_factureMedInf IS NULL) AND mi.id_consuInf=:idconsu ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();



		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.numero=:num AND (ml.id_factureMedLabo=0 OR ml.id_factureMedLabo IS NULL) AND ml.id_consuLabo=:idconsu ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();



		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.numero=:num AND (mr.id_factureMedRadio=0 OR mr.id_factureMedRadio IS NULL) AND mr.id_consuRadio=:idconsu ORDER BY mr.id_medradio');		
		$resultMedRadio->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();



		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.numero=:num AND (mco.id_factureMedConsom=0 OR mco.id_factureMedConsom IS NULL) AND mco.id_consuConsom=:idconsu ORDER BY mco.id_medconsom');		
		$resultMedConsom->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();



		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.numero=:num AND (mdo.id_factureMedMedoc=0 OR mdo.id_factureMedMedoc IS NULL) AND mdo.id_consuMedoc=:idconsu ORDER BY mdo.id_medmedoc');		
		$resultMedMedoc->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();



		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.numero=:num AND (ms.id_factureMedSurge=0 OR ms.id_factureMedSurge IS NULL) AND ms.id_consuSurge=:idconsu ORDER BY ms.id_medsurge');		
		$resultMedSurge->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

		$comptMedSurge=$resultMedSurge->rowCount();



		$resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk WHERE mk.numero=:num AND (mk.id_factureMedKine=0 OR mk.id_factureMedKine IS NULL) AND mk.id_consuKine=:idconsu ORDER BY mk.id_medkine');
		$resultMedKine->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

		$comptMedKine=$resultMedKine->rowCount();



		$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo WHERE mo.numero=:num AND (mo.id_factureMedOrtho=0 OR mo.id_factureMedOrtho IS NULL) AND mo.id_consuOrtho=:idconsu ORDER BY mo.id_medortho');
		$resultMedOrtho->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));

		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

		$comptMedOrtho=$resultMedOrtho->rowCount();

		$resultMedPyscho=$connexion->prepare('SELECT *FROM med_psy psy WHERE psy.numero=:num AND (psy.id_factureMedPsy=0 OR psy.id_factureMedPsy IS NULL) AND psy.id_consuPSy=:idconsu ORDER BY psy.id_medpsy');
		$resultMedPyscho->execute(array(
		'num'=>$_GET['num'],
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedPyscho->setFetchMode(PDO::FETCH_OBJ);

		$comptMedPyscho=$resultMedPyscho->rowCount();
		
		
		
		
		if($idconsuFact==NULL OR $comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedRadio!=0 OR $comptMedSurge!=0 OR $comptMedKine!=0 OR $comptMedOrtho!=0 OR $comptMedPyscho!=0)
		{
		?>
		
			<form method="post" action="printBill.php?num=<?php echo $_GET['num'];?>&idbill=<?php echo $idconsuFact;?>&cashier=<?php echo $_GET['cashier'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?><?php if(isset($_GET['newdette'])){ echo '&newdette='.$_GET['newdette'];}?><?php if(isset($_GET['datefacture'])){ echo $_GET['datefacture'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormCategoBill(this)" enctype="multipart/form-data">
							<?php 
				if($nomassu=='RSSB')
				{									
					$checkIdBill=$connexion->prepare('SELECT *FROM bills b WHERE b.id_bill=:idbill');

					$checkIdBill->execute(array(
					'idbill'=>$_GET['idbill']
					));

					$comptidBill=$checkIdBill->rowCount();
				
					$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
							
					$ligneIdBill=$checkIdBill->fetch();
					
					if($comptidBill !=0)
					{
						$vouchernum = $ligneIdBill->vouchernum;
					}else{
						$vouchernum = '';
					}
				?>	
				<table align="center">	   
					<tr>	   
						
						<td><label for="vouchernum"><?php echo 'Voucher Identification'; ?></label></td>
							
						<td>
						<p class="patientId">
							<input size="25px" type="text" id="vouchernum" name="vouchernum" onclick="ds_sh(this);" value="<?php echo $vouchernum;?>" placeholder="Enter here..." required/>
						</p>
						</td>
						
					</tr>
				</table>
				<?php
				}else{
				?>
					<input size="25px" type="hidden" id="vouchernum" name="vouchernum" onclick="ds_sh(this);" value=""/>	
				<?php
				}
				?>				

			<?php
			if($idconsuFact==NULL)
			{
			?>
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:50%"><?php echo 'Type of Consultation';?></th>
							<th style="width:15%"><?php echo getString(145).' '.$nomassu;?></th>
							<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:15%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						$i=1;
						
						while($ligneConsult=$resultConsult->fetch())
						{
						?>
						<tr style="text-align:center;">
							<td>
							<?php
							
							$idassuConsu=$ligneConsult->id_assuConsu;
							
							$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsu->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idassuConsu
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$presta_assuConsu='prestations_'.$ligneNomAssu->nomassurance;
								}
							}


							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuConsu.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
							'prestaId'=>$ligneConsult->id_typeconsult
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
							
								echo '<input type="text" name="idprestaConsu[]" style="width:100px;display:none; text-align:center" id="idprestaConsu'.$i.'" class="idprestaConsu" value="'.$lignePresta->id_prestation.'"/>';
								
								echo '<input type="text" name="autretypeconsult[]" style="width:100px;display:none; text-align:center" id="autretypeconsult'.$i.'" value=""/>';
										
								if($lignePresta->namepresta!='')
								{

									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td>								
								<input type="text" name="prixtypeconsult[]" style="width:100px;" id="prixtypeconsult<?php echo $i;?>" class="prixtypeconsult" value="<?php if($ligneConsult->prixtypeconsult!=0){ echo $ligneConsult->prixtypeconsult; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>								
							</td>
							
							<!-- <td>								
								<input type="text" name="prixtypeconsultCCO[]" style="width:100px; text-align:center" id="prixtypeconsultCCO<?php echo $i;?>" class="prixtypeconsultCCO" value="<?php if($ligneConsult->prixtypeconsultCCO!=0){ echo $ligneConsult->prixtypeconsultCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>					
							</td> -->
								<?php
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td>								
								<input type="text" name="prixtypeconsult[]" style="width:100px; text-align:center" id="prixtypeconsult<?php echo $i;?>" class="prixtypeconsult" value="<?php if($ligneConsult->prixtypeconsult!=0){ echo $ligneConsult->prixtypeconsult; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>						
							</td>
							
							<!-- <td>								
								<input type="text" name="prixtypeconsultCCO[]" style="width:100px; text-align:center" id="prixtypeconsultCCO<?php echo $i;?>" class="prixtypeconsultCCO" value="<?php if($ligneConsult->prixtypeconsultCCO!=0){ echo $ligneConsult->prixtypeconsultCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>				
							</td> -->
								<?php
								}
							}
							
							if($ligneConsult->id_typeconsult==NULL AND ($ligneConsult->prixautretypeconsult==0 OR $ligneConsult->prixautretypeconsultCCO==0))
							{
								echo '<input type="text" name="autretypeconsult[]" style="width:100px;display:none; text-align:center" id="autretypeconsult'.$i.'" value="'.$ligneConsult->autretypeconsult.'"/>';
								
								echo $ligneConsult->autretypeconsult.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixtypeconsult[]" style="width:100px; text-align:center" id="prixtypeconsult'.$i.'" class="prixtypeconsult" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneConsult->id_consu.'" style="width:100px;display:none; text-align:center" id="id_consu" value="'.$ligneConsult->id_consu.'"/>
									
										<input type="text" name="idprestaConsu[]" style="width:100px;display:none; text-align:center" id="idprestaConsu'.$i.'" value="0"/>
									</td>
									';

							}else{
							
								if($ligneConsult->id_typeconsult==NULL AND ($ligneConsult->prixautretypeconsult!=0 OR $ligneConsult->prixautretypeconsultCCO!=0))
								{
									
									echo '<input type="text" name="idprestaConsu[]" style="width:100px;display:none; text-align:center" id="idprestaConsu'.$i.'" value="0"/>';
								
									echo '<input type="text" name="autretypeconsult[]" style="width:100px;display:none; text-align:center" id="autretypeconsult'.$i.'" value="'.$ligneConsult->autretypeconsult.'"/>';
								
									echo $ligneConsult->autretypeconsult.'</td>
									<td>
										<input type="text" name="prixtypeconsult[]" style="width:100px; text-align:center" id="prixtypeconsult'.$i.'" class="prixtypeconsult" value="'.$ligneConsult->prixautretypeconsult.'"/>
									
									</td>
									
									<td>
										<input type="text" name="prixtypeconsultCCO[]" style="width:100px; text-align:center" id="prixtypeconsultCCO'.$i.'" class="prixtypeconsultCCO" value="'.$ligneConsult->prixautretypeconsultCCO.'"/>
									
									</td>';
								}
							}
							?>
							
							<td>
								<input type="text" name="percentTypeConsu[]" class="percentTypeConsu" id="percentTypeConsu<?php echo $i;?>" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
							
								<input type="hidden" name="idConsu[]" class="idConsu"  id="idConsu<?php echo $i;?>"style="width:50px; text-align:center" value="<?php echo $ligneConsult->id_consu;?>"/>
							
							</td>
							
							<!-- <td>
							<?php
							if ($ligneConsult->done==0)
							{	
							?>
								<a href="categoriesbill.php?deleteConsu=<?php echo $ligneConsult->id_consu;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							<?php
							}
							?>							
							</td> -->
						</tr>
						<?php
							$i++;
						}
						?>		
					</tbody>
				</table>
			<?php
			}
			
			
			if($comptMedConsult!=0)
			{
		?>
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:50%"><?php echo getString(39);;?></th>
							<th style="width:15%"><?php echo getString(145).' '.$nomassu;?></th>
							<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:15%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
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
								<input type="text" name="prixprestaConsu[]" style="width:100px;" id="prixprestaConsu<?php echo $i;?>" class="prixprestaConsu" value="<?php if($ligneMedConsult->prixprestationConsu!=0){ echo $ligneMedConsult->prixprestationConsu; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>
								
							</td>
							
							<!-- <td>								
								<input type="text" name="prixprestaConsuCCO[]" style="width:100px;" id="prixprestaConsuCCO<?php echo $i;?>" class="prixprestaConsuCCO" value="<?php if($ligneMedConsult->prixprestationConsuCCO!=0){ echo $ligneMedConsult->prixprestationConsuCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>
								
							</td> -->
								<?php
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaConsu[]" style="width:100px; text-align:center" id="prixprestaConsu<?php echo $i;?>" class="prixprestaConsu" value="<?php if($ligneMedConsult->prixprestationConsu!=0){ echo $ligneMedConsult->prixprestationConsu; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>
								
							</td>
							
							<!-- <td>								
								<input type="text" name="prixprestaConsuCCO[]" style="width:100px;" id="prixprestaConsuCCO<?php echo $i;?>" class="prixprestaConsuCCO" value="<?php if($ligneMedConsult->prixprestationConsuCCO!=0){ echo $ligneMedConsult->prixprestationConsuCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo 0;}}?>"/>
								
							</td> -->
								<?php
								}
							}
							
							if($ligneMedConsult->id_prestationConsu==NULL AND ($ligneMedConsult->prixautreConsu==0 OR $ligneMedConsult->prixautreConsuCCO==0))
							{
								echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu'.$i.'" value="'.$ligneMedConsult->autreConsu.'"/>';
								
								echo $ligneMedConsult->autreConsu.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaConsu[]" style="width:100px; text-align:center" id="prixprestaConsu'.$i.'" class="prixprestaConsu" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedConsult->id_medconsu.'" style="width:100px;display:none; text-align:center" id="id_medconsu" value="'.$ligneMedConsult->id_medconsu.'"/>
									
										<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta'.$i.'" value="0"/>
									</td>
									';

							}else{
							
								if($ligneMedConsult->id_prestationConsu==NULL AND ($ligneMedConsult->prixautreConsu!=0 OR $ligneMedConsult->prixautreConsuCCO!=0))
								{
									
									echo '<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta'.$i.'" value="0"/>';
								
									echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu'.$i.'" value="'.$ligneMedConsult->autreConsu.'"/>';
								
									echo $ligneMedConsult->autreConsu.'</td>
									<td>
										<input type="text" name="prixprestaConsu[]" style="width:100px; text-align:center" id="prixprestaConsu'.$i.'" class="prixprestaConsu" value="'.$ligneMedConsult->prixautreConsu.'"/>
									
									</td>
									';
								}
							}
							?>
							
							<td>
								<input type="text" name="percentConsu[]" class="percentConsu" id="percentConsu<?php echo $i;?>" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
							
								<input type="hidden" name="idmedConsu[]" class="idmedConsu"  id="idmedConsu<?php echo $i;?>"style="width:50px; text-align:center" value="<?php echo $ligneMedConsult->id_medconsu;?>"/>
							
							</td>
							
					<!-- 		<td>
								<a href="categoriesbill.php?deleteMedConsu=<?php echo $ligneMedConsult->id_medconsu;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
														
							</td> -->
						</tr>
						<?php
							$i++;
						}
						?>		
					</tbody>
				</table>
			<?php
			}
			
			
			if($comptMedSurge != 0)
			{
			?>		
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:50%"><?php echo "Surgery";?></th>
							<th style="width:15%"><?php echo getString(145).' '.$nomassu;?></th>
							<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:15%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						while($ligneMedSurge=$resultMedSurge->fetch())
						{
						?>
						<tr style="text-align:center;">
							<td>
							<?php
							
							$idassuSurge=$ligneMedSurge->id_assuSurge;
							
							$comptAssuSurge=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
							$comptAssuSurge->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuSurge->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuSurge=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuSurge->execute(array(
								'idassu'=>$idassuSurge
								));
								
								$getAssuSurge->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuSurge=$getAssuSurge->fetch())
								{
									$presta_assuSurge='prestations_'.$ligneNomAssuSurge->nomassurance;
								}
							}


							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuSurge.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedSurge->id_prestationSurge
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								echo '<input type="text" name="idprestaSurge[]" style="width:100px;display:none; text-align:center" id="idprestaSurge" value="'.$lignePresta->id_prestation.'"/>';
							
								echo '<input type="text" name="autreSurge[]" style="width:100px;display:none; text-align:center" id="autreSurge" value=""/>';
									
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaSurge[]" style="width:100px; text-align:center" id="prixprestaSurge" class="prixprestaSurge" value="<?php if($ligneMedSurge->prixprestationSurge!=0){ echo $ligneMedSurge->prixprestationSurge; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>								
							</td>
							
							<!-- <td>								
								<input type="text" name="prixprestaSurgeCCO[]" style="width:100px; text-align:center" id="prixprestaSurgeCCO" class="prixprestaSurgeCCO" value="<?php if($ligneMedSurge->prixprestationSurgeCCO!=0){ echo $ligneMedSurge->prixprestationSurgeCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>								
							</td> -->
								<?php
								}else{								
									echo $lignePresta->nompresta;	
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaSurge[]" style="width:100px; text-align:center" id="prixprestaSurge" class="prixprestaSurge" value="<?php if($ligneMedSurge->prixprestationSurge!=0){ echo $ligneMedSurge->prixprestationSurge; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>								
							</td>
							
							<!-- <td>								
								<input type="text" name="prixprestaSurgeCCO[]" style="width:100px; text-align:center" id="prixprestaSurgeCCO" class="prixprestaSurgeCCO" value="<?php if($ligneMedSurge->prixprestationSurgeCCO!=0){ echo $ligneMedSurge->prixprestationSurgeCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>								
							</td> -->
								<?php
								}
							}
							
							if($ligneMedSurge->id_prestationSurge==NULL AND ($ligneMedSurge->prixautrePrestaS==0 OR $ligneMedSurge->prixautrePrestaS==0))
							{
								echo '<input type="text" name="autreSurge[]" style="width:100px;display:none; text-align:center" id="autreSurge" value="'.$ligneMedSurge->autrePrestaS.'"/>';
									
								echo $ligneMedSurge->autrePrestaS.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaSurge[]" style="width:100px; text-align:center" id="prixprestaSurge" class="prixprestaSurge" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedSurge->id_medsurge.'" style="width:100px;display:none; text-align:center" id="id_medsurge" value="'.$ligneMedSurge->id_medsurge.'"/>
										
										<input type="hidden" name="idprestaSurge[]" style="width:100px; text-align:center" id="idprestaSurge" value="0"/>
									</td>
									';
							}else{
							
								if($ligneMedSurge->id_prestationSurge==NULL AND ($ligneMedSurge->prixautrePrestaS!=0 OR $ligneMedSurge->prixautrePrestaSCCO!=0))
								{
									echo $ligneMedSurge->autrePrestaS.'
<input type="hidden" name="idprestaSurge[]" style="width:100px;display:none;" id="idprestaSurge" value="0"/>
									</td>';
									
									echo '<input type="text" name="autreSurge[]" style="width:100px;display:none; text-align:center" id="autreSurge" value="'.$ligneMedSurge->autrePrestaS.'"/>';
									
									echo '<td>
									<input type="text" name="prixprestaSurge[]" style="width:100px; text-align:center" id="prixprestaSurge" class="prixprestaSurge" value="'.$ligneMedSurge->prixautrePrestaS.'"/>
									</td>
									';
								}
							}
							?>
							</td>
							
							<td>
								<input type="text" id="percentSurge" name="percentSurge[]" class="percentSurge" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
								<input type="hidden" id="idmedSurge" name="idmedSurge[]" style="width:50px; text-align:center" value="<?php echo $ligneMedSurge->id_medsurge;?>"/>
							
							</td>
							
							<!-- <td>
								<a href="categoriesbill.php?deleteMedSurge=<?php echo $ligneMedSurge->id_medsurge;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
														
							</td> -->
						</tr>
						<?php
						}
						?>		
					</tbody>
				</table>
		<?php
			}
			
			
			if($comptMedKine != 0)
			{
			?>		
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:50%"><?php echo 'Physiotherapy';?></th>
							<th style="width:15%"><?php echo getString(145).' '.$nomassu;?></th>
						<!-- 	<th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:15%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						while($ligneMedKine=$resultMedKine->fetch())
						{
						?>
						<tr style="text-align:center;">
							<td>
							<?php
							
							$idassuKine=$ligneMedKine->id_assuKine;
							
							$comptAssuKine=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
							$comptAssuKine->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuKine->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuKine=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuKine->execute(array(
								'idassu'=>$idassuKine
								));
								
								$getAssuKine->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuKine=$getAssuKine->fetch())
								{
									$presta_assuKine='prestations_'.$ligneNomAssuKine->nomassurance;
								}
							}


							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuKine.' p WHERE p.id_prestation=:prestaId');
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedKine->id_prestationKine
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								echo '<input type="text" name="idprestaKine[]" style="width:100px;display:none; text-align:center" id="idprestaKine" value="'.$lignePresta->id_prestation.'"/>';
							
								echo '<input type="text" name="autreKine[]" style="width:100px;display:none; text-align:center" id="autreKine" value=""/>';
									
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaKine[]" style="width:100px; text-align:center" id="prixprestaKine" class="prixprestaKine" value="<?php if($ligneMedKine->prixprestationKine!=0){ echo $ligneMedKine->prixprestationKine; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>
							</td>
							
							<!-- <td>								
								<input type="text" name="prixprestaKineCCO[]" style="width:100px; text-align:center" id="prixprestaKineCCO" class="prixprestaKineCCO" value="<?php if($ligneMedKine->prixprestationKineCCO!=0){ echo $ligneMedKine->prixprestationKineCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>
							</td> -->
								<?php
								}else{								
									echo $lignePresta->nompresta;	
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaKine[]" style="width:100px; text-align:center" id="prixprestaKine" class="prixprestaKine" value="<?php if($ligneMedKine->prixprestationKine!=0){ echo $ligneMedKine->prixprestationKine; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>
							</td>
							
							<!-- <td>								
								<input type="text" name="prixprestaKineCCO[]" style="width:100px; text-align:center" id="prixprestaKineCCO" class="prixprestaKineCCO" value="<?php if($ligneMedKine->prixprestationKineCCO!=0){ echo $ligneMedKine->prixprestationKineCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>
							</td> -->
								<?php
								}
							}
							
							if($ligneMedKine->id_prestationKine==NULL AND ($ligneMedKine->prixautrePrestaK==0 OR $ligneMedKine->prixautrePrestaK==0))
							{
								echo '<input type="text" name="autreKine[]" style="width:100px;display:none; text-align:center" id="autreKine" value="'.$ligneMedKine->autrePrestaK.'"/>';
									
								echo $ligneMedKine->autrePrestaK.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaKine[]" style="width:100px; text-align:center" id="prixprestaKine" class="prixprestaKine" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedKine->id_medkine.'" style="width:100px;display:none; text-align:center" id="id_medkine" value="'.$ligneMedKine->id_medkine.'"/>
										
										<input type="hidden" name="idprestaKine[]" style="width:100px; text-align:center" id="idprestaKine" value="0"/>
									</td>
									';
							}else{
							
								if($ligneMedKine->id_prestationKine==NULL AND ($ligneMedKine->prixautrePrestaK!=0 OR $ligneMedKine->prixautrePrestaKCCO!=0))
								{
									echo $ligneMedKine->autrePrestaK.'
<input type="hidden" name="idprestaKine[]" style="width:100px;display:none;" id="idprestaKine" value="0"/>
									</td>';
									
									echo '<input type="text" name="autreKine[]" style="width:100px;display:none; text-align:center" id="autreKine" value="'.$ligneMedKine->autrePrestaK.'"/>';
									
									echo '<td>
									<input type="text" name="prixprestaKine[]" style="width:100px; text-align:center" id="prixprestaKine" class="prixprestaKine" value="'.$ligneMedKine->prixautrePrestaK.'"/>
									</td>
									
									<td>
									';
								}
							}
							?>
							</td>
							
							<td>
								<input type="text" id="percentKine" name="percentKine[]" class="percentKine" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
								<input type="hidden" id="idmedKine" name="idmedKine[]" style="width:50px; text-align:center" value="<?php echo $ligneMedKine->id_medkine;?>"/>
							
							</td>
							
							<!-- <td>
								<a href="categoriesbill.php?deleteMedKine=<?php echo $ligneMedKine->id_medkine;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
														
							</td> -->
						</tr>
						<?php
						}
						?>		
					</tbody>
				</table>
		<?php
			}


			if($comptMedOrtho != 0)
			{
			?>
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;">
					<thead>
						<tr>
							<th style="width:50%"><?php echo 'P&O';?></th>
							<th style="width:15%"><?php echo getString(145).' '.$nomassu;?></th>
							<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:15%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr>
					</thead>


					<tbody>
						<?php
						while($ligneMedOrtho=$resultMedOrtho->fetch())
						{
						?>
						<tr style="text-align:center;">
							<td>
							<?php

							$idassuOrtho=$ligneMedOrtho->id_assuOrtho;

							$comptAssuOrtho=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

							$comptAssuOrtho->setFetchMode(PDO::FETCH_OBJ);

							$assuCount = $comptAssuOrtho->rowCount();

							for($i=1;$i<=$assuCount;$i++)
							{

								$getAssuOrtho=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuOrtho->execute(array(
								'idassu'=>$idassuOrtho
								));

								$getAssuOrtho->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuOrtho=$getAssuOrtho->fetch())
								{
									$presta_assuOrtho='prestations_'.$ligneNomAssuOrtho->nomassurance;
								}
							}


							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuOrtho.' p WHERE p.id_prestation=:prestaId');
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedOrtho->id_prestationOrtho
							));

							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();

							if($lignePresta=$resultPresta->fetch())
							{
								echo '<input type="text" name="idprestaOrtho[]" style="width:100px;display:none; text-align:center" id="idprestaOrtho" value="'.$lignePresta->id_prestation.'"/>';

								echo '<input type="text" name="autreOrtho[]" style="width:100px;display:none; text-align:center" id="autreOrtho" value=""/>';

								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								?>
							</td>

							<td>
								<input type="text" name="prixprestaOrtho[]" style="width:100px; text-align:center" id="prixprestaOrtho" class="prixprestaOrtho" value="<?php if($ligneMedOrtho->prixprestationOrtho!=0){ echo $ligneMedOrtho->prixprestationOrtho; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>
							</td>

							<!-- <td>
								<input type="text" name="prixprestaOrthoCCO[]" style="width:100px; text-align:center" id="prixprestaOrthoCCO" class="prixprestaOrthoCCO" value="<?php if($ligneMedOrtho->prixprestationOrthoCCO!=0){ echo $ligneMedOrtho->prixprestationOrthoCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>
							</td> -->
								<?php
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>

							<td>
								<input type="text" name="prixprestaOrtho[]" style="width:100px; text-align:center" id="prixprestaOrtho" class="prixprestaOrtho" value="<?php if($ligneMedOrtho->prixprestationOrtho!=0){ echo $ligneMedOrtho->prixprestationOrtho; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>
							</td>

							<!-- <td>
								<input type="text" name="prixprestaOrthoCCO[]" style="width:100px; text-align:center" id="prixprestaOrthoCCO" class="prixprestaOrthoCCO" value="<?php if($ligneMedOrtho->prixprestationOrthoCCO!=0){ echo $ligneMedOrtho->prixprestationOrthoCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>
							</td> -->
								<?php
								}
							}

							if($ligneMedOrtho->id_prestationOrtho==NULL AND ($ligneMedOrtho->prixautrePrestaO==0 OR $ligneMedOrtho->prixautrePrestaO==0))
							{
								echo '<input type="text" name="autreOrtho[]" style="width:100px;display:none; text-align:center" id="autreOrtho" value="'.$ligneMedOrtho->autrePrestaO.'"/>';

								echo $ligneMedOrtho->autrePrestaO.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaOrtho[]" style="width:100px; text-align:center" id="prixprestaOrtho" class="prixprestaOrtho" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedOrtho->id_medortho.'" style="width:100px;display:none; text-align:center" id="id_medortho" value="'.$ligneMedOrtho->id_medortho.'"/>
										
										<input type="hidden" name="idprestaOrtho[]" style="width:100px; text-align:center" id="idprestaOrtho" value="0"/>
									</td>
									';
							}else{

								if($ligneMedOrtho->id_prestationOrtho==NULL AND ($ligneMedOrtho->prixautrePrestaO!=0 OR $ligneMedOrtho->prixautrePrestaOCCO!=0))
								{
									echo $ligneMedOrtho->autrePrestaO.'
<input type="hidden" name="idprestaOrtho[]" style="width:100px;display:none;" id="idprestaOrtho" value="0"/>
									</td>';

									echo '<input type="text" name="autreOrtho[]" style="width:100px;display:none; text-align:center" id="autreOrtho" value="'.$ligneMedOrtho->autrePrestaO.'"/>';

									echo '<td>
									<input type="text" name="prixprestaOrtho[]" style="width:100px; text-align:center" id="prixprestaOrtho" class="prixprestaOrtho" value="'.$ligneMedOrtho->prixautrePrestaO.'"/>
									</td>
									';
								}
							}
							?>
							</td>

							<td>
								<input type="text" id="percentOrtho" name="percentOrtho[]" class="percentOrtho" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
								<input type="hidden" id="idmedOrtho" name="idmedOrtho[]" style="width:50px; text-align:center" value="<?php echo $ligneMedOrtho->id_medortho;?>"/>

							</td>

							<!-- <td>
								<a href="categoriesbill.php?deleteMedOrtho=<?php echo $ligneMedOrtho->id_medortho;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td> -->
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
		<?php
			}

			if($comptMedPyscho != 0)
			{
			?>
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;">
					<thead>
						<tr>
							<th style="width:50%"><?php echo getString(283);?></th>
							<th style="width:15%"><?php echo getString(145).' '.$nomassu;?></th>
							<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:15%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr>
					</thead>


					<tbody>
						<?php
						while($ligneMedPyscho=$resultMedPyscho->fetch())
						{
						?>
						<tr style="text-align:center;">
							<td>
							<?php
							$idassuPsycho = $ligneMedPyscho->id_assuPsy;

							//echo $ligneMedPyscho->id_consuPSy;

							$comptAssupsycho=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

							$comptAssupsycho->setFetchMode(PDO::FETCH_OBJ);

							$assuCount = $comptAssupsycho->rowCount();

							for($i=1;$i<=$assuCount;$i++)
							{

								$getAssuPyscho=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuPyscho->execute(array(
								'idassu'=>$idassuPsycho
								));

								$getAssuPyscho->setFetchMode(PDO::FETCH_OBJ);
								$count = $getAssuPyscho->rowCount();

								if($ligneNomAssuPyscho=$getAssuPyscho->fetch())
								{
									$presta_assuPyscho='prestations_'.$ligneNomAssuPyscho->nomassurance;
								}
							}


							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuPyscho.' p WHERE p.id_prestation=:prestaId');
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedPyscho->id_prestation
							));

							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();

							if($lignePresta=$resultPresta->fetch())
							{
								echo '<input type="text" name="idprestaPyscho[]" style="width:100px;display:none; text-align:center" id="idprestaPyscho" value="'.$lignePresta->id_prestation.'"/>';
								echo '<input type="text" name="autrePyscho[]" style="width:100px;display:none; text-align:center" id="autrepyscho" value=""/>';

								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								?>
							</td>

							<td>
								<input type="text" name="prixprestaPyscho[]" style="width:100px; text-align:center" id="prixprestaPyscho" class="prixprestaOrtho" value="<?php if($ligneMedPyscho->prixprestation!=0){ echo $ligneMedPyscho->prixprestation;}else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>
							</td>

							<!-- <td>
								<input type="text" name="prixprestaOrthoCCO[]" style="width:100px; text-align:center" id="prixprestaOrthoCCO" class="prixprestaOrthoCCO" value="<?php if($ligneMedPyscho->prixprestationOrthoCCO!=0){ echo $ligneMedPyscho->prixprestationOrthoCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>
							</td> -->
								<?php
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>

							<td>
								<input type="text" name="prixprestaPyscho[]" style="width:100px; text-align:center" id="prixprestaPyscho" class="prixprestaPyscho" value="<?php if($ligneMedPyscho->prixprestation!=0){ echo $ligneMedPyscho->prixprestation; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>
							</td>

							<!-- <td>
								<input type="text" name="prixprestaOrthoCCO[]" style="width:100px; text-align:center" id="prixprestaOrthoCCO" class="prixprestaOrthoCCO" value="<?php if($ligneMedPyscho->prixprestationOrthoCCO!=0){ echo $ligneMedPyscho->prixprestationOrthoCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>
							</td> -->
								<?php
								}
							}

							if($ligneMedPyscho->id_prestation==NULL AND ($ligneMedPyscho->prixautrePrestaM==0 OR $ligneMedPyscho->prixautrePrestaM==0))
							{
								echo '<input type="text" name="autreyscho[]" style="width:100px;display:none; text-align:center" id="autrePyscho" value="'.$ligneMedPyscho->autrePrestaO.'"/>';

								echo $ligneMedPyscho->autrePrestaO.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaPyscho[]" style="width:100px; text-align:center" id="prixprestaPyscho" class="prixprestaPyscho" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedPyscho->id_medpsy.'" style="width:100px;display:none; text-align:center" id="id_medpsy" value="'.$ligneMedPyscho->id_medpsy.'"/>
										
										<input type="hidden" name="idprestaPyscho[]" style="width:100px; text-align:center" id="idprestaPyscho" value="0"/>
									</td>
									';
							}else{

								if($ligneMedPyscho->id_prestation==NULL AND ($ligneMedPyscho->prixautrePrestaM!=0 OR $ligneMedPyscho->prixautrePrestaMCCO!=0))
								{
									echo $ligneMedPyscho->autrePrestaO.'
<input type="hidden" name="idprestaOrtho[]" style="width:100px;display:none;" id="idprestaOrtho" value="0"/>
									</td>';

									echo '<input type="text" name="autrePyscho[]" style="width:100px;display:none; text-align:center" id="autrePyscho" value="'.$ligneMedPyscho->autrePrestaM.'"/>';

									echo '<td>
									<input type="text" name="prixprestaPyscho[]" style="width:100px; text-align:center" id="prixprestaPyscho" class="prixprestaPyscho" value="'.$ligneMedPyscho->prixautrePrestaM.'"/>
									</td>
									';
								}
							}
							?>
							</td>

							<td>
								<input type="text" id="percentPyscho" name="percentPyscho[]" class="percentPyscho" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
								<input type="hidden" id="idmedPyscho" name="idmedPyscho[]" style="width:50px; text-align:center" value="<?php echo $ligneMedPyscho->id_medpsy;?>"/>

							</td>

							<!-- <td>
								<a href="categoriesbill.php?deleteMedOrtho=<?php echo $ligneMedPyscho->id_medortho;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td> -->
						</tr>
						<?php
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
							<th style="width:15%"><?php echo getString(145).' '.$nomassu;?></th>
							<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:15%"><?php echo getString(38);?></th>
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
								<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="<?php if($ligneMedInf->prixprestation!=0){ echo $ligneMedInf->prixprestation; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>								
							</td>
							<!-- 
							<td>								
								<input type="text" name="prixprestaInfCCO[]" style="width:100px; text-align:center" id="prixprestaInfCCO" class="prixprestaInfCCO" value="<?php if($ligneMedInf->prixprestationCCO!=0){ echo $ligneMedInf->prixprestationCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>								
							</td> -->
								<?php
								}else{								
									echo $lignePresta->nompresta;	
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="<?php if($ligneMedInf->prixprestation!=0){ echo $ligneMedInf->prixprestation; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>								
							</td>
							<!-- 
							<td>								
								<input type="text" name="prixprestaInfCCO[]" style="width:100px; text-align:center" id="prixprestaInfCCO" class="prixprestaInfCCO" value="<?php if($ligneMedInf->prixprestationCCO!=0){ echo $ligneMedInf->prixprestationCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>								
							</td> -->
								<?php
								}
							}
							
							if($ligneMedInf->id_prestation==NULL AND ($ligneMedInf->prixautrePrestaM==0 OR $ligneMedInf->prixautrePrestaM==0))
							{
								echo '<input type="text" name="autreInf[]" style="width:100px;display:none; text-align:center" id="autreInf" value="'.$ligneMedInf->autrePrestaM.'"/>';
									
								echo $ligneMedInf->autrePrestaM.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedInf->id_medinf.'" style="width:100px;display:none; text-align:center" id="id_medinf" value="'.$ligneMedInf->id_medinf.'"/>
										
										<input type="hidden" name="idprestaInf[]" style="width:100px; text-align:center" id="idprestaInf" value="0"/>
									</td>
									';
							}else{
							
								if($ligneMedInf->id_prestation==NULL AND ($ligneMedInf->prixautrePrestaM!=0 OR $ligneMedInf->prixautrePrestaMCCO!=0))
								{
									echo $ligneMedInf->autrePrestaM.'
<input type="hidden" name="idprestaInf[]" style="width:100px;display:none;" id="idprestaInf" value="0"/>
									</td>';
									
									echo '<input type="text" name="autreInf[]" style="width:100px;display:none; text-align:center" id="autreInf" value="'.$ligneMedInf->autrePrestaM.'"/>';
									
									echo '<td>
									<input type="text" name="prixprestaInf[]" style="width:100px; text-align:center" id="prixprestaInf" class="prixprestaInf" value="'.$ligneMedInf->prixautrePrestaM.'"/>
									</td>
									';
								}
							}
							?>
							</td>
							
							<td>
								<input type="text" id="percentInf" name="percentInf[]" class="percentInf" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
								<input type="hidden" id="idmedInf" name="idmedInf[]" style="width:50px; text-align:center" value="<?php echo $ligneMedInf->id_medinf;?>"/>
							
							</td>
							
							<!-- <td>
								<a href="categoriesbill.php?deleteMedInf=<?php echo $ligneMedInf->id_medinf;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
									 -->					
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
							<th style="width:15%"><?php echo getString(145).' '.$nomassu;?></th>
							<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:15%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						while($ligneMedLabo=$resultMedLabo->fetch())
						{
						?>
						<tr style="text-align:center;<?php if($ligneMedLabo->examenfait==1){ echo 'background:rgba(0,100,255,0.5);';}?>">
							<td>
							<?php
							
							$idassuLab=$ligneMedLabo->id_assuLab;

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
								<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="<?php if($ligneMedLabo->prixprestationExa!=0){ echo $ligneMedLabo->prixprestationExa; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>								
							</td>
							
							<!-- <td>								
								<input type="text" name="prixprestaLabCCO[]" style="width:100px; text-align:center" id="prixprestaLabCCO"  class="prixprestaLabCCO" value="<?php if($ligneMedLabo->prixprestationExaCCO!=0){ echo $ligneMedLabo->prixprestationExaCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>								
							</td> -->
								<?php
								
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="<?php if($ligneMedLabo->prixprestationExa!=0){ echo $ligneMedLabo->prixprestationExa; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}}?>"/>								
							</td>
							<!-- 
							<td>								
								<input type="text" name="prixprestaLabCCO[]" style="width:100px; text-align:center" id="prixprestaLabCCO"  class="prixprestaLabCCO" value="<?php if($ligneMedLabo->prixprestationExaCCO!=0){ echo $ligneMedLabo->prixprestationExaCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }else{ echo "";}}?>"/>								
							</td> -->
								<?php
								}
							}
							
							if($ligneMedLabo->id_prestationExa==NULL AND ($ligneMedLabo->prixautreExamen==0 OR $ligneMedLabo->prixautreExamenCCO==0))
							{
								echo '<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab" value="'.$ligneMedLabo->autreExamen.'"/>';
								
								echo $ligneMedLabo->autreExamen.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedLabo->id_medlabo.'" style="width:100px;display:none; text-align:center" id="id_medlabo" value="'.$ligneMedLabo->id_medlabo.'"/>
										
										<input type="hidden" name="idprestaLab[]" style="width:100px; text-align:center" id="idprestaLab" value="0"/>
									</td>
								';
							}else{

								if($ligneMedLabo->id_prestationExa==NULL AND ($ligneMedLabo->prixautreExamen!=0 OR $ligneMedLabo->prixautreExamenCCO!=0))
								{
									echo $ligneMedLabo->autreExamen.'
									<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab" value="'.$ligneMedLabo->autreExamen.'"/>
									
									<input type="hidden" name="idprestaLab[]" style="width:100px;display:none; text-align:center" id="idprestaLab" value="0"/>
									</td>';
								
									echo '<td>
									<input type="text" name="prixprestaLab[]" style="width:100px; text-align:center" id="prixprestaLab"  class="prixprestaLab" value="'.$ligneMedLabo->prixautreExamen.'"/></td>
									';
								}
							}
							?>
							</td>
							
							<td>
								<input type="text" id="percentLab" name="percentLab[]" class="percentLab" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
								<input type="hidden" id="idmedLab" name="idmedLab[]" style="width:50px; text-align:center" value="<?php echo $ligneMedLabo->id_medlabo;?>"/>
							</td>
							
							<!-- <td>
							<?php
							if($ligneMedLabo->examenfait!=1)
							{
							?>
								<a href="categoriesbill.php?deleteMedLabo=<?php echo $ligneMedLabo->id_medlabo;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							<?php
							}else{
							?>
								<i class="fa fa-check fa-1x fa-fw"></i>
							<?php
							}
							?>
							</td> -->
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
							<th style="width:15%"><?php echo getString(145).' '.$nomassu;?></th>
							<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:15%"><?php echo getString(38);?></th>
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
								<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="<?php if($ligneMedRadio->prixprestationRadio!=0){ echo $ligneMedRadio->prixprestationRadio; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }}?>"/>								
							</td>
							<!-- 
							<td>								
								<input type="text" name="prixprestaRadCCO[]" style="width:100px; text-align:center" id="prixprestaRadCCO"  class="prixprestaRadCCO" value="<?php if($ligneMedRadio->prixprestationRadioCCO!=0){ echo $ligneMedRadio->prixprestationRadioCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }}?>"/>								
							</td> -->
								<?php
								
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="<?php if($ligneMedRadio->prixprestationRadio!=0){ echo $ligneMedRadio->prixprestationRadio; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }}?>"/>								
							</td>
							<!-- 
							<td>								
								<input type="text" name="prixprestaRadCCO[]" style="width:100px; text-align:center" id="prixprestaRadCCO"  class="prixprestaRadCCO" value="<?php if($ligneMedRadio->prixprestationRadioCCO!=0){ echo $ligneMedRadio->prixprestationRadioCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }}?>"/>								
							</td> -->
								<?php
								}
							}
							
							if($ligneMedRadio->id_prestationRadio==NULL AND ($ligneMedRadio->prixautreRadio==0 OR $ligneMedRadio->prixautreRadioCCO==0))
							{
								echo '<input type="text" name="autreRad[]" style="width:100px;display:none; text-align:center" id="autreRad" value="'.$ligneMedRadio->autreRadio.'"/>';
								
								echo $ligneMedRadio->autreRadio.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedRadio->id_medradio.'" style="width:100px;display:none; text-align:center" id="id_medradio" value="'.$ligneMedRadio->id_medradio.'"/>
										
										<input type="hidden" name="idprestaRad[]" style="width:100px; text-align:center" id="idprestaRad" value="0"/>
									</td>
									';
							}else{

								if($ligneMedRadio->id_prestationRadio==NULL AND ($ligneMedRadio->prixautreRadio!=0 OR $ligneMedRadio->prixautreRadioCCO!=0))
								{
									echo $ligneMedRadio->autreRadio.'<input type="text" name="autreRad[]" style="width:100px;display:none; text-align:center" id="autreRad" value="'.$ligneMedRadio->autreRadio.'"/>
									<input type="hidden" name="idprestaRad[]" style="width:100px;display:none; text-align:center" id="idprestaRad" value="0"/>
									</td>';
									
									echo '<td>
									<input type="text" name="prixprestaRad[]" style="width:100px; text-align:center" id="prixprestaRad"  class="prixprestaRad" value="'.$ligneMedRadio->prixautreRadio.'"/></td>
									';
								}
							}
							?>
							</td>
							
							<td>
								<input type="text" id="percentRad" name="percentRad[]" class="percentRad" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
								<input type="hidden" id="idmedRad" name="idmedRad[]" style="width:50px; text-align:center" value="<?php echo $ligneMedRadio->id_medradio;?>"/>
							</td>
							
						<!-- 	<td>
								<a href="categoriesbill.php?deleteMedRadio=<?php echo $ligneMedRadio->id_medradio;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
														
							</td> -->
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
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:50%"><?php echo getString(214);?></th>
							<th style="width:10%"><?php echo getString(145).' '.$nomassu;?></th>
							<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
							<th style="width:10%"><?php echo getString(215);?></th>
							<th style="width:20%"><?php echo getString(38);?></th>
							<th style="width:10%"><?php echo 'Action';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						while($ligneMedConsom=$resultMedConsom->fetch())
						{
						?>
						<tr style="text-align:center;">
							<td>
							<?php
							
							$idassuConsom=$ligneMedConsom->id_assuConsom;
							
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
							
							$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assuConsom.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedConsom->id_prestationConsom
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();

							$chekAutreConsom=0;
							
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
								<input type="text" name="prixprestaConsom[]" style="width:100px;" id="prixprestaConsom"  class="prixprestaConsom"  value="<?php if($ligneMedConsom->prixprestationConsom!=0){ echo $ligneMedConsom->prixprestationConsom; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }}?>"/>
							</td>
							<!-- 
							<td>								
								<input type="text" name="prixprestaConsomCCO[]" style="width:100px;" id="prixprestaConsomCCO"  class="prixprestaConsomCCO"  value="<?php if($ligneMedConsom->prixprestationConsomCCO!=0){ echo $ligneMedConsom->prixprestationConsomCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }}?>"/>
							</td> -->
								<?php
								}else{
									echo $lignePresta->nompresta;
								?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaConsom[]" style="width:100px; text-align:center" id="prixprestaConsom"  class="prixprestaConsom"  value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo 0;}?>"/>								
							</td>
								<?php
								}
								
								$chekAutreConsom=1;
							}
							
							if($chekAutreConsom==0 AND ($ligneMedConsom->prixautreConsom==0 OR $ligneMedConsom->prixautreConsomCCO==0))
							{
								echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom" value="'.$ligneMedConsom->autreConsom.'"/>';
			
								echo $ligneMedConsom->autreConsom.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaConsom[]" style="width:100px; text-align:center" id="prixprestaConsom"  class="prixprestaConsom"  value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedConsom->id_medconsom.'" style="width:100px;display:none; text-align:center" id="id_medconsom" value="'.$ligneMedConsom->id_medconsom.'"/>
										
										<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom" value="0"/>
									</td>
									';
							}else{

								if($chekAutreConsom==0 AND ($ligneMedConsom->prixautreConsom!=0 OR $ligneMedConsom->prixautreConsomCCO!=0))
								{
									echo $ligneMedConsom->autreConsom.'<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom" value="0"/>
									</td>';
									echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom" value="'.$ligneMedConsom->autreConsom.'"/>';		
									echo '
									<td>
										<input type="text" name="prixprestaConsom[]" style="width:100px; text-align:center" id="prixprestaConsom"  class="prixprestaConsom"  value="'.$ligneMedConsom->prixautreConsom.'"/>									
									</td>
									';
								}
								
							}
							?>
						</td>
						
						<td>
							<input type="text" id="quantityConsom" name="quantityConsom[]" class="quantityConsom" style="width:30px; text-align:center" value="<?php if($ligneMedConsom->qteConsom ==0){ echo 1;}else{ echo $ligneMedConsom->qteConsom;}?>"/>
						
						</td>
						
						<td>
							<input type="text" id="percentConsom" name="percentConsom[]" class="percentConsom" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
							<input type="text" id="idmedConsom" name="idmedConsom[]" style="width:30px;display:none; text-align:center" value="<?php echo $ligneMedConsom->id_medconsom;?>"/>
						</td>
						
						<!-- <td>
							<a href="categoriesbill.php?deleteMedConsom=<?php echo $ligneMedConsom->id_medconsom;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
													
						</td> -->
					</tr>
					<?php
					}
					?>		
				</tbody>
				</table>
		<?php
			}

			if($comptMedMedoc != 0)
			{
		?>	
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
				<thead> 
					<tr>
						<th style="width:50%"><?php echo getString(216);?></th>
						<th style="width:10%"><?php echo getString(145).' '.$nomassu;?></th>
						<!-- <th style="width:10%"><?php echo getString(145).' ra';?></th> -->
						<th style="width:10%"><?php echo getString(215);?></th>
						<th style="width:20%"><?php echo getString(38);?></th>
						<th style="width:10%"><?php echo 'Action';?></th>
					</tr> 
				</thead> 


				<tbody>
					<?php
					while($ligneMedMedoc=$resultMedMedoc->fetch())
					{
					?>
					<tr style="text-align:center;">
						<td>
							<?php
							
							$idassuMedoc=$ligneMedMedoc->id_assuMedoc;
							
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

							
							$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assuMedoc.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedMedoc->id_prestationMedoc
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
																	
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
								<input type="text" name="prixprestaMedoc[]" style="width:100px;" id="prixprestaMedoc"  class="prixprestaMedoc"  value="<?php if($ligneMedMedoc->prixprestationMedoc!=0){ echo $ligneMedMedoc->prixprestationMedoc; }else{ if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }}?>"/>
							</td>
							<!-- 
							<td>								
								<input type="text" name="prixprestaMedocCCO[]" style="width:100px;" id="prixprestaMedocCCO"  class="prixprestaMedocCCO"  value="<?php if($ligneMedMedoc->prixprestationMedocCCO!=0){ echo $ligneMedMedoc->prixprestationMedocCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }}?>"/>
							</td> -->
								<?php
								}else{
									echo $lignePresta->nompresta;
									?>
							</td>
							
							<td>								
								<input type="text" name="prixprestaMedoc[]" style="width:100px; text-align:center" id="prixprestaMedoc" class="prixprestaMedoc" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta; }else{ echo "";}?>"/>
								
							</td>
							<!-- 
							<td>								
								<input type="text" name="prixprestaMedocCCO[]" style="width:100px;" id="prixprestaMedocCCO"  class="prixprestaMedocCCO"  value="<?php if($ligneMedMedoc->prixprestationMedocCCO!=0){ echo $ligneMedMedoc->prixprestationMedocCCO; }else{ if($lignePresta->prixprestaCCO!=-1){ echo $lignePresta->prixprestaCCO; }}?>"/>
							</td> -->
								<?php
								}
								
							}
							
							if($ligneMedMedoc->id_prestationMedoc==0 AND ($ligneMedMedoc->prixautreMedoc==0 OR $ligneMedMedoc->prixautreMedocCCO==0))
							{
								echo '<input type="text" name="autreMedoc[]" style="width:100px;display:none; text-align:center" id="autreMedoc" value="'.$ligneMedMedoc->autreMedoc.'"/>';

								echo $ligneMedMedoc->autreMedoc.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
									<td>
										<input type="text" name="prixprestaMedoc[]" style="width:100px; text-align:center" id="prixprestaMedoc" class="prixprestaMedoc" value="" placeholder="Tarrif ici..."/>
										
										<input type="text" name="'.$ligneMedMedoc->id_medmedoc.'" style="width:100px;display:none; text-align:center" id="id_medmedoc" value="'.$ligneMedMedoc->id_medmedoc.'"/>
										
										<input type="text" name="idprestaMedoc[]" style="width:100px;display:none; text-align:center" id="idprestaMedoc" value="0"/>
									</td>
									';

							}else{

								if($ligneMedMedoc->id_prestationMedoc==0 AND ($ligneMedMedoc->prixautreMedoc!=0 OR $ligneMedMedoc->prixautreMedocCCO!=0))
								{
									echo $ligneMedMedoc->autreMedoc.'<input type="text" name="idprestaMedoc[]" style="width:100px;display:none;" id="idprestaMedoc" value="0"/>
									</td>';
									echo '<input type="text" name="autreMedoc[]" style="width:100px;display:none;" id="autreMedoc" value="'.$ligneMedMedoc->autreMedoc.'"/>';		
									echo '
									<td>
									<input type="text" name="prixprestaMedoc[]" style="width:100px;" id="prixprestaMedoc" class="prixprestaMedoc" value="'.$ligneMedMedoc->prixautreMedoc.'"/>
									</td>
									';
								}
								
							}
							?>
							</td>
							
							<td>
								<input type="text" id="quantityMedoc" name="quantityMedoc[]" class="quantityMedoc" style="width:30px; text-align:center" value="<?php if($ligneMedMedoc->qteMedoc ==0){ echo 1;}else{ echo $ligneMedMedoc->qteMedoc;}?>"/>
							
							</td>
							
							<td>
								<input type="text" id="percentMedoc" name="percentMedoc[]" class="percentMedoc" style="width:30px; text-align:center" value="<?php echo $bill;?>"/> %
								<input type="text" id="idmedMedoc" name="idmedMedoc[]" style="width:30px;display:none; text-align:center" value="<?php echo $ligneMedMedoc->id_medmedoc;?>"/>
							</td>
							
							<!-- <td>
								<a href="categoriesbill.php?deleteMedMedoc=<?php echo $ligneMedMedoc->id_medmedoc;?>&cashier=<?php echo $_GET['cashier'];?>&num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
														
							</td> -->
						</tr>
					<?php
					}
					?>		
					</tbody>
				</table>
		<?php
			}
		?>
				<table class="tablesorter tablesorter1" cellspacing="0" style="background:none;border:none; width:70%; margin-top:10px;">
					<tr>
						<td>
							<input type="submit" id="previewbtn" name="previewbtn" class="btn-large" value="Preview Print"/>
						</td>
					</tr>
				</table>
			</form>
		<?php
			
		}
		?>
		</div>
			
	<?php
	}
	?>
	
	<?php
	if(isset($_GET['datefacture']))
	{

		if((isset($_GET['search']) and isset($_POST['datefacture'])) or ((isset($_GET['search'])!="ok") and (isset($_GET['datefacture'])!="ok")) or isset($_POST['step2']) or isset($_GET['delete']))
		{
			
	?>
		
		<div style="background:#fff;border:1px solid #eee;border-radius:3px;padding:20px;" class="step2"> 
		
		<table style="padding:20px;width:100%">	
			<tr>
				<td>
					<p class="patientId" style="text-align:center;color:#a00000;"><?php if(isset($_POST['datefacture'])){ echo $_POST['datefacture']; }else { if(isset($_GET['datefacture'])) echo $_GET['datefacture'];}?></p>
								
				</td>
			</tr>
			
			<tr>	
				<?php
				if(isset($_GET['datefacture']))
				{
					$codecashier=$_GET['cashier'];
					$numPa=$_GET['num'];
					
					if(isset($_POST['datefacture']))
					{
						$datefacture=$_POST['datefacture'];
					}else{
						$datefacture=$_GET['datefacture'];
					}



					$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.dateconsu=:datefacture AND c.numero=:num AND c.dateconsu!="0000-00-00" AND c.id_factureConsult IS NULL ORDER BY c.id_consu');		
					$resultConsult->execute(array(
					'num'=>$numPa,
					'datefacture'=>$datefacture	
					));
					
					$resultConsult->setFetchMode(PDO::FETCH_OBJ);

					$comptConsult=$resultConsult->rowCount();




					$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.dateconsu=:datefacture AND mc.numero=:num AND (mc.id_factureMedConsu=0 OR mc.id_factureMedConsu IS NULL) ORDER BY mc.id_medconsu');		
					$resultMedConsult->execute(array(
					'num'=>$numPa,
					'datefacture'=>$datefacture	
					));
					
					$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

					$comptMedConsult=$resultMedConsult->rowCount();




					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.dateconsu=:datefacture AND mi.soinsfait=1 AND mi.numero=:num AND (mi.id_factureMedInf=0 OR mi.id_factureMedInf IS NULL) ORDER BY mi.id_medinf');		
					$resultMedInf->execute(array(
					'num'=>$numPa,
					'datefacture'=>$datefacture	
					));
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

					$comptMedInf=$resultMedInf->rowCount();
				
				

					$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.dateconsu=:datefacture AND ml.examenfait=1 AND ml.numero=:num AND (ml.id_factureMedLabo=0 OR ml.id_factureMedLabo IS NULL) ORDER BY ml.id_medlabo');		
					$resultMedLabo->execute(array(
					'num'=>$numPa,
					'datefacture'=>$datefacture	
					));
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

					$comptMedLabo=$resultMedLabo->rowCount();
				

				?>
				<td style="background:#f8f8f8; border:1px solid #eee; border-radius:4px; padding:5px;">
				
				<form method="post" action="printTempBilling.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&datefacture=<?php if(isset($_POST['datefacture'])){ echo $_POST['datefacture']; }else { if(isset($_GET['datefacture'])) echo $_GET['datefacture'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormBilling(this)" enctype="multipart/form-data">
					<?php	
						if($comptConsult != 0)
						{
					?>
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%;"> 
						<thead> 
							<tr>
								<th><?php echo getString(113);?></th>
								<th><?php echo getString(145);?></th>
							</tr> 
						</thead> 


						<tbody>
					<?php
							while($ligneConsult=$resultConsult->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td>
								<?php
								$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneConsult->id_typeconsult
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>
										<td>'.$lignePresta->prixpresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>
										<td>'.$lignePresta->prixpresta.'</td>';
									}
								}
								?>
							</tr>
					<?php
							}
					?>		
						</tbody>
						</table>
					<?php
						}
						
						if($comptMedConsult != 0)
						{
					?>
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%; margin-top:10px;"> 
						<thead> 
							<tr>
								<th><?php echo getString(39);?>s</th>
								<th><?php echo getString(145);?></th>
							</tr> 
						</thead> 


						<tbody>
					<?php
							while($ligneMedConsult=$resultMedConsult->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td>
								<?php
								
								$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsult->id_prestationConsu
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>
										<td>'.$lignePresta->prixpresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>
										<td>'.$lignePresta->prixpresta.'</td>';
									}
								}
								
								if($ligneMedConsult->id_prestationConsu=="" AND $ligneMedConsult->prixautreConsu==0)
								{
									echo $ligneMedConsult->autreConsu.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
										<td>
											<input type="text" name="prixautreConsu'.$ligneMedConsult->id_medconsu.'" style="width:100px;  text-align:center" id="prixautreConsu" value="" placeholder="Tarrif ici..." required/>
											
											<input type="text" name="'.$ligneMedConsult->id_medconsu.'" style="width:100px;display:none; text-align:center" id="id_medconsu" value="'.$ligneMedConsult->id_medconsu.'"/></td>';
								
								}else{
									if($ligneMedConsult->id_prestationConsu=="" AND $ligneMedConsult->prixautreConsu!=0)
									{
										echo $ligneMedConsult->autreConsu.'</td>
										<td>'.$ligneMedConsult->prixautreConsu.'</td>';
									}
								}
								?>
							</tr>
					<?php
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
								<th><?php echo getString(98);?></th>
								<th><?php echo getString(145);?></th>
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
									
								$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneMedInf->id_prestation
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>
										<td>'.$lignePresta->prixpresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>
										<td>'.$lignePresta->prixpresta.'</td>';
									}
								}
								
								if($ligneMedInf->id_prestation=="" AND $ligneMedInf->prixautrePrestaM==0)
								{
									echo $ligneMedInf->autrePrestaM.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
										<td>
											<input type="text" name="prixautrePrestaM'.$ligneMedInf->id_medinf.'" style="width:100px; text-align:center" id="prixautrePrestaM" value="" placeholder="Tarrif ici..." required/>
											<input type="text" name="'.$ligneMedInf->id_medinf.'" style="width:100px;display:none; text-align:center" id="id_medinf" value="'.$ligneMedInf->id_medinf.'"/>
										
										</td>';
								}else{
									if($ligneMedInf->id_prestation=="" AND $ligneMedInf->prixautrePrestaM!=0)
									{
										echo $ligneMedInf->autrePrestaM.'</td>
										<td>'.$ligneMedInf->prixautrePrestaM.'</td>';
									}
								}
								?>
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
								<th><?php echo getString(99);?></th>
								<th><?php echo getString(145);?></th>
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
									$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedLabo->id_prestationExa
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des ??ents
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>
										<td>'.$lignePresta->prixpresta.'</td>';
										}else{
											echo $lignePresta->nompresta.'</td>
										<td>'.$lignePresta->prixpresta.'</td>';
										}
									}
									
									if($ligneMedLabo->id_prestationExa=="" AND $ligneMedLabo->prixautreExamen==0)
									{
										echo $ligneMedLabo->autreExamen.'<img src="icones/s_warn.png" style="width:20px;height:20px;margin-left:20px;"/></td>
											<td>
												<input type="text" name="prixautreExamen'.$ligneMedLabo->id_medlabo.'" style="width:100px; text-align:center" id="prixautreExamen" value="" placeholder="Tarrif ici..." required/>
												<input type="text" name="'.$ligneMedLabo->id_medlabo.'" style="width:100px;display:none; text-align:center" id="id_medlabo" value="'.$ligneMedLabo->id_medlabo.'"/>
											</td>';
									}else{

										if($ligneMedLabo->id_prestationExa=="" AND $ligneMedLabo->prixautreExamen!=0)
										{
											echo $ligneMedLabo->autreExamen.'</td>
											<td>'.$ligneMedLabo->prixautreExamen.'</td>';
										}
									}
									?>
								</td>
							</tr>
					<?php
							}
					?>		
						</tbody>
						</table>
					<?php
						}
					?>
						<p style="text-align: center;">
							<button style="width:300px; margin:10px auto auto;" type="submit" name="printbill" id="printbill" class="btn-large">
								<i class="fa fa-desktop fa-lg fa-fw"></i> <?php echo getString(148);?>
							</button>
						</p>
					</form>
				</td>
				
				<?php
				}
				?>
				
				<!--
				
				<td>
					<form id="formulairetest" method="post" action="billing.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&datefacture=<?php if(isset($_POST['datefacture'])){ echo $_POST['datefacture']; }else { if(isset($_GET['datefacture'])) echo $_GET['datefacture'];}?>&facture=ok&search=ok#addBilling">
				
					
					<input name="datefacture" type="hidden" value="<?php if(isset($_POST['datefacture'])){ echo $_POST['datefacture']; }else { if(isset($_GET['datefacture'])) echo $_GET['datefacture'];}?>" style="width:90px; text-align:center;" readonly="readonly"/>
					
					<table>
					
						<tr>
							<td>Autres</td>
							<td>
								<input type="text" name="autresPresta" style="height:30px;width:200px;margin-left:5px;border-radius:2px;"/>
							</td>
						</tr>
						
						<tr>
							<td>Prix Unitaire</td>
							<td style="text-align:left;">
								<input type="text" name="puPresta" style="height:30px;width:100px;margin-left:5px;border-radius:2px;"/>
							</td>
						</tr>
						
						<tr>
							<td>Quantity</td>
							<td style="text-align:left;">
								<input name="quantityPresta" type="text" value="1" style="height:30px;width:40px;margin-left:5px;border-radius:2px;"/>
							</td>
						</tr>
						
						<tr>
							<td></td>
							<td style="text-align:left;">
								<input type = "submit" value = "Add >>" id="step1" name="step1" class="btn"/>
							</td>
						</tr>
					
					</table>
									
										
					</form>
				</td>
				
				-->
				
			</tr>
			
			<tr>
				<td colspan="2">
				<div id="addBilling">

				<?php 	
				
					$codecashier=$_GET['cashier'];
					$numPa=$_GET['num'];
					
					
					if(isset($_POST['searchbtn']) or isset($_POST['step1']) or isset($_GET['delete']))
					{

						if(isset($_POST['datefacture']))
						{
							$datefacture=$_POST['datefacture'];
						}else{
							$datefacture=$_GET['datefacture'];				
						}
						
						if(isset($_POST['autresPresta']))
						{					
							$autresPresta=$_POST['autresPresta'];
							$prixUni=$_POST['puPresta'];
							$quantityPresta=$_POST['quantityPresta'];
							
							$totalTempBill= $prixUni * $quantityPresta;
																
						
							$resultat=$connexion->prepare('INSERT INTO temp_facture (autreprestaBill,prixunitaire,quantitytempfact,totaltempfact,date_tempfacture,numero,codecashier) VALUES(:autresPresta,:prixUni,:quantityPresta,:total,:datefacture,:numPa,:codecashier)');
							$resultat->execute(array(
							'autresPresta'=>nl2br($autresPresta),
							'prixUni'=>$prixUni,
							'quantityPresta'=>$quantityPresta,
							'total'=>$totalTempBill,
							'datefacture'=>$datefacture,
							'numPa'=>$numPa,
							'codecashier'=>$codecashier
							)) or die( print_r($connexion->errorInfo()));
						
						}
					
						$resultTempFacture=$connexion->query("SELECT * FROM temp_facture tf, patients p WHERE p.numero='$numPa' AND tf.date_tempfacture='$datefacture' AND p.numero= tf.numero ORDER BY tf.id_tempfacture DESC")or die( print_r($connexion->errorInfo()));
					
						$tempFact_rows = $resultTempFacture->rowCount();
						
						$resultTempFacture->setFetchMode(PDO::FETCH_OBJ);
					

						if($tempFact_rows != 0)
						{
					?>			
						
							<input name="cashier" type="hidden" value="<?php echo $codecashier;?>" readonly="readonly"/>
							
							<input name="numPa" type="hidden" value="<?php echo $numPa;?>" readonly="readonly"/>
							
							<input name="datefacture" type="hidden" value="<?php echo $datefacture;?>" readonly="readonly"/>
							
							<table class="tablesorter" cellspacing="0"  style="background:rgb(218,254,169);margin-top:20px;" id="TabBilling"> 
								<thead> 
									<tr>
										<th>Date</th>
										<th>Title</th>
										<th>Price/item</th>
										<th>Quantity</th>
										<th>Total</th>
										<th>Done by</th>
										<th>Action</th>
									</tr> 
								</thead> 


								<tbody>
							<?php 	
						
								while($ligneTempFacture=$resultTempFacture->fetch())
								{
							?>
									<tr align="center">
										<td><?php echo $ligneTempFacture->date_tempfacture;?></td>
										<td><?php echo $ligneTempFacture->autreprestaBill;?></td>
										<td><?php echo $ligneTempFacture->prixunitaire;?></td>
										<td><?php echo $ligneTempFacture->quantitytempfact;?></td>
										
										<td><?php echo $ligneTempFacture->totaltempfact;?></td>
										
										<td>
									<?php 
										$resultcashier=$connexion->query('SELECT *FROM cashiernateurs c, utilisateurs u WHERE c.id_u=u.id_u') or die( print_r($connexion->errorInfo()));

										$resultcashier->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet
										$cashier_rows = $resultcashier->rowCount();
									while($lignecashier=$resultcashier->fetch())
									{
										if($ligneTempFacture->codecashier == $lignecashier->codecashier)
										{	
											echo $lignecashier->nom_u.' '.$lignecashier->prenom_u;
										}
									}
									?>
										</td>
										<td>
											<a href="traitement_billing.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&search=ok&datefacture=<?php if(isset($_POST['datefacture'])){ echo $_POST['datefacture']; }else { if(isset($_GET['datefacture'])) echo $_GET['datefacture'];}?>&deleteid_item=<?php echo $ligneTempFacture->id_tempfacture;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" name="deleteTempitem" class="btn"/>Supprimer</a>
										</td>
									</tr> 
								<?php
								}$resultTempFacture->closeCursor();
								?>
								</tbody>
							</table>
					<?php
						}
					}
					?>
				</div>
				</td>
				<td>
				</td>
			</tr>
			
			
		</table>
		
		</div>
		<?php 	
		}
	}
	
	if(isset($_POST['searchreport']))
	{
	?>
	
	<div class="account-container" style="margin: 30px auto auto;width:90%;border: 1px solid #eee;background:#fff;padding:20px;border-radius:3px;">

<?php

		$codecashier=$_GET['cashier'];
		$numPa=$_GET['num'];
		$datedebut=$_POST['datedebut'];
		
		if($_POST['datefin'] != "")
		{
			$datefin=$_POST['datefin'];
		}else{
			$datefin=$datedebut;
		}
		
		$TotalGnl = 0;
		
			
		$resultatscashier=$connexion->prepare('SELECT *FROM utilisateurs u, cashiernateurs c WHERE u.id_u=c.id_u and c.codecashier=:operation');
		$resultatscashier->execute(array(
		'operation'=>$codecashier	
		));
		
		$resultatscashier->setFetchMode(PDO::FETCH_OBJ);
		if($lignecashier=$resultatscashier->fetch())
		{
			echo '<b style="margin:30px">Done by : '.$lignecashier->nom_u.'  '.$lignecashier->prenom_u.'</b><br/><br/>';
		}	
		
		$resultatsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultatsPatient->execute(array(
		'operation'=>$numPa	
		));
		
		$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
		if($lignePatient=$resultatsPatient->fetch())
		{
			echo '<b style="margin:30px">Patient\'s Full name : '.$lignePatient->nom_u.'  '.$lignePatient->prenom_u.'</b><br/>';
		}	
		
		echo '<b style="margin:30px">From : '.$datedebut.'</b><br/>';
		echo '<b style="margin:30px">To : '.$datefin.'</b><br/>';
		
		
		
			
		/*-------Requ?e pour AFFICHER FACTURE-----------*/


		/*-------Requ?e pour AFFICHER Type consultation-----------*/
		
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE p.numero=:num AND p.numero=c.numero AND c.dateconsu>=:datedebut AND c.dateconsu<=:datefin AND c.numero=:num ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'num'=>$numPa,
		'datedebut'=>$datedebut,
		'datefin'=>$datefin
		));
		
		$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet

		$comptConsult=$resultConsult->rowCount();
		
		$TotalConsult = 0;
		
			
	
		/*-------Requ?e pour AFFICHER Med_consult-----------*/
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.dateconsu>=:datedebut AND mc.dateconsu<=:datefin AND mc.numero=:num ORDER BY mc.id_medconsu');		
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'datedebut'=>$datedebut,
		'datefin'=>$datefin
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
	
	
	
		/*-------Requ?e pour AFFICHER Med_inf-----------*/
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.dateconsu>=:datedebut AND mi.dateconsu<=:datefin AND mi.numero=:num ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'datedebut'=>$datedebut,
		'datefin'=>$datefin
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet

		$comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
		
	
	
		/*-------Requ?e pour AFFICHER Med_labo-----------*/
	
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.dateconsu>=:datedebut AND ml.dateconsu<=:datefin AND ml.numero=:num ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'datedebut'=>$datedebut,
		'datefin'=>$datefin
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet

		$comptMedLabo=$resultMedLabo->rowCount();			
		
		$TotalMedLabo = 0;
		
		
		
		/*-------Requ?e pour AFFICHER Temp_facture-----------*/
		
		$resultatsTempFact=$connexion->query("SELECT * FROM temp_facture tf, patients p WHERE p.numero='$numPa' AND tf.date_tempfacture>='$datedebut' AND tf.date_tempfacture<='$datefin' AND p.numero= tf.numero ORDER BY tf.id_tempfacture DESC") or die( print_r($connexion->errorInfo()));

		$resultatsTempFact->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet
	
		$comptResultTempFact=$resultatsTempFact->rowCount();
		
		$TotalTempFact = 0;

			
		if($comptResultTempFact!=0)
		{
			$title='';
			$priceItem='';
			$quantity='';
			$total='';
		
			while($ligne=$resultatsTempFact->fetch())//on r?up?e la liste des ??ents
			{
				$billpercent=$ligne->bill;
				
				$resultPresta=$connexion->query('SELECT *FROM prestations WHERE id_prestation='.$ligne->id_prestation.'') or die( print_r($connexion->errorInfo()));

				$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet
					
				$presta_rows = $resultPresta->rowCount();
					
				while($lignePresta=$resultPresta->fetch())
				{
					if($ligne->id_prestation == $lignePresta->id_prestation)
					{	
						$title=$title.'<br/>'.$lignePresta->nompresta;
						// echo $title;
				
						$priceItem=$priceItem.'<br/>'.$lignePresta->prixpresta;
						// echo $priceItem;
			
					}
				}
				
					$quantity=$quantity.'<br/>'.$ligne->quantitytempfact;
					// echo $quantity;
				
					$total=$total.'<br/>'.$ligne->totaltempfact;
					// echo $total;
				
					$TotalGnl = $TotalGnl+$ligne->totaltempfact;

			}
			$resultatsTempFact->closeCursor();
			
		}
?>
	<b><h2 style="margin-left:150px">Report</h2></b>
	
	<br/>

		<?php
		try
		{
			if($comptConsult != 0)
			{
			?>
			
			<table class="tablesorter" cellspacing="0" style="margin:auto;width:90%;"> 
				<thead> 
					<tr>
						<th>Type de Consultation</th>
						<th>Price</th>
						<th>Patient price</th>
						<th>UAP price</th>
					</tr> 
				</thead> 


				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneConsult=$resultConsult->fetch())
					{
					
						$billpercent=$ligneConsult->bill;
			?>
					<tr style="text-align:center;">
						<td>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
							'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des ??ents
						{
							if($lignePresta->namepresta!='')
							{
								echo $lignePresta->namepresta.'</td>';
							}else{								
								echo $lignePresta->nompresta.'</td>';
							}
							
							echo '<td>'.$lignePresta->prixpresta.'</td>';
						}
						
						$TotalConsult=$TotalConsult + $lignePresta->prixpresta;
						?>
						<td>
						<?php 
							$patientPrice=($lignePresta->prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice;
						?>
						</td>
						<td>
						<?php 
							$uapPrice= $lignePresta->prixpresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $lignePresta->prixpresta - $patientPrice;
						?>
						</td>
					</tr>
			<?php
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size:15px">
							<?php						
								echo $TotalConsult;						
							?>
						</td>
						<td style="font-size:15px">
							<?php						
								echo $TotalpatientPrice;				
							?>
						</td>
						<td style="font-size:15px">
							<?php						
								echo $TotaluapPrice;					
							?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			}
			
			if($comptMedConsult != 0)
			{
			?>
			
			<table class="tablesorter" cellspacing="0" style="margin:auto;width:90%;">
				<thead> 
					<tr>
						<th>Consultation</th>
						<th>Price</th>						
						<th>Patient price</th>
						<th>UAP price</th>
					</tr> 
				</thead> 


				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedConsult=$resultMedConsult->fetch())
					{
						
						$billpercent=$ligneMedConsult->bill;
			?>
					<tr style="text-align:center;">
						<td>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedConsult->id_prestationConsu
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des ??ents
						{
							if($lignePresta->namepresta!='')
							{
								echo $lignePresta->namepresta.'</td>';
							}else{								
								echo $lignePresta->nompresta.'</td>';
							}
							
							echo '<td>'.$lignePresta->prixpresta.'</td>';
						}
						
						$TotalMedConsult=$TotalMedConsult + $lignePresta->prixpresta;
						?>
						<td>
						<?php 
							$patientPrice=($lignePresta->prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice;
						?>
						</td>
						<td>
						<?php 
							$uapPrice= $lignePresta->prixpresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $lignePresta->prixpresta - $patientPrice;
						?>
						</td>
					</tr>
			<?php
					}
			?>			
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size:15px">
							<?php						
								echo $TotalMedConsult;						
							?>
						</td>
						<td style="font-size:15px">
							<?php						
								echo $TotalpatientPrice;				
							?>
						</td>
						<td style="font-size:15px">
							<?php						
								echo $TotaluapPrice;					
							?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			}
			
			if($comptMedInf != 0)
			{
			?>
			
			<table class="tablesorter" cellspacing="0" style="margin:auto;width:90%;">
				<thead> 
					<tr>
						<th>Nursery</th>
						<th>Price</th>						
						<th>Patient price</th>
						<th>UAP price</th>
					</tr> 
				</thead> 


				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedInf=$resultMedInf->fetch())
					{
					
						$billpercent=$ligneMedInf->bill;
			?>
					<tr style="text-align:center;">
						<td>
						<?php 
							
						$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
							'prestaId'=>$ligneMedInf->id_prestation
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des ??ents
						{
							if($lignePresta->namepresta!='')
							{
								echo $lignePresta->namepresta.'</td>';
							}else{								
								echo $lignePresta->nompresta.'</td>';
							}
							
							echo '<td>'.$lignePresta->prixpresta.'</td>';
						}
						
						$TotalMedInf = $TotalMedInf + $lignePresta->prixpresta;
						?>
						</td>
						<td>
						<?php 
							$patientPrice=($lignePresta->prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice;
						?>
						</td>
						<td>
						<?php 
							$uapPrice= $lignePresta->prixpresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $lignePresta->prixpresta - $patientPrice;
						?>
						</td>
					</tr>
			<?php
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size:15px">
							<?php						
								echo $TotalMedInf;						
							?>
						</td>
						<td style="font-size:15px">
							<?php						
								echo $TotalpatientPrice;				
							?>
						</td>
						<td style="font-size:15px">
							<?php						
								echo $TotaluapPrice;					
							?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			}
			
			if($comptMedLabo != 0)
			{
			?>
			
			<table class="tablesorter" cellspacing="0" style="margin:auto;width:90%;">
				<thead> 
					<tr>
						<th>Labs</th>
						<th>Price</th>						
						<th>Patient price</th>
						<th>UAP price</th>
					</tr> 
				</thead> 


				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedLabo=$resultMedLabo->fetch())
					{
					
						$billpercent=$ligneMedLabo->bill;
			?>
					<tr style="text-align:center;">
						<td>
							<?php
							$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
							
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedLabo->id_prestationExa
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ultat soit r?up?able sous forme d'objet

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())//on recupere la liste des ??ents
							{
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta.'</td>';
								}else{								
									echo $lignePresta->nompresta.'</td>';
								}
								
								echo '<td>'.$lignePresta->prixpresta.'</td>';
							}
							
							$TotalMedLabo = $TotalMedLabo + $lignePresta->prixpresta;
							?>
						</td>
						<td>
						<?php 
							$patientPrice=($lignePresta->prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice;
						?>
						</td>
						<td>
						<?php 
							$uapPrice= $lignePresta->prixpresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $lignePresta->prixpresta - $patientPrice;
						?>
						</td>
					</tr>
			<?php
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size:15px">
							<?php						
								echo $TotalMedLabo;						
							?>
						</td>
						<td style="font-size:15px">
							<?php						
								echo $TotalpatientPrice;				
							?>
						</td>
						<td style="font-size:15px">
							<?php						
								echo $TotaluapPrice;					
							?>
						</td>
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

		?>
	
		<a href="billing.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&search=ok" name="printbtn" class="btn-large"/>Report</a>
	
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
		
		if(more==4)
		{
			
			document.getElementById('divViewSurge').style.display='block';	
		}else{
		
			document.getElementById('divViewSurge').style.display='none';
			
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
		
		if(more==14)
		{
			
			document.getElementById('divViewKine').style.display='block';
		}else{
		
			document.getElementById('divViewKine').style.display='none';
			
		}

		if(more==23)
		{

			document.getElementById('divViewOrtho').style.display='block';
		}else{

			document.getElementById('divViewOrtho').style.display='none';

		}

		if(more="services" || more==4 || more==3 || more==12 || more==13 || more==14 || more==21 || more==22 || more==23)
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

	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	
		$('#checkprestaSurge').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaServ').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaInf').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaLab').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaRad').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaConsom').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaMedoc').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaKine').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#checkprestaOrtho').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
	</script>
	
</body>
</html>