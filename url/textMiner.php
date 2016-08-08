<?php
	include('../simple_html_dom.php');
	include('../koneksi/koneksi.php');

	class TextMiner{
		public $removeStopWords;
		public $convertToLower;
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

		function getElementKompas($kompas){
			foreach ($kompas->find('.kcm-read') as $element) {
				foreach ($element->find('p') as $text) {
					$this->set_description($text->innertext);
            		$term = $this->get_description();
				}
			}
		}




		public function addText($text){
			$this->text .= ' || '.$text;
		}

		public function addFile($fileHTML){
			$text = file_get_html($fileHTML);
		}

		public function clear(){
			$this->text = '';
			$this->processed = FALSE;
			$this->removeStopWords = TRUE;
			$this->convertToLower = FALSE;
		}

		public function process(){
			$this->cleanText();
			$this->processed = TRUE;
		}

		public function setText($text){
			$this->text = $text;
		}

		public function getText(){
			return $this->text;
		}

		// PRIVATE METHODS
		private function cleanText(){
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

		// STATIC METHODS - can be called without having an instance of TextMiner
		// removeStopWords: removes the stopwords from a referenced array
		public static function removeStopWords($words){
			// expects an array ([0] = w1, [1] = w2, etc.)
			$numWordsIn = count($words);
			if (self::verbose) {
				echo "removeStopWords => wordcount (IN: ".$numWordsIn.") ";
			}
			if (file_exists(self::stop_words_file)) {
				$stopwords = explode("\n", strtolower(file_get_contents(self::stop_words_file)));
			}
			else{
				$sw = mysql_query("SELECT * from stopword");
				while ($r=mysql_fetch_array($sw)) {
					$stopwords = $r['daftar'];
				}
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

	$query = mysql_query("SELECT * from url");
	while ($r = mysql_fetch_array($query)) {
		$url = $r['link'];

		$key = new TextMiner;
		$get = $key->addFile($url);
		$process = $key->process(); echo $process;
	}
?>