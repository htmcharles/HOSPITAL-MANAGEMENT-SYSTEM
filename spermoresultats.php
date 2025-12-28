<?php 
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


	$annee = date('d').'-'.date('M').'-'.date('Y');

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

?>

<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<img src="images/logo_large.png" class="brand" />	
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
				<li class="">			
					<form method="post" action="spermoresultats.php?num=<?php echo $_GET['num'];?>&labo=<?php echo $_SESSION['id'];?>&facture=ok<?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idmedLab'])){ echo '&idmedLab='.$_GET['idmedLab'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="spermoresultats.php?english=english<?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idmedLab'])){ echo '&idmedLab='.$_GET['idmedLab'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="spermoresultats.php?francais=francais<?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idmedLab'])){ echo '&idmedLab='.$_GET['idmedLab'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['idassurance'])){ echo '&idassurance='.$_GET['idassurance'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['updateidmoremedLabo'])){ echo '&updateidmoremedLabo=ok';}?>" class="btn"><?php echo getString(29);?></a>
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

	<form method="post" action="traitement_spermoresultats.php?labo=<?php echo $_SESSION['id'];?>&idassu=<?php echo $_GET['idassu'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&num=<?php echo $_GET['num'];?>&idmed=<?php echo $_GET['idmed'];?>&idmedLab=<?php echo $_GET['idmedLab'];?>&presta=<?php echo $_GET['presta'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data">
		
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

					?>
						<input type="text" name="presta" id="presta" value="<?php echo $_GET['presta'];?>" readonly="readonly"/>
						
						<input type="hidden" name="idmedLab" id="idmedLab" value="<?php echo $_GET['idmedLab'];?>"/>
					
				</td>
			
			</tr>	   

		</table>
		
		<div id="divSpermo" style="margin:40px auto 0; text-align:center;background:#eee;width:60%;">

			<table align="center" style="margin:20px auto; display:inline;">
				<tr>
					<td style="padding:5px;text-align:center;">
						EXAMEN MACROSCOPIQUES
					</td>
				</tr>
				
				<tr>
					<td style="padding:5px;text-align:center;">
						<table align="center" style="margin:20px auto; display:inline;">
										
							<tr>
								<td style="padding:15px;text-align:right;">
									Volume
								</td>
								<td style="text-align:center;">
									<input style="margin:0" type="text" name="volume" value="" placeholder="Entrez volume ici..."/>
								</td>
							</tr>
							
							<tr>
								<td style="padding:15px;text-align:right;">
									Densité
								</td>
								<td style="text-align:center;">
									<input style="margin:0" type="text" name="densite" value="" placeholder="Entrez densité ici..."/>
								</td>
							</tr>
							
							<tr>
								<td style="padding:15px;text-align:right;">
									Viscosité
								</td>
								<td style="text-align:center;">
									<input style="margin:0" type="text" name="viscosite" value="" placeholder="Entrez viscosité ici..."/>
								</td>
							</tr>
							
							<tr>
								<td style="padding:15px;text-align:right;">
									PH
								</td>
								<td style="text-align:center;">
									<input style="margin:0" type="text" name="ph" value="" placeholder="Entrez PH ici..."/>
								</td>
							</tr>
							
							<tr>
								<td style="padding:15px;text-align:right;">
									Aspect
								</td>
								<td style="text-align:center;">
									<input style="margin:0" type="text" name="aspect" value="" placeholder="Entrez aspect ici..."/>
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
									<input style='margin:0' type='text' name='examdirect' value='' placeholder='Entrez examen direct ici...'/>
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
												<input style='margin:0;width:150px;' type='text' name='zeroheureafter' value=''/>
											</td>
										</tr>		
										<tr>
											<td style='text-align:right;'>
												1h après emission
											</td>
											
											<td style='text-align:left;'>
												<input style='margin:0;width:150px;' type='text' name='uneheureafter' value=''/>
											</td>
										</tr>		
										<tr>
											<td style='text-align:right;'>
												2h après emission
											</td>
											
											<td style="text-align:left;">
												<input style='margin:0;width:150px;' type='text' name='deuxheureafter' value=''/>
											</td>
										</tr>		
										<tr>
											<td style='text-align:right;'>
												3h après emission
											</td>
											
											<td style='text-align:left;'>
												<input style='margin:0;width:150px;' type='text' name='troisheureafter' value=''/>
											</td>
										</tr>		
										<tr>
											<td style='text-align:right;'>
												4h après emission
											</td>
											
											<td style='text-align:left;'>
												<input style='margin:0;width:150px;' type='text' name='quatreheureafter' value=''/>
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
												<input style='margin:0;width:200px;' type='text' name='numeration' value='' placeholder='Entrez numeration ici...'/>
											</td>
											
											<td style='border-right:none'>
												V.N
											</td>
											
											<td style='padding:5px;'>
												<input style='margin:0;width:150px;' type='text' name='vn' value='' placeholder='.......................................'/>
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
												<input style='margin:0;width:150px;' type='text' name='formtypik' value=''/>
											</td>
										</tr>		
										<tr>
											<td style='text-align:right;'>
												Forme atypique
											</td>
											
											<td style='text-align:left;'>
												<input style='margin:0;width:150px;' type='text' name='formatypik' value=''/>
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
									<input style='margin:0' type='text' name='autre' value=''/>
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
									<input style='margin:0' type='text' name='conclusion' value='' placeholder='Entrez la conclusion ici...'/>
								</td>
							</tr>
							
						</table>
					</td>
				</tr>
				
			</table>
		
		</div>
		
		
		<div style="margin-top:20px; text-align:center;">
			<button type="submit" class="btn-large" name="addspermobtn" id="addspermobtn" style="display:none;">Envoyer</button>
		</div>
		
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
	
		}else{
			document.getElementById('divViewExam').style.display='none';
			document.getElementById('addbtn').style.display='none';
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

</body>
</html>