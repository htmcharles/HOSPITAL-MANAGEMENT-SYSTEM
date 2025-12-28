<?php
	$PARAM_hote='127.0.0.1';
	$PARAM_port='3306';
	$PARAM_nom_bd='cng';
	$PARAM_utilisateur='root';
	$PARAM_mot_passe='';

	$connexion=new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd.';charset=UTF8',$PARAM_utilisateur,$PARAM_mot_passe,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));//connexion � la BD

	$nameHospital='Clinique De Ngororero';
?>