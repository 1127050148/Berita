<?php
    // include "./koneksi/koneksi.php";
    $conn = mysql_connect("localhost","root","");
    if (!$conn) die ("Koneksi gagal");
    mysql_select_db("db_berita",$conn) or die ("Database tidak ditemukan");
    
    include "preprocessing.php";
    include "getElementWeb.php";
    include "knn_hitung.php";
    include "naiveBayes_hitung.php";
    require "stem.php";
    session_start();
    $url = $_POST['curl'];
    $sumber = $_POST['sumber'];

    $newItem = new GetElementWeb();
    $ir = new Preprocessing();
    $stem = new Stem();
    $knn = new Knn_hitung();
    $nb = new NaiveBayes_hitung();
    $parsedNews = array();
    $html = file_get_html($url);
    // $html = file_get_html("http://news.detik.com/read/2016/07/25/105950/3260305/10/menanti-eksekusi-mati-freddy-pencopet-yang-jadi-gembong-narkoba-kelas-wahid");
    // $html = file_get_html("http://otomotif.news.viva.co.id/news/read/834386-apes-mobil-sport-masuk-got-saat-dites-calon-pembeli");
    // $html = file_get_html("http://nasional.kompas.com/read/2016/08/02/06571811/megawati.di.sekitar.jokowi.?utm_source=WP&utm_medium=box&utm_campaign=Kpopwp");
    // $html = file_get_html("http://showbiz.liputan6.com/read/2567567/kata-ibunda-tentang-kekasih-mike-mohede?medium=Headline&amp;campaign=Headline_click_5");
    // $html = file_get_html("http://www.tribunnews.com/superskor/2016/08/04/barcelona-permalukan-juara-liga-inggris");
    if(!$html){
        // continue;
    }
    else
    {
        if ($sumber == "detik") { 
            foreach($html->find('article') as $element) {                   
            // Parse the news item's title.
                foreach ($element->find('.jdl') as $title) {
                    $newItem->set_title($title->innertext);
                    $judul = $newItem->get_title(); 
                }
                foreach ($element->find('.detail_text') as $text) {
                    $newItem->set_description($text->innertext);
                    $term = $newItem->get_description();    
                }
            }
        }
        // echo "$judul<br><br>";
        // echo "$term";
        elseif ($sumber == "viva") {
            foreach($html->find('article') as $element) {    // bener semua yg viva               
            // Parse the news item's title.
                foreach ($element->find('.title') as $title) {
                    $newItem->set_title($title->innertext);
                    $judul = $newItem->get_title(); 
                }
                foreach ($element->find('span') as $text) {
                    $newItem->set_description($text->innertext);
                    $term = $newItem->get_description();    
                }
            }
            // echo "<b>$judul<br><br></b>$term";
        }
        elseif ($sumber == "kompas") {
            foreach($html->find('.kcm-read') as $element) {   //bener semua yg kompas              
            // Parse the news item's title.
                foreach ($element->find('h2') as $title) {
                    $newItem->set_title($title->innertext);
                    $judul = $newItem->get_title(); 
                }
                foreach ($element->find('.kcm-read-text') as $text) {
                    $newItem->set_description($text->innertext);
                    $term = $newItem->get_description();    
                }
            }
            // echo "$judul<br><br>";
            // echo "$term";
        }
        elseif ($sumber == "liputan") {
            foreach($html->find('.inner-container-article') as $element) {                  
                // Parse the news item's title.
                foreach ($element->find('h1') as $title) {
                    $newItem->set_title($title->innertext);
                    $judul = $newItem->get_title(); 
                }
                foreach ($element->find('.article-content-body__item-content p') as $text) {
                    $newItem->set_description($text->innertext);
                    $term = $newItem->get_description();    
                }
            }
            // echo "$judul<br><br>";
            // echo "$term";
        }
        elseif ($sumber == "tribunnews") {
            foreach($html->find('.pos_rel') as $element) {    // sudah benar              
                // Parse the news item's title.
                foreach ($element->find('h1') as $title) {
                    $newItem->set_title($title->innertext);
                    $judul = $newItem->get_title(); // sudah benar
                }
                foreach ($element->find('.side-article txt-article') as $text) {
                    $newItem->set_description($text->innertext);
                    $term = $newItem->get_description();    
                }
            }
            // echo "$judul<br><br>";
            // echo "$term";
        }
        else {
            echo "SALAH INPUT!!! WEBSITE TIDAK DITEMUKAN!!!";
        }
    }   
?>

<?php $awal = microtime(TRUE); ?>
<div class="container">
    <div class="page-header">
        <h1>Classification Process</h1>
    </div>
     <form action="#" method="post" id="form1">
        <label for="curl" class="sr-only">CURL</label>
        <input type="text" name="curl" class="form-control" value = "<?php echo $url;?>" required autofocus><br>
        <input type='radio' name='sumber' value='detik' required/>Detik</label>&nbsp;&nbsp;
        <input type='radio' name='sumber' value='kompas' required/>Kompas</label>&nbsp;&nbsp;
        <input type='radio' name='sumber' value='liputan' required/>Liputan 6</label>&nbsp;&nbsp;
        <input type='radio' name='sumber' value='viva' required/>Viva News</label>&nbsp;&nbsp;
        <input type='radio' name='sumber' value='tribunnews' required/>Tribun News</label>&nbsp;&nbsp;
        <br> <br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Process</button>
    </form>
    <br>
    <div class="col-md-12">
        <div class="row" style="padding: 3px; overflow: auto; width: 100; height: 300px; border: 1px solid grey">
            &nbsp; <b>Result of Scrapping News Articles</b><hr>
            <table class="table table-bordered" font="center" align="center">
                <?php                     
                    $term = strip_tags($term);
                    $judul = strip_tags($judul);
                    mysql_query("INSERT INTO berita_training VALUES ('','$judul','$term','','')");
                    echo "<h2><u>$judul</u></h2>"; 
                    echo "$term";
                ?>
            </table>
        </div><br><br>
    </div>
    <br>
    <!-- <div class = "row"> -->
        <div class="col-md-5">
            <div class="row" style="padding: 3px; overflow: auto; width: 100; height: 300px; border: 1px solid grey">
                &nbsp; <b>Result of KNN Classification</b><hr>
                <table class="table table-bordered" font="center" align="center">
                    <?php
                        $data = $ir->casefolding(strip_tags($term));
    
                        foreach ($data as $keyCase => $valCase) {$naive = $nb->nbProses();
                            $data[$keyCase] = $valCase;
                            foreach ($ir->tokenizing($valCase) as $keyToken => $valToken) {
                                $data[$keyToken] = $valToken;
                                foreach ($ir->filtering($valToken) as $keyFilter => $valFilter) {
                                    $data[$keyFilter] = $valFilter;
                                    $hasil_stem = $stem->stemming($data[$keyFilter]);
                                    // echo $hasil_stem."<br>";
                                    $tf = $ir->tf_idf($hasil_stem);
                                }
                            }
                        }
                        $w = $ir->hitungBobot();

                        $awal_knn = microtime(TRUE);
                        
                        $wdiWdUji = $knn->knnProses();

                        $akhir_knn = microtime(TRUE);
                        $lama_knn = $akhir_knn - $awal_knn;
                        echo "<br>Time to process of categorization with KNN method is <b>$lama_knn second </b>";
                    ?>
                </table>
            </div>
        </div><br><br>
        <div class = "col-md-2">

        </div>
        <div class="col-md-5">
            <div class="row" style="padding: 3px; overflow: auto; width: 100; height: 300px; border: 1px solid grey">
                &nbsp; <b>Result of Naive Bayes Classification</b><hr>
                <table class="table table-bordered" font="center" align="center">
                    <?php
                        $awal_nb = microtime(TRUE);

                        $naive = $nb->nbProses();

                        $akhir_nb = microtime(TRUE);
                        $lama_nb = $akhir_nb - $awal_nb;
                        echo "<br>Time to process of categorization with Naive Bayes method is <b>$lama_nb second </b>";
                    ?>
                </table>
            </div>
        </div><br><br>
    </div><br><br>
    <?php
        $akhir = microtime(TRUE);
        $lama = $akhir - $awal;
        echo "<br><h4>Time to process of categorization with  is <b>$lama second </b></h4>";
    ?>
    <hr>
    <center>
        <footer>
            <p>&copy; Siti Nurpadilah (1127050148).</p>
        </footer>
    </center>
</div>
     