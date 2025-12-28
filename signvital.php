<?php
session_start();

include("connectLangues.php");
include("connect.php");


	$annee = date('Y').'-'.date('m').'-'.date('d');

if(isset($_GET['idmedRad']) AND isset($_GET['checkradio']))
{
	if(isset($_GET['english']))
	{
		$langue="&english=english";				
	
	}else{
		if(isset($_GET['francais']))
		{
			$langue="&francais=francais";
			
		}else{
			$langue="";
		}
	}
	
	$num=$_GET['num'];
	$idmedRad=$_GET['idmedRad'];
	$radiofait=1;
	 
	$resultIdMedRadio=$connexion->prepare("SELECT * FROM med_radio WHERE id_medradio=:idmedRad")or die( print_r($connexion->errorInfo()));
	$resultIdMedRadio->execute(array(
	'idmedRad'=>$idmedRad
	))or die( print_r($connexion->errorInfo()));
			
	$comptIdmedradio = $resultIdMedRadio->rowCount();
	
	if( $comptIdmedradio != 0)
	{
		$resultMeRadio=$connexion->prepare("UPDATE med_radio SET radiofait = :radiofait, dateradio=:dateradio, id_uX=:id_uX WHERE id_medradio =:idmedRad");
		$resultMeRadio->execute(array(
		'idmedRad'=>$idmedRad,
		'radiofait'=>$radiofait,
		'dateradio'=>$annee,
		'id_uX'=>$_SESSION['id']
		
		))or die( print_r($connexion->errorInfo()));
	
		
		// echo '<script type="text/javascript"> alert("Results sent!");</script>';
	
		echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&radioPa=ok'.$langue.'"</script>';
		
	}
}

if(isset($_GET['idmedRad']) AND isset($_GET['checkeditradio']))
{
	if(isset($_GET['english']))
	{
		$langue="&english=english";				
	
	}else{
		if(isset($_GET['francais']))
		{
			$langue="&francais=francais";
			
		}else{
			$langue="";
		}
	}
	
	$num=$_GET['num'];
	$idmedRad=$_GET['idmedRad'];
	$radiofait=0;
	 
	$resultIdMedRadio=$connexion->prepare("SELECT * FROM med_radio WHERE id_medradio=:idmedRad")or die( print_r($connexion->errorInfo()));
	$resultIdMedRadio->execute(array(
	'idmedRad'=>$idmedRad
	))or die( print_r($connexion->errorInfo()));
			
	$comptIdmedradio = $resultIdMedRadio->rowCount();
	
	if( $comptIdmedradio != 0)
	{
		$resultMeRadio=$connexion->prepare("UPDATE med_radio SET radiofait = :radiofait, dateradio=:dateradio, id_uX=:id_uX WHERE id_medradio =:idmedRad");
		$resultMeRadio->execute(array(
		'idmedRad'=>$idmedRad,
		'radiofait'=>$radiofait,
		'dateradio'=>"0000-00-00",
		'id_uX'=>NULL
		
		))or die( print_r($connexion->errorInfo()));
	
		
		// echo '<script type="text/javascript"> alert("Results sent!");</script>';
	
		echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&radioPa=ok'.$langue.'"</script>';
		
	}
}


if(isset($_GET['deleteconsu']))
{
	
	$idconsu=$_GET['deleteconsu'];
	$nomconsu=$_GET['nomconsu'];
	 
	$deleteConsu=$connexion->prepare('DELETE FROM consultations WHERE id_consu=:idConsu');
		
	$deleteConsu->execute(array(
	'idConsu'=>$idconsu
	
	))or die($deleteConsu->errorInfo());
	
		
	echo '<script type="text/javascript"> alert("Consultation "'.$nomconsu.'" deleted");</script>';
	
	echo '<script text="text/javascript">document.location.href="patients1.php"</script>';
	
}


if(isset($_GET['deleteIDconsu']))
{
	
	$idconsu=$_GET['deleteIDconsu'];
	
	
	/*-----------Delete From Nursery------------*/
	
	$getIdInf=$connexion->prepare('SELECT *FROM med_inf WHERE id_consuInf=:id_medI');
	$getIdInf->execute(array(
	'id_medI'=>$idconsu
	));
	
	$getIdInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
		
	$compteurInf=$getIdInf->rowCount();
		
	if($compteurInf!=0)
	{	

		$deleteInf=$connexion->prepare('DELETE FROM med_inf WHERE id_consuInf=:id_medI');
			
		$deleteInf->execute(array(
		'id_medI'=>$idconsu
		
		))or die($deleteInf->errorInfo());
	}
	
	
	/*-----------Delete From Labs------------*/
	
	$getIdLabo=$connexion->prepare('SELECT *FROM med_labo WHERE id_consuLabo=:id_medL');
	$getIdLabo->execute(array(
	'id_medL'=>$idconsu
	));
	
	$getIdLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurLabo=$getIdLabo->rowCount();

	if($compteurLabo!=0)
	{
		$deleteLabo=$connexion->prepare('DELETE FROM med_labo WHERE id_consuLabo=:id_medL');
			
		$deleteLabo->execute(array(
		'id_medL'=>$idconsu
		
		))or die($deleteLabo->errorInfo());
	}
	
	
	/*-----------Delete From Med_Consu------------*/
	
	$getIdMedConsu=$connexion->prepare('SELECT *FROM med_consult WHERE id_consuMed=:id_medC');
	$getIdMedConsu->execute(array(
	'id_medC'=>$idconsu
	));
	
	$getIdMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurMedConsu=$getIdMedConsu->rowCount();
		
	if($compteurMedConsu!=0)
	{
		$deleteMedConsu=$connexion->prepare('DELETE FROM med_consult WHERE id_consuMed=:id_medC');
		
		$deleteMedConsu->execute(array(
		'id_medC'=>$idconsu
		
		))or die($deleteMedConsu->errorInfo());
	
	}

	 
	$deleteConsu=$connexion->prepare('DELETE FROM consultations WHERE id_consu=:id_medConsu');
		
	$deleteConsu->execute(array(
	'id_medConsu'=>$idconsu
	
	))or die($deleteConsu->errorInfo());
	
}



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
		$assuId=$ligne->id_assurance;
		$site=$_GET['num'];
		
		
		$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($ligne->date_naissance)));
		$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
		$interval = $datetime1->diff($datetime2);
		
		if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
		{
			$an = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
		}
	
	}
	$result->closeCursor();
	
	
/* 
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
	 */
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
	<script src="myQuery.js"></script>
	
	
	
<script type="text/javascript">
 
function controlFormResult(theForm){
	var rapport="";
	 
	rapport +=controlExaResult(theForm.examen,theForm.result);

		if (rapport != "") {
		alert("<?php echo getString(111)?> :\n" + rapport);
					return false;
		 }
}


function controlExaResult(fld1,fld2){
	var erreur="";

	if(fld1.value.trim()=="" && fld2.value.trim()=="")
	{
		erreur="The Results\n";
		fld1.style.background="rgba(0,255,0,0.3)";
		fld2.style.background="rgba(0,255,0,0.3)";
	}	
	return erreur;
}

</script>
	
	
<script type="text/javascript">
 
function controlFormResultat(theForm){
	var rapport="";
	 
	rapport +=controlExaResult(theForm.examen,theForm.result);

		if (rapport != "") {
		alert("<?php echo getString(111)?> :\n" + rapport);
					return false;
		 }
}


function controlExaResult(fld1,fld2){
	var erreur="";

	if(fld1.value.trim()=="" && fld2.value.trim()=="")
	{
		erreur="The Results\n";
		fld1.style.background="rgba(0,255,0,0.3)";
		fld2.style.background="rgba(0,255,0,0.3)";
	}	
	return erreur;
}

</script>

</head>

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
					<form method="post" action="signvital.php?signvital=ok&<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}?><?php if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="signvital.php?signvital=ok&english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="signvital.php?signvital=ok&francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(29);?></a>
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
	</div>
<?php
}
?>

<?php
if(isset($_SESSION['infhosp']))
{
	$infhosp=$_SESSION['infhosp'];
	
	if($infhosp!=0)
	{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1_hosp.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Hospitalisation';?> 
		</a>

		<a href="signvital.php?signvital=ok&<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; margin-left: 10px; padding:10px 40px;">
			<?php echo 'Sign Vital';?> 
		</a>

	</div>
<?php
	}
}
?>

<?php
if(isset($_SESSION['codeM']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="report.php?med=<?php echo $_SESSION['id'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="font-size:20px;height:40px; padding:10px 40px;"><?php echo getString(94);?></a>
	</div>
<?php
}
?>

<?php
if(isset($_SESSION['codeL']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients_laboreport.php?laboreport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Reports';?>
		</a>

	</div>
	
<?php
}
?>

<?php
/* if(isset($_SESSION['codeC']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="report.php?coordi=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Reports';?>
		</a>

	</div>
	
<?php
} */
?>

<div class="account-container" style="width:90%; text-align:center;">

<?php

$id=$_SESSION['id'];

$sqlD=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");
$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");
$sqlX=$connexion->query("SELECT *FROM radiologues x WHERE x.id_u='$id'");

if(isset($_GET['receptioniste']))
{
	$sqlR=$connexion->query("SELECT *FROM receptionistes r WHERE r.id_u='$id'");
}else{
	$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");
}
$sqlA=$connexion->query("SELECT *FROM auditors a WHERE a.id_u='$id'");
$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");

$comptidD=$sqlD->rowCount();
$comptidI=$sqlI->rowCount();
$comptidL=$sqlL->rowCount();
$comptidX=$sqlX->rowCount();

if(isset($_GET['receptioniste']))
{
	$comptidR=$sqlR->rowCount();
	$comptidC=0;
}else{
	$comptidC=$sqlC->rowCount();
	$comptidR=0;
}

$comptidA=$sqlA->rowCount();
$comptidM=$sqlM->rowCount();

// echo $_SESSION[''];

if($comptidM!=0)
{
?>

<div id='cssmenu' style="text-align:center">

<ul>
	<li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="<?php echo getString(48);?>"><i class="fa fa-users fa-lg fa-fw"></i> <?php echo getString(48);?></a></li>
	
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

</ul>	
	
	<div style="display:none;" id="divItem">

		<a href="items.php<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn btn-success btn-large" name="item" id="item">Add item</a>
		
		<a href="items.php?showitems=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="ShowItem" class="btn btn-success btn-large">Show items</a>


	</div>


		<div style="display:none;" id="divListe" align="center">

			<a href="medecins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(19);?></a>
			
			<a href="infirmiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(21);?></a>
			
			<a href="laborantins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(22);?></a>
			
			<a href="radiologues1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Radiologue';?></a>
				
			<a href="receptionistes1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(40);?></a>
			
			<a href="caissiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(23);?></a>
			
			<!--
			<a href="auditeurs1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(149);?></a>
			
			<a href="comptables1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(150);?></a>
			-->
			
			<a href="coordinateurs1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(179);?></a>
			
		</div>
</div>
<?php
}

if($comptidD!=0 or $comptidI!=0 or $comptidL!=0 or $comptidX!=0 or $comptidR!=0 or $comptidC!=0 or $comptidA!=0)
{
?>
<div id='cssmenu' style="text-align:center">

<ul style="margin-top:20px;background:none;border:none;">

	<?php
	if(isset($_SESSION['codeR']) AND isset($_SESSION['codeCash']))
	{
		if(!isset($_GET['caissier']))
		{
	?>
		<li style="width:50%;"><a href="utilisateurs.php?iduser=<?php echo $id;?>&receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="<?php echo getString(88);?>"><i class="fa fa-plus-circle fa-lg fa-fw"></i> <?php echo getString(88);?></a></li>
		<?php
		}else{
		?>
			<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?>&caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
	<?php
		}
	}else{
		if(isset($_SESSION['codeR']) AND !isset($_SESSION['codeCash']) AND !isset($_SESSION['codeL']))
		{
	?>
			<li style="width:50%;"><a href="utilisateurs.php?iduser=<?php echo $id;?>&receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="<?php echo getString(88);?>"><i class="fa fa-plus-circle fa-lg fa-fw"></i> <?php echo getString(88);?></a></li>
		<?php
		}else{
			if(isset($_SESSION['codeCash'])AND !isset($_SESSION['codeR']) )
			{
		?>
				<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?>&caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
			<?php
			}else{

				if(isset($_SESSION['codeA']))
				{
			?>
					<li style="width:50%;"><a href="report.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Report"><i class="fa fa-file fa-lg fa-fw"></i> <?php echo getString(88);?></a></li>
			<?php
				}else{
				
					if(isset($_SESSION['codeM']))
					{
						if(!isset($_GET['all']))
						{
			?>
							<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&all=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Liste des patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> <?php echo getString(92);?></a></li>
									
						<?php
						}else{
						?>
							<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Voir patients à consulter"><i class="fa fa-wheelchair fa-lg fa-fw"></i> <?php echo getString(217);?></a></li>
									
					<?php
						}
						
					}else{
					?>
							<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
	<?php
					}
				}
			}
		}
	}
	?>
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

<ul style="margin-top:20px; background:none;border:none;">

</ul>
	
	<div style="display:none; margin-bottom:20px;" id="divMenuMsg">

		<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
		
		 <?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
		
		<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

	</div>
	
</div>


<?php 
}

if(!isset($_GET['num']))
{
?>
			<h2><?php echo getString(20);?>s</h2>

<form class="ajax" action="search.php" method="get">
	<p>
		<?php
		if($comptidA != 0)
		{
		?>
			<a href="report.php?<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="float:left; height:30px; margin-top:20px;"><i class="fa fa-hand-o-left fa-lg fa-fw"></i> <?php echo getString(151);?></a>
		<?php
		}
		?>
		<table align="center">
			<tr>
				<td>
					<label for="q"><?php echo getString(80);?></label>
					<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
				</td>
				
				<td>
					<label for="r"><?php echo getString(223);?></label>
					<input type="text" name="r" id="r" onclick="ShowSearch('byri')"/>
				</td>
				
				<td>
					<label for="s"><?php echo getString(91);?></label>
					<input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
				</td>
			</tr>
		</table>
		
	</p>
</form>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsRI"></div>

<script type="text/javascript">
$(document).ready( function() {
  // détection de la saisie dans le champ de recherche
  $('#q').keyup( function(){
    $field = $(this);
    $('#results').html(''); // on vide les resultats
    $('#ajax-loader').remove(); // on retire le loader
 
    // on commence à traiter à partir du 2ème caractère saisie
    if( $field.val().length > 0 )
    {
      // on envoie la valeur recherché en GET au fichier de traitement
      $.ajax({
  	type : 'GET', // envoi des données en GET ou POST
	url : 'traitement_signvital.php?name=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
	data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
	beforeSend : function() { // traitements JS à faire AVANT l'envoi
		$field.after('<img src="images/loader_30x30.gif" style="margin:5px" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
	},
	success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
		$('#ajax-loader').remove(); // on enleve le loader
		$('#results').html(data); // affichage des résultats dans le bloc
	}
      });
    }		
  });
});

</script>

<script type="text/javascript">
$(document).ready( function() {
  // détection de la saisie dans le champ de recherche
  $('#s').keyup( function(){
    $field = $(this);
    $('#resultsSN').html(''); // on vide les resultats
    $('#ajax-loader').remove(); // on retire le loader
 
    // on commence à traiter à partir du 2ème caractère saisie
    if( $field.val().length > 0 )
    {
      // on envoie la valeur recherché en GET au fichier de traitement
      $.ajax({
  	type : 'GET', // envoi des données en GET ou POST
	url : 'traitement_signvital.php?sn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
	data : 's='+$(this).val() , // données à envoyer en  GET ou POST
	beforeSend : function() { // traitements JS à faire AVANT l'envoi
		$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
	},
	success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
		$('#ajax-loader').remove(); // on enleve le loader
		$('#resultsSN').html(data); // affichage des résultats dans le bloc
	}
      });
    }		
  });
});

</script>

<script type="text/javascript">
$(document).ready( function() {
  // détection de la saisie dans le champ de recherche
  $('#r').keyup( function(){
    $field = $(this);
    $('#resultsRI').html(''); // on vide les resultats
    $('#ajax-loader').remove(); // on retire le loader
 
    // on commence à traiter à partir du 2ème caractère saisie
    if( $field.val().length > 0 )
    {
      // on envoie la valeur recherché en GET au fichier de traitement
      $.ajax({
  	type : 'GET', // envoi des données en GET ou POST
	url : 'traitement_signvital.php?ri=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
	data : 'r='+$(this).val() , // données à envoyer en  GET ou POST
	beforeSend : function() { // traitements JS à faire AVANT l'envoi
		$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
	},
	success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
		$('#ajax-loader').remove(); // on enleve le loader
		$('#resultsRI').html(data); // affichage des résultats dans le bloc
	}
      });
    }		
  });
});

</script>

	<?php
    if(isset($_GET['divPa']))
	{
		/*-----------------Requête pour Doctor--------------*/
		
		if($comptidD!=0)
		{
			$resultatsD=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND u.full_name LIKE \'%'.$_GET['fullname'].'%\' ');
			/* $resultatsD->execute(array(
			'idPa'=>$_GET['iduti']	
			))or die( print_r($connexion->errorInfo()));
		 */
			
			$resultatsD->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptPaD=$resultatsD->rowCount();
		}
		
		/*-----------------Requête pour Nurse--------------*/
		
		if($comptidI!=0)
		{
			$resultatsI=$connexion->query('SELECT *FROM utilisateurs u, patients p,consultations c WHERE c.dateconsu="'.$annee.'" AND c.numero=p.numero AND u.id_u=p.id_u AND u.full_name LIKE \'%'.$_GET['fullname'].'%\' ');
			/* $resultatsI->execute(array(
			'idPa'=>$_GET['iduti']	
			))or die( print_r($connexion->errorInfo()));
		 */
			// SELECT *FROM utilisateurs u, patients p, med_inf mi WHERE u.id_u=p.id_u AND p.id_u=:idPa AND u.id_u=:idPa AND mi.numero=p.numero AND  (mi.id_prestation !='' OR mi.autrePrestaM !='') AND mi.soinsfait=0 GROUP BY p.numero ORDER BY u.nom_u
			
			$resultatsI->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptPaI=$resultatsI->rowCount();
			
		}
		
		/*-----------------Requête pour Labs--------------*/
		
		if($comptidL!=0)
		{
			
			$resultatsL=$connexion->prepare("SELECT *FROM utilisateurs u, patients p, med_labo ml WHERE u.id_u=p.id_u AND ml.numero=p.numero  AND (ml.id_prestationExa !=0 OR ml.autreExamen !='') AND u.full_name=:fullname GROUP BY p.numero");
			$resultatsL->execute(array(
			'fullname'=>$_GET['fullname']	
			))or die( print_r($connexion->errorInfo()));
			
			$resultatsL->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptPaL=$resultatsL->rowCount();
		}
		
		/*-----------------Requête pour Radio--------------*/
		
		if($comptidX!=0)
		{
			$resultatsX=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND u.full_name=:fullname');
			$resultatsX->execute(array(
			'idPa'=>$_GET['iduti']	
			))or die( print_r($connexion->errorInfo()));
		 
			
			$resultatsX->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptPaX=$resultatsX->rowCount();
		}
		
		/*-----------Requête pour receptionistes-----------*/
		
		if($comptidR!=0)
		{
			$resultatsR=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c WHERE c.dateconsu="'.$annee.'" AND u.id_u=p.id_u AND p.numero=c.numero AND c.done=0 AND u.full_name=:fullname');
			$resultatsR->execute(array(
			'fullname'=>$_GET['fullname']	
			))or die( print_r($connexion->errorInfo()));
		

			$resultatsR->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptPaR=$resultatsR->rowCount();
			
		}
		
		/*-----------Requête pour auditeur-----------*/
		
		if($comptidA!=0)
		{
			$resultatsA=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND u.id_u=:idPa');
			$resultatsA->execute(array(
			'idPa'=>$_GET['iduti']	
			))or die( print_r($connexion->errorInfo()));
			
			$resultatsA->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptPaA=$resultatsA->rowCount();
			
		}

		/*-----------Requête pour caissier-----------*/
		
		if($comptidC!=0)
		{
			$resultatsC=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.id_factureConsult IS NULL AND u.full_name =:fullname AND c.dateconsu!="0000-00-00" ORDER BY u.nom_u');
			$resultatsC->execute(array(
			'fullname'=>$_GET['fullname']	
			))or die( print_r($connexion->errorInfo()));
			
			$resultatsC->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptPaC=$resultatsC->rowCount();
			
			$resultsC=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.dateconsu!="0000-00-00" AND c.id_factureConsult IS NOT NULL AND u.full_name =:fullname ORDER BY u.nom_u');
			$resultsC->execute(array(
			'fullname'=>$_GET['fullname']	
			))or die( print_r($connexion->errorInfo()));
			
			$resultsC->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comPaC=$resultsC->rowCount();
			
		}

		/*-----------Requête pour coordinateur-----------*/
		
		if($comptidM!=0)
		{
			$resultatsM=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND u.full_name =:fullname');
			$resultatsM->execute(array(
			'fullname'=>$_GET['fullname']	
			))or die( print_r($connexion->errorInfo()));
		

			$resultatsM->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptPaM=$resultatsM->rowCount();
			
		}
		
	?>
	
	<div style="margin-top:20px;">
	<?php	
	if($comptidI!=0)
	{
		if($comptPaI!=0)
		{
	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			<thead>
				<tr>
					<th>S/N</th>
					<th><?php echo getString(222);?></th>
					<th><?php echo getString(89);?></th>
					<th><?php echo 'Assurance';?></th>
					<th><?php echo 'Gender';?></th>
					<th><?php echo getString(8);?></th>
					<th><?php echo getString(14);?></th>
					<th><?php echo getString(15);?></th>
					<th><?php echo getString(33);?></th>
					<th><?php echo getString(125);?></th>
				</tr> 
			</thead>
			
			<tbody> 
			
			<span style="position:relative; font-size:250%;"><?php echo getString(60);?></span>
			
			<br/><br/>

<?php

			while($ligneI=$resultatsI->fetch())//on recupere la liste des éléments
			{
?>
				<tr style="text-align:center;">
					<td><?php echo $ligneI->numero;?></td>
					<td><?php echo $ligneI->reference_id;?></td>
					<td><?php echo $ligneI->full_name;?></td>
					<td>
						<?php
					 	   $getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
							$getAssuConsu->execute(array(
							'idassu'=>$ligneI->id_assurance
							));
							
							$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

							if($ligneNomAssu=$getAssuConsu->fetch())
							{
								echo strtoupper($ligneNomAssu->nomassurance);
							}
						 ?>
						
					</td>
					<td>
					<?php 
					if($ligneI->sexe=="M")
					{
						echo getString(12);
					}else{
						if($ligneI->sexe=="F")
						echo getString(13);
					}
					?>
					</td>
					<td><?php echo $ligneI->date_naissance;?></td>
					<td>
					<?php
							
						$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
						$resultAdresse->execute(array(
						'idProv'=>$ligneI->province,
						'idDist'=>$ligneI->district,
						'idSect'=>$ligneI->secteur
						));
								
						$resultAdresse->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptAdress=$resultAdresse->rowCount();
						
						if($ligneAdresse=$resultAdresse->fetch())
						{
							if($ligneAdresse->id_province == $ligneI->province)
							{
								$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
								
								echo $adresse;
							}
						}else{
							echo '';
						}

					?>
					</td>
					<td><?php echo $ligneI->telephone;?></td>
					<td><?php echo $ligneI->profession;?></td>
					<td>
						<!-- <?php
							$getIdMedConsub = $connexion->prepare('SELECT * FROM consultations')
						?> -->
						<a class="btn" href="consultations.php?iduti=<?php echo $_SESSION['id'];?>&idconsu=<?php echo $ligneI->id_consu;?>&num=<?php echo $ligneI->numero;?>&id_uM=<?php echo $ligneI->id_uM;?>&consu=ok&receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
						<i class="fa fa-stethoscope fa-lg fa-fw "></i> <?php echo 'Sign Vital' ?>
						</a>
					</td>
				</tr>
<?php
			}
			$resultatsI->closeCursor();
					
?>
			</tbody> 
	
		</table>
	<?php
		}else{
	?>
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
				<thead> 
					<tr>
						<th><?php echo getString(152).' ';?>
						<?php 
							echo $_GET['fullname'];
						?>
						<?php echo " ne possède pas de soins à faire";?></th>
					</tr>
				</thead> 
			<table>
	<?php
		}
	}
}
}

if(!isset($_GET['divPa']) )
{

	if(!isset($_GET['showresult']))
	{

		try
		{
		$annee = date('Y').'-'.date('m').'-'.date('d');

			if($comptidA != 0)
			{

				function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
				{
					$pagination = '';
					$link = preg_replace('`%([^d])`', '%%$1', $link);
					if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
					if ( $nb_pages > 1 ) {
				 
						// Lien Précédent
						if ( $current_page > 1 )
						{
							
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&report=ok&english='.$_GET['english'].'" title="Previous page" style="border-radius: 24px 4px 4px 14px;">Prev</a>';
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&report=ok&francais='.$_GET['francais'].'" title="Previous page" style="border-radius: 24px 4px 4px 14px;">Prev</a>';
								}else{
									$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&report=ok" title="Previous page" style="border-radius: 24px 4px 4px 14px;">Prev</a>';				
								}
							}
						
						}else{
							$pagination .= '';
						}
						// Lien(s) début
						for ( $i=1 ; $i<=$firstlast ; $i++ ) 
						{
							$pagination .= ' ';
							
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok&english='.$_GET['english'].'">'.$i.'</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok&francais='.$_GET['francais'].'">'.$i.'</a>';
								}else{
									
									$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok">'.$i.'</a>';				
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
									$pagination .= '<a href="'.sprintf($link, $i).'&report=ok&english='.$_GET['english'].'">'.$i.'</a>';
								
								}else{
									if(isset($_GET['francais']))
									{
										// echo '&francais='.$_GET['francais'];
										$pagination .= '<a href="'.sprintf($link, $i).'&report=ok&francais='.$_GET['francais'].'">'.$i.'</a>';
									}else{
										$pagination .= '<a href="'.sprintf($link, $i).'&report=ok">'.$i.'</a>';				
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
								// echo '&english='.$_GET['english'];	
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok&english='.$_GET['english'].'">'.$i.'</a>';
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];	
									$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok&francais='.$_GET['francais'].'">'.$i.'</a>';
								}else{
									$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok">'.$i.'</a>';				
								}
							}
						}
				 
						// Lien suivant
						if ( $current_page < $nb_pages )
						{
							
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&report=ok&english='.$_GET['english'].'" title="Next page" style="border-radius: 4px 24px 14px 4px;">Next</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&report=ok&francais='.$_GET['francais'].'" title="Next page" style="border-radius: 4px 24px 14px 4px;">Next</a>';
							
								}else{
									$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&report=ok" title="Next page" style="border-radius: 4px 24px 14px 4px;">Next</a>';
											
								}
							}
						}else{
							$pagination .= '';
						}
					}
					return $pagination;
				}
				
			}else{
							
				function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
				{
					
					if(isset($_GET['receptioniste']))
					{
						$caissrecep= '&receptioniste='.$_GET['receptioniste'];
					}else{
						if(isset($_GET['caissier']))
						{
							$caissrecep= '&caissier='.$_GET['caissier'];
						}else{
							$caissrecep='';
						}
					}
				
					if(isset($_GET['all']))
					{
						$all= '&all=ok';
					}else{
						$all='';
					}
				
					$pagination = '';
					$link = preg_replace('`%([^d])`', '%%$1', $link);
					if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
					if ( $nb_pages > 1 ) {
				 
						// Lien Précédent
						if ( $current_page > 1 )
						{
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&english='.$_GET['english'].''.$caissrecep.''.$all.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];									
									$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&francais='.$_GET['francais'].''.$caissrecep.''.$all.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

								}else{
									
									$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).''.$caissrecep.''.$all.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

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
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.''.$all.'">'.$i.'</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.''.$all.'">'.$i.'</a>';
							
								}else{
									
									$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$caissrecep.''.$all.'">'.$i.'</a>';
							
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
									$pagination .= '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.''.$all.'">'.$i.'</a>';
								
								}else{
									if(isset($_GET['francais']))
									{
										// echo '&francais='.$_GET['francais'];
										$pagination .= '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.''.$all.'">'.$i.'</a>';
									}else{
										
										$pagination .= '<a href="'.sprintf($link, $i).''.$caissrecep.''.$all.'">'.$i.'</a>';				
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
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.''.$all.'">'.$i.'</a>';
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.''.$all.'">'.$i.'</a>';	
									
								}else{
									
									$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$caissrecep.''.$all.'">'.$i.'</a>';				
								}
							}
							
						}
				 
						// Lien suivant
						if ( $current_page < $nb_pages )
						{
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&english='.$_GET['english'].''.$caissrecep.''.$all.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
							
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									
									$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&francais='.$_GET['francais'].''.$caissrecep.''.$all.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
							
								}else{
									$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).''.$caissrecep.''.$all.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
											
								}
							}
							
						}else{
							$pagination .= '';
						}
					}
					return $pagination;
				}
			}
				
			// Numero de page (1 par défaut)
				if( isset($_GET['page']) && is_numeric($_GET['page']) )
						$page = $_GET['page'];
					else
						$page = 1;
					 
					// Nombre d'info par page
					$pagination =10;
					 
					// Numero du 1er enregistrement à lire
					$limit_start = ($page - 1) * $pagination;


			
				/*-----------Requête pour infirmier-----------*/
					
				/* if($comptidI!=0)
				{
					// -----------Soins à executer---------------
					
					if(isset($_GET['soinsPa']))
					{
						$resultatsInfPa=$connexion->prepare("SELECT *FROM med_inf mf,utilisateurs u, patients p WHERE u.id_u=p.id_u AND mf.numero=p.numero AND (mf.id_prestation !='' OR mf.autrePrestaM !='') AND mf.soinsfait=0 AND mf.numero=:numPa ORDER BY mf.dateconsu DESC");
						
						$resultatsInfPa->execute(array(
							'numPa'=>$_GET['num']
							));
							
						$resultatsInfPa->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
						$comptResultInfPa=$resultatsInfPa->rowCount();
					
					}else{
					
						$resultatsI=$connexion->query("SELECT *FROM utilisateurs u, patients p, med_inf mf WHERE u.id_u=p.id_u AND mf.numero=p.numero AND  (mf.id_prestation !='' OR mf.autrePrestaM !='') AND mf.soinsfait=0 GROUP BY p.numero ORDER BY u.nom_u");
							
						$resultatsI->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
						$comptResultI=$resultatsI->rowCount();
						
					
					}
				}
				*/
				
				/*-----------Requête pour laborantin-----------*/
				
				
				
				/*-----------Requête pour medecin-----------*/

			/* 	
				if($comptidD!=0)
				{
					$resultatsD=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.nom_u LIMIT '.$limit_start.', '.$pagination.'');
					$resultatsD->execute(array(
					'operationM'=>$_SESSION['codeM']	
					));
				
					$resultatsD->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet 
				}
				
			*/
				
				/*-----------Requête pour receptionistes-----------*/
				
				if($comptidR!=0)
				{
					$resultatsR=$connexion->query('SELECT *FROM utilisateurs u, patients p  consultations c WHERE c.dateconsu="'.$annee.'" AND u.id_u=p.id_u ORDER BY c.heureconsu') or die( print_r($connexion->errorInfo()));

					$resultatsR->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					$comptresultatR = $resultatsR->rowCount();
				}

				if($comptidI!=0)
				{
					$resultatsR=$connexion->query('SELECT *FROM utilisateurs u, patients p, consultations c WHERE c.dateconsu="'.$annee.'" AND u.id_u=p.id_u AND p.numero=c.numero ORDER BY c.heureconsu DESC') or die( print_r($connexion->errorInfo()));

					$resultatsR->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					$comptresultatR = $resultatsR->rowCount();
				}
				
				/*-----------Requête pour caissier-----------*/
				
				if($comptidC!=0)
				{
					$resultatsC=$connexion->query("SELECT *FROM utilisateurs u, patients p, consultations c WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.dateconsu !='0000-00-00' AND c.dateconsu='".$annee."' AND c.done=0 ORDER BY c.id_consu DESC") or die( print_r($connexion->errorInfo()));
					
					$resultatsC->setFetchMode(PDO::FETCH_OBJ);
					
					$comptResultatsC = $resultatsC->rowCount();
					
					
					$resultsC=$connexion->query("SELECT *FROM utilisateurs u, patients p, consultations c WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.dateconsu='".$annee."' AND c.done=1 ORDER BY c.id_consu DESC") or die( print_r($connexion->errorInfo()));

					$resultsC->setFetchMode(PDO::FETCH_OBJ);
					
					$comptResultsC = $resultsC->rowCount();
				}
				
				/*-----------Requête pour auditeur-----------*/
				
				if($comptidA!=0)
				{
					$resultatsA=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.nom_u LIMIT '.$limit_start.', '.$pagination.'') or die( print_r($connexion->errorInfo()));

					$resultatsA->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				}
				
				/*-----------Requête pour coordinateur-----------*/
				
				if($comptidM!=0)
				{
					$resultatsM=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.nom_u LIMIT '.$limit_start.', '.$pagination.'') or die( print_r($connexion->errorInfo()));

					$resultatsM->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				}
				
				
		if($comptidL!=0 or $comptidX!=0 or $comptidI!=0)
		{
			
			if(isset($_GET['num']))
			{
			?>
				<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 30px; padding: 10px; width:80%;">
					<tr>
						<td style="font-size:18px; text-align:center; width:33.333%;">
							<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $nom_uti.' '.$prenom_uti;?>
						</td>
						
						<td style="font-size:18px; text-align:center; width:33.333%;">
							<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
							<?php 
							if($sexe=="M")
							{
								echo getString(12);
							}else{
								if($sexe=="F")
								echo getString(13);
							}
							?>
						</td>
						
						<td style="font-size:18px; text-align:center; width:33.333%;">
							<span style="font-weight:bold;">Age: </span><?php echo $an;?>
						</td>
					</tr>
				</table>
			<?php
			}

		}
		?>

		<?php
		if(isset($_GET['signvital']) && $comptresultatR!=0)
		{
		?>
			<h3>Patient waiting for Vital Sign</h3>
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;">
			<thead>
				<tr>
					<th>S/N</th>
					<th><?php echo getString(222);?></th>
					<th><?php echo getString(89);?></th>
					<th><?php echo 'Assurance';?></th>
					<th><?php echo getString(8);?></th>
					<th><?php echo getString(14);?></th>
					<th><?php echo getString(15);?></th>
					<th><?php echo getString(33);?></th>
					<th colspan="2"><?php echo getString(125);?></th>
				</tr> 
			</thead> 
		<?php
		}
		if($comptidR!=0)
		{
		?>
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;">
			<thead>
				<tr>
					<th>S/N</th>
					<th><?php echo getString(222);?></th>
					<th><?php echo getString(89);?></th>
					<th><?php echo 'Assurance';?></th>
					<th><?php echo getString(8);?></th>
					<th><?php echo getString(14);?></th>
					<th><?php echo getString(15);?></th>
					<th><?php echo getString(33);?></th>
					<th><?php echo getString(32);?></th>
					<th><?php echo getString(125);?></th>
				</tr> 
			</thead> 
		<?php
		}

		if($comptidC!=0)
		{
		?>
			<div style="overflow:auto;height:350px;display:inline" id="divPatient">
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;">
			<thead> 
				<tr>
					<th style="width:5%">S/N</th> 
					<th style="width:5%"><?php echo getString(222);?></th>
					<th style="width:20%"><?php echo getString(205);?></th>
					<th style="width:15%"><?php echo getString(206);?></th>
					<th style="width:5%"><?php echo getString(97);?></th>
					<th style="width:20%"><?php echo getString(130);?></th>
					<th style="width:10%"><?php echo getString(113);?></th>
					<th style="width:20%; padding:0"><?php echo 'Actions';?></th>
				</tr> 
			</thead> 
		<?php
		}
		
		if($comptidM!=0 OR $comptidA!=0)
		{
		?>
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;">
			<thead> 
				<tr>
					<th>S/N</th> 
					<th style="width:10%"><?php echo getString(222);?></th>
					<th><?php echo getString(9);?></th>
					<th><?php echo getString(10);?></th>
					<th><?php echo getString(11);?></th>
					<th><?php echo getString(8);?></th>
					<th><?php echo getString(16);?></th>
					<th><?php echo getString(14);?></th>
					<th><?php echo getString(15);?></th>
					<th><?php echo getString(18);?></th>
					<th><?php echo getString(33);?></th>
					<?php 
					
					?>	
					
					<?php 
					if($comptidM!=0 AND !isset($_GET['report']))
					{
					?>
						<th><?php echo getString(93);?></th>			
					<?php 
					}else{
						if($comptidA!=0)
						{
					?>
							<th>Actions</th>			
					<?php 
						}else{
					?>
							<th><?php echo getString(2);?></th>
					<?php
						}
					}
					?>
				</tr> 
			</thead> 
		<?php
		}
		
		if($comptidM!=0)
		{
		?>				
			<tbody>
			
				<?php
				while($ligneM=$resultatsM->fetch())//on récupère la liste des éléments
				{
				?>
				<tr style="text-align:center;<?php if($ligneM->status==0){?>background:rgba(255,0,0,0.15)<?php ;}?>"> 
					<td><?php echo $ligneM->numero;?></td>
					<td><?php echo $ligneM->reference_id;?></td>
					<td><?php echo $ligneM->nom_u;?></td>
					<td><?php echo $ligneM->prenom_u;?></td>
					<td>
					<?php 
					if($ligneM->sexe=="M")
					{
						echo getString(12);
					}else{
						if($ligneM->sexe=="F")
						echo getString(13);
					}
					?>
					</td>
					<td><?php echo $ligneM->date_naissance;?></td>
					<td><?php echo $ligneM->e_mail;?></td>
					<td>
					<?php
							
						$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
						$resultAdresse->execute(array(
						'idProv'=>$ligneM->province,
						'idDist'=>$ligneM->district,
						'idSect'=>$ligneM->secteur
						));
								
						$resultAdresse->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptAdress=$resultAdresse->rowCount();
						
						if($ligneAdresse=$resultAdresse->fetch())
						{
							if($ligneAdresse->id_province == $ligneM->province)
							{
								$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
								
								echo $adresse;
							}
						}else{
							echo '';
						}

					?>
					</td>
					<td><?php echo $ligneM->telephone;?></td>
					<td><?php echo $ligneM->anneeadhesion;?></td>
					<td><?php echo $ligneM->profession;?></td>
					<?php
					
					if($ligneM->status==1)
					{
					?>
					<td>			
						<a href="traitement_utilisateurs.php?idutiDesactif=<?php echo $ligneM->id_u?>&code=<?php echo $ligneM->numero;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo getString(95);?><input type="image" style="border-radius:100px;" src="images/onBlue.png" name="desactifbtn"/></a>
					
					</td>
					<?php
					}else{
						if($ligneM->status==0)
						{
					?>
					<td>
						<a href="traitement_utilisateurs.php?idutiActif=<?php echo $ligneM->id_u?>&code=<?php echo $ligneM->numero;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo getString(96);?><input type="image" style="border-radius:100px;" src="images/offBlue.png" name="actifbtn"/></a>
					
					</td>
					<?php
						}
					}
					?>
				</tr>
				<?php
				}
				$resultatsM->closeCursor();
				?>
			</tbody>
			
			</table>
		<?php
		}
		
		if($comptidA!=0)
		{
		?>				
			<tbody>			
				<?php
				while($ligneA=$resultatsA->fetch())//on récupère la liste des éléments
				{
			?>
				<tr style="text-align:center;<?php if($ligneA->status==0){?>background:rgba(255,0,0,0.15)<?php ;}?>"> 
					<td><?php echo $ligneA->numero;?></td>
					<td><?php echo $ligneA->reference_id;?></td>
					<td><?php echo $ligneA->nom_u;?></td>
					<td><?php echo $ligneA->prenom_u;?></td>
					<td>
					<?php 
					if($ligneA->sexe=="M")
					{
						echo getString(12);
					}else{
						if($ligneA->sexe=="F")
						echo getString(13);
					}
					?>
					</td>
					<td><?php echo date('d-M-Y', strtotime($ligneA->date_naissance));?></td>
					<td><?php echo $ligneA->e_mail;?></td>
					<td>
					<?php
							
						$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
						$resultAdresse->execute(array(
						'idProv'=>$ligneA->province,
						'idDist'=>$ligneA->district,
						'idSect'=>$ligneA->secteur
						));
								
						$resultAdresse->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptAdress=$resultAdresse->rowCount();
						
						if($ligneAdresse=$resultAdresse->fetch())
						{
							if($ligneAdresse->id_province == $ligneA->province)
							{
								$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
								
								echo $adresse;
							}
						}else{
							echo '';
						}

					?>
					</td>
					<td><?php echo $ligneA->telephone;?></td>
					<td><?php echo date('d-M-Y', strtotime($ligneA->anneeadhesion));?></td>
					<td><?php echo $ligneA->profession;?></td>
					
					<td>
						<a href="report.php?num=<?php echo $ligneA->numero;?>&audit=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="<?php if($ligneA->status==0){ echo "display:none";}?>" class="btn"><?php echo getString(94);?></a>
					</td>
					
				</tr>
				<?php
				}
				$resultatsA->closeCursor();
				?>
			</tbody>
			
			</table>
		<?php
		}

		if($comptidC!=0)
		{
		?>				
			<tbody>			
			<?php
			
			if($comptResultatsC!=0 OR $comptResultsC!=0)
			{
				
				while($ligneC=$resultatsC->fetch())//on récupère la liste des éléments
				{
					
					$getIdBillConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_factureConsult IS NULL AND c.numero=:numPa AND c.dateconsu!="0000-00-00" GROUP BY c.dateconsu ORDER BY c.dateconsu DESC');
					$getIdBillConsu->execute(array(
					'numPa'=>$ligneC->numero
					));

					$comptIdBillConsu=$getIdBillConsu->rowCount();
					
					
					$getIdBillMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, consultations c WHERE mc.numero=:numPa AND c.numero=mc.numero AND c.id_consu=mc.id_consuMed AND c.dateconsu=mc.dateconsu AND mc.id_factureMedConsu=0 GROUP BY c.dateconsu ORDER BY c.dateconsu DESC');
					$getIdBillMedConsu->execute(array(
					'numPa'=>$ligneC->numero
					));

					$comptIdBillMedConsu=$getIdBillMedConsu->rowCount();
					
					
					$getIdBillMedInf=$connexion->prepare('SELECT *FROM med_inf mi, consultations c WHERE mi.numero=:numPa AND c.numero=mi.numero AND c.id_consu=mi.id_consuInf AND c.dateconsu=mi.dateconsu AND mi.datesoins!="0000-00-00" AND mi.id_factureMedInf=0 GROUP BY c.dateconsu ORDER BY c.dateconsu DESC');
					$getIdBillMedInf->execute(array(
					'numPa'=>$ligneC->numero
					));

					$comptIdBillMedInf=$getIdBillMedInf->rowCount();
					
					
					$getIdBillMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, consultations c WHERE ml.numero=:numPa AND c.numero=ml.numero AND c.id_consu=ml.id_consuLabo AND c.dateconsu=ml.dateconsu AND ml.dateresultats!="0000-00-00" AND ml.id_factureMedLabo=0 GROUP BY c.dateconsu ORDER BY c.dateconsu DESC');
					$getIdBillMedLabo->execute(array(
					'numPa'=>$ligneC->numero
					));

					$comptIdBillMedLabo=$getIdBillMedLabo->rowCount();
					
					// if($comptIdBillConsu!=0 OR $comptIdBillMedConsu!=0 OR $comptIdBillMedInf!=0 OR $comptIdBillMedLabo!=0)
					// {
		?>
					<tr style="text-align:center;<?php if($ligneC->status==0){?>background:rgba(255,0,0,0.15)<?php }?>"> 
						<td><?php echo $ligneC->numero;?></td>
						<td><?php echo $ligneC->reference_id;?></td>
						<td><?php echo $ligneC->nom_u.' '.$ligneC->prenom_u;?></td>
						<td>
						<?php
						$resultatsReceptionist=$connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=r.id_u AND r.id_u=:idRecept ORDER BY u.nom_u');
						$resultatsReceptionist->execute(array(
						'idRecept'=>$ligneC->id_uR,
						));
							
						$resultatsReceptionist->setFetchMode(PDO::FETCH_OBJ);
					
						if($ligneReceptionist=$resultatsReceptionist->fetch())
						{
						
							echo $ligneReceptionist->nom_u.' '.$ligneReceptionist->prenom_u;
						}
						?>
						</td>
						
						<td><?php echo date('d-M-Y',strtotime($ligneC->dateconsu));?></td>
						<td>
						<?php
						$resultatsMedecins=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m, grades g, categopresta_ins cp, servicemed sm WHERE u.id_u=m.id_u and g.id_grade=cp.id_grade and sm.id_categopresta=cp.id_categopresta and sm.codemedecin=m.codemedecin and m.id_u=:idMedecin ORDER BY u.nom_u');
						$resultatsMedecins->execute(array(
						'idMedecin'=>$ligneC->id_uM,
						));
							
							
						$resultatsMedecins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
						if($ligneMedecins=$resultatsMedecins->fetch())//on recupere la liste des éléments
						{
						
							echo $ligneMedecins->nom_u.' '.$ligneMedecins->prenom_u;
									
							if($ligneMedecins->nomcategopresta == 'Consultations')
							{
								echo ' (Generalist)';
							}else{
								echo ' ('.$ligneMedecins->nomcategopresta.')';
							}
						}
						?>
						</td>
						
						<td>
						<?php
						
						$idassu=$ligneC->id_assuConsu;

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

						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
						$resultPresta->execute(array(
						'idPresta'=>$ligneC->id_typeconsult	
						))or die( print_r($connexion->errorInfo()));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);
						
						$comptidPresta=$resultPresta->rowCount();
						
						if($comptidPresta!=0)
						{
							if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
							{
								$nomconsu=$lignePresta->nompresta;
								
								echo $lignePresta->nompresta;
							}
						}else{
							
							$nomconsu=$ligneC->autretypeconsult;
								
							echo $ligneC->autretypeconsult;
						}
						?>
						</td>
						<td style="text-align:right;">
							<a class="btn" href="categoriesbill.php?num=<?php echo $ligneC->numero;?>&cashier=<?php echo $_SESSION['id'];?>&dateconsu=<?php echo $ligneC->dateconsu;?>&idtypeconsu=<?php echo $ligneC->id_typeconsult;?>&idconsu=<?php echo $ligneC->id_consu;?>&idmed=<?php echo $ligneC->id_uM;?>&idassu=<?php echo $ligneC->id_assurance;?>&idbill=<?php echo $ligneC->id_factureConsult;?>&previewprint=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px; <?php if($ligneC->status==0){ echo "display:none";}?>">
								<i class="fa fa-money fa-2x fa-fw"></i>
							</a>
						
							<a class="btn" href="patients1.php?cashier=<?php echo $_SESSION['id'];?>&deleteconsu=<?php echo $ligneC->id_consu;?>&nomconsu=<?php echo $nomconsu;?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px; <?php if($ligneC->status==0){ echo "display:none";}?>">
								<i class="fa fa-trash fa-2x fa-fw"></i>
							</a>

						</td>
						
					</tr>
			<?php
					// }
				}
				$resultatsC->closeCursor();
				
				while($lineC=$resultsC->fetch())
				{
					
					$getIdBillConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:numPa AND c.done=1 AND c.dateconsu!="0000-00-00" GROUP BY c.dateconsu ORDER BY c.dateconsu DESC');
					$getIdBillConsu->execute(array(
					'numPa'=>$lineC->numero
					));

					$comptIdBillConsu=$getIdBillConsu->rowCount();
					
					
					$getIdBillMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, consultations c WHERE mc.numero=:numPa AND c.numero=mc.numero AND c.id_consu=mc.id_consuMed AND c.dateconsu=mc.dateconsu AND mc.id_factureMedConsu=0 GROUP BY c.dateconsu ORDER BY c.dateconsu DESC');
					$getIdBillMedConsu->execute(array(
					'numPa'=>$lineC->numero
					));

					$comptIdBillMedConsu=$getIdBillMedConsu->rowCount();
					
					
					$getIdBillMedInf=$connexion->prepare('SELECT *FROM med_inf mi, consultations c WHERE mi.numero=:numPa AND c.numero=mi.numero AND c.id_consu=mi.id_consuInf AND c.dateconsu=mi.dateconsu AND mi.id_factureMedInf=0 GROUP BY c.dateconsu ORDER BY c.dateconsu DESC');
					$getIdBillMedInf->execute(array(
					'numPa'=>$lineC->numero
					));

					$comptIdBillMedInf=$getIdBillMedInf->rowCount();
					
					
					$getIdBillMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, consultations c WHERE ml.numero=:numPa AND c.numero=ml.numero AND c.id_consu=ml.id_consuLabo AND c.dateconsu=ml.dateconsu AND ml.id_factureMedLabo=0 GROUP BY c.dateconsu ORDER BY c.dateconsu DESC');
					$getIdBillMedLabo->execute(array(
					'numPa'=>$lineC->numero
					));

					$comptIdBillMedLabo=$getIdBillMedLabo->rowCount();
					
					if($comptIdBillConsu!=0 OR $comptIdBillMedConsu!=0 OR $comptIdBillMedInf!=0 OR $comptIdBillMedLabo!=0)
					{
		?>
					<tr style="text-align:center;<?php if($lineC->status==0){?>background:rgba(255,0,0,0.15)<?php ;}?>"> 
						<td><?php echo $lineC->numero;?></td>
						<td><?php echo $lineC->reference_id;?></td>
						<td><?php echo $lineC->nom_u.' '.$lineC->prenom_u;?></td>
						<td>
						<?php
						$resultatsReceptionist=$connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=r.id_u AND r.id_u=:idRecept ORDER BY u.nom_u');
						$resultatsReceptionist->execute(array(
						'idRecept'=>$lineC->id_uR,
						));
							
						$resultatsReceptionist->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
						if($ligneReceptionist=$resultatsReceptionist->fetch())//on recupere la liste des éléments
						{
						
							echo $ligneReceptionist->nom_u.' '.$ligneReceptionist->prenom_u;
						}
						?>
						</td>
						
						<td><?php echo date('d-M-Y', strtotime($lineC->dateconsu));?></td>
						<td>
						<?php
						$resultatsMedecins=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m, grades g, categopresta_ins cp, servicemed sm WHERE u.id_u=m.id_u and g.id_grade=cp.id_grade and sm.id_categopresta=cp.id_categopresta and sm.codemedecin=m.codemedecin and m.id_u=:idMedecin ORDER BY u.nom_u');
						$resultatsMedecins->execute(array(
						'idMedecin'=>$lineC->id_uM,
						));
							
							
						$resultatsMedecins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
						if($ligneMedecins=$resultatsMedecins->fetch())//on recupere la liste des éléments
						{
						
							echo $ligneMedecins->nom_u.' '.$ligneMedecins->prenom_u;
									
							if($ligneMedecins->nomcategopresta == 'Consultations')
							{
								echo ' (Generalist)';
							}else{
								echo ' ('.$ligneMedecins->nomcategopresta.')';
							}
						}
						?>
						</td>
						
						<td>
						<?php 
						
						$idassu=$lineC->id_assurance;
						
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
						
						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
						$resultPresta->execute(array(
						'idPresta'=>$lineC->id_typeconsult	
						))or die( print_r($connexion->errorInfo()));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							$nomconsu=$lignePresta->nompresta;
							
							echo $lignePresta->nompresta;
						}

						?>
						</td>
						<?php
			
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.numero=:num AND mc.id_factureMedConsu=0 AND mc.id_consuMed=:idconsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'num'=>$lineC->numero,
						'idconsu'=>$lineC->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

						$comptMedConsult=$resultMedConsult->rowCount();



						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=0 AND mi.numero=:num AND mi.id_factureMedInf=0 AND mi.id_consuInf=:idconsu ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'num'=>$lineC->numero,
						'idconsu'=>$lineC->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

						$comptMedInf=$resultMedInf->rowCount();



						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.numero=:num AND ml.id_factureMedLabo=0 AND ml.id_consuLabo=:idconsu ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(
						'num'=>$lineC->numero,
						'idconsu'=>$lineC->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

						$comptMedLabo=$resultMedLabo->rowCount();




						$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.radiofait=0 AND mr.numero=:num AND mr.id_factureMedRadio=0 AND mr.id_consuRadio=:idconsu ORDER BY mr.id_medradio');		
						$resultMedRadio->execute(array(
						'num'=>$lineC->numero,
						'idconsu'=>$lineC->id_consu
						));
						
						$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

						$comptMedRadio=$resultMedRadio->rowCount();




						$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.numero=:num AND mco.id_factureMedConsom=0 AND mco.id_consuConsom=:idconsu ORDER BY mco.id_medconsom');		
						$resultMedConsom->execute(array(
						'num'=>$lineC->numero,
						'idconsu'=>$lineC->id_consu
						));
						
						$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

						$comptMedConsom=$resultMedConsom->rowCount();

						$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.numero=:num AND mdo.id_factureMedMedoc=0 AND mdo.id_consuMedoc=:idconsu ORDER BY mdo.id_medmedoc');		
						$resultMedMedoc->execute(array(
						'num'=>$lineC->numero,
						'idconsu'=>$lineC->id_consu
						));
						
						$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

						$comptMedMedoc=$resultMedMedoc->rowCount();
						?>
						
						<td style="text-align:right">
						
						<?php
						if($comptMedConsult!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0)
						{
						?>
							<a style="padding:10px 20px;" href="<?php echo 'categoriesbill.php?cashier='.$_SESSION['id'].'&num='.$lineC->numero.'&idconsu='.$lineC->id_consu.'&idmed='.$lineC->id_uM.'&dateconsu='.$lineC->dateconsu.'&idtypeconsu='.$lineC->id_typeconsult.'&idassu='.$lineC->id_assurance.'&idbill='.$lineC->id_factureConsult.'&previewprint=ok'?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>" class="btn-large" name="showpreviewbtn" id="showpreviewbtn"><?php echo getString(220)?></a>
						<?php
						}
						?>
							<a style="padding:10px 15px;" class="btn-large" href="categoriesbill.php?cashier=<?php echo $_SESSION['id'];?>&num=<?php echo $lineC->numero;?>&idconsu=<?php echo $lineC->id_consu;?>&idmed=<?php echo $lineC->id_uM;?>&dateconsu=<?php echo $lineC->dateconsu;?>&idtypeconsu=<?php echo $lineC->id_typeconsult;?>&idassu=<?php echo $lineC->id_assuConsu;?>&idbill=<?php echo $lineC->id_factureConsult;?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
								<i class="fa fa-plus fa-0.5x fa-fw"></i>
							</a>
						
						</td>
					</tr>
			<?php
					}
				}
				$resultsC->closeCursor();
			}else{
			?>
				<tr style="text-align:center;"> 
					<td colspan=8 style="text-align:center"><?php echo getString(190);?></td>
					</td>
				</tr>
			<?php			
			}
			?>
			</tbody>
			
			</table>
		<?php
		}

		if(isset($_GET['signvital']) && $comptresultatR!=0)
		{
		?>				
			<tbody>			
			<?php
				while($ligneR=$resultatsR->fetch())//on récupère la liste des éléments
				{
					$fullname=$ligneR->nom_u.' '.$ligneR->prenom_u;
		?>
				<tr style="text-align:center;<?php if($ligneR->status==0){?>background:rgba(255,0,0,0.15);<?php  ;}?> <?php if($ligneR->poids!=''){?>background:rgba(0,100,255,0.5);<?php  ;}?>"> 
					<td><?php echo $ligneR->numero;?></td>
					<td><?php echo $ligneR->reference_id;?></td>
					<td><?php echo $fullname;?></td>
					
					<td>
					<?php
							
						$resultAssu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu');
						$resultAssu->execute(array(
						'idassu'=>$ligneR->id_assurance
						));
								
						$resultAssu->setFetchMode(PDO::FETCH_OBJ);

						$comptAssu=$resultAssu->rowCount();
						
						if($ligneAssu=$resultAssu->fetch())
						{
							$assurance = $ligneAssu->nomassurance;
							
							echo $assurance;
							
						}else{
							echo '';
						}

					?>
					</td>
						
					<td><?php echo $ligneR->date_naissance;?></td>
					<td>
					<?php
					
						$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
						$resultAdresse->execute(array(
						'idProv'=>$ligneR->province,
						'idDist'=>$ligneR->district,
						'idSect'=>$ligneR->secteur
						));
								
						$resultAdresse->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptAdress=$resultAdresse->rowCount();
						
						if($ligneAdresse=$resultAdresse->fetch())
						{
							if($ligneAdresse->id_province == $ligneR->province)
							{
								$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
								
								echo $adresse;
							}
						}else{
							echo '';
						}

					?>
					</td>
					<td><?php echo $ligneR->telephone;?></td>
					
					<td><?php echo $ligneR->profession;?></td>			
					<td>
						<!-- <a class="btn" href="consultations.php?iduti=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneR->numero?>&consu=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="<?php if($ligneR->status==0){ echo "display:none";}?>"><i class="fa fa-stethoscope fa-lg fa-fw "></i> <?php echo getString(202)?></a> -->

						<?php if($ligneR->poids!=''){?>
							<a href="consultations.php?iduti=<?php echo $_SESSION['id'];?>&idconsu=<?php echo $ligneR->id_consu;?>&num=<?php echo $ligneR->numero;?>&id_uM=<?php echo $ligneR->id_uM;?>&consu=ok&receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
										<i class="fa fa-check fa-1x fa-fw" style="margin-left: 30px;color: black;font-size: 25px;"></i>
						<?php  }else{?>
							<a class="btn" href="consultations.php?iduti=<?php echo $_SESSION['id'];?>&idconsu=<?php echo $ligneR->id_consu;?>&num=<?php echo $ligneR->numero;?>&id_uM=<?php echo $ligneR->id_uM;?>&consu=ok&receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
										<i class="fa fa-stethoscope fa-lg fa-fw "></i> <?php echo 'Sign Vital' ?>
						</a>
						<?php  }?>
						
					</td>
									
				</tr>
				<?php
				}
				$resultatsR->closeCursor();
				?>
			</tbody>
			
			</table>
		<?php
		}else{
			?>
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:80%;">
				<thead> 
					<tr>
						<th><?php echo getString(264)?></th>		
					</tr> 
				</thead>
			</table>
		<?php
		}


		if($comptidD!=0)
		{
		?>				
			<?php
			if(!isset($_GET['all']))
			{
			?>
				<?php
			
				$resultatsConsultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.id_uM=:idMed AND c.dateconsu=:annee AND c.done=0 GROUP BY c.numero ORDER BY c.heureconsu');

				$resultatsConsultPatient->execute(array(
				'idMed'=>$_SESSION['id'],
				'annee'=>$annee
				));

				$resultatsConsultPatient->setFetchMode(PDO::FETCH_OBJ);
			
				$comptConsultPatient=$resultatsConsultPatient->rowCount();
				
				
				$attenteResultsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c, med_labo ml WHERE u.id_u=p.id_u AND c.numero=p.numero AND ml.numero=p.numero AND c.id_uM=:idMed AND c.id_consu=ml.id_consuLabo AND c.dateconsu=:annee GROUP BY c.numero ORDER BY c.numero DESC');

				$attenteResultsPatient->execute(array(
				'idMed'=>$_SESSION['id'],
				'annee'=>$annee
				));

				$attenteResultsPatient->setFetchMode(PDO::FETCH_OBJ);
			
				$comptAttenteResultsPatient=$attenteResultsPatient->rowCount();
						
					
				
				$noResultsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.id_uM=:idMed AND c.dateconsu=:annee AND c.done=1 GROUP BY c.numero ORDER BY c.numero DESC');

				$noResultsPatient->execute(array(
				'idMed'=>$_SESSION['id'],
				'annee'=>$annee
				));

				$noResultsPatient->setFetchMode(PDO::FETCH_OBJ);
			
				$comptNoResultsPatient=$noResultsPatient->rowCount();
						
						
				if($comptConsultPatient!=0)
				{
				?>
				
				<h2 style="font-size:50px;margin:10px;font-weight:100;">Patients à consulter</h2>
				
				<div style="overflow:auto;max-height:350px;margin-top:;display:block">
			
				<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;">
					<thead> 
						<tr>
							<th>S/N</th>
							<th><?php echo getString(222);?></th>
							<th><?php echo getString(89);?></th>
							<th><?php echo getString(11);?></th>
							<th colspan=2 style="width:30%;">Actions</th>
						</tr> 
					</thead>
					
					<tbody>
				
					<?php
					
					while($ligneConsultPatient=$resultatsConsultPatient->fetch())
					{
					?>
						<tr style="text-align:center;">

							<td><?php echo $ligneConsultPatient->numero;?></td>
							<td><?php echo $ligneConsultPatient->reference_id;?></td>
							<td><?php echo $ligneConsultPatient->full_name;?></td>
							<td>
							<?php 
							if($ligneConsultPatient->sexe=="M")
							{
								echo getString(12);
							}else{
								if($ligneConsultPatient->sexe=="F")
								echo getString(13);
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num');		
							$resultConsult->execute(array(
							'num'=>$ligneConsultPatient->numero
							));

							$comptConsult=$resultConsult->rowCount();
							
							$resultConsult->setFetchMode(PDO::FETCH_OBJ);
							
							if($comptConsult!=0)
							{
								if($ligneMedConsult=$resultConsult->fetch())
								{
							?>
								<a href="consult.php?num=<?php echo $ligneConsultPatient->numero?>&showfiche=ok&idtypeconsult=<?php echo $ligneMedConsult->id_typeconsult;?>&idconsu=<?php echo $ligneMedConsult->id_consu;?>&idassuconsu=<?php echo $ligneMedConsult->id_assuConsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient" class="btn"><span title="View Profile" name="fichebtn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(100);?></span></a>
								
							<?php 
								}
								
							}else{
							?>
								---<?php echo getString(208) ?>---
							<?php 
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$resultMedConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.dateconsu=:annee AND (c.done=1 OR c.done=0) AND c.id_uM=:idMed ORDER BY c.id_consu DESC LIMIT 1');		
							$resultMedConsult->execute(array(
							'num'=>$ligneConsultPatient->numero,
							'annee'=>$annee,
							'idMed'=>$_SESSION['id']
							));

							$comptMedConsult=$resultMedConsult->rowCount();
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);
							
							if($comptMedConsult!=0)
							{
								if($ligneConsult=$resultMedConsult->fetch())
								{
							
							?>
									<a href="consult.php?num=<?php echo $ligneConsultPatient->numero;?>&idtypeconsult=<?php echo $ligneConsult->id_typeconsult;?>&idconsu=<?php echo $ligneConsult->id_consu;?>&idassuconsu=<?php echo $ligneConsult->id_assuConsu;?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>
									
							<?php
								}
							}else{
							?>
								---<?php echo getString(207) ?>---
							<?php
							}
							?>
							</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
				
				</div>
				<?php
				}else{
				?>
					<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:80%;">
						<thead> 
							<tr>
								<th><?php echo getString(264)?></th>		
							</tr> 
						</thead>
					</table>
			<?php
				}		
						
				if($comptAttenteResultsPatient!=0)
				{
				?>
				
				<h2 style="font-size:50px;margin-top:50px;margin-bottom:10px;font-weight:100;"><?php echo getString(265);?></h2>
				
				<div style="overflow:auto;max-height:350px;display:block">
			
				<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;">
					<thead> 
						<tr>
							<th>S/N</th>
							<th><?php echo getString(222);?></th>
							<th><?php echo getString(89);?></th>
							<th><?php echo getString(11);?></th>
							<th colspan=2 style="width:30%;">Actions</th>
						</tr> 
					</thead>
					
					<tbody>
				
					<?php
					
				while($ligneattenteResultsPatient=$attenteResultsPatient->fetch())
					{
						
						
						$doneResultsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c, med_labo ml WHERE u.id_u=p.id_u AND c.numero=p.numero AND ml.numero=p.numero AND ml.numero=:num AND c.id_uM=:idMed AND c.id_consu=ml.id_consuLabo AND ml.dateresultats="0000-00-00" ORDER BY c.numero');

						$doneResultsPatient->execute(array(
						'num'=>$ligneattenteResultsPatient->numero,
						'idMed'=>$_SESSION['id']
						));

						$doneResultsPatient->setFetchMode(PDO::FETCH_OBJ);
					
						$comptDoneResultsPatient=$doneResultsPatient->rowCount();
				
				
				
						$DiagnoPostDone=0;
															
						$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
						
						$resuPostdiagnostic->execute(array(
						'idConsu'=>$ligneattenteResultsPatient->id_consu
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
								$DiagnoPostDone=1;
							}else{
							
								if($lignePostdiagnostic->postdiagnostic != "")
								{
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
									$DiagnoPostDone=1;
								}else{
									if($linePostDiagno->autrepostdia !="")
									{
										$DiagnoPostDone=1;
									}
								}
								
							}
						
						}
						
						if($DiagnoPostDone ==0)
						{
					?>
						<tr style="text-align:center;<?php if($comptDoneResultsPatient==0){ echo 'background:rgba(0,100,255,0.5);';}?>">

							<td><?php echo $ligneattenteResultsPatient->numero;?></td>
							<td><?php echo $ligneattenteResultsPatient->reference_id;?></td>
							<td><?php echo $ligneattenteResultsPatient->full_name;?></td>
							<td>
							<?php 
							if($ligneattenteResultsPatient->sexe=="M")
							{
								echo getString(12);
							}else{
								if($ligneattenteResultsPatient->sexe=="F")
								echo getString(13);
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num');		
							$resultConsult->execute(array(
							'num'=>$ligneattenteResultsPatient->numero
							));

							$comptConsult=$resultConsult->rowCount();
							
							$resultConsult->setFetchMode(PDO::FETCH_OBJ);
							
							if($comptConsult!=0)
							{
								if($ligneMedConsult=$resultConsult->fetch())
								{
							?>
								<a href="consult.php?num=<?php echo $ligneattenteResultsPatient->numero?>&showfiche=ok&idtypeconsult=<?php echo $ligneMedConsult->id_typeconsult;?>&idconsu=<?php echo $ligneMedConsult->id_consu;?>&idassuconsu=<?php echo $ligneMedConsult->id_assuConsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient" class="btn"><span title="View Profile" name="fichebtn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(100);?></span></a>
								
							<?php 
								}
								
							}else{
							?>
								---<?php echo getString(208) ?>---
							<?php 
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$resultMedConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.dateconsu=:annee AND (c.done=1 OR c.done=0) AND c.id_uM=:idMed ORDER BY c.id_consu DESC LIMIT 1');		
							$resultMedConsult->execute(array(
							'num'=>$ligneattenteResultsPatient->numero,
							'annee'=>$annee,
							'idMed'=>$_SESSION['id']
							));

							$comptMedConsult=$resultMedConsult->rowCount();
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);
							
							if($comptMedConsult!=0)
							{
								if($ligneConsult=$resultMedConsult->fetch())
								{
							
							?>
									<a href="consult.php?num=<?php echo $ligneattenteResultsPatient->numero;?>&idtypeconsult=<?php echo $ligneConsult->id_typeconsult;?>&idconsu=<?php echo $ligneConsult->id_consu;?>&idassuconsu=<?php echo $ligneConsult->id_assuConsu;?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>
									
							<?php
								}
							}else{
							?>
								---<?php echo getString(207) ?>---
							<?php
							}
							?>
							</td>
						</tr>
					<?php
						}
					}
					?>
					</tbody>
				</table>
				
				</div>
				<?php
				}/* else{
				?>
					<table class="tablesorter" cellspacing="0" style="background-color:#FFF;">
						<thead> 
							<tr>
								<th>Pas de patient à consulter</th>		
							</tr> 
						</thead>
					</table>
			<?php
				} */
						
				if($comptNoResultsPatient!=0)
				{
				?>
				
				<h2 style="font-size:50px;margin-top:50px;margin-bottom:10px;font-weight:100;"><?php echo getString(263);?></h2>
				
				<div style="overflow:auto;max-height:350px;margin:auto;width:100%;display:block">
			
				<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;">
					<thead> 
						<tr>
							<th>S/N</th>
							<th><?php echo getString(222);?></th>
							<th><?php echo getString(89);?></th>
							<th><?php echo getString(11);?></th>
							<th colspan=2 style="width:30%;">Actions</th>
						</tr> 
					</thead>
					
					<tbody>
				
					<?php
					
					while($lignenoResultsPatient=$noResultsPatient->fetch())
					{
						
						$doneResultsPatient=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idconsu ORDER BY ml.numero');

						$doneResultsPatient->execute(array(
						'idconsu'=>$lignenoResultsPatient->id_consu
						));

						$doneResultsPatient->setFetchMode(PDO::FETCH_OBJ);
						
						/* if($ligneConsultation=$doneResultsPatient->fetch())
						{
							$found=1;
						}else{
							$found=0;
						}
						 */
					?>
						<tr style="text-align:center;">

							<td><?php echo $lignenoResultsPatient->numero;?></td>
							<td><?php echo $lignenoResultsPatient->reference_id;?></td>
							<td><?php echo $lignenoResultsPatient->full_name;?></td>
							<td>
							<?php 
							if($lignenoResultsPatient->sexe=="M")
							{
								echo getString(12);
							}else{
								if($lignenoResultsPatient->sexe=="F")
								echo getString(13);
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num');		
							$resultConsult->execute(array(
							'num'=>$lignenoResultsPatient->numero
							));

							$comptConsult=$resultConsult->rowCount();
							
							$resultConsult->setFetchMode(PDO::FETCH_OBJ);
							
							if($comptConsult!=0)
							{
								if($ligneMedConsult=$resultConsult->fetch())
								{
							?>
								<a href="consult.php?num=<?php echo $lignenoResultsPatient->numero?>&showfiche=ok&idtypeconsult=<?php echo $ligneMedConsult->id_typeconsult;?>&idconsu=<?php echo $ligneMedConsult->id_consu;?>&idassuconsu=<?php echo $ligneMedConsult->id_assuConsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient" class="btn"><span title="View Profile" name="fichebtn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(100);?></span></a>
								
							<?php 
								}
								
							}else{
							?>
								---<?php echo getString(208) ?>---
							<?php 
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
						
							$resultMedConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.dateconsu=:annee AND (c.done=1 OR c.done=0) AND c.id_uM=:idMed ORDER BY c.id_consu DESC LIMIT 1');		
							$resultMedConsult->execute(array(
							'num'=>$lignenoResultsPatient->numero,
							'annee'=>$annee,
							'idMed'=>$_SESSION['id']
							));

							$comptMedConsult=$resultMedConsult->rowCount();
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);
							
							if($comptMedConsult!=0)
							{
								if($ligneConsult=$resultMedConsult->fetch())
								{
							
							?>
									<a href="consult.php?num=<?php echo $lignenoResultsPatient->numero;?>&idtypeconsult=<?php echo $ligneConsult->id_typeconsult;?>&idconsu=<?php echo $ligneConsult->id_consu;?>&idassuconsu=<?php echo $ligneConsult->id_assuConsu;?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>
									
							<?php
								}
							}else{
							?>
								---<?php echo getString(207) ?>---
							<?php
							}
							?>
							</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
				
				</div>
				<?php
				}/* else{
				?>
					<table class="tablesorter" cellspacing="0" style="background-color:#FFF;">
						<thead> 
							<tr>
								<th>Pas de patient à consulter</th>		
							</tr> 
						</thead>
					</table>
			<?php
				} */
				
			}
			
			if(isset($_GET['all']))
			{
			?>
				<div style="font-size:50px; font-weight:100;margin:25px;">Tous les patients</div>
				
				
				<?php
				
				$resultatsPatient=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.nom_u LIMIT '.$limit_start.', '.$pagination.'');

				$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet 
								
				$comptPatient=$resultatsPatient->rowCount();
				
				// echo $comptPatient.'---';
				
				if($comptPatient!=0)
				{
				?>
				<div style="overflow:auto;height:350px;margin-top:;display:block">
			
				<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;">
					<thead> 
						<tr>
							<th>S/N</th> 
							<th><?php echo getString(222);?></th> 
							<th><?php echo getString(9);?></th>
							<th><?php echo getString(10);?></th>
							<th><?php echo getString(11);?></th>
							<th colspan=2 style="width:30%;">Actions</th>
						</tr> 
					</thead>
					
					<tbody>
				
					<?php
					
					while($ligneD=$resultatsPatient->fetch())//on recupere la liste des éléments
					{					
					?>
						<tr style="text-align:center;">

							<td><?php echo $ligneD->numero;?></td>
							<td><?php echo $ligneD->reference_id;?></td>
							<td><?php echo $ligneD->nom_u;?></td>
							<td><?php echo $ligneD->prenom_u;?></td>
							<td>
							<?php 
							if($ligneD->sexe=="M")
							{
								echo getString(12);
							}else{
								if($ligneD->sexe=="F")
								echo getString(13);
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num');		
							$resultConsult->execute(array(
							'num'=>$ligneD->numero
							));

							$comptConsult=$resultConsult->rowCount();
							
							$resultConsult->setFetchMode(PDO::FETCH_OBJ);
							
							if($comptConsult!=0)
							{
								if($ligneMedConsult=$resultConsult->fetch())
								{
							?>
								<a href="consult.php?num=<?php echo $ligneD->numero?>&showfiche=ok&idtypeconsult=<?php echo $ligneMedConsult->id_typeconsult;?>&idconsu=<?php echo $ligneMedConsult->id_consu;?>&idassuconsu=<?php echo $ligneMedConsult->id_assuConsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient" class="btn"><span title="View Profile" name="fichebtn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(100);?></span></a>
								
							<?php 
								}
								
							}else{
							?>
								---<?php echo getString(208) ?>---
							<?php 
							}
							?>
							</td>
							
						</tr>
					<?php
					}
					$resultatsPatient->closeCursor();
					?>
					</tbody>
				</table>
				
				</div>
				<?php
					if(isset($_GET['all']))
					{
						if($comptidD!=0)
						{
							echo '
							<tr>
								<td>';
						
									$nb_totalM=$connexion->query('SELECT COUNT(*) AS nb_totalM FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.nom_u');

									$nb_totalM=$nb_totalM->fetch();
									$nb_totalM = $nb_totalM['nb_totalM'];
									// Pagination
									$nb_pagesM = ceil($nb_totalM / $pagination);
									// Affichage
									echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pagesM) . '</p>';
							
							echo '
								</td>
							</tr>
							';

						}
					}
					
				}else{
				?>
					<table class="tablesorter" cellspacing="0" style="background-color:#FFF;">
						<thead> 
							<tr>
								<th>Aucune fiche de malade existante</th>							
							</tr> 
						</thead>
					</table>
			<?php
				}
					
			}
			?>
			
				
		<?php
		}
		if($comptidL!=0)
		{
			if(isset($_GET['examenPa']))
			{
				
				if(isset($_GET['idmedLab']))
				{
				
					$annee = date('Y').'-'.date('m').'-'.date('d');
			?>
				<form method="post" action="traitement_resultats.php?num=<?php echo $num;?>&idmedLab=<?php echo $_GET['idmedLab']; ?>&examenPa=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResult(this)" enctype="multipart/form-data">
							
							
					<table class="tablesorter" style="margin: 20px auto auto; background: rgb(255, 255, 255) none repeat scroll 0% 0%; width: 80%; border: 1px solid rgb(238, 238, 238); border-radius: 5px; padding: 5px;">
						<tbody>							
							<tr style="background: rgb(248, 248, 248) none repeat scroll 0% 0%; font-size: 15px;">
								<td style="font-size: 20px;">
									<span style="font-weight:bold;"><?php echo getString(139) ?>: </span>
						<?php echo $_GET['presta'];?>
							
								</td>
								
								<td style="color:#a00000; padding-right: 20px; padding-top: 10px; padding-bottom: 10px;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo 'Date d\'envoi : '.$annee;?></td>
							</tr>
							
							<tr>
								<td>
									<label style="padding-left: 40px; padding-top: 10px;" for="examen"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo getString(137) ?></label>
									
									<input type="text" id="examen" name="examen" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value=""/>
								</td>
								
								<td align="left" style="vertical-align: top; padding-left: 10px; padding-top:35px;display:none;">
								
									<label for="result"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo getString(138) ?></label>
									
									<input type="file" name="result" id="result" style="border:1px solid #eee; height:40px; padding 2px; width:95%;"/>
								
									<input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
								</td>
							</tr>								
						</tbody>						
						
						<tfoot>
							<tr>
								<td colspan=2 style="text-align:center">										
				
									<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="resultbtn" class="btn-large">
									<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(68) ?>
									</button>
									
									<a href="patients1.php?num=<?php echo $_GET['num'];?>&examenPa=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 40px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></a>
									
									<input type="hidden" name="submitExa" value="<?php echo $_GET['idmedLab'];?>"/>
							
									
								</td>
							</tr>
						</tfoot>
												
					</table>
				</form>
			<?php
				}
				
				if(isset($_GET['updateidmedLabo']))
				{
					
					$updateResult=$connexion->prepare("SELECT *FROM med_labo WHERE id_medlabo =:updateidmedLabo");
					$updateResult->execute(array(
					'updateidmedLabo'=>$_GET['updateidmedLabo']
				
					))or die( print_r($connexion->errorInfo()));
						
					$updateResult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				
					$comptUpdateResult=$updateResult->rowCount();

					if($ligneUpdateResult=$updateResult->fetch())
					{
						$autreresultats=$ligneUpdateResult->autreresultats;
						$idFactureMedLabo=$ligneUpdateResult->id_factureMedLabo;
						$idPrestationExa=$ligneUpdateResult->id_prestationExa;
						$valeurLab=$ligneUpdateResult->valeurLab;
						$minresultats=$ligneUpdateResult->minresultats;
						$maxresultats=$ligneUpdateResult->maxresultats;
						$resultats=$ligneUpdateResult->resultats;
						
					}
				
			?>
				<form method="post" action="traitement_resultats.php?num=<?php echo $num;?>&updateidmedLabo=<?php echo $_GET['updateidmedLabo']; ?>&examenPa=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResult(this)" enctype="multipart/form-data">
							
							
					<table class="tablesorter" style="padding: 5px;">
						<tbody>							
							<tr style="background: rgb(248, 248, 248) none repeat scroll 0% 0%; font-size: 15px;">
								<td colspan=3 style="font-size: 20px;">
									<span style="font-weight:bold;"><?php echo getString(139) ?>: </span><?php echo $_GET['presta'];?>
							
								</td>
								<td style="color:#a00000; padding-right: 20px; padding-top: 10px; padding-bottom: 10px;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo 'Date d\'envoi : '.$annee;?></td>
							</tr>
							
							<tr>
								<td>
									<label style="text-align:right;padding-bottom:0;" for="examen"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo 'Modification des résultats' ?></label>
								</td>
								
								<td>
									<label style="text-align:center;padding-bottom:0;" for="valeur"><?php echo 'Valeur' ?></label>
								</td>
								
								<td>
									<label style="text-align:center;padding-bottom:0;" for="min"><?php echo 'Min' ?></label>
								</td>
								
								<td>	
									<label style="text-align:left;padding-bottom:0;padding-left:30px;" for="max"><?php echo 'Max' ?></label>
									
								</td>
								
								<td align="center" style="vertical-align: top; padding-bottom:0;display:none;">
								
									<label for="result"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo 'Joindre nouveau résultat' ?></label>
								</td>
							</tr>
							
							<tr>
								<td style="text-align:right">
									<input type="text" id="examen" name="examen" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $autreresultats; ?>"/>
								</td>
								
								<td style="text-align:center">
									
									<input type="hidden" id="idprestationExa" name="idprestationExa[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $idPrestationExa;?>"/>
							
								<?php
								$k=0;
								
								if($idFactureMedLabo!=0)
								{
									$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE id_examen=:idexamen ORDER BY valeur');
									$resultValeur->execute(array(
									'idexamen'=>$idPrestationExa
									));
									
									$resultValeur->setFetchMode(PDO::FETCH_OBJ);

									$comptValeur=$resultValeur->rowCount();
									
									if($comptValeur!=0)
									{
									?>
										<select name="valeur" id="valeur" style="width:100px;">
							
											<option value='----' id="nominmax">
											<?php echo 'Select here...';?>
											</option>
											<?php

											while($ligneValeur=$resultValeur->fetch())
											{
											?>
											<option value='<?php echo $ligneValeur->valeur;?>' id='minmax' <?php if(isset($_GET['updateidmedLabo']) AND $ligneValeur->valeur==$valeurLab){ echo "selected='selected'";}else{ echo '';}?>>
											<?php 
											if($ligneValeur->valeur!="")
											{
												echo $ligneValeur->valeur;
											}else{
												echo 'Default value';
											}
											?>
											</option>
											<?php
											}
											?>
										</select>
									
									<?php
									}elseif($comptValeur==0){
									
										if(isset($_GET['updateidmedLabo']))
										{								
											echo "<input type='text' name='valeur' id='valeur' style='width:100px;' value='".$valeurLab."'/>";
										}else{ 
											echo "<input type='text' name='valeur' id='valeur' style='width:100px;' value=''/>";
										}
									
									}
								}
								?>
								</td>								
								
								<td style="text-align:center">	
									<input type="text" id="min" name="min" style="width:80px" value="<?php echo $minresultats;?>"/>
								</td>
								
								<td style="text-align:left">
									<input type="text" id="max" name="max" style="width:80px" value="<?php echo $maxresultats;?>"/>
								
									<?php
									/* if($resultats!="")
									{
									?>
										<a href="<?php echo $resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
										
									<?php
									} */
									?>
								</td>
								
								
								<td style="text-align:center;display:none;">
								
									<input type="file" name="result" id="result" style="border:1px solid #eee; height:40px;width:250px;"/>
								
									<input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
								</td>
								
							</tr>								
						</tbody>						
						
						<tfoot>
							<tr>
								<td colspan=4 style="text-align:center">										
				
									<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="updateresultbtn" class="btn-large">
									<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(32) ?>
									</button>
									
									
									<a href="patients1.php?num=<?php echo $_GET['num'];?>&examenPa=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 40px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></a>
									
									<input type="hidden" name="submitExa" value="<?php echo $_GET['updateidmedLabo'];?>"/>
							
									
								</td>
							</tr>
						</tfoot>
												
					</table>
				</form>
			<?php
				}
			}
			?>
			
			<?php
			if(isset($_GET['examenPa']))
			{
			?>		
				<span style="font-size:250%;"><?php echo getString(99);?></span>
			
			<?php
			}
			?>	
			<table class="<?php  if(isset($_GET['examenPa'])){ echo 'previewprint tablesorter1';}else{ echo 'tablesorter';}?>" cellpadding="5" style="background-color:#FFF;margin-top:10px;">
			<?php
		
			/*-----------Examen à faire---------------*/
				
			if(isset($_GET['examenPa']))
			{
			
				$resultatsExamPa=$connexion->prepare("SELECT *FROM med_labo ml,utilisateurs u, patients p WHERE u.id_u=p.id_u AND ml.numero=p.numero AND (ml.id_prestationExa IS NOT NULL OR ml.autreExamen !='') AND ml.examenfait=0 AND ml.numero=:numPa ORDER BY ml.dateconsu DESC");
				
				$resultatsExamPa->execute(array(
				'numPa'=>$_GET['num']
				));
					
				$resultatsExamPa->setFetchMode(PDO::FETCH_OBJ);
			
				$comptResultExamPa=$resultatsExamPa->rowCount();


				
				$resultatsExamPaEdit=$connexion->prepare("SELECT *FROM med_labo ml,utilisateurs u, patients p, consultations c WHERE u.id_u=p.id_u AND ml.numero=p.numero AND (ml.id_prestationExa IS NOT NULL OR ml.autreExamen !='') AND ml.dateresultats=:annee AND ml.examenfait=1 AND ml.numero=:numPa AND ml.id_factureMedLabo!=0 AND c.prediagnostic='' AND c.id_consu=ml.id_consuLabo AND c.numero=p.numero ORDER BY ml.dateconsu DESC");
				
				$resultatsExamPaEdit->execute(array(
				'numPa'=>$_GET['num'],
				'annee'=>$annee
				));
					
				$resultatsExamPaEdit->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
				$comptResultExamPaEdit=$resultatsExamPaEdit->rowCount();
				

			}else{
				$start_week=strtotime("last week");
				$start_week=date("Y-m-d",$start_week);
								
				$resultatsL=$connexion->prepare("SELECT *FROM utilisateurs u, patients p, med_labo ml WHERE u.id_u=p.id_u AND ml.numero=p.numero AND ml.dateconsu =:annee AND (ml.dateresultats =:annee OR ml.dateresultats ='0000-00-00' OR ml.dateresultats IS NULL) AND (ml.id_prestationExa !=0 OR ml.autreExamen !='') GROUP BY p.numero ORDER BY ml.dateconsu DESC");
				$resultatsL->execute(array(
				'annee'=>$annee
				
				))or die( print_r($connexion->errorInfo()));
			
				
				$resultatsL->setFetchMode(PDO::FETCH_OBJ);
		
				$comptResultL=$resultatsL->rowCount();
			
			}
			
				
			if(!isset($_GET['examenPa']))
			{
				if($comptResultL!=0)
				{
			?>
				<thead> 
					<tr>
						<th>S/N</th>
						<th><?php echo getString(205);?></th>
						<th><?php echo getString(11);?></th>
						<th><?php echo 'Date Consultation';?></th>
						<th style="text-align:right;"><?php echo getString(99);?></th>
						<th>Rapport</th>
					</tr> 
				</thead>
		<?php
				}
				
			}else{
				
				if(isset($_GET['examenPa']))
				{
		?>
					<thead> 
						<tr style="background-color:#A00000;color:white;margin-top:10px;">
							<th style="width:8%"><?php echo getString(97);?></th>
							<th><?php echo getString(19) ?></th>
							<th><?php echo getString(99);?></th>
							<th><?php echo 'Résultats';?></th>
							<th><?php echo 'Valeur';?></th>
							<th><?php echo 'Min';?></th>
							<th><?php echo 'Max'; ?></th>
							<th style="width:8%"><?php echo 'Date Résultats';?></th>
							<th><?php echo 'Done by';?></th>
							<th style="width:15%" colspan=2>Actions</th>
						</tr> 
					</thead>
			<?php
				}
			}
			
			
			if(isset($_GET['examenPa']))
			{
			?>
				<tbody>
				<?php
				if($comptResultExamPa != 0)
				{
					$comptFacture=0;
				?>
				<form method="post" action="traitement_resultats.php?num=<?php echo $_GET['num'];?>&examenPa=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResultat(this)" enctype="multipart/form-data">
					
					<?php				
					$i=0;
					
					while($ligneExamPa=$resultatsExamPa->fetch())
					{
					
						$idassu=$ligneExamPa->id_assuLab;
					
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
								<?php echo $ligneExamPa->dateconsu;?>
								<input type="hidden" id="idmedLaboResult<?php echo $i;?>" name="idmedLaboResult[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneExamPa->id_medlabo;?>"/>
								
								<input type="hidden" id="idprestationExa<?php echo $i;?>" name="idprestationExa[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneExamPa->id_prestationExa;?>"/>
							</td>
														
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneExamPa->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);
								
							if($ligneMed=$resultatsMed->fetch())
							{
								echo $ligneMed->full_name;			
							}
							$resultatsMed->closeCursor();
							?>						
							</td>			
						
							<td>
							<?php 
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneExamPa->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
									}else{								
										$presta=$lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneExamPa->autreExamen;
									$mesure='';
								}
								
								echo $presta;
							
							?>
							</td>
							
							<td>
								<?php 
								if($ligneExamPa->id_factureMedLabo!=0)
								{
								?>
								<input type="text" id="resultats" name="resultats[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;width:130px;margin:15px auto auto;" value="" placeholder="Taper les résultats ici..."/> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
								
								<?php 
								}
								?>
							</td>
							
							<td>
							<?php
							if($ligneExamPa->id_factureMedLabo!=0)
							{
								$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE id_examen=:idexamen ORDER BY valeur');
								$resultValeur->execute(array(
								'idexamen'=>$ligneExamPa->id_prestationExa
								));
								
								$resultValeur->setFetchMode(PDO::FETCH_OBJ);

								$comptValeur=$resultValeur->rowCount();
								
								if($comptValeur!=0)
								{
								?>
									<select name="<?php echo $i;?>" id="valeur<?php echo $i;?>" style="width:100px;" onchange="ShowMinMax()">
						
										<option value='----' id="nominmax">
										<?php echo 'Select here...';?>
										</option>
										<?php
										$j = 0;
										while($ligneValeur=$resultValeur->fetch())
										{
										?>
										<option value='<?php echo $ligneValeur->valeur;?>' id='minmax<?php echo $j;?>'>
										<?php 
										if($ligneValeur->valeur!="")
										{
											echo $ligneValeur->valeur;
										}else{
											echo 'Default value';
										}
										?>
										</option>
										<?php
										$j++;
										}
										?>
									</select>
								
								<?php
								}elseif($comptValeur==0){
								
									echo "<input type='text' name='".$i."' id='valeur'".$i."' style='width:100px;' value=''/>";
								
								}
							}
							?>
							</td>
							
							<td>
								<?php 
								if($ligneExamPa->id_factureMedLabo!=0)
								{
								?>
								<input type="text" id="min<?php echo $i;?>" name="min[]" style="width:60px;" value=""/>
								<?php 
								}
								?>
							</td>
							
							<td>
								<?php 
								if($ligneExamPa->id_factureMedLabo!=0)
								{
								?>
								<input type="text" id="max<?php echo $i;?>" name="max[]" style="width:60px;" value=""/>
								<?php 
								}
								?>
							</td>
							
							
							<td style="display:none">
								<input type="file" name="autreresult[]" id="autreresult" style="border:1px solid #eee; height:40px; padding 2px; width:230px;"/>
								
								<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
							</td>
							
							
							<td>
							<?php
							if($ligneExamPa->dateresultats != '0000-00-00')
							{
								echo $ligneExamPa->dateresultats;
							}
							?>
							</td>

							<td></td>
							
							<td colspan=2>
							<?php
							
							if($ligneExamPa->id_factureMedLabo!=0)
							{
							?>
								<a href="moreresultats.php?num=<?php echo $ligneExamPa->numero;?>&examenPa=ok&idmedLab=<?php echo $ligneExamPa->id_medlabo;?>&idmed=<?php echo $ligneExamPa->id_uM;?>&idassu=<?php echo $ligneExamPa->id_assuLab;?>&dateconsu=<?php echo $ligneExamPa->dateconsu;?>&dateresultats=<?php echo $ligneExamPa->dateresultats;?>&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-plus fa-0.5x fa-fw"> </i> <?php echo 'Add'; ?></a>
								
								
							<?php
							}else{
							?>
								<span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 5px;"><?php echo getString(218) ?></span>
							<?php 
							}
							?>
							</td>
							
						</tr>
						
						<input type='hidden' id='iVal' value='<?php echo $i-1;?>'/>
					<?php
						$comptFacture=$comptFacture+$ligneExamPa->id_factureMedLabo;
						
						$i++;
					}
					$resultatsExamPa->closeCursor();
					
					if($comptFacture!=0)
					{
					?>
											
						<tr>
							<td colspan=11 style="text-align:center">									
								<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="resultatbtn" class="btn-large">
								<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(68) ?>
								</button>
								
							</td>
							
						</tr>
				<?php
					}
				}
				?>
				</form>
				<?php
									
				if($comptResultExamPaEdit != 0)
				{
					while($ligneExamPaEdit=$resultatsExamPaEdit->fetch())//on recupere la liste des éléments
					{
					
						$idassu=$ligneExamPaEdit->id_assuLab;
						
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


					
						if($ligneExamPaEdit->moreresultats!=0)
						{
				?>
						
						<tr style="text-align:center;<?php if($ligneExamPaEdit->id_uL == $_SESSION['id']){ echo "background-color:rgba(0,50,255,0.15);";}else{ echo "background-color:rgba(250,250,0,0.15);";}?>">
						
							<td><?php echo $ligneExamPaEdit->dateconsu;?></td>
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneExamPaEdit->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
							?>	
								<?php echo $ligneMed->full_name;?>
														
							<?php
							}
							$resultatsMed->closeCursor();
							?>						
							</td>
							
							<td>
							
								<?php 
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneExamPaEdit->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;	
									}else{								
										$presta=$lignePresta->nompresta;								
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneExamPaEdit->autreExamen;
									$mesure='';
								}
								
									echo $presta;
								?>
							</td>
								
							<td colspan=4>
							</td>
						
							<td>
							<?php
							if($ligneExamPaEdit->dateresultats != '0000-00-00')
							{
								echo $ligneExamPaEdit->dateresultats;
							}
							?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
							</td>
							
							<td>
							<?php
							$idLabo=$ligneExamPaEdit->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
								if($fullnameLabo==$ligneLaboId->full_name)
								{
									echo "Vous même";
								}else{
									echo $ligneLaboId->full_name;
								}
							}
							
							?>
							</td>
							
							
							<td colspan=2>
							<?php	
							if($ligneExamPaEdit->examenfait==1)
							{
							?>
								<a href="<?php if($ligneExamPaEdit->moreresultats==1){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidmoremedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==2){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidspermomedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==0){ echo 'patients1.php?updateidmedLabo='.$ligneExamPaEdit->id_medlabo.'&idassuLab='.$ligneExamPaEdit->id_assuLab.'&examenPa=ok';}}}?>&num=<?php echo $ligneExamPaEdit->numero;?>&dateconsu=<?php echo $ligneExamPaEdit->dateconsu;?>&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="envoiresult" name="envoiresult" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"> </i> <?php echo getString(32) ?></a>
							<?php
							}
							?>
							</td>
							
							
						</tr>
						
							<?php
							if($ligneExamPaEdit->moreresultats==1)
							{
								$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');		
								$resultMoreMedLabo->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$ligneExamPaEdit->id_medlabo
								));
								
								$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

								$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
								
								while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
								{
												
									$idassuLab=$ligneMoreMedLabo->id_assuLab;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($i=1;$i<=$assuCount;$i++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuLab
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
										}
									}
								?>									
								<tr>
									<td colspan=2></td>
									<td>
									<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												$presta=$lignePresta->namepresta;
											}else{
											
												$presta=$lignePresta->nompresta;
											}
											$mesure=$lignePresta->mesure;
										}else{
											$presta=$ligneMoreMedLabo->autreExamen;
											$mesure='';
										}
										
										echo $presta;
									?>
									</td>
									
									<td>
									<?php echo $ligneMoreMedLabo->autreresultats;?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
									</td>
									
									<td>
									<?php echo $ligneMoreMedLabo->valeurLab;?>
									</td>
									
									<td>
									<?php echo $ligneMoreMedLabo->minresultats;?>
									</td>
									
									<td>
									<?php echo $ligneMoreMedLabo->maxresultats;?>
									</td>
												
									<td style='display:none'>
									<?php
									if($ligneMoreMedLabo->resultats!="")
									{
									?>
										<a href="<?php echo $ligneMoreMedLabo->resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-paperclip fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
									<?php
									}
									?>
									</td>
							
									<td colspan=4></td>
									
								</tr>
							<?php
								}
							}
							
							if($ligneExamPaEdit->moreresultats==2)
							{
															
								$resultSpermoMedLabo=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medlabo=:idmedLab ORDER BY sml.id_spermomedlabo');		
								$resultSpermoMedLabo->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$ligneExamPaEdit->id_medlabo
								));
								
								$resultSpermoMedLabo->setFetchMode(PDO::FETCH_OBJ);

								$comptSpermoMedLabo=$resultSpermoMedLabo->rowCount();
								
								while($ligneSpermoMedLabo=$resultSpermoMedLabo->fetch())
								{
								?>									
								<tr style="background-color:#eee">
									<td colspan=5>EXAMEN MACROSCOPIQUES</td>
									<td style='border-left:1px solid #aaa;' colspan=6>EXAMEN MICROSCOPIQUES</td>
								</tr>
								
								<tr>
									<td>Volume</td>
									<td>Densité</td>
									<td>Viscosité</td>
									<td>PH</td>
									<td>Aspect</td>
									
									<td style='border-left:1px solid #aaa;'>Examen direct</td>
									<td>Mobilité après</td>
									<td>Numération</td>
									<td>V.N</td>
									<td>Spermocytogramme</td>
									<td>Autres</td>
								
								</tr>
								
								<tr>							
									<td>
									<?php echo $ligneSpermoMedLabo->volume;?>
									</td>						
									<td>
									<?php echo $ligneSpermoMedLabo->densite;?>
									</td>						
									<td>
									<?php echo $ligneSpermoMedLabo->viscosite;?>
									</td>						
									<td>
									<?php echo $ligneSpermoMedLabo->ph;?>
									</td>						
									<td>
									<?php echo $ligneSpermoMedLabo->aspect;?>
									</td>
									
									<td style='border-left:1px solid #aaa;'>
									<?php echo $ligneSpermoMedLabo->examdirect;?>
									</td>
									
									<td>							
										<table>
											<tr>
												<td style='border-left:1px solid #aaa;'>0h après emission</td>
												<td>1h après emission</td>
												<td>2h après emission</td>
												<td>3h après emission</td>
												<td style='border-right:1px solid #aaa;'>4h après emission</td>
											</tr>
											
											<tr>
												<td style='border-left:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->zeroheureafter;?>
												</td>
												<td>
												<?php echo $ligneSpermoMedLabo->uneheureafter;?>
												</td>
												<td>
												<?php echo $ligneSpermoMedLabo->deuxheureafter;?>
												</td>
												<td>
												<?php echo $ligneSpermoMedLabo->troisheureafter;?>
												</td>
												<td style='border-right:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->quatreheureafter;?>
												</td>
											</tr>
										</table>
									</td>
									
									<td>
									<?php echo $ligneSpermoMedLabo->numeration;?>
									</td>
									
									<td>
									<?php echo $ligneSpermoMedLabo->vn;?>
									</td>
									
									<td>
										<table>
											<tr>
												<td style='border-left:1px solid #aaa;'>Forme typique</td>
												<td style='border-right:1px solid #aaa;'>Forme atypique</td>
											</tr>
											
											<tr>
												<td style='border-left:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->formtypik;?>
												</td>
												<td style='border-right:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->formatypik;?>
												</td>
											</tr>
										</table>
									</td>						
									<td>
									<?php echo $ligneSpermoMedLabo->autre;?>
									</td>
									
								</tr>
								
								<tr style="background-color:#eee">	
									<td colspan=11>CONCLUSION</td>
									
								</tr>
								<tr>	
									<td colspan=11>
									<?php echo $ligneSpermoMedLabo->conclusion;?>
									</td>
									
								</tr>
								<?php
								}
							}
						}else{
						?>
						
						<tr style="text-align:center;<?php if($ligneExamPaEdit->id_uL == $_SESSION['id']){ echo "background-color:rgba(0,50,255,0.15);";}else{ echo "background-color:rgba(250,250,0,0.15);";}?>">
						
							<td><?php echo $ligneExamPaEdit->dateconsu;?></td>
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneExamPaEdit->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
							?>	
								<?php echo $ligneMed->full_name;?>
														
							<?php
							}
							$resultatsMed->closeCursor();
							?>						
							</td>
							
							<td>
							
								<?php 
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneExamPaEdit->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
									}else{
									
										$presta=$lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneExamPaEdit->autreExamen;
									$mesure='';
								}
									
									echo $presta;
								?>
								
							</td>
							
							<td><?php echo $ligneExamPaEdit->autreresultats;?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span></td>
							
							<td><?php echo $ligneExamPaEdit->valeurLab;?></td>
							
							<td><?php echo $ligneExamPaEdit->minresultats;?></td>
							
							<td><?php echo $ligneExamPaEdit->maxresultats;?></td>
								
							<td style='display:none'>
							<?php
							if($ligneExamPaEdit->resultats!="")
							{
							?>
								<a href="<?php echo $ligneExamPaEdit->resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-paperclip fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
							<?php
							}
							?>
							</td>
								
							<td>
							<?php
							if($ligneExamPaEdit->dateresultats != '0000-00-00')
							{
								echo $ligneExamPaEdit->dateresultats;
							}
							?>
							</td>
							
							<td>
							<?php
							$idLabo=$ligneExamPaEdit->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
								if($fullnameLabo==$ligneLaboId->full_name)
								{
									echo "Vous même";
								}else{
									echo $ligneLaboId->full_name;
								}
							}
							
							?>
							</td>
							
							
							<td>
							<?php	
							if($ligneExamPaEdit->examenfait==1)
							{
							?>
								<a href="<?php if($ligneExamPaEdit->moreresultats==1){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidmoremedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==2){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidmoremedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==0){ echo 'patients1.php?updateidmedLabo='.$ligneExamPaEdit->id_medlabo.'&idassuLab='.$ligneExamPaEdit->id_assuLab.'&examenPa=ok';}}}?>&num=<?php echo $ligneExamPaEdit->numero;?>&dateconsu=<?php echo $ligneExamPaEdit->dateconsu;?>&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="envoiresult" name="envoiresult" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"> </i> <?php echo getString(32) ?></a>
							<?php
							}
							?>
							</td>
							
						</tr>							
						<?php
						}
				
					}
					$resultatsExamPaEdit->closeCursor();
				}

			
			}else{
				
				if($comptResultL!=0)
				{
			?>
					<span style="position:relative; font-size:250%;"><?php echo getString(108);?></span>
					
					<br/>
					<br/>
				
					<?php

					while($ligneL=$resultatsL->fetch())//on recupere la liste des éléments
					{
						
						$doneResultsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c, med_labo ml WHERE u.id_u=p.id_u AND c.numero=p.numero AND ml.numero=p.numero AND ml.numero=:num AND c.id_consu=ml.id_consuLabo AND ml.dateresultats="0000-00-00" ORDER BY c.numero');

						$doneResultsLabo->execute(array(
						'num'=>$ligneL->numero
						));

						$doneResultsLabo->setFetchMode(PDO::FETCH_OBJ);
					
						$comptDoneResultsLabo=$doneResultsLabo->rowCount();
				
					?>
						<tr style="text-align:center;<?php if($comptDoneResultsLabo==0){ echo 'background:rgba(0,100,255,0.5);';}?>">

							<td><?php echo $ligneL->numero;?></td>
							<td><?php echo $ligneL->full_name;?></td>
							<td>
							<?php 
							if($ligneL->sexe=="M")
							{
								echo getString(12);
							}else{
								if($ligneL->sexe=="F")
								echo getString(13);
							}
							?>
							</td>
							
							<td><?php echo $ligneL->dateconsu;?></td>
							<td style="text-align:right;">
							
								<a href="patients1.php?num=<?php echo $ligneL->numero;?>&examenPa=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(109);?></a>
													
							</td>
							
							<td>
							
								<a href="patients_laboreport.php?num=<?php echo $ligneL->numero;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo 'Rapport labo';?></a>
												
							</td>

						</tr>
				<?php
					}
					$resultatsL->closeCursor();
	
				}else{
				?>
						
					<tr>
						<td colspan=5><h3><?php echo getString(190);?></h3></td>
					</tr>
				<?php
				}
				?>
				</tbody>			
			<?php
			}
			?>			
				
			</table>
	
		<?php
		}
		
		if($comptidX!=0)
		{			
		?>
			<?php
		
			$resultatsX=$connexion->prepare("SELECT *FROM utilisateurs u, patients p, med_radio mr WHERE u.id_u=p.id_u AND mr.numero=p.numero AND mr.dateradio <=:annee AND (mr.id_prestationRadio !=0 OR mr.autreRadio !='') GROUP BY p.numero ORDER BY u.nom_u");
			$resultatsX->execute(array(
			'annee'=>$annee
			
			))or die( print_r($connexion->errorInfo()));
		
			
			$resultatsX->setFetchMode(PDO::FETCH_OBJ);
	
			$comptResultX=$resultatsX->rowCount();
	
				
			if(!isset($_GET['radioPa']))
			{
				if($comptResultX!=0)
				{
			?>
				
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:70%;">
			
				<thead> 
					<tr>
						<th>S/N</th> 
						<th><?php echo getString(9);?></th>
						<th><?php echo getString(11);?></th>
						<th style="text-align:center;"><?php echo 'Examen de radiologie';?></th>
					</tr> 
				</thead>
		<?php
				}
				
			}else{
				
				if(isset($_GET['radioPa']))
				{
		?>
			
				<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;">
					<thead> 
						<tr>
							<th><?php echo getString(97);?></th>
							<th><?php echo getString(19) ?></th>
							<th><?php echo 'Examen de radiologie';?></th>
							<th><?php echo 'Date Résultats';?></th>
							<th><?php echo 'Done by';?></th>
							<th>Actions</th>
						</tr> 
					</thead>
			<?php
				}
			}
			
			
			if(isset($_GET['radioPa']))
			{
			?>		
				<h3 style="position:relative; font-size:200%;margin-bottom:15px;"><?php echo 'Examen de radiologie';?></h3>
			
				<tbody>
				<?php
				
				$resultatsRadioPa=$connexion->prepare("SELECT *FROM med_radio mr,utilisateurs u, patients p WHERE u.id_u=p.id_u AND mr.numero=p.numero AND (mr.id_prestationRadio IS NOT NULL OR mr.autreRadio !='') AND radiofait=0 AND mr.numero=:numPa ORDER BY mr.dateconsu DESC");				
				$resultatsRadioPa->execute(array(
				'numPa'=>$_GET['num']
				));
					
				$resultatsRadioPa->setFetchMode(PDO::FETCH_OBJ);
			
				$comptResultRadioPa=$resultatsRadioPa->rowCount();


				$resultatsEditRadioPa=$connexion->prepare("SELECT *FROM med_radio mr,utilisateurs u, patients p WHERE u.id_u=p.id_u AND mr.numero=p.numero AND (mr.id_prestationRadio IS NOT NULL OR mr.autreRadio !='') AND radiofait=1 AND dateradio>=:annee AND mr.numero=:numPa ORDER BY mr.dateconsu DESC");				
				$resultatsEditRadioPa->execute(array(
				'numPa'=>$_GET['num'],
				'annee'=>$annee
				));
					
				$resultatsEditRadioPa->setFetchMode(PDO::FETCH_OBJ);
			
				$comptResultEditRadioPa=$resultatsEditRadioPa->rowCount();


				if($comptResultRadioPa != 0)
				{
				?>
				<form method="post" action="traitement_radiologie.php?num=<?php echo $_GET['num'];?>&radioPa=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResultat(this)" enctype="multipart/form-data">
					
				<?php
				
					while($ligneRadioPa=$resultatsRadioPa->fetch())
					{
					
						$idassu=$ligneRadioPa->id_assuRad;
						
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
						<tr style="text-align:center;<?php /* if($ligneRadioPa->id_factureMedLabo!=0){ echo 'display:none;';} */?>">
						
							<td>
								<?php echo $ligneRadioPa->dateconsu;?>
								<input type="hidden" id="idmedRadioResult" name="idmedRadioResult" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneRadioPa->id_medradio;?>"/>							
							</td>
														
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneRadioPa->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								echo $ligneMed->full_name;			
							}
							$resultatsMed->closeCursor();
							?>						
							</td>			
						
							<td style="width:300px">
								<?php 
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneRadioPa->id_prestationRadio
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;											
										echo $lignePresta->namepresta;
									
									}else{
									
										$presta=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
									}
								}else{
									$presta=$ligneRadioPa->autreRadio;
									echo $ligneRadioPa->autreRadio;
								}
								?>
							</td>
							
							<td>
							<?php
							if($ligneRadioPa->dateradio != '0000-00-00')
							{
								echo $ligneRadioPa->dateradio;
							}
							?>
							</td>

							<td>
							<?php
							$idRadio=$ligneRadioPa->id_uX;
							
							$resultRadioId=$connexion->prepare('SELECT *FROM utilisateurs u, radiologues x WHERE u.id_u=x.id_u AND x.id_u=:idRadio');
							$resultRadioId->execute(array(
							'idRadio'=>$idRadio
							));
								
							$resultRadioId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneRadioId = $resultRadioId->fetch())
							{
								$fullnameRadio=$_SESSION['nom'].' '.$_SESSION['prenom'];
								if($fullnameRadio==$ligneRadioId->full_name)
								{
									echo "Vous même";
								}else{
									echo $ligneRadioId->full_name;
								}
							}
							
							?>
							</td>
							
							<td>
							<?php
							
							if($ligneRadioPa->id_factureMedRadio!=0)
							{ 
							?>
								<a href="patients1.php?num=<?php echo $ligneRadioPa->numero;?>&radioPa=ok&idmedRad=<?php echo $ligneRadioPa->id_medradio;?>&checkradio=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-square-o fa-lg fa-fw"></i> <?php echo "Check"; ?></a>
							
							<?php
							}else{
							?>
								<span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 5px;"><?php echo getString(218) ?></span>
							<?php 
							}
							?>
							</td>
							
						</tr>
					<?php
					}
					$resultatsRadioPa->closeCursor();
					
				}
				?>
				</form>
				
				<?php
				if($comptResultEditRadioPa != 0)
				{
				?>
				<form method="post" action="traitement_radiologie.php?num=<?php echo $_GET['num'];?>&radioPa=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResultat(this)" enctype="multipart/form-data">
					
					<?php				
					while($ligneEditRadioPa=$resultatsEditRadioPa->fetch())
					{
					
						$idassu=$ligneEditRadioPa->id_assuRad;
						
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
						<tr style="text-align:center;<?php /* if($ligneEditRadioPa->id_factureMedLabo!=0){ echo 'display:none;';} */?>">
						
							<td>
								<?php echo $ligneEditRadioPa->dateconsu;?>
								<input type="hidden" id="idmedRadioResult" name="idmedRadioResult" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneEditRadioPa->id_medradio;?>"/>							
							</td>
														
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneEditRadioPa->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								echo $ligneMed->full_name;			
							}
							$resultatsMed->closeCursor();
							?>						
							</td>			
						
							<td style="width:300px">
								<?php 
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneEditRadioPa->id_prestationRadio
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;											
										echo $lignePresta->namepresta;
									
									}else{
									
										$presta=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
									}
								}else{
									$presta=$ligneEditRadioPa->autreRadio;
									echo $ligneEditRadioPa->autreRadio;
								}
								?>
							</td>
							
							<td>
							<?php
							if($ligneEditRadioPa->dateradio != '0000-00-00')
							{
								echo $ligneEditRadioPa->dateradio;
							}
							?>
							</td>

							<td>
							<?php
							$idRadio=$ligneEditRadioPa->id_uX;
							
							$resultRadioId=$connexion->prepare('SELECT *FROM utilisateurs u, radiologues x WHERE u.id_u=x.id_u AND x.id_u=:idRadio');
							$resultRadioId->execute(array(
							'idRadio'=>$idRadio
							));
								
							$resultRadioId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneRadioId = $resultRadioId->fetch())
							{
								$fullnameRadio=$_SESSION['nom'].' '.$_SESSION['prenom'];
								if($fullnameRadio==$ligneRadioId->full_name)
								{
									echo "Vous même";
								}else{
									echo $ligneRadioId->full_name;
								}
							}
							
							?>
							</td>
							
							<td>
								<a href="patients1.php?num=<?php echo $ligneEditRadioPa->numero;?>&radioPa=ok&idmedRad=<?php echo $ligneEditRadioPa->id_medradio;?>&checkeditradio=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-check-square-o fa-lg fa-fw"></i> <?php echo "Uncheck"; ?></a>
							</td>
							
						</tr>
					<?php
					}
					$resultatsEditRadioPa->closeCursor();
					
				}
				?>
				</form>
				<?php
				
			}else{
				
				if($comptResultX!=0)
				{
			?>
					<span style="position:relative; font-size:250%;"><?php echo getString(108);?></span>
					
					<br/>
					<br/>
				
					<?php

					while($ligneX=$resultatsX->fetch())//on recupere la liste des éléments
					{
					?>
						<tr style="text-align:center;">
							<td><?php echo $ligneX->numero;?></td>
							<td><?php echo $ligneX->full_name;?></td>
							<td>
							<?php 
							if($ligneX->sexe=="M")
							{
								echo getString(12);
							}else{
								if($ligneX->sexe=="F")
								echo getString(13);
							}
							?>
							</td>
							
							<td style="text-align:center;">
							
								<a href="patients1.php?num=<?php echo $ligneX->numero;?>&radioPa=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(109);?></a>
													
							</td>
							
						</tr>
				<?php
					}
					$resultatsX->closeCursor();
	
				}else{
				?>
						
					<tr>
						<td colspan=5><h3><?php echo getString(190);?></h3></td>
					</tr>
				<?php
				}
				?>
				</tbody>
			
			<?php
			}
			?>			
				
			</table>
	
		<?php
		}
		}

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
		}


?>

	<?php
	
	/* 
	
	if(!isset($_GET['soinsPa']))
	{
		if($comptidI!=0)
		{
			if($comptResultI!=0)
			{
			
			echo '
			<tr>
				<td>';
		
					$nb_totalI=$connexion->query("SELECT COUNT(*) AS nb_totalI FROM utilisateurs u, patients p WHERE u.id_u=p.id_u GROUP BY p.numero ORDER BY u.nom_u");

					$nb_totalI=$nb_totalI->fetch();
					
					$nb_totalI = $nb_totalI['nb_totalI'];
					// Pagination
					$nb_pagesI = ceil($nb_totalI / $pagination);
					// Affichage
					echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pagesI) . '</p>';
			
			echo '
				</td>
			</tr>
			';
			
			}else{
				echo 'Pas de patients à traiter';
			}
		}
	}
	
	if(!isset($_GET['examenPa'])AND !isset($_GET['sendResult']))
	{
		if($comptidL!=0)
		{
			if($comptResultL!=0)
			{
				
			echo '
			<tr>
				<td>';
		
					$nb_totalL=$connexion->query("SELECT COUNT(*) AS nb_totalL FROM utilisateurs u, patients p WHERE u.id_u=p.id_u GROUP BY p.numero ORDER BY u.nom_u");

					$nb_totalL=$nb_totalL->fetch();
					
					$nb_totalL = $nb_totalL['nb_totalL'];
					// Pagination
					$nb_pagesL = ceil($nb_totalL / $pagination);
					// Affichage
					echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pagesL) . '</p>';
			
			echo '
				</td>
			</tr>
			';			
			
			}else{
				 echo getString(110);
			}
		}
	}
	
	 */
	 
	if($comptidR!=0)
	{
		
		echo '
		<tr>
			<td>';
	
				$nb_totalR=$connexion->query('SELECT COUNT(*) AS nb_totalR FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.nom_u');				
				$nb_totalR=$nb_totalR->fetch();
				
				$nb_totalR = $nb_totalR['nb_totalR'];
				// Pagination
				$nb_pagesR = ceil($nb_totalR / $pagination);
			   // Affichage
				echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pagesR) . '</p>';
		  
		echo '
			</td>
		</tr>
		';
	}
	
	if($comptidM!=0 OR $comptidA!=0)
	{
		echo '
		<tr>
			<td>';
			
			$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.nom_u');
			
			$nb_total=$nb_total->fetch();
			
			$nb_total = $nb_total['nb_total'];
			// Pagination
			$nb_pages = ceil($nb_total / $pagination);
			// Affichage
			echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
		
		echo '
			</td>
		</tr>
		';
	}
	/* 
	if($comptidC!=0)
	{
		
		echo '
		<tr>
			<td>';
			
			$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.nom_u');
			
			$nb_total=$nb_total->fetch();
			
			$nb_total = $nb_total['nb_total'];
			// Pagination
			$nb_pages = ceil($nb_total / $pagination);
			// Affichage
			echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
		
		echo '
			</td>
		</tr>
		';
	}
	 */
	 
	?>
		<?php
		if($comptidC!=0)
		{
		?>
		</div>
	<?php
	}
	?>
	

</div>

<?php
	}
}
?>

<?php
if($comptidD!=0 AND isset($_GET['showresult']))
{
?>
	<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto; padding: 10px; width:80%;">
		<tr>
			<td style="font-size:18px; text-align:center; width:33.333%;">
				<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $nom_uti.' '.$prenom_uti;?>
			</td>
			
			<td style="font-size:18px; text-align:center; width:33.333%;">
				<span style="font-weight:bold;"><?php echo getString(11) ?> : </span><?php echo $sexe;?>
			</td>
			
			<td style="font-size:18px; text-align:center; width:33.333%;">
				<span style="font-weight:bold;">Age : </span><?php echo $an;?>
			</td>
		</tr>
	</table>
<?php
	$idmedlabo=$_GET['idmedLabo'];

	$num=$_GET['num'];
	
	$resultatExam=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_medlabo=:idmedlabo AND ml.numero=:numPa');
	$resultatExam->execute(array(
	'idmedlabo'=>$idmedlabo,
	'numPa'=>$num
	));
	$resultatExam->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
	
	$comptExam=$resultatExam->rowCount();
	
	if($ligneExam=$resultatExam->fetch())
	{
		
?>
		<form method="post" action="traitement_resultats.php?num=<?php echo $num;?>&updateidmedLabo=<?php echo $idmedlabo;?>&idconsu=<?php echo $_GET['idconsu'];?>&dateconsu=<?php echo $_GET['dateconsu'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResult(this)" enctype="multipart/form-data">
			
			
			<table style="margin: 20px auto auto; background: rgb(255, 255, 255) none repeat scroll 0% 0%; width: 100%; border: 1px solid rgb(238, 238, 238); border-radius: 5px; padding: 5px;">
			
				<tr style="background: rgb(248, 248, 248) none repeat scroll 0% 0%; font-size: 15px;">
				
					<td>
					<?php echo getString(129);?> : <?php echo $ligneExam->dateconsu;?>
					</td>
					
					<td>
					
					</td>
					
					
				</tr>
				
				<tr style="background: rgb(248, 248, 248) none repeat scroll 0% 0%; font-size: 15px;">				
					
					<td style="color:#a00000;">
						<?php echo getString(191);?> : <?php echo $ligneExam->dateresultats;?>
					
					</td>
					
					<td><?php echo getString(192);?> : 
					<?php 

					$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=:idL') or die( print_r($connexion->errorInfo()));
					$resultatsLabo->execute(array(
					'idL'=>$ligneExam->id_uL
					));

					$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($ligneLabo=$resultatsLabo->fetch())//on recupere la liste des éléments
					{
						echo ' '.$ligneLabo->nom_u.' '.$ligneLabo->prenom_u;
					}
					?>

					</td>
				
				</tr>
				
				<tr style="background: rgb(248, 248, 248) none repeat scroll 0% 0%; font-size: 15px;">
				
					<td style="font-size: 20px;">
					 
						<span style="font-weight:bold;"><?php echo getString(139) ?>: </span>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
							'prestaId'=>$ligneExam->id_prestationExa
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							if($lignePresta->namepresta!='')
							{
								$presta=$lignePresta->namepresta;										
								echo $lignePresta->namepresta;
							
							}else{
							
								$presta=$lignePresta->nompresta;
								echo $lignePresta->nompresta;
							}
							$mesure=$lignePresta->mesure;
						}else{
							$presta=$ligneExamPa->autreExamen;
							$mesure='';
							
							echo $ligneExamPa->autreExamen;
						}
						
						// echo $_SESSION['id'];
						
					?>
					</td>
					
					<td>
					</td>
				</tr>
				<tr>
					<?php 
					if($ligneExam->autreresultats != "")
					{					
					?>
					<td style="float: left; text-align: left;">
					
						<label style="padding-left: 40px; padding-top: 10px;"><?php echo getString(193) ?></label>
						
						<input type="text" id="examen" name="examen" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php if(isset($_GET['editResult']) OR isset($_GET['idmedLabo'])){echo strip_tags($ligneExam->autreresultats);}else{ echo '';}?>" readonly="readonly"/> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
					</td>
					<?php 
					}
					?>
					
					<?php
					if($ligneExam->resultats!="")
					{
					?>
					<td style="float:left;text-align:left;margin:10px;">
						
						<a href="<?php echo $ligneExam->resultats;?>" class="btn" target="_blank"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo getString(189) ?></a>
					</td>
					<?php
					}
					?>
					
					
				</tr>
				
				<tr align="left">
					
						<td>
							<a href="consult.php?num=<?php echo $_GET['num'];?>&idconsu=<?php echo $_GET['idconsu'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&showmore=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#labotable" style="font-weight: 400; margin: 10px auto auto 20px; padding: 7px 40px 10px;" class="btn-large-inversed"><i class="fa fa-check fa-lg fa-fw"></i> Ok</a>

						</td>
						<td>
							<input type="hidden" name="nomDoc" value="<?php echo $ligneExam->resultats;?>"/>			
							<input type="hidden" name="idupdateLabo" value="<?php echo $ligneExam->id_medlabo;?>"/>
						</td>
				
				</tr>
					
				
			</table>
			
		</form>
	
<?php
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


function ShowSearch(search)
{
	if(search =='byname')
	{
		document.getElementById('results').style.display='inline';
		document.getElementById('resultsSN').style.display='none';
		document.getElementById('resultsRI').style.display='none';
	}
	
	if(search =='bysn')
	{
		document.getElementById('results').style.display='none';
		document.getElementById('resultsSN').style.display='inline';
		document.getElementById('resultsRI').style.display='none';
	}
	
	if(search =='byri')
	{
		document.getElementById('results').style.display='none';
		document.getElementById('resultsSN').style.display='none';
		document.getElementById('resultsRI').style.display='inline';
	}
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

function MoreOptions(fld){

	if(fld.value=="options")
	{
		document.getElementById('newautrePrestaI').style.display='inline';
	}
}


</script>

<?php
	
	}else{
		echo '<script language="javascript"> alert("You\'ve been disabled!!\n Ask the administrator to enable you");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}
}else{
	echo '<script language="javascript">document.location.href="index.php"</script>';
}



	if(isset($_POST['confirmPass']))
	{
		
		if(isset($_GET['receptioniste']))
		{
			$caissrecep= '&receptioniste='.$_GET['receptioniste'];
		}else{
			if(isset($_GET['caissier']))
			{
				$caissrecep= '&caissier='.$_GET['caissier'];
			}else{
				$caissrecep='';
			}
		}

	
		$pass = $_POST['Pass'];
		$iduti = $_SESSION['id'];
				
		$resultats=$connexion->prepare('UPDATE utilisateurs SET password=:pass WHERE id_u=:modifierIduti');
						
		$resultats->execute(array(
		'pass'=>$pass,
		'modifierIduti'=>$iduti
		))or die( print_r($connexion->errorInfo()));
			
		echo '<script type="text/javascript"> alert("Your password have been changed\nYour new password is : '.$pass.'");</script>';
		
	}

	
	
	if(isset($_POST['submitMedinf']))
	{
		$idMedInf=$_GET['idmedInf'];
		$idInf=$_SESSION['id'];
		
		$annee = date('Y').'-'.date('m').'-'.date('d');
		
		$datetoday=$annee;
		
		$updateMedInf=$connexion->prepare("UPDATE med_inf SET soinsfait = '1', id_uI=:idInf, datesoins=:datetoday WHERE id_medinf =:idMedInf");			
		$updateMedInf->execute(array(
		'idMedInf'=>$idMedInf,
		'idInf'=>$idInf,
		'datetoday'=>$datetoday
		))or die( print_r($connexion->errorInfo()));
		
		echo '<script type="text/javascript"> alert("Traitement effectuer avec success");</script>';
			
		echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&soinsPa=ok";</script>';
			
	}
	
	
	if(isset($_POST['submitConsom']))
	{
		if($_POST['nomconsom']!="")
		{
			$numero=$_GET['num'];
			$id_uM=$_GET['iduM'];
			$idMedInf=$_GET['idmedInf'];
			$nomConsom=$_POST['nomconsom'];
			$qteConsom=$_POST['qteconsom'];
			$idconsuInf=$_GET['idconsuInf'];
			

			$idInf=$_SESSION['id'];
			
			$annee = date('Y').'-'.date('m').'-'.date('d');
			
			$datetoday=$annee;
						
			$resultIdMedInf=$connexion->prepare("SELECT * FROM med_inf WHERE id_medinf=:idMedInf");
			
			$resultIdMedInf->execute(array(
			'idMedInf'=>$idMedInf
			
			))or die( print_r($connexion->errorInfo()));
			
			$comptidMedinf = $resultIdMedInf->rowCount();
			
			
			if( $comptidMedinf != 0)
			{
				if(isset($_GET['num']))
				{
					$resultatsPa=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
					$resultatsPa->execute(array(
					'operation'=>$numero
					));
					
					$resultatsPa->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsPa->fetch())
					{
						$idassu=$ligne->id_assurance;
						$bill=$ligne->bill;
					}
					$resultatsPa->closeCursor();

				}
		
				$idfacture=0;
				
				$updateConsom=$connexion->prepare('INSERT INTO med_consom (dateconsu,autreConsom,qteConsom,id_uInfConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom,id_factureMedConsom) VALUES(:dateconsu,:autreConsom,:qteConsom,:idInf,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
				$updateConsom->execute(array(
				'dateconsu'=>$datetoday,
				'autreConsom'=>$nomConsom,
				'qteConsom'=>$qteConsom,
				'idInf'=>$_SESSION['id'],
				'idassu'=>$idassu,
				'bill'=>$bill,
				'numero'=>$numero,
				'id_uM'=>$id_uM,
				'idconsuAdd'=>$idconsuInf,
				'idfacture'=>$idfacture
				)) or die( print_r($connexion->errorInfo()));

				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					echo '<script type="text/javascript"> alert("Consommable ajouté...Success!!!");</script>';
				
					echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok&english='.$_GET['english'].'";</script>';
					
				}else{
				
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						echo '<script type="text/javascript"> alert("Consommable ajouté...Success!!!");</script>';
				
						echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok&francais='.$_GET['francais'].'";</script>';
					
					}else{
						echo '<script type="text/javascript"> alert("Consommable ajouté...Success!!!");</script>';
				
						echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok";</script>';
					}
				}
			
			}
		
		}
	}
	
	
	if(isset($_POST['submitMedoc']))
	{
		if($_POST['nommedoc']!="")
		{
			$numero=$_GET['num'];
			$id_uM=$_GET['iduM'];
			$idMedInf=$_GET['idmedInf'];
			$nomMedoc=$_POST['nommedoc'];
			$qteMedoc=$_POST['qtemedoc'];
			$idconsuInf=$_GET['idconsuInf'];
			

			$idInf=$_SESSION['id'];
			
			$annee = date('Y').'-'.date('m').'-'.date('d');
			
			$datetoday=$annee;

			$resultIdMedInf=$connexion->prepare("SELECT * FROM med_inf WHERE id_medinf=:idMedInf");
			
			$resultIdMedInf->execute(array(
			'idMedInf'=>$idMedInf
			
			))or die( print_r($connexion->errorInfo()));
			
			$comptidMedinf = $resultIdMedInf->rowCount();
			
			
			if( $comptidMedinf != 0)
			{
				if(isset($_GET['num']))
				{
					$resultatsPa=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
					$resultatsPa->execute(array(
					'operation'=>$numero
					));
					
					$resultatsPa->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsPa->fetch())
					{
						$idassu=$ligne->id_assurance;
						$bill=$ligne->bill;
					}
					$resultatsPa->closeCursor();

				}
		
				$idfacture=0;
				
				$updateMedoc=$connexion->prepare('INSERT INTO med_medoc (dateconsu,autreMedoc,qteMedoc,id_uInfMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc,id_factureMedMedoc) VALUES(:dateconsu,:autreMedoc,:qteMedoc,:idInf,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture)');
				$updateMedoc->execute(array(
				'dateconsu'=>$datetoday,
				'autreMedoc'=>$nomMedoc,
				'qteMedoc'=>$qteMedoc,
				'idInf'=>$_SESSION['id'],
				'idassu'=>$idassu,
				'bill'=>$bill,
				'numero'=>$numero,
				'id_uM'=>$id_uM,
				'idconsuAdd'=>$idconsuInf,
				'idfacture'=>$idfacture
				)) or die( print_r($connexion->errorInfo()));

				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					echo '<script type="text/javascript"> alert("Médicament ajouté...Success!!!");</script>';
				
					echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok&english='.$_GET['english'].'";</script>';
					
				}else{
				
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						echo '<script type="text/javascript"> alert("Médicament ajouté...Success!!!");</script>';
				
						echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok&francais='.$_GET['francais'].'";</script>';
					
					}else{
						echo '<script type="text/javascript"> alert("Médicament ajouté...Success!!!");</script>';
				
						echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok";</script>';
					}
				}
			
			}
		
		}
		
	}
	
	if(isset($_POST['deleteMedconsom']))
	{		
				
		$numero = $_GET['num'];
		$idmedInf = $_GET['idmedInf'];
		$idconsuInf = $_GET['idconsuInf'];
		$idassuInf = $_GET['idassuInf'];
		$iduM = $_GET['iduM'];
		$presta = $_GET['presta'];
		$soinsPa = $_GET['soinsPa'];
		$id_uI = $_SESSION['id'];
		
		
		$deleteMedconsom = array();

		foreach($_POST['deleteMedconsom'] as $valeur)
		{
			$deleteMedconsom[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteMedconsom);$i++)
		{
			// echo $deleteMedconsom[$i];
	
			$resultats=$connexion->prepare('DELETE FROM med_consom WHERE id_medconsom=:id_medCo');
		
			$resultats->execute(array(
			'id_medCo'=>$deleteMedconsom[$i]			
			))or die($resultats->errorInfo());
			
			// echo '<script type="text/javascript"> alert("Le consommable '.$deleteMedconsom[$i].' a bien été supprimé");</script>';
		

		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$numero.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuInf.'&idassuInf='.$idassuInf.'&iduM='.$iduM.'&presta='.$presta.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$numero.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuInf.'&idassuInf='.$idassuInf.'&iduM='.$iduM.'&presta='.$presta.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
		
			}else{
				
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$numero.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuInf.'&idassuInf='.$idassuInf.'&iduM='.$iduM.'&presta='.$presta.'&soinsPa='.$soinsPa.'";</script>';
		
		
			}
		}
		
	}
	
	
	if(isset($_POST['deleteMedmedoc']))
	{
				
		$numero = $_GET['num'];
		$idmedInf = $_GET['idmedInf'];
		$idconsuInf = $_GET['idconsuInf'];
		$idassuInf = $_GET['idassuInf'];
		$iduM = $_GET['iduM'];
		$presta = $_GET['presta'];
		$soinsPa = $_GET['soinsPa'];
		$id_uI = $_SESSION['id'];
		
		
		$deleteMedmedoc = array();

		foreach($_POST['deleteMedmedoc'] as $valeur)
		{
			$deleteMedmedoc[] = $valeur;
		}
		
		
		for($i=0;$i<sizeof($deleteMedmedoc);$i++)
		{
			// echo $deleteMedmedoc[$i];
	
			$resultats=$connexion->prepare('DELETE FROM med_medoc WHERE id_medmedoc=:id_medMdo');
		
			$resultats->execute(array(
			'id_medMdo'=>$deleteMedmedoc[$i]			
			))or die($resultats->errorInfo());
			
			// echo '<script type="text/javascript"> alert("Le medicament '.$deleteMedmedoc[$i].' a bien été supprimé");</script>';
		

		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$numero.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuInf.'&idassuInf='.$idassuInf.'&iduM='.$iduM.'&presta='.$presta.'&soinsPa='.$soinsPa.'&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$numero.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuInf.'&idassuInf='.$idassuInf.'&iduM='.$iduM.'&presta='.$presta.'&soinsPa='.$soinsPa.'&francais='.$_GET['francais'].'";</script>';
		
			}else{
				
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$numero.'&idmedInf='.$idmedInf.'&idconsuInf='.$idconsuInf.'&idassuInf='.$idassuInf.'&iduM='.$iduM.'&presta='.$presta.'&soinsPa='.$soinsPa.'";</script>';
		
		
			}
		}
		
	}
?>
</div>

<div> <!-- footer -->
	<?php
		include('footer.html');
	?>
</div> <!-- /footer -->
	
<script>
	var j = parseInt($('#iVal').val());
	var i = 0;
	for(var i=0; i<=j; i++)
	{
		$('#valeur' + i).on('change', function(e){
			var v = e.target.value;
			var k = e.target.name;
			
			var idmedRes = $('#idmedLaboResult' + k).val();
			var idpresta = $('#idprestationExa' + k).val();
			
				$.ajax({
					url:"valeur.php?min=ok&valeur="+v+"&idpresta="+idpresta+"",
					type:"GET",
					data:'valeur'+k+'='+v,
					
					success:function(resultat)
					{
						// alert(resultat);
						
						$('#min' + k).val(resultat);
					}
				});
		
			
				$.ajax({
					url:"valeur.php?max=ok&valeur="+v+"&idpresta="+idpresta+"",
					type:"GET",
					data:'valeur'+k+'='+v,
					
					success:function(resultat)
					{
						// alert(resultat);
						
						$('#max' + k).val(resultat);
					}
				});
		
			
		});
	}
</script>
	
<script>
	
		$('#valeur').on('change', function(e){
			var v = e.target.value;
			var k = e.target.name;
			
			
			var idpresta = $('#idprestationExa').val();
			
				$.ajax({
					url:"valeur.php?min=ok&valeur="+v+"&idpresta="+idpresta+"",
					type:"GET",
					data:'valeur='+v,
					
					success:function(resultat)
					{
						// alert(idpresta);
						
						$('#min').val(resultat);
					}
				});
		
			
				$.ajax({
					url:"valeur.php?max=ok&valeur="+v+"&idpresta="+idpresta+"",
					type:"GET",
					data:'valeur='+v,
					
					success:function(resultat)
					{
						// alert(resultat);
						
						$('#max').val(resultat);
					}
				});
		
			
		});

</script>

</body>

</html>