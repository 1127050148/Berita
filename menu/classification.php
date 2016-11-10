<div class="container">
    <div class="page-header">
        <h1>Classification Process</h1>
    </div>
     <form action="?t=resultClassification" method="post" id="form1" name="form1">
        <label for="curl" class="sr-only">CURL</label>
        <input type="text" name="curl" class="form-control" placeholder="Input Link Artikel. Ex: http://news.detik.com/read/2016/07/03/064930/3247612/10/demi-lebaran-bersama-orangtua-firman-rela-10-jam-terperangkap-macet-di-brebes" required autofocus><br>
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
                <?php echo "";?>
            </table>
        </div><br><br>
    </div>
    <br>
    <!-- <div class = "row"> -->
        <div class="col-md-5">
            <div class="row" style="padding: 3px; overflow: auto; width: 100; height: 300px; border: 1px solid grey">
                &nbsp; <b>Result of KNN Classification</b><hr>
                <table class="table table-bordered" font="center" align="center">
                    <!-- isi datanya di sini -->
                </table>
            </div>
        </div><br><br>
        <div class = "col-md-2">

        </div>
        <div class="col-md-5">
            <div class="row" style="padding: 3px; overflow: auto; width: 100; height: 300px; border: 1px solid grey">
                &nbsp; <b>Result of Naive Bayes Classification</b><hr>
                <table class="table table-bordered" font="center" align="center">
                    <!-- isi datanya disini -->
                </table>
            </div>
        </div><br><br>
    </div><br>
    <hr>
    <center>
        <footer>
            <p>&copy; Siti Nurpadilah (1127050148).</p>
        </footer>
    </center>
</div>
     