<?php
	include './config.php';
	
	$sample_names 	= array();
	$sample_ids 	= array();
	$sample_sex		= array();
	$sample_ages	= array();
	$sample_disease	= array();
	$sample_origin	= array();
	$sample_tissue  = array();
	
	$query = "SELECT * FROM Sample order by tissue, name";
	$result = $mysqli->query($query);
	if(!$result) { echo $mysqli->error;	}
	else { $sample_counts = $result->num_rows; }

	if ($sample_counts > 0) {
    	while($row = $result->fetch_assoc()) {
			array_push($sample_names, $row['name']);
			array_push($sample_ids, $row['idCellosaurus']);
			array_push($sample_sex, isNull($row['sex']));
			array_push($sample_ages, isNull($row['age']));
			array_push($sample_disease, isNull($row['disease']));
			if($row['origin'] != ""){
				array_push($sample_origin, "(<i>metastasis</i>)");
			} else {
				array_push($sample_origin, "");
			}
			array_push($sample_tissue, isNull($row['tissue']));
		}
	}
?>

<HTML>
	<HEAD>
		<META HTTP-EQUIV="CONTENT-Type" CONTENT="text/html" CHARSET="utf-8">
		<link rel="stylesheet" type="text/css" href="./resource/mystyle.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
		<TITLE> Drug Combo :: Sample profiles </TITLE>
	</HEAD>
	<BODY>
		<FORM NAME="default">
			<h2>Cell lines, <i>N</i>=<?php echo $sample_counts; ?></h2>
			<TABLE WIDTH="800" BORDER="0" CELLPADDING="3" CELLSPACING="0">
				<TR>
					<TH width="50" BGCOLOR="black">&nbsp;</TH>
					<TH width="300" BGCOLOR="black"><FONT COLOR="white">Age</FONT></TH>
					<TH width="50" BGCOLOR="black">&nbsp;</TH>
					<TH width="50" BGCOLOR="black">&nbsp;</TH>
					<TH width="300" BGCOLOR="black"><FONT COLOR="white">Sex</FONT></TH>
					<TH width="50" BGCOLOR="black">&nbsp;</TH>
				</TR>
				<TR>
					<td width=50>&nbsp;</td>
					<td width="300"><canvas id="pie_age" width="300" height="300"></canvas></td>
					<td width=50>&nbsp;</td>
					<td width=50>&nbsp;</td>
					<td width="300"><canvas id="pie_sex" width="300" height="300"></canvas></td>
					<td width=50>&nbsp;</td>
				</TR>
				<TR>
					<TH width="50" BGCOLOR="black">&nbsp;</TH>
					<TH width="300" BGCOLOR="black"><FONT COLOR="white">Tissues</FONT></TH>
					<TH width="50" BGCOLOR="black">&nbsp;</TH>
					<TH width="50" BGCOLOR="black">&nbsp;</TH>
					<TH width="300" BGCOLOR="black"><FONT COLOR="white">Biopsy</FONT></TH>
					<TH width="50" BGCOLOR="black">&nbsp;</TH>
				</TR>
				<TR>
					<td width=50>&nbsp;</td>
					<td width="300"><canvas id="pie_tissues" width="300" height="300"></canvas></td>
					<td width=50>&nbsp;</td>
					<td width=50>&nbsp;</td>
					<td width="300"><canvas id="pie_biopsy" width="300" height="300"></canvas></td>
					<td width=50>&nbsp;</td>
				</TR>
			</TABLE>
			<script>
				Chart.defaults.global.defaultFontStyle = 'Bold';
				var age = document.getElementById("pie_age");
				var sex = document.getElementById("pie_sex");
				var tissues = document.getElementById("pie_tissues");
				var biopsy = document.getElementById("pie_biopsy");

				var myChart = new Chart(age, {
					type: 'pie',
					data: {
						labels: ["> 50", "<= 50", "Unknown"],
						datasets: [{
							label: 'Age',
							data: [92, 53, 41],
							backgroundColor: [
								'#4D4D4D',
								'#7F7F7F',
								'#E5E5E5'
							],
							borderColor: [
								'#4D4D4D',
								'#7F7F7F',
								'#E5E5E5'
							],
							borderWidth: 1
						}]
					},
				});
				
				var myChart = new Chart(sex, {
					type: 'pie',
					data: {
						labels: ["Female", "Male", "Unknown"],
						datasets: [{
							label: 'Sex',
							data: [86, 83, 17],
							backgroundColor: [
								'#4D4D4D',
								'#7F7F7F',
								'#E5E5E5'
							],
							borderColor: [
								'#4D4D4D',
								'#7F7F7F',
								'#E5E5E5'
							],
							borderWidth: 1
						}]
					},
				});
				
				var myChart = new Chart(tissues, {
					type: 'pie',
					data: {
						labels: ["Skin","Breast","Lung","Colorectal","Ovary","Bladder","Kidney","Blood","Brain","Prostate","Pancreas","Mesothelioma","Stomach"],
						datasets: [{
							label: 'Tissues',
							data: [40,38,30,16,14,12,8,8,7,5,4,2,2],
							backgroundColor: [
								'#000000',
								'#303030',
								'#3C3C3C',
								'#505050',
								'#646464',
								'#787878',
								'#8C8C8C',
								'#A0A0A0',
								'#B4B4B4',
								'#C8C8C8',
								'#DCDCDC',
								'#EEEEEE',
								'#FFFFFF'

							],
							borderColor: [
								'#000000',
								'#282828',
								'#3C3C3C',
								'#505050',
								'#646464',
								'#787878',
								'#8C8C8C',
								'#A0A0A0',
								'#B4B4B4',
								'#C8C8C8',
								'#DCDCDC',
								'#DCDCDC',
								'#DCDCDC'
							],
							borderWidth: 1
						}]
					},
				});
				
				var myChart = new Chart(biopsy, {
					type: 'pie',
					data: {
						labels: ["Primary","Metastasis"],
						datasets: [{
							label: 'Biopsy',
							data: [123, 63],
							backgroundColor: [
								'#4D4D4D',
								'#7F7F7F'
							],
							borderColor: [
								'#4D4D4D',
								'#7F7F7F'
							],
							borderWidth: 1
						}]
					},
				});
			</script>
			<br><br>
			<TABLE WIDTH="800" BORDER="0" CELLPADDING="3" CELLSPACING="0" BORDERCOLOR="grey">
				<TR BGCOLOR="black"><TH><FONT COLOR="white">Name</FONT></TH><TH><FONT COLOR="white">Tissue</FONT></TH><TH><FONT COLOR="white">Sex</FONT></TH><TH><FONT COLOR="white">Age</TH><TH><FONT COLOR="white">Disease</FONT></TH><TH><FONT COLOR="white">Cellosaurus</FONT></TH></TR>
<?php
	for($idx = 0 ; $idx < $sample_counts ; ++$idx){
		$bgcolor = "d6d6d6";
		if($idx%2 == 0){
			$bgcolor = "white";
		}
?>
				<TR BGCOLOR="<?php echo $bgcolor; ?>">
					<TD align="left"><?php echo $sample_names[$idx]; ?></TD>
					<TD align="left"><?php echo $sample_tissue[$idx]; ?></TD>
					<TD align="center"><?php echo $sample_sex[$idx]; ?></TD>
					<TD align="right"><?php echo $sample_ages[$idx]; ?></TD>
					<TD><?php echo $sample_disease[$idx]." ".$sample_origin[$idx]; ?> </TD>
					<TD><A HREF="https://web.expasy.org/cellosaurus/<?php echo $sample_ids[$idx]; ?>" target="_blank"><?php echo $sample_ids[$idx]; ?></A></TD>
				</TR>
<?php
	}
?>	
				<tr><td colspan=6><hr></td></tr>			
			</TABLE>
		</FORM>
	</BODY>
</HTML>

<?php
mysqli_close($con);
?>
