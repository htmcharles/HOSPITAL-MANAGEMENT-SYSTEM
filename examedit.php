<?php
session_start();

include("connect.php");
include("connectLangues.php");


$annee = date('Y').'-'.date('m').'-'.date('d');

if(isset($_GET['deleteValeurExam']))
{

	 if(isset($_GET['page']))
	 {
		$pageTable='&page='.$_GET['page'].'#tableExam';
	 }else{
		$pageTable="#tableExam";
	 }
	 
	$id_valeur = $_GET['deleteValeurExam'];
	
	$deleteValeurExam=$connexion->prepare('DELETE FROM valeurs_lab WHERE id_valeur=:id_valeur');
	
	$deleteValeurExam->execute(array(
	'id_valeur'=>$id_valeur
	
	))or die( print_r($connexion->errorInfo()));
		
	echo '<script type="text/javascript"> alert("Vous venez de supprimer une valeur");</script>';
	
	echo '<script type="text/javascript">document.location.href="examedit.php?'.$pageTable.'";</script>';
	
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
	<title><?php echo 'Exams edit';?></title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
	<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<script src="script.js"></script>
			
			<!------------------------------------>
			
	<link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />
	
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
				<li class="">			
					<form method="post" action="examedit.php?<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="examedit.php?english=english<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="examedit.php?francais=francais<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?>" class="btn"><?php echo getString(29);?></a>
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

<div class="account-container" style="width:80%; text-align:center;">

	<div id='cssmenu' style="text-align:center">

		<ul style="margin-top:20px;background:none;border:none;">

		<?php

		$id=$_SESSION['id'];
		$annee = date('Y').'-'.date('m').'-'.date('d');

		$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");

		$comptidL=$sqlL->rowCount();

		if($comptidL!=0)
		{
		?>
			<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
			
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
<br/>

<div id="formExam">
	
	<form method="post" action="insertionSQL.php" style="margin:auto;">
	
	<?php
	/* 
	for($q=0;$q<5;$q++)
	{
	?>
		<input type="text" name="groupe[]" id="groupe" placeholder="Groupe" style="margin-top:10px;margin-bottom:0;height:20px;width:20%;"/>
		
		<input type="text" name="localisation[]" id="localisation" placeholder="Localisation" style="margin-top:10px;margin-bottom:0;height:20px;width:15%;"/>
		
		<input type="text" name="produit[]" id="produit" placeholder="Inserer Produit" style="margin-top:10px;margin-bottom:0;height:20px;width:25%;"/>
		
		<br/>
	<?php
	} 
	*/
	
	/* 
	for($q=0;$q<5;$q++)
	{
	?>	
		<p style="margin:auto;">
		<select style="margin:auto;" name="checkSousCatego[]" class="chosen-select" id="checkSousCatego<?php echo $q?>">
			<option value=''></option>
		<?php

		$resultatsSousCatego=$connexion->query('SELECT *FROM souscategopresta scp WHERE scp.catego_id=12 ORDER BY scp.nomsouscatego ASC');
		
		$resultatsSousCatego->setFetchMode(PDO::FETCH_OBJ);
		
		while($ligneSousCatego=$resultatsSousCatego->fetch())
		{
		?>
			<option value='<?php echo $ligneSousCatego->souscatego_id;?>'><?php echo $ligneSousCatego->nomsouscatego;?></option>
		<?php
		}$resultatsSousCatego->closeCursor();
		?>
		</select>
		</p>
		
		<input type="text" name="produit[]" id="produit" placeholder="Inserer le Produit" style="margin-top:10px;margin-bottom:0;height:20px;width:15%;"/>
		
		<input type="text" name="mesure[]" id="mesure" placeholder="Unité mesure" style="margin-top:10px;margin-bottom:0;height:20px;width:100px;display:none;"/>
		
		<input type="text" name="prixassuMUSA[]" id="prixassuMUSA" placeholder="Prix MUSA" style="margin-top:10px;margin-bottom:0;height:20px;width:100px;"/>
		
		<input type="text" name="prixassuMMI[]" id="prixassuMMI" placeholder="Prix MMI" style="margin-top:10px;margin-bottom:0;height:20px;width:100px;"/>
		
		<input type="text" name="prixassuRAMA[]" id="prixassuRAMA" placeholder="Prix RAMA" style="margin-top:10px;margin-bottom:0;height:20px;width:100px;"/>
		
		<input type="text" name="prixassuAutre[]" id="prixassuAutre" placeholder="Prix Autres" style="margin-top:10px;margin-bottom:0;height:20px;width:100px;"/>
		
		<input type="text" name="prixCCO[]" id="prixCCO" placeholder="Prix CCO" style="margin-top:10px;margin-bottom:0;height:20px;width:100px;"/>
		
		<br/>
	<?php
	} */
	?>
	
	<table style="background:#ddd;" class="printPreview tablesorter3" cellspacing="0" align="center"> 
		<thead>
			<tr>
				<th style="border-right: 1px solid #bbb;text-align:center;">Examen</th>
				<th style="border-right: 1px solid #bbb;text-align:center;">Categorie</th>
				<th style="border-right: 1px solid #bbb;text-align:center;">Valeur</th>
				<th style="border-right: 1px solid #bbb;text-align:center;">Min</th>
				<th style="border-right: 1px solid #bbb;text-align:center;">Max</th>
				<th style="border-right: 1px solid #bbb;text-align:center;">Mesure</th>
			</tr>
		</thead>
		
		<tbody>
		<?php
		for($q=0;$q<5;$q++)
		{
		?>
			<tr>
				<td style="text-align:center;">		
					<p style="margin:auto;">
					<select style="margin:auto;" name="checkExam[]" class="chosen-select" id="checkExam<?php echo $q?>">
						<option value=''>None</option>
					<?php

					$resultatsExams=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_categopresta=12 ORDER BY p.id_prestation ASC');
					
					$resultatsExams->setFetchMode(PDO::FETCH_OBJ);
					
					while($ligneExam=$resultatsExams->fetch())
					{
					?>
						<option value='<?php echo $ligneExam->nompresta;?>'><?php echo $ligneExam->nompresta;?></option>
					<?php
					}$resultatsExams->closeCursor();
					?>
					</select>
					</p>
				</td>
				
				<td style="text-align:center;">	
					<p style="margin:auto;">
					<select style="margin:auto;" name="checkSousCatego[]" class="chosen-select" id="checkSousCatego<?php echo $q?>">
						<option value=''>None</option>
					<?php

					$resultatsSousCatego=$connexion->query('SELECT *FROM souscategopresta scp WHERE scp.catego_id=12 ORDER BY scp.nomsouscatego ASC');
					
					$resultatsSousCatego->setFetchMode(PDO::FETCH_OBJ);
					
					while($ligneSousCatego=$resultatsSousCatego->fetch())
					{
					?>
						<option value='<?php echo $ligneSousCatego->souscatego_id;?>'><?php echo $ligneSousCatego->nomsouscatego;?></option>
					<?php
					}$resultatsSousCatego->closeCursor();
					?>
					</select>
					</p>					
				</td>
				
				<td style="text-align:center;">		
					<p>
						<input type="text" name="valeur[]" id="valeur" placeholder="Valeur" style="margin:5px;height:20px;width:auto;"/>
					</p>
				</td>
				
				<td style="text-align:center;">
					<p>
						<input type="text" name="min[]" id="min" placeholder="Min" style="margin:5px;height:20px;width:50px;"/>
					</p>
				</td>
				
				<td style="text-align:center;">				
					<p>
						<input type="text" name="max[]" id="max" placeholder="Max" style="margin:5px;height:20px;width:50px;"/>
					</p>
				</td>
				
				<td style="text-align:center;">				
					<p>
						<input type="text" name="mesure[]" id="mesure" placeholder="Mesure" style="margin:5px;height:20px;width:50px;"/>
					</p>
				</td>
			</tr>
			
			<tr>
				<td style="text-align:center;">	
					<input type="text" name="autreExam[]" id="autreExam" placeholder="Autre Examen" style="margin:5px;height:20px;width:auto;"/>		
				</td>
				
				<td style="text-align:center;">	
					<p style="margin:auto;">
					<select style="margin:auto;" name="autreSousCatego[]" class="chosen-select" id="autreSousCatego<?php echo $q?>">
						<option value=''>None</option>
					<?php

					$resultatsSousCatego=$connexion->query('SELECT *FROM souscategopresta scp WHERE scp.catego_id=12 ORDER BY scp.nomsouscatego ASC');
					
					$resultatsSousCatego->setFetchMode(PDO::FETCH_OBJ);
					
					while($ligneSousCatego=$resultatsSousCatego->fetch())
					{
					?>
						<option value='<?php echo $ligneSousCatego->souscatego_id;?>'><?php echo $ligneSousCatego->nomsouscatego;?></option>
					<?php
					}$resultatsSousCatego->closeCursor();
					?>
					</select>
					</p>					
				</td>
				
				<td style="text-align:center;">		
					<p>
						<input type="text" name="autrevaleur[]" id="autrevaleur" placeholder="Autre Valeur" style="margin:5px;height:20px;width:auto;"/>
					</p>
				</td>
				
				<td style="text-align:center;">
					<p>
						<input type="text" name="autremin[]" id="autremin" placeholder="Autre Min" style="margin:5px;height:20px;width:65px;"/>
					</p>
				</td>
				
				<td style="text-align:center;">				
					<p>
						<input type="text" name="autremax[]" id="autremax" placeholder="Autre Max" style="margin:5px;height:20px;width:65px;"/>
					</p>
				</td>
				
				<td style="text-align:center;">				
					<p>
						<input type="text" name="autremesure[]" id="autremesure" placeholder="Autre Mesure" style="margin:5px;height:20px;width:65px;"/>
					</p>
				</td>
			</tr>
			
			<tr>
				<td style="background:#aaa;" colspan=6>
				</td>
			</tr>
			
		<?php
		}
		?>	
		</tbody>
	</table>
		<br/>
		<input type="submit" value="Save" name="confirmExam" id="confirmExam" class="btn"/>
		
		<input type="submit" name="confirmProduct" id="confirmProduct" class="btn" style="display:none;"/>
		
		<input type="submit" name="confirmDiagno" id="confirmDiagno" class="btn" style="display:none;"/>		
	
	</form>
	
</div>

<div style="margin-top:20px;" id="tableExam">
	<h2><?php echo 'Liste des Examens';?></h2>

	<form class="ajax" action="search.php" method="get">
		<p>
			<table align="center">
				<tr>
					<td>
						<label for="q"><?php echo getString(80);?></label>
						<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
					</td>
				</tr>
			</table>
		</p>
	</form>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>


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
	url : 'traitement_examedit.php?name=ok<?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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

	<?php 
	if(isset($_GET['divValeur']))
	{
		
		/*-----------Requête pour coordinateur-----------*/
		
		$resultatsV=$connexion->prepare('SELECT *FROM valeurs_lab WHERE id_valeur=:idvaleur');
		$resultatsV->execute(array(
		'idvaleur'=>$_GET['idvaleur']	
		))or die( print_r($connexion->errorInfo()));
	

		$resultatsV->setFetchMode(PDO::FETCH_OBJ);
		
	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			
			<thead> 
				<tr>
					<th>Exam</th> 
					<th>Valeur</th>
					<th>Min</th>
					<th>Max</th>
					<th>Action</th>
			</thead>
			<tbody>
				<?php
				while($ligneV=$resultatsV->fetch())
				{
				?>
				<tr style="text-align:center;"> 
					<td><?php echo $ligneV->nomexam;?></td>
					<td><?php echo $ligneV->valeur;?></td>
					<td><?php echo $ligneV->min_valeur;?></td>
					<td><?php echo $ligneV->max_valeur;?></td>
					
					<td>
						<a href="examedit.php?deleteValeurExam=<?php echo $ligneV->id_valeur;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}?>" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
												
					</td>
				</tr>
				<?php
				}
					$resultatsV->closeCursor();
				?>
		
			</tbody> 
	
		</table>
		<?php
		/* ?>
	
		<a href="medecins1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Show Doctors" class="btn-large">Show Doctors</a>
	<?php */
	}
	?>
	
	<table class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th>Exam</th> 
				<th>Valeur</th>
				<th>Min</th>
				<th>Max</th>
				<th>Action</th>
			</tr> 
		</thead> 
		<tbody>
		<?php
		try
		{
			function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
			{
				$pagination = '';
				$link = preg_replace('`%([^d])`', '%%$1', $link);
				if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
				if ( $nb_pages > 1 ) {
			 
			 if(isset($_GET['page']))
			 {
				$pageTable='&'.$_GET['page'].'#tableExam';
			 }else{
				$pageTable="";
			 }
					// Lien Précédent
					if ( $current_page > 1 )
					{
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&english='.$_GET['english'].''.$pageTable.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];									
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&francais='.$_GET['francais'].''.$pageTable.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

							}else{
								
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).''.$pageTable.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

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
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$pageTable.'">'.$i.'</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$pageTable.'">'.$i.'</a>';
						
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$pageTable.'">'.$i.'</a>';
						
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
								$pagination .= '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$pageTable.'">'.$i.'</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$pageTable.'">'.$i.'</a>';
								}else{
									
									$pagination .= '<a href="'.sprintf($link, $i).''.$pageTable.'">'.$i.'</a>';				
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
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$pageTable.'">'.$i.'</a>';
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$pageTable.'">'.$i.'</a>';	
								
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$pageTable.'">'.$i.'</a>';				
							}
						}
						
					}
			 
					// Lien suivant
					if ( $current_page < $nb_pages )
					{
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							
							$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&english='.$_GET['english'].''.$pageTable.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&francais='.$_GET['francais'].''.$pageTable.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
							}else{
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).''.$pageTable.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
										
							}
						}
						
					}else{
						$pagination .= '';
					}
				}
				return $pagination;
			}
		// }
			
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
				
			$resultats=$connexion->query('SELECT * FROM valeurs_lab ORDER BY nomexam LIMIT '.$limit_start.', '.$pagination.'') or die( print_r($connexion->errorInfo()));

			$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				while($ligne=$resultats->fetch())//on recupere la liste des éléments
				{
			?>
				<tr style="text-align:center;"> 
					<td><?php echo $ligne->nomexam;?></td>
					<td><?php echo $ligne->valeur;?></td>
					<td><?php echo $ligne->min_valeur;?></td>
					<td><?php echo $ligne->max_valeur;?></td>
					
					<td>
						<a href="examedit.php?deleteValeurExam=<?php echo $ligne->id_valeur;?>" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i></a>
												
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

	<tr>
		<td>
		<?php

		$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM valeurs_lab ORDER BY nomexam');
			$nb_total=$nb_total->fetch();
			$nb_total = $nb_total['nb_total'];
			// Pagination
			$nb_pages = ceil($nb_total / $pagination);
				   // Affichage
			  echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
		?>
		</td>
	</tr>
	
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

</div>

<?php
	
	}else{
		echo '<script language="javascript"> alert("Vous avez été désactivé!!\n Demander à l\'administrateur de vous activer");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}
}else{
	echo '<script language="javascript"> alert("Vous n\'êtes pas connecté");</script>';
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
			
		echo '<script type="text/javascript"> alert("Vous venez de modifier votre mot de passe");</script>';
		
	}
?>

<div> <!-- footer -->
	<footer style="bottom: 0px; width: 100%; text-align: center; background: #fff; border-top: 1px solid #eee; padding: 10px 0px; vertical-align: middle;">
		<p style="margin:0"><span style="color:#a00000">Medical FIle®</span> is a product of <span style="font-style:bold;">Innovate Solutions Ltd</span>. ©2022-<?php echo date('Y');?> All rights reserved.</p>
	</footer>
</div> <!-- /footer -->
	
	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	var q;
	
	for(q=0;q<5;q++)
	{
		$('#idExam'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
		$('#checkExam'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
		$('#checkSousCatego'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
		$('#autreSousCatego'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
	}
	</script>
	
</body>

</html>