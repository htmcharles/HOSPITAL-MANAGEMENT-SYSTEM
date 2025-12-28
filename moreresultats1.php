<?php 
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


	$annee = date('d').'-'.date('M').'-'.date('Y');

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
	
	echo '<script type="text/javascript">document.location.href="moreresultats.php?labo='.$_GET['labo'].'&num='.$_GET['num'].'&idmedLab='.$_GET['idmedLab'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&presta='.$_GET['presta'].'&idassu='.$_GET['idassu'].'&previewprint=ok'.$langue.''.$updateidmoremedLabo.'";</script>';
	
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
					<form method="post" action="moreresultats.php?num=<?php echo $_GET['num'];?>&labo=<?php echo $_SESSION['id'];?>&facture=ok<?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idmedLab'])){ echo '&idmedLab='.$_GET['idmedLab'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="moreresultats.php?english=english<?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idmedLab'])){ echo '&idmedLab='.$_GET['idmedLab'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="moreresultats.php?francais=francais<?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idmedLab'])){ echo '&idmedLab='.$_GET['idmedLab'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?>" class="btn"><?php echo getString(29);?></a>
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

	<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:10px auto 20px auto; padding: 10px; width:80%;">  
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

	<form method="post" action="addpresta_resultats.php?labo=<?php echo $_SESSION['id'];?>&idassu=<?php echo $_GET['idassu'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&num=<?php echo $_GET['num'];?>&idmed=<?php echo $_GET['idmed'];?>&idmedLab=<?php echo $_GET['idmedLab'];?>&presta=<?php echo $_GET['presta'];?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data">
		
		<table align="center">
		   
			<tr>
				<td style="text-align:center"><label><?php echo 'Nom du medecin'; ?></label></td>
				
				<td style="text-align:center"><label><?php echo 'Examen'; ?></label></td>
				
			</tr>	   

			<tr>				
				<td>
					<?php
					
					$resultatsMedecins=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m, grades g, servicemed sm WHERE u.id_u=m.id_u AND sm.codemedecin=m.codemedecin AND m.id_u=:medId ORDER BY u.nom_u');
					$resultatsMedecins->execute(array(
						'medId'=>$_GET['idmed']
					));
					
					$resultatsMedecins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				
					if($ligneMedecins=$resultatsMedecins->fetch())//on recupere la liste des éléments
					{
					?>
						<input type="text" name="medecin" id="medecin" value="<?php echo $ligneMedecins->nom_u.' '.$ligneMedecins->prenom_u;?>" readonly="readonly"/>
						
						<input type="hidden" name="idmedecin" id="idmedecin" value="<?php echo $ligneMedecins->id_u;?>"/>
					<?php
					}
					?>
				</td>
						
				<td>
					<?php
					$idassuLabo=$_GET['idassu'];
					
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
					$assuCount = $comptAssuConsu->rowCount();
					
					for($i=1;$i<=$assuCount;$i++)
					{
						
						$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
						$getAssuConsu->execute(array(
						'idassu'=>$idassuLabo
						));
						
						$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

						if($ligneNomAssu=$getAssuConsu->fetch())
						{
							$presta_assuLabo='prestations_'.$ligneNomAssu->nomassurance;
						}
					}

/* 
					$resultatsTypeConsu=$connexion->prepare('SELECT *FROM '.$presta_assuLabo.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$resultatsTypeConsu->execute(array(
						'idPresta'=>$_GET['idtypeconsu']
					));
					
					if($ligneTypeConsu=$resultatsTypeConsu->fetch(PDO::FETCH_OBJ))
					{ */
					?>
						<input type="text" name="presta" id="presta" value="<?php echo $_GET['presta'];?>" readonly="readonly"/>
						
						<input type="hidden" name="idmedLab" id="idmedLab" value="<?php echo $_GET['idmedLab'];?>"/>
					<?php
					// }
					?>
					
				</td>
			
			</tr>	   

		</table>
		
		<?php
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');
		$resultMedLabo->execute(array(
		'num'=>$_GET['num'],
		'idmedLab'=>$_GET['idmedLab']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();

		
		if($comptMedLabo!=0)
		{
		?>
				
		<table style="margin:20px auto;">
			<tr>
				<td>
				<?php
				if(!isset($_GET['previewprint']))
				{
				?>
					<a style="padding:10px 40px;" href="<?php echo 'moreresultats.php?labo='.$_SESSION['id'].'&num='.$_GET['num'].'&idmedLab='.$_GET['idmedLab'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&presta='.$_GET['presta'].'&idassu='.$_GET['idassu'].'&previewprint=ok'?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="showpreviewbtn" id="showpreviewbtn"><?php echo 'Finish'; ?></a>
				<?php
				}else{
					if(isset($_GET['previewprint']))
					{
				?>
					<a style="padding:10px 40px;" href="<?php echo 'moreresultats.php?labo='.$_SESSION['id'].'&num='.$_GET['num'].'&idmedLab='.$_GET['idmedLab'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&presta='.$_GET['presta'].'&idassu='.$_GET['idassu'].'&back=ok'?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="showbackbtn" id="showbackbtn"><?php echo getString(221) ?></a>
				<?php
					}
				}
				?>
				</td>
			</tr>
		</table>
		<?php
		}else{
			if(isset($_GET['previewprint']))
			{
		?>
			<table style="margin:20px auto;">
				<tr>
					<td>
						<a style="padding:10px 40px;" href="<?php echo 'moreresultats.php?labo='.$_SESSION['id'].'&num='.$_GET['num'].'&idmedLab='.$_GET['idmedLab'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&presta='.$_GET['presta'].'&idassu='.$_GET['idassu'].'&back=ok'?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="showbackbtn" id="showbackbtn"><?php echo getString(221) ?></a>
					</td>
				</tr>
			</table>
		<?php
			}
		}
		?>
		
		<?php
		if(!isset($_GET['previewprint']))
		{
		?>
		<div id="divCatego" style="margin:40px auto 0; text-align:center">

			<table id="showService" align="center" style="margin:20px auto; display:inline;">
				<tr>
					<td style="padding:5px;text-align:center;">
					
						<select name="souscategorie" id="souscategorie" onchange="ShowAddbtn('souscategorie')">

							<option value='0'><?php echo 'Select categorie here...'; ?></option>
						<?php

						$resultatsSousCatego=$connexion->query('SELECT *FROM souscategopresta scp,categopresta_ins cp WHERE scp.catego_id=12 AND scp.catego_id=cp.id_categopresta ORDER BY scp.nomsouscatego');
							
						$resultatsSousCatego->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
										
						while($ligneSousCatego=$resultatsSousCatego->fetch())//on recupere la liste des éléments
						{
						?>
							<option value="<?php echo $ligneSousCatego->souscatego_id;?>">
								<?php echo $ligneSousCatego->nomsouscatego?>
							</option>
						<?php
						}
						?>
							<option value="spermo"><?php echo 'Spermogramme'; ?></option>
							
						</select>
						
						<script src="jQuery.js"></script>
						<script>
						$(function(){
							$("#souscategorie").change(function(){
						var souscategoChoisi='souscategorie='+$(this).val();
							//alert(souscategoChoisi);
							$.ajax({
								url:"tablepresta_resultats.php?<?php echo 'num='.$num.'&presta='.$_GET['presta'];?>",
								type:"POST",
								data:souscategoChoisi,
								
								success:function(resultat)
								{
									
									// alert(resultat);
									$('#divViewExam').html(resultat);
								}
								});
							});
						});
						</script>

					</td>
				</tr>
			</table>
		
		</div>
		
		<?php
		}
		?>
		<div id="divViewExam" style="overflow:auto;height:600px;display:none;">
		
		
		</div>
	
		<div style="margin-top:20px; text-align:center;">
			<button type="submit" class="btn-large" name="addbtn" id="addbtn" style="display:none;">Ajouter</button>
			
			<button type="submit" class="btn-large" name="addspermobtn" id="addspermobtn" style="display:none;">Envoyer</button>
		</div>
		
	</form>

	
	<?php
	if(isset($_GET['previewprint']))
	{
	?>
		<div id="previewprint">
				
		<?php		
		
		$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');		
		$resultMoreMedLabo->execute(array(
		'num'=>$_GET['num'],
		'idmedLab'=>$_GET['idmedLab']
		));
		
		$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMoreMedLabo=$resultMoreMedLabo->rowCount();


		
		if($comptMoreMedLabo!=0)
		{
		?>
		
			<form method="post" action="traitement_moreresultats.php?num=<?php echo $_GET['num'];?>&labo=<?php echo $_SESSION['id'];?>&idmedLab=<?php echo $_GET['idmedLab'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&presta=<?php echo $_GET['presta'];?>&idassu=<?php echo $_GET['idassu'];?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data">
			
				<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
					<thead> 
						<tr>
							<th style="width:20%;"><?php echo getString(99);?></th>
							<th><?php echo 'Résultats';?></th>
							<th><?php echo 'Valeur';?></th>
							<th><?php echo 'Min';?></th>
							<th><?php echo 'Max'; ?></th>
							<th><?php echo 'Actions';?></th>
						</tr> 
					</thead> 


					<tbody>
						<?php
						$i=0;
						
						while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
						{
						?>
						<tr style="text-align:center;">
						
							<td>
								<input type="hidden" id="idmoremedLaboResult<?php echo $i;?>" name="idmoremedLaboResult[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneMoreMedLabo->id_moremedlabo;?>"/>
								
								<input type="hidden" id="idprestationExa<?php echo $i;?>" name="idprestationExa[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneMoreMedLabo->id_prestationExa;?>"/>
							
							<?php

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
							
							<td>
								<input type="text" id="moreresultats" name="moreresultats[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;width:130px" value="<?php if(isset($_GET['updateidmoremedLabo'])){ echo $ligneMoreMedLabo->autreresultats;}else{ echo '';}?>" placeholder="Taper les résultats ici..." required/> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
							</td>
								
							<td>
							<?php
							
							$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE id_examen=:idexamen ORDER BY valeur');
							$resultValeur->execute(array(
							'idexamen'=>$ligneMoreMedLabo->id_prestationExa
							));
							
							$resultValeur->setFetchMode(PDO::FETCH_OBJ);

							$comptValeur=$resultValeur->rowCount();
							
							if($comptValeur!=0)
							{
							?>
								<select name="<?php echo $i;?>" id="valeur<?php echo $i;?>" style="width:200px;" onchange="ShowMinMax()">
					
									<option value='----' id="nominmax">
									<?php echo 'Select here...';?>
									</option>
									<?php
									$j = 0;
									while($ligneValeur=$resultValeur->fetch())
									{
									?>
									<option value='<?php echo $ligneValeur->valeur;?>' id='minmax<?php echo $j;?>' <?php if(isset($_GET['updateidmoremedLabo']) AND $ligneValeur->valeur==$ligneMoreMedLabo->valeurLab){ echo "selected='selected'";}else{ echo '';}?>>
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
							
								if(isset($_GET['updateidmoremedLabo']))
								{								
									echo "<input type='text' name='".$i."' id='valeur'".$i."' style='width:100px;' value='".$ligneMoreMedLabo->valeurLab."'/>";
								}else{ 
									echo "<input type='text' name='".$i."' id='valeur'".$i."' style='width:100px;' value=''/>";
								}
							
							}
							?>
							</td>
							
							<td>
								<input type="text" id="min<?php echo $i;?>" name="min[]" style="width:80px" value="<?php if(isset($_GET['updateidmoremedLabo'])){ echo $ligneMoreMedLabo->minresultats;}else{ echo '';}?>"/>
							</td>
							
							<td>
								<input type="text" id="max<?php echo $i;?>" name="max[]" style="width:80px" value="<?php if(isset($_GET['updateidmoremedLabo'])){ echo $ligneMoreMedLabo->maxresultats;}else{ echo '';}?>"/>
							</td>
							
							<td style="display:none">
								<input type="file" name="moreautreresult[]" id="moreautreresult" style="border:1px solid #eee; height:40px; padding 2px; width:230px;"/>
								
								<br/>
								
								<?php
								if(isset($_GET['updateidmoremedLabo']) AND $ligneMoreMedLabo->resultats!="")
								{
								?>
									<a href="<?php echo $ligneMoreMedLabo->resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
									
								<?php
								}
								?>
								<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
							
							</td>
							
							<td>
								<a href="moreresultats.php?deleteMoreMedLabo=<?php echo $ligneMoreMedLabo->id_moremedlabo;?>&labo=<?php echo $_GET['labo'];?>&num=<?php echo $_GET['num'];?>&idmedLab=<?php echo $_GET['idmedLab'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&presta=<?php echo $_GET['presta'];?>&idassu=<?php echo $_GET['idassu'];?>&previewprint=ok<?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>

							</td>
						</tr>
						<?php
							$i++;
						}
						?>
						<tr>
							<td colspan=8 style="text-align:center">									
								<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="moreresultatbtn" class="btn-large">
								<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(68) ?>
								</button>
								
							</td>
							
						</tr>						
					</tbody>
				</table>
		
			</form>
			<input type='hidden' id='iVal' value='<?php echo $i-1;?>'/>
		<?php
		}
		?>
		
		<?php		
		
		$resultSpermoMedLabo=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medlabo=:idmedLab ORDER BY sml.id_spermomedlabo');		
		$resultSpermoMedLabo->execute(array(
		'num'=>$_GET['num'],
		'idmedLab'=>$_GET['idmedLab']
		));
		
		$resultSpermoMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptSpermoMedLabo=$resultSpermoMedLabo->rowCount();


		
		if($comptSpermoMedLabo!=0)
		{
		?>
		
			<form method="post" action="addpresta_resultats.php?labo=<?php echo $_SESSION['id'];?>&idassu=<?php echo $_GET['idassu'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&num=<?php echo $_GET['num'];?>&idmed=<?php echo $_GET['idmed'];?>&idmedLab=<?php echo $_GET['idmedLab'];?>&presta=<?php echo $_GET['presta'];?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data">
		
			<div id='divSpermo' style='margin:40px auto 0; text-align:center;background:#eee;width:60%;'>
				
				<?php
				if($ligneSpermoMedLabo=$resultSpermoMedLabo->fetch())
				{
				?>
				<table align='center' style='margin:20px auto; display:inline;'>
					<tr>
						<td style='padding:5px;text-align:center;'>
							EXAMEN MACROSCOPIQUES
						</td>
					</tr>
					
					<tr>
						<td style='padding:5px;text-align:center;'>
							<table align='center' style='margin:20px auto; display:inline;'>
											
								<tr>
									<td style='padding:15px;text-align:right;'>
										Volume
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='volume' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->volume;}?>' placeholder='Entrez volume ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Densité
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='densite' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->densite;}?>' placeholder='Entrez densité ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Viscosité
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='viscosite' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->viscosite;}?>' placeholder='Entrez viscosité ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										PH
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='ph' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->ph;}?>' placeholder='Entrez PH ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Aspect
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='aspect' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->aspect;}?>' placeholder='Entrez aspect ici...'/>
									</td>
								</tr>
							</table>
						</td>
					</tr>						
						
					<tr>
						<td style='padding:5px;text-align:center;'>
							EXAMEN MICROSCOPIQUES
						</td>
					</tr>
					
					<tr>
						<td style='padding:5px;text-align:center;'>
							<table align='center' style='margin:20px auto; display:inline;'>
											
								<tr>
									<td style='padding:15px;text-align:right;'>
										Examen direct
									</td>
									<td style='text-align:left;'>
										<input style='margin:0' type='text' name='examdirect' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->examdirect;}?>' placeholder='Entrez examen direct ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Mobilité
									</td>
									
									<td style='text-align:center;width:50px;'>
									
										<table class='tablesorter'style='background:#fff;'>
													
											<tr>
												<td style='text-align:right;'>
													0h après emission
												</td>
												
												<td style='text-align:left	;'>
													<input style='margin:0;width:150px;' type='text' name='zeroheureafter' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->zeroheureafter;}?>'/>
												</td>
											</tr>		
											<tr>
												<td style='text-align:right;'>
													1h après emission
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='uneheureafter' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->uneheureafter;}?>'/>
												</td>
											</tr>		
											<tr>
												<td style='text-align:right;'>
													2h après emission
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='deuxheureafter' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->deuxheureafter;}?>'/>
												</td>
											</tr>		
											<tr>
												<td style='text-align:right;'>
													3h après emission
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='troisheureafter' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->troisheureafter;}?>'/>
												</td>
											</tr>		
											<tr>
												<td style='text-align:right;'>
													4h après emission
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='quatreheureafter' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->quatreheureafter;}?>'/>
												</td>
											</tr>
										</table>
										
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Numeration
									</td>
									
									<td>
									
										<table class='tablesorter tablesorter3' style='background:#eee;'>
													
											<tr>
												<td style='text-align:left;'>
													<input style='margin:0;width:200px;' type='text' name='numeration' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->numeration;}?>' placeholder='Entrez numeration ici...'/>
												</td>
												
												<td style='border-right:none'>
													V.N
												</td>
												
												<td style='padding:5px;'>
													<input style='margin:0;width:150px;' type='text' name='vn' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->vn;}?>' placeholder='.......................................'/>
												</td>
											</tr>		
											
										</table>
									</td>
									
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Spermocytogramme
									</td>
									<td style='text-align:center;'>
										
										<table class='tablesorter' style='background:#fff;'>
													
											<tr>
												<td style='text-align:right;'>
													Forme typique
												</td>
												
												<td style='text-align:left	;'>
													<input style='margin:0;width:150px;' type='text' name='formtypik' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->formtypik;}?>'/> %
												</td>
											</tr>		
											<tr>
												<td style='text-align:right;'>
													Forme atypique
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='formatypik' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->formatypik;}?>'/> %
												</td>
											</tr>
										</table>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Autre
									</td>
									<td style='text-align:left;'>
										<input style='margin:0' type='text' name='autre' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->autre;}?>'/>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					
					<tr>
						<td style='padding-top:45px;text-align:center;'>
							<table align='center' style='margin:20px auto; display:inline;'>
											
								<tr>
									<td style='padding:15px;text-align:right;'>
										CONCLUSION
									</td>
									<td style='text-align:left;'>
										<input style='margin:0' type='text' name='conclusion' value='<?php if(isset($_GET['updateidspermomedLabo'])){ echo $ligneSpermoMedLabo->conclusion;}?>' placeholder='Entrez la conclusion ici...'/>
									</td>
								</tr>
								
							</table>
						</td>
					</tr>
					
				</table>
			
				<?php
				}
				?>
			
			<div style="margin-top:20px; text-align:center;">
			
				<button type="submit" class="btn-large" name="updatespermobtn" id="updatespermobtn" style="display:inline;">Envoyer modification</button>
			</div>
			
			</div>
			
			</form>
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