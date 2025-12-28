<?php
try
{

	session_start();
	
	include("connect.php");
	include("serialNumber.php");

	$annee = date('Y').'-'.date('m').'-'.date('d').' '.date('H').':'.date('i').':'.date('s');

	if(isset($_GET['idbillActif']))
	{
		$modifierId=$_GET['idbillActif'];
		$page=$_GET['page'];
		$newStatu=1;
		
		
		$resultats=$connexion->query("SELECT status FROM bills WHERE id_bill='$modifierId'");
		
		//$bills_rows=$resultats->fetchColumn();
		$bills_rows=$resultats->rowCount();
		
		if( $bills_rows != 0)
		{
			$resultats=$connexion->prepare('UPDATE bills SET status=:status, codeaccount=:codeaccount, datedone=:datedone WHERE id_bill=:idbill');
			
			$resultats->execute(array(
			'idbill'=>$modifierId,			
			'status'=>$newStatu,
			'codeaccount'=>$_SESSION['codeAcc'],
			'datedone'=>$annee
			
			))or die( print_r($connexion->errorInfo()));		
		}
		
		
		header ("Location:billsaccount.php?page=".$page."&idbill=".$modifierId."");
			

	}else{

		if(isset($_GET['idbillDesactif']))
		{
			$modifierId=$_GET['idbillDesactif'];
			$page=$_GET['page'];
			$newStatu=0;
			
			$resultats=$connexion->query("SELECT status FROM bills WHERE id_bill='$modifierId'");
			
			//$bills_rows=$resultats->fetchColumn();
			$bills_rows=$resultats->rowCount();
			
			if( $bills_rows != 0)
			{
				$resultats=$connexion->prepare('UPDATE bills SET status=:status, codeaccount=:codeaccount, datedone=:datedone WHERE id_bill=:idbill');
			
				$resultats->execute(array(
				'idbill'=>$modifierId,			
				'status'=>$newStatu,
				'codeaccount'=>$_SESSION['codeAcc'],
				'datedone'=>$annee
				
				))or die( print_r($connexion->errorInfo()));		
			}
			

			
			header ("Location:billsaccount.php?page=".$page."&idbill=".$modifierId."");
				

			
		}
	}
	
}

catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}

?>