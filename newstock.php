<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");
 $annee = date('Y').'-'.date('m').'-'.date('d');

// echo $annee;

if(isset($_GET['Consomstockout'])){
    $statusCate = 21;
}else{
    if(isset($_GET['Medicanentstockout'])){
        $statusCate = 22;
    }else{
        $statusCate = 23;
    }
}

 //Selection of Expired Drugs
//  echo "SELECT * FROM `stockin` WHERE `quantity`!=0 AND `status`=".$statusCate." AND `expireddate` <= '$annee'";
 $EXp = $connexion->query("SELECT * FROM `stockin` WHERE `quantity`!=0 AND `status`=".$statusCate." AND `expireddate` <= '$annee'");
 $EXp->setFetchMode(PDO::FETCH_OBJ);
 $countD=$EXp->rowCount();

if (isset($_POST['savenewstockbtn'])) {
    $sizeoftable = $_POST['sizeoftable'];
    for ($i=0; $i < $sizeoftable; $i++) { 
        $pname = 'pname'.$i;
        $pid = 'pid'.$i;
        $mesure = 'mesure'.$i;
        $price = 'price'.$i;
        $quantity = 'quantity'.$i;
        $manufacturedate = 'manufacturedate'.$i;
        $expireddate = 'expireddate'.$i;
        $Barcode = 'Barcode'.$i;
        $suppliername = 'suppliername'.$i;
        $stockin = 'stockin'.$i;
        
        $pname = $_POST[$pname];
        $pid = $_POST[$pid];
        $mesure = $_POST[$mesure];
        $price = $_POST[$price];
        $quantity = $_POST[$quantity];
        $manufacturedate = $_POST[$manufacturedate];
        $expireddate = $_POST[$expireddate];
        $Barcode = $_POST[$Barcode];
        $suppliername = $_POST[$suppliername];
        $stockin = $_POST[$stockin];

        //CHECK IF THAT IS EXIST

        $CheckStock = $connexion->prepare('SELECT * FROM stockin WHERE pid=:pid AND product_name=:product_name');
        $CheckStock->execute(array('pid'=>$pid,'product_name'=>$pname));
        $CheckStock->setFetchMode(PDO::FETCH_OBJ);
        $countStock=$CheckStock->rowCount();
        if ($countStock == 0) {
            $productinsertion=$connexion->prepare('INSERT INTO stockin (
                pid,
                product_name,
                quantity,
                mesure,
                stokin,
                manufacturedate,
                expireddate,
                price,
                suppliername,
                barcode,
                addby
            ) 

            VALUES (
                :pid,
                :product_name,
                :quantity,
                :mesure,
                :stokin,
                :manufacturedate,
                :expireddate,
                :price,
                :suppliername,
                :barcode,
                :addby)
            ');

        $productinsertion->execute(array(
            'pid'=>$pid,
            'product_name'=>$pname,
            'quantity'=>$quantity,
            'mesure'=>$mesure,
            'stokin'=>$stockin,
            'manufacturedate'=>$manufacturedate,
            'expireddate'=>$expireddate,
            'price'=>$price,
            'suppliername'=>$suppliername,
            'barcode'=>$Barcode,
            'addby'=>$_SESSION['id']
        ));


        $SELECTLastid = $connexion->query('SELECT * FROM stockin ORDER BY sid DESC');
        $SELECTLastid ->setFetchMode(PDO::FETCH_OBJ);
        if($GetLastId  = $SELECTLastid->fetch()){
            $lastid = $GetLastId->sid;

            // Update Stockin History to Justify How Drugs Stored In stock
             $InsertHistoIn = $connexion->prepare("INSERT INTO stockin_history(sid, quantityIn, mesure, doneon,doneby) VALUES (:sid,:quantity,:mesure,:doneon,:doneby)");
             $InsertHistoIn->execute(array('sid'=>$lastid,'quantity'=>$quantity,'mesure'=>$mesure,'doneon'=>$stockin,'doneby'=>$_SESSION['id']));
        }

        }else{
            $GetPro = $CheckStock->fetch();

            //print_r($Produ);

            echo "<script>alert('".$GetPro->product_name."  Already Exist in Stock Do you Want To Increase Quantity?');</script>";

            if($price !=""){
                if ($price == $GetPro->price) {

                    $exisQ = $GetPro->quantity;
                    $allq =  $exisQ + $quantity;

                    // Update Stockin History to Justify How Consummables Stored In stock
                     $InsertHistoIn = $connexion->prepare("INSERT INTO stockin_history(sid, quantityIn, mesure, doneon,doneby) VALUES (:sid,:quantity,:mesure,:doneon,:doneby)");
                    $InsertHistoIn->execute(array('sid'=>$GetPro->sid,'quantity'=>$quantity,'mesure'=>$mesure,'doneon'=>$stockin,'doneby'=>$_SESSION['id']));

                     //Update

                     $updateStokin = $connexion->prepare("UPDATE stockin SET quantity=:quantity WHERE sid=:sid");
                     $updateStokin->execute(array('quantity'=>$allq,'sid'=>$GetPro->sid));
                }else{

                    $productinsertion=$connexion->prepare('INSERT INTO stockin (
                    pid,
                    product_name,
                    quantity,
                    mesure,
                    stokin,
                    manufacturedate,
                    expireddate,
                    price,
                    suppliername,
                    barcode,
                    addby
                ) 

                VALUES (
                    :pid,
                    :product_name,
                    :quantity,
                    :mesure,
                    :stokin,
                    :manufacturedate,
                    :expireddate,
                    :price,
                    :suppliername,
                    :barcode,
                    :addby)
                ');

                $productinsertion->execute(array(
                    'pid'=>$pid,
                    'product_name'=>$pname,
                    'quantity'=>$quantity,
                    'mesure'=>$mesure,
                    'stokin'=>$stockin,
                    'manufacturedate'=>$manufacturedate,
                    'expireddate'=>$expireddate,
                    'price'=>$price,
                    'suppliername'=>$suppliername,
                    'barcode'=>$Barcode,
                    'addby'=>$_SESSION['id']
                ));

            $SELECTLastid = $connexion->query('SELECT * FROM stockin ORDER BY sid DESC');
            $SELECTLastid ->setFetchMode(PDO::FETCH_OBJ);
            if($GetLastId  = $SELECTLastid->fetch()){
                $lastid = $GetLastId->sid;

                // Update Stockin History to Justify How Consummables Stored In stock
                $InsertHistoIn = $connexion->prepare("INSERT INTO stockin_history(sid, quantityIn, mesure, doneon,doneby) VALUES (:sid,:quantity,:mesure,:doneon,:doneby)");
                $InsertHistoIn->execute(array('sid'=>$lastid,'quantity'=>$quantity,'mesure'=>$mesure,'doneon'=>$stockin,'doneby'=>$_SESSION['id']));
            }

                }
            }
            

        }


        
       // echo $count=$productinsertion->rowCount();

    }
}


// Consumbables Entry

if (isset($_POST['savenewstockbtnConsom'])) {
    $sizeoftable = $_POST['sizeoftable'];
    $status = 21;
    for ($i=0; $i < $sizeoftable; $i++) { 

        $pname = 'pname'.$i;
        $pid = 'pid'.$i;
        $mesure = 'mesure'.$i;
        $price = 'price'.$i;
        $stockin = 'stockin'.$i;
        
        // $prixpresta = 'prixpresta'.$i;
        $quantity = 'quantity'.$i;
        $manufacturedate = 'manufacturedate'.$i;
        $expireddate = 'expireddate'.$i;
        $Barcode = 'Barcode'.$i;
        $suppliername = 'suppliername'.$i;
        
        $pname = $_POST[$pname];
        $pid = $_POST[$pid];
        $mesure = $_POST[$mesure];
        $price = $_POST[$price];
        // $prixpresta = $_POST[$prixpresta];
        $quantity = $_POST[$quantity];
        $manufacturedate = $_POST[$manufacturedate];
        $expireddate = $_POST[$expireddate];
        $Barcode = $_POST[$Barcode];
        $suppliername = $_POST[$suppliername];
        $stockin = $_POST[$stockin];

        //CHECK IF THAT IS EXIST

        $CheckStock = $connexion->prepare('SELECT * FROM stockin WHERE pid=:pid AND product_name=:product_name');
        $CheckStock->execute(array('pid'=>$pid,'product_name'=>$pname));
        $CheckStock->setFetchMode(PDO::FETCH_OBJ);
        $countStock=$CheckStock->rowCount();
        if ($countStock == 0) {

            $productinsertion=$connexion->prepare('INSERT INTO stockin (
                pid,
                product_name,
                quantity,
                mesure,
                stokin,
                manufacturedate,
                expireddate,
                price,
                suppliername,
                barcode,
                addby,
                status
            ) 

            VALUES (
                :pid,
                :product_name,
                :quantity,
                :mesure,
                :stokin,
                :manufacturedate,
                :expireddate,
                :price,
                :suppliername,
                :barcode,
                :addby,
                :status)
            ');

        $productinsertion->execute(array(
            'pid'=>$pid,
            'product_name'=>$pname,
            'quantity'=>$quantity,
            'mesure'=>$mesure,
            'stokin'=>$stockin,
            'manufacturedate'=>$manufacturedate,
            'expireddate'=>$expireddate,
            'price'=>$price,
            'suppliername'=>$suppliername,
            'barcode'=>$Barcode,
            'addby'=>$_SESSION['id'],
            'status'=>$status
        ));


        $SELECTLastid = $connexion->query('SELECT * FROM stockin ORDER BY sid DESC');
        $SELECTLastid ->setFetchMode(PDO::FETCH_OBJ);
        if($GetLastId  = $SELECTLastid->fetch()){
            $lastid = $GetLastId->sid;

            // Update Stockin History to Justify How Consummables Stored In stock
             $InsertHistoIn = $connexion->prepare("INSERT INTO stockin_history(sid, quantityIn, mesure, doneon,doneby,status) VALUES (:sid,:quantity,:mesure,:doneon,:doneby,:status)");
             $InsertHistoIn->execute(array('sid'=>$lastid,'quantity'=>$quantity,'mesure'=>$mesure,'doneon'=>$stockin,'doneby'=>$_SESSION['id'],'status'=>$status));
        }

        }else{
            $GetPro = $CheckStock->fetch();

            //print_r($Produ);

            echo "<script>alert('".$GetPro->product_name."  Already Exist in Stock Do you Want To Increase Quantity?');</script>";

            if($price !=""){
                if ($price == $GetPro->price) {

                    $exisQ = $GetPro->quantity;
                    $allq =  $exisQ + $quantity;

                    // Update Stockin History to Justify How Consummables Stored In stock
                     $InsertHistoIn = $connexion->prepare("INSERT INTO stockin_history(sid, quantityIn, mesure, doneon,doneby,status) VALUES (:sid,:quantity,:mesure,:doneon,:doneby,:status)");
                     $InsertHistoIn->execute(array('sid'=>$GetPro->sid,'quantity'=>$quantity,'mesure'=>$mesure,'doneon'=>$stockin,'doneby'=>$_SESSION['id'],'status'=>$status));

                     //Update

                     $updateStokin = $connexion->prepare("UPDATE stockin SET quantity=:quantity WHERE sid=:sid");
                     $updateStokin->execute(array('quantity'=>$allq,'sid'=>$GetPro->sid));
                }else{

                $productinsertion=$connexion->prepare('INSERT INTO stockin (
                    pid,
                    product_name,
                    quantity,
                    mesure,
                    stokin,
                    manufacturedate,
                    expireddate,
                    price,
                    suppliername,
                    barcode,
                    addby,
                    status
                ) 

                VALUES (
                    :pid,
                    :product_name,
                    :quantity,
                    :mesure,
                    :stokin,
                    :manufacturedate,
                    :expireddate,
                    :price,
                    :suppliername,
                    :barcode,
                    :addby,
                    :status)
                ');

            $productinsertion->execute(array(
                'pid'=>$pid,
                'product_name'=>$pname,
                'quantity'=>$quantity,
                'mesure'=>$mesure,
                'stokin'=>$stockin,
                'manufacturedate'=>$manufacturedate,
                'expireddate'=>$expireddate,
                'price'=>$price,
                'suppliername'=>$suppliername,
                'barcode'=>$Barcode,
                'addby'=>$_SESSION['id'],
                'status'=>$status
            ));

            $SELECTLastid = $connexion->query('SELECT * FROM stockin ORDER BY sid DESC');
            $SELECTLastid ->setFetchMode(PDO::FETCH_OBJ);
            if($GetLastId  = $SELECTLastid->fetch()){
                $lastid = $GetLastId->sid;

                // Update Stockin History to Justify How Consummables Stored In stock
                 $InsertHistoIn = $connexion->prepare("INSERT INTO stockin_history(sid, quantityIn, mesure, doneon,doneby,status) VALUES (:sid,:quantity,:mesure,:doneon,:doneby,:status)");
                 $InsertHistoIn->execute(array('sid'=>$lastid,'quantity'=>$quantity,'mesure'=>$mesure,'doneon'=>$stockin,'doneby'=>$_SESSION['id'],'status'=>$status));
            }

                }
            }


            
        }


        
       // echo $count=$productinsertion->rowCount();

    }
}



// Material Entry

if (isset($_POST['savenewstockbtnMaterial'])) {
    $sizeoftable = $_POST['sizeoftable'];
    $status = 23;
    for ($i=0; $i < $sizeoftable; $i++) { 

        $pname = 'pname'.$i;
        $pid = 'pid'.$i;
        $mesure = 'mesure'.$i;
        $price = 'price'.$i;
        $stockin = 'stockin'.$i;
        
        // $prixpresta = 'prixpresta'.$i;
        $quantity = 'quantity'.$i;
        $manufacturedate = 'manufacturedate'.$i;
        $expireddate = 'expireddate'.$i;
        $Barcode = 'Barcode'.$i;
        $suppliername = 'suppliername'.$i;
        
        $pname = $_POST[$pname];
        $pid = $_POST[$pid];
        $mesure = $_POST[$mesure];
        $price = $_POST[$price];
        // $prixpresta = $_POST[$prixpresta];
        $quantity = $_POST[$quantity];
        $manufacturedate = $_POST[$manufacturedate];
        $expireddate = $_POST[$expireddate];
        $Barcode = $_POST[$Barcode];
        $suppliername = $_POST[$suppliername];
        $stockin = $_POST[$stockin];

        //CHECK IF THAT IS EXIST

        $CheckStock = $connexion->prepare('SELECT * FROM stockin WHERE pid=:pid AND product_name=:product_name');
        $CheckStock->execute(array('pid'=>$pid,'product_name'=>$pname));
        $CheckStock->setFetchMode(PDO::FETCH_OBJ);
        $countStock=$CheckStock->rowCount();
        if ($countStock == 0) {

            $productinsertion=$connexion->prepare('INSERT INTO stockin (
                pid,
                product_name,
                quantity,
                mesure,
                stokin,
                manufacturedate,
                expireddate,
                price,
                suppliername,
                barcode,
                addby,
                status
            ) 

            VALUES (
                :pid,
                :product_name,
                :quantity,
                :mesure,
                :stokin,
                :manufacturedate,
                :expireddate,
                :price,
                :suppliername,
                :barcode,
                :addby,
                :status)
            ');

        $productinsertion->execute(array(
            'pid'=>$pid,
            'product_name'=>$pname,
            'quantity'=>$quantity,
            'mesure'=>$mesure,
            'stokin'=>$stockin,
            'manufacturedate'=>$manufacturedate,
            'expireddate'=>$expireddate,
            'price'=>$price,
            'suppliername'=>$suppliername,
            'barcode'=>$Barcode,
            'addby'=>$_SESSION['id'],
            'status'=>$status
        ));


        $SELECTLastid = $connexion->query('SELECT * FROM stockin ORDER BY sid DESC');
        $SELECTLastid ->setFetchMode(PDO::FETCH_OBJ);
        if($GetLastId  = $SELECTLastid->fetch()){
            $lastid = $GetLastId->sid;

            // Update Stockin History to Justify How Material Stored In stock
             $InsertHistoIn = $connexion->prepare("INSERT INTO stockin_history(sid, quantityIn, mesure, doneon,doneby,status) VALUES (:sid,:quantity,:mesure,:doneon,:doneby,:status)");
             $InsertHistoIn->execute(array('sid'=>$lastid,'quantity'=>$quantity,'mesure'=>$mesure,'doneon'=>$stockin,'doneby'=>$_SESSION['id'],'status'=>$status));
        }

        }else{
            $GetPro = $CheckStock->fetch();

            //print_r($Produ);

            echo "<script>alert('".$GetPro->product_name."  Already Exist in Stock Do you Want To Increase Quantity?');</script>";

            if($price !=""){
                if ($price == $GetPro->price) {

                    $exisQ = $GetPro->quantity;
                    $allq =  $exisQ + $quantity;

                    // Update Stockin History to Justify How Material Stored In stock
                     $InsertHistoIn = $connexion->prepare("INSERT INTO stockin_history(sid, quantityIn, mesure, doneon,doneby,status) VALUES (:sid,:quantity,:mesure,:doneon,:doneby,:status)");
                     $InsertHistoIn->execute(array('sid'=>$GetPro->sid,'quantity'=>$quantity,'mesure'=>$mesure,'doneon'=>$stockin,'doneby'=>$_SESSION['id'],'status'=>$status));

                     //Update

                     $updateStokin = $connexion->prepare("UPDATE stockin SET quantity=:quantity WHERE sid=:sid");
                     $updateStokin->execute(array('quantity'=>$allq,'sid'=>$GetPro->sid));
                }else{

                $productinsertion=$connexion->prepare('INSERT INTO stockin (
                    pid,
                    product_name,
                    quantity,
                    mesure,
                    stokin,
                    manufacturedate,
                    expireddate,
                    price,
                    suppliername,
                    barcode,
                    addby,
                    status
                ) 

                VALUES (
                    :pid,
                    :product_name,
                    :quantity,
                    :mesure,
                    :stokin,
                    :manufacturedate,
                    :expireddate,
                    :price,
                    :suppliername,
                    :barcode,
                    :addby,
                    :status)
                ');

            $productinsertion->execute(array(
                'pid'=>$pid,
                'product_name'=>$pname,
                'quantity'=>$quantity,
                'mesure'=>$mesure,
                'stokin'=>$stockin,
                'manufacturedate'=>$manufacturedate,
                'expireddate'=>$expireddate,
                'price'=>$price,
                'suppliername'=>$suppliername,
                'barcode'=>$Barcode,
                'addby'=>$_SESSION['id'],
                'status'=>$status
            ));

            $SELECTLastid = $connexion->query('SELECT * FROM stockin ORDER BY sid DESC');
            $SELECTLastid ->setFetchMode(PDO::FETCH_OBJ);
            if($GetLastId  = $SELECTLastid->fetch()){
                $lastid = $GetLastId->sid;

                // Update Stockin History to Justify How Material Stored In stock
                 $InsertHistoIn = $connexion->prepare("INSERT INTO stockin_history(sid, quantityIn, mesure, doneon,doneby,status) VALUES (:sid,:quantity,:mesure,:doneon,:doneby,:status)");
                 $InsertHistoIn->execute(array('sid'=>$lastid,'quantity'=>$quantity,'mesure'=>$mesure,'doneon'=>$stockin,'doneby'=>$_SESSION['id'],'status'=>$status));
            }

                }
            }


            
        }


        
       // echo $count=$productinsertion->rowCount();

    }
}


if (isset($_POST['updatestockbtn'])) {
    $stoid = $_POST['stoid'];
    $mesure = $_POST['mesure'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $expireddate = $_POST['expireddate'];

    $productinsertion=$connexion->prepare('UPDATE stockin SET quantity=:quantity, mesure=:mesure, price=:price,expireddate=:expireddate WHERE sid=:stoid');
    $productinsertion->execute(array('stoid'=>$stoid,'quantity'=>$quantity,'mesure'=>$mesure,'price'=>$price,'expireddate'=>$expireddate));
    // echo $count=$productinsertion->rowCount();
   // echo'<script>alert("hello");</script>';

}

if(isset($_GET['deleteStock'])){
    $deletestock=$connexion->prepare('DELETE FROM stockin WHERE sid=:stoid');
    $deletestock->execute(array('stoid'=>$_GET['sid']));
    header('location:newstock.php?showstock=ok');
    // echo'<script>alert("hello");</script>';
}


     if (isset($_POST['stockOut'])) {

        $sizeoftable = $_POST['sizeoftable'];
        $errors = array();
        $savedPro = array();
        $OrderedquantityQty= array();
        $takenBy= array();

        for ($i=0; $i < $sizeoftable; $i++) { 

             $pid = 'pid'.$i;
             $sid = 'sid'.$i;
             $Orderedquantity = 'quantity'.$i;
             $mesure = 'mesure'.$i;
             $takenby = 'takenby'.$i;
             $date = 'date'.$i;

             $pid = $_POST[$pid];
             $sid = $_POST[$sid];
             $Orderedquantity = $_POST[$Orderedquantity];
             $mesure = $_POST[$mesure];
             $takenby = $_POST[$takenby];
             $date = date("Y-m-d", strtotime($_POST[$date]));
             //Get Stock Id

             $GetStock = $connexion->prepare("SELECT * FROM stockin WHERE sid=:sid");
             $GetStock ->execute(array('sid'=>$sid));
             $GetStock->setFetchMode(PDO::FETCH_OBJ);
             $GetLigne = $GetStock->fetch();

             $sid = $GetLigne->sid;
             $ExistQuantity =  $GetLigne->quantity;

             if($ExistQuantity >= $Orderedquantity){

                 $statusPre = $GetLigne->status;
                 // Insert History Into Stock History

                 $InsertHisto = $connexion->prepare("INSERT INTO stockout_history(`sid`, quantityOut, mesure, doneon, takenby,doneby,`status`,existing_qty) VALUES (:sid,:quantity,:mesure,:doneon,:takenby,:doneby,:status,:existing_qty)");
                 $InsertHisto->execute(array('sid'=>$sid,'quantity'=>$Orderedquantity,'mesure'=>$mesure,'doneon'=>$date,'takenby'=>$takenby,'doneby'=>$_SESSION['id'],'status'=>$statusPre,'existing_qty'=>$ExistQuantity));

                 // Formula...

                 $NewQuantity =  $ExistQuantity - $Orderedquantity;
                 //echo 'NewQ='.$NewQuantity.'<br>';

                 //Take out Given Quantity into stockIn

                $UpdateStok = $connexion->prepare("UPDATE stockin SET quantity=:NewQ WHERE pid=:pid AND sid=:sid");
                $UpdateStok->execute(array('NewQ'=>$NewQuantity,'pid'=>$pid,'sid'=>$sid));

                $Qty[] =   $Orderedquantity;             
                $QtyExisting[] =   $ExistQuantity;             
                $savedPro[] =   $GetLigne->product_name;             
                $cusumer[] =   $takenby;             
            
                // echo $count=$productinsertion->rowCount();
            }else{
                $errors [] = $GetLigne->product_name;
            }

        }
    }

      $AllRequisition = $connexion->prepare("SELECT * FROM requisition WHERE created_at=:created_at GROUP BY asked_by");
      $AllRequisition->execute(array('created_at'=>$annee));
      $AllRequisition->setFetchMode(PDO::FETCH_OBJ);
      $AllRequisitions = $AllRequisition->rowcount();
?>

<!doctype html>
<html lang="en">
<noscript>
    Cette page requiert du Javascript.
    Veuillez l'activer pour votre navigateur
</noscript>

<head>
    <meta charset="utf-8"/>
    <title>Stock</title>

    <link rel="icon" href="images/favicon.ico">
    <link rel="shortcut icon" href="images/favicon.ico" />

    <!-------------------barre de menu------------------->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="source/cssmenu/styles.css">
    <link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
    
    <script src="script.js"></script>
    <?php if(!isset($_GET['editStock'])){ ?>
        <link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />
    <?php }?>
            
            <!------------------------------------>
<!--    <link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />
 -->
    <link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
            
    <link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->
    
    <link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
    
    <link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
    <link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
    
        <!-------------------calendrier------------------->
    
    <link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
    <script type="text/javascript" src="calender/calendrier.js"></script>   
    <link href="css/form-signin.css" rel="stylesheet" type="text/css">

       <!-- BOOTSTRAP CORE STYLE  -->
       <link href="assets/css/bootstrap.css" rel="stylesheet" />
      <!-- FONT AWESOME STYLE  -->
      <link href="assets/css/font-awesome.css" rel="stylesheet" />
      <!-- DATATABLE STYLE  -->
      <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
      <!-- CUSTOM STYLE  -->
      <link href="assets/css/style.css" rel="stylesheet" />
    
    <link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
    <link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
    <script src="myQuery.js"></script>

    </head>

    <style type="text/css">
    .flashing {
      -webkit-animation: glowing 1500ms infinite;
      -moz-animation: glowing 1500ms infinite;
      -o-animation: glowing 1500ms infinite;
      animation: glowing 1500ms infinite;
    }
    @-webkit-keyframes glowing {
      0% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
      50% { background-color: #FF0000; -webkit-box-shadow: 0 0 40px #FF0000; }
      100% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
    }

    @-moz-keyframes glowing {
      0% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
      50% { background-color: #FF0000; -moz-box-shadow: 0 0 40px #FF0000; }
      100% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
    }

    @-o-keyframes glowing {
      0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
      50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
      100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
    }

    @keyframes glowing {
      0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
      50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
      100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
    }
    </style>

    <script type="text/javascript">
        
        function ShowList(list)
        {
            if( list =='Users')
            {
                document.getElementById('divMenuUser').style.display='inline';
                document.getElementById('divMenuMsg').style.display='none';
                document.getElementById('divListe').style.display='none';
                document.getElementById('listOn').style.display='inline';
                document.getElementById('listOff').style.display='none';
            }

            if( list =='Msg')
            {
                document.getElementById('divMenuMsg').style.display='inline';
                document.getElementById('divMenuUser').style.display='none';
                document.getElementById('divListe').style.display='none';
                document.getElementById('listOn').style.display='inline';
                document.getElementById('listOff').style.display='none';
            }

            if( list =='Liste')
            {
                document.getElementById('divListe').style.display='inline';
                document.getElementById('listOff').style.display='inline';
                document.getElementById('listOn').style.display='none';
            }

            if( list =='ListeNon')
            {
                document.getElementById('divListe').style.display='none';
                document.getElementById('listOn').style.display='inline';
                document.getElementById('listOff').style.display='none';
            }

        }

    </script>

<body>
<?php

$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true)
{
    if($status==1)
    {
        ?>

        <div class="navbar">

            <div class="navbar-inner">

                <div class="container">

                    <img src="images/logo_large.png" class="brand" />

                    <div class="nav-collapse">

                        <ul class="nav pull-right">
                            <li class="">
                                <form method="post" action="newstock.php?<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">

                                    <span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>

                                    <a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>

                                    <?php
                                    if($langue == 'francais')
                                    {
                                        ?>
                                        <a href="newstock.php?english=english<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?>" class="btn"><?php echo getString(30);?></a>
                                        <?php
                                    }else{
                                        ?>
                                        <a href="newstock.php?francais=francais<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?>" class="btn"><?php echo getString(29);?></a>
                                        <?php
                                    }
                                    ?>
                                    <br/>

                                    <input type="text" name="Pass" id="Pass" placeholder="<?php echo getString(26);?>" style="margin-top:10px;margin-bottom:0;"/>

                                    <input type="submit" name="confirmPass" id="confirmPass" class="btn"  value="<?php echo getString(27);?>"/>


                                </form>
                            </li>
                        </ul>
                    </div><!--/.nav-collapse -->

                </div> <!-- /container -->

            </div> <!-- /navbar-inner -->

        </div><!-- <br><br><br><br><br> -->

            <?php
            if(isset($_SESSION['codeS']))
            {
            ?>
            <div style="text-align:center;margin-top:20px;">
            
            <a href="report.php?coordi=ok&StockChoose=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
                <?php echo 'Reports';?>
            </a>

            <a href="closing_stock.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;font-family:'ubuntu';">
                <?php echo 'Stock Closing';?>
            </a>

            <!-- <a href="requisition2.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-requisition" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;font-family:'ubuntu';">
                <?php echo 'Requisition request';?>
                <span id="data-badge" data-badge="<?php echo $AllRequisitions; ?>"></span>
            </a> -->
        </div>
        
    <?php
    }
    ?>


         <div class="account-container" style="width:90%; text-align:center;font-family:'ubuntu';">

            <div id='cssmenu' style="text-align:center">

                <ul style="margin-top:20px;background:none;border:none;">

                         <li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="Stock"><i class="fa fa-database fa-lg fa-fw"></i> Stock 
                         <?php if($countD != 0){echo' <span class="flashing" style="color:white;border: 1px solid #ddd;border-radius: 10px;padding: 6px 6px;margin-left: 5px;"> <span style="padding: 2px 7px;color:white;font-weight:bold;background: red;border-radius: 50px;">'.$countD.'</span> Expired</span>';}else{echo' <span style="border: 1px solid #ddd;border-radius: 10px;padding: 6px 6px;margin-left: 5px;"> <span style="padding: 2px 7px;color:white;font-weight:bold;background: red;border-radius: 50px;">'.$countD.'</span> Expired</span>';}?>

                         <span style="border: 1px solid #ddd;border-radius: 10px;padding: 6px 6px;margin-left: 5px;"> <span style="padding: 2px 7px;color:white;font-weight:bold;background: red;border-radius: 50px;"><?php echo $AllRequisitions; ?></span> <small>Requisition</small></span>
                         </a></li>

                         <!-- <li style="width:33.3%;"><a href="requisition.php" style="margin-right:5px;" data-title="Make Requisition"><i class="fa fa-plus fa-lg fa-fw"></i> Make Requisition</a></li> -->

                         <li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
                </ul>

                <ul style="margin-top:20px; background:none;border:none;">
 
                    <div id="divMenuUser" style="display:none;">

                         <a href="stockrecording.php?Medicament=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo "Medicament & Consumables";?></a>

                        <a href="stockrecording.php?Laboratory=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo 'Laboratory';?></a>  
                        <a href="stockrecording.php?materials=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo 'Materials';?></a>

                      <!--   <a href="produit.php?addNew=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo "Show/Add product";?></a>

                        <a href="newstock.php?addNew=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo getString(280);?></a> -->

                    </div>
                </ul>

                    <div style="display:none; margin-bottom:20px;" id="divMenuMsg">
 
                        <a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>

                        <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>

                        <a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

                    </div>

                    <br>
                    <?php if(isset($_GET['Medicament'])){ ?>
                        <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;">Medicament & Consumables</h3>
                        <br>
                    <div style="margin-bottom:20px;" id="divMenustock">

                        <a href="produit.php?NewMedicament=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo "Add New Medicament & Consumables";?></a>

                        <a href="newstock.php?addNew=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo 'Stock In';?></a>  

                        <a href="newstock.php?showstock=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo 'Show Stock';?></a>  

                        <a href="newstock.php?Medicanentstockout=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo 'Stock Out';?></a>  

                    </div>
                    <?php }?> 

                    <?php if(isset($_GET['Laboratory'])){ ?>
                        <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;">Laboratory</h3>
                        <br>
                    <div style="margin-bottom:20px;" id="divMenustock">

                        <a href="produit.php?NewConsumables=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo "Add New Laboratory product";?></a>

                        <a href="newstock.php?addNew=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo 'Stock In';?></a>  

                        <a href="newstock.php?Consumablesstockout=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large "><?php echo 'Stock Out';?></a>  

                    </div>
                    <?php }?>


                    <?php if(isset($_GET['materials'])){ ?>
                        <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;">Materials</h3>
                        <br>
                    <div style="margin-bottom:20px;" id="divMenustock">

                        <a href="stockrecording.php?NewMaterial=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><?php echo "Add New Material";?></a>

                        <a href="newstock.php?addNewMaterial=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><?php echo 'Stock In';?></a>  

                        <a href="newstock.php?Materialstockout=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large "><?php echo 'Stock Out';?></a>  

                        <a href="newstock.php?showstock=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-folder-open"></i> <?php echo 'Show Stock';?></a>

                    </div>
                    <?php }?>

                <br/>
            </div>
            <?php if(isset($_GET['addNew']) || isset($_GET['Newstock'])){?>
                <a href="stockrecording.php?NewMedicament=ok" class="btn-large-inversed" style="font-family:'ubuntu';">New Drugs & Consumables</a>
                <br><br>
                <form method="POST" action="newstock.php?addNew=ok&Newstock=ok">
                        <tr>
                            <td style="padding:5px;text-align:center;">
                        <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;font-family:'ubuntu';">Select Medicaments & Consumables(Stock in)</h3>
                                    <hr>
                                <select style="margin:auto;" multiple="multiple" name="checkstock[]" class="chosen-select" id="checkstock">                     
                                    <!--
                                    <option value='0'><?php echo getString(121) ?></option>
                                    -->                         
                                <?php
                                $id_categopresta = '22';
                                $resultatsPrestaSoins=$connexion->prepare('SELECT * FROM products WHERE id_categopresta =:id ORDER BY pro_id ASC');
                                $resultatsPrestaSoins->execute(array('id'=>$id_categopresta));
                                
                                $resultatsPrestaSoins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
                                
                                if($ligneCatPrestaSoins=$resultatsPrestaSoins->fetch())
                                {
                                    echo '<optgroup label="Medicament">';

                                    echo '<option value='.$ligneCatPrestaSoins->pro_id.' onclick="ShowOthersSoins(\'soins\')">'.$ligneCatPrestaSoins->productname.'</option>';                         
                                    
                                    while($lignePrestaSoins=$resultatsPrestaSoins->fetch())//on recupere la liste des éléments
                                    {
                                ?>
                                    <option value='<?php echo $lignePrestaSoins->pro_id;?>'><?php echo $lignePrestaSoins->productname;?></option>
                                <?php
                                    }$resultatsPrestaSoins->closeCursor();
                                
                                    echo '</optgroup>';
                                }
                                ?>
                                    <!--
                                    <option value="autresoins" id="autresoins"><?php echo getString(122) ?></option>
                                    -->
                                    
                                </select>
                                
                            </td>
                            <br>
                            <td>
                                <button type="submit" class="btn-large" name="listoutProduct" style="width:20%;margin-top: 30px;">
                                    <i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i><?php echo getString(125);?>
                                
                                </button>
                            </td>
                
                        </tr>
                    </form>
          <?php
            }
            if (isset($_GET['Newstock'])) {
                $newStock = array();

                foreach ($_POST['checkstock'] as $stockvalue) {
                    $newStock[] = $stockvalue;
                //print_r($newStock);
                }
         ?>
            <form method="POST" action="newstock.php?showstock=ok">
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Product Name</th>
                            <th style="width: 5%;">Dosage</th>
                            <th style="width: 5%;">P/U</th>
                            <th style="width: 5%;">Quantity</th>
                            <th style="width: 5%;">Manufacture Date</th>
                            <th style="width: 5%;">Expired Date</th>
                            <th style="width: 5%;">Barcode</th>
                            <th style="width: 5%;">Supplier</th>
                            <th style="width: 5%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for ($i=0; $i < sizeof($newStock) ; $i++) { 
                                $sizeoftable = sizeof($newStock);
                                $sizeoftable;

                                $selectinfoPro = $connexion->query('SELECT * FROM products WHERE pro_id="'.$newStock[$i].'" ORDER BY pro_id ASC');
                                $selectinfoPro->setFetchMode(PDO::FETCH_OBJ);

                                $lingeProduct = $selectinfoPro->fetch();

                        ?>
                                <tr>
                                    <td style="border:1px solid #ddd;width: 150px;" >
                                        <?php echo $lingeProduct->productname; ?>
                                        <input type="hidden" name="pname<?php echo $i; ?>" value="<?php echo $lingeProduct->productname; ?>" >
                                        <input type="hidden" name="pid<?php echo $i; ?>" value="<?php echo $lingeProduct->pro_id; ?>" >
                                        <input type="hidden" name="sizeoftable" value="<?php echo $sizeoftable; ?>">
                                    </td>
                                   <!--  <td>
                                        <p style="background: #ddd;padding: 5px 5px;"><?php echo 'mg/l'; ?></p>
                                    </td> -->
                                    <td>
                                        <input  style="width: 60px;" type="text" name="mesure<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input  style="width: 60px;" type="text" name="price<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 60px;" type="text" name="quantity<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 150px;height: 30px;text-align: center;" type="date" name="manufacturedate<?php echo $i; ?>">
                                        <!-- <input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/> -->

                                    </td>
                                    <td>
                                        <input style="width: 150px;height: 30px;text-align: center;" type="date" name="expireddate<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 100px;" type="text" name="Barcode<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 150px;" type="text" name="suppliername<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 150px;height: 30px;text-align: center;" type="date" name="stockin<?php echo $i; ?>" value="<?php echo $annee;?>">
                                    </td>

                                </tr>
                        <?php
                            }
                        ?>
                        <td>
                            <button class="btn-large" name="savenewstockbtn">Save</button>
                        </td>
                    </tbody>
                </table>
            </form>
            <table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
            <tr>
                <td style="padding:5px;" id="ds_calclass"></td>
            </tr>
        </table>
        <?php
            }
        ?>

        <?php if(isset($_GET['addNewConsom']) || isset($_GET['Newstockconsom'])){?>
                <a href="stockrecording.php?NewConsumables=ok" class="btn-large-inversed" style="font-family:'ubuntu';">New Labotory</a>
                <br><br>
            <form method="POST" action="newstock.php?addNewConsom=ok&Newstockconsom=ok">
                        <tr>
                            <td style="padding:5px;text-align:center;">
                        <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;font-family:'ubuntu';">Select Labotory(Stock in)</h3>
                                    <hr>
                            <select style="margin:auto;" multiple="multiple" name="checkstockconsom[]" class="chosen-select" id="checkstockconsom">                     
                                    <!--
                                    <option value='0'><?php echo getString(121) ?></option>
                                    -->                         
                                <?php
                                $id_categopresta = '21';
                                $resultatsPrestaSoins=$connexion->prepare('SELECT * FROM products WHERE id_categopresta =:id ORDER BY pro_id ASC');
                                $resultatsPrestaSoins->execute(array('id'=>$id_categopresta));
                                
                                $resultatsPrestaSoins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
                                
                                if($ligneCatPrestaSoins=$resultatsPrestaSoins->fetch())
                                {
                                    echo '<optgroup label="Medicament">';

                                    echo '<option value='.$ligneCatPrestaSoins->pro_id.' onclick="ShowOthersSoins(\'soins\')">'.$ligneCatPrestaSoins->productname.' </option>';                           
                                    
                                    while($lignePrestaSoins=$resultatsPrestaSoins->fetch())//on recupere la liste des éléments
                                    {
                                ?>
                                    <option value='<?php echo $lignePrestaSoins->pro_id;?>'><?php echo $lignePrestaSoins->productname;?></option>
                                <?php
                                    }$resultatsPrestaSoins->closeCursor();
                                
                                    echo '</optgroup>';
                                }
                                ?>
                                    <!--
                                    <option value="autresoins" id="autresoins"><?php echo getString(122) ?></option>
                                    -->
                                    
                                </select>
                                
                            </td>
                            <br>
                            <td>
                                <button type="submit" class="btn-large" name="listoutProduct" style="width:20%;margin-top: 30px;">
                                    <i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i><?php echo getString(125);?>
                                
                                </button>
                            </td>
                
                        </tr>
                    </form>
          <?php
            }
            if (isset($_GET['addNewConsom'])) {
                $newStock = array();
                if(isset($_POST['checkstockconsom'])){
                    foreach ($_POST['checkstockconsom'] as $stockvalue) {
                        $newStock[] = $stockvalue;
                        //print_r($newStock);
                    }  
         ?>
            <form method="POST" action="newstock.php?showstock=ok">
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Product Name</th>
                            <th style="width: 5%;">Dosage</th>
                            <th style="width: 5%;">P/U</th>
                            <th style="width: 5%;">Quantity</th>
                            <th style="width: 5%;">Manufacture Date</th>
                            <th style="width: 5%;">Expired Date</th>
                            <th style="width: 5%;">Barcode</th>
                            <th style="width: 5%;">Supplier</th>
                            <th style="width: 5%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for ($i=0; $i < sizeof($newStock) ; $i++) { 
                                $sizeoftable = sizeof($newStock);
                                $sizeoftable;

                                $selectinfoPro = $connexion->query('SELECT * FROM products WHERE pro_id="'.$newStock[$i].'" ORDER BY pro_id ASC');
                                $selectinfoPro->setFetchMode(PDO::FETCH_OBJ);

                                $lingeProduct = $selectinfoPro->fetch();

                        ?>
                                <tr>
                                    <td style="border:1px solid #ddd;">
                                        <?php echo $lingeProduct->productname; ?>
                                        <input type="hidden" name="pname<?php echo $i; ?>" value="<?php echo $lingeProduct->productname; ?>" >
                                        <input type="hidden" name="pid<?php echo $i; ?>" value="<?php echo $lingeProduct->pro_id; ?>" >
                                        <input type="hidden" name="sizeoftable" value="<?php echo $sizeoftable; ?>">
                                    </td>
                                   <!--  <td>
                                        <p style="background: #ddd;padding: 5px 5px;"><?php echo 'mg/l'; ?></p>
                                    </td> -->
                                    <td>
                                        <input  style="width: 60px;" type="text" name="mesure<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input  style="width: 60px;" type="text" name="price<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 60px;" type="text" name="quantity<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 150px;" type="text" onclick="ds_sh(this);" name="manufacturedate<?php echo $i; ?>" >
                                        <!-- <input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/> -->

                                    </td>
                                    <td>
                                        <input style="width: 150px;" type="text"  onclick="ds_sh(this);" name="expireddate<?php echo $i; ?>" >
                                    </td>
                                    <td>
                                        <input style="width: 100px;" type="text" name="Barcode<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 150px;" type="text" name="suppliername<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 150px;height: 30px;text-align: center;" type="date" name="stockin<?php echo $i; ?>" value="<?php echo $annee;?>">
                                    </td>

                                </tr>
                        <?php
                            }
                        ?>
                                    <td>
                                        <button class="btn-large" name="savenewstockbtnConsom">Save</button>
                                    </td>
                    </tbody>
                </table>
            </form>
            <table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
            <tr>
                <td style="padding:5px;" id="ds_calclass"></td>
            </tr>
        </table>
        <?php
            }else{

            }
        }
        ?>                  

             <!-- Material Part  -->
             <?php if(isset($_GET['addNewMaterial']) || isset($_GET['Newstockmaterial'])){?>
                <a href="stockrecording.php?NewMaterial=ok" class="btn-large-inversed" style="font-family:'ubuntu';">New Material</a>
                <br><br>
            <form method="POST" action="newstock.php?addNewMaterial=ok&Newstockmaterial=ok">
                        <tr>
                            <td style="padding:5px;text-align:center;">
                        <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;font-family:'ubuntu';">Select Material(Stock in)</h3>
                                    <hr>
                            <select style="margin:auto;" multiple="multiple" name="checkstockmaterial[]" class="chosen-select" id="checkstockmaterial">                     
                                    <!--
                                    <option value='0'><?php echo getString(121) ?></option>
                                    -->                         
                                <?php
                                $id_categopresta = '23';
                                $resultatsPrestaSoins=$connexion->prepare('SELECT * FROM products WHERE id_categopresta =:id ORDER BY pro_id ASC');
                                $resultatsPrestaSoins->execute(array('id'=>$id_categopresta));
                                
                                $resultatsPrestaSoins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
                                
                                if($ligneCatPrestaSoins=$resultatsPrestaSoins->fetch())
                                {
                                    echo '<optgroup label="Materials">';

                                    echo '<option value='.$ligneCatPrestaSoins->pro_id.' onclick="ShowOthersSoins(\'soins\')">'.$ligneCatPrestaSoins->productname.' </option>';                           
                                    
                                    while($lignePrestaSoins=$resultatsPrestaSoins->fetch())//on recupere la liste des éléments
                                    {
                                ?>
                                    <option value='<?php echo $lignePrestaSoins->pro_id;?>'><?php echo $lignePrestaSoins->productname;?></option>
                                <?php
                                    }$resultatsPrestaSoins->closeCursor();
                                
                                    echo '</optgroup>';
                                }
                                ?>
                                    <!--
                                    <option value="autresoins" id="autresoins"><?php echo getString(122) ?></option>
                                    -->
                                    
                                </select>
                                
                            </td>
                            <br>
                            <td>
                                <button type="submit" class="btn-large" name="listoutProduct" style="width:20%;margin-top: 30px;">
                                    <i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i><?php echo getString(125);?>
                                
                                </button>
                            </td>
                
                        </tr>
                    </form>
          <?php
            }
            if (isset($_GET['addNewMaterial'])) {
                $newStock = array();
                if(isset($_POST['checkstockmaterial'])){
                    foreach ($_POST['checkstockmaterial'] as $stockvalue) {
                        $newStock[] = $stockvalue;
                        //print_r($newStock);
                    }  
         ?>
            <form method="POST" action="newstock.php?showstock=ok">
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Product Name</th>
                            <th style="width: 5%;">Dosage</th>
                            <th style="width: 5%;">P/U</th>
                            <th style="width: 5%;">Quantity</th>
                            <th style="width: 5%;">Manufacture Date</th>
                            <th style="width: 5%;">Expired Date</th>
                            <th style="width: 5%;">Barcode</th>
                            <th style="width: 5%;">Supplier</th>
                            <th style="width: 5%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for ($i=0; $i < sizeof($newStock) ; $i++) { 
                                $sizeoftable = sizeof($newStock);
                                $sizeoftable;

                                $selectinfoPro = $connexion->query('SELECT * FROM products WHERE pro_id="'.$newStock[$i].'" ORDER BY pro_id ASC');
                                $selectinfoPro->setFetchMode(PDO::FETCH_OBJ);

                                $lingeProduct = $selectinfoPro->fetch();

                        ?>
                                <tr>
                                    <td style="border:1px solid #ddd;">
                                        <?php echo $lingeProduct->productname; ?>
                                        <input type="hidden" name="pname<?php echo $i; ?>" value="<?php echo $lingeProduct->productname; ?>" >
                                        <input type="hidden" name="pid<?php echo $i; ?>" value="<?php echo $lingeProduct->pro_id; ?>" >
                                        <input type="hidden" name="sizeoftable" value="<?php echo $sizeoftable; ?>">
                                    </td>
                                   <!--  <td>
                                        <p style="background: #ddd;padding: 5px 5px;"><?php echo 'mg/l'; ?></p>
                                    </td> -->
                                    <td>
                                        <input  style="width: 60px;" type="text" name="mesure<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input  style="width: 60px;" type="text" name="price<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 60px;" type="text" name="quantity<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 150px;" type="date" name="manufacturedate<?php echo $i; ?>" >
                                        <!-- <input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value=""/> -->

                                    </td>
                                    <td>
                                        <input style="width: 150px;" type="date" name="expireddate<?php echo $i; ?>" >
                                    </td>
                                    <td>
                                        <input style="width: 100px;" type="text" name="Barcode<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 150px;" type="text" name="suppliername<?php echo $i; ?>">
                                    </td>
                                    <td>
                                        <input style="width: 150px;height: 30px;text-align: center;" type="date" name="stockin<?php echo $i; ?>" value="<?php echo $annee;?>">
                                    </td>

                                </tr>
                        <?php
                            }
                        ?>
                                    <td>
                                        <button class="btn-large" name="savenewstockbtnMaterial">Save</button>
                                    </td>
                    </tbody>
                </table>
            </form>
            <table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
            <tr>
                <td style="padding:5px;" id="ds_calclass"></td>
            </tr>
        </table>
        <?php
            }else{

            }
        }
        ?> 
            <!--  end of Material  section -->
        <?php if(isset($_GET['Medicanentstockout'])){?>
            <form method="POST" action="newstock.php?Medicanentstockout=ok&Newstockout=ok">
                        <tr>
                            <td style="padding:5px;text-align:center;">
                                 <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;font-family:'ubuntu';">Select Medicaments & Consumables(Stock Out)</h3>
                               
                                    <hr>
                                <select style="margin:auto;margin-left: 50px;" multiple="multiple" name="checkstock[]" class="chosen-select" id="checkstock">                     
                                    <!--
                                    <option value='0'><?php echo getString(121) ?></option>
                                    -->                         
                                <?php
                                //$id_categopresta = '22';
                                $resultatsPrestaSoins=$connexion->query("SELECT * FROM stockin s,products p WHERE s.expireddate > $annee AND p.pro_id=s.pid AND p.id_categopresta=22 AND s.quantity>0");
                                //$resultatsPrestaSoins->execute(array('id'=>$id_categopresta));
                                
                                $resultatsPrestaSoins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
                                
                                if($ligneCatPrestaSoins=$resultatsPrestaSoins->fetch())
                                {
                                    echo '<optgroup label="Medicament">';

                                    echo '<option value='.$ligneCatPrestaSoins->sid.' onclick="ShowOthersSoins(\'soins\')">'.$ligneCatPrestaSoins->product_name.' ('.$ligneCatPrestaSoins->price.' Rwf)'.' -> ('.$ligneCatPrestaSoins->quantity.' Qty) </option>';                         
                                    
                                    while($lignePrestaSoins=$resultatsPrestaSoins->fetch())//on recupere la liste des éléments
                                    {
                                ?>
                                    <option value='<?php echo $lignePrestaSoins->sid;?>' style="<?php if(($lignePrestaSoins->sid==377 OR $lignePrestaSoins->sid==378 OR $lignePrestaSoins->sid==380) ){echo "display: none;";} ?>"><?php echo $lignePrestaSoins->product_name;?> <b>(<?php echo $lignePrestaSoins->price;?> Rwf)</b> -> <b>(<?php echo $lignePrestaSoins->quantity;?> Qty)</b></option>
                                <?php
                                    }$resultatsPrestaSoins->closeCursor();
                                
                                    echo '</optgroup>';
                                }else{
                                ?>
                                    
                                    <option value="autresoins" id="autresoins"><?php echo getString(232) ?></option>
                                    
                                <?php }?>
                                    
                                </select>
                                
                            </td>

                            <br>
                            <td>
                                <button type="submit" class="btn-large" name="listoutProduct" style="width:20%;margin-top: 30px;">
                                    <i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i><?php echo getString(125);?>
                                
                                </button>
                            </td>
                            
                            <!-- <br>
                                <a href="newstock.php?Medicanentstockout=ok&expiredDr=ok" name="expiredDr" style="position: relative;top: 5px;cursor: pointer;">Show Expired Drugs</a>   -->            
                              <hr>
                        </tr>
                    </form>
                    <?php 
                        if ($countD !=0) {
                            $expire =array();
                            while($GetExpire = $EXp->fetch()){

                                $expire= $GetExpire->product_name;
                                echo "<input type='hidden' name='expire[]' class='expire' value=".$expire.">";
                                
                            } ?>
                            <?php echo '<i class="fa fa-warning"></i> ( '.$countD.' Drugs are Expired )';?>
                              <span onclick="ShowDrugs('showExpiredDr')" class="btn" title="Watch Expired Drugs"><?php echo 'Click to watch';?></span>

                            <?php                      
                        }
                    ?>
          <?php
            }
            ?>


            <?php 

           //echo print_r($savedPro);
            if(!empty($savedPro)){ ?>
                <h3><i class="fa fa-check fa-lg fa-fw"></i>  Drugs And consumables Taken out into stock</h3>
                <table class="tablesorter" cellspacing="0">
                <thead>
                    <tr> 
                        <th style="text-align: center;">Product name</th>
                        <th style="text-align: center;">Stock out</th>
                        <th style="text-align: center;">Remain Qty</th>
                        <th style="text-align: center;">Taken By</th>
                    </tr>
                </thead>
                <tbody>
                    
                         <?php
                            for ($i=0; $i < sizeof($savedPro); $i++) { 
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $savedPro[$i];?></td>
                            <td style="text-align: center;"><?php echo $Qty[$i];?></td>
                            <td style="text-align: center;"><?php echo $QtyExisting[$i];?></td>
                            <td style="text-align: center;"><?php echo $cusumer[$i];?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    
                </tbody>
                </table>
                <hr>
            <?php } ?>            



            <?php if(!empty($errors)): ?>
                <h3><i class="fa fa-ban fa-lg fa-fw"></i>  Drugs That has Higher Quality Than those are in stock </h3>
                <table class="tablesorter" cellspacing="0">
                <thead>
                    <tr class="flashing"> 
                        <th style="text-align: center;">Product name</th>
                    </tr>
                </thead>
                <tbody>
                    
                         <?php
                            for ($i=0; $i < sizeof($errors); $i++) { 
                        ?>
                        <tr>
                            <td style="text-align: center;"><?= $errors[$i];?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    
                </tbody>
                </table>
            <?php endif ?>


            <?php

            if (isset($_GET['Newstockout'])) {
                $newStock = array();
                foreach ($_POST['checkstock'] as $stockvalue) {
                    $newStock[] = $stockvalue;
                    //print_r($newStock);
                }
         ?>
            <form method="POST" action="newstock.php">
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Product Name</th>
                            <th style="width: 5%;">Dosage</th>
                            <th style="width: 5%;">P/U</th>
                            <th style="width: 5%;">Quantity (Out)</th>
                            <th style="width: 5%;">Taken By</th>
                            <th style="width: 5%;">Manufacture Date</th>
                            <th style="width: 5%;">Expired Date</th>
                            <th style="width: 5%;">Barcode</th>
                            <th style="width: 5%;">Supplier</th>
                            <th style="width: 5%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for ($i=0; $i < sizeof($newStock); $i++) { 

                                $sizeoftable = sizeof($newStock);
                                $sizeoftable;

                                $selectinfoPro = $connexion->query('SELECT * FROM stockin WHERE sid="'.$newStock[$i].'" ORDER BY pid ASC');
                                $selectinfoPro->setFetchMode(PDO::FETCH_OBJ);

                                $lingeProduct = $selectinfoPro->fetch();

                        ?>
                                <tr>
                                    <td style="border:1px solid #ddd;color: #666666e3;">
                                        <?php echo $lingeProduct->product_name; ?>
                                        <input type="hidden" name="pname<?php echo $i; ?>" value="<?php echo $lingeProduct->product_name; ?>" >
                                        <input type="hidden" name="pid<?php echo $i; ?>" value="<?php echo $lingeProduct->pid; ?>" >
                                        <input type="hidden" name="sid<?php echo $i; ?>" value="<?php echo $lingeProduct->sid; ?>" >
                                        <input type="hidden" name="mesure<?php echo $i; ?>" value="<?php echo $lingeProduct->mesure; ?>">
                                        <input type="hidden" name="sizeoftable" value="<?php echo $sizeoftable; ?>">
                                    </td>
                                    <td>
                                        <p style="border: 1px solid #a0000038;padding: 5px 5px;border-radius: 3px;"><?php echo 'mg/l'; ?></p>
                                    </td>
                                    <td>
                                        <!-- <input  style="width: 60px;" type="text" name="price<?php echo $i; ?>" value="<?php echo $lingeProduct->price; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo $lingeProduct->price;?> Rwf</p>
                                    </td>
                                    <td>
                                    <span style="color: #a0000094;position: relative;bottom: 10px;padding: 1px 7px;background: #ddd;border-radius: 5px;font-weight: bold;"><?php echo $lingeProduct->quantity; ?> - </span> <input style="width: 100px;position: relative;bottom: 10px;" type="text" name="quantity<?php echo $i; ?>" required> 
                                    </td> 

                                    <td>
                                        <input style="width: 100px;" type="text" name="takenby<?php echo $i; ?>" required> 
                                    </td>
                                    <td>
<!--                                 <input style="width: 150px;" type="text" onclick="ds_sh(this);" name="manufacturedate<?php echo $i; ?>">-->
                                        <p style="color: #666666e3;"><?php echo $lingeProduct->manufacturedate; ?></p>

                                    </td>
                                    <td>
                                        <!-- <input style="width: 150px;" type="text"  onclick="ds_sh(this);" name="expireddate<?php echo $i; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo $lingeProduct->expireddate; ?></p>

                                    </td>
                                    <td>
                                        <!-- <p style="width: 100px;" type="text" name="Barcode<?php echo $i; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo strtoupper($lingeProduct->barcode) ; ?></p>

                                    </td>
                                    <td>
                                        <!-- <input style="width: 150px;" type="text" name="suppliername<?php echo $i; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo  ucfirst(strtolower($lingeProduct->suppliername)); ?></p>
                                    </td>

                                    <td>
                                        <input style="width: auto;height: 40px;" type="date" name="date<?php echo $i; ?>" value="<?= $annee; ?>" required="required"> 
                                    </td>

                                </tr>
                        <?php
                            }
                        ?>
                               
                    </tbody>
                </table>
                <br>
                 <button class="btn-large" name="stockOut">Stock Out</button>
            </form>
            <table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
            <tr>
                <td style="padding:5px;" id="ds_calclass"></td>
            </tr>
        </table>
        <?php
            }
        ?>


        <?php if(isset($_GET['Consomstockout'])){?>
            <form method="POST" action="newstock.php?Consomstockout=ok&Newstockoutconsom=ok">
                        <tr>
                            <td style="padding:5px;text-align:center;">
                                 <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;font-family:'ubuntu';">Select Labo(Stock Out)</h3>
                               
                                    <hr>
                                <select style="margin:auto;margin-left: 50px;" multiple="multiple" name="checkstockconsom[]" class="chosen-select" id="checkstockconsom">                     
                                    <!--
                                    <option value='0'><?php echo getString(121) ?></option>
                                    -->                         
                                <?php
                                //$id_categopresta = '22';
                                $resultatsPrestaSoins=$connexion->query("SELECT * FROM stockin s,products p WHERE s.expireddate > $annee AND p.pro_id=s.pid AND p.id_categopresta=21 AND s.quantity>0");
                                //$resultatsPrestaSoins->execute(array('id'=>$id_categopresta));
                                
                                $resultatsPrestaSoins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
                                
                                if($ligneCatPrestaSoins=$resultatsPrestaSoins->fetch())
                                {
                                    echo '<optgroup label="Consumables">';

                                    echo '<option value='.$ligneCatPrestaSoins->sid.' onclick="ShowOthersSoins(\'soins\')">'.$ligneCatPrestaSoins->product_name.' ('.$ligneCatPrestaSoins->price.' Rwf)'.' -> ('.$ligneCatPrestaSoins->quantity.' Qty) </option>';                         
                                    
                                    while($lignePrestaSoins=$resultatsPrestaSoins->fetch())//on recupere la liste des éléments
                                    {
                                ?>
                                    <option value='<?php echo $lignePrestaSoins->sid;?>'><?php echo $lignePrestaSoins->product_name;?> <b>(<?php echo $lignePrestaSoins->price;?> Rwf)</b> -> <b>(<?php echo $lignePrestaSoins->quantity;?> Qty)</b></option>
                                <?php
                                    }$resultatsPrestaSoins->closeCursor();
                                
                                    echo '</optgroup>';
                                }else{
                                ?>
                                    
                                    <option value="autresoins" id="autresoins"><?php echo getString(232) ?></option>
                                    
                                <?php }?>
                                    
                                </select>
                                
                            </td>

                            <br>
                            <td>
                                <button type="submit" class="btn-large" name="listoutProduct" style="width:20%;margin-top: 30px;font-family:'ubuntu';">
                                    <i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i><?php echo getString(125);?>
                                
                                </button>
                            </td>
                            
                            <!-- <br>
                                <a href="newstock.php?Medicanentstockout=ok&expiredDr=ok" name="expiredDr" style="position: relative;top: 5px;cursor: pointer;">Show Expired Drugs</a>   -->            
                              <hr>
                        </tr>
                    </form>
                    <?php 
                        if ($countD !=0) {
                            $expire =array();
                            while($GetExpire = $EXp->fetch()){

                                $expire= $GetExpire->product_name;
                                echo "<input type='hidden' name='expire[]' class='expire' value=".$expire.">";
                                
                            } ?>
                            <?php echo '<i class="fa fa-warning"></i> ( '.$countD.' Consumables are Expired )';?>
                              <span onclick="ShowDrugs('showExpiredDr')" class="btn" title="Watch Expired Consumables"><?php echo 'Click to watch';?></span>

                            <?php                      
                        }
                    ?>
          <?php
            }
            if (isset($_GET['Newstockoutconsom'])) {
                $newStock = array();

                foreach ($_POST['checkstockconsom'] as $stockvalue) {
                    $newStock[] = $stockvalue;
                //print_r($newStock);
                }
         ?>
            <form method="POST" action="newstock.php?showstock=ok">
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Product Name</th>
                            <th style="width: 5%;">Dosage</th>
                            <th style="width: 5%;">P/U</th>
                            <th style="width: 5%;">Quantity (Out)</th>
                            <th style="width: 5%;">Taken By</th>
                            <th style="width: 5%;">Manufacture Date</th>
                            <th style="width: 5%;">Expired Date</th>
                            <th style="width: 5%;">Barcode</th>
                            <th style="width: 5%;">Supplier</th>
                            <th style="width: 5%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for ($i=0; $i < sizeof($newStock) ; $i++) { 
                                $sizeoftable = sizeof($newStock);

                                $selectinfoPro = $connexion->query('SELECT * FROM stockin WHERE sid="'.$newStock[$i].'" ORDER BY product_name ASC');
                                $selectinfoPro->setFetchMode(PDO::FETCH_OBJ);

                                $lingeProduct = $selectinfoPro->fetch();

                        ?>
                                <tr>
                                    <td style="border:1px solid #ddd;color: #666666e3;">
                                        <?php echo $lingeProduct->product_name; ?>
                                        <input type="hidden" name="pname<?php echo $i; ?>" value="<?php echo $lingeProduct->product_name; ?>" >
                                        <input type="hidden" name="pid<?php echo $i; ?>" value="<?php echo $lingeProduct->pid; ?>" >
                                        <input type="hidden" name="sid<?php echo $i; ?>" value="<?php echo $lingeProduct->sid; ?>" >
                                        <input type="hidden" name="mesure<?php echo $i; ?>" value="<?php echo $lingeProduct->mesure; ?>">
                                        <input type="hidden" name="sizeoftable" value="<?php echo $sizeoftable; ?>">
                                    </td>
                                    <td>
                                        <p style="border: 1px solid #a0000038;padding: 5px 5px;border-radius: 3px;"><?php echo 'mg/l'; ?></p>
                                    </td>
                                    <td>
                                        <!-- <input  style="width: 60px;" type="text" name="price<?php echo $i; ?>" value="<?php echo $lingeProduct->price; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo $lingeProduct->price;?> Rwf</p>
                                    </td>
                                    <td>
                                    <span style="color: #a0000094;position: relative;bottom: 10px;padding: 1px 7px;background: #ddd;border-radius: 5px;font-weight: bold;"><?php echo $lingeProduct->quantity; ?> - </span> <input style="width: 60px;position: relative;bottom: 10px;" type="text" name="quantity<?php echo $i; ?>" required> 
                                    </td> 

                                    <td>
                                        <input style="width: 100px;" type="text" name="takenby<?php echo $i; ?>" required> 
                                    </td>
                                    <td>
<!--                                 <input style="width: 150px;" type="text" onclick="ds_sh(this);" name="manufacturedate<?php echo $i; ?>">-->
                                        <p style="color: #666666e3;"><?php echo $lingeProduct->manufacturedate; ?></p>

                                    </td>
                                    <td>
                                        <!-- <input style="width: 150px;" type="text"  onclick="ds_sh(this);" name="expireddate<?php echo $i; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo $lingeProduct->expireddate; ?></p>

                                    </td>
                                    <td>
                                        <!-- <p style="width: 100px;" type="text" name="Barcode<?php echo $i; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo strtoupper($lingeProduct->barcode) ; ?></p>

                                    </td>
                                    <td>
                                        <!-- <input style="width: 150px;" type="text" name="suppliername<?php echo $i; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo  ucfirst(strtolower($lingeProduct->suppliername)); ?></p>
                                    </td>
                                    <td>
                                        <input style="width: auto;height: 40px;" type="date" name="date<?php echo $i; ?>" value="<?= $annee; ?>" required> 
                                    </td>

                                </tr>
                        <?php
                            }
                        ?>
                               
                    </tbody>
                </table>
                <br>
                 <button class="btn-large" name="stockOut">Stock Out</button>
            </form>
            <table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
            <tr>
                <td style="padding:5px;" id="ds_calclass"></td>
            </tr>
        </table>
        <?php
            }
        ?>

<!-- Material Stock out -->
<?php if(isset($_GET['Materialstockout'])){
    ?>
            <form method="POST" action="newstock.php?Materialstockout=ok&Newstockoutmaterial=ok">
                        <tr>
                            <td style="padding:5px;text-align:center;">
                                 <h3 style="background: #ddd;padding: 10px 10px;border:2px solid white;font-family:'ubuntu';">Select Material(Stock Out)</h3>
                               
                                    <hr>
                                <select style="margin:auto;margin-left: 50px;" multiple="multiple" name="checkstockmaterial[]" class="chosen-select" id="checkstockmaterial">                     
                                    <!--
                                    <option value='0'><?php echo getString(121) ?></option>
                                    -->                         
                                <?php
                                //$id_categopresta = '22';
                                $resultatsPrestaSoins=$connexion->query("SELECT * FROM stockin s,products p WHERE s.expireddate > $annee AND p.pro_id=s.pid AND p.id_categopresta=23 AND s.quantity>0");
                                //$resultatsPrestaSoins->execute(array('id'=>$id_categopresta));
                                
                                $resultatsPrestaSoins->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
                                
                                if($ligneCatPrestaSoins=$resultatsPrestaSoins->fetch())
                                {
                                    echo '<optgroup label="Material">';

                                    echo '<option value='.$ligneCatPrestaSoins->sid.' onclick="ShowOthersSoins(\'soins\')">'.$ligneCatPrestaSoins->product_name.' ('.$ligneCatPrestaSoins->price.' Rwf)'.' -> ('.$ligneCatPrestaSoins->quantity.' Qty) </option>';                         
                                    
                                    while($lignePrestaSoins=$resultatsPrestaSoins->fetch())//on recupere la liste des éléments
                                    {
                                ?>
                                    <option value='<?php echo $lignePrestaSoins->sid;?>'><?php echo $lignePrestaSoins->product_name;?> <b>(<?php echo $lignePrestaSoins->price;?> Rwf)</b> -> <b>(<?php echo $lignePrestaSoins->quantity;?> Qty)</b></option>
                                <?php
                                    }$resultatsPrestaSoins->closeCursor();
                                
                                    echo '</optgroup>';
                                }else{
                                ?>
                                    
                                    <option value="autresoins" id="autresoins"><?php echo getString(232) ?></option>
                                    
                                <?php }?>
                                    
                                </select>
                                
                            </td>

                            <br>
                            <td>
                                <button type="submit" class="btn-large" name="listoutProduct" style="width:20%;margin-top: 30px;">
                                    <i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i><?php echo getString(125);?>
                                
                                </button>
                            </td>
                            
                            <!-- <br>
                                <a href="newstock.php?Medicanentstockout=ok&expiredDr=ok" name="expiredDr" style="position: relative;top: 5px;cursor: pointer;">Show Expired Drugs</a>   -->            
                              <hr>
                        </tr>
                    </form>
                    <?php 
                        if ($countD !=0) {
                            $expire =array();
                            while($GetExpire = $EXp->fetch()){

                                $expire= $GetExpire->product_name;
                                echo "<input type='hidden' name='expire[]' class='expire' value=".$expire.">";
                                
                            } ?>
                            <?php echo '<i class="fa fa-warning"></i> ( '.$countD.' Products are Expired )';?>
                              <span onclick="ShowDrugs('showExpiredDr')" class="btn" title="Watch Expired Products"><?php echo 'Click to watch';?></span>

                            <?php                      
                        }
                    ?>
          <?php
            }
            if (isset($_GET['Newstockoutmaterial'])) {
                $newStock = array();

                foreach ($_POST['checkstockmaterial'] as $stockvalue) {
                    $newStock[] = $stockvalue;
                //print_r($newStock);
                }
         ?>
            <form method="POST" action="newstock.php?showstock=ok">
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Product Name</th>
                            <th style="width: 5%;">Dosage</th>
                            <th style="width: 5%;">P/U</th>
                            <th style="width: 5%;">Quantity (Out)</th>
                            <th style="width: 5%;">Taken By</th>
                            <th style="width: 5%;">Manufacture Date</th>
                            <th style="width: 5%;">Expired Date</th>
                            <th style="width: 5%;">Barcode</th>
                            <th style="width: 5%;">Supplier</th>
                            <th style="width: 5%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for ($i=0; $i < sizeof($newStock) ; $i++) { 
                                $sizeoftable = sizeof($newStock);

                                $selectinfoPro = $connexion->query('SELECT * FROM stockin WHERE sid="'.$newStock[$i].'" ORDER BY product_name ASC');
                                $selectinfoPro->setFetchMode(PDO::FETCH_OBJ);

                                $lingeProduct = $selectinfoPro->fetch();

                        ?>
                                <tr>
                                    <td style="border:1px solid #ddd;color: #666666e3;">
                                        <?php echo $lingeProduct->product_name; ?>
                                        <input type="hidden" name="pname<?php echo $i; ?>" value="<?php echo $lingeProduct->product_name; ?>" >
                                        <input type="hidden" name="pid<?php echo $i; ?>" value="<?php echo $lingeProduct->pid; ?>" >
                                        <input type="hidden" name="sid<?php echo $i; ?>" value="<?php echo $lingeProduct->sid; ?>" >
                                        <input type="hidden" name="mesure<?php echo $i; ?>" value="<?php echo $lingeProduct->mesure; ?>">
                                        <input type="hidden" name="sizeoftable" value="<?php echo $sizeoftable; ?>">
                                    </td>
                                    <td>
                                        <p style="border: 1px solid #a0000038;padding: 5px 5px;border-radius: 3px;"><?php echo $lingeProduct->mesure; ?></p>
                                    </td>
                                    <td>
                                        <!-- <input  style="width: 60px;" type="text" name="price<?php echo $i; ?>" value="<?php echo  number_format($lingeProduct->price,2); ?>"> -->
                                        <p style="color: #666666e3;"><?php echo number_format($lingeProduct->price);?> Rwf</p>
                                    </td>
                                    <td>
                                    <span style="color: #a0000094;position: relative;bottom: 10px;padding: 1px 7px;background: #ddd;border-radius: 5px;font-weight: bold;"><?php echo $lingeProduct->quantity; ?> - </span> <input style="width: 60px;position: relative;bottom: 10px;" type="text" name="quantity<?php echo $i; ?>" required> 
                                    </td> 

                                    <td>
                                        <input style="width: 100px;" type="text" name="takenby<?php echo $i; ?>" required> 
                                    </td>
                                    <td>
<!--                                 <input style="width: 150px;" type="text" onclick="ds_sh(this);" name="manufacturedate<?php echo $i; ?>">-->
                                        <p style="color: #666666e3;"><?php echo $lingeProduct->manufacturedate; ?></p>

                                    </td>
                                    <td>
                                        <!-- <input style="width: 150px;" type="text"  onclick="ds_sh(this);" name="expireddate<?php echo $i; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo $lingeProduct->expireddate; ?></p>

                                    </td>
                                    <td>
                                        <!-- <p style="width: 100px;" type="text" name="Barcode<?php echo $i; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo strtoupper($lingeProduct->barcode) ; ?></p>

                                    </td>
                                    <td>
                                        <!-- <input style="width: 150px;" type="text" name="suppliername<?php echo $i; ?>"> -->
                                        <p style="color: #666666e3;"><?php echo  ucfirst(strtolower($lingeProduct->suppliername)); ?></p>
                                    </td>
                                    <td>
                                        <input style="width: auto;height: 40px;" type="date" name="date<?php echo $i; ?>" value="<?= $annee; ?>" required> 
                                    </td>

                                </tr>
                        <?php
                            }
                        ?>
                               
                    </tbody>
                </table>
                <br>
                 <button class="btn-large" name="stockOut">Stock Out</button>
            </form>
            <table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
            <tr>
                <td style="padding:5px;" id="ds_calclass"></td>
            </tr>
        </table>
        <?php
            }
        ?>

<!-- end -->
<?php
            if (isset($_GET['editStock'])) {
                $sid = $_GET['sid'];

                // foreach ($_POST['checkstock'] as $stockvalue) {
                //     $newStock[] = $stockvalue;
                // //print_r($newStock);
                // }
        ?>
            <form method="POST" action="newstock.php?showstock=ok">
                <table class="tablesorter" cellspacing="0" style="width: 80%">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Dosage</th>
                            <th>P/U</th>
                            <th>Quantity</th>
                            <th>Expi Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $selectinfoPro = $connexion->query('SELECT * FROM stockin s WHERE sid="'.$sid.'" ORDER BY sid ASC');
                            $selectinfoPro->setFetchMode(PDO::FETCH_OBJ);

                            $lingeProduct = $selectinfoPro->fetch();

                        ?>
                                <tr>
                                    <td>
                                        <?php echo strtoupper($lingeProduct->product_name); ?>
                                        <input type="hidden" name="pid" value="<?php echo $lingeProduct->pid; ?>" >
                                        <input type="hidden" name="stoid" value="<?php echo $lingeProduct->sid; ?>" >
                                        <input type="hidden" name="mesure" value="<?php echo $lingeProduct->mesure; ?>">
                                    </td>
                                    <td style="width: 10%;" >
                                        <input style="width: 100%;" type="text" name="mesure" value="<?php echo $lingeProduct->mesure; ?>" >
                                    </td> 
                                    <td>
                                        <input type="text" name="price" value="<?php echo $lingeProduct->price; ?>" >
                                    </td>
                                    <td>
                                        <input type="text" name="quantity" value="<?php echo $lingeProduct->quantity; ?>">
                                    </td>  
                                    <td>
                                        <input type="text" name="expireddate" onclick="ds_sh(this);" value="<?php echo $lingeProduct->expireddate; ?>">
                                    </td>
                                </tr>
                        <?php
                           // }
                        ?>
                                    <td align="center">
                                        <button class="btn-large" name="updatestockbtn">Update</button>
                                    </td>
                    </tbody>
                </table>
                <table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
                    <tr>
                        <td style="padding:5px;" id="ds_calclass"></td>
                    </tr>
                </table>
            </form>

        <?php
            }
        ?>

        <?php
            if (isset($_GET['showstock'])) {
            $resultatsCount=$connexion->query('SELECT * FROM stockin WHERE quantity!=0 ORDER BY tme ASC');
            // $resultatsCount->execute();
            $resultatsCount->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
            $numresultatsCount = $resultatsCount->rowCount();

            if($numresultatsCount > 0){
          ?>
            <div style="margin-top:10px;">
                <h2><?php echo 'Stock List';?></h2>
                <table class="tablesorter" id="dataTables-example" cellspacing="0" style="width: 100%">
                    <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Dosage</th>
                        <th>Stock In</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($ligne=$resultatsCount->fetch()){
                        ?>
                        <tr style="text-align:center;">
                            <td style="text-align:left;"><?php echo $ligne->product_name;?></td>
                            <td><?php echo $ligne->quantity; ?></td>
                            <td><?php echo $ligne->price; ?></td>
                            <td><?php echo $ligne->mesure; ?></td>
                            <td><?php echo $ligne->tme ?></td>
                            <td>
                                <a href="newstock.php?sid=<?php echo $ligne->sid;?>&editStock=ok<?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}?>" class="btn-large"><i class="fa fa-pencil fa-1x fa-fw"></i></a>

                                <a href="newstock.php?sid=<?php echo $ligne->sid;?>&deleteStock=ok<?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}?>" class="btn-large-inversed"><i class="fa fa-trash fa-1x fa-fw"></i></a>
                            </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
                <?php 
                }
            }
            ?>







            <script>

                function getXMLHttpRequest() {
                    var xhr = null;

                    if (window.XMLHttpRequest || window.ActiveXObject) {
                        if (window.ActiveXObject) {
                            try {
                                xhr = new ActiveXObject("Msxml2.XMLHTTP");
                            } catch(e) {
                                xhr = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                        } else {
                            xhr = new XMLHttpRequest();
                        }
                    }else {
                        alert("Your Browser does not support XMLHTTPRequest object...");
                        return null;
                    }

                    return xhr;
                }



                function ShowDrugs(showExpiredDr){
    
                var elem = document.getElementsByClassName("expire");
                var names = array();
                for (var i = 0; i < elem.length; ++i) {
                
                    if (typeof elem[i].value != "") {
                        names.push(elem[i].value);
                    }
                }
                
                var webcamval = names;
                
                alert("List Of Expired Drugs :\n" + webcamval);
                }


                function ShowList(list)
                {
                    if( list =='Users')
                    {
                        document.getElementById('divMenuUser').style.display='inline';
                        document.getElementById('divMenuMsg').style.display='none';
                        document.getElementById('divListe').style.display='none';
                        document.getElementById('listOn').style.display='inline';
                        document.getElementById('listOff').style.display='none';
                    }

                    if( list =='Msg')
                    {
                        document.getElementById('divMenuMsg').style.display='inline';
                        document.getElementById('divMenuUser').style.display='none';
                        document.getElementById('divListe').style.display='none';
                        document.getElementById('listOn').style.display='inline';
                        document.getElementById('listOff').style.display='none';
                    }

                    if( list =='Liste')
                    {
                        document.getElementById('divListe').style.display='inline';
                        document.getElementById('listOff').style.display='inline';
                        document.getElementById('listOn').style.display='none';
                    }

                    if( list =='ListeNon')
                    {
                        document.getElementById('divListe').style.display='none';
                        document.getElementById('listOn').style.display='inline';
                        document.getElementById('listOff').style.display='none';
                    }

                }


                function controlFormPassword(theForm){
                    var rapport="";

                    rapport +=controlPass(theForm.Pass);

                    if (rapport != "") {
                        alert("Please review the following fields:\n" + rapport);
                        return false;
                    }
                }


                function controlPass(fld){
                    var erreur="";

                    if(fld.value=="")
                    {
                        erreur="Your new password\n";
                        fld.style.background="rgba(0,255,0,0.3)";
                    }

                    return erreur;
                }


            </script>

        </div>

        <?php

    }else{
        echo '<script language="javascript"> alert("Vous avez été désactivé!!\n Demander à l\'administrateur de vous activer");</script>';
        echo '<script language="javascript">document.location.href="index.php"</script>';
    }
}else{
    echo '<script language="javascript"> alert("Vous n\'êtes pas connecté");</script>';
    echo '<script language="javascript">document.location.href="index.php"</script>';
}



if(isset($_POST['confirmPass']))
{

    $pass = $_POST['Pass'];
    $iduti = $_SESSION['id'];

    $resultats=$connexion->prepare('UPDATE utilisateurs SET password=:pass WHERE id_u=:modifierIduti');

    $resultats->execute(array(
        'pass'=>$pass,
        'modifierIduti'=>$iduti
    ))or die( print_r($connexion->errorInfo()));

    echo '<script type="text/javascript"> alert("Vous venez de modifier votre mot de passe");</script>';

}
?>

<div> <!-- footer -->
    <footer style="bottom: 0px; width: 100%; text-align: center; background: #fff; border-top: 1px solid #eee; padding: 10px 0px; vertical-align: middle;">
        <p style="margin:0"><span style="color:#a00000">Medical FIle®</span> is a product of <span style="font-style:bold;">Innovate Solutions Ltd</span>. ©2022-<?php echo date('Y');?> All rights reserved.</p>
    </footer>
</div> <!-- /footer -->

<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
<script type="text/javascript">
    var q;

    for(q=0;q<5;q++)
    {
        $('#idExam'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#checkExam'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#checkSousCatego'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#prixref'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#editAssu'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
    }
</script>
    <script type="text/javascript">
    
        $('#checkstock').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
        $('#checkstockconsom').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
        $('#checkstockmaterial').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
        $('#checkprestaLab').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
        $('#checkprestaRad').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
    </script>

      <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
      <!-- CORE JQUERY  -->
      <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS  -->
      <script src="assets/js/bootstrap.js"></script>
      <!-- DATATABLE SCRIPTS  -->
      <script src="assets/js/dataTables/jquery.dataTables.js"></script>
      <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
      <script src="assets/js/custom.js"></script>  

</body>

</html>