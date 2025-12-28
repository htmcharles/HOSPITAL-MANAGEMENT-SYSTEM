<?php
session_start();

include("connect.php");
include("connectLangues.php");

$annee = date('Y').'-'.date('m').'-'.date('d');
$recomm="";

if(isset($_GET['deleteIDconsu']))
{
	$idHosp=$_GET['deleteIDconsu'];
	
	
	/*-----------Delete From Nursery------------*/
	
	$getIdInf=$connexion->prepare('SELECT *FROM med_inf WHERE id_consuInf=:id_medI');
	$getIdInf->execute(array(
	'id_medI'=>$idHosp
	));
	
	$getIdInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
		
	$compteurInf=$getIdInf->rowCount();
		
	if($compteurInf!=0)
	{

		$deleteInf=$connexion->prepare('DELETE FROM med_inf WHERE id_consuInf=:id_medI');
			
		$deleteInf->execute(array(
		'id_medI'=>$idHosp
		
		))or die($deleteInf->errorInfo());
	}
	
	
	/*-----------Delete From Labs------------*/
	
	$getIdLabo=$connexion->prepare('SELECT *FROM med_labo WHERE id_consuLabo=:id_medL');
	$getIdLabo->execute(array(
	'id_medL'=>$idHosp
	));
	
	$getIdLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurLabo=$getIdLabo->rowCount();

	if($compteurLabo!=0)
	{
		$deleteLabo=$connexion->prepare('DELETE FROM med_labo WHERE id_consuLabo=:id_medL');
			
		$deleteLabo->execute(array(
		'id_medL'=>$idHosp
		
		))or die($deleteLabo->errorInfo());
	}
	
		
	/*-----------Delete From Radio------------*/
	
	$getIdRadio=$connexion->prepare('SELECT *FROM med_radio WHERE id_consuRadio=:id_medR');
	$getIdRadio->execute(array(
	'id_medR'=>$idHosp
	));
	
	$getIdRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurRadio=$getIdRadio->rowCount();

	if($compteurRadio!=0)
	{
		$deleteRadio=$connexion->prepare('DELETE FROM med_radio WHERE id_consuRadio=:id_medR');
			
		$deleteRadio->execute(array(
		'id_medR'=>$idHosp
		
		))or die($deleteRadio->errorInfo());
	}
	
	
	/*-----------Delete From Med_Consu------------*/
	
	$getIdMedConsu=$connexion->prepare('SELECT *FROM med_consult WHERE id_consuMed=:id_medC');
	$getIdMedConsu->execute(array(
	'id_medC'=>$idHosp
	));
	
	$getIdMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurMedConsu=$getIdMedConsu->rowCount();
		
	if($compteurMedConsu!=0)
	{
		$deleteMedConsu=$connexion->prepare('DELETE FROM med_consult WHERE id_consuMed=:id_medC');
		
		$deleteMedConsu->execute(array(
		'id_medC'=>$idHosp
		
		))or die($deleteMedConsu->errorInfo());
	
	}

	
	/*-----------Delete From prepostdia------------*/
	
	$getIdPrePoDia=$connexion->prepare('SELECT *FROM prepostdia WHERE id_consudia=:id_medD');
	$getIdPrePoDia->execute(array(
	'id_medD'=>$idHosp
	));
	
	$getIdPrePoDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurPrePoDia=$getIdPrePoDia->rowCount();
		
	if($compteurPrePoDia!=0)
	{
		$deleteMedDia=$connexion->prepare('DELETE FROM prepostdia WHERE id_consudia=:id_medD');
		
		$deleteMedDia->execute(array(
		'id_medD'=>$idHosp
		
		))or die($deleteMedDia->errorInfo());
	
	}

	 
	$deleteConsu=$connexion->prepare('DELETE FROM consultations WHERE id_consu=:id_medConsu');
		
	$deleteConsu->execute(array(
	'id_medConsu'=>$idHosp
	
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
		$etatPa=strip_tags($ligneConsult->etatpatient);
		$antec=strip_tags($ligneConsult->antecedent);
		$sisympt=strip_tags($ligneConsult->signsymptomes);
		$recomm=strip_tags($ligneConsult->recommandations);
		$poids=$ligneConsult->poids;
		$taille=$ligneConsult->taille;
		$tempera=$ligneConsult->temperature;
		$tensionart=$ligneConsult->tensionart;
		$pouls=$ligneConsult->pouls;
		$prediagno=$ligneConsult->prediagnostic;
		$postdiagno=$ligneConsult->postdiagnostic;
		$codemedecin=$ligneConsult->id_uM;
		$num=$ligneConsult->numero;
		$idtypeconsult=$ligneConsult->id_typeconsult;
		$prixtypeconsult=$ligneConsult->prixtypeconsult;
		$hospitalized=$ligneConsult->hospitalized;
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

	$resultsPatientHistory = $connexion->prepare("SELECT * FROM clinical_history WHERE numero=:numero");
	$resultsPatientHistory->execute(array('numero'=>$_GET['num']));
	$resultsPatientHistory->setFetchMode(PDO::FETCH_OBJ);
	$CountPH = $resultsPatientHistory->rowCount();

	if(isset($_SESSION['codeI'])){
		$col = "id_uInf";
	}else{
		$col = "id_uM";
	}

	if (isset($_POST['saveHistory'])) {

		$InsertHistory = $connexion->prepare("INSERT INTO clinical_history(history,numero,idHosp,".$col.",hist_date) VALUES(:history,:numero,:idHosp,:id_uM,:hist_date)");
		$InsertHistory->execute(array('history'=>$_POST['ClinicalHistory'],'numero'=>$_GET['num'],'idHosp'=>$_GET['idhosp'],'id_uM'=>$_SESSION['id'],'hist_date'=>$annee));

		echo "<script>alert('Patient Clinical History Added Well!');</script>";
		echo '<script type="text/javascript">document.location.href="formPatient_hosp.php?num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&showmore=ok";</script>';
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
			
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
		
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	
	<link href="AdministrationSOMADO/css/form-consultation.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->

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

</head>

<body>
<?php
	$resultHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:hospId AND ph.numero=:num ORDER BY ph.id_hosp');		
	$resultHosp->execute(array(
	'hospId'=>$_GET['idhosp'],
	'num'=>$_GET['num']
	));

	$resultHosp->setFetchMode(PDO::FETCH_OBJ);

	$comptHosp=$resultHosp->rowCount();
	



$id=$_SESSION['id'];

$sqlC=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");
$sqlO=$connexion->query("SELECT *FROM orthopedistes o WHERE o.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");
$sqlmn=$connexion->query("SELECT *FROM coordinateurs mn WHERE mn.id_u='$id'");

$comptidC=$sqlC->rowCount();
$comptidO=$sqlO->rowCount();
$comptidL=$sqlL->rowCount();
$comptidmn=$sqlmn->rowCount();

$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND ($comptidC==0 OR $comptidO==0 OR $comptidmn==0))
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
				<li>			
					<form method="post" action="formPatient_hosp.php?<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'].'';}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];} if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];} if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['showmore'])){ echo '&showmore='.$_GET['showmore'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="formPatient_hosp.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'].'';}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];} if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];} if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['showmore'])){ echo '&showmore='.$_GET['showmore'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="formPatient_hosp.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'].'';}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];} if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];} if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['showmore'])){ echo '&showmore='.$_GET['showmore'];}?>" class="btn"><?php echo getString(29);?></a>
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
$sqlO=$connexion->query("SELECT *FROM orthopedistes o WHERE o.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");


$comptidP=$sqlP->rowCount();
$comptidM=$sqlM->rowCount();
$comptidI=$sqlI->rowCount();
$comptidO=$sqlO->rowCount();
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
		$bill=$ligne->bill;
		$idassu=$ligne->id_assurance;
		$province=$ligne->province;
		$district=$ligne->district;
		$secteur=$ligne->secteur;
		$profession=$ligne->profession;
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
		$etatPa=strip_tags($ligneConsu->etatpatient);
		$antec=strip_tags($ligneConsu->antecedent);
		$sisympt=strip_tags($ligneConsu->signsymptomes);
		$recomm=strip_tags($ligneConsu->recommandations);
		$poids=$ligneConsu->poids;
		$taille=$ligneConsu->taille;
		$tempera=$ligneConsu->temperature;
		$tensionart=$ligneConsu->tensionart;
		$pouls=$ligneConsu->pouls;
		$prediagno=strip_tags($ligneConsu->diagnostic);		
		$id_uM=$ligneConsu->id_uM;
		$numeroPa=$ligneConsu->numero;
		$idtypeconsu=$ligneConsu->id_typeconsult;
		$modifierIdConsu=$_SESSION['id'];
	}
	$resultConsu->closeCursor();
}

?>


<?php
if(isset($_SESSION['codeO']))
{
?>
	<div style="text-align:center;margin-top:20px;margin-bottom:15px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px; margin:5px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
}
?>

<?php
if(isset($_SESSION['codeM']))
{
?>
	<div style="text-align:center;margin-top:20px;margin-bottom:15px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px; margin:5px;">
			<?php echo 'Clinique';?>
		</a>

		<a href="report.php?med=<?php echo $_SESSION['id'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="font-size:20px;height:40px; padding:10px 40px;"><?php echo getString(94);?></a>
		
		<a href="rendezvous1.php?med=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="font-size:20px;height:40px; padding:10px 40px;"><?php echo 'Vos rendez-vous';?></a>
		
	</div>
<?php
}
?>


<div  style="text-align:center; width:90%" class="account-container">

<div id='cssmenu'>

	<ul style="margin-top:20px;background:none;border:none;">

		<li style="width:50%;"><a href="patients1_hosp.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients hospitalisés"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients hospitalisés</a></li>
	
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


	<ul style="margin-top:20px; margin-bottom:20px; background:none; border:none;">

		

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
	<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto; padding: 10px; width:80%;">
		<tr>
			<td style="font-size:18px; text-align:center; width:20%;">
				
				<p class="patientId" style="text-align:left;"><span>S/N:</span> <?php echo $num; ?>
				<p class="patientId" style="text-align:left;"><span> <?php echo getString(89);?>:</span> <?php echo $nom_uti.' '.$prenom_uti; ?>
				
			</td>
			
			<td style="text-align:center; width:20%;">
				<p class="patientId" style="text-align:left;"><span>Insurance type:</span>
			<?php
			if($idassu!=NULL)
			{
				$resultAssu=$connexion->prepare('SELECT * FROM assurances a WHERE id_assurance=:assu');
				$resultAssu->execute(array(
				'assu'=>$idassu
				)); 
				
				if($ligneAssu=$resultAssu->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
				{
					echo $ligneAssu->nomassurance.' ('.$bill.'%)';
				}
				
			}else{
				echo "Privé";
			}
			?>
			
				<p class="patientId" style="text-align:left;"><span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
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
			</td>
			
			<td style="font-size:18px; text-align:center; width:20%;">
			
				<p class="patientId" style="text-align:left;"><span style="font-weight:bold;">Age : </span><?php echo $an;?>
				
				<p class="patientId" style="text-align:left;"><span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo 'Date Entrée'; ?>: </span><?php echo date('d-M-Y',strtotime($_GET['datehosp']));?>
			</td>
		</tr>
	</table>
	<br>
	<button onclick="Clinical('show');" class="btn btn-large-inversed" id="show" style="margin-left: 10px;margin-top: -5px;"><?php echo "Patient Clinical History";?>
		</button>
		<button onclick="Clinical('hide');" class="btn btn-large-inversed" id="hide" style="margin-left: 10px;margin-top: -5px;display: none;"><i class="fa fa-cancel"></i> <?php echo "Hide Clinical History Panel";?>
		</button>
		<br/>
		<br>
		<div style="height: auto;width: 85%;border: 1px solid #A00000;box-shadow: 0 0 3px #B20000;border-radius: 9px;position: relative;left: 6rem;font-family:ubuntu;"> 
			<form method="POST" style="display: none;" id="cliniHis">
				<h4 style="border-bottom: 2px solid #A00000;padding: 10px 10px;background: black;color: white;border-top-left-radius: 9px;border-top-right-radius: 9px;">Patient History Recording</h4>
				<br>
				<textarea name="ClinicalHistory" style="height: 150px;width: 70%;"></textarea>
			<button class="btn btn-large" name="saveHistory">Save</button>
			</form>
		</div>
		<br>
	
	
	<table align="center">
	   
		<tr>
			<td></td>
			
			<td style="padding:10px;" align="center">
				
				<table cellpadding=3>
					<tr>
						<td style="padding:0 20px; text-align:center"><label for="roomhosp"><?php echo 'N° Salle'; ?></label></td>
							
						<td style="padding:0 20px; text-align:center"><label for="lithosp"><?php echo 'N° du lit'; ?></label></td>
							
					</tr>	   

					<tr>	
						<td style="text-align:center">
							
								<input type="text" name="roomhosp" id="roomhosp" value="<?php echo $_GET['numroom'];?>" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:150px;" readonly="readonly"/>
								
								<input type="hidden" name="idhosp" id="idhosp" value="<?php echo $_GET['idhosp'];?>"/>
							
							
						</td>
							
						<td style="text-align:center">
							<input type="text" name="lithosp" id="lithosp" value="<?php echo $_GET['numlit'];?>" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:50px;" readonly="readonly"/>
							
						</td>
					</tr>
				</table>
			</td>				
		</tr>	   

	</table>
	
<?php
}
?>

<br/>
<?php
if(isset($_GET['showmore']))
{
	$numero = $_GET['num'];
	$idHosp = $_GET['idhosp'];
	$datehosp = $_GET['datehosp'];
	

		$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'idConsu'=>$idHosp	
		));
		
		$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptConsult=$resultConsult->rowCount();
	
	
	
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.id_hospMed=:idHosp ORDER BY mc.id_medconsu');	
		$resultMedConsult->execute(array(
		'idHosp'=>$idHosp	
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedConsult=$resultMedConsult->rowCount();
	
	
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.id_hospInf=:idHosp ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'idHosp'=>$idHosp	
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedInf=$resultMedInf->rowCount();
	
	
	
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.id_hospSurge=:idHosp ORDER BY ms.id_medsurge');		
		$resultMedSurge->execute(array(
		'idHosp'=>$idHosp	
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedSurge=$resultMedSurge->rowCount();
	
	
	
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.id_hospLabo=:idHosp ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'idHosp'=>$idHosp	
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedLabo=$resultMedLabo->rowCount();
	
	
	
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.id_hospRadio=:idConsu ORDER BY mr.id_medradio');		
		$resultMedRadio->execute(array(
		'idConsu'=>$idHosp	
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedRadio=$resultMedRadio->rowCount();
	
	
	
		$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mr WHERE mr.id_hospKine=:idConsu ORDER BY mr.id_medKine');		
		$resultMedKine->execute(array(
		'idConsu'=>$idHosp	
		));
		
		$resultMedKine->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedKine=$resultMedKine->rowCount();
	
	
	
	
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.id_hospConsom=:idHosp ORDER BY mco.id_medconsom');	
		$resultMedConsom->execute(array(
		'idHosp'=>$idHosp	
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedConsom=$resultMedConsom->rowCount();
	
	
	
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.id_hospMedoc=:idHosp ORDER BY mdo.id_medmedoc');		
		$resultMedMedoc->execute(array(
		'idHosp'=>$idHosp	
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedMedoc=$resultMedMedoc->rowCount();
	
	
	
	
		$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mdo WHERE mdo.id_hospOrtho=:idHosp ORDER BY mdo.id_medortho');		
		$resultMedOrtho->execute(array(
		'idHosp'=>$idHosp	
		));
		
		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedOrtho=$resultMedOrtho->rowCount();
	
	
	
	
		$resultPreDia=$connexion->prepare('SELECT *FROM prepostdia p WHERE p.id_consudia=:idConsudia AND (p.autrepredia!="" OR p.id_predia IS NOT NULL) ORDER BY p.id_dia');		
		$resultPreDia->execute(array(
		'idConsudia'=>$idHosp	
		));
		
		$resultPreDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptPreDia=$resultPreDia->rowCount();
	
	
	
	
		$resultPostDia=$connexion->prepare('SELECT *FROM prepostdia p WHERE p.id_consudia=:idConsudia AND (p.autrepostdia!="" OR p.id_postdia IS NOT NULL) ORDER BY p.id_dia');		
		$resultPostDia->execute(array(
		'idConsudia'=>$idHosp	
		));
		
		$resultPostDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptPostDia=$resultPostDia->rowCount();
	
	
	
	
	
	$start_week=strtotime("last week");
	$start_week=date("Y-m-d",$start_week);
				
	
	if(isset($_SESSION['codeM']))
	{
?>
		<a style="padding:10px 40px;" href="<?php echo 'categoriesbill_hosp_medecin.php?num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&back=ok'?>" class="btn-large-inversed" name="showbackbtn" id="showbackbtn"><?php echo getString(266) ?></a>
		
		<br/>
		<br/>
	<?php
	}			
	
	if(isset($_SESSION['codeI']))
	{
		if(isset($_GET['id_uM'])!=0)
		{
			$id_uM=$_GET['id_uM'];
		}else{
			$id_uM=0;
		}
		
		if($comptidI!=0)
		{
			$infShow='&infShow=ok';
		}else{
			$infShow='';
		}
?>
		<a style="padding:10px 40px;" href="<?php echo 'categoriesbill_hosp.php?inf='.$_SESSION['id'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&back=ok'?>" class="btn-large-inversed" name="showbackbtn" id="showbackbtn"><?php echo getString(266) ?></a>
		
		<a style="padding:10px 40px;" href="<?php echo 'categoriesbill_hosp.php?inf='.$_SESSION['id'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$id_uM.'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&previewprint=ok&today=ok'.$infShow.''?>" class="btn-large" name="showpreviewbtn" id="showpreviewbtn"><?php echo 'Edit Profile'; ?></a>
		
		<br/>
		<br/>
	<?php
	}
	
	if(isset($_SESSION['codeO']))
	{
		if(isset($_GET['id_uM'])!=0)
		{
			$id_uM=$_GET['id_uM'];
		}else{
			$id_uM=0;
		}
		
		if($comptidO!=0)
		{
			$infShow='&infShow=ok';
		}else{
			$infShow='';
		}
?>
		<a style="padding:10px 40px;" href="<?php echo 'categoriesbill_hosp.php?inf='.$_SESSION['id'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&back=ok'?>" class="btn-large-inversed" name="showbackbtn" id="showbackbtn"><?php echo getString(266) ?></a>
		
		<a style="padding:10px 40px;" href="<?php echo 'categoriesbill_hosp.php?inf='.$_SESSION['id'].'&num='.$_GET['num'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$id_uM.'&datehosp='.$_GET['datehosp'].'&idassu='.$_GET['idassu'].'&idbill='.$_GET['idbill'].'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&previewprint=ok&today=ok'.$infShow.''?>" class="btn-large" name="showpreviewbtn" id="showpreviewbtn"><?php echo 'Edit Profile'; ?></a>
		
		<br/>
		<br/>
	<?php
	}
	?>	
	<div id="showmore">	

<?php
	/*
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
							$presta_assuConsu='prestations_'.$ligneNomAssu->nomassurance;
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
					
		
		<table class="cons-info" cellpadding=3 style="margin-bottom:15px;">

			<tr>
	<?php 		if($ligneConsult->motif != "")
				{
	?>				<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo getString(154) ?></td>
	<?php 		}
				if($ligneConsult->etatpatient != "")
				{
	?>				<td style="border-bottom:1px solid #bbb; font-weight:bold; text-align:center;"><?php echo getString(155) ?></td>
	<?php 		}
				if($ligneConsult->antecedent != "")
				{
	?>				<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo getString(156) ?></td>
	<?php 		}
				if($ligneConsult->signsymptomes != "")
				{
	?>				<td style="border-bottom:1px solid #bbb; font-weight:bold; text-align:center;"><?php echo getString(157) ?></td>
	<?php		}
	?>
			</tr>
			
			<tr>
	<?php 		if($ligneConsult->motif != "")
				{
	?>				<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-height: 300px; max-width: 500px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 80px; min-height: 80px;" readonly='readonly'><?php echo strip_tags($ligneConsult->motif)?></textarea>
					</td>
	<?php		}
				if($ligneConsult->etatpatient != "")
				{
	?>				<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-height: 300px; max-width: 500px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 80px; min-height: 80px;"readonly='readonly'><?php echo strip_tags($ligneConsult->etatpatient)?></textarea>
					</td>
	<?php		}
				if($ligneConsult->antecedent != "")
				{
	?>				<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-height: 300px; max-width: 500px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 80px; min-height: 80px;"readonly='readonly'><?php echo strip_tags($ligneConsult->antecedent)?></textarea>
					</td>
				<?php
				}
				if($ligneConsult->signsymptomes != "")
				{
				?>			
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-height: 300px; max-width: 500px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 80px; min-height: 80px;"readonly='readonly'><?php echo strip_tags($ligneConsult->signsymptomes)?></textarea>
					</td>
				<?php		
				}
				?>				
			</tr>
	
		</table>

	<?php
	}
	
	*/
	?>
	
	<!--	
	<?php
	$Postdia = array();
	$DiagnoPostDone=0;
										
	$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
	
	$resuPostdiagnostic->execute(array(
	'idConsu'=>$idHosp
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
			'idConsu'=>$idHosp
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
	-->
	
	<?php

	if($comptMedSurge!=0)
	{
?>
		<span style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;"><?php echo getString(274) ?></span>
		
		<div style="overflow:auto;height:auto;padding:5px; margin-bottom:30px;">
		
			<table style="width:80%;" class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr style="height:45px;">
						<th style="width:15%; color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(274) ?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(215) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedSurge=$resultMedSurge->fetch())//on recupere la liste des éléments
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
									$presta_assuSurge='prestations_'.$ligneNomAssuSurge->nomassurance;
								}
							}
							
					?>
						<tr style="text-align:center;">
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedSurge->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedSurge->datehosp));}else{ echo '';}?></td>
						
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_surge_hosp ms, '.$presta_assuSurge.' p WHERE ms.id_prestationSurge=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedSurge->id_prestationSurge
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
							
								echo $ligneMedSurge->autrePrestaS;
							?>
							</td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
								echo $ligneMedSurge->qteSurge;
							?>
							</td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
												
							$resultatsSurge=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idSurge') or die( print_r($connexion->errorInfo()));
							$resultatsSurge->execute(array(
							'idSurge'=>$ligneMedSurge->id_uI
							));

							$resultatsSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneSurge=$resultatsSurge->fetch())//on recupere la liste des éléments
							{
							
								if($ligneMedSurge->id_uI==$ligneSurge->id_u)
								{
									echo $ligneSurge->full_name;
								}else{
									echo '';
								}
							}
							$resultatsSurge->closeCursor();
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
	
	
	if($comptMedInf!=0)
	{
?>
		<span style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;"><?php echo getString(98) ?></span>
		
		<div style="overflow:auto;height:auto;padding:5px; margin-bottom:30px;">
		
			<table style="width:80%;" class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr style="height:45px;">
						<th style="width:15%; color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(98) ?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(215) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
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
									$presta_assuInf='prestations_'.$ligneNomAssuInf->nomassurance;
								}
							}
							
					?>
						<tr style="text-align:center;">
						
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedInf->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedInf->datehosp));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_inf_hosp mi, '.$presta_assuInf.' p WHERE mi.id_prestation=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
								echo $ligneMedInf->qteInf;
							?>
							</td>
							
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
						<th style="width:10%; color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(99) ?></th>					
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(215) ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(3) ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Valeur';?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Min';?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Max'; ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(22) ?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important">Date</th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important" colspan=4><?php echo getString(19) ?></th>
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
									$presta_assuLab='prestations_'.$ligneNomAssuLab->nomassurance;
								}
							}

							if($ligneMedLabo->moreresultats!=0)
							{
						?>
							<tr>
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedLabo->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedLabo->datehosp));}else{ echo '';}?></td>
						
								<td style="background:#eee; padding:10px; text-align: center; border-right: 1px solid #ccc; font-weight:bold;" colspan=5>
								
								<?php									
								$resultatsPresta=$connexion->prepare('SELECT *FROM med_labo_hosp ml, '.$presta_assuLab.' p WHERE ml.id_prestationExa=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
									echo $ligneMedLabo->qteLab;
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
							
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php
		
								$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
								$resultatsInf->execute(array(
								'idInf'=>$ligneMedLabo->id_uI
								));

								$resultatsInf->setFetchMode(PDO::FETCH_OBJ);
									
								while($ligneInf=$resultatsInf->fetch())
								{
									if($ligneMedLabo->id_uI==$ligneInf->id_u)
									{
										echo $ligneInf->full_name;
									}else{
										echo '';
									}
								}
								$resultatsInf->closeCursor();
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
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
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
												$mesure=$lignePresta->mesure;
											}else{
												$presta=$ligneMoreMedLabo->autreExamen;
												$mesure='';
												echo $ligneMoreMedLabo->autreExamen;
											}
										?>
										</td>
										
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px">
										<?php echo $ligneMoreMedLabo->autreresultats;?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
										</td>
										
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px">
										<?php echo $ligneMoreMedLabo->valeurLab;?>
										</td>
									
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px">
										<?php
																										$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');		
								$resultExa->execute(array(
								'prestaId'=>$ligneMoreMedLabo->id_prestationExa
								));
								
								$resultExa->setFetchMode(PDO::FETCH_OBJ);
								$comptExa=$resultExa->rowCount();

								if($ligneMoreMedLabo->minresultats =="")
								{
									if($comptExa ==1)
									{
										$ligneMin=$resultExa->fetch();
										echo $ligneMin->min_valeur;
									}
								}else{
									echo $ligneMoreMedLabo->minresultats;
								}
										?>
										</td>
										
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px">
										<?php 
								$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');		
								$resultExa->execute(array(
								'prestaId'=>$ligneMoreMedLabo->id_prestationExa
								));
								
								$resultExa->setFetchMode(PDO::FETCH_OBJ);
								$comptExa=$resultExa->rowCount();

								if($ligneMoreMedLabo->maxresultats =="")
								{
									if($comptExa ==1)
									{
										$ligneMin=$resultExa->fetch();
										echo $ligneMin->max_valeur;
									}
								}else{
									echo $ligneMoreMedLabo->maxresultats;
								}
										?>
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
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedLabo->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedLabo->datehosp));}else{ echo '';}?></td>						
							
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;font-weight:bold;">
								<?php									
								$resultatsPresta=$connexion->prepare('SELECT *FROM med_labo_hosp ml, '.$presta_assuLab.' p WHERE ml.id_prestationExa=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
									echo $ligneMedLabo->qteLab;
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
								
								<td style="text-align:center;border-right: 1px solid #ccc;">
								<?php
																										$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');		
								$resultExa->execute(array(
								'prestaId'=>$ligneMedLabo->id_prestationExa
								));
								
								$resultExa->setFetchMode(PDO::FETCH_OBJ);
								$comptExa=$resultExa->rowCount();

								if($ligneMedLabo->minresultats =="")
								{
									if($comptExa ==1)
									{
										$ligneMin=$resultExa->fetch();
										echo $ligneMin->min_valeur;
									}
								}else{
									echo $ligneMedLabo->minresultats;
								}

								?>
								</td>
								
								<td style="text-align:center;border-right: 1px solid #ccc;">
								<?php
																										$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');		
								$resultExa->execute(array(
								'prestaId'=>$ligneMedLabo->id_prestationExa
								));
								
								$resultExa->setFetchMode(PDO::FETCH_OBJ);
								$comptExa=$resultExa->rowCount();

								if($ligneMedLabo->maxresultats =="")
								{
									if($comptExa ==1)
									{
										$ligneMin=$resultExa->fetch();
										echo $ligneMin->max_valeur;
									}
								}else{
									echo $ligneMedLabo->maxresultats;
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
							
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php
		
								$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
								$resultatsInf->execute(array(
								'idInf'=>$ligneMedLabo->id_uI
								));

								$resultatsInf->setFetchMode(PDO::FETCH_OBJ);
									
								while($ligneInf=$resultatsInf->fetch())
								{
									if($ligneMedLabo->id_uI==$ligneInf->id_u)
									{
										echo $ligneInf->full_name;
									}else{
										echo '';
									}
								}
								$resultatsInf->closeCursor();
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
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date</th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Radio demandée' ?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(215) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date Résultats</th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Done by'; ?></th>						
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
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
									$presta_assuRad='prestations_'.$ligneNomAssuRad->nomassurance;
								}
							}

					?>
						<tr style="text-align:center;">
						
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedRadio->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedRadio->datehosp));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;" <?php if($ligneMedRadio->resultatsRad !=""){ echo 'colspan=3';}?>>
							<?php									
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_radio_hosp mr, '.$presta_assuRad.' p WHERE mr.id_prestationRadio=p.id_prestation AND p.id_prestation=:idPresta') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idPresta'=>$ligneMedRadio->id_prestationRadio
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
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
								echo $ligneMedRadio->qteRad;
							?>
							</td>							
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedRadio->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);
								
							while($ligneInf=$resultatsInf->fetch())
							{
								if($ligneMedRadio->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
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
	
	if($comptMedKine!=0)
	{
?>
		<span style="position:relative; font-size:250%;margin-bottom: 2px;padding:5px;"><?php echo 'Kinesitherapie'; ?></span>
		
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px" id="kinetable">
		
			<table class="tablesorter" cellspacing="0"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date</th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Actes' ?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(215) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedKine=$resultMedKine->fetch())//on recupere la liste des éléments
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
									$presta_assuKine='prestations_'.$ligneNomAssuKine->nomassurance;
								}
							}

					?>
						<tr style="text-align:center;">
						
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedKine->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedKine->datehosp));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php									
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_kine_hosp mr, '.$presta_assuKine.' p WHERE mr.id_prestationKine=p.id_prestation AND p.id_prestation=:idPresta') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idPresta'=>$ligneMedKine->id_prestationKine
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
								echo $ligneMedKine->qteKine;
							?>
							</td>							
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedKine->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);
								
							while($ligneInf=$resultatsInf->fetch())
							{
								if($ligneMedKine->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>							
							
							<td style="padding:10px; text-align: center;">
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedKine->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedKine->id_uM==$ligneMed->id_u)
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
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Consommables' ?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(215) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
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
									$presta_assuConsom='prestations_'.$ligneNomAssuConsom->nomassurance;
								}
							}

							
					?>
						<tr style="text-align:center;">
						
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedConsom->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsom->datehosp));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_consom_hosp mco, '.$presta_assuConsom.' p WHERE mco.id_prestationConsom=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
								echo $ligneMedConsom->qteConsom;
							?>
							</td>							
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedConsom->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);
								
							while($ligneInf=$resultatsInf->fetch())
							{
								if($ligneMedConsom->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
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
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date</th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Medicaments' ?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(215) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
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
									$presta_assuMedoc='prestations_'.$ligneNomAssuMedoc->nomassurance;
								}
							}

					?>
						<tr style="text-align:center;">
						
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedMedoc->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedMedoc->datehosp));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, '.$presta_assuMedoc.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
								echo $ligneMedMedoc->qteMedoc;
							?>
							</td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedMedoc->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
								if($ligneMedMedoc->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
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
	
	
	if($comptMedOrtho!=0)
	{
?>
		<span style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;"><?php echo 'Orthopedie'; ?></span>
		
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px" id="orthotable">
		
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr style="height:45px;">
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date</th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Actes' ?></th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(215) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr>
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedOrtho=$resultMedOrtho->fetch())//on recupere la liste des éléments
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
									$presta_assuOrtho='prestations_'.$ligneNomAssuOrtho->nomassurance;
								}
							}

					?>
						<tr style="text-align:center;">
						
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedOrtho->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedOrtho->datehosp));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_ortho_hosp mo, '.$presta_assuOrtho.' p WHERE mo.id_prestationOrtho=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
								echo $ligneMedOrtho->qteOrtho;
							?>
							</td>							
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedOrtho->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);
								
							while($ligneInf=$resultatsInf->fetch())
							{
								if($ligneMedOrtho->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
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
	<?php	
	}
	
	if($comptMedConsult!=0)
	{
	?>		
		<span style="position:relative; font-weight:400; font-size:250%; margin-bottom:10px; padding:5px;"><?php echo "Services"; ?></span>

		<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
		
			<table style="width:80%;" class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr style="height:45px;">
						<th style="width:15%; color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important">Services</th>
						<th style="width:5%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(215) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
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
									$presta_assuServ='prestations_'.$ligneNomAssuServ->nomassurance;
								}
							}
							
					?>
						<tr style="text-align:center;">
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedConsult->datehosp != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsult->datehosp));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
						
							<?php									
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_consult_hosp c, '.$presta_assuServ.' p WHERE c.id_prestationConsu=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
								echo $ligneMedConsult->qteConsu;
							?>
							</td>							
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
	
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedConsult->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);
								
							while($ligneInf=$resultatsInf->fetch())
							{
								if($ligneMedConsult->id_uI==$ligneInf->id_u)
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

	
	?>

<?php
	if($CountPH!=0)
	{
	?>		
		<span style="position:relative; font-weight:400; font-size:250%; margin-bottom:10px; padding:5px;font-family:ubuntu;"><?php echo "Patient Clinical History"; ?></span>

		<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;font-family:ubuntu;">
		
			<table style="width:80%;" class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr style="height:45px;">
						<th style="width:15%; color:#333; background:#ccc !important">Hispitalization <?php echo getString(71) ?></th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important">History</th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important">Done By</th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important">Done On</th>

					</tr> 
				</thead> 
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedHistory=$resultsPatientHistory->fetch())//on recupere la liste des éléments
						{							
					?>
						<tr style="text-align:center;">
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($_GET['datehosp'] != '0000-00-00'){ echo date('d-M-Y', strtotime($_GET['datehosp']));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<blockquote>
								<?php									
									echo $ligneMedHistory->history;
								?>
							</blockquote>
							</td>
							
							<td style="padding:10px; text-align:center;border-right: 1px solid #ccc;">
							<?php

							if($ligneMedHistory->id_uM!=""){
								$table = "medecins";
								$userId = $ligneMedHistory->id_uM;
							}else{
								$table = "infirmiers";
								$userId = $ligneMedHistory->id_uInf;
							}
														
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, '.$table.' m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$userId
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($userId ==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>	
							<td>
								<p class="badge" style="font-size: 13px;"><?php echo $ligneMedHistory->History_time; ?></p>
							</td>
						</tr>
					<!-- 	<tr style="border-bottom: 2px solid black;<?php if($anne == $ligneMedHistory->hist_date){echo "display: inline";} ?>"><td style="text-align: center;">End</td></tr> -->

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

	
	?>


	<!--
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
			echo '
			<table style="width:100%;background:#fff; border:1px solid #eee; border-radius:4px; margin-bottom:10px; padding: 20px;">
				<tr>
					<td style="text-align:center;font-size:30px;">'.getString(267).'
					</td>
				</tr>
			</table>';
	}
	?>
	-->
	
		<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin-bottom:10px; padding: 20px; <?php if($recomm != "") echo 'width:50%;'; else echo 'width:80%;';?>; display:none;" align="center" id="diagnorecomm">
						
			<?php 
			if($recomm != "")
			{
			?>
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
			<?php
			}else{
				echo '<tr><td style="text-align:center;font-size:30px;">Pas de traitments proposés pour cette consultation</td></tr>';
			}
			?>
		
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
			$idHosplt=$ligneConsultation->id_consu;
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
			
			
			<table class="tablesorter" cellspacing="0"> 
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
					<tr style="text-align:center;<?php if($ligneConsultation->id_uM == $_SESSION['id'] AND $ligneConsultation->id_consu == $idHosplt){ echo '';}?><?php if($comptDoneResultsPatient==0){ echo 'background:rgba(0,100,255,0.5);';}?>">
						<td style="padding:0;"><?php echo date('d-M-Y', strtotime($ligneConsultation->dateconsu)).' -- '.$ligneConsultation->heureconsu;?></td>
						<?php

						$resultatsTypeConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assu.' p WHERE c.id_typeconsult=p.id_prestation AND p.id_prestation=:idTypeconsu') or die( print_r($connexion->errorInfo()));
						$resultatsTypeConsu->execute(array(
						'idTypeconsu'=>$ligneConsultation->id_typeconsult
						));

						$resultatsTypeConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
						if($ligneTypeConsu=$resultatsTypeConsu->fetch())//on recupere la liste des éléments
						{
						?>
						<td>
						<?php 
							if($ligneTypeConsu->namepresta!="")
							{
								echo $ligneTypeConsu->namepresta;
							}else{
								echo $ligneTypeConsu->nompresta;
							}
						?>
						</td>
						<?php 
						}
						?>
						
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
						
							<a href="formPatient_hosp.php?num=<?php echo $ligneConsultation->numero;?>&idconsu=<?php echo $ligneConsultation->id_consu;?>&idtypeconsult=<?php echo $ligneConsultation->id_typeconsult;?>&idassuconsu=<?php echo $ligneConsultation->id_assuConsu;?>&showmore=ok&dateconsu=<?php echo $ligneConsultation->dateconsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(135); ?></a>
						
						<?php 
						if($ligneConsultation->id_uM == $_SESSION['id'] AND $ligneConsultation->dateconsu == $annee)
						{
							if($ligneConsultation->id_factureConsult!=NULL)
							{
						?>
							<a href="formPatient_hosp.php?num=<?php echo $ligneConsultation->numero;?>&consu=ok&idconsu=<?php echo $ligneConsultation->id_consu;?>&idtypeconsult=<?php echo $ligneConsultation->id_typeconsult;?>&idassuconsu=<?php echo $ligneConsultation->id_assuConsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>
						
						<?php
							}else{
							?>
								<span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 5px;"><?php echo getString(218) ?></span>
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
		$idHosplt=0;
		
		echo getString(261);
	}
	?>	
	<div style="margin-top:35px" id="fichepatient">	
		
<?php

	// echo 'SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero='.$_GET['num'].' AND c.id_consu!='.$idHosplt.' AND c.done=1 ORDER BY c.id_consu DESC';
	
	$resultats=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:num AND c.id_consu!=:idconsu AND c.done=1 ORDER BY c.id_consu DESC');
	$resultats->execute(array(
	'num'=>$_GET['num'],
	'idconsu'=>$idHosplt
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
							if($ligne->id_consu != $idHosplt)
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
										echo $ligneTypeConsu->namepresta;
									}else{
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
									<a href="formPatient_hosp.php?num=<?php echo $ligne->numero;?>&idconsu=<?php echo $ligne->id_consu;?>&idtypeconsult=<?php echo $ligne->id_typeconsult;?>&idassuconsu=<?php echo $ligne->id_assuConsu;?>&showmore=ok&dateconsu=<?php echo $ligne->dateconsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(135); ?></a>
								<?php
								$start_week=strtotime("last week");
								$start_week=date("Y-m-d",$start_week);

								if($ligne->dateconsu >= $start_week AND $ligne->id_uM == $_SESSION['id'])
								{
								?>
									<a href="formPatient_hosp.php?num=<?php echo $ligne->numero;?>&idtypeconsult=<?php echo $ligne->id_typeconsult;?>&idconsu=<?php echo $ligne->id_consu;?>&idassuconsu=<?php echo $ligne->id_assuConsu;?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a>
									
								<?php
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

function Clinical(fld){

if(fld == 'show'){
  document.getElementById('cliniHis').style.display='inline';
  document.getElementById('hide').style.display='inline';
  document.getElementById('show').style.display='none';
}	

if(fld == 'hide'){
  document.getElementById('cliniHis').style.display='none';
  document.getElementById('show').style.display='inline';
  document.getElementById('hide').style.display='none';
}
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

<div> <!-- footer -->
	<?php
		include('footer.php');
	?>
</div> <!-- /footer -->

	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	
		$('#prediagno').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#postdiagno').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#consult').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#soins').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#listexamen').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#listradio').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#consom').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#medoc').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
	</script>
	

</body>

</html>
