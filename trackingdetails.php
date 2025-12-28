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

?>

<!doctype html>
<html lang="en">
<noscript>
    Cette page requiert du Javascript.
    Veuillez l'activer pour votre navigateur
</noscript>
<head>
    <meta charset="utf-8"/>
    <title><?php echo 'Tracking Bills Errors'; ?></title>

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
    $code->setLabel('');
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
			
			<td style="text-align:right;">
				<img src="barcode/png/barcode'.$codeDoneby.'.png" style="height:auto;"/>	
			</td>
							
		</tr>
		
	</table>';

        echo $barcode;
        ?>

        <?php
        if(isset($_GET['tracking']))
        {
            ?>

            <table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
                <tr>
                    <td style="text-align:left;width:10%;">
                        <h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
                    </td>

                    <td style="text-align:left;">
                        <h2 style="font-size:150%; font-weight:600;"><?php echo $_GET['stringResult'];?> Bills errors</h2>
                    </td>

                    <td style="text-align:right">

                        <form method="post" action="trackingdetails.php?tracking=<?php echo $_GET['tracking'];?>dailydategnl=<?php echo $_GET['dailydategnl'];?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportPdf=ok" enctype="multipart/form-data" class="buttonBill">

                            <button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
                        </form>

                    </td>

                    <td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">

                        <a href="trackingbill.php" class="buttonBill">
                            <button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
                        </a>

                    </td>
                </tr>

            </table>

            <?php


            if(isset($_GET['dailydategnl']))
            {

                $dailydategnl = $_GET['dailydategnl'];
                $condition =$_GET['condition'];
                echo "SELECT *FROM bills WHERE ".$condition."";
                $resultatsC = $connexion->query("SELECT *FROM bills WHERE ".$condition."");
                $resultatsC->setFetchMode(PDO::FETCH_OBJ);
                $comptResultatsC = $resultatsC->rowCount();
                if($comptResultatsC != 0)
                {echo "string";
                ?>
                    <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;margin-top: 10px;width: 80%;">
                    <thead>
                        <tr>
                            <th style="width:5%;text-align: center;">#</th>
                            <th style="width:10%;text-align: center;">Date</th>
                            <th style="width:10%;text-align: center;">Bill number</th>
                            <th style="width:10%;text-align: center;">Patient S/N Errors</th>
                            <th style="width:10%;text-align: center;">Cashier S/N Errors</th>
                            <th style="width:10%;text-align: center;">Patient S/N True</th>
                            <th style="width:10%;text-align: center;">Cashier S/N True</th>
                        </tr>
                    </thead>
                <?php
                $nbr =1;
                while($getthem = $resultatsC->fetch()){
                    $idBill = $getthem->id_bill;
                    $numero = $getthem->numero;

                    $checkconsu = $connexion->prepare('SELECT * FROM consultions c, bills b WHERE c.id_factureConsult=b.id_bill AND c.id_factureConsult=:id_factureConsult');
                    $checkconsu->execute(array(
                        'id_factureConsult'=>$idBill
                    ));
                    $checkconsu->setFetchMode(PDO::FETCH_OBJ);
                    $nbreconsu = $checkconsu->rowCount();


                    $checkconsom = $connexion->prepare('SELECT * FROM med_consom mc, bills b WHERE mc.id_factureMedConsom=b.id_bill AND mc.id_factureMedConsom=:id_factureMedConsom');
                    $checkconsom->execute(array(
                        'id_factureMedConsom'=>$idBill
                    ));
                    $checkconsom->setFetchMode(PDO::FETCH_OBJ);
                    $nbreconsom = $checkconsom->rowCount();

                    
                    $checkconsult = $connexion->prepare('SELECT * FROM med_consult mc, bills b WHERE mc.id_factureMedConsu=b.id_bill AND mc.id_factureMedConsu=:id_factureMedConsu');
                    $checkconsult->execute(array(
                        'id_factureMedConsu'=>$idBill
                    ));
                    $checkconsult->setFetchMode(PDO::FETCH_OBJ);
                    $nbreconsult = $checkconsult->rowCount();

                    
                    $checkmdeinf = $connexion->prepare('SELECT * FROM med_inf mi, bills b WHERE mi.id_factureMedInf=b.id_bill AND mi.id_factureMedInf=:id_factureMedInf');
                    $checkmdeinf->execute(array(
                        'id_factureMedInf'=>$idBill
                    ));
                    $checkmdeinf->setFetchMode(PDO::FETCH_OBJ);
                    $nbremedinf = $checkmdeinf->rowCount();

                    
                    $checkmedkine = $connexion->prepare('SELECT * FROM med_kine mk, bills b WHERE mk.id_factureMedKine=b.id_bill AND mk.id_factureMedKine=:id_factureMedKine');
                    $checkmedkine->execute(array(
                        'id_factureMedKine'=>$idBill
                    ));
                    $checkmedkine->setFetchMode(PDO::FETCH_OBJ);
                    $nbremedkine = $checkmedkine->rowCount();


                    $checkmedlabo = $connexion->prepare('SELECT * FROM med_labo ml, bills b WHERE ml.id_factureMedLabo=b.id_bill AND ml.id_factureMedLabo=:id_factureMedLabo');
                    $checkmedlabo->execute(array(
                        'id_factureMedLabo'=>$idBill
                    ));
                    $checkmedlabo->setFetchMode(PDO::FETCH_OBJ);
                    $nbremedlabo = $checkmedlabo->rowCount();


                    $checkmedoc = $connexion->prepare('SELECT * FROM med_medoc mc, bills b WHERE mc.id_factureMedMedoc=b.id_bill AND mc.id_factureMedMedoc=:id_factureMedMedoc');
                    $checkmedoc->execute(array(
                        'id_factureMedMedoc'=>$idBill
                    ));
                    $checkmedoc->setFetchMode(PDO::FETCH_OBJ);
                    $nbremedoc = $checkmedoc->rowCount();

                    
                    $checkmedortho = $connexion->prepare('SELECT * FROM med_ortho mc, bills b WHERE mc.id_factureMedOrtho=b.id_bill AND mc.id_factureMedOrtho=:id_factureMedOrtho');
                    $checkmedortho->execute(array(
                        'id_factureMedOrtho'=>$idBill
                    ));
                    $checkmedortho->setFetchMode(PDO::FETCH_OBJ);
                    $nbremedortho = $checkmedortho->rowCount();

                    
                    $checkmdepsy = $connexion->prepare('SELECT * FROM med_psy mi, bills b WHERE mi.id_factureMedPsy=b.id_bill AND mi.id_factureMedPsy=:id_factureMedPsy');
                    $checkmdepsy->execute(array(
                        'id_factureMedPsy'=>$idBill
                    ));
                    $checkmdepsy->setFetchMode(PDO::FETCH_OBJ);
                    $nbremedpsy = $checkmdepsy->rowCount();

                    
                    $checkmedradio = $connexion->prepare('SELECT * FROM med_radio mk, bills b WHERE mk.id_factureMedRadio=b.id_bill AND mk.id_factureMedRadio=:id_factureMedRadio');
                    $checkmedradio->execute(array(
                        'id_factureMedRadio'=>$idBill
                    ));
                    $checkmedradio->setFetchMode(PDO::FETCH_OBJ);
                    $nbremedradio = $checkmedradio->rowCount();


                    $checkmedsurge = $connexion->prepare('SELECT * FROM med_surge mk, bills b WHERE mk.id_factureMedSurge=b.id_bill AND mk.id_factureMedSurge=:id_factureMedSurge');
                    $checkmedsurge->execute(array(
                        'id_factureMedSurge'=>$idBill
                    ));
                    $checkmedsurge->setFetchMode(PDO::FETCH_OBJ);
                    $nbremedsurge = $checkmedsurge->rowCount();
            ?>

            

                    <tbody>
                        <tr>
                            <td style="text-align: center;"><?php echo $nbr;?></td>
                            <td style="text-align: center;"><?php echo $getthem->datebill; ?></td>    
                            <td style="text-align: center;"><?php echo $getthem->numbill; ?></td>    
                            <td style="text-align: center;">
                                <?php 
                                    $selectinfopaterr = $connexion->prepare('SELECT * FROM patients p, utilisateurs u WHERE u.id_u=p.id_u AND p.numero=:numero');
                                    $selectinfopaterr->execute(array(
                                        'numero'=>$getthem->numero
                                    ));
                                    $selectinfopaterr->setFetchMode(PDO::FETCH_OBJ);
                                    if ($lignepaterr = $selectinfopaterr->fetch()) {
                                        echo $lignepaterr->full_name.' ('.$getthem->numero.')';
                                    }
                                    //echo $getthem->numero; 
                                ?>
                            </td>    
                            <td style="text-align: center;">
                                <?php 
                                    $selectinfocasherr = $connexion->prepare('SELECT * FROM cashiers c, utilisateurs u WHERE u.id_u=c.id_u AND c.codecashier=:numero');
                                    $selectinfocasherr->execute(array(
                                        'numero'=>$getthem->codecashier
                                    ));
                                    $selectinfocasherr->setFetchMode(PDO::FETCH_OBJ);
                                    if ($lignecasherr = $selectinfocasherr->fetch()) {
                                        echo $lignecasherr->full_name.' ('.$getthem->codecashier.')';
                                    }
                                    //echo $getthem->numero; 
                                ?>
                            </td>    
                            <td style="text-align: center;">
                                <?php 
                                    $truenumero = '';
                                    if ($nbreconsu != 0) {
                                        $ligneconsu = $checkconsu->fetch();
                                       $truenumero = $ligneconsu->numero;
                                    }
                                    if ($nbreconsom != 0) {
                                        $ligneconsom = $checkconsom->fetch();
                                        $truenumero = $ligneconsom->numero;
                                    }
                                    if ($nbreconsult != 0) {
                                        $ligneconsult = $checkconsult->fetch();
                                        $truenumero = $ligneconsult->numero;
                                    }
                                    if ($nbremedkine != 0) {
                                        $lignemedkine = $checkmedkine->fetch();
                                        $truenumero = $lignemedkine->numero;
                                    }
                                    if ($nbremedinf != 0) {
                                        $lignemedinf = $checkmedinf->fetch();
                                        $truenumero = $lignemedinf->numero;
                                    }
                                    if ($nbremedlabo != 0) {
                                        $lignemedlabo = $checkmedlabo->fetch();
                                        $truenumero = $lignemedlabo->numero;
                                    }
                                    if ($nbremedoc != 0) {
                                        $lignemedoc = $checkmedoc->fetch();
                                        $truenumero = $lignemedoc->numero;
                                    }
                                    if ($nbremedortho != 0) {
                                        $lignemedortho = $checkmedortho->fetch();
                                        $truenumero = $lignemedortho->numero;
                                    }
                                    if ($nbremedpsy != 0) {
                                        $lignemedpsy = $checkcmedpsy->fetch();
                                        $truenumero = $lignemedpsy->numero;
                                    }
                                    if ($nbremedradio != 0) {
                                        $lignemedradio = $checkmedradio->fetch();
                                        $truenumero = $lignemedradio->numero;
                                    }
                                    if ($nbremedsurge != 0) {
                                        $lignemedsurge = $checkmedsurge->fetch();
                                        $truenumero = $lignemedsurge->numero;
                                    }
                                    echo '<br>kndh'.$truenumero ;
                                    $selectinfopat = $connexion->prepare('SELECT * FROM patients p, utilisateurs u WHERE u.id_u=p.id_u AND p.numero=:numero');
                                    $selectinfopat->execute(array(
                                        'numero'=>$truenumero
                                    ));
                                    $selectinfopat->setFetchMode(PDO::FETCH_OBJ);
                                    if ($lignepat = $selectinfopat->fetch()) {
                                        echo $lignepat->full_name.' ('.$truenumero.')';
                                    }
                                    //echo $getthem->numero; 
                                ?>
                            </td>    
                            <td style="text-align: center;">
                                <?php 
                                    $truecash = '';
                                    if ($nbreconsu != 0) {
                                        $ligneconsu = $checkconsu->fetch();
                                        $truecash = $ligneconsu->codecashier;
                                    }
                                    if ($nbreconsom != 0) {
                                        $ligneconsom = $checkconsom->fetch();
                                        $truecash = $ligneconsom->codecashier;
                                    }
                                    if ($nbreconsult != 0) {
                                        $ligneconsult = $checkconsult->fetch();
                                        $truecash = $ligneconsult->codecashier;
                                    }
                                    if ($nbremedkine != 0) {
                                        $lignemedkine = $checkmedkine->fetch();
                                        $truecash = $lignemedkine->codecashier;
                                    }
                                    if ($nbremedinf != 0) {
                                        $lignemedinf = $checkmedinf->fetch();
                                        $truecash = $lignemedinf->codecashier;
                                    }
                                    if ($nbremedlabo != 0) {
                                        $lignemedlabo = $checkmedlabo->fetch();
                                        $truecash = $lignemedlabo->codecashier;
                                    }
                                    if ($nbremedoc != 0) {
                                        $lignemedoc = $checkmedoc->fetch();
                                        $truecash = $lignemedoc->codecashier;
                                    }
                                    if ($nbremedortho != 0) {
                                        $lignemedortho = $checkmedortho->fetch();
                                        $truecash = $lignemedortho->codecashier;
                                    }
                                    if ($nbremedpsy != 0) {
                                        $lignemedpsy = $checkcmedpsy->fetch();
                                        $truecash = $lignemedpsy->codecashier;
                                    }
                                    if ($nbremedradio != 0) {
                                        $lignemedradio = $checkmedradio->fetch();
                                        $truecash = $lignemedradio->codecashier;
                                    }
                                    if ($nbremedsurge != 0) {
                                        $lignemedsurge = $checkmedsurge->fetch();
                                        $truecash = $lignemedsurge->codecashier;
                                    }
                                    $selectinfocash = $connexion->prepare('SELECT * FROM cashiers c, utilisateurs u WHERE u.id_u=c.id_u AND c.codecashier=:numero');
                                    $selectinfocash->execute(array(
                                        'numero'=>$truecash
                                    ));
                                    $selectinfocash->setFetchMode(PDO::FETCH_OBJ);
                                    if ($lignecash = $selectinfocash->fetch()) {
                                        echo $lignecash->full_name.' ('.$truecash.')';
                                    }
                                    //echo $getthem->numero; 
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