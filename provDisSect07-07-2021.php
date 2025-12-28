<?php
include("connect.php");

if(isset($_POST['province']))
{

	$id_prov=$_POST['province'];

	//$id_prov=1;
	$req=$connexion->prepare('SELECT * FROM district WHERE id_province=:prov');
	$req->execute(array(
		'prov'=>$id_prov
	));

	
	if($_POST['province']!=6)
	{

		$district.="<select name='district' id='district'><option value=''>Select district...</option>";
		while($reponse=$req->fetch(PDO::FETCH_ASSOC))
		{
			
			// $id_departement=$reponse['id_departement'];
				$district.="<option value=".$reponse['id_district'].">".htmlentities($reponse['nomdistrict'])."</option>";
		}
		
		$district.="</select><span style='color:black;font-weight:bold'> *</span>";
	}
	

	echo $district;
	$req->closeCursor();

}else{

	if(isset($_POST['district']))
	{
		$id_dist=$_POST['district'];
		
		// echo $id_dist;
		
		$req=$connexion->prepare('SELECT * FROM sectors WHERE id_district=:distri ORDER BY id_sector');
		$req->execute(array(
		'distri'=>$id_dist
		));

		$secteur.="<select id='secteur' name='secteur'><option value=''>Select sector...</option>";
			while($reponse=$req->fetch(PDO::FETCH_ASSOC))
			{
				// $id_sport=$reponse['id_sport'];
				$secteur.="<option value=".$reponse['id_sector'].">".htmlentities($reponse['nomsector'])."</option>";
			}
		$secteur.="</select><span style='color:black;font-weight:bold'> *</span>";
		echo $secteur;
		$req->closeCursor();
	}
}
?>