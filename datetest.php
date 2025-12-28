
<!doctype html>
<html lang="en">
<noscript>
	Cette page requiert du Javascript.
	Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<meta charset="utf-8"/>
	<title><?php echo 'Date test';?></title>

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

</head>

<body>

	<table id="tabledate">

		<tr>		
			<td>
				<select name="annee" id="annee" onchange="myScriptAnnee()">
					<?php
					for($a=2000;$a<=2020;$a++)
					{
					?>
						<option value="<?php echo $a;?>" <?php if(date('Y')==$a) echo 'selected="selected"';?>>
						<?php echo $a;?>
						</option>
					<?php
					}
					?>
				</select>
				
				<select name="mois" id="mois" onchange="myScriptMois()">
					<?php
					for($m=1;$m<=12;$m++)
					{
						$moisString=date("F",mktime(0,0,0,$m,10));
					?>
						<option value="<?php echo $m;?>" <?php if(date('F')==$moisString) echo 'selected="selected"'; ?>>
						<?php 
							echo $moisString;
						?>
						</option>
					<?php
					}
					?>
				</select>
				
				
				<select style="width:100px;" name="jours" id="jours">
					<?php
					if(isset($_GET['jours']))
					{
						$jours=$_GET['jours'];

						for($j=1;$j<=$jours;$j++)
						{
						?>
							<option value="<?php echo $j;?>">
							<?php 
								echo $j;
							?>
							</option>
					<?php
						}
					}
					?>
				</select>
			</td>
		</tr>
	</table>

				<script src="jQuery.js"></script>
	
	<script type="text/javascript">
	
		function myScriptAnnee()
	   {
		   var i;
		   var test = [];
		   var annee = $('#annee').val();
		   var mois = $('#mois').val();
		   var jours = new Date(annee, mois , 0).getDate();
		   for(i = 1; i<= jours; i++)
		   {
				test[i-1] = i;
				$('#jours').append('<option value="' + i + '">'
						+ i + '</option>');
		   }
	   }
		
		function myScriptMois()
	   {
		   var i;
		   var test = [];
		   var annee = $('#annee').val();
		   var mois = $('#mois').val();
		   var jours = new Date(annee, mois , 0).getDate();
		   for(i = 1; i<= jours; i++)
		   {
				test[i-1] = i;
				$('#jours').append('<option value="' + i + '">'
						+ i + '</option>');
		   }
	   }
	    
	</script>
				
</body>

</html>
