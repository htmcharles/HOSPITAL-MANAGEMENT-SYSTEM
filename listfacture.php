<?php
session_start();

include("connectLangues.php");
include("connect.php");
$annee = date('Y').'-'.date('m').'-'.date('d');
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript> 
<head>
	<title><?php echo 'FACTURES';?></title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
		<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<script src="script.js"></script>
			
			<!------------------------------------->
			
	<link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->
	
	<link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	

	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<script src="myQuery.js"></script>
	<style type="text/css">
		body{
			font-family: ubuntu;
		}
	</style>
	
</head>

<body>
<?php

$id=$_SESSION['id'];

$sqlCash=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");


$comptidCash=$sqlCash->rowCount();


$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $comptidCash!=0)
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
					<form method="post" action="listfacture.php?<?php if(isset($_GET['codeCash'])){ echo 'codeCash='.$_GET['codeCash'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
						<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
						
						<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
						
						<?php
						if($langue == 'francais')
						{
						?>
							<a href="listfacture.php?english=english<?php if(isset($_GET['codeCash'])){ echo '&codeAcc='.$_GET['codeCash'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divAcc'])){ echo '&divAcc='.$_GET['divAcc'];}?>" class="btn"><?php echo getString(30);?></a>
						<?php
						}else{
						?>
							<a href="listfacture.php?francais=francais<?php if(isset($_GET['codeCash'])){ echo '&codeAcc='.$_GET['codeCash'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divAcc'])){ echo '&divAcc='.$_GET['divAcc'];}?>" class="btn"><?php echo getString(29);?></a>
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
	<div style="text-align:center;margin:20px;">
	
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
		<a href="patients1.php?receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="patientsClinbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Reception';?>
		</a>
	
		<a href="patients1_hosp.php?cashHosp=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="patientsHospbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Hospitalisation';?>
		</a>

		<a href="dettesList.php?codeCash=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Dettes';?>
		</a>
		
	<?php
	}
	?>
	</div>
<?php
}
?>

<div class="account-container" style="width:95%;margin:auto; text-align:center;">

<div id='cssmenu' style="text-align:center">

<ul>
	<li style="width:50%;"><a href="patients1.php?caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="<?php echo 'Patients';?>"><i class="fa fa-wheelchair fa-1x fa-fw"></i><?php echo 'Patients';?></a></li>

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


<ul style="margin-top:20px; margin-bottom:20px; background:none;border:none;">
	
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
</div>

	<div style="text-align:center;margin-top:50px;margin-bottom:30px;">
		
		<span onclick="ShowBills('Clinic')" class="btn-large" id="clinicbill" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;<?php if(isset($_GET['divBill'])){ echo 'display:none;';}?>">
			<i class="fa fa-eye fa-lg fa-fw"></i> <?php echo 'Factures Clinic';?>
		</span>

		<span onclick="ShowBills('Hosp')" class="btn-large-inversed" id="hospbill" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;<?php if(isset($_GET['divBillHosp'])){ echo 'display:none;';}?>">
			<i class="fa fa-eye fa-lg fa-fw"></i> <?php echo 'Factures Hôpital';?>
		</span>

	</div>



	<div id="divclinicbills" style="<?php if(!isset($_GET['divBill'])){ echo 'display:none;';}?>">
		
		<b><h3><?php echo 'Liste des factures de la Clinic';?></h3></b>
		
		<form class="ajax" action="search.php" method="get">
			<p>
				<table align="center">
					<tr>
						<td>
							<label for="d"><?php echo 'Rechercher par date';?></label>
							<input type="text" name="d" id="d" onclick="ShowSearch('bydate')"/>
						</td>
						
						<td>
							<label for="n"><?php echo 'Rechercher par nom';?></label>
							<input type="text" name="n" id="n" onclick="ShowSearch('byname')"/>
						</td>
						
						<td>
							<label for="s"><?php echo 'Rechercher par S/N';?></label>
							<input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
						</td>
						
						<td>
							<label for="b"><?php echo 'Rechercher par N° Facture';?></label>
							<input type="text" name="b" id="b" onclick="ShowSearch('bybn')"/>
						</td>
					</tr>
				</table>
			</p>
		</form>
		
		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsDate"></div>

		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsName"></div>
		
		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsBN"></div>

		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#d').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_billsaccount1.php?date=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCash=<?php echo $_GET['codeCash'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'd='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsDate').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#n').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_billsaccount1.php?name=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCash=<?php echo $_GET['codeCash'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'n='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsName').html(data); // affichage des résultats dans le bloc
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
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_billsaccount1.php?sn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCash=<?php echo $_GET['codeCash'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
		  $('#b').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_billsaccount1.php?bn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCash=<?php echo $_GET['codeCash'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'b='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsBN').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
	
	<?php
	if(isset($_GET['divBill']))
	{
		$resultatsBills=$connexion->prepare('SELECT *FROM bills b WHERE b.id_bill=:idBill');
		$resultatsBills->execute(array(
		'idBill'=>$_GET['idbill']
		));
	
		$resultatsBills->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptBill=$resultatsBills->rowCount();
		

		if($comptBill!=0)
		{
		?>
		<table class="tablesorter" cellspacing="0">
				
				<thead>
					<tr>
						<th style="width:12%"><?php echo getString(71);?></th>
						<th style="width:8%"><?php echo getString(166);?></th>
						<th style="width:20%"><?php echo getString(89);?></th>
						<th style="width:10%"><?php echo getString(76);?></th>
						<th style="width:10%"><?php echo 'Actions';?></th>
					</tr>
				</thead>
				
				<tbody>
				<?php
				
				while($ligneBill=$resultatsBills->fetch())//on recupere la liste des éléments
				{
					$getIdDebt=$connexion->prepare('SELECT * FROM bills b WHERE b.numero=:numero AND b.detteDone IS NULL AND b.dette IS NOT NULL');
					$getIdDebt->execute(array(
						'numero'=>$ligneBill->numero
					));
					
					$getIdDebt->setFetchMode(PDO::FETCH_OBJ);
					
					$idDebtCount = $getIdDebt->rowCount();
					
					$totalDettes=NULL;
					
					while($ligneIdDebt=$getIdDebt->fetch())
					{
						$detteRestante=$ligneIdDebt->dette;
						
						if($ligneIdDebt->detteDone != 1)
						{
							$totalDettes=$totalDettes + $detteRestante;
						}
					}
				?>
					<tr style="text-align:center;<?php if($ligneBill->status==0){?>background:rgba(255,255,0, 0.3)<?php ;}?>">
						<td><?php echo $ligneBill->datebill;?></td>
						
						<td>
							<a href="bills.php?num=<?php echo $ligneBill->numero;?>&idbill=<?php echo $ligneBill->id_bill;?>&cashier=<?php if(isset($_SESSION['codeR'])){ echo $_SESSION['codeR'];}else{ if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}};?>&datefacture=<?php echo $ligneBill->datebill;?>&idbill=<?php echo $ligneBill->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligneBill->numbill;?></a>
						</td>
						<td>
							<?php
							
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
							$resultPatient->execute(array(
								'operation'=>$ligneBill->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								$snPa = $lignePatient->numero;
								
								echo $fullname.' ('.$snPa.')';
							}
							
							if($totalDettes!=NULL)
							{
								?>
								<br/>
								<span style="text-align:right;font-weight:700;color:gray;">Total debts</span>
								<a class="btn" href="dettesList.php?num=<?php echo $ligneBill->numero;?>&divDette=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="color:red;"><?php echo $totalDettes;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
								</a>
								<?php
							}
							?>
						</td>
						
						<td>
						<?php
							if($ligneBill->nomassurance !="")
								echo $ligneBill->nomassurance.' '.$ligneBill->billpercent.'%';
						?>
						</td>
						
						<td>
							<a href="formRembourser.php?num=<?php echo $ligneBill->numero;?>&numbill=<?php echo $ligneBill->numbill;?>&cashier=<?php if(isset($_SESSION['codeR'])){ echo $_SESSION['codeR'];}else{ if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}};?>&datefacture=<?php echo $ligneBill->datebill;?>&idbill=<?php echo $ligneBill->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" style="display:none;"><?php echo 'Rembourser';?></a>
						</td>
					</tr>
				<?php
					
				}
				?>
				</tbody>
			</table>
		<?php
		}else{
		?>
			<table class="tablesorter" cellspacing="0">
				
				<thead>
					<tr>
						<th style="text-align:center"><?php echo getString(169);?></th>
					</tr>
				</thead>
				
			</table>
		<?php
		}
		?>
		<br/>

	<?php
	}
	?>
		
		<?php
		if(!isset($_GET['divAcc']))
		{
			
			$resultats=$connexion->prepare('SELECT *FROM bills b WHERE b.status=0 AND b.numero!="NULL" AND DATE(b.datebill)=:dateconsu ORDER BY b.numbill DESC') ;
			$resultats->execute(['dateconsu'=>$annee]);
			$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptBill=$resultats->rowCount();
			
		?>
		<div style="overflow:auto;height:1000px;background-color:none;margin-top:10px;">
		<?php
		if($comptBill!=0)
		{
		?>
		<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th style="width:6%">N°</th>
					<th style="width:12%"><?php echo getString(71);?></th>
					<th style="width:8%"><?php echo getString(166);?></th>
					<th style="width:20%"><?php echo getString(89);?></th>
					<th style="width:10%"><?php echo getString(76);?></th>
					<th style="width:10%"><?php echo 'Actions';?></th>
				</tr>
			</thead>
			
			<tbody>
			<?php
			try
			{
				$i = 1;
				while($ligne=$resultats->fetch())//on recupere la liste des éléments
				{
					$getIdDebt=$connexion->prepare('SELECT * FROM bills b WHERE b.numero=:numero AND b.detteDone IS NULL AND b.dette IS NOT NULL');
					$getIdDebt->execute(array(
						'numero'=>$ligne->numero
					));
					
					$getIdDebt->setFetchMode(PDO::FETCH_OBJ);
					
					$idDebtCount = $getIdDebt->rowCount();
					
					$totalDettes=NULL;
					
					while($ligneIdDebt=$getIdDebt->fetch())
					{
						$detteRestante=$ligneIdDebt->dette;
						
						if($ligneIdDebt->detteDone != 1)
						{
							$totalDettes=$totalDettes + $detteRestante;
						}
					}
					
					?>
					<tr style="text-align:center;<?php if($ligne->status==0){?>background:rgba(255,255,0, 0.3)<?php ;}?>">
						<td><?= $i;?></td>
						<td><?php echo $ligne->datebill;?></td>
						
						<td>
							<a href="bills.php?num=<?php echo $ligne->numero;?>&idbill=<?php echo $ligne->id_bill;?>&cashier=<?php if(isset($_SESSION['codeR'])){ echo $_SESSION['codeR'];}else{ if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}};?>&datefacture=<?php echo $ligne->datebill;?>&idbill=<?php echo $ligne->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligne->numbill;?></a>
						</td>
						<td>
							<?php
							
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
							$resultPatient->execute(array(
								'operation'=>$ligne->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								$snPa = $lignePatient->numero;
								
								echo $fullname.' ('.$snPa.')';
							}
							
							if($totalDettes!=NULL)
							{
								?>
								<br/>
								<span style="text-align:right;font-weight:700;color:gray;">Total debts</span>
								<a class="btn" href="dettesList.php?num=<?php echo $ligne->numero;?>&divDette=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="color:red;"><?php echo $totalDettes;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
								</a>
								<?php
							}
							?>
						</td>
						
						<td>
						<?php
							if($ligne->nomassurance !="")
								echo $ligne->nomassurance.' '.$ligne->billpercent.'%';
						?>
						</td>
						
						<td>
							<a href="formRembourser.php?num=<?php echo $ligne->numero;?>&numbill=<?php echo $ligne->numbill;?>&cashier=<?php if(isset($_SESSION['codeR'])){ echo $_SESSION['codeR'];}else{ if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}};?>&datefacture=<?php echo $ligne->datebill;?>&idbill=<?php echo $ligne->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" style="display:none;"><?php echo 'Rembourser';?></a>
						</td>
					</tr>
			<?php
					$i++;
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
		<?php
			}else{
			?>
				<table class="tablesorter" cellspacing="0">
					
					<thead>
						<tr>
							<th style="text-align:center"><?php echo getString(169);?></th>
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
	
	
	<div id="divhospbills" style="<?php if(!isset($_GET['divBillHosp'])){ echo 'display:none;';}?>">
		
		<b><h3><?php echo 'Liste des factures de l\'hôpital';?></h3></b>
		
		
		<form class="ajax" action="search.php" method="get">
			<p>
				<table align="center">
					<tr>
						<td>
							<label for="dh"><?php echo 'Rechercher par date';?></label>
							<input type="text" name="dh" id="dh" onclick="ShowSearch('bydateh')"/>
						</td>
						
						<td>
							<label for="nh"><?php echo 'Rechercher par nom';?></label>
							<input type="text" name="nh" id="nh" onclick="ShowSearch('bynameh')"/>
						</td>
						
						<td>
							<label for="sh"><?php echo 'Rechercher par S/N';?></label>
							<input type="text" name="sh" id="sh" onclick="ShowSearch('bysnh')"/>
						</td>
						
						<td>
							<label for="bh"><?php echo 'Rechercher par N° Facture';?></label>
							<input type="text" name="bh" id="bh" onclick="ShowSearch('bybnh')"/>
						</td>
					</tr>
				</table>
			</p>
		</form>
		
		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsDateH"></div>

		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsNameH"></div>
		
		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSNH"></div>

		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsBNH"></div>

		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#dh').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_factureshospedit.php?cai=ok&datehosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'dh='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsDateH').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#nh').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_factureshospedit.php?cai=ok&namehosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'nh='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsNameH').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#sh').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_factureshospedit.php?cai=ok&snhosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'sh='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsSNH').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#bh').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_factureshospedit.php?cai=ok&bnhosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'bh='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsBNH').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<?php
		
		if(isset($_GET['divBillHosp']))
		{
			
			$resultatshosp=$connexion->prepare("SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:id_hosp") ;
			$resultatshosp->execute(array(
			'id_hosp'=>$_GET['id_hosp']
			));

			$resultatshosp->setFetchMode(PDO::FETCH_OBJ);
			
			$comptBillHosp=$resultatshosp->rowCount();
		

			if($comptBillHosp!=0)
			{
			?>
			<table class="tablesorter" cellspacing="0">
					<thead>
						<tr>
							<th style="width:12%"><?php echo getString(71).' Sortie';?></th>
							<th style="width:8%"><?php echo getString(166);?></th>
							<th style="width:20%"><?php echo getString(89);?></th>
							<th style="width:10%"><?php echo getString(76);?></th>
							<th style="width:10%" colspan=2><?php echo 'Actions';?></th>
						</tr>
					</thead>

					<tbody>
						<?php
						try
						{
							while($ligne=$resultatshosp->fetch())
							{
								?>
								<tr style="text-align:center;<?php if($ligne->statusPaHosp==0){?>background:rgba(255,255,0, 0.3)<?php ;}?>">
									<td><?php echo $ligne->dateSortie.' '.$ligne->heureSortie;?></td>

									<td>
										<a href="billhosp.php?manager=<?php echo $_SESSION['codeCash'];?>&num=<?php echo $ligne->numero;?>&idhosp=<?php echo $ligne->id_hosp;?>&numroom=<?php echo $ligne->numroomPa;?>&numlit=<?php echo $ligne->numlitPa;?>&idassu=<?php echo $ligne->id_assuHosp;?>&nomassurance=<?php echo $ligne->nomassuranceHosp;?>&billpercent=<?php echo $ligne->insupercent_hosp;?>&datefacture=<?php echo $ligne->dateSortie;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligne->id_factureHosp;?></a>
									</td>
									<?php
									
									$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
									$resultPatient->execute(array(
										'operation'=>$ligne->numero
									));
									
									$resultPatient->setFetchMode(PDO::FETCH_OBJ);
									
									$comptFiche=$resultPatient->rowCount();
									
									if($lignePatient=$resultPatient->fetch())
									{
										$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
										$snPa = $lignePatient->numero;
										
										echo '<td>'.$fullname.' ('.$snPa.')</td>';
									}else{
										echo '<td></td>';
									}
									
									?>

									<td>
										<?php
										if($ligne->nomassuranceHosp !="")
											echo $ligne->nomassuranceHosp.' '.$ligne->insupercent_hosp.'%';
										?>
									</td>

									<td>


									<td>

									</td>
								</tr>
								<?php
							}
							$resultatshosp->closeCursor();
							
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
			}else{
			?>
				<table class="tablesorter" cellspacing="0">
					
					<thead>
						<tr>
							<th style="text-align:center"><?php echo getString(169);?></th>
						</tr>
					</thead>
					
				</table>
			<?php
			}
			?>
			<br/>
		<?php
		}
		?>

		
		<?php
		if(!isset($_GET['divAcc']))
		{
			
			
			$resultatshosp=$connexion->query("SELECT *FROM patients_hosp ph WHERE ph.id_factureHosp IS NOT NULL AND ph.dateSortie!='0000-00-00' AND ph.statusBill=0 ORDER BY ph.id_factureHosp DESC") ;

			$resultatshosp->setFetchMode(PDO::FETCH_OBJ);
			
			$comptBillHosp=$resultatshosp->rowCount();
			
		?>
		<div style="overflow:auto;height:1000px;background-color:none;margin-top:10px">
		<?php
		if($comptBillHosp!=0)
		{
		?>
		<table class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th style="width:12%"><?php echo getString(71).' Sortie';?></th>
								<th style="width:8%"><?php echo getString(166);?></th>
								<th style="width:20%"><?php echo getString(89);?></th>
								<th style="width:10%"><?php echo getString(76);?></th>
								<th style="width:10%" colspan=2><?php echo 'Actions';?></th>
							</tr>
						</thead>

						<tbody>
							<?php
							try
							{
								while($ligne=$resultatshosp->fetch())
								{
									?>
									<tr style="text-align:center;<?php if($ligne->statusPaHosp==0){?>background:rgba(255,255,0, 0.3)<?php ;}?>">
										<td><?php echo $ligne->dateSortie.' '.$ligne->heureSortie;?></td>

										<td>
											<?php 
												$selectidresto=$connexion->prepare('SELECT * FROM restauration WHERE numero=:num');
												$selectidresto->execute(array(
													'num'=>$ligne->numero
												));
												$selectidresto->setFetchMode(PDO::FETCH_OBJ);
												$ligneresto=$selectidresto->fetch();

												$selectidtds=$connexion->prepare('SELECT * FROM tour_de_salle WHERE numero=:num');
												$selectidtds->execute(array(
													'num'=>$ligne->numero
												));
												$selectidtds->setFetchMode(PDO::FETCH_OBJ);
												$lignetds=$selectidtds->fetch();

												$comptselectresto = $selectidresto->rowCount();
												$comptselecttds = $selectidtds->rowCount();

												if ($comptselectresto!=0 AND $comptselecttds) {
											?>
												<a href="billhosp.php?num=<?php echo $ligne->numero;?>&idhosp=<?php echo $ligne->id_hosp;?>&numroom=<?php echo $ligne->numroomPa;?>&numlit=<?php echo $ligne->numlitPa;?>&manager=<?php echo $_SESSION['codeCash'];?>&idassu=<?php echo $ligne->id_assuHosp;?>&nomassurance=<?php echo $ligne->nomassuranceHosp;?>&billpercent=<?php echo $ligne->insupercent_hosp;?>&datefacture=<?php echo $ligne->dateSortie;?>&idresto=<?php echo $ligneresto->id_resto;?>&idtourdesalle=<?php echo $lignetds->id_tour_de_salle?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligne->id_factureHosp;?></a>
											<?php
												}else{
											?>
											<a href="billhosp.php?num=<?php echo $ligne->numero;?>&idhosp=<?php echo $ligne->id_hosp;?>&numroom=<?php echo $ligne->numroomPa;?>&numlit=<?php echo $ligne->numlitPa;?>&manager=<?php echo $_SESSION['codeCash'];?>&idassu=<?php echo $ligne->id_assuHosp;?>&nomassurance=<?php echo $ligne->nomassuranceHosp;?>&billpercent=<?php echo $ligne->insupercent_hosp;?>&datefacture=<?php echo $ligne->dateSortie;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligne->id_factureHosp;?></a>
											<?php
												}
											?>
											
										</td>
										<?php
										
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
										$resultPatient->execute(array(
											'operation'=>$ligne->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);
										
										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())
										{
											$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
											$snPa = $lignePatient->numero;
											
											echo '<td>'.$fullname.' ('.$snPa.')</td>';
										}else{
											echo '<td></td>';
										}
										
										?>

										<td>
											<?php
											if($ligne->nomassuranceHosp !="")
												echo $ligne->nomassuranceHosp.' '.$ligne->insupercent_hosp.'%';
											?>
										</td>

										<td>

										</td>

										<td>

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
		<?php
			}else{
			?>
				<table class="tablesorter" cellspacing="0">
					
					<thead>
						<tr>
							<th style="text-align:center"><?php echo getString(169);?></th>
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


function ShowBills(bills)
{
	if(bills =='Clinic')
	{
		document.getElementById('divclinicbills').style.display='inline';
		document.getElementById('divhospbills').style.display='none';
		document.getElementById('hospbill').style.display='inline';
		document.getElementById('clinicbill').style.display='none';
	}

	if(bills =='Hosp')
	{
		document.getElementById('divhospbills').style.display='inline';
		document.getElementById('divclinicbills').style.display='none';
		document.getElementById('clinicbill').style.display='inline';
		document.getElementById('hospbill').style.display='none';
	}

}


function ShowSearch(search)
{
	if(search =='bydate')
	{
		document.getElementById('resultsDate').style.display='inline';
		document.getElementById('resultsName').style.display='none';
		document.getElementById('resultsSN').style.display='none';
		document.getElementById('resultsBN').style.display='none';
	}
	
	if(search =='byname')
	{
		document.getElementById('resultsDate').style.display='none';
		document.getElementById('resultsName').style.display='inline';
		document.getElementById('resultsSN').style.display='none';
		document.getElementById('resultsBN').style.display='none';
	}
	
	if(search =='bysn')
	{
		document.getElementById('resultsDate').style.display='none';
		document.getElementById('resultsName').style.display='none';
		document.getElementById('resultsSN').style.display='inline';
		document.getElementById('resultsBN').style.display='none';
	}
	
	if(search =='bybn')
	{
		document.getElementById('resultsDate').style.display='none';
		document.getElementById('resultsName').style.display='none';
		document.getElementById('resultsSN').style.display='none';
		document.getElementById('resultsBN').style.display='inline';
	}

	if(search =='bydateh')
	{
		document.getElementById('resultsDateH').style.display='inline';
		document.getElementById('resultsNameH').style.display='none';
		document.getElementById('resultsSNH').style.display='none';
		document.getElementById('resultsBNH').style.display='none';
	}
	
	if(search =='bynameh')
	{
		document.getElementById('resultsDateH').style.display='none';
		document.getElementById('resultsNameH').style.display='inline';
		document.getElementById('resultsSNH').style.display='none';
		document.getElementById('resultsBNH').style.display='none';
	}
	
	if(search =='bysnh')
	{
		document.getElementById('resultsDateH').style.display='none';
		document.getElementById('resultsNameH').style.display='none';
		document.getElementById('resultsSNH').style.display='inline';
		document.getElementById('resultsBNH').style.display='none';
	}
	
	if(search =='bybnh')
	{
		document.getElementById('resultsDateH').style.display='none';
		document.getElementById('resultsNameH').style.display='none';
		document.getElementById('resultsSNH').style.display='none';
		document.getElementById('resultsBNH').style.display='inline';
	}
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
	
		$pass = $_POST['Pass'];
		$iduti = $_SESSION['id'];
		
		$resultats=$connexion->prepare('UPDATE utilisateurs SET password=:pass WHERE id_u=:modifierIduti');
		
		$resultats->execute(array(
		'pass'=>$pass,
		'modifierIduti'=>$iduti
		));
		
		echo '<script type="text/javascript"> alert("Your password have been changed\nYour new password is : '.$pass.'");</script>';
		
	}

?>

</div>

</body>

</html>