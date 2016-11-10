<?php
	class preprocessing
	{
		private $dbHost = "localhost";
		private $dbUser = "root";
		private $dbPass = "";
		private $dbName = "db";
		public $data;

		function connectMysql() 
		{
			mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
			mysql_select_db($this->dbName) or die("Database Not Found!!!");
		}

		function text()
		{
		 	$query = "SELECT * FROM berita_baru";
			$result = mysql_query($query);
			while($row = mysql_fetch_array($result)) 
			{
		    	$text = $row['isi_berita'];
		    }
			return $text;
		}

		function pecah_kalimat()
		{
			$pecah = explode(".", $this->text());
			return $pecah;
		}

		function case_folding()
		{
			$looping_data = $this->pecah_kalimat();
			foreach ($looping_data as $key => $value) 
			{
				$input = preg_replace('@[?:;,./"+=!#()0-9]+@', " ", strtolower($value));
				$data[] = $input;
			}
			return $data;
		}

		function tokenizing()
		{
			$data = $this->case_folding();
			foreach ($data as $key => $values) 
			{
				$case[] = explode(" ", $values);
			}
			foreach ($case as $key => $value) 
			{
				$response[] = $value;
			}
			return $response;
		}

		function filtering() 
		{
			$data = $this->tokenizing();
			foreach ($data as $key => $value) 
			{
				$index_array = $this->array_empty_remover($value);
				$uniq = array_unique($index_array);
				$filtering[] = array_merge($uniq);
			}
			return $filtering;
		}

		function cekKamus($data) 
		{
			$sql = "SELECT * from kata_dasar where katadasar='$data' LIMIT 1";
			$result = pg_query($sql) or die(pr_error());
			if (pg_num_rows($result) == 1) 
			{
				return TRUE;
			}
			else 
			{
				return FALSE;
			}
		}

		//langkah 1 - hapus partikel
		function hapuspartikel($data)
		{
			if(cari($data)!=1)
			{
				if((substr($data, -3) == 'kah' )||( substr($data, -3) == 'lah' )||( substr($data, -3) == 'pun' ))
				{
					$data = substr($data, 0, -3);			
				}
			}
			return $data;
		}

		//langkah 2 - hapus possesive pronoun
		function hapuspp($data)
		{
			if(cari($data)!=1)
			{
				if(strlen($data) > 4)
				{
					if((substr($data, -2)== 'ku')||(substr($data, -2)== 'mu'))
					{
						$data = substr($data, 0, -2);
					}
				}
				else if((substr($data, -3)== 'nya'))
				{
					$data = substr($data,0, -3);
				}
			}
			return $data;
		}

		//langkah 3 hapus first order prefiks (awalan pertama)
		function hapusawalan1($data)
		{
			if(cari($data)!=1)
			{
				if(substr($data,0,4)=="meng")
				{
					if(substr($data,4,1)=="e"||substr($data,4,1)=="u")
					{
						$data = "k".substr($data,4);
					}
					else
					{
						$data = substr($data,4);
					}
				}
				else if(substr($data,0,4)=="meny")
				{
					$data = "s".substr($data,4);
				}
				else if(substr($data,0,3)=="men")
				{
					$data = substr($data,3);
				}
				else if(substr($data,0,3)=="mem"){
					if(substr($data,3,1)=="a" || substr($data,3,1)=="i" || substr($data,3,1)=="e" || substr($data,3,1)=="u" || substr($data,3,1)=="o")
					{
						$data = "p".substr($data,3);
					}
					else
					{
						$data = substr($data,3);
					}
				}
				else if(substr($data,0,2)=="me")
				{
					$data = substr($data,2);
				}
				else if(substr($data,0,4)=="peng")
				{
					if(substr($data,4,1)=="e" || substr($data,4,1)=="a")
					{
						$data = "k".substr($data,4);
					}
					else
					{
						$data = substr($data,4);
					}
				}
				else if(substr($data,0,4)=="peny")
				{
					$data = "s".substr($data,4);
				}
				else if(substr($data,0,3)=="pen")
				{
					if(substr($data,3,1)=="a" || substr($data,3,1)=="i" || substr($data,3,1)=="e" || substr($data,3,1)=="u" || substr($data,3,1)=="o")
					{
						$data = "t".substr($data,3);
					}
					else
					{
						$data = substr($data,3);
					}
				}
				else if(substr($data,0,3)=="pem")
				{
					if(substr($data,3,1)=="a" || substr($data,3,1)=="i" || substr($data,3,1)=="e" || substr($data,3,1)=="u" || substr($data,3,1)=="o")
					{
						$data = "p".substr($data,3);
					}
					else
					{
						$data = substr($data,3);
					}
				}
				else if(substr($data,0,2)=="di")
				{
					$data = substr($data,2);
				}
				else if(substr($data,0,3)=="ter")
				{
					$data = substr($data,3);
				}
				else if(substr($data,0,2)=="ke")
				{
					$data = substr($data,2);
				}
			}
			return $data;
		}
		//langkah 4 hapus second order prefiks (awalan kedua)
		function hapusawalan2($data)
		{
			if(cari($data)!=1)
			{
				if(substr($data,0,3)=="ber")
				{
					$data = substr($data,3);
				}
				else if(substr($data,0,3)=="bel")
				{
					$data = substr($data,3);
				}
				else if(substr($data,0,2)=="be")
				{
					$data = substr($data,2);
				}
				else if(substr($data,0,3)=="per" && strlen($data) > 5)
				{
					$data = substr($data,3);
				}
				else if(substr($data,0,2)=="pe"  && strlen($data) > 5)
				{
					$data = substr($data,2);
				}
				else if(substr($data,0,3)=="pel"  && strlen($data) > 5)
				{
					$data = substr($data,3);
				}
				else if(substr($data,0,2)=="se"  && strlen($data) > 5)
				{
					$data = substr($data,2);
				}
			}
			return $data;
		}
		////langkah 5 hapus suffiks
		function hapusakhiran($data)
		{
			if(cari($data)!=1)
			{
				if (substr($data, -3)== "kan" )
				{
					$data = substr($data, 0, -3);
				}
				else if(substr($data, -1)== "i" )
				{
				    $data = substr($data, 0, -1);
				}
				else if(substr($data, -2)== "an")
				{
					$data = substr($data, 0, -2);
				}
			}	
			return $data;
		}

		function stemming($data)
		{
			$teksAsli = $data;
			$length = strlen($teksAsli);
			$pattern = '[A-Za-z]';
			$data = '';
			if (eregi($pattern, $teksAsli)) 
			{
				$data = $teksAsli;
				$stemming = NAZIEF($data);
				$data = '';
			}
		}

		function tfidf()
		{
			$idf = array();
			foreach ($this->terms as $term => $value) {
				$D = 0;
				$dfi = 0;
				foreach ($this->kalimat as $kalKey => $kalimat) {
					$this->tf[$kalKey][$term] = 0;

					$found = FALSE;
					$string = explode(" ", $kalimat);
					foreach ($string as $str) {
						if (strcmp($str, $term) == 0) {
							$this->tf[$kalKey][$term]++;
							$dfi++;
							$found = TRUE;
						}
					}
					$D++;
				}
				if ($dfi > 0) {
					$idf[$term] = log10($D / $dfi);
				}
				echo "<br />IDF (".$term.") = log(".$D." / ".$dfi.") = ".$idf[$term];
			}
			$this->idf = $idf;
			return TRUE;
		}

		function queryRelevance()
		{
			echo "<br />";
			$denom = 0;
			$tf = $this->tf;
			$idf = $this->idf;

			$denom_idf = 0;
			foreach ($$this->term as $term) {
				$denom_idf += ($idf[$term] * $idf[$term]);
			}
			$denom_idf = sqrt($denom_idf);
			foreach ($$this->kalimat as $keyKal => $kalimat) {
				echo "<br />W (S".($keyKal+1).") = ";
				$denom_kal = 0;
				$nom = 0;
				foreach ($this->term as $term) {
					$denom_kal += ($tf[$keyKal][$term] * $tf[$keyKal][$term]);
					$nom += ($idf[$term] * $tf[$keyKal][$term]);
				}
				$denom_kal = sqrt($denom_kal);
				$denom = $denom_idf * $denom_kal;
				echo "(".$nom." / ".$denom.") = ";

				if ($denom != 0) {
					$this->W[$keyKal] = $nom / $denom;
				}
				else {
					$this->W[$keyKal] = 0;
				}
				echo $this->W[$keyKal];
			}
			return TRUE;
		}

		function cosineSimilarity()
		{
			echo "<br />";
			$cs = array();
			$tf = $this->tf;
			$denom_base = array();

			foreach ($this->kalimat as $key => $kal) {
				$denom_kal = 0;
				foreach ($this->terms as $term) {
					$denom_kal += ($tf[$key][$term] * $tf[$key][$term]);
				}
				$denom_kal = sqrt($denom_kal);
				$denom_base[$key] = $denom_kal;
			}
			foreach ($this->kalimat as $keyX => $kalX) {
				foreach ($this->kalimat as $keyY => $kalY) {
					$nom = 0;
					$denom_kal = 0;
					foreach ($this->terms as $term) {
						$denom_kal += ($tf[$keyY][$term] * $tf[$keyY][$term]);
						$nom += ($tf[$keyX][$term] * $tf[$keyX][$term]);
					}
					$denom_kal = sqrt($denom_kal);
					$denom = $denom_base[$keyX] * $denom_kal;

					if ($denom != 0) {
						$cs[$keyX][$keyY] = $nom / $denom;
					}
					else {
						$cs[$keyX][$keyY] = 0;
					}
				}
			}
			$this->cs = $cs;

			echo "<br /> Cosine Similarity<br />";
			echo "<table border=1> <tr><th>&nbsp;</th>";
			foreach ($this->cs as $n => $cs) {
				echo "<th>S".($n+1)."</th>";
			}
			echo "</tr>";

			foreach ($this->cs as $x => $cx) {
				echo "<tr><td>S".($x+1)."</td>";
				foreach ($cx as $y => $cy) {
					echo "<td>".$cy."</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
			return TRUE;
		}
	}
	
	$ir = new preprocessing();
	$data = $ir->connectMysql();
	foreach ($data->result() as $key) {
		
	}
	echo $ir->text()."<br /><br />";
?>