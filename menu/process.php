<?php
	include('../simple_html_dom.php');
	include('../koneksi/koneksi.php');

	session_start();
	$curl = $_POST['url'];

	class process {
	    var $image;
	    var $fechanoticia;
	    var $title;
	    var $description;
	    var $sourceurl;
	    var $index;
	    var $tagName;
	    private $xmlParser = null;
	    private $insideItem = array();
	    private $currentTag = null;
	    private $currentAttr = null;
	    private $namespaces = array('http://rss.detik.com/index.php/indeks'	=> 'RSS 1.0',
									'http://rss.viva.co.id/get/all'			=> 'RSS 2.0',
									'http://www.liputan6.com/feed/rss'		=> 'RSS 3.0',
									'http://www.kompas.com'					=> 'ATOM 1',
									'http://www.merdeka.com'				=> 'ATOM 2',
									);
	    private $itemTags = array('ITEM', 'ENTRY');
	    private $ChannelTags = array('CHANNEL', 'FEED');
	    private $dateTags = array('UPDATED', 'PUBDATE', 'DC:DATE');
	    private $hasSubTags = array('IMAGE', 'AUTHor');
	    private $channels = array();
	    private $items = array();
	    private $itemIndex = 0;
	    private $url = null;
	    private $version = null;

	    function __construct()
	    {
	    	$this->xmlParser = xml_parser_create();
	    	xml_set_object($this->xmlParser, $this);
	    	xml_set_element_handler($this->xmlParser, "startElement", "endElement");
	    	xml_set_character_data_handler($this->xmlParser, "characterData");
	    }

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

	    public function getChannels(){
	    	return $this->channels;
	    }

	    public function getItems(){
	    	return $this->items;
	    }

	    public function getTotalItems(){
	    	return count($this->items);
	    }

	    public function getItem($index){
	    	if ($index < $this->getTotalItems()) {
	    		return $this->items[$index];
	    	}
	    	else{
	    		throw new Exception("Item index is larger then total items!!!");
	    		return false;
	    	}
	    }

	    public function getChannel($tagName){
	    	if (array_key_exists(strtoupper($tagName), $this->channels)) {
	    		return $this->channels[strtoupper($tagName)];
	    	}
	    	else{
	    		throw new Exception("Channel tag $tagName not found!!!");
	    		return false;
	    	}
	    }

	    public function getParsedUrl(){
	    	if (empty($this->url)) {
	    		throw new Exception("Feed URL is not set yet!!!");
	    		return FALSE;
	    	}
	    	else{
	    		return $this->url;
	    	}
	    }

	    public function getFeedVersion(){
	    	return $this->version;
	    }

	    public function parse($url){
	    	$this->url = $url;
	    	$URLContent = $this->getUrlContent();

	    	if ($URLContent) {
	    		$segments = str_split($URLContent, 4096);
	    		foreach ($segments as $index => $data) {
	    			$lastPiece = ((count($segments)-1) == $index)? true : false;
	    			xml_parse($this->xmlParser, $data, $lastPiece)
	    				or die(sprintf("XML error: %s at line %d",
	    				xml_error_string(xml_get_error_code($this->xmlParser)),
	    				xml_get_current_line_number($this->xmlParser)));
	    		}
	    		xml_parser_free($this->xmlParser);
	    	}
	    	else{
	    		die("Sorry!!! Cannot load the feed URL.");
	    	}

	    	if (empty($this->version)) {
	    		die("Sorry!!! Cannot detect the feed version");
	    	}
	    }

	    private function getUrlContent(){
	    	if (empty($this->url)) {
	    		throw new Exception("URL to parse is empty!!!");
	    		return false;
	    	}

	    	if ($content = @file_get_contents($this->url)) {
	    		return $content;
	    	}
	    	else{
	    		$ch = curl_init();
	    		curl_setopt($ch, CURLOPT_URL, $this->url);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$content    = curl_exec($ch);
				$error      = curl_error($ch);

				curl_close($ch);
				if (empty($error)) {
					return $content;
				}
				else{
					throw new Exception("Error occured while loading url by cURL!!!.<br />\n".$error);
					return false;					
				}
	    	}
	    }

	    private function startElement($parser, $tagName, $attrs){
	    	if (!$this->version) {
	    		$this->findVersion($tagName, $attrs);
	    	}

	    	array_push($this->insideItem, $tagName);
	    	$this->currentTag = $tagName;
	    	$this->currentAttr = $attrs;
	    }

	    private function endElement($parser, $tagName){
	    	if (in_array($tagName, $this->itemTags)) {
	    		$this->itemIndex++;
	    	}

	    	array_pop($this->insideItem);
	    	$this->currentTag = $this->insideItem[count($this->insideItem)-1];
	    }

	    private function characterData($parser, $data) {
	    	if(in_array($this->currentTag, $this->dateTags)) {
				$data = strtotime($data);
			}
					 
		   if($this->inChannel()) {
				// If has subtag, make current element an array and assign subtags as it's element
				if(in_array($this->getParentTag(), $this->hasSubTags)) {
					if(! is_array($this->channels[$this->getParentTag()])) {
						$this->channels[$this->getParentTag()] = array();
					}

					$this->channels[$this->getParentTag()][$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
					return;
				}
				else {
					if(! in_array($this->currentTag, $this->hasSubTags)) {
						$this->channels[$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
					}
				}
						   
				if(!empty($this->currentAttr)) {
					$this->channels[$this->currentTag . '_ATTRS'] = $this->currentAttr;          
					
					//If the tag has no value
					if(strlen($this->channels[$this->currentTag]) < 2) {
						//If there is only one attribute, assign the attribute value as channel value
						if(count($this->currentAttr) == 1) {
							foreach($this->currentAttr as $attrVal) {
								$this->channels[$this->currentTag] = $attrVal;
							}
						}
						//If there are multiple attributes, assign the attributs array as channel value
						else
						{
							$this->channels[$this->currentTag] = $this->currentAttr;
						}                        
					}
				}
			}
			elseif ($this->inItem()) {
				if (in_array($this->getParentTag(), $this->hasSubTags)) {
					if (! is_array($this->items[$this->itemIndex][$this->getParentTag()])) {
						$this->items[$this->itemIndex][$this->getParentTag()] = array();
					}

					$this->items[$this->itemIndex][$this->getParentTag()][$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
					return;
				}
				else{
					if (! in_array($this->currentTag, $this->hasSubTags)) {
						$this->items[$this->itemIndex][$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
					}
				}

				if (!empty($this->currentAttr)) {
					$this->items[$this->itemIndex][$this->currentTag . '_ATTRS'] = $this->currentAttr;

					if (strlen($this->items[$this->itemIndex][$this->currentTag]) < 2) {
						if (count($this->currentAttr) == 11) {
							foreach ($this->currentAttr as $attrVal) {
								$this->items[$this->itemIndex][$this->currentTag] = $attrVal;
							}
						}
						else{
							$this->items[$this->itemIndex][$this->currentTag] = $this->currentAttr;
						}
					}					
				}
			}
	    }

	    private function findVersion($tagName, $attrs){
	    	$namespaces = array_values($attrs);
	    	foreach ($this->namespaces as $value => $version) {
	    		if (in_array($value, $namespaces)) {
	    			$this->version = $version;
	    			return;
	    		}
	    	}
	    }

	    private function getParentTag(){
	    	return $this->insideItem[count($this->insideItem) - 2];
	    }

	    private function inChannel(){
	    	if ($this->version == 'RSS 1.0') {
	    		if (in_array("CHANNEL", $this->insideItem) && $this->currentTag != 'CHANNEL') {
	    			return TRUE;
	    		}
	    	}
	    	elseif ($this->version == 'RSS 2.0') {
	    		if (in_array('CHANNEL', $this->insideItem) && !in_array('ITEM', $this->insideItem) && $this->currentTag != 'CHANNEL') {
	    			return TRUE;
	    		}
	    	}
	    	elseif ($this->version == 'ATOM 1') {
	    		if (in_array('FEED', $this->insideItem) && !in_array('ENTRY', $this->insideItem) && $this->currentTag != 'FEED') {
	    			return TRUE;
	    		}
	    	}
	    	return FALSE;
	    }

	    private function inItem(){
	    	if ($this->version == 'RSS 1.0' || $this->version == 'RSS 2.0') {
	    		if (in_array('ITEM', $this->insideItem) && $this->currentTag != 'ITEM') {
	    			return TRUE;
	    		}
	    	}
	    	elseif ($this->version = 'ATOM 1') {
	    		if (in_array('ENTRY', $this->insideItem) && $this->currentTag != 'ENTRY') {
	    			return TRUE;
	    		}
	    	}
	    	return FALSE;
	    }

	    private function unhtmlentities($string){
	    	$trans_tbl = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
	    	$trans_tbl = array_flip($trans_tbl);
	    	$trans_tbl += array('&apos;' => "'");
	    	return strtr($string, $trans_tbl);
	    }
	}


	$html = file_get_html($url);
	if(!$html){
		continue;
	}
	else
	{
		$parsedNews = array();
		foreach($html->find('.kcm-read') as $element) {

			$newItem = new process;
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
                $judul = $newItem->get_title();
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

            	$term = strip_tags($term);
            	$term = strtolower($term);

                //menghilangkan tanda baca
                $term = str_replace(".", "", $term);
                $term = str_replace(",", "", $term);
                $term = str_replace("'", "", $term);
                $term = str_replace("-", "", $term);
                $term = str_replace(")", "", $term);
                $term = str_replace("(", "", $term);
                $term = str_replace("\'", "", $term);
                $term = str_replace("/", "", $term);
                $term = str_replace("=", "", $term);
                $term = str_replace(":", "", $term);
                $term = str_replace("!", "", $term);
                $term = str_replace("?", "", $term);
                $term = str_replace("*", "", $term);
                $term = str_replace("&", "", $term);
                $term = str_replace("%", "", $term);
                $term = str_replace(";", "", $term);
                $term = str_replace("nbsp", "", $term);
                $term = str_replace("—", "", $term);

                //Hapus stoplist
                $query = mysql_query("SELECT * from stopword");
                while ($daftar=mysql_fetch_array($query)) {
                    $stoplist = $daftar['daftar'];

                    $term = str_replace($stoplist, " ", $term);
                } echo $term."<br><br>";
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
?>