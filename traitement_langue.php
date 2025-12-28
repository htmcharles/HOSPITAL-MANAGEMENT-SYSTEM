<?php
try
{
	
	session_start();
	include("connect.php");

	if(isset($_GET['francais']))
	{
		$_SESSION['langue']='francais';
	}
	
	if(isset($_GET['anglais']))
	{
		$_SESSION['langue']='english';
	}
	
}

catch(Excepton $e)
{
	echo 'Erreur:'.$e->getMessage().'<br/>';
	echo'Numero:'.$e->getCode();
}

?>