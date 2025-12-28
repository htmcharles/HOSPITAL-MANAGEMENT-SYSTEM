<?php
	try {
		session_start();

		//connexion à la base de données 
		include("connect.php");
		include("connectLangues.php"); 

		$assurance_name = $_GET['assurance'];
		$presta_assu = 'prestations_'.$assurance_name;



		if (isset($_GET['acteActive']) {
			$modifierId=$_GET['acteActive'];
			$page=$_GET['page'];
			$newStatu=1;

			$resultats=$connexion->query('SELECT statupresta FROM '.$presta_assu.' WHERE id_prestation="'.$modifierId.'"');
				
			//$num_rows=$resultats->fetchColumn();
			$num_rows=$resultats->rowCount();
			
			if( $num_rows != 0)
			{
				$resultats=$connexion->prepare('UPDATE '.$presta_assu.' SET statupresta=:statupresta WHERE id_prestation=:iduti');
				
				$resultats->execute(array(
				'iduti'=>$modifierId,			
				'statupresta'=>$newStatu		
				));		
			}

			$link = 'prices_edit_'.$assurance_name.'.php';
			//header('Location:'.$link.'?page='.$page.'&assurances_name='.$assurance_name.'');
		}else{
			if (isset($_GET['acteDesactif']) {
				$modifierId=$_GET['acteDesactif'];
				$page=$_GET['page'];
				$newStatu=1;
				$newprix = 0;


				$resultats=$connexion->query('SELECT prixpresta,statupresta FROM '.$presta_assu.' WHERE id_prestation="'.$modifierId.'"');
					
				//$num_rows=$resultats->fetchColumn();
				$num_rows=$resultats->rowCount();
				
				if( $num_rows != 0)
				{
					$resultats=$connexion->prepare('UPDATE '.$presta_assu.' SET prixpresta=:newprix,statupresta=:statupresta WHERE id_prestation=:iduti');
					
					$resultats->execute(array(
					'iduti'=>$modifierId,			
					'statupresta'=>$newStatu,		
					'newprix'=>$newprix		
					));		
				}

				$link = 'prices_edit_'.$assurance_name.'.php';
				//header('Location:'.$link.'?page='.$page.'&assurances_name='.$assurance_name.'');
			}
		}
	} catch (Exception $e) {
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
	}
		
 
?>