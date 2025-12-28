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

if(isset($_GET['createRN']))
{
    $createRN=$_GET['createRN'];
}

if(isset($_GET['stringResult']))
{
    $stringResult=$_GET['stringResult'];
}


/*if(isset($_GET['UnBilledbill']))
{
    if($_GET['paVisit']=='dailydatebillGnl')
    {
        $sn = showRN('CRD');
    }else{
        if($_GET['paVisit']=='monthlydatebillGnlYear')
        {
            $sn = showRN('CRM');
        }else{
            if($_GET['paVisit']=='annualyGnlMedic')
            {
                $sn = showRN('CRA');
            }else{
                if($_GET['paVisit']=='customGnlMedic')
                {
                    $sn = showRN('CRC');
                }else{
                    if($_GET['paVisit']=='gnlPersoBill')
                    {
                        $sn = showRN('CRG');
                    }
                }
            }
        }
    }
}*/
if (isset($_GET['dailydateperso'])) {
   $dailydateperso = $_GET['dailydateperso'];
}

if(isset($_GET['caVisit']))
{
    $caVisit=$_GET['caVisit'];
}

if(isset($_GET['caVisitgnl']))
{
    $caVisitgnl=$_GET['caVisitgnl'];
}


?>

<!doctype html>
<html lang="en">
<noscript>
    Cette page requiert du Javascript.
    Veuillez l'activer pour votre navigateur
</noscript>
<head>
    <meta charset="utf-8"/>
    <title>
        <?php 
            if (isset($_GET['clinic'])) {
                if (isset($_GET['clearbill'])) {
                    echo 'ClearBill Clinical Report'; 
                }else{
                    if (isset($_GET['unclearbill'])) {
                      echo 'UnclearBill Clinical Report'; 
                    }
                }
            }
            if (isset($_GET['hospi'])) {
                if (isset($_GET['clearbill'])) {
                   echo 'ClearBill Hospital Report'; 
                }else{
                    if (isset($_GET['unclearbill'])) {
                       echo 'UnclearBill Hospital Report'; 
                    }
                }
            }
            
        ?>
    </title>

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
$idDoneby=$_SESSION['id'];

if($connected==true AND isset($_SESSION['id']))
{

    if(isset($_SESSION['codeC']))
    {
        $resultatsCoordi=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.id_u=:operation');
        $resultatsCoordi->execute(array(
            'operation'=>$idDoneby
        ));

        $resultatsCoordi->setFetchMode(PDO::FETCH_OBJ);

        if($ligneCoordi=$resultatsCoordi->fetch())
        {
            $doneby = $ligneCoordi->full_name;
            $codeDoneby = $ligneCoordi->codecoordi;
        }
    }

    if(isset($_SESSION['codeAcc']))
    {
        $resultatsAcc=$connexion->prepare('SELECT *FROM utilisateurs u, accountants acc WHERE u.id_u=acc.id_u and acc.id_u=:operation');
        $resultatsAcc->execute(array(
            'operation'=>$idDoneby
        ));

        $resultatsAcc->setFetchMode(PDO::FETCH_OBJ);

        if($ligneAcc=$resultatsAcc->fetch())
        {
            $doneby = $ligneAcc->full_name;
            $codeDoneby = $ligneAcc->codeaccount;
        }
    }

    if(isset($_SESSION['codeCash']))
    {
        $resultatsCa=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers ca WHERE u.id_u=ca.id_u and ca.id_u=:operation');
        $resultatsCa->execute(array(
            'operation'=>$idDoneby
        ));

        $resultatsCa->setFetchMode(PDO::FETCH_OBJ);

        if($ligneCa=$resultatsCa->fetch())
        {
            $doneby = $ligneCa->full_name;
            $codeDoneby = $ligneCa->codecashier;
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
    $code->setLabel('#  #');
    $code->parse('');

    // Drawing Part
    $drawing = new BCGDrawing('barcode/png/barcode'.$codeDoneby.'.png', $color_white);
    $drawing->setBarcode($code);
    $drawing->draw();

    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    ?>
    <div id="Report" class="account-container" style="margin: 10px auto auto; width:98%; border: 1px solid #eee; background:#fff; padding:5px; padding-bottom:0px; border-radius:3px; font-size:80%;">
        <?php
        $barcode = '

	<table style="width:100%">
		
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
			
			<td style="text-align:right;display:none;">
				<img src="barcode/png/barcode'.$codeDoneby.'.png" style="height:auto;"/>	
			</td>
							
		</tr>
		
	</table>';

        echo $barcode;
        ?>

        <?php
        /*if(isset($_GET['cash']))
        {*/
            /*if(isset($_GET['cash'])){
            $result=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE c.codecashier=:operation AND u.id_u=c.id_u');
            $result->execute(array(
                'operation'=>$_GET['cash']
            ));
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

            }}*/


           /* if(isset($_GET['cash']) OR isset($_SESSION['codeCash'])){
                 $codeCa=$_GET['cash'];
            }else{
                 $codeCa=$_SESSION['codeC'];
            }*/

           /* $dailydateperso=$_GET['dailydateperso'];
            $paVisit=$_GET['paVisit'];


            // $dailydateperso;

            $objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
                ->setLastModifiedBy(''.$doneby.'')
                ->setTitle('Report #'.$sn.'')
                ->setSubject("Report information")
                ->setDescription('Report information for cashier : '.$codeCa.', '.$fullname.'')
                ->setKeywords("Report Excel")
                ->setCategory("Report");

            for($col = ord('a'); $col <= ord('z'); $col++)
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'S/N')
                ->setCellValue('B1', ''.$codeCa.'')
                ->setCellValue('A2', 'Cashier name')
                ->setCellValue('B2', ''.$fullname.'')
                ->setCellValue('A3', 'Adresse')
                ->setCellValue('B3', ''.$adresse.'')

                ->setCellValue('F1', 'Report #')
                ->setCellValue('G1', '')
                ->setCellValue('F2', 'Done by')
                ->setCellValue('G2', ''.$doneby.'')
                ->setCellValue('F3', 'Date')
                ->setCellValue('G3', ''.$annee.'');*/

            ?>

            <table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
                <tr>
                    <td style="text-align:left;width:10%;">
                        <h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
                    </td>

                    <td style="text-align:left;">
                        <h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> | 
                            <?php 
                                if(isset($_GET['divGnlclearbillClinicReport'])){
                                    echo ' Billed Cashier Clinic Report';
                                }else{
                                    if (isset($_GET['divGnlclearbillHospiReport'])) {
                                       echo ' Billed Cashier Hospital Report';
                                    }
                                }
                            ?></h2>
                    </td>

                    <td style="text-align:right">
                        <!-- num=<?php echo $_GET['cash']; ?>&id_uC=<?php echo $_GET['id_uC']; ?> -->

                        <form method="post" action="accountsUnbilledReport.php?&dailydateperso=<?php echo $_GET['dailydateperso'];?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['clinic'])){ echo'&clinic='.$_GET['clinic']; }?><?php if(isset($_GET['hospi'])){ echo'&hospi='.$_GET['hospi']; }?>&divGnlclearbillClinicReport=ok&UnBilledbill=ok&createReportPdf=ok" enctype="multipart/form-data" class="buttonBill">

                            <button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
                        </form>

                    </td>

                    <td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">

                        <a href="report.php?coordi=<?php echo $_SESSION['id'];?><?php if(isset($_GET['divPersoBillReport'])){ echo '&report=ok';} if(isset($_GET['divPersoBillReportHosp'])){ echo '&reporthospCash=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
                            <button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
                        </a>

                    </td>
                </tr>

            </table>

            <?php
           /* $userinfo = '
	
	<table style="width:50%; margin-top:20px;font-size:15px;" align="center">		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">Cashier name : </span>		
			</td>
			<td style="text-align:left;">'.$fullname.'</td>			
		</tr>
		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">S/N : </span>		
			</td>
			<td style="text-align:left;">'.$codeCa.'</td>			
		</tr>
		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">Gender : </span>		
			</td>
			<td style="text-align:left;">'.$sexe.'</td>			
		</tr>
		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">Adress : </span>		
			</td>
			<td style="text-align:left;">'.$adresse.'</td>			
		</tr>		
	</table>';

            echo $userinfo;*/



            if(isset($_GET['divGnlclearbillClinicReport']))
            {
                ?>
            <div id="divPersoBillReport">

                    <?php

                    $resultCashierBillReport=$connexion->query('SELECT *FROM bills b WHERE '.$dailydateperso.' ORDER BY b.datebill ASC');

                    $resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);

                    $compCashBillReport=$resultCashierBillReport->rowCount();

                    if($compCashBillReport!=0)
                    {
                    
                        $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('A9', 'N°')
                                        ->setCellValue('B9', 'Date of bill')
                                        ->setCellValue('C9', 'Cashier name')
                                        ->setCellValue('D9', 'Bill number')
                                        ->setCellValue('E9', 'Beneficiary\'s age')
                                        ->setCellValue('F9', 'Gender ')
                                        ->setCellValue('G9', 'Beneficiary\'s name')
                                        ->setCellValue('H9', 'Principal member')
                                        ->setCellValue('I9', 'Affiliate\'s affectation')
                                        ->setCellValue('J9', 'Type of consultation')
                                        ->setCellValue('K9', 'Price of consultation (Rwf)')
                                        ->setCellValue('L9', 'Surgery')
                                        ->setCellValue('M9', 'Nursing Care')
                                        ->setCellValue('N9', 'Laboratory tests')
                                        ->setCellValue('O9', 'Medical imaging')
                                        ->setCellValue('P9', 'Physiotherapy')
                                        ->setCellValue('Q9', 'P&O')
                                        ->setCellValue('R9', 'Psychologie')
                                        ->setCellValue('S9', 'Consommables')
                                        ->setCellValue('T9', 'Medications')
                                        ->setCellValue('U9', 'Services')
                                        ->setCellValue('V9', 'Total Amount')
                                        ->setCellValue('W9', 'Total Amount Patient')
                                        ->setCellValue('X9', 'Total Amount Insurance')
                                        ->setCellValue('Y9', 'Insurance Type');
                            
                    ?>
                    <table class="printPreview tablesorter3" cellspacing="0" style="background:#fff; margin:auto;"> 
                                
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #bbb;text-align:center;">N°</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Date</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Cashier Name</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Bill number</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Insurance Type</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;" colspan=2>Beneficiary's age / gender </th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Beneficiary's names</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Principal member</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Affiliate's affectation</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(113);?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Surgery';?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(98);?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(99);?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medical imaging';?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Physiotherapy';?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'P&O';?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Psychologie';?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Consommables';?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medications';?></th>
                                <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(39);?>s</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Total Amount</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Total Amount Patient</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Total Debts</th>
                                <th style="border-right: 1px solid #bbb;text-align:center;">Total Amount Insurance</th>
                            </tr> 
                        </thead> 
                        
                        <tbody>
                        <?php
                
                        $billArray = array();
                        
                        $idBillString = '(';
                        
                        // echo $comptBillReport.'<br/>';
                        
                        
                        while($ligneCashierBillReport=$resultCashierBillReport->fetch(PDO::FETCH_ASSOC))
                        {
                            $billArray[] = $ligneCashierBillReport;
                            $idBillString .= ''.$ligneCashierBillReport['id_bill'].','; 
                        }
                        
                        $idBillString = substr($idBillString,0,-1).')';

                                        
                        $resultConsu=$connexion->query('SELECT *FROM consultations c WHERE c.id_factureConsult in '.$idBillString.'');
                         
                        $consuArray=array();
                         
                        $comptConsu=$resultConsu->rowCount();
                        
                    
                        while($ligneGnlConsultReport=$resultConsu->fetch(PDO::FETCH_ASSOC))
                        {
                            $consuArray[$ligneGnlConsultReport['id_factureConsult']] = $ligneGnlConsultReport;
                            
                        }                           
                        

                        $resultMedSurge=$connexion->query('SELECT *FROM med_surge ms WHERE ms.id_factureMedSurge in '.$idBillString.'');
                        
                        $comptMedSurge=$resultMedSurge->rowCount();
                            

                        $surgeArray=array();
                        $sCount=0;
                        
                        while($ligneGnlSurge=$resultMedSurge->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlSurge['id_factureMedSurge'], $surgeArray))
                            {
                                $sCount = sizeof($surgeArray[$ligneGnlSurge['id_factureMedSurge']]);
                            }else{
                                $sCount=0;
                            }
                                
                                $surgeArray[$ligneGnlSurge['id_factureMedSurge']][$sCount] = $ligneGnlSurge;
                            
                        }                           
                                                
                        
                        $resultMedInf=$connexion->query('SELECT *FROM med_inf mi WHERE mi.id_factureMedInf in '.$idBillString.'');

                        $comptMedInf=$resultMedInf->rowCount();


                        $infArray=array();
                        $iCount=0;

                        while($ligneGnlInf=$resultMedInf->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlInf['id_factureMedInf'], $infArray))
                            {
                                $iCount = sizeof($infArray[$ligneGnlInf['id_factureMedInf']]);
                            }else{
                                $iCount=0;
                            }

                                $infArray[$ligneGnlInf['id_factureMedInf']][$iCount] = $ligneGnlInf;

                        }


                        $resultMedLabo=$connexion->query('SELECT *FROM med_labo ml WHERE ml.id_factureMedLabo in '.$idBillString.'');
                        
                        $comptMedLabo=$resultMedLabo->rowCount();
                            

                        $laboArray=array();             
                        $lCount=0;
                        
                        while($ligneGnlLabo=$resultMedLabo->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlLabo['id_factureMedLabo'], $laboArray))
                            {
                                $lCount = sizeof($laboArray[$ligneGnlLabo['id_factureMedLabo']]);
                            }else{
                                $lCount=0;
                            }
                            
                            $laboArray[$ligneGnlLabo['id_factureMedLabo']][$lCount] = $ligneGnlLabo;
                            
                            
                        }                           
                                // print_r($laboArray[20873]);                  
                                                
                        
                        $resultMedRadio=$connexion->query('SELECT *FROM med_radio mr WHERE mr.id_factureMedRadio in '.$idBillString.'');
                        
                        $comptMedRadio=$resultMedRadio->rowCount();
                            

                        $radioArray=array();                
                        $rCount=0;
                        
                        while($ligneGnlRadio=$resultMedRadio->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlRadio['id_factureMedRadio'], $radioArray))
                            {
                                $rCount = sizeof($radioArray[$ligneGnlRadio['id_factureMedRadio']]);
                            }else{
                                $rCount=0;
                            }
                            
                            $radioArray[$ligneGnlRadio['id_factureMedRadio']][$rCount] = $ligneGnlRadio;
                            
                        }                           
                                // print_r($radioArray[20873]);                 
                                                
                        
                        $resultMedKine=$connexion->query('SELECT *FROM med_kine mk WHERE mk.id_factureMedKine in '.$idBillString.'');

                        $comptMedKine=$resultMedKine->rowCount();


                        $kineArray=array();
                        $kCount=0;

                        while($ligneGnlKine=$resultMedKine->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlKine['id_factureMedKine'], $kineArray))
                            {
                                $kCount = sizeof($kineArray[$ligneGnlKine['id_factureMedKine']]);
                            }else{
                                $kCount=0;
                            }

                            $kineArray[$ligneGnlKine['id_factureMedKine']][$kCount] = $ligneGnlKine;

                        }
                                // print_r($kineArray[20873]);


                        $resultMedOrtho=$connexion->query('SELECT *FROM med_ortho mo WHERE mo.id_factureMedOrtho in '.$idBillString.'');

                        $comptMedOrtho=$resultMedOrtho->rowCount();


                        $orthoArray=array();
                        $oCount=0;

                        while($ligneGnlOrtho=$resultMedOrtho->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlOrtho['id_factureMedOrtho'], $orthoArray))
                            {
                                $oCount = sizeof($orthoArray[$ligneGnlOrtho['id_factureMedOrtho']]);
                            }else{
                                $oCount=0;
                            }

                            $orthoArray[$ligneGnlOrtho['id_factureMedOrtho']][$oCount] = $ligneGnlOrtho;

                        }
                                // print_r($orthoArray[20873]);



                        $resultMedPsycho=$connexion->query('SELECT *FROM med_psy mp WHERE mp.id_factureMedPsy in '.$idBillString.'');

                        $comptMedPsycho=$resultMedPsycho->rowCount();


                        $psyArray=array();
                        $psyCount=0;

                        while($ligneGnlPsycho=$resultMedPsycho->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlPsycho['id_factureMedPsy'], $psyArray))
                            {
                                $psyCount = sizeof($psyArray[$ligneGnlPsycho['id_factureMedPsy']]);
                            }else{
                                $psyCount=0;
                            }

                            $psyArray[$ligneGnlPsycho['id_factureMedPsy']][$psyCount] = $ligneGnlPsycho;

                        }
                                // print_r($psyArray[20873]);


                        $resultMedConsom=$connexion->query('SELECT *FROM med_consom mco WHERE mco.id_factureMedConsom in '.$idBillString.'');
                        
                        $comptMedConsom=$resultMedConsom->rowCount();
                            

                        $consomArray=array();               
                        $cCount=0;
                        
                        while($ligneGnlConsom=$resultMedConsom->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlConsom['id_factureMedConsom'], $consomArray))
                            {
                                $cCount = sizeof($consomArray[$ligneGnlConsom['id_factureMedConsom']]);
                            }else{
                                $cCount=0;
                            }
                            
                            $consomArray[$ligneGnlConsom['id_factureMedConsom']][$cCount] = $ligneGnlConsom;
                            
                        }                           
                                // print_r($consomArray[20873]);                    
                                                
                        
                        $resultMedMedoc=$connexion->query('SELECT *FROM med_medoc mdo WHERE mdo.id_factureMedMedoc in '.$idBillString.'');
                        
                        $comptMedMedoc=$resultMedMedoc->rowCount();
                            

                        $medocArray=array();                
                        $mCount=0;
                        
                        while($ligneGnlMedoc=$resultMedMedoc->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlMedoc['id_factureMedMedoc'], $medocArray))
                            {
                                $mCount = sizeof($medocArray[$ligneGnlMedoc['id_factureMedMedoc']]);
                            }else{
                                $mCount=0;
                            }
                            
                            $medocArray[$ligneGnlMedoc['id_factureMedMedoc']][$mCount] = $ligneGnlMedoc;
                                                
                        }                           
                            // print_r($medocArray[20873]);                 

                        $resultServices=$connexion->query('SELECT *FROM med_consult mc WHERE mc.id_factureMedConsu in '.$idBillString.'');

                        $comptServices=$resultServices->rowCount();

                        $serviceArray=array();
                        $sCount=0;

                        while($ligneGnlServices=$resultServices->fetch(PDO::FETCH_ASSOC))
                        {
                            if(array_key_exists($ligneGnlServices['id_factureMedConsu'], $serviceArray))
                            {
                                $sCount = sizeof($serviceArray[$ligneGnlServices['id_factureMedConsu']]);
                            }else{
                                $sCount=0;
                            }

                            $serviceArray[$ligneGnlServices['id_factureMedConsu']][$sCount] = $ligneGnlServices;


                        }



                        $TotalGnlTypeConsu=0;
                        $TotalGnlTypeConsuCCO=0;
                            $TotalGnlTypeConsuPatient=0;
                            $TotalGnlTypeConsuInsu=0;
                        $TotalGnlMedSurge=0;
                        $TotalGnlMedSurgeCCO=0;
                            $TotalGnlMedSurgePatient=0;
                            $TotalGnlMedSurgeInsu=0;
                        $TotalGnlMedInf=0;
                        $TotalGnlMedInfCCO=0;
                            $TotalGnlMedInfPatient=0;
                            $TotalGnlMedInfInsu=0;
                        $TotalGnlMedLabo=0;
                        $TotalGnlMedLaboCCO=0;
                            $TotalGnlMedLaboPatient=0;
                            $TotalGnlMedLaboInsu=0;
                        $TotalGnlMedRadio=0;
                        $TotalGnlMedRadioCCO=0;
                            $TotalGnlMedRadioPatient=0;
                            $TotalGnlMedRadioInsu=0;
                        $TotalGnlMedKine=0;
                        $TotalGnlMedKineCCO=0;
                            $TotalGnlMedKinePatient=0;
                            $TotalGnlMedKineInsu=0;
                        $TotalGnlMedOrtho=0;
                        $TotalGnlMedOrthoCCO=0;
                            $TotalGnlMedOrthoPatient=0;
                            $TotalGnlMedOrthoInsu=0;
                        $TotalGnlMedPsy=0;
                        $TotalGnlMedPsyCCO=0;
                            $TotalGnlMedPsyPatient=0;
                            $TotalGnlMedPsyInsu=0;
                        $TotalGnlMedConsom=0;
                        $TotalGnlMedConsomCCO=0;
                            $TotalGnlMedConsomPatient=0;
                            $TotalGnlMedConsomInsu=0;
                        $TotalGnlMedMedoc=0;
                        $TotalGnlMedMedocCCO=0;
                            $TotalGnlMedMedocPatient=0;
                            $TotalGnlMedMedocInsu=0;
                        $TotalGnlMedConsu=0;
                        $TotalGnlMedConsuCCO=0;
                            $TotalGnlMedConsuPatient=0;
                            $TotalGnlMedConsuInsu=0;
                        $TotalGnlPrice=0;
                        $TotalGnlPriceCCO=0;
                            $TotalGnlPricePatient=0;
                            $TotalGnlDettePatient=0;
                            $TotalGnlPriceInsu=0;
                        
                        $i=0;
                        $compteur=1;
                                        

                        for($b=0;$b<sizeof($billArray);$b++)
                        {
                            $TotalDayPrice=0;
                            $TotalDayPriceCCO=0;
                            $TotalDayPricePatient=0;
                            $TotalDayPriceInsu=0;
                            
                            $consult ="";
                            $medsurge ="";
                            $medinf ="";
                            $medlabo ="";
                            $medradio ="";
                            $medkine ="";
                            $medortho ="";
                            $medpsy ="";
                            $medconsom ="";
                            $medmedoc ="";
                            $medconsu ="";
                                
                    ?>
                    
                            <tr style="text-align:center;">
                                <td style="text-align:center;"><?php echo $compteur;?></td>
                                <td style="text-align:center;"><?php echo $billArray[$b]['datebill'];?></td>
                                <td>
                                    <?php
                                        $resultCashier=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u AND c.codecashier=:operation ORDER BY u.id_u DESC');
                                        $resultCashier->execute(array(
                                        'operation'=> $billArray[$b]['codecashier']
                                        ));
                                        
                                        $resultCashier->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

                                        $comptDoct=$resultCashier->rowCount();
                                        
                                        if($ligneCashier=$resultCashier->fetch())//on recupere la liste des éléments
                                        {
                                            $fullnameCashier = $ligneCashier->nom_u.' '.$ligneCashier->prenom_u;
                                            
                                            echo $fullnameCashier;
                                        }else{
                                            echo '';
                                        }
                                        
                                    ?>
                                </td>       
                                <td style="text-align:center;"><?php echo $billArray[$b]['numbill'];?></td>
                                        
                                <?php
                                    $resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
                                    $resultPatient->execute(array(
                                    'operation'=>$billArray[$b]['numero']
                                    ));
                            
                                    $resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit recupérable sous forme d'objet

                                    $comptFiche=$resultPatient->rowCount();
                                    
                                    if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
                                    {
                                        $fullname = $lignePatient->full_name;
                                        $numero = $lignePatient->numero;
                                        $sexe = $lignePatient->sexe;
                                        $carteassuid = $billArray[$b]['idcardbill'];
                                        $insurancetype = $billArray[$b]['nomassurance'].' ('.$billArray[$b]['billpercent'].'%)';
                                        
                                        $adherent =$lignePatient->adherent;
                                        $dateN =$lignePatient->date_naissance;
                                        $profession=$lignePatient->profession;
                                                    
                        $old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].' ';//re?t l'ann?de naissance
                        $month=$dateN[5].''.$dateN[6].' ';//re?t le mois de naissance

                        $an= date('Y')-$old.'   ';//recupere l'? en ann?
                        $mois= date('m')-$month.'   ';//recupere l'? en mois

                        if($mois<0)
                        {
                            $age= ($an-1).' ans ';
                            // $an= ($an-1).' ans   '.(12+$mois).' mois';
                            // echo $an= $an-1;

                        }else{

                            $age= $an.' ans';
                            // $an= $an.' ans';
                            //$an= $an.' ans    '.(date('m')-$month).' mois';// X ans Y mois
                            // echo $mois= date('m')-$month;
                        }
                                        
                                        
                                        
                                        echo '<td style="text-align:center;">'.$billArray[$b]['nomassurance'].' ('.$billArray[$b]['billpercent'].'%)</td>'; 
                                        
                                        echo '<td style="text-align:center;">'.$age.'</td>';
                                        echo '<td style="text-align:center;">'.$sexe.'</td>';
                                        echo '<td style="text-align:center; font-weight: bold;">'.$fullname.' ('.$numero.')</td>';
                                        echo '<td style="text-align:center; font-weight: normal;">'.$adherent.'</td>';
                                        echo '<td style="text-align:center;font-weight:normal;">'.$profession.'</td>';
                                    }else{
                                        echo '<td></td>';
                                        echo '<td></td>';
                                        echo '<td></td>';
                                    }
                                    
                                ?>
                                                    
                                <td style="text-align:center;">
                                
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
                                        
                                        <tr>
                                            <td style="text-align:center">
                                            <?php
                                            
                                            $TotalTypeConsu=0;
                                            $TotalTypeConsuPatient=0;
                                            $TotalTypeConsuInsu=0;
                                        
                                        if($comptConsu!=0)
                                        {   
                                            if(array_key_exists($billArray[$b]['id_bill'], $consuArray))
                                            {
                                                if($consuArray[$billArray[$b]['id_bill']]['prixtypeconsult']!=0 AND $consuArray[$billArray[$b]['id_bill']]['prixrembou']!=0)
                                                {
                                                    $prixPrestaRembou=$consuArray[$billArray[$b]['id_bill']]['prixrembou'];
                                                                                    
                                                    $prixconsult=$consuArray[$billArray[$b]['id_bill']]['prixtypeconsult'] - $prixPrestaRembou;

                                                }else{
                                                    $prixconsult=$consuArray[$billArray[$b]['id_bill']]['prixtypeconsult'];

                                                }
                                                    
                                                $prixconsultpatient=($prixconsult * $consuArray[$billArray[$b]['id_bill']]['insupercent'])/100;                         
                                                
                                                $prixconsultinsu= $prixconsult - $prixconsultpatient;   
                                                
                                                if($prixconsult>=0)
                                                {   
                                                    $TotalTypeConsu=$TotalTypeConsu+$prixconsult;

                                                    $TotalTypeConsuPatient=$TotalTypeConsuPatient+$prixconsultpatient;
                                                    $TotalTypeConsuInsu=$TotalTypeConsuInsu+$prixconsultinsu;
                                                }
                                            }
                                        }

                            $TotalTypeConsuBalance = ($TotalTypeConsuPatient + $TotalTypeConsuInsu);

                                    echo $TotalTypeConsuBalance;

                                    $consult .= $TotalTypeConsuBalance;

                                            $TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
                                            $TotalDayPricePatient=$TotalDayPricePatient+$TotalTypeConsuPatient;
                                            $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalTypeConsuInsu;
                                            
                                            ?>                                  
                                            </td>
                                        </tr>
                                    </table>                        
                                </td>
                                
                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
                                        
                                        <tr>
                                            <td style="text-align:center">      
                                            <?php
                                                            
                                            $TotalMedSurge=0;
                                            $TotalMedSurgePatient=0;
                                            $TotalMedSurgeInsu=0;
                                            
                                        // print_r($surgeArray);
                                    
                                    if($comptMedSurge!=0)
                                    {
                                        if(array_key_exists($billArray[$b]['id_bill'], $surgeArray))
                                        {
                                            for($n=0;$n<sizeof($surgeArray[$billArray[$b]['id_bill']]);$n++)
                                            {
                                                $prixprestation=$surgeArray[$billArray[$b]['id_bill']][$n]['prixprestationSurge'];
                                                $prixrembouSurge=$surgeArray[$billArray[$b]['id_bill']][$n]['prixrembouSurge'];
                                                $prixautrePrestaS=$surgeArray[$billArray[$b]['id_bill']][$n]['prixautrePrestaS'];
                                                $insupercentSurge=$surgeArray[$billArray[$b]['id_bill']][$n]['insupercentSurge'];
                                                
                                                
                                                if($prixprestation!=0 AND $prixrembouSurge!=0)
                                                {
                                                    $prixPrestaRembou=$prixrembouSurge;
                                                    
                                                    $prixsurge=$prixprestation - $prixPrestaRembou;

                                                }else{
                                                    if($prixautrePrestaS!=0 AND $prixrembouSurge!=0)
                                                    {
                                                        $prixPrestaRembou=$prixrembouSurge;
                                                        
                                                        $prixsurge=$prixautrePrestaS - $prixPrestaRembou;

                                                    }else{
                                                        if($prixprestation!=0 AND $prixrembouSurge ==0)
                                                        {   
                                                            $prixsurge=$prixprestation;
                                                        }else{
                                                            $prixsurge=$prixautrePrestaS;

                                                        }
                                                    }

                                                }
                                                
                                                $prixsurgepatient=($prixsurge * $insupercentSurge)/100;
                                                
                                                $prixsurgeinsu= $prixsurge - $prixsurgepatient;
                                                
                                                if($prixsurge>=1)
                                                {
                                                    $TotalMedSurge=$TotalMedSurge+$prixsurge;
                                                    $TotalMedSurgePatient=$TotalMedSurgePatient+$prixsurgepatient;
                                                    $TotalMedSurgeInsu=$TotalMedSurgeInsu+$prixsurgeinsu;
                                                
                                                }
                                            
                                            }
                                        }

                                    }

                                $TotalMedSurgeBalance = $TotalMedSurgePatient + $TotalMedSurgeInsu;

                                    echo $TotalMedSurgeBalance;

                                    $medsurge .= $TotalMedSurgeBalance;
                                        
                                        $TotalDayPrice=$TotalDayPrice+$TotalMedSurge;
                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedSurgePatient;
                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedSurgeInsu;
                                            ?>
                                            </td>
                                        </tr>
                                    </table>                        
                                </td>

                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;">

                                        <tr>
                                            <td style="text-align:center">
                                            <?php

                                            $TotalMedInf=0;
                                            $TotalMedInfPatient=0;
                                            $TotalMedInfInsu=0;

                                        // print_r($infArray);

                                    if($comptMedInf!=0)
                                    {
                                        if(array_key_exists($billArray[$b]['id_bill'], $infArray))
                                        {
                                            for($n=0;$n<sizeof($infArray[$billArray[$b]['id_bill']]);$n++)
                                            {
                                                $prixprestation=$infArray[$billArray[$b]['id_bill']][$n]['prixprestation'];
                                                $prixrembouInf=$infArray[$billArray[$b]['id_bill']][$n]['prixrembouInf'];
                                                $prixautrePrestaM=$infArray[$billArray[$b]['id_bill']][$n]['prixautrePrestaM'];
                                                $insupercentInf=$infArray[$billArray[$b]['id_bill']][$n]['insupercentInf'];


                                                if($prixprestation!=0 AND $prixrembouInf!=0)
                                                {
                                                    $prixPrestaRembou=$prixrembouInf;

                                                    $prixinf=$prixprestation - $prixPrestaRembou;

                                                }else{
                                                    if($prixautrePrestaM!=0 AND $prixrembouInf!=0)
                                                    {
                                                        $prixPrestaRembou=$prixrembouInf;

                                                        $prixinf=$prixautrePrestaM - $prixPrestaRembou;

                                                    }else{
                                                        if($prixprestation!=0 AND $prixrembouInf ==0)
                                                        {
                                                            $prixinf=$prixprestation;
                                                        }else{
                                                            $prixinf=$prixautrePrestaM;

                                                        }
                                                    }

                                                }

                                                $prixinfpatient=($prixinf * $insupercentInf)/100;

                                                $prixinfinsu= $prixinf - $prixinfpatient;

                                                if($prixinf>=1)
                                                {
                                                    $TotalMedInf=$TotalMedInf+$prixinf;
                                                    $TotalMedInfPatient=$TotalMedInfPatient+$prixinfpatient;
                                                    $TotalMedInfInsu=$TotalMedInfInsu+$prixinfinsu;

                                                }

                                            }
                                        }

                                    }

                                $TotalMedInfBalance = $TotalMedInfPatient + $TotalMedInfInsu;


                                    echo $TotalMedInfBalance;

                                    $medinf .= $TotalMedInfBalance;

                                        $TotalDayPrice=$TotalDayPrice+$TotalMedInf;
                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedInfPatient;
                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedInfInsu;
                                            ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                
                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
                                        
                                        <tr>
                                            <td style="text-align:center">      
                                            <?php
                                                            
                                            $TotalMedLabo=0;
                                            $TotalMedLaboPatient=0;
                                            $TotalMedLaboInsu=0;
                                            
                                        // print_r($laboArray); 
                                    
                                    if($comptMedLabo!=0)
                                    {
                                        
                                        if(array_key_exists($billArray[$b]['id_bill'], $laboArray))
                                        {
                                            for($l=0;$l<sizeof($laboArray[$billArray[$b]['id_bill']]);$l++)
                                            {
                                                $prixprestationExa=$laboArray[$billArray[$b]['id_bill']][$l]['prixprestationExa'];
                                                $prixrembouLabo=$laboArray[$billArray[$b]['id_bill']][$l]['prixrembouLabo'];
                                                $prixautreExamen=$laboArray[$billArray[$b]['id_bill']][$l]['prixautreExamen'];
                                                $insupercentLab=$laboArray[$billArray[$b]['id_bill']][$l]['insupercentLab'];
                                                
                                                
                                                if($prixprestationExa!=0 AND $prixrembouLabo!=0)
                                                {
                                                    $prixPrestaRembou=$prixrembouLabo;
                                                    
                                                    $prixlabo=$prixprestationExa - $prixPrestaRembou;

                                                }else{
                                                    if($prixautreExamen!=0 AND $prixrembouLabo!=0)
                                                    {
                                                        $prixPrestaRembou=$prixrembouLabo;
                                                        
                                                        $prixlabo=$prixautreExamen - $prixPrestaRembou;

                                                    }else{
                                                        if($prixprestationExa!=0 AND $prixrembouLabo ==0)
                                                        {   
                                                            $prixlabo=$prixprestationExa;
                                                        }else{
                                                            $prixlabo=$prixautreExamen;

                                                        }
                                                    }

                                                }
                                                
                                                $prixlabopatient=($prixlabo * $insupercentLab)/100;                         
                                                
                                                $prixlaboinsu= $prixlabo - $prixlabopatient;    
                                                
                                                if($prixlabo>=1)
                                                {
                                                    $TotalMedLabo=$TotalMedLabo+$prixlabo;
                                                    $TotalMedLaboPatient=$TotalMedLaboPatient+$prixlabopatient;
                                                    $TotalMedLaboInsu=$TotalMedLaboInsu+$prixlaboinsu;
                                                
                                                }
                                            
                                            }
                                        }

                                    }

                                    $TotalMedLaboBalance = $TotalMedLaboPatient + $TotalMedLaboInsu;

                                        echo $TotalMedLaboBalance;

                                        $medlabo .= $TotalMedLaboBalance;
                                        
                                        $TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedLaboPatient;
                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedLaboInsu;
                                            ?>
                                            </td>
                                        </tr>
                                    </table>                        
                                </td>
                                
                                
                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
                                        
                                        <tr>
                                            <td style="text-align:center">      
                                            <?php

                                            $TotalMedRadio=0;
                                            $TotalMedRadioPatient=0;
                                            $TotalMedRadioInsu=0;
                                            
                                        // print_r($infArray);  
                                    
                                    if($comptMedRadio!=0)
                                    {
                                        if(array_key_exists($billArray[$b]['id_bill'], $radioArray))
                                        {
                                            for($r=0;$r<sizeof($radioArray[$billArray[$b]['id_bill']]);$r++)
                                            {
                                                $prixprestationRadio=$radioArray[$billArray[$b]['id_bill']][$r]['prixprestationRadio'];
                                                $prixrembouRadio=$radioArray[$billArray[$b]['id_bill']][$r]['prixrembouRadio'];
                                                $prixautreRadio=$radioArray[$billArray[$b]['id_bill']][$r]['prixautreRadio'];
                                                $insupercentRad=$radioArray[$billArray[$b]['id_bill']][$r]['insupercentRad'];
                                                
                                                
                                                if($prixprestationRadio!=0 AND $prixrembouRadio!=0)
                                                {
                                                    $prixPrestaRembou=$prixrembouRadio;
                                                    
                                                    $prixradio=$prixprestationRadio - $prixPrestaRembou;

                                                }else{
                                                    if($prixautreRadio!=0 AND $prixrembouRadio!=0)
                                                    {
                                                        $prixPrestaRembou=$prixrembouRadio;
                                                        
                                                        $prixradio=$prixautreRadio - $prixPrestaRembou;

                                                    }else{
                                                        if($prixprestationRadio!=0 AND $prixrembouRadio ==0)
                                                        {   
                                                            $prixradio=$prixprestationRadio;
                                                        }else{
                                                            $prixradio=$prixautreRadio;

                                                        }
                                                    }

                                                }
                                                
                                                $prixradiopatient=($prixradio * $insupercentRad)/100;                           
                                                
                                                $prixradioinsu= $prixradio - $prixradiopatient; 
                                                
                                                if($prixradio>=1)
                                                {
                                                    $TotalMedRadio=$TotalMedRadio+$prixradio;
                                                    $TotalMedRadioPatient=$TotalMedRadioPatient+$prixradiopatient;
                                                    $TotalMedRadioInsu=$TotalMedRadioInsu+$prixradioinsu;
                                                
                                                }
                                            
                                            }
                                        }

                                    }

                                $TotalMedRadioBalance = $TotalMedRadioPatient + $TotalMedRadioInsu;

                                    echo $TotalMedRadioBalance;
                                    $medradio .= $TotalMedRadioBalance;
                                        
                                        $TotalDayPrice=$TotalDayPrice+$TotalMedRadio;
                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedRadioPatient;
                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedRadioInsu;
                                            ?>
                                            </td>
                                        </tr>
                                    </table>                        
                                </td>

                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;">

                                        <tr>
                                            <td style="text-align:center">
                                            <?php

                                            $TotalMedKine=0;
                                            $TotalMedKinePatient=0;
                                            $TotalMedKineInsu=0;

                                        // print_r($infArray);

                                    if($comptMedKine!=0)
                                    {
                                        if(array_key_exists($billArray[$b]['id_bill'], $kineArray))
                                        {
                                            for($r=0;$r<sizeof($kineArray[$billArray[$b]['id_bill']]);$r++)
                                            {
                                                $prixprestationKine=$kineArray[$billArray[$b]['id_bill']][$r]['prixprestationKine'];
                                                $prixrembouKine=$kineArray[$billArray[$b]['id_bill']][$r]['prixrembouKine'];
                                                $prixautreKine=$kineArray[$billArray[$b]['id_bill']][$r]['prixautrePrestaK'];
                                                $insupercentRad=$kineArray[$billArray[$b]['id_bill']][$r]['insupercentKine'];


                                                if($prixprestationKine!=0 AND $prixrembouKine!=0)
                                                {
                                                    $prixPrestaRembou=$prixrembouKine;

                                                    $prixkine=$prixprestationKine - $prixPrestaRembou;

                                                }else{
                                                    if($prixautreKine!=0 AND $prixrembouKine!=0)
                                                    {
                                                        $prixPrestaRembou=$prixrembouKine;

                                                        $prixkine=$prixautreKine - $prixPrestaRembou;

                                                    }else{
                                                        if($prixprestationKine!=0 AND $prixrembouKine ==0)
                                                        {
                                                            $prixkine=$prixprestationKine;
                                                        }else{
                                                            $prixkine=$prixautreKine;

                                                        }
                                                    }

                                                }

                                                $prixkinepatient=($prixkine * $insupercentRad)/100;

                                                $prixkineinsu= $prixkine - $prixkinepatient;

                                                if($prixkine>=1)
                                                {
                                                    $TotalMedKine=$TotalMedKine+$prixkine;
                                                    $TotalMedKinePatient=$TotalMedKinePatient+$prixkinepatient;
                                                    $TotalMedKineInsu=$TotalMedKineInsu+$prixkineinsu;

                                                }

                                            }
                                        }

                                    }

                                $TotalMedKineBalance = $TotalMedKinePatient + $TotalMedKineInsu;

                                    echo $TotalMedKineBalance;
                                    $medkine .= $TotalMedKineBalance;

                                        $TotalDayPrice=$TotalDayPrice+$TotalMedKine;
                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedKinePatient;
                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedKineInsu;
                                            ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;">

                                        <tr>
                                            <td style="text-align:center">
                                            <?php

                                            $TotalMedOrtho=0;
                                            $TotalMedOrthoPatient=0;
                                            $TotalMedOrthoInsu=0;

                                        // print_r($infArray);

                                    if($comptMedOrtho!=0)
                                    {
                                        if(array_key_exists($billArray[$b]['id_bill'], $orthoArray))
                                        {
                                            for($r=0;$r<sizeof($orthoArray[$billArray[$b]['id_bill']]);$r++)
                                            {
                                                $prixprestationOrtho=$orthoArray[$billArray[$b]['id_bill']][$r]['prixprestationOrtho'];
                                                $prixrembouOrtho=$orthoArray[$billArray[$b]['id_bill']][$r]['prixrembouOrtho'];
                                                $prixautreOrtho=$orthoArray[$billArray[$b]['id_bill']][$r]['prixautrePrestaO'];
                                                $insupercentRad=$orthoArray[$billArray[$b]['id_bill']][$r]['insupercentOrtho'];


                                                if($prixprestationOrtho!=0 AND $prixrembouOrtho!=0)
                                                {
                                                    $prixPrestaRembou=$prixrembouOrtho;

                                                    $prixOrtho=$prixprestationOrtho - $prixPrestaRembou;

                                                }else{
                                                    if($prixautreOrtho!=0 AND $prixrembouOrtho!=0)
                                                    {
                                                        $prixPrestaRembou=$prixrembouOrtho;

                                                        $prixortho=$prixautreOrtho - $prixPrestaRembou;

                                                    }else{
                                                        if($prixprestationOrtho!=0 AND $prixrembouOrtho ==0)
                                                        {
                                                            $prixortho=$prixprestationOrtho;
                                                        }else{
                                                            $prixortho=$prixautreOrtho;

                                                        }
                                                    }

                                                }

                                                $prixorthopatient=($prixortho * $insupercentRad)/100;

                                                $prixorthoinsu= $prixortho - $prixorthopatient;

                                                if($prixortho>=1)
                                                {
                                                    $TotalMedOrtho=$TotalMedOrtho+$prixortho;
                                                    $TotalMedOrthoPatient=$TotalMedOrthoPatient+$prixorthopatient;
                                                    $TotalMedOrthoInsu=$TotalMedOrthoInsu+$prixorthoinsu;

                                                }

                                            }
                                        }

                                    }

                                $TotalMedOrthoBalance = $TotalMedOrthoPatient + $TotalMedOrthoInsu;

                                    echo $TotalMedOrthoBalance;

                                    $medortho .= $TotalMedOrthoBalance;

                                        $TotalDayPrice=$TotalDayPrice+$TotalMedOrtho;
                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedOrthoPatient;
                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedOrthoInsu;
                                            ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;">

                                        <tr>
                                            <td style="text-align:center">
                                            <?php

                                            $TotalMedPsy=0;
                                            $TotalMedPsyPatient=0;
                                            $TotalMedPsyInsu=0;

                                        // print_r($infArray);

                                    if($comptMedPsycho!=0)
                                    {
                                        if(array_key_exists($billArray[$b]['id_bill'], $psyArray))
                                        {
                                            for($r=0;$r<sizeof($psyArray[$billArray[$b]['id_bill']]);$r++)
                                            {
                                                $prixprestationPsy=$psyArray[$billArray[$b]['id_bill']][$r]['prixprestation'];
                                                $prixrembouPsy=$psyArray[$billArray[$b]['id_bill']][$r]['prixrembouPsy'];
                                                $prixautrePsy=$psyArray[$billArray[$b]['id_bill']][$r]['prixautrePrestaM'];
                                                $insupercentPsy=$psyArray[$billArray[$b]['id_bill']][$r]['insupercentPsy'];


                                                if($prixprestationPsy!=0 AND $prixrembouPsy!=0)
                                                {
                                                    $prixPrestaRembou=$prixrembouPsy;

                                                    $prixPsy=$prixprestationPsy - $prixPrestaRembou;

                                                }else{
                                                    if($prixautrePsy!=0 AND $prixrembouPsy!=0)
                                                    {
                                                        $prixPrestaRembou=$prixrembouPsy;

                                                        $prixPsy=$prixautrePsy - $prixPrestaRembou;

                                                    }else{
                                                        if($prixprestationPsy!=0 AND $prixrembouPsy ==0)
                                                        {
                                                            $prixPsy=$prixprestationPsy;
                                                        }else{
                                                            $prixPsy=$prixautrePsy;

                                                        }
                                                    }

                                                }

                                                $prixpsypatient=($prixPsy * $insupercentPsy)/100;

                                                $prixpsyinsu= $prixPsy - $prixpsypatient;

                                                if($prixPsy>=1)
                                                {
                                                    $TotalMedPsy=$TotalMedPsy+$prixPsy;
                                                    $TotalMedPsyPatient=$TotalMedPsyPatient+$prixpsypatient;
                                                    $TotalMedPsyInsu=$TotalMedPsyInsu+$prixpsyinsu;

                                                }

                                            }
                                        }

                                    }

                                $TotalMedPsyBalance = $TotalMedPsyPatient + $TotalMedPsyInsu;

                                    echo $TotalMedPsyBalance;

                                    $medpsy .= $TotalMedPsyBalance;

                                        $TotalDayPrice=$TotalDayPrice+$TotalMedPsy;
                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedPsyPatient;
                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedPsyInsu;
                                            ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                
                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
                                        
                                        <tr>
                                            <td style="text-align:center">      
                                            <?php
                                                            
                                            $TotalMedConsom=0;
                                            $TotalMedConsomPatient=0;
                                            $TotalMedConsomInsu=0;
                                            
                                        // print_r($medocArray);    
                                    
                                    if($comptMedConsom!=0)
                                    {
                                        if(array_key_exists($billArray[$b]['id_bill'], $consomArray))
                                        {
                                            for($c=0;$c<sizeof($consomArray[$billArray[$b]['id_bill']]);$c++)
                                            {
                                                $prixprestationConsom=$consomArray[$billArray[$b]['id_bill']][$c]['prixprestationConsom'];
                                                $prixrembouConsom=$consomArray[$billArray[$b]['id_bill']][$c]['prixrembouConsom'];
                                                $prixautreConsom=$consomArray[$billArray[$b]['id_bill']][$c]['prixautreConsom'];
                                                $qteConsom=$consomArray[$billArray[$b]['id_bill']][$c]['qteConsom'];
                                                $insupercentConsom=$consomArray[$billArray[$b]['id_bill']][$c]['insupercentConsom'];
                                                
                                                
                                                if($prixprestationConsom!=0 AND $prixrembouConsom!=0)
                                                {
                                                    $prixPrestaRembou=$prixrembouConsom;
                                                    
                                                    $prixconsom=($prixprestationConsom * $qteConsom) - $prixPrestaRembou;

                                                }else{
                                                    if($prixautreConsom!=0 AND $prixrembouConsom!=0)
                                                    {
                                                        $prixPrestaRembou=$prixrembouConsom;
                                                        
                                                        $prixconsom=($prixautreConsom * $qteConsom) - $prixPrestaRembou;

                                                    }else{
                                                        if($prixprestationConsom!=0 AND $prixrembouConsom ==0)
                                                        {   
                                                            $prixconsom=$prixprestationConsom * $qteConsom;
                                                        }else{
                                                            $prixconsom=$prixautreConsom * $qteConsom;

                                                        }
                                                    }

                                                }
                                                
                                                $prixconsompatient=($prixconsom * $insupercentConsom)/100;                          
                                                
                                                $prixconsominsu= $prixconsom - $prixconsompatient;  
                                                
                                                if($prixconsom>=1)
                                                {
                                                    $TotalMedConsom=$TotalMedConsom+$prixconsom;
                                                    $TotalMedConsomPatient=$TotalMedConsomPatient+$prixconsompatient;
                                                    $TotalMedConsomInsu=$TotalMedConsomInsu+$prixconsominsu;
                                                
                                                }
                                            
                                            }
                                        }

                                    }

                                $TotalMedConsomBalance = $TotalMedConsomPatient + $TotalMedConsomInsu;

                                    echo $TotalMedConsomBalance;

                                    $medconsom .= $TotalMedConsomBalance;
                                        
                                        $TotalDayPrice=$TotalDayPrice+$TotalMedConsom;
                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsomPatient;
                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedConsomInsu;                                              
                                            ?>
                                            </td>
                                        </tr>
                                    </table>                        
                                </td>
                                
                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
                                        
                                        <tr>
                                            <td style="text-align:center">      
                                            <?php
                                                            
                                            $TotalMedMedoc=0;
                                            $TotalMedMedocPatient=0;
                                            $TotalMedMedocInsu=0;
                                            
                                        // print_r($medocArray);    
                                    
                                    if($comptMedMedoc!=0)
                                    {
                                        if(array_key_exists($billArray[$b]['id_bill'], $medocArray))
                                        {
                                            for($m=0;$m<sizeof($medocArray[$billArray[$b]['id_bill']]);$m++)
                                            {
                                                $prixprestationMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['prixprestationMedoc'];
                                                $prixrembouMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['prixrembouMedoc'];
                                                $prixautreMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['prixautreMedoc'];
                                                $qteMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['qteMedoc'];
                                                $insupercentMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['insupercentMedoc'];
                                                
                                                
                                                if($prixprestationMedoc!=0 AND $prixrembouMedoc!=0)
                                                {
                                                    $prixPrestaRembou=$prixrembouMedoc;
                                                    
                                                    $prixmedoc=($prixprestationMedoc * $qteMedoc) - $prixPrestaRembou;

                                                }else{
                                                    if($prixautreMedoc!=0 AND $prixrembouMedoc!=0)
                                                    {
                                                        $prixPrestaRembou=$prixrembouMedoc;
                                                        
                                                        $prixmedoc=($prixautreMedoc * $qteMedoc) - $prixPrestaRembou;

                                                    }else{
                                                        if($prixprestationMedoc!=0 AND $prixrembouMedoc ==0)
                                                        {   
                                                            $prixmedoc=$prixprestationMedoc * $qteMedoc;
                                                        }else{
                                                            $prixmedoc=$prixautreMedoc * $qteMedoc;

                                                        }
                                                    }

                                                }
                                                
                                                $prixmedocpatient=($prixmedoc * $insupercentMedoc)/100;                         
                                                
                                                $prixmedocinsu= $prixmedoc - $prixmedocpatient;
                                                
                                                if($prixmedoc>=1)
                                                {
                                                    $TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
                                                    $TotalMedMedocPatient=$TotalMedMedocPatient+$prixmedocpatient;
                                                    $TotalMedMedocInsu=$TotalMedMedocInsu+$prixmedocinsu;
                                                
                                                }
                                            
                                            }
                                        }

                                    }

                                $TotalMedMedocBalance = $TotalMedMedocPatient + $TotalMedMedocInsu;

                                    echo $TotalMedMedocBalance;

                                    $medmedoc .= $TotalMedMedocBalance;
                                        
                                        $TotalDayPrice=$TotalDayPrice+$TotalMedMedoc;
                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedMedocPatient;
                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedMedocInsu;                                           
                                            ?>
                                            </td>
                                        </tr>
                                    </table>                        
                                </td>

                                <td style="text-align:center;font-weight:normal;">
                                    <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;">

                                        <tr>
                                            <td style="text-align:center">
                                                <?php

                                                $TotalMedConsu=0;
                                                $TotalMedConsuPatient=0;
                                                $TotalMedConsuInsu=0;

                                                // print_r($serviceArray);

                                                if($comptServices!=0)
                                                {
                                                    if(array_key_exists($billArray[$b]['id_bill'], $serviceArray))
                                                    {

                                                        for($s=0;$s<sizeof($serviceArray[$billArray[$b]['id_bill']]);$s++)
                                                        {
                                                            $prixprestationConsu=$serviceArray[$billArray[$b]['id_bill']][$s]['prixprestationConsu'];
                                                            $prixrembouConsu=$serviceArray[$billArray[$b]['id_bill']][$s]['prixrembouConsu'];
                                                            $prixautreConsu=$serviceArray[$billArray[$b]['id_bill']][$s]['prixautreConsu'];
                                                            $insupercentServ=$serviceArray[$billArray[$b]['id_bill']][$s]['insupercentServ'];


                                                            if($prixprestationConsu!=0 AND $prixrembouConsu!=0)
                                                            {
                                                                $prixPrestaRembou=$prixrembouConsu;

                                                                $prixconsu=$prixprestationConsu - $prixPrestaRembou;

                                                            }else{
                                                                if($prixautreConsu!=0 AND $prixrembouConsu!=0)
                                                                {
                                                                    $prixPrestaRembou=$prixrembouConsu;

                                                                    $prixconsu=$prixautreConsu - $prixPrestaRembou;

                                                                }else{
                                                                    if($prixprestationConsu!=0 AND $prixrembouConsu ==0)
                                                                    {
                                                                        $prixconsu=$prixprestationConsu;
                                                                    }else{
                                                                        $prixconsu=$prixautreConsu;

                                                                    }
                                                                }

                                                            }

                                                            $prixconsupatient=($prixconsu * $insupercentServ)/100;

                                                            $prixconsuinsu= $prixconsu - $prixconsupatient;

                                                            if($prixconsu>=1)
                                                            {
                                                                $TotalMedConsu=$TotalMedConsu+$prixconsu;
                                                                $TotalMedConsuPatient=$TotalMedConsuPatient+$prixconsupatient;
                                                                $TotalMedConsuInsu=$TotalMedConsuInsu+$prixconsuinsu;

                                                            }

                                                        }
                                                    }

                                                }

                                            $TotalMedConsuBalance= $TotalMedConsuPatient + $TotalMedConsuInsu;

                                                echo $TotalMedConsuBalance;

                                                $medconsu .= $TotalMedConsuBalance;

                                                $TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
                                                $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsuPatient;
                                                $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedConsuInsu;
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                        
                                <td style="text-align:center;">
                                    <?php
                                    $TotalDayPriceBalance = $TotalDayPricePatient + $TotalDayPriceInsu;

                                    echo $TotalDayPriceBalance;
                                    ?>
                                </td>
                                
                                <td style="text-align:center;">
                                    <?php
                                    $TotalDayPricePatientBalance = ($TotalDayPriceBalance * $billArray[$b]['billpercent'])/100;

                                        echo $TotalDayPricePatientBalance;
                                    ?>
                                </td>
                                
                                <td style="text-align:center;">
                                    <?php
                                    if($billArray[$b]['dette']!=NULL)
                                    {
                                        echo $billArray[$b]['dette'];
                                    }else{
                                        echo 0;
                                    }
                                    ?>
                                </td>

                                <td style="text-align:center;"><?php echo $TotalDayPriceInsu;?></td>
                            </tr>
                            <?php
                            $TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
                                $TotalGnlTypeConsuPatient = $TotalGnlTypeConsuPatient + $TotalTypeConsuPatient;
                                $TotalGnlTypeConsuInsu = $TotalGnlTypeConsuInsu + $TotalTypeConsuInsu;
                                
                            $TotalGnlMedSurge=$TotalGnlMedSurge + $TotalMedSurge;
                                $TotalGnlMedSurgePatient = $TotalGnlMedSurgePatient + $TotalMedSurgePatient;
                                $TotalGnlMedSurgeInsu = $TotalGnlMedSurgeInsu + $TotalMedSurgeInsu;
                            
                            $TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
                                $TotalGnlMedInfPatient = $TotalGnlMedInfPatient + $TotalMedInfPatient;
                                $TotalGnlMedInfInsu = $TotalGnlMedInfInsu + $TotalMedInfInsu;

                            $TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
                                $TotalGnlMedLaboPatient=$TotalGnlMedLaboPatient + $TotalMedLaboPatient;
                                $TotalGnlMedLaboInsu=$TotalGnlMedLaboInsu + $TotalMedLaboInsu;
                            
                            $TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
                                $TotalGnlMedRadioPatient = $TotalGnlMedRadioPatient + $TotalMedRadioPatient;
                                $TotalGnlMedRadioInsu = $TotalGnlMedRadioInsu + $TotalMedRadioInsu;
                            
                            $TotalGnlMedKine=$TotalGnlMedKine + $TotalMedKine;
                                $TotalGnlMedKinePatient = $TotalGnlMedKinePatient + $TotalMedKinePatient;
                                $TotalGnlMedKineInsu = $TotalGnlMedKineInsu + $TotalMedKineInsu;

                            $TotalGnlMedOrtho=$TotalGnlMedOrtho + $TotalMedOrtho;
                                $TotalGnlMedOrthoPatient = $TotalGnlMedOrthoPatient + $TotalMedOrthoPatient;
                                $TotalGnlMedOrthoInsu = $TotalGnlMedOrthoInsu + $TotalMedOrthoInsu;

                            $TotalGnlMedPsy=$TotalGnlMedPsy + $TotalMedPsy;
                                $TotalGnlMedPsyPatient = $TotalGnlMedPsyPatient + $TotalMedPsyPatient;
                                $TotalGnlMedPsyInsu = $TotalGnlMedPsyInsu + $TotalMedPsyInsu;

                            $TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
                                $TotalGnlMedConsomPatient = $TotalGnlMedConsomPatient + $TotalMedConsomPatient;
                                $TotalGnlMedConsomInsu = $TotalGnlMedConsomInsu + $TotalMedConsomInsu;
                            
                            $TotalGnlMedMedoc=$TotalGnlMedMedoc + $TotalMedMedoc;
                                $TotalGnlMedMedocPatient = $TotalGnlMedMedocPatient + $TotalMedMedocPatient;
                                $TotalGnlMedMedocInsu = $TotalGnlMedMedocInsu + $TotalMedMedocInsu;

                            $TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
                                $TotalGnlMedConsuPatient = $TotalGnlMedConsuPatient + $TotalMedConsuPatient;
                                $TotalGnlMedConsuInsu = $TotalGnlMedConsuInsu + $TotalMedConsuInsu;


                            $TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;
                                $TotalGnlPricePatient = $TotalGnlPricePatient + $TotalDayPricePatient;
                                // $TotalGnlPricePatient = $TotalGnlPricePatient + $TotalDayPricePatientBalance;
                                $TotalGnlDettePatient = $TotalGnlDettePatient + $billArray[$b]['dette'];
                                $TotalGnlPriceInsu = $TotalGnlPriceInsu + $TotalDayPriceInsu;
                                
                                
                                $arrayGnlBillReport[$i][0]=$compteur;
                                $arrayGnlBillReport[$i][1]=$billArray[$b]['datebill'];
                                $arrayGnlBillReport[$i][2]=$billArray[$b]['numbill'];
                                $arrayGnlBillReport[$i][3]=$old;
                                $arrayGnlBillReport[$i][4]=$sexe;
                                $arrayGnlBillReport[$i][5]=$fullname;           
                                $arrayGnlBillReport[$i][6]=$adherent;
                                $arrayGnlBillReport[$i][7]=$profession;
                                
                                $arrayGnlBillReport[$i][8]=$consult;            
                                $arrayGnlBillReport[$i][9]=$TotalTypeConsu;
                                
                                // $arrayGnlBillReport[$i][7]=$medconsu;        
                                $arrayGnlBillReport[$i][10]=$TotalMedConsu;
                                
                                // $arrayGnlBillReport[$i][9]=$medinf;      
                                $arrayGnlBillReport[$i][11]=$TotalMedInf;
                                
                                // $arrayGnlBillReport[$i][11]=$medlabo;        
                                $arrayGnlBillReport[$i][12]=$TotalMedLabo;
                                
                                // $arrayGnlBillReport[$i][9]=$medradio;        
                                $arrayGnlBillReport[$i][13]=$TotalMedRadio;
                                
                                // $arrayGnlBillReport[$i][11]=$medconsom;      
                                $arrayGnlBillReport[$i][14]=$TotalMedConsom;
                                
                                // $arrayGnlBillReport[$i][11]=$medmedoc;       
                                $arrayGnlBillReport[$i][15]=$TotalMedMedoc;
                                
                                $arrayGnlBillReport[$i][16]=$TotalDayPrice;
                                $arrayGnlBillReport[$i][17]=$TotalDayPricePatient;
                                $arrayGnlBillReport[$i][18]=$TotalDayPriceInsu;
                                $arrayGnlBillReport[$i][19]=$insurancetype;
                                
                                $i++;
                                
                                $compteur++;
                                
                            }
                            ?>
                                <tr style="text-align:center;">
                                    <td colspan=10></td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlTypeConsuBalance = $TotalGnlTypeConsuPatient + $TotalGnlTypeConsuInsu;

                                            echo $TotalGnlTypeConsuBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedSurgeBalance = $TotalGnlMedSurgePatient + $TotalGnlMedSurgeInsu;

                                            echo $TotalGnlMedSurgeBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedInfBalance = $TotalGnlMedInfPatient + $TotalGnlMedInfInsu;

                                            echo $TotalGnlMedInfBalance;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedLaboBalance = $TotalGnlMedLaboPatient + $TotalGnlMedLaboInsu;

                                            echo $TotalGnlMedLaboBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedRadioBalance = $TotalGnlMedRadioPatient + $TotalGnlMedRadioInsu;

                                            echo $TotalGnlMedRadioBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedKineBalance = $TotalGnlMedKinePatient + $TotalGnlMedKineInsu;

                                            echo $TotalGnlMedKineBalance;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedOrthoBalance = $TotalGnlMedOrthoPatient + $TotalGnlMedOrthoInsu;

                                            echo $TotalGnlMedOrthoBalance;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedPsyBalance = $TotalGnlMedPsyPatient + $TotalGnlMedPsyInsu;

                                            echo $TotalGnlMedPsyBalance;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedConsomBalance = $TotalGnlMedConsomPatient + $TotalGnlMedConsomInsu;

                                            echo $TotalGnlMedConsomBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedMedocBalance = $TotalGnlMedMedocPatient + $TotalGnlMedMedocInsu;

                                            echo $TotalGnlMedMedocBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedConsuBalance = $TotalGnlMedConsuPatient + $TotalGnlMedConsuInsu;

                                        echo $TotalGnlMedConsuBalance;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;">
                                        <?php
                                        $TotalGnlPriceBalance =$TotalGnlPricePatient + $TotalGnlPriceInsu;

                                            echo $TotalGnlPrice;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;">
                                        <?php
                                        $TotalGnlPricePatientBalance = $TotalGnlPricePatient;
                                            echo $TotalGnlPricePatient;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;">
                                        <?php
                                        echo $TotalGnlDettePatient;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;">
                                        <?php                       
                                            echo $TotalGnlPriceInsu;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php


                        $objPHPExcel->setActiveSheetIndex(0)
                            ->fromArray($arrayGnlBillReport,'','A10')
                            ->setCellValue('J'.(10+$i).'', ''.$TotalGnlTypeConsu.'')
                            ->setCellValue('K'.(10+$i).'', ''.$TotalGnlMedConsu.'')
                            ->setCellValue('L'.(10+$i).'', ''.$TotalGnlMedInf.'')
                            ->setCellValue('M'.(10+$i).'', ''.$TotalGnlMedLabo.'')
                            ->setCellValue('N'.(10+$i).'', ''.$TotalGnlMedRadio.'')
                            ->setCellValue('O'.(10+$i).'', ''.$TotalGnlMedConsom.'')
                            ->setCellValue('P'.(10+$i).'', ''.$TotalGnlMedMedoc.'')
                            ->setCellValue('Q'.(10+$i).'', ''.$TotalGnlPrice.'')
                            ->setCellValue('R'.(10+$i).'', ''.$TotalGnlPricePatient.'')
                            ->setCellValue('S'.(10+$i).'', ''.$TotalGnlPriceInsu.'');
                    }
                    ?>
                </div>
           
                </div>
                <?php

            }

             if(isset($_GET['divGnlclearbillHospiReport']))
            {
                // echo $_GET['dailydateperso'];
                ?>
                <div id="divGnlclearbillHospiReport">

                    <?php

                    $resultCashierBillReport=$connexion->query('SELECT *FROM patients_hosp ph WHERE '.$dailydateperso.' ORDER BY dateSortie ASC');
                    /*$resultCashierBillReport->execute(array(
                        'codeCa'=>$codeCa
                    ));*/

                    $resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);

                    $compCashBillReport=$resultCashierBillReport->rowCount();

                    if($compCashBillReport!=0)
                    {

                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A9', 'N°')
                                    ->setCellValue('B9', 'Date of bill')
                                    ->setCellValue('C9', 'Cashier name')
                                    ->setCellValue('D9', 'Bill number')
                                    ->setCellValue('E9', 'Beneficiary\'s age')
                                    ->setCellValue('F9', 'Gender ')
                                    ->setCellValue('G9', 'Beneficiary\'s name')
                                    ->setCellValue('H9', 'Principal member')
                                    ->setCellValue('I9', 'Affiliate\'s affectation')
                                    ->setCellValue('J9', 'Type of consultation')
                                    ->setCellValue('K9', 'Price of consultation (Rwf)')
                                    ->setCellValue('L9', 'Surgery')
                                    ->setCellValue('M9', 'Nursing Care')
                                    ->setCellValue('N9', 'Laboratory tests')
                                    ->setCellValue('O9', 'Medical imaging')
                                    ->setCellValue('P9', 'Physiotherapy')
                                    ->setCellValue('Q9', 'P&O')
                                    ->setCellValue('R9', 'Consommables')
                                    ->setCellValue('S9', 'Medications')
                                    ->setCellValue('T9', 'Services')
                                    ->setCellValue('U9', 'Total Amount')
                                    ->setCellValue('V9', 'Total Amount Patient')
                                    ->setCellValue('W9', 'Total Amount Insurance')
                                    ->setCellValue('X9', 'Insurance Type');

                        ?>
                        <table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">

                            <thead>
                                <tr>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">N°</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Cashier Name</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Bill number</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Insurance</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Insurance card n°</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;" colspan=2>Beneficiary's age / gender </th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Beneficiary's names</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Principal member</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Affiliate's affectation</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Date Entrée</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Date Sortie</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Nbre de jours</th>
                                    <!-- <th style="border-right: 1px solid #bbb;text-align:center;">P/Days CCO</th> -->
                                    <th style="border-right: 1px solid #bbb;text-align:center;">P/Days</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Prix Total</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Surgery';?></th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(98);?></th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(99);?></th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medical imaging';?></th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Physiotherapy';?></th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'P&O';?></th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Consommables';?></th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medications';?></th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(39);?>s</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Total Amount</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Total Patient</th>
                                    <th style="border-right: 1px solid #bbb;text-align:center;">Total Amount Insurance</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $TotalGnlTypeConsu=0;
                                    $TotalGnlTypeConsuPatient=0;
                                    $TotalGnlTypeConsuInsu=0;
                                $TotalGnlMedSurge=0;
                                    $TotalGnlMedSurgePatient=0;
                                    $TotalGnlMedSurgeInsu=0;
                                $TotalGnlMedInf=0;
                                    $TotalGnlMedInfPatient=0;
                                    $TotalGnlMedInfInsu=0;
                                $TotalGnlMedLabo=0;
                                    $TotalGnlMedLaboPatient=0;
                                    $TotalGnlMedLaboInsu=0;
                                $TotalGnlMedRadio=0;
                                    $TotalGnlMedRadioPatient=0;
                                    $TotalGnlMedRadioInsu=0;
                                $TotalGnlMedKine=0;
                                    $TotalGnlMedKinePatient=0;
                                    $TotalGnlMedKineInsu=0;
                                $TotalGnlMedOrtho=0;
                                    $TotalGnlMedOrthoPatient=0;
                                    $TotalGnlMedOrthoInsu=0;
                                $TotalGnlMedConsom=0;
                                    $TotalGnlMedConsomPatient=0;
                                    $TotalGnlMedConsomInsu=0;
                                $TotalGnlMedMedoc=0;
                                    $TotalGnlMedMedocPatient=0;
                                    $TotalGnlMedMedocInsu=0;
                                $TotalGnlMedConsu=0;
                                    $TotalGnlMedConsuPatient=0;
                                    $TotalGnlMedConsuInsu=0;
                                $TotalGnlPrice=0;
                                    $TotalGnlPricePatient=0;
                                    $TotalGnlPriceInsu=0;
                                
                                $i=0;
                                $compteur=1;
                                
                                while($ligneCashierBillReport=$resultCashierBillReport->fetch())//on recupère la liste des éléments
                                {
                                    $TotalDayPrice=0;
                                    $TotalDayPricePatient=0;
                                    $TotalDayPriceInsu=0;
                                    
                                    $consult ="";
                                    $medconsu ="";
                                    $medsurge ="";
                                    $medinf ="";
                                    $medlabo ="";
                                    $medradio ="";
                                    $medkine ="";
                                    $medortho ="";
                                    $medconsom ="";
                                    $medmedoc ="";
                                    

                                    $getAssu=$connexion->prepare('SELECT *FROM assurances a WHERE a.nomassurance=:nomassurance ORDER BY a.id_assurance');
                                    $getAssu->execute(array(
                                        'nomassurance'=>$ligneCashierBillReport->nomassuranceHosp
                                    ));

                                    $getAssu->setFetchMode(PDO::FETCH_OBJ);

                                    if($ligneNomAssu=$getAssu->fetch())
                                    {
                                        $presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
                                        $nomassu=$ligneNomAssu->nomassurance;
                                    }


                                    $vouchernumHosp = $ligneCashierBillReport->vouchernumHosp;
                                    $carteassuid = $ligneCashierBillReport->idcardbillHosp;
                                    $insupercent = $ligneCashierBillReport->insupercent_hosp;
                                    $numpolice = $ligneCashierBillReport->numpolicebillHosp;
                                    $adherent =$ligneCashierBillReport->adherentbillHosp;
                                    ?>

                                    <tr style="text-align:center;">
                                        <td style="text-align:center;"><?php echo $compteur;?></td>
                                         <td>
                                            <?php
                                                $resultCashier=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u AND c.codecashier=:operation ORDER BY u.id_u DESC');
                                                $resultCashier->execute(array(
                                                'operation'=> $ligneCashierBillReport->codecashierHosp
                                                ));
                                                
                                                $resultCashier->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

                                                $comptDoct=$resultCashier->rowCount();
                                                
                                                if($ligneCashier=$resultCashier->fetch())//on recupere la liste des éléments
                                                {
                                                    $fullnameCashier = $ligneCashier->nom_u.' '.$ligneCashier->prenom_u;
                                                    
                                                    echo $fullnameCashier;
                                                }else{
                                                    echo '';
                                                }
                                                
                                            ?>
                                        </td>       
                                        <td style="text-align:center;"><?php echo $ligneCashierBillReport->id_factureHosp;?></td>
                                        <td style="text-align:center;"><?php echo $nomassu;?><br/>(<?php echo '<span style="font-weight:bold">'.$insupercent.'</span>%';?>)</td>
                                        <?php
                                            $resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
                                            $resultPatient->execute(array(
                                            'operation'=>$ligneCashierBillReport->numero
                                            ));
                                    
                                            $resultPatient->setFetchMode(PDO::FETCH_OBJ);

                                            $comptFiche=$resultPatient->rowCount();
                                            
                                            if($lignePatient=$resultPatient->fetch())
                                            {
                                                $fullname = $lignePatient->full_name;
                                                $numero = $lignePatient->numero;
                                                $sexe = $lignePatient->sexe;
                                                $dateN =$lignePatient->date_naissance;
                                                $profession=$lignePatient->profession;
                                                            
                                $old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].' ';//re?t l'ann?de naissance
                                $month=$dateN[5].''.$dateN[6].' ';//re?t le mois de naissance

                                $an= date('Y')-$old.'   ';//recupere l'? en ann?
                                $mois= date('m')-$month.'   ';//recupere l'? en mois

                                if($mois<0)
                                {
                                    $age= ($an-1).' ans ';
                                    // $an= ($an-1).' ans   '.(12+$mois).' mois';
                                    // echo $an= $an-1;

                                }else{

                                    $age= $an.' ans';
                                    // $an= $an.' ans';
                                    //$an= $an.' ans    '.(date('m')-$month).' mois';// X ans Y mois
                                    // echo $mois= date('m')-$month;
                                }
                                                
                                                
                                                // echo '<td style="text-align:center;">'.$vouchernumHosp.'</td>';  
                                                echo '<td style="text-align:center;">'.$carteassuid.'</td>';    
                                                // echo '<td style="text-align:center;">'.$numpolice.'</td>';   
                                                echo '<td style="text-align:center;">'.$age.'</td>';
                                                echo '<td style="text-align:center;">'.$sexe.'</td>';
                                                echo '<td style="text-align:center;font-weight:bold;">'.$fullname.'<br/>('.$numero.')</td>';
                                                echo '<td style="text-align:center;font-weight:normal;">'.$adherent.'</td>';
                                                echo '<td style="text-align:center;font-weight:normal;">'.$profession.'</td>';
                                            }else{
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                            }
                                            
                                        ?>
                                        
                                        <td><?php echo $ligneCashierBillReport->dateEntree.' à '.$ligneCashierBillReport->heureEntree;?></td>
                                        <td style="font-weight:bold;"><?php echo $ligneCashierBillReport->dateSortie;?></td>
                                
                                        <td style="text-align:center;">
                                        <?php
                                        
                                        $dateIn=strtotime($ligneCashierBillReport->dateEntree);
                                        $dateOut=strtotime($ligneCashierBillReport->dateSortie);
                                        
                                        $datediff= abs($dateOut - $dateIn);
                                        
                                        $nbrejrs= floor($datediff /(60*60*24));
                                        
                                        if($nbrejrs==0)
                                        {
                                            $nbrejrs=1;
                                        }
                                            echo $nbrejrs;
                                        ?>
                                        </td>

                                        <?php
                                        $prixroom=$ligneCashierBillReport->prixroom;

                                        $balance=$prixroom*$nbrejrs;

                                        $prixconsultpatient=($balance * $ligneCashierBillReport->insupercent_hosp)/100;
                                        $prixconsultinsu= $balance - $prixconsultpatient;
                                        ?>

                                       <td>
                                        <?php
                                            echo $prixroom;
                                        ?>
                                        </td> 
                                        
                                        <td style="text-align:center;">
                                                    
                                            <?php
                                            // $roomBalance = $balance + ($prixconsultpatient - $prixconsultinsu);
                                            $roomBalance = $balance;

                                            echo $roomBalance;
                                                
                                
                                            $TotalTypeConsu=0;
                                            $TotalTypeConsuPatient=0;
                                            $TotalTypeConsuInsu=0;
                                            


                                            $TotalTypeConsu=$TotalTypeConsu+$balance;
                                            $TotalTypeConsuPatient=$TotalTypeConsuPatient+$prixconsultpatient;
                                            $TotalTypeConsuInsu=$TotalTypeConsuInsu+$prixconsultinsu;
                                    
                        
                                            $TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
                                            $TotalDayPricePatient=$TotalDayPricePatient+$TotalTypeConsuPatient;
                                            $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalTypeConsuInsu;
                                        ?>
                                        
                                        </td>
                                                            
                                        <td style="text-align:center;font-weight:normal;">
                                            <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">  
                                            <?php
                                            
                                        $resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms, '.$presta_assu.' p WHERE ms.id_prestationSurge=p.id_prestation AND ms.id_factureMedSurge=:idbill AND ms.id_hospSurge=:id_hosp ORDER BY ms.id_medsurge DESC');
                                        $resultMedSurge->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp,
                                        'id_hosp'=>$ligneCashierBillReport->id_hosp
                                        ));
                                        
                                        $comptMedSurge=$resultMedSurge->rowCount();
                                        
                                        $resultMedSurge->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        
                                        $resultMedAutreSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.id_prestationSurge IS NULL AND ms.id_factureMedSurge=:idbill ORDER BY ms.id_medsurge DESC');
                                        $resultMedAutreSurge->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp
                                        ));
                                        
                                        $comptMedAutreSurge=$resultMedAutreSurge->rowCount();
                                        
                                        $resultMedAutreSurge->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        
                                        $TotalMedSurge=0;                   
                                        $TotalMedSurgePatient=0;
                                        $TotalMedSurgeInsu=0;   
                                        
                                    if($comptMedSurge!=0 or $comptMedAutreSurge!=0)
                                    {
                                        if($comptMedSurge!=0)
                                        {
                                            while($ligneMedSurge=$resultMedSurge->fetch())
                                            {
                                                $qteSurge=$ligneMedSurge->qteSurge;
                                                
                                                if($ligneMedSurge->prixprestationSurge!=0 AND $ligneMedSurge->prixrembouSurge!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedSurge->prixrembouSurge;
                                                    
                                                    $prixsurge=($ligneMedSurge->prixprestationSurge * $qteSurge) - $prixPrestaRembou;

                                                }else{
                                                    $prixsurge=$ligneMedSurge->prixprestationSurge * $qteSurge;

                                                }
                                                
                                                $prixsurgepatient=($prixsurge * $ligneMedSurge->insupercentSurge)/100;                          
                                                
                                                $prixsurgeinsu= $prixsurge - $prixsurgepatient; 
                                                
                                                if($prixsurge>=1)
                                                {
                                            ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php
                                                        echo $ligneMedSurge->nompresta;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixsurgeBalance = $prixsurge + ($prixsurgepatient + $prixsurgeinsu);

                                                        echo $prixsurgeBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                            <?php
                                                
                                                    $medsurge .= ''.$ligneMedSurge->nompresta.' ('.$prixsurgeBalance.' Rwf), ';
                                                
                                                    $TotalMedSurge=$TotalMedSurge+$prixsurge;
                                                    $TotalMedSurgePatient=$TotalMedSurgePatient+$prixsurgepatient;
                                                    $TotalMedSurgeInsu=$TotalMedSurgeInsu+$prixsurgeinsu;
                                                }
                                            }
                                        }
                                        
                                        if($comptMedAutreSurge!=0)
                                        {
                                            while($ligneMedAutreSurge=$resultMedAutreSurge->fetch())
                                            {
                                                $qteSurge=$ligneMedAutreSurge->qteSurge;
                                            
                                                if($ligneMedAutreSurge->prixautrePrestaS!=0 AND $ligneMedAutreSurge->prixrembouSurge!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedAutreSurge->prixrembouSurge;
                                                    
                                                    $prixsurge=($ligneMedAutreSurge->prixautrePrestaS * $qteSurge) - $prixPrestaRembou;

                                                }else{
                                                    $prixsurge=$ligneMedAutreSurge->prixautrePrestaS * $qteSurge;

                                                }
                                                
                                                $prixsurgepatient=($prixsurge * $ligneMedAutreSurge->insupercentSurge)/100;         
                                                $prixsurgeinsu= $prixsurge - $prixsurgepatient;                             
                                                
                                                if($prixsurge>=1)
                                                {
                                            ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php                               
                                                        echo $ligneMedAutreSurge->autrePrestaS;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixsurgeBalance = $prixsurge + ($prixsurgepatient + $prixsurgeinsu);

                                                        echo $prixsurgeBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    
                                                    $medsurge .= ''.$ligneMedAutreSurge->autrePrestaM.' ('.$prixsurgeBalance.' Rwf), ';
                                                
                                                    $TotalMedSurge=$TotalMedSurge+$prixsurge;
                                                    $TotalMedSurgePatient=$TotalMedSurgePatient+$prixsurgepatient;
                                                    $TotalMedSurgeInsu=$TotalMedSurgeInsu+$prixsurgeinsu;
                                                }
                                            }
                                        }

                                    }               
                                    ?>
                                                    
                                                <tr>
                                                    <td style="text-align:center">
                                                    <?php
                                                     // + ($TotalMedSurgePatient + $TotalMedSurgeInsu)
                                                    $TotalMedSurgeBalance = $TotalMedSurge;

                                                        echo $TotalMedSurgeBalance;

                                                        $TotalDayPrice=$TotalDayPrice+$TotalMedSurge;
                                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedSurgePatient;
                                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedSurgeInsu;
                                                    ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        
                                        </td>
                                                            
                                        <td style="text-align:center;font-weight:normal;">
                                            <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">  
                                            <?php
                                            
                                        $resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, '.$presta_assu.' p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill AND mi.id_hospInf=:id_hosp ORDER BY mi.id_medinf DESC');
                                        $resultMedInf->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp,
                                        'id_hosp'=>$ligneCashierBillReport->id_hosp
                                        ));
                                        
                                        $comptMedInf=$resultMedInf->rowCount();
                                        
                                        $resultMedInf->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        
                                        $resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.id_prestation IS NULL AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
                                        $resultMedAutreInf->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp
                                        ));
                                        
                                        $comptMedAutreInf=$resultMedAutreInf->rowCount();
                                        
                                        $resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        
                                        $TotalMedInf=0;                 
                                        $TotalMedInfPatient=0;
                                        $TotalMedInfInsu=0;                 
                                                    
                                    if($comptMedInf!=0 or $comptMedAutreInf!=0)
                                    {
                                        if($comptMedInf!=0)
                                        {
                                            while($ligneMedInf=$resultMedInf->fetch())
                                            {
                                                $qteInf=$ligneMedInf->qteInf;
                                            
                                                if($ligneMedInf->prixprestation!=0 AND $ligneMedInf->prixrembouInf!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedInf->prixrembouInf;
                                                    
                                                    $prixinf=($ligneMedInf->prixprestation * $qteInf) - $prixPrestaRembou;

                                                }else{
                                                    $prixinf=$ligneMedInf->prixprestation * $qteInf;

                                                }
                                                
                                                $prixinfpatient=($prixinf * $ligneMedInf->insupercentInf)/100;                          
                                                
                                                $prixinfinsu= $prixinf - $prixinfpatient;   
                                                
                                                if($prixinf>=1)
                                                {
                                            ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php
                                                        echo $ligneMedInf->nompresta;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixinfBalance = $prixinf + ($prixinfpatient + $prixinfinsu);

                                                        echo $prixinfBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                            <?php
                                                
                                                    $medinf .= ''.$ligneMedInf->nompresta.' ('.$prixinfBalance.' Rwf), ';
                                                
                                                    $TotalMedInf=$TotalMedInf+$prixinf;
                                                    $TotalMedInfPatient=$TotalMedInfPatient+$prixinfpatient;
                                                    $TotalMedInfInsu=$TotalMedInfInsu+$prixinfinsu;
                                                }
                                            }
                                        }
                                        
                                        if($comptMedAutreInf!=0)
                                        {
                                            while($ligneMedAutreInf=$resultMedAutreInf->fetch())
                                            {
                                                $qteInf=$ligneMedAutreInf->qteInf;
                                                
                                                if($ligneMedAutreInf->prixautrePrestaM!=0 AND $ligneMedAutreInf->prixrembouInf!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedAutreInf->prixrembouInf;
                                                    
                                                    $prixinf=($ligneMedAutreInf->prixautrePrestaM * $qteInf) - $prixPrestaRembou;

                                                }else{
                                                    $prixinf=$ligneMedAutreInf->prixautrePrestaM * $qteInf;

                                                }
                                                
                                                $prixinfpatient=($prixinf * $ligneMedAutreInf->insupercentInf)/100;         
                                                $prixinfinsu= $prixinf - $prixinfpatient;                               
                                                
                                                if($prixinf>=1)
                                                {
                                            ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php                               
                                                        echo $ligneMedAutreInf->autrePrestaM;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixinfBalance = $prixinf + ($prixinfpatient + $prixinfinsu);

                                                        echo $prixinfBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    
                                                    $medinf .= ''.$ligneMedAutreInf->autrePrestaM.' ('.$prixinfBalance.' Rwf), ';
                                                
                                                    $TotalMedInf=$TotalMedInf+$prixinf;
                                                    $TotalMedInfPatient=$TotalMedInfPatient+$prixinfpatient;
                                                    $TotalMedInfInsu=$TotalMedInfInsu+$prixinfinsu;
                                                }
                                            }
                                        }

                                    }               
                                    ?>
                                                    
                                                <tr>
                                                    <td style="text-align:center">
                                                    <?php
                                                    // + ($TotalMedInfPatient + $TotalMedInfInsu)
                                                    $TotalMedInfBalance = $TotalMedInf ;

                                                        echo $TotalMedInfBalance;

                                                        $TotalDayPrice=$TotalDayPrice+$TotalMedInf;
                                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedInfPatient;
                                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedInfInsu;
                                                    ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        
                                        </td>
                                        
                                        <td style="text-align:center;">
                                            <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
                                                        
                                            <?php
                                            
                                            $resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, '.$presta_assu.' p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill AND ml.id_hospLabo=:id_hosp ORDER BY ml.id_medlabo DESC');
                                            $resultMedLabo->execute(array(
                                            'idbill'=>$ligneCashierBillReport->id_factureHosp,
                                            'id_hosp'=>$ligneCashierBillReport->id_hosp
                                            ));
                                            
                                            $comptMedLabo=$resultMedLabo->rowCount();
                                            
                                            $resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
                                            
                                            $resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.id_prestationExa IS NULL AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
                                            $resultMedAutreLabo->execute(array(
                                            'idbill'=>$ligneCashierBillReport->id_factureHosp
                                            ));
                                            
                                            $comptMedAutreLabo=$resultMedAutreLabo->rowCount();
                                            
                                            $resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);
                                            
                                            
                                            $TotalMedLabo=0;
                                            $TotalMedLaboPatient=0;
                                            $TotalMedLaboInsu=0;
                                            
                                    if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
                                    {
                                        if($comptMedLabo!=0)
                                        {
                                            while($ligneMedLabo=$resultMedLabo->fetch())
                                            {
                                                $qteLab=$ligneMedLabo->qteLab;
                                                
                                                if($ligneMedLabo->prixprestationExa!=0 AND $ligneMedLabo->prixrembouLabo!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
                                                                    
                                                    $prixlabo=($ligneMedLabo->prixprestationExa * $qteLab) - $prixPrestaRembou;

                                                }else{
                                                    $prixlabo=$ligneMedLabo->prixprestationExa * $qteLab;

                                                }
                                                
                                                $prixlabopatient=($prixlabo * $ligneMedLabo->insupercentLab)/100;                           
                                                
                                                $prixlaboinsu= $prixlabo - $prixlabopatient;    
                                                
                                                if($prixlabo>=1)
                                                {
                                            ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php
                                                        echo $ligneMedLabo->nompresta;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixlaboBalance = $prixlabo + ($prixlabopatient + $prixlaboinsu);
                                                        echo $prixlaboBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                
                                                    $medlabo .= ''.$ligneMedLabo->nompresta.' ('.$prixlaboBalance.' Rwf), ';
                                                    
                                                    $TotalMedLabo=$TotalMedLabo+$prixlabo;
                                                    $TotalMedLaboPatient=$TotalMedLaboPatient+$prixlabopatient;
                                                    $TotalMedLaboInsu=$TotalMedLaboInsu+$prixlaboinsu;
                                                }
                                            }
                                        }
                                        
                                        if($comptMedAutreLabo!=0)
                                        {
                                            while($ligneMedAutreLabo=$resultMedAutreLabo->fetch())
                                            {
                                                $qteLab=$ligneMedAutreLabo->qteLab;
                                                
                                                if($ligneMedAutreLabo->prixautreExamen!=0 AND $ligneMedAutreLabo->prixrembouLabo!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedAutreLabo->prixrembouLabo;
                                                    
                                                    $prixlabo=($ligneMedAutreLabo->prixautreExamen * $qteLab) - $prixPrestaRembou;

                                                }else{
                                                    $prixlabo=$ligneMedAutreLabo->prixautreExamen * $qteLab;

                                                }
                                                
                                                $prixlabopatient=($prixlabo * $ligneMedAutreLabo->insupercentLab)/100;                          
                                                
                                                $prixlaboinsu= $prixlabo - $prixlabopatient;    
                                                
                                                if($prixlabo>=1)
                                                {
                                                ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php                               
                                                        echo $ligneMedAutreLabo->autreExamen;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixlaboBalance = $prixlabo + ($prixlabopatient + $prixlaboinsu);

                                                        echo $prixlaboBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                    <?php
                                            
                                                    $medlabo .= ''.$ligneMedAutreLabo->autreExamen.' ('.$prixlaboBalance.' Rwf), ';
                                                    
                                                    $TotalMedLabo=$TotalMedLabo+$prixlabo;
                                                    $TotalMedLaboPatient=$TotalMedLaboPatient+$prixlabopatient;
                                                    $TotalMedLaboInsu=$TotalMedLaboInsu+$prixlaboinsu;
                                                }
                                            
                                            }
                                        }
                                    }                   
                                    ?>
                                                                
                                                <tr>
                                                    <td style="text-align:center">
                                                    <?php
                                                    // + ($TotalMedLaboPatient + $TotalMedLaboInsu)
                                                    $TotalMedLaboBalance = $TotalMedLabo ;

                                                        echo $TotalMedLaboBalance;
                                                        
                                                        $TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
                                                        // $TotalDayPriceCCO=$TotalDayPriceCCO+$TotalMedLaboCCO;
                                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedLaboPatient;
                                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedLaboInsu;
                                                    ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        
                                        </td>
                                        
                                        <td>
                                            <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

                                        <?php
                                                
                                        $resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, '.$presta_assu.' p WHERE mr.id_prestationRadio=p.id_prestation AND mr.id_factureMedRadio=:idbill AND mr.id_hospRadio=:id_hosp ORDER BY mr.id_medradio DESC');
                                        $resultMedRadio->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp,
                                        'id_hosp'=>$ligneCashierBillReport->id_hosp
                                        ));
                                        
                                        $comptMedRadio=$resultMedRadio->rowCount();
                                        
                                        $resultMedRadio->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        $resultMedAutreRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.id_prestationRadio IS NULL AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
                                        $resultMedAutreRadio->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp
                                        ));
                                        
                                        $comptMedAutreRadio=$resultMedAutreRadio->rowCount();
                                        
                                        $resultMedAutreRadio->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        
                                        $TotalMedRadio=0;
                                        $TotalMedRadioPatient=0;
                                        $TotalMedRadioInsu=0;
                                        
                                    if($comptMedRadio!=0 or $comptMedAutreRadio!=0)
                                    {
                                        if($comptMedRadio!=0)
                                        {
                                            while($ligneMedRadio=$resultMedRadio->fetch())
                                            {
                                                $qteRad=$ligneMedRadio->qteRad;
                                                
                                                if($ligneMedRadio->prixprestationRadio!=0 AND $ligneMedRadio->prixrembouRadio!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
                                                    
                                                    $prixradio=($ligneMedRadio->prixprestationRadio * $qteRad) - $prixPrestaRembou;

                                                }else{
                                                    $prixradio=$ligneMedRadio->prixprestationRadio * $qteRad;

                                                }
                                                
                                                $prixradiopatient=($prixradio * $ligneMedRadio->insupercentRad)/100;                            
                                                
                                                $prixradioinsu= $prixradio - $prixradiopatient; 
                                                
                                                if($prixradio>=1)
                                                {
                                        ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php
                                                        echo $ligneMedRadio->nompresta;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixradioBalance = $prixradio + ($prixradiopatient + $prixradioinsu);

                                                        echo $prixradioBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                        <?php
                                        
                                                    $medradio .= ''.$ligneMedRadio->nompresta.' ('.$prixradioBalance.' Rwf), ';
                                                    
                                                    $TotalMedRadio=$TotalMedRadio+$prixradio;
                                                    $TotalMedRadioPatient=$TotalMedRadioPatient+$prixradiopatient;
                                                    
                                                    $TotalMedRadioInsu=$TotalMedRadioInsu+$prixradioinsu;
                                                }
                                            }
                                        }
                                        
                                        if($comptMedAutreRadio!=0)
                                        {
                                            while($ligneMedAutreRadio=$resultMedAutreRadio->fetch())//on recupere la liste des �l�ments
                                            {
                                                $qteRad=$ligneMedAutreRadio->qteRad;
                                                
                                                if($ligneMedAutreRadio->prixautreRadio!=0 AND $ligneMedAutreRadio->prixrembouRadio!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedAutreRadio->prixrembouRadio;
                                                    
                                                    $prixradio=($ligneMedAutreRadio->prixautreRadio * $qteRad) - $prixPrestaRembou;

                                                }else{
                                                    $prixradio=$ligneMedAutreRadio->prixautreRadio * $qteRad;

                                                }
                                                
                                                $prixradiopatient=($prixradio * $ligneMedAutreRadio->insupercentRad)/100;                           
                                                
                                                $prixradioinsu= $prixradio - $prixradiopatient; 
                                                
                                                if($prixradio>=1)
                                                {
                                        ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php                               
                                                        echo $ligneMedAutreRadio->autreRadio;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixradioBalance = $prixradio + ($prixradiopatient + $prixradioinsu);

                                                    echo $prixradioBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                    <?php
                                        
                                                    $medradio .= ''.$ligneMedAutreRadio->autreRadio.' ('.$prixradioBalance.' Rwf), ';
                                                    
                                                    $TotalMedRadio=$TotalMedRadio+$prixradio;
                                                    $TotalMedRadioPatient=$TotalMedRadioPatient+$prixradiopatient;
                                                    
                                                    $TotalMedRadioInsu=$TotalMedRadioInsu+$prixradioinsu;
                                                }
                                            }
                                        }

                                    }                   
                                    ?>                                      
                                                <tr>
                                                    <td style="text-align:center" colspan=2>
                                                    <?php
                                                     // + ($TotalMedRadioPatient + $TotalMedRadioInsu)
                                                    $TotalMedRadioBalance = $TotalMedRadio;

                                                        echo $TotalMedRadioBalance;
                                                        
                                                        $TotalDayPrice=$TotalDayPrice+$TotalMedRadio;
                                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedRadioPatient;

                                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedRadioInsu;
                                                    ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                        </td>
                                        
                                        <td>
                                            <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

                                        <?php
                                                
                                        $resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk, '.$presta_assu.' p WHERE mk.id_prestationKine=p.id_prestation AND mk.id_factureMedKine=:idbill AND mk.id_hospKine=:id_hosp, ORDER BY mk.id_medkine DESC');
                                        $resultMedKine->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp,
                                        'id_hosp'=>$ligneCashierBillReport->id_hosp
                                        ));
                                        
                                        $comptMedKine=$resultMedKine->rowCount();
                                        
                                        $resultMedKine->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        $resultMedAutreKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk WHERE mk.id_prestationKine IS NULL AND mk.id_factureMedKine==:idbill ORDER BY mk.id_medkine DESC');
                                        $resultMedAutreKine->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp
                                        ));
                                        
                                        $comptMedAutreKine=$resultMedAutreKine->rowCount();
                                        
                                        $resultMedAutreKine->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        // echo 'SELECT *FROM med_kine_hosp mk, '.$presta_assu.' p WHERE mk.id_prestationKine=p.id_prestation AND mk.id_factureMedKine=\''.$ligneCashierBillReport->id_factureHosp.'\' ORDER BY mk.id_medkine DESC';
                                        
                                        $TotalMedKine=0;
                                        $TotalMedKinePatient=0;
                                        $TotalMedKineInsu=0;
                                        
                                    if($comptMedKine!=0 OR $comptMedAutreKine!=0)
                                    {
                                    
                                        if($comptMedKine!=0)
                                        {
                                            while($ligneMedKine=$resultMedKine->fetch())
                                            {
                                                $qteKine=$ligneMedKine->qteKine;
                                                
                                                if($ligneMedKine->prixprestationKine!=0 AND $ligneMedKine->prixrembouKine!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedKine->prixrembouKine;
                                                    
                                                    $prixkine=($ligneMedKine->prixprestationKine * $qteKine) - $prixPrestaRembou;

                                                }else{                              
                                                    $prixkine=$ligneMedKine->prixprestationKine * $qteKine;

                                                }
                                                
                                                $prixkinepatient=($prixkine * $ligneMedKine->insupercentKine)/100;                          
                                                
                                                $prixkineinsu= $prixkine - $prixkinepatient;    
                                                
                                                if($prixkine>=1)
                                                {
                                        ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php
                                                        echo $ligneMedKine->nompresta;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixkineBalance = $prixkine + ($prixkinepatient + $prixkineinsu);

                                                        echo $prixkineBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                        <?php
                                        
                                                    $medkine .= ''.$ligneMedKine->nompresta.' ('.$prixkineBalance.' Rwf), ';
                                                    
                                                    $TotalMedKine=$TotalMedKine+$prixkine;
                                                    $TotalMedKinePatient=$TotalMedKinePatient+$prixkinepatient;
                                                    
                                                    $TotalMedKineInsu=$TotalMedKineInsu+$prixkineinsu;
                                                }
                                            }
                                        }
                                        
                                        if($comptMedAutreKine!=0)
                                        {
                                            while($ligneMedAutreKine=$resultMedAutreKine->fetch())//on recupere la liste des �l�ments
                                            {
                                                $qteKine=$ligneMedAutreKine->qteKine;
                                                
                                                if($ligneMedAutreKine->prixautrePrestaK!=0 AND $ligneMedAutreKine->prixrembouKine!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedAutreKine->prixrembouKine;
                                                    
                                                    $prixkine=($ligneMedAutreKine->prixautrePrestaK * $qteKine) - $prixPrestaRembou;

                                                }else{
                                                    $prixkine=$ligneMedAutreKine->prixautrePrestaK * $qteKine;

                                                }
                                                
                                                $prixkinepatient=($prixkine * $ligneMedAutreKine->insupercentKine)/100;                         
                                                
                                                $prixkineinsu= $prixkine - $prixkinepatient;    
                                                
                                                if($prixkine>=1)
                                                {
                                        ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php                               
                                                        echo $ligneMedAutreKine->autreKine;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixkineBalance = $prixkine + ($prixkinepatient + $prixkineinsu);

                                                    echo $prixkineBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                    <?php
                                        
                                                    $medkine .= ''.$ligneMedAutreKine->autreKine.' ('.$prixkineBalance.' Rwf), ';
                                                    
                                                    $TotalMedKine=$TotalMedKine+$prixkine;
                                                    $TotalMedKinePatient=$TotalMedKinePatient+$prixkinepatient;
                                                    
                                                    $TotalMedKineInsu=$TotalMedKineInsu+$prixkineinsu;
                                                }
                                            }
                                        }

                                    }                   
                                    ?>                                      
                                                <tr>
                                                    <td style="text-align:center" colspan=2>
                                                    <?php
                                                     // + ($TotalMedKinePatient + $TotalMedKineInsu)
                                                    $TotalMedKineBalance = $TotalMedKine;

                                                        echo $TotalMedKineBalance;
                                                        
                                                        $TotalDayPrice=$TotalDayPrice+$TotalMedKine;
                                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedKinePatient;

                                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedKineInsu;
                                                    ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                        </td>
                                        
                                        <td>
                                            <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

                                        <?php
                                                
                                        $resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo, '.$presta_assu.' p WHERE mo.id_prestationOrtho=p.id_prestation AND mo.id_factureMedOrtho=:idbill AND mo.id_hospOrtho=:id_hosp ORDER BY mo.id_medortho DESC');
                                        $resultMedOrtho->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp,
                                        'id_hosp'=>$ligneCashierBillReport->id_hosp
                                        ));
                                        
                                        $comptMedOrtho=$resultMedOrtho->rowCount();
                                        
                                        $resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        $resultMedAutreOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo WHERE mo.id_prestationOrtho IS NULL AND mo.id_factureMedOrtho=:idbill ORDER BY mo.id_medortho DESC');
                                        $resultMedAutreOrtho->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp
                                        ));
                                        
                                        $comptMedAutreOrtho=$resultMedAutreOrtho->rowCount();
                                        
                                        $resultMedAutreOrtho->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        
                                        $TotalMedOrtho=0;
                                        $TotalMedOrthoPatient=0;
                                        $TotalMedOrthoInsu=0;
                                        
                                    if($comptMedOrtho!=0 or $comptMedAutreOrtho!=0)
                                    {
                                        if($comptMedOrtho!=0)
                                        {
                                            while($ligneMedOrtho=$resultMedOrtho->fetch())
                                            {
                                                $qteOrtho=$ligneMedOrtho->qteOrtho;
                                                
                                                if($ligneMedOrtho->prixprestationOrtho!=0 AND $ligneMedOrtho->prixrembouOrtho!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedOrtho->prixrembouOrtho;
                                                    
                                                    $prixortho=($ligneMedOrtho->prixprestationOrtho * $qteOrtho) - $prixPrestaRembou;

                                                }else{
                                                    $prixortho=$ligneMedOrtho->prixprestationOrtho * $qteOrtho;

                                                }
                                                
                                                $prixorthopatient=($prixortho * $ligneMedOrtho->insupercentOrtho)/100;                          
                                                
                                                $prixorthoinsu= $prixortho - $prixorthopatient; 
                                                
                                                if($prixortho>=1)
                                                {
                                        ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php
                                                        echo $ligneMedOrtho->nompresta;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixorthoBalance = $prixortho + ($prixorthopatient + $prixorthoinsu);

                                                        echo $prixorthoBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                        <?php
                                        
                                                    $medortho .= ''.$ligneMedOrtho->nompresta.' ('.$prixorthoBalance.' Rwf), ';
                                                    
                                                    $TotalMedOrtho=$TotalMedOrtho+$prixortho;
                                                    $TotalMedOrthoPatient=$TotalMedOrthoPatient+$prixorthopatient;
                                                    
                                                    $TotalMedOrthoInsu=$TotalMedOrthoInsu+$prixorthoinsu;
                                                }
                                            }
                                        }
                                        
                                        if($comptMedAutreOrtho!=0)
                                        {
                                            while($ligneMedAutreOrtho=$resultMedAutreOrtho->fetch())//on recupere la liste des �l�ments
                                            {
                                                $qteOrtho=$ligneMedAutreOrtho->qteOrtho;
                                                
                                                if($ligneMedAutreOrtho->prixautrePrestaO!=0 AND $ligneMedAutreOrtho->prixrembouOrtho!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedAutreOrtho->prixrembouOrtho;
                                                    
                                                    $prixortho=($ligneMedAutreOrtho->prixautrePrestaO * $qteOrtho) - $prixPrestaRembou;

                                                }else{
                                                    $prixortho=$ligneMedAutreOrtho->prixautrePrestaO * $qteOrtho;

                                                }
                                                
                                                $prixorthopatient=($prixortho * $ligneMedAutreOrtho->insupercentOrtho)/100;                         
                                                
                                                $prixorthoinsu= $prixortho - $prixorthopatient; 
                                                
                                                if($prixortho>=1)
                                                {
                                        ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php                               
                                                        echo $ligneMedAutreOrtho->autreOrtho;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixorthoBalance = $prixortho + ($prixorthopatient + $prixorthoinsu);

                                                    echo $prixorthoBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                    <?php
                                        
                                                    $medortho .= ''.$ligneMedAutreOrtho->autreOrtho.' ('.$prixorthoBalance.' Rwf), ';
                                                    
                                                    $TotalMedOrtho=$TotalMedOrtho+$prixortho;
                                                    $TotalMedOrthoPatient=$TotalMedOrthoPatient+$prixorthopatient;
                                                    
                                                    $TotalMedOrthoInsu=$TotalMedOrthoInsu+$prixorthoinsu;
                                                }
                                            }
                                        }

                                    }                   
                                    ?>                                      
                                                <tr>
                                                    <td style="text-align:center" colspan=2>
                                                    <?php
                                                     // + ($TotalMedOrthoPatient + $TotalMedOrthoInsu)
                                                    $TotalMedOrthoBalance = $TotalMedOrtho;

                                                        echo $TotalMedOrthoBalance;
                                                        
                                                        $TotalDayPrice=$TotalDayPrice+$TotalMedOrtho;
                                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedOrthoPatient;

                                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedOrthoInsu;
                                                    ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                        </td>
                                        
                                        <td>
                                            <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

                                        <?php
                                                
                                        $resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, '.$presta_assu.' p WHERE mco.id_prestationConsom=p.id_prestation AND mco.id_factureMedConsom=:idbill AND mco.id_hospConsom=:id_hosp ORDER BY mco.id_medconsom DESC');
                                        $resultMedConsom->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp,
                                        'id_hosp'=>$ligneCashierBillReport->id_hosp
                                        ));
                                        
                                        $comptMedConsom=$resultMedConsom->rowCount();
                                        
                                        $resultMedConsom->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        $resultMedAutreConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.id_prestationConsom IS NULL AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
                                        $resultMedAutreConsom->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp
                                        ));
                                        
                                        $comptMedAutreConsom=$resultMedAutreConsom->rowCount();
                                        
                                        $resultMedAutreConsom->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        
                                        $TotalMedConsom=0;
                                        $TotalMedConsomPatient=0;
                                        $TotalMedConsomInsu=0;
                                        
                                    if($comptMedConsom!=0 or $comptMedAutreConsom!=0)
                                    {
                                        if($comptMedConsom!=0)
                                        {
                                            while($ligneMedConsom=$resultMedConsom->fetch())
                                            {
                                                $qteConsom=$ligneMedConsom->qteConsom;
                                                
                                                if($ligneMedConsom->prixprestationConsom!=0 AND $ligneMedConsom->prixrembouConsom!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
                                                    
                                                    $prixconsom=($ligneMedConsom->prixprestationConsom * $qteConsom) - $prixPrestaRembou;

                                                }else{
                                                    $prixconsom=$ligneMedConsom->prixprestationConsom * $qteConsom;

                                                }
                                                
                                                $prixconsompatient=($prixconsom * $ligneMedConsom->insupercentConsom)/100;                          
                                                
                                                $prixconsominsu= $prixconsom - $prixconsompatient;  
                                                
                                                if($prixconsom!=0)
                                                {
                                    ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php
                                                        echo $ligneMedConsom->nompresta;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php                           
                                                        echo $qteConsom;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixconsomBalance = $prixconsom + ($prixconsompatient + $prixconsominsu);

                                                        echo $prixconsomBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                        <?php
                                        
                                                    $medconsom .= ''.$ligneMedConsom->nompresta.' ('.$prixconsomBalance.' Rwf), ';
                                                    
                                                    $TotalMedConsom=$TotalMedConsom+$prixconsom;
                                                    $TotalMedConsomPatient=$TotalMedConsomPatient + $prixconsompatient;
                                                    
                                                    $TotalMedConsomInsu=$TotalMedConsomInsu + $prixconsominsu;
                                                }
                                            }
                                        }
                                        
                                        if($comptMedAutreConsom!=0)
                                        {
                                            while($ligneMedAutreConsom=$resultMedAutreConsom->fetch())
                                            {
                                                $qteConsom=$ligneMedAutreConsom->qteConsom;
                                                
                                                if($ligneMedAutreConsom->prixautreConsom!=0 AND $ligneMedAutreConsom->prixrembouConsom!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedAutreConsom->prixrembouConsom;
                                                    
                                                    $prixconsom=($ligneMedAutreConsom->prixautreConsom * $qteConsom) - $prixPrestaRembou;

                                                }else{
                                                    $prixconsom=$ligneMedAutreConsom->prixautreConsom * $qteConsom;

                                                }
                                                
                                                $prixconsompatient=($prixconsom * $ligneMedAutreConsom->insupercentConsom)/100;                         
                                                
                                                $prixconsominsu= $prixconsom - $prixconsompatient;  
                                                
                                                if($prixconsom!=0)
                                                {
                                        ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php                           
                                                        echo $ligneMedAutreConsom->autreConsom;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php                           
                                                        echo $qteConsom;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixconsomBalance = $prixconsom + ($prixconsompatient + $prixconsominsu);

                                                    echo $prixconsomBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                    <?php
                                                
                                                    $medconsom .= ''.$ligneMedAutreConsom->autreConsom.' ('.$prixconsomBalance.' Rwf), ';
                                                    
                                                    $TotalMedConsom=$TotalMedConsom+$prixconsom;
                                                    $TotalMedConsomPatient=$TotalMedConsomPatient + $prixconsompatient;
                                                    
                                                    $TotalMedConsomInsu=$TotalMedConsomInsu + $prixconsominsu;
                                                }
                                            }
                                        }

                                    }                   
                                    ?>
                                                    
                                                <tr>
                                                    <td style="text-align:center" colspan=2>
                                                    <?php
                                                     // + ($TotalMedConsomPatient + $TotalMedConsomInsu)
                                                    $TotalMedConsomBalance = $TotalMedConsom;

                                                        echo $TotalMedConsomBalance;
                                                        
                                                        $TotalDayPrice=$TotalDayPrice + $TotalMedConsom;
                                                        $TotalDayPricePatient=$TotalDayPricePatient + $TotalMedConsomPatient;
                                                        $TotalDayPriceInsu=$TotalDayPriceInsu + $TotalMedConsomInsu;
                                                    ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                        </td>
                                        
                                        <td>
                                            <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

                                        <?php
                                                
                                        $resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, '.$presta_assu.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND mdo.id_factureMedMedoc=:idbill AND mdo.id_hospMedoc=:id_hosp ORDER BY mdo.id_medmedoc DESC');
                                        $resultMedMedoc->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp,
                                        'id_hosp'=>$ligneCashierBillReport->id_hosp
                                        ));
                                        
                                        $comptMedMedoc=$resultMedMedoc->rowCount();
                                        
                                        $resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        $resultMedAutreMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.id_prestationMedoc IS NULL AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
                                        $resultMedAutreMedoc->execute(array(
                                        'idbill'=>$ligneCashierBillReport->id_factureHosp
                                        ));
                                        
                                        $comptMedAutreMedoc=$resultMedAutreMedoc->rowCount();
                                        
                                        $resultMedAutreMedoc->setFetchMode(PDO::FETCH_OBJ);
                                        
                                        
                                        $TotalMedMedoc=0;
                                        $TotalMedMedocPatient=0;
                                        $TotalMedMedocInsu=0;

                                        
                                    if($comptMedMedoc!=0 or $comptMedAutreMedoc!=0)
                                    {
                                        if($comptMedMedoc!=0)
                                        {                       
                                            while($ligneMedMedoc=$resultMedMedoc->fetch())
                                            {
                                                $qteMedoc=$ligneMedMedoc->qteMedoc;
                                                
                                                if($ligneMedMedoc->prixprestationMedoc!=0 AND $ligneMedMedoc->prixrembouMedoc!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
                                
                                                    $prixmedoc=($ligneMedMedoc->prixprestationMedoc * $qteMedoc) - $prixPrestaRembou;

                                                }else{
                                                    $prixmedoc=$ligneMedMedoc->prixprestationMedoc * $qteMedoc;

                                                }
                                                
                                                $prixmedocpatient=($prixmedoc * $ligneMedMedoc->insupercentMedoc)/100;
                                                
                                                $prixmedocinsu= $prixmedoc - $prixmedocpatient; 
                                                
                                                if($prixmedoc!=0)
                                                {
                                    ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php
                                                        echo $ligneMedMedoc->nompresta;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php                           
                                                        echo $qteMedoc;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixmedocBalance = $prixmedoc + ($prixmedocpatient + $prixmedocinsu);
                                                        echo $prixmedocBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                        <?php
                                        
                                                    $medmedoc .= ''.$ligneMedMedoc->nompresta.' ('.$prixmedocBalance.' Rwf), ';
                                                    
                                                    $TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
                                                    $TotalMedMedocPatient=$TotalMedMedocPatient + $prixmedocpatient;
                                                    $TotalMedMedocInsu= $TotalMedMedocInsu + $prixmedocinsu;
                                                    
                                                }
                                            }
                                        }
                                        
                                        if($comptMedAutreMedoc!=0)
                                        {
                                            while($ligneMedAutreMedoc=$resultMedAutreMedoc->fetch())
                                            {
                                                $qteMedoc=$ligneMedAutreMedoc->qteMedoc;
                                                
                                                if($ligneMedAutreMedoc->prixautreMedoc!=0 AND $ligneMedAutreMedoc->prixrembouMedoc!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedAutreMedoc->prixrembouMedoc;
                                
                                                    $prixmedoc=($ligneMedAutreMedoc->prixautreMedoc * $qteMedoc) - $prixPrestaRembou;

                                                }else{
                                                    $prixmedoc=$ligneMedAutreMedoc->prixautreMedoc * $qteMedoc;

                                                }
                                                
                                                $prixmedocpatient=($prixmedoc * $ligneMedAutreMedoc->insupercentMedoc)/100;                         
                                                
                                                $prixmedocinsu= $prixmedoc - $prixmedocpatient; 
                                                
                                                if($prixmedoc!=0)
                                                {
                                        ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php                           
                                                        echo $ligneMedAutreMedoc->autreMedoc;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php                           
                                                        echo $qteMedoc;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixmedocBalance = $prixmedoc + ($prixmedocpatient + $prixmedocinsu);

                                                        echo $prixmedocBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                    <?php
                                    
                                                    $medmedoc .= ''.$ligneMedAutreMedoc->autreMedoc.' ('.$prixmedocBalance.' Rwf), ';
                                                    
                                                    $TotalMedMedoc=$TotalMedMedoc+$prixmedoc;

                                                    $TotalMedMedocPatient=$TotalMedMedocPatient + $prixmedocpatient;                            
                                                    $TotalMedMedocInsu= $TotalMedMedocInsu + $prixmedocinsu;
                                                    
                                                }
                                            }
                                        }

                                    }                   
                                    ?>
                                                    
                                                <tr>
                                                    <td style="text-align:center" colspan=2>
                                                    <?php
                                                     // + ($TotalMedMedocPatient + $TotalMedMedocInsu)
                                                    $TotalMedMedocBalance = $TotalMedMedoc;

                                                        echo $TotalMedMedocBalance.'';
                                                        
                                                        $TotalDayPrice=$TotalDayPrice+$TotalMedMedoc;
                                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedMedocPatient;
                                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedMedocInsu;
                                                    ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                        </td>

                                        <td style="text-align:center;font-weight:normal;">
                                            <table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
                                                    
                                            <?php
                                            
                                            $resultMedConsu=$connexion->prepare('SELECT *FROM med_consult_hosp mc, '.$presta_assu.' p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill AND mc.id_hospMed=:id_hosp ORDER BY mc.id_medconsu DESC');
                                            $resultMedConsu->execute(array(
                                            'idbill'=>$ligneCashierBillReport->id_factureHosp,
                                            'id_hosp'=>$ligneCashierBillReport->id_hosp
                                            ));
                                            
                                            $comptMedConsu=$resultMedConsu->rowCount();
                                            
                                            $resultMedConsu->setFetchMode(PDO::FETCH_OBJ);
                                            
                                            $resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.id_factureMedConsu=:idbill AND mc.id_prestationConsu IS NULL ORDER BY mc.id_medconsu DESC');
                                            $resultMedAutreConsu->execute(array(
                                            'idbill'=>$ligneCashierBillReport->id_factureHosp
                                            ));
                                            
                                            $comptMedAutreConsu=$resultMedAutreConsu->rowCount();
                                            $resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);
                                            
                                            
                                            $TotalMedConsu=0;
                                            $TotalMedConsuPatient=0;
                                            $TotalMedConsuInsu=0;
                                            
                                    if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
                                    {
                                        if($comptMedConsu!=0)
                                        {
                                            while($ligneMedConsu=$resultMedConsu->fetch())
                                            {
                                                $qteConsu=$ligneMedConsu->qteConsu;
                                                
                                                if($ligneMedConsu->prixprestationConsu!=0 AND $ligneMedConsu->prixrembouConsu!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedConsu->prixrembouConsu;
                                                    
                                                    $prixconsu=($ligneMedConsu->prixprestationConsu * $qteConsu) - $prixPrestaRembou;

                                                }else{
                                                    $prixconsu=$ligneMedConsu->prixprestationConsu * $qteConsu;

                                                }
                                                
                                                $prixconsupatient=($prixconsu * $ligneMedConsu->insupercentServ)/100;                           
                                                
                                                $prixconsuinsu= $prixconsu - $prixconsupatient;

                                                if($prixconsu>=1)
                                                {
                                            ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php
                                                        echo $ligneMedConsu->nompresta;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixconsuBalance = $prixconsu + ($prixconsupatient + $prixconsuinsu);

                                                        echo $prixconsuBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                            <?php

                                                    $medconsu .= ''.$ligneMedConsu->nompresta.' ('.$prixconsuBalance.'), ';

                                                    $TotalMedConsu=$TotalMedConsu+$prixconsu;
                                                    $TotalMedConsuPatient=$TotalMedConsuPatient+$prixconsupatient;
                                                    $TotalMedConsuInsu=$TotalMedConsuInsu+$prixconsuinsu;
                                                
                                                }
                                            }
                                        }
                                        
                                        if($comptMedAutreConsu!=0)
                                        {
                                            while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())
                                            {
                                                $qteConsu=$ligneMedAutreConsu->qteConsu;
                                                
                                                if($ligneMedAutreConsu->prixautreConsu!=0 AND $ligneMedAutreConsu->prixrembouConsu!=0)
                                                {
                                                    $prixPrestaRembou=$ligneMedAutreConsu->prixrembouConsu;
                                                    
                                                    $prixconsu=($ligneMedAutreConsu->prixautreConsu * $qteConsu) - $prixPrestaRembou;

                                                }else{
                                                    $prixconsu=$ligneMedAutreConsu->prixautreConsu * $qteConsu;

                                                }
                                                
                                                $prixconsupatient=($prixconsu * $ligneMedAutreConsu->insupercentServ)/100;                          
                                                
                                                $prixconsuinsu= $prixconsu - $prixconsupatient; 
                                                
                                                if($prixconsu>=1)
                                                {
                                        ?>
                                                <tr style="display:none">
                                                    <td style="text-align:center">
                                                    <?php                           
                                                        echo $ligneMedAutreConsu->autreConsu;
                                                    ?>
                                                    </td>
                                                    
                                                    <td style="text-align:center">
                                                    <?php
                                                    $prixconsuBalance = $prixconsu + ($prixconsupatient + $prixconsuinsu);
                                                        echo $prixconsuBalance;
                                                    ?>
                                                    </td>
                                                </tr>
                                    <?php
                                            
                                                    $medconsu .= ''.$ligneMedAutreConsu->autreConsu.' ('.$prixconsuBalance.' Rwf), ';
                                                    
                                                    $TotalMedConsu=$TotalMedConsu+$prixconsu;
                                                    $TotalMedConsuPatient=$TotalMedConsuPatient+$prixconsupatient;
                                                    $TotalMedConsuInsu=$TotalMedConsuInsu+$prixconsuinsu;
                                                }
                                            }
                                        }

                                    }           
                                    ?>
                                                    
                                                <tr>
                                                    <td style="text-align:center">
                                                    <?php
                                                     // + ($TotalMedConsuPatient + $TotalMedConsuInsu)
                                                    $TotalMedConsuBalance = $TotalMedConsu;

                                                        echo $TotalMedConsuBalance;
                                                        
                                                        $TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
                                                        $TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsuPatient;
                                                        $TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedConsuInsu;
                                                    ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        
                                        </td>
                                        
                                        <td style="text-align:center;">
                                            <?php
                                             // + ($TotalDayPricePatient + $TotalDayPriceInsu)
                                            $TotalDayPriceBalance = $TotalDayPrice;

                                            echo $TotalDayPriceBalance;
                                            ?>
                                        </td>

                                        <td style="text-align:center;">
                                            <?php
                                            // $TotalDayPrice + 
                                            $TotalDayPricePatientBalance = $TotalDayPricePatient;

                                            echo $TotalDayPricePatientBalance;
                                            ?>
                                        </td>

                                        <td style="text-align:center;"><?php echo $TotalDayPriceInsu;?></td>
                                    </tr>
                                    <?php
                                    $TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
                                        $TotalGnlTypeConsuPatient = $TotalGnlTypeConsuPatient + $TotalTypeConsuPatient;
                                        $TotalGnlTypeConsuInsu = $TotalGnlTypeConsuInsu + $TotalTypeConsuInsu;
                                        
                                    $TotalGnlMedSurge=$TotalGnlMedSurge + $TotalMedSurge;
                                        $TotalGnlMedSurgePatient = $TotalGnlMedSurgePatient + $TotalMedSurgePatient;
                                        $TotalGnlMedSurgeInsu = $TotalGnlMedSurgeInsu + $TotalMedSurgeInsu;
                                        
                                    $TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
                                        $TotalGnlMedInfPatient = $TotalGnlMedInfPatient + $TotalMedInfPatient;
                                        $TotalGnlMedInfInsu = $TotalGnlMedInfInsu + $TotalMedInfInsu;
                                    
                                    $TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
                                        $TotalGnlMedLaboPatient=$TotalGnlMedLaboPatient + $TotalMedLaboPatient;
                                        $TotalGnlMedLaboInsu=$TotalGnlMedLaboInsu + $TotalMedLaboInsu;
                                    
                                    $TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
                                        $TotalGnlMedRadioPatient = $TotalGnlMedRadioPatient + $TotalMedRadioPatient;
                                        $TotalGnlMedRadioInsu = $TotalGnlMedRadioInsu + $TotalMedRadioInsu;
                                    
                                    $TotalGnlMedKine=$TotalGnlMedKine + $TotalMedKine;
                                        $TotalGnlMedKinePatient = $TotalGnlMedKinePatient + $TotalMedKinePatient;
                                        $TotalGnlMedKineInsu = $TotalGnlMedKineInsu + $TotalMedKineInsu;
                                    
                                    $TotalGnlMedOrtho=$TotalGnlMedOrtho + $TotalMedOrtho;
                                        $TotalGnlMedOrthoPatient = $TotalGnlMedOrthoPatient + $TotalMedOrthoPatient;
                                        $TotalGnlMedOrthoInsu = $TotalGnlMedOrthoInsu + $TotalMedOrthoInsu;
                                    
                                    $TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
                                        $TotalGnlMedConsomPatient = $TotalGnlMedConsomPatient + $TotalMedConsomPatient;
                                        $TotalGnlMedConsomInsu = $TotalGnlMedConsomInsu + $TotalMedConsomInsu;
                                    
                                    $TotalGnlMedMedoc=$TotalGnlMedMedoc + $TotalMedMedoc;
                                        $TotalGnlMedMedocPatient = $TotalGnlMedMedocPatient + $TotalMedMedocPatient;
                                        $TotalGnlMedMedocInsu = $TotalGnlMedMedocInsu + $TotalMedMedocInsu;
                                        
                                    $TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
                                        $TotalGnlMedConsuPatient = $TotalGnlMedConsuPatient + $TotalMedConsuPatient;
                                        $TotalGnlMedConsuInsu = $TotalGnlMedConsuInsu + $TotalMedConsuInsu;
                                    
                                    $TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;
                                        $TotalGnlPricePatient = $TotalGnlPricePatient + $TotalDayPricePatient;
                                        
                                        $TotalGnlPriceInsu = $TotalGnlPriceInsu + $TotalDayPriceInsu;
                                        
                                        
                                    $arrayGnlBillReport[$i][0]=$compteur;
                                    $arrayGnlBillReport[$i][1]=$ligneCashierBillReport->codecashierHosp;
                                    $arrayGnlBillReport[$i][2]=$ligneCashierBillReport->id_factureHosp;
                                    $arrayGnlBillReport[$i][3]=$nomassu;
                                    $arrayGnlBillReport[$i][4]=$carteassuid;
                                    $arrayGnlBillReport[$i][5]=$old;
                                    $arrayGnlBillReport[$i][6]=$sexe;
                                    $arrayGnlBillReport[$i][7]=$fullname;           
                                    $arrayGnlBillReport[$i][8]=$adherent;
                                    $arrayGnlBillReport[$i][9]=$profession;
                                    
                                    
                                    $arrayGnlBillReport[$i][10]=$ligneCashierBillReport->dateEntree;     
                                    $arrayGnlBillReport[$i][11]=$ligneCashierBillReport->dateSortie;        
                                    $arrayGnlBillReport[$i][12]=$nbrejrs;
                                    $arrayGnlBillReport[$i][13]=$prixroom;
                                    $arrayGnlBillReport[$i][14]=$balance;
                                    
                                    // $arrayGnlBillReport[$i][14]=$medconsu;       
                                    $arrayGnlBillReport[$i][15]=$TotalMedConsu;
                                    
                                    // $arrayGnlBillReport[$i][15]=$medinf;     
                                    $arrayGnlBillReport[$i][16]=$TotalMedInf;
                                    
                                    // $arrayGnlBillReport[$i][16]=$medlabo;        
                                    $arrayGnlBillReport[$i][17]=$TotalMedLabo;
                                    
                                    // $arrayGnlBillReport[$i][17]=$medradio;       
                                    $arrayGnlBillReport[$i][18]=$TotalMedRadio;
                                    
                                    // $arrayGnlBillReport[$i][18]=$medconsom;      
                                    $arrayGnlBillReport[$i][19]=$TotalMedConsom;
                                    
                                    // $arrayGnlBillReport[$i][19]=$medmedoc;       
                                    $arrayGnlBillReport[$i][20]=$TotalMedMedoc;
                                    
                                    $arrayGnlBillReport[$i][21]=$TotalDayPrice;
                                    $arrayGnlBillReport[$i][22]=$TotalDayPricePatient;
                                    $arrayGnlBillReport[$i][23]=$TotalDayPriceInsu;
                                    
                                    $arrayGnlBillReport[$i][24]=$insupercent.'%';
                                    
                                    $i++;
                                    
                                    $compteur++;
                    
                                }
                                ?>
                                <tr style="text-align:center;">
                                    <td colspan=13></td>
                                    <td></td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlTypeConsuBalance = $TotalGnlTypeConsu;

                                            echo $TotalGnlTypeConsuBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedSurgeBalance = $TotalGnlMedSurge;

                                            echo $TotalGnlMedSurgeBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedInfBalance = $TotalGnlMedInf;

                                            echo $TotalGnlMedInfBalance;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedLaboBalance = $TotalGnlMedLabo;

                                            echo $TotalGnlMedLaboBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedRadioBalance = $TotalGnlMedRadio;

                                            echo $TotalGnlMedRadioBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedKineBalance =  $TotalGnlMedKine;

                                            echo $TotalGnlMedKineBalance;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedOrthoBalance = $TotalGnlMedOrtho;

                                            echo $TotalGnlMedOrthoBalance;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedConsomBalance = $TotalGnlMedConsom;

                                            echo $TotalGnlMedConsomBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedMedocBalance = $TotalGnlMedMedoc;

                                            echo $TotalGnlMedMedocBalance;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;text-align:center;">
                                        <?php
                                        $TotalGnlMedConsuBalance = $TotalGnlMedConsu;

                                        echo $TotalGnlMedConsuBalance;

                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;">
                                        <?php
                                        $TotalGnlPriceBalance = $TotalGnlPrice;

                                            echo $TotalGnlPrice;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;">
                                        <?php
                                        $TotalGnlPricePatientBalance = $TotalGnlPricePatient;
                                            echo $TotalGnlPricePatient;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                    <td style="font-size: 13px; font-weight: bold;">
                                        <?php                       
                                            echo $TotalGnlPriceInsu;
                                            
                                        ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php


                        $objPHPExcel->setActiveSheetIndex(0)
                            ->fromArray($arrayGnlBillReport,'','A10')

                            ->setCellValue('O'.(10+$i).'', ''.$TotalGnlTypeConsu.'')
                            ->setCellValue('P'.(10+$i).'', ''.$TotalGnlMedConsu.'')
                            ->setCellValue('Q'.(10+$i).'', ''.$TotalGnlMedInf.'')
                            ->setCellValue('R'.(10+$i).'', ''.$TotalGnlMedLabo.'')
                            ->setCellValue('S'.(10+$i).'', ''.$TotalGnlMedRadio.'')
                            ->setCellValue('T'.(10+$i).'', ''.$TotalGnlMedConsom.'')
                            ->setCellValue('U'.(10+$i).'', ''.$TotalGnlMedMedoc.'')
                            ->setCellValue('V'.(10+$i).'', ''.$TotalGnlPrice.'')
                            ->setCellValue('W'.(10+$i).'', ''.$TotalGnlPricePatient.'')
                            ->setCellValue('X'.(10+$i).'', ''.$TotalGnlPriceInsu.'');

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
					<td style="text-align:left; margin: 10px auto auto; width:200px; background:#fff; padding-bottom:20px;">
						
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