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


$annee = date('Y').'-'.date('m').'-'.date('d');

$heure = date('H').':'.date('i').':'.date('s');

	$selectdate = $connexion->prepare('SELECT * FROM bills WHERE id_bill=:idbill');
	$selectdate->execute(array(
		'idbill'=>$_GET['idbill']
	));
	$selectdate->setFetchMode(PDO::FETCH_OBJ);

	if ($lignesect = $selectdate->fetch()) {

		$dateConsult1 = date('Y-m-d', strtotime($lignesect->datebill));

		$resultatConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.dateconsu=:dateconsu');
		$resultatConsu->execute(array(
		'num'=>$_GET['num'],
		'dateconsu'=>$dateConsult1
		));
		
		$resultatConsu->setFetchMode(PDO::FETCH_OBJ);

		if($ligneConsu=$resultatConsu->fetch())
		{
			$idassurance=$ligneConsu->id_assuConsu;
			$id_typeconsult=$ligneConsu->id_typeconsult;
			$idmed1 = $ligneConsu->id_uM;
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
	}

	if(isset($_POST['anneeC']))
	{
		$dateConsult = $_POST['anneeC'].'-'.$_POST['moisC'].'-'.$_POST['joursC'];
	}else{
		$dateConsult = $dateConsult1;
	}

	if(isset($_POST['idprestaConsu']))
	{
		$nomassu = $_POST['idprestaConsu'];
	}else{
		$typeconsu = $id_typeconsult;
	}

	if(isset($_POST['idprestaConsu']))
	{
		$typeconsu = $_POST['idprestaConsu'];
	}else{
		$typeconsu = $id_typeconsult;
	}

	if(isset($_POST['idprestaConsu']))
	{
		$typeconsu = $_POST['idprestaConsu'];
	}else{
		$typeconsu = $id_typeconsult;
	}

	$numPa=$_GET['num'];
	$id_Bill=$_GET['idbill'];
	

	if(isset($_POST['payement']))
	{
		if($_POST['payement']=="" AND $_POST['dettes']<$_POST['TotalGnlPatientPrice'])
		{
			$newDette=$_POST['TotalGnlPatientPrice'] - $_POST['dettes'];
			$amountpaid=$_POST['dettes'];
			$detteDone=NULL;
			$detteIdIn=$_SESSION['id'];
			$detteIdOut=NULL;
			$dateDetteOut=NULL;
			
			// echo $_POST['payement'].' != '.$_POST['TotalGnlPatientPrice'].' : <br/>'.$newDette.'<br/>'.$amountpaid.'<br/>'.$detteIdIn.'<br/>'.$detteIdOut.'<br/>';
			
		}else{
			// echo 'Done';
			
			$newDette=NULL;
			$amountpaid=$_POST['TotalGnlPatientPrice'];
			$detteDone=1;
			$detteIdIn=NULL;
			$detteIdOut=$_SESSION['id'];
			$dateDetteOut=date('Y-m-d', strtotime($annee));
		}
		
		$updateIdBill=$connexion->prepare('UPDATE bills b SET b.dette=:dette, b.detteIdIn=:detteIdIn, b.amountpaid=:amountpaid, b.detteDone=:detteDone, b.detteIdOut=:detteIdOut, b.dateDetteOut=:dateDetteOut WHERE b.id_bill=:idbill');
		
		$updateIdBill->execute(array(
			'idbill'=>$_GET['idbill'],
			'dette'=>$newDette,
			'detteIdIn'=>$detteIdIn,
			'amountpaid'=>$amountpaid,
			'detteDone'=>$detteDone,
			'detteIdOut'=>$detteIdOut,
			'dateDetteOut'=>$dateDetteOut
		
		))or die( print_r($connexion->errorInfo()));
	}
	



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
		$oldorgBill = $ligne->idorgBill;
		$idcardbill = $ligne->idcardbill;
		$numpolicebill = $ligne->numpolicebill;
		$adherentbill = $ligne->adherentbill;
		$codeCashierbill = $ligne->codecashier;
		$codeManagerbill = $ligne->codecoordi;
		
		if(isset($_POST['annee']))
		{
			$dateBill = $_POST['annee'].'-'.$_POST['mois'].'-'.$_POST['jours'].' '.$_POST['heureBill'].':'.$_POST['minuteBill'].':'.$_POST['secondeBill'];
		}else{
			$dateBill = $ligne->datebill;
		}
		
		
		$vouchernum = $ligne->vouchernum;
		$numbill = $ligne->numbill;
		$dette=$ligne->dette;
		$createBill = 0;
		
		// echo $idBilling;
		
	}/* else{

		$createIdBill=$connexion->prepare('INSERT INTO bills (numbill) VALUES(:numbill)');

		$createIdBill->execute(array(
		'numbill'=>showBN()
		));
		
		$checkIdBilling=$connexion->prepare('SELECT *FROM bills b WHERE b.numbill=:numbill ORDER BY b.id_bill LIMIT 1');
		
		$checkIdBilling->execute(array(
		'numbill'=>showBN()
		));
		
		$checkIdBilling->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBilling->fetch();
		
		$idBilling = $ligne->id_bill;
		
		$numbill = showBN();
		$createBill = 1;
		
	} */

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
	
	// echo 'New '.$idBilling;
	
	if($codeCashierbill !=NULL)
	{
		$resultatsCashier=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u and c.codecashier=:operation');
		$resultatsCashier->execute(array(
		'operation'=>$codeCashierbill
		));

		$resultatsCashier->setFetchMode(PDO::FETCH_OBJ);
		if($ligneCashier=$resultatsCashier->fetch())
		{
			$doneby = $ligneCashier->nom_u.'  '.$ligneCashier->prenom_u;
			$codeuser = $ligneCashier->codecashier;
		}
		
	}else{
		$resultatsManager=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.codecoordi=:operation');
		$resultatsManager->execute(array(
		'operation'=>$codeManagerIdbill
		));

		$resultatsManager->setFetchMode(PDO::FETCH_OBJ);
		if($ligneManager=$resultatsManager->fetch())
		{
			$doneby = $ligneManager->nom_u.'  '.$ligneManager->prenom_u;
			$codeuser = $ligneManager->codecoordi;
		}
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
		$drawing = new BCGDrawing('barcode/png/barcode'.$codeuser.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	
	<div class="account-container" style="margin: 10px auto auto; width:95%; border: 1px solid #ccc; background:#fff; padding:5px; border-radius:3px; font-size:80%;">
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
				<img src="barcode/png/barcode'.$codeuser.'.png" style="height:auto;"/>
			</td>
			
		</tr>
		
	</table>';

echo $barcode;
?>

<?php

		
		$dateconsu= date('d-M-Y', strtotime($dateConsult));
		
			/*--------------Billing Info Patient-----------------*/
		
		$resultatsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultatsPatient->execute(array(
		'operation'=>$numPa
		));
		
		$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
		
		if($lignePatient=$resultatsPatient->fetch()) {

            if ($lignePatient->sexe == "M") {
                $sexe = "Male";
            } elseif ($lignePatient->sexe == "F") {
                $sexe = "Female";
            } else {
                $sexe = "";
            }

            $resultAdresse = $connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
            $resultAdresse->execute(array(
                'idProv' => $lignePatient->province,
                'idDist' => $lignePatient->district,
                'idSect' => $lignePatient->secteur
            ));

            $resultAdresse->setFetchMode(PDO::FETCH_OBJ);

            $comptAdress = $resultAdresse->rowCount();

            if ($ligneAdresse = $resultAdresse->fetch())
            {
                if ($ligneAdresse->id_province == $lignePatient->province) {
                    $adresse = $ligneAdresse->nomprovince . ', ' . $ligneAdresse->nomdistrict . ', ' . $ligneAdresse->nomsector;

                }
            } elseif ($lignePatient->autreadresse != "") {
                $adresse = $lignePatient->autreadresse;
            } else {
                $adresse = "";
            }


            $userinfo = '<table style="width:100%;">
			
			<tr>
				<td style="text-align:left;">
					Full name:
					<span style="font-weight:bold">' . $lignePatient->nom_u . ' ' . $lignePatient->prenom_u . '</span><br/>
					Gender: <span style="font-weight:bold">' . $sexe . '</span><br/>
					Adress: <span style="font-weight:bold">' . $adresse . '</span>
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

                if (isset($_POST['assurance'])) {
                    $assurance = $_POST['assurance'];

                } else {
                    $assurance = $_GET['idassu'];
                }

                $resultAssurance = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');

                $resultAssurance->execute(array(
                    'assuId' => $assurance
                ));

                $resultAssurance->setFetchMode(PDO::FETCH_OBJ);

                if ($ligneAssu = $resultAssurance->fetch()) {
                    $idassu = $ligneAssu->id_assurance;
                    $nomassurance = $ligneAssu->nomassurance;


                    $userinfo .= '' . $nomassurance . '</span><br/>';

                    if ($assurance != 1) {

                        if (isset($_POST['percentIdbill'])) {
                            $percentIdbill = $_POST['percentIdbill'];
                        } else {
                            $percentIdbill = $_GET['billpercent'];
                        }

                        if (isset($_POST['idcardbill'])) {
                            $idcardbill = $_POST['idcardbill'];
                        }

                        if (isset($_POST['numpolicebill'])) {
                            $numpolicebill = $_POST['numpolicebill'];
                        }

                        if (isset($_POST['adherentbill'])) {
                            $adherentbill = $_POST['adherentbill'];
                        }

                        if (isset($_POST['vouchernum'])) {
                            $vouchernum = $_POST['vouchernum'];
                        }

                        if($idcardbill!="")
                        {
                            $userinfo .= 'N° insurance card:
						<span style="font-weight:bold">'.$idcardbill;

                        }

                        if($numpolicebill!="")
                        {
                            $userinfo .= '</span><br/>
						
						N° police:
						<span style="font-weight:bold">'.$numpolicebill;

                        }

                        if($adherentbill!="")
                        {
                            $userinfo .= '</span><br/>
						
						Principal member:
						<span style="font-weight:bold">'.$adherentbill;

                        }

                    } else {

                        $percentIdbill = 100;

                        $idcardbill = "";
                        $numpolicebill = "";
                        $adherentbill = "";
                    }

                    if (isset($_POST['medecin'])) {
                        echo $medecin = $_POST['medecin'];
                    }else{
                        $medecin = $idmed1;
                    }
        }

		$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
		$result->execute(array(
		'operation'=>$medecin
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codeMed=$ligne->codemedecin;
			$fullnameMed=$ligne->nom_u.' '.$ligne->prenom_u;
		}
				$userinfo .='</span>
				</td>
				
				<td style="text-align:right;">
					Patient ID: <span style="font-weight:bold">'.$lignePatient->numero.'</span><br/>
					Date of birth: <span style="font-weight:bold">'.date('d-M-Y', strtotime($lignePatient->date_naissance)).'</span><br/>
					Date of Consultation: <span style="font-weight:bold">'.$dateconsu.'</span><br/>
					
					Consulted by: <span style="font-weight:bold">'.$fullnameMed.'</span>
					
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
						->setCellValue('B4', ''.$nomassurance.' '.$percentIdbill.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.$numbill.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$dateBill.'');
			
		}
		
		
		/*-------Requête pour AFFICHER Type consultation-----------*/

		
		if(isset($_POST['prixtypeconsult']))
		{

			$idprestatc = $_POST['idprestaConsu'];
			$prixtc = $_POST['prixtypeconsult'];
			//$prixtcCCO = $_POST['prixtypeconsultCCO'];
			$addtc = $_POST['percentTypeConsu'];
			$idtc = $_POST['idConsu'];
			$autretc = $_POST['autretypeconsult'];

			$discountpercentConsu = $_POST['discountpercentConsu'];
			if($discountpercentConsu == ''){
				$discountpercentConsu = NULL;
			}

            if(isset($_POST['idassuTypeconsult']))
            {
                $idassu=$_POST['idassuTypeconsult'];
            }else{
                $idassu=$_GET['idassu'];
            }

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
		
			$result=$connexion->query('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation='.$idprestatc.'');
			
			$result->setFetchMode(PDO::FETCH_OBJ);
			
			$comptPresta=$result->rowCount();
			
			if($comptPresta!=0)
			{
				if($ligne=$result->fetch())
				{
					$updatepercent=$connexion->prepare('UPDATE consultations c SET c.id_uM=:medecin,c.dateconsu=:dateConsult,c.discountpercentConsu=:discountpercentConsu,c.id_typeconsult=:idprestatc,c.autretypeconsult="",c.insupercent=:addtc,c.prixtypeconsult=:prixtc,c.prixautretypeconsult=0 WHERE c.id_factureConsult=:id_Bill');
					
					$updatepercent->execute(array(
					'medecin'=>$medecin,
					'dateConsult'=>$dateConsult,
					'idprestatc'=>$idprestatc,
					'addtc'=>$addtc,
					'prixtc'=>$prixtc,
					'id_Bill'=>$id_Bill,
					'discountpercentConsu'=>$discountpercentConsu
					
					))or die( print_r($connexion->errorInfo()));
					
				}
				
			}else{
			
				$results=$connexion->query('SELECT *FROM consultations c WHERE c.id_factureConsult='.$id_Bill.'');
			
				$results->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligne=$results->fetch())
				{
					$updatepercent=$connexion->prepare('UPDATE consultations c SET c.id_uM=:medecin,c.dateconsu=:dateConsult,c.discountpercentConsu=:discountpercentConsu,c.id_typeconsult="",c.autretypeconsult=:autretc,c.insupercent=:addtc,c.prixtypeconsult=0,c.prixautretypeconsult=:prixtc WHERE c.id_factureConsult=:id_Bill');
					
					$updatepercent->execute(array(
					'medecin'=>$medecin,
					'dateConsult'=>$dateConsult,
					'autretc'=>$autretc,
					'addtc'=>$addtc,
					'prixtc'=>$prixtc,
					'id_Bill'=>$id_Bill,
					'discountpercentConsu'=>$discountpercentConsu
					
					))or die( print_r($connexion->errorInfo()));
					
				}
			}

		}
		
		
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_factureConsult=:consuId AND p.numero=:num AND p.numero=c.numero AND c.numero=:num AND c.id_factureConsult IS NOT NULL ORDER BY c.id_consu');
		$resultConsult->execute(array(
		'consuId'=>$_GET['idbill'],
		'num'=>$numPa
		));

		$resultConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptConsult=$resultConsult->rowCount();
		
		$TotalConsult = 0;
		$TotalConsultCCO = 0;

		
	
		/*-------Requête pour AFFICHER Med_consult-----------*/
	
		
		if(isset($_POST['idpresta']))
		{
			
			$idprestamc = array();
			$prixmc = array();
			$add = array();
			$idmc = array();
			$autremc = array();
			$discountpercentConsult = $_POST['discountpercentConsult'];

			if ($discountpercentConsult == '') {
				$discountpercentConsult = NULL;
			}

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

			foreach($_POST['idassuServ'] as $valueassumc)
			{
                $idassuServ[] = $valueassumc;
			}
			
			for($i=0;$i<sizeof($add);$i++)
			{
			    $idassu=$idassuServ[$i];

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

                // echo $add[$i].'_'.$idmc[$i].'_('.$prixmc[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation='.$idprestamc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_consult mc SET mc.dateconsu=:dateConsult,mc.insupercentServ=:add,mc.discountpercentConsult=:discountpercentConsult,mc.prixprestationConsu=:prixmc,mc.prixautreConsu=0,mc.id_uM=:medecin WHERE mc.id_medconsu=:idmc');
						
						$updatepercent->execute(array(
						'dateConsult'=>$dateConsult,
						'add'=>$add[$i],
						'prixmc'=>$prixmc[$i],
						'idmc'=>$idmc[$i],
						'medecin'=>$medecin,
						'discountpercentConsult'=>$discountpercentConsult
						
						))or die( print_r($connexion->errorInfo()));
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_consult mc WHERE mc.id_medconsu='.$idmc[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_consult mc SET mc.dateconsu=:dateConsult,mc.insupercentServ=:add,mc.discountpercentConsult=:discountpercentConsult,mc.prixprestationConsu=0,mc.prixautreConsu=:prixmc,mc.id_uM=:medecin WHERE mc.id_medconsu=:idmc');
						
						$updatepercent->execute(array(
						'dateConsult'=>$dateConsult,
						'add'=>$add[$i],
						'prixmc'=>$prixmc[$i],
						'idmc'=>$idmc[$i],
						'medecin'=>$medecin,
						'discountpercentConsult'=>$discountpercentConsult
						))or die( print_r($connexion->errorInfo()));
						
					}
				}

			}
		}
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_factureMedConsu=:id_Bill AND mc.dateconsu!="0000-00-00" ORDER BY mc.id_medconsu');
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'id_Bill'=>$_GET['idbill']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
		$TotalMedConsultCCO = 0;

	
	
		/*-------Requête pour AFFICHER Med_surge-----------*/


        if(isset($_POST['idprestaSurge']))
        {
            $idprestams = array();
            $prixms = array();
            $addSurge = array();
            $idms = array();
            $autrems = array();
            $discountpercentSurge = $_POST['discountpercentSurge'];

            if ($discountpercentSurge == '') {
            	$discountpercentSurge = NULL;
            }

            foreach($_POST['idprestaSurge'] as $ms)
            {
                $idprestams[] = $ms;
            }

            foreach($_POST['prixprestaSurge'] as $valms)
            {
                $prixms[] = $valms;
            }

            foreach($_POST['percentSurge'] as $valeurSurge)
            {
                $addSurge[] = $valeurSurge;
            }

            foreach($_POST['idmedSurge'] as $valeurms)
            {
                $idms[] = $valeurms;
            }

            foreach($_POST['autreSurge'] as $autrevaluems)
            {
                $autrems[] = $autrevaluems;
            }

            foreach($_POST['idassuSurge'] as $valueassums)
            {
                $idassuSurge[] = $valueassums;
            }


            for($i=0;$i<sizeof($addSurge);$i++)
            {
                $idassu=$idassuSurge[$i];

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

                // echo $addSurge[$i].'_'.$idms[$i].'_('.$prixms[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation='.$idprestams[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    $updatepercent=$connexion->prepare('UPDATE med_surge ms SET ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge='.$prixms[$i].',ms.discountpercentSurge=:discountpercentSurge,ms.prixautrePrestaS=0,ms.id_factureMedSurge='.$_GET['idbill'].' WHERE ms.id_medsurge='.$idms[$i].'');
                    $updatepercent->execute(array('discountpercentSurge'=>$discountpercentSurge));

                }else{

                    $results=$connexion->query('SELECT *FROM med_surge ms WHERE ms.id_medsurge='.$idms[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->prepare('UPDATE med_surge ms SET ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge=0,ms.prixautrePrestaS='.$prixms[$i].',ms.discountpercentSurge=:discountpercentSurge,ms.id_factureMedSurge='.$_GET['idbill'].' WHERE ms.id_medsurge='.$idms[$i].'');
                        $updatepercent->execute(array('discountpercentSurge'=>$discountpercentSurge));

                    }
                }

            }
        }

        $resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_factureMedSurge=:id_Bill ORDER BY ms.id_medsurge');
        $resultMedSurge->execute(array(
        'num'=>$numPa,
        'id_Bill'=>$_GET['idbill']
        ));

        $resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

        $comptMedSurge=$resultMedSurge->rowCount();

        $TotalMedSurge = 0;
        $TotalMedSurgeCCO = 0;


        /*-------Requête pour AFFICHER Med_inf-----------*/


        if(isset($_POST['idprestaInf']))
        {
            $idprestami = array();
            $prixmi = array();
            $addInf = array();
            $idmi = array();
            $autremi = array();
            $discountpercentInf = $_POST['discountpercentInf'];

            if($discountpercentInf ==''){
            	$discountpercentInf = NULL;
            }

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

            foreach($_POST['idassuInf'] as $valueassumi)
            {
                $idassuInf[] = $valueassumi;
            }


            for($i=0;$i<sizeof($addInf);$i++)
            {
                $idassu=$idassuInf[$i];

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

                // echo $addInf[$i].'_'.$idmi[$i].'_('.$prixmi[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation='.$idprestami[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    $updatepercent=$connexion->prepare('UPDATE med_inf mi SET mi.insupercentInf='.$addInf[$i].',mi.prixprestation='.$prixmi[$i].',mi.prixautrePrestaM=0,mi.discountpercentInf=:discountpercentInf,mi.id_factureMedInf='.$_GET['idbill'].' WHERE mi.id_medinf='.$idmi[$i].'');
                    $updatepercent->execute(array('discountpercentInf'=>$discountpercentInf));

                }else{

                    $results=$connexion->query('SELECT *FROM med_inf mi WHERE mi.id_medinf='.$idmi[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->prepare('UPDATE med_inf mi SET mi.insupercentInf='.$addInf[$i].',mi.prixprestation=0,mi.prixautrePrestaM='.$prixmi[$i].',mi.discountpercentInf=:discountpercentInf,mi.id_factureMedInf='.$_GET['idbill'].' WHERE mi.id_medinf='.$idmi[$i].'');
                        $updatepercent->execute(array('discountpercentInf'=>$discountpercentInf));
                    }
                }

            }
        }

        $resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_factureMedInf=:id_Bill ORDER BY mi.id_medinf');
        $resultMedInf->execute(array(
            'num'=>$numPa,
            'id_Bill'=>$_GET['idbill']
        ));

        $resultMedInf->setFetchMode(PDO::FETCH_OBJ);

        $comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
		$TotalMedInfCCO = 0;

	
	
		/*-------Requête pour AFFICHER Med_labo-----------*/


        if(isset($_POST['idprestaLab']))
        {
            $idprestaml = array();
            $prixml = array();
            $addLab = array();
            $idml = array();
            $autreml = array();

            $discountpercentLab = $_POST['discountpercentLab'];

            if($discountpercentLab == ''){
            	$discountpercentLab = NULL;
            }

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

            foreach($_POST['idassuLab'] as $valueassuml)
            {
                $idassuLab[] = $valueassuml;
            }

            for($i=0;$i<sizeof($addLab);$i++)
            {
                $idassu=$idassuLab[$i];

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

                // echo $addLab[$i].'_'.$idml[$i].'_('.$prixml[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation='.$idprestaml[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    $updatepercent=$connexion->prepare('UPDATE med_labo ml SET ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa='.$prixml[$i].',ml.discountpercentLab=:discountpercentLab,ml.prixautreExamen=0,ml.id_factureMedLabo='.$_GET['idbill'].' WHERE ml.id_medlabo='.$idml[$i].'');
                    $updatepercent->execute(array('discountpercentLab'=>$discountpercentLab));

                }else{

                    $results=$connexion->query('SELECT *FROM med_labo ml WHERE ml.id_medlabo='.$idml[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->prepare('UPDATE med_labo ml SET ml.insupercentLab='.$addLab[$i].',ml.discountpercentLab=:discountpercentLab,ml.prixprestationExa=0,ml.prixautreExamen='.$prixml[$i].',ml.id_factureMedLabo='.$_GET['idbill'].' WHERE ml.id_medlabo='.$idml[$i].'');
                        $updatepercent->execute(array('discountpercentLab'=>$discountpercentLab));
                    }
                }
            }
        }

        $resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_factureMedLabo=:id_Bill ORDER BY ml.id_medlabo');
        $resultMedLabo->execute(array(
            'num'=>$numPa,
            'id_Bill'=>$_GET['idbill']
        ));

        $resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

        $comptMedLabo=$resultMedLabo->rowCount();
		
		$TotalMedLabo = 0;
		$TotalMedLaboCCO = 0;

	
	
	
		/*-------Requête pour AFFICHER Med_radio-----------*/


        if(isset($_POST['idprestaRad']))
        {
            $idprestamr = array();
            $prixmr = array();
            $addRad = array();
            $idmr = array();
            $autremr = array();
            $discountpercentRadio = $_POST['discountpercentRadio'];
            echo $discountpercentRadio;
            if($discountpercentRadio == ''){
            	$discountpercentRadio = NULL;
            }

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

            foreach($_POST['idassuRad'] as $valueassumr)
            {
                $idassuRad[] = $valueassumr;
            }
           // print_r($addRad);

            for($i=0;$i<sizeof($addRad);$i++)
            {
                $idassu=$idassuRad[$i];

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

                // echo $addRad[$i].'_'.$idmr[$i].'_('.$prixmr[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation='.$idprestamr[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    $updatepercent=$connexion->query('UPDATE med_radio mr SET discountpercentRadio='.$discountpercentRadio.',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio='.$prixmr[$i].',mr.prixautreRadio=0,mr.id_factureMedRadio='.$_GET['idbill'].' WHERE mr.id_medradio='.$idmr[$i].'');
                }else{

                    $results=$connexion->query('SELECT *FROM med_radio mr WHERE mr.id_medradio='.$idmr[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->query('UPDATE med_radio mr SET discountpercentRadio='.$discountpercentRadio.',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio=0,mr.prixautreRadio='.$prixmr[$i].',mr.id_factureMedRadio='.$_GET['idbill'].' WHERE mr.id_medradio='.$idmr[$i].'');
                    }
                }
            }
        }

        $resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio');
        $resultMedRadio->execute(array(
        'num'=>$numPa,
        'idbill'=>$_GET['idbill']
        ));

        $resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

        $comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
		$TotalMedRadioCCO = 0;

	
	
		/*-------Requête pour AFFICHER Med_kine-----------*/


        if(isset($_POST['idprestaKine']))
        {
            $idprestamk = array();
            $prixmk = array();
            $addKine = array();
            $idmk = array();
            $autremk = array();
            $discountpercentkine = $_POST['discountpercentkine'];

            if($discountpercentkine == ''){
            	$discountpercentkine=NULL;
            }

            foreach($_POST['idprestaKine'] as $mk)
            {
                $idprestamk[] = $mk;
            }

            foreach($_POST['prixprestaKine'] as $valmk)
            {
                $prixmk[] = $valmk;
            }

            foreach($_POST['percentKine'] as $valeurKine)
            {
                $addKine[] = $valeurKine;
            }

            foreach($_POST['idmedKine'] as $valeurmk)
            {
                $idmk[] = $valeurmk;
            }

            foreach($_POST['autreKine'] as $autrevaluemk)
            {
                $autremk[] = $autrevaluemk;
            }

            foreach($_POST['idassuKine'] as $valueassumk)
            {
                $idassuKine[] = $valueassumk;
            }


            for($i=0;$i<sizeof($addKine);$i++)
            {
                $idassu=$idassuKine[$i];

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

                // echo $addKine[$i].'_'.$idmk[$i].'_('.$prixmk[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation='.$idprestamk[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    $updatepercent=$connexion->prepare('UPDATE med_kine mk SET mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine='.$prixmk[$i].',mk.discountpercentkine=:discountpercentkine,mk.prixautrePrestaK=0,mk.id_factureMedKine='.$_GET['idbill'].' WHERE mk.id_medkine='.$idmk[$i].'');
                    $updatepercent->execute(array('discountpercentkine'=>$discountpercentkine));
                }else{

                    $results=$connexion->query('SELECT *FROM med_kine mk WHERE mk.id_medkine='.$idmk[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->prepare('UPDATE med_kine mk SET mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine=0,mk.prixautrePrestaK='.$prixmk[$i].',mk.discountpercentkine=:discountpercentkine,mk.id_factureMedKine='.$_GET['idbill'].' WHERE mk.id_medkine='.$idmk[$i].'');
                        $updatepercent->execute(array('discountpercentkine'=>$discountpercentkine));
                    }
                }

            }
        }

        $resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_factureMedKine=:idbill AND (mk.id_factureMedKine!=0 OR mk.id_factureMedKine IS NOT NULL) ORDER BY mk.id_medkine');
        $resultMedKine->execute(array(
        'num'=>$numPa,
        'idbill'=>$_GET['idbill']
        ));

        $resultMedKine->setFetchMode(PDO::FETCH_OBJ);

        $comptMedKine=$resultMedKine->rowCount();

        $TotalMedKine = 0;
        $TotalMedKineCCO = 0;



		/*-------Requête pour AFFICHER Med_ortho-----------*/


        if(isset($_POST['idprestaOrtho']))
        {
            $idprestamo = array();
            $prixmo = array();
            $addOrtho = array();
            $idmo = array();
            $autremo = array();
            $discountpercentOrtho = $_POST['discountpercentOrtho'];
            if ($discountpercentOrtho == '') {
            	$discountpercentOrtho = NULL;
            }
            foreach($_POST['idprestaOrtho'] as $mo)
            {
                $idprestamo[] = $mo;
            }

            foreach($_POST['prixprestaOrtho'] as $valmo)
            {
                $prixmo[] = $valmo;
            }

            foreach($_POST['percentOrtho'] as $valeurOrtho)
            {
                $addOrtho[] = $valeurOrtho;
            }

            foreach($_POST['idmedOrtho'] as $valeurmo)
            {
                $idmo[] = $valeurmo;
            }

            foreach($_POST['autreOrtho'] as $autrevaluemo)
            {
                $autremo[] = $autrevaluemo;
            }

            foreach($_POST['idassuOrtho'] as $valueassumo)
            {
                $idassuOrtho[] = $valueassumo;
            }


            for($i=0;$i<sizeof($addOrtho);$i++)
            {
                $idassu=$idassuOrtho[$i];

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

                // echo $addOrtho[$i].'_'.$idmo[$i].'_('.$prixmo[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation='.$idprestamo[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    $updatepercent=$connexion->prepare('UPDATE med_ortho mo SET mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho='.$prixmo[$i].',mo.discountpercentOrtho=:discountpercentOrtho,mo.prixautrePrestaO=0,mo.id_factureMedOrtho='.$_GET['idbill'].' WHERE mo.id_medortho='.$idmo[$i].'');
                    $updatepercent->execute(array('discountpercentOrtho'=>$discountpercentOrtho));
                }else{

                    $results=$connexion->query('SELECT *FROM med_ortho mo WHERE mo.id_medortho='.$idmo[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->prepare('UPDATE med_ortho mo SET mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho=0,mo.prixautrePrestaO='.$prixmo[$i].',mo.discountpercentOrtho=:discountpercentOrtho,mo.id_factureMedOrtho='.$_GET['idbill'].' WHERE mo.id_medortho='.$idmo[$i].'');
                        $updatepercent->execute(array('discountpercentOrtho'=>$discountpercentOrtho));
                    }
                }

            }
        }

        $resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_factureMedOrtho=:idbill ORDER BY mo.id_medortho');
        $resultMedOrtho->execute(array(
        'num'=>$numPa,
        'idbill'=>$_GET['idbill']
        ));

        $resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

        $comptMedOrtho=$resultMedOrtho->rowCount();

        $TotalMedOrtho = 0;
        $TotalMedOrthoCCO = 0;



        /*-------Requête pour AFFICHER Med_consom-----------*/


        if(isset($_POST['idprestaConsom']))
        {
            $idprestaconsom = array();
            $prixmco = array();
            $addConsom = array();
            $idmco = array();
            $autreconsom = array();
            $qteConsom = array();

            $discountpercentConsom = $_POST['discountpercentConsom'];
            if ($discountpercentConsom == '') {
            	$discountpercentConsom = NULL;
            }

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

            foreach($_POST['idassuConsom'] as $valueassuconsom)
            {
                $idassuConsom[] = $valueassuconsom;
            }

            for($i=0;$i<sizeof($addConsom);$i++)
            {
                $idassu=$idassuConsom[$i];

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

                // echo $addConsom[$i].'_'.$idmco[$i].'_('.$prixmco[$i].' : '.$qteConsom[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation='.$idprestaconsom[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    $updatepercent=$connexion->prepare('UPDATE med_consom mco SET mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom='.$prixmco[$i].',mco.discountpercentConsom=:discountpercentConsom,mco.prixautreConsom=0,mco.qteConsom='.$qteConsom[$i].',mco.id_factureMedConsom='.$_GET['idbill'].' WHERE mco.id_medconsom='.$idmco[$i].'');
                    $updatepercent->execute(array('discountpercentConsom'=>$discountpercentConsom));
                }else{

                    $results=$connexion->query('SELECT *FROM med_consom mco WHERE mco.id_medconsom='.$idmco[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->prepare('UPDATE med_consom mco SET mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom=0,mco.prixautreConsom='.$prixmco[$i].',mco.discountpercentConsom=:discountpercentConsom,mco.qteConsom='.$qteConsom[$i].',mco.id_factureMedConsom='.$_GET['idbill'].' WHERE mco.id_medconsom='.$idmco[$i].'');
                        $updatepercent->execute(array('discountpercentConsom'=>$discountpercentConsom));
                    }
                }
            }
        }

        $resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom');
        $resultMedConsom->execute(array(
            'num'=>$numPa,
            'idbill'=>$_GET['idbill']
        ));

        $resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

        $comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		$TotalMedConsomCCO = 0;

	
	
		/*-------Requête pour AFFICHER Med_medoc-----------*/


        if(isset($_POST['idprestaMedoc']))
        {
            $idprestamedoc = array();
            $prixmdo = array();
            $addMedoc = array();
            $idmdo = array();
            $autremedoc = array();
            $qteMedoc = array();
            $discountpercentMedoc = $_POST['discountpercentMedoc'];
            if ($discountpercentMedoc == '') {
            	$discountpercentMedoc = NULL;
            }

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

            foreach($_POST['idassuMedoc'] as $valueassumedoc)
            {
                $idassuMedoc[] = $valueassumedoc;
            }

            for($i=0;$i<sizeof($addMedoc);$i++)
            {
                $idassu=$idassuMedoc[$i];

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

                // echo $addMedoc[$i].'_'.$idmdo[$i].'_('.$prixmdo[$i].' : '.$qteMedoc[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation='.$idprestamedoc[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    $updatepercent=$connexion->prepare('UPDATE med_medoc mdo SET mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc='.$prixmdo[$i].',mdo.discountpercentMedoc=:discountpercentMedoc,mdo.prixautreMedoc=0,mdo.qteMedoc='.$qteMedoc[$i].',mdo.id_factureMedMedoc='.$_GET['idbill'].' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
                    $updatepercent->execute(array('discountpercentMedoc'=>$discountpercentMedoc));
                }else{

                    $results=$connexion->query('SELECT *FROM med_medoc mdo WHERE mdo.id_medmedoc='.$idmdo[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->prepare('UPDATE med_medoc mdo SET mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc=0,mdo.prixautreMedoc='.$prixmdo[$i].',mdo.discountpercentMedoc=:discountpercentMedoc,mdo.qteMedoc='.$qteMedoc[$i].',mdo.id_factureMedMedoc='.$_GET['idbill'].' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
                        $updatepercent->execute(array('discountpercentMedoc'=>$discountpercentMedoc));

                    }
                }
            }
        }

        $resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc');
        $resultMedMedoc->execute(array(
        'num'=>$numPa,
        'idbill'=>$_GET['idbill']
        ));

        $resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

        $comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
		$TotalMedMedocCCO = 0;

	?>
	
	<table style="width:100%; margin-bottom:-5px">
		<tr>
			<td style="text-align:left; width:10%;">
				<h4>
				<?php
				echo date('d-M-Y', strtotime($dateBill));
				?>
				</h4>
			</td>
			
			<td style="text-align:left; width:30%;">
				<h2 style="font-size:150%; font-weight:600;">Bill n° <?php echo $numbill;?> <?php if($dette!=NULL){ echo '<span style="font-size:150%; font-weight:600;color:red;" class="buttonBill">Indebted</span>';}?></h2>
			</td>
			
			<td style="text-align:right;width:10%;">
			
				<form method="post" action="printBill_modifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}else{ echo '&dateconsu='.$dateConsult;}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idmed'])){ echo '&idmed='.$medecin;}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}else{ echo '&idtypeconsu='.$typeconsu;}?><?php echo '&idassu='.$assurance;?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}else{ echo '&billpercent='.$percentIdbill;}?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}?>&updatebill=ok&createBill=<?php echo $createBill;?>&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
				</form>
			</td>
		
			<td style="text-align:right;width:25%;" class="buttonBill">
				
				<a href="formModifierBill.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?>&finishbtn=ok<?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="updatebtn" style="margin:5px;">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo 'Modifier';?></button>
				</a>
				
			</td>
			
			<td class="buttonBill" style="text-align:right;width:25%;">
				<a href="categoriesbill_modifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}else{ echo '&dateconsu='.$dateConsult;}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?>&finishbtn=ok<?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($medecin)){ echo '&idmed='.$medecin;}?><?php echo '&idassu='.$assurance;?><?php if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}else{ echo '&nomassurance='.$nomassurance;}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}else{ echo '&billpercent='.$percentIdbill;}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}else{ echo '&idtypeconsu='.$typeconsu;}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="addmorebtn" style="margin:5px;">
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
						<th>Type of Consultation</th>
						<th style="width:10%;">Balance HMC</th>
						<th style="width:10%;">Balance '.$nomassurance.'</th>
						<th style="width:10%;">Top Up</th>
						<th style="width:10%;">Percent</th>
						<th style="width:10%;">Patient ('.$percentIdbill.'%)</th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>


				<tbody>';

                $TotaltopupPrice=0;
                $TotalpatientPrice=0;
                $TotalpatientBalance=0;
                $TotaluapPrice=0;

                while($ligneConsult=$resultConsult->fetch())
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
                            if(isset($_POST['pourcentage']))
                            {
                                $resultats=$connexion->prepare('UPDATE consultations SET insupercent=:percent WHERE id_consu=:idConsult');

                                $resultats->execute(array(
                                    'percent'=>$_POST['pourcentage'],
                                    'idConsult'=>$_GET['idconsu']

                                ))or die( print_r($connexion->errorInfo()));
                            }

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

                            $prixPresta = $ligneConsult->prixtypeconsult;
                            $prixPrestaCCO = $ligneConsult->prixtypeconsultCCO;

                            $typeconsult .= '<td style="font-weight:700">'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="font-weight:700">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="font-weight:700">';

                            $topupPrice = $prixPrestaCCO - $prixPresta;

                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            $typeconsult .= $topupPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
						
							<td>'.$ligneConsult->insupercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';


                            $TotalConsult=$TotalConsult + $prixPresta;
                            $TotalConsultCCO=$TotalConsultCCO + $prixPrestaCCO;


                            $typeconsult .= '<td style="font-weight:700">';
                            $patientPrice=($prixPresta * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            $typeconsult .= $patientPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-weight:700">';

                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            $typeconsult .= $patientBalance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-weight:700">';

                            $uapPrice= $prixPresta - $patientPrice;
                            $TotaluapPrice = $TotaluapPrice + $uapPrice;

                            $typeconsult .= $uapPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>';


                           /* if($ligneConsult->id_factureConsult!=NULL)
                            {
                                $typeconsult .= '<td style="font-weight:700">Consultation payed</td>';
                            }*/

                            $typeconsult .= '</tr>';

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

                        if($ligneNewPresta=$resultNewPresta->fetch())
                        {
                            $nameprestaConsult=$ligneNewPresta->autretypeconsult;

                            $typeconsult .= $ligneNewPresta->autretypeconsult.'</td>';

                            $prixPresta = $ligneNewPresta->prixautretypeconsult;
                            $prixPrestaCCO = $ligneNewPresta->prixautretypeconsultCCO;

                            $typeconsult .= '<td style="font-weight:700">'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>
			
							<td style="font-weight:700">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>
			
							<td style="font-weight:700">';

                            $topupPrice = $prixPrestaCCO - $prixPresta;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            $typeconsult .= $topupPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
						
							<td>'.$ligneConsult->insupercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';


                            $TotalConsult=$TotalConsult + $prixPresta;
                            $TotalConsultCCO=$TotalConsultCCO + $prixPrestaCCO;


                            $typeconsult .= '<td style="font-weight:700">';

                            $patientPrice=($prixPresta * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            $typeconsult .= $patientPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-weight:700">';

                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            $typeconsult .= $patientBalance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-weight:700">';

                            $uapPrice= $prixPresta - $patientPrice;
                            $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            $typeconsult .= $uapPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
			</td>';

                           /* if($ligneConsult->id_factureConsult!=NULL)
                            {
                                $typeconsult .= '<td style="font-weight:700">Consultation payed</td>';
                            }*/

                            $typeconsult .= '</tr>';

                        }
                    }

                    $arrayConsult[$i][0]=$nameprestaConsult;
                    $arrayConsult[$i][1]=$prixPresta;
                    $arrayConsult[$i][2]=$patientPrice;
                    $arrayConsult[$i][3]=$uapPrice;

                    $i++;

                    $objPHPExcel->setActiveSheetIndex(0)
                        ->fromArray($arrayConsult,'','B9');

                }

                /* $typeconsult .= '<tr style="text-align:center;">
                                <td></td>
                                <td style="font-size: 110%; font-weight: bold;">'.$TotalConsult;
                 */
                $TotalGnlPrice=$TotalGnlPrice + $TotalConsult;
                $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalConsultCCO;


                $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;

                /*
                    $typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>
                                <td></td>
                                <td style="font-size: 110%; font-weight: bold;">'.$TotalpatientPrice;
                     */
                $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;


                $TotalGnlPatientBalance=$TotalGnlPatientBalance + $TotalpatientBalance;


                /*
                    $typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>
                                <td style="font-size: 110%; font-weight: bold;">'.$TotaluapPrice;
                     */
                $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;


                /*
                    $typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>
                            </tr>';
                 */

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
                        <th>Services</th>
                        <th style="width:10%;">Balance HMC</th>
                        <th style="width:10%;">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Top Up</th>
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php


                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
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
                            $prixPrestaCCO = $ligneMedConsult->prixprestationConsuCCO;

                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


                            $TotalMedConsult=$TotalMedConsult + $prixPresta;
                            $TotalMedConsultCCO=$TotalMedConsultCCO + $prixPrestaCCO;
                            ?>

                        <td>
                            <?php
                            $topupPrice = $prixPrestaCCO - $prixPresta;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedConsult->insupercentServ;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($prixPresta * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $uapPrice= $prixPresta - $patientPrice;
                            $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            echo $uapPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <?php
                        }

                        if($ligneMedConsult->id_prestationConsu==NULL AND ($ligneMedConsult->prixautreConsu!=0 OR $ligneMedConsult->prixautreConsuCCO!=0))
                        {
                            $nameprestaMedConsult=$ligneMedConsult->autreConsu;
                            echo $ligneMedConsult->autreConsu.'</td>';

                            $prixPresta = $ligneMedConsult->prixautreConsu;
                            $prixPrestaCCO = $ligneMedConsult->prixautreConsuCCO;

                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedConsult=$TotalMedConsult + $prixPresta;
                            $TotalMedConsultCCO=$TotalMedConsultCCO + $prixPrestaCCO;
                            ?>

                            <td>
                                <?php
                                $topupPrice = $prixPrestaCCO - $prixPresta;
                                $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                                echo $topupPrice;
                                ?>
                            </td><span style="font-size:70%; font-weight:normal;">Rwf</span>

                            <td><?php echo $ligneMedConsult->insupercentServ;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;

                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $prixPresta - $patientPrice;
                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $uapPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>

                            </td>
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
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedConsultCCO.'';

                            $TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedConsultCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedConsult.'';

                            $TotalGnlPrice = $TotalGnlPrice + $TotalMedConsult;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            $TotalGnlTopupPrice = $TotalGnlPriceCCO - $TotalGnlPrice;
                            echo $TotalGnlTopupPrice;

                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
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
                        <th>Surgery</th>
                        <th style="width:10%;">Balance HMC</th>
                        <th style="width:10%;">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Top Up</th>
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
                    $TotaluapPrice=0;

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

                    ?>
                    <tr style="text-align:center;">
                        <td>
                            <?php

                            $resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
                            $resultPresta->execute(array(
                                'prestaId'=>$ligneMedSurge->id_prestationSurge
                            ));

                            $resultPresta->setFetchMode(PDO::FETCH_OBJ);

                            $comptPresta=$resultPresta->rowCount();

                            if($lignePresta=$resultPresta->fetch())
                            {
                            if($lignePresta->namepresta!='')
                            {
                                $nameprestaMedSurge=$lignePresta->namepresta;
                                echo $lignePresta->namepresta.'</td>';

                            }else{

                                $nameprestaMedSurge=$lignePresta->nompresta;
                                echo $lignePresta->nompresta.'</td>';
                            }

                            $prixPresta = $ligneMedSurge->prixprestationSurge;
                            $prixPrestaCCO = $ligneMedSurge->prixprestationSurgeCCO;

                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedSurge = $TotalMedSurge + $prixPresta;
                            $TotalMedSurgeCCO = $TotalMedSurgeCCO + $prixPrestaCCO;
                            ?>
                        </td>

                        <td>
                            <?php
                            $topupPrice = $prixPrestaCCO - $prixPresta;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($ligneMedSurge->prixprestationSurge * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $uapPrice= $ligneMedSurge->prixprestationSurge - $patientPrice;
                            $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            echo $uapPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <?php
                        }

                        if(($ligneMedSurge->id_prestationSurge=="" OR $ligneMedSurge->id_prestationSurge==0) AND ($ligneMedSurge->prixautrePrestaS!=0 OR $ligneMedSurge->prixautrePrestaSCCO!=0))
                        {
                            $nameprestaMedSurge=$ligneMedSurge->autrePrestaS;
                            echo $ligneMedSurge->autrePrestaS.'</td>';


                            $prixPresta = $ligneMedSurge->prixautrePrestaS;
                            $prixPrestaCCO = $ligneMedSurge->prixautrePrestaSCCO;
                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


                            $TotalMedSurge = $TotalMedSurge + $prixPresta;
                            $TotalMedSurgeCCO = $TotalMedSurgeCCO + $prixPrestaCCO;
                            ?>

                            <td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $prixPresta - $patientPrice;
                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $uapPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
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
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedSurgeCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedSurgeCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedSurge.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedSurge;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedSurge.'')
                    ->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
                    ->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');

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
                        <th><?php echo 'Physiotherapy';?></th>
                        <th style="width:10%;">Balance HMC</th>
                        <th style="width:10%;">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Top Up</th>
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
                    $TotaluapPrice=0;

                    while($ligneMedKine=$resultMedKine->fetch())
                    {

                    $billpercent=$ligneMedKine->insupercentKine;

                    $idassu=$ligneMedKine->id_assuKine;
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
                                'prestaId'=>$ligneMedKine->id_prestationKine
                            ));

                            $resultPresta->setFetchMode(PDO::FETCH_OBJ);

                            $comptPresta=$resultPresta->rowCount();

                            if($lignePresta=$resultPresta->fetch())
                            {
                            if($lignePresta->namepresta!='')
                            {
                                $nameprestaMedKine=$lignePresta->namepresta;
                                echo $lignePresta->namepresta.'</td>';

                            }else{

                                $nameprestaMedKine=$lignePresta->nompresta;
                                echo $lignePresta->nompresta.'</td>';
                            }

                            $prixPresta = $ligneMedKine->prixprestationKine;
                            $prixPrestaCCO = $ligneMedKine->prixprestationKineCCO;

                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedKine = $TotalMedKine + $prixPresta;
                            $TotalMedKineCCO = $TotalMedKineCCO + $prixPrestaCCO;
                            ?>
                        </td>

                        <td>
                            <?php
                            $topupPrice = $prixPrestaCCO - $prixPresta;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedKine->insupercentKine;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($ligneMedKine->prixprestationKine * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $uapPrice= $ligneMedKine->prixprestationKine - $patientPrice;
                            $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            echo $uapPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <?php
                        }

                        if(($ligneMedKine->id_prestationKine=="" OR $ligneMedKine->id_prestationKine==0) AND ($ligneMedKine->prixautrePrestaK!=0 OR $ligneMedKine->prixautrePrestaKCCO!=0))
                        {
                            $nameprestaMedKine=$ligneMedKine->autrePrestaO;
                            echo $ligneMedKine->autrePrestaO.'</td>';


                            $prixPresta = $ligneMedKine->prixautrePrestaK;
                            $prixPrestaCCO = $ligneMedKine->prixautrePrestaKCCO;
                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


                            $TotalMedKine = $TotalMedKine + $prixPresta;
                            $TotalMedKineCCO = $TotalMedKineCCO + $prixPrestaCCO;
                            ?>

                            <td><?php echo $ligneMedKine->insupercentKine;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $prixPresta - $patientPrice;
                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $uapPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
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
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedKineCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedKineCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedKine.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedKine;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedKine.'')
                    ->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
                    ->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');

            }


            if($comptMedOrtho != 0)
            {

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.(14+$i+$x).'', 'P&O')
                    ->setCellValue('C'.(14+$i+$x).'', 'Price')
                    ->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
                    ->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');

                ?>

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th><?php echo 'P&O';?></th>
                        <th style="width:10%;">Balance HMC</th>
                        <th style="width:10%;">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Top Up</th>
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
                    $TotaluapPrice=0;

                    while($ligneMedOrtho=$resultMedOrtho->fetch())
                    {

                    $billpercent=$ligneMedOrtho->insupercentOrtho;

                    $idassu=$ligneMedOrtho->id_assuOrtho;
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
                                'prestaId'=>$ligneMedOrtho->id_prestationOrtho
                            ));

                            $resultPresta->setFetchMode(PDO::FETCH_OBJ);

                            $comptPresta=$resultPresta->rowCount();

                            if($lignePresta=$resultPresta->fetch())
                            {
                            if($lignePresta->namepresta!='')
                            {
                                $nameprestaMedOrtho=$lignePresta->namepresta;
                                echo $lignePresta->namepresta.'</td>';

                            }else{

                                $nameprestaMedOrtho=$lignePresta->nompresta;
                                echo $lignePresta->nompresta.'</td>';
                            }

                            $prixPresta = $ligneMedOrtho->prixprestationOrtho;
                            $prixPrestaCCO = $ligneMedOrtho->prixprestationOrthoCCO;

                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedOrtho = $TotalMedOrtho + $prixPresta;
                            $TotalMedOrthoCCO = $TotalMedOrthoCCO + $prixPrestaCCO;
                            ?>
                        </td>

                        <td>
                            <?php
                            $topupPrice = $prixPrestaCCO - $prixPresta;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($ligneMedOrtho->prixprestationOrtho * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $uapPrice= $ligneMedOrtho->prixprestationOrtho - $patientPrice;
                            $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            echo $uapPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <?php
                        }

                        if(($ligneMedOrtho->id_prestationOrtho=="" OR $ligneMedOrtho->id_prestationOrtho==0) AND ($ligneMedOrtho->prixautrePrestaO!=0 OR $ligneMedOrtho->prixautrePrestaOCCO!=0))
                        {
                            $nameprestaMedOrtho=$ligneMedOrtho->autrePrestaO;
                            echo $ligneMedOrtho->autrePrestaO.'</td>';


                            $prixPresta = $ligneMedOrtho->prixautrePrestaO;
                            $prixPrestaCCO = $ligneMedOrtho->prixautrePrestaOCCO;
                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


                            $TotalMedOrtho = $TotalMedOrtho + $prixPresta;
                            $TotalMedOrthoCCO = $TotalMedOrthoCCO + $prixPrestaCCO;
                            ?>

                            <td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $prixPresta - $patientPrice;
                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $uapPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
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
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedOrthoCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedOrthoCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedOrtho.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedOrtho;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedOrtho.'')
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
                        <th>Nursing Care</th>
                        <th style="width:10%;">Balance HMC</th>
                        <th style="width:10%;">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Top Up</th>
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
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
                            $prixPrestaCCO = $ligneMedInf->prixprestationCCO;

                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedInf = $TotalMedInf + $prixPresta;
                            $TotalMedInfCCO = $TotalMedInfCCO + $prixPrestaCCO;
                            ?>
                        </td>

                        <td>
                            <?php
                            $topupPrice = $prixPrestaCCO - $prixPresta;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedInf->insupercentInf;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($ligneMedInf->prixprestation * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $uapPrice= $ligneMedInf->prixprestation - $patientPrice;
                            $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            echo $uapPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <?php
                        }

                        if(($ligneMedInf->id_prestation=="" OR $ligneMedInf->id_prestation==0) AND ($ligneMedInf->prixautrePrestaM!=0 OR $ligneMedInf->prixautrePrestaMCCO!=0))
                        {
                            $nameprestaMedInf=$ligneMedInf->autrePrestaM;
                            echo $ligneMedInf->autrePrestaM.'</td>';


                            $prixPresta = $ligneMedInf->prixautrePrestaM;
                            $prixPrestaCCO = $ligneMedInf->prixautrePrestaMCCO;
                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


                            $TotalMedInf = $TotalMedInf + $prixPresta;
                            $TotalMedInfCCO = $TotalMedInfCCO + $prixPrestaCCO;
                            ?>

                            <td><?php echo $ligneMedInf->insupercentInf;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $prixPresta - $patientPrice;
                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $uapPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
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
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedInfCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedInfCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedInf.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
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

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th>Labs</th>
                        <th style="width:10%;">Balance HMC</th>
                        <th style="width:10%;">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Top Up</th>
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
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
                            $prixPrestaCCO = $ligneMedLabo->prixprestationExaCCO;

                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedLabo = $TotalMedLabo + $prixPresta;
                            $TotalMedLaboCCO = $TotalMedLaboCCO + $prixPrestaCCO;
                            ?>
                            </td>

                            <td>
                                <?php
                                $topupPrice = $prixPrestaCCO - $prixPresta;
                                $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                                echo $topupPrice;
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td><?php echo $ligneMedLabo->insupercentLab;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($ligneMedLabo->prixprestationExa * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $ligneMedLabo->prixprestationExa - $patientPrice;
                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $ligneMedLabo->prixprestationExa - $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
                            </tr>
                            <?php
                        }

                        if($ligneMedLabo->id_prestationExa=="" AND ($ligneMedLabo->prixautreExamen!=0 OR $ligneMedLabo->prixautreExamenCCO!=0))
                        {
                            $nameprestaMedLabo=$ligneMedLabo->autreExamen;
                            echo $ligneMedLabo->autreExamen.'</td>';

                            $prixPresta = $ligneMedLabo->prixautreExamen;
                            $prixPrestaCCO = $ligneMedLabo->prixautreExamenCCO;
                            echo '<td>'.$ligneMedLabo->prixautreExamenCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$ligneMedLabo->prixautreExamen.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedLabo=$TotalMedLabo + $ligneMedLabo->prixautreExamen;
                            $TotalMedLaboCCO=$TotalMedLaboCCO + $ligneMedLabo->prixautreExamenCCO;
                            ?>

                            <td><?php echo $ligneMedLabo->insupercentLab;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($ligneMedLabo->prixautreExamen * $billpercent)/100;

                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $ligneMedLabo->prixautreExamen - $patientPrice;

                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $uapPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
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
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedLaboCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedLaboCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedLabo.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
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

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th>Radiology</th>
                        <th style="width:10%;">Balance HMC</th>
                        <th style="width:10%;">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Top Up</th>
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
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
                            $prixPrestaCCO = $ligneMedRadio->prixprestationRadioCCO;

                            echo '<td>'.$prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedRadio = $TotalMedRadio + $prixPresta;
                            $TotalMedRadioCCO = $TotalMedRadioCCO + $prixPrestaCCO;
                            ?>
                            </td>

                            <td>
                                <?php
                                $topupPrice = $prixPrestaCCO - $prixPresta;
                                $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                                echo $topupPrice;
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td><?php echo $ligneMedRadio->insupercentRad;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $prixPresta - $patientPrice;
                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $uapPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
                            </tr>
                            <?php
                        }

                        if($ligneMedRadio->id_prestationRadio=="" AND ($ligneMedRadio->prixautreRadio!=0 OR $ligneMedRadio->prixautreRadioCCO!=0))
                        {
                            $nameprestaMedRadio=$ligneMedRadio->autreRadio;
                            echo $ligneMedRadio->autreRadio.'</td>';

                            $prixPresta = $ligneMedRadio->prixautreRadio;
                            $prixPrestaCCO = $ligneMedRadio->prixautreRadioCCO;
                            echo '<td>'.$ligneMedRadio->prixautreRadioCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            echo '<td>'.$ligneMedRadio->prixautreRadio.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedRadio=$TotalMedRadio + $ligneMedRadio->prixautreRadio;
                            $TotalMedRadioCCO=$TotalMedRadioCCO + $ligneMedRadio->prixautreRadioCCO;
                            ?>

                            <td>
                                <?php
                                $topupPrice = $prixPrestaCCO - $prixPresta;
                                $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                                echo $topupPrice;
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td><?php echo $ligneMedRadio->insupercentRad;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($ligneMedRadio->prixautreRadio * $billpercent)/100;

                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $ligneMedRadio->prixautreRadio - $patientPrice;

                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $ligneMedRadio->prixautreRadio - $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
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
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedRadioCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedRadioCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedRadio.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
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

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th>Consommables</th>
                        <th style="width:4%">Qty</th>
                        <th style="width:6%">P/U CCO</th>
                        <th style="width:8%">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Balance ra</th>
                        <th style="width:10%;">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Top Up</th>
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
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


                        $resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');

                        $resultPresta->execute(array(
                            'prestaId'=>$ligneMedConsom->id_prestationConsom
                        ));

                        $resultPresta->setFetchMode(PDO::FETCH_OBJ);

                        $comptPresta=$resultPresta->rowCount();

                        if($lignePresta=$resultPresta->fetch())
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

                                <td>
                                    <?php
                                    $qteConsom=$ligneMedConsom->qteConsom;
                                    echo $qteConsom;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPrestaCCO = $ligneMedConsom->prixprestationConsomCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedConsom->prixprestationConsom;
                                    echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteConsom;

                                    echo $balanceCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedConsomCCO=$TotalMedConsomCCO + $balanceCCO;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balance=$prixPresta*$qteConsom;

                                    echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedConsom=$TotalMedConsom + $balance;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $topupPrice = $balanceCCO - $balance;
                                    $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                                    echo $topupPrice;
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>

                                <td>
                                    <?php
                                    $patientPrice=($balance * $billpercent)/100;
                                    $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                    echo $patientPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                    echo $patientBalance.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $uapPrice= $balance - $patientPrice;
                                    $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    echo $uapPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                            </tr>
                            <?php
                        }

                        if($ligneMedConsom->id_prestationConsom==0 AND ($ligneMedConsom->prixautreConsom!=0 OR $ligneMedConsom->prixautreConsomCCO!=0))
                        {
                            ?>
                            <tr style="text-align:center;">
                                <td>
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
                                    $prixPrestaCCO = $ligneMedConsom->prixautreConsomCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedConsom->prixautreConsom;
                                    echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteConsom;

                                    echo $balanceCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedConsomCCO=$TotalMedConsomCCO + $balanceCCO;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balance=$prixPresta*$qteConsom;

                                    echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedConsom=$TotalMedConsom + $balance;
                                    ?>
                                </td>

                                <td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>

                                <td>
                                    <?php
                                    $patientPrice=($balance * $billpercent)/100;
                                    $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                    echo $patientPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $uapPrice= $balance - $patientPrice;
                                    $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                    echo $uapPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>
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
                        <td></td>
                        <td></td>
                        <td></td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedConsomCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedConsomCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedConsom.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
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
                        <th>Medicaments</th>
                        <th style="width:4%">Qty</th>
                        <th style="width:6%">P/U CCO</th>
                        <th style="width:8%">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Balance ra</th>
                        <th style="width:10%;">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;">Top Up</th>
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
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


                        $resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');

                        $resultPresta->execute(array(
                            'prestaId'=>$ligneMedMedoc->id_prestationMedoc
                        ));

                        $resultPresta->setFetchMode(PDO::FETCH_OBJ);

                        $comptPresta=$resultPresta->rowCount();

                        if($lignePresta=$resultPresta->fetch())
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
                                    $prixPrestaCCO = $ligneMedMedoc->prixprestationMedocCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedMedoc->prixprestationMedoc;
                                    echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteMedoc;

                                    echo $balanceCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedMedocCCO=$TotalMedMedocCCO + $balanceCCO;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balance=$prixPresta*$qteMedoc;

                                    echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedMedoc=$TotalMedMedoc + $balance;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $topupPrice = $balanceCCO - $balance;
                                    $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                                    echo $topupPrice;
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>

                                <td>
                                    <?php
                                    $patientPrice=($balance * $billpercent)/100;
                                    $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                    echo $patientPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                    echo $patientBalance.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $uapPrice= $balance - $patientPrice;
                                    $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    echo $uapPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                            </tr>

                            <?php
                        }

                        if($ligneMedMedoc->id_prestationMedoc==0 AND ($ligneMedMedoc->prixautreMedoc!=0 OR $ligneMedMedoc->prixautreMedocCCO!=0))
                        {
                            ?>
                            <tr style="text-align:center;">
                                <td>
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
                                    $prixPrestaCCO = $ligneMedMedoc->prixautreMedocCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedMedoc->prixautreMedoc;
                                    echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteMedoc;

                                    echo $balanceCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedMedocCCO=$TotalMedMedocCCO + $balanceCCO;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balance=$prixPresta*$qteMedoc;

                                    echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedMedoc=$TotalMedMedoc + $balance;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $topupPrice = $balanceCCO - $balance;
                                    $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                                    echo $TotaltopupPrice;
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>

                                <td>
                                    <?php
                                    $patientPrice=($balance * $billpercent)/100;
                                    $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                    echo $patientPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                    echo $patientBalance.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $uapPrice= $balance - $patientPrice;
                                    $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                    echo $uapPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>
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
                        <td></td>
                        <td></td>
                        <td></td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedMedocCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedMedocCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedMedoc.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
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

	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:10px; border-radius:3px; font-size:85%;">

        <table class="printPreview" cellspacing="0" style="margin:auto;">
            <thead>
            <tr>
                <th style="width:15%"></th>
                <th style="width:15%;">Total Balance HMC</th>
                <th style="width:15%;">Total balance <?php echo $nomassurance;?></th>
                <th style="width:15%;">Total Top Up</th>
                <th style="width:15%;">Patient <?php echo '('.$percentIdbill.'%)';?></th>
                <th style="width:15%;">Patient balance</th>
                <th style="width:15%;">Insurance</th>
            </tr>
            </thead>

            <tbody>
            <tr style="text-align:center;">
                <td style="font-size: 110%; font-weight: bold;">Final Balance</td>
                <td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlPriceCCO;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                <td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlPrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                <td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlTopupPrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                <td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlPatientPrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                <td style="font-size: 110%; font-weight: bold;">
					<?php
						$patientPayed = $TotalGnlPatientBalance - $dettes;
						echo $TotalGnlPatientBalance;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
				</td>
                <td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlInsurancePrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
            </tr>
            </tbody>
        </table>
		
		<?php
		
		if($idBillCount!=0)
		{
			?>
			<table class="printPreview" cellspacing="0" style="margin-top:5px;border:none;">
				<tr style="text-align:center;" class="buttonBill">

					<td style="font-size: 110%; font-weight: bold;border:none;"></td>

					<td style="font-size: 110%; font-weight: bold;text-align:center;border:2px solid #e8e8e8;width:10%;">
						<?php
						echo '<span>Payed : </span><span style="color:gray">'.$patientPayed.'</span>';?><span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>

					<td style="font-size: 110%; font-weight: bold;text-align:center; border:2px solid #e8e8e8;width:10%;">
						<?php
						echo '<span>Debt : </span><span style="color:gray">'.$dettes.'</span>';?><span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>
				</tr>
			</table>
			<?php
		}
		?>

	</div>
	
	<div class="account-container" style="margin:20px auto auto; width:90%; background:#fff; border-radius:3px; font-size:85%;">
	
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
		
		/*---------------------Update Bills---------------------*/
		
		if(isset($_POST['previewbtn']))
		{

			//echo "string=" .$_POST['Change_cashier'];
			if (isset($_POST['prixtypeconsult'])) {
				$prixtypeconsult22 = $_POST['prixtypeconsult'];
			}else{
				$prixtypeconsult22 = 0;
			}
			
			$updateIdBill=$connexion->prepare('UPDATE bills b SET b.datebill=:datebill, b.totaltypeconsuprice=:totaltypeconsu, b.totalgnlprice=:TotalGnlPrice, b.dateconsu=:dateconsu, b.numero=:num, b.idorgBill=:org, b.nomassurance=:nomassu, b.idcardbill=:idcardbill, b.numpolicebill=:numpolicebill, b.adherentbill=:adherentbill, b.billpercent=:percentIdbill, b.codecashier=:codecashier, b.codecoordi=:codecoordi, b.vouchernum=:vouchernum WHERE b.id_bill=:idbill');

			$updateIdBill->execute(array(
			'idbill'=>$idBilling,
			'datebill'=>$dateBill,
			'totaltypeconsu'=>$prixtypeconsult22,
			'TotalGnlPrice'=>$TotalGnlPrice,
			'dateconsu'=>$dateConsult,
			'num'=>$_GET['num'],
			'nomassu'=>$nomassurance,
			'org'=>$org,
			'idcardbill'=>$idcardbill,
			'numpolicebill'=>$numpolicebill,
			'adherentbill'=>$adherentbill,
			'percentIdbill'=>$percentIdbill,
			'codecashier'=>$_POST['Change_cashier'],
			'codecoordi'=>$_SESSION['codeC'],
			'vouchernum'=>$vouchernum
			
			))or die( print_r($connexion->errorInfo()));

			$selectBill_other =$connexion->prepare('SELECT * FROM bills_other_cashier WHERE id_bill_OG=:id_bill');
			$selectBill_other->execute(array(
				'id_bill'=>$idBilling
			));
			$selectBill_other->setFetchMode(PDO::FETCH_OBJ);
			$nbre = $selectBill_other->rowCount();

			if ($nbre != 0) {
				$deleteBill_other=$connexion->prepare('DELETE FROM bills_other_cashier WHERE id_bill_OG=:id_bill');
	
				$deleteBill_other->execute(array(
				'id_bill'=>$idBilling
				));
				$other_cashier = 0;
				$updateConsultation = $connexion->prepare("UPDATE bills SET other_cashier=:other_cashier WHERE id_bill=:id_bill");
				$updateConsultation->execute(array('other_cashier'=>$other_cashier,'id_bill'=>$idBilling));
			}



			$updateConsultation = $connexion->prepare("UPDATE consultations SET codecashier=:codecashier WHERE id_factureConsult=:id_factureConsult");
			$updateConsultation->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureConsult'=>$idBilling));

			$updateConsult = $connexion->prepare("UPDATE med_consult SET codecashier=:codecashier WHERE id_factureMedConsu=:id_factureMedConsu");
			$updateConsult->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedConsu'=>$idBilling));

			$updateConsom = $connexion->prepare("UPDATE med_consom SET codecashier=:codecashier WHERE id_factureMedConsom=:id_factureMedConsom");
			$updateConsom->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedConsom'=>$idBilling));

			$updateInf = $connexion->prepare("UPDATE med_inf SET codecashier=:codecashier WHERE id_factureMedInf=:id_factureMedInf");
			$updateInf->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedInf'=>$idBilling));

			$updateLabo = $connexion->prepare("UPDATE med_labo SET codecashier=:codecashier WHERE id_factureMedLabo=:id_factureMedLabo");
			$updateLabo->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedLabo'=>$idBilling));

			$updateMedoc = $connexion->prepare("UPDATE med_medoc SET codecashier=:codecashier WHERE id_factureMedMedoc=:id_factureMedMedoc");
			$updateMedoc->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedMedoc'=>$idBilling));
			
			$updatePysco = $connexion->prepare("UPDATE med_psy SET codecashier=:codecashier WHERE id_factureMedPsy=:id_factureMedPsy");
			$updatePysco->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedPsy'=>$idBilling));
			
			$updateRadio = $connexion->prepare("UPDATE med_radio SET codecashier=:codecashier WHERE id_factureMedRadio=:id_factureMedRadio");
			$updateRadio->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedRadio'=>$idBilling));
			
			$updateSurge = $connexion->prepare("UPDATE med_surge SET codecashier=:codecashier WHERE id_factureMedSurge=:id_factureMedSurge");
			$updateSurge->execute(array('codecashier'=>$_POST['Change_cashier'],'id_factureMedSurge'=>$idBilling));


			//echo "string= ".$idBilling;

			if(isset($_GET['idconsu'])){
				$billcate = 'clinic';
			}else{
				$billcate = 'hospi';
			}

			$metionEditedBill = $connexion->prepare('INSERT INTO `editedBillHisto` (`eidbill`, `whoedit`, `editedon`, `billcatego`) VALUES (:eidbill, :whoedit, :editedon, :billcatego);');
			$metionEditedBill->execute(array('eidbill'=>$idBilling,'whoedit'=>$_SESSION['id'],'editedon'=>$annee,'billcatego'=>$billcate));
			
			
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