<?php
	include('../simple_html_dom.php');
	include('../koneksi/koneksi.php');

	class htmlDOMKompas {
	    var $image;
	    var $fechanoticia;
	    var $title;
	    var $description;
	    var $sourceurl;
	    var $new_link;

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

	    function get_link( ) {
	        return $this->link;
	    }

	    function set_link($new_link) {
	        $this->link = $new_link;
	    }
	}

	$html = file_get_html('http://www.kompas.com/');

	if(!$html){
		continue;
	}
	else{
		$parsedNews = array();
		// echo "<table border=1><tr><td>Judul</td><td>Deskripsi</td></tr>";
		foreach($html->find('div#populer') as $element) {
			$newItem = new htmlDOMKompas;
			
            foreach ($element->find('div.most__title a') as $link) {
                $newItem->set_link($link->href);
                $url = $newItem->get_link();
            	// echo $url."<br><br>";
            	$insert = "INSERT INTO url (link,sumber) values('".$url."','kompas.com')";
           		mysql_query($insert) or die ("tidak dapat memasukkan data ke tabel");
            }
        }
    }
?>