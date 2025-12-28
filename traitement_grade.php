<?php
try
{
	include("connect.php");

	$id_grade=$_POST['grade'];

	//$id_dep=1;
	$req=$connexion->prepare('SELECT * FROM categopresta_ins WHERE id_grade=:grad');
	$req->execute(array('grad'=>$id_grade));

	$compteur=$req->rowCount();
	
	if($compteur!=0)
	{
		$categopresta.="<select name='categopresta' id='categopresta'>";
			while($reponse=$req->fetch(PDO::FETCH_ASSOC))
			{
				// $id_grade=$reponse['id_grade'];
				$categopresta.="<option value=".$reponse['id_categopresta'].">".$reponse['nomcategopresta']."</option>";
			}
		$categopresta.="</select>";
		
		echo $categopresta;
		$req->closeCursor();
	
	}

}

catch(Excepton $e)
{
	echo 'Erreur:'.$e->getMessage().'<br/>';
	echo'Numero:'.$e->getCode();
}

?>