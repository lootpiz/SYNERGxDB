<?php
include './config.php';

$idSamples	 	= array();
$sample_names 	= array();
$idDrugs	 	= array();
$drug_names 	= array();

$query = "SELECT idSample, name FROM Sample ORDER BY name";
$result = $mysqli->query($query);
if(!$result) { echo $mysqli->error;	}
else { $sample_counts = $result->num_rows; }
if ($sample_counts > 0) {
	while($row = $result->fetch_assoc()) {
		array_push($idSamples, $row['idSample']);
		array_push($sample_names, $row['name']);
	}
}

$query = "SELECT idDrug, name FROM Drug ORDER BY name";
$result = $mysqli->query($query);
if(!$result) { echo $mysqli->error;	}
else { $drug_counts = $result->num_rows; }
if ($drug_counts > 0) {
	while($row = $result->fetch_assoc()) {
		array_push($idDrugs, $row['idDrug']);
		array_push($drug_names, $row['name']);
	}
}

$idSample = 0;
$idDrugA = 11;
$idDrugB = 97;
$idSource = 0;
$tissue = 0;
	
?>

<!DOCTYPE html>
<HTML>
        <HEAD>
                <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
                <META http-equiv="Content-Type" content="text/html; charset=UTF8" />
                <LINK REL='shortcut icon' HREF='./img/favicon.png' />
                <link rel="stylesheet" type="text/css" href="resource/mystyle.css" />
                <TITLE>
			SYNERGxDB
                </TITLE>
        </HEAD>
        <BODY>
             <FORM NAME="query" action="combo_summary.php" method="post">
                <DIV NAME="body" ALIGN="center">
                <table border=0 cellpadding=10 cellspacing=30>
                		<tr>
							<td align="center">
                            	<font size="5"><b>SYNERGxDB</b> is a web-application for discovering synergistic drug combinations and<br>corresponding biomarkers, in order to improve cancer patient prognosis and selection.</font>
                            </td>
                        </tr>
                        <tr align="center">
							<td align="center">
								<table border=0 cellpadding=5>
									<tr><td width=80><b>Tissue</b></td><td> :
										<select name="tissue">
										<option value=0 <?php if($tissue == 0) { echo " selected"; } ?>>Any tissue</option>
										<option value=1 <?php if($tissue == 1) { echo " selected"; } ?>>Breast</option>
										<option value=2 <?php if($tissue == 2) { echo " selected"; } ?>>Bladder</option>
										<option value=3 <?php if($tissue == 3) { echo " selected"; } ?>>Blood</option>
										<option value=4 <?php if($tissue == 4) { echo " selected"; } ?>>Brain</option>
										<option value=5 <?php if($tissue == 5) { echo " selected"; } ?>>Colorectal</option>
										<option value=6 <?php if($tissue == 6) { echo " selected"; } ?>>Kidney</option>
										<option value=7 <?php if($tissue == 7) { echo " selected"; } ?>>Lung</option>
										<option value=8 <?php if($tissue == 8) { echo " selected"; } ?>>Mesothelioma</option>
										<option value=9 <?php if($tissue == 9) { echo " selected"; } ?>>Ovary</option>
										<option value=10 <?php if($tissue == 10) { echo " selected"; } ?>>Stomach</option>
										<option value=11 <?php if($tissue == 11) { echo " selected"; } ?>>Pancreas</option>
										<option value=12 <?php if($tissue == 12) { echo " selected"; } ?>>Prostate</option>
										<option value=13 <?php if($tissue == 13) { echo " selected"; } ?>>Skin</option>
										</select>
									</td><td>&nbsp;</td></tr>

									<tr><td width=80><b>Sample</b></td><td> :
										<select name="sample">
										<option value=0>Any sample</option>
					<?php for($idx = 0 ; $idx < $sample_counts ; ++$idx) { ?>
										<option value="<?php echo $idSamples[$idx]; ?>" <?php if($idSamples[$idx] == $idSample) { echo " selected"; } ?>><?php echo $sample_names[$idx]; ?></option>
					<?php } ?>			
										</select>
									</td><td>&nbsp;</td></tr>

									<tr><td><b>Drug<sub><i>A</i></sub></b></td><td> :
										<select name="drugA">
										<option value=0>Any drug</option>
					<?php for($idx = 0 ; $idx < $drug_counts ; ++$idx) { ?>
										<option value="<?php echo $idDrugs[$idx]; ?>" <?php if($idDrugs[$idx] == $idDrugA) { echo " selected"; } ?>><?php echo $drug_names[$idx]; ?></option>
					<?php } ?>			
										</select>
									</td><td>&nbsp;</td></tr>

									<tr><td><b>Drug<sub><i>B</i></sub></b></td><td> :
										<select name="drugB">
										<option value=0>Any drug</option>
					<?php for($idx = 0 ; $idx < $drug_counts ; ++$idx) { ?>
										<option value="<?php echo $idDrugs[$idx]; ?>" <?php if($idDrugs[$idx] == $idDrugB) { echo " selected"; } ?>><?php echo $drug_names[$idx]; ?></option>
					<?php } ?>			
										</select>
									</td><td>&nbsp;</td></tr>

									<tr><td><b>Dataset</b></td><td> :
										<select name="source">
										<option value="0" <?php if($idSource == 0) { echo " selected"; } ?>>Any dataset</option>
										<!--option value="3" <?php if($idSource == 3) { echo " selected"; } ?>>AstraZeneca</option-->
										<option value="2" <?php if($idSource == 2) { echo " selected"; } ?>>NCI-ALMANAC</option>
										<option value="1" <?php if($idSource == 1) { echo " selected"; } ?>>MERCK</option>
										<option value="7" <?php if($idSource == 7) { echo " selected"; } ?>>MIT-MELANOMA</option>
										<option value="5" <?php if($idSource == 5) { echo " selected"; } ?>>YALE-TNBC</option>
										<option value="4" <?php if($idSource == 4) { echo " selected"; } ?>>YALE-PDAC</option>
										<option value="8" <?php if($idSource == 8) { echo " selected"; } ?>>STANFORD</option>
										<option value="6" <?php if($idSource == 6) { echo " selected"; } ?>>CLOUD</option>
										</select>
									</td><td><input name="submit" type="submit" value="Search"></td>
								</tr>
							</table>
						</td>
                    </tr>
                    <tr>
                			<TABLE WIDTH="800" BORDER="1" CELLPADDING="1" CELLSPACING="0">
								<TR BGCOLOR="black">
									<td width="133" align="center"><font color="white"># Cell lines</font></td>
									<td width="133" align="center"><font color="white"># Tissue types</font></td>
									<td width="133" align="center"><font color="white"># Drugs</font></td>
									<td width="133" align="center"><font color="white"># Combinations</font></td>
									<td width="133" align="center"><font color="white"># Experiments</font></td>
									<td width="135" align="center"><font color="white"># Data points</font></td>
                				</tr>
                				<tr>
	                				<td width="133" align="center"><font size=4>&nbsp;<br>123<br>&nbsp;</font></td>
	                				<td width="133" align="center"><font size=4>&nbsp;<br>11<br>&nbsp;</font></td>
	                				<td width="133" align="center"><font size=4>&nbsp;<br>1,965<br>&nbsp;</font></td>
	                				<td width="133" align="center"><font size=4>&nbsp;<br>14,634<br>&nbsp;</font></td>
	                				<td width="133" align="center"><font size=4>&nbsp;<br>475,278<br>&nbsp;</font></td>
	                				<td width="135" align="center"><font size=4>&nbsp;<br>8,454,213<br>&nbsp;</font></td>
                				</tr>
                			</table>
                		</tr>
            	</table>
		<br><br>
		<font color="grey"><i>This is a prototype. Please visit <a href="http://SYNERGxDB.ca" target="_blank">http://SYNERGxDB.ca/</a> for the production!</i></font>
                </DIV>
			</FORM>
        </BODY>
</HTML>
<?php
mysqli_close($con);
?>
