
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Test Search</title>
		
		<link rel="icon" href="images/favicon.ico">
		<link rel="shortcut icon" href="images/favicon.ico"/>
		
		<link href="css/testSearch.css" rel="stylesheet" type="text/css"/>

		
		<script type="text/javascript"></script>
	</head>

	<body>
		<?php
			if(isset($_GET['finishbtn']))
			{
		?>
				<!-- <body onload="window.print(); window.close()"> -->
				<body onload="imprim();">
		<?php
			}
		?>

		<div class="tables">
			<p>
				<label for="search">
					<strong>Enter keyword to search </strong>
				</label>
				<input type="text" id="search"/>
				<label>e.g. bar, parking, tv</label>


				<form method="POST" action="test.php?finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo 'Print small size';?></button>
							
				</form>
			</p>
			<table width="100%" id="tblData" class="target" bgcolor="#ACAAFC">
				<tbody>
					<tr>
						<th width="10%">#</th>
						<th width="35%">Hotel Name</th>
						<th width="55%">Facilities</th>
					</tr>
					<tr>
						<td class="odd">1</td>
						<td class="odd">Manu Maharani</td>
						<td class="odd">Attached Bath, Bar, Swimming Pool, </td>
					</tr>
					<tr>
						<td class="even">2</td>
						<td class="even">Hill View</td>
						<td class="even">TV, In-Room Safe, Bar</td>
					</tr>
					<tr>
						<td class="odd">3</td>
						<td class="odd">Hotel Suba Galaxy</td>
						<td class="odd">Paid Internet Access, Coffee Shop, Spa</td>
					</tr>
					<tr>
						<td class="even">4</td>
						<td class="even">The Residence Hotel</td>
						<td class="even">Doctor on Call, Parking</td>
					</tr>
					<tr>
						<td class="odd">5</td>
						<td class="odd">The Taj</td>
						<td class="odd">Currency Exchange, Bar, Golf</td>
					</tr>
					<tr>
						<td class="even">6</td>
						<td class="even">Mumbai Grand</td>
						<td class="even">Jacuzzi, Spa, Coffee Shop</td>
					</tr>
					<tr>
						<td class="odd">7</td>
						<td class="odd">The Promenade</td>
						<td class="odd">Cable TV, Coffee Shop, Spa</td>
					</tr>
					<tr>
						<td class="even">8</td>
						<td class="even">Hotel Regency</td>
						<td class="even">Mini Bar,Golf, Spa, Sauna</td>
					</tr>
					<tr>
						<td class="odd">9</td>
						<td class="odd">Park Plaza</td>
						<td class="odd">Currency Exchange, Bar, Golf</td>
					</tr>
					<tr>
						<td class="even">10</td>
						<td class="even">The Mapple Inn</td>
						<td class="even">Jacuzzi, Spa, Coffee Shop</td>
					</tr>
					<tr>
						<td class="odd">11</td>
						<td class="odd">Cidade de Goa</td>
						<td class="odd">Cable TV, Coffee Shop, Spa</td>
					</tr>
					<tr>
						<td class="even">12</td>
						<td class="even">Saurabh Mountview</td>
						<td class="even">Doctor, Free Parking</td>
					</tr>
				</tbody>
			</table>
		</div>
		<script src="js/jquery-2.2.0.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function()
			{
				$('#search').keyup(function()
				{
					searchTable($(this).val());
				});
			});

			function searchTable(inputVal)
			{
				var table = $('#tblData');
				table.find('tr').each(function(index, row)
				{
					var allCells = $(row).find('td');
					if(allCells.length > 0)
					{
						var found = false;
						allCells.each(function(index, td)
						{
							var regExp = new RegExp(inputVal, 'i');
							if(regExp.test($(td).text()))
							{
								found = true;
								return false;
							}
						});
						if(found == true)$(row).show();else $(row).hide();
					}
				});
			}
			/*function finish(){
				var term
			}*/
		</script>
		<!-- <script type="text/javascript">
		    var document_focus = false; // var we use to monitor document focused status.
		    // Now our event handlers.
		    $(document).focus(function() { document_focus = true; });
		    $(document).ready(function() { window.print(); });
		    setInterval(function() { 
		    	if (document_focus === true) {
		    		window.close();
		    		//var p = $('#action-button');
		    		if ($(document).onclick()) {

		    		}
		    	} 
		    }, 500);
		</script> -->
		<script type="text/javascript">
			function imprim(){
				//window.print();
				var document_focus = false; // var we use to monitor document focused status.
			    // Now our event handlers.
			    $(document).focus(function() { document_focus = true; });
			    $(document).ready(function() { window.print(); });
			    const valide = document.querySelector("#action-button");
			    valide.map(lien =>lien.addEventListener("click",function(){
			    	console.log("click");
			    }))
			  /*  setInterval(function() { 
			    	if (document_focus === true) {
			    		window.location = "./index.php";
			    		window.close();
			    		//var p = $('#action-button');
			    		if ($(document).onclick()) {

			    		}
			    	} 
			    }, 500);
				*/
				return false;
			}
		</script>
	</body>
</html>
