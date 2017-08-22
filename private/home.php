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

<!-- head -->
<head>
    <meta charset="utf-8">
    <title>Détéction de pollution</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- CSS global -->
    <link rel="stylesheet" href="/css/demo.css">
    <!-- css du slide -->
    <link rel="stylesheet" href="/css/slide.css">
    <!-- fin css slide -->

    <meta name="author" content="Yvon Benahita">
    <link rel="icon" type="image/png" href="/img/datastore-logo.png" />

    <!-- font -->
    <link rel="stylesheet" href="css/font-awesome/font-awesome.css">

    <!-- script pour le Pie -->
     <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
</head>
<!-- end head -->

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
                <li class="active colortextnav"><a href="#"><b><i class="fa fa-home" style="margin-right: 4px;color:black !important;"></i>Home</b><span class="sr-only">(current)</span></a></li>
              </ul>
              <form class="navbar-form navbar-left" style="margin-left: 150px;">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search" style="width: 370px;display: none;">
                </div>
                <button type="submit" class="btn btn-default" style="display: none;"><b>Chercher</b></button>
              </form>
              <ul class="nav navbar-nav navbar-right colortextnav">
                <li><a href="/home/co2">Gaz Carbonique</a></li>
                <li><a href="/home/co">Monoxyde de Carbone</a></li>
                <li><a href="/home/nh3">Amoniaque</a></li>
                <li class="dropdown colortextnav">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user" aria-hidden="true" style="margin-right: 0px;"></i>
                    <b><?php echo htmlspecialchars($user->getNickname());?></b><span class="caret"></span></a>
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

<!-- =====================La définition et la Réssource===================== -->
            <div class="row">
                <div class="col-md-8">
                    <h2>Lorem Ipsum</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla est purus,<br> ultrices in porttitor
                    in, accumsan non quam. Nam consectetur porttitor rhoncus.<br> Curabitur eu est et leo feugiat
                    auctor vel quis lorem.</p>
                    <p>Ut et ligula dolor, sit amet consequat lorem. Aliquam porta eros sed
                    velit imperdiet egestas.</p>
                </div>
                <!-- ============== -->
                <div class="col-md-4">
                    <h2 align="center">Gas not accepted</h2>
                      <div id="container" style="height: 400px"></div> <!-- div pour contenir le Pie -->

                      <?php

                        try
                        {
                        // On crée un objet de type Repository.
                        $obj_repo = new \GDS\Demo\Repository();
                        // Chercher TOUS 'All' les gazs insérées récemment.
                        $arr_posts = $obj_repo->getAllRecentPost();

                        // au début 
                        $nbr_co2_na = 0;
                        $n_co2 = 0;
                        $nbr_co_na = 0;
                        $n_co = 0;
                        $nbr_nh3_na = 0;
                        $n_nh3 = 0;

                        // Compte tous les posts.
                        $nbr = count($arr_posts); // C'est le N dans le livre
                        
                        foreach ($arr_posts as $obj_post) 
                        {
                            if($obj_post->co2 >= 396)// tous les co2 qui dépasse ou égale à 396ppm
                                {   
                                    $nbr_co2_na += 1; // si on est ici c'est qu'il y a des co2 non acceptables, on icremente le nombre $nbr_co2_na alors !
                                    $n_co2 = $nbr_co2_na;
                                    // $co2_na = $obj_post->co2;
                                }
                            if($obj_post->co >= 3) // tous les co qui dépasse ou égale à 3ppm
                                {
                                    // si on est ici c'est qu'il y a des co non acceptables, on icremente le nombre $nbr_co_na alors !
                                    $nbr_co_na += 1;
                                    $n_co = $nbr_co_na;
                                    // $co_na = $obj_post->co;
                                }
                            if($obj_post->nh3 >= 5) // tous les nh3 qui dépasse ou égale à 5ppm
                                    {   
                                        // si on est ici c'est qu'il y a des nh3 non acceptables, on icremente le nombre $nbr_nh3_na alors !
                                        $nbr_nh3_na += 1;
                                        $n_nh3 = $nbr_nh3_na;
                                    }  
                            ?>
                        
                        <?php 

                        }

                        //calculs les %
                        if($nbr != 0) // on évite la division par zéro
                            {   
                                $pource_co2 = ($n_co2*100)/$nbr;
                                $pource_co = ($n_co*100)/$nbr;
                                $pource_nh3 = ($n_nh3*100)/$nbr;
                            }

                        ?>

                        <!-- script pour afficher le Pie -->
                        <script type="text/javascript">
                        Highcharts.chart('container', {
                              chart: {
                                  type: 'pie',
                                  options3d: {
                                      enabled: true,
                                      alpha: 45,
                                      beta: 0
                                  }
                              },
                              title: {
                                  text: 'Know where you find is livable or not'
                              },
                              tooltip: {
                                  pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                              },
                              plotOptions: {
                                  pie: {
                                      allowPointSelect: true,
                                      cursor: 'pointer',
                                      depth: 35,
                                      dataLabels: {
                                          enabled: true,
                                          format: '{point.name}'
                                      }
                                  }
                              },
                              series: [{
                                  type: 'pie',
                                  name: 'Not acceptable',
                                  data: [
                                      ['co2', 45.0],
                                      ['co', 26.8],
                                      {
                                          name: 'nh3',
                                          y: 12.8,
                                          sliced: true,
                                          selected: true
                                      } 
              
                                  ]
                              }]
                        });
                        </script>
                        <!-- end script Pie -->

                        <!-- actu auto du div du Pie -->
                          <script type="text/javascript">
                            $(document).ready(function()
                                {   
                                    $('#container').load('home.php');
                                    refresh();
                                });

                            function refresh() 
                                {   
                                    setTimeout(
                                                function()
                                                    {
                                                       $('#container').load('home.php');
                                                       refresh();     
                                                    }, 1000        // l'actualisation se fait chaque sec 
                                              );
                                }
                            </script>
                        <!-- end actu auto -->

                          <?php
                   }
                    catch(\Exception $obj_ex)
                        {
                            syslog(LOG_ERR, $obj_ex->getMessage());
                            echo '<em>Whoops, something went wrong!</em>';
                        }

                            ?>
                </div> <!-- end coll md 4 -->
            </div> <!-- end row -->
            <!-- =========================================================================== -->

            <!-- ============================= Slide des 03 courbes ======================== -->
                  <!-- 1)HTML -->
                    <div class="mon_slide">
                        <div id="slider">
                          <ul id="slideWrap"> 
                            <li><?php include("div_co2.php") ?></li>
                            <li><?php include("div_co.php") ?></li>
                            <li><?php include("div_nh3.php") ?></li>
                          </ul>
                          <a id="prev" href="#">&#8810;</a>
                          <a id="next" href="#">&#8811;</a>
                        </div>
                    </div>

                    <!-- 2) CSS : slide.css-->

                    <!-- 3) JS -->
                    <script type="text/javascript">

                      var responsiveSlider = function() 
                      {
                          var slider = document.getElementById("slider");
                          var sliderWidth = slider.offsetWidth;
                          var slideList = document.getElementById("slideWrap");
                          var count = 1;
                          var items = slideList.querySelectorAll("li").length;
                          var prev = document.getElementById("prev");
                          var next = document.getElementById("next");

                          window.addEventListener('resize', function() 
                                                              {
                                                                sliderWidth = slider.offsetWidth;
                                                              }
                                                  );

                          var prevSlide = function() 
                                              {
                                                if(count > 1) 
                                                    {
                                                      count = count - 2;
                                                      slideList.style.left = "-" + count * sliderWidth + "px";
                                                      count++;
                                                    }
                                                else if(count = 1) 
                                                    {
                                                      count = items - 1;
                                                      slideList.style.left = "-" + count * sliderWidth + "px";
                                                      count++;
                                                    }
                                              };

                          var nextSlide = function() 
                                              {
                                                if(count < items) 
                                                    {
                                                      slideList.style.left = "-" + count * sliderWidth + "px";
                                                      count++;
                                                    }
                                                else if(count = items) 
                                                    {
                                                      slideList.style.left = "0px";
                                                      count = 1;
                                                    }
                                              };
                          
                          next.addEventListener("click", function() 
                                                              {
                                                                nextSlide();
                                                              }
                                               );

                          prev.addEventListener("click", function() 
                                                              {
                                                                prevSlide();
                                                              }
                                                );
                          
                          setInterval(function() 
                                          {
                                            nextSlide()
                                          }, 8000
                                     );

                    };

                    window.onload = function()
                     {
                      responsiveSlider();  
                     }
                    </script>


            <!-- ================================Fin slide de 3 courbes======================================================== -->

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