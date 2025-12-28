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


if(isset($_GET['cashierUnBilledbill']))
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
    <title><?php echo 'Cashier Report#'.$sn; ?></title>

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
    $code->setLabel('# '.$sn.' #');
    $code->parse(''.$sn.'');

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
			
			<td style="text-align:right;">
				<img src="barcode/png/barcode'.$codeDoneby.'.png" style="height:auto;"/>	
			</td>
							
		</tr>
		
	</table>';

        echo $barcode;
        ?>

        <?php
        if(isset($_GET['cash']))
        {
            if(isset($_GET['cash'])){
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

            }}


            if(isset($_GET['cash']) OR isset($_SESSION['codeCash'])){
                 $codeCa=$_GET['cash'];
            }else{
                 $codeCa=$_SESSION['codeC'];
            }

            $dailydateperso=$_GET['dailydateperso'];
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
                ->setCellValue('G1', ''.$sn.'')
                ->setCellValue('F2', 'Done by')
                ->setCellValue('G2', ''.$doneby.'')
                ->setCellValue('F3', 'Date')
                ->setCellValue('G3', ''.$annee.'');

            ?>

            <table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
                <tr>
                    <td style="text-align:left;width:10%;">
                        <h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
                    </td>

                    <td style="text-align:left;">
                        <h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> | <?php if(isset($_GET['divGnlUnbilledReport'])){ echo ' UnBilled Cashier Report #';}?><?php echo $sn;?></h2>
                    </td>

                    <td style="text-align:right">

                        <button type="submit" class="btn-large buttonBill" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> CTRL+P</button>

                    </td>

                    <td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">

                        <?php if(!isset($_SESSION['codeC'])){?>
                            <a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
                                <button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
                            </a>
                        <?php }else{?>
                            <a href="report.php?coordi=<?php echo $_SESSION['id'];?><?php if(isset($_GET['divPersoBillReport'])){ echo '&report=ok';} if(isset($_GET['divPersoBillReportHosp'])){ echo '&reporthospCash=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
                                <button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
                            </a>
                        <?php }?>

                    </td>
                </tr>

            </table>

            <?php
            $userinfo = '
	
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

            echo $userinfo;



            if(isset($_GET['divGnlUnbilledReport']))
            {

                $dailydategnl = $_GET['dailydateperso'];
                // echo "SELECT *FROM consultations c WHERE c.id_uR=:oper AND c.id_factureConsult IS NULL AND ".$dailydategnl."";
                $resultatsC = $connexion->prepare("SELECT *FROM consultations c WHERE c.id_uR=:oper AND c.id_factureConsult IS NULL AND ".$dailydategnl."");
                $resultatsC->execute(array('oper'=>$_GET['id_uC']));
                $resultatsC->setFetchMode(PDO::FETCH_OBJ);
                $comptResultatsC = $resultatsC->rowCount();
                if($comptResultatsC != 0)
                {
                ?>
                    <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;margin-top: 10px;width: 80%;">
                    <thead>
                        <tr>
                            <th style="width:5%;text-align: center;">#</th>
                            <th style="width:10%;text-align: center;">Date</th>
                            <th style="width:10%;text-align: center;">Patient S/N</th>
                            <th style="width:10%;text-align: center;">Patient Insurance</th>
                            <th style="width:10%;text-align: center;">Consultation</th>
                            <th style="width:10%;text-align: center;">Nursing Care</th>
                            <th style="width:10%;text-align: center;">Exam</th>
                            <th style="width:10%;text-align: center;">Consommables</th>
                            <th style="width:10%;text-align: center;">Medicament</th>
                            <th style="width:10%;text-align: center;">Radiology</th>
                            <th style="width:10%;text-align: center;">Dental</th>
                            <th style="width:10%;text-align: center;">Other Actes</th>
                            <th style="width:20%;text-align: center;">Status</th>
                        </tr>
                    </thead>
                <?php
                $nbr =1;
                while($getthem = $resultatsC->fetch()){
                    ?>

            

                    <tbody>
                        <tr>
                            <td style="text-align: center;"><?php echo $nbr;?></td>
                            <td style="text-align: center;"><?php echo $getthem->dateconsu; ?></td>    
                            <td style="text-align: center;"><?php echo $getthem->numero; ?></td>    
                            <td style="text-align: center;"><?php echo $getthem->assuranceConsuName; ?></td>    
                            <td style="text-align: center;">
                                <?php

                                    $comptConsult = 1; 
                                    echo $comptConsult;
                                ?>
                            </td>                            

                            <td style="text-align: center;">
                                <?php

                                    $resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.numero=:num AND  mi.id_consuInf=:id_consu AND  mi.id_factureMedInf=0 ORDER BY mi.id_medinf');
                                    $resultMedInf->execute(array(
                                        'num'=>$getthem->numero,
                                        'id_consu'=>$getthem->id_consu
                                    ));
                                    
                                    $resultMedInf->setFetchMode(PDO::FETCH_OBJ);

                                    $comptMedInf=$resultMedInf->rowCount();
                                    if($comptMedInf != 0){
                                        echo '<b>'.$comptMedInf.'</b>';
                                    }else{
                                        echo "----";
                                    }
                                    // if($FetchInf = $resultMedInf->fetch()){
                                    //     echo $FetchInf->id_prestation;
                                    // }
                                 ?>
                            </td>                            

                            <td style="text-align: center;">
                                <?php

                                    $resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.numero=:num AND ml.id_consuLabo=:id_consu AND ml.id_factureMedLabo = 0 ORDER BY ml.id_medlabo');
                                    $resultMedLabo->execute(array(
                                        'num'=>$getthem->numero,
                                        'id_consu'=>$getthem->id_consu
                                    ));
                                    
                                    $resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

                                    $comptMedLabo=$resultMedLabo->rowCount();

                                     if($comptMedLabo != 0){
                                        echo '<b>'.$comptMedLabo.'</b>';
                                    }else{
                                        echo "----";
                                    }

                                    // if($FetchInf = $resultMedInf->fetch()){
                                    //     echo $comptMedLabo;
                                    // }
                                 ?>
                            </td>
                            <td style="text-align: center;">
                                <?php

                                    $resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.numero=:num AND mco.id_consuConsom=:id_consu AND mco.id_factureMedConsom=0 ORDER BY mco.id_medconsom');
                                    $resultMedConsom->execute(array(
                                        'num'=>$getthem->numero,
                                        'id_consu'=>$getthem->id_consu
                                    ));
                                    
                                    $resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

                                    $comptMedConsom=$resultMedConsom->rowCount();

                                     if($comptMedConsom != 0){
                                        echo '<b>'.$comptMedConsom.'</b>';
                                    }else{
                                        echo "----";
                                    }

                                    // if($FetchInf = $resultMedInf->fetch()){
                                    //     echo $comptMedLabo;
                                    // }
                                 ?>
                            </td>                            

                            <td style="text-align: center;">
                                <?php

                                    $resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.numero=:num AND mdo.id_consuMedoc=:id_consu AND mdo.id_factureMedMedoc=0 ORDER BY mdo.id_medmedoc');
                                    $resultMedMedoc->execute(array(
                                        'num'=>$getthem->numero,
                                        'id_consu'=>$getthem->id_consu
                                    ));
                                    
                                    $resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

                                    $comptMedMedoc=$resultMedMedoc->rowCount();

                                    if($comptMedMedoc != 0){
                                        echo '<b>'.$comptMedMedoc.'<b>';
                                    }else{
                                        echo "----";
                                    }

                                    // if($FetchInf = $resultMedInf->fetch()){
                                    //     echo $comptMedLabo;
                                    // }
                                 ?>
                            </td>                            

                            <td style="text-align: center;">
                                <?php

                                    $resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.numero=:num AND mr.id_consuRadio=:id_consu AND mr.id_factureMedRadio=0 ORDER BY mr.id_medradio');
                                    $resultMedRadio->execute(array(
                                        'num'=>$getthem->numero,
                                        'id_consu'=>$getthem->id_consu
                                    ));
                                    
                                    $resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

                                    $comptMedRadio=$resultMedRadio->rowCount();

                                    if($comptMedRadio != 0){
                                        echo '<b>'.$comptMedRadio.'</b>';
                                    }else{
                                        echo "----";
                                    }

                                    // if($FetchInf = $resultMedInf->fetch()){
                                    //     echo $comptMedLabo;
                                    // }
                                 ?>
                            </td>                            

                            <td style="text-align: center;">
                                <?php

                                    $resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.numero=:num AND ms.id_consuSurge=:id_consu AND ms.id_factureMedSurge=0 ORDER BY ms.id_medsurge');
                                    $resultMedSurge->execute(array(
                                        'num'=>$getthem->numero,
                                        'id_consu'=>$getthem->id_consu
                                    ));
                                    
                                    $resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

                                    $comptMedSurge=$resultMedSurge->rowCount();

                                    if($comptMedSurge != 0){
                                        echo '<b>'.$comptMedSurge.'<b>';
                                    }else{
                                        echo "----";
                                    }

                                    // if($FetchInf = $resultMedInf->fetch()){
                                    //     echo $comptMedLabo;
                                    // }
                                 ?>
                            </td>                            

                            <td style="text-align: center;">
                                <?php

                                    $resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.numero=:num AND mc.id_consuMed=:id_consu AND mc.id_factureMedConsu=0 ORDER BY mc.id_medconsu');
                                    $resultMedConsult->execute(array(
                                        'num'=>$getthem->numero,
                                        'id_consu'=>$getthem->id_consu
                                    ));
                                    
                                    $resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

                                    $comptMedConsult=$resultMedConsult->rowCount();

                                    if($comptMedConsult != 0){
                                        echo '<b>'.$comptMedConsult.'<b>';
                                    }else{
                                        echo "----";
                                    }

                                    // if($FetchInf = $resultMedInf->fetch()){
                                    //     echo $comptMedLabo;
                                    // }
                                 ?>
                            </td>
                            <td style="text-align: center;">
                                <?php 
                                    if($comptConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedRadio!=0 OR $comptMedSurge!=0 OR $comptMedConsult!=0 ){
                                            if(isset($_SESSION['codeCash'])){

                                        ?> 
                                        <a href="categoriesbill.php?cashier=<?php echo $_SESSION['id'];?>&cash=<?php echo $_GET['cash']; ?>&previewprint=ok&idbill=ok&idconsu=<?php echo $getthem->id_consu;?>&num=<?php echo $getthem->numero;?>&idmed=<?php echo $getthem->id_uM;?>&dateconsu=<?php echo $getthem->dateconsu;?>&idtypeconsu=<?php echo $getthem->id_typeconsult;?>&idassu=<?php echo $getthem->id_assuConsu;?>" class="btn buttonBill"><i class='fa fa-money' style="font-size: 20px;color: red;border-radius: 50px;" class="flashing"></i></a>
                                        
                                        <?php
                                        }else{
                                    ?>

                                        <a href="UnBilledView.php?audit=<?php echo $_SESSION['id'];?>&cash=<?php echo $_GET['cash']; ?>&stringResult=<?php echo $_GET['stringResult']; ?>&id_uC=<?php echo $_GET['id_uC']; ?>&dailydateperso=<?php echo $_GET['dailydateperso'];?>&divGnlUnbilledReport=ok&cashierUnBilledbill=ok&paVisit=<?php echo $_GET['paVisit'];?>&idconsu=<?php echo $getthem->id_consu;?>&num=<?php echo $getthem->numero;?>" class="btn buttonBill">View Bill <i class='fa fa-info-circle flashing' style="font-size: 20px;color: red;border-radius: 50px;" class="flashing"></i></a>

                                    <?php
                                        }
                                    }else{
                                       ?>
                                        <i class='fa fa-check-circle' style="color: green;"></i>
                                       <?php
                                    }
                                ?>
                            </td>
                        </tr>
                    </tbody>
               
                        
                        <?php
                         $nbr++;
                    }
                    ?>
                      </table>
                    <?php
                }
            ?>

           
                </div>
                <?php

            }
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