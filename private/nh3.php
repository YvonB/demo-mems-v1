<?php
    use google\appengine\api\users\User;
    use google\appengine\api\users\UserService;
    // On crée une session, pour pouvoir utiliser les sessions. De sorte à ce connecter au session.  
    $user = UserService::getCurrentUser();

    // on a pas le droit de voir index si on était pas connecter au préalable.
    if(!$user)
    {   

        header("Location: /"); // on rédirige vers l'accueil et 
        exit; // On arrête tout.
    }

    // Pour notre lib
    require_once('../vendor/autoload.php');

    // Chercher les dernières valeurs insérées
    $obj_repo = new \GDS\Demo\Repository();
    $arr_posts = $obj_repo->getRecentPosts();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Détéction de Pollution</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- css global -->
    <link rel="stylesheet" href="/css/demo.css"> 
    <link rel="stylesheet" href="/css/slide.css"> <!-- pour l'utilisation de #slider ect -->

    <meta name="author" content="Yvon Benahita">
    <link rel="icon" type="image/png" href="/img/datastore-logo.png" />
	<!-- script pour la courbe -->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.highcharts.com/stock/highstock.js"></script>
	<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
	<!-- ********************** -->

    <!-- font awesome-->
    <link rel="stylesheet" href="css/font-awesome/font-awesome.css">
</head>

<body>
    <!--************************ Début Navigation ************************************-->
    <header>
        <nav class="navbar navbar-default navbar-fixed-top colornav">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand colortextnav" href="/"><b>SDP - IoT</b></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li class="active colortextnav"><a href="#"><b>NH3</b><span class="sr-only">(current)</span></a></li>
                <!-- <li class="colortextnav"><a href="#">Link</a></li>
                <li class="dropdown colortextnav">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">One more separated link</a></li>
                  </ul>
                </li> -->
              </ul>
              <form class="navbar-form navbar-left">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default"><b>Chercher</b></button>
              </form>
              <ul class="nav navbar-nav navbar-right colortextnav">
                <li><a href="/home"><b><i class="fa fa-home" style="margin-right: 4px;"></i>Back Home</b></a></li>
                <li><a href="/home/co2">Gaz Carbonique</a></li>
                <li><a href="/home/co">Monoxyde de Carbone</a></li>
                <!-- <li><a href="#">Amoniaque</a></li> -->
                <li class="dropdown colortextnav">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><?php echo htmlspecialchars($user->getNickname());?></b><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <!-- <li><a href="#"><b>Voir l'état de CO2</a></b></li>
                    <li><a href="#"><b>Voir l'état de CO</a></b></li>
                    <li><a href="#"><b>Voir l'état de NH3</a></b></li>
                    <li role="separator" class="divider"></li> -->
                    <li><a href="/login"><button type="submit" class="btn btn-primary" align="center">Se Deconnecter</button></a></li>
                  </ul>
                </li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
    </header>
    <!--****************************** Fin Navigation *****************************-->

<div class="container">  <!-- Pour tout le contenu de notre site -->

<!-- ===========================Le logo et le titre============================ -->
    <div class="row">
        <div class="col-md-12">
            <h1><img src="/img/datastore-logo.png" id="gds-logo" /> PHP & <span class="hidden-xs">Google</span> Cloud Datastore</h1>
        </div>
    </div>
<!-- ====================================================================== -->

    <div align="center">  
        <h4>Amoniaque</h4>
         <div class="mon_slide">
            <div id="slider">
                <div id="nh3" style="height: 400px; min-width: 310px">
                    
                </div>
            </div>
        </div>
    </div> 

    <!-- pour récupérer les valeurs dans la BD -->
    <?php
        foreach ($arr_posts as $obj_post)
        {
            $data_nh3[] = $obj_post->nh3;
        }
    ?>
    
    <!-- le script de la courbe lui même -->
    <script type="text/javascript">
        $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=new-intraday.json&callback=?', function (data) 
        {

            // create the chart
            Highcharts.stockChart('nh3', {


                title: {
                    text: 'Valeurs d\'Amoniaque par minute'
                },

                subtitle: {
                    text: 'En partie par million (ppm)'
                },

                xAxis: {
                    gapGridLineWidth: 0
                },

                rangeSelector: {
                    buttons: [{
                        type: 'hour',
                        count: 1,
                        text: '1h'
                    }, {
                        type: 'day',
                        count: 1,
                        text: '1j'
                    }, {
                        type: 'all',
                        count: 1,
                        text: 'Tous'
                    }],
                    selected: 1,
                    inputEnabled: false
                },

                series: [{
                    name: 'NH3',
                    type: 'area',
                    data: [<?php echo join($data_nh3, ',') ?>],
                    gapSize: 5,
                    tooltip: {
                        valueDecimals: 2
                    },
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    threshold: null
                }]
            });
});

    </script>
<!-- ************************************************************************ -->

</div> <!-- fin de container de la page --> 

   <!-- ********************************* Footer ***************************************** -->
    <footer>
    <!--footer-->
        <footer class="footer1">
        <div class="container">

            <div class="row"><!-- row --> 

                <!-- 1er colonne          -->
                <div class="col-lg-3 col-md-3"><!-- widgets column left -->
                    <ul class="list-unstyled clear-margins"><!-- widgets -->              
                        <li class="widget-container widget_nav_menu"><!-- widgets list -->     
                            <h1 class="title-widget">Useful links</h1>           
                                <ul>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i> About Us</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i> Contact Us</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i> Success Stories</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i> LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i> LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                </ul>    
                        </li> <!-- end list  -->              
                    </ul>
                </div><!-- widgets column left end -->
            
                <!-- 2ème colonne -->
                <div class="col-lg-3 col-md-3"><!-- widgets column left -->
                    <ul class="list-unstyled clear-margins"><!-- widgets -->
                        <li class="widget-container widget_nav_menu"><!-- widgets list -->
                            <h1 class="title-widget">Useful links</h1>
                                <ul>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a  href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a  href="#" target="_blank"><i class="fa fa-angle-double-right"></i> LOREM IPSUM</a></li>
                                </ul>
                        </li>   
                    </ul>     
                </div><!-- widgets column left end -->
                 
                <!-- 3éme colonne -->
                <div class="col-lg-3 col-md-3"><!-- widgets column left -->
                    <ul class="list-unstyled clear-margins"><!-- widgets -->
                        <li class="widget-container widget_nav_menu"><!-- widgets list -->
                            <h1 class="title-widget">Useful links</h1>            
                                <ul>
                                    <li><a href="#"><i class="fa fa-angle-double-right"></i> LOREM IPSUM</a></li>
                                    <li><a href="#"><i class="fa fa-angle-double-right"></i> LOREM IPSUM</a></li>
                                    <li><a href="#"><i class="fa fa-angle-double-right"></i> LOREM IPSUM</a></li>
                                    <li><a href="#"><i class="fa fa-angle-double-right"></i> LOREM IPSUM</a></li>
                                    <li><a href="#"><i class="fa fa-angle-double-right"></i> LOREM IPSUM</a></li>
                                    <li><a href="#"><i class="fa fa-angle-double-right"></i> LOREM IPSUM</a></li>
                                    <li><a href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                    <li><a href="#"><i class="fa fa-angle-double-right"></i>  LOREM IPSUM</a></li>
                                </ul>
                        </li>              
                    </ul>    
                </div><!-- widgets column left end -->

                <!-- 4éme colonne  -->
                <div class="col-lg-3 col-md-3"><!-- widgets column center -->
                    <ul class="list-unstyled clear-margins"><!-- widgets -->
                        <li class="widget-container widget_recent_news"><!-- widgets list -->
                            <h1 class="title-widget">Contact Detail </h1>
                                <!-- 1° Contact Rapide -->
                                <div class="footerp"> 
                                    <h2 class="title-median">Web Developper Junior</h2>
                                    <p><b>Email id:</b> <a href="mailto:yvonbenahita@gmail.com">yvonbenahita@gmail.com</a></p>
                                    <p><b>Helpline Numbers </b>
                                    <b style="color:#ffc106;">(8AM to 10PM):</b>  +91-8130890090, +91-8130190010  </p>
                                    <p><b>Corp Office / Postal Address</b></p>
                                    <p><b>Phone Numbers : </b>7042827160, </p>
                                    <p> 011-27568832, 9868387223</p>
                                </div>
                                <!-- 2° Réseaux Sociaux -->
                                <div class="social-icons">
                                    <ul class="nomargin">
                                        <a href="https://www.facebook.com/"><i class="fa fa-facebook-square fa-3x social-fb" id="social"></i></a>
                                        <a href="https://twitter.com/"><i class="fa fa-twitter-square fa-3x social-tw" id="social"></i></a>
                                        <a href="https://plus.google.com/"><i class="fa fa-google-plus-square fa-3x social-gp" id="social"></i></a>
                                        <a href="mailto:yvonbenahita@gmail.com"><i class="fa fa-envelope-square fa-3x social-em" id="social"></i></a>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                     </div>
            </div> <!--end row -->
        </div>
    </footer>
    
    <!-- copyright -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="copyright">
                         © 2017, YY, All rights reserved
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="design">
                        <a href="https://github.com/YvonB">Yvon B | Web Developer</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end copyright -->

</footer> 
<!--*********************************** Fin footer **************************************** -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

</body>
</html>