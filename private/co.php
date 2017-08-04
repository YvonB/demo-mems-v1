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
    <link rel="stylesheet" href="../public/css/demo.css">
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
                <li><a href="/index.php/co2">Gaz carbonique</a></li>
                <li><a href="#">Monoxyde de carbone</a></li>
                <li><a href="/index.php/nh3">Amoniaque</a></li>
               
            </ul>
        </nav>
    </header>
    
    <div class="bienvenue" align="center">
        <p><?php echo 'Bienvenu '.'<b>'.$_SESSION['signin'].'</b>'.' ;) - <a href="/deconnexion">Déconnexion</a>'?></p>
    </div>

    <div align="center">  
        <h3>Monoxyde de Carbone</h3>
        <div id="co" style="height: 400px; min-width: 310px">
            
        </div>
    </div>

    <!-- pour récupérer les valeurs dans la BD -->
    <?php
        foreach ($arr_posts as $obj_post)
        {
            $data_co[] = $obj_post->co;
        }
    ?>
    
    <!-- le script de la courbe lui même -->
    <script type="text/javascript">
        $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=new-intraday.json&callback=?', function () 
        {

            // create the chart
            Highcharts.stockChart('co', {


                title: {
                    text: 'Valeurs de Monoxyde de carbone par minute'
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
                    name: 'CO',
                    type: 'area',
                    data: [<?php echo join($data_co, ',') ?>],
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
<!-- ***************************************** -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

</body>
</html>