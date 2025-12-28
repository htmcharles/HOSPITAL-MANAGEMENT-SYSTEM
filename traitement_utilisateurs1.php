<?php
try
{

	session_start();
	
	include("connect.php");
	include("serialNumber.php");

	// echo $_POST['profil'];
	
	if(isset($_GET['idutiActif']))
	{
		$modifierId=$_GET['idutiActif'];
		$page=$_GET['page'];
		$newStatu=1;
		
		
		$resultats=$connexion->query("SELECT status FROM utilisateurs WHERE id_u='$modifierId'");
		
		//$num_rows=$resultats->fetchColumn();
		$num_rows=$resultats->rowCount();
		
		if( $num_rows != 0)
		{
			$resultats=$connexion->prepare('UPDATE utilisateurs SET status=:status, updatedby=:idupdatedby WHERE id_u=:iduti');
			
			$resultats->execute(array(
			'iduti'=>$modifierId,			
			'status'=>$newStatu,
			'idupdatedby'=>$_SESSION['id']
			
			))or die( print_r($connexion->errorInfo()));		
		}
		
		
		$sqlP=$connexion->query("SELECT *FROM patients p WHERE p.id_u='$modifierId'");
		$sqlD=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$modifierId'");
		$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$modifierId'");
		$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$modifierId'");
		$sqlX=$connexion->query("SELECT *FROM radiologues x WHERE x.id_u='$modifierId'");
		$sqlR=$connexion->query("SELECT *FROM receptionistes r WHERE r.id_u='$modifierId'");
		$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$modifierId'");
		$sqlA=$connexion->query("SELECT *FROM auditors a WHERE a.id_u='$modifierId'");
		$sqlAcc=$connexion->query("SELECT *FROM accountants acc WHERE acc.id_u='$modifierId'");
		$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$modifierId'");

		$comptidP=$sqlP->rowCount();
		$comptidD=$sqlD->rowCount();
		$comptidI=$sqlI->rowCount();
		$comptidL=$sqlL->rowCount();
		$comptidX=$sqlX->rowCount();
		$comptidR=$sqlR->rowCount();
		$comptidC=$sqlC->rowCount();
		$comptidA=$sqlA->rowCount();
		$comptidAcc=$sqlAcc->rowCount();
		$comptidM=$sqlM->rowCount();
		
		if($comptidP!=0)
		{
			if(isset($_GET['divPa']))
			{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divPa=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divPa=ok');						
						
					}else{
						header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&divPa=ok');						
					}
				}
			}else{
				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');	
						
					}else{
						header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
			}
		}
		
		if($comptidD!=0)
		{
			if(isset($_GET['divMed']))
			{
				header ("Location:medecins1.php?page=".$page."&iduti=".$modifierId."&divMed=ok");
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divMed=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divMed=ok');						
						
					}else{
						header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&divMed=ok');						
					}
				}
			}else{
			
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
			}
		}
		
		if($comptidI!=0)
		{
			if(isset($_GET['divInf']))
			{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divInf=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divInf=ok');						
						
					}else{
						header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&divInf=ok');						
					}
				}
				
			}else{
			
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
			}
		}
		
		if($comptidL!=0)
		{
			if(isset($_GET['divLabo']))
			{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divLabo=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divLabo=ok');						
						
					}else{
						header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&divLabo=ok');						
					}
				}
				
			}else{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
			}
		}
		
		if($comptidX!=0)
		{
			if(isset($_GET['divRadio']))
			{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divRadio=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divRadio=ok');						
						
					}else{
						header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&divRadio=ok');						
					}
				}
				
			}else{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
			}
		}
		
		if($comptidR!=0 AND isset($_GET['updaterecep']))
		{
			if(isset($_GET['divRec']))
			{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divRec=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divRec=ok');						
						
					}else{
						header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&divRec=ok');						
					}
				}
				
			}else{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
			}
		}

		
		if($comptidC!=0 AND isset($_GET['updatecash']))
		{
			if(isset($_GET['divCash']))
			{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divCash=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divCash=ok');						
						
					}else{
						header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&divCash=ok');						
					}
				}
				
			}else{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
			}
		}
		
		if($comptidA!=0)
		{
			if(isset($_GET['divAud']))
			{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divAud=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divAud=ok');						
						
					}else{
						header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&divAud=ok');						
					}
				}
				
			}else{
				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
				
			}
				
		}

		if($comptidAcc!=0)
		{
			if(isset($_GET['divAcc']))
			{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divAcc=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divAcc=ok');						
						
					}else{
						header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&divAcc=ok');						
					}
				}
				
			}else{
				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
			}
		}	
		
		if($comptidM!=0)
		{
			if(isset($_GET['divCoord']))
			{				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divCoord=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divCoord=ok');						
						
					}else{
						header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&divCoord=ok');						
					}
				}
				
			}else{
				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'');						
					}
				}
			}
		}

	}else{

		if(isset($_GET['idutiDesactif']))
		{
			$modifierId=$_GET['idutiDesactif'];
			$page=$_GET['page'];
			$newStatu=0;
			
			$resultats=$connexion->query("SELECT status FROM utilisateurs WHERE id_u='$modifierId'");
			
			//$num_rows=$resultats->fetchColumn();
			$num_rows=$resultats->rowCount();
			
			if( $num_rows != 0)
			{
				$resultats=$connexion->prepare('UPDATE utilisateurs SET status=:status, updatedby=:idupdatedby WHERE id_u=:iduti');
				
				$resultats->execute(array(
				'iduti'=>$modifierId,				
				'status'=>$newStatu,
				'idupdatedby'=>$_SESSION['id']
				
				))or die( print_r($connexion->errorInfo()));		
			}
			
			
			$sqlP=$connexion->query("SELECT *FROM patients p WHERE p.id_u='$modifierId'");
			$sqlD=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$modifierId'");
			$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$modifierId'");
			$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$modifierId'");
			$sqlX=$connexion->query("SELECT *FROM radiologues x WHERE x.id_u='$modifierId'");
			$sqlR=$connexion->query("SELECT *FROM receptionistes r WHERE r.id_u='$modifierId'");
			$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$modifierId'");
			$sqlA=$connexion->query("SELECT *FROM auditors a WHERE a.id_u='$modifierId'");
			$sqlAcc=$connexion->query("SELECT *FROM accountants acc WHERE acc.id_u='$modifierId'");
			$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$modifierId'");

			$comptidP=$sqlP->rowCount();
			$comptidD=$sqlD->rowCount();
			$comptidI=$sqlI->rowCount();
			$comptidL=$sqlL->rowCount();
			$comptidX=$sqlX->rowCount();
			$comptidR=$sqlR->rowCount();
			$comptidC=$sqlC->rowCount();
			$comptidA=$sqlA->rowCount();
			$comptidAcc=$sqlAcc->rowCount();
			$comptidM=$sqlM->rowCount();
			

			if($comptidP!=0)
			{
				
				if(isset($_GET['divPa']))
				{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divPa=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divPa=ok');						
							
						}else{
							header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&divPa=ok');						
						}
					}
				}else{
					
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');	
							
						}else{
							header('Location:patients1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
				}
			}
			
			if($comptidD!=0)
			{
				if(isset($_GET['divMed']))
				{
					header ("Location:medecins1.php?page=".$page."&iduti=".$modifierId."&divMed=ok");
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divMed=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divMed=ok');						
							
						}else{
							header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&divMed=ok');						
						}
					}
				}else{
				
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:medecins1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
				}
			}
			
			if($comptidI!=0)
			{
				if(isset($_GET['divInf']))
				{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divInf=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divInf=ok');						
							
						}else{
							header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&divInf=ok');						
						}
					}
					
				}else{
				
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:infirmiers1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
				}
			}
			
			if($comptidL!=0)
			{
				if(isset($_GET['divLabo']))
				{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divLabo=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divLabo=ok');						
							
						}else{
							header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&divLabo=ok');						
						}
					}
					
				}else{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:laborantins1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
				}
			}
			
			if($comptidX!=0)
			{
				if(isset($_GET['divRadio']))
				{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divRadio=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divRadio=ok');						
							
						}else{
							header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&divRadio=ok');						
						}
					}
					
				}else{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:radiologues1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
				}
			}
			
			if($comptidR!=0 AND isset($_GET['updaterecep']))
			{
				if(isset($_GET['divRec']))
				{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divRec=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divRec=ok');						
							
						}else{
							header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&divRec=ok');						
						}
					}
					
				}else{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:receptionistes1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
				}
			}

			if($comptidC!=0 AND isset($_GET['updatecash']))
			{
				if(isset($_GET['divCash']))
				{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divCash=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divCash=ok');						
							
						}else{
							header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&divCash=ok');						
						}
					}
					
				}else{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:caissiers1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
				}
			}
			
			if($comptidA!=0)
			{
				if(isset($_GET['divAud']))
				{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divAud=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divAud=ok');						
							
						}else{
							header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&divAud=ok');						
						}
					}
					
				}else{
					
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:auditeurs1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
					
				}
				
			}
			
			if($comptidAcc!=0)
			{
				if(isset($_GET['divAcc']))
				{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divAcc=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divAcc=ok');						
							
						}else{
							header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&divAcc=ok');						
						}
					}
					
				}else{
					
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:comptables1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
				}
			}
		
			if($comptidM!=0)
			{
				if(isset($_GET['divCoord']))
				{
					
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divCoord=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divCoord=ok');						
							
						}else{
							header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&divCoord=ok');						
						}
					}
					
				}else{
					
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:coordinateurs1.php?page='.$page.'&iduti='.$modifierId.'');						
						}
					}
				}
			}
			
		}
	}
	
	if(isset($_GET['num']) AND isset($_POST['signvitobtn']))
	{
		$numero=$_GET['num'];
		$poids=$_POST['poids'];
		$taille=$_POST['taille'];
		$tempera=$_POST['tempera'];
		$tensionart=$_POST['tensionart'];
		$pouls=$_POST['pouls'];
		
		$resultats=$connexion->query("SELECT *FROM patients p WHERE p.numero='$numero'");
		
		//$num_rows=$resultats->fetchColumn();
		$num_rows=$resultats->rowCount();
		
		if( $num_rows != 0)
		{
			$resultats=$connexion->prepare('UPDATE patients p SET p.poidsPa=:poids, p.taillePa=:taille, p.temperaturePa=:tempera, p.tensionarteriellePa=:tensionart, p.poulsPa=:pouls WHERE p.numero=:num');
			
			$resultats->execute(array(
			'num'=>$numero,			
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls
			
			))or die( print_r($connexion->errorInfo()));
			
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:patients1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:patients1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:patients1.php");
				}
			}
			
		}
	}
	
	if(isset($_POST['add']))
	{
		$newService=$_POST['newService'];
		$grade=1;
		
		if(isset($_GET['iduti'])!="")
		{
			$id_uti=$_GET['iduti'];
		}
		
		$bd=$connexion->prepare("SELECT * FROM services WHERE nomservice=:newService");
		$bd->execute(array(
		'newService'=>$newService
		));
		
		$compteur=$bd->rowCount();
		
		if($compteur==0)
		{
			if($newService!="")
			{
				$resultat=$connexion->prepare("INSERT INTO services (nomservice,id_grade) VALUES(:nomserv,:id_grade)");
				$resultat->execute(array(
				'nomserv'=>$newService,
				'id_grade'=>$grade
				)) or die( print_r($connexion->errorInfo()));
		
				if(isset($_GET['iduti']))
				{
					echo '<script type="text/javascript">alert("Ajout du service seuleument réussi");</script>';
					echo '<script type="text/javascript">document.location.href="utilisateurs.php?iduti='.$id_uti.'";</script>';
					
				}else{
					
					echo '<script type="text/javascript">alert("Ajout du service seuleument réussi");</script>';
					echo '<script type="text/javascript">document.location.href="utilisateurs.php";</script>';
				
				}
				
				
			}else{
			
				if(isset($_GET['iduti'])!="")
				{
					echo '<script type="text/javascript">alert("Veuillez saisir un service");</script>';
					echo '<script type="text/javascript">document.location.href="utilisateurs.php?iduti='.$id_uti.'";</script>';
					
				}else{
					
					echo '<script type="text/javascript">alert("Veuillez saisir un service");</script>';
					echo '<script type="text/javascript">document.location.href="utilisateurs.php";</script>';
				}
			}
			
		}else{
		
			if(isset($_GET['iduti'])!="")
			{
				echo '<script type="text/javascript">alert("Le service saisi existe déjà");</script>';
				echo '<script type="text/javascript">document.location.href="utilisateurs.php?iduti='.$id_uti.'";</script>';
				
			}else{
				
				echo '<script type="text/javascript">alert("Le service saisi existe déjà");</script>';
				echo '<script type="text/javascript">document.location.href="utilisateurs.php";</script>';
			}

		}
	}

		
	if(isset($_POST['savebtn']))
	{

		$nom=ucwords(strtolower(strip_tags($_POST['nom'])));
		$prenom=ucwords(strtolower(strip_tags($_POST['prenom'])));
		$fullname=$nom.' '.$prenom;
		$sexe=$_POST['sexe'];
		$province=strip_tags($_POST['province']);
		
		if($_POST['province']==6)
		{
			$adresseExt=strip_tags($_POST['adresseExt']);
			$distict='';
			$secteur='';
		}else{
			$adresseExt="";
			
			$distict=strip_tags($_POST['district']);
			$secteur=strip_tags($_POST['secteur']);
		}
		$phone=strip_tags($_POST['phone']);
		$mail=strip_tags($_POST['mail']);
		$id_createdby=$_SESSION['id'];
		
		if($_POST['profil']!="Patient")
		{
			$password=strip_tags($_POST['password']);
		}else{
			$password="";
		}
		
		$status=1;
		

		// echo $nom.'__'.$password;
		
		
		if($_POST['profil']!="Patient")
		{
			$resultats=$connexion->prepare('INSERT INTO utilisateurs (nom_u,prenom_u,full_name,sexe,province,autreadresse,telephone,e_mail,password,status,district,secteur) VALUES(:nom, :prenom, :fullname, :sexe, :province, :adresseExt, :phone, :e_mail, :password,:status,:distict,:secteur)');
			
			$resultats->execute(array(
			'nom'=>$nom,
			'prenom'=>$prenom,
			'fullname'=>$fullname,
			'sexe'=>$sexe,
			'province'=>$province,
			'adresseExt'=>$adresseExt,
			'phone'=>$phone,
			'e_mail'=>$mail,
			'password'=>$password,
			'status'=>$status,
			'distict'=>$distict,
			'secteur'=>$secteur
			)); 
			
			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
			$res->setFetchMode(PDO::FETCH_OBJ);					
		
		}
		 
		if($_POST['profil']=="Doctor" OR $_POST['profil']=="Medecin")
		{
		
			$codeD=$_POST['codedoc'];
			$grade=$_POST['grade'];
			
			if(isset($_POST['grade'])==1)
			{
				$categopre=$_POST['categopresta'];
			}else{
				$categopre=0;
			}
			

			// echo $codeD.'___'.$nom.'___'.$grade.'___'.$categopres;

			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('INSERT INTO medecins (id_u,codemedecin,id_grade,createdbyMed) VALUES(:id,:codeD,:grade,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeD'=>$codeD,
				'grade'=>$grade,
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));

				$result=$connexion->prepare('INSERT INTO servicemed (dateaffectationmed,codemedecin,id_categopresta) VALUES(:date,:code,:categopre)');
				$result->execute(array(
				'date'=>$_POST['anAd'],
				'code'=>$codeD,
				'categopre'=>$categopre
				))or die( print_r($connexion->errorInfo()));	
			}
			
			createSN('D');
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:medecins1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:medecins1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:medecins1.php");
				}
			}
			
		}
		
		
		if($_POST['profil']=="Nurse" OR $_POST['profil']=="Infirmier")
		{
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('INSERT INTO infirmiers (id_u,codeinfirmier,dateaffectationinf,createdbyInf) VALUES(:id,:codeI,:date,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeI'=>$_POST['codeinf'],
				'date'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
				
				
				if(isset($_POST['hosp']) AND isset($_POST['clinik']))
				{
					
					$resultats=$connexion->prepare('UPDATE infirmiers SET inf_hosp=2 WHERE codeinfirmier=:modifierInf');
					$resultats->execute(array(
					'modifierInf'=>$_POST['codeinf']
					))or die( print_r($connexion->errorInfo()));
					
				}else{
					if(isset($_POST['hosp']) AND !isset($_POST['clinik']))
					{
						
						$resultats=$connexion->prepare('UPDATE infirmiers SET inf_hosp=1 WHERE codeinfirmier=:modifierInf');
						$resultats->execute(array(
						'modifierInf'=>$_POST['codeinf']
						))or die( print_r($connexion->errorInfo()));
						
					}else{
						if(!isset($_POST['hosp']) AND isset($_POST['clinik']))
						{
							
							$resultats=$connexion->prepare('UPDATE infirmiers SET inf_hosp=0 WHERE codeinfirmier=:modifierInf');
							$resultats->execute(array(
							'modifierInf'=>$_POST['codeinf']
							))or die( print_r($connexion->errorInfo()));
							
						}
					}
				}
				
			}
			createSN('N');
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:infirmiers1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:infirmiers1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:infirmiers1.php");
				}
			}
			
		}
		
		
		if($_POST['profil']=="Laboratory Technician" OR $_POST['profil']=="Laborantin")
		{
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('INSERT INTO laborantins (id_u,codelabo,dateaffectationlabo,createdbyLabo) VALUES(:id,:codeL,:date,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeL'=>$_POST['codelab'],
				'date'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
				
			}
			createSN('L');
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:laborantins1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:laborantins1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:laborantins1.php");
				}
			}
			
		}
		
		
		
		if($_POST['profil']=="Radiologue" OR $_POST['profil']=="Radiologue")
		{
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('INSERT INTO radiologues (id_u,coderadio,dateaffectationradio,createdbyRadio) VALUES(:id,:codeX,:date,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeX'=>$_POST['coderad'],
				'date'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
				
			}
			createSN('X');
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:radiologues1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:radiologues1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:radiologues1.php");
				}
			}
			
		}
		
		
		if($_POST['profil']=="Receptioniste" OR $_POST['profil']=="Receptionist")
		{
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('INSERT INTO receptionistes (id_u,codereceptio,dateaffectationreceptio,createdbyRec) VALUES(:id,:codeR,:date,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeR'=>$_POST['coderec'],
				'date'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
				
		
				if(isset($_POST['checkCashier']))
				{
					$resultats=$connexion->prepare('INSERT INTO cashiers (id_u,codecashier,dateaffectationcash,createdbyCash) VALUES(:id,:codeC,:date,:idcreatedby)');
					$resultats->execute(array(
					'id'=>$ligne->id_u,
					'codeC'=>$_POST['coderec'],
					'date'=>$_POST['anAd'],
					'idcreatedby'=>$id_createdby
					))or die( print_r($connexion->errorInfo()));
					
					
					$resultats=$connexion->prepare('UPDATE receptionistes SET codeC=1 WHERE codereceptio=:modifierRec');
					$resultats->execute(array(
					'modifierRec'=>$_POST['coderec']
					))or die( print_r($connexion->errorInfo()));
					
					
				}
				
				
			}
			createSN('R');
			
			
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:receptionistes1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:receptionistes1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:receptionistes1.php");
				}
			}
			
		}
		
		
		if($_POST['profil']=="Caissier" OR $_POST['profil']=="Cashier")
		{
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('INSERT INTO cashiers (id_u,codecashier,dateaffectationcash,createdbyCash) VALUES(:id,:codeC,:date,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeC'=>$_POST['codecash'],
				'date'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
				
			
				if(isset($_POST['checkRec']))
				{
					$resultats=$connexion->prepare('INSERT INTO receptionistes (id_u,codereceptio,dateaffectationreceptio,createdbyRec) VALUES(:id,:codeR,:date,:idcreatedby)');
					$resultats->execute(array(
					'id'=>$ligne->id_u,
					'codeR'=>$_POST['codecash'],
					'date'=>$_POST['anAd'],
					'idcreatedby'=>$id_createdby
					))or die( print_r($connexion->errorInfo()));
					

					
					$resultats=$connexion->prepare('UPDATE cashiers SET codeR=1 WHERE codecashier=:modifierCash');
					$resultats->execute(array(
					'modifierCash'=>$_POST['codecash']
					))or die( print_r($connexion->errorInfo()));
					
				}
				
			}
			createSN('C');
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:caissiers1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:caissiers1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:caissiers1.php");
				}
			}
		}
		
		
		if($_POST['profil']=="Auditeur" OR $_POST['profil']=="Auditor")
		{
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('INSERT INTO auditors (id_u,codeaudit,dateaffectationaudit,createdbyAudit) VALUES(:id,:codeA,:date,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeA'=>$_POST['codeaudit'],
				'date'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
				
			}
			createSN('A');
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:auditeurs1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:auditeurs1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:auditeurs1.php");
				}
			}
			
		}
		
		
		if($_POST['profil']=="Comptable" OR $_POST['profil']=="Accountant")
		{
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('INSERT INTO accountants (id_u,codeaccount,dateaffectationaccount,createdbyAcc) VALUES(:id,:codeAcc,:date,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeAcc'=>$_POST['codeacc'],
				'date'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
				
			}
			createSN('B');
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:comptables1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:comptables1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:comptables1.php");
				}
			}	
		}
		
		if($_POST['profil']=="Coordinateur" OR $_POST['profil']=="Manager")
		{
			while($ligne=$res->fetch())
			{
				
				$resultats=$connexion->prepare('INSERT INTO coordinateurs (id_u,codecoordi,dateaffectationcoordi,createdbyCoord) VALUES(:id,:codeC,:date,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeC'=>$_POST['codecoord'],
				'date'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
				
			}
			createSN('M');
			
				
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:coordinateurs1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:coordinateurs1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:coordinateurs1.php");
				}
			}
				
		}
		
		if($_POST['profil']=="Patient")
		{
		
			$anneeNaiss=$_POST['annee'];
			$moisNaiss=$_POST['mois'];
			$jourNaiss=$_POST['jours'];
			$dateNaiss=$anneeNaiss.'-'.$moisNaiss.'-'.$jourNaiss;
			$profession=$_POST['profession'];
			
			$assurance=$_POST['assurance'];
			
			if($assurance!=1)
			{
				if(isset($_POST['bill']))
				{
					$bill=$_POST['bill'];
				}else{
					$bill=0;
				}
			}else{
				$bill=100;
			}
			
			if(isset($_POST['cardIDassurance']))
			{
				$cardIDassurance=$_POST['cardIDassurance'];
			}else{
				$cardIDassurance="";
			}
			
			$numeropolice=$_POST['numeropolice'];
			
			if($sexe=='M')
			{
				if($_POST['adherent']=='Lui-même' OR $_POST['adherent']=='')
				{
					$adherent='Lui-même';
				}else{
					$adherent=$_POST['adherent'];
				}
			}else{
				if($sexe=='F')
				{
					if($_POST['adherent']=='Elle-même' OR $_POST['adherent']=='')
					{
						$adherent='Elle-même';
					}else{
						$adherent=$_POST['adherent'];
					}
				}
			}

			
			
			
			$resultatsP=$connexion->prepare('SELECT * FROM patients p, utilisateurs u WHERE u.nom_u=:nom AND u.prenom_u=:prenom AND p.date_naissance=:dateNaiss AND u.telephone=:phone');
			$resultatsP->execute(array(
			'nom'=>$nom,
			'prenom'=>$prenom,
			'dateNaiss'=>$dateNaiss,
			'phone'=>$phone
			));
			
			$resultatsP->setFetchMode(PDO::FETCH_OBJ);
			
			$comptidPa=$resultatsP->rowCount();
			
			
			if($comptidPa==0)
			{			
				$insertUti=$connexion->prepare('INSERT INTO utilisateurs (nom_u,prenom_u,full_name,sexe,province,autreadresse,telephone,e_mail,password,status,district,secteur) VALUES(:nom, :prenom, :fullname, :sexe, :province, :adresseExt, :phone, :e_mail, :password,:status,:distict,:secteur)');
				
				$insertUti->execute(array(
				'nom'=>$nom,
				'prenom'=>$prenom,
				'fullname'=>$fullname,
				'sexe'=>$sexe,
				'province'=>$province,
				'adresseExt'=>$adresseExt,
				'phone'=>$phone,
				'e_mail'=>$mail,
				'password'=>$password,
				'status'=>$status,
				'distict'=>$distict,
				'secteur'=>$secteur
				)); 
		
				// echo $ligne->id_u."<br/>".$dateNaiss."<br/>".$profession."<br/>".$nomPic."<br/>".$_POST['anAd']."<br/>".$_POST['num'];
			
				$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
				$res->setFetchMode(PDO::FETCH_OBJ);					
				
				if($ligne=$res->fetch())
				{
				
					$resultats=$connexion->prepare('INSERT INTO patients (id_u,numero,reference_id,anneeadhesion,profession,anneeNaiss,moisNaiss,jourNaiss,date_naissance,bill,id_assurance,carteassuranceid,numeropolice,adherent,createdbyPa) VALUES(:id,:numero,:referenceid,:annee,:profess,:anneeNaiss,:moisNaiss,:jourNaiss,:dateNaiss,:bill,:assurance,:cardIDassurance,:numeropolice,:adherent,:idcreatedby)');
					$resultats->execute(array(
					'id'=>$ligne->id_u,
					'numero'=>createSN('P'),
					'referenceid'=>$_POST['referenceid'],
					'annee'=>$_POST['anAd'],
					'profess'=>$profession,
					'anneeNaiss'=>$anneeNaiss,
					'moisNaiss'=>$moisNaiss,
					'jourNaiss'=>$jourNaiss,
					'dateNaiss'=>$dateNaiss,
					'bill'=>$bill,
					'assurance'=>$assurance,
					'cardIDassurance'=>$cardIDassurance,
					'numeropolice'=>$numeropolice,
					'adherent'=>$adherent,
					'idcreatedby'=>$id_createdby
					
					))or die( print_r($connexion->errorInfo()));
				
				
					if(isset($_SESSION['infhosp']))
					{
						$getSNpatient=$connexion->prepare("SELECT *FROM patients p WHERE p.id_u=:id");
						$getSNpatient->execute(array(
						'id'=>$ligne->id_u,
						
						))or die( print_r($connexion->errorInfo()));
						
						$getSNpatient->setFetchMode(PDO::FETCH_OBJ);

						if($lignePa=$getSNpatient->fetch())
						{
							$pahosp="hospForm.php?idInf=".$_SESSION['id']."&num=".$lignePa->numero."";
						}
					}else{
						$getSNpatient=$connexion->prepare("SELECT *FROM patients p WHERE p.id_u=:id");
						$getSNpatient->execute(array(
						'id'=>$ligne->id_u,
						
						))or die( print_r($connexion->errorInfo()));
						
						$getSNpatient->setFetchMode(PDO::FETCH_OBJ);

						if($lignePa=$getSNpatient->fetch())
						{
							$pahosp="patients1.php?iduti=".$_SESSION['id']."&numPa=".$lignePa->numero."&fullname=".$fullname."&divPa=ok";
						}
					}
				
				
					if(isset($_GET['english']))
					{
						$getSNpatient=$connexion->prepare("SELECT *FROM patients p WHERE p.id_u=:id");
						$getSNpatient->execute(array(
						'id'=>$ligne->id_u,
						
						))or die( print_r($connexion->errorInfo()));
						
						$getSNpatient->setFetchMode(PDO::FETCH_OBJ);

						if($lignePa=$getSNpatient->fetch())
						{
							echo '<script type="text/javascript"> alert("Nom du patient: '.$fullname.'\n S/N: '.$lignePa->numero.'\n");</script>';
						
							echo '<script type="text/javascript">document.location.href="'.$pahosp.'&english='.$_GET['english'].'&receptioniste=ok"</script>';
							
							// header('Location:'.$pahosp.'&english='.$_GET['english'].'&receptioniste=ok');
						}
						
					}else{
						if(isset($_GET['francais']))
						{
							$getSNpatient=$connexion->prepare("SELECT *FROM patients p WHERE p.id_u=:id");
							$getSNpatient->execute(array(
							'id'=>$ligne->id_u,
							
							))or die( print_r($connexion->errorInfo()));
								
							$getSNpatient->setFetchMode(PDO::FETCH_OBJ);

							if($lignePa=$getSNpatient->fetch())
							{
								echo '<script type="text/javascript"> alert("Nom du patient: '.$fullname.'\n S/N: '.$lignePa->numero.'\n");</script>';
							
								echo '<script type="text/javascript">document.location.href="'.$pahosp.'&francais='.$_GET['francais'].'&receptioniste=ok"</script>';
							
								// header('Location:'.$pahosp.'&francais='.$_GET['francais'].'&receptioniste=ok');
							}
							
						}else{
							
							$getSNpatient=$connexion->prepare("SELECT *FROM patients p WHERE p.id_u=:id");
							$getSNpatient->execute(array(
							'id'=>$ligne->id_u,
							
							))or die( print_r($connexion->errorInfo()));
							
							$getSNpatient->setFetchMode(PDO::FETCH_OBJ);

							if($lignePa=$getSNpatient->fetch())
							{
								echo '<script type="text/javascript"> alert("Nom du patient: '.$fullname.'\n S/N: '.$lignePa->numero.'\n");</script>';
							
								echo '<script type="text/javascript">document.location.href="'.$pahosp.'&receptioniste=ok"</script>';
							
								// header('Location:'.$pahosp.'&receptioniste=ok');
							}
						}
					}			
						
				}	
			}else{
				
				if($lignePa=$resultatsP->fetch())
				{
					// echo $comptidPa.'----'.$lignePa->id_u.' = '.$lignePa->full_name.'__'.$lignePa->telephone.' : '.$lignePa->date_naissance.'<br/>';
				
					$pahosp="patients1.php?iduti=".$lignePa->id_u."&numPa=".$lignePa->numero."&fullname=".$fullname."&divPa=ok";
				
					echo '<script type="text/javascript"> alert("Nom du patient : '.$fullname.'\n Telephone : '.$lignePa->telephone.'\n Né le : '.$lignePa->date_naissance.'\n\n Existe deja\n");</script>';
					
					echo '<script type="text/javascript">document.location.href="'.$pahosp.'&receptioniste=ok"</script>';
				} 
			}			
		}

	}
	
	if(isset($_POST['updatebtn']))
	{
		$newNom=ucwords(strtolower(strip_tags($_POST['nom'])));
		$newPrenom=ucwords(strtolower(strip_tags($_POST['prenom'])));
		$newFullname=$newNom.' '.$newPrenom;
		$newSexe=$_POST['sexe'];
		$newProvince=strip_tags($_POST['province']);
			
		if($_POST['province']==6)
		{
			$newAdresseExt=strip_tags($_POST['adresseExt']);
		}else{
			$newAdresseExt="";
		}	
		if(isset($_POST['district']))
		{
			$newDistrict=strip_tags($_POST['district']);
		}else{
			$newDistrict="";
		}	
		if(($_POST['secteur']))
		{
			$newSecteur=strip_tags($_POST['secteur']);
		}else{
			$newSecteur="";
		}
		$newDistrict=strip_tags($_POST['district']);
		$newSecteur=strip_tags($_POST['secteur']);
		$newPhone=strip_tags($_POST['phone']);
		$newMail=strip_tags($_POST['mail']);
		
		if($_POST['profil']!="Patient")
		{
			$newPassword=strip_tags($_POST['password']);
		}else{
			$newPassword="";
		}
		
		$newAnAd=$_POST['anAd'];
		$modifierUti=$_POST['idopere'];
		$id_updatedby=$_SESSION['id'];

		$resultats=$connexion->query("SELECT id_u FROM utilisateurs WHERE id_u='$modifierUti'");
		
		//$num_rows=$resultats->fetchColumn();
		$num_rows=$resultats->rowCount();
		
		if( $num_rows != 0)
		{
			$resultats=$connexion->prepare('UPDATE utilisateurs SET nom_u=:nom, prenom_u=:prenom, full_name=:fullname, sexe=:sexe, province=:province, autreadresse=:adresseExt, telephone=:phone, e_mail=:mail, password=:password, district=:district, secteur=:secteur, updatedby=:idupdatedby WHERE id_u=:iduti');
			
			$resultats->execute(array(
			'iduti'=>$modifierUti,
			'nom'=>$newNom,
			'prenom'=>$newPrenom,
			'fullname'=>$newFullname,
			'sexe'=>$newSexe,
			'province'=>$newProvince,
			'adresseExt'=>$newAdresseExt,
			'phone'=>$newPhone,
			'mail'=>$newMail,
			'password'=>$newPassword,
			'district'=>$newDistrict,
			'secteur'=>$newSecteur,
			'idupdatedby'=>$id_updatedby
			))or die( print_r($connexion->errorInfo()));		
		}


		if($_POST['profil']=="Doctor" OR $_POST['profil']=="Medecin")
		{
			$codeD=$_POST['codedoc'];
			$newGrade=$_POST['grade'];
						
			if(isset($_POST['categopresta']))
			{
				$newServ=$_POST['categopresta'];
			}
			
			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
							
			$res->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('UPDATE medecins SET id_u=:iduti, codemedecin=:codeD, id_grade=:grade WHERE id_u=:iduti');
				$resultats->execute(array(
				'iduti'=>$modifierUti,
				'codeD'=>$codeD,
				'grade'=>$newGrade
				))or die( print_r($connexion->errorInfo()));

				if(isset($_POST['categopresta']))
				{
					$result=$connexion->prepare('UPDATE servicemed SET dateaffectationmed=:date, codemedecin=:code, id_categopresta=:idserv WHERE codemedecin=:code');
					$result->execute(array(
					'date'=>$newAnAd,
					'code'=>$codeD,
					'idserv'=>$newServ
					))or die( print_r($connexion->errorInfo()));
				
				}			
			}
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:medecins1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:medecins1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:medecins1.php");
				}
			}
			
		}
		
		if($_POST['profil']=="Patient")
		{
			$newNum=$_POST['num'];
			$newReferenceid=$_POST['referenceid'];
			$newAnneeNaiss=$_POST['annee'];
			$newMoisNaiss=$_POST['mois'];
			$newJourNaiss=$_POST['jours'];
			$newDateNaiss=$newAnneeNaiss.'-'.$newMoisNaiss.'-'.$newJourNaiss;
			$newProfession=$_POST['profession'];
			$newAssurance=$_POST['assurance'];
			
			if($newAssurance!=1)
			{
				if(isset($_POST['bill']))
				{
					$newBill=$_POST['bill'];
				}else{
					$newBill=0;
				}
			}else{
				$newBill=100;
			}
			
			$newCardIDassurance=$_POST['cardIDassurance'];
			$newNumeropolice=$_POST['numeropolice'];
					
			if($newSexe=='M')
			{
				if($_POST['adherent']=='Lui-même' OR $_POST['adherent']=='')
				{
					$newAdherent='Lui-même';
				}else{
					$newAdherent=$_POST['adherent'];
				}
			}else{
				if($newSexe=='F')
				{
					if($_POST['adherent']=='Elle-même' OR $_POST['adherent']=='')
					{
						$newAdherent='Elle-même';
					}else{
						$newAdherent=$_POST['adherent'];
					}
				}
			}

			$modifierUti=$_POST['idopere'];
			
				
			$resultats=$connexion->prepare('UPDATE patients SET numero=:num, reference_id=:referenceid, anneeadhesion=:annee, profession=:profession,anneeNaiss=:anneeNaiss,moisNaiss=:moisNaiss,jourNaiss=:jourNaiss,date_naissance=:dateNaiss,id_assurance=:assurance,bill=:bill,carteassuranceid=:cardIDassurance,numeropolice=:numeropolice,adherent=:adherent WHERE id_u=:iduti ');
			$resultats->execute(array(
			'num'=>$newNum,
			'referenceid'=>$newReferenceid,
			'annee'=>$newAnAd,
			'profession'=>$newProfession,
			'anneeNaiss'=>$newAnneeNaiss,
			'moisNaiss'=>$newMoisNaiss,
			'jourNaiss'=>$newJourNaiss,
			'dateNaiss'=>$newDateNaiss,
			'assurance'=>$newAssurance,
			'bill'=>$newBill,
			'cardIDassurance'=>$newCardIDassurance,
			'numeropolice'=>$newNumeropolice,
			'adherent'=>$newAdherent,
			'iduti'=>$modifierUti
			));
		
			
/* 
			echo '<script type="text/javascript"> alert("- NewCardIDassurance : '.$newCardIDassurance.'\n - NewNumeropolice :'.$newNumeropolice.'\n - NewAdherent :'.$newAdherent.'\n - ModifierUti :'.$modifierUti.'");</script>';
			 */
			
			
			if(isset($_SESSION['infhosp']))
			{
				$pahosp="hospForm.php?idInf=".$_SESSION['id']."&num=".$newNum."";
			}else{
				$pahosp="patients1.php?iduti=".$_SESSION['id']."&numPa=".$newNum."&fullname=".$newFullname."&divPa=ok";
			}
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:'.$pahosp.'&english='.$_GET['english'].'&receptioniste=ok');				
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:'.$pahosp.'&francais='.$_GET['francais'].'&receptioniste=ok');
					
				}else{
					header('Location:'.$pahosp.'&receptioniste=ok');
				}
			}	
								
		}
		
		if($_POST['profil']=="Nurse" OR $_POST['profil']=="Infirmier")
		{
			$codeI=$_POST['codeinf'];

			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
							
			$res->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('UPDATE infirmiers SET id_u=:iduti,codeinfirmier=:codeInf,dateaffectationinf=:dateAff WHERE id_u=:iduti');
				$resultats->execute(array(
				'iduti'=>$modifierUti,
				'codeInf'=>$codeI,
				'dateAff'=>$newAnAd
				))or die( print_r($connexion->errorInfo()));
	
	
				if(isset($_POST['hosp']) AND isset($_POST['clinik']))
				{
					
					$resultats=$connexion->prepare('UPDATE infirmiers SET inf_hosp=2 WHERE codeinfirmier=:modifierInf');
					$resultats->execute(array(
					'modifierInf'=>$_POST['codeinf']
					))or die( print_r($connexion->errorInfo()));
					
				}else{
					if(isset($_POST['hosp']) AND !isset($_POST['clinik']))
					{
						
						$resultats=$connexion->prepare('UPDATE infirmiers SET inf_hosp=1 WHERE codeinfirmier=:modifierInf');
						$resultats->execute(array(
						'modifierInf'=>$_POST['codeinf']
						))or die( print_r($connexion->errorInfo()));
						
					}else{
						if(!isset($_POST['hosp']) AND isset($_POST['clinik']))
						{
							
							$resultats=$connexion->prepare('UPDATE infirmiers SET inf_hosp=0 WHERE codeinfirmier=:modifierInf');
							$resultats->execute(array(
							'modifierInf'=>$_POST['codeinf']
							))or die( print_r($connexion->errorInfo()));
							
						}
					}
				}
				
			}
			
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:infirmiers1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:infirmiers1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:infirmiers1.php");
				}
			}
			
		}
		
		if($_POST['profil']=="Laboratory Technician" OR $_POST['profil']=="Laborantin")
		{
			$codeL=$_POST['codelab'];

			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
							
			$res->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('UPDATE laborantins SET id_u=:iduti,codelabo=:codeLab,dateaffectationlabo=:dateAff WHERE id_u=:iduti');
				$resultats->execute(array(
				'iduti'=>$modifierUti,
				'codeLab'=>$codeL,
				'dateAff'=>$newAnAd
				))or die( print_r($connexion->errorInfo()));
	
			}
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:laborantins1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:laborantins1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:laborantins1.php");
				}
			}	
		}
		
		if($_POST['profil']=="Radiologue" OR $_POST['profil']=="Radiologue")
		{
			$codeX=$_POST['coderad'];

			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
							
			$res->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('UPDATE radiologues SET id_u=:iduti,coderadio=:codeRad,dateaffectationradio=:dateAff WHERE id_u=:iduti');
				$resultats->execute(array(
				'iduti'=>$modifierUti,
				'codeRad'=>$codeX,
				'dateAff'=>$newAnAd
				))or die( print_r($connexion->errorInfo()));
			}
			createSN('X');
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:radiologues1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:radiologues1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:radiologues1.php");
				}
			}
		}
		
		if($_POST['profil']=="Receptioniste" OR $_POST['profil']=="Receptionist")
		{

			$codeR=$_POST['coderec'];

			$resultats=$connexion->prepare('UPDATE receptionistes SET id_u=:iduti,codereceptio=:coderec,dateaffectationreceptio=:dateAff WHERE id_u=:iduti');
			$resultats->execute(array(
			'iduti'=>$modifierUti,
			'coderec'=>$codeR,
			'dateAff'=>$newAnAd
			))or die( print_r($connexion->errorInfo()));
				
		
			if(isset($_POST['checkCashRec']))
			{
				$res=$connexion->prepare('SELECT *FROM cashiers c WHERE c.codecashier=:code');
				$res->execute(array(
				'code'=>$_POST['coderec']
				))or die( print_r($connexion->errorInfo()));
				
				$res->setFetchMode(PDO::FETCH_OBJ);
				
				$comptCodeRes=$res->rowCount();
				
				
				if($comptCodeRes!=0)
				{
					$resultats=$connexion->prepare('UPDATE cashiers SET id_u=:iduti,codecashier=:codecash,dateaffectationcash=:dateAff WHERE id_u=:iduti');
					$resultats->execute(array(
					'iduti'=>$modifierUti,
					'codecash'=>$_POST['coderec'],
					'dateAff'=>$newAnAd
					))or die( print_r($connexion->errorInfo()));
					
					$resultats->setFetchMode(PDO::FETCH_OBJ);
				
				}else{
		
					$resultats=$connexion->prepare('INSERT INTO cashiers (id_u,codecashier,dateaffectationcash,createdbyRec) VALUES(:id,:codeC,:date,:idcreatedby)');
					$resultats->execute(array(
					'id'=>$modifierUti,
					'codeC'=>$_POST['coderec'],
					'date'=>$_POST['anAd'],
					'idcreatedby'=>$_SESSION['id']
					))or die( print_r($connexion->errorInfo()));
					
					$resultats->setFetchMode(PDO::FETCH_OBJ);
				}

				
				$updateCash=$connexion->prepare('UPDATE receptionistes SET codeC=1 WHERE codereceptio=:modifierRec');
				$updateCash->execute(array(
				'modifierRec'=>$_POST['coderec']
				))or die( print_r($connexion->errorInfo()));
				
				$updateCash->setFetchMode(PDO::FETCH_OBJ);
				
			}else{
				
				if(isset($_POST['checkCashier']))
				{
					$insertNewRec=$connexion->prepare('INSERT INTO cashiers (id_u,codecashier,dateaffectationcash) VALUES(:id,:codeC,:date)');
					$insertNewRec->execute(array(
					'id'=>$modifierUti,
					'codeC'=>$codeR,
					'date'=>$_POST['anAd']
					))or die( print_r($connexion->errorInfo()));
					
					
					$updateCash=$connexion->prepare('UPDATE receptionistes SET codeC=1 WHERE codereceptio=:modifierRec');
					$updateCash->execute(array(
					'modifierRec'=>$codeR
					))or die( print_r($connexion->errorInfo()));
					
					// echo '<script type="text/javascript"> alert("'.$ligne->id_u.'\n'.$codeR.'\n'.$_POST['anAd'].'\n");</script>';
		
				}else{
				
					$resultats=$connexion->prepare('DELETE FROM cashiers WHERE id_u=:id AND codeR=:codeR');
					$resultats->execute(array(
					'id'=>$modifierUti,
					'codeR'=>$_POST['coderec']
					))or die( print_r($connexion->errorInfo()));
					

					$updateCashier=$connexion->prepare('UPDATE receptionistes SET codeC=0 WHERE codereceptio=:modifierRec');
					$updateCashier->execute(array(
					'modifierRec'=>$_POST['coderec']
					))or die( print_r($connexion->errorInfo()));
				
				}
			}
			
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:receptionistes1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:receptionistes1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:receptionistes1.php");
				}
			}	
			
		}
					
		if($_POST['profil']=="Caissier" OR $_POST['profil']=="Cashier")
		{
			
			if(isset($_POST['codereccash']))
			{
				$codeC=$_POST['codereccash'];
			}else{
				$codeC=$_POST['codecash'];
			}	

			
			$resultats=$connexion->prepare('UPDATE cashiers SET id_u=:iduti,codecashier=:codeCash,dateaffectationcash=:dateAff WHERE id_u=:iduti');
			$resultats->execute(array(
			'iduti'=>$modifierUti,
			'codeCash'=>$codeC,
			'dateAff'=>$newAnAd
			))or die( print_r($connexion->errorInfo()));
			
			
				
			if(isset($_POST['checkRecCash']))
			{
				$res=$connexion->prepare('SELECT *FROM receptionistes r WHERE r.codereceptio=:code');
				$res->execute(array(
				'code'=>$_POST['codecash']
				))or die( print_r($connexion->errorInfo()));
				
				$res->setFetchMode(PDO::FETCH_OBJ);
				
				$comptCodeRes=$res->rowCount();
				
				
				if($comptCodeRes!=0)
				{
					$resultats=$connexion->prepare('UPDATE receptionistes SET id_u=:iduti,codereceptio=:coderec,dateaffectationreceptio=:dateAff WHERE id_u=:iduti');
					$resultats->execute(array(
					'iduti'=>$modifierUti,
					'coderec'=>$_POST['codecash'],
					'dateAff'=>$newAnAd
					))or die( print_r($connexion->errorInfo()));
					
					$resultats->setFetchMode(PDO::FETCH_OBJ);
				
				}else{
		
					$resultats=$connexion->prepare('INSERT INTO receptionistes (id_u,codereceptio,dateaffectationreceptio,createdbyRec) VALUES(:id,:codeR,:date,:idcreatedby)');
					$resultats->execute(array(
					'id'=>$modifierUti,
					'codeR'=>$_POST['codecash'],
					'date'=>$_POST['anAd'],
					'idcreatedby'=>$_SESSION['id']
					))or die( print_r($connexion->errorInfo()));
					
					$resultats->setFetchMode(PDO::FETCH_OBJ);
				}

				
				$updateCash=$connexion->prepare('UPDATE cashiers SET codeR=1 WHERE codecashier=:modifierCash');
				$updateCash->execute(array(
				'modifierCash'=>$_POST['codecash']
				))or die( print_r($connexion->errorInfo()));
				
				$updateCash->setFetchMode(PDO::FETCH_OBJ);
				
			}else{
				
				if(isset($_POST['checkRec']))
				{
					$insertNewRec=$connexion->prepare('INSERT INTO receptionistes (id_u,codereceptio,dateaffectationreceptio) VALUES(:id,:codeR,:date)');
					$insertNewRec->execute(array(
					'id'=>$modifierUti,
					'codeR'=>$codeC,
					'date'=>$_POST['anAd']
					))or die( print_r($connexion->errorInfo()));
					
					
					$updateCash=$connexion->prepare('UPDATE cashiers SET codeR=1 WHERE codecashier=:modifierCash');
					$updateCash->execute(array(
					'modifierCash'=>$codeC
					))or die( print_r($connexion->errorInfo()));
					
					// echo '<script type="text/javascript"> alert("'.$ligne->id_u.'\n'.$codeC.'\n'.$_POST['anAd'].'\n");</script>';
		
				}else{
				
					$resultats=$connexion->prepare('DELETE FROM receptionistes WHERE id_u=:id AND codeC=:codeC');
					$resultats->execute(array(
					'id'=>$modifierUti,
					'codeC'=>$_POST['codecash']
					))or die( print_r($connexion->errorInfo()));
					

					$updateCashier=$connexion->prepare('UPDATE cashiers SET codeR=0 WHERE codecashier=:modifierCash');
					$updateCashier->execute(array(
					'modifierCash'=>$_POST['codecash']
					))or die( print_r($connexion->errorInfo()));
				
				}
			}
			
			
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:caissiers1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:caissiers1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:caissiers1.php");
				}
			}	
		}
		
		
		if($_POST['profil']=="Auditeur" OR $_POST['profil']=="Auditor")
		{
			$codeA=$_POST['codeaudit'];

			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');

			$res->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('UPDATE auditors SET id_u=:iduti,codeaudit=:codeAudit,dateaffectationaudit=:dateAff WHERE id_u=:iduti');
				$resultats->execute(array(
				'iduti'=>$modifierUti,
				'codeAudit'=>$codeA,
				'dateAff'=>$newAnAd
				))or die( print_r($connexion->errorInfo()));
	
			}
			
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:auditeurs1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:auditeurs1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:auditeurs1.php");
				}
			}	
		}
		
		
		if($_POST['profil']=="Comptable" OR $_POST['profil']=="Accountant")
		{
			$codeAc=$_POST['codeacc'];

			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
							
			$res->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('UPDATE auditors SET id_u=:iduti,codeaccount=:codeAcc,dateaffectationaccount=:dateAff WHERE id_u=:iduti');
				$resultats->execute(array(
				'iduti'=>$modifierUti,
				'codeAcc'=>$codeAc,
				'dateAff'=>$newAnAd
				))or die( print_r($connexion->errorInfo()));
	
			}
			
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:comptables1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:comptables1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:comptables1.php");
				}
			}
		}
		
		if($_POST['profil']=="Coordinateur" OR $_POST['profil']=="Manager")
		{
			$codeM=$_POST['codecoord'];

			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
							
			$res->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('UPDATE coordinateurs SET codecoordi=:codeCoord,dateaffectationcoordi=:dateAff WHERE id_u=:iduti');
				$resultats->execute(array(
				'iduti'=>$modifierUti,
				'codeCoord'=>$codeM,
				'dateAff'=>$newAnAd
				))or die( print_r($connexion->errorInfo()));
	
			}
			echo 'aaa';
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:coordinateurs1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:coordinateurs1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:coordinateurs1.php");
				}
			}				
		
		}
		
	}
	
	if(isset($_POST['showbtn']))
	{
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			header('Location:utilisateurs1.php?english='.$_GET['english'].'');						
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				header('Location:utilisateurs1.php?francais='.$_GET['francais'].'');
				
			}else{
				header("Location:utilisateurs1.php");
			}
		}	
	}
}

catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}

?>