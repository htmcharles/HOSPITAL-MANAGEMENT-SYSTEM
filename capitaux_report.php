<?php
session_start();

include("connectLangues.php");
include("connect.php");


    $annee = date('Y').'-'.date('m').'-'.date('d');
    $expensesnamemsg="";
    $Amountmsg="";
    $msg="";
    if(isset($_POST['save_expenses_btn'])){
        $expensesname=$_POST['expensesname'];
        $Motif=$_POST['Motif'];
        $Amount=$_POST['Amount'];
        if(isset($_GET['codeCoord'])){
            $user=$_GET['codeCoord'];
        }else{
            $user=$_SESSION['codeCash'];
        }
        
        if (empty($expensesname)) {
            $expensesnamemsg="Please Enter Expense Name";
        }
        if (empty($Amount)) {
            $Amountmsg="Please Enter Amount";
        }
        if(!empty($expensesname) && !empty($Amount)){
        $saveexpenses=$connexion->prepare("INSERT INTO `expenses`(`expensename`, `Motif`,`amount`,`datebill`, `doneby`) VALUES (:expensesname,:Motif,:Amount,:datebill,:doneby)");
        $saveexpenses->execute(["expensesname"=>$expensesname,"Motif"=>$Motif,"Amount"=>$Amount,"datebill"=>$annee,"doneby"=>$user]);
        if($saveexpenses=True){
        $msg="Expenses Saved";
        $expensesname="";
        $Motif="";
        $Amount="";
        }else{
            $msg="Expenses Not Saved";
        }
    }
    }

?>
<!doctype html>
<html lang="en">
<noscript>
This page requires Javascript.
Please enable it in your browser.
</noscript>
<head>
    <meta charset="utf-8"/>
    <title><?php echo getString(92);?></title>
    
    <link rel="icon" href="images/favicon.ico">
    <link rel="shortcut icon" href="images/favicon.ico" />
    
            <!-------------------barre de menu------------------->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="source/cssmenu/styles.css">
    <link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
    
    <script src="script.js"></script>
            
            <!------------------------------------>
            
    <link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->
    
    <link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
    
    <link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
    <link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
    
    <link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
    <link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
    <script src="myQuery.js"></script></head>
    <body>

<div class="navbar navbar-fixed-top">
    
    <div class="navbar-inner">
        
        <div class="container">
            
            <img src="images/logo_large.png" class="brand" />  
            
            <div class="nav-collapse">
            
                <ul class="nav pull-right">
                <li class="">           
                    <form method="post" action="patients1.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}?><?php if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
                    
                    <span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
                    
                    <a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
                    
                    <?php
                    if($langue == 'francais')
                    {
                    ?>
                        <a href="expenses.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(30);?></a>
                    <?php
                    }else{
                    ?>
                        <a href="expenses.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(29);?></a>
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
?>
    <div style="text-align:center;margin-top:20px;">
        
    <?php
    if(isset($_GET['receptioniste']))
    {
    ?>
        <a href="patients1.php?caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
            <?php echo 'Caisse';?>
        </a>

    <?php
    }else{
    ?>  

        <a href="patients1.php?receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
            <?php echo 'Reception';?>
        </a>
    
        <a href="patients1_hosp.php?cashHosp=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
            <?php echo 'Hospitalisation';?>
        </a>
    
        <a href="listfacture.php?codeCash=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
            <?php echo 'Factures';?>
        </a>
    
    <?php
    }
    ?>      
        <a href="report.php?cash=<?php echo $_SESSION['codeCash'];?>&coordi=<?php echo $_SESSION['id'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="font-size:20px;height:40px; padding:10px 40px;margin-left:10px;"><?php echo getString(94);?></a>

        <a href="expenses.php?cash=<?php if(isset($_SESSION['codeCash'])){echo $_SESSION['codeCash'];}?>&coordi=<?php if(isset($_SESSION['codeC'])){echo $_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&expenses=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
            <?php echo 'Expenses';?>
        </a>
    </div>
<?php
}
?>
<?php
    if(isset($_SESSION['codeC'])){?>


        <div style="text-align:center;margin-top:20px;">
        
        <a href="report.php?coordi=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
            <?php echo 'Reports';?>
        </a>
        
        <a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
            <?php echo 'Factures';?>
        </a>

        <a href="assurances.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="assurancebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
            <?php echo 'Assurances';?>
        </a>
        <a href="expenses.php?&codecashier=<?php if(isset($_SESSION['codeCash'])){echo $_SESSION['codeCash'];} ?>&codeCoord=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?>&expenses=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
            <?php echo 'Expenses';?>
        </a>

        <a href="dettesList.php?codeCash=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
            <?php echo 'Dettes';?>
        </a>
    </div>
        <?php
    }
?>
<div id='cssmenu' style="text-align:center;margin-top:50px;">

<ul>
                <li style="width:50%;"><a href="expenses.php?iduser=<?php echo $_SESSION['id'];?>&codecashier=<?php if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}?>&codeCoord=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?>&expensesList=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="List Of Expenses"><b><i class="fa fa-list fa-lg"></i> List Of Expenses</b></a></li>
    
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
               <?php 
              }else{?>
            <li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
              <?php }?>

</ul>


<ul style="margin-top:20px; margin-bottom:20px; background:none;border:none;">

        <div id="divMenuUser" style="display:none;">
        
            <a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>" class="btn-large"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(87);?></a>
            
            <a onclick="ShowList('Liste')" id="listOn" class="btn-large" style="display:inline;"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(55);?></a>
            
            <span onclick="ShowList('ListeNon')" id="listOff" class="btn-large" style="display:none;"><i class="fa fa-eye-slash fa-lg fa-fw"></i> <?php echo getString(56);?></span>
        
        </div>

    
        <div style="display:none;" id="divMenuMsg">

            <a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
            
            <?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
            
            <a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>

        </div>

<?php
if(isset($_GET['createReportPdf']))
{
?>
<body onload="window.print()">
<?php
}
?>
<?php

       if(isset($_SESSION['codeC'])){
            $doneby=$_SESSION['codeC'];
        }else{
            if(isset($_SESSION['codeCash'])){
            $doneby=$_SESSION['codeCash'];
            }
        }

if(isset($_GET['cash']) OR isset($_SESSION['codeC']))
    {
        $result=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c, coordinateurs co WHERE (c.codecashier=:operation AND u.id_u=c.id_u) OR ( co.codecoordi=:operation AND u.id_u=co.id_u)');
        $result->execute(array(
        'operation'=>$doneby
        ));
        $result->setFetchMode(PDO::FETCH_OBJ);
        
        if($ligne=$result->fetch())
        {
            if(isset($_SESSION['codeCash'])){
                $codeCa=$ligne->codecashier;
            }else{
                $codeCa=$ligne->codecoordi;
            }
            $fullname=$ligne->full_name;
            $sexe=$ligne->sexe;
            $adresse=$ligne->province.','.$ligne->district.','.$ligne->secteur; 
    ?>

            <table style="margin:auto;">
                <tr>
                    <td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
                        <b><h2><?php if(isset($_GET['report'])){ echo 'Cashier Clinic Report';}?></h2></b>
                    </td>
                </tr>
            </table>

            <table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:80%;">
                <tr>
                    <td style="font-size:18px; text-align:center; width:33.333%;">
                        <span style="font-weight:bold;">S/N : </span></span><?php echo $codeCa;?>
                    </td>

                    <td style="font-size:18px; text-align:center; width:33.333%;">
                        <span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $fullname;?>
                    </td>

                    <td style="font-size:18px; text-align:center; width:33.333%;">
                        <span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
                        <?php
                        if($ligne->sexe=="M" OR $ligne->sexe=="m")
                        {
                            $sexe = "Male";
                        }elseif($ligne->sexe=="F" OR $ligne->sexe=="f")
                        {
                            $sexe = "Female";
                        }else{
                            $sexe="";
                        }

                        echo $sexe;
                        ?>
                    </td>
                </tr>

                <tr>
                    <td></td>
                </tr>
            </table>

            <?php
        }
        ?>
        
        <div id="selectdatePersoBillReport">
        
            <form action="capitaux_report.php?cash=<?php echo $_GET['cash'];?>&audit=<?php echo $_SESSION['id'];?><?php if(isset($_GET['reporthospCash'])){ echo '&reporthospCash='.$_GET['reporthospCash'];}else{ if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}}?>&dmacbillperso=ok&selectPersoBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
            
                <table id="dmacbillperso" style="margin:auto auto 20px">
                    <tr style="display:inline-block; margin-bottom:25px;">
                        <td>
                            <span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelectreport('dailybillPerso')" class="btn">Daily</span>
                        </td>
                        
                        <td>
                            <span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelectreport('monthlybillPerso')" class="btn">Monthly</span>
                        </td>
                        
                        <td>
                            <span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelectreport('annualybillPerso')" class="btn">Annualy</span>
                        </td>
                        
                        <td>
                            <span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelectreport('custombillPerso')" class="btn">Custom</span>
                        </td>
                    </tr>
                    
                    <tr style="visibility:visible">
                    
                        <td id="dailybillPerso" style="display:none">Select date
                            <input type="text" id="dailydatebillPerso" name="dailydatebillPerso" onclick="ds_sh(this);" value=""/>
                        
                            <button type="submit" name="searchdailybillPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
                        </td>
                        
                        <td id="monthlybillPerso" style="display:none">Select Month
                        
                            <select name="monthlydatebillPerso" id="monthlydatebillPerso" style="width:100px;height:40px;">

                                <option value='1' <?php if(date('m')=='01') {echo 'selected="selected"';}?>>January</option>
                                <option value='2' <?php if(date('m')=='02') {echo 'selected="selected"';}?>>February</option>
                                <option value='3' <?php if(date('m')=='03') {echo 'selected="selected"';}?>>March</option>
                                <option value='4' <?php if(date('m')=='04') {echo 'selected="selected"';}?>>April</option>
                                <option value='5' <?php if(date('m')=='05') {echo 'selected="selected"';}?>>May</option>
                                <option value='6' <?php if(date('m')=='06') {echo 'selected="selected"';}?>>June</option>
                                <option value='7' <?php if(date('m')=='07') {echo 'selected="selected"';}?>>July</option>
                                <option value='8' <?php if(date('m')=='08') {echo 'selected="selected"';}?>>August</option>
                                <option value='9' <?php if(date('m')=='09') {echo 'selected="selected"';}?>>September</option>
                                <option value='10' <?php if(date('m')=='10') {echo 'selected="selected"';}?>>October</option>
                                <option value='11' <?php if(date('m')=='11') {echo 'selected="selected"';}?>>November</option>
                                <option value='12' <?php if(date('m')=='12') {echo 'selected="selected"';}?>>December</option>
                            
                            </select>
                            
                            <select name="monthlydatebillPersoYear" id="monthlydatebillPersoYear" style="width:100px;height:40px;">
                            <?php 
                            for($i=2016;$i<=2030;$i++)
                            {
                            ?>
                                <option value='<?php echo $i;?>' <?php if(date('Y')==$i) {echo 'selected="selected"';}?>'><?php echo $i;?></option>
                            <?php 
                            }
                            ?>
                            </select>
                            
                            <button type="submit"  name="searchmonthlybillPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
                            
                        </td>
                        
                        <td id="annualybillPerso" style="display:none">Select Year
                        
                            <select name="annualydatebillPerso" id="annualydatebillPerso" style="width:100px;height:40px;">
                            <?php 
                            for($i=2016;$i<=2030;$i++)
                            {
                            ?>
                                <option value='<?php echo $i;?>' <?php if(date('Y')==$i) {echo 'selected="selected"';}?>><?php echo $i;?></option>
                            <?php 
                            }
                            ?>
                            </select>
                        
                            <button type="submit"  name="searchannualybillPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
                        </td>
                        
                        <td id="custombillPerso" style="display:none">
                        
                            <table>
                                <tr>
                                    <td>From</td>
                                    <td>
                                        <input type="text" id="customdatedebutbillPerso" name="customdatedebutbillPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
                                    </td>
                                
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
                                    <td>
                                        <input type="text" id="customdatefinbillPerso" name="customdatefinbillPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
                                    </td>
                                
                                    <td style="vertical-align:top;">
                                        <button type="submit"  name="searchcustombillPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                
                </table>

            </form>
            
        </div>

        <table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
            <tr>
                <td style="padding:5px;" id="ds_calclass"></td>
            </tr>
        </table>

        <?php

        if(!isset($_GET['reporthospCash']))
        {
            if(isset($_GET['dmacbillperso']) OR isset($_GET['selectPersoBill']))
            {
                $stringResult = "";
                $dailydateperso = "";
                $caVisit="gnlPersoBill";

                if(isset($_POST['searchdailybillPerso']))
                {
                    if(isset($_POST['dailydatebillPerso']))
                    {
                        $dailydateperso = 'AND datebill LIKE \''.$_POST['dailydatebillPerso'].'%\'';

                        $caVisit="dailyPersoBill";

                        $stringResult="Daily results : ".$_POST['dailydatebillPerso'];

                    }
                }

                if(isset($_POST['searchmonthlybillPerso']))
                {
                    if(isset($_POST['monthlydatebillPerso']) AND isset($_POST['monthlydatebillPersoYear']))
                    {
                        if($_POST['monthlydatebillPerso']<10)
                        {
                            $ukwezi = '0'.$_POST['monthlydatebillPerso'];
                        }else{
                            $ukwezi = $_POST['monthlydatebillPerso'];
                        }

                        $umwaka = $_POST['monthlydatebillPersoYear'];

                        $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
                        if($daysmonth<10)
                        {
                            $daysmonth='0'.$daysmonth;
                        }

                        $dailydateperso = 'AND datebill>=\''.$umwaka.'-'.$ukwezi.'-01\' AND (datebill<\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' OR datebill LIKE \''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'%\')';

                        $caVisit="monthlyPersoBill";

                        $stringResult=date("F",mktime(0,0,0,$_POST['monthlydatebillPerso'],10))." - ".$_POST['monthlydatebillPersoYear'];

                    }
                }

                if(isset($_POST['searchannualybillPerso']))
                {
                    if(isset($_POST['annualydatebillPerso']))
                    {
                        $year = $_POST['annualydatebillPerso'];

                        $dailydateperso = 'AND datebill >=\''.$year.'-01-01\' AND datebill <=\''.$year.'-12-31\'';

                        $caVisit="annualyPersoBill";

                        $stringResult="Annualy results : ".$_POST['annualydatebillPerso'];

                    }
                }

                if(isset($_POST['searchcustombillPerso']))
                {
                    if(isset($_POST['customdatedebutbillPerso']) AND isset($_POST['customdatefinbillPerso']))
                    {
                        $debut = $_POST['customdatedebutbillPerso'];
                        $fin = $_POST['customdatefinbillPerso'];

                        // $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);

                        $dailydateperso = 'AND datebill>=\''.$debut.'\' AND (datebill<\''.$fin.'\' OR datebill LIKE \''.$fin.'%\')';

                        $caVisit="customPersoBill";

                        $stringResult="Customer results : [ ".$_POST['customdatedebutbillPerso']."/".$_POST['customdatefinbillPerso']." ]";

                    }
                }

                // echo $dailydateperso;
                // echo $ukwezi.' et '.$year;
                // echo $year;

                ?>

            <div id="dmacBillReport" style="display:inline">

                <table style="width:100%;">
                    <tr>
                        <td style="text-align:center; width:33.333%;">

                        </td>

                        <td style="text-align:center; width:40%;">
                            <span style="position:relative; font-size:150%;"></i> <?php echo $stringResult;?></span>

                        </td>

                        <td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">

                        </td>
                    </tr>
                </table>

                <?php

                if (isset($_SESSION['codeC'])) {
                    $resultCashierBillReport=$connexion->prepare('SELECT * FROM expenses WHERE (doneby=:doneby OR doneby!=:doneby) '.$dailydateperso.'');
                }else{
                    $resultCashierBillReport=$connexion->prepare('SELECT * FROM expenses WHERE doneby=:doneby '.$dailydateperso.'');
                }

                $resultCashierBillReport=$connexion->prepare('SELECT * FROM expenses WHERE doneby=:doneby '.$dailydateperso.'');

                $resultCashierBillReport->execute(array(
                    'doneby'=> $doneby
                ));

                $resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

                $compCashBillReport=$resultCashierBillReport->rowCount();

                if($compCashBillReport!=0)
                {
                    ?>

                    <table style="width:100%;">
                        <tr>
                            <td style="text-align:left; width:33.333%;">

                                <a href="cashier_report.php?cash=<?php echo $_GET['cash'];?>&codecoordi=<?php if(isset($_SESSION['codeC'])){echo $_SESSION['codeC'];}?>&dailydateperso=<?php echo $dailydateperso;?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReportExpe=ok&createRN=1" style="text-align:center" id="dmacbillpersopreview">

                                    <button style="width:250px; margin:auto;" type="submit" name="printbill" id="printbill" class="btn-large-inversed">
                                        <i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
                                    </button>

                                </a>

                                <input type="hidden" name="dateprint" value="<?php  echo $annee;?>"/>

                            </td>

                            <td style="text-align:center; width:33.333%;">

                            </td>

                            <td style="font-size:18px; padding-right:20px; text-align:right; width:33.333%;">
                                <span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y',strtotime($annee));?>
                            </td>
                        </tr>
                    </table>

                    <?php
                }else{
                    ?>
                    <table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">

                        <thead>
                        <tr>
                            <th style="width:12%;text-align:center">No Report for this search</th>
                        </tr>
                        </thead>
                    </table>

                    <?php
                }
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
    if( list =='Msg')
    {
        document.getElementById('divMenuMsg').style.display='inline';
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


function ShowSelect(fld){
    
    if(fld=="dailymedicPerso")
    {
        document.getElementById('dailymedicPerso').style.display='inline-block';
    }else{
        document.getElementById('dailymedicPerso').style.display='none';
    }
    
    if(fld=="monthlymedicPerso")
    {
        document.getElementById('monthlymedicPerso').style.display='inline-block';
    }else{
        document.getElementById('monthlymedicPerso').style.display='none';
    }
    
    if(fld=="annualymedicPerso")
    {
        document.getElementById('annualymedicPerso').style.display='inline-block';
    }else{
        document.getElementById('annualymedicPerso').style.display='none';
    }
    
    if(fld=="custommedicPerso")
    {
        document.getElementById('custommedicPerso').style.display='inline-block';
    }else{
        document.getElementById('custommedicPerso').style.display='none';
    }

            /*---For Billing---*/
    
    if(fld=="dailybillPerso")
    {
        document.getElementById('dailybillPerso').style.display='inline-block';
    }else{
        document.getElementById('dailybillPerso').style.display='none';
    }
    
    if(fld=="monthlybillPerso")
    {
        document.getElementById('monthlybillPerso').style.display='inline-block';
    }else{
        document.getElementById('monthlybillPerso').style.display='none';
    }
    
    if(fld=="annualybillPerso")
    {
        document.getElementById('annualybillPerso').style.display='inline-block';
    }else{
        document.getElementById('annualybillPerso').style.display='none';
    }
    
    if(fld=="custombillPerso")
    {
        document.getElementById('custombillPerso').style.display='inline-block';
    }else{
        document.getElementById('custombillPerso').style.display='none';
    }

}


function ShowSelectGnl(fld){
        
    /*---------For Gnl Medic report---------------*/
    
    if(fld=="dailymedicGnl")
    {
        document.getElementById('dailymedicGnl').style.display='inline-block';
    }else{
        document.getElementById('dailymedicGnl').style.display='none';
    }
    
    if(fld=="monthlymedicGnl")
    {
        document.getElementById('monthlymedicGnl').style.display='inline-block';
    }else{
        document.getElementById('monthlymedicGnl').style.display='none';
    }
    
    if(fld=="annualymedicGnl")
    {
        document.getElementById('annualymedicGnl').style.display='inline-block';
    }else{
        document.getElementById('annualymedicGnl').style.display='none';
    }
    
    if(fld=="custommedicGnl")
    {
        document.getElementById('custommedicGnl').style.display='inline-block';
    }else{
        document.getElementById('custommedicGnl').style.display='none';
    }
}


function ShowSelectreport(fld){
    
    if(fld=="dailybillPerso")
    {
        document.getElementById('dailybillPerso').style.display='inline-block';
    }else{
        document.getElementById('dailybillPerso').style.display='none';
    }
    
    if(fld=="monthlybillPerso")
    {
        document.getElementById('monthlybillPerso').style.display='inline-block';
    }else{
        document.getElementById('monthlybillPerso').style.display='none';
    }
    
    if(fld=="annualybillPerso")
    {
        document.getElementById('annualybillPerso').style.display='inline-block';
    }else{
        document.getElementById('annualybillPerso').style.display='none';
    }
    
    if(fld=="custombillPerso")
    {
        document.getElementById('custombillPerso').style.display='inline-block';
    }else{
        document.getElementById('custombillPerso').style.display='none';
    }
    
}


function ShowSelectreportGnl(fld){
    
    /*---------For Gnl Bill report---------------*/
    
    
    if(fld=="dailybillGnl")
    {
        document.getElementById('dailybillGnl').style.display='inline-block';
    }else{
        document.getElementById('dailybillGnl').style.display='none';
    }
    
    if(fld=="monthlybillGnl")
    {
        document.getElementById('monthlybillGnl').style.display='inline-block';
    }else{
        document.getElementById('monthlybillGnl').style.display='none';
    }
    
    if(fld=="annualybillGnl")
    {
        document.getElementById('annualybillGnl').style.display='inline-block';
    }else{
        document.getElementById('annualybillGnl').style.display='none';
    }
    
    if(fld=="custombillGnl")
    {
        document.getElementById('custombillGnl').style.display='inline-block';
    }else{
        document.getElementById('custombillGnl').style.display='none';
    }

    /*---------For Gnl Bill report---------------*/
    
    
    if(fld=="dailybillPerso")
    {
        document.getElementById('dailybillGnl').style.display='inline-block';
    }else{
        document.getElementById('dailybillGnl').style.display='none';
    }
    
    if(fld=="monthlybillGnl")
    {
        document.getElementById('monthlybillGnl').style.display='inline-block';
    }else{
        document.getElementById('monthlybillGnl').style.display='none';
    }
    
    if(fld=="annualybillGnl")
    {
        document.getElementById('annualybillGnl').style.display='inline-block';
    }else{
        document.getElementById('annualybillGnl').style.display='none';
    }
    
    if(fld=="custombillGnl")
    {
        document.getElementById('custombillGnl').style.display='inline-block';
    }else{
        document.getElementById('custombillGnl').style.display='none';
    }
}


function ShowDivReport(fld){

    if(fld=="divPersoMedicReport")
    {       
        document.getElementById('divPersoBillReport').style.display='none';
        document.getElementById('persobillingstring').style.display='none';
        document.getElementById('individualstring').style.display='none';
        document.getElementById('billingpersopreview').style.display='none';
        document.getElementById('selectdatePersoBillReport').style.display='none';
        document.getElementById('dmacBillReport').style.display='none';
        document.getElementById('dmacMedicReport').style.display='none';
        document.getElementById('dmacmedicalpersopreview').style.display='none';
        document.getElementById('dmacbillpersopreview').style.display='none';
    }
    
    if(fld=="divPersoBillReport")
    {
        document.getElementById('divPersoMedicReport').style.display='none';
        document.getElementById('persomedicalstring').style.display='none';
        document.getElementById('individualstring').style.display='none';
        document.getElementById('medicalpersopreview').style.display='none';
        document.getElementById('selectdatePersoMedicReport').style.display='none';
        document.getElementById('dmacMedicReport').style.display='none';
        document.getElementById('dmacBillReport').style.display='none';
        document.getElementById('dmacmedicalpersopreview').style.display='none';
        document.getElementById('dmacbillpersopreview').style.display='none';
        
    }
    
    if(fld=="divGnlMedicReport")
    {
        document.getElementById('divGnlBillReport').style.display='none';
        document.getElementById('gnlbillstring').style.display='none';
        document.getElementById('gnlmedicalstring').style.display='none';
        document.getElementById('billinggnlpreview').style.display='none';
        document.getElementById('selectdateGnlMedicReport').style.display='inline';
        document.getElementById('dmacMedicReport').style.display='none';
        document.getElementById('dmacBillReport').style.display='none';
        document.getElementById('dmacmedicalgnlpreview').style.display='none';
        document.getElementById('dmacbillgnlpreview').style.display='none';
    }
    
    if(fld=="divGnlBillReport")
    {
        document.getElementById('divGnlMedicReport').style.display='none';
        document.getElementById('gnlmedicalstring').style.display='none';
        document.getElementById('gnlbillstring').style.display='none';
        document.getElementById('medicalgnlpreview').style.display='none';
        document.getElementById('dmacMedicReport').style.display='none';
        document.getElementById('dmacBillReport').style.display='none';
        document.getElementById('dmacmedicalgnlpreview').style.display='none';
        document.getElementById('dmacbillgnlpreview').style.display='none';
    }
    
}

</script>
</body>
</html>