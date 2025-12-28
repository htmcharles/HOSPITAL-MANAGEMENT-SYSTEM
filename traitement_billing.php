<?php
try
{
	include("connect.php");

	if(isset($_POST['searchbtn']))
	{
		$idCoor=$_POST['idCoor'];
		$numPa=$_POST['numPa'];
		$datedebut=$_POST['datedebut'];
		
		if(isset($_POST['datefin']))
		{
			$datefin=$_POST['datefin'];
		}else{
			$datefin=$_POST['datedebut'];
		}

		$resultats=$connexion->query("SELECT *FROM consultations c, patients p WHERE c.dateconsu>='$datedebut' AND c.dateconsu<='$datedebut' AND p.numero='$numPa' AND c.numero='$numPa'");
		//$num_rows=$resultats->fetchColumn();
		
		
			$num_rows=$resultats->rowCount();

			
		if( $num_rows != 0)
		{
			$resultats->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$resultats->fetch())
			{
				echo 'Date consultation :'.$ligne->dateconsu.'<br/>Observations faites :'.$ligne->observations.'<br/>Soins executer :'.$ligne->soinsexecuter.'<br/>Examens faits :'.$ligne->examen.'!<br/><br/>';
				
				// echo '<script type="text/javascript"> alert("Find :\n '.$ligne->dateconsu.'_'.$ligne->observations.'_'.$ligne->soinsexecuter.'_'.$ligne->examen.'");</script>';
			
			}
			// echo '<script type="text/javascript">document.location.href="billing.php?num='.$numPa.'";</script>';
		}else{
		
			echo '<script type="text/javascript"> alert("Not Find");</script>';
			echo '<script type="text/javascript">document.location.href="billing.php?num='.$numPa.'";</script>';
		}
		
	}
	
	if(isset($_GET['deleteid_item']))
	{
		$resultats=$connexion->prepare('DELETE FROM temp_facture WHERE id_tempfacture=:id_tempfact');
		
		$resultats->execute(array(
		'id_tempfact'=>$_GET['deleteid_item']
		
		))or die($resultats->errorInfo());

		// echo '<script type="text/javascript"> alert("L\'article '.$_GET['deleteid_item'].' a bien été supprimé");</script>';

		echo '<script type="text/javascript">document.location.href="billing.php?num='.$_GET['num'].'&coordi='.$_GET['coordi'].'&datefacture='.$_GET['datefacture'].'&search=ok&facture=ok&delete=ok#addBilling"</script>';
	}

}

catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}


?>