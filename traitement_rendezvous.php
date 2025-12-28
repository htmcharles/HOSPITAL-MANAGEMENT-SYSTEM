<?php
try
{
		
	session_start();
	include("connect.php");

	if(isset($_POST['savebtn']))
	{
	
		$heure=date("H");
		$min=date("i");


		$heureToday=$heure.':'.$min;
		$annee = date('Y').'-'.date('m').'-'.date('d');
					
		$idmed=$_POST['idMed'];
		$num=$_POST['numPa'];
		
		if($num=="0")
		{
			$newPa=$_POST['newPa'];
			$newTelPa=$_POST['newTelPa'];
			$num=NULL;
		}else{
			$newPa=NULL;
			$newTelPa=NULL;
		}
		
		$doneby=$_SESSION['id'];
		$statusRdv=0;
				
		
		if($_POST['joursrdv']!="00")
		{
			$dateRdv=$_POST['anneerdv'].'-'.$_POST['moisrdv'].'-'.$_POST['joursrdv'];		
			$heureRdv=$_POST['heurerdv'].':'.$_POST['minrdv'];
			
		}else{
			$dateRdv="0000-00-00";
			$heureRdv="";	
		}
		
		
		if($dateRdv != "0000-00-00")
		{
			
			$getRdv=$connexion->prepare('SELECT *FROM rendez_vous r WHERE r.daterdv=:daterdv AND r.heurerdv=:heurerdv AND r.numero=:num  AND r.id_uM=:idmed ORDER BY r.id_rdv');		
			$getRdv->execute(array(
			'daterdv'=>$dateRdv,
			'heurerdv'=>$heureRdv,
			'num'=>$num,
			'idmed'=>$idmed	
			));
			
			$getRdv->setFetchMode(PDO::FETCH_OBJ);

			$comptgetRdv=$getRdv->rowCount();
			
			// echo $comptgetRdv;
			
			if($comptgetRdv ==0)
			{
				$statusRdv=0;
				
				if($annee <= $dateRdv)
				{
					$resultat=$connexion->prepare('INSERT INTO rendez_vous (dateattribution,daterdv,heurerdv,id_uM,numero,autrePa,autreTel,motifrdv,doneby,statusRdv) VALUES(:dateattri,:daterdv,:heurerdv,:idmed,:num,:autrePa,:autreTel,:motifrdv,:doneby,:statusRdv)');
					$resultat->execute(array(
					'dateattri'=>date('Y-m-d', strtotime($annee)),
					'daterdv'=>date('Y-m-d', strtotime($dateRdv)),
					'heurerdv'=>$heureRdv,
					'idmed'=>$idmed,
					'num'=>$num,
					'autrePa'=>$newPa,
					'autreTel'=>$newTelPa,
					'motifrdv'=>nl2br($_POST['motifrdv']),
					'doneby'=>$doneby,
					'statusRdv'=>$statusRdv
					)) or die( print_r($connexion->errorInfo()));
				}
			}
		}
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="rendezvous1.php?english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="rendezvous1.php?francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="rendezvous1.php";</script>';
			}
		}	
	}
	
	if(isset($_POST['updatebtn']))
	{
		
		
		$heure=date("H");
		$min=date("i");


		$heureToday=$heure.':'.$min;
		$annee = date('Y').'-'.date('m').'-'.date('d');
					
		$idmed=$_POST['idMed'];
		$num=$_POST['numPa'];
		
		
		if($num=="0")
		{
			$newPa=$_POST['newPa'];
			$newTelPa=$_POST['newTelPa'];
			$num=NULL;
		}else{
			$newPa=NULL;
			$newTelPa=NULL;
		}
		
		$doneby=$_SESSION['id'];
		$idRdv=$_POST['idrdv'];
		
		if($_POST['joursrdv']!="00")
		{
			$dateRdv=$_POST['anneerdv'].'-'.$_POST['moisrdv'].'-'.$_POST['joursrdv'];		
			$heureRdv=$_POST['heurerdv'].':'.$_POST['minrdv'];
			
		}else{
			$dateRdv="0000-00-00";
			$heureRdv="";	
		}
		
		// echo 'UPDATE rendez_vous SET daterdv='.$dateRdv.',heurerdv='.$heureRdv.',numero='.$num.',autrePa='.$newPa.',autreTel='.$newTelPa.',id_uM='.$idmed.',motifrdv='.$_POST['motifrdv'].',doneby='.$doneby.' WHERE id_rdv='.$idRdv.'';
		
		$resultats=$connexion->prepare('UPDATE rendez_vous SET daterdv=:daterdv,heurerdv=:heurerdv,numero=:num,autrePa=:autrePa,autreTel=:autreTel,id_uM=:idmed,motifrdv=:motifrdv,doneby=:doneby WHERE id_rdv=:modifierIdrdv');
						
		$resultats->execute(array(
		'daterdv'=>$dateRdv,
		'heurerdv'=>$heureRdv,
		'num'=>$num,
		'autrePa'=>$newPa,
		'autreTel'=>$newTelPa,
		'idmed'=>$idmed,
		'motifrdv'=>nl2br($_POST['motifrdv']),
		'doneby'=>$doneby,
		'modifierIdrdv'=>$idRdv
		))or die( print_r($connexion->errorInfo()));
			
		echo '<script type="text/javascript"> alert("Successfully updated!!!");</script>';
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="rendezvous1.php?english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="rendezvous1.php?francais='.$_GET['francais'].'";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="rendezvous1.php";</script>';
			}
		}
	
	}
		
}

catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}


	/*
	if(isset($_POST['delete']))
	{
	$resultats=$connexion->prepare('DELETE FROM utilisateurs WHERE nom=\'B\'');
	$resultats->execute(array(
	'nom'=>$_POST['nom'],
	'prenom'=>$_POST['prenom'],
	'e_mail'=>$_POST['e_mail'],
	'password'=>md5($_POST['password']),
	'status'=>$_POST['status']
	));
	echo "L'utilisateur a bien été supprimé<br/><br/>";

	$resultats->closeCursor();
	}


catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}
*/

?>