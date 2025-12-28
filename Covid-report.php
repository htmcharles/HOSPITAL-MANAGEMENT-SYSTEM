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

$heure = date('H').' : '.date('i').' : '.date('s');


if(isset($_GET['createRN']))
{
	$createRN=$_GET['createRN'];
}

if(isset($_GET['paVisit']))
{
	$iVisit=$_GET['paVisit'];
	$sn = showRN('COVID-19');
}
		

?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo 'Covid-19 Report#'.$sn; ?></title>

	<link href="cssBourbonCoffee/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
	<!--<meta HTTP-EQUIV="Refresh" CONTENT="30; URL=http://www.tonSite.com/page.html"> --> 
	
		
			<!------------------------------------>
	
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	
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

		    .flashing {
      -webkit-animation: glowing 1000ms infinite;
      -moz-animation: glowing 1000ms infinite;
      -o-animation: glowing 1000ms infinite;
      animation: glowing 1000ms infinite;
    }
    @-webkit-keyframes glowing {
      0% {  -webkit-box-shadow: 0 0 3px #B20000;}
      50% {  -webkit-box-shadow: 0 0 40px #FF0000; }
      100% {  -webkit-box-shadow: 0 0 3px #B20000; }
    }

    @-moz-keyframes glowing {
      0% {  -moz-box-shadow: 0 0 3px #B20000; }
      50% {  -moz-box-shadow: 0 0 40px #FF0000; }
      100% {  -moz-box-shadow: 0 0 3px #B20000; }
    }

    @-o-keyframes glowing {
      0% {  box-shadow: 0 0 3px #B20000; }
      50% {  box-shadow: 0 0 40px #FF0000; }
      100% {  box-shadow: 0 0 3px #B20000; }
    }

    @keyframes glowing {
      0% {  box-shadow: 0 0 3px #B20000; }
      50% {  box-shadow: 0 0 40px #FF0000; }
      100% {  box-shadow: 0 0 3px #B20000; }
    }
	
	</style>
	
</head>


<body>

	<?php
	if(isset($_GET['createReportPdf']))
	{
	?>
		<body onload="window.print()">
	<?php
	}
	?>
	
<?php
$connected=$_SESSION['connect'];
$idCoordi=$_SESSION['id'];

if($connected==true AND isset($_SESSION['id']))
{
			
	$resultatsCoordi=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.id_u=:operation');
	$resultatsCoordi->execute(array(
	'operation'=>$idCoordi	
	));

	$resultatsCoordi->setFetchMode(PDO::FETCH_OBJ);
	if($ligneCoordi=$resultatsCoordi->fetch())
	{
		$doneby = $ligneCoordi->full_name;
		$codecoordi = $ligneCoordi->codecoordi;
	}else{

		$resultatsStock=$connexion->prepare('SELECT *FROM utilisateurs u, stockkeeper k WHERE u.id_u=k.id_u and k.id_u=:operation');
		$resultatsStock->execute(array(
		'operation'=>$idCoordi	
		));

		$resultatsStock->setFetchMode(PDO::FETCH_OBJ);
		if($ligneStock=$resultatsStock->fetch())
		{
			$doneby = $ligneStock->full_name;
			$codecoordi = $ligneStock->codestock;
		}
	}
	

		$font = new BCGFontFile('barcode/font/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		
		// Barcode Part
		$code = new BCGcode93();
		$code->setScale(2);
		$code->setThickness(20);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setLabel('# '.$sn.' #');
		$code->parse(''.$sn.'');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecoordi.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	<div id="Report" class="account-container" style="margin: 10px auto auto; width:98%; border: 1px solid #eee; background:#fff; padding:5px; padding-bottom:0px; border-radius:3px; font-size:80%;">
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
	if(isset($_GET['selectCovidReport']))
	{
			if(isset($_SESSION['codeS'])){
				$result=$connexion->prepare('SELECT *FROM utilisateurs u, stockkeeper k WHERE u.id_u=k.id_u and k.id_u=:operation');
	            $result->execute(array(
	                'operation'=>$_SESSION['id']
	            ));
			}else{
				$result=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE c.id_u=:operation AND u.id_u=c.id_u');
	            $result->execute(array(
	                'operation'=>$_SESSION['id']
	            ));
			}

            $result->setFetchMode(PDO::FETCH_OBJ);

            if($ligne=$result->fetch())
            {
                $idCa=$ligne->id_u;
                $fullname=$ligne->full_name;

                if($ligne->sexe=="M")
                {
                    $sexe = "Male";
                }elseif($ligne->sexe=="F"){
                    $sexe = "Female";
                }else{
                    $sexe="";
                }

                $resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
                $resultAdresse->execute(array(
                    'idProv'=>$ligne->province,
                    'idDist'=>$ligne->district,
                    'idSect'=>$ligne->secteur
                ));

                $resultAdresse->setFetchMode(PDO::FETCH_OBJ);

                $comptAdress=$resultAdresse->rowCount();

                if($ligneAdresse=$resultAdresse->fetch())
                {
                    if($ligneAdresse->id_province == $ligne->province)
                    {
                        $adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;

                    }
                }elseif($ligne->autreadresse!=""){
                    $adresse=$ligne->autreadresse;
                }else{
                    $adresse="";
                }
            }else{

            }
		
		// $numPa=$_GET['num'];
		// $dailydateperso=$_GET['dailydateperso'];

		$dailydateperso=$_GET['dailydateperso'];
        $paVisit=$_GET['paVisit'];

        $userinfo = '<table style="width:100%; margin-top:20px;">
		
		<tr>
			<td style="text-align:left;">
				<span style="font-weight:bold">Full name: </span>
				'.$fullname.'<br/>
				<span style="font-weight:bold">Gender: </span>'.$sexe.'<br/>
				<span style="font-weight:bold">Adress: </span>'.$adresse.'
			</td>	
			</tr>		
		</table>';

		echo $userinfo;
		}
		
	
	
		if(isset($_GET['selectCovidReport']))
		{


		$dailydategnl=$_GET['dailydateperso'];
		$iVisitgnl=$_GET['paVisit'];
		
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Bill #'.$sn.'')
					 ->setSubject("Billing information")
					 ->setDescription('Billing information for all patients')
					 ->setKeywords("Bill Excel")
					 ->setCategory("Bill");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B1', 'Report #')
					->setCellValue('C1', ''.$sn.'')
					->setCellValue('B2', 'Done by')
					->setCellValue('C2', ''.$doneby.'')
					->setCellValue('B3', 'Date')
					->setCellValue('C3', ''.$annee.'');

					
		// echo $dailydategnl;
		
		if(isset($_GET['stringResult']))
		{
			$stringResult=$_GET['stringResult'];
		}
		
	?>
		<table cellpadding=3 style="width:100%; margin:5px auto auto;">
			
			<tr>
				<td style="text-align:left;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
				
				<td style="text-align:center; width:50%;">
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> Report #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right; width:30%;">
					
					<form method="post" action="Covid-report.php?selectCovidReport=ok&dailydateperso=<?php echo $dailydategnl;?>&paVisit=<?php echo $iVisitgnl;?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportPdf=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<!-- <td style="text-align:left">
						
					<form method="post" action="dmacrssb_report.php?paVisitgnl=<?php echo $iVisitgnl;?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td> -->
				
				<td style="text-align:right">
					
					<a href="report.php?Covid19report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
						<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
					</a>
					
				</td>
			</tr>

			<tr class="buttonBill">
				<td colspan="2">
					<span class="fa fa-spinner" style="color: red;font-size: 17px;text-align: left;"></span>  <span style="text-align: left;padding-left: 10px;"> Means That No Results Found on this Test.</span>
				</td>
			</tr>
			<tr class="buttonBill">
				<td colspan="2">
					<span style="background-color: #ff00007d;padding: 5px; padding-left: 10px;padding-right: 20px;text-align: left;"></span>  <span style="text-align: left;padding-left: 10px;">Means That Amount Paid is Lower than Test Regular Price <b>Or Not yet Billed.</b></span>
				</td>
			</tr>
		
		</table>
		
		<?php
		if(isset($_GET['selectCovidReport']))
		{
		?>
		<div id="selectCovidReport" style="font-weight:normal;">
			<table style="width:100%" class="printPreview tablesorter3" cellspacing="0"> 
						
				<thead>
					<tr>
						<th style="border-right: 1px solid #bbb;text-align:center;">N°</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Patient Name</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Sex And Age</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Test</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Assurance</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Results</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Results Status</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Results Date</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Amount</th>
					</tr> 
				</thead> 
				
				<tbody>
					<?php 
						$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
						$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
								
						$assuCount = $comptAssuConsu->rowCount();
						$TotalGnlMedLabo = 0;
                   		$compteur=1;
						
						for($la=1;$la<=$assuCount;$la++)
						{
							$assurances = $comptAssuConsu->fetch();
							$idassu = $assurances->id_assurance;
							$assu = $assurances->nomassurance;
							$presta_assuLab=strtolower('prestations_'.$assu);


							 $resultGnlBillReport=$connexion->query('SELECT * FROM med_labo c,'.$presta_assuLab.' p WHERE c.id_prestationExa=p.id_prestation AND (p.nompresta LIKE "%covid%" OR p.namepresta LIKE "%covid%") AND c.prixprestationExa!=0.01 AND c.id_factureMedLabo!=0 AND c.id_assuLab='.$idassu.' '.$dailydategnl);
                   			 $resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);
                   			 $rowCount = $resultGnlBillReport->rowCount();

                   			 //echo $rowCount.'<br>';

                   			 //echo 'SELECT * FROM med_labo c,'.$presta_assuLab.' p WHERE c.id_prestationExa=p.id_prestation AND (p.nompresta LIKE "%covid%" OR p.namepresta LIKE "%covid%") AND c.prixprestationExa!=0.01 AND c.id_factureMedLabo!=0  '.$dailydategnl.'<br>';

                   			 //make while
                   			 
				            while($ligneCov = $resultGnlBillReport->fetch()){
							?>
								<tr style="text-align:center;
									<?php if($ligneCov->prixprestationExa<10000){echo "background-color: #ff00007d;";} ?>
									">
										<td style="text-align:center;"><?php echo $compteur;?></td>

										<?php
											$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
											$resultPatient->execute(array(
											'operation'=>$ligneCov->numero
											));
									
											$resultPatient->setFetchMode(PDO::FETCH_OBJ);
											//on veut que le résultat soit recupérable sous forme d'objet

											$comptFiche=$resultPatient->rowCount();
											
											if($lignePatient=$resultPatient->fetch())
											//on recupere la liste des éléments
											{
												$fullname = $lignePatient->full_name;
												$numero = $lignePatient->numero;
												$sexe = $lignePatient->sexe;

												$dateN =$lignePatient->date_naissance;
												$profession=$lignePatient->profession;
																			
												$old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].'	';//re?t l'ann?de naissance
												$month=$dateN[5].''.$dateN[6].'	';//re?t le mois de naissance

												$an= date('Y')-$old.'	';//recupere l'? en ann?
												$mois= date('m')-$month.'	';//recupere l'? en mois

												if($mois<0)
												{
													$age= ($an-1).' ans	';
													// $an= ($an-1).' ans	'.(12+$mois).' mois';
													// echo $an= $an-1;

												}else{

													$age= $an.' ans';
													// $an= $an.' ans';
													//$an= $an.' ans	'.(date('m')-$month).' mois';// X ans Y mois
													// echo $mois= date('m')-$month;
												}
													
												
												echo '<td style="text-align:left;font-weight:bold;">'.$fullname.'('.$numero.')</td>';
												echo '<td style="text-align:center;">'.$sexe.' <b>|</b> '.$age.'</td>';

											}
										?>

										<td style="text-align:center;">
										 <?php
										 	// $ExamName = $connexion->prepare("SELECT * FROM prestations_private WHERE id_prestation=:presta");
										 	// $ExamName->execute(array('presta'=>$ligneCov->id_prestationExa));
										 	// $ExamName->setFetchMode(PDO::FETCH_OBJ);
										 	// if($ligneExam = $ExamName->fetch()){
										 	// 	echo $ligneExam->nompresta;
										 	// }else{
										 	// 	echo "---";
										 	// }

										 	// echo $ligneCov->nompresta.' ('.$ligneCov->id_assuLab.')=>'.'('.$idassu.')';
										 	echo $ligneCov->nompresta;

										 ?>
										</td>
										<td style="text-align:center;"><?php echo $assu; ?></td>
										<td style="text-align:center;"><span class="badge <?php if($ligneCov->autreresultats=='POSITIF' OR $ligneCov->autreresultats=='POSITIVE'){echo "flashing";} ?>"><?php echo $ligneCov->autreresultats;?></span></td>
										<td style="text-align:center;">
											<?php if($ligneCov->autreresultats =="" OR $ligneCov->dateresultats=="0000-00-00"){?>
												<span class="fa fa-spinner fa-spin" style="color: red;font-size: 17px;"></span>

											<?php } ?>
										</td>
										<td style="text-align:center;"><?php echo $ligneCov->dateresultats;?></td>
										<td style="text-align:center;">
											<?php 
												echo number_format($ligneCov->prixprestationExa);
												$TotalGnlMedLabo += $ligneCov->prixprestationExa;
											?>
										</td>
									</tr>

							<?php
							$compteur++;
							}
						}
					?>
					<tr style="text-align:center;">
						<td colspan=8></td>
						<td style="font-size: 13px; font-weight: bold;text-align: center;">
							<?php						
								echo number_format($TotalGnlMedLabo);			
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			 }
			?>
		</div>
		<?php
		}
	?>

	</div>
	
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
	
		<?php
		$footer = '

			<table style="width:100%">
				
				<tr>
					<td style="text-align:left; margin: 10px auto auto; width:200px; background:#fff; padding-bottom:20px; border-bottom:1px solid #333;">
						<span style="font-weight:bold">Insurance Signature</span>
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
	
}
?>
</body>

</html>