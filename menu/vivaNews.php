<div class="container">
      <!-- Example row of columns -->
    <div class="page-header">
        <h3> News From <b>viva.co.id</b></h3>
    </div>

    <div class="row">
        <?php 
            include "./koneksi/koneksi.php";
            $sel = mysql_query("SELECT berita_baru.* , url.sumber AS sumber  FROM berita_baru JOIN url ON berita_baru.id_link = url.id_link WHERE url.sumber = 'viva.co.id'");
            while ($row = mysql_fetch_array($sel)) {
        ?>
        <div class="col-md-4">
          
          <h2><?php echo $row['judul_berita'];?></h2>
                <p><?php echo substr($row['isi_berita'], 0, 200);?></p>
                <p><a class="btn btn-default" href="?t=readmore&id=<?php echo $row['id_berita_baru'] ?>" role="button">View details &raquo;</a></p>
        </div>
        <?php 
            }
        ?>
      </div>

      <hr>
      <center>
        <footer>
          <p>&copy; Siti Nurpadilah (1127050148).</p>
        </footer>
      </center>
</div> <!-- /container -->
