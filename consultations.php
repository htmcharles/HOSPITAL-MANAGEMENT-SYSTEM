<?php
session_start();

include("connect.php");
include("connectLangues.php");

$annee = date('Y').'-'.date('m').'-'.date('d');


if(isset($_GET['dltconsu']))
{
	
	$num=$_GET['num'];
	$idconsu=$_GET['dltconsu'];
	$nomconsu=$_GET['nomconsu'];
	
	$dltConsu=$connexion->prepare('DELETE FROM consultations WHERE id_consu=:idConsu');
		
	$dltConsu->execute(array(
	'idConsu'=>$idconsu
	
	))or die($dltConsu->errorInfo());
	
		
	echo '<script type="text/javascript"> alert("Consultation \''.$nomconsu.'\' deleted");</script>';
	
	
	if(isset($_GET['english']))
	{
		// echo '&english='.$_GET['english'];
		echo '<script type="text/javascript">document.location.href="consultations.php?num='.$num.'&consu=ok&english='.$_GET['english'].'&receptioniste=ok";</script>';
	
	}else{
		if(isset($_GET['francais']))
		{
			// echo '&francais='.$_GET['francais'];
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$num.'&consu=ok&francais='.$_GET['francais'].'&receptioniste=ok";</script>';
		
		}else{
			echo '<script text="text/javascript">document.location.href="consultations.php?consu=ok&num='.$num.'&receptioniste=ok"</script>';

		}
	}
	
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
		$prediagno=strip_tags($ligneConsult->prediagnostic);
		$postdiagno=strip_tags($ligneConsult->postdiagnostic);
		$codemedecin=$ligneConsult->id_uM;
		$num=$ligneConsult->numero;
		$idtypeconsult=$ligneConsult->id_typeconsult;
		$modifierIdConsu=$_GET['idconsu'];
	}
	$resultConsult->closeCursor();
	
	
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
	
	rapport +=controlMedecin(theForm.medecins);
	rapport +=controlTypeconsult(theForm.typeconsult,theForm.areaAutretypeconsult);

		if (rapport != "") {
		alert("Veuillez corrigez les erreurs suivantes:\n" + rapport);
					return false;
		 }
}

function controlMedecin(fld){
	var erreur="";

	if(fld.value=="0"){
	erreur="<?php echo 'Select the Doctor' ?>\n";
	fld.style.background="rgba(255,255,0,0.3)";
	}	
	return erreur;
}


function controlTypeconsult(fld1,fld2){
	var erreur="";

	if((fld1.value=="0" && fld2.value=="") || (fld1.value=="autretypeconsult" && fld2.value=="")){
	erreur="<?php echo getString(113) ?>\n";
	fld1.style.background="rgba(255,255,0,0.3)";
	fld2.style.background="rgba(255,255,0,0.3)";
	}	
	return erreur;
}

</script>
<style type="text/css">
		body{
		font-family: Century Gothic;
	}
</style>

</head>

<body>
<?php

if(isset($_SESSION['codeM']))
{
	if(isset($_GET['idconsuNext']))
	{
		$checkIdConsult=$connexion->query('SELECT *FROM consultations c ORDER BY c.id_consu DESC LIMIT 1');

	}else{

		$checkIdConsult=$connexion->query('SELECT *FROM consultations c WHERE c.id_typeconsult is NULL ORDER BY c.id_consu LIMIT 1');
	}

	$comptidConsult=$checkIdConsult->rowCount();

		if($comptidConsult != 0)
		{
			$checkIdConsult->setFetchMode(PDO::FETCH_OBJ);
			
			$ligne=$checkIdConsult->fetch();
			
			$idConsu = $ligne->id_consu;
			
		}else{

			$createIdconsult=$connexion->prepare('INSERT INTO consultations (id_uR,numero) VALUES(:idRec,:num)');

			$createIdconsult->execute(array(
			'idRec'=>$_SESSION['id'],
			'num'=>$_GET['num']
			));
			
			$checkIdCons=$connexion->query('SELECT *FROM consultations c WHERE c.id_typeconsult is NULL ORDER BY c.id_consu LIMIT 1');
			
			$checkIdCons->setFetchMode(PDO::FETCH_OBJ);
			
			$ligne=$checkIdCons->fetch();
			
			$idConsu = $ligne->id_consu;
			
			// echo $idConsu;
		}
	
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

<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<img src="images/logo_large.png" class="brand" />	
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
				<li>			
					<form method="post" action="consultations.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'].'&consu=ok';}?><?php if(isset($_GET['idconsuNext'])){ echo '&idconsuNext='.$_GET['idconsuNext'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['showmore'])){ echo '&showmore='.$_GET['showmore'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($idConsu)){ echo 'deleteIDconsu='.$idConsu;}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="consultations.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'].'&consu=ok';}?><?php if(isset($_GET['idconsuNext'])){ echo '&idconsuNext='.$_GET['idconsuNext'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['showmore'])){ echo '&showmore='.$_GET['showmore']; echo '&id_consu='.$_GET['id_consu']; echo '&dateconsu='.$_GET['dateconsu'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="consultations.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'].'&consu=ok';}?><?php if(isset($_GET['idconsuNext'])){ echo '&idconsuNext='.$_GET['idconsuNext'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['showmore'])){ echo '&showmore='.$_GET['showmore']; echo '&id_consu='.$_GET['id_consu']; echo '&dateconsu='.$_GET['dateconsu'];}?>" class="btn"><?php echo getString(29);?></a>
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
	if(isset($_GET['receptioniste']))
	{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?caissier=ok" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Caisse';?>
		</a>

	</div>
	<?php
	}else{
	?>
		<div style="text-align:center;margin-top:20px;">
			
			<a href="patients1.php?receptioniste=ok" class="btn-large" name="savebtn" style="font-size:20px;height:40px;paddin:10px 40px;">
				<?php echo 'Reception';?>
			</a>

		</div>
<?php
	}
}
?>

<?php



$sqlP=$connexion->query("SELECT *FROM patients p WHERE p.id_u='$id'");
$sqlM=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");
$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");
$sqlR=$connexion->query("SELECT *FROM receptionistes r WHERE r.id_u='$id'");


$comptidP=$sqlP->rowCount();
$comptidM=$sqlM->rowCount();
$comptidI=$sqlI->rowCount();
$comptidL=$sqlL->rowCount();
$comptidR=$sqlR->rowCount();


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
		$oxgen=$ligne->oxgen;
		$province=$ligne->province;
		$district=$ligne->district;
		$secteur=$ligne->secteur;
		$profession=$ligne->profession;
		$idassu=$ligne->id_assurance;
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
		$postdiagno=strip_tags($ligneConsu->postdiagnostic);
		$id_uM=$ligneConsu->id_uM;
		$numeroPa=$ligneConsu->numero;
		$idtypeconsu=$ligneConsu->id_typeconsult;
		$modifierIdConsu=$_SESSION['id'];
	}
	$resultConsu->closeCursor();
}

?>



<div  style="text-align:center; width:90%; margin-top:20px;" class="account-container">

<div id='cssmenu'>

<ul style="margin-top:20px;background:none;border:none;">

	<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($idConsu)){ echo '&deleteIDconsu='.$idConsu;}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><b><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</b></a></li>
	
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
}
?>

<br/>
<?php
if(!isset($_GET['showfiche']) and !isset($_GET['showmore']))
{

	if($comptidR!=0 OR $comptidI!=0 and isset($_GET['consu']))
	{
?>
		<div id="forFiche">

		<form style="margin-top:25px" method="post" action="traitement_consultations.php?num=<?php echo $num;?>&idMed=<?php echo $_SESSION['id'];?>&dateconsu=<?php echo $annee;?>&idassu=<?php echo $idassu;?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['idconsuNext'])){ echo '&idconsuNext='.$_GET['idconsuNext'];}else{ if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}else{ if(isset($idConsu)){ echo '&idconsult='.$idConsu;}}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormConsultation(this)" enctype="multipart/form-data">

			<table style="margin-top:40px; margin-bottom:-10px; width:100%;">
				<tr>
					<td style="text-align:center; width:33.333%;">
						<?php
							$pasconsu = 562;
							$selectelastconsu = $connexion->prepare('SELECT * FROM consultations c WHERE c.numero=:numero AND c.id_typeconsult!=:id_typeconsult AND c.dateconsu!=:annee AND ((c.id_assuConsu=1 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=3 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=4 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=5 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28 OR c.id_typeconsult!=1254 OR c.id_typeconsult!=1255)) OR (c.id_assuConsu=7 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=8 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=11 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=15 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=17 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=18 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=19 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=20 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28)) OR (c.id_assuConsu=21 AND (c.id_typeconsult!=25 OR c.id_typeconsult!=26 OR c.id_typeconsult!=27 OR c.id_typeconsult!=28))) ORDER BY c.id_consu DESC LIMIT 1');
							$selectelastconsu->execute(array(
								'numero'=>$_GET['num'],
								'id_typeconsult'=>$pasconsu,
								'annee'=>$annee
							));
							$selectelastconsu->setFetchMode(PDO::FETCH_OBJ);
							$count = $selectelastconsu->rowCount();
							if ($count>0) {
								$ligneselectlastconsu = $selectelastconsu->fetch();

								//$lastconsu = $ligneselectlastconsu->dateconsu;
								//$diff = $annee - $lastconsu;

								// $lastconsu = new DateTime(date('Y-m-d H:i:s', strtotime($ligneselectlastconsu->dateconsu)));
								// $today = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
								// $interval = $lastconsu->diff($today);
								
								$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($ligneselectlastconsu->dateconsu)));
								$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
								$interval = $datetime1->diff($datetime2);
								
								if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
								{
									if ($interval->format('%y')!=0 AND $interval->format('%m')!=0 AND $interval->format('%d')!=0) {
										$diff = $interval->format('%y '.getString(224).', %m '.getString(228).', %d ');
									}elseif ($interval->format('%y')==0 AND $interval->format('%m')!=0 AND $interval->format('%d')!=0) {
										$diff = $interval->format('%m '.getString(228).', %d ');
									}elseif ($interval->format('%y')==0 AND $interval->format('%m')==0 AND $interval->format('%d')!=0) {
										$diff = $interval->format(' %d');
									}
									
								}
								
								//echo "id = ".$ligneselectlastconsu->id_consu;
						?>
								<span style="position:relative; font-size:100%;" class="alert alert-success"></i> <?php echo getString(285).' '.$diff.' '.getString(287); ?></span>
						<?php
							}else{
						?>
								<span style="position:relative; font-size:100%;" class="alert alert-danger"></i> <?php echo getString(286); ?></span>
						<?php
							}
						?>
					</td>
					<td style="text-align:center; width:33.333%;">
						<span style="position:relative; font-size:250%;"></i> <?php echo 'Add New Consultation'; ?></span>
					</td>
					<td style="font-size:18px; padding-right:20px; text-align:right; width:33.333%;">
						<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php if(isset($_GET['idconsu'])){ echo $dateConsu;}else{ echo $annee;}?>
					</td>
				</tr>			
			</table>
			<hr>
			
			<input type="hidden" name="dateconsu" value="<?php if(isset($_GET['idconsu'])){ echo $dateConsu;}else{ echo $annee;}?>"/>
						
			<fieldset style="text-align:center; background:white;">
			
			<table style="width:98%; margin: 10px auto; background: #f8f8f8 none repeat scroll 0% 0%; border: 1px solid #eee; border-radius: 4px;" cellpadding=20 cellspacing=1>	
						
				<tr>
					<td style="padding: 20px 10px;" align="center">
					<?php
					if($comptidR!=0 OR $comptidI!=0)
					{
					?>
					
					<table style="background:#fff; border:1px solid #eee; border-radius: 4px; margin:auto; padding:5px;" cellpadding=3>
							
						<tr style="<?php if(isset($_SESSION['codeI'])){echo "display: none;";} ?>">
							<td><label for="medecins"><?php echo 'Nom du medecin'; ?></label></td>
										
							<td style="padding:10px;" align="center">													
								<select name="medecins" id="medecins" style="background:white; border:1px solid #ddd; height:40px; width:500px;">
								<?php
								
								$resultatsMedecins=$connexion->query('SELECT *FROM utilisateurs u, medecins m, categopresta_ins cp, servicemed sm WHERE u.id_u=m.id_u AND sm.id_categopresta=cp.id_categopresta AND sm.codemedecin=m.codemedecin AND u.status!=0 ORDER BY u.nom_u');
								
								$resultatsMedecins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
								while($ligneMedecins=$resultatsMedecins->fetch())//on recupere la liste des éléments
								{
								?>
									<option value="<?php echo $ligneMedecins->id_u;?>" <?php if(isset($_GET['idconsu'])){if($_GET['id_uM'] == $ligneMedecins->id_u){ echo 'selected="selected"';}}?>>
										<?php
										echo $ligneMedecins->nom_u.' '.$ligneMedecins->prenom_u;
										
										if($ligneMedecins->nomcategopresta == 'Consultations')
										{
											echo ' (Generalist)';
										}else{
											echo ' ('.$ligneMedecins->nomcategopresta.')';
										}
										?>
									</option>
								<?php
								}
								?>
								</select>
							</td>

						</tr>
						
						<tr>
							<td>
							</td>
							<td>
								<?php
								
								$resultatsPrestaConsultation=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu');
										
								$resultatsPrestaConsultation->execute(array(
								'idassu'=>$idassu	
								))or die( print_r($connexion->errorInfo()));

								$resultatsPrestaConsultation->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
											
								if($lignePrestaConsultation=$resultatsPrestaConsultation->fetch())//on recupere la liste des éléments
								{
								?>
									<input name="assuranceId" type="hidden" value="<?php echo $idassu;?>"/>
									
									<input name="assuranceName" type="hidden" value="<?php echo $lignePrestaConsultation->nomassurance;?>"/>
								<?php
								}
								?>
										
							</td>
							
						</tr>

						<tr id="assu" style="<?php if(isset($_SESSION['codeI'])){echo "display: none;";} ?>">
						
							<td><label for="typeconsult"><?php echo getString(113); ?></label></td>
							
							<td>										
								<select name="typeconsult" id="typeconsult" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:500px;" onchange="NewConsult('typeconsult')">

									<option value='0'><?php echo getString(114); ?></option>
							<?php
																	
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

						
						$resultatsPrestaConsultation=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=1 ORDER BY p.id_prestation');
						
						$resultatsPrestaConsultation->setFetchMode(PDO::FETCH_OBJ);
					
								if($ligneCatPrestaConsultation=$resultatsPrestaConsultation->fetch())
								{
									echo '<optgroup label="'.$ligneCatPrestaConsultation->nomcategopresta.'">';
									
									?>
										<option value="<?php echo $ligneCatPrestaConsultation->id_prestation;?>" <?php if(isset($_GET['idconsuNext']) and $idtypeconsu == $ligneCatPrestaConsultation->id_prestation){ echo "selected='selected'";}?> <?php if(isset($_GET['idconsu']) and $idtypeconsult == $ligneCatPrestaConsultation->id_prestation){ echo "selected='selected'";}?>>
											<?php echo $ligneCatPrestaConsultation->nompresta;?>
										</option>
									<?php

									while($lignePrestaConsultation=$resultatsPrestaConsultation->fetch())//on recupere la liste des éléments
									{
								?>
										<option value="<?php echo $lignePrestaConsultation->id_prestation;?>" <?php if(isset($_GET['idconsuNext']) and $idtypeconsu == $lignePrestaConsultation->id_prestation){ echo "selected='selected'";}?> <?php if(isset($_GET['idconsu']) and $idtypeconsult == $lignePrestaConsultation->id_prestation){ echo "selected='selected'";}?>>
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
								<input type="text" id="areaAutretypeconsult" name="areaAutretypeconsult" placeholder="<?php echo 'Inserer autre consultation'; ?>" style="border:1px solid #ddd; height:30px; width:500px;display:none;"/>
								
							</td>
							
						</tr>
					</table>
					<?php
						if (isset($_SESSION['codeI'])) {
					?>
					<table style="margin:30px auto auto; width:70%; padding-left:80px;" cellpadding=3>
						<tr>
							<td style="text-align:center; width:20%;"><label style="margin:auto;"for="poids"><?php echo getString(115) ?></label></td>
							<td style="text-align:center; width:20%;"><label style="margin:auto;"for="taille"><?php echo getString(238); ?></label></td>
							<td style="text-align:center; width:20%;"><label for="tempera"><?php echo getString(116) ?></label></td>
							<td style="text-align:center; width:20%;"><label for="oxgen"><?php echo 'Oxgen'; ?></label></td>
							<td style="text-align:center; width:20%;"><label for="tensionart"><?php echo getString(117) ?></label></td>
							<td style="text-align:center; width:20%;"><label for="pouls"><?php echo getString(239); ?></label></td>
						</tr>
						<tr>						
							<td style="text-align:left;color:black;">
								<input type="text" name="poids" id="poids"  value="<?php if(isset($_GET['idconsu']) and $comptidM!=0){echo $poids;}else{if(isset($_GET['idconsuNext'])){echo $poids;}else{ echo $poidsPa;}}?>" style="width:35px;"/><span style="line-height:37px;">Kg</span>
								
							</td>
							
							<td style="text-align:left;color:black;">
								<input type="text" name="taille" id="taille"  value="<?php if(isset($_GET['num']) AND $comptidR!=0 OR $comptidI!=0){ echo $taillePa;}?>" style="width:35px;"/><span style="line-height:37px;">Cm</span>
								
							</td>				
							<td style="text-align:left;color:black;">
								<input type="text" name="tempera" id="tempera"  value="<?php if(isset($_GET['num']) AND $comptidR!=0 OR $comptidI!=0){ echo $temperaPa;}?>" style="width:35px;"/><span style="line-height:37px;">°C</span>
							</td>

							<td style="text-align:left;color:black;">
								<input type="text" name="oxgen" id="oxgen"  value="<?php if(isset($_GET['num']) AND $comptidR!=0 OR $comptidI!=0){ echo $oxgen;}?>" style="width:35px;"/><span style="line-height:37px;">Oxgen</span>
							</td>
							
							<td style="text-align:left;color:black;">
								<input type="text" name="tensionart" id="tensionart" value="<?php if(isset($_GET['num']) AND $comptidR!=0 OR $comptidI!=0){ echo $tensionartPa;}?>" style="width:35px;"/><span style="line-height:37px;">mmHg</span>
								
							</td>
							
							<td style="text-align:left;color:black;">
								<input type="text" name="pouls" id="pouls" value="<?php if(isset($_GET['num']) AND $comptidR!=0 OR $comptidI!=0){ echo $poulsPa;}?>" style="width:35px;"/><span style="line-height:37px;">/min</span>
								
							</td>	
							
						</tr>
					</table>
					
					<?php
						}
					}
					?>
					</td>
					
				</tr>
			
	<table align="center">
				<tr>
					<?php
					if($comptidR!=0 OR $comptidI!=0)
					{
					?>
						<td>
							<button style="width:300px; margin-top:10px;" type="submit" name="saveconsubtn" id="saveconsubtn" class="btn-large">
								<i class="fa fa-check fa-lg fa-fw"></i> <?php echo getString(141) ?>
							</button>
						</td>
						<td>
							<a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($idConsu)){ echo '&deleteIDconsu='.$idConsu;}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 140px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140) ?></a>
						</td>
					<?php
					}
					?>
				</tr>
			</table>
		
			</fieldset>	
					
			
		</form>

		<?php
			
			$resultatsConsu=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.dateconsu=:annee AND p.numero=:numPa ORDER BY c.id_consu DESC');
			$resultatsConsu->execute(array(
			'numPa'=>$_GET['num'],	
			'annee'=>$annee	
			))or die( print_r($connexion->errorInfo()));
			
			$resultatsConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptPaC=$resultatsConsu->rowCount();
		
		if($comptPaC!=0)
		{
		?>
		<div style="overflow:auto;height:300px;background-color:none;">
		
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
				
				<thead> 
					<tr>
						<th>S/N</th> 
						<th><?php echo 'Patient Full name';?></th>
						<th><?php echo 'Add by';?></th>
						<th><?php echo 'Date de la consultation';?></th>
						<th><?php echo 'Doctor';?></th>
						<th><?php echo 'Type of consultation';?></th>
						<th colspan=2>Action</th>
						
					</tr>
				</thead> 
				
				<tbody>
				<?php
					while($ligneC=$resultatsConsu->fetch())//on récupère la liste des éléments
					{
				?>
						<tr style="text-align:center;<?php if($ligneC->status==0){?>background:rgba(255,0,0,0.15)<?php ;}?>"> 
							<td><?php echo $ligneC->numero;?></td>
							<td><?php echo $ligneC->nom_u.' '.$ligneC->prenom_u;?></td>
							<td>
							<?php
							$resultatsReceptionist=$connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=r.id_u AND r.id_u=:idRecept ORDER BY u.nom_u');
							$resultatsReceptionist->execute(array(
							'idRecept'=>$ligneC->id_uR,
							));
								
							$resultatsReceptionist->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
							if($ligneReceptionist=$resultatsReceptionist->fetch())//on recupere la liste des éléments
							{
							
								echo $ligneReceptionist->nom_u.' '.$ligneReceptionist->prenom_u;
							}
							?>
							</td>
							
							<td><?php echo $ligneC->dateconsu;?></td>
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
										
								if($ligneMedecins->nomcategopresta == 'Consultation')
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
							
							<td>
							<?php
							if($ligneC->id_factureConsult ==NULL)
							{
							?>
								<a class="btn" href="consultations.php?iduti=<?php echo $_SESSION['id'];?>&dltconsu=<?php echo $ligneC->id_consu;?>&num=<?php echo $ligneC->numero;?>&nomconsu=<?php echo $nomconsu;?>&consu=ok&receptioniste=ok<?php if(isset($_GET['idconsu'])){ echo'&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
										<i class="fa fa-trash fa-1x fa-fw"></i>
								</a>
							<?php
							}else{
								echo '<i class="fa fa-check fa-1x fa-fw"></i>';
							}
							?>
							</td>
							
							<td>
							<?php
							if($ligneC->dateconsu ==$annee)
							{
							?>
								<a class="btn" href="consultations.php?iduti=<?php echo $_SESSION['id'];?>&idconsu=<?php echo $ligneC->id_consu;?>&num=<?php echo $ligneC->numero;?>&id_uM=<?php echo $ligneC->id_uM;?>&nomconsu=<?php echo $nomconsu;?>&consu=ok&receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
										<i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32).'' ?>
								</a>
							<?php
							}
							?>
							</td>
							
						</tr>
				<?php
					}
					$resultatsConsu->closeCursor();
				?>
				</tbody> 
		
			</table>
		</div>
		<?php
		}else{
		?>
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
				<thead> 
					<tr>
						<th><?php echo getString(152).' ';?> 
						<?php
						
						$resultPatient=$connexion->prepare('SELECT *FROM patients p, utilisateurs u WHERE p.numero=:numPa AND p.id_u=u.id_u ORDER BY p.numero ASC LIMIT 1');
						$resultPatient->execute(array(
						'numPa'=>$_GET['num']
						));
						
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);
						
							if($ligneC=$resultPatient->fetch())//on récupère la liste des éléments
							{
								echo $ligneC->nom_u.' '.$ligneC->prenom_u;
							}
						?>
						<?php echo ' has no consultations yet';?></th>
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
</div>

<?php
}

if(isset($_GET['showmore']))
{
	$numero = $_GET['num'];
	$idConsu = $_GET['id_consu'];
	$dateconsu = $_GET['dateconsu'];
	
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptConsult=$resultConsult->rowCount();
	
	
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, consultations c WHERE c.id_consu=:idConsu AND mc.id_consuMed=c.id_consu AND mc.id_consuMed=:idConsu ORDER BY mc.id_medconsu');		
		$resultMedConsult->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedConsult=$resultMedConsult->rowCount();
	
	
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, consultations c WHERE c.id_consu=:idConsu AND mi.id_consuInf=c.id_consu AND mi.id_consuInf=:idConsu ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedInf=$resultMedInf->rowCount();
	
	
	
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, consultations c WHERE c.id_consu=:idConsu AND ml.id_consuLabo=c.id_consu AND ml.id_consuLabo=:idConsu ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedLabo=$resultMedLabo->rowCount();
	
	
	
?>
		<a href="consultations.php?num=<?php echo $_GET['num'];?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo getString(101) ?></a>
		
		<a href="consultations.php?num=<?php echo $_GET['num'];?>&showfiche=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(100) ?></a>
		
		<br/>
		<br/>

	<div id="showmore">	

<?php

	if($ligneConsult=$resultConsult->fetch())//on recupere la liste des éléments
	{
?>
	
		<span style="position:relative; font-size:200%;margin-bottom:2px; padding:5px;"><?php echo getString(129) ?> <span style="color:#a00000; font-size:120%; font-weight:100;"><?php echo $ligneConsult->dateconsu;?></span></span>
		
		
		<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; font-size:120%; margin-bottom:10px; padding: 20px; width:100%;" align="center">
			<tr>
				<td style="width:40%; text-align:left; vertical-align:top;">
					<span style="font-weight:bold;"><?php echo getString(113) ?> : </span>
					<?php
													
					$resultatsTypeConsu=$connexion->prepare('SELECT *FROM consultations c, prestations p WHERE c.id_typeconsult=p.id_prestation AND p.id_prestation=:idTypeconsu') or die( print_r($connexion->errorInfo()));
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
				
				<td style="width:15%; text-align:left; vertical-align:top;">
					<span style="font-weight:bold;"><?php echo getString(115) ?> : </span><?php echo $ligneConsult->poids;?>
				</td>
				
				<td style="width:15%; text-align:left; vertical-align:top;">
					<span style="font-weight:bold;"><?php echo getString(116) ?> : </span><?php echo $ligneConsult->temperature;?>
				</td>
				
				<td style="width:15%; text-align:left; vertical-align:top;">
					<span style="font-weight:bold;"><?php echo getString(117) ?> : </span><?php echo $ligneConsult->tensionart;?>
				</td>
				
			</tr>
			
			<tr>
				<td style="text-align:left; vertical-align:top;">
					<span style="font-weight:bold;"><?php echo getString(154) ?> : </span><?php echo $ligneConsult->motif;?>
				</td>
				
				<td style="text-align:left; vertical-align:top;">
					<span style="font-weight:bold;"><?php echo getString(155) ?> : </span><?php echo $ligneConsult->etatpatient;?>
				</td>
				
				<td style="text-align:left; vertical-align:top;">
					<span style="font-weight:bold;"><?php echo getString(156) ?> : </span><?php echo $ligneConsult->antecedent;?>
				</td>
								
				<td style="text-align:left; vertical-align:top;">
				<?php 
				if($ligneConsult->signsymptomes !="")
				{				
				?>
					<span style="font-weight:bold;"><?php echo getString(157) ?> : </span><?php echo $ligneConsult->signsymptomes;?>
				<?php 
				}else{
					echo '<span style="font-weight:bold;">'.getString(157).' :</span> -------';
				}
				?>
				</td>
			</tr>
			
		</table>
<?php
	}
?>

<?php
	if($comptMedConsult!=0)
	{
?>		
		<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
		
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th>Services</th>
						<th><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedConsult=$resultMedConsult->fetch())//on recupere la liste des éléments
						{
					?>
						<tr style="text-align:center;">
							
							<td style="text-align:center;">
						
							<?php									
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_consult c, prestations p WHERE c.id_prestationConsu=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
							
							<td>
<?php
							
		$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
		$resultatsMed->execute(array(
		'idMed'=>$ligneMedConsult->id_uM
		));

		$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
		while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
		{
?>	
			<?php echo getString(130) ?> <?php if($ligneMedConsult->id_uM==$_SESSION['id']){ echo getString(132);}else{ echo $ligneMed->nom_u;?><br/><?php echo $ligneMed->prenom_u;}?>
							
<?php
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
	}else{
	?>
		<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(131) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	}
	?>

	
<?php 
	if($comptMedInf!=0)
	{
?>
		<span style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;"><?php echo getString(98) ?></span>
		
		<div style="overflow:auto;height:auto;padding:5px; margin-bottom:30px;">
		
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th><?php echo getString(98) ?></th>
						<th><?php echo getString(122) ?></th>
						<th><?php echo getString(71) ?></th>
						<th><?php echo getString(21) ?></th>
						<th><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des éléments
						{
					?>
						<tr style="text-align:center;">
							<td style="text-align:center;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_inf mi, prestations p WHERE mi.id_prestation=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
							
							<td><?php echo $ligneMedInf->autrePrestaI;?></td>
							<td><?php if($ligneMedInf->datesoins != '0000-00-00'){ echo $ligneMedInf->datesoins;}else{ echo '';}?></td>
							<td>
<?php
							
		$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
		$resultatsInf->execute(array(
		'idInf'=>$ligneMedInf->id_uI
		));

		$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
		while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
		{
?>	
			<?php if($ligneMedInf->id_uI==$ligneInf->id_u){ echo $ligneInf->nom_u.' '.$ligneInf->prenom_u;}else{ echo '';}?>
							
<?php
		}
		$resultatsInf->closeCursor();
?>						
							</td>	
							<td>
<?php
							
		$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
		$resultatsMed->execute(array(
		'idMed'=>$ligneMedInf->id_uM
		));

		$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
		while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
		{
?>	
			<?php echo getString(130) ?> <?php if($ligneMedInf->id_uM==$_SESSION['id']){ echo getString(132);}else{ echo $ligneMed->nom_u;?><br/><?php echo $ligneMed->prenom_u;}?>
							
<?php
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
	}else{
	?>
		<table style="margin-bottom:30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(104) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	}
	
	if($comptMedLabo!=0)
	{
?>
		<span style="position:relative; font-size:250%;margin-bottom: 2px;padding:5px;"><?php echo getString(133) ?></span>
		
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px" id="labotable">
		
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th><?php echo getString(99) ?></th>
						<th><?php echo getString(3) ?></th>
						<th><?php echo getString(22) ?></th>
						<th>Date</th>
						<th><?php echo getString(158) ?></th>					
						<th><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des éléments
						{
					?>
						<tr style="text-align:center;">
							<td style="text-align:center;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_labo ml, prestations p WHERE ml.id_prestationExa=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
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
							
							<td>
							<?php
							if($ligneMedLabo->resultats!="" or $ligneMedLabo->autreresultats!="")
							{							
							?>
								<a href="patients1.php?num=<?php echo $ligneMedLabo->numero;?>&idmedLabo=<?php echo $ligneMedLabo->id_medlabo;?>&id_consu=<?php echo $_GET['id_consu'];?>&dateconsu=<?php echo $ligneMedLabo->dateconsu;?>&showresult=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="showresult" name="showresult" class="btn">Show</a>
								
							<?php 
							}
							?>
							</td>
							
							<td>
							<?php 

							$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=:idL') or die( print_r($connexion->errorInfo()));
							$resultatsLabo->execute(array(
							'idL'=>$ligneMedLabo->id_uL
							));

							$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneLabo=$resultatsLabo->fetch())//on recupere la liste des éléments
							{
								echo $ligneLabo->nom_u.' '.$ligneLabo->prenom_u;
							}else{
								echo '';
							}
							?>
							</td>
							
							<td>
								<?php 
								if($ligneMedLabo->dateresultats!="0000-00-00")
								{
									echo $ligneMedLabo->dateresultats;
								}else{
									echo '';
								}								
								?>
							</td>
							
							<td>
								<?php 
								if($ligneMedLabo->diagnosticexa!="")
								{
									echo $ligneMedLabo->diagnosticexa;
								}else{
									echo '';
								}								
								?>
							</td>
							
							<td>
 <?php
					$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
		$resultatsMed->execute(array(
		'idMed'=>$ligneMedLabo->id_uM
		));

		$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
		while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
		{
?>	
			<?php echo getString(130) ?> <?php if($ligneMedLabo->id_uM==$_SESSION['id']){ echo getString(132);}else{ echo $ligneMed->nom_u;?><br/><?php echo $ligneMed->prenom_u;}?>
							
<?php
		}
		$resultatsMed->closeCursor();
?>						
							</td>			
							
						</tr>

					<?php
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
	}else{
	?>
		<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(107) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	}
	?>

		<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; font-size:120%; margin-bottom:10px; padding: 20px; width:100%;" align="center" id="diagnorecomm">
			<tr>
				
				<?php
				if($ligneConsult->diagnostic != "")
				{
				?>
				<td style="text-align:left; vertical-align:top;">
				
					<span style="font-weight:bold;"><?php echo getString(158) ?> : 
					<textarea id="diagnofinal" name="diagnofinal" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%; height: 50px; width: 100px; max-width:600px; min-width:500px; min-height:200px;" readonly="readonly"><?php echo strip_tags($ligneConsult->diagnostic);?></textarea>
					
				</td>
				
				<?php
				}else{
				?>
				<td style="text-align:left; vertical-align:top;">
					<?php 
					if($ligneConsult->diagnostic == "" AND $ligneConsult->id_uM == $_SESSION['id'])
					{
					?>
					<form method="post" action="traitement_resultats.php?num=<?php echo $num;?>&updateidconsu=<?php echo $ligneConsult->id_consu;?>&dateconsu=<?php echo $_GET['dateconsu'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResult(this)" enctype="multipart/form-data">
					
						<label style="padding-left: 40px; padding-top: 10px;" for="diagnofinal"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo getString(158) ?></label>
					
						<textarea id="diagnofinal" name="diagnofinal" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%; height: 50px; width: 100px; max-width:600px; min-width:500px; min-height:200px;" required></textarea>

						<button style="width: 300px; margin: 0 auto auto 20px;" type="submit" name="diagnobtn" class="btn-large">
							<i class="fa fa-check fa-lg fa-fw"></i> <?php echo getString(141) ?>
						</button>
					
						<!--
						
						<a href="consultations.php?num=<?php echo $_GET['num'];?>&id_consu=<?php echo $_GET['id_consu'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&showmore=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 40px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140) ?></a>
						
						-->
						
					</form>
						
					<?php 
					}else{
						if($ligneConsult->diagnostic != "")
						{
					?>
						<label style="padding-left: 40px; padding-top: 10px;" for="diagnofinal"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo getString(158) ?></label>
						
						<textarea id="diagnofinal" name="diagnofinal" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%; height: 50px; width: 100px; max-width:600px; min-width:500px; min-height:200px;" readonly="readonly"><?php echo strip_tags($ligneConsult->diagnostic);?></textarea>
						
					<?php
						}
					}
					?>
				</td>
				<?php
				}
				?>
				
				<?php 
				if($ligneConsult->recommandationnext != "")
				{
				?>
				<td style="text-align:left; vertical-align:top;">
				
					<span style="font-weight:bold;"><?php echo getString(159) ?> : 
					<textarea id="diagnofinal" name="diagnofinal" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%; height: 50px; width: 100px; max-width:600px; min-width:500px; min-height:200px;" readonly="readonly" required><?php echo strip_tags($ligneConsult->recommandationnext);?></textarea>
				</td>
				<?php
				}else{
				?>
				<td style="text-align:left; vertical-align:top;">
					<?php 
					if($ligneConsult->recommandationnext == "" AND $ligneConsult->id_uM == $_SESSION['id'])
					{
					?>
					<form method="post" action="traitement_resultats.php?num=<?php echo $num;?>&updateidconsu=<?php echo $ligneConsult->id_consu;?>&dateconsu=<?php echo $_GET['dateconsu'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResult(this)" enctype="multipart/form-data">
					
						<label style="padding-left: 40px; padding-top: 10px;" for="recommfinal"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo getString(159) ?></label>
						
						<textarea id="recommfinal" name="recommfinal" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%; height: 50px; width: 100px; max-width:600px; min-width:500px; min-height:200px;" required></textarea>

						<button style="width: 300px; margin: 0 auto auto 20px;" type="submit" name="recommbtn" class="btn-large">
							<i class="fa fa-check fa-lg fa-fw"></i> <?php echo getString(141) ?>
						</button>
					
						<!--
						
						<a href="consultations.php?num=<?php echo $_GET['num'];?>&id_consu=<?php echo $_GET['id_consu'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&showmore=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 40px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140) ?></a>
						
						-->
						
					</form>
						
					<?php 
					}else{

						if($ligneConsult->recommandationnext != "")
						{
					?>
						<label style="padding-left: 40px; padding-top: 10px;" for="recommfinal"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo getString(159) ?></label>
						<textarea id="recommfinal" name="recommfinal" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%; height: 50px; width: 100px; max-width:600px; min-width:500px; min-height:200px;" readonly="readonly"><?php echo strip_tags($ligneConsult->recommandationnext);?></textarea>
						
					<?php
						}
					}
					?>
					
				</td>
				<?php
				}
				?>
				
			</tr>
			
		</table>

	</div>

<?php
}


if(isset($_GET['showfiche']) and !isset($_GET['showmore']))
{
		$resultats=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
		$resultats->execute(array(
		'operation'=>$_GET['num']	
		));
		
		$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptFiche=$resultats->rowCount();
	
	
?>
		<a href="consultations.php?num=<?php echo $_GET['num'];?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101) ?></a>
		
	<div style="margin-top:35px" id="fichepatient">	
		
<?php 
	if($comptFiche!=0) 
	{
?>
		<span style="position:relative; font-weight:100; font-size:250%; margin-bottom: 2px;padding:5px;"><?php echo getString(134) ?></span>
		
			<div style="overflow:auto;height:400px; padding:5px;">
			
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th><?php echo getString(97) ?></th>
							<th><?php echo getString(113) ?></th>
							<th><?php echo getString(135) ?></th>
							<th><?php echo getString(19) ?></th>
							<th>Actions</th>
						</tr> 
					</thead> 
				
				
					<tbody>	
						<?php
						try
						{
							while($ligne=$resultats->fetch())//on recupere la liste des éléments
							{
						?>
							<tr style="text-align:center;">
								<td><?php echo $ligne->dateconsu.'<br/>'.$ligne->heureconsu;?></td>
								<?php
													
								$resultatsTypeConsu=$connexion->prepare('SELECT *FROM consultations c, prestations p WHERE c.id_typeconsult=p.id_prestation AND p.id_prestation=:idTypeconsu') or die( print_r($connexion->errorInfo()));
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
									<a href="consultations.php?num=<?php echo $ligne->numero;?>&id_consu=<?php echo $ligne->id_consu;?>&showmore=ok&dateconsu=<?php echo $ligne->dateconsu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(135) ?></a>
								
								</td>
								
								<td>
<?php
								
			$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m, servicemed sm, categopresta cp WHERE u.id_u=m.id_u AND m.id_u=:idMed AND sm.codemedecin=m.codemedecin AND cp.id_categopresta=sm.id_categopresta') or die( print_r($connexion->errorInfo()));
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
				?>
								
<?php
			}
			$resultatsMed->closeCursor();
?>						
								</td>
								
								<td>
								<?php 
								if($ligne->id_uM == $_SESSION['id'] AND $ligne->id_factureConsult == 0)
								{
								?>
									<a href="consultations.php?num=<?php echo $ligne->numero;?>&consu=ok&idconsu=<?php echo $ligne->id_consu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32) ?></a>
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
	}else{
	?>
		<table class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(161) ?>.<span style="color:#19BC9C;position:relative;"><?php echo getString(162) ?>.</span></th> 
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

function NewConsult(consult)
{
	var consu=document.getElementById('typeconsult').value;

	if( consu =='autretypeconsult')
	{
		document.getElementById('areaAutretypeconsult').style.display='inline';
		document.getElementById('addAutretypeConsult').style.visibility='visible';
		document.getElementById('addtypeConsult').style.visibility='hidden';
	}else{
		document.getElementById('areaAutretypeconsult').style.display='none';
		document.getElementById('addAutretypeConsult').style.visibility='hidden';
		document.getElementById('addtypeConsult').style.visibility='visible';
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

<div>
	<footer style="bottom: 0px; width: 100%; text-align: center; background: #fff; border-top: 1px solid #eee; padding: 10px 0px; vertical-align: middle;">
		<p style="margin:0"><span style="color:#a00000">Medical FIle®</span> is a product of <span style="font-style:bold;">Innovate Solutions Ltd</span>. ©2022-2016 All rights reserved.</p>
	</footer>
</div>

</body>

</html>
