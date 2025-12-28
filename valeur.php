<?php
include("connect.php");


	$valeur=$_GET['valeur'];
	$idpresta=$_GET['idpresta'];

	$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE id_examen=:idexamen AND valeur=:valeur ORDER BY valeur');
	$resultValeur->execute(array(
	'idexamen'=>$idpresta,
	'valeur'=>$valeur
	));

	$resultValeur->setFetchMode(PDO::FETCH_OBJ);

	$comptIdValeur=$resultValeur->rowCount();
		

	if($comptIdValeur!=0)
	{
			
		if($ligneValeur=$resultValeur->fetch())
		{
			if(isset($_GET['min']))
			{
				echo $ligneValeur->min_valeur;
			}
			
			if(isset($_GET['max']))
			{
				echo $ligneValeur->max_valeur;
			}
		}
	}
	
?>