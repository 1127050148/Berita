<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>News Classification with KNN and NBC</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        padding-top: 50px;
      }
      .starter-template {
        padding: 40px 15px;
        text-align: center;
      }
    </style>
    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/jquery-1.4.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.fusioncharts.js"></script>
    <!-- jQuery (necessary for Bootstrap'a JavaScript plugins) -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- include all complied plugins (below), or include individual files as needed -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/highcharts.js"></script>
    <script type="text/javascript" src="assets/js/exporting.js"></script>
</head>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">News Classifier</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="?t=home">Home</a></li>
            <li><a href="?t=classification">Classification</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">News <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="?t=political">Political</a></li>
                    <li><a href="?t=sport">Sport</a></li>
                    <li><a href="?t=education">Education</a></li>
                    <li><a href="?t=automotive">Automotive</a></li>
                    <li><a href="?t=daily_news">Daily News</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sources<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="?t=detik">Detik</a></li>
                    <li><a href="?t=kompas">Kompas</a></li>
                    <li><a href="?t=liputan6">Liputan 6</a></li>
                    <li><a href="?t=tribunNews">Tribun News</a></li>
                    <li><a href="?t=vivaNews">Viva News</a></li>
                </ul>
            </li>
            <li><a href="?t=result">Table Result</a></li>
            <li><a href="?t=graphics">Graphics</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div> <!--/.end container -->
    </nav>
    <div class="item">
        <div class="container">
            <div class="clearfloat">
                <div id="top" class="clearfloat">
                    <div id="headline">
                    <div class="title"></div>
                    <div class="meta"></div>
                        <?php
                            if (isset($_GET['t']) && $_GET['t'] == 'home') {
                                include 'home.php';
                            } 
                            else if (isset($_GET['t']) && $_GET['t'] == 'classification') {
                                include 'menu/classification.php';
                            } 
                            else if (isset($_GET['t']) && $_GET['t'] == 'political') {
                                include 'menu/political.php';
                            } 
                            else if (isset($_GET['t']) && $_GET['t'] == 'sport') {
                                include 'menu/sport.php';
                            } 
                            else if (isset($_GET['t']) && $_GET['t'] == 'education') {
                                include 'menu/education.php';
                            }
                            else if (isset($_GET['t']) && $_GET['t'] == 'automotive') {
                                include 'menu/automotive.php';
                            }
                            else if (isset($_GET['t']) && $_GET['t'] == 'daily_news') {
                                include 'menu/daily_news.php';
                            }
                            else if (isset($_GET['t']) && $_GET['t'] == 'detik') {
                                include 'menu/detik.php';
                            }
                            else if (isset($_GET['t']) && $_GET['t'] == 'kompas') {
                                include 'menu/kompas.php';
                            } 
                            else if (isset($_GET['t']) && $_GET['t'] == 'liputan6') {
                                include 'menu/liputan6.php';
                            } 
                            else if (isset($_GET['t']) && $_GET['t'] == 'tribunNews') {
                                include 'menu/tribunNews.php';
                            }
                            else if (isset($_GET['t']) && $_GET['t'] == 'vivaNews') {
                                include 'menu/vivaNews.php';
                            }
                            else if (isset($_GET['t']) && $_GET['t'] == 'graphics') {
                                include 'menu/graphics.php';
                            }
                            else if (isset($_GET['t']) && $_GET['t'] == 'result') {
                                include 'menu/result.php';
                            }
                            else if (isset($_GET['t']) && $_GET['t'] == 'readmore') {
                                include 'readmore.php';
                            }
                            else if (isset($_GET['t']) && $_GET['t'] == 'resultClassification') {
                                include 'menu/resultClassification.php';
                            }
                            else {
                                include 'home.php';
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    
</body>
</html>