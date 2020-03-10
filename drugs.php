<?php
	include './config.php';
	
	$drug_names 	= array();
	$atc_codes	 	= array();
	$drugbank_ids	= array();
	$pubchem_ids	= array();
	$descriptions	= array();
	
	$query = "SELECT * FROM Drug ORDER BY idPubChem IS NULL, atcCode IS NULL, idDrugBank IS NULL, name";
	$result = $mysqli->query($query);
	if(!$result) { echo $mysqli->error;	}
	else { $drug_counts = $result->num_rows; }

	if ($drug_counts > 0) {
    	while($row = $result->fetch_assoc()) {
			array_push($drug_names, $row['name']);
			$atc_code = $row['atcCode']; 
			if(strpos($atc_code, ';')) {
				$atc_code = explode(";", $atc_code)[0].", ...";
			}
			array_push($atc_codes, $atc_code);
			array_push($drugbank_ids, isNull($row['idDrugBank']));
			array_push($pubchem_ids, isNull($row['idPubChem']));
			array_push($descriptions, isNull($row['description']));
		}
	}
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
		<META HTTP-EQUIV="CONTENT-Type" CONTENT="text/html" CHARSET="utf-8">
		<link rel="stylesheet" type="text/css" href="./resource/mystyle.css" />
		<style>
			ul, #myUL {
			  list-style-type: none;
			}

			#myUL {
			  margin: 0;
			  padding: 0;
			}

			.caret {
			  cursor: pointer;
			  -webkit-user-select: none; /* Safari 3.1+ */
			  -moz-user-select: none; /* Firefox 2+ */
			  -ms-user-select: none; /* IE 10+ */
			  user-select: none;
			}

			.caret::before {
			  content: "\25B6";
			  color: black;
			  display: inline-block;
			  margin-right: 6px;
			}

			.caret-down::before {
			  -ms-transform: rotate(90deg); /* IE 9 */
			  -webkit-transform: rotate(90deg); /* Safari */'
			  transform: rotate(90deg);  
			}

			.nested {
			  display: none;
			}

			.active {
			  display: block;
			}
		</style>
		<TITLE> Drug Combo :: Drug profiles </TITLE>
	</HEAD>
	<BODY>
		<FORM NAME="default">
			<h2>Anatomical Therapeutic Chemical (ATC) Classification System</h2>
			<ul id="myUL">
				<li><span class="caret"><b>[A]</b> ALIMENTARY TRACT AND METABOLISM</span>
					<ul class="nested">
						<li><span class="caret"><b>[A01]</b> STOMATOLOGICAL PREPARATIONS</span>
							<ul class="nested">
								<li><span class="caret"><b>[A01A]</b> STOMATOLOGICAL PREPARATIONS</span>
									<ul class="nested">
										<li><span class="caret"><b>[A01AC]</b> CORTICOSTEROIDS FOR LOCAL ORAL TREATMENT</span>
											<ul class="nested">
												<li><b>[A01AC02] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								<li><span class="caret"><b>[A10B]</b> BLOOD GLUCOSE LOWERING DRUGS, EXCL. INSULINS</span>
									<ul class="nested">
										<li><span class="caret"><b>[A10BA]</b> BIGUANIDES</span>
											<ul class="nested">
												<li><b>[A10BA02] METFORMIN</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[C]</b> CARDIOVASCULAR SYSTEM</span>
					<ul class="nested">
						<li><span class="caret"><b>[C05]</b> VASOPROTECTIVES</span>
							<ul class="nested">
								<li><span class="caret"><b>[C05A]</b> AGENTS FOR TREATMENT OF HEMORRHOIDS AND ANAL FISSURES FOR TOPICAL USE</span>
									<ul class="nested">
										<li><span class="caret"><b>[C05AA]</b> CORTICOSTEROIDS</span>
											<ul class="nested">	
												<li><b>[C05AA09] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[C10]</b> LIPID MODIFYING AGENTS</span>
							<ul class="nested">
								<li><span class="caret"><b>[C10A]</b> LIPID MODIFYING AGENTS, PLAIN</span>
									<ul class="nested">
										<li><span class="caret"><b>[C10AA]</b> HMG CoA reductase inhibitors</span>
											<ul class="nested">	
												<li><b>[C10AA01] SIMVASTATIN</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[D]</b> DERMATOLOGICALS</span>
					<ul class="nested">
						<li><span class="caret"><b>[D05]</b> ANTIPSORIATICS</span>
							<ul class="nested">
								<li><span class="caret"><b>[D05A]</b> ANTIPSORIATICS FOR TOPICAL USE</span>
									<ul class="nested">
										<li><span class="caret"><b>[D05AD]</b> PSORALENS FOR TOPICAL USE</span>		
											<ul class="nested">
												<li><b>[D05AD02] METHOXSALEN</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[D05B]</b> ANTIPSORIATICS FOR SYSTEMIC USE</span>
									<ul class="nested">
										<li><span class="caret"><b>[D05BA]</b> PSORALENS FOR SYSTEMIC USE</span>
											<ul class="nested">
												<li><b>[D05BA02] METHOXSALEN</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[D06]</b> ANTIBIOTICS AND CHEMOTHERAPEUTICS FOR DERMATOLOGICAL USE</span>
							<ul class="nested">
								<li><span class="caret"><b>[D06B]</b> CHEMOTHERAPEUTICS FOR TOPICAL USE</span>
									<ul class="nested">
										<li><span class="caret"><b>[D06BB]</b> ANTIVIRALS</span>
											<ul class="nested">
												<li><b>[D06BB10] IMIQUIMOD</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[D07]</b> CORTICOSTEROIDS, DERMATOLOGICAL PREPARATIONS</span>
							<ul class="nested">
								<li><span class="caret"><b>[D07A]</b> CORTICOSTEROIDS, PLAIN</span>
									<ul class="nested">
										<li><span class="caret"><b>[D07AB]</b> CORTICOSTEROIDS, MODERATELY POTENT (GROUP II)</span>
											<ul class="nested">
												<li><b>[D07AB19] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[D07X]</b> CORTICOSTEROIDS, OTHER COMBINATIONS</span>
									<ul class="nested">
										<li><span class="caret"><b>[D07XB]</b> CORTICOSTEROIDS, MODERATELY POTENT, OTHER COMBINATIONS</span>
											<ul class="nested">
												<li><b>[D07XB05] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[D10]</b> ANTI-ACNE PREPARATIONS</span>
							<ul class="nested">
								<li><span class="caret"><b>[D10A]</b> ANTI-ACNE PREPARATIONS FOR TOPICAL USE</span>
									<ul class="nested">
										<li><span class="caret"><b>[D10AA]</b> CORTICOSTEROIDS, COMBINATIONS FOR TREATMENT OF ACNE</span>
											<ul class="nested">
												<li><b>[D10AA03] DEXAMETHASONE</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[D10AD]</b> RETINOIDS FOR TOPICAL USE IN ACNE</span>
											<ul class="nested">
												<li><b>[D10AD01] TRETINOIN</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[G]</b> GENITO URINARY SYSTEM AND SEX HORMONES</span>
					<ul class="nested">
						<li><span class="caret"><b>[G01]</b> GYNECOLOGICAL ANTIINFECTIVES AND ANTISEPTICS</span>
							<ul class="nested">
								<li><span class="caret"><b>[G01A]</b> ANTIINFECTIVES AND ANTISEPTICS, EXCL. COMBINATIONS WITH CORTICOSTEROIDS</span>
									<ul class="nested">
										<li><span class="caret"><b>[G01AE]</b> SULFONAMIDES</span>
											<ul class="nested">
												<li><b>[G01AE10] CELECOXIB</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[G03]</b> SEX HORMONES AND MODULATORS OF THE GENITAL SYSTEM</span>
							<ul class="nested">
								<li><span class="caret"><b>[G03A]</b> HORMONAL CONTRACEPTIVES FOR SYSTEMIC USE</span>
									<ul class="nested">
										<li><span class="caret"><b>[G03AC]</b> PROGESTOGENS</span>
											<ul class="nested">
												<li><b>[G03AC05] MEGESTROL ACETATE</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[G03D]</b> PROGESTOGENS</span>
									<ul class="nested">
										<li><span class="caret"><b>[G03DB]</b> PREGNADIEN DERIVATIVES</span>
											<ul class="nested">
												<li><b>[G03DB02] MEGESTROL ACETATE</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[G03X]</b> OTHER SEX HORMONES AND MODULATORS OF THE GENITAL SYSTEM</span>
									<ul class="nested">
										<li><span class="caret"><b>[G03XC]</b> SELECTIVE ESTROGEN RECEPTOR MODULATORS</span>
											<ul class="nested">
												<li><b>[G03XC01] RALOXIFENE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[H]</b> SYSTEMIC HORMONAL PREPARATIONS, EXCL. SEX HORMONES AND INSULINS</span>
					<ul class="nested">
						<li><span class="caret"><b>[H02]</b> CORTICOSTEROIDS FOR SYSTEMIC USE</span>
							<ul class="nested">
								<li><span class="caret"><b>[H02A]</b> CORTICOSTEROIDS FOR SYSTEMIC USE, PLAIN</span>
									<ul class="nested">
										<li><span class="caret"><b>[H02AB]</b> GLUCOCORTICOIDS</span>
											<ul class="nested">
												<li><b>[H02AB02] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[L]</b> ANTINEOPLASTIC AND IMMUNOMODULATING AGENTS</span>
					<ul class="nested">
						<li><span class="caret"><b>[L01]</b> ANTINEOPLASTIC AGENTS</span>
							<ul class="nested">
								<li><span class="caret"><b>[L01A]</b> ALKYLATING AGENTS</span>
									<ul class="nested">
										<li><span class="caret"><b>[L01AA]</b> NITROGEN MUSTARD ANALOGUES</span>
											<ul class="nested">
												<li><b>[L01AA01] CYCLOPHOSPHAMIDE</b></li>
												<li><b>[L01AA02] CHLORAMBUCIL</b></li>
												<li><b>[L01AA03] MELPHALAN</b></li>
												<li><b>[L01AA05] MECHLORETHAMINE</b></li>
												<li><b>[L01AA06] IFOSFAMIDE</b></li>
												<li><b>[L01AA09] BENDAMUSTINE</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01AB]</b> ALKYL SULFONATES</span>	
											<ul class="nested">
												<li><b>[L01AB01] BUSULFAN</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01AC]</b> ETHYLENE IMINES</span>
											<ul class="nested">
												<li><b>[L01AC01] THIOTEPA</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01AD]</b> NITROSOUREAS</span>
											<ul class="nested">
												<li><b>[L01AD01] CARMUSTINE</b></li>
												<li><b>[L01AD02] LOMUSTINE</b></li>
												<li><b>[L01AD04] STREPTOZOCIN</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01AX]</b> OTHER ALKYLATING AGENTS</span>
											<ul class="nested">
												<li><b>[L01AX02] PIPOBROMAN</b></li>
												<li><b>[L01AX03] TEMOZOLOMIDE</b></li>
												<li><b>[L01AX04] DACARBAZINE</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[L01B]</b> ANTIMETABOLITES</span>
									<ul class="nested">
										<li><span class="caret"><b>[L01BA]</b> FOLIC ACID ANALOGUES	</span>
											<ul class="nested">
												<li><b>[L01BA01] METHOTREXATE</b></li>
												<li><b>[L01BA04] EMETREXED</b></li>
												<li><b>[L01BA05] RALATREXATE</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01BB]</b> PURINE ANALOGUES	</span>
											<ul class="nested">
												<li><b>[L01BB02] MERCAPTOPURINE</b></li>
												<li><b>[L01BB03] THIOGUANINE</b></li>
												<li><b>[L01BB04] CLADRIBINE</b></li>
												<li><b>[L01BB05] FLUDARABINE</b></li>
												<li><b>[L01BB06] CLOFARABINE</b></li>
												<li><b>[L01BB07] NELARABINE</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01BC]</b> PYRIMIDINE ANALOGUES	</span>
											<ul class="nested">
												<li><b>[L01BC01] CYTARABINE</b></li>
												<li><b>[L01BC02] LUOROURACIL</b></li>
												<li><b>[L01BC05] GEMCITABINE</b></li>
												<li><b>[L01BC06] CAPECITABINE</b></li>
												<li><b>[L01BC07] AZACITIDINE</b></li>
												<li><b>[L01BC08] DECITABINE</b></li>
												<li><b>[L01BC52] FLUOROURACIL</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[L01C]</b> PLANT ALKALOIDS AND OTHER NATURAL PRODUCTS</span>
									<ul class="nested">
										<li><span class="caret"><b>[L01CA]</b> VINCA ALKALOIDS AND ANALOGUES	</span>
											<ul class="nested">
												<li><b>[L01CA01] VINBLASTINE</b></li>
												<li><b>[L01CA02] VINCRISTINE</b></li>
												<li><b>[L01CA04] VINORELBINE</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01CB]</b> PODOPHYLLOTOXIN DERIVATIVES	</span>
											<ul class="nested">
												<li><b>[L01CB01] ETOPOSIDE</b></li>
												<li><b>[L01CB02] TENIPOSIDE</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01CD]</b> TAXANES	</span>
											<ul class="nested">
												<li><b>[L01CD01] PACLITAXEL</b></li>
												<li><b>[L01CD02] DOCETAXEL</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[L01D]</b> CYTOTOXIC ANTIBIOTICS AND RELATED SUBSTANCES</span>
									<ul class="nested">
										<li><span class="caret"><b>[L01DA]</b> ACTINOMYCINES</span>
											<ul class="nested">
												<li><b>[L01DA01] DACTINOMYCIN</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01DB]</b> ANTHRACYCLINES AND RELATED SUBSTANCES	</span>
											<ul class="nested">
												<li><b>[L01DB01] DOXORUBICIN</b></li>
												<li><b>[L01DB02] DAUNORUBICIN</b></li>
												<li><b>[L01DB07] MITOXANTRONE</b></li>
												<li><b>[L01DB09] VALRUBICIN</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01DC]</b> OTHER CYTOTOXIC ANTIBIOTICS	</span>
											<ul class="nested">
												<li><b>[L01DC01] BLEOMYCIN</b></li>
												<li><b>[L01DC02] PLICAMYCIN</b></li>
												<li><b>[L01DC03] MITOMYCIN</b></li>
												<li><b>[L01DC04] IXABEPILONE</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[L01X]</b> OTHER ANTINEOPLASTIC AGENTS</span>
									<ul class="nested">
										<li><span class="caret"><b>[L01XA]</b> PLATINUM COMPOUNDS	</span>
											<ul class="nested">
												<li><b>[L01XA01] DIAMMINEDICHLOROPLATINUM</b></li>
												<li><b>[L01XA02] CARBOPLATIN</b></li>
												<li><b>[L01XA03] OXALIPLATINO</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01XB]</b> METHYLHYDRAZINES	</span>
											<ul class="nested">
												<li><b>[L01XB01] PROCARBAZINE</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01XD]</b> SENSITIZERS USED IN PHOTODYNAMIC/RADIATION THERAPY	</span>
											<ul class="nested">
												<li><b>[L01XD04] AMINOLEVULINIC ACID</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01XE]</b> PROTEIN KINASE INHIBITORS	</span>
											<ul class="nested">
												<li><b>[L01XE01] IMATINIB</b></li>
												<li><b>[L01XE02] GEFITINIB</b></li>
												<li><b>[L01XE03] ERLOTINIB</b></li>
												<li><b>[L01XE04] SUNITINIB</b></li>
												<li><b>[L01XE05] SORAFENIB</b></li>
												<li><b>[L01XE06] DASATINIB</b></li>
												<li><b>[L01XE07] LAPATINIB</b></li>
												<li><b>[L01XE08] NILOTINIB</b></li>
												<li><b>[L01XE10] EVEROLIMUS</b></li>
												<li><b>[L01XE12] VANDETANIB</b></li>
												<li><b>[L01XE13] AFATINIB</b></li>
												<li><b>[L01XE16] CRIZOTINIB</b></li>
												<li><b>[L01XE19] RIDAFOROLIMUS</b></li>
												<li><b>[L01XE25] TRAMETINIB</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L01XX]</b> OTHER ANTINEOPLASTIC AGENTS	</span>
											<ul class="nested">
												<li><b>[L01XX03] ALTRETAMINE</b></li>
												<li><b>[L01XX05] HYDROXYUREA</b></li>
												<li><b>[L01XX08] PENTOSTATIN</b></li>
												<li><b>[L01XX11] ESTRAMUSTINE PHOSPHATE SODIUM</b></li>
												<li><b>[L01XX14] TRETINOIN</b></li>
												<li><b>[L01XX17] TOPOTECAN</b></li>
												<li><b>[L01XX23] MITOTANE</b></li>
												<li><b>[L01XX27] DIARSENIC TRIOXIDE</b></li>
												<li><b>[L01XX32] BORTEZOMIB</b></li>
												<li><b>[L01XX33] CELECOXIB</b></li>
												<li><b>[L01XX38] VORINOSTAT</b></li>
												<li><b>[L01XX39] ROMIDEPSIN</b></li>
												<li><b>[L01XX45] CARFILZOMIB</b></li>
												<li><b>[L01XX46] OLAPARIB</b></li>
												<li><b>[L01XX52] VENETOCLAX</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[L02]</b> ENDOCRINE THERAPY</span>
							<ul class="nested">
								<li><span class="caret"><b>[L02A]</b> HORMONES AND RELATED AGENTS</span>
									<ul class="nested">
										<li><span class="caret"><b>[L02AB]</b> PROGESTOGENS	</span>
											<ul class="nested">
												<li><b>[L02AB01] MEGESTROL ACETATE</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[L02B]</b> HORMONE ANTAGONISTS AND RELATED AGENTS</span>
									<ul class="nested">
										<li><span class="caret"><b>[L02BA]</b> ANTI-ESTROGENS	</span>
											<ul class="nested">
												<li><b>[L02BA01] TAMOXIFEN</b></li>
												<li><b>[L02BA03] FULVESTRANT</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L02BG]</b> AROMATASE INHIBITORS	</span>
											<ul class="nested">
												<li><b>[L02BG03] ANASTROZOLE</b></li>
												<li><b>[L02BG04] LETROZOLE</b></li>
												<li><b>[L02BG06] EXEMESTANE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[L04]</b> IMMUNOSUPPRESSANTS</span>
							<ul class="nested">	
								<li><span class="caret"><b>[L04A]</b> IMMUNOSUPPRESSANTS</span>
									<ul class="nested">
										<li><span class="caret"><b>[L04AA]</b> SELECTIVE IMMUNOSUPPRESSANTS	</span>
											<ul class="nested">
												<li><b>[L04AA10] SIROLIMUS</b></li>
												<li><b>[L04AA18] EVEROLIMUS</b></li>
											</ul>
										</li>
										<li><span class="caret"><b>[L04AX]</b> OTHER IMMUNOSUPPRESSANTS	</span>
											<ul class="nested">
												<li><b>[L04AX02] THALIDOMIDE</b></li>
												<li><b>[L04AX03] METHOTREXATE</b></li>
												<li><b>[L04AX04] LENALIDOMIDE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[M]</b> MUSCULO-SKELETAL SYSTEM</span>
					<ul class="nested">
						<li><span class="caret"><b>[M01]</b> ANTIINFLAMMATORY AND ANTIRHEUMATIC PRODUCTS</span>
							<ul class="nested">
								<li><span class="caret"><b>[M01A]</b> ANTIINFLAMMATORY AND ANTIRHEUMATIC PRODUCTS, NON-STEROIDS</span>
									<ul class="nested">
										<li><span class="caret"><b>[M01AH]</b> COXIBS</span>
											<ul class="nested">
												<li><b>[M01AH01] CELECOXIB</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[M04]</b> ANTIGOUT PREPARATIONS</span>
							<ul class="nested">
								<li><span class="caret"><b>[M04A]</b> ANTIGOUT PREPARATIONS</span>
									<ul class="nested">
										<li><span class="caret"><b>[M04AA]</b> PREPARATIONS INHIBITING URIC ACID PRODUCTION</span>			
											<ul class="nested">
												<li><b>[M04AA01] ALLOPURINOL</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[M05]</b> DRUGS FOR TREATMENT OF BONE DISEASES</span>
							<ul class="nested">
								<li><span class="caret"><b>[M05B]</b> DRUGS AFFECTING BONE STRUCTURE AND MINERALIZATION</span>
									<ul class="nested">
										<li><span class="caret"><b>[M05BA]</b> BISPHOSPHONATES</span>			
											<ul class="nested">
												<li><b>[M05BA08] ZOLEDRONIC ACID</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[P]</b> ANTIPARASITIC PRODUCTS, INSECTICIDES AND REPELLENTS</span>
					<ul class="nested">
						<li><span class="caret"><b>[P01]</b> ANTIPROTOZOALS</span>
							<ul class="nested">
								<li><span class="caret"><b>[P01A]</b> AGENTS AGAINST AMOEBIASIS AND OTHER PROTOZOAL DISEASES</span>
									<ul class="nested">
										<li><span class="caret"><b>[P01AX]</b> OTHER AGENTS AGAINST AMOEBIASIS AND OTHER PROTOZOAL DISEASES</span>
											<ul class="nested">
												<li><b>[P01AX05] MEPACRINE</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[P01B]</b> ANTIMALARIALS</span>
									<ul class="nested">
										<li><span class="caret"><b>[P01BA]</b> AMINOQUINOLINES</span>
											<ul class="nested">
												<li><b>[P01BA01] CHLOROQUINE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[R]</b> RESPIRATORY SYSTEM</span>
					<ul class="nested">
						<li><span class="caret"><b>[R01]</b> NASAL PREPARATIONS</span>
							<ul class="nested">
								<li><span class="caret"><b>[R01A]</b> DECONGESTANTS AND OTHER NASAL PREPARATIONS FOR TOPICAL USE</span>
									<ul class="nested">
										<li><span class="caret"><b>[R01AD]</b> CORTICOSTEROIDS</span>
											<ul class="nested">
												<li><b>[R01AD03] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[S]</b> SENSORY ORGANS</span>
					<ul class="nested">
						<li><span class="caret"><b>[S01]</b> OPHTHALMOLOGICALS</span>
							<ul class="nested">
								<li><span class="caret"><b>[S01B]</b> ANTIINFLAMMATORY AGENTS</span>
									<ul class="nested">
										<li><span class="caret"><b>[S01BA]</b> CORTICOSTEROIDS, PLAIN	</span>			
											<ul class="nested">
												<li><b>[S01BA01] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><span class="caret"><b>[S01C]</b> ANTIINFLAMMATORY AGENTS AND ANTIINFECTIVES IN COMBINATION</span>
									<ul class="nested">
										<li><span class="caret"><b>[S01CB]</b> CORTICOSTEROIDS/ANTIINFECTIVES/MYDRIATICS IN COMBINATION	</span>			
											<ul class="nested">
												<li><b>[S01CB01] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								</li>					
								<li><span class="caret"><b>[S01X]</b> OTHER OPHTHALMOLOGICALS</span>
									<ul class="nested">
										<li><span class="caret"><b>[S01XA]</b> OTHER OPHTHALMOLOGICALS</span>		
											<ul class="nested">
												<li><b>[S01XA23] SIROLIMUS</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li><span class="caret"><b>[S02]</b> OTOLOGICALS</span>
							<ul class="nested">
								<li><span class="caret"><b>[S02B]</b> CORTICOSTEROIDS</span>
									<ul class="nested">
										<li><span class="caret"><b>[S02BA]</b> CORTICOSTEROIDS</span>				
											<ul class="nested">
												<li><b>[S02BA06] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								</li>	
							</ul>
						</li>
						<li><span class="caret"><b>[S03]</b> OPHTHALMOLOGICAL AND OTOLOGICAL PREPARATIONS</span>
							<ul class="nested">
								<li><span class="caret"><b>[S03B]</b> CORTICOSTEROIDS</span>
									<ul class="nested">
										<li><span class="caret"><b>[S03BA]</b> CORTICOSTEROIDS</span>			
											<ul class="nested">
												<li><b>[S03BA01] DEXAMETHASONE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li><span class="caret"><b>[V]</b> VARIOUS</span>
					<ul class="nested">
						<li><span class="caret"><b>[V03]</b> ALL OTHER THERAPEUTIC PRODUCTS</span>
							<ul class="nested">
								<li><span class="caret"><b>[V03A]</b> ALL OTHER THERAPEUTIC PRODUCTS</span>
									<ul class="nested">
										<li><span class="caret"><b>[V03AF]</b> DETOXIFYING AGENTS FOR ANTINEOPLASTIC TREATMENT				</span>
											<ul class="nested">
												<li><b>[V03AF02] DEXRAZOXANE</b></li>
												<li><b>[V03AF05] AMIFOSTINE</b></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>		
			<script>
				var toggler = document.getElementsByClassName("caret");
				var i;

				for (i = 0; i < toggler.length; i++) {
				  toggler[i].addEventListener("click", function() {
					this.parentElement.querySelector(".nested").classList.toggle("active");
					this.classList.toggle("caret-down");
				  });
				}
			</script>

			<h2>Drugs, <i>N</i>=<?php echo $drug_counts; ?></h2>
			<TABLE WIDTH="800" BORDER="0" CELLPADDING="3" CELLSPACING="0">
				<TR BGCOLOR="black"><TH><FONT COLOR="white">Name</FONT></TH><TH width=120><FONT COLOR="white">ATC Code</FONT></TH><TH width=120><FONT COLOR="white">PubChem CID</FONT></TH><TH width=120><FONT COLOR="white">DrugBank ID</FONT></TH></TR>
<?php
	for($idx = 0 ; $idx < $drug_counts ; ++$idx){
		$bgcolor = "d6d6d6";
		if($idx%2 == 0){
			$bgcolor = "white";
		}
?>
				<TR BGCOLOR="<?php echo $bgcolor; ?>">
					<TD><?php echo $drug_names[$idx]; ?></TD>
					<TD><?php echo $atc_codes[$idx]; ?></TD>
					<TD ALIGN="RIGHT"><?php if($pubchem_ids[$idx] != "&nbsp;"){ ?><A HREF="https://pubchem.ncbi.nlm.nih.gov/compound/<?php echo $pubchem_ids[$idx]; ?>" target="_blank"><?php } ?>
						<?php echo $pubchem_ids[$idx]; ?>
						<?php if($pubchem_ids[$idx] != "&nbsp;"){ ?></A><?php } ?>
					</TD>
					<TD><?php if($drugbank_ids[$idx] != "&nbsp;"){ ?><A HREF="https://www.drugbank.ca/drugs/<?php echo $drugbank_ids[$idx]; ?>" target="_blank"><?php } ?>
						<?php echo $drugbank_ids[$idx]; ?>
						<?php if($drugbank_ids[$idx] != "&nbsp;"){ ?></A><?php } ?>
					</TD>
				</TR>
<?php
	}
?>	
				<tr><td colspan=4><hr></td></tr>			
			</TABLE>
		</FORM>
	</BODY>
</HTML>

<?php
mysqli_close($con);
?>
