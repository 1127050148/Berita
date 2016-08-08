<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Kid News</title>

    <!-- Bootstrap -->
    <link href="http://localhost/Berita/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="http://localhost/Berita/assets/css/carousel.css">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    	body {
			padding-top: 50px;
		}
		.starter-template {
			padding: 40px 15px;
			text-align: center;
		}
    </style>
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
          <a class="navbar-brand" href="#">Kid News Home</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="?t=home">Home</a></li>
            <li><a href="?t=classification">Classification</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">News <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="?t=politics">Politics</a></li>
                    <li><a href="?t=events">Events</a></li>
                    <li><a href="?t=general">General</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Source<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="?t=detik">Detik</a></li>
                    <li><a href="?t=kompas">Kompas</a></li>
                    <li><a href="?t=liputan6">Liputan 6</a></li>
                    <li><a href="?=merdeka">Merdeka</a></li>
                    <li><a href="?=viva">Viva News</a></li>
                </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Keyword" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Find</button>
          </form>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="http://localhost/Berita/image/335993_620.jpg" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Example headline.</h1>
              <p>Note: If you're viewing this page via a <code>file://</code> URL, the "next" and "previous" Glyphicon buttons on the left and right might not load/display properly due to web browser security rules.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
            </div>
          </div>
        </div>
   
      <div class="item">
          <img class="second-slide" src="http://localhost/Berita/image/039674600_1447239646-20151111-TKI-Jakarta-Angga-Yuniar8.jpg" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Another example headline.</h1>
              <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="http://localhost/Berita/image/Berita-Politik-Hari-Ini-PPP-Keluar-Dari-KMP-Koalisi-Merah-Putih.jpg" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>One more for good measure.</h1>
              <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.carousel -->
      
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
							else if (isset($_GET['t']) && $_GET['t'] == 'politics') {
						  		include 'menu/politics.php';
							} 
							else if (isset($_GET['t']) && $_GET['t'] == 'events') {
								include 'menu/events.php';
							} 
							else if (isset($_GET['t']) && $_GET['t'] == 'general') {
								include 'menu/general.php';
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
							else if (isset($_GET['t']) && $_GET['t'] == 'merdeka') {
								include 'menu/merdeka.php';
							}
							else if (isset($_GET['t']) && $_GET['t'] == 'viva') {
								include 'menu/viva.php';
							}
							else if (isset($_GET['t']) && $_GET['t'] == 'details') {
								include 'detail.php';
							}
							else {
    							include 'home.php';
							}
						?>
                    </div>
                </div>
      <hr>
      <footer>
        <p>&copy; Kid News, Inc.</p>
      </footer>
    </div> <!-- /container -->

	<!-- jQuery (necessary for Bootstrap'a JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<!-- include all complied plugins (below), or include individual files as needed -->
	<script src="http://localhost/Berita/assets/js/bootstrap.min.js"></script>
</body>
</html>