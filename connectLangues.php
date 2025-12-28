<?php

		// echo date('Y-m-d H:i');
		/* $today = date('Y-m-d H:i');
		$boomDay = '2017-12-21 13:00';
		
		$today = date('Y-m-d H:i', strtotime($today));
		$boomDay = date('Y-m-d H:i', strtotime($boomDay)); */
		
		// echo 'if('.$today.' > '.$boomDay.')<br/>';
		
		/* if($today > $boomDay)
		{
			sleep(15);
		} */
	include("connect.php");
	include("Language.php");
	
	if(isset($_GET['francais']))
	{
		$_SESSION['langue']='francais';
	}else{
		if(isset($_GET['english']))
		{
			$_SESSION['langue']='english';
		}else{
			$_SESSION['langue']='english';
		}
	}
	
	$langue=$_SESSION['langue'];
		
		function getString($id_langue)
		{
			include("connect.php");

			$resL=$connexion->query('SELECT * FROM langues');

			$resL->setFetchMode(PDO::FETCH_OBJ);
			$arrayIdLangue = array();
			$arrayFrancais = array();
			$arrayEnglish = array();
			
			while($ligneL=$resL->fetch())
			{
				$arrayIdLangue[] = $ligneL->id_langue;
				$arrayFrancais[] = $ligneL->francais;
				$arrayEnglish[] = $ligneL->english;
			}
	
		
			if($_SESSION['langue'] == 'francais')
			{
				return $arrayFrancais[$id_langue - 1];
			/* }else if(getLang() == 'francais')
			{
				return $arrayFrancais[$id_langue - 1]; */
			}else{
				return $arrayEnglish[$id_langue - 1];
			}
		
		}
		
		function getPresta($id_langue)
		{
			include("connect.php");
			
			$resL=$connexion->query('SELECT * FROM prestations');
			
			$resL->setFetchMode(PDO::FETCH_OBJ);
			
			$arrayIdLangue = array();
			$arrayFrancais = array();
			$arrayEnglish = array();
			
			while($ligneL=$resL->fetch())
			{
				$arrayIdLangue[] = $ligneL->id_prestation;
				$arrayFrancais[] = $ligneL->nompresta;
				$arrayEnglish[] = $ligneL->namepresta;
			}
			
			if($_SESSION['langue'] == 'francais')
			{
				if( $arrayFrancais[$id_langue - 1] != '')
				{
					return $arrayFrancais[$id_langue - 1];
				}else{
					return $arrayEnglish[$id_langue - 1];
				}
								
			/* }elseif(getLang() == 'francais')
			{
				if( $arrayFrancais[$id_langue - 1] != '')
				{
					return $arrayFrancais[$id_langue - 1];
				}else{
					return $arrayEnglish[$id_langue - 1];
				} */
			}else{
				if( $arrayEnglish[$id_langue - 1] != '')
				{
					return $arrayEnglish[$id_langue - 1];
				}else{
					return $arrayFrancais[$id_langue - 1];
				}
			}
		}
		
		function getCatego($id_langue)
		{
			include("connect.php");
			
			$resL=$connexion->query('SELECT * FROM categopresta_ins');
			
			$resL->setFetchMode(PDO::FETCH_OBJ);
			
			$arrayIdLangue = array();
			$arrayFrancais = array();
			$arrayEnglish = array();
			
			while($ligneL=$resL->fetch())
			{
				$arrayIdLangue[] = $ligneL->id_categopresta;
				$arrayFrancais[] = $ligneL->nomcategopresta;
				$arrayEnglish[] = $ligneL->namecategopresta;
			}
			
			if($_SESSION['langue'] == 'francais')
			{
				if( $arrayFrancais[$id_langue - 1] != '')
				{
					return $arrayFrancais[$id_langue - 1];
				}else{
					return $arrayEnglish[$id_langue - 1];
				}
			/* }elseif(getLang() == 'francais')
			{
				if( $arrayFrancais[$id_langue - 1] != '')
				{
					return $arrayFrancais[$id_langue - 1];
				}else{
					return $arrayEnglish[$id_langue - 1];
				}	 */				
			}else{
				if( $arrayEnglish[$id_langue - 1] != '')
				{
					return $arrayEnglish[$id_langue - 1];
				}else{
					return $arrayFrancais[$id_langue - 1];
				}
			}
		}

?>