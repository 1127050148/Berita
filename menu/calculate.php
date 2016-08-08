<?php
	include('../simple_html_dom.php');
	include('../koneksi/koneksi.php');

	class Calculate {
	    var $image;
	    var $fechanoticia;
	    var $title;
	    var $description;
	    var $sourceurl;

	    function get_image( ) {
	        return $this->image;
	    }

	    function set_image ($image, $new_image) {
	        $image->src = $new_image;
	    }

	    function get_fechanoticia( ) {
	        return $this->fechanoticia;
	    }

	    function set_fechanoticia ($new_fechanoticia) {
	        $this->fechanoticia = $new_fechanoticia;
	    }

	    function get_title( ) {
	        return $this->title;
	    }

	    function set_title ($new_title) {
	        $this->title = $new_title;
	    }

	    function get_description( ) {
	        return $this->description;
	    }

	    function set_description ($new_description) {
	        $this->description = $new_description;
	    }

	    function get_sourceurl( ) {
	        return $this->sourceurl;
	    }

	    function set_sourceurl ($new_sourceurl) {
	        $this->sourceurl = $new_sourceurl;
	    }

	    function process(){
			$this->cleanText();
			$this->processed = TRUE;
		}

	    function getText(){
			return $this->text;
		}

		function setText($text){
			$this->text = $text;
		}

	    function cleanText(){
			$searchReplace = array(
				// REMOVALS
				"'<script[^>]*?>.*?</script>'si" => " " // Strip Out Javascript
				, "'<style[^>]*?>.*?</style>'si" => " " // Strip out Styles
				, "'<[/!]*?[^<>]*?>'si" => " " // Strip out html tags
				// ACCEPT ONLY
				, "/[^a-zA-Z0-9\-' ]/" => " " //only accept these characters
				);
			foreach ($searchReplace as $s => $r) {
				$search[] = $s;
				$replace[] = $r;
			}
			$this->setText(utf8_encode($this->getText()));
			$this->setText(html_entity_decode($this->getText()));
			if ($this->convertToLower) {
				$this->setText(strtolower($this->getText()));
			}
			// $this->setText(strip_tags($this->text));
			// if(self::verbose) { echo "<hr>BEFORE<hr><pre>"; echo $this->getText(); echo "</pre>";}
         	$this->setText(preg_replace($search, $replace, $this->getText()));
         	// if(self::verbose) { echo "<hr>AFTER<hr><pre>"; print_r( preg_split('/\s+/',$this->getText()) ); echo "</pre>";}   
		}

		function removeStopWords($words){
			// expects an array ([0] = w1, [1] = w2, etc.)
			$numWordsIn = count($words);
			if (self::verbose) {
				echo "removeStopWords => wordcount (IN: ".$numWordsIn.") ";
			}
			if (file_exists(self::stop_words_file)) {
				$stopwords = explode("\n", strtolower(file_get_contents(self::stop_words_file)));
			}
			else{
				$stopwords = array("","-","ada","adalah","akan","aku","anda","antara","apa","apakah","apalagi",
					"atau","bagaimana","bagi","bagian","bahkan","bahwa","baru","beberapa","yang","juga","dari",
					"dia","kami","kamu","ini","itu","dan","tersebut","pada","dengan","yaitu");
			}
			// printa($stopWords);
			$words = array_diff($words, $stopwords);
			$words = array_values($words); // re-indexes array
			$numWordsOut = count($words);
			if (self::verbose) {
				echo " (OUT: ".$numWordsOut.") Removed: ".($numWordsIn-$numWordsOut)."<br/>";
				return $words;
			}
		}
	}

	$select = mysql_query("SELECT count(*) as jum from url");
	$jum = mysql_fetch_assoc($select); 

	if ($jum['jum'] > 0) {
		$query = mysql_query("SELECT * from url");
		while ($row = mysql_fetch_array($query)) {
			$link = $row['link'];
			$sumber = $row['sumber'];

			$html = file_get_html($link);

			if(!$html){
				continue;
			}
			else
			{
				$parsedNews = array();
				foreach($html->find('.kcm-read') as $element) {

					$newItem = new Calculate;
					// Parse the news item's thumbnail image.
		            foreach ($element->find('src') as $image) {
		                $property = 'img';
		                $image->removeAttribute('class');
		                $newItem->set_image($image , $image->$property);
		                // echo $newItem->get_image() . "<br />";
		                $linkgambar = $image->$property;
		            }

		            foreach ($element->find('href') as $link) {
		                $link->outertext = '';
		            }
		                
		            // Parse the news item's title.
		            foreach ($element->find('h2') as $title) {
		                $newItem->set_title($title->innertext);
		                $judul = $newItem->get_title(); echo $judul."<br /><br>";
		            }

		            foreach ($element->find('h2') as $link) {
		                $link->outertext = '';
		            }

		            foreach ($element->find('.meta') as $link) {
		                $link->outertext = '';
		            }

		            foreach ($element->find('p') as $text) {
		            	$newItem->set_description($text->innertext);
		            	$term = $newItem->get_description();

		            	$term = cleanText($term);
		            	// $term = strtolower($term);

		             //    //menghilangkan tanda baca
		             //    $term = str_replace(".", "", $term);
		     
		                // //Hapus stoplist
		                // $query = mysql_query("SELECT * from stopword");
		                // while ($daftar=mysql_fetch_array($query)) {
		                //     $stoplist = $daftar['daftar'];

		                //     $term = str_replace($stoplist, " ", $term);
		                // } echo $term."<br><br>";
		            }
		        }
		    }


			// function bacaHTML($url){
			//     // inisialisasi CURL
			//     $data = curl_init();
			//     // setting CURL
			//     curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
			//     curl_setopt($data, CURLOPT_URL, $url);
			//     // menjalankan CURL untuk membaca isi file
			//     $hasil = curl_exec($data);
			//     curl_close($data);
			//     return $hasil;
			// }
			// $kodeHTML = bacaHTML($url);
			// $pecah = explode('<kcm-read-text>', $kodeHTML);
			// $pecahLagi = explode('</div>', $pecah[1]);
			// echo $pecahLagi[0];
		}
	}	
?>