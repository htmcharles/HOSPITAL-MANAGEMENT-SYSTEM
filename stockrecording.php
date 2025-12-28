<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");
 $id=$_SESSION['id'];
 $annee = date('Y').'-'.date('m').'-'.date('d');

//Selection of Expired Drugs
$EXp = $connexion->query("SELECT * FROM `stockin` WHERE `quantity`!=0 AND `expireddate` <= '$annee'");
$EXp->setFetchMode(PDO::FETCH_OBJ);
$GetExpiredP = $EXp->fetch();
$countD=$EXp->rowCount();
// echo $annee;

//Save New Product
 $ExistPro = array();
 $savedPro = array();

 if (isset($_POST['SaveNewProduct'])) {

     $productname = array();
     $mesure = array();
     $catgo = 22;
     
     foreach($_POST['productname'] as $proVal){
        $productname [] = $proVal;
     }

     foreach($_POST['mesure'] as $mesureVal){
        $mesure[] = $mesureVal;
     }

     for($i=0;$i<sizeof($productname);$i++){
        if(isset($productname[$i]) === true && empty($productname[$i]) === true){
            
        }else{
            $SELECTPre = $connexion->prepare('SELECT * FROM products WHERE productname = :productname');
            $SELECTPre->execute(array('productname'=>$productname[$i]));
            $SELECTPre->setFetchMode(PDO::FETCH_OBJ);

            if ($getxistPreta = $SELECTPre->fetch()) {
                $ExistPro[] =  $productname[$i];
            }else{
                $P_PriveIns = $connexion->prepare("INSERT INTO `products`(`productname`, `mesure`,`id_categopresta`) VALUES (:productname,:mesure,:id_categopresta)");
                $P_PriveIns ->execute(array('productname'=>$productname[$i],'mesure'=>$mesure[$i],'id_categopresta'=>$catgo));
                $savedPro[] = $productname[$i];
            }
        }
    }
 }


  if (isset($_POST['SaveNewConsumable'])) {

    $productname = array();
    $mesure = array();
    $catgo = 21;
    
    foreach($_POST['productname'] as $proVal){
       $productname [] = $proVal;
    }

    foreach($_POST['mesure'] as $mesureVal){
       $mesure[] = $mesureVal;
    }

    for($i=0;$i<sizeof($productname);$i++){
        if(isset($productname[$i]) === true && empty($productname[$i]) === true){

        }else{
           $SELECTPre = $connexion->prepare('SELECT * FROM products WHERE productname = :productname');
           $SELECTPre->execute(array('productname'=>$productname[$i]));
           $SELECTPre->setFetchMode(PDO::FETCH_OBJ);

           if ($getxistPreta = $SELECTPre->fetch()) {
               $ExistPro[] =  $productname[$i];
           }else{
               $P_PriveIns = $connexion->prepare("INSERT INTO `products`(`productname`, `mesure`,`id_categopresta`) VALUES (:productname,:mesure,:id_categopresta)");
               $P_PriveIns ->execute(array('productname'=>$productname[$i],'mesure'=>$mesure[$i],'id_categopresta'=>$catgo));
               $savedPro[] = $productname[$i];
           }
       }
   }
 }


 if (isset($_POST['SaveNewMaterial'])) {

    $productname = array();
    $mesure = array();
    $catgo = 23;
    
    foreach($_POST['productname'] as $proVal){
       $productname [] = $proVal;
    }

    foreach($_POST['mesure'] as $mesureVal){
       $mesure[] = $mesureVal;
    }

    for($i=0;$i<sizeof($productname);$i++){
        if(isset($productname[$i]) === true && empty($productname[$i]) === true){
           // echo $productname[$i];
       }else{
           $SELECTPre = $connexion->prepare('SELECT * FROM products WHERE productname = :productname');
           $SELECTPre->execute(array('productname'=>$productname[$i]));
           $SELECTPre->setFetchMode(PDO::FETCH_OBJ);

           if ($getxistPreta = $SELECTPre->fetch()) {
               $ExistPro[] =  $productname[$i];
           }else{
               $P_PriveIns = $connexion->prepare("INSERT INTO `products`(`productname`, `mesure`,`id_categopresta`) VALUES (:productname,:mesure,:id_categopresta)");
               $P_PriveIns ->execute(array('productname'=>$productname[$i],'mesure'=>$mesure[$i],'id_categopresta'=>$catgo));
               $savedPro[] = $productname[$i];
           }
       }
   }
}


  $AllRequisition = $connexion->prepare("SELECT * FROM requisition WHERE created_at=:created_at GROUP BY asked_by");
  $AllRequisition->execute(array('created_at'=>$annee));
  $AllRequisition->setFetchMode(PDO::FETCH_OBJ);
  $AllRequisitions = $AllRequisition->rowcount();
?>

<!doctype html>
<html lang="en">
<noscript>
	Cette page requiert du Javascript.
	Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<meta charset="utf-8"/>
    <title>Stock</title>

    <link rel="icon" href="images/favicon.ico">
    <link rel="shortcut icon" href="images/favicon.ico" />

    <!-------------------barre de menu------------------->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="source/cssmenu/styles.css">
    <script src="script.js"></script>

    <!------------------------------------>

    <link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />

    <link rel="stylesheet" href="source/cssmenu/styles.css">
    <link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">

    <script src="script.js"></script>

    <!------------------------------------>
    <link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />

    <link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->

    <link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->

    <link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
    <link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->



    <link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
    <link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
    <script src="myQuery.js"></script>

    </head>
    <style type="text/css">
    .flashing {
      -webkit-animation: glowing 1500ms infinite;
      -moz-animation: glowing 1500ms infinite;
      -o-animation: glowing 1500ms infinite;
      animation: glowing 1500ms infinite;
    }
    @-webkit-keyframes glowing {
      0% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
      50% { background-color: #FF0000; -webkit-box-shadow: 0 0 40px #FF0000; }
      100% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
    }

    @-moz-keyframes glowing {
      0% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
      50% { background-color: #FF0000; -moz-box-shadow: 0 0 40px #FF0000; }
      100% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
    }

    @-o-keyframes glowing {
      0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
      50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
      100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
    }

    @keyframes glowing {
      0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
      50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
      100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
    }
    </style>
    <script type="text/javascript">
    	
        function ShowList(list)
        {
            if( list =='Users')
            {
                document.getElementById('divMenuUser').style.display='inline';
                document.getElementById('divMenuMsg').style.display='none';
                document.getElementById('divListe').style.display='none';
                document.getElementById('listOn').style.display='inline';
                document.getElementById('listOff').style.display='none';
            }

            if( list =='Msg')
            {
                document.getElementById('divMenuMsg').style.display='inline';
                document.getElementById('divMenuUser').style.display='none';
                document.getElementById('divListe').style.display='none';
                document.getElementById('listOn').style.display='inline';
                document.getElementById('listOff').style.display='none';
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

        }

    </script>

<body>
<?php

$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true)
{
    if($status==1)
    {
        ?>

        <div class="navbar navbar-fixed-top">

            <div class="navbar-inner">

                <div class="container">

                    <img src="images/logo_large.png" class="brand" />

                    <div class="nav-collapse">

                        <ul class="nav pull-right">
                            <li class="">
                                <form method="post" action="stockrecording.php?<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">

                                    <span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>

                                    <a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>

                                    <?php
                                    if($langue == 'francais')
                                    {
                                        ?>
                                        <a href="stockrecording.php?english=english<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?>" class="btn"><?php echo getString(30);?></a>
                                        <?php
                                    }else{
                                        ?>
                                        <a href="stockrecording.php?francais=francais<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?>" class="btn"><?php echo getString(29);?></a>
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
        if(isset($_SESSION['codeS']))
        {
        ?>
            <div style="text-align:center;margin-top:20px;">
                
                <a href="report.php?Report=ok&StockChoose=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
                    <?php echo 'Reports';?>
                </a>

                <a href="closing_stock.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;font-family:'ubuntu';">
                    <?php echo 'Stock Closing';?>
                </a>

                <!-- <a href="requisition2.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large <?php if($AllRequisitions!=0){echo 'flashing';} ?>" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;font-family:'ubuntu';">
                    <?php echo 'Requisition request';?>
                  <span id="data-badge" data-badge="<?php echo $AllRequisitions; ?>"></span>
                </a> -->
            </div>
            
        <?php
        }
        ?>


        <div class="account-container" style="width:85%; text-align:center;font-family: 'ubuntu';">

            <div id='cssmenu' style="text-align:center">

                <ul style="margin-top:20px;background:none;border:none;font-family: 'ubuntu';">

                        <li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="Stock"><i class="fa fa-database fa-lg fa-fw"></i> Stock
                         <?php if($countD != 0){echo' <span class="flashing" style="color:white;border: 1px solid #ddd;border-radius: 10px;padding: 6px 6px;margin-left: 5px;"> <span style="padding: 2px 7px;color:white;font-weight:bold;background: red;border-radius: 50px;">'.$countD.'</span> Expired</span>';}else{echo' <span style="border: 1px solid #ddd;border-radius: 10px;padding: 6px 6px;margin-left: 5px;"> <span style="padding: 2px 7px;color:white;font-weight:bold;background: red;border-radius: 50px;">'.$countD.'</span> Expired</span>';}?>

                         <span style="border: 1px solid #ddd;border-radius: 10px;padding: 6px 6px;margin-left: 5px;"> <span style="padding: 2px 7px;color:white;font-weight:bold;background: red;border-radius: 50px;"><?php echo $AllRequisitions; ?></span> <small>Requisition</small></span>
                         </a></li>

                         <!-- <li style="width:33.3%;"><a href="requisition.php" style="margin-right:5px;" data-title="Make Requisition"><i class="fa fa-plus fa-lg fa-fw"></i> Make Requisition</a></li> -->

                         <li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
                </ul>

                <ul style="margin-top:20px; background:none;border:none;font-family: 'ubuntu';">
 
                    <div id="divMenuUser" style="display:none;font-family: 'ubuntu';">

                        <a href="stockrecording.php?Medicament=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo "Medicament & Consumables";?></a>

                        <a href="stockrecording.php?Laboratory=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo 'Laboratory';?></a>  
                        <a href="stockrecording.php?materials=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo 'Materials';?></a>  

                      <!--   <a href="produit.php?addNew=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo "Show/Add product";?></a>

                        <a href="newstock.php?addNew=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo getString(280);?></a> -->

                    </div>
                </ul>

                    <div style="display:none; margin-bottom:20px;font-family: 'ubuntu';" id="divMenuMsg">
 
                        <a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>

                        <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>

                        <a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

                    </div>

                    <br>
                     <?php if(isset($_GET['Medicament'])){ ?>
                        <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;font-family: 'ubuntu';">Medicament & Consumables</h3>
                        <br>
                    <div style="margin-bottom:20px;font-family: 'ubuntu';" id="divMenustock">

                        <a href="stockrecording.php?NewMedicament=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-plus-circle"></i> <?php echo "Add New Medicament & Consumables";?></a>

                        <a href="newstock.php?addNew=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-arrow-circle-up"></i> <?php echo 'Stock In';?></a>  

                        <a href="newstock.php?Medicanentstockout=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-arrow-circle-down"></i> <?php echo 'Stock Out';?></a>  

                        <a href="newstock.php?showstock=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-folder-open"></i> <?php echo 'Show Stock';?></a>  


                    </div>
                    <?php }?> 

                    <?php if(isset($_GET['Laboratory'])){ ?>
                        <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;font-family: 'ubuntu';">Laboratory</h3>
                        <br>
                    <div style="margin-bottom:20px;font-family: 'ubuntu';" id="divMenustock">

                        <a href="stockrecording.php?NewLaboratory=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo "Add New Laboratory";?></a>

                        <a href="newstock.php?addNewConsom=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo 'Stock In';?></a>  

                        <a href="newstock.php?Consomstockout=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large "><?php echo 'Stock Out';?></a>  

                        <a href="newstock.php?showstock=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-folder-open"></i> <?php echo 'Show Stock';?></a>

                    </div>
                    <?php }?>

                    <?php if(isset($_GET['materials'])){ ?>
                        <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;font-family: 'ubuntu';">Materials</h3>
                        <br>
                    <div style="margin-bottom:20px;font-family: 'ubuntu';" id="divMenustock">

                        <a href="stockrecording.php?NewMaterial=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo "Add New Material";?></a>

                        <a href="newstock.php?addNewMaterial=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo 'Stock In';?></a>  

                        <a href="newstock.php?Materialstockout=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large "><?php echo 'Stock Out';?></a>  

                        <a href="newstock.php?showstock=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-folder-open"></i> <?php echo 'Show Stock';?></a>

                    </div>
                    <?php }?>

                <br/>
            </div>


        <?php
         	$date = date("Y-m-d");
            $jour = date("d");
            $mois = date('m');
			$year = date('Y');

           // echo "date = ".$date;

            if ($jour>1) {
            	$newjour = $jour - 1 ;
            	$dateClosing = $year.'-'.$mois.'-'.$newjour;
            	//echo "dateClosing = ".$dateClosing;
            }else{
            	if ($mois>1) {
            		$newmois = $mois - 1;
            		$daysmonth= cal_days_in_month(CAL_GREGORIAN,$newmois,$year);

					if($daysmonth<10)
					{
						$daysmonth='0'.$daysmonth;
					}

					$dateClosing = $year.'-'.$newmois.'-'.$daysmonth;
            	}else{
            		$newmois = 12;
            		$newyear = $year - 1;

            		$daysmonth= cal_days_in_month(CAL_GREGORIAN,$newmois,$newyear);

					if($daysmonth<10)
					{
						$daysmonth='0'.$daysmonth;
					}

					$dateClosing = $newyear.'-'.$newmois.'-'.$daysmonth;

            	}
            }
            //echo $dateClosing;

    	  	$resultatsCount=$connexion->prepare('SELECT * FROM stockin c WHERE c.stokin=:dateClosing ORDER BY c.sid ');
    	  	$resultatsCount->execute(array(
    	  		'dateClosing'=>$dateClosing
    	  	));
            $resultatsCount->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
            $numresultatsCount = $resultatsCount->rowCount();

            if($numresultatsCount > 0){
        ?>
            <div style="margin-top:10px;display:none;" id="tableAssu">
                <h2><?php echo 'Opening Stock';?></h2>
                <table class="tablesorter" cellspacing="0" style="width: 50%">
                    <thead>
                    <tr>
                        <th>Items</th>
                        <th>Opening Stock</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    try
                    {
                        function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
                        {
                            $pagination = '';
                            $link = preg_replace('`%([^d])`', '%%$1', $link);
                            if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
                            if ( $nb_pages > 1 ) {

                                if(isset($_GET['page']))
                                {
                                    $pageTable='#tableAssu';
                                }else{
                                    $pageTable='';
                                }
                                // Lien Précédent
                                if ( $current_page > 1 )
                                {
                                    if(isset($_GET['english']))
                                    {
                                        // echo '&english='.$_GET['english'];
                                        $pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&english='.$_GET['english'].''.$pageTable.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

                                    }else{
                                        if(isset($_GET['francais']))
                                        {
                                            // echo '&francais='.$_GET['francais'];
                                            $pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&francais='.$_GET['francais'].''.$pageTable.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

                                        }else{

                                            $pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).''.$pageTable.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

                                        }
                                    }

                                }else{
                                    $pagination .= '';
                                }

                                // Lien(s) début
                                for ( $i=1 ; $i<=$firstlast ; $i++ ) {
                                    $pagination .= ' ';

                                    if(isset($_GET['english']))
                                    {
                                        // echo '&english='.$_GET['english'];
                                        $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$pageTable.'">'.$i.'</a>';

                                    }else{
                                        if(isset($_GET['francais']))
                                        {
                                            // echo '&francais='.$_GET['francais'];
                                            $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$pageTable.'">'.$i.'</a>';

                                        }else{

                                            $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$pageTable.'">'.$i.'</a>';

                                        }
                                    }

                                }

                                // ... après pages début ?
                                if ( ($current_page-$around) > $firstlast+1 )
                                    $pagination .= '<span class="current">&hellip;</span>';

                                // On boucle autour de la page courante
                                $start = ($current_page-$around)>$firstlast ? $current_page-$around : $firstlast+1;
                                $end = ($current_page+$around)<=($nb_pages-$firstlast) ? $current_page+$around : $nb_pages-$firstlast;
                                for ( $i=$start ; $i<=$end ; $i++ ) {
                                    $pagination .= ' ';
                                    if ( $i==$current_page )
                                    {
                                        $pagination .= '<span class="current">'.$i.'</span>';
                                    }else{

                                        if(isset($_GET['english']))
                                        {
                                            // echo '&english='.$_GET['english'];
                                            $pagination .= '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$pageTable.'">'.$i.'</a>';

                                        }else{
                                            if(isset($_GET['francais']))
                                            {
                                                // echo '&francais='.$_GET['francais'];
                                                $pagination .= '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$pageTable.'">'.$i.'</a>';
                                            }else{

                                                $pagination .= '<a href="'.sprintf($link, $i).''.$pageTable.'">'.$i.'</a>';
                                            }
                                        }
                                    }
                                }

                                // ... avant page nb_pages ?
                                if ( ($current_page+$around) < $nb_pages-$firstlast )
                                    $pagination .= '<span class="current">&hellip;</span>';

                                // Lien(s) fin
                                $start = $nb_pages-$firstlast+1;
                                if( $start <= $firstlast ) $start = $firstlast+1;

                                for ( $i=$start ; $i<=$nb_pages ; $i++ )
                                {
                                    $pagination .= ' ';

                                    if(isset($_GET['english']))
                                    {
                                        $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$pageTable.'">'.$i.'</a>';
                                    }else{
                                        if(isset($_GET['francais']))
                                        {
                                            // echo '&francais='.$_GET['francais'];
                                            $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$pageTable.'">'.$i.'</a>';

                                        }else{

                                            $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$pageTable.'">'.$i.'</a>';
                                        }
                                    }

                                }

                                // Lien suivant
                                if ( $current_page < $nb_pages )
                                {
                                    if(isset($_GET['english']))
                                    {
                                        // echo '&english='.$_GET['english'];

                                        $pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&english='.$_GET['english'].''.$pageTable.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';


                                    }else{
                                        if(isset($_GET['francais']))
                                        {
                                            // echo '&francais='.$_GET['francais'];

                                            $pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&francais='.$_GET['francais'].''.$pageTable.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';

                                        }else{
                                            $pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).''.$pageTable.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';

                                        }
                                    }

                                }else{
                                    $pagination .= '';
                                }
                            }
                            return $pagination;
                        }
                        // }

                        // Numero de page (1 par défaut)
                        if( isset($_GET['page']) && is_numeric($_GET['page']) )
                            $page = $_GET['page'];
                        else
                            $page = 1;

                        // Nombre d'info par page
                        $pagination =5;

                        // Numero du 1er enregistrement à lire
                        $limit_start = ($page - 1) * $pagination;


                        /*-----------Requête pour coordinateur-----------*/
                        $resultats=$connexion->query('SELECT * FROM stockin c  ORDER BY c.sid LIMIT '.$limit_start.', '.$pagination.'');

                        $resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

                        while($ligne=$resultats->fetch())//on recupere la liste des éléments
                        {
                            ?>
                            <tr style="text-align:center;">
                                <td><?php echo $ligne->product_name;?></td>
                                <td>
                                    <a href="stockrecording.php?sid=<?php echo $ligne->sid;?>&items=<?php echo $ligne->product_name;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}?>" class="btn"><i class="fa fa-pencil fa-1x fa-fw"></i></a>

                                </td>
                            </tr>
                            <?php
                        }
                        $resultats->closeCursor();

                    }

                    catch(Excepton $e)
                    {
                        echo 'Erreur:'.$e->getMessage().'<br/>';
                        echo'Numero:'.$e->getCode();
                    }


                    ?>
                    </tbody>
                </table>

                <tr>
                    <td>
                        <?php

                        $nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM stockin');
                        $nb_total=$nb_total->fetch();
                        $nb_total = $nb_total['nb_total'];
                        // Pagination
                        $nb_pages = ceil($nb_total / $pagination);
                        // Affichage
                        echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
                        ?>
                    </td>
                </tr>

            </div>
            <?php

            	}else{
            ?>
            	<div style="margin-top:10px;" id="tableAssu">
	                <!-- <h2><?php echo 'No Stock';?></h2> -->
            	</div>
            <?php
            	}

            ?>




                <?php if(sizeof($ExistPro) !=0){ ?>
                    <div class="alert alert-danger" style="font-family:'ubuntu';">
                        <h2>Following Product Already Exist in Database!</h2>
                        <hr>
                        <?php for($i=0;$i<sizeof($ExistPro);$i++){ ?>
                            <p style="font-size:18px;"><?php echo $i;?> - <?php echo $ExistPro[$i]; ?></p><br>
                        <?php }?>
                    </div>
                <?php } ?>

                <?php if(sizeof($savedPro) !=0){ ?>
                    <div class="alert alert-success" style="font-family:'ubuntu';">
                        <h2>Following Product Saved Successfuly!</h2>
                        <hr>
                        <?php for($i=0;$i<sizeof($savedPro);$i++){ ?>
                            <p style="font-size:18px;"><?php echo $i;?> - <?php echo $savedPro[$i]; ?></p><br>
                        <?php }?>
                    </div>
                <?php } ?>

            <?php
                if (isset($_GET['NewMedicament'])) {
            ?>
            <form  style="font-family:'ubuntu';" method="POST" action="stockrecording.php?NewMedicament=ok">
                <h1>New Drugs or Consumables</h1>
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th style="border-right: 1px solid white;padding-right: 5px;">Mesure</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php for($q=0;$q<7;$q++){ ?>
                        <tr>
                            <td><?php echo $q;?></td>
                            <td>
                                <input  type="text" placeholder="Product <?php echo $q; ?>" id="<?php echo $q; ?>" name="productname[]"> 
                            </td> 
                            <td>
                                <input  type="text" name="mesure[]" id="<?php echo $q; ?>"> 
                            </td>
                        </tr>
                    <?php }?>
                               
                    </tbody>
                </table>
                <br>
                 <tr>
                 <td style="width: 300px;left: 100%;">
                        <button  class="btn-large" name="SaveNewProduct"><i class="fa fa-check"></i> Save New Products</button>
                    </td>
                </tr>
            </form>
             <?php
                }else{
                     if (isset($_GET['NewLaboratory'])) {
            ?>
            <form style="font-family:'ubuntu';" method="POST" action="stockrecording.php?NewLaboratory=ok">
            <h1>New Laboratory Products</h1>
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Laboratory Product Name</th>
                            <th style="border-right: 1px solid white;padding-right: 5px;">Mesure</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php for($j=0;$j<7;$j++){ ?>
                            <tr>
                                <td><?php echo $j;?></td>
                                <td>
                                    <input  type="text" placeholder="Product <?php echo $j; ?>" name="productname[]"> 
                                </td> 
                                <td>
                                    <input  type="text" name="mesure[]" > 
                                </td>
                            </tr>
                        <?php }?>
                               
                    </tbody>
                </table>
                <br>
                <tr>
                <td style="width: 300px;left: 100%;">
                    <button  class="btn-large" name="SaveNewConsumable"><i class="fa fa-check"></i> Save New Products</button>
                </td>
            </tr>
            </form>
             <?php
                } else{
                    if (isset($_GET['NewMaterial'])) {
                ?>
                <form style="font-family:'ubuntu';" method="POST" action="stockrecording.php?NewMaterial=ok">
                <h1>New Materials</h1>
                    <table class="tablesorter" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Material Name</th>
                                <th style="border-right: 1px solid white;padding-right: 5px;">Mesure</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php for($k=0;$k<7;$k++){ ?>
                            <tr>
                                <td><?php echo $k;?></td>
                                <td>
                                    <input  type="text" placeholder="Product <?php echo $k; ?>" name="productname[]"> 
                                </td> 
                                <td>
                                    <input  type="text" name="mesure[]" > 
                                </td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                    <br>
                        <tr>
                        <td style="width: 300px;left: 100%;">
                            <button  class="btn-large" name="SaveNewMaterial"><i class="fa fa-check"></i> Save New Products</button>
                        </td>
                    </tr>
                </form>
                 <?php
                    } 
                }
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
                        alert("Your Browser does not support XMLHTTPRequest object...");
                        return null;
                    }

                    return xhr;
                }



                function ShowList(list)
                {
                    if( list =='Users')
                    {
                        document.getElementById('divMenuUser').style.display='inline';
                        document.getElementById('divMenuMsg').style.display='none';
                        document.getElementById('divListe').style.display='none';
                        document.getElementById('listOn').style.display='inline';
                        document.getElementById('listOff').style.display='none';
                    }

                    if( list =='Msg')
                    {
                        document.getElementById('divMenuMsg').style.display='inline';
                        document.getElementById('divMenuUser').style.display='none';
                        document.getElementById('divListe').style.display='none';
                        document.getElementById('listOn').style.display='inline';
                        document.getElementById('listOff').style.display='none';
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

                }


                function controlFormPassword(theForm){
                    var rapport="";

                    rapport +=controlPass(theForm.Pass);

                    if (rapport != "") {
                        alert("Please review the following fields:\n" + rapport);
                        return false;
                    }
                }


                function controlPass(fld){
                    var erreur="";

                    if(fld.value=="")
                    {
                        erreur="Your new password\n";
                        fld.style.background="rgba(0,255,0,0.3)";
                    }

                    return erreur;
                }


            </script>

        </div>

        <?php

    }else{
        echo '<script language="javascript"> alert("Vous avez été désactivé!!\n Demander à l\'administrateur de vous activer");</script>';
        echo '<script language="javascript">document.location.href="index.php"</script>';
    }
}else{
    echo '<script language="javascript"> alert("Vous n\'êtes pas connecté");</script>';
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

    echo '<script type="text/javascript"> alert("Vous venez de modifier votre mot de passe");</script>';

}
?>

<div> <!-- footer -->
    <footer style="bottom: 0px; width: 100%; text-align: center; background: #fff; border-top: 1px solid #eee; padding: 10px 0px; vertical-align: middle;">
        <p style="margin:0"><span style="color:#a00000">Clinic Plus®</span> is a product of <span style="font-style:bold;">CodeBlock</span>. ©2022-<?php echo date('Y');?> All rights reserved.</p>
    </footer>
</div> <!-- /footer -->

<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
<script type="text/javascript">
    var q;

    for(q=0;q<5;q++)
    {
        $('#idExam'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#checkExam'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#checkSousCatego'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#prixref'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#editAssu'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
    }
</script>

</body>

</html>