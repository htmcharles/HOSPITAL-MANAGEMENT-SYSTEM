<?php

if(isset($_POST['submitExa']))
{
	if(!($_FILES['res']['error']== UPLOAD_ERR_NO_FILE))
	{
		$extAuthorisedDoc=array('.png','.gif','.jpg','.jpeg','.pdf','.doc','.docx','.PNG','.GIF','.JPG','.JPEG','.PDF','.DOC','.DOCX');

		$extensionDoc=strrchr($_FILES['res']['name'],'.');
		//echo $extensionPic.'<br/>';
		
		if(!in_array($extensionDoc,$extAuthorisedDoc))
		{
			echo '<script language="javascript"> alert("Ce n\'est pas le type de document qui peut être téléchargé! ='.$extensionDoc.'");</script>';
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&examenPa=ok";</script>';
			
		}else{
		
			if($_FILES['res']['error']== UPLOAD_ERR_FORM_SIZE)
			{
				echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!");</script>';
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&examenPa=ok";</script>';
			
			}else{
				$nomDoc=time().strtolower($extensionDoc);
				
				echo $nomDoc.'<br/>';
				
				$resultat=move_uploaded_file($_FILES['res']['tmp_name'], $nomDoc);
				
				/* if($resultat)
				{
					echo "<br/>Fichier transferé.<br/>";
					echo '<img src="'.$nomPic.'" height="70px" width="70px"/>';
				} */
			
				
				
				echo '*ID du resultats: '.$idmedLabo.'<br/>*Date des résultats: '.$annee.'<br/>*ID du Laborantin: '.$idLabo.'<br/>*Numero Patient: '.$_GET['num'].'<br/>*Résultats: '.$nomDoc; 

				
				/* $resultMedLabo=$connexion->prepare("UPDATE med_labo SET examenfait = '1', id_uL=:idLabo, resultats=:nomDoc, dateresultats=:datetoday WHERE id_medlabo =:idmedLabo");			
				$resultMedLabo->execute(array(
				
				'idmedLabo'=>$idmedLabo,
				'idLabo'=>$idLabo,
				'nomDoc'=>$nomDoc,
				'datetoday'=>$datetoday
			
				))or die( print_r($connexion->errorInfo()));
			
				echo '<script type="text/javascript"> alert("Vous venez d\' envoyer les résultats de '.$exam.'");</script>';
				
				echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&examenPa=ok";</script>';
						 */
			}
		}
		
	}else{
		echo '<script language="javascript"> alert("Veuillez charger des résultats!!");</script>';
		echo '<script type="text/javascript">document.location.href="patients1.php?num='.$_GET['num'].'&examenPa=ok";</script>';
	}

	
}

?>