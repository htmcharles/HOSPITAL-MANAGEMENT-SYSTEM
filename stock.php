<?php
session_start();

include("connectLangues.php");
include("connect.php");
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title>AFFICHE Stock Kepper</title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
	<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<script src="script.js"></script>
			
			<!------------------------------------>
			
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

$id=$_SESSION['id'];

$sqlA=$connexion->query("SELECT *FROM auditors a WHERE a.id_u='$id'");
$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");


$comptidA=$sqlA->rowCount();
$comptidM=$sqlM->rowCount();


$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND ($comptidM!=0 OR $comptidA!=0))
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
					<form method="post" action="stock.php?<?php if(isset($_GET['codestock'])){ echo 'codestock='.$_GET['codestock'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="stock.php?english=english<?php if(isset($_GET['codestock'])){ echo '&codestock='.$_GET['codestock'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divSTO'])){ echo '&divSTO='.$_GET['divSTO'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="stock.php?francais=francais<?php if(isset($_GET['codestock'])){ echo '&codestock='.$_GET['codestock'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divSTO'])){ echo '&divSTO='.$_GET['divSTO'];}?>" class="btn"><?php echo getString(29);?></a>
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



<div class="account-container" style="width:90%; text-align:center;">

<?php
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

		<div id="divMenuUser" style="display:none;">
		
			<a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(87);?></a>
			
			<a onclick="ShowList('Liste')" id="listOn" class="btn-large" style="display:inline;"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(55);?></a>
			
			<span onclick="ShowList('ListeNon')" id="listOff" class="btn-large" style="display:none;"><i class="fa fa-eye-slash fa-lg fa-fw"></i> <?php echo getString(56);?></span>
		
		</div>

	
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
	
	<div style="display:none;" id="divItem">

		<a href="items.php<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn btn-success btn-large" name="item" id="item">Add item</a>
		
		<a href="items.php?showitems=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="ShowItem" class="btn btn-success btn-large">Show items</a>


	</div>


		<div style="display:none;" id="divListe" align="center">

			<a href="patients1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(20);?></a>

			<a href="medecins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(19);?></a>
			
			<a href="infirmiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(21);?></a>
		
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

if($comptidA!=0)
{
?>
<div id='cssmenu' style="text-align:center" class="menu">

<ul>
	<li style="width:50%;"><a href="report.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Report"><i class="fa fa-file fa-lg fa-fw"></i> Report</a></li>

	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>

</ul>

<ul style="margin-top:20px; background:none;border:none;">

</ul>
	
	<div style="display:none; margin-bottom:20px;" id="divMenuMsg">

		<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
		
		<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
		
		<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

	</div>
	
</div>


<?php 
}
?>
<h2><?php echo getString(279);?>s</h2>
		
<form class="ajax" action="search.php" method="get">
	<p>
		<table align="center">
			<tr>
				<td>
					<label for="q"><?php echo getString(80);?></label>
					<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
				</td>
				
				<td>
					<label for="s">Search by s/n</label>
					<input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
				</td>
			</tr>
		</table>
	</p>
</form>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

<script type="text/javascript">
$(document).ready( function() {
  // détection de la saisie dans le champ de recherche
  $('#q').keyup( function(){
    $field = $(this);
    $('#results').html(''); // on vide les resultats
    $('#ajax-loader').remove(); // on retire le loader
 
    // on commence à traiter à partir du 2ème caractère saisie
    if( $field.val().length > 0 )
    {
      // on envoie la valeur recherché en GET au fichier de traitement
      $.ajax({
  	type : 'GET', // envoi des données en GET ou POST
	url : 'traitement_stock1.php?name=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
	data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
	beforeSend : function() { // traitements JS à faire AVANT l'envoi
		$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
	},
	success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
		$('#ajax-loader').remove(); // on enleve le loader
		$('#results').html(data); // affichage des résultats dans le bloc
	}
      });
    }		
  });
});
</script>

<script type="text/javascript">
$(document).ready( function() {
  // d?ction de la saisie dans le champ de recherche
  $('#s').keyup( function(){
    $field = $(this);
    $('#resultsSN').html(''); // on vide les resultats
    $('#ajax-loader').remove(); // on retire le loader
 
    // on commence ?raiter ?artir du 2? caract? saisie
    if( $field.val().length > 0 )
    {
      // on envoie la valeur recherch?n GET au fichier de traitement
      $.ajax({
  	type : 'GET', // envoi des donn? en GET ou POST
	url : 'traitement_stock1.php?sn=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
	data : 's='+$(this).val() , // donn? ?nvoyer en  GET ou POST
	beforeSend : function() { // traitements JS ?aire AVANT l'envoi
		$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
	},
	success : function(data){ // traitements JS ?aire APRES le retour d'ajax-search.php
		$('#ajax-loader').remove(); // on enleve le loader
		$('#resultsSN').html(data); // affichage des r?ltats dans le bloc
	}
      });
    }		
  });
});
</script>

	<?php 
	if(isset($_GET['divSTO']))
	{
		
		/*-----------Requête pour auditeur-----------*/
		
		if($comptidA!=0)
		{
			$resultatsA=$connexion->prepare('SELECT *FROM utilisateurs u, stockkeeper l WHERE u.id_u=l.id_u AND l.id_u=:idSTO AND u.id_u=:idSTO');
			$resultatsA->execute(array(
			'idSTO'=>$_GET['iduti']	
			))or die( print_r($connexion->errorInfo()));
			
			$resultatsA->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
		}

		/*-----------Requête pour coordinateur-----------*/
		
		if($comptidM!=0)
		{
			$resultatsM=$connexion->prepare('SELECT *FROM utilisateurs u, stockkeeper l WHERE u.id_u=l.id_u AND l.id_u=:idSTO AND u.id_u=:idSTO');
			$resultatsM->execute(array(
			'idSTO'=>$_GET['iduti']	
			))or die( print_r($connexion->errorInfo()));
		

			$resultatsM->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
		}
		
		
		if($comptidM!=0)
		{
	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			
			<thead> 
				<tr>
					<th>S/N</th> 
					<th><?php echo getString(9);?></th>
					<th><?php echo getString(10);?></th>
					<th><?php echo getString(11);?></th>
					<th><?php echo getString(16);?></th>
					<th><?php echo getString(14);?></th>
					<th><?php echo getString(15);?></th>
					<th>DATE D'AFFECTION</th>
					<th>ACTIONS</th>
					<th>STATUS</th>
			</thead>
			<tbody>
				<?php
				while($ligneM=$resultatsM->fetch())
				{
				?>
				<tr style="text-align:center;<?php if($ligneM->status==0){?>background:rgb(253,168,170)<?php ;}?>"> 
					<td><?php echo $ligneM->codestock;?></td>
					<td><?php echo $ligneM->nom_u;?></td>
					<td><?php echo $ligneM->prenom_u;?></td>
					<td>
					<?php 
					if($ligneM->sexe=="M")
					{
						echo "Male";
					}else{
						if($ligneM->sexe=="F")
						echo "Female";
					}
					?>
					</td>
					<td><?php echo $ligneM->e_mail;?></td>
					<td>
					<?php
							
						$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
						$resultAdresse->execute(array(
						'idProv'=>$ligneM->province,
						'idDist'=>$ligneM->district,
						'idSect'=>$ligneM->secteur
						));
								
						$resultAdresse->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptAdress=$resultAdresse->rowCount();
						
						if($ligneAdresse=$resultAdresse->fetch())
						{
							if($ligneAdresse->id_province == $ligneM->province)
							{
								$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
								
							}
						}elseif($ligneM->autreadresse!=""){
								$adresse=$ligneM->autreadresse;
						}else{
							$adresse="";
			
						}
							
						echo $adresse;

					?>
					</td>
					<td><?php echo $ligneM->telephone;?></td>
					<td><?php echo $ligneM->dateaffectionstock;?></td>
					
					<?php
					if($ligneM->status==1)
					{
					?>
					<td>
						<a href="utilisateurs.php?iduti=<?php echo $ligneM->id_u?>"><input type="image" src="images/icn_edit.png" title="Modifier"/></a>
					</td>

					<td>
						<a href="traitement_utilisateurs.php?idutiDesactif=<?php echo $ligneM->id_u?>&code=<?php echo $ligneM->codestock;?>&divSTO=ok<?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>">Activé<input type="image" style="border-radius:100px;" src="images/onBlue.png" name="desactifbtn"/></a>

					</td>
					<?php
					}else{
						if($ligneM->status==0)
						{
					?>
						<td></td>
						<td>
							<a href="traitement_utilisateurs.php?idutiActif=<?php echo $ligneM->id_u?>&code=<?php echo $ligneM->codestock;?>&divSTO=ok<?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>">Desactivé<input type="image" style="border-radius:100px;" src="images/offBlue.png" name="actifbtn"/></a>
						
						</td>
					<?php
						}
					}
					?>
				</tr>
				<?php
				}
					$resultatsM->closeCursor();
				?>
		
			</tbody> 
	
		</table>
		<?php
		}
		
		if($comptidA!=0)
		{
	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			
			<thead> 
				<tr>
					<th>S/N</th> 
					<th><?php echo getString(9);?></th>
					<th><?php echo getString(10);?></th>
					<th><?php echo getString(11);?></th>
					<th><?php echo getString(16);?></th>
					<th><?php echo getString(14);?></th>
					<th><?php echo getString(15);?></th>
					<th>DATE D'AFFECTION</th>
					<th>ACTIONS</th>
			</thead>
			<tbody>
				<?php
				while($ligneA=$resultatsA->fetch())//on récupère la liste des éléments
				{
				?>
				<tr style="text-align:center;<?php if($ligneA->status==0){?>background:rgb(253,168,170)<?php ;}?>"> 
					<td><?php echo $ligneA->codestock;?></td>
					<td><?php echo $ligneA->nom_u;?></td>
					<td><?php echo $ligneA->prenom_u;?></td>
					<td>
					<?php 
					if($ligneA->sexe=="M")
					{
						echo "Male";
					}else{
						if($ligneA->sexe=="F")
						echo "Female";
					}
					?>
					</td>
					<td><?php echo $ligneA->e_mail;?></td>
					<td>
					<?php
							
						$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
						$resultAdresse->execute(array(
						'idProv'=>$ligneA->province,
						'idDist'=>$ligneA->district,
						'idSect'=>$ligneA->secteur
						));
								
						$resultAdresse->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptAdress=$resultAdresse->rowCount();
						
						if($ligneAdresse=$resultAdresse->fetch())
						{
							if($ligneAdresse->id_province == $ligneA->province)
							{
								$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
								
								echo $adresse;
							}
						}else{
							echo '';
						}

					?>
					</td>
					<td><?php echo $ligneA->telephone;?></td>
					<td><?php echo $ligneA->dateaffectionstock;?></td>

					<td>
						<a href="report.php?lab=<?php echo $ligneA->id_u;?>&audit=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="<?php if($ligneA->status==0){ echo "display:none";}?>" class="btn"><?php echo getString(94);?></a>
					</td>
					
				</tr>
				<?php
				}
					$resultatsA->closeCursor();
				?>
		
			</tbody> 
	
		</table>
		<?php
		}
		?>
	
		<a href="stock.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Show Laboratory technicians" class="btn-large">Show Laboratory technicians</a>
	<?php
	}
	?>
	
	
	<?php 
	if(!isset($_GET['divSTO']))
	{
	?>
	<table class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th>S/N</th> 
				<th>NOM</th>
				<th>PRENOM</th>
				<th>SEXE</th>
				<th>E_MAIL</th>
				<th>ADRESSE</th>
				<th>TELEPHONE</th>
				<th>ANNEE D'AFFECTION</th>
				<th>ACTIONS</th>
				<?php 
				if($comptidA==0)
				{
				?>
				<th>STATUS</th>
				<?php 
				}
				?>
			</tr> 
		</thead> 
	<tbody>
	<?php
	try
	{

		if($comptidA != 0)
		{
	
			function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
			{
				$pagination = '';
				$link = preg_replace('`%([^d])`', '%%$1', $link);
				if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
				if ( $nb_pages > 1 ) {
			 
					// Lien Précédent
					if ( $current_page > 1 )
					{
						
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&report=ok&english='.$_GET['english'].'" title="Previous page" style="border-radius: 24px 4px 4px 14px;">Prev</a>';
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&report=ok&francais='.$_GET['francais'].'" title="Previous page" style="border-radius: 24px 4px 4px 14px;">Prev</a>';
							}else{
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&report=ok" title="Previous page" style="border-radius: 24px 4px 4px 14px;">Prev</a>';				
							}
						}
					
					}else{
						$pagination .= '';
					}
					// Lien(s) début
					for ( $i=1 ; $i<=$firstlast ; $i++ ) 
					{
						$pagination .= ' ';
						
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok&english='.$_GET['english'].'">'.$i.'</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok&francais='.$_GET['francais'].'">'.$i.'</a>';
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok">'.$i.'</a>';				
							}
						}
					}
			 
					// ... après pages début ?
					if ( ($current_page-$around) > $firstlast+1 )
						$pagination .= '<span class="current">&hellip;</span>';
			 
					// On boucle autour de la page courante
					$start = ($current_page-$around)>$firstlast ? $current_page-$around : $firstlast+1;
					$end = ($current_page+$around)<=($nb_pages-$firstlast) ? $current_page+$around : $nb_pages-$firstlast;
					for ( $i=$start ; $i<=$end ; $i++ ) {
						$pagination .= ' ';
						if ( $i==$current_page )
						{
							$pagination .= '<span class="current">'.$i.'</span>';
						}else{
							
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								$pagination .= '<a href="'.sprintf($link, $i).'&report=ok&english='.$_GET['english'].'">'.$i.'</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= '<a href="'.sprintf($link, $i).'&report=ok&francais='.$_GET['francais'].'">'.$i.'</a>';
								}else{
									$pagination .= '<a href="'.sprintf($link, $i).'&report=ok">'.$i.'</a>';				
								}
							}
						}
					}
			 
					// ... avant page nb_pages ?
					if ( ($current_page+$around) < $nb_pages-$firstlast )
						$pagination .= '<span class="current">&hellip;</span>';
			 
				// Lien(s) fin
					$start = $nb_pages-$firstlast+1;
					if( $start <= $firstlast ) $start = $firstlast+1;
					for ( $i=$start ; $i<=$nb_pages ; $i++ ) 
					{
						$pagination .= ' ';
						
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];	
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok&english='.$_GET['english'].'">'.$i.'</a>';
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];	
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok&francais='.$_GET['francais'].'">'.$i.'</a>';
							}else{
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&report=ok">'.$i.'</a>';				
							}
						}
					}
			 
					// Lien suivant
					if ( $current_page < $nb_pages )
					{
						
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&report=ok&english='.$_GET['english'].'" title="Next page" style="border-radius: 4px 24px 14px 4px;">Next</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&report=ok&francais='.$_GET['francais'].'" title="Next page" style="border-radius: 4px 24px 14px 4px;">Next</a>';
						
							}else{
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&report=ok" title="Next page" style="border-radius: 4px 24px 14px 4px;">Next</a>';
										
							}
						}
					}else{
						$pagination .= '';
					}
				}
				return $pagination;
			}
			
		}else{
		
			function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
			{
				$pagination = '';
				$link = preg_replace('`%([^d])`', '%%$1', $link);
				if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
				if ( $nb_pages > 1 ) {
			 
					// Lien Précédent
					if ( $current_page > 1 )
					{
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&english='.$_GET['english'].'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];									
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&francais='.$_GET['francais'].'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

							}else{
								
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

							}
						}
						
					}else{
						$pagination .= '';
					}
			 
					// Lien(s) début
					for ( $i=1 ; $i<=$firstlast ; $i++ ) {
						$pagination .= ' ';
						
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].'">'.$i.'</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].'">'.$i.'</a>';
						
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'">'.$i.'</a>';
						
							}
						}
						
					}
			 
					// ... après pages début ?
					if ( ($current_page-$around) > $firstlast+1 )
						$pagination .= '<span class="current">&hellip;</span>';
			 
					// On boucle autour de la page courante
					$start = ($current_page-$around)>$firstlast ? $current_page-$around : $firstlast+1;
					$end = ($current_page+$around)<=($nb_pages-$firstlast) ? $current_page+$around : $nb_pages-$firstlast;
					for ( $i=$start ; $i<=$end ; $i++ ) {
						$pagination .= ' ';
						if ( $i==$current_page )
						{
							$pagination .= '<span class="current">'.$i.'</span>';
						}else{
						
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								$pagination .= '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].'">'.$i.'</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].'">'.$i.'</a>';
								}else{
									
									$pagination .= '<a href="'.sprintf($link, $i).'">'.$i.'</a>';				
								}
							}
						}
					}
			 
					// ... avant page nb_pages ?
					if ( ($current_page+$around) < $nb_pages-$firstlast )
						$pagination .= '<span class="current">&hellip;</span>';
			 
				// Lien(s) fin
					$start = $nb_pages-$firstlast+1;
					if( $start <= $firstlast ) $start = $firstlast+1;
					
					for ( $i=$start ; $i<=$nb_pages ; $i++ )
					{
						$pagination .= ' ';
						
						if(isset($_GET['english']))
						{
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].'">'.$i.'</a>';
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].'">'.$i.'</a>';	
								
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'">'.$i.'</a>';				
							}
						}
						
					}
			 
					// Lien suivant
					if ( $current_page < $nb_pages )
					{
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							
							$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&english='.$_GET['english'].'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&francais='.$_GET['francais'].'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
							}else{
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
										
							}
						}
						
					}else{
						$pagination .= '';
					}
				}
				return $pagination;
			}
		}
				

		// Numero de page (1 par défaut)
			if( isset($_GET['page']) && is_numeric($_GET['page']) )
					$page = $_GET['page'];
				else
					$page = 1;
				 
				// Nombre d'info par page
				$pagination =5;
				 
				// Numero du 1er enregistrement à lire
				$limit_start = ($page - 1) * $pagination;

			
			/*-----------Requête pour coordinateur-----------*/
			
		$resultats=$connexion->query('SELECT *FROM utilisateurs u, stockkeeper l WHERE u.id_u=l.id_u ORDER BY u.nom_u LIMIT '.$limit_start.', '.$pagination.'') or die( print_r($connexion->errorInfo()));

		$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			while($ligne=$resultats->fetch())//on recupere la liste des éléments
			{
		?>
			<tr style="text-align:center;<?php if($ligne->status==0){?>background:rgb(253,168,170)<?php ;}?>"> 
				<td><?php echo $ligne->codestock;?></td>
				<td><?php echo $ligne->nom_u;?></td>
				<td><?php echo $ligne->prenom_u;?></td>
				<td>
				<?php 
				if($ligne->sexe=="M")
				{
					echo "Male";
				}else{
					if($ligne->sexe=="F")
					echo "Female";
				}
				?>
				</td>
				<td><?php echo $ligne->e_mail;?></td>
				<td>
				<?php
						
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
							
							echo $adresse;
						}
					}else{
						echo '';
					}

				?>
				</td>
				<td><?php echo $ligne->telephone;?></td>
				<td><?php echo $ligne->dateaffectionstock;?></td>

				<?php
				if($ligne->status==1 AND $comptidA==0)
				{
				?>
				<td>
					<a href="utilisateurs.php?iduti=<?php echo $ligne->id_u?>"><input type="image" src="images/icn_edit.png" title="Modifier"/></a>
				</td>

				<td>
					<a href="traitement_utilisateurs.php?idutiDesactif=<?php echo $ligne->id_u?>&code=<?php echo $ligne->codestock;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>">Activé<input type="image" style="border-radius:100px;" src="images/onBlue.png" name="desactifbtn" title="Actif"/></a>
				</td>
				<?php
				}else{
					if($ligne->status==0 AND $comptidA==0)
					{
				?>
					<td></td>
					<td>
						<a href="traitement_utilisateurs.php?idutiActif=<?php echo $ligne->id_u?>&code=<?php echo $ligne->codestock;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>">Desactivé<input type="image" style="border-radius:100px;" src="images/offBlue.png" name="actifbtn" title="Passif"/></a>
					
					</td>
				<?php
					}else{					
						if($comptidA!=0)
						{
				?>
						<td>
							<a href="report.php?lab=<?php echo $ligne->id_u;?>&audit=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="<?php if($ligne->status==0){ echo "display:none";}?>" class="btn"><?php echo getString(94);?></a>
						</td>
				<?php
						}
					}
				}
				?>
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
	
	<tr>
		<td>
		<?php

		$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM utilisateurs u, stockkeeper l WHERE u.id_u=l.id_u ORDER BY u.nom_u');
			$nb_total=$nb_total->fetch();
			$nb_total = $nb_total['nb_total'];
			// Pagination
			$nb_pages = ceil($nb_total / $pagination);
				   // Affichage
			  echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
		?>
		</td>
	</tr>
	
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
	}
	
	if(search =='bysn')
	{
		document.getElementById('results').style.display='none';
		document.getElementById('resultsSN').style.display='inline';
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
		))or die( print_r($connexion->errorInfo()));
			
		echo '<script type="text/javascript"> alert("Your password have been changed\nYour new password is : '.$pass.'");</script>';
		
	}


?>

</body>

</html>