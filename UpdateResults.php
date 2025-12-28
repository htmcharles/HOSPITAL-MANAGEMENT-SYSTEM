<?php
session_start();
include("connect.php");
include("connectLangues.php");
$annee = date('Y').'-'.date('m').'-'.date('d');


//get all missed exams
// echo "SELECT * FROM `med_labo` WHERE `examenfait` != 1 AND dateconsu<'".$annee."' LIMIT 5";
// $missedExa = $connexion->query("SELECT * FROM `med_labo` WHERE `examenfait` != 1 AND dateconsu<'".$annee."'");
// $missedExa->setFetchMode(PDO::FETCH_OBJ);
// while($Results = $missedExa->fetch()){
//     // update 
//     $examenfait = 1;
//     $resu = "Normal";
//     $id_uL = 1195;

//     // echo $Results->id_medlabo.'<br>';
    
//     $updateNormal = $connexion->prepare("UPDATE `med_labo` SET `autreresultats`=:resu,`dateresultats`=:dateResu,`id_uL`=:id_uL,`examenfait`=:fait  WHERE `id_medlabo`=:id_medlabo");
//     $updateNormal->execute(array(
//     'resu'=>$resu,
//     'dateResu'=>$Results->dateconsu,
//     'id_uL'=>$id_uL,
//     'fait'=>$examenfait,
//     'id_medlabo'=>$Results->id_medlabo
//     ));
// }



// Missed Radio

// $missedExa = $connexion->query("SELECT * FROM `med_radio` WHERE `radiofait` != 1 AND dateconsu<'".$annee."'");
// $missedExa->setFetchMode(PDO::FETCH_OBJ);
// while($Results = $missedExa->fetch()){
//     // update 
//     $radiofait = 1;
//     $resu = "Normal";
//     $id_uX = 1195;

//     // echo $Results->id_medradio.'<br>';
    
//     $updateNormal = $connexion->prepare("UPDATE `med_radio` SET `resultatsRad`=:resu,`dateradio`=:dateResu,`id_uX`=:id_uX,`radiofait`=:fait  WHERE `id_medradio`=:id_medradio");
//     $updateNormal->execute(array(
//     'resu'=>$resu,
//     'dateResu'=>$Results->dateconsu,
//     'id_uX'=>$id_uX,
//     'fait'=>$radiofait,
//     'id_medradio'=>$Results->id_medradio
//     ));
// }


// Update diagnostic



?>