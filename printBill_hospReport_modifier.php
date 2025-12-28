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
// echo showON('H');


	$numPa=$_GET['num'];
	$hospId=$_GET['idhosp'];
	
	
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
		$vouchernum = $ligne->vouchernumHosp;
		
		$oldorgBill = $ligne->idorgBillHosp;
		$idassubill=$ligne->id_assuHosp;
		$percentIdbill=$ligne->insupercent_hosp;
		$nomassurance=$ligne->nomassuranceHosp;
		$idcardbill=$ligne->idcardbillHosp;
		$numpolicebill=$ligne->numpolicebillHosp;
		$adherentbill=$ligne->adherentbillHosp;
		
		$datebill = date('d-M-Y', strtotime($ligne->dateSortie));
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
	
</head>



<body>


	<?php
	if(isset($_GET['finishbtn']))
	{
	?>
		<body onload="window.print()">
	<?php
	}
	?>
	
<?php
$connected=$_SESSION['connect'];
$idCoordi=$_SESSION['id'];

if($connected==true AND isset($_SESSION['codeC']))
{
	
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

			$resultatHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:idhosp');
			$resultatHosp->execute(array(
			'idhosp'=>$hospId
			));
			
			$resultatHosp->setFetchMode(PDO::FETCH_OBJ);

			if($ligneHosp=$resultatHosp->fetch())
			{
				$datehosp= date('d-M-Y', strtotime($ligneHosp->dateEntree));
			}

		
		
		$TotalGnl = 0;
		$TotalGnlCCO = 0;
		
		
			/*--------------Billing Info Patient-----------------*/
		
		$resultatsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultatsPatient->execute(array(
		'operation'=>$numPa
		));
		
		$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
		
		if($lignePatient=$resultatsPatient->fetch())
		{
			
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

			

		$userinfo = '<table style="width:100%; margin-top:20px;">
			
			<tr>
				<td style="text-align:left;">
					Full name:
					<span style="font-weight:bold">'.$lignePatient->nom_u.' '.$lignePatient->prenom_u.'</span><br/>
					Gender: <span style="font-weight:bold">'.$sexe.'</span><br/>
					Adress: <span style="font-weight:bold">'.$adresse.'</span>
				</td>
				
				<td style="text-align:center;">
					Organisation: <span style="font-weight:bold">';

            if (isset($_POST['org'])) {
                $org = $_POST['org'];

            } else {
                $org = $oldorgBill;
            }

            $resultOrg = $connexion->prepare('SELECT *FROM organisations o WHERE o.id_org=:orgId');

            $resultOrg->execute(array(
                'orgId' => $org
            ));

            $resultOrg->setFetchMode(PDO::FETCH_OBJ);

            if ($ligneOrg = $resultOrg->fetch()) {
                $idorg = $ligneOrg->id_org;
                $nomorg = $ligneOrg->nomOrg;

                if($ligneOrg->lieuOrg!=NULL)
                {
                    $lieuorg = ' _ '.$ligneOrg->lieuOrg;
                }else{
                    $lieuorg = '';
                }

                $userinfo .= '' . $nomorg . '' . $lieuorg . '</span><br/>';
			}

            $userinfo .= '
            
					Insurance type: <span style="font-weight:bold">';
			
			if(isset($_POST['assurance']))
			{
				$assurance = $_POST['assurance'];
			
			}else{
				$assurance = $_GET['idassu'];
			}
						
			$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
			
			$resultAssurance->execute(array(
			'assuId'=>$assurance
			));
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

			if($ligneAssu=$resultAssurance->fetch())
			{
				$idassu=$ligneAssu->id_assurance;
				$nomassurancebill=$ligneAssu->nomassurance;
				
				
				$userinfo .= ''.$nomassurancebill.'</span><br/>';
				
				if($idassu!=1)
				{
					if(isset($_POST['percentIdbill']))
					{
						$percentIdbill= $_POST['percentIdbill'];
					}else{
						$percentIdbill=$_GET['billpercent'];
					}
				
					if(isset($_POST['idcardbill']))
					{
						$idcardbill=$_POST['idcardbill'];
					}
					
					if(isset($_POST['numpolicebill']))
					{
						$numpolicebill=$_POST['numpolicebill'];
					}
					
					if(isset($_POST['adherentbill']))
					{
						$adherentbill=$_POST['adherentbill'];
					}
				
					if(isset($_POST['vouchernum']))
					{
						$vouchernum=$_POST['vouchernum'];
					}
				
					$userinfo .= 'N° insurance card:
					<span style="font-weight:bold">'.$idcardbill;
					
					if($numpolicebill!="")
					{
						$userinfo .= '</span><br/>
						
						N° police:
						<span style="font-weight:bold">'.$numpolicebill;
					}
					
					$userinfo .= '</span><br/>
					
					Principal member:
					<span style="font-weight:bold">'.$adherentbill;
				
				}else{
					
					$percentIdbill=100;
			
					$idcardbill="";
					$numpolicebill="";
					$adherentbill="";
				}
				
			}

				$userinfo .='</span>
				</td>
				
				<td style="text-align:right;">
					Patient ID: <span style="font-weight:bold">'.$lignePatient->numero.'</span><br/>
					Date of birth: <span style="font-weight:bold">'.date('d-M-Y', strtotime($lignePatient->date_naissance)).'</span><br/>
					Date of Hospitalisation: <span style="font-weight:bold">'.$datehosp.'</span>
					
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
						->setCellValue('B4', ''.$nomassurancebill.' '.$percentIdbill.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.$numbill.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$annee.'');
			
		}
		
		/*-------Requête pour AFFICHER Type Hospitalisation-----------*/
		
	if(isset($_POST['previewbtn']))
	{
		
		$idHosp=$_GET['idhosp'];
		$numroom=$_POST['numroom'];
		$prixprestaHosp=$_POST['prixprestaHosp'];
		
		if($idassu==1)
		{
			$percentHosp=100;
		}else{
			$percentHosp=$_POST['percentHosp'];
		}
		
		
		
		if($_POST['mois']<10)
		{
			$mois='0'.$_POST['mois'];
		}else{
			$mois=$_POST['mois'];
		}
		
		if($_POST['jours']<10)
		{
			$jours='0'.$_POST['jours'];
		}else{
			$jours=$_POST['jours'];
		}
				
				if($_POST['heurein']<10)
				{
					$heurein='0'.$_POST['heurein'];
				}else{
					$heurein=$_POST['heurein'];
				}
				
				if($_POST['minutein']<10)
				{
					$minutein='0'.$_POST['minutein'];
				}else{
					$minutein=$_POST['minutein'];
				}
				
				if($_POST['secondein']<10)
				{
					$secondein='0'.$_POST['secondein'];
				}else{
					$secondein=$_POST['secondein'];
				}
				
		$firstDate=$_POST['annee'].'-'.$mois.'-'.$jours;
		
		$dateEntree = date('Y-m-d', strtotime($firstDate));
		$heureEntree=$heurein.':'.$minutein.':'.$secondein;
		
		
		if($_POST['moisC']<10)
		{
			$moisC='0'.$_POST['moisC'];
		}else{
			$moisC=$_POST['moisC'];
		}
		
		if($_POST['joursC']<10)
		{
			$joursC='0'.$_POST['joursC'];
		}else{
			$joursC=$_POST['joursC'];
		}
				
				if($_POST['heureout']<10)
				{
					$heureout='0'.$_POST['heureout'];
				}else{
					$heureout=$_POST['heureout'];
				}
				
				if($_POST['minuteout']<10)
				{
					$minuteout='0'.$_POST['minuteout'];
				}else{
					$minuteout=$_POST['minuteout'];
				}
				
				if($_POST['secondeout']<10)
				{
					$secondeout='0'.$_POST['secondeout'];
				}else{
					$secondeout=$_POST['secondeout'];
				}
				
		$lastDate=$_POST['anneeC'].'-'.$moisC.'-'.$joursC;
		
		$dateSortie = date('Y-m-d', strtotime($lastDate));
		$heureSortie=$heureout.':'.$minuteout.':'.$secondeout;
		
		
		$updatepercent=$connexion->prepare('UPDATE patients_hosp ph SET ph.numroomPa=:numroomPa,ph.dateEntree=:dateEntree,ph.heureEntree=:heureEntree,ph.dateSortie=:dateSortie,ph.heureSortie=:heureSortie,ph.prixroom=:prixprestaHosp,ph.insupercent_hosp=:percentHosp,ph.id_assuHosp=:idassuHosp,ph.idorgBillHosp=:org, ph.nomassuranceHosp=:nomassuranceHosp,ph.idcardbillHosp=:idcardbillHosp,ph.numpolicebillHosp=:numpolicebillHosp,ph.adherentbillHosp=:adherentbillHosp,ph.codecoordiHosp=:codecoordiHosp,ph.vouchernumHosp=:vouchernum WHERE ph.id_hosp=:idHosp');
		
		$updatepercent->execute(array(
		'numroomPa'=>$numroom,
		'dateEntree'=>$dateEntree,
		'heureEntree'=>$heureEntree,
		'dateSortie'=>$dateSortie,
		'heureSortie'=>$heureSortie,
		'prixprestaHosp'=>$prixprestaHosp,
		'org'=>$org,
		'percentHosp'=>$percentHosp,
		'idassuHosp'=>$idassu,
		'nomassuranceHosp'=>$nomassurancebill,
		'idcardbillHosp'=>$idcardbill,
		'numpolicebillHosp'=>$numpolicebill,
		'adherentbillHosp'=>$adherentbill,
		'codecoordiHosp'=>$codecoordi,
		'vouchernum'=>$vouchernum,
		'idHosp'=>$idHosp
		
		))or die( print_r($connexion->errorInfo()));


		$idBilling = $_GET['numbill'];
		$idhosp = $_GET['idhosp'];

		$updateHospi = $connexion->prepare("UPDATE patients_hosp SET codecashierHosp=:codecashierHosp WHERE id_hosp=:id_hosp");
		$updateHospi->execute(array('codecashierHosp'=>$_POST['Change_cashier'],'id_hosp'=>$idhosp));		

		$updateConsult = $connexion->prepare("UPDATE med_consult_hosp SET codecashier=:codecashier WHERE id_factureMedConsu=:id_factureMedConsu");
		$updateConsult->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedConsu'=>$idBilling));

		$updateConsom = $connexion->prepare("UPDATE med_consom_hosp SET codecashier=:codecashier WHERE id_factureMedConsom=:id_factureMedConsom");
		$updateConsom->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedConsom'=>$idBilling));

		$updateInf = $connexion->prepare("UPDATE med_inf_hosp SET codecashier=:codecashier WHERE id_factureMedInf=:id_factureMedInf");
		$updateInf->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedInf'=>$idBilling));

		$updateLabo = $connexion->prepare("UPDATE med_labo_hosp SET codecashier=:codecashier WHERE id_factureMedLabo=:id_factureMedLabo");
		$updateLabo->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedLabo'=>$idBilling));

		$updateMedoc = $connexion->prepare("UPDATE med_medoc_hosp SET codecashier=:codecashier WHERE id_factureMedMedoc=:id_factureMedMedoc");
		$updateMedoc->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedMedoc'=>$idBilling));
		
		$updatePysco = $connexion->prepare("UPDATE med_kine_hosp SET codecashier=:codecashier WHERE id_factureMedKine=:id_factureMedKine");
		$updatePysco->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedKine'=>$idBilling));
		
		$updateRadio = $connexion->prepare("UPDATE med_radio_hosp SET codecashier=:codecashier WHERE id_factureMedRadio=:id_factureMedRadio");
		$updateRadio->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedRadio'=>$idBilling));		

		$updateOrtho = $connexion->prepare("UPDATE med_ortho_hosp SET codecashier=:codecashier WHERE id_factureMedOrtho =:id_factureMedOrtho");
		$updateOrtho->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedOrtho'=>$idBilling));
		
		$updateSurge = $connexion->prepare("UPDATE med_surge_hosp SET codecashier=:codecashier WHERE id_factureMedSurge=:id_factureMedSurge");
		$updateSurge->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedSurge'=>$idBilling));
	}
	
		$resultHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:hospId AND ph.numero=:num ORDER BY ph.id_hosp');
		$resultHosp->execute(array(
		'hospId'=>$hospId,
		'num'=>$numPa
		));

		$resultHosp->setFetchMode(PDO::FETCH_OBJ);

		$comptHosp=$resultHosp->rowCount();
		
		$TotalHospPayed = 0;
		$TotalHospPayedCCO = 0;
		$TotalHosp = 0;
		$TotalHospCCO = 0;
		
		/*-------Requête pour AFFICHER med_consult_hosp-----------*/
	
		
		if(isset($_POST['idpresta']))
		{
			$idprestamc = array();
			$prixmc = array();
			$add = array();
			$idmc = array();
			$autremc = array();
			$qteConsu = array();
			$anneemc = array();
			$moismc = array();
			$jourmc = array();
			$idassuServ = array();

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
			
			foreach($_POST['quantityConsu'] as $valueConsu)
			{
				$qteConsu[] = $valueConsu;
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
			
			foreach($_POST['idassuServ'] as $valueassumc)
			{
				$idassuServ[] = $valueassumc;
			}
			
			
			for($i=0;$i<sizeof($add);$i++)
			{
				$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuServ->rowCount();
				
				for($a=1;$a<=$assuCount;$a++)
				{
					
					$getAssuServ=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuServ->execute(array(
						'idassu'=>$idassuServ[$i]
					));
					
					$getAssuServ->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomAssuServ=$getAssuServ->fetch())
					{
						$presta_assuServ='prestations_'.$ligneNomAssuServ->nomassurance;
					}
				}
				
				
				if($moismc[$i]<10)
				{
					$moismc[$i]='0'.$moismc[$i];
				}
				
				if($jourmc[$i]<10)
				{
					$jourmc[$i]='0'.$jourmc[$i];
				}
				
				$datehospMedConsu=$anneemc[$i].'-'.$moismc[$i].'-'.$jourmc[$i];
				
				$result=$connexion->query('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation='.$idprestamc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
			
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_consult_hosp mc SET mc.datehosp=\''.$datehospMedConsu.'\',mc.insupercentServ='.$add[$i].',mc.prixprestationConsu='.$prixmc[$i].',mc.qteConsu='.$qteConsu[$i].',mc.codecoordi=\''.$codecoordi.'\' WHERE mc.id_medconsu='.$idmc[$i].'');
					
					/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedConsu,
						'add'=>$add[$i],
						'prixmc'=>$prixmc[$i],
						'prixmcCCO'=>$prixmcCCO[$i],
						'qteConsu'=>$qteConsu[$i],
						'codecoordi'=>$codecoordi,
						'idmc'=>$idmc[$i]
						
					))or die( print_r($connexion->errorInfo()));*/
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_consult_hosp mc WHERE mc.id_medconsu='.$idmc[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					$comptPrestas=$results->rowCount();
					
		
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consult_hosp mc SET mc.datehosp=\''.$datehospMedConsu.'\',mc.insupercentServ='.$add[$i].',mc.prixprestationConsu=0,mc.prixautreConsu='.$prixmc[$i].',mc.prixautreConsuCCO='.$prixmcCCO[$i].',mc.qteConsu='.$qteConsu[$i].',mc.codecoordi=\''.$codecoordi.'\' WHERE mc.id_medconsu='.$idmc[$i].'');
						
						/*$updatepercent->execute(array(
							'datehosp'=>$datehospMedConsu,
							'add'=>$add[$i],
							'prixmc'=>$prixmc[$i],
							'prixmcCCO'=>$prixmcCCO[$i],
							'qteConsu'=>$qteConsu[$i],
							'codecoordi'=>$codecoordi,
							'idmc'=>$idmc[$i]
							
						))or die( print_r($connexion->errorInfo()));*/
						
					}
				}

			}
		}
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc, patients_hosp p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_hospMed=:idhosp AND p.id_hosp=mc.id_hospMed AND mc.datehosp!="0000-00-00" AND mc.id_factureMedConsu!="" ORDER BY mc.datehosp');
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
		$TotalMedConsultCCO = 0;
		$TotalMedConsultPayed = 0;
		$TotalMedConsultPayedCCO = 0;

		
		
		/*-------Requête pour AFFICHER med_surge_hosp-----------*/
		
		
		if(isset($_POST['idprestaSurge']))
		{
			$idprestaSurge = array();
			$prixms = array();
			$addSurge = array();
			$idms = array();
			$autresurge = array();
			$qteSurge = array();
			$anneeeSurge = array();
			$moisSurge = array();
			$jourSurge = array();
			$idassuSurge = array();
			
			
			foreach($_POST['idprestaSurge'] as $surge)
			{
				$idprestasurge[] = $surge;
			}
			
			foreach($_POST['prixprestaSurge'] as $valSurge)
			{
				$prixms[] = $valSurge;
			}
			
			foreach($_POST['percentSurge'] as $valeurSurge)
			{
				$addSurge[] = $valeurSurge;
			}
			
			foreach($_POST['idmedSurge'] as $valeurms)
			{
				$idms[] = $valeurms;
			}
			
			foreach($_POST['autreSurge'] as $autrevaluesurge)
			{
				$autresurge[] = $autrevaluesurge;
			}
			
			foreach($_POST['quantitySurge'] as $valueSurge)
			{
				$qteSurge[] = $valueSurge;
			}
			
			foreach($_POST['anneeMedSurge'] as $anSurge)
			{
				$anneeSurge[] = $anSurge;
			}
			
			foreach($_POST['moisMedSurge'] as $moiSurge)
			{
				$moisSurge[] = $moiSurge;
			}
			
			foreach($_POST['joursMedSurge'] as $jrSurge)
			{
				$jourSurge[] = $jrSurge;
			}
			
			foreach($_POST['idassuSurge'] as $valueassusurge)
			{
				$idassuSurge[] = $valueassusurge;
			}
			
			
			for($i=0;$i<sizeof($addSurge);$i++)
			{
				$comptAssuSurge=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuSurge->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuSurge->rowCount();
				
				for($a=1;$a<=$assuCount;$a++)
				{
					
					$getAssuSurge=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuSurge->execute(array(
						'idassu'=>$idassuSurge[$i]
					));
					
					$getAssuSurge->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomAssuSurge=$getAssuSurge->fetch())
					{
						$presta_assuSurge='prestations_'.$ligneNomAssuSurge->nomassurance;
					}
				}
				
				// echo $addSurge[$i].'_'.$idms[$i].'_('.$prixms[$i].' : '.$qteSurge[$i].')<br/>';
				
				if($moisSurge[$i]<10)
				{
					$moisSurge[$i]='0'.$moisSurge[$i];
				}
				
				if($jourSurge[$i]<10)
				{
					$jourSurge[$i]='0'.$jourSurge[$i];
				}
				
				$datehospMedSurge=$anneeSurge[$i].'-'.$moisSurge[$i].'-'.$jourSurge[$i];
				
				
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuSurge.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=4 AND p.id_prestation='.$idprestasurge[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_surge_hosp ms SET ms.datehosp=\''.$datehospMedSurge.'\',ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge='.$prixms[$i].',ms.prixautrePrestaS=0,ms.qteSurge='.$qteSurge[$i].',ms.codecoordi=\''.$codecoordi.'\' WHERE ms.id_medsurge='.$idms[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedSurge,
						'addSurge'=>$addSurge[$i],
						'prixms'=>$prixms[$i],
						'prixmsCCO'=>$prixmsCCO[$i],
						'qteSurge'=>$qteSurge[$i],
						'codecoordi'=>$codecoordi,
						'idms'=>$idms[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_surge_hosp ms WHERE ms.id_medsurge='.$idms[$i].'');
					
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_surge_hosp ms SET ms.datehosp=\''.$datehospMedSurge.'\',ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge=0,ms.prixautrePrestaS='.$prixms[$i].',ms.qteSurge='.$qteSurge[$i].',ms.codecoordi=\''.$codecoordi.'\' WHERE ms.id_medsurge='.$idms[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedSurge,
						'addSurge'=>$addSurge[$i],
						'prixms'=>$prixms[$i],
						'prixmsCCO'=>$prixmsCCO[$i],
						'qteSurge'=>$qteSurge[$i],
						'codecoordi'=>$codecoordi,
						'idms'=>$idms[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
				}
				
			}
		}
		
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms, patients_hosp p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_hospSurge=:idhosp AND p.id_hosp=ms.id_hospSurge AND ms.id_factureMedSurge!="" ORDER BY ms.datehosp');
		$resultMedSurge->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedSurge=$resultMedSurge->rowCount();
		
		$TotalMedSurge = 0;
		$TotalMedSurgePayed = 0;
		$TotalMedSurgePayedCCO = 0;




		/*-------Requête pour AFFICHER med_inf_hosp-----------*/
	
		
		if(isset($_POST['idprestaInf']))
		{
			$idprestami = array();
			$prixmi = array();
			$addInf = array();
			$idmi = array();
			$autremi = array();
			$qteInf = array();
			$anneemi = array();
			$moismi = array();
			$jourmi = array();
			$idassuInf = array();

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
			
			foreach($_POST['quantityInf'] as $valueInf)
			{
				$qteInf[] = $valueInf;
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
			
			foreach($_POST['idassuInf'] as $valueassumi)
			{
				$idassuInf[] = $valueassumi;
			}

			
			for($i=0;$i<sizeof($addInf);$i++)
			{
				$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuInf->rowCount();
				
				for($a=1;$a<=$assuCount;$a++)
				{
					$getAssuInf=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuInf->execute(array(
						'idassu'=>$idassuInf[$i]
					));
					
					$getAssuInf->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomAssuInf=$getAssuInf->fetch())
					{
						$presta_assuInf='prestations_'.$ligneNomAssuInf->nomassurance;
					}
				}
				
				// echo $addInf[$i].'_'.$idmi[$i].'_('.$prixmi[$i].')<br/>';
				
				if($moismi[$i]<10)
				{
					$moismi[$i]='0'.$moismi[$i];
				}
				
				if($jourmi[$i]<10)
				{
					$jourmi[$i]='0'.$jourmi[$i];
				}
				
				$datehospMedInf=$anneemi[$i].'-'.$moismi[$i].'-'.$jourmi[$i];
				
				
				$result=$connexion->query('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation='.$idprestami[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_inf_hosp mi SET mi.datehosp=\''.$datehospMedInf.'\',mi.datesoins=\''.$datehospMedInf.'\',mi.insupercentInf='.$addInf[$i].',mi.prixprestation='.$prixmi[$i].',mi.prixautrePrestaM=0,mi.qteInf='.$qteInf[$i].',mi.codecoordi=\''.$codecoordi.'\' WHERE mi.id_medinf='.$idmi[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedInf,
						'datesoins'=>$datehospMedInf,
						'assurance'=>$assurance,
						'addInf'=>$addInf[$i],
						'prixmi'=>$prixmi[$i],
						'qteInf'=>$qteInf[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmi'=>$idmi[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_inf_hosp mi WHERE mi.id_medinf='.$idmi[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_inf_hosp mi SET mi.datehosp=\''.$datehospMedInf.'\',mi.datesoins=\''.$datehospMedInf.'\',mi.insupercentInf='.$addInf[$i].',mi.prixprestation=0,mi.prixautrePrestaM='.$prixmi[$i].',mi.qteInf='.$qteInf[$i].',mi.codecoordi=\''.$codecoordi.'\' WHERE mi.id_medinf='.$idmi[$i].'');

						/*$updatepercent->execute(array(
						'datehosp'=>$dateEntree,
						'datesoins'=>$datehospMedInf,
						'assurance'=>$assurance,
						'addInf'=>$addInf[$i],
						'prixmi'=>$prixmi[$i],
						'qteInf'=>$qteInf[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmi'=>$idmi[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
				}
				
			}
		}
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, patients_hosp p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_hospInf=:idhosp AND p.id_hosp=mi.id_hospInf AND mi.id_factureMedInf!="" ORDER BY mi.datehosp');
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();

		$TotalMedInf = 0;
		$TotalMedInfPayed = 0;
		$TotalMedInfPayedCCO = 0;
		
		


		/*-------Requête pour AFFICHER med_labo_hosp-----------*/
	
		
		if(isset($_POST['idprestaLab']))
		{
			$idprestaml = array();
			$prixml = array();
			$addLab = array();
			$idml = array();
			$autreml = array();
			$qteLab = array();
			$anneeml = array();
			$moisml = array();
			$jourml = array();
			$idassuLab = array();


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
			
			foreach($_POST['quantityLab'] as $valueLab)
			{
				$qteLab[] = $valueLab;
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
			
			foreach($_POST['idassuLab'] as $valueassuml)
			{
				$idassuLab[] = $valueassuml;
			}

			
			for($i=0;$i<sizeof($addLab);$i++)
			{
				$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuLab->rowCount();
				
				for($a=1;$a<=$assuCount;$a++)
				{
					
					$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuLab->execute(array(
						'idassu'=>$idassuLab[$i]
					));
					
					$getAssuLab->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomAssuLab=$getAssuLab->fetch())
					{
						$presta_assuLab='prestations_'.$ligneNomAssuLab->nomassurance;
					}
				}
				
				// echo $addLab[$i].'_'.$idml[$i].'_('.$prixml[$i].')<br/>';
			
				if($moisml[$i]<10)
				{
					$moisml[$i]='0'.$moisml[$i];
				}
				
				if($jourml[$i]<10)
				{
					$jourml[$i]='0'.$jourml[$i];
				}
				
				$datehospMedLabo=$anneeml[$i].'-'.$moisml[$i].'-'.$jourml[$i];
				
				$result=$connexion->query('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation='.$idprestaml[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_labo_hosp ml SET ml.datehosp=\''.$datehospMedLabo.'\',ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa='.$prixml[$i].',ml.prixautreExamen=0,ml.prixautreExamenCCO=0,ml.qteLab='.$qteLab[$i].',ml.codecoordi=\''.$codecoordi.'\' WHERE ml.id_medlabo='.$idml[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedLabo,
						'addLab'=>$addLab[$i],
						'prixml'=>$prixml[$i],
						'prixmlCCO'=>$prixmlCCO[$i],
						'qteLab'=>$qteLab[$i],
						'codecoordi'=>$codecoordi,
						'idml'=>$idml[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_labo_hosp ml WHERE ml.id_medlabo='.$idml[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_labo_hosp ml SET ml.datehosp=\''.$datehospMedLabo.'\',ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa=0,ml.prixautreExamen='.$prixml[$i].',ml.qteLab='.$qteLab[$i].',ml.codecoordi=\''.$codecoordi.'\' WHERE ml.id_medlabo='.$idml[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedLabo,
						'assurance'=>$assurance,
						'addLab'=>$addLab[$i],
						'prixml'=>$prixml[$i],
						'qteLab'=>$qteLab[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idml'=>$idml[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
				}
			}
		}
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, patients_hosp p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_hospLabo=:idhosp AND p.id_hosp=ml.id_hospLabo AND ml.id_factureMedLabo!="" ORDER BY ml.datehosp');
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();
		
		$TotalMedLabo = 0;
		$TotalMedLaboPayed = 0;
		$TotalMedLaboPayedCCO = 0;
	
	
	
	
		/*-------Requête pour AFFICHER med_radio_hosp-----------*/
	
		
		if(isset($_POST['idprestaRad']))
		{
			$idprestamr = array();
			$prixmr = array();
			$addRad = array();
			$idmr = array();
			$autremr = array();
			$qteRad = array();
			$anneeemr = array();
			$moismr = array();
			$jourmr = array();
			$idassuRad = array();


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
			
			foreach($_POST['quantityRad'] as $valueRad)
			{
				$qteRad[] = $valueRad;
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
			
			foreach($_POST['idassuRad'] as $valueassumr)
			{
				$idassuRad[] = $valueassumr;
			}
			
			
			for($i=0;$i<sizeof($addRad);$i++)
			{
				$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuRad->rowCount();
				
				for($a=1;$a<=$assuCount;$a++)
				{
					
					$getAssuRad=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuRad->execute(array(
						'idassu'=>$idassuRad[$i]
					));
					
					$getAssuRad->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomAssuRad=$getAssuRad->fetch())
					{
						$presta_assuRad='prestations_'.$ligneNomAssuRad->nomassurance;
					}
				}
				
				// echo $addRad[$i].'_'.$idmr[$i].'_('.$prixmr[$i].')<br/>';
				
				if($moismr[$i]<10)
				{
					$moismr[$i]='0'.$moismr[$i];
				}
				
				if($jourmr[$i]<10)
				{
					$jourmr[$i]='0'.$jourmr[$i];
				}
				
				$datehospMedRadio=$anneemr[$i].'-'.$moismr[$i].'-'.$jourmr[$i];
				
				$result=$connexion->query('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation='.$idprestamr[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
				
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_radio_hosp mr SET mr.datehosp=\''.$datehospMedRadio.'\',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio='.$prixmr[$i].',mr.prixautreRadio=0,mr.qteRad='.$qteRad[$i].',mr.codecoordi=\''.$codecoordi.'\' WHERE mr.id_medradio='.$idmr[$i].'');

						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedRadio,
						'assurance'=>$assurance,
						'addRad'=>$addRad[$i],
						'prixmr'=>$prixmr[$i],
						'prixmrCCO'=>$prixmrCCO[$i],
						'qteRad'=>$qteRad[$i],
						'codecoordi'=>$codecoordi,
						'idmr'=>$idmr[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_radio_hosp mr WHERE mr.id_medradio='.$idmr[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_radio_hosp mr SET mr.datehosp=\''.$datehospMedRadio.'\',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio=0,mr.prixautreRadio='.$prixmr[$i].',mr.qteRad='.$qteRad[$i].',mr.codecoordi=\''.$codecoordi.'\' WHERE mr.id_medradio='.$idmr[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedRadio,
						'assurance'=>$assurance,
						'addRad'=>$addRad[$i],
						'prixmr'=>$prixmr[$i],
						'prixmrCCO'=>$prixmrCCO[$i],
						'qteRad'=>$qteRad[$i],
						'codecoordi'=>$codecoordi,
						'idmr'=>$idmr[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
				}
			}
		}
		
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, patients_hosp p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_hospRadio=:idhosp AND p.id_hosp=mr.id_hospRadio AND mr.id_factureMedRadio!="" ORDER BY mr.datehosp');
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
		$TotalMedRadioPayed = 0;
		$TotalMedRadioPayedCCO = 0;

		
		
		/*-------Requête pour AFFICHER med_kine_hosp-----------*/
		
		
		if(isset($_POST['idprestaKine']))
		{
			$idprestakine = array();
			$prixmk = array();
			$addKine = array();
			$idmk = array();
			$autrekine = array();
			$qteKine = array();
			$anneeeKine = array();
			$moisKine = array();
			$jourKine = array();
			$idassuKine = array();
			
			
			foreach($_POST['idprestaKine'] as $kine)
			{
				$idprestakine[] = $kine;
			}
			
			foreach($_POST['prixprestaKine'] as $valKine)
			{
				$prixmk[] = $valKine;
			}
			
			foreach($_POST['percentKine'] as $valeurKine)
			{
				$addKine[] = $valeurKine;
			}
			
			foreach($_POST['idmedKine'] as $valeurmk)
			{
				$idmk[] = $valeurmk;
			}
			
			foreach($_POST['autreKine'] as $autrevaluekine)
			{
				$autrekine[] = $autrevaluekine;
			}
			
			foreach($_POST['quantityKine'] as $valueKine)
			{
				$qteKine[] = $valueKine;
			}
			
			foreach($_POST['anneeMedKine'] as $anKine)
			{
				$anneeKine[] = $anKine;
			}
			
			foreach($_POST['moisMedKine'] as $moiKine)
			{
				$moisKine[] = $moiKine;
			}
			
			foreach($_POST['joursMedKine'] as $jrKine)
			{
				$jourKine[] = $jrKine;
			}
			
			foreach($_POST['idassuKine'] as $valueassukine)
			{
				$idassuKine[] = $valueassukine;
			}
			
			
			for($i=0;$i<sizeof($addKine);$i++)
			{
				$comptAssuKine=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuKine->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuKine->rowCount();
				
				for($a=1;$a<=$assuCount;$a++)
				{
					
					$getAssuKine=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuKine->execute(array(
						'idassu'=>$idassuKine[$i]
					));
					
					$getAssuKine->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomAssuKine=$getAssuKine->fetch())
					{
						$presta_assuKine='prestations_'.$ligneNomAssuKine->nomassurance;
					}
				}
				
				// echo $addKine[$i].'_'.$idmk[$i].'_('.$prixmk[$i].' : '.$qteKine[$i].')<br/>';
				
				if($moisKine[$i]<10)
				{
					$moisKine[$i]='0'.$moisKine[$i];
				}
				
				if($jourKine[$i]<10)
				{
					$jourKine[$i]='0'.$jourKine[$i];
				}
				
				$datehospMedKine=$anneeKine[$i].'-'.$moisKine[$i].'-'.$jourKine[$i];
				
				
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuKine.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=14 AND p.id_prestation='.$idprestakine[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_kine_hosp mk SET mk.datehosp=\''.$datehospMedKine.'\',mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine='.$prixmk[$i].',mk.prixautrePrestaK=0,mk.qteKine='.$qteKine[$i].',mk.codecoordi=\''.$codecoordi.'\' WHERE mk.id_medkine='.$idmk[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedKine,
						'addKine'=>$addKine[$i],
						'prixmk'=>$prixmk[$i],
						'prixmkCCO'=>$prixmkCCO[$i],
						'qteKine'=>$qteKine[$i],
						'codecoordi'=>$codecoordi,
						'idmk'=>$idmk[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_kine_hosp mk WHERE mk.id_medkine='.$idmk[$i].'');
					
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_kine_hosp mk SET mk.datehosp=\''.$datehospMedKine.'\',mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine=0,mk.prixautrePrestaK='.$prixmk[$i].',mk.qteKine='.$qteKine[$i].',mk.codecoordi=\''.$codecoordi.'\' WHERE mk.id_medkine='.$idmk[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedKine,
						'addKine'=>$addKine[$i],
						'prixmk'=>$prixmk[$i],
						'prixmkCCO'=>$prixmkCCO[$i],
						'qteKine'=>$qteKine[$i],
						'codecoordi'=>$codecoordi,
						'idmk'=>$idmk[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
				}
				
			}
		}
		
		$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk, patients_hosp p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_hospKine=:idhosp AND p.id_hosp=mk.id_hospKine AND mk.id_factureMedKine!="" ORDER BY mk.datehosp');
		$resultMedKine->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedKine->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedKine=$resultMedKine->rowCount();
		
		$TotalMedKine = 0;
		$TotalMedKinePayed = 0;
		$TotalMedKinePayedCCO = 0;
		
		
		
		/*-------Requête pour AFFICHER med_consom_hosp-----------*/
		
		if(isset($_POST['idprestaConsom']))
		{
			$idprestaconsom = array();
			$prixmco = array();
			$addConsom = array();
			$idmco = array();
			$autreconsom = array();
			$qteConsom = array();
			$anneeeConsom = array();
			$moisConsom = array();
			$jourConsom = array();
			$idassuConsom = array();

			
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
			
			foreach($_POST['idassuConsom'] as $valueassuconsom)
			{
				$idassuConsom[] = $valueassuconsom;
			}

			
			for($i=0;$i<sizeof($addConsom);$i++)
			{
				$comptAssuConsom=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuConsom->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuConsom->rowCount();
				
				for($a=1;$a<=$assuCount;$a++)
				{
					
					$getAssuConsom=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuConsom->execute(array(
						'idassu'=>$idassuConsom[$i]
					));
					
					$getAssuConsom->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomAssuConsom=$getAssuConsom->fetch())
					{
						$presta_assuConsom='prestations_'.$ligneNomAssuConsom->nomassurance;
					}
				}
			
				// echo $addConsom[$i].'_'.$idmco[$i].'_('.$prixmco[$i].' : '.$qteConsom[$i].')<br/>';
				
			
				if($moisConsom[$i]<10)
				{
					$moisConsom[$i]='0'.$moisConsom[$i];
				}
				
				if($jourConsom[$i]<10)
				{
					$jourConsom[$i]='0'.$jourConsom[$i];
				}
				
				$datehospMedConsom=$anneeConsom[$i].'-'.$moisConsom[$i].'-'.$jourConsom[$i];
				
			
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuConsom.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation='.$idprestaconsom[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consom_hosp mco SET mco.datehosp=\''.$datehospMedConsom.'\',mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom='.$prixmco[$i].',mco.prixautreConsom=0,mco.qteConsom='.$qteConsom[$i].',mco.codecoordi=\''.$codecoordi.'\' WHERE mco.id_medconsom='.$idmco[$i].'');
					
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedConsom,
						'addConsom'=>$addConsom[$i],
						'prixmco'=>$prixmco[$i],
						'prixmcoCCO'=>$prixmcoCCO[$i],
						'qteConsom'=>$qteConsom[$i],
						'codecoordi'=>$codecoordi,
						'idmco'=>$idmco[$i]

						))or die( print_r($connexion->errorInfo()));*/
						
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_consom_hosp mco WHERE mco.id_medconsom='.$idmco[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consom_hosp mco SET mco.datehosp=\''.$datehospMedConsom.'\',mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom=0,mco.prixautreConsom='.$prixmco[$i].',mco.qteConsom='.$qteConsom[$i].',mco.codecoordi=\''.$codecoordi.'\' WHERE mco.id_medconsom='.$idmco[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedConsom,
						'addConsom'=>$addConsom[$i],
						'prixmco'=>$prixmco[$i],
						'prixmcoCCO'=>$prixmcoCCO[$i],
						'qteConsom'=>$qteConsom[$i],
						'codecoordi'=>$codecoordi,
						'idmco'=>$idmco[$i]

						))or die( print_r($connexion->errorInfo()));*/
						
					}
				}
			}
		}
		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, patients_hosp p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_hospConsom=:idhosp AND p.id_hosp=mco.id_hospConsom AND mco.id_factureMedConsom!="" ORDER BY mco.datehosp');
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		$TotalMedConsomPayed = 0;
		$TotalMedConsomPayedCCO = 0;
		
	
	
		/*-------Requête pour AFFICHER med_medoc_hosp-----------*/
	
		
		if(isset($_POST['idprestaMedoc']))
		{
			$idprestamedoc = array();
			$prixmdo = array();
			$addMedoc = array();
			$idmdo = array();
			$autremedoc = array();
			$qteMedoc = array();
			$anneeeMedoc = array();
			$moisMedoc = array();
			$jourMedoc = array();
			$idassuMedoc = array();

			
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
			
			foreach($_POST['idassuMedoc'] as $valueassumedoc)
			{
				$idassuMedoc[] = $valueassumedoc;
			}


			for($i=0;$i<sizeof($addMedoc);$i++)
			{
				$comptAssuMedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuMedoc->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuMedoc->rowCount();
				
				for($a=1;$a<=$assuCount;$a++)
				{
					
					$getAssuMedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuMedoc->execute(array(
						'idassu'=>$idassuMedoc[$i]
					));
					
					$getAssuMedoc->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomAssuMedoc=$getAssuMedoc->fetch())
					{
						$presta_assuMedoc='prestations_'.$ligneNomAssuMedoc->nomassurance;
					}
				}
			
				// echo $addMedoc[$i].'_'.$idmdo[$i].'_('.$prixmdo[$i].' : '.$qteMedoc[$i].')<br/>';
				
				if($moisMedoc[$i]<10)
				{
					$moisMedoc[$i]='0'.$moisMedoc[$i];
				}
				
				if($jourMedoc[$i]<10)
				{
					$jourMedoc[$i]='0'.$jourMedoc[$i];
				}
				
				$datehospMedMedoc=$anneeMedoc[$i].'-'.$moisMedoc[$i].'-'.$jourMedoc[$i];
				
			
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuMedoc.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation='.$idprestamedoc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_medoc_hosp mdo SET mdo.datehosp=\''.$datehospMedMedoc.'\',mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc='.$prixmdo[$i].',mdo.prixautreMedoc=0,mdo.qteMedoc='.$qteMedoc[$i].',mdo.codecoordi=\''.$codecoordi.'\' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedMedoc,
						'addMedoc'=>$addMedoc[$i],
						'prixmdo'=>$prixmdo[$i],
						'prixmdoCCO'=>$prixmdoCCO[$i],
						'qteMedoc'=>$qteMedoc[$i],
						'codecoordi'=>$codecoordi,
						'idmdo'=>$idmdo[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_medoc_hosp mdo WHERE mdo.id_medmedoc='.$idmdo[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_medoc_hosp mdo SET mdo.datehosp=\''.$datehospMedMedoc.'\',mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc=0,mdo.prixautreMedoc='.$prixmdo[$i].',mdo.qteMedoc='.$qteMedoc[$i].',mdo.codecoordi=\''.$codecoordi.'\' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedMedoc,
						'addMedoc'=>$addMedoc[$i],
						'prixmdo'=>$prixmdo[$i],
						'prixmdoCCO'=>$prixmdoCCO[$i],
						'qteMedoc'=>$qteMedoc[$i],
						'codecoordi'=>$codecoordi,
						'idmdo'=>$idmdo[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
				}
				
			}
		}
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, patients_hosp p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_hospMedoc=:idhosp AND p.id_hosp=mdo.id_hospMedoc AND mdo.id_factureMedMedoc!="" ORDER BY mdo.datehosp');
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
		$TotalMedMedocPayed = 0;
		$TotalMedMedocPayedCCO = 0;


		
		/*-------Requête pour AFFICHER med_ortho_hosp-----------*/
		
		
		if(isset($_POST['idprestaOrtho']))
		{
			$idprestaortho = array();
			$prixmo = array();
			$addOrtho = array();
			$idmo = array();
			$autreortho = array();
			$qteOrtho = array();
			$anneeeOrtho = array();
			$moisOrtho = array();
			$jourOrtho = array();
			$idassuOrtho = array();
			
			
			foreach($_POST['idprestaOrtho'] as $ortho)
			{
				$idprestaortho[] = $ortho;
			}
			
			foreach($_POST['prixprestaOrtho'] as $valOrtho)
			{
				$prixmo[] = $valOrtho;
			}
			
			foreach($_POST['percentOrtho'] as $valeurOrtho)
			{
				$addOrtho[] = $valeurOrtho;
			}
			
			foreach($_POST['idmedOrtho'] as $valeurmo)
			{
				$idmo[] = $valeurmo;
			}
			
			foreach($_POST['autreOrtho'] as $autrevalueortho)
			{
				$autreortho[] = $autrevalueortho;
			}
			
			foreach($_POST['quantityOrtho'] as $valueOrtho)
			{
				$qteOrtho[] = $valueOrtho;
			}
			
			foreach($_POST['anneeMedOrtho'] as $anOrtho)
			{
				$anneeOrtho[] = $anOrtho;
			}
			
			foreach($_POST['moisMedOrtho'] as $moiOrtho)
			{
				$moisOrtho[] = $moiOrtho;
			}
			
			foreach($_POST['joursMedOrtho'] as $jrOrtho)
			{
				$jourOrtho[] = $jrOrtho;
			}
			
			foreach($_POST['idassuOrtho'] as $valueassuortho)
			{
				$idassuOrtho[] = $valueassuortho;
			}
			
			
			for($i=0;$i<sizeof($addOrtho);$i++)
			{
				$comptAssuOrtho=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuOrtho->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuOrtho->rowCount();
				
				for($a=1;$a<=$assuCount;$a++)
				{
					
					$getAssuOrtho=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuOrtho->execute(array(
						'idassu'=>$idassuOrtho[$i]
					));
					
					$getAssuOrtho->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomAssuOrtho=$getAssuOrtho->fetch())
					{
						$presta_assuOrtho='prestations_'.$ligneNomAssuOrtho->nomassurance;
					}
				}
				
				// echo $addOrtho[$i].'_'.$idmo[$i].'_('.$prixmo[$i].' : '.$qteOrtho[$i].')<br/>';
				
				if($moisOrtho[$i]<10)
				{
					$moisOrtho[$i]='0'.$moisOrtho[$i];
				}
				
				if($jourOrtho[$i]<10)
				{
					$jourOrtho[$i]='0'.$jourOrtho[$i];
				}
				
				$datehospMedOrtho=$anneeOrtho[$i].'-'.$moisOrtho[$i].'-'.$jourOrtho[$i];
				
				
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuOrtho.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation='.$idprestaortho[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						echo $idprestaortho[$i].'-';
						
						$updatepercent=$connexion->query('UPDATE med_ortho_hosp mo SET mo.datehosp=\''.$datehospMedOrtho.'\',mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho='.$prixmo[$i].',mo.prixautrePrestaO=0,mo.qteOrtho='.$qteOrtho[$i].',mo.codecoordi=\''.$codecoordi.'\' WHERE mo.id_medortho='.$idmo[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedOrtho,
						'addOrtho'=>$addOrtho[$i],
						'prixmo'=>$prixmo[$i],
						'prixmoCCO'=>$prixmoCCO[$i],
						'qteOrtho'=>$qteOrtho[$i],
						'codecoordi'=>$codecoordi,
						'idmo'=>$idmo[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_ortho_hosp mo WHERE mo.id_medortho='.$idmo[$i].'');
					
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_ortho_hosp mo SET mo.datehosp=\''.$datehospMedOrtho.'\',mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho=0,mo.prixautrePrestaO='.$prixmo[$i].',mo.qteOrtho='.$qteOrtho[$i].',mo.codecoordi=\''.$codecoordi.'\' WHERE mo.id_medortho='.$idmo[$i].'');
						
						/*$updatepercent->execute(array(
						'datehosp'=>$datehospMedOrtho,
						'addOrtho'=>$addOrtho[$i],
						'prixmo'=>$prixmo[$i],
						'prixmoCCO'=>$prixmoCCO[$i],
						'qteOrtho'=>$qteOrtho[$i],
						'codecoordi'=>$codecoordi,
						'idmo'=>$idmo[$i]
						
						))or die( print_r($connexion->errorInfo()));*/
						
					}
				}
				
			}
		}
		
		$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo, patients_hosp p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_hospOrtho=:idhosp AND p.id_hosp=mo.id_hospOrtho AND mo.id_factureMedOrtho!="" ORDER BY mo.datehosp');
		$resultMedOrtho->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedOrtho=$resultMedOrtho->rowCount();
		
		$TotalMedOrtho = 0;
		$TotalMedOrthoPayed = 0;
		$TotalMedOrthoPayedCCO = 0;
	
	
	?>
	
	<table style="width:100%; margin:20px auto auto;">
		<tr>
			<td style="text-align:left; width:33%;">
				<h4><?php echo $datebill;?></h4>
			</td>
			
			<td style="text-align:center; width:43%;">
				<h2 style="font-size:150%; font-weight:600;">Hospitalisation bill-exit n° <?php echo $numbill;?></h2>
			</td>
			
			<td style="text-align:right;width:33%;">
				<form method="post" action="printBill_hospReport_modifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&datehosp=<?php echo $datehosp;?>&dateSortie=<?php if(isset($_GET['dateSortie'])){ echo $_GET['dateSortie'];}else{ echo $dateSortie;}?>&numbill=<?php echo $_GET['numbill'];?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($assurance)){ echo '&idassu='.$assurance;}else{ if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}}?><?php if(isset($nomassurancebill)){ echo '&nomassurance='.$nomassurancebill;}else{ if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}}?><?php if(isset($percentIdbill)){ echo '&billpercent='.$percentIdbill;}else{ if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?>&updatebill=ok&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
				</form>
			</td>
		
			<td style="text-align:right;width:25%;" class="buttonBill">
				
				<a href="formModifierBillHosp.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($assurance)){ echo '&idassu='.$assurance;}else{ if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}}?><?php if(isset($nomassurancebill)){ echo '&nomassurance='.$nomassurancebill;}else{ if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}}?><?php if(isset($percentIdbill)){ echo '&billpercent='.$percentIdbill;}else{ if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?>" id="updatebtn" style="margin:5px;">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo 'Modifier';?></button>
				</a>
			
			</td>
			
			<td class="buttonBill" style="text-align:right;width:25%;">
				<a href="categoriesbill_hospmodifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}else{ echo '&datehosp='.$datehosp;}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($assurance)){ echo '&idassu='.$assurance;}else{ if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}}?><?php if(isset($nomassurancebill)){ echo '&nomassurance='.$nomassurancebill;}else{ if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}}?><?php if(isset($percentIdbill)){ echo '&billpercent='.$percentIdbill;}else{ if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="margin:5px;">
					<button class="btn-large" style="width:150px;"><i class="fa fa-plus fa-lg fa-fw"></i> <?php echo getString(221);?></button>
				</a>
				
				<a href="facturesedit.php?manager=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="finishbtn" style="margin:5px;">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-check fa-lg fa-fw"></i> <?php echo getString(141);?></button>
				</a>
				
			</td>
		</tr>
	</table>
	
	
	<?php
		try
		{
			$TotalGnlPriceCCO=0;
			$TotalGnlPrice=0;
			$TotalGnlTopupPrice=0;
			$TotalGnlPatientPrice=0;
			$TotalGnlPatientBalance=0;
			$TotalGnlInsurancePrice=0;
			
			
			$TotalGnlPricePayedCCO=0;
			$TotalGnlPricePayed=0;
			$TotalGnlTopupPricePayed=0;
			$TotalGnlPatientPricePayed=0;
			$TotalGnlPatientBalancePayed=0;
			$TotalGnlInsurancePricePayed=0;
			
			$i=0;
			$x=0;
			$y=0;
			$z=0;
			
			if($comptHosp != 0)
			{
		
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B8', 'Type of consultation')
							->setCellValue('C8', 'Price')
							->setCellValue('D8', 'Patient Price')
							->setCellValue('E8', 'Insurance Price');
				
					
	?>
			<table class="printPreview tablesorter3" cellspacing="0" style="margin:0 auto 25px;">
				<thead>
					<tr>
						<th style="width:5%;text-align:center;">Room</th>
						<!--<th style="width:20%;text-align:center;">Type</th>-->
						<th style="width:10%;text-align:center;">Date In</th>
						<th style="width:13%;text-align:center;">Date Out</th>
						<th style="width:5%;text-align:center;">Days</th>
						<th style="width:8%;text-align:center;" class="buttonBill">Price/day</th>
						<th style="width:8%;text-align:center;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:8%;text-align:center;">Percent</th>
						<th style="width:13%;text-align:center;">Patient <?php echo '('.$percentIdbill.'%)'?></th>
						<th style="width:13%;text-align:center;">Patient balance</th>
						<th style="width:13%;text-align:center;">Insurance</th>
					</tr>
				</thead>

				<tbody>
				<?php
				
				$TotalPatientPriceHospPayed=0;
				$TotalInsurancePriceHospPayed=0;
				
				$TotalPatientPriceHosp=0;
				$TotalInsurancePriceHosp=0;
		
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
							}else{
							
								if($lignePresta->nompresta!='')
								{
									$nameprestaHosp=$lignePresta->nompresta;
								}
							}
							
							$prixPrestaHosp = $lignePresta->prixpresta;
							$prixPrestaHospCCO = $lignePresta->prixprestaCCO;
							
						?>
							
							<td style="text-align:center;">
							<?php
								echo date('d-M-Y', strtotime($ligneHosp->dateEntree));
							?>
							</td>
							<td style="text-align:center;">
							<?php
								echo date('d-M-Y', strtotime($ligneHosp->dateSortie));
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$dateIn=strtotime($ligneHosp->dateEntree);
							$dateOut=strtotime($ligneHosp->dateSortie);
							
							$datediff= abs($dateOut - $dateIn);
							
							$nbrejrs= floor($datediff /(60*60*24));
							
							if($nbrejrs==0)
							{
								$nbrejrs=1;
							}
							
							if($nbrejrs==1)
							{
								echo $nbrejrs.' day';
							}else{
								echo $nbrejrs.' days';
							}
							?>
							</td>

							<td style="text-align:center;" class="buttonBill"><?php echo $ligneHosp->prixroom;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

							
							<td style="text-align:center;">
							<?php
							$balanceHosp=$ligneHosp->prixroom * $nbrejrs;
							
							echo $balanceHosp;
							
							if($ligneHosp->statusPaHosp!=1)
							{
								$TotalHospPayed=$TotalHospPayed + $balanceHosp;
							}else{
								
								$TotalHosp=$TotalHosp + $balanceHosp;
							}
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<?php
							
							$patientPriceHosp=($balanceHosp * $billpercent)/100;
							$insurancePriceHosp= $balanceHosp - $patientPriceHosp;
							
							
							$TotalPatientPriceHosp=$TotalPatientPriceHosp + $patientPriceHosp;
							$TotalInsurancePriceHosp = $TotalInsurancePriceHosp + $insurancePriceHosp;
							
							?>

							<td style="text-align:center;">
								<?php echo $billpercent;?>%
							</td>

							<td style="text-align:center;">
								<?php echo $patientPriceHosp;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td>
								<?php
								$patientBalanceHosp = $patientPriceHosp;
								echo $patientBalanceHosp.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="text-align:center;">
								<?php
								echo $insurancePriceHosp;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
					</tr>
				<?php
						}
						
					}
					
					$arrayConsult[$i][0]=$nameprestaHosp;
					$arrayConsult[$i][1]=$prixPrestaHosp;
					$arrayConsult[$i][2]=$patientPriceHosp;
					$arrayConsult[$i][3]=$insurancePriceHosp;
					
					$i++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','B9');
		
				}
				
				?>
				</tbody>
			</table>
			<?php
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.(9+$i).'', ''.$balanceHosp.'')
					->setCellValue('D'.(9+$i).'', ''.$patientPriceHosp.'')
					->setCellValue('E'.(9+$i).'', ''.$insurancePriceHosp.'');
				
			}
			
			
			if($comptMedSurge != 0)
			{
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B'.(14+$i+$x).'', 'Surgery')
					->setCellValue('C'.(14+$i+$x).'', 'Price')
					->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
					->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');
				
				?>

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
					<thead>
						<tr>
							<th style="width:20%" colspan=13>Surgery</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$percentIdbill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotalpatientPricePayed=0;
						$TotaluapPricePayed=0;
						
						while($ligneMedSurge=$resultMedSurge->fetch())
						{
							
							$billpercent=$ligneMedSurge->insupercentSurge;
							
							$idassu=$ligneMedSurge->id_assuSurge;
							
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
								'prestaId'=>$ligneMedSurge->id_prestationSurge
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
							
							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedSurge->datehosp;?></td>
									<td style="text-align:left;">
										<?php
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

									<td>
										<?php
										$qteSurge=$ligneMedSurge->qteSurge;
										echo $qteSurge;
										?>
									</td>


									<td>
										<?php
										$prixPresta = $ligneMedSurge->prixprestationSurge;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteSurge;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedSurge = $TotalMedSurge + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedSurge->id_factureMedSurge!="" AND $ligneMedSurge->id_factureMedSurge!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
							}
							
							if($ligneMedSurge->id_prestationSurge=="" AND $ligneMedSurge->prixautrePrestaS!=0)
							{
								?>
								<tr style="text-align:center;" class="buttonBill">
									<td><?php echo $ligneMedSurge->datesoins;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedSurge=$ligneMedSurge->autrePrestaM;
										
										echo $ligneMedSurge->autrePrestaM;
										?>
									</td>

									<td>
										<?php
										$qteSurge=$ligneMedSurge->qteSurge;
										echo $qteSurge;
										?>
									</td>


									<td>
										<?php
										$prixPresta = $ligneMedSurge->prixautrePrestaS;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteSurge;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $topupPrice + $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedSurge = $TotalMedSurge + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>


									<td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										if($ligneMedSurge->id_factureMedSurge!="" AND $ligneMedSurge->id_factureMedSurge!=0)
										{
											echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
										}
										?>
									</td>
								</tr>
								<?php
							}
							
							
							$arrayMedSurge[$y][0]=$nameprestaMedSurge;
							$arrayMedSurge[$y][1]=$prixPresta;
							$arrayMedSurge[$y][2]=$patientPrice;
							$arrayMedSurge[$y][3]=$uapPrice;
							
							$y++;
							
							$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayMedSurge,'','B'.(15+$i+$x).'');
						}
						?>
						<tr style="text-align:center;">
							<td colspan=4 class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedSurge.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedSurge;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedSurgePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>


							<td class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<!--<td>
								<?php
							/*								if($ligneMedSurge->id_factureMedSurge!="" AND $ligneMedSurge->id_factureMedSurge!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
						</tr>
					</tbody>
				</table>
				<?php
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedSurge.'')
					->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
					->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');
				
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
							<th style="width:20%" colspan=13>Nursing Care</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$percentIdbill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
						while($ligneMedInf=$resultMedInf->fetch())
						{
							
							$billpercent=$ligneMedInf->insupercentInf;
							
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
							
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedInf->id_prestation
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
							
							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedInf->datehosp;?></td>
									<td style="text-align:left;">
										<?php
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

									<td>
										<?php
										$qteInf=$ligneMedInf->qteInf;
										echo $qteInf;
										?>
									</td>


									<td>
										<?php
										$prixPresta = $ligneMedInf->prixprestation;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteInf;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance =$patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedInf = $TotalMedInf + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<td><?php echo $ligneMedInf->insupercentInf;?>%</td>
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
								</tr>
								<?php
							}
							
							if($ligneMedInf->id_prestation=="" AND $ligneMedInf->prixautrePrestaM!=0)
							{
								?>
								<tr style="text-align:center;" class="buttonBill">
									<td><?php echo $ligneMedInf->datesoins;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedInf=$ligneMedInf->autrePrestaM;
										
										echo $ligneMedInf->autrePrestaM;
										?>
									</td>

									<td>
										<?php
										$qteInf=$ligneMedInf->qteInf;
										echo $qteInf;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedInf->prixautrePrestaM;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteInf;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $topupPrice + $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										/*if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
										{*/
										$TotalMedInf = $TotalMedInf + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										/*}else{
											$TotalMedInfPayedCCO= $TotalMedInfPayedCCO + $balanceCCO;
											$TotalMedInfPayed= $TotalMedInfPayed + $balance;
											$TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
											$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
											$TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
											$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
										}*/
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>


									<td><?php echo $ligneMedInf->insupercentInf;?>%</td>
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
								</tr>
								<?php
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
						<tr style="text-align:center;">
							<td colspan=4 class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedInf.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedInfPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>


							<td class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<!--<td>
								<?php
							/*								if($ligneMedInf->id_factureMedInf!="" AND $ligneMedInf->id_factureMedInf!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
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

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
					<thead>
						<tr>
							<th style="width:20%" colspan=13>Labs</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$percentIdbill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
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
							
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
							
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedLabo->id_prestationExa
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
							
							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedLabo->datehosp;?></td>
									<td style="text-align:left;">
										<?php
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

									<td>
										<?php
										$qteLab=$ligneMedLabo->qteLab;
										echo $qteLab;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedLabo->prixprestationExa;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteLab;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedLabo = $TotalMedLabo + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->


									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>


									<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedLabo->id_factureMedLabo!="" AND $ligneMedLabo->id_factureMedLabo!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
							}
							
							if($ligneMedLabo->id_prestationExa=="" AND $ligneMedLabo->prixautreExamen!=0)
							{
								?>
								<tr style="text-align:center;" class="buttonBill">
									<td><?php echo $ligneMedLabo->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedLabo=$ligneMedLabo->autreExamen;
										echo $ligneMedLabo->autreExamen;
										
										?>
									</td>

									<td>
										<?php
										$qteLab=$ligneMedLabo->qteLab;
										echo $qteLab;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedLabo->prixautreExamen;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteLab;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedLabo = $TotalMedLabo + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>


									<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedLabo->id_factureMedLabo!="" AND $ligneMedLabo->id_factureMedLabo!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
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
						<tr style="text-align:center;">
							<td colspan=4 class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedLabo.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedLaboPayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<!--<td>
								<?php
							/*								if($ligneMedLabo->id_factureMedLabo!="" AND $ligneMedLabo->id_factureMedLabo!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
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

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
					<thead>
						<tr>
							<th style="width:20%" colspan=13>Radiologie</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$percentIdbill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
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
							
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
							
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedRadio->id_prestationRadio
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
							
							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedRadio->datehosp;?></td>
									<td style="text-align:left;">
										<?php
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

									<td>
										<?php
										$qteRad=$ligneMedRadio->qteRad;
										echo $qteRad;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedRadio->prixprestationRadio;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteRad;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedRadio = $TotalMedRadio + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedRadio->id_factureMedRadio!="" AND $ligneMedRadio->id_factureMedRadio!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
							}
							
							if($ligneMedRadio->id_prestationRadio=="" AND $ligneMedRadio->prixautreRadio!=0)
							{
								?>
								<tr style="text-align:center;" class="buttonBill">
									<td><?php echo $ligneMedRadio->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedRadio=$ligneMedRadio->autreRadio;
										
										echo $ligneMedRadio->autreRadio;
										?>
									</td>

									<td>
										<?php
										$qteRad=$ligneMedRadio->qteRad;
										echo $qteRad;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedRadio->prixautreRadio;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteRad;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedRadio = $TotalMedRadio + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedRadio->id_factureMedRadio!="" AND $ligneMedRadio->id_factureMedRadio!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
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
						<tr style="text-align:center;">
							<td colspan=4 class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedRadio.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
								
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedRadioPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<!--<td>
								<?php
							/*								if($ligneMedRadio->id_factureMedRadio!="" AND $ligneMedRadio->id_factureMedRadio!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
						</tr>

					</tbody>
				</table>
				<?php
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.(18+$i+$x+$y+$z).'', ''.$TotalMedRadio.'')
					->setCellValue('D'.(18+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
					->setCellValue('E'.(18+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
				
			}
			
			
			if($comptMedKine != 0)
			{
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B'.(14+$i+$x).'', 'Physiotherapy')
					->setCellValue('C'.(14+$i+$x).'', 'Price')
					->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
					->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');
				
				?>

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
					<thead>
						<tr>
							<th style="width:20%" colspan=13>Physiotherapy</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$percentIdbill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
						while($ligneMedKine=$resultMedKine->fetch())
						{
							
							$billpercent=$ligneMedKine->insupercentKine;
							
							$idassu=$ligneMedKine->id_assuKine;
							
							$comptAssuKine=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuKine->setFetchMode(PDO::FETCH_OBJ);
							
							$assuCount = $comptAssuKine->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuKine=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuKine->execute(array(
									'idassu'=>$idassu
								));
								
								$getAssuKine->setFetchMode(PDO::FETCH_OBJ);
								
								if($ligneNomAssu=$getAssuKine->fetch())
								{
									$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
								}
							}
							
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedKine->id_prestationKine
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
							
							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedKine->datehosp;?></td>
									<td style="text-align:left;">
										<?php
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

									<td>
										<?php
										$qteKine=$ligneMedKine->qteKine;
										echo $qteKine;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedKine->prixprestationKine;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteKine;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedKine = $TotalMedKine + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedKine->insupercentKine;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedKine->id_factureMedKine!="" AND $ligneMedKine->id_factureMedKine!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
							}
							
							if($ligneMedKine->id_prestationKine=="" AND $ligneMedKine->prixautrePrestaK!=0)
							{
								?>
								<tr style="text-align:center;" class="buttonBill">
									<td><?php echo $ligneMedKine->datesoins;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedKine=$ligneMedKine->autrePrestaM;
										
										echo $ligneMedKine->autrePrestaM;
										?>
									</td>

									<td>
										<?php
										$qteKine=$ligneMedKine->qteKine;
										echo $qteKine;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedKine->prixautrePrestaK;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteKine;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										
										$TotalMedKine= $TotalMedKine + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedKine->insupercentKine;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedKine->id_factureMedKine!="" AND $ligneMedKine->id_factureMedKine!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
							}
							
							
							$arrayMedKine[$y][0]=$nameprestaMedKine;
							$arrayMedKine[$y][1]=$prixPresta;
							$arrayMedKine[$y][2]=$patientPrice;
							$arrayMedKine[$y][3]=$uapPrice;
							
							$y++;
							
							$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayMedKine,'','B'.(15+$i+$x).'');
						}
						?>
						<tr style="text-align:center;">
							<td colspan=4 class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedKine.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedKine;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedKinePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<!--<td>
								<?php
							/*								if($ligneMedKine->id_factureMedKine!="" AND $ligneMedKine->id_factureMedKine!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
						</tr>
					</tbody>
				</table>
				<?php
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedKine.'')
					->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
					->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');
				
			}
			
			
			if($comptMedConsom != 0)
			{
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B'.(20+$i+$x+$y).'', 'Consommables')
					->setCellValue('C'.(20+$i+$x+$y).'', 'Price')
					->setCellValue('D'.(20+$i+$x+$y).'', 'Patient Price')
					->setCellValue('E'.(20+$i+$x+$y).'', 'Insurance Price');
				
				?>

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
					<thead>
						<tr>
							<th style="width:20%" colspan=13>Consommables</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$percentIdbill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
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
							
							if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
							{
								?>
								<tr style="text-align:center;" class="buttonBill">
									<td><?php echo $ligneMedConsom->datehosp;?></td>
									<td style="text-align:left;">
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

									<td>
										<?php
										$qteConsom=$ligneMedConsom->qteConsom;
										
										echo $qteConsom;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedConsom->prixprestationConsom;
										echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteConsom;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedConsom= $TotalMedConsom + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedConsom->id_factureMedConsom!="" AND $ligneMedConsom->id_factureMedConsom!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
							}
							
							if($ligneMedConsom->id_prestationConsom==0 AND $ligneMedConsom->prixautreConsom!=0)
							{
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedConsom->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedConsom=$ligneMedConsom->autreConsom;
										echo $nameprestaMedConsom;
										?>
									</td>

									<td>
										<?php
										$qteConsom=$ligneMedConsom->qteConsom;
										echo $qteConsom;
										?>
									</td>


									<td>
										<?php
										$prixPresta = $ligneMedConsom->prixautreConsom;
										echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteConsom;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $topupPrice + $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedConsom= $TotalMedConsom + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedConsom->id_factureMedConsom!="" AND $ligneMedConsom->id_factureMedConsom!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
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
							<td colspan=4 class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedConsom.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsomPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								// float round(float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP)
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<!--<td>
								<?php
							/*								if($ligneMedKine->id_factureMedKine!="" AND $ligneMedKine->id_factureMedKine!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
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

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
					<thead>
						<tr>
						<th style="width:20%" colspan=13>Medicaments</th>
						</tr>
						<tr>
							<th style="width:9%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$percentIdbill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
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
							
							if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
							{
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedMedoc->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										if($lignePresta->namepresta!='')
										{
											$nameprestaMedMedoc=$lignePresta->namepresta;
											echo $lignePresta->namepresta;
											
										}else{
											
											$nameprestaMedMedoc=$lignePresta->nompresta;
											echo $lignePresta->nompresta;
											
										}
										?>

									</td>

									<td>
										<?php
										$qteMedoc=$ligneMedMedoc->qteMedoc;
										
										echo $qteMedoc;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedMedoc->prixprestationMedoc;
										echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteMedoc;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedMedoc= $TotalMedMedoc + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedMedoc->id_factureMedMedoc!="" AND $ligneMedMedoc->id_factureMedMedoc!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								
								<?php
							}
							
							if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc!=0)
							{
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedMedoc->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedMedoc=$ligneMedMedoc->autreMedoc;
										echo $nameprestaMedMedoc;
										?>
									</td>

									<td>
										<?php
										$qteMedoc=$ligneMedMedoc->qteMedoc;
										echo $qteMedoc;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedMedoc->prixautreMedoc;
										echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteMedoc;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedMedoc= $TotalMedMedoc + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedMedoc->id_factureMedMedoc!="" AND $ligneMedMedoc->id_factureMedMedoc!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
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
							<td colspan=4 class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedMedoc.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedMedocPayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<!--<td>
								<?php
							/*								if($ligneMedMedoc->id_factureMedMedoc!="" AND $ligneMedMedoc->id_factureMedMedoc!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
						</tr>

					</tbody>
				</table>
				<?php
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.(24+$i+$x+$y+$z).'', ''.$TotalMedMedoc.'')
					->setCellValue('D'.(24+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
					->setCellValue('E'.(24+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
				
			}
			
			
			if($comptMedOrtho != 0)
			{
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B'.(14+$i+$x).'', 'P & O')
					->setCellValue('C'.(14+$i+$x).'', 'Price')
					->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
					->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');
				
				?>

				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
					<thead>
						<tr>
							<th style="width:20%" colspan=13>P & O</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$percentIdbill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
						while($ligneMedOrtho=$resultMedOrtho->fetch())
						{
							
							$billpercent=$ligneMedOrtho->insupercentOrtho;
							
							$idassu=$ligneMedOrtho->id_assuOrtho;
							
							$comptAssuOrtho=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuOrtho->setFetchMode(PDO::FETCH_OBJ);
							
							$assuCount = $comptAssuOrtho->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuOrtho=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuOrtho->execute(array(
									'idassu'=>$idassu
								));
								
								$getAssuOrtho->setFetchMode(PDO::FETCH_OBJ);
								
								if($ligneNomAssu=$getAssuOrtho->fetch())
								{
									$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
								}
							}
							
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedOrtho->id_prestationOrtho
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
							
							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedOrtho->datehosp;?></td>
									<td style="text-align:left;">
										<?php
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

									<td>
										<?php
										$qteOrtho=$ligneMedOrtho->qteOrtho;
										echo $qteOrtho;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedOrtho->prixprestationOrtho;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteOrtho;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										$TotalMedOrtho = $TotalMedOrtho + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										//echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedOrtho->id_factureMedOrtho!="" AND $ligneMedOrtho->id_factureMedOrtho!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
							}
							
							if($ligneMedOrtho->id_prestationOrtho=="" AND $ligneMedOrtho->prixautrePrestaO!=0)
							{
								?>
								<tr style="text-align:center;" class="buttonBill">
									<td><?php echo $ligneMedOrtho->datesoins;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedOrtho=$ligneMedOrtho->autrePrestaM;
										
										echo $ligneMedOrtho->autrePrestaM;
										?>
									</td>

									<td>
										<?php
										$qteOrtho=$ligneMedOrtho->qteOrtho;
										echo $qteOrtho;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedOrtho->prixautrePrestaO;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteOrtho;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedOrtho = $TotalMedOrtho + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedOrtho->id_factureMedOrtho!="" AND $ligneMedOrtho->id_factureMedOrtho!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
							}
							
							
							$arrayMedOrtho[$y][0]=$nameprestaMedOrtho;
							$arrayMedOrtho[$y][1]=$prixPresta;
							$arrayMedOrtho[$y][2]=$patientPrice;
							$arrayMedOrtho[$y][3]=$uapPrice;
							
							$y++;
							
							$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayMedOrtho,'','B'.(15+$i+$x).'');
						}
						?>
						<tr style="text-align:center;">
							<td colspan=4 class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedOrtho.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedOrtho;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedOrthoPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<!--<td>
								<?php
							/*								if($ligneMedOrtho->id_factureMedOrtho!="" AND $ligneMedOrtho->id_factureMedOrtho!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
						</tr>
					</tbody>
				</table>
				<?php
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedOrtho.'')
					->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
					->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');
				
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
							<th style="width:20%" colspan=13>Services</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurancebill;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$percentIdbill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
						
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
							
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
							
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedConsult->id_prestationConsu
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
							
							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedConsult->datehosp;?></td>
									<td style="text-align:left;">
										<?php
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

									<td>
										<?php
										$qteConsu=$ligneMedConsult->qteConsu;
										echo $qteConsu;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedConsult->prixprestationConsu;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteConsu;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedConsult = $TotalMedConsult + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedConsult->insupercentServ;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedConsult->id_factureMedConsu!="" OR $ligneMedConsult->id_factureMedConsu!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
								<?php
							}
							
							if($ligneMedConsult->id_prestationConsu=="" AND $ligneMedConsult->prixautreConsu!=0)
							{
								?>
								<tr style="text-align:center;" class="buttonBill">

									<td><?php echo $ligneMedConsult->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										
										$nameprestaMedConsult=$ligneMedConsult->autreConsu;
										echo $ligneMedConsult->autreConsu;
										?>
									</td>

									<td>
										<?php
										$qteConsu=$ligneMedConsult->qteConsu;
										echo $qteConsu;
										?>
									</td>

									<td>
										<?php
										$prixPresta = $ligneMedConsult->prixautreConsu;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>

									<!-- <td> -->
										<?php
										$balance=$prixPresta*$qteConsu;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedConsult = $TotalMedConsult + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									<!-- </td> -->

									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>

									<td><?php echo $ligneMedConsult->insupercentServ;?>%</td>

									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<!--<td>
										<?php
									/*										if($ligneMedConsult->id_factureMedConsu!="" OR $ligneMedConsult->id_factureMedConsu!=0)
																			{
																				echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
																			}
																			*/?>
									</td>-->
								</tr>
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
						<tr style="text-align:center;">
							<td colspan=4 class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedConsult.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsultPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td class="buttonBill"></td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								
								$TotalGnlPatientBalancePayed=$TotalGnlPatientBalancePayed + $TotalpatientBalancePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<!--<td>
								<?php
							/*								if($ligneMedConsult->id_factureMedConsu!="" AND $ligneMedConsult->id_factureMedConsu!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
						</tr>
					</tbody>
				</table>
				<?php
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.(12+$i+$x).'', ''.$TotalMedConsult.'')
					->setCellValue('D'.(12+$i+$x).'', ''.$TotalpatientPrice.'')
					->setCellValue('E'.(12+$i+$x).'', ''.$TotaluapPrice.'');
				
			}
			
			
		}

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
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

function CheckOrders(order)
{
	if( hour =='heures'){
	document.getElementById('tableheure').style.display='inline';
	}
	
}


function ShowFinish(finish)
{
	if( finish =='finishbtn'){
		document.getElementById('finishbtn').style.display='inline';
	}
	
}

</script>

	</div>

	<style>

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  padding-top: 10px; /* Location of the box */
  width: 50%; /* Full width */
  height: 50%; /* Full height */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  font-family: century Gothic;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
.fa-close{
	-webkit-transition:-webkit-transform .25s, opacity .25s;
	-moz-transition:-moz-transform .25s, opacity .25s;
	transition: transform .25s, opacity .25s;
	color: #ccc;
}
.fa-close:hover{
	-webkit-transform: rotate(270deg);
    -moz-transform: rotate(270deg);
    transform: rotate(270deg);
	opacity:1;
}
.close{
	background: #222;
	padding: 2px 5px;
	border-radius: 50%;
}
</style>
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:10px; border-radius:3px; font-size:85%;">
		<?php
		$TotalFinalPrice=0;
		$TotalFinalPatientPrice=0;
		$TotalFinalPatientBalance=0;
		$TotalFinalInsurancePrice=0;
		
		$TotalFinalPricePayed=0;
		$TotalFinalPatientPricePayed=0;
		$TotalFinalPatientBalancePayed=0;
		$TotalFinalInsurancePricePayed=0;
		
		?>
				<div class="buttonBill">
		<?php 
			$CheckEdi = $connexion->prepare('SELECT * FROM editedBillHisto WHERE id_factureHosp=:numbill');
			$CheckEdi->execute(array('numbill'=>$_GET['numbill']));
			$CheckEdi->setFetchMode(PDO::FETCH_OBJ);
			$countB = $CheckEdi->rowCount();

			if($countB !=0){
		?>
			<tr>
				<td style="text-align:left; width:33%;">
					
				</td>
				<td style="text-align:center; width:33%;">
					<h5 style="font-size:100%; font-weight:600;text-align: center;color: #A00000;font-family: century Gothic;padding-bottom: 5px;">
						<i class="fa fa-info-circle" style="font-size: 20px;"></i> <span style="position: relative;bottom: 3px;">This Bill Has Been Edited.
						 <span style="text-transform: none;color: blue;cursor: pointer;" id="myBtn">Read More....</span>
						</span>
					</h5>
				</td>
			</tr>

						<!-- The Modal -->
			<div id="myModal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content">
			    <span class="close"><i class="fa fa-close"></i></span>
			    <p style="text-align: center;color: #A00000;border-bottom: 1px solid #ddd;">List Of Person Who Edited This Bill.</p>
			    <hr>
			    <div class="Info">
			    	<table class="printPreview">
			    		<thead>
			    			<th>#</th>
			    			<th style="text-align: center;">Who Edited Bill</th>
			    			<th style="text-align: center;">Edited On</th>
			    		</thead>
			    		<tbody>
			    			<?php 
			    			$count =1;
			    				while($fetchEd=$CheckEdi->fetch()){
			    					$GetUsername = $connexion->prepare("SELECT * FROM utilisateurs WHERE id_u=:id_u");
			    					$GetUsername->execute(array('id_u'=>$fetchEd->whoedit));
			    					$GetUsername->setFetchMode(PDO::FETCH_OBJ);
			    					$username = $GetUsername->fetch();
			    			?>
			    				<tr>
			    					<td><?php echo $count; ?></td>
			    					<td style="text-align: center;"><?php echo $username->full_name; ?></td>
			    					<td style="text-align: center;font-weight: bold;color: #A00000;"><?php echo $fetchEd->timee; ?></td>
			    				</tr>
			    			<?php $count++; }?>
			    		</tbody>
			    	</table>
			    </div>
			  </div>

			</div>
		<?php }?>
		</div>
		<table class="printPreview" cellspacing="0" style="margin:auto;">
			<thead>
				<tr>
					<th style="width:20%;"></th>
					<th style="width:15%;">Total <?php echo $nomassurance;?></th>
					<th style="width:15%;">Patient <?php echo '('.$billpercent.'%)';?></th>
					<th style="width:15%;">Patient Balance</th>
					<th style="width:10%;">Insurance</th>
				</tr>
			</thead>

			<tbody>
				
				<?php
				
				$TotalFinalPrice=$TotalGnlPrice + $balanceHosp;
				$TotalFinalPatientPrice=$TotalGnlPatientPrice + $patientPriceHosp;
				$TotalFinalPatientBalance=$TotalGnlPatientBalance + $patientBalanceHosp;
				$TotalFinalInsurancePrice=$TotalGnlInsurancePrice + $insurancePriceHosp;
				
				
				$TotalFinalPricePayed=$TotalGnlPricePayed + $balanceHosp;
				$TotalFinalPatientPricePayed=$TotalGnlPatientPricePayed + $patientPriceHosp;
				$TotalFinalPatientBalancePayed=$TotalGnlPatientBalancePayed + $patientBalanceHosp;
				$TotalFinalInsurancePricePayed=$TotalGnlInsurancePricePayed + $insurancePriceHosp;
				
				
				/*
						if($TotalFinalPricePayed !=0)
						{
						?>
						<tr style="text-align:center;">
							<td style="font-size: 13px; font-weight: bold;">Final Balance not payed</td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPriceCCO;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlTopupPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlInsurancePrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
						</tr>
						
						<tr style="text-align:center;color:rgba(0, 0, 0, 0.5);">
							<td style="font-size: 13px; font-weight: bold;">Final Balance payed</td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPricePayedCCO;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlTopupPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPatientPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPatientBalancePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlInsurancePricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
						</tr>
						<?php
						}
				*/
				?>

				<tr style="text-align:center;">
					<td style="font-size: 15px; font-weight: bold;">Final Balance</td>
					<td style="font-size: 15px; font-weight: bold;">
						<?php
						echo $TotalFinalPrice;
						?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 15px; font-weight: bold;">
						<?php
						echo $TotalFinalPatientPrice;
						?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 18px; font-weight: bold;">
						<?php
						echo $TotalFinalPatientBalance;
						?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 15px; font-weight: bold;">
						<?php
						echo $TotalFinalInsurancePrice;
						?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
				</tr>
			</tbody>
		</table>

	</div>

	<div class="account-container" style="margin: 10px auto auto; width:90%; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
	
		<?php
		$footer = '

			<table style="width:100%">
				
				<tr>
					<td style="text-align:left; margin: 10px auto auto; width:200px; background:#fff; padding-bottom:20px; border-bottom:1px solid #333;">
						<span style="font-weight:bold">Patient Signature</span>
					</td>
					
					<td style="text-align:right;">
						 Done by : <span style="font-weight:bold">'.$doneby.'</span>
					</td>
					
				</tr>
				
			</table>';

		echo $footer;
		?>
		
	</div>
	
<?php

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
	/* $file = file_get_contents("http://192.168.135.50/uap/printConsuBill.php?num=P9&manager=CSC15A01&dateconsu=2015-09-19");
	file_put_contents("toPDF.html", $file); */

}
?>
</body>
<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
</html>