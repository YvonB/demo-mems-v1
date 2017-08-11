<?php    
    // Pour notre lib
    require_once('../vendor/autoload.php');

    // Chercher les dernières valeurs insérées
    $obj_repo = new \GDS\Demo\Repository();
    $arr_posts = $obj_repo->getRecentPosts();
?>
<html>
<head>
	<!-- script pour la courbe -->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.highcharts.com/stock/highstock.js"></script>
	<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
	<!-- ********************** -->
</head>	
</html>
<div align="center">  
        <h4>Monoxyde de Carbone</h4>
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
		$.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=new-intraday.json&callback=?', function (data) 
		{

            // create the chart
            Highcharts.stockChart('co', {


                title: {
                    text: 'Valeurs du Monoxyde de Carbone par minute'
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