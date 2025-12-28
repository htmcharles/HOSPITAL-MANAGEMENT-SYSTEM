<?php
session_start();
include("connect.php");

try
{

	$annee = date('Y').'-'.date('m').'-'.date('d');

	if(isset($_POST['sendbtn']) or isset($_POST['answerbtn']))
	{
		if(isset($_POST['sendbtn']))
		{
			if(!($_FILES['annexe']['error']== UPLOAD_ERR_NO_FILE))
			{
				$extAuthorisedDoc=array('.png','.gif','.jpg','.jpeg','.pdf','.doc','.docx','.xlsx','.PNG','.GIF','.JPG','.JPEG','.PDF','.DOC','.DOCX','.XLSX');

				$extensionDoc=strrchr($_FILES['annexe']['name'],'.');
				//echo $extensionPic.'<br/>';
				
				if(!in_array($extensionDoc,$extAuthorisedDoc))
				{
					echo '<script language="javascript"> alert("Ce n\'est pas le type de document qui peut être téléchargé!");</script>';
					
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						echo '<script type="text/javascript">document.location.href="messages.php?ecrire=ok&english='.$_GET['english'].'";</script>';
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							echo '<script type="text/javascript">document.location.href="messages.php?ecrire=ok&francais='.$_GET['francais'].'";</script>';
						
						}else{
							
							echo '<script type="text/javascript">document.location.href="messages.php?ecrire=ok";</script>';
					
						}
					}
					
					
				}else{
				
					if($_FILES['annexe']['error']== UPLOAD_ERR_FORM_SIZE)
					{
						echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!");</script>';
						
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							echo '<script type="text/javascript">document.location.href="messages.php?ecrire=ok&english='.$_GET['english'].'";</script>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								echo '<script type="text/javascript">document.location.href="messages.php?ecrire=ok&francais='.$_GET['francais'].'";</script>';
							
							}else{
								
								echo '<script type="text/javascript">document.location.href="messages.php?ecrire=ok";</script>';
						
							}
						}
					
					
					}else{
						$nomDoc=time().strtolower($extensionDoc);
						
						// echo $nomDoc.'<br/>';
						
						$resultat=move_uploaded_file($_FILES['annexe']['tmp_name'], $nomDoc);
						
						/* if($resultat)
						{
							echo "<br/>Fichier transferé.<br/>";
							echo '<img src="'.$nomPic.'" height="70px" width="70px"/>';
						} */
						
						$annexe=$nomDoc;
					
					}
				}
			}else{
				$annexe="";
			}
			
			$to=$_POST['to'];
			$content=nl2br($_POST['contenu']);
			$from=$_SESSION['id'];
	
			$lu=0;
			
			if(isset($_POST['objet']))
			{
				$objet=$_POST['objet'];
			}else{
				$objet='';
			}
			
			// echo 'To :'.$to.'_Contenu :'.$content.'_Date :'.$date.'_From :'.$from.'_Lu :'.$lu.'_Objet :'.$objet;

			$resultat=$connexion->prepare("INSERT INTO messages (receiverId,senderId,contenu,annexe,objet,datemessage,lu) VALUES(:to,:from,:content,:annexe,:objet,:date,:lu)");
					
			$resultat->execute(array(
			'to'=>$to,
			'from'=>$from,
			'content'=>$content,
			'annexe'=>$annexe,
			'objet'=>$objet,
			'date'=>$annee,
			'lu'=>$lu
			));
			
			echo '<script type="text/javascript">alert("Message envoyé");</script>';

			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				echo '<script type="text/javascript">document.location.href="messages.php?envoye=ok&english='.$_GET['english'].'";</script>';
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					echo '<script type="text/javascript">document.location.href="messages.php?envoye=ok&francais='.$_GET['francais'].'";</script>';
				
				}else{
					
					echo '<script type="text/javascript">document.location.href="messages.php?envoye=ok";</script>';
			
				}
			}
					

		}else{
			
			if(isset($_POST['answerbtn']))
			{
				$idMsg=$_POST['idMsg'];
				
				if(!($_FILES['annexe']['error']== UPLOAD_ERR_NO_FILE))
				{
					$extAuthorisedDoc=array('.png','.gif','.jpg','.jpeg','.pdf','.doc','.docx','.xlsx','.PNG','.GIF','.JPG','.JPEG','.PDF','.DOC','.DOCX','.XLSX');

					$extensionDoc=strrchr($_FILES['annexe']['name'],'.');
					//echo $extensionPic.'<br/>';
					
					if(!in_array($extensionDoc,$extAuthorisedDoc))
					{
						echo '<script language="javascript"> alert("Ce n\'est pas le type de document qui peut être téléchargé!");</script>';
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$idMsg.'&ecrire=ok&english='.$_GET['english'].'";</script>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$idMsg.'&ecrire=ok&francais='.$_GET['francais'].'";</script>';
							
							}else{
								
								echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$idMsg.'&ecrire=ok";</script>';
						
							}
						}
						
					}else{
					
						if($_FILES['annexe']['error']== UPLOAD_ERR_FORM_SIZE)
						{
							echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!");</script>';
							
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$idMsg.'&ecrire=ok&english='.$_GET['english'].'";</script>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$idMsg.'&ecrire=ok&francais='.$_GET['francais'].'";</script>';
								
								}else{
									
									echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$idMsg.'&ecrire=ok";</script>';
							
								}
							}
						
						}else{
							$nomDoc=time().strtolower($extensionDoc);
							
							// echo $nomDoc.'<br/>';
							
							$resultat=move_uploaded_file($_FILES['annexe']['tmp_name'], $nomDoc);
							
							$annexe=$nomDoc;
					
						}
					}
				}else{
					$annexe="";
				}
				
				$to=$_POST['idresultsRep'];
					
				if($_POST['contenuRetour']!="")
				{
					$content=nl2br($_POST['contenuRetour']);
				}else{
					
					echo '<script type="text/javascript">alert("Veuillez saisir du texte");</script>';
					
					if(isset($_GET['english']))
					{
						// echo '&english='.$_GET['english'];
						echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$idMsg.'&ecrire=ok&english='.$_GET['english'].'";</script>';
					
					}else{
						if(isset($_GET['francais']))
						{
							// echo '&francais='.$_GET['francais'];
							echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$idMsg.'&ecrire=ok&francais='.$_GET['francais'].'";</script>';
						
						}else{
							
							echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$idMsg.'&ecrire=ok";</script>';
					
						}
					}
						
					
				}
				
				$date=$_POST['dateRetour'];			
				$from=$_SESSION['id'];
		
				$lu=0;
				
				if(isset($_POST['objet']))
				{
					$objet=$_POST['objet'];
				}else{
					$objet='';
				}
				
				// echo 'To :'.$to.'_Contenu :'.$content.'_Date :'.$date.'_From :'.$from.'_Lu :'.$lu.'_Objet :'.$objet;
				
				$resultat=$connexion->prepare("INSERT INTO messages (receiverId,senderId,contenu,annexe,objet,datemessage,lu) VALUES(:to,:from,:content,:annexe,:objet,:date,:lu)");
			
				$resultat->execute(array(
				'to'=>$to,
				'from'=>$from,
				'content'=>$content,
				'annexe'=>$annexe,
				'objet'=>$objet,
				'date'=>$date,
				'lu'=>$lu
				));
				
				echo '<script type="text/javascript">alert("Réponse envoyé");</script>';
				
				
				if(isset($_GET['english']))
				{
					// echo '&english='.$_GET['english'];
					echo '<script type="text/javascript">document.location.href="messages.php?envoye=ok&english='.$_GET['english'].'";</script>';
				
				}else{
					if(isset($_GET['francais']))
					{
						// echo '&francais='.$_GET['francais'];
						echo '<script type="text/javascript">document.location.href="messages.php?envoye=ok&francais='.$_GET['francais'].'";</script>';
					
					}else{
						
						echo '<script type="text/javascript">document.location.href="messages.php?envoye=ok";</script>';
				
					}
				}
				
			}
		}
		

	}

	
	if(isset($_GET['idMsgRecu']))
	{
				
		$lu=1;
	
		$resultats=$connexion->prepare('UPDATE messages SET lu=:lu WHERE id_message=:idMsgRecu');
					
		$resultats->execute(array(
		'idMsgRecu'=>$_GET['idMsgRecu'],
		'lu'=>$lu

		));

		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$_GET['idMsgRecu'].'&ecrire=ok&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$_GET['idMsgRecu'].'&ecrire=ok&francais='.$_GET['francais'].'";</script>';
			
			}else{
				
				echo '<script type="text/javascript">document.location.href="messages.php?idMsgRecu='.$_GET['idMsgRecu'].'&ecrire=ok";</script>';
		
			}
		}

	}	

	if(isset($_GET['back']))
	{
				
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="messages.php?recuAll=ok&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="messages.php?recuAll=ok&francais='.$_GET['francais'].'";</script>';
			
			}else{
				
				echo '<script type="text/javascript">document.location.href="messages.php?recuAll=ok";</script>';
		
			}
		}

	}

	if(isset($_GET['idMsgDeleteRecu']))
	{
		$resultats=$connexion->prepare('DELETE FROM messages WHERE id_message=:id_msg');
		$resultats->execute(array(
		'id_msg'=>$_GET['idMsgDeleteRecu']
		));
		
		echo '<script type="text/javascript"> alert("Le message reçu a bien été supprimé");</script>';
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="messages.php?recu=ok&english='.$_GET['english'].'";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="messages.php?recu=ok&francais='.$_GET['francais'].'";</script>';
			
			}else{
				
				echo '<script type="text/javascript">document.location.href="messages.php?recu=ok";</script>';
		
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