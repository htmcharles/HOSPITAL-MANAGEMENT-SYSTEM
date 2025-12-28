<!--

<!doctype html>
<html lang="en">
<noscript>
This page requires Javascript.
Please enable it in your browser.
</noscript>
<head>
	<meta charset="utf-8"/>
</head>

<body>

-->

<?php
include("connect.php");

if(isset($_POST['desti']))
{

	$id_prov=$_POST['desti'];
	
	if($id_prov == "Med")
	{
		$req=$connexion->query('SELECT * FROM medecins m, utilisateurs u WHERE m.id_u=u.id_u ORDER BY u.nom_u');
		
	}
	
	if($id_prov == "Inf")
	{
		$req=$connexion->query('SELECT * FROM infirmiers i, utilisateurs u WHERE i.id_u=u.id_u ORDER BY u.nom_u');
		
	}
	
	if($id_prov == "Lab")
	{
		$req=$connexion->query('SELECT * FROM laborantins l, utilisateurs u WHERE l.id_u=u.id_u ORDER BY u.nom_u');
		
	}
	
	if($id_prov == "Rec")
	{
		$req=$connexion->query('SELECT * FROM receptionistes r, utilisateurs u WHERE r.id_u=u.id_u ORDER BY u.nom_u');
		
	}
	
	if($id_prov == "Cash")
	{
		$req=$connexion->query('SELECT * FROM cashiers c, utilisateurs u WHERE c.id_u=u.id_u ORDER BY u.nom_u');
		
	}
	
	if($id_prov == "Aud")
	{
		$req=$connexion->query('SELECT * FROM auditors a, utilisateurs u WHERE a.id_u=u.id_u ORDER BY u.nom_u');
		
	}
	
	if($id_prov == "Acc")
	{
		$req=$connexion->query('SELECT * FROM accountants acc, utilisateurs u WHERE acc.id_u=u.id_u ORDER BY u.nom_u');
		
	}
	
	if($id_prov == "Man")
	{
		$req=$connexion->query('SELECT * FROM coordinateurs c, utilisateurs u WHERE c.id_u=u.id_u ORDER BY u.nom_u');
		
	}
	
	
	$to.="<select name='to' id='to' style='display:none'>";
		while($reponse=$req->fetch(PDO::FETCH_ASSOC))
		{
			// $id_departement=$reponse['id_departement'];
			$to.="<option value=".$reponse['id_u'].">".htmlentities($reponse['nom_u'])." ".htmlentities($reponse['prenom_u'])."</option>";
		}
		
	$to.="</select>";

	echo $to;
	$req->closeCursor();

}
?>

<!--

</body>

</html>

-->