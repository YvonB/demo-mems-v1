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

    <!-- CSS -->
    <link rel="stylesheet" href="/css/demo.css">

    <link rel="stylesheet" href="/css/slide.css">  <!-- pour l'utilisation de #slider ect -->

    <meta name="author" content="Yvon Benahita">
    <link rel="icon" type="image/png" href="/img/datastore-logo.png" />

	<!-- script pour la courbe -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
	<!-- ********************** -->

    <!-- font pour home-->
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
                <li class="active colortextnav"><a href="#" style="color:black !important;"><b>CO2</b><span class="sr-only">(current)</span></a></li>
              </ul>
              <form class="navbar-form navbar-left" style="margin-left: 150px;">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search" style="width: 370px;display: none;">
                </div>
                <button type="submit" class="btn btn-default" style="display: none;"><b>Chercher</b></button>
              </form>
              <ul class="nav navbar-nav navbar-right colortextnav">
                <li><a href="/home"><b><i class="fa fa-home" style="margin-right: 4px;"></i>Back Home</b></a></li>
                <li><a href="/home/co">Monoxyde de Carbone</a></li>
                <li><a href="/home/nh3">Amoniaque</a></li>
                <li class="dropdown colortextnav">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user" aria-hidden="true" style="margin-right: 0px;"></i><b><?php echo htmlspecialchars($user->getNickname());?></b><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="/login"><button type="submit" class="btn btn-primary" align="center">Se Deconnecter</button></a></li>
                  </ul>
                </li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
    </header>
    <!--****************************** Fin Navigation *****************************-->


<div class="container" id="contenu">  <!-- Pour tout le contenu de notre site -->

<!-- ===========================Le logo et le titre============================ -->
    <div class="row">
        <div class="col-md-12">
            <h1><img src="/img/datastore-logo.png" id="gds-logo" /> PHP & <span class="hidden-xs">Google</span> Cloud Datastore</h1>
        </div>
    </div>
<!-- ====================================================================== -->

	<!-- div permettant de visualiser la courbe -->
    <div align="center">  
        <h4>Gaz carbonique</h4>
        <div class="mon_slide">
            <div id="slider">
            	<div id="co2" style="min-width: 310px; height: 400px;"></div><!-- div qui va contenir la courbe -->
           </div>
        </div>
    </div> <!-- fin div courbe -->

<!-- pour récupérer les valeurs dans la BD -->
    <?php
        try
            {   
                // ========Appel de notre modèle

                // On crée un objet de type Repository.
                $obj_repo = new \GDS\Demo\Repository();
                // Chercher juste la dernières valeurs insérées récemment.
                $arr_posts = $obj_repo->getLatestRecentPost();

                // =========fin appel de notre modèle

               // val ppm
                if(isset($arr_posts)){$ppm_co2 = $arr_posts->co2;} 
            }
            catch(\Exception $obj_ex)
                {
                    syslog(LOG_ERR, $obj_ex->getMessage());
                    echo '<em>Whoops, something went wrong!</em>';
                }
     
    ?>
<!-- fin récupération -->
	
<!-- le script de la courbe lui même -->
<script type="text/javascript">
   $(document).ready(function () {
    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });

    Highcharts.chart('co2', {
        chart: {
            type: 'spline',
            animation: Highcharts.svg, // Ne pas animer dans l'ancien IE
            marginRight: 10,
            events: {
                load: function () {

                    // Configurer la mise à jour du graphique chaque 4 seconde
                    var series = this.series[0];
                    setInterval(function () {
                        var x = (new Date()).getTime(), // heure actuelle
                            y = <?php echo $ppm_co2 ; ?>; // les valeurs en ppm sur l'axe des abscisses
                        series.addPoint([x, y], true, true);
                    }, 4000);
                }
            }
        },
        title: {
            text: 'Live co2 data'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150
        },
        yAxis: {
            title: {
                text: 'Value in ppm'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                    Highcharts.numberFormat(this.y, 2);
            }
        },
        legend: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        series: [{
            name: 'co2',
            data: (function () {
                var data = [],
                    time = (new Date()).getTime(),
                    i;

                for (i = -19; i <= 0; i += 1) {
                    data.push({
                        x: time + i * 1000,
                        y: <?php echo $ppm_co2 ; ?> // les valeurs en ppm sur l'axe des abscisses
                    });
                }
                return data;
            }())
        }]
    });
});
</script>
<!-- ===================================== fin script ================================ -->

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