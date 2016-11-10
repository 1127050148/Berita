<div class="container">
      <!-- Example row of columns -->
      <div class="row">
          <!--<?php 
              foreach ($daftar as $key) {
          ?>
           <div class="col-md-4 ">      
              <h2><?php echo $key['judul_berita']; ?></h2>
              <h5>Sumber: <b> <?php echo $key['sumber']; ?></b></h5>
              <hr>
              <p><?php echo substr($key['isi_berita'], 0, 200); ?></p>
              <p><a class="btn btn-default" href="<?php echo base_url(); ?>home/readmore/<?php echo $key['id_berita_baru'] ?>" role="button">View details &raquo;</a></p>
          </div> 
          <?php 
              }
          ?> -->
          <div class="page-header">
              <h1>HOME</h1>
          </div>
          <div class = "col-md-8">
              <h3>Welcome</h3>
              <br>
              <p>Online News Categorization Application is an application
                that is used to determine the category of news on Internet (online news).
                To determine news category is used K-Neares Neighbour method and Naive Bayes
                method. Both of methods wil be compared its performance to determine
                which method is more effective to process the text category.</p>
              <br>
              <p>(Aplikasi pengkategorian berita online adalah aplikasi yang digunakan untuk menentukan 
                kategori berita yang ada di internet (berita online). untuk menentukan kategori ini 
                digunakan metode knn dan naive bayes. dari kedua metode tersebut akan dibandingkan kinerjanya 
                untuk menentukan metode mana yang lebih efektif untuk proses kategori teks.)
              </p>
          </div>
      </div>

      <hr>
      <center>
          <footer>
              <p>&copy; Siti Nurpadilah (1127050148).</p>
          </footer>
      </center>
</div> <!-- /container -->