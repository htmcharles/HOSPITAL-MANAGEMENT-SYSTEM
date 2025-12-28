<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");

$id_Bill = $_GET['idbill'];
if(isset($_GET['deleteConsu']))
{

	$id_Consu = $_GET['deleteConsu'];
	$id_Bill = $_GET['idbill'];
	
		
	$deleteBill=$connexion->prepare('DELETE FROM bills WHERE id_bill=:id_Bill');
	
	$deleteBill->execute(array(
	'id_Bill'=>$id_Bill
	
	))or die( print_r($connexion->errorInfo()));

	$deleteConsult=$connexion->prepare('DELETE FROM consultations WHERE id_factureConsult=:id_Bill AND id_consu=:id_consu');
	
	$deleteConsult->execute(array(
	'id_Bill'=>$id_Bill,
	'id_consu'=>$_GET['deleteConsu']
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer la consultation et la facture");</script>';
	
	echo '<script type="text/javascript">document.location.href="facturesedit.php?manager='.$_GET['manager'].'";</script>';
	
}


if(isset($_GET['deleteMedConsu']))
{
	
	$deleteConsu=$connexion->prepare('DELETE FROM med_consult WHERE id_factureMedConsu=:id_Bill AND id_medconsu=:id_medconsu');
	
	$deleteConsu->execute(array(
	'id_Bill'=>$id_Bill,
	'id_medconsu'=>$_GET['deleteMedConsu']
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un service");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBill.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&finishbtn=ok";</script>';
	
}


if(isset($_GET['deleteMedSurge']))
{
	
	$deleteSurge=$connexion->prepare('DELETE FROM med_surge WHERE id_factureMedSurge=:id_Bill AND id_medsurge=:id_medsurge');
	
	$deleteSurge->execute(array(
	'id_Bill'=>$id_Bill,
	'id_medsurge'=>$_GET['deleteMedSurge']
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un acte");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBill.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&finishbtn=ok";</script>';
	
}


if(isset($_GET['deleteMedInf']))
{
	
	$deleteInf=$connexion->prepare('DELETE FROM med_inf WHERE id_factureMedInf=:id_Bill AND id_medinf=:id_medinf');
	
	$deleteInf->execute(array(
	'id_Bill'=>$id_Bill,
	'id_medinf'=>$_GET['deleteMedInf']
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un soins");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBill.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&finishbtn=ok";</script>';
	
}

if(isset($_GET['deleteMedLabo']))
{
	
	$deleteLabo=$connexion->prepare('DELETE FROM med_labo WHERE id_factureMedLabo=:id_Bill AND id_medlabo=:id_medlabo');
	
	$deleteLabo->execute(array(
	'id_Bill'=>$id_Bill,
	'id_medlabo'=>$_GET['deleteMedLabo']
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un examen");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBill.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&finishbtn=ok";</script>';
	
}

if(isset($_GET['deleteMedRadio']))
{
	
	$deleteRadio=$connexion->prepare('DELETE FROM med_radio WHERE id_factureMedRadio=:id_Bill AND id_medradio=:id_medradio');
	
	$deleteRadio->execute(array(
	'id_Bill'=>$id_Bill,
	'id_medradio'=>$_GET['deleteMedRadio']
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une radio");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBill.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&finishbtn=ok";</script>';
	
}


if(isset($_GET['deleteMedKine']))
{
		
	$deleteKine=$connexion->prepare('DELETE FROM med_kine WHERE id_factureMedKine=:id_Bill AND id_medkine=:id_medkine');
	
	$deleteKine->execute(array(
		'id_Bill'=>$id_Bill,
		'id_medkine'=>$_GET['deleteMedKine']
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une kine");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBill.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&finishbtn=ok";</script>';
	
}


if(isset($_GET['deleteMedOrtho']))
{
		
	$deleteOrtho=$connexion->prepare('DELETE FROM med_kine WHERE id_factureMedOrtho=:id_Bill AND id_medortho=:id_medortho');
	
	$deleteOrtho->execute(array(
		'id_Bill'=>$id_Bill,
		'id_medortho'=>$_GET['deleteMedOrtho']
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un appareil");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBill.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&finishbtn=ok";</script>';
	
}

if(isset($_GET['deleteMedConsom']))
{
	
	$deleteConsom=$connexion->prepare('DELETE FROM med_consom WHERE id_factureMedConsom=:id_Bill AND id_medconsom=:id_medconsom');
	
	$deleteConsom->execute(array(
	'id_Bill'=>$id_Bill,
	'id_medconsom'=>$_GET['deleteMedConsom']
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un consommable");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBill.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&finishbtn=ok";</script>';
	
}

if(isset($_GET['deleteMedMedoc']))
{
	
	$deleteMedoc=$connexion->prepare('DELETE FROM med_medoc WHERE id_factureMedMedoc=:id_Bill AND id_medmedoc=:id_medmedoc');
	
	$deleteMedoc->execute(array(
	'id_Bill'=>$id_Bill,
	'id_medmedoc'=>$_GET['deleteMedMedoc']
	))or die( print_r($connexion->errorInfo()));
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un medicament");</script>';
	
	echo '<script type="text/javascript">document.location.href="formModifierBill.php?manager='.$_GET['manager'].'&num='.$_GET['num'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&finishbtn=ok";</script>';

}

		
	$getIdBill=$connexion->prepare('SELECT * FROM bills b WHERE b.id_bill=:idbill AND b.dette IS NOT NULL');
	$getIdBill->execute(array(
		'idbill'=>$_GET['idbill']
	));
	
	$getIdBill->setFetchMode(PDO::FETCH_OBJ);
	
	$idBillCount = $getIdBill->rowCount();
	
	if($ligneIdBill=$getIdBill->fetch())
	{
		$dettes=$ligneIdBill->dette;
	}else{
		$dettes=NULL;
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
	$id_Bill=$_GET['idbill'];
	
	
	$checkIdBill=$connexion->prepare('SELECT *FROM bills b WHERE b.id_bill=:idbill ORDER BY b.id_bill LIMIT 1');

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
		$datebill = $ligne->datebill;

            $resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.nomassurance=:assuName');

            $resultAssurance->execute(array(
                'assuName'=>$ligne->nomassurance
            ));

            $resultAssurance->setFetchMode(PDO::FETCH_OBJ);

            if($ligneAssu=$resultAssurance->fetch())
            {
                $idassubill = $ligneAssu->id_assurance;
                $nomassurancebill = $ligneAssu->nomassurance;
            }

        $idorgBill = $ligne->idorgBill;
		$idcardbill = $ligne->idcardbill;
		$numpolicebill = $ligne->numpolicebill;
		$adherentbill = $ligne->adherentbill;
		$percentIdbill = $ligne->billpercent;
		$dateconsu = $ligne->dateconsu;
		$vouchernum = $ligne->vouchernum;
		
		$numbill = $ligne->numbill;
		$createBill = 0;


		$GetNumero = $connexion->prepare("SELECT * FROM bills WHERE id_bill=:id_bill");
		$GetNumero->execute(array('id_bill'=>$_GET['idbill']));
		$GetNumero->setFetchMode(PDO::FETCH_OBJ);
		$FetchNumer = $GetNumero->fetch();

		$cashierNumero = $FetchNumer->codecashier;
		//echo $cashierNumero;
		
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
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
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
		
		$resultatConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_factureConsult=:id_Bill AND p.numero=:num AND p.numero=c.numero AND c.numero=:num ORDER BY c.id_consu');
		$resultatConsult->execute(array(
		'id_Bill'=>$id_Bill,
		'num'=>$numPa
		));

		$resultatConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptConsult=$resultatConsult->rowCount();
		
		$TotalConsult = 0;
		$TotalConsultCCO = 0;

	
		/*-------Requête pour AFFICHER Med_surge-----------*/
	
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_factureMedSurge=:id_Bill ORDER BY ms.id_medsurge');
		$resultMedSurge->execute(array(
		'num'=>$numPa,
		'id_Bill'=>$id_Bill
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

		$comptMedSurge=$resultMedSurge->rowCount();
	
		$TotalMedSurge = 0;
		$TotalMedSurgeCCO = 0;

	
	
		/*-------Requête pour AFFICHER Med_inf-----------*/

		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_factureMedInf=:id_Bill ORDER BY mi.id_medinf');
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'id_Bill'=>$id_Bill
		));

		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();

		$TotalMedInf = 0;
		$TotalMedInfCCO = 0;



		/*-------Requête pour AFFICHER Med_labo-----------*/
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_factureMedLabo=:id_Bill ORDER BY ml.id_medlabo');
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'id_Bill'=>$id_Bill
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();
		
		$TotalMedLabo = 0;
		$TotalMedLaboCCO = 0;

	
	
	
		/*-------Requête pour AFFICHER Med_radio-----------*/
	
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_factureMedRadio=:id_Bill ORDER BY mr.id_medradio');
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'id_Bill'=>$id_Bill
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
		$TotalMedRadioCCO = 0;



        /*-------Requête pour AFFICHER Med_Kine-----------*/

        $resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_factureMedKine=:id_Bill ORDER BY mk.id_medkine');
        $resultMedKine->execute(array(
            'num'=>$numPa,
            'id_Bill'=>$id_Bill
        ));

        $resultMedKine->setFetchMode(PDO::FETCH_OBJ);

        $comptMedKine=$resultMedKine->rowCount();

        $TotalMedKine = 0;
        $TotalMedKineCCO = 0;



        /*-------Requête pour AFFICHER Med_ortho-----------*/

        $resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_factureMedOrtho=:id_Bill ORDER BY mo.id_medortho');
        $resultMedOrtho->execute(array(
            'num'=>$numPa,
            'id_Bill'=>$id_Bill
        ));

        $resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

        $comptMedOrtho=$resultMedOrtho->rowCount();

        $TotalMedOrtho = 0;
        $TotalMedOrthoCCO = 0;

//echo 'SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero='.$numPa.' AND mo.id_consuOrtho='.$_GET['idconsu'].' AND mo.id_factureMedOrtho!=0 ORDER BY mo.id_medortho';

        /*-------Requête pour AFFICHER Med_consom-----------*/
		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=mco.numero AND mco.numero=:num AND mco.id_factureMedConsom=:id_Bill ORDER BY mco.id_medconsom');
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'id_Bill'=>$id_Bill
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		$TotalMedConsomCCO = 0;

	// echo 'SELECT *FROM med_consom mco, patients p WHERE p.numero='.$numPa.' AND p.numero=mco.numero AND mco.numero='.$numPa.' AND mco.id_consuConsom='.$_GET['idconsu'].' AND mco.id_factureMedConsom!=0 ORDER BY mco.id_medconsom';
	
		/*-------Requête pour AFFICHER Med_medoc-----------*/
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_factureMedMedoc=:id_Bill ORDER BY mdo.id_medmedoc');
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'id_Bill'=>$id_Bill
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
		$TotalMedMedocCCO = 0;


        /*-------Requête pour AFFICHER Med_consult-----------*/

        $resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_factureMedConsu=:id_Bill AND mc.dateconsu!="0000-00-00" ORDER BY mc.id_medconsu');
        $resultMedConsult->execute(array(
        'num'=>$numPa,
        'id_Bill'=>$id_Bill
        ));

        $resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

        $comptMedConsult=$resultMedConsult->rowCount();

        $TotalMedConsult = 0;
        $TotalMedConsultCCO = 0;





/*--------------Billing Info Patient-----------------*/
		
		$resultatsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultatsPatient->execute(array(
		'operation'=>$numPa
		));

		if (isset($_GET['datefacture'])) {
			$dateConsult = date('Y-m-d', strtotime($_GET['datefacture']));
		}else{
			$selectdate = $connexion->prepare('SELECT * FROM bills WHERE id_bill=:idbill');
			$selectdate->execute(array(
				'idbill'=>$_GET['idbill']
			));
			$selectdate->setFetchMode(PDO::FETCH_OBJ);
			$lignesect = $selectdate->fetch();
			$dateConsult = date('Y-m-d', strtotime($lignesect->datebill));
		}
		

		$resultatConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.dateconsu=:dateconsu');
		$resultatConsu->execute(array(
		'num'=>$_GET['num'],
		'dateconsu'=>$dateConsult
		));
		
		$resultatConsu->setFetchMode(PDO::FETCH_OBJ);

		if($ligneConsu=$resultatConsu->fetch())
		{
			$idassurance=$ligneConsu->id_assuConsu;
			$dateconsu= date('d-M-Y', strtotime($ligneConsu->dateconsu));
			$resultIdDoc=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
			$resultIdDoc->execute(array(
			'operation'=>$ligneConsu->id_uM
			));
			$resultIdDoc->setFetchMode(PDO::FETCH_OBJ);
			
			
			if($ligneIdDoc=$resultIdDoc->fetch())
			{
				$codeDoc=$ligneIdDoc->codemedecin;
				$fullnameDoc=$ligneIdDoc->nom_u.' '.$ligneIdDoc->prenom_u;
			}
		}
		
	?>
	
	
		<form method="post" action="printBill_modifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}?>" onsubmit="return controlFormRembou(this)" enctype="multipart/form-data">

		<table style="width:100%; margin:20px auto auto;">
			<tr>
				<td style="text-align:left;">
				
				</td>
				
				<td style="text-align:center;">
					<h2 style="font-size:150%; font-weight:600;">Formulaire Modification Facture n° <?php echo $numbill;?></h2>
				</td>
				
				<td>
					<a href="categoriesbill_modifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?>&finishbtn=ok<?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}else{ echo '&billpercent='.$percentIdbill;}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="addmorebtn" style="margin:5px;">
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
		
		<table style="width:100%; margin:20px auto auto;">
			<tr>
				<td style="text-align:left">
					Date of bill:
					<select name="annee" id="annee" style="width:100px;" onchange="myScriptAnnee()">
						<?php
						for($a=2016;$a<=2050;$a++)
						{
							$anneeBill=date('Y', strtotime($datebill));
						?>
							<option value="<?php echo $a;?>" <?php if($anneeBill==$a) echo 'selected="selected"';?>>
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
							$moisBill=date("F",mktime(0,0,0,date('m', strtotime($datebill)),10));
						?>
							<option value="<?php echo $m;?>" <?php if($moisBill==$moisString) echo 'selected="selected"'; ?>>
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
					
					<input size="25px" type="hidden" id="jourN" name="jourN" value="<?php echo $joursBill=date('d', strtotime($datebill));?>"/>
					
					<span style="margin:5px">à</span>
					
					<input style="width:80px;margin-right:5px;" type="text" id="heureBill" name="heureBill" value="<?php echo $heureBill=date('H', strtotime($datebill));?>"/> H
					
					
					<input style="width:80px;" type="text" id="minuteBill" name="minuteBill" value="<?php echo $minuteBill=date('i', strtotime($datebill));?>"/> min
					
					<input style="width:80px;" type="text" id="secondeBill" name="secondeBill" value="<?php echo $secondeBill=date('s', strtotime($datebill));?>"/> sec
					
				</td>
			</tr>
		</table>
		
		<?php

		$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
		
		if($lignePatient=$resultatsPatient->fetch())
		{
			
			$fullname= $lignePatient->full_name;
			$bill= $lignePatient->bill;
			$idassurance=$lignePatient->id_assurance;
			$idorg=$lignePatient->id_org;
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
			
				<td style="text-align:right;">
                    Organisation:
                    <select name="org" id="org" style="width:40%;" onchange="ShowOrg('org')">

                        <?php
                        $resultats = $connexion->query('SELECT *FROM organisations ORDER BY nomOrg');

                        while ($ligne = $resultats->fetch(PDO::FETCH_OBJ)) {
                            ?>
                            <option value="<?php echo $ligne->id_org; ?>" id="organisation"
                                    style="font-weight:bold" <?php if ($idorgBill == $ligne->id_org) {
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

							<option value="1" id="noinsurance" style="font-weight:bold" <?php if($idassubill == 1){echo "selected='selected'";}?>>
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
					
					Date of Consultation:
					<select name="anneeC" id="anneeC" style="width:100px;" onchange="myScriptAnneeC()">
						<?php
						for($a=2016;$a<=2050;$a++)
						{
							$anneeConsu=date('Y', strtotime($dateconsu));
						?>
							<option value="<?php echo $a;?>" style="font-weight:bold" <?php if($anneeConsu==$a) echo 'selected="selected"';?>>
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
							$moisConsu=date("F",mktime(0,0,0,date('m', strtotime($dateconsu)),10));
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
					
					
					<select name="joursC" id="joursC" style="width:80px;">
						<?php
						for($d=1;$d<=31;$d++)
						{
							$joursConsu=date('d', strtotime($dateconsu));
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
					
					<input size="25px" type="hidden" id="jourC" name="jourC" value="<?php echo $joursConsu=date('d', strtotime($dateconsu));?>"/>
			
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
			$TotalGnlPriceCCO=0;
			$TotalGnlPatientPrice=0;
			$TotalGnlPatientBalance=0;
			$TotalGnlInsurancePrice=0;
			
			$x=0;
			$y=0;
			$z=0;
			
			if($comptConsult != 0)
			{
			?>
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th>Type of Consultation</th>
						<th style="text-align:left;width:10%;">Balance</th>
						<th style="text-align:left;width:10%;">Pourcentage</th>
						<th style="text-align:left;width:15%;">Nouveau prix</th>
						<th style="text-align:left;width:18%;">Nouveau pourcentage</th>
						<th style="text-align:left;width:2%;">Action</th>
						<th style="text-align:left;width:10%;">Discount Percent</th>
					</tr>
				</thead>

				<tbody>
			<?php
			$TotalpatientPrice=0;
			$TotaluapPrice=0;
			
			
					$i=1;
					
					while($ligneConsult=$resultatConsult->fetch())
					{
						$typeconsult="";
						
						$billpercent=$ligneConsult->insupercent;
						$discountpercentConsu=$ligneConsult->discountpercentConsu;
						
						$idassu=$ligneConsult->id_assuConsu;
						$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
						$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
						
						$assuCount = $comptAssuConsu->rowCount();
						
						for($a=1;$a<=$assuCount;$a++)
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

                            <input type="hidden" name="idassuTypeconsult" style="width:20px;text-align:center" id="idassuTypeconsult" value="<?php echo $idassu;?>"/>
					<?php
					
					
					$resultatsPrestaConsult=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=1 ORDER BY p.id_prestation');
					
					$resultatsPrestaConsult->setFetchMode(PDO::FETCH_OBJ);
					$comptPrestaConsult=$resultatsPrestaConsult->rowCount();
					
						if($comptPrestaConsult!=0)
						{
						?>
							
							<select name="idprestaConsu" id="idprestaConsu" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:300px;margin-top:5px">
							<?php

							while($lignePrestaConsult=$resultatsPrestaConsult->fetch())
							{
							?>
							<option value="<?php echo $lignePrestaConsult->id_prestation;?>" <?php if($ligneConsult->id_typeconsult == $lignePrestaConsult->id_prestation){ echo "selected='selected'";}?>>
								<?php
								$nameprestaConsult="";
								
								if($lignePrestaConsult->namepresta!='')
								{
									$nameprestaConsult=$lignePrestaConsult->namepresta;
								}else{
								
									if($lignePrestaConsult->nompresta!='')
									{
										$nameprestaConsult=$lignePrestaConsult->nompresta;
									}
								}
								echo $nameprestaConsult;
								?>
							</option>
							<?php
							}
							?>
							</select>

							<input type="hidden" name="autretypeconsult" style="text-align:center" id="autretypeconsult" value=""/>

							<br/>
							
							Consulted By:
							<?php

								$result=$connexion->query('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u');
								$result->setFetchMode(PDO::FETCH_OBJ);
								
							?>
							
								<select name="medecin" id="medecin" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:300px;margin-top:5px">
								<?php

								while($ligneMedecin=$result->fetch())
								{
								?>
								<option value="<?php echo $ligneMedecin->id_u;?>" <?php if($ligneConsult->id_uM == $ligneMedecin->id_u){ echo "selected='selected'";}?>>
									<?php
										echo $ligneMedecin->full_name;
									?>
								</option>
								<?php
								}
								?>
								</select>


						</td>
						<?php
							$prixpresta=$ligneConsult->prixtypeconsult;

						$TotalConsult=$TotalConsult + $prixpresta;

		$typeconsult .= '<td style="text-align:left;font-weight:bold">';

		

							$patientPrice=($prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;



			$typeconsult .= $prixpresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="text-align:left;">'.$billpercent.'<span style="width:30%; font-weight:normal;margin-top:5px;"/><span style="font-size:70%;font-weight:normal;">%</span>
						
						</td>
						
						
						<td style="text-align:left;">
							<input type="text" name="prixtypeconsult" id="prixtypeconsult" class="prixtypeconsult" value="'.$prixpresta.'" style="width:60%; font-weight:normal;margin:0 0 5px 0;"/>
							
						</td>';
						
							$uapPrice= $prixpresta - $patientPrice;
							$TotaluapPrice = $TotaluapPrice + $uapPrice;
							
						}else{
						
							$nameprestaConsult=$ligneConsult->autretypeconsult;
							
		$typeconsult .= '<input type="hidden" name="idprestaConsu" style="text-align:center" id="idprestaConsu" value=""/>
		
						<input type="text" name="autretypeconsult" style="text-align:center" id="autretypeconsult" value="'.$ligneConsult->autretypeconsult.'" required/>
						</td>';
		
							$prixpresta = $ligneConsult->prixautretypeconsult;

			
		$TotalConsult=$TotalConsult + $ligneConsult->prixautretypeconsult;

		$typeconsult .= '<td style="text-align:left;font-weight:bold>';

			$patientPrice=($prixpresta * $billpercent)/100;
			$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
			
			
		$typeconsult .= $prixpresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>
					
					<td style="text-align:left;">'.$billpercent.'<span style="width:30%; font-weight:normal;margin-top:5px;"/><span style="font-size:70%;font-weight:normal;">%</span>
					
					</td>
					
					
					<td style="text-align:left;">
						<input type="text" name="prixtypeconsult" id="prixtypeconsult" class="prixtypeconsult" value="'.$prixpresta.'" style="width:30%; font-weight:normal;margin-top:5px;"/><span style="font-size:70%;font-weight:normal;">Rwf</span>
						
					</td>';
			
		
						$uapPrice= $prixpresta - $patientPrice;
						$TotaluapPrice= $TotaluapPrice + $uapPrice;
			

						}
						
						
		$typeconsult .= '
		
						<td style="text-align:left;">
							<input type="text" name="percentTypeConsu" id="percentTypeConsu" class="percentTypeConsu" value="'.$billpercent.'" style="width:30%; font-weight:normal;margin-top:5px;"/><span style="font-size:70%;font-weight:normal;">%</span>
							
							<input type="hidden" name="idConsu" class="idConsu"  id="idConsu"style="width:50px; text-align:center" value="'.$ligneConsult->id_consu.'"/>
							
						</td>
						
						<td>';
				
			if($comptMedSurge==0 AND $comptMedInf==0 AND $comptMedLabo==0 AND $comptMedRadio==0 AND $comptMedKine==0 AND $comptMedOrtho==0 AND $comptMedConsom==0 AND $comptMedMedoc==0 AND $comptMedConsult==0)
			{
				$typeconsult .= '
							<a href="formModifierBill.php?deleteConsu='.$ligneConsult->id_consu.'&manager='.$_GET['manager'].'&num='.$_GET['num'].'&idbill='.$_GET['idbill'].'&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>';
			}
				
				$typeconsult .= '
						</td>';

						$typeconsult .= '
						<td>
							<input type="text" name="discountpercentConsu" id="discountpercentConsu" class="discountpercentConsu"  style="width:50%; font-weight:normal;margin-top:5px;"/><span style="font-size:70%;font-weight:normal;">%</span>
							</td></tr>';

					
					}
					
				$TotalGnlPrice=$TotalGnlPrice + $TotalConsult;

				$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
				
				$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
				
		 
			$typeconsult .= '</tbody>
			</table>';

				echo $typeconsult;
			
			}
			
			
			if($comptMedSurge != 0)
			{
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th>Surgery</th>
						<th style="text-align:left;width:10%;">Balance</th>
						<th style="text-align:left;width:10%;">Percent</th>
						<th style="text-align:left;width:15%;">Nouveau prix</th>
						<th style="text-align:left;width:18%;">Nouveau pourcentage</th>
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

					$discountpercentSurge=$ligneMedSurge->discountpercentSurge;
					
					$idassu=$ligneMedSurge->id_assuSurge;
					
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($a=1;$a<=$assuCount;$a++)
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
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedSurge=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedSurge->prixprestationSurge;

							echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

							echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';
							
							$TotalMedSurge = $TotalMedSurge + $prixPresta;
						?>
						
							<?php
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							?>

						<td style="text-align:left;">
							<input type="text" name="prixprestaSurge[]" id="prixprestaSurge<?php echo $i;?>" class="prixprestaSurge" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

						</td>

						<?php
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
					
						}
						
						if($ligneMedSurge->id_prestationSurge==NULL AND $ligneMedSurge->autrePrestaS!="")
						{
							echo '<input type="hidden" name="idprestaSurge[]" style="width:100px;display:none;" id="idprestaSurge'.$i.'" value="0"/>';
							
							echo '<input type="text" name="autreSurge[]" style="width:100px;display:none; text-align:center" id="autreSurge'.$i.'" value="'.$ligneMedSurge->autrePrestaS.'"/>';
							
							$nameprestaMedSurge=$ligneMedSurge->autrePrestaS;
							echo $ligneMedSurge->autrePrestaS.'</td>';
							
							
							$prixPresta = $ligneMedSurge->prixautrePrestaS;

							echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';
							
							$TotalMedSurge = $TotalMedSurge + $prixPresta;
			?>
			
							<?php
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							?>
						

						<td style="text-align:left;">
							<input type="text" name="prixprestaSurge[]" id="prixprestaSurge<?php echo $i;?>" class="prixprestaSurge" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

						</td>

						<?php
							$uapPrice= $prixPresta - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;

						}
						?>
						
						<td style="text-align:left;">
							<input type="hidden" name="idmedSurge[]" id="idmedSurge<?php echo $i;?>" class="idmedSurge" value="<?php echo $ligneMedSurge->id_medsurge;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
					
							<input type="text" name="percentSurge[]" id="percentSurge<?php echo $i;?>" class="percentSurge" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
						</td>
						
						<td>
							<a href="formModifierBill.php?deleteMedSurge=<?php echo $ligneMedSurge->id_medsurge;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							
						</td>
					</tr>
				<?php
					$i++;
				}
				?>
					<tr style="text-align:center;">
						
						<td></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedSurge;
						?>
						

						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedSurge.'';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<label style="text-align: center;font-weight: bold;font-size: 10px;margin-left: 20px;color: ">Discount Percent</label> <input type="text" name="discountpercentSurge" value="<?php echo $discountpercentSurge; ?>" style="width:80%;">
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

			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th>Nursing Care</th>
						<th style="text-align:left;width:10%;">Balance</th>
						<th style="text-align:left;width:10%;">Percent</th>
						<th style="text-align:left;width:15%;">Nouveau prix</th>
						<th style="text-align:left;width:18%;">Nouveau pourcentage</th>
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

					$discountpercentInf=$ligneMedInf->discountpercentInf;

					$idassu=$ligneMedInf->id_assuInf;

					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

					$assuCount = $comptAssuConsu->rowCount();

					for($a=1;$a<=$assuCount;$a++)
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
								echo $lignePresta->namepresta.'</td>';

							}else{

								$nameprestaMedInf=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}

							$prixPresta = $ligneMedInf->prixprestation;
							echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

							echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';

							$TotalMedInf = $TotalMedInf + $prixPresta;
						?>

							<?php
							$patientPrice=($prixPresta * $billpercent)/100;

							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;

							?>

						<td style="text-align:left;">
							<input type="text" name="prixprestaInf[]" id="prixprestaInf<?php echo $i;?>" class="prixprestaInf" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

						</td>

						<?php
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;

						}

						if($ligneMedInf->id_prestation==NULL AND $ligneMedInf->autrePrestaM!="")
						{
							echo '<input type="hidden" name="idprestaInf[]" style="width:100px;display:none;" id="idprestaInf'.$i.'" value="0"/>';

							echo '<input type="text" name="autreInf[]" style="width:100px;display:none; text-align:center" id="autreInf'.$i.'" value="'.$ligneMedInf->autrePrestaM.'"/>';

							$nameprestaMedInf=$ligneMedInf->autrePrestaM;
							echo $ligneMedInf->autrePrestaM.'</td>';


							$prixPresta = $ligneMedInf->prixautrePrestaM;

							echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

							echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';

							$TotalMedInf = $TotalMedInf + $prixPresta;
			?>

							<?php
							$patientPrice=($prixPresta * $billpercent)/100;

							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;

							?>


						<td style="text-align:left;">
							<input type="text" name="prixprestaInf[]" id="prixprestaInf<?php echo $i;?>" class="prixprestaInf" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

						</td>

						<?php
							$uapPrice= $prixPresta - $patientPrice;

							$TotaluapPrice= $TotaluapPrice + $uapPrice;

						}
						?>

						<td style="text-align:left;">
							<input type="hidden" name="idmedInf[]" id="idmedInf<?php echo $i;?>" class="idmedInf" value="<?php echo $ligneMedInf->id_medinf;?>" style="width:30%; font-weight:normal;margin-top:5px"/>

							<input type="text" name="percentInf[]" id="percentInf<?php echo $i;?>" class="percentInf" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
						</td>

						<td>
							<a href="formModifierBill.php?deleteMedInf=<?php echo $ligneMedInf->id_medinf;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

						</td>
					</tr>
				<?php
					$i++;
				}
				?>
					<tr style="text-align:center;">

						<td></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
						?>

						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedInf.'';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<label style="text-align: center;font-weight: bold;font-size: 10px;margin-left: 20px;color: ">Discount Percent</label> <input type="text" name="discountpercentInf" value="<?php echo $discountpercentInf; ?>" style="width:80%;">
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
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead>
					<tr>
						<th>Labs</th>
						<th style="text-align:left;width:10%;">Balance</th>
						<th style="text-align:left;width:10%;">Pourcentage</th>
						<th style="text-align:left;width:15%;">Nouveau prix</th>
						<th style="text-align:left;width:18%;">Nouveau pourcentage</th>
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
					$discountpercentLab=$ligneMedLabo->discountpercentLab;
					
					$idassu=$ligneMedLabo->id_assuLab;
					
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($a=1;$a<=$assuCount;$a++)
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
					<tr style="text-align:center;<?php if($ligneMedLabo->examenfait==1){ echo 'background:rgba(0,100,255,0.5);';}?>">
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
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedLabo=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedLabo->prixprestationExa;


							echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

							echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';
							
							$TotalMedLabo = $TotalMedLabo + $prixPresta;
							?>
						
								<?php
								$patientPrice=($prixPresta * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								?>

						<td style="text-align:left;">
							<input type="text" name="prixprestaLab[]" id="prixprestaLab<?php echo $i;?>" class="prixprestaLab" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

						</td>

						<?php
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						
						}
						
						if($ligneMedLabo->id_prestationExa==NULL AND $ligneMedLabo->autreExamen!="")
						{
							echo $ligneMedLabo->autreExamen.'
							<input type="text" name="autreLab[]" style="width:100px;display:none; text-align:center" id="autreLab'.$i.'" value="'.$ligneMedLabo->autreExamen.'"/>
							
							<input type="hidden" name="idprestaLab[]" style="width:100px;display:none; text-align:center" id="idprestaLab'.$i.'" value="0"/>
							</td>';
						
							$nameprestaMedLabo=$ligneMedLabo->autreExamen;
							
							$prixPresta = $ligneMedLabo->prixautreExamen;

							echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

							echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';
							
							$TotalMedLabo = $TotalMedLabo + $prixPresta;
						?>
						
							<?php
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							?>
						<td style="text-align:left;">
							<input type="text" name="prixprestaLab[]" id="prixprestaLab<?php echo $i;?>" class="prixprestaLab" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

						</td>

						<?php
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						
						}
						?>
						
						<td style="text-align:left;">
							<input type="hidden" name="idmedLab[]" id="idmedLab<?php echo $i;?>" class="idmedLab" value="<?php echo $ligneMedLabo->id_medlabo;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						
							<input type="text" name="percentLab[]" id="percentLab<?php echo $i;?>" class="percentLab" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
							
						</td>
						
						<td>
							<a href="formModifierBill.php?deleteMedLabo=<?php echo $ligneMedLabo->id_medlabo;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							
						</td>
					</tr>
				<?php
					$i++;
				}
				?>
					<tr style="text-align:center;">
						
						<td></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
						?>
						

						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedLabo.'';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<label style="text-align: center;font-weight: bold;font-size: 10px;margin-left: 20px;color: ">Discount Percent</label> <input type="text" name="discountpercentLab" value="<?php echo $discountpercentLab; ?>" style="width:80%;">
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
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead>
					<tr>
						<th>Radiologie</th>
						<th style="text-align:left;width:10%;">Balance</th>
						<th style="text-align:left;width:10%;">Pourcentage</th>
						<th style="text-align:left;width:15%;">Nouveau prix</th>
						<th style="text-align:left;width:18%;">Nouveau pourcentage</th>
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

					$discountpercentRadio=$ligneMedRadio->discountpercentRadio;
					
					$idassu=$ligneMedRadio->id_assuRad;
					
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($a=1;$a<=$assuCount;$a++)
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
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedRadio=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedRadio->prixprestationRadio;

						

							echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

							echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';
							
							$TotalMedRadio = $TotalMedRadio + $prixPresta;
							?>
						</td>

								<?php
								$patientPrice=($prixPresta * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								?>

						<td style="text-align:left;">
							<input type="text" name="prixprestaRad[]" id="prixprestaRad<?php echo $i;?>" class="prixprestaRad" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

						</td>

						<?php
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
						}
						
						if($ligneMedRadio->id_prestationRadio==NULL AND $ligneMedRadio->autreRadio!="")
						{
							echo $ligneMedRadio->autreRadio.'<input type="text" name="autreRad[]" style="width:100px;display:none; text-align:center" id="autreRad'.$i.'" value="'.$ligneMedRadio->autreRadio.'"/>
							<input type="text" name="idprestaRad[]" style="width:100px;display:none; text-align:center" id="idprestaRad'.$i.'" value="0"/>
							</td>';
							
							$nameprestaMedRadio=$ligneMedRadio->autreRadio;
							
							$prixPresta = $ligneMedRadio->prixautreRadio;

							

							echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

							echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';
							
							$TotalMedRadio = $TotalMedRadio + $prixPresta;
						?>
						
							<?php
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							?>
							

						<td style="text-align:left;">
							<input type="text" name="prixprestaRad[]" id="prixprestaRad<?php echo $i;?>" class="prixprestaRad" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

						</td>

						<?php
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
						}
						?>
						
						<td style="text-align:left;">
							<input type="hidden" name="idmedRad[]" id="idmedRad<?php echo $i;?>" class="idmedRad" value="<?php echo $ligneMedRadio->id_medradio;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
						
							<input type="text" name="percentRad[]" id="percentRad<?php echo $i;?>" class="percentRad" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
							
						</td>
						
						<td>
							<a href="formModifierBill.php?deleteMedRadio=<?php echo $ligneMedRadio->id_medradio;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							
						</td>
					</tr>
				<?php
					$i++;
				}
				?>
					<tr style="text-align:center;">
						<td></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
						?>

						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedRadio.'';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<label style="text-align: center;font-weight: bold;font-size: 10px;margin-left: 20px;color: ">Discount Percent</label> <input type="text" name="discountpercentRadio" value="<?php echo $discountpercentRadio; ?>" style="width:80%;">
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

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th>Physiotherapy</th>
                        <th style="text-align:left;width:10%;">Balance</th>
                        <th style="text-align:left;width:10%;">Percent</th>
                        <th style="text-align:left;width:15%;">Nouveau prix</th>
                        <th style="text-align:left;width:18%;">Nouveau pourcentage</th>
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

                        $discountpercentkine=$ligneMedKine->discountpercentkine;

                        $idassu=$ligneMedKine->id_assuKine;

                        $comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

                        $comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

                        $assuCount = $comptAssuConsu->rowCount();

                        for($a=1;$a<=$assuCount;$a++)
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
                                    echo $lignePresta->namepresta.'</td>';

                                }else{

                                    $nameprestaMedKine=$lignePresta->nompresta;
                                    echo $lignePresta->nompresta.'</td>';
                                }

                                $prixPresta = $ligneMedKine->prixprestationKine;

                                echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                                echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';

                                $TotalMedKine = $TotalMedKine + $prixPresta;
                                ?>

                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;

                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                ?>

                            <td style="text-align:left;">
                                <input type="text" name="prixprestaKine[]" id="prixprestaKine<?php echo $i;?>" class="prixprestaKine" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

                            </td>

                            <?php
                            $uapPrice= $prixPresta - $patientPrice;
                            $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            }

                            if($ligneMedKine->id_prestationKine==NULL AND $ligneMedKine->autrePrestaK!="")
                            {
                                echo '<input type="hidden" name="idprestaKine[]" style="width:100px;display:none;" id="idprestaKine'.$i.'" value="0"/>';

                                echo '<input type="text" name="autreKine[]" style="width:100px;display:none; text-align:center" id="autreKine'.$i.'" value="'.$ligneMedKine->autrePrestaK.'"/>';

                                $nameprestaMedKine=$ligneMedKine->autrePrestaK;
                                echo $ligneMedKine->autrePrestaK.'</td>';


                                $prixPresta = $ligneMedKine->prixautrePrestaK;

                                echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                                echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';

                                $TotalMedKine = $TotalMedKine + $prixPresta;
                                ?>

                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;

                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                ?>

                                <td style="text-align:left;">
                                    <input type="text" name="prixprestaKine[]" id="prixprestaKine<?php echo $i;?>" class="prixprestaKine" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

                                </td>

                                <?php
                                $uapPrice= $prixPresta - $patientPrice;

                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            }
                            ?>

                            <td style="text-align:left;">
                                <input type="hidden" name="idmedKine[]" id="idmedKine<?php echo $i;?>" class="idmedKine" value="<?php echo $ligneMedKine->id_medkine;?>" style="width:30%; font-weight:normal;margin-top:5px"/>

                                <input type="text" name="percentKine[]" id="percentKine<?php echo $i;?>" class="percentKine" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
                            </td>

                            <td>
                                <a href="formModifierBill.php?deleteMedKine=<?php echo $ligneMedKine->id_medkine;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                    <tr style="text-align:center;">

                        <td></td>
                        <?php
                        $TotalGnlPrice=$TotalGnlPrice + $TotalMedKine;
                        ?>

                        <td style="text-align:left; font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedKine.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="text-align:left; font-size: 13px; font-weight: bold;">
							<label style="text-align: center;font-weight: bold;font-size: 10px;margin-left: 20px;color: ">Discount Percent</label> <input type="text" name="discountpercentkine" value="<?php echo $discountpercentkine; ?>" style="width:80%;">
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

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th>Orthopedy</th>
                        <th style="text-align:left;width:10%;">Balance</th>
                        <th style="text-align:left;width:10%;">Percent</th>
                        <th style="text-align:left;width:15%;">Nouveau prix</th>
                        <th style="text-align:left;width:18%;">Nouveau pourcentage</th>
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

                        $discountpercentOrtho=$ligneMedOrtho->discountpercentOrtho;

                        $idassu=$ligneMedOrtho->id_assuOrtho;

                        $comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

                        $comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

                        $assuCount = $comptAssuConsu->rowCount();

                        for($a=1;$a<=$assuCount;$a++)
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
                                        echo $lignePresta->namepresta.'</td>';

                                    }else{
                                        $nameprestaMedOrtho=$lignePresta->nompresta;
                                        echo $lignePresta->nompresta.'</td>';
                                    }

                                    $prixPresta = $ligneMedOrtho->prixprestationOrtho;


                                    echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                                    echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';

                                    $TotalMedOrtho = $TotalMedOrtho + $prixPresta;
                                    ?>

                                    <?php
                                    $patientPrice=($prixPresta * $billpercent)/100;

                                    $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                    ?>


                                <td style="text-align:left;">
                                    <input type="text" name="prixprestaOrtho[]" id="prixprestaOrtho<?php echo $i;?>" class="prixprestaOrtho" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

                                </td>

                                <?php
                                $uapPrice= $prixPresta - $patientPrice;
                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            }

                            if($ligneMedOrtho->id_prestationOrtho==NULL AND $ligneMedOrtho->autrePrestaO!="")
                            {
                                echo '<input type="hidden" name="idprestaOrtho[]" style="width:100px;display:none;" id="idprestaOrtho'.$i.'" value="0"/>';

                                echo '<input type="text" name="autreOrtho[]" style="width:100px;display:none; text-align:center" id="autreOrtho'.$i.'" value="'.$ligneMedOrtho->autrePrestaO.'"/>';

                                $nameprestaMedOrtho=$ligneMedOrtho->autrePrestaO;
                                echo $ligneMedOrtho->autrePrestaO.'</td>';


                                $prixPresta = $ligneMedOrtho->prixautrePrestaO;

                                echo '<td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                                echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';

                                $TotalMedOrtho = $TotalMedOrtho + $prixPresta;
                                ?>

                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;

                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                ?>


                                <td style="text-align:left;">
                                    <input type="text" name="prixprestaOrtho[]" id="prixprestaOrtho<?php echo $i;?>" class="prixprestaOrtho" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

                                </td>

                                <?php
                                $uapPrice= $prixPresta - $patientPrice;

                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            }
                            ?>

                            <td style="text-align:left;">
                                <input type="hidden" name="idmedOrtho[]" id="idmedOrtho<?php echo $i;?>" class="idmedOrtho" value="<?php echo $ligneMedOrtho->id_medortho;?>" style="width:30%; font-weight:normal;margin-top:5px"/>

                                <input type="text" name="percentOrtho[]" id="percentOrtho<?php echo $i;?>" class="percentOrtho" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>
                            </td>

                            <td>
                                <a href="formModifierBill.php?deleteMedOrtho=<?php echo $ligneMedOrtho->id_medortho;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                    <tr style="text-align:center;">

                        <td></td>
                        <?php
                        $TotalGnlPrice=$TotalGnlPrice + $TotalMedOrtho;
                        ?>


                        <td style="text-align:left; font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedOrtho.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="text-align:left; font-size: 13px; font-weight: bold;">
							<label style="text-align: center;font-weight: bold;font-size: 10px;margin-left: 20px;color: ">Discount Percent</label> <input type="text" name="discountpercentOrtho" value="<?php echo $discountpercentOrtho; ?>" style="width:80%;">
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
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead>
					<tr>
                        <th style="text-align:left;width:15%;">Consommables</th>
                        <th style="text-align:left;width:10%">Qté</th>
                        <th style="text-align:left;width:10%">P/U</th>
                        <th style="text-align:left;width:2%;">Pourcentage</th>
                        <th style="text-align:left;width:10%;">Nouvelle Qté</th>
                        <th style="text-align:left;width:15%">Nouveau P/U</th>
                        <th style="text-align:left;width:10%;">Nouveau Pourcentage</th>
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

					$discountpercentConsom=$ligneMedConsom->discountpercentConsom;
					
					$idassu=$ligneMedConsom->id_assuConsom;
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($c=1;$c<=$assuCount;$c++)
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
					
					if($lignePresta=$resultPresta->fetch())
					{
					?>
						<tr>
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
							<?php
								$qteConsom=$ligneMedConsom->qteConsom;
								$prixPresta = $ligneMedConsom->prixprestationConsom;
							?>
							
							<td style="text-align:left;">
							<?php
								$balance=$prixPresta*$qteConsom;

								echo $qteConsom;
								
								$TotalMedConsom=$TotalMedConsom + $balance;
							?>
							</td>
							

							<td style="text-align:left;">
							<?php
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
								echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
							?>
							</td>
							
								<?php
								$patientPrice=($balance * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								?>
								
							<td style="text-align:left;">
								<input type="text" name="quantityConsom[]" id="quantityConsom<?php echo $i;?>" class="quantityConsom" value="<?php echo $qteConsom;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
							

							<td style="text-align:left;">
								<input type="text" name="prixprestaConsom[]" id="prixprestaConsom<?php echo $i;?>" class="prixprestaConsom" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

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
								<a href="formModifierBill.php?deleteMedConsom=<?php echo $ligneMedConsom->id_medconsom;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
								
							</td>
						</tr>
					<?php
					}
					
					if($ligneMedConsom->id_prestationConsom==0 AND $ligneMedConsom->autreConsom!="")
					{
					?>
						<tr>
							<td style="text-align:left;">

                                <input type="hidden" name="idassuConsom[]" style="width:20px;text-align:center" id="idassuConsom<?php echo $i;?>" value="<?php echo $idassu;?>"/>
							<?php
								
								echo '<input type="text" name="idprestaConsom[]" style="width:100px;display:none; text-align:center" id="idprestaConsom'.$i.'" value="0"/>';

								echo '<input type="text" name="autreConsom[]" style="width:100px;display:none; text-align:center" id="autreConsom'.$i.'" value="'.$ligneMedConsom->autreConsom.'"/>';
			
								$nameprestaMedConsom=$ligneMedConsom->autreConsom;
								echo $nameprestaMedConsom;
							?>
							</td>
						
								<?php
								$qteConsom=$ligneMedConsom->qteConsom;
								$prixPresta = $ligneMedConsom->prixautreConsom;

								?>
						
							<td style="text-align:left;">
							<?php
								$balance=$prixPresta*$qteConsom;

								echo $qteConsom;
								
								$TotalMedConsom=$TotalMedConsom + $balance;
							?>
							</td>
							

							<td style="text-align:left;">
							<?php
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>

							<td style="text-align:left;">
							<?php
								echo $billpercent.'<span style="font-size:70%; font-weight:normal;">%</span>';
							?>
							</td>
							
								<?php
								$patientPrice=($balance * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								?>
								
							<td style="text-align:left;">
								<input type="text" name="quantityConsom[]" id="quantityConsom<?php echo $i;?>" class="quantityConsom" value="<?php echo $qteConsom;?>" style="width:30%; font-weight:normal;margin-top:5px"/>
							</td>
							

							<td style="text-align:left;">
								<input type="text" name="prixprestaConsom[]" id="prixprestaConsom<?php echo $i;?>" class="prixprestaConsom" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

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
								<a href="formModifierBill.php?deleteMedConsom=<?php echo $ligneMedConsom->id_medconsom;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
								
							</td>
						</tr>
				<?php
					}
					$i++;
				}
				?>
					<tr style="text-align:center;">
						
						<td colspan=2></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
						?>
						

						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedConsom.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;

			// float round(float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP)

							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						 <td style="text-align:left; font-size: 13px; font-weight: bold;">
							<label style="text-align: center;font-weight: bold;font-size: 10px;margin-left: 20px;color: ">Discount Percent</label> <input type="text" name="discountpercentConsom" value="<?php echo $discountpercentConsom; ?>" style="width:80%;">
						</td>
						<td>
						<?php
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
						?>
						</td>
						
						<td colspan=5></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			}
			
			if($comptMedMedoc != 0)
			{
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead>
					<tr>
						<th style="text-align:left;width:15%;">Medicaments</th>
						<th style="text-align:left;width:10%">Qté</th>
						<th style="text-align:left;width:10%">P/U</th>
						<th style="text-align:left;width:2%;">Pourcentage</th>
						<th style="text-align:left;width:10%;">Nouvelle Qté</th>
						<th style="text-align:left;width:15%">Nouveau P/U</th>
						<th style="text-align:left;width:10%;">Nouveau Pourcentage</th>
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

					$discountpercentMedoc=$ligneMedMedoc->discountpercentMedoc;
					
					$idassu=$ligneMedMedoc->id_assuMedoc;
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
					$assuCount = $comptAssuConsu->rowCount();
					
					for($a=1;$a<=$assuCount;$a++)
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
					
				
					if($lignePresta=$resultPresta->fetch())
					{
					?>
						<tr style="text-align:center;">
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
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>

							<td style="text-align:left;">
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
								<input type="text" name="prixprestaMedoc[]" id="prixprestaMedoc<?php echo $i;?>" class="prixprestaMedoc" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

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
								<a href="formModifierBill.php?deleteMedMedoc=<?php echo $ligneMedMedoc->id_medmedoc;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
								
							</td>
						</tr>
					
					<?php
					}
					
					if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->autreMedoc!="")
					{
					?>
						<tr style="text-align:center;">
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

							<td style="text-align:left;">
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
								<input type="text" name="prixprestaMedoc[]" id="prixprestaMedoc<?php echo $i;?>" class="prixprestaMedoc" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

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
								<a href="formModifierBill.php?deleteMedMedoc=<?php echo $ligneMedMedoc->id_medmedoc;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
								
							</td>
						</tr>
				<?php
					}
					$i++;
				}
				?>
					<tr style="text-align:center;">
						<td colspan=2></td>
						<?php
							$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
						?>
						

						<td style="text-align:left; font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedMedoc.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						 <td style="text-align:left; font-size: 13px; font-weight: bold;">
							<label style="text-align: center;font-weight: bold;font-size: 10px;margin-left: 20px;color: ">Discount Percent</label> <input type="text" name="discountpercentMedoc" value="<?php echo $discountpercentMedoc; ?>" style="width:80%;">
						</td>
						<td>
						<?php
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
						?>
						</td>
						<td colspan=5></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			}


            if($comptMedConsult != 0)
            {
                ?>

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th>Services</th>
                        <th style="text-align:left;width:10%;">Balance</th>
                        <th style="text-align:left;width:10%;">Pourcentage</th>
                        <th style="text-align:left;width:15%;">Nouveau prix</th>
                        <th style="text-align:left;width:18%;">Nouveau pourcentage</th>
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

                        $discountpercentConsult=$ligneMedConsult->discountpercentConsult;

                        $idassu=$ligneMedConsult->id_assuServ;
                        $comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

                        $comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

                        $assuCount = $comptAssuConsu->rowCount();

                        for($a=1;$a<=$assuCount;$a++)
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
                                    echo $lignePresta->namepresta.'</td>';

                                }else{

                                    $nameprestaMedConsult=$lignePresta->nompresta;
                                    echo $lignePresta->nompresta.'</td>';
                                }

                                $prixPresta = $ligneMedConsult->prixprestationConsu;


                                echo '
                        
                    <td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                                echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';

                                $TotalMedConsult=$TotalMedConsult + $prixPresta;
                                ?>

                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                ?>


                            <td style="text-align:left;">
                                <input type="text" name="prixprestaConsu[]" id="prixprestaConsu<?php echo $i;?>" class="prixprestaConsu" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>

                            </td>

                            <?php
                            $uapPrice= $prixPresta - $patientPrice;
                            $TotaluapPrice= $TotaluapPrice + $uapPrice;
                            }

                            if($ligneMedConsult->id_prestationConsu==NULL AND $ligneMedConsult->autreConsu!="")
                            {

                                echo '<input type="text" name="idpresta[]" style="width:100px;display:none; text-align:center" id="idpresta'.$i.'" value="0"/>';

                                echo '<input type="text" name="autreConsu[]" style="width:100px;display:none; text-align:center" id="autreConsu'.$i.'" value="'.$ligneMedConsult->autreConsu.'"/>';

                                $nameprestaMedConsult=$ligneMedConsult->autreConsu;
                                echo $ligneMedConsult->autreConsu.'</td>';

                                $prixPresta = $ligneMedConsult->prixautreConsu;

                                echo '

                    <td style="text-align:left;">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                                echo '<td style="text-align:left;">'.$billpercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';

                                $TotalMedConsult=$TotalMedConsult + $prixPresta;
                                ?>

                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;

                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                ?>

                                <td style="text-align:left;">
                                    <input type="text" name="prixprestaConsu[]" id="prixprestaConsu<?php echo $i;?>" class="prixprestaConsu" value="<?php echo $prixPresta;?>" style="width:60%; font-weight:normal;margin:0 0 5px 0"/><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <?php
                                $uapPrice= $prixPresta - $patientPrice;

                                $TotaluapPrice= $TotaluapPrice + $uapPrice;
                            }
                            ?>

                            <td style="text-align:left;">
                                <input type="hidden" name="idmedConsu[]" id="idmedConsu<?php echo $i;?>" class="idmedConsu" value="<?php echo $ligneMedConsult->id_medconsu;?>" style="width:30%; font-weight:normal;margin-top:5px"/>

                                <input type="text" name="percentConsu[]" id="percentConsu<?php echo $i;?>" class="percentConsu" value="<?php echo $billpercent;?>" style="width:30%; font-weight:normal;margin-top:5px"/><span style="font-size:70%; font-weight:normal;">%</span>

                            </td>

                            <td>
                                <a href="formModifierBill.php?deleteMedConsu=<?php echo $ligneMedConsult->id_medconsu;?>&manager=<?php echo $_GET['manager'];?>&num=<?php echo $_GET['num'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&finishbtn=ok" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                    <tr style="text-align:center;">

                        <td></td>
                        <?php
                        $TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
                        ?>

                        <td style="text-align:left; font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedConsult.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                         <td style="text-align:left; font-size: 13px; font-weight: bold;">
							<label style="text-align: center;font-weight: bold;font-size: 10px;margin-left: 20px;color: ">Discount Percent</label> <input type="text" name="discountpercentConsult" value="<?php echo $discountpercentConsult; ?>" style="width:80%;">
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
			
			$TotalGnlPatientBalance = $TotalGnlPatientPrice;
			
			$patientPayed = $TotalGnlPatientBalance - $dettes;
			
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
						Payement
					</td>
					<td style="font-size: 110%; font-weight: bold;text-align:center;" class="buttonBill">
						Change Cashier
					</td>
				</tr>

				<tr style="text-align:center;">

					<td style="font-size: 110%; font-weight: bold;" class="buttonBill">
						<select style="margin:auto;width:30%;" name="payement" id="payement" onclick="ShowTxtDette()">

							<option value='<?php echo $TotalGnlPatientPrice;?>'>Total</option>
							<option value='<?php echo '';?>' <?php if($idBillCount!=0) echo 'selected="selected"';?>>Partiel</option>
						</select>


						<input type="text" id="dettes" name="dettes" value="<?php if($idBillCount!=0) { echo $patientPayed;}else{ echo '';} ?>" style="width:auto;margin:auto;<?php if($idBillCount==0){ echo 'display:none;';}?>" placeholder="Montant payé ici.."/>

						<input type="hidden" id="dettesfixe" name="dettesfixe" value="<?php echo $patientPayed;?>" style="width:auto;margin:auto;"/>
						<input type="hidden" id="TotalGnlPatientPrice" name="TotalGnlPatientPrice" value="<?php echo $TotalGnlPatientBalance;?>" style="width:auto;margin:auto;"/>
					</td>
					<td>
						<label style="color: red;">Done By:</label>
						<select name="Change_cashier">
							<option value="">Select Cashier...</option>
							<?php 
								while($FetchCashier = $GetCachier->fetch()){
							?>
								<option value="<?php echo $FetchCashier->codecashier; ?>" <?php if($FetchCashier->codecashier == $cashierNumero){echo "selected='selected'";} ?>><?php echo $FetchCashier->full_name; ?></option>
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

	function ShowTxtDette(dette)
	{
		var payement=document.getElementById('payement').value;
		var dettes=document.getElementById('dettesfixe').value;

		if( payement =='')
		{
			document.getElementById('dettes').style.display='inline';
			document.getElementById('dettes').value=dettes;
		}else{
			document.getElementById('dettes').style.display='none';
			document.getElementById('dettes').value='';
		}

	}

	</script>
	
</html>