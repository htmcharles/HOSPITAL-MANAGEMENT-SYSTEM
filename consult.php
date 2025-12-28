<?php
session_start();

include("connect.php");
include("connectLangues.php");

$annee = date('Y').'-'.date('m').'-'.date('d');


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
	
		
	/*-----------Delete From Radio------------*/
	
	$getIdRadio=$connexion->prepare('SELECT *FROM med_radio WHERE id_consuRadio=:id_medR');
	$getIdRadio->execute(array(
	'id_medR'=>$idconsu
	));
	
	$getIdRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurRadio=$getIdRadio->rowCount();

	if($compteurRadio!=0)
	{
		$deleteRadio=$connexion->prepare('DELETE FROM med_radio WHERE id_consuRadio=:id_medR');
			
		$deleteRadio->execute(array(
		'id_medR'=>$idconsu
		
		))or die($deleteRadio->errorInfo());
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

	
	/*-----------Delete From prepostdia------------*/
	
	$getIdPrePoDia=$connexion->prepare('SELECT *FROM prepostdia WHERE id_consudia=:id_medD');
	$getIdPrePoDia->execute(array(
	'id_medD'=>$idconsu
	));
	
	$getIdPrePoDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurPrePoDia=$getIdPrePoDia->rowCount();
		
	if($compteurPrePoDia!=0)
	{
		$deleteMedDia=$connexion->prepare('DELETE FROM prepostdia WHERE id_consudia=:id_medD');
		
		$deleteMedDia->execute(array(
		'id_medD'=>$idconsu
		
		))or die($deleteMedDia->errorInfo());
	
	}

	 
	$deleteConsu=$connexion->prepare('DELETE FROM consultations WHERE id_consu=:id_medConsu');
		
	$deleteConsu->execute(array(
	'id_medConsu'=>$idconsu
	
	))or die($deleteConsu->errorInfo());
	
}

/*----------------Edit Consultations--------------*/


if(isset($_GET['idconsu']))
{

	$resultConsult=$connexion->prepare('SELECT *FROM utilisateurs u, patients p,consultations c WHERE c.id_consu=:operation and p.numero=c.numero AND u.id_u=p.id_u');
	$resultConsult->execute(array(
	'operation'=>$_GET['idconsu']	
	));
	$resultConsult->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligneConsult=$resultConsult->fetch())
	{
		$dateConsu=$ligneConsult->dateconsu;
		$motif=strip_tags($ligneConsult->motif);
		$anamnese=strip_tags($ligneConsult->anamnese);
		$clihist=strip_tags($ligneConsult->clihist);
		$etatPa=strip_tags($ligneConsult->etatpatient);
		$antec=strip_tags($ligneConsult->antecedent);
		$allergie=strip_tags($ligneConsult->allergie);
		$examcli=strip_tags($ligneConsult->examcli);
		$sisympt=strip_tags($ligneConsult->signsymptomes);
		$recomm=strip_tags($ligneConsult->recommandations);
		$poids=$ligneConsult->poids;
		$taille=$ligneConsult->taille;
		$tempera=$ligneConsult->temperature;
		$tensionart=$ligneConsult->tensionart;
		$pouls=$ligneConsult->pouls;
		$oxgen=$ligneConsult->oxgen;
		$prediagno=$ligneConsult->prediagnostic;
		$postdiagno=$ligneConsult->postdiagnostic;
		$codemedecin=$ligneConsult->id_uM;
		$num=$ligneConsult->numero;
		$telephone=$ligneConsult->telephone;
		$telephone=$ligneConsult->telephone;
		$birth_date=$ligneConsult->date_naissance;
		$percent=$ligneConsult->insupercent;
		$idtypeconsult=$ligneConsult->id_typeconsult;
		$prixtypeconsult=$ligneConsult->prixtypeconsult;
		$hospitalized=$ligneConsult->hospitalized;
		$motifhospitalized=$ligneConsult->motifhospitalized;
		$physio=$ligneConsult->physio;
		$motifphysio=$ligneConsult->motifphysio;
		$transfer=$ligneConsult->transfer;
		$motiftransfer=$ligneConsult->motiftransfer;
		$modifierIdConsu=$_GET['idconsu'];
	}
	$resultConsult->closeCursor();
	
	
	$resultRdv=$connexion->prepare('SELECT *FROM rendez_vous r, patients p,medecins m WHERE p.numero=r.numero AND r.id_uM=m.id_u AND r.id_consurdv=:idconsu');
	$resultRdv->execute(array(
	'idconsu'=>$_GET['idconsu']
	));
	$resultRdv->setFetchMode(PDO::FETCH_OBJ);
	
	$comptRdv=$resultRdv->rowCount();
		
	if($comptRdv!=0)
	{
		while($ligneRdv=$resultRdv->fetch())
		{
			$idRdv=$ligneRdv->id_rdv;
			$dateRdv=$ligneRdv->daterdv;
				$anneeRdv=date('Y', strtotime($ligneRdv->daterdv));
				$moisRdv=date('m', strtotime($ligneRdv->daterdv));
				$joursRdv=date('d', strtotime($ligneRdv->daterdv));
			$heureMinRdv=$ligneRdv->heurerdv;
				$heureRdv=date('H', strtotime($ligneRdv->heurerdv));
				$minRdv=date('i', strtotime($ligneRdv->heurerdv));
			$motifRdv=$ligneRdv->motifrdv;
			$statusRdv=$ligneRdv->statusRdv;
		}
		$resultRdv->closeCursor();
	}
	
}



if (isset($_GET['hide'])) {
	$idConsu = $_GET['idconsu'];

	//Update consultation 

	$HideFile = $connexion->prepare("UPDATE consultations SET HiddenFile=1 WHERE id_consu=:id_consu");
	$HideFile->execute(array('id_consu'=>$idConsu));

	echo "<script>alert('This File Hiden Successfuly!')</script>";
	echo '<script type="text/javascript">document.location.href="consult.php?num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idtypeconsult='.$_GET['idtypeconsult'].'&idassuconsu='.$_GET['idassuconsu'].'&consu=ok#fichepatient";</script>';
}

if (isset($_GET['unhide'])) {
	$idConsu = $_GET['idconsu'];

	//Update consultation 

	$HideFile = $connexion->prepare("UPDATE consultations SET HiddenFile=0 WHERE id_consu=:id_consu");
	$HideFile->execute(array('id_consu'=>$idConsu));

	echo "<script>alert('This File UnHidden Successfuly!')</script>";
	echo '<script type="text/javascript">document.location.href="consult.php?num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idtypeconsult='.$_GET['idtypeconsult'].'&idassuconsu='.$_GET['idassuconsu'].'&consu=ok#fichepatient";</script>';
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
	<title>New Consultation</title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
	<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<script src="script.js"></script>
			
			<!------------------------------------>
	<link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />	
			
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
		
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css">
	
	<link href="AdministrationSOMADO/css/form-consultation.css" rel="stylesheet" type="text/css">

	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>

	<!---------------Pagination--------------------->
			
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />		
	
	<script src="myQuery.js"></script>

<script type="text/javascript">
 
function controlFormConsultation(theForm){
	var rapport="";
	
	// rapport +=controlTypeconsult(theForm.typeconsult);
	/* rapport +=controlPoids(theForm.poids);
	rapport +=controlTaille(theForm.taille);
	rapport +=controlTempera(theForm.tempera);
	rapport +=controlPouls(theForm.pouls);
	rapport +=controlTension(theForm.tensionart); */
	// rapport +=controlMotif(theForm.motif);
	// rapport +=controlEtatPa(theForm.etatpa);
	// rapport +=controlAntec(theForm.antec);
	// rapport +=controlSoinsfait(theForm.soinsfait);

		if (rapport != "") {
		alert("Veuillez corrigez les erreurs suivantes:\n" + rapport);
					return false;
		 }
}

function controlTypeconsult(fld){
	var erreur="";

	if(fld.value=="0"){
	erreur="<?php echo getString(113) ?>\n";
	fld.style.background="rgba(255,255,0,0.3)";
	}	
	return erreur;
}

function controlMotif(fld){
	var erreur="";

	if(fld.value.trim()==""){
	erreur="<?php echo getString(154) ?>\n";
	fld.style.background="rgba(255,255,0,0.3)";
	}	
	return erreur;
}

function controlEtatPa(fld){
	var erreur="";

	if(fld.value==""){
	erreur="<?php echo getString(155) ?>\n";
	fld.style.background="rgba(255,255,0,0.3)";
	}	
	return erreur;
}

function controlAntec(fld){
	var erreur="";
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	if(fld.value.trim()==""){
	erreur="<?php echo getString(156) ?>\n";
	fld.style.background="rgba(255,255,0,0.3)";
	} 
	return erreur;	
}

function controlPoids(fld){
	var erreur="";
	// var illegalChar=/[0-9]/;

	if(fld.value.trim()=="")
	{
		erreur="Le poids\n";
		fld.style.background="rgba(255,255,0,0.3)";
	}/* else{
		if(fld.value.trim().match(illegalChar))
			{
				erreur="";
			}else{
				erreur="Invalid poids\n";
				fld.style.color="red";
			}
	} */
	return erreur;	
}

function controlTaille(fld){
	var erreur="";
	// var illegalChar=/[0-9]/;

	if(fld.value.trim()=="")
	{
		erreur="La taille\n";
		fld.style.background="rgba(255,255,0,0.3)";
	}/* else{
		if(fld.value.trim().match(illegalChar))
			{
				erreur="";
			}else{
				erreur="Invalid poids\n";
				fld.style.color="red";
			}
	} */
	return erreur;	
}

function controlTempera(fld){
	var erreur="";

	if(fld.value.trim()==""){
	erreur="La temperature\n";
	fld.style.background="rgba(255,255,0,0.3)";
	} 
	return erreur;	
}

function controlTension(fld){
	var erreur="";

	if(fld.value.trim()==""){
	erreur="La tension arterielle\n";
	fld.style.background="rgba(255,255,0,0.3)";
	} 
	return erreur;	
}

function controlPouls(fld){
	var erreur="";

	if(fld.value.trim()==""){
	erreur="Le pouls\n";
	fld.style.background="rgba(255,255,0,0.3)";
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

  .downArrow{
	position: absolute;
/*	bottom: 60%;
	left: 70%;*/
	margin-left:300px;
	margin-top: -25px;
	color: red;
}
.bounce {
	-moz-animation: bounce 3s infinite;
	-webkit-animation: bounce 3s infinite;
	animation: bounce 3s infinite;
}
@-moz-keyframes bounce {
  0%, 20%, 50%, 80%, 100% {
    -moz-transform: translateY(0);
    transform: translateY(0);
  }
  40% {
    -moz-transform: translateY(-30px);
    transform: translateY(-30px);
  }
  60% {
    -moz-transform: translateY(-15px);
    transform: translateY(-15px);
  }
}
@-webkit-keyframes bounce {
  0%, 20%, 50%, 80%, 100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }
  40% {
    -webkit-transform: translateY(-30px);
    transform: translateY(-30px);
  }
  60% {
    -webkit-transform: translateY(-15px);
    transform: translateY(-15px);
  }
}
@keyframes bounce {
  0%, 20%, 50%, 80%, 100% {
    -moz-transform: translateY(0);
    -ms-transform: translateY(0);
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }
  40% {
    -moz-transform: translateY(-30px);
    -ms-transform: translateY(-30px);
    -webkit-transform: translateY(-30px);
    transform: translateY(-30px);
  }
  60% {
    -moz-transform: translateY(-15px);
    -ms-transform: translateY(-15px);
    -webkit-transform: translateY(-15px);
    transform: translateY(-15px);
  }
}
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

		body{
			font-family: Century Gothic;
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
		//echo '<script text="text/javascript">document.location.href="consult.php?num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&idtypeconsult='.$_GET['idtypeconsult'].'&idassuconsu='.$_GET['idassuconsu'].'&showfiche='.$_GET['showfiche'].'&dateconsu='.$_GET['dateconsu'].'"</script>';

	}
	?>
<?php

	$checkIdConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idconsu');

	$checkIdConsult->execute(array(
	'idconsu'=>$_GET['idconsu']
	));
	
	$checkIdConsult->setFetchMode(PDO::FETCH_OBJ);

	$comptidConsult=$checkIdConsult->rowCount();

	if($comptidConsult != 0)
	{
		$ligne=$checkIdConsult->fetch();
		
		$idConsu = $ligne->id_consu;
		
	}




$id=$_SESSION['id'];

$sqlC=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");

$comptidC=$sqlC->rowCount();
$comptidL=$sqlL->rowCount();

$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $comptidC==0)
{
	if($status==1)
	{

?>

<div class="navbar navbar-fixed-top buttonBill">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<img src="images/logo_large.png" class="brand" />	
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
				<li>			
					<form method="post" action="consult.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'].'&consu=ok';}?><?php if(isset($_GET['idconsuNext'])){ echo '&idconsuNext='.$_GET['idconsuNext'];} if(isset($_GET['idtypeconsult'])){ echo '&idtypeconsult='.$_GET['idtypeconsult'];}?><?php if(isset($_GET['idassuconsu'])){ echo '&idassuconsu='.$_GET['idassuconsu'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['showmore'])){ echo '&showmore='.$_GET['showmore'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="consult.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'].'&consu=ok';}?><?php if(isset($_GET['idconsuNext'])){ echo '&idconsuNext='.$_GET['idconsuNext'];} if(isset($_GET['idtypeconsult'])){ echo '&idtypeconsult='.$_GET['idtypeconsult'];}?><?php if(isset($_GET['idassuconsu'])){ echo '&idassuconsu='.$_GET['idassuconsu'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['showmore'])){ echo '&showmore='.$_GET['showmore']; echo '&dateconsu='.$_GET['dateconsu'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="consult.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'].'&consu=ok';}?><?php if(isset($_GET['idconsuNext'])){ echo '&idconsuNext='.$_GET['idconsuNext'];} if(isset($_GET['idtypeconsult'])){ echo '&idtypeconsult='.$_GET['idtypeconsult'];}?><?php if(isset($_GET['idassuconsu'])){ echo '&idassuconsu='.$_GET['idassuconsu'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['showmore'])){ echo '&showmore='.$_GET['showmore']; echo '&idconsu='.$_GET['idconsu']; echo '&dateconsu='.$_GET['dateconsu'];}?>" class="btn"><?php echo getString(29);?></a>
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



$sqlP=$connexion->query("SELECT *FROM patients p WHERE p.id_u='$id'");
$sqlM=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");
$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");


$comptidP=$sqlP->rowCount();
$comptidM=$sqlM->rowCount();
$comptidI=$sqlI->rowCount();
$comptidL=$sqlL->rowCount();


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
		$poidsPa=$ligne->poidsPa;
		$taillePa=$ligne->taillePa;
		$temperaPa=$ligne->temperaturePa;
		$tensionartPa=$ligne->tensionarteriellePa;
		$poulsPa=$ligne->poulsPa;
		$oxgenPa=$ligne->oxgen;
		$bill=$ligne->bill;
		$idassu=$ligne->id_assurance;
		$province=$ligne->province;
		$district=$ligne->district;
		$secteur=$ligne->secteur;
		$profession=$ligne->profession;
		$site=$_GET['num'];
		
		
		$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($ligne->date_naissance)));
		if (isset($_GET['showmore'])) {
			$selectdateconsu = $connexion->prepare("SELECT * FROM consultations WHERE id_consu=:idconsu");
			$selectdateconsu->execute(array(
				'idconsu'=>$_GET['idconsu']
			));
			$selectdateconsu->setFetchMode(PDO::FETCH_OBJ);
			$dateconsuHist = $selectdateconsu->fetch();
			$histdateconsu = $dateconsuHist->dateconsu;

			$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($histdateconsu)));
		}else{
			$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
		}
		$interval = $datetime1->diff($datetime2);
		
		if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
		{
			$an = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
			$ages = $interval->format('%y ');
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
			$assurancesName = $ligneNomAssu->nomassurance;
			$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
		}
	}


}

if(isset($_GET['idconsuNext']))
{
	$resultConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:operation');
	$resultConsu->execute(array(
	'operation'=>$_GET['idconsuNext']	
	));
	$resultConsu->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligneConsu=$resultConsu->fetch())
	{
		$dateConsu=$ligneConsu->dateconsu;
		$heureconsu=$ligneConsu->heureconsu;
		$motif=strip_tags($ligneConsu->motif);
		$anamnese=strip_tags($ligneConsu->anamnese);
		$clihist=strip_tags($ligneConsu->clihist);
		$etatPa=strip_tags($ligneConsu->etatpatient);
		$antec=strip_tags($ligneConsu->antecedent);
		$allergie=strip_tags($ligneConsu->allergie);
		$examcli=strip_tags($ligneConsu->examcli);
		$sisympt=strip_tags($ligneConsu->signsymptomes);
		$recomm=strip_tags($ligneConsu->recommandations);
		$poids=$ligneConsu->poids;
		$taille=$ligneConsu->taille;
		$tempera=$ligneConsu->temperature;
		$tensionart=$ligneConsu->tensionart;
		$pouls=$ligneConsu->pouls;
		$oxgen=$ligneConsu->oxgen;
		$prediagno=strip_tags($ligneConsu->diagnostic);		
		$id_uM=$ligneConsu->id_uM;
		$numeroPa=$ligneConsu->numero;
		$idtypeconsu=$ligneConsu->id_typeconsult;
		$modifierIdConsu=$_GET['idconsuNext'];
	}
	$resultConsu->closeCursor();
}

?>


<?php
if(isset($_SESSION['codeM']))
{
?>
	<div style="text-align:center;margin-top:20px;margin-bottom:15px;" class="buttonBill">
		
		<a href="report.php?med=<?php echo $_SESSION['id'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="font-size:20px;height:40px; padding:10px 40px;"><?php echo getString(94);?></a>
		
		<a href="rendezvous1.php?med=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="font-size:20px;height:40px; padding:10px 40px;"><?php echo 'Vos rendez-vous';?></a>
	</div>
<?php
}
?>


<div  style="text-align:center; width:90%" class="account-container">

<div id='cssmenu' class="buttonBill">

	<ul style="margin-top:20px;background:none;border:none;">

		<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><b><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</b></a></li>
		
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
        <?php }else{?>
        	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
        <?php }?>


		</ul>

		<ul style="margin-top:20px; margin-bottom:20px; background:none; border:none;" class="buttonBill">

			
			<div style="display:none;" id="divMenuMsg">

				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57) ?></a>
				
				<?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
				
				<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59) ?></a>

			</div>
		</ul>
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


		function ShowList(list)
		{
			
			if( list =='Msg')
			{
				document.getElementById('divMenuMsg').style.display='inline';
			}
			
		}



		function Motdepass(){
			var erreur="";
			// var format=/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
			if(document.getElementById('updatePass').style.display='inline')
			{
				document.getElementById('confirmPass').style.display='inline';
				document.getElementById('Pass').style.display='inline';
				document.getElementById('updatePass').style.display='none';
			
			}

		}

		function controlFormPassword(theForm){
			var rapport="";
			
			rapport +=controlPass(theForm.Pass);

				if (rapport != "") {
				alert("Veuillez corrigez les erreurs suivantes:\n" + rapport);
							return false;
				 }
		}


		function controlPass(fld){
			var erreur="";
			
			if(fld.value=="")
			{
				erreur="Saisir nouveau mot de pass\n";
				fld.style.background="cyan";
			}
			
			return erreur;
		}



		</script>

</div>


<?php
if(isset($_GET['num']))
{
?>
	<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto; padding: 10px; width:95%;">
		<tr>
			<td style="font-size:18px; text-align:center; width:21%;">
				<span style="font-weight:bold;"><?php echo getString(89) ?> : </span><?php echo $nom_uti.' '.$prenom_uti;?>
				<br/>
				<span style="font-weight:bold;margin-left:60px;">( <?php
				 	if($telephone!=''){
				 		echo $telephone;
				 	}else{
				 		echo "none";
				 	}
				 ?> )</span>
			</td>

			<td style="font-size:18px; text-align:center; width:21%;">
				<span style="font-weight:bold;"><?php echo getString(280) ?> : </span><?php echo $assurancesName;?><b>(<?php echo $percent;?>%)</b>
				
			</td>
			
			<td style="font-size:18px; text-align:center; width:21%;">
				<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
				<?php
				if($sexe=="M")
				{
					$sexe = "Male";
				}elseif($sexe=="F"){			
					$sexe = "Female";			
				}else{				
					$sexe="";
				}
					
				 echo $sexe;
				?>
					
			<?php
										
			$getEcho=$connexion->prepare('SELECT * FROM med_radio mr, prestations_private pp WHERE mr.numero=:num AND ((mr.id_prestationRadio IS NULL AND (mr.autreRadio LIKE "echo%" OR mr.autreRadio LIKE "eco%")) OR (mr.id_prestationRadio = pp.id_prestation AND (pp.namepresta LIKE "echo%" OR pp.nompresta LIKE "echo%"))) GROUP BY mr.id_medradio ORDER BY mr.dateconsu DESC LIMIT 5');		
			$getEcho->execute(array(
			'num'=>$_GET['num']
			));

			$comptEcho=$getEcho->rowCount();
			
			$getEcho->setFetchMode(PDO::FETCH_OBJ);
				
			if($comptEcho!=0)
			{
			?>
				<br/>
					
				<span title="View Echographie" name="viewechobtn" class="btn" id="viewecho" onclick="ShowHideEcho('viewecho')" style="display:none"><i class="fa fa-chevron-circle-down fa-lg fa-fw"></i><?php echo 'View Echos done';?></span>
			<?php
			}
			?>	
			</td>
			
			<td style="font-size:18px; text-align:center; width:33.333%;">
			
				<span style="font-weight:bold;">Age : </span><?php echo $an.'<br>(<b>'.$birth_date.'</b>)';?>
			</td>
		</tr>
	</table>
	
	<?php		
	if(!isset($_GET['showmore']))
	{
		if(!isset($_GET['showfiche']))
		{
			if($comptEcho!=0)
			{
	?>
			<form method="post" action="traitement_resultecho.php?num=<?php echo $_GET['num'];?>&idMed=<?php echo $_SESSION['id'];?>&datetoday=<?php echo $annee;?><?php if(isset($_GET['idconsuNext'])){ echo '&idconsuNext='.$_GET['idconsuNext'];}else{ if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}else{ echo '&idconsult='.$idConsu;}} if(isset($_GET['idtypeconsult'])){ echo '&idtypeconsult='.$_GET['idtypeconsult'];} if(isset($_GET['idassuconsu'])){ echo '&idassuconsu='.$_GET['idassuconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data">

			<table id="listecho" cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:auto; height:20%; display:inline-block;" class="printPreview tablesorter3">
				<tr>
					<td style="font-size:18px; text-align:center;" colspan=2>
						<span style="font-weight:bold;"><?php echo getString(226);?>
					</td>
				</tr>
				<?php
				if($comptEcho!=0)
				{
					$r=0;
					
					while($ligneEcho=$getEcho->fetch())
					{
						
				?>
						<tr>			
							<td style="width:80%;font-size:18px; text-align:center;">
							<?php 
							
							$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($ligneEcho->dateconsu)));
							$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
							$interval = $datetime1->diff($datetime2);
							 
							$date = ($interval->format('%y') != 0) ? '%y ans' : '';
							
							if($ligneEcho->id_prestationRadio != NULL)
							{
								if($ligneEcho->nompresta != "")
								{
									$echografi = $ligneEcho->nompresta;
								}else{
									if($ligneEcho->namepresta != "")
									{
										$echografi = $ligneEcho->namepresta;
									}
								}
							}else{
								if($ligneEcho->autreRadio != "")
								{
									$echografi = $ligneEcho->autreRadio;
								}
							}
								
	// echo 'Nombre de temp : '.$interval->format('%y ans,%m mois, %d jour, %h heures, %i minutes %s secondes').'<br/><br/>';
						
						if($interval->format('%y')==0 AND $interval->format('%m')==0 AND $interval->format('%d')==0)
						{
							echo $echografi.' : '.getString(230);
						}else{
							echo $echografi.' : '.getString(227).' '.$interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
						}
							
						/* 
							if(strpos($echografi, "obste") != false)
							{
								echo '<br/> Hahahahahaha';
							}
						*/


	// if( substr(strtolower($presta), 0,4) === "echo" OR substr(strtolower($presta), 0,3) === "eco")
							
							?>
							</td>
							
							<td style="font-size:18px; text-align:left;">	
							<?php						
							if( $ligneEcho->resultatsRad !="")
							{
							?>
								<span id="resultsEcho<?php echo $r;?>" name="resultsEcho<?php echo $r;?>" class="btn" onclick="ResultsEcho(<?php echo $r;?>)" style="display:inline;">View <i class="fa fa-eye fa-lg fa-fw"></i> Results</span>
							<?php							
							}else{
							?>
							<span style="font-size:70%;margin-left:20px;">---No Results---</span>
							<?php
							}
							?>
							
							<span id="noresultsEcho<?php echo $r;?>" name="noresultsEcho<?php echo $r;?>" class="noresultsEcho btn" onclick="NoresultsEcho(<?php echo $r;?>)" style="display:none;"/>Hide <i class="fa fa-chevron-up fa-lg fa-fw"></i> Results</span>
							
							</td>
						</tr>
						<?php 
						if($ligneEcho->resultatsRad != "")
						{
						?>
						<tr>
							<td style="font-size:18px; text-align:center;border-right:none;">
								<textarea name="resultsRad[]" id="resultsRad<?php echo $r;?>" style="display:none; border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc;background: #fafafa none repeat scroll 0% 0%; height: 200px; width: 550px; max-height: 300px; max-width: 550px; min-height: 200px; min-width: 200px; margin-top:5px;" placeholder="<?php if($ligneEcho->id_uM == $_SESSION['id']) { echo "Tapez les résultats ici...";}?>" readonly="readonly" onchange="ShowSaveEcho('saveecho')"><?php if($ligneEcho->resultatsRad != "") { echo strip_tags($ligneEcho->resultatsRad);}?></textarea>
								<input type="text" id="idRad" name="idRad[]" value="<?php echo $ligneEcho->id_medradio;?>" style="display:none;"/>
								
							</td>					
							<td></td>					
						</tr>
				<?php
						}
					
						$r++;
					}
					
				}
				?>
				<tr>
					<td style="font-size:18px; text-align:center;border-bottom:none;" colspan=2>
						<button style="width:200px; margin-top:10px; display:none;" type="submit" name="saveechobtn" id="saveechobtn" class="btn-large">
							<i class="fa fa-save fa-lg fa-fw"></i> <?php echo 'Save changes' ?>
						</button>
						
						<input style="width:100px; margin-top:10px;display:none;" type="text" name="url" id="url" value="<?php $url=$_SERVER['REQUEST_URI']; echo $url;?>">
					</td>
					
				</tr>
				
			</table>
					
				<span title="Hide Echographie" name="hideechobtn" class="btn" id="hideecho" onclick="ShowHideEcho('hideecho')"><i class="fa fa-chevron-circle-up fa-lg fa-fw"></i><?php echo '';?></span>
				
			</form>
	<?php
			}
		}
	}
	?>
<?php
}
?>

<br/>
<?php
if(!isset($_GET['showfiche']) AND !isset($_GET['showmore']))
{

	if($comptidM!=0 AND isset($_GET['consu']))
	{
?>
		<?php
					
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num');		
		$resultConsult->execute(array(
		'num'=>$_GET['num']
		));

		$comptConsult=$resultConsult->rowCount();
		
		$resultConsult->setFetchMode(PDO::FETCH_OBJ);
		
		if($comptConsult!=0)
		{
			if($ligneMedConsult=$resultConsult->fetch())
			{

				// Get Data

				$CheckHiden = $connexion->prepare("SELECT * FROM consultations WHERE id_consu=:id_consu");
				$CheckHiden->execute(array('id_consu'=>$_GET['idconsu']));
				$rowCountH = $CheckHiden->rowCount();
				$CheckHiden->setFetchMode(PDO::FETCH_OBJ);
				$ligneHidden = $CheckHiden->fetch();
		?>
			<a href="consult.php?num=<?php echo $ligneMedConsult->numero?>&showfiche=ok&idconsu=<?php echo $ligneMedConsult->id_consu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient" class="btn-large-inversed buttonBill"><span title="View Profile" name="fichebtn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(100);?></span></a>

			<?php if($ligneHidden->HiddenFile != 1){ ?>

				<a href='consult.php?num=<?php echo $ligneMedConsult->numero?>&idtypeconsult=<?php echo $_GET['idconsu'];?>&idassuconsu=<?php echo $_GET['idassuconsu'];?>&idconsu=<?php echo $_GET['idconsu'];?>&consu=ok&hide=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient' class="btn-large" style="<?php if($_SESSION['id']!=2421){echo "display: none;";} ?>"><span title="View Profile" name="fichebtn"><i class="fa fa-eye-slash fa-lg fa-fw"></i><?php echo getString(293);?></span></a>

			<?php }else{?>
				<a href='consult.php?num=<?php echo $ligneMedConsult->numero?>&idtypeconsult=<?php echo $_GET['idconsu'];?>&idassuconsu=<?php echo $_GET['idassuconsu'];?>&idconsu=<?php echo $_GET['idconsu'];?>&consu=ok&unhide=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient' class="btn-large" style="<?php if($_SESSION['id']!=2421){echo "display: none;";} ?>"><span title="View Profile" name="fichebtn"><i class="fa fa-recycle fa-lg fa-fw"></i>Unhide This File</span></a>

			<?php }?>
			
		<?php 
			}
		}else{
		?>
			<span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 25px;"><?php echo getString(208) ?></span>
		<?php 
		}
		?>

		<div id="forFiche">

		<form style="margin-top:25px" method="post" action="traitement_consult.php?num=<?php echo $num;?>&idMed=<?php echo $_SESSION['id'];?>&datetoday=<?php echo $annee;?><?php if(isset($_GET['idconsuNext'])){ echo '&idconsuNext='.$_GET['idconsuNext'];}else{ if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}else{ echo '&idconsult='.$idConsu;}} if(isset($_GET['idtypeconsult'])){ echo '&idtypeconsult='.$_GET['idtypeconsult'];} if(isset($_GET['idassuconsu'])){ echo '&idassuconsu='.$_GET['idassuconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormConsultation(this)" enctype="multipart/form-data">

			<table style="margin-top:40px; margin-bottom:-10px; width:100%;">
				<tr>
					<td style="text-align:center; width:23.333%;">
					</td>
					<td style="text-align:center; width:43.333%;">
						<span style="position:relative; font-size:250%;"></i> <?php echo getString(112) ?></span>
					</td>
					<td style="font-size:18px; padding-right:20px; text-align:right; width:33.333%;">
						<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php if(isset($_GET['idconsu'])){ echo $dateConsu;}else{ echo $annee;}?>
					</td>
				</tr>			
			</table>
			
			<input type="hidden" name="dateconsu" value="<?php if(isset($_GET['idconsu'])){ echo $dateConsu;}else{ echo $annee;}?>"/>
						
			<fieldset style="text-align:center; background:#ddd;">
			
			<table style="width:98%; margin: 10px auto; background: #f8f8f8 none repeat scroll 0% 0%; border: 1px solid #eee; border-radius: 4px;" cellpadding=20 cellspacing=1>	
				<tr>
					<td style="padding: 20px 10px;" align="center">
					<?php
					if($comptidM!=0 or $comptidI!=0)
					{
					?>
					
						<table style="background:#fff; border:1px solid #eee; border-radius: 4px; margin:auto; padding:5px;" cellpadding=3>
							
							<tr>
								<td><label for="typeconsult"><?php echo getString(113); ?></label></td>
								
								<td style="width:500px">
									<?php
						

			$resultConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.id_consu=:idconsu ORDER BY c.id_consu');		
			$resultConsu->execute(array(
			'num'=>$_GET['num'],
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultConsu->setFetchMode(PDO::FETCH_OBJ);

			if($ligneConsu=$resultConsu->fetch())
			{
				
				$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
						
				$assuCount = $comptAssuConsu->rowCount();
				
				for($i=1;$i<=$assuCount;$i++)
				{
					
					$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
					$getAssuConsu->execute(array(
					'idassu'=>$_GET['idassuconsu']
					));
					
					$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

					if($ligneNomAssu=$getAssuConsu->fetch())
					{
						$presta_assuConsu='prestations_'.strtolower($ligneNomAssu->nomassurance);
					}
				}


			}
								
			/*
									$resultatsPrestaConsultation=$connexion->prepare('SELECT *FROM categopresta_ins cp, '.$presta_assuConsu.' p WHERE p.id_categopresta=1 AND cp.id_categopresta=p.id_categopresta AND p.id_prestation=:idtypeconsult ORDER BY nompresta ASC');						
									$resultatsPrestaConsultation->execute(array(
									'idtypeconsult'=>$_GET['idtypeconsult']
									))or die( print_r($connexion->errorInfo()));
									$resultatsPrestaConsultation->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
									
									if($lignePrestaConsultation=$resultatsPrestaConsultation->fetch())//on recupere la liste des éléments
									{
									?>
										<input type="text" name="typeconsult" id="typeconsult" style="background:#fbfbfb; border:1px solid #ddd; height:40px;" value="<?php echo $lignePrestaConsultation->nompresta;?>" <?php if(isset($_GET['idconsuNext']) and $idtypeconsu == $lignePrestaConsultation->id_prestation){ echo $lignePrestaConsultation->nompresta;}?><?php if(isset($_GET['idconsu']) and $idtypeconsult == $lignePrestaConsultation->id_prestation){ echo $lignePrestaConsultation->nompresta;}?> readonly="readonly"/>
										
										<input type="text" name="prixtypeconsult" id="prixtypeconsult" style="background:#fbfbfb; border:1px solid #ddd; height:40px;display:none;" value="<?php echo $prixtypeconsult;?>" readonly="readonly"/>
									<?php
									}
			*/
									?>
									
									
									<select name="typeconsult" id="typeconsult" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:500px;" onchange="NewTypeConsult('typeconsult')">

										<!--
										<option value='0'><?php echo getString(114); ?></option>
										
										-->
								<?php
								
										
								$resultatsPrestaConsultation=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuConsu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=1 ORDER BY p.id_prestation');
								
								$resultatsPrestaConsultation->setFetchMode(PDO::FETCH_OBJ);
							
								if($ligneCatPrestaConsultation=$resultatsPrestaConsultation->fetch())
								{
									echo '<optgroup label="'.$ligneCatPrestaConsultation->nomcategopresta.'">';
									
									?>
										<option value="<?php echo $ligneCatPrestaConsultation->id_prestation;?>" <?php if(isset($_GET['idconsu']) and $_GET['idtypeconsult'] == $ligneCatPrestaConsultation->id_prestation){ echo "selected='selected'";}?>>
											<?php echo $ligneCatPrestaConsultation->nompresta;?>
										</option>
									<?php

									while($lignePrestaConsultation=$resultatsPrestaConsultation->fetch())//on recupere la liste des éléments
									{
									?>
										<option value="<?php echo $lignePrestaConsultation->id_prestation;?>" <?php if(isset($_GET['idconsu']) and $_GET['idtypeconsult'] == $lignePrestaConsultation->id_prestation){ echo "selected='selected'";}?>>
											<?php echo $lignePrestaConsultation->nompresta;?>
										</option>
								<?php
									}
									
										echo '</optgroup>';
								}
								?>
										<option value='autretypeconsult' id='autretypeconsult'><?php echo getString(120); ?></option>
									</select>
								</td>
							</tr>

							<tr>
								<td></td>
								<td>
									<input type="text" id="areaAutretypeconsult" name="areaAutretypeconsult" placeholder="<?php echo 'Inserer autre type de consultation'; ?>" style="border:1px solid #ddd; height:30px; width:500px;display:none;"/>
									
								</td>
								
							</tr>
						</table>
						
						<table style="margin:30px auto auto; width:70%; padding-left:80px;" cellpadding=3>
							<tr>
								<td style="text-align:center; width:20%;"><label style="margin:auto;"for="poids"><?php echo getString(115) ?></label></td>
								<td style="text-align:center; width:20%;"><label style="margin:auto;"for="taille"><?php echo getString(238); ?></label></td>
								<td style="text-align:center; width:20%;"><label for="tempera"><?php echo getString(116) ?></label></td>
								<td style="text-align:center; width:20%;"><label for="oxgen"><?php echo 'oxgen'; ?></label></td>
								<td style="text-align:center; width:20%;"><label for="tensionart"><?php echo getString(117) ?></label></td>
								<td style="text-align:center; width:20%;"><label for="pouls"><?php echo getString(239); ?></label></td>
							</tr>
							
							<tr>						
								<td style="text-align:left;color:black;font-weight:bold;">
									<input type="text" name="poids" id="poids"  value="<?php echo $poids;?>" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?> style="width:35px;font-weight:bold;"/><span style="line-height:37px;">Kg</span>
									
								</td>
								
								<td style="text-align:left;color:black;font-weight:bold;">
									<input type="text" name="taille" id="taille"  value="<?php echo $taille;?>" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?> style="width:35px;font-weight:bold;"/><span style="line-height:37px;">Cm</span>
									
								</td>
													
								<td style="text-align:left;color:black;font-weight:bold;">
									<input type="text" name="tempera" id="tempera"  value="<?php echo $tempera;?>" <?php  if(isset($_GET['num']) and $comptidI!=0){ echo 'readonly="readonly"';}else{echo '';}?> style="width:35px;font-weight:bold;"/><span style="line-height:37px;">°C</span>
								</td>

								<td style="text-align:left;color:black;font-weight:bold;">
									<input type="text" name="oxgen" id="oxgen"  value="<?php echo $oxgen;?>" <?php  if(isset($_GET['num']) and $comptidI!=0){ echo 'readonly="readonly"';}else{echo '';}?> style="width:35px;font-weight:bold;"/><span style="line-height:37px;">O2</span>
								</td>
								
								<td style="text-align:left;color:black;font-weight:bold;">
									<input type="text" name="tensionart" id="tensionart" value="<?php echo $tensionart;?>" <?php  if(isset($_GET['num']) and $comptidI!=0){ echo 'readonly="readonly"';}else{echo '';}?> style="width:35px;font-weight:bold;"/><span style="line-height:37px;">mmHg</span>
									
								</td>
								
								<td style="text-align:left;color:black;font-weight:bold;">
									<input type="text" name="pouls" id="pouls" value="<?php echo $pouls;?>" <?php  if(isset($_GET['num']) and $comptidI!=0){ echo 'readonly="readonly"';}else{echo '';}?> style="width:35px;font-weight:bold;"/><span style="line-height:37px;">/min</span>
									
								</td>
								
							</tr>
						</table>
						
						<!--
						<table class="cons-info" cellpadding=3 style="width:auto; height:auto;" align="center">
							<tr>
								<td><label for="motif"><?php echo getString(154) ?></label></td>
							</tr>
							
							<tr>							
								<td>
									<textarea style="max-width:200px; min-width:200px;" id="motif" name="motif" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $motif;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $motif;}else{ if(isset($_GET['idconsuNext'])){echo $motif;}else{ echo '';}}}?></textarea>
								</td>							
							</tr>
						</table>
						-->
						
			
						<table class="cons-table" cellpadding=3 style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;" align="center" id="motifTable">	
							<tr>
								<td><label for="motif"><?php echo getString(154) ?></label></td>
							</tr>
							
							<tr>
								<td>
									<select style="margin:auto;text-align:center;" multiple="multiple" name="motif[]" class="chosen-select" id="motif">
									<?php
								
									$resultatsMotif=$connexion->query('SELECT *FROM motifs m ORDER BY m.nommotif');
									
									$resultatsMotif->setFetchMode(PDO::FETCH_OBJ);
									
									$comptPrediagno=$resultatsMotif->rowCount();
									
									while($ligneMotif=$resultatsMotif->fetch())
									{
									?>
										<option value='<?php echo $ligneMotif->id_motif;?>' style="text-align:center;">
										<?php
										echo $ligneMotif->nommotif;
										?>							
										</option>
									<?php
									}
									?>
										
									</select>
								
									<input type="submit" style="height:35px; margin:0;visibility:visible;" id="addMotif" name="addMotif" value="<?php echo getString(125) ?>" class="btn"/>
									
								</td>
							</tr>

							<tr>
								<td>
									<input type="text" style="height:35px; display:inline;" id="areaMotif" name="areaMotif" placeholder="<?php echo 'Autre motif ici....';?>"/>
									
									<input type = "submit" style="height:35px; margin:0;visibility:visible;" id="addAutreMotif" name="addAutreMotif" value="<?php echo getString(266) ?>" class="btn"/>
								</td>
								
							</tr>
							<?php
							
							$medMotif=$connexion->prepare('SELECT *FROM med_motif mm WHERE mm.id_consumotif=:idConsu AND mm.id_uM=:idM AND mm.numero=:num AND (mm.autremotif!="" OR mm.id_motif IS NOT NULL) ORDER BY mm.id_medmotif');		
							$medMotif->execute(array(
							'idConsu'=>$modifierIdConsu,
							'idM'=>$_SESSION['id'],
							'num'=>$_GET['num']
							));
							
							$medMotif->setFetchMode(PDO::FETCH_OBJ);

							$comptMedMotif=$medMotif->rowCount();
								
							if($comptMedMotif!=0)
							{	
							?>
							<tr>
								<td style="vertical-align: top;">			
									<div style="font-size:13px; overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
									
										<table class="tablesorter" cellspacing="0"> 
											<thead> 
												<tr>
													<th colspan=10><?php echo getString(266);?></th>
												</tr> 
											</thead>
											
											<tbody>	
											<?php
											try
											{
											?>
												<tr style="text-align:center;">
												<?php
												while($ligneMedMotif=$medMotif->fetch())
												{
												?>	
													<td>
													<?php
													$resultMotif=$connexion->prepare('SELECT *FROM motifs m WHERE m.id_motif=:motifId');		
													$resultMotif->execute(array(
													'motifId'=>$ligneMedMotif->id_motif
													));
													
													$resultMotif->setFetchMode(PDO::FETCH_OBJ);

													$comptMotif=$resultMotif->rowCount();
													
													if($ligneMotif=$resultMotif->fetch())
													{
														$nomMotif=$ligneMotif->nommotif;	
													}else{
														$nomMotif=$ligneMedMotif->autremotif;
													}
													
													echo $nomMotif;
													?>
													<br/>
													<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedmotif[]" value="<?php echo $ligneMedMotif->id_medmotif; ?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
														
													</td>
												<?php
												}
												$medMotif->closeCursor();
												?>
												</tr>

											<?php
												
											}

											catch(Excepton $e)
											{
												echo 'Erreur:'.$e->getMessage().'<br/>';
												echo'Numero:'.$e->getCode();
											}
											?>
											</tbody>
										</table>
									</div>
								</td>					
							</tr>
														
							<?php	
							}
							?>
							
						</table>
			
						<table class="cons-info" cellpadding=3>				
							<tr>
								<td><label for="anamnese"><?php echo 'Anamnèse'; ?></label></td>
								<td><label for="clihist"><?php echo 'Clinical History'; ?></label></td>
								<td style="display:none;"><label for="etatpa"><?php echo getString(155) ?></label></td>
								<td><label for="antec"><?php echo getString(156) ?></label></td>
								<td><label for="allergie"><?php echo 'Allergie'; ?></label></td>
								<td><label for="examcli"><?php echo 'Examen Clinique'; ?></label></td>							
								<td style="display:none;"><label for="sisympt"><?php echo getString(157) ?></label></td>
							</tr>
							
							<tr>							
								<td>
									<textarea style="max-width:200px; min-width:200px;" id="anamnese" name="anamnese" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $anamnese;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $anamnese;}else{ if(isset($_GET['idconsuNext'])){echo $anamnese;}else{ echo '';}}}?></textarea>
								</td>
								
								<td>
									<textarea style="max-width:200px; min-width:200px;" id="clihist" name="clihist" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $clihist;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $clihist;}else{ if(isset($_GET['idconsuNext'])){echo $clihist;}else{ echo '';}}}?></textarea>
								</td>

								<td style="display:none;">
									<textarea style="max-width:200px; min-width:200px;" id="etatpa" name="etatpa" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $etatPa;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $etatPa;}else{ if(isset($_GET['idconsuNext'])){echo $etatPa;}else{ echo '';}}}?></textarea>
								</td>

								<td>
									<textarea style="max-width:200px; min-width:200px;" id="antec" name="antec" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $antec;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $antec;}else{ if(isset($_GET['idconsuNext'])){echo $antec;}else{ echo '';}}}?></textarea>
								</td>
								
								<td>
									<textarea style="max-width:200px; min-width:200px;" id="allergie" name="allergie" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $allergie;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $allergie;}else{ if(isset($_GET['idconsuNext'])){echo $allergie;}else{ echo '';}}}?></textarea>
								</td>
								
								<td>
									<textarea style="max-width:200px; min-width:200px;" id="examcli" name="examcli" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $examcli;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $examcli;}else{ if(isset($_GET['idconsuNext'])){echo $examcli;}else{ echo '';}}}?></textarea>
								</td>
								
								<td style="display:none;">
									<textarea style="max-width:200px; min-width:200px;" id="sisympt" name="sisympt"<?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $sisympt;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $sisympt;}else{ if(isset($_GET['idconsuNext'])){echo $sisympt;}else{ echo '';}}}?></textarea>
								</td>
															
							</tr>
					
							
							<?php
							if(isset($_GET['idconsu']))
							{
							?>
							<input type="hidden" name="idConsult" value="<?php echo $_GET['idconsu'];?>"/>
							<?php
							}
							?>
					
						</table>					
					<?php
					}
					?>
						
					</td>
								
				</tr>				
			</table>
			
			<table class="cons-info" style="padding: 5px; margin: 20px auto; background: #eee none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:50%;"  cellpadding=5 id="diagno">			
				<tr>
					<td><label for="prediagno"><?php echo getString(246) ?></label></td>
				</tr>
				
				<tr>
					<td>
						<select style="margin:auto" multiple="multiple" name="prediagno[]" class="chosen-select" id="prediagno">
						<?php
					
						$resultatsPrediagno=$connexion->query('SELECT *FROM diagnostic d ORDER BY d.nomdiagno');
						
						$resultatsPrediagno->setFetchMode(PDO::FETCH_OBJ);
						
						$comptPrediagno=$resultatsPrediagno->rowCount();
						
						while($lignePrediagno=$resultatsPrediagno->fetch())
						{
						?>
							<option style="text-align:center;" value='<?php echo $lignePrediagno->id_diagno;?>'>
							<?php
							echo $lignePrediagno->nomdiagno;
							?>							
							</option>
						<?php
						}
						?>
							
						</select>
					
						<input type="submit" style="height:35px; margin:0;visibility:visible;" id="addPrediagno" name="addPrediagno" value="<?php echo getString(125) ?>" class="btn"/>
						
					</td>
				</tr>

				<tr>
					<td>
						<input type="text" style="height:35px; width:70%;display:block;" id="areaPrediagno" name="areaPrediagno" placeholder="<?php echo getString(248);?>"/>
						
						<input type = "submit" style="height:35px; margin:0;visibility:visible;" id="addAutrePrediagno" name="addAutrePrediagno" value="<?php echo getString(266) ?>" class="btn"/>
					</td>
					
				</tr>
				<?php
		
				$medDia=$connexion->prepare('SELECT *FROM prepostdia p WHERE p.id_consudia=:idConsu AND p.id_uM=:idM AND p.numero=:num AND (p.autrepredia!="" OR p.id_predia IS NOT NULL) ORDER BY p.id_dia');		
				$medDia->execute(array(
				'idConsu'=>$modifierIdConsu,
				'idM'=>$_SESSION['id'],
				'num'=>$_GET['num']
				));
				
				$medDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptMedDia=$medDia->rowCount();
					
				if($comptMedDia!=0)
				{		
				?>
				<tr>
					<td style="vertical-align: top;">			
						<div style="font-size:13px; overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
						
							<table class="tablesorter" cellspacing="0"> 
								<thead> 
									<tr>
										<th colspan=10><?php echo getString(246);?></th>
									</tr> 
								</thead> 
							
							
								<tbody>	
								<?php
								try
								{
								?>
									<tr style="text-align:center;">
									<?php
									while($ligneMedDia=$medDia->fetch())
									{
									?>	
										<td>
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedDia->id_predia
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											$presta=$lignePresta->nomdiagno;	
										}else{
											$presta=$ligneMedDia->autrepredia;
										}
										
										echo $presta;
										?>
										<br/>
										<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedpredia[]" value="<?php echo $ligneMedDia->id_dia; ?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
											
										</td>
									<?php
									}
									$medDia->closeCursor();
									?>
									</tr>

								<?php
									
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
								</tbody>
							</table>
						</div>
					</td>					
				</tr>
											
				<?php	
				}
				?>
				
			</table>
			</fieldset>
	

		<div id="forBilling">
					
			<legend style="margin:20px auto auto;">
		
			<?php
			
			$consult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu AND c.id_uM=:idM AND c.numero=:num ORDER BY c.id_consu');		
			$consult->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$consult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptConsult=$consult->rowCount();
			
			if($lineConsult=$consult->fetch())
			{
				$consuDate=$lineConsult->dateconsu;
			}
		
		if(isset($_GET['idconsu']))
		{
			
			$medConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idConsu AND mc.numero=:num ORDER BY mc.id_medconsu');		
			$medConsult->execute(array(
			'idConsu'=>$_GET['idconsu'],
			'num'=>$_GET['num']
			));
			
			$medConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptMedConsult=$medConsult->rowCount();
			
			
		
			$medInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_consuInf=:idConsu AND mi.numero=:num ORDER BY mi.id_medinf');		
			$medInf->execute(array(
			'idConsu'=>$_GET['idconsu'],
			'num'=>$_GET['num']
			));
			
			$medInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptMedInf=$medInf->rowCount();
			
		
		
		
			$medPsy=$connexion->prepare('SELECT *FROM med_psy mp WHERE mp.id_consuPsy=:idConsu AND mp.id_uM=:idM AND mp.numero=:num ORDER BY mp.id_medpsy');		
			$medPsy->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medPsy->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptMedPsy=$medPsy->rowCount();
			
		
			$medSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.id_consuSurge=:idConsu AND ms.numero=:num ORDER BY ms.id_medsurge');		
			$medSurge->execute(array(
			'idConsu'=>$idConsu,
			'num'=>$_GET['num']
			));
			
			$medSurge->setFetchMode(PDO::FETCH_OBJ);

			$comptMedSurge=$medSurge->rowCount();
		
		
		
			$medLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idConsu AND ml.numero=:num ORDER BY ml.id_medlabo');		
			$medLabo->execute(array(
			'idConsu'=>$_GET['idconsu'],
			'num'=>$_GET['num']
			));
			
			$medLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptMedLabo=$medLabo->rowCount();
		
					
		
			$medRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_consuRadio=:idConsu AND mr.numero=:num ORDER BY mr.id_medradio');		
			$medRadio->execute(array(
			'idConsu'=>$_GET['idconsu'],
			'num'=>$_GET['num']
			));
			
			$medRadio->setFetchMode(PDO::FETCH_OBJ);

			$comptMedRadio=$medRadio->rowCount();
		
		
		
			$medConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.id_consuConsom=:idConsu AND mco.numero=:num ORDER BY mco.id_medconsom');		
			$medConsom->execute(array(
			'idConsu'=>$_GET['idconsu'],
			'num'=>$_GET['num']
			));
			
			$medConsom->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsom=$medConsom->rowCount();
		
		
			$medMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.id_consuMedoc=:idConsu AND mdo.numero=:num ORDER BY mdo.id_medmedoc');		
			$medMedoc->execute(array(
			'idConsu'=>$_GET['idconsu'],
			'num'=>$_GET['num']
			));
			
			$medMedoc->setFetchMode(PDO::FETCH_OBJ);

			$comptMedMedoc=$medMedoc->rowCount();
		
		
			$medKine=$connexion->prepare('SELECT *FROM med_kine mk WHERE mk.id_consuKine=:idConsu AND mk.id_uM=:idM AND mk.numero=:num ORDER BY mk.id_medkine');		
			$medKine->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medKine->setFetchMode(PDO::FETCH_OBJ);

			$comptMedKine=$medKine->rowCount();
		
		
			$medOrtho=$connexion->prepare('SELECT *FROM med_ortho mo WHERE mo.id_consuOrtho=:idConsu AND mo.id_uM=:idM AND mo.numero=:num ORDER BY mo.id_medortho');		
			$medOrtho->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medOrtho->setFetchMode(PDO::FETCH_OBJ);

			$comptMedOrtho=$medOrtho->rowCount();
		
		
		}else{
			
			$medConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idConsu AND mc.id_uM=:idM AND mc.numero=:num ORDER BY mc.id_medconsu');		
			$medConsult->execute(array(
			'idConsu'=>$_GET['idconsu'],
			'num'=>$_GET['num']
			));
			
			$medConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptMedConsult=$medConsult->rowCount();
			
		
		
			$medInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_consuInf=:idConsu AND mi.id_uM=:idM AND mi.numero=:num ORDER BY mi.id_medinf');		
			$medInf->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptMedInf=$medInf->rowCount();
			
		
		
		
			$medPsy=$connexion->prepare('SELECT *FROM med_psy mp WHERE mp.id_consuPsy=:idConsu AND mp.id_uM=:idM AND mp.numero=:num ORDER BY mp.id_medpsy');		
			$medPsy->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medPsy->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptMedPsy=$medPsy->rowCount();
			
		
		
			$medSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.id_consuSurge=:idConsu AND ms.id_uM=:idM AND ms.numero=:num ORDER BY ms.id_medsurge');		
			$medSurge->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medSurge->setFetchMode(PDO::FETCH_OBJ);

			$comptMedSurge=$medSurge->rowCount();
		
		
		
			$medLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idConsu AND ml.id_uM=:idM AND ml.numero=:num ORDER BY ml.id_medlabo');		
			$medLabo->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']	
			));
			
			$medLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptMedLabo=$medLabo->rowCount();
		
		
		
			$medRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_consuRadio=:idConsu AND mr.id_uM=:idM AND mr.numero=:num ORDER BY mr.id_medradio');		
			$medRadio->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medRadio->setFetchMode(PDO::FETCH_OBJ);

			$comptMedRadio=$medRadio->rowCount();
		
		
		
			$medConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.id_consuConsom=:idConsu AND mco.id_uM=:idM AND mco.numero=:num ORDER BY mco.id_medconsom');		
			$medConsom->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medConsom->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsom=$medConsom->rowCount();
		
		
			$medMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.id_consuMedoc=:idConsu AND mdo.id_uM=:idM AND mdo.numero=:num ORDER BY mdo.id_medmedoc');		
			$medMedoc->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medMedoc->setFetchMode(PDO::FETCH_OBJ);

			$comptMedMedoc=$medMedoc->rowCount();
		
		
			$medKine=$connexion->prepare('SELECT *FROM med_kine mk WHERE mk.id_consuKine=:idConsu AND mk.id_uM=:idM AND mk.numero=:num ORDER BY mk.id_medkine');		
			$medKine->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medKine->setFetchMode(PDO::FETCH_OBJ);

			$comptMedKine=$medKine->rowCount();
		
		
			$medOrtho=$connexion->prepare('SELECT *FROM med_ortho mo WHERE mo.id_consuOrtho=:idConsu AND mo.id_uM=:idM AND mo.numero=:num ORDER BY mo.id_medortho');		
			$medOrtho->execute(array(
			'idConsu'=>$idConsu,
			'idM'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$medOrtho->setFetchMode(PDO::FETCH_OBJ);

			$comptMedOrtho=$medOrtho->rowCount();
		
		}
		
		?>
			
			
			<table class="cons-table" style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:85%;"  cellpadding=5>			
				<tr>
					<td><label for="listradio"><?php echo getString(240); ?></label></td>
					<td><label for="listexamen"><?php echo getString(99) ?></label></td>
				</tr>
						<hr>
						<label style="font-size: 13px;color: red;"> <i class="fa fa-info-circle " style="font-size:25px;"></i> <?php echo getString(291); ?></label>
						<br>
				<?php
				/* if($consuDate == $annee)
				{ */
				?>			
				<tr>				
					<td>
							<hr>
						<div class="downArrow bounce">
							<i class="fa fa-arrow-down"></i>
						</div>
						<select style="margin:auto" multiple="multiple" name="listradio[]" class="chosen-select" id="listradio">

							<!--
							<option value='0'><?php echo 'Selectionner le type de radio...' ?></option>
							-->
						<?php
						
						$resultatsPrestaRadio=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=13 AND p.statupresta!=0  ORDER BY p.nompresta ASC');
						
						$resultatsPrestaRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaRadio=$resultatsPrestaRadio->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaRadio->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaRadio->id_prestation.' onclick="ShowRadio(\'radio\')">'.$ligneCatPrestaRadio->nompresta.'</option>';
							
							while($lignePrestaRadio=$resultatsPrestaRadio->fetch())
							{
						?>
								<option value='<?php echo $lignePrestaRadio->id_prestation;?>'><?php echo $lignePrestaRadio->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}
						?>
						
							<!--
							<option value="autreradio" id="autreradio"><?php echo 'Autre type radio...'; ?></option>
							-->
							
						</select>
												
						<input style="height:35px; margin:0;visibility:visible;" type = "submit" value ="<?php echo getString(125) ?>" id="addRadio" name="addRadio" class="btn"/>

					</td>
					
					<td>
						<hr>
						<div class="downArrow bounce">
							<i class="fa fa-arrow-down"></i>
						</div>
						<select style="margin:auto" multiple="multiple" name="listexamen[]" class="chosen-select" id="listexamen">

						<?php
						$resultatsPrestaExamen=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=12 AND p.statupresta!=0 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaExamen->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaExamen=$resultatsPrestaExamen->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaExamen->nomcategopresta.'">';
							
							echo '<option value='.$ligneCatPrestaExamen->id_prestation.' onclick="ShowExam(\'exam\')">'.$ligneCatPrestaExamen->nompresta.'</option>';
							
							while($lignePrestaExamen=$resultatsPrestaExamen->fetch())//on recupere la liste des éléments
							{
						?>
								<option value='<?php echo $lignePrestaExamen->id_prestation;?>'><?php echo $lignePrestaExamen->nompresta;?></option>
						<?php
							}							
							echo '</optgroup>';
						}

						?>
						
							<!--
							<option value="autreexamen" id="autreexamen"><?php echo getString(124) ?></option>
							-->
							
						</select>
												
						<input style="height:35px; margin:0;visibility:visible;" type = "submit" value ="<?php echo getString(125) ?>" id="addExam" name="addExam" class="btn"/>

					</td>
				</tr>
			
				<tr>
					<td>
						<input style="width:40%;margin:0;display:inline" type="text" id="areaAutreradio" name="areaAutreradio" placeholder="<?php echo getString(242).'...'; ?>"/>
						
						<input style="height:35px; margin:0; visibility:visible;" type = "submit" id="addAutreRadio" name="addAutreRadio" value="<?php echo getString(266) ?>" class="btn"/>
					</td>
					
					<td>
						<!-- <input style="height:35px; width:40%; margin:0;display:inline;" type="text" id="areaAutreexamen" name="areaAutreexamen" placeholder="<?php echo getString(128) ?>"/> -->
						
						<!-- <input style="height:35px; margin:0; visibility:visible;" type = "submit" id="addAutreExam" name="addAutreExam" value="<?php echo getString(266) ?>" class="btn"/> -->
					</td>
				
				</tr>
				<?php
				//}
				?>	
				<tr>
					<td style="vertical-align: top;">
					<?php	
					if($comptMedRadio!=0)
					{
					?>					
						<div style="font-size:13px; overflow:auto;height:auto;padding:5px;margin-bottom:30px;">
						
							<table class="tablesorter" cellspacing="0"> 
								<thead> 
									<tr>
										<th><?php echo 'Radiologie'; ?></th>
										<th style="width:15%;"><?php?></th>
										<th style="width:20%;"><?php echo getString(70) ?></th>
									</tr> 
								</thead> 
														
								<tbody>	
								<?php
								try
								{
									$x=0;
									
									while($ligneMedRadio=$medRadio->fetch())
									{
								?>
									<tr style="text-align:center;">
										<td>
									<?php

									$idassuRad=$ligneMedRadio->id_assuRad;

									$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuLab->rowCount();
									
									for($i=1;$i<=$assuCount;$i++)
									{
										
										$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuLab->execute(array(
										'idassu'=>$idassuRad
										));
										
										$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuLab=$getAssuLab->fetch())
										{
											$presta_assuRad='prestations_'.strtolower($ligneNomAssuLab->nomassurance);
										}
									}


									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedRadio->id_prestationRadio
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

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
									}else{
										$presta=$ligneMedRadio->autreRadio;
										echo $ligneMedRadio->autreRadio;
									}
									
									/*if( substr(strtolower($presta), 0,4) === "echo" OR substr(strtolower($presta), 0,3) === "eco")
									{*/
									?>
									<br/>
									
									<textarea name="resultatsRad[]" id="resultatsRad<?php echo $x;?>" style="display:none; border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc;background: #fafafa none repeat scroll 0% 0%; height: 100px; width: 250px; max-height: 300px; max-width: 250px; min-height: 100px; min-width: 200px; margin-top:5px;" placeholder="Tapez les résultats ici..."><?php if($ligneMedRadio->resultatsRad != "") { echo strip_tags($ligneMedRadio->resultatsRad);}?></textarea>
									<input type="text" id="idRad" name="idRad[]" value="<?php echo $ligneMedRadio->id_medradio;?>" style="display:none;"/>
									<?php
									//}
									?>
										</td>
										<td>
								
								<?php	
	
								/*if( substr(strtolower($presta), 0,4) === "echo" OR substr(strtolower($presta), 0,3) === "eco")
								{	*/								
								?>
									<span id="resultatsEcho<?php echo $x;?>" name="resultatsEcho<?php echo $x;?>" class="btn" onclick="ResultatsEcho(<?php echo $x;?>)" style="display:inline;"><i class="fa fa-chevron-down lg fa-fw"></i></span>
								<?php
								//}
								?>
								
									<span id="noresultatsEcho<?php echo $x;?>" name="noresultatsEcho<?php echo $x;?>" class="btn" onclick="NoresultatsEcho(<?php echo $x;?>)" style="display:none;"/><i class="fa fa-chevron-up lg fa-fw"></i></span>
							
										</td>
										<td>
										<?php
										if($ligneMedRadio->id_factureMedRadio ==0 AND $ligneMedRadio->id_uM ==$_SESSION['id'])
										{
											$nompresta=$presta;
										?>
										
											<button style="width:auto; height:auto;" type="submit" name="deleteMedradio[]" value="<?php echo $ligneMedRadio->id_medradio;?>" class="btn"><i class="fa fa-trash fa-lg fa-fw"></i></button>
										<?php
										}else{
										?>
											------
										<?php
										}
										?>
										</td>
									</tr>
								<?php
										$x++;
									}
									$medRadio->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
								</tbody>
							</table>
						</div>

											
					<?php	
					}/* else{
					?>
						<table class="tablesorter" cellspacing="0"> 
						<thead> 
							<tr>
								<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas de radio demandé</th>
							</tr>
						</thead> 		
						</table>
					<?php
					} */
					?>
					</td>
					
					<td style="vertical-align: top;">
					<?php	
					if($comptMedLabo!=0)
					{
					?>					
						<div style="font-size:13px; overflow:auto;height:auto;padding:5px;margin-bottom:30px;">
						
							<table class="tablesorter" cellspacing="0"> 
								<thead> 
									<tr>
										<th><?php echo getString(99) ?></th>
										<th style="width:20%;"><?php echo getString(70) ?></th>
									</tr> 
								</thead> 
							
							
								<tbody>	
								<?php
								try
								{
									while($ligneMedLabo=$medLabo->fetch())//on recupere la liste des éléments
									{
								?>
									<tr style="text-align:center;">
										<td style="<?php if($ligneMedLabo->examenfait==1){ echo 'background:rgba(0,100,255,0.5);';}?>">
								<?php

								$idassuLab=$ligneMedLabo->id_assuLab;

								$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
								$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
										
								$assuCount = $comptAssuLab->rowCount();
								
								for($i=1;$i<=$assuCount;$i++)
								{
									
									$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
									$getAssuLab->execute(array(
									'idassu'=>$idassuLab
									));
									
									$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

									if($ligneNomAssuLab=$getAssuLab->fetch())
									{
										$presta_assuLab='prestations_'.strtolower($ligneNomAssuLab->nomassurance);
									}
								}

								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedLabo->id_prestationExa
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
									$presta=$ligneMedLabo->autreExamen;
									echo $ligneMedLabo->autreExamen;
								}
								?>
										</td>
										<td> 
									<?php
									if($ligneMedLabo->id_factureMedLabo==0 AND $ligneMedLabo->id_uM ==$_SESSION['id'])
									{
										if($ligneMedLabo->examenfait!=1)
										{
											$nompresta=$presta;
									?>
										
											<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedlabo[]" value="<?php echo $ligneMedLabo->id_medlabo;?>" class="btn"><i class="fa fa-trash fa-lg fa-fw"></i></button>
									<?php
										}else{
									?>
										------
										<?php
										}
									}else{
									?>
										------
									<?php
									}
									?>
										</td>
									</tr>

								<?php
									}
									$medLabo->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
								</tbody>
							</table>
						</div>

											
					<?php	
					}/* else{
					?>
						<table class="tablesorter" cellspacing="0"> 
						<thead> 
							<tr>
								<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas d'examen demandé</th>
							</tr>
						</thead> 		
						</table>
					<?php
					} */
					?>
					</td>
				</tr>
				
				<tr>
					<td style="text-align:center;background:#eee;"></td>
					<td style="text-align:center;background:#eee;"></td>
				</tr>
										
									
					
				<tr>
					<td style="text-align:center"><label for="soins"><?php echo getString(98) ?></label></td>
					<td style="text-align:center"><label for="soins"><?php echo "Psychologie" ?></label></td>
				</tr>
				<?php
				/* if($consuDate == $annee)
				{ */
				?>	
				<tr>	
					<td>
						<select style="margin:auto;" multiple="multiple" name="soins[]" class="chosen-select" id="soins">						
							<!--
							<option value='0'><?php echo getString(121) ?></option>
							-->							
						<?php

						$resultatsPrestaSoins=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=3 AND p.statupresta!=0  ORDER BY p.nompresta ASC');
						
						$resultatsPrestaSoins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaSoins=$resultatsPrestaSoins->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaSoins->nomcategopresta.'">';

							echo '<option value='.$ligneCatPrestaSoins->id_prestation.' onclick="ShowOthersSoins(\'soins\')">'.$ligneCatPrestaSoins->nompresta.'</option>';							
							
							while($lignePrestaSoins=$resultatsPrestaSoins->fetch())//on recupere la liste des éléments
							{
						?>
								<option value='<?php echo $lignePrestaSoins->id_prestation;?>'><?php echo $lignePrestaSoins->nompresta;?></option>
						<?php
							}$resultatsPrestaSoins->closeCursor();
						
							echo '</optgroup>';
						}
						?>
							<!--
							<option value="autresoins" id="autresoins"><?php echo getString(122) ?></option>
							-->
							
						</select>
						
						<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addNursery" name="addNursery" value="<?php echo getString(125) ?>" class="btn"/>
					</td>	

					<td>
						<select style="margin:auto;" multiple="multiple" name="psycho[]" class="chosen-select" id="psycho">						
							<!--
							<option value='0'><?php echo getString(121) ?></option>
							-->							
						<?php

						$resultatsPrestaSoins=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=25 AND p.statupresta!=0  ORDER BY p.nompresta ASC');
						
						$resultatsPrestaSoins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaSoins=$resultatsPrestaSoins->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaSoins->nomcategopresta.'">';

							echo '<option value='.$ligneCatPrestaSoins->id_prestation.' onclick="ShowOthersSoins(\'psycho\')">'.$ligneCatPrestaSoins->nompresta.'</option>';							
							
							while($lignePrestaSoins=$resultatsPrestaSoins->fetch())//on recupere la liste des éléments
							{
						?>
								<option value='<?php echo $lignePrestaSoins->id_prestation;?>'><?php echo $lignePrestaSoins->nompresta;?></option>
						<?php
							}$resultatsPrestaSoins->closeCursor();
						
							echo '</optgroup>';
						}
						?>
							<!--
							<option value="autresoins" id="autresoins"><?php echo getString(122) ?></option>
							-->
							
						</select>
						
						<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addpsy" name="addpsy" value="<?php echo getString(125) ?>" class="btn"/>
					</td>
				</tr>
				
				<tr>
					<td>					
						<input style="width:40%;margin:0;display:inline" type="text" id="areaAutresoins" name="areaAutresoins" placeholder="<?php echo getString(127) ?>"/>
						
						<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addAutreNursery" name="addAutreNursery" value="<?php echo getString(266) ?>" class="btn"/>
					</td>			
					<td>					
						<input style="width:40%;margin:0;display:inline" type="text" id="areaAutrepsy" name="areaAutrepsy" placeholder="<?php echo getString(127) ?>"/>
						
						<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addAutrePsycho" name="addAutrePsycho" value="<?php echo getString(266) ?>" class="btn"/>
					</td>				
				</tr>				
				<?php
				// }
				?>
				<tr>					
					<td style="vertical-align: top;">	
					<?php 
					if($comptMedInf!=0)
					{
					?>					
					
						<div style="font-size:13px; overflow:auto;height:auto;padding:5px;margin-bottom:30px;">
						
							<table class="tablesorter" cellspacing="0"> 
								<thead> 
									<tr>
										<th><?php echo getString(98) ?></th>
										<th style="width:20%;"><?php echo getString(70) ?></th>
									</tr> 
								</thead> 
							
							
								<tbody>	
									<?php
									try
									{
										while($ligneMedInf=$medInf->fetch())//on recupere la liste des éléments
										{
									?>
										<tr style="text-align:center;">
											<td>
												<?php
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedInf->id_prestation
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
										echo $lignePresta->namepresta.'</td>';
									}else{
										$presta=$lignePresta->nompresta;
										echo $lignePresta->nompresta.'</td>';
									}
								}else{
									$presta=$ligneMedInf->autrePrestaM;
									echo $ligneMedInf->autrePrestaM;
								}
								?>
											</td>
											<td>
											<?php
											if($ligneMedInf->id_factureMedInf ==0 AND $ligneMedInf->id_uM ==$_SESSION['id'])
											{
											?>
												<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedinf[]" value="<?php echo $ligneMedInf->id_medinf;?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
											<?php
											}else{
											?>
												------
											<?php
											}
											?>

											</td>
										
										</tr>

									<?php
										}
										$medInf->closeCursor();
									}

									catch(Excepton $e)
									{
										echo 'Erreur:'.$e->getMessage().'<br/>';
										echo'Numero:'.$e->getCode();
									}
									?>
								</tbody>
							</table>
						</div>
					
					<?php	
					}/* else{
					?>
						<table class="tablesorter" cellspacing="0"> 
						<thead> 
							<tr>
								<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas de soins à l'infimerie</th>
							</tr>
						</thead> 		
						</table>
					<?php
					} */
					?>
					</td>		
					<!-- Psychologie -->				
					<td style="vertical-align: top;">	
					<?php 
					if($comptMedPsy!=0)
					{
					?>					
					
						<div style="font-size:13px; overflow:auto;height:auto;padding:5px;margin-bottom:30px;">
						
							<table class="tablesorter" cellspacing="0"> 
								<thead> 
									<tr>
										<th><?php echo getString(98) ?></th>
										<th style="width:20%;"><?php echo getString(70) ?></th>
									</tr> 
								</thead> 
							
							
								<tbody>	
									<?php
									try
									{
										while($ligneMedPsy=$medPsy->fetch())//on recupere la liste des éléments
										{
									?>
										<tr style="text-align:center;">
											<td>
												<?php
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedPsy->id_prestation
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
										echo $lignePresta->namepresta.'</td>';
									}else{
										$presta=$lignePresta->nompresta;
										echo $lignePresta->nompresta.'</td>';
									}
								}else{
									$presta=$ligneMedPsy->autrePrestaM;
									echo $ligneMedPsy->autrePrestaM;
								}
								?>
											</td>
											<td>
											<?php
											if($ligneMedPsy->id_factureMedPsy ==0 AND $ligneMedPsy->id_uM ==$_SESSION['id'])
											{
											?>
												<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedPsy[]" value="<?php echo $ligneMedPsy->id_medpsy;?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
											<?php
											}else{
											?>
												------
											<?php
											}
											?>

											</td>
										
										</tr>

									<?php
										}
										$medPsy->closeCursor();
									}

									catch(Excepton $e)
									{
										echo 'Erreur:'.$e->getMessage().'<br/>';
										echo'Numero:'.$e->getCode();
									}
									?>
								</tbody>
							</table>
						</div>
					
					<?php	
					}/* else{
					?>
						<table class="tablesorter" cellspacing="0"> 
						<thead> 
							<tr>
								<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas de soins à l'infimerie</th>
							</tr>
						</thead> 		
						</table>
					<?php
					} */
					?>
					</td>		
				</tr>
					
				<tr>
					<td style="text-align:center;background:#eee;"></td>
				</tr>
			</table>
					
			</legend>

		</div>
		
		<table style="background: #d8d8d8" class="cons-info" cellpadding=10>

			<tr>
				<td colspan=4>					
					<table class="cons-info" style="padding: 5px; margin: 20px auto; background: #eee none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:60%;"  cellpadding=5 id="diagnopost">			
						<tr>
							<td><label for="postdiagno"><?php echo getString(247);?></label></td>
						</tr>
						
						<tr>
							<td>
								<select style="margin:auto" multiple="multiple" name="postdiagno[]" class="chosen-select" id="postdiagno">
									<!--
									<option value='0'><?php echo getString(119) ?></option>
									-->
								<?php
							
								$resultatsPostdiagno=$connexion->query('SELECT *FROM diagnostic d ORDER BY d.nomdiagno');
								
								$resultatsPostdiagno->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPostdiagno=$resultatsPostdiagno->rowCount();
								
								while($lignePostdiagno=$resultatsPostdiagno->fetch())
								{
								?>
									<option style="text-align:center;" value='<?php echo $lignePostdiagno->id_diagno;?>'>
									<?php
									echo $lignePostdiagno->nomdiagno;
									?>							
									</option>
								<?php
								}
								?>
									
								</select>
							
								<input type="submit" style="height:35px; margin:0;visibility:visible;" id="addPostdiagno" name="addPostdiagno" value="<?php echo getString(125) ?>" class="btn"/>
								
							</td>
						</tr>

						<tr>
							<td>
								<input type="text" style="height:35px; display:inline;" id="areaPostdiagno" name="areaPostdiagno" placeholder="<?php echo getString(251);?>"/>
								
								<input type = "submit" style="height:35px; margin:0;visibility:visible;" id="addAutrePostdiagno" name="addAutrePostdiagno" value="<?php echo getString(266) ?>" class="btn"/>
							</td>
							
						</tr>
						<?php
						
						$medDia=$connexion->prepare('SELECT *FROM prepostdia p WHERE p.id_consudia=:idConsu AND p.id_uM=:idM AND p.numero=:num AND (p.autrepostdia!="" OR p.id_postdia IS NOT NULL) ORDER BY p.id_dia');		
						$medDia->execute(array(
						'idConsu'=>$idConsu,
						'idM'=>$_SESSION['id'],
						'num'=>$_GET['num']
						));
						
						$medDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedDia=$medDia->rowCount();
						
						if($comptMedDia!=0)
						{
						?>
						<tr>
							<td style="vertical-align: top;">			
								<div style="font-size:13px; overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
								
									<table class="tablesorter" cellspacing="0"> 
										<thead> 
											<tr>
												<th><?php echo getString(247);?></th>
												<th style="width:20%"><?php echo getString(70); ?></th>
											</tr> 
										</thead> 
									
									
										<tbody>	
										<?php
										try
										{
											while($ligneMedDia=$medDia->fetch())
											{
										?>
											<tr style="text-align:center;">
												
												<td>
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedDia->id_postdia
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											$presta=$lignePresta->nomdiagno;	
										}else{
											$presta=$ligneMedDia->autrepostdia;
										}
											echo $presta;
										?>
												</td>
												<td>
													<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedpostdia[]" value="<?php echo $ligneMedDia->id_dia; ?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
													
												</td>	
											</tr>

											<?php
												}
												$medDia->closeCursor();
											}

											catch(Excepton $e)
											{
												echo 'Erreur:'.$e->getMessage().'<br/>';
												echo'Numero:'.$e->getCode();
											}
											?>
										</tbody>
									</table>
								</div>
							</td>						
						</tr>												
						<?php	
						}
						?>
						
					</table>
				</td>
			</tr>
				
			<tr>
				<td colspan=4>
					<table class="cons-info" cellpadding=5 style="margin:0;" id="tableSurge">
						<tr>
							<td style="text-align:center"><label for="recomm"><?php echo getString(159) ?></label></td>
							
							<td style="text-align:center"><label for="surge"><?php echo "Actes medicaux"; ?></label></td>
						</tr>
						
						<tr>
							<td style="text-align:center">
								<textarea style="background: #eee; margin:auto; height:150px; max-width:500px; min-height:150px; min-width:500px;text-align:center;" id="recomm" name="recomm" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $recomm;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $recomm;}else{ if(isset($_GET['idconsuNext'])){echo $recomm;}else{ echo '';}}}?></textarea>
							</td>
				
							<td style="text-align:center">
								<table class="cons-table" style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;" cellpadding=3>	
									<tr>
										<td>
											<select style="margin:auto" multiple="multiple" name="surge[]" class="chosen-select" id="surge">
												<!--
												<option value='0'><?php echo getString(119) ?></option>
												-->
											<?php

											$resultatsCategoPrestaSurge=$connexion->query('SELECT *FROM categopresta_ins cp WHERE cp.id_categopresta=4 OR cp.id_categopresta=17 ORDER BY cp.nomcategopresta');

											$resultatsCategoPrestaSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptCategoMedSurge=$resultatsCategoPrestaSurge->rowCount();
											
											while($ligneCategoPrestaSurge=$resultatsCategoPrestaSurge->fetch())
											{
												echo '<optgroup label="'.$ligneCategoPrestaSurge->nomcategopresta.'">';
												
												$resultatsPrestaSurge=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND (p.id_categopresta=4 OR p.id_categopresta=17) AND p.id_categopresta='.$ligneCategoPrestaSurge->id_categopresta.' ORDER BY p.nompresta');

												$resultatsPrestaSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

												$comptMedPrestaSurge=$resultatsPrestaSurge->rowCount();
												
												while($lignePrestaSurge=$resultatsPrestaSurge->fetch())
												{
											?>
													<option value='<?php echo $lignePrestaSurge->id_prestation;?>'>
													<?php
													if($lignePrestaSurge->nompresta!="")
													{
														echo $lignePrestaSurge->nompresta;
													}else{
														if($lignePrestaSurge->namepresta!="")
														{
															echo $lignePrestaSurge->namepresta;
														}
													}
													?>
													
													</option>
											<?php
												}
											echo '</optgroup>';
											}
											?>							
												<!--
												<option value='autresurge' id="autresurge"> <?php echo getString(120); ?></option>
												-->
												
											</select>
										
											<input style="height:35px; margin:0;visibility:visible;" type="submit" id="addSurge" name="addSurge" value="<?php echo getString(125) ?>" class="btn"/>
											
										</td>
									</tr>
									
									<tr>
										<td>					
											<input style="width:40%;margin:0;display:inline" type="text" id="areaAutresurge" name="areaAutresurge" placeholder="<?php echo "Inserer autre acte..."; ?>"/>
											
											<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addAutreSurge" name="addAutreSurge" value="<?php echo getString(266) ?>" class="btn"/>
										</td>							
									</tr>
								<!-- 	<?php echo'SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=17 AND p.id_categopresta='.$ligneCategoPrestaSurge->id_categopresta.' ORDER BY p.nompresta' ?> -->
									<tr>
										<td style="vertical-align: top;">	
										<?php 
										if($comptMedSurge!=0)
										{
										?>					
										
											<div style="font-size:13px; overflow:auto;height:auto;padding:5px;margin-bottom:30px;">
											
												<table class="tablesorter" cellspacing="0"> 
													<thead> 
														<tr>
															<th><?php echo "Actes Medicaux"; ?></th>
															<th style="width:20%;"><?php echo getString(70) ?></th>
														</tr> 
													</thead> 
												
												
													<tbody>	
														<?php
														try
														{
															while($ligneMedSurge=$medSurge->fetch())
															{
														?>
															<tr style="text-align:center;">
																<td>
																	<?php
													$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
													$resultPresta->execute(array(
														'prestaId'=>$ligneMedSurge->id_prestationSurge
													));
													
													$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

													$comptPresta=$resultPresta->rowCount();
													
													if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
													{
														if($lignePresta->namepresta!='')
														{
															$presta=$lignePresta->namepresta;
															echo $lignePresta->namepresta.'</td>';
														}else{
															$presta=$lignePresta->nompresta;
															echo $lignePresta->nompresta.'</td>';
														}
													}else{
														$presta=$ligneMedSurge->autrePrestaS;
														echo $ligneMedSurge->autrePrestaS;
													}
													?>
																</td>
																<td>
																<?php
																if($ligneMedSurge->id_factureMedSurge ==0 AND $ligneMedSurge->id_uM ==$_SESSION['id'])
																{
																?>
																	<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedsurge[]" value="<?php echo $ligneMedSurge->id_medsurge;?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
																<?php
																}else{
																?>
																	------
																<?php
																}
																?>

																</td>
															
															</tr>

														<?php
															}
															$medSurge->closeCursor();
														}

														catch(Excepton $e)
														{
															echo 'Erreur:'.$e->getMessage().'<br/>';
															echo'Numero:'.$e->getCode();
														}
														?>
													</tbody>
												</table>
											</div>
										
										<?php	
										}/* else{
										?>
											<table class="tablesorter" cellspacing="0"> 
											<thead> 
												<tr>
													<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas traitement</th>
												</tr>
											</thead> 		
											</table>
										<?php
										} */
										?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
	
					</table>
				</td>				
			</tr>
				
			<tr>
				<hr>
						<label style="font-size: 13px;color: red;"> <i class="fa fa-info-circle " style="font-size:25px;"></i> <?php echo getString(291); ?></label>
						<br>
				<td>
					<table class="cons-table" style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>	
						<tr>
						<?php 
						if($hospitalized==1 OR $hospitalized==0)
						{
						?>	
							<td style="text-align:left">HOSPITALISER<input type="checkbox" name="hospitalized" id="hospitalized" <?php if($hospitalized==1){ echo "checked=checked";}?> onclick="ShowMotifhospi('hospi')"/>
							</td>
						<?php 
						}elseif($hospitalized==2){
						?>		
							<td>					
								<table class="cons-info" cellpadding=5 style="margin:auto auto auto auto;background:#fff;width:auto;">
								   
									<tr>
										<td style="text-align:left">Patient déjà Hospitalisé</td>
									</tr>
									
								</table>

							</td>
						<?php 
						}
						?>
						</tr>
						
						<tr id="motifhospi" style="<?php if($hospitalized !=NULL){ echo "display:inline;";}else{ echo "display:none;";}?>">
							<td>					
								Motif <textarea style="background: #eee; margin:auto; height:50px; width:100px; max-width:180px; max-height:250px; min-height:50px; min-width:100px; text-align:center;" id="motifhospitalized" name="motifhospitalized" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $motifhospitalized;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $motifhospitalized;}else{ echo '';}}?></textarea>
							</td>
						</tr>
					</table>
				</td>
				
				<td>
				<?php
					/*if($_SESSION['nomcatego']!="Kinésithérapie")
					{
				?>
					<table class="cons-table" style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>	
							<tr>	
								<td style="text-align:left">
									PHYSIOTHERAPY
									<input type="checkbox" name="physio" id="physio" <?php if($physio==1){ echo "checked=checked";}?> onclick="ShowMotifphysio('physio')"/>
								</td>
							</tr>
							
							<tr id="motifkine" style="<?php if($physio!=NULL){ echo "display:inline;";}else{ echo "display:none;";}?>">
								<td>					
									Motif <textarea style="background: #eee; margin:auto; height:50px; width:100px; max-width:180px; max-height:250px; min-height:50px; min-width:100px; text-align:center;" id="motifphysio" name="motifphysio" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $motifphysio;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $motifphysio;}else{ echo '';}}?></textarea>
								</td>
							</tr>
					</table>
				<?php
					}else{*/
				?>
					<table class="cons-table" style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3 id="tableKine">	
						<tr>					
							<td style="text-align:center"><label for="kine"><?php echo 'PHYSIOTHERAPY'; ?></label></td>
						</tr>
						
						<tr>						
							<td>
								<select style="margin:auto;" multiple="multiple" name="kine[]" class="chosen-select" id="kine">
								<?php

								$resultatsPrestaKine=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=14 AND p.statupresta!=0  ORDER BY p.nompresta ASC');
								
								$resultatsPrestaKine->setFetchMode(PDO::FETCH_OBJ);
								
								if($ligneCatPrestaKine=$resultatsPrestaKine->fetch())
								{
									echo '<optgroup label="'.$ligneCatPrestaKine->nomcategopresta.'">';

									echo '<option value='.$ligneCatPrestaKine->id_prestation.' onclick="ShowOthersKine(\'kine\')">'.$ligneCatPrestaKine->nompresta.'</option>';							
									
									while($lignePrestaKine=$resultatsPrestaKine->fetch())
									{
								?>
										<option value='<?php echo $lignePrestaKine->id_prestation;?>'><?php echo $lignePrestaKine->nompresta;?></option>
								<?php
									}$resultatsPrestaKine->closeCursor();
								
									echo '</optgroup>';
								}
								?>							
								</select>
								
								<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addKine" name="addKine" value="<?php echo getString(125) ?>" class="btn"/>
							</td>
						</tr>
						
						<tr>	
							<td>					
								<input style="width:40%;margin:0;display:inline" type="text" id="areaAutreKine" name="areaAutreKine" placeholder="<?php echo 'Inserer autre Acte'; ?>"/>
								
								<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addAutreKine" name="addAutreKine" value="<?php echo getString(266) ?>" class="btn"/>
							</td>
						</tr>
						
						<tr>	
							<td>	
							<?php 
							if($comptMedKine!=0)
							{
							?>
								<div style="font-size:13px; overflow:auto;height:auto;padding:5px;width:auto;background:#eee;" align="center">
								
									<table class="tablesorter" cellspacing="0" align="center"> 
										<thead> 
											<tr>
												<th><?php echo 'PHYSIOTHERAPY'; ?></th>
												<th style="width:20%;"><?php echo getString(70) ?></th>
											</tr> 
										</thead> 
									
									
										<tbody>	
											<?php
											try
											{
												while($ligneMedKine=$medKine->fetch())
												{
											?>
												<tr style="text-align:center;">
													<td>
														<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedKine->id_prestationKine
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
										}else{
											$presta=$ligneMedKine->autrePrestaO;
										}
										echo $presta;
										?>
													</td>
													<td>
													<?php
													if($ligneMedKine->id_factureMedKine ==0 AND $ligneMedKine->id_uM ==$_SESSION['id'])
													{
													?>
														<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedkine[]" value="<?php echo $ligneMedKine->id_medkine;?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
													<?php
													}else{
													?>
														------
													<?php
													}
													?>

													</td>
												
												</tr>

											<?php
												}
												$medKine->closeCursor();
											}

											catch(Excepton $e)
											{
												echo 'Erreur:'.$e->getMessage().'<br/>';
												echo'Numero:'.$e->getCode();
											}
											?>
										</tbody>
									</table>
								</div>
							
							<?php	
							}/* else{
							?>
								<table class="tablesorter" cellspacing="0"> 
								<thead> 
									<tr>
										<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas d'appareil au <?php echo 'P&O';?></th>
									</tr>
								</thead> 		
								</table>
							<?php
							} */
							?>
							</td>
						</tr>
				
					</table>
				<?php
					//}
				?>
				</td>
				
				<td>		
					<table class="cons-table" style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3 id="tableOrtho">	
						<tr>					
							<td style="text-align:center"><label for="ortho"><?php echo 'P&O'; ?></label></td>
						</tr>
						
						<tr>						
							<td>
								<select style="margin:auto;" multiple="multiple" name="ortho[]" class="chosen-select" id="ortho">
								<?php

								$resultatsPrestaOrtho=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=23  AND p.statupresta!=0  ORDER BY p.nompresta ASC');
								
								$resultatsPrestaOrtho->setFetchMode(PDO::FETCH_OBJ);
								
								if($ligneCatPrestaOrtho=$resultatsPrestaOrtho->fetch())
								{
									echo '<optgroup label="'.$ligneCatPrestaOrtho->nomcategopresta.'">';

									echo '<option value='.$ligneCatPrestaOrtho->id_prestation.' onclick="ShowOthersSoins(\'psy\')">'.$ligneCatPrestaOrtho->nompresta.'</option>';							
									
									while($lignePrestaOrtho=$resultatsPrestaOrtho->fetch())
									{
								?>
										<option value='<?php echo $lignePrestaOrtho->id_prestation;?>'><?php echo $lignePrestaOrtho->nompresta;?></option>
								<?php
									}$resultatsPrestaOrtho->closeCursor();
								
									echo '</optgroup>';
								}
								?>							
								</select>
								
								<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addOrtho" name="addOrtho" value="<?php echo getString(125) ?>" class="btn"/>
							</td>
						</tr>
						
						<tr>	
							<td>					
								<input style="width:40%;margin:0;display:inline" type="text" id="areaAutreortho" name="areaAutreortho" placeholder="<?php echo 'Inserer autre P&O'; ?>"/>
								
								<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addAutreOrtho" name="addAutreOrtho" value="<?php echo getString(266) ?>" class="btn"/>
							</td>
						</tr>
						
						<tr>	
							<td>	
							<?php 
							if($comptMedOrtho!=0)
							{
							?>
								<div style="font-size:13px; overflow:auto;height:auto;padding:5px;width:auto;background:#eee;" align="center">
								
									<table class="tablesorter" cellspacing="0" align="center"> 
										<thead> 
											<tr>
												<th><?php echo 'P&O'; ?></th>
												<th style="width:20%;"><?php echo getString(70) ?></th>
											</tr> 
										</thead> 
									
									
										<tbody>	
											<?php
											try
											{
												while($ligneMedOrtho=$medOrtho->fetch())
												{
											?>
												<tr style="text-align:center;">
													<td>
														<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedOrtho->id_prestationOrtho
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
										}else{
											$presta=$ligneMedOrtho->autrePrestaO;
										}
										echo $presta;
										?>
													</td>
													<td>
													<?php
													if($ligneMedOrtho->id_factureMedOrtho ==0 AND $ligneMedOrtho->id_uM ==$_SESSION['id'])
													{
													?>
														<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedortho[]" value="<?php echo $ligneMedOrtho->id_medortho;?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
													<?php
													}else{
													?>
														------
													<?php
													}
													?>

													</td>
												
												</tr>

											<?php
												}
												$medOrtho->closeCursor();
											}

											catch(Excepton $e)
											{
												echo 'Erreur:'.$e->getMessage().'<br/>';
												echo'Numero:'.$e->getCode();
											}
											?>
										</tbody>
									</table>
								</div>
							
							<?php	
							}/* else{
							?>
								<table class="tablesorter" cellspacing="0"> 
								<thead> 
									<tr>
										<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas d'appareil au <?php echo 'P&O';?></th>
									</tr>
								</thead> 		
								</table>
							<?php
							} */
							?>
							</td>
						</tr>
				
					</table>
				</td>

				<td>
					<table class="cons-table" style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>	
							<tr>	
								<td style="text-align:left">TRANSFER<input type="checkbox" name="transfer" id="transfer" <?php if($transfer==1){ echo "checked=checked";}?> onclick="ShowMotiftransfer('transfer')"/>
								</td>
							</tr>
							
							<tr id="motiftrans" style="<?php if($transfer!=NULL){ echo "display:inline;";}else{ echo "display:none;";}?>">
								<td>					
									Commentaire <textarea style="background: #eee; margin:auto; height:50px; width:100px; max-width:180px; max-height:250px; min-height:50px; min-width:100px; text-align:center;" id="motiftransfer" name="motiftransfer" <?php if(isset($_GET['num']) and $comptidI!=0){echo "readonly='readonly'";}?>><?php if(isset($_GET['num']) and $comptidI!=0){echo $motiftransfer;}else{if(isset($_GET['idconsu']) and $comptidM!=0){echo $motiftransfer;}else{ echo '';}}?></textarea>
								</td>
							</tr>
					</table>
				</td>
								
			</tr>
			
		</table>					
			
				
		<table class="cons-table" style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>	
			<tr id="ServConsomMedoc">				
				<td><label for="consult">Autre Services</label></td>
				<td style="text-align:center"><label for="consom"><?php echo 'Matériels'; ?></label></td>
				<td style="text-align:center"><label for="listradio"><?php echo 'Médicaments'; ?></label></td>
			</tr>
			<hr>
						<label style="font-size: 13px;color: red;"> <i class="fa fa-info-circle " style="font-size:25px;"></i> <?php echo getString(291); ?></label>
						<br>
			<tr>
				<td>
						<hr>
						<div class="downArrow bounce">
							<i class="fa fa-arrow-down"></i>
						</div>
					<select style="margin:auto" multiple="multiple" name="consult[]" class="chosen-select" id="consult">
						<!--
						<option value='0'><?php echo getString(119) ?></option>
						-->
					<?php

					$resultatsCategoPrestaConsu=$connexion->query('SELECT *FROM categopresta_ins cp WHERE cp.id_categopresta=20 ORDER BY cp.nomcategopresta');

					$resultatsCategoPrestaConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptCategoMedConsu=$resultatsCategoPrestaConsu->rowCount();
					
					while($ligneCategoPrestaConsu=$resultatsCategoPrestaConsu->fetch())
					{
						echo '<optgroup label="'.$ligneCategoPrestaConsu->nomcategopresta.'">';
						
						$resultatsPrestaConsu=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=20 AND p.id_categopresta='.$ligneCategoPrestaConsu->id_categopresta.'  AND p.statupresta!=0  ORDER BY p.nompresta');

						$resultatsPrestaConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedConsu=$resultatsPrestaConsu->rowCount();
						
						while($lignePrestaConsu=$resultatsPrestaConsu->fetch())
						{
					?>
							<option value='<?php echo $lignePrestaConsu->id_prestation;?>'>
							<?php
							if($lignePrestaConsu->nompresta!="")
							{
								echo $lignePrestaConsu->nompresta;
							}else{
								if($lignePrestaConsu->namepresta!="")
								{
									echo $lignePrestaConsu->namepresta;
								}
							}
							?>
							
							</option>
					<?php
						}
					echo '</optgroup>';
					}
					?>							
						
						<option value='autreconsult' id="autreconsult"> <?php echo getString(120); ?></option>
						
						
					</select>
				
					<input style="height:35px; margin:0;visibility:visible;" type="submit" id="addConsult" name="addConsult" value="<?php echo getString(125) ?>" class="btn"/>
					
				</td>
				
				<td>
						<hr>
						<div class="downArrow bounce">
							<i class="fa fa-arrow-down"></i>
						</div>
					<select style="margin:auto;" multiple="multiple" name="consom[]" class="chosen-select" id="consom">						
						<!--
						<option value='0'><?php echo getString(121) ?></option>
						-->							
					<?php

					$resultatsPrestaConsom=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=21  AND p.statupresta!=0  ORDER BY p.nompresta ASC');
					
					$resultatsPrestaConsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					if($ligneCatPrestaConsom=$resultatsPrestaConsom->fetch())
					{
						echo '<optgroup label="'.$ligneCatPrestaConsom->nomcategopresta.'">';

						echo '<option value='.$ligneCatPrestaConsom->id_prestation.' onclick="ShowOthersConsom(\'consom\')">'.$ligneCatPrestaConsom->nompresta.'</option>';							
						
						while($lignePrestaConsom=$resultatsPrestaConsom->fetch())//on recupere la liste des éléments
						{
					?>
							<option value='<?php echo $lignePrestaConsom->id_prestation;?>'><?php echo $lignePrestaConsom->nompresta;?></option>
					<?php
						}$resultatsPrestaConsom->closeCursor();
					
						echo '</optgroup>';
					}
					?>
						<!--
						<option value="autreconsom" id="autreconsom"><?php echo getString(122) ?></option>
						-->
						
					</select>
					
					<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addConsom" name="addConsom" value="<?php echo getString(125) ?>" class="btn"/>
				</td>
				
				<td>
						<hr>
						<div class="downArrow bounce">
							<i class="fa fa-arrow-down"></i>
						</div>
					<select style="margin:auto" multiple="multiple" name="medoc[]" class="chosen-select" id="medoc">

						<!--
						<option value='0'><?php echo 'Selectionner le type de médicament...' ?></option>
						-->
					<?php
					
					$resultatsPrestaMedoc=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.statupresta!=0  ORDER BY p.nompresta ASC');
					
					$resultatsPrestaMedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					if($ligneCatPrestaMedoc=$resultatsPrestaMedoc->fetch())
					{
						echo '<optgroup label="'.$ligneCatPrestaMedoc->nomcategopresta.'">';
						
						echo '<option value='.$ligneCatPrestaMedoc->id_prestation.' onclick="ShowRadio(\'radio\')">'.$ligneCatPrestaMedoc->nompresta.'</option>';
						
						while($lignePrestaMedoc=$resultatsPrestaMedoc->fetch())
						{
					?>
							<option value='<?php echo $lignePrestaMedoc->id_prestation;?>'><?php echo $lignePrestaMedoc->nompresta;?></option>
					<?php
						}							
						echo '</optgroup>';
					}
					?>
					
						<!--
						<option value="autreradio" id="autreradio"><?php echo 'Autre type de médicaments...'; ?></option>
						-->
						
					</select>
											
					<input style="height:35px; margin:0;visibility:visible;" type = "submit" value ="<?php echo getString(125) ?>" id="addMedoc" name="addMedoc" class="btn"/>

				</td>				
			</tr>
			
			<tr>					
				<td>
					<input style="height:35px; width:40%; margin:0;display:inline;" type="text" id="areaAutreconsult" name="areaAutreconsult" placeholder="<?php echo getString(126) ?>"/>
					
					<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addAutreConsult" name="addAutreConsult" value="<?php echo getString(266) ?>" class="btn"/>
				</td>
				
				<td>					
					<input style="width:40%;margin:0;display:inline" type="text" id="areaAutreconsom" name="areaAutreconsom" placeholder="<?php echo "Inserer l'autre matériel..." ?>"/>
					
					<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addAutreConsom" name="addAutreConsom" value="<?php echo getString(266) ?>" class="btn"/>
				</td>
				
				<td>
					<input style="width:40%;margin:0;display:inline" type="text" id="areaAutremedoc" name="areaAutremedoc" placeholder="<?php echo "Inserer l'autre médicament..."; ?>"/>
					
					<input style="height:35px; margin:0; visibility:visible;" type = "submit" id="addAutreMedoc" name="addAutreMedoc" value="<?php echo getString(266) ?>" class="btn"/>
				</td>
			</tr>
			
			<tr>
				<td style="vertical-align:top;">
				<?php
				if($comptMedConsult!=0)
				{
				?>			
					<div style="font-size:13px; overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
					
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th>Services</th>
									<th style="width:20%"><?php echo getString(70); ?></th>
								</tr> 
							</thead> 
						
						
							<tbody>	
							<?php
							try
							{
								while($ligneMedConsult=$medConsult->fetch())//on recupere la liste des éléments
								{
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
									$presta=$lignePresta->namepresta;
									echo $lignePresta->namepresta.'</td>';
								}else{
									$presta=$lignePresta->nompresta;
									echo $lignePresta->nompresta.'</td>';
								}
							}else{
								$presta=$ligneMedConsult->autreConsu;
								echo $ligneMedConsult->autreConsu;
							}
							?>
									</td>
									<td>
										<?php
										if($ligneMedConsult->id_factureMedConsu ==0 AND $ligneMedConsult->id_uM ==$_SESSION['id'])
										{
										?>
											<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedconsu[]" value="<?php echo $ligneMedConsult->id_medconsu; ?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
										<?php
										}else{
										?>
											------
										<?php
										}
										?>
										
									</td>	
								</tr>

								<?php
									}
									$medConsult->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
							</tbody>
						</table>
					</div>
										
				<?php	
				}/* else{
				?>
					<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas de consultation</th>
						</tr>
					</thead> 		
					</table>
				<?php
				} */
				?>
				</td>	
				
				<td style="vertical-align: top;">	
				<?php 
				if($comptMedConsom!=0)
				{
				?>					
				
					<div style="font-size:13px; overflow:auto;height:auto;padding:5px;margin-bottom:30px;">
					
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th><?php echo 'Matériels'; ?></th>
									<th style="width: 20%;">Quantités</th>
									<th style="width:20%;"><?php echo getString(70) ?></th>
								</tr> 
							</thead> 
						
						
							<tbody>	
								<?php
								try
								{
									$consom = 0;
									while($ligneMedConsom=$medConsom->fetch())
									{
								?>
									<tr style="text-align:center;">
										<td>
											<?php
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedConsom->id_prestationConsom
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
							}else{
								$presta=$ligneMedConsom->autreConsom;
							}
								echo $presta;
							?>
										</td>
										<td>
											<span type="submit" id="qteConsomMoins<?php echo $consom;?>" name="qteConsomMoins<?php echo $consom;?>" class="qteConsomMoins btn" style="display:<?php if($ligneMedConsom->qteConsom ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteConsom(<?php echo $consom;?>)"/>-</span>
											<input type="text" id="quantityConsom<?php echo $consom;?>" name="quantityConsom[]" class="quantityConsom" style="width:30px;margin-left:0px;" value="<?php echo $ligneMedConsom->qteConsom; ?>" />
										
											<input type="hidden" id="qteConsom<?php echo $consom;?>" name="qteConsom[]" class="qteConsom" style="width:50px;margin-left:0px;" value="<?php echo $consom;?>"/>
										
											<span type="submit" id="qteConsomPlus<?php echo $consom;?>" name="qteConsomPlus<?php echo $consom;?>" class="qteConsomPlus btn" onclick="PlusQteConsom(<?php echo $consom;?>)"/>+</span>

										</td>
										<td>
										<?php
										if($ligneMedConsom->id_factureMedConsom ==0 AND $ligneMedConsom->id_uM ==$_SESSION['id'])
										{
										?>
											<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedconsom[]" value="<?php echo $ligneMedConsom->id_medconsom;?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
										<?php
										}else{
										?>
											------
										<?php
										}
										?>

										</td>
										<?php
											if($comptMedConsom!=0)
											{
											?>
											<tr>
												<td colspan=5 style="border:0 20px 0 0">
													<input type="hidden" name="idmedConsomUpdate[]" id="idmedConsomUpdate<?php echo $consom;?>" value="<?php echo $ligneMedConsom->id_medconsom;?>">
													<input type="submit" name="addQteConsomBtn<?php echo $ligneMedConsom->id_medconsom;?>" id="addQteConsomBtn<?php echo $consom;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
												
												</td>
											</tr>
											
											<tr>
												<td colspan=6 style="background:#eee;">
												
												</td>
											</tr>
											<?php
											}
												$consom++;
										?>
									
									</tr>

								<?php
									}
									$medConsom->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
							</tbody>
						</table>
					</div>
				
				<?php	
				}/* else{
				?>
					<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas de matériels</th>
						</tr>
					</thead> 		
					</table>
				<?php
				} */
				?>
				</td>
				
				<td style="vertical-align: top;">	
				<?php 
				if($comptMedMedoc!=0)
				{
				?>					
				
					<div style="font-size:13px; overflow:auto;height:auto;padding:5px;margin-bottom:30px;">
					
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th><?php echo 'Médicaments'; ?></th>
									<th style="width: 20%;">Quantités</th>
									<th style="width:20%;"><?php echo getString(70) ?></th>
								</tr> 
							</thead> 
						
						
							<tbody>	
								<?php
								try
								{	
									$medo = 0;
									while($ligneMedMedoc=$medMedoc->fetch())
									{
								?>
									<tr style="text-align:center;">
										<td>
											<?php
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneMedMedoc->id_prestationMedoc
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
							}else{
								$presta=$ligneMedMedoc->autreMedoc;
							}
								echo $presta;
							?>
										</td>
										<td>
											<span type="submit" id="qteMedocMoins<?php echo $medo;?>" name="qteMedocMoins<?php echo $medo;?>" class="qteMedocMoins btn" style="display:<?php if($ligneMedMedoc->qteMedoc ==1){ echo 'none';}else{ echo 'inline';}?>" onclick="MoinsQteMedoc(<?php echo $medo;?>)"/>-</span>
											<input type="text" id="quantityMedoc<?php echo $medo;?>" name="quantityMedoc[]" class="quantityMedoc" style="width:30px;margin-left:0px;" value="<?php echo $ligneMedMedoc->qteMedoc; ?>" />
										
											<input type="hidden" id="qteMedoc<?php echo $medo;?>" name="qteMedoc[]" class="qteMedoc" style="width:50px;margin-left:0px;" value="<?php echo $medo;?>"/>
										
											<span type="submit" id="qteMedocPlus<?php echo $medo;?>" name="qteMedocPlus<?php echo $medo;?>" class="qteMedocPlus btn" onclick="PlusQteMedoc(<?php echo $medo;?>)"/>+</span>

										</td>

										<td>
										<?php
										if($ligneMedMedoc->id_factureMedMedoc ==0 AND $ligneMedMedoc->id_uM ==$_SESSION['id'])
										{
										?>
											<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedmedoc[]" value="<?php echo $ligneMedMedoc->id_medmedoc;?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
										<?php
										}else{
										?>
											------
										<?php
										}
										?>

										</td>
										<?php
											if($comptMedMedoc!=0)
											{
											?>
											<tr>
												<td colspan=5 style="border:0 20px 0 0">
													<input type="hidden" name="idmedMedocUpdate[]" id="idmedMedocUpdate<?php echo $medo;?>" value="<?php echo $ligneMedMedoc->id_medmedoc;?>">
													<input type="submit" name="addQteMedocBtn<?php echo $ligneMedMedoc->id_medmedoc;?>" id="addQteMedocBtn<?php echo $medo;?>" style="display:none" class="btn" value="Enregistrer modifications"/>
												
												</td>
											</tr>
											
											<tr>
												<td colspan=6 style="background:#eee;">
												
												</td>
											</tr>
											<?php
											}
												$medo++;
										?>
									</tr>

								<?php
									}
									$medMedoc->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
							</tbody>
						</table>
					</div>
				
				<?php	
				}/* else{
				?>
					<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas de médicaments</th>
						</tr>
					</thead> 		
					</table>
				<?php
				} */
				?>
				</td>
			</tr>
		</table>					
		
		<table class="cons-table" style="padding: 5px; margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width: 100%;"  cellpadding=3>	
			<tr id="ServConsomMedoc">				
				<td style="text-align:center;border-bottom: 1px solid green;padding-bottom: 20px;font-weight: bold;"><label for="consom"><?php echo 'Make Recommandation to Nurses'; ?></label></td>
				<!-- <td style="text-align:center;border-bottom: 1px solid green;padding-bottom: 20px;font-weight: bold;"><label for="listradio"><?php echo 'Médicaments Recommandations'; ?></label></td> -->
			</tr>
			
			<tr>
				<td>					
					<!-- <input style="width:70%;margin:0;display:inline" type="text" id="consomRecomm" name="consomRecomm" placeholder="<?php echo "Material Recommandations..." ?>"/> -->
					<textarea name="consomRecomm" style="width:70%;height:150px;background:#eee;"></textarea>
					<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addConsomRecomm" name="addConsomRecomm" value="<?php echo getString(266) ?>" class="btn"/>
				</td>
				
				<!-- <td>
					<input style="width:70%;margin:0;display:inline" type="text" id="medocRecomm" name="medocRecomm" placeholder="<?php echo "Drugs Recommandations..."; ?>"/>
					
					<input style="height:35px; margin:0; visibility:visible;" type = "submit" id="addMedocRecomm" name="addMedocRecomm" value="<?php echo getString(266) ?>" class="btn"/>
				</td> -->
			</tr>	

			<tr>
				<td style="vertical-align: top;">	
				<?php 
				// Get The Reccomandations 
				$id_categoprestaRec = 21;
				$GetRecomm = $connexion->prepare("SELECT * FROM doctorrecommandations WHERE idcategopresta =:id_categopresta AND numero=:numero AND idconsu=:idconsu AND id_M=:id_M");
				$GetRecomm->execute(array('id_categopresta'=>$id_categoprestaRec,'numero'=>$_GET['num'],'idconsu'=>$_GET['idconsu'],'id_M'=>$_SESSION['id']));
				$GetRecomm->setFetchMode(PDO::FETCH_OBJ);
				$comptMedConsomRecomm = $GetRecomm->rowCount();
				//echo $comptMedConsomRecomm;	
				if($comptMedConsomRecomm!=0)
				{
				?>					
				
					<div style="font-size:13px; overflow:auto;height:auto;padding:5px;margin-bottom:30px;">
					
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th><?php echo 'Material Recommandations'; ?></th>
									<th style="width:20%;"><?php echo getString(70) ?></th>
								</tr> 
							</thead> 
						
						
							<tbody>	
								<?php
								try
								{
									$consom = 0;
									while($ligneMedConsom = $GetRecomm->fetch())
									{
								?>
									<tr style="text-align:center;">
										<td>
											<?php echo $ligneMedConsom->recommandations;?>
										</td>
										<td>
										<?php
										if($ligneMedConsom->id_M == $_SESSION['id'])
										{
										?>
											<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedconsomRecom[]" value="<?php echo $ligneMedConsom->idreco;?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
										<?php
										}else{
										?>
											------
										<?php
										}
										?>

										</td>
											<?php
												$consom++;
										?>
									
									</tr>

								<?php
									}
									$GetRecomm->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
							</tbody>
						</table>
					</div>
				
				<?php	
				} /*else{
				?>
					<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">No Available Material</th>
						</tr>
					</thead> 		
					</table>
				<?php
				} */
				?>
				</td>
				<td style="vertical-align: top;">	
				<?php 
				// Get The Reccomandations 
				$id_categoprestaRec = 22;
				$GetReMedoc = $connexion->prepare("SELECT * FROM doctorrecommandations WHERE idcategopresta =:id_categopresta AND numero=:numero AND idconsu=:idconsu AND id_M=:id_M");
				$GetReMedoc->execute(array('id_categopresta'=>$id_categoprestaRec,'numero'=>$_GET['num'],'idconsu'=>$_GET['idconsu'],'id_M'=>$_SESSION['id']));
				$GetReMedoc->setFetchMode(PDO::FETCH_OBJ);
				$comptMedocRecomm = $GetReMedoc->rowCount();
				//echo $comptMedConsomRecomm;	
				if($comptMedocRecomm!=0)
				{
				?>					
				
					<div style="font-size:13px; overflow:auto;height:auto;padding:5px;margin-bottom:30px;">
					
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th><?php echo 'Drugs Recommandations'; ?></th>
									<th style="width:20%;"><?php echo getString(70) ?></th>
								</tr> 
							</thead> 
						
						
							<tbody>	
								<?php
								try
								{
									$consom = 0;
									while($ligneMedConsom = $GetReMedoc->fetch())
									{
								?>
									<tr style="text-align:center;">
										<td>
											<?php echo $ligneMedConsom->recommandations;?>
										</td>
										<td>
										<?php
										if($ligneMedConsom->id_M == $_SESSION['id'])
										{
										?>
											<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedocRecom[]" value="<?php echo $ligneMedConsom->idreco;?>" class="btn"/><i class="fa fa-trash fa-lg fa-fw"></i></button>
										<?php
										}else{
										?>
											------
										<?php
										}
										?>

										</td>
											<?php
												$consom++;
										?>
									
									</tr>

								<?php
									}
									$GetRecomm->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
							</tbody>
						</table>
					</div>
				
				<?php	
				} /*else{
				?>
					<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">No Drugs Available</th>
						</tr>
					</thead> 		
					</table>
				<?php
				} */
				?>
				</td>
			</tr>

		</table>		

		<table style="background: #d8d8d8" class="cons-info" cellpadding=10>
			
			<tr>	
				<td>					
					<table class="cons-info" cellpadding=5 style="margin:0;margin:20px auto auto auto;background:#fff;">
					   
						<tr>
							<td style="text-align:right">Date rendez-vous</td>
							<td>
								<select name="anneerdv" id="anneerdv" style="width:100px;background:#f8f8f8;">
									<?php
									$juska=date('Y')+5;
									
									for($a=date('Y');$a<=$juska;$a++)
									{
									?>
										<option value="<?php echo $a;?>" <?php if(date('Y')==$a OR ($comptRdv!=0 AND $anneeRdv==$a)) echo 'selected="selected"';?>>
										
											<?php echo $a;?>
										</option>
									<?php
									}
									?>
								</select>						
						
								<select name="moisrdv" id="moisrdv" style="width:120px;background:#f8f8f8;">
									<?php
									for($m=1;$m<=12;$m++)
									{
										$moisString=date("F",mktime(0,0,0,$m,10));
										if($m<10)
										{
											$m='0'.$m;
										}
									?>
										<option value="<?php echo $m;?>" <?php if(date('F')==$moisString) echo 'selected="selected"'; if(isset($_GET['idconsu']) AND $comptRdv!=0){ if(date("F",mktime(0,0,0,$moisRdv,10))==$moisString) { echo 'selected="selected"';} }?>>
										
										<?php 
											echo $moisString;
										?>
										</option>
									<?php
									}
									?>
								</select>
								
								<select name="joursrdv" id="joursrdv" style="width:80px;background:#f8f8f8;">
									<option value="00">Jour...</option>
									<?php
									for($d=1;$d<=31;$d++)
									{
										if($d<10)
										{
											$d='0'.$d;
										}
									?>
										<option value="<?php echo $d;?>" <?php if($comptRdv!=0 AND $joursRdv==$d) echo 'selected="selected"';?>>
										<?php echo $d;?>
										</option>
									<?php
									}
									?>
								</select>
						
							</td>
														
							<td>à
								<select name="heurerdv" id="heurerdv" style="width:100px;height:40px;background:#fff;">
								<?php 
								for($h=0;$h<=23;$h++)
								{
									if($h<10)
									{
										$h='0'.$h;
									}
								?>
									<option value='<?php echo $h;?>' <?php if($comptRdv!=0 AND $heureRdv==$h) echo 'selected="selected"';?>><?php echo $h;?></option>
								<?php 
								}
								?>
								</select>H :
								<select name="minrdv" id="minrdv" style="width:100px;height:40px;background:#fff;">
								<?php 
								for($m=0;$m<=59;$m++)
								{
									if($m<10)
									{
										$m='0'.$m;
									}
								?>
									<option value='<?php echo $m;?>' <?php if($comptRdv!=0 AND $minRdv==$m) echo 'selected="selected"';?>><?php echo $m;?></option>
								<?php 
								}
								?>
								</select>Min
							</td>
						</tr>
								
						<tr>
							<td style="text-align:right">Motif du rendez-vous</td>
							<td style="text-align:center">
								<textarea name="motifrdv" id="motifrdv" value="" style="margin:auto; height:50px; max-width:500px; min-height:50px; min-width:50px;background:#f8f8f8;" placeholder="Tapez ici............"><?php
								if($comptRdv!=0 AND $motifRdv!="")
								{
									echo $motifRdv;
								}else{
									echo "";
								}
								?></textarea>

							</td>
						</tr>
						
					</table>

				</td>
			</tr>
		</table>
		
			<table align="center">
				<tr>
					<?php
					if(isset($_GET['idconsu']) and $comptidM!=0 and !isset($_GET['forBilling']))
					{
					?>
					<input type="hidden" name="idopereMedPa" value="<?php echo $_GET['idconsu'];?>"/>
					
					<td colspan=2>
					
						<button style="width:300px; margin-top:10px;" type="submit" name="updatebtn" id="updatebtn" class="btn-large">
							<i class="fa fa-check fa-lg fa-fw"></i> <?php echo getString(141) ?>
						</button>
						
					</td>
					
					<?php
					}else{
						if($comptidM!=0)
						{
					?>
							<td>
								<button style="width:300px; margin-top:10px;" type="submit" name="savebtn" id="savebtn" class="btn-large">
									<i class="fa fa-check fa-lg fa-fw"></i> <?php echo getString(141) ?>
								</button>
							</td>
							<td>
								<a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 140px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140) ?></a>
							</td>
					<?php
						}
					}
					?>
				</tr>
			</table>
		
		</form>

		
		</div>
		

<?php
	}
?>
</div>

<?php
}

if(isset($_GET['showmore']))
{
	$numero = $_GET['num'];
	$idConsu = $_GET['idconsu'];
	$dateconsu = $_GET['dateconsu'];
	
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptConsult=$resultConsult->rowCount();
	
	
	
		$resultMedMotif=$connexion->prepare('SELECT *FROM med_motif mm WHERE mm.id_consumotif=:idConsu ORDER BY mm.id_medmotif');	
		$resultMedMotif->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedMotif->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedMotif=$resultMedMotif->rowCount();
	
	
	
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idConsu ORDER BY mc.id_medconsu');	
		$resultMedConsult->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedConsult=$resultMedConsult->rowCount();
	
	
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_consuInf=:idConsu ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedInf=$resultMedInf->rowCount();
	
	
	
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idConsu ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedLabo=$resultMedLabo->rowCount();
	
	
	
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_consuRadio=:idConsu ORDER BY mr.id_medradio');		
		$resultMedRadio->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedRadio=$resultMedRadio->rowCount();
	
	
	
	
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.id_consuConsom=:idConsu ORDER BY mco.id_medconsom');	
		$resultMedConsom->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedConsom=$resultMedConsom->rowCount();
	
	
	
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.id_consuMedoc=:idConsu ORDER BY mdo.id_medmedoc');		
		$resultMedMedoc->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedMedoc=$resultMedMedoc->rowCount();	
	
	
	
		$resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk WHERE mk.id_consuKine=:idConsu ORDER BY mk.id_medkine');		
		$resultMedKine->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedKine->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedKine=$resultMedKine->rowCount();
	
	
	
		$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo WHERE mo.id_consuOrtho=:idConsu ORDER BY mo.id_medortho');		
		$resultMedOrtho->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedOrtho=$resultMedOrtho->rowCount();
	
	
	
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.id_consuSurge=:idConsu ORDER BY ms.id_medsurge');		
		$resultMedSurge->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedSurge=$resultMedSurge->rowCount();
	
	
	
	
		$resultPreDia=$connexion->prepare('SELECT *FROM prepostdia p WHERE p.id_consudia=:idConsudia AND (p.autrepredia!="" OR p.id_predia IS NOT NULL) ORDER BY p.id_dia');		
		$resultPreDia->execute(array(
		'idConsudia'=>$idConsu	
		));
		
		$resultPreDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptPreDia=$resultPreDia->rowCount();
	
	
	
	
		$resultPostDia=$connexion->prepare('SELECT *FROM prepostdia p WHERE p.id_consudia=:idConsudia AND (p.autrepostdia!="" OR p.id_postdia IS NOT NULL) ORDER BY p.id_dia');		
		$resultPostDia->execute(array(
		'idConsudia'=>$idConsu	
		));
		
		$resultPostDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptPostDia=$resultPostDia->rowCount();
	
	
	
	
	
	$start_week=strtotime("last week");
	$start_week=date("Y-m-d",$start_week);
				
	$getConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.dateconsu >=:startweek AND c.id_uM=:idMed ORDER BY c.id_consu DESC LIMIT 1');		
	$getConsult->execute(array(
	'num'=>$_GET['num'],
	'startweek'=>$start_week,
	'idMed'=>$_SESSION['id']
	));

	$comptGetConsult=$getConsult->rowCount();
	
	$getConsult->setFetchMode(PDO::FETCH_OBJ);
	
	if($comptGetConsult!=0)
	{
		if($ligneGetConsult=$getConsult->fetch())
		{
			$idconsult=$ligneGetConsult->id_consu;
			
			$resultatsTypeConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assu.' p WHERE c.id_typeconsult=p.id_prestation AND p.id_prestation=:idTypeconsu') or die( print_r($connexion->errorInfo()));
			$resultatsTypeConsu->execute(array(
			'idTypeconsu'=>$ligneGetConsult->id_typeconsult
			));

			$resultatsTypeConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				
			if($ligneTypeConsu=$resultatsTypeConsu->fetch())//on recupere la liste des éléments
			{
				if($ligneTypeConsu->namepresta!="")
				{
					$nomTypeConsult = $ligneTypeConsu->namepresta;
				}else{
					$nomTypeConsult = $ligneTypeConsu->nompresta;
				}
			}
			
			if(($ligneGetConsult->id_factureConsult!=NULL OR ($nomTypeConsult =="Pas de consultation" OR $nomTypeConsult =="No Consultation")) AND $ligneGetConsult->dateconsu == $annee)
			{
?>
			<a href="consult.php?num=<?php echo $ligneGetConsult->numero;?>&idtypeconsult=<?php echo $ligneGetConsult->id_typeconsult;?>&idconsu=<?php echo $_GET['idconsu'];?>&idassuconsu=<?php echo $ligneGetConsult->id_assuConsu;?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>

			
	<?php
			}else{
				if($ligneGetConsult->dateconsu == $annee)
				{
			?>
				<!-- <span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 5px;"><?php echo getString(218) ?></span> -->
				<a href="consult.php?num=<?php echo $ligneGetConsult->numero;?>&idtypeconsult=<?php echo $ligneGetConsult->id_typeconsult;?>&idconsu=<?php echo $_GET['idconsu'];?>&idassuconsu=<?php echo $ligneGetConsult->id_assuConsu;?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>
				
			<?php
			}else{
				// echo "<b>-- Consultation Has Been Expired --</b>";

			}
		  }
		}
	}else{
		$idconsult=0;
	?>
		---No consultation---
	<?php
	}
	?>
		
		<?php
					
		$getProfil=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num');		
		$getProfil->execute(array(
		'num'=>$_GET['num']
		));

		$comptgetProfil=$getProfil->rowCount();
		
		$getProfil->setFetchMode(PDO::FETCH_OBJ);
		
		if($comptgetProfil!=0)
		{
			if($ligneGetProfil=$getProfil->fetch())
			{
		?>
			<a href="consult.php?num=<?php echo $ligneGetProfil->numero?>&showfiche=ok&idconsu=<?php echo $ligneGetProfil->id_consu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient" class="btn-large buttonBill"><span title="View Profile" name="fichebtn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(100);?></span></a>
			
		<?php 
			}
		}else{
		?>
			<span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 25px;"><?php echo getString(208) ?></span>
		<?php 
		}
		?>
		<?php
			if (isset($_GET['showmore'])) {
		?>
			<a href="PrintFishe.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idtypeconsult'])){ echo '&idtypeconsult='.$_GET['idtypeconsult'];}?><?php if(isset($_GET['idassuconsu'])){ echo '&idassuconsu='.$_GET['idassuconsu'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&showmore=ok" class="btn-large buttonBill"><span title="View Profile" name="fichebtn"><i class="fa fa-print fa-lg fa-fw"></i>Print</span></a>
		<?php
			}
		?>
		<br/>
		<br/>

	<div id="showmore">	

<?php

	if($ligneConsult=$resultConsult->fetch())//on recupere la liste des éléments
	{
?>
	
		<span style="position:relative; font-size:200%;margin-bottom:2px; padding:5px;"><?php echo getString(129) ?> <span style="color:#a00000; font-size:120%; font-weight:100;"><?php echo date('d-M-Y', strtotime($ligneConsult->dateconsu));?></span></span>
		
		<table class="tablesorter" style="margin:30px auto auto; width:50%; padding-left:50px;" cellpadding=3>
			
			<tr>
				<td style="width:40%; text-align:left; vertical-align:top;">
					<span style="font-weight:bold;"><?php echo getString(113) ?> : </span>
					<?php
					
					$idassuConsu=$ligneConsult->id_assuConsu;
							
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
					$assuCount = $comptAssuConsu->rowCount();
					
					for($i=1;$i<=$assuCount;$i++)
					{
						
						$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
						$getAssuConsu->execute(array(
						'idassu'=>$idassuConsu
						));
						
						$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

						if($ligneNomAssu=$getAssuConsu->fetch())
						{
							$presta_assuConsu='prestations_'.strtolower($ligneNomAssu->nomassurance);
						}
					}

					
					$resultatsTypeConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assuConsu.' p WHERE c.id_typeconsult=p.id_prestation AND p.id_prestation=:idTypeconsu') or die( print_r($connexion->errorInfo()));
					$resultatsTypeConsu->execute(array(
					'idTypeconsu'=>$ligneConsult->id_typeconsult
					));

					$resultatsTypeConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($ligneTypeConsu=$resultatsTypeConsu->fetch())//on recupere la liste des éléments
					{
						if($ligneTypeConsu->namepresta!="")
						{
							echo $ligneTypeConsu->namepresta;
						
						}else{
						
							echo $ligneTypeConsu->nompresta;
						}
					}
					?>
				</td>
			</tr>
		</table>
		
		<table style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 10px; width: 80%; border: 1px solid rgb(238, 238, 238); margin: 10px auto auto; border-radius: 4px;" cellpadding=3>
			<tr>						
				<td style="text-align: center; width: 18%; border-right: 1px solid #ccc;">
					<?php echo getString(115); ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->poids != null)
					{
					?>						
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->poids ;?></span>
						<span style="font-size:90%;">Kg</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
					
				</td>
				
				<td style="text-align: center; width: 18%; border-right: 1px solid #ccc;">
					<?php echo 'Taille'; ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->taille != null)
					{
					?>
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->taille ;?></span>
						<span style="font-size:90%;">Cm</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>					
				</td>
				
				<td style="text-align: center; width: 20%; border-right: 1px solid #ccc;">
					<?php echo getString(116); ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->temperature != null)
					{
					?>
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->temperature ;?></span>
						<span style="font-size:90%;">°C</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
				</td>

				<td style="text-align: center; width: 20%; border-right: 1px solid #ccc;">
					<?php echo 'Oxgen'; ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->oxgen != null)
					{
					?>
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->oxgen ;?></span>
						<span style="font-size:90%;">O<sub>2</sub></span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
				</td>
				
				<td style="text-align: center; width: 26%; border-right: 1px solid #ccc;">
					<?php echo getString(117); ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->tensionart != null)
					{
					?>						
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->tensionart ;?></span>
						<span style="font-size:90%;">mmHg</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
				</td>
				
				<td style="text-align: center; width: 18%;">
					<?php echo 'Pouls'; ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->pouls != null)
					{ 
					?>
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->pouls ;?></span>
						<span style="font-size:90%;">/min</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
				</td>
				
			</tr>
		</table>
		
		<?php
		if($comptMedMotif!=0)
		{
		?>
		<table style="width:50%; margin-top:15px;" class="tablesorter" cellspacing="0" align="center"> 
			
			<thead> 
				<tr style="height:45px;">
					<th style="font-size:20px; width:15%; border-radius:0; color:#333; background:rgb(228,228,228) !important" colspan=10><?php echo getString(154); ?></th>
				</tr> 
			</thead> 
			
			<tbody> 
		
				<tr>
					<?php
					while($ligneMedMotif=$resultMedMotif->fetch())
					{
						$resultatsPrestaMotif=$connexion->prepare('SELECT *FROM med_motif mm, motifs m WHERE m.id_motif=mm.id_motif AND mm.id_motif=:idConsu') or die( print_r($connexion->errorInfo()));
						$resultatsPrestaMotif->execute(array(
						'idConsu'=>$ligneMedMotif->id_motif
						));

						$resultatsPrestaMotif->setFetchMode(PDO::FETCH_OBJ);
							
						if($ligneMotif=$resultatsPrestaMotif->fetch())
						{ 
							if($ligneMotif->nommotif !="")
							{
								$nomMotif = $ligneMotif->nommotif;
							}
						}else{
							$nomMotif = $ligneMedMotif->autremotif;
						}						
						
						echo '<td style="padding:10px; text-align: center; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">'.$nomMotif.'</td>';
					}
					?>
				</tr>
			</tbody>
				
		</table>
		<?php
		}
		?>
		
		<table class="cons-info" cellpadding=3 style="margin-bottom:15px;">

			<tr>
				<?php 		
				if($ligneConsult->motif != "")
				{
				?>
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo getString(154) ?></td>
				<?php 		
				}		
				if($ligneConsult->anamnese != "")
				{
				?>
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo 'Anamnèse'; ?></td>
				<?php 		
				}		
				if($ligneConsult->clihist != "")
				{
				?>
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo 'Clinical History'; ?></td>
				<?php 		
				}
				if($ligneConsult->etatpatient != "")
				{
				?>				
					<td style="border-bottom:1px solid #bbb; font-weight:bold; text-align:center;"><?php echo getString(155) ?></td>
				<?php 		
				}
				if($ligneConsult->antecedent != "")
				{
				?>				
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo getString(156) ?></td>
				<?php 		
				}
				if($ligneConsult->allergie != "")
				{
				?>				
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo 'Allergie'; ?></td>
				<?php 		
				}
				if($ligneConsult->examcli != "")
				{
				?>				
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo 'Examen Clinique'; ?></td>
				<?php 		
				}
				if($ligneConsult->signsymptomes != "")
				{
				?>
					<td style="border-bottom:1px solid #bbb; font-weight:bold; text-align:center;"><?php echo getString(157) ?></td>
	<?php		}
	?>
			</tr>
			
			<tr>
				<?php 		
				if($ligneConsult->motif != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->motif)?></textarea>
					</td>
				<?php		
				}
				if($ligneConsult->anamnese != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->anamnese)?></textarea>
					</td>
				<?php		
				}
				if($ligneConsult->clihist != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->clihist)?></textarea>
					</td>
				<?php		
				}
				if($ligneConsult->etatpatient != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->etatpatient)?></textarea>
					</td>
				<?php		
				}
				if($ligneConsult->antecedent != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->antecedent)?></textarea>
					</td>
				<?php
				}
				if($ligneConsult->allergie != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->allergie)?></textarea>
					</td>
				<?php
				}
				if($ligneConsult->examcli != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->examcli)?></textarea>
					</td>
				<?php
				}
				if($ligneConsult->signsymptomes != "")
				{
				?>			
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->signsymptomes)?></textarea>
					</td>
				<?php		
				}
				?>				
			</tr>
	
		</table>

	<?php
	}
	?>
				
	<?php
	$Postdia = array();
	$DiagnoPostDone=0;
										
	$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
	
	$resuPostdiagnostic->execute(array(
	'idConsu'=>$idConsu
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
						
	if($DiagnoPostDone ==0)
	{
	?>			
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px;" id="prediagnotable">
			<?php
			$Predia = array();
			$DiagnoPreDone=0;
										
			$resuPrediagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
			
			$resuPrediagnostic->execute(array(
			'idConsu'=>$idConsu
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
					$DiagnoPreDone=1;
				}else{
				
					if($lignePrediagnostic->prediagnostic != "")
					{
						$Predia[] = $lignePrediagnostic->prediagnostic;
						$DiagnoPreDone=1;
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
						$DiagnoPreDone=1;
					}else{
						$Predia[] = $linePrediagno->autrepredia;
						$DiagnoPreDone=1;
					}
					
				}
			
			}
				
			if($DiagnoPreDone==1)
			{
			?>
			<table style="width:50%;" class="tablesorter" cellspacing="0" align="center"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="font-size:20px; width:15%; border-radius:0; color:#333; background:rgb(228,228,228) !important" colspan=10><?php echo getString(246); ?></th>
					</tr> 
				</thead> 
				
				<tbody> 
			
					<tr>
						<?php
						
						for($p=0;$p<sizeof($Predia);$p++)
						{
							echo '<td style="padding:10px; text-align: center; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">'.$Predia[$p].'</td>';
						}
						
						/* if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
						{
							echo getString(209) .$ligneDiagnoPost->nomdiagno. " - " .getString(210);
						}else{
							echo getString(209) .$prediagno. " - " .getString(210);
						} */
						?>
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
	
	
	<?php
	if($comptMedConsult!=0)
	{
	?>		
		<span style="position:relative; font-weight:400; font-size:250%; margin-bottom:10px; padding:5px;"><?php echo "Services"; ?></span>

		<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
		
			<table style="width:70%;" class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr style="height:45px;">
						<th style="border-radius:0; color:#333; background:#ccc !important">Services</th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedConsult=$resultMedConsult->fetch())//on recupere la liste des éléments
						{
							
							$idassuServ=$ligneMedConsult->id_assuServ;
							
							$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
							$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuServ->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuServ=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuServ->execute(array(
								'idassu'=>$idassuServ
								));
								
								$getAssuServ->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuServ=$getAssuServ->fetch())
								{
									$presta_assuServ='prestations_'.strtolower($ligneNomAssuServ->nomassurance);
								}
							}
							
					?>
						<tr style="text-align:center;">
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
						
							<?php									
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_consult c, '.$presta_assuServ.' p WHERE c.id_prestationConsu=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedConsult->id_prestationConsu
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{
							?>
							
							<?php 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
								
								echo $ligneMedConsult->autreConsu;
							?>
							</td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedConsult->dateconsu != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsult->dateconsu));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align:center;">
							<?php
														
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedConsult->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedConsult->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>	
						</tr>

					<?php
						}
						$resultMedConsult->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}/* else{
	?>
		<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(131) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	} */

	if($comptMedInf!=0)
	{
?>
		<span style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;"><?php echo getString(98) ?></span>
		
		<div style="overflow:auto;height:auto;padding:5px; margin-bottom:30px;">
		
			<table style="width:80%;" class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr style="height:45px;">
						<th style="width:25%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(98) ?></th>
						<th style="width:15%; color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="width:30%; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:30%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des éléments
						{
							
							$idassuInf=$ligneMedInf->id_assuInf;
							
							$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
							$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuInf->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuInf=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuInf->execute(array(
								'idassu'=>$idassuInf
								));
								
								$getAssuInf->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuInf=$getAssuInf->fetch())
								{
									$presta_assuInf='prestations_'.strtolower($ligneNomAssuInf->nomassurance);
								}
							}
							
					?>
						<tr style="text-align:center;">
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_inf mi, '.$presta_assuInf.' p WHERE mi.id_prestation=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedInf->id_prestation
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{ 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
							
								echo $ligneMedInf->autrePrestaM;
							?>
							</td>
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedInf->datesoins != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedInf->datesoins));}else{ echo '';}?></td>
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
												
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedInf->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
							
								if($ligneMedInf->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>	
							<td style="padding:10px; text-align:center;">
							<?php
														
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedInf->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedInf->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>	
						</tr>

					<?php
						}
						$resultMedInf->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}/* else{
	?>
		<table style="margin-bottom:30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(104) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	} */
	
	if($comptMedLabo!=0)
	{
?>
		<span style="position:relative; font-size:250%;margin-bottom: 2px;padding:5px;"><?php echo getString(133) ?></span>
		
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px" id="labotable">
		
			<table class="tablesorter" cellspacing="0"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(99) ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(3) ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Valeur';?></th>
					    <th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Valeur (min-max)';?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(22) ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date</th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important" colspan=4><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des éléments
						{
							
							$idassuLab=$ligneMedLabo->id_assuLab;

							$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
							$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuLab->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuLab->execute(array(
								'idassu'=>$idassuLab
								));
								
								$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuLab=$getAssuLab->fetch())
								{
									$presta_assuLab='prestations_'.strtolower($ligneNomAssuLab->nomassurance);
								}
							}

							if($ligneMedLabo->moreresultats!=0)
							{
						?>
							<tr>
								
								<td style="background:#eee; padding:10px; text-align: center; border-right: 1px solid #ccc; font-weight:bold;" colspan=4>
								
								<?php									
								$resultatsPresta=$connexion->prepare('SELECT *FROM med_labo ml, '.$presta_assuLab.' p WHERE ml.id_prestationExa=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
								$resultatsPresta->execute(array(
								'idConsu'=>$ligneMedLabo->id_prestationExa
								));

								$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
								{ 
									if($lignePresta->namepresta!="")
									{
										echo $lignePresta->namepresta;
									}else{
										echo $lignePresta->nompresta;
									}
								}
								
									echo $ligneMedLabo->autreExamen;
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php 

								$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=:idL') or die( print_r($connexion->errorInfo()));
								$resultatsLabo->execute(array(
								'idL'=>$ligneMedLabo->id_uL
								));

								$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								if($ligneLabo=$resultatsLabo->fetch())//on recupere la liste des éléments
								{
									echo $ligneLabo->full_name;
								}else{
									echo '';
								}
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
									<?php 
									if($ligneMedLabo->dateresultats!="0000-00-00")
									{
										echo date('d-M-Y', strtotime($ligneMedLabo->dateresultats));
									}else{
										echo '';
									}								
									?>
								</td>
															
								<td style="padding:10px; text-align: center;" colspan=4>
								<?php
								$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
								$resultatsMed->execute(array(
								'idMed'=>$ligneMedLabo->id_uM
								));

								$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
								{
									if($ligneMedLabo->id_uM==$ligneMed->id_u)
									{
										echo $ligneMed->full_name;
									}else{
										echo '';
									}
								}
								$resultatsMed->closeCursor();
								?>						
								</td>			
							</tr>
						
								<?php
								if($ligneMedLabo->moreresultats==1)
								{
							
									$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');		
									$resultMoreMedLabo->execute(array(
									'num'=>$_GET['num'],
									'idmedLab'=>$ligneMedLabo->id_medlabo
									));
									
									$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
									
									while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
									{
								?>									
									<tr>
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM nfssubexams p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										$lignePresta=$resultPresta->fetch();
											if($comptPresta!=0)
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
												$presta=$ligneMoreMedLabo->autreExamen;
												$mesure='';
												echo $ligneMoreMedLabo->autreExamen;
											}
										?>
										</td>
										
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px;font-weight:bold;">
										<?php echo $ligneMoreMedLabo->autreresultats;?>
										</td>
										
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px;font-weight:bold;">
										<?php echo $ligneMoreMedLabo->valeurLab;?>
										</td>
										<td>
										<?php
											//  $ages = 17;
											//  echo $ages;
											// echo $sexe;
											if($comptPresta!=0){
											if($ages<18){
											echo $lignePresta->rangesChildren; 
											//    echo "child";
											}else{
												if($sexe=="Male"){
													echo $lignePresta->rangesMen; 
													// echo "men";
												}else{
													if($sexe=="Female"){
														echo $lignePresta->rangesWomen; 
														// echo "women";
													}
												}
											}
											}
										?>
										 <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
										</td>

										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px;display:none">
										<?php
										if($ligneMoreMedLabo->resultats!="")
										{
										?>
											<span><?php echo 'Un fichier a été joint sur ces résultats';?></span>
										<?php
										}
										?>
										</td>
								
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px" colspan=6></td>
										
									</tr>
									
								<?php
									}
								}
							
								if($ligneMedLabo->moreresultats==2)
								{
									$resultSpermoMedLabo=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medlabo=:idmedLab ORDER BY sml.id_spermomedlabo');		
								
									$resultSpermoMedLabo->execute(array(
									'num'=>$_GET['num'],
									'idmedLab'=>$ligneMedLabo->id_medlabo
									));
									
									$resultSpermoMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptSpermoMedLabo=$resultSpermoMedLabo->rowCount();
									
									while($ligneSpermoMedLabo=$resultSpermoMedLabo->fetch())
									{
									?>									
									<tr>
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
									
									<tr>	
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
								
								echo '<tr style="background-color:#eee;height:0;min-height:0;">
									<td style="padding:5px" colspan=11></td>
								</tr>';
							
							}else{
							?>
							<tr style="text-align:center;">
							
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;font-weight:bold;">
								<?php									
								$resultatsPresta=$connexion->prepare('SELECT *FROM med_labo ml, '.$presta_assuLab.' p WHERE ml.id_prestationExa=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
								$resultatsPresta->execute(array(
								'idConsu'=>$ligneMedLabo->id_prestationExa
								));

								$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
								{ 
									if($lignePresta->namepresta!="")
									{
										$presta=$lignePresta->namepresta;
									}else{
										$presta=$lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneMedLabo->autreExamen;
									$mesure='';
								}
									echo $presta;
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php
								if($ligneMedLabo->autreresultats!="")
								{
									echo $ligneMedLabo->autreresultats.' <span style="font-size:80%; font-weight:normal;padding:5px;"> '.$mesure.'</span>';
								}else
								{
									echo "<span style='color:#bc0000'>En attente...</span>";
								}
								?>
								</td>
									
								<td style="text-align:center;border-right: 1px solid #ccc;"><?php echo $ligneMedLabo->valeurLab;?></td>
								
								
								
							<td style="text-align:center;text-align:center;border-right: 1px solid #ccc;">
										<?php
										
										$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE nomexam=:nomexam ORDER BY valeur');
										$resultValeur->execute(array(
										'nomexam'=>$presta
										));
										
										$resultValeur->setFetchMode(PDO::FETCH_OBJ);

										$comptValeur=$resultValeur->rowCount();
										
										if($comptValeur!=0)
										{
											$v=0;
											while($ligneValeur=$resultValeur->fetch())
											{
											?>
												
												<table class="printPreview tablesorter3" cellspacing="0" style="width:100%;">
													<tr>
														<td style="text-align:center;">
														<?php 
														/* if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL)
														{ */
														?>
															<span type="text" id="valeur<?php echo $v;?>" name="valeur[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL){ echo $ligneValeur->valeur;}else{ echo '---';}?></span>
														<?php 
														// }
														
														if($ligneValeur->min_valeur !="" OR $ligneValeur->max_valeur !="")
														{
														?>
														( 
														<span type="text" id="min<?php echo $v;?>" name="min[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->min_valeur !=""){ echo $ligneValeur->min_valeur;}?></span> 
														- 
														<span type="text" id="max<?php echo $v;?>" name="max[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->max_valeur !=""){ echo $ligneValeur->max_valeur;}?></span> )
														<?php
														}
														?>
														</td>
													</tr>						
												</table>						
										<?php
												$v++;
											}
										}
										?>
										</td>
																
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;display:none">
								<?php
								if($ligneMedLabo->resultats!="")
								{
								?>
									<a href="<?php echo $ligneMedLabo->resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-paperclip fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
								<?php
								}
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php 

								$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=:idL') or die( print_r($connexion->errorInfo()));
								$resultatsLabo->execute(array(
								'idL'=>$ligneMedLabo->id_uL
								));

								$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								if($ligneLabo=$resultatsLabo->fetch())//on recupere la liste des éléments
								{
									echo $ligneLabo->full_name;
								}else{
									echo '';
								}
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
									<?php 
									if($ligneMedLabo->dateresultats!="0000-00-00")
									{
										echo date('d-M-Y', strtotime($ligneMedLabo->dateresultats));
									}else{
										echo '';
									}								
									?>
								</td>
															
								<td style="padding:10px; text-align: center;" colspan=4>
								<?php
								$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
								$resultatsMed->execute(array(
								'idMed'=>$ligneMedLabo->id_uM
								));

								$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
								{
									if($ligneMedLabo->id_uM==$ligneMed->id_u)
									{
										echo $ligneMed->full_name;
									}else{
										echo '';
									}
								}
								$resultatsMed->closeCursor();
								?>						
								</td>			
								
							</tr>
							<?php								
							}
						
						
						}
						$resultMedLabo->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}/* else{
	?>
		<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(107) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	} */	
	
	if($comptMedRadio!=0)
	{
?>
		<span style="position:relative; font-size:250%;margin-bottom: 2px;padding:5px;"><?php echo 'Radiologie'; ?></span>
		
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px" id="radiotable">
		
			<table class="tablesorter" cellspacing="0"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Radio demandée' ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date Résultats</th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Done by'; ?></th>						
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedRadio=$resultMedRadio->fetch())//on recupere la liste des éléments
						{
							
							$idassuRad=$ligneMedRadio->id_assuRad;

							$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
							$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuRad->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuRad=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuRad->execute(array(
								'idassu'=>$idassuRad
								));
								
								$getAssuRad->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuRad=$getAssuRad->fetch())
								{
									$presta_assuRad='prestations_'.strtolower($ligneNomAssuRad->nomassurance);
								}
							}

					?>
						<tr style="text-align:center;">
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;" <?php if($ligneMedRadio->resultatsRad !=""){ echo 'colspan=3';}?>>
							<?php									
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_radio mr, '.$presta_assuRad.' p WHERE mr.id_prestationRadio=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedRadio->id_prestationRadio
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{ 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
							
								echo $ligneMedRadio->autreRadio;
							?>
							</td>
							<?php 
							if($ligneMedRadio->resultatsRad =="")
							{
							?>
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">								
							</td>
								
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							</td>
							<?php
							}
							?>						
							<td style="padding:10px; text-align: center;">
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedRadio->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedRadio->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>			
							
						</tr>
						<?php
						if($ligneMedRadio->resultatsRad !="")
						{
						?>
						<tr style="text-align:center;background:#eee">
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-height: 300px; max-width: 500px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 80px; min-height: 80px;"readonly='readonly'><?php echo strip_tags($ligneMedRadio->resultatsRad)?></textarea>
							</td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php 
								if($ligneMedRadio->dateradio!="0000-00-00")
								{
									echo date('d-M-Y', strtotime($ligneMedRadio->dateradio));
								}else{
									echo '';
								}								
								?>
							</td>
								
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php 

							$resultatsRadio=$connexion->prepare('SELECT *FROM utilisateurs u, medecins x WHERE u.id_u=x.id_u AND x.id_u=:idX') or die( print_r($connexion->errorInfo()));
							$resultatsRadio->execute(array(
							'idX'=>$ligneMedRadio->id_uX
							));

							$resultatsRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneRadio=$resultatsRadio->fetch())//on recupere la liste des éléments
							{
								echo $ligneRadio->full_name;
							}else{
								echo '';
							}
							?>
							</td>
													
							<td style="padding:10px; text-align: center;">						
							</td>			
							
						</tr>
						<?php
						}
						?>
						
					<?php
						}
						$resultMedRadio->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}/* else{
	?>
		<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(107) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	} */	

	if($comptMedConsom!=0)
	{
?>
		<span style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;"><?php echo 'Consommables'; ?></span>
		
		<div style="overflow:auto;height:auto;padding:5px; margin-bottom:30px;">
		
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th style="color:#333; background:#ccc !important"><?php echo 'Consommables' ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo 'Quantity' ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedConsom=$resultMedConsom->fetch())
						{
							$idassuConsom=$ligneMedConsom->id_assuConsom;
							
							$comptAssuConsom=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
							$comptAssuConsom->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuConsom->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuConsom=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsom->execute(array(
								'idassu'=>$idassuConsom
								));
								
								$getAssuConsom->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuConsom=$getAssuConsom->fetch())
								{
									$presta_assuConsom='prestations_'.strtolower($ligneNomAssuConsom->nomassurance);
								}
							}

							
					?>
						<tr style="text-align:center;">
							<td style="text-align:center;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_consom mco, '.$presta_assuConsom.' p WHERE mco.id_prestationConsom=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedConsom->id_prestationConsom
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{ 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
							
								echo $ligneMedConsom->autreConsom;
							?>
							</td>
							
							<td><b style="border-radius: 5px;background: #bb090973;padding: 10px 30px;"><?php echo $ligneMedConsom->qteConsom;?></b></td>
							<td><?php if($ligneMedConsom->dateconsu != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsom->dateconsu));}else{ echo '';}?></td>
							
							<td>
							<?php
	
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedConsom->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedConsom->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>	
						</tr>

					<?php
						}
						$resultMedConsom->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}	
	
	
	
	if($comptMedMedoc!=0)
	{
?>
		<span style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;"><?php echo 'Medicaments'; ?></span>
		
		<div style="overflow:auto;height:auto;padding:5px; margin-bottom:30px;">
		
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th style="color:#333; background:#ccc !important"><?php echo 'Medicaments' ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo 'Quantity' ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedMedoc=$resultMedMedoc->fetch())//on recupere la liste des éléments
						{
							
							$idassuMedoc=$ligneMedMedoc->id_assuMedoc;
						
							$comptAssuMedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
							$comptAssuMedoc->setFetchMode(PDO::FETCH_OBJ);
									
							$assuCount = $comptAssuMedoc->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuMedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuMedoc->execute(array(
								'idassu'=>$idassuMedoc
								));
								
								$getAssuMedoc->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuMedoc=$getAssuMedoc->fetch())
								{
									$presta_assuMedoc='prestations_'.strtolower($ligneNomAssuMedoc->nomassurance);
								}
							}

					?>
						<tr style="text-align:center;">
							<td style="text-align:center;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_medoc mdo, '.$presta_assuMedoc.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedMedoc->id_prestationMedoc
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{ 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
							
								echo $ligneMedMedoc->autreMedoc;
							?>
							</td>
							<td><b style="border-radius: 5px;background: #bb090973;padding: 10px 30px;"><?php echo $ligneMedMedoc->qteMedoc;?></b></td>

							<td><?php if($ligneMedMedoc->dateconsu != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedMedoc->dateconsu));}else{ echo '';}?></td>
							
							<td>
							<?php
	
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedMedoc->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedMedoc->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>	
						</tr>

					<?php
						}
						$resultMedMedoc->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}

		$idcategopresta =21;
		$GetRecomm = $connexion->prepare("SELECT * FROM doctorrecommandations WHERE idcategopresta=:idcategopresta AND numero=:numero AND id_M=:idM AND idconsu=:idconsu ");
		$GetRecomm->execute(array('numero'=>$_GET['num'],'idconsu'=>$_GET['idconsu'],'idM'=>$_SESSION['id'],'idcategopresta'=>$idcategopresta));
		$GetRecomm->setFetchMode(PDO::FETCH_OBJ);
		$comptMedConsomRecomm = $GetRecomm->rowCount();


		if($comptMedConsomRecomm!=0)
		{
		?>		
			<span style="position:relative; font-weight:400; font-size:250%; margin-bottom:10px; padding:5px;" ><?php echo "Recommanded Consomables"; ?></span>

			<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
			
				<table style="width:80%;" class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr style="height:45px;">
							<th style="border-radius:0; color:#333; background:#ccc !important">#</th>
							<th style="border-radius:0; color:#333; background:#ccc !important">Consomables</th>
							<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
							<th style="color:#333; background:#ccc !important"><?php echo'Time'; ?></th>
							<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
						</tr> 
					</thead> 
				
					<tbody>	
						<?php
						try
						{
							$consomm = 1;
							while($ligneMedConsult=$GetRecomm->fetch())//on recupere la liste des éléments
							{
						?>
							<tr style="text-align:center;">
								<td><?php echo $consomm; ?></td>
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							
								<?php									
									echo $ligneMedConsult->recommandations;
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedConsult->duration != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsult->duration));}else{ echo '';}?></td>

								<td><?php echo $ligneMedConsult->timet; ?></td>
								
								<td style="padding:10px; text-align:center;">
								<?php
															
								$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
								$resultatsMed->execute(array(
								'idMed'=>$ligneMedConsult->id_M
								));

								$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
								{
									if($ligneMedConsult->id_M==$ligneMed->id_u)
									{
										echo $ligneMed->full_name;
									}else{
										echo '';
									}
								}
								$resultatsMed->closeCursor();
								?>						
								</td>	
							</tr>

						<?php
							$consomm ++;
							}
							$GetRecomm->closeCursor();
						}

						catch(Excepton $e)
						{
							echo 'Erreur:'.$e->getMessage().'<br/>';
							echo'Numero:'.$e->getCode();
						}
						?>
					</tbody>
				</table>
			</div>	
		<?php	
		} /*else{
		?>
			<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
					<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(131) ?></th>
				</tr>
			</thead> 		
			</table>
		<?php
		} */

		$idcategopresta =22;
		$GetRecomm = $connexion->prepare("SELECT * FROM doctorrecommandations WHERE idcategopresta=:idcategopresta AND numero=:numero AND id_M=:idM AND idconsu=:idconsu");
		$GetRecomm->execute(array('numero'=>$_GET['num'],'idconsu'=>$_GET['idconsu'],'idM'=>$_SESSION['id'],'idcategopresta'=>$idcategopresta));
		$GetRecomm->setFetchMode(PDO::FETCH_OBJ);
		$comptMedConsomRecomm = $GetRecomm->rowCount();


		if($comptMedConsomRecomm!=0)
		{
		?>		
			<span style="position:relative; font-weight:400; font-size:250%; margin-bottom:10px; padding:5px;"><?php echo "Recommended Drugs"; ?></span>

			<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
			
				<table style="width:80%;" class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr style="height:45px;">
							<th style="border-radius:0; color:#333; background:#ccc !important">#</th>
							<th style="border-radius:0; color:#333; background:#ccc !important">Drugs</th>
							<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
							<th style="color:#333; background:#ccc !important"><?php echo 'Time' ?></th>
							<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
						</tr> 
					</thead> 
				
					<tbody>	
						<?php
						try
						{
							$medoc = 1;
							while($ligneMedConsult=$GetRecomm->fetch())//on recupere la liste des éléments
							{
						?>
							<tr style="text-align:center;">
								<td><?php echo $medoc; ?></td>
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							
								<?php									
									echo $ligneMedConsult->recommandations;
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedConsult->duration != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsult->duration));}else{ echo '';}?></td>

								<td><?php echo $ligneMedConsult->timet; ?></td>
								
								<td style="padding:10px; text-align:center;">
								<?php
															
								$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
								$resultatsMed->execute(array(
								'idMed'=>$ligneMedConsult->id_M
								));

								$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
								{
									if($ligneMedConsult->id_M==$ligneMed->id_u)
									{
										echo $ligneMed->full_name;
									}else{
										echo '';
									}
								}
								$resultatsMed->closeCursor();
								?>						
								</td>	
							</tr>

						<?php
							$medoc ++;
							}
							$GetRecomm->closeCursor();
						}

						catch(Excepton $e)
						{
							echo 'Erreur:'.$e->getMessage().'<br/>';
							echo'Numero:'.$e->getCode();
						}
						?>
					</tbody>
				</table>
			</div>	
		<?php	
		} /*else{
		?>
			<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
					<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(131) ?></th>
				</tr>
			</thead> 		
			</table>
		<?php
		} */
	?>
					
	<?php 
	if($DiagnoPostDone !=0)
	{
	?>
		
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px;" id="postdiagnotable">
		
			<table style="width:50%;" class="tablesorter" cellspacing="0" align="center"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="font-size:20px; width:15%; border-radius:0; color:#333; background:rgb(228,228,228) !important" colspan=10><?php echo getString(247); ?></th>
					</tr> 
				</thead> 
				
				<tbody> 
			
					<tr>
						<?php
						
						for($p=0;$p<sizeof($Postdia);$p++)
						{
							echo '<td style="padding:10px; text-align: center; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">'.$Postdia[$p].'</td>';
						}
						
						/* if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
						{
							echo getString(209) .$ligneDiagnoPost->nomdiagno. " - " .getString(210);
						}else{
							echo getString(209) .$postdiagno. " - " .getString(210);
						} */
						?>
					</tr>
				</tbody>
					
			</table>
		</div>
		
	<?php
	}else{
			
		if(isset($_GET['dateconsu']) AND $_GET['dateconsu']!=$annee){
			echo '<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px;" id="postdiagnotable">
		
			<table style="width:50%;" class="tablesorter" cellspacing="0" align="center"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="font-size:20px; width:15%; border-radius:0; color:#333; background:rgb(228,228,228) !important" colspan=10><?php echo getString(247); ?></th>
					</tr> 
				</thead> 
				
				<tbody> 
					<tr>
						<td>Normal</td>
					</tr>
				</tbody>
					
			</table>
		</div>';
		}else{
			echo '
			<table style="width:100%;background:#fff; border:1px solid #eee; border-radius:4px; margin-bottom:10px; padding: 20px;">
				<tr>
					<td style="text-align:center;font-size:30px;">'.getString(267).'
					</td>
				</tr>
			</table>';
		}
	}
	?>
		<?php 
		if($recomm != "" OR $comptMedSurge != "")
		{
		?>
		<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin-bottom:10px; padding: 20px;width:auto;" align="center" id="diagnorecomm">
			<tr>
				<?php 
				if($recomm != "")
				{
				?>
				<td style="text-align:center;">			
					<table>					
						<tr>
							<td style="text-align:center; vertical-align:top; margin-top:100px;">
								<span style="font-weight:bold;font-size:30px;"><?php echo getString(159) ?>
							
							</td>
						</tr>
						
						<tr>
							<td style="text-align:center; vertical-align:top;">
							
								<textarea style="background:#eee;; height: 50px; width: 100px; max-width:600px; min-width:500px; min-height:200px;text-align:center;" readonly="readonly"><?php echo strip_tags($recomm);?></textarea>
								
							</td>
							
						</tr>
					</table>
					
				</td>
				<?php
				}else{
					echo '<td style="text-align:center;font-size:30px;">Pas de traitments proposés pour cette consultation</td>';
				}
				
				if($comptMedSurge!=0)
				{
				?>
				<td style="text-align:center;width:50%">
					
					<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
					
						<table style="width:70%;" class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr style="height:45px;">
									<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(279); ?></th>
									<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19); ?></th>
								</tr> 
							</thead> 
						
							<tbody>	
								<?php
								try
								{
									while($ligneMedSurge=$resultMedSurge->fetch())
									{								
										$idassuSurge=$ligneMedSurge->id_assuSurge;
										
										$comptAssuSurge=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuSurge->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuSurge->rowCount();
										
										for($i=1;$i<=$assuCount;$i++)
										{
											
											$getAssuSurge=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssuSurge->execute(array(
											'idassu'=>$idassuSurge
											));
											
											$getAssuSurge->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssuSurge=$getAssuSurge->fetch())
											{
												$presta_assuSurge='prestations_'.strtolower($ligneNomAssuSurge->nomassurance);
											}
										}
										
								?>
									<tr style="text-align:center;">
										
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
									
										<?php									
										$resultatsPresta=$connexion->prepare('SELECT * FROM med_surge ms, '.$presta_assuSurge.' p WHERE ms.id_prestationSurge=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
										$resultatsPresta->execute(array(
										'idConsu'=>$ligneMedSurge->id_prestationSurge
										));

										$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);
										
										if($lignePresta=$resultatsPresta->fetch())
										{
										?>
										
										<?php 
											if($lignePresta->namepresta!="")
											{
												echo $lignePresta->namepresta;
											}else{
												echo $lignePresta->nompresta;
											}
										}
											
											echo $ligneMedSurge->autrePrestaS;
										?>
										</td>
										
										<td style="padding:10px; text-align:center;">
										<?php
																	
										$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
										$resultatsMed->execute(array(
										'idMed'=>$ligneMedSurge->id_uM
										));

										$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
											
										while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
										{
											if($ligneMedSurge->id_uM==$ligneMed->id_u)
											{
												echo $ligneMed->full_name;
											}else{
												echo '';
											}
										}
										$resultatsMed->closeCursor();
										?>						
										</td>	
									</tr>

								<?php
									}
									$resultMedSurge->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
							</tbody>
						</table>
					</div>	
								
				</td>	
				<?php	
				}
				?>
			</tr>
		</table>
		<?php	
		}
		?>
		
		<table align="center">
			<tr>
				<td style="vertical-align:top;">

					<table class="cons-table" style="margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>
								
					<?php 
					if($hospitalized != NULL)
					{
					?>
						<tr>
							<td style="text-align:center; vertical-align:top; margin-top:100px;">
								<span style="font-weight:bold;font-size:15px;"><?php echo 'Hospitalisé'; ?>
									<input type="checkbox" checked="checked" disabled="disabled"/>
								</span>
							</td>
						</tr>
						
						<tr>
							<td style="text-align:center; vertical-align:top;">
							<?php
							if($motifhospitalized !="")
							{
							?>
								Motif <textarea style="background: #eee; margin:auto; height:50px; width:100px; max-width:180px; max-height:250px; min-height:50px; min-width:100px; text-align:center;" readonly="readonly"><?php echo strip_tags($motifhospitalized);?></textarea>
							<?php
							}/* else{
							?>
								<span style="font-weight:bold;font-size:15px;"><?php echo '---'; ?>
								</span>
							<?php
							} */
							?>
							</td>
							
						</tr>
					<?php
					}
					?>					
					</table>
				</td>
				
				<td style="vertical-align:top;">

					<table class="cons-table" style="margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>
								
					<?php 
					if($physio != NULL)
					{
					?>
						<tr>
							<td style="text-align:center; vertical-align:top; margin-top:100px;">
								<span style="font-weight:bold;font-size:15px;"><?php echo 'Physioterapy'; ?>
									<input type="checkbox" checked="checked" disabled="disabled"/>
								</span>
							</td>
						</tr>
						
						<tr>
							<td style="text-align:center; vertical-align:top;">
							<?php
							if($motifphysio !="")
							{
							?>
								Motif <textarea style="background: #eee; margin:auto; height:50px; width:100px; max-width:180px; max-height:250px; min-height:50px; min-width:100px; text-align:center;" readonly="readonly"><?php echo strip_tags($motifphysio);?></textarea>
								
							<?php
							}/* else{
							?>
								<span style="font-weight:bold;font-size:15px;"><?php echo '---'; ?>
								</span>
							<?php
							} */
							?>								
							</td>
							
						</tr>
					<?php
					}
								
					if($comptMedKine!=0)
					{
					?>
						<tr>
							<td style="text-align:center; vertical-align:top;">
							<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px" id="kinetable">
							
								<table class="tablesorter" cellspacing="0">	
									<thead> 
										<tr style="height:45px;">
											<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo 'Actes' ?></th>
											<th style="border-radius:0; color:#333; background:#ccc !important">Date</th>
											<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo 'Done by'; ?></th>
										</tr> 
									</thead>
								
									<tbody>	
										<?php
										try
										{
											while($ligneMedKine=$resultMedKine->fetch())
											{							
												$idassuKine=$ligneMedKine->id_assuKine;

												$comptAssuKine=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
								
												$comptAssuKine->setFetchMode(PDO::FETCH_OBJ);
														
												$assuCount = $comptAssuKine->rowCount();
												
												for($i=1;$i<=$assuCount;$i++)
												{
													
													$getAssuKine=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
													$getAssuKine->execute(array(
													'idassu'=>$idassuKine
													));
													
													$getAssuKine->setFetchMode(PDO::FETCH_OBJ);

													if($ligneNomAssuKine=$getAssuKine->fetch())
													{
														$presta_assuKine='prestations_'.strtolower($ligneNomAssuKine->nomassurance);
													}
												}

										?>
											<tr style="text-align:center;">
												<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
												<?php									
												$resultatsPresta=$connexion->prepare('SELECT *FROM med_kine mk, '.$presta_assuKine.' p WHERE mk.id_prestationKine=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
												$resultatsPresta->execute(array(
												'idConsu'=>$ligneMedKine->id_prestationKine
												));

												$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
													
												if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
												{ 
													if($lignePresta->namepresta!="")
													{
														echo $lignePresta->namepresta;
													}else{
														echo $lignePresta->nompresta;
													}
												}
												
													echo $ligneMedKine->autrePrestaK;
												?>
												</td>
												
												<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
													<?php 
													if($ligneMedKine->datekine!="0000-00-00")
													{
														echo date('d-M-Y', strtotime($ligneMedKine->datekine));
													}else{
														echo '';
													}								
													?>
												</td>					
												<td style="padding:10px; text-align: center;">
												<?php
												$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
												$resultatsMed->execute(array(
												'idMed'=>$ligneMedKine->id_uK
												));

												$resultatsMed->setFetchMode(PDO::FETCH_OBJ);
													
												while($ligneMed=$resultatsMed->fetch())
												{
													if($ligneMedKine->id_uK==$ligneMed->id_u)
													{
														echo $ligneMed->full_name;
													}else{
														echo '';
													}
												}
												$resultatsMed->closeCursor();
												?>						
												</td>			
												
											</tr>
										<?php
											}
											$resultMedKine->closeCursor();
										}

										catch(Excepton $e)
										{
											echo 'Erreur:'.$e->getMessage().'<br/>';
											echo'Numero:'.$e->getCode();
										}
										?>
									</tbody>
								</table>
							</div>	
							</td>
						</tr>
					<?php			
					}
					?>
					</table>
					
				
				</td>
				
				<?php			
				if($comptMedOrtho!=0)
				{
				?>
				<td style="text-align:center; vertical-align:top;">
				
					<span style="position:relative; font-size:250%;margin-bottom: 2px;"><?php echo 'P&O'; ?></span>
					
					<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px;" id="orthotable">
					
						<table class="tablesorter" cellspacing="0"> 
							
							<thead> 
								<tr style="height:45px;">
									<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Matériel demandé' ?></th>
									<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date</th>
									<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Done by'; ?></th>						
									<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
								</tr> 
							</thead> 
						
						
							<tbody>	
								<?php
								try
								{
									while($ligneMedOrtho=$resultMedOrtho->fetch())
									{							
										$idassuOrtho=$ligneMedOrtho->id_assuOrtho;

										$comptAssuOrtho=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuOrtho->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuOrtho->rowCount();
										
										for($i=1;$i<=$assuCount;$i++)
										{
											
											$getAssuOrtho=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssuOrtho->execute(array(
											'idassu'=>$idassuOrtho
											));
											
											$getAssuOrtho->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssuOrtho=$getAssuOrtho->fetch())
											{
												$presta_assuOrtho='prestations_'.strtolower($ligneNomAssuOrtho->nomassurance);
											}
										}

								?>
									<tr style="text-align:center;">
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;" <?php if($ligneMedOrtho->resultatsOrtho !=""){ echo 'colspan=3';}?>>
										<?php									
										$resultatsPresta=$connexion->prepare('SELECT *FROM med_ortho mo, '.$presta_assuOrtho.' p WHERE mo.id_prestationOrtho=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
										$resultatsPresta->execute(array(
										'idConsu'=>$ligneMedOrtho->id_prestationOrtho
										));

										$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
											
										if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
										{ 
											if($lignePresta->namepresta!="")
											{
												echo $lignePresta->namepresta;
											}else{
												echo $lignePresta->nompresta;
											}
										}
										
											echo $ligneMedOrtho->autrePrestaO;
										?>
										</td>
																
										<td style="padding:10px; text-align: center;">
										<?php
										if($ligneMedOrtho->dateortho!='0000-00-00')
										{
											echo $ligneMedOrtho->dateortho;
										}else{
											echo '';
										}
										?>						
										</td>			
																
										<td style="padding:10px; text-align: center;">
										<?php
										$resultatsOrtho=$connexion->prepare('SELECT *FROM utilisateurs u, orthopedistes o WHERE u.id_u=o.id_u AND o.id_u=:idOrtho') or die( print_r($connexion->errorInfo()));
										$resultatsOrtho->execute(array(
										'idOrtho'=>$ligneMedOrtho->id_uO
										));

										$resultatsOrtho->setFetchMode(PDO::FETCH_OBJ);
											
										while($ligneOrtho=$resultatsOrtho->fetch())
										{
											if($ligneMedOrtho->id_uO==$ligneOrtho->id_u)
											{
												echo $ligneOrtho->full_name;
											}else{
												echo '';
											}
										}
										$resultatsOrtho->closeCursor();
										?>						
										</td>			
																
										<td style="padding:10px; text-align: center;">
										<?php
										$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
										$resultatsMed->execute(array(
										'idMed'=>$ligneMedOrtho->id_uM
										));

										$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
											
										while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
										{
											if($ligneMedOrtho->id_uM==$ligneMed->id_u)
											{
												echo $ligneMed->full_name;
											}else{
												echo '';
											}
										}
										$resultatsMed->closeCursor();
										?>						
										</td>			
										
									</tr>
									<?php
									if($ligneMedOrtho->resultatsOrtho !="")
									{
									?>
									<tr style="text-align:center;background:#eee">
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
											<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-height: 300px; max-width: 500px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 80px; min-height: 80px;"readonly='readonly'><?php echo strip_tags($ligneMedOrtho->resultatsOrtho)?></textarea>
										</td>
										
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
											<?php 
											if($ligneMedOrtho->dateortho!="0000-00-00")
											{
												echo date('d-M-Y', strtotime($ligneMedOrtho->dateortho));
											}else{
												echo '';
											}								
											?>
										</td>
											
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
										<?php 

										$resultatsOrtho=$connexion->prepare('SELECT *FROM utilisateurs u, medecins x WHERE u.id_u=x.id_u AND x.id_u=:idX') or die( print_r($connexion->errorInfo()));
										$resultatsOrtho->execute(array(
										'idX'=>$ligneMedOrtho->id_uX
										));

										$resultatsOrtho->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
											
										if($ligneOrtho=$resultatsOrtho->fetch())//on recupere la liste des éléments
										{
											echo $ligneOrtho->full_name;
										}else{
											echo '';
										}
										?>
										</td>
																
										<td style="padding:10px; text-align: center;">						
										</td>			
										
									</tr>
									<?php
									}
									?>
									
								<?php
									}
									$resultMedOrtho->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
							</tbody>
						</table>
					</div>	
				</td>
				<?php	
				}/* else{
				?>
					<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(107) ?></th>
						</tr>
					</thead> 		
					</table>
				<?php
				} */	
				
				?>
				
				<td style="vertical-align:top;">

					<table class="cons-table" style="margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>
								
					<?php 
					if($transfer != NULL)
					{
					?>
						<tr>
							<td style="text-align:center; vertical-align:top; margin-top:100px;">
								<span style="font-weight:bold;font-size:15px;"><?php echo 'Transfer'; ?>
									<input type="checkbox" checked="checked" disabled="disabled"/>
								</span>
							</td>
						</tr>
						
						<tr>
							<td style="text-align:center;">
							<?php
							if($motiftransfer !="")
							{
							?>	
								Motif <textarea style="background: #eee; margin:auto; height:50px; width:100px; max-width:180px; max-height:250px; min-height:50px; min-width:100px; text-align:center;" readonly="readonly"><?php echo strip_tags($motiftransfer);?></textarea>
								
							<?php
							}/* else{
							?>
								<span style="font-weight:bold;font-size:15px;"><?php echo '---'; ?>
								</span>
							<?php
							} */
							?>	
							</td>
							
						</tr>
					<?php
					}
					?>					
					</table>
				</td>	
				<!-- <tr><button class="btn btn-large" style="width: 50%;font-family: arial;">Share Patient To Other Doctor</button></tr>			 -->
			</tr>
		</table>
	</div>

<?php
}


if(isset($_GET['showfiche']) and !isset($_GET['showmore']))
{

	$resultConsultation=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.dateconsu=:annee ORDER BY c.id_consu DESC LIMIT 1');		
	$resultConsultation->execute(array(
	'num'=>$_GET['num'],
	'annee'=>$annee
	));

	$comptConsultation=$resultConsultation->rowCount();
	
	$resultConsultation->setFetchMode(PDO::FETCH_OBJ);

	
	if($comptConsultation!=0)
	{
		if($ligneConsultation=$resultConsultation->fetch())
		{
			$idconsult=$ligneConsultation->id_consu;
?>
		<?php 
		if($ligneConsultation->dateconsu == $annee)
		{
		?>
			<span style="position:relative; font-weight:100; font-size:250%;"><?php echo getString(259) ?></span>
		
		<?php 
		}else{
		?>
			<span style="position:relative; font-weight:100; font-size:250%;"><?php echo getString(260) ?></span>
		<?php 
		}
		?>
			
			
			<table class="tablesorter" cellspacing="0" style="border-bottom:7px solid green;"> 
				<thead> 
					<tr>
						<th style="width:15%"><?php echo getString(97) ?></th>
						<th style="width:15%"><?php echo getString(113) ?></th>
						<th style="width:15%"><?php echo getString(246); ?></th>
						<th style="width:15%"><?php echo getString(247); ?></th>
						<th style="width:15%"><?php echo getString(19) ?></th>
						<th style="width:20%">Actions</th>
					</tr> 
				</thead> 
			
			
				<tbody>	
				<?php
				try
				{
					
					$doneResultsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c, med_labo ml WHERE u.id_u=p.id_u AND c.numero=p.numero AND ml.numero=p.numero AND ml.numero=:num AND c.id_uM=:idMed AND c.id_consu=ml.id_consuLabo AND ml.dateresultats="0000-00-00" ORDER BY c.numero');

					$doneResultsPatient->execute(array(
					'num'=>$ligneConsultation->numero,
					'idMed'=>$_SESSION['id']
					));

					$doneResultsPatient->setFetchMode(PDO::FETCH_OBJ);
				
					$comptDoneResultsPatient=$doneResultsPatient->rowCount();
				?>
					<tr style="text-align:center;<?php if($ligneConsultation->id_uM == $_SESSION['id'] AND $ligneConsultation->id_consu == $idconsult){ echo '';}?><?php if($comptDoneResultsPatient==0){ echo 'background:rgba(0,100,255,0.5);';}?>">
					
						<td style="padding:0;"><?php echo date('d-M-Y', strtotime($ligneConsultation->dateconsu)).' -- '.$ligneConsultation->heureconsu;?></td>
						
						<td style="text-align:left;">
						<?php

						$resultatsTypeConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assu.' p WHERE c.id_typeconsult=p.id_prestation AND p.id_prestation=:idTypeconsu') or die( print_r($connexion->errorInfo()));
						$resultatsTypeConsu->execute(array(
						'idTypeconsu'=>$ligneConsultation->id_typeconsult
						));

						$resultatsTypeConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
						if($ligneTypeConsu=$resultatsTypeConsu->fetch())//on recupere la liste des éléments
						{
							if($ligneTypeConsu->namepresta!="")
							{
								$nomTypeConsult = $ligneTypeConsu->namepresta;
								echo $ligneTypeConsu->namepresta;
							}else{
								$nomTypeConsult = $ligneTypeConsu->nompresta;
								echo $ligneTypeConsu->nompresta;
							}
						}
						?>
						</td>
						
						<td style="text-align:left;">
						<?php 
						$Predia = array();
													
						if(isset ($_GET['idconsu']) AND $ligneConsultation->prediagnostic !="")
						{
							$resultatsDiagnoPre=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
							
							$resultatsDiagnoPre->execute(array(
							'iddiagno'=>$ligneConsultation->prediagnostic
							))or die( print_r($connexion->errorInfo()));
								
							$resultatsDiagnoPre->setFetchMode(PDO::FETCH_OBJ);
							
							if($ligneDiagnoPre=$resultatsDiagnoPre->fetch())
							{
								$Predia[] = $ligneDiagnoPre->nomdiagno;
							}else{
								$Predia[] = $ligneConsultation->prediagnostic;
							}
							
						}
							
						$resultatsPreDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_predia IS NOT NULL OR d.autrepredia!="") ORDER BY d.id_dia');
						
						$resultatsPreDiagno->execute(array(
						'id_consudia'=>$ligneConsultation->id_consu
						
						))or die( print_r($connexion->errorInfo()));
							
						$resultatsPreDiagno->setFetchMode(PDO::FETCH_OBJ);
						$prediaCount = $resultatsPreDiagno->rowCount();
						
						if($prediaCount!=0)
						{
							
							while($linePreDiagno=$resultatsPreDiagno->fetch())
							{
								$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
								$resultsDiagno->execute(array(
								'iddiagno'=>$linePreDiagno->id_predia
								));
								
								$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
								$comptDiagno=$resultsDiagno->rowCount();
								
								if($comptDiagno!=0)
								{
									$ligne=$resultsDiagno->fetch();
									
									$Predia[] = $ligne->nomdiagno;
								}else{
									$Predia[] = $linePreDiagno->autrepredia;
								}
								
							}
						
						}
							for($p=0;$p<sizeof($Predia);$p++)
							{
								echo '- '.$Predia[$p].'<br/>';
							}
						?>
						</td>
						
														
						<td>
						<?php 
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
						'id_consudia'=>$ligneConsultation->id_consu
						
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
							for($p=0;$p<sizeof($Postdia);$p++)
							{
								echo '- '.$Postdia[$p].'<br/>';
							}
						?>
						</td>
						
						<td>
						<?php
											
						$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m, servicemed sm, categopresta_ins cp WHERE u.id_u=m.id_u AND m.id_u=:idMed AND m.id_u=u.id_u AND sm.id_categopresta=cp.id_categopresta AND sm.codemedecin=m.codemedecin') or die( print_r($connexion->errorInfo()));
						$resultatsMed->execute(array(
						'idMed'=>$ligneConsultation->id_uM
						));

						$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
						while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
						{
						?>	
							<?php echo getString(130).' '; if($ligneConsultation->id_uM==$_SESSION['id']){ echo getString(132);}else{ echo $ligneMed->nom_u;?><br/><?php echo $ligneMed->prenom_u;}?><br/>
						
						<?php 
							if($ligneMed->namecategopresta!="")
							{
								echo $ligneMed->namecategopresta;
							}else{
								echo $ligneMed->nomcategopresta;
							}
					
						}
						$resultatsMed->closeCursor();
						?>						
						</td>
						
						<td style="padding:0;">
						
							<a href="consult.php?num=<?php echo $ligneConsultation->numero;?>&idconsu=<?php echo $ligneConsultation->id_consu;?>&idtypeconsult=<?php echo $ligneConsultation->id_typeconsult;?>&idassuconsu=<?php echo $ligneConsultation->id_assuConsu;?>&showmore=ok&dateconsu=<?php echo $ligneConsultation->dateconsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(135); ?></a>
						
						<?php 
						if($ligneConsultation->id_uM == $_SESSION['id'] AND $ligneConsultation->dateconsu == $annee)
						{
							if($ligneConsultation->id_factureConsult!=NULL OR ($nomTypeConsult =="Pas de consultation" OR $nomTypeConsult =="No Consultation"))
							{
						?>
							<a href="consult.php?num=<?php echo $ligneConsultation->numero;?>&consu=ok&idconsu=<?php echo $ligneConsultation->id_consu;?>&idtypeconsult=<?php echo $ligneConsultation->id_typeconsult;?>&idassuconsu=<?php echo $ligneConsultation->id_assuConsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large flashing"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>
						
						<?php
							}else{
							?>
								<!-- <span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 5px;"><?php echo getString(218) ?></span> -->
								<a href="consult.php?num=<?php echo $ligneConsultation->numero;?>&consu=ok&idconsu=<?php echo $ligneConsultation->id_consu;?>&idtypeconsult=<?php echo $ligneConsultation->id_typeconsult;?>&idassuconsu=<?php echo $ligneConsultation->id_assuConsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>
							<?php 
							}
						}else{
							// echo '------';
						}
						?>
						</td>
					</tr>

				<?php
				}

				catch(Excepton $e)
				{
					echo 'Erreur:'.$e->getMessage().'<br/>';
					echo'Numero:'.$e->getCode();
				}
				?>
				</tbody>
			</table>
	<?php
		}
	}else{
		$idconsult=0;
		
		echo getString(261);
	}
	?>	
	<div style="margin-top:35px" id="fichepatient">	
		
<?php

	// echo 'SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero='.$_GET['num'].' AND c.id_consu!='.$idconsult.' AND c.done=1 ORDER BY c.id_consu DESC';
	
	$resultats=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:num AND c.done=1 ORDER BY c.id_consu DESC');
	$resultats->execute(array(
	'num'=>$_GET['num']
	));
	
	$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$comptFiche=$resultats->rowCount();

	
	if($comptFiche!=0) 
	{
?>

		<span style="position:relative; font-weight:100; font-size:250%;margin:20px;"><?php echo getString(262) ?></span>
		
		<div style="overflow:auto;height:400px; padding:5px;">
		
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th style="width:15%"><?php echo getString(97) ?></th>
						<th style="width:15%"><?php echo getString(113) ?></th>
						<th style="width:15%"><?php echo getString(246); ?></th>
						<th style="width:15%"><?php echo getString(247); ?></th>
						<th style="width:15%"><?php echo getString(19) ?></th>
						<th colspan=2>Actions</th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligne=$resultats->fetch())//on recupere la liste des éléments
						{
							if($ligne->id_consu != $idconsult)
							{
							?>
							<tr style="text-align:center;">
								<td><?php echo date('d-M-Y',strtotime($ligne->dateconsu)).'<br/>'.$ligne->heureconsu;?></td>
								<?php

								$resultatsTypeConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assu.' p WHERE c.id_typeconsult=p.id_prestation AND p.id_prestation=:idTypeconsu') or die( print_r($connexion->errorInfo()));
								$resultatsTypeConsu->execute(array(
								'idTypeconsu'=>$ligne->id_typeconsult
								));

								$resultatsTypeConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								if($ligneTypeConsu=$resultatsTypeConsu->fetch())//on recupere la liste des éléments
								{
								?>
								<td>
								<?php 
									if($ligneTypeConsu->namepresta!="")
									{
										$nomTypeConsult = $ligneTypeConsu->namepresta;
										echo $ligneTypeConsu->namepresta;
									}else{
										$nomTypeConsult = $ligneTypeConsu->nompresta;
										echo $ligneTypeConsu->nompresta;
									}
								?>
								</td>
								<?php 
								}
								?>

								<td>
								<?php 
								if(isset ($_GET['idconsu']) and $ligne->prediagnostic !=0)
								{
									$resultatsDiagnoPre=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
									
									$resultatsDiagnoPre->execute(array(
									'iddiagno'=>$ligne->prediagnostic
									))or die( print_r($connexion->errorInfo()));
										
									$resultatsDiagnoPre->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligneDiagnoPre=$resultatsDiagnoPre->fetch())
									{
										echo $ligneDiagnoPre->nomdiagno;
									}else{
										echo $ligne->prediagnostic;
									}
									
								}
								?>
								</td>
								
																
								<td>
								<?php 
								if(isset ($_GET['idconsu']) and $ligne->postdiagnostic !=0)
								{
									$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
									
									$resultatsDiagnoPost->execute(array(
									'iddiagno'=>$ligne->postdiagnostic
									))or die( print_r($connexion->errorInfo()));
										
									$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
									{
										echo $ligneDiagnoPost->nomdiagno;
									}else{
										echo $ligne->postdiagnostic;
									}
									
								}
								?>
								</td>
																
								<td>
								<?php
													
								$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m, servicemed sm, categopresta_ins cp WHERE u.id_u=m.id_u AND m.id_u=:idMed AND m.id_u=u.id_u AND sm.id_categopresta=cp.id_categopresta AND sm.codemedecin=m.codemedecin') or die( print_r($connexion->errorInfo()));
								$resultatsMed->execute(array(
								'idMed'=>$ligne->id_uM
								));

								$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
								{
								?>	
									<?php echo getString(130).' '; if($ligne->id_uM==$_SESSION['id']){ echo getString(132);}else{ echo $ligneMed->nom_u;?><br/><?php echo $ligneMed->prenom_u;}?><br/>
								
								<?php 
									if($ligneMed->namecategopresta!="")
									{
										echo $ligneMed->namecategopresta;
									}else{
										echo $ligneMed->nomcategopresta;
									}
							
								}
								$resultatsMed->closeCursor();
								?>						
								</td>
								
								<td>
									<a href="consult.php?num=<?php echo $ligne->numero;?>&idconsu=<?php echo $ligne->id_consu;?>&idtypeconsult=<?php echo $ligne->id_typeconsult;?>&idassuconsu=<?php echo $ligne->id_assuConsu;?>&showmore=ok&dateconsu=<?php echo $ligne->dateconsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(135); ?></a>
								<?php
								$start_week=strtotime("last week");
								$start_week=date("Y-m-d",$start_week);

								if($ligne->id_uM == $_SESSION['id'])
								{									
									if(($ligne->id_factureConsult !=NULL OR ($nomTypeConsult =="Pas de consultation" OR $nomTypeConsult =="No Consultation")) AND $ligne->dateconsu==$annee)
									{
								?>
										<a href="consult.php?num=<?php echo $ligne->numero;?>&idtypeconsult=<?php echo $ligne->id_typeconsult;?>&idconsu=<?php echo $ligne->id_consu;?>&idassuconsu=<?php echo $ligne->id_assuConsu;?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>
									
								<?php
									}else{
										// echo "<b style='margin-left:10px;font-size:11px;'>-- Consultation Has Been Expired --</b>";
									}
								}
								?>
								</td>
																
							</tr>
					<?php
							}
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
		</div>	
	<?php	
	}/* else{
	?>
		<table class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(161) ?>.<span style="color:#19BC9C;position:relative;"> <?php if($sexe=='M'){ echo 'Il n\'a jamais été consulté';}else{ echo 'Elle n\'a jamais été consulté';} ?>.</span></th> 
			</tr>
		</thead> 		
		</table>
	<?php
	} */
	?>
	</div>

<?php
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

function NewTypeConsult(consult)
{
	var typeconsu=document.getElementById('typeconsult').value;

	if( typeconsu =='autretypeconsult')
	{
		document.getElementById('areaAutretypeconsult').style.display='inline';
	}else{
		document.getElementById('areaAutretypeconsult').style.display='none';
		document.getElementById('areaAutretypeconsult').value='';
	}
	
}


function NewConsult(consult)
{
	var consu=document.getElementById('consult').value;

	if( consu =='autreconsult')
	{
		document.getElementById('areaAutreconsult').style.display='inline';
		document.getElementById('addAutreConsult').style.visibility='visible';
		document.getElementById('addConsult').style.visibility='hidden';
	}else{
		document.getElementById('areaAutreconsult').style.display='none';
		document.getElementById('addAutreConsult').style.visibility='hidden';
		document.getElementById('addConsult').style.visibility='visible';
	}
	
}


function ShowOthersDiagno(diagno)
{

	if( diagno =='diagnodiff')
	{
		document.getElementById('diagnodiffrow').style.display='inline';
		document.getElementById('diagnofinalrow').style.display='none';
	}
	
	if( diagno =='diagnofinal')
	{
		document.getElementById('diagnofinalrow').style.display='inline';
		document.getElementById('diagnodiffrow').style.display='none';
	}
	
}


function ShowOthersDiagnodiff(diagnodiff)
{
	var diagno=document.getElementById('diagnodiff').value;

	if( diagno ==0)
	{
		document.getElementById('areaautrediagnodiff').style.display='none';
	}
	
	if( diagno =='autrediagnodiff')
	{
		document.getElementById('areaautrediagnodiff').style.display='inline';
	}else{
		document.getElementById('areaautrediagnodiff').style.display='none';
	}
	
	
}


function ShowOthersDiagnofinal(diagnofinal)
{
	var diagno=document.getElementById('diagnofinal').value;

	if( diagno ==0)
	{
		document.getElementById('areaautrediagnofinal').style.display='none';
	}
	
	if( diagno =='autrediagnofinal')
	{
		document.getElementById('areaautrediagnofinal').style.display='inline';
	}else{
		document.getElementById('areaautrediagnofinal').style.display='none';
	}
	
	
}

function ShowExam(autreexamen)
{
	var exam=document.getElementById('listexamen').value;
	
	if( exam =='autreexamen')
	{
		document.getElementById('areaAutreexamen').style.display='inline';
		document.getElementById('addAutreExam').style.visibility='visible';
		document.getElementById('addExam').style.visibility='hidden';
	}else{
		document.getElementById('areaAutreexamen').style.display='none';
		document.getElementById('addAutreExam').style.visibility='hidden';
		document.getElementById('addExam').style.visibility='visible';
	}
	
}

function ShowRadio(autreradio)
{
	var radio=document.getElementById('listradio').value;
	
	if( radio =='autreradio')
	{
		document.getElementById('areaAutreradio').style.display='inline';
		document.getElementById('addAutreRadio').style.visibility='visible';
		document.getElementById('addRadio').style.visibility='hidden';
	}else{
		document.getElementById('areaAutreradio').style.display='none';
		document.getElementById('addAutreRadio').style.visibility='hidden';
		document.getElementById('addRadio').style.visibility='visible';
	}
	
}

function ShowOthersSoins(autresoins)
{
	var soins=document.getElementById('soins').value;
	
	if( soins =='autresoins')
	{
		document.getElementById('areaAutresoins').style.display='inline';
		document.getElementById('addAutreNursery').style.visibility='visible';
		document.getElementById('addNursery').style.visibility='hidden';
	}else{
		document.getElementById('areaAutresoins').style.display='none';
		document.getElementById('addAutreNursery').style.visibility='hidden';
		document.getElementById('addNursery').style.visibility='visible';
	}
	
}


function ShowList(list)
{
	
	if( list =='Msg')
	{
		document.getElementById('divMenuMsg').style.display='inline';
	}
	
}


function ShowHideEcho(echo)
{
	
	if( echo =='viewecho')
	{
		$('#listecho').slideDown(300);
		
		document.getElementById('viewecho').style.display='none';
		document.getElementById('hideecho').style.display='inline-block';
		
	}
	
	if( echo =='hideecho')
	{
		$('#listecho').slideUp(300);
		
		document.getElementById('viewecho').style.display='inline-block';
		document.getElementById('hideecho').style.display='none';
		
	}
	
}

function ResultsEcho(i)
{	
	$('#resultsRad'+i).slideDown(300);
	
	document.getElementById('noresultsEcho'+i).style.display="inline";
	document.getElementById('resultsEcho'+i).style.display="none";
}

function NoresultsEcho(i)
{	
	$('#resultsRad'+i).slideUp(300);
	
	document.getElementById('resultsEcho'+i).style.display="inline";
	document.getElementById('noresultsEcho'+i).style.display="none";
}


function ResultatsEcho(j)
{	
	$('#resultatsRad'+j).slideDown(300);
	
	document.getElementById('noresultatsEcho'+j).style.display="inline";
	document.getElementById('resultatsEcho'+j).style.display="none";
}

function NoresultatsEcho(j)
{	
	$('#resultatsRad'+j).slideUp(300);
	
	document.getElementById('resultatsEcho'+j).style.display="inline";
	document.getElementById('noresultatsEcho'+j).style.display="none";
}
	
/* 
function ShowSaveEcho(saveecho)
{	
	document.getElementById('saveechobtn').style.display="inline-block";
}

*/

	function PlusQteMedoc(i)
	{	
		var plus=parseInt($('#quantityMedoc'+i).val()) + 1;		
		$('#quantityMedoc'+i).val(plus);
		
		if($('#quantityMedoc'+i).val()<2)
		{
			document.getElementById('qteMedocMoins'+i).style.display="none";
		}else{
			document.getElementById('qteMedocMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteMedocBtn'+i).style.display="inline";
	}

	function PlusQteConsom(i)
	{	
		var plus=parseInt($('#quantityConsom'+i).val()) + 1;		
		$('#quantityConsom'+i).val(plus);
		
		if($('#quantityConsom'+i).val()<2)
		{
			document.getElementById('qteConsomMoins'+i).style.display="none";
		}else{
			document.getElementById('qteConsomMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteConsomBtn'+i).style.display="inline";
	}

	function MoinsQteConsom(i)
	{
		var moins=parseInt($('#quantityConsom'+i).val()) - 1;		
		$('#quantityConsom'+i).val(moins);
		
		if($('#quantityConsom'+i).val()<2)
		{
			document.getElementById('qteConsomMoins'+i).style.display="none";
		}else{
			document.getElementById('qteConsomMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteConsomBtn'+i).style.display="inline";
	}
	
	function MoinsQteMedoc(i)
	{
		var moins=parseInt($('#quantityMedoc'+i).val()) - 1;		
		$('#quantityMedoc'+i).val(moins);
		
		if($('#quantityMedoc'+i).val()<2)
		{
			document.getElementById('qteMedocMoins'+i).style.display="none";
		}else{
			document.getElementById('qteMedocMoins'+i).style.display="inline";
		}
		
		document.getElementById('addQteMedocBtn'+i).style.display="inline";
	}
	


function ShowMotifhospi(motif)
{	
	if( motif =='hospi' && document.getElementById('hospitalized').checked)
	{
		$('#motifhospi').slideDown(300);
		
		document.getElementById('motifhospi').style.display='inline';
		// alert(document.getElementById('hospitalized').checked);
	
	}else{
		document.getElementById('motifhospi').style.display='none';
		document.getElementById('motifhospitalized').value='';
	}
}

function ShowMotifphysio(motif)
{	
	if( motif =='physio' && document.getElementById('physio').checked)
	{
		$('#motifkine').slideDown(300);
		
		document.getElementById('motifkine').style.display='inline';
		// alert(document.getElementById('physio').checked);
	
	}else{
		document.getElementById('motifkine').style.display='none';
		document.getElementById('motifphysio').value='';
	}
}

function ShowMotiftransfer(motif)
{	
	if( motif =='transfer' && document.getElementById('transfer').checked)
	{
		$('#motiftransfer').slideDown(300);
		
		document.getElementById('motiftrans').style.display='inline';
		// alert(document.getElementById('transfer').checked);
	
	}else{
		document.getElementById('motiftrans').style.display='none';
		document.getElementById('motiftransfer').value='';
	}
	
}




function Motdepass(){
	var erreur="";
	// var format=/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
	if(document.getElementById('updatePass').style.display='inline')
	{
		document.getElementById('confirmPass').style.display='inline';
		document.getElementById('Pass').style.display='inline';
		document.getElementById('updatePass').style.display='none';
	
	}

}

function controlFormPassword(theForm){
	var rapport="";
	
	rapport +=controlPass(theForm.Pass);

		if (rapport != "") {
		alert("Please correct the following errors:\n" + rapport);
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
<?php
	}else{
		echo '<script language="javascript"> alert("Vous avez été désactivé!!\n Demander à l\'administrateur de vous activer");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}
	
}else{
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
			
		echo '<script type="text/javascript"> alert("Your password have been changed\nYour new password is : '.$pass.'");</script>';
		
	}

?>

	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	
		$('#motif').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#prediagno').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#postdiagno').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#consult').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#soins').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#psycho').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#surge').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#listexamen').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#listradio').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#consom').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#medoc').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#kine').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#ortho').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
	</script>
	

<div> <!-- footer -->
	<?php
		include('footer.php');
	?>
</div> <!-- /footer -->

</body>

</html>
