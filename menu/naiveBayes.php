<?php
	class NaiveBayes {

		public function nbProses() {

			$awal = microtime(TRUE);
			// jumlah kata dalam data latih
			$queryTerm = mysql_query("SELECT tbindex.term AS term, tbindex.id_doc AS id_doc, tbindex.jumlah AS Frekuensi,
									berita_training.kategori AS kategori FROM tbindex 
									JOIN berita_training ON tbindex.id_doc = berita_training.id_berita_training");
			$kosakataLatih = mysql_num_rows($queryTerm); //2513 kata
			while ($data = mysql_fetch_array($queryTerm)) {
				$term = $data['term'];
				$id = $data['id_doc'];
				$frek = $data['Frekuensi'];
				$kat = $data['kategori'];

				$qCekData = mysql_query("SELECT * FROM nb_latih WHERE term = '".$term."' AND id_doc = '".$id."'");
				$cekData = mysql_num_rows($qCekData);
				if ($cekData > 0) {
					echo "";
				}
				else {
					mysql_query("INSERT INTO nb_latih (term, id_doc, frekuensi, kategori) VALUES
								('".$term."', '".$id."', '".$frek."', '".$kat."')");
				}
			}

			// jumlah seluruh dokumen latih
			$nDok = mysql_query("SELECT DISTINCT id_doc FROM nb_latih");
			$jumDoc = mysql_num_rows($nDok); //28 Data

			// jumlah dokumen dengan kategori Politik
			$dokPol = mysql_query("SELECT DISTINCT id_doc FROM nb_latih WHERE kategori = 'Politik'");
			$jumPol = mysql_num_rows($dokPol); //5 Data
			$pvjPol = $jumPol / $jumDoc;
			$qJumPol = mysql_query("SELECT SUM(frekuensi) AS freq FROM nb_latih WHERE kategori = 'Politik'");
			$rPol = mysql_fetch_array($qJumPol); // 576 kata
			$jumKataPol = $rPol['freq'];

			// jumlah dokumen dengan kategori Olahraga
			$dokOlg = mysql_query("SELECT DISTINCT id_doc FROM nb_latih WHERE kategori = 'Olahraga'");
			$jumOlg = mysql_num_rows($dokOlg); //5 Data
			$pvjOlg = $jumOlg / $jumDoc;
			$qJumOlg = mysql_query("SELECT SUM(frekuensi) AS freq FROM nb_latih WHERE kategori = 'Olahraga'");
			$rOlg = mysql_fetch_array($qJumOlg); // 633 kata
			$jumKataOlg = $rOlg['freq'];

			// jumlah dokumen dengan kategori Pendidikan
			$dokPend = mysql_query("SELECT DISTINCT id_doc FROM nb_latih WHERE kategori = 'Pendidikan'");
			$jumPend = mysql_num_rows($dokPend); //5 Data
			$pvjPend = $jumPend / $jumDoc;
			$qJumPend = mysql_query("SELECT SUM(frekuensi) AS freq FROM nb_latih WHERE kategori = 'Pendidikan'");
			$rPend = mysql_fetch_array($qJumPend); // 932 kata
			$jumKataPend = $rPend['freq'];

			// jumlah dokumen dengan kategori Otomotif
			$dokOto = mysql_query("SELECT DISTINCT id_doc FROM nb_latih WHERE kategori = 'Otomotif'");
			$jumOto = mysql_num_rows($dokOto); //5 Data
			$pvjOto = $jumOto / $jumDoc;
			$qJumOto = mysql_query("SELECT SUM(frekuensi) AS freq FROM nb_latih WHERE kategori = 'Otomotif'");
			$rOto = mysql_fetch_array($qJumOto); // 754 kata
			$jumKataOto = $rOto['freq'];

			// jumlah dokumen dengan kategori Umum
			$dokUmum = mysql_query("SELECT DISTINCT id_doc FROM nb_latih WHERE kategori = 'Umum'");
			$jumUmum = mysql_num_rows($dokUmum); //8 Data
			$pvjUmum = $jumUmum / $jumDoc;
			$qJumUmum = mysql_query("SELECT SUM(frekuensi) AS freq FROM nb_latih WHERE kategori = 'Umum'");
			$rUmum = mysql_fetch_array($qJumUmum); // 1174 kata
			$jumKataUmum = $rUmum['freq'];

			//pvj tiap kategori
			mysql_query("UPDATE naive_bayes SET pvj = '".$pvjPol."' WHERE kategori = 'Politik'");
			mysql_query("UPDATE naive_bayes SET pvj = '".$pvjOlg."' WHERE kategori = 'Olahraga'");
			mysql_query("UPDATE naive_bayes SET pvj = '".$pvjPend."' WHERE kategori = 'Pendidikan'");
			mysql_query("UPDATE naive_bayes SET pvj = '".$pvjOto."' WHERE kategori = 'Otomotif'");
			mysql_query("UPDATE naive_bayes SET pvj = '".$pvjUmum."' WHERE kategori = 'Umum'");

			
			// pwk tiap dokumen dengan kategori
			// insert to tabel naive_bayes
			$selAll = mysql_query("SELECT  tbindex_baru.term AS termUji, tbindex_baru.id_doc AS id_uji, tbindex_baru.jumlah AS freqUji,
								nb_latih.term AS termLatih, nb_latih.id_doc AS id_latih, nb_latih.frekuensi AS freq FROM tbindex_baru
								JOIN nb_latih ON nb_latih.term = tbindex_baru.term");
			while ($rAll = mysql_fetch_array($selAll)) {
				$tAllUji = $rAll['termUji'];
				$idAllUji = $rAll['id_uji'];
				$freqUji = $rAll['freqUji'];
				$tAllLatih = $rAll['termLatih'];
				$idAllLatih = $rAll['id_latih'];
				$freq = $rAll['freq'];

				$s = mysql_query("SELECT SUM(jumlah) AS jum FROM tbindex_baru WHERE id_doc = ".$idAllUji." GROUP BY id_doc");
				while ($rSum = mysql_fetch_array($s)) {
					$jumData = $rSum['jum'];
				}
				$sFrekPol = mysql_query("SELECT SUM(frekuensi) AS frek from nb_latih where term = '".$tAllLatih."' AND kategori = 'Politik'");
				while ($rFrekPol = mysql_fetch_array($sFrekPol)) {
					$frekPol = $rFrekPol['frek'];
					$pwkPol = ($frekPol + 1) / ($jumKataPol + $jumData);
					// echo "Term Uji ($tAllUji)($idAllUji) : Term Latih ($tAllLatih)($idAllLatih) : NK ($frekPol)
					// : N ($jumKataPol) : Vocab ($jumData) = $pwkPol<br>";
				}
				$sFrekOlg = mysql_query("SELECT SUM(frekuensi) AS frek from nb_latih where term = '".$tAllLatih."' AND kategori = 'Olahraga'");
				while ($rFrekOlg = mysql_fetch_array($sFrekOlg)) {
					$frekOlg = $rFrekOlg['frek'];
					$pwkOlg = ($frekOlg + 1) / ($jumKataOlg + $jumData);
					// echo "Term Uji ($tAllUji)($idAllUji) : Term Latih ($tAllLatih)($idAllLatih) : NK ($frekOlg)
					// : N ($jumKataOlg) : Vocab ($jumData) = $pwkOlg<br>";
				}
				$sFrekPend = mysql_query("SELECT SUM(frekuensi) AS frek from nb_latih where term = '".$tAllLatih."' AND kategori = 'Pendidikan'");
				while ($rFrekPend = mysql_fetch_array($sFrekPend)) {
					$frekPend = $rFrekPend['frek'];
					$pwkPend = ($frekPend + 1) / ($jumKataPend + $jumData);
					// echo "Term Uji ($tAllUji)($idAllUji) : Term Latih ($tAllLatih)($idAllLatih) : NK ($frekPend)
					// : N ($jumKataPend) : Vocab ($jumData) = $pwkPend<br>";
				}
				$sFrekOto = mysql_query("SELECT SUM(frekuensi) AS frek from nb_latih where term = '".$tAllLatih."' AND kategori = 'Otomotif'");
				while ($rFrekOto = mysql_fetch_array($sFrekOto)) {
					$frekOto = $rFrekOto['frek'];
					$pwkOto = ($frekOto + 1) / ($jumKataOto + $jumData);
					// echo "Term Uji ($tAllUji)($idAllUji) : Term Latih ($tAllLatih)($idAllLatih) : NK ($frekOto)
					// : N ($jumKataOto) : Vocab ($jumData) = $pwkOto<br>";
				}
				$sFrekUmum = mysql_query("SELECT SUM(frekuensi) AS frek from nb_latih where term = '".$tAllLatih."' AND kategori = 'Umum'");
				while ($rFrekUmum = mysql_fetch_array($sFrekUmum)) {
					$frekUmum = $rFrekUmum['frek'];
					$pwkUmum = ($frekUmum + 1) / ($jumKataUmum + $jumData);
				// 	echo "Term Uji ($tAllUji)($idAllUji) : Term Latih ($tAllLatih)($idAllLatih) : NK ($frekUmum)
				// 	: N ($jumKataUmum) : Vocab ($jumData) = $pwkUmum<br>";
				// }

				$selNB = mysql_query("SELECT * FROM naive_bayes");
				$cekNB = mysql_num_rows($selNB);
				if ($cekNB > 0) {
					echo "";
				}
				else {
					mysql_query("INSERT INTO naive_bayes(term_uji,id_doc_uji,frekuensi_uji,term_latih,id_doc_latih,frekuensi_latih, pwk_politik,
								pwk_olahraga,pwk_pendidikan,pwk_otomotif,pwk_umum)
								VALUES('$tAllUji',$idAllUji,$freqUji,'$tAllLatih',$idAllLatih,$freq,'$pwkPol','$pwkOlg','$pwkPend',
								'$pwkOto','$pwkUmum')");
				}
			}

			// // update kategori dari data latih
			// $selKat = mysql_query("SELECT nb_latih.kategori AS kat, nb_latih.id_doc AS id_doc, naive_bayes.id_doc_latih AS id_latih
			// 					FROM nb_latih JOIN naive_bayes on nb_latih.id_doc = naive_bayes.id_doc_latih");
			// while ($rKat = mysql_fetch_array($selKat)) {
			// 	$kat = $rKat['kat'];
			// 	$id_latih = $rKat['id_latih'];
			// 	// mysql_query("UPDATE naive_bayes SET kategori = '".$kat."' WHERE id_doc_latih = ".$id_latih);
			// }


			// hitung Vmap Politik
			$selPolUji = mysql_query("SELECT * FROM naive_bayes");
			// while ($rowPolUji = mysql_fetch_array($selPolUji)) {
			// 	$dok_uji = $rowPolUji['id_doc_uji'];
			// 	$term_uji = $rowPolUji['term_uji'];
			for ($dok_uji=1; $dok_uji < 30; $dok_uji++) { 
				$selPwk = mysql_query("SELECT * FROM naive_bayes WHERE id_doc_uji = $dok_uji  
											GROUP BY term_uji ORDER BY  id_doc_uji ASC");
					$vmapPol = 1;
					$vmapOlg = 1;
					$vmapPend = 1;
					$vmapOto = 1;
					$vmapUmum = 1;
					while ($rowPwk = mysql_fetch_array($selPwk)) {
						$id = $rowPwk['id_doc_uji'];
						$pwkPol = $rowPwk['pwk_politik'];
						$pwkOlg = $rowPwk['pwk_olahraga'];
						$pwkPend = $rowPwk['pwk_pendidikan'];
						$pwkOto = $rowPwk['pwk_otomotif'];
						$pwkUmum = $rowPwk['pwk_umum'];
						// echo "id uji ($id) | pwk ($pwkPol)<br>";
						foreach ((array)$pwkPol as $keyPol => $valPol) {
							$vmapPol = $vmapPol * $valPol;
						}
						foreach ((array)$pwkOlg as $keyOlg => $valOlg) {
							$vmapOlg = $vmapOlg * $valOlg;
						}
						foreach ((array)$pwkPend as $keyPend => $valPend) {
							$vmapPend = $vmapPend * $valPend;
						}
						foreach ((array)$pwkOto as $keyOto => $valOto) {
							$vmapOto = $vmapOto * $valOto;
						}
						foreach ((array)$pwkUmum as $keyUmum => $valUmum) {
							$vmapUmum = $vmapUmum * $valUmum;
						}
					}
					$hasilPol = $pvjPol * $vmapPol;
					$hasilOlg = $pvjOlg * $vmapOlg;
					$hasilPend = $pvjPend * $vmapPend;
					$hasilOto = $pvjOto * $vmapOto;
					$hasilUmum = $pvjUmum * $vmapUmum;
					// echo "(id uji)$id | pvj($pvjPol) * pwk ($pwkPol)= (vMAP)$hasilPol<br>";
					// echo "(id uji)$id | pvj($pvjOlg) * pwk ($pwkOlg)= (vMAP)$hasilOlg<br>";
					// echo "(id uji)$id | pvj($pvjPend) * pwk ($pwkPend)= (vMAP)$hasilPend<br>";
					// echo "(id uji)$id | pvj($pvjOto) * pwk ($pwkOto)= (vMAP)$hasilOto<br>";
					// echo "(id uji)$id | pvj($pvjUmum) * pwk ($pwkUmum)= (vMAP)$hasilUmum<br>";
					mysql_query("UPDATE naive_bayes SET vmap_politik = '$hasilPol', vmap_olahraga = '$hasilOlg',
								vmap_pendidikan = '$hasilPend', vmap_otomotif = '$hasilOto', vmap_umum = '$hasilUmum'
								WHERE id_doc_uji = $id");			
			}
			echo "<br>";
			// menentukan kategori
			$selData = mysql_query("SELECT * FROM naive_bayes GROUP BY id_doc_uji");
			while ($rowData = mysql_fetch_array($selData)) {
				$iUji = $rowData['id_doc_uji'];
				$pol = $rowData['vmap_politik'];
				$olg = $rowData['vmap_olahraga'];
				$pend = $rowData['vmap_pendidikan'];
				$oto = $rowData['vmap_otomotif'];
				$umum = $rowData['vmap_umum'];

				if ($pol > $olg && $pol > $pend && $pol > $oto && $pol > $umum) {
					// echo "$iUji : Politik <br>";
					mysql_query("UPDATE berita_baru set kategori_nb = 'Politik' WHERE id_berita_baru = $iUji");
				}
				elseif ($olg > $pol && $olg > $pend && $olg > $oto && $olg > $umum) {
					// echo "$iUji : Olahraga <br>";
					mysql_query("UPDATE berita_baru set kategori_nb = 'Olahraga' WHERE id_berita_baru = $iUji");
				}
				elseif ($pend > $pol && $pend > $olg && $pend > $oto && $pend > $umum) {
					// echo "$iUji : Pendidikan <br>";
					mysql_query("UPDATE berita_baru set kategori_nb = 'Pendidikan' WHERE id_berita_baru = $iUji");			
				}
				elseif ($oto > $pol && $oto > $pend && $oto > $olg && $oto > $umum) {
					// echo "$iUji : Otomotif <br>";
					mysql_query("UPDATE berita_baru set kategori_nb = 'Otomotif' WHERE id_berita_baru = $iUji");
				}
				elseif ($umum > $pol && $umum > $olg && $umum > $pend && $umum > $oto) {
					// echo "$iUji : Umum <br>";
					mysql_query("UPDATE berita_baru set kategori_nb = 'Umum' WHERE id_berita_baru = $iUji");
				}
			}

			$akhir = microtime(TRUE);
			$lama = $akhir - $awal;
			mysql_query("UPDATE waktu set nb = '".$lama."'");
			echo "Lama Proses = <b>$lama</b> detik";
		}
	}
?>