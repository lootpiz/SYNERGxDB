<?php
include './config.php';

$idSamples	 	= array();
$sample_names 		= array();
$idDrugs	 	= array();
$drug_names 		= array();

$idCombo_Design 	= array();
$names 			= array();
$tissues		= array();
$drugAs		 	= array();
$drugBs			= array();
$bliss			= array();
$loewe			= array();
$hsa			= array();
$zip			= array();
$idSources		= array();

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

if(isset($_POST['submit'])) {
	$idSample = $_POST['sample'];
	$idDrugA = $_POST['drugA'];
	$idDrugB = $_POST['drugB'];
	$idSource = $_POST['source'];
	$tissue = $_POST['tissue'];
} else {
	$idSample = 0;
	$idDrugA = 11;
	$idDrugB = 97;
	$idSource = 0;
	$tissue = 0;
}
if($idDrugA > $idDrugB) {
	$idTmp = $idDrugA;
	$idDrugA = $idDrugB;
	$idDrugB = $idTmp;
}

$exp_counts = 0;

$where_and = array();
$where_or = array();

if(!empty($idSample) && $idSample != 0) { array_push($where_and, "S.idSample = $idSample"); } 

if($idDrugA != 0 && $idDrugB != 0) { array_push($where_and, "Da.idDrug = $idDrugA"); array_push($where_and, "Db.idDrug = $idDrugB"); }
else if($idDrugA == 0 && $idDrugB != 0) { array_push($where_or, "Da.idDrug = $idDrugB"); array_push($where_or, "Db.idDrug = $idDrugB"); }

if(!empty($idSource) && $idSource == 1) { array_push($where_and, "SS.idSource = 1"); }
else if(!empty($idSource) && $idSource == 2) { array_push($where_and, "SS.idSource = 2"); }
else if(!empty($idSource) && $idSource == 4) { array_push($where_and, "SS.idSource = 4"); }
else if(!empty($idSource) && $idSource == 5) { array_push($where_and, "SS.idSource = 5"); }
else if(!empty($idSource) && $idSource == 6) { array_push($where_and, "SS.idSource = 6"); }
else if(!empty($idSource) && $idSource == 7) { array_push($where_and, "SS.idSource = 7"); }
else if(!empty($idSource) && $idSource == 8) { array_push($where_and, "SS.idSource = 8"); }

if(!empty($tissue) && $tissue == 1) { array_push($where_and, "S.tissue = \"breast\""); }
else if(!empty($tissue) && $tissue == 2) { array_push($where_and, "S.tissue = \"bladder\""); }
else if(!empty($tissue) && $tissue == 3) { array_push($where_and, "S.tissue = \"blood\""); }
else if(!empty($tissue) && $tissue == 4) { array_push($where_and, "S.tissue = \"brain\""); }
else if(!empty($tissue) && $tissue == 5) { array_push($where_and, "S.tissue = \"colorectal\""); }
else if(!empty($tissue) && $tissue == 6) { array_push($where_and, "S.tissue = \"kidney\""); }
else if(!empty($tissue) && $tissue == 7) { array_push($where_and, "S.tissue = \"lung\""); }
else if(!empty($tissue) && $tissue == 8) { array_push($where_and, "S.tissue = \"mesothelioma\""); }
else if(!empty($tissue) && $tissue == 9) { array_push($where_and, "S.tissue = \"ovary\""); }
else if(!empty($tissue) && $tissue == 10) { array_push($where_and, "S.tissue = \"stomach\""); }
else if(!empty($tissue) && $tissue == 11) { array_push($where_and, "S.tissue = \"pancreas\""); }
else if(!empty($tissue) && $tissue == 12) { array_push($where_and, "S.tissue = \"prostate\""); }
else if(!empty($tissue) && $tissue == 13) { array_push($where_and, "S.tissue = \"skin\""); }

$query = "SELECT S.name as name, S.tissue as tissue, Da.name as drugA, Db.name as drugB, SS.idCombo_Design as idCombo_Design, SS.bliss as bliss, SS.loewe as loewe, SS.hsa as hsa, SS.zip as zip, SS.idSource as idSource ";
$query .= "FROM ((((Combo_Design as CD INNER JOIN Sample as S ON CD.idSample = S.idSample) ";
$query .= "INNER JOIN Drug as Da ON CD.idDrugA = Da.idDrug) ";
$query .= "INNER JOIN Drug as Db ON CD.idDrugB = Db.idDrug) ";
$query .= "INNER JOIN Synergy_Score as SS on CD.idCombo_Design = SS.idCombo_Design) ";
if(!empty($where_and)) { $query .= "WHERE ".implode($where_and, " and ")." "; }

if(!empty($where_and) && !empty($where_or)) { $query .= "AND (".implode($where_or, " OR ").") "; }
else if(empty($where_and) && !empty($where_or)) { $query .= "WHERE ".implode($where_or, " OR ")." "; }

$query .= "ORDER BY zip desc, tissue, name, drugA, drugB";

$result = $mysqli->query($query);
if(!$result) { echo $mysqli->error;	}
else { $exp_counts += $result->num_rows; }

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		array_push($idCombo_Design, $row['idCombo_Design']);
		array_push($names, $row['name']);
		array_push($tissues, $row['tissue']);
		array_push($drugAs, $row['drugA']);
		array_push($drugBs, $row['drugB']);
		array_push($bliss, isNull($row['bliss']));
		array_push($loewe, isNull($row['loewe']));
		array_push($hsa, isNull($row['hsa']));
		array_push($zip, isNull($row['zip']));
		array_push($idSources, $row['idSource']);
	}
}

$query_display = $query;


$anova_sources = array();
$anova_drugas = array();
$anova_drugbs = array();
$anova_genes = array();
$anova_ps = array();
$anova_sig_counts = 0;

$query = "SELECT idSource, idDrugA, idDrugB, gene, p from anova where ";
if($idSource != 0) { $query = $query." idSource = ".$idSource." and"; }
if($idDrugA != 0 && $idDrugB != 0) { $query = $query."( idDrugA = ".$idDrugA." and idDrugB = ".$idDrugB. " ) and "; } 
else if($idDrugA == 0 && $idDrugB != 0) { $query = $query."( idDrugA = ".$idDrugB. " or idDrugB = ".$idDrugB. " ) and "; }
$query = $query." p > 0 and p < 0.05 order by p, gene limit 11";

$anova_result = $mysqli->query($query);
if(!$anova_result) { $mysqli->error;     }
else { $anova_sig_counts += $anova_result->num_rows; }

if ($anova_result->num_rows > 0) {
	while($anova_row = $anova_result->fetch_assoc()) {
		array_push($anova_drugas, $anova_row['idDrugA']);
		array_push($anova_drugbs, $anova_row['idDrugB']);
		array_push($anova_sources, isNull($anova_row['idSource']));
		array_push($anova_genes, isNull($anova_row['gene']));
		array_push($anova_ps, sprintf('%.2E', $anova_row['p']));
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
				if (idDrugA == idDrugB && idDrugA != 0) {
					alert("You selected the same drugs!");
					return false;
				}
			}
		</script>
		<TITLE> Drug Combo :: Summary </TITLE>
	</HEAD>
	<BODY TOPMARGIN="20">
		<FORM NAME="query" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()" method="post">
			<h2>Search</h2>
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

				<tr><td><b>Drug<sub>A</sub></b></td><td> :
					<select name="drugA">
					<option value=0>Any drug</option>
<?php for($idx = 0 ; $idx < $drug_counts ; ++$idx) { ?>
					<option value="<?php echo $idDrugs[$idx]; ?>" <?php if($idDrugs[$idx] == $idDrugA) { echo " selected"; } ?>><?php echo $drug_names[$idx]; ?></option>
<?php } ?>			
					</select>
				</td><td>&nbsp;</td></tr>

				<tr><td><b>Drug<sub>B</sub></b></td><td> :
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
				</td><td><input name="submit" type="submit" value="Search"></td></tr>
			</table>
		</FORM>
		<hr>
		<h2>Potential Biomarkers, <?php if($anova_sig_counts > 10) { echo "Top 10"; $anova_sig_counts = 10; } else { echo"<i>N</i>= ".$anova_sig_counts; } ?></h2>
<?php
if($anova_sig_counts > 0){
?>
		<TABLE WIDTH="800" BORDER="0" CELLPADDING="3" CELLSPACING="0">
			<TR BGCOLOR="black">
				<TH><FONT COLOR="white">Gene Symbol</FONT></TH> 
				<TH><FONT COLOR="white">One-way ANOVA <i>P</i></FONT></TH> 
				<TH><FONT COLOR="white">Source</FONT></TH> 
			</TR>
<?php
for($idx2 = 0 ; $idx2 < $anova_sig_counts ; ++$idx2) {
?>
			<TR>
				<TD><i><?php echo $anova_genes[$idx2]; ?></i></TD>
				<TD align="center"><?php echo $anova_ps[$idx2]; ?></TD>
				<TD align="center"><a href="./imgs/stats/<?php echo $anova_sources[$idx2]."/".$anova_genes[$idx2]."/".$anova_sources[$idx2]."__".$anova_drugas[$idx2]."__".$anova_drugbs[$idx2]."__".$anova_genes[$idx2]; ?>.png" target="_blank"><img src="./imgs/source_icon_<?php echo $anova_sources[$idx2]; ?>.png"></a></TD>	
			</TR>
<?php
}
?>
			<TR><TD colspan=3><HR></TD></TR>
		</TABLE>
<?php
}
?>
		<a href="./anova_results.php?source=<?php echo $idSource; ?>&drugA=<?php echo $idDrugA; ?>&drugB=<?php echo $idDrugB; ?>" target="main">Click here to check <i>P</i>-values of all protein-coding genes.</a>
		<br>
		<br>
		<hr>
		<h2>Synergy Scores, <i>N</i>=<?php echo $exp_counts; ?></h2>
		<TABLE WIDTH="800" BORDER="0" CELLPADDING="3" CELLSPACING="0">
			<TR BGCOLOR="black">
				<TH ROWSPAN=2><FONT COLOR="white">Tissue</FONT></TH>
				<TH ROWSPAN=2><FONT COLOR="white">Cell line</FONT></TH>
				<TH ROWSPAN=2><FONT COLOR="white">Drug<sub>A</sub></FONT></TH>
				<TH ROWSPAN=2><FONT COLOR="white">Drug<sub>B</sub></FONT></TH>
				<TH COLSPAN=4><FONT COLOR="white">Synergy Scores</FONT></TH>
				<TH ROWSPAN=2><FONT COLOR="white">Source</FONT></TH>
			</TR>
			<TR BGCOLOR="black">
				<TH><FONT COLOR="white">ZIP</FONT></TH>
				<TH><FONT COLOR="white">Bliss</FONT></TH>
				<TH><FONT COLOR="white">Loewe</FONT></TH>
				<TH><FONT COLOR="white">HSA</FONT></TH>
			</TR>
<?php
for($idx = 0 ; $idx < $exp_counts ; ++$idx){
	$bgcolor = "d6d6d6";
	if($idx%2 == 0){
		$bgcolor = "white";
	}
	
	if(strlen($drugAs[$idx]) > 20) {
		$drugA_label = substr($drugAs[$idx],0,20)."..";
	} else {
		$drugA_label = $drugAs[$idx];
	}
	if(strlen($drugBs[$idx]) > 20) {
		$drugB_label = substr($drugBs[$idx],0,20)."..";
	} else {
		$drugB_label = $drugBs[$idx];
	}
?>
			<TR BGCOLOR="<?php echo $bgcolor; ?>">
				<TD><?php echo $tissues[$idx]; ?></TD>
				<TD><?php echo $names[$idx]; ?></TD>
				<TD><?php echo $drugA_label; ?></TD>
				<TD><?php echo $drugB_label; ?></TD>
				<TD align="right"><?php if($zip[$idx] > 0.2) { echo "<b>"; } echo number_format($zip[$idx], 4, '.', ','); if($zip[$idx] > 0.2) { echo "</b>"; } ?></TD>
				<TD align="right"><?php if($bliss[$idx] > 0.2) { echo "<b>"; } echo number_format($bliss[$idx], 4, '.', ','); if($bliss[$idx] > 0.2) { echo "</b>"; } ?></TD>
				<TD align="right"><?php if($loewe[$idx] > 0.2) { echo "<b>"; } echo number_format($loewe[$idx], 4, '.', ','); if($loewe[$idx] > 0.2) { echo "</b>"; } ?></TD>
				<TD align="right"><?php if($hsa[$idx] > 0.2) { echo "<b>"; } echo number_format($hsa[$idx], 4, '.', ','); if($hsa[$idx] > 0.2) { echo "</b>"; } ?></TD>
				<TD align="center"><A HREF="./combo_detail.php?id=<?php echo $idCombo_Design[$idx]; ?>&source=<?php echo $idSources[$idx]; ?>" TARGET="main"><img src="./imgs/source_icon_<?php echo $idSources[$idx]; ?>.png"></a></TD>
			</TR>
<?php
}
?>			
			<TR><TD colspan=9><HR></TD></TR>				
		</TABLE>
	</BODY>
</HTML>

<?php
mysqli_close($con);
?>
