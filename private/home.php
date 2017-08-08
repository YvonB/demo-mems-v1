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
    <!-- <meta http-equiv = "refresh" content = "<?php echo $sec ?> ; URL ='<?php echo $page ?>' " charset="utf-8"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/demo.css">
    <meta name="author" content="Yvon Benahita">
    <link rel="icon" type="image/png" href="/img/datastore-logo.png" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
              <a class="navbar-brand colortextnav" href="#"><b>SDP-IoT</b></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li class="active colortextnav"><a href="#"><b>Home</b><span class="sr-only">(current)</span></a></li>
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
                <li><a href="#">Gaz Carbonique</a></li>
                <li><a href="#">Monoxyde de Carbone</a></li>
                <li><a href="#">Amoniaque</a></li>
                <li class="dropdown colortextnav">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b>Options</b><span class="caret"></span></a>
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

            <!-- =====================La définition et la Réssource===================== -->
            <div class="row">
                <div class="col-md-8">
                    <h2>Qu'est-ce que c'est?</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla est purus,<br> ultrices in porttitor
                    in, accumsan non quam. Nam consectetur porttitor rhoncus.<br> Curabitur eu est et leo feugiat
                    auctor vel quis lorem.</p>
                    <p>Ut et ligula dolor, sit amet consequat lorem. Aliquam porta eros sed
                    velit imperdiet egestas.</p>
                </div>
                <!-- ============== -->
                <div class="col-md-4">
                    <h2>Resources</h2>
                    <p><a href="https://github.com/YvonB/demo-mems-v1" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-new-window"></span> Pollution detection demo (Ce site web)</a></p>
                </div>
            </div>
            <!-- ========================================================================== -->
    
    <div class="bienvenue" align="center">
        <h3><?php echo 'Bienvenu '.'<b>'.htmlspecialchars($user->getNickname()).'</b>'?></h3>
    </div>
	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

</body>
</html>