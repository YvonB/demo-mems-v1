<?php
// Définitions des constantes modèles pour l'accès au datatore
define('GDS_ACCOUNT', ' !! your service account name here !! ');
define('GDS_KEY_FILE', dirname(__FILE__) . '/key.p12');

define('MASSE_MOLAIRE_CO2', 44);
define('MASSE_MOLAIRE_CO', 28);
define('MASSE_MOLAIRE_NH3', 17);
define('VOLUME_MOLAIRE', 22.4); // une mole de gaz occupe toujours le même volume dans les CNTP


use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

# Looks for current Google account session
$user = UserService::getCurrentUser();

// Inclusion pour notre lib
require_once('../vendor/autoload.php');

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Détéction de pollution</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/demo.css">
    <meta name="author" content="Yvon Benahita">
    <link rel="icon" type="image/png" href="/img/datastore-logo.png" />

    <!-- font awesome -->
    <link rel="stylesheet" href="css/font-awesome/font-awesome.css">
    
    <!-- Pour le Jauge  -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Fin -->

    <!-- jquery du rafraîchissement -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
</head>
<!-- end head -->

<body>
    <!-- ====================================== Benière ====================================== -->
    <div class="container-fluid banner">
        <div class="ban">
            <video id = "video" src="medias/la_pollution.mp4" type="video/mp4" autoplay="autoplay" loop="loop" muted="muted" >   
            </video>
        </div> 

        <div class="inner-banner">
            <h3 class="sub_title_ban"><img src="/img/datastore-logo.png"  class="logo_ban" />Detecteur - Analyseur Web des Gaz polluants SDP - IoT</h3>
            <h1>Know what really exists in the air you breathe</h1>
            <p class="sub_title_ban" id="sous_titre"><b>Follow your health closely</b></p>
            <button type="submit" class="btn btn-primary">
                                    <?php 
                                        if(isset($user)) 
                                            {echo "Go Home<i class='fa fa-arrow-right' style='margin-left: 15px;'></i>";}
                                        else 
                                            {echo "See More Content";}
                                    ?>
            </button>
        </div>
    </div>

<div data-spy="affix" data-offset-top="763.8">
        <nav class="navbar-default colornav"> 

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
              <!-- Page courante -->
              <ul class="nav navbar-nav">
                <li class="active colortextnav"><a href="#"><b>Welcome</b><span class="sr-only">(current)</span></a></li>
              </ul>
              <!-- Recherche -->
              <form class="navbar-form navbar-left" style="margin-left: 150px;">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search" style="width: 370px;">
                </div>
                <button type="submit" class="btn btn-default" style="display: none;"><b>Chercher</b></button>
              </form>

              <ul class="nav navbar-nav navbar-right colortextnav">
                <li class="dropdown colortextnav">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b>Options</b><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="/home/co2"><b>Voir l'état de CO2</a></b></li>
                    <li><a href="/home/co"><b>Voir l'état de CO</a></b></li>
                    <li><a href="/home/nh3"><b>Voir l'état de NH3</a></b></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php 
                                    $login = "/login";
                                    $logout = "/logout";
                                    echo(isset($user) ? $logout : $login );
                                ?>">
                    <button type="submit" class="btn btn-primary" align="center"><?php echo (isset($user) ? "Deconnexion" : "Se Connecter"); ?></button></a></li>
                  </ul>
                </li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
    </nav><!-- end nav -->
</div>
    
    <!-- =================================== end banière ===================================== -->
        
        <div class="container" id="contenu">  <!-- ========= Pour tout le contenu de notre site======== -->

            <!-- =========================== Le logo et le titre ============================ -->
            <div class="row">
                <div class="col-md-12">
                    <h1><img src="/img/datastore-logo.png" id="gds-logo" /> PHP & <span class="hidden-xs">Google</span> Cloud Datastore</h1>
                </div>
            </div>
            <!-- ====================================================================== -->

            <!-- ==================== La définition et le Dashboard ===================== -->
            <div class="row">
                <!-- Définition -->
                <div class="col-md-8">
                    <h2>What is it ?</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla est purus,<br> ultrices in porttitor
                    in, accumsan non quam. Nam consectetur porttitor rhoncus.<br> Curabitur eu est et leo feugiat
                    auctor vel quis lorem.</p>
                    <p>Ut et ligula dolor, sit amet consequat lorem. Aliquam porta eros sed
                    velit imperdiet egestas.</p>
                </div>
                <!-- Dadhboard -->
                <div class="col-md-4" >
                    <h3>Counter Of Gases not acceptable</h3>
                    <div id="chart_div" style="width: 400px; height: 120px;">

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

                <!-- script pour afficher les pourcentages de gazs non acceptable sur les compteurs -->
                            <script type="text/javascript">
                                google.charts.load('current', {'packages':['gauge']});
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() 
                                {
                                    // des valeurs aléatoires au chargement de la page
                                    var data = google.visualization.arrayToDataTable([
                                      ['Label', 'Value'],                             
                                      ['CO2', <?php echo rand(0, 100); ?>],
                                      ['CO', <?php echo rand(0, 100); ?>],
                                      ['NH3', <?php echo rand(0, 100); ?>]
                                    ]);

                                    var options = {
                                      width: 400, height: 120,
                                      redFrom: 90, redTo: 100,
                                      yellowFrom:75, yellowTo: 90,
                                      minorTicks: 10
                                    };

                                    var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

                                    chart.draw(data, options);

                                    setInterval(function() {
                                      data.setValue(0, 1, 0 + <?php echo $pource_co2; ?>);
                                      chart.draw(data, options);
                                    }, 4000);
                                    setInterval(function() {
                                      data.setValue(1, 1, 0 + <?php echo $pource_co; ?>);
                                      chart.draw(data, options);
                                    }, 4000);
                                    setInterval(function() {
                                      data.setValue(2, 1, 0 + <?php echo $pource_nh3; ?>);
                                      chart.draw(data, options);
                                    }, 4000);
                                }                    
                            </script>
                    <!-- ================= Fin script affich ===================== % -->
                            
                        <!-- actualisation automatique,SEULEMENT le div des compteurs-->
                            <script type="text/javascript">
                            $(document).ready(function()
                                {   
                                    $('#chart_div').load('main.php');
                                    refresh();
                                });

                            function refresh() 
                                {   
                                    setTimeout(
                                                function()
                                                    {
                                                       $('#chart_div').load('main.php');
                                                       refresh();     
                                                    }, 1000        // l'actualisation se fait chaque sec 
                                              );
                                }
                            </script>
                        <!-- ======================= fin actu auto ===================== -->

                            <?php
                        }
                        catch(\Exception $obj_ex)
                        {
                            syslog(LOG_ERR, $obj_ex->getMessage());
                            echo '<em>Whoops, something went wrong!</em>';
                        }

                            ?>
                    </div>
                </div>
            </div>
        <!-- ========================================================================== -->

        <!-- ============================== Le Map ==================================== -->
            <div>
                <h2>Where are our sensors?</h2>
                <div class="my_map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d963367.6427555117!2d46.800975397000194!3d-19.40571407254446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x21fa8238a95a8965%3A0xe11f2e914a20ec99!2sEcole+Sup%C3%A9rieur+Polytechnique+d&#39;Antananarivo!5e0!3m2!1sfr!2sfr!4v1501594670727" width="675" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        <!-- =============================== Fin Map ================================== -->


<!-- ====== actualisation automatique,SEULEMENT le div des valeurs en mg/m3  ============ -->
                            <script type="text/javascript">
                            $(document).ready(function()
                                {   
                                    $('#mg_m3').load('main.php');
                                    refresh();
                                });

                            function refresh() 
                                {   
                                    setTimeout(
                                                function()
                                                    {
                                                       $('#mg_m3').load('main.php');
                                                       refresh();     
                                                    }, 1000        // l'actualisation se fait chaque seconde 
                                              );
                                }
                            </script>
<!-- ======================= fin actu auto ===================== --> 

<!-- ========================== Tableau des dernièrs valeurs en mg/m3 ========================== -->
<div class="brute" id="mg_m3">
<h2>Notifications</h2>

<!-- Calculs -->

<?php
try
{   
    // ========Appel de notre modèle

    // On crée un objet de type Repository.
    $obj_repo = new \GDS\Demo\Repository();
    // Chercher juste les dernières valeurs insérées récemment.
    $arr_posts = $obj_repo->getLatestRecentPost();

    // =========fin appel de notre modèle

   // val ppm
    if(isset($arr_posts->co2) AND isset($arr_posts->co) AND isset($arr_posts->nh3))
       {
        $ppm_co2 = $arr_posts->co2;
        $ppm_co = $arr_posts->co;
        $ppm_nh3 = $arr_posts->nh3;

        // masse masseVolumique_co2 avec 3 après la virgule comme précision
        $masseVolumique_co2 = round(($ppm_co2 * (MASSE_MOLAIRE_CO2/VOLUME_MOLAIRE)), 3, PHP_ROUND_HALF_UP);
        $masseVolumique_co = round(($ppm_co * (MASSE_MOLAIRE_CO/VOLUME_MOLAIRE)), 3, PHP_ROUND_HALF_UP);
        $masseVolumique_nh3 = round(($ppm_nh3 * (MASSE_MOLAIRE_NH3/VOLUME_MOLAIRE)), 3, PHP_ROUND_HALF_UP);
       }    
}
catch(\Exception $obj_ex)
{
    syslog(LOG_ERR, $obj_ex->getMessage());
    echo '<em>Whoops, something went wrong!</em>';
}
?>
<!-- fin claculs -->

    <div class="promos">  
        <div class="promo"  style="background: #000000b7">
          <div class="deal">
            <span>CO2</span>
            <span>Lorem ipsum lorem ipsum</span>
          </div>
          <span class="price" style="background-color: <?php
                                                             if($ppm_co2 <= 396)
                                                                {
                                                                    echo '#41c12d'; // vert
                                                                }
                                                                elseif($ppm_co2 > 396 AND $ppm_co2 <= 496)
                                                                {
                                                                    echo '#f08e13'; // jaune orange
                                                                }
                                                                elseif($ppm_co2 > 496) 
                                                                {
                                                                   echo '#d01f1f'; // rouge == danger
                                                                }


                                                        ?>"><?php if(isset($masseVolumique_co2)) echo $masseVolumique_co2." ".'<em>mg/m3</em>'; else echo "(^_^)";?></span>
          <ul class="features">
            <li class="li_brute">Lorem Ipsum ipsum</li>
            <li class="li_brute">Another lorem ipsum</li>
            <li class="li_brute">Lorem ipsum...</li>   
          </ul>
          <button class="sign_up">See More</button>
        </div>
        <div class="promo scale" style="background: #000000b7">
          <div class="deal">
            <span>CO</span>
            <span>Lorem ipsum lorem ipsum</span>
          </div>
          <span class="price" style="background-color: <?php
                                                             if($ppm_co <= 3)
                                                                {
                                                                    echo '#41c12d'; // vert
                                                                }
                                                                elseif($ppm_co > 3 AND $ppm_co <= 4 )
                                                                {
                                                                    echo '#f08e13'; // jaune orange
                                                                }
                                                                elseif($ppm_co > 5) 
                                                                {
                                                                   echo '#d01f1f'; // rouge == danger
                                                                }


                                                        ?>"><?php if(isset($masseVolumique_co)) echo $masseVolumique_co." ".'<em>mg/m3</em>'; else echo "(^_^)";?></span>
          <ul class="features">
            <li class="li_brute">Lorem Ipsum ipsum</li>
            <li class="li_brute">Another lorem ipsum</li>
            <li class="li_brute">Lorem ipsum...</li>   
          </ul>
          <button class="sign_up">See More</button>
        </div>
        <div class="promo" style="background: #000000b7">
          <div class="deal">
            <span>NH3</span>
            <span>Lorem ipsum lorem ipsum</span>
          </div>
          <span class="price" style="background-color: <?php
                                                             if($ppm_nh3 <= 5)
                                                                {
                                                                    echo '#41c12d'; // vert
                                                                }
                                                                elseif($ppm_nh3 > 5 AND $ppm_nh3 <= 6)
                                                                {
                                                                    echo '#f08e13'; // jaune orange
                                                                }
                                                                elseif($ppm_nh3 > 6) 
                                                                {
                                                                   echo '#d01f1f'; // rouge == danger
                                                                }


                                                        ?>"><?php if(isset($masseVolumique_nh3)) echo $masseVolumique_nh3." ".'<em>mg/m3</em>'; else echo "(^_^)";?></span>
          <ul class="features">
            <li class="li_brute">Choose the lorem ipsum</li>
            <li class="li_brute">We need lorem ipsum</li>
            <li class="li_brute">Lorem ipsem...</li>   
          </ul>
          <button class="sign_up">See More</button>
        </div> 
    </div>    
</div>
<!-- ========================== fin Tab Dèr=============================== -->


        <!-- ========================== Espace connexion ============================== -->
            <div class="row">
                <div class="col-md-12">
                    <h2>See more content</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" id="login_btn">
                    <div class="well">
                        <form method="POST" action="/login">
                            <button type="submit" class="btn btn-primary" align="center">
                                <?php 
                                    if(isset($user)) 
                                        {echo "Go Home<i class='fa fa-arrow-right' style='margin-left: 15px;'></i>";}
                                    else 
                                        {echo "Se Connecter";}
                                ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <!-- ====================== Fin Espace Connexion ============================== -->

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