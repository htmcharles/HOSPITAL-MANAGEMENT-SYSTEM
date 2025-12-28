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
	<title><?php echo 'Edit Exam Results';?></title>
	
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

<style type="text/css">
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
					<form method="post" action="Edit_exam_results.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}?><?php if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="Edit_exam_results.php?english=english&editResult=ok<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['fullname'])){ echo '&fullname='.$_GET['fullname'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="Edit_exam_results.php?francais=francais&editResult=ok<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['fullname'])){ echo '&fullname='.$_GET['fullname'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(29);?></a>
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
	
</div> <!-- /navbar --> <br><br><br><br><br>

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

		<a href="expenses.php?cash=<?php echo $_SESSION['codeCash'];?>&coordi=<?php echo $_SESSION['id'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&expenses=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
			<?php echo 'Expenses';?>
		</a>
	
	<?php
	}
	?>
		<a href="report.php?cash=<?php echo $_SESSION['codeCash'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="font-size:20px;height:40px; padding:10px 40px;margin-left:10px;"><?php echo getString(94);?></a>
	
		<a href="rendezvous1.php?<?php if(isset($_GET['receptioniste'])){ echo 'receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo 'caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="font-size:20px;height:40px; padding:10px 40px;margin-left:5px;"><?php echo 'Rendez-vous';?></a>
	</div>
<?php
}
?>

<?php
if(isset($_SESSION['codeR']) AND !isset($_SESSION['codeCash']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="rendezvous1.php?<?php if(isset($_GET['receptioniste'])){ echo 'receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo 'caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="font-size:20px;height:40px; padding:10px 40px;margin-left:5px;"><?php echo 'Rendez-vous';?></a>
	</div>
<?php
}
?>

<?php
if(isset($_SESSION['codeCash']) AND !isset($_SESSION['codeR']))
{
?>
	<div style="text-align:center;margin-top:20px;">
	
		<a href="patients1_hosp.php?cashHosp=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Hospitalisation';?>
		</a>
	
		<a href="listfacture.php?codeCash=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Factures';?>
		</a>

		<a href="expenses.php?cash=<?php echo $_SESSION['codeCash'];?>&coordi=<?php echo $_SESSION['id'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&expenses=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
			<?php echo 'Expenses';?>
		</a>
	
		<a href="report.php?cash=<?php echo $_SESSION['codeCash'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;"><?php echo getString(94);?></a>
	
		<a href="dettesList.php?codeCash=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Dettes';?>
		</a>
	
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
		&nbsp
		<a href="report.php?codeI=<?php if(isset($_SESSION['codeI'])){ echo $_SESSION['codeI'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Reports';?>
		</a>

		<a href="signvital.php?signvital=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px; margin-left: 10px;">
			<?php echo 'Sign Vital';?>
		</a>

	</div>
<?php
	}
}
?>

<?php
if(isset($_SESSION['codeO']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1_hosp.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Hospitalisation';?>
		</a>

	</div>
<?php
}
?> 

<?php
if(isset($_SESSION['codeM']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="report.php?med=<?php echo $_SESSION['id'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="font-size:20px;height:40px; padding:10px 40px;"><?php echo getString(94);?></a>

		<a href="patients1_hosp.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Hospitalisation';?>
		</a>

		<a href="rendezvous1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="font-size:20px;height:40px; padding:10px 40px;margin-left:5px;"><?php echo 'Rendez-vous';?></a>
	</div>
<?php
}
?>

<?php
if(isset($_SESSION['codeL']))
{
?>
	<div style="text-align:center;margin-top:20px;">

		<!-- <a href="patients1_hosp.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Hospitalisation';?>
		</a> -->
		&nbsp
		<a href="patients_laboreport.php?laboreport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Reports';?>
		</a>

		&nbsp
		<a href="Edit_exam_results.php?editResult=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Edit Exam Results';?>
		</a>

	</div>
		
		

	
	
<?php
}
?>

<?php
if(isset($_SESSION['codeX']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1_hosp.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Hospitalisation';?>
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
//$sqlX=$connexion->query("SELECT *FROM radiologues x WHERE x.id_u='$id'");

if(isset($_GET['receptioniste']))
{
	$sqlR=$connexion->query("SELECT *FROM receptionistes r WHERE r.id_u='$id'");
}else{
	$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");
}
//$sqlA=$connexion->query("SELECT *FROM auditors a WHERE a.id_u='$id'");
$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");
//$sqlO=$connexion->query("SELECT *FROM orthopedistes o WHERE o.id_u='$id'");

$comptidD=$sqlD->rowCount();
$comptidI=$sqlI->rowCount();
$comptidL=$sqlL->rowCount();
//$comptidX=$sqlX->rowCount();

if(isset($_GET['receptioniste']))
{
	$comptidR=$sqlR->rowCount();
	$comptidC=0;
}else{
	$comptidC=$sqlC->rowCount();
	$comptidR=0;
}

//$comptidA=$sqlA->rowCount();
$comptidM=$sqlM->rowCount();
//$comptidO=$sqlO->rowCount();

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
		        // echo $_SESSION['id'];
		        // echo $lignecount;	
			?>
		<style type="text/css">.badge{background: black;}.badge2{background: rgb(160, 0, 0);}</style>
		<?php if($lignecount!=0){?>
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?> || Unread messages: <?php echo $lignecount; ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?> <i class="badge badge">Unread messages: <i class="badge badge2"><?php echo $lignecount; ?></i></i> </a></li>
        <?php }else{?>
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
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge badge"><?php echo $lignecount; ?></i></a>
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

if($comptidD!=0 or $comptidI!=0 or $comptidL!=0 or $comptidX!=0 or $comptidR!=0 or $comptidC!=0 or $comptidA!=0 or $comptidO!=0)
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
		if(isset($_SESSION['codeR']) AND !isset($_SESSION['codeCash']))
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
							<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="<?php echo getString(217);?>"><i class="fa fa-wheelchair fa-lg fa-fw"></i> <?php echo getString(217);?></a></li>
							
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
        // echo 'id:'.$_SESSION['id'];
        // echo 'Numero:'.$lignecount;
		?>
		<style type="text/css">.badge{background: black;}.badge2{background: rgb(160, 0, 0);}</style>
		<?php if($lignecount!=0){?>
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?> || Unread messages: <?php echo $lignecount; ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?> <i class="badge badge">Unread messages: <i class="badge badge2"><?php echo $lignecount; ?></i></i> </a></li>
        <?php }else{?>
        	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
        <?php }?>

		</ul>

		<ul style="margin-top:20px; background:none;border:none;">

		</ul>
			
			<div style="display:none; margin-bottom:20px;" id="divMenuMsg">

				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
				
				 <?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge badge"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
				
				<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

			</div>
			
		</div>


<?php
	}
?>


	<?php  

		if ($comptidL !=0) {
			if(isset($_GET['editResult'])){
		?>

		<h3 style="text-align: center;font-family: century Gothic;">Select Period</h3>
			<div id="selectdatePersoBillReport">
		
			<form action="Edit_exam_results.php?labId=<?php echo $_SESSION['id'];?>&editResult=ok&dmacbillperso=ok&selectPersoBill=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
			
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
		<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
			<tr>
				<td style="padding:5px;" id="ds_calclass"></td>
			</tr>
		</table>

	<?php
			}
		}
	?>


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
                        $dailydateperso = 'ml.dateconsu LIKE \''.$_POST['dailydatebillPerso'].'%\'';

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

                        $dailydateperso = 'ml.dateconsu>=\''.$umwaka.'-'.$ukwezi.'-01\' AND (ml.dateconsu<\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' OR ml.dateconsu LIKE \''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'%\')';

                        $caVisit="monthlyPersoBill";

                        $stringResult=date("F",mktime(0,0,0,$_POST['monthlydatebillPerso'],10))." - ".$_POST['monthlydatebillPersoYear'];

                    }
                }

                if(isset($_POST['searchannualybillPerso']))
                {
                    if(isset($_POST['annualydatebillPerso']))
                    {
                        $year = $_POST['annualydatebillPerso'];

                        $dailydateperso = 'ml.dateconsu >=\''.$year.'-01-01\' AND ml.dateconsu <=\''.$year.'-12-31\'';

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

                        $dailydateperso = 'ml.dateconsu>=\''.$debut.'\' AND (ml.dateconsu<\''.$fin.'\' OR 	ml.dateconsu LIKE \''.$fin.'%\')';

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
            	  //  echo $dailydateperso;
							$resultsEX=$connexion->query('SELECT * FROM med_labo ml,consultations c WHERE ml.id_consuLabo=c.id_consu AND '.$dailydateperso.'');              
							$resultsEX->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

              $compEX=$resultsEX->rowCount();

                if($compEX != 0)
                {
                    ?>

                    <table style="width:100%;">
                        <tr>

                        	 <td style="text-align:left; width:33.333%;">

                                <a href="Edit_exam_results.php?labId=<?php echo $_GET['labId'];?>&editResult=ok&dailydateperso=<?php echo $dailydateperso;?>&stringResult=<?php echo $stringResult;?>&EditingOfExaResults=ok" style="text-align:center" id="dmacbillpersopreview">

                                    <button style="width:250px; margin:auto;" type="submit" name="printbill" id="printbill" class="btn-large-inversed">
                                        <i class="fa fa-pencil fa-lg fa-fw"></i> Edit Results
                                    </button>

                                </a>

                                <input type="hidden" name="dateprint" value="<?php  echo $annee;?>"/>

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
    ?>

    <?php 

    	if(isset($_GET['EditingOfExaResults'])){
    			$dailydateperso = $_GET['dailydateperso'];
    			// $resultsEX=$connexion->query('SELECT * FROM `med_labo` WHERE `autreresultats`  LIKE \'%'.'NON'.'%\''. $dailydateperso);
    			//echo 'SELECT * FROM med_labo ml,consultations c WHERE ml.id_consuLabo=c.id_consu AND '.$dailydateperso.' GROUP BY ml.id_consuLabo';
    			$resultsEX=$connexion->query('SELECT * FROM med_labo ml,consultations c WHERE ml.id_consuLabo=c.id_consu AND '.$dailydateperso.' GROUP BY ml.id_consuLabo');
                $resultsEX->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

                $compEX=$resultsEX->rowCount();

                if($compEX != 0)
                {
    		?>
				  <td style="text-align:center; width:40%;">
                        <span style="position:relative; font-size:150%;"></i> <?php echo $_GET['stringResult'];?></span>
                  </td>
                  <hr>
    		        <form method="POST">
    		        	<h2 style="border-bottom: 2px solid #ddd;padding-bottom: 10px;font-family: century Gothic;">Edit Laboratory Results</h2>
                     <table class="tablesorter tablesorter3" style="width:100%;padding-top: 10px;font-family: century Gothic;">
                        <thead>
                        	<th style="text-align: center;">#</th>
                        	<th style="text-align: center;">Patient No</th>
                        	<th style="text-align: center;">Exam name</th>
                        	<th style="text-align: center;">Date of Results</th>
                        	<th style="text-align: center;">Assurance</th>
                        	<th style="text-align: center;">Laborantist</th>
                        	<th style="text-align: center;">Doctor</th>
                        </thead>
                        <tbody>
                        	<?php $count=1; 
                        	
                        	while($GetResults = $resultsEX->fetch()){
                        		$dateconsu = $GetResults->dateconsu;
                        		?>
                        		<tr style="<?php if($GetResults->HiddenFile==1 AND $_SESSION['id']!=16905){echo "display: none";} ?>">
                        			<td style="text-align: center;"><?php echo $count; ?></td>
                        			<td style="text-align: center;">
                        				<?php
                        				$SelectName = $connexion->prepare("SELECT * FROM utilisateurs u,patients p WHERE u.id_u=p.id_u AND p.numero=:numero");
                        				$SelectName->execute(array('numero'=>$GetResults->numero));
                        				$SelectName->setFetchMode(PDO::FETCH_OBJ);
                        				$GetName = $SelectName->fetch();
                        				 echo $GetName->full_name.'<br><b>('.$GetName->numero.')</b>';
                        				 ?>	
                        			</td>
                        			<td>
                        				<table cellspacing="0" style="width:100%;">
                        					<tr>
                        						<th style="text-align: left;color: white;background: #2c2f258c  !important;">Exam</th>
                        						<th style="text-align: left;color: white;background: #2c2f258c  !important;">Results</th>
                        						<th style="text-align: right;color: white;background: #2c2f258c !important;">Status</th>
                        						<th style="text-align: right;color: white;background: #2c2f258c !important;">Action</th>
                        					</tr>
                        					<?php 
                        							$selectPaExa = $connexion->prepare("SELECT * FROM med_labo WHERE id_consuLabo=:id_consuLabo");
                        							$selectPaExa->execute(array('id_consuLabo'=>$GetResults->id_consuLabo));
                        							$selectPaExa->setFetchMode(PDO::FETCH_OBJ);
                        							$countPexam = $selectPaExa->rowCount();
                        							$cou=1;
                        							while ($lignepa = $selectPaExa->fetch()) {
                        								$getAssuConsu=$connexion->prepare('SELECT * FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
														$getAssuConsu->execute(array(
														'idassu'=>$GetResults->id_assuLab
														));
														
														$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

														if($ligneNomAssu=$getAssuConsu->fetch())
														{
															$presta_assuConsu='prestations_'.$ligneNomAssu->nomassurance;
														}

														//echo $presta_assuConsu;
                        								?>
                        								<tr>
                        									<td style="text-align: left;">
                        									<?php 
					                        					if ($lignepa->id_prestationExa != NULL) {
																	$Result=$connexion->query('SELECT *FROM '.$presta_assuConsu.' p WHERE p.id_prestation='.$lignepa->id_prestationExa.'');
																	$Result->setFetchMode(PDO::FETCH_OBJ);
																	$comptPresta=$Result->rowCount();
																	$GEtPresta = $Result->fetch();
																	$exa = $GEtPresta->nompresta;
																	echo $exa;
                        											// if($exa == "EDS" AND $lignepa->autreresultats=="RAS"){
                        									 	// 		//Get Med_labo id
                        									 	// 		$id_medlabo = $lignepa->id_medlabo;
                        									 	// 		$ReplaceResu = "3-5GB";

                        									 	// 		$updateThis = $connexion->prepare("UPDATE med_labo SET autreresultats=:resu WHERE id_medlabo=:id_medlabo");
                        									 	// 		$updateThis->execute(array('resu'=>$ReplaceResu,'id_medlabo'=>$id_medlabo));
                        									 	// 	}
																}else{
																	echo $GetResults->autreExamen;
																}
                        									 ?>
                        									 </td>
                        									 <td>
                        									 	<?php 
                        									 		if($lignepa->autreresultats != ''){
                        									 			echo $lignepa->autreresultats;
                        									 		}else{
                        									 			echo "---";
                        									 		}
                        									 	?>
                        									 </td>

                        									 <td style="text-align: right;">
                        									 	<?php 
                        									 		if($lignepa->autreresultats != ''){
                        									 			echo'<i class="fa fa-check-circle" style="color:green;"></i>';
                        									 		}else{
                        									 			if($lignepa->moreresultats ==1){
                        									 				echo'<i class="fa fa-check-circle" style="color:green;"></i>';
                        									 			}else{
                        									 				echo'<i class="fa fa-ban flashing" style="color:red;"></i>';
                        									 			}
                        									 		}
                        									 	?>
                        									 </td>
                        								<td style="text-align: right;">
					                        				<a href="Edit_exam_results.php?id_uL=<?php echo $lignepa->id_uL; ?>&labId=<?php echo $_GET['labId'];?>&id_medlabo=<?php echo $lignepa->id_medlabo;?>&editResult=ok&dailydateperso=<?php echo $dailydateperso;?>&numero=<?php echo $lignepa->numero;?>&id_assuLab=<?php echo $lignepa->id_assuLab;?>&id_prestationExa=<?php echo $lignepa->id_prestationExa;?>&dateresultats=<?php echo $lignepa->dateconsu;?>&autreresultats=<?php echo $lignepa->autreresultats;?>&stringResult=<?php echo $_GET['stringResult'];?>&UpdateResults=ok" class="btn" name="UpdateResults"><i class="fa fa-edit"></i></a>
					                        			</td>
                        								</tr>
                        					<?php
                        							}		
                        					?>
                        					<tr>
                        						<td></td>
                        						<td></td>
                        						<td></td>
                        						<td>
                        						<a href="Edit_exam_results.php?dateconsu=<?php echo $dateconsu; ?>&numero=<?php echo $GetResults->numero;?>&id_consuLabo=<?php echo 
                        							$GetResults->id_consuLabo;?>&stringResult=<?php echo $_GET['stringResult'];?>&dailydateperso=<?php echo $_GET['dailydateperso'];?>&editAllExam=ok" style="height: 30px;float: right;padding-right: 5px;">Edit All</a>
                        						</td>
                        					</tr>
                        				</table>
                        			</td>

                        			<td style="text-align: center;"><?php echo $GetResults->dateresultats;?></td>
                        			<td style="text-align: center;"><?php echo $ligneNomAssu->nomassurance;?></td>
                        			<td style="text-align: center;"><?php

                        			$SelectName = $connexion->prepare("SELECT * FROM utilisateurs u,laborantins p WHERE u.id_u=p.id_u AND p.id_u=:numero");
															$SelectName->execute(array('numero'=>$GetResults->id_uL));
															$SelectName->setFetchMode(PDO::FETCH_OBJ);
															if($GetName = $SelectName->fetch()){
															 echo'<b>'. $GetName->full_name.'<br>('.$GetName->codelabo.')</b>';
															}else{
																echo "---";
															}

                        			?></td>                        			

                        			<td style="text-align: center;"><?php

                        			$SelectName = $connexion->prepare("SELECT * FROM utilisateurs u,medecins m WHERE u.id_u=m.id_u AND m.id_u=:numero");
															$SelectName->execute(array('numero'=>$GetResults->id_uM));
															$SelectName->setFetchMode(PDO::FETCH_OBJ);
															if($GetName = $SelectName->fetch()){
															 echo'<b>'. $GetName->full_name.'<br>('.$GetName->codemedecin.')</b>';
															}else{
																echo "---";
															}

                        			?></td>
                        		</tr>
                        	<?php $count++;}?>
                        </tbody>
                    </table>
                </form>
    		<?php
    	}
    }
    ?>


	<?php
		if(isset($_GET['UpdateResults'])){
			$id_medlabo = $_GET['id_medlabo'];
			$numero = $_GET['numero'];
			$id_prestation = $_GET['id_prestationExa'];
			$dateresultats = $_GET['dateresultats'];
			$id_assuLab = $_GET['id_assuLab'];
			$autreresultats = $_GET['autreresultats'];
			$dailydateperso = $_GET['dailydateperso'];
	?>
	<form method="POST" action="Edit_exam_results.php?id_uL=<?php echo $_GET['id_uL']; ?>&stringResult=<?php echo $_GET['stringResult']; ?>&labId=<?php echo $_GET['labId']; ?>&id_medlabo=<?php echo $_GET['id_medlabo'];?>&id_prestationExa=<?php echo $_GET['id_prestationExa'];?>&dateresultats=<?php echo $_GET['dateresultats'];?>&id_assuLab=<?php echo $_GET['id_assuLab'];?>&numero=<?php echo $_GET['numero'];?>&dailydateperso=<?php echo $dailydateperso;?>&autreresultats=<?php echo $_GET['autreresultats'];?>">
		<p style="border-bottom: 2px solid #ddd;padding-bottom: 10px;">Edit Results Of <?php
			$SelectName = $connexion->prepare("SELECT * FROM utilisateurs u,patients p WHERE u.id_u=p.id_u AND p.numero=:numero");
			$SelectName->execute(array('numero'=>$numero));
			$SelectName->setFetchMode(PDO::FETCH_OBJ);
			$GetName = $SelectName->fetch();
			 echo'<b>'. $GetName->full_name.'<br>('.$GetName->numero.')</b>';
		 ?>	
		</p>

		<p>Labo Exam: 
			<?php 
			// echo $GetResults->id_prestationExa;
                $getAssuConsu=$connexion->prepare('SELECT * FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
				$getAssuConsu->execute(array(
				'idassu'=>$id_assuLab
				));
				
				$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsu->fetch())
				{
					$presta_assuConsu='prestations_'.$ligneNomAssu->nomassurance;
				}

				if ($id_prestation != NULL) {
					$Result=$connexion->query('SELECT *FROM '.$presta_assuConsu.' p WHERE p.id_prestation='.$id_prestation.'');
					$Result->setFetchMode(PDO::FETCH_OBJ);
					$comptPresta=$Result->rowCount();
					$GEtPresta = $Result->fetch();
					echo '<b>'.$GEtPresta->nompresta.'</b>';
					$presta = $GEtPresta->nompresta;
				}else{
					$selectNom = $connexion->prepare('SELECT * FROM med_labo WHERE id_medlabo=:id_medlabo');
					$selectNom->execute(array(
						'id_medlabo'=>$id_medlabo
					));
					$selectNom->setFetchMode(PDO::FETCH_OBJ);
					$ligneNompresta= $selectNom->fetch();
					$presta =$ligneNompresta->autreExamen;
					echo $presta;
				}
			?>
		</p>

		<?php
			$selectmed = $connexion->prepare('SELECT * FROM med_labo WHERE id_medlabo=:id_medlabo');
			$selectmed->execute(array(
				'id_medlabo'=>$id_medlabo
			));
			$selectmed->setFetchMode(PDO::FETCH_OBJ);
			$lignemed = $selectmed->fetch();
		?>
		<?php if($_GET['id_prestationExa'] == 7){} ?>
		<input type="text" name="newResults" value="<?php echo $autreresultats;?>">

		<a href="moreresultatsEditExam.php?num=<?php echo $_GET['numero'];?>&idmed=<?php echo $lignemed->id_uM;?>&examenPa=ok&idmedLab=<?php echo $_GET['id_medlabo'];?>&dateconsu=<?php echo $_GET['dateresultats'];?>&idassu=<?php echo $_GET['id_assuLab'];?>&dateresultats=<?php echo $_GET['dateresultats'];?>&editExam=ok&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-plus fa-0.5x fa-fw"> </i> <?php echo 'Add'; ?></a>
								
		<button class="btn-large" name="updates" style="margin-top: -9px;">Update</button>
	</form>

	<?php
	}
	if(isset($_POST['updates'])){
		//echo "string";
		//GET info
		$id_medlabo = $_GET['id_medlabo'];
		$numero = $_GET['numero'];
		$id_prestation = $_GET['id_prestationExa'];
		$dateresultats = $_GET['dateresultats'];
		$id_assuLab = $_GET['id_assuLab'];
		$autreresultats = $_GET['autreresultats'];
		$dailydateperso = $_GET['dailydateperso'];
		$examenfait = 1;

		if($_GET['id_uL'] == NULL){
			$id_uL = $_SESSION['id'];
		}else{
			$id_uL = $_GET['id_uL'];
		}

		//POST info
		$newExamResults = $_POST['newResults'];
		//echo "newExamResults = ".$newExamResults;
		//Update

		$UpdateExamResults = $connexion->prepare("UPDATE med_labo SET autreresultats=:autreresultats, id_uL=:who, dateresultats=:dateresultats, examenfait=:examenfait WHERE id_medlabo=:id_medlabo");
		$UpdateExamResults->execute(array(
			'autreresultats'=>$newExamResults,
			'dateresultats'=>$dateresultats,
			'who'=>$id_uL,
			'examenfait'=>$examenfait,
			'id_medlabo'=>$id_medlabo
		));
		if ($UpdateExamResults) {
			echo "<script>alert('Exam Results Updated Successfuly!');</script>";
			echo '<script type="text/javascript">document.location.href="Edit_exam_results.php?editResult=ok&labId='.$_GET['labId'].'&dailydateperso='.$_GET['dailydateperso'].'&stringResult='.$_GET['stringResult'].'&EditingOfExaResults=ok";</script>';

		}else{
			echo "<script>alert('Exam Results Failed To Update!');</script>";
		}
	}
	?>



		<?php 
			if (isset($_GET['editAllExam'])) {
		?>	

		<form method="POST">
			<h3 style="text-align: center;font-family: century Gothic;">Edit Exam Of Patient</h3>
		<table class="tablesorter tablesorter3" style="font-family: century Gothic;">
			<tr style="color: white;">
				<th style="text-align: center;">#</th>
				<th style="text-align: center;border-right: 1px solid #ddd;">Exam</th>
				<th style="text-align: center;border-right: 1px solid #ddd;" colspan="3">Results</th>
				<th style="text-align: center;">More</th>
			</tr>
			
				<?php
				$GetAllExam = $connexion->prepare("SELECT * FROM med_labo WHERE id_consuLabo=:id AND numero=:num");
				$GetAllExam->execute(array('id'=>$_GET['id_consuLabo'],'num'=>$_GET['numero']));
				$GetAllExam->setFetchMode(PDO::FETCH_OBJ);
				$count = $GetAllExam->rowCount();
				if ($count != 0) {
					$countExam = 1;
					while($ligneExamPaEdit = $GetAllExam->fetch()){
					?>
					<tr>
					<td style="text-align: center;"><?php echo $countExam++; ?></td>
					<td style="text-align: center;font-weight: bold;">
						<?php
						$getAssuConsu=$connexion->prepare('SELECT * FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
							$getAssuConsu->execute(array(
							'idassu'=>$ligneExamPaEdit->id_assuLab
							));
							
							$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

							if($ligneNomAssu=$getAssuConsu->fetch())
							{
								$presta_assuConsu='prestations_'.$ligneNomAssu->nomassurance;
							}

	    					if ($ligneExamPaEdit->id_prestationExa != NULL) {
								$Result=$connexion->query('SELECT *FROM '.$presta_assuConsu.' p WHERE p.id_prestation='.$ligneExamPaEdit->id_prestationExa.'');
								$Result->setFetchMode(PDO::FETCH_OBJ);
								$comptPresta=$Result->rowCount();
								$GEtPresta = $Result->fetch();
								$prestation = $GEtPresta->nompresta;
							}else{
								$prestation = $GetResults->autreExamen;
							}

						?>
						<input type="text" name="prestation" value="<?php echo $prestation; ?>" readonly style="font-weight: bold;">
					<td>
					<?php 
						if($ligneExamPaEdit->moreresultats==0){
					?>
						<td colspan="1"></td>
						<td>
							<input type="hidden" name="id_medlabo[]" value="<?php echo $ligneExamPaEdit->id_medlabo; ?>">
							<input type="hidden" name="dateresultats[]" value="<?php echo $ligneExamPaEdit->dateresultats; ?>">
							<input type="hidden" name="id_uL[]" value="<?php echo $ligneExamPaEdit->id_uL; ?>">
							<input type="text" name="results[]" value="<?php echo $ligneExamPaEdit->autreresultats; ?>">
						</td>
						<td><!-- <a href="moreresultats.php?idmedLab=<?php echo  $ligneExamPaEdit->id_medlabo;?>" class="btn-large"><i class="fa fa-plus-circle"></i></a> -->
							<a href="Edit_exam_results.php?id_uL=<?php echo $ligneExamPaEdit->id_uL; ?>&labId=<?php echo $_SESSION['id'];?>&id_medlabo=<?php echo $ligneExamPaEdit->id_medlabo;?>&editResult=ok&dailydateperso=<?php echo $_GET['dailydateperso'];?>&numero=<?php echo $ligneExamPaEdit->numero;?>&id_assuLab=<?php echo $ligneExamPaEdit->id_assuLab;?>&id_prestationExa=<?php echo $ligneExamPaEdit->id_prestationExa;?>&dateresultats=<?php echo $ligneExamPaEdit->dateconsu;?>&autreresultats=<?php echo $ligneExamPaEdit->autreresultats;?>&stringResult=<?php echo $_GET['stringResult'];?>&UpdateResults=ok" class="btn-large" name="UpdateResults"><i class="fa fa-edit"></i></a>
						</td>
					<?php
						}
					 ?>
					<?php
						if($ligneExamPaEdit->moreresultats==1)
						{
							$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medlabo=:idmedLab GROUP BY id_prestationExa ORDER BY mml.id_moremedlabo');		
							$resultMoreMedLabo->execute(array(
							'num'=>$_GET['numero'],
							'idmedLab'=>$ligneExamPaEdit->id_medlabo
							));
							
							$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
							$countNfs = 1;
							while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
							{
							?>									
							<tr>
								<td colspan=1></td>
								<td style="text-align: center;background: #ddd;"><?php echo $countNfs ;?></td>
								<td style="text-align: center;background: #ddd;">
								<?php
									$resultPresta=$connexion->prepare('SELECT *FROM nfssubexams p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMoreMedLabo->id_prestationExa
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											$presta = $lignePresta->namepresta;
											$ranges = $lignePresta->ranges;
										
										}else{
										
											$presta=$lignePresta->nompresta;
										}
										$ranges = $lignePresta->ranges;
										$mesure=$lignePresta->mesure;
									}else{
										$presta=$ligneMoreMedLabo->autreExamen;
										$mesure='';
									}
									
									echo $presta;
								?>
								</td>
								
								<td style="border:1px solid #ddd;background: #ddd;">
								<input type="hidden" name="id_moremedlabo[]" value="<?php echo $ligneMoreMedLabo->id_moremedlabo; ?>">
								<br>
								<input type="text" name="NfsPresta[]" value="<?php echo $ligneMoreMedLabo->autreresultats; ?>" style="font-weight: bold;">
								<span style="font-size:60%; font-weight:normal;"><?php if($mesure!=''){ echo $mesure;}?></span>
								</td>
																
								<td style="border:1px solid #ddd;background: #ddd;"><span style="height:15px; width:130px; font-weight:bold; font-size:10px;text-align: center;"><?php echo $ranges; ?></span>
								</td>

								<tr>
						<?php
									$countNfs ++;
									}
									?>
										<a href="Edit_exam_results.php?id_uL=<?php echo $ligneExamPaEdit->id_uL; ?>&labId=<?php echo $_SESSION['id'];?>&id_medlabo=<?php echo $ligneExamPaEdit->id_medlabo;?>&editResult=ok&dailydateperso=<?php echo $_GET['dailydateperso'];?>&numero=<?php echo $ligneExamPaEdit->numero;?>&id_assuLab=<?php echo $ligneExamPaEdit->id_assuLab;?>&id_prestationExa=<?php echo $ligneExamPaEdit->id_prestationExa;?>&dateresultats=<?php echo $ligneExamPaEdit->dateconsu;?>&autreresultats=<?php echo $ligneExamPaEdit->autreresultats;?>&stringResult=<?php echo $_GET['stringResult'];?>&UpdateResults=ok" class="btn-large" name="UpdateResults"><i class="fa fa-edit"></i></a>
									<?php
								}
								
							}

							$countExam ++;
						}
						?>
						<tr>
							<td colspan="2">
								<button class="btn-large" name="UpdateAllResult">Update</button>
								<a href="patients_laboreport.php?num=<?php echo $_GET['numero']; ?>" class="btn-large-inversed"><i class="fa fa-print"></i></a>
							</td>
						</tr>
		
		</table>
		</form>
		<?php
			}
		?>


		<!-- Update All Exams On Some Time -->
		<?php 

			if (isset($_POST['UpdateAllResult'])) {
				// Declare Variable

				//print_r($_POST['id_medlabo']);

				$id_medlabo = array();
				$results = array();
				$id_moremedlabo = array();
				$NfsPresta = array();

				$dateresultats = array();
				$id_uL = array();

				$examenfait = 1;

				// store Array in foreach variables

				foreach ($_POST['dateresultats'] as $valuedateresu) {
					$dateresultats [] = $valuedateresu;
				}	

				foreach ($_POST['id_uL'] as $valueid_uL) {
					$id_uL [] = $valueid_uL;
				}		

				if(isset($_POST['id_medlabo'])){

					foreach ($_POST['id_medlabo'] as $valueid_medlabo) {
						$id_medlabo [] = $valueid_medlabo;
					}

					foreach ($_POST['results'] as $valueresults) {
						$results [] = $valueresults;
					}

				for ($i=0; $i < sizeof($id_medlabo); $i++) { 
					// Update med_labo
					if(($dateresultats[$i] !="" OR $dateresultats[$i] !="0000-00-00" OR $dateresultats[$i] != NULL) AND $id_uL[$i]!=NULL){
						//echo "Update dateresultats <br>";
						$UpdateMedlabo = $connexion->prepare('UPDATE med_labo SET autreresultats=:autreresultats WHERE id_medlabo=:id_medlabo');
						$UpdateMedlabo->execute(array('autreresultats'=>$results[$i],'id_medlabo'=>$id_medlabo[$i]));
					}else{
						//echo "string <br>";
						$UpdateMedlabo = $connexion->prepare('UPDATE med_labo SET autreresultats=:autreresultats,dateresultats=:dateresultats,id_uL=:id_uL,examenfait=:examenfait WHERE id_medlabo=:id_medlabo');
						$UpdateMedlabo->execute(array('autreresultats'=>$results[$i],'dateresultats'=>$_GET['dateconsu'],'id_uL'=>$_SESSION['id'],'examenfait'=>$examenfait,'id_medlabo'=>$id_medlabo[$i]));
					}

					//echo $id_medlabo[$i].'<br>';
					
				}

				}

				if(isset($_POST['id_moremedlabo'])){
					foreach ($_POST['id_moremedlabo'] as $valueid_moremedlabo) {
						$id_moremedlabo [] = $valueid_moremedlabo;
					}

					foreach ($_POST['NfsPresta'] as $valueNfsPresta) {
						$NfsPresta [] = $valueNfsPresta;
					}

					for ($i=0; $i < sizeof($id_moremedlabo); $i++) { 
						//Update more_med_labo

						$Updatemoremedlabo = $connexion->prepare("UPDATE more_med_labo SET autreresultats=:autreresultats WHERE id_moremedlabo=:id_moremedlabo");
						$Updatemoremedlabo->execute(array('autreresultats'=>$NfsPresta[$i],'id_moremedlabo'=>$id_moremedlabo[$i]));
						//echo $id_moremedlabo[$i].'<br>';
					}

				}

				// print_r($dateresultats);
				// print_r($id_uL);


				echo "<script>alert('Exams Updated Successfuly');</script>";
				echo '<script text="text/javascript">document.location.href="Edit_exam_results.php?dateconsu='.$_GET['dateconsu'].'&numero='.$_GET['numero'].'&id_consuLabo='.$_GET['id_consuLabo'].'&dailydateperso='.$_GET['dailydateperso'].'&stringResult='.$_GET['stringResult'].'&editAllExam=ok"</script>';

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
					// echo '<script type="text/javascript"> alert("Consommable ajouté...Success!!!");</script>';
				
					echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok&english='.$_GET['english'].'";</script>';
					
				}else{
				
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						// echo '<script type="text/javascript"> alert("Consommable ajouté...Success!!!");</script>';
				
						echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok&francais='.$_GET['francais'].'";</script>';
					
					}else{
						// echo '<script type="text/javascript"> alert("Consommable ajouté...Success!!!");</script>';
				
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
					// echo '<script type="text/javascript"> alert("Médicament ajouté...Success!!!");</script>';
				
					echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok&english='.$_GET['english'].'";</script>';
					
				}else{
				
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						// echo '<script type="text/javascript"> alert("Médicament ajouté...Success!!!");</script>';
				
						echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&idmedInf='.$_GET['idmedInf'].'&idconsuInf='.$_GET['idconsuInf'].'&idassuInf='.$_GET['idassuInf'].'&iduM='.$_GET['iduM'].'&presta='.$_GET['presta'].'&soinsPa=ok&francais='.$_GET['francais'].'";</script>';
					
					}else{
						// echo '<script type="text/javascript"> alert("Médicament ajouté...Success!!!");</script>';
				
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
	<footer style="bottom: 0px; width: 100%; text-align: center; background: #fff; border-top: 1px solid #eee; padding: 10px 0px; vertical-align: middle;">
		<p style="margin:0"><span style="color:#a00000">Medical FIle®</span> is a product of <span style="font-style:bold;">Innovate Solutions Ltd</span>. ©2022-<?php echo date('Y');?> All rights reserved.</p>
	</footer>
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

<script type="text/javascript">
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

	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	
		$('#consult').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#soins').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#consom').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#medoc').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#ortho').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
	</script>
	
</body>

</html>