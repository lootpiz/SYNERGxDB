<?php
include './config.php';

$idSource = $_GET['source'];
$idDrugA = $_GET['drugA'];
$idDrugB = $_GET['drugB'];

$anova_sources = array();
$anova_drugas = array();
$anova_drugbs = array();
$anova_genes = array();
$anova_ps = array();
$anova_na_genes =array();
$anova_sig_counts = 0;
$anova_viz_counts = 0;

$query = "SELECT idSource, idDrugA, idDrugB, gene, p from anova where ";
if($idSource != 0) { $query = $query." idSource = ".$idSource." and"; }
if($idDrugA != 0 && $idDrugB != 0) { $query = $query."( idDrugA = ".$idDrugA." and idDrugB = ".$idDrugB. " ) "; } 
else if($idDrugA == 0 && $idDrugB != 0) { $query = $query."( idDrugA = ".$idDrugB. " or idDrugB = ".$idDrugB. " ) "; }
$query = $query." order by p IS NULL, p, gene";

$anova_result = $mysqli->query($query);
if(!$anova_result) { echo $mysqli->error;     }
else { $anova_sig_counts += $anova_result->num_rows; }

if ($anova_result->num_rows > 0) {
	while($anova_row = $anova_result->fetch_assoc()) {
		if($anova_row['p'] == "") { 
			array_push($anova_na_genes, $anova_row['gene']);
		} else {
			$anova_viz_counts = $anova_viz_counts + 1;
			
			array_push($anova_drugas, $anova_row['idDrugA']);
			array_push($anova_drugbs, $anova_row['idDrugB']);			
			array_push($anova_sources, isNull($anova_row['idSource']));
			array_push($anova_genes, isNull($anova_row['gene']));
			if($anova_row['p'] < 0.0001){
				array_push($anova_ps, sprintf('%.2E', $anova_row['p']));
			} else {
				array_push($anova_ps, sprintf('%.4f', $anova_row['p']));
			}
		}
	}
}
?>
<HTML>
	<HEAD>
		<META HTTP-EQUIV="CONTENT-Type" CONTENT="text/html" CHARSET="utf-8">
		<link rel="stylesheet" type="text/css" href="./resource/mystyle.css" />
		<TITLE> ANOVA results </TITLE>
	</HEAD>
	<BODY TOPMARGIN="20">
		<h2>Association between gene expression and synergy scores of the ZIP-based model</h2>
<?php
if($anova_sig_counts > 0){
?>
		<TABLE WIDTH="800" BORDER="0" CELLPADDING="3" CELLSPACING="0">
			<TR BGCOLOR="black">
				<TH><FONT COLOR="white">Rank</FONT></TH> 
				<TH><FONT COLOR="white">Gene Symbol</FONT></TH> 
				<TH><FONT COLOR="white">One-way ANOVA <i>P</i></FONT></TH> 
				<TH><FONT COLOR="white">Source</FONT></TH> 
			</TR>
<?php
for($idx2 = 0 ; $idx2 < $anova_viz_counts ; ++$idx2) {
?>
			<TR>
				<TD align="right"><?php echo $idx2 + 1; ?></td>
				<TD><i><?php echo $anova_genes[$idx2]; ?></i></TD>
				<TD align="center"><?php echo $anova_ps[$idx2]; ?></TD>
<?php
if($anova_ps[$idx2] < 0.05){
?>
				<TD align="center"><a href="./imgs/stats/<?php echo $anova_sources[$idx2]."/".$anova_genes[$idx2]."/".$anova_sources[$idx2]."__".$anova_drugas[$idx2]."__".$anova_drugbs[$idx2]."__".$anova_genes[$idx2]; ?>.png" target="_blank"><img src="./imgs/source_icon_<?php echo $anova_sources[$idx2]; ?>.png"></a></TD>	
<?php
} else {
?>
				<TD align="center"><img src="./imgs/source_icon_<?php echo $anova_sources[$idx2]; ?>.png"></TD>	
<?php
}
?>
			</TR>
<?php
}
?>
			<TR><TD colspan=4><HR></TD></TR>
		</TABLE>
<?php
}
?>
	The number of protein-coding genes with N/A: <b><?php echo count($anova_na_genes); ?></b>
	</BODY>
</HTML>

<?php
mysqli_close($con);
?>
