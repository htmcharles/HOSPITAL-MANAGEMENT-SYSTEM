<?php
session_start();
include("connect.php");

try
{

	if(isset($_GET['idConsult']))
	{
	
		$diagnoExa = $_POST['diagnosticexa'];
		$numPa = $_GET['num'];
		$idconsu = $_GET['idConsult'];
				
		$resultats=$connexion->prepare('UPDATE consultations SET diagnosticexa=:diagno WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'diagno'=>$diagnoExa,
		'modifierConsu'=>$idconsu
		))or die( print_r($connexion->errorInfo()));
			
		echo '<script type="text/javascript"> alert("Vous venez de diagnostiquer le patient");</script>';
		echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numPa.'#fichepatient";</script>';
		
	}

}

catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}

?>