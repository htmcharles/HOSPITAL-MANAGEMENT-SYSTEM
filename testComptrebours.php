<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="testComptrebours.php">
		<button onclick="start()">Lancer le décompte</button>
	</form>
	
	<div id="bip" class="display"></div>

	<script>
	var counter = 10;
	var intervalId = null;
	function finish() {
	  clearInterval(intervalId);
	  document.getElementById("bip").innerHTML = "TERMINE!";	
	  window.location.href = "index.php";
	}
	function bip() {
	    counter--;
	    if(counter == 0) finish();
	    else {	
	        document.getElementById("bip").innerHTML = counter + " secondes restantes";
	    }	
	}
	function start(){
	  intervalId = setInterval(bip, 1000);
	}	
	</script>
</body>
</html>