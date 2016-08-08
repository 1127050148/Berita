<div class="post">
	<div class="container">
		<!-- Example row of columns -->
    	<div class="row">
        	<div class="col-md-4">
            	<?php
                	include './koneksi/koneksi.php';
					
					$sql=mysql_query("select * from berita order by id_berita desc limit 6");
					while($isi=mysql_fetch_array($sql))
					{
				?>		<h2><?php echo $isi['judul_berita']?></h2>
                		<p><?php echo $isi['tanggal']?></p>
                        <h4><?php echo $isi['sumber']?></h4>
                        <p align="justify"></p>
						<?php 
							echo $berita= substr($isi['isi_berita'],0,300);
                        	$berita = substr($isi['isi_berita'],0,strrpos($berita,""));
						?>
                <p><a class="btn btn-default" href="?t=details&id=<?php echo $isi['id_berita'] ?>" role="button">View details &raquo;</a></p>
                	<?php } ?>
			</div>
		</div>
	</div>
</div>