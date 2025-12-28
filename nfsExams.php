<?php 
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


$annee = date('Y').'-'.date('m').'-'.date('d');

if(isset($_GET['deleteMoreMedLabo']))
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
	
	if(isset($_GET['updateidmoremedLabo']))
	{
		$updateidmoremedLabo="&updateidmoremedLabo=ok";				
	}else{
		$updateidmoremedLabo="";				
	}
	
	
	
	$id_moremedL= $_GET['deleteMoreMedLabo'];
	
	$deleteLabo=$connexion->prepare('DELETE FROM more_med_labo WHERE id_moremedlabo=:id_moremedL');
	
	$deleteLabo->execute(array(
	'id_moremedL'=>$id_moremedL
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer un examen");</script>';
	
	echo '<script type="text/javascript">document.location.href="nfsExams.php?labo='.$_GET['labo'].'&num='.$_GET['num'].'&idmedLab='.$_GET['idmedLab'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&presta='.$_GET['presta'].'&idassu='.$_GET['idassu'].'&previewprint=ok'.$langue.''.$updateidmoremedLabo.'";</script>';
	
}

if(isset($_POST['nfsResultatbtn'])){
	$nfsResult = array();
	$id_prestationExa = array();
	$idmedLab = $_GET['idmedLab'];
	$idassu = $_GET['idassu'];
	$idmed = $_GET['idmed'];
	$numero = $_GET['num'];
	$id_uL = $_SESSION['id'];

	foreach($_POST['nfsResult'] as $result){
		$nfsResult [] = $result;
	}

	foreach($_POST['id_prestationExa'] as $id_prestation){
		$id_prestationExa [] = $id_prestation;
	}

	for($i=0;$i<sizeof($nfsResult);$i++){
		if($nfsResult[$i] !=""){

			//check if there result in 
			$selectFromMoreResult = $connexion->prepare("SELECT * FROM more_med_labo WHERE id_prestationExa=:id_prestationExa AND id_medlabo=:id_medlabo");
			$selectFromMoreResult->execute(array('id_prestationExa'=>$id_prestationExa[$i],'id_medlabo'=>$idmedLab));
			$getData = $selectFromMoreResult->fetch(PDO::FETCH_OBJ);
			$count= $selectFromMoreResult->rowcount();
			if($count==0){
				//save to more result
				$saveNfsExam = $connexion->prepare("INSERT INTO more_med_labo(id_medlabo,id_prestationExa,id_assuLab,autreresultats,numero,id_uM,id_uL) VALUES(:id_medlabo,:id_prestationExa,:id_assuLab,:autreresultats,:numero,:id_uM,:id_uL)");
				$saveNfsExam->execute(array("id_medlabo"=>$idmedLab,"id_prestationExa"=>$id_prestationExa[$i],"id_assuLab"=>$idassu,"autreresultats"=>$nfsResult[$i],"numero"=>$numero,"id_uM"=>$idmed,"id_uL"=>$id_uL));

			}else{
				//update more_med_labo

				$resultMoreMedLabo=$connexion->prepare("UPDATE more_med_labo SET autreresultats=:moreautreresult, id_uL=:id_uL WHERE id_moremedlabo =:idmedLabo AND id_medlabo=:id_medlabo");
				$resultMoreMedLabo->execute(array(
				'moreautreresult'=>$nfsResult[$i],
				'id_uL'=>$id_uL,
				'idmedLabo'=>$getData->id_moremedlabo,
				'id_medlabo'=>$getData->id_medlabo
				));
			}

			//update medlabo exam fait
			$updateMedlabo = $connexion->prepare("UPDATE med_labo SET examenfait=1,moreresultats=1,dateresultats=:dateresultats,id_uL=:id_uL WHERE id_medlabo=:id_medlabo");
			$updateMedlabo->execute(array('dateresultats'=>$annee,'id_uL'=>$id_uL,'id_medlabo'=>$idmedLab));
		}
	}
}

?>

<!doctype html>
<html>
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<meta charset="utf-8"/>
	<title><?php echo 'More Labo Results'; ?></title>
	
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
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"/><!--Header-->

		<!-------------------calendrier------------------->
	
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>	
	<link href="css/form-signin.css" rel="stylesheet" type="text/css">

			<!---------------Pagination--------------------->
			
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">		
	
	<script src="myQuery.js"></script>

</head>

<body>
<?php

$id=$_SESSION['id'];

$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");

$comptidL=$sqlL->rowCount();


$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $comptidL!=0 AND isset($_GET['num']))
{
	if($status==1)
	{

		if(isset($_GET['num']))
		{
			$resultats=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
			$resultats->execute(array(
			'operation'=>$_GET['num']	
			));
			
			$resultats->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$resultats->fetch())
			{
				$num=$ligne->numero;
				$nom_uti=$ligne->nom_u;
				$prenom_uti=$ligne->prenom_u;
				$sexe=$ligne->sexe;
				$dateN=$ligne->date_naissance;
				$province=$ligne->province;
				$district=$ligne->district;
				$secteur=$ligne->secteur;
					
				$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
				$resultAdresse->execute(array(
				'idProv'=>$ligne->province,
				'idDist'=>$ligne->district,
				'idSect'=>$ligne->secteur
				));
						
				$resultAdresse->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

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
						
				$phone=$ligne->telephone;
				$mail=$ligne->e_mail;
				$profession=$ligne->profession;
				$idassu=$ligne->id_assurance;
				$bill=$ligne->bill;
				$password=$ligne->password;
				$idP=$ligne->id_u;
			}
			$resultats->closeCursor();

			$old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].'	';//reçoit l'année de naissance
			$month=$dateN[5].''.$dateN[6].'	';//reçoit le mois de naissance

			$an= date('Y')-$old.'	';//recupere l'âge en année
			$ages= date('Y')-$old.'	';//recupere l'âge en année
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

?>

<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<img src="images/logo_large.png" class="brand" />	
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
				<li class="">			
					<form method="post" action="nfsExams.php?num=<?php echo $_GET['num'];?>&labo=<?php echo $_SESSION['id'];?>&facture=ok<?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idmedLab'])){ echo '&idmedLab='.$_GET['idmedLab'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="nfsExams.php?english=english<?php if(isset($_GET['labo'])){ echo '&labo='.$_GET['labo'];}if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idmedLab'])){ echo '&idmedLab='.$_GET['idmedLab'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['previewprint'])){ echo '&previewprint=ok';}?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="nfsExams.php?francais=francais<?php if(isset($_GET['labo'])){ echo '&labo='.$_GET['labo'];}if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idmedLab'])){ echo '&idmedLab='.$_GET['idmedLab'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['previewprint'])){ echo '&previewprint=ok';}?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?>" class="btn"><?php echo getString(29);?></a>
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

<div class="account-container" style="width:90%">

<?php

if($comptidL!=0)
{
?>
<div id='cssmenu' style="text-align:center">

<ul style="margin-top:20px;background:none;border:none;">

	<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?>&caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
	
	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49); ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49); ?></a></li>
	
</ul>

<ul style="margin-top:20px; background:none;border:none;">
		
	<div style="display:none;" id="divMenuMsg">

		<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57); ?></a>
		
		<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
		
		<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

	</div>
	
</ul>
	
</div>
<?php
}
?>

	<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:10px auto 20px auto; padding: 10px; width:90%;">  
		<tr>
			<td style="text-align:center; width:33.333%;">
				
				<p class="patientId" style="text-align:left"><span> <?php echo getString(89);?>:</span> <?php echo $nom_uti.' '.$prenom_uti; ?></p> 
				
				<p class="patientId" style="text-align:left"><span>S/N:</span> <?php echo $num; ?></p> 

				<p class="patientId" style="text-align:left"><span> <?php echo 'Age';?>:</span> <?php echo $an.' ( '.$dateN.' )';?></p> 
				
			</td>
		<?php
		if($idassu!=NULL)
		{
		?>
			<td style="text-align:center; width:33.333%;">
				
				<p class="patientId" style="text-align:left"><span>Adresse:</span> <?php echo $adresse; ?></p> 

				<p class="patientId" style="text-align:left"><span>Insurance type:</span>
				<?php
				
				$resultAssu=$connexion->prepare('SELECT * FROM assurances a WHERE id_assurance=:assu');
				$resultAssu->execute(array(
				'assu'=>$idassu
				)); 
				
				if($ligneAssu=$resultAssu->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
				{
					echo $ligneAssu->nomassurance.' ('.$bill.'%)';
				}
				?>
				</p>
				
			</td>
		<?php
		}else{
		?>
			<td style="text-align:center; width:33.333%;">
				
				<p class="patientId" style="text-align:left"><span>Adresse:</span> <?php echo $adresse; ?></p> 

				<p class="patientId" style="text-align:left"><span>Insurance type:</span> <?php echo "Privé"; ?>
				
			</td>
		<?php
		}
		?>

			<td style="font-size:18px; padding-right:20px; text-align:right; width:33.333%;">
				<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo $annee;?>
				
				<input size="25px" type="hidden" id="today" name="today" value="<?php echo $annee;?>"/>
			</td>
		</tr>

	</table>
    <form method="POST">
        <?php 
            //Get all nfs exams
            $nfsExams = $connexion->query("SELECT * FROM nfssubexams WHERE status=1");
            $nfsExams ->setFetchMode(PDO::FETCH_OBJ);

		?>
        <table class="tablesorter tablesorter3" style="border:1px solid #ddd;font-family:calibri;">
            <tr style="height:50px;color:white;font-size:15px;">
                <th style="text-align:center">#</th>
                <th style="text-align:center">Exam</th>
                <th style="text-align:center">Result</th>
                <th style="text-align:center">Ranges</th>
                <th style="text-align:center">Unit</th>
            </tr>
            <?php  while($getExam = $nfsExams->fetch()){ 
				//check if there result in 
				$selectFromMoreResult = $connexion->prepare("SELECT * FROM more_med_labo WHERE id_prestationExa=:id_prestationExa AND id_medlabo=:id_medlabo");
				$selectFromMoreResult->execute(array('id_prestationExa'=>$getExam->id_prestation,'id_medlabo'=>$_GET['idmedLab']));
				if($getData = $selectFromMoreResult->fetch(PDO::FETCH_OBJ)){
					$resu = $getData->autreresultats;
				}else{
					$resu ="";
				}
				// $count= $selectFromMoreResult->rowcount();
			?>
            <tr>
                <td style="text-align:center"><?php echo $getExam->idCount; ?></td>
                <td style="text-align:center"><?php echo strtoupper($getExam->namepresta); ?></td>
                <td style="text-align:center">
					<input type="text" name="nfsResult[]" value="<?php echo $resu; ?>" style="width:90px;height:35px;margin-top:5px;">
					<input type="hidden" name="id_prestationExa[]" value="<?php echo $getExam->id_prestation;?>" style="width:90px;height:35px;margin-top:5px;">
				</td>
                <td style="text-align:center">
                     <?php
                    //  $ages = 17;
                    //  echo $ages;
                     if($ages<18){
                       echo $getExam->rangesChildren; 
                    //    echo "child";
                     }else{
                         if($sexe=="M"){
                            echo $getExam->rangesMen; 
                            // echo "men";
                         }else{
                             if($sexe=="F"){
                                echo $getExam->rangesWomen; 
                                // echo "women";
                             }
                         }
                     }
                    ?>
                </td>
                <td style="text-align:center"><?php echo $getExam->mesure; ?></td>
            </tr>
            <?php }?>
			<tr>
				<td colspan=12 style="text-align:center">
					<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="nfsResultatbtn" class="btn-large">
						<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(68) ?>
					</button>
				</td>
				
			</tr>
        </table>		
    </form>
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

	
	function ShowAddbtn(souscategorie)
	{	
		var souscatego =document.getElementById('souscategorie').value;
			
		if(souscatego!="0")
		{
			document.getElementById('divViewExam').style.display='inline';
			document.getElementById('addbtn').style.display='inline';
	
			if(souscatego=="spermo")
			{
				document.getElementById('addspermobtn').style.display='inline';
				document.getElementById('addbtn').style.display='none';
			
			}else{
				document.getElementById('addspermobtn').style.display='none';
			}
			
		}else{
			document.getElementById('divViewExam').style.display='none';
			document.getElementById('addbtn').style.display='none';
			document.getElementById('addspermobtn').style.display='none';

		}
		
	}
	
	function ShowList(list)
	{
		if( list =='Users')
		{
			document.getElementById('divMenuUser').style.display='inline';
			document.getElementById('divMenuMsg').style.display='none';
		
		}
		
		if( list =='Msg')
		{
			document.getElementById('divMenuMsg').style.display='inline';
			document.getElementById('divMenuUser').style.display='none';
			document.getElementById('divListe').style.display='none';
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
		
		if( list =='MsgRecu')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='inline';
			document.getElementById('EnvoiMsg').style.display='inline';
			document.getElementById('MsgEnvoye').style.display='inline';
			document.getElementById('MsgRecu').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
		if( list =='MsgEnvoye')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('MsgEnvoye').style.display='none';
			document.getElementById('EnvoiMsg').style.display='inline';
			document.getElementById('MsgRecu').style.display='inline';
			document.getElementById('envoye').style.display='inline';
		}
		
		if( list =='EnvoiMsg')
		{
			document.getElementById('formMsg').style.display='inline';
			document.getElementById('MsgEnvoye').style.display='inline';
			document.getElementById('MsgRecu').style.display='inline';
			document.getElementById('EnvoiMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('envoye').style.display='none';
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
	
	}else{
		echo '<script language="javascript"> alert("Vous avez été désactivé\n Demander à l\'administrateur de vous activer");</script>';
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
<script>
	var j = parseInt($('#iVal').val());
	var i = 0;
	for(var i=0; i<=j; i++)
	{
		$('#valeur' + i).on('change', function(e){
			var v = e.target.value;
			var k = e.target.name;
			
			var idmoreRes = $('#idmoremedLaboResult' + k).val();
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

</body>
</html>