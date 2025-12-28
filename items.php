<?php
session_start();
include("connect.php");
include("connectLangues.php");

?>

<!doctype html>
<html>
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<meta charset="utf-8"/>
	<title>BILLING</title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
		<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="script.js"></script>
			
			<!------------------------------------>
	
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"/><!--Header-->
	
		<!-------------------calendrier------------------->
	
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>
	
	
	<link href="css/font-awesome.css" rel="stylesheet">
	
	<link href="css/pages/dashboard.css" rel="stylesheet">
	
	
	
			<!------------------------------------>
			
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />		
	
</head>

<body>
<?php

$id=$_SESSION['id'];

$sqlP=$connexion->query("SELECT *FROM patients p WHERE p.id_u='$id'");
$sqlM=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");
$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");
$sqlC=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");

$comptidP=$sqlP->rowCount();
$comptidM=$sqlM->rowCount();
$comptidI=$sqlI->rowCount();
$comptidL=$sqlL->rowCount();
$comptidC=$sqlC->rowCount();




$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $comptidC!=0 AND isset($_GET['num']))
{
	if($status==1)
	{
?>

<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<a class="brand">
				<img src="images/logo_large.png"/>		
			</a>	
			
			<div class="nav-collapse">
				
				<ul class="nav pull-right">
					
					<li class="">						
						<span style="color:black;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
						<a href="deconnect.php" class="btn btn-large" style="color:red !important;height:20px;padding:0px;"><?php echo getString(25);?></a>
						
						<br/>
											
						
						<form method="post" action="utilisateurs.php" onsubmit="return controlFormPassword(this)">
											
						
						<br/><br/>
							<input type="text" name="Pass" id="Pass" placeholder="<?php echo getString(26);?>"/>
							<br/>
							<input type="submit" name="confirmPass" id="confirmPass" class="btn"  value="<?php echo getString(27);?>"/>
						</form>
						
					</li>
				</ul>
				
			</div><!--/.nav-collapse -->
			
			<br/>
			
			<div class="nav-collapse">
				
				<ul class="nav pull-right">
					
					<li class="">						
						
						
						<?php
						
						
							if($langue == 'francais')
							{
								echo '<a href="billing.php?num='.$_GET['num'].'&english=english" class="btn btn-large" style="color:blue !important;height:20px;padding:0px;">'.getString(30).'</a>';
							}else{
							
								echo '<a href="billing.php?num='.$_GET['num'].'&francais=francais" class="btn btn-large" style="color:red !important;height:20px;padding:0px;">'.getString(29).'</a>';
							}
								
												
						?>
						
												
					</li>
				</ul>
				
			</div><!--/.nav-collapse -->	
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div><br><br><br><br><br>

<div class="account-container">

	<div id='cssmenu' style="width:650px">
	<ul>
		<li><a onclick="ShowList('Users')" data-title="G�rer utilisateurs">G�rer utilisateurs</a></li>
		<li><a onclick="ShowList('Msg')" data-title="G�rer Messagerie">G�rer Messagerie</a></li>
	</ul>

	<br/>
			<div style="display:none;" id="divMenuUser">
		
				<a href="utilisateurs.php" class="btn btn-success btn-large">Ajouter Utilisateurs</a>
				
				 <a onclick="ShowList('Liste')" id="listOn" class="btn btn-success btn-large" style="display:inline;">Afficher Utilisateurs</a>
				
				<span onclick="ShowList('ListeNon')" id="listOff" class="btn btn-success btn-large" style="display:none;">Cacher Utilisateurs</span><br/><br/><br/>
			
			</div>
			
			<div style="display:none;" id="divMenuMsg">

				<a href="messages.php?ecrire=ok" class="btn btn-success btn-large" name="newMsg" id="EnvoiMsg">Ecrire un message</a>
				
				<a href="messages.php?recu=ok" id="MsgRecu" class="btn btn-success btn-large" onclick="ShowList('MsgRecu')" >Bo�te de r�c�ption</a>
				
				<a href="messages.php?envoye=ok" id="MsgEnvoye" class="btn btn-success btn-large" onclick="ShowList('MsgEnvoye')" >El�ments envoy�s</a>

			</div>
	</div>		

	<div style="display:none;" id="divListe" align="center">
			
			<a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&listPa=1" class="btn">Afficher Patients</a>
				<a href="medecins1.php?iduser=<?php echo $_SESSION['id'];?>" class="btn">Afficher Medecins</a>
				<a href="infirmiers1.php?iduser=<?php echo $_SESSION['id'];?>" class="btn">Afficher Infirmiers</a>
				<a href="laborantins1.php?iduser=<?php echo $_SESSION['id'];?>" class="btn">Afficher Laborantins</a>
			
	</div>
	<br/>
	
	<div>
	
	<form method="post" action="traitement_items.php" onsubmit="return controlFormItems(this)">

		<table>

		<tr>
		<td>MAXIMA</td><td><input type="text" name="maxima" id="max" value="<?php if(isset($_GET['idcours'])){ echo $maxima;}else{echo '';}?>" placeholder="Tapez le nom du cours"/></td>
		</tr>

		<tr>
		<td>Item</td>
		<td>
		<select name="item">
		<?php

			$resultats=$connexion->query('SELECT *FROM categopresta cp ORDER BY cp.nomcategopresta');
			
			$resultats->setFetchMode(PDO::FETCH_OBJ);
			
			while($ligne=$resultats->fetch())//on recupere la liste des �l�ments
			{
			?>
			<option value='<?php echo $ligne->id_categopresta; ?>' <?pNetadmin/*  if(isset($_GET['idcours']) and $idEnca == $ligne->id_encadreur){echo "selected='selected'";} */?>><?php echo $ligne->nomcategopresta;?></option>
			<?php
			}
			$resultats->closeCursor();
		?>
		</select>
		</td>
		</tr>


		<tr> 
		<td><input type="hidden" name="idopere" value="<?php if(isset($_GET['idcours'])){ echo $site;}else{echo '';}?>"/></td>
		</tr>

		</table>

		<br/><br/>

			<input type="submit" name="savebtn" value="Inserer"/>
			<?php if(isset($_GET['idcours']))
			{
			?>
			<input type="submit" name="updatebtn" value="Modifier"/><?php
			}
			?>
			<input type="reset" value="Effacer Tout" name="resetbtn"/>
			<input type="submit" value="Afficher" name="showbtn"/>

	</form>

	</div>
	
	
</div>
<?php
	
	}else{
		echo '<script language="javascript"> alert("Your count is disabled!!\n Please contact de Admin");</script>';
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
			
		echo '<script type="text/javascript"> alert("Your password have been changed");</script>';
		
	}
?>

</body>
</html>