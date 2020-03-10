<?php
	include './config.php';
	
	$idSource 	= array();
	$names	 	= array();
	$no_samples	= array();
	$no_drugs	= array();
	$pmIDs		= array();
	$authors	= array();
	$design		= array();
	
	$query = "SELECT * FROM Source ORDER BY no_samples desc, no_drugs desc";
	$result = $mysqli->query($query);
	if(!$result) { echo $mysqli->error;	}
	else { $source_counts = $result->num_rows; }

	if ($source_counts > 0) {
    	while($row = $result->fetch_assoc()) {
			array_push($idSource, $row['idSource']);
			array_push($names, $row['name']);
			array_push($no_samples, $row['no_samples']);
			array_push($no_drugs, $row['no_drugs']);
			array_push($pmIDs, isNull($row['pmID']));
			array_push($authors, isNull($row['author']));
			array_push($design, isNull($row['combo']));
		}
	}
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
		<META HTTP-EQUIV="CONTENT-Type" CONTENT="text/html" CHARSET="utf-8">
		<link rel="stylesheet" type="text/css" href="./resource/mystyle.css" />
		<TITLE> Drug Combo :: Sources </TITLE>
	</HEAD>
	<BODY>
		<FORM NAME="default">
			<h2>Datasets, <i>N</i>=<?php echo $source_counts-1; ?></h2>
			<!--h2>Databases, <i>N</i>=<?php echo $source_counts; ?></h2-->
			<TABLE WIDTH="800" BORDER="0" CELLPADDING="3" CELLSPACING="0" BORDERCOLOR="grey">
				<TR BGCOLOR="black"><TH><FONT COLOR="white">Name</FONT></TH><TH><FONT COLOR="white">Source</FONT></TH><TH><FONT COLOR="white">#Cell lines</FONT></TH><TH><FONT COLOR="white">#Drugs</FONT></TH><TH><FONT COLOR="white">Design</FONT></TH></TR>
<?php
	for($idx = 1 ; $idx < $source_counts ; ++$idx){
		$bgcolor = "d6d6d6";
		if($idx%2 == 0){
			$bgcolor = "white";
		}
?>
				<TR BGCOLOR="<?php echo $bgcolor; ?>">
					<TD><?php echo $names[$idx]; ?></TD>
					<TD align="left"><?php echo "<a href=\"https://www.ncbi.nlm.nih.gov/pubmed/".$pmIDs[$idx]."\" target=\"_blank\">".$authors[$idx]."</a>"; ?></TD>
					<TD align="right"><?php echo $no_samples[$idx]; ?></TD>
					<TD align="right"><?php echo $no_drugs[$idx]; ?></TD>
					<TD align="center"><?php echo $design[$idx]; ?></TD>
				</TR>
<?php
	}
?>
				<tr><td colspan=5><hr></td></tr>			
				<tr><td colspan=5>
					<b>Acknowledgement</b>: We would like to say the biggest thank you to the authors above for their efforts and data shraing.
				</td></tr>
			</TABLE>
			<br>
		</FORM>
	</BODY>
</HTML>

<?php
mysqli_close($con);
?>
