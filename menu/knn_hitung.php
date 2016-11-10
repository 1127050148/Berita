<?php

class Knn_hitung {

	public function tf ($term) {
		$tf = array_count_values(str_word_count($term, 1));
		return $tf;
	}

	 public function hitungBobot() {
            //hitung doc data latih
            $sqlJumDoc = mysql_query("SELECT DISTINCT id_doc from tbindex_hitung");
            $jumDoc = mysql_num_rows($sqlJumDoc);

            $sqlJumTerm = mysql_query("SELECT * FROM tbindex_hitung ORDER BY id_doc");
            while ($rowUji = mysql_fetch_array($sqlJumTerm)) {
                $term = $rowUji['term']; //echo "$term";
                $tf = $rowUji['jumlah'];
                $id = $rowUji['id_doc'];

                // jumlah dokumen dengan term tertentu
                $sqlTermInDoc = mysql_query("SELECT COUNT(*) as jumDoc from tbindex_hitung WHERE term = '".$term."'");
                $rowTerm = mysql_fetch_array($sqlTermInDoc);
                $nTerm = $rowTerm['jumDoc'];

                $w = $tf * (log10($jumDoc / $nTerm));
                // echo "$w<br>";

                // update bobot term di tabel tbindex_hitung
                $updatetbindex_hitung = mysql_query("UPDATE tbindex_hitung SET bobot = '".$w."', df = '$nTerm' WHERE id_doc = '".$id."' AND term = '$term'");
            }
        }

	public function knnProses(){
		$awal = microtime(TRUE);

		// perkalian data W-uji dengan semua bobot W dokumen
        $selectJumData = mysql_query("SELECT * FROM tbindex_hitung");
        while ($jumData = mysql_fetch_array($selectJumData)) {
            $id = $jumData['id_doc'];
            $dBobotLatih = $jumData['bobot'];
            $term = $jumData['term'];

            $sel29 = mysql_query("SELECT * FROM tbindex_hitung WHERE id_doc IN (SELECT id_doc FROM tbindex_hitung 
                                WHERE id_kategori IS NULL) AND term = '$term'");
            while ($row29 = mysql_fetch_array($sel29)) {
                $dBobotUji = $row29['bobot'];
                // $termUji = $row29['term'];
                if ($termUji = $term) {
                    $wPerDok = $dBobotUji * $dBobotLatih;
                    // echo "$id : $term => $dBobotUji * $dBobotLatih =  $wPerDok<br>";
                    mysql_query("UPDATE tbindex_hitung SET bobot_kali_uji = '".$wPerDok."' WHERE id_doc = '".$id."' AND term = '$term'");
                }
            }
        }
        // hitung semua penjumlahan dari data uji dan latih
        $aa = array();
        $bobot = array();
        $id = array();
        $selectData = mysql_query("SELECT * FROM tbindex_hitung");
        while ($y = mysql_fetch_array($selectData)) {
            $id = $y['id_doc'];
           
            $l = mysql_query("SELECT SUM(bobot_kali_uji) AS jum FROM tbindex_hitung WHERE id_doc = $id");
            while ($k = mysql_fetch_array($l)) {
                $bobot = $k['jum'];
                mysql_query("UPDATE tbindex_hitung SET jum_bobot_kali_uji = '".$bobot."' WHERE id_doc = '".$id."'");      
            }
            // echo "$id = $bobot<br>";
        }


		// bagian bobot data latih
        $select = mysql_query("SELECT * FROM tbindex_hitung");
        while ($latih = mysql_fetch_array($select)) {
            $termLatih = $latih['term'];
            $idLatih = $latih['id_doc'];
            $bobotLatih = $latih['bobot'];
            // perkalian untuk panjang vektor dari tiap bobot
            $hasilKaliVektor = $bobotLatih * $bobotLatih;
            // echo "$idLatih : $termLatih => $bobotLatih * $bobotLatih = $hasilKaliVektor<br>";
            mysql_query("UPDATE tbindex_hitung SET bobot_vektor = '".$hasilKaliVektor."' WHERE term = '".$termLatih."' AND id_doc = '".$idLatih."'");
        }
        // hitung semua jumlah dari perkalian vektor
        $jumDocLatih = mysql_query("SELECT id_doc, SUM(bobot_vektor) AS bobot_kuadrat FROM tbindex_hitung GROUP BY id_doc");
        while ($aa = mysql_fetch_array($jumDocLatih)) {
            $id = $aa['id_doc'];
            $bobot = $aa['bobot_kuadrat'];
            $sqrtBobotLatih = sqrt($bobot);
            // echo "$id : $bobot : $sqrtBobotLatih <br>";
            mysql_query("UPDATE tbindex_hitung SET jum_bobot_vektor = '".$bobot."', sqrt_bobot_vektor = '".$sqrtBobotLatih."' WHERE id_doc = '".$id."'");
        }


		// hitung cosine similarity
        $selCos = mysql_query("SELECT * FROM tbindex_hitung");
        $cos = array();
        while ($rowData = mysql_fetch_array($selCos)) {
            $id = $rowData['id_doc'];

            $selDataUji = mysql_query("SELECT sqrt_bobot_vektor, jum_bobot_kali_uji FROM tbindex_hitung WHERE id_doc = '".$id."' GROUP BY id_doc");
            while ($w = mysql_fetch_array($selDataUji)) {
                $akarVektorUji = $w['sqrt_bobot_vektor'];
                $jumBobotKaliUji = $w['jum_bobot_kali_uji'];

                $selJum = mysql_query("SELECT DISTINCT sqrt_bobot_vektor AS bobot FROM tbindex_hitung WHERE id_kategori IS NULL");
                $dt = mysql_fetch_array($selJum);
                $jum_bobot = $dt['bobot'];

                $cos = $jumBobotKaliUji / ($akarVektorUji * $jum_bobot);

                // echo "Cos(D$id) => $jumBobotKaliUji / ($akarVektorUji * $jum_bobot) = $cos <br>";
                mysql_query("UPDATE tbindex_hitung SET similarity = '".$cos."' WHERE id_doc = ".$id);
            }
        }
        echo "<br>";

		$k = 9;
        $no = 1;
        $dKat = array();
        echo "<table class='table table-bordered' font='center' align='center'>
        	<tr>
            	<th>NO</th>
                <th>ID Document</th>
                <th>Cosine Similarity</th>
                <th>ID Categories</th>
            </tr>";
            $selData = mysql_query("SELECT * FROM (SELECT * FROM tbindex_hitung WHERE id_kategori IS NOT NULL) a 
                                    GROUP BY id_doc ORDER BY similarity  DESC LIMIT $k");
            while ($rowData = mysql_fetch_array($selData)) {
                $id = $rowData['id_doc'];
                $similarity = $rowData['similarity'];
                $idKat = $rowData['id_kategori'];
                echo "<tr>
                        <td>$no</td>
                        <td>$id</td>
                        <td>$similarity</td>
                        <td>$idKat</td>
                    </tr>";
                    $no++;
            }
            echo "</table>";

            $selDatacosine = mysql_query("SELECT id_doc,id_kategori, COUNT(id_kategori) AS jum FROM (
                                        SELECT * FROM (SELECT * FROM tbindex_hitung WHERE id_kategori IS NOT NULL) 
                                        a GROUP BY id_doc ORDER BY similarity  DESC LIMIT $k)
                                        AS frek GROUP BY id_kategori ORDER BY jum ASC");
            while ($rCos = mysql_fetch_array($selDatacosine)) {
                $dKat = $rCos['id_kategori'];
                $id = $rCos['id_doc'];
                $jum = $rCos['jum'];     
            }
        	echo "<br>The most appears ID categori is ID category <b>$dKat</b><br><br>";
        	foreach ((array)$dKat as $key) {
                switch ($key) {
                    case '1':
                        echo "The most appears ID Categories is ID category <b>$key</b>, then the news was included in the category <b>Politics</b>";
                        // mysql_query("UPDATE berita_baru SET kategori_knn = 'Politik' WHERE id_berita_baru = ".$id);
                        break;
                    
                    case '2':
                        echo "The most appears ID Categories is ID category <b>$key</b>, then the news was includedin the category <b>Sport</b>";
                        // mysql_query("UPDATE berita_baru SET kategori_knn = 'Olahraga' WHERE id_berita_baru = ".$id);
                        break;

                    case '3':
                        echo "The most appears ID Categories is ID category <b>$key</b>, then the news was included in the category <b>Education</b>";
                        // mysql_query("UPDATE berita_baru SET kategori_knn = 'Pendidikan' WHERE id_berita_baru = ".$id);
                        break;

                    case '4':
                        echo "The most appears ID Categories is ID category <b>$key</b>, then the news was included in the category <b>Automotive</b>";
                        // mysql_query("UPDATE berita_baru SET kategori_knn = 'Otomotif' WHERE id_berita_baru = ".$id);                 
                        break;

                    case '5':
                        echo "The most appears ID Categories is ID category <b>$key</b>, then the news was included in the category <b>Daily News</b>";
                        // mysql_query("UPDATE berita_baru SET kategori_knn = 'Umum' WHERE id_berita_baru = ".$id);
                        break;
                }echo "<br>";
            }
        echo "<br>";
	}
}