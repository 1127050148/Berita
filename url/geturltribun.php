<?php
	include('../simple_html_dom.php');
	include('../koneksi/koneksi.php');

	class htmlDOMTribun {
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

	$html = file_get_html('http://api.tribunnews.com/rss');

	if(!$html){
		continue;
	}
	else{
		$parsedNews = array();
		// echo "<table border=1><tr><td>Judul</td><td>Deskripsi</td></tr>";
		foreach($html->find('item') as $element) {
			$newItem = new htmlDOMTribun;
			
            foreach ($element->find('guid') as $link) {
                $newItem->set_link($link->innertext);
                $url = $newItem->get_link();
            	// echo $url."<br><br>";
            }
            	echo "<table border=1><tr><td>Link</td></tr>
           	<tr><td>$url</td></tr></table><br><br><br>";
            // $insert = "INSERT IGNORE INTO url (link,sumber) values('".$url."','tribunnews.com')";
           	// mysql_query($insert) or die ("tidak dapat memasukkan data ke tabel");
        }
    }
?>