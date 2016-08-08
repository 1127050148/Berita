<div class="container">
	<div class="entry">
    	<?php 
			include 'koneksi/koneksi.php';
			$sql = mysql_query("select * from berita where id_berita='$_GET[id]'");
			while($isi=mysql_fetch_array($sql))
			{
		?>
        		<h2><?php echo $isi['judul_berita']?></h2>
                <p><?php echo $isi['tanggal']?></p>
                <h4><?php echo $isi['sumber']?></h4>
                <p align="justify">
                	<?php echo $isi['isi_berita']?>
                </p>
        	<?php } ?>
    </div>
</div><!-- /.container -->