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
		
		
		$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($ligne->date_naissance)));
		$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
		$interval = $datetime1->diff($datetime2);
		
		if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
		{
			$an = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
		}
	
	}
	$result->closeCursor();
	
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
	<link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />	
		
	<link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->
	
	<link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
	
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	

	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<script src="myQuery.js"></script>
	
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
				<li>
					<form method="post" action="consultToPhysio.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'].'';}?><?php if(isset($_GET['idconsuKine'])){ echo '&idconsuKine='.$_GET['idconsuKine'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['idmedKine'])){ echo '&idmedKine='.$_GET['idmedKine'];}?><?php if(isset($_GET['idassuKine'])){ echo '&idassuKine='.$_GET['idassuKine'];}?><?php if(isset($_GET['iduM'])){ echo '&iduM='.$_GET['iduM'];}?><?php if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['kinePa'])){ echo '&kinePa='.$_GET['kinePa'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">

					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="consultToPhysio.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'].'';}?><?php if(isset($_GET['idconsuKine'])){ echo '&idconsuKine='.$_GET['idconsuKine'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['idmedKine'])){ echo '&idmedKine='.$_GET['idmedKine'];}?><?php if(isset($_GET['idassuKine'])){ echo '&idassuKine='.$_GET['idassuKine'];}?><?php if(isset($_GET['iduM'])){ echo '&iduM='.$_GET['iduM'];}?><?php if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['kinePa'])){ echo '&kinePa='.$_GET['kinePa'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="consultToPhysio.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'].'';}?><?php if(isset($_GET['idconsuKine'])){ echo '&idconsuKine='.$_GET['idconsuKine'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['idmedKine'])){ echo '&idmedKine='.$_GET['idmedKine'];}?><?php if(isset($_GET['idassuKine'])){ echo '&idassuKine='.$_GET['idassuKine'];}?><?php if(isset($_GET['iduM'])){ echo '&iduM='.$_GET['iduM'];}?><?php if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['kinePa'])){ echo '&kinePa='.$_GET['kinePa'];}?>" class="btn"><?php echo getString(29);?></a>
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
if(isset($_SESSION['codeM']))
{
?>
	<div style="text-align:center;margin-top:20px;margin-bottom:15px;">
		
		<a href="report.php?med=<?php echo $_SESSION['id'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="font-size:20px;height:40px; padding:10px 40px;"><?php echo getString(94);?></a>
		
		<a href="rendezvous1.php?med=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="font-size:20px;height:40px; padding:10px 40px;"><?php echo 'Vos rendez-vous';?></a>
	</div>
<?php
}
?>

<div class="account-container" style="width:90%; text-align:center;">

<?php

$id=$_SESSION['id'];

$sqlD=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");

$comptidD=$sqlD->rowCount();


	if($comptidD!=0)
	{
?>
	<div id='cssmenu' style="text-align:center">

	<ul style="margin-top:20px;background:none;border:none;">

		<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><b><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</b></a></li>
		
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49) ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49) ?></a></li>


	</ul>

	<ul style="margin-top:20px; background:none;border:none;">

		<div style="display:none; margin-bottom:20px;" id="divMenuMsg">

			<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
			
			<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
			
			<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

		</div>
	</ul>
</div>


<?php 
}
		$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
		$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
				
		$assuCount = $comptAssuConsu->rowCount();

		for($i=1;$i<=$assuCount;$i++)
		{
			
			$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
			$getAssuConsu->execute(array(
			'idassu'=>$_GET['idassuKine']
			));
			
			$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

			if($ligneNomAssu=$getAssuConsu->fetch())
			{
				$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
			}
		}
				
				
if(isset($_GET['idconsuKine']))
{
	$modifierIdConsu=$_GET['idconsuKine'];
	

	$medKine=$connexion->prepare('SELECT *FROM med_kine mk WHERE mk.id_consuKine=:idConsu AND mk.numero=:num ORDER BY mk.id_medkine');		
	$medKine->execute(array(
	'idConsu'=>$modifierIdConsu,
	'num'=>$_GET['num']
	));
	
	$medKine->setFetchMode(PDO::FETCH_OBJ);

	$comptMedKine=$medKine->rowCount();

}		
?>
	
	<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto; padding: 10px; width:100%;">
		<tr>
			<td style="font-size:18px; text-align:center; width:33.333%;">
				<span style="font-weight:bold;"><?php echo getString(89) ?> : </span><?php echo $nom_uti.' '.$prenom_uti;?>
			</td>
			
			<td style="font-size:18px; text-align:center; width:33.333%;">
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
			</td>
			
			<td style="font-size:18px; text-align:center; width:33.333%;">
			
				<span style="font-weight:bold;">Age : </span><?php echo $an;?>
			</td>
		</tr>
	</table>
	
	<form method="post" action="traitement_consultToPhysioForm.php?num=<?php echo $_GET['num'];?>&kinePa=ok&idassuKine=<?php echo $_GET['idassuKine'];?>&idmedKine=<?php echo $_GET['idmedKine'];?>&presta=<?php echo $_GET['presta'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idconsuKine=<?php echo $_GET['idconsuKine'];?>&iduM=<?php echo $_GET['iduM'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data">

		<table class="cons-table" style="margin: 20px auto; background: #bbb none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:85%;" id="kineTable" cellpadding=5>
		
			<thead style="background:black none repeat scroll 0% 0% !important; color:white;">
				<tr>
					<th style="text-align:left">
						<span>
						<?php	
						// echo $_GET['presta'];
						
						$resultatsMedecins=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMedecin ORDER BY u.nom_u');
						$resultatsMedecins->execute(array(
						'idMedecin'=>$_GET['iduM']
						));
							
							
						$resultatsMedecins->setFetchMode(PDO::FETCH_OBJ);
						
						$nomDr="";
					
						if($ligneMedecins=$resultatsMedecins->fetch())
						{
							$nomDr = $ligneMedecins->full_name;
						}
						echo $nomDr;
						?>
						</span>
						<span style="text-align:right"> : <?php	echo date('d-M-Y', strtotime($_GET['dateconsu']));?></span>
					</th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
				
				<tr>
					<td style="text-align:center"><label for="kine"><?php echo 'PHYSIOTERAPY'; ?></label></td>								
					<td style="background:#eee; width:1px;"></td>
				</tr>
				
				<tr id="MedKine">	
					<td>
						<select style="margin:auto;" multiple="multiple" name="kine[]" class="chosen-select" id="kine">												
						<?php

						$resultatsPrestaKine=$connexion->query('SELECT *FROM '.$presta_assu.' p, categopresta_ins cp WHERE cp.id_categopresta=p.id_categopresta AND p.id_categopresta=14 ORDER BY p.nompresta ASC');
						
						$resultatsPrestaKine->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						if($ligneCatPrestaKine=$resultatsPrestaKine->fetch())
						{
							echo '<optgroup label="'.$ligneCatPrestaKine->nomcategopresta.'">';

							echo '<option value='.$ligneCatPrestaKine->id_prestation.' onclick="ShowOthersKine(\'kine\')">'.$ligneCatPrestaKine->nompresta.'</option>';							
							
							while($lignePrestaKine=$resultatsPrestaKine->fetch())//on recupere la liste des éléments
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
													
					<td style="background:#eee; width:1px;"></td>
					
				</tr>
				
				<tr>
					<td>					
						<input style="width:40%;margin:0;display:inline" type="text" id="areaAutrekine" name="areaAutrekine" placeholder="<?php echo "Inserer Autre..." ?>"/>
						
						<input style="height:35px; margin:0;visibility:visible;" type = "submit" id="addAutreKine" name="addAutreKine" value="<?php echo getString(266) ?>" class="btn"/>
					</td>
													
					<td style="background:#eee; width:1px;"></td>
					
				</tr>				
				
				<tr>					
					<td style="vertical-align: top;">	
					<?php 
					if($comptMedKine!=0)
					{
					?>	
						<table class="tablesorter" cellspacing="0" style="width:50%;"> 
							<thead> 
								<tr>
									<th><?php echo 'Actes'; ?></th>
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
								$presta=$ligneMedKine->autrePrestaK;
							}
								echo $presta;
							?>
										</td>
										
										<td>
										<?php
										if($ligneMedKine->id_factureMedKine ==0 AND $ligneMedKine->id_uK ==$_SESSION['id'])
										{
										?>
											<button style="width:auto; height:auto; margin-top:10px;" type="submit" name="deleteMedkine[]" value="<?php echo $ligneMedKine->id_medkine;?>" class="btn"><i class="fa fa-trash fa-lg fa-fw"></i></button>
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
					<?php	
					}/* else{
					?>
						<table class="tablesorter" cellspacing="0"> 
						<thead> 
							<tr>
								<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;">Pas de Physiotherapie</th>
							</tr>
						</thead> 		
						</table>
					<?php
					} */
					?>
					</td>
													
					<td style="background:#eee; width:1px;"></td>
					
				</tr>
			</tbody>							
			
			<tfoot style="background:#eee">
				<tr>
					<td colspan=2 style="text-align:center; border-top:none">
						<button style="height:50px; width:300px" type="submit" class="btn-large" name="savebtn"><i class="fa fa-check-square-o fa-lg fa-fw"></i><?php echo 'Finir traitement';?></button>
						
					</td>
				</tr>
			</tfoot>
			
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

?>
</div>
<div> <!-- footer -->
	<footer style="bottom: 0px; width: 100%; text-align: center; background: #fff; border-top: 1px solid #eee; padding: 10px 0px; vertical-align: middle;">
		<p style="margin:0"><span style="color:#a00000">Medical FIle®</span> is a product of <span style="font-style:bold;">Innovate Solutions Ltd</span>. ©2022-<?php echo date('Y');?> All rights reserved.</p>
	</footer>
</div> <!-- /footer -->
	
	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	
		$('#consult').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#soins').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#kine').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#medoc').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
	</script>
	
</body>

</html>					