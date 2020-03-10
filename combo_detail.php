<?php
	include './config.php';
	$idCombo_Design = $_GET['id'];
	$idSource = $_GET['source'];

	if($idSource > 0) {
		$query = "select name, pmID from Source where idSource = ".$idSource.";";
		$res = $mysqli->query($query);
		$row = $res->fetch_assoc();
		$source_name = $row['name'];
		$source_pmid = $row['pmID'];
	}

	if(!empty($idCombo_Design) && !empty($idSource) && !empty($source_name)){
		$query = "select idSample, idDrugA, idDrugB from Combo_Design where idCombo_Design = ".$idCombo_Design.";";
		$result = $mysqli->query($query);
		if(!empty($result)) {
			$row = $result->fetch_assoc();
			$idSample = $row['idSample'];
			$idDrugA = $row['idDrugA'];
			$idDrugB = $row['idDrugB'];

			$query = "select name, idCellosaurus, disease, origin from Sample where idSample = ".$idSample.";";
			$result1 = $mysqli->query($query);
			$row = $result1->fetch_assoc();
			$cell_name = $row['name'];
			$cell_idCellosaurus = $row['idCellosaurus'];
			$cell_disease = $row['disease'];
			$cell_origin = $row['origin'];

			$query = "select name, atcCode, idDrugBank, idPubChem, description from Drug where idDrug = ".$idDrugA.";";
			$result2 = $mysqli->query($query);
			$row = $result2->fetch_assoc();
			$drugA_name = $row['name'];
			$drugA_atcCode = $row['atcCode'];
			$drugA_idDrugBank = $row['idDrugBank'];
			$drugA_idPubChem = $row['idPubChem'];
			$drugA_description = $row['description'];

			$query = "select name, atcCode, idDrugBank, idPubChem, description from Drug where idDrug = ".$idDrugB.";";
			$result3 = $mysqli->query($query);
			$row = $result3->fetch_assoc();
			$drugB_name = $row['name'];
			$drugB_atcCode = $row['atcCode'];
			$drugB_idDrugBank = $row['idDrugBank'];
			$drugB_idPubChem = $row['idPubChem'];
			$drugB_description = $row['description'];

			$query = "select S.name as name, D.name as drug, MS.aac, MS.ic50, MS.ec50 from (( Mono_Summary as MS inner join Sample as S on MS.idSample = S.idSample) inner join Drug as D on MS.idDrug = D.idDrug) where MS.idSample = ".$idSample." and MS.idDrug = ".$idDrugA.";";
			$result7 = $mysqli->query($query);
			$row = $result7->fetch_assoc();
			$drugA_aac = $row['aac'];
			$drugA_ic50 = $row['ic50'];
			$drugA_ec50 = $row['ec50'];

			$query = "select S.name as name, D.name as drug, MS.aac, MS.ic50, MS.ec50 from (( Mono_Summary as MS inner join Sample as S on MS.idSample = S.idSample) inner join Drug as D on MS.idDrug = D.idDrug) where MS.idSample = ".$idSample." and MS.idDrug = ".$idDrugB.";";
			$result8 = $mysqli->query($query);
			$row = $result8->fetch_assoc();
			$drugB_aac = $row['aac'];
			$drugB_ic50 = $row['ic50'];
			$drugB_ec50 = $row['ec50'];

			$query = "select bliss, loewe, hsa, zip from Synergy_Score where idCombo_Design = ".$idCombo_Design." and idSource = ".$idSource.";";
			$result5 = $mysqli->query($query);
			$row = $result5->fetch_assoc();
			$bliss = $row['bliss'];
			$loewe = $row['loewe'];
			$hsa = $row['hsa'];
			$zip = $row['zip'];

			$concA = array();
			$concB = array();
			$raw_matrix = array();
			$bliss_matrix = array();
			$loewe_matrix = array();
			$hsa_matrix = array();
			$zip_matrix = array();

			$query = "select concA, concB, raw_matrix, bliss_matrix, loewe_matrix, hsa_matrix, zip_matrix from Combo_Matrix where idCombo_Design = ".$idCombo_Design." and idSource = ".$idSource.";";
			$result6 = $mysqli->query($query);
			$matrix_cnt = $result6->num_rows;
			while($row = $result6->fetch_assoc()) {
				array_push($concA, $row['concA']);
				array_push($concB, $row['concB']);
				array_push($raw_matrix, $row['raw_matrix']);
				array_push($bliss_matrix, $row['bliss_matrix']);
				array_push($loewe_matrix, $row['loewe_matrix']);
				array_push($hsa_matrix, $row['hsa_matrix']);
				array_push($zip_matrix, $row['zip_matrix']);
			}
			$concA_uniq = array_unique($concA, SORT_REGULAR);
			$concB_uniq = array_unique($concB, SORT_REGULAR);
			$nrow = sizeof($concA_uniq);
			$ncol = sizeof($concB_uniq);
		}
	} else {
		echo("ERROR");
	}
?>
<HTML>
	<HEAD>
		<META HTTP-EQUIV="CONTENT-Type" CONTENT="text/html" CHARSET="utf-8">
		<link rel="stylesheet" type="text/css" href="./resource/mystyle.css" />
		<TITLE> Drug Combo :: Detail </TITLE>
	</HEAD>
	<BODY TOPMARGIN="20">
		<h2>Drug combination synergy</h2>
		<ul>
			<li>Sample: <b><?php echo $cell_name; ?></b><?php if(!empty($cell_disease)) { echo ", ".$cell_disease; } ?><?php if(!empty($cell_origin)) { echo ", ".$cell_origin; } ?>
<?php if(!empty($cell_idCellosaurus)) { ?>
				<A HREF="https://web.expasy.org/cellosaurus/<?php echo $cell_idCellosaurus; ?>" target="_blank"><img src="./imgs/cellosaurus_icon.png"></A>
<?php } ?>
			</li>
			<li>Drug<sub>A</sub> : <b><?php echo $drugA_name; ?></b><?php if(!empty($drugA_description)) { echo ", ".$drugA_description; } ?>
<?php if(!empty($drugA_idPubChem)) { ?>
				<A HREF="https://pubchem.ncbi.nlm.nih.gov/compound/<?php echo $drugA_idPubChem; ?>" target="_blank"><img src="./imgs/pubchem_icon.png"></A>
<?php } ?>
<?php if(!empty($drugA_idDrugBank)) { ?>
				<A HREF="https://www.drugbank.ca/drugs/<?php echo $drugA_idDrugBank; ?>" target="_blank"><img src="./imgs/drugbank_icon.png"></A>
<?php } ?>
			</li>
			<li>Drug<sub>B</sub> : <b><?php echo $drugB_name; ?></b><?php if(!empty($drugB_description)) { echo ", ".$drugB_description; } ?>			
<?php if(!empty($drugB_idPubChem)) { ?>
				<A HREF="https://pubchem.ncbi.nlm.nih.gov/compound/<?php echo $drugB_idPubChem; ?>" target="_blank"><img src="./imgs/pubchem_icon.png"></A>
<?php } ?>
<?php if(!empty($drugB_idDrugBank)) { ?>
				<A HREF="https://www.drugbank.ca/drugs/<?php echo $drugB_idDrugBank; ?>" target="_blank"><img src="./imgs/drugbank_icon.png"></A>
<?php } ?>
			</li>
			<li>Source: <b><?php echo $source_name; ?></b> <a href="https://www.ncbi.nlm.nih.gov/pubmed/<?php echo $source_pmid; ?>" target="_blank"><img src="./imgs/medline_icon.png"></a>
			</li>
			<br>
			<li>Synergy scores (rank)<br>
			<table border=0>
				<tr align="left">
					<td valign="top">
						<a href="./imgs/ecdf_rank/<?php echo $idSource."/".$idSample."/".$idSample."__".$idDrugA."__".$idDrugB; ?>.png" target="_blank">
						<img src="./imgs/ecdf_rank/<?php echo $idSource."/".$idSample."/Thumb/".$idSample."__".$idDrugA."__".$idDrugB; ?>_400.png" width=400></a>
					</td>
					<td valign="bottom">
						<table>
							<tr><td>&#9702; Bliss</td><td>: <?php if($bliss != "") { echo $bliss; } else { echo "NA"; } ?></td></tr>
							<tr><td>&#9702; Loewe</td><td>: <?php if($loewe != "") { echo $loewe; } else { echo "NA"; } ?></td></tr>
							<tr><td>&#9702; HSA</td><td>: <?php if($hsa != "") { echo $hsa; } else { echo "NA"; }  ?></td></tr>
							<tr><td>&#9702; ZIP</td><td>: <?php if($zip != "") { echo $zip; } else { echo "NA"; }  ?></td></tr>
						</table>
					</td>
				</tr>
			</table>						
		</ul>
		<br>
		<H3>Synergy matrices</h3>
		<DIV CLASS="tab">
			<button class="tablinks" onclick="openSynergy(event, 'raw')" id="defaultOpen">Input</button>
			<button class="tablinks" onclick="openSynergy(event, 'bliss')">Bliss</button>
			<button class="tablinks" onclick="openSynergy(event, 'loewe')">Loewe</button>
			<button class="tablinks" onclick="openSynergy(event, 'hsa')">HSA</button>
			<button class="tablinks" onclick="openSynergy(event, 'zip')">ZIP</button>
		</DIV>
<!-- Raw matrix -->
		<DIV ID="raw" class="tabcontent">
			<TABLE WIDTH="775" BORDER="0" CELLPADDING="3" CELLSPACING="0">
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cell_name; ?></FONT></TH>
					<TH COLSPAN=<?php echo $ncol; ?>><FONT COLOR="white"><?php echo $drugB_name; ?></FONT></TH>
				</TR>
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $drugA_name; ?></FONT></TH>
<?php 
	foreach($concB_uniq as $cB) {
?>
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cB." &#181;M"; ?></FONT></TH>
<?php
	}
?>
				</TR>
<?php
	for($idx = 0 ; $idx < $matrix_cnt ; ++$idx){
		$bgcolor = "d6d6d6";
		if($idx%2 == 0){
			$bgcolor = "white";
		}
?>
<?php
		if($idx%$nrow == 0) {
?>
				<TR BGCOLOR="<?php echo $bgcolor; ?>">
				<TD  WIDTH=155 BGCOLOR="black" ALIGN="center"><FONT COLOR="white"><B><?php echo $concA[$idx]." &#181;M"; ?></B></FONT></TD>
<?php
		}
?>
					<TD  WIDTH=155 ALIGN="center"><?php echo number_format($raw_matrix[$idx], 4, '.', ','); ?></TD>
<?php
		if($idx%$nrow == $nrow) {
?>
				</TR>
<?php
		}
?>
<?php
	}
?>
			</TABLE>
		</DIV>
		
<!-- Bliss matrix -->
		<DIV ID="bliss" class="tabcontent">
			<TABLE WIDTH="775" BORDER="0" CELLPADDING="3" CELLSPACING="0">
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cell_name; ?></FONT></TH>
					<TH WIDTH=155 COLSPAN=<?php echo $ncol; ?>><FONT COLOR="white"><?php echo $drugB_name; ?></FONT></TH>
				</TR>
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $drugA_name; ?></FONT></TH>
<?php 
	foreach($concB_uniq as $cB) {
?>
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cB." &#181;M"; ?></FONT></TH>
<?php
	}
?>
				</TR>
<?php
	for($idx = 0 ; $idx < $matrix_cnt ; ++$idx){
		$bgcolor = "d6d6d6";
		if($idx%2 == 0){
			$bgcolor = "white";
		}
?>
<?php
		if($idx%$nrow == 0) {
?>
				<TR BGCOLOR="<?php echo $bgcolor; ?>">
				<TD WIDTH=155 BGCOLOR="black" ALIGN="center"><FONT COLOR="white"><B><?php echo $concA[$idx]." &#181;M"; ?></B></FONT></TD>
<?php
		}
?>
					<TD WIDTH=155 ALIGN="center"><?php echo number_format($bliss_matrix[$idx], 4, '.', ','); ?></TD>
<?php
		if($idx%$nrow == $nrow) {
?>
				</TR>
<?php
		}
?>
<?php
	}
?>
			</TABLE>
			<a href="./imgs/three_d/<?php echo $idSource."/".$idSample."/".$idSample."__".$idDrugA."__".$idDrugB; ?>__Bliss.png" target="_blank">
			<img src="./imgs/three_d/<?php echo $idSource."/".$idSample."/Thumb/".$idSample."__".$idDrugA."__".$idDrugB; ?>__Bliss.png" width=775></a>
		</DIV>
		
<!-- Loewe matrix -->
		<DIV ID="loewe" class="tabcontent">
			<TABLE WIDTH="775" BORDER="0" CELLPADDING="3" CELLSPACING="0">
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cell_name; ?></FONT></TH>
					<TH COLSPAN=<?php echo $ncol; ?>><FONT COLOR="white"><?php echo $drugB_name; ?></FONT></TH>
				</TR>
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $drugA_name; ?></FONT></TH>
<?php 
	foreach($concB_uniq as $cB) {
?>
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cB." &#181;M"; ?></FONT></TH>
<?php
	}
?>
				</TR>
<?php
	for($idx = 0 ; $idx < $matrix_cnt ; ++$idx){
		$bgcolor = "d6d6d6";
		if($idx%2 == 0){
			$bgcolor = "white";
		}
?>
<?php
		if($idx%$nrow == 0) {
?>
				<TR BGCOLOR="<?php echo $bgcolor; ?>">
				<TD WIDTH=155 BGCOLOR="black" ALIGN="center"><FONT COLOR="white"><B><?php echo $concA[$idx]." &#181;M"; ?></B></FONT></TD>
<?php
		}
?>
					<TD WIDTH=155 ALIGN="center"><?php echo number_format($loewe_matrix[$idx], 4, '.', ','); ?></TD>
<?php
		if($idx%$nrow == $nrow) {
?>
				</TR>
<?php
		}
?>
<?php
	}
?>
			</TABLE>
			<a href="./imgs/three_d/<?php echo $idSource."/".$idSample."/".$idSample."__".$idDrugA."__".$idDrugB; ?>__Loewe.png" target="_blank">
			<img src="./imgs/three_d/<?php echo $idSource."/".$idSample."/Thumb/".$idSample."__".$idDrugA."__".$idDrugB; ?>__Loewe.png" width=775></a>
		</DIV>

<!-- HSA matrix -->
		<DIV ID="hsa" class="tabcontent">
			<TABLE WIDTH="775" BORDER="0" CELLPADDING="3" CELLSPACING="0">
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cell_name; ?></FONT></TH>
					<TH COLSPAN=<?php echo $ncol; ?>><FONT COLOR="white"><?php echo $drugB_name; ?></FONT></TH>
				</TR>
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $drugA_name; ?></FONT></TH>
<?php 
	foreach($concB_uniq as $cB) {
?>
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cB." &#181;M"; ?></FONT></TH>
<?php
	}
?>
				</TR>
<?php
	for($idx = 0 ; $idx < $matrix_cnt ; ++$idx){
		$bgcolor = "d6d6d6";
		if($idx%2 == 0){
			$bgcolor = "white";
		}
?>
<?php
		if($idx%$nrow == 0) {
?>
				<TR BGCOLOR="<?php echo $bgcolor; ?>">
				<TD WIDTH=155 BGCOLOR="black" ALIGN="center"><FONT COLOR="white"><B><?php echo $concA[$idx]." &#181;M"; ?></B></FONT></TD>
<?php
		}
?>
					<TD WIDTH=155 ALIGN="center"><?php echo number_format($hsa_matrix[$idx], 4, '.', ','); ?></TD>
<?php
		if($idx%$nrow == $nrow) {
?>
				</TR>
<?php
		}
?>
<?php
	}
?>
			</TABLE>
			<a href="./imgs/three_d/<?php echo $idSource."/".$idSample."/".$idSample."__".$idDrugA."__".$idDrugB; ?>__HSA.png" target="_blank">
			<img src="./imgs/three_d/<?php echo $idSource."/".$idSample."/Thumb/".$idSample."__".$idDrugA."__".$idDrugB; ?>__HSA.png" width=775></a>
		</DIV>

<!-- ZIP matrix -->
		<DIV ID="zip" class="tabcontent">
			<TABLE WIDTH="775" BORDER="0" CELLPADDING="3" CELLSPACING="0">
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cell_name; ?></FONT></TH>
					<TH WIDTH=155 COLSPAN=<?php echo $ncol; ?>><FONT COLOR="white"><?php echo $drugB_name; ?></FONT></TH>
				</TR>
				<TR BGCOLOR="black">
					<TH WIDTH=155><FONT COLOR="white"><?php echo $drugA_name; ?></FONT></TH>
<?php 
	foreach($concB_uniq as $cB) {
?>
					<TH WIDTH=155><FONT COLOR="white"><?php echo $cB." &#181;M"; ?></FONT></TH>
<?php
	}
?>
				</TR>
<?php
	for($idx = 0 ; $idx < $matrix_cnt ; ++$idx){
		$bgcolor = "d6d6d6";
		if($idx%2 == 0){
			$bgcolor = "white";
		}
?>
<?php
		if($idx%$nrow == 0) {
?>
				<TR BGCOLOR="<?php echo $bgcolor; ?>">
				<TD WIDTH=155 BGCOLOR="black" ALIGN="center"><FONT COLOR="white"><B><?php echo $concA[$idx]." &#181;M"; ?></B></FONT></TD>
<?php
		}
?>
					<TD WIDTH=155 ALIGN="center"><?php echo number_format($zip_matrix[$idx], 4, '.', ','); ?></TD>
<?php
		if($idx%$nrow == $nrow) {
?>
				</TR>
<?php
		}
?>
<?php
	}
?>
			</TABLE>
			<a href="./imgs/three_d/<?php echo $idSource."/".$idSample."/".$idSample."__".$idDrugA."__".$idDrugB; ?>__ZIP.png" target="_blank">
			<img src="./imgs/three_d/<?php echo $idSource."/".$idSample."/Thumb/".$idSample."__".$idDrugA."__".$idDrugB; ?>__ZIP.png" width=775></a>
		</DIV>
		<script>
			function openSynergy(evt, synergy) {
				// Declare all variables
				var i, tabcontent, tablinks;

				// Get all elements with class="tabcontent" and hide them
				tabcontent = document.getElementsByClassName("tabcontent");
				for (i = 0; i < tabcontent.length; i++) {
					tabcontent[i].style.display = "none";
				}

				// Get all elements with class="tablinks" and remove the class "active"
				tablinks = document.getElementsByClassName("tablinks");
				for (i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" active", "");
				}

				// Show the current tab, and add an "active" class to the button that opened the tab
				document.getElementById(synergy).style.display = "block";
				evt.currentTarget.className += " active";
			}
			
			// Get the element with id="defaultOpen" and click on it
			document.getElementById("defaultOpen").click();
		</script>

		<br>
		<h3>Synergistic inhibition</h3>
		<a href="./imgs/heatmap/<?php echo $idSource."/".$idSample."/".$idSample."__".$idDrugA."__".$idDrugB.".png"; ?>" target="_blank"><img src="./imgs/heatmap/<?php echo $idSource."/".$idSample."/Thumb/".$idSample."__".$idDrugA."__".$idDrugB.".png"; ?>" width=800></a>		

		<br>		
		<H3>Single-agents</h3>
		<table border=0 width=640>
			<tr><td>
				<a href="./imgs/mono/<?php echo $idSource."/".$idSample."__".$idDrugA.".png"; ?>" target="_blank"><img src="./imgs/mono/<?php echo $idSource."/Thumb/".$idSample."__".$idDrugA.".png"; ?>"></a>
			</td><td>
				<a href="./imgs/mono/<?php echo $idSource."/".$idSample."__".$idDrugB.".png"; ?>" target="_blank"><img src="./imgs/mono/<?php echo $idSource."/Thumb/".$idSample."__".$idDrugB.".png"; ?>"></a>			
			</td></tr>
			<tr align="center"><td colspan=2>
				<table>
					<tr bgcolor="black"><th width=100><font color="white"><?php echo $cell_name; ?></font></th><th width=100><font color="white">AAC</font></th><th width=100><font color="white">IC50(&#181;M)</font></th><th width=100><font color="white">EC50(&#181;M)</font></th></tr>
					<tr align="center"><td><?php echo $drugA_name; ?></td><td><?php echo number_format($drugA_aac, 4, '.', ','); ?></td><td><?php echo number_format($drugA_ic50, 4, '.', ','); ?></td><td><?php echo number_format($drugA_ec50, 4, '.', ','); ?></td></tr>
					<tr align="center"><td><?php echo $drugB_name; ?></td><td><?php echo number_format($drugB_aac, 4, '.', ','); ?></td><td><?php echo number_format($drugB_ic50, 4, '.', ','); ?></td><td><?php echo number_format($drugB_ec50, 4, '.', ','); ?></td></tr>
					<tr><td colspan=4><hr></td></tr>
				</table>
			</td></tr>
		</table>
		
		<br><br><br><br>
	</BODY>
</HTML>

<?php
	mysqli_close($con);
?>
