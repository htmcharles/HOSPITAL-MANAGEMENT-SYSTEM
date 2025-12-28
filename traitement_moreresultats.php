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
	
	
 	if(isset($_POST['moreresultatbtn']))
	{
		
		$num=$_GET['num'];
					
		$idmoremedlabo = array();

		foreach($_POST['idmoremedLaboResult'] as $valeur)
		{
			$idmoremedlabo[] = $valeur;			   
		}
		
		
		$moreresultats = array();

		foreach($_POST['moreresultats'] as $value)
		{
			$moreresultats[] = $value;			   
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
		
		
		$moreautreresult = array();

		foreach($_FILES['moreautreresult']['name'] as $val)
		{
			$moreautreresult[] = $val;			   
		}
		
		
		for($i=0;$i<sizeof($idmoremedlabo);$i++)
		{	
			/* 
				echo $idmoremedlabo[$i].'<br/>';
				echo $moreresultats[$i].'<br/>';
				echo $min[$i].'<br/>';
				echo $max[$i].'<br/>';
				echo $moreautreresult[$i].'<br/><br/>';
			*/
			$extAuthorisedDoc=array('.png','.gif','.jpg','.jpeg','.pdf','.doc','.docx','.xlsx','.PNG','.GIF','.JPG','.JPEG','.PDF','.DOC','.DOCX','.XLSX','');

			$extensionDoc=strrchr($moreautreresult[$i],'.');
			//echo $extensionPic.'<br/>';
			
			if(!in_array($extensionDoc,$extAuthorisedDoc))
			{
				echo '<script language="javascript"> alert("Ce n\'est pas le type de document qui peut être téléchargé!");</script>';
				echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok'.$langue.'"</script>';
				
			}else{
		
				if($_FILES['moreautreresult']['error'][$i]== UPLOAD_ERR_FORM_SIZE)
				{
					echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!");</script>';
					echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok'.$langue.'"</script>';
				
				}else{
			
					if($extensionDoc!="")
					{
						$nomDoc=$moreautreresult[$i];
						$tempName=$_FILES['moreautreresult']['tmp_name'][$i];
						
						$resultat=move_uploaded_file($_FILES['moreautreresult']['tmp_name'][$i], $nomDoc);
					
					}else{
						$nomDoc="";
						$tempName="";
						
						$resultat="";
					}
					
					$resultIdMoreMedLabo=$connexion->query("SELECT * FROM more_med_labo WHERE id_moremedlabo='$idmoremedlabo[$i]'")or die( print_r($connexion->errorInfo()));
				
					$comptIdmoremedlabo = $resultIdMoreMedLabo->rowCount();
					
					if( $comptIdmoremedlabo != 0)
					{
						if($moreresultats[$i] != "" OR $nomDoc != "")
						{
							$iduser=$_SESSION['id'];					
						}else{
							$iduser=NULL;
						}

						
						$CheckIdMoreMedLabo = $resultIdMoreMedLabo->fetch(PDO::FETCH_OBJ);
						$idIdmedlabo = $CheckIdMoreMedLabo->id_medlabo;

						$selectIdMedLabo = $connexion->query("SELECT * FROM med_labo WHERE id_medlabo='$idIdmedlabo'");
						$ligneL=$selectIdMedLabo->fetch(PDO::FETCH_OBJ);
					
						$resultMoreMedLabo=$connexion->prepare("UPDATE more_med_labo SET resultats=:result,valeurLab=:valeurLab, minresultats=:min,maxresultats=:max, autreresultats=:moreautreresult, id_uL=:id_uL WHERE id_moremedlabo =:idmedLabo");
						$resultMoreMedLabo->execute(array(
						'idmedLabo'=>$idmoremedlabo[$i],
						'result'=>$nomDoc,
						'valeurLab'=>$_POST[''.$i.''],
						'min'=>$min[$i],
						'max'=>$max[$i],
						'moreautreresult'=>$moreresultats[$i],
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
									

									// echo '<script type="text/javascript"> alert("'.$iteration.','.$DocName .'");</script>';


									/* The function to send sms Start */

									// function send_message ( $post_body, $url, $username, $password) {
									//   	$ch = curl_init( );
									// 	$headers = array(
									// 	  'Content-Type:application/json',
									// 	  'Authorization:Basic '. base64_encode("$username:$password")
									// 	);
									// 	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
									// 	curl_setopt ( $ch, CURLOPT_URL, $url );
									// 	curl_setopt ( $ch, CURLOPT_POST, 1 );
									// 	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
									// 	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_body );
									// 	// Allow cUrl functions 20 seconds to execute
									// 	curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
									// 	// Wait 10 seconds while trying to connect
									// 	curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
									// 	$output = array();
									// 	$output['server_response'] = curl_exec( $ch );
									// 	$curl_info = curl_getinfo( $ch );
									// 	$output['http_status'] = $curl_info[ 'http_code' ];
									// 	$output['error'] = curl_error($ch);
									// 	curl_close( $ch );
									// 	return $output;
									// } 

									/* The function to send sms Start */

									// $bodySms = "*ID:".$iteration."* Hello ".$fullnameSms ." Your Labo Exams Result  Completed,You should go to see Dr ".$DocName." Thank you!  \r\n\nFrom HOREBU MEDICAL CLINIC .";
									// $username = 'horebu2021';
									// $password = 'Horebu@2021!';
									// $messages = array(
									//    	'from'=>'HMC',
									//    	'to'=>$fullnumber,
									//    	'body'=> $bodySms
									// );

									// $result = send_message( json_encode($messages), 'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30', $username, $password );

									// if ($result['http_status'] != 201) {
									//    	print "Error sending: " . ($result['error'] ? $result['error'] : "HTTP status ".$result['http_status']."; Response was " .$result['server_response']);
									// } else {
									//    	//print "Response " . $result['server_response'];
									//    	// Use json_decode($result['server_response']) to work with the response further

									//    	//save in table 
									//  	$saveSmsHistory = $connexion->prepare('INSERT INTO sms_sent(numero,id_consu,phone) VALUES(:numero,:id_consu,:phone)');
									//  	$saveSmsHistory->execute(array('numero'=>$ligneL->numero,'id_consu'=>$ligneL->id_consuLabo,'phone'=>$fullnumber));

									//  	echo '<script type="text/javascript"> alert("Patient Results Sent And Received Sms Successfuly.");</script>';
									// }
								}
							}
						}else{
							// echo '<script type="text/javascript"> alert("Patient Results Sent  But Not Yet Completed to send sms.");</script>';
						}
						
						echo '<script type="text/javascript"> alert("Patient Results Sent Successfuly.");</script>';

						echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok'.$langue.'"</script>';
						
					
						
						// echo '<script language="javascript"> alert ("\n Nom Doc = '.$nomDoc.'\n Result ='.$moreautreresult[$i].'\n ExtensionDoc ='.$extensionDoc.'\n Temp name = '.$_FILES['moreautreresult']['tmp_name'][$i].'\n Move uploaded file = '.$resultat.'\n ComptIdmedlabo = '.$comptIdmedlabo.'");</script>';
					
			
					}
					
				}			
			}
		}
		

	}
	
	
	

?>