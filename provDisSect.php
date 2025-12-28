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
	}else{
		if(isset($_POST['secteur']))
		{
			$id_sector=$_POST['secteur'];
			
			// echo $id_dist;
			
			$req=$connexion->prepare('SELECT * FROM cells WHERE id_sector=:id_sector ORDER BY id_cell');
			$req->execute(array(
			'id_sector'=>$id_sector
			));

			$cell.="<select id='cell' name='cell'><option value=''>Select cell...</option>";
				while($reponse=$req->fetch(PDO::FETCH_ASSOC))
				{
					// $id_sport=$reponse['id_sport'];
					$cell.="<option value=".$reponse['id_cell'].">".htmlentities($reponse['nomcell'])."</option>";
				}
			$cell.="</select><span style='color:black;font-weight:bold'> *</span>";
			echo $cell;
			$req->closeCursor();
		}else{
			if(isset($_POST['cell']))
			{
				$id_cell=$_POST['cell'];
				
				// echo $id_dist;
				
				$req=$connexion->prepare('SELECT * FROM villages WHERE id_cell=:id_cell ORDER BY id_village');
				$req->execute(array(
				'id_cell'=>$id_cell
				));

				$village.="<select id='village' name='village'><option value=''>Select village...</option>";
					while($reponse=$req->fetch(PDO::FETCH_ASSOC))
					{
						// $id_sport=$reponse['id_sport'];
						$village.="<option value=".$reponse['id_village'].">".htmlentities($reponse['nomvillage'])."</option>";
					}
				$village.="</select><span style='color:black;font-weight:bold'> *</span>";
				echo $village;
				$req->closeCursor();
			}
		}
	}
}
?>