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
		
		/* 
		$valeurLab = array();

		foreach($_POST['valeur'] as $valval)
		{
			$valeurLab[] = $valval;			   
		}
		
		 */
		$min = array();

		foreach($_POST['min'] as $valmin)
		{
			$min[] = $valmin;			   
		}
		
		
		$max = array();

		foreach($_POST['max'] as $valmax)
		{
			$max[] = $valmax;			   
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
					
					if($ligneL=$resultIdMedLabo->fetch(PDO::FETCH_OBJ))
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
					
						$resultMedLabo=$connexion->prepare("UPDATE med_labo SET examenfait = :examenfait, resultats=:result,valeurLab=:valeurLab,minresultats=:min,maxresultats=:max,autreresultats=:autreresult, dateresultats=:dateresult, id_uL=:id_uL WHERE id_medlabo =:idmedLabo");
						$resultMedLabo->execute(array(
						'idmedLabo'=>$idmedlabo[$i],
						'examenfait'=>$examenfait,
						'result'=>$nomDoc,
						'valeurLab'=>$_POST[''.$i.''],
						'min'=>$min[$i],
						'max'=>$max[$i],
						'autreresult'=>$resultats[$i],
						'dateresult'=>$dateresult,
						'id_uL'=>$iduser
						
						))or die( print_r($connexion->errorInfo()));

						
						// Get Number of exam Of Each Patient

						$getNber = $connexion->prepare("SELECT * FROM consultations c,med_labo ml WHERE c.id_consu=ml.id_consuLabo AND ml.numero=:num AND ml.dateconsu=:annee AND ml.id_consuLabo=:id_consuLabo");
						$getNber->execute(array('num'=>$ligneL->numero,'annee'=>$annee,'id_consuLabo'=>$ligneL->id_consuLabo));
						$getNber->setFetchMode(PDO::FETCH_OBJ);
						$FetchId_uM = $getNber->fetch();
						$countExa = $getNber->rowCount();

						

						//Get Doc num name
						$getDoc = $connexion->query("SELECT * FROM utilisateurs WHERE id_u=".$FetchId_uM ->id_uM."");
						$getDoc->setFetchMode(PDO::FETCH_OBJ);
						$FetchDoc = $getDoc->fetch();
						// print_r($FetchDoc);
						$DocName = $FetchDoc->full_name;

						//Count Full Results

						$getDoneExa = $connexion->prepare("SELECT * FROM consultations c,med_labo ml WHERE c.id_consu=ml.id_consuLabo AND ml.numero=:num AND ml.dateresultats=:annee AND ml.examenfait=1 AND ml.id_consuLabo=:id_consuLabo");
						$getDoneExa->execute(array('num'=>$ligneL->numero,'annee'=>$annee,'id_consuLabo'=>$ligneL->id_consuLabo));
						$countDone = $getDoneExa->rowCount();

							// Get Phone Numbers

							$GetNumber = $connexion->prepare("SELECT * FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:num");
							$GetNumber->execute(array('num'=>$ligneL->numero));
							if($phoneno = $GetNumber->fetch(PDO::FETCH_OBJ)){
								
								$phonenumber = $phoneno->telephone;
								$code = '+25';
		
								$fullnumber = $code.''.$phonenumber;
								$fullnameSms = $phoneno->nom_u;
							}else{
								$fullnumber = "";
								$fullnameSms = "";
							}

						//send sms

						if($countExa == $countDone){

							if($phonenumber!=""){

								//save in table

								/*Check if the sms is sended*/
								//echo $ligneL->id_u;
								// echo $ligneL->numero;
								$selectIfSendSmS = $connexion->query('SELECT * FROM sms_sent WHERE  id_consu='.$ligneL->id_consuLabo.' AND phone="'.$fullnumber.'"');
								$Fetchnumer = $selectIfSendSmS->fetch(PDO::FETCH_OBJ);
								$rowIfSendSmS = $selectIfSendSmS->rowCount();

								if ($rowIfSendSmS == 0) {

									//Get Iteration

									$selectIteration = $connexion->query('SELECT * FROM CountResults WHERE  id_consu='.$ligneL->id_consuLabo.' AND phone="'.$fullnumber.'"');
							    	if($Fetchiteration = $selectIteration->fetch(PDO::FETCH_OBJ)){
										$iteration = $Fetchiteration->id_che;
									}else{
										$saveIteration= $connexion->prepare('INSERT INTO CountResults(numero,id_consu,phone) VALUES(:numero,:id_consu,:phone)');
										$saveIteration->execute(array('numero'=>$ligneL->numero,'id_consu'=>$ligneL->id_consuLabo,'phone'=>$fullnumber));

										$selectIteration = $connexion->query('SELECT * FROM CountResults WHERE  id_consu='.$ligneL->id_consuLabo.' AND phone="'.$fullnumber.'"');
										$Fetchiteration = $selectIteration->fetch(PDO::FETCH_OBJ);
										$iteration = $Fetchiteration->id_che;
									}							
									

									//echo '<script type="text/javascript"> alert("'.$iteration.','.$DocName .'");</script>';


									/* The function to send sms Start */

									function send_message ( $post_body, $url, $username, $password) {
									  	$ch = curl_init( );
										$headers = array(
										  'Content-Type:application/json',
										  'Authorization:Basic '. base64_encode("$username:$password")
										);
										curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
										curl_setopt ( $ch, CURLOPT_URL, $url );
										curl_setopt ( $ch, CURLOPT_POST, 1 );
										curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
										curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_body );
										// Allow cUrl functions 20 seconds to execute
										curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
										// Wait 10 seconds while trying to connect
										curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
										$output = array();
										$output['server_response'] = curl_exec( $ch );
										$curl_info = curl_getinfo( $ch );
										$output['http_status'] = $curl_info[ 'http_code' ];
										$output['error'] = curl_error($ch);
										curl_close( $ch );
										return $output;
									} 

									// /* The function to send sms Start */

									$bodySms = "*ID:".$iteration."* Hello ".$fullnameSms ." Your Labo Exams Result  Completed,You should go to see Dr ".$DocName." Thank you!  \r\n\nFrom HOREBU MEDICAL CLINIC .";
									$username = 'horebu2021';
									$password = 'Horebu@2021!';
									$messages = array(
									  	'from'=>'HMC',
									  	'to'=>$fullnumber,
									  	'body'=> $bodySms
									);

									$result = send_message( json_encode($messages), 'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30', $username, $password );

									if ($result['http_status'] != 201) {
									  	print "Error sending: " . ($result['error'] ? $result['error'] : "HTTP status ".$result['http_status']."; Response was " .$result['server_response']);
									} else {
									  	//print "Response " . $result['server_response'];
									  	// Use json_decode($result['server_response']) to work with the response further

									  	//save in table 
										$saveSmsHistory = $connexion->prepare('INSERT INTO sms_sent(numero,id_consu,phone) VALUES(:numero,:id_consu,:phone)');
										$saveSmsHistory->execute(array('numero'=>$ligneL->numero,'id_consu'=>$ligneL->id_consuLabo,'phone'=>$fullnumber));

										echo '<script type="text/javascript"> alert("Patient Results Sent And Received Sms Successfuly.");</script>';
									}
								}
						}
					}else{
						echo '<script type="text/javascript"> alert("Patient Results Sent  But Not Yet Completed to send sms.");</script>';
					}
					
						
						echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok'.$langue.'"</script>';
						
					
						
						// echo '<script language="javascript"> alert ("\n Nom Doc = '.$nomDoc.'\n Result ='.$autreresult[$i].'\n ExtensionDoc ='.$extensionDoc.'\n Temp name = '.$_FILES['autreresult']['tmp_name'][$i].'\n Move uploaded file = '.$resultat.'\n ComptIdmedlabo = '.$comptIdmedlabo.'");</script>';
					
			
					}
					
				}			
			}
		}
		

	}

	/*------Resultat Patient Hospital--------*/
	if(isset($_POST['resultatHospbtn']))
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
		
		/* 
		$valeurLab = array();

		foreach($_POST['valeur'] as $valval)
		{
			$valeurLab[] = $valval;			   
		}
		
		 */
		$min = array();

		foreach($_POST['min'] as $valmin)
		{
			$min[] = $valmin;			   
		}
		
		
		$max = array();

		foreach($_POST['max'] as $valmax)
		{
			$max[] = $valmax;			   
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
					
					$resultIdMedLabo=$connexion->query("SELECT * FROM med_labo_hosp WHERE id_medlabo='$idmedlabo[$i]' AND numero='$num' ")or die( print_r($connexion->errorInfo()));
				
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
					
						$resultMedLabo=$connexion->prepare("UPDATE med_labo_hosp SET examenfait = :examenfait, resultats=:result,valeurLab=:valeurLab,minresultats=:min,maxresultats=:max,autreresultats=:autreresult, dateresultats=:dateresult, id_uL=:id_uL WHERE id_medlabo =:idmedLabo AND numero=:num");
						$resultMedLabo->execute(array(
						'idmedLabo'=>$idmedlabo[$i],
						'examenfait'=>$examenfait,
						'result'=>$nomDoc,
						'valeurLab'=>$_POST[''.$i.''],
						'min'=>$min[$i],
						'max'=>$max[$i],
						'autreresult'=>$resultats[$i],
						'dateresult'=>$dateresult,
						'num'=>$num,
						'id_uL'=>$iduser
						
						))or die( print_r($connexion->errorInfo()));
					
						
						echo '<script type="text/javascript"> alert("Results sent!");</script>';
						echo '<script language="javascript">document.location.href="patients1_hosp_labresult.php?num='.$num.'&examenPaHosp=ok'.$langue.'"</script>';
						
					
						
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
	/*---------Hosp------------*/
	if(isset($_POST['resultHospbtn']))
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
				
							
				$resultIdMedLabo=$connexion->query("SELECT * FROM med_labo_hosp WHERE id_medlabo='$idmedlabo' AND numero='$num' ")or die( print_r($connexion->errorInfo()));
				
				$comptIdmedlabo = $resultIdMedLabo->rowCount();
				
				if( $comptIdmedlabo != 0)
				{
					
					// echo $comptIdmedlabo;
					
					$resultMedLabo=$connexion->prepare("UPDATE med_labo_hosp SET examenfait = '1', resultats=:result, autreresultats=:autreresult, dateresultats=:dateresult, id_uL=:id_uL WHERE id_medlabo =:idmedLabo AND numero=:num");
					$resultMedLabo->execute(array(
					'idmedLabo'=>$idmedlabo,
					'result'=>$nomDoc,
					'autreresult'=>$autreresult,
					'dateresult'=>$dateresult,
					'num'=>$num,
					'id_uL'=>$iduser
					
					))or die( print_r($connexion->errorInfo()));
				
					
					if(isset($_GET['english']))
					{
						echo '<script type="text/javascript"> alert("Results sent!");</script>';
						echo '<script language="javascript">document.location.href="patients1_hosp_labresult.php?english='.$_GET['english'].'&num='.$num.'&examenPa=ok"</script>';
							
					
					}else{
						if(isset($_GET['francais']))
						{
							echo '<script type="text/javascript"> alert("Results sent!");</script>';
							echo '<script language="javascript">document.location.href="patients1_hosp_labresult.php?francais='.$_GET['francais'].'&num='.$num.'&examenPa=ok"</script>';
														
						}else{
							echo '<script type="text/javascript"> alert("Results sent!");</script>';
							echo '<script language="javascript">document.location.href="patients1_hosp_labresult.php?num='.$num.'&examenPa=ok"</script>';
					
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
		
		$valeurLab=$_POST['valeur'];
		$min=$_POST['min'];
		$max=$_POST['max'];
		
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
				echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!")	;</script>';
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
					
					$resultMedLabo=$connexion->prepare("UPDATE med_labo SET examenfait = '1', resultats=:result, valeurLab=:valeurLab, minresultats=:min, maxresultats=:max, autreresultats=:autreresult, dateresultats=:dateresult, id_uL=:id_uL WHERE id_medlabo =:idmedLabo");
					$resultMedLabo->execute(array(
					'idmedLabo'=>$idmedlabo,
					'result'=>$nomDoc,
					'valeurLab'=>$valeurLab,
					'min'=>$min,
					'max'=>$max,
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
	$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	/*----Update resultats Hosp-----*/
	if(isset($_POST['updateresultHospbtn']))
	{
	
		$idmedlabo=$_GET['updateidmedLabo'];
		$dateresult=$annee;
		
		if($_POST['examen']!="")
		{
			$autreresult=nl2br($_POST['examen']);
		}else{
			$autreresult=$_POST['examen'];
		}
		
		$valeurLab=$_POST['valeur'];
		$min=$_POST['min'];
		$max=$_POST['max'];
		
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
				
							
				$resultIdMedLabo=$connexion->query("SELECT * FROM med_labo_hosp WHERE id_medlabo='$idmedlabo' AND numero='$num' ")or die( print_r($connexion->errorInfo()));
				
				$comptIdmedlabo = $resultIdMedLabo->rowCount();
				
				if( $comptIdmedlabo != 0)
				{
					
					// echo $comptIdmedlabo;
					
					$resultMedLabo=$connexion->prepare("UPDATE med_labo_hosp SET examenfait = '1', resultats=:result, valeurLab=:valeurLab, minresultats=:min, maxresultats=:max, autreresultats=:autreresult, dateresultats=:dateresult, id_uL=:id_uL WHERE id_medlabo =:idmedLabo AND numero=:num");
					$resultMedLabo->execute(array(
					'idmedLabo'=>$idmedlabo,
					'result'=>$nomDoc,
					'valeurLab'=>$valeurLab,
					'min'=>$min,
					'max'=>$max,
					'autreresult'=>$autreresult,
					'dateresult'=>$dateresult,
					'num'=>$num,
					'id_uL'=>$iduser
					
					));
				
					
					if(isset($_GET['english']))
					{
						echo '<script type="text/javascript"> alert("Results sent!");</script>';
						echo '<script language="javascript">document.location.href="patients1_hosp_labresult.php?english='.$_GET['english'].'&num='.$num.'&examenPaHosp=ok"</script>';
							
					
					}else{
						if(isset($_GET['francais']))
						{
							echo '<script type="text/javascript"> alert("Results sent!");</script>';
							echo '<script language="javascript">document.location.href="patients1_hosp_labresult.php?francais='.$_GET['francais'].'&num='.$num.'&examenPaHosp=ok"</script>';
														
						}else{
							echo '<script type="text/javascript"> alert("Results sent!");</script>';
							echo '<script language="javascript">document.location.href="patients1_hosp_labresult.php?num='.$num.'&examenPaHosp=ok"</script>';
					
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