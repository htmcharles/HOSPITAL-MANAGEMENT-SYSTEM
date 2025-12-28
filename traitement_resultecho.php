<?php
session_start();
include("connect.php");


	if(isset($_POST['saveechobtn']))
	{
		
		$url = $_POST['url'];
		$idRad = array();
		$resultatsRad = array();
		
		foreach($_POST['idRad'] as $idR)
		{
			$idRad[] = $idR;
		}
			
		foreach($_POST['resultatsRad'] as $echo)
		{
			$resultatsRad[] = $echo;
		}
			
		
		for($i=0;$i<sizeof($idRad);$i++)
		{
			if($resultatsRad[$i] !="")
			{
				$idMed=$_SESSION['id'];
				$annee = date('Y').'-'.date('m').'-'.date('d');

			}else{
				$idMed=NULL;
				$annee="0000-00-00";
			}
			
			$resultats=$connexion->prepare('UPDATE med_radio mr SET mr.dateradio=:dateradio, mr.resultatsRad=:resultatsRad, mr.numero=:num, mr.id_uX=:idMed, mr.radiofait=1 WHERE id_medradio=:modifierRadio');
					
			$resultats->execute(array(
			'dateradio'=>$annee,
			'resultatsRad'=>$resultatsRad[$i],
			'num'=>$_GET['num'],
			'idMed'=>$idMed,
			'modifierRadio'=>$idRad[$i]
			
			))or die( print_r($connexion->errorInfo()));
		}
		
			echo '<script type="text/javascript">document.location.href="'.$url.'";</script>';
				
	}	
?>