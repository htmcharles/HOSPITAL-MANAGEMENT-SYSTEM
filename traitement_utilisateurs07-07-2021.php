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
		$sqlO=$connexion->query("SELECT *FROM orthopedistes o WHERE o.id_u='$modifierId'");
		$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$modifierId'");
		$sqlS=$connexion->query("SELECT *FROM stockkeeper S WHERE S.id_u='$modifierId'");

		$comptidP=$sqlP->rowCount();
		$comptidD=$sqlD->rowCount();
		$comptidI=$sqlI->rowCount();
		$comptidL=$sqlL->rowCount();
		$comptidX=$sqlX->rowCount();
		$comptidR=$sqlR->rowCount();
		$comptidC=$sqlC->rowCount();
		$comptidA=$sqlA->rowCount();
		$comptidAcc=$sqlAcc->rowCount();
		$comptidO=$sqlO->rowCount();
		$comptidM=$sqlM->rowCount();
		$comptidS=$sqlS->rowCount();
		
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
		
		if($comptidO!=0)
		{
			if(isset($_GET['divOrtho']))
			{
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divOrtho=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divOrtho=ok');						
						
					}else{
						header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&divOrtho=ok');						
					}
				}
				
			}else{
			
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'');						
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

		if($comptidS!=0)
		{
			if(isset($_GET['divSto']))
			{				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divCoord=ok');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divCoord=ok');						
						
					}else{
						header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&divCoord=ok');						
					}
				}
				
			}else{
				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
						
					}else{
						header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'');						
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
			$sqlA=$connexion->query("SELECT *FROM accountants acc WHERE acc.id_u='$modifierId'");
			$sqlAcc=$connexion->query("SELECT *FROM accountants acc WHERE acc.id_u='$modifierId'");
			$sqlO=$connexion->query("SELECT *FROM orthopedistes o WHERE o.id_u='$modifierId'");
			$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$modifierId'");
			$sqlS=$connexion->query("SELECT *FROM stockkeeper S WHERE S.id_u='$modifierId'");

			$comptidP=$sqlP->rowCount();
			$comptidD=$sqlD->rowCount();
			$comptidI=$sqlI->rowCount();
			$comptidL=$sqlL->rowCount();
			$comptidX=$sqlX->rowCount();
			$comptidR=$sqlR->rowCount();
			$comptidC=$sqlC->rowCount();
			$comptidA=$sqlA->rowCount();
			$comptidAcc=$sqlAcc->rowCount();
			$comptidO=$sqlO->rowCount();
			$comptidM=$sqlM->rowCount();
			$comptidS=$sqlS->rowCount();
			

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
		
			if($comptidO!=0)
			{
				if(isset($_GET['divOrtho']))
				{
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divOrtho=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divOrtho=ok');						
							
						}else{
							header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&divOrtho=ok');						
						}
					}
					
				}else{
				
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:orthopedistes1.php?page='.$page.'&iduti='.$modifierId.'');						
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

			if($comptidS!=0)
			{
				if(isset($_GET['divSto']))
				{
					
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'&divCoord=ok');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'&divCoord=ok');						
							
						}else{
							header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&divCoord=ok');						
						}
					}
					
				}else{
					
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&english='.$_GET['english'].'');						
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'&francais='.$_GET['francais'].'');						
							
						}else{
							header('Location:stockkeeper.php?page='.$page.'&iduti='.$modifierId.'');						
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
		
		if(isset($_POST['dataM']))
		{
			$dataM=1;
		}else{
			$dataM=NULL;
		}
		
		$status=1;
		

		// echo $nom.'__'.$password;
		
		
		if($_POST['profil']!="Patient")
		{
			$resultats=$connexion->prepare('INSERT INTO utilisateurs (nom_u,prenom_u,full_name,sexe,province,autreadresse,telephone,e_mail,password,datamanager,status,district,secteur) VALUES(:nom, :prenom, :fullname, :sexe, :province, :adresseExt, :phone, :e_mail, :password,:dataM,:status,:distict,:secteur)');
			
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
			'dataM'=>$dataM,
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
				));


				//echo "string1=".$_POST['anAd']."@string1=".$codeD."@string3 =".$categopre;

				$result=$connexion->prepare('INSERT INTO servicemed (dateaffectationmed,codemedecin,id_categopresta) VALUES(:dates,:code,:categopre)');
				$result->execute(array(
				'dates'=>$_POST['anAd'],
				'code'=>$codeD,
				'categopre'=>$categopre
				));
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
		
		
		if($_POST['profil']=="Orthopediste" OR $_POST['profil']=="Orthopedist")
		{
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('INSERT INTO OrthopedisteS (id_u,codeortho,dateaffectationortho,createdbyOrt) VALUES(:id,:codeO,:date,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeO'=>$_POST['codeortho'],
				'date'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
								
			}
			createSN('O');
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:orthopedistes1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:orthopedistes1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:orthopedistes1.php");
				}
			}
			
		}
		
		if($_POST['profil']=="Coordinateur" OR $_POST['profil']=="Manager" OR $_POST['profil']=="Gérant")
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


		if($_POST['profil']=="Stockepper" OR $_POST['profil']=="Gerar de Stock")
		{
			while($ligne=$res->fetch())
			{
				
				$resultats=$connexion->prepare('INSERT INTO stockkeeper (id_u,codestock,dateaffectionstock,createdbystock) VALUES(:id,:codeS,:dateS,:idcreatedby)');
				$resultats->execute(array(
				'id'=>$ligne->id_u,
				'codeS'=>$_POST['codesto'],
				'dateS'=>$_POST['anAd'],
				'idcreatedby'=>$id_createdby
				))or die( print_r($connexion->errorInfo()));
				
			}
			createSN('S');
			
				
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:stockkeeper.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:stockkeeper.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:stockkeeper.php");
				}
			}
				
		}
		
		if($_POST['profil']=="Patient")
		{
		
			$anneeNaiss=$_POST['annee'];
			$moisNaiss=$_POST['mois'];
			$jourNaiss=$_POST['jours'];
			$reference_id = $_POST['referenceid'];
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
				echo $comptidPa;
				
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
				
					echo '<script type="text/javascript"> alert("Nom du patient : '.$fullname.'\n N° Reference : '.$lignePa->reference_id.'\n Telephone : '.$lignePa->telephone.'\n Né le : '.$lignePa->date_naissance.'\n\n Existe deja\n");</script>';
					
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
		
		if(isset($_POST['dataM']))
		{
			$newDataM=1;
		}else{
			$newDataM=NULL;
		}
		
		$newAnAd=$_POST['anAd'];
		$modifierUti=$_POST['idopere'];
		$id_updatedby=$_SESSION['id'];

		$resultats=$connexion->query("SELECT id_u FROM utilisateurs WHERE id_u='$modifierUti'");
		
		//$num_rows=$resultats->fetchColumn();
		$num_rows=$resultats->rowCount();
		
		if( $num_rows != 0)
		{
			$resultats=$connexion->prepare('UPDATE utilisateurs SET nom_u=:nom, prenom_u=:prenom, full_name=:fullname, sexe=:sexe, province=:province, autreadresse=:adresseExt, telephone=:phone, e_mail=:mail, password=:password, datamanager=:newDataM, district=:district, secteur=:secteur, updatedby=:idupdatedby WHERE id_u=:iduti');
			
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
			'newDataM'=>$newDataM,
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

			$seletIdassu = $connexion->prepare('SELECT * FROM patients WHERE id_u=:iduti');
			$seletIdassu->execute(array(
				'iduti'=>$modifierUti
			));
			$seletIdassu->setFetchMode(PDO::FETCH_OBJ);
			$ligneselectIdassu = $seletIdassu->fetch();

			if($ligneselectIdassu->id_assurance != $newAssurance)
			{
				//Select name of the old insurance
				$resultAssuOld=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu');
				$resultAssuOld->execute(array(
				'idassu'=>$ligneselectIdassu->id_assurance
				));
				
				$resultAssuOld->setFetchMode(PDO::FETCH_OBJ);

				$comptAssuOld=$resultAssuOld->rowCount();
				
				if($ligneAssuOld=$resultAssuOld->fetch())
				{
					$Oldnomassurance = $ligneAssuOld->nomassurance;
				}else{
					$Oldnomassurance = "";
				}

				//Select name of the new insurance
				$resultAssuNew=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu');
				$resultAssuNew->execute(array(
				'idassu'=>$newAssurance
				));
				
				$resultAssuNew->setFetchMode(PDO::FETCH_OBJ);

				$comptAssuNew=$resultAssuNew->rowCount();
				
				if($ligneAssuNew=$resultAssuNew->fetch())
				{
					$Newnomassurance = $ligneAssuNew->nomassurance;
				}else{
					$Newnomassurance = "";
				}

				$oldassuname =strtolower("prestations_".$Oldnomassurance);
				$newassuname =strtolower("prestations_".$Newnomassurance);

				//Regarder si s'il y'a une consultation qui
				$selectifconsu = $connexion->prepare('SELECT * FROM consultations WHERE numero=:numero AND id_factureConsult IS NULL AND codecashier="" ');
				$selectifconsu->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifconsu->setFetchMode(PDO::FETCH_OBJ);
				$rowConsu = $selectifconsu->rowCount();
				if ($rowConsu !=0) {
					while ($ligneselectifconsu = $selectifconsu->fetch()) {
						$id_consu = $ligneselectifconsu->id_consu;
						$id_typeconsult =  $ligneselectifconsu->id_typeconsult;
						//echo "id_consu ".$id_consu;
						//echo "id_typeconsult ".$id_typeconsult;
						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_typeconsult
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						//echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateconsu = $connexion->prepare('UPDATE consultations SET id_typeconsult=:id_typeconsult AND id_assuConsu=:id_assuConsu AND assuranceConsuName=:assurance AND insupercent=:insupercent WHERE id_consu=:id_consu ');
							$updateconsu->execute(array(
								'id_typeconsult'=>$new_id_presta,
								'id_assuConsu'=>$newAssurance,
								'assurance'=>$Newnomassurance,
								'insupercent'=>$newBill,
								'id_consu'=>$id_consu
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateconsu = $connexion->prepare('UPDATE consultations SET id_typeconsult=:id_typeconsult AND id_assuConsu=:id_assuConsu AND assuranceConsuName=:assurance AND insupercent=:insupercent WHERE id_consu=:id_consu');
							$updateconsu->execute(array(
								'id_typeconsult'=>$new_id_presta,
								'id_assuConsu'=>$newAssurance,
								'assurance'=>$Newnomassurance,
								'insupercent'=>$newBill,
								'id_consu'=>$id_consu
							));
						}
					}
				}


				//Regarder si s'il y'a une med_consom qui
				$selectifmedconsom = $connexion->prepare('SELECT * FROM med_consom WHERE numero=:numero AND id_factureConsom IS NULL AND codecashier="" ');
				$selectifmedconsom->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifmedconsom->setFetchMode(PDO::FETCH_OBJ);
				$rowmedconsom = $selectifmedconsom->rowCount();
				if ($rowmedconsom !=0) {
					while ($ligneselectifmedconsom = $selectifmedconsom->fetch()) {
						$id_medconsom =  $ligneselectifmedconsom->id_medconsom;
						$id_prestationConsom =  $ligneselectifmedconsom->id_prestationConsom;

						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_prestationConsom
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateconsu = $connexion->prepare('UPDATE med_consom SET id_prestationConsom=:id_prestationConsom AND id_assuConsom=:id_assuConsom AND insupercentConsom=:insupercent WHERE id_medconsom=:id_medconsom ');
							$updateconsu->execute(array(
								'id_prestationConsom'=>$new_id_presta,
								'id_assuConsom'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medconsom'=>$id_medconsom
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateconsu = $connexion->prepare('UPDATE med_consom SET id_prestationConsom=:id_prestationConsom AND id_assuConsom=:id_assuConsom AND insupercentConsom=:insupercent WHERE id_medconsom=:id_medconsom ');
							$updateconsu->execute(array(
								'id_prestationConsom'=>$new_id_presta,
								'id_assuConsom'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medconsom'=>$id_medconsom
							));
						}
					}
				}

				//Regarder si s'il y'a une med_consult qui
				$selectifmedConsu = $connexion->prepare('SELECT * FROM med_consult WHERE numero=:numero AND id_factureConsu IS NULL AND codecashier="" ');
				$selectifmedConsu->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifmedConsu->setFetchMode(PDO::FETCH_OBJ);
				$rowmedConsu = $selectifmedConsu->rowCount();
				if ($rowmedConsu !=0) {
					while ($ligneselectifmedConsu = $selectifmedConsu->fetch()) {
						$id_medconsu =  $ligneselectifmedConsu->id_medconsu;
						$id_prestationConsu =  $ligneselectifmedConsu->id_prestationConsu;

						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_prestationConsu
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateconsu = $connexion->prepare('UPDATE med_consult SET id_prestationConsu=:id_prestationConsu AND id_assuServ=:id_assuServ AND insupercentServ=:insupercent WHERE id_medconsu=:id_medconsu ');
							$updateconsu->execute(array(
								'id_prestationConsu'=>$new_id_presta,
								'id_assuServ'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medconsu'=>$id_medconsu
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateconsu = $connexion->prepare('UPDATE med_consult SET id_prestationConsu=:id_prestationConsu AND id_assuServ=:id_assuServ AND insupercentServ=:insupercent WHERE id_medconsu=:id_medconsu ');
							$updateconsu->execute(array(
								'id_prestationConsu'=>$new_id_presta,
								'id_assuServ'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medconsu'=>$id_medconsu
							));
						}
					}
				}


				//Regarder si s'il y'a une med_inf qui
				$selectifmedInf = $connexion->prepare('SELECT * FROM med_inf WHERE numero=:numero AND id_factureMedInf IS NULL AND codecashier="" ');
				$selectifmedInf->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifmedInf->setFetchMode(PDO::FETCH_OBJ);
				$rowmedInf = $selectifmedInf->rowCount();
				if ($rowmedInf !=0) {
					while ($ligneselectifmedInf = $selectifmedInf->fetch()) {
						$id_medinf =  $ligneselectifmedInf->id_medinf;
						$id_prestationInf =  $ligneselectifmedInf->id_prestation;

						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_prestationInf
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateInf = $connexion->prepare('UPDATE med_inf SET id_prestation=:id_prestationInf AND id_assuInf=:id_assuInf AND insupercentInf=:insupercent WHERE id_medinf=:id_medinf ');
							$updateInf->execute(array(
								'id_prestationInf'=>$new_id_presta,
								'id_assuInf'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medinf'=>$id_medinf
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateInf = $connexion->prepare('UPDATE med_inf SET id_prestation=:id_prestationInf AND id_assuInf=:id_assuInf AND insupercentInf=:insupercent WHERE id_medinf=:id_medinf ');
							$updateInf->execute(array(
								'id_prestationInf'=>$new_id_presta,
								'id_assuInf'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medinf'=>$id_medinf
							));
						}
					}
				}

				//Regarder si s'il y'a une med_kine qui
				$selectifmedkine = $connexion->prepare('SELECT * FROM med_kine WHERE numero=:numero AND id_factureMedKine IS NULL AND codecashier="" ');
				$selectifmedkine->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifmedkine->setFetchMode(PDO::FETCH_OBJ);
				$rowmedkine = $selectifmedkine->rowCount();
				if ($rowmedkine !=0) {
					while ($ligneselectifmedkine = $selectifmedkine->fetch()) {
						$id_medkine =  $ligneselectifmedkine->id_medkine;
						$id_prestationKine =  $ligneselectifmedkine->id_prestationKine;

						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_prestationKine
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_kine SET id_prestationKine=:id_prestationKine AND id_assuKine=:id_assuKine AND insupercentKine=:insupercent WHERE id_medkine=:id_medkine ');
							$updateKine->execute(array(
								'id_prestationKine'=>$new_id_presta,
								'id_assuKine'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medkine'=>$id_medkine
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_kine SET id_prestationKine=:id_prestationKine AND id_assuKine=:id_assuKine AND insupercentKine=:insupercent WHERE id_medkine=:id_medkine ');
							$updateKine->execute(array(
								'id_prestationKine'=>$new_id_presta,
								'id_assuKine'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medkine'=>$id_medkine
							));
						}
					}
				}

				//Regarder si s'il y'a une med_labo qui
				$selectifmedlabo = $connexion->prepare('SELECT * FROM med_labo WHERE numero=:numero AND id_factureMedLabo IS NULL AND codecashier="" ');
				$selectifmedlabo->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifmedlabo->setFetchMode(PDO::FETCH_OBJ);
				$rowmedlabo = $selectifmedlabo->rowCount();
				if ($rowmedlabo !=0) {
					while ($ligneselectifmedlabo = $selectifmedlabo->fetch()) {
						$id_medlabo =  $ligneselectifmedlabo->id_medlabo;
						$id_prestationExa =  $ligneselectifmedlabo->id_prestationExa;

						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_prestationExa
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_labo SET id_prestationExa=:id_prestationExa AND id_assuLab=:id_assuLab AND insupercentLab=:insupercent WHERE id_medlabo=:id_medlabo ');
							$updateKine->execute(array(
								'id_prestationExa'=>$new_id_presta,
								'id_assuLab'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medlabo'=>$id_medlabo
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_labo SET id_prestationExa=:id_prestationExa AND id_assuLab=:id_assuLab AND insupercentLab=:insupercent WHERE id_medlabo=:id_medlabo ');
							$updateKine->execute(array(
								'id_prestationExa'=>$new_id_presta,
								'id_assuLab'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medlabo'=>$id_medlabo
							));
						}
					}
				}

				//Regarder si s'il y'a une med_medoc qui
				$selectifmedmedoc = $connexion->prepare('SELECT * FROM med_medoc WHERE numero=:numero AND id_factureMedMedoc IS NULL AND codecashier="" ');
				$selectifmedmedoc->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifmedmedoc->setFetchMode(PDO::FETCH_OBJ);
				$rowmedmedoc = $selectifmedmedoc->rowCount();
				if ($rowmedmedoc !=0) {
					while ($ligneselectifmedmedoc = $selectifmedmedoc->fetch()) {
						$id_medmedoc =  $ligneselectifmedmedoc->id_medmedoc;
						$id_prestationMedoc =  $ligneselectifmedmedoc->id_prestationMedoc;

						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_prestationMedoc
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_medoc SET id_prestationMedoc=:id_prestationMedoc AND id_assuMedoc=:id_assuMedoc AND insupercentMedoc=:insupercent WHERE id_medmedoc=:id_medmedoc ');
							$updateKine->execute(array(
								'id_prestationMedoc'=>$new_id_presta,
								'id_assuMedoc'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medmedoc'=>$id_medmedoc
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_medoc SET id_prestationMedoc=:id_prestationMedoc AND id_assuMedoc=:id_assuMedoc AND insupercentMedoc=:insupercent WHERE id_medmedoc=:id_medmedoc ');
							$updateKine->execute(array(
								'id_prestationMedoc'=>$new_id_presta,
								'id_assuMedoc'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medmedoc'=>$id_medmedoc
							));
						}
					}
				}

				//Regarder si s'il y'a une med_ortho qui
				$selectifmedortho = $connexion->prepare('SELECT * FROM med_ortho WHERE numero=:numero AND id_factureMedOrtho IS NULL AND codecashier="" ');
				$selectifmedortho->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifmedortho->setFetchMode(PDO::FETCH_OBJ);
				$rowmedortho = $selectifmedortho->rowCount();
				if ($rowmedortho !=0) {
					while ($ligneselectifmedortho = $selectifmedortho->fetch()) {
						$id_medortho =  $ligneselectifmedortho->id_medortho;
						$id_prestationOrtho =  $ligneselectifmedortho->id_prestationOrtho;

						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_prestationOrtho
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_ortho SET id_prestationOrtho=:id_prestationOrtho AND id_assuOrtho=:id_assuOrtho AND insupercentOrtho=:insupercent WHERE id_medortho=:id_medortho ');
							$updateKine->execute(array(
								'id_prestationOrtho'=>$new_id_presta,
								'id_assuOrtho'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medortho'=>$id_medortho
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_ortho SET id_prestationOrtho=:id_prestationOrtho AND id_assuOrtho=:id_assuOrtho AND insupercentOrtho=:insupercent WHERE id_medortho=:id_medortho ');
							$updateKine->execute(array(
								'id_prestationOrtho'=>$new_id_presta,
								'id_assuOrtho'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medortho'=>$id_medortho
							));
						}
					}
				}

				//Regarder si s'il y'a une med_radio qui
				$selectifmedradio = $connexion->prepare('SELECT * FROM med_radio WHERE numero=:numero AND id_factureMedRadio IS NULL AND codecashier="" ');
				$selectifmedradio->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifmedradio->setFetchMode(PDO::FETCH_OBJ);
				$rowmedradio = $selectifmedradio->rowCount();
				if ($rowmedradio !=0) {
					while ($ligneselectifmedradio = $selectifmedradio->fetch()) {
						$id_medradio =  $ligneselectifmedradio->id_medradio;
						$id_prestationRadio =  $ligneselectifmedradio->id_prestationRadio;

						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_prestationRadio
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_radio SET id_prestationRadio=:id_prestationRadio AND id_assuRad=:id_assuRad AND insupercentRad=:insupercent WHERE id_medradio=:id_medradio ');
							$updateKine->execute(array(
								'id_prestationRadio'=>$new_id_presta,
								'id_assuRad'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medradio'=>$id_medradio
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_radio SET id_prestationRadio=:id_prestationRadio AND id_assuRad=:id_assuRad AND insupercentRad=:insupercent WHERE id_medradio=:id_medradio ');
							$updateKine->execute(array(
								'id_prestationRadio'=>$new_id_presta,
								'id_assuRad'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medradio'=>$id_medradio
							));
						}
					}
				}


				//Regarder si s'il y'a une med_surge qui
				$selectifmedsurge = $connexion->prepare('SELECT * FROM med_surge WHERE numero=:numero AND id_factureMedSurge IS NULL AND codecashier="" ');
				$selectifmedsurge->execute(array(
					'numero'=>$ligneselectIdassu->numero
				));
				$selectifmedsurge->setFetchMode(PDO::FETCH_OBJ);
				$rowmedsurge = $selectifmedsurge->rowCount();
				if ($rowmedsurge !=0) {
					while ($ligneselectifmedsurge = $selectifmedsurge->fetch()) {
						$id_medsurge =  $ligneselectifmedsurge->id_medsurge;
						$id_prestationSurge =  $ligneselectifmedsurge->id_prestationSurge;

						$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
						$selectnameprestaoldassu->execute(array(
							'id_presta'=>$id_prestationSurge
						));
						$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
						$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

						$nomprestation = $ligneselectnameprestaoldassu->nompresta;
						$nameprestation = $ligneselectnameprestaoldassu->namepresta;
						$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
						$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

						$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
						/*$selectnameprestanewassu->execute(array(
							'nompresta'=>$nomprestation,
							'namepresta'=>$nameprestation,
							'id_categopresta'=>$id_categopresta,
							'id_souscategopresta'=>$id_souscategopresta
						));*/
						$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
						$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
						echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
						if ($rowifnameprestaexit!=0) {
							$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_surge SET id_prestationSurge=:id_prestationSurge AND id_assuSurge=:id_assuSurge AND insupercentSurge=:insupercent WHERE id_medsurge=:id_medsurge ');
							$updateKine->execute(array(
								'id_prestationSurge'=>$new_id_presta,
								'id_assuSurge'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medsurge'=>$id_medsurge
							));
							
						}else{
							$prixpresta = 0;
							$prixprestaCCO = 0;
							$statupresta = 0;
							$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
							$insertNewpresta->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'prixpresta'=>$prixpresta,
								'prixprestaCCO'=>$prixprestaCCO,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta,
								'statupresta'=>$statupresta
							));

							$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
							/*$selectnewnameprestanewassu->execute(array(
								'nompresta'=>$nomprestation,
								'namepresta'=>$nameprestation,
								'id_categopresta'=>$id_categopresta,
								'id_souscategopresta'=>$id_souscategopresta
							));*/
							$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
							$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

							$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
							$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

							$updateKine = $connexion->prepare('UPDATE med_surge SET id_prestationSurge=:id_prestationSurge AND id_assuSurge=:id_assuSurge AND insupercentSurge=:insupercent WHERE id_medsurge=:id_medsurge ');
							$updateKine->execute(array(
								'id_prestationSurge'=>$new_id_presta,
								'id_assuSurge'=>$newAssurance,
								'insupercent'=>$newBill,
								'id_medsurge'=>$id_medsurge
							));
						}
					}
				}

				//Regarder si lepatient est hospitalise

				$selectIfpatientHosp = $connexion->prepare('SELECT * FROM patients_hosp WHERE id_uHosp=:iduti AND numero=:numero AND id_factureHosp IS NULL ');
				$selectIfpatientHosp->execute(array(
					'iduti'=>$modifierUti,
					'numero'=>$ligneselectIdassu->numero
				));
				$selectIfpatientHosp->setFetchMode(PDO::FETCH_OBJ);
				$rowpatHosp = $selectIfpatientHosp->rowCount();
				if ($rowpatHosp !=0) {
					while ($ligneselectIfpatientHosp = $selectIfpatientHosp->fetch()) {
						$id_hosp = $ligneselectIfpatientHosp->id_hosp;

						$updatepatHosp=$connexion->prepare('UPDATE patients_hosp SET insupercent_hosp=:insupercent_hosp,id_assuHosp=:id_assuHosp,nomassuranceHosp=:nomassuranceHosp WHERE id_hosp=:id_hosp AND numero=:numero ');
						$updatepatHosp->execute(array(
						'insupercent_hosp'=>$newBill,
						'id_assuHosp'=>$newAssurance,
						'nomassuranceHosp'=>$Newnomassurance,
						'id_hosp'=>$id_hosp,
						'numero'=>$ligneselectIdassu->numero
						));

						//REgarder si il est restaure
						$selectifpatientResto = $connexion->prepare('SELECT * FROM restauration WHERE numero=:numero AND id_factureHosp IS NULL ');
						$selectifpatientResto->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifpatientResto->setFetchMode(PDO::FETCH_OBJ);
						$rowpatResto = $selectifpatientResto->rowCount();
						if ($rowpatResto !=0) {
							while ($ligneselectIfpatientResto = $selectifpatientResto->fetch()) {
								$id_resto = $ligneselectIfpatientResto->id_resto;

								$updatepatResto=$connexion->prepare('UPDATE restauration SET insupercent_Resto=:insupercent_Resto,id_assuHosp=:id_assuHosp WHERE id_resto=:iduti ');
								$updatepatResto->execute(array(
								'insupercent_Resto'=>$newBill,
								'id_assuHosp'=>$newAssurance,
								'iduti'=>$id_resto
								));
							}
						}

						//REgarder si il est Tour de salle
						$selectifpatientTDS = $connexion->prepare('SELECT * FROM tour_de_salle WHERE numero=:numero AND id_factureHosp="" ');
						$selectifpatientTDS->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifpatientTDS->setFetchMode(PDO::FETCH_OBJ);
						$rowpatResto = $selectifpatientTDS->rowCount();
						if ($rowpatResto !=0) {
							while ($ligneselectifpatientTDS = $selectifpatientTDS->fetch()) {
								$id_tour_de_salle = $ligneselectifpatientTDS->id_tour_de_salle;
								$id_prestationttds = $ligneselectifpatientTDS->id_prestation;
								echo "<br>id_tour_de_salle ".$id_tour_de_salle;
								echo "<br>id_prestation ".$id_prestationttds;

								/*$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationttds
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$rowtds = $selectnameprestaoldassu->rowCount();
								echo "rowtds = = ".$rowtds;
								if ($ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch()) {
									echo "OK OK ";
								}
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;*/
								$id_categopresta = 2;
								//$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->prepare('SELECT * FROM '.$newassuname.' WHERE nompresta LIKE "%Tour de Salle%" AND id_categopresta=:id_categopresta');
								$selectnameprestanewassu->execute(array(
									'id_categopresta'=>$id_categopresta
								));
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								//echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
								$new_id_presta = $ligneselectnameprestanewassu->id_prestation;
								echo "string";

								$updatepatTDS=$connexion->prepare('UPDATE tour_de_salle SET id_prestation=:id_prestation,id_assuHosp=:id_assuHosp,insupercent=:insupercent WHERE id_tour_de_salle=:iduti ');
								$updatepatTDS->execute(array(
									'id_prestation'=>$new_id_presta,
									'id_assuHosp'=>$newAssurance,
									'insupercent'=>$newBill,
									'iduti'=>$id_tour_de_salle
								))or die(print_r($connexion->errorInfo()));
							}
						}

						//Regarder si s'il y'a une med_consom_hosp qui
						$selectifmedconsom = $connexion->prepare('SELECT * FROM med_consom_hosp WHERE numero=:numero AND id_factureConsom IS NULL AND codecashier="" ');
						$selectifmedconsom->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifmedconsom->setFetchMode(PDO::FETCH_OBJ);
						$rowmedconsom = $selectifmedconsom->rowCount();
						if ($rowmedconsom !=0) {
							while ($ligneselectifmedconsom = $selectifmedconsom->fetch()) {
								$id_medconsom =  $ligneselectifmedconsom->id_medconsom;
								$id_prestationConsom =  $ligneselectifmedconsom->id_prestationConsom;

								$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationConsom
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;
								$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
								$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
								/*$selectnameprestanewassu->execute(array(
									'nompresta'=>$nomprestation,
									'namepresta'=>$nameprestation,
									'id_categopresta'=>$id_categopresta,
									'id_souscategopresta'=>$id_souscategopresta
								));*/
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								if ($rowifnameprestaexit!=0) {
									$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateconsu = $connexion->prepare('UPDATE med_consom_hosp SET id_prestationConsom=:id_prestationConsom AND id_assuConsom=:id_assuConsom AND insupercentConsom=:insupercent WHERE id_medconsom=:id_medconsom ');
									$updateconsu->execute(array(
										'id_prestationConsom'=>$new_id_presta,
										'id_assuConsom'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medconsom'=>$id_medconsom
									));
									
								}else{
									$prixpresta = 0;
									$prixprestaCCO = 0;
									$statupresta = 0;
									$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
									$insertNewpresta->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'prixpresta'=>$prixpresta,
										'prixprestaCCO'=>$prixprestaCCO,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta,
										'statupresta'=>$statupresta
									));

									$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
									/*$selectnewnameprestanewassu->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta
									));*/
									$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
									$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

									$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateconsu = $connexion->prepare('UPDATE med_consom_hosp SET id_prestationConsom=:id_prestationConsom AND id_assuConsom=:id_assuConsom AND insupercentConsom=:insupercent WHERE id_medconsom=:id_medconsom ');
									$updateconsu->execute(array(
										'id_prestationConsom'=>$new_id_presta,
										'id_assuConsom'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medconsom'=>$id_medconsom
									));
								}
							}
						}

						//Regarder si s'il y'a une med_consult_hosp qui
						$selectifmedConsu = $connexion->prepare('SELECT * FROM med_consult_hosp WHERE numero=:numero AND id_factureConsu IS NULL AND codecashier="" ');
						$selectifmedConsu->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifmedConsu->setFetchMode(PDO::FETCH_OBJ);
						$rowmedConsu = $selectifmedConsu->rowCount();
						if ($rowmedConsu !=0) {
							while ($ligneselectifmedConsu = $selectifmedConsu->fetch()) {
								$id_medconsu =  $ligneselectifmedConsu->id_medconsu;
								$id_prestationConsu =  $ligneselectifmedConsu->id_prestationConsu;

								$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationConsu
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;
								$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
								$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
								/*$selectnameprestanewassu->execute(array(
									'nompresta'=>$nomprestation,
									'namepresta'=>$nameprestation,
									'id_categopresta'=>$id_categopresta,
									'id_souscategopresta'=>$id_souscategopresta
								));*/
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								if ($rowifnameprestaexit!=0) {
									$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateconsu = $connexion->prepare('UPDATE med_consult_hosp SET id_prestationConsu=:id_prestationConsu AND id_assuServ=:id_assuServ AND insupercentServ=:insupercent WHERE id_medconsu=:id_medconsu ');
									$updateconsu->execute(array(
										'id_prestationConsu'=>$new_id_presta,
										'id_assuServ'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medconsu'=>$id_medconsu
									));
									
								}else{
									$prixpresta = 0;
									$prixprestaCCO = 0;
									$statupresta = 0;
									$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
									$insertNewpresta->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'prixpresta'=>$prixpresta,
										'prixprestaCCO'=>$prixprestaCCO,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta,
										'statupresta'=>$statupresta
									));

									$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
									/*$selectnewnameprestanewassu->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta
									));*/
									$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
									$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

									$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateconsu = $connexion->prepare('UPDATE med_consult_hosp SET id_prestationConsu=:id_prestationConsu AND id_assuServ=:id_assuServ AND insupercentServ=:insupercent WHERE id_medconsu=:id_medconsu ');
									$updateconsu->execute(array(
										'id_prestationConsu'=>$new_id_presta,
										'id_assuServ'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medconsu'=>$id_medconsu
									));
								}
							}
						}


						//Regarder si s'il y'a une med_inf_hosp qui
						$selectifmedInf = $connexion->prepare('SELECT * FROM med_inf_hosp WHERE numero=:numero AND id_factureMedInf IS NULL AND codecashier="" ');
						$selectifmedInf->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifmedInf->setFetchMode(PDO::FETCH_OBJ);
						$rowmedInf = $selectifmedInf->rowCount();
						if ($rowmedInf !=0) {
							while ($ligneselectifmedInf = $selectifmedInf->fetch()) {
								$id_medinf =  $ligneselectifmedInf->id_medinf;
								$id_prestationInf =  $ligneselectifmedInf->id_prestation;

								$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationInf
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;
								$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
								$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
								/*$selectnameprestanewassu->execute(array(
									'nompresta'=>$nomprestation,
									'namepresta'=>$nameprestation,
									'id_categopresta'=>$id_categopresta,
									'id_souscategopresta'=>$id_souscategopresta
								));*/
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								if ($rowifnameprestaexit!=0) {
									$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateInf = $connexion->prepare('UPDATE med_inf_hosp SET id_prestation=:id_prestationInf AND id_assuInf=:id_assuInf AND insupercentInf=:insupercent WHERE id_medinf=:id_medinf ');
									$updateInf->execute(array(
										'id_prestationInf'=>$new_id_presta,
										'id_assuInf'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medinf'=>$id_medinf
									));
									
								}else{
									$prixpresta = 0;
									$prixprestaCCO = 0;
									$statupresta = 0;
									$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
									$insertNewpresta->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'prixpresta'=>$prixpresta,
										'prixprestaCCO'=>$prixprestaCCO,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta,
										'statupresta'=>$statupresta
									));

									$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
									/*$selectnewnameprestanewassu->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta
									));*/
									$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
									$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

									$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateInf = $connexion->prepare('UPDATE med_inf_hosp SET id_prestation=:id_prestationInf AND id_assuInf=:id_assuInf AND insupercentInf=:insupercent WHERE id_medinf=:id_medinf ');
									$updateInf->execute(array(
										'id_prestationInf'=>$new_id_presta,
										'id_assuInf'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medinf'=>$id_medinf
									));
								}
							}
						}

						//Regarder si s'il y'a une med_kine_hosp qui
						$selectifmedkine = $connexion->prepare('SELECT * FROM med_kine_hosp WHERE numero=:numero AND id_factureMedKine IS NULL AND codecashier="" ');
						$selectifmedkine->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifmedkine->setFetchMode(PDO::FETCH_OBJ);
						$rowmedkine = $selectifmedkine->rowCount();
						if ($rowmedkine !=0) {
							while ($ligneselectifmedkine = $selectifmedkine->fetch()) {
								$id_medkine =  $ligneselectifmedkine->id_medkine;
								$id_prestationKine =  $ligneselectifmedkine->id_prestationKine;

								$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationKine
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;
								$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
								$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
								/*$selectnameprestanewassu->execute(array(
									'nompresta'=>$nomprestation,
									'namepresta'=>$nameprestation,
									'id_categopresta'=>$id_categopresta,
									'id_souscategopresta'=>$id_souscategopresta
								));*/
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								if ($rowifnameprestaexit!=0) {
									$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_kine_hosp SET id_prestationKine=:id_prestationKine AND id_assuKine=:id_assuKine AND insupercentKine=:insupercent WHERE id_medkine=:id_medkine ');
									$updateKine->execute(array(
										'id_prestationKine'=>$new_id_presta,
										'id_assuKine'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medkine'=>$id_medkine
									));
									
								}else{
									$prixpresta = 0;
									$prixprestaCCO = 0;
									$statupresta = 0;
									$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
									$insertNewpresta->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'prixpresta'=>$prixpresta,
										'prixprestaCCO'=>$prixprestaCCO,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta,
										'statupresta'=>$statupresta
									));

									$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
									/*$selectnewnameprestanewassu->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta
									));*/
									$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
									$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

									$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_kine_hosp SET id_prestationKine=:id_prestationKine AND id_assuKine=:id_assuKine AND insupercentKine=:insupercent WHERE id_medkine=:id_medkine ');
									$updateKine->execute(array(
										'id_prestationKine'=>$new_id_presta,
										'id_assuKine'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medkine'=>$id_medkine
									));
								}
							}
						}

						//Regarder si s'il y'a une med_labo_hosp qui
						$selectifmedlabo = $connexion->prepare('SELECT * FROM med_labo_hosp WHERE numero=:numero AND id_factureMedLabo IS NULL AND codecashier="" ');
						$selectifmedlabo->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifmedlabo->setFetchMode(PDO::FETCH_OBJ);
						$rowmedlabo = $selectifmedlabo->rowCount();
						if ($rowmedlabo !=0) {
							while ($ligneselectifmedlabo = $selectifmedlabo->fetch()) {
								$id_medlabo =  $ligneselectifmedlabo->id_medlabo;
								$id_prestationExa =  $ligneselectifmedlabo->id_prestationExa;

								$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationExa
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;
								$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
								$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
								/*$selectnameprestanewassu->execute(array(
									'nompresta'=>$nomprestation,
									'namepresta'=>$nameprestation,
									'id_categopresta'=>$id_categopresta,
									'id_souscategopresta'=>$id_souscategopresta
								));*/
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								if ($rowifnameprestaexit!=0) {
									$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_labo_hosp SET id_prestationExa=:id_prestationExa AND id_assuLab=:id_assuLab AND insupercentLab=:insupercent WHERE id_medlabo=:id_medlabo ');
									$updateKine->execute(array(
										'id_prestationExa'=>$new_id_presta,
										'id_assuLab'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medlabo'=>$id_medlabo
									));
									
								}else{
									$prixpresta = 0;
									$prixprestaCCO = 0;
									$statupresta = 0;
									$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
									$insertNewpresta->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'prixpresta'=>$prixpresta,
										'prixprestaCCO'=>$prixprestaCCO,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta,
										'statupresta'=>$statupresta
									));

									$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
									/*$selectnewnameprestanewassu->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta
									));*/
									$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
									$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

									$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_labo_hosp SET id_prestationExa=:id_prestationExa AND id_assuLab=:id_assuLab AND insupercentLab=:insupercent WHERE id_medlabo=:id_medlabo ');
									$updateKine->execute(array(
										'id_prestationExa'=>$new_id_presta,
										'id_assuLab'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medlabo'=>$id_medlabo
									));
								}
							}
						}

						//Regarder si s'il y'a une med_medoc_hosp qui
						$selectifmedmedoc = $connexion->prepare('SELECT * FROM med_medoc_hosp WHERE numero=:numero AND id_factureMedMedoc IS NULL AND codecashier="" ');
						$selectifmedmedoc->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifmedmedoc->setFetchMode(PDO::FETCH_OBJ);
						$rowmedmedoc = $selectifmedmedoc->rowCount();
						if ($rowmedmedoc !=0) {
							while ($ligneselectifmedmedoc = $selectifmedmedoc->fetch()) {
								$id_medmedoc =  $ligneselectifmedmedoc->id_medmedoc;
								$id_prestationMedoc =  $ligneselectifmedmedoc->id_prestationMedoc;

								$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationMedoc
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;
								$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
								$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
								/*$selectnameprestanewassu->execute(array(
									'nompresta'=>$nomprestation,
									'namepresta'=>$nameprestation,
									'id_categopresta'=>$id_categopresta,
									'id_souscategopresta'=>$id_souscategopresta
								));*/
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								if ($rowifnameprestaexit!=0) {
									$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_medoc_hosp SET id_prestationMedoc=:id_prestationMedoc AND id_assuMedoc=:id_assuMedoc AND insupercentMedoc=:insupercent WHERE id_medmedoc=:id_medmedoc ');
									$updateKine->execute(array(
										'id_prestationMedoc'=>$new_id_presta,
										'id_assuMedoc'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medmedoc'=>$id_medmedoc
									));
									
								}else{
									$prixpresta = 0;
									$prixprestaCCO = 0;
									$statupresta = 0;
									$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
									$insertNewpresta->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'prixpresta'=>$prixpresta,
										'prixprestaCCO'=>$prixprestaCCO,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta,
										'statupresta'=>$statupresta
									));

									$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
									/*$selectnewnameprestanewassu->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta
									));*/
									$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
									$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

									$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_medoc_hosp SET id_prestationMedoc=:id_prestationMedoc AND id_assuMedoc=:id_assuMedoc AND insupercentMedoc=:insupercent WHERE id_medmedoc=:id_medmedoc ');
									$updateKine->execute(array(
										'id_prestationMedoc'=>$new_id_presta,
										'id_assuMedoc'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medmedoc'=>$id_medmedoc
									));
								}
							}
						}

						//Regarder si s'il y'a une med_ortho_hosp qui
						$selectifmedortho = $connexion->prepare('SELECT * FROM med_ortho_hosp WHERE numero=:numero AND id_factureMedOrtho IS NULL AND codecashier="" ');
						$selectifmedortho->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifmedortho->setFetchMode(PDO::FETCH_OBJ);
						$rowmedortho = $selectifmedortho->rowCount();
						if ($rowmedortho !=0) {
							while ($ligneselectifmedortho = $selectifmedortho->fetch()) {
								$id_medortho =  $ligneselectifmedortho->id_medortho;
								$id_prestationOrtho =  $ligneselectifmedortho->id_prestationOrtho;

								$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationOrtho
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;
								$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
								$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
								/*$selectnameprestanewassu->execute(array(
									'nompresta'=>$nomprestation,
									'namepresta'=>$nameprestation,
									'id_categopresta'=>$id_categopresta,
									'id_souscategopresta'=>$id_souscategopresta
								));*/
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								if ($rowifnameprestaexit!=0) {
									$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_ortho_hosp SET id_prestationOrtho=:id_prestationOrtho AND id_assuOrtho=:id_assuOrtho AND insupercentOrtho=:insupercent WHERE id_medortho=:id_medortho ');
									$updateKine->execute(array(
										'id_prestationOrtho'=>$new_id_presta,
										'id_assuOrtho'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medortho'=>$id_medortho
									));
									
								}else{
									$prixpresta = 0;
									$prixprestaCCO = 0;
									$statupresta = 0;
									$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
									$insertNewpresta->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'prixpresta'=>$prixpresta,
										'prixprestaCCO'=>$prixprestaCCO,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta,
										'statupresta'=>$statupresta
									));

									$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
									/*$selectnewnameprestanewassu->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta
									));*/
									$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
									$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

									$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_ortho_hosp SET id_prestationOrtho=:id_prestationOrtho AND id_assuOrtho=:id_assuOrtho AND insupercentOrtho=:insupercent WHERE id_medortho=:id_medortho ');
									$updateKine->execute(array(
										'id_prestationOrtho'=>$new_id_presta,
										'id_assuOrtho'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medortho'=>$id_medortho
									));
								}
							}
						}

						//Regarder si s'il y'a une med_ortho_hosp qui
						$selectifmedradio = $connexion->prepare('SELECT * FROM med_ortho_hosp WHERE numero=:numero AND id_factureMedRadio IS NULL AND codecashier="" ');
						$selectifmedradio->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifmedradio->setFetchMode(PDO::FETCH_OBJ);
						$rowmedradio = $selectifmedradio->rowCount();
						if ($rowmedradio !=0) {
							while ($ligneselectifmedradio = $selectifmedradio->fetch()) {
								$id_medradio =  $ligneselectifmedradio->id_medradio;
								$id_prestationRadio =  $ligneselectifmedradio->id_prestationRadio;

								$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationRadio
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;
								$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
								$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
								/*$selectnameprestanewassu->execute(array(
									'nompresta'=>$nomprestation,
									'namepresta'=>$nameprestation,
									'id_categopresta'=>$id_categopresta,
									'id_souscategopresta'=>$id_souscategopresta
								));*/
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								if ($rowifnameprestaexit!=0) {
									$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_ortho_hosp SET id_prestationRadio=:id_prestationRadio AND id_assuRad=:id_assuRad AND insupercentRad=:insupercent WHERE id_medradio=:id_medradio ');
									$updateKine->execute(array(
										'id_prestationRadio'=>$new_id_presta,
										'id_assuRad'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medradio'=>$id_medradio
									));
									
								}else{
									$prixpresta = 0;
									$prixprestaCCO = 0;
									$statupresta = 0;
									$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
									$insertNewpresta->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'prixpresta'=>$prixpresta,
										'prixprestaCCO'=>$prixprestaCCO,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta,
										'statupresta'=>$statupresta
									));

									$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
									/*$selectnewnameprestanewassu->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta
									));*/
									$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
									$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

									$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_ortho_hosp SET id_prestationRadio=:id_prestationRadio AND id_assuRad=:id_assuRad AND insupercentRad=:insupercent WHERE id_medradio=:id_medradio ');
									$updateKine->execute(array(
										'id_prestationRadio'=>$new_id_presta,
										'id_assuRad'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medradio'=>$id_medradio
									));
								}
							}
						}


						//Regarder si s'il y'a une med_surge_hosp qui
						$selectifmedsurge = $connexion->prepare('SELECT * FROM med_surge_hosp WHERE numero=:numero AND id_factureMedSurge IS NULL AND codecashier="" ');
						$selectifmedsurge->execute(array(
							'numero'=>$ligneselectIdassu->numero
						));
						$selectifmedsurge->setFetchMode(PDO::FETCH_OBJ);
						$rowmedsurge = $selectifmedsurge->rowCount();
						if ($rowmedsurge !=0) {
							while ($ligneselectifmedsurge = $selectifmedsurge->fetch()) {
								$id_medsurge =  $ligneselectifmedsurge->id_medsurge;
								$id_prestationSurge =  $ligneselectifmedsurge->id_prestationSurge;

								$selectnameprestaoldassu = $connexion->prepare('SELECT * FROM '.$oldassuname.' WHERE id_prestation=:id_presta ');
								$selectnameprestaoldassu->execute(array(
									'id_presta'=>$id_prestationSurge
								));
								$selectnameprestaoldassu->setFetchMode(PDO::FETCH_OBJ);
								$ligneselectnameprestaoldassu = $selectnameprestaoldassu->fetch();

								$nomprestation = $ligneselectnameprestaoldassu->nompresta;
								$nameprestation = $ligneselectnameprestaoldassu->namepresta;
								$id_categopresta = $ligneselectnameprestaoldassu->id_categopresta;
								$id_souscategopresta = $ligneselectnameprestaoldassu->id_souscategopresta;

								$selectnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
								/*$selectnameprestanewassu->execute(array(
									'nompresta'=>$nomprestation,
									'namepresta'=>$nameprestation,
									'id_categopresta'=>$id_categopresta,
									'id_souscategopresta'=>$id_souscategopresta
								));*/
								$selectnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
								$rowifnameprestaexit = $selectnameprestanewassu->rowCount();
								echo "rowifnameprestaexit =  ".$rowifnameprestaexit;
								if ($rowifnameprestaexit!=0) {
									$ligneselectnameprestanewassu = $selectnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_surge_hosp SET id_prestationSurge=:id_prestationSurge AND id_assuSurge=:id_assuSurge AND insupercentSurge=:insupercent WHERE id_medsurge=:id_medsurge ');
									$updateKine->execute(array(
										'id_prestationSurge'=>$new_id_presta,
										'id_assuSurge'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medsurge'=>$id_medsurge
									));
									
								}else{
									$prixpresta = 0;
									$prixprestaCCO = 0;
									$statupresta = 0;
									$insertNewpresta = $connexion->prepare('INSERT INTO '. $newassuname.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:statupresta)');
									$insertNewpresta->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'prixpresta'=>$prixpresta,
										'prixprestaCCO'=>$prixprestaCCO,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta,
										'statupresta'=>$statupresta
									));

									$selectnewnameprestanewassu = $connexion->query('SELECT * FROM '.$newassuname.' WHERE (nompresta LIKE "%'.$nomprestation.'%" OR namepresta LIKE "%'.$nameprestation.'%") AND id_categopresta='.$id_categopresta.' AND id_souscategopresta='.$id_souscategopresta.' ');
									/*$selectnewnameprestanewassu->execute(array(
										'nompresta'=>$nomprestation,
										'namepresta'=>$nameprestation,
										'id_categopresta'=>$id_categopresta,
										'id_souscategopresta'=>$id_souscategopresta
									));*/
									$selectnewnameprestanewassu->setFetchMode(PDO::FETCH_OBJ);
									$rowifnewnameprestaexit = $selectnewnameprestanewassu->rowCount();

									$ligneselectnewnameprestanewassu = $selectnewnameprestanewassu->fetch();
									$new_id_presta = $ligneselectnameprestanewassu->id_prestation;

									$updateKine = $connexion->prepare('UPDATE med_surge_hosp SET id_prestationSurge=:id_prestationSurge AND id_assuSurge=:id_assuSurge AND insupercentSurge=:insupercent WHERE id_medsurge=:id_medsurge ');
									$updateKine->execute(array(
										'id_prestationSurge'=>$new_id_presta,
										'id_assuSurge'=>$newAssurance,
										'insupercent'=>$newBill,
										'id_medsurge'=>$id_medsurge
									));
								}
							}
						}
					}
				}

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

			}else{

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
			
			
		
			
/* 
			echo '<script type="text/javascript"> alert("- NewCardIDassurance : '.$newCardIDassurance.'\n - NewNumeropolice :'.$newNumeropolice.'\n - NewAdherent :'.$newAdherent.'\n - ModifierUti :'.$modifierUti.'");</script>';
			 */
			
			
			/*if(isset($_SESSION['infhosp']))
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
			}*/	
								
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
		
		
		if($_POST['profil']=="Orthopediste" OR $_POST['profil']=="Orthopedist")
		{
			$codeO=$_POST['codeortho'];

			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
							
			$res->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('UPDATE orthopedistes SET id_u=:iduti,codeortho=:codeO,dateaffectationortho=:dateAff WHERE id_u=:iduti');
				$resultats->execute(array(
				'iduti'=>$modifierUti,
				'codeO'=>$codeO,
				'dateAff'=>$newAnAd
				))or die( print_r($connexion->errorInfo()));
	
			}
			
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:orthopedistes1.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:orthopedistes1.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:orthopedistes1.php");
				}
			}
		}
		
		if($_POST['profil']=="Coordinateur" OR $_POST['profil']=="Manager" OR $_POST['profil']=="Gérant")
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


		if($_POST['profil']=="Gerar de Stock" OR $_POST['profil']=="Stock Keeper")
		{
			$codeS=$_POST['codesto'];

			$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
							
			$res->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$res->fetch())
			{
				$resultats=$connexion->prepare('UPDATE stockkeeper SET codestock=:codestock,dateaffectionstock=:dateAff WHERE id_u=:iduti');
				$resultats->execute(array(
				'iduti'=>$modifierUti,
				'codestock'=>$codeS,
				'dateAff'=>$newAnAd
				))or die( print_r($connexion->errorInfo()));
	
			}
			echo 'aaa';
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:stockkeeper.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:stockkeeper.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:stockkeeper.php");
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