<?php include "./koneksi/koneksi.php"; ?>
<div class="container">
	<div class="page-header">
    	<h3>K-Nearest Neighbour</h3>
    </div>
    <div class="col-md-12">
    	<div class="row" style="padding: 3px; overflow: auto; width: 1000; height: 500px; border: 1px solid grey">
    		<table class="table table-bordered" font="center" align="center">
    			<tr>
		 			<th>Term</th>
		 			<th>ID Doc. Testing</th>
		 			<th>Weight</th>
		 			<th>ID Doc. Training</th>
		 			<th>Weight</th>
		 			<th>WDi * WD Testing</th>
                    <th>Cosine Similarity</th>
		 		</tr>
		 		<?php 
                    $sel = mysql_query("SELECT * FROM cosine");
                    while ($row = mysql_fetch_array($sel)) {
                ?>
		 			<tr>
                        <td><?php echo $row['term_uji'];?></td>
                        <td><?php echo $row['id_doc_uji'];?></td>
                        <td><?php echo $row['bobot_dok_uji'];?></td>
                        <td><?php echo $row['id_doc_latih'];?></td>
                        <td><?php echo $row['bobot_dok_latih'];?></td>
                        <td><?php echo $row['bobot_kali_dok'];?></td>
                        <td><?php echo $row['similarity'];?></td>
		 			 </tr>
		 		<?php } ?>		 		
    		</table>
    	</div><br>
    </div>
    <div class="col-md-12">
    	<div class="row" style="padding: 3px; overflow: auto; width: 100; height: 300px; border: 1px solid grey">
    		&nbsp; <b>K-Nearest Neighbour Result</b><hr>
            <table class="table table-bordered" font="center" align="center">
                <tr>
                    <th>ID Doc. Testing</th>
                    <th>Category Result</th>
                </tr>
                <?php 
                    $sel = mysql_query("SELECT * FROM berita_baru WHERE kategori_knn = 'politik' OR kategori_knn = 'umum'
                                    OR kategori_knn = 'pendidikan' OR kategori_knn = 'otomotif' OR kategori_knn = 'olahraga'");
                    while ($row = mysql_fetch_array($sel)) {
                ?>
                <tr>
                    <td><?php echo $row['id_berita_baru'];?></td>
                    <td><?php echo $row['kategori_knn'];?></td>
                </tr
                <?php 
                    }
                ?> 
    		?>
            </table>
    	</div><br><br>
    </div>

    <div class="page-header">
    	<h3>Naive Bayes</h3>
    </div>
    <div class="col-md-12">
    	<div class="row" style="padding: 3px; overflow: auto; width: 1000; height: 500px; border: 1px solid grey">
    		<table class="table table-bordered" font="center" align="center">
    			<!-- <tr>
    				<th colspan = 4>Data Training</th>
    				<th colspan = 4>Data Testing</th>
    			</tr> -->
    			<tr>
		 			<th>Term</th>
		 			<th>ID Doc. Training</th>
		 			<th>PVj</th>
		 			<th>PWk Politics</th>
                    <th>PWk Sport</th>
                    <th>PWk Education</th>
                    <th>PWk Automotive</th>
                    <th>PWk Daily</th>
                    <th>ID Doc. Testing</th>
                    <th>VMap Politics</th>
                    <th>VMap Sport</th>
                    <th>VMap Education</th>
                    <th>VMap Automotive</th>
                    <th>VMap Daily</th>
		 		</tr>
		 		<?php 
                    $sel = mysql_query("SELECT * FROM naive_bayes");
                    while ($rowNB = mysql_fetch_array($sel)) { 
                ?>
		 			<tr>
		 			    <td><?php echo $rowNB['term_latih'];?></td>   		
                        <td><?php echo $rowNB['id_doc_latih'];?></td>
                        <td><?php echo $rowNB['pvj'];?></td>
                        <td><?php echo $rowNB['pwk_politik'];?></td>
                        <td><?php echo $rowNB['pwk_olahraga'];?></td>
                        <td><?php echo $rowNB['pwk_pendidikan'];?></td>
                        <td><?php echo $rowNB['pwk_otomotif'];?></td>
                        <td><?php echo $rowNB['pwk_umum'];?></td>
                        <td><?php echo $rowNB['id_doc_uji'];?></td>
                        <td><?php echo $rowNB['vmap_politik'];?></td>
                        <td><?php echo $rowNB['vmap_olahraga'];?></td>
                        <td><?php echo $rowNB['vmap_pendidikan'];?></td>
                        <td><?php echo $rowNB['vmap_otomotif'];?></td>
                        <td><?php echo $rowNB['vmap_umum'];?></td>
		 			</tr>
		 		<?php } ?>		 		
    		</table>
    	</div><br>
    </div>
    <div class="col-md-12">
    	<div class="row" style="padding: 3px; overflow: auto; width: 1000; height: 300px; border: 1px solid grey">
    		&nbsp; <b>Naive Bayes Result</b><hr>
            <table class="table table-bordered" font="center" align="center">
                <tr>
                    <th>ID Doc. Testing</th>
                    <th>Category Result</th>
                </tr>
                <?php 
                    $sel = mysql_query("SELECT * FROM berita_baru WHERE kategori_nb = 'politik' OR kategori_nb = 'umum'
                                    OR kategori_nb = 'pendidikan' OR kategori_nb = 'otomotif' OR kategori_nb = 'olahraga'");
                    while ($row = mysql_fetch_array($sel)) {
                ?>
                <tr>
                    <td><?php echo $row['id_berita_baru'];?></td>
                    <td><?php echo $row['kategori_nb'];?></td>
                </tr
                <?php 
                    }
                ?> 
            ?>
            </table>
    	</div><br><br>
    </div>
</div>