<?php

session_start();
include("connect.php");
	
	$annee = date('Y').'-'.date('m').'-'.date('d');

	if(isset($_GET['english']))
	{
		$langue="&english=english";				
	
	}else{
		if(isset($_GET['francais']))
		{
			$langue="&francais=francais";
			
		}else{
			$langue="";
		}
	}
	
	
 	if(isset($_POST['resultatbtn']))
	{
		
		$num=$_GET['num'];
					
		$idmedlabo = array();

		foreach($_POST['idmedLaboResult'] as $valeur)
		{
			$idmedlabo[] = $valeur;			   
		}
		
		
		$resultats = array();

		foreach($_POST['resultats'] as $value)
		{
			$resultats[] = $value;			   
		}
		
		
		$autreresult = array();

		foreach($_FILES['autreresult']['name'] as $val)
		{
			$autreresult[] = $val;			   
		}
		
		
		for($i=0;$i<sizeof($idmedlabo);$i++)
		{	
			/* echo $idmedlabo[$i].'<br/>';
			echo $resultats[$i].'<br/>';
			echo $autreresult[$i].'<br/><br/>';
			 */
			$extAuthorisedDoc=array('.png','.gif','.jpg','.jpeg','.pdf','.doc','.docx','.xlsx','.PNG','.GIF','.JPG','.JPEG','.PDF','.DOC','.DOCX','.XLSX','');

			$extensionDoc=strrchr($autreresult[$i],'.');
			//echo $extensionPic.'<br/>';
			
			if(!in_array($extensionDoc,$extAuthorisedDoc))
			{
				echo '<script language="javascript"> alert("Ce n\'est pas le type de document qui peut être téléchargé!");</script>';
				echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok'.$langue.'"</script>';
				
			}else{
		
				if($_FILES['autreresult']['error'][$i]== UPLOAD_ERR_FORM_SIZE)
				{
					echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!");</script>';
					echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok'.$langue.'"</script>';
				
				}else{
			
					// echo '<script language="javascript"> alert ("'.$_POST['nomDoc'].'\n Result ='.$_FILES['result']['name'].'\n ExtensionDoc ='.$extensionDoc.'");</script>';
					
					if($extensionDoc!="")
					{
						$nomDoc=$autreresult[$i];
						$tempName=$_FILES['autreresult']['tmp_name'][$i];
						
						$resultat=move_uploaded_file($_FILES['autreresult']['tmp_name'][$i], $nomDoc);
					
					}else{
						$nomDoc="";
						$tempName="";
						
						$resultat="";
					}
					
					$resultIdMedLabo=$connexion->query("SELECT * FROM med_labo WHERE id_medlabo='$idmedlabo[$i]'")or die( print_r($connexion->errorInfo()));
				
					$comptIdmedlabo = $resultIdMedLabo->rowCount();
					
					if( $comptIdmedlabo != 0)
					{
						if($resultats[$i] != "" OR $nomDoc != "")
						{
							
							$dateresult=$annee;							
							$iduser=$_SESSION['id'];					
							$examenfait=1;
						}else{
												
							$dateresult="0000-00-00";					
							$iduser=NULL;						
							$examenfait=0;
						}
					
						$resultMedLabo=$connexion->prepare("UPDATE med_labo SET examenfait = :examenfait, resultats=:result, autreresultats=:autreresult, dateresultats=:dateresult, id_uL=:id_uL WHERE id_medlabo =:idmedLabo");
						$resultMedLabo->execute(array(
						'idmedLabo'=>$idmedlabo[$i],
						'examenfait'=>$examenfait,
						'result'=>$nomDoc,
						'autreresult'=>$resultats[$i],
						'dateresult'=>$dateresult,
						'id_uL'=>$iduser
						
						))or die( print_r($connexion->errorInfo()));
					
						
						echo '<script type="text/javascript"> alert("Results sent!");</script>';
						echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok'.$langue.'"</script>';
						
					
						
						// echo '<script language="javascript"> alert ("\n Nom Doc = '.$nomDoc.'\n Result ='.$autreresult[$i].'\n ExtensionDoc ='.$extensionDoc.'\n Temp name = '.$_FILES['autreresult']['tmp_name'][$i].'\n Move uploaded file = '.$resultat.'\n ComptIdmedlabo = '.$comptIdmedlabo.'");</script>';
					
			
					}
					
				}			
			}
		}
		

	}
	
	

 	if(isset($_POST['resultbtn']))
	{
	
		$idmedlabo=$_GET['idmedLab'];
		$dateresult=$annee;
		
		if($_POST['examen']!="")
		{
			$autreresult=nl2br($_POST['examen']);
		}else{
			$autreresult="";
		}
		
		$iduser=$_SESSION['id'];
		$num=$_GET['num'];
		$examenfait=1;

		$extAuthorisedDoc=array('.png','.gif','.jpg','.jpeg','.pdf','.doc','.docx','.PNG','.GIF','.JPG','.JPEG','.PDF','.DOC','.DOCX','');

		$extensionDoc=strrchr($_FILES['result']['name'],'.');
		//echo $extensionPic.'<br/>';
		
		if(!in_array($extensionDoc,$extAuthorisedDoc))
		{
			echo '<script language="javascript"> alert("Ce n\'est pas le type de document qui peut être téléchargé!");</script>';
			echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&getidmedLabo='.$idmedlabo.'&dateconsu='.$dateresult.'&sendResult=ok"</script>';
			
		}else{
		
			if($_FILES['result']['error']== UPLOAD_ERR_FORM_SIZE)
			{
				echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!");</script>';
				echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&getidmedLabo='.$idmedlabo.'&dateconsu='.$dateresult.'&sendResult=ok"</script>';
			
			}else{
			
				// echo '<script language="javascript"> alert ("'.$_POST['nomDoc'].'\n Result ='.$_FILES['result']['name'].'\n ExtensionDoc ='.$extensionDoc.'");</script>';
				
				if($extensionDoc!="")
				{
					$nomDoc=time().strtolower($extensionDoc);
					$resultat=move_uploaded_file($_FILES['result']['tmp_name'], $nomDoc);
				}else{
					$nomDoc="";
				}
				
				/* echo $nomDoc.'<br/>';
				echo $autreresult.'<br/>';
				 */
				
							
				$resultIdMedLabo=$connexion->query("SELECT * FROM med_labo WHERE id_medlabo='$idmedlabo'")or die( print_r($connexion->errorInfo()));
				
				$comptIdmedlabo = $resultIdMedLabo->rowCount();
				
				if( $comptIdmedlabo != 0)
				{
					
					// echo $comptIdmedlabo;
					
					$resultMedLabo=$connexion->prepare("UPDATE med_labo SET examenfait = '1', resultats=:result, autreresultats=:autreresult, dateresultats=:dateresult, id_uL=:id_uL WHERE id_medlabo =:idmedLabo");
					$resultMedLabo->execute(array(
					'idmedLabo'=>$idmedlabo,
					'result'=>$nomDoc,
					'autreresult'=>$autreresult,
					'dateresult'=>$dateresult,
					'id_uL'=>$iduser
					
					))or die( print_r($connexion->errorInfo()));
				
					
					if(isset($_GET['english']))
					{
						echo '<script type="text/javascript"> alert("Results sent!");</script>';
						echo '<script language="javascript">document.location.href="patients1.php?english='.$_GET['english'].'&num='.$num.'&examenPa=ok"</script>';
							
					
					}else{
						if(isset($_GET['francais']))
						{
							echo '<script type="text/javascript"> alert("Results sent!");</script>';
							echo '<script language="javascript">document.location.href="patients1.php?francais='.$_GET['francais'].'&num='.$num.'&examenPa=ok"</script>';
														
						}else{
							echo '<script type="text/javascript"> alert("Results sent!");</script>';
							echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok"</script>';
					
						}
					}
	
		
				}
				
			}
		}
		

	}
	
	

 	if(isset($_POST['updateresultbtn']))
	{
	
		$idmedlabo=$_GET['updateidmedLabo'];
		$dateresult=$annee;
		
		if($_POST['examen']!="")
		{
			$autreresult=nl2br($_POST['examen']);
		}else{
			$autreresult=$_POST['examen'];
		}
		
		$iduser=$_SESSION['id'];
		$num=$_GET['num'];
		$examenfait=1;

		$extAuthorisedDoc=array('.png','.gif','.jpg','.jpeg','.pdf','.doc','.docx','.PNG','.GIF','.JPG','.JPEG','.PDF','.DOC','.DOCX','');

		$extensionDoc=strrchr($_FILES['result']['name'],'.');
		//echo $extensionPic.'<br/>';
		
		if(!in_array($extensionDoc,$extAuthorisedDoc))
		{
			echo '<script language="javascript"> alert("Ce n\'est pas le type de document qui peut être téléchargé!");</script>';
			echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&getidmedLabo='.$idmedlabo.'&dateconsu='.$dateresult.'&sendResult=ok"</script>';
			
		}else{
		
			if($_FILES['result']['error']== UPLOAD_ERR_FORM_SIZE)
			{
				echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!");</script>';
				echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&getidmedLabo='.$idmedlabo.'&dateconsu='.$dateresult.'&sendResult=ok"</script>';
			
			}else{
			
				// echo '<script language="javascript"> alert ("'.$_POST['nomDoc'].'\n Result ='.$_FILES['result']['name'].'\n ExtensionDoc ='.$extensionDoc.'");</script>';
				
				if($extensionDoc!="")
				{
					$nomDoc=time().strtolower($extensionDoc);
					$resultat=move_uploaded_file($_FILES['result']['tmp_name'], $nomDoc);
				}else{
					$nomDoc="";
				}
				
				/* 
				echo $nomDoc.'<br/>';
				echo $autreresult.'<br/>';
				 */
				
							
				$resultIdMedLabo=$connexion->query("SELECT * FROM med_labo WHERE id_medlabo='$idmedlabo'")or die( print_r($connexion->errorInfo()));
				
				$comptIdmedlabo = $resultIdMedLabo->rowCount();
				
				if( $comptIdmedlabo != 0)
				{
					
					// echo $comptIdmedlabo;
					
					$resultMedLabo=$connexion->prepare("UPDATE med_labo SET examenfait = '1', resultats=:result, autreresultats=:autreresult, dateresultats=:dateresult, id_uL=:id_uL WHERE id_medlabo =:idmedLabo");
					$resultMedLabo->execute(array(
					'idmedLabo'=>$idmedlabo,
					'result'=>$nomDoc,
					'autreresult'=>$autreresult,
					'dateresult'=>$dateresult,
					'id_uL'=>$iduser
					
					))or die( print_r($connexion->errorInfo()));
				
					
					if(isset($_GET['english']))
					{
						echo '<script type="text/javascript"> alert("Results sent!");</script>';
						echo '<script language="javascript">document.location.href="patients1.php?english='.$_GET['english'].'&num='.$num.'&examenPa=ok"</script>';
							
					
					}else{
						if(isset($_GET['francais']))
						{
							echo '<script type="text/javascript"> alert("Results sent!");</script>';
							echo '<script language="javascript">document.location.href="patients1.php?francais='.$_GET['francais'].'&num='.$num.'&examenPa=ok"</script>';
														
						}else{
							echo '<script type="text/javascript"> alert("Results sent!");</script>';
							echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok"</script>';
					
						}
					}
	
		
				}
				
			}
		}
		

	}
	
	

 	if(isset($_POST['diagnobtn']))
	{
		$idconsu=$_GET['updateidconsu'];
		
		$resultIdconsu=$connexion->query("SELECT * FROM consultations c WHERE c.id_consu='$idconsu'")or die( print_r($connexion->errorInfo()));
		
		$comptIdconsu = $resultIdconsu->rowCount();
		
		if( $comptIdconsu != 0)
		{
			$diagnofinal=nl2br($_POST['diagnofinal']);
			
				$resultConsu=$connexion->prepare("UPDATE consultations SET diagnostic=:diagnofinal, id_uM=:id_uM WHERE id_consu =:idconsu");
				$resultConsu->execute(array(
					'idconsu'=>$idconsu,
					'diagnofinal'=>nl2br($diagnofinal),
					'id_uM'=>$_SESSION['id']
				
				))or die( print_r($connexion->errorInfo()));
			
				echo '<script language="javascript">document.location.href="consultations.php?num='.$_GET['num'].'&id_consu='.$idconsu.'&dateconsu='.$_GET['dateconsu'].'&showmore=ok#diagnorecomm"</script>';
				

		}
		

	}
	
	
 	if(isset($_POST['recommbtn']))
	{
		$idconsu=$_GET['updateidconsu'];
		
		$resultIdconsu=$connexion->query("SELECT * FROM consultations c WHERE c.id_consu='$idconsu'")or die( print_r($connexion->errorInfo()));
		
		$comptIdconsu = $resultIdconsu->rowCount();
		
		if( $comptIdconsu != 0)
		{
			$recommfinal=$_POST['recommfinal'];
			
				$resultConsu=$connexion->prepare("UPDATE consultations SET recommandationnext=:recommfinal, id_uM=:id_uM WHERE id_consu =:idconsu");
				$resultConsu->execute(array(
					'idconsu'=>$idconsu,
					'recommfinal'=>nl2br($recommfinal),
					'id_uM'=>$_SESSION['id']
				
				))or die( print_r($connexion->errorInfo()));
			
				echo '<script language="javascript">document.location.href="consultations.php?num='.$_GET['num'].'&id_consu='.$idconsu.'&dateconsu='.$_GET['dateconsu'].'&showmore=ok#diagnorecomm"</script>';
				

		}
		

	}
	
	

?>