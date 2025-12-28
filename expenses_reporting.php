<?php
session_start();

include("connectLangues.php");
include("connect.php");

$annee = date('Y').'-'.date('m').'-'.date('d');

if(isset($_GET['num']))
{

	$result=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
	$result->execute(array(
	'operation'=>$_GET['num']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
	$num=$ligne->numero;
	$nom_uti=$ligne->nom_u;
	$prenom_uti=$ligne->prenom_u;
	$sexe=$ligne->sexe;
	$dateN=$ligne->date_naissance;
	$province=$ligne->province;
	$district=$ligne->district;
	$secteur=$ligne->secteur;
	$profession=$ligne->profession;
	$site=$_GET['num'];
	}
	$result->closeCursor();

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

}


if(isset($_GET['cash']))
{

	$result=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE c.codecashier=:operation AND u.id_u=c.id_u');
	$result->execute(array(
	'operation'=>$_GET['cash']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
		$cash=$ligne->codecashier;
		$nom_uti=$ligne->nom_u;
		$prenom_uti=$ligne->prenom_u;
		$sexe=$ligne->sexe;
		$province=$ligne->province;
		$district=$ligne->district;
		$secteur=$ligne->secteur;
		$site=$_GET['cash'];
	}
	$result->closeCursor();

}
if(isset($_GET['codeI']))
{

	$result=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE i.codecashier=:operation AND u.id_u=c.id_u');
	$result->execute(array(
	'operation'=>$_GET['codeI']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
		$codeI=$ligne->codeinfirmier;
		$nom_uti=$ligne->nom_u;
		$prenom_uti=$ligne->prenom_u;
		$sexe=$ligne->sexe;
		$province=$ligne->province;
		$district=$ligne->district;
		$secteur=$ligne->secteur;
		$site=$_GET['codeI'];
	}
	$result->closeCursor();

}

?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title>Reports</title>
	
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
	
		<!-------------------calendrier------------------->
	
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>	
	<link href="css/form-signin.css" rel="stylesheet" type="text/css">

	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<script src="myQuery.js"></script>
	<script type="text/javascript">

function controlFormCustom(theForm){
	var rapport="";
	
	rapport +=controlDateDebut(theForm.customdatedebutPerso);
	rapport +=compareDateDebuFin(theForm.customdatedebutPerso,theForm.customdatefinPerso);
	// rapport +=compareHeures(theForm.heurerdvDebut,theForm.heurerdvFin,theForm.minrdvDebut,theForm.minrdvFin);
	
		if (rapport != "") {
		alert("Check error please :\n" + rapport);
					return false;
		 }
}

function controlDateDebut(fld){
	var erreur="";
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	if(fld.value.trim()==""){
		erreur="Begining date";
		fld.style.background="cyan";
	}
	return erreur;	
} 

function compareDateDebuFin(fld1,fld2){
	var erreur="";
	var dateDebut=fld1.value;
	var dateFin=fld2.value;
	

	if(dateFin != "")
	{
		if(dateDebut>dateFin)
		{
			fld1.style.background='yellow';
			fld2.style.background='yellow';
			
			erreur="\nInvalid Search\n Check the dates input\n";
		}
	}

	return erreur;	
}


</script>

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

$id=$_SESSION['id'];

$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");
$sqlD=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");
$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");
$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");

$comptidM=$sqlM->rowCount();
$comptidD=$sqlD->rowCount();
$comptidC=$sqlC->rowCount();
$comptidI=$sqlI->rowCount();

// echo $_SESSION[''];

$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND ($comptidM!=0 OR $comptidD!=0 OR $comptidC!=0 OR $comptidI!=0))
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
					<form method="post" action="report.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}} if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}if(isset($_GET['audit'])){ echo '&audit='.$_GET['audit'];} if(isset($_GET['cash'])){ echo '&cash='.$_GET['cash'];} if(isset($_GET['med'])){ echo '&med='.$_GET['med'];}if(isset($_GET['inf'])){ echo '&inf='.$_GET['inf'];}if(isset($_GET['lab'])){ echo '&lab='.$_GET['lab'];}if(isset($_GET['rec'])){ echo '&rec='.$_GET['rec'];}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="report.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}if(isset($_GET['audit'])){ echo '&audit='.$_GET['audit'];}if(isset($_GET['cash'])){ echo '&cash='.$_GET['cash'];}if(isset($_GET['med'])){ echo '&med='.$_GET['med'];}if(isset($_GET['inf'])){ echo '&inf='.$_GET['inf'];}if(isset($_GET['lab'])){ echo '&lab='.$_GET['lab'];}if(isset($_GET['rec'])){ echo '&rec='.$_GET['rec'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="report.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}if(isset($_GET['audit'])){ echo '&audit='.$_GET['audit'];} if(isset($_GET['cash'])){ echo '&cash='.$_GET['cash'];} if(isset($_GET['med'])){ echo '&med='.$_GET['med'];}if(isset($_GET['inf'])){ echo '&inf='.$_GET['inf'];}if(isset($_GET['lab'])){ echo '&lab='.$_GET['lab'];}if(isset($_GET['rec'])){ echo '&rec='.$_GET['rec'];}?>" class="btn"><?php echo getString(29);?></a>
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
if(isset($_SESSION['codeC']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Factures';?>
		</a>
		<a href="dettesList.php?codeCash=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
			<?php echo 'Dettes';?>
		</a>
	
	</div>
	
<?php
}
?>

<?php
if(isset($_SESSION['codeI']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?codeI=<?php echo $_SESSION['codeI'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinic';?>
		</a>
		<a href="patients1_hosp.php?codeI=<?php echo $_SESSION['codeI'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
			<?php echo 'Hospitalisation';?>
		</a>
	
	</div>
	
<?php
}
?>

<div class="account-container" style="width:90%; text-align:center;">

	<div id='cssmenu' style="text-align:center" class="menu">

	<?php
	if(isset($_SESSION['codeC']))
	{
	?>
		<ul>
			<?php
			if(isset($_GET['num']) OR isset($_GET['gnlreport']) OR isset($_GET['cash']) OR isset($_GET['med']) OR isset($_GET['gnlmed']) OR isset($_GET['inf']) OR isset($_GET['lab']) OR isset($_GET['rec']) OR isset($_GET['gnlreporthosp']))
			{
			?>
				<li style="width:33.33%;"><a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Utilisateurs"><i class="fa fa-users fa-lg fa-fw"></i> Utilisateurs</a></li>
			
				<li style="width:33.33%;"><a href="report.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-left:5px;margin-right:5px;" data-title="Select report"><i class="fa fa-file fa-lg fa-fw"></i> Select reports</a></li>
				
				<li style="width:33.33%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
			<?php
			}else{
			?>
			
				<li style="width:50%;"><a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Utilisateurs"><i class="fa fa-users fa-lg fa-fw"></i> Utilisateurs</a></li>
			
				<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
			<?php
			}
			?>
			
		</ul>

		<ul style="margin-top:20px; margin-bottom:20px; background:none;border:none;">
			
				<div style="display:none;" id="divMenuMsg">

					<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
					
					<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
					
					<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>

				</div>

		</ul>
	<?php
	}
	?>
	
	<?php
	if(isset($_SESSION['codeM']))
	{
	?>
		<ul style="margin-top:20px;background:none;border:none;">

			<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><b><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</b></a></li>
			
			<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49) ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49) ?></a></li>


		</ul>


		<ul style="margin-top:20px; margin-bottom:20px; background:none; border:none;">

			
			<div style="display:none;" id="divMenuMsg">

				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57) ?></a>
				
				<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')"><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58) ?></a>
				
				<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59) ?></a>

			</div>
		</ul>
	<?php
	}
	?>

	<?php
	if(isset($_SESSION['codeI']))
	{
	?>
		<ul style="margin-top:20px;background:none;border:none;">

			<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><b><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</b></a></li>
			
			<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49) ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49) ?></a></li>


		</ul>


		<ul style="margin-top:20px; margin-bottom:20px; background:none; border:none;">

			
			<div style="display:none;" id="divMenuMsg">

				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57) ?></a>
				
				<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')"><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58) ?></a>
				
				<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59) ?></a>

			</div>
		</ul>
	<?php
	}
	?>
	
	<?php
	if(isset($_SESSION['codeCash']))
	{
	?>
		<ul style="margin-top:20px;background:none;border:none;">

			<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><b><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</b></a></li>
			
			<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49) ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49) ?></a></li>

		</ul>

		<ul style="margin-top:20px; margin-bottom:20px; background:none; border:none;">

			
			<div style="display:none;" id="divMenuMsg">

				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57) ?></a>
				
				<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')"><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58) ?></a>
				
				<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59) ?></a>

			</div>
		</ul>
	<?php
	}
	?>
	</div>


	<?php
		if (isset($_GET['expegnreport'])) {
	?>
	<?php

       if(isset($_SESSION['codeC'])){
            $doneby=$_SESSION['codeC'];
        }else{
            if(isset($_SESSION['codeCash'])){
            $doneby=$_SESSION['codeCash'];
            }
        }

if(isset($_GET['cash']) OR isset($_SESSION['codeC']) OR isset($_SESSION['codeC']))
    {
        
        $result=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c, coordinateurs co WHERE (c.codecashier=:operation OR co.codecoordi=:operation) AND (u.id_u=c.id_u OR u.id_u=co.id_u)');
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
                        if($ligne->sexe=="M")
                        {
                            $sexe = "Male";
                        }elseif($ligne->sexe=="F")
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
        
            <form action="expenses_reporting.php?expegnreport=ok&codeC=<?php echo $_SESSION['codeC']; ?>&audit=<?php echo $_SESSION['id'];?><?php if(isset($_GET['reporthospCash'])){ echo '&reporthospCash='.$_GET['reporthospCash'];}else{ if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}}?>&dmacbillperso=ok&selectPersoBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
            
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
                        $dailydateperso = 'datebill LIKE \''.$_POST['dailydatebillPerso'].'%\'';

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

                        $dailydateperso = 'datebill>=\''.$umwaka.'-'.$ukwezi.'-01\' AND (datebill<\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' OR datebill LIKE \''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'%\')';

                        $caVisit="monthlyPersoBill";

                        $stringResult=date("F",mktime(0,0,0,$_POST['monthlydatebillPerso'],10))." - ".$_POST['monthlydatebillPersoYear'];

                    }
                }

                if(isset($_POST['searchannualybillPerso']))
                {
                    if(isset($_POST['annualydatebillPerso']))
                    {
                        $year = $_POST['annualydatebillPerso'];

                        $dailydateperso = 'datebill >=\''.$year.'-01-01\' AND datebill <=\''.$year.'-12-31\'';

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

                        $dailydateperso = 'datebill>=\''.$debut.'\' AND (datebill<\''.$fin.'\' OR datebill LIKE \''.$fin.'%\')';

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

                $resultCashierBillReport=$connexion->query('SELECT * FROM expenses WHERE '.$dailydateperso.'');

                // $resultCashierBillReport->execute(array(
                //     'doneby'=> $doneby
                // ));

                $resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

               $compCashBillReport=$resultCashierBillReport->rowCount();

                if($compCashBillReport!=0)
                {
                    ?>

                    <table style="width:100%;">
                        <tr>
                            <td style="text-align:left; width:33.333%;">

                                <a href="expenses_reporting1.php?codecoordi=<?php if(isset($_SESSION['codeC'])){echo $_SESSION['codeC'];}?>&dailydateperso=<?php echo $dailydateperso;?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReportExpeGnl=ok&createRN=1" style="text-align:center" id="dmacbillpersopreview">

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
	<?php
		}
	?>

	
	<?php
	if(isset($_GET['num']))
	{
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
		$result->execute(array(
		'operation'=>$_GET['num']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$numPa=$ligne->numero;
			$fullname=$ligne->nom_u.' '.$ligne->prenom_u;
			$sexe=$ligne->sexe;
			$dateN=$ligne->date_naissance;
			$adresse=$ligne->province.','.$ligne->district.','.$ligne->secteur;
			$profession=$ligne->profession;
							
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

	?>
			<table style="margin:auto;">
				<tr>
					<?php
					if(!isset($_GET['selectPersoMedic']))
					{
						if(!isset($_GET['selectPersoBill']))
						{
					?>
						<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
							<b><h2>Individual Report</h2></b>
						</td>
					<?php
						}
					}
					if(isset($_GET['selectPersoMedic']))
					{
					?>
					<td style="font-size:18px; width:33.333%; " id="persomedicalstring">
						<b><h2>Individual Medical Report</h2></b>
					</td>
					<?php
					}
					if(isset($_GET['selectPersoBill']))
					{
					?>
					<td style="font-size:18px; width:33.333%;" id="persobillingstring">
						<b><h2>Individual Billing Report</h2></b>
					</td>
					<?php
					}
					?>
				</tr>
			</table>
			
			<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:80%;">
				<tr>
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $fullname;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
						<?php
						if($ligne->sexe=="M")
						{
							$sexe = "Male";
						}else{
						
							if($ligne->sexe=="F")
							
							$sexe = "Female";
						}
						
						echo $sexe;
						?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;">Age : </span><?php echo $an;?>
					</td>
				</tr>
				
				<tr>
					<td></td>
				</tr>
			</table>
		
		<?php 
		}
		?>
		
		<table cellpadding=3 style="margin:auto auto 10px auto; padding: 10px; width:80%;">
			<tr>
				<td style="font-size:18px; text-align:center; width:33.333%;"></td>
				
				<td style="font-size:18px; text-align:center; width:33.333%;">
					
					<a href="report.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&selectPersoMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center">
					
						<button id="persoMedicReport" class="btn-large">Medical Report</button>
						
					</a>
					
					<a href="report.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&selectPersoBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center">
					
						<button id="persoBillReport" class="btn-large" onclick="ShowDivReport('divPersoBillReport')">Billing Report</button>
						
					</a>
					
				</td>
				
				<td style="font-size:18px; text-align:right; width:33.333%;">
					
					<a href="reportPrint.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?>&divPersoMedicReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;display:none" id="medicalpersopreview">
					
						<button style="width:250px; margin:auto;" type="submit" name="printbill" id="printbill" class="btn-large-inversed">
							<i class="fa fa-desktop fa-lg fa-fw"></i> <?php echo getString(148);?>
						</button>
					
					</a>
			
					<?php
			
					$resultBillReport=$connexion->prepare('SELECT *FROM bills b WHERE b.numero=:numPa');		
					
					$resultBillReport->execute(array(
						'numPa'=>$num
					));
					
					$resultBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptBillReport=$resultBillReport->rowCount();

					if($comptBillReport!=0)
					{
					?>
					<a href="reportPrint.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?>&divPersoBillReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;display:none" id="billingpersopreview">
					
						<button style="width:250px; margin:auto;" type="submit" name="printbill" id="printbill" class="btn-large-inversed">
							<i class="fa fa-desktop fa-lg fa-fw"></i> <?php echo getString(148);?>
						</button>
					</a>
					<?php
					}
					?>
					
			
				</td>
			</tr>
			
		</table>
		
		<?php
		if(isset($_GET['selectPersoMedic']))
		{
		?>
		<div id="selectdatePersoMedicReport">
		
			<form action="report.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmac=ok&selectPersoMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
				<table id="dmac" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
						</td>
					</tr>
					
					<tr id="dmacligne" style="visibility:visible">
					
						<td id="dailymedicPerso" style="display:none;">Select date
							<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlymedicPerso" style="display:none">Select Month
						
							<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualymedicPerso" style="display:none">Select Year
						
							<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custommedicPerso" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				
				</table>


			</form>
			
		</div>
		<?php
		}
		?>
		
		<?php
		if(isset($_GET['selectPersoBill']))
		{
		?>
		<div id="selectdatePersoBillReport">
		
			<form action="report.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmacbillperso=ok&selectPersoBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
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
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatebillPersoYear" id="monthlydatebillPersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
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
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
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
			
		<?php
		}
		?>
		<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
			<tr>
				<td style="padding:5px;" id="ds_calclass"></td>
			</tr>
		</table>

	
		<?php
		
		if(isset($_GET['dmac']) OR isset($_GET['selectPersoMedic']))
		{
			$dailydateperso = " AND c.dateconsu != '0000-00-00'";
			$paVisit="gnlPersoMedic";
			
			if(isset($_POST['searchdailyPerso']))
			{
				if(isset($_POST['dailydatePerso']))
				{
					$dailydateperso = ' AND dateconsu=\''.$_POST['dailydatePerso'].'\'';
					
					$paVisit="dailyPersoMedic";
				
				}

			}
			
			if(isset($_POST['searchmonthlyPerso']))
			{
				if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
				{
					$ukwezi = $_POST['monthlydatePerso'];
					$umwaka = $_POST['monthlydatePersoYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND dateconsu>=\''.$umwaka.'-'.$ukwezi.'-1\' AND dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
					
					$paVisit="monthlyPersoMedic";
					
				}

			}
			
			if(isset($_POST['searchannualyPerso']))
			{
				if(isset($_POST['annualydatePerso']))
				{
					$year = $_POST['annualydatePerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydateperso = 'AND dateconsu>=\''.$year.'-01-01\' AND dateconsu<=\''.$year.'-12-31\'';
					
					$paVisit="annualyPersoMedic";
			
				}
			
			}
			
			if(isset($_POST['searchcustomPerso']))
			{
				if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
				{
					$debut = $_POST['customdatedebutPerso'];
					$fin = $_POST['customdatefinPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND dateconsu>=\''.$debut.'\' AND dateconsu<=\''.$fin.'\'';
					$paVisit="customPersoMedic";
			
				}

			}
			
				// echo $dailydateperso;
				// echo $ukwezi.' et '.$year;
				// echo $year;

		?>
		
			<div id="dmacMedicReport" style="display:inline">
			
			<?php
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num '.$dailydateperso.' ORDER BY c.id_consu');		
			$resultConsult->execute(array(
			'num'=>$numPa
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptConsult=$resultConsult->rowCount();
		

			if($comptConsult != 0)
			{
			?>
			
			<a href="dmacreport.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&paVisit=<?php echo $paVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
				
				<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
					<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
				</button>
			
			</a>
				
			<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
			<thead> 
				<tr style="text-align:left">
					<th style="width:10%">N°</th>
					<th style="width:10%">Date</th>
					<th style="width:35%"><?php echo getString(113);?></th>
					<th style="width:18.333%"><?php echo getString(39);?></th>
					<th style="width:18.333%"><?php echo getString(98);?></th>
					<th style="width:18.333%"><?php echo getString(99);?></th>
				</tr> 
			</thead> 

			<tbody>
			<?php
			// $date='0000-00-00';
			$compteur=1;
			
				while($ligneConsult=$resultConsult->fetch())
				{
			?>
				<tr>
					<td style="text-align:left;"><?php echo $compteur;?></td>
					<td style="text-align:left;"><?php echo $ligneConsult->dateconsu;?></td>
					<td style="text-align:left;">
					<?php
					$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
					$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
						
						if($lignePresta->namepresta!='')
						{
							echo $lignePresta->namepresta.'</td>';
						}else{								
							echo $lignePresta->nompresta.'</td>';
						}
					}
				
					echo '<td style="text-align:left;">';
					
					$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.numero=:num AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
					$resultMedConsult->execute(array(
					'num'=>$numPa,
					'idMedConsu'=>$ligneConsult->id_consu
					));
					
					$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptMedConsult=$resultMedConsult->rowCount();
				
				
					if($comptMedConsult != 0)
					{
					?>
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						

						<tbody>
					<?php
							while($ligneMedConsult=$resultMedConsult->fetch())
							{
					?>
							<tr style="text-align:center;">
								
								<td>
								<?php
								
								$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsult->id_prestationConsu
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>';
										
									}
								}
									echo $ligneMedConsult->autreConsu.'</td>';
								?>
							</tr>
					<?php
							}
					?>		
						</tbody>
						</table>
					<?php
					}
				
					echo '</td>';
					
					echo '<td>';
					
					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.numero=:num AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
					$resultMedInf->execute(array(
					'num'=>$numPa,					
					'idMedInf'=>$ligneConsult->id_consu
					));
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptMedInf=$resultMedInf->rowCount();
				
				
					if($comptMedInf != 0)
					{
					?>		
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
					
						<tbody>
					<?php
							while($ligneMedInf=$resultMedInf->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td>
								<?php 
									
								$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>';
									}
								}
								
									echo $ligneMedInf->autrePrestaM.'</td>';
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

					
					echo '</td>';
					
					echo '<td>';
					
					$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.numero=:num AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
					$resultMedLabo->execute(array(
					'num'=>$numPa,					
					'idMedLabo'=>$ligneConsult->id_consu
					));
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

					$comptMedLabo=$resultMedLabo->rowCount();


					if($comptMedLabo != 0)
					{
					?>	
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						
						<tbody>
					<?php
							while($ligneMedLabo=$resultMedLabo->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td>
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedLabo->id_prestationExa
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{
											echo $lignePresta->nompresta.'</td>';
										}
									}
									
										echo $ligneMedLabo->autreExamen;
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
					</td>
				</tr>
				<?php
					$compteur++;
				}
				?>		
			</tbody>
			</table>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center">No Medical Report for this search</th>
						</tr> 
					</thead> 
				</table> 
				
			<?php
			}
			?>
			</div>
		<?php
		}
		?>
	
		<?php
		
		if(isset($_GET['dmacbillperso']) OR isset($_GET['selectPersoBill']))
		{
			$dailydateperso = "";
			$paVisit="gnlPersoBill";
			
			if(isset($_POST['searchdailybillPerso']))
			{
				if(isset($_POST['dailydatebillPerso']))
				{
					$dailydateperso = 'AND datebill LIKE \''.$_POST['dailydatebillPerso'].'%\'';
					
					$paVisit="dailyPersoBill";
				
				}else{
					$dailydateperso = "";
					$paVisit="gnlPersoBill";
				
				}

			}
			
			if(isset($_POST['searchmonthlybillPerso']))
			{
				if(isset($_POST['monthlydatebillPerso']) AND isset($_POST['monthlydatebillPersoYear']))
				{
					$ukwezi = $_POST['monthlydatebillPerso'];
					$umwaka = $_POST['monthlydatebillPersoYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					$paVisit="monthly";
					
					$dailydateperso = 'AND datebill>=\''.$umwaka.'-'.$ukwezi.'-1\' AND datebill<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
			
					$paVisit="monthlyPersoBill";
					
				}else{
					$dailydateperso = "";
					$paVisit="gnlPersoBill";
				
				}

			}
			
			if(isset($_POST['searchannualybillPerso']))
			{
				if(isset($_POST['annualydatebillPerso']))
				{
					$year = $_POST['annualydatebillPerso'];
					
					$dailydateperso = 'AND datebill>=\''.$year.'-01-01\' AND datebill<=\''.$year.'-12-31\'';
					$paVisit="annualyPersoBill";
			
				}else{
					$dailydateperso = "";
					$paVisit="gnlPersoBill";
				
				}
			
			}
			
			if(isset($_POST['searchcustombillPerso']))
			{
				if(isset($_POST['customdatedebutbillPerso']) AND isset($_POST['customdatefinbillPerso']))
				{
					$debut = $_POST['customdatedebutbillPerso'];
					$fin = $_POST['customdatefinbillPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND datebill>=\''.$debut.'\' AND datebill<=\''.$fin.'\'';
					$paVisit="customPersoBill";
			
				}else{
					$dailydateperso = "";
					$paVisit="gnlPersoBill";
				
				}

			}
			
				// echo $dailydateperso;
				// echo $ukwezi.' et '.$year;
				// echo $year;
		
		?>
		
			<div id="dmacBillReport" style="display:inline">
				
				<?php
				
				$resultBillReport=$connexion->prepare('SELECT *FROM bills b WHERE b.numero=:numPa '.$dailydateperso.'');		
				
				$resultBillReport->execute(array(
					'numPa'=>$num
				));
				
				$resultBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptBillReport=$resultBillReport->rowCount();

				if($comptBillReport!=0)
				{
				?>
				
				<a href="dmacreport.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&divPersoBillReport=ok&paVisit=<?php echo $paVisit;?>" style="text-align:center" id="dmacbillpersopreview">
					
					<button style="width:250px; margin:auto;" type="submit" name="printbill" id="printbill" class="btn-large-inversed">
						<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
					</button>
				
				</a>
				
				<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:80%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:10%">N°</th>
							<th>Date</th>
							<th>Bill number</th>
							<th>Insurance</th>
							<th><?php echo getString(113);?></th>
							<th><?php echo getString(39);?></th>
							<th><?php echo getString(98);?></th>
							<th><?php echo getString(99);?></th>
							<th>Total Final</th>
						</tr> 
					</thead> 
					
					<tbody>
				<?php
				$TotalGnlTypeConsu=0;
				$TotalGnlMedConsu=0;
				$TotalGnlMedInf=0;
				$TotalGnlMedLabo=0;
				$TotalGnlPrice=0;
				
				$compteur=1;
				
					while($ligneBillReport=$resultBillReport->fetch())//on recupere la liste des éléments
					{
				?>
				
						<tr style="text-align:center;">
							<td><?php echo $compteur;?></td>
							<td><?php echo $ligneBillReport->datebill;?></td>
							<td><?php echo $ligneBillReport->numbill;?></td>
							<td><?php echo $ligneBillReport->nomassurance.' '.$ligneBillReport->billpercent.' %';?></td>
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
								<?php
					
								$resultConsu=$connexion->prepare('SELECT *FROM consultations c, prestations_private p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
								$resultConsu->execute(array(
								'idbill'=>$ligneBillReport->id_bill
								));
								
								$comptConsu=$resultConsu->rowCount();
								
								$resultConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								if($comptConsu!=0)
								{
									while($ligneConsu=$resultConsu->fetch())//on recupere la liste des éléments
									{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneConsu->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneConsu->prixpresta;
										?>
										</td>
									</tr>
								<?php
									}
								}
								?>
									
									<tr>
										<?php
										
										if($ligneBillReport->totaltypeconsuprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneBillReport->totaltypeconsuprice;
										?>
										</td>
									</tr>
								</table>
						
							</td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
								<?php
								
						$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
						$resultMedConsu->execute(array(
						'idbill'=>$ligneBillReport->id_bill
						));
						
						$comptMedConsu=$resultMedConsu->rowCount();
						
						$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE  mc.id_factureMedConsu=:idbill GROUP BY id_medconsu ORDER BY mc.id_medconsu DESC');
						$resultMedAutreConsu->execute(array(
						'idbill'=>$ligneBillReport->id_bill
						));
						
						$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
						
						$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
					{
						if($comptMedConsu!=0)
						{
							while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
							{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedConsu->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneMedConsu->prixpresta;
										?>
										</td>
									</tr>
								<?php
								
							}
						}
						
						if($comptMedAutreConsu!=0)
						{
							while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())//on recupere la liste des éléments
							{
								
								if($ligneMedAutreConsu->prixautreConsu!=0 and $ligneMedAutreConsu->autreConsu!="")
								{
								?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreConsu->autreConsu;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreConsu->prixautreConsu;
									
									?>
									</td>
								</tr>
								<?php
								}
							}
						}

					}					
					?>
										
									<tr>
										<?php
										
										if($ligneBillReport->totalmedconsuprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneBillReport->totalmedconsuprice;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
								<?php
								
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
						$resultMedInf->execute(array(
						'idbill'=>$ligneBillReport->id_bill
						));
						
						$comptMedInf=$resultMedInf->rowCount();
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_factureMedInf=:idbill GROUP BY id_medinf ORDER BY mi.id_medinf DESC');
						$resultMedAutreInf->execute(array(
						'idbill'=>$ligneBillReport->id_bill
						));
						
						$comptMedAutreInf=$resultMedAutreInf->rowCount();
						
						$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($comptMedInf!=0 or $comptMedAutreInf!=0)
					{
						if($comptMedInf!=0)
						{
							while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des éléments
							{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedInf->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneMedInf->prixpresta;
										?>
										</td>
									</tr>
								<?php
								
							}
						}
						
						if($comptMedAutreInf!=0)
						{
							while($ligneMedAutreInf=$resultMedAutreInf->fetch())//on recupere la liste des éléments
							{
								
								if($ligneMedAutreInf->prixautrePrestaM != 0 and $ligneMedAutreInf->autrePrestaM != "")
								{
								?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreInf->autrePrestaM;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreInf->prixautrePrestaM;
									
									?>
									</td>
								</tr>
								<?php
								}
							}
						}

					}					
					?>
										
									<tr>
										<?php
										
										if($ligneBillReport->totalmedinfprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneBillReport->totalmedinfprice;
										?>
										</td>
									</tr>
								</table>
							
							</td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
								<?php
											
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedLabo->execute(array(
						'idbill'=>$ligneBillReport->id_bill
						));
						
						$comptMedLabo=$resultMedLabo->rowCount();
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_factureMedLabo=:idbill GROUP BY id_medlabo ORDER BY ml.id_medlabo DESC');
						$resultMedAutreLabo->execute(array(
						'idbill'=>$ligneBillReport->id_bill
						));
						
						$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
						
						$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
						{
							if($comptMedLabo!=0)
							{
								while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des éléments
								{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedLabo->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneMedLabo->prixpresta;
										?>
										</td>
									</tr>
									<?php
									
								}
							}
							
							if($comptMedAutreLabo!=0)
							{
								while($ligneMedAutreLabo=$resultMedAutreInf->fetch())//on recupere la liste des éléments
								{
									if($ligneMedAutreLabo->prixautreExamen != 0 and $ligneMedAutreLabo->autreExamen != "")
									{
									?>
									<tr>
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreLabo->autreExamen;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreLabo->prixautreExamen;
										
										?>
										</td>
									</tr>
						<?php
									}
								}
							}

						}					
						?>
									
									<tr>
										<?php
										
										if($ligneBillReport->totalmedlaboprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneBillReport->totalmedlaboprice;
										?>
										</td>
									</tr>
								</table>
							
							</td>
							
							<td><?php echo $ligneBillReport->totalgnlprice;?></td>
						</tr>
				<?php
						$TotalGnlTypeConsu=$TotalGnlTypeConsu + $ligneBillReport->totaltypeconsuprice;
						$TotalGnlMedConsu= $TotalGnlMedConsu + $ligneBillReport->totalmedconsuprice;
						$TotalGnlMedInf= $TotalGnlMedInf + $ligneBillReport->totalmedinfprice;
						$TotalGnlMedLabo=$TotalGnlMedLabo + $ligneBillReport->totalmedlaboprice;
						$TotalGnlPrice=$TotalGnlPrice + $ligneBillReport->totalgnlprice;
						
						$compteur++;
					}
				?>
						<tr style="text-align:center;">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlTypeConsu;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedConsu;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedInf;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedLabo;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlPrice;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Billing Report for this patient</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
			</div>
		<?php
		}
		?>
		
				
	<div style="overflow:auto;height:500px;background-color:none;">
	
		<div id="divPersoMedicReport" style="display:none;">
				
			<?php
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num ORDER BY c.id_consu');		
			$resultConsult->execute(array(
			'num'=>$numPa
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptConsult=$resultConsult->rowCount();
		

			if($comptConsult != 0)
			{
			?>
			<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
			<thead> 
				<tr style="text-align:left">
					<th style="width:10%">N°</th>
					<th style="width:10%">Date</th>
					<th style="width:35%"><?php echo getString(113);?></th>
					<th style="width:18.333%"><?php echo getString(39);?></th>
					<th style="width:18.333%"><?php echo getString(98);?></th>
					<th style="width:18.333%"><?php echo getString(99);?></th>
				</tr> 
			</thead> 


			<tbody>
		<?php
			// $date='0000-00-00';
			$compteur=1;
			
				while($ligneConsult=$resultConsult->fetch())
				{
		?>
				<tr>
					<td style="text-align:left;"><?php echo $compteur;?></td>
					<td style="text-align:left;">
					<?php
						echo $ligneConsult->dateconsu;
					/* if($date != $ligneConsult->dateconsu)
					{
						echo $ligneConsult->dateconsu;
					}else{
						echo '';
					}
						$date=$ligneConsult->dateconsu; */
					?>
					</td>
					<td style="text-align:left;">
					<?php
					$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
					$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
						
						if($lignePresta->namepresta!='')
						{
							echo $lignePresta->namepresta.'</td>';
						}else{								
							echo $lignePresta->nompresta.'</td>';
						}
					}
				
					echo '<td style="text-align:left;">';
					
					$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.numero=:num AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
					$resultMedConsult->execute(array(
					'num'=>$numPa,
					'idMedConsu'=>$ligneConsult->id_consu
					));
					
					$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptMedConsult=$resultMedConsult->rowCount();
				
				
					if($comptMedConsult != 0)
					{
					?>
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						

						<tbody>
					<?php
							while($ligneMedConsult=$resultMedConsult->fetch())
							{
					?>
							<tr style="text-align:center;">
								
								<td>
								<?php
								
								$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsult->id_prestationConsu
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>';
										
									}
								}
									echo $ligneMedConsult->autreConsu.'</td>';
								?>
							</tr>
					<?php
							}
					?>		
						</tbody>
						</table>
					<?php
					}
				
					echo '</td>';
					
					echo '<td>';
					
					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.numero=:num AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
					$resultMedInf->execute(array(
					'num'=>$numPa,					
					'idMedInf'=>$ligneConsult->id_consu
					));
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptMedInf=$resultMedInf->rowCount();
				
				
					if($comptMedInf != 0)
					{
					?>		
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
					
						<tbody>
					<?php
							while($ligneMedInf=$resultMedInf->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td>
								<?php 
									
								$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>';
									}
								}
								
									echo $ligneMedInf->autrePrestaM.'</td>';
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

					
					echo '</td>';
					
					echo '<td>';
					
					$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.numero=:num AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
					$resultMedLabo->execute(array(
					'num'=>$numPa,					
					'idMedLabo'=>$ligneConsult->id_consu
					));
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

					$comptMedLabo=$resultMedLabo->rowCount();


					if($comptMedLabo != 0)
					{
					?>	
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						
						<tbody>
					<?php
							while($ligneMedLabo=$resultMedLabo->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td>
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedLabo->id_prestationExa
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{
											echo $lignePresta->nompresta.'</td>';
										}
									}
									
										echo $ligneMedLabo->autreExamen;
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
					</td>
				</tr>
				<?php
					$compteur++;
				}
				?>		
			</tbody>
			</table>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center">No Medical Report for this patient</th>
						</tr> 
					</thead> 
				</table> 
				
			<?php
			}
			?>
		</div>
		
		<div id="divPersoBillReport" style="display:none">
			
			<?php
			
			$resultBillReport=$connexion->prepare('SELECT *FROM bills b WHERE b.numero=:numPa');		
			
			$resultBillReport->execute(array(
				'numPa'=>$num
			));
			
			$resultBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptBillReport=$resultBillReport->rowCount();

			if($comptBillReport!=0)
			{
			?>
			<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:80%; margin-top:10px;"> 
						
				<thead>
					<tr>
						<th style="width:10%">N°</th>
						<th>Date</th>
						<th>Bill number</th>
						<th>Insurance</th>
						<th><?php echo getString(113);?></th>
						<th><?php echo getString(39);?></th>
						<th><?php echo getString(98);?></th>
						<th><?php echo getString(99);?></th>
						<th>Total Final</th>
					</tr> 
				</thead> 
				
				<tbody>
			<?php
			$TotalGnlTypeConsu=0;
			$TotalGnlMedConsu=0;
			$TotalGnlMedInf=0;
			$TotalGnlMedLabo=0;
			$TotalGnlPrice=0;
			
			$compteur=1;
			
				while($ligneBillReport=$resultBillReport->fetch())//on recupere la liste des éléments
				{
			?>			
					<tr style="text-align:center;">
						<td><?php echo $compteur;?></td>
						<td><?php echo $ligneBillReport->datebill;?></td>
						<td><?php echo $ligneBillReport->numbill;?></td>
						<td><?php echo $ligneBillReport->nomassurance.' '.$ligneBillReport->billpercent.' %';?></td>
						
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
							<?php
				
							$resultConsu=$connexion->prepare('SELECT *FROM consultations c, prestations_private p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
							$resultConsu->execute(array(
							'idbill'=>$ligneBillReport->id_bill
							));
							
							$comptConsu=$resultConsu->rowCount();
							
							$resultConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							if($comptConsu!=0)
							{
								while($ligneConsu=$resultConsu->fetch())//on recupere la liste des éléments
								{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneConsu->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneConsu->prixpresta;
									?>
									</td>
								</tr>
							<?php
								}
							}
							?>
								
								<tr>
									<?php
									
									if($ligneBillReport->totaltypeconsuprice!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php

										echo $ligneBillReport->totaltypeconsuprice;
									?>
									</td>
								</tr>
							</table>
					
						</td>
						
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
							<?php
							
					$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
					$resultMedConsu->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedConsu=$resultMedConsu->rowCount();
					
					$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE  mc.id_factureMedConsu=:idbill GROUP BY id_medconsu ORDER BY mc.id_medconsu DESC');
					$resultMedAutreConsu->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
					
					$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
				if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
				{
					if($comptMedConsu!=0)
					{
						while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
						{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedConsu->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedConsu->prixpresta;
									?>
									</td>
								</tr>
							<?php
							
						}
					}
					
					if($comptMedAutreConsu!=0)
					{
						while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())//on recupere la liste des éléments
						{
							
							if($ligneMedAutreConsu->prixautreConsu!=0 and $ligneMedAutreConsu->autreConsu!="")
							{
							?>
							<tr>
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreConsu->autreConsu;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreConsu->prixautreConsu;
								
								?>
								</td>
							</tr>
							<?php
							}
						}
					}

				}					
				?>
									
								<tr>
									<?php
									
									if($ligneBillReport->totalmedconsuprice!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php

										echo $ligneBillReport->totalmedconsuprice;
									?>
									</td>
								</tr>
							</table>
							
						</td>
						
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
							<?php
							
					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
					$resultMedInf->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedInf=$resultMedInf->rowCount();
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_factureMedInf=:idbill GROUP BY id_medinf ORDER BY mi.id_medinf DESC');
					$resultMedAutreInf->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedAutreInf=$resultMedAutreInf->rowCount();
					
					$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
				if($comptMedInf!=0 or $comptMedAutreInf!=0)
				{
					if($comptMedInf!=0)
					{
						while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des éléments
						{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedInf->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedInf->prixpresta;
									?>
									</td>
								</tr>
							<?php
							
						}
					}
					
					if($comptMedAutreInf!=0)
					{
						while($ligneMedAutreInf=$resultMedAutreInf->fetch())//on recupere la liste des éléments
						{
							
							if($ligneMedAutreInf->prixautrePrestaM != 0 and $ligneMedAutreInf->autrePrestaM != "")
							{
							?>
							<tr>
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreInf->autrePrestaM;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreInf->prixautrePrestaM;
								
								?>
								</td>
							</tr>
							<?php
							}
						}
					}

				}					
				?>
									
								<tr>
									<?php
									
									if($ligneBillReport->totalmedinfprice!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php

										echo $ligneBillReport->totalmedinfprice;
									?>
									</td>
								</tr>
							</table>
						
						</td>
						
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
							<?php
										
					$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
					$resultMedLabo->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedLabo=$resultMedLabo->rowCount();
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_factureMedLabo=:idbill GROUP BY id_medlabo ORDER BY ml.id_medlabo DESC');
					$resultMedAutreLabo->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
					
					$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
					{
						if($comptMedLabo!=0)
						{
							while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des éléments
							{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedLabo->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedLabo->prixpresta;
									?>
									</td>
								</tr>
								<?php
								
							}
						}
						
						if($comptMedAutreLabo!=0)
						{
							while($ligneMedAutreLabo=$resultMedAutreInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedAutreLabo->prixautreExamen != 0 and $ligneMedAutreLabo->autreExamen != "")
								{
								?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreLabo->autreExamen;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreLabo->prixautreExamen;
									
									?>
									</td>
								</tr>
					<?php
								}
							}
						}

					}					
					?>
								
								<tr>
									<?php
									
									if($ligneBillReport->totalmedlaboprice!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php

										echo $ligneBillReport->totalmedlaboprice;
									?>
									</td>
								</tr>
							</table>
						
						</td>
						
						<td><?php echo $ligneBillReport->totalgnlprice;?></td>
					</tr>
			<?php
					$TotalGnlTypeConsu=$TotalGnlTypeConsu + $ligneBillReport->totaltypeconsuprice;
					$TotalGnlMedConsu= $TotalGnlMedConsu + $ligneBillReport->totalmedconsuprice;
					$TotalGnlMedInf= $TotalGnlMedInf + $ligneBillReport->totalmedinfprice;
					$TotalGnlMedLabo=$TotalGnlMedLabo + $ligneBillReport->totalmedlaboprice;
					$TotalGnlPrice=$TotalGnlPrice + $ligneBillReport->totalgnlprice;
					$compteur++;
				}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlTypeConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedInf;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedLabo;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlPrice;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center">No Billing Report for this patient</th>
						</tr> 
					</thead> 
				</table> 
				
			<?php
			}
			?>
		</div>
		
	</div>
		
	<?php
	}
	
	if(isset($_GET['gnlreport']))
	{
	?>
		<table style="margin:auto;">
			<tr>
				<?php
				if(isset($_GET['selectGnlMedic']))
				{
				?>
				<td style="font-size:18px; width:33.333%; " id="gnlmedicalstring">
					<b><h2>General Medical Report</h2></b>
				</td>
				<?php
				}else{
					if(isset($_GET['selectGnlBill']))
					{
				?>
					<td style="font-size:18px; width:33.333%;" id="gnlbillstring">
						<b><h2>General Billing Report</h2></b>
					</td>
				<?php
					}else{
				?>
						<td style="font-size:18px; width:33.333%;" id="gnlbillstring">
							<b><h2>General Report</h2></b>
						</td>
				<?php
					}
				}
				?>
			</tr>
		</table>
		
		
		<table cellpadding=3 style="margin:auto auto 10px auto; padding: 10px; width:95%;">
			<tr>
				<td style="font-size:18px; text-align:center; width:33.333%;">
				
					<a href="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreport=ok&selectGnlMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;display:none;">
					
						<button id="gnlMedicReport" class="btn-large" onclick="ShowDivReport('divGnlMedicReport')" >Medical Report</button>
						
					</a>
					
					<a href="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreport=ok&selectGnlBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center">
					
					<button id="gnlBillReport" class="btn-large" onclick="ShowDivReport('divGnlBillReport')">Billing Report</button>						
					</a>
					
				</td>
			</tr>
		</table>
	
		<?php
		if(isset($_GET['selectGnlMedic']))
		{
		?>
		<div id="selectdateGnlMedicReport" style="display:inline">
		
			<form action="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreport=ok&dmacgnl=ok&selectGnlMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">

				<table id="dmacgnl" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailygnlbtn" onclick="ShowSelectGnl('dailymedicGnl')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlygnlbtn" onclick="ShowSelectGnl('monthlymedicGnl')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualygnlbtn" onclick="ShowSelectGnl('annualymedicGnl')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="customgnlbtn" onclick="ShowSelectGnl('custommedicGnl')" class="btn">Custom</span>
						</td>
					</tr>
					
					<tr style="visibility:visible">
					
						<td id="dailymedicGnl" style="display:none">Select date
							<input type="text" id="dailydateGnl" name="dailydateGnl" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlymedicGnl" style="display:none">Select Month
						
							<select name="monthlydateGnl" id="monthlydateGnl" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydateGnlYear" id="monthlydateGnlYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualymedicGnl" style="display:none">Select Year
						
							<select name="annualydateGnl" id="annualydateGnl" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custommedicGnl" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutGnl" name="customdatedebutGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinGnl" name="customdatefinGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomGnl" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				
				</table>


			</form>
			
		</div>
		<?php
		}
		?>
		
		
		<?php
		if(isset($_GET['selectGnlBill']))
		{
		?>
		<div id="selectdateGnlBillReport" style="display:inline">
		
			<form action="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreport=ok&dmacbillgnl=ok&selectGnlBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">

				<table id="dmacbillgnl" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailygnlbtn" onclick="ShowSelectreportGnl('dailybillGnl')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlygnlbtn" onclick="ShowSelectreportGnl('monthlybillGnl')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualygnlbtn" onclick="ShowSelectreportGnl('annualybillGnl')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="customgnlbtn" onclick="ShowSelectreportGnl('custombillGnl')" class="btn">Custom</span>
						</td>
					</tr>
					
					<tr style="visibility:visible">
					
						<td id="dailybillGnl" style="display:none">Select Date
							<input type="text" id="dailydatebillGnl" name="dailydatebillGnl" onclick="ds_sh(this);" value=""/>
						
							<!--
							<select name="dailypercbillGnl" id="dailypercbillGnl" style="width:60px;height:40px;">
							<?php 
							for($j=0;$j<=100;$j++)
							{
							?>
								<option value='<?php echo $j;?>'><?php echo $j;?></option>
							<?php 
							}
							?>
							</select><span style="font-size:100%; font-weight:normal;margin-right:50px;">%</span>
							-->
							
							<button type="submit"  name="searchdailybillGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlybillGnl" style="display:none">Select Month
						
							<select name="monthlydatebillGnl" id="monthlydatebillGnl" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatebillGnlYear" id="monthlydatebillGnlYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<!--
							<select name="monthlypercbillGnl" id="monthlypercbillGnl" style="width:60px;height:40px;">
							<?php 
							for($j=0;$j<=100;$j++)
							{
							?>
								<option value='<?php echo $j;?>'><?php echo $j;?></option>
							<?php 
							}
							?>
							</select><span style="font-size:100%; font-weight:normal;margin-right:50px;">%</span>
							-->
							
							<button type="submit"  name="searchmonthlybillGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualybillGnl" style="display:none">Select Year
						
							<select name="annualydatebillGnl" id="annualydatebillGnl" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<!--
							<select name="annualypercbillGnl" id="annualypercbillGnl" style="width:60px;height:40px;">
							<?php 
							for($j=0;$j<=100;$j++)
							{
							?>
								<option value='<?php echo $j;?>'><?php echo $j;?></option>
							<?php 
							}
							?>
							</select><span style="font-size:100%; font-weight:normal;margin-right:50px;">%</span>
							-->
							
							<button type="submit"  name="searchannualybillGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custombillGnl" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutbillGnl" name="customdatedebutbillGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinbillGnl" name="customdatefinbillGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td>
										<!--
										<select name="custompercbillGnl" id="custompercbillGnl" style="width:60px;height:40px;">
										<?php 
										for($j=0;$j<=100;$j++)
										{
										?>
											<option value='<?php echo $j;?>'><?php echo $j;?></option>
										<?php 
										}
										?>
										</select><span style="font-size:100%; font-weight:normal;margin-right:50px;">%</span>
										-->
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustombillGnl" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				
				</table>

			</form>
			
		</div>
			
		<?php
		}
		?>
			<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
				<tr>
					<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
			</table>

	<?php
		
		if(isset($_GET['dmacgnl']) OR isset($_GET['selectGnlMedic']))
		{
			$dailydategnl = "WHERE c.dateconsu != '0000-00-00'";
			$paVisitgnl="gnlGnlMedic";
			
			if(isset($_POST['searchdailyGnl']))
			{
				if(isset($_POST['dailydateGnl']))
				{
					$dailydategnl = 'WHERE dateconsu=\''.$_POST['dailydateGnl'].'\'';
					
					$paVisitgnl="dailyGnlMedic";
				
				}

			}
			
			if(isset($_POST['searchmonthlyGnl']))
			{
				if(isset($_POST['monthlydateGnl']) AND isset($_POST['monthlydateGnlYear']))
				{
					$ukwezi = $_POST['monthlydateGnl'];
					$umwaka = $_POST['monthlydateGnlYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydategnl = 'WHERE dateconsu>=\''.$umwaka.'-'.$ukwezi.'-1\' AND dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
					
					$paVisitgnl="monthlyGnlMedic";
					
				}

			}
			
			if(isset($_POST['searchannualyGnl']))
			{
				if(isset($_POST['annualydateGnl']))
				{
					$year = $_POST['annualydateGnl'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydategnl = 'WHERE dateconsu>=\''.$year.'-01-01\' AND dateconsu<=\''.$year.'-12-31\'';
					
					$paVisitgnl="annualyGnlMedic";
			
				}
			
			}
			
			if(isset($_POST['searchcustomGnl']))
			{
				if(isset($_POST['customdatedebutGnl']) AND isset($_POST['customdatefinGnl']))
				{
					$debut = $_POST['customdatedebutGnl'];
					$fin = $_POST['customdatefinGnl'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydategnl = 'WHERE dateconsu>=\''.$debut.'\' AND dateconsu<=\''.$fin.'\'';
					$paVisitgnl="customGnlMedic";
			
				}

			}
			
				// echo $dailydategnl;
				// echo $ukwezi.' et '.$year;
				// echo $year;

		?>
			<div id="divGnlMedicReport" style="display:inline">
			
				<?php
			
				$resultConsult=$connexion->query("SELECT *FROM consultations c ".$dailydategnl." AND c.dateconsu != '0000-00-00' ORDER BY c.dateconsu DESC");		
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptConsult=$resultConsult->rowCount();
			

				if($comptConsult != 0)
				{
				?>
				
				<a href="dmacreport.php?dailydategnl=<?php echo $dailydategnl;?>&divGnlMedicReport=ok&gnlpatient=ok&paVisit=<?php echo $paVisitgnl;?>" style="text-align:center" id="dmacmedicalgnlpreview">
					
					<button style="width:250px; margin:auto;" type="submit" name="printMedicReportGnl" id="printMedicReportGnl" class="btn-large-inversed">
						<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
					</button>
				
				</a>
				
				<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:95%;"> 
					<thead> 
						<tr>
							<th style="width:10%">N°</th>
							<th style="width:10%">Date</th>
							<th style="width:15%">Name</th>
							<th style="width:20%"><?php echo getString(113);?></th>
							<th style="width:15%"><?php echo getString(39);?></th>
							<th style="width:15%"><?php echo getString(98);?></th>
							<th style="width:15%"><?php echo getString(99);?></th>
						</tr> 
					</thead> 


					<tbody>
				<?php
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())//on recupere la liste des éléments
					{
				?>
				
					<tr style="text-align:center;">
						<td><?php echo $compteur; ?></td>
						<td><?php echo $ligneConsult->dateconsu; ?></td>
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								
								echo '<td>'.$fullname.'</td>';
							}else{
								echo '<td></td>';
							}
							
						?>
						
						<td style="padding:0 10px;">
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
							'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							
							if($lignePresta->namepresta!='')
							{
								echo $lignePresta->namepresta.'</td>';
							}else{								
								echo $lignePresta->nompresta.'</td>';
							}
						}
						?>
						<td>
						<?php
						
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedConsult=$resultMedConsult->rowCount();
					
					
						if($comptMedConsult != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
						<?php
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
						?>
								<tr style="text-align:center;">
									
									<td>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsult->id_prestationConsu
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
									}
										echo $ligneMedConsult->autreConsu.'</td>';
									?>
								</tr>
						<?php
								}
						?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>
						
						<td>
						<?php
						
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(				
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedInf=$resultMedInf->rowCount();
					
					
						if($comptMedInf != 0)
						{
						?>		
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						
							<tbody>
						<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
									}
									
										echo $ligneMedInf->autrePrestaM.'</td>';
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
						</td>
						
						<td>
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(			
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

						$comptMedLabo=$resultMedLabo->rowCount();


						if($comptMedLabo != 0)
						{
						?>	
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
							<tbody>
						<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
											}else{
												echo $lignePresta->nompresta.'</td>';
											}
										}
										
											echo $ligneMedLabo->autreExamen;
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
						</td>
					</tr>
				<?php
						$compteur++;
					}
				?>
					</tbody>
				</table>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%">No General Medical Report</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
			</div>
				
		<?php
		}
		?>

		<?php
		
		if(isset($_GET['dmacbillgnl']))
		{
			$stringResult = "";
			$dailydategnl = "";
			$paVisitgnl="gnlGnlBill";
			
			if(isset($_POST['searchdailybillGnl']))
			{
				if(isset($_POST['dailydatebillGnl']))
				{
					// $percent = $_POST['dailypercbillGnl'];
					
					$dailydategnl = 'datebill LIKE \''.$_POST['dailydatebillGnl'].'%\'';
					
					$paVisitgnl="dailyGnlBill";
				
					$stringResult="Daily results : ".$_POST['dailydatebillGnl'];
				
				}
			}
			
			if(isset($_POST['searchmonthlybillGnl']))
			{
				if(isset($_POST['monthlydatebillGnl']) AND isset($_POST['monthlydatebillGnlYear']))
				{
					
					if($_POST['monthlydatebillGnl']<10)
					{
						$ukwezi = '0'.$_POST['monthlydatebillGnl'];
					}else{						
						$ukwezi = $_POST['monthlydatebillGnl'];
					}
					
					$umwaka = $_POST['monthlydatebillGnlYear'];
					// $percent = $_POST['monthlypercbillGnl'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					if($daysmonth<10)
					{
						$daysmonth='0'.$daysmonth;
					}
					
					$paVisitgnl="monthly";
					
					$dailydategnl = 'datebill>=\''.$umwaka.'-'.$ukwezi.'-01\' AND (datebill<\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' OR datebill LIKE \''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'%\')';
			
					$paVisitgnl="monthlyGnlBill";
					
					$stringResult=date("F",mktime(0,0,0,$_POST['monthlydatebillGnl'],10))."-".$_POST['monthlydatebillGnlYear'];
					
				}

			}
			
			if(isset($_POST['searchannualybillGnl']))
			{
				if(isset($_POST['annualydatebillGnl']))
				{
					$year = $_POST['annualydatebillGnl'];
					// $percent = $_POST['annualypercbillGnl'];
					
					$dailydategnl = 'datebill>=\''.$year.'-01-01\' AND datebill<=\''.$year.'-12-31\'';
					
					$paVisitgnl="annualyGnlBill";
					
					$stringResult="Annualy results : ".$_POST['annualydatebillGnl'];
			
			
				}
			
			}
			
			if(isset($_POST['searchcustombillGnl']))
			{
				if(isset($_POST['customdatedebutbillGnl']) AND isset($_POST['customdatefinbillGnl']))
				{
					$debut = $_POST['customdatedebutbillGnl'];
					$fin = $_POST['customdatefinbillGnl'];
					// $percent = $_POST['custompercbillGnl'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydategnl = 'datebill>=\''.$debut.'\' AND (datebill<\''.$fin.'\' OR datebill LIKE \''.$fin.'%\')';
					$paVisitgnl="customGnlBill";
					
					$stringResult="Customer results : [ ".$_POST['customdatedebutbillGnl']."/".$_POST['customdatefinbillGnl']." ]";
			
			
				}

			}
			
				// echo $dailydategnl;
				// echo $ukwezi.' et '.$year;
				// echo $year;
		
		?>
		
			<div id="divGnlBillReport" style="display:inline">
			
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

			$resultGnlBillReport=$connexion->query('SELECT *FROM bills WHERE '.$dailydategnl.' ORDER BY datebill DESC');

			$resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptBillReport=$resultGnlBillReport->rowCount();
			
			if($comptBillReport!=0)
			{
			?>
				
			<table style="width:100%;">
				<tr>
					<td style="text-align:left; width:33.333%;">
				
						<a href="dmacreport.php?dailydategnl=<?php echo $dailydategnl;?>&divGnlBillReport=ok&gnlpatient=ok&paVisit=<?php echo $paVisitgnl;?>&stringResult=<?php echo $stringResult;?>" style="text-align:center" id="dmacbillgnlpreview">
							
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
		
			<div style="overflow:auto;height:500px;background-color:none;margin-top:10px;">
				
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:10%">N°</th>
							<th style="width:12%">Date</th>
							<th style="width:8%">Bill number</th>
							<th style="width:20%">Full name</th>
							<th style="width:10%">Insurance type</th>
							<th style="width:10%">Type consultations</th>
							<th style="width:10%"><?php echo getString(39);?></th>
							<th style="width:10%"><?php echo getString(98);?></th>
							<th style="width:10%"><?php echo getString(99);?></th>
							<th style="width:10%"><?php echo 'Radiologie';?></th>
							<th style="width:10%"><?php echo 'Consommables';?></th>
							<th style="width:10%"><?php echo 'Medicaments';?></th>
							<th style="width:10%">Total Final</th>
						</tr> 
					</thead> 
					
					<tbody>
				<?php
				$TotalGnlTypeConsu=0;
				$TotalGnlMedConsu=0;
				$TotalGnlMedInf=0;
				$TotalGnlMedLabo=0;
				$TotalGnlMedRadio=0;
				$TotalGnlMedConsom=0;
				$TotalGnlMedMedoc=0;
				$TotalGnlPrice=0;
				
				$compteur=1;
				
					while($ligneGnlBillReport=$resultGnlBillReport->fetch())//on recupere la liste des éléments
					{
						$TotalDayPrice=0;
				?>
				
						<tr style="text-align:center;">
							<td><?php echo $compteur;?></td>
							<td><?php echo $ligneGnlBillReport->datebill;?></td>
							<td><?php echo $ligneGnlBillReport->numbill;?></td>
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneGnlBillReport->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
									
									echo '<td>'.$fullname.'</td>';
								}else{
									echo '<td></td>';
								}
								
							?>
							
							<td><?php echo $ligneGnlBillReport->nomassurance.' '.$ligneGnlBillReport->billpercent.' %';?></td>
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
								<?php
					
								$resultConsu=$connexion->prepare('SELECT *FROM consultations c, prestations_private p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
								$resultConsu->execute(array(
								'idbill'=>$ligneGnlBillReport->id_bill
								));
								
								$comptConsu=$resultConsu->rowCount();
								
								$resultConsu->setFetchMode(PDO::FETCH_OBJ);
								
								$resultAutreConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_typeconsult IS NULL AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
								$resultAutreConsu->execute(array(
								'idbill'=>$ligneGnlBillReport->id_bill
								));
								
								$comptAutreConsu=$resultAutreConsu->rowCount();
								$resultAutreConsu->setFetchMode(PDO::FETCH_OBJ);
									
								$TotalTypeConsu=0;
								
								if($comptConsu!=0)
								{
									while($ligneConsu=$resultConsu->fetch())
									{
										if($ligneConsu->prixtypeconsult!=0 AND $ligneConsu->prixrembou!=0)
										{
											$prixconsult=$ligneConsu->prixtypeconsult - $prixPrestaRembou;
										
										}else{
											$prixconsult=$ligneConsu->prixtypeconsult;

										}
										
										if($prixconsult>=0)
										{
									?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneConsu->nompresta;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixconsult;
											?>
											</td>
										</tr>
									<?php
											$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
										}
									}
								}
								
								if($comptAutreConsu!=0)
								{
									while($ligneAutreConsu=$resultAutreConsu->fetch())
									{
										if($ligneAutreConsu->prixautretypeconsult!=0 AND $ligneAutreConsu->prixrembou!=0)
										{
											$prixconsult=$ligneAutreConsu->prixautretypeconsult - $prixPrestaRembou;
										
										}else{
											$prixconsult=$ligneAutreConsu->prixautretypeconsult;

										}
										
										if($prixconsult>=0)
										{
									?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneAutreConsu->autretypeconsult;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixconsult;
											?>
											</td>
										</tr>
									<?php
											$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
										}
									}
								}
								?>
										
									<tr>
										<?php
										if($TotalTypeConsu!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php
											echo $TotalTypeConsu;
										
											$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
										?>
										</td>
									</tr>
								</table>
							
							</td>
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
								<?php
								
							$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
							$resultMedConsu->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedConsu=$resultMedConsu->rowCount();
							
							$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);
							
							$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc WHERE  mc.id_factureMedConsu=:idbill AND mc.id_prestationConsu IS NULL ORDER BY mc.id_medconsu DESC');
							$resultMedAutreConsu->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedAutreConsu=$resultMedAutreConsu->rowCount();

							$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);
							
							$TotalMedConsu=0;
								
						if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
						{
							if($comptMedConsu!=0)
							{
								while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
								{
									if($ligneMedConsu->prixprestationConsu!=0 AND $ligneMedConsu->prixrembouConsu!=0)
									{
										$prixPrestaRembou=$ligneMedConsu->prixrembouConsu;
										
										$prixconsu=$ligneMedConsu->prixprestationConsu - $prixPrestaRembou;
									
									}else{
										$prixconsu=$ligneMedConsu->prixprestationConsu;

									}
									
									if($prixconsu>=1)
									{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedConsu->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $prixconsu;
										?>
										</td>
									</tr>
							<?php
										$TotalMedConsu=$TotalMedConsu+$prixconsu;
									}
								}
							}
							
							if($comptMedAutreConsu!=0)
							{
								while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())
								{								
									if($ligneMedAutreConsu->prixautreConsu!=0 AND $ligneMedAutreConsu->prixrembouConsu!=0)
									{
										$prixPrestaRembou=$ligneMedAutreConsu->prixrembouConsu;
										
										$prixconsu=$ligneMedAutreConsu->prixautreConsu - $prixPrestaRembou;
									
									}else{
										$prixconsu=$ligneMedAutreConsu->prixautreConsu;

									}
									
									if($prixconsu>=1)
									{
							?>
									<tr>
										<td style="text-align:center">
										<?php						
											echo $ligneMedAutreConsu->autreConsu;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $prixconsu;
										?>
										</td>
									</tr>
						<?php
										$TotalMedConsu=$TotalMedConsu+$prixconsu;
									}
								}
							}

						}					
						?>
											
									<tr>
										<?php
										if($TotalMedConsu!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php
											echo $TotalMedConsu;
											
											$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
										?>
										</td>
									</tr>
								</table>
							
							</td>
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
								<?php
								
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
							$resultMedInf->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedInf=$resultMedInf->rowCount();
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
							
							
							$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_prestation IS NULL AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
							$resultMedAutreInf->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedAutreInf=$resultMedAutreInf->rowCount();
							
							$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);
							
								
								$TotalMedInf=0;					
							
						if($comptMedInf!=0 or $comptMedAutreInf!=0)
						{
							if($comptMedInf!=0)
							{
								while($ligneMedInf=$resultMedInf->fetch())
								{
									if($ligneMedInf->prixprestation!=0 AND $ligneMedInf->prixrembouInf!=0)
									{
										$prixPrestaRembou=$ligneMedInf->prixrembouInf;
										
										$prixinf=$ligneMedInf->prixprestation - $prixPrestaRembou;
									
									}else{
										$prixinf=$ligneMedInf->prixprestation;
																		
									}
									
									if($prixinf>=1)
									{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedInf->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $prixinf;
										?>
										</td>
									</tr>
								<?php
										$TotalMedInf=$TotalMedInf+$prixinf;
									}
								}
							}
							
							if($comptMedAutreInf!=0)
							{
								while($ligneMedAutreInf=$resultMedAutreInf->fetch())
								{	
									if($ligneMedAutreInf->prixautrePrestaM!=0 AND $ligneMedAutreInf->prixrembouInf!=0)
									{
										$prixPrestaRembou=$ligneMedAutreInf->prixrembouInf;
										
										$prixinf=$ligneMedAutreInf->prixautrePrestaM - $prixPrestaRembou;
									
									}else{
										$prixinf=$ligneMedAutreInf->prixautrePrestaM;
																		
									}
									
									if($prixinf>=1)
									{
								?>
									<tr>
										<td style="text-align:center">
										<?php						
											echo $ligneMedAutreInf->autrePrestaM;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $prixinf;
										?>
										</td>
									</tr>
									<?php
										$TotalMedInf=$TotalMedInf+$prixinf;
									}
								}
							}
						}					
						?>
										
									<tr>
										<?php						
										if($TotalMedInf!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php
											echo $TotalMedInf;
								
											$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
						<?php
									
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
							$resultMedLabo->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedLabo=$resultMedLabo->rowCount();
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_prestationExa IS NULL AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
							$resultMedAutreLabo->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
							
							$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);
							
							
							$TotalMedLabo=0;
							
						if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
						{
							if($comptMedLabo!=0)
							{
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
									if($ligneMedLabo->prixprestationExa!=0 AND $ligneMedLabo->prixrembouLabo!=0)
									{
										$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
														
										$prixlabo=$ligneMedLabo->prixprestationExa - $prixPrestaRembou;
									
									}else{
										$prixlabo=$ligneMedLabo->prixprestationExa;

									}
									
									if($prixlabo>=1)
									{
						?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedLabo->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $prixlabo;
										?>
										</td>
									</tr>
							<?php
										$TotalMedLabo=$TotalMedLabo+$prixlabo;
									}
								}
							}
							
							if($comptMedAutreLabo!=0)
							{
								while($ligneMedAutreLabo=$resultMedAutreLabo->fetch())//on recupere la liste des éléments
								{
									if($ligneMedAutreLabo->prixautreExamen!=0 AND $ligneMedAutreLabo->prixrembouLabo!=0)
									{
										$prixPrestaRembou=$ligneMedAutreLabo->prixrembouLabo;
										
										$prixlabo=$ligneMedAutreLabo->prixautreExamen - $prixPrestaRembou;
									
									}else{
										$prixlabo=$ligneMedAutreLabo->prixautreExamen;
																		
									}
									
									if($prixlabo>=1)
									{
							?>
									<tr>
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreLabo->autreExamen;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $prixlabo;
										?>
										</td>
									</tr>
						<?php
										$TotalMedLabo=$TotalMedLabo+$prixlabo;
									}
								}
							}

						}
						?>
										
									<tr>
										<?php
										
										if($TotalMedLabo!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php
											echo $TotalMedLabo;
											
											$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

							<?php
									
							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, prestations_private p WHERE mr.id_prestationRadio=p.id_prestation AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
							$resultMedRadio->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedRadio=$resultMedRadio->rowCount();
							
							$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$resultMedAutreRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_prestationRadio IS NULL AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
							$resultMedAutreRadio->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedAutreRadio=$resultMedAutreRadio->rowCount();
							
							$resultMedAutreRadio->setFetchMode(PDO::FETCH_OBJ);
							
							
							$TotalMedRadio=0;
							
						if($comptMedRadio!=0 or $comptMedAutreRadio!=0)
						{
							if($comptMedRadio!=0)
							{
								while($ligneMedRadio=$resultMedRadio->fetch())
								{
									if($ligneMedRadio->prixprestationRadio!=0 AND $ligneMedRadio->prixrembouRadio!=0)
									{
										$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
										
										$prixradio=$ligneMedRadio->prixprestationRadio - $prixPrestaRembou;
									
									}else{
										$prixradio=$ligneMedRadio->prixprestationRadio;
																		
									}
									
									if($prixradio>=1)
									{
						?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedRadio->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $prixradio;
										?>
										</td>
									</tr>
							<?php
										$TotalMedRadio=$TotalMedRadio+$prixradio;
									}
								}
							}
							
							if($comptMedAutreRadio!=0)
							{
								while($ligneMedAutreRadio=$resultMedAutreRadio->fetch())//on recupere la liste des éléments
								{
									if($ligneMedAutreRadio->prixautreRadio!=0 AND $ligneMedAutreRadio->prixrembouRadio!=0)
									{
										$prixPrestaRembou=$ligneMedAutreRadio->prixrembouRadio;
										
										$prixradio=$ligneMedAutreRadio->prixautreRadio - $prixPrestaRembou;
									
									}else{
										$prixradio=$ligneMedAutreRadio->prixautreRadio;
									
									}
									
									if($prixradio>=1)
									{
							?>
									<tr>
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreRadio->autreRadio;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $prixradio;
										?>
										</td>
									</tr>
						<?php
										$TotalMedRadio=$TotalMedRadio+$prixradio;
									}
								}
							}

						}					
						?>										
									<tr>
										<?php
										
										if($TotalMedRadio!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php
											echo $TotalMedRadio;
											
											$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

							<?php
									
							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, prestations_private p WHERE mco.id_prestationConsom=p.id_prestation AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
							$resultMedConsom->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedConsom=$resultMedConsom->rowCount();
							
							$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$resultMedAutreConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.id_prestationConsom=0 AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
							$resultMedAutreConsom->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedAutreConsom=$resultMedAutreConsom->rowCount();
							
							$resultMedAutreConsom->setFetchMode(PDO::FETCH_OBJ);
							
							
							$TotalMedConsom=0;
							
						if($comptMedConsom!=0 or $comptMedAutreConsom!=0)
						{
							if($comptMedConsom!=0)
							{
								while($ligneMedConsom=$resultMedConsom->fetch())
								{
									if($ligneMedConsom->prixprestationConsom!=0 AND $ligneMedConsom->prixrembouConsom!=0)
									{
										$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
										
										$prixconsom=($ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom) - $prixPrestaRembou;
									
									}else{
										$prixconsom=$ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom;
									
									}
									
									if($prixconsom!=0)
									{
						?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedConsom->nompresta;
										?>
										</td>
										
										<!--
										<td style="text-align:center">
										<?php							
											echo $ligneMedConsom->qteConsom;
										?>
										</td>
										-->
										
										<td style="text-align:center">
										<?php
											echo $prixconsom;
										?>
										</td>
									</tr>
							<?php
										$TotalMedConsom=$TotalMedConsom+$prixconsom;
									}
								}
							}
							
							if($comptMedAutreConsom!=0)
							{
								while($ligneMedAutreConsom=$resultMedAutreConsom->fetch())//on recupere la liste des éléments
								{
									if($ligneMedAutreConsom->prixautreConsom!=0 AND $ligneMedAutreConsom->prixrembouConsom!=0)
									{
										$prixPrestaRembou=$ligneMedAutreConsom->prixrembouConsom;
										
										$prixconsom=($ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom) - $prixPrestaRembou;
									
									}else{
										$prixconsom=$ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom;
									
									}
									
									if($prixconsom!=0)
									{
							?>
									<tr>
										<td style="text-align:center">
										<?php							
											echo $ligneMedAutreConsom->autreConsom;
										?>
										</td>
										
										<!--
										<td style="text-align:center">
										<?php							
											echo $ligneMedAutreConsom->qteConsom;
										?>
										</td>
										-->
										
										<td style="text-align:center">
										<?php
										
											echo $prixconsom;
										?>
										</td>
									</tr>
						<?php
										$TotalMedConsom=$TotalMedConsom+$prixconsom;
									}
								}
							}

						}					
						?>
										
									<tr>
										<?php
										
										if($TotalMedConsom!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center" colspan=2>
										<?php
											echo $TotalMedConsom;
											
											$TotalDayPrice=$TotalDayPrice+$TotalMedConsom;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

							<?php
									
							$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, prestations_private p WHERE mdo.id_prestationMedoc=p.id_prestation AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
							$resultMedMedoc->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedMedoc=$resultMedMedoc->rowCount();
							
							$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$resultMedAutreMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.id_prestationMedoc=0 AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
							$resultMedAutreMedoc->execute(array(
							'idbill'=>$ligneGnlBillReport->id_bill
							));
							
							$comptMedAutreMedoc=$resultMedAutreMedoc->rowCount();
							
							$resultMedAutreMedoc->setFetchMode(PDO::FETCH_OBJ);
							
							
							$TotalMedMedoc=0;
							
						if($comptMedMedoc!=0 or $comptMedAutreMedoc!=0)
						{
							if($comptMedMedoc!=0)
							{
								while($ligneMedMedoc=$resultMedMedoc->fetch())
								{
									if($ligneMedMedoc->prixprestationMedoc!=0 AND $ligneMedMedoc->prixrembouMedoc!=0)
									{
										$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
					
										$prixmedoc=($ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc) - $prixPrestaRembou;
									
									}else{
										$prixmedoc=$ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc;
									
									}
									
									if($prixmedoc!=0)
									{
						?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedMedoc->nompresta;
										?>
										</td>
										
										<!--
										<td style="text-align:center">
										<?php
										if($ligneMedMedoc->prixrembouMedoc==0)
											echo $ligneMedMedoc->qteMedoc;
										else
											echo '-';
										?>
										</td>
										-->
										
										<td style="text-align:center">
										<?php
											echo $prixmedoc;
										?>
										</td>
									</tr>
							<?php
										$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
									}
								}
							}
							
							if($comptMedAutreMedoc!=0)
							{
								while($ligneMedAutreMedoc=$resultMedAutreMedoc->fetch())//on recupere la liste des éléments
								{
									if($ligneMedAutreMedoc->prixautreMedoc!=0 AND $ligneMedAutreMedoc->prixrembouMedoc!=0)
									{
										$prixPrestaRembou=$ligneMedAutreMedoc->prixrembouMedoc;
					
										$prixmedoc=($ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc) - $prixPrestaRembou;
									
									}else{
										$prixmedoc=$ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc;
									
									}
									
									if($prixmedoc!=0)
									{
							?>
									<tr>
										<td style="text-align:center">
										<?php							
											echo $ligneMedAutreMedoc->autreMedoc;
										?>
										</td>
										
										<!--
										<td style="text-align:center">
										<?php
										if($ligneMedAutreMedoc->prixrembouMedoc==0)
											echo $ligneMedAutreMedoc->qteMedoc;
										else
											echo '-';
										?>
										</td>
										-->
										
										<td style="text-align:center">
										<?php
											echo $prixmedoc;
										?>
										</td>
									</tr>
						<?php
										$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
									}
								}
							}

						}					
						?>
										
									<tr>
										<?php							
										if($TotalMedMedoc!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center" colspan=2>
										<?php
											echo $TotalMedMedoc;
											
											$TotalDayPrice=$TotalDayPrice+$TotalMedMedoc;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td><?php echo $TotalDayPrice;?></td>
						</tr>
					<?php
							$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
							$TotalGnlMedConsu= $TotalGnlMedConsu + $TotalMedConsu;
							$TotalGnlMedInf= $TotalGnlMedInf + $TotalMedInf;
							$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
							$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
							$TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
							$TotalGnlMedMedoc=$TotalGnlMedMedoc + $TotalMedMedoc;
							$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;
							
							$compteur++;

					}
					?>
						<tr style="text-align:center;">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlTypeConsu;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedConsu;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedInf;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedLabo;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedRadio;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedConsom;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedMedoc;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlPrice;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center;">No report for this search</th>
						</tr> 
					</thead> 
				</table> 
				
			<?php
			}
			?>
			</div>
		
		<?php
		}
		?>
		
		<div style="overflow:auto;height:500px;background-color:none;">
		
			<div id="divGnlMedicReport" style="display:none">
			
			<?php
			
				$resultConsult=$connexion->query('SELECT *FROM consultations c ORDER BY c.dateconsu DESC');		
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptConsult=$resultConsult->rowCount();
			

				if($comptConsult != 0)
				{
			?>
				<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:95%;"> 
					<thead> 
						<tr>
							<th style="width:10%">N°</th>
							<th style="width:10%">Date</th>
							<th style="width:15%">Name</th>
							<th style="width:20%"><?php echo getString(113);?></th>
							<th style="width:15%"><?php echo getString(39);?></th>
							<th style="width:15%"><?php echo getString(98);?></th>
							<th style="width:15%"><?php echo getString(99);?></th>
						</tr> 
					</thead> 


					<tbody>
				<?php
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())//on recupere la liste des éléments
					{
				?>
				
					<tr style="text-align:center;">
						<td><?php echo $compteur; ?></td>
						<td><?php echo $ligneConsult->dateconsu; ?></td>
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								
								echo '<td>'.$fullname.'</td>';
							}else{
								echo '<td></td>';
							}
							
						?>
						
						<td style="padding:0 10px;">
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
							'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							
							if($lignePresta->namepresta!='')
							{
								echo $lignePresta->namepresta.'</td>';
							}else{								
								echo $lignePresta->nompresta.'</td>';
							}
						}
						?>
						<td>
						<?php
						
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedConsult=$resultMedConsult->rowCount();
					
					
						if($comptMedConsult != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
						<?php
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
						?>
								<tr style="text-align:center;">
									
									<td>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsult->id_prestationConsu
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
									}
										echo $ligneMedConsult->autreConsu.'</td>';
									?>
								</tr>
						<?php
								}
						?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>
						
						<td>
						<?php
						
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(				
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedInf=$resultMedInf->rowCount();
					
					
						if($comptMedInf != 0)
						{
						?>		
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						
							<tbody>
						<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
									}
									
										echo $ligneMedInf->autrePrestaM.'</td>';
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
						</td>
						
						<td>
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(			
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

						$comptMedLabo=$resultMedLabo->rowCount();


						if($comptMedLabo != 0)
						{
						?>	
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
							<tbody>
						<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
											}else{
												echo $lignePresta->nompresta.'</td>';
											}
										}
										
											echo $ligneMedLabo->autreExamen;
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
						</td>
					</tr>
				<?php
						$compteur++;
					}
				?>
					</tbody>
				</table>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%">No General Medical Report</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
			</div>

		
		<?php
		/* 
		if(isset($_GET['selectGnlBill']))
		{
		?>
			<div id="divGnlBillReport" style="display:none">
			
				<?php
				
				$resultGnlBillReport=$connexion->query('SELECT *FROM bills WHERE numero != "" ORDER BY datebill DESC');
				
				$resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptBillReport=$resultGnlBillReport->rowCount();
				
				if($comptBillReport!=0)
				{
				?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:10%">N°</th>
							<th style="width:12%">Date</th>
							<th style="width:8%">Bill number</th>
							<th style="width:20%">Full name</th>
							<th style="width:10%">Insurance</th>
							<th style="width:10%">Consultation</th>
							<th style="width:10%"><?php echo getString(39);?></th>
							<th style="width:10%"><?php echo getString(98);?></th>
							<th style="width:10%"><?php echo getString(99);?></th>
							<th style="width:10%">Total Final</th>
						</tr> 
					</thead> 
					
					<tbody>
				<?php
				$TotalGnlTypeConsu=0;
				$TotalGnlMedConsu=0;
				$TotalGnlMedInf=0;
				$TotalGnlMedLabo=0;
				$TotalGnlPrice=0;
				
				$compteur=1;
				
					while($ligneGnlBillReport=$resultGnlBillReport->fetch())//on recupere la liste des éléments
					{
				?>
				
						<tr style="text-align:center;">
						
							<td><?php echo $compteur;?></td>
							<td><?php echo $ligneGnlBillReport->datebill;?></td>
							<td><?php echo $ligneGnlBillReport->numbill;?></td>
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneGnlBillReport->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
									
									echo '<td>'.$fullname.'</td>';
								}else{
									echo '<td></td>';
								}
								
							?>
							
							<td><?php echo $ligneGnlBillReport->nomassurance.' '.$ligneGnlBillReport->billpercent.' %';?></td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
								<?php
					
								$resultConsu=$connexion->prepare('SELECT *FROM consultations c, prestations_private p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
								$resultConsu->execute(array(
								'idbill'=>$ligneGnlBillReport->id_bill
								));
								
								$comptConsu=$resultConsu->rowCount();
								
								$resultConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								if($comptConsu!=0)
								{
									while($ligneConsu=$resultConsu->fetch())//on recupere la liste des éléments
									{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneConsu->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneConsu->prixpresta;
										?>
										</td>
									</tr>
								<?php
									}
								}
								?>
										
									<tr>
										<?php
										
										if($ligneGnlBillReport->totaltypeconsuprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneGnlBillReport->totaltypeconsuprice;
										?>
										</td>
									</tr>
								</table>
							
							</td>
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
								<?php
								
						$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
						$resultMedConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedConsu=$resultMedConsu->rowCount();
						
						$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE  mc.id_factureMedConsu=:idbill GROUP BY id_medconsu ORDER BY mc.id_medconsu DESC');
						$resultMedAutreConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
						
						$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
					{
						if($comptMedConsu!=0)
						{
							while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
							{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedConsu->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneMedConsu->prixpresta;
										?>
										</td>
									</tr>
								<?php
								
							}
						}
						
						if($comptMedAutreConsu!=0)
						{
							while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())//on recupere la liste des éléments
							{
								
								if($ligneMedAutreConsu->prixautreConsu!=0 and $ligneMedAutreConsu->autreConsu!="")
								{
								?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreConsu->autreConsu;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreConsu->prixautreConsu;
									
									?>
									</td>
								</tr>
								<?php
								}
							}
						}

					}					
					?>
										
									<tr>
										<?php
										
										if($ligneGnlBillReport->totalmedconsuprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneGnlBillReport->totalmedconsuprice;
										?>
										</td>
									</tr>
								</table>
							
							</td>
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
								<?php
								
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
						$resultMedInf->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedInf=$resultMedInf->rowCount();
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_factureMedInf=:idbill GROUP BY id_medinf ORDER BY mi.id_medinf DESC');
						$resultMedAutreInf->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedAutreInf=$resultMedAutreInf->rowCount();
						
						$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($comptMedInf!=0 or $comptMedAutreInf!=0)
					{
						if($comptMedInf!=0)
						{
							while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des éléments
							{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedInf->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneMedInf->prixpresta;
										?>
										</td>
									</tr>
								<?php
								
							}
						}
						
						if($comptMedAutreInf!=0)
						{
							while($ligneMedAutreInf=$resultMedAutreInf->fetch())//on recupere la liste des éléments
							{
								
								if($ligneMedAutreInf->prixautrePrestaM != 0 and $ligneMedAutreInf->autrePrestaM != "")
								{
								?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreInf->autrePrestaM;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreInf->prixautrePrestaM;
									
									?>
									</td>
								</tr>
								<?php
								}
							}
						}

					}					
					?>
										
									<tr>
										<?php
										
										if($ligneGnlBillReport->totalmedinfprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneGnlBillReport->totalmedinfprice;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
					<?php
								
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedLabo=$resultMedLabo->rowCount();
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_factureMedLabo=:idbill GROUP BY id_medlabo ORDER BY ml.id_medlabo DESC');
						$resultMedAutreLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
						
						$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
					{
						if($comptMedLabo!=0)
						{
							while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des éléments
							{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedLabo->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneMedLabo->prixpresta;
										?>
										</td>
									</tr>
								<?php
								
							}
						}
						
						if($comptMedAutreLabo!=0)
						{
							while($ligneMedAutreLabo=$resultMedAutreInf->fetch())//on recupere la liste des éléments
							{
								
								if($ligneMedAutreLabo->prixautreExamen != 0 and $ligneMedAutreLabo->autreExamen != "")
								{
								?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreLabo->autreExamen;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreLabo->prixautreExamen;
									
									?>
									</td>
								</tr>
								<?php
								}
							}
						}

					}					
					?>
										
									<tr>
										<?php
										
										if($ligneGnlBillReport->totalmedlaboprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneGnlBillReport->totalmedlaboprice;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td><?php echo $ligneGnlBillReport->totalgnlprice;?></td>
						</tr>
					<?php
							$TotalGnlTypeConsu=$TotalGnlTypeConsu + $ligneGnlBillReport->totaltypeconsuprice;
							$TotalGnlMedConsu= $TotalGnlMedConsu + $ligneGnlBillReport->totalmedconsuprice;
							$TotalGnlMedInf= $TotalGnlMedInf + $ligneGnlBillReport->totalmedinfprice;
							$TotalGnlMedLabo=$TotalGnlMedLabo + $ligneGnlBillReport->totalmedlaboprice;
							$TotalGnlPrice=$TotalGnlPrice + $ligneGnlBillReport->totalgnlprice;
						
							$compteur++;
						
					}
					?>
						<tr style="text-align:center;">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlTypeConsu;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedConsu;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedInf;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedLabo;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlPrice;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%">No General Billing Report</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
			</div>
		
		<?php
		}
		*/
		?>
		
		</div>
	<?php
	}
	
	
	if(isset($_GET['gnlreporthosp']))
	{
	?>
		<table style="margin:auto;">
			<tr>
				<?php
				if(isset($_GET['selectGnlMedicHosp']))
				{
				?>
				<td style="font-size:18px; width:33.333%; " id="gnlmedicalstring">
					<b><h2>General Medical Hospital Report</h2></b>
				</td>
				<?php
				}else{
					if(isset($_GET['selectGnlBill']))
					{
				?>
					<td style="font-size:18px; width:33.333%;" id="gnlbillstring">
						<b><h2>General Billing Hospital Report</h2></b>
					</td>
				<?php
					}else{
				?>
						<td style="font-size:18px; width:33.333%;" id="gnlbillstring">
							<b><h2>General Hospital Report</h2></b>
						</td>
				<?php
					}
				}
				?>
			</tr>
		</table>
		
		
		<table cellpadding=3 style="margin:auto auto 10px auto; padding: 10px; width:95%;">
			<tr>
				<td style="font-size:18px; text-align:center; width:33.333%;">
				
					<a href="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreporthosp=ok&selectGnlMedicHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;display:none;">
					
						<button id="gnlMedicReport" class="btn-large" onclick="ShowDivReport('divGnlMedicReport')" >Medical Report</button>
						
					</a>
					
					<a href="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreporthosp=ok&selectGnlBillHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center">
					
					<button id="gnlBillReport" class="btn-large" onclick="ShowDivReport('divGnlBillReport')">Billing Report</button>						
					</a>
					
				</td>
			</tr>
		</table>
	
		<?php
		if(isset($_GET['selectGnlMedicHosp']))
		{
		?>
		<div id="selectdateGnlMedicReport" style="display:inline">
		
			<form action="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreporthosp=ok&dmacgnl=ok&selectGnlMedicHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">

				<table id="dmacgnl" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailygnlbtn" onclick="ShowSelectGnl('dailymedicGnl')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlygnlbtn" onclick="ShowSelectGnl('monthlymedicGnl')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualygnlbtn" onclick="ShowSelectGnl('annualymedicGnl')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="customgnlbtn" onclick="ShowSelectGnl('custommedicGnl')" class="btn">Custom</span>
						</td>
					</tr>
					
					<tr style="visibility:visible">
					
						<td id="dailymedicGnl" style="display:none">Select date
							<input type="text" id="dailydateGnl" name="dailydateGnl" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlymedicGnl" style="display:none">Select Month
						
							<select name="monthlydateGnl" id="monthlydateGnl" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydateGnlYear" id="monthlydateGnlYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualymedicGnl" style="display:none">Select Year
						
							<select name="annualydateGnl" id="annualydateGnl" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custommedicGnl" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutGnl" name="customdatedebutGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinGnl" name="customdatefinGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomGnl" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				
				</table>


			</form>
			
		</div>
		<?php
		}
		?>
		
		
		<?php
		if(isset($_GET['selectGnlBillHosp']))
		{
		?>
		<div id="selectdateGnlBillReport" style="display:inline">
		
			<form action="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreporthosp=ok&dmacbillgnl=ok&selectGnlBillHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">

				<table id="dmacbillgnl" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailygnlbtn" onclick="ShowSelectreportGnl('dailybillGnl')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlygnlbtn" onclick="ShowSelectreportGnl('monthlybillGnl')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualygnlbtn" onclick="ShowSelectreportGnl('annualybillGnl')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="customgnlbtn" onclick="ShowSelectreportGnl('custombillGnl')" class="btn">Custom</span>
						</td>
					</tr>
					
					<tr style="visibility:visible">
					
						<td id="dailybillGnl" style="display:none">Select Date
							<input type="text" id="dailydatebillGnl" name="dailydatebillGnl" onclick="ds_sh(this);" value=""/>
						
							<!--
							<select name="dailypercbillGnl" id="dailypercbillGnl" style="width:60px;height:40px;">
							<?php 
							for($j=0;$j<=100;$j++)
							{
							?>
								<option value='<?php echo $j;?>'><?php echo $j;?></option>
							<?php 
							}
							?>
							</select><span style="font-size:100%; font-weight:normal;margin-right:50px;">%</span>
							-->
							
							<button type="submit"  name="searchdailybillGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlybillGnl" style="display:none">Select Month
						
							<select name="monthlydatebillGnl" id="monthlydatebillGnl" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatebillGnlYear" id="monthlydatebillGnlYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<!--
							<select name="monthlypercbillGnl" id="monthlypercbillGnl" style="width:60px;height:40px;">
							<?php 
							for($j=0;$j<=100;$j++)
							{
							?>
								<option value='<?php echo $j;?>'><?php echo $j;?></option>
							<?php 
							}
							?>
							</select><span style="font-size:100%; font-weight:normal;margin-right:50px;">%</span>
							-->
							
							<button type="submit"  name="searchmonthlybillGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualybillGnl" style="display:none">Select Year
						
							<select name="annualydatebillGnl" id="annualydatebillGnl" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<!--
							<select name="annualypercbillGnl" id="annualypercbillGnl" style="width:60px;height:40px;">
							<?php 
							for($j=0;$j<=100;$j++)
							{
							?>
								<option value='<?php echo $j;?>'><?php echo $j;?></option>
							<?php 
							}
							?>
							</select><span style="font-size:100%; font-weight:normal;margin-right:50px;">%</span>
							-->
							
							<button type="submit"  name="searchannualybillGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custombillGnl" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutbillGnl" name="customdatedebutbillGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinbillGnl" name="customdatefinbillGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td>
										<!--
										<select name="custompercbillGnl" id="custompercbillGnl" style="width:60px;height:40px;">
										<?php 
										for($j=0;$j<=100;$j++)
										{
										?>
											<option value='<?php echo $j;?>'><?php echo $j;?></option>
										<?php 
										}
										?>
										</select><span style="font-size:100%; font-weight:normal;margin-right:50px;">%</span>
										-->
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustombillGnl" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				
				</table>

			</form>
			
		</div>
			
		<?php
		}
		?>
			<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
				<tr>
					<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
			</table>

	<?php
		
		if(isset($_GET['dmacgnl']) OR isset($_GET['selectGnlMedicHosp']))
		{
			$dailydategnl = "WHERE c.dateconsu != '0000-00-00'";
			$paVisitgnl="gnlGnlMedic";
			
			if(isset($_POST['searchdailyGnl']))
			{
				if(isset($_POST['dailydateGnl']))
				{
					$dailydategnl = 'WHERE dateconsu=\''.$_POST['dailydateGnl'].'\'';
					
					$paVisitgnl="dailyGnlMedic";
				
				}

			}
			
			if(isset($_POST['searchmonthlyGnl']))
			{
				if(isset($_POST['monthlydateGnl']) AND isset($_POST['monthlydateGnlYear']))
				{
					$ukwezi = $_POST['monthlydateGnl'];
					$umwaka = $_POST['monthlydateGnlYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydategnl = 'WHERE dateconsu>=\''.$umwaka.'-'.$ukwezi.'-1\' AND dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
					
					$paVisitgnl="monthlyGnlMedic";
					
				}

			}
			
			if(isset($_POST['searchannualyGnl']))
			{
				if(isset($_POST['annualydateGnl']))
				{
					$year = $_POST['annualydateGnl'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydategnl = 'WHERE dateconsu>=\''.$year.'-01-01\' AND dateconsu<=\''.$year.'-12-31\'';
					
					$paVisitgnl="annualyGnlMedic";
			
				}
			
			}
			
			if(isset($_POST['searchcustomGnl']))
			{
				if(isset($_POST['customdatedebutGnl']) AND isset($_POST['customdatefinGnl']))
				{
					$debut = $_POST['customdatedebutGnl'];
					$fin = $_POST['customdatefinGnl'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydategnl = 'WHERE dateconsu>=\''.$debut.'\' AND dateconsu<=\''.$fin.'\'';
					$paVisitgnl="customGnlMedic";
			
				}

			}
			
				// echo $dailydategnl;
				// echo $ukwezi.' et '.$year;
				// echo $year;

		?>
			<div id="divGnlMedicReport" style="display:inline">
			
				<?php
			
				$resultConsult=$connexion->query("SELECT *FROM consultations c ".$dailydategnl." AND c.dateconsu != '0000-00-00' ORDER BY c.dateconsu DESC");		
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptConsult=$resultConsult->rowCount();
			

				if($comptConsult != 0)
				{
				?>
				
				<a href="dmacreport.php?dailydategnl=<?php echo $dailydategnl;?>&divGnlMedicReport=ok&gnlpatient=ok&paVisit=<?php echo $paVisitgnl;?>" style="text-align:center" id="dmacmedicalgnlpreview">
					
					<button style="width:250px; margin:auto;" type="submit" name="printMedicReportGnl" id="printMedicReportGnl" class="btn-large-inversed">
						<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
					</button>
				
				</a>
				
				<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:95%;"> 
					<thead> 
						<tr>
							<th style="width:10%">N°</th>
							<th style="width:10%">Date</th>
							<th style="width:15%">Name</th>
							<th style="width:20%"><?php echo getString(113);?></th>
							<th style="width:15%"><?php echo getString(39);?></th>
							<th style="width:15%"><?php echo getString(98);?></th>
							<th style="width:15%"><?php echo getString(99);?></th>
						</tr> 
					</thead> 


					<tbody>
				<?php
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())//on recupere la liste des éléments
					{
				?>
				
					<tr style="text-align:center;">
						<td><?php echo $compteur; ?></td>
						<td><?php echo $ligneConsult->dateconsu; ?></td>
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								
								echo '<td>'.$fullname.'</td>';
							}else{
								echo '<td></td>';
							}
							
						?>
						
						<td style="padding:0 10px;">
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
							'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							
							if($lignePresta->namepresta!='')
							{
								echo $lignePresta->namepresta.'</td>';
							}else{								
								echo $lignePresta->nompresta.'</td>';
							}
						}
						?>
						<td>
						<?php
						
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedConsult=$resultMedConsult->rowCount();
					
					
						if($comptMedConsult != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
						<?php
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
						?>
								<tr style="text-align:center;">
									
									<td>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsult->id_prestationConsu
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
									}
										echo $ligneMedConsult->autreConsu.'</td>';
									?>
								</tr>
						<?php
								}
						?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>
						
						<td>
						<?php
						
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(				
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedInf=$resultMedInf->rowCount();
					
					
						if($comptMedInf != 0)
						{
						?>		
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						
							<tbody>
						<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
									}
									
										echo $ligneMedInf->autrePrestaM.'</td>';
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
						</td>
						
						<td>
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(			
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

						$comptMedLabo=$resultMedLabo->rowCount();


						if($comptMedLabo != 0)
						{
						?>	
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
							<tbody>
						<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
											}else{
												echo $lignePresta->nompresta.'</td>';
											}
										}
										
											echo $ligneMedLabo->autreExamen;
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
						</td>
					</tr>
				<?php
						$compteur++;
					}
				?>
					</tbody>
				</table>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%">No General Medical Report</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
			</div>
				
		<?php
		}
		?>

		<?php
		
		if(isset($_GET['dmacbillgnl']))
		{
			$stringResult = "";
			$dailydategnl = "";
			$paVisitgnl="gnlGnlBill";
			
			if(isset($_POST['searchdailybillGnl']))
			{
				if(isset($_POST['dailydatebillGnl']))
				{
					// $percent = $_POST['dailypercbillGnl'];
					
					$dailydategnl = 'dateSortie LIKE \''.$_POST['dailydatebillGnl'].'%\' AND statusPaHosp=0';
					
					$paVisitgnl="dailyGnlBill";
				
					$stringResult=$_POST['dailydatebillGnl'];
				
				}
			}
			
			if(isset($_POST['searchmonthlybillGnl']))
			{
				if(isset($_POST['monthlydatebillGnl']) AND isset($_POST['monthlydatebillGnlYear']))
				{
					$ukwezi = $_POST['monthlydatebillGnl'];
					
					if($ukwezi<10)
					{
						$ukwezi = '0'.$_POST['monthlydatebillGnl'];
					}else{						
						$ukwezi = $_POST['monthlydatebillGnl'];
					}
					
					$umwaka = $_POST['monthlydatebillGnlYear'];
					// $percent = $_POST['monthlypercbillGnl'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					if($daysmonth<10)
					{
						$daysmonth='0'.$daysmonth;
					}
					
					$paVisitgnl="monthly";
					
					$dailydategnl = 'dateSortie>=\''.$umwaka.'-'.$ukwezi.'-01\' AND (dateSortie<\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' OR dateSortie LIKE \''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'%\') AND statusPaHosp=0';
			
					$paVisitgnl="monthlyGnlBill";
					
					$stringResult=date("F",mktime(0,0,0,$ukwezi,10))."-".$umwaka;
					
				}

			}
			
			if(isset($_POST['searchannualybillGnl']))
			{
				if(isset($_POST['annualydatebillGnl']))
				{
					$year = $_POST['annualydatebillGnl'];
					// $percent = $_POST['annualypercbillGnl'];
					
					$dailydategnl = 'dateSortie>=\''.$year.'-01-01\' AND dateSortie<=\''.$year.'-12-31\' AND statusPaHosp=0';
					
					$paVisitgnl="annualyGnlBill";
					
					$stringResult=$_POST['annualydatebillGnl'];
			
			
				}
			
			}
			
			if(isset($_POST['searchcustombillGnl']))
			{
				if(isset($_POST['customdatedebutbillGnl']) AND isset($_POST['customdatefinbillGnl']))
				{
					$debut = $_POST['customdatedebutbillGnl'];
					$fin = $_POST['customdatefinbillGnl'];
					// $percent = $_POST['custompercbillGnl'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydategnl = 'dateSortie>=\''.$debut.'\' AND (dateSortie<\''.$fin.'\' OR dateSortie LIKE \''.$fin.'%\') AND statusPaHosp=0';
					$paVisitgnl="customGnlBill";
					
					$stringResult="[ ".$debut."/".$fin." ]";
			
				}

			}
			
				// echo $dailydategnl;
				// echo $ukwezi.' et '.$year;
				// echo $year;
		
		?>
		
			<div id="divGnlBillReport" style="display:inline">
			
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

			$resultGnlBillReport=$connexion->query('SELECT *FROM patients_hosp ph WHERE '.$dailydategnl.' ORDER BY dateSortie DESC');

			$resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptBillReport=$resultGnlBillReport->rowCount();
			
			if($comptBillReport!=0)
			{
			?>
				
			<table style="width:100%;">
				<tr>
					<td style="text-align:left; width:33.333%;">
				
						<a href="dmacreporthosp.php?dailydategnl=<?php echo $dailydategnl;?>&divGnlBillReport=ok&gnlpatient=ok&paVisit=<?php echo $paVisitgnl;?>&stringResult=<?php echo $stringResult;?>&createRN=1" style="text-align:center" id="dmacbillgnlpreview">
							
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
		
			<div style="overflow:auto;height:500px;background-color:none;margin-top:10px;">
			
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:5%">N°</th>
							<th style="width:8%">Bill number</th>
							<th style="width:8%">Insurance</th>
							<th style="width:12%">N° carte assurance</th>
							<th style="width:20%">Full name</th>
							<th style="width:12%">Date Entrée</th>
							<th style="width:12%">Date Sortie</th>
							<th style="width:5%">Nbre de jours</th>
							<th style="width:10%">P/Days</th>
							<th style="width:10%">Prix Total</th>
							<th style="width:10%"><?php echo getString(39);?></th>
							<th style="width:10%"><?php echo getString(98);?></th>
							<th style="width:10%"><?php echo getString(99);?></th>
							<th style="width:10%"><?php echo 'Radiologie';?></th>
							<th style="width:10%"><?php echo 'Consommables';?></th>
							<th style="width:10%"><?php echo 'Medicaments';?></th>
							<th style="width:10%">Total Final</th>
						</tr> 
					</thead> 
					
					<tbody>
					<?php
					$TotalGnlTypeConsu=0;
					$TotalGnlMedConsu=0;
					$TotalGnlMedInf=0;
					$TotalGnlMedLabo=0;
					$TotalGnlMedRadio=0;
					$TotalGnlMedConsom=0;
					$TotalGnlMedMedoc=0;
					$TotalGnlPrice=0;
					
					$compteur=1;
					
					while($ligneGnlBillReport=$resultGnlBillReport->fetch())//on recupere la liste des éléments
					{
						$TotalDayPrice=0;
						
						
						$idassu = $ligneGnlBillReport->id_assuHosp;

						$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

						$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
								
						$assuCount = $comptAssuConsu->rowCount();

						for($h=1;$h<=$assuCount;$h++)
						{
							$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
							$getAssuConsu->execute(array(
							'idassu'=>$idassu
							));
							
							$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

							if($ligneNomAssu=$getAssuConsu->fetch())
							{
								$presta_assu='prestations_'.$ligneNomAssu->nomassurance;
								$nomassu=$ligneNomAssu->nomassurance;
							}
						}

					?>
					<tr style="text-align:center;">
						<td><?php echo $compteur;?></td>
						<td><?php echo $ligneGnlBillReport->id_factureHosp;?></td>
						<td><?php echo $nomassu;?></td>
						
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneGnlBillReport->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->full_name;
								$carteassuid = $ligneGnlBillReport->idcardbillHosp;
								
								echo '<td>'.$carteassuid.'</td>';
								echo '<td>'.$fullname.'</td>';
							}else{
								echo '<td></td>';
								echo '<td></td>';
							}
							
						?>
						
						<td><?php echo $ligneGnlBillReport->dateEntree.' à '.$ligneGnlBillReport->heureEntree;?></td>
						<td><?php echo $ligneGnlBillReport->dateSortie.' à '.$ligneGnlBillReport->heureSortie;?></td>
				
						<td>
						<?php
						
						$dateIn=strtotime($ligneGnlBillReport->dateEntree);
						$dateOut=strtotime($ligneGnlBillReport->dateSortie);
						
						$datediff= abs($dateOut - $dateIn);
						
						$nbrejrs= floor($datediff /(60*60*24));
						
						if($nbrejrs==0)
						{
							$nbrejrs=1;
						}
							echo $nbrejrs;
						?>
						</td>
						
						<td>
						<?php
									
						$prixroom=$ligneGnlBillReport->prixroom;
						echo $prixroom;
						?>									
						</td>
						
						<td>
						<?php
						$balance=$prixroom*$nbrejrs;
						echo $balance;
												
						$TotalTypeConsu=0;
						
						$TotalTypeConsu=$TotalTypeConsu+$balance;
					
						$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
						?>									
						</td>
						
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
							<?php
							
						$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult_hosp mc, '.$presta_assu.' p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
						$resultMedConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedConsu=$resultMedConsu->rowCount();
						
						$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);
						
						$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.id_factureMedConsu=:idbill AND mc.id_prestationConsu IS NULL ORDER BY mc.id_medconsu DESC');
						$resultMedAutreConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
						$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedConsu=0;
						
					if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
					{
						if($comptMedConsu!=0)
						{
							while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
							{
								if($ligneMedConsu->prixprestationConsu!=0 AND $ligneMedConsu->prixrembouConsu!=0)
								{
									$prixPrestaRembou=$ligneMedConsu->prixrembouConsu;
									
									$prixconsu=$ligneMedConsu->prixprestationConsu - $prixPrestaRembou;
								
								}else{
									$prixconsu=$ligneMedConsu->prixprestationConsu;

								}
								
								if($prixconsu>=1)
								{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedConsu->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixconsu;
									?>
									</td>
								</tr>
						<?php
									$TotalMedConsu=$TotalMedConsu+$prixconsu;
								}
							}
						}
						
						if($comptMedAutreConsu!=0)
						{
							while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())
							{
								if($ligneMedAutreConsu->prixautreConsu!=0 AND $ligneMedAutreConsu->prixrembouConsu!=0)
								{
									$prixPrestaRembou=$ligneMedAutreConsu->prixrembouConsu;
									
									$prixconsu=$ligneMedAutreConsu->prixautreConsu - $prixPrestaRembou;
								
								}else{
									$prixconsu=$ligneMedAutreConsu->prixautreConsu;

								}
								
								if($prixconsu>=1)
								{
						?>
								<tr>
									<td style="text-align:center">
									<?php						
										echo $ligneMedAutreConsu->autreConsu;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixconsu;
									?>
									</td>
								</tr>
					<?php
									$TotalMedConsu=$TotalMedConsu+$prixconsu;
								}
							}
						}

					}					
					?>
										
								<tr>
									<?php
									if($TotalMedConsu!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php
										echo $TotalMedConsu;
										
										$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
									?>
									</td>
								</tr>
							</table>
						
						</td>
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
							<?php
							
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, '.$presta_assu.' p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
						$resultMedInf->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedInf=$resultMedInf->rowCount();
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
						
						$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.id_prestation IS NULL AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
						$resultMedAutreInf->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedAutreInf=$resultMedAutreInf->rowCount();
						
						$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedInf=0;					
						
					if($comptMedInf!=0 or $comptMedAutreInf!=0)
					{
						if($comptMedInf!=0)
						{
							while($ligneMedInf=$resultMedInf->fetch())
							{
								if($ligneMedInf->prixprestation!=0 AND $ligneMedInf->prixrembouInf!=0)
								{
									$prixPrestaRembou=$ligneMedInf->prixrembouInf;
									
									$prixinf=$ligneMedInf->prixprestation - $prixPrestaRembou;
								
								}else{
									$prixinf=$ligneMedInf->prixprestation;
																	
								}
								
								if($prixinf>=1)
								{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedInf->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixinf;
									?>
									</td>
								</tr>
							<?php
									$TotalMedInf=$TotalMedInf+$prixinf;
								}
							}
						}
						
						if($comptMedAutreInf!=0)
						{
							while($ligneMedAutreInf=$resultMedAutreInf->fetch())
							{	
								if($ligneMedAutreInf->prixautrePrestaM!=0 AND $ligneMedAutreInf->prixrembouInf!=0)
								{
									$prixPrestaRembou=$ligneMedAutreInf->prixrembouInf;
									
									$prixinf=$ligneMedAutreInf->prixautrePrestaM - $prixPrestaRembou;
								
								}else{
									$prixinf=$ligneMedAutreInf->prixautrePrestaM;
																	
								}
								
								if($prixinf>=1)
								{
							?>
								<tr>
									<td style="text-align:center">
									<?php						
										echo $ligneMedAutreInf->autrePrestaM;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixinf;
									?>
									</td>
								</tr>
								<?php
									$TotalMedInf=$TotalMedInf+$prixinf;
								}
							}
						}
					}					
					?>
									
								<tr>
									<?php						
									if($TotalMedInf!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php
										echo $TotalMedInf;
							
										$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
									?>
									</td>
								</tr>
							</table>
							
						</td>
						
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

					<?php
								
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, '.$presta_assu.' p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedLabo=$resultMedLabo->rowCount();
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.id_prestationExa IS NULL AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedAutreLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
						
						$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedLabo=0;
						
					if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
					{
						if($comptMedLabo!=0)
						{
							while($ligneMedLabo=$resultMedLabo->fetch())
							{
								if($ligneMedLabo->prixprestationExa!=0 AND $ligneMedLabo->prixrembouLabo!=0)
								{
									$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
													
									$prixlabo=$ligneMedLabo->prixprestationExa - $prixPrestaRembou;
								
								}else{
									$prixlabo=$ligneMedLabo->prixprestationExa;

								}
								
								if($prixlabo>=1)
								{
					?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedLabo->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixlabo;
									?>
									</td>
								</tr>
						<?php
									$TotalMedLabo=$TotalMedLabo+$prixlabo;
								}
							}
						}
						
						if($comptMedAutreLabo!=0)
						{
							while($ligneMedAutreLabo=$resultMedAutreLabo->fetch())//on recupere la liste des éléments
							{
								if($ligneMedAutreLabo->prixautreExamen!=0 AND $ligneMedAutreLabo->prixrembouLabo!=0)
								{
									$prixPrestaRembou=$ligneMedAutreLabo->prixrembouLabo;
									
									$prixlabo=$ligneMedAutreLabo->prixautreExamen - $prixPrestaRembou;
								
								}else{
									$prixlabo=$ligneMedAutreLabo->prixautreExamen;
																	
								}
								
								if($prixlabo>=1)
								{
						?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreLabo->autreExamen;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixlabo;
									?>
									</td>
								</tr>
					<?php
									$TotalMedLabo=$TotalMedLabo+$prixlabo;
								}
							}
						}

					}
					?>
									
								<tr>
									<?php
									
									if($TotalMedLabo!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php
										echo $TotalMedLabo;
										
										$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
									?>
									</td>
								</tr>
							</table>
							
						</td>
						
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

						<?php
								
						$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, '.$presta_assu.' p WHERE mr.id_prestationRadio=p.id_prestation AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
						$resultMedRadio->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedRadio=$resultMedRadio->rowCount();
						
						$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.id_prestationRadio IS NULL AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
						$resultMedAutreRadio->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedAutreRadio=$resultMedAutreRadio->rowCount();
						
						$resultMedAutreRadio->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedRadio=0;
						
					if($comptMedRadio!=0 or $comptMedAutreRadio!=0)
					{
						if($comptMedRadio!=0)
						{
							while($ligneMedRadio=$resultMedRadio->fetch())
							{
								if($ligneMedRadio->prixprestationRadio!=0 AND $ligneMedRadio->prixrembouRadio!=0)
								{
									$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
									
									$prixradio=$ligneMedRadio->prixprestationRadio - $prixPrestaRembou;
								
								}else{
									$prixradio=$ligneMedRadio->prixprestationRadio;
																	
								}
								
								if($prixradio>=1)
								{
					?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedRadio->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixradio;
									?>
									</td>
								</tr>
						<?php
									$TotalMedRadio=$TotalMedRadio+$prixradio;
								}
							}
						}
						
						if($comptMedAutreRadio!=0)
						{
							while($ligneMedAutreRadio=$resultMedAutreRadio->fetch())//on recupere la liste des éléments
							{
								if($ligneMedAutreRadio->prixautreRadio!=0 AND $ligneMedAutreRadio->prixrembouRadio!=0)
								{
									$prixPrestaRembou=$ligneMedAutreRadio->prixrembouRadio;
									
									$prixradio=$ligneMedAutreRadio->prixautreRadio - $prixPrestaRembou;
								
								}else{
									$prixradio=$ligneMedAutreRadio->prixautreRadio;
								
								}
								
								if($prixradio>=1)
								{
						?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreRadio->autreRadio;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixradio;
									?>
									</td>
								</tr>
					<?php
									$TotalMedRadio=$TotalMedRadio+$prixradio;
								}
							}
						}

					}					
					?>										
								<tr>
									<?php
									
									if($TotalMedRadio!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php
										echo $TotalMedRadio;
										
										$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;
									?>
									</td>
								</tr>
							</table>
							
						</td>
						
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

						<?php
								
						$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, '.$presta_assu.' p WHERE mco.id_prestationConsom=p.id_prestation AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
						$resultMedConsom->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedConsom=$resultMedConsom->rowCount();
						
						$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.id_prestationConsom IS NULL AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
						$resultMedAutreConsom->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedAutreConsom=$resultMedAutreConsom->rowCount();
						
						$resultMedAutreConsom->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedConsom=0;
						
					if($comptMedConsom!=0 or $comptMedAutreConsom!=0)
					{
						if($comptMedConsom!=0)
						{
							while($ligneMedConsom=$resultMedConsom->fetch())
							{
								if($ligneMedConsom->prixprestationConsom!=0 AND $ligneMedConsom->prixrembouConsom!=0)
								{
									$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
									
									$prixconsom=($ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom) - $prixPrestaRembou;
								
								}else{
									$prixconsom=$ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom;
								
								}
								
								if($prixconsom!=0)
								{
					?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedConsom->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php							
										echo $ligneMedConsom->qteConsom;
									?>
									</td>
									
									<td style="text-align:center">
									<?php							
										echo $ligneMedConsom->prixprestationConsom;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixconsom;
									?>
									</td>
								</tr>
						<?php
									$TotalMedConsom=$TotalMedConsom+$prixconsom;
								}
							}
						}
						
						if($comptMedAutreConsom!=0)
						{
							while($ligneMedAutreConsom=$resultMedAutreConsom->fetch())//on recupere la liste des éléments
							{
								if($ligneMedAutreConsom->prixautreConsom!=0 AND $ligneMedAutreConsom->prixrembouConsom!=0)
								{
									$prixPrestaRembou=$ligneMedAutreConsom->prixrembouConsom;
									
									$prixconsom=($ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom) - $prixPrestaRembou;
								
								}else{
									$prixconsom=$ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom;
								
								}
								
								if($prixconsom!=0)
								{
						?>
								<tr>
									<td style="text-align:center">
									<?php							
										echo $ligneMedAutreConsom->autreConsom;
									?>
									</td>
									
									<td style="text-align:center">
									<?php							
										echo $ligneMedAutreConsom->qteConsom;
									?>
									</td>
									
									<td style="text-align:center">
									<?php							
										echo $ligneMedAutreConsom->prixautreConsom;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $prixconsom;
									?>
									</td>
								</tr>
					<?php
									$TotalMedConsom=$TotalMedConsom+$prixconsom;
								}
							}
						}

					}					
					?>
									
								<tr>
									<?php
									
									if($TotalMedConsom!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center" colspan=2>
									<?php
										echo $TotalMedConsom;
										
										$TotalDayPrice=$TotalDayPrice+$TotalMedConsom;
									?>
									</td>
								</tr>
							</table>
							
						</td>
						
						<td>
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

						<?php
								
						$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, '.$presta_assu.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
						$resultMedMedoc->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedMedoc=$resultMedMedoc->rowCount();
						
						$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.id_prestationMedoc IS NULL AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
						$resultMedAutreMedoc->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedAutreMedoc=$resultMedAutreMedoc->rowCount();
						
						$resultMedAutreMedoc->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedMedoc=0;
						
					if($comptMedMedoc!=0 or $comptMedAutreMedoc!=0)
					{
						if($comptMedMedoc!=0)
						{
							while($ligneMedMedoc=$resultMedMedoc->fetch())
							{
								if($ligneMedMedoc->prixprestationMedoc!=0 AND $ligneMedMedoc->prixrembouMedoc!=0)
								{
									$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
				
									$prixmedoc=($ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc) - $prixPrestaRembou;
								
								}else{
									$prixmedoc=$ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc;
								
								}
								
								if($prixmedoc!=0)
								{
					?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedMedoc->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedMedoc->qteMedoc;
									
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedMedoc->prixprestationMedoc;
									
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixmedoc;
									?>
									</td>
								</tr>
						<?php
									$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
								}
							}
						}
						
						if($comptMedAutreMedoc!=0)
						{
							while($ligneMedAutreMedoc=$resultMedAutreMedoc->fetch())//on recupere la liste des éléments
							{
								if($ligneMedAutreMedoc->prixautreMedoc!=0 AND $ligneMedAutreMedoc->prixrembouMedoc!=0)
								{
									$prixPrestaRembou=$ligneMedAutreMedoc->prixrembouMedoc;
				
									$prixmedoc=($ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc) - $prixPrestaRembou;
								
								}else{
									$prixmedoc=$ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc;
								
								}
								
								if($prixmedoc!=0)
								{
						?>
								<tr>
									<td style="text-align:center">
									<?php							
										echo $ligneMedAutreMedoc->autreMedoc;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedAutreMedoc->qteMedoc;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedAutreMedoc->prixautreMedoc;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $prixmedoc;
									?>
									</td>
								</tr>
					<?php
									$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
								}
							}
						}

					}					
					?>
									
								<tr>
									<?php							
									if($TotalMedMedoc!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center" colspan=2>
									<?php
										echo $TotalMedMedoc;
										
										$TotalDayPrice=$TotalDayPrice+$TotalMedMedoc;
									?>
									</td>
								</tr>
							</table>
							
						</td>
						
						<td><?php echo $TotalDayPrice;?></td>
					</tr>
					<?php
							$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
							$TotalGnlMedConsu= $TotalGnlMedConsu + $TotalMedConsu;
							$TotalGnlMedInf= $TotalGnlMedInf + $TotalMedInf;
							$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
							$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
							$TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
							$TotalGnlMedMedoc=$TotalGnlMedMedoc + $TotalMedMedoc;
							$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;
							
							$compteur++;					
						
					}
					?>
					<tr style="text-align:center;">
						<td colspan=8></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlTypeConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedInf;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedLabo;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedRadio;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedConsom;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedMedoc;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlPrice;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
					</tbody>
				</table>
			
			</div>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center;">No report for this search</th>
						</tr> 
					</thead> 
				</table> 
				
			<?php
			}
			?>
			</div>
		
		<?php
		}
		?>
		
		<div style="overflow:auto;height:500px;background-color:none;">
		
			<div id="divGnlMedicReport" style="display:none">
			
			<?php
			
				$resultConsult=$connexion->query('SELECT *FROM consultations c ORDER BY c.dateconsu DESC');		
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptConsult=$resultConsult->rowCount();
			

				if($comptConsult != 0)
				{
			?>
				<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:95%;"> 
					<thead> 
						<tr>
							<th style="width:10%">N°</th>
							<th style="width:10%">Date</th>
							<th style="width:15%">Name</th>
							<th style="width:20%"><?php echo getString(113);?></th>
							<th style="width:15%"><?php echo getString(39);?></th>
							<th style="width:15%"><?php echo getString(98);?></th>
							<th style="width:15%"><?php echo getString(99);?></th>
						</tr> 
					</thead> 


					<tbody>
				<?php
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())//on recupere la liste des éléments
					{
				?>
				
					<tr style="text-align:center;">
						<td><?php echo $compteur; ?></td>
						<td><?php echo $ligneConsult->dateconsu; ?></td>
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								
								echo '<td>'.$fullname.'</td>';
							}else{
								echo '<td></td>';
							}
							
						?>
						
						<td style="padding:0 10px;">
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
							'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							
							if($lignePresta->namepresta!='')
							{
								echo $lignePresta->namepresta.'</td>';
							}else{								
								echo $lignePresta->nompresta.'</td>';
							}
						}
						?>
						<td>
						<?php
						
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedConsult=$resultMedConsult->rowCount();
					
					
						if($comptMedConsult != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
						<?php
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
						?>
								<tr style="text-align:center;">
									
									<td>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsult->id_prestationConsu
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
									}
										echo $ligneMedConsult->autreConsu.'</td>';
									?>
								</tr>
						<?php
								}
						?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>
						
						<td>
						<?php
						
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(				
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedInf=$resultMedInf->rowCount();
					
					
						if($comptMedInf != 0)
						{
						?>		
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						
							<tbody>
						<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
									}
									
										echo $ligneMedInf->autrePrestaM.'</td>';
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
						</td>
						
						<td>
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(			
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

						$comptMedLabo=$resultMedLabo->rowCount();


						if($comptMedLabo != 0)
						{
						?>	
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
							<tbody>
						<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
											}else{
												echo $lignePresta->nompresta.'</td>';
											}
										}
										
											echo $ligneMedLabo->autreExamen;
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
						</td>
					</tr>
				<?php
						$compteur++;
					}
				?>
					</tbody>
				</table>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%">No General Medical Report</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
			</div>

		
		<?php
		/* 
		if(isset($_GET['selectGnlBillHosp']))
		{
		?>
			<div id="divGnlBillReport" style="display:none">
			
				<?php
				
				$resultGnlBillReport=$connexion->query('SELECT *FROM bills WHERE numero != "" ORDER BY datebill DESC');
				
				$resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptBillReport=$resultGnlBillReport->rowCount();
				
				if($comptBillReport!=0)
				{
				?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:10%">N°</th>
							<th style="width:12%">Date</th>
							<th style="width:8%">Bill number</th>
							<th style="width:20%">Full name</th>
							<th style="width:10%">Insurance</th>
							<th style="width:10%">Consultation</th>
							<th style="width:10%"><?php echo getString(39);?></th>
							<th style="width:10%"><?php echo getString(98);?></th>
							<th style="width:10%"><?php echo getString(99);?></th>
							<th style="width:10%">Total Final</th>
						</tr> 
					</thead> 
					
					<tbody>
				<?php
				$TotalGnlTypeConsu=0;
				$TotalGnlMedConsu=0;
				$TotalGnlMedInf=0;
				$TotalGnlMedLabo=0;
				$TotalGnlPrice=0;
				
				$compteur=1;
				
					while($ligneGnlBillReport=$resultGnlBillReport->fetch())//on recupere la liste des éléments
					{
				?>
				
						<tr style="text-align:center;">
						
							<td><?php echo $compteur;?></td>
							<td><?php echo $ligneGnlBillReport->datebill;?></td>
							<td><?php echo $ligneGnlBillReport->numbill;?></td>
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneGnlBillReport->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
									
									echo '<td>'.$fullname.'</td>';
								}else{
									echo '<td></td>';
								}
								
							?>
							
							<td><?php echo $ligneGnlBillReport->nomassurance.' '.$ligneGnlBillReport->billpercent.' %';?></td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
								<?php
					
								$resultConsu=$connexion->prepare('SELECT *FROM consultations c, prestations_private p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
								$resultConsu->execute(array(
								'idbill'=>$ligneGnlBillReport->id_bill
								));
								
								$comptConsu=$resultConsu->rowCount();
								
								$resultConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								if($comptConsu!=0)
								{
									while($ligneConsu=$resultConsu->fetch())//on recupere la liste des éléments
									{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneConsu->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneConsu->prixpresta;
										?>
										</td>
									</tr>
								<?php
									}
								}
								?>
										
									<tr>
										<?php
										
										if($ligneGnlBillReport->totaltypeconsuprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneGnlBillReport->totaltypeconsuprice;
										?>
										</td>
									</tr>
								</table>
							
							</td>
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
								<?php
								
						$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
						$resultMedConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedConsu=$resultMedConsu->rowCount();
						
						$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE  mc.id_factureMedConsu=:idbill GROUP BY id_medconsu ORDER BY mc.id_medconsu DESC');
						$resultMedAutreConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
						
						$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
					{
						if($comptMedConsu!=0)
						{
							while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
							{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedConsu->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneMedConsu->prixpresta;
										?>
										</td>
									</tr>
								<?php
								
							}
						}
						
						if($comptMedAutreConsu!=0)
						{
							while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())//on recupere la liste des éléments
							{
								
								if($ligneMedAutreConsu->prixautreConsu!=0 and $ligneMedAutreConsu->autreConsu!="")
								{
								?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreConsu->autreConsu;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreConsu->prixautreConsu;
									
									?>
									</td>
								</tr>
								<?php
								}
							}
						}

					}					
					?>
										
									<tr>
										<?php
										
										if($ligneGnlBillReport->totalmedconsuprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneGnlBillReport->totalmedconsuprice;
										?>
										</td>
									</tr>
								</table>
							
							</td>
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
								<?php
								
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
						$resultMedInf->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedInf=$resultMedInf->rowCount();
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_factureMedInf=:idbill GROUP BY id_medinf ORDER BY mi.id_medinf DESC');
						$resultMedAutreInf->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedAutreInf=$resultMedAutreInf->rowCount();
						
						$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($comptMedInf!=0 or $comptMedAutreInf!=0)
					{
						if($comptMedInf!=0)
						{
							while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des éléments
							{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedInf->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneMedInf->prixpresta;
										?>
										</td>
									</tr>
								<?php
								
							}
						}
						
						if($comptMedAutreInf!=0)
						{
							while($ligneMedAutreInf=$resultMedAutreInf->fetch())//on recupere la liste des éléments
							{
								
								if($ligneMedAutreInf->prixautrePrestaM != 0 and $ligneMedAutreInf->autrePrestaM != "")
								{
								?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreInf->autrePrestaM;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreInf->prixautrePrestaM;
									
									?>
									</td>
								</tr>
								<?php
								}
							}
						}

					}					
					?>
										
									<tr>
										<?php
										
										if($ligneGnlBillReport->totalmedinfprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneGnlBillReport->totalmedinfprice;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td>
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
					<?php
								
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedLabo=$resultMedLabo->rowCount();
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_factureMedLabo=:idbill GROUP BY id_medlabo ORDER BY ml.id_medlabo DESC');
						$resultMedAutreLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
						
						$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
					{
						if($comptMedLabo!=0)
						{
							while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des éléments
							{
								?>
									<tr>
										<td style="text-align:center">
										<?php
											echo $ligneMedLabo->nompresta;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
											echo $ligneMedLabo->prixpresta;
										?>
										</td>
									</tr>
								<?php
								
							}
						}
						
						if($comptMedAutreLabo!=0)
						{
							while($ligneMedAutreLabo=$resultMedAutreInf->fetch())//on recupere la liste des éléments
							{
								
								if($ligneMedAutreLabo->prixautreExamen != 0 and $ligneMedAutreLabo->autreExamen != "")
								{
								?>
								<tr>
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreLabo->autreExamen;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									
										echo $ligneMedAutreLabo->prixautreExamen;
									
									?>
									</td>
								</tr>
								<?php
								}
							}
						}

					}					
					?>
										
									<tr>
										<?php
										
										if($ligneGnlBillReport->totalmedlaboprice!=0)
										{
										?>
										<td></td>
										<?php
										}
										?>
										<td style="text-align:center">
										<?php

											echo $ligneGnlBillReport->totalmedlaboprice;
										?>
										</td>
									</tr>
								</table>
								
							</td>
							
							<td><?php echo $ligneGnlBillReport->totalgnlprice;?></td>
						</tr>
					<?php
							$TotalGnlTypeConsu=$TotalGnlTypeConsu + $ligneGnlBillReport->totaltypeconsuprice;
							$TotalGnlMedConsu= $TotalGnlMedConsu + $ligneGnlBillReport->totalmedconsuprice;
							$TotalGnlMedInf= $TotalGnlMedInf + $ligneGnlBillReport->totalmedinfprice;
							$TotalGnlMedLabo=$TotalGnlMedLabo + $ligneGnlBillReport->totalmedlaboprice;
							$TotalGnlPrice=$TotalGnlPrice + $ligneGnlBillReport->totalgnlprice;
						
							$compteur++;
						
					}
					?>
						<tr style="text-align:center;">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlTypeConsu;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedConsu;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedInf;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlMedLabo;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlPrice;
									
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%">No General Billing Report</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
			</div>
		
		<?php
		}
		*/
		?>
		
		</div>
	<?php
	}
	
	if(isset($_GET['cash']))
	{
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE c.codecashier=:operation AND u.id_u=c.id_u');
		$result->execute(array(
		'operation'=>$_GET['cash']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codeCa=$ligne->codecashier;
			$fullname=$ligne->full_name;
			$sexe=$ligne->sexe;
			$adresse=$ligne->province.','.$ligne->district.','.$ligne->secteur;	
	?>
			<table style="margin:auto;">
				<tr>
					<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
						<b><h2>Cashier Report</h2></b>
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
						if($ligne->sexe=="M")
						{
							$sexe = "Male";
						}elseif($ligne->sexe=="F")
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
		
			<form action="report.php?cash=<?php echo $_GET['cash'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmacbillperso=ok&selectPersoBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
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
						
						<td>
						<?php
						/* if(isset($_GET['selectPersoBill']) OR isset($_GET['selectGnlBill']))
						{
						?>
							<a href="report.php?cash=<?php echo $_GET['cash'];?>&audit=ok&report=ok" id="nomassurance" style="text-align:center;width:100px;" class="btn">Full Report</a>
						<?php
						} */
						?>
						</td>
					</tr>
					
					<tr style="visibility:visible">
					
						<td id="dailybillPerso" style="display:none">Select date
							<input type="text" id="dailydatebillPerso" name="dailydatebillPerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit" name="searchdailybillPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlybillPerso" style="display:none">Select Month
						
							<select name="monthlydatebillPerso" id="monthlydatebillPerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatebillPersoYear" id="monthlydatebillPersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
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
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
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
				
				$resultCashierBillReport=$connexion->prepare('SELECT *FROM bills b WHERE b.codecashier=:codeCa '.$dailydateperso.' ORDER BY b.datebill ASC');		
				
				$resultCashierBillReport->execute(array(
				'codeCa'=>$cash
				));
				
				$resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$compCashBillReport=$resultCashierBillReport->rowCount();

				if($compCashBillReport!=0)
				{
				?>
				
			<table style="width:100%;">
				<tr>
					<td style="text-align:left; width:33.333%;">
				
						<a href="cashier_report.php?cash=<?php echo $_GET['cash'];?>&dailydateperso=<?php echo $dailydateperso;?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createRN=1" style="text-align:center" id="dmacbillpersopreview">
							
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
		
				<div style="overflow:auto;height:500px;background-color:none;margin-top:10px;">
				
					<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:10%">N°</th>
								<th style="width:12%">Date</th>
								<th style="width:8%">Bill number</th>
								<th style="width:20%">Full name</th>
								<th style="width:10%">Insurance type</th>
								<th style="width:10%">Type consultations</th>
								<th style="width:10%"><?php echo getString(39);?></th>
								<th style="width:10%"><?php echo getString(98);?></th>
								<th style="width:10%"><?php echo getString(99);?></th>
								<th style="width:10%"><?php echo 'Radiologie';?></th>
								<th style="width:10%"><?php echo 'Consommables';?></th>
								<th style="width:10%"><?php echo 'Medicaments';?></th>
								<th style="width:10%">Total Final</th>
							</tr> 
						</thead> 
						
						<tbody>
					<?php
					$TotalGnlTypeConsu=0;
					$TotalGnlMedConsu=0;
					$TotalGnlMedInf=0;
					$TotalGnlMedLabo=0;
					$TotalGnlMedRadio=0;
					$TotalGnlMedConsom=0;
					$TotalGnlMedMedoc=0;
					$TotalGnlPrice=0;
					
					$compteur=1;
					
						while($ligneCashierBillReport=$resultCashierBillReport->fetch())//on recupere la liste des éléments
						{
							$TotalDayPrice=0;
					?>
					
							<tr style="text-align:center;">
								<td><?php echo $compteur;?></td>
								<td><?php echo $ligneCashierBillReport->datebill;?></td>
								<td><?php echo $ligneCashierBillReport->numbill;?></td>
								<?php
									$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
									$resultPatient->execute(array(
									'operation'=>$ligneCashierBillReport->numero
									));
									
									$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptFiche=$resultPatient->rowCount();
									
									if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
									{
										$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
										
										echo '<td>'.$fullname.'</td>';
									}else{
										echo '<td></td>';
									}
									
								?>
								
								<td><?php echo $ligneCashierBillReport->nomassurance.' '.$ligneCashierBillReport->billpercent.' %';?></td>
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
									<?php
						
									$resultConsu=$connexion->prepare('SELECT *FROM consultations c, prestations_private p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
									$resultConsu->execute(array(
									'idbill'=>$ligneCashierBillReport->id_bill
									));
									
									$comptConsu=$resultConsu->rowCount();
									
									$resultConsu->setFetchMode(PDO::FETCH_OBJ);
									
									$resultAutreConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_typeconsult IS NULL AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
									$resultAutreConsu->execute(array(
									'idbill'=>$ligneCashierBillReport->id_bill
									));
									
									$comptAutreConsu=$resultAutreConsu->rowCount();
									$resultAutreConsu->setFetchMode(PDO::FETCH_OBJ);
										
									$TotalTypeConsu=0;
									
									if($comptConsu!=0)
									{
										while($ligneConsu=$resultConsu->fetch())
										{
											if($ligneConsu->prixtypeconsult!=0 AND $ligneConsu->prixrembou!=0)
											{
												$prixconsult=$ligneConsu->prixtypeconsult - $prixPrestaRembou;
											
											}else{
												$prixconsult=$ligneConsu->prixtypeconsult;

											}
											
											if($prixconsult>=0)
											{
										?>
											<tr>
												<td style="text-align:center">
												<?php
													echo $ligneConsu->nompresta;
												?>
												</td>
												
												<td style="text-align:center">
												<?php
													echo $prixconsult;
												?>
												</td>
											</tr>
										<?php
												$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
											}
										}
									}
									
									if($comptAutreConsu!=0)
									{
										while($ligneAutreConsu=$resultAutreConsu->fetch())
										{
											if($ligneAutreConsu->prixautretypeconsult!=0 AND $ligneAutreConsu->prixrembou!=0)
											{
												$prixconsult=$ligneAutreConsu->prixautretypeconsult - $prixPrestaRembou;
											
											}else{
												$prixconsult=$ligneAutreConsu->prixautretypeconsult;

											}
											
											if($prixconsult>=0)
											{
										?>
											<tr>
												<td style="text-align:center">
												<?php
													echo $ligneAutreConsu->autretypeconsult;
												?>
												</td>
												
												<td style="text-align:center">
												<?php
													echo $prixconsult;
												?>
												</td>
											</tr>
										<?php
												$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
											}
										}
									}
									?>
											
										<tr>
											<?php
											if($TotalTypeConsu!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center">
											<?php
												echo $TotalTypeConsu;
											
												$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
											?>
											</td>
										</tr>
									</table>
								
								</td>
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
									<?php
									
								$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
								$resultMedConsu->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedConsu=$resultMedConsu->rowCount();
								
								$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);
								
								$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc WHERE  mc.id_factureMedConsu=:idbill AND mc.id_prestationConsu IS NULL ORDER BY mc.id_medconsu DESC');
								$resultMedAutreConsu->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedAutreConsu=$resultMedAutreConsu->rowCount();

								$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);
								
								$TotalMedConsu=0;
									
							if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
							{
								if($comptMedConsu!=0)
								{
									while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
									{
										if($ligneMedConsu->prixprestationConsu!=0 AND $ligneMedConsu->prixrembouConsu!=0)
										{
											$prixPrestaRembou=$ligneMedConsu->prixrembouConsu;
											
											$prixconsu=$ligneMedConsu->prixprestationConsu - $prixPrestaRembou;
										
										}else{
											$prixconsu=$ligneMedConsu->prixprestationConsu;

										}
										
										if($prixconsu>=1)
										{
									?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneMedConsu->nompresta;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixconsu;
											?>
											</td>
										</tr>
								<?php
											$TotalMedConsu=$TotalMedConsu+$prixconsu;
										}
									}
								}
								
								if($comptMedAutreConsu!=0)
								{
									while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())
									{								
										if($ligneMedAutreConsu->prixautreConsu!=0 AND $ligneMedAutreConsu->prixrembouConsu!=0)
										{
											$prixPrestaRembou=$ligneMedAutreConsu->prixrembouConsu;
											
											$prixconsu=$ligneMedAutreConsu->prixautreConsu - $prixPrestaRembou;
										
										}else{
											$prixconsu=$ligneMedAutreConsu->prixautreConsu;

										}
										
										if($prixconsu>=1)
										{
								?>
										<tr>
											<td style="text-align:center">
											<?php						
												echo $ligneMedAutreConsu->autreConsu;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixconsu;
											?>
											</td>
										</tr>
							<?php
											$TotalMedConsu=$TotalMedConsu+$prixconsu;
										}
									}
								}

							}					
							?>
												
										<tr>
											<?php
											if($TotalMedConsu!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center">
											<?php
												echo $TotalMedConsu;
												
												$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
											?>
											</td>
										</tr>
									</table>
								
								</td>
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
									<?php
									
								$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
								$resultMedInf->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedInf=$resultMedInf->rowCount();
								
								$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
								
								
								$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_prestation IS NULL AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
								$resultMedAutreInf->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedAutreInf=$resultMedAutreInf->rowCount();
								
								$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);
								
									
									$TotalMedInf=0;					
								
							if($comptMedInf!=0 or $comptMedAutreInf!=0)
							{
								if($comptMedInf!=0)
								{
									while($ligneMedInf=$resultMedInf->fetch())
									{
										if($ligneMedInf->prixprestation!=0 AND $ligneMedInf->prixrembouInf!=0)
										{
											$prixPrestaRembou=$ligneMedInf->prixrembouInf;
											
											$prixinf=$ligneMedInf->prixprestation - $prixPrestaRembou;
										
										}else{
											$prixinf=$ligneMedInf->prixprestation;
																			
										}
										
										if($prixinf>=1)
										{
									?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneMedInf->nompresta;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixinf;
											?>
											</td>
										</tr>
									<?php
											$TotalMedInf=$TotalMedInf+$prixinf;
										}
									}
								}
								
								if($comptMedAutreInf!=0)
								{
									while($ligneMedAutreInf=$resultMedAutreInf->fetch())
									{	
										if($ligneMedAutreInf->prixautrePrestaM!=0 AND $ligneMedAutreInf->prixrembouInf!=0)
										{
											$prixPrestaRembou=$ligneMedAutreInf->prixrembouInf;
											
											$prixinf=$ligneMedAutreInf->prixautrePrestaM - $prixPrestaRembou;
										
										}else{
											$prixinf=$ligneMedAutreInf->prixautrePrestaM;
																			
										}
										
										if($prixinf>=1)
										{
									?>
										<tr>
											<td style="text-align:center">
											<?php						
												echo $ligneMedAutreInf->autrePrestaM;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixinf;
											?>
											</td>
										</tr>
										<?php
											$TotalMedInf=$TotalMedInf+$prixinf;
										}
									}
								}
							}					
							?>
											
										<tr>
											<?php						
											if($TotalMedInf!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center">
											<?php
												echo $TotalMedInf;
									
												$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
											?>
											</td>
										</tr>
									</table>
									
								</td>
								
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
											
							<?php
										
								$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
								$resultMedLabo->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedLabo=$resultMedLabo->rowCount();
								
								$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
								$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_prestationExa IS NULL AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
								$resultMedAutreLabo->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
								
								$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);
								
								
								$TotalMedLabo=0;
								
							if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
							{
								if($comptMedLabo!=0)
								{
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
										if($ligneMedLabo->prixprestationExa!=0 AND $ligneMedLabo->prixrembouLabo!=0)
										{
											$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
															
											$prixlabo=$ligneMedLabo->prixprestationExa - $prixPrestaRembou;
										
										}else{
											$prixlabo=$ligneMedLabo->prixprestationExa;

										}
										
										if($prixlabo>=1)
										{
							?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneMedLabo->nompresta;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixlabo;
											?>
											</td>
										</tr>
								<?php
											$TotalMedLabo=$TotalMedLabo+$prixlabo;
										}
									}
								}
								
								if($comptMedAutreLabo!=0)
								{
									while($ligneMedAutreLabo=$resultMedAutreLabo->fetch())//on recupere la liste des éléments
									{
										if($ligneMedAutreLabo->prixautreExamen!=0 AND $ligneMedAutreLabo->prixrembouLabo!=0)
										{
											$prixPrestaRembou=$ligneMedAutreLabo->prixrembouLabo;
											
											$prixlabo=$ligneMedAutreLabo->prixautreExamen - $prixPrestaRembou;
										
										}else{
											$prixlabo=$ligneMedAutreLabo->prixautreExamen;
																			
										}
										
										if($prixlabo>=1)
										{
								?>
										<tr>
											<td style="text-align:center">
											<?php
											
												echo $ligneMedAutreLabo->autreExamen;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixlabo;
											?>
											</td>
										</tr>
							<?php
											$TotalMedLabo=$TotalMedLabo+$prixlabo;
										}
									}
								}

							}
							?>
											
										<tr>
											<?php
											
											if($TotalMedLabo!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center">
											<?php
												echo $TotalMedLabo;
												
												$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
											?>
											</td>
										</tr>
									</table>
									
								</td>
								
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

								<?php
										
								$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, prestations_private p WHERE mr.id_prestationRadio=p.id_prestation AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
								$resultMedRadio->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedRadio=$resultMedRadio->rowCount();
								
								$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
								$resultMedAutreRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_prestationRadio IS NULL AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
								$resultMedAutreRadio->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedAutreRadio=$resultMedAutreRadio->rowCount();
								
								$resultMedAutreRadio->setFetchMode(PDO::FETCH_OBJ);
								
								
								$TotalMedRadio=0;
								
							if($comptMedRadio!=0 or $comptMedAutreRadio!=0)
							{
								if($comptMedRadio!=0)
								{
									while($ligneMedRadio=$resultMedRadio->fetch())
									{
										if($ligneMedRadio->prixprestationRadio!=0 AND $ligneMedRadio->prixrembouRadio!=0)
										{
											$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
											
											$prixradio=$ligneMedRadio->prixprestationRadio - $prixPrestaRembou;
										
										}else{
											$prixradio=$ligneMedRadio->prixprestationRadio;
																			
										}
										
										if($prixradio>=1)
										{
							?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneMedRadio->nompresta;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixradio;
											?>
											</td>
										</tr>
								<?php
											$TotalMedRadio=$TotalMedRadio+$prixradio;
										}
									}
								}
								
								if($comptMedAutreRadio!=0)
								{
									while($ligneMedAutreRadio=$resultMedAutreRadio->fetch())//on recupere la liste des éléments
									{
										if($ligneMedAutreRadio->prixautreRadio!=0 AND $ligneMedAutreRadio->prixrembouRadio!=0)
										{
											$prixPrestaRembou=$ligneMedAutreRadio->prixrembouRadio;
											
											$prixradio=$ligneMedAutreRadio->prixautreRadio - $prixPrestaRembou;
										
										}else{
											$prixradio=$ligneMedAutreRadio->prixautreRadio;
										
										}
										
										if($prixradio>=1)
										{
								?>
										<tr>
											<td style="text-align:center">
											<?php
											
												echo $ligneMedAutreRadio->autreRadio;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $prixradio;
											?>
											</td>
										</tr>
							<?php
											$TotalMedRadio=$TotalMedRadio+$prixradio;
										}
									}
								}

							}					
							?>										
										<tr>
											<?php
											
											if($TotalMedRadio!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center">
											<?php
												echo $TotalMedRadio;
												
												$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;
											?>
											</td>
										</tr>
									</table>
									
								</td>
								
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

								<?php
										
								$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, prestations_private p WHERE mco.id_prestationConsom=p.id_prestation AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
								$resultMedConsom->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedConsom=$resultMedConsom->rowCount();
								
								$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
								$resultMedAutreConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.id_prestationConsom=0 AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
								$resultMedAutreConsom->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedAutreConsom=$resultMedAutreConsom->rowCount();
								
								$resultMedAutreConsom->setFetchMode(PDO::FETCH_OBJ);
								
								
								$TotalMedConsom=0;
								
							if($comptMedConsom!=0 or $comptMedAutreConsom!=0)
							{
								if($comptMedConsom!=0)
								{
									while($ligneMedConsom=$resultMedConsom->fetch())
									{
										if($ligneMedConsom->prixprestationConsom!=0 AND $ligneMedConsom->prixrembouConsom!=0)
										{
											$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
											
											$prixconsom=($ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom) - $prixPrestaRembou;
										
										}else{
											$prixconsom=$ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom;
										
										}
										
										if($prixconsom!=0)
										{
							?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneMedConsom->nompresta;
											?>
											</td>
											
											<!--
											<td style="text-align:center">
											<?php							
												echo $ligneMedConsom->qteConsom;
											?>
											</td>
											-->
											
											<td style="text-align:center">
											<?php
												echo $prixconsom;
											?>
											</td>
										</tr>
								<?php
											$TotalMedConsom=$TotalMedConsom+$prixconsom;
										}
									}
								}
								
								if($comptMedAutreConsom!=0)
								{
									while($ligneMedAutreConsom=$resultMedAutreConsom->fetch())//on recupere la liste des éléments
									{
										if($ligneMedAutreConsom->prixautreConsom!=0 AND $ligneMedAutreConsom->prixrembouConsom!=0)
										{
											$prixPrestaRembou=$ligneMedAutreConsom->prixrembouConsom;
											
											$prixconsom=($ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom) - $prixPrestaRembou;
										
										}else{
											$prixconsom=$ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom;
										
										}
										
										if($prixconsom!=0)
										{
								?>
										<tr>
											<td style="text-align:center">
											<?php							
												echo $ligneMedAutreConsom->autreConsom;
											?>
											</td>
											
											<!--
											<td style="text-align:center">
											<?php							
												echo $ligneMedAutreConsom->qteConsom;
											?>
											</td>
											-->
											
											<td style="text-align:center">
											<?php
											
												echo $prixconsom;
											?>
											</td>
										</tr>
							<?php
											$TotalMedConsom=$TotalMedConsom+$prixconsom;
										}
									}
								}

							}					
							?>
											
										<tr>
											<?php
											
											if($TotalMedConsom!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center" colspan=2>
											<?php
												echo $TotalMedConsom;
												
												$TotalDayPrice=$TotalDayPrice+$TotalMedConsom;
											?>
											</td>
										</tr>
									</table>
									
								</td>
								
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

								<?php
										
								$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, prestations_private p WHERE mdo.id_prestationMedoc=p.id_prestation AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
								$resultMedMedoc->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedMedoc=$resultMedMedoc->rowCount();
								
								$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
								$resultMedAutreMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.id_prestationMedoc=0 AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
								$resultMedAutreMedoc->execute(array(
								'idbill'=>$ligneCashierBillReport->id_bill
								));
								
								$comptMedAutreMedoc=$resultMedAutreMedoc->rowCount();
								
								$resultMedAutreMedoc->setFetchMode(PDO::FETCH_OBJ);
								
								
								$TotalMedMedoc=0;
								
							if($comptMedMedoc!=0 or $comptMedAutreMedoc!=0)
							{
								if($comptMedMedoc!=0)
								{
									while($ligneMedMedoc=$resultMedMedoc->fetch())
									{
										if($ligneMedMedoc->prixprestationMedoc!=0 AND $ligneMedMedoc->prixrembouMedoc!=0)
										{
											$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
						
											$prixmedoc=($ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc) - $prixPrestaRembou;
										
										}else{
											$prixmedoc=$ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc;
										
										}
										
										if($prixmedoc!=0)
										{
							?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneMedMedoc->nompresta;
											?>
											</td>
											
											<!--
											<td style="text-align:center">
											<?php
											if($ligneMedMedoc->prixrembouMedoc==0)
												echo $ligneMedMedoc->qteMedoc;
											else
												echo '-';
											?>
											</td>
											-->
											
											<td style="text-align:center">
											<?php
												echo $prixmedoc;
											?>
											</td>
										</tr>
								<?php
											$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
										}
									}
								}
								
								if($comptMedAutreMedoc!=0)
								{
									while($ligneMedAutreMedoc=$resultMedAutreMedoc->fetch())//on recupere la liste des éléments
									{
										if($ligneMedAutreMedoc->prixautreMedoc!=0 AND $ligneMedAutreMedoc->prixrembouMedoc!=0)
										{
											$prixPrestaRembou=$ligneMedAutreMedoc->prixrembouMedoc;
						
											$prixmedoc=($ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc) - $prixPrestaRembou;
										
										}else{
											$prixmedoc=$ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc;
										
										}
										
										if($prixmedoc!=0)
										{
								?>
										<tr>
											<td style="text-align:center">
											<?php							
												echo $ligneMedAutreMedoc->autreMedoc;
											?>
											</td>
											
											<!--
											<td style="text-align:center">
											<?php
											if($ligneMedAutreMedoc->prixrembouMedoc==0)
												echo $ligneMedAutreMedoc->qteMedoc;
											else
												echo '-';
											?>
											</td>
											-->
											
											<td style="text-align:center">
											<?php
												echo $prixmedoc;
											?>
											</td>
										</tr>
							<?php
											$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
										}
									}
								}

							}					
							?>
											
										<tr>
											<?php							
											if($TotalMedMedoc!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center" colspan=2>
											<?php
												echo $TotalMedMedoc;
												
												$TotalDayPrice=$TotalDayPrice+$TotalMedMedoc;
											?>
											</td>
										</tr>
									</table>
									
								</td>
								
								<td><?php echo $TotalDayPrice;?></td>
							</tr>
						<?php
								$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
								$TotalGnlMedConsu= $TotalGnlMedConsu + $TotalMedConsu;
								$TotalGnlMedInf= $TotalGnlMedInf + $TotalMedInf;
								$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
								$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
								$TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
								$TotalGnlMedMedoc=$TotalGnlMedMedoc + $TotalMedMedoc;
								$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;
								
								$compteur++;

						}
						?>
							<tr style="text-align:center;">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlTypeConsu;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlMedConsu;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlMedInf;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlMedLabo;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlMedRadio;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlMedConsom;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlMedMedoc;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlPrice;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
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
				?>
			</div>
		<?php
		}else{
		
		}
	}

	if(isset($_GET['codeI']))
	{
	?>
		<table style="margin:auto;">
			<tr>
				<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
					<b><h2>Infirmier Report</h2></b>
				</td>
			</tr>
		</table>
	<?php
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE i.codeinfirmier=:operation AND u.id_u=i.id_u');
		$result->execute(array(
		'operation'=>$_GET['codeI']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codeI=$ligne->codeinfirmier;
			$fullname=$ligne->full_name;
			$sexe=$ligne->sexe;
			$adresse=$ligne->province.','.$ligne->district.','.$ligne->secteur;	
	?>
			
			
			<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:80%;">
				<tr>
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;">S/N : </span></span><?php echo $codeI;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $fullname;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
						<?php
						if($ligne->sexe=="M")
						{
							$sexe = "Male";
						}elseif($ligne->sexe=="F")
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
		
			<form action="report.php?codeI=<?php echo $_GET['codeI'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmacbillperso=ok&selectPersoBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?><?php if(isset($_GET['coordi'])){ echo'&coordi=ok';}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
				<table id="dmacbillperso" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelectreport('dailybillPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelectreport('monthlybillPerso')" class="btn">Monthly</span>
						</td>
						
						<!-- <td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelectreport('annualybillPerso')" class="btn">Annualy</span>
						</td>
						 -->
						<!-- <td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelectreport('custombillPerso')" class="btn">Custom</span>
						</td> -->
						
						<td>
						<?php
						/* if(isset($_GET['selectPersoBill']) OR isset($_GET['selectGnlBill']))
						{
						?>
							<a href="report.php?cash=<?php echo $_GET['cash'];?>&audit=ok&report=ok" id="nomassurance" style="text-align:center;width:100px;" class="btn">Full Report</a>
						<?php
						} */
						?>
						</td>
					</tr>
					
					<tr style="visibility:visible">
					
						<td id="dailybillPerso" style="display:none">Select date
							<input type="text" id="dailydatebillPerso" name="dailydatebillPerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit" name="searchdailybillPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlybillPerso" style="display:none">Select Month
						
							<select name="monthlydatebillPerso" id="monthlydatebillPerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatebillPersoYear" id="monthlydatebillPersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
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
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
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
		
		if(isset($_GET['dmacbillperso']) OR isset($_GET['selectPersoBill']))
		{
			$stringResult = "";
			$dailydateperso = "";
			$caVisit="gnlPersoBill";
			
			if(isset($_POST['searchdailybillPerso']))
			{
				if(isset($_POST['dailydatebillPerso']))
				{
					$dailydateperso = 'dateconsu LIKE \''.$_POST['dailydatebillPerso'].'%\'';
					
					$caVisit="dailyPersoBill";
				
					$stringResult="Daily results : ".$_POST['dailydatebillPerso'];
					//echo "string";
		
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
					
					$dailydateperso = 'dateconsu>=\''.$umwaka.'-'.$ukwezi.'-01\' AND (dateconsu<\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' OR dateconsu LIKE \''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'%\')';

					$caVisit="monthlyPersoBill";
					
					$stringResult=date("F",mktime(0,0,0,$_POST['monthlydatebillPerso'],10))." - ".$_POST['monthlydatebillPersoYear'];
			
				}
			}
			if(isset($_POST['searchcustombillPerso']))
			{
				if(isset($_POST['customdatedebutbillPerso']) AND isset($_POST['customdatefinbillPerso']))
				{
					$debut = $_POST['customdatedebutbillPerso'];
					$fin = $_POST['customdatefinbillPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND dateconsu>=\''.$debut.'\' AND (dateconsu<\''.$fin.'\' OR dateconsu LIKE \''.$fin.'%\')';
					
					$caVisit="customPersoBill";
					
					$stringResult="Customer results : [ ".$_POST['customdatedebutbillPerso']."/".$_POST['customdatefinbillPerso']." ]";

				}
			}
			
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
				
				$resultCashierBillReport=$connexion->query('SELECT * FROM consultations WHERE '.$dailydateperso.' ORDER BY id_consu ASC');		
				
				$resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$compCashBillReport=$resultCashierBillReport->rowCount();

				if($compCashBillReport!=0)
				{
				?>
				
			<table style="width:100%;">
				<tr>
					<td style="text-align:left; width:33.333%;">
						<a href="infirmier_report.php?codeI=<?php echo $_GET['codeI'];?>&dailydateperso=<?php echo $dailydateperso;?><?php if(isset($_POST['dailydatebillPerso'])){ echo '&dailydatebillPerso='.$_POST['dailydatebillPerso'];}?><?php if(isset($_POST['searchmonthlybillPerso'])){ echo '&searchmonthlybillPerso=ok'; if(isset($_POST['monthlydatebillPerso']) AND isset($_POST['monthlydatebillPersoYear'])){ echo '&monthlydatebillPerso='.$_POST['monthlydatebillPerso'].'&monthlydatebillPersoYear='.$_POST['monthlydatebillPersoYear'];}}?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createRN=1<?php if(isset($_GET['coordi'])){ echo'&coordi=ok';}?>" style="text-align:center" id="dmacbillpersopreview"> 
							<!-- <a href="infirmier_report.php?codeI=<?php echo $_GET['codeI'];?><?php if(isset($_POST['searchdailybillPerso'])){ echo '&dailydatebillPerso=ok'; echo'&dailydateperso='.$dailydateperso;}?>"> -->
							
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
		
				<div style="overflow:auto;height:500px;background-color:none;margin-top:10px;">
				
					<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:10%">N° Month</th>
								<th style="width:10%">N° Day</th>
								<th style="width:12%">Date</th>
								<th style="width:20%">Full name</th>
								<th style="width:10%">Age</th>
								<th style="width:10%">Sexe</th>
								<th style="width:10%">Poids</th>
								<th style="width:10%">Taille</th>
								<th style="width:10%">Insurance type</th>
								<th style="width:10%">Address</th>
								<th style="width:10%">Z/HZ/HD</th>
								<th style="width:10%">NC/AC</th>
								<th style="width:10%">Status d' Enregistrement</th>
								<th style="width:40%">Plaintes/ Symptome / Signes Cliniques</th>
								<th style="width:10%">Examen Labo</th>
								<th style="width:10%">Resultat Labo</th>
								<th style="width:10%">Diagnostic</th>
								<th style="width:10%">Traitement</th>
							</tr> 
						</thead> 
						
						<tbody>
					<?php
					$compteur=1;
					$numMonth = 1;
					$numcustom = 1;
					$numDay = 1;
					
						while($ligneCashierBillReport=$resultCashierBillReport->fetch())//on recupere la liste des éléments
						{
							//$TotalDayPrice=0;
					?>
					
							<tr style="text-align:center;">
								<td>
									<?php
										if ((isset($_POST['dailydatebillPerso'])) AND !(isset($_POST['searchmonthlybillPerso']))) {
											if ($compteur == 1) {
												$id = $ligneCashierBillReport->id_consu;
												$date = date_create($_POST['dailydatebillPerso']);
												$mois = $date->format('m');
												$year = $date->format('Y');

												$daysmonth= cal_days_in_month(CAL_GREGORIAN,$mois,$year);
												if($daysmonth<10)
												{
													$daysmonth='0'.$daysmonth;
												}
												
												$dailydateperso = 'dateconsu>=\''.$year.'-'.$mois.'-01\' AND (dateconsu<\''.$year.'-'.$mois.'-'.$daysmonth.'\' OR dateconsu LIKE \''.$year.'-'.$year.'-'.$daysmonth.'%\')';


												$resultCount=$connexion->query('SELECT * FROM consultations WHERE '.$dailydateperso.' AND id_consu<="'.$id.'" ORDER BY id_consu ASC');		
												
												$resultCount->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

												$comptresultCount=$resultCount->rowCount();

												

												while ($ligneresultCount = $resultCount->fetch()) {
													//echo $numMonth.' date= '.$year.'-'.$mois.'<br>';
													$numMonth++;
												}
											}else{
												$numMonth++;
											}
											echo $numMonth;
										}else{
											/*if (isset($_POST['searchcustombillPerso'])) {
												if ($compteur == 1) {
													
												}else{
													$numcustom++;
												}
											}*/
											if (isset($_POST['searchmonthlybillPerso'])) {
												echo $numMonth;
												$numMonth++;
											}
										}

										
									?>
								</td>
								<td>
									<?php
										if (isset($_POST['searchmonthlybillPerso'])) {
											$dateconsu = $ligneCashierBillReport->dateconsu;
											$id = $ligneCashierBillReport->id_consu;
											//echo "dateconsu = ".$dateconsu."<br>";

											$resultCountDay=$connexion->query('SELECT * FROM consultations WHERE dateconsu="'.$dateconsu.'" AND id_consu="'.$id.'" ORDER BY id_consu ASC');		
												
											$resultCountDay->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptresultCountDay=$resultCountDay->rowCount();

											

											while ($ligneresultCountDay = $resultCountDay->fetch()) {
												//echo $numMonth.' date= '.$year.'-'.$mois.'<br>';
												
												//echo "string";
												//echo $numDay;
												if($compteur == 1){
													$dateconsuTest = $ligneCashierBillReport->dateconsu;
												}

												if ($dateconsu == $dateconsuTest) {
													//echo $numDay;
													//$numDay++;
												}else{
													$numDay = 1;
													//echo $numDay;
												}
											}
											echo $numDay;

										}else{
											// $numDay++;
											echo $numDay;
											$numDay++;
										}
										//echo $numDay;
									?>
								</td>
								<td>
									<?php
										$dateconsu = $ligneCashierBillReport->dateconsu;
										echo $dateconsu;
									?>
								</td>
								<td>
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneCashierBillReport->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullname = $lignePatient->full_name.' ('.$lignePatient->numero.' )';
											
											echo $fullname;
										}else{
											echo '';
										}
									?>
								</td>
								<td>
									<?php
										$dateN = $lignePatient->date_naissance;

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
										echo $an;
									?>
								</td>
								<td>
									<?php
										$sexe = $lignePatient->sexe;
										if ($sexe == 'M') {
											echo "Male";
										}else{
											if ($sexe == 'F') {
												echo "Female";
											}else{
												echo "";
											}
										}
									?>
								</td>
								<td>
									<?php
										$poids = $ligneCashierBillReport->poids;
										if ($poids != "") {
											echo $poids.' Kg';
										}else{
											echo "---";
										}
										
									?>
								</td>
								<td>
									<?php
										$taille = $ligneCashierBillReport->taille;
										if ($taille != "") {
											echo $taille.' Cm';
										}else{
											echo "---";
										}
									?>
								</td>
								<td>
									<?php
										$idassu = $ligneCashierBillReport->id_assuConsu;

										$comptAssuConsu = $connexion->query('SELECT * FROM assurances a ORDER BY a.id_assurance');
										$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										$assuCount = $comptAssuConsu->rowCount();

										for ($i=1; $i <=$assuCount ; $i++) { 
											$getAssuConsu=$connexion->prepare('SELECT * FROM assurances a  WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
											$getAssuConsu->execute(array(
												'idassu'=>$idassu
											));
											$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											if ($ligneNomAssu=$getAssuConsu->fetch()) {
												$prestations_assu = 'prestations_'.$ligneNomAssu->nomassurance;
											}
										}
										echo $ligneNomAssu->nomassurance.' ('.$ligneCashierBillReport->insupercent.' %)';
									?>
								</td>
								<td>
									<?php
										$idProvince = $lignePatient->province;
										$selectnameProvince = $connexion->prepare("SELECT * FROM province WHERE id_province=:idProvince");
										$selectnameProvince->execute(array(
											'idProvince'=>$idProvince
										));
										$selectnameProvince->setFetchMode(PDO::FETCH_OBJ);
										$ligneselectnameProvince = $selectnameProvince->fetch();
										if ($idProvince != 6) {
											$iddistrict = $lignePatient->district;
											$selectnameDistrict = $connexion->prepare("SELECT * FROM district WHERE id_district=:idDistrict AND id_province=:idProvince");
											$selectnameDistrict->execute(array(
												'idDistrict'=>$iddistrict,
												'idProvince'=>$idProvince
											));
											$selectnameDistrict->setFetchMode(PDO::FETCH_OBJ);
											$ligneselectnameDistrict = $selectnameDistrict->fetch();

											$idsector = $lignePatient->secteur;
											$selectnameSector = $connexion->prepare("SELECT * FROM sectors WHERE id_sector=:idSector AND id_district=:idDistrict");
											$selectnameSector->execute(array(
												'idSector'=>$idsector,
												'idDistrict'=>$iddistrict
											));
											$selectnameSector->setFetchMode(PDO::FETCH_OBJ);
											$ligneselectnameSector = $selectnameSector->fetch();

											echo $ligneselectnameProvince->nomprovince.", ".$ligneselectnameDistrict->nomdistrict.", ".$ligneselectnameSector->nomsector;
										}else{
											echo $lignePatient->autreadresse;
										}
									?>
								</td>
								<td>
									<?php
										if ($iddistrict == 2) {
											if ($idsector == 25) {
												echo "Z";
											}else{
												echo "HZ";
											}
										}else{
											echo "HD";
										}
									?>
								</td>
								<td>
									<?php
										if (($ligneCashierBillReport->dateconsu) == ($lignePatient->anneeadhesion)) {
											echo "NC";
										}else{
											$dateconsu = date_create($ligneCashierBillReport->dateconsu);
											$moisConsu = $dateconsu->format('m');
											$yearConsu = $dateconsu->format('Y');

											$daysmonth= cal_days_in_month(CAL_GREGORIAN,$moisConsu,$yearConsu);
											if($daysmonth<10)
											{
												$daysmonth='0'.$daysmonth;
											}
											
											$datedebut = $yearConsu.'-'.$moisConsu.'-01';
											$datefin = $ligneCashierBillReport->dateconsu;

											$researchNcAc = $connexion->prepare('SELECT * FROM consultations WHERE numero=:num AND dateconsu>=:datedebut AND dateconsu<=:datefin AND id_consu<=:id_consu ORDER BY id_consu');
											$researchNcAc->execute(array(
												'num'=>$lignePatient->numero,
												'datedebut'=>$datedebut,
												'datefin'=>$datefin,
												'id_consu'=>$ligneCashierBillReport->id_consu
											));
											$researchNcAc->setFetchMode(PDO::FETCH_OBJ);
											$compteurresearchNcAc = $researchNcAc->rowCount();
											//echo "compteurresearchNcAc = ".$compteurresearchNcAc;
											$compteurNcAc = 1;

											while ($ligneresearchNcAc = $researchNcAc->fetch()) {
												//echo "numero = ".$ligneresearchNcAc->numero;
												//echo "dateconsu = ".$ligneresearchNcAc->dateconsu;
												$compteurNcAc++;
											}
											if ($compteurresearchNcAc == 1 ) {
												echo "NC";
											}else{
												if ($compteurNcAc == 1) {
													echo "NC";
												}else{
													echo "AC";
												}
												
											}
											
										}
									?>
								</td>
								<td>
									<?php
										echo "----";
									?>
								</td>
								<td>
									<?php
										if (isset($ligneCashierBillReport->motif)) {
											echo $ligneCashierBillReport->motif;
										}
										
									?>
								</td>
								<td>
									<?php
										$dateresu = '0000-00-00';
 										$selectExa = $connexion->prepare("SELECT id_prestationExa, id_assuLab FROM med_labo WHERE numero=:num AND id_consuLabo=:id_consuLabo AND dateresultats!=:dateresultats");
										$selectExa->execute(array(
											'num'=>$ligneCashierBillReport->numero,
											'id_consuLabo'=>$ligneCashierBillReport->id_consu,
											'dateresultats'=>$dateresu
										));
										$selectExa->setFetchMode(PDO::FETCH_OBJ);
										$compteurselectExa = $selectExa->rowCount();
										//echo "compteurselectExa = ".$compteurselectExa;

										while ($ligneselectExa = $selectExa->fetch()) {
											$id_presta = $ligneselectExa->id_prestationExa;
											$id_assuLab = $ligneselectExa->id_assuLab;

											$comptAssuConsu = $connexion->query('SELECT * FROM assurances a ORDER BY a.id_assurance');
											$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											$assuCount = $comptAssuConsu->rowCount();

											for ($i=1; $i <=$assuCount ; $i++) { 
												$getAssuConsu=$connexion->prepare('SELECT * FROM assurances a  WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
												$getAssuConsu->execute(array(
													'idassu'=>$id_assuLab
												));
												$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);
												if ($ligneNomAssu=$getAssuConsu->fetch()) {
													$prestations_assu = 'prestations_'.$ligneNomAssu->nomassurance;
												}
											}
											$selectnomExa=$connexion->prepare('SELECT nompresta FROM '.$prestations_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
											$selectnomExa->execute(array(
											'idPresta'=>$id_presta
											));

											$selectnomExa->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptselectnomExa=$selectnomExa->rowCount();
											while ($ligneselectnomExa = $selectnomExa->fetch()) {
												echo $ligneselectnomExa->nompresta;
											}
					
										}
									?>
								</td>
								<td>
									<?php
										$dateresu = '0000-00-00';
 										$selectresultExa = $connexion->prepare("SELECT autreresultats FROM med_labo WHERE numero=:num AND id_consuLabo=:id_consuLabo AND dateresultats!=:dateresultats");
										$selectresultExa->execute(array(
											'num'=>$ligneCashierBillReport->numero,
											'id_consuLabo'=>$ligneCashierBillReport->id_consu,
											'dateresultats'=>$dateresu
										));
										$selectresultExa->setFetchMode(PDO::FETCH_OBJ);
										$compteurselectresultExa = $selectresultExa->rowCount();
										//echo "compteurselectExa = ".$compteurselectExa;


										while ($ligneselectresultExa = $selectresultExa->fetch()) {
											echo $ligneselectresultExa->autreresultats;
					
										}
									?>
								</td>
								<td>
									<?php
										$selectDiagno=$connexion->prepare('SELECT id_postdia,autrepostdia FROM prepostdia WHERE id_consudia=:id_consu ORDER BY id_dia');
										$selectDiagno->execute(array(
											'id_consu'=>$ligneCashierBillReport->id_consu
										));
										$selectDiagno->setFetchMode(PDO::FETCH_OBJ);
										while ($ligneselectDiagno=$selectDiagno->fetch()) {
											if (isset($ligneselectDiagno->id_postdia)) {
												$selectnomDiagno=$connexion->prepare('SELECT nomdiagno FROM diagnostic WHERE id_diagno=:id_diagno');
												$selectnomDiagno->execute(array(
													'id_diagno'=>$ligneselectDiagno->id_postdia
												));
												$selectnomDiagno->setFetchMode(PDO::FETCH_OBJ);
												$ligneselectnomDiagno=$selectnomDiagno->fetch();
												echo $ligneselectnomDiagno->nomdiagno;
											}else{
												echo $ligneselectDiagno->autrepostdia;
											}
										}

									?>
								</td>
								<td>
									<?php
										if (isset($ligneCashierBillReport->recommandations)) {
											echo $ligneCashierBillReport->recommandations;
										}

									?>
								</td>
								
							</tr>
						<?php
							//$numMonth++;
							$dateconsuTest = $ligneCashierBillReport->dateconsu;
							$numDay++;
							$compteur++;

						}
						?>
						</tbody>
					</table>
				</div>
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
				?>
			</div>
		<?php
		}else{
		/* 
			if(isset($_GET['cash']))
			{
				$dailydateperso = "";
				$caVisit="gnlPersoBill";
		?>
			
					<a href="cashier_report.php?cash=<?php echo $_GET['cash'];?>&dailydateperso=<?php echo $dailydateperso;?>&divPersoBillReport=ok&caVisit=<?php echo $caVisit;?>" style="text-align:center" id="dmacbillpersopreview">
						
						<button style="width:250px; margin:10px;" type="submit" name="printbill" id="printbill" class="btn-large-inversed">
							<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
						</button>
					
					</a>
					
		
			<div style="overflow:auto;height:500px;background-color:none;">
				
				<div id="divPersoBillReport">
					
					<?php
					
					$resultBillReport=$connexion->prepare('SELECT *FROM bills b WHERE b.codecashier=:codeCa ORDER BY datebill DESC');		
					
					$resultBillReport->execute(array(
						'codeCa'=>$cash
					));
					
					$resultBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptBillReport=$resultBillReport->rowCount();

					if($comptBillReport!=0)
					{
					?>
					
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:80%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:10%">N°</th>
								<th>Date</th>
								<th>Bill number</th>
								<th>Insurance</th>
								<th>Full name</th>
								<th><?php echo getString(113);?></th>
								<th><?php echo getString(39);?></th>
								<th><?php echo getString(98);?></th>
								<th><?php echo getString(99);?></th>
								<th>Total Final</th>
							</tr> 
						</thead> 
						
						<tbody>
					<?php
					$TotalGnlTypeConsu=0;
					$TotalGnlMedConsu=0;
					$TotalGnlMedInf=0;
					$TotalGnlMedLabo=0;
					$TotalGnlPrice=0;
					
					$compteur=1;
					
						while($ligneBillReport=$resultBillReport->fetch())//on recupere la liste des éléments
						{
					?>
					
							<tr style="text-align:center;">
							
								<td><?php echo $compteur;?></td>
								
								<td><?php echo $ligneBillReport->datebill;?></td>
								<td><?php echo $ligneBillReport->numbill;?></td>
								<td><?php echo $ligneBillReport->nomassurance.' '.$ligneBillReport->billpercent.' %';?></td>
								<?php
									$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
									$resultPatient->execute(array(
									'operation'=>$ligneBillReport->numero
									));
									
									$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptFiche=$resultPatient->rowCount();
									
									if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
									{
										$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
										$snPa = $lignePatient->numero;
										
										echo '<td>'.$fullname.' ('.$snPa.')</td>';
									}else{
										echo '<td></td>';
									}
									
								?>
									
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
									<?php
						
									$resultConsu=$connexion->prepare('SELECT *FROM consultations c, prestations_private p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
									$resultConsu->execute(array(
									'idbill'=>$ligneBillReport->id_bill
									));
									
									$comptConsu=$resultConsu->rowCount();
									
									$resultConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									if($comptConsu!=0)
									{
										while($ligneConsu=$resultConsu->fetch())//on recupere la liste des éléments
										{
									?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneConsu->nompresta;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $ligneConsu->prixpresta;
											?>
											</td>
										</tr>
									<?php
										}
									}
									?>
											
										<tr>
											<?php
											
											if($ligneBillReport->totaltypeconsuprice!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center">
											<?php

												echo $ligneBillReport->totaltypeconsuprice;
											?>
											</td>
										</tr>
									</table>
								
								</td>
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
									<?php
									
							$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
							$resultMedConsu->execute(array(
							'idbill'=>$ligneBillReport->id_bill
							));
							
							$comptMedConsu=$resultMedConsu->rowCount();
							
							$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations_private p WHERE  mc.id_factureMedConsu=:idbill GROUP BY id_medconsu ORDER BY mc.id_medconsu DESC');
							$resultMedAutreConsu->execute(array(
							'idbill'=>$ligneBillReport->id_bill
							));
							
							$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
							
							$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
						if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
						{
							if($comptMedConsu!=0)
							{
								while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
								{
									?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneMedConsu->nompresta;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $ligneMedConsu->prixpresta;
											?>
											</td>
										</tr>
									<?php
									
								}
							}
							
							if($comptMedAutreConsu!=0)
							{
								while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())//on recupere la liste des éléments
								{
									
									if($ligneMedAutreConsu->prixautreConsu!=0 and $ligneMedAutreConsu->autreConsu!="")
									{
									?>
									<tr>
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreConsu->autreConsu;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreConsu->prixautreConsu;
										
										?>
										</td>
									</tr>
									<?php
									}
								}
							}

						}					
						?>
											
										<tr>
											<?php
											
											if($ligneBillReport->totalmedconsuprice!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center">
											<?php

												echo $ligneBillReport->totalmedconsuprice;
											?>
											</td>
										</tr>
									</table>
								
								</td>
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
									<?php
									
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
							$resultMedInf->execute(array(
							'idbill'=>$ligneBillReport->id_bill
							));
							
							$comptMedInf=$resultMedInf->rowCount();
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations_private p WHERE mi.id_factureMedInf=:idbill GROUP BY id_medinf ORDER BY mi.id_medinf DESC');
							$resultMedAutreInf->execute(array(
							'idbill'=>$ligneBillReport->id_bill
							));
							
							$comptMedAutreInf=$resultMedAutreInf->rowCount();
							
							$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
						if($comptMedInf!=0 or $comptMedAutreInf!=0)
						{
							if($comptMedInf!=0)
							{
								while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des éléments
								{
									?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneMedInf->nompresta;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $ligneMedInf->prixpresta;
											?>
											</td>
										</tr>
									<?php
									
								}
							}
							
							if($comptMedAutreInf!=0)
							{
								while($ligneMedAutreInf=$resultMedAutreInf->fetch())//on recupere la liste des éléments
								{
									
									if($ligneMedAutreInf->prixautrePrestaM != 0 and $ligneMedAutreInf->autrePrestaM != "")
									{
									?>
									<tr>
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreInf->autrePrestaM;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreInf->prixautrePrestaM;
										
										?>
										</td>
									</tr>
									<?php
									}
								}
							}

						}					
						?>
											
										<tr>
											<?php
											
											if($ligneBillReport->totalmedinfprice!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center">
											<?php

												echo $ligneBillReport->totalmedinfprice;
											?>
											</td>
										</tr>
									</table>
									
								</td>
								
								<td>
									<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
											
						<?php
									
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
							$resultMedLabo->execute(array(
							'idbill'=>$ligneBillReport->id_bill
							));
							
							$comptMedLabo=$resultMedLabo->rowCount();
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations_private p WHERE ml.id_factureMedLabo=:idbill GROUP BY id_medlabo ORDER BY ml.id_medlabo DESC');
							$resultMedAutreLabo->execute(array(
							'idbill'=>$ligneBillReport->id_bill
							));
							
							$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
							
							$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
						if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
						{
							if($comptMedLabo!=0)
							{
								while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des éléments
								{
									?>
										<tr>
											<td style="text-align:center">
											<?php
												echo $ligneMedLabo->nompresta;
											?>
											</td>
											
											<td style="text-align:center">
											<?php
												echo $ligneMedLabo->prixpresta;
											?>
											</td>
										</tr>
									<?php
									
								}
							}
							
							if($comptMedAutreLabo!=0)
							{
								while($ligneMedAutreLabo=$resultMedAutreInf->fetch())//on recupere la liste des éléments
								{
									
									if($ligneMedAutreLabo->prixautreExamen != 0 and $ligneMedAutreLabo->autreExamen != "")
									{
									?>
									<tr>
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreLabo->autreExamen;
										?>
										</td>
										
										<td style="text-align:center">
										<?php
										
											echo $ligneMedAutreLabo->prixautreExamen;
										
										?>
										</td>
									</tr>
									<?php
									}
								}
							}

						}					
						?>
											
										<tr>
											<?php
											
											if($ligneBillReport->totalmedlaboprice!=0)
											{
											?>
											<td></td>
											<?php
											}
											?>
											<td style="text-align:center">
											<?php

												echo $ligneBillReport->totalmedlaboprice;
											?>
											</td>
										</tr>
									</table>
									
								</td>
								
								<td><?php echo $ligneBillReport->totalgnlprice;?></td>
							</tr>
					<?php
							$TotalGnlTypeConsu=$TotalGnlTypeConsu + $ligneBillReport->totaltypeconsuprice;
							$TotalGnlMedConsu= $TotalGnlMedConsu + $ligneBillReport->totalmedconsuprice;
							$TotalGnlMedInf= $TotalGnlMedInf + $ligneBillReport->totalmedinfprice;
							$TotalGnlMedLabo=$TotalGnlMedLabo + $ligneBillReport->totalmedlaboprice;
							$TotalGnlPrice=$TotalGnlPrice + $ligneBillReport->totalgnlprice;
							
							$compteur++;
						}
					?>
							<tr>
								<td colspan=4></td>
								<td></td>
								<td style="font-size: 13px; font-weight: bold;text-align:center;">
									<?php						
										echo $TotalGnlTypeConsu;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;text-align:center;">
									<?php						
										echo $TotalGnlMedConsu;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;text-align:center;">
									<?php						
										echo $TotalGnlMedInf;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;text-align:center;">
									<?php						
										echo $TotalGnlMedLabo;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 13px; font-weight: bold;text-align:center;">
									<?php						
										echo $TotalGnlPrice;
										
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
							</tr>
						</tbody>
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
					?>
				</div>
				
			</div>
	<?php
			} */
		}
	}
	
	
	if(isset($_GET['med']))
	{
		$idDoc=$_GET['med'];
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
		$result->execute(array(
		'operation'=>$_GET['med']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codeMed=$ligne->codemedecin;
			$fullname=$ligne->full_name;
			$sexe=$ligne->sexe;
			$adresse=$ligne->province.','.$ligne->district.','.$ligne->secteur;	
	?>
			<table style="margin:auto;">
				<tr>
					<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
						<b><h2>Individual Doctor Report</h2></b>
					</td>
				</tr>
			</table>
			
			<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:80%;">
				<tr>
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;">S/N : </span></span><?php echo $codeMed;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo ''.$fullname;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
						<?php
						if($ligne->sexe=="M")
						{
							$sexe = "Male";
						}elseif($ligne->sexe=="F"){
							
							$sexe = "Female";
						}else{
							$sexe = "";
						}
						
						echo $sexe;
						?>
					</td>
				</tr>
				<?php
				if(!isset($_SESSION['codeM']))
				{
				?>
				<tr>
					<td></td>
					<td>
						
						<a href="report.php?audit=<?php echo $_SESSION['id'];if(isset($_GET['med'])){ echo '&med='.$_GET['med'];}if(isset($_GET['coordi'])){ echo '&coordi='.$_GET['coordi'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}?>&gnlMedMedicReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['gnlMedMedicReport'])){ echo 'display:none';}else{ echo 'display:inline';}?>">
						
						<button id="gnlMedMedicReport" class="btn-large">Medical Report</button>						
						</a>

						<a href="report.php?audit=<?php echo $_SESSION['id'];if(isset($_GET['med'])){ echo '&med='.$_GET['med'];}if(isset($_GET['coordi'])){ echo '&coordi='.$_GET['coordi'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}?>&gnlMedBillReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['gnlMedBillReport'])){ echo 'display:none';}else{ echo 'display:inline';}?>">
						
						<button id="gnlMedBillReport" class="btn-large">Billing Report</button>
						</a>
					
					</td>
				</tr>
				<?php
				}
				?>
			</table>
		
		<?php 
		}
		?>
		
		<div id="selectdatePersoMedicReport" style="<?php if(isset($_GET['gnlMedMedicReport']) OR isset($_SESSION['codeM'])){ echo 'display:inline';}else{ echo 'display:none';}?>">
				<h2>Medical</h2>
				
			<form action="report.php?med=<?php echo $_GET['med'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmac=ok&gnlMedMedicReport&selectPersoMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
				<table id="dmac" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
						</td>
						
						<!--
						<td>
						<?php
						if(isset($_GET['selectPersoMedic']) OR isset($_GET['selectGnlMedic']))
						{
						?>
							<a href="report.php?med=<?php echo $_GET['med'];?>&audit=ok&report=ok" id="nomassurance" style="text-align:center;width:100px;" class="btn">Full Report</a>
						<?php
						}
						?>
						</td>
						-->
					</tr>
					
					<tr id="dmacligne" style="visibility:visible">
					
						<td id="dailymedicPerso" style="display:none;">Select date
							<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlymedicPerso" style="display:none">Select Month
						
							<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualymedicPerso" style="display:none">Select Year
						
							<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custommedicPerso" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				
				</table>


			</form>
			
		</div>
		
		<div id="selectdatePersoBillReport" style="<?php if(isset($_GET['gnlMedBillReport'])){ echo 'display:inline';}else{ echo 'display:none';}?>">
					<h2>Billing</h2>
					
			<form action="report.php?med=<?php echo $_GET['med'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmac=ok&gnlMedBillReport&selectPersoBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
				<table id="dmac" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailybillPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlybillPerso')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualybillPerso')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custombillPerso')" class="btn">Custom</span>
						</td>
						
						<!--
						<td>
						<?php
						if(isset($_GET['selectPersoBill']) OR isset($_GET['selectGnlBill']))
						{
						?>
							<a href="report.php?med=<?php echo $_GET['med'];?>&audit=ok&report=ok" id="nomassurance" style="text-align:center;width:100px;" class="btn">Full Report</a>
						<?php
						}
						?>
						</td>
						-->
					</tr>
					
					<tr id="dmacligne" style="visibility:visible">
					
						<td id="dailybillPerso" style="display:none;">Select date
							<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlybillPerso" style="display:none">Select Month
						
							<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualybillPerso" style="display:none">Select Year
						
							<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custombillPerso" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
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
		
		if(isset($_GET['dmac']) AND isset($_GET['selectPersoMedic']))
		{
			$stringResult = "";
			$dailydateperso = " AND c.dateconsu != '0000-00-00'";
			$docVisit="gnlPersoMedic";
			
			if(isset($_POST['searchdailyPerso']))
			{
				if(isset($_POST['dailydatePerso']))
				{
					$dailydateperso = ' AND dateconsu=\''.$_POST['dailydatePerso'].'\'';
					
					$docVisit="dailyPersoMedic";
				
					// $stringResult="Daily results : ".$_POST['dailydatePerso'];
					
					$stringResult=$_POST['dailydatePerso'];
				
				}

			}
			
			if(isset($_POST['searchmonthlyPerso']))
			{
				if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
				{
					$ukwezi = $_POST['monthlydatePerso'];
					$umwaka = $_POST['monthlydatePersoYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND dateconsu>=\''.$umwaka.'-'.$ukwezi.'-01\' AND dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
					
					$docVisit="monthlyPersoMedic";
					
					// $stringResult="Monthly results : ".date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
					
					$stringResult=date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
					
				}

			}
			
			if(isset($_POST['searchannualyPerso']))
			{
				if(isset($_POST['annualydatePerso']))
				{
					$year = $_POST['annualydatePerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydateperso = 'AND dateconsu>=\''.$year.'-01-01\' AND dateconsu<=\''.$year.'-12-31\'';
					
					$docVisit="annualyPersoMedic";
			
					$stringResult="Annualy results : ".$_POST['annualydatePerso'];
					
					// $stringResult=$_POST['annualydatePerso'];
			
				}
			
			}
			
			if(isset($_POST['searchcustomPerso']))
			{
				if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
				{
					$debut = $_POST['customdatedebutPerso'];
					$fin = $_POST['customdatefinPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND dateconsu>=\''.$debut.'\' AND dateconsu<=\''.$fin.'\'';
					$docVisit="customPersoMedic";
			
					// $stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
					
					$stringResult="[ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
			
			
				}

			}
			
				// echo $dailydateperso;
				// echo $ukwezi.' et '.$year;
				// echo $year;

		?>
		<div id="dmacMedicReport" style="display:inline;">
			
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
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
			$resultConsult->execute(array(
			'med'=>$_GET['med']
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();
		
			// echo $comptConsult;
			
			if($comptConsult != 0)
			{
			?>
			
			<table style="width:100%;">
				<tr>
					<td style="text-align:left; width:33.333%;">
							
						<a href="doctor_report.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoMedicReport=ok&createRN=1" style="text-align:left" id="dmacmedicalpersopreview">
							
							<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
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
		
			<div style="overflow:auto;height:500px;background-color:none;">
			
				
			<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
			<thead> 
				<tr style="text-align:left">
					<th style="width:5%">N°</th>
					<th style="width:10%">Date</th>
					<th style="width:15%">Full name</th>
					<th style="width:20%"><?php echo getString(113);?></th>
					<th style="width:10%"><?php echo getString(39);?></th>
					<th style="width:10%"><?php echo getString(98);?></th>
					<th style="width:10%"><?php echo getString(99);?></th>
					<th style="width:10%;text-align:center;"><?php echo 'Diagnosis';?></th>
				</tr> 
			</thead> 


			<tbody>
				<?php
				// $date='0000-00-00';
				$compteur=1;
				
				while($ligneConsult=$resultConsult->fetch())
				{
				?>
				<tr>
					<td style="text-align:center;">
					<?php
						echo $compteur;
					?>
					</td>
					
					<td style="text-align:center;">
					<?php
						echo $ligneConsult->dateconsu;
					?>
					</td>
					
					<td style="text-align:center;">
					<?php
						$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
						$resultPatient->execute(array(
						'operation'=>$ligneConsult->numero
						));
						
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
						{
							$fullnamePa = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
							
							echo $fullnamePa.'<br/>('.$ligneConsult->numero.')';
						}else{
							echo '';
						}
						
					?>
					</td>
					
					<td style="text-align:center;">
					<?php

					$idassuServ=$ligneConsult->id_assuConsu;

					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
					$assuCount = $comptAssuConsu->rowCount();
					
					for($i=1;$i<=$assuCount;$i++)
					{
						
						$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
						$getAssuConsu->execute(array(
						'idassu'=>$idassuServ
						));
						
						$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

						if($ligneNomAssu=$getAssuConsu->fetch())
						{
							$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
						}
					}

					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
					$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
						
						if($lignePresta->namepresta!='')
						{
							echo $lignePresta->namepresta;
						}else{								
							echo $lignePresta->nompresta;
						}
					}
					?>
					</td>
					
					<td style="text-align:center;">
					
					<?php
					$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
					$resultMedConsult->execute(array(
					'med'=>$idDoc,
					'idMedConsu'=>$ligneConsult->id_consu
					));
					
					$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptMedConsult=$resultMedConsult->rowCount();
				
				
					if($comptMedConsult != 0)
					{
					?>
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						

						<tbody>
							<?php
							while($ligneMedConsult=$resultMedConsult->fetch())
							{
							?>
							<tr style="text-align:center;">
								
								<td>
								<?php
								
								$idassuServ=$ligneMedConsult->id_assuServ;
								
								$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
								
								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										
								$assuCount = $comptAssuConsu->rowCount();
								
								for($i=1;$i<=$assuCount;$i++)
								{
									
									$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
									$getAssuConsu->execute(array(
									'idassu'=>$idassuServ
									));
									
									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if($ligneNomAssu=$getAssuConsu->fetch())
									{
										$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
									}
								}
								
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsult->id_prestationConsu
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>';
										
									}
								}else{
									
									echo $ligneMedConsult->autreConsu.'</td>';
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
					?>
					</td>
					
					<td>
					<?php
					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
					$resultMedInf->execute(array(
					'med'=>$idDoc,					
					'idMedInf'=>$ligneConsult->id_consu
					));
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptMedInf=$resultMedInf->rowCount();
				
				
					if($comptMedInf != 0)
					{
					?>		
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
					
						<tbody>
					<?php
							while($ligneMedInf=$resultMedInf->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td>
								<?php 
									
								$idassuServ=$ligneMedInf->id_assuInf;
							
								$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
								
								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										
								$assuCount = $comptAssuConsu->rowCount();
								
								for($i=1;$i<=$assuCount;$i++)
								{
									
									$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
									$getAssuConsu->execute(array(
									'idassu'=>$idassuServ
									));
									
									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if($ligneNomAssu=$getAssuConsu->fetch())
									{
										$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
									}
								}

								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>';
									}
								}else{
									
									echo $ligneMedInf->autrePrestaM.'</td>';
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
					?>					
					</td>
					
					<td>
					<?php
					$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
					$resultMedLabo->execute(array(
					'med'=>$idDoc,					
					'idMedLabo'=>$ligneConsult->id_consu
					));
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

					$comptMedLabo=$resultMedLabo->rowCount();


					if($comptMedLabo != 0)
					{
					?>	
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						
						<tbody>
					<?php
							while($ligneMedLabo=$resultMedLabo->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td style="text-align:center;font-weight:normal;">
									<?php
									$idassuServ=$ligneMedLabo->id_assuLab;
							
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($i=1;$i<=$assuCount;$i++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuServ
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
										}
									}

									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedLabo->id_prestationExa
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();


									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta;
										}else{
											echo $lignePresta->nompresta;
										}
									}else{
										
										echo $ligneMedLabo->autreExamen;
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
					</td>
					
					<td>						
					<?php
									
					$Postdia = array();
					$DiagnoPostDone=0;
														
					$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
					
					$resuPostdiagnostic->execute(array(
					'idConsu'=>$ligneConsult->id_consu
					))or die( print_r($connexion->errorInfo()));
						
					$resuPostdiagnostic->setFetchMode(PDO::FETCH_OBJ);
						
					$lignePostdiagnostic=$resuPostdiagnostic->fetch();
					
						$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
						
						$resultatsDiagnoPost->execute(array(
						'iddiagno'=>$lignePostdiagnostic->postdiagnostic
						))or die( print_r($connexion->errorInfo()));
							
						$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
						{
							$Postdia[] = $ligneDiagnoPost->nomdiagno;			
							$DiagnoPostDone=1;
						}else{
						
							if($lignePostdiagnostic->postdiagnostic != "")
							{
								$Postdia[] = $lignePostdiagnostic->postdiagnostic;
								$DiagnoPostDone=1;
							}
						}

						
					$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
					
					$resultatsPostDiagno->execute(array(
					'id_consudia'=>$lignePostdiagnostic->id_consu
					
					))or die( print_r($connexion->errorInfo()));
						
					$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
					$postdiaCount = $resultatsPostDiagno->rowCount();
					
					if($postdiaCount!=0)
					{
						
						while($linePostDiagno=$resultatsPostDiagno->fetch())
						{
							$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
							$resultsDiagno->execute(array(
							'iddiagno'=>$linePostDiagno->id_postdia
							));
							
							$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
							$comptDiagno=$resultsDiagno->rowCount();
							
							if($comptDiagno!=0)
							{
								$ligne=$resultsDiagno->fetch();			
								$Postdia[] = $ligne->nomdiagno;
								$DiagnoPostDone=1;
							}else{
								if($linePostDiagno->autrepostdia !="")
								{
									$Postdia[] = $linePostDiagno->autrepostdia;
									$DiagnoPostDone=1;
								}
							}
							
						}
					
					}


		$Predia = array();
						
		$resuPrediagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
		
		$resuPrediagnostic->execute(array(
		'idConsu'=>$ligneConsult->id_consu
		))or die( print_r($connexion->errorInfo()));
			
		$resuPrediagnostic->setFetchMode(PDO::FETCH_OBJ);
			
		$lignePrediagnostic=$resuPrediagnostic->fetch();
		
			$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
			
			$resultatsDiagnoPost->execute(array(
			'iddiagno'=>$lignePrediagnostic->prediagnostic
			))or die( print_r($connexion->errorInfo()));
				
			$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
			
			if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
			{
					$Predia[] = $ligneDiagnoPost->nomdiagno;
			}else{
			
				if($lignePrediagnostic->prediagnostic != "")
				{
					$Predia[] = $lignePrediagnostic->prediagnostic;
				}
			}

			
		$resultatsPrediagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_predia IS NOT NULL OR d.autrepredia!="") ORDER BY d.id_dia');
		
		$resultatsPrediagno->execute(array(
		'id_consudia'=>$lignePrediagnostic->id_consu
		
		))or die( print_r($connexion->errorInfo()));
			
		$resultatsPrediagno->setFetchMode(PDO::FETCH_OBJ);
		$prediaCount = $resultatsPrediagno->rowCount();
		
		if($prediaCount!=0)
		{
			
			while($linePrediagno=$resultatsPrediagno->fetch())
			{
				$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				$resultsDiagno->execute(array(
				'iddiagno'=>$linePrediagno->id_predia
				));
				
				$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
				$comptDiagno=$resultsDiagno->rowCount();
				
				if($comptDiagno!=0)
				{
					$ligne=$resultsDiagno->fetch();
					
					$Predia[] = $ligne->nomdiagno;
				}else{
					$Predia[] = $linePrediagno->autrepredia;
				}
				
			}
		
		}
	
		$Postdia = array();
					
		if(isset ($_GET['idconsu']) AND $ligneConsultation->postdiagnostic !="")
		{
			$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
			
			$resultatsDiagnoPost->execute(array(
			'iddiagno'=>$ligneConsultation->postdiagnostic
			))or die( print_r($connexion->errorInfo()));
				
			$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
			
			if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
			{
				$Postdia[] = $ligneDiagnoPost->nomdiagno;
			}else{
				$Postdia[] = $ligneConsultation->postdiagnostic;
			}
			
		}
			
		$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
		
		$resultatsPostDiagno->execute(array(
		'id_consudia'=>$ligneConsult->id_consu
		
		))or die( print_r($connexion->errorInfo()));
			
		$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
		$postdiaCount = $resultatsPostDiagno->rowCount();
		
		if($postdiaCount!=0)
		{
			
			while($linePostDiagno=$resultatsPostDiagno->fetch())
			{
				$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				$resultsDiagno->execute(array(
				'iddiagno'=>$linePostDiagno->id_postdia
				));
				
				$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
				$comptDiagno=$resultsDiagno->rowCount();
				
				if($comptDiagno!=0)
				{
					$ligne=$resultsDiagno->fetch();
					
					$Postdia[] = $ligne->nomdiagno;
				}else{
					$Postdia[] = $linePostDiagno->autrepostdia;
				}
				
			}
		
		}
					
					
					if($DiagnoPostDone ==0)
					{	
						for($p=0;$p<sizeof($Predia);$p++)
						{
							echo '-'.$Predia[$p].'<br/>';
						}
					}else{
						for($p=0;$p<sizeof($Postdia);$p++)
						{
							echo '- '.$Postdia[$p].'<br/>';
						}
					}
					?>	
					</td>		
				</tr>
				<?php
					$compteur++;
				}
				?>		
			</tbody>
			</table>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center">No Medical Report for this search</th>
						</tr> 
					</thead> 
				</table> 
				
			<?php
			}
			?>
			</div>
		</div>
		<?php
		}else{
			
			if(isset($_GET['dmac']) AND isset($_GET['selectPersoBill']))
			{
				$stringResult = "";
				$dailydateperso = " AND c.dateconsu != '0000-00-00'";
				$docVisit="gnlPersoBill";
				
				if(isset($_POST['searchdailyPerso']))
				{
					if(isset($_POST['dailydatePerso']))
					{
						$dailydateperso = ' AND dateconsu=\''.$_POST['dailydatePerso'].'\'';
						
						$docVisit="dailyPersoBill";
					
						// $stringResult="Daily results : ".$_POST['dailydatePerso'];
						
						$stringResult=$_POST['dailydatePerso'];
					
					}

				}
				
				if(isset($_POST['searchmonthlyPerso']))
				{
					if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
					{
						$ukwezi = $_POST['monthlydatePerso'];
						$umwaka = $_POST['monthlydatePersoYear'];
					
						$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						
						$dailydateperso = 'AND dateconsu>=\''.$umwaka.'-'.$ukwezi.'-1\' AND dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
						
						$docVisit="monthlyPersoBill";
						
						// $stringResult="Monthly results : ".date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
						
						$stringResult=date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
						
					}

				}
				
				if(isset($_POST['searchannualyPerso']))
				{
					if(isset($_POST['annualydatePerso']))
					{
						$year = $_POST['annualydatePerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
						
						$dailydateperso = 'AND dateconsu>=\''.$year.'-01-01\' AND dateconsu<=\''.$year.'-12-31\'';
						
						$docVisit="annualyPersoBill";
				
						// $stringResult="Annualy results : ".$_POST['annualydatePerso'];
						
						$stringResult=$_POST['annualydatePerso'];
				
					}
				
				}
				
				if(isset($_POST['searchcustomPerso']))
				{
					if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
					{
						$debut = $_POST['customdatedebutPerso'];
						$fin = $_POST['customdatefinPerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						
						$dailydateperso = 'AND dateconsu>=\''.$debut.'\' AND dateconsu<=\''.$fin.'\'';
						$docVisit="customPersoBill";
				
						// $stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
						
						$stringResult="[ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
				
				
					}

				}
				
					// echo $dailydateperso;
					// echo $ukwezi.' et '.$year;
					// echo $year;

			?>
			<div id="dmacBillReport" style="display:inline;">
				
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
				
				$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu DESC');		
				$resultConsult->execute(array(
				'med'=>$_GET['med']
				));
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
			
				// echo $comptConsult;
				
				if($comptConsult != 0)
				{
				?>
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:left; width:33.333%;">
						
					<a href="doctor_report.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createRN=1" style="text-align:left" id="dmacbillingpersopreview">
						
						<button style="width:250px; margin:auto;" type="submit" name="printBillReportPerso" id="printBillReportPerso" class="btn-large-inversed">
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
			
				<div style="overflow:auto;height:500px;background-color:none;">
				
					
				<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
				<thead> 
					<tr style="text-align:left">
						<th style="width:5%">N°</th>
						<th style="width:10%">Date</th>
						<th style="width:15%">Full name</th>
						<th style="width:20%" colspan=2><?php echo getString(113);?></th>
						<th style="width:10%"><?php echo getString(39);?></th>
						<th style="width:10%"><?php echo getString(98);?></th>
						<th style="width:10%"><?php echo getString(99);?></th>
						<th style="width:10%"><?php echo 'Radiologie';?></th>
						<th style="width:10%">Total Final</th>
					</tr> 
				</thead> 


				<tbody>
				<?php
				$TotalGnlTypeConsu=0;
					
				$TotalGnlMedConsu=0;
					
				$TotalGnlMedInf=0;
					
				$TotalGnlMedLabo=0;
					
				$TotalGnlMedRadio=0;
					
				$TotalGnlPrice=0;
					
				
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())
					{
						$TotalDayPrice=0;
				?>
					<tr>
						<td style="text-align:center;">
						<?php
							echo $compteur;
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
							echo $ligneConsult->dateconsu;
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullnamePa = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								
								echo $fullnamePa.'<br/>('.$ligneConsult->numero.')';
							}else{
								echo '';
							}
							
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
						
						$TotalTypeConsu=0;
						
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							
							if($lignePresta->namepresta!='')
							{
								echo $lignePresta->namepresta;
							}else{								
								echo $lignePresta->nompresta;
							}
						}
						?>
						</td>
						
						<td style="text-align:center;">
							<?php
								echo $ligneConsult->prixtypeconsult;
								
								$prixconsult=$ligneConsult->prixtypeconsult;
								
								$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
								
								$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
							?>
						</td>
						
						<td style="text-align:center;">
						
						<?php
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'med'=>$idDoc,
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedConsult=$resultMedConsult->rowCount();
					
						$TotalMedConsu=0;
					
						if($comptMedConsult != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
								<?php
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
								?>
								<tr style="text-align:center;">
									
									<td>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsult->id_prestationConsu
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
											
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixprestationConsu.'</td>';
										$prixconsu=$ligneMedConsult->prixprestationConsu;
									}else{
										
										echo $ligneMedConsult->autreConsu.'</td>';
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixautreConsu.'</td>';
										$prixconsu=$ligneMedConsult->prixautreConsu;
									}
									?>
								</tr>
								<?php
									$TotalMedConsu=$TotalMedConsu+$prixconsu;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
						?>
						</td>
						
						<td>
						<?php
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'med'=>$idDoc,					
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedInf=$resultMedInf->rowCount();
					
						$TotalMedInf=0;	
					
						if($comptMedInf != 0)
						{
						?>		
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						
							<tbody>
						<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixprestation.'</td>';
										$prixinf=$ligneMedInf->prixprestation;
									}else{
										
										echo $ligneMedInf->autrePrestaM.'</td>';										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixautrePrestaM.'</td>';
										$prixinf=$ligneMedInf->prixautrePrestaM;
									}
									
									?>
								</tr>
								<?php
									$TotalMedInf=$TotalMedInf+$prixinf;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
						?>					
						</td>
						
						<td>
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(
						'med'=>$idDoc,					
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

						$comptMedLabo=$resultMedLabo->rowCount();

						$TotalMedLabo=0;
						
						if($comptMedLabo != 0)
						{
						?>	
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
							<tbody>
							<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
							?>
								<tr style="text-align:center;">
									<td style="text-align:center;font-weight:normal;">
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedLabo->id_prestationExa
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();


									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{
											echo $lignePresta->nompresta.'</td>';
										}
															
										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
										$prixlabo=$ligneMedLabo->prixprestationExa;
									}else{
										
										echo $ligneMedLabo->autreExamen.'</td>';					
										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
										$prixlabo=$ligneMedLabo->prixautreExamen;
									}									
									?>
								</tr>
								<?php
									$TotalMedLabo=$TotalMedLabo+$prixlabo;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
						?>
						</td>
						
						<td>						
						<?php
								
						$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_uM=:med AND mr.id_consuRadio=:idMedRadio ORDER BY mr.id_medradio DESC');
						$resultMedRadio->execute(array(
						'med'=>$idDoc,					
						'idMedRadio'=>$ligneConsult->id_consu
						));
						
						$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

						$comptMedRadio=$resultMedRadio->rowCount();
						
						$TotalMedRadio=0;
					
						if($comptMedRadio!=0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
							<tbody>
							<?php
								while($ligneMedRadio=$resultMedRadio->fetch())
								{
							?>
								<tr style="text-align:center;">
									<td>
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedRadio->id_prestationRadio
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{
											echo $lignePresta->nompresta.'</td>';
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixprestationRadio.'</td>';
										$prixradio=$ligneMedRadio->prixprestationRadio;
									}else{
										
										echo $ligneMedRadio->autreRadio.'</td>';
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixautreRadio.'</td>';
										$prixradio=$ligneMedRadio->prixautreRadio;
									}									
									?>
									
								</tr>
								<?php
									$TotalMedRadio=$TotalMedRadio+$prixradio;
								}
								?>		
							</tbody>
							</table>
						<?php
						}						
						$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;						
						?>	
						</td>
						
						<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
							
					</tr>
					<?php
			$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
				
			$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
				
			$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
			
			$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
				
			$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
			
			$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
				
						$compteur++;
					}
					?>
					
					<tr style="text-align:center;">
						<td colspan=4></td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlTypeConsu;			
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedConsu;				
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedInf;				
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedLabo;				
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedRadio;			
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlPrice;				
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
				</table>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Billing Report for this search</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
				</div>
			</div>
		<?php
			}else{
				/*
				if(isset($_GET['med']))
				{
					$dailydateperso = "";
					$docVisit="gnlPersoMedic";
					$idDoc=$_GET['med'];

					
					$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 ORDER BY c.id_consu');		
					$resultConsult->execute(array(
					'med'=>$idDoc
					));
					
					$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptConsult=$resultConsult->rowCount();
				

					if($comptConsult != 0)
					{
					?>
					
						<a href="doctor_report.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
					
							<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
								<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
							</button>
						
						</a>	
					
					<div style="overflow:auto;height:500px;background-color:none;">
						
						<div id="divPersoMedicReport" style="display:inline;">
								
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
							<thead> 
								<tr style="text-align:left">
									<th style="width:10%">N°</th>
									<th style="width:10%">Date</th>
									<th style="width:10%">s/n</th>
									<th style="width:10%">Full name</th>
									<th style="width:35%"><?php echo getString(113);?></th>
									<th style="width:18.333%"><?php echo getString(39);?></th>
									<th style="width:18.333%"><?php echo getString(98);?></th>
									<th style="width:18.333%"><?php echo getString(99);?></th>
								</tr> 
							</thead> 


							<tbody>
						<?php
							// $date='0000-00-00';
							$compteur=1;
							
								while($ligneConsult=$resultConsult->fetch())
								{
						?>
								<tr>
									<td style="text-align:left;">
									<?php
										echo $compteur;
									?>
									</td>
									
									<td style="text-align:left;">
									<?php
										echo $ligneConsult->dateconsu;
									?>
									</td>
									
									<td style="text-align:left;">
									<?php
										echo $ligneConsult->numero;
									?>
									</td>
									
									<td style="text-align:left;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation ORDER BY c.id_consu DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneConsult->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
											
											echo $fullname;
										}else{
											echo '';
										}
										
									?>
									</td>
									
									<td style="text-align:left;">
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneConsult->id_typeconsult
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
									}
								
									echo '<td style="text-align:left;">';
									
									$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
									$resultMedConsult->execute(array(
									'med'=>$idDoc,
									'idMedConsu'=>$ligneConsult->id_consu
									));
									
									$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptMedConsult=$resultMedConsult->rowCount();
								
								
									if($comptMedConsult != 0)
									{
									?>
										<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
										

										<tbody>
									<?php
											while($ligneMedConsult=$resultMedConsult->fetch())
											{
									?>
											<tr style="text-align:center;">
												
												<td>
												<?php
												
												$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
												$resultPresta->execute(array(
													'prestaId'=>$ligneMedConsult->id_prestationConsu
												));
												
												$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

												$comptPresta=$resultPresta->rowCount();
												
												if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
												{
													if($lignePresta->namepresta!='')
													{
														echo $lignePresta->namepresta.'</td>';
													}else{								
														echo $lignePresta->nompresta.'</td>';
														
													}
												}
													echo $ligneMedConsult->autreConsu.'</td>';
												?>
											</tr>
									<?php
											}
									?>		
										</tbody>
										</table>
									<?php
									}
								
									echo '</td>';
									
									echo '<td>';
									
									$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
									$resultMedInf->execute(array(
									'med'=>$idDoc,					
									'idMedInf'=>$ligneConsult->id_consu
									));
									
									$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptMedInf=$resultMedInf->rowCount();
								
								
									if($comptMedInf != 0)
									{
									?>		
										<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
									
										<tbody>
									<?php
											while($ligneMedInf=$resultMedInf->fetch())
											{
									?>
											<tr style="text-align:center;">
												<td>
												<?php 
													
												$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
												$resultPresta->execute(array(
													'prestaId'=>$ligneMedInf->id_prestation
												));
												
												$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

												$comptPresta=$resultPresta->rowCount();
												
												if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
												{
													if($lignePresta->namepresta!='')
													{
														echo $lignePresta->namepresta.'</td>';
													}else{								
														echo $lignePresta->nompresta.'</td>';
													}
												}
												
													echo $ligneMedInf->autrePrestaM.'</td>';
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

									
									echo '</td>';
									
									echo '<td>';
									
									$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
									$resultMedLabo->execute(array(
									'med'=>$idDoc,					
									'idMedLabo'=>$ligneConsult->id_consu
									));
									
									$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

									$comptMedLabo=$resultMedLabo->rowCount();


									if($comptMedLabo != 0)
									{
									?>	
										<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
										
										<tbody>
									<?php
											while($ligneMedLabo=$resultMedLabo->fetch())
											{
									?>
											<tr style="text-align:center;">
												<td>
													<?php
													$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
													$resultPresta->execute(array(
														'prestaId'=>$ligneMedLabo->id_prestationExa
													));
													
													$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

													$comptPresta=$resultPresta->rowCount();
													
													if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
													{
														if($lignePresta->namepresta!='')
														{
															echo $lignePresta->namepresta.'</td>';
														}else{
															echo $lignePresta->nompresta.'</td>';
														}
													}
													
														echo $ligneMedLabo->autreExamen;
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
									</td>
								</tr>
								<?php
									$compteur++;
								}
								?>		
							</tbody>
							</table>

						</div>
						
					</div>
					<?php
					}else{
					?>
						<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
							<thead>
								<tr>
									<th style="width:12%;text-align:center">No Medical Report for this Doctor</th>
								</tr> 
							</thead> 
						</table> 
						
					<?php
					}
				
				}
				*/
			}
		}
	}
	
	
	if(isset($_GET['gnlmed']))
	{
	?>
		<table style="margin:auto;">
			<tr>
				<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
					<b><h2>General Doctor Report</h2></b>
				</td>
			</tr>
		</table>
	
		<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:80%;">
			<?php
			if(!isset($_SESSION['codeM']))
			{
			?>
			<tr>
				<td></td>
				<td>
					
					<a href="report.php?audit=<?php echo $_SESSION['id'];if(isset($_GET['gnlmed'])){ echo '&gnlmed='.$_GET['gnlmed'];}if(isset($_GET['coordi'])){ echo '&coordi='.$_GET['coordi'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}?>&gnlMedMedicReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['gnlMedMedicReport'])){ echo 'display:none';}else{ echo 'display:inline';}?>">
					
					<button id="gnlMedMedicReport" class="btn-large">Medical Report</button>						
					</a>

					<a href="report.php?audit=<?php echo $_SESSION['id'];if(isset($_GET['gnlmed'])){ echo '&gnlmed='.$_GET['gnlmed'];}if(isset($_GET['coordi'])){ echo '&coordi='.$_GET['coordi'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}?>&gnlMedBillReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['gnlMedBillReport'])){ echo 'display:none';}else{ echo 'display:inline';}?>">
					
					<button id="gnlMedBillReport" class="btn-large">Billing Report</button>
					</a>
				
				</td>
			</tr>
			<?php
			}
			?>
		</table>
	
		<div id="selectdatePersoMedicReport" style="<?php if(isset($_GET['gnlMedMedicReport']) OR isset($_SESSION['codeM'])){ echo 'display:inline';}else{ echo 'display:none';}?>">
				<h2>Medical</h2>
				
			<form action="report.php?gnlmed=<?php echo $_GET['gnlmed'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmac=ok&gnlMedMedicReport&selectPersoMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
				<table id="dmac" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
						</td>
						
						<!--
						<td>
						<?php
						if(isset($_GET['selectPersoMedic']) OR isset($_GET['selectGnlMedic']))
						{
						?>
							<a href="report.php?med=<?php echo $_GET['med'];?>&audit=ok&report=ok" id="nomassurance" style="text-align:center;width:100px;" class="btn">Full Report</a>
						<?php
						}
						?>
						</td>
						-->
					</tr>
					
					<tr id="dmacligne" style="visibility:visible">
					
						<td id="dailymedicPerso" style="display:none;">Select date
							<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlymedicPerso" style="display:none">Select Month
						
							<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualymedicPerso" style="display:none">Select Year
						
							<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custommedicPerso" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				
				</table>


			</form>
			
		</div>
		
		<div id="selectdatePersoBillReport" style="<?php if(isset($_GET['gnlMedBillReport'])){ echo 'display:inline';}else{ echo 'display:none';}?>">
					<h2>Billing</h2>
					
			<form action="report.php?gnlmed=<?php echo $_GET['gnlmed'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmac=ok&gnlMedBillReport&selectPersoBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
				<table id="dmac" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailybillPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlybillPerso')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualybillPerso')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custombillPerso')" class="btn">Custom</span>
						</td>
						
						<!--
						<td>
						<?php
						if(isset($_GET['selectPersoBill']) OR isset($_GET['selectGnlBill']))
						{
						?>
							<a href="report.php?med=<?php echo $_GET['med'];?>&audit=ok&report=ok" id="nomassurance" style="text-align:center;width:100px;" class="btn">Full Report</a>
						<?php
						}
						?>
						</td>
						-->
					</tr>
					
					<tr id="dmacligne" style="visibility:visible">
					
						<td id="dailybillPerso" style="display:none;">Select date
							<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlybillPerso" style="display:none">Select Month
						
							<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualybillPerso" style="display:none">Select Year
						
							<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custombillPerso" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
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
		
		if(isset($_GET['dmac']) AND isset($_GET['selectPersoMedic']))
		{
			$stringResult = "";
			$dailydateperso = " AND c.dateconsu != '0000-00-00'";
			$docVisit="gnlPersoMedic";
			
			if(isset($_POST['searchdailyPerso']))
			{
				if(isset($_POST['dailydatePerso']))
				{
					$dailydateperso = ' AND dateconsu=\''.$_POST['dailydatePerso'].'\'';
					
					$docVisit="dailyPersoMedic";
				
					// $stringResult="Daily results : ".$_POST['dailydatePerso'];
					
					$stringResult=$_POST['dailydatePerso'];
				
				}

			}
			
			if(isset($_POST['searchmonthlyPerso']))
			{
				if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
				{
					$ukwezi = $_POST['monthlydatePerso'];
					$umwaka = $_POST['monthlydatePersoYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND dateconsu>=\''.$umwaka.'-'.$ukwezi.'-01\' AND dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
					
					$docVisit="monthlyPersoMedic";
					
					// $stringResult="Monthly results : ".date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
					
					$stringResult=date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
					
				}

			}
			
			if(isset($_POST['searchannualyPerso']))
			{
				if(isset($_POST['annualydatePerso']))
				{
					$year = $_POST['annualydatePerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydateperso = 'AND dateconsu>=\''.$year.'-01-01\' AND dateconsu<=\''.$year.'-12-31\'';
					
					$docVisit="annualyPersoMedic";
			
					$stringResult="Annualy results : ".$_POST['annualydatePerso'];
					
					// $stringResult=$_POST['annualydatePerso'];
			
				}
			
			}
			
			if(isset($_POST['searchcustomPerso']))
			{
				if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
				{
					$debut = $_POST['customdatedebutPerso'];
					$fin = $_POST['customdatefinPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND dateconsu>=\''.$debut.'\' AND dateconsu<=\''.$fin.'\'';
					$docVisit="customPersoMedic";
			
					// $stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
					
					$stringResult="[ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
			
			
				}

			}
			
				// echo $dailydateperso;
				// echo $ukwezi.' et '.$year;
				// echo $year;

		?>
		<div id="dmacMedicReport" style="display:inline;">
			
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

			<table style="width:100%;">
				<tr>
					<td style="text-align:left; width:33.333%;">
							
						<a href="doctor_report.php?gnlmed=<?php echo $_GET['gnlmed'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoMedicReport=ok&createRN=1" style="text-align:left" id="dmacmedicalpersopreview">
							
							<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
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
		
			<div style="overflow:auto;height:500px;background-color:none;">
			
				<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
				<thead> 
					<tr style="text-align:left">
						<th style="width:5%">N°</th>
						<th style="width:10%">Date</th>
						<th style="width:15%">Full name</th>
						<th style="width:20%"><?php echo getString(113);?></th>
						<th style="width:10%"><?php echo getString(39);?></th>
						<th style="width:10%"><?php echo getString(98);?></th>
						<th style="width:10%"><?php echo getString(99);?></th>
						<th style="width:10%;text-align:center;"><?php echo 'Diagnosis';?></th>
					</tr> 
				</thead> 

			<?php
			$resultGnlMed=$connexion->query('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u ORDER BY u.full_name ASC');
			
			$resultGnlMed->setFetchMode(PDO::FETCH_OBJ);
			
			
			while($ligne=$resultGnlMed->fetch())
			{
				$idDoc=$ligne->id_u;
				
				$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
				$resultConsult->execute(array(
				'med'=>$idDoc
				));
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
			
				if($comptConsult != 0)
				{
				?>
					<tbody>
						<tr>
							<td style="text-align:center;background:#eee;" colspan=8>
							<?php
								echo $ligne->full_name;
							?>
							</td>
						</tr>
							
						<?php
						// $date='0000-00-00';
						$compteur=1;
						
						while($ligneConsult=$resultConsult->fetch())
						{
						?>
						<tr>
							<td style="text-align:center;">
							<?php
								echo $compteur;
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
								echo $ligneConsult->dateconsu;
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneConsult->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									$fullnamePa = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
									
									echo $fullnamePa.'<br/>('.$ligneConsult->numero.')';
								}else{
									echo '';
								}
								
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneConsult->id_typeconsult
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
							{
								
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								}else{								
									echo $lignePresta->nompresta;
								}
							}
							?>
							</td>
							
							<td style="text-align:center;">
							
							<?php
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
							$resultMedConsult->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_consu
							));
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptMedConsult=$resultMedConsult->rowCount();
						
						
							if($comptMedConsult != 0)
							{
							?>
								<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
								

								<tbody>
									<?php
									while($ligneMedConsult=$resultMedConsult->fetch())
									{
									?>
									<tr style="text-align:center;">
										
										<td>
										<?php
										
										$idassuServ=$ligneMedConsult->id_assuServ;
										
										$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
										
										$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuConsu->rowCount();
										
										for($i=1;$i<=$assuCount;$i++)
										{
											
											$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssuConsu->execute(array(
											'idassu'=>$idassuServ
											));
											
											$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssu=$getAssuConsu->fetch())
											{
												$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
											}
										}
										
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedConsult->id_prestationConsu
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
											}else{								
												echo $lignePresta->nompresta.'</td>';
												
											}
										}else{
											
											echo $ligneMedConsult->autreConsu.'</td>';
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
							?>
							</td>
							
							<td>
							<?php
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
							$resultMedInf->execute(array(
							'med'=>$idDoc,					
							'idMedInf'=>$ligneConsult->id_consu
							));
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptMedInf=$resultMedInf->rowCount();
						
						
							if($comptMedInf != 0)
							{
							?>		
								<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
								<tbody>
							<?php
									while($ligneMedInf=$resultMedInf->fetch())
									{
							?>
									<tr style="text-align:center;">
										<td>
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedInf->id_prestation
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
											}else{								
												echo $lignePresta->nompresta.'</td>';
											}
										}else{
											
											echo $ligneMedInf->autrePrestaM.'</td>';
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
							?>					
							</td>
							
							<td>
							<?php
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'med'=>$idDoc,					
							'idMedLabo'=>$ligneConsult->id_consu
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMedLabo=$resultMedLabo->rowCount();


							if($comptMedLabo != 0)
							{
							?>	
								<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
								
								<tbody>
							<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
							?>
									<tr style="text-align:center;">
										<td style="text-align:center;font-weight:normal;">
											<?php
											$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
											$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
											));
											
											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta=$resultPresta->rowCount();


											if($lignePresta=$resultPresta->fetch())
											{
												if($lignePresta->namepresta!='')
												{
													echo $lignePresta->namepresta;
												}else{
													echo $lignePresta->nompresta;
												}
											}else{
												
												echo $ligneMedLabo->autreExamen;
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
							</td>
							
							<td>						
							<?php
											
							$Postdia = array();
							$DiagnoPostDone=0;
																
							$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
							
							$resuPostdiagnostic->execute(array(
							'idConsu'=>$ligneConsult->id_consu
							))or die( print_r($connexion->errorInfo()));
								
							$resuPostdiagnostic->setFetchMode(PDO::FETCH_OBJ);
								
							$lignePostdiagnostic=$resuPostdiagnostic->fetch();
							
								$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
								
								$resultatsDiagnoPost->execute(array(
								'iddiagno'=>$lignePostdiagnostic->postdiagnostic
								))or die( print_r($connexion->errorInfo()));
									
								$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
								
								if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
								{
									$Postdia[] = $ligneDiagnoPost->nomdiagno;			
									$DiagnoPostDone=1;
								}else{
								
									if($lignePostdiagnostic->postdiagnostic != "")
									{
										$Postdia[] = $lignePostdiagnostic->postdiagnostic;
										$DiagnoPostDone=1;
									}
								}

								
							$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
							
							$resultatsPostDiagno->execute(array(
							'id_consudia'=>$lignePostdiagnostic->id_consu
							
							))or die( print_r($connexion->errorInfo()));
								
							$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
							$postdiaCount = $resultatsPostDiagno->rowCount();
							
							if($postdiaCount!=0)
							{
								
								while($linePostDiagno=$resultatsPostDiagno->fetch())
								{
									$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
									$resultsDiagno->execute(array(
									'iddiagno'=>$linePostDiagno->id_postdia
									));
									
									$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
									$comptDiagno=$resultsDiagno->rowCount();
									
									if($comptDiagno!=0)
									{
										$ligne=$resultsDiagno->fetch();			
										$Postdia[] = $ligne->nomdiagno;
										$DiagnoPostDone=1;
									}else{
										if($linePostDiagno->autrepostdia !="")
										{
											$Postdia[] = $linePostDiagno->autrepostdia;
											$DiagnoPostDone=1;
										}
									}
									
								}
							
							}


				$Predia = array();
								
				$resuPrediagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
				
				$resuPrediagnostic->execute(array(
				'idConsu'=>$ligneConsult->id_consu
				))or die( print_r($connexion->errorInfo()));
					
				$resuPrediagnostic->setFetchMode(PDO::FETCH_OBJ);
					
				$lignePrediagnostic=$resuPrediagnostic->fetch();
				
					$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
					
					$resultatsDiagnoPost->execute(array(
					'iddiagno'=>$lignePrediagnostic->prediagnostic
					))or die( print_r($connexion->errorInfo()));
						
					$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
					{
							$Predia[] = $ligneDiagnoPost->nomdiagno;
					}else{
					
						if($lignePrediagnostic->prediagnostic != "")
						{
							$Predia[] = $lignePrediagnostic->prediagnostic;
						}
					}

					
				$resultatsPrediagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_predia IS NOT NULL OR d.autrepredia!="") ORDER BY d.id_dia');
				
				$resultatsPrediagno->execute(array(
				'id_consudia'=>$lignePrediagnostic->id_consu
				
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsPrediagno->setFetchMode(PDO::FETCH_OBJ);
				$prediaCount = $resultatsPrediagno->rowCount();
				
				if($prediaCount!=0)
				{
					
					while($linePrediagno=$resultatsPrediagno->fetch())
					{
						$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
						$resultsDiagno->execute(array(
						'iddiagno'=>$linePrediagno->id_predia
						));
						
						$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
						$comptDiagno=$resultsDiagno->rowCount();
						
						if($comptDiagno!=0)
						{
							$ligne=$resultsDiagno->fetch();
							
							$Predia[] = $ligne->nomdiagno;
						}else{
							$Predia[] = $linePrediagno->autrepredia;
						}
						
					}
				
				}
			
				$Postdia = array();
							
				if(isset ($_GET['idconsu']) AND $ligneConsultation->postdiagnostic !="")
				{
					$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
					
					$resultatsDiagnoPost->execute(array(
					'iddiagno'=>$ligneConsultation->postdiagnostic
					))or die( print_r($connexion->errorInfo()));
						
					$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
					{
						$Postdia[] = $ligneDiagnoPost->nomdiagno;
					}else{
						$Postdia[] = $ligneConsultation->postdiagnostic;
					}
					
				}
					
				$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
				
				$resultatsPostDiagno->execute(array(
				'id_consudia'=>$ligneConsult->id_consu
				
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
				$postdiaCount = $resultatsPostDiagno->rowCount();
				
				if($postdiaCount!=0)
				{
					
					while($linePostDiagno=$resultatsPostDiagno->fetch())
					{
						$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
						$resultsDiagno->execute(array(
						'iddiagno'=>$linePostDiagno->id_postdia
						));
						
						$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
						$comptDiagno=$resultsDiagno->rowCount();
						
						if($comptDiagno!=0)
						{
							$ligne=$resultsDiagno->fetch();
							
							$Postdia[] = $ligne->nomdiagno;
						}else{
							$Postdia[] = $linePostDiagno->autrepostdia;
						}
						
					}
				
				}
							
							
							if($DiagnoPostDone ==0)
							{	
								for($p=0;$p<sizeof($Predia);$p++)
								{
									echo '-'.$Predia[$p].'<br/>';
								}
							}else{
								for($p=0;$p<sizeof($Postdia);$p++)
								{
									echo '- '.$Postdia[$p].'<br/>';
								}
							}
							?>	
							</td>		
						</tr>
						<?php
							$compteur++;
						}
						?>		
					</tbody>
					
				<?php
				}/* else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Medical Report for <?php echo $ligne->full_name?></th>
							</tr> 
						</thead> 
					</table>				
			<?php
				} */
			}
			?>
				</table>
				
			</div>
			
		</div>
		<?php
		}else{
			
			if(isset($_GET['dmac']) AND isset($_GET['selectPersoBill']))
			{
				$stringResult = "";
				$dailydateperso = " AND c.dateconsu != '0000-00-00'";
				$docVisit="gnlPersoBill";
				
				if(isset($_POST['searchdailyPerso']))
				{
					if(isset($_POST['dailydatePerso']))
					{
						$dailydateperso = ' AND dateconsu=\''.$_POST['dailydatePerso'].'\'';
						
						$docVisit="dailyPersoBill";
					
						// $stringResult="Daily results : ".$_POST['dailydatePerso'];
						
						$stringResult=$_POST['dailydatePerso'];
					
					}

				}
				
				if(isset($_POST['searchmonthlyPerso']))
				{
					if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
					{
						$ukwezi = $_POST['monthlydatePerso'];
						$umwaka = $_POST['monthlydatePersoYear'];
					
						$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						
						$dailydateperso = 'AND dateconsu>=\''.$umwaka.'-'.$ukwezi.'-1\' AND dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
						
						$docVisit="monthlyPersoBill";
						
						// $stringResult="Monthly results : ".date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
						
						$stringResult=date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
						
					}

				}
				
				if(isset($_POST['searchannualyPerso']))
				{
					if(isset($_POST['annualydatePerso']))
					{
						$year = $_POST['annualydatePerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
						
						$dailydateperso = 'AND dateconsu>=\''.$year.'-01-01\' AND dateconsu<=\''.$year.'-12-31\'';
						
						$docVisit="annualyPersoBill";
				
						// $stringResult="Annualy results : ".$_POST['annualydatePerso'];
						
						$stringResult=$_POST['annualydatePerso'];
				
					}
				
				}
				
				if(isset($_POST['searchcustomPerso']))
				{
					if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
					{
						$debut = $_POST['customdatedebutPerso'];
						$fin = $_POST['customdatefinPerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						
						$dailydateperso = 'AND dateconsu>=\''.$debut.'\' AND dateconsu<=\''.$fin.'\'';
						$docVisit="customPersoBill";
				
						// $stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
						
						$stringResult="[ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
				
				
					}

				}
				
					// echo $dailydateperso;
					// echo $ukwezi.' et '.$year;
					// echo $year;

			?>
			<div id="dmacBillReport" style="display:inline;">
				
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
				
				$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu DESC');		
				$resultConsult->execute(array(
				'med'=>$_GET['med']
				));
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
			
				// echo $comptConsult;
				
				if($comptConsult != 0)
				{
				?>
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:left; width:33.333%;">
						
					<a href="doctor_report.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createRN=1" style="text-align:left" id="dmacbillingpersopreview">
						
						<button style="width:250px; margin:auto;" type="submit" name="printBillReportPerso" id="printBillReportPerso" class="btn-large-inversed">
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
			
				<div style="overflow:auto;height:500px;background-color:none;">
				
					
				<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
				<thead> 
					<tr style="text-align:left">
						<th style="width:5%">N°</th>
						<th style="width:10%">Date</th>
						<th style="width:15%">Full name</th>
						<th style="width:20%" colspan=2><?php echo getString(113);?></th>
						<th style="width:10%"><?php echo getString(39);?></th>
						<th style="width:10%"><?php echo getString(98);?></th>
						<th style="width:10%"><?php echo getString(99);?></th>
						<th style="width:10%"><?php echo 'Radiologie';?></th>
						<th style="width:10%">Total Final</th>
					</tr> 
				</thead> 


				<tbody>
				<?php
				$TotalGnlTypeConsu=0;
					
				$TotalGnlMedConsu=0;
					
				$TotalGnlMedInf=0;
					
				$TotalGnlMedLabo=0;
					
				$TotalGnlMedRadio=0;
					
				$TotalGnlPrice=0;
					
				
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())
					{
						$TotalDayPrice=0;
				?>
					<tr>
						<td style="text-align:center;">
						<?php
							echo $compteur;
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
							echo $ligneConsult->dateconsu;
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullnamePa = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								
								echo $fullnamePa.'<br/>('.$ligneConsult->numero.')';
							}else{
								echo '';
							}
							
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
						
						$TotalTypeConsu=0;
						
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							
							if($lignePresta->namepresta!='')
							{
								echo $lignePresta->namepresta;
							}else{								
								echo $lignePresta->nompresta;
							}
						}
						?>
						</td>
						
						<td style="text-align:center;">
							<?php
								echo $ligneConsult->prixtypeconsult;
								
								$prixconsult=$ligneConsult->prixtypeconsult;
								
								$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
								
								$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
							?>
						</td>
						
						<td style="text-align:center;">
						
						<?php
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'med'=>$idDoc,
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedConsult=$resultMedConsult->rowCount();
					
						$TotalMedConsu=0;
					
						if($comptMedConsult != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
								<?php
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
								?>
								<tr style="text-align:center;">
									
									<td>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsult->id_prestationConsu
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
											
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixprestationConsu.'</td>';
										$prixconsu=$ligneMedConsult->prixprestationConsu;
									}else{
										
										echo $ligneMedConsult->autreConsu.'</td>';
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixautreConsu.'</td>';
										$prixconsu=$ligneMedConsult->prixautreConsu;
									}
									?>
								</tr>
								<?php
									$TotalMedConsu=$TotalMedConsu+$prixconsu;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
						?>
						</td>
						
						<td>
						<?php
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'med'=>$idDoc,					
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedInf=$resultMedInf->rowCount();
					
						$TotalMedInf=0;	
					
						if($comptMedInf != 0)
						{
						?>		
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						
							<tbody>
						<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixprestation.'</td>';
										$prixinf=$ligneMedInf->prixprestation;
									}else{
										
										echo $ligneMedInf->autrePrestaM.'</td>';										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixautrePrestaM.'</td>';
										$prixinf=$ligneMedInf->prixautrePrestaM;
									}
									
									?>
								</tr>
								<?php
									$TotalMedInf=$TotalMedInf+$prixinf;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
						?>					
						</td>
						
						<td>
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(
						'med'=>$idDoc,					
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

						$comptMedLabo=$resultMedLabo->rowCount();

						$TotalMedLabo=0;
						
						if($comptMedLabo != 0)
						{
						?>	
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
							<tbody>
							<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
							?>
								<tr style="text-align:center;">
									<td style="text-align:center;font-weight:normal;">
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedLabo->id_prestationExa
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();


									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{
											echo $lignePresta->nompresta.'</td>';
										}
															
										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
										$prixlabo=$ligneMedLabo->prixprestationExa;
									}else{
										
										echo $ligneMedLabo->autreExamen.'</td>';					
										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
										$prixlabo=$ligneMedLabo->prixautreExamen;
									}									
									?>
								</tr>
								<?php
									$TotalMedLabo=$TotalMedLabo+$prixlabo;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
						?>
						</td>
						
						<td>						
						<?php
								
						$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_uM=:med AND mr.id_consuRadio=:idMedRadio ORDER BY mr.id_medradio DESC');
						$resultMedRadio->execute(array(
						'med'=>$idDoc,					
						'idMedRadio'=>$ligneConsult->id_consu
						));
						
						$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

						$comptMedRadio=$resultMedRadio->rowCount();
						
						$TotalMedRadio=0;
					
						if($comptMedRadio!=0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
							<tbody>
							<?php
								while($ligneMedRadio=$resultMedRadio->fetch())
								{
							?>
								<tr style="text-align:center;">
									<td>
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedRadio->id_prestationRadio
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{
											echo $lignePresta->nompresta.'</td>';
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixprestationRadio.'</td>';
										$prixradio=$ligneMedRadio->prixprestationRadio;
									}else{
										
										echo $ligneMedRadio->autreRadio.'</td>';
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixautreRadio.'</td>';
										$prixradio=$ligneMedRadio->prixautreRadio;
									}									
									?>
									
								</tr>
								<?php
									$TotalMedRadio=$TotalMedRadio+$prixradio;
								}
								?>		
							</tbody>
							</table>
						<?php
						}						
						$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;						
						?>	
						</td>
						
						<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
							
					</tr>
					<?php
			$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
				
			$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
				
			$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
			
			$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
				
			$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
			
			$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
				
						$compteur++;
					}
					?>
					
					<tr style="text-align:center;">
						<td colspan=4></td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlTypeConsu;			
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedConsu;				
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedInf;				
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedLabo;				
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedRadio;			
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlPrice;				
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
				</table>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Billing Report for this search</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
				</div>
			</div>
		<?php
			}else{
			
				if(isset($_GET['dmac']) AND isset($_GET['selectPersoMedic']))
				{
					$stringResult = "";
					$dailydateperso = " AND c.dateconsu != '0000-00-00'";
					$docVisit="gnlPersoMedic";
					
					if(isset($_POST['searchdailyPerso']))
					{
						if(isset($_POST['dailydatePerso']))
						{
							$dailydateperso = ' AND dateconsu=\''.$_POST['dailydatePerso'].'\'';
							
							$docVisit="dailyPersoMedic";
						
							// $stringResult="Daily results : ".$_POST['dailydatePerso'];
							
							$stringResult=$_POST['dailydatePerso'];
						
						}

					}
					
					if(isset($_POST['searchmonthlyPerso']))
					{
						if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
						{
							$ukwezi = $_POST['monthlydatePerso'];
							$umwaka = $_POST['monthlydatePersoYear'];
						
							$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
							
							$dailydateperso = 'AND dateconsu>=\''.$umwaka.'-'.$ukwezi.'-01\' AND dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
							
							$docVisit="monthlyPersoMedic";
							
							// $stringResult="Monthly results : ".date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
							
							$stringResult=date("F",mktime(0,0,0,$_POST['monthlydatePerso'],10))."-".$_POST['monthlydatePersoYear'];
							
						}

					}
					
					if(isset($_POST['searchannualyPerso']))
					{
						if(isset($_POST['annualydatePerso']))
						{
							$year = $_POST['annualydatePerso'];
						
							// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
							
							$dailydateperso = 'AND dateconsu>=\''.$year.'-01-01\' AND dateconsu<=\''.$year.'-12-31\'';
							
							$docVisit="annualyPersoMedic";
					
							$stringResult="Annualy results : ".$_POST['annualydatePerso'];
							
							// $stringResult=$_POST['annualydatePerso'];
					
						}
					
					}
					
					if(isset($_POST['searchcustomPerso']))
					{
						if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
						{
							$debut = $_POST['customdatedebutPerso'];
							$fin = $_POST['customdatefinPerso'];
						
							// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
							
							$dailydateperso = 'AND dateconsu>=\''.$debut.'\' AND dateconsu<=\''.$fin.'\'';
							$docVisit="customPersoMedic";
					
							// $stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
							
							$stringResult="[ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
					
					
						}

					}
					
						// echo $dailydateperso;
						// echo $ukwezi.' et '.$year;
						// echo $year;

				?>
				<div id="dmacMedicReport" style="display:inline;">
					
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
					
					$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
					$resultConsult->execute(array(
					'med'=>$_GET['med']
					));
					
					$resultConsult->setFetchMode(PDO::FETCH_OBJ);

					$comptConsult=$resultConsult->rowCount();
				
					// echo $comptConsult;
					
					if($comptConsult != 0)
					{
					?>
					
					<table style="width:100%;">
						<tr>
							<td style="text-align:left; width:33.333%;">
									
								<a href="doctor_report.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoMedicReport=ok&createRN=1" style="text-align:left" id="dmacmedicalpersopreview">
									
									<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
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
				
					<div style="overflow:auto;height:500px;background-color:none;">
					
						
					<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
					<thead> 
						<tr style="text-align:left">
							<th style="width:5%">N°</th>
							<th style="width:10%">Date</th>
							<th style="width:15%">Full name</th>
							<th style="width:20%"><?php echo getString(113);?></th>
							<th style="width:10%"><?php echo getString(39);?></th>
							<th style="width:10%"><?php echo getString(98);?></th>
							<th style="width:10%"><?php echo getString(99);?></th>
							<th style="width:10%;text-align:center;"><?php echo 'Diagnosis';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						// $date='0000-00-00';
						$compteur=1;
						
						while($ligneConsult=$resultConsult->fetch())
						{
						?>
						<tr>
							<td style="text-align:center;">
							<?php
								echo $compteur;
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
								echo $ligneConsult->dateconsu;
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneConsult->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									$fullnamePa = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
									
									echo $fullnamePa.'<br/>('.$ligneConsult->numero.')';
								}else{
									echo '';
								}
								
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneConsult->id_typeconsult
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
							{
								
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta;
								}else{								
									echo $lignePresta->nompresta;
								}
							}
							?>
							</td>
							
							<td style="text-align:center;">
							
							<?php
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
							$resultMedConsult->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_consu
							));
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptMedConsult=$resultMedConsult->rowCount();
						
						
							if($comptMedConsult != 0)
							{
							?>
								<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
								

								<tbody>
									<?php
									while($ligneMedConsult=$resultMedConsult->fetch())
									{
									?>
									<tr style="text-align:center;">
										
										<td>
										<?php
										
										$idassuServ=$ligneMedConsult->id_assuServ;
										
										$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
										
										$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuConsu->rowCount();
										
										for($i=1;$i<=$assuCount;$i++)
										{
											
											$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssuConsu->execute(array(
											'idassu'=>$idassuServ
											));
											
											$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssu=$getAssuConsu->fetch())
											{
												$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
											}
										}
										
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedConsult->id_prestationConsu
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
											}else{								
												echo $lignePresta->nompresta.'</td>';
												
											}
										}else{
											
											echo $ligneMedConsult->autreConsu.'</td>';
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
							?>
							</td>
							
							<td>
							<?php
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
							$resultMedInf->execute(array(
							'med'=>$idDoc,					
							'idMedInf'=>$ligneConsult->id_consu
							));
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptMedInf=$resultMedInf->rowCount();
						
						
							if($comptMedInf != 0)
							{
							?>		
								<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
								<tbody>
							<?php
									while($ligneMedInf=$resultMedInf->fetch())
									{
							?>
									<tr style="text-align:center;">
										<td>
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedInf->id_prestation
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
											}else{								
												echo $lignePresta->nompresta.'</td>';
											}
										}else{
											
											echo $ligneMedInf->autrePrestaM.'</td>';
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
							?>					
							</td>
							
							<td>
							<?php
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'med'=>$idDoc,					
							'idMedLabo'=>$ligneConsult->id_consu
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMedLabo=$resultMedLabo->rowCount();


							if($comptMedLabo != 0)
							{
							?>	
								<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
								
								<tbody>
							<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
							?>
									<tr style="text-align:center;">
										<td style="text-align:center;font-weight:normal;">
											<?php
											$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
											$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
											));
											
											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta=$resultPresta->rowCount();


											if($lignePresta=$resultPresta->fetch())
											{
												if($lignePresta->namepresta!='')
												{
													echo $lignePresta->namepresta;
												}else{
													echo $lignePresta->nompresta;
												}
											}else{
												
												echo $ligneMedLabo->autreExamen;
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
							</td>
							
							<td>						
							<?php
											
							$Postdia = array();
							$DiagnoPostDone=0;
																
							$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
							
							$resuPostdiagnostic->execute(array(
							'idConsu'=>$ligneConsult->id_consu
							))or die( print_r($connexion->errorInfo()));
								
							$resuPostdiagnostic->setFetchMode(PDO::FETCH_OBJ);
								
							$lignePostdiagnostic=$resuPostdiagnostic->fetch();
							
								$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
								
								$resultatsDiagnoPost->execute(array(
								'iddiagno'=>$lignePostdiagnostic->postdiagnostic
								))or die( print_r($connexion->errorInfo()));
									
								$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
								
								if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
								{
									$Postdia[] = $ligneDiagnoPost->nomdiagno;			
									$DiagnoPostDone=1;
								}else{
								
									if($lignePostdiagnostic->postdiagnostic != "")
									{
										$Postdia[] = $lignePostdiagnostic->postdiagnostic;
										$DiagnoPostDone=1;
									}
								}

								
							$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
							
							$resultatsPostDiagno->execute(array(
							'id_consudia'=>$lignePostdiagnostic->id_consu
							
							))or die( print_r($connexion->errorInfo()));
								
							$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
							$postdiaCount = $resultatsPostDiagno->rowCount();
							
							if($postdiaCount!=0)
							{
								
								while($linePostDiagno=$resultatsPostDiagno->fetch())
								{
									$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
									$resultsDiagno->execute(array(
									'iddiagno'=>$linePostDiagno->id_postdia
									));
									
									$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
									$comptDiagno=$resultsDiagno->rowCount();
									
									if($comptDiagno!=0)
									{
										$ligne=$resultsDiagno->fetch();			
										$Postdia[] = $ligne->nomdiagno;
										$DiagnoPostDone=1;
									}else{
										if($linePostDiagno->autrepostdia !="")
										{
											$Postdia[] = $linePostDiagno->autrepostdia;
											$DiagnoPostDone=1;
										}
									}
									
								}
							
							}


				$Predia = array();
								
				$resuPrediagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
				
				$resuPrediagnostic->execute(array(
				'idConsu'=>$ligneConsult->id_consu
				))or die( print_r($connexion->errorInfo()));
					
				$resuPrediagnostic->setFetchMode(PDO::FETCH_OBJ);
					
				$lignePrediagnostic=$resuPrediagnostic->fetch();
				
					$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
					
					$resultatsDiagnoPost->execute(array(
					'iddiagno'=>$lignePrediagnostic->prediagnostic
					))or die( print_r($connexion->errorInfo()));
						
					$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
					{
							$Predia[] = $ligneDiagnoPost->nomdiagno;
					}else{
					
						if($lignePrediagnostic->prediagnostic != "")
						{
							$Predia[] = $lignePrediagnostic->prediagnostic;
						}
					}

					
				$resultatsPrediagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_predia IS NOT NULL OR d.autrepredia!="") ORDER BY d.id_dia');
				
				$resultatsPrediagno->execute(array(
				'id_consudia'=>$lignePrediagnostic->id_consu
				
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsPrediagno->setFetchMode(PDO::FETCH_OBJ);
				$prediaCount = $resultatsPrediagno->rowCount();
				
				if($prediaCount!=0)
				{
					
					while($linePrediagno=$resultatsPrediagno->fetch())
					{
						$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
						$resultsDiagno->execute(array(
						'iddiagno'=>$linePrediagno->id_predia
						));
						
						$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
						$comptDiagno=$resultsDiagno->rowCount();
						
						if($comptDiagno!=0)
						{
							$ligne=$resultsDiagno->fetch();
							
							$Predia[] = $ligne->nomdiagno;
						}else{
							$Predia[] = $linePrediagno->autrepredia;
						}
						
					}
				
				}
			
				$Postdia = array();
							
				if(isset ($_GET['idconsu']) AND $ligneConsultation->postdiagnostic !="")
				{
					$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
					
					$resultatsDiagnoPost->execute(array(
					'iddiagno'=>$ligneConsultation->postdiagnostic
					))or die( print_r($connexion->errorInfo()));
						
					$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
					{
						$Postdia[] = $ligneDiagnoPost->nomdiagno;
					}else{
						$Postdia[] = $ligneConsultation->postdiagnostic;
					}
					
				}
					
				$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
				
				$resultatsPostDiagno->execute(array(
				'id_consudia'=>$ligneConsult->id_consu
				
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
				$postdiaCount = $resultatsPostDiagno->rowCount();
				
				if($postdiaCount!=0)
				{
					
					while($linePostDiagno=$resultatsPostDiagno->fetch())
					{
						$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
						$resultsDiagno->execute(array(
						'iddiagno'=>$linePostDiagno->id_postdia
						));
						
						$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
						$comptDiagno=$resultsDiagno->rowCount();
						
						if($comptDiagno!=0)
						{
							$ligne=$resultsDiagno->fetch();
							
							$Postdia[] = $ligne->nomdiagno;
						}else{
							$Postdia[] = $linePostDiagno->autrepostdia;
						}
						
					}
				
				}
							
							
							if($DiagnoPostDone ==0)
							{	
								for($p=0;$p<sizeof($Predia);$p++)
								{
									echo '-'.$Predia[$p].'<br/>';
								}
							}else{
								for($p=0;$p<sizeof($Postdia);$p++)
								{
									echo '- '.$Postdia[$p].'<br/>';
								}
							}
							?>	
							</td>		
						</tr>
						<?php
							$compteur++;
						}
						?>		
					</tbody>
					</table>
					<?php
					}else{
					?>
						<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
							<thead>
								<tr>
									<th style="width:12%;text-align:center">No Medical Report for this search</th>
								</tr> 
							</thead> 
						</table> 
						
					<?php
					}
					?>
					</div>
				</div>
				<?php
				}else{
					/*
					if(isset($_GET['med']))
					{
						$dailydateperso = "";
						$docVisit="gnlPersoMedic";
						$idDoc=$_GET['med'];

						
						$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 ORDER BY c.id_consu');		
						$resultConsult->execute(array(
						'med'=>$idDoc
						));
						
						$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptConsult=$resultConsult->rowCount();
					

						if($comptConsult != 0)
						{
						?>
						
							<a href="doctor_report.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
						
								<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
									<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
								</button>
							
							</a>	
						
						<div style="overflow:auto;height:500px;background-color:none;">
							
							<div id="divPersoMedicReport" style="display:inline;">
									
								<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
								<thead> 
									<tr style="text-align:left">
										<th style="width:10%">N°</th>
										<th style="width:10%">Date</th>
										<th style="width:10%">s/n</th>
										<th style="width:10%">Full name</th>
										<th style="width:35%"><?php echo getString(113);?></th>
										<th style="width:18.333%"><?php echo getString(39);?></th>
										<th style="width:18.333%"><?php echo getString(98);?></th>
										<th style="width:18.333%"><?php echo getString(99);?></th>
									</tr> 
								</thead> 


								<tbody>
							<?php
								// $date='0000-00-00';
								$compteur=1;
								
									while($ligneConsult=$resultConsult->fetch())
									{
							?>
									<tr>
										<td style="text-align:left;">
										<?php
											echo $compteur;
										?>
										</td>
										
										<td style="text-align:left;">
										<?php
											echo $ligneConsult->dateconsu;
										?>
										</td>
										
										<td style="text-align:left;">
										<?php
											echo $ligneConsult->numero;
										?>
										</td>
										
										<td style="text-align:left;">
										<?php
											$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation ORDER BY c.id_consu DESC');
											$resultPatient->execute(array(
											'operation'=>$ligneConsult->numero
											));
											
											$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptFiche=$resultPatient->rowCount();
											
											if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
											{
												$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
												
												echo $fullname;
											}else{
												echo '';
											}
											
										?>
										</td>
										
										<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneConsult->id_typeconsult
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
											}else{								
												echo $lignePresta->nompresta.'</td>';
											}
										}
									
										echo '<td style="text-align:left;">';
										
										$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
										$resultMedConsult->execute(array(
										'med'=>$idDoc,
										'idMedConsu'=>$ligneConsult->id_consu
										));
										
										$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptMedConsult=$resultMedConsult->rowCount();
									
									
										if($comptMedConsult != 0)
										{
										?>
											<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
											

											<tbody>
										<?php
												while($ligneMedConsult=$resultMedConsult->fetch())
												{
										?>
												<tr style="text-align:center;">
													
													<td>
													<?php
													
													$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
													$resultPresta->execute(array(
														'prestaId'=>$ligneMedConsult->id_prestationConsu
													));
													
													$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

													$comptPresta=$resultPresta->rowCount();
													
													if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
													{
														if($lignePresta->namepresta!='')
														{
															echo $lignePresta->namepresta.'</td>';
														}else{								
															echo $lignePresta->nompresta.'</td>';
															
														}
													}
														echo $ligneMedConsult->autreConsu.'</td>';
													?>
												</tr>
										<?php
												}
										?>		
											</tbody>
											</table>
										<?php
										}
									
										echo '</td>';
										
										echo '<td>';
										
										$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
										$resultMedInf->execute(array(
										'med'=>$idDoc,					
										'idMedInf'=>$ligneConsult->id_consu
										));
										
										$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptMedInf=$resultMedInf->rowCount();
									
									
										if($comptMedInf != 0)
										{
										?>		
											<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
										
											<tbody>
										<?php
												while($ligneMedInf=$resultMedInf->fetch())
												{
										?>
												<tr style="text-align:center;">
													<td>
													<?php 
														
													$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
													$resultPresta->execute(array(
														'prestaId'=>$ligneMedInf->id_prestation
													));
													
													$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

													$comptPresta=$resultPresta->rowCount();
													
													if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
													{
														if($lignePresta->namepresta!='')
														{
															echo $lignePresta->namepresta.'</td>';
														}else{								
															echo $lignePresta->nompresta.'</td>';
														}
													}
													
														echo $ligneMedInf->autrePrestaM.'</td>';
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

										
										echo '</td>';
										
										echo '<td>';
										
										$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
										$resultMedLabo->execute(array(
										'med'=>$idDoc,					
										'idMedLabo'=>$ligneConsult->id_consu
										));
										
										$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

										$comptMedLabo=$resultMedLabo->rowCount();


										if($comptMedLabo != 0)
										{
										?>	
											<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
											
											<tbody>
										<?php
												while($ligneMedLabo=$resultMedLabo->fetch())
												{
										?>
												<tr style="text-align:center;">
													<td>
														<?php
														$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
														$resultPresta->execute(array(
															'prestaId'=>$ligneMedLabo->id_prestationExa
														));
														
														$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

														$comptPresta=$resultPresta->rowCount();
														
														if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
														{
															if($lignePresta->namepresta!='')
															{
																echo $lignePresta->namepresta.'</td>';
															}else{
																echo $lignePresta->nompresta.'</td>';
															}
														}
														
															echo $ligneMedLabo->autreExamen;
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
										</td>
									</tr>
									<?php
										$compteur++;
									}
									?>		
								</tbody>
								</table>

							</div>
							
						</div>
						<?php
						}else{
						?>
							<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
								<thead>
									<tr>
										<th style="width:12%;text-align:center">No Medical Report for this Doctor</th>
									</tr> 
								</thead> 
							</table> 
							
						<?php
						}
					
					}
					*/
				}
			}
		}
	}
	
	
	if(isset($_GET['inf']))
	{
		
		$idInf=$_GET['inf'];
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE i.id_u=:operation AND u.id_u=i.id_u');
		$result->execute(array(
		'operation'=>$_GET['inf']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codeInf=$ligne->codeinfirmier;
			$fullname=$ligne->nom_u.' '.$ligne->prenom_u;
			$sexe=$ligne->sexe;
			$adresse=$ligne->province.','.$ligne->district.','.$ligne->secteur;	
	?>
			<table style="margin:auto;">
				<tr>
					<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
						<b><h2>Individual Nurse Report</h2></b>
					</td>
				</tr>
			</table>
			
			<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:80%;">
				<tr>
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;">S/N : </span></span><?php echo $codeInf;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $fullname;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
						<?php
						if($ligne->sexe=="M")
						{
							$sexe = "Male";
						}else{
						
							if($ligne->sexe=="F")
							
							$sexe = "Female";
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
		
		<div id="selectdatePersoMedicReport">
		
			<form action="report.php?inf=<?php echo $_GET['inf'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmac=ok&selectPersoMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
				<table id="dmac" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
						</td>
						
						<td>
						<?php
						if(isset($_GET['selectPersoMedic']) OR isset($_GET['selectGnlMedic']))
						{
						?>
							<a href="report.php?inf=<?php echo $_GET['inf'];?>&audit=ok&report=ok" id="nomassurance" style="text-align:center;width:100px;" class="btn">Full Report</a>
						<?php
						}
						?>
						</td>
					</tr>
					
					<tr id="dmacligne" style="visibility:visible">
					
						<td id="dailymedicPerso" style="display:none;">Select date
							<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlymedicPerso" style="display:none">Select Month
						
							<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualymedicPerso" style="display:none">Select Year
						
							<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custommedicPerso" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
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
		
		if(isset($_GET['dmac']) OR isset($_GET['selectPersoMedic']))
		{
			$dailydateperso = " AND c.dateconsu != '0000-00-00'";
			$infVisit="gnlPersoMedic";
			
			if(isset($_POST['searchdailyPerso']))
			{
				if(isset($_POST['dailydatePerso']))
				{
					$dailydateperso = ' AND c.dateconsu=\''.$_POST['dailydatePerso'].'\'';
					
					$infVisit="dailyPersoMedic";
				
				}

			}
			
			if(isset($_POST['searchmonthlyPerso']))
			{
				if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
				{
					$ukwezi = $_POST['monthlydatePerso'];
					$umwaka = $_POST['monthlydatePersoYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND c.dateconsu>=\''.$umwaka.'-'.$ukwezi.'-1\' AND c.dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
					
					$infVisit="monthlyPersoMedic";
					
				}

			}
			
			if(isset($_POST['searchannualyPerso']))
			{
				if(isset($_POST['annualydatePerso']))
				{
					$year = $_POST['annualydatePerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydateperso = 'AND c.dateconsu>=\''.$year.'-01-01\' AND c.dateconsu<=\''.$year.'-12-31\'';
					
					$infVisit="annualyPersoMedic";
			
				}
			
			}
			
			if(isset($_POST['searchcustomPerso']))
			{
				if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
				{
					$debut = $_POST['customdatedebutPerso'];
					$fin = $_POST['customdatefinPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND c.dateconsu>=\''.$debut.'\' AND c.dateconsu<=\''.$fin.'\'';
					$infVisit="customPersoMedic";
			
				}

			}
			
				// echo $dailydateperso;
				// echo $ukwezi.' et '.$year;
				// echo $year;
				// echo $year;

		?>
		
			<div id="dmacMedicReport" style="display:inline">
			
			<?php
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c, med_inf mi WHERE mi.id_consuInf=c.id_consu AND mi.id_uI=:inf '.$dailydateperso.' ORDER BY c.id_consu DESC');		
			$resultConsult->execute(array(
			'inf'=>$idInf
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptConsult=$resultConsult->rowCount();
		
			// echo $comptConsult;
			
			if($comptConsult != 0)
			{
			?>
			
			<a href="nurse_report.php?inf=<?php echo $_GET['inf'];?>&dailydateperso=<?php echo $dailydateperso;?>&infVisit=<?php echo $infVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
				
				<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
					<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
				</button>
			
			</a>
				
			<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
			<thead> 
				<tr style="text-align:left">
					<th style="width:10%">N°</th>
					<th style="width:10%">Date de consultation</th>
					<th style="width:10%">s/n</th>
					<th style="width:10%">Full name</th>
					<th style="width:35%"><?php echo getString(113);?></th>
					<th style="width:18.333%"><?php echo getString(98);?></th>
				</tr> 
			</thead> 


			<tbody>
			<?php
			// $date='0000-00-00';
			$compteur=1;
			
				while($ligneConsult=$resultConsult->fetch())
				{
			?>
				<tr>
					<td style="text-align:left;">
					<?php
						echo $compteur;
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
						echo $ligneConsult->dateconsu;
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
						echo $ligneConsult->numero;
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
						$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
						$resultPatient->execute(array(
						'operation'=>$ligneConsult->numero
						));
						
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
						{
							$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
							
							echo $fullname;
						}else{
							echo '';
						}
						
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
					
					$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
					$result->execute(array(
					'operation'=>$ligneConsult->id_uM
					));
					$result->setFetchMode(PDO::FETCH_OBJ);
					
					
					if($ligne=$result->fetch())
					{
						$fullnameDoc=$ligne->nom_u.' '.$ligne->prenom_u;
					}
					
					
					$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
					$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
						
						if($lignePresta->namepresta!='')
						{
							echo $lignePresta->namepresta.' ( by Dr '.$fullnameDoc.'</td>';
						}else{								
							echo $lignePresta->nompresta.' ( by Dr '.$fullnameDoc.'</td>';
						}
					}
				
					echo '<td>';
					
					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_uI=:inf AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
					$resultMedInf->execute(array(
					'inf'=>$idInf,					
					'idMedInf'=>$ligneConsult->id_consu
					));
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptMedInf=$resultMedInf->rowCount();
				
				
					if($comptMedInf != 0)
					{
					?>		
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
					
						<tbody>
					<?php
							while($ligneMedInf=$resultMedInf->fetch())
							{
					?>
							<tr style="text-align:center;">
								<!--
								
								<td>
								<?php
									echo $ligneMedInf->datesoins;
								?>
								</td>
								
								-->
								
								<td>
								<?php 
									
								$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>';
									}
								}
								
									echo $ligneMedInf->autrePrestaM.'</td>';
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
					</td>
				</tr>
				<?php
					$compteur++;
				}
				?>		
			</tbody>
			</table>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center">No Medical Report for this search</th>
						</tr> 
					</thead> 
				</table> 
				
			<?php
			}
			?>
			</div>
		<?php
		}else{
		
			if(isset($_GET['inf']))
			{
				$dailydateperso = "";
				$infVisit="gnlPersoMedic";

				
				$resultConsult=$connexion->prepare('SELECT *FROM consultations c, med_inf mi WHERE mi.id_consuInf=c.id_consu AND mi.id_uI=:inf '.$dailydateperso.' ORDER BY c.id_consu DESC');		
				$resultConsult->execute(array(
				'inf'=>$idInf
				));
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptConsult=$resultConsult->rowCount();
			

				if($comptConsult != 0)
				{
				?>
				
				<a href="nurse_report.php?inf=<?php echo $_GET['inf'];?>&dailydateperso=<?php echo $dailydateperso;?>&infVisit=<?php echo $infVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
			
					<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
						<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
					</button>
				
				</a>	
		
				<div style="overflow:auto;height:500px;background-color:none;">
					
					<div id="divPersoMedicReport" style="display:inline;">
							
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
						<thead> 
							<tr style="text-align:left">
								<th style="width:10%">N°</th>
								<th style="width:10%">Date</th>
								<th style="width:10%">s/n</th>
								<th style="width:10%">Full name</th>
								<th style="width:35%"><?php echo getString(113);?></th>
								<th style="width:18.333%"><?php echo getString(98);?></th>
							</tr> 
						</thead> 


						<tbody>
					<?php
						// $date='0000-00-00';
						$compteur=1;
						
							while($ligneConsult=$resultConsult->fetch())
							{
					?>
							<tr>
								<td style="text-align:left;">
								<?php
									echo $compteur;
								?>
								</td>
								
								<td style="text-align:left;">
								<?php
									echo $ligneConsult->dateconsu;
								?>
								</td>
								
								<td style="text-align:left;">
								<?php
									echo $ligneConsult->numero;
								?>
								</td>
								
								<td style="text-align:left;">
								<?php
									$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
									$resultPatient->execute(array(
									'operation'=>$ligneConsult->numero
									));
									
									$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptFiche=$resultPatient->rowCount();
									
									if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
									{
										$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
										
										echo $fullname;
									}else{
										echo '';
									}
									
								?>
								</td>
								
								<td style="text-align:left;">
								<?php
								$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
								$result->execute(array(
								'operation'=>$ligneConsult->id_uM
								));
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								
								if($ligne=$result->fetch())
								{
									$fullnameDoc=$ligne->nom_u.' '.$ligne->prenom_u;
								}
								
								
								$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneConsult->id_typeconsult
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.' ( by Dr '.$fullnameDoc.' )</td>';
									}else{								
										echo $lignePresta->nompresta.' ( by Dr '.$fullnameDoc.' )</td>';
									}
								}
							
								echo '<td>';
								
								$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_uI=:inf AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
								$resultMedInf->execute(array(
								'inf'=>$idInf,					
								'idMedInf'=>$ligneConsult->id_consu
								));
								
								$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptMedInf=$resultMedInf->rowCount();
							
							
								if($comptMedInf != 0)
								{
								?>		
									<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
								
									<tbody>
								<?php
										while($ligneMedInf=$resultMedInf->fetch())
										{
								?>
										<tr style="text-align:center;">
											<!--
											
											<td>
											<?php
												echo $ligneMedInf->datesoins;
											?>
											</td>
											
											-->
											<td>
											<?php 
												
											$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
											$resultPresta->execute(array(
												'prestaId'=>$ligneMedInf->id_prestation
											));
											
											$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptPresta=$resultPresta->rowCount();
											
											if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
											{
												if($lignePresta->namepresta!='')
												{
													echo $lignePresta->namepresta.'</td>';
												}else{								
													echo $lignePresta->nompresta.'</td>';
												}
											}
											
												echo $ligneMedInf->autrePrestaM.'</td>';
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
								echo '</td>';
								
								?>
							</tr>
							<?php
								$compteur++;
							}
							?>		
						</tbody>
						</table>

					</div>
					
				</div>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Medical Report for this Nurse</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
			}
		}
	}
	
	
	if(isset($_GET['lab']))
	{
		
		$idLab=$_GET['lab'];
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE l.id_u=:operation AND u.id_u=l.id_u');
		$result->execute(array(
		'operation'=>$_GET['lab']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codelab=$ligne->codelabo;
			$fullname=$ligne->nom_u.' '.$ligne->prenom_u;
			$sexe=$ligne->sexe;
			$adresse=$ligne->province.','.$ligne->district.','.$ligne->secteur;	
	?>
			<table style="margin:auto;">
				<tr>
					<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
						<b><h2>Individual Labs Report</h2></b>
					</td>
				</tr>
			</table>
			
			<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:80%;">
				<tr>
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;">S/N : </span></span><?php echo $codelab;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $fullname;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
						<?php
						if($ligne->sexe=="M")
						{
							$sexe = "Male";
						}else{
						
							if($ligne->sexe=="F")
							
							$sexe = "Female";
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
		
		<div id="selectdatePersoMedicReport">
		
			<form action="report.php?lab=<?php echo $_GET['lab'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmac=ok&selectPersoMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
				<table id="dmac" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
						</td>
						
						<td>
						<?php
						if(isset($_GET['selectPersoMedic']) OR isset($_GET['selectGnlMedic']))
						{
						?>
							<a href="report.php?lab=<?php echo $_GET['lab'];?>&audit=ok&report=ok" id="nomassurance" style="text-align:center;width:100px;" class="btn">Full Report</a>
						<?php
						}
						?>
						</td>
					</tr>
					
					<tr id="dmacligne" style="visibility:visible">
					
						<td id="dailymedicPerso" style="display:none;">Select date
							<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlymedicPerso" style="display:none">Select Month
						
							<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualymedicPerso" style="display:none">Select Year
						
							<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custommedicPerso" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
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
		
		if(isset($_GET['dmac']) OR isset($_GET['selectPersoMedic']))
		{
			$dailydateperso = " AND c.dateconsu != '0000-00-00'";
			$labVisit="gnlPersoMedic";
			
			if(isset($_POST['searchdailyPerso']))
			{
				if(isset($_POST['dailydatePerso']))
				{
					$dailydateperso = ' AND c.dateconsu=\''.$_POST['dailydatePerso'].'\'';
					
					$labVisit="dailyPersoMedic";
				
				}

			}
			
			if(isset($_POST['searchmonthlyPerso']))
			{
				if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
				{
					$ukwezi = $_POST['monthlydatePerso'];
					$umwaka = $_POST['monthlydatePersoYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND c.dateconsu>=\''.$umwaka.'-'.$ukwezi.'-1\' AND c.dateconsu<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
					
					$labVisit="monthlyPersoMedic";
					
				}

			}
			
			if(isset($_POST['searchannualyPerso']))
			{
				if(isset($_POST['annualydatePerso']))
				{
					$year = $_POST['annualydatePerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydateperso = 'AND c.dateconsu>=\''.$year.'-01-01\' AND c.dateconsu<=\''.$year.'-12-31\'';
					
					$labVisit="annualyPersoMedic";
			
				}
			
			}
			
			if(isset($_POST['searchcustomPerso']))
			{
				if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
				{
					$debut = $_POST['customdatedebutPerso'];
					$fin = $_POST['customdatefinPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND c.dateconsu>=\''.$debut.'\' AND c.dateconsu<=\''.$fin.'\'';
					$labVisit="customPersoMedic";
			
				}

			}
			
				// echo $dailydateperso;
				// echo $ukwezi.' et '.$year;
				// echo $year;
				// echo $year;

		?>
		
			<div id="dmacMedicReport" style="display:inline">
			
			<?php
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c, med_labo ml WHERE ml.id_consuLabo=c.id_consu AND ml.id_uL=:lab '.$dailydateperso.' ORDER BY c.id_consu DESC');		
			$resultConsult->execute(array(
			'lab'=>$idLab
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptConsult=$resultConsult->rowCount();
		
			// echo $comptConsult;
			
			if($comptConsult != 0)
			{
			?>
			
			<a href="labs_report.php?lab=<?php echo $_GET['lab'];?>&dailydateperso=<?php echo $dailydateperso;?>&labVisit=<?php echo $labVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
				
				<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
					<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
				</button>
			
			</a>
				
			<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
			<thead> 
				<tr style="text-align:left">
					<th style="width:10%">N°</th>
					<th style="width:10%">Date de consultation</th>
					<th style="width:10%">s/n</th>
					<th style="width:10%">Full name</th>
					<th style="width:35%"><?php echo getString(113);?></th>
					<th style="width:18.333%"><?php echo getString(99);?></th>
				</tr> 
			</thead> 


			<tbody>
			<?php
			// $date='0000-00-00';
			$compteur=1;
			
				while($ligneConsult=$resultConsult->fetch())
				{
			?>
				<tr>
					<td style="text-align:left;">
					<?php
						echo $compteur;
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
						echo $ligneConsult->dateconsu;
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
						echo $ligneConsult->numero;
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
						$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
						$resultPatient->execute(array(
						'operation'=>$ligneConsult->numero
						));
						
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
						{
							$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
							
							echo $fullname;
						}else{
							echo '';
						}
						
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
					$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
					
					$result->execute(array(
					'operation'=>$ligneConsult->id_uM
					));
					$result->setFetchMode(PDO::FETCH_OBJ);
					
					
					if($ligne=$result->fetch())
					{
						$fullnameDoc=$ligne->nom_u.' '.$ligne->prenom_u;
					}
					
					
					$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
					$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
						
						if($lignePresta->namepresta!='')
						{
							echo $lignePresta->namepresta.' ( by Dr '.$fullnameDoc.' )</td>';
						}else{								
							echo $lignePresta->nompresta.' ( by Dr '.$fullnameDoc.' )</td>';
						}
					}
				
				
				
					echo '<td>';
					
					$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_uL=:lab AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
					
					$resultMedLabo->execute(array(
					'lab'=>$idLab,					
					'idMedLabo'=>$ligneConsult->id_consu
					));
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptMedLabo=$resultMedLabo->rowCount();
				
				
					if($comptMedLabo != 0)
					{
					?>		
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
					
						<tbody>
					<?php
							while($ligneMedLabo=$resultMedLabo->fetch())
							{
					?>
							<tr style="text-align:center;">
								<!--
								
								<td>
								<?php
									echo $ligneMedLabo->dateresultats;
								?>
								</td>
								
								-->
								
								<td>
								<?php 
									
								$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedLabo->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>';
									}else{								
										echo $lignePresta->nompresta.'</td>';
									}
								}
								
									echo $ligneMedLabo->autreExamen.'</td>';
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
					</td>
				</tr>
				<?php
					$compteur++;
				}
				?>		
			</tbody>
			</table>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center">No Medical Report for this search</th>
						</tr> 
					</thead> 
				</table> 
				
			<?php
			}
			?>
			</div>
		<?php
		}else{
		
			if(isset($_GET['lab']))
			{
				$dailydateperso = "";
				$labVisit="gnlPersoMedic";
		?>
	
			<?php
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c, med_labo ml WHERE ml.id_consuLabo=c.id_consu AND ml.id_uL=:lab '.$dailydateperso.' ORDER BY c.id_consu DESC');		

			$resultConsult->execute(array(
			'lab'=>$idLab
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptConsult=$resultConsult->rowCount();
		

			if($comptConsult != 0)
			{
			?>
			
				<a href="labs_report.php?lab=<?php echo $_GET['lab'];?>&dailydateperso=<?php echo $dailydateperso;?>&labVisit=<?php echo $labVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
			
					<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
						<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
					</button>
				
				</a>	
			
			<div style="overflow:auto;height:500px;background-color:none;">
				
				<div id="divPersoMedicReport" style="display:inline;">
						
					<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
					<thead> 
						<tr style="text-align:left">
							<th style="width:10%">N°</th>
							<th style="width:10%">Date</th>
							<th style="width:10%">s/n</th>
							<th style="width:10%">Full name</th>
							<th style="width:35%"><?php echo getString(113);?></th>
							<th style="width:18.333%"><?php echo getString(99);?></th>
						</tr> 
					</thead> 


					<tbody>
				<?php
					// $date='0000-00-00';
					$compteur=1;
					
						while($ligneConsult=$resultConsult->fetch())
						{
				?>
						<tr>
							<td style="text-align:left;">
							<?php
								echo $compteur;
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
								echo $ligneConsult->dateconsu;
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
								echo $ligneConsult->numero;
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
							$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								
								echo $fullname;
							}else{
								echo '';
							}
							
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
							$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
							
							$result->execute(array(
							'operation'=>$ligneConsult->id_uM
							));
							$result->setFetchMode(PDO::FETCH_OBJ);
							
							
							if($ligne=$result->fetch())
							{
								$fullnameDoc=$ligne->nom_u.' '.$ligne->prenom_u;
							}
							
							
							$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneConsult->id_typeconsult
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
							{
								
								if($lignePresta->namepresta!='')
								{
									echo $lignePresta->namepresta.' ( by Dr '.$fullnameDoc.' )</td>';
								}else{								
									echo $lignePresta->nompresta.' ( by Dr '.$fullnameDoc.' )</td>';
								}
							}
								
								
							echo '<td>';
							
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_uL=:lab AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							
							$resultMedLabo->execute(array(
							'lab'=>$idLab,					
							'idMedLabo'=>$ligneConsult->id_consu
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

							$comptMedLabo=$resultMedLabo->rowCount();


							if($comptMedLabo != 0)
							{
							?>		
								<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							
								<tbody>
							<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
							?>
									<tr style="text-align:center;">
										<!--
										
										<td>
										<?php
											echo $ligneMedLabo->dateresultats;
										?>
										</td>
										
										-->
										<td>
											<?php
											$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
											$resultPresta->execute(array(
												'prestaId'=>$ligneMedLabo->id_prestationExa
											));
											
											$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptPresta=$resultPresta->rowCount();
											
											if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
											{
												if($lignePresta->namepresta!='')
												{
													echo $lignePresta->namepresta.'</td>';
												}else{
													echo $lignePresta->nompresta.'</td>';
												}
											}
											
												echo $ligneMedLabo->autreExamen;
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
							echo '</td>';
							
							?>
						</tr>
						<?php
							$compteur++;
						}
						?>		
					</tbody>
					</table>

				</div>
				
			</div>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center">No Medical Report for this Laboratory Technician</th>
						</tr> 
					</thead>
					
				</table>
				
			<?php
			}
			?>

	<?php
			}
		}
	}
	
	if(isset($_GET['rec']))
	{
		
		$idRec=$_GET['rec'];
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE r.id_u=:operation AND u.id_u=r.id_u');
		$result->execute(array(
		'operation'=>$_GET['rec']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$coderec=$ligne->codereceptio;
			$fullname=$ligne->nom_u.' '.$ligne->prenom_u;
			$sexe=$ligne->sexe;
			$adresse=$ligne->province.','.$ligne->district.','.$ligne->secteur;	
	?>
			<table style="margin:auto;">
				<tr>
					<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
						<b><h2>Individual Receptionist Report</h2></b>
					</td>
				</tr>
			</table>
			
			<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:80%;">
				<tr>
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;">S/N : </span></span><?php echo $coderec;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $fullname;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
						<?php
						if($ligne->sexe=="M")
						{
							$sexe = "Male";
						}else{
						
							if($ligne->sexe=="F")
							
							$sexe = "Female";
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
		
		<div id="selectdatePersoMedicReport">
		
			<form action="report.php?rec=<?php echo $_GET['rec'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok&dmac=ok&selectPersoMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
				<table id="dmac" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
						</td>
						
						<td>
						<?php
						if(isset($_GET['selectPersoMedic']) OR isset($_GET['selectGnlMedic']))
						{
						?>
							<a href="report.php?rec=<?php echo $_GET['rec'];?>&audit=ok&report=ok" id="nomassurance" style="text-align:center;width:100px;" class="btn">Full Report</a>
						<?php
						}
						?>
						</td>
					</tr>
					
					<tr id="dmacligne" style="visibility:visible">
					
						<td id="dailymedicPerso" style="display:none;">Select date
							<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/>
						
							<button type="submit"  name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlymedicPerso" style="display:none">Select Month
						
							<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualymedicPerso" style="display:none">Select Year
						
							<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custommedicPerso" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
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
		
		if(isset($_GET['dmac']) OR isset($_GET['selectPersoMedic']))
		{
			$dailydateperso = " AND p.createdtimePa != '0000-00-00 00:00:00'";
			$recVisit="gnlPersoMedic";
			
			if(isset($_POST['searchdailyPerso']))
			{
				if(isset($_POST['dailydatePerso']))
				{
					$dailydateperso = 'AND createdtimePa LIKE \''.$_POST['dailydatePerso'].'%\'';
					
					$recVisit="dailyPersoMedic";
				
				}

			}
			
			if(isset($_POST['searchmonthlyPerso']))
			{
				if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
				{
					$ukwezi = $_POST['monthlydatePerso'];
					$umwaka = $_POST['monthlydatePersoYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND p.createdtimePa>=\''.$umwaka.'-'.$ukwezi.'-1\' AND p.createdtimePa<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
					
					$recVisit="monthlyPersoMedic";
					
					// echo $dailydateperso;
				}

			}
			
			if(isset($_POST['searchannualyPerso']))
			{
				if(isset($_POST['annualydatePerso']))
				{
					$year = $_POST['annualydatePerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydateperso = 'AND p.createdtimePa>=\''.$year.'-01-01\' AND p.createdtimePa<=\''.$year.'-12-31\'';
					
					$recVisit="annualyPersoMedic";
			
				}
			
			}
			
			if(isset($_POST['searchcustomPerso']))
			{
				if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
				{
					$debut = $_POST['customdatedebutPerso'];
					$fin = $_POST['customdatefinPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND p.createdtimePa>=\''.$debut.'\' AND p.createdtimePa<=\''.$fin.'\'';
					$recVisit="customPersoMedic";
			
				}

			}
			
				// echo $dailydateperso;
				// echo $ukwezi.' et '.$year;
				// echo $year;

		?>
		
			<div id="dmacMedicReport" style="display:inline">
			
			<?php
			
			$resultConsult=$connexion->prepare('SELECT *FROM patients p, utilisateurs u WHERE p.createdbyPa=:rec AND p.id_u=u.id_u '.$dailydateperso.' ORDER BY p.numero ASC');		
			$resultConsult->execute(array(
			'rec'=>$idRec
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptConsult=$resultConsult->rowCount();
		
			// echo $comptConsult;
			
			if($comptConsult != 0)
			{
			?>
			
			<a href="recep_report.php?rec=<?php echo $_GET['rec'];?>&dailydateperso=<?php echo $dailydateperso;?>&recVisit=<?php echo $recVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
				
				<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
					<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
				</button>
			
			</a>
				
			<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
			<thead> 
				<tr style="text-align:left">
					<th style="width:10%">N°</th>
					<th style="width:10%">s/n</th>
					<th style="width:10%">Full name</th>
					<th style="width:35%">Created Time</th>
				</tr> 
			</thead> 


			<tbody>
			<?php
			// $date='0000-00-00';
			$compteur=1;
			
				while($ligneConsult=$resultConsult->fetch())
				{
			?>
				<tr>
					<td style="text-align:left;">
					<?php
						echo $compteur;
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
						echo $ligneConsult->numero;
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
						$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
						$resultPatient->execute(array(
						'operation'=>$ligneConsult->numero
						));
						
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
						{
							$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
							$createdtime = $lignePatient->createdtimePa;
							
							echo $fullname;
					?>
					</td>
					
					<td style="text-align:left;">
					<?php
							echo $createdtime;
					?>
					
					<?php
						}else{
							echo '<td></td>';
							echo '<td></td>';
						}
					?>
				</tr>
				<?php
					$compteur++;
				}
				?>		
			</tbody>
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
			?>
			</div>
		<?php
		}else{
		
			if(isset($_GET['rec']))
			{
				$dailydateperso = "";
				$recVisit="gnlPersoMedic";
		?>
	
				<?php
				
				$resultConsult=$connexion->prepare('SELECT *FROM patients p, utilisateurs u WHERE p.createdbyPa=:rec AND u.id_u=p.id_u ORDER BY p.numero ASC');		
				$resultConsult->execute(array(
				'rec'=>$idRec
				));
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptConsult=$resultConsult->rowCount();
			
				// echo $comptConsult;
				
				if($comptConsult != 0)
				{
				?>
			
				<a href="recep_report.php?rec=<?php echo $_GET['rec'];?>&dailydateperso=<?php echo $dailydateperso;?>&recVisit=<?php echo $recVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
			
					<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
						<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
					</button>
				
				</a>	
			
				<div style="overflow:auto;height:500px;background-color:none;">
					
					<div id="divPersoMedicReport" style="display:inline;">
							
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
						<thead> 
							<tr style="text-align:left">
								<th style="width:10%">N°</th>
								<th style="width:10%">s/n</th>
								<th style="width:10%">Full name</th>
								<th style="width:35%">Created Time</th>
							</tr> 
						</thead> 


						<tbody>
					<?php
						// $date='0000-00-00';
						$compteur=1;
						
							while($ligneConsult=$resultConsult->fetch())
							{
					?>
							<tr>
								<td style="text-align:left;">
								<?php
									echo $compteur;
								?>
								</td>
								
								<td style="text-align:left;">
								<?php
									echo $ligneConsult->numero;
								?>
								</td>
								
								<td style="text-align:left;">
								<?php
									$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
									$resultPatient->execute(array(
									'operation'=>$ligneConsult->numero
									));
									
									$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptFiche=$resultPatient->rowCount();
									
									if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
									{
										$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
										$createdtime = $lignePatient->createdtimePa;
										
										echo $fullname;
								?>
								</td>
								
								<td style="text-align:left;">
								<?php
										echo $createdtime;
								?>
								
								<?php
									}else{
										echo '<td></td>';
										echo '<td></td>';
									}
								?>
							</tr>
							<?php
								$compteur++;
							}
							?>		
						</tbody>
						</table>

					</div>
					
				</div>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Report for this Receptionist</th>
							</tr> 
						</thead>
						
					</table>
					
				<?php
				}
				?>

	<?php
			}
		}
	}
	?>
	
</div>


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

<?php
	
	}else{
		echo '<script language="javascript"> alert("Vous avez été désactivé!!\n Demander à l\'administrateur de vous activer");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}
	
}else{header('Location:index.php');}



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
	
</body>

</html>