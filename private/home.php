<?php
    // On crée une session, pour pouvoir utiliser les sessions. De sorte à ce connecter au session.  
    session_start();

    // on a pas le droit de voir index si on était pas connecter au préalable.
    if(!isset($_SESSION['signin']))
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
    <link rel="stylesheet" href="/css/demo.css">
    <meta name="author" content="Yvon Benahita">

	<!-- script pour la courbe -->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.highcharts.com/stock/highstock.js"></script>
	<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
	<!-- ********************** -->
</head>

<body>

    <header>
        <nav>
            <ul>
                <li><a href="/home/co2">Gaz carbonique</a></li>
                <li><a href="/home/co">Monoxyde de carbone</a></li>
                <li><a href="/home/nh3">Amoniaque</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="bienvenue" align="center">
        <h3><?php echo 'Bienvenu '.'<b>'.$_SESSION['signin'].'</b>'.' ;) - <a href="/logout">Déconnexion</a>'?></h3>
    </div>
	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

</body>
</html>