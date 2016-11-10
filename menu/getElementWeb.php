<?php
	include('./simple_html_dom.php');

	class GetElementWeb {
	    var $title;
	    var $description;
	    var $sourceurl;

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
	}
?>