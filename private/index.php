<?php
    // Inclusion
    require_once('../vendor/autoload.php');

    // Chercher les dernières valeurs insérées
    $obj_repo = new \GDS\Demo\Repository();
    $arr_posts = $obj_repo->getRecentPosts(); 

    // session
    session_start();
    $_SESSION['signin'] = $_GET['user'];
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
    
    <div class="bienvenue">
        <?php echo 'Bienvenu '.$_SESSION['signin'].'!'?>
    </div>

	<!-- div permettant de visualiser la courbe -->
    <div align="center">  
        <h3>Gaz carbonique</h3>
    	<div id="co2" style="height: 400px; min-width: 310px">
    		
    	</div>
    </div> 
    <div align="center">  
        <h3>Monoxyde de Carbone</h3>
        <div id="co" style="height: 400px; min-width: 310px">
            
        </div>
    </div>
    <div align="center">  
        <h3>Amoniaque</h3>
        <div id="nh3" style="height: 400px; min-width: 310px">
            
        </div>
    </div> 
    <!-- pour récupérer les valeurs dans la BD -->
    <?php
        foreach ($arr_posts as $obj_post)
        {
            $data_co2[] = $obj_post->co2;
        }
    ?>
	
	<!-- le script de la courbe lui même -->
	<script type="text/javascript">
		$.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=new-intraday.json&callback=?', function () 
		{

            // create the chart
            Highcharts.stockChart('co2', {


                title: {
                    text: 'Valeurs de Gaz Cabonique par minute'
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
                    name: 'CO2',
                    type: 'area',
                    data: [<?php echo join($data_co2, ',') ?>],
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
<!-- pour récupérer les valeurs dans la BD -->
    <?php
        foreach ($arr_posts as $obj_post)
        {
            $data_nh3[] = $obj_post->nh3;
        }
    ?>
    
    <!-- le script de la courbe lui même -->
    <script type="text/javascript">
        $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=new-intraday.json&callback=?', function () 
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
<!-- ***************************************** -->

</body>
</html>