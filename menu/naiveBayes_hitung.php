<?php
	class NaiveBayes_hitung {

		public function nbProses() {
			// jumlah seluruh dokumen latih
            $nDok = mysql_query("SELECT DISTINCT id_doc FROM tbindex_hitung");
            $jumDoc = mysql_num_rows($nDok); //28 Data

            // jumlah dokumen dengan kategori Politik
            $dokPol = mysql_query("SELECT DISTINCT id_doc FROM tbindex_hitung WHERE id_kategori = 1");
            $jumPol = mysql_num_rows($dokPol); //5 Data
            $pvjPol = $jumPol / $jumDoc;
            $qJumPol = mysql_query("SELECT SUM(jumlah) AS freq FROM tbindex_hitung WHERE id_kategori = 1");
            $rPol = mysql_fetch_array($qJumPol); // 576 kata
            $jumKataPol = $rPol['freq'];

            // jumlah dokumen dengan kategori Olahraga
            $dokOlg = mysql_query("SELECT DISTINCT id_doc FROM tbindex_hitung WHERE id_kategori = 2");
            $jumOlg = mysql_num_rows($dokOlg); //5 Data
            $pvjOlg = $jumOlg / $jumDoc;
            $qJumOlg = mysql_query("SELECT SUM(jumlah) AS freq FROM tbindex_hitung WHERE id_kategori = 2");
            $rOlg = mysql_fetch_array($qJumOlg); // 633 kata
            $jumKataOlg = $rOlg['freq'];

            // jumlah dokumen dengan kategori Pendidikan
            $dokPend = mysql_query("SELECT DISTINCT id_doc FROM tbindex_hitung WHERE id_kategori = 3");
            $jumPend = mysql_num_rows($dokPend); //5 Data
            $pvjPend = $jumPend / $jumDoc;
            $qJumPend = mysql_query("SELECT SUM(jumlah) AS freq FROM tbindex_hitung WHERE id_kategori = 3");
            $rPend = mysql_fetch_array($qJumPend); // 932 kata
            $jumKataPend = $rPend['freq'];

            // jumlah dokumen dengan kategori Otomotif
            $dokOto = mysql_query("SELECT DISTINCT id_doc FROM tbindex_hitung WHERE id_kategori = 4");
            $jumOto = mysql_num_rows($dokOto); //5 Data
            $pvjOto = $jumOto / $jumDoc;
            $qJumOto = mysql_query("SELECT SUM(jumlah) AS freq FROM tbindex_hitung WHERE id_kategori = 4");
            $rOto = mysql_fetch_array($qJumOto); // 754 kata
            $jumKataOto = $rOto['freq'];

            // jumlah dokumen dengan kategori Umum
            $dokUmum = mysql_query("SELECT DISTINCT id_doc FROM tbindex_hitung WHERE id_kategori = 5");
            $jumUmum = mysql_num_rows($dokUmum); //8 Data
            $pvjUmum = $jumUmum / $jumDoc;
            $qJumUmum = mysql_query("SELECT SUM(jumlah) AS freq FROM tbindex_hitung WHERE id_kategori = 5");
            $rUmum = mysql_fetch_array($qJumUmum); // 1174 kata
            $jumKataUmum = $rUmum['freq'];

            // jumlah data uji
            $sFrek29 = mysql_query("SELECT SUM(jumlah) AS frek from tbindex_hitung where id_kategori IS NULL");
            $rUji = mysql_fetch_array($sFrek29);
            $jumData = $rUji['frek'];
            
            // pwk tiap dokumen dengan kategori
            // insert to tabel tbindex_hitung
            $selAll = mysql_query("SELECT * FROM tbindex_hitung");
            while ($rAll = mysql_fetch_array($selAll)) {
                $id = $rAll['id_doc'];
                $term = $rAll['term'];

                $sFrekPol = mysql_query("SELECT SUM(jumlah) AS frek from tbindex_hitung where term = '".$term."' AND id_kategori = 1");
                while ($rFrekPol = mysql_fetch_array($sFrekPol)) {
                    $frekPol = $rFrekPol['frek'];
                    $pwkPol = ($frekPol + 1) / ($jumKataPol + $jumData);
                    // echo "$id - $term: ($frekPol + 1) / ($jumKataPol + $jumData) = $pwkPol<br> ";
                }
                $sFrekOlg = mysql_query("SELECT SUM(jumlah) AS frek from tbindex_hitung where term = '".$term."' AND id_kategori = 2");
                while ($rFrekOlg = mysql_fetch_array($sFrekOlg)) {
                    $frekOlg = $rFrekOlg['frek'];
                    $pwkOlg = ($frekOlg + 1) / ($jumKataOlg + $jumData);
                    // echo "$id - $term: ($frekOlg + 1) / ($jumKataOlg + $jumData) = $pwkOlg<br> ";
                }
                $sFrekPend = mysql_query("SELECT SUM(jumlah) AS frek from tbindex_hitung where term = '".$term."' AND id_kategori = 3");
                while ($rFrekPend = mysql_fetch_array($sFrekPend)) {
                    $frekPend = $rFrekPend['frek'];
                    $pwkPend = ($frekPend + 1) / ($jumKataPend + $jumData);
                    // echo "$id - $term: ($frekPend + 1) / ($jumKataPend + $jumData) = $pwkPend<br> ";
                }
                $sFrekOto = mysql_query("SELECT SUM(jumlah) AS frek from tbindex_hitung where term = '".$term."' AND id_kategori = 4");
                while ($rFrekOto = mysql_fetch_array($sFrekOto)) {
                    $frekOto = $rFrekOto['frek'];
                    $pwkOto = ($frekOto + 1) / ($jumKataOto + $jumData);
                    // echo "$id - $term: ($frekOto + 1) / ($jumKataOto + $jumData) = $pwkOto<br> ";
                }
                $sFrekUmum = mysql_query("SELECT SUM(jumlah) AS frek from tbindex_hitung where term = '".$term."' AND id_kategori = 5");
                while ($rFrekUmum = mysql_fetch_array($sFrekUmum)) {
                    $frekUmum = $rFrekUmum['frek'];
                    $pwkUmum = ($frekUmum + 1) / ($jumKataUmum + $jumData);
                    // echo "$id - $term: ($frekUmum + 1) / ($jumKataUmum + $jumData) = $pwkUmum<br> ";
                }

                mysql_query("UPDATE tbindex_hitung SET pvj = '".$pvjPol."' WHERE id_kategori = 1");
                mysql_query("UPDATE tbindex_hitung SET pvj = '".$pvjOlg."' WHERE id_kategori = 2");
                mysql_query("UPDATE tbindex_hitung SET pvj = '".$pvjPend."' WHERE id_kategori = 3");
                mysql_query("UPDATE tbindex_hitung SET pvj = '".$pvjOto."' WHERE id_kategori = 4");
                mysql_query("UPDATE tbindex_hitung SET pvj = '".$pvjUmum."' WHERE id_kategori = 5");

                mysql_query("UPDATE tbindex_hitung SET pwk_politik = '$pwkPol', pwk_olahraga = '$pwkOlg', pwk_pendidikan = '$pwkPend',
                         pwk_otomotif = '$pwkOto', pwk_umum = '$pwkUmum' WHERE id_doc = $id AND term = '$term'");
            }
            echo "<br><br>";



			// hitung Vmap Politik               
            $selUji = mysql_query("SELECT * FROM tbindex_hitung WHERE id_doc IN (SELECT id_doc FROM tbindex_hitung 
                                    WHERE id_kategori IS NULL) AND df > 1");
            $vmapPol = 1;
            $vmapOlg = 1;
            $vmapPend = 1;
            $vmapOto = 1;
            $vmapUmum = 1;
            while ($rowPwk = mysql_fetch_array($selUji)) {
	            $idUji = $rowPwk['id_doc'];
	            $tUji = $rowPwk['term'];
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

	            $hasilPol = $pvjPol * $vmapPol;
	            $hasilOlg = $pvjOlg * $vmapOlg;
	            $hasilPend = $pvjPend * $vmapPend;
	            $hasilOto = $pvjOto * $vmapOto;
	            $hasilUmum = $pvjUmum * $vmapUmum;

	            // echo "POLITIK --------  $idUji - $tUji : $pvjPol * $vmapPol = $hasilPol <br>";
	            // echo "OLAHRAGA --------  $idUji - $tUji : $pvjOlg * $vmapOlg = $hasilOlg <br>";
	            // echo "PENDIDIKAN --------  $idUji - $tUji : $pvjPend * $vmapPend = $hasilPend <br>";
	            // echo "OTOMOTIF --------  $idUji - $tUji : $pvjOto * $vmapOto = $hasilOto <br>";
	            // echo "UMUM --------  $idUji - $tUji : $pvjUmum * $vmapUmum = $hasilUmum <br>";

	            mysql_query("UPDATE tbindex_hitung SET vmap_politik = '$hasilPol', vmap_olahraga = '$hasilOlg',
	            			vmap_pendidikan = '$hasilPend', vmap_otomotif = '$hasilOto', vmap_umum = '$hasilUmum'
	            			WHERE id_doc = $idUji");
            }           
            echo "<br>";
            echo "<table class='table table-bordered' font='center' align='center'>
        	<tr>
                <th>ID Document</th>
                <th>VMap Politics</th>
                <th>Vmap Sport</th>
                <th>Vmap Education</th>
                <th>Vmap Automotive</th>
                <th>Vmap Daily News</th>
            </tr>";
            // menentukan kategori
            $selData = mysql_query("SELECT * FROM tbindex_hitung WHERE id_doc IN 
                                (SELECT id_doc FROM tbindex_hitung WHERE id_kategori IS NULL) GROUP BY id_doc"); 
            while ($rowData = mysql_fetch_array($selData)) {
                $iUji = $rowData['id_doc'];
                $pol = $rowData['vmap_politik'];
                $olg = $rowData['vmap_olahraga'];
                $pend = $rowData['vmap_pendidikan'];
                $oto = $rowData['vmap_otomotif'];
                $umum = $rowData['vmap_umum'];
                echo "<tr>
                        <td>$iUji</td>
                        <td>$pol</td>
                        <td>$olg</td>
                        <td>$pend</td>
                        <td>$oto</td>
                        <td>$umum</td>
                    </tr> </table>";

                if ($pol > $olg && $pol > $pend && $pol > $oto && $pol > $umum) {
                    echo "The greatest VMap value is $pol, then document with ID = $iUji was included in category <b>Politics</b><br>";
                    // mysql_query("UPDATE berita_baru set kategori_nb = 'Politik' WHERE id_berita_baru = $iUji");
                }
                elseif ($olg > $pol && $olg > $pend && $olg > $oto && $olg > $umum) {
                    echo "The greatest VMap value is $pol, then document with ID = $iUji was included in category <b>Sport</b><br>";
                    // mysql_query("UPDATE berita_baru set kategori_nb = 'Olahraga' WHERE id_berita_baru = $iUji");
                }
                elseif ($pend > $pol && $pend > $olg && $pend > $oto && $pend > $umum) {
                    echo "The greatest VMap value is $pol, then document with ID = $iUji was included in category <b>Education</b><br>";
                    // mysql_query("UPDATE berita_baru set kategori_nb = 'Pendidikan' WHERE id_berita_baru = $iUji");           
                }
                elseif ($oto > $pol && $oto > $pend && $oto > $olg && $oto > $umum) {
                    echo "The greatest VMap value is $pol, then document with ID = $iUji was included in category <b>Automotive</b><br>";
                    // mysql_query("UPDATE berita_baru set kategori_nb = 'Otomotif' WHERE id_berita_baru = $iUji");
                }
                elseif ($umum > $pol && $umum > $olg && $umum > $pend && $umum > $oto) {
                    echo "The greatest VMap value is $pol, then document with ID = $iUji was included in category <b>Daily News</b><br>";
                    // mysql_query("UPDATE berita_baru set kategori_nb = 'Umum' WHERE id_berita_baru = $iUji");
                }
            }
		}
	}
?>