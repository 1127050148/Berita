<?php
	class Knn {

		public function updateBobot() {
			//hitung doc data latih
			$sqlJumLatih = mysql_query("SELECT DISTINCT id_doc FROM tbindex");
			$numRowLatih = mysql_num_rows($sqlJumLatih);

			// hitung doc data uji
			$sqljumBaru = mysql_query("SELECT DISTINCT id_doc FROM tbindex_baru");
			$numRowBaru = mysql_num_rows($sqljumBaru);

			// jumlah dokumen update
			$n = $numRowLatih+$numRowBaru; echo $n;


			$resVektor = mysql_query("SELECT a.* FROM (SELECT tbindex_baru.term AS termBaru, tbindex_baru.jumlah AS jumBaru, tbindex_baru.id_doc AS idBaru, 
									tbindex_baru.bobot AS bobotBaru, tbindex.term AS termLatih, tbindex.jumlah AS jumLatih, 
									tbindex.id_doc AS idLatih, tbindex.bobot AS bobotLatih FROM tbindex_baru 
									left JOIN tbindex on tbindex_baru.term =  tbindex.term 
									UNION
									SELECT tbindex_baru.term AS termBaru, tbindex_baru.jumlah AS jumBaru, tbindex_baru.id_doc AS idBaru, 
									tbindex_baru.bobot AS bobotBaru, tbindex.term AS termLatih, tbindex.jumlah AS jumLatih, 
									tbindex.id_doc AS idLatih, tbindex.bobot AS bobotLatih FROM tbindex_baru 
									right JOIN tbindex on tbindex_baru.term =  tbindex.term) a ORDER BY a.termBaru DESC");

			while ($row = mysql_fetch_array($resVektor)) {
				$termBaru = $row['termBaru'];
				$jumBaru = $row['jumBaru'];
				$idBaru = $row['idBaru'];
				$bobotBaru = $row['bobotBaru'];
				$termLatih = $row['termLatih'];
				$jumLatih = $row['jumLatih'];
				$idLatih = $row['idLatih'];
				$bobotLatih = $row['bobotLatih'];

				$idfBaru = mysql_query("SELECT COUNT(*) AS jum FROM tbindex_baru WHERE term = '".$termBaru."'");
				while ($rowJumBaru = mysql_fetch_array($idfBaru)) {
					$dfBaru = $rowJumBaru['jum'];
				}

				$idfLatih = mysql_query("SELECT COUNT(*) AS jum FROM tbindex WHERE term = '".$termLatih."'");
				while ($rowJumLatih = mysql_fetch_array($idfLatih)) {
					$dfLatih = $rowJumLatih['jum'];
				}
				// update nilai df
				$dfUpdate = $dfBaru + $dfLatih; // echo $termLatih." : ".$dfUpdate."<br>";


				if ($termBaru == $termLatih) {	
					// update bobot termBaru
					$sqlUpdateBaru = mysql_query("SELECT bobot FROM update_bobotuji WHERE term = '".$termBaru."' AND id_doc = '".$idBaru."'");
					$jumRowBaru = mysql_num_rows($sqlUpdateBaru);
					$wUpdate = $jumBaru * log10($n / $dfUpdate);
					if ($jumRowBaru > 0) {
						mysql_query("UPDATE update_bobotuji SET bobot = '".$wUpdate."' WHERE term = '".$termBaru."' AND id_doc = '".$idBaru."'");
					}
					else {
						mysql_query("INSERT INTO update_bobotuji VALUES ('".$termBaru."', '".$idBaru."' , '".$wUpdate."', '', '')");
					}

					// update bobot termLatih
					$sqlUpdateLatih = mysql_query("SELECT bobot FROM update_bobotlatih WHERE term = '".$termLatih."' AND id_doc = '".$idLatih."'");
					$jumRowLatih = mysql_num_rows($sqlUpdateLatih);
					$wUpdateLatih = $jumLatih * log10($n / $dfUpdate);
					if ($jumRowLatih > 0) {
						mysql_query("UPDATE update_bobotlatih SET bobot = '".$wUpdateLatih."' WHERE term = '".$termLatih."' AND id_doc = '".$idLatih."'");
					}
					else {
						mysql_query("INSERT INTO update_bobotlatih VALUES ('".$termLatih."', '".$idLatih."' , '".$wUpdateLatih."', '', '')");
					}
				}
				elseif ($termBaru == NULL) {
					// update bobot termLatih
					$sqlUpdateLatih = mysql_query("SELECT bobot FROM update_bobotlatih WHERE term = '".$termLatih."' AND id_doc = '".$idLatih."'");
					$jumRowLatih = mysql_num_rows($sqlUpdateLatih);
					$w = $jumLatih * log10($n / $dfLatih);
					if ($jumRowLatih > 0) {
						mysql_query("UPDATE update_bobotlatih SET bobot = '".$w."' WHERE term = '".$termLatih."' AND id_doc = '".$idLatih."'");
					}
					else {
						mysql_query("INSERT INTO update_bobotlatih VALUES ('".$termLatih."', '".$idLatih."' , '".$w."', '', '')");
					}
				}
				elseif ($termLatih == NULL) {
					// update bobot termUji
					$sqlUpdateBaru = mysql_query("SELECT bobot FROM update_bobotuji WHERE term = '".$termBaru."' AND id_doc = '".$idBaru."'");
					$jumRowBaru = mysql_num_rows($sqlUpdateBaru);
					$wBaru = $jumBaru * log10($n / $dfBaru);
					if ($jumRowBaru > 0) {
						mysql_query("UPDATE update_bobotuji SET bobot = '".$wBaru."' WHERE term = '".$termBaru."' AND id_doc = '".$idBaru."'");
					}
					else {
						mysql_query("INSERT INTO update_bobotuji VALUES ('".$termBaru."', '".$idBaru."', '".$wBaru."', '', '')");
					}
				}
			}
		}

		public function knnProses() {
			$query = mysql_query("SELECT update_bobotuji.term AS termUji, update_bobotuji.id_doc AS idUji,
								update_bobotuji.bobot AS bobotUji,
								update_bobotlatih.term AS termLatih, update_bobotlatih.id_doc AS idLatih,
								update_bobotlatih.bobot AS bobotLatih FROM update_bobotuji
								JOIN update_bobotlatih on update_bobotuji.term = update_bobotlatih.term
								ORDER BY update_bobotuji.id_doc ASC");

			// insert ke tabel cosine
			while ($row = mysql_fetch_array($query)) {
				$rowTermLatih = $row['termLatih'];
				$rowIDLatih = $row['idLatih'];
				$rowBobotLatih = $row['bobotLatih'];
				$rowTermUji = $row['termUji'];
				$rowIDUji = $row['idUji'];
				$rowBobotUji = $row['bobotUji'];

				$qcosine = mysql_query("SELECT * FROM cosine WHERE term_uji = '".$rowTermUji."' AND id_doc_uji = '".$rowIDUji."'");
				$ccosine = mysql_num_rows($qcosine);
				if ($ccosine > 0) {
					echo "";
				}
				else {
					mysql_query("INSERT INTO cosine VALUES ('','".$rowTermUji."',".$rowIDUji. ", '".$rowBobotUji."', '".$rowTermLatih."',
								".$rowIDLatih.", '".$rowBobotLatih."', '','','','')");
				}
			}

			// perkalian data W-uji dengan semua bobot W dokumen
			$selectJumData = mysql_query("SELECT * FROM cosine");
			while ($jumData = mysql_fetch_array($selectJumData)) {
				$id = $jumData['id_cos'];
				$dBobotUji = $jumData['bobot_dok_uji'];
				$dBobotLatih = $jumData['bobot_dok_latih'];

				$wPerDok = $dBobotUji * $dBobotLatih;
				mysql_query("UPDATE cosine SET bobot_kali_dok = '".$wPerDok."' WHERE id_cos = '".$id."'");
			}

			// hitung semua penjumlahan dari data uji dan latih
			$aa = array();
			$bobot = array();
			$id = array();
			$selectcosine = mysql_query("SELECT * FROM cosine");
			while ($y = mysql_fetch_array($selectcosine)) {
				$id = $y['id_doc_uji'];
				for ($j=1; $j <= 30; $j++) { 
					$l = mysql_query("SELECT SUM(bobot_kali_dok) AS jum FROM cosine WHERE id_doc_uji = $id AND id_doc_latih = $j");
					while ($k = mysql_fetch_array($l)) {
						$bobot = $k['jum'];
							mysql_query("UPDATE cosine SET jum_bobot_kali_dok = '".$bobot."' WHERE id_doc_uji = '".$i."' AND id_doc_latih = '".$j."'");		
					}
					// echo "$id.$j = $bobot<br>";
					break;
				}
			}

			// bagian bobot data latih
			$queryVektorLatih = mysql_query("SELECT * FROM update_bobotlatih");
			while ($latih = mysql_fetch_array($queryVektorLatih)) {
				$termLatih = $latih['term'];
				$idLatih = $latih['id_doc'];
				$bobotLatih = $latih['bobot'];
				// perkalian untuk panjang vektor dari tiap bobot
				$hasilKaliVektor = $bobotLatih * $bobotLatih;
				mysql_query("UPDATE update_bobotlatih SET bobot_vektor = '".$hasilKaliVektor."' WHERE term = '".$termLatih."' AND id_doc = '".$idLatih."'");
			}

			$jumDocLatih = mysql_query("SELECT id_doc, SUM(bobot_vektor) AS bobot_kuadrat FROM update_bobotlatih GROUP BY id_doc");
			while ($aa = mysql_fetch_array($jumDocLatih)) {
				$id = $aa['id_doc'];
				$bobot = $aa['bobot_kuadrat'];
				$sqrtBobotLatih = sqrt($bobot);
				// echo "$id : $bobot : $sqrtBobotLatih <br>";
				mysql_query("UPDATE update_bobotlatih SET sum_bobot_vektor = '".$bobot."', akar_bobot_vektor = '".$sqrtBobotLatih."' WHERE id_doc = '".$id."'");
			}

			// echo "<br><br>";
			// bagian bobot data uji
			$queryVektorUji = mysql_query("SELECT * FROM update_bobotuji ");
			while ($uji = mysql_fetch_array($queryVektorUji)) {
				$termUji = $uji['term'];
				$idUji = $uji['id_doc'];
				$bobotUji = $uji['bobot'];
				// perkalian untuk panjang vektor dari tiap bobot
				$hasilKaliVektorUji = $bobotUji * $bobotUji;
				mysql_query("UPDATE update_bobotuji SET bobot_vektor = '".$hasilKaliVektorUji."' WHERE term = '".$termUji."' AND id_doc = '".$idUji."'");
			}
			
			$jumDocUji = mysql_query("SELECT id_doc,SUM(bobot_vektor) AS bobot_kuadratUji FROM update_bobotuji GROUP BY id_doc");
			while ($bb = mysql_fetch_array($jumDocUji)) {
				$id_uji = $bb['id_doc'];
				$bobot_uji = $bb['bobot_kuadratUji'];
				$sqrtBobotUji = sqrt($bobot_uji);
				// echo "$id_uji : $bobot_uji : $sqrtBobotUji <br>";
				mysql_query("UPDATE update_bobotuji SET sum_bobot_vektor = '".$bobot_uji."', akar_bobot_vektor = '".$sqrtBobotUji."' WHERE id_doc = '".$id_uji."'");
			}

			 //update id_kategori di tabel coba
			$selkat = mysql_query("SELECT update_bobotlatih.id_doc AS id, berita_training.id_kategori AS idKat FROM update_bobotlatih
								INNER JOIN berita_training ON berita_training.id_berita_training = update_bobotlatih.id_doc");
			while ($rKat = mysql_fetch_array($selkat)) {
				$idLatih = $rKat['id'];
				$idKat = $rKat['idKat'];
				mysql_query("UPDATE cosine SET id_kategori = ".$idKat." WHERE id_doc_latih = ".$idLatih);
			}


			// hitung cosine similarity
			$cos = array();
			for ($i=1; $i <= 30 ; $i++) { 
				$selDataUji = mysql_query("SELECT akar_bobot_vektor FROM update_bobotuji WHERE id_doc = '".$i."' GROUP BY id_doc");
				while ($w = mysql_fetch_array($selDataUji)) {
					$akarVektorUji = $w['akar_bobot_vektor'];

					for ($j=1; $j < 30; $j++) { 

						$selDataLatih = mysql_query("SELECT akar_bobot_vektor FROM update_bobotlatih WHERE id_doc = '".$j."' GROUP BY id_doc");
						while ($r = mysql_fetch_array($selDataLatih)) {
							$akarVektorLatih = $r['akar_bobot_vektor'];
							// echo "$akarVektorUji ---- $akarVektorLatih<br> ";

							$selJum = mysql_query("SELECT DISTINCT jum_bobot_kali_dok AS bobot FROM cosine WHERE id_doc_uji = '".$i."' AND id_doc_latih = '".$j."'");
							$dt = mysql_fetch_array($selJum);
							$jum_bobot = $dt['bobot'];

							$cos = $jum_bobot / ($akarVektorUji * $akarVektorLatih);
							echo "Cos(D$i,D$j) : $jum_bobot / ($akarVektorUji * $akarVektorLatih) = $cos <br>";
							mysql_query("UPDATE cosine SET similarity = '".$cos."' WHERE id_doc_uji = ".$i." AND id_doc_latih = ".$j);
						}
					}
					echo "<br>";
				}	
			}

			$k = 9;
			$dKat = array();	
			for ($docUji=1; $docUji < 30; $docUji++) {
				$selDatacosine = mysql_query("SELECT id_doc_uji,id_kategori, count(id_kategori) as jum from
											(SELECT id_doc_uji,id_kategori FROM cosine 
											WHERE id_doc_uji = $docUji GROUP BY id_doc_latih  
											ORDER BY similarity DESC LIMIT $k) as xxx 
											GROUP BY id_kategori  ORDER BY jum ASC");
				while ($rCos = mysql_fetch_array($selDatacosine)) {
					$freq = $rCos['jum'];
					$dKat = $rCos['id_kategori'];
					$id = $rCos['id_doc_uji'];echo "$id----id dokumen $dKat : frekuensi $freq----<br>";
				}
				echo "$id----id dokumen $dKat : frekuensi $freq----<br>";
				foreach ((array)$dKat as $key) {
					switch ($key) {
						case '1':
							// echo "$docUji = Politik";
							mysql_query("UPDATE berita_baru SET kategori_knn = 'Politik' WHERE id_berita_baru = ".$id);
							break;
						
						case '2':
							// echo "$docUji = Olahraga";
							mysql_query("UPDATE berita_baru SET kategori_knn = 'Olahraga' WHERE id_berita_baru = ".$id);
							break;

						case '3':
							// echo "$docUji = Pendidikan";
							mysql_query("UPDATE berita_baru SET kategori_knn = 'Pendidikan' WHERE id_berita_baru = ".$id);
							break;

						case '4':
							// echo "$docUji = Otomotif";
							mysql_query("UPDATE berita_baru SET kategori_knn = 'Otomotif' WHERE id_berita_baru = ".$id);					
							break;

						case '5':
							// echo "$docUji = Umum";
							mysql_query("UPDATE berita_baru SET kategori_knn = 'Umum' WHERE id_berita_baru = ".$id);
							break;
					}echo "<br>";
				}
				echo "<br>";
			}
			echo "<br>";
		}
	}
?>