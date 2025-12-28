<?php
session_start();

include("connectLangues.php");
include("connect.php");




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
	$province=$ligne->province;
	$district=$ligne->district;
	$secteur=$ligne->secteur;
	$profession=$ligne->profession;
	$assuId=$ligne->id_assurance;
	$site=$_GET['num'];
	}
	$result->closeCursor();
	
	

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
					<form method="post" action="patients1_inf.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="patients1_inf.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="patients1_inf.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(29);?></a>
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
if(isset($_SESSION['infhosp']))
{
	$infhosp=$_SESSION['infhosp'];
	
	if($infhosp!=0)
	{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
	}
}
?>

<div class="account-container" style="width:90%; text-align:center;">

<?php

$id=$_SESSION['id'];

$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");

$comptidI=$sqlI->rowCount();


if($comptidI!=0)
{
?>
<div id='cssmenu' style="text-align:center">

<ul style="margin-top:20px;background:none;border:none;">

	<li style="width:50%;"><a href="patients1_hosp.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients hospitalisation"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients hospitalisation</a></li>
		
	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>

</ul>

<ul style="margin-top:20px; background:none;border:none;">

	
		<a href="utilisateurs_hosp.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(88);?></a>
		
	<div style="display:none; margin-bottom:20px;" id="divMenuMsg">
		
		<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
		
		<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
		
		<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

	</div>

</ul>

</div>


<?php 
}

if(!isset($_GET['num']))
{
?>
			<h2><?php echo getString(20);?>s</h2>

<form class="ajax" action="search.php" method="get">
	<p>
		
		<table align="center">
			<tr>
				<td>
					<label for="q"><?php echo getString(80);?></label>
					<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
				</td>
				
				<td>
					<label for="r"><?php echo getString(223);?></label>
					<input type="text" name="r" id="r" onclick="ShowSearch('byri')"/>
				</td>
				
				<td>
					<label for="s"><?php echo getString(91);?></label>
					<input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
				</td>
			</tr>
		</table>
		
	</p>
</form>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsRI"></div>

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
	url : 'traitement_patients1_inf.php?name=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
	data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
	beforeSend : function() { // traitements JS à faire AVANT l'envoi
		$field.after('<img src="images/loader_30x30.gif" style="margin:5px" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
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
  // détection de la saisie dans le champ de recherche
  $('#s').keyup( function(){
    $field = $(this);
    $('#resultsSN').html(''); // on vide les resultats
    $('#ajax-loader').remove(); // on retire le loader
 
    // on commence à traiter à partir du 2ème caractère saisie
    if( $field.val().length > 0 )
    {
      // on envoie la valeur recherché en GET au fichier de traitement
      $.ajax({
  	type : 'GET', // envoi des données en GET ou POST
	url : 'traitement_patients1_inf.php?sn=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
  $('#r').keyup( function(){
    $field = $(this);
    $('#resultsRI').html(''); // on vide les resultats
    $('#ajax-loader').remove(); // on retire le loader
 
    // on commence à traiter à partir du 2ème caractère saisie
    if( $field.val().length > 0 )
    {
      // on envoie la valeur recherché en GET au fichier de traitement
      $.ajax({
  	type : 'GET', // envoi des données en GET ou POST
	url : 'traitement_patients1_inf.php?ri=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
	data : 'r='+$(this).val() , // données à envoyer en  GET ou POST
	beforeSend : function() { // traitements JS à faire AVANT l'envoi
		$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
	},
	success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
		$('#ajax-loader').remove(); // on enleve le loader
		$('#resultsRI').html(data); // affichage des résultats dans le bloc
	}
      });
    }		
  });
});

</script>

	<?php
    if(isset($_GET['divPa']))
	{
		
		/*-----------------Requête pour Nurse--------------*/
		
		if($comptidI!=0)
		{
			$resultatsI=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND u.full_name LIKE \'%'.$_GET['fullname'].'%\' ');
			/* $resultatsI->execute(array(
			'idPa'=>$_GET['iduti']	
			))or die( print_r($connexion->errorInfo()));
		 */
			
			$resultatsI->setFetchMode(PDO::FETCH_OBJ);
			
			$comptPaI=$resultatsI->rowCount();
			
		}
	?>
	
	<div style="margin-top:20px;">
	
		<span style="position:relative; font-size:250%;"><?php echo getString(60);?></span>
			
	<?php
	
	if($comptidI!=0)
	{
		if($comptPaI!=0)
		{
	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:80%;margin-bottom:20px;margin-top:10px;">
			<thead> 
				<tr>
					<th style="width:10%">S/N</th> 
					<th style="width:15%"><?php echo getString(222);?></th> 
					<th style="width:35%"><?php echo getString(9);?></th>
					<th style="width:10%"><?php echo getString(11);?></th>
					<th style="padding:0; width:30%" colspan=2>Actions</th>
				</tr> 
			</thead>
			
			<tbody> 
			
<?php

			while($ligneI=$resultatsI->fetch())//on recupere la liste des éléments
			{
?>
				<tr style="text-align:center;">
					<td><?php echo $ligneI->numero;?></td>
					<td><?php echo $ligneI->reference_id;?></td>
					<td><?php echo $ligneI->full_name;?></td>
					<td>
					<?php 
					if($ligneI->sexe=="M")
					{
						echo getString(12);
					}else{
						if($ligneI->sexe=="F")
						echo getString(13);
					}
					?>
					</td>
					
					<td>	
					<?php
					$resultStatuPaHosp=$connexion->prepare('SELECT *FROM patients_hosp ph, patients p WHERE ph.numero=:num AND ph.numero=p.numero AND ph.statusPaHosp=1');
					$resultStatuPaHosp->execute(array(
					'num'=>$ligneI->numero
					));
						
					$compteStatuPaHosp=$resultStatuPaHosp->rowCount();
	
					if($compteStatuPaHosp==0)
					{
					?>
						<a class="btn" href="hospForm.php?idInf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Hospitaliser
						</a>
					<?php
					}else{
					?>
						Déjà hospitalisé
					<?php
					}
					?>
					</td>
					
					<td>
						<a class="btn" href="utilisateurs_hosp.php?iduti=<?php echo $ligneI->id_u?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="<?php if($ligneI->status==0){ echo "display:none";}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
					</td>
				</tr>
<?php
			}
			$resultatsI->closeCursor();
					
?>
			</tbody> 
	
		</table>
	<?php
		}else{
	?>
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
				<thead> 
					<tr>
						<th><?php echo getString(152).' ';?> 
						<?php 
							if($ligneI=$resultatsI->fetch())//on récupère la liste des éléments
							{
								echo $ligneI->nom_u.' '.$ligneI->prenom_u;
							}
						?>
						<?php echo ' '. getString(153);?></th>
					</tr>
				</thead> 
			<table>
	<?php
		}
	}
	
	?>
	
	
	<a href="patients1_inf.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" class="btn-large"><?php echo 'Afficher tous les patients';?></a>
	
	<br/><br/>
	
	</div>
	
	<?php
    }else{ echo '';}
	?>


<?php
}

if(!isset($_GET['divPa']))
{

		try
		{
			$annee = date('Y').'-'.date('m').'-'.date('d');

			function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
			{
				
				$caissrecep='';
			
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
							$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&english='.$_GET['english'].''.$caissrecep.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];									
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&francais='.$_GET['francais'].''.$caissrecep.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

							}else{
								
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).''.$caissrecep.'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

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
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.'">'.$i.'</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.'">'.$i.'</a>';
						
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$caissrecep.'">'.$i.'</a>';
						
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
								$pagination .= '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.'">'.$i.'</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.'">'.$i.'</a>';
								}else{
									
									$pagination .= '<a href="'.sprintf($link, $i).''.$caissrecep.'">'.$i.'</a>';				
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
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.'">'.$i.'</a>';
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.'">'.$i.'</a>';	
								
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$caissrecep.'">'.$i.'</a>';				
							}
						}
						
					}
			 
					// Lien suivant
					if ( $current_page < $nb_pages )
					{
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							
							$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&english='.$_GET['english'].''.$caissrecep.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&francais='.$_GET['francais'].''.$caissrecep.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
							}else{
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).''.$caissrecep.'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
										
							}
						}
						
					}else{
						$pagination .= '';
					}
				}
				return $pagination;
			}
	
				
			// Numero de page (1 par défaut)
				if( isset($_GET['page']) && is_numeric($_GET['page']) )
						$page = $_GET['page'];
					else
						$page = 1;
					 
					// Nombre d'info par page
					$pagination =10;
					 
					// Numero du 1er enregistrement à lire
					$limit_start = ($page - 1) * $pagination;


		?>

			<?php
			
			if($comptidI!=0)
			{
			
				$resultatsI=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE p.id_u=u.id_u ORDER BY u.nom_u LIMIT '.$limit_start.', '.$pagination.'');
						
				$resultatsI->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
				$comptResultI=$resultatsI->rowCount();

			?>
				<table class="tablesorter tablesorter2" style="width:80%; margin-bottom:30px;">
				<?php
				
				if($comptResultI!=0)
				{
				?>	
					<thead> 
						<tr>
							<th style="width:10%">S/N</th> 
							<th style="width:15%"><?php echo getString(222);?></th> 
							<th style="width:35%"><?php echo getString(9);?></th>
							<th style="width:10%"><?php echo getString(11);?></th>
							<th style="padding:0; width:30%" colspan=2>Actions</th>
						</tr> 
					</thead>
			
					<tbody>
		<?php

					while($ligneI=$resultatsI->fetch())//on recupere la liste des éléments
					{
		?>
						<tr style="text-align:center;">
							<td><?php echo $ligneI->numero;?></td>
							<td><?php echo $ligneI->reference_id;?></td>
							<td><?php echo $ligneI->nom_u.' '.$ligneI->prenom_u ;?></td>
							<td>
							<?php 
							if($ligneI->sexe=="M")
							{
								echo getString(12);
							}elseif($ligneI->sexe=="F")
							{
								echo getString(13);
							}
							?>
							</td>
							
							<td>
							<?php
							$resultStatuPaHosp=$connexion->prepare('SELECT *FROM patients_hosp ph, patients p WHERE ph.numero=:num AND ph.numero=p.numero AND ph.statusPaHosp=1');
							$resultStatuPaHosp->execute(array(
							'num'=>$ligneI->numero
							));
								
							$compteStatuPaHosp=$resultStatuPaHosp->rowCount();
			
							if($compteStatuPaHosp==0)
							{
							?>
								<a class="btn" href="hospForm.php?idInf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
									Hospitaliser
								</a>
							<?php
							}else{
							?>
								Déjà hospitalisé
							<?php
							}
							?>
							</td>
							
							<td>
								<a class="btn" href="utilisateurs_hosp.php?iduti=<?php echo $ligneI->id_u?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="<?php if($ligneI->status==0){ echo "display:none";}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
							</td>
						</tr>
					<?php
					}
					$resultatsI->closeCursor();
					?>
					</tbody>
					
				<?php
				}else{
				?>
					<thead> 
						<tr>
							<th><?php echo getString(75);?></th>
						</tr> 
					</thead>
				<?php
				}
				?>
		
				</table>
			<?php
			}
			
		}

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
		}


?>

	<?php
	
	if($comptidI!=0)
	{
		echo '
		<tr>
			<td>';
			
			$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.nom_u');
			
			$nb_total=$nb_total->fetch();
			
			$nb_total = $nb_total['nb_total'];
			// Pagination
			$nb_pages = ceil($nb_total / $pagination);
			// Affichage
			echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
		
		echo '
			</td>
		</tr>
		';
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
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
	}
	
	if( list =='Msg')
	{
		document.getElementById('divMenuMsg').style.display='inline';
		document.getElementById('divMenuUser').style.display='none';
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
	}
	
	if( list =='Liste')
	{
		document.getElementById('listOff').style.display='inline';
		document.getElementById('listOn').style.display='none';
	}
	
	if( list =='ListeNon')
	{
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
	<?php
		include('footer.php');
	?>
</div> <!-- /footer -->
	
</body>

</html>