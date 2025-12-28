<?php
include("connect.php");

echo "Bleh-BlehBleh";

/* 
if(isset($_POST['anneemois']))
{
	$anneemois=array();
	
	foreach($_POST['anneemois'] as $val)
	{
		$anneemois=$val;
	}
	
	
	
	$days=cal_days_in_month(CAL_GREGORIAN,$annemois[1],$annemois[0]);

	
	$jours="";
	
	$jours.="<select name='jours' id='jours'>";
		for($j=1;$j<=$days;$j++)
		{
			$jours.="<option value=".$j.">".$j."</option>";
		}		
		
	$jours.="</select>";

	echo $jours;
 */
}
?>