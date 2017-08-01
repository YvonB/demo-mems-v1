<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Détéction de Pollution</title>

	<!-- script pour la courbe -->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.highcharts.com/stock/highstock.js"></script>
	<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
	<!-- ********************** -->
	<style type="text/css">
		.highcharts-credits
		{display: none;}
	</style>
</head>
<body>

	<!-- div permettant de visualiser la courbe -->
	<div id="container" style="height: 400px; min-width: 310px">
		
	</div>

    <!-- pour récupérer les valeurs dans la BD -->
    <?php
        // Inclusion
        require_once('../vendor/autoload.php');

        // Chercher les dernières valeurs insérées
        $obj_repo = new \GDS\Demo\Repository();
        $arr_posts = $obj_repo->getRecentPosts(); 

        foreach ($arr_posts as $obj_post)
        {
            $data[] = $obj_post->co2;
        }
    ?>
	
	<!-- le script de la courbe lui même -->
	<script type="text/javascript">
		$.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=new-intraday.json&callback=?', function () 
		{

            // create the chart
            Highcharts.stockChart('container', {


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
                    data: [<?php echo join($data, ',') ?>],
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