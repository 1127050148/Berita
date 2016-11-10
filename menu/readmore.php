<div class="container">
	<?php 
        include "./koneksi/koneksi.php";
        $sel = mysql_query("SELECT * FROM berita_baru WHERE id_berita_baru = '$_GET[id]'");
    	while ($row = mysql_fetch_array($sel)) {
    ?>
			<div class="page-header">
		    	<h2><?php echo $row['judul_berita'];?></h2>
		    </div>
		    <div class="col-md-12">
		    	<div class="row">
		    		<p><?php echo $row['isi_berita'];?></p>
		    	</div>
		    </div>
	<?php
		}
	?>
</div>