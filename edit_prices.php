<?php 
    session_start();
    include("connect.php");
    include("connectLangues.php");
    include("serialNumber.php");

    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['updateprice'])) {
        $assurance_name = $_POST['assurances_name'];
        $presta_assu = 'prestations_'.$assurance_name;

    	$assurances_name=$_POST['assurances_name'];
    	$id_prestation=$_POST['idpresta'];
        $nompresta=$_POST['nompresta'];
    	$namepresta=$_POST['namepresta'];
    	$prixpresta=$_POST['prixpresta'];
    	$mesure=$_POST['mesure'];
        
        $updatepricePresta=$connexion->prepare('UPDATE prestations_'.$assurances_name.' SET nompresta=:nompresta,namepresta=:namepresta,prixpresta=:prix,mesure=:mesure WHERE id_prestation =:id');
        $updatepricePresta->execute(array(
            'nompresta'=>$nompresta,
            'namepresta'=>$namepresta,
        	'prix'=>$prixpresta,
        	'mesure'=>$mesure,
        	'id'=>$id_prestation
        ))or die(print_r($connexion->errorInfo()));

        $compt = $updatepricePresta->rowCount();
        if ($compt!=0) {
            $link = 'prices_edit.php';
        	header('location:'.$link.'?assurances_name='.$_POST['assurances_name'].'');
        }else{
            $link = 'prices_edit.php';
            header('location:'.$link.'?assurances_name='.$_POST['assurances_name'].'');
        }

    }

    if (isset($_POST['prestabtn'])) {
        $assurance_name = $_GET['assurances_name'];
        $presta_assu = 'prestations_'.$assurance_name;

        $nompresta=$_POST['nompresta'];
        $namepresta=$_POST['namepresta'];
        $prixpresta=$_POST['prixpresta'];
        $id_categopresta=$_POST['id_categopresta'];
        $mesure=$_POST['mesure'];
        $statupresta=$_POST['statupresta'];
        
        $insertNewpresta = $connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta, namepresta, prixpresta, id_categopresta, mesure, statupresta) VALUES (:nompresta, :namepresta, :prixpresta, :id_categopresta, :mesure, :statupresta)');
        $insertNewpresta->execute(array(
            'nompresta'=>$nompresta,
            'namepresta'=>$namepresta,
            'prixpresta'=>$prixpresta,
            'id_categopresta'=>$id_categopresta,
            'mesure'=>$mesure,
            'statupresta'=>$statupresta,
        ));

        $compt = $insertNewpresta->rowCount();
       /* if ($compt!=0) {
            $link = 'prices_edit.php';
            header('location:'.$link.'?assurances_name='.$assurance_name.'');
        }else{
            $link = 'prices_edit.php';
            header('location:'.$link.'?assurances_name='.$_POST['assurances_name'].'');
        }*/


        echo '<script>document.location.href="../prices_edit.php?assurances_name='.$assurance_name.'"</script>';

    }



    if (isset($_GET['actifbtn'])) {
       // echo "string";
        $assurance_name = $_GET['assurances_name'];
        $presta_assu = 'prestations_'.$assurance_name;

        $modifierId=$_GET['id_prestation'];
        $page=$_GET['page'];
        $newStatu=1;

        /*$resultats=$connexion->query('SELECT statupresta FROM '.$presta_assu.' WHERE id_prestation="'.$modifierId.'"');
            
        //$num_rows=$resultats->fetchColumn();
        $num_rows=$resultats->rowCount();
        
        if( $num_rows != 0)
        {*/
            $resultats=$connexion->prepare('UPDATE '.$presta_assu.' SET statupresta=:statupresta WHERE id_prestation=:iduti');
            
            $resultats->execute(array(
            'iduti'=>$modifierId,           
            'statupresta'=>$newStatu        
            ));     
       // }

        $link = 'prices_edit.php';

        if (isset($_GET['divCash'])) {
            echo "string";
            header('Location:'.$link.'?id_prestation='.$modifierId.'&assurances_name='.$assurance_name.'&divCash=ok');
        }else{
            header('Location:'.$link.'?page='.$page.'&assurances_name='.$assurance_name.'');
        }

    }else{
        if (isset($_GET['desactifbtn'])) {
            $assurance_name = $_GET['assurances_name'];
            $presta_assu = 'prestations_'.$assurance_name;
            $modifierId=$_GET['id_prestation'];
            $page=$_GET['page'];
            $newStatu= 0;
            $newprix = 0;


            /*$resultats=$connexion->query('SELECT prixpresta,statupresta FROM '.$presta_assu.' WHERE id_prestation="'.$modifierId.'"');
                
            //$num_rows=$resultats->fetchColumn();
            $num_rows=$resultats->rowCount();
            
            if( $num_rows != 0)
            {*/
                $resultats=$connexion->prepare('UPDATE '.$presta_assu.' SET prixpresta=:newprix, statupresta=:statupresta WHERE id_prestation=:iduti');
                
                $resultats->execute(array(
                'iduti'=>$modifierId,           
                'statupresta'=>$newStatu,       
                'newprix'=>$newprix     
                ));     
           // }

            $link = 'prices_edit.php';
            
            if (isset($_GET['divCash'])) {
                echo "string";
                header('Location:'.$link.'?id_prestation='.$modifierId.'&assurances_name='.$assurance_name.'&divCash=ok');
            }else{
                header('Location:'.$link.'?page='.$page.'&assurances_name='.$assurance_name.'');
            }
        }
    }
?>