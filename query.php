<?php
	include './config.php';
	
	$idSamples	 	= array();
	$sample_names 	= array();
	$idDrugs	 	= array();
	$drug_names 	= array();

	$query = "SELECT idSample, name FROM Sample ORDER BY idSample";
	$result = $mysqli->query($query);
	if(!$result) { echo $mysqli->error;	}
	else { $sample_counts = $result->num_rows; }
	if ($sample_counts > 0) {
    	while($row = $result->fetch_assoc()) {
			array_push($idSamples, $row['idSample']);
			array_push($sample_names, $row['name']);
		}
	}

	$query = "SELECT idDrug, name FROM Drug ORDER BY idDrug";
	$result = $mysqli->query($query);
	if(!$result) { echo $mysqli->error;	}
	else { $drug_counts = $result->num_rows; }
	if ($drug_counts > 0) {
    	while($row = $result->fetch_assoc()) {
			array_push($idDrugs, $row['idDrug']);
			array_push($drug_names, $row['name']);
		}
	}
?>
<HTML>
	<HEAD>
		<META HTTP-EQUIV="CONTENT-Type" CONTENT="text/html" CHARSET="utf-8">
		<link rel="stylesheet" type="text/css" href="./resource/mystyle.css" />
		<script>
			function validateForm() {
				var idDrugA = document.forms["query"]["drugA"].value;
				var idDrugB = document.forms["query"]["drugB"].value;
				var source = document.forms["query"]["source"].value;
				if (idDrugA == idDrugB) {
					alert("You selected the same drugs!");
					return false;
				}
				if (source > 1) {
					alert("NCI-ALMANAC data not ready!");
					return false;
				}
			}
		</script>
		<TITLE> Drug Combo :: Query </TITLE>
	</HEAD>
	<BODY>
		<FORM NAME="query" action="combo_detail.php" onsubmit="return validateForm()" method="post">
			<h2>Search</h2>
			<table border=0 cellpadding=5>
				<tr><td width=80>Sample</td><td> :
					<select name="sample">
<?php for($idx = 0 ; $idx < $sample_counts ; ++$idx) { ?>
					<option value="<?php echo $idSamples[$idx]; ?>" <?php if($sample_names[$idx] == "A2780") { echo " selected"; } ?>><?php echo $sample_names[$idx]; ?></option>
<?php } ?>			
					</select>
				</td><td>&nbsp;</td></tr>

				<tr><td>Drug<sub>A</sub></td><td> :
					<select name="drugA">
<?php for($idx = 0 ; $idx < $drug_counts ; ++$idx) { ?>
					<option value="<?php echo $idDrugs[$idx]; ?>" <?php if($drug_names[$idx] == "ABT-888,") { echo " selected"; } ?>><?php echo $drug_names[$idx]; ?></option>
<?php } ?>			
					</select>
				</td><td>&nbsp;</td></tr>

				<tr><td>Drug<sub>B</sub></td><td> :
					<select name="drugB">
<?php for($idx = 0 ; $idx < $drug_counts ; ++$idx) { ?>
					<option value="<?php echo $idDrugs[$idx]; ?>" <?php if($drug_names[$idx] == "Fluorouracil") { echo " selected"; } ?>><?php echo $drug_names[$idx]; ?></option>
<?php } ?>			
					</select>
				</td><td>&nbsp;</td></tr>

				<tr><td>Source</td><td> :
					<select name="source">
					<option value="1" selected>MERCK</option>
					<option value="2">NCI-ALMANAC</option>
					</select>
				</td><td><input type="submit" value="Go"></td></tr>
			</table>
				
		</FORM>
	</BODY>
</HTML>

<?php
mysqli_close($con);
?>
